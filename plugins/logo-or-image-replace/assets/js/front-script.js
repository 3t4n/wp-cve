jQuery(document).ready(function($){

  'use strict';

  var original_img_url = qc_lpp_js_vars.original_img_url;
  var replacing_image_url = qc_lpp_js_vars.replacing_image_url;
  var image_width = qc_lpp_js_vars.qc_lpp_image_width;
  var image_height = qc_lpp_js_vars.qc_lpp_image_height;

  jQuery('img[src="'+original_img_url+'"]').each(function(){
  	jQuery(this).attr('src', replacing_image_url);
  	jQuery(this).removeAttr('srcset');
  	jQuery(this).removeAttr('sizes');

  	if( image_width > 0 ){
  		jQuery(this).attr('width', image_width);
  		jQuery(this).css('maxWidth', image_width+'px');
  	}

  	if( image_height > 0 ){
  		jQuery(this).attr('height', image_height);
  		jQuery(this).css('height', image_height);
  	}
  })

});