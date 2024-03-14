jQuery(document).ready(function() {
	var add_lightbox_string = ewd_ulb_php_add_data.add_lightbox.replace(/&quot;/g, '"');
	var add_lightbox_array = jQuery.parseJSON(add_lightbox_string);

	var lightbox_added = false;

	var title_src = ewd_ulb_php_add_data.overlay_text_source == 'caption' ? 'title' : 'alt';
	var description_src = ewd_ulb_php_add_data.overlay_text_source == 'caption' ? 'caption' : 'alt';

	if (jQuery.inArray("all_images", add_lightbox_array) !== -1) {
		jQuery("img").each(function() {
			if (jQuery(this).height() >= ewd_ulb_php_add_data.min_height && jQuery(this).width() >= ewd_ulb_php_add_data.min_width) {
				if (description_src == 'caption') {var description = jQuery(this).parent().parent().find('figcaption').html();}
				else {var description = jQuery(this).attr(description_src);}

				jQuery(this).parent().addClass('ewd-ulb-lightbox');
				jQuery(this).parent().data('ulbsource', jQuery(this).attr('src').replace(/-150x150/g, ''));
				jQuery(this).parent().data('ulbtitle', jQuery(this).attr(title_src));
				jQuery(this).parent().data('ulbdescription', description);
			}
		});
	}

	if (jQuery.inArray("all_youtube", add_lightbox_array) !== -1) {
		jQuery("iframe").each(function() {
			if (jQuery(this).height() >= ewd_ulb_php_add_data.min_height && jQuery(this).width() >= ewd_ulb_php_add_data.min_width) {
				var youtube = jQuery(this).attr('src').match(/\/\/(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=|embed\/)?([a-z0-9\-\_\%]+)/i);
				if (youtube) {
					jQuery(this).parent().addClass('ewd-ulb-lightbox');
					jQuery(this).parent().addClass('ewd-ulb-iframe-parent');
					jQuery(this).parent().append('<div class="ewd-ulb-overlay"></div>');
					jQuery(this).parent().data('ulbsource', jQuery(this).attr('src').split("?")[0]);
					jQuery(this).parent().data('ulbheight', jQuery(this).attr('height'));
					jQuery(this).parent().data('ulbwidth', jQuery(this).attr('width'));
				}
			}
		});
	}

	if (jQuery.inArray("image_class", add_lightbox_array) !== -1) {
		var class_array = ewd_ulb_php_add_data.image_class_list.split(','); console.log( class_array );
		jQuery(class_array).each(function(index, el) {
			jQuery("."+el).each(function() { 
				if (jQuery(this).height() >= ewd_ulb_php_add_data.min_height && jQuery(this).width() >= ewd_ulb_php_add_data.min_width) { console.log( 'applied' );

					if (description_src == 'caption') {var description = jQuery(this).closest('figure').find('figcaption').html();}
					else {var description = jQuery(this).attr(description_src);}

					jQuery(this).parent().addClass('ewd-ulb-lightbox');
					jQuery(this).parent().data('ulbsource', jQuery(this).attr('src'));
					jQuery(this).parent().data('ulbtitle', jQuery(this).attr(title_src));
					jQuery(this).parent().data('ulbdescription', description);
				}
			});
		});
	}

	if (jQuery.inArray("image_selector", add_lightbox_array) !== -1) {
		var selector_array = ewd_ulb_php_add_data.image_selector_list.split(',');
		jQuery(selector_array).each(function(index, el) {
			jQuery(el).each(function() { 
				if (jQuery(this).height() >= ewd_ulb_php_add_data.min_height && jQuery(this).width() >= ewd_ulb_php_add_data.min_width) {
					 
					if (description_src == 'caption') {var description = jQuery(this).closest('figure').find('figcaption').html();}
					else {var description = jQuery(this).attr(description_src);}
					
					jQuery(this).parent().addClass('ewd-ulb-lightbox');
					jQuery(this).parent().data('ulbsource', jQuery(this).attr('src'));
					jQuery(this).parent().data('ulbtitle', jQuery(this).attr(title_src));
					jQuery(this).parent().data('ulbdescription', description);
				}
			});
		});
	}

	if (jQuery.inArray("woocommerce_product_page", add_lightbox_array) !== -1) {
		jQuery('.product .images figure').find('img.wp-post-image').each(function() {
			if (jQuery(this).data('src')) {var src = jQuery(this).data('src');}
			else {var src = jQuery(this).attr('src');}

			if (description_src == 'caption') {var description = jQuery(this).parent().parent().find('figcaption').html();}
			else {var description = img.attr(description_src);}

			jQuery(this).parent().addClass('ewd-ulb-lightbox');
			jQuery(this).parent().data('ulbsource', src);
			jQuery(this).parent().data('ulbtitle', jQuery(this).attr(title_src));
			jQuery(this).parent().data('ulbdescription', description);

			jQuery(this).parent().parent().on('click', function() {
				lightbox.toggle();
			});

		});
		/*jQuery('.flex-control-nav').on('click', function() {
			lightbox.setGenericCurrentSlide(this);
		});
		jQuery('.woocommerce-product-gallery__trigger').off();
		jQuery('.woocommerce-product-gallery__trigger').on('click', function() {console.log("Called click");
			lightbox.toggle();
		});*/
	}

	if (jQuery.inArray("galleries", add_lightbox_array) !== -1 && jQuery.inArray("all_images", add_lightbox_array) === -1) {
		jQuery(".gallery").each(function() { 
			var gallery_id = jQuery(this).attr("id");
			jQuery(this).find('a').each(function() {
				if (jQuery(this).find("img").length) {
					var img = jQuery(this).find('img').first();
					if (img.height() >= ewd_ulb_php_add_data.min_height && img.width() >= ewd_ulb_php_add_data.min_width) {	
						if (description_src == 'caption') {var description = jQuery(this).parent().parent().find('figcaption').html();}
						else {var description = img.attr(description_src);}

						jQuery(this).addClass('ewd-ulb-lightbox');
						jQuery(this).data('ulbsource', img.attr('src').replace(/-150x150/g, ''));
						jQuery(this).data('ulbtitle', img.attr(title_src));
						jQuery(this).data('ulbdescription', description);
						jQuery(this).data('ulbGallery', gallery_id);
					}
				}
			})
		});

		jQuery( '.wp-block-gallery' ).each( function() { 

			var gallery_id = ulb_generate_random_string();
			jQuery( this ).find( 'img' ).each( function() {

				var img = jQuery( this );

				if ( img.height() >= ewd_ulb_php_add_data.min_height && img.width() >= ewd_ulb_php_add_data.min_width ) {	
					
					if ( description_src == 'caption' ) { var description = jQuery(this).parent().find('figcaption').html(); }
					else { var description = img.attr(description_src); }

					jQuery(this).addClass( 'ewd-ulb-lightbox' );
					jQuery(this).data( 'ulbsource', jQuery( this ).attr('src').replace(/-150x150/g, '') );
					jQuery(this).data( 'ulbtitle', jQuery( this ).attr( title_src ) );
					jQuery(this).data( 'ulbdescription', description );
					jQuery(this).data( 'ulbGallery', gallery_id );
				}

			});
		});
	} 

});

function ulb_generate_random_string( length ) {

   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   var charactersLength = characters.length;

   for ( var i = 0; i < length; i++ ) {
      
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }

   return result;
}