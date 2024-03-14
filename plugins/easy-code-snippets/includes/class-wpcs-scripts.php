<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Scripts Class
 *
 * Handles adding scripts functionality to the admin
 * as well as the front pages.
 */
class ECSnippets_Scripts {
	
	public function __construct() {

		// enqueue admin side script
		add_action( 'admin_enqueue_scripts', array($this, 'ecsnippets_admin_scripts') );

		// print snippet code in header or footer section.
		add_action( 'wp_head', array($this, 'print_front_snippet_header_scripts'), 99 );
		add_action( 'wp_footer', array($this, 'print_front_snippet_footer_scripts'), 99 );
	}

	/**
	 * Enqueue Admin Scripts
	 */
	public function ecsnippets_admin_scripts( $hook ) {
	
		// register scripts
		wp_register_script( 'wpcs-admin-js', WPCS_PLUGIN_URL . 'js/wpcs-admin.js', array('jquery'), WPCS_VERSION, true );

		// enqueue code editor
		wp_enqueue_code_editor( array('type' => 'text/html') );
		
		// enqueue scripts
		wp_enqueue_script( 'wpcs-admin-js' );	
	}

	/**
	* Print snippet code front side
	* at header
	*/
	public function print_front_snippet_header_scripts() {
		global $wpdb, $post;
		$post_id = isset( $post->ID ) ? esc_attr( $post->ID ) : '';
		$query = "SELECT * FROM {$wpdb->prefix}ecs_snippets WHERE position = 'header'";
		$snippets = $wpdb->get_results( $query );
		if( !empty($snippets) ) :
			foreach( $snippets as $snippet ) :
				$snippet_code = $snippet->code;
				if( !empty($snippet_code) ) {
					echo html_entity_decode( $snippet_code );
				}
			endforeach;
		endif;
	}

	/**
	* Print snippet code front side
	* at footer
	*/
	public function print_front_snippet_footer_scripts() {
		global $wpdb, $post;
		$post_id = isset( $post->ID ) ? esc_attr( $post->ID ) : '';
		$query = "SELECT * FROM {$wpdb->prefix}ecs_snippets WHERE position = 'footer'";
		$snippets = $wpdb->get_results( $query );
		if( !empty($snippets) ) :
			foreach( $snippets as $snippet ) :
				$snippet_code = $snippet->code;
				if( !empty($snippet_code) ) {
					echo html_entity_decode( $snippet_code );
				}
			endforeach;
		endif;
	}
}
return new ECSnippets_Scripts();