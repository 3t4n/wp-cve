/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

/* 
 Show ShortCode information block in gallery list
*/

function wpApeGalleryCopyToClipboard(text, type){
	var msgText = 'Use this ShortCode in posts or pages';
	if(type==1) msgText =  'Use this function in php files';
    window.prompt( msgText, text);
  }

/*(function() {
    var els = document.querySelectorAll("a[href=\"edit.php?post_type=wpape_gallery_type&page=wpape-gallery-premium\"]");
	if(els.length > 0 ){
		els[0].addEventListener( "click", function(event) {
			event.preventDefault();
			window.open("https://wpape.net/open.php?type=gallery&action=premium", "_blank");
		});	
	}
})();*/

jQuery(function(){
	

	jQuery('a[href="edit.php?post_type=wpape_gallery_type&page=wpape-gallery-premium"]').click( function(event ){
		event.preventDefault();
		window.open("https://wpape.net/open.php?type=gallery&action=premium", "_blank");
	});

	jQuery('a[href="edit.php?post_type=wpape_gallery_type&page=wpape-gallery-support-premium"]').click( function(event ){
		event.preventDefault();
		window.open("https://wpape.net/open.php?type=gallery&action=supportPremium", "_blank");
	});

	jQuery('a[href="edit.php?post_type=wpape_gallery_type&page=wpape-gallery-support"]').click( function(event ){
		event.preventDefault();
		window.open("https://wpape.net/open.php?type=gallery&action=support", "_blank");
	});

	jQuery('a[href="edit.php?post_type=wpape_gallery_type&page=wpape-gallery-demo"]').click( function(event ){
		event.preventDefault();
		window.open("https://wpape.net/open.php?type=gallery&action=demo", "_blank");
	});

	jQuery('a[href="edit.php?post_type=wpape_gallery_type&page=wpape-gallery-guides"]').click( function(event ){
		event.preventDefault();
		window.open("https://wpape.net/open.php?type=gallery&action=guides", "_blank");
	});

	jQuery('td.column-wpape_gallery_shortcode').click( function(){
		wpApeGalleryCopyToClipboard( jQuery(this).find('span').text(), 0 );
	});

	jQuery('td.column-wpape_gallery_code').click( function(){
		wpApeGalleryCopyToClipboard( jQuery(this).find('span').text(), 1 );
	});

	/*jQuery('.wpape_gallery_static span').click( function(){
		alert("Static gallery \n help you to make your gallery load faster and speed up page with gallery in general. \n When this option enabled your page will have better load time and saved resources of the server.");
	})*/


	
});