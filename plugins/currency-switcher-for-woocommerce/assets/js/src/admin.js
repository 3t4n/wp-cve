/**
 * Currency Switcher for WooCommerce
 * #
 *
 * Copyright (c) 2018 TruongSa
 * Licensed under the GPL-2.0+ license.
 */
"use strict";

jQuery(document).ready(function($) {
	if ( typeof PMCS_Admin_Args.max_currencies == "undefined" ) {
		PMCS_Admin_Args.max_currencies = 2;
	} else {
		PMCS_Admin_Args.max_currencies = parseInt( PMCS_Admin_Args.max_currencies ) || 2;
	} 

	$(".pmcs-form-currencies .form-table")
		.removeClass("form-table")
		.addClass("pmcs-form-table");

	var reIndexCurrencies = function() {
		var inputName =
			$(".pmcs-currencies-list .the-list").attr("data-name") || "";
		$(".pmcs-currencies-list .tbody .tr").each(function(index) {
			var tr = $(this);

			var isDefault = false;
			if (
				$(".pmcs_default_currency", tr)
					.eq(0)
					.is(":checked")
			) {
				isDefault = true;
			}

			$(".riname", tr).each(function(j) {
				var dataName = $(this).attr("data-name") || "";
				if (dataName) {
					var n = dataName.replace(/__name__/g, inputName);
					n = n.replace(/__i__/g, index);
					$(this).attr("name", n);
				}

				var id = $(this).attr("id") || false;
				if (!id) {
					id = "_" + index + j + new Date().getTime();
					$(this).attr("id", id);
				}
			});

			if (isDefault) {
				$(".pmcs-currencies-list .tbody .tr").removeClass(
					"pmcs_default_row"
				);
				$(".pmcs-currencies-list .riname.pmcs-rate").removeAttr(
					"disabled"
				);
				tr.addClass("pmcs_default_row");
				$(".riname.pmcs-rate", tr)
					.attr("disabled", "disabled")
					.val(1);
			}
		});
	};

	reIndexCurrencies();

	function maybeShowUpsellMessage(){
		if ( isLimitedCurrencies() ){
			$( '.pmcs-limit-currency-msg').show();
		} else {
			$( '.pmcs-limit-currency-msg').hide();
		}
	}

	var isLimitedCurrencies = function(){
		var l = $( '.pmcs-table-lm .the-list tr' ).length;
		var max = PMCS_Admin_Args.max_currencies;
		return l >= max;
	};

	var checkLimitedCurrencies = function(){
		var c = 0;
		$( '.pmcs-currencies-list .the-list tr:not(.pmcs_default_row)' ).each( function( index ){
			c++;
			if ( index >= PMCS_Admin_Args.max_currencies - 1 ) {
				$( this ).remove();
			}
		} );

		if( c >= PMCS_Admin_Args.max_currencies - 1 ) { // include default currency.
			maybeShowUpsellMessage();
		}

		$( '.pmcs-table-lm' ).show();
	};

	var checkLimitedGeoIP = function(){
		var c = 0;
		$( '.pmcs-geoip-list .the-list tr:not(.default-currency)' ).each( function( index ){
			c++;
			if ( index >= PMCS_Admin_Args.max_currencies - 1 ) {
				$( this ).remove();
			}
		} );

		if( c >= PMCS_Admin_Args.max_currencies - 1 ) { // include default currency.
			maybeShowUpsellMessage();
		}

		$( 'pmcs-table-lm' ).show();
	};

	checkLimitedCurrencies();
	checkLimitedGeoIP();

	$(document).on("change", ".pmcs_default_currency", function() {
		reIndexCurrencies();
	});

	$(".pmcs-currencies-list .tbody").sortable({
		handle: ".handle",
		containment: "parent",
		update: function(event, ui) {
			reIndexCurrencies();
		}
	});

	

	// Add new
	$(".pmcs-add-currency-list").on("click", function(e) {
		e.preventDefault();
		if( ! isLimitedCurrencies() ) {
			var p = $(this).parent();
			var list = p.find(".the-list");
			var tr = $("#wc_pmcs_currency_row").html();
			tr = $(tr);
			var select2 = tr.find(".td_currency_code");
			select2
				.find("select")
				.removeClass("select2-hidden-accessible enhanced");
			select2.find(".select2").remove();
			list.append(tr);
			reIndexCurrencies();
			$(document.body).trigger("wc-enhanced-select-init");

			maybeShowUpsellMessage();
		} else {
			maybeShowUpsellMessage();
		}
	});

	// Remove
	$(document).on("click", ".the-list .remove", function(e) {
		e.preventDefault();
		var table = $(this).closest(".the-list");
		var tr = $(this).closest(".tr");
		var isDefault = false;
		if (
			$(".pmcs_default_currency", tr)
				.eq(0)
				.is(":checked")
		) {
			isDefault = true;
		}

		if (!isDefault) {
			tr.remove();
			reIndexCurrencies();
		}

		maybeShowUpsellMessage();

	});

	// When currency select change
	$(document).on("change", ".pmcs-currency-select", function() {
		var v = $(this).val();
		var p = $(this).closest(".tr");
		var displayName = "";
		if (typeof PMCS_List_Currency[v] !== "undefined") {
			displayName = PMCS_List_Currency[v].label;
		}

		$("input.pmcs_default_currency", p).val(v);

		var flagImg = p.find(".currency_flag img");
		var folderUrl = flagImg.attr("data-url") || "";
		var imageUrl = folderUrl + v.toLowerCase() + ".png";
		flagImg.attr("src", imageUrl);

		$("input.pmcs-currency-display", p).val(displayName);
	});

	// For sync all rates
	$(".pmcs-currency-sync-all").on("click", function(e) {
		e.preventDefault();
		var button = $(this);
		button.addClass("updating-message");
		var form_currency = $('input[name="pmcs_default_currency"]:checked').val();
		var to_currency = [];

		$(".pmcs-currency-select").each(function() {
			var _v = $(this).val();
			if (_v) {
				to_currency.push(_v);
			}
		});

		$.ajax({
			url: ajaxurl,
			type: "get",
			data: {
				action: "load_exchange_rates",
				form_currency: form_currency,
				to_currency: to_currency.join(","),
				nonce: PMCS_Admin_Args.nonce
			},
			success: function(res) {
				button.removeClass("updating-message");
				if (typeof res === "object" && typeof res.rates === "object") {
					$(".tr select.pmcs-currency-select").each(function() {
						var v = $(this).val();
						var p = $(this).closest(".tr");

						var rate = Number(res.rates[v]);
						var num_decimals = $("input.num_decimals", p).val();
						if (num_decimals.length === 0) {
							num_decimals = 2;
						}
						num_decimals = Number(num_decimals);
						if (isNaN(num_decimals)) {
							num_decimals = 2;
						}

						if( rate ) {
							$(".pmcs-rate", p).val( rate );
						}
						
					});
				}
			}
		});
	});

	// For sync rate each currency
	$(".tr .pmcs-sync-rate").on("click", function(e) {
		e.preventDefault();
		var button = $(this);
		var p = button.closest(".tr");
		var current_currency = $("select.pmcs-currency-select", p).val();
		button.addClass("loading");
		var form_currency = $('input[name="pmcs_default_currency"]:checked').val();
		var to_currency = [];

		$(".pmcs-currency-select").each(function() {
			var _v = $(this).val();
			if (_v) {
				to_currency.push(_v);
			}
		});

		if (current_currency) {
			$.ajax({
				url: ajaxurl,
				type: "get",
				data: {
					action: "load_exchange_rates",
					form_currency: form_currency,
					to_currency: to_currency.join(","),
					nonce: PMCS_Admin_Args.nonce
				},
				success: function(res) {
					button.removeClass("loading");
					if (
						typeof res === "object" &&
						typeof res.rates === "object"
					) {
						var rate = Number(res.rates[current_currency]);
						var num_decimals = $("input.num_decimals", p).val();
						if (num_decimals.length === 0) {
							num_decimals = 2;
						}
						num_decimals = Number(num_decimals);
						if (isNaN(num_decimals)) {
							num_decimals = 2;
						}

						if ( rate ) {
							$(".pmcs-rate", p).val( rate );
						}

					}
				}
			});
		}
	});

	// For Exchange Rate API Server
	$("select.pmsc_exchange_rate_server").on("change init_change", function() {
		var v = $(this).val();
		$("tr .pmsc_exc_server").each(function() {
			var p = $(this).closest("tr");
			if ($(this).hasClass(v)) {
				p.show();
			} else {
				p.hide();
			}
		});
	});

	$("select.pmsc_exchange_rate_server").trigger("init_change");

	$(document).on("click", ".pmcs-show-price-variable", function(e) {
		e.preventDefault();
		var p = $(this).closest(".variable_pricing");
		$(".pmcs-price-variable-wrapper", p).toggle();
	});

	// woocommerce_admin_meta_boxes
	/**
	 * Order Metabox
	 */
	if ("shop_order" === window.pagenow) {
		var wc_ajax_meta_box_url = window.woocommerce_admin_meta_boxes.ajax_url;
		var sign = "?";
		if (wc_ajax_meta_box_url.indexOf("?") > 0) {
			sign = "&";
		}
		var current_c = $("#pmcs_order_currency").val();
		window.woocommerce_admin_meta_boxes.ajax_url +=
			"?currency=" + current_c;

		console.log(window.woocommerce_admin_meta_boxes.ajax_url);

		$("#pmcs_order_currency").on("change", function() {
			var code = $(this).val();
			window.woocommerce_admin_meta_boxes.ajax_url =
				wc_ajax_meta_box_url + sign + "currency=" + code;
		});
	}
});
