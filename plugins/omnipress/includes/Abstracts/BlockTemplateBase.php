<?php

namespace Omnipress\Abstracts;

use http\Params;
use Omnipress\BlockTemplates;
use function remove_menu_page;

defined( 'ABSPATH' ) || exit;

/**
 * Register Menu and Templates Page.
 *
 * @since 1.2.0
 */
abstract class BlockTemplateBase {
	/**
	 * Init All Templates and blocks menu.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_template_menu' ) );
		$menu_tempalates = new BlockTemplates( 'Menu', 'Menu Templates', 'menu-templates' );
	}


	/**
	 * It will add menu for custom templates.
	 *
	 * @return void
	 */
	public static function register_template_menu() {
		// phpcs:ignore
		add_menu_page( 'omnipress-template', 'Templates', 'manage_options', 'omnipress-templates', array( __CLASS__, 'register_template_page' ), 'data:image/svg+xml;base64,' . base64_encode( @file_get_contents( OMNIPRESS_PATH . 'assets/images/omnipress-template-icon.svg' ) ), 100 );
	}

	/**
	 * It will display custom templates page.
	 *
	 * @return void
	 */
	public static function register_template_page() {
		echo '<div class="omnipress_templates--wrapper"><h2>Templates</h2></div>';
	}
}
