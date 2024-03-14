jQuery(document).ready(function($){

		$('a.axScrollToTop').hide();

		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				$('.axScrollToTop').fadeIn();
			} else {
				$('.axScrollToTop').fadeOut();
			}
		});


		$('a.axScrollToTop').click(function(){
			$('html, body').animate({scrollTop:0}, 'slow');
			return false;
		});			


});