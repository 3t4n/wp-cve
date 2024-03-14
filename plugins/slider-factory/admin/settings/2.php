<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} // Exit if accessed directly ?>

<div class="p-3 sf-panel-setting">
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_2_width'] ) ) {
			$sf_2_width = $slider['sf_2_width'];
		} else {
			$sf_2_width = '100%';
		}
		?>
		<h5 for="sf-1-width" class="form-label sf-title"><?php esc_html_e( 'Width', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_2_width" name="sf_2_width" value="<?php echo esc_attr( $sf_2_width ); ?>" aria-describedby="sf-1-width-help">
		<div id="sf-1-width-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider width. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_2_height'] ) ) {
			$sf_2_height = $slider['sf_2_height'];
		} else {
			$sf_2_height = '100%';
		}
		?>
		<h5 for="sf_2_height" class="form-label sf-title"><?php esc_html_e( 'Height', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_2_height" name="sf_2_height" value="<?php echo esc_attr( $sf_2_height ); ?>" aria-describedby="sf_2_height-help">
		<div id="sf_2_height-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider height. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_2_startpoint" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Starting Slide Number', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="text" class="" id="sf_2_startpoint" name="sf_2_startpoint" value="1" disabled>
		<div id="sf_2_startpoint-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Defines which image slide to be the first one to show.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3 col-md-6">
		<h5 for="sf_2_jump_back" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Jump Back To Starting Slide', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_2_jump_back-1" name="sf_2_jump_back" value="true" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_2_jump_back-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_2_jump_back-2" name="sf_2_jump_back" value="false" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_2_jump_back-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_2_jump_back-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Slider should jump back to the starting slide number when the mouse leaves the slider.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3 col-md-6">
		<h5 for="sf_2_jumppoint_click" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Jump Back To Last Clicked Slide', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_2_jumppoint_click-1" name="sf_2_jumppoint_click" value="true" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_2_jumppoint_click-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_2_jumppoint_click-2" name="sf_2_jumppoint_click" value="false" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_2_jumppoint_click-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_2_jumppoint_click-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'If Jump Back To Slide is active then the image slide should jump back to the last clicked image slide.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_2_sorting'] ) ) {
			$sf_2_sorting = $slider['sf_2_sorting'];
		} else {
			$sf_2_sorting = 0;
		}
		?>
		<h5 for="sf_2_sorting-1" class="form-label sf-title"><?php esc_html_e( 'Slide Order By', 'slider-factory' ); ?></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_2_sorting-0" name="sf_2_sorting" value="0" <?php checked( $sf_2_sorting, 0 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_2_sorting-0"><?php esc_html_e( 'None', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_2_sorting-1" name="sf_2_sorting" value="1" <?php checked( $sf_2_sorting, 1 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_2_sorting-1"><?php esc_html_e( 'Slide ID Ascending', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_2_sorting-2" name="sf_2_sorting" value="2" <?php checked( $sf_2_sorting, 2 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_2_sorting-2"><?php esc_html_e( 'Slide ID Descending', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_2_sorting-3" name="sf_2_sorting" value="3" <?php checked( $sf_2_sorting, 3 ); ?> disabled>
			<label class="btn btn-outline-secondary sf-tooltip-disabled" for="sf_2_sorting-3"><?php esc_html_e( 'Slide ID Shuffle', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_2_sorting-4" name="sf_2_sorting" value="4" <?php checked( $sf_2_sorting, 4 ); ?> disabled>
			<label class="btn btn-outline-secondary sf-tooltip-disabled" for="sf_2_sorting-4"><?php esc_html_e( 'Slide Title Ascending', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_2_sorting-5" name="sf_2_sorting" value="5" <?php checked( $sf_2_sorting, 5 ); ?> disabled>
			<label class="btn btn-outline-secondary sf-tooltip-disabled" for="sf_2_sorting-5"><?php esc_html_e( 'Slide Title Descending', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_2_sorting-help" class="form-text sf-tooltip"><?php esc_html_e( 'Set slide loading order in slide show.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>:</strong> <?php esc_html_e( 'Select [None] to leave the order as it was at time of creation.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_2_custom_css" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Custom CSS', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<textarea type="text" class="form-control w-50" id="sf_2_custom_css" name="sf_2_custom_css" aria-describedby="sf-1-custom-css-help" disabled></textarea>
		<div id="sf_2_custom_css-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Apply the custom design code to this slider.', 'slider-factory' ); ?></div>
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
		var sf_2_width = jQuery("#sf_2_width").val();
		var sf_2_height = jQuery("#sf_2_height").val();
		var sf_2_sorting = jQuery("input[name='sf_2_sorting']:checked").val();
		
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
				'sf_2_width': sf_2_width,
				'sf_2_height': sf_2_height,
				'sf_2_sorting': sf_2_sorting,
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
