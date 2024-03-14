<?php

namespace XproElementorAddons\Control\Dynamic_Content;

use Xpro_Elementor_Addons;

defined( 'ABSPATH' ) || exit;


class Xpro_Elementor_Post_Item {


	public function __construct() {
		$this->post_type();

		register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
		register_activation_hook( __FILE__, array( $this, 'flush_rewrites' ) );

		add_action( 'admin_menu', array( $this, 'register_settings_submenus' ), 99 );
		add_action( 'admin_head', array( $this, 'correct_current_active_menu' ), 50 );

		//Filter the single_template with our custom function.
		add_filter( 'single_template', array( $this, 'template_redirect' ) );

		$this->add_elementor_cpt_support();

	}

	public function post_type() {

		$labels = array(
			'name'                  => _x( 'Templates', 'Post Type General Name', 'xpro-elementor-addons' ),
			'singular_name'         => _x( 'Template', 'Post Type Singular Name', 'xpro-elementor-addons' ),
			'menu_name'             => esc_html__( 'Template', 'xpro-elementor-addons' ),
			'name_admin_bar'        => esc_html__( 'Saved Templates', 'xpro-elementor-addons' ),
			'archives'              => esc_html__( 'Template Archives', 'xpro-elementor-addons' ),
			'attributes'            => esc_html__( 'Template Attributes', 'xpro-elementor-addons' ),
			'parent_item_colon'     => esc_html__( 'Parent Template:', 'xpro-elementor-addons' ),
			'all_items'             => esc_html__( 'All Templates', 'xpro-elementor-addons' ),
			'add_new_item'          => esc_html__( 'Add New Template', 'xpro-elementor-addons' ),
			'add_new'               => esc_html__( 'Add New', 'xpro-elementor-addons' ),
			'new_item'              => esc_html__( 'New Template', 'xpro-elementor-addons' ),
			'edit_item'             => esc_html__( 'Edit Template', 'xpro-elementor-addons' ),
			'update_item'           => esc_html__( 'Update Template', 'xpro-elementor-addons' ),
			'view_item'             => esc_html__( 'View Template', 'xpro-elementor-addons' ),
			'view_items'            => esc_html__( 'View Templates', 'xpro-elementor-addons' ),
			'search_items'          => esc_html__( 'Search Template', 'xpro-elementor-addons' ),
			'not_found'             => esc_html__( 'Not found', 'xpro-elementor-addons' ),
			'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'xpro-elementor-addons' ),
			'featured_image'        => esc_html__( 'Featured Image', 'xpro-elementor-addons' ),
			'set_featured_image'    => esc_html__( 'Set featured image', 'xpro-elementor-addons' ),
			'remove_featured_image' => esc_html__( 'Remove featured image', 'xpro-elementor-addons' ),
			'use_featured_image'    => esc_html__( 'Use as featured image', 'xpro-elementor-addons' ),
			'insert_into_item'      => esc_html__( 'Insert into Template', 'xpro-elementor-addons' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this Template', 'xpro-elementor-addons' ),
			'items_list'            => esc_html__( 'Items list', 'xpro-elementor-addons' ),
			'items_list_navigation' => esc_html__( 'Items list navigation', 'xpro-elementor-addons' ),
			'filter_items_list'     => esc_html__( 'Filter items list', 'xpro-elementor-addons' )
		);
		$args   = array(
			'label'               => esc_html__( 'Templates', 'xpro-elementor-addons' ),
			'description'         => esc_html__( 'Xpro saved templates', 'xpro-elementor-addons' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'elementor' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'publicly_queryable'  => true,
			'rewrite'             => false,
			'query_var'           => false,
			'exclude_from_search' => true,
			'map_meta_cap'        => true,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
			'rest_base'           => 'xpro-content',
		);
		register_post_type( 'xpro_content', $args );
	}

	public function flush_rewrites() {
		$this->post_type();
		flush_rewrite_rules();
	}

	public function register_settings_submenus() {
		add_submenu_page(
			Xpro_Elementor_Addons::PAGE_SLUG,
			esc_html__( 'Saved Templates', 'xpro-elementor-addons' ),
			esc_html__( 'Saved Templates', 'xpro-elementor-addons' ),
			'manage_options',
			'edit.php?post_type=xpro_content'
		);
	}

	/**
	 * Content Template
	 *
	 * @since 1.0.0
	 * @access public
	 */

	public function template_redirect( $single ) {

		global $post;

		/* Checks for single template by post type */
		if ( 'xpro_content' === $post->post_type ) {
			if ( file_exists( ELEMENTOR_PATH . 'modules/page-templates/templates/canvas.php' ) ) {
				return ELEMENTOR_PATH . 'modules/page-templates/templates/canvas.php';
			}
		}

		return $single;
	}

	/**
	 *** Add elementor support for wpr_templates.
	 **/
	public function add_elementor_cpt_support() {

		if ( ! is_admin() ) {
			return;
		}

		$cpt_support = get_option( 'elementor_cpt_support' );

		if ( ! $cpt_support ) {
			update_option( 'elementor_cpt_support', array( 'post', 'page', 'xpro_content' ) );
		} elseif ( ! in_array( 'xpro_content', $cpt_support, true ) ) {
			$cpt_support[] = 'xpro_content';
			update_option( 'elementor_cpt_support', $cpt_support );
		}

	}

	public function correct_current_active_menu() {

		$screen = get_current_screen();

		if ( 'xpro_content' === $screen->id ) {
			?>
			<script type="text/javascript">
				jQuery(document).ready(function ($) {
					$('#toplevel_page_xpro-elementor-addons').addClass('wp-has-current-submenu wp-menu-open menu-top menu-top-first').removeClass('wp-not-current-submenu');
					$('#toplevel_page_xpro-elementor-addons > a').addClass('wp-has-current-submenu').removeClass('wp-not-current-submenu');
					$("#toplevel_page_xpro-elementor-addons a[href*='edit.php?post_type=xpro-content']").addClass('current');
				});
			</script>
			<?php
		}

	}


}

new Xpro_Elementor_Post_Item();
