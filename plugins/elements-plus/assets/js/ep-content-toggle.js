(function($) {

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/ep-content-toggle-plus.default', function($scope) {
			
			$('.ep-ct-input').on('click', function(e) {
				var mainParent = $(this).closest('.ep-ct-outer-wrapper');
				$primary = mainParent.find('.ep-ct-primary');
				$secondary = mainParent.find('.ep-ct-secondary');
				if($(mainParent).find('input.ep-ct-input').is(':checked')) {
					$($primary).removeClass('active');
					$($secondary).addClass('active');
				} else {
					$($secondary).removeClass('active');
					$($primary).addClass('active');
				}	
			})

		});
	});
})( jQuery );