<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} // Exit if accessed directly ?>

<div class="p-3 sf-panel-setting">
	<div class="mb-3">
		<div id="sf-11-imply-help" class="form-text sf-tooltip"><?php esc_html_e( 'Notes to imply Slides details :', 'slider-factory' ); ?></div>
		<div id="sf-11-imply-help2" class="form-text sf-tooltip"><?php esc_html_e( 'Slide Title will be displayed as Product Name/Title.', 'slider-factory' ); ?></div>
		<div id="sf-11-imply-help3" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( '< Slide Button text 1 > will be displayed as Price. Use appropriate Currency sign also.', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></div>
		<div id="sf-11-imply-help4" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( '< Slide Button text 2 > will be displayed as < Buy This Item Now > button text', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></div>
		<div id="sf-11-imply-help5" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( '< Place redirection URL to buy product in < Slide Button 2 URL >', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_11_width'] ) ) {
			$sf_11_width = $slider['sf_11_width'];
		} else {
			$sf_11_width = '100%';
		}
		?>
		<h5 for="sf_11_width" class="form-label sf-title"><?php esc_html_e( 'Width', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_11_width" name="sf_11_width" value="<?php echo esc_attr( $sf_11_width ); ?>" aria-describedby="sf-11-width-help">
		<div id="sf_11_width-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider width. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_11_height'] ) ) {
			$sf_11_height = $slider['sf_11_height'];
		} else {
			$sf_11_height = '750px';
		}
		?>
		<h5 for="sf_11_height" class="form-label sf-title"><?php esc_html_e( 'Height', 'slider-factory' ); ?></h5>
		<input type="text" class="form-control w-50" id="sf_11_height" name="sf_11_height" value="<?php echo esc_attr( $sf_11_height ); ?>" aria-describedby="sf_11_height-help">
		<div id="sf_11_height-help" class="form-text sf-tooltip"><?php esc_html_e( 'Define the slider height. You can use any unit you like: percent 0% to 100% OR pixels 200px/300px/500px etc.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_11_slider_header" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Slider Header', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5></h5>
		<input type="text" class="" id="sf_11_slider_header" name="sf_11_slider_header" value="" disabled>
		<div id="sf_11_slider_header-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Name to be displayed on product Carousel Head.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<?php
		if ( isset( $slider['sf_11_sorting'] ) ) {
			$sf_11_sorting = $slider['sf_11_sorting'];
		} else {
			$sf_11_sorting = 0;
		}
		?>
		<h5 for="sf_11_sorting-1" class="form-label sf-title"><?php esc_html_e( 'Slide Order By', 'slider-factory' ); ?></h5>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" id="sf_11_sorting-0" name="sf_11_sorting" value="0" <?php checked( $sf_11_sorting, 0 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_11_sorting-0"><?php esc_html_e( 'None', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_11_sorting-1" name="sf_11_sorting" value="1" <?php checked( $sf_11_sorting, 1 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_11_sorting-1"><?php esc_html_e( 'Slide ID Ascending', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_11_sorting-2" name="sf_11_sorting" value="2" <?php checked( $sf_11_sorting, 2 ); ?>>
			<label class="btn btn-outline-secondary" for="sf_11_sorting-2"><?php esc_html_e( 'Slide ID Descending', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_11_sorting-3" name="sf_11_sorting" value="3" <?php checked( $sf_11_sorting, 3 ); ?> disabled>
			<label class="btn btn-outline-secondary sf-tooltip-disabled" for="sf_11_sorting-3"><?php esc_html_e( 'Slide ID Shuffle', 'slider-factory' ); ?></label>
			
			<input type="radio" class="btn-check" id="sf_11_sorting-4" name="sf_11_sorting" value="4" <?php checked( $sf_11_sorting, 4 ); ?> disabled>
			<label class="btn btn-outline-secondary sf-tooltip-disabled" for="sf_11_sorting-4"><?php esc_html_e( 'Slide Title Ascending', 'slider-factory' ); ?></label>

			<input type="radio" class="btn-check" id="sf_11_sorting-5" name="sf_11_sorting" value="5" <?php checked( $sf_11_sorting, 5 ); ?> disabled>
			<label class="btn btn-outline-secondary sf-tooltip-disabled" for="sf_11_sorting-5"><?php esc_html_e( 'Slide Title Descending', 'slider-factory' ); ?></label>
		</div>
		<div id="sf_11_sorting-help" class="form-text sf-tooltip"><?php esc_html_e( 'Set slide loading order in slide show.', 'slider-factory' ); ?><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>:</strong> <?php esc_html_e( 'Select [None] to leave the order as it was at time of creation.', 'slider-factory' ); ?></div>
	</div>
	
	<div class="mb-3">
		<h5 for="sf_11_custom_css" class="form-label sf-title sf-title-disabled"><?php esc_html_e( 'Custom CSS', 'slider-factory' ); ?> <sup><a class="badge rounded-pill bg-info  sf-buypro-link" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" target="_blank"><span class="sf-pro-tag"></span></a></sup></h5></h5>
		<textarea type="text" class="form-control w-50" id="sf_11_custom_css" name="sf_11_custom_css" aria-describedby="sf-11-custom-css-help" disabled></textarea>
		<div id="sf_11_custom_css-help" class="form-text sf-tooltip sf-tooltip-disabled"><?php esc_html_e( 'Apply the custom design code to this slider.', 'slider-factory' ); ?></div>
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
		var sf_11_width = jQuery("#sf_11_width").val();
		var sf_11_height = jQuery("#sf_11_height").val();
		var sf_11_sorting = jQuery("input[name='sf_11_sorting']:checked").val();
		
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
				'sf_11_width': sf_11_width,
				'sf_11_height': sf_11_height,
				'sf_11_sorting': sf_11_sorting,
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
