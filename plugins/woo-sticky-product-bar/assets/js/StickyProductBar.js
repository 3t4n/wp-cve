var WCStickyProductBar = /** @class */ (function () {
    function StickyProductBar(settings) {
		this.settings = settings;
		this.isMobile = false;
		this.location = 'bottom';

		if (this.settings.isMobile == 'yes') {
			this.isMobile = true;
		}
	}
	
	StickyProductBar.prototype.isEnabled = function()
	{
		var isStickyProductBarEnabled = false;

		// check bar is enabled for the given platform
		if (this.isMobile || /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
			if (this.settings.enableMobile == 'yes') {
				isStickyProductBarEnabled = true;

				this.isMobile = true;
				this.location = this.settings.locationMobile;
			}
		} else if (this.settings.enableDesktop == 'yes') {
			isStickyProductBarEnabled = true;

			this.location = this.settings.locationDesktop;
		}
		
		return isStickyProductBarEnabled;
	};

	StickyProductBar.prototype.activateRating = function()
	{
		// activate rating plugin
		jQuery('.' + this.settings.id + ' .rateyo').rateYo();

		// fix incorrect width set by the plugin
		var ratingContainer = jQuery('.' + this.settings.id + ' .rateyo.jq-ry-container');
		ratingContainer.css('width', 'auto');
		ratingContainer.css('width', ratingContainer.width());
	};

	StickyProductBar.prototype.show = function()
	{
		var BarElement = jQuery('.' + this.settings.id);

		if (jQuery('body').hasClass(this.settings.id + '-displayed')) {
			return;
		}

		if (this.isMobile) {
			BarElement.addClass('mobile');
		} else {
			BarElement.addClass('desktop');
		}

		jQuery('body').addClass(this.settings.id + '-displayed');

		var height = BarElement.outerHeight();

		if (this.location == 'top') {
			var barPositionTop = 0;
			if (jQuery('#wpadminbar').length > 0)
			{
				barPositionTop = jQuery('#wpadminbar').position().top + jQuery('#wpadminbar').outerHeight();
			}

			BarElement.css({top: -height, bottom: 'auto'}).animate({top: barPositionTop});
		} else {
			BarElement.css({top: 'auto', bottom: -height}).animate({bottom: 0});
		}

		this.activateRating();

		BarElement.trigger('show');
	};
	
	StickyProductBar.prototype.hide = function()
	{
		jQuery('body').removeClass(this.settings.id + '-displayed');

		jQuery('.' + this.settings.id).trigger('hide');
	};

	StickyProductBar.prototype.onUpdateCheckout = function(e, data)
	{
		if (jQuery('.' + this.settings.id + '.checkout-page').length == 0) {
			return;
		}

		jQuery('.' + this.settings.id + ' .action-button').html(jQuery('#place_order').html());
	};

	StickyProductBar.prototype.onCheckoutClick = function(event)
	{
		// make sure the customer will accept terms and conditions
		if (jQuery('form.checkout [name=terms]').length > 0 && !jQuery('form.checkout [name=terms]').prop('checked')) {
			if (confirm(this.settings.termsQuestions)) {
				jQuery('.' + this.settings.id + ' [name=terms]').prop('checked', true);
				jQuery('form.checkout [name=terms]').prop('checked', true);
			} else {
				return;
			}
		}

		jQuery('#place_order').click();  
	};

	StickyProductBar.prototype.onTermsChange = function(event)
	{
		jQuery('form.checkout [name=terms]').prop('checked', jQuery(event.target).prop('checked'));
	};

	StickyProductBar.prototype.onUpdateCartTotal = function()
	{
		var TotalSource = jQuery('.cart_totals .order-total .woocommerce-Price-amount');

		if (TotalSource.length > 0) {
			jQuery('.' + this.settings.id + ' .total .woocommerce-Price-amount').html(TotalSource.html());
		} else if (jQuery('.cart-empty').length > 0) {
			jQuery('.' + this.settings.id).hide();
		}
	};

	StickyProductBar.prototype.getBarPriceElement = function()
	{
		return jQuery('.' + this.settings.id + ' .price');
	};

	StickyProductBar.prototype.getProductPriceElement = function()
	{
		var filter = this.settings.productPriceFilter ? this.settings.productPriceFilter : ':last';

		var PriceSource = jQuery();
		if (this.settings.productPriceSelector && this.settings.productPriceSelector.length > 0) {
			PriceSource = jQuery(this.settings.productPriceSelector).filter(filter);
		}

		if (PriceSource.length == 0)
		{
			PriceSource = jQuery('.summary.entry-summary .price > .amount, .summary.entry-summary .price ins .amount').filter(filter);
		}

		return PriceSource;
	};

	StickyProductBar.prototype.getBarQuantityElement = function()
	{
		return jQuery('.' + this.settings.id + '.product-page input[name=quantity]');
	};

	StickyProductBar.prototype.getProductQuantityElement = function()
	{
		var stickyBarQuantity = this.getBarQuantityElement();

		var productQuantity = jQuery();
		if (this.settings.productQuantitySelector && this.settings.productQuantitySelector.length > 0) {
			productQuantity = jQuery(this.settings.productQuantitySelector).not(stickyBarQuantity);
		}

		if (productQuantity.length == 0) {
			productQuantity = jQuery('.cart input[name=quantity]').not(stickyBarQuantity);
		}

		return productQuantity;
	};

	StickyProductBar.prototype.setFromProductPrice = function()
	{
		var productPrice = this.getProductPriceElement();
		var stickBarPrice = this.getBarPriceElement();
		if (productPrice.length > 0 && productPrice.html() != stickBarPrice.html()) {
			stickBarPrice.html(productPrice.html());
		}
	};

	StickyProductBar.prototype.setFromProductQuantity = function()
	{
		var productQuantity = this.getProductQuantityElement();
		var stickBarQuantity = this.getBarQuantityElement();
		if (productQuantity.length > 0 && productQuantity.val() != stickBarQuantity.val()) {
			stickBarQuantity.val(productQuantity.val());
		}
	};

	StickyProductBar.prototype.onProductOptionChange = function()
	{
		this.setFromProductPrice();
		this.setFromProductQuantity();
	};

	StickyProductBar.prototype.onQuantityChange = function()
	{
		var stickyBarQuantity = this.getBarQuantityElement();
		var productQuantity = this.getProductQuantityElement();
		if (productQuantity.length > 0 && productQuantity.val() != stickyBarQuantity.val()) {
			productQuantity.val(stickyBarQuantity.val()).change();
		}
	};

	StickyProductBar.prototype.onAddToCartClick = function(event)
	{
		var addToCartButton = this.getAddToCartButton();

		if (addToCartButton.filter('.disabled.wc-variation-selection-needed').length > 0) {
			this.scrollTo(addToCartButton);
		} else {
			addToCartButton.click();
		}

		return false;
	};

	StickyProductBar.prototype.onComponentTotalChanged = function()
	{
		// seems like composite uses timeout so we won't be able to get final totals without timeout
		setTimeout(function() {
		  var TotalSource = jQuery('.composite_price ins .woocommerce-Price-amount');
	
		  if (TotalSource.length > 0) {
			jQuery('.' + this.settings.id + '.product-page .woocommerce-Price-amount').html(TotalSource.html());
		  }  
		}, 100);
	};

	StickyProductBar.prototype.showChooseAnOptionButtonText = function()
	{
		var actionButton = jQuery('.' + this.settings.id + '.product-page .action-button');
		if (this.settings.textChooseAnOption && this.settings.textChooseAnOption.length > 0) {
			if (!actionButton.data('originalText')) {
				actionButton.data('originalText', actionButton.text());
			}

			actionButton.text(this.settings.textChooseAnOption);
		}
	};

	StickyProductBar.prototype.restoreButtonText = function()
	{
		var actionButton = jQuery('.' + this.settings.id + '.product-page .action-button');
		if (actionButton.data('originalText') && actionButton.text() != actionButton.data('originalText')) {
			actionButton.text(actionButton.data('originalText'));
		}
	};

	StickyProductBar.prototype.onInit = function()
	{
		// conditional show bar when add to cart button is not visible
		if (this.settings.alwaysVisible == 'yes') {
			this.show();

			return;
		};

		this.toggleVisibility();

		var showStickyProductBarTimeoutId = null;
		var _this = this;

		jQuery(window).on('scroll', function() {
			clearTimeout(showStickyProductBarTimeoutId);
			
			showStickyProductBarTimeoutId = setTimeout(function() {
				_this.toggleVisibility();
			}, 20);
		});
	};

	StickyProductBar.prototype.scrollTo = function(target)
	{
		var newTopPosition = target.offset().top;
		newTopPosition -= window.innerHeight / 2;

		jQuery('html, body').animate({
			scrollTop: newTopPosition
		}, parseInt(this.settings.scrollAnimationDuration));

		target.focus();
	};

	StickyProductBar.prototype.getAddToCartButton = function()
	{
		return jQuery('[name=add-to-cart]:visible, .single_add_to_cart_button:visible').not('.wc-sticky-product-bar button[type=submit]');
	};

	StickyProductBar.prototype.toggleVisibility = function()
	{
		var element = jQuery('[name=add-to-cart], .single_add_to_cart_button, .checkout-button, .woocommerce-review-order-payment button[type=submit]');
		if (element.length == 0 || this.isElementVisible(element)) {
			this.hide();
		} else {
			this.show();
		} 
	};

	StickyProductBar.prototype.isElementVisible = function(element)
	{
		var element = jQuery(element);
		var pageTop = jQuery(window).scrollTop();
		var pageBottom = pageTop + window.innerHeight;
		var elementTop = jQuery(element).offset().top;
		var elementBottom = elementTop + jQuery(element).height();

		return ((pageTop < elementTop) && (pageBottom > elementBottom));
    };

	StickyProductBar.prototype.getClickEventName = function()
	{
  		// we need to choose correct event to avoid double execution
		var clickEventName = 'click';
		if ('ontouchend' in document.documentElement) {
			clickEventName = 'touchend';
		}

		return clickEventName;
	};

	StickyProductBar.prototype.register = function ()
	{
		if (!this.isEnabled()) {
			return;
		}

		var _this = this;
		var clickEventName = this.getClickEventName();

		jQuery(window).on('load', function() { _this.onInit(); });
	
		// passthrough click on place an order button
  		// checkout button is clicked on sticky bar
		jQuery(document).on(clickEventName, '.' + this.settings.id + '.checkout-page .action-button', function(event) { return _this.onCheckoutClick(event); });

		// add to cart button is clicked
		jQuery(document).on(clickEventName, '.' + this.settings.id + '.product-page .action-button', function(event) { return _this.onAddToCartClick(event); });

		// passthrough click on terms checkbox
		jQuery(document).on('change', '.' + this.settings.id + '.checkout-page [name=terms]', function(event) { return _this.onTermsChange(event); });

		// product quantity is updated on the sticky bar
		jQuery(document).on('change', '.' + this.settings.id + '.product-page input[name=quantity]', function() { return _this.onQuantityChange(); });

		// update price on any product option change  
		jQuery(document).on('change', '.product form.cart :input', function(event) { return _this.onProductOptionChange(event); });
		
		// handle when shipping method is changed on the cart page
		jQuery(document).on('wc_fragments_refreshed', function() { return _this.onUpdateCartTotal(); });
		jQuery(document).on('updated_shipping_method', function() { return _this.onUpdateCartTotal(); });

		// update place order text on event from checkout page
		jQuery(document).on('change', 'input[name="payment_method"]', function(event) { return _this.onUpdateCheckout(event); });
		jQuery(document).on('updated_checkout', function(e, data) { return _this.onUpdateCheckout(e, data); });

		// handle composite products
		jQuery('.composite_data').on('wc-composite-initializing', function(event, composite) {
			composite.actions.add_action('composite_totals_changed', function() { return _this.onComponentTotalChanged(); }, 1000);
		});

		// update text of Add to Cart button when variations are hidden or shown
		jQuery(document).on('hide_variation', '.product form.cart', function(event) { return _this.showChooseAnOptionButtonText(); });
		jQuery(document).on('show_variation', '.product form.cart', function(event) { return _this.restoreButtonText(); });

	};
	
    return StickyProductBar;
}());

(new WCStickyProductBar(WCStickyProductBarSettings)).register();