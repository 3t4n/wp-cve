(function ($, app) {
	"use strict";
	$.fn.dataTable.ext.errMode = 'none';
	let needNotify = true;

	function WtbpFrontendPage() {
		this.$obj = this;
		return this.$obj;
	}

	WtbpFrontendPage.prototype.init = (function () {
		var _thisObj = this.$obj;
		//$('<meta>', {name: 'viewport', content: 'user-scalable=no'}).appendTo('head');
		_thisObj.initializeTable();
		_thisObj.eventsFrontend();
	});

	WtbpFrontendPage.prototype.initializeTable = (function () {
		var _thisObj = this.$obj;

		$('.wtbpTableWrapper').on( 'change', '.quantity .qty', function() {
			var qtyInput = $( this );
			//setTimeout(function() {
				var wrapper = qtyInput.closest('.wtbpAddToCartWrapper'),
					row = qtyInput.closest('tr'),
					addButton = wrapper.find('.add_to_cart_button'),
					qtyInputVal = qtyInput.prop('value');
					
				if(row.hasClass('child')){
					row = row.prev();
					var wrapperMain = row.find('td.add_to_cart');
					wrapperMain.find('.add_to_cart_button ').attr( 'data-quantity', qtyInputVal);
					wrapperMain.find('.qty').val(qtyInputVal);
					wrapperMain.find('.wtbpAddMulty').attr( 'data-quantity', qtyInputVal);
				}
				wrapper.find('.wtbpAddMulty').attr( 'data-quantity', qtyInputVal);
				if (addButton.length) {
					var addButtonUrl = addButton.attr('href').split('&quantity')[0];
					addButton.attr( 'data-quantity', qtyInputVal);
					addButton.data( 'quantity', qtyInputVal);
					addButton.attr( 'href', addButtonUrl + '&quantity=' + qtyInputVal);
				}
			//}, 100);
		});

		$('.wtbpTableWrapper').each(function( ) {
			var tableWrapper = $(this);
			app.initializeTable(tableWrapper, function(){
				lightbox.option({
					'resizeDuration': 200,
					'wrapAround': true
				});

				setTimeout(function() {
					tableWrapper.css({'visibility':'visible'});
					tableWrapper.find('.wtbpLoader').addClass('wtbpHidden');
					jQuery('body').find('.product_mpc').each(function(){
						var title = jQuery('.product_mpc').closest('form').find('.single_add_to_cart_button').html();
						jQuery(this).html(title);
					});
					if (!tableWrapper.is(':visible')) {
						var maxTime = 600000,
						startTime = Date.now();
						var interval = setInterval(function () {
							if (tableWrapper.is(':visible')) {
								app.getTableInstanceById(tableWrapper.data('table-id')).columns.adjust();
								//$(window).trigger('resize');
								clearInterval(interval);
							} else {
								if (Date.now() - startTime > maxTime) {
									clearInterval(interval);
								}
							}
						}, 200);
					}
				}, 200);
			});
		});

		jQuery('body').on('change keyup click', '.amount_needed', function(){
			var itemVal = jQuery(this).val();
			jQuery(this).closest('form.cart').find('#_measurement_needed').val(itemVal);
		})

		//Set Ajax function for MPC add_to_cart button
		jQuery('.wtbpTableWrapper').on('click', '.add_to_cart_button.product_mpc', function(e) {
			var settings = app.getSetting(false, jQuery(this).closest('.wtbpContentTable'));
			e.preventDefault();

			var form = jQuery(this).closest('form'),
				button = jQuery(this),
				value = jQuery(this).attr('data-product_id'),
				addtocart = '';

			//Add product_id input to form for ?wc-ajax=add_to_cart
			if (form.find('[name="product_id"]').length == 0) {
				form.append('<input type="hidden" class="product_id" name="product_id" value="'+value+'">');
			}
			
			if (form.find('[name="add-to-cart"]').length) {
				form.find('[name="add-to-cart"]').remove();
			}

			//Prepare URL and check MPC required field
			var url = '/?wc-ajax=add_to_cart',
				formSerialize = form.serialize(),
				amountInput = jQuery(this).closest('form').find('.amount_needed'),
				amountInputVal = amountInput.val();

			//If MPC required field not empty
			if (amountInputVal !== '') {
				button.attr('disabled',true).prop('disabled',true).addClass('loading');
				amountInput.attr('style', '');
				jQuery.ajax({
					url: url,
					type: "POST",
					data: formSerialize,
					success: function (response) {
						if (response) {
							button.attr('disabled',false).prop('disabled',false).removeClass('loading');
							if (response.error) {
								var translatedText = app.checkSettings(settings, 'product_not_added_to_cart', 'Product not added to cart');
								$.sNotify({
									'icon': 'fa fa-warning',
									'content': '<span> '+translatedText+'</span>',
									'delay' : 3500
								});
							} else {
								$(document.body).trigger('wc_fragment_refresh');
								$(document.body).trigger('wc_cart_button_updated', [button]);
								var translatedText = app.checkSettings(settings, 'product_added_to_cart', 'Product added to cart');

								$.sNotify({
									'icon': 'fa fa-check',
									'content': '<span> '+translatedText+'</span>',
									'delay': 1500
								});
							}
						}
					},
				});
			} else {
				amountInput.attr('style', 'border:1px solid red');
				return false;
			}
		});

		$('.wtbpTableWrapper').on( 'blur', '.wtbpProductNote', function() {
			var noteInput = $( this ),
				wrapper = noteInput.closest('td').find('.wtbpAddToCartWrapper'),
				row = noteInput.closest('tr'),
				addButton = wrapper.find('.add_to_cart_button'),
				addButtonUrl = addButton.length ? addButton.attr('href').split('&product_note')[0] : '',
				note = noteInput.val();

			if(row.hasClass('child')){
				row = row.prev();
				var wrapperMain = row.find('td.add_to_cart');
				wrapperMain.find('.add_to_cart_button ').attr( 'data-product_note', note);
				wrapperMain.find('.wtbpAddMulty').attr( 'data-product_note', note);
			}
			wrapper.find('.wtbpAddMulty').attr( 'data-product_note', note);
			addButton.attr( 'data-product_note', note);
			addButton.data( 'product_note', note);
			addButton.attr( 'href', addButtonUrl + '&product_note=' + note);
		});

		$('.wtbpTableWrapper').on('click', '.button.product_type_variation, .button.product_type_variable.add_to_cart_button', function(e) {
			e.preventDefault();
			var $this = jQuery(this),
				wrapper = $this.closest('.wtbpAddToCartWrapper'),
				hasPopup = wrapper.hasClass('wtbpHasPopupVariations'),
				isPopup = $this.closest('.wtbpModalContentForVariations').length;

			 if (wrapper.hasClass('wtbpDisabledLink') || (hasPopup && !isPopup)) {
				return false;
			}

			var	selectedProduct = [],
				productId = $this.attr('data-product_id'),
				productIdMain = wrapper.attr('data-product_id'),
				product = {id: productId, varId: $this.attr('data-variation_id'), quantity: $this.attr('data-quantity'), addData: {}},
				productNote = $this.closest('.wtbpModalContentForVariations').find('.wtbpProductNote'),
				variation = {};

			var addFieldList = $this.closest('tr').find('.wtbpAddDataToCartMeta');
			if (typeof app.getAddProductCartMetaPro === "function") {
				product['addData'] = app.getAddProductCartMetaPro(addFieldList);
			}
			if (productNote.length) {
				product['addData']['product_note'] = productNote.val();
			}

			$.each(this.attributes, function() {
				if(this.name.indexOf('data-attribute_') === 0) {
					variation[this.name.replace('data-', '')] = this.value;
				} else {
					if(this.name == 'data-product_note') product['addData']['product_note'] = this.value;
				}
			});
			product['variation'] = variation;
			selectedProduct.push(product);

			var data = {
				mod: 'wootablepress',
				action: 'multyProductAddToCart',
				selectedProduct: selectedProduct,
				pl: 'wtbp',
				reqType: "ajax"
			};

			jQuery.ajax({
				url: url,
				data: data,
				type: 'POST',
				success: function (res) {
					try {
						var result = JSON.parse(res);
						var message = result.messages;
					} catch(e){
						var message = 'Error!';
					}

					var settings = app.getSetting(false, $('table.wtbpContentTable'));
					var isAddToCartMessage = app.checkSettings(
						settings, 'show_add_to_cart_message', '1'
					);

					if (message.length && isAddToCartMessage) {
						$.sNotify({
							'icon': 'fa fa-check',
							'content': '<span>' + message + '</span>',
							'delay': 2500,
							'position': app.checkSettings(
								settings, 'add_to_cart_message_position', 'top_right'
							)
						});
					}

					if (result.data.added) {
						needNotify = false;
						$(document.body).trigger('added_to_cart', [wrapper.hasClass('wtpbVariableButtons') ? null : [], null, $this]);
						$(document.body).trigger('wc_fragment_refresh');
						if (isPopup) {
							$(document.body).trigger('wtbpCloseModal');
						}
					}
				}
			});

			return false;
		});
		$('.wtbpTableWrapper').on('click', '.wtbpDisabledLink .button.product_type_variation, .wtbpDisabledLink .button.product_type_variable.add_to_cart_button', function(e) {
			e.preventDefault();
			var settings = app.getSetting(false, jQuery(this).closest('.wtbpContentTable'));
			$.sNotify({
				'icon': 'fa fa-warning',
				'content': '<span style="padding-left:10px;">'+app.checkSettings(settings, 'select_attributes_text', 'Select attributes before add the product to the cart')+'.</span>',
				'delay' : 2500
			});
			return false;
		});

		// woocommerce add to cart button action injection
		$( document.body ).on( 'added_to_cart', function( fragments, cartHash, thisbutton, data ) {
			var settings = app.getSetting(false, $('table.wtbpContentTable'));
			var isAddToCartMessage = app.checkSettings(
				settings, 'show_add_to_cart_message', '1'
			);
			if (!needNotify) {
				needNotify = true;
			} else if (isAddToCartMessage) {
				$.sNotify({
					'icon': 'fa fa-check',
					'content': '<span>' + data.data('quantity') + ' Product(s) added to the cart</span>',
					'delay': 2500,
					'position': app.checkSettings(
						settings, 'add_to_cart_message_position', 'top_right'
					)
				});
			}

			jQuery('.wtbpContentTable').each(function() {
				var useAddCartStyles = app.checkSettings(settings, 'use_add_cart_styles', '0');

				if ( useAddCartStyles == '1' ) {
					var addCartStyles = app.checkSettings(settings, 'add_cart_styles', '');

					if ( addCartStyles.text ) {
						jQuery(this).find('.added_to_cart.wc-forward').each(function( ) {
							jQuery(this).text(addCartStyles.text);
						});
					}

					if ( addCartStyles.color ) {
						jQuery(this).find('.added_to_cart.wc-forward').each(function( ) {
							jQuery(this).css('background-color', addCartStyles.color);
						});
					}
				}
			});


			jQuery('.wtbpTableWrapper').each(function () {
				var _this = jQuery(this),
					tdWidth,
					th,
					index = 0,
					diff = 16; // difference between td and tr paddings

				jQuery('.wtbpContentTable tr:first-child td', _this).each(function () {
					tdWidth = jQuery(this).width() - diff;
					if (typeof tdWidth !== 'undefined') {
						th = jQuery('.dataTables_scrollHead tr th', _this).eq(index);
						th.css('width', tdWidth);
					}
					index++;
				});
			});

		});

		jQuery(document).on('click', '.paginate_button', function(e) {
			var _this = jQuery(this),
				tableId = _this.attr('aria-controls');
				var tableWrapper = jQuery('.wtbpTableWrapper[data-table-id='+tableId+']'),
					settings = app.getSetting(false, tableWrapper.find('.wtbpContentTable'));

			if (tableId.startsWith('wtbp-') && !settings.pagination_menu) {
				var getParam = window.location.search,
					tableNumber = tableId.split('_');

				tableNumber = tableNumber[0];
				if (getParam === '') {
					var newGetParam = tableNumber + '=' + tableWrapper.data('start');
					window.history.pushState("history push state", "", "?"+newGetParam);
				} else {
					var url = new URL(window.location.href);
					url.searchParams.set(tableNumber, tableWrapper.data('start'));
					window.history.pushState("history push state", "", url);
				}
			}

			if (app.checkSettings(settings, 'pagination_scroll', false)) {
				jQuery([document.documentElement, document.body]).animate({
			        scrollTop: tableWrapper.offset().top
			    }, 1500);
			}
			
		});

	});

	WtbpFrontendPage.prototype.eventsFrontend = (function () {
		$(document).on('click', '.elementor-tab-title', function(){
			var tabContent = $(this).siblings('.elementor-tab-content'),
				table = tabContent.find('[data-table-id]').eq(0);
			
			if (table.length) {
				var wtbpFrontendPage = new WtbpFrontendPage();
				wtbpFrontendPage.init();
			}
		});
	});

	$(document).ready(function () {
		var wtbpFrontendPage = new WtbpFrontendPage();
		wtbpFrontendPage.init();
	});

}(window.jQuery, window.woobewoo.WooTablepress));
