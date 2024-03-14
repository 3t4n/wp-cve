(function($) {
	$(document).ready(function() {
		/* InnerPage Slider */
		var gabfire_videoslider = $(".gabfire-videos");
		gabfire_videoslider.owlCarousel({
		  autoPlay: 999999,
		  pagination:true,
		  singleItem : true,
		  autoHeight : true,
		  mouseDrag: false,
		  touchDrag: false					  
		});	
		$(".gabfire-videos-next").click(function(){
			gabfire_videoslider.trigger('owl.next');
		});
		$(".gabfire-videos-prev").click(function(){
			gabfire_videoslider.trigger('owl.prev');
		});	
	});
})(jQuery);