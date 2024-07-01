<form method="post" id="dokan-admin-setup-wizard">
  <h1><?php esc_html_e( 'Chào mừng đến với thế giới của PallMall.Shop Kênh người bán!', 'dokan-lite' ); ?></h1>
  <p>
    <?php
        echo wp_kses(
            sprintf(
                /* translators: %1$s: line break and opening strong tag, %2$s: closing strong tag */
                __(
                    'Cảm ơn bạn đã chọn PallMall.Shop để hỗ trợ thị trường trực tuyến của bạn! Trình hướng dẫn thiết lập nhanh này sẽ giúp bạn định cấu hình các cài đặt cơ bản. %1$sTrình hướng dẫn thiết lập này hoàn toàn là tùy chọn và nên\'t mất nhiều hơn ba phút.%2$s',
                    'dokan-lite'
                ),
                '<br><strong>',
                '</strong>'
            ),
            [
                'strong' => [],
                'br'     => [],
            ]
        );
        ?>
  </p>
  <p>
    <?php
        esc_html_e(
            'Nếu bạn chọn bỏ qua trình hướng dẫn thiết lập, bạn luôn có thể thiết lập Người bán PallMall theo cách thủ công hoặc quay lại đây và hoàn tất thiết lập thông qua Trình hướng dẫn.',
            'dokan-lite'
        );
        ?>
  </p>

  <?php if ( ! dokan_met_minimum_php_version_for_wc() ) : ?>
  <p class="dokan-no-wc-warning">
    <?php
        echo wp_kses(
            sprintf(
                /* translators: %1$s: opening anchor tag with WooCommerce plugin link, %2$s: closing anchor tag, %3$s: opening anchor tag with php update link */
                __( 'Xin lưu ý rằng %1$sWooCommerce%2$s cần thiết để Người bán PallMall hoạt động nhưng phiên bản PHP hiện tại không đáp ứng các yêu cầu tối thiểu cho WooC Commerce. %3$sVui lòng tìm hiểu thêm về việc cập nhật PHP%2$s', 'dokan-lite' ),
                '<a href="https://wordpress.org/plugins/woocommerce" target="_blank" rel="noopener">',
                '</a>',
                '<a href="https://wordpress.org/support/update-php/" target="_blank" rel="noopener">'
            ),
            [
                'a' => [
                    'href'   => [],
                    'target' => [],
                    'rel'    => [],
                ],
            ]
        );
        ?>
  </p>
  <?php else : ?>
  <p>
    <?php
        echo wp_kses(
            sprintf(
                /* translators: %1$s: opening anchor tag with WooCommerce plugin link, %2$s: closing anchor tag */
                __( 'Please note that %1$sWooCommerce%2$s is necessary for Dokan to work and it will be automatically installed if you haven\'t already done so.', 'dokan-lite' ),
                '<a href="https://wordpress.org/plugins/woocommerce" target="_blank" rel="noopener">',
                '</a>'
            ),
            [
                'a' => [
                    'href'   => [],
                    'target' => [],
                    'rel'    => [],
                ],
            ]
        );
        ?>

  <p class="wc-setup-actions step">
    <button type="submit" class="button-primary button button-large">
      <?php esc_html_e( "Đi nào!", 'dokan-lite' ); ?>
    </button>
    <input type="hidden" name="save_step" value="true">
    <?php wp_nonce_field( 'dokan-setup' ); ?>
  </p>
  </p>
  <?php endif; ?>
</form>