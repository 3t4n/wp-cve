(function ($) {
	'use strict';

	function cancelRequestForm() {
		var parent = this;
		this.init = function () {
			this.clickDetect();
		}

		this.clickDetect = function () {
			jQuery(document).on('click', '.pi_cancel_request_form', parent.getCancelRequestForm);
		}

		this.getCancelRequestForm = function (event) {
			event.preventDefault();
			var caller_button = jQuery(this);
			var url = caller_button.attr('href');
			$.magnificPopup.open({
				items: {
					src: url,
					type: "ajax",
					showCloseBtn: true,
					closeOnContentClick: false,
					closeOnBgClick: false,
					mainClass: 'mfp-fade',
					removalDelay: 300
				}
			});
		}



	}

	var cancelRequestFormObj = new cancelRequestForm();
	cancelRequestFormObj.init();

	function reorderFunction() {
		var parent = this;
		this.init = function () {
			this.clickDetect();
			this.replaceCart();
			this.mergeCart();
		}

		this.clickDetect = function () {
			jQuery(document).on('click', '.pi_reorder', parent.reorderEvent);
		}

		this.replaceCart = function () {
			jQuery(document).on('click', '.pi-replace-cart', parent.reorderEvent);
		}

		this.mergeCart = function () {
			jQuery(document).on('click', '.pi-merge-cart', parent.reorderEvent);
		}

		this.reorderEvent = function (e) {
			e.preventDefault();
			var url = jQuery(this).attr('href');
			if (url) {
				var res = parent.sendReorderRequest(url);
				res.done(function (data) {
					console.log(data);
					parent.responseHandler(data);
				});
			} else {
				alert('No url provided')
			}
		}

		this.sendReorderRequest = function (url) {
			this.blockUI();
			return jQuery.ajax({
				url: url,
				method: 'GET',
				dataType: 'json',
				success: function () {
					parent.unblockUI();
				}
			});
		}

		this.responseHandler = function (res) {
			console.log(res);
			switch (res.action) {

				case 'options':
					parent.options(res.html, pi_corw_settings.success_toast_bg);
					break;
				case 'error':
					parent.toast(res.heading, res.message, res.icon, pi_corw_settings.error_toast_bg);
					break;
				case 'success':
					parent.toast(res.heading, res.message, res.icon, pi_corw_settings.success_toast_bg);
					break;
			}
		}

		this.toast = function (heading, message, icon, color = '#f00') {
			jQuery.toast().reset('all');
			jQuery.toast({
				heading: heading,
				text: message,
				hideAfter: false,
				bgColor: color,
				position: 'mid-center',
				icon: icon
			})
		}

		this.options = function (html, color = '#28a745') {
			jQuery.toast().reset('all');
			jQuery.toast({
				text: html,
				hideAfter: false,
				bgColor: color,
				position: 'mid-center',
			})
		}

		this.blockUI = function () {
			if (typeof jQuery.fn.block == 'undefined') return;
			jQuery("body").block({
				message: null,
				overlayCSS: {
					background: "#fff",
					opacity: .6
				}
			});
		}

		this.unblockUI = function () {
			if (typeof jQuery.fn.block == 'undefined') return;
			jQuery("body").unblock();
		}


	}
	var reorderFunctionObj = new reorderFunction();
	reorderFunctionObj.init();

})(jQuery);
