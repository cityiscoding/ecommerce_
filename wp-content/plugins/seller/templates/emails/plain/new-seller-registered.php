<?php
/**
 * New Seller Email ( plain text )
 *
 * An email sent to the admin when a new vendor is registered.
 *
 * @class       Dokan_Email_New_Seller
 * @version     2.6.8
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

esc_html_e( 'Xin chào,', 'dokan-lite' );
echo " \n";

esc_html_e( 'Một nhà cung cấp mới đã đăng ký trên trang web của bạn  ', 'dokan-lite' );
echo " \n";

esc_html_e( 'Chi tiết nhà cung cấp:', 'dokan-lite' );
echo " \n";

echo "\n\n----------------------------------------\n\n";

// translators: 1) seller name
printf( esc_html__( 'Người bán: %s', 'dokan-lite' ), esc_html( $data['{seller_name}'] ) );
echo " \n";

// translators: 1) store name
printf( esc_html__( 'Cửa hàng nhà cung cấp: %s', 'dokan-lite' ), esc_html( $data['{store_name}'] ) );
echo " \n";

// translators: 1) seller edit url
printf( esc_html__( 'Để chỉnh sửa quyền truy cập và chi tiết của nhà cung cấp, hãy truy cập : %s', 'dokan-lite' ), esc_url( $data['{seller_edit}'] ) );

echo "\n\n----------------------------------------\n\n";

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( ! empty( $additional_content ) ) {
    echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
    echo "\n\n----------------------------------------\n\n";
}

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );