<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} // Exit if accessed directly ?>

<div class="p-3 sf-panel-setting">
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_7_width'] ) ) {
			$sf_7_width = $slider['sf_7_width'];
		} else {
			$sf_7_width = '100%';
		}
		?>
		<h5 for="sf_7_width" class="form-label sf-title"><?php esc_html_e( 'Width', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_7_width" name="sf_7_width" value="<?php echo esc_attr( $sf_7_width ); ?>" aria-describedby="sf_7_width-help">
		<div id="sf_7_width-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider container width. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_7_height'] ) ) {
			$sf_7_height = $slider['sf_7_height'];
		} else {
			$sf_7_height = '100%';
		}
		?>
		<h5 for="sf_7_height" class="form-label sf-title"><?php esc_html_e( 'Height', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_7_height" name="sf_7_height" value="<?php echo esc_attr( $sf_7_height ); ?>" aria-describedby="sf_7_height-help">
		<div id="sf_7_height-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider container height. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_7_slide_circle_size'] ) ) {
			$sf_7_slide_circle_size = $slider['sf_7_slide_circle_size'];
		} else {
			$sf_7_slide_circle_size = 360;
		}
		?>
		<h5 for="sf_7_slide_circle_size" class="form-label sf-title"><?php esc_html_e( 'Slide Circle Size', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_7_slide_circle_size" name="sf_7_slide_circle_size" value="<?php echo esc_attr( $sf_7_slide_circle_size ); ?>" aria-describedby="sf_7_slide_circle_size-help">
		<div id="sf_7_slide_circle_size-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slide circle size. (any number)', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_7_inner_circle_size'] ) ) {
			$sf_7_inner_circle_size = $slider['sf_7_inner_circle_size'];
		} else {
			$sf_7_inner_circle_size = 480;
		}
		?>
		<h5 for="sf_7_inner_circle_size" class="form-label sf-title"><?php esc_html_e( 'Inner Circle Size', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_7_inner_circle_size" name="sf_7_inner_circle_size" value="<?php echo esc_attr( $sf_7_inner_circle_size ); ?>" aria-describedby="sf_7_inner_circle_size-help">
		<div id="sf_7_inner_circle_size-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the inner slide circle size. (any number)', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_7_circle_type-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Circle Type', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_7_circle_type-1" name="sf_7_circle_type" value="half" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_7_circle_type-1"><?php esc_html_e( 'Half', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_7_circle_type-2" name="sf_7_circle_type" value="full" disabled>
			<label class="btn btn-outline-secondary" for="sf_7_circle_type-2"><?php esc_html_e( 'Full', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_7_circle_type-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Select half or full circle for the slider.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3 col-md-6">
		<h5 for="sf_7_Color" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Title and Description Text Color', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<p><input type="color" class="col-2" id="sf_7_Color" value="#eeeeee" oninput="SFprintRange(this.id, this.value);" disabled></p>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_7_Color-value" disabled>#eeeeee</button> <?php esc_html_e( 'HEX', 'slider-factory' ); ?>
		<div id="sf_7_Color-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Choose the title and description text color.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3 col-md-6">
		<h5 for="sf_7_shadowColor" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Title and Description Shadow Color', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<p><input type="color" class="col-2" id="sf_7_shadowColor" value="#616161" oninput="SFprintRange(this.id, this.value);" disabled></p>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_7_shadowColor-value" disabled>#616161</button> <?php esc_html_e( 'HEX', 'slider-factory' ); ?>
		<div id="sf_7_shadowColor-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Choose the title and description text shadow color.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_7_auto_play'] ) ) {
			$sf_7_auto_play = $slider['sf_7_auto_play'];
		} else {
			$sf_7_auto_play = 'true';
		}
		?>
		<h5 for="sf_7_auto_play" class="form-label sf-title"><?php esc_html_e( 'Auto Play', 'slider-factory' ); ?></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_7_auto_play-1" name="sf_7_auto_play" value="true" <?php checked( $sf_7_auto_play, 'true' ); ?>>
			<label class="btn btn-outline-secondary" for="sf_7_auto_play-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_7_auto_play-2" name="sf_7_auto_play" value="false" <?php checked( $sf_7_auto_play, 'false' ); ?>>
			<label class="btn btn-outline-secondary" for="sf_7_auto_play-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_7_auto_play-help" class="form-text sf-tooltip"><?php esc_html_e( 'Enable or disable auto play feature.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3 col-md-6">
		<h5 for="sf_7_auto_play_speed" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Auto Play Speed', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="range" class="form-range" id="sf_7_auto_play_speed" min="100" max="7000" step="50" value="4000" oninput="SFprintRange(this.id, this.value);" disabled>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_7_auto_play_speed-value" disabled>4000</button> <?php esc_html_e( 'milliseconds', 'slider-factory' ); ?>
		<div id="sf_7_auto_play_speed-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Adjust the speed of slide show in milliseconds.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3 col-md-6">
		<h5 for="sf_7_rotation_speed" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Rotation Transition Speed', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="range" class="form-range" id="sf_7_rotation_speed" min="100" max="4000" step="50" value="750" oninput="SFprintRange(this.id, this.value);" disabled>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_7_rotation_speed-value" disabled>750</button> <?php esc_html_e( 'milliseconds', 'slider-factory' ); ?>
		<div id="sf_7_rotation_speed-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Adjust the speed of slide rotation transition in milliseconds.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_7_mouse_draggable" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Mouse Draggable', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_7_mouse_draggable-1" name="sf_7_mouse_draggable" value="true" disabled>
			<label class="btn btn-outline-secondary" for="sf_7_mouse_draggable-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_7_mouse_draggable-2" name="sf_7_mouse_draggable" value="false" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_7_mouse_draggable-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_7_mouse_draggable-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Enable or disable mouse draggable feature.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_7_navigation_arrow-1" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Slider Navigation Arrow', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_7_navigation_arrow-1" name="sf_7_navigation_arrow" value="true" autocomplete="off" checked disabled>
			<label class="btn btn-outline-secondary" for="sf_7_navigation_arrow-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_7_navigation_arrow-2" name="sf_7_navigation_arrow" value="false" autocomplete="off" disabled>
			<label class="btn btn-outline-secondary" for="sf_7_navigation_arrow-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_7_navigation_arrow-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Hide or display next and previous slide navigation arrow button over the slides.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_7_sorting'] ) ) {
			$sf_7_sorting = $slider['sf_7_sorting'];
		} else {
			$sf_7_sorting = 0;
		}
		?>
		<h5 for="sf_7_sorting-1" class="form-label sf-title"><?php esc_html_e( 'Slide Order By', 'slider-factory' ); ?></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_7_sorting-0" name="sf_7_sorting" value="0" <?php checked( $sf_7_sorting, 0 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_7_sorting-0"><?php esc_html_e( 'None', 'slider-factory' ); ?></label>
		
			<input type="radio" class="btn-check" id="sf_7_sorting-1" name="sf_7_sorting" value="1" <?php checked( $sf_7_sorting, 1 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_7_sorting-1"><?php esc_html_e( 'Slide ID Ascending', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_7_sorting-2" name="sf_7_sorting" value="2" <?php checked( $sf_7_sorting, 2 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_7_sorting-2"><?php esc_html_e( 'Slide ID Descending', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_7_sorting-3" name="sf_7_sorting" value="3" <?php checked( $sf_7_sorting, 3 ); ?> disabled>
			<label class="btn btn-outline-secondary sf-tooltip-disabled" for="sf_7_sorting-3"><?php esc_html_e( 'Slide ID Shuffle', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_7_sorting-4" name="sf_7_sorting" value="4" <?php checked( $sf_7_sorting, 4 ); ?> disabled>
			<label class="btn btn-outline-secondary sf-tooltip-disabled" for="sf_7_sorting-4"><?php esc_html_e( 'Slide Title Ascending', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_7_sorting-5" name="sf_7_sorting" value="5" <?php checked( $sf_7_sorting, 5 ); ?> disabled>
			<label class="btn btn-outline-secondary sf-tooltip-disabled" for="sf_7_sorting-5"><?php esc_html_e( 'Slide Title Descending', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_7_sorting-help" class="form-text sf-tooltip"><?php esc_html_e( 'Set slide loading order in slide show.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>:</strong> <?php esc_html_e( 'Select [None] to leave the order as it was at time of creation.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_7_custom_css-css" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Custom CSS', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<textarea type="text" class="form-control w-50" id="sf_7_custom_css" name="sf_7_custom_css" aria-describedby="sf_7_custom_css-css-help" disabled></textarea>
		<div id="sf_7_custom_css-css-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Apply the custom design code to this slider.', 'slider-factory' ); ?></div>
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
		var sf_7_width = jQuery("#sf_7_width").val();
		var sf_7_height = jQuery("#sf_7_height").val();
		var sf_7_slide_circle_size = jQuery("#sf_7_slide_circle_size").val();
		var sf_7_inner_circle_size = jQuery("#sf_7_inner_circle_size").val();
		var sf_7_auto_play = jQuery("input[name='sf_7_auto_play']:checked").val();
		var sf_7_sorting = jQuery("input[name='sf_7_sorting']:checked").val();
		
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
				'sf_7_width': sf_7_width,
				'sf_7_height': sf_7_height,
				'sf_7_slide_circle_size': sf_7_slide_circle_size,
				'sf_7_inner_circle_size': sf_7_inner_circle_size,
				'sf_7_auto_play': sf_7_auto_play,
				'sf_7_sorting': sf_7_sorting,
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
