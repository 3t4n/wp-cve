jQuery(document).ready(function($) {
	
			jQuery("input[type=checkbox].enotyswitch").each(function() {
				// Insert switch
				jQuery(this).before('<span class="enotyswitch"><span class="background" /><span class="easynotifymask" /></span>');
				 //Hide checkbox
				jQuery(this).hide();
				if (!jQuery(this)[0].checked) jQuery(this).prev().find(".background").css({left: "-49px"});
				if (jQuery(this)[0].checked) jQuery(this).prev().find(".background").css({left: "-2px"});	
			});
			// Toggle switch when clicked
			jQuery("span.enotyswitch").click(function() {
				// Slide switch off
				if (jQuery(this).next()[0].checked) {
					jQuery(this).find(".background").animate({left: "-49px"}, 200);
				// Slide switch on
				} else {
					jQuery(this).find(".background").animate({left: "-2px"}, 200);
				}
				// Toggle state of checkbox
				//jQuery('#').attr('checked', true);
				jQuery(this).next()[0].checked = !jQuery(this).next()[0].checked;
				
					if (jQuery("#enoty_cp_thumbsize").is(':checked')) {
					jQuery('#thumbsz').show("slow");
					} else {						
					jQuery('#thumbsz').hide("slow");
					}
					
					if (jQuery("#enoty_cp_bullet").is(':checked')) {
					jQuery('#customfileds').show("slow");
					} else {						
					jQuery('#customfileds').hide("slow");
					}					
					
																
			});
			
});