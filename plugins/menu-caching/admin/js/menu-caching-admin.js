(function($){
	'use strict';
	$(document).ready(function() {

		let nocacheMenus = ajax_data.nocache_menus;
		let statusToggle = $(".dc-mc-enable-menu-state-toggle input[type=checkbox]");

		statusToggle.each(function(index){
			if( nocacheMenus.indexOf($(this).data("menu-slug") ) === -1 ) {
				$(this).prop( "checked", true );
			}
		});

		$('#dc_mc_enable_save').on('click tap', function(){

			let saveBtn = $(this);
			saveBtn.prop('disabled', true);

			let nocacheMenus = [];
			let checkboxes = $('.dc-mc-enable-menu-state-toggle');

			checkboxes.each( function(index){
				if( $(this).find('input[type=checkbox]:checked').length < 1 ) {
					let menuSlug = $(this).find('input[type=checkbox]').data("menu-slug");
					nocacheMenus.push(menuSlug);
				}
			});

			$.ajax({
				url: ajax_data.ajaxurl,
				type: "post",
				data:{
					action: "dc_save_nocache_menus",
					nocache_menus: nocacheMenus,
					nonce_ajax: ajax_data.nonce
				},
				success: function(response) {
					if ( response.success === true ) {
						saveBtn.prop('disabled', false);
					}
				}
			});
		});
	});

	$('#dc_menu_caching_purge_all').on('click tap',function(){

		let btn = $(this);
		btn.prop('disabled', true);

		$.ajax({
			url: ajax_data.ajaxurl,
			type: "post",
			data:{
				action: "dc_menu_caching_purge_all",
				nonce_ajax: ajax_data.nonce
			},
			success: function(response){
				if (response.success === true) {
					alert(ajax_data.message);
					btn.prop('disabled', false);
				}
			}
		});
	});
})(jQuery);
