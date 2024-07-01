<?php

use Automattic\Jetpack\Sync\Functions;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * payOS Transfer Payment Gateway.
 *
 * Provides a payOS Payment Gateway. Based on code by Mike Pepper.
 *
 * @class       WC_Gateway_CASSO_payOS
 * @extends     WC_Payment_Gateway
 * @version     2.1.0
 * @package     WooCommerce\Classes\Payment
 */
class WC_Gateway_CASSO_payOS extends WC_Payment_Gateway
{

	/**
	 * Array of locales
	 *
	 * @var array
	 */
	public $locale;

	/**
	 * Constructor for the gateway.
	 */
	static private $URL_CREATE_PAYMENT_LINK = "https://api-merchant.payos.vn/v2/payment-requests";
	static private $URL_CONFIRM_WEBHOOK = "https://api-merchant.payos.vn/confirm-webhook";
	static private $WEBHOOK_ROUTE = "verify_payos_webhook";
	static private $default_settings = array(
		'use_payment_gateway'         => 'yes',
		'client_id' => '',
		'api_key' => '',
		'checksum_key' => '',
		'order_status' => array(
			'order_status_after_paid'   => 'wc-completed',
			'order_status_after_underpaid' => 'wc-processing',
		),
		'transaction_prefix' => 'DH',
		'redirect' => 'yes',
		'link_webhook' => 'no',
		'gateway_info' => array(
			'name' => '',
			'account_number' => '',
			'account_name' => '',
			'bank_name' => ''
		)
	);
	static private $BLACK_LIST_PREFIX = array("FT", "TF", "TT", "VQR");
	public function __construct()
	{
		$this->id                 = 'casso-payos';
		$this->icon               = apply_filters('woocommerce_payos_icon', '');
		$this->has_fields         = false;
		$this->icon               = apply_filters('woocommerce_icon_payos', plugins_url('../assets/img/payos_crop.png', __FILE__));
		$this->method_title       = __('Payment by bank transfer (Scan VietQR)', 'casso-payos');
		$this->method_description = __('Take payments by scanning QR code with Vietnamese banking App. Supported by most major banks in Vietnam', 'casso-payos');

		global $wp_session;

		$this->message = '';

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->title        = $this->get_option('title');
		$this->description  = $this->get_option('description');

		//TODO: xóa dòng dưới khi đã sang năm 2023
		if (strpos($this->description, "Hơn 14 ngân hàng Việt Nam") !== false){
			$this->description = str_replace( "Hơn 14 ngân hàng Việt Nam", "Hầu hết ngân hàng Việt Nam", $this->description);
			$this->update_option("description", $this->description);
		}

		$this->developer_id = $this->get_option('developer_id');
		// payOS account fields shown on the thanks page and in emails.

		$this->payos_gateway_settings = self::get_payos_gateway_settings();

		if (isset($_REQUEST['payos_gateway_settings']) && isset($_REQUEST['submit'])) {
			$this->save_settings_and_webhook();
		}

		add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
		add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'save_settings_and_reset_webhook'));
		add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
		// Customer Emails.
		add_action('woocommerce_email_before_order_table', array($this, 'email_instructions'), 10, 3);
		add_action('woocommerce_api_payos/developer', array($this, 'payos_developer_payment_handler'));
		// Customize Order Button
		add_action('woocommerce_after_checkout_validation', array($this, 'check_payos_gateway_settings'), 10, 2 );
		// Code for add tax_code field
		// add_action('woocommerce_after_checkout_billing_form', array($this, 'custom_tax_code_field'));
		// add_action('woocommerce_checkout_update_order_meta', array($this, 'update_tax_code_to_order_meta'));
		
		add_action('woocommerce_api_' . self::$WEBHOOK_ROUTE, array($this, 'verify_payment_handler'));
		add_action('admin_notices', array($this, 'payos_notice_checkout'));

		if (wc_get_checkout_url() == 'https://mitom.devgioi.com/checkout/' || wc_get_checkout_url() == 'http://woo.dev.com/checkout/') {
			add_filter( 'woocommerce_checkout_fields' , array($this, 'remove_checkout_fields')); 
		}
	}

	public function remove_checkout_fields($fields) {
		unset($fields['billing']['billing_company']); 
		unset($fields['billing']['billing_address_2']); 
		unset($fields['billing']['billing_postcode']); 
		unset($fields['order']['order_comments']);
		$fields['billing']['billing_city']['default'] = 'HCM';
		$fields['billing']['billing_address_1']['default'] = 'Khu CNPM ĐHQG, TP.Thu Duc, TP.HCM';
		return $fields; 
	}

	public function payos_notice_checkout() {
		$payos_gateway_settings = self::get_payos_gateway_settings();
		if (!isset($payos_gateway_settings['client_id']) || strlen($payos_gateway_settings['client_id']) == 0
			|| !isset($payos_gateway_settings['api_key']) || strlen($payos_gateway_settings['api_key']) == 0
			|| !isset($payos_gateway_settings['checksum_key']) || strlen($payos_gateway_settings['checksum_key']) == 0)
			{
				echo '<div class="notice notice-warning">
					<p>' . __('payOS has not been set up yet!', 'casso-payos') . '</p>
				</div>';
			}
	}

	public function check_payos_gateway_settings($data, $error) {
		if ($data['payment_method'] === $this->id) {
			$payos_gateway_settings = self::get_payos_gateway_settings();
			if (!isset($payos_gateway_settings['client_id']) || strlen($payos_gateway_settings['client_id']) == 0
				|| !isset($payos_gateway_settings['api_key']) || strlen($payos_gateway_settings['api_key']) == 0
				|| !isset($payos_gateway_settings['checksum_key']) || strlen($payos_gateway_settings['checksum_key']) == 0)
				{
					wc_add_notice(__('payOS has not been set up by the administrator. Please contact the system administrator.', 'casso-payos'), 'error');
				}
		}
	}

	public function save_settings_and_webhook()
	{
		if (is_array($_REQUEST['payos_gateway_settings'])) {
			$create_webhook = self::payos_create_webhook($_REQUEST['payos_gateway_settings']);
			if ($create_webhook) {
				$gateway_info = json_decode($create_webhook, true);
				$this->update_payos_gateway_settings("yes", $gateway_info, $_REQUEST['payos_gateway_settings']);

				$this->message = '<div class="updated notice"><p><strong>' .
									__('Successful webhook registration', 'casso-payos') .
									'</p></strong></div>';
				
			} else {
				$this->update_payos_gateway_settings("no", self::$default_settings['gateway_info']);

				$this->message = 
				'<div class="error notice"><p><strong>' .
				__('Webhook creation failed', 'casso-payos') .
				'</p></strong></div>';
			}
			// Message for use
			$this->message .=
			'<div class="updated notice"><p><strong>' .
			__('Settings saved', 'casso-payos') .
			'</p></strong></div>';
		} else {
			$this->update_payos_gateway_settings("no", self::$default_settings['gateway_info']);
			$this->message =
			'<div class="error notice"><p><strong>' .
			__('Can not save settings! Please refresh this page.', 'casso-payos') .
			'</p></strong></div>';
		}
	}

	public function custom_tax_code_field($checkout) {
		woocommerce_form_field('tax_code', array(
			'type'          => 'text',
			'class'         => array('my-field-class form-row-wide'),
			'label'         => __('Mã số thuế'),
			'placeholder'   => __('Nhập mã số thuế'),
			), $checkout->get_value('tax_code'));
	}

	public function update_tax_code_to_order_meta($order_id) {
		$order = wc_get_order($order_id);
		if (!empty($_POST['tax_code'])) {
			$order->update_meta_data('tax_code',sanitize_text_field($_POST['tax_code']));
			$order->save();
		}
	}

	public function update_payos_gateway_settings($webhook_status, $gateway_info, $gateway_settings = null) {
		if ($webhook_status == 'no') {
			$this->payos_gateway_settings['gateway_info'] = $gateway_info;
		} else {
			$this->payos_gateway_settings = array_merge($this->payos_gateway_settings, $gateway_settings);
			$this->payos_gateway_settings['gateway_info']['account_name'] = $gateway_info['data']['accountName'];
			$this->payos_gateway_settings['gateway_info']['account_number'] = $gateway_info['data']['accountNumber'];
			$this->payos_gateway_settings['gateway_info']['name'] = $gateway_info['data']['name'];
			$this->payos_gateway_settings['gateway_info']['bank_name'] = $gateway_info['data']['shortName'];
		}
		$this->payos_gateway_settings['link_webhook'] = $webhook_status;
		update_option("woocommerce_payos_gateway_settings", $this->payos_gateway_settings);
	}

	static function get_payos_gateway_settings()
	{
		$payos_settings = get_option('woocommerce_payos_gateway_settings', self::$default_settings);
		$payos_settings = wp_parse_args($payos_settings, self::$default_settings);
		return $payos_settings;
	}

	public function save_settings_and_reset_webhook()
	{
		if (is_array($_REQUEST['payos_gateway_settings'])) {
			if ($_REQUEST['payos_gateway_settings']['client_id'] != $this->payos_gateway_settings['client_id']
				|| $_REQUEST['payos_gateway_settings']['api_key'] != $this->payos_gateway_settings['api_key']
				|| $_REQUEST['payos_gateway_settings']['checksum_key'] != $this->payos_gateway_settings['checksum_key']) 
			{
				$_REQUEST['payos_gateway_settings']['link_webhook'] = 'no';
			}

			$_REQUEST['payos_gateway_settings']['transaction_prefix'] = preg_replace('/[^a-zA-Z0-9]/', '', $_REQUEST['payos_gateway_settings']['transaction_prefix']);

			if (strlen($_REQUEST['payos_gateway_settings']['transaction_prefix']) > 3) {
				$_REQUEST['payos_gateway_settings']['transaction_prefix'] = substr($_REQUEST['payos_gateway_settings']['transaction_prefix'], 0, 3);
			}

			if (in_array($_REQUEST['payos_gateway_settings']['transaction_prefix'], self::$BLACK_LIST_PREFIX)) {
				$_REQUEST['payos_gateway_settings']['transaction_prefix'] = self::$default_settings["transaction_prefix"];
			}
			update_option('woocommerce_payos_gateway_settings', $_REQUEST['payos_gateway_settings']);
		}
	}

	/**
	 * Initialise Gateway Settings Form Fields. 
	 * 
	 */
	public function console_log($output, $with_script_tags = true)
	{
		$js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
			');';
		if ($with_script_tags) {
			$js_code = '<script>' . $js_code . '</script>';
		}
		echo $js_code;
	}
	public function init_form_fields()
	{
		$this->form_fields = array(
			'enabled'         => array(
				'title'   => __('Enable/Disable', 'woocommerce'),
				'type'    => 'checkbox',
				'label'   => __('Enable bank transfer', 'woocommerce'),
				'default' => 'true',
			),
			'title'           => array(
				'title'       => __('Title', 'woocommerce'),
				'type'        => 'text',
				'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
				'default'     => __('Payment by bank transfer (Scan VietQR)', 'casso-payos'),
				'desc_tip'    => true,
			),
			'description'     => array(
				'title'       => __('Description', 'woocommerce'),
				'type'        => 'textarea',
				'description' => __('Payment method description that the customer will see on your checkout.', 'woocommerce'),
				'default'     => __('Pay for orders via payOS. Supported by almost Vietnamese banking apps', 'casso-payos'),
				'desc_tip'    => true,
			),
			'payos_gateway_settings' => array(
				'type' => 'payos_gateway_settings',
			)
		);
	}

	/**
	 * Generate account details html.
	 *
	 * @return string
	 */
	public function generate_payos_gateway_settings_html()
	{
		ob_start();
		$settings = self::get_payos_gateway_settings();

		// wp_enqueue_script('jQuery');
		wp_enqueue_script('payos-select2-script', plugins_url('../assets/js/select2.min.js', __FILE__));
		wp_enqueue_style('payos-select2-styles', plugins_url('../assets/css/select2.min.css', __FILE__));
		$queries = array();
		parse_str($_SERVER['QUERY_STRING'], $queries);
		$is_claim_show = false;
		if (isset($queries['claim'])) $is_claim_show = true;
?>
<script>
jQuery(document).ready(function() {
  jQuery("#payos_bank_selector2").select2({
    templateResult: formatOptions
  });
});

function formatOptions(state) {
  if (!state.id) {
    return state.text;
  }
  var $state = jQuery(
    '<span ><img style="vertical-align: middle;" src="' + state.title + '"  width="80px"/> ' + state.text + '</span>'
  );
  return $state;
}
</script>
<?php echo wp_kses_post($this->message) ?>
<input type="hidden" id="action" name="action" value="payos_save_settings">
<input type="hidden" id="payos_nonce" name="payos_nonce" value="<?php echo wp_create_nonce('payos_save_settings') ?>">
<?php if ($this->payos_gateway_settings['use_payment_gateway'] == 'yes') {
			$payment_gateway_config = 
		'
		<tr>
			<th scope="row">' . __('Connection Information', 'casso-payos') . '</th>
			<td class="forminp" id="payos_gateway_settings">
				<button id="payos_info_button" onclick="showDetailGateway()" type="button" style="border: none; cursor:pointer;';
				
				if ($this->payos_gateway_settings['link_webhook'] == 'no')  
					$payment_gateway_config .= 'background-color: #af1818;';
				else 
					$payment_gateway_config .= 'background-color: #15ab64;';
				
				$payment_gateway_config .=	'color: white; border-radius: 5px; padding: 6px; min-width: 125px !important; margin-bottom: 10px;">';

				if ($this->payos_gateway_settings['link_webhook'] == 'no') {
					$payment_gateway_config .= __('No Connection', 'casso-payos') . '</button>';
				}
				
				else {	
					$payment_gateway_config .=  __('Đã kết nối', 'casso-payos') . '</button>
					<div id="payos_gateway_info" style="padding: 20px; background-color: #bdbdbd; color: black; border: 1px solid; max-width: 500px; border-radius: 10px; display: none">
						<ul' ;
						if ($this->payos_gateway_settings['link_webhook'] == 'no') $payment_gateway_config .= ' style="display: none"'; 
						$payment_gateway_config .= '>
							<li><b>' . __('Gateway name', 'casso-payos') . ': </b>' . esc_html($this->payos_gateway_settings['gateway_info']['name']) . '</li>
							<li><b>' . __('Account number', 'casso-payos') . ': </b>' . esc_html($this->payos_gateway_settings['gateway_info']['account_number']) . '</li>
							<li><b>' . __('Account name', 'casso-payos') . ': </b>' . esc_html($this->payos_gateway_settings['gateway_info']['account_name']) . '</li>	
							<li><b>' . __('Bank', 'casso-payos') . ': </b>' . esc_html($this->payos_gateway_settings['gateway_info']['bank_name']) . '</li>	
						</ul>
					</div>';
				} 
			
			$payment_gateway_config .= '
				<input type="text" name="payos_gateway_settings[link_webhook]" value="' . esc_html($this->payos_gateway_settings['link_webhook']) . '" style="display: none" />
				</br>
				<button id="toggle_payos_gateway_settings" onClick="togglePayOSSetting()" style="border: none; text-decoration: underline; color: #2271b1">' . __('Enter information of payOS', 'casso-payos') .'</button>
			</td>
		</tr>
		<tbody id="payos_gateway_settings_group" style="display:none">
			<tr valign="top">
				<th scope="row" class="titledesc">' . esc_html(__('Client ID', 'casso-payos')) . ':</th>
				<td class="forminp" id="payos_gateway_settings">
					<input type="password" value="' . esc_html($this->payos_gateway_settings['client_id']) . '" name="payos_gateway_settings[client_id]" id="payos_client_id"/>
					<input id="show_client_id" onclick="showClientId()" type="button" style="border: none; background-color: #f0f0f1; min-width: 45px !important; color: #056fca; border-radius: 5px; padding: 6px;" value="' . __('Show', 'casso-payos') . '" /> 
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">' . esc_html(__('API Key', 'casso-payos')) . ':</th>
				<td class="forminp" id="payos_gateway_settings">
					<input type="password" value="' . esc_html($this->payos_gateway_settings['api_key']) . '" name="payos_gateway_settings[api_key]" id="payos_api_key"/>
					<input id="show_api_key" onclick="showApiKey()" type="button" style="border: none; background-color: #f0f0f1; min-width: 45px !important; color: #056fca; border-radius: 5px; padding: 6px;" value="' . __('Show', 'casso-payos') . '" /> 
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">' . esc_html(__('Checksum Key', 'casso-payos')) . ':</th>
				<td class="forminp" id="payos_gateway_settings">
					<input type="password" value="' . esc_html($this->payos_gateway_settings['checksum_key']) . '" name="payos_gateway_settings[checksum_key]" id="payos_checksum_key"/>
					<input id="show_checksum_key" onclick="showChecksumKey()" type="button" style="border: none; background-color: #f0f0f1; min-width: 45px !important; color: #056fca; border-radius: 5px; padding: 6px;" value="' . __('Show', 'casso-payos') . '" /> 
					<br>
					<div style="margin-top: 8px">
						<input type="submit" name="submit" id="submit" class="button button-primary" value="' . __('Check connection payOS', 'casso-payos') .'" style="border: none; background-color: #056fca; color: white; border-radius: 5px; min-width: 95px !important;">
						<a href="https://a6e3495e.payos-docs.pages.dev/cong-cu/cong-thanh-toan/huong-dan-lay-client-id-va-api-key" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;" style="margin-left: 5px; border: none; color: #2271b1; text-decoration: underline; line-height: 30px">' . __('Instructions to get Client ID and API Key', 'casso-payos') . '</a>
					</div>
					';		
				$payment_gateway_config .= '
				</td>
			</tr>
		</tbody>
		<tr>
			<th scope="row">' . esc_html(__('Prefix:', 'casso-payos')) . '</th>
			<td id="payos_gateway_settings">
				<input name="payos_gateway_settings[transaction_prefix]" type="text" value="' . esc_html($this->payos_gateway_settings['transaction_prefix']) . '" id="transaction_prefix"> 
				<label for="transaction_prefix" style="font-size: 13px; font-style: oblique;">' . esc_html(__('Maximum 3 characters, no spaces and no special characters. If contained, it will be deleted. Please do not prefix starting with FT, TF, TT, VQR', 'casso-payos')) . '</label>
			</td>
		</tr>
		<tr>
			<th scope="row">' . esc_html(__('Status if payment is successful:', 'casso-payos')) . '</th>
			<td id="payos_gateway_settings">
				<select name="payos_gateway_settings[order_status][order_status_after_paid]" id="order_status_after_paid">';
					foreach ($this->casso_get_order_statuses_after_paid() as $key => $value) {
						if ($key == $this->payos_gateway_settings["order_status"]["order_status_after_paid"])
							$payment_gateway_config .= '<option value="' . esc_attr($key) . '" selected>' . esc_attr($value) . '</option>';
						else 
							$payment_gateway_config .= '<option value="' . esc_attr($key) . '" >' . esc_attr($value) . '</option>';
					}
		$payment_gateway_config .= '</select>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">' . esc_html(__('Status if payment is failed:', 'casso-payos')) . '</th>
			<td  id="payos_gateway_settings">
				<select name="payos_gateway_settings[order_status][order_status_after_underpaid]" id="order_status_after_underpaid">';
					foreach ($this->casso_get_order_statuses_after_underpaid() as $key => $value) {
						if ($key == $this->payos_gateway_settings['order_status']['order_status_after_underpaid'])
							$payment_gateway_config .= '<option value="' . esc_attr($key) . '" selected>' . esc_attr($value) . '</option>';
						else 
							$payment_gateway_config .= '<option value="' . esc_attr($key) . '">' . esc_attr($value) . '</option>';
					}
		$payment_gateway_config .= '</select>
			</td>
		</tr>
		<tr valign="top" style="display: none">
			<th scope="row">' . esc_html(__('Redirect payOS:', 'casso-payos')) . '</th>
			<td  id="payos_gateway_settings">
				<input name="payos_gateway_settings[redirect]" type="hidden" value="no">
				<input name="payos_gateway_settings[redirect]" type="checkbox" value="yes" id="redirect" '; 
				if (!empty($this->payos_gateway_settings['redirect']) && $this->payos_gateway_settings['redirect'] == 'yes') 
					$payment_gateway_config .= 'checked="checked"';
					
		$payment_gateway_config .= '/>
				<label for="payos_gateway_settings[redirect]">' . esc_html(__('On/Off', 'casso-payos')) . '</label>
			</td>
		</tr>
		
		<script type="text/javascript">
			function showDetailGateway() {
				var dots = document.getElementById("payos_gateway_info");
				var button = document.getElementById("payos_info_button");

				if(!dots) return;
				if (dots.style.display === "none") {
					dots.style.display = "block";
					button.innerHTML = "' . __('Show less', 'casso-payos') . '";
				} else {
					dots.style.display = "none";
					button.innerHTML = "';
				if ($this->payos_gateway_settings['link_webhook'] == 'no') 
					$payment_gateway_config .= __('No Connection', 'casso-payos');
				else 
				 	$payment_gateway_config .= __('Đã kết nối', 'casso-payos');  
				$payment_gateway_config .=	'";
				}
			}

			function showClientId() {
				var clientId = document.getElementById("payos_client_id");
				var showClientIdBtn = document.getElementById("show_client_id");
				if (clientId.type === "password") {
					clientId.type = "text";
					showClientIdBtn.value = "' . __('Hide', 'casso-payos') . '";
				} else {
					clientId.type = "password";
					showClientIdBtn.value = "' . __('Show', 'casso-payos') . '";
				}
			}

			function showApiKey() {
				var apiKey = document.getElementById("payos_api_key");
				var showApiKeyBtn = document.getElementById("show_api_key");
				if (apiKey.type === "password") {
					apiKey.type = "text";
					showApiKeyBtn.value = "' . __('Hide', 'casso-payos') . '";
				} else {
					apiKey.type = "password";
					showApiKeyBtn.value = "' . __('Show', 'casso-payos') . '";
				}
			}

			function showChecksumKey() {
				var checksumKey = document.getElementById("payos_checksum_key");
				var showChecksumKeyBtn = document.getElementById("show_checksum_key");
				if (checksumKey.type === "password") {
					checksumKey.type = "text";
					showChecksumKeyBtn.value = "' . __('Hide', 'casso-payos') . '";
				} else {
					checksumKey.type = "password";
					showChecksumKeyBtn.value = "' . __('Show', 'casso-payos') . '";
				}
			}

			function togglePayOSSetting() {
				event.preventDefault();
				var toggleBtn = document.getElementById("toggle_payos_gateway_settings");
				var payOSGatewayGroup = document.getElementById("payos_gateway_settings_group");
				if (payOSGatewayGroup.style.display == "none") {
					payOSGatewayGroup.style.display = "table-row-group";
				}
				else {
					payOSGatewayGroup.style.display = "none";
				}
			}
		</script>
		';
		echo $payment_gateway_config; 
		}
		?>
<?php
		return ob_get_clean();
	}

	/**
	 * Output for the order received page.
	 *
	 * @param int $order_id Order ID.
	 */
	public function thankyou_page($order_id)
	{
		$query_params = array();
		parse_str($_SERVER['QUERY_STRING'], $query_params);
		$payment_status = isset($query_params['status']) ? $query_params['status'] : null;
		if (!$payment_status) {
			$this->payos_payment_page($order_id);
		}
			
		$this->use_payment_gateway_template($payment_status);
	}

	/**
	 * Add content to the WC emails.
	 *
	 * @param WC_Order $order Order object.
	 * @param bool     $sent_to_admin Sent to admin.
	 * @param bool     $plain_text Email format: plain text or HTML.
	 */
	public function email_instructions($order, $sent_to_admin, $plain_text = false)
	{
		if (!$sent_to_admin && $this->id === $order->get_payment_method() && $order->has_status('on-hold')) {
			if ($this->instructions) {
				echo wp_kses_post(wpautop(wptexturize($this->instructions)) . PHP_EOL);
			}
		}
	}

	/**
	 * Process the payment and return the result.
	 *
	 * @param int $order_id Order ID.
	 * @return array
	 */
	public function process_payment($order_id)
	{
		$order = wc_get_order($order_id);
		if ($order->get_total() > 0) {
			// Mark as on-hold (we're awaiting the payment).
			$order->update_status(apply_filters('woocommerce_payos_process_payment_order_status', 'on-hold', $order), __('Awaiting payment', 'woocommerce'));
		} else {
			$order->payment_complete();
		}

		// Remove cart.
		WC()->cart->empty_cart();

		// Return thankyou redirect.
		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url($order),
		);
	}

	public function get_payos_payment_url($order_id)
	{
		$order = wc_get_order($order_id);
		$payos_gateway_settings = self::get_payos_gateway_settings();
		if ($order->get_payment_method() === 'casso-payos') {
			if (isset($payos_gateway_settings['use_payment_gateway']) && $payos_gateway_settings['use_payment_gateway'] == 'yes') {
				parse_str($_SERVER['QUERY_STRING'], $query_str_arr);
				if ($query_str_arr && array_key_exists("code", $query_str_arr)) return;
				
				$order_id = $order->get_id();
				$woo_checkout_url = wc_get_checkout_url();
				if (substr($woo_checkout_url, -1) != '/') {
					$woo_checkout_url = $woo_checkout_url . '/';
				}
				$redirect_url = $woo_checkout_url . 'order-received/' . $order->get_id() . '/?' . $_SERVER['QUERY_STRING'];
				$client_id = $this->payos_gateway_settings['client_id'];
				$api_key = $this->payos_gateway_settings['api_key'];

				$item_cart = array();
				foreach ( $order->get_items() as $item_id => $item ) {
					$product_name = $item->get_name();
					$quantity = $item->get_quantity();
					$total = intval($item->get_total());
					array_push($item_cart, array(
						'name' => $product_name,
						'quantity' => $quantity,
						'price' => $total
					));
				}
				$data = array(
					"orderCode" => $order->get_id(),
					"description" => $this->payos_gateway_settings['transaction_prefix'] . $order->get_id(),
					"amount" => intval($order->get_total()),
					"buyerName" => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
					"buyerEmail" => $order->get_billing_email(),
					// "taxCode" => $order->get_meta("tax_code"),
					"items" => $item_cart,
					"returnUrl" => $redirect_url,
					"cancelUrl" => $redirect_url
				);
				// Tao signature cho data request
				$request_data_signature = $this->create_signature_payment_request($data["amount"], $data["cancelUrl"], $data["description"], $data["orderCode"], $data["returnUrl"]);
				$data["signature"] = $request_data_signature;

				$request = curl_init();
				curl_setopt($request, CURLOPT_URL, self::$URL_CREATE_PAYMENT_LINK);
				curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($request, CURLOPT_HTTPHEADER, array(
						"Content-Type: application/json",
						"x-client-id: {$client_id}",
						"x-api-key: {$api_key}"
					)
				);
				
				curl_setopt($request, CURLOPT_POST, 1);
				curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($data));
				$response = curl_exec($request);

				#Ensure to close curl
				curl_close($request);
				$result = json_decode($response, true);
				// Kiem tra data co toan ven bang cach kiem tra signature
				$checkout_response = $result['data'];
				$checkout_response_signature = $this->create_signature($checkout_response);
				
				if ($checkout_response_signature !== $result['signature']) { // Khong toan ven du lieu
					return null;
				}
				return $result['data']['checkoutUrl'];
			}
			else return;
		} 
	}

	public function use_payment_gateway_template($status) {
		if (empty($this->account_details) && isset($this->account_details)) {
			return;
		}
		// Get order and store in $order.
		$payment_html = '';

		switch($status) {
			case 'PAID':
				$img_file_name = 'success.png';
				$msg_txt = __('Order has been successfully paid.', 'casso-payos');
				break;
			case 'CANCELLED':
				$img_file_name = 'failed.png';
				$msg_txt = __('Payment canceled', 'casso-payos');
				break;
			default:
				$img_file_name = 'info.png';
				$msg_txt = __('Your order will be automatically confirmed once the payment transaction is received.', 'casso-payos');
				break;
		}

		$payment_html = '
		<section class="woocommerce-payos-gateway-page">
			<img src="' . plugins_url('../assets/img/' . $img_file_name, __FILE__) . '" style = "width: 100px; margin: 0 auto; display: block" id=""/>

			<h4>' . $msg_txt . '</h4>
		</section>

		<style>
			.woocommerce-payos-gateway-page {
				text-align: center;
				margin-bottom: 20px;
			}
		</style>';

		echo $payment_html;
	}

	public function casso_get_order_statuses_after_paid()
	{
		$wooDefaultStatuses = array(
			"wc-pending",
			"wc-processing",
			"wc-on-hold",
			// "wc-completed",
			"wc-cancelled",
			"wc-refunded",
			"wc-failed",
			// "wc-paid",
			"wc-underpaid"
		);
		$statuses =  wc_get_order_statuses();
		$statuses['wc-default'] = __('Default', 'casso-payos');
		for ($i = 0; $i < count($wooDefaultStatuses); $i++) {
			$statusName = $wooDefaultStatuses[$i];
			if (isset($statuses[$statusName])) {
				unset($statuses[$statusName]);
			}
		}
		return $statuses;
	}

	public function casso_get_order_statuses_after_underpaid()
	{
		$wooDefaultStatuses = array(
			// "wc-pending",
			// "wc-processing",
			// "wc-on-hold",
			"wc-completed",
			// "wc-cancelled",
			"wc-refunded",
			"wc-failed",
			"wc-paid",
			// "wc-underpaid"
		);
		$statuses =  wc_get_order_statuses();
		$statuses['wc-default'] =  __('Default', 'casso-payos');
		for ($i = 0; $i < count($wooDefaultStatuses); $i++) {
			$statusName = $wooDefaultStatuses[$i];
			if (isset($statuses[$statusName])) {
				unset($statuses[$statusName]);
			}
		}
		return $statuses;
	}	

public function verify_payment_handler() 
{
    $txtBody = file_get_contents('php://input');
    $body = json_decode($txtBody, true);
    if ($body['desc'] == 'Giao dich thu nghiem' || $body['data']['description'] == 'VQRIO123' || $body['data']['reference'] == 'MA_GIAO_DICH_THU_NGHIEM') {
        echo "True";
        die();
    }
    $transaction = $body['data'];
    // --------------Xac thuc du lieu webhook-------------------
    $signature = $this->create_signature($transaction);
    if (!$body['signature'] || $signature !== $body['signature']) {
        echo "Missing signature or wrong signature";
        die();
    }
    // ---------------------------------------------------------
    // Xu ly du lieu giao dich
    $orderCode = $transaction['orderCode'];
    $order = wc_get_order($orderCode);
    $payos_gateway_settings = get_option('woocommerce_payos_gateway_settings');
    if (!$order) {
        echo "False";
        die();
    }
    $rest_amount = intval($transaction['amount']) - intval($order->get_total());
    switch (true) {
        case $rest_amount >= 0:
            $order->payment_complete();
            wc_reduce_stock_levels($orderCode);
            $order->update_status('processing'); // Cập nhật trạng thái đơn hàng thành 'processing'
            if ($rest_amount > 0) {
                $order->add_order_note(__('Order has been overpaid', 'casso-payos'));
            }
            break;
        case $rest_amount < 0:
            if (isset($payos_gateway_settings['order_status']['order_status_after_underpaid'])) {
                $order->update_status($payos_gateway_settings['order_status']['order_status_after_underpaid']);
                $order->add_order_note(__('Order has been underpaid', 'casso-payos'));
            }
            break;
        default:
            break;
    }
    $transaction_note = sprintf(__('<b>Transaction Information:</b> <br> Order Code: %s <br> Amount: %s <br> Description: %s <br> Account Number: %s <br> Reference: %s', 'casso-payos'), 
                                    $transaction['orderCode'], 
                                    $transaction['amount'], 
                                    $transaction['description'], 
                                    $transaction['accountNumber'], 
                                    $transaction['reference']
                        );
    $order->add_order_note($transaction_note);
    echo "True";
    die();
}


	public function payos_payment_page($order_id) {
		$order = wc_get_order($order_id);
		if ($this->id === $order->get_payment_method()) {
			$payment_url = $this->get_payos_payment_url($order_id);
			header("Location: {$payment_url}");
			die();
		}
	}

	public function payos_create_webhook($settings)
	{
		$response = null;
		$body = array(
			"webhookUrl" =>  self::get_webhook_url()
		);
		
		$url  = self::$URL_CONFIRM_WEBHOOK;
		$args = array(
			'body'        => json_encode($body),
			'headers' => array(
				"Content-Type" 	=> "application/json",
				"x-client-id" 	=> $settings['client_id'],
				"x-api-key" 	=> $settings['api_key']
			)
		);
		$response = wp_remote_post($url, $args);

		if (is_wp_error($response)) {
			return null;
		}
		$body_response = json_decode($response['body'], true);
		if (($response['response']['code'] == 200 || $response['response']['code'] == 201) && $body_response['code'] == '00') {
			$body     = wp_remote_retrieve_body($response);
			return $body;
		}
		return null;
	}

	public function create_signature($data) {
		ksort($data);
		$data_str_arr = [];
		foreach ($data as $key => $value) {
			$data_str_arr[] = $key . "=" . $value;
		}
		$data_str = implode('&', $data_str_arr);
		$signature = hash_hmac('sha256', $data_str, $this->payos_gateway_settings['checksum_key']);
		return $signature;
	}

	public function create_signature_payment_request($amount, $cancel_url, $description, $order_code, $return_url) {
		$data_str = "amount={$amount}&cancelUrl={$cancel_url}&description={$description}&orderCode={$order_code}&returnUrl={$return_url}";
		$signature = hash_hmac('sha256', $data_str, $this->payos_gateway_settings['checksum_key']);
		return $signature;
	}

	static function get_webhook_url()
	{
		return WC()->api_request_url(self::$WEBHOOK_ROUTE);
		// return 'https://ce5b-203-205-33-133.ngrok-free.app/wc-api/' . self::$WEBHOOK_ROUTE;
	}
}