<?php
/**
 * New Withdraw request Email.
 *
 * An email sent to the admin when a new withdraw request is created by vendor.
 *
 * @class       Dokan_Vendor_Withdraw_Request
 * @version     2.6.8
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>
<p>
  <?php esc_html_e( 'Xin chàooooo,', 'dokan-lite' ); ?>
</p>
<p>
  <?php esc_html_e( 'Một yêu cầu rút tiền mới đã được thực hiện bởi', 'dokan-lite' ); ?>
  <?php echo esc_html( $data ['{store_name}'] ); ?>.
</p>
<hr>
<ul>
  <li>
    <strong>
      <?php esc_html_e( 'Tên cửa hàng : ', 'dokan-lite' ); ?>
    </strong>
    <?php
        printf( '<a href="%s">%s</a>', esc_url( $data['{profile_url}'] ), esc_html( $data['{store_name}'] ) );
        ?>
  </li>
  <li>
    <strong>
      <?php esc_html_e( 'Số tiền yêu cầu:', 'dokan-lite' ); ?>
    </strong>
    <?php echo wp_kses_post( $data['{amount}'] ); ?>
  </li>
  <li>
    <strong>
      <?php esc_html_e( 'Phương thức thanh toán: ', 'dokan-lite' ); ?>
    </strong>
    <?php echo esc_html( $data['{method}'] ); ?>
  </li>
</ul>

<?php
echo wp_kses_post(
    sprintf(
        // translators: 1) withdraw page url
        __( 'Bạn có thể phê duyệt hoặc từ chối nó bằng cách<a href="%s">ấn vào đây</a>', 'dokan-lite' ), esc_url( $data['{withdraw_page}'] )
    )
);

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
    echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );