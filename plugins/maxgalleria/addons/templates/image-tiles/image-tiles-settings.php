<?php
$options = new MaxGalleriaImageTilesOptions();
?>

<script type="text/javascript">		
	jQuery(document).ready(function() {
    
    jQuery(document).on("click", "#save-image-tiles-settings", function() {
			jQuery("#save-image-tiles-settings-success").hide();
			
			var form_data = jQuery("#form-image-tiles-settings").serialize();

			// If thumb caption enabled is not checked, we have to add it to form data with an empty value
			if (jQuery("#<?php echo esc_html($options->thumb_caption_enabled_default_key) ?>").is(":not(:checked)")) {
				form_data += "&<?php echo esc_html($options->thumb_caption_enabled_default_key) ?>=";
			}
			
			// If lightbox caption enabled is not checked, we have to add it to form data with an empty value
			if (jQuery("#<?php echo esc_html($options->lightbox_caption_enabled_default_key) ?>").is(":not(:checked)")) {
				form_data += "&<?php echo esc_html($options->lightbox_caption_enabled_default_key) ?>=";
			}
      
			if (jQuery("#<?php echo esc_html($options->prev_button_title_default_key) ?>").is(":not(:checked)")) {
				form_data += "&<?php echo esc_html($options->lazy_load_enabled_default_key) ?>=";
			}
      
			if (jQuery("#<?php echo esc_html($options->vertical_fit_enabled_default_key) ?>").is(":not(:checked)")) {
				form_data += "&<?php echo esc_html($options->vertical_fit_enabled_default_key) ?>=";
			}
      
			if (jQuery("#<?php echo esc_html($options->content_click_close_enabled_default_key) ?>").is(":not(:checked)")) {
				form_data += "&<?php echo esc_html($options->content_click_close_enabled_default_key) ?>=";
			}
      
			if (jQuery("#<?php echo esc_html($options->bg_click_close_enabled_default_key) ?>").is(":not(:checked)")) {
				form_data += "&<?php echo esc_html($options->bg_click_close_enabled_default_key) ?>=";
			}
      
			if (jQuery("#<?php echo esc_html($options->hide_close_btn_enabled_default_key) ?>").is(":not(:checked)")) {
				form_data += "&<?php echo esc_html($options->hide_close_btn_enabled_default_key) ?>=";
			}
      
			if (jQuery("#<?php echo esc_html($options->escape_key_enabled_default_key) ?>").is(":not(:checked)")) {
				form_data += "&<?php echo esc_html($options->escape_key_enabled_default_key) ?>=";
			}
      
			if (jQuery("#<?php echo esc_html($options->align_top_enabled_default_key) ?>").is(":not(:checked)")) {
				form_data += "&<?php echo esc_html($options->align_top_enabled_default_key) ?>=";
			}
      
			if (jQuery("#<?php echo esc_html($options->zoom_enabled_default_key) ?>").is(":not(:checked)")) {
				form_data += "&<?php echo esc_html($options->zoom_enabled_default_key) ?>=";
			}
      
			if (jQuery("#<?php echo esc_html($options->gallery_enabled_default_key) ?>").is(":not(:checked)")) {
				form_data += "&<?php echo esc_html($options->gallery_enabled_default_key) ?>=";
			}
      
			if (jQuery("#<?php echo esc_html($options->navigate_by_img_click_enabled_default_key) ?>").is(":not(:checked)")) {
				form_data += "&<?php echo esc_html($options->navigate_by_img_click_enabled_default_key) ?>=";
			}
      
			if (jQuery("#<?php echo esc_html($options->retina_enabled_default_key) ?>").is(":not(:checked)")) {
				form_data += "&<?php echo esc_html($options->retina_enabled_default_key) ?>=";
			}
			
			if (jQuery("#<?php echo esc_html($options->ns_show_border_default_key) ?>").is(":not(:checked)")) {
				form_data += "&<?php echo esc_html($options->ns_show_border_default_key) ?>=";
			}
			
            
			// Add the action to the form data
			form_data += "&action=save_image_tiles_defaults";

			jQuery.ajax({
				type: "POST",
				url: "<?php echo admin_url('admin-ajax.php') ?>",
				data: form_data,
				success: function(message) {
					if (message == "success") {
						jQuery("#save-image-tiles-settings-success").show();
            window.location.reload(true);
					}
				}
			});
			
			return false;
		});
		
    jQuery(document).on("click", "#revert-image-tiles-defaults", function() {
			jQuery.each(jQuery("input, select", "#form-image-tiles-settings"), function() {
				var type = jQuery(this)[0].type;
				var default_value = jQuery(this).attr("data-default");
				
				if (type != "hidden") {
					if (type == "checkbox") {
						if (default_value == "on") {
							jQuery(this).prop("checked", true);
						}
						else {
							jQuery(this).prop("checked", false);
						}
					}
					else {
						jQuery(this).val(default_value);
					}
				}
			});
			
			jQuery("#thickness_default").prop('checked', true);
			jQuery("#radius-default").prop('checked', true);
			jQuery("#shadow-default").prop('checked', true);						
			jQuery("#blur-default").prop('checked', true);						
			jQuery("#spread-default").prop('checked', true);						
			jQuery("#thumbnail-column-default").prop('checked', true);						
			jQuery("#thumbnail-shape-default").prop('checked', true);						
			jQuery("#caption-position-default").prop('checked', true);						
			jQuery("#close-button-default").prop('checked', true);						
			jQuery("#default-arrow-type").prop('checked', true);						

			return false;
		});
		
		jQuery('#<?php echo esc_html($options->ns_border_color_default_key) ?>').colpick({
				layout:'hex',
				submit:0,
				colorScheme:'dark',
				onChange:function(hsb,hex,rgb,el,bySetColor) {
					jQuery(el).css('border-color','#'+hex);
					// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
					if(!bySetColor) jQuery(el).val('#'+hex);
		    }
		}).keyup(function(){
				jQuery(this).colpickSetColor(this.value);
		});		

		jQuery('#<?php echo esc_html($options->ns_shadow_color_default_key) ?>').colpick({
				layout:'hex',
				submit:0,
				colorScheme:'dark',
				onChange:function(hsb,hex,rgb,el,bySetColor) {
					jQuery(el).css('border-color','#'+hex);
					// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
					if(!bySetColor) jQuery(el).val('#'+hex);
		    }
		}).keyup(function(){
				jQuery(this).colpickSetColor(this.value);
		});		
		
		jQuery('#<?php echo esc_html($options->ns_border_color_default_key) ?>').css('border-color','<?php echo esc_html($options->get_border_color()) ?>');
		jQuery('#<?php echo esc_html($options->ns_shadow_color_default_key) ?>').css('border-color','<?php echo esc_html($options->get_shadow_color()) ?>');
				
	});
