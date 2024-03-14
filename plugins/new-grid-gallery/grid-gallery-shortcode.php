<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
add_shortcode( 'GGAL', 'awl_grid_gallery_shortcode' );
function awl_grid_gallery_shortcode( $post_id ) {
	ob_start();
	// CSS
	wp_enqueue_style( 'gg-bootstrap-css' );
	wp_enqueue_style( 'gg-gridder-css' );
	wp_enqueue_style( 'gg-demo-css' );
	wp_enqueue_style( 'gg-font-awesome-css' );

	// JS
	wp_enqueue_script( 'awl-gg-bootstrap-js' );
	wp_enqueue_script( 'awl-gridder-js' );

	// unsterilized
	$gg_settings     = unserialize( base64_decode( get_post_meta( $post_id['id'], 'awl_gg_settings_' . $post_id['id'], true ) ) );
	$grid_gallery_id = $post_id['id'];

	if ( isset( $gg_settings['animation_speed'] ) ) {
		$animation_speed = $gg_settings['animation_speed'];
	} else {
		$animation_speed = 400;
	}
	if ( isset( $gg_settings['thumbnail_border'] ) ) {
		$thumbnail_border = $gg_settings['thumbnail_border'];
	} else {
		$thumbnail_border = 'hide';
	}
	if ( $thumbnail_border == 'hide' ) {
		$thumb_bor = ' '; }
	if ( $thumbnail_border == 'show' ) {
		$thumb_bor = 'thumbnail'; }

	// hover effect
	if ( isset( $gg_settings['image_hover_effect_type'] ) ) {
		$image_hover_effect_type = $gg_settings['image_hover_effect_type'];
	} else {
		$image_hover_effect_type = 'no';
	}
	if ( $image_hover_effect_type == 'no' ) {
		$image_hover_effect = '';
	} else {
		// hover CSS
		wp_enqueue_style( 'ggp-hover-css', GG_PLUGIN_URL . 'css/hover.css' );
	}
	if ( $image_hover_effect_type == '2d' ) {
		if ( isset( $gg_settings['image_hover_effect_one'] ) ) {
			$image_hover_effect = $gg_settings['image_hover_effect_one'];
		} else {
			$image_hover_effect = 'hvr-buzz';
		}
	}
	if ( $image_hover_effect_type == 'sg' ) {
		if ( isset( $gg_settings['image_hover_effect_four'] ) ) {
			$image_hover_effect = $gg_settings['image_hover_effect_four'];
		} else {
			$image_hover_effect = 'hvr-box-shadow-outset';
		}
	}

	if ( isset( $gg_settings['thumb_title'] ) ) {
		$thumb_title = $gg_settings['thumb_title'];
	} else {
		$thumb_title = 'show';
	}
	if ( isset( $gg_settings['scroll_loading'] ) ) {
		$scroll_loading = $gg_settings['scroll_loading'];
	} else {
		$scroll_loading = 'true';
	}
	if ( isset( $gg_settings['gal_thumb_size'] ) ) {
		$gal_thumb_size = $gg_settings['gal_thumb_size'];
	} else {
		$gal_thumb_size = 'medium';
	}
	if ( isset( $gg_settings['no_spacing'] ) ) {
		$no_spacing = $gg_settings['no_spacing'];
	} else {
		$no_spacing = 'no';
	}
	if ( isset( $gg_settings['tdp_setting'] ) ) {
		$tdp_setting = $gg_settings['tdp_setting'];
	} else {
		$tdp_setting = 'top';
	}
	if ( isset( $gg_settings['tdp_setting2'] ) ) {
		$tdp_setting2 = $gg_settings['tdp_setting2'];
	} else {
		$tdp_setting2 = 'left';
	}
	if ( isset( $gg_settings['title_setting'] ) ) {
		$title_setting = $gg_settings['title_setting'];
	} else {
		$title_setting = 'show';
	}
	if ( isset( $gg_settings['title_color'] ) ) {
		$title_color = $gg_settings['title_color'];
	} else {
		$title_color = 'white';
	}
	if ( isset( $gg_settings['custom-css'] ) ) {
		$custom_css = $gg_settings['custom-css'];
	} else {
		$custom_css = '';
	}
	if ( isset( $gg_settings['nbp_setting2'] ) ) {
		$nbp_setting2 = $gg_settings['nbp_setting2'];
	} else {
		$nbp_setting2 = 'left';
	}
	?>
	<style>
	
	<?php if ( $thumbnail_border == 'show' ) { ?>
		.gridder-show {
			margin-bottom:-15px !important;
		}
	<?php } ?>
	
	<?php if ( $no_spacing == 'yes' ) { ?>
		.gg-gridder-list-<?php echo esc_attr( $grid_gallery_id ); ?>:nth-child(n) {
			margin: 0% !important;
		}
		
		.gridder-show {
			width: 95.9% !important;
			/* margin-bottom:-7px; */
		}
	<?php } // end no spacing if ?>
	<?php if ( $no_spacing == 'no' ) { ?>
		.gg-gridder-list-<?php echo esc_attr( $grid_gallery_id ); ?>:nth-child(n) {
			/* margin-bottom:-7px; */
		}
		.gridder-show {
			padding-left: 0% !important;
			padding-right: 1% !important;
			margin-bottom:5px;
		}
		.gg-<?php echo esc_attr( $grid_gallery_id ); ?>{
		margin:0px !important;
		}
		<?php
	}
	?>
	
	<?php
	// column setting
	if ( isset( $gg_settings['col_large_desktops'] ) ) {
		$col_large_desktops = $gg_settings['col_large_desktops'];
	} else {
		$col_large_desktops = '3_column';
	}
	if ( $no_spacing == 'yes' ) {
		$col_width = '32%';
		if ( $col_large_desktops == '4_column' ) {
			$col_width = '24%'; }
	}
	if ( $no_spacing == 'no' ) {
		$col_width = '32.33%';
		if ( $col_large_desktops == '4_column' ) {
			$col_width = '24%'; }
	}
	?>
	.gg-gridder-list-<?php echo esc_attr( $grid_gallery_id ); ?> {
		width: <?php echo esc_attr( $col_width ); ?> !important;
	}

	<?php
	// navigation buttons settings
	if ( isset( $gg_settings['nbp_setting'] ) ) {
		$nbp_setting = $gg_settings['nbp_setting'];
	} else {
		$nbp_setting = 'in';
	}
	$nvb = ' ';
	if ( $nbp_setting == 'in' ) {
		$nvb = 'absolute'; }
	?>
	.gridder-navigation {
		position: <?php echo esc_attr( $nvb ); ?>;
	}
	
	<?php if ( $nbp_setting2 == 'left' ) { ?>
	.gridder-navigation {
		text-align: left;
		<?php if ( $nbp_setting == 'in' ) { ?>
		left: 8px;
		margin-top: 8px !important;
		<?php } ?>
	}
	<?php } ?>
	
	<?php if ( $nbp_setting2 == 'right' ) { ?>
	.gridder-navigation {
		text-align: right;
		<?php if ( $nbp_setting == 'in' ) { ?>
			<?php if ( $no_spacing == 'yes' ) { ?>
			right: 8px;
			<?php } else { ?>
			right: 20px;
			<?php } ?>
		margin-top: 8px !important;
		<?php } ?>
	}
	<?php } ?>
	
	<?php
	// border settings
	if ( isset( $gg_settings['image_border'] ) ) {
		$image_border = $gg_settings['image_border'];
	} else {
		$image_border = 'hide';
	}
	if ( $image_border == 'show' ) {
		if ( isset( $gg_settings['border_thickness'] ) ) {
			$border_thickness = $gg_settings['border_thickness'];
		} else {
			$border_thickness = 5;
		}
		if ( isset( $gg_settings['border_color'] ) ) {
			$border_color = $gg_settings['border_color'];
		} else {
			$border_color = '#000000';
		}
		?>
	.gg-gridder-list-<?php echo esc_attr( $grid_gallery_id ); ?> {
		border: <?php echo esc_attr( $border_thickness ); ?>px solid <?php echo esc_attr( $border_color ); ?> !important;
	}
	.imgbor-<?php echo esc_attr( $grid_gallery_id ); ?> {
		border: <?php echo esc_attr( $border_thickness ); ?>px solid <?php echo esc_attr( $border_color ); ?> !important;
	}
	<?php } ?>
	
	/* image description - title / dewc /link */
	.gg-description-<?php echo esc_attr( $grid_gallery_id ); ?> {
		width: 100% !important;
		<?php if ( $tdp_setting == 'top' ) { ?>
		top: 72px !important;
		<?php } ?>
		<?php if ( $tdp_setting == 'bottom' ) { ?>
		bottom: 30px !important;
		<?php } ?>
		<?php if ( $tdp_setting2 == 'left' ) { ?>
		text-align: left !important;
		left: 30px !important;
		<?php } ?>
		<?php if ( $tdp_setting2 == 'center' ) { ?>
		text-align: center !important;
		<?php } ?>
		<?php if ( $tdp_setting2 == 'right' ) { ?>
		text-align: right !important;
		right: 30px !important;
		<?php } ?>
	} 
	.gg-title-<?php echo esc_attr( $grid_gallery_id ); ?> {
		font-size: 24px !important;
		color: <?php echo esc_attr( $title_color ); ?>  !important;
		padding-left: 15px !important;
		padding-right: 24px !important;
	}
	<?php echo $custom_css; ?>
	</style>
	<?php
	// load without lightbox gallery output
	require 'grid-gallery-output.php';
	return ob_get_clean();
}
?>
