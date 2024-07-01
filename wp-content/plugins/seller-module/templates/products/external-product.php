<?php

/**
 * External product template
 *
 * @since Dokan_PRO_SINCE
 *
 * @package Dokan
 */

$_product_url = get_post_meta( $post_id, '_product_url', true );
$_button_text = get_post_meta( $post_id, '_button_text', true );
?>

<div class="dokan-external-product-content show_if_external">
  <div class="dokan-form-group">
    <label class="form-label"><?php echo esc_html_e( 'URL Sản phẩm ', 'dokan' ); ?></label>
    <input type="text" class="dokan-form-control" style="" name="_product_url" id="_product_url"
      value="<?php echo esc_url_raw( $_product_url ); ?>" placeholder="https://">
    <span><?php echo esc_html_e( 'Nhập URL bên ngoài vào sản phẩm.', 'dokan' ); ?></span>
  </div>

  <div class="dokan-form-group">
    <label class="form-label"><?php echo esc_html_e( 'Nút văn bản', 'dokan' ); ?></label>
    <input type="text" class="dokan-form-control" name="_button_text" id="_button_text"
      value="<?php echo esc_html( $_button_text ); ?>"
      placeholder="<?php echo esc_attr_e( 'Buy product', 'dokan' ); ?>"> <span
      class="description"><?php echo esc_html_e( 'Văn bản này sẽ được hiển thị trên nút liên kết với sản phẩm bên ngoài.', 'dokan' ); ?></span>
  </div>
</div>