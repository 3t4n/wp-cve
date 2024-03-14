<?php
global $post;
$options = new MaxGalleriaImageTilesOptions($post->ID);
?>

<script type="text/javascript">		
	jQuery(document).ready(function() {
    		
		jQuery('#<?php echo esc_html($options->ns_border_color_key) ?>').colpick({
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
		        
		jQuery('#<?php echo esc_html($options->ns_shadow_color_key) ?>').colpick({
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
				        
	});
	
	
	function enableDisableThumbClickNewWindow() {
		var thumb_click = jQuery("#<?php echo esc_html($options->thumb_click_key) ?>").val();
		
		if (thumb_click === "lightbox") {
			jQuery("#<?php echo esc_html($options->thumb_click_new_window_key) ?>").attr("disabled", "disabled");
			jQuery("#<?php echo esc_html($options->thumb_click_new_window_key) ?>").removeAttr("checked");
		}
		else {
			jQuery("#<?php echo esc_html($options->thumb_click_new_window_key) ?>").removeAttr("disabled");
		}
    
		if (thumb_click !== "lightbox") {
			jQuery(".mag-popup-settings").attr("disabled", "disabled");
    } else {
			jQuery(".mag-popup-settings").removeAttr("disabled");
    }  
	}
    
</script>

<div class="meta-options">
	<table>
		<tr>
			<td colspan="2" class="options-heading"><span class="mg-heading"><?php esc_html_e('GALLERY STYLES', 'maxgalleria') ?></span></td><td></td>
		</tr>
		<tr>
			<td class="padding-top">
				<?php esc_html_e('Preset Layouts:', 'maxgalleria') ?>
			</td>
			<td class="padding-top">
		    <?php if($options->hide_presets === 'off') 
				  $skins = array_merge($options->new_skins, $options->skins );
					  else
					$skins = $options->new_skins;
		     ?>
				<select id="<?php echo esc_html($options->skin_key) ?>" name="<?php echo esc_html($options->skin_key) ?>">
				<?php foreach ($skins as $key => $name) { ?>
					<?php $selected = ($options->get_skin() == $key) ? 'selected=selected' : ''; ?>
					<option value="<?php echo esc_attr($key) ?>" <?php echo esc_attr($selected) ?>><?php echo esc_html($name) ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="<?php echo esc_html($options->ns_show_border_key) ?>"><?php esc_html_e('Display Border:', 'maxgalleria') ?></label>
			</td>
			<td>
				<input type="checkbox" id="<?php echo esc_html($options->ns_show_border_key) ?>" name="<?php echo esc_html($options->ns_show_border_key) ?>" <?php echo esc_attr(($options->get_show_border() == 'on') ? 'checked' : '') ?> />
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e('Border Thickness:', 'maxgalleria') ?>
			</td>
			<td>
				<table class="mg-settings">
					<tr>
						<td class="mg-radio">
							<input id="border-thickness-default" type="radio" name="<?php echo esc_html($options->ns_border_thickness_key) ?>" value="1" class="border-thickness" <?php echo esc_attr(($options->get_border_thickness() === '1') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_border_thickness_key) ?>" value="3" class="border-thickness" <?php echo esc_attr(($options->get_border_thickness() === '3') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_border_thickness_key) ?>" value="5" class="border-thickness" <?php echo esc_attr(($options->get_border_thickness() === '5') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_border_thickness_key) ?>" value="7" class="border-thickness" <?php echo esc_attr(($options->get_border_thickness() === '7') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_border_thickness_key) ?>" value="9" class="border-thickness" <?php echo esc_attr(($options->get_border_thickness() === '9') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_border_thickness_key) ?>" value="15" class="border-thickness" <?php echo esc_attr(($options->get_border_thickness() === '15') ? 'checked' : ''); ?>>
						</td>
					</tr>	
					<tr>
						<td>
							<img title="<?php esc_html_e('1 pixel', 'maxgalleria') ?>" alt="<?php esc_html_e('border thickness 1 pixel', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/border-01.png') ?>" >
						</td>
						<td>
							<img title="<?php esc_html_e('3 pixels', 'maxgalleria') ?>" alt="<?php esc_html_e('border thickness 3 pixels', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/border-03.png') ?>" >
						</td>
						<td>
							<img title="<?php esc_html_e('5 pixels', 'maxgalleria') ?>" alt="<?php esc_html_e('border thickness 5 pixels', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/border-05.png') ?>" >
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
		<tr id="border-color-row">
			<td>
				<?php esc_html_e('Border Color:', 'maxgalleria') ?>
			</td>
			<td>
				<?php 				
				$border_color = get_post_meta($post->ID, $options->ns_border_color_key, true);				
				if($border_color === '')
          $border_color = get_option($options->ns_border_color_default_key, $options->ns_border_color_default );	
				?>
				<img id="<?php echo esc_html($options->ns_border_color_key . '2') ?>" class="left" alt="border color button" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/color.png') ?>">
				<input class="color-input" type="text" id="<?php echo esc_html($options->ns_border_color_key) ?>" name="<?php echo esc_html($options->ns_border_color_key) ?>" value="<?php echo esc_html($border_color) ?>" />
			</td>
		</tr>
		
		<tr>
			<td>
				<?php esc_html_e('Border Radius:', 'maxgalleria') ?>
			</td>
			<td>
				<table class="mg-settings">
					<tr>
						<td class="mg-radio">
							<input type="radio" id="border-radius-default" name="<?php echo esc_html($options->ns_border_radius_key) ?>" value="0" class="border-radius" <?php echo esc_attr(($options->get_border_radius() === '0') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_border_radius_key) ?>" value="10" class="border-radius" <?php echo esc_attr(($options->get_border_radius() === '10') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_border_radius_key) ?>" value="20" class="border-radius" <?php echo esc_attr(($options->get_border_radius() === '20') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_border_radius_key) ?>" value="30" class="border-radius" <?php echo esc_attr(($options->get_border_radius() === '30') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_border_radius_key) ?>" value="40" class="border-radius" <?php echo esc_attr(($options->get_border_radius() === '40') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_border_radius_key) ?>" value="50" class="border-radius" <?php echo esc_attr(($options->get_border_radius() === '50') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_border_radius_key) ?>" value="60" class="border-radius" <?php echo esc_attr(($options->get_border_radius() === '60') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_border_radius_key) ?>" value="70" class="border-radius" <?php echo esc_attr(($options->get_border_radius() === '70') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_border_radius_key) ?>" value="80" class="border-radius" <?php echo esc_attr(($options->get_border_radius() === '80') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_border_radius_key) ?>" value="90" class="border-radius" <?php echo esc_attr(($options->get_border_radius() === '90') ? 'checked' : ''); ?>>
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
							<img title="<?php esc_html_e('20 pixel border radius', 'maxgalleria') ?>" alt="<?php esc_html_e('20 pixel border radius', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/border-radius-20.png') ?>" >
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
			<td>
				<?php esc_html_e('Shadow Type:', 'maxgalleria') ?>
			</td>
			<td>
				<table class="mg-settings">
					<tr>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_shadow_key) ?>" value="none" class="ns-shadow-type" <?php echo esc_attr(($options->get_shadow() === 'none') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_shadow_key) ?>" value="inside" class="ns-shadow-type" <?php echo esc_attr(($options->get_shadow() === 'inside') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_shadow_key) ?>" value="behind" class="ns-shadow-type" <?php echo esc_attr(($options->get_shadow() === 'behind') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" id="shadow-color-option" name="<?php echo esc_html($options->ns_shadow_key) ?>" value="color" class="ns-shadow-type" <?php echo esc_attr(($options->get_shadow() === 'color') ? 'checked' : ''); ?>>
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
		<tr id="shadow-color-row">
			<td>
				<?php esc_html_e('Shadow Color:', 'maxgalleria') ?>
			</td>
			<td>
				<?php 				
				$shadow_color = get_post_meta($post->ID, $options->ns_shadow_color_key, true);				
				if($shadow_color === '')
          $shadow_color = get_option($options->ns_shadow_color_default_key, $options->ns_shadow_color_default );	
				?>
				<img id="<?php echo esc_html($options->ns_shadow_color_key . '2') ?>" class="left" alt="border color button" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/color.png') ?>">
				<input class="color-input" type="text" id="<?php echo esc_html($options->ns_shadow_color_key) ?>" name="<?php echo esc_html($options->ns_shadow_color_key) ?>" value="<?php echo esc_html($shadow_color) ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e('Shadow Blur:', 'maxgalleria') ?>
			</td>
			<td>
				<table class="mg-settings">
					<tr>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_shadow_blur_key) ?>" value="5" class="ns-blur-type" <?php echo esc_attr(($options->get_shadow_blur() === '5') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_shadow_blur_key) ?>" value="10" class="ns-blur-type" <?php echo esc_attr(($options->get_shadow_blur() === '10') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_shadow_blur_key) ?>" value="15" class="ns-blur-type" <?php echo esc_attr(($options->get_shadow_blur() === '15') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_shadow_blur_key) ?>" value="20" class="ns-blur-type" <?php echo esc_attr(($options->get_shadow_blur() === '20') ? 'checked' : ''); ?>>
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
			<td>
				<?php esc_html_e('Shadow Spread:', 'maxgalleria') ?>
			</td>
			<td>
				<table class="mg-settings">
					<tr>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_shadow_spread_key) ?>" value="0" class="ns-spread-type" <?php echo esc_attr(($options->get_shadow_spread() === '0') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_shadow_spread_key) ?>" value="1" class="ns-spread-type" <?php echo esc_attr(($options->get_shadow_spread() === '1') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_shadow_spread_key) ?>" value="2" class="ns-spread-type" <?php echo esc_attr(($options->get_shadow_spread() === '2') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->ns_shadow_spread_key) ?>" value="3" class="ns-spread-type" <?php echo esc_attr(($options->get_shadow_spread() === '3') ? 'checked' : ''); ?>>
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
    <tr><td colspan="2" class="options-heading"><span class="mg-heading">THUMBNAIL OPTIONS</span></td></tr>		
		<tr>
			<td>
				<label for="<?php echo esc_html($options->lazy_load_enabled_key) ?>"><?php esc_html_e('Lazy Load Enabled:', 'maxgalleria') ?></label>
			</td>
			<td>
				<input type="checkbox" id="<?php echo esc_html($options->lazy_load_enabled_key) ?>" name="<?php echo esc_html($options->lazy_load_enabled_key) ?>" <?php echo esc_attr(($options->get_lazy_load_enabled() == 'on') ? 'checked' : '') ?> />
			</td>
		</tr>
		<tr>
			<td class="mg-italic" colspan = "2"><?php esc_html_e('Lazy Loading allows for faster page loading times and is enabled by default for a better user experience.', 'maxgalleria') ?></td>			
		</tr>
		<tr>
			<td>
				<?php esc_html_e('Lazy Load Threshold (0 for off or 1 for on):', 'maxgalleria') ?>
			</td>
			<td>
				<input type="text" class="small" id="<?php echo esc_html($options->lazy_load_threshold_key) ?>" name="<?php echo esc_html($options->lazy_load_threshold_key) ?>" value="<?php echo esc_html($options->get_lazy_load_threshold()) ?>" />
			</td>
		</tr>
		<tr>
			<td class="padding-top">
						<?php 
						$number_thumb_columns = $options->get_thumb_columns(); 
						if($number_thumb_columns == '')
							$number_thumb_columns = strval($options->thumb_columns_default);
						?>
				<?php echo esc_html__('Thumbnail Columns: ', 'maxgalleria') ?>
			</td>
			<td class="padding-top">
				<table class="mg-settings">
					<tr>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->thumb_columns_key) ?>" value="1" class="thumbnail-column-type" <?php echo esc_attr(($number_thumb_columns == '1') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->thumb_columns_key) ?>" value="2" class="thumbnail-column-type" <?php echo esc_attr(($number_thumb_columns == '2') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->thumb_columns_key) ?>" value="3" class="thumbnail-column-type" <?php echo esc_attr(($number_thumb_columns == '3') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->thumb_columns_key) ?>" value="4" class="thumbnail-column-type" <?php echo esc_attr(($number_thumb_columns == '4') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->thumb_columns_key) ?>" value="5" class="thumbnail-column-type" <?php echo esc_attr(($number_thumb_columns == '5') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->thumb_columns_key) ?>" value="6" class="thumbnail-column-type" <?php echo esc_attr(($number_thumb_columns == '6') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->thumb_columns_key) ?>" value="7" class="thumbnail-column-type" <?php echo esc_attr(($number_thumb_columns == '7') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->thumb_columns_key) ?>" value="8" class="thumbnail-column-type" <?php echo esc_attr(($number_thumb_columns == '8') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio"> 
							<input type="radio" name="<?php echo esc_html($options->thumb_columns_key) ?>" value="9" class="thumbnail-column-type" <?php echo esc_attr(($number_thumb_columns == '9') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio"> 
							<input type="radio" name="<?php echo esc_html($options->thumb_columns_key) ?>" value="10" class="thumbnail-column-type" <?php echo esc_attr(($number_thumb_columns == '10') ? 'checked' : ''); ?>>
						</td>
					</tr>
					<tr>
						<td>
							<img title="<?php esc_html_e('1 column thumnbnails', 'maxgalleria') ?>" alt="<?php esc_html_e('1 column thumnbnail', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-columns-01.png') ?>">
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
			<td>
				<?php esc_html_e('Thumbnail Shape:', 'maxgalleria') ?>
			</td>
			<td>
				<table class="mg-settings">
					<tr>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->thumb_shape_key) ?>" value="landscape" class="thumbnail-shape-type" <?php echo esc_attr(($options->get_thumb_shape() === 'landscape') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->thumb_shape_key) ?>" value="portrait" class="thumbnail-shape-type" <?php echo esc_attr(($options->get_thumb_shape() === 'portrait') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->thumb_shape_key) ?>" value="square" class="thumbnail-shape-type" <?php echo esc_attr(($options->get_thumb_shape() === 'square') ? 'checked' : ''); ?>>
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
							<img title="<?php esc_html_e('Square thumnbnail shape', 'maxgalleria') ?>" alt="<?php esc_html_e('square thumnbnail shape', 'maxgalleria') ?>" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/options-icons/thumbnail-shape-square.png') ?>">
						</td>
					</tr>
				</table>
			</td>																
		</tr>
		<tr>
			<td>
				<label for="<?php echo esc_html($options->thumb_caption_enabled_key) ?>"><?php esc_html_e('Thumbnail Captions Enabled:', 'maxgalleria') ?></label>
			</td>
			<td>
				<input type="checkbox" id="<?php echo esc_html($options->thumb_caption_enabled_key) ?>" name="<?php echo esc_html($options->thumb_caption_enabled_key) ?>" <?php echo esc_attr(($options->get_thumb_caption_enabled() == 'on') ? 'checked' : '') ?> />
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e('Thumbnail Captions Position:', 'maxgalleria') ?>
			</td>
			<td>
				<table class="mg-settings">
					<tr>
						<td class="mg-radio">
							<input id="default-caption-position" type="radio" name="<?php echo esc_html($options->thumb_caption_position_key) ?>" value="below" class="caption-position-type" <?php echo esc_attr(($options->get_thumb_caption_position() === 'below') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->thumb_caption_position_key) ?>" value="bottom" class="caption-position-type" <?php echo esc_attr(($options->get_thumb_caption_position() === 'bottom') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->thumb_caption_position_key) ?>" value="above" class="caption-position-type" <?php echo esc_attr(($options->get_thumb_caption_position() === 'above') ? 'checked' : ''); ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo esc_html($options->thumb_caption_position_key) ?>" value="center" class="caption-position-type" <?php echo esc_attr(($options->get_thumb_caption_position() === 'center') ? 'checked' : ''); ?>>
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
			<td>
				<?php esc_html_e('Thumbnail Click Opens:', 'maxgalleria') ?>
			</td>
			<td>
				<select id="<?php echo esc_html($options->thumb_click_key) ?>" name="<?php echo esc_html($options->thumb_click_key) ?>">
				<?php foreach ($options->thumb_clicks as $key => $name) { ?>
					<?php $selected = ($options->get_thumb_click() == $key) ? 'selected=selected' : ''; ?>
					<option value="<?php echo esc_attr($key) ?>" <?php echo esc_attr($selected) ?>><?php echo esc_html($name) ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="<?php echo esc_html($options->thumb_click_new_window_key) ?>"><?php esc_html_e('Thumbnail Click New Window:', 'maxgalleria') ?></label>
			</td>
			<td>
				<input type="checkbox" id="<?php echo esc_html($options->thumb_click_new_window_key) ?>" name="<?php echo esc_html($options->thumb_click_new_window_key) ?>" <?php echo esc_attr(($options->get_thumb_click_new_window() == 'on') ? 'checked' : '') ?> />
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e('Thumbnail Custom Image Class:', 'maxgalleria') ?>
			</td>
			<td>
				<input type="text" id="<?php echo esc_html($options->thumb_image_class_key) ?>" name="<?php echo esc_html($options->thumb_image_class_key) ?>" value="<?php echo esc_html($options->get_thumb_image_class()) ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e('Thumbnail Custom Image Container Class:', 'maxgalleria') ?>
			</td>
			<td>
				<input type="text" id="<?php echo esc_html($options->thumb_image_container_class_key) ?>" name="<?php echo esc_html($options->thumb_image_container_class_key) ?>" value="<?php echo esc_html($options->get_thumb_image_container_class()) ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e('Thumbnail Custom Rel Attribute:', 'maxgalleria') ?>
			</td>
			<td>
				<input type="text" id="<?php echo esc_html($options->thumb_image_rel_attribute_key) ?>" name="<?php echo esc_html($options->thumb_image_rel_attribute_key) ?>" value="<?php echo esc_html($options->get_thumb_image_rel_attribute()) ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e('Images Per Page:', 'maxgalleria') ?>
			</td>
			<td>
				<input type="text" class="small" id="<?php echo esc_html($options->images_per_page_key) ?>" name="<?php echo esc_html($options->images_per_page_key) ?>" value="<?php echo esc_html($options->get_images_per_page()) ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e('Display Images by:', 'maxgalleria') ?>
			</td>
			<td>
				<select id="<?php echo esc_html($options->sort_type_key) ?>" name="<?php echo esc_html($options->sort_type_key) ?>">
				<?php foreach ($options->sort_by as $key => $name) { ?>
					<?php $selected = ($options->get_sort_type() == $key) ? 'selected=selected' : ''; ?>
					<option value="<?php echo esc_attr($key) ?>" <?php echo esc_attr($selected) ?>><?php echo esc_html($name) ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>        
		<tr>
			<td>
				<?php esc_html_e('Image display order:', 'maxgalleria') ?>
			</td>
			<td>
				<select id="<?php echo esc_html($options->sort_order_key) ?>" name="<?php echo esc_html($options->sort_order_key) ?>">
				<?php foreach ($options->sort_orders as $key => $name) { ?>
					<?php $selected = ($options->get_sort_order() == $key) ? 'selected=selected' : ''; ?>
					<option value="<?php echo esc_attr($key) ?>" <?php echo esc_attr($selected) ?>><?php echo esc_html($name) ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
    <tr><td colspan="2" class="options-heading"><span class="mg-heading">LIGHTBOX SETTINGS</span></td></tr>    
    <tr>
      <td>
        <label for="<?php echo esc_html($options->lightbox_caption_enabled_key) ?>"><?php esc_html_e('Lightbox Captions Enabled:', 'maxgalleria') ?></label>
      </td>
      <td>
        <input type="checkbox" class="mag-popup-settings" id="<?php echo esc_html($options->lightbox_caption_enabled_key) ?>" name="<?php echo esc_html($options->lightbox_caption_enabled_key) ?>" <?php echo esc_attr(($options->get_lightbox_caption_enabled() == 'on') ? 'checked' : '') ?> />
      </td>
    </tr>
    
    <tr>
			<td class="padding-top">
				<?php esc_html_e('Lightbox Skin:', 'maxgalleria') ?>
			</td>
			<td class="padding-top">
				<select id="<?php echo esc_html($options->lightbox_skin_key) ?>" name="<?php echo esc_html($options->lightbox_skin_key) ?>">
				<?php foreach ($options->lightbox_skins as $key => $name) { ?>
					<?php $selected = ($options->get_lightbox_skin() == $key) ? 'selected=selected' : ''; ?>
					<option value="<?php echo esc_attr($key) ?>" <?php echo esc_attr($selected) ?>><?php echo esc_html($name) ?></option>
				<?php } ?>
				</select>
			</td>
    </tr>
    
    <tr>
			<td class="padding-top">
				<?php esc_html_e('Lightbox Opening CSS Transition Effect:', 'maxgalleria') ?>
			</td>
			<td class="padding-top">
				<select id="<?php echo esc_html($options->lightbox_effect_key) ?>" name="<?php echo esc_html($options->lightbox_effect_key) ?>">
				<?php foreach ($options->lightbox_effects as $key => $name) { ?>
					<?php $selected = ($options->get_lightbox_effect() == $key) ? 'selected=selected' : ''; ?>
					<option value="<?php echo esc_attr($key) ?>" <?php echo esc_attr($selected) ?>><?php echo esc_html($name) ?></option>
				<?php } ?>
				</select>
			</td>
    </tr>
    
    <tr>
      <td>
        <label for="<?php echo esc_html($options->lightbox_kb_nav_key) ?>"><?php esc_html_e('Lightbox Keyboard Navigation:', 'maxgalleria') ?></label>
      </td>
      <td>
        <input type="checkbox" class="mag-popup-settings" id="<?php echo esc_html($options->lightbox_kb_nav_key) ?>" name="<?php echo esc_html($options->lightbox_kb_nav_key) ?>" <?php echo esc_attr(($options->get_lightbox_kb_nav() == 'on') ? 'checked' : '') ?> />
      </td>
    </tr>
    
    <tr>
      <td>
        <label for="<?php echo esc_html($options->lightbox_img_click_close_key) ?>"><?php esc_html_e('Close Lightbox on Image Click:', 'maxgalleria') ?></label>
      </td>
      <td>
        <input type="checkbox" class="mag-popup-settings" id="<?php echo esc_html($options->lightbox_img_click_close_key) ?>" name="<?php echo esc_html($options->lightbox_img_click_close_key) ?>" <?php echo esc_attr(($options->get_lightbox_img_click_close() == 'on') ? 'checked' : '') ?> />
      </td>
    </tr>
    
    <tr>
      <td>
        <label for="<?php echo esc_html($options->lightbox_overlay_click_close_key) ?>"><?php esc_html_e('Close Lightbox on Overlay Click:', 'maxgalleria') ?></label>
      </td>
      <td>
        <input type="checkbox" class="mag-popup-settings" id="<?php echo esc_html($options->lightbox_overlay_click_close_key) ?>" name="<?php echo esc_html($options->lightbox_overlay_click_close_key) ?>" <?php echo esc_attr(($options->get_lightbox_overlay_click_close() == 'on') ? 'checked' : '') ?> />
      </td>
    </tr>    
		<tr>
			<td>
				<?php esc_html_e('Close Button Tool Tip Text:', 'maxgalleria') ?>
			</td>
			<td>
        <?php
          $default_text = $options->get_lightbox_close_text();
          if(empty($default_text))
            $default_text = $options->lightbox_close_text_default;     
        ?>
				<input type="text" id="<?php echo esc_html($options->lightbox_close_text_key) ?>" name="<?php echo esc_html($options->lightbox_close_text_key) ?>" value="<?php echo esc_html($default_text) ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e('Next Button Tool Tip Text:', 'maxgalleria') ?>
			</td>
			<td>
        <?php
          $default_text = $options->get_lightbox_next_text();
          if(empty($default_text))
            $default_text = $options->lightbox_next_text_default;     
        ?>
				<input type="text" id="<?php echo esc_html($options->lightbox_next_text_key) ?>" name="<?php echo esc_html($options->lightbox_next_text_key) ?>" value="<?php echo esc_html($default_text) ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e('Previous Button Tool Tip Text:', 'maxgalleria') ?>
			</td>
			<td>
        <?php
          $default_text = $options->get_lightbox_prev_text();
          if(empty($default_text))
            $default_text = $options->lightbox_prev_text_default;     
        ?>
				<input type="text" id="<?php echo esc_html($options->lightbox_prev_text_key) ?>" name="<?php echo esc_html($options->lightbox_prev_text_key) ?>" value="<?php echo esc_html($default_text) ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<?php esc_html_e('Unable to load content message:', 'maxgalleria') ?>
			</td>
			<td>
        <?php
          $default_text = $options->get_lightbox_error_text();
          if(empty($default_text))
            $default_text = $options->lightbox_error_text_default;     
        ?>
				<input type="text" class="wide" id="<?php echo esc_html($options->lightbox_error_text_key) ?>" name="<?php echo esc_html($options->lightbox_error_text_key) ?>" value="<?php echo esc_html($default_text) ?>" />
			</td>
		</tr>
    
		<tr>
			<td>
				<?php _e('Lightbox Close Icon:', 'maxgalleria') ?>
				<?php 
				  $lightbox_close = $options->get_lightbox_close(); 
					if($lightbox_close == false)
						$lightbox_close = '0';
				?>
			</td>
			<td>
					<table id="close-table">
						<tr>
							<td class="mg-radio">
								<input type="radio" name="<?php echo $options->ns_lightbox_close_key ?>" value="0" <?php echo ($lightbox_close === '0') ? 'checked' : ''; ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo $options->ns_lightbox_close_key ?>" value="1" <?php echo ($lightbox_close === '1') ? 'checked' : ''; ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo $options->ns_lightbox_close_key ?>" value="2" <?php echo ($lightbox_close === '2') ? 'checked' : ''; ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo $options->ns_lightbox_close_key ?>" value="3" <?php echo ($lightbox_close === '3') ? 'checked' : ''; ?>>
							</td>
							<td class="mg-radio">
								<input type="radio" name="<?php echo $options->ns_lightbox_close_key ?>" value="4" <?php echo ($lightbox_close === '4') ? 'checked' : ''; ?>>
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
			<td class="mg-align-top"><?php _e('Lightbox Arrows:', 'maxgalleria') . "value: " . $options->get_lightbox_arrow_default(); ?></td>
			<td>
				<table id="arrow-table">
					<tr>
						<td class="mg-radio">
							<input type="radio" name="<?php echo $options->ns_lightbox_arrow_key ?>" value="0" <?php echo ($options->get_lightbox_arrow() === '0') ? 'checked' : ''; ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo $options->ns_lightbox_arrow_key ?>" value="1" <?php echo ($options->get_lightbox_arrow() === '1') ? 'checked' : ''; ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo $options->ns_lightbox_arrow_key ?>" value="2" <?php echo ($options->get_lightbox_arrow() === '2') ? 'checked' : ''; ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo $options->ns_lightbox_arrow_key ?>" value="3" <?php echo ($options->get_lightbox_arrow() === '3') ? 'checked' : ''; ?>>
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
					</tr>
					
					<tr>
						<td class="mg-radio">
							<input type="radio" name="<?php echo $options->ns_lightbox_arrow_key ?>" value="4" <?php echo ($options->get_lightbox_arrow() === '4') ? 'checked' : ''; ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo $options->ns_lightbox_arrow_key ?>" value="5" <?php echo ($options->get_lightbox_arrow() === '5') ? 'checked' : ''; ?>>
						</td>
						<td class="mg-radio">
							<input type="radio" name="<?php echo $options->ns_lightbox_arrow_key ?>" value="6" <?php echo ($options->get_lightbox_arrow() === '6') ? 'checked' : ''; ?>>
						</td>
							<td class="mg-radio">
							<input type="radio" name="<?php echo $options->ns_lightbox_arrow_key ?>" value="7" <?php echo ($options->get_lightbox_arrow() === '7') ? 'checked' : ''; ?>>
						</td>
					</tr>	
					<tr style="background-color:#3C3C3C">
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
    
    
    
    
       		
    <?php if(class_exists('Responsive_Lightbox')) { ?>    
      <tr>
        <td>
          <label for="<?php echo esc_html($options->dfactory_lightbox_key) ?>"><?php esc_html_e('Use dFactory Responsive Lightbox:', 'maxgalleria') ?></label>
        </td>
        <td>
          <input type="checkbox" id="<?php echo esc_html($options->dfactory_lightbox_key) ?>" name="<?php echo esc_html($options->dfactory_lightbox_key) ?>" <?php echo esc_attr(($options->get_dfactory_lightbox() == 'on') ? 'checked' : '') ?> />
        </td>
      </tr>
			<tr>
	      <td class="mg-italic" colspan = "2"><?php esc_html_e('Set "Thumbnail Click Opens" to "Original Image" or "Image Link" when using this option.</span>', 'maxgalleria') ?></td>
	    </tr>	
    <?php } ?>
		
    <tr><td colspan="2" class="options-heading"><span class="mg-heading">GALLERY OPTIONS</span></td></tr>
		<tr>
			<td class="padding-top">
				<label for="<?php echo esc_html($options->gallery_enabled_key) ?>"><?php esc_html_e('Gallery Enabled (Displays previous and next navigation arrows):', 'maxgalleria') ?></label>
			</td>
			<td class="padding-top">
				<input type="checkbox" class="mag-popup-settings" id="<?php echo esc_html($options->gallery_enabled_key) ?>" name="<?php echo esc_html($options->gallery_enabled_key) ?>" <?php echo esc_attr(($options->get_gallery_enabled() == 'on') ? 'checked' : '') ?> />
			</td>
		</tr>
		<tr>
			<td colspan="2" class="options-heading"><span class="mg-heading"><?php esc_html_e('ADVANCED SETTINGS', 'maxgalleria') ?></span></td><td></td>
		</tr>
		<tr><td class="padding-top"><span class="mg-bold"><?php esc_html_e('Thumbnail Options', 'maxgalleria') ?></span></td></tr>
		<tr>
			<td>
				<label for="<?php echo esc_html($options->lazy_load_enabled_key) ?>"><?php esc_html_e('Lazy Load Enabled:', 'maxgalleria') ?></label>
			</td>
			<td>
				<input type="checkbox" id="<?php echo esc_html($options->lazy_load_enabled_key) ?>" name="<?php echo esc_html($options->lazy_load_enabled_key) ?>" <?php echo esc_attr(($options->get_lazy_load_enabled() == 'on') ? 'checked' : '') ?> />
			</td>
		</tr>
		<tr>
			<td class="mg-italic" colspan = "2"><?php esc_html_e('Lazy Loading allows for faster page loading times and is enabled by default for a better user experience.', 'maxgalleria') ?></td>			
		</tr>
		<tr>
			<td>
				<?php esc_html_e('Lazy Load Threshold (0 for off or 1 for on):', 'maxgalleria') ?>
			</td>
			<td>
				<input type="text" class="small" id="<?php echo esc_html($options->lazy_load_threshold_key) ?>" name="<?php echo esc_html($options->lazy_load_threshold_key) ?>" value="<?php echo esc_html($options->get_lazy_load_threshold()) ?>" />
			</td>
		</tr>
    
	</table>
  
</div>
<script type="text/javascript">		
	jQuery(document).ready(function() {
    		
		jQuery('#<?php echo esc_html($options->ns_border_color_key) ?>').css('border-color','<?php echo esc_html($options->get_border_color()) ?>');
		jQuery('#<?php echo esc_html($options->ns_shadow_color_key) ?>').css('border-color','<?php echo esc_html($options->get_shadow_color()) ?>');
		
	  jQuery(document).on("change", "#<?php echo esc_html($options->ns_shadow_key) ?>", function () {						
			var shadow_type = jQuery("#<?php echo esc_html($options->ns_shadow_key) ?>").val();
			if(shadow_type === 'color') {
				jQuery("#<?php echo esc_html($options->ns_shadow_color_key) ?>").prop('disabled', false);
			} else {
				jQuery("#<?php echo esc_html($options->ns_shadow_color_key) ?>").prop('disabled', 'disabled');
			}	
    });   
		
		var shadow_type = jQuery("#<?php echo esc_html($options->ns_shadow_key) ?>").val();
		if(shadow_type === 'color') {
			jQuery("#<?php echo esc_html($options->ns_shadow_color_key) ?>").prop('disabled', false);
		} else {
			jQuery("#<?php echo esc_html($options->ns_shadow_color_key) ?>").prop('disabled', 'disabled');
		}	
		
    jQuery(document).on("click", "#<?php echo esc_html($options->ns_show_border_key) ?>", function() {
			if(this.checked) {
			  jQuery("#<?php echo esc_html($options->ns_border_color_key) ?>").prop('disabled', false);
			  jQuery(".border-thickness").prop('disabled', false);
			  jQuery(".border-radius").prop('disabled', false);				
        jQuery("input.ns-shadow-type").prop('disabled', false);
				jQuery("#border-thickness-default").prop("checked", false)
				jQuery("#border-radius-default").prop("checked", false)
				//jQuery("#border-color-row").show();
        //jQuery("#shadow-color-row").show();                        				
			} else {
			  jQuery("#<?php echo esc_html($options->ns_border_color_key) ?>").prop('disabled', 'disabled');
			  jQuery(".border-thickness").prop('disabled', 'disabled');
			  jQuery(".border-radius").prop('disabled', 'disabled');
        jQuery("input.ns-shadow-type").prop('disabled','disabled');
				jQuery("#border-thickness-default").prop("checked", 'disabled')
				jQuery("#border-radius-default").prop("checked", 'disabled')
				//jQuery("#border-color-row").hide();
        //jQuery("#shadow-color-row").hide();                
			}	
		});		
		
		if(jQuery('#<?php echo esc_html($options->ns_show_border_key) ?>').is(':checked')) {
			jQuery("#<?php echo esc_html($options->ns_border_color_key) ?>").prop('disabled', false);
			jQuery(".border-thickness").prop('disabled', false);
			jQuery(".border-radius").prop('disabled', false);				
      jQuery("input.ns-shadow-type").prop('disabled', false);
      jQuery("#border-thickness-default").prop("disabled", false)
      jQuery("#border-radius-default").prop("disabled", false)
      jQuery("input.ns-blur-type").prop('disabled', false);
      jQuery("input.ns-spread-type").prop('disabled', false);            
      //jQuery("#border-color-row").show();
      //jQuery("#shadow-color-row").show();                        								
		} else {
			jQuery("#<?php echo esc_html($options->ns_border_color_key) ?>").prop('disabled', 'disabled');
			jQuery(".border-thickness").prop('disabled', 'disabled');
			jQuery(".border-radius").prop('disabled', 'disabled');
      jQuery("input.ns-shadow-type").prop('disabled', 'disabled');
      jQuery("#border-thickness-default").prop("disabled", 'disabled')
      jQuery("#border-radius-default").prop("disabled", 'disabled')			
      jQuery("input.ns-blur-type").prop('disabled', 'disabled');
      jQuery("input.ns-spread-type").prop('disabled', 'disabled');            
      //jQuery("#border-color-row").hide();
      //jQuery("#shadow-color-row").hide();                        								
		}

    jQuery(document).on("click", "#<?php echo esc_html($options->ns_border_color_key) . '2' ?>", function() {
			if(!jQuery("#<?php echo esc_html($options->ns_border_color_key) ?>").prop('disabled')) {				
        jQuery("#<?php echo esc_html($options->ns_border_color_key) ?>").trigger("click")
		    //jQuery("#<?php echo esc_html($options->ns_border_color_key) ?>").click();
			}	
	  });  

	  jQuery(document).on("change", ".ns-shadow-type", function () {						
			var shadow_type = this.value
			if(shadow_type === 'color') {
				jQuery("#<?php echo esc_html($options->ns_shadow_color_key) ?>").prop('disabled', false);
			  jQuery("#<?php echo esc_html($options->ns_shadow_color_key . '2') ?>").prop('disabled', false);
        //jQuery("#shadow-color-row").show();        
			} else {
				jQuery("#<?php echo esc_html($options->ns_shadow_color_key) ?>").prop('disabled', 'disabled');
			  jQuery("#<?php echo esc_html($options->ns_shadow_color_key . '2') ?>").prop('disabled', 'disabled');
        //jQuery("#shadow-color-row").hide();        
			}	
			if(shadow_type === 'none') {
				jQuery(".ns-blur-type").prop('disabled', 'disabled');
				jQuery(".ns-spread-type").prop('disabled', 'disabled');
				jQuery("#<?php echo esc_html($options->ns_shadow_color_key) ?>").prop('disabled', 'disabled');
			  jQuery("#<?php echo esc_html($options->ns_shadow_color_key . '2') ?>").prop('disabled', 'disabled');
        //jQuery("#shadow-color-row").hide();
			} else {
				jQuery(".ns-blur-type").prop('disabled', false);
				jQuery(".ns-spread-type").prop('disabled', false);
			}				
			
    });   
		
    jQuery(document).on("click", "#<?php echo esc_html($options->ns_shadow_color_key . '2') ?>", function() {
			if(!jQuery("#<?php echo esc_html($options->ns_shadow_color_key) ?>").prop('disabled')) {				
		    //jQuery("#<?php echo esc_html($options->ns_shadow_color_key) ?>").click();
        jQuery("#<?php echo esc_html($options->ns_shadow_color_key) ?>").trigger("click")
		  }
	  });  
		
    jQuery(document).on("click", "#<?php echo esc_html($options->thumb_caption_enabled_key) ?>", function() {
			if(this.checked) {
			  jQuery(".caption-position-type").prop('disabled', false);				
			  jQuery("input#default-caption-position").prop("checked", true);
			} else {
			  jQuery(".caption-position-type").prop('disabled', 'disabled');				
			}	
		});		
				
		if(jQuery('#<?php echo esc_html($options->thumb_caption_enabled_key) ?>').is(':checked')) {
			jQuery(".caption-position-type").prop('disabled', false);
		} else {
			jQuery(".caption-position-type").prop('disabled', 'disabled');
		}			
		
	});
</script>
