jQuery( document ).ready( function( $ ) {

	// Admin Notices
	$( document ).on( 'click', '.mxmtzc-admin-notice .notice-dismiss', function( e ) {
		e.preventDefault();

		const data = {
			action: 'mxmtzc_dismiss_admin_notice',
			notice: 'hire_developer',
			nonce: mxmtzc_admin_localize.nonce
		};

		jQuery.post(mxmtzc_admin_localize.ajax_url, data, function (response) {

			// console.log(response)

		});

	} );

	// 

	$( '.mxmtzc_save_notice' ).on( 'click', function() {

		var scroll_top_to_shortcode = $( '#mx_time_zone_shortcode' ).offset().top;

		$( 'html,body' ).animate( {scrollTop: scroll_top_to_shortcode},500 );

	} );

	// show | hide clocks types
	$( '.mxmtzc_display_clock_wrap' ).find( 'input' ).on( 'change', function() {

		if( $( this ).val() === 'digital'  ) {

			$( '.mx-clock-apperiance_box' ).hide( 'slow' );

			$( '#mxmtzc_digital_clock_value' ).text( 'true' );

			mxmtzc_show_notification( $ );

		} else {

			$( '.mx-clock-apperiance_box' ).show( 'slow' );

			$( '#mxmtzc_digital_clock_value' ).text( 'false' );

			mxmtzc_show_notification( $ );

		}

	} );

	// choose clock type
	$( '.mx-time-zone-design-list' ).find( '.mx-time-zone-design-item' ).on( 'click', function() {

		var image_upload = $( '#mxmtzc_clock_upload' ).val();

		if( image_upload === '' ) {

			var image_src = $( this ).find( 'img' ).attr( 'data-image-src' );

			$( '#mxmtzc_clock_type_value' ).text( image_src );		

			mxmtzc_show_notification( $ );

		} else {

			alert( 'To choose the default clock, please, remove the image from "Upload clock" section!' );

		}		

	} );

	// change time zone
	$( '#mxmtzc_time_zone_name' ).on( 'blur', function() {

		var time_zone_name = $( this ).val();

		if( time_zone_name === '' ) {

			time_zone_name = 'Australia/Sydney';

		}

		$( '#mxmtzc_time_zone_value' ).text( time_zone_name );

		mxmtzc_show_notification( $ );

	} );

	// change time zone
	$( '#mxmtzc_city_name' ).on( 'blur', function() {

		var city_name = $( this ).val();

		$( '#mxmtzc_city_name_value' ).text( city_name );

		mxmtzc_show_notification( $ );

	} );

	// time format
	$( '.mxmtzc_time_format_wrap' ).find( 'input' ).on( 'change', function() {

		if( $( this ).val() === '12'  ) {

			$( '#mxmtzc_time_format_value' ).text( '12' );

			mxmtzc_show_notification( $ );

		} else {

			$( '#mxmtzc_time_format_value' ).text( '24' );

			mxmtzc_show_notification( $ );

		}

	} );

	// language attr
	$( '#mxmtzc_language_attr' ).on( 'blur', function() {

		var language_attr = $( this ).val();

		$( '#mxmtzc_lang_value' ).text( language_attr );

		mxmtzc_show_notification( $ );

	} );

	// language attr for days
	$( '#mxmtzc_language_for_days_attr' ).on( 'blur', function() {

		var language_attr = $( this ).val();

		$( '#mxmtzc_language_for_days_attr_value' ).text( language_attr );

		mxmtzc_show_notification( $ );

	} );	

	// show days
	$( '.mxmtzc_show_days_wrap' ).find( 'input' ).on( 'change', function() {

		if( $( this ).val() === 'show'  ) {

			$( '#mxmtzc_show_days_value' ).text( 'true' );

			mxmtzc_show_notification( $ );

		} else {

			$( '#mxmtzc_show_days_value' ).text( 'false' );

			mxmtzc_show_notification( $ );

		}

	} );

	// font size
	$( '#mxmtzc_font_size_attr' ).on( 'change', function() {

		var font_size = $( this ).val();

		$( '#mxmtzc_font_size_attr_value' ).text( font_size );

		mxmtzc_show_notification( $ );

	} );

	// show seconds
	$( '.mxmtzc_show_seconds_wrap' ).find( 'input' ).on( 'change', function() {

		if( $( this ).val() === 'show'  ) {

			$( '#mxmtzc_show_seconds_value' ).text( 'true' );

			mxmtzc_show_notification( $ );

		} else {

			$( '#mxmtzc_show_seconds_value' ).text( 'false' );

			mxmtzc_show_notification( $ );

		}

	} );

	// arrow type
	$( '[name="mxmtzc_arrow_type"]' ).on( 'change', function() {

		var arrow_type = $( this ).val();

		$( '#mxmtzc_arrow_type_value' ).text( arrow_type );

		mxmtzc_show_notification( $ );

	} );

	// super simple clock
	$( '[name="mxmtzc_super_simple"]' ).on( 'change', function() {

		var arrow_type = $( this ).val();

		$( '#mxmtzc_super_simple_value' ).text( arrow_type );

		mxmtzc_show_notification( $ );

	} );

	// arrows color
	$( '[name="mxmtzc_arrows_color"]' ).on( 'change', function() {

		var arrows_color = $( this ).val();

		$( '#mxmtzc_arrows_color_value' ).text( arrows_color );

		mxmtzc_show_notification( $ );

	} );
	
	// upload image
	$( '[name="mxmtzc_clock_upload"]' ).on( 'change', function() {

		var _val = $( this ).val();

		_val = _val === '' ? 'false' : _val

		$( '#mxmtzc_clock_upload_value' ).text( _val );

		mxmtzc_show_notification( $ );

	} );

	$( '#mxCopyShortcode' ).on( 'click', function( e ) {

		e.preventDefault();

		copyToClipboard( $( '.mxmtzc_time_zone_shortcode_body' ) );

	} );
	
	// copy shortcode
	function copyToClipboard(element) {

	    var $temp = $( '<input>' );

	    $( 'body' ).append( $temp );

	    var shortcode = $( element ).text().replace( /(\r\n|\n|\r|\s+)/gm, ' ' );

	    shortcode = shortcode.replace( /(\s+)/gm, ' ' );

	    $temp.val( shortcode ).select();

	    document.execCommand( 'copy' );

	    alert( 'Copied: ' + shortcode + '. \r\n You can now paste this code into your page.' );

	    $temp.remove();

	}

} );

// functions
// show notification
function mxmtzc_show_notification( $ ) {

	$( '.mxmtzc_save_notice' ).show( 'slow' );

	setTimeout( function() {

		$( '.mxmtzc_save_notice' ).hide( 'slow' );

	},2000 );

}