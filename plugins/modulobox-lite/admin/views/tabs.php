<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$active   = ' mobx-tab-active';
$sections = array(
	'general'         => __( 'General', 'modulobox' ),
	'accessibility'   => __( 'Accessibility', 'modulobox' ),
	'navigation'      => __( 'Navigation', 'modulobox' ),
	'controls'        => __( 'Controls', 'modulobox' ),
	'caption'         => __( 'Caption', 'modulobox' ),
	'thumbnails'      => __( 'Thumbnails', 'modulobox' ),
	'social-sharing'  => __( 'Social Sharing', 'modulobox' ),
	'slideshow'       => __( 'Slideshow', 'modulobox' ),
	'zoom-video'      => __( 'Zoom &amp; Videos', 'modulobox' ),
	'gallery'         => __( 'Gallery', 'modulobox' ),
	'customization'   => __( 'Customization', 'modulobox' ),
	'import-export'   => __( 'Import/Export', 'modulobox' ),
	'system-status'   => __( 'System Status', 'modulobox' ),
	'premium-version' => __( 'Premium Version', 'modulobox' )
);

echo '<ul class="mobx-tabs">';

	foreach ( $sections as $section => $name ) {

		echo '<li class="mobx-tab mobx-icon mobx-' . sanitize_html_class( $section ) . '-tab' . esc_attr( $active ) . '">';
			echo '<span>' . esc_html( $name ) . '</span>';
		echo '</li>';
		
		$active = null;

	}

echo '</ul>';
