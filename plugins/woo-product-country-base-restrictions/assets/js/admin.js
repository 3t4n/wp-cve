/*header script*/
jQuery( document ).on( "click", "#activity-panel-tab-help", function(e) {
	e.preventDefault(); // stops link from making page jump to the top
	e.stopPropagation(); // when you click the button, it stops the page from seeing it as clicking the body too
	jQuery(this).addClass( 'is-active' );
	jQuery( '.woocommerce-layout__activity-panel-wrapper' ).addClass( 'is-open is-switching' );
});

jQuery( document ).on( "click", ".woocommerce-layout__activity-panel-wrapper", function(e) {	
	e.stopPropagation(); // when you click the button, it stops the page from seeing it as clicking the body too	
});

jQuery( document ).on( "click", "body", function() {	
	jQuery('#activity-panel-tab-help').removeClass( 'is-active' );
	jQuery( '.woocommerce-layout__activity-panel-wrapper' ).removeClass( 'is-open is-switching' );
});
/*header script end*/ 


/* cbr_snackbar jquery */
(function( $ ){
	$.fn.cbr_snackbar = function(msg) {
		if ( jQuery('.snackbar-logs').length === 0 ){
			$("body").append("<section class=snackbar-logs></section>");
		}
		var cbr_snackbar = $("<article></article>").addClass('snackbar-log snackbar-log-success snackbar-log-show').text( msg );
		$(".snackbar-logs").append(cbr_snackbar);
		setTimeout(function(){ cbr_snackbar.remove(); }, 3000);
		return this;
	}; 
})( jQuery );

/* cbr_snackbar_warning jquery */
(function( $ ){
	$.fn.cbr_snackbar_warning = function(msg) {
		if ( jQuery('.snackbar-logs').length === 0 ){
			$("body").append("<section class=snackbar-logs></section>");
		}
		var cbr_snackbar_warning = $("<article></article>").addClass( 'snackbar-log snackbar-log-error snackbar-log-show' ).html( msg );
		$(".snackbar-logs").append(cbr_snackbar_warning);
		setTimeout(function(){ cbr_snackbar_warning.remove(); }, 3000);
		return this;
	}; 
})( jQuery );

jQuery(document).ready(function(){
	"use strict";
	jQuery(".tipTip").tipTip();
	jQuery("#wpcbr_choose_the_page_to_redirect").select2();
	jQuery('#cbrw_border_color, #cbrw_background_color, #cbrw_font_color, #cbrwl_box_background_color, #cbrwl_background_color').wpColorPicker();
	
	jQuery('.product_visibility:checked').parent().find('span').css("color", "#00ab6f");
	jQuery('.product_visibility:checked').parent().parent().parent().parent().parent().css("background", "#fff");
	
	jQuery('#wpcbr_choose_the_page_to_redirect').parent().parent().parent().hide();
	if( jQuery("#wpcbr_redirect_404_page").is(":checked") === true ){
		jQuery('#wpcbr_choose_the_page_to_redirect').parent().parent().parent().show();
	}
	
	jQuery('#wpcbr_hide_product_price1').parent().parent().parent().parent().hide();
	if( jQuery("#wpcbr_make_non_purchasable1").is(":checked") === true ){
		jQuery('#wpcbr_hide_product_price1').parent().parent().parent().parent().show();
	}
	
	jQuery("#wpcbr_message_position").parent().addClass('hidden-desc');
	if( jQuery("#wpcbr_message_position").val() === "custom_shortcode"){
		jQuery("#wpcbr_message_position").parent().removeClass('hidden-desc');
	}
	var restriction_type = jQuery(".cbr_restricted_type").find(":selected").val();
	if( restriction_type === 'all' ){
		jQuery(".restricted_countries").hide();
	}
	
});

jQuery(document).on("click", ".accordion", function(){
	"use strict";
		
	if (jQuery(this).next('.panel').hasClass('active')) {
		//
		jQuery(".accordion").css('border-color','');
		jQuery(".accordion").removeClass('active');
		jQuery(".accordion").next('.panel').removeClass('active').slideUp("slow");
		jQuery(".accordion").css('cursor', '');
		jQuery(".accordion").find('span.cbr-btn').hide();
		jQuery(".accordion").find('span.dashicons').addClass('dashicons-arrow-right-alt2');
		jQuery(".accordion").find('label').css('color','');
	} else {
		jQuery(".accordion").css('border-color','');
		jQuery(".accordion").removeClass('active');
		jQuery(".accordion").next('.panel').removeClass('active').slideUp("slow");
		jQuery(".accordion").css('cursor', '');
		jQuery(".accordion").find('span.cbr-btn').hide();
		jQuery(".accordion").find('span.dashicons').addClass('dashicons-arrow-right-alt2');
		jQuery(".accordion").find('label').css('color','');
		jQuery(this).addClass('active');
		jQuery(this).css('cursor', 'default');
		jQuery(this).find('span.cbr-btn').show();
		jQuery(this).find('span.dashicons').removeClass('dashicons-arrow-right-alt2');
		jQuery(this).find('label').css('color','#212121');
		jQuery(this).next('.panel').addClass('active').slideDown( 'slow', function() {
			var visible = jQuery(this).isInViewport();
			if ( !visible ) {
				jQuery('html, body').animate({
					scrollTop: jQuery(this).prev().offset().top - 35
				}, 1000);	
			}			
		} );
	}
});

