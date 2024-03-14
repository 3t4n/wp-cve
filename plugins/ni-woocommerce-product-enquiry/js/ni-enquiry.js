// JavaScript Document
(function($) {
	$(".ni_enquiry_message").hide();
	
	$(".btn_ni_send").click(function(){
		ni_send_enquiry();
		return false;
    	//alert("The paragraph was clicked.");
	});
	
	//$(".btn_ni_enquiry").click(function(){
		//alert($(this).data('product-id'));
		//$("#ni_product_id").val($(this).data('product-id'));
		//return false;
    	//alert("The paragraph was clicked.");
	//});
	
	function ni_send_enquiry(){
		if(is_valid())
	  		jQuery( "#frm_hd_ni_enquiry" ).submit();
		else{
			jQuery(".ni_enquiry_message").show();	
			MessageAddClass(".ni_enquiry_message","alert-error");		
			 jQuery(".ni_enquiry_message").html("field marked with red are mandatory");		
			 }
	}
	function isEmail(email) {
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	  return regex.test(email);
	}
	function MessageAddClass(object_name, class_name){
		if (jQuery(object_name ).hasClass( "alert-error" ))
			jQuery( object_name ).removeClass( "alert-error" );
			
		if (jQuery(object_name ).hasClass( "alert-danger" ))
			jQuery( object_name ).removeClass( "alert-danger" );	
		
		if (jQuery(object_name ).hasClass("alert-success" ))
			jQuery( object_name ).removeClass( "alert-success" );
			
		if (jQuery(object_name ).hasClass("alert-info"))
			jQuery( object_name ).removeClass( "alert-info" );
			
			
		jQuery( object_name).addClass( class_name );	
	}
	function is_valid(){
		valid =true;
		
		/*Full Name*/
		if ( jQuery.trim( $( "#ni_full_name" ).val()) == "") {
			jQuery( "#ni_full_name" ).addClass( "error_message" );
			valid = false;
		}else{
			//hasClass
			if (jQuery("#ni_full_name" ).hasClass("error_message"))
			jQuery( "#ni_full_name" ).removeClass( "error_message" );
		}
		
		/*Email Address*/
		if ( jQuery.trim( $( "#ni_email_address" ).val()) == "") {
			jQuery( "#ni_email_address" ).addClass( "error_message" );
			valid = false;
		}else{
			
			if (!isEmail(jQuery.trim( $( "#ni_email_address" ).val()))) {
				jQuery( "#ni_email_address" ).addClass( "error_message" );
				valid = false;
			}else{
				//hasClass
				if (jQuery("#ni_email_address" ).hasClass("error_message"))
				jQuery( "#ni_email_address" ).removeClass( "error_message" );
			}
			
		}
		
		/*Contact Number*/
		if ( jQuery.trim( $( "#ni_contact_number" ).val()) == "") {
			jQuery( "#ni_contact_number" ).addClass( "error_message" );
			valid = false;
		}else{
			//hasClass
			if (jQuery("#ni_contact_number" ).hasClass("error_message"))
			jQuery( "#ni_contact_number" ).removeClass( "error_message" );
		}
		
		/*Enquiry Description*/
		/*
		if ( jQuery.trim( $( "#ni_enquiry_description" ).val()) == "") {
			jQuery( "#ni_enquiry_description" ).addClass( "error_message" );
			valid = false;
		}else{
			//hasClass
			if (jQuery("#ni_enquiry_description" ).hasClass("error_message"))
			jQuery( "#ni_enquiry_description" ).removeClass( "error_message" );
		}
		*/
		return valid;
	}
	$("#frm_hd_ni_enquiry").submit(function(e) {
		
		
		jQuery(".ni_enquiry_message").show();	
		MessageAddClass(".ni_enquiry_message","alert-info");	
		jQuery(".ni_enquiry_message").html("please wait..");		
		$.ajax({
			//url:ajax_object.ic_ajax_url,
			url:$("#ni_ajax_url").val(),
			data:$("#frm_hd_ni_enquiry").serialize(),
			success:function(data) {
				// This outputs the result of the ajax request
				//alert(data);
				console.log(data);
				var obj = JSON.parse(data);
				
				//alert(obj.status);
				if (obj.status=="SUCCESS") {
					//alert(obj.message);
					jQuery(".enquiry_table").hide();
				//	$(".ni_enquiry_message").fadeIn('slow').delay(5000).fadeOut('slow');
					jQuery(".ni_enquiry_message").html(obj.message);
					MessageAddClass(".ni_enquiry_message","alert-success");	
					
					setTimeout(function() { 
							$('[data-popup-close]').click();
						}, 3000);
					
					
					//alert("SUCCESS");
				 }
				 else if (obj.status=="FAIL") {
					 jQuery(".ni_enquiry_message").html("email not sending");
					MessageAddClass(".ni_enquiry_message","alert-danger");	
					
					//alert("SUCCESS");
					setTimeout(function() { 
							$('[data-popup-close]').click();
						}, 3000);
				 }
				 else{
					// alert("else");
				 	jQuery(".ni_enquiry_message").html(data);
					setTimeout(function() { 
							$('[data-popup-close]').click();
						}, 3000);
				 }
				//alert("s");
				//$(".ajax_content").html(data);
			},
			error: function(errorThrown){
				console.log(errorThrown);
				alert("e");
			}
		});
		 e.preventDefault(); // avoid to execute the actual submit of the form.
	});	  
	
	/*Popup JS Start */
	$('[data-popup-open]').on('click', function(e)  {
		$("#ni_variation_id").val($(".variation_id").val());
		var targeted_popup_class = jQuery(this).attr('data-popup-open');
		$('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);
		clear_form();
		e.preventDefault();
	});

	//----- CLOSE
	$('[data-popup-close]').on('click', function(e)  {
		var targeted_popup_class = jQuery(this).attr('data-popup-close');
		$('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);

		e.preventDefault();
	});
	/*Popup JS End*/
	function clear_form(){
		jQuery(".ni_enquiry_message").hide();
		jQuery.trim( $( "#ni_full_name" ).val(''));
		jQuery.trim( $( "#ni_email_address" ).val(''));
		jQuery.trim( $( "#ni_contact_number" ).val(''));
		jQuery.trim( $( "#ni_enquiry_description" ).val(''));
		if (jQuery("#ni_enquiry_description" ).hasClass("error_message"))
			jQuery(".error_message").removeClass( "error_message" );
		jQuery(".ni_enquiry_message").hide();		
		jQuery(".enquiry_table").show();
	}
	
	//btn_ni_enquiry
})( jQuery );


