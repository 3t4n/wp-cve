<?php

class NJBA_Settings_Page {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'njbaMainSettingsPage' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'njbaAdminEnqueueScript' ] );
	}

	/**
	 * Renders the nav items for the admin settings menu.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function njbaRenderNavItems() {
		$item_data   = apply_filters( 'fl_builder_admin_settings_nav_items', array(
			'welcome' => array(
				'title'    => __( 'Welcome', 'fl-builder' ),
				'show'     => true,
				'priority' => 100
			),
			'general' => array(
				'title'    => __( 'General Settings', 'fl-builder' ),
				'show'     => true,
				'priority' => 300
			),
			'modules' => array(
				'title'    => __( 'Modules', 'fl-builder' ),
				'show'     => true,
				'priority' => 400
			),
		) );
		$sorted_data = array();
		foreach ( $item_data as $key => $data ) {
			$data['key']                      = $key;
			$sorted_data[ $data['priority'] ] = $data;
		}
		ksort( $sorted_data );
		foreach ( $sorted_data as $data ) {
			if ( $data['show'] ) {
				echo '<li><a href="#' . $data['key'] . '">' . $data['title'] . '</a></li>';
			}
		}
	}

	/**
	 * Renders the admin settings forms.
	 * @return void
	 * @since 1.0.0
	 */
	public static function njbaRenderForms() {
		self::njbaRenderSpecificForm( 'welcome' ); // Welcome
		self::njbaRenderSpecificForm( 'general' ); // General
		self::njbaRenderSpecificForm( 'modules' ); // Modules
		do_action( 'fl_builder_admin_settings_render_forms' );
	}

	/**
	 * Renders an admin settings form based on the type specified.
	 *
	 * @param string $type The type of form to render.
	 *
	 * @return void
	 * @since 1.0
	 */
	public static function njbaRenderSpecificForm( $type ) {
		include NJBA_MODULE_DIR . 'classes/admin/admin-settings-' . $type . '.php';
	}

	/**
	 * Renders the action for admin settings form.
	 *
	 * @param string $type The type of form being rendered.
	 *
	 * @return void
	 * @since 1.0 .0
	 */
	public static function njbaRenderFormAction( $type = '' ) {
		echo admin_url( '/admin.php?page=njba-admin-setting#' . $type );
	}

	/**
	 * Register the styles and scripts.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function njbaAdminEnqueueScript() {
		wp_enqueue_style( 'njba-admin-settings', NJBA_MODULE_URL . 'assets/css/njba-admin-settings.css', array(), NJBA_MODULE_VERSION );
		if ( isset( $_REQUEST['page'] ) && 'njba-admin-setting' == $_REQUEST['page'] ) {
			wp_enqueue_script( 'njba-admin-menu', NJBA_MODULE_URL . 'assets/js/njba-admin-menu.js', array(), NJBA_MODULE_VERSION );
		}
	}

	/**
	 * This page will be appearance "Dashboard"
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function njbaMainSettingsPage() {
		$page_title = 'Ninja Beaver';
		$menu_title = 'Ninja Beaver';
		$capability = 'manage_options';
		$menu_slug  = 'njba-admin-setting';
		$function   = [ $this, 'njbaAdminOptions' ];
		$icon_url   = 'dashicons-admin-generic';
		$position   = 81;
		add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	}

	/**
	 * njba add admin menu and options.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function njbaAdminOptions() {
		include( 'admin/class-general-settings.php' );
	}

	/**
	 * License Lists page
	 * Currently This function Not any place in used.
	 */
	public function njbaAddonsLicense() {
		include( 'admin/extensions-list/class-extensions-list-settings.php' );
	}

	/**
	 * Addons Purchase List
	 *Currently This function Not any place in used.
	 */
	public function njbaAddonsPurchase() {
		include( 'admin/purchase/class-purchase-list.php' );
	}
}

if ( is_admin() ) {
	$my_settings_page = new NJBA_Settings_Page();
}
