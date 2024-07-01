<?php
/**
 * Monthly Reverse Withdrawal Invoice Template.
 *
 * Vendors Will get this email once in a month.
 *
 * @class ReverseWithdrawalInvoice
 *
 * @package \WeDevs\Dokan\Emails\ReverseWithdrawalInvoice
 *
 * @since 3.5.1
 *
 * @var $seller_info \WeDevs\Dokan\Vendor\Vendor
 * @var $due_status array
 * @var $data array
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p>
  <?php
    // translators: 1) store name
    printf( esc_html__( 'Chào %s,', 'dokan-lite' ), esc_html( $seller_info->get_shop_name() ) );
    ?>
</p>
<p>
  <?php
    printf(
        // translators: 1) invoice month 2) invoice year 3) store name
        esc_html__( 'Hóa đơn %1$s %2$s của bạn hiện có tại cửa hàng: %3$s.', 'dokan-lite' ),
        esc_html( $data['{month}'] ), esc_html( $data['{year}'] ), esc_html( $seller_info->get_shop_name() )
    );
    ?>
</p>
<hr>
<ul>
  <li>
    <strong>
      <?php
            // translators: 1) store name
            printf( esc_html__( 'Tóm tắt  %1$s: ', 'dokan-lite' ), esc_html( $seller_info->get_shop_name() ) );
            ?>
    </strong>
  </li>
  <li>
    <strong>
      <?php
            printf(
                // translators: 1) invoice month 2) invoice year
                esc_html__( 'Phí rút tiền đảo ngược cho %1$s %2$s:', 'dokan-lite' ),
                esc_html( $data['{month}'] ), esc_html( $data['{year}'] )
            );
            ?>
    </strong>
    <?php echo wp_kses_post( wc_price( $due_status['balance']['payable_amount'] ) ); ?>
  </li>
  <li>
    <strong>
      <?php esc_html_e( 'Ngày đáo hạn: ', 'dokan-lite' ); ?>
    </strong>
    <?php echo 'immediate' === $due_status['due_date'] ? esc_html( ucfirst( $due_status['due_date'] ) ) : esc_html( dokan_format_date( $due_status['due_date'] ) ); ?>
  </li>
</ul>

<p>
  <?php
    printf(
        wp_kses(
            '<a href="' . $data['{reverse_withdrawal_url}'] . '">' . esc_html__( 'Pay Now', 'dokan-lite' ) . '</a>',
            array(
                'a' => array(
                    'href' => array(),
                ),
            )
        )
    );
    ?>
</p>

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( ! empty( $additional_content ) ) {
    echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );