<?php

namespace WeDevs\DokanPro\Emails;

use WC_Email;

class RefundRequest extends WC_Email {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id               = 'dokan_refund_request';
        $this->title            = __( 'PallMall Yêu cầu hoàn lại tiền mới', 'dokan' );
        $this->description      = __( 'Những email này được gửi đến (những) người nhận đã chọn khi nhà cung cấp gửi yêu cầu hoàn tiền', 'dokan' );
        $this->template_html    = 'emails/refund_request.php';
        $this->template_plain   = 'emails/plain/refund_request.php';
        $this->template_base    = DOKAN_PRO_DIR . '/templates/';

        // Triggers for this email
        add_action( 'dokan_rma_requested_amount', array( $this, 'trigger' ), 30, 2 );
        add_action( 'dokan_refund_requested_amount', array( $this, 'trigger' ), 30, 2 );

        // Call parent constructor
        parent::__construct();

        // Other settings
        $this->recipient = $this->get_option( 'recipient', get_option( 'admin_email' ) );
    }

    /**
     * Get email subject.
     * @return string
     */
    public function get_default_subject() {
        return __( '[{site_name}] Một yêu cầu hoàn lại mới được thực hiện bởi {seller_name}', 'dokan' );
    }

    /**
     * Get email heading.
     * @return string
     */
    public function get_default_heading() {
        return __( 'Yêu cầu hoàn trả mới từ - {seller_name}', 'dokan' );
    }

    /**
     * Trigger the sending of this email.
     *
     * @param int   $order_id      Order id
     * @param float $refund_amount Refund amount
     *
     * @return void
     */
    public function trigger( $order_id, $refund_amount ) {
        if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
            return;
        }

        $order = wc_get_order( $order_id );

        if ( ! $order ) {
            return;
        }

        // get seller id from order
        $seller_id = dokan_get_seller_id_by_order( $order_id );
        if ( ! $seller_id ) {
            return;
        }

        // get seller object
        $seller = dokan()->vendor->get( $seller_id );
        if ( ! is_a( $seller, '\WeDevs\Dokan\Vendor\Vendor' ) ) {
            return;
        }

        $this->object              = $order;
        $this->find['seller_name'] = '{seller_name}';
        $this->find['order_id']    = '{order_id}';
        $this->find['refund_url']  = '{refund_url}';
        $this->find['site_name']   = '{site_name}';
        $this->find['site_url']    = '{site_url}';
        $this->find['amount']      = '{amount}';

        $this->replace['seller_name'] = $seller->get_shop_name();
        $this->replace['order_id']    = $order_id;
        $this->replace['refund_url']  = admin_url( 'admin.php?page=dokan#/refund?status=pending' );
        $this->replace['site_name']   = $this->get_from_name();
        $this->replace['site_url']    = site_url();
        $this->replace['amount']      = dokan()->email->currency_symbol( wc_format_decimal( $refund_amount, '' ) );

        $this->setup_locale();
        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
        $this->restore_locale();
    }

        /**
     * Get content html.
     *
     * @access public
     * @return string
     */
    public function get_content_html() {
        ob_start();
            wc_get_template(
                $this->template_html, array(
					'order'         => $this->object,
					'email_heading' => $this->get_heading(),
					'sent_to_admin' => true,
					'plain_text'    => false,
					'email'         => $this,
					'data'          => $this->replace,
                ), 'dokan/', $this->template_base
            );
        return ob_get_clean();
    }

    /**
     * Get content plain.
     *
     * @access public
     * @return string
     */
    public function get_content_plain() {
        ob_start();
            wc_get_template(
                $this->template_html, array(
					'order'         => $this->object,
					'email_heading' => $this->get_heading(),
					'sent_to_admin' => true,
					'plain_text'    => true,
					'email'         => $this,
					'data'          => $this->replace,
                ), 'dokan/', $this->template_base
            );
        return ob_get_clean();
    }

    /**
     * Initialise settings form fields.
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'         => __( 'Cho phép / vô hiệu hóa', 'dokan' ),
                'type'          => 'checkbox',
                'label'         => __( 'Bật thông báo email này', 'dokan' ),
                'default'       => 'yes',
            ),
            'recipient' => array(
                'title'         => __( 'Người nhận(s)', 'dokan' ),
                'type'          => 'text',
                'description'   => sprintf(
                    // translators: %s is admin email address.
                    __( 'Nhập người nhận (được phân tách bằng dấu phẩy) cho email này. Mặc định là %s.', 'dokan' ),
                    '<code>' . esc_attr( get_option( 'admin_email' ) ) . '</code>'
                ),
                'placeholder'   => '',
                'default'       => '',
                'desc_tip'      => true,
            ),
            'subject' => array(
                'title'         => __( 'Tiêu đề', 'dokan' ),
                'type'          => 'text',
                'desc_tip'      => true,
                /* translators: %s: list of placeholders */
                'description'   => sprintf( __( 'Mô tả: %s', 'dokan' ), '<code>{site_name},{amount},{user_name}</code>' ),
                'placeholder'   => $this->get_default_subject(),
                'default'       => '',
            ),
            'heading' => array(
                'title'         => __( 'Tiêu đề email', 'dokan' ),
                'type'          => 'text',
                'desc_tip'      => true,
                /* translators: %s: list of placeholders */
                'description'   => sprintf( __( 'Mô tả: %s', 'dokan' ), '<code>{site_name},{amount},{user_name}</code>' ),
                'placeholder'   => $this->get_default_heading(),
                'default'       => '',
            ),
            'email_type' => array(
                'title'         => __( 'Loại email', 'dokan' ),
                'type'          => 'select',
                'description'   => __( 'Chọn định dạng email để gửi.', 'dokan' ),
                'default'       => 'html',
                'class'         => 'email_type wc-enhanced-select',
                'options'       => $this->get_email_type_options(),
                'desc_tip'      => true,
            ),
        );
    }
}