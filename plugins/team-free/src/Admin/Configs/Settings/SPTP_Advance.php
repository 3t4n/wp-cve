<?php
/**
 * Advance section in settings page.
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
class SPTP_Advance {

	/**
	 * Advanced settings (script/style enqueue/dequeue, remove data) in Settings page
	 *
	 * @since 2.0.0
	 * @param string $prefix _sptp_settings.
	 */
	public static function section( $prefix ) {
		SPF_TEAM::createSection(
			$prefix,
			array(
				'id'     => 'advance_settings',
				'title'  => __( 'Advanced', 'team-free' ),
				'icon'   => 'fa fa-wrench',
				'fields' => array(
					array(
						'id'         => 'delete_on_remove',
						'type'       => 'checkbox',
						'title'      => __( 'Clean-up Data on Deletion', 'team-free' ),
						'title_info' => __( 'Check this box if you would like WP Team to completely clean-up all of its data when the plugin is deleted.', 'team-free' ),
						'default'    => false,
					),
					array(
						'id'         => 'sptf_use_cache',
						'type'       => 'switcher',
						'title'      => __( 'Cache', 'team-free' ),
						'title_info' => __( 'Enable/Disable plugin cache. To make WP Team faster, keep enable cache. The cache is enabled by default.', 'team-free' ),
						'default'    => true,
						'text_on'    => __( 'Enable', 'team-free' ),
						'text_off'   => __( 'Disable', 'team-free' ),
						'text_width' => 100,
					),
					array(
						'id'      => 'cache_remove',
						'class'   => 'cache_remove',
						'type'    => 'button_clean',
						'options' => array(
							'' => 'Flush Cache',
						),
						'title'   => __( 'Clean Cache', 'team-free' ),
						'default' => false,
					),
					array(
						'id'         => 'enqueue_swiper_js',
						'type'       => 'switcher',
						'title'      => __( 'Swiper JS', 'team-free' ),
						'default'    => true,
						'text_on'    => __( 'Enqueued', 'team-free' ),
						'text_off'   => __( 'Dequeued', 'team-free' ),
						'text_width' => 110,
					),
					array(
						'id'         => 'enqueue_fontawesome',
						'type'       => 'switcher',
						'title'      => __( 'Font Awesome', 'team-free' ),
						'default'    => true,
						'text_on'    => __( 'Enqueued', 'team-free' ),
						'text_off'   => __( 'Dequeued', 'team-free' ),
						'text_width' => 110,
					),
					array(
						'id'         => 'enqueue_swiper',
						'type'       => 'switcher',
						'title'      => __( 'Swiper CSS', 'team-free' ),
						'default'    => true,
						'text_on'    => __( 'Enqueued', 'team-free' ),
						'text_off'   => __( 'Dequeued', 'team-free' ),
						'text_width' => 110,
					),
				),
			)
		);
	}
}
