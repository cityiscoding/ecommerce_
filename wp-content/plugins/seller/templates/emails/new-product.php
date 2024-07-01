<?php
/**
 * New Product Email.
 *
 * An email sent to the admin when a new Product is created by vendor.
 *
 * @class       Dokan_Email_New_Product
 * @version     2.6.8
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php esc_attr_e( 'Xin chào,', 'dokan-lite' ); ?></p>

<p><?php esc_attr_e( 'Một sản phẩm mới được gửi đến trang web của bạn', 'dokan-lite' ); ?> <a
    href="<?php echo esc_url( $data['{site_url}'] ); ?>"><?php echo esc_attr( $data['{site_title}'] ); ?></a> </p>
<p><?php esc_attr_e( 'Tóm tắt sản phẩm:', 'dokan-lite' ); ?></p>
<hr>
<ul>
  <li>
    <strong>
      <?php esc_attr_e( 'Tiêu đề :', 'dokan-lite' ); ?>
    </strong>
    <?php printf( '<a href="%s">%s</a>', esc_url( $data['{product_link}'] ), esc_attr( $data['{product_title}'] ) ); ?>
  </li>
  <li>
    <strong>
      <?php esc_attr_e( 'Giá :', 'dokan-lite' ); ?>
    </strong>
    <?php echo esc_html( $data['{price}'] ); ?>
  </li>
  <li>
    <strong>
      <?php esc_attr_e( 'Người bán :', 'dokan-lite' ); ?>
    </strong>
    <?php printf( '<a href="%s">%s</a>', esc_url( $data['{seller_url}'] ), esc_attr( $data['{store_name}'] ) ); ?>
  </li>
  <li>
    <strong>
      <?php esc_attr_e( 'Danh mục :', 'dokan-lite' ); ?>
    </strong>
    <?php echo esc_html( $data['{category}'] ); ?>
  </li>

</ul>
<p>
  <?php esc_attr_e( 'Sản phẩm hiện đang ở trạng thái "xuất bản".Vì vậy, mọi người đều có thể xem sản phẩm.', 'dokan-lite' ); ?>
</p>

<?php

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
    echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );