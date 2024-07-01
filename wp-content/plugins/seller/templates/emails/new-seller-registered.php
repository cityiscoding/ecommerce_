<?php
/**
 * New Seller Email.
 * @version     2.6.8
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>
<p>
  <?php esc_html_e( 'Xin chào!!!,', 'dokan-lite' ); ?>
  <br>
  <?php esc_html_e( 'Một nhà cung cấp mới đã đăng ký trên nền tảng! ', 'dokan-lite' ); ?>
  <?php echo esc_html( $data['{site_title}'] ); ?>
</p>
<p>
  <?php esc_html_e( 'Chi tiết nhà cung cấp:', 'dokan-lite' ); ?>
  <hr>
</p>
<ul>
  <li>
    <strong>
      <?php esc_html_e( 'Người bán :', 'dokan-lite' ); ?>
    </strong>
    <?php printf( '<a href="%s">%s</a>', esc_url( $data['{seller_edit}'] ), esc_html( $data['{seller_name}'] ) ); ?>
  </li>
  <li>
    <strong>
      <?php esc_html_e( 'Cửa hàng của người bán:', 'dokan-lite' ); ?>
    </strong>
    <?php printf( '<a href="%s">%s</a>', esc_url( $data['{store_url}'] ), esc_html( $data['{store_name}'] ) ); ?>
  </li>
</ul>
<p>
  <?php
    // translators: 1) seller edit url
    echo wp_kses_post( sprintf( __( 'Để chỉnh sửa quyền truy cập và chi tiết của nhà cung cấp <a href="%s">Bấm vào đây</a>', 'dokan-lite' ), esc_url( $data['{seller_edit}'] ) ) );
    ?>
</p>

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
    echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}
do_action( 'woocommerce_email_footer', $email );