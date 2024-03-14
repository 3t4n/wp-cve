jQuery(document).ready(function() {
	jQuery('.vtabs > div').hide();
	jQuery('.vtabs > div').first().show();
	jQuery('.vtabs > ul > li:first-child').addClass('lg-tabs-active');
	
	jQuery('.vtabs > ul > li').each(function(){
		jQuery(this).on('click', function(e){
			e.preventDefault();
			jQuery('.vtabs > ul > li').removeClass('lg-tabs-active')
			jQuery(this).addClass('lg-tabs-active')
			var tabCont = jQuery(this).find('a').attr('href');
			console.log(tabCont)
			jQuery('.vtabs > div').hide();
			jQuery(tabCont).show();
		})
	})
	
// 	jQuery(".vtabs").tabs({
// 		activate: function(event, ui) {
// 			if(ui.newTab.find('a').attr('href') == '#tabs-Layout') {
// 				jQuery('#wp_lightgalleries_data_layout_ignore').change();
// 			}
// 		}
// 	}).addClass("ui-tabs-vertical ui-helper-clearfix");
// 	jQuery(".vtabs li").removeClass("ui-corner-top").addClass("ui-corner-left");
	
	jQuery('.toggle-control').click(function() {
		jQuery(this).toggleClass('active');		
		if(jQuery(this).next().val() == 'true') {
			jQuery(this).next().val('false');
		} else {
			jQuery(this).next().val('true');
		}
	});
	
	jQuery("body").on("click", "#wp_lightgalleries_data_images_button_add_new_ignore", function(e) {
		e.preventDefault();	 
		var button = jQuery(this),
		custom_uploader = wp.media({
			title: "Select / Upload Slide(s)",
			type : "image",
			button: {
				text: "Use this image"
			},
			multiple: 'add'	
		}).on("select", function() {
			var slides = custom_uploader.state().get('selection').map(function(attachment) {
				attachment.toJSON();
				return attachment;
			});
			jQuery.each(slides, function(index, value) {
				wp_lightgalleries_data_slide_add_new(value.attributes);
			});
		}).open();
	});
	
	jQuery('#wp_lightgalleries_data_layout_ignore').change(function() {
		jQuery('.layout_option').parent().hide();
		jQuery('.'+jQuery(this).val()+'_layout_option').parent().show();
	}).change();
	
	if(jQuery('#original_post_status').val() == 'publish') {
		jQuery('#embed_shortcode_success').text('[lightgallery id="'+jQuery('#post_ID').val()+'"]');
		jQuery('#embed_shortcode_success').show();
		jQuery('#embed_shortcode_success_instruction').show();
		jQuery('#embed_shortcode_fail').hide();
	} else {
		jQuery('#embed_shortcode_success').hide();
		jQuery('#embed_shortcode_success_instruction').hide();
		jQuery('#embed_shortcode_fail').show();
	}
	
	const slideControlsTemplate = `<fieldset class="slide_current_wrapper">
										<div class="slide_current_wrapper_inner">
										<div class="lg-fileupload-image">
											<p class="control">
												<a href="#" class="smartlogix_uploader_button">
													<img src="%%%slide_image_url_ignore%%%">
												</a>
												<a href="#" class="smartlogix_uploader_remove_button button">Remove Image</a>
												<input type="hidden" id="wp_lightgalleries_data[slide_image_ignore][]" name="wp_lightgalleries_data[slide_image_ignore][]" value="%%%slide_image_ignore%%%">
												<span class="clear"></span>
											</p>
										</div>
										<div class="lg-fileupload-form">
											<div class="lg-field-group">
												<p class="control">
													<label for="wp_lightgalleries_data[slide_width_ignore][]">Thumbnails Width</label>
													<input placeholder="Optional" type="number" min="0" id="wp_lightgalleries_data[slide_width_ignore][]" name="wp_lightgalleries_data[slide_width_ignore][]" value="%%%slide_width_ignore%%%" class="multilanguage-input input widefat">
												</p>
												<p class="control">
													<label for="wp_lightgalleries_data[slide_height_ignore][]">Thumbnails Height</label>
													<input placeholder="Optional" type="number" min="0" id="wp_lightgalleries_data[slide_height_ignore][]" name="wp_lightgalleries_data[slide_height_ignore][]" value="%%%slide_height_ignore%%%" class="multilanguage-input input widefat">
												</p>
											</div>
											<p class="control">
												<label for="wp_lightgalleries_data[slide_title_ignore][]">Slide Title</label>
												<input type="text" id="wp_lightgalleries_data[slide_title_ignore][]" name="wp_lightgalleries_data[slide_title_ignore][]" value="%%%slide_title_ignore%%%" class="multilanguage-input input widefat">
											</p>
											<p class="control">
												<label for="wp_lightgalleries_data[slide_description_ignore][]">Slide Description</label>
												<input type="text" id="wp_lightgalleries_data[slide_description_ignore][]" name="wp_lightgalleries_data[slide_description_ignore][]" value="%%%slide_description_ignore%%%" class="multilanguage-input input widefat">
											</p>
											<p class="control">
												<label for="wp_lightgalleries_data[slide_video_ignore][]">Video URL</label>
												<input type="text" id="wp_lightgalleries_data[slide_video_ignore][]" name="wp_lightgalleries_data[slide_video_ignore][]" value="%%%slide_video_ignore%%%" class="multilanguage-input input widefat">
												<span class="settings-info">Optional : Supports Youtube, Vimeo and Wistia videos.</span>
											</p>
										</div>
										<span class="slide_current_remove"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span>
</div>
									</fieldset>`;
	function wp_lightgalleries_data_slide_add_new(value) {
		var template = slideControlsTemplate;
		var templateVariables = {
			'%%%slide_image_url_ignore%%%': value.url,
			'%%%slide_image_ignore%%%': value.id,
			'%%%slide_width_ignore%%%': '',
			'%%%slide_height_ignore%%%': '',
			'%%%slide_title_ignore%%%': value.title,
			'%%%slide_description_ignore%%%': value.description,
			'%%%slide_video_ignore%%%': '',
		};
		for(var key in templateVariables) {
			template = template.replace(key, templateVariables[key]);
		}
		jQuery('.slides_current_wrapper .lg-slide-content').prepend(template);
		jQuery('.slides_current_wrapper').show();
		jQuery('.slides_current_wrapper').sortable({
			items: '.slide_current_wrapper'
		});
		jQuery('.slides_add_new_wrapper .control .smartlogix_uploader_remove_button').click();
		jQuery('.slides_add_new_wrapper .control #wp_lightgalleries_data_slide_width_add_new_ignore').val('');
		jQuery('.slides_add_new_wrapper .control #wp_lightgalleries_data_slide_height_add_new_ignore').val('');
		jQuery('.slides_add_new_wrapper .control #wp_lightgalleries_data_slide_title_add_new_ignore').val('');	
		jQuery('.slides_add_new_wrapper .control #wp_lightgalleries_data_slide_description_add_new_ignore').val('');
		jQuery('.slide_current_remove').click(function() {
			lg_slide_current_remove(this);
		});
// 			jQuery('.slides_current_wrapper .slide_current_wrapper').each(function() {
// 				jQuery(this).css('max-height', (jQuery(this).find('p.control:first').height()+30)+'px');
// 			});
		// jQuery('.slide_current_resize').click(function() {
			// lg_slide_current_expand(this);
		// });
	}

	jQuery('.slides_current_wrapper').sortable({
		items: '.slide_current_wrapper'
	});
	
	jQuery('.slide_current_remove').click(function() {
		lg_slide_current_remove(this);
	});
	// jQuery('.slide_current_resize').click(function() {
		// lg_slide_current_expand(this);
	// });
	
	// if(jQuery('.slides_current_wrapper .slide_current_wrapper').length != 0) {
		// jQuery('.slides_current_wrapper .slide_current_wrapper').each(function() {
			// jQuery(this).css('max-height', (jQuery(this).find('p.control:first').height()+30)+'px');
		// });
	// }
});

function lg_slide_current_remove(sender) {
	jQuery(sender).parent().remove();
	if(jQuery('.slides_current_wrapper .slide_current_wrapper').length == 0) {
		jQuery('.slides_current_wrapper').hide();
	}
}

function lg_slide_current_expand(sender) {
	if(jQuery(sender).parent().hasClass('expanded')) {
		jQuery(sender).parent().css('max-height', (jQuery(sender).parent().find('p.control:first').height()+30)+'px').removeClass('expanded');
		jQuery(sender).removeClass('expanded');
	} else {
		jQuery(sender).parent().css('max-height', 'none').addClass('expanded');
		jQuery(sender).addClass('expanded');
	}
}
