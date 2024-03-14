/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

(function($){

	$('.wpape_gallery').on( "click", '.wpape-lightbox.mfp-link', function (event) {
		event.preventDefault();
		window.location.href = $(this).data('mfp-src');
	} );  

	$('.wpape_gallery').each( function(){
		
		var galleryID =$(this).data('options');

		var galleryBlock = $('#Block_'+galleryID);
		galleryBlock.css('display', 'block');
		
		var objectOptions = window[ galleryID ];
		var realOptions = $.extend({ },objectOptions);
		var gallery = $(this).collagePlus( realOptions );   
		var Gallery_Image = '0';

		if( realOptions.touch == 1 && (typeof $("body").swipe === "function") ) { 
			$("body").swipe({
		        swipeLeft: function(event, direction, distance, duration, fingerCount){ $(".mfp-arrow-left").magnificPopup("prev"); },
		        swipeRight: function(){ $(".mfp-arrow-right").magnificPopup("next"); },
		        threshold: 50
		    });
		    $("body").swipe("disable");
		}
		if( apeGalleryDelay != undefined && apeGalleryDelay > 0 ){
			setTimeout(function(){
				Gallery_Image = 1;
				gallery.eveMB('resize'); 
			}, apeGalleryDelay);
		}
	});
})( window.apeQuer || window.jQuery );