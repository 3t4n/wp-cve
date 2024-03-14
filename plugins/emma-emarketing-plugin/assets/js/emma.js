jQuery(document).ready(function($) {
	
	$('.recaptcha-popup').on('click', function(e){
		
		// Fade out the pop up
		$(this).fadeOut(400, function(){
			$(this).addClass('hidden');
			
			// Reset the form submit button and form classes
			$('.activeForm').find('input.waiting').removeClass('waiting');
			$('.activeForm').removeClass('activeForm');
			
		});
	}).children().click(function(){return false;});
	
	// Watch the validation field to change, if it changes...probably a spam bot - disable the submit button
	$('input[name="validation"]').on( 'change', function(e){
		
		if ( $(this).val() !== '' ) {
			var thisForm = $(e.target).closest('#emma-subscription-form');
			var thisWrap = $(e.target).closest('.emma-wrap');
			var thisFormUnique = thisForm.attr('data-form-unique');
			
			$('#emma-form-submit-' + thisFormUnique).attr('disabled', 'disabled');
			
		} else {
			
			$('#emma-form-submit-' + thisFormUnique).removeAttr('disabled');
			
		}
		
	});
	
	$(document).on('click', '#emma-form input[name="emma_form_submit"]:not(.waiting)', function(e){
		//prevent the form from actually submitting and refreshing the page
		e.preventDefault();
		e.stopPropagation();
		
		var thisButton = $(e.target);
		
		// Make sure there's only one active form
		if ( $('.activeForm').length > 0 && typeof captchaExists !== "undefined" && ! thisButton.hasClass("passed-captcha"))
			return false;
		
		var thisForm = $(e.target).closest('#emma-subscription-form');
		var thisWrap = $(e.target).closest('.emma-wrap');
		var thisFormUnique = thisForm.attr('data-form-unique');
		
		thisForm.addClass('activeForm');
		
		// Maybe run some other code to handle our reCAPTCHAs
		if ( typeof captchaExists !== "undefined" ) {
			
			// See if we've passed the captcha yet. If not, show the popup, render the captcha
			if ( ! thisButton.hasClass("passed-captcha") ) {
				// We haven't passed our captcha yet
				thisButton.addClass("waiting");
				
				if ( $('#recaptcha-container-' + thisFormUnique).children().length == 0 ) {
					grecaptcha.render(
						'recaptcha-container-' + thisFormUnique,
						{
							"sitekey": recaptchaSiteKey,
							"callback": goodCaptcha,
						});
				}
				
				$('#recaptcha-popup-' + thisFormUnique + '.hidden').fadeIn(400, function(){
					$(this).removeClass("hidden");
				});
				
				return false;
			} else {
				
				// Hide the popup
				$("#recaptcha-popup-" + thisFormUnique ).addClass('hidden').hide();
			
			}
			
		}
		
		// If a status already exists, fade it out in sync with the form then remove it.
		if ($('.emma-status').length > 0) {
			$('.emma-status').fadeOut({
				duration:300, 
				queue: false, 
				complete: function(){
					$('.emma-status').remove();
				}
			});
		}
		
		// Fade out the form, show a little spinner thing
		thisForm.fadeOut({
			duration: 300,
			queue: false,
			complete: function(){
				// Show the WordPress default spinner
				$('<div class="spinner"></div>').prependTo(thisWrap).show();
				
				// Now let's submit the form via AJAX
				var data = {
					'action': 'emma_ajax_form_submit',
					'emma_email': $('#emma-subscription-form[data-form-unique="' + thisFormUnique + '"] input[name="emma_main_input"]').val(),
					'emma_firstname': $('#emma-subscription-form[data-form-unique="' + thisFormUnique + '"] input[name="emma_firstname"]').val(),
					'emma_lastname': $('#emma-subscription-form[data-form-unique="' + thisFormUnique + '"] input[name="emma_lastname"]').val(),
					'emma_signup_form_id': $('#emma-subscription-form[data-form-unique="' + thisFormUnique + '"] input[name="emma_signup_form_id"]').val(),
					'emma_send_confirmation': $('#emma-subscription-form[data-form-unique="' + thisFormUnique + '"] input[name="emma_send_confirmation"]').val(),
					'validation': $('#emma-subscription-form[data-form-unique="' + thisFormUnique + '"] input[name="validation"]').val(),
					'token': $('#emma-subscription-form[data-form-unique="' + thisFormUnique + '"] #g-recaptcha-response').val(),
				};
				
				jQuery.post(ajax_object.ajax_url, data, function(response) {
					var errorClass = '';
					var hasError = false;
					
					// Check for errors
					if ( response.data.code > 800) {
						errorClass = 'emma-alert';
						hasError = true;
						response.data.tracking_pixel = '<p style="display:none !important;">Error occured. No tracking pixel placed.</p>';
					} else {
						errorClass = '';
					}
					
					// Display the status
					thisWrap.prepend('<div class="emma-status ' + errorClass + '" style="display:none;">' + response.data.status_txt + '</div>' + response.data.tracking_pixel);
					
					// Show/Hide stuff
					$('.spinner').delay(800).fadeOut(300, function(){
						$('.spinner').remove();
						$('.emma-status').fadeIn(300);
						
						// If we have an error, we need to show the form again
						if (hasError == true) {
							thisForm.fadeIn(300);
						}
					});

				});
				
				thisForm.removeClass('activeForm');
			}
		});
	});
});