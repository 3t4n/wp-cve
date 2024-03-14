jQuery(document).ready(function($) {
	"use strict";

	if( 'undefined' == typeof hootkitSettingsData )
		window.hootkitSettingsData = {};

	var dirtyvalues = false;
	window.onbeforeunload = function(e) {
		if ( dirtyvalues ) {
			e.preventDefault();
			e.returnValue = 'Some text';
		}
	};

	/*** Nav ***/

	var $boxes = $('.hk-box'),
		$navitems = $('.hk-navitem');
	$('.hootkit-nav .hk-navitem').click( function(e){
		var $self = $(this);
		if ( !$self.is('.reload-href') ) {
			e.preventDefault();
			var newView = $self.data('view');
			$boxes.removeClass('hk-box-current').filter('#hk-' + newView).addClass('hk-box-current');
			$navitems.removeClass('hk-currentnav');
			$self.addClass('hk-currentnav');
		}
	} );

	/*** Modules Toggle ***/

	$('.hk-modtype-enable').click( function(e){
		$(this).siblings('input').prop( "checked", false ).closest('.hk-box').removeClass('hk-box-disabled');
		dirtyvalues = true;
	} );
	$('.hk-modtype-disable').click( function(e){
		$(this).siblings('input').prop( "checked", true ).closest('.hk-box').addClass('hk-box-disabled');
		dirtyvalues = true;
	} );

	/*** Filters ***/

	$('.hk-box-nav').each(function(){
		var $self = $(this),
			$filters = $self.find('.hk-boxnav-filter'),
			$modules = $self.siblings('.hk-box-modules').find('.hk-module');
		$filters.click( function(e){
			var slug = $(this).data('displayset');
			$modules.show().not('.hk-set-' + slug).hide();
			$filters.removeClass('hk-currentfilter');
			$(this).addClass('hk-currentfilter');
		} );
	});

	/*** Module Toggle ***/

	$('.hk-toggle').click( function(e){
		$(this).siblings('input[type=checkbox]').click();
		dirtyvalues = true;
	});

	/*** Submit ***/

	$('#hk-submit').click( function(e){
		e.preventDefault();

		var $submit = $(this),
			$form = $('#hootkit-settings'),
			$feedback = $('#hkfeedback'),
			formvalues = $form.serialize();
		// console.log($form.serialize()); console.log($form.serializeArray()); return;

		if ( $submit.is('.disabled') )
			return;

		$form.addClass('hksdisabled');
		$submit.addClass('disabled');
		$feedback.hide();

		$.ajax({
			method: 'POST',
			url: hootkitSettingsData.ajaxurl, // url with nonce GET param
			data: { 'handle' : 'setactivemods', 'values' : formvalues },
			success: function( data ){
				dirtyvalues = false;
				console.log(data);
				if ( data.setactivemods == true ) {
					feedback( $feedback, 'success', hootkitSettingsData.strings.success );
				} else {
					var msg = ( 'undefined' !== typeof data.msg ) ? data.msg : hootkitSettingsData.strings.error;
					feedback( $feedback, 'error', msg );
				}
			},
			error: function( data ){
				feedback( 'error', hootkitSettingsData.strings.error );
			},
			complete: function( data ){
				$form.removeClass('hksdisabled');
				$submit.removeClass('disabled');
			}
		});

	});

	function feedback( $feedback, context, string ) {
		$feedback.html( string ).removeClass('hkfberror hkfbsuccess').addClass('hkfb'+context).fadeIn().delay(1500).fadeOut();
	}

});