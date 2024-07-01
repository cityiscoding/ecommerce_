<?php
/**
 * Class Dokan_Reply_To_User_Support_Ticket file
 *
 * @package Dokan/Emails
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Dokan_Reply_To_User_Support_Ticket' ) ) :

    /**
     * New Support Ticket.
     *
     * An email sent to the seller when a new support ticket submit.
     *
     * @class       Dokan_Reply_To_User_Support_Ticket
     * @version     3.3.4
     * @package     Dokan/Classes/Emails
     * @extends     WC_Email
     */
    class Dokan_Reply_To_User_Support_Ticket extends WC_Email {

        /**
         * Constructor.
         */
        public function __construct() {
            $this->id             = 'dokan_reply_to_user_support_ticket';
            $this->title          = __( 'PallMall trả lời vé hỗ trợ người dùng', 'dokan' );
            $this->description    = __( 'Email vé hỗ trợ mới được gửi đến (những) người nhận đã chọn khi nhận được phản hồi cho vé người dùng.', 'dokan' );
            $this->template_html  = 'emails/reply-to-user-support-ticket.php';
            $this->template_plain = 'emails/plain/reply-to-user-support-ticket.php';
            $this->template_base  = DOKAN_STORE_SUPPORT_DIR . '/templates/';

            $this->placeholders = array(
                '{site_title}'   => $this->get_blogname(),
            );

            // Triggers for this email.
            add_action( 'dokan_reply_to_user_ticket_created_notify', array( $this, 'trigger' ), 10, 2 );

            // Call parent constructor.
            parent::__construct();

            // Other settings.
            $this->recipient = 'customer@email.com';
        }

        /**
         * Get email subject.
         *
         * @since  3.3.4
         * @return string
         */
        public function get_default_subject() {
            return __( '[{site_title}] Một câu trả lời mới trên vé của bạn #{ticket_id}', 'dokan' );
        }

        /**
         * Get email heading.
         *
         * @since  3.3.4
         * @return string
         */
        public function get_default_heading() {
            return __( 'Một câu trả lời mới trên vé của bạn #{ticket_id}', 'dokan' );
        }

        /**
         * Trigger the sending of this email.
         *
         * @param int            $order_id The order ID.
         * @param WC_Order|false $order Order object.
         */
        public function trigger( $store_id, $email_data ) {
            if ( ! $this->is_enabled() ) {
                return;
            }

            $this->email_data             = $email_data;
            $this->find['ticket-id']      = '{ticket_id}';
            $this->replace['seller-name'] = $email_data['ticket_id'];

            $this->setup_locale();
            $this->send( $email_data['to_email'], $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );

            $this->restore_locale();
        }

        /**
         * Get content html.
         *
         * @access public
         * @return string
         */
        public function get_content_html() {
            return wc_get_template_html(
                $this->template_html, array(
                    'email_heading' => $this->get_heading(),
                    'plain_text'    => false,
                    'email'         => $this,
                    'email_data'    => $this->email_data,
                ), 'dokan/', $this->template_base
            );
        }

        /**
         * Get content plain.
         *
         * @access public
         * @return string
         */
        public function get_content_plain() {
            return wc_get_template_html(
                $this->template_plain, array(
                    'email_heading' => $this->get_heading(),
                    'plain_text'    => true,
                    'email'         => $this,
                    'email_data'    => $this->email_data,
                ), 'dokan/', $this->template_base
            );
        }

        /**
         * Initialise settings form fields.
         */
        public function init_form_fields() {
            $this->form_fields = array(
                'enabled'    => array(
                    'title'   => __( 'Cho phép /Vô hiệu hóa', 'dokan' ),
                    'type'    => 'checkbox',
                    'label'   => __( 'Bật thông báo email này', 'dokan' ),
                    'default' => 'yes',
                ),
                'subject'    => array(
                    'title'       => __( 'Tiêu đề', 'dokan' ),
                    'type'        => 'text',
                    'desc_tip'    => true,
                    /* translators: %s: list of placeholders */
                    'description' => sprintf( __( 'Mô tả: %s', 'dokan' ), '<code>{site_title}</code>' ),
                    'placeholder' => $this->get_default_subject(),
                    'default'     => '',
                ),
                'heading'    => array(
                    'title'       => __( 'Tiêu đề email', 'dokan' ),
                    'type'        => 'text',
                    'desc_tip'    => true,
                    /* translators: %s: list of placeholders */
                    'description' => sprintf( __( 'Mô tả: %s', 'dokan' ), '<code>{site_title}</code>' ),
                    'placeholder' => $this->get_default_heading(),
                    'default'     => '',
                ),
                'email_type' => array(
                    'title'       => __( 'Loại email', 'dokan' ),
                    'type'        => 'select',
                    'description' => __( 'Chọn định dạng email để gửi.', 'dokan' ),
                    'default'     => 'html',
                    'class'       => 'email_type wc-enhanced-select',
                    'options'     => $this->get_email_type_options(),
                    'desc_tip'    => true,
                ),
            );
        }
    }

endif;