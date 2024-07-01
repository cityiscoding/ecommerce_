<?php
/**
 * New Product Email ( plain text )
 *
 * An email sent to the admin when a new Product is created by vendor.
 *
 * @class       Dokan_Email_New_Product
 * @version     2.6.8
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

esc_html_e( 'xin chào,', 'dokan-lite' );
echo " \n\n";

esc_html_e( 'Một sản phẩm mới được gửi tới và đang chờ xem xét', 'dokan-lite' );
echo " \n\n";

esc_html_e( 'Tóm tắt sản phẩm:', 'dokan-lite' );

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";

// translators: 1) product title
echo sprintf( esc_html__( 'Tiêu đề: %s', 'dokan-lite' ), esc_html( $data['{product_title}'] ) );
echo " \n";

// translators: 1) product price
echo sprintf( esc_html__( 'Giá: %1$s', 'dokan-lite' ), esc_html( $data['{price}'] ) );
echo " \n";

// translators: 1) product seller name
echo sprintf( esc_html__( 'Người bán: %1$s', 'dokan-lite' ), esc_html( $data['{store_name}'] ) );
echo " \n";

// translators: 1) product category
echo sprintf( esc_html__( 'Danh mục: %1$s', 'dokan-lite' ), esc_html( $data['{category}'] ) );
echo " \n";

esc_html_e( 'Sản phẩm hiện đang ở trạng thái "đang chờ xử lý".', 'dokan-lite' );
echo " \n";

esc_html_e( 'Trong trường hợp cần được kiểm duyệt, vui lòng truy cập URL bên dưới.', 'dokan-lite' );
echo esc_url( $data['{product_link}'] );

echo "\n\n----------------------------------------\n\n";

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( ! empty( $additional_content ) ) {
    echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
    echo "\n\n----------------------------------------\n\n";
}

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );