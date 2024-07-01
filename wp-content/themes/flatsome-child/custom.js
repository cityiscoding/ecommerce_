jQuery(document).ready(function () {
	jQuery('#mp-notification-button-close').on("click", function (e) {
		jQuery("#mp-notification-5zL12bB1Jr").removeClass("mp-floating-cart-active");
	});
	jQuery("#mp-notification-button-open").on("click", function (e) {

		if (jQuery("#mp-notification-5zL12bB1Jr").hasClass("mp-floating-cart-active")) {
			alert('dddd');

			jQuery("#mp-notification-5zL12bB1Jr").removeClass("mp-floating-cart-active");

		}
		else {
			jQuery("#mp-notification-5zL12bB1Jr").addClass("mp-floating-cart-active");


		}
	});


});

jQuery(document).ready(function ($) {
	// Di chuyển trường "Nhập lại mật khẩu" đến ngay sau trường "Mật khẩu"
	$('#reg_password2').closest('.form-row').insertAfter($('#reg_password').closest('.form-row'));
});

