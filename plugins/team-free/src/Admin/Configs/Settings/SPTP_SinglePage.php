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
class SPTP_SinglePage {

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
				'id'     => 'single_page',
				'title'  => __( 'Single Page', 'team-free' ),
				'icon'   => 'fa fa-id-card-o',
				'fields' => array(
					array(
						'id'       => 'single_page_view',
						'class'    => 'sptp_single_page_view',
						'type'     => 'image_select',
						'title'    => __( 'Layout For Member Single Page', 'team-free' ),
						'subtitle' => __( 'Choose a layout for member single page.', 'team-free' ),
						'options'  => array(
							'right_content'  => array(
								'image'       => SPT_PLUGIN_ROOT . 'src/Admin/img/single-right-content.svg',
								'option_name' => __( 'Right Content', 'team-free' ),
							),
							'bottom_content' => array(
								'image'       => SPT_PLUGIN_ROOT . 'src/Admin/img/single-bottom-content.svg',
								'option_name' => __( 'Bottom Content', 'team-free' ),
								'pro_only'    => true,

							),
						),
						'only_pro' => true,
						'default'  => 'right_content',
					),
					array(
						'id'       => 'detail_page_fields',
						'class'    => 'sptp_style_generator_list detail_page_fields',
						'type'     => 'fieldset',
						'title'    => __( 'Member Detail Page Fields ', 'team-free' ),
						'subtitle' => __( 'Show/Hide member detail or single page meta fields.', 'team-free' ),
						'desc'     => __( 'To unlock the additional information fields and drag & drop sorting options</b>, <a href="https://getwpteam.com/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a>', 'team-free' ),
						'default'  => array(
							'image_switch'        => true,
							'name_switch'         => true,
							'job_position_switch' => true,
							'bio_switch'          => true,
							'social_switch'       => true,
						),
						'fields'   => array(
							array(
								'id'                 => 'image_switch',
								'type'               => 'switcher',
								'title'              => __( 'Photo/Image', 'team-free' ),
								'text_on'            => __( 'Show', 'team-free' ),
								'text_off'           => __( 'Hide', 'team-free' ),
								'text_width'         => 80,
								'switcher_drag_icon' => true,
							),
							array(
								'id'                 => 'name_switch',
								'type'               => 'switcher',
								'title'              => __( 'Member Name', 'team-free' ),
								'text_on'            => __( 'Show', 'team-free' ),
								'text_off'           => __( 'Hide', 'team-free' ),
								'text_width'         => 80,
								'switcher_drag_icon' => true,
							),
							array(
								'id'                 => 'job_position_switch',
								'type'               => 'switcher',
								'title'              => __( 'Position/Job Title', 'team-free' ),
								'text_on'            => __( 'Show', 'team-free' ),
								'text_off'           => __( 'Hide', 'team-free' ),
								'text_width'         => 80,
								'switcher_drag_icon' => true,
							),
							array(
								'id'                 => 'bio_switch',
								'class'              => 'sptp_bio_switch',
								'type'               => 'switcher',
								'title'              => __( 'Short Bio', 'team-free' ),
								'text_on'            => __( 'Show', 'team-free' ),
								'text_off'           => __( 'Hide', 'team-free' ),
								'text_width'         => 80,
								'switcher_drag_icon' => true,
							),
							array(
								'id'                 => 'email_switch',
								'class'              => 'sptp_member_meta_info_pro sptp_pro_only_field',
								'type'               => 'switcher',
								'title'              => __( 'Email Address', 'team-free' ),
								'text_on'            => __( 'Show', 'team-free' ),
								'text_off'           => __( 'Hide', 'team-free' ),
								'text_width'         => 80,
								'switcher_drag_icon' => true,
								'only_pro'           => true,
							),
							array(
								'id'                 => 'mobile_switch',
								'class'              => 'sptp_member_meta_info_pro sptp_pro_only_field',
								'type'               => 'switcher',
								'title'              => __( 'Mobile (personal)', 'team-free' ),
								'text_on'            => __( 'Show', 'team-free' ),
								'text_off'           => __( 'Hide', 'team-free' ),
								'text_width'         => 80,
								'switcher_drag_icon' => true,
								'only_pro'           => true,
							),
							array(
								'id'                 => 'phone_switch',
								'class'              => 'sptp_member_meta_info_pro sptp_pro_only_field',
								'type'               => 'switcher',
								'title'              => __( 'Phone (business)', 'team-free' ),
								'text_on'            => __( 'Show', 'team-free' ),
								'text_off'           => __( 'Hide', 'team-free' ),
								'text_width'         => 80,
								'switcher_drag_icon' => true,
								'only_pro'           => true,
							),
							array(
								'id'                 => 'location_switch',
								'class'              => 'sptp_member_meta_info_pro sptp_pro_only_field',
								'type'               => 'switcher',
								'title'              => __( 'Location', 'team-free' ),
								'text_on'            => __( 'Show', 'team-free' ),
								'text_off'           => __( 'Hide', 'team-free' ),
								'text_width'         => 80,
								'switcher_drag_icon' => true,
								'only_pro'           => true,
							),
							array(
								'id'                 => 'website_switch',
								'class'              => 'sptp_member_meta_info_pro sptp_pro_only_field',
								'type'               => 'switcher',
								'title'              => __( 'Website', 'team-free' ),
								'text_on'            => __( 'Show', 'team-free' ),
								'text_off'           => __( 'Hide', 'team-free' ),
								'text_width'         => 80,
								'switcher_drag_icon' => true,
								'only_pro'           => true,
							),
							array(
								'id'                 => 'experience_switch',
								'class'              => 'sptp_member_experience sptp_pro_only_field',
								'type'               => 'switcher',
								'title'              => __( 'Year of Experience ', 'team-free' ),
								'text_on'            => __( 'Show', 'team-free' ),
								'text_off'           => __( 'Hide', 'team-free' ),
								'text_width'         => 80,
								'switcher_drag_icon' => true,
								'only_pro'           => true,
							),
							array(
								'id'                 => 'extra_fields_switch',
								'class'              => 'sptp_member_experience sptp_pro_only_field',
								'type'               => 'switcher',
								'title'              => __( 'Additional Custom Fields', 'team-free' ),
								'text_on'            => __( 'Show', 'team-free' ),
								'text_off'           => __( 'Hide', 'team-free' ),
								'text_width'         => 80,
								'switcher_drag_icon' => true,
								'only_pro'           => true,
							),
							array(
								'id'                 => 'skill_switch',
								'class'              => 'sptp_member_meta_info_pro sptp_pro_only_field',
								'type'               => 'switcher',
								'title'              => __( 'Skill Bars', 'team-free' ),
								'text_on'            => __( 'Show', 'team-free' ),
								'text_off'           => __( 'Hide', 'team-free' ),
								'text_width'         => 80,
								'switcher_drag_icon' => true,
								'only_pro'           => true,
							),
							array(
								'id'                 => 'social_switch',
								'type'               => 'switcher',
								'title'              => __( 'Social Profiles', 'team-free' ),
								'text_on'            => __( 'Show', 'team-free' ),
								'text_off'           => __( 'Hide', 'team-free' ),
								'text_width'         => 80,
								'switcher_drag_icon' => true,
							),
						),
					),
				),
			)
		);
	}
}
