jQuery(function($){
	// This is a temporary addition. Remove along with Main.php summer 2021
	$('#the-list').find('[data-plugin="order-tracking/Main.php"]').hide();

	var $deactivateLink = $('#the-list').find('[data-slug="order-tracking"] span.deactivate a'),
		$overlay        = $('#ewd-otp-deactivate-survey-order-tracking'),
		$form           = $overlay.find('form'),
		formOpen        = false;
	// Plugin listing table deactivate link.
	$deactivateLink.on('click', function(event) {
		event.preventDefault();
		$overlay.css('display', 'table');
		formOpen = true;
		$form.find('.ewd-otp-deactivate-survey-option:first-of-type input[type=radio]').focus();
	});
	// Survey radio option selected.
	$form.on('change', 'input[type=radio]', function(event) {
		event.preventDefault();
		$form.find('input[type=text], .error').hide();
		$form.find('.ewd-otp-deactivate-survey-option').removeClass('selected');
		$(this).closest('.ewd-otp-deactivate-survey-option').addClass('selected').find('input[type=text]').show();
	});
	// Survey Skip & Deactivate.
	$form.on('click', '.ewd-otp-deactivate-survey-deactivate', function(event) {
		event.preventDefault();
		location.href = $deactivateLink.attr('href');
	});
	// Survey submit.
	$form.submit(function(event) {
		event.preventDefault();
		if (! $form.find('input[type=radio]:checked').val()) {
			$form.find('.ewd-otp-deactivate-survey-footer').prepend('<span class="error">Please select an option below</span>');
			return;
		}
		var data = {
			code: $form.find('.selected input[type=radio]').val(),
			install_time: $form.data('installtime'),
			reason: $form.find('.selected .ewd-otp-deactivate-survey-option-reason').text(),
			details: $form.find('.selected input[type=text]').val(),
			site: ewd_otp_deactivation_data.site_url,
			plugin: 'Order Tracking'
		}
		var submitSurvey = $.post('https://www.etoilewebdesign.com/UPCP-Key-Check/Deactivation_Surveys.php', data);
		submitSurvey.always(function() {
			location.href = $deactivateLink.attr('href');
		});
	});
	// Exit key closes survey when open.
	$(document).keyup(function(event) {
		if (27 === event.keyCode && formOpen) {
			$overlay.hide();
			formOpen = false;
			$deactivateLink.focus();
		}
	});
});