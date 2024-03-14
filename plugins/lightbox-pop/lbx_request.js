jQuery(document).ready(function($) {
	
	var xyz_lbx_shortcode=0;
	
	if(jQuery("#xyz_lbx_shortcode").length>0)
	{	
		xyz_lbx_shortcode=1;
		
	}   
	
	var data = {
			action: 'xyz_lbx_action',
			xyz_lbx_shortcd:xyz_lbx_shortcode,
			xyz_lbx_pg:xyz_lbx_ajax_object.ispage,
			xyz_lbx_ps:xyz_lbx_ajax_object.ispost,
			xyz_lbx_hm:xyz_lbx_ajax_object.ishome  // Pass php values
	};
	// Pass the url value separately from ajaxurl for front end AJAX implementations
	jQuery.post(xyz_lbx_ajax_object.ajax_url, data, function(response) {
		if(xyz_lbx_shortcode==1)
		{
			if(response!=0)
			    jQuery("#xyz_lbx_shortcode").append(response);
		}	
		else
		{
			if(response!=0)
		        jQuery("#xyz_lbx_container").append(response);
		}
	});
});