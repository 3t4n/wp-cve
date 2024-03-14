/**
 * @copyright	Copyright (C) 2012 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr - http://www.wp-plugins
 * Module Accordeon CK
 * @license		GNU/GPL
 * */

(function($) {

	//define the defaults for the plugin and how to call it
	$.fn.accordeonmenuck = function(options) {
		//set default options
		var defaults = {
			eventtype: 'click',
			// fadetransition: false, // pas encore implemente
			transition: 'linear',
			duree: 500,
			defaultopenedid: '0',
			showactive: '1',
			showactivesubmenu: '1',
			activeeffect: true
		};

		//call in the default otions
		var opts = $.extend(defaults, options);
		var menu = this;

		//act upon the element that is passed into the design
		return menu.each(function(options) {
			if (! menu.attr('accordeonck_done')) {
				menu.attr('accordeonck_done', '1');
				accordeonmenuInit();
			}
		});
// menu-item menu-item-type-taxonomy menu-item-object-product_cat current-product-ancestor menu-item-has-children accordeonck parent menu-item-778 level1 parent open
// menu-item menu-item-type-taxonomy menu-item-object-product_cat current-product-ancestor current-menu-parent current-product-parent accordeonck menu-item-779 level2
		function accordeonmenuInit() {
			$(".parent > ul", menu).hide();
			if (opts.showactive == '1' && !opts.activeeffect) {
				$(".current-menu-parent > ul", menu).show().parent().addClass("open");
				$(".accordeonck.current-menu-parent", menu).parents('li.accordeonck:not(.open)').find('> ul').each(function() {
					$(this).show().parent().addClass("open");
				});
				if (opts.showactivesubmenu == '1') {
					$(".current-menu-item.parent > ul", menu).show().parent().addClass("open");
				}
			} else if (opts.showactive == '1' && opts.activeeffect) {
				togglemenu($(".current-menu-ancestor > .toggler, .current-product-ancestor > .toggler", menu));
				if (opts.showactivesubmenu == '1') {
					togglemenu($(".current-menu-item.parent > .toggler, .current-product-parent > .toggler", menu));
				}
			}
			if (opts.defaultopenedid == '1' && !$(".current-menu-parent", menu).length) {
				$(".item-"+opts.defaultopenedid+" > ul", menu).show().parent().addClass("open");
				// $(".item-"+opts.defaultopenedid+" > img.toggler", menu).attr('src', opts.imageminus);
			}
			if (opts.eventtype == 'click') {
				$("li.parent > .toggler", menu).click(function() {
					togglemenu($(this));
				});
			} else {
				$("li.parent > .toggler > .toggler_icon", menu).mouseenter(function() {
					togglemenu($(this).parent());
				});
			}
		}

		function togglemenu(link) {
			ck_content = link.parent();
			if (!link.parent().hasClass("open")) {
				$(".parent > ul", ck_content.parent()).slideUp({
					duration: opts.duree,
					easing: opts.transition,
					complete: function() {
						$(".parent", ck_content.parent()).removeClass("open");
						// $(".parent > img.toggler", ck_content.parent()).attr('src', opts.imageplus);
						// if (link.get(0).tagName.toLowerCase() == 'img')
							// link.attr('src', opts.imageplus);
					}
				});
				link.nextAll("ul").slideDown({
					duration: opts.duree,
					easing: opts.transition,
					complete: function() {
						link.parent().addClass("open");
						// if (link.get(0).tagName.toLowerCase() == 'img')
							// link.attr('src', opts.imageminus);
					}
				});
			} else {
				link.nextAll("ul").slideUp({
					duration: opts.duree,
					easing: opts.transition,
					complete: function() {
						link.parent().removeClass("open");
						// if (link.get(0).tagName.toLowerCase() == 'img')
							// link.attr('src', opts.imageplus);
					}
				});
			}
		}

	};
})(jQuery);