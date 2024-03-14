/**
 * Scripts for GP Back To Top Plugin
 *
 * @author Giang Peter
 */ 

 /**
 * Scripts for GP Back To Top Plugin
 *
 * @author Giang Peter
 */ 

(function ($) {
	$(document).ready(function() {
		var element = '<div class="gp-back-to-top" id="gpToTop"><span></span></div>';
		$('body').append(element);

		var toTop = $('#gpToTop');
		$(window).on('scroll', function() {
			if ( $(window).scrollTop() > 250 ) {
				toTop.show();
			} else {
				toTop.hide();
			}
		});

		toTop.off('click').on('click',function() {
			$('html, body').animate({
				scrollTop: 0
			}, 1000);
		});
	});
})(jQuery);