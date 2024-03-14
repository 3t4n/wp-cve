// JavaScript Document
jQuery(function($){
	$( "#frm_cog_report" ).submit(function( e ) {
		$(".ajax_cog_content").html("please wait..");
		
		$.ajax({
			
			url:ni_cog_ajax_object.ni_cog_ajax_object_ajaxurl,
			data: $("#frm_cog_report").serialize(),
			success:function(response) {
				
				$(".ajax_cog_content").html(response);
			},
			error: function(response){
				console.log(response);
				//alert("e");
			}
		}); 
		e.preventDefault();
	});
	
	$( "#frm_cog_report" ).trigger( "submit" );
	
});