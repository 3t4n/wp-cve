<?php
/**
 * Plugin Name: Hot Blocks
 * Plugin URI: https://www.hotjoomlatemplates.com/wordpress-plugins/hot-blocks
 * Description: Gutenberg blocks by Hot Themes.
 * Version: 1.3.3
 * Author: Hot Themes
 * Author URI: https://www.hotjoomlatemplates.com
 *
 * @package hotblocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// create blocks category
add_filter( 'block_categories_all', function( $categories, $post ) {
    return array_merge(
        $categories,
        array(
            array(
                'slug' => 'hot-blocks',
                'title' => __( 'Hot Blocks', 'hot-blocks' ),
            ),
        )
    );
}, 10, 2 );

// enqueue customizations of default blocks
function hot_blocks_enqueue() {
    wp_enqueue_script(
        'hot-blocks-customizations',
        plugins_url( 'build/customizations.js', __FILE__ ),
        array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
        filemtime( plugin_dir_path( __FILE__ ) . 'build/customizations.js' )
    );
}
add_action( 'enqueue_block_editor_assets', 'hot_blocks_enqueue' );

// assets for editor
function hot_blocks_editor_assets() {
    if (current_user_can( 'edit_posts' )) {
        wp_enqueue_script(
            'hotblocks',
            plugins_url( 'build/index.build.js', __FILE__ ),
            array( 'wp-blocks', 'wp-element', 'wp-editor' )
        );

        wp_enqueue_style(
    		'hotblocks-editor-style',
            plugins_url( 'css/editor.css', __FILE__ ),
            array( 'wp-edit-blocks' )
    	);
    }
};
add_action( 'enqueue_block_editor_assets', 'hot_blocks_editor_assets');

// assets for front-end
function hot_blocks_assets() {

    wp_enqueue_script('jquery');

    wp_enqueue_script(
        'jquery.bxslider',
        plugins_url( 'js/jquery.bxslider.js', __FILE__ ),
        array( 'jquery' ),
        '4.1.2'
    );

    wp_enqueue_script(
        'hotblocks-js',
        plugins_url( 'js/hot_blocks.js', __FILE__ ),
        array( 'jquery' )
    );

    wp_enqueue_style(
		'hotblocks',
        plugins_url( 'css/view.css', __FILE__ )
	);

}
add_action( 'enqueue_block_assets', 'hot_blocks_assets');

// contact form block sending mail
function hot_contact_email() {
if ( isset($_POST['hb_submit'] ) ) {

    if ( $_POST['hb_anti_spam_answer'] == $_POST['hb_anti_spam_correct'] ) {

        require_once("wp-load.php");

        $hb_to = get_bloginfo('admin_email');
        $hb_subject = __( 'Website inquiry', 'hot-blocks' );
        $hb_name = $_POST['hb_name']; 
        $hb_email = $_POST['hb_email'];
        $hb_message = $_POST['hb_message'];
        $hb_headers = 'From: '.$hb_name.' <noreply@'.$_SERVER['SERVER_NAME'].'>' . "\r\n" . 'Reply-To: ' . $hb_email;

        if( wp_mail( $hb_to, $hb_subject, $hb_message, $hb_headers ) ) {
            echo '<div class="hb_contact_success">'. __( 'E-mail sent successfully!', 'hot-blocks' ) . '</div>';
        } else {
            echo '<div class="hb_contact_error">'. __( 'E-mail sending failed!', 'hot-blocks' ) . '</div>';
        }

    } else {
        echo '<div class="hb_contact_error">'. __( 'Wrong anti-spam answer!', 'hot-blocks' ) . '</div>';
    }

}
}
add_action( 'wp_loaded', 'hot_contact_email' );