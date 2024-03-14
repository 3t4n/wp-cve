/*SEO Manager admin js*/
jQuery(document).ready(function(){
	    jQuery(".wp_tracking_manager-tab").hide();
		jQuery("#div-wp_tracking_manager-general").show();
	    jQuery(".wp_tracking_manager-tab-links").click(function(){
		var divid=jQuery(this).attr("id");
		jQuery(".wp_tracking_manager-tab-links").removeClass("active");
		jQuery(".wp_tracking_manager-tab").hide();
		jQuery("#"+divid).addClass("active");
		jQuery("#div-"+divid).fadeIn();
		});
});
