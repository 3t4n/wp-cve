;(function($){
	$(document).ready(function(){
		$('.mgpd-dismiss').on('click',function(){
			var url = new URL(location.href);
			url.searchParams.append('dismissed',1);
			location.href= url;
		});
		$('.mgpd-revdismiss').on('click',function(){
			var url = new URL(location.href);
			url.searchParams.append('revadded',1);
			location.href= url;
		});
	});
})(jQuery);