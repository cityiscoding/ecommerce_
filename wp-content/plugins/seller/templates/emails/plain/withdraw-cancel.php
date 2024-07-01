<?php
/**
 * New Withdraw request Email. ( plain text )
 *
 * An email sent to the admin when a new withdraw request is created by vendor.
 *
 * @class       Dokan_Email_Withdraw_Cancelled
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

esc_attr_e( 'Yêu cầu rút tiền của bạn đã bị hủy', 'dokan-lite' );
echo " \n";

esc_attr_e( 'Bạn đã gửi một yêu cầu rút tiền của:', 'dokan-lite' );
echo " \n";

// translators: 1) withdraw amount
echo sprintf( esc_html__( 'Số tiền : %s', 'dokan-lite' ), esc_html( $data['{amount}'] ) );
echo " \n";

// translators: 1) withdraw method
echo sprintf( esc_html__( 'Phương thức : %s', 'dokan-lite' ), esc_html( $data['{method}'] ) );
echo " \n";

esc_attr_e( 'Đây là lý do, tại sao : ', 'dokan-lite' );
echo " \n";

echo esc_html( $data['{note}'] );
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