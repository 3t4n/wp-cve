jQuery(document).ready(function(){
	jQuery("#ResponsiveContactForm").validate({						
		submitHandler: function(form) 
		{
		jQuery.ajax({
			type: "POST",			
			dataType: "json",	
			url:MyAjax, 
			data:{
				action: 'ai_action', 
				fdata : jQuery(document.formValidate).serialize()
			},
			success:function(response) {
				if(response == 1) {
					jQuery("#smsg").slideDown(function() {
						jQuery('html, body').animate({scrollTop: jQuery("#smsg").offset().top},'fast');
						jQuery(this).show().delay(8000).slideUp("fast")
						jQuery("#ResponsiveContactForm")[0].reset();
					});									
				} else if(response == 2) {											
					jQuery("#fmsg").slideDown(function() {
						jQuery(this).show().delay(8000).slideUp("fast")
					});
				} else {
					alert(response);
				}
			}
			});	
		}			
	});
});