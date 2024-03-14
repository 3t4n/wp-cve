<?php
/**
 * Rename section in settings page.
 *
 * @since      2.0.0
 * @version    2.0.0
 *
 * @package    WP_Team
 * @subpackage WP_Team/admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WPTeam\Admin\Configs;

use ShapedPlugin\WPTeam\Traits\Singleton;
use ShapedPlugin\WPTeam\Admin\Framework\Classes\SPF_TEAM;
use ShapedPlugin\WPTeam\Admin\Configs\Settings\SPTP_Accessibility;
use ShapedPlugin\WPTeam\Admin\Configs\Settings\SPTP_Advance;
use ShapedPlugin\WPTeam\Admin\Configs\Settings\SPTP_Rename;
use ShapedPlugin\WPTeam\Admin\Configs\Settings\SPTP_SinglePage;
use ShapedPlugin\WPTeam\Admin\Configs\Settings\SPTP_SettingsStyle;
use ShapedPlugin\WPTeam\Admin\Configs\Settings\SPTP_License;
if ( ! defined( 'ABSPATH' ) ) {
	die; }

/**
 * Settings class
 */
class Settings {

	use Singleton;

	/**
	 * Settings page metabox.
	 *
	 * @param string $prefix The metabox main Key.
	 * @return void
	 */
	public static function metaboxes( $prefix ) {
		SPF_TEAM::createOptions(
			$prefix,
			array(
				'menu_title'              => __( 'Settings', 'team-free' ),
				'show_bar_menu'           => false,
				'menu_slug'               => 'team_settings',
				'menu_parent'             => 'edit.php?post_type=sptp_member',
				'framework_title'         => __( 'Settings', 'team-free' ),
				'menu_type'               => 'submenu',
				'admin_bar_menu_priority' => 5,
				'show_search'             => false,
				'show_all_options'        => false,
				'show_reset_section'      => true,
				'show_reset_all'          => false,
				'show_footer'             => false,
				'theme'                   => 'light',
				'framework_class'         => 'sptp-option-settings',
			)
		);
		SPTP_SinglePage::section( $prefix );
		SPTP_Rename::section( $prefix );
		SPTP_Advance::section( $prefix );
		SPTP_SettingsStyle::section( $prefix );
		SPTP_Accessibility::section( $prefix );
		SPTP_License::section( $prefix );

	}
}
