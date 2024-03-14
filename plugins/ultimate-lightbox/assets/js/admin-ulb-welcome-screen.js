jQuery(document).ready(function() {
	jQuery( '.ulb-welcome-screen-box h2' ).on( 'click', function() {
		var section = jQuery( this ).parent().data( 'screen' );
		ulb_toggle_section( section );
	});

	jQuery( '.ulb-welcome-screen-next-button' ).on( 'click', function() {
		var section = jQuery( this ).data( 'nextaction' );
		ulb_toggle_section( section );
	});

	jQuery( '.ulb-welcome-screen-previous-button' ).on( 'click', function() {
		var section = jQuery( this ).data( 'previousaction' );
		ulb_toggle_section( section );
	});

	jQuery( '.ulb-welcome-screen-save-add-lightbox-button' ).on( 'click', function() {
		
		var add_lightbox = [];

		jQuery( 'input[name="add_lightbox[]"]:checked' ).each( function() {
			add_lightbox.push( jQuery( this ).val() );
		});

		var image_class_list = jQuery( 'input[name="image_class_list"]' ).val();
		var image_selector_list = jQuery( 'input[name="image_selector_list"]' ).val();

		var params = {
			add_lightbox: JSON.stringify( add_lightbox ),
			image_class_list: image_class_list,
			image_selector_list: image_selector_list,
			nonce: ewd_ulb_getting_started.nonce,
			action: 'ulb_welcome_set_options'
		};

		var data = jQuery.param( params );

		jQuery.post( ajaxurl, data, function( response ) {} );

		ulb_toggle_section( 'options' );
	});

	jQuery( '.ulb-welcome-screen-save-options-button' ).on( 'click', function() {
		
		var show_thumbnails = jQuery( 'input[name="show_thumbnails"]:checked' ).val();
		var show_overlay_text = jQuery( 'input[name="show_overlay_text"]' ).is( ':checked' );
		var background_close = jQuery( 'input[name="background_close"]' ).is( ':checked' );

		var mobile_hide_elements = [];

		jQuery( 'input[name="mobile_hide_elements[]"]:checked' ).each( function() {
			mobile_hide_elements.push( jQuery( this ).val() );
		});

		var params = {
			show_thumbnails: show_thumbnails,
			show_overlay_text: show_overlay_text,
			background_close: background_close,
			mobile_hide_elements: JSON.stringify( mobile_hide_elements ),	
			nonce: ewd_ulb_getting_started.nonce,
			action: 'ulb_welcome_set_options'
		};

		var data = jQuery.param( params );

		jQuery.post( ajaxurl, data, function( response ) {} );

		ulb_toggle_section( 'arrows_icons' );
	});

	jQuery( '.ulb-welcome-screen-save-arrows-icons-button' ).on( 'click', function() {

		var arrow = jQuery( 'input[name="arrow"]:checked' ).val();
		var icons = jQuery( 'input[name="icons"]:checked' ).val();

		var params = {
			arrow: arrow,
			icons: icons,
			nonce: ewd_ulb_getting_started.nonce,
			action: 'ulb_welcome_set_options'
		};

		var data = jQuery.param( params );

		jQuery.post( ajaxurl, data, function( response ) {} );
	});
});

function ulb_toggle_section(page) {
	jQuery('.ulb-welcome-screen-box').removeClass('ulb-welcome-screen-open');
	jQuery('.ulb-welcome-screen-' + page).addClass('ulb-welcome-screen-open');
}