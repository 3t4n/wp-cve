jQuery(document).ready(function($){
		$('a.axScrollToTop').click(function(){
			$('html, body').animate({scrollTop:0}, 'slow');
			return false;
		});			

});