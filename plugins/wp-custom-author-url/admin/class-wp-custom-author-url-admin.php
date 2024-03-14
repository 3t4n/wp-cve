<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://chrisjallen.com
 * @since      1.0.0
 *
 * @package    Wp_Custom_Author_Url
 * @subpackage Wp_Custom_Author_Url/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Custom_Author_Url
 * @subpackage Wp_Custom_Author_Url/admin
 * @author     Chris Allen <me@chrisjallen.com>
 */
class Wp_Custom_Author_Url_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Custom_Author_Url_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Custom_Author_Url_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-custom-author-url-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Custom_Author_Url_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Custom_Author_Url_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-custom-author-url-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * This function modifies the author link, if necessary.
	 *
	 */
	public function modify_author_link( $link, $author_id, $author_nicename ) {

		// Get global custom author URL settings
		$options = get_option( 'wp_custom_author_url_global_options' );

		// Is the overide all authors set?
		if ( isset( $options['redirect_all_authors'] ) && '1' === $options['redirect_all_authors'] && strlen( $options['redirect_url'] ) ) {
			$link = esc_url( $options['redirect_url'] );

			if ( isset( $options['override_individual_authors'] ) && '1' === $options['override_individual_authors'] ) {
				return $link;
			}
		}

		// Get author options from author_id
		$author_options = get_user_meta( $author_id );
		// Check if the author has a custom url set
		if ( isset( $author_options['use_custom_author_url'] ) && 'on' === $author_options['use_custom_author_url'][0] && strlen( $author_options['custom_author_url'][0] ) ) {
			$link = esc_url( $author_options['custom_author_url'][0] );
			return $link;
		}

		return $link;

	}

	/**
	 * This function performs the redirect if the author page is directly accessed.
	 *
	 */
	public function author_page_redirect() {
		if ( is_author() ) {

			// Get author from slug
			$author         = get_user_by( 'slug', get_query_var( 'author_name' ) );
			// If using no permalinks, fall back to user id ( ?author=1 )
			if ( ! $author ) {
				$author = get_user_by( 'id', get_query_var( 'author' ) );
			}
			$author_options = get_user_meta( $author->ID );
			// Check if the author has a custom url set
			if ( isset( $author_options['use_custom_author_url'] ) && 'on' === $author_options['use_custom_author_url'][0] && strlen( $author_options['custom_author_url'][0] ) ) {
				$link = esc_url( $author_options['custom_author_url'][0] );
				wp_redirect( $link );
				exit;
			}
		}
	}

}
