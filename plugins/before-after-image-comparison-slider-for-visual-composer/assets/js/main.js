// jQuery(window).load(function(){
// 	jQuery(".wb_vc_baic_container").twentytwenty();
// });

jQuery(window).on('load',function(){
	jQuery(window).trigger("resize");
	
	jQuery(window).on('scroll', function() {
		jQuery('.wb_vc_baic_container').each(function(){
			if (jQuery(this).wbvcbaic_isInViewport()) {
				jQuery(window).trigger('resize');
			}
		});
	});

	jQuery('.wb_vc_baic_container').each(function(){
		wb_baics_render(jQuery(this))
	});
});

function wb_baics_render( element ){
	var before_text = element.attr('data-before-text');
	var after_text = element.attr('data-after-text');
	element.twentytwenty({
		before_label : before_text,
		after_label : after_text
	});
	jQuery(window).trigger("resize");
}

jQuery.fn.wbvcbaic_isInViewport = function() {
  var elementTop = jQuery(this).offset().top;
  var elementBottom = elementTop + jQuery(this).outerHeight();

  var viewportTop = jQuery(window).scrollTop();
  var viewportBottom = viewportTop + jQuery(window).height();

  return elementBottom > viewportTop && elementTop < viewportBottom;
};