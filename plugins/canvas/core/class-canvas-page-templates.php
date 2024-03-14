<?php
/**
 * Page Templates
 *
 * @package Canvas
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Module main class.
 */
class CNVS_Page_Templates {

	/**
	 * The array of templates that this plugin tracks.
	 *
	 * @var      array
	 */
	protected $templates;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct() {

		$this->templates = array();

		add_filter( 'theme_page_templates', array( $this, 'add_new_template' ) );

		// Add a filter to the page attributes metabox to inject our template into the page template cache.
		add_filter( 'page_attributes_dropdown_pages_args', array( $this, 'register_project_templates' ) );

		// Add a filter to the save post in order to inject out template into the page cache.
		add_filter( 'wp_insert_post_data', array( $this, 'register_project_templates' ) );

		// Add a filter to the template include in order to determine if the page has our template assigned and return it's path.
		add_filter( 'template_include', array( $this, 'view_project_template' ) );

		// Add your templates to this array.
		$this->templates = array(
			'template-canvas-fullwidth.php' => esc_html__( 'Canvas Full Width', 'canvas' ),
		);
	}

	/**
	 * Adds our template to the page dropdown
	 *
	 * @param array $posts_templates The posts templates.
	 */
	public function add_new_template( $posts_templates ) {
		$posts_templates = array_merge( $posts_templates, $this->templates );

		return $posts_templates;
	}

	/**
	 * Adds our template to the pages cache in order to trick WordPress
	 * into thinking the template file exists where it doens't really exist.
	 *
	 * @param array $atts The attributes for the page attributes dropdown.
	 */
	public function register_project_templates( $atts ) {

		// Create the key used for the themes cache.
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		// Retrieve the cache list. If it doesn't exist, or it's empty prepare an array.
		$templates = wp_cache_get( $cache_key, 'themes' );

		if ( empty( $templates ) ) {
			$templates = array();
		}

		// Since we've updated the cache, we need to delete the old cache.
		wp_cache_delete( $cache_key, 'themes' );

		/**Now add our template to the list of templates by merging our templates
		with the existing templates array from the cache. */
		$templates = array_merge( $templates, $this->templates );

		// Add the modified cache to allow WordPress to pick it up for listing available templates.
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $atts;
	}

	/**
	 * Checks if the template is assigned to the page
	 *
	 * @param string $template The name of template.
	 */
	public function view_project_template( $template ) {
		global $post;

		// Determines whether the query is for an existing single page.
		if ( ! is_page() ) {
			return $template;
		}

		// If no posts found, return to avoid "Trying to get property of non-object" error.
		if ( ! isset( $post ) ) {
			return $template;
		}

		if ( ! isset( $this->templates[ get_post_meta( $post->ID, '_wp_page_template', true ) ] ) ) {
			return $template;
		}

		$file = CNVS_PATH . 'page-templates/' . get_post_meta( $post->ID, '_wp_page_template', true );

		// Just to be safe, we check if the file exist first.
		if ( file_exists( $file ) ) {
			return $file;
		}

		return $template;
	}
}

new CNVS_Page_Templates();
