<?php
/*
Plugin Name: Post Words Count
Description: Simple Plugin which counts <strong>Total Post Words</strong> and display the number and <strong>Post Thumbnail</strong> at All Post Section in Dashboard
Author: Zakaria Binsaifullah
Author URI: https://makegutenblock.com
Version: 2.2.1
Text Domain: post-words-count
License: GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Domain Path:  /languages
*/

if ( ! function_exists( 'add_action' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . '/admin/admin.php';


/**
* SDK Integration
*/

if ( ! function_exists( 'dci_plugin_post_words_counter' ) ) {
	function dci_plugin_post_words_counter() {
		// Include DCI SDK.
		require_once dirname( __FILE__ ) . '/dci/start.php';

		dci_dynamic_init( array(
			'sdk_version'  => '1.0.0',
			'plugin_title' => 'Post Words Counter',
			'product_id'   => 2,
			'api_endpoint' => 'https://dashboard.gutenbergkits.com/wp-json/dci/v1/data-insights',
			'slug'         => 'post-words-count',
			'public_key'   => 'pk_O9vwzVkUcXxaKahVQA75fJXflftW9hb4',
			'is_premium'   => false,
			'menu'         => array(
				'slug' => 'post-words-count',
			),
		) );

	}
}
add_action( 'plugins_loaded', 'dci_plugin_post_words_counter');

/**
 * Plguin Class 
 */

class POST_WORDS_COUNT {

	public function __construct() {
		/**
		 * Word Count Column
		 */
		if ( is_admin() ) {
			add_action( 'plugins_loaded', array( $this, 'pwc_text_domain' ) );
			add_filter( 'manage_posts_columns', array( $this, 'pwc_custom_columns' ) );
			add_action( 'manage_posts_custom_column', array( $this, 'pwc_words_column_data' ), 10, 2 );
			add_filter( 'manage_posts_columns', array( $this, 'custom_word_count_column' ) );
			add_action( 'manage_posts_custom_column', array( $this, 'custom_word_count_value' ), 10, 2 );
			add_action( 'activated_plugin', array( $this, 'pwc_users_redirecting_support_page' ) );
		}
	}
	
	/**
	 * Load Text Domain
	 */

	public function pwc_text_domain() {
		load_plugin_textdomain( 'post-words-count', false, dirname( __FILE__ ) . '/languages' );
	}

	/**
	 * Add Custom Column
	 */
	public function pwc_custom_columns( $columns ) {
		$columns['post_thumb'] = __( 'Thumbnail', 'post-words-count' );
		return $columns;
	}

	/**
	 * Add Custom Column Data
	 */
	public function pwc_words_column_data( $cols, $post_id ) {
		if ( $cols == 'post_thumb' ) {
			$post_thumbnail = get_the_post_thumbnail( $post_id, array( 60, 50 ) );
			if ( ! empty( $post_thumbnail ) ) {
				printf( "<a href='%s' target='_blank'>%s</a>", get_the_permalink( $post_id ), $post_thumbnail );
			} else {
				echo __( "No thumbnail", "post-words-count" );
			}
		}
	}

	// Add custom column to post list
	function custom_word_count_column( $columns ) {
		$columns['word_count'] = __( 'Words', 'post-words-count' );
		return $columns;
	}


	// Populate custom column with word count
	function custom_word_count_value( $column, $post_id ) {
		if ( $column === 'word_count' ) {
			$content    = get_post_field( 'post_content', $post_id );
			$word_count = str_word_count( strip_tags( $content ) );
			echo $word_count;
		}
	}

	/*
	* Redirecting
	*/
	public function pwc_users_redirecting_support_page( $plugin ) {
		if ( plugin_basename( __FILE__ ) == $plugin ) {
			wp_redirect( admin_url( 'tools.php?page=post-words-count' ) );
			die();
		}
	}
}

new POST_WORDS_COUNT();



