<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Dokan_Send_Coupon_Email' ) ) :

/**
 * Dokan Send Coupon Email
 *
 * An email sent to the admin and customer when a vendor generate a coupon for customer
 *
 * @class       Dokan_Send_Coupon_Email
 * @version     2.9.3
 * @author      weDevs
 * @extends     WC_Email
 */
class Dokan_Send_Coupon_Email extends WC_Email {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id             = 'Dokan_Send_Coupon_Email';
        $this->title          = __( 'PallMall gửi phiếu giảm giá cho khách hàng', 'dokan' );
        $this->description    = __( 'Email này gửi cho khách hàng khi khách hàng gửi yêu cầu trả lại và nhà cung cấp gửi tín dụng cửa hàng cho khách hàng', 'dokan' );

        $this->template_base  = DOKAN_RMA_DIR . '/templates/';
        $this->template_html  = 'emails/send-coupon.php';
        $this->template_plain = 'emails/plain/send-coupon.php';
        $this->customer_email = true;

        // Triggers for this email
        add_action( 'dokan_send_coupon_to_customer', [ $this, 'trigger' ], 30, 2 );

        // Call parent constructor
        parent::__construct();

        $this->recipient = $this->get_option( 'recipient', get_option( 'admin_email' ) );
    }

    /**
     * Get email subject.
     *
     * @since  3.1.0
     * @return string
     */
    public function get_default_subject() {
        return __( '[{site_name}] Một phiếu giảm giá mới được tạo ra bởi ({vendor_name})', 'dokan' );
    }

    /**
     * Get email heading.
     *
     * @since  3.1.0
     * @return string
     */
    public function get_default_heading() {
        return __( 'Phiếu giảm giá mới được tạo ra', 'dokan' );
    }

    /**
     * Trigger the sending of this email.
     *
     * @param int $product_id The product ID.
     * @param array $postdata.
     */
    public function trigger( $coupon, $data ) {
        if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
            return;
        }

        $this->object = $coupon;

        if ( $data ) {
            $coupon_id      = isset( $data['request_id'] ) ? $data['request_id'] : '';
            $vendor_id      = isset( $data['refund_vendor_id'] ) ? $data['refund_vendor_id'] : '';
            $vendor         = dokan()->vendor->get( $vendor_id );
            $customer_email = $coupon->get_email_restrictions();
            $customer_email = is_array( $customer_email ) ? $customer_email[0] : $customer_email;
        }

        $this->find['site_name']      = '{site_name}';
        $this->find['vendor_name']    = '{vendor_name}';

        $this->replace['site_name']   = $this->get_from_name();
        $this->replace['vendor_name'] = $vendor->get_name();
        $this->replace['coupon_id']   = $coupon_id;

        $this->setup_locale();
        $this->send( "{$customer_email}, {$this->get_recipient()}", $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
        $this->restore_locale();
    }

    /**
     * Get content html.
     *
     * @access public
     *
     * @return string
     */
    public function get_content_html() {
        ob_start();
        wc_get_template( $this->template_html, array(
            'coupon'        => $this->object,
            'email_heading' => $this->get_heading(),
            'plain_text'    => false,
            'email'         => $this,
            'data'          => $this->replace
        ), 'dokan/', $this->template_base );
        return ob_get_clean();
    }

    /**
     * Get content plain.
     *
     * @access public
     *
     * @return string
     */
    public function get_content_plain() {
        ob_start();
        wc_get_template( $this->template_html, array(
            'coupon'       => $this->object,
            'email_heading' => $this->get_heading(),
            'plain_text'    => true,
            'email'         => $this,
            'data'          => $this->replace
        ), 'dokan/', $this->template_base );
        return ob_get_clean();
    }


    /**
     * Initialise settings form fields.
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'         => __( 'Cho phép / Vô hiệu hóa', 'dokan' ),
                'type'          => 'checkbox',
                'label'         => __( 'Bật thông báo email này', 'dokan' ),
                'default'       => 'yes',
            ),
            'recipient' => array(
                'title'         => __( 'Người nhận(s)', 'dokan' ),
                'type'          => 'text',
                'description'   => sprintf( __( 'Nhập người nhận cho email này. Mặc định là %s.', 'dokan' ), '<code>' . esc_attr( get_option( 'admin_email' ) ) . '</code>' ),
                'placeholder'   => '',
                'default'       => '',
                'desc_tip'      => true,
            ),
            'subject' => array(
                'title'         => __( 'Tiêu đề', 'dokan' ),
                'type'          => 'text',
                'desc_tip'      => true,
                /* translators: %s: list of placeholders */
                'description'   => sprintf( __( 'Mô tả %s', 'dokan' ), '<code>{site_name} {vendor_name}</code>' ),
                'placeholder'   => $this->get_default_subject(),
                'default'       => '',
            ),
            'heading' => array(
                'title'         => __( 'Tiêu đề email', 'dokan' ),
                'type'          => 'text',
                'desc_tip'      => true,
                /* translators: %s: list of placeholders */
                'description'   => __( 'Tiêu đề mặc định', 'dokan' ),
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

endif;