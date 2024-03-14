jQuery(document).ready(function($) {
	// @var applyDefaultSettings modifies the back-end logic to render the "form-settings" partial
	function ajaxSaveForm(data) {
		jQuery.ajax({
			type: 'POST',
			url: window.ajaxurl,
			data: data,
			beforeSend: successMessageSaving,
			success: function (response) {
				successMessageSaved()

				if (data.hasOwnProperty('apply_default_settings')) {
					jQuery('#woo_image_seo_form').html(response)
				}
			},
			error: ajaxErrorHandle
		})
	}

	function successMessageSaving() {
		var $successMessage = jQuery('#post-success');

		$successMessage.text($successMessage.data('saving')).fadeIn()
	}

	function successMessageSaved() {
		var $successMessage = jQuery('#post-success');

		$successMessage.text($successMessage.data('saved')).addClass('bg-green')

		window.setTimeout(function () {
			$successMessage.fadeOut()
		}, 2000)
	}

	function ajaxErrorHandle(jqXhr, textStatus, errorThrown) {
		console.log(errorThrown)

		var $successMessage = jQuery('#post-success');

		$successMessage.text('ERROR!')

		window.setTimeout(function () {
			$successMessage.fadeOut()
		}, 2000)
	}

	function settingsFormSubmitHandle(event) {
		event.preventDefault()

		ajaxSaveForm(jQuery(this).serialize())
	}

	function settingsFormResetHandle() {
		var $resetSettingsButton = $(this);

		$resetSettingsButton.blur()

		if (!window.confirm($resetSettingsButton.data('confirm'))) {
			return
		}

		ajaxSaveForm({
			action: saveAction,
			_wpnonce: saveActionNonce,
			_wp_http_referer: saveActionReferer,
			apply_default_settings: true,
		})
	}

	function feedbackFormSubmitHandle(event) {
		event.preventDefault();

		if (!window.confirm("To provide better support, we will collect the following data:\n- The site's URL\n- The version of Woo Image SEO\n- The version of WooCommerce\nDo you agree?")) {
			return;
		}

		var $feedbackSubmit = jQuery(this).find('input[type="submit"]');
		var $feedbackFormBody = jQuery(this).find('.form__body');

		jQuery.ajax({
			type: 'POST',
			url: window.ajaxurl,
			data: jQuery(this).serialize(),
			beforeSend: function() {
				$feedbackSubmit.replaceWith(`<strong>${$feedbackSubmit.data('submitting')}</strong>`)
			},
			success: function() {
				$feedbackFormBody.html(`${$feedbackFormBody.data('sent')}<br>${$feedbackFormBody.data('thanks')}`)
			},
			error: ajaxErrorHandle
		})
	}

	function helpIconClickHandle(event) {
		event.preventDefault()
		event.stopPropagation()

		var $target = jQuery(this.hash)
		var $helpBackground = jQuery('#help-background')

		$helpBackground.fadeIn()
		$target.fadeIn()

		$helpBackground.click(function() {
			$helpBackground.fadeOut()
			$target.fadeOut()
		})
	}

	// if no option is selected, pick the empty option
	function validateAttributeBuilder() {
		jQuery('#woo_image_seo select[name*="[text]"]').each(function () {
			var $dropdown = jQuery(this);

			if ($dropdown.find('option[selected]').length === 0) {
				$dropdown.find('option[value="[none]"]').prop('selected', 'selected')
			}
		})
	}

	var saveAction = jQuery('#woo_image_seo_form [name="action"]').val();
	var saveActionNonce = jQuery('#woo_image_seo_form [name="_wpnonce"]').val();
	var saveActionReferer = jQuery('#woo_image_seo_form [name="_wp_http_referer"]').val();

	var $wrapper = jQuery('#woo_image_seo');

	// save settings on form submit
	$wrapper.on('submit', '#woo_image_seo_form', settingsFormSubmitHandle);

	// reset settings on button click
	$wrapper.on('click', '#reset-settings', settingsFormResetHandle);

	// submit feedback form
	$wrapper.on('submit', '#woo_image_seo_feedback', feedbackFormSubmitHandle);

	// show help modal on icon click
	$wrapper.on('click', 'a.dashicons-editor-help, a.help-trigger', helpIconClickHandle)

	// avoid bad attribute builder values
	$(window).on( 'load', validateAttributeBuilder)

	// add helper classes for better accessibility
	$('body').on('mousedown', function() {
		this.classList.add('no-focus');
	}).on('keydown', function() {
		this.classList.remove('no-focus');
	});
})
