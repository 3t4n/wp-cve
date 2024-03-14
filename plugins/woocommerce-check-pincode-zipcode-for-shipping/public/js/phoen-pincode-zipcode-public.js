(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

    jQuery(document).on('click', "#phoeniixx-pincode-button", function(event){
        event.preventDefault();
        
        const message   	= jQuery("#phoeniixx-pincode-message");
	    const file      	= jQuery("#phoeniixx-pincode-file");
        let getPincodeValue = jQuery("#phoeniixx-pincode-input").val();
        
        if(getPincodeValue == ''){
            message.empty().text(pincode.input_error);
            return false;
        }
        jQuery("#phoeniixx-pincode-button").prop('disabled', true);
        message.empty().text('Please Wait While A moment');
        jQuery.ajax({
            url         : phoeniixx_pincode_zipcode_ajax_check.ajaxurl,
            type        : 'post',
            dataType    : "json",
            data        : { 
                action  : 'phoeniixx_check_pincode_by_ajax', 
                nonce   : phoeniixx_pincode_zipcode_ajax_check.nonce,
                pincode : getPincodeValue 
            },
            success     : function(response){
                jQuery("#phoeniixx-pincode-message-display-3").hide();
                if(response.status){
                    file.empty().html(response.file);
                }else{
                    message.empty().text(pincode.pincode_error);
                }
                jQuery("#phoeniixx-pincode-button").prop('disabled', false);
            }
        });
    });	    
	
	jQuery(document).on('click', "#phoeniixx-pincode-change", function(event){
        event.preventDefault();
        jQuery("#phoeniixx-pincode-set-pincode-section").show();
        jQuery("#phoeniixx-pincode-show-pincode-section").hide();
    });

	jQuery(document).on('click','#click_delivery_help_text',function(){
    	jQuery("#show_delivery_help_text").fadeToggle(500);
    	jQuery("#show_cod_help_text").hide();
	});

	jQuery(document).on('click','#click_cod_help_text',function(){
    	jQuery("#show_delivery_help_text").hide();
    	jQuery("#show_cod_help_text").fadeToggle(500);
	});

})( jQuery );