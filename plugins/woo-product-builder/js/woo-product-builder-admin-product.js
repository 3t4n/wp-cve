'use strict';
jQuery(document).ready(function () {
	// Color picker
	jQuery('.color-picker').iris({
		change: function (event, ui) {
			jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
			var ele = jQuery(this).data('ele');
			if (ele == 'highlight') {
				jQuery('#message-purchased').find('a').css({'color': ui.color.toString()});
			} else if (ele == 'textcolor') {
				jQuery('#message-purchased').css({'color': ui.color.toString()});
			} else {
				jQuery('#message-purchased').css({backgroundColor: ui.color.toString()});
			}
		},
		hide  : true,
		border: true
	}).click(function () {
		jQuery('.iris-picker').hide();
		jQuery(this).closest('td').find('.iris-picker').show();
	});
	jQuery('body').click(function () {
		jQuery('.iris-picker').hide();
	});
	jQuery('.color-picker').click(function (event) {
		event.stopPropagation();
	});
	jQuery('select.vi-ui.dropdown').dropdown();
	/*End setup tab*/
	jQuery('.vi-ui.checkbox').checkbox();
	jQuery('.vi-ui.radio').checkbox();

	/*Check item added*/
	var tab_id = jQuery('.menu .item').first().attr('data-tab');
	woo_product_buider.check_added(tab_id);

	/*Clear all compatible select*/
	woo_product_buider.compatible_remove_all();

	/*Product Configuration*/
	woo_product_buider.init();
	woo_product_buider.ajax_search();


});

