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

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>
<p>
  <?php
    // translators: user name
    echo sprintf( esc_html__( 'Chào %s', 'dokan-lite' ), esc_html( $data['{store_name}'] ) );
    ?>
</p>
<p>
  <?php esc_html_e( 'Yêu cầu rút tiền của bạn đã được chấp thuận, xin chúc mừng!', 'dokan-lite' ); ?>
</p>
<p>
  <?php esc_html_e( 'Bạn đã gửi yêu cầu rút tiền của:', 'dokan-lite' ); ?>
  <br>
  <?php esc_html_e( 'Số lượng : ', 'dokan-lite' ); ?>
  <?php echo wp_kses_post( $data['{amount}'] ); ?>
  <br>
  <?php
    // translators: 1) withdraw method title
    echo sprintf( esc_html__( 'Phương thức : %s', 'dokan-lite' ), esc_html( $data['{method}'] ) );
    ?>
</p>
<p>
  <?php esc_html_e( 'Chúng tôi sẽ sớm chuyển số tiền này sang phương thức rút tiền bạn đã lựa chọn.', 'dokan-lite' ); ?>

  <?php esc_html_e( 'Cảm ơn đã đồng hành cùng chúng tôi.', 'dokan-lite' ); ?>
</p>

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
    echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );