<?php
/**
 * Custom CSS/JS section in settings page.
 *
 * @since      2.0.0
 * @version    2.0.0
 *
 * @package    WP_Team
 * @subpackage WP_Team/admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WPTeam\Admin\Configs\Settings;

use ShapedPlugin\WPTeam\Admin\Framework\Classes\SPF_TEAM;
// Cannot access directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * This class is responsible for Custom CSS/JS in Settings page.
 *
 * @since      2.0.0
 */
class SPTP_SettingsStyle {

	/**
	 * Custom CSS/JS settings.
	 *
	 * @since 2.0.0
	 * @param string $prefix _sptp_settings.
	 */
	public static function section( $prefix ) {
		SPF_TEAM::createSection(
			$prefix,
			array(
				'id'     => 'custom_style',
				'title'  => __( 'Additional CSS & JS', 'team-free' ),
				'icon'   => 'fa fa-file-code-o',
				'fields' => array(
					array(
						'id'       => 'custom_css',
						'type'     => 'code_editor',
						'title'    => __( 'Custom CSS' ),
						'settings' => array(
							'icon'  => 'fa fa-sliders',
							'theme' => 'mbo',
							'mode'  => 'css',
						),
					),
					array(
						'id'       => 'custom_js',
						'type'     => 'code_editor',
						'title'    => __( 'Custom JS' ),
						'settings' => array(
							'theme' => 'mbo',
							'mode'  => 'javascript',
						),
					),

				),
			)
		);

	}
}
