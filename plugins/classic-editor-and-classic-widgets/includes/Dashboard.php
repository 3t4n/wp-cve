<?php

namespace GRIM_CEW;

use GRIM_CEW\Vendor\Controller;

class Dashboard extends Controller {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu_page' ), 1 );
		add_action( 'admin_menu', array( $this, 'disable_admin_menu' ) );
		add_filter( 'plugin_action_links_' . CEW_BASENAME, array( $this, 'plugin_action_links' ) );
	}

	public function admin_menu_page() {
		add_options_page(
			esc_html__( 'Classic Editor & Classic Widgets', 'classic-editor-and-classic-widgets' ),
			esc_html__( 'Classic Editor', 'classic-editor-and-classic-widgets' ),
			'manage_options',
			self::$slug,
			array( Settings::class, 'render_settings_page' ),
			10
		);
	}

	public function disable_admin_menu() {
		if ( Settings::get_option( 'hide_menu_item' ) ) {
			remove_submenu_page( 'options-general.php', self::$slug );
		}
	}

	public function plugin_action_links( $links ) {
		$settings_link = sprintf(
			'<a href="%1$s">%2$s</a>',
			admin_url( 'options-general.php?page=' . self::$slug ),
			esc_html__( 'Settings', 'classic-editor-and-classic-widgets' )
		);

		$links[] = $settings_link;

		if ( ! cew_pro_enabled() ) {
			$pro_link = sprintf(
				'<a href="%1$s" style="font-weight: 600; color: #00b000;" target="_blank">%2$s</a>',
				esc_url( 'https://wpgrim.com/classic-editor-and-classic-widgets-pro/?utm_source=cew-plugin&utm_medium=get-pro&utm_campaign=cew' ),
				esc_html__( 'Get Pro', 'classic-editor-and-classic-widgets' )
			);

			$links[] = $pro_link;
		}

		return $links;
	}
}
