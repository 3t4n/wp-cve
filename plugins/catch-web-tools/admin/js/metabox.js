/**
 * Admin Metabox 
 * Metabox custom jquery functions
 */
jQuery( document ).ready( function() {
	var active = 0;
	if( jQuery.cookie( '#ui-tabs' ) ) {
		active = jQuery.cookie( '#ui-tabs' );
		jQuery.cookie( '#ui-tabs', null );
	}
	
	var tabs = jQuery( '#ui-tabs' ).tabs( { active: active } );
	
	
	if (jQuery('#catchwebtools_seo_title').length ) {
	var length	=	jQuery("#catchwebtools_seo_title").val().length;
			jQuery("#catchwebtools_seo_title_left").html(70-length);
		
		length	=	jQuery("#catchwebtools_seo_description").val().length;
			jQuery("#catchwebtools_seo_description_left").html(156-length);
		
		jQuery("#catchwebtools_seo_title").keypress (function(){
			var length	=	jQuery(this).val().length;
			jQuery("#catchwebtools_seo_title_left").html(70-length);
		});
		jQuery("#catchwebtools_seo_description").keypress (function(){
			var length	=	jQuery(this).val().length;
			jQuery("#catchwebtools_seo_description_left").html(156-length);
		});
	}
	
} );