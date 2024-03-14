
if (typeof woodmartThemeModule !== "undefined"){
	woodmart_settings.ajax_add_to_cart = false;
}
jQuery(document).ready(function ($) {
	'use strict';
	//Woodmart theme v:5.3.6
	if (typeof woodmartThemeModule !== "undefined"){
		woodmart_settings.ajax_add_to_cart = false;
	}
	var woo_product_builder = {
		init: function () {
			this.sort_by();
			this.review_popup();
			this.review_total_price();
			this.events();
			this.mobileControlBar();
		},
		sort_by: function () {
			jQuery('.woopb-sort-by-button').on('change', function () {
				var href = jQuery(this).val();
				window.location.href = href
			})
		},
		review_popup: function () {
			jQuery('#vi_wpb_sendtofriend').on('click', function () {
				woo_product_builder.review_popup_show();
			});
			jQuery('#vi_wpb_popup_email .vi-wpb_overlay, #vi_wpb_popup_email .woopb-close').on('click', function () {
				woo_product_builder.review_popup_hide();
			});
		},
		review_popup_show: function () {
			jQuery('html').css({'overflow': 'hidden'});
			jQuery('#vi_wpb_popup_email').fadeIn(500);
		},
		review_popup_hide: function () {
			jQuery('#vi_wpb_popup_email').fadeOut(300);
			jQuery('html').css({'overflow': 'inherit'});
		},
		review_total_price: function () {
			jQuery('.woopb-qty-input').on('change', function () {
				var quantity = parseInt(jQuery(this).val());
				var price = parseFloat(jQuery(this).closest('td').attr('data-price'));
				var total_html = jQuery(this).closest('tr').find('.woopb-total .woocommerce-Price-amount').contents();

				if (price > 0) {
					var total = quantity * price;
					total_html.filter(function (index) {
						return this.nodeType == 3;
					}).each(function () {
						this.textContent = total;
					})
				} else {
					return;
				}
			})
		},
		events: function () {
			jQuery('.woopb-share-link').on('click', function () {
				jQuery(this).select();
				document.execCommand("copy");
			})
		},

		mobileControlBar() {
			let overlay = jQuery('.woopb-overlay'),
				steps = jQuery('.vi-wpb-wrapper .woopb-steps'),
				sidebar = jQuery('.woocommerce-product-builder-sidebar'),
				viewStepsBtn = jQuery('.woopb-steps-detail-btn'),
				viewFilterBtn = jQuery('.woopb-mobile-filters-control'),
				close = jQuery('.woopb-close-modal');

			viewStepsBtn.on('click', function () {
				steps.toggle('slow');
				sidebar.hide();
			});

			viewStepsBtn.on('mouseup', function () {
				steps.css('display') === 'none' ? overlay.show('slow') : overlay.hide();
				steps.css('display') === 'none' ? close.show() : close.hide();
			});

			viewFilterBtn.on('click', function () {
				sidebar.toggle('slow');
				steps.hide();
			});

			viewFilterBtn.on('mouseup', function () {
				sidebar.css('display') === 'none' ? overlay.show('slow') : overlay.hide();
				sidebar.css('display') === 'none' ? close.show() : close.hide();
			});

			function hideAll() {
				sidebar.hide('slow');
				steps.hide('show');
				overlay.hide();
				close.hide();
			}

			overlay.on('click', function () {
				hideAll();
			});

			close.on('click', function () {
				hideAll();
			});
		},

	};

	woo_product_builder.init();

});
