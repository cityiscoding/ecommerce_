<?php
/**
 * New Product Review Email (plain text)
 *
 * After a product has been reviewed, an email is sent to the vendor containing information about the review.
 * The email may include details such as the reviewer’s name, the product’s name and description, the review rating, and the review text.
 * The email may also contain a link to the review page where the vendor can view the review and respond to it if necessary.
 *
 * @class    \WeDevs\Dokan\Emails\VendorProductReview
 *
 * @since    3.9.2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

esc_html_e( 'Xin chào,', 'dokan-lite' );
echo " \n\n";

printf(
// translators: 1) product name, 2) customer name, 3) rating
    esc_html__( 'Chúng tôi vui mừng thông báo với bạn rằng sản phẩm %1$s của bạn đã nhận được đánh giá mới trên trang web của chúng tôi. Bài đánh giá được viết bởi %2$s và được xếp hạng %3$s trên 5 sao.', 'dokan-lite' ),
    esc_html( $data['{product_name}'] ),
    esc_html( $data['{customer_name}'] ),
    esc_html( $data['{rating}'] )
);
echo " \n\n";

esc_html_e( 'Văn bản đánh giá như sau:', 'dokan-lite' );

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo esc_html( wp_strip_all_tags( wptexturize( $data['{review_text}'] ) ) );

echo "\n\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

esc_html_e( 'Bạn có thể xem đánh giá bằng cách truy cập vào liên kết sau:', 'dokan-lite' );

printf(
    ' %s',
    esc_url( $data['{review_link}'] )
);

echo "\n\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

esc_html_e( 'Chúng tôi đánh giá cao sự tham gia của bạn vào nền tảng của chúng tôi và hy vọng rằng bạn sẽ tiếp tục cung cấp các sản phẩm và dịch vụ chất lượng cho khách hàng của chúng tôi.', 'dokan-lite' );

echo "\n\n";

esc_html_e( 'Cám ơn vì vì bạn đã quan tâm.', 'dokan-lite' );

echo "\n\n----------------------------------------\n\n";

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( ! empty( $additional_content ) ) {
    echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
    echo "\n\n----------------------------------------\n\n";
}

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );