<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Admin\Forms\FireBox;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Helpers\AnimationsHelper;

class Design
{
	/**
	 * Holds the Design Settings
	 * 
	 * @return  array
	 */
	public function getSettings()
	{
		$settings = [
			'title' => 'FPF_DESIGN',
			'content' => [
				'popup_size' => [
					'title' => [
						'title' => firebox()->_('FB_DESIGN_POPUP_SIZE'),
						'description' => firebox()->_('FB_DESIGN_POPUP_SIZE_DESC')
					],
					'fields' => [
						[
							'name' => 'width_control',
							'type' => 'ResponsiveControl',
							'label' => 'FPF_WIDTH',
							'class' => ['small-12', 'medium-auto'],
							'value' => [
								'width' => [
									'desktop' => 500
								]
							],
							'fields' => [
								[
									'name' => 'width',
									'type' => 'Number',
									'label' => 'FPF_WIDTH',
									'input_class' => ['fullwidth'],
									'placeholder' => '500',
									'units' => ['px', '%'],
									'units_relative_position' => false,
									'render_group' => false
								],
							]
						],
						[
							'name' => 'height_control',
							'type' => 'ResponsiveControl',
							'label' => 'FPF_HEIGHT',
							'class' => ['small-12', 'medium-auto'],
							'fields' => [
								[
									'name' => 'height',
									'type' => 'Number',
									'input_class' => ['fullwidth'],
									'placeholder' => 'auto',
									'units' => ['px', '%', 'auto'],
									'units_default' => 'auto',
									'units_relative_position' => false,
									'render_group' => false
								]
							]
						]
					]
				],
				'box' => [
					'title' => [
						'title' => firebox()->_('FB_CAMPAIGN'),
						'description' => firebox()->_('FB_METABOX_DESIGN_CAMPAIGN_DESC')
					],
					'fields' => [
						[
							'name' => 'fontsize_control',
							'type' => 'ResponsiveControl',
							'label' => 'FPF_FONT_SIZE',
							'description' => 'FPF_FONT_SIZE_DESC',
							'control_inner_class' => ['fpf-max-width-300'],
							'value' => [
								'fontsize' => [
									'desktop' => 16
								]
							],
							'fields' => [
								[
									'name' => 'fontsize',
									'type' => 'Number',
									'placeholder' => 16,
									'input_class' => ['fullwidth'],
									'units' => ['px', 'em', 'rem'],
									'units_relative_position' => false,
									'render_group' => false
								],
							]
						],
						[
							'name' => 'padding_control',
							'type' => 'ResponsiveControl',
							'label' => 'FPF_PADDING',
							'description' => firebox()->_('FB_METABOX_PADDING_DESC'),
							'control_inner_class' => ['fpf-max-width-300'],
							'value' => [
								'padding' => [
									'desktop' => [
										'top' => 30,
										'right' => 30,
										'bottom' => 30,
										'left' => 30
									]
								]
							],
							'fields' => [
								[
									'name' => 'padding',
									'type' => 'Dimensions',
									'units' => ['px', '%'],
									'units_relative_position' => false,
									'render_group' => false,
									'isLinked' => true
								],
							]
						],
						[
							'name' => 'margin_control',
							'type' => 'ResponsiveControl',
							'label' => 'FPF_MARGIN',
							'description' => firebox()->_('FB_METABOX_MARGIN_DESC'),
							'control_inner_class' => ['fpf-max-width-300'],
							'fields' => [
								[
									'name' => 'margin',
									'type' => 'Dimensions',
									'units' => ['px', '%'],
									'render_group' => false,
									'units_relative_position' => false,
									'isLinked' => true
								],
							]
						],
						[
							'name' => 'textcolor',
							'type' => 'Color',
							'label' => 'FPF_TEXT_COLOR',
							'default' => '#444444',
							'input_class' => ['medium']
						],
						[
							'name' => 'backgroundcolor',
							'type' => 'Color',
							'label' => 'FPF_BG_COLOR',
							'description' => firebox()->_('FB_METABOX_BG_COLOR_DESC'),
							'default' => '#ffffff',
							'input_class' => ['medium']
						],
						[
							'name' => 'aligncontent',
							'type' => 'Dropdown',
							'label' => 'FPF_ALIGN_CONTENT',
							'choices' => [
								'acl' => firebox()->_('FB_METABOX_POSITION_TL'),
								'act acc' => firebox()->_('FB_METABOX_POSITION_TC'),
								'act acr' => firebox()->_('FB_METABOX_POSITION_TR'),
								'acm acl' => firebox()->_('FB_METABOX_POSITION_ML'),
								'acm acc' => firebox()->_('FB_METABOX_POSITION_MC'),
								'acm acr' => firebox()->_('FB_METABOX_POSITION_MR'),
								'acb acl' => firebox()->_('FB_METABOX_POSITION_BL'),
								'acb acc' => firebox()->_('FB_METABOX_POSITION_BC'),
								'acb acr' => firebox()->_('FB_METABOX_POSITION_BR')
							],
							'filter' => 'sanitize_text_field'
						],
						[
							'name' => 'boxshadow',
							'type' => 'Toggle',
							'label' => firebox()->_('FB_METABOX_SHADOW'),
							'input_class' => ['default'],
							'default' => '0',
							'choices' => [
								'0' => 'FPF_NONE',
								'1' => firebox()->_('FB_METABOX_SHADOW_S1'),
								'2' => firebox()->_('FB_METABOX_SHADOW_S2'),
								'3' => firebox()->_('FB_METABOX_SHADOW_S3'),
								'elevation' => firebox()->_('FB_ELEVATION')
							]
						]
					]
				],
				'close_button' => [
					'title' => [
						'title' => firebox()->_('FB_METABOX_CLOSE_BUTTON'),
						'description' => firebox()->_('FB_METABOX_CLOSE_BUTTON_TITLE_DESC')
					],
					'fields' => [
						[
							'name' => 'closebutton.show',
							'type' => 'Toggle',
							'input_class' => ['default'],
							'default' => 1,
							'choices' => [
								1 => firebox()->_('FB_METABOX_CLOSE_BUTTON_INSIDE'),
								2 => firebox()->_('FB_METABOX_CLOSE_BUTTON_OUTSIDE'),
								0 => 'FPF_HIDE'
							]
						],
						[
							'name' => 'closebutton.source',
							'type' => 'Toggle',
							'label' => firebox()->_('FB_METABOX_CLOSE_BUTTON_TYPE'),
							'default' => 'icon',
							'choices' => [
								'icon' => 'FPF_ICON',
								'image' => 'FPF_IMAGE'
							],
							'showon' => '[closebutton][show]:1,2'
						],
						[
							'name' => 'closebutton.color',
							'type' => 'Color',
							'label' => 'FPF_COLOR',
							'default' => 'rgba(136, 136, 136, 1)',
							'input_class' => ['medium'],
							'showon' => '[closebutton][source]:icon[AND][closebutton][show]:1,2'
						],
						[
							'name' => 'closebutton.hover',
							'type' => 'Color',
							'label' => firebox()->_('FB_METABOX_HOVER_COLOR'),
							'default' => 'rgba(85, 85, 85, 1)',
							'input_class' => ['medium'],
							'showon' => '[closebutton][source]:icon[AND][closebutton][show]:1,2'
						],
						[
							'name' => 'closebutton.size',
							'type' => 'Slider',
							'label' => 'FPF_SIZE',
							'number_class' => ['xsmall'],
							'default' => 30,
							'step' => 2,
							'addon' => 'px',
							'showon' => '[closebutton][source]:icon[AND][closebutton][show]:1,2'
						],
						[
							'name' => 'closebutton.image',
							'type' => 'MediaUploader',
							'label' => 'FPF_SELECT_IMAGE',
							'description' => firebox()->_('FB_METABOX_SELECT_IMAGE_DESC'),
							'showon' => '[closebutton][source]:image[AND][closebutton][show]:1,2',
							'filter' => 'esc_url_raw'
						],
						[
							'name' => 'closebutton.delay',
							'type' => 'Slider',
							'label' => firebox()->_('FB_METABOX_DELAY'),
							'default' => 0,
							'min' => 0,
							'max' => 120,
							'step' => 2,
							'addon' => 'sec',
							'showon' => '[closebutton][show]:1,2'
						],
					]
				],
				'animation' => [
					'title' => [
						'title' => firebox()->_('FB_METABOX_ANIMATION'),
						'description' => firebox()->_('FB_METABOX_ANIMATION_DESC'),
					],
					'fields' => [
						[
							'name' => 'animationin',
							'type' => 'SearchDropdown',
							'label' => firebox()->_('FB_METABOX_ANIMATION_IN'),
							'default' => ['transition.slideUpIn'],
							'control_inner_class' => ['fpf-fullwidth'],
							'search_query_placeholder' => firebox()->_('FB_TRANSITION_IN_HINT'),
							'hide_ids' => true,
							'multiple' => false,
							'items' => AnimationsHelper::getTransitionsIn(),
							'filter' => 'sanitize_text_field',
							'class' => ['medium-auto'],
							'input_class' => ['fullwidth']
						],
						[
							'name' => 'animationout',
							'type' => 'SearchDropdown',
							'label' => firebox()->_('FB_METABOX_ANIMATION_OUT'),
							'default' => ['transition.fadeOut'],
							'control_inner_class' => ['fpf-fullwidth'],
							'search_query_placeholder' => firebox()->_('FB_TRANSITION_OUT_HINT'),
							'hide_ids' => true,
							'multiple' => false,
							'items' => AnimationsHelper::getTransitionsOut(),
							'filter' => 'sanitize_text_field',
							'class' => ['medium-auto'],
							'input_class' => ['fullwidth']
						],
						[
							'name' => 'duration',
							'type' => 'Slider',
							'label' => firebox()->_('FB_METABOX_DURATION'),
							'default' => 0.3,
							'min' => 0,
							'max' => 2,
							'step' => 0.1,
							'number_step' => 0.1,
							'addon' => 'sec'
						]
					]
				],
				'border' => [
					'title' => [
						'title' => 'FPF_BORDER',
						'description' => 'FPF_BORDER_DESC'
					],
					'fields' => [
						[
							'name' => 'bordertype',
							'type' => 'Dropdown',
							'label' => 'FPF_STYLE',
							'default' => 'solid',
							'choices' => [
								'none' => 'FPF_NONE',
								'solid' => 'FPF_SOLID',
								'dotted' => 'FPF_DOTTED',
								'double' => 'FPF_DOUBLE',
								'dashed' => 'FPF_DASHED',
								'inset' => 'FPF_INSET',
								'outset' => 'FPF_OUTSET',
								'groove' => 'FPF_GROOVE',
								'ridge' => 'FPF_RIDGE',
								'hidden' => 'FPF_HIDDEN'
							],
						],
						[
							'name' => 'bordercolor',
							'type' => 'Color',
							'label' => 'FPF_COLOR',
							'default' => 'rgba(0, 0, 0, 0.4)',
							'input_class' => ['medium'],
							'showon' => '[bordertype]!:none'
						],
						[
							'name' => 'borderwidth',
							'type' => 'Slider',
							'label' => 'FPF_WIDTH',
							'number_class' => ['xsmall'],
							'default' => 1,
							'min' => 0,
							'max' => 15,
							'units' => ['px'],
							'showon' => '[bordertype]!:none'
						],
						[
							'name' => 'borderradius_control',
							'type' => 'ResponsiveControl',
							'label' => firebox()->_('FB_METABOX_BORDERRADIUS'),
							'description' => firebox()->_('FB_METABOX_BORDERRADIUS_DESC'),
							'control_inner_class' => ['fpf-max-width-300'],
							'value' => [
								'borderradius' => [
									'desktop' => [
										'top_left' => 0,
										'top_right' => 0,
										'bottom_right' => 0,
										'bottom_left' => 0
									]
								]
							],
							'fields' => [
								[
									'name' => 'borderradius',
									'type' => 'Dimensions',
									'max' => 100,
									'units' => ['px', '%'],
									'units_relative_position' => false,
									'isLinked' => true,
									'labels' => [
										'top_left' => fpframework()->_('FPF_TOP_LEFT'),
										'top_right' => fpframework()->_('FPF_TOP_RIGHT'),
										'bottom_right' => fpframework()->_('FPF_BOTTOM_RIGHT'),
										'bottom_left' => fpframework()->_('FPF_BOTTOM_LEFT')
									]
								]
							]
						],
					]
				],
				'background_overlay' => [
					'title' => [
						'title' => firebox()->_('FB_METABOX_BG_OVERLAY'),
						'description' => firebox()->_('FB_METABOX_BG_OVERLAY_DESC')
					],
					'fields' => [
						[
							'name' => 'overlay',
							'type' => 'FPToggle'
						],
						[
							'name' => 'overlay_color',
							'type' => 'Color',
							'label' => 'FPF_BG_COLOR',
							'description' => firebox()->_('FB_METABOX_OVERLAY_COLOR_DESC'),
							'default' => 'rgba(0, 0, 0, 0.5)',
							'input_class' => ['medium'],
							'showon' => '[overlay]:1'
						],
						[
							'name' => 'overlayblurradius',
							'type' => 'Slider',
							'label' => firebox()->_('FB_METABOX_OVERLAY_BLUR_RADIUS'),
							'description' => firebox()->_('FB_METABOX_OVERLAY_BLUR_RADIUS_DESC'),
							'number_class' => ['xsmall'],
							'default' => 0,
							'min' => 0,
							'max' => 100,
							'addon' => '%',
							'showon' => '[overlay]:1'
						],
						[
							'name' => 'overlayclick',
							'type' => 'FPToggle',
							'label' => firebox()->_('FB_METABOX_OVERLAY_CLICK'),
							'description' => firebox()->_('FB_METABOX_OVERLAY_CLICK_DESC'),
							'checked' => true,
							'showon' => '[overlay]:1'
						]
					]
				],
				'background_image' => [
					'title' => [
						'title' => 'FPF_BG_IMAGE',
						'description' => 'FPF_BG_IMAGE_DESC'
					],
					'fields' => [
						[
							'name' => 'bgimage',
							'type' => 'FPToggle'
						],
						[
							'name' => 'bgimagefile',
							'type' => 'MediaUploader',
							'label' => 'FPF_IMAGE',
							'description' => firebox()->_('FB_METABOX_BG_IMAGE_FILE_DESC'),
							'showon' => '[bgimage]:1'
						],
						[
							'name' => 'bgrepeat',
							'type' => 'Dropdown',
							'label' => 'FPF_REPEAT',
							'description' => firebox()->_('FB_METABOX_BGREPEAT_DESC'),
							'default' => 'Repeat',
							'choices' => [
								'No-repeat' => firebox()->_('FB_METABOX_BGREPEAT_NOREPEAT'),
								'Repeat' => 'FPF_REPEAT',
								'Repeat-x' => firebox()->_('FB_METABOX_BGREPEAT_REPEATX'),
								'Repeat-y' => firebox()->_('FB_METABOX_BGREPEAT_REPEATY')
							],
							'filter' => 'sanitize_text_field',
							'showon' => '[bgimage]:1'
						],
						[
							'name' => 'bgsize',
							'type' => 'Dropdown',
							'label' => 'FPF_SIZE',
							'description' => firebox()->_('FB_METABOX_BGSIZE_DESC'),
							'default' => 'Auto',
							'choices' => [
								'Auto' => 'FPF_AUTO',
								'Cover' => firebox()->_('FB_METABOX_BGSIZE_COVER'),
								'Contain' => firebox()->_('FB_METABOX_BGSIZE_CONTAIN'),
								'100% 100%' => '100% 100%'
							],
							'filter' => 'sanitize_text_field',
							'showon' => '[bgimage]:1'
						],
						[
							'name' => 'bgposition',
							'type' => 'Dropdown',
							'label' => 'FPF_POSITION',
							'description' => firebox()->_('FB_METABOX_BG_POSITION_DESC'),
							'default' => 'Left Top',
							'choices' => [
								'Left Top' => firebox()->_('FB_METABOX_BG_POSITION_LEFT_TOP'),
								'Left Center' => firebox()->_('FB_METABOX_BG_POSITION_LEFT_CENTER'),
								'Left Bottom' => firebox()->_('FB_METABOX_BG_POSITION_LEFT_BOTTOM'),
								'Right Top' => firebox()->_('FB_METABOX_BG_POSITION_RIGHT_TOP'),
								'Right Center' => firebox()->_('FB_METABOX_BG_POSITION_RIGHT_CENTER'),
								'Right Bottom' => firebox()->_('FB_METABOX_BG_POSITION_RIGHT_BOTTOM'),
								'Center Top' => firebox()->_('FB_METABOX_BG_POSITION_CENTER_TOP'),
								'Center Center' => firebox()->_('FB_METABOX_BG_POSITION_CENTER_CENTER'),
								'Center Bottom' => firebox()->_('FB_METABOX_BG_POSITION_CENTER_BOTTOM')
							],
							'filter' => 'sanitize_text_field',
							'showon' => '[bgimage]:1'
						]
					]
				]
			]
		];

		return apply_filters('firebox/box/settings/design/edit', $settings);
	}
}