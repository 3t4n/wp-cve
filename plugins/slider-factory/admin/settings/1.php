<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} // Exit if accessed directly ?>

<div class="p-3 sf-panel-setting">
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_1_width'] ) ) {
			$sf_1_width = $slider['sf_1_width'];
		} else {
			$sf_1_width = '100%';
		}
		?>
		<h5 for="sf-1-width" class="form-label sf-title"><?php esc_html_e( 'Width', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_1_width" name="sf_1_width" value="<?php echo esc_attr( $sf_1_width ); ?>" aria-describedby="sf-1-width-help">
		<div id="sf-1-width-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider width. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_1_height'] ) ) {
			$sf_1_height = $slider['sf_1_height'];
		} else {
			$sf_1_height = '100%';
		}
		?>
		<h5 for="sf_1_height" class="form-label sf-title"><?php esc_html_e( 'Height', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_1_height" name="sf_1_height" value="<?php echo esc_attr( $sf_1_height ); ?>" aria-describedby="sf_1_height-help">
		<div id="sf_1_height-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider height. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_1_design_preset-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Design Preset', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_1_design_preset-1" name="sf_1_design_preset" value="1" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_1_design_preset-1"><?php esc_html_e( 'One', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_1_design_preset-2" name="sf_1_design_preset" value="2" disabled>
			<label class="btn btn-outline-secondary" for="sf_1_design_preset-2"><?php esc_html_e( 'Two', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_1_design_preset-3" name="sf_1_design_preset" value="3" disabled>
			<label class="btn btn-outline-secondary" for="sf_1_design_preset-3"><?php esc_html_e( 'Three', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_1_design_preset-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Select a predefined design for the slider.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>: </strong><?php esc_html_e( 'To use Preset Design 2 and 3 Fade Effect should be disabled.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_1_auto_play'] ) ) {
			$sf_1_auto_play = $slider['sf_1_auto_play'];
		} else {
			$sf_1_auto_play = 'true';
		}
		?>
		<h5 for="sf_1_auto_play" class="form-label sf-title"><?php esc_html_e( 'Auto Play', 'slider-factory' ); ?></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_1_auto_play-1" name="sf_1_auto_play" value="true" <?php checked( $sf_1_auto_play, 'true' ); ?>>
			<label class="btn btn-outline-secondary" for="sf_1_auto_play-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_1_auto_play-2" name="sf_1_auto_play" value="false" <?php checked( $sf_1_auto_play, 'false' ); ?>>
			<label class="btn btn-outline-secondary" for="sf_1_auto_play-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_1_auto_play-help" class="form-text sf-tooltip"><?php esc_html_e( 'Enable or disable automatic slide show.', 'slider-factory' ); ?></div>
	</div>

	<div class="mb-3 col-md-6">
		<h5 for="sf_1_auto_play_speed" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Auto Play Speed', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="range" class="form-range" id="sf_1_auto_play_speed-1" min="100" max="5000" step="100" value="3000" oninput="SFprintRange(this.id, this.value);" disabled>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_1_auto_play_speed-1-value" disabled>3000</button> <?php esc_html_e( 'milliseconds', 'slider-factory' ); ?>
		<div id="sf_1_auto_play_speed-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Adjust the speed of slide show in milliseconds.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_1_auto_play_pause_on_hover" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Pause On Mouse Hover', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_1_auto_play_pause_on_hover-1" name="sf_1_auto_play_pause_on_hover" value="true" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_1_auto_play_pause_on_hover-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_1_auto_play_pause_on_hover-2" name="sf_1_auto_play_pause_on_hover" value="false" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_1_auto_play_pause_on_hover-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_1_auto_play_pause_on_hover-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable auto play on mouse hover on the slide.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_1_infinite_scroll" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Infinite Scroll', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_1_infinite_scroll-1" name="sf_1_infinite_scroll" value="true" autocomplete="off"  disabled>
			<label class="btn btn-outline-secondary" for="sf_1_infinite_scroll-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_1_infinite_scroll-2" name="sf_1_infinite_scroll" value="false" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_1_infinite_scroll-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_1_infinite_scroll-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable infinite loop/scroll feature.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_1_full_screen-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Full Screen Button', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_1_full_screen-1" name="sf_1_full_screen" value="true" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_1_full_screen-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_1_full_screen-2" name="sf_1_full_screen" value="false" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_1_full_screen-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_1_full_screen-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable the full-screen slide show button on the slider to make the slider full screen.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_1_fade-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Fade Effect', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_1_fade-1" name="sf_1_fade" value="true" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_1_fade-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_1_fade-2" name="sf_1_fade" value="false" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_1_fade-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_1_fade-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable fade effect during slide show.', 'slider-factory' ); ?></div>
		<div id="sf_1_fade-help" class="form-text sf-tooltip sf-tooltip-disabled"><strong><?php esc_html_e( 'NOTE', 'slider-factory' ); ?>:</strong> <?php esc_html_e( 'Fade effect only works with Preset Design 1.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_1_adaptive_height-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Adaptive Height', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_1_adaptive_height-1" name="sf_1_adaptive_height" value="true" autocomplete="off"  disabled>
			<label class="btn btn-outline-secondary" for="sf_1_adaptive_height-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_1_adaptive_height-2" name="sf_1_adaptive_height" value="false" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_1_adaptive_height-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_1_adaptive_height-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Auto fit height of the slide to its original height.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_1_thumbnail-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Slider Thumbnail', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_1_thumbnail-1" name="sf_1_thumbnail" value="true" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_1_thumbnail-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_1_thumbnail-2" name="sf_1_thumbnail" value="false" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_1_thumbnail-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_1_thumbnail-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Hide or display the thumbnails under the slides.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_1_navigation_arrow-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Slider Navigation Arrow', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_1_navigation_arrow-1" name="sf_1_navigation_arrow" value="true" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_1_navigation_arrow-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_1_navigation_arrow-2" name="sf_1_navigation_arrow" value="false" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_1_navigation_arrow-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_1_navigation_arrow-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Hide or display next and previous slide navigation arrow button over the slides.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_1_navigation_dots-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Slider Navigation Dots', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_1_navigation_dots-1" name="sf_1_navigation_dots" value="true" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_1_navigation_dots-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_1_navigation_dots-2" name="sf_1_navigation_dots" value="false" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_1_navigation_dots-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_1_navigation_dots-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Hide or display navigation dots button below the slides.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_1_sorting'] ) ) {
			$sf_1_sorting = $slider['sf_1_sorting'];
		} else {
			$sf_1_sorting = 0;
		}
		?>
		<h5 for="sf_1_sorting-1" class="form-label sf-title"><?php esc_html_e( 'Slide Order By', 'slider-factory' ); ?></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_1_sorting-0" name="sf_1_sorting" value="0" <?php checked( $sf_1_sorting, 0 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_1_sorting-0"><?php esc_html_e( 'None', 'slider-factory' ); ?></label>
		
			<input type="radio" class="btn-check" id="sf_1_sorting-1" name="sf_1_sorting" value="1" <?php checked( $sf_1_sorting, 1 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_1_sorting-1"><?php esc_html_e( 'Slide ID Ascending', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_1_sorting-2" name="sf_1_sorting" value="2" <?php checked( $sf_1_sorting, 2 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_1_sorting-2"><?php esc_html_e( 'Slide ID Descending', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_1_sorting-3" name="sf_1_sorting" value="3" <?php checked( $sf_1_sorting, 3 ); ?> disabled>
			<label class="btn btn-outline-secondary  sf-tooltip-disabled" for="sf_1_sorting-3"><?php esc_html_e( 'Slide ID Shuffle', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_1_sorting-4" name="sf_1_sorting" value="4" <?php checked( $sf_1_sorting, 4 ); ?> disabled>
			<label class="btn btn-outline-secondary  sf-tooltip-disabled" for="sf_1_sorting-4"><?php esc_html_e( 'Slide Title Ascending', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_1_sorting-5" name="sf_1_sorting" value="5" <?php checked( $sf_1_sorting, 5 ); ?> disabled>
			<label class="btn btn-outline-secondary  sf-tooltip-disabled" for="sf_1_sorting-5"><?php esc_html_e( 'Slide Title Descending', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_1_sorting-help" class="form-text sf-tooltip"><?php esc_html_e( 'Set slide loading order in slide show.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>:</strong> <?php esc_html_e( 'Select [None] to leave the order as it was at time of creation.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_1_slide_align-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Slide Align', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_1_slide_align-1" name="sf_1_slide_align" value="left" disabled >
			<label class="btn btn-outline-secondary" for="sf_1_slide_align-1"><?php esc_html_e( 'Left', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_1_slide_align-2" name="sf_1_slide_align" value="center" checked  disabled>
			<label class="btn btn-outline-secondary" for="sf_1_slide_align-2"><?php esc_html_e( 'Center', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_1_slide_align-3" name="sf_1_slide_align" value="right" disabled>
			<label class="btn btn-outline-secondary" for="sf_1_slide_align-3"><?php esc_html_e( 'Right', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_1_slide_align-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Set the slide alignment.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>: </strong><?php esc_html_e( 'This feature only works with Preset Design 2 and 3.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_1_rtl-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Right To Left (RTL Support)', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_1_rtl-1" name="sf_1_rtl" value="true" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_1_rtl-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_1_rtl-2" name="sf_1_rtl" value="false" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_1_rtl-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_1_rtl-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( "Enable or disable that option If your website's primary language has the right to left text like Arabic, Hebrew, Pashto, Persian, Urdu.", 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf-1-custom-css" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Custom CSS', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<textarea type="text" class="form-control w-50" id="sf_1_custom_css" name="sf_1_custom_css" aria-describedby="sf-1-custom-css-help" disabled></textarea>
		<div id="sf-1-custom-css-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Apply the custom design code to this slider.', 'slider-factory' ); ?></div>
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
		var sf_1_width = jQuery("#sf_1_width").val();
		var sf_1_height = jQuery("#sf_1_height").val();
		var sf_1_auto_play = jQuery("input[name='sf_1_auto_play']:checked").val();
		var sf_1_sorting = jQuery("input[name='sf_1_sorting']:checked").val();
		
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
				'sf_1_width': sf_1_width,
				'sf_1_height': sf_1_height,
				'sf_1_auto_play': sf_1_auto_play,
				'sf_1_sorting': sf_1_sorting,
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
