<?php
/*
Plugin Name: Image Carousel Divi
Plugin URI:  https://www.learnhowwp.com/divi-image-carousel-plugin
Description: This plugin adds an image carousel module to the Divi theme.
Version:     1.0
Author:      learnhowwp.com
Author URI:  http://www.learnhowwp.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: lwp-image-carousel
Domain Path: /languages

Image Carousel Divi is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Image Carousel Divi is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Lwp Image Carousel. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/


if ( ! function_exists( 'lwp_initialize_image_carousel_extension' ) ):
/**
 * Creates the extension's main class instance.
 *
 * @since 1.0.0
 */
function lwp_initialize_image_carousel_extension() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/LwpImageCarousel.php';
}
add_action( 'divi_extensions_init', 'lwp_initialize_image_carousel_extension' );
endif;

if ( ! function_exists( 'lwp_hook_css' ) ):

function lwp_hook_css() {
	$url_eot= esc_url( plugins_url( 'fonts/slick.eot', __FILE__ ) );
	$url_woff= esc_url( plugins_url( 'fonts/slick.woff', __FILE__ ) );
	$url_ttf= esc_url( plugins_url( 'fonts/slick.ttf', __FILE__ ) );
	$url_svg= esc_url( plugins_url( 'fonts/slick.svg#slick', __FILE__ ) );
	?>
		<style>
			@font-face
			{
				font-family: 'slick';
				font-weight: normal;
				font-style: normal;

				src: url(<?php echo"'".$url_eot."'"; ?>);
				src: url(<?php echo"'".$url_eot."'"; ?>) format('embedded-opentype'),
				 url(<?php echo"'".$url_woff."'"; ?>) format('woff'),
				  url(<?php echo"'".$url_ttf."'"; ?>) format('truetype'),
				   url(<?php echo"'".$url_svg."'"; ?>) format('svg');
			}
		</style>
	<?php
}

endif;


if ( ! function_exists( 'lwp_get_carousel_images' ) ):

add_action( 'wp_ajax_lwp_get_carousel_images', 'lwp_get_carousel_images' );

function lwp_get_carousel_images(){

	$gallery_ids = wp_parse_id_list($_POST['gallery_ids']);

	$attachments = array();
	$attachments_args = array(
		'numberposts'    => 5,
		'include'        => $gallery_ids,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'post__in',
	);
	
	$_attachments = get_posts( $attachments_args );

	foreach ( $_attachments as $key => $val ) {
		$attachments[$key] = $_attachments[$key];
		$attachments[$key]->image_alt_text  = get_post_meta( $val->ID, '_wp_attachment_image_alt', true);
		$attachments[$key]->image_src_full  = wp_get_attachment_image_src( $val->ID, 'full' );
	}

	$images_output='';	//stores html output for the images

	foreach ( $attachments as $key => $val ) {

		$images_output=$images_output.'<div><img src="'.$attachments[$key]->image_src_full[0].'" alt"'.$attachments[$key]->image_alt_text.'"></div>';
		
		$attachments[$key];
		$attachments[$key]->image_alt_text;
		$attachments[$key]->image_src_full;
	}	

	$result = [
		'html'	=> $images_output
	];
	echo json_encode( $result );
	wp_die();
}

endif;

if ( ! function_exists( 'lwp_image_carousel_plugin_row_meta' ) ):

add_filter( 'plugin_row_meta', 'lwp_image_carousel_plugin_row_meta', 10, 2 );

function lwp_image_carousel_plugin_row_meta( $links, $file ) {

    if ( plugin_basename( __FILE__ ) == $file ) {
		$new_links = array(
			'<a href="https://wordpress.org/support/plugin/image-carousel-divi/reviews/#new-post" target="_blank">'.esc_html__( 'Rate Plugin', 'lwp-image-carousel' ).'</a>',
			'<a href="https://www.learnhowwp.com/how-to-create-image-carousel-divi-free-plugin/" target="_blank">'.esc_html__( 'Getting Started Guide', 'lwp-image-carousel' ).'</a>'
			);
		
		$links = array_merge( $links, $new_links );
	}
	
	return $links;
}

endif;
