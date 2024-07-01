<?php
/*
Hiển thị bán bởi. product page
*/
 
 add_action( 'woocommerce_after_shop_loop_item_title','sold_by' );
   function sold_by(){
   ?>
</a>
<?php
           global $product;
           $seller = get_post_field( 'post_author', $product->get_id());
            $author  = get_user_by( 'id', $seller );
           $store_info = dokan_get_store_info( $author->ID );
 
           if ( !empty( $store_info['store_name'] ) ) { ?>
<span class="details">
  <?php printf( 'Bán bởi: <a href="%s">%s</a>', dokan_get_store_url( $author->ID ), $author->display_name ); ?>
</span>
<?php
 
       }
 

 
 
   }
// ẩn trạng thái đơn hàng đã hoàn thành.
function hide_order_status_options( $order ) {
    ?>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
  // Lấy trạng thái hiện tại của đơn hàng
  var currentStatus = document.querySelector('#order_status').value;

  // Nếu trạng thái hiện tại là 'Đã hoàn thành' (wc-completed)
  if (currentStatus === 'wc-completed') {
    // Lấy các tùy chọn trạng thái đơn hàng
    var options = document.querySelectorAll('#order_status option');

    // Lặp qua các tùy chọn và ẩn những tùy chọn 'Đang xử lý' và 'Đang giao hàng'
    options.forEach(function(option) {
      if (option.value === 'wc-processing' || option.value === 'wc-sd-delivering') {
        option.style.display = 'none';
      }
    });
  }
});
</script>
<?php
}
add_action( 'dokan_order_details_after_order_items', 'hide_order_status_options', 10, 1 );
// continie
function hide_order_status_options1() {
    ?>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
  var form = document.getElementById('dokan-order-status-form');

  if (form) {
    form.addEventListener('submit', function(event) {
      event.preventDefault(); // Ngăn chặn submit mặc định
      var currentStatus = document.getElementById('order_status').value;

      if (currentStatus === 'wc-completed') {
        // Lấy các tùy chọn trạng thái đơn hàng
        var options = document.querySelectorAll('#order_status option');

        // Lặp qua các tùy chọn và ẩn những tùy chọn 'Đang xử lý' và 'Đang giao hàng'
        options.forEach(function(option) {
          if (option.value === 'wc-processing' || option.value === 'wc-sd-delivering') {
            option.style.display = 'none';
          }
        });
      }

      // Submit form và reload trang
      form.submit();
      window.location.reload();
    });
  }
});
</script>
<?php
}
add_action( 'wp_footer', 'hide_order_status_options' );
// contine
function custom_hide_order_status_options() {
    ?>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
  var form = document.getElementById('dokan-order-status-form');
  var orderStatus = document.getElementById('order_status');
  var label = document.querySelector('.dokan-label-success');

  if (label && label.innerText.trim() === 'Đã hoàn thành') {
    var options = orderStatus.querySelectorAll('option');
    options.forEach(function(option) {
      if (option.value === 'wc-processing' || option.value === 'wc-sd-delivering') {
        option.style.display = 'none';
      }
    });
  }

  if (form) {
    form.addEventListener('submit', function(event) {
      // Cho phép submit form
      form.submit();
      // Reload trang sau khi submit
      window.location.reload();
    });
  }
});
</script>
<?php
}
add_action( 'wp_footer', 'custom_hide_order_status_options' );




//
function custom_hide_edit_option_for_pending_orders1() {
?>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
  // Lấy tất cả các phần tử li có class order-status
  const orderStatusElements = document.querySelectorAll('.order-status li');

  orderStatusElements.forEach(function(element) {
    // Tìm label chứa trạng thái đơn hàng
    const statusLabel = element.querySelector('.dokan-label');
    if (statusLabel && statusLabel.textContent.trim() === 'Đang-chờ') {
      // Ẩn link "Chỉnh sửa" nếu trạng thái là "Đang-chờ"
      const editLink = element.querySelector('.dokan-edit-status');
      if (editLink) {
        editLink.style.display = 'none';
      }
    }
  });
});
</script>
<?php
}
add_action('wp_footer', 'custom_hide_edit_option_for_pending_orders');


// check out
function defined_countries_for_phone_field() {
    return array('VN'); // Sửa lại để chỉ có Việt Nam
}
// Remove "(optional)" from required "Billing phone" field
add_filter('woocommerce_form_field', 'remove_checkout_optional_fields_label', 10, 4);
function remove_checkout_optional_fields_label($field, $key, $args, $value) {
    // Get the defined countries codes
    $countries = defined_countries_for_phone_field();

    // Get Customer shipping country
    $shipping_country = WC()->customer->get_shipping_country();

    // Only on checkout page and My account > Edit address for billing phone field
    if ('billing_phone' === $key && ((is_wc_endpoint_url('edit-address') && !in_array($shipping_country, $countries)) || is_checkout())) {
        $optional = '&nbsp;<span class="optional">(' . esc_html__('optional', 'woocommerce') . ')</span>';
        $field = str_replace($optional, '', $field);
    }
    return $field;
}
// Make the billing phone field required
add_filter('woocommerce_billing_fields', 'filter_billing_phone_field', 10, 1);
function filter_billing_phone_field($fields) {
    // Get the defined countries codes
    $countries = defined_countries_for_phone_field();

    // Get Customer shipping country
    $shipping_country = WC()->customer->get_shipping_country();

    // Only on checkout page and My account > Edit address
    if ((is_wc_endpoint_url('edit-address') && !in_array($shipping_country, $countries)) || is_checkout()) {
        $fields['billing_phone']['required'] = true;
    }

    return $fields;
}
add_filter('woocommerce_checkout_fields', 'dms_custom_override_checkout_fields', 9999999);
function dms_custom_override_checkout_fields($fields)
{
//billing
$fields['billing']['billing_country']['priority'] = 1;
$fields['billing']['billing_first_name'] = array(
'label' => __('Họ và tên'),
'placeholder' => _x('Ví dụ: Trần Thành Phố', 'placeholder'),
'required' => true,
'class' => array('form-row-wide'),
'clear' => true,
'priority' => 10
);
$fields['billing']['billing_phone']['priority'] = 20;
$fields['billing']['billing_email']['priority'] = 20;
$fields['billing']['billing_email']['placeholder'] = _x('tranthanhpho.dev@gmail.com','placeholder');
unset($fields['billing']['billing_last_name']);
unset($fields['billing']['billing_company']);
unset($fields['billing']['billing_postcode']);
unset($fields['billing']['billing_state']);
unset($fields['billing']['billing_address_2']);
//
$fields['billing']['billing_phone']['placeholder'] = _x('Số điện thoại nhận hàng', 'placeholder');
$fields['billing']['billing_address_1']['class'] = array('form-row-wide');
$fields['billing']['billing_address_1']['priority'] = 50;
$fields['billing']['billing_address_1']['label'] = _x('Địa chỉ cụ thể', 'placeholder');
$fields['billing']['billing_address_1']['placeholder'] = _x('Ví dụ: Ấp 3 Xã An Xuyên Thành Phố Cà Mau', 'placeholder');
$fields['billing']['billing_city']['priority'] = 120;
$fields['billing']['billing_district']['priority'] = 120;
$fields['billing']['billing_ward']['priority'] = 120;


//shipping
$fields['shipping']['shipping_first_name'] = array(
'label' => __('Họ và tên'),
'placeholder' => _x('Họ và tên', 'placeholder'),
'required' => true,
'class' => array('form-row-first'),
'clear' => true,
'priority' => 10
);

$fields['shipping']['shipping_address_1']['class'] = array('form-row-wide');
$fields['shipping']['shipping_phone'] = array(
'label' => __('Số điện thoại'),
'placeholder' => _x('Số điện thoại', 'placeholder'),
'required' => true,
'class' => array('form-row-last'),
'clear' => true,
'priority' => 20
);
uasort($fields['billing'], 'dms_sort_fields_by_order');
uasort($fields['shipping'], 'dms_sort_fields_by_order');
return $fields;
}
// Định nghĩa hàm sắp xếp theo thứ tự
function dms_sort_fields_by_order($a, $b) {
    $priorityA = isset($a['priority']) ? $a['priority'] : 0;
    $priorityB = isset($b['priority']) ? $b['priority'] : 0;
    return $priorityA - $priorityB;
}


function ts_hide_ship_to_different_address_checkbox() {
if (is_checkout()) {
echo '<style>
#ship-to-different-address label {
  display: none;
}
</style>';
}
}
add_action('wp_head', 'ts_hide_ship_to_different_address_checkbox');
add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 9999 );

// Chỉnh sửa trường billing_email để chỉ có thể xem (read-only)
// Ẩn checkbox "Ship to a different address" trên trang thanh toán
add_action('wp_head', 'ts_hide_ship_to_different_address_checkbox');
add_filter('woocommerce_enable_order_notes_field', '__return_false', 9999);

// Chỉnh sửa trường billing_email để chỉ có thể xem (read-only)
function custom_billing_email_readonly_script() {
    // Chỉ thêm mã JavaScript nếu ở trang Checkout của WooCommerce
    if (is_checkout()) {
        ?>
<script>
jQuery(document).ready(function($) {
  // Chọn ô nhập liệu theo ID
  var billingEmailInput = $('#billing_email');

  // Kiểm tra xem ô nhập liệu có tồn tại không
  if (billingEmailInput.length) {
    // Đặt thuộc tính readonly cho ô nhập liệu
    billingEmailInput.prop('readonly', true);
  }
});
</script>
<?php
    }
}
add_action('wp_footer', 'custom_billing_email_readonly_script');

// Chỉnh sửa trường billing_country để chỉ có thể xem (read-only)
function custom_billing_country_readonly_script() {
    // Chỉ thêm mã JavaScript nếu ở trang Checkout của WooCommerce
    if (is_checkout()) {
        ?>
<script>
jQuery(document).ready(function($) {
  // Chọn ô nhập liệu theo ID
  var billingCountryInput = $('#billing_country');

  // Kiểm tra xem ô nhập liệu có tồn tại không
  if (billingCountryInput.length) {
    // Đặt thuộc tính readonly cho ô nhập liệu
    billingCountryInput.prop('readonly', true);
  }
});
</script>
<?php
    }
}



// tự động tính toán lại phí vận chuyển khi người dùng nhập input click chuột
add_action('wp_footer', 'custom_billing_email_readonly_script');
// Thêm đoạn mã JavaScript trong trang Checkout
function custom_automatic_shipping_calculation_script() {
// Chỉ thêm mã JavaScript nếu ở trang Checkout của WooCommerce
if (is_checkout()) {
?>
<script>
jQuery(document).ready(function($) {
  // Lắng nghe sự kiện khi người dùng nhập liệu và di chuyển ra khỏi ô nhập liệu
  $('body').on('change', 'input, select', function() {
    // Gọi hàm tính toán lại vận chuyển và tổng tiền của WooCommerce
    $('body').trigger('update_checkout');
  });
});
</script>
<?php
    }
}

//chặn người dùng không cho người dùng request khi checkout

function custom_disable_checkout_fields_script() {
    // Chỉ thêm mã JavaScript nếu ở trang Checkout của WooCommerce
    if (is_checkout()) {
        ?>
<script>
jQuery(document).ready(function($) {
  // Lắng nghe sự kiện khi người dùng ấn nút "Đặt hàng"
  $('form.checkout').on('submit', function() {
    // Ngăn chặn sự kiện mặc định (submit form)
    return false;
  });
});
</script>
<?php
    }
}
add_action('wp_footer', 'custom_disable_checkout_fields_script');

