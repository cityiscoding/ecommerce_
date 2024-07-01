<?php

class Dokan_Follow_Store_Email extends WC_Email {

    /**
     * Store Follower
     *
     * @since 1.0.0
     *
     * @var null|WP_User
     */
    public $follower = null;

    /**
     * Following stores
     *
     * @since 1.0.0
     *
     * @var null|array
     */
    public $vendors = null;

    public function __construct() {
        $this->id             = 'updates_for_store_followers';
        $this->title          = __( 'PallMall cập nhật người theo dõi cửa hàng', 'dokan' );
        $this->description    = __( 'Gửi cập nhật cửa hàng cho người theo dõi.', 'dokan' );
        $this->template_html  = 'follow-store-updates-email-html.php';
        $this->template_base  = DOKAN_FOLLOW_STORE_VIEWS . '/';
        $this->customer_email = true;
        $this->placeholders   = array(
            '{site_title}'    => $this->get_blogname(),
            '{follower_name}' => '',
        );

        // Call parent constructor
        parent::__construct();

        // Set the email content type text/html
        $this->email_type = 'html';

        add_action( 'dokan_follow_store_send_update_email', array( $this, 'trigger' ), 10, 2 );
    }

    /**
     * Email settings
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function init_form_fields() {
        $available_placeholders = '{site_title}, {follower_name}';

        $this->form_fields = array(
            'enabled' => array(
                'title'         => __( 'Cho phép / vô hiệu hóa', 'dokan' ),
                'type'          => 'checkbox',
                'label'         => __( 'Bật email này', 'dokan' ),
                'default'       => 'yes',
            ),

            'subject' => array(
                'title'         => __( 'Tiêu đề', 'dokan' ),
                'type'          => 'text',
                'desc_tip'      => true,
                'description'   => sprintf( __( 'Mô tả: %s', 'dokan' ), $available_placeholders ),
                'placeholder'   => $this->get_default_subject(),
                'default'       => $this->get_default_subject(),
            ),

            'heading' => array(
                'title'         => __( 'Tiêu đề email', 'dokan' ),
                'type'          => 'text',
                'desc_tip'      => true,
                'description'   => sprintf( __( 'Mô tả: %s', 'dokan' ), $available_placeholders ),
                'placeholder'   => $this->get_default_heading(),
                'default'       => $this->get_default_heading(),
            ),
        );
    }

    /**
     * Email default subject
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_default_subject() {
        return sprintf( __( '%s, Xem các cập nhật mới từ %s', 'dokan' ), '{follower_name}', '{site_title}' );
    }

    /**
     * Email default heading
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_default_heading() {
        return sprintf( __( 'Cập nhật mới nhất từ %s', 'dokan' ), '{site_title}' );
    }

    /**
     * Send email
     *
     * @since 1.0.0
     *
     * @param WP_User $follower
     * @param array   $vendors
     *
     * @return void
     */
    public function trigger( $follower, $vendors ) {
        $this->follower = $follower;
        $this->vendors  = $vendors;

        $this->setup_locale();

        if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
            return;
        }

        $this->placeholders['{follower_name}'] = $this->follower->display_name;

        $sent = $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );

        $this->restore_locale();
    }

    /**
     * Follower email
     *
     * @since 1.0.0
     *
     * @return string|null
     */
    public function get_recipient() {
        if ( $this->follower instanceof WP_User && is_email( $this->follower->user_email ) ) {
            return $this->follower->user_email;
        }

        return null;
    }

    /**
     * Email content
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function get_content() {
        ob_start();
        wc_get_template( $this->template_html, array(
            'email_heading' => $this->get_heading(),
            'sent_to_admin' => false,
            'plain_text'    => false,
            'email'         => $this,
            'data'          => array(
                'vendors'         => $this->vendors,
            ),
        ), 'dokan/', $this->template_base );
        return ob_get_clean();
    }
}