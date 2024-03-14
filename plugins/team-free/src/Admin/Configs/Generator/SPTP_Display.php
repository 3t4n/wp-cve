<?php
/**
 * Display settings tab.
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
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * This class is responsible for Style tab in Team page.
 *
 * @since      2.0.0
 */
class SPTP_Display {

	/**
	 * Member Display Settings.
	 *
	 * @since 2.0.0
	 * @param string $prefix _sptp_generator.
	 */
	public static function section( $prefix ) {
		SPF_TEAM::createSection(
			$prefix,
			array(
				'title'  => __( 'Display Settings', 'team-free' ),
				'icon'   => 'fa fa-th-large',
				'fields' => array(
					array(
						'type'  => 'tabbed',
						'class' => 'sptp-display-tabs',
						'tabs'  => array(
							array(
								'title'  => __( 'Member Styles', 'team-free' ),
								'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"><g clip-path="url(#A)" fill-rule="evenodd" fill="#343434"><path d="M13.5 7.7V1c0-.6-.5-1-1-1H1a1.08 1.08 0 0 0-1 1v12.1c0 .6.5 1 1 1h.9 6.3c.1.1.1.2.2.3.8.9 2 1.5 3.2 1.5 2.4 0 4.3-1.9 4.3-4.3.1-1.7-.9-3.2-2.4-3.9zM1.2 13V1.2h11.1v6.2h-.6C10.3 7.3 9.1 8 8.3 9H5.7c-1 0-2 .4-2.7 1.1a4.44 4.44 0 0 0-.8 1.1c-.2.5-.3 1-.3 1.6v.2h-.7zm10.5 1.8c-.5 0-1-.1-1.4-.3-.2-.1-.3-.2-.5-.3a.78.78 0 0 1-.4-.4c-.2-.2-.4-.5-.5-.8-.2-.4-.3-.9-.3-1.4v-.4a3.09 3.09 0 0 1 1.1-2c.5-.5 1.2-.7 2-.7.2 0 .4 0 .6.1.4.1.8.3 1.2.5.8.6 1.4 1.5 1.4 2.6-.1 1.7-1.5 3.1-3.2 3.1zm1.8-4.8c-.1-.1-.2-.1-.3-.1-.2 0-.3.1-.5.2l-.4.5-.5.5-.1.2-.2.2a1.38 1.38 0 0 1 .4.3c.1 0 .1.1.1.2.1.1.1.2.2.3l1.2-1 .1-.1c.1-.1.2-.3.2-.5.1-.5 0-.7-.2-.7zM7.1 8.2c1.5 0 2.8-1.2 2.8-2.8 0-1.5-1.2-2.8-2.8-2.8S4.3 3.9 4.3 5.4a2.73 2.73 0 0 0 2.8 2.8z"/><path d="M10.4 11.8c-.3.3-.2.6-.2.9v.3c0 .2-.1.3-.2.5v.1s0 .1.1.1.2.1.4.1c.4 0 1-.1 1.4-.5l.2-.2c.1-.1.1-.3.1-.5 0-.3-.1-.5-.3-.7-.5-.4-1.1-.4-1.5-.1z"/></g><defs><clipPath id="A"><path fill="#fff" d="M0 0h16v16H0z"/></clipPath></defs></svg></span>',
								'fields' => array(
									array(
										'id'         => 'style_title',
										'type'       => 'switcher',
										'title'      => __( 'Team Section Title', 'team-free' ),
										'subtitle'   => __( 'Show/Hide team section title.', 'team-free' ),
										'text_on'    => __( 'Show', 'team-free' ),
										'text_off'   => __( 'Hide', 'team-free' ),
										'default'    => true,
										'text_width' => 80,
									),
									array(
										'id'         => 'style_member_content_position',
										'class'      => 'sptp_member_content_position',
										'type'       => 'image_select',
										'title'      => __( 'Member Style', 'team-free' ),
										'desc'       => __( 'To unlock more amazing member styles and advanced customizations, <a href="https://getwpteam.com/pricing/?ref=1" target="_blank"><b>Upgrade to Pro!</b></a>', 'team-free' ),
										'dependency' => array( 'layout_preset', '!=', 'list', true ),
										'options'    => array(
											'top_img_bottom_content' => array(
												'image' => SPT_PLUGIN_ROOT . 'src/Admin/img/member-style/bottom.svg',
												'option_name' => __( 'Bottom', 'team-free' ),

											),
											'top_content_bottom_img' => array(
												'image' => SPT_PLUGIN_ROOT . 'src/Admin/img//member-style/top.svg',
												'option_name' => __( 'Top', 'team-free' ),
											),
											'left_img_right_content' => array(
												'image'    => SPT_PLUGIN_ROOT . 'src/Admin/img/member-style/left.svg',
												'option_name' => __( 'Right', 'team-free' ),
												'pro_only' => true,
											),
											'left_content_right_img' => array(
												'image'    => SPT_PLUGIN_ROOT . 'src/Admin/img/member-style/right.svg',
												'option_name' => __( 'Left', 'team-free' ),
												'pro_only' => true,
											),
											'content_over_image' => array(
												'image'    => SPT_PLUGIN_ROOT . 'src/Admin/img/member-style/overlay.svg',
												'option_name' => __( 'Overlay', 'team-free' ),
												'pro_only' => true,
											),
											'caption'      => array(
												'image'    => SPT_PLUGIN_ROOT . 'src/Admin/img/member-style/caption.svg',
												'option_name' => __( 'Caption', 'team-free' ),
												'pro_only' => true,
											),
											'diagonal'     => array(
												'image'    => SPT_PLUGIN_ROOT . 'src/Admin/img/member-style/diagonal.svg',
												'option_name' => __( 'Diagonal', 'team-free' ),
												'pro_only' => true,
											),
											'caption-half' => array(
												'image'    => SPT_PLUGIN_ROOT . 'src/Admin/img/member-style/caption-half.svg',
												'option_name' => __( 'Caption Half', 'team-free' ),
												'pro_only' => true,
											),
										),
										'default'    => 'top_img_bottom_content',
										'subtitle'   => __( 'Select a position or layout for member content and image.', 'team-free' ),
									),
									array(
										'id'         => 'item_padding',
										'class'      => 'members_padding',
										'type'       => 'spacing',
										'title'      => __( 'Padding', 'team-free' ),
										'subtitle'   => __( 'Set padding for the member/item.', 'team-free' ),
										'units'      => array( 'px' ),
										'default'    => array(
											'top'    => '0',
											'right'  => '0',
											'bottom' => '0',
											'left'   => '0',
											'unit'   => 'px',
										),
										'title_info' => '<div class="spf-img-tag"><img src="' . SPT_PLUGIN_ROOT . 'src/Admin/img/visual/content_padding.svg" alt="Content Padding"></div><div class="spf-info-label img">' . __( 'Content Padding', 'team-free' ) . '</div>',
									),
									array(
										'id'         => 'member_box_shadow_type',
										'type'       => 'button_set',
										'title'      => __( 'Box-Shadow', 'team-free' ),
										'subtitle'   => __( 'Choose box-shadow type for the member.', 'team-free' ),
										'options'    => array(
											'none'   => __( 'None', 'team-free' ),
											'outset' => __( 'Outset', 'team-free' ),
											'inset'  => __( 'Inset', 'team-free' ),
										),
										'default'    => 'none',
										'dependency' => array( 'layout_preset', 'not-any', 'mosaic,table,thumbnail-pager', true ),

									),
									array(
										'id'          => 'member_box_shadow',
										'type'        => 'box_shadow',
										'title'       => __( 'Box-Shadow Values', 'team-free' ),
										'subtitle'    => __( 'Set box-shadow property values for the member.', 'team-free' ),
										'style'       => false,
										'hover_color' => true,
										'default'     => array(
											'vertical'    => '0',
											'horizontal'  => '0',
											'blur'        => '10',
											'spread'      => '0',
											'color'       => '#ECECEC',
											'hover_color' => '#dddddd',
										),
										'dependency'  => array( 'layout_preset|member_box_shadow_type', 'not-any|!=', 'mosaic,table,thumbnail-pager|none', true ),
									),
									array(
										'id'     => 'border_bg_around_member',
										'type'   => 'fieldset',
										'class'  => 'sptp-border-bg-group',
										'fields' => array(
											array(
												'id'       => 'border_around_member',
												'class'    => 'sptp_border_around',
												'type'     => 'border',
												'title'    => __( 'Border', 'team-free' ),
												'subtitle' => __( 'Set border for the member.', 'team-free' ),
												'all'      => true,
												'default'  => array(
													'all'  => 0,
													'style' => 'solid',
													'unit' => 'px',
													'color' => '#ddd',
													'hover_color' => '#444',
												),
											),
											array(
												'id'       => 'border_around_member_border_radius',
												'type'     => 'spacing',
												'title'    => __( 'Border Radius', 'team-free' ),
												'all'      => true,
												'units'    => array(
													__( 'px', 'team-free' ),
													__( '%', 'team-free' ),
												),
												'subtitle' => __( 'Set border radius for the member.', 'team-free' ),
												'default'  => array(
													'all' => '0',
												),
											),
											array(
												'id'       => 'bg_color_around_member_type',
												'type'     => 'button_set',
												'class'    => 'sptp_image_grayscale',
												'title'    => __( 'Background Color', 'team-free' ),
												'subtitle' => __( 'Choose background color type for the member.', 'team-free' ),
												'options'  => array(
													'solid'    => __( 'Solid', 'team-free' ),
													'gradient' => array(
														'option_name' => __( 'Gradient', 'team-free' ),
														'pro_only' => true,
													),
												),
												'default'  => 'solid',
											),
											array(
												'id'       => 'bg_color_around_member',
												'class'    => 'sptp_bg_color_around',
												'type'     => 'color',
												'title'    => __( 'Color', 'team-free' ),
												'subtitle' => __( 'Set background color for the member.', 'team-free' ),
												'default'  => 'transparent',
											),
											array(
												'id'       => 'item_same_height',
												'class'    => 'sptp_pro_only_field',
												'type'     => 'switcher',
												'title'    => __( 'Enable Equal Height', 'team-free' ),
												'subtitle' => __( 'Enable/Disable to equalize all member items height as the tallest one.', 'team-free' ),
												'text_on'  => __( 'Enabled', 'team-free' ),
												'text_off' => __( 'Disabled', 'team-free' ),
												'default'  => false,
												'text_width' => 100,
												'only_pro' => true,
												'title_info' => '<div class="spf-img-tag"><img src="' . SPT_PLUGIN_ROOT . 'src/Admin/img/visual/equalize_members_height.svg" alt="equalize_members_height"></div><div class="spf-info-label img">' . __( 'Equalize Members Height', 'team-free' ) . '</div>',
											),
											array(
												'type'    => 'notice',
												'class'   => 'pro-notice',
												'content' => __( 'To unlock more amazing member styles and advanced customizations,</b> <a href="https://getwpteam.com/pricing/?ref=1" target="_blank"><b>Upgrade to Pro!</b></a>', 'team-free' ),
												'dependency' => array( 'layout_preset', '==', 'list', true ),
											),

										),
									),
								),
							),
							array(
								'title'  => __( 'Control Member Info', 'team-free' ),
								'class'  => 'sptp-control-member-info',
								'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"><g clip-path="url(#A)" fill="#343434"><path fill-rule="evenodd" d="M2.1 1.2a.94.94 0 0 0-.9.9v11.8a.94.94 0 0 0 .9.9h11.8a.94.94 0 0 0 .9-.9V2.1a.94.94 0 0 0-.9-.9H2.1zM0 2.1A2.14 2.14 0 0 1 2.1 0h11.8A2.14 2.14 0 0 1 16 2.1v11.8a2.14 2.14 0 0 1-2.1 2.1H2.1A2.14 2.14 0 0 1 0 13.9V2.1z"/><path d="M11 7.6H5A2.65 2.65 0 0 1 2.4 5 2.65 2.65 0 0 1 5 2.4h6A2.65 2.65 0 0 1 13.6 5 2.65 2.65 0 0 1 11 7.6zm-6-4c-.8 0-1.4.6-1.4 1.4S4.2 6.4 5 6.4h6c.8 0 1.4-.6 1.4-1.4s-.6-1.4-1.4-1.4H5zM5.1 6a1 1 0 1 0 0-2 1 1 0 1 0 0 2z"/><path fill-rule="evenodd" d="M5 8.4h6a2.65 2.65 0 0 1 2.6 2.6 2.65 2.65 0 0 1-2.6 2.6H5A2.65 2.65 0 0 1 2.4 11 2.65 2.65 0 0 1 5 8.4zm7.3 2.6a1.5 1.5 0 1 1-3 0 1.5 1.5 0 1 1 3 0z"/></g><defs><clipPath id="A"><path fill="#fff" d="M0 0h16v16H0z"/></clipPath></defs></svg></span>',
								'fields' => array(
									array(
										'id'       => 'style_members',
										'class'    => 'sptp_style_generator_list',
										'type'     => 'fieldset',
										'title'    => __( 'Member Meta Fields', 'team-free' ),
										'subtitle' => __( 'Show/Hide member meta fields.', 'team-free' ),
										'desc'     => __( ' To manage member information with drag & drop sorting options effortlessly,</b> <a href="https://getwpteam.com/pricing/?ref=1" target="_blank"><b>Upgrade to Pro!</b></a>', 'team-free' ),
										'default'  => array(
											'image_switch' => true,
											'name_switch'  => true,
											'job_position_switch' => true,
											'bio_switch'   => true,
											'social_switch' => true,
										),
										'fields'   => array(
											array(
												'id'       => 'image_switch',
												'type'     => 'switcher',
												'switcher_drag_icon' => true,
												'title'    => __( 'Photo/Image', 'team-free' ),
												'text_on'  => __( 'Show', 'team-free' ),
												'text_off' => __( 'Hide', 'team-free' ),
												'text_width' => 80,
											),
											array(
												'id'       => 'name_switch',
												'type'     => 'switcher',
												'title'    => __( 'Member Name', 'team-free' ),
												'text_on'  => __( 'Show', 'team-free' ),
												'text_off' => __( 'Hide', 'team-free' ),
												'text_width' => 80,
												'switcher_drag_icon' => true,

											),
											array(
												'id'       => 'job_position_switch',
												'type'     => 'switcher',
												'title'    => __( 'Position/Job Title', 'team-free' ),
												'text_on'  => __( 'Show', 'team-free' ),
												'text_off' => __( 'Hide', 'team-free' ),
												'text_width' => 80,
												'switcher_drag_icon' => true,

											),
											array(
												'id'       => 'bio_switch',
												'class'    => 'sptp_bio_switch',
												'type'     => 'switcher',
												'title'    => __( 'Short Bio', 'team-free' ),
												'text_on'  => __( 'Show', 'team-free' ),
												'text_off' => __( 'Hide', 'team-free' ),
												'text_width' => 80,
												'switcher_drag_icon' => true,

											),
											array(
												'id'       => 'email_switch',
												'class'    => 'sptp_member_meta_info_pro sptp_pro_only_field',
												'type'     => 'switcher',
												'title'    => __( 'Email Address', 'team-free' ),
												'text_on'  => __( 'Show', 'team-free' ),
												'text_off' => __( 'Hide', 'team-free' ),
												'text_width' => 80,
												'switcher_drag_icon' => true,

											),
											array(
												'id'       => 'mobile_switch',
												'class'    => 'sptp_member_meta_info_pro sptp_pro_only_field',
												'type'     => 'switcher',
												'title'    => __( 'Mobile (personal)', 'team-free' ),
												'text_on'  => __( 'Show', 'team-free' ),
												'text_off' => __( 'Hide', 'team-free' ),
												'text_width' => 80,
												'switcher_drag_icon' => true,

											),
											array(
												'id'       => 'phone_switch',
												'class'    => 'sptp_member_meta_info_pro sptp_pro_only_field',
												'type'     => 'switcher',
												'title'    => __( 'Phone (business)', 'team-free' ),
												'text_on'  => __( 'Show', 'team-free' ),
												'text_off' => __( 'Hide', 'team-free' ),
												'text_width' => 80,
												'switcher_drag_icon' => true,

											),
											array(
												'id'       => 'location_switch',
												'class'    => 'sptp_member_meta_info_pro sptp_pro_only_field',
												'type'     => 'switcher',
												'title'    => __( 'Location', 'team-free' ),
												'text_on'  => __( 'Show', 'team-free' ),
												'text_off' => __( 'Hide', 'team-free' ),
												'text_width' => 80,
												'switcher_drag_icon' => true,

											),
											array(
												'id'       => 'website_switch',
												'class'    => 'sptp_member_meta_info_pro sptp_pro_only_field',
												'type'     => 'switcher',
												'title'    => __( 'Website', 'team-free' ),
												'text_on'  => __( 'Show', 'team-free' ),
												'text_off' => __( 'Hide', 'team-free' ),
												'text_width' => 80,
												'switcher_drag_icon' => true,

											),
											array(
												'id'       => 'experience_switch',
												'class'    => 'sptp_member_experience sptp_pro_only_field',
												'type'     => 'switcher',
												'title'    => __( 'Year of Experience ', 'team-free' ),
												'text_on'  => __( 'Show', 'team-free' ),
												'text_off' => __( 'Hide', 'team-free' ),
												'text_width' => 80,
												'switcher_drag_icon' => true,

											),
											array(
												'id'       => 'extra_fields_switch',
												'class'    => 'sptp_member_experience sptp_pro_only_field',
												'type'     => 'switcher',
												'title'    => __( 'Additional Custom Fields', 'team-free' ),
												'text_on'  => __( 'Show', 'team-free' ),
												'text_off' => __( 'Hide', 'team-free' ),
												'text_width' => 80,
												'switcher_drag_icon' => true,
											),
											array(
												'id'       => 'skill_switch',
												'class'    => 'sptp_member_meta_info_pro sptp_pro_only_field',
												'type'     => 'switcher',
												'title'    => __( 'Skill Bars', 'team-free' ),
												'text_on'  => __( 'Show', 'team-free' ),
												'text_off' => __( 'Hide', 'team-free' ),
												'text_width' => 80,
												'switcher_drag_icon' => true,
											),
											array(
												'id'       => 'social_switch',
												'type'     => 'switcher',
												'title'    => __( 'Social Profiles', 'team-free' ),
												'text_on'  => __( 'Show', 'team-free' ),
												'text_off' => __( 'Hide', 'team-free' ),
												'text_width' => 80,
												'switcher_drag_icon' => true,
											),
										),
									),
								),
							),
							array(
								'title'  => __( 'Member Info Styles', 'team-free' ),
								'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" ><g clip-path="url(#A)" fill="#343434"><path d="M14.7 6.6c-.4 0-.7.2-1 .4l-2.4 2.7c.3.1.6.3.9.6s.5.6.6.9l2.7-2.4c.3-.2.4-.6.4-1 .1-.6-.5-1.2-1.2-1.2z"/><path d="M9 10.7c-.6.6-.5 1.2-.5 1.7.1.6.1 1.1-.5 1.6v.3c0 .1.1.2.2.2.2.1.5.1.9.1.9 0 2.1-.3 2.9-1.1.4-.4.6-.9.6-1.4s-.2-1-.6-1.4c-.9-.8-2.2-.8-3 0z"/><g fill-rule="evenodd"><path d="M2.9 5.5a.6.6 0 0 1 .6-.6h5a.6.6 0 1 1 0 1.2h-5a.6.6 0 0 1-.6-.6zm0 2.5a.6.6 0 0 1 .6-.6h5a.6.6 0 1 1 0 1.2h-5a.6.6 0 0 1-.6-.6zm0 2.5a.6.6 0 0 1 .6-.6H6a.6.6 0 1 1 0 1.2H3.5a.6.6 0 0 1-.6-.6z"/></g><path d="M11.8 1.4H2.2A2.26 2.26 0 0 0-.1 3.7v8.6a2.26 2.26 0 0 0 2.3 2.3h4a.65.65 0 0 0 .6-.6.65.65 0 0 0-.6-.6h-4a1.11 1.11 0 0 1-1.1-1.1V3.7a1.11 1.11 0 0 1 1.1-1.1h9.6a1.11 1.11 0 0 1 1.1 1.1v1.4a.65.65 0 0 0 .6.6h.1c.1 0 .2-.1.3-.1.1-.1.2-.3.2-.5V3.7a2.26 2.26 0 0 0-2.3-2.3z"/></g><defs><clipPath id="A"><path fill="#fff" d="M0 0h16v16H0z"/></clipPath></defs></svg></span>',
								'fields' => array(
									array(
										'id'         => 'biography_type',
										'type'       => 'button_set',
										'title'      => __( 'Bio Display Type', 'team-free' ),
										'subtitle'   => __( 'Choose biography display type for the member description.', 'team-free' ),
										'options'    => array(
											'short-bio' => __( 'Short', 'team-free' ),
											'full-bio'  => __( 'Full', 'team-free' ),
											'any-bio'   => __( 'Any', 'team-free' ),
										),
										'default'    => 'short-bio',
										'title_info' => __(
											'<b>Short:</b> Retrieve the member description from the Short Bio field.<br/><b>Full:</b> Retrieve the member description from the Description field.<br/><b>Any:</b> Retrieve the member description from either the Short Bio or Description field. If the Short Bio field is empty, the description will be retrieved from the Description field.',
											'team-free'
										),
										'dependency' => array( 'layout_preset|bio_switch', '!=|==', 'thumbnail-pager|true', true ),
									),
									array(
										'id'              => 'style_description_character_limit',
										'class'           => 'style_description_character_limit',
										'type'            => 'spacing',
										'title'           => __( 'Bio Limit', 'team-free' ),
										'all'             => true,
										'all_placeholder' => '',
										'all_icon'        => '',
										'units'           => array( 'Words (Pro)', 'Characters (Pro)' ),
										'default'         => array(
											'all'  => '100',
											'unit' => 'Characters (Pro)',
										),
										'subtitle'        => __( 'Set number of characters or words for the member description.', 'team-free' ),
										'title_info'      => __( 'Leave it empty to show the full member biography.', 'team-free' ),
										'only_pro'        => true,
										'dependency'      => array( 'layout_preset|bio_switch', '!=|==', 'thumbnail-pager|true', true ),
									),
									array(
										'id'         => 'read_more',
										'type'       => 'switcher',
										'class'      => 'sptp_pro_only_field',
										'title'      => __( 'Read More', 'team-free' ),
										'subtitle'   => __( 'Show/Hide read more button.', 'team-free' ),
										'text_on'    => __( 'Show', 'team-free' ),
										'text_off'   => __( 'Hide', 'team-free' ),
										'text_width' => 80,
										'default'    => false,
										'only_pro'   => true,
										'dependency' => array( 'layout_preset', '!=', 'thumbnail-pager', true ),
									),
									array(
										'type'    => 'notice',
										'content' => __( 'To personalize member information styles with flexible options,</b> <a href="https://getwpteam.com/pricing/?ref=1" target="_blank"><b>Upgrade to Pro!</b></a>', 'team-free' ),
									),
									array(
										'type'    => 'subheading',
										'content' => __( 'Social Profiles', 'team-free' ),
									),
									array(
										'id'     => 'social_settings',
										'class'  => 'sptp_social_settings',
										'type'   => 'fieldset',
										'title'  => __( 'Social Settings', 'team-free' ),
										'fields' => array(
											array(
												'id'       => 'social_icon_shape',
												'class'    => 'sptp_social_icon_shape',
												'type'     => 'image_select',
												'title'    => __( 'Social Icon Shape', 'team-free' ),
												'subtitle' => __( 'Choose a social icon shape.', 'team-free' ),
												'options'  => array(
													'rounded' => array(
														'image' => SPT_PLUGIN_ROOT . 'src/Admin/img/social-icon/round.svg',
														'class' => 'sptp_free-feature',
													),
													'circle'  => array(
														'image' => SPT_PLUGIN_ROOT . 'src/Admin/img/social-icon/circle.svg',
														'class' => 'sptp_free-feature',
													),
												),
												'default'  => 'rounded',
											),
											array(
												'id'       => 'social_position',
												'class'    => 'sptp_social_position',
												'type'     => 'button_set',
												'title'    => __( 'Position', 'team-free' ),
												'subtitle' => __( 'Set alignment for social profile icon.', 'team-free' ),
												'options'  => array(
													'left' => '<i class="fa fa-align-left" title="Left"></i>',
													'center' => '<i class="fa fa-align-center" title="Center"></i>',
													'right' => '<i class="fa fa-align-right" title="Right"></i>',
												),
												'title_info' => '<div class="spf-img-tag"><img src="' . SPT_PLUGIN_ROOT . 'src/Admin/img/visual/social_margin.svg" alt="social_margin"></div><div class="spf-info-label img">' . __( 'Social Margin', 'team-free' ) . '</div>',

												'default'  => 'center',
											),
											array(
												'id'       => 'social_icon_custom_color',
												'type'     => 'button_set',
												'title'    => __( 'Custom Color', 'team-free' ),
												'options'  => array(
													'original' => __( 'Native', 'team-free' ),
													'custom' => array(
														'option_name' => __( 'Custom', 'team-free' ),
														'pro_only' => true,
													),
												),
												'default'  => 'original',
												'subtitle' => __( 'Choose icon color type.', 'team-free' ),
											),
											array(
												'id'       => 'social_margin',
												'type'     => 'spacing',
												'title'    => __( 'Margin', 'team-free' ),
												'subtitle' => __( 'Set margin for social profile.', 'team-free' ),
												'units'    => array( 'px' ),
												'default'  => array(
													'top'  => '0',
													'right' => '0',
													'bottom' => '4',
													'left' => '0',
												),
											),
										),
									),
									array(
										'type'    => 'subheading',
										'content' => __( 'Skill Progress Bars (Pro)', 'team-free' ),
									),
									array(
										'id'       => 'skill_settings',
										'type'     => 'fieldset',
										'title'    => __( 'Skill Bars Settings', 'team-free' ),
										'class'    => 'sptp-custom-fields sptp_social_settings',
										'only_pro' => true,
										'fields'   => array(
											array(
												'id'       => 'progressbar_color_group',
												'type'     => 'color_group',
												'title'    => __( 'Progress Bar Color', 'team-free' ),
												'options'  => array(
													'progress_color' => __( 'Progress Bar', 'team-free' ),
													'progress_bg_color' => __( 'Background', 'team-free' ),
												),
												'subtitle' => __( 'Set color for progress bar.', 'team-free' ),
												'default'  => array(
													'progress_color' => '#559173',
													'progress_bg_color' => '#c9dfd1',
												),
											),
											array(
												'id'       => 'tooltip_color_group',
												'class'    => 'tooltip_color_group',
												'type'     => 'color_group',
												'title'    => __( 'Tooltip Color', 'team-free' ),
												'subtitle' => __( 'Set color for tooltip.', 'team-free' ),
												'only_pro' => true,
												'options'  => array(
													'progress_tooltip_color'    => __( 'Percentage', 'team-free' ),
													'progress_tooltip_bg_color' => __( 'Tooltip Background', 'team-free' ),
												),
												'default'  => array(
													'progress_tooltip_color'    => '#ffffff',
													'progress_tooltip_bg_color' => '#559173',
												),
												'dependency' => array( 'layout_preset', '!=', 'table', true ),
											),
										),
									), // End of the Skill Settings Fieldset.
								),
							),
							array(
								'title'  => __( 'Member Image Styles', 'team-free' ),
								'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"><g clip-path="url(#A)" fill="#343434"><path d="M4.9 6.2a1.5 1.5 0 1 0 0-3 1.5 1.5 0 1 0 0 3zM12.5 0H1.7C.8 0 0 .8 0 1.7v10.8c0 .9.8 1.7 1.7 1.7h7.9c-.7-.6-1.1-1.5-1.1-2.5 0-1.8 1.5-3.2 3.2-3.2 1 0 1.8.4 2.5 1.1V1.7c0-.9-.8-1.7-1.7-1.7zm.5 8l-.2-.1-.4-.3-1.2-.8c-.7-.5-1.7-.6-2.4-.2-.2 0-.3.1-.5.3l-.6.5-1.5 1.4-.4.3c-.2.2-.4.4-.7.4-.5.2-1 .2-1.5.1-.3 0-.5-.1-.7-.3l-1.4-1-.3-.2V1.7a.47.47 0 0 1 .5-.5h10.8a.47.47 0 0 1 .5.5V8z"/><path d="M14.2 8.3c-.4-.3-.8-.5-1.2-.6-.2-.1-.4-.1-.6-.1s-.5-.1-.7-.1a4.23 4.23 0 0 0-4.2 4.2c0 .9.3 1.8.8 2.5.8 1.1 2 1.8 3.5 1.8a4.23 4.23 0 0 0 4.2-4.2c0-1.5-.7-2.7-1.8-3.5zM11.8 15c-.8 0-1.6-.3-2.1-.8-.7-.6-1.1-1.5-1.1-2.5 0-1.8 1.5-3.2 3.2-3.2 1 0 1.8.4 2.5 1.1.5.6.8 1.3.8 2.1-.1 1.8-1.6 3.3-3.3 3.3z"/><g fill-rule="evenodd"><path d="M13.5 10.1c-.1-.1-.2-.1-.3-.1-.2 0-.3.1-.5.2l-.4.5-.5.5-.1.2-.2.2a1.38 1.38 0 0 1 .4.3c.1 0 .1.1.1.2.1.1.1.2.2.3l1.2-1 .1-.1c.1-.1.2-.3.2-.5.1-.4 0-.6-.2-.7z"/><path d="M10.4 12c-.3.3-.2.6-.2.9v.3c0 .2-.1.3-.2.5v.1s0 .1.1.1.2.1.4.1c.4 0 1-.1 1.4-.5l.2-.2c.1-.1.1-.3.1-.5 0-.3-.1-.5-.3-.7-.5-.5-1.1-.5-1.5-.1z"/></g></g><defs><clipPath id="A"><path fill="#fff" d="M0 0h16v16H0z"/></clipPath></defs></svg></span>',
								'fields' => array(
									array(
										'id'         => 'image_on_off',
										'type'       => 'switcher',
										'title'      => __( 'Photo/Image', 'team-free' ),
										'subtitle'   => __( 'Show/Hide member photo or image.', 'team-free' ),
										'text_on'    => __( 'Show', 'team-free' ),
										'text_off'   => __( 'Hide', 'team-free' ),
										'text_width' => 80,
										'default'    => true,
									),
									array(
										'id'         => 'image_size',
										'class'      => 'sptp_image_size',
										'type'       => 'select',
										'title'      => __( 'Dimensions', 'team-free' ),
										'subtitle'   => __( 'Sets the dimensions (width & height) for the member image.', 'team-free' ),
										'options'    => 'img_sizes',
										'default'    => 'medium',
										'dependency' => array( 'image_on_off', '==', 'true' ),
									),
									array(
										'id'         => 'custom_image_option',
										'class'      => 'sptp_custom_image_option spf-pro-only',
										'type'       => 'fieldset',
										'title'      => __( 'Custom Dimensions', 'team-free' ),
										'dependency' => array( 'image_on_off|image_size', '==|==', 'true|custom', true ),
										'fields'     => array(
											array(
												'id'      => 'custom_image_width',
												'type'    => 'spinner',
												'title'   => __( 'Width*', 'team-free' ),
												'default' => 400,
												'unit'    => 'px',
												'max'     => 99999,
											),
											array(
												'id'      => 'custom_image_height',
												'type'    => 'spinner',
												'title'   => __( 'Height*', 'wp' ),
												'default' => 416,
												'unit'    => 'px',
												'max'     => 99999,
											),
											array(
												'id'      => 'custom_image_crop',
												'type'    => 'switcher',
												'title'   => __( 'Hard Crop', 'team-free' ),
												'default' => true,

											),
										),
									),
									array(
										'id'         => 'load_2x_image',
										'class'      => 'sptp_load_2x_image sptp_pro_only_field',
										'type'       => 'switcher',
										'title'      => __( 'Load 2x Resolution Image in Retina Display', 'team-free' ),
										'subtitle'   => __( 'You should upload 2x sized images to show in retina display.', 'team-free' ),
										'text_on'    => __( 'Enabled', 'team-free' ),
										'text_off'   => __( 'Disabled', 'team-free' ),
										'text_width' => 100,
										'default'    => false,
										'dependency' => array( 'image_on_off|image_size', '==|==', 'true|custom', true ),
									),
									array(
										'id'         => 'image_shape',
										'class'      => 'sptp_image_shape',
										'type'       => 'image_select',
										'title'      => __( 'Image Shape', 'team-free' ),
										'subtitle'   => __( 'Choose an image shape for member.', 'team-free' ),
										'options'    => array(
											'sptp-square'  => array(
												'image' => SPT_PLUGIN_ROOT . 'src/Admin/img/image-shape/square.svg',
												'option_name' => __( 'Square', 'team-free' ),
											),
											'sptp-rounded' => array(
												'image'    => SPT_PLUGIN_ROOT . 'src/Admin/img/image-shape/rounded.svg',
												'option_name' => __( 'Rounded', 'team-free' ),
												'pro_only' => true,
											),
											'sptp-circle'  => array(
												'image'    => SPT_PLUGIN_ROOT . 'src/Admin/img/image-shape/circle.svg',
												'option_name' => __( 'Circle', 'team-free' ),
												'pro_only' => true,
											),
										),
										'default'    => 'sptp-square',
										'dependency' => array( 'image_on_off', '==', 'true' ),
									),
									array(
										'id'       => 'border',
										'type'     => 'border',
										'title'    => __( 'Border', 'team-free' ),
										'subtitle' => __( 'Set border.', 'team-free' ),
										'all'      => true,
										'default'  => array(
											'style' => 'solid',
										),
									),
									array(
										'id'         => 'background',
										'type'       => 'color',
										'title'      => __( 'Background', 'team-free' ),
										'subtitle'   => __( 'Set background for member image.', 'team-free' ),
										'default'    => '#FFFFFF',
										'dependency' => array( 'image_on_off', '==', 'true' ),
									),
									array(
										'id'         => 'image_flip',
										'type'       => 'switcher',
										'class'      => 'sptp_pro_only_field',
										'title'      => __( 'Image Flip', 'team-free' ),
										'subtitle'   => __( 'Enable/Disable Member photo/image flipping.', 'team-free' ),
										'title_info' => __( 'The flipping image is the first image of the member photo gallery.', 'team-free' ),
										'text_on'    => __( 'Enabled', 'team-free' ),
										'text_off'   => __( 'Disabled', 'team-free' ),
										'text_width' => 100,
										'default'    => false,
										'only_pro'   => true,
									),
									array(
										'id'         => 'image_lazyload',
										'type'       => 'switcher',
										'class'      => 'sptp_pro_only_field',
										'title'      => __( 'Lazy Load', 'team-free' ),
										'subtitle'   => __( 'Enable/Disable lazy load for member images.', 'team-free' ),
										'text_on'    => __( 'Enabled', 'team-free' ),
										'text_off'   => __( 'Disabled', 'team-free' ),
										'text_width' => 100,
										'default'    => false,
										'only_pro'   => true,
										'dependency' => array( 'image_on_off', '==', 'true' ),
									),
									array(
										'id'         => 'image_zoom',
										'type'       => 'select',
										'title'      => __( 'Zoom', 'team-free' ),
										'subtitle'   => __( 'Select a zoom effect for image on hover.', 'team-free' ),
										'options'    => array(
											'none'     => __( 'Normal', 'team-free' ),
											'zoom_in'  => __( 'Zoom In', 'team-free' ),
											'zoom_out' => __( 'Zoom Out', 'team-free' ),
										),
										'default'    => 'none',
										'dependency' => array( 'image_on_off', '==', 'true' ),
									),
									array(
										'id'         => 'image_grayscale',
										'class'      => 'sptp_image_grayscale',
										'type'       => 'button_set',
										'title'      => __( 'Image Mode', 'team-free' ),
										'subtitle'   => __( 'Select a mode for the image.', 'team-free' ),
										'options'    => array(
											'none'   => __( 'Normal', 'team-free' ),
											'always' => array(
												'option_name' => __( 'Grayscale', 'team-free' ),
												'pro_only' => true,
											),
										),
										'default'    => 'none',
										'dependency' => array( 'image_on_off', '==', 'true' ),
									),
									array(
										'id'         => 'image_grayscale_on_hover',
										'type'       => 'checkbox',
										'class'      => 'sptp_pro_only_field',
										'title'      => __( 'Grayscale on Hover', 'team-free' ),
										'subtitle'   => __( 'Check to grayscale logo image on hover (pro).', 'team-free' ),
										'only_pro'   => true,
										'default'    => false,
										'dependency' => array( 'image_on_off|image_grayscale', '==|==', 'true|none', true ),
									),
									array(
										'type'    => 'notice',
										'content' => __( 'To unleash your creativity with flexible member image styling options,</b> <a href="https://getwpteam.com/pricing/?ref=1" target="_blank"><b>Upgrade to Pro!</b></a>', 'team-free' ),
									),
								),
							),
							array(
								'title'  => __( 'Join Team Button', 'team-free' ),
								'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#343434"><path d="M11.9 12.1H4.1C1.8 12.1 0 10.3 0 8s1.8-4.1 4.1-4.1h7.8C14.2 3.9 16 5.7 16 8s-1.8 4.1-4.1 4.1zm-7.8-7C2.5 5.1 1.2 6.4 1.2 8s1.3 2.9 2.9 2.9h7.8c1.6 0 2.9-1.3 2.9-2.9s-1.3-2.9-2.9-2.9H4.1z"/><path fill-rule="evenodd" d="M2.9 8a.9.9 0 0 1 .9-.9h8.4a.9.9 0 0 1 0 1.8H3.8a.9.9 0 0 1-.9-.9z"/></svg></span>',
								'fields' => array(
									array(
										'type'    => 'notice',
										'content' => __( 'To add a "Join Team" button at the end of the members section with numerous options,</b> <a href="https://getwpteam.com/pricing/?ref=1" target="_blank"><b>Upgrade to Pro!</b></a>', 'team-free' ),
									),
									// Member search options.
									array(
										'id'       => 'call_to_action_fields',
										'class'    => 'member-call-to-action-fields',
										'type'     => 'fieldset',
										'only_pro' => true,
										'fields'   => array(
											array(
												'id'       => 'show_call_to_action',
												'type'     => 'switcher',
												'title'    => __( 'Join Team Button', 'team-free' ),
												'subtitle' => __( 'Enable/Disable join team button.', 'team-free' ),
												'text_on'  => __( 'Enabled', 'team-free' ),
												'text_off' => __( 'Disabled', 'team-free' ),
												'text_width' => 100,
												'default'  => true,
												'only_pro' => true,
												'title_info' => __( 'The call to action will be displayed below the team members showcase. Use this functionality to invite people to join your team or to encourage them to contact you.', 'team-free' ),
											),
											array(
												'id'       => 'title',
												'type'     => 'text',
												'title'    => __( 'Title', 'team-free' ),
												'subtitle' => __( 'Set call to action button title.', 'team-free' ),
												'default'  => __( 'Join Our Team', 'team-free' ),
												'only_pro' => true,
												'dependency' => array( 'show_call_to_action', '==', 'true', true ),
											),
											array(
												'id'       => 'cta_btn_type',
												'type'     => 'image_select',
												'title'    => __( 'Button Type', 'team-free' ),
												'class'    => 'hide-active-sign cta_btn_type',
												'subtitle' => __( 'Select a button type for the call to action.', 'team-free' ),
												'options'  => array(
													'filled'   => array(
														'image' => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-style/filled.svg',
														'option_name' => __( 'Filled', 'team-free' ),
													),
													'outline' => array(
														'image' => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-style/outline.svg',
														'option_name' => __( 'Outline', 'team-free' ),
													),
												),
												'default'  => 'filled',
												'only_pro' => true,
												'dependency' => array( 'show_call_to_action', '==', 'true', true ),
											),
											array(
												'id'       => 'button_label',
												'type'     => 'text',
												'title'    => __( 'Button Label', 'team-free' ),
												'subtitle' => __( 'Set button label.', 'team-free' ),
												'default'  => __( 'See Job Openings', 'team-free' ),
												'only_pro' => true,
												'dependency' => array( 'show_call_to_action', '==', 'true', true ),
											),
											array(
												'id'       => 'cta_btn_link_type',
												'type'     => 'button_set',
												'title'    => __( 'Button Link Type', 'team-free' ),
												'subtitle' => __( 'Select a button link type for the call to action.', 'team-free' ),
												'options'  => array(
													'url' => __( 'URL', 'team-free' ),
													'email' => __( 'Email', 'team-free' ),
												),
												'default'  => 'url',
												'only_pro' => true,
												'dependency' => array( 'show_call_to_action', '==', 'true', true ),
											),
											array(
												'id'       => 'button_link',
												'type'     => 'text',
												'title'    => __( 'Link URL', 'team-free' ),
												'subtitle' => __( 'Set button link url.', 'team-free' ),
												'only_pro' => true,
												'dependency' => array( 'show_call_to_action|cta_btn_link_type', '==|==', 'true|url', true ),
											),
											array(
												'id'       => 'button_email',
												'type'     => 'text',
												'title'    => __( 'Email Address', 'team-free' ),
												'subtitle' => __( 'Set button link email.', 'team-free' ),
												'only_pro' => true,
												'dependency' => array( 'show_call_to_action|cta_btn_link_type', '==|==', 'true|email', true ),
											),
											array(
												'id'       => 'cta_btn_colors',
												'type'     => 'color_group',
												'class'    => 'cta_btn_colors',
												'title'    => __( 'Button Color', 'team-free' ),
												'subtitle' => __( 'Set button color.', 'team-free' ),
												'options'  => array(
													'color'    => __( 'Color', 'team-free' ),
													'active_color' => __( 'Hover Color', 'team-free' ),
													'bg_color' => __( 'Background', 'team-free' ),
													'active_hover_bg_color' => __( 'Hover BG', 'team-free' ),
												),
												'default'  => array(
													'color'    => '#ffffff',
													'active_color' => '#ffffff',
													'bg_color' => '#3677e4',
													'active_hover_bg_color' => '#3677e4',
												),
												'only_pro' => true,
												'dependency' => array( 'show_call_to_action', '==', 'true', true ),
											),
											array(
												'id'       => 'cta_btn_border',
												'type'     => 'border',
												'title'    => __( 'Border', 'team-free' ),
												'subtitle' => __( 'Set border for the call to action button.', 'team-free' ),
												'all'      => true,
												'border_radius' => true,
												'default'  => array(
													'all' => 2,
													'style' => 'solid',
													'color' => '#3677e4',
													'hover_color' => '#3677e4',
													'border_radius' => '5',
												),
												'only_pro' => true,
												'dependency' => array( 'show_call_to_action', '==', 'true', true ),
											),
											array(
												'id'       => 'cta_btn_padding',
												'type'     => 'spacing',
												'title'    => __( 'Padding', 'team-free' ),
												'units'    => array( 'px' ),
												'subtitle' => __( 'Set padding for the button.', 'team-free' ),
												'default'  => array(
													'top'  => '12',
													'right' => '38',
													'bottom' => '12',
													'left' => '38',
												),
												'only_pro' => true,
												'dependency' => array( 'show_call_to_action', '==', 'true', true ),
											),
											array(
												'id'       => 'cta_btn_alignments',
												'class'    => 'social_position',
												'type'     => 'button_set',
												'title'    => __( 'Alignment', 'team-free' ),
												'subtitle' => __( 'Set alignment for the call to action button.', 'team-free' ),
												'options'  => array(
													'left' => '<i class="fa fa-align-left" title="Left"></i>',
													'center' => '<i class="fa fa-align-center" title="Center"></i>',
													'right' => '<i class="fa fa-align-right" title="Right"></i>',
												),
												'only_pro' => true,
												'default'  => 'center',
												'dependency' => array( 'show_call_to_action', '==', 'true', true ),
											),
											array(
												'id'       => 'cta_btn_margin',
												'type'     => 'spacing',
												'title'    => __( 'Margin', 'team-free' ),
												'units'    => array( 'px' ),
												'subtitle' => __( 'Set margin for the button.', 'team-free' ),
												'default'  => array(
													'top'  => '50',
													'right' => '0',
													'bottom' => '25',
													'left' => '0',
												),
												'only_pro' => true,
												'dependency' => array( 'show_call_to_action', '==', 'true', true ),
											),
										),
									), // End of the Show call to action field.
								),
							),
						),
					),
				),
			)
		);
	}
}
