<?php
/**
 * Layout section in team page.
 *
 * @since      2.0.0
 * @version    2.0.0
 *
 * @package    WP_Team
 * @subpackage WP_Team/admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\WPTeam\Admin\Configs\Generator;

use ShapedPlugin\WPTeam\Admin\Framework\Classes\SPF_TEAM;
// Cannot access directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * This class is responsible for Shortcode output in Team page.
 *
 * @since      2.0.0
 */
class SPTP_Output {

	/**
	 * Member Detail Settings.
	 *
	 * @since 2.0.0
	 * @param string $prefix _sptp_generator_output.
	 */
	public static function section( $prefix ) {

		SPF_TEAM::createSection(
			$prefix,
			array(
				'fields' => array(
					array(
						'id'     => 'outputs',
						'type'   => 'fieldset',
						'class'  => '_sptp_output',
						'fields' => array(
							array(
								'id'   => 'output_shortcode',
								'type' => 'shortcode',
							),
						),
					),
				),
			)
		);
	}
}
