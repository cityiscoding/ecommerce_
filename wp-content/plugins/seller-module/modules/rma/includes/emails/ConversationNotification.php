<?php

namespace WeDevs\DokanPro\Modules\RMA\emails;

use WC_Email;
use WeDevs\Dokan\Vendor\Vendor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * New Conversation Notification Email.
 * An email sent to the vendor or customer when a warranty request conversation is made by customer or vendor.
 *
 * @class       Dokan_Rma_Conversation_Notification
 * @version     3.9.5
 * @author      weDevs
 * @extends     WC_Email
 */
class ConversationNotification extends WC_Email {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id          = 'Dokan_Rma_Conversation_Notification';
        $this->title       = __( 'PallMall Gửi thông báo về yêu cầu hoàn tiền cho nhà cung cấp và khách hàng', 'dokan' );
        $this->description = __( 'Email này gửi cho nhà cung cấp hoặc khách hàng một khi khách hàng hoặc nhà cung cấp thực hiện một cuộc trò chuyện mới theo yêu cầu hoàn lại tiền', 'dokan' );

        $this->template_base  = DOKAN_RMA_DIR . '/templates/';
        $this->template_html  = 'emails/send-conversation-notification.php';
        $this->template_plain = 'emails/plain/send-conversation-notification.php';
        $this->placeholders   = [
            '{request_id}' => '',
            '{message}'    => '',
            '{to}'         => '',
            '{from}'       => '',
        ];

        // Triggers for this email
        add_action( 'dokan_pro_rma_conversion_created', [ $this, 'trigger' ], 30, 2 );

        // Call parent constructor
        parent::__construct();

        $this->recipient = 'vendor@ofthe.product,customer@ofthe.website';
    }

    /**
     * Get email subject.
     *
     * @since 3.9.5
     * @return string
     */
    public function get_default_subject() {
        return __( '[{site_title}] Một câu trả lời mới được thêm vào trong một yêu cầu hoàn tiền hoặc trả hàng', 'dokan' );
    }

    /**
     * Get email heading.
     *
     * @since 3.9.5
     * @return string
     */
    public function get_default_heading() {
        return __( 'Một câu trả lời mới được thêm vào trong một yêu cầu hoàn tiền hoặc trả hàng', 'dokan' );
    }

    /**
     * Trigger the sending of this email.
     *
     * @param int $conversation_id The Conversation id.
     * @param array $data Data of the Conversation.
     */
    public function trigger( $conversation_id, $data ) {
        if ( ! $this->is_enabled() || ! $this->get_recipient() || empty( $data ) ) {
            return;
        }
        $this->setup_locale();

        $this->object = $data;
        [ $from_name ] = $this->get_display_name_email( absint( $data['from'] ) );
        [ $to_name, $email, $is_vendor ] = $this->get_display_name_email( absint( $data['to'] ) );

        $this->placeholders['{request_id}'] = absint( $data['request_id'] );
        $this->placeholders['{message}']    = esc_html( $data['message'] );
        $this->placeholders['{to}']         = $to_name;
        $this->placeholders['{from}']       = $from_name;
        $this->object['to_name']            = $to_name;
        $this->object['from_name']          = $from_name;
        $this->object['is_vendor']          = $is_vendor;
        $this->object['rma_url']            = $is_vendor ? add_query_arg( 'request', $data['request_id'],
            dokan_get_navigation_url( 'return-request' ) )
            : esc_url( wc_get_account_endpoint_url( 'view-rma-requests' ) . $data['request_id'] );

        $this->send( $email, $this->get_subject(), $this->get_content(), $this->get_headers(),
            $this->get_attachments() );
        $this->restore_locale();
    }


    /**
     * Get content html.
     *
     * @access public
     * @return string
     */
    public function get_content_html(): string {
        return wc_get_template_html( $this->template_html, array(
            'data'               => $this->object,
            'email_heading'      => $this->get_heading(),
            'plain_text'         => false,
            'email'              => $this,
            'additional_content' => $this->get_additional_content(),
        ), 'dokan/', $this->template_base );
    }

    /**
     * Get content plain.
     *
     * @access public
     * @return string
     */
    public function get_content_plain(): string {
        return wc_get_template_html( $this->template_plain, array(
            'data'               => $this->object,
            'email_heading'      => $this->get_heading(),
            'plain_text'         => true,
            'email'              => $this,
            'additional_content' => $this->get_additional_content(),
        ), 'dokan/', $this->template_base );
    }

    /**
     * Initialise settings form fields.
     */
    public function init_form_fields() {
        /* translators: %s: list of placeholders */
        $placeholder_text  = sprintf( __( 'Mô tả: %s', 'dokan' ), '<code>' . implode( '</code>, <code>', array_keys( $this->placeholders ) ) . '</code>' );
        $this->form_fields = array(
            'enabled'            => array(
                'title'   => __( 'Cho phép / Vô hiệu hóa', 'dokan' ),
                'type'    => 'checkbox',
                'label'   => __( 'Bật thông báo email này', 'dokan' ),
                'default' => 'yes',
            ),
            'subject'            => array(
                'title'       => __( 'Tiêu đề', 'dokan' ),
                'type'        => 'text',
                'desc_tip'    => true,
                'description' => $placeholder_text,
                'placeholder' => $this->get_default_subject(),
                'default'     => '',
            ),
            'heading'            => array(
                'title'       => __( 'Tiêu đề email', 'dokan' ),
                'type'        => 'text',
                'desc_tip'    => true,
                'description' => $placeholder_text,
                'placeholder' => $this->get_default_heading(),
                'default'     => '',
            ),
            'additional_content' => array(
                'title'       => __( 'Nội dung bổ sung', 'dokan' ),
                'description' => __( 'Văn bản xuất hiện bên dưới nội dung email chính.', 'dokan' ) . ' '
                                 . $placeholder_text,
                'css'         => 'width:400px; height: 75px;',
                'placeholder' => __( 'N/A', 'dokan' ),
                'type'        => 'textarea',
                'default'     => $this->get_default_additional_content(),
                'desc_tip'    => true,
            ),
            'email_type'         => array(
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

    /**
     * Get display name and email of a user or vendor.
     *
     * @since 3.9.5
     *
     * @param int $id User id.
     *
     * @return array
     */
    private function get_display_name_email( int $id ): array {
        $user      = new \WP_User( $id );
        $email     = $user->user_email;
        $name      = $user->display_name;
        $is_vendor = false;

        if ( $user->has_cap( 'dokandar' ) ) {
            if ( $user->has_cap( 'vendor_staff' ) ) {
                $staff_id = $user->ID;
                $user     = (int) get_user_meta( $staff_id, '_vendor_id', true );
            }

            $user      = new Vendor( $user );
            $email     = $user->get_email();
            $name      = $user->get_shop_name();
            $is_vendor = true;
        }

        return [ $name, $email, $is_vendor ];
    }
}