</script>

<div id="save-image-tiles-settings-success" class="alert alert-success" style="display: none;">
	<?php esc_html_e('Settings saved.', 'maxgalleria') ?>
</div>

<div class="settings-title">
	<?php esc_html_e('Image Tiles Defaults', 'maxgalleria') ?>
</div>

<div class="settings-options">
	<p class="note"><?php esc_html_e('These are the default settings that will be used every time you create a gallery with the Image Tiles template. Each of these settings can be changed per gallery.', 'maxgalleria') ?></p>
	
	<form id="form-image-tiles-settings">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td colspan="2" class="options-heading"><span class="mg-heading"><?php esc_html_e('GALLERY STYLES', 'maxgalleria') ?></span></td>
			</tr>
			<tr>
				<td class="padding-top"><?php esc_html_e('Preset Layouts:', 'maxgalleria') ?></td>
				<td class="padding-top">
		    <?php if($options->hide_presets === 'off') 
				  $skins = array_merge($options->new_skins, $options->skins );
					  else
					$skins = $options->new_skins;
		     ?>					
					<select data-default="<?php echo esc_html($options->skin_default) ?>" id="<?php echo esc_html($options->skin_default_key) ?>" name="<?php echo esc_html($options->skin_default_key) ?>">
					<?php foreach ($skins as $key => $name) { ?>
						<?php $selected = ($options->get_skin_default() == $key) ? 'selected=selected' : ''; ?>
						<option value="<?php echo esc_attr($key) ?>" <?php echo esc_attr($selected) ?>><?php echo esc_html($name) ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e('Display Border:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo esc_html($options->ns_show_border_default) ?>" type="checkbox" id="<?php echo esc_html($options->ns_show_border_default_key) ?>" name="<?php echo esc_html($options->ns_show_border_default_key) ?>" <?php echo esc_attr(($options->get_show_border_default() == 'on') ? 'checked' : '') ?> />
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e('Border Thickness:', 'maxgalleria') ?></td>
				<td>
				  <table class="mg-settings">
						<tr>
							<td class="mg-radio">
								<input data-default="<?php echo esc_html($options->ns_border_thickness_default) ?>"  id="thickness_default" type="radio" name="<?php echo esc_html($options->ns_border_thickness_default_key) ?>" value="1" class="border-thickness" <?php echo esc_attr(($options->get_border_thickness_default() === '1') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_border_thickness_default_key) ?>" value="3" class="border-thickness" <?php echo esc_attr(($options->get_border_thickness_default() === '3') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_border_thickness_default_key) ?>" value="5" class="border-thickness" <?php echo esc_attr(($options->get_border_thickness_default() === '5') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_border_thickness_default_key) ?>" value="7" class="border-thickness" <?php echo esc_attr(($options->get_border_thickness_default() === '7') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_border_thickness_default_key) ?>" value="9" class="border-thickness" <?php echo esc_attr(($options->get_border_thickness_default() === '9') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_border_thickness_default_key) ?>" value="15" class="border-thickness" <?php echo esc_attr(($options->get_border_thickness_default() === '15') ? 'checked' : ''); ?>>
							</td>
						</tr>	
						<tr>
							<td>
								<img title="<?php esc_html_e('1 pixel', 'maxgalleria') ?>" alt="<?php esc_html_e('border thickness 1 pixel', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL . '/images/options-icons/border-01.png') ?>" >
							</td>
							<td>
								<img title="<?php esc_html_e('3 pixels', 'maxgalleria') ?>" alt="<?php esc_html_e('border thickness 3 pixels', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL . '/images/options-icons/border-03.png') ?>" >
							</td>
							<td>
								<img title="<?php esc_html_e('5 pixels', 'maxgalleria') ?>" alt="<?php esc_html_e('border thickness 5 pixels', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL . '/images/options-icons/border-05.png') ?>" >
							</td>
							<td>
								<img title="<?php esc_html_e('7 pixels', 'maxgalleria') ?>" alt="<?php esc_html_e('border thickness 7 pixels', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/border-07.png') ?>" >
							</td>
							<td>
								<img title="<?php esc_html_e('9 pixels', 'maxgalleria') ?>" alt="<?php esc_html_e('border thickness 9 pixels', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/border-09.png') ?>" >
							</td>
							<td>
								<img title="<?php esc_html_e('15 pixel', 'maxgalleria') ?>" alt="<?php esc_html_e('border thickness 15 pixels', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/border-15.png') ?>" >
							</td>
						</tr>
					</table>
				</td>								
			</tr>
			<tr>
				<td><?php esc_html_e('Border Color:', 'maxgalleria') ?></td>
				<td>
					<img id="<?php echo esc_html($options->ns_border_color_default_key . '2') ?>" class="left" alt="border color button" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/color.png') ?>">
					<input class="color-input" data-default="<?php echo esc_html($options->ns_border_color_default) ?>" type="text" id="<?php echo esc_html($options->ns_border_color_default_key) ?>" name="<?php echo esc_html($options->ns_border_color_default_key) ?>" value="<?php echo esc_html($options->get_border_color_default()) ?>" />
				</td>
			</tr>			
			<tr>
				<td><?php esc_html_e('Border Radius:', 'maxgalleria') ?></td>
				<td>
				  <table class="mg-settings">
						<tr>
							<td class="mg-radio">
								<input data-default="<?php echo esc_html($options->ns_border_radius_default) ?>"  id="radius-default" type="radio" name="<?php echo esc_html($options->ns_border_radius_default_key) ?>" value="0" class="border-radius" <?php echo esc_attr(($options->get_border_radius_default() === '0') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_border_radius_default_key) ?>" value="10" class="border-radius" <?php echo esc_attr(($options->get_border_radius_default() === '10') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_border_radius_default_key) ?>" value="20" class="border-radius" <?php echo esc_attr(($options->get_border_radius_default() === '20') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_border_radius_default_key) ?>" value="30" class="border-radius" <?php echo esc_attr(($options->get_border_radius_default() === '30') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_border_radius_default_key) ?>" value="40" class="border-radius" <?php echo esc_attr(($options->get_border_radius_default() === '40') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_border_radius_default_key) ?>" value="50" class="border-radius" <?php echo esc_attr(($options->get_border_radius_default() === '50') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_border_radius_default_key) ?>" value="60" class="border-radius" <?php echo esc_attr(($options->get_border_radius_default() === '60') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_border_radius_default_key) ?>" value="70" class="border-radius" <?php echo esc_attr(($options->get_border_radius_default() === '70') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_border_radius_default_key) ?>" value="80" class="border-radius" <?php echo esc_attr(($options->get_border_radius_default() === '80') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_border_radius_default_key) ?>" value="90" class="border-radius" <?php echo esc_attr(($options->get_border_radius_default() === '90') ? 'checked' : ''); ?>>
							</td>
						</tr>
						<tr>
							<td>
								<img title="<?php esc_html_e('No border radius', 'maxgalleria') ?>" alt="<?php esc_html_e('No border radius', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/border-radius-0.png') ?>" >
							</td>
							<td>
								<img title="<?php esc_html_e('10 pixel border radius', 'maxgalleria') ?>" alt="<?php esc_html_e('10 pixel border radius', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/border-radius-10.png') ?>" >
							</td>
							<td>
								<img title="<?php esc_html_e('20 pixel border radius', 'maxgalleria') ?>" alt="<?php esc_html_e('20 pixel border radius', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/border-radius-20.png')?>" >
							</td>
							<td>
								<img title="<?php esc_html_e('30 pixel border radius', 'maxgalleria') ?>" alt="<?php esc_html_e('30 pixel border radius', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/border-radius-30.png') ?>" >
							</td>
							<td>
								<img title="<?php esc_html_e('40 pixel border radius', 'maxgalleria') ?>" alt="<?php esc_html_e('40 pixel border radius', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/border-radius-40.png') ?>" >
							</td>
							<td>
								<img title="<?php esc_html_e('50 pixel border radius', 'maxgalleria') ?>" alt="<?php esc_html_e('50 pixel border radius', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/border-radius-50.png') ?>" >
							</td>
							<td>
								<img title="<?php esc_html_e('60 pixel border radius', 'maxgalleria') ?>" alt="<?php esc_html_e('60 pixel border radius', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/border-radius-60.png') ?>" >
							</td>
							<td>
								<img title="<?php esc_html_e('70 pixel border radius', 'maxgalleria') ?>" alt="<?php esc_html_e('70 pixel border radius', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/border-radius-70.png') ?>" >
							</td>
							<td>
								<img title="<?php esc_html_e('80 pixel border radius', 'maxgalleria') ?>" alt="<?php esc_html_e('80 pixel border radius', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/border-radius-80.png') ?>" >
							</td>
							<td>
								<img title="<?php esc_html_e('90 pixel border radius', 'maxgalleria') ?>" alt="<?php esc_html_e('90 pixel border radius', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/border-radius-90.png') ?>" >
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e('Shadow Type:', 'maxgalleria') ?></td>
				<td>
				  <table class="mg-settings">
						<tr>
							<td class="mg-radio">
								<input data-default="<?php echo esc_html($options->ns_shadow_default) ?>" id="shadow-default" type="radio" name="<?php echo esc_html($options->ns_shadow_default_key) ?>" value="none" class="ns-shadow-type" <?php echo esc_attr(($options->get_shadow_default() === 'none') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_shadow_default_key) ?>" value="inside" class="ns-shadow-type" <?php echo esc_attr(($options->get_shadow_default() === 'inside') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_shadow_default_key) ?>" value="behind" class="ns-shadow-type" <?php echo esc_attr(($options->get_shadow_default() === 'behind') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" id="shadow-color-option" name="<?php echo esc_html($options->ns_shadow_default_key) ?>" value="color" class="ns-shadow-type" <?php echo esc_attr(($options->get_shadow_default() === 'color') ? 'checked' : ''); ?>>
							</td>
						</tr>
						<tr>
							<td>
								<img title="<?php esc_html_e('No shadow', 'maxgalleria') ?>" alt="<?php esc_html_e('no shadow style', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/shadow-none.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('Inside shadow', 'maxgalleria') ?>" alt="<?php esc_html_e('inside shadow style', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/shadow-inside.png') ?>" >
							</td>
							<td>
								<img title="<?php esc_html_e('Behind shadow', 'maxgalleria') ?>" alt="<?php esc_html_e('behind shadow style', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/shadow-behind.png') ?>" >
							</td>
							<td>
								<img title="<?php esc_html_e('Color shadow', 'maxgalleria') ?>" alt="<?php esc_html_e('color shadow style', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/shadow-color.png') ?>" >
							</td>
						</tr>
					</table>
				</td>								
			</tr>
			<tr>
				<td><?php esc_html_e('Shadow Color:', 'maxgalleria') ?></td>
				<td>
					<img id="<?php echo esc_html($options->ns_shadow_color_default_key . '2') ?>" class="left" alt="border color button" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/color.png') ?>">
					<input class="color-input" data-default="<?php echo esc_html($options->ns_shadow_color_default) ?>" type="text" id="<?php echo esc_html($options->ns_shadow_color_default_key) ?>" name="<?php echo esc_html($options->ns_shadow_color_default_key) ?>" value="<?php echo esc_html($options->get_shadow_color_default()) ?>" />
				</td>
			</tr>												
			<tr>
				<td><?php esc_html_e('Shadow Blur:', 'maxgalleria') ?></td>
				<td>
				  <table class="mg-settings">
						<tr>
							<td class="mg-radio">
								<input data-default="<?php echo esc_html($options->ns_shadow_blur_default) ?>"  id="blur-default" type="radio" name="<?php echo esc_html($options->ns_shadow_blur_default_key) ?>" value="5" class="ns-blur-type" <?php echo esc_attr(($options->get_shadow_blur_default() === '5') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_shadow_blur_default_key) ?>" value="10" class="ns-blur-type" <?php echo esc_attr(($options->get_shadow_blur_default() === '10') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_shadow_blur_default_key) ?>" value="15" class="ns-blur-type" <?php echo esc_attr(($options->get_shadow_blur_default() === '15') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_shadow_blur_default_key) ?>" value="20" class="ns-blur-type" <?php echo esc_attr(($options->get_shadow_blur_default() === '20') ? 'checked' : ''); ?>>
							</td>
						</tr>
						<tr>
							<td>
								<img title="<?php esc_html_e('5 pixels', 'maxgalleria') ?>" alt="<?php esc_html_e('5 pixel blur', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/shadow-blur-5.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('10 pixels', 'maxgalleria') ?>" alt="<?php esc_html_e('10 pixel blur', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/shadow-blur-10.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('15 pixels', 'maxgalleria') ?>" alt="<?php esc_html_e('15 pixel blur', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/shadow-blur-15.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('20 pixels', 'maxgalleria') ?>" alt="<?php esc_html_e('20 pixel blur', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/shadow-blur-20.png') ?>">
							</td>
						</tr>
					</table>
				</td>												
			</tr>			
			<tr>
				<td><?php esc_html_e('Shadow Spread:', 'maxgalleria') ?></td>
				<td>
				  <table class="mg-settings">
						<tr>
							<td class="mg-radio">
								<input data-default="<?php echo esc_html($options->ns_shadow_spread_default) ?>"  id="spread-default" type="radio" name="<?php echo esc_html($options->ns_shadow_spread_default_key) ?>" value="0" class="ns-spread-type" <?php echo esc_attr(($options->get_shadow_spread_default() === '0') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_shadow_spread_default_key) ?>" value="1" class="ns-spread-type" <?php echo esc_attr( ($options->get_shadow_spread_default() === '1') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_shadow_spread_default_key) ?>" value="2" class="ns-spread-type" <?php echo esc_attr(($options->get_shadow_spread_default() === '2') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->ns_shadow_spread_default_key) ?>" value="3" class="ns-spread-type" <?php echo esc_attr(($options->get_shadow_spread_default() === '3') ? 'checked' : ''); ?>>
							</td>
						</tr>
						<tr>
							<td>
								<img title="<?php esc_html_e('0 pixel spread', 'maxgalleria') ?>" alt="<?php esc_html_e('0 pixel spread', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/shadow-spread-0.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('1 pixel spread', 'maxgalleria') ?>" alt="<?php esc_html_e('1 pixel spread', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/shadow-spread-1.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('2 pixels spread', 'maxgalleria') ?>" alt="<?php esc_html_e('2 pixels spread', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/shadow-spread-2.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('3 pixels spread', 'maxgalleria') ?>" alt="<?php esc_html_e('3 pixels spread', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/shadow-spread-3.png') ?>">
							</td>
						</tr>
					</table>
				</td>												
			</tr>						
			<tr><td colspan="2" class="options-heading"><span class="mg-heading"><?php esc_html_e('THUMBNAIL OPTIONS', 'maxgalleria') ?></span></td><td></td></tr>
      <tr>
				<td>
					<?php esc_html_e('Lazy Load Enabled:', 'maxgalleria') ?>
				</td>	
				<td>
					<input data-default="<?php echo esc_html($options->lazy_load_enabled_default) ?>" type="checkbox" id="<?php echo esc_html($options->lazy_load_enabled_default_key) ?>" name="<?php echo esc_html($options->lazy_load_enabled_default_key) ?>" <?php echo esc_attr(($options->get_lazy_load_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
      </tr>
			<tr>
				<td class="mg-italic" colspan = "2"><?php esc_html_e('Lazy Loading allows for faster page loading times and is enabled by default for a better user experience. But you can turn it off if individual images in your gallery are not loading fast enough.', 'maxgalleria') ?></td>				
			</tr>
			<tr>
				<td>
					<?php esc_html_e('Lazy Load Threshold (Pixels):', 'maxgalleria') ?>
				</td>
				<td>
					<input data-default="<?php echo esc_html($options->lazy_load_threshold_default); ?>" type="text" class="small" id="<?php echo esc_html($options->lazy_load_threshold_default_key) ?>" name="<?php echo esc_html($options->lazy_load_threshold_default_key) ?>" value="<?php echo esc_attr($options->get_lazy_load_threshold_default()) ?>" />
				</td>
			</tr>
			<tr>
				<td class="mg-italic" colspan = "2"><?php esc_html_e('Lazy Load Threshold is the number of pixels above an image before it starts loading as the user scrolls down your page.  We set the default to 50 pixels.  If you find you want your images to start loading sooner increase the number of pixels for the threshold.', 'maxgalleria') ?></td>				
			</tr>
			<tr>
				<td class="padding-top"><?php esc_html_e('Thumbnail Columns:', 'maxgalleria') ?></td>
				<td class="padding-top">
				  <table class="mg-settings">
						<tr>
							<td class="mg-radio">
								<input data-default="<?php echo esc_html($options->thumb_columns_default) ?>"  id="thumbnail-column-default" type="radio" name="<?php echo esc_html($options->thumb_columns_default_key) ?>" value="1" class="thumbnail-column-type" <?php echo esc_attr(($options->get_thumb_columns_default() === '1') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->thumb_columns_default_key) ?>" value="2" class="thumbnail-column-type" <?php echo esc_attr(($options->get_thumb_columns_default() === '2') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->thumb_columns_default_key) ?>" value="3" class="thumbnail-column-type" <?php echo esc_attr(($options->get_thumb_columns_default() === '3') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->thumb_columns_default_key) ?>" value="4" class="thumbnail-column-type" <?php echo esc_attr(($options->get_thumb_columns_default() === '4') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->thumb_columns_default_key) ?>" value="5" class="thumbnail-column-type" <?php echo esc_attr(($options->get_thumb_columns_default() === '5') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->thumb_columns_default_key) ?>" value="6" class="thumbnail-column-type" <?php echo esc_attr(($options->get_thumb_columns_default() === '6') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->thumb_columns_default_key) ?>" value="7" class="thumbnail-column-type" <?php echo esc_attr(($options->get_thumb_columns_default() === '7') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->thumb_columns_default_key) ?>" value="8" class="thumbnail-column-type" <?php echo esc_attr(($options->get_thumb_columns_default() === '8') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio"> 
								<input type="radio" name="<?php echo esc_html($options->thumb_columns_default_key) ?>" value="9" class="thumbnail-column-type" <?php echo esc_attr(($options->get_thumb_columns_default() === '9') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio"> 
								<input type="radio" name="<?php echo esc_html($options->thumb_columns_default_key) ?>" value="10" class="thumbnail-column-type" <?php echo esc_attr(($options->get_thumb_columns_default() === '10') ? 'checked' : ''); ?>>
							</td>
						</tr>
						<tr>
							<td>
								<img title="<?php esc_html_e('1 column thumnbnail', 'maxgalleria') ?>" alt="<?php esc_html_e('1 column thumnbnail', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-columns-01.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('2 column thumnbnails', 'maxgalleria') ?>" alt="<?php esc_html_e('2 column thumnbnails', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-columns-02.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('3 column thumnbnails', 'maxgalleria') ?>" alt="<?php esc_html_e('3 column thumnbnails', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-columns-03.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('4 column thumnbnails', 'maxgalleria') ?>" alt="<?php esc_html_e('4 column thumnbnails', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-columns-04.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('5 column thumnbnails', 'maxgalleria') ?>" alt="<?php esc_html_e('5 column thumnbnails', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-columns-05.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('6 column thumnbnails', 'maxgalleria') ?>" alt="<?php esc_html_e('6 column thumnbnails', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-columns-06.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('7 column thumnbnails', 'maxgalleria') ?>" alt="<?php esc_html_e('7 column thumnbnails', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-columns-07.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('8 column thumnbnails', 'maxgalleria') ?>" alt="<?php esc_html_e('8 column thumnbnails', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-columns-08.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('9 column thumnbnails', 'maxgalleria') ?>" alt="<?php esc_html_e('9 column thumnbnails', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-columns-09.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('10 column thumnbnails', 'maxgalleria') ?>" alt="<?php esc_html_e('10 column thumnbnails', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-columns-10.png') ?>">
							</td>
						</tr>
					</table>
				</td>																
			</tr>
			<tr>
				<td><?php esc_html_e('Thumbnail Shape:', 'maxgalleria') ?></td>
				<td>
				  <table class="mg-settings">
						<tr>
							<td class="mg-radio">
								<input data-default="<?php echo esc_html($options->thumb_shape_default) ?>"  id="thumbnail-shape-default" type="radio" name="<?php echo esc_html($options->thumb_shape_default_key) ?>" value="landscape" class="thumbnail-shape-type" <?php echo esc_attr(($options->get_thumb_shape_default() === 'landscape') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->thumb_shape_default_key) ?>" value="portrait" class="thumbnail-shape-type" <?php echo esc_attr(($options->get_thumb_shape_default() === 'portrait') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->thumb_shape_default_key) ?>" value="square" class="thumbnail-shape-type" <?php echo esc_attr(($options->get_thumb_shape_default() === 'square') ? 'checked' : ''); ?>>
							</td>
						</tr>
						<tr>
							<td>
								<img title="<?php esc_html_e('Landscape thumnbnail shape', 'maxgalleria') ?>" alt="<?php esc_html_e('landscape thumnbnail shape', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-shape-landscape.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('Portrait thumnbnail shape', 'maxgalleria') ?>" alt="<?php esc_html_e('portrait thumnbnail shape', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-shape-portrait.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('square thumnbnail shape', 'maxgalleria') ?>" alt="<?php esc_html_e('square thumnbnail shape', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-shape-square.png') ?>">
							</td>
						</tr>
					</table>
				</td>																
			</tr>
			<tr>
				<td><?php esc_html_e('Thumbnail Captions Enabled:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo esc_html($options->thumb_caption_enabled_default) ?>" type="checkbox" id="<?php echo esc_html($options->thumb_caption_enabled_default_key) ?>" name="<?php echo esc_html($options->thumb_caption_enabled_default_key) ?>" <?php echo esc_attr(($options->get_thumb_caption_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e('Thumbnail Captions Position:', 'maxgalleria') ?></td>
				<td>
				  <table class="mg-settings">
						<tr>
							<td class="mg-radio">
								<input data-default="<?php echo esc_html($options->thumb_caption_position_default) ?>"  id="caption-position-default" type="radio" name="<?php echo esc_html($options->thumb_caption_position_default_key) ?>" value="below" class="caption-position-type" <?php echo esc_attr(($options->get_thumb_caption_position_default() === 'below') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->thumb_caption_position_default_key) ?>" value="bottom" class="caption-position-type" <?php echo esc_attr(($options->get_thumb_caption_position_default() === 'bottom') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->thumb_caption_position_default_key) ?>" value="above" class="caption-position-type" <?php echo esc_attr(($options->get_thumb_caption_position_default() === 'above') ? 'checked' : ''); ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo esc_html($options->thumb_caption_position_default_key) ?>" value="center" class="caption-position-type" <?php echo esc_attr(($options->get_thumb_caption_position_default() === 'center') ? 'checked' : ''); ?>>
							</td>
						</tr>
						<tr>
							<td>
								<img title="<?php esc_html_e('Below Image caption', 'maxgalleria') ?>" alt="<?php esc_html_e('below image caption', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-captions-below.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('Bottom of Image caption', 'maxgalleria') ?>" alt="<?php esc_html_e('bottom of image caption', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-captions-bottom.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('Above Image caption', 'maxgalleria') ?>" alt="<?php esc_html_e('above image caption', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-captions-above.png') ?>">
							</td>
							<td>
								<img title="<?php esc_html_e('Center of Image caption', 'maxgalleria') ?>" alt="<?php esc_html_e('center of image caption', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-captions-center.png') ?>">
							</td>
						</tr>
					</table>
				</td>																
			</tr>
			<tr>
				<td><?php esc_html_e('Thumbnail Click Opens:', 'maxgalleria') ?></td>
				<td>
					<select data-default="<?php echo esc_html($options->thumb_click_default) ?>" id="<?php echo esc_html($options->thumb_click_default_key) ?>" name="<?php echo esc_html($options->thumb_click_default_key) ?>">
					<?php foreach ($options->thumb_clicks as $key => $name) { ?>
						<?php $selected = ($options->get_thumb_click_default() == $key) ? 'selected=selected' : ''; ?>
						<option value="<?php echo esc_attr($key) ?>" <?php echo esc_attr($selected) ?>><?php echo esc_html($name) ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e('Thumbnail Custom Image Class:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo esc_html($options->thumb_image_class_default) ?>" type="text" id="<?php echo esc_html($options->thumb_image_class_default_key) ?>" name="<?php echo esc_html($options->thumb_image_class_default_key) ?>" value="<?php echo esc_html($options->get_thumb_image_class_default()) ?>" />
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e('Thumbnail Custom Image Container Class:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo esc_html($options->thumb_image_container_class_default) ?>" type="text" id="<?php echo esc_html($options->thumb_image_container_class_default_key) ?>" name="<?php echo esc_html($options->thumb_image_container_class_default_key) ?>" value="<?php echo esc_html($options->get_thumb_image_container_class_default()) ?>" />
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e('Number of Images Per Page:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo esc_html($options->images_per_page_default); ?>" type="text" class="small" id="<?php echo esc_html($options->images_per_page_default_key) ?>" name="<?php echo esc_html($options->images_per_page_default_key) ?>" value="<?php echo esc_html($options->get_images_per_page_default()) ?>" />
				</td>
			</tr>
      
			<tr>
				<td><?php esc_html_e('Display Images by:', 'maxgalleria') ?></td>
				<td>
					<select data-default="<?php echo esc_html($options->sort_type_default) ?>" id="<?php echo esc_html($options->sort_type_default_key) ?>" name="<?php echo esc_html($options->sort_type_default_key) ?>">
					<?php foreach ($options->sort_by as $key => $name) { ?>
						<?php $selected = ($options->get_sort_type_default() == $key) ? 'selected=selected' : ''; ?>
						<option value="<?php echo esc_attr($key) ?>" <?php echo esc_attr($selected) ?>><?php echo esc_html($name) ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>      
      
			<tr>
				<td><?php esc_html_e('Image display order:', 'maxgalleria') ?></td>
				<td>
					<select data-default="<?php echo esc_html($options->sort_order_default) ?>" id="<?php echo esc_html($options->sort_order_default_key) ?>" name="<?php echo esc_html($options->sort_order_default_key) ?>">
					<?php foreach ($options->sort_orders as $key => $name) { ?>
						<?php $selected = ($options->get_sort_order_default() == $key) ? 'selected=selected' : ''; ?>
						<option value="<?php echo esc_attr($key) ?>" <?php echo esc_attr($selected) ?>><?php echo esc_html($name) ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>

			<tr><td colspan="2" class="options-heading"><span class="mg-heading"><?php esc_html_e('LIGHTBOX SETTINGS', 'maxgalleria') ?></span></td><td></td></tr>            
      <?php if(class_exists('Responsive_Lightbox')) { ?>    
        <tr>
          <td><?php esc_html_e('Use dFactory&#39;s Resposive Lightbox:', 'maxgalleria') ?></td>
          <td>
            <input data-default="<?php echo esc_html($options->dfactory_lightbox_default) ?>" type="checkbox" id="<?php echo esc_html($options->dfactory_lightbox_default_key) ?>" name="<?php echo esc_html($options->dfactory_lightbox_default_key) ?>" <?php echo esc_attr(($options->get_dfactory_lightbox_default() == 'on') ? 'checked' : '') ?> />
          </td>
        </tr>  
				<tr>
          <td class="mg-italic" colspan = "2"><?php esc_html_e('Set "Thumbnail Click Opens" to "Original Image" or "Image Link" when using this option.</span>', 'maxgalleria') ?></td>					
				</tr>
      <?php } ?>
        
			<tr>
				<td><?php esc_html_e('Lightbox Captions Enabled:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo esc_html($options->lightbox_caption_enabled_default) ?>" type="checkbox" id="<?php echo esc_html($options->lightbox_caption_enabled_default_key) ?>" name="<?php echo esc_html($options->lightbox_caption_enabled_default_key) ?>" <?php echo esc_attr(($options->get_lightbox_caption_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
			</tr>       
			<tr>
				<td class="padding-top"><?php esc_html_e('Lightbox Skin:', 'maxgalleria') ?></td>
				<td class="padding-top">
					<select data-default="<?php echo esc_html($options->lightbox_skin_default) ?>" id="<?php echo esc_html($options->lightbox_skin_default_key) ?>" name="<?php echo esc_html($options->lightbox_skin_default_key) ?>">
					<?php foreach ($options->lightbox_skins as $key => $name) { ?>
						<?php $selected = ($options->get_lightbox_skin_default() == $key) ? 'selected=selected' : ''; ?>
						<option value="<?php echo esc_attr($key) ?>" <?php echo esc_attr($selected) ?>><?php echo esc_html($name) ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>      
      <tr>
        <td class="padding-top"><?php esc_html_e('Lightbox Opening CSS Transition Effect:', 'maxgalleria') ?></td>
        <td class="padding-top">
          <select id="<?php echo esc_html($options->lightbox_effect_default_key) ?>" name="<?php echo esc_html($options->lightbox_effect_default_key) ?>">
          <?php foreach ($options->lightbox_effects as $key => $name) { ?>
            <?php $selected = ($options->get_lightbox_effect_default() == $key) ? 'selected=selected' : ''; ?>
            <option value="<?php echo esc_attr($key) ?>" <?php echo esc_attr($selected) ?>><?php echo esc_html($name) ?></option>
          <?php } ?>
          </select>
        </td>
      </tr>      
			<tr>
				<td><?php esc_html_e('Lightbox Keyboard Navigation:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo esc_html($options->lightbox_kb_nav_default) ?>" type="checkbox" id="<?php echo esc_html($options->lightbox_kb_nav_default_key) ?>" name="<?php echo esc_html($options->lightbox_kb_nav_default_key) ?>" <?php echo esc_attr(($options->get_lightbox_kb_nav_default() == 'on') ? 'checked' : '') ?> />
				</td>
			</tr>           
			<tr>
				<td><?php esc_html_e('Close Lightbox on Image Click:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo esc_html($options->lightbox_img_click_close_default) ?>" type="checkbox" id="<?php echo esc_html($options->lightbox_img_click_close_default_key) ?>" name="<?php echo esc_html($options->lightbox_img_click_close_default_key) ?>" <?php echo esc_attr(($options->get_lightbox_img_click_close_default() == 'on') ? 'checked' : '') ?> />
				</td>
			</tr>           
			<tr>
				<td><?php esc_html_e('Close Button Tool Tip Text:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo esc_html($options->lightbox_close_text_default) ?>" type="text" id="<?php echo esc_html($options->lightbox_close_text_default_key) ?>" name="<?php echo esc_html($options->lightbox_close_text_default_key) ?>" value="<?php echo esc_html($options->get_lightbox_close_text_default()) ?>" />
				</td>
			</tr>      
			<tr>
				<td><?php esc_html_e('Next Button Tool Tip Text:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo esc_html($options->lightbox_next_text_default) ?>" type="text" id="<?php echo esc_html($options->lightbox_next_text_default_key) ?>" name="<?php echo esc_html($options->lightbox_next_text_default_key) ?>" value="<?php echo esc_html($options->get_lightbox_next_text_default()) ?>" />
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e('Previous Button Tool Tip Text:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo esc_html($options->lightbox_prev_text_default) ?>" type="text" id="<?php echo esc_html($options->lightbox_prev_text_default_key) ?>" name="<?php echo esc_html($options->lightbox_prev_text_default_key) ?>" value="<?php echo esc_html($options->get_lightbox_prev_text_default()) ?>" />
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e('Unable to Load Content Message:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo esc_html($options->lightbox_error_text_default) ?>" type="text" class="wide" id="<?php echo esc_html($options->lightbox_error_text_default_key) ?>" name="<?php echo esc_html($options->lightbox_error_text_default_key) ?>" value="<?php echo esc_html($options->get_lightbox_error_text_default()) ?>" />
				</td>
			</tr>
      
			<tr>
				<td><?php _e('Lightbox Close Icon:', 'maxgalleria') ?></td>
				<td>
					<table id="close-table">
						<tr>
							<td class="mg-radio">
								<input id="close-button-default" data-default="<?php echo $options->ns_lightbox_close_default ?>"  type="radio" name="<?php echo $options->ns_lightbox_close_default_key ?>" value="0" class="close-button" <?php echo ($options->get_lightbox_close_default() === '0') ? 'checked' : ''; ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo $options->ns_lightbox_close_default_key ?>" value="1" class="close-button" <?php echo ($options->get_lightbox_close_default() === '1') ? 'checked' : ''; ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo $options->ns_lightbox_close_default_key ?>" value="2" class="close-button" <?php echo ($options->get_lightbox_close_default() === '2') ? 'checked' : ''; ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo $options->ns_lightbox_close_default_key ?>" value="3" class="close-button" <?php echo ($options->get_lightbox_close_default() === '3') ? 'checked' : ''; ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo $options->ns_lightbox_close_default_key ?>" value="4" class="close-button" <?php echo ($options->get_lightbox_close_default() === '4') ? 'checked' : ''; ?>>
							</td>
						</tr>	
						<tr style="background-color:#3C3C3C">
							<td>
								<img alt="close style 0" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/close-style-0-wt.png" >
							</td>
							<td>
								<img alt="close style 1" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/close-style-1-wt.png" >
							</td>
							<td>
								<img alt="close style 2" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/close-style-2-wt.png" >
							</td>
							<td>
								<img alt="close style 3" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/close-style-3-wt.png" >
							</td>
							<td>
								<img alt="close style 4" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/close-style-4-wt.png" >
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan = "2">&nbsp;</td>
			</tr>
			
			<tr>
				<td><?php _e('Lightbox Arrows:', 'maxgalleria') . "value: " . $options->get_lightbox_arrow_default(); ?></td>
				<td>
					<table id="arrow-table">
						<tr>
							<td class="mg-radio">
								<input id="default-arrow-type" data-default="<?php echo $options->ns_lightbox_arrow_default ?>" type="radio" name="<?php echo $options->ns_lightbox_arrow_default_key ?>" value="0" class="close-button" <?php echo ($options->get_lightbox_arrow_default() === '0') ? 'checked' : ''; ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo $options->ns_lightbox_arrow_default_key ?>" value="1" class="close-button" <?php echo ($options->get_lightbox_arrow_default() === '1') ? 'checked' : ''; ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo $options->ns_lightbox_arrow_default_key ?>" value="2" class="close-button" <?php echo ($options->get_lightbox_arrow_default() === '2') ? 'checked' : ''; ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo $options->ns_lightbox_arrow_default_key ?>" value="3" class="close-button" <?php echo ($options->get_lightbox_arrow_default() === '3') ? 'checked' : ''; ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo $options->ns_lightbox_arrow_default_key ?>" value="4" class="close-button" <?php echo ($options->get_lightbox_arrow_default() === '4') ? 'checked' : ''; ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo $options->ns_lightbox_arrow_default_key ?>" value="5" class="close-button" <?php echo ($options->get_lightbox_arrow_default() === '5') ? 'checked' : ''; ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo $options->ns_lightbox_arrow_default_key ?>" value="6" class="close-button" <?php echo ($options->get_lightbox_arrow_default() === '6') ? 'checked' : ''; ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo $options->ns_lightbox_arrow_default_key ?>" value="7" class="close-button" <?php echo ($options->get_lightbox_arrow_default() === '7') ? 'checked' : ''; ?>>
							</td>
						</tr>	
						<tr style="background-color:#3C3C3C">
							<td>
								<img class="mg-float" alt="arrow style 0" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/arrow-style-0l-wt.png" >
								<img class="mg-float" alt="arrow style 0" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/arrow-style-0r-wt.png" >
							</td>
							<td>
								<img class="mg-float" alt="arrow style 1" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/arrow-style-1l-wt.png" >
								<img class="mg-float" alt="arrow style 1" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/arrow-style-1r-wt.png" >
							</td>
							<td>
								<img class="mg-float" alt="arrow style 2" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/arrow-style-2l-wt.png" >
								<img class="mg-float" alt="arrow style 2" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/arrow-style-2r-wt.png" >
							</td>
							<td>
								<img class="mg-float" alt="arrow style 3" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/arrow-style-3l-wt.png" >
								<img class="mg-float" alt="arrow style 3" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/arrow-style-3r-wt.png" >
							</td>
							<td>
								<img class="mg-float" alt="arrow style 4" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/arrow-style-4l-wt.png" >
								<img class="mg-float" alt="arrow style 4" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/arrow-style-4r-wt.png" >
							</td>
							<td>
								<img class="mg-float" alt="arrow style 5" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/arrow-style-5l-wt.png" >
								<img class="mg-float" alt="arrow style 5" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/arrow-style-5r-wt.png" >
							</td>
							<td>
								<img class="mg-float" alt="arrow style 6" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/arrow-style-6l-wt.png" >
								<img class="mg-float" alt="arrow style 6" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/arrow-style-6r-wt.png" >
							</td>
							<td>
								<img class="mg-float" alt="arrow style 7" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/arrow-style-7l-wt.png" >
								<img class="mg-float" alt="arrow style 7" src="<?php echo MAXGALLERIA_PLUGIN_URL ?>/images/icons/arrow-style-7r-wt.png" >
							</td>
						</tr>
					</table>
				</td>
			</tr>
                    
			<tr><td colspan="2" class="options-heading"><span class="mg-heading"><?php esc_html_e('GALLERY SETTINGS', 'maxgalleria') ?></span></td><td></td></tr>            
      <tr>
				<td><?php esc_html_e('Gallery Enabled Displays previous and next navigation arrows:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo esc_html($options->gallery_enabled_default) ?>" type="checkbox" id="<?php echo esc_html($options->gallery_enabled_default_key) ?>" name="<?php echo esc_html($options->gallery_enabled_default_key) ?>" <?php echo esc_attr(($options->get_gallery_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
      </tr>  
			<tr><td colspan="2" class="options-heading"><span class="mg-heading"><?php esc_html_e('ADVANCED SETTINGS', 'maxgalleria') ?></span></td><td></td></tr>
			<tr><td colspan="2" class="padding-top"><span class="mg-bold"><?php esc_html_e('Thumbnail Options', 'maxgalleria') ?></span></td></tr>
      <tr>
				<td>
					<?php esc_html_e('Lazy Load Enabled:', 'maxgalleria') ?>
				</td>	
				<td>
					<input data-default="<?php echo esc_html($options->lazy_load_enabled_default) ?>" type="checkbox" id="<?php echo esc_html($options->lazy_load_enabled_default_key) ?>" name="<?php echo esc_html($options->lazy_load_enabled_default_key) ?>" <?php echo esc_attr(($options->get_lazy_load_enabled_default() == 'on') ? 'checked' : '') ?> />
				</td>
      </tr>
			<tr>
				<td class="mg-italic" colspan = "2"><?php esc_html_e('Lazy Loading allows for faster page loading times and is enabled by default for a better user experience. But you can turn it off if individual images in your gallery are not loading fast enough.', 'maxgalleria') ?></td>				
			</tr>
			<tr>
				<td>
					<?php esc_html_e('Lazy Load Threshold (Pixels):', 'maxgalleria') ?>
				</td>
				<td>
					<input data-default="<?php echo esc_html($options->lazy_load_threshold_default); ?>" type="text" class="small" id="<?php echo esc_html($options->lazy_load_threshold_default_key) ?>" name="<?php echo esc_html($options->lazy_load_threshold_default_key) ?>" value="<?php echo esc_html($options->get_lazy_load_threshold_default()) ?>" />
				</td>
			</tr>
			<tr>
				<td class="mg-italic" colspan = "2"><?php esc_html_e('Lazy Load Threshold is the number of pixels above an image before it starts loading as the user scrolls down your page.  We set the default to 50 pixels.  If you find you want your images to start loading sooner increase the number of pixels for the threshold.', 'maxgalleria') ?></td>				
			</tr>
			<tr>
				<td><?php esc_html_e('Thumbnail Custom Rel Attribute:', 'maxgalleria') ?></td>
				<td>
					<input data-default="<?php echo esc_html($options->thumb_image_rel_attribute_default) ?>" type="text" id="<?php echo esc_html($options->thumb_image_rel_attribute_default_key) ?>" name="<?php echo esc_html($options->thumb_image_rel_attribute_default_key) ?>" value="<?php echo esc_html($options->get_thumb_image_rel_attribute_default()) ?>" />
				</td>
			</tr>
			            
		</table>

		
		<?php wp_nonce_field($options->nonce_save_image_tiles_defaults['action'], $options->nonce_save_image_tiles_defaults['name']) ?>
	</form>
</div>

<a id="save-image-tiles-settings" href="#" class="button button-primary"><?php esc_html_e('Save Settings', 'maxgalleria') ?></a>
<a id="revert-image-tiles-defaults" href="#" class="button" style="margin-left: 10px;"><?php esc_html_e('Revert Defaults', 'maxgalleria') ?></a>
<script>
  jQuery(document).ready(function() {
    		
	  jQuery(document).on("change", ".ns-shadow-type", function () {						
			var shadow_type = this.value
			if(shadow_type === 'color') {
				jQuery("#<?php echo esc_html($options->ns_shadow_color_default_key) ?>").prop('disabled', false);
			  jQuery("#<?php echo esc_html($options->ns_shadow_color_default_key . '2') ?>").prop('disabled', false);
			} else {
				jQuery("#<?php echo esc_html($options->ns_shadow_color_default_key) ?>").prop('disabled', 'disabled');
			  jQuery("#<?php echo esc_html($options->ns_shadow_color_default_key . '2') ?>").prop('disabled', 'disabled');
			}	
			if(shadow_type === 'none') {
				jQuery(".ns-blur-type").prop('disabled', 'disabled');
				jQuery(".ns-spread-type").prop('disabled', 'disabled');
				jQuery("#<?php echo esc_html($options->ns_shadow_color_default_key) ?>").prop('disabled', 'disabled');
			  jQuery("#<?php echo esc_html($options->ns_shadow_color_default_key . '2') ?>").prop('disabled', 'disabled');
			} else {
				jQuery(".ns-blur-type").prop('disabled', false);
				jQuery(".ns-spread-type").prop('disabled', false);
			}	
			
    });   
		
		if(jQuery('#shadow-default').is(':checked')) {
			jQuery(".ns-blur-type").prop('disabled', 'disabled');
			jQuery(".ns-spread-type").prop('disabled', 'disabled');
		} else {
			jQuery(".ns-blur-type").prop('disabled', false);
			jQuery(".ns-spread-type").prop('disabled', false);		
		}
		
				
    jQuery(document).on("click", "#<?php echo esc_html($options->ns_border_color_default_key . '2') ?>", function() {
			if(!jQuery("#<?php echo esc_html($options->ns_border_color_default_key) ?>").prop('disabled')) {				
		    //jQuery("#<?php echo esc_html($options->ns_border_color_default_key) ?>").click();
        jQuery("#<?php echo esc_html($options->ns_border_color_default_key) ?>").trigger("click");        
			}	
	  });  
		
    jQuery(document).on("click", "#<?php echo esc_html($options->ns_shadow_color_default_key . '2') ?>", function() {
			if(!jQuery("#<?php echo esc_html($options->ns_shadow_color_default_key) ?>").prop('disabled')) {				
		    //jQuery("#<?php echo esc_html($options->ns_shadow_color_default_key) ?>").click();
        jQuery("#<?php echo esc_html($options->ns_shadow_color_default_key) ?>").trigger("click");
		  }
	  });  
		
		if(jQuery('#shadow-color-option').is(':checked')) {
			jQuery("#<?php echo esc_html($options->ns_shadow_color_default_key) ?>").prop('disabled', false);
		} else {
			jQuery("#<?php echo esc_html($options->ns_shadow_color_default_key) ?>").prop('disabled', 'disabled');
		}	
		
    jQuery(document).on("click", "#<?php echo esc_html($options->ns_show_border_default_key) ?>", function() {
			if(this.checked) {
			  jQuery("#<?php echo esc_html($options->ns_border_color_default_key) ?>").prop('disabled', false);
			  jQuery(".border-thickness").prop('disabled', false);
			  jQuery(".border-radius").prop('disabled', false);				
			} else {
			  jQuery("#<?php echo esc_html($options->ns_border_color_default_key) ?>").prop('disabled', 'disabled');
			  jQuery(".border-thickness").prop('disabled', 'disabled');
			  jQuery(".border-radius").prop('disabled', 'disabled');
			}	
		});		
		
		if(jQuery('#<?php echo esc_html($options->ns_show_border_default_key) ?>').is(':checked')) {
			jQuery("#<?php echo esc_html($options->ns_border_color_default_key) ?>").prop('disabled', false);
			jQuery(".border-thickness").prop('disabled', false);
			jQuery(".border-radius").prop('disabled', false);				
		} else {
			jQuery("#<?php echo esc_html($options->ns_border_color_default_key) ?>").prop('disabled', 'disabled');
			jQuery(".border-thickness").prop('disabled', 'disabled');
			jQuery(".border-radius").prop('disabled', 'disabled');
		}
		
    jQuery(document).on("click", "#<?php echo esc_html($options->thumb_caption_enabled_default_key) ?>", function() {
			if(this.checked) {
			  jQuery(".caption-position-type").prop('disabled', false);				
			} else {
			  jQuery(".caption-position-type").prop('disabled', 'disabled');				
			}	
		});		
		
		
		if(jQuery('#<?php echo esc_html($options->thumb_caption_enabled_default_key) ?>').is(':checked')) {
			jQuery(".caption-position-type").prop('disabled', false);
		} else {
			jQuery(".caption-position-type").prop('disabled', 'disabled');
		}	
								  
	});  
</script>  