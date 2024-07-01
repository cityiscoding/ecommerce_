<div class="dokan-form-group dokan-product-type-container <?php echo esc_attr( $class ); ?>">
  <div class="content-half-part downloadable-checkbox">
    <label>
      <input type="checkbox" <?php checked( $is_downloadable, true ); ?> class="_is_downloadable" name="_downloadable"
        id="_downloadable"> <?php esc_html_e( 'Có thể tải xuống', 'dokan-lite' ); ?> <i
        class="fas fa-question-circle tips" aria-hidden="true"
        data-title="<?php esc_attr_e( 'Downloadable products give access to a file upon purchase.', 'dokan-lite' ); ?>"></i>
    </label>
  </div>
  <div class="content-half-part virtual-checkbox">
    <label>
      <input type="checkbox" <?php checked( $is_virtual, true ); ?> class="_is_virtual" name="_virtual" id="_virtual">
      <?php esc_html_e( 'Sản phẩm ảo', 'dokan-lite' ); ?> <i class="fas fa-question-circle tips" aria-hidden="true"
        data-title="<?php esc_attr_e( 'Sản phẩm ảo là sản phẩm không thể mua được.', 'dokan-lite' ); ?>"></i>
    </label>
  </div>
  <div class="dokan-clearfix"></div>
</div>