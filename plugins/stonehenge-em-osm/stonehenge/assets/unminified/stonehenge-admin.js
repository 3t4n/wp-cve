jQuery.noConflict();
// Errors.
function showErrors() {
	if( jQuery('.parsley-errors-list').length > 0) {
		jQuery('.listFieldError').html("<span class='stonehenge-error'>" + CORE.showError + "</span>").show();
		jQuery('.stonehenge-section').each( function() {
			var inputs = jQuery(this).find(':input');
			if( inputs.length > 0 ) { jQuery(this).show(); }
		});
	}
}

(function($) {
$(document).ready(function(){
// Options Page.
	if( $('.settings-error').length > 0 ) {
		$('.settings-error').delay(3000).fadeOut(400);
	}

	var Sections = $('.stonehenge-metabox').length;
	if( CORE.is_ready && Sections > 1 ) {
		$('.stonehenge-section').hide();
	}

	$('h3.handle').click( function() {
		$(this).closest('div').next('.stonehenge-section').slideToggle(250);
	});

// Input Fields.

	// Repeatable divs.
	$(".button-add").attr('title', CORE.add);
	$(".button-remove").attr('title', CORE.remove);
	$(".button-edit").attr('title', CORE.edit);
	$(document.body).on('click', '.button-remove', function() {
    	$(this).closest('tr').remove();
	});

	// Datepicker.
	if( $('.pickadate').length > 0 ) {
		var dateOptions = {
			dateFormat: CORE.dateFormat,
			dayNames: CORE.daysFull,
			dayNamesMin: CORE.daysShort,
			monthNames: CORE.monthsFull,
			monthNamesShort: CORE.monthsShort,
		 	nextText: CORE.next,
			prevText: CORE.previous,
			changeMonth: true,
			changeYear: true,
			yearRange: CORE.yearRange,
			defaultDate: new Date(),
			minDate: '-7d',
		};
		$('.pickadate').datepicker(dateOptions);
	}

	// Time Picker.
	if( $('.pickatime').length > 0 ) {
		var timeOptions = {
			'timeFormat': CORE.time_format,
			'step' : 30,
			'minTime': '07:00',
			'maxTime': '22:00',
			'forceRoundTime': false,
			'useSelect': false,
		};
		$('.pickatime').timepicker(timeOptions);
	}

	// Color Picker
	$('.pickcolor').click(function(e) {
		colorPicker = jQuery(this).next('div');
		input = jQuery(this).prev('input');
		$.farbtastic($(colorPicker), function(a) { $(input).val(a).css('background', a); });
		colorPicker.show();
		e.preventDefault();
		$(document).mousedown( function() { $(colorPicker).hide(); });
	});

	// Attachments
	if( $('.file-button').length > 0 ) {
		$('.file-button').click( function(e) {
			e.preventDefault();
			Destination 	= $(this).prev('.filename');
			mediaUploader 	= wp.media.frames.file_frame = wp.media({
				title: CORE.chooseFile,
				button:	{
					text: CORE.chooseFile
				},
				multiple: false,
			});

			mediaUploader.on('select', function() {
				Attachment = mediaUploader.state().get('selection').first().toJSON();
				Destination.val( Attachment.url );
			});
			mediaUploader.open();
		});
		$('.clear-file').click(function() {
			$(this).prevAll().val('');
		});
	}

	// Allow TinyMCE in Option Pages.
	$('#stonehenge-options-form').on('submit', function() {
		tinymce.triggerSave();
	});

	// Submit Form.
	$('#stonehenge_form').on('submit', function(e) {
		tinymce.triggerSave();
		e.preventDefault();
		$(this).parsley().validate();
		if( $(this).parsley().isValid() ) {
			var $form = $(this);
			formData = new FormData($(this)[0]);
			$.ajax({
				url : $form.attr('action'),
				type: 'POST',
				data: formData,
				async: false,
				beforeSend: function() {
					$('#loader').show();
					$('#stonehenge_form_fields').hide(500);
				},
				success: function(result) {
					$('#loader').hide();
					$('#stonehenge_form_result').html( result );
				},
				cache: false,
				contentType: false,
				processData: false
			});
		}
	});

	// Submit Email.
	$('#stonehenge_mailer_form').on('submit', function(e) {
		tinymce.triggerSave();
		e.preventDefault();
		$(this).parsley().validate();
		if( $(this).parsley().isValid() ) {
			var $form = $(this);
			formData = new FormData($(this)[0]);
			$.ajax({
				url : $form.attr('action'),
				type: 'POST',
				data: formData,
				async: false,
				beforeSend: function() {
					$('#loader').show();
					$('#stonehenge_mailer_fields').hide(500);
				},
				success: function(result) {
					$('#loader').hide();
					$('#stonehenge_mailer_result').html( result );
				},
				cache: false,
				contentType: false,
				processData: false
			});
		}
	});
});
})
(jQuery);
