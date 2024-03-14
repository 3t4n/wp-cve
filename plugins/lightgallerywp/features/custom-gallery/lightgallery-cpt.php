<?php
namespace LightGallery;

require_once dirname( __FILE__ ) . '/shortcode.php';
require_once dirname( __FILE__ ) . '/invoke-script.php';
require_once dirname( __FILE__ ) . '/embed-settings.php';
require_once dirname( __FILE__ ) . '/layout-settings.php';
require_once dirname( __FILE__ ) . '/advanced-settings.php';

/**
 * Enqueue necessary scripts in the Admin Page.
 */
function lightgallerywp_cpt_admin_enqueue_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'lightgallerywp-admin-script', plugins_url( '../assets/js/lightgallery-admin.js', dirname( __FILE__ ) ), [], '1.0', 'all' );
	wp_enqueue_style( 'lightgallerywp-admin-styles', plugins_url( '../assets/css/lightgallery-admin.css', dirname( __FILE__ ) ), [], '1.0', 'all' );
}

/**
 * Register the LightGallery post type and create the necessary settings fields.
 */
add_action(
	'plugins_loaded',
	function() {
		$boolean_options = [
			[
				'text'  => 'Yes',
				'value' => 'true',
			],
			[
				'text'  => 'No',
				'value' => 'false',
			],
		];
		new SmartlogixCPTWrapper(
			[
				'name'               => 'lightgalleries',
				'singular_name'      => 'LightGallery',
				'plural_name'        => 'LightGallery',
				'callback_functions' => [
					'meta_box_content'      => 'LightGallery\lightgallerywp_cpt_get_slides',
					'admin_enqueue_scripts' => 'LightGallery\lightgallerywp_cpt_admin_enqueue_scripts',
				],
				'metaboxes'          => [
					'light_gallery_slides'   => 'LightGallery Slides',
					'light_gallery_settings' => 'LightGallery Settings',
				],
				'controls'           => apply_filters(
					'lightgallerywp_cpt_controls',
					array_merge(
						lightgallerywp_get_custom_gallery_embed_settings( $boolean_options ),
						lightgallerywp_get_custom_gallery_layout_settings( $boolean_options ),
						lightgallerywp_get_basic_settings( $boolean_options ),
						lightgallerywp_get_pro_upsell_settings( $boolean_options ),
						lightgallerywp_get_custom_gallery_advanced_settings( $boolean_options )
					),
					$boolean_options
				),
			]
		);
	}
);

/**
 * Function to display the Slides Management Metabox.
 *
 * @param array $args Configuration data associated with the custom post type.
 */
function lightgallerywp_cpt_get_slides( $args ) {
	if ( 'light_gallery_slides' === $args['metaboxID'] ) {
		echo '<div class="slides_wrapper" style="margin: 15px 0 0;">';
			echo '<div class="slides_add_new_wrapper">';
				echo '<input id="wp_lightgalleries_data_images_button_add_new_ignore" type="button" value="Add Slides" class="input button-primary" />';
			echo '</div>';
			echo '<div class="slides_current_wrapper" ' . ( ( isset( $args['data']['slide_image_ignore'] ) && is_array( $args['data']['slide_image_ignore'] ) && ( count( $args['data']['slide_image_ignore'] ) > 0 ) ) ? 'style="display: block;"' : 'style="display: none;"' ) . '>';
				echo '<div class="lg-tab-content lg-slide-content" style="margin: 15px 0 0; padding: 0 15px; border: 1px solid #ddd; border-radius: 5px; position: relative;">';
					echo '<label style="font-weight: bold; position: absolute; left: 15px; top: -10px; background: #FFFFFF; padding: 0px 10px;">Slides</label>';
		if ( isset( $args['data']['slide_image_ignore'] ) && is_array( $args['data']['slide_image_ignore'] ) && ( count( $args['data']['slide_image_ignore'] ) > 0 ) ) {
			$index              = 0;
			$slide_widths       = ( ( isset( $args['data']['slide_width_ignore'] ) && is_array( $args['data']['slide_width_ignore'] ) ) ? $args['data']['slide_width_ignore'] : [] );
			$slide_heights      = ( ( isset( $args['data']['slide_height_ignore'] ) && is_array( $args['data']['slide_height_ignore'] ) ) ? $args['data']['slide_height_ignore'] : [] );
			$slide_titles       = ( ( isset( $args['data']['slide_title_ignore'] ) && is_array( $args['data']['slide_title_ignore'] ) ) ? $args['data']['slide_title_ignore'] : [] );
			$slide_descriptions = ( ( isset( $args['data']['slide_description_ignore'] ) && is_array( $args['data']['slide_description_ignore'] ) ) ? $args['data']['slide_description_ignore'] : [] );
			$slide_videos       = ( ( isset( $args['data']['slide_video_ignore'] ) && is_array( $args['data']['slide_video_ignore'] ) ) ? $args['data']['slide_video_ignore'] : [] );
			if ( isset( $args['data']['slide_image_ignore'] ) && is_array( $args['data']['slide_image_ignore'] ) ) {
				foreach ( $args['data']['slide_image_ignore'] as $slide_image ) {
					echo '<fieldset class="slide_current_wrapper">';
						echo '<div class="slide_current_wrapper_inner">';
							echo '<div class="lg-fileupload-image">';
								echo wp_kses( SmartlogixControlsWrapper::get_control( 'upload', 'Slide Image', 'wp_lightgalleries_data[slide_image_ignore][]', 'wp_lightgalleries_data[slide_image_ignore][]', $slide_image, '', '' ), SmartlogixControlsWrapper::get_allowed_html() );
							echo '</div>';
							echo '<div class="lg-fileupload-form">';
								echo '<div class="lg-field-group">';
									echo wp_kses( SmartlogixControlsWrapper::get_control( 'number-placeholder', 'Thumbnails Width', 'wp_lightgalleries_data[slide_width_ignore][]', 'wp_lightgalleries_data[slide_width_ignore][]', ( ( isset( $slide_widths[ $index ] ) ) ? $slide_widths[ $index ] : '' ), '', '' ), SmartlogixControlsWrapper::get_allowed_html() );
									echo wp_kses( SmartlogixControlsWrapper::get_control( 'number-placeholder', 'Thumbnails Height', 'wp_lightgalleries_data[slide_height_ignore][]', 'wp_lightgalleries_data[slide_height_ignore][]', ( ( isset( $slide_heights[ $index ] ) ) ? $slide_heights[ $index ] : '' ), '', '' ), SmartlogixControlsWrapper::get_allowed_html() );
								echo '</div>';
								echo wp_kses( SmartlogixControlsWrapper::get_control( 'text', 'Slide Title', 'wp_lightgalleries_data[slide_title_ignore][]', 'wp_lightgalleries_data[slide_title_ignore][]', ( ( isset( $slide_titles[ $index ] ) ) ? $slide_titles[ $index ] : '' ), '', '' ), SmartlogixControlsWrapper::get_allowed_html() );
								echo wp_kses( SmartlogixControlsWrapper::get_control( 'text', 'Slide Description', 'wp_lightgalleries_data[slide_description_ignore][]', 'wp_lightgalleries_data[slide_description_ignore][]', ( ( isset( $slide_descriptions[ $index ] ) ) ? $slide_descriptions[ $index ] : '' ), '', '' ), SmartlogixControlsWrapper::get_allowed_html() );
								echo wp_kses( SmartlogixControlsWrapper::get_control( 'text', 'Slide Video', 'wp_lightgalleries_data[slide_video_ignore][]', 'wp_lightgalleries_data[slide_video_ignore][]', ( ( isset( $slide_videos[ $index ] ) ) ? $slide_videos[ $index ] : '' ), '', 'Optional : Supports Youtube, Vimeo and Wistia videos.' ), SmartlogixControlsWrapper::get_allowed_html() );
							echo '</div>';
						echo '<span class="slide_current_remove"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span>';
						echo '</div>';
					echo '</fieldset>';
					$index++;
				}
			}
		}
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}
}

