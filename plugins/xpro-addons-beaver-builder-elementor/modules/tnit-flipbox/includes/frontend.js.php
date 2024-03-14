(function($) {
	var width = $(window).width(); 

	if ((width <= 991  )) {

		//horizontal flip
		$('.fl-node-<?php echo esc_attr( $id ); ?> .tnit-flip-horizontal').on('click', function(e) {
			e.stopPropagation();
			console.log('horizontal clicked');

			$(this).toggleClass('fliped-horizontal');
		});

		//vertical flip
		$('.fl-node-<?php echo esc_attr( $id ); ?> .tnit-flip-vertical').on('click', function(e) {
			e.stopPropagation();
			console.log('vertical clicked');

			$(this).toggleClass('flip-vertical');
		});

		//zoom in
		$('.fl-node-<?php echo esc_attr( $id ); ?> .tnit-flip-zoomIn').on('click', function(e) {
			e.stopPropagation();
			console.log('zoom in clicked');

			$(this).toggleClass('flip-zoomIn');
		});

		//skew
		$('.fl-node-<?php echo esc_attr( $id ); ?> .tnit-flip-skewUp').on('click', function(e) {
			e.stopPropagation();
			console.log('skew up clicked');

			$(this).toggleClass('flip-skewUp');
		});
	}

})(jQuery);