(function( $ ){
	$.fn.isInViewport = function( element ) {
		var win = $(window);
		var viewport = {
			top : win.scrollTop()			
		};
		viewport.bottom = viewport.top + win.height();
		
		var bounds = this.offset();		
		bounds.bottom = bounds.top + this.outerHeight();

		if( bounds.top >= 0 && bounds.bottom <= window.innerHeight) {
			return true;
		} else {
			return false;	
		}		
	};
})( jQuery );

jQuery(document).on("click", ".catelog_visibility", function(){
	"use strict";
	
	var hasClass = jQuery(this).parent().hasClass("hide-child-panel");
	
	if(hasClass === true ){
		jQuery(".catelog_visibility").parent().addClass("hide-child-panel");
		jQuery(".catelog_visibility").find('span').css("color", "#bdbdbd");
		jQuery('.catelog_visibility').css('background','');
		jQuery(this).parent().removeClass("hide-child-panel");
		jQuery(this).find('input.product_visibility').trigger("click");
		jQuery(this).css('background','#fff');
		jQuery(this).find('span').css("color", "#00ab6f");
	}
});


jQuery(document).on("change", "#wpcbr_message_position", function(){
	"use strict";
	jQuery(this).parent().addClass('hidden-desc');
	if( jQuery(this).val() === "custom_shortcode"){
		jQuery(this).parent().removeClass('hidden-desc');
	}
});

jQuery(document).on("change", "#wpcbr_make_non_purchasable1", function(){
	"use strict";
	jQuery('#wpcbr_hide_product_price1').parent().parent().parent().parent().hide();
	if( jQuery(this).is(":checked") === true){
		jQuery('#wpcbr_hide_product_price1').parent().parent().parent().parent().show();
	}
	
});
jQuery(document).on("change", "#wpcbr_redirect_404_page", function(){
	"use strict";
	jQuery('#wpcbr_choose_the_page_to_redirect').parent().parent().parent().hide();
	if( jQuery(this).is(":checked") === true){
		jQuery('#wpcbr_choose_the_page_to_redirect').parent().parent().parent().show();
	}
	
});
jQuery(document).on("change", ".cbr_restricted_type", function(){
	"use strict";
	if( jQuery(this).find(":selected").val() === 'specific' || jQuery(this).find(":selected").val() === 'excluded'){
		jQuery(".restricted_countries").show();
	}
	if(jQuery(this).find(":selected").val() === 'all' ){
		jQuery(".restricted_countries").hide();
	}
});

/*ajex call for general tab form save*/	
jQuery(document).on("click", "#cbr_setting_tab_form .cbr-save", function(){
	"use strict";
	jQuery(this).parent().find(".spinner").addClass("active");
	var form = jQuery('#cbr_setting_tab_form');
	jQuery.ajax({
		url: ajaxurl+"?action=cbr_setting_form_update",//csv_workflow_update,		
		data: form.serialize(),
		type: 'POST',
		dataType:"json",	
		success: function(response) {
			if( response.success === "true" ){
				jQuery("#cbr_setting_tab_form .spinner").removeClass("active");
				jQuery(document).cbr_snackbar( "Settings Successfully Saved." );
			} else {
				//show error on front
			}
		},
		error: function(response) {
			console.log(response);			
		}
	});
	return false;
});

jQuery(document).on("click", ".cbr_tab_input", function(){
	"use strict";
	var tab = jQuery(this).data('tab');
	var label = jQuery(this).data('label');
	jQuery('.zorem-layout__header .breadcums_page_heading').text(label);
	var url = window.location.protocol + "//" + window.location.host + window.location.pathname+"?page=woocommerce-product-country-base-restrictions&tab="+tab;
	window.history.pushState({path:url},'',url);
	jQuery(window).trigger('resize');	
});