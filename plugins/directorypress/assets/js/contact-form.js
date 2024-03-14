// AJAX Contact form
(function($) {
	$(document).on('submit', '#directorypress_contact_form, #directorypress_report_form', function(e) {
		e.preventDefault();
	    var $this = $(this);
		if ($this.attr('id') == 'directorypress_contact_form') {
			var type = 'contact';
		} else if ($this.attr('id') == 'directorypress_report_form') {
			var type = 'report';
		}
		var warning = $this.find('#'+type+'_warning');
		var listing_id = $this.find('#'+type+'_listing_id');
		var nonce = $this.find('#'+type+'_nonce');
		var name = $this.find('#'+type+'_name');
		var email = $this.find('#'+type+'_email');
		var message = $this.find('#'+type+'_message');
		var button = $this.find('.directorypress-send-message-button');
		var recaptcha = ($this.find('.g-recaptcha-response').length ? $this.find('.g-recaptcha-response').val() : '');
		
	    $this.css('opacity', '0.5');
		warning.hide();
		button.val(directorypress_js_instance.send_button_sending).attr('disabled', 'disabled');

	    var data = {
				action: "directorypress_contact_form",
				type: type,
				listing_id: listing_id.val(),
				name: name.val(),
				email: email.val(),
				message: message.val(),
				security: nonce.val(),
				'g-recaptcha-response': recaptcha
		};

	    $.ajax({
				url: directorypress_js_instance.ajaxurl,
				type: "POST",
				dataType: "json",
				data: data,
				global: false,
				success: function(response_from_the_action_function) {
					if (response_from_the_action_function != 0) {
						if (response_from_the_action_function.error == '') {
							name.val(''),
							email.val(''),
							message.val(''),
							warning.html(response_from_the_action_function.success).show();
						} else {
							var error;
							if (typeof response_from_the_action_function.error == 'object') {
								error = '<ul>';
								$.each(response_from_the_action_function.error, function(key, value) {
									error = error + '<li>' + value + '</li>';
								});
	            				error = error + '</ul>';
	            			} else {
	            				error = response_from_the_action_function.error;
	            			}
							warning.html(error).show();
	            		}
	            		$this.css('opacity', '1');
	            		button.val(directorypress_js_instance.send_button_text).removeAttr('disabled');
					}
				}
		});
	});
})(jQuery);