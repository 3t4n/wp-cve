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

namespace ShapedPlugin\WPTeam\Admin\Configs\Settings;

use ShapedPlugin\WPTeam\Admin\Framework\Classes\SPF_TEAM;
// Cannot access directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * This class is responsible for Rename in Settings page.
 *
 * @since      2.0.0
 */
class SPTP_Rename {

	/**
	 * Rename settings.
	 *
	 * @since 2.0.0
	 * @param string $prefix _sptp_settings.
	 */
	public static function section( $prefix ) {
		SPF_TEAM::createSection(
			$prefix,
			array(
				'id'     => 'dashboard_menu_rename',
				'title'  => __( 'Custom Menu', 'team-free' ),
				'icon'   => 'fa fa-bars',
				'fields' => array(
					array(
						'id'      => 'rename_member_singular',
						'type'    => 'text',
						'title'   => __( 'Member singular name', 'team-free' ),
						'default' => __( 'Member', 'team-free' ),
					),
					array(
						'id'      => 'rename_member_plural',
						'type'    => 'text',
						'title'   => __( 'Member plural name', 'team-free' ),
						'default' => __( 'Members', 'team-free' ),
					),
					array(
						'id'      => 'rename_team',
						'type'    => 'text',
						'title'   => __( 'Plural name', 'team-free' ),
						'default' => __( 'Teams', 'team-free' ),
					),
				),
			)
		);

	}
}
