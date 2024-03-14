/* trackship_snackbar jquery */
(function( $ ){
	$.fn.trackship_snackbar = function(msg) {
		if ( jQuery('.snackbar-logs').length === 0 ){
			$("body").append("<section class=snackbar-logs></section>");
		}
		var trackship_snackbar = $("<article></article>").addClass('snackbar-log snackbar-log-success snackbar-log-show').text( msg );
		$(".snackbar-logs").empty();
		$(".snackbar-logs").append(trackship_snackbar);
		setTimeout(function(){ trackship_snackbar.remove(); }, 3000);
		return this;
	}; 
})( jQuery );

/*ajax call for settings tab form save*/
jQuery(document).on("click", ".zorem_plugin_setting_tab_form .woocommerce-save-button", function(){
	'use strict';
	save_sms_settings();
	return false;
});

/*ajax call for settings tab form toggle*/
jQuery(document).on("change", ".shipment-status-sms-section .tgl-flat", function(){
	'use strict';
	save_sms_settings();
});

function save_sms_settings(){
	'use strict';
	var form = jQuery( '.zorem_plugin_setting_tab_form' );
	form.find(".spinner").addClass("active");
	jQuery.ajax({
		url: ajaxurl,
		data: form.serialize(),
		type: 'POST',
		dataType:"json",
		success: function(response) {
			form.find(".spinner").removeClass("active");
			jQuery( '.smswoo-top.smswoo-open .smswoo-top-click' ).trigger('click');
			if ( response.success == 'false' ) {
				jQuery(document).trackship_snackbar_warning( response.message );
			} else {
				jQuery(document).trackship_snackbar( response.message );
			}
			jQuery( '.heading_panel' ).removeClass( 'active' );
			jQuery( '.heading_panel' ).siblings( '.panel_content' ).removeClass('active').slideUp( 'slow' );
			jQuery( '.heading_panel' ).find('span.dashicons').addClass('dashicons-arrow-right-alt2');
			jQuery( '.heading_panel' ).find('button.button-primary').hide();
		},
		error: function(response) {
			console.log(response);
		}
	});
	return false;
}

/*ajex call for general tab form save*/	 
jQuery(document).on("change", "#all-shipment-status-sms-delivered", function(){
	"use strict";
	
	if(jQuery(this).prop("checked") == true){
		var checked = 1;
	} else {
		var checked = 0;
	}
	var ajax_data = {
		action: 'update_all_shipment_status_sms_delivered',
		sms_delivered: checked,
		security: jQuery( '#delivered_sms' ).val()
	};
	
	jQuery.ajax({
		url: ajaxurl,
		data: ajax_data,
		type: 'POST',
		success: function(response) {
			jQuery(document).trackship_snackbar( trackship_script.i18n.data_saved );
		},
		error: function(response) {
			console.log(response);
			jQuery(document).trackship_snackbar_warning( trackship_script.i18n.data_saved );
		}
	});
	return false;
});

/** show/ hide event **/
jQuery(document).on( "click", ".shipment-status-sms-section .smswoo-top-click", function(){
	'use strict';
	var smswootop = jQuery(this).parents(".smswoo-top");
	var smswoobottom = smswootop.siblings(".smswoo-bottom");
	var smssavebtn = smswootop.find(".button-smswoo");
	var smstgl = smswootop.find(".smswoo-inlineblock");
	var smscustomizebtn = smswootop.find(".smswoo-shipment-sendto-customer");
	
	if ( smswootop.hasClass( 'smswoo-open' ) ) {
	} else {
		jQuery(".smswoo-bottom").slideUp(400);
	}
	jQuery(".button-smswoo").hide();
	jQuery(".smswoo-shipment-sendto-customer").show();
	jQuery(".smswoo-top").removeClass('smswoo-open');
	jQuery(".smswoo-inlineblock").show();
	
	smswoobottom.slideToggle( 400, "swing", function(){
		if( smswoobottom.is(":visible") ){
			smswootop.addClass('smswoo-open');
			smssavebtn.show();
			smscustomizebtn.hide();
			smstgl.hide();
		} else {
			smswootop.removeClass('smswoo-open');
			smssavebtn.hide();
			smscustomizebtn.show();
			smstgl.show();
		}
	});
});

jQuery(document).on( "change", ".smswoo-checkbox", function(){
	'use strict';
	if( jQuery(this).prop("checked") === true ){
		jQuery(this).closest('.smswoo-row').removeClass('disable_row');
	} else {
		jQuery(this).closest('.smswoo-row').addClass('disable_row');
	}
});

jQuery(document).on( "change", ".smswoo-shipment-checkbox", function(){
	'use strict';
	var row_class = jQuery(this).data( "row_class" );
	
	if( jQuery(this).prop("checked") === true ){
		jQuery(this).closest('.smswoo-row').addClass( row_class );
	} else {
		jQuery(this).closest('.smswoo-row').removeClass( row_class );
	}
});

function copyToClipboard(text) {
	var $temp = jQuery("<input>");
	jQuery("body").append($temp);
	$temp.val(text).select();
	document.execCommand("copy");
	$temp.remove();
}

jQuery(document).on( "click", ".shipment-status-sms-section .clipboard", function(){
	'use strict';
	var clipboard_text = jQuery(this).data( "clipboard-text" );
	copyToClipboard( clipboard_text );
	
	jQuery(".clipboard").removeClass("active");
	jQuery(this).addClass("active");
	
	jQuery(document).trackship_snackbar( clipboard_text + ' is copied to clipboard.' );
});

jQuery(document).on( "change", "#smswoo_sms_provider", function(){
	'use strict';
	var provider = jQuery('#smswoo_sms_provider').val();

	if ( provider === 'smswoo_msg91' && jQuery('#smswoo_msg91_dlt').prop("checked") ) {
		jQuery('.shipment-status-sms-section .smswoo-textarea').hide();
		jQuery('.shipment-status-sms-section .smswoo-text').show();
	} else {
		jQuery('.shipment-status-sms-section .smswoo-textarea').show();
		jQuery('.shipment-status-sms-section .smswoo-text').hide();
	}
});

jQuery(document).on( "change", "#smswoo_msg91_dlt", function(){
	'use strict';
	var provider = jQuery('#smswoo_sms_provider').val();
	
	if ( provider === 'smswoo_msg91' && jQuery('#smswoo_msg91_dlt').prop("checked") ) {
		jQuery('.shipment-status-sms-section .smswoo-textarea').hide();
		jQuery('.shipment-status-sms-section .smswoo-text').show();
	} else {
		jQuery('.shipment-status-sms-section .smswoo-textarea').show();
		jQuery('.shipment-status-sms-section .smswoo-text').hide();
	}
});

jQuery(document).ready(function () {
	jQuery("#smswoo_sms_provider").trigger('change');
});
