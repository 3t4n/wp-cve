jQuery.noConflict();
(function($) {
$(document).ready(function(){

	// Submit Form.
	$('#stonehenge_form').on('submit', function(e) {
		e.preventDefault();
		if( $(this).parsley().isValid() ) {
			var $form = $(this);
			$('#stonehenge_form_fields').hide();
			$('#loader').show();
			$.post($form.attr('action'), $form.serialize(), function(data) {
				$('#loader').hide();
				$(data).insertAfter('#stonehenge_form_result');
			});
		}
	});

	// Submit Email.
	$('#stonehenge_mailer_form').on('submit', function(e) {
		e.preventDefault();
		if( $(this).parsley().isValid() ) {
			var $form = $(this);
			$('#stonehenge_mailer_fields').hide();
			$('#loader').show();
			$.post($form.attr('action'), $form.serialize(), function(data) {
				$('#loader').hide();
				$(data).insertAfter('#stonehenge_mailer_result');
			});
		}
	});

});
})
(jQuery);
