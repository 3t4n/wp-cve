/* Advanced Security Form 7 admin js*/
jQuery(document).ready(function(){
		jQuery(".cf7as-tab").hide();
		jQuery("#div-cf7as-general").show();
	    jQuery(".cf7as-tab-links").click(function(){
		var divid=jQuery(this).attr("id");
		jQuery(".cf7as-tab-links").removeClass("active");
		jQuery(".cf7as-tab").hide();
		jQuery("#"+divid).addClass("active");
		jQuery("#div-"+divid).fadeIn();
		});
})
