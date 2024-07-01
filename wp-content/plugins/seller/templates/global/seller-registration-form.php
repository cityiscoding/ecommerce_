<?php
/**
 * Dokan Seller registration form
 */
?>

<div class="show_if_seller" style="<?php echo esc_attr( $role_style ); ?>">

  <div class="split-row form-row-wide">
    <p class="form-row form-group">
      <label for="full-name"><?php esc_html_e( 'Họ và tên', 'dokan-lite' ); ?> <span class="required">*</span></label>
      <input type="text" class="input-text form-control" name="fullname" id="full-name"
        value="<?php echo ! empty( $data['fullname'] ) ? esc_attr( $data['fullname'] ) : ''; ?>" required="required" />
    </p>
  </div>

  <p class="form-row form-group form-row-wide">
    <label for="company-name"><?php esc_html_e( 'Tên cửa hàng', 'dokan-lite' ); ?> <span
        class="required">*</span></label>
    <input type="text" class="input-text form-control" name="shopname" id="company-name"
      value="<?php echo ! empty( $data['shopname'] ) ? esc_attr( $data['shopname'] ) : ''; ?>" required="required" />
  </p>

  <p class="form-row form-group form-row-wide">
    <label for="seller-url" class="pull-left"><?php esc_html_e( 'Đường liên kết đến cửa hàng', 'dokan-lite' ); ?> <span
        class="required">*</span></label>
    <strong id="url-alart-mgs" class="pull-right"></strong>
    <input type="text" class="input-text form-control" name="shopurl" id="seller-url"
      value="<?php echo ! empty( $data['shopurl'] ) ? esc_attr( $data['shopurl'] ) : ''; ?>" required="required" />
    <small><?php echo esc_url( home_url() . '/' . dokan_get_option( 'custom_store_url', 'dokan_general', 'store' ) ); ?>/<strong
        id="url-alart"></strong></small>
  </p>

  <?php
    /**
     * Store Address Fields
     */
    if ( 'on' === dokan_get_option( 'enabled_address_on_reg', 'dokan_general', 'off' ) ) {
        dokan_seller_address_fields( false, true );
    }
    /**
     * @since 3.2.8
     */
    do_action( 'dokan_seller_registration_after_shopurl_field', [] );
    ?>

  <p class="form-row form-group form-row-wide">
    <label for="shop-phone"><?php esc_html_e( 'Số điện thoại cửa hàng', 'dokan-lite' ); ?><span
        class="required">*</span></label>
    <input type="text" class="input-text form-control" name="phone" id="shop-phone"
      value="<?php echo ! empty( $data['phone'] ) ? esc_attr( $data['phone'] ) : ''; ?>" required="required" />
  </p>

  <?php
    $show_terms_condition = dokan_get_option( 'enable_tc_on_reg', 'dokan_general' );
    $terms_condition_url  = dokan_get_terms_condition_url();

    if ( 'on' === $show_terms_condition && $terms_condition_url ) {
        ?>
  <p class="form-row form-group form-row-wide">
    <input class="tc_check_box" type="checkbox" id="tc_agree" name="tc_agree" required="required">
    <label style="display: inline" for="tc_agree">
      <?php
            printf(
                /* translators: %1$s: opening anchor tag with link, %2$s: an ampersand %3$s: closing anchor tag */
                __( 'I have read and agree to the %1$sTerms %2$s Conditions%3$s.', 'dokan-lite' ),
                sprintf( '<a target="_blank" href="%s">', esc_url( $terms_condition_url ) ),
                '&amp;',
                '</a>'
            );
            ?>
    </label>
  </p>
  <?php
    }
    do_action( 'dokan_seller_registration_field_after' );
    ?>
</div>

<?php do_action( 'dokan_reg_form_field' ); ?>

<p class="form-row form-group user-role vendor-customer-registration">

  <label class="radio">
    <input type="radio" name="role" value="customer" <?php checked( $role, 'customer' ); ?> class="dokan-role-customer">
    <?php esc_html_e( 'Tôi là khách hàng', 'dokan-lite' ); ?>
  </label>
  <br />
  <?php do_action( 'dokan_registration_form_role', $role ); ?>
</p>