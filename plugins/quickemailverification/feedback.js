jQuery(document).ready(function($) {
	$('#qev-feedback-modal').dialog({
		title: 'Quick Feedback',
		autoOpen: false,
		draggable: false,
		width: 'auto',
		modal: true,
		resizable: false,
		closeOnEscape: false,
		position: {
			my: 'center',
			at: 'center',
			of: window
		},
				
		open: function() {
			$('.ui-widget-overlay').bind('click', function() {
				$('#qev-feedback-modal').dialog('close');
			});
		},
			
		create: function() {
			$('.ui-dialog-titlebar-close').addClass('ui-button');
		},
	});

	$('.deactivate a').each(function(i, ele) {
		if ($(ele).attr('href').indexOf('quickemailverification') > -1) {

			$('#qev-feedback-modal').find('a').attr('href', $(ele).attr('href'));

			$(ele).on('click', function(e) {
				e.preventDefault();

				$('#qev-feedback-response').html('');
				$('#qev-feedback-modal').dialog('open');
			});

			$('input[name="qev-feedback"]').on('change', function(e) {
				if($(this).val() == 4) {
					$('#qev-feedback-other').show();
				} else {
					$('#qev-feedback-other').hide();
				}
			});

			$('#qev-submit-feedback-button').on('click', function(e) {
				e.preventDefault();

				$('#qev-feedback-response').html('');

				if (!$('input[name="qev-feedback"]:checked').length) {
					$('#qev-feedback-response').html('<div style="color:#cc0033;font-weight:800">Please select your feedback.</div>');
				} else {
					$(this).val('Loading...');
					$.post(ajaxurl, {
						action: 'qev_submit_feedback',
						feedback: $('input[name="qev-feedback"]:checked').val(),
						others: $('#qev-feedback-other').val(),
					}, function(response) {
						window.location = $(ele).attr('href');
					}).always(function() {
						window.location = $(ele).attr('href');
					});
				}
			});
		}
	});
});

