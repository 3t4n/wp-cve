//NEW DASHBOARD MOBILE MENU AND WIDGET TOGGLING
jQuery(document).ready(function($){
	$('#ewd-ufaq-dash-mobile-menu-open').click(function(){
		$('.ewd-ufaq-admin-header-menu .nav-tab:nth-of-type(1n+2)').toggle();
		$('#ewd-ufaq-dash-mobile-menu-up-caret').toggle();
		$('#ewd-ufaq-dash-mobile-menu-down-caret').toggle();
		return false;
	});
	$(function(){
		$(window).resize(function(){
			if($(window).width() > 800){
				$('.ewd-ufaq-admin-header-menu .nav-tab:nth-of-type(1n+2)').show();
			}
			else{
				$('.ewd-ufaq-admin-header-menu .nav-tab:nth-of-type(1n+2)').hide();
				$('#ewd-ufaq-dash-mobile-menu-up-caret').hide();
				$('#ewd-ufaq-dash-mobile-menu-down-caret').show();
			}
		}).resize();
	});	
	$('#ewd-ufaq-dashboard-support-widget-box .ewd-ufaq-dashboard-new-widget-box-top').click(function(){
		$('#ewd-ufaq-dashboard-support-widget-box .ewd-ufaq-dashboard-new-widget-box-bottom').toggle();
		$('#ewd-ufaq-dash-mobile-support-up-caret').toggle();
		$('#ewd-ufaq-dash-mobile-support-down-caret').toggle();
	});
	$('#ewd-ufaq-dashboard-optional-table .ewd-ufaq-dashboard-new-widget-box-top').click(function(){
		$('#ewd-ufaq-dashboard-optional-table .ewd-ufaq-dashboard-new-widget-box-bottom').toggle();
		$('#ewd-ufaq-dash-optional-table-up-caret').toggle();
		$('#ewd-ufaq-dash-optional-table-down-caret').toggle();
	});
});

// About Us Page
jQuery( document ).ready( function( $ ) {

	jQuery( '.ewd-ufaq-about-us-tab-menu-item' ).on( 'click', function() {

		jQuery( '.ewd-ufaq-about-us-tab-menu-item' ).removeClass( 'ewd-ufaq-tab-selected' );
		jQuery( '.ewd-ufaq-about-us-tab' ).addClass( 'ewd-ufaq-hidden' );

		var tab = jQuery( this ).data( 'tab' );

		jQuery( this ).addClass( 'ewd-ufaq-tab-selected' );
		jQuery( '.ewd-ufaq-about-us-tab[data-tab="' + tab + '"]' ).removeClass( 'ewd-ufaq-hidden' );
	} );

	jQuery( '.ewd-ufaq-about-us-send-feature-suggestion' ).on( 'click', function() {

		var feature_suggestion = jQuery( '.ewd-ufaq-about-us-feature-suggestion textarea' ).val();
		var email_address = jQuery( '.ewd-ufaq-about-us-feature-suggestion input[name="feature_suggestion_email_address"]' ).val();
    
    	var params = {};

    	params.nonce  				= ewd_ufaq_php_data.nonce;
    	params.action 				= 'ewd_ufaq_send_feature_suggestion';
    	params.feature_suggestion   = feature_suggestion;
    	params.email_address 		= email_address;

    	var data = jQuery.param( params );
    	jQuery.post( ajaxurl, data, function() {} );

    	jQuery( '.ewd-ufaq-about-us-feature-suggestion' ).prepend( '<p>Thank you, your feature suggestion has been submitted.' );
	} );
} );

jQuery(document).ready(function($) {

	$( '.ewd-ufaq-list' ).sortable({
		items: '.ewd-ufaq-item',
		opacity: 0.6,
		cursor: 'move',
		axis: 'y',
		update: function() {
				var order = jQuery( this ).sortable( 'serialize' ) 
				  + '&action=ewd_ufaq_update_order'
				  + '&nonce='+ewd_ufaq_php_data.nonce;
				jQuery.post( ajaxurl, order, function( response ) {} );
		}
	});
});

jQuery(document).ready(function($) {

	if ( ewd_ufaq_php_data.ordering ) { return; }

	$( 'input[name="ewd-ufaq-settings[faq-order-by]"][value="set_order"]' ).prop( 'disabled', true );
});

jQuery(document).ready(function($){

	$( '.sap-new-admin-add-button' ).on( 'click', function() {

		setTimeout( ewd_ufaq_field_added_handler, 500);
	});
});

function ewd_ufaq_field_added_handler() {

	var highest = 0;
	jQuery( '.sap-infinite-table input[data-name="id"]' ).each( function() {
		highest = Math.max( highest, this.value );
	});

	jQuery( '.sap-infinite-table  tbody tr:last-of-type span.sap-infinite-table-hidden-value' ).html( highest + 1 );
	jQuery( '.sap-infinite-table  tbody tr:last-of-type input[data-name="id"]' ).val( highest + 1 );
}

// Reset button for search term statistics
jQuery( document ).ready( function() {
	jQuery( '.ewd-ufaq-settings-reset-search-terms' ).on( 'click', function() {

		if ( confirm( 'Are you sure that you want to delete all saved search terms?' ) ) {
    	
	    	jQuery( '.ewd-ufaq-search-terms-box' ).hide( 500 );
	
	    	var params = {};
	
	    	params.nonce  				= ewd_ufaq_php_data.nonce;
	    	params.action 				= 'ewd_ufaq_reset_saved_search_terms';
	
	    	var data = jQuery.param( params );
	    	jQuery.post( ajaxurl, data, function() {} );
	
	    	jQuery( '.ewd-ufaq-about-us-feature-suggestion' ).prepend( '<p>Thank you, your feature suggestion has been submitted.' );
	    }
	} );
});

//SETTINGS PREVIEW SCREENS

jQuery( document ).ready( function() {

	jQuery( '.ewd-ufaq-settings-preview' ).prevAll( 'h2' ).hide();
	jQuery( '.ewd-ufaq-settings-preview' ).prevAll( '.sap-tutorial-toggle' ).hide();
	jQuery( '.ewd-ufaq-settings-preview .sap-tutorial-toggle' ).hide();
});