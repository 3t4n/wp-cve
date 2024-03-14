/*
 *** This function is to get all the messages related to  contact form
	You need to pass form id and message key and this function will return contact form message.
 *** Since: 1.3.0
 *** @params:
			1) form id
			2) message key
 *** return: Contact form message
 */
function getContactFormMessage(formid,key){
	if(contactform == ""){
		return "";
	}
	else{
		for (var i=0; i<contactform.length; i++){
			var checkId = contactform[i][0];
			var checkKey = contactform[i][1];
			var returnMsg = contactform[i][2];

			if(formid == checkId && checkKey == key){
				return returnMsg;
				break;
			}
		}
	}
}

// Default validator function
function contactFormDefaultValidator(objForm){

	var formid=jQuery(objForm).find(".formid").val();
	var havingError = false;

	// Fetch each validation field one by one
	objForm.find('.wpcf7-validates-as-required').each(function(){
		jQuery(this).parent().find('.wpcf7-not-valid-tip').remove();
		// Check if empty or checkbox checked or not
		if(!jQuery(this).hasClass('wpcf7-checkbox')){
			// For GDPR
			if(jQuery(this).hasClass('wpcf7-wpgdprc')){
				var checkselected = 0;
				jQuery(this).find('input').each(function(){
					if(jQuery(this).prop('checked') == true){
						checkselected++;
					}
				});
				if(checkselected == 0){
					jQuery(this).parent().find('.wpcf7-not-valid-tip').remove();
					jQuery(this).after('<span class="wpcf7-not-valid-tip" role="alert">'+getContactFormMessage(formid,'gdpr')+'</span>');
					havingError = true;
				}
			}
			else if(!jQuery(this).val()){
				jQuery(this).val('');
				jQuery(this).parent().find('.wpcf7-not-valid-tip').remove();
				jQuery(this).after('<span class="wpcf7-not-valid-tip" role="alert">'+getContactFormMessage(formid,'invalid_required')+'</span>');
				havingError = true;
			}
			// Check if not valid email address entered
			else{
				if(jQuery(this).attr('class').indexOf("wpcf7-validates-as-email") >= 0){
					var emailField = jQuery(this).val();
					if(!validateCustomFormEmail(emailField)){
						jQuery(this).parent().find('.wpcf7-not-valid-tip').remove();
						jQuery(this).after('<span role="alert" class="wpcf7-not-valid-tip">'+getContactFormMessage(formid,'invalid_email')+'</span>');
						havingError = true;
					}
				}
				// Check if not valid url entered
				else if(jQuery(this).attr('class').indexOf("wpcf7-validates-as-url") >= 0){
					var urlField = jQuery(this).val();
					if(!validateCustomFormurl(urlField)){
						jQuery(this).parent().find('.wpcf7-not-valid-tip').remove();
						jQuery(this).after('<span role="alert" class="wpcf7-not-valid-tip">'+getContactFormMessage(formid,'invalid_url')+'</span>');
						havingError = true;
					}
				}
				// Check if not valid telephone entered
				else if(jQuery(this).attr('class').indexOf("wpcf7-validates-as-tel") >= 0){
					var telField = jQuery(this).val();
					if(!validateCustomFormtel(telField)){
						jQuery(this).parent().find('.wpcf7-not-valid-tip').remove();
						jQuery(this).after('<span role="alert" class="wpcf7-not-valid-tip">'+getContactFormMessage(formid,'invalid_tel')+'</span>');
						havingError = true;
					}
				}
				// Check if not valid number entered
				else if(jQuery(this).attr('class').indexOf("wpcf7-validates-as-number") >= 0){
					var numField = jQuery(this).val();
					var min = jQuery(this).attr('min');
					var max = jQuery(this).attr('max');
					var testnum = validateCustomFormnum(numField,min,max);
					if(testnum != 0){
						jQuery(this).parent().find('.wpcf7-not-valid-tip').remove();
						if(testnum ==1){
							jQuery(this).after('<span role="alert" class="wpcf7-not-valid-tip">'+getContactFormMessage(formid,'invalid_number')+'</span>');
						}
						if(testnum ==2){
							jQuery(this).after('<span role="alert" class="wpcf7-not-valid-tip">'+getContactFormMessage(formid,'invalid_too_long')+'</span>');
						}
						if(testnum ==3){
							jQuery(this).after('<span role="alert" class="wpcf7-not-valid-tip">'+getContactFormMessage(formid,'invalid_too_short')+'</span>');
						}
						havingError = true;
					}
				}
				// Check if not valid date entered
				else if(jQuery(this).attr('class').indexOf("wpcf7-validates-as-date") >= 0){
					var date = jQuery(this).val();
					if(!validateCustomFordate(date)){
						jQuery(this).parent().find('.wpcf7-not-valid-tip').remove();
						jQuery(this).after('<span role="alert" class="wpcf7-not-valid-tip">'+getContactFormMessage(formid,'invalid_date')+'</span>');
						havingError = true;
					}
				}
			}
		}
		else{
			var checkselected = 0;
			jQuery(this).find('input').each(function(){
				if(jQuery(this).prop('checked') == true){
					checkselected++;
				}
			});
			if(checkselected == 0){
				jQuery(this).parent().find('.wpcf7-not-valid-tip').remove();
				jQuery(this).after('<span class="wpcf7-not-valid-tip" role="alert">'+getContactFormMessage(formid,'invalid_required')+'</span>');
				havingError = true;
			}
		}
	});
	//for acceptance validation
	if(jQuery(objForm).find('.wpcf7-acceptance').length > 0){
		if(jQuery(objForm).find('.wpcf7-acceptance').hasClass('wpcf7-invert')){
			if(jQuery(objForm).find('.wpcf7-acceptance').prop('checked') == true){
				jQuery(objForm).find('.wpcf7-acceptance').parent().find('.wpcf7-not-valid-tip').remove();
				jQuery(objForm).find('.wpcf7-acceptance').after('<span class="wpcf7-not-valid-tip" role="alert">'+getContactFormMessage(formid,'accept_terms')+'</span>');
				havingError = true;
			}
			else{
				jQuery(objForm).find('.wpcf7-acceptance').parent().find('.wpcf7-not-valid-tip').remove();
			}
		}
		else{
			if(jQuery(objForm).find('.wpcf7-acceptance').prop('checked') == false){
				jQuery(objForm).find('.wpcf7-acceptance').parent().find('.wpcf7-not-valid-tip').remove();
				jQuery(objForm).find('.wpcf7-acceptance').after('<span class="wpcf7-not-valid-tip" role="alert">'+getContactFormMessage(formid,'accept_terms')+'</span>');
				havingError = true;
			}
			else{
				jQuery(objForm).find('.wpcf7-acceptance').parent().find('.wpcf7-not-valid-tip').remove();
			}
		}
	}

	return havingError;
}
//email validation function
function validateCustomFormEmail(email) {
	var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,10}|[0-9]{1,3})(\]?)$/;
	return expr.test(email);
}
//url validation function
function validateCustomFormurl(url) {
	if(url){
		return true;
	}
	else{
		return false;
	}
}
//telephone validation function
function validateCustomFormtel(number){
	var phoneno = /[a-zA-Z]/;
	if(number.match(phoneno)) {
		return false;
	}
	else {
		return true;
	}
}
//number filed validation function
function validateCustomFormnum(number,min,max){
	if (isNaN(number) ) {
		return 1;
	}
	else {
		if(min){
			if(number < min){
				return 3;
			}
		}
		if(max){
			if(number > max){
				return 2;
			}
		}
		return 0;
	}
}
//date filed validation function
function validateCustomFordate(input) {
	var status = false;
	if (!input || input.length <= 0) {
	  status = false;
	}
	else {
	  var result = new Date(input);
	  if (result == 'Invalid Date') {
		status = false;
	  }
	  else {
		status = true;
	  }
	}
	return status;
}