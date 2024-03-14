<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} // Exit if accessed directly ?>

<div class="p-3 sf-panel-setting">
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_5_width'] ) ) {
			$sf_5_width = $slider['sf_5_width'];
		} else {
			$sf_5_width = '500px';
		}
		?>
		<h5 for="sf_5_width" class="form-label sf-title"><?php esc_html_e( 'Width', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_5_width" name="sf_5_width" value="<?php echo esc_attr( $sf_5_width ); ?>" aria-describedby="sf-5-width-help">
		<div id="sf_5_width-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider width. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_5_height'] ) ) {
			$sf_5_height = $slider['sf_5_height'];
		} else {
			$sf_5_height = '400px';
		}
		?>
		<h5 for="sf_5_height" class="form-label sf-title"><?php esc_html_e( 'Height', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_5_height" name="sf_5_height" value="<?php echo esc_attr( $sf_5_height ); ?>" aria-describedby="sf_5_height-help">
		<div id="sf_5_height-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider height. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_5_design_preset-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Design Preset', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_5_design_preset-1" name="sf_5_design_preset" value="1" disabled>
			<label class="btn btn-outline-secondary" for="sf_5_design_preset-1"><?php esc_html_e( 'Coverflow', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_5_design_preset-2" name="sf_5_design_preset" value="2" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_5_design_preset-2"><?php esc_html_e( 'Carousel', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_5_design_preset-3" name="sf_5_design_preset" value="3" disabled>
			<label class="btn btn-outline-secondary" for="sf_5_design_preset-3"><?php esc_html_e( 'Wheel', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_5_design_preset-4" name="sf_5_design_preset" value="4" disabled>
			<label class="btn btn-outline-secondary" for="sf_5_design_preset-4"><?php esc_html_e( 'Flat', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_1_design_preset-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Select a predefined design for the slider.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_5_auto_play'] ) ) {
			$sf_5_auto_play = $slider['sf_5_auto_play'];
		} else {
			$sf_5_auto_play = 'false';
		}
		?>
		<h5 for="sf_5_auto_play" class="form-label sf-title"><?php esc_html_e( 'Auto Play', 'slider-factory' ); ?></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_5_auto_play-1" name="sf_5_auto_play" value="true" <?php checked( $sf_5_auto_play, 'true' ); ?>>
			<label class="btn btn-outline-secondary" for="sf_5_auto_play-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_5_auto_play-2" name="sf_5_auto_play" value="false" <?php checked( $sf_5_auto_play, 'false' ); ?>>
			<label class="btn btn-outline-secondary" for="sf_5_auto_play-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_5_auto_play-help" class="form-text sf-tooltip"><?php esc_html_e( 'Enable or disable automatic slide show.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3 col-md-6">
		<h5 for="sf_5_auto_play_speed" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Auto Play Speed', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="range" class="form-range" id="sf_5_auto_play_speed-1" min="100" max="5000" step="100" value="2000" oninput="SFprintRange(this.id, this.value);" disabled>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_5_auto_play_speed-1-value" disabled>2000</button> <?php esc_html_e( 'milliseconds', 'slider-factory' ); ?>
		<div id="sf_5_auto_play_speed-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Adjust the automatic slide show speed.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_5_auto_play_pause_on_hover" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Pause On Mouse Hover', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_5_auto_play_pause_on_hover-1" name="sf_5_auto_play_pause_on_hover" value="true" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_5_auto_play_pause_on_hover-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_5_auto_play_pause_on_hover-2" name="sf_5_auto_play_pause_on_hover" value="false" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_5_auto_play_pause_on_hover-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_5_auto_play_pause_on_hover-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable auto play on mouse hover on the slide.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_5_loop" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Loop', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_5_loop-1" name="sf_5_loop" value="true" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_5_loop-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_5_loop-2" name="sf_5_loop" value="false" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_5_loop-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_5_loop-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable loop around slides.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3 col-md-6">
		<h5 for="sf_5_fadein_speed" class="form-label sf- sf-title-disabled"><?php esc_html_e( 'Fade In Speed', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="range" class="form-range" id="sf_5_fadein_speed-1" min="100" max="5000" step="100" value="400" oninput="SFprintRange(this.id, this.value);" disabled>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_5_fadein_speed-1-value" disabled>400</button> <?php esc_html_e( 'milliseconds', 'slider-factory' ); ?>
		<div id="sf_5_fadein_speed-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Speed of the fade in animation after items have been setup.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>: </strong><?php esc_html_e( 'This only runs once at the time of slider loads first time on the webpage.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_5_click" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Click', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_5_click-1" name="sf_5_click" value="true" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_5_click-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_5_click-2" name="sf_5_click" value="false" disabled>
			<label class="btn btn-outline-secondary" for="sf_5_click-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_5_click-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable clicking an item switches to that item.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_5_keyboard" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Keyboard Arrow Keys', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_5_keyboard-1" name="sf_5_keyboard" value="true" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_5_keyboard-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_5_keyboard-2" name="sf_5_keyboard" value="false" disabled>
			<label class="btn btn-outline-secondary" for="sf_5_keyboard-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_5_keyboard-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable slide navigation by keyboard left/right arrow keys.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_5_scrollwheel" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Scrool Wheel', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_5_scrollwheel-1" name="sf_5_scrollwheel" value="true" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_5_scrollwheel-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_5_scrollwheel-2" name="sf_5_scrollwheel" value="false" disabled>
			<label class="btn btn-outline-secondary" for="sf_5_scrollwheel-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_5_scrollwheel-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable mouse wheel/trackpad navigation.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_5_touch" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Touch', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_5_touch-1" name="sf_5_touch" value="true" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_5_touch-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_5_touch-2" name="sf_5_touch" value="false" disabled>
			<label class="btn btn-outline-secondary" for="sf_5_touch-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_5_touch-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable swipe navigation for touch devices.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_5_nav-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Navigation', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_5_nav-1" name="sf_5_nav" value="1" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_5_nav-1"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_5_nav-2" name="sf_5_nav" value="2" disabled>
			<label class="btn btn-outline-secondary" for="sf_5_nav-2"><?php esc_html_e( 'Before Slider', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_5_nav-3" name="sf_5_nav" value="3" disabled>
			<label class="btn btn-outline-secondary" for="sf_5_nav-3"><?php esc_html_e( 'After Slider', 'slider-factory' ); ?></label>
			
		</div>
		<div id="sf_1_nav-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Values [before] will insert the navigation before the items, [after] will append the navigation after the items.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_5_button" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Prev/Next Buttons', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_5_button-1" name="sf_5_button" value="true" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_5_button-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_5_button-2" name="sf_5_button" value="false" disabled>
			<label class="btn btn-outline-secondary" for="sf_5_button-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_5_button-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Hide or display next and previous slide navigation arrow button over the slides.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_5_sorting'] ) ) {
			$sf_5_sorting = $slider['sf_5_sorting'];
		} else {
			$sf_5_sorting = 0;
		}
		?>
		<h5 for="sf_5_sorting-1" class="form-label sf-title"><?php esc_html_e( 'Slide Order By', 'slider-factory' ); ?></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_5_sorting-0" name="sf_5_sorting" value="0" <?php checked( $sf_5_sorting, 0 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_5_sorting-0"><?php esc_html_e( 'None', 'slider-factory' ); ?></label>
		
			<input type="radio" class="btn-check" id="sf_5_sorting-1" name="sf_5_sorting" value="1" <?php checked( $sf_5_sorting, 1 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_5_sorting-1"><?php esc_html_e( 'Slide ID Ascending', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_5_sorting-2" name="sf_5_sorting" value="2" <?php checked( $sf_5_sorting, 2 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_5_sorting-2"><?php esc_html_e( 'Slide ID Descending', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_5_sorting-help" class="form-text sf-tooltip"><?php esc_html_e( 'Set slide loading order in slide show. ', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>:</strong> <?php esc_html_e( 'Select [None] to leave the order as it is above.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_5_custom_css'] ) ) {
			$sf_5_custom_css = $slider['sf_5_custom_css'];
		} else {
			$sf_5_custom_css = '';
		}
		?>
		<h5 for="sf_5_custom_css" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Custom CSS', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<textarea type="text" class="form-control w-50" id="sf_5_custom_css" name="sf_5_custom_css" aria-describedby="sf-1-custom-css-help" disabled></textarea>
		<div id="sf_5_custom_css-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Apply the custom design code to this slider.', 'slider-factory' ); ?></div>
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
		
		//settings
		var sf_5_width = jQuery("#sf_5_width").val();
		var sf_5_height = jQuery("#sf_5_height").val();
		var sf_5_auto_play = jQuery("input[name='sf_5_auto_play']:checked").val();
		var sf_5_sorting = jQuery("input[name='sf_5_sorting']:checked").val();
		
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

				//slider settings
				'sf_5_width': sf_5_width,
				'sf_5_height': sf_5_height,
				'sf_5_auto_play': sf_5_auto_play,
				'sf_5_sorting': sf_5_sorting,
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
