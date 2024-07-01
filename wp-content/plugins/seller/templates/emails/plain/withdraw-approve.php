<?php
/**
 * New Withdraw request Email.
 *
 * An email sent to the admin when a new withdraw request is created by vendor.
 *
 * @class       Dokan_Email_Withdraw_Approved
 * @version     2.6.8
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

// translators: 1) user name
echo sprintf( esc_html__( 'Chào %s', 'dokan-lite' ), esc_html( $data['{store_name}'] ) );
echo " \n";

esc_html_e( 'Yêu cầu rút tiền của bạn đã được phê duyệt, xin chúc mừng!', 'dokan-lite' );
echo " \n";

esc_html_e( 'Bạn đã gửi yêu cầu rút tiền của:', 'dokan-lite' );
echo " \n";

// translators: 1) withdraw amount
echo sprintf( esc_html__( 'Số lượng : %s', 'dokan-lite' ), esc_html( $data['{amount}'] ) );
echo " \n";

// translators: 1) withdraw method
echo sprintf( esc_html__( 'Phương thức : %s', 'dokan-lite' ), esc_html( $data['{method}'] ) );
echo " \n";

esc_html_e( 'Chúng tôi sẽ sớm chuyển số tiền này sang phương thức rút tiền bạn đã lựa chọn.', 'dokan-lite' );
echo " \n";

esc_html_e( 'Cảm ơn đã đồng hành cùng chúng tôi.', 'dokan-lite' );
echo " \n";

echo "\n\n----------------------------------------\n\n";

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( ! empty( $additional_content ) ) {
    echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
    echo "\n\n----------------------------------------\n\n";
}

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );