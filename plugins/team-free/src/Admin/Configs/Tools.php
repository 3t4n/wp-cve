<?php
/**
 * Tools page.
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

if ( ! defined( 'ABSPATH' ) ) {
	die; }

/**
 * Tools class
 */
class Tools {

	use Singleton;

	/**
	 * Tools metabox.
	 *
	 * @param string $prefix The metabox main Key.
	 * @return void
	 */
	public static function metaboxes( $prefix ) {
		SPF_TEAM::createOptions(
			$prefix,
			array(
				'menu_title'              => __( 'Tools', 'team-free' ),
				'menu_slug'               => 'team_tools',
				'menu_parent'             => 'edit.php?post_type=sptp_member',
				'menu_type'               => 'submenu',
				'admin_bar_menu_priority' => 4,
				'ajax_save'               => false,
				'show_bar_menu'           => false,
				'save_defaults'           => false,
				'show_reset_all'          => false,
				'show_all_options'        => false,
				'show_search'             => false,
				'show_footer'             => false,
				'show_buttons'            => false, // Custom show button option added for hide save button in tools page.
				'theme'                   => 'light',
				'framework_title'         => __( 'Tools', 'team-free' ),
				'framework_class'         => 'sptp-option-settings team__tools',
			)
		);
		SPF_TEAM::createSection(
			$prefix,
			array(
				'title'  => __( 'Export', 'team-free' ),
				'fields' => array(
					array(
						'id'       => 'sptp_what_export',
						'type'     => 'radio',
						'class'    => 'sptp_what_export',
						'title'    => __( 'Choose What To Export', 'team-free' ),
						'multiple' => false,
						'options'  => array(
							'all_members'         => __( 'All Members', 'team-free' ),
							'all_shortcodes'      => __( 'All Team (Shortcodes)', 'team-free' ),
							'selected_shortcodes' => __( 'Selected Team (Shortcodes)', 'team-free' ),
						),
						'default'  => 'all_members',
					),
					array(
						'id'          => 'team_post_id',
						'class'       => 'sptp_post_ids',
						'type'        => 'select',
						'title'       => ' ',
						'options'     => 'sptp_generator',
						'chosen'      => true,
						'sortable'    => false,
						'multiple'    => true,
						'placeholder' => __( 'Choose shortcode(s)', 'team-free' ),
						'query_args'  => array(
							'posts_per_page' => -1,
						),
						'dependency'  => array( 'sptp_what_export', '==', 'selected_shortcodes' ),

					),
					array(
						'id'      => 'export',
						'class'   => 'sptp_export',
						'type'    => 'button_set',
						'title'   => ' ',
						'options' => array(
							'' => 'Export',
						),
					),
				),
			)
		);
		SPF_TEAM::createSection(
			$prefix,
			array(
				'title'  => __( 'Import', 'team-free' ),
				'fields' => array(
					array(
						'class' => 'sptp_import',
						'type'  => 'custom_import',
						'title' => __( 'Import JSON File To Upload', 'team-free' ),
					),
				),
			)
		);

	}
}
