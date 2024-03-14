<?php
/**
 * Preloader for the plugin
 *
 * @link       https://shapedplugin.com/
 * @since      1.1.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/public
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$preloader_image = SP_WCS_URL . 'admin/img/spinner.svg';
if ( ! empty( $preloader_image ) ) {
	$output .= '<div id="wcsp-preloader-' . $post_id . '" class="sp-wcsp-preloader"><img src=" ' . $preloader_image . ' " attr="Loader Image"/></div>';
}
