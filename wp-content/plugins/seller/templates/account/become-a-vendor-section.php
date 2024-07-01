<?php
/**
 * Dokan Become A Vendor Section Template.
 *
 * @since 3.7.21
 *
 * @package dokan
 */
?>

<p>&nbsp;</p>

<ul class="dokan-account-migration-lists">
  <li>
    <div class="dokan-w8 left-content">
      <p><strong><?php esc_html_e( 'Đăng ký trở thành người bán tại đây', 'dokan-lite' ); ?></strong></p>
      <p>
        <?php esc_html_e( 'Nhà cung cấp có thể bán sản phẩm và quản lý cửa hàng bằng bảng điều khiển của nhà cung cấp.', 'dokan-lite' ); ?>
      </p>
    </div>
    <div class="dokan-w4 right-content">
      <a href="<?php echo esc_url( dokan_get_page_url( 'myaccount', 'woocommerce', 'account-migration' ) ); ?>"
        class="btn btn-primary" target="_blank"><?php esc_html_e( 'ĐĂNG KÝ NGAY', 'dokan-lite' ); ?></a>
    </div>
    <div class="dokan-clearfix"></div>


  </li>
  <style>
  .btn-primary {
    background-color: #FF5622;
    /* Màu vàng */
    color: #fff;
    /* Màu chữ trắng */
    padding: 10px 20px;
    /* Căn lề nút */
    border: none;
    /* Xóa viền */
    text-decoration: none;
    /* Xóa gạch chân (nếu có) */
    display: inline-block;
    /* Hiển thị như block để có thể điều chỉnh padding và margin */
  }

  .btn-primary:hover {
    background-color: #ffcd38;
    /* Màu vàng nhạt khi hover */
    color: #fff;
    /* Màu chữ trắng */
  }
  </style>

  <?php do_action( 'dokan_customer_account_migration_list' ); ?>
</ul>