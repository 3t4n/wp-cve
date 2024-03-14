<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       https://walterpinem.me/
 * @since      1.0.0
 *
 * @package    Wp_Mylinks
 * @subpackage Wp_Mylinks/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Wp_Mylinks
 * @subpackage Wp_Mylinks/includes
 * @author     Walter Pinem <hello@walterpinem.me>
 */
class Wp_Mylinks_Loader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();

	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress action that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the action is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         The priority at which the function should be fired.
	 * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;

	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

	}

}

/**
 * Class Plugin
 * 
 * @since 1.0.0
 *
 * Code by @wpscholar
 */
class Plugin {

	/**
	 * Class instance reference.
	 *
	 * @var Plugin
	 */
	private static $instance;

	/**
	 * Get or create instance of this class
	 *
	 * @return Plugin
	 */
	public static function get_instance() {
		return isset( self::$instance ) ? self::$instance : new self();
	}

	/**
	 * Add our WordPress actions and filters
	 */
	private function __construct() {
		self::$instance = $this;
		if ( is_admin() ) {
			add_filter( 'wp_dropdown_pages', array( $this, 'wp_dropdown_pages' ) );
		} else {
			add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
			add_action( 'template_redirect', array( $this, 'template_redirect' ) );
		}
	}

	/**
	 * This filter swaps out the normal dropdown for the front page with our own list
	 * of posts.
	 *
	 * @param string $output The output of the `wp_dropdown_pages()` function.
	 *
	 * @return string
	 */
	public function wp_dropdown_pages( $output ) { // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		global $pagenow;
		if ( ( 'options-reading.php' === $pagenow || 'customize.php' === $pagenow ) && preg_match( '#page_on_front#', $output ) ) {
			$output = $this->posts_dropdown();
		}

		return $output;
	}

	/**
	 * Generate a list of available posts to be used as the homepage
	 *
	 * @param string $post_type The post type name.
	 *
	 * @return string $output
	 */
	protected function posts_dropdown( $post_type = 'any' ) {
		$output = '';
		if ( 'any' !== $post_type && ! post_type_exists( $post_type ) ) {
			$post_type = array( 'page', 'mylink' );
		}
		$posts = get_posts(
			array(
				'posts_per_page' => - 1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'post_type'      => array( 'page', 'mylink' ), // Include MyLink in the options
				'post_status'    => 'publish',
			)
		);

		$front_page_id = get_option( 'page_on_front' );

		$select  = __( 'Select', 'wp-mylinks' );
		$output .= '<select name="page_on_front" id="page_on_front">';
		$output .= "<option value=\"0\">&mdash; {$select} &mdash;</option>";
		foreach ( $posts as $post ) {
			$selected      = selected( $front_page_id, $post->ID, false );
			$post_type_obj = get_post_type_object( $post->post_type );

			$output .= "<option value=\"{$post->ID}\"{$selected}>{$post->post_title} ({$post_type_obj->labels->singular_name})</option>";
		}
		$output .= '</select>';

		return $output;
	}

	/**
	 * A custom post type set as the homepage will still load under its original URL by default.
	 * This code ensures that it loads under the homepage URL.
	 *
	 * @param \WP_Query $query Query instance.
	 */
	public function pre_get_posts( $query ) {
		if ( $query->is_main_query() ) {
			$post_type = $query->get( 'post_type' );
			$page_id   = $query->get( 'page_id' );
			if ( empty( $post_type ) && ! empty( $page_id ) ) {
				$query->set( 'post_type', get_post_type( $page_id ) );
			}
		}
	}

	/**
	 * If the front page is loaded under its original URL, do a 301 redirect to the homepage.
	 */
	public function template_redirect() {
		global $post;
		if ( is_singular() && ! is_front_page() && absint( get_option( 'page_on_front' ) ) === $post->ID ) {
			wp_safe_redirect( site_url(), 301 );
		}
	}
}

Plugin::get_instance();