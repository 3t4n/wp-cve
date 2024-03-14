(function( $ ) {
	'use strict';

	if ($('#class_holder').length) {
		$('#class_holder').on( 'change', function () {
			var skwpClassVal = $('#class_holder').val();
			$.ajax({
				url: skwp_ajax_object.ajaxurl,
				type: 'POST',
				data: {
					action: 'sakolawp_select_section',
					class_id: skwpClassVal
				},
				success: function(response) {
					$('#section_holder').html(response);
				}
			});
		});
	}

	if ($('#section_holder').length) {
		$('#section_holder').on( 'change', function () {
			var skwpSectionVal = $('#section_holder').val();
			$.ajax({
				url: skwp_ajax_object.ajaxurl,
				type: 'POST',
				data: {
					action: 'sakolawp_select_subject',
					section_id: skwpSectionVal
				},
				success: function(response) {
					$('#subject_holder').html(response);
				}
			});
		});
	}

	if ($('#section_holder.first').length) {
		var skwpClass = $("#class_id").val();
		var skwpSubject = $("#subject_id").val();
		$.ajax({
			url: skwp_ajax_object.ajaxurl,
			type: 'POST',
			data: {
				action: 'sakolawp_select_section_first',
				class_id: skwpClass,
				subject_id: skwpSubject,
			},
			success: function(response) {
				$('#section_holder').html(response);
			}
		});
	}

})( jQuery );