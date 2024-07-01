<?php
/**
 * New Product Review Email
 *
 * After a product has been reviewed, an email is sent to the vendor containing information about the review.
 * The email may include details such as the reviewer’s name, the product’s name and description, the review rating, and the review text.
 * The email may also contain a link to the review page where the vendor can view the review and respond to it if necessary.
 *
 * @class     \WeDevs\Dokan\Emails\VendorProductReview
 * @since     3.9.2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php esc_attr_e( 'Hello there,', 'dokan-lite' ); ?></p>

<p>
  <?php
        printf(
            wp_kses_post(
            // translators: 1) product name, 2) customer name, 3) rating
                __( 'Chúng tôi vui mừng thông báo với bạn rằng sản phẩm <strong>%1$s</strong> của bạn đã nhận được đánh giá mới trên trang web của chúng tôi. Bài đánh giá được viết bởi <strong>%2$s</strong> và được xếp hạng <strong>%3$s</strong> trên 5 sao.', 'dokan-lite' )
            ),
            esc_html( $data['{product_name}'] ),
            esc_html( $data['{customer_name}'] ),
            esc_html( $data['{rating}'] )
        );
        ?>
</p>

<p><?php esc_html_e( 'Văn bản đánh giá như sau:', 'dokan-lite' ); ?></p>
<hr>
<p><?php echo wp_kses_post( $data['{review_text}'] ); ?></p>
<hr>
<p><?php esc_html_e( 'Bạn có thể xem đánh giá bằng cách truy cập vào liên kết sau:', 'dokan-lite' ); ?></p>
<p>
  <?php
        printf(
            '<a href="%1$s">%2$s</a>',
            esc_url( $data['{review_link}'] ),
            esc_html( $data['{product_name}'] )
        );
        ?>
</p>
<hr>
<p>
  <?php esc_html_e( 'Chúng tôi đánh giá cao sự tham gia của bạn vào nền tảng của chúng tôi và hy vọng rằng bạn sẽ tiếp tục cung cấp các sản phẩm và dịch vụ chất lượng cho khách hàng của chúng tôi.', 'dokan-lite' ); ?>
</p>
<p><?php esc_html_e( 'Cám ơn bạn đã quan tâm.', 'dokan-lite' ); ?></p>
<?php

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
    echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );