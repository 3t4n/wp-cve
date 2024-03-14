<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} // Exit if accessed directly ?>

<div class="p-3 sf-panel-setting">
	<div class="mb-3">
		<div id="sf_12_slider-help" class="form-text sf-tooltip">
			<strong><?php esc_html_e( 'Note : This is a Before After Slider.', 'slider-factory' ); ?></strong>
			<strong><?php esc_html_e( 'You need to upload two images only. First image is Before image and second image is After image.', 'slider-factory' ); ?></strong>
		</div>
	</div>

	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_12_width'] ) ) {
			$sf_12_width = $slider['sf_12_width'];
		} else {
			$sf_12_width = '100%';
		}
		?>
		<h5 for="sf_12_width" class="form-label sf-title"><?php esc_html_e( 'Width', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_12_width" name="sf_12_width" value="<?php echo esc_attr( $sf_12_width ); ?>" aria-describedby="sf_12_width-help">
		<div id="sf_12_width-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider image width. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_12_height'] ) ) {
			$sf_12_height = $slider['sf_12_height'];
		} else {
			$sf_12_height = 'auto';
		}
		?>
		<h5 for="sf_12_height" class="form-label sf-title"><?php esc_html_e( 'Height', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_12_height" name="sf_12_height" value="<?php echo esc_attr( $sf_12_height ); ?>" aria-describedby="sf_12_height-help">
		<div id="sf_12_height-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider image height. You can use unit like: pixels 200px/300px/500px etc. OR leave blank for auto.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_12_overlay" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Overlay Effect', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_12_overlay-1" name="sf_12_overlay" value="true" disabled>
			<label class="btn btn-outline-secondary" for="sf_12_overlay-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_12_overlay-2" name="sf_12_overlay" value="false" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_12_overlay-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_12_overlay-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable overlay effect on hover.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3 col-md-6">
		<h5 for="sf_12_image_visible_ratio" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Before/After Image Visible Ratio', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="range" class="form-range" id="sf_12_image_visible_ratio" min="0.0" max="1.0" step="0.05" value="0.5" oninput="SFprintRange(this.id, this.value);" disabled>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_12_image_visible_ratio-value" disabled>0.5</button> <?php esc_html_e( 'milliseconds', 'slider-factory' ); ?>
		<div id="sf_12_image_visible_ratio-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Choose the before and after image visible ratio when the page loads.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_12_orientation" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Orientation', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_12_orientation-1" name="sf_12_orientation" value="horizontal" checked disabled >
			<label class="btn btn-outline-secondary" for="sf_12_orientation-1"><?php esc_html_e( 'Horizontal', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_12_orientation-2" name="sf_12_orientation" value="vertical" disabled >
			<label class="btn btn-outline-secondary" for="sf_12_orientation-2"><?php esc_html_e( 'Vertical', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_12_orientation-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Choose the orientation of the before and after images.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_12_beforeLabel" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Before Image Label', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="text" class="form-control w-50" id="sf_12_beforeLabel" name="sf_12_beforeLabel" value="Before" aria-describedby="sf_12_beforeLabel-help" disabled >
		<div id="sf_12_beforeLabel-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Set a custom before label for before-image.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_12_afterLabel" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'After Image Label', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="text" class="form-control w-50" id="sf_12_afterLabel" name="sf_12_afterLabel" value="After" aria-sf_12_afterLabel="sf_12_beforeLabel-help" disabled>
		<div id="sf_12_afterLabel-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Set a custom after label for after-image.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_12_move_slider_on_hover" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Move Slider On Hover', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_12_move_slider_on_hover-1" name="sf_12_move_slider_on_hover" value="true" disabled>
			<label class="btn btn-outline-secondary" for="sf_12_move_slider_on_hover-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_12_move_slider_on_hover-2" name="sf_12_move_slider_on_hover" value="false" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_12_move_slider_on_hover-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_12_move_slider_on_hover-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable move slider on mouse hover.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_12_move_with_handle_only" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Move With Handle Only', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_12_move_with_handle_only-1" name="sf_12_move_with_handle_only" value="true" checked disabled >
			<label class="btn btn-outline-secondary" for="sf_12_move_with_handle_only-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_12_move_with_handle_only-2" name="sf_12_move_with_handle_only" value="false" disabled >
			<label class="btn btn-outline-secondary" for="sf_12_move_with_handle_only-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_12_move_with_handle_only-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable to swipe anywhere on the image to control slider movement.', 'slider-factory' ); ?></div>
	</div>	
	
	<div class="mb-3">
		<h5 for="sf_12_click_to_move" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Click To Move', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_12_click_to_move-1" name="sf_12_click_to_move" value="true" disabled>
			<label class="btn btn-outline-secondary" for="sf_12_click_to_move-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_12_click_to_move-2" name="sf_12_click_to_move" value="false" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_12_click_to_move-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_12_click_to_move-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable click (or tap) anywhere on the image to move the slider to that location.', 'slider-factory' ); ?></div>
	</div>		
	
	<div class="mb-3">
		<h5 for="sf_12_custom_css" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Custom CSS', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<textarea type="text" class="form-control w-50" id="sf_12_custom_css" name="sf_12_custom_css" aria-describedby="sf_12_custom_css-help" disabled></textarea>
		<div id="sf_12_custom_css-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Apply the custom design code to this slider.', 'slider-factory' ); ?></div>
	</div>
</div>
<script>
// save slider JS start
jQuery(document).ready(function () {
	jQuery('#sf-save-slider').click(function(){
		// show processing icon
		jQuery('button#sf-save-slider').addClass('d-none');
		jQuery('div#sf-slider-process').removeClass('d-none');
		
		//slider info
		var sf_slider_id = jQuery('#sf_slider_id').val();
		var sf_slider_layout = jQuery('#sf_slider_layout').val();
		var sf_slider_title = jQuery('#sf_slider_title').val();
		
		//slides
		var sf_slide_ids = jQuery('.sf_slide_id').serialize();
		var sf_slide_titles = jQuery('input:text.sf_slide_title').serialize();
		var sf_slide_descs = jQuery('.sf_slide_desc').serialize();
		var sf_slide_link_texts_1 = jQuery('input:text.sf_slide_link_text_1').serialize();
		var sf_slide_links_1 = jQuery('input:text.sf_slide_link_1').serialize();
		var sf_slide_link_texts_2 = jQuery('input:text.sf_slide_link_text_2').serialize();
		var sf_slide_links_2 = jQuery('input:text.sf_slide_link_2').serialize();
		var sf_slide_alts_text = jQuery('input:text.sf_slide_alt_text').serialize();
		
		//settings
		var sf_12_width = jQuery("#sf_12_width").val();
		var sf_12_height = jQuery("#sf_12_height").val();
		
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				'action': 'sf_save_slider', //this is the name of the AJAX method called in WordPress
				'nonce': "<?php echo esc_js( wp_create_nonce( 'save-slider' ) ); ?>",
				//slider info
				'sf_slider_id': sf_slider_id,
				'sf_slider_layout': sf_slider_layout,
				'sf_slider_title': sf_slider_title,
				
				//slides info
				'sf_slide_ids': sf_slide_ids,
				'sf_slide_titles': sf_slide_titles,
				'sf_slide_descs': sf_slide_descs,
				'sf_slide_link_texts_1': sf_slide_link_texts_1,
				'sf_slide_links_1': sf_slide_links_1,
				'sf_slide_link_texts_2': sf_slide_link_texts_2,
				'sf_slide_links_2': sf_slide_links_2,
				'sf_slide_alts_text': sf_slide_alts_text,
				
				//slider settings
				'sf_12_width': sf_12_width,
				'sf_12_height': sf_12_height,

			}, 
			success: function (result) {
				//alert(result);
				jQuery(function() {
					// setTimeout() function will be fired after page is loaded
					// it will wait for 5 sec. and then will fire
					// $("#successMessage").hide() function
					setTimeout(function() {
						// hide processing icon and show button
						jQuery('button#sf-save-slider').removeClass('d-none');
						jQuery('div#sf-slider-process').addClass('d-none');
						// show shortcode
						jQuery("#sf-shortcode-content").removeClass('d-none').slideDown("slow");
					}, 1500);
				});
			},
			error: function () {
				//alert("error");
			}
		});
		
	});
});
// save slider JS end
</script>
