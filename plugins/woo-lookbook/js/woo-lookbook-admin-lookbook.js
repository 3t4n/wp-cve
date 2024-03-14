'use strict';
jQuery(document).ready(function () {
	jQuery('#wpbody-content .wrap a.page-title-action:first').after('<span class="page-title-action instagram wlb-instagram-sync"><i class="instagram icon"></i>' + _wlb_params.sync_title + '</span>');
	/*Ajax sync button*/
	jQuery('.wlb-instagram-sync').one('click', function () {
		jQuery(this).addClass('loading');
		jQuery(this).text(_wlb_params.loading);
		var button = jQuery(this);
		jQuery.ajax({
			type   : 'POST',
			data   : 'action=wlb_sync_instagram&nonce=' + _wlb_params.nonce,
			url    : _wlb_params.ajax_url,
			success: function (data) {
				button.removeClass('loading');
				button.text(_wlb_params.sync_title);
				location.reload();
			},
			error  : function (html) {
			}
		})
	});
	/*Quick status*/
	jQuery('.wlb_quick_status span.button').bind('click', function () {
		jQuery(this).addClass('loading');
		var p_id = jQuery(this).closest('.vi-ui.buttons').attr('data-id');
		var p_status = jQuery(this).attr('data-val');
		var buttons = jQuery(this).closest('.vi-ui.buttons');
		var button = jQuery(this);
		jQuery.ajax({
			type   : 'POST',
			data   : 'action=wlb_change_status&' + 'p_id=' + p_id + '&p_status=' + p_status + '&nonce=' + _wlb_params.nonce,
			url    : _wlb_params.ajax_url,
			success: function (data) {
				buttons.find('span.button').removeClass('green grey orange loading');
				if (p_status == 1) {
					button.addClass('green');
				} else if (p_status == 2) {
					button.addClass('grey');
				} else {
					button.addClass('orange');
				}
			},
			error  : function (html) {
			}
		})
	});
	jQuery(".wlb-shortcode").click(function () {
		jQuery(this).select();
	});

});