// end check out
//them dang ký
// Thêm trường "Nhập lại mật khẩu" vào form đăng ký
function flatsome_child_add_confirm_password_field() {
    ?>
<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide" id="confirm_password_field">
  <label for="reg_password2"><?php esc_html_e('Nhập lại mật khẩu', 'flatsome'); ?> <span
      class="required">*</span></label>
  <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password2"
    id="reg_password2" autocomplete="new-password" />
</p>
<?php
}
add_action('woocommerce_register_form', 'flatsome_child_add_confirm_password_field', 20);
function flatsome_child_enqueue_scripts() {
    wp_enqueue_script( 'custom-register', get_stylesheet_directory_uri() . 'custom.js', array('jquery'), null, true );
}
add_action( 'wp_enqueue_scripts', 'flatsome_child_enqueue_scripts' );
// Kiểm tra mật khẩu và xác nhận mật khẩu
function flatsome_child_check_password_match( $username, $email, $validation_errors ) {
    if ( isset( $_POST['password'] ) && isset( $_POST['password2'] ) ) {
        if ( $_POST['password'] !== $_POST['password2'] ) {
            $validation_errors->add( 'password_error', __( 'Mật khẩu nhập lại không chính xác', 'flatsome' ) );
        }
    }
    return $validation_errors;
}
add_action('woocommerce_register_post', 'flatsome_child_check_password_match', 10, 3);
// Lưu mật khẩu khi đăng ký thành công
function flatsome_child_save_password( $customer_id ) {
    if ( isset( $_POST['password'] ) ) {
        $password = $_POST['password'];
        wp_set_password( $password, $customer_id );
    }
}
add_action('woocommerce_created_customer', 'flatsome_child_save_password');





//
function hide_news_section() {
    echo '<style>
        .postbox.dokan-postbox:has(h2:contains("Tin tức")) {
            display: none !important;
        }
    </style>';
}
add_action('admin_head', 'hide_news_section');

function hide_news_section_script() {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var newsSection = document.querySelector(".postbox.dokan-postbox:has(h2:contains(\'Tin tức\'))");
            if (newsSection) {
                newsSection.style.display = "none";
            }
        });
    </script>';
}
add_action('admin_footer', 'hide_news_section_script');
//
function custom_admin_css() {if (is_admin())
    echo '<style>
        .plugin-description .fyi {
            display: none;
        }
        .thickbox.open-plugin-details-modal {
            display: none;
        }
        .active.update.is-uninstallable .second.plugin-version-author-uri {
            display: none;
        }
        .column-description .desc {
            display: none;
        }
        .column-auto-updates{
            display: none;
        }
        .wp-mail-smtp-support{
            display: none;
        }
        .wp-mail-smtp-docs{
            display: none;
        }
        .row-actions .1 a {
            display: none;
        } 
        .dokan-admin-header  {
            display: none !important;
        }
        .settings-document-button
        {
            display: none;
        }
            .module-plan{
            display: none !important;
        }
    </style>';
}
add_action('admin_head', 'custom_admin_css');
///////////////////////////////////
function hide_dokan_admin_header_logo() {if (is_admin())
    ?>
<style>
.dokan-admin-header-logo {
  display: none !important;
}

.support {
  display: none !important;
}

.upgrade {
  display: none !important;
}

.dokan-settings-wrap .nav-tab-wrapper .nab-section .search-box {

  display: none !important;
}
</style>
<?php
}
add_action('admin_head', 'hide_dokan_admin_header_logo');
function hide_dokan_news_content() {if (is_admin())
    ?>
<style>
.rss-widget {
  display:  !important;
}

.hndle font-bold {
  display: none;
}

.handlediv {
  display: none;
}

.postbox-header .hndle font-bold {
  display: none;
}

.postbox dokan-postbox {
  display: none;
}

.search-box {
  display: none;
}

.module-plan {
  display:  !important;
}

.rss-widget {
  display: none;
}
</style>
<?php
}
// tin tuc
add_action('admin_head', 'hide_news_tab_js');

function hide_news_tab_js() {
    echo '<script>
        jQuery(document).ready(function($) {
            // Ẩn tab "Tin tức" trong trang quản trị
            $(".postbox.dokan-postbox h2 span:contains(\'Tin tức\')").closest(".postbox").hide();
        });
    </script>';
}
// tin tuc 2
add_action('admin_head', 'hide_newss_tab_js');

function hide_newss_tab_js() {
    ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  function hideNewsTab() {
    // Select the "Tin tức" section using a more specific selector
    document.querySelectorAll('.postbox.dokan-postbox h2 span').forEach(function(element) {
      if (element.textContent.trim() === 'Tin tức') {
        element.closest('.postbox.dokan-postbox').style.display = 'none';
      }
    });
  }

  // Initial hide on page load
  hideNewsTab();

  // Observe changes in the DOM to handle dynamic updates
  var observer = new MutationObserver(hideNewsTab);

  // Observe the entire body for changes
  observer.observe(document.body, {
    childList: true,
    subtree: true
  });

  // Reapply hide on AJAX complete (when switching tabs)
  jQuery(document).ajaxComplete(hideNewsTab);

  // Add event listener for tab clicks to handle manual tab switches
  jQuery(document).on('click', '.nav-tab', function() {
    setTimeout(hideNewsTab, 100);
  });
});
</script>
<?php
}


//
add_action('admin_head', 'hide_dokan_updates_tab_js');

function hide_dokan_updates_tab_js() {
    echo '<script>
        jQuery(document).ready(function($) {
            // Ẩn tab "<a href="admin.php?page=dokan_updates"></a></li></ul></li>"
            $("a[href=\'admin.php?page=dokan_updates\']").closest("li").hide();
        });
    </script>';
}
// !!!!!!!


//
add_action('wp_footer', 'custom_admin_order_status_script1');
////////////////////////

function custom_admin_order_status_script1() {
    ?>
<script type="text/javascript">
jQuery(function($) {
  // Xóa lựa chọn "Cancelled" và "Delivered"
  $('#shipping_status option[value="ss_cancelled"]').remove();
  $('#shipping_status option[value="ss_delivered"]').remove();
});
</script>
<?php
}

//

add_action('admin_head', 'hide_news_tab_js1');

function hide_news_tab_js1() {
    echo '<script>
        jQuery(document).ready(function($) {
            // Ẩn tab "Tin tức" trong trang quản trị
            $(".postbox.dokan-postbox h2 span:contains(\'Tin tức\')").closest(".postbox").hide();
        });
    </script>';
}

// hide module
function hide_dokan_news_content1() {if (is_admin())
    ?>
<style>
.module-plan {
  display: none;
}
</style>
<?php
}

//hide text get help
 
function hide_dokan_news_content2() {if (is_admin())
    ?>
<style>
.help-text {
  display: none;
}
</style>
<?php
}

//hide edit shiping:
add_action('wp_footer', 'custom_admin_order_status_script2');

function custom_admin_order_status_script2() {
    ?>
<script type="text/javascript">
jQuery(function($) {
  // Xóa lựa chọn "Cancelled" và "Delivered"
  $('#shipping_status option[value="ss_cancelled"]').remove();
  $('#shipping_status option[value="ss_delivered"]').remove();
});
</script>
<?php
}

//hide 3
add_action('admin_footer', 'custom_admin_order_statuss_script');

function custom_admin_order_statuss_script() {
    ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
  // Lặp qua tất cả các dropdown với id bắt đầu bằng update_shipping_status_
  $('select[id^="update_shipping_status_"]').each(function() {
    // Xóa lựa chọn "Cancelled" và "Delivered"
    $(this).find('option[value="ss_cancelled"]').remove();
    $(this).find('option[value="ss_delivered"]').remove();
  });
});
</script>
<?php
}
//hide help 2
add_action('admin_footer', 'hide_dokan_helps_content');
function hide_dokan_helps_content() {if (is_admin())
    ?>
<style>
.help-block help-block-ltr {
  display: none;
}
</style>
<?php
}
//hide help 3
add_action('admin_footer', 'hide_dokan_helps2_content');
function hide_dokan_helps2_content() {if (is_admin())
    ?>
<style>
.help-block help-block-ltr .help-text {
  display: none;
}
</style>
<?php
}
//hide help 3
// doan nay bi loi
add_action('admin_footer', 'hide_dokan_helps3_content');
function hide_dokan_helps3_content() {if (is_admin())
    ?>
<style>
.help-text {
  display: none !important;
}
</style>
<?php
}

// // ẩn phương thức vận chuyển đối với id.
function custom_hide_shipping_method_for_specific_products( $rates, $package ) {
    // Array of product IDs to limit the shipping method
    $product_ids_to_limit = array(437,435,446,447); // id sản phẩm chặn phương thức vận chuyển

    // Loop through cart items to check for specific products
    foreach ( WC()->cart->get_cart() as $cart_item ) {
        $product = $cart_item['data'];
        if ( in_array( $product->get_id(), $product_ids_to_limit ) ) {
            // Unset the shipping methods you want to hide
            if ( isset( $rates['free_shipping:4'] ) ) {
                unset( $rates['free_shipping:4'] );
            }
            if ( isset( $rates['sd_shipdepot_shipping_method'] ) ) {
                unset( $rates['sd_shipdepot_shipping_method'] );
            }
            break; // Break loop if product found
        }
    }

    return $rates;
}
add_filter( 'woocommerce_package_rates', 'custom_hide_shipping_method_for_specific_products', 100, 2 );


// end doan loi
// login
// Kiểm tra xem tên đăng nhập có chứa khoảng trắng không
add_filter( 'woocommerce_registration_errors', 'custom_username_validation', 10, 3 );
function custom_username_validation( $errors, $username, $email ) {
    if ( strpos( $username, ' ' ) !== false ) {
        $errors->add( 'username_space_error', __( 'Tên tài khoản không được chứa khoảng chắn!', 'woocommerce' ) );
    }
    return $errors;
}
//
// check họ và tên
// Xác thực và hiển thị thông báo lỗi nếu các trường bắt buộc không được điền đầy đủ
add_action( 'woocommerce_register_post', 'validate_registration_fields', 10, 3 );
function validate_registration_fields( $username, $email, $validation_errors ) {
    if ( isset( $_POST['fullname'] ) && empty( $_POST['fullname'] ) ) {
        $validation_errors->add( 'fullname_error', __( 'Họ và Tên là bắt buộc.', 'woocommerce' ) );
    }
    if ( isset( $_POST['email'] ) && empty( $_POST['email'] ) ) {
        $validation_errors->add( 'email_error', __( 'Địa chỉ email là bắt buộc.', 'woocommerce' ) );
    }
    if ( isset( $_POST['password'] ) && empty( $_POST['password'] ) ) {
        $validation_errors->add( 'password_error', __( 'Mật khẩu là bắt buộc.', 'woocommerce' ) );
    }
    if ( isset( $_POST['password'] ) && isset( $_POST['password_confirm'] ) && $_POST['password'] !== $_POST['password_confirm'] ) {
        $validation_errors->add( 'password_confirm_error', __( 'Mật khẩu nhập xác nhận không khớp.', 'woocommerce' ) );
    }
    return $validation_errors;
}
// check nhập lại mật khẩu
add_action( 'woocommerce_register_post', 'validate_password_confirmation', 10, 3 );
function validate_password_confirmation( $username, $email, $validation_errors ) {
    if ( isset( $_POST['password'] ) && isset( $_POST['password_confirm'] ) ) {
        if ( $_POST['password'] !== $_POST['password_confirm'] ) {
            $validation_errors->add( 'password_error', __( 'Mật khẩu nhập lại không giống nhau', 'woocommerce' ) );
        }
    }
    return $validation_errors;
}

// Ẩn tùy chọn "Delivered" và "Cancelled" từ dropdown
function hide_shipment_status_options($options) {
    unset($options['ss_delivered']);
    unset($options['ss_cancelled']);
    return $options;
}
add_filter('woocommerce_shipment_tracking_statuses', 'hide_shipment_status_options');

add_action('wp_footer', 'custom_hide_shipmentt_status_script');

function custom_hide_shipmentt_status_script() {
    ?>
<script type="text/javascript">
jQuery(function($) {
  // Xóa lựa chọn "Cancelled" và "Delivered"
  $('#update_shipping_status_3 option[value="ss_cancelled"]').remove();
  $('#update_shipping_status_3 option[value="ss_delivered"]').remove();
});
</script>
<?php
}

// hide in amdin
add_action('admin_footer', 'custom_hide_admin_shipment_statuses');

