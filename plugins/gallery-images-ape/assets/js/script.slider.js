/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

(function() {
	var apeSliders 			= document.getElementsByClassName('wpape-slider-container'),
		apeSliderslength 	= apeSliders.length;

	for (var i = 0; i < apeSliderslength; i++)  buildApeSlider( apeSliders[i] );

	function buildApeSlider( apeSlider ){

		var id 				= apeSlider.id,
			objectOptions 	= window[id],
			loader          = apeSlider.previousElementSibling;

		if(apeSlider.style.display=='none') apeSlider.style.display = 'block';

		window['obj_'+id] = new Swiper( '#'+id, objectOptions);
		if( loader !== null ) loader.style.display = "none";
	}
})();