var woo_product_buider = {
	init                 : function () {
		this.tab_init();
		this.add_item();
		this.remove_item();
	},
	/**
	 * Reinit tab
	 */
	tab_init             : function () {
		/*Init tab */
		jQuery('.menu .item').unbind();
		jQuery('.vi-ui.tabular.menu .item').vi_tab({
			history    : true,
			historyType: 'hash'
		});
		jQuery('.menu .item').on('click', function () {
			/*Check item added*/
			var tab_id = jQuery(this).attr('data-tab');
			woo_product_buider.check_added(tab_id);
		});

		/*Add new tab*/
		jQuery('.woopb-add-tab').unbind();
		jQuery('.woopb-add-tab').on('click', function () {
			if (jQuery('.woopb-tabs .menu a').length > 2) {
				alert(_woopb_params.message_notice_3)
				return;
			}
			var tab_id = Date.now();
			var title = prompt(_woopb_params.tab_title);
			if (title == null || title == '') {
				return;
			}
			/*Menu*/
			var tab_data = jQuery('.woopb-tabs .menu a:first-child').clone();
			tab_data.find('.woopb-tab-title').text(title);
			tab_data.addClass('active').attr('data-tab', tab_id).find('input').val(title);
			jQuery('.woopb-tabs .menu').find('a').removeClass('active');
			jQuery('.woopb-tabs .menu').append(tab_data);

			/*Tab content*/
			var tab_content = jQuery('.woopb-tabs-content .tab').first().clone().html('');
			jQuery('.woopb-tabs-content .tab').removeClass('active');
			tab_content.addClass('active').attr('data-tab', tab_id);
			jQuery('.woopb-tabs-content').append(tab_content);
			woo_product_buider.check_added(tab_id);
			woo_product_buider.tab_init();
		});

		/*Edit tab title*/
		jQuery('.woopb-edit').unbind();
		jQuery('.woopb-edit').on('click', function () {
			var current_tab_item = jQuery(this).closest('a');
			var title = prompt(_woopb_params.tab_title_change);
			if (title == null || title == '') {
				return;
			}
			current_tab_item.find('.woopb-tab-title').text(title);
			current_tab_item.find('input').val(title);
		});

		/*Remove tab*/
		jQuery('.woopb-remove').unbind();
		jQuery('.woopb-remove').on('click', function () {
			var r = confirm(_woopb_params.tab_notice_remove);
			if (r == true && jQuery('.woopb-tabs .menu .item').length > 1) {
				var tab_id = jQuery(this).closest('a').attr('data-tab');
				jQuery('a[data-tab="' + tab_id + '"],div[data-tab="' + tab_id + '"]').remove();
				if (jQuery(this).closest('a').hasClass('active')) {
					var new_tab_id = jQuery('.woopb-tabs-content .tab').first().attr('data-tab');
					jQuery('.woopb-tabs .menu .item').first().addClass('active');
					jQuery('.woopb-tabs-content .tab').first().addClass('active');
					woo_product_buider.check_added(new_tab_id);
				}
			}
		});
	},
	/**
	 * Add item to tab
	 */
	add_item             : function () {
		jQuery('.woopb-product-select .woopb-item').unbind();
		jQuery('.woopb-product-select .woopb-item:not(".woopb-exist")').on('click', function () {
			var item_html = jQuery(this).clone();
			var current_tab = jQuery('.woopb-tabs-content .active.tab');
			var tab_id = current_tab.attr('data-tab');
			var item_id = jQuery(this).attr('data-id');
			if (jQuery(this).hasClass('woopb-item-category')) {
				item_id = 'cate_' + item_id;
			}

			item_html.append('<input type="hidden" name="woopb-param[list_content][' + tab_id + '][]" value="' + item_id + '"/>');
			current_tab.append(item_html);
			jQuery(this).addClass('woopb-exist');
			woo_product_buider.init();
		})
	},
	/**
	 * Toggle item in tab
	 */
	remove_item          : function () {
		jQuery('.woopb-tabs-content .tab .woopb-item').on('click', function () {
			var item_id = jQuery(this).attr('data-id');
			if (jQuery(this).hasClass('woopb-item-category')) {
				jQuery('.woopb-product-select .woopb-item-category[data-id="' + item_id + '"]').removeClass('woopb-exist');
			} else {
				jQuery('.woopb-product-select .woopb-item-product[data-id="' + item_id + '"]').removeClass('woopb-exist');
			}
			jQuery(this).remove();
			woo_product_buider.add_item();
		})
	},
	/**
	 * Check tab what added in tab
	 * @param tab_id
	 */
	check_added          : function (tab_id) {
		jQuery('.woopb-product-select .woopb-item').removeClass('woopb-exist');
		jQuery('.woopb-tabs-content .tab[data-tab="' + tab_id + '"]').find('.woopb-item').each(function () {
			var item_id = jQuery(this).attr('data-id');
			if (jQuery(this).hasClass('woopb-item-category')) {
				jQuery('.woopb-product-select .woopb-item-category[data-id="' + item_id + '"]').addClass('woopb-exist');
			} else {
				jQuery('.woopb-product-select .woopb-item-product[data-id="' + item_id + '"]').addClass('woopb-exist');
			}
		});
		woo_product_buider.add_item();
	},
	ajax_search          : function () {
		jQuery('.wpb-search-field,.woopb-type').on('change', function () {
			woo_product_buider.send_ajax();
		});
		jQuery('.woopb-search-button').on('click', function () {
			woo_product_buider.send_ajax();
		});
	},
	send_ajax            : function () {
		var keyword = jQuery('.wpb-search-field').val();

		var type = jQuery('.woopb-type option:selected').val();
		var data ={
			_woopb_field_nonce: jQuery('#_woopb_field_nonce').val(),
			action: 'woopb_get_data',
			type: type,
			keyword: keyword,
		};
		var template = wp.template('woopb-item-template');
		jQuery('.woopb-items').html('');
		jQuery('.woopb-search-button').addClass('loading');
		jQuery('.woopb-search-form').addClass('loading');
		jQuery.ajax({
			type   : 'POST',
			data   :  data,
			url    : _woopb_params.ajax_url,
			success: function (data) {
				jQuery('.woopb-search-form').removeClass('loading');
				jQuery('.woopb-search-button').removeClass('loading');
				jQuery.each(data, function (index, value) {
					if (type == 0) {
						var html = template({
							id        : value.id,
							name      : value.title,
							item_class: 'category',
							thumb     : '',
						});
						jQuery('.woopb-items').append(html);

					} else {
						if (value.thumb_url == false) {
							var html = template({
								id        : value.id,
								name      : value.title,
								item_class: 'product',
								thumb     : '',
							});
							jQuery('.woopb-items').append(html);
						} else {
							var html = template({
								id        : value.id,
								name      : value.title,
								item_class: 'product woopb-img',
								thumb     : '<img src="' + value.thumb_url + '"/>',
							});
							jQuery('.woopb-items').append(html);
						}
					}
				});
				var current_tab = jQuery('.woopb-tabs-content .active.tab').attr('data-tab');
				woo_product_buider.check_added(current_tab);
				woo_product_buider.init();
			},
			error  : function (html) {
				jQuery('.woopb-search-button').removeClass('loading');
				jQuery('.woopb-search-form').removeClass('loading');
			}
		})
	},
	compatible_remove_all: function () {
		jQuery('.woopb-compatible-clear-all').on('click', function () {
			var r = confirm(_woopb_params.compatible_notice_remove);
			if (r == true) {
				jQuery('.woopb-compatible-field option:selected').removeAttr("selected");
			}
		})
	}
}