function custom_hide_admin_shipment_statuses() {
    ?>
<script type="text/javascript">
jQuery(function($) {
  // Tìm và ẩn các mục "Delivered" và "Cancelled"
  $('.dokan-settings-repeatable-list li:contains("Delivered")').hide();
  $('.dokan-settings-repeatable-list li:contains("Cancelled")').hide();
});
</script>
<?php
}

// button cancel order
add_action( 'woocommerce_order_details_after_order_table', 'add_cancel_order_button' );

function add_cancel_order_button( $order ) {
    if ( $order->has_status( 'processing' ) ) {
        echo '<a href="' . wp_nonce_url( add_query_arg( 'cancel_order', $order->get_id() ) ) . '" class="button">Hủy đơn hàng</a>';
    }
}

add_action( 'init', 'cancel_order' );

function cancel_order() {
    if ( isset( $_GET['cancel_order'] ) && wp_verify_nonce( $_GET['_wpnonce'] ) ) {
        $order_id = absint( $_GET['cancel_order'] );
        $order = wc_get_order( $order_id );

        if ( $order && $order->has_status( 'processing' ) ) {
            $order->update_status( 'cancelled', 'Đơn hàng đã được hủy bởi khách hàng', true );
            wp_redirect( $order->get_view_order_url() );
            exit;
        }
    }
}


// Hạn chế thay đổi trạng thái đơn hàng của người bán
function custom_dokan_limit_order_status_change( $order_id, $old_status, $new_status ) {
    if ( current_user_can( 'seller' ) ) {
        // Danh sách các trạng thái mà người bán được phép thay đổi
        $allowed_statuses = array( 'sd-delivering', 'completed', 'sd-delivery-failed' );

        // Nếu trạng thái mới không nằm trong danh sách cho phép, hủy bỏ thay đổi
        if ( ! in_array( $new_status, $allowed_statuses ) ) {
            $order = wc_get_order( $order_id );
            $order->update_status( $old_status, __( 'Người bán không có quyền thay đổi trạng thái này.', 'dokan' ), true );

            // Thông báo lỗi
            wc_add_notice( __( 'Lỗi: Bạn không thể cập nhật trạng thái cũ. Trạng thái đơn hàng đã ở "Đang xử lý" trước đó"', 'dokan' ), 'error' );
        }
    }
}
add_action( 'woocommerce_order_status_changed', 'custom_dokan_limit_order_status_change', 10, 3 );

/// hạn chế complete
add_action( 'wp_footer', 'custom_hide_shipping_options_for_completed_orders' );
function custom_hide_shipping_options_for_completed_orders() {
    ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
  // Lấy giá trị của trạng thái đơn hàng
  var orderStatus = $('#order_status').val();

  // Kiểm tra nếu đơn hàng đã hoàn thành
  if (orderStatus === 'wc-completed') {
    // Ẩn các phần tử liên quan đến tùy chọn giao hàng
    $('#order_status').closest('li').hide();
    $('#dokan-order-status-form').find('input[type="submit"]').hide();
    $('#dokan-order-status-form').find('.dokan-cancel-status').hide();
    $('.dokan-edit-status').hide(); // Ẩn liên kết chỉnh sửa
  }
});
</script>
<?php
}

/// hạn chế wc-sd-delivery-failed
add_action( 'wp_footer', 'custom_hide_shipping_options_for_completed_orders1' );
function custom_hide_shipping_options_for_completed_orders1() {
    ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
  // Lấy giá trị của trạng thái đơn hàng
  var orderStatus = $('#order_status').val();

  // Kiểm tra nếu đơn hàng đã hoàn thành
  if (orderStatus === 'wc-sd-delivery-failed') {
    // Ẩn các phần tử liên quan đến tùy chọn giao hàng
    $('#order_status').closest('li').hide();
    $('#dokan-order-status-form').find('input[type="submit"]').hide();
    $('#dokan-order-status-form').find('.dokan-cancel-status').hide();
    $('.dokan-edit-status').hide(); // Ẩn liên kết chỉnh sửa
  }
});
</script>
<?php
}



//Hạn chế thay đổi trạng thái đơn hàng của người bán
add_filter( 'dokan_can_update_order_status', 'restrict_dokan_order_status_update', 10, 4 );

function restrict_dokan_order_status_update( $can_update, $new_status, $order, $seller_id ) {
    $current_status = $order->get_status();

    if ( $current_status === 'wc-sd-delivering' && $new_status === 'wc-processing' ) {
        return false; // Không cho phép cập nhật từ "Đang giao hàng" sang "Đang xử lý"
    }
    return $can_update;
}


// Ẩn các tùy chọn trạng thái không được phép trong giao diện quản lý đơn hàng của người bán
function custom_dokan_hide_order_status_options1() {
    if ( current_user_can( 'seller' ) ) {
        ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
  var allowedStatuses = ['wc-processing', 'wc-sd-delivering', 'wc-completed']; // Các trạng thái được phép
  $('#order_status option').each(function() {
    if ($.inArray($(this).val(), allowedStatuses) === -1) {
      $(this).remove();
    }
  });
});
</script>
<?php
    }
}
add_action( 'wp_footer', 'custom_dokan_hide_order_status_options1', 100 );

// hide thong tin van calllet
function custom_hide_order_status_options_script() {
    if ( current_user_can( 'seller' ) ) {
        ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
  // Mảng các giá trị option 
  var statusesToHide = ['ss_delivered', 'ss_cancelled'];

  // Lặp qua từng option và ẩn những option có giá trị trong mảng statusesToHide
  $('#update_shipping_status_5 option').each(function() {
    if (statusesToHide.indexOf($(this).val()) !== -1) {
      $(this).hide();
    }
  });

  // Sau khi ẩn, cần cập nhật lại giá trị được chọn nếu giá trị bị ẩn
  var selectedValue = $('#update_shipping_status_5').val();
  if (statusesToHide.indexOf(selectedValue) !== -1) {
    $('#update_shipping_status_5').val('');
  }
});
</script>
<?php
    }
}
add_action( 'wp_footer', 'custom_hide_order_status_options_script' );

// hide tiếp tục
add_action( 'wp_footer', 'custom_hide_shipping_options' );
function custom_hide_shipping_options() {
    ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
  // Ẩn các tùy chọn "Delivered" và "Cancelled"
  $('#update_shipping_status_7 option[value="ss_delivered"]').hide();
  $('#update_shipping_status_7 option[value="ss_cancelled"]').hide();
});
</script>
<?php
}

// hide tiep tuc
add_filter( 'woocommerce_shipping_statuses', 'custom_hide_shipping_statuses' );

function custom_hide_shipping_statuses( $statuses ) {
    // Loại bỏ các trạng thái không mong muốn
    unset( $statuses['ss_delivered'] );
    unset( $statuses['ss_cancelled'] );

    return $statuses;
}

// ẩn trong admin
add_action( 'admin_head', 'hide_custom_shipping_statuses' );

function hide_custom_shipping_statuses() {
    global $pagenow;
    // Chỉ ẩn khi đang ở trang cấu hình trạng thái vận chuyển của Dokan
    if ( $pagenow === 'admin.php' && isset( $_GET['page'] ) && $_GET['page'] === 'dokan' ) {
        echo '<style>.shipping_status_list .repeatable-item-description { display: none; }</style>';
    }
}

// hide good admin shiping delivêri
add_action('admin_head', 'custom_hide_shipping_statuses_css');

function custom_hide_shipping_statuses_css() {
    ?>
<style>
.shipping_status_list .dokan-settings-repeatable-list li:nth-child(1),
.shipping_status_list .dokan-settings-repeatable-list li:nth-child(2) {
  display: none;
}
</style>
<?php
}

// hide continue
add_action('admin_footer', 'hide_dokan_shipping_status_items');
function hide_dokan_shipping_status_items() {
    ?>
<script>
jQuery(document).ready(function($) {
  // Tìm các phần tử li chứa nội dung cần ẩn và ẩn chúng
  $('ul.dokan-settings-repeatable-list li:contains("Delivered"), ul.dokan-settings-repeatable-list li:contains("Cancelled")')
    .each(function() {
      if ($(this).find('.repeatable-item-description:contains("(This is must use item)")').length > 0) {
        $(this).hide();
      }
    });
});
</script>
<?php
}

// hide dash board seller
add_action('wp_footer', 'custom_hide_tools_menu_item');

function custom_hide_tools_menu_item() {
    // Kiểm tra xem người dùng có đang ở trang bảng điều khiển Dokan hay không
    if (is_page('dashboard')) {
        ?>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
  var toolsMenuItem = document.querySelector('.dokan-dashboard-menu .tools');
  if (toolsMenuItem) {
    toolsMenuItem.style.display = 'none';
  }
});
</script>
<?php
    }
}

// dili vs caller
function hide_specific_order_statuses($statuses) {
    // Các trạng thái muốn ẩn
    $hidden_statuses = array('ss_delivered', 'ss_cancelled');

    foreach ($hidden_statuses as $status) {
        if (($key = array_search($status, $statuses)) !== false) {
            unset($statuses[$key]);
        }
    }

    return $statuses;
}
add_filter('dokan_order_statuses', 'hide_specific_order_statuses', 10, 1);

//
function hide_specific_select_options() {
    ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
  // Ẩn các lựa chọn "Delivered" và "Cancelled"
  $('select[name^="update_shipping_status_"] option[value="ss_delivered"]').remove();
  $('select[name^="update_shipping_status_"] option[value="ss_cancelled"]').remove();
});
</script>
<?php
}
add_action('wp_footer', 'hide_specific_select_options');

