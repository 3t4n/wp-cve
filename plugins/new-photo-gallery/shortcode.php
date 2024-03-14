<?php
// New Photo Gallery Shortcode

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_shortcode( 'NPG', 'awl_photo_gallery_shortcode' );
function awl_photo_gallery_shortcode( $post_id ) {
	ob_start();
	// JS
	wp_enqueue_script( 'npg-ig-bootstrap-js' );
	wp_enqueue_script( 'imagesloaded' );
	wp_enqueue_script( 'awplife-npg-isotope-js' );

	// awp custom bootstrap css
	wp_enqueue_style( 'npg-bootstrap-css' );

	$gallery_settings = unserialize( base64_decode( get_post_meta( $post_id['id'], 'awl_lg_settings_' . $post_id['id'], true ) ) );
	// print_r($gallery_settings);

	$light_image_gallery_id = $post_id['id'];

	// columns settings
	$gal_thumb_size     = $gallery_settings['gal_thumb_size'];
	$col_large_desktops = $gallery_settings['col_large_desktops'];
	$col_desktops       = $gallery_settings['col_desktops'];
	$col_tablets        = $gallery_settings['col_tablets'];
	$col_phones         = $gallery_settings['col_phones'];

	// lightbox style
	if ( isset( $gallery_settings['light-box'] ) ) {
		$light_box = $gallery_settings['light-box'];
	} else {
		$light_box = 1;
	}

	// transition effect
	if ( isset( $gallery_settings['transition_effects'] ) ) {
		$transition_effects = $gallery_settings['transition_effects'];
	} else {
		$transition_effects = 'lg-fade';
	}
	if ( $transition_effects != 'none' ) {
		// transition effects css
		wp_enqueue_style( 'awplife-npg-lg-transitions-css', NPG_PLUGIN_URL . 'lightbox/light-gallery/css/lg-transitions.css' );
	}

	// hover effect
	if ( isset( $gallery_settings['image_hover_effect_type'] ) ) {
		$image_hover_effect_type = $gallery_settings['image_hover_effect_type'];
	} else {
		$image_hover_effect_type = 'no';
	}
	if ( $image_hover_effect_type == 'no' ) {
		$image_hover_effect = '';
	} else {
		// hover CSS
		wp_enqueue_style( 'lg-hover-css', NPG_PLUGIN_URL . 'css/hover.css' );
	}

	if ( $image_hover_effect_type == 'sg' ) {
		if ( isset( $gallery_settings['image_hover_effect_four'] ) ) {
			$image_hover_effect = $gallery_settings['image_hover_effect_four'];
		} else {
			$image_hover_effect = 'hvr-box-shadow-outset';
		}
	}

	if ( isset( $gallery_settings['title_color'] ) ) {
		$title_color = $gallery_settings['title_color'];
	} else {
		$title_color = 'white';
	}
	if ( isset( $gallery_settings['tool_color'] ) ) {
		$tool_color = $gallery_settings['tool_color'];
	} else {
		$tool_color = 'gold';
	}
	if ( isset( $gallery_settings['thumbnails_spacing'] ) ) {
		$thumbnails_spacing = $gallery_settings['thumbnails_spacing'];
	} else {
		$thumbnails_spacing = 1;
	}
	if ( isset( $gallery_settings['custom-css'] ) ) {
		$custom_css = $gallery_settings['custom-css'];
	} else {
		$custom_css = '';
	}
	?>
	<!-- CSS Part Start From Here-->
	<style>
		#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> a {
			text-decoration: none !important;
			box-shadow: 0 0px 0 0 currentcolor !important;
		}
		<?php if ( $thumbnails_spacing == 0 ) { ?>
			#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .thumbnail {
				border: 0px !important;
				border-radius: 0px !important;
				display: block;
				line-height: 1.42857;
				margin-bottom: 0px !important;
				padding: 0px !important;
			}
			#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-xs-1, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-sm-1, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-md-1, 
			#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-lg-1, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-xs-2, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-sm-2, 
			#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-md-2, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-lg-2, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-xs-3, 
			#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-sm-3, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-md-3, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-lg-3, 
			#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-xs-4, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-sm-4, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-md-4, 
			#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-lg-4, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-xs-5, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-sm-5, 
			#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-md-5, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-lg-5, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-xs-6, 
			#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-sm-6, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-md-6, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-lg-6, 
			#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-xs-7, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-sm-7, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-md-7, 
			#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-lg-7, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-xs-8, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-sm-8, 
			#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-md-8, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-lg-8, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-xs-9, 
			#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-sm-9, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-md-9, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-lg-9, 
			#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-xs-10, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-sm-10, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-md-10, 
			#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-lg-10, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-xs-11, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-sm-11, 
			#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-md-11, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-lg-11, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-xs-12, 
			#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-sm-12, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-md-12, #animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> .col-lg-12 {
				padding-left: 0px !important;
				padding-right: 0px !important;
			}
		<?php } ?>
		
			#animated-thumbnails-<?php echo esc_attr( $light_image_gallery_id ); ?> img {
				width: 100% !important;
				height: auto !important;
			}

			.lg-icon {
				color : <?php echo esc_attr( $tool_color ); ?> !important;
			}
			.pg-title {
				color: <?php echo esc_attr( $title_color ); ?> !important;
			}
		<?php echo $custom_css; ?>
	</style>
	<?php
	require 'output.php';
	return ob_get_clean();
}
?>
