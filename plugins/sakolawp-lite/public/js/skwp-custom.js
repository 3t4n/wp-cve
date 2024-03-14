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

	if ($('#class_holder_spe').length) {
		$('#class_holder_spe').on( 'change', function () {
			var skwpClassVal = $('#class_holder_spe').val();
			$.ajax({
				url: skwp_ajax_object.ajaxurl,
				type: 'POST',
				data: {
					action: 'sakolawp_select_section_spe',
					class_id: skwpClassVal
				},
				success: function(response) {
					$('#section_holder_spe').html(response);
				}
			});
		});
	}

	if ($('#section_holder.teacher-section').length) {
		$('#section_holder.teacher-section').on( 'change', function () {
			var skwpSectionVal = $('#section_holder').val(),
				skwpTeacherVal = $('#teacher_id_sel').val();
			$.ajax({
				url: skwp_ajax_object.ajaxurl,
				type: 'POST',
				data: {
					action: 'sakolawp_select_subject_teacher',
					section_id: skwpSectionVal,
					teacher_id: skwpTeacherVal
				},
				success: function(response) {
					$('#subject_holder').html(response);
				}
			});
		});
	}

	$('.skwp-menu-btn').click(function() {
		$(this).toggleClass("active");
		$('.sakolawp-navigation').toggleClass("open");
		$('.skwp-masking').toggleClass("open");
	});

})( jQuery );