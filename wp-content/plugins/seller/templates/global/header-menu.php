<?php
/**
 * Dokan Header Menu Template
 *
 * @since   2.4
 *
 * @package dokan
 */
?>

<ul class="nav navbar-nav navbar-right">
  <li>
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <?php
            // translators: 1) cart total amount
            echo wp_kses_post( sprintf( __( 'Cart %s', 'dokan-lite' ), '<span class="dokan-cart-amount-top">(' . WC()->cart->get_cart_total() . ')</span>' ) );
            ?>
      <b class="caret"></b>
    </a>

    <ul class="dropdown-menu">
      <li>
        <div class="widget_shopping_cart_content"></div>
      </li>
    </ul>
  </li>

  <?php if ( is_user_logged_in() ) { ?>

  <?php

        if ( dokan_is_user_seller( $user_id ) ) {
            ?>
  <li class="dropdown">
    <a href="#" class="dropdown-toggle"
      data-toggle="dropdown"><?php esc_html_e( 'Trang quản trị kênh người bán', 'dokan-lite' ); ?> <b
        class="caret"></b></a>

    <ul class="dropdown-menu">
      <li><a href="<?php echo esc_url( dokan_get_store_url( $user_id ) ); ?>"
          target="_blank"><?php esc_html_e( 'Ghé thăm cửa hàng của bạn', 'dokan-lite' ); ?> <i
            class="fas fa-external-link-alt"></i></a></li>
      <li class="divider"></li>
      <?php
                    foreach ( $nav_urls as $key => $item ) {
                        echo wp_kses_post( sprintf( '<li><a href="%s">%s &nbsp;%s</a></li>', esc_url( $item['url'] ), $item['icon'], $item['title'] ) );
                    }
                    ?>
    </ul>
  </li>
  <?php } ?>

  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo esc_html( $current_user->display_name ); ?> <b
        class="caret"></b></a>
    <ul class="dropdown-menu">
      <li><a
          href="<?php echo esc_url( dokan_get_page_url( 'my_orders' ) ); ?>"><?php esc_html_e( 'Đơn hàng của tôi', 'dokan-lite' ); ?></a>
      </li>
      <li><a
          href="<?php echo esc_url( dokan_get_page_url( 'myaccount', 'woocommerce' ) ); ?>"><?php esc_html_e( 'Tài khoản của tôi', 'dokan-lite' ); ?></a>
      </li>
      <li><a
          href="<?php echo esc_url( wc_customer_edit_account_url() ); ?>"><?php esc_html_e( 'Chỉnh sửa tài khoản', 'dokan-lite' ); ?></a>
      </li>
      <li class="divider"></li>
      <li><a
          href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'billing', get_permalink( wc_get_page_id( 'myaccount' ) ) ) ); ?>"><?php esc_html_e( 'Địa chỉ thanh toán', 'dokan-lite' ); ?></a>
      </li>
      <li><a
          href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'shipping', get_permalink( wc_get_page_id( 'myaccount' ) ) ) ); ?>"><?php esc_html_e( 'Địa chỉ giao hàng', 'dokan-lite' ); ?></a>
      </li>
    </ul>
  </li>

  <li><?php wp_loginout( home_url() ); ?></li>

  <?php } else { ?>
  <li><a
      href="<?php echo esc_url( dokan_get_page_url( 'myaccount', 'woocommerce' ) ); ?>"><?php esc_html_e( 'Đăng nhập', 'dokan-lite' ); ?></a>
  </li>
  <li><a
      href="<?php echo esc_url( dokan_get_page_url( 'myaccount', 'woocommerce' ) ); ?>"><?php esc_html_e( 'Đăng ký', 'dokan-lite' ); ?></a>
  </li>
  <?php } ?>
</ul>