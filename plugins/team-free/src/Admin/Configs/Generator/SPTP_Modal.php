<?php
/**
 * Member Detail Settings tab.
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
 * This class is responsible for Member Detail tab in Team page.
 *
 * @since      2.0.0
 */
class SPTP_Modal {

	/**
	 * Member Detail Settings.
	 *
	 * @since 2.0.0
	 * @param string $prefix _sptp_generator.
	 */
	public static function section( $prefix ) {
		$single_page_link = admin_url( 'edit.php?post_type=sptp_member&page=team_settings#tab=single-page' );
		SPF_TEAM::createSection(
			$prefix,
			array(
				'title'  => __( 'Detail Page Settings', 'team-free' ),
				'icon'   => 'fa fa-id-card-o',
				'fields' => array(

					array(
						'type'       => 'switcher',
						'id'         => 'link_detail',
						'title'      => __( 'Link To Detail Page', 'team-free' ),
						'subtitle'   => __( 'Enable/Disable for linking member detail page.', 'team-free' ),
						'text_on'    => __( 'Enabled', 'team-free' ),
						'text_off'   => __( 'Disabled', 'team-free' ),
						'text_width' => 100,
						'default'    => true,
					),
					array(
						'id'         => 'link_detail_fields',
						'type'       => 'fieldset',
						'dependency' => array( 'link_detail', '==', 'true' ),
						'fields'     => array(
							array(
								'id'       => 'page_link_type',
								'class'    => 'sptp_page_link_type',
								'type'     => 'button_set',
								'title'    => __( 'Detail Page Link Type', 'team-free' ),
								'subtitle' => __( 'Choose member detail page type.', 'team-free' ),
								'options'  => array(
									'new_page' => __( 'Single Page', 'team-free' ),
									'modal'    => __( 'Modal', 'team-free' ),
									'drawer'   => __( 'Drawer', 'team-free' ),
								),
								'default'  => 'new_page',
								'inline'   => true,
							),
							array(
								'id'         => 'page_link_open',
								'type'       => 'radio',
								'title'      => __( 'Link Target', 'team-free' ),
								'options'    => array(
									'_blank' => __( 'New Tab', 'team-free' ),
									'_self'  => __( 'Current  Tab', 'team-free' ),
								),
								'default'    => '_blank',
								'dependency' => array( 'page_link_type', '==', 'new_page' ),
							),
							array(
								'id'      => 'nofollow_link',
								'type'    => 'checkbox',
								'title'   => __( 'Add rel="nofollow" to Link', 'team-free' ),
								'default' => false,
							),
							// Member details page in single page link.
							array(
								'id'    => 'sptp_single_page_link',
								'class' => 'sptp_single_page_link',
								'type'  => 'none',
								'title' => '<a href="' . esc_url( $single_page_link ) . '">' . __( 'Explore More Settings For Single Page→', 'team-free' ) . '</a>',
							),
							array(
								'type'    => 'notice',
								'content' => __( 'Want to enhance the member detail page with powerful <a href="https://getwpteam.com/beautiful-modal-layouts-slide-ins/" target="_blank">modal</a> settings? <a href="https://getwpteam.com/pricing/?ref=1" target="_blank"><b>Upgrade to Pro!</b></a>', 'team-free' ),
							),
							array(
								'id'         => 'modal_type',
								'type'       => 'radio',
								'title'      => __( 'Modal Type', 'team-free' ),
								'attributes' => array( 'disabled' => 'disabled' ),
								'subtitle'   => __( 'Choose modal type.', 'team-free' ),
								'class'      => 'sptp_page_link_type_option',
								'options'    => array(
									'single'   => __( 'Single Member', 'team-free' ),
									'multiple' => __( 'Multiple Members with Navigation', 'team-free' ),
								),
								'default'    => 'multiple',
							),
							array(
								'id'         => 'modal_layout',
								'class'      => 'sptp_modal_layout',
								'type'       => 'image_select',
								'attributes' => array( 'disabled' => 'disabled' ),
								'title'      => __( 'Modal Layout', 'team-free' ),
								'subtitle'   => __( 'Choose a modal layout.', 'team-free' ),
								'options'    => array(
									'style-1' => array(
										'image'       => SPT_PLUGIN_ROOT . 'src/Admin/img/modal-layout/classic_modal.svg',
										'option_name' => __( 'Classic Modal', 'team-free' ),
										'pro_only'    => true,
									),
									'style-3' => array(
										'image'       => SPT_PLUGIN_ROOT . 'src/Admin/img/modal-layout/slide_ins_left.svg',
										'option_name' => __( 'Slide-Ins Left', 'team-free' ),
										'pro_only'    => true,
									),
									'style-4' => array(
										'image'       => SPT_PLUGIN_ROOT . 'src/Admin/img/modal-layout/slide_ins_center.svg',
										'option_name' => __( 'Slide-Ins Center', 'team-free' ),
										'pro_only'    => true,
									),
									'style-2' => array(
										'image'       => SPT_PLUGIN_ROOT . 'src/Admin/img/modal-layout/slide_ins_right.svg',
										'option_name' => __( 'Slide-Ins Right', 'team-free' ),
										'pro_only'    => true,
									),
								),
							),
							array(
								'id'         => 'modal_background',
								'class'      => 'sptp_pro_only_field',
								'type'       => 'color',
								'attributes' => array( 'disabled' => 'disabled' ),
								'title'      => __( 'Modal Background', 'team-free' ),
								'subtitle'   => __( 'Set modal background.', 'team-free' ),
								'default'    => '#ffffff',
							),
							array(
								'id'         => 'member_name_clickable',
								'type'       => 'switcher',
								'class'      => 'sptp_pro_only_field',
								'title'      => __( 'Member Name Clickable', 'team-free' ),
								'subtitle'   => __( 'Enable/Disable member name clickable to open detail page.', 'team-free' ),
								'text_on'    => __( 'Enabled', 'team-free' ),
								'text_off'   => __( 'Disabled', 'team-free' ),
								'text_width' => 100,
								'default'    => false,
								'only_pro'   => true,
							),
							array(
								'id'         => 'enable_photo_gallery_autoplay',
								'type'       => 'switcher',
								'class'      => 'sptp_pro_only_field',
								'title'      => __( 'Enable Photo Gallery AutoPlay', 'team-free' ),
								'subtitle'   => __( 'Enable/Disable photo gallery autoplay to open detail page.', 'team-free' ),
								'text_on'    => __( 'Enabled', 'team-free' ),
								'text_off'   => __( 'Disabled', 'team-free' ),
								'text_width' => 100,
								'default'    => false,
								'only_pro'   => true,
							),
							array(
								'id'         => 'member_details_pdf_download',
								'type'       => 'switcher',
								'class'      => 'sptp_pro_only_field',
								'title'      => __( 'Member Details PDF Download', 'team-free' ),
								'subtitle'   => __( 'Enable/Disable pdf download of member details page.', 'team-free' ),
								'text_on'    => __( 'Enabled', 'team-free' ),
								'text_off'   => __( 'Disabled', 'team-free' ),
								'text_width' => 100,
								'default'    => false,
								'only_pro'   => true,
							),
							array(
								'id'       => 'image_size_details',
								'class'    => 'image_size',
								'type'     => 'select',
								'title'    => __( 'Dimensions', 'team-free' ),
								'subtitle' => __( 'Set image dimensions for the member detail page (Pro).', 'team-free' ),
								'options'  => 'img_sizes',
								'default'  => 'large',
								'only_pro' => true,
							),

						),
					),
					array(
						'type'    => 'subheading',
						'content' => __( 'Member Detail Fields', 'team-free' ),
					),
					array(
						'id'       => 'detail_page_draggable_fields',
						'class'    => 'sptp_style_generator_list detail_page_draggable_fields',
						'type'     => 'fieldset',
						'title'    => __( 'Member Detail Fields in Modal', 'team-free' ),
						'subtitle' => __( 'Show/Hide and sort (drag & drop) member detail fields for the modal.', 'team-free' ),
						'desc'     => __( ' To manage member information with drag & drop sorting options effortlessly,</b> <a href="https://getwpteam.com/pricing/?ref=1" target="_blank"><b>Upgrade to Pro!</b></a>', 'team-free' ),
						'only_pro' => true,
						'default'  => array(
							'image_switch'        => true,
							'name_switch'         => true,
							'job_position_switch' => true,
							'bio_switch'          => true,
							'email_switch'        => true,
							'mobile_switch'       => true,
							'phone_switch'        => true,
							'location_switch'     => true,
							'website_switch'      => true,
							'experience_switch'   => true,
							'extra_fields_switch' => true,
							'skill_switch'        => true,
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
								'only_pro'           => true,
							),
							array(
								'id'                 => 'name_switch',
								'type'               => 'switcher',
								'title'              => __( 'Member Name', 'team-free' ),
								'text_on'            => __( 'Show', 'team-free' ),
								'text_off'           => __( 'Hide', 'team-free' ),
								'text_width'         => 80,
								'switcher_drag_icon' => true,
								'only_pro'           => true,
							),
							array(
								'id'                 => 'job_position_switch',
								'type'               => 'switcher',
								'title'              => __( 'Position/Job Title', 'team-free' ),
								'text_on'            => __( 'Show', 'team-free' ),
								'text_off'           => __( 'Hide', 'team-free' ),
								'text_width'         => 80,
								'switcher_drag_icon' => true,
								'only_pro'           => true,
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
								'only_pro'           => true,
							),
							array(
								'id'                 => 'email_switch',
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
								'only_pro'           => true,
							),
						),
					),
				),
			)
		);
	}
}
