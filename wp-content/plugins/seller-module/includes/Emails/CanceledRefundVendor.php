<?php

namespace WeDevs\DokanPro\Emails;

use WC_Email;
use WeDevs\DokanPro\Refund\Refund;

/**
 * Notify Vendor when a refund request get canceled.
 *
 * @since 3.3.6
 */
class CanceledRefundVendor extends WC_Email {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id               = 'dokan_vendor_refund_canceled';
        $this->title            = __( 'PallMall Hoàn lại tiền bị hủy', 'dokan' );
        $this->description      = __( 'Những email này được gửi đến nhà cung cấp khi yêu cầu hoàn lại tiền của nhà cung cấp bị hủy', 'dokan' );
        $this->template_html    = 'emails/refund-canceled-seller-mail.php';
        $this->template_plain   = 'emails/plain/refund-canceled-seller-mail.php';
        $this->template_base    = DOKAN_PRO_DIR . '/templates/';

        // Triggers for this email
        add_action( 'dokan_pro_refund_cancelled', array( $this, 'trigger' ), 30, 1 );

        // Call parent constructor
        parent::__construct();

        // Other settings
        $this->recipient = 'vendor@ofthe.product';
    }

    /**
     * Get email subject.
     *
     * @since  3.3.6
     *
     * @return string
     */
    public function get_default_subject() {
        return __( '[{site_name}] Yêu cầu hoàn tiền của bạn là {status}', 'dokan' );
    }

    /**
     * Get email heading.
     *
     * @since 3.3.6
     *
     * @return string
     */
    public function get_default_heading() {
        return __( 'Yêu cầu hoàn tiền cho ID đơn hàng #{order_id} is {status}', 'dokan' );
    }

    /**
     * Trigger the sending of this email.
     *
     * @since 3.3.6
     *
     * @param Refund $refund Vendor Refund Request.
     */
    public function trigger( $refund ) {
        if ( ! $this->is_enabled() ) {
            return;
        }

        $seller = dokan()->vendor->get( $refund->get_seller_id() );

        if ( ! is_a( $seller, '\WeDevs\Dokan\Vendor\Vendor' ) ) {
            return;
        }

        $this->object  = $seller;
        $order_id      = $refund->get_order_id();
        $refund_amount = $refund->get_refund_amount();
        $refund_reason = $refund->get_refund_reason();
        $status        = 'canceled';
        $seller_mail   = $seller->get_email();
        $order_url     = esc_url(
            add_query_arg(
                array(
                    'order_id' => $order_id,
                    '_view_mode' => 'email',
                    'permission' => '1',
                ), dokan_get_navigation_url( 'orders' )
            )
        );

        $this->placeholders = array(
            '{seller_name}' => $seller->get_name(),
            '{amount}'      => wc_price( $refund_amount ),
            '{reason}'      => $refund_reason,
            '{order_id}'    => $order_id,
            '{status}'      => $status,
            '{order_link}'  => $order_url,
            '{site_name}'   => $this->get_from_name(),
            '{site_url}'    => site_url(),
        );

        $this->data = array(
            'seller_name' => $seller->get_name(),
            'amount'      => wc_price( $refund_amount ),
            'reason'      => $refund_reason,
            'order_id'    => $order_id,
            'status'      => $status,
            'order_link'  => $order_url,
            'site_name'   => $this->get_from_name(),
            'site_url'    => site_url(),
        );

        $this->setup_locale();
        $this->send( $seller_mail, $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
        $this->restore_locale();
    }

    /**
     * Get content html.
     *
     * @since 3.3.6
     *
     * @access public
     * @return string
     */
    public function get_content_html() {
        ob_start();
        wc_get_template(
            $this->template_html, array(
                'seller'        => $this->object,
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text'    => false,
                'email'         => $this,
                'seller_name'   => $this->data['seller_name'],
                'status'        => $this->data['status'],
                'order_id'      => $this->data['order_id'],
                'amount'        => $this->data['amount'],
                'reason'        => $this->data['reason'],
                'order_link'    => $this->data['order_link'],
            ), 'dokan/', $this->template_base
        );
        return ob_get_clean();
    }

    /**
     * Get content plain.
     *
     * @since 3.3.6
     *
     * @access public
     * @return string
     */
    public function get_content_plain() {
        ob_start();
        wc_get_template(
            $this->template_plain, array(
                'seller'        => $this->object,
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text'    => true,
                'email'         => $this,
                'seller_name'   => $this->data['seller_name'],
                'status'        => $this->data['status'],
                'order_id'      => $this->data['order_id'],
                'amount'        => $this->data['amount'],
                'reason'        => $this->data['reason'],
                'order_link'    => $this->data['order_link'],
            ), 'dokan/', $this->template_base
        );
        return ob_get_clean();
    }

    /**
     * Initialise settings form fields.
     *
     * @since 3.3.6
     *
     * @return void
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'         => __( 'Cho phép / vô hiệu hóa', 'dokan' ),
                'type'          => 'checkbox',
                'label'         => __( 'Bật thông báo email này', 'dokan' ),
                'default'       => 'yes',
            ),
            'subject' => array(
                'title'         => __( 'Tiêu đề', 'dokan' ),
                'type'          => 'text',
                'desc_tip'      => true,
                /* translators: %s: list of placeholders */
                'description'   => sprintf( __( 'Mô tả: %s', 'dokan' ), '<code>{site_name},{amount},{seller_name},{order_id},{status}</code>' ),
                'placeholder'   => $this->get_default_subject(),
                'default'       => '',
            ),
            'heading' => array(
                'title'         => __( 'Tiêu đề email', 'dokan' ),
                'type'          => 'text',
                'desc_tip'      => true,
                /* translators: %s: list of placeholders */
                'description'   => sprintf( __( 'Mô tả: %s', 'dokan' ), '<code>{site_name},{amount},{seller_name},{order_id},{status}</code>' ),
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