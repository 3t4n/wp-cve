<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} // Exit if accessed directly ?>

<div class="p-3 sf-panel-setting">
	<!-- Width -->
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_9_width'] ) ) {
			$sf_9_width = $slider['sf_9_width'];
		} else {
			$sf_9_width = '100%';
		}
		?>
		<h5 for="sf_9_width" class="form-label sf-title"><?php esc_html_e( 'Width', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_9_width" name="sf_9_width" value="<?php echo esc_attr( $sf_9_width ); ?>" aria-describedby="sf-9-width-help">
		<div id="sf_9_width-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider image width. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	
	<!-- Height -->
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_9_height'] ) ) {
			$sf_9_height = $slider['sf_9_height'];
		} else {
			$sf_9_height = '700px';
		}
		?>
		<h5 for="sf_9_height" class="form-label sf-title"><?php esc_html_e( 'Height', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_9_height" name="sf_9_height" value="<?php echo esc_attr( $sf_9_height ); ?>" aria-describedby="sf_9_height-help">
		<div id="sf_9_height-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider image height. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	<!-- Auto play On load -->
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_9_auto_play'] ) ) {
			$sf_9_auto_play = $slider['sf_9_auto_play'];
		} else {
			$sf_9_auto_play = 'true';
		}
		?>
		<h5 for="sf_9_auto_play" class="form-label sf-title"><?php esc_html_e( 'Auto Play on Load', 'slider-factory' ); ?></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_9_auto_play-1" name="sf_9_auto_play" value="true" <?php checked( $sf_9_auto_play, 'true' ); ?>>
			<label class="btn btn-outline-secondary" for="sf_9_auto_play-1"><?php esc_html_e( 'ON', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_9_auto_play-2" name="sf_9_auto_play" value="false" <?php checked( $sf_9_auto_play, 'false' ); ?>>
			<label class="btn btn-outline-secondary" for="sf_9_auto_play-2"><?php esc_html_e( 'OFF', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_9_auto_play-help" class="form-text sf-tooltip"><?php esc_html_e( 'Enable or disable automatic slide show on load.', 'slider-factory' ); ?></div>
	</div>
	
	<!-- Autoplay speed -->
	<div class="mb-3 col-md-6">
			<h5 for="sf_9_auto_play_speed" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Auto Play Speed', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
			<input type="range" class="form-range" id="sf_9_auto_play_speed-1" min="100" max="10000" step="100" value="4000" oninput="SFprintRange(this.id, this.value);" disabled>
			<button class="btn btn-sm btn-secondary pl-2" id="sf_9_auto_play_speed-1-value" disabled>4000</button> <?php esc_html_e( 'milliseconds', 'slider-factory' ); ?>
			<div id="sf_9_auto_play_speed-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Time interval in changing of slides.', 'slider-factory' ); ?></div>
		</div>
	<!-- Fade Speed -->
	<div class="mb-3 col-md-6">
		<h5 for="sf_9_fade_speed" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Fade In/Out Speed on slide change (set 0 for no fade)', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<input type="range" class="form-range" id="sf_9_fade_speed-1" min="0" max="6000" step="50" value="300" oninput="SFprintRange(this.id, this.value);" disabled>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_9_fade_speed-1-value" disabled>300</button> <?php esc_html_e( 'milliseconds', 'slider-factory' ); ?>
		<div id="sf_9_fade_speed-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Speed of the fade when switch to next slide.', 'slider-factory' ); ?></div>
	</div>
	<!-- Caption Background Color -->
	<div class="mb-3 col-md-6">
		<h5 for="sf_9_bgColor" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Caption Background Color', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<p><input type="color" class="col-2" id="sf_9_bgColor" value="#252525" oninput="SFprintRange(this.id, this.value);" disabled></p>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_9_bgColor-value" disabled>#252525</button> <?php esc_html_e( 'HEX', 'slider-factory' ); ?>
		<div id="sf_9_bgColor-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Choose the caption background color.', 'slider-factory' ); ?></div>
	</div>
	
	<!-- Caption caption Color -->
	<div class="mb-3 col-md-6">
		<h5 for="sf_9_textColor" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Caption Text Color', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<p><input type="color" class="col-2" id="sf_9_textColor" value="#ffffff" oninput="SFprintRange(this.id, this.value);" disabled></p>
		<button class="btn btn-sm btn-secondary pl-2" id="sf_9_textColor-value" disabled>#ffffff</button> <?php esc_html_e( 'HEX', 'slider-factory' ); ?>
		<div id="sf_9_textColor-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Choose the caption text color.', 'slider-factory' ); ?></div>
	</div>

	<!-- Sorting -->
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_9_sorting'] ) ) {
			$sf_9_sorting = $slider['sf_9_sorting'];
		} else {
			$sf_9_sorting = 0;
		}
		?>
		<h5 for="sf_9_sorting-1" class="form-label sf-title"><?php esc_html_e( 'Slide Order By', 'slider-factory' ); ?></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_9_sorting-0" name="sf_9_sorting" value="0" <?php checked( $sf_9_sorting, 0 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_9_sorting-0"><?php esc_html_e( 'None', 'slider-factory' ); ?></label>
		
			<input type="radio" class="btn-check" id="sf_9_sorting-1" name="sf_9_sorting" value="1" <?php checked( $sf_9_sorting, 1 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_9_sorting-1"><?php esc_html_e( 'Slide ID Ascending', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_9_sorting-2" name="sf_9_sorting" value="2" <?php checked( $sf_9_sorting, 2 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_9_sorting-2"><?php esc_html_e( 'Slide ID Descending', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_9_sorting-3" name="sf_9_sorting" value="3" <?php checked( $sf_9_sorting, 3 ); ?> disabled>
			<label class="btn btn-outline-secondary sf-tooltip-disabled" for="sf_9_sorting-3"><?php esc_html_e( 'Slide ID Shuffle', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_9_sorting-4" name="sf_9_sorting" value="4" <?php checked( $sf_9_sorting, 4 ); ?> disabled>
			<label class="btn btn-outline-secondary sf-tooltip-disabled" for="sf_9_sorting-4"><?php esc_html_e( 'Slide Title Ascending', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_9_sorting-5" name="sf_9_sorting" value="5" <?php checked( $sf_9_sorting, 5 ); ?> disabled>
			<label class="btn btn-outline-secondary sf-tooltip-disabled" for="sf_9_sorting-5"><?php esc_html_e( 'Slide Title Descending', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_6_sorting-help" class="form-text sf-tooltip"><?php esc_html_e( 'Set slide loading order in slide show. ', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>:</strong> <?php esc_html_e( 'Select [None] to leave the order as it is above.', 'slider-factory' ); ?></div>
	</div>
		
	<!-- Custom CSS -->
	<div class="mb-3">
		<h5 for="sf_9_custom_css" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Custom CSS', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5>
		<textarea type="text" class="form-control w-50" id="sf_9_custom_css" name="sf_9_custom_css" aria-describedby="sf-9-custom-css-help" disabled></textarea>
		<div id="sf_9_custom_css-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Apply the custom design code to this slider.', 'slider-factory' ); ?></div>
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
		var sf_9_width = jQuery("#sf_9_width").val();
		var sf_9_height = jQuery("#sf_9_height").val();
		var sf_9_auto_play = jQuery("input[name='sf_9_auto_play']:checked").val();
		var sf_9_sorting = jQuery("input[name='sf_9_sorting']:checked").val();
		
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
				'sf_9_width': sf_9_width,
				'sf_9_height': sf_9_height,
				'sf_9_auto_play':sf_9_auto_play,
				'sf_9_sorting': sf_9_sorting,
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
