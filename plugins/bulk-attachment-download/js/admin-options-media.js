// Media settings jquery
jQuery(document).ready(function($) {

	// On click of show / hide link, toggle link and guidance display
	$('body').on( 'click', '.jabd-guidance-link', function() {
		toggleGuidanceDisplay();
	});
	
	function toggleGuidanceDisplay() {
		
		$(".jabd-guidance-link").each( function() {
			if ( $(this).css('display') == 'none' ) {
				$(this).css('display', 'inline');
			} else {
				$(this).css('display', 'none');
			}
		});
		
		if ( $('.jabd-guidance').css('display') == 'none' ) {
			$('.jabd-guidance').css('display', 'block');
		} else {
			$('.jabd-guidance').css('display', 'none');
		}
		
	}
	
});