// mặc định
function vpw_breadcrumbs() {
    /* === OPTIONS === */
    $text['home']     = 'Trang chủ'; // text for the 'Home' link
    $text['category'] = '%s'; // text for a category page
    $text['search']   = 'Kết quả tìm kiếm %s'; // text for a search results page
    $text['tag']      = 'Từ khóa %s'; // text for a tag page
    $text['author']   = 'Tất cả bài viết của %s'; // text for an author page
    $text['404']      = 'Lỗi 404'; // text for the 404 page
    $text['page']     = 'Trang %s'; // text 'Page N'
    $text['cpage']    = 'Trang bình luận %s'; // text 'Comment Page N'
    $wrap_before    = '
 
 
<div class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">'; // the opening wrapper tag
    $wrap_after     = '</div>
 
 
 
<!-- .breadcrumbs -->'; // the closing wrapper tag
    $sep            = '›'; // separator between crumbs
    $sep_before     = '<span class="sep">'; // tag before separator
    $sep_after      = '</span>'; // tag after separator
    $show_home_link = 1; // 1 - show the 'Home' link, 0 - don't show
    $show_on_home   = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $show_current   = 1; // 1 - show current page title, 0 - don't show
    $before         = '<span class="current vpw_breadcrumbs">'; // tag before the current crumb
    $after          = '</span>'; // tag after the current crumb
    /* === END OF OPTIONS === */
    global $post;
    $home_url       = home_url('/');
    $link_before    = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
    $link_after     = '</span>';
    $link_attr      = ' itemprop="item"';
    $link_in_before = '<span itemprop="name" class="vpw_breadcrumbs">';
    $link_in_after  = '</span>';
    $link           = $link_before . '<a href="%1$s"' . $link_attr . '>' . $link_in_before . '%2$s' . $link_in_after . '</a>' . $link_after;
    $frontpage_id   = get_option('page_on_front');
    $parent_id      = ($post) ? $post->post_parent : '';
    $sep            = ' ' . $sep_before . $sep . $sep_after . ' ';
    $home_link      = $link_before . '<a href="' . $home_url . '"' . $link_attr . ' class="home">' . $link_in_before . $text['home'] . $link_in_after . '</a>' . $link_after;
    if (is_home() || is_front_page()) {
        if ($show_on_home) echo $wrap_before . $home_link . $wrap_after;
    } else {
        echo $wrap_before;
        if ($show_home_link) echo $home_link;
        if ( is_category() ) {
            $cat = get_category(get_query_var('cat'), false);
            if ($cat->parent != 0) {
                $cats = get_category_parents($cat->parent, TRUE, $sep);
                $cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
                $cats = preg_replace('#<a([^>]+)>([^<]+)</a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
                if ($show_home_link) echo $sep;
                echo $cats;
            }
            if ( get_query_var('paged') ) {
                $cat = $cat->cat_ID;
                echo $sep . sprintf($link, get_category_link($cat), get_cat_name($cat)) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
            } else {
                if ($show_current) echo $sep . $before . sprintf($text['category'], single_cat_title('', false)) . $after;
            }
        } elseif ( is_search() ) {
            if (have_posts()) {
                if ($show_home_link && $show_current) echo $sep;
                if ($show_current) echo $before . sprintf($text['search'], get_search_query()) . $after;
            } else {
                if ($show_home_link) echo $sep;
                echo $before . sprintf($text['search'], get_search_query()) . $after;
            }
        } elseif ( is_day() ) {
            if ($show_home_link) echo $sep;
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $sep;
            echo sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F'));
            if ($show_current) echo $sep . $before . get_the_time('d') . $after;
        } elseif ( is_month() ) {
            if ($show_home_link) echo $sep;
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y'));
            if ($show_current) echo $sep . $before . get_the_time('F') . $after;
        } elseif ( is_year() ) {
            if ($show_home_link && $show_current) echo $sep;
            if ($show_current) echo $before . get_the_time('Y') . $after;
        } elseif ( is_single() && !is_attachment() ) {
            if ($show_home_link) echo $sep;
            if ( get_post_type() != 'post' ) {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                printf($link, $home_url . $slug['slug'] . '/', $post_type->labels->singular_name);
                if ($show_current) echo $sep . $before . get_the_title() . $after;
            } else {
                $cat = get_the_category(); $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, $sep);
                if (!$show_current || get_query_var('cpage')) $cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
                $cats = preg_replace('#<a([^>]+)>([^<]+)</a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
                echo $cats;
                if ( get_query_var('cpage') ) {
                    echo $sep . sprintf($link, get_permalink(), get_the_title()) . $sep . $before . sprintf($text['cpage'], get_query_var('cpage')) . $after;
                } else {
                    if ($show_current) echo $before . get_the_title() . $after;
                }
            }
        // custom post type
        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
            $post_type = get_post_type_object(get_post_type());
            if ( get_query_var('paged') ) {
                echo $sep . sprintf($link, get_post_type_archive_link($post_type->name), $post_type->label) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
            } else {
                if ($show_current) echo $sep . $before . $post_type->label . $after;
            }
        } elseif ( is_attachment() ) {
            if ($show_home_link) echo $sep;
            $parent = get_post($parent_id);
            $cat = get_the_category($parent->ID); $cat = $cat[0];
            if ($cat) {
                $cats = get_category_parents($cat, TRUE, $sep);
                $cats = preg_replace('#<a([^>]+)>([^<]+)</a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
                echo $cats;
            }
            printf($link, get_permalink($parent), $parent->post_title);
            if ($show_current) echo $sep . $before . get_the_title() . $after;
        } elseif ( is_page() && !$parent_id ) {
            if ($show_current) echo $sep . $before . get_the_title() . $after;
        } elseif ( is_page() && $parent_id ) {
            if ($show_home_link) echo $sep;
            if ($parent_id != $frontpage_id) {
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    if ($parent_id != $frontpage_id) {
                        $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                    }
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) { echo $breadcrumbs[$i]; if ($i != count($breadcrumbs)-1) echo $sep; } } if ($show_current) echo $sep . $before . get_the_title() . $after; } elseif ( is_tag() ) { if ( get_query_var('paged') ) { $tag_id = get_queried_object_id(); $tag = get_tag($tag_id); echo $sep . sprintf($link, get_tag_link($tag_id), $tag->name) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
            } else {
                if ($show_current) echo $sep . $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
            }
        } elseif ( is_author() ) {
            global $author;
            $author = get_userdata($author);
            if ( get_query_var('paged') ) {
                if ($show_home_link) echo $sep;
                echo sprintf($link, get_author_posts_url($author->ID), $author->display_name) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
            } else {
                if ($show_home_link && $show_current) echo $sep;
                if ($show_current) echo $before . sprintf($text['author'], $author->display_name) . $after;
            }
        } elseif ( is_404() ) {
            if ($show_home_link && $show_current) echo $sep;
            if ($show_current) echo $before . $text['404'] . $after;
        } elseif ( has_post_format() && !is_singular() ) {
            if ($show_home_link) echo $sep;
            echo get_post_format_string( get_post_format() );
        }
        echo $wrap_after;
    }
} // end of truongmanh_net_breadcrumbs()

function renhat() {
if(get_field('gia_re_nhat')=="co"){
echo '<div class="customized-overlay-image"><img src="'.get_stylesheet_directory_uri().'/images/renhat.png"></div>';
}elseif(get_field('freeship')=="co"){
echo '<div class="customized-overlay-image"><img src="'.get_stylesheet_directory_uri().'/images/freeship.png"></div>';
}
}
add_action( 'flatsome_woocommerce_shop_loop_images', 'renhat' );




function hoantien1() {
if(get_field('gia_re_nhat')=="co"){
    echo '<div class="_2MH7dC">Ở đâu rẻ hơn, Shopee hoàn tiền</div>';
}

}
add_action( 'hoantien', 'hoantien1' );

function hoantien2() {
if(get_field('hoan_tien')=="co"){
    echo '<div style="margin-bottom: 30px; border-top: 1px solid rgba(0, 0, 0, 0.05);"><a class="_13C8_x flex items-center" href="jvascript:;"><img src="'.get_stylesheet_directory_uri().'/images/hoantien.png" class="_110HpJ"><span class="XNBuk1">Shopee Đảm Bảo</span><span>3 Ngày Trả Hàng / Hoàn Tiền</span></a></div>';
}
}
add_action( 'woocommerce_after_add_to_cart_form', 'hoantien2' );

function headert() {

if(is_single()){
echo '
    <section class="bread-crumb">
    <span class="crumb-border"></span>
    <div class="row align-center">
            <div class="large-10 col">';
                vpw_breadcrumbs();
            echo'</div>
    </div>
</section> 
';
 } 
 else{

echo '    <section class="bread-crumb">
    <span class="crumb-border"></span>
    <div class="row align-center">
            <div class="large-10 col">';
                vpw_breadcrumbs();
            echo'</div>
    </div>
</section> 
';



}

}
add_action( 'flatsome_before_blog', 'headert' );

function yeuthich() {
    global $product;

    if ( $product->is_featured() ) {
        echo '<div class="MW4BW_"><div class="_150RS_ bgXBUk" style="color: rgb(242, 82, 32);"><span class="lVCR4M">Yêu thích</span></div></div>';
    }
}
add_action( 'flatsome_woocommerce_shop_loop_images', 'yeuthich' );

function mh_load_theme_style() {
	if ( !is_user_logged_in() ) {
	wp_dequeue_script('wc-password-strength-meter');
    wp_deregister_script('wc-password-strength-meter');
	}
	/* Add Font Awesome */
	wp_deregister_script('font-awesome');
	wp_deregister_style('font-awesome');
	wp_register_style( 'font-awesome', get_stylesheet_directory_uri() . '/font-awesome/css/font-awesome.min.css', false, false );
	wp_enqueue_style( 'font-awesome' );
    	wp_register_script( 'custom-js', get_stylesheet_directory_uri() . '/custom.js', false, false );
	wp_enqueue_script( 'custom-js' );
	
}
add_action( 'wp_enqueue_scripts', 'mh_load_theme_style', 99998 );
function disable_plugin_updates( $value ) {
  if ( isset($value) && is_object($value) ) {
    if ( isset( $value->response['advanced-custom-fields-pro/acf.php'] ) ) {
      unset( $value->response['advanced-custom-fields-pro/acf.php'] );
    }
    if ( isset( $value->response['woocommerce/woocommerce.php'] ) ) {
      unset( $value->response['woocommerce/woocommerce.php'] );
    }
	 if ( isset( $value->response['yith-woocommerce-ajax-product-filter-premium/init.php'] ) ) {
      unset( $value->response['yith-woocommerce-ajax-product-filter-premium/init.php'] );
    }  
  }
  return $value;
}
add_filter( 'site_transient_update_plugins', 'disable_plugin_updates' );
function wporg_remove_all_dashboard_metaboxes() {
    // Remove Welcome panel
   // remove_action( 'welcome_panel', 'wp_welcome_panel' );
    // Remove the rest of the dashboard widgets
  //  remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
 //  remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
   // remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
   // remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
}
add_action( 'wp_dashboard_setup', 'wporg_remove_all_dashboard_metaboxes' );
add_action('admin_enqueue_scripts', 'ds_admin_theme_style');
add_action('login_enqueue_scripts', 'ds_admin_theme_style');
function ds_admin_theme_style() {
        echo '<style> .error, .is-dismissible, .yith-license-notice { display: none; }</style>';
    
}
function add_this_script_footer()
{ 

	 if( have_rows('tin_khuyen_mai','option') ){
?>
<style>
.mp-notification-list-group-item-active,
.mp-notification-element-button-wrapper {
  background-color: <?php echo get_field('mau_chu_dao', 'option');
  ?>;
}
</style>
<div id="mp-notification-5zL12bB1Jr" class="mp-notification-element-wrapper">
  <div class="mp-notification-element-button-wrapper">
    <div class="mp-notification-element mp-floating-cart-active">
      <a href="javascript:void(0)" id="mp-notification-button-open">
        <img src="/wp-content/themes/flatsome-child/bell-icon.png" class="mp-notification-element-button-img">
      </a>
      <span
        class="mp-notification-element-button-count"><?php echo count(get_field('tin_khuyen_mai','option')); ?></span>
    </div>
  </div>
  <div class="mp-notification-element-content">
    <a href="javascript:void(0)" id="mp-notification-button-close" class="mp-btn-close">
      <div class="mp-notification-item-close"></div>
    </a>
    <ul class="mp-notification-list-group">
      <li class="mp-notification-list-group-item-active"><?php echo get_field('tieu_de_chinh','option'); ?></li>
      <?php  while( have_rows('tin_khuyen_mai','option') ): the_row(); 
     $tieu_de_tin = get_sub_field('tieu_de_tin');
	 $tieu_de_phu = get_sub_field('tieu_de_phu');											
	 $hinh_anh_tin = get_sub_field('hinh_anh');
	 $url_tin_khuyen_mai = get_sub_field('url_tin_khuyen_mai');
     $dem_tin = 1;
       
?>

      <li class="mp-notification-list-group-item item<?php echo $dem_tin; ?>">
        <div class="mp-notification-item-media">
          <img class="mp-notification-item-media-thumb lazy-load"
            src="data:image/svg+xml,%3Csvg%20viewBox%3D%220%200%20247%20296%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3C%2Fsvg%3E"
            data-src="<?php echo $hinh_anh_tin;  ?>" width="78" alt="<?php echo esc_attr($tieu_de_tin); ?>">
          <div class="mp-notification-item-media-body">
            <p class="mp-notification-item-title"><a title="<?php echo esc_attr($tieu_de_tin); ?>"
                href="<?php echo $url_tin_khuyen_mai; ?>"><?php echo esc_html($tieu_de_tin); ?></a></p>
            <p class="mp-notification-item-description"><a title="<?php echo esc_attr($tieu_de_tin); ?>"
                href="<?php echo $url_tin_khuyen_mai; ?>"><?php echo esc_html($tieu_de_phu); ?></a></p>
          </div>
        </div>
      </li>

      <?php 
						$dem_tin++;		endwhile; ?>

    </ul>
  </div>
</div>
<?php } 
} 

add_action('wp_footer', 'add_this_script_footer'); 

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Tùy chỉnh khác',
		'menu_title'	=> 'Tùy chỉnh khác',
		'menu_slug' 	=> 'Tùy chỉnh khác',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
}
?>

<?
add_action( 'woocommerce_single_product_summary', 'baohanh', 6);
  
function baohanh() { ?>

<?php if(get_field('bao_hanh')) { ?>
<div class="cg-author">Bảo Hành :<p class="congminh"><?php the_field('bao_hanh'); ?></p>
</div>

<?php
								}
}