"use strict";

var CountryJSON = {};
// JavaScript Document
jQuery(function($){
	
	jQuery( "#frm_order_status_report" ).submit(function( event ) {
		
		event.preventDefault();
		jQuery.ajax({			
			url:niwoocos_ajax_object.niwoocos_ajaxurl,
			method: "POST",
			data: $(this).serialize(),
			success:function(response){				
				jQuery("._ajax_content").html(response);
			
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		
		});
	});
	
	jQuery("#frm_order_status_report").trigger("submit");
	
});


