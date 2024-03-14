(function($){
	"use strict";
	var c4dWooWishlist = {
		prefix : 'c4d-woo-wishlist'
	}

	if (typeof c4d_woo_wishlist == 'undefined') return;

	c4dWooWishlist.cookieName = c4dWooWishlist.prefix + '-cookie';

	c4dWooWishlist.current = function() {
		var current = $.cookie(c4dWooWishlist.cookieName);
		return current = typeof current != 'undefined' ? current.split(',').filter(Number) : [];
	};

	c4dWooWishlist.deleteUserMeta = function(id) {
		$.get({
			url: c4d_woo_wishlist.ajax_url,
			data: {
				'action': 'c4d_woo_delete_user_meta',
				'pid': id
			}
		}).done(function(res){
			
		});
	};

	c4dWooWishlist.cart = function(callback) {
		$.get({
			url: c4d_woo_wishlist.ajax_url,
			data: {
				'action': 'c4d_woo_wishlist_cart',
			}
		}).done(function(res){
			$('.c4d-woo-wishlist-cart__list').html(res);
			c4dWooWishlist.hideList($('.c4d-woo-wishlist-cart__icon'));
			if (callback) {
				callback();	
			}
		});
	};

	c4dWooWishlist.hideList = function(self, addCart) {
		if (addCart) {
			$('.c4d-woo-wishlist-cart').removeClass('empty');
		} else {
			if ($(self).parents('.c4d-woo-wishlist-cart').find('.c4d-woo-wishlist-cart__list_items .item').length <= 1) {
				$(self).parents('.c4d-woo-wishlist-cart').addClass('empty');
			} else {
				$(self).parents('.c4d-woo-wishlist-cart').removeClass('empty');
			}	
		}
	};

	$(document).ready(function(){
		var current = $.cookie(c4dWooWishlist.cookieName),
		number = $('.c4d-woo-wishlist-cart__icon .number');
		current = (typeof current != 'undefined' && current != '') ? current.split(',').filter(Number) : [];
		
		c4dWooWishlist.cart();

		$('body').on('click', '.c4d-woo-wishlist-button', function(event){
			event.preventDefault();
			var self = this,
			id = $(self).attr('data-id'),
			current = c4dWooWishlist.current();
			
			if ($.inArray(id, current) < 0) {
				current.push(id);
				$.cookie(c4dWooWishlist.cookieName, current, { expires: 30, path: '/'});	
				number.html(parseInt(number.html()) + 1);
				number.addClass('add-new');
				$(self).addClass('added');
				c4dWooWishlist.hideList(self, true);
				c4dWooWishlist.cart(function(){
					number.removeClass('add-new');
				});
			}
			
			return false;
		});

		$('body').on('click', '.c4d-woo-wishlist-remove-item', function(event){
			event.preventDefault();
			var self = this,
			id = $(self).attr('data-id'),
			current = c4dWooWishlist.current(),
			index = $.inArray(id, current);
			current.splice(index, 1);
			//update cookie
			$.cookie(c4dWooWishlist.cookieName, current, { expires: 30, path: '/'});	
			// update number cart wishtlist
			if (parseInt(number.html()) >= 1) {
				number.html(parseInt(number.html()) - 1);
				$('.c4d-woo-wishlist-cart__list_header .number').html(parseInt($('.c4d-woo-wishlist-cart__list_header .number').html()) - 1);	
			}
			// remove added class for button wishlist 
			$('.c4d-woo-wishlist-button[data-id="'+id+'"]').removeClass('added');
			// remove this item from list
			$(self).parents('.item').addClass('remove');
			setTimeout(function(){
				c4dWooWishlist.hideList(self);
				$(self).parents('.item').remove();
			}, 500);
			c4dWooWishlist.deleteUserMeta(id);
		});

		$('body').on('click', '.c4d-woo-wishlist-cart__list_items .add_to_cart_button', function(event){
			event.preventDefault();
			if (!$(this).hasClass('.product_type_variable')) {
				$(this).parents('.item').find('.c4d-woo-wishlist-remove-item').trigger('click');
				window.location = $(this).attr('href');
			}
		});
	});
})(jQuery);