/*! clean testimonials ajax scripts */

jQuery( function($) {

	// Rotate through random testimonials
	cycleTestimonial = function( attach_to, ajax_source, context, word_limit ) {

		setInterval( function() {

			var testimonial = $('.single-testimonial.testimonial-' + attach_to);

			if (typeof context === 'undefined') {
				context = 'shortcode';
			}

			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: ajax_source,
				data: {
					action: 'get_random_testimonial', 
					context: context, 
					word_limit: word_limit
				},
				success: function(result) {

					$(result.data.markup).insertBefore(testimonial);

					testimonial.remove();
					
					attach_to = result.data.testimonial_id;
				}
			});

		}, 6000 );

	}

});
