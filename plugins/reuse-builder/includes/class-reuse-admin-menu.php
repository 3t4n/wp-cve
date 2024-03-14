<?php
/**
 *
 */

namespace Reuse\Builder;

class Reuse_Builder_Admin_Menu {

	public function __construct() {
		add_action('admin_menu', array($this, 'reuse_builder_register_menu'), 9);
	}

	public function reuse_builder_register_menu() {
		add_menu_page(
			$page_title 	= esc_html__( 'Builder', 'reuse-builder' ),
			$menu_title 	= esc_html__( 'Builder', 'reuse-builder' ),
			$capability 	= 'manage_options',
			$menu_slug 		= 'reuse_builder',
			$function 		=  array( $this , 'reuse_builder_menu_render'),
			$icon_url 		= 'dashicons-screenoptions'
		);

		add_submenu_page(
			$parent_slug = 'reuse_builder',
			$page_title = esc_html__( 'Settings', 'reuse-builder' ),
			$menu_title = esc_html__( 'Settings', 'reuse-builder' ),
			$capability = 'manage_options',
			$menu_slug = 'reuse_builder_settings',
			$function = array( $this, 'settings' )
		);
	}

	public function reuse_builder_menu_render() {
		if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.', 'reuse-builder' ) );
    }
		include_once( REUSE_BUILDER_DIR. '/admin-templates/listing.php');
	}

	public function settings()
	{
		if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.', 'reuse-builder' ) );
    }
		include_once( REUSE_BUILDER_DIR. '/admin-templates/settings.php');
	}
}
