<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} // Exit if accessed directly ?>

<div class="p-3 sf-panel-setting">
	<!-- width -->
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_6_width'] ) ) {
			$sf_6_width = $slider['sf_6_width'];
		} else {
			$sf_6_width = '100%';
		}
		?>
		<h5 for="sf-6-width" class="form-label sf-title"><?php esc_html_e( 'Width', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_6_width" name="sf_6_width" value="<?php echo esc_attr( $sf_6_width ); ?>" aria-describedby="sf-6-width-help">
		<div id="sf-6-width-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider width. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	<!-- height -->
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_6_height'] ) ) {
			$sf_6_height = $slider['sf_6_height'];
		} else {
			$sf_6_height = '100%';
		}
		?>
		<h5 for="sf_6_height" class="form-label sf-title"><?php esc_html_e( 'Height', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_6_height" name="sf_6_height" value="<?php echo esc_attr( $sf_6_height ); ?>" aria-describedby="sf_6_height-help">
		<div id="sf_6_height-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider height. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	
	<!-- Autoplay -->
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_6_auto_play'] ) ) {
			$sf_6_auto_play = $slider['sf_6_auto_play'];
		} else {
			$sf_6_auto_play = 'true';
		}
		?>
		<h5 for="sf_6_auto_play" class="form-label sf-title"><?php esc_html_e( 'Auto Play', 'slider-factory' ); ?></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_6_auto_play-1" name="sf_6_auto_play" value="true" <?php checked( $sf_6_auto_play, 'true' ); ?>>
			<label class="btn btn-outline-secondary" for="sf_6_auto_play-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_6_auto_play-2" name="sf_6_auto_play" value="false" <?php checked( $sf_6_auto_play, 'false' ); ?>>
			<label class="btn btn-outline-secondary" for="sf_6_auto_play-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_6_auto_play-help" class="form-text sf-tooltip"><?php esc_html_e( 'Enable or disable automatic slide show.', 'slider-factory' ); ?></div>
	</div>

	<!-- Autoplay speed -->
	<div class="mb-3 col-md-6">
			<h5 for="sf_6_auto_play_speed" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Auto Play Speed', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
			<input type="range" class="form-range" id="sf_6_auto_play_speed-1" min="100" max="10000" step="100" value="2500" oninput="SFprintRange(this.id, this.value);" disabled>
			<button class="btn btn-sm btn-secondary pl-2" id="sf_6_auto_play_speed-1-value" disabled>2500</button> <?php esc_html_e( 'milliseconds', 'slider-factory' ); ?>
			<div id="sf_6_auto_play_speed-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Adjust automatic slide show speed.', 'slider-factory' ); ?></div>
		</div>
		
	<!-- Transition speed -->
	<div class="mb-3 col-md-6">
			<h5 for="sf_6_transition_speed" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Transition Speed', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
			<input type="range" class="form-range" id="sf_6_transition_speed-1" min="100" max="5000" step="100" value="1700" oninput="SFprintRange(this.id, this.value);" disabled>
			<button class="btn btn-sm btn-secondary pl-2" id="sf_6_transition_speed-1-value" disabled>1700</button> <?php esc_html_e( 'milliseconds', 'slider-factory' ); ?>
			<div id="sf_6_transition_speed-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Adjust slide transition speed.', 'slider-factory' ); ?></div>
	</div>

	<!-- Page Dots -->
	<div class="mb-3">
		<h5 for="sf_6_page_dots" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Slide Dots', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_6_page_dots-1" name="sf_6_page_dots" value="true" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_6_page_dots-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_6_page_dots-2" name="sf_6_page_dots" value="false" disabled>
			<label class="btn btn-outline-secondary" for="sf_6_page_dots-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_6_page_dots-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable pagination dots.', 'slider-factory' ); ?></div>
	</div>

	<!-- Arrows -->
	<div class="mb-3">
		<h5 for="sf_6_navigation_arrow-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Slider Navigation Arrow', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_6_navigation_arrow-1" name="sf_6_navigation_arrow" value="true" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_6_navigation_arrow-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_6_navigation_arrow-2" name="sf_6_navigation_arrow" value="false" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_6_navigation_arrow-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_6_navigation_arrow-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Hide or display next and previous slide navigation arrow button over the slides.', 'slider-factory' ); ?></div>
	</div>
		
	<!-- responsive variable -->
	<div class="mb-3">
		<h5 for="sf_6_responsive" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Responsive Slider', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_6_responsive-1" name="sf_6_responsive" value="true" disabled>
			<label class="btn btn-outline-secondary" for="sf_6_responsive-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_6_responsive-2" name="sf_6_responsive" value="false" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_6_responsive-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_6_responsive-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable responsiveness of slider for small screens.', 'slider-factory' ); ?></div>
	</div>
	
	<!-- Direction -->
	<div class="mb-3">
		<h5 for="sf_6_direction-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Slide Loading Direction', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_6_direction-1" name="sf_6_direction" value="horizontal" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_6_direction-1"><?php esc_html_e( 'Horizontal', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_6_direction-2" name="sf_6_direction" value="vertical" disabled>
			<label class="btn btn-outline-secondary" for="sf_6_direction-2"><?php esc_html_e( 'Vertical', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_6_direction-3" name="sf_6_direction" value="four" disabled>
			<label class="btn btn-outline-secondary" for="sf_6_direction-3"><?php esc_html_e( 'All Four Side Clockwise', 'slider-factory' ); ?></label>
			
		</div>
		<div id="sf_6_direction-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Wipe animation direction for the next slide.', 'slider-factory' ); ?></div>
	</div>

	<!-- Sorting -->
	<div class="mb-3">
			<?php
			if ( isset( $slider['sf_6_sorting'] ) ) {
				$sf_6_sorting = $slider['sf_6_sorting'];
			} else {
				$sf_6_sorting = 0;
			}
			?>
			<h5 for="sf_6_sorting-1" class="form-label sf-title"><?php esc_html_e( 'Slide Order By', 'slider-factory' ); ?></h5>
			<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
				<input type="radio" class="btn-check" id="sf_6_sorting-0" name="sf_6_sorting" value="0" <?php checked( $sf_6_sorting, 0 ); ?>>
				<label class="btn btn-outline-secondary" for="sf_6_sorting-0"><?php esc_html_e( 'None', 'slider-factory' ); ?></label>
			
				<input type="radio" class="btn-check" id="sf_6_sorting-1" name="sf_6_sorting" value="1" <?php checked( $sf_6_sorting, 1 ); ?>>
				<label class="btn btn-outline-secondary" for="sf_6_sorting-1"><?php esc_html_e( 'Slide ID Ascending', 'slider-factory' ); ?></label>

				<input type="radio" class="btn-check" id="sf_6_sorting-2" name="sf_6_sorting" value="2" <?php checked( $sf_6_sorting, 2 ); ?>>
				<label class="btn btn-outline-secondary" for="sf_6_sorting-2"><?php esc_html_e( 'Slide ID Descending', 'slider-factory' ); ?></label>
				
				<input type="radio" class="btn-check" id="sf_6_sorting-3" name="sf_6_sorting" value="3" <?php checked( $sf_6_sorting, 3 ); ?> disabled>
				<label class="btn btn-outline-secondary sf-tooltip-disabled" for="sf_6_sorting-3"><?php esc_html_e( 'Slide ID Shuffle', 'slider-factory' ); ?></label>
				
				<input type="radio" class="btn-check" id="sf_6_sorting-4" name="sf_6_sorting" value="4" <?php checked( $sf_6_sorting, 4 ); ?> disabled>
				<label class="btn btn-outline-secondary sf-tooltip-disabled" for="sf_6_sorting-4"><?php esc_html_e( 'Slide Title Ascending', 'slider-factory' ); ?></label>

				<input type="radio" class="btn-check" id="sf_6_sorting-5" name="sf_6_sorting" value="5" <?php checked( $sf_6_sorting, 5 ); ?> disabled>
				<label class="btn btn-outline-secondary sf-tooltip-disabled" for="sf_6_sorting-5"><?php esc_html_e( 'Slide Title Descending', 'slider-factory' ); ?></label>
			</div>
			<div id="sf_6_sorting-help" class="form-text sf-tooltip"><?php esc_html_e( 'Set slide loading order in slide show.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>:</strong> <?php esc_html_e( 'Select [None] to leave the order as it was at time of creation.', 'slider-factory' ); ?></div>
		</div>
		
		<!-- Custom CSS -->
		<div class="mb-3">
			<h5 for="sf_6_custom_css" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Custom CSS', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
			<textarea type="text" class="form-control w-50" id="sf_6_custom_css" name="sf_6_custom_css" aria-describedby="sf-6-custom-css-help" disabled></textarea>
			<div id="sf_6_custom_css-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Apply the custom design code to this slider.', 'slider-factory' ); ?></div>
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
		var sf_6_width = jQuery("#sf_6_width").val();
		var sf_6_height = jQuery("#sf_6_height").val();
		var sf_6_auto_play = jQuery("input[name='sf_6_auto_play']:checked").val();
		var sf_6_sorting = jQuery("input[name='sf_6_sorting']:checked").val();
		
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
				'sf_6_width': sf_6_width,
				'sf_6_height': sf_6_height,
				'sf_6_auto_play': sf_6_auto_play,
				'sf_6_sorting': sf_6_sorting,
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
