/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

document.addEventListener("DOMContentLoaded", function(event) {
	
	function apeInformationAddEvent( classSearch, typeEvent ){
		var elemWithClass = document.getElementsByClassName( classSearch);
		for (var i = 0; i < elemWithClass.length; i++) {
			elemWithClass[i].addEventListener("click", function(){
				window.open( typeEvent,'_blank');
				if( (' '+this.className+' ').indexOf(' wpape_close_dialog ') > -1 ) window['apeGalleryDialog'].dialog("close")
				return false;
			});
		};
	}

	apeInformationAddEvent( 'wpape_getproversion_blank', 		'https://wpape.net/open.php?type=gallery&action=premium' );
	apeInformationAddEvent( 'wpape_getproversionfree_blank', 	'https://wpape.net/open.php?type=gallery&action=premiumfree' );
	apeInformationAddEvent( 'wpape_getproversiontrans_blank', 	'https://wpape.net/open.php?type=gallery&action=premiumtrans' );
});
