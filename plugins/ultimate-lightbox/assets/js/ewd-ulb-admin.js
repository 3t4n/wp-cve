jQuery(document).ready(function(){
    jQuery('.ewd-ulb-meta-menu-item').on('click', function() {
        var ID = jQuery(this).attr('id');
        var ID_Base = ID.substring(5);

        jQuery(".ewd-ulb-meta-body").each(function() {
            jQuery(this).addClass("ewd-ulb-hidden");
        });
        jQuery('#Body_'+ID_Base).removeClass("ewd-ulb-hidden");
    
        jQuery(".ewd-ulb-meta-menu-item").each(function() {
            jQuery(this).removeClass("meta-menu-tab-active");
        });
        jQuery(this).addClass("meta-menu-tab-active");
    });
});

// About Us Page
jQuery( document ).ready( function( $ ) {

	jQuery( '.ewd-ulb-about-us-tab-menu-item' ).on( 'click', function() {

		jQuery( '.ewd-ulb-about-us-tab-menu-item' ).removeClass( 'ewd-ulb-tab-selected' );
		jQuery( '.ewd-ulb-about-us-tab' ).addClass( 'ewd-ulb-hidden' );

		var tab = jQuery( this ).data( 'tab' );

		jQuery( this ).addClass( 'ewd-ulb-tab-selected' );
		jQuery( '.ewd-ulb-about-us-tab[data-tab="' + tab + '"]' ).removeClass( 'ewd-ulb-hidden' );
	} );

	jQuery( '.ewd-ulb-about-us-send-feature-suggestion' ).on( 'click', function() {

		var feature_suggestion = jQuery( '.ewd-ulb-about-us-feature-suggestion textarea' ).val();
		var email_address = jQuery( '.ewd-ulb-about-us-feature-suggestion input[name="feature_suggestion_email_address"]' ).val();
    
    	var params = {};

    	params.nonce  				= ewd_ulb_admin_php_data.nonce;
    	params.action 				= 'ewd_ulb_send_feature_suggestion';
    	params.feature_suggestion   = feature_suggestion;
    	params.email_address 		= email_address;

    	var data = jQuery.param( params );
    	jQuery.post( ajaxurl, data, function() {} );

    	jQuery( '.ewd-ulb-about-us-feature-suggestion' ).prepend( '<p>Thank you, your feature suggestion has been submitted.' );
	} );
} );
