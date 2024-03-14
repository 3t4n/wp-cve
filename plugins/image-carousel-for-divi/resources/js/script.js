function wptools_slick_init(){
	jQuery('section.et_pb_wptools_image_carousel').each(function(){
		var el = jQuery(this);
		if(!el.data('slick-initialised')){
			// console.log('here', el.data('carousel-props'));
			el.slick(el.data('carousel-props'));
			el.data('slick-initialised', true);
		} else {
			el.slick('unslick');
		}
	});

}

jQuery(document).ready(function($) {
	wptools_slick_init();
});