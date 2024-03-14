<?php
/**
 * SPTP_Generator class for admin generators page
 *
 * @link       https://shapedplugin.com
 * @since      2.0
 *
 * @package    WP_Team
 * @subpackage WP_Team/admin/partials
 */

namespace ShapedPlugin\WPTeam\Admin\Configs;

use ShapedPlugin\WPTeam\Traits\Singleton;
use ShapedPlugin\WPTeam\Admin\Framework\Classes\SPF_TEAM;
use ShapedPlugin\WPTeam\Admin\Configs\Generator\SPTP_Carousel;
use ShapedPlugin\WPTeam\Admin\Configs\Generator\SPTP_General;
use ShapedPlugin\WPTeam\Admin\Configs\Generator\SPTP_Display;
use ShapedPlugin\WPTeam\Admin\Configs\Generator\SPTP_Layout;
use ShapedPlugin\WPTeam\Admin\Configs\Generator\SPTP_Modal;
use ShapedPlugin\WPTeam\Admin\Configs\Generator\SPTP_Output;
use ShapedPlugin\WPTeam\Admin\Configs\Generator\SPTP_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	die;} // Cannot access directly.

/**
 * Generator class
 */
class Generator {

	use Singleton;

	/**
	 * Preview metabox.
	 *
	 * @param string $prefix The metabox main Key.
	 * @return void
	 */
	public static function preview_metabox( $prefix ) {
		SPF_TEAM::createMetabox(
			$prefix,
			array(
				'title'        => __( 'Live Preview', 'team-free' ),
				'post_type'    => 'sptp_generator',
				'show_restore' => false,
				'context'      => 'normal',
			)
		);
		SPF_TEAM::createSection(
			$prefix,
			array(
				'fields' => array(
					array(
						'type' => 'preview',
					),
				),
			)
		);

	}

	/**
	 * Create metabox for Layout preset section of the generator.
	 *
	 * @param string $prefix Metabox key prefix.
	 * @return void
	 */
	public static function layout_metaboxes( $prefix ) {
		SPF_TEAM::createMetabox(
			$prefix,
			array(
				'title'     => __( 'Team Generator Layout ', 'team-free' ),
				'post_type' => 'sptp_generator',
			)
		);

		SPTP_Layout::section( $prefix );
	}

	/**
	 * Create metabox for the Generator options.
	 *
	 * @param string $prefix Metabox key prefix.
	 * @return void
	 */
	public static function metaboxes( $prefix ) {
		SPF_TEAM::createMetabox(
			$prefix,
			array(
				'title'     => __( 'Team Generator Settings', 'team-free' ),
				'post_type' => 'sptp_generator',
				'theme'     => 'light',
				'class'     => 'sptp-generator-tabs',
			)
		);

		SPTP_General::section( $prefix );
		SPTP_Display::section( $prefix );
		SPTP_Carousel::section( $prefix );
		SPTP_Modal::section( $prefix );
		SPTP_Typography::section( $prefix );
	}

	/**
	 * Shortcode field .
	 *
	 * Shows the generated shortcode with id attribute .
	 *
	 * @param string $prefix Metabox id prefix .
	 * @return void
	 */
	public static function output_metaboxes( $prefix ) {
		SPF_TEAM::createMetabox(
			$prefix,
			array(
				'title'     => __( 'How To Use', 'team-free' ),
				'post_type' => 'sptp_generator',
				'context'   => 'side',
			)
		);
		SPTP_Output::section( $prefix );
	}
}
