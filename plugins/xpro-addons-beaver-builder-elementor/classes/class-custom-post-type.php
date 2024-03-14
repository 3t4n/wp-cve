<?php

defined( 'ABSPATH' ) || exit;

class Xpro_Beaver_Dynamic_Content {


	public function __construct() {
		$this->post_type();

		register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
		register_activation_hook( __FILE__, array( $this, 'flush_rewrites' ) );

		add_action( 'admin_menu', array( $this, 'register_settings_submenus' ), 99 );

        add_filter( 'fl_builder_post_types', array( $this, 'enable_builder' ) );
        add_filter( 'fl_builder_admin_settings_post_types', array( $this, 'remove_from_post_type_settings' ) );

        add_filter( 'single_template', [ $this, 'blank_template' ] );

	}

	public function post_type() {

        $labels = array(
            'name'               => __( 'Save Template', 'xpro-beaver-themer' ),
            'singular_name'      => __( 'Save Template', 'xpro-beaver-themer' ),
            'menu_name'          => __( 'Save Template', 'xpro-beaver-themer' ),
            'name_admin_bar'     => __( 'Save Template', 'xpro-beaver-themer' ),
            'add_new'            => __( 'Add New', 'xpro-beaver-themer' ),
            'add_new_item'       => __( 'Add New Template', 'xpro-beaver-themer' ),
            'new_item'           => __( 'New Template', 'xpro-beaver-themer' ),
            'edit_item'          => __( 'Edit Template', 'xpro-beaver-themer' ),
            'view_item'          => __( 'View Template', 'xpro-beaver-themer' ),
            'all_items'          => __( 'All Templates', 'xpro-beaver-themer' ),
            'search_items'       => __( 'Search Templates', 'xpro-beaver-themer' ),
            'parent_item_colon'  => __( 'Parent Templates:', 'xpro-beaver-themer' ),
            'not_found'          => __( 'No Templates found.', 'xpro-beaver-themer' ),
            'not_found_in_trash' => __( 'No Templates found in Trash.', 'xpro-beaver-themer' ),
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'rewrite'             => false,
            'query_var'           => false,
            'can_export'          => true,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => true,
            'map_meta_cap'        => true,
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'supports'            => array( 'title', 'thumbnail' ),
        );

        register_post_type( 'xpro_bb_templates', $args );
	}

	public function flush_rewrites() {
		$this->post_type();
		flush_rewrite_rules();
	}

	public function register_settings_submenus() {
		add_submenu_page(
			'xpro_dashboard_welcome',
			esc_html__( 'Saved Templates', 'xpro-elementor-addons' ),
			esc_html__( 'Saved Templates', 'xpro-elementor-addons' ),
			'edit_pages',
			'edit.php?post_type=xpro_bb_templates'
		);
	}

    /**
     * Enable the builder for the theme layout post type.
     *
     * @since 1.0
     * @param array $post_types
     * @return array
     */
    public function enable_builder( $post_types ) {
        $post_types[] = 'xpro_bb_templates';
        return $post_types;
    }

    /**
     * Remove the theme layout post type from the builder settings.
     *
     * @since 1.0
     * @param array $post_types
     * @return array
     */
    public function remove_from_post_type_settings( $post_types ) {
        if ( isset( $post_types['xpro_bb_templates'] ) ) {
            unset( $post_types['xpro_bb_templates'] );
        }

        return $post_types;
    }

    public function blank_template( $template ) {

        global $post;
        if ( $post->post_type == 'xpro_bb_templates' ) {
            if ( file_exists( XPRO_ADDONS_FOR_BB_DIR . 'inc/templates/blank.php' ) ) {
                return XPRO_ADDONS_FOR_BB_DIR . 'inc/templates/blank.php';
            }
        }

        return $template;
    }

}

new Xpro_Beaver_Dynamic_Content();
