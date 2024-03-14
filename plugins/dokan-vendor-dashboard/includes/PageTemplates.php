<?php

namespace WeDevs\DokanVendorDashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Page template resolver.
 *
 * @since 1.0.0
 */
class PageTemplates {

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Registers necessary hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function hooks() {
		// Add a filter to the attributes metabox to inject template into the cache.
		if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {
			// 4.6 and older
			add_filter(
				'page_attributes_dropdown_pages_args',
				array( $this, 'register_project_templates' )
			);
		} else {
			// Add a filter to the wp 4.7 version attributes metabox.
			add_filter(
				'theme_page_templates',
				array( $this, 'add_new_template' )
			);
		}

		// Add a filter to the save post to inject out template into the page cache.
		add_filter(
			'wp_insert_post_data',
			array( $this, 'register_project_templates' )
		);

		// Add a filter to the template include to determine if the page has our
		// template assigned and return it's path.
		add_filter(
			'template_include',
			array( $this, 'view_project_template' )
		);
	}

	/**
	 * Get vendor dashboard page template.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function get_vendor_dashboard_page_template() {
		$templates = array();
		$templates['templates/vendor-dashboard-full-width.php'] = __( 'Dokan Vendor Dashboard Template', 'dokan-vendor-dashboard' );

		return $templates;
	}

	/**
	 * Adds our template to the page dropdown for v4.7+.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function add_new_template( $posts_templates ) {
		return array_merge( $posts_templates, $this->get_vendor_dashboard_page_template() );
	}

	/**
	 * Adds our template to the pages cache in order to trick WordPress
	 * into thinking the template file exists where it doesn't really exist.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_project_templates( $atts ) {
		// Create the key used for the themes cache.
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		// Retrieve the cache list.
		// If it doesn't exist, or it's empty prepare an array.
		$templates = wp_get_theme()->get_page_templates();
		if ( empty( $templates ) ) {
			$templates = array();
		}

		// New cache, therefore remove the old one.
		wp_cache_delete( $cache_key, 'themes' );

		// Now add our template to the list of templates by merging our templates
		// with the existing templates array from the cache.
		$templates = array_merge( $templates, $this->get_vendor_dashboard_page_template() );

		// Add the modified cache to allow WordPress to pick it up for listing
		// available templates.
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $atts;
	}

	/**
	 * Checks if the template is assigned to the page.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function view_project_template( $template ) {
		// Get global post.
		global $post;

		// Return template if post is empty.
		if ( ! $post ) {
			return $template;
		}

		$page_templates = $this->get_vendor_dashboard_page_template();
		$wp_template	= get_post_meta( $post->ID, '_wp_page_template', true );

		// Return default template if we don't have a custom one defined.
		if ( ! isset( $page_templates[ $wp_template ] ) ) {
			return $template;
		}

		$file = DOKAN_VENDOR_DASHBOARD_DIR . "/$wp_template";

		// Just to be safe, we check if the file exist first.
		if ( file_exists( $file ) ) {
			$template = $file;
		}

		// Return template.
		return $template;
	}
}
