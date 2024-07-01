<?php

defined( 'ABSPATH' ) || exit;

/**
 * Dokan Manage Reviews Template.
 *
 * @since 3.7.13
 *
 * @package dokan
 */
?>

<form id="dokan_comments-form" action="" method="post">
  <div class="dokan-form-group">
    <select name="comment_status">
      <option value="none"><?php esc_html_e( 'Hành động hàng loạt', 'dokan' ); ?></option>
      <?php
            if ( $comment_status === 'hold' ) {
                ?>
      <option value="approve"><?php esc_html_e( 'Đánh dấu phê duyệt', 'dokan' ); ?></option>
      <option value="spam"><?php esc_html_e( 'Đánh dấu Spam', 'dokan' ); ?></option>
      <option value="trash"><?php esc_html_e( 'Đánh dấu Rác', 'dokan' ); ?></option>
      <?php } elseif ( $comment_status === 'spam' ) { ?>
      <option value="approve"><?php esc_html_e( 'Đánh dấu không phải rác', 'dokan' ); ?></option>
      <option value="delete"><?php esc_html_e( 'Xóa vĩnh viễn', 'dokan' ); ?></option>
      <?php } elseif ( $comment_status === 'trash' ) { ?>
      <option value="approve"><?php esc_html_e( 'Khôi phục', 'dokan' ); ?></option>
      <option value="delete"><?php esc_html_e( 'Xóa vĩnh viễn', 'dokan' ); ?></option>
      <?php } else { ?>
      <option value="hold"><?php esc_html_e( 'Đánh dấu đang chờ xử lý', 'dokan' ); ?></option>
      <option value="spam"><?php esc_html_e( 'Đánh dấu Spam', 'dokan' ); ?></option>
      <option value="trash"><?php esc_html_e( 'Đánh dấu Rác', 'dokan' ); ?></option>
      <?php
            }
            ?>
    </select>

    <?php wp_nonce_field( 'dokan_comment_nonce_action', 'dokan_comment_nonce' ); ?>

    <input type="submit" value="<?php esc_html_e( 'Áp dụng', 'dokan' ); ?>" class="dokan-btn dokan-btn-sm"
      name="comt_stat_sub">
  </div>