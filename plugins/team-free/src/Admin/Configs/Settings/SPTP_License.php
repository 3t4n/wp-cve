<?php
/**
 * License section in settings page.
 *
 * @since      2.0.0
 * @version    2.0.0
 *
 * @package    WP_Team
 * @subpackage WP_Team/admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\WPTeam\Admin\Configs\Settings;

use ShapedPlugin\WPTeam\Admin\Framework\Classes\SPF_TEAM;

// Cannot access directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * This class is responsible for License settings in Settings page.
 *
 * @since      2.0.0
 */
class SPTP_License {

	/**
	 * License settings.
	 *
	 * @since 2.0.0
	 * @param string $prefix _sptp_settings.
	 */
	public static function section( $prefix ) {
		SPF_TEAM::createSection(
			$prefix,
			array(
				'title'  => __( 'License Key', 'team-free' ),
				'icon'   => 'fa fa-key',
				'fields' => array(
					array(
						'id'   => 'license_key',
						'type' => 'license',
					),
				),
			)
		);
	}
}
