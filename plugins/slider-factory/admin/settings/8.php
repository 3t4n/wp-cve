<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} // Exit if accessed directly ?>

<div class="p-3 sf-panel-setting">
	<!-- width -->
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_8_width'] ) ) {
			$sf_8_width = $slider['sf_8_width'];
		} else {
			$sf_8_width = '400px';
		}
		?>
		<h5 for="sf_8_width" class="form-label sf-title"><?php esc_html_e( 'Width', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_8_width" name="sf_8_width" value="<?php echo esc_attr( $sf_8_width ); ?>" aria-describedby="sf-8-width-help">
		<div id="sf_8_width-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider width. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	<!-- height -->
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_8_height'] ) ) {
			$sf_8_height = $slider['sf_8_height'];
		} else {
			$sf_8_height = '400px';
		}
		?>
		<h5 for="sf_8_height" class="form-label sf-title"><?php esc_html_e( 'Height', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_8_height" name="sf_8_height" value="<?php echo esc_attr( $sf_8_height ); ?>" aria-describedby="sf_8_height-help">
		<div id="sf_8_height-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider height. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	<!-- Autoplay speed -->
	<div class="mb-3 col-md-6">
		<h5 for="sf_8_play_speed" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Play Speed', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="range" class="form-range" id="sf_8_play_speed-1" min="0" max="500" step="10" value="100" oninput="SFprintRange(this.id, this.value);" disabled>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_8_play_speed-1-value" disabled>100</button> <?php esc_html_e( 'px/min', 'slider-factory' ); ?>
		<div id="sf_8_play_speed-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Play speed for reel.its px/min', 'slider-factory' ); ?></div>
	</div>
	<!-- Direction -->
	<div class="mb-3">
		<h5 for="sf_8_direction" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Slider Running Direction', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<!-- right-to-left -->
			<input type="radio" class="btn-check" id="sf_8_direction-left" name="sf_8_direction" value="right" disabled>
			<label class="btn btn-outline-secondary" for="sf_8_direction-left"><?php esc_html_e( 'Left to right', 'slider-factory' ); ?></label>
			<!-- left-to-right -->
			<input type="radio" class="btn-check" id="sf_8_direction-right" name="sf_8_direction" value="left" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_8_direction-right"><?php esc_html_e( 'Right to Left', 'slider-factory' ); ?></label>
			<!-- Vertical Down to UP-->
			<input type="radio" class="btn-check" id="sf_8_direction-up" name="sf_8_direction" value="up" disabled>
			<label class="btn btn-outline-secondary" for="sf_8_direction-up"><?php esc_html_e( 'Down to up', 'slider-factory' ); ?></label>
			<!-- Vertical Up to Down-->
			<input type="radio" class="btn-check" id="sf_8_direction-down" name="sf_8_direction" value="down" disabled>
			<label class="btn btn-outline-secondary" for="sf_8_direction-down"><?php esc_html_e( 'Up to Down', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_8_direction-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Set slide running order in slide show.', 'slider-factory' ); ?></div>
	</div>
	
	<!-- Pause on Hover -->
	<div class="mb-3">
		<h5 for="sf_8_hover_pause" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Pause on Hover', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_8_hover_pause-on" name="sf_8_hover_pause" value="true" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_8_hover_pause-on"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_8_hover_pause-off" name="sf_8_hover_pause" value="false" disabled>
			<label class="btn btn-outline-secondary" for="sf_8_hover_pause-off"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_8_hover_pause-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable Pause on mouse Hover.', 'slider-factory' ); ?></div>
	</div>
	<!-- Responsive variable -->
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_8_responsive'] ) ) {
			$sf_8_responsive = $slider['sf_8_responsive'];
		} else {
			$sf_8_responsive = 'true';
		}
		?>
		<h5 for="sf_8_responsive" class="form-label sf-title"><?php esc_html_e( 'Responsive Slider', 'slider-factory' ); ?></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_8_responsive-on" name="sf_8_responsive" value="true" <?php checked( $sf_8_responsive, 'true' ); ?>>
			<label class="btn btn-outline-secondary" for="sf_8_responsive-on"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_8_responsive-off" name="sf_8_responsive" value="false" <?php checked( $sf_8_responsive, 'false' ); ?>>
			<label class="btn btn-outline-secondary" for="sf_8_responsive-off"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_8_responsive-help" class="form-text sf-tooltip"><?php esc_html_e( 'Enable or disable Responsiveness. Width/Height recalculation on window resize respective different screen layouts.', 'slider-factory' ); ?></div>
	</div>
	<!-- Cloning -->
	<div class="mb-3">
			<h5 for="sf_8_cloning" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Clone Slides', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
			<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
				<input type="radio" class="btn-check" id="sf_8_cloning-0" name="sf_8_cloning" value="0"  disabled>
				<label class="btn btn-outline-secondary" for="sf_8_cloning-0"><?php esc_html_e( 'None', 'slider-factory' ); ?></label>
			
				<input type="radio" class="btn-check" id="sf_8_cloning-1" name="sf_8_cloning" value="1" checked disabled>
				<label class="btn btn-outline-secondary" for="sf_8_cloning-1"><?php esc_html_e( 'by 1', 'slider-factory' ); ?></label>

				<input type="radio" class="btn-check" id="sf_8_cloning-2" name="sf_8_cloning" value="2"  disabled>
				<label class="btn btn-outline-secondary" for="sf_8_cloning-2"><?php esc_html_e( 'by 2', 'slider-factory' ); ?></label>
				
				<input type="radio" class="btn-check" id="sf_8_cloning-3" name="sf_8_cloning" value="3" disabled>
				<label class="btn btn-outline-secondary" for="sf_8_cloning-3"><?php esc_html_e( 'by 3', 'slider-factory' ); ?></label>
				
				<input type="radio" class="btn-check" id="sf_8_cloning-4" name="sf_8_cloning" value="4" < disabled>
				<label class="btn btn-outline-secondary" for="sf_8_cloning-4"><?php esc_html_e( 'by 4', 'slider-factory' ); ?></label>

				<input type="radio" class="btn-check" id="sf_8_cloning-5" name="sf_8_cloning" value="5"  disabled>
				<label class="btn btn-outline-secondary" for="sf_8_cloning-5"><?php esc_html_e( 'by 5', 'slider-factory' ); ?></label>
				
				<input type="radio" class="btn-check" id="sf_8_cloning-6" name="sf_8_cloning" value="6"  disabled>
				<label class="btn btn-outline-secondary" for="sf_8_cloning-6"><?php esc_html_e( 'by 6', 'slider-factory' ); ?></label>
			</div>
			<div id="sf_8_cloning-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Use cloning if your slides are too few or Number of selected images are less than 6. ', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>:</strong> <?php esc_html_e( 'Select [None] if you dont want to clone selected images in reel or number of selected images is more than 6.', 'slider-factory' ); ?></div>
	</div>
	<!-- Slides Gap -->
	<div class="mb-3 col-md-6">
			<h5 for="sf_8_slides_gap" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Gap between slides', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
			<input type="range" class="form-range" id="sf_8_slides_gap-1" min="0" max="5" step="0.1" value="0" oninput="SFprintRange(this.id, this.value);" disabled>
			<button class="btn btn-sm btn-secondary pl-2" id="sf_8_slides_gap-1-value" disabled>0</button> <?php esc_html_e( '%', 'slider-factory' ); ?>
			<div id="sf_8_slides_gap-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Setting to put gap between slides.set 0 for no gap.', 'slider-factory' ); ?></div>
	</div>
	
	<!-- Sorting -->
	<div class="mb-3">
			<?php
			if ( isset( $slider['sf_8_sorting'] ) ) {
				$sf_8_sorting = $slider['sf_8_sorting'];
			} else {
				$sf_8_sorting = 0;
			}
			?>
			<h5 for="sf_8_sorting-1" class="form-label sf-title"><?php esc_html_e( 'Slide Order By', 'slider-factory' ); ?></h5>
			<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
				<input type="radio" class="btn-check" id="sf_8_sorting-0" name="sf_8_sorting" value="0" <?php checked( $sf_8_sorting, 0 ); ?>>
				<label class="btn btn-outline-secondary" for="sf_8_sorting-0"><?php esc_html_e( 'None', 'slider-factory' ); ?></label>
			
				<input type="radio" class="btn-check" id="sf_8_sorting-1" name="sf_8_sorting" value="1" <?php checked( $sf_8_sorting, 1 ); ?>>
				<label class="btn btn-outline-secondary" for="sf_8_sorting-1"><?php esc_html_e( 'Slide ID Ascending', 'slider-factory' ); ?></label>

				<input type="radio" class="btn-check" id="sf_8_sorting-2" name="sf_8_sorting" value="2" <?php checked( $sf_8_sorting, 2 ); ?>>
				<label class="btn btn-outline-secondary" for="sf_8_sorting-2"><?php esc_html_e( 'Slide ID Descending', 'slider-factory' ); ?></label>
				
				<input type="radio" class="btn-check" id="sf_8_sorting-3" name="sf_8_sorting" value="3" <?php checked( $sf_8_sorting, 3 ); ?> disabled>
				<label class="btn btn-outline-secondary sf-tooltip-disabled" for="sf_8_sorting-3"><?php esc_html_e( 'Slide ID Shuffle', 'slider-factory' ); ?></label>
				
				<input type="radio" class="btn-check" id="sf_8_sorting-4" name="sf_8_sorting" value="4" <?php checked( $sf_8_sorting, 4 ); ?> disabled>
				<label class="btn btn-outline-secondary sf-tooltip-disabled" for="sf_8_sorting-4"><?php esc_html_e( 'Slide Title Ascending', 'slider-factory' ); ?></label>

				<input type="radio" class="btn-check" id="sf_8_sorting-5" name="sf_8_sorting" value="5" <?php checked( $sf_8_sorting, 5 ); ?> disabled>
				<label class="btn btn-outline-secondary sf-tooltip-disabled" for="sf_8_sorting-5"><?php esc_html_e( 'Slide Title Descending', 'slider-factory' ); ?></label>
			</div>
			<div id="sf_8_sorting-help" class="form-text sf-tooltip"><?php esc_html_e( 'Set slide loading order in slide show. ', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>:</strong> <?php esc_html_e( 'Select [None] to leave the order as it is above.', 'slider-factory' ); ?></div>
	</div>
	<!-- Custom CSS -->
	<div class="mb-3">
			<h5 for="sf_8_custom_css-css" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Custom CSS', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
			<textarea type="text" class="form-control w-50" id="sf_8_custom_css" name="sf_8_custom_css" aria-describedby="sf-8-custom-css-help" disabled></textarea>
			<div id="sf_8_custom_css-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Apply the custom design code to this slider using your own css.', 'slider-factory' ); ?></div>
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
		var sf_8_width = jQuery("#sf_8_width").val();
		var sf_8_height = jQuery("#sf_8_height").val();
		var sf_8_responsive = jQuery("input[name='sf_8_responsive']:checked").val();
		var sf_8_sorting = jQuery("input[name='sf_8_sorting']:checked").val();
		
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
				'sf_8_width': sf_8_width,
				'sf_8_height': sf_8_height,
				'sf_8_responsive': sf_8_responsive,
				'sf_8_sorting': sf_8_sorting
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
