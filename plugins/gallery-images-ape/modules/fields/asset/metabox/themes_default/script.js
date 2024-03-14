/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

(function ($) {
    $(document).ready(function () {
    	$('#wapeGalleryThemesDefaultButton').each(function() {
			var button = this;
			var buttonObj = $(button);

			buttonObj.click(function(event){
				event.preventDefault();


				$('#wpape_gallery_fields_themes_default_message').html( '<div class="apeLoading"></div>' );
				$('#wapeGalleryThemesDefaultButtonDiv').hide();

				var data2Send = {
					'action': 'wpape_gallery_default_theme_save', 
					'idGallery': buttonObj.data('id'),
					'nonce': buttonObj.data('nonce'),
				};

				$.getJSON( ajaxurl, data2Send, function(response) {
					//console.log('Got this from the server: ' + response);
				})
				.done(function(){ 
					$('#wpape_gallery_fields_themes_default_message').html( twojGalleryThemesDefaultTr.messageOk ).addClass('big');
					$('#wapeGalleryThemesDefaultButtonDiv').remove();
				})
				.fail(function(){
					$('#wpape_gallery_fields_themes_default_message').html( twojGalleryThemesDefaultTr.messageError ).addClass('big');
				});

			});
		});
	});
})(jQuery);