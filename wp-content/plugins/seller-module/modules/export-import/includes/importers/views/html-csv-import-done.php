<?php
/**
 * Admin View: Importer - Done!
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wc-progress-form-content woocommerce-importer">
  <section class="woocommerce-importer-done">
    <?php
        $results = array();

        if ( 0 < $imported ) {
            $results[] = sprintf(
            /* translators: %d: products count */
            _n( '%s Sản phẩm đã được nhập', '%s sản phẩm đã được nhập', $imported, 'dokan' ), '<strong>' . number_format_i18n( $imported ) . '</strong>'
            );
        }

        if ( 0 < $updated ) {
            $results[] = sprintf(
            /* translators: %d: products count */
            _n( '%s Sản phẩm đã được cập nhật', '%s sản phẩm đã được cập nhật', $updated, 'dokan' ), '<strong>' . number_format_i18n( $updated ) . '</strong>'
            );
        }

        if ( 0 < $skipped ) {
            $results[] = sprintf(
            /* translators: %d: products count */
            _n( '%s sản phẩm đã bị bỏ qua', '%s sản phẩm đã bị bỏ qua', $skipped, 'dokan' ), '<strong>' . number_format_i18n( $skipped ) . '</strong>'
            );
        }

        if ( 0 < $failed ) {
            $results [] = sprintf(
            /* translators: %d: products count */
            _n( 'Thất bại khi nhập %s sản phẩm', 'thất bại khi nhập %s sản phẩm', $failed, 'dokan' ), '<strong>' . number_format_i18n( $failed ) . '</strong>'
            );
        }

        if ( 0 < $failed || 0 < $skipped ) {
            $results[] = '<a href="#" class="woocommerce-importer-done-view-errors">' . __( 'View import log', 'dokan' ) . '</a>';
        }

        /* translators: %d: import results */
        echo wp_kses_post( __( 'Import complete!', 'dokan' ) . ' ' . implode( '. ', $results ) );
        ?>
  </section>
  <section class="wc-importer-error-log" style="display:none">
    <table class="widefat wc-importer-error-log-table">
      <thead>
        <tr>
          <th><?php esc_html_e( 'Sản phẩm', 'dokan' ); ?></th>
          <th><?php esc_html_e( 'Lý do thất bại', 'dokan' ); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
if ( count( $errors ) ) {
    foreach ( $errors as $error ) {
        if ( !is_wp_error( $error ) ) {
            continue;
        }
        $error_data = $error->get_error_data();
        ?>
        <tr>
          <th><code><?php echo esc_html( $error_data['row'] ); ?></code></th>
          <td><?php echo esc_html( $error->get_error_message() ); ?></td>
        </tr>
        <?php
    }
}
?>
      </tbody>
    </table>
  </section>
  <script type="text/javascript">
  jQuery(function() {
    jQuery('.woocommerce-importer-done-view-errors').on('click', function() {
      jQuery('.wc-importer-error-log').slideToggle();
      return false;
    });
  });
  </script>
  <div class="wc-actions">
    <a class="button button-primary"
      href="<?php echo esc_url( dokan_get_navigation_url( 'products' ) ); ?>"><?php esc_html_e( 'Xem sản phẩm', 'dokan' ); ?></a>
  </div>
</div>