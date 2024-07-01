<?php

namespace WeDevs\DokanPro\Emails;

use WC_Email;

class VendorDisable extends WC_Email {

    /**
    * Constructor.
    */
    public function __construct() {
        $this->id               = 'dokan_email_vendor_disable';
        $this->title            = __( 'PallMall Nhà cung cấp vô hiệu hóa', 'dokan' );
        $this->description      = __( 'Email này được gửi cho một nhà cung cấp khi tài khoản bị quản trị viên vô hiệu hóa', 'dokan' );
        $this->template_html    = 'emails/vendor-disabled.php';
        $this->template_plain   = 'emails/plain/vendor-disabled.php';
        $this->template_base    = DOKAN_PRO_DIR . '/templates/';

        // Triggers for this email
        add_action( 'dokan_vendor_disabled', array( $this, 'trigger' ) );

        // Call parent constructor
        parent::__construct();

        $this->recipient = 'vendor@ofthe.product';
    }

    /**
    * Get email subject.
    * @return string
    */
    public function get_default_subject() {
        return __( '[{site_name}] Tài khoản của bạn bị vô hiệu hóa', 'dokan' );
    }

    /**
    * Get email heading.
    * @return string
    */
    public function get_default_heading() {
        return __( 'Tài khoản nhà cung cấp của bạn bị vô hiệu hóa', 'dokan' );
    }

    /**
    * Trigger the email.
    */
    public function trigger( $seller_id ) {
        if ( ! $this->is_enabled() ) {
            return;
        }

        $this->setup_locale();

        $seller = get_user_by( 'ID', $seller_id );
        $seller_email = $seller->user_email;

        $this->find['site_name']        = '{site_name}';
        $this->find['first_name']       = '{first_name}';
        $this->find['last_name']        = '{last_name}';
        $this->find['display_name']     = '{display_name}';

        $this->replace['site_name']     = $this->get_from_name();
        $this->replace['first_name']    = $seller->first_name;
        $this->replace['last_name']     = $seller->last_name;
        $this->replace['display_name']  = $seller->display_name;

        $this->send( $seller_email, $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );

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
				'email_heading' => $this->get_heading(),
				'sent_to_admin' => false,
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
				'email_heading' => $this->get_heading(),
				'sent_to_admin' => false,
				'plain_text'    => true,
				'email'         => $this,
				'data'          => $this->replace,
            ), 'dokan/', $this->template_base
        );
        return ob_get_clean();
    }

    /**
    * Initialize settings form fields.
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
                'description'   => sprintf( __( 'Mô tả: %s', 'dokan' ), '<code>{title}, {message}, {site_name}</code>' ),
                'placeholder'   => $this->get_default_subject(),
                'default'       => '',
            ),
            'heading' => array(
                'title'         => __( 'Tiêu đề email', 'dokan' ),
                'type'          => 'text',
                'desc_tip'      => true,
                /* translators: %s: list of placeholders */
                'description'   => sprintf( __( 'Mô tả: %s', 'dokan' ), '<code>{title}, {message}, {site_name}</code>' ),
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