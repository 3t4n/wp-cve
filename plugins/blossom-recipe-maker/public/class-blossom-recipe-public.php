<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       test.com
 * @since      1.0.0
 *
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/public
 * @author     Blossom <test@test.com>
 */
class Blossom_Recipe_Maker_Public {

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blossom_Recipe_Maker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blossom_Recipe_Maker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/blossom-recipe-public.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name . 'owl-carousel', plugin_dir_url( __FILE__ ) . 'css/owl.carousel.min.css', array(), '2.3.4', 'all' );

		wp_enqueue_style( $this->plugin_name . 'owl-carousel-default', plugin_dir_url( __FILE__ ) . 'css/owl.theme.default.min.css', array(), '2.3.4', 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blossom_Recipe_Maker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blossom_Recipe_Maker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/blossom-recipe-public.js', array( 'jquery', 'jquery-ui-progressbar' ), $this->version, true );

		wp_enqueue_script( $this->plugin_name . 'adjust-ingredients', plugin_dir_url( __FILE__ ) . 'js/blossom-adjust-ingredients.js', array( 'jquery' ), $this->version, true );

		wp_enqueue_script( $this->plugin_name . 'math-script', plugin_dir_url( __FILE__ ) . 'js/math.min.js', array( 'jquery' ), '5.1.2', true );

		wp_enqueue_script( $this->plugin_name . 'owl-carousel', plugin_dir_url( __FILE__ ) . 'js/owl.carousel.min.js', array( 'jquery' ), '2.3.4', true );

		wp_enqueue_script( 'all', plugin_dir_url( __FILE__ ) . 'js/fontawesome/all.min.js', array( 'jquery' ), '5.14.0', true );

	}

	function post_types_author_archives( $query ) {

		if ( ! is_admin() && $query->is_main_query() ) {

			if ( $query->is_author() || $query->is_home() ) {

				$options = get_option( 'br_recipe_settings', array() );

				if ( isset( $options['act_as_posts'] ) ) {

					$post_type = $query->get( 'post_type' );

					if ( $post_type == '' || $post_type == 'post' ) {
						$post_type = array( 'post', 'blossom-recipe' );
					} elseif ( is_array( $post_type ) ) {
						if ( in_array( 'post', $post_type ) && ! in_array( 'blossom-recipe', $post_type ) ) {
							$post_type[] = 'blossom-recipe';
						}
					}

					$query->set( 'post_type', $post_type );
				}
			}

			remove_action( 'pre_get_posts', 'post_types_author_archives' );
		}
	}

	function blossom_recipe_archive_posts_per_page( $query ) {
		if ( ! is_admin() && is_post_type_archive( 'blossom-recipe' ) ) {
			$options                = get_option( 'br_recipe_settings', array() );
			$default_posts_per_page = ( isset( $options['no_of_recipes'] ) && ( ! empty( $options['no_of_recipes'] ) ) ) ? $options['no_of_recipes'] : get_option( 'posts_per_page' );

			if ( $query->is_main_query() ) {
				$query->set( 'posts_per_page', $default_posts_per_page );
				return $query;
			}
		}
	}



}
