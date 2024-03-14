<?php
/**
 * Accessibility section in settings page.
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
 * This class is responsible for Accessibility settings in Settings page.
 *
 * @since      2.0.0
 */
class SPTP_Accessibility {

	/**
	 * Accessibility settings.
	 *
	 * @since 2.0.0
	 * @param string $prefix _sptp_settings.
	 */
	public static function section( $prefix ) {
		SPF_TEAM::createSection(
			$prefix,
			array(
				'id'     => 'advance_settings',
				'title'  => __( 'Accessibility', 'team-free' ),
				'icon'   => 'fa fa-braille',
				'fields' => array(
					array(
						'id'      => 'carousel_accessibility',
						'class'   => 'carousel_accessibility',
						'type'    => 'fieldset',
						'title'   => __( 'Carousel Accessibility', 'team-free' ),
						'fields'  => array(
							array(
								'id'         => 'accessibility',
								'type'       => 'switcher',
								'title'      => __( 'Accessibility', 'team-free' ),
								'text_on'    => __( 'Enabled', 'team-free' ),
								'text_off'   => __( 'Disabled', 'team-free' ),
								'text_width' => 100,
							),
							array(
								'id'         => 'prev_slide_message',
								'type'       => 'text',
								'title'      => __( 'Previous Slide Message', 'team-free' ),
								'dependency' => array( 'accessibility', '==', 'true' ),
							),
							array(
								'id'         => 'next_slide_message',
								'type'       => 'text',
								'title'      => __( 'Next Slide Message', 'team-free' ),
								'dependency' => array( 'accessibility', '==', 'true' ),
							),
							array(
								'id'         => 'first_slide_message',
								'type'       => 'text',
								'title'      => __( 'First Slide Message', 'team-free' ),
								'dependency' => array( 'accessibility', '==', 'true' ),
							),
							array(
								'id'         => 'last_slide_message',
								'type'       => 'text',
								'title'      => __( 'Last Slide Message', 'team-free' ),
								'dependency' => array( 'accessibility', '==', 'true' ),
							),
							array(
								'id'         => 'pagination_bullet_message',
								'type'       => 'text',
								'title'      => __( 'Pagination Bullet Message', 'team-free' ),
								'dependency' => array( 'accessibility', '==', 'true' ),
							),
						),
						'default' => array(
							'accessibility'             => true,
							'prev_slide_message'        => __( 'Previous slide', 'team-free' ),
							'next_slide_message'        => __( 'Next slide', 'team-free' ),
							'first_slide_message'       => __( 'This is the first slide', 'team-free' ),
							'last_slide_message'        => __( 'This is the last slide', 'team-free' ),
							'pagination_bullet_message' => __( 'Go to slide {{index}}', 'team-free' ),
						),
					),
				),
			)
		);
	}
}
