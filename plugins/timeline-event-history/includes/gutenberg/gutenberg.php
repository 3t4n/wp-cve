<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// echo "Hello";
// die;
/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';


// add_action('wp_head', 'print_stylesheet', 0);
function print_stylesheet() {
	global $post ;
    echo "<pre>";
	$content =  get_post_field('post_content', $post->ID) ;
    $reusable_blocks = parse( $content );
    print_r($reusable_blocks);
    die;
    get_post_field('post_content', $post->ID);
}

/**
 * Parse Guten Block.
 *
 * @param string $content the content string.
 * @since 1.1.0
 */
function parse( $content ) {

	global $wp_version;

	return ( version_compare( $wp_version, '5', '>=' ) ) ? parse_blocks( $content ) : gutenberg_parse_blocks( $content );
}