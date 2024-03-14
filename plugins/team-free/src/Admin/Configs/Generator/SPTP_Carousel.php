<?php
/**
 * Carousel tab.
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
 * This class is responsible for Carousel tab in Team page.
 *
 * @since      2.0.0
 */
class SPTP_Carousel {

	/**
	 * Carousel settings.
	 *
	 * @since 2.0.0
	 * @param string $prefix _sptp_generator.
	 */
	public static function section( $prefix ) {
		SPF_TEAM::createSection(
			$prefix,
			array(
				'title'  => __( 'Carousel Settings', 'team-free' ),
				'icon'   => 'fa fa-sliders',
				'fields' => array(
					array(
						'type'  => 'tabbed',
						'class' => 'sptp-carousel-tabs',
						'tabs'  => array(
							array(
								'title'  => __( 'Carousel Basics', 'team-free' ),
								'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"><g clip-path="url(#A)"><path fill-rule="evenodd" d="M1.224 1.224c-.009.009-.024.03-.024.076v13.4c0 .046.015.067.024.076s.03.024.076.024h13.4c.02-.017.019-.043.012-.082l-.012-.058V14.6 1.3c0-.046-.015-.067-.024-.076s-.03-.024-.076-.024H1.3c-.046 0-.067.015-.076.024zM0 1.3A1.28 1.28 0 0 1 1.3 0h13.3a1.28 1.28 0 0 1 1.3 1.3v13.247c.058.368-.014.734-.248 1.02-.244.299-.602.433-.952.433H1.3A1.28 1.28 0 0 1 0 14.7V1.3zm12.4 3h-.9c-.3-.7-1.1-1.2-1.9-1.2-.9 0-1.6.5-1.9 1.2H3.6c-.5 0-.9.4-.9.9s.4.9.9.9h4.1c.3.8 1 1.3 1.9 1.3s1.6-.5 1.9-1.2h.9c.5 0 .9-.4.9-.9s-.4-1-.9-1zm-7.9 7.4h-.9c-.5 0-.9-.4-.9-.9s.4-.9.9-.9h.9c.3-.8 1-1.3 1.9-1.3s1.6.5 1.9 1.3h4.1c.5 0 .9.4.9.9s-.4.9-.9.9H8.3c-.3.7-1 1.2-1.9 1.2-.8 0-1.6-.5-1.9-1.2z" fill="#000"/></g><defs><clipPath id="A"><path fill="#fff" d="M0 0h16v16H0z"/></clipPath></defs></svg></span>',
								'fields' => array(
									array(
										'id'         => 'carousel_autoplay',
										'type'       => 'switcher',
										'title'      => __( 'AutoPlay', 'team-free' ),
										'subtitle'   => __( 'Enable/Disable auto play.', 'team-free' ),
										'default'    => 'true',
										'text_on'    => __( 'Enabled', 'team-free' ),
										'text_off'   => __( 'Disabled', 'team-free' ),
										'text_width' => 95,
										'dependency' => array( 'carousel_mode', '==', 'standard', true ),
									),
									array(
										'id'         => 'carousel_autoplay_speed',
										'type'       => 'slider',
										'class'      => 'carousel_auto_play_ranger',
										'title'      => __( 'AutoPlay Delay', 'team-free' ),
										'subtitle'   => __( 'Set autoplay delay time in millisecond.', 'team-free' ),
										'unit'       => __( 'ms', 'team-free' ),
										'step'       => 100,
										'min'        => 100,
										'max'        => 50000,
										'default'    => 5000,
										'dependency' => array( 'carousel_mode|carousel_autoplay', '==|==', 'standard|true', true ),
										'title_info' => __( '<div class="spf-info-label">AutoPlay Delay Time</div> <div class="spf-short-content">Set autoplay delay or interval time. The amount of time to delay between automatically cycling a member. e.g. 1000 milliseconds(ms) = 1 second.</div>', 'team-free' ),

									),
									array(
										'id'         => 'carousel_speed',
										'type'       => 'slider',
										'class'      => 'carousel_auto_play_ranger',
										'title'      => __( 'Carousel Speed', 'team-free' ),
										'subtitle'   => __( 'Set carousel scroll speed in millisecond.', 'team-free' ),
										'unit'       => __( 'ms', 'team-free' ),
										'step'       => 100,
										'min'        => 1,
										'max'        => 20000,
										'default'    => 600,
										'dependency' => array( 'carousel_mode', '==', 'standard', true ),
										'title_info' => __( '<div class="spf-info-label">Carousel Speed</div> <div class="spf-short-content">Set carousel scrolling speed. e.g. 1000 milliseconds(ms) = 1 second.</div>', 'team-free' ),
									),
									array(
										'id'         => 'carousel_onhover',
										'type'       => 'switcher',
										'title'      => __( 'Stop on Hover', 'team-free' ),
										'subtitle'   => __( 'Enable/Disable carousel pause on hover.', 'team-free' ),
										'text_on'    => __( 'Enabled', 'team-free' ),
										'text_off'   => __( 'Disabled', 'team-free' ),
										'text_width' => 95,
										'default'    => 'true',
										'dependency' => array( 'carousel_mode|carousel_autoplay', '==|==', 'standard|true', true ),
									),
									array(
										'id'         => 'carousel_loop',
										'type'       => 'switcher',
										'title'      => __( 'Loop', 'team-free' ),
										'subtitle'   => __( 'Enable/Disable infinite loop mode.', 'team-free' ),
										'default'    => 'true',
										'text_on'    => __( 'Enabled', 'team-free' ),
										'text_off'   => __( 'Disabled', 'team-free' ),
										'text_width' => 95,
										'dependency' => array( 'carousel_mode', '==', 'standard', true ),
									),
									array(
										'id'         => 'member_per_slide',
										'type'       => 'column',
										'title'      => __( 'Member(s) Per Slide', 'team-free' ),
										'subtitle'   => __( 'Set members per slide or scroll at a time.', 'team-free' ),
										'default'    => array(
											'desktop' => '1',
											'laptop'  => '1',
											'tablet'  => '1',
											'mobile'  => '1',
										),
										'dependency' => array( 'layout_preset', '==', 'carousel', true ),
									),
								),
							),
							array(
								'title'  => __( 'Navigation', 'team-free' ),
								'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#343434" ><path d="M2.2 8l4.1-4.1a.85.85 0 0 0 0-1.3c-.4-.3-1-.3-1.3.1L.3 7.4a.85.85 0 0 0 0 1.3L5 13.3c.3.3.9.3 1.2 0a.85.85 0 0 0 0-1.3l-4-4zM11 2.7l4.7 4.7c.4.3.4.9-.1 1.3l-4.7 4.7c-.4.4-1 .2-1.2 0a.85.85 0 0 1 0-1.3L13.8 8l-4-4.1c-.4-.3-.4-.9-.1-1.2a.85.85 0 0 1 1.3 0zM6.5 6a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1h-3z"/></svg></span>',
								'fields' => array(
									array(
										'id'     => 'carousel_navigation_data',
										'class'  => 'navigation-and-pagination-style',
										'type'   => 'fieldset',
										'fields' => array(
											array(
												'id'       => 'carousel_navigation',
												'type'     => 'switcher',
												'title'    => __( 'Navigation', 'team-free' ),
												'class'    => 'sptp_navigation',
												'subtitle' => __( 'Show/hide navigation.', 'team-free' ),
												'text_on'  => __( 'Show', 'team-free' ),
												'text_off' => __( 'Hide', 'team-free' ),
												'text_width' => 77,
												'default'  => true,
												'dependency' => array( 'layout_preset|carousel_mode', '==|!=', 'carousel|ticker', true ),
											),
											array(
												'id'      => 'nav_hide_on_mobile',
												'type'    => 'checkbox',
												'class'   => 'sptp_hide_on_mobile',
												'title'   => __( 'Hide on Mobile', 'team-free' ),
												'default' => false,
												'dependency' => array( 'layout_preset|carousel_mode|carousel_navigation', '==|!=|==', 'carousel|ticker|true', true ),
											),
										),
									),
									array(
										'id'         => 'carousel_navigation_position',
										'class'      => 'carousel_navigation_position',
										'type'       => 'select',
										'preview'    => true,
										'only_pro'   => true,
										'title'      => __( 'Navigation Position', 'team-free' ),
										'options'    => array(
											'top-right'    => __( 'Top Right', 'team-free' ),
											'top-center'   => __( 'Top Center', 'team-free' ),
											'top-left'     => __( 'Top Left', 'team-free' ),
											'bottom-left'  => __( 'Bottom Left', 'team-free' ),
											'bottom-center' => __( 'Bottom Center', 'team-free' ),
											'bottom-right' => __( 'Bottom Right', 'team-free' ),
											'vertically-center-outer' => __( 'Vertical Center Outer', 'team-free' ),
											'vertically-center' => __( 'Vertical Center', 'team-free' ),
											'vertically-center-inner' => __( 'Vertical Center Inner', 'team-free' ),
										),
										'default'    => 'top-right',
										'dependency' => array( 'layout_preset|carousel_mode|carousel_navigation', '==|!=|==', 'carousel|ticker|true', true ),
										'subtitle'   => __( 'Select a position for the navigation arrows.', 'team-free' ),
									),
									array(
										'id'         => 'nev_visible_on_hover',
										'type'       => 'checkbox',
										'title'      => __( 'Visible On Hover', 'team-free' ),
										'subtitle'   => __( 'Check to show navigation on hover in the carousel or slider area.', 'team-free' ),
										'default'    => false,
										'only_pro'   => true,
										'dependency' => array( 'layout_preset|carousel_mode|carousel_navigation_position|carousel_navigation', '==|!=|any|==', 'carousel|ticker|vertically-center-outer,vertically-center-inner,vertically-center|true', true ),
									),
									array(
										'id'         => 'carousel_navigation_color',
										'type'       => 'color_group',
										'title'      => __( 'Color', 'team-free' ),
										'subtitle'   => __( 'Set color for the carousel navigation.', 'team-free' ),
										'options'    => array(
											'color'       => __( 'Color', 'team-free' ),
											'hover_color' => __( 'Hover Color', 'team-free' ),
											'bg_color'    => __( 'Background', 'team-free' ),
											'bg_hover_color' => __( 'Hover Background', 'team-free' ),
										),
										'default'    => array(
											'color'       => '#aaaaaa',
											'hover_color' => '#ffffff',
											'bg_color'    => 'transparent',
											'bg_hover_color' => '#63a37b',
										),
										'dependency' => array( 'carousel_navigation|carousel_mode', '==|==', 'true|standard', true ),
									),
									array(
										'id'         => 'carousel_navigation_border',
										'type'       => 'border',
										'title'      => __( 'Border', 'team-free' ),
										'subtitle'   => __( 'Set border for the carousel navigation.', 'team-free' ),
										'all'        => true,
										'default'    => array(
											'all'         => 1,
											'style'       => 'solid',
											'color'       => '#aaaaaa',
											'hover_color' => '#63a37b',
											'unit'        => 'px',
										),
										'dependency' => array( 'carousel_navigation|carousel_mode', '==|==', 'true|standard', true ),
									),
									array(
										'type'    => 'notice',
										'content' => __( 'Want even more fine-tuned control over your team carousel navigation display?</b> <a href="https://getwpteam.com/pricing/?ref=1" target="_blank"><b>Upgrade to Pro!</b></a>', 'team-free' ),
									),
								),
							),
							array(
								'title'  => __( 'Pagination', 'team-free' ),
								'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" ><g clip-path="url(#A)" fill="#343434"><path d="M5.2 10.2a2.2 2.2 0 1 0 0-4.4 2.2 2.2 0 1 0 0 4.4zm6.2-.5a1.7 1.7 0 0 0 0-3.4 1.7 1.7 0 0 0 0 3.4z"/><path fill-rule="evenodd" d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-.5h12a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5V4a.5.5 0 0 1 .5-.5z"/></g><defs><clipPath id="A"><path fill="#fff" d="M0 0h16v16H0z"/></clipPath></defs></svg></span>',
								'fields' => array(
									array(
										'id'     => 'carousel_pagination_group',
										'class'  => 'navigation-and-pagination-style',
										'type'   => 'fieldset',
										'fields' => array(
											array(
												'id'       => 'carousel_pagination',
												'type'     => 'switcher',
												'title'    => __( 'Pagination', 'team-free' ),
												'class'    => 'sptp_pagination',
												'subtitle' => __( 'Show/hide navigation.', 'team-free' ),
												'text_on'  => __( 'Show', 'team-free' ),
												'text_off' => __( 'Hide', 'team-free' ),
												'text_width' => 77,
												'default'  => true,
												'dependency' => array( 'layout_preset|carousel_mode', '==|!=', 'carousel|ticker', true ),
											),
											array(
												'id'      => 'pagination_hide_on_mobile',
												'type'    => 'checkbox',
												'class'   => 'sptp_hide_on_mobile',
												'title'   => __( 'Hide on Mobile', 'team-free' ),
												'default' => false,
												'dependency' => array( 'layout_preset|carousel_mode|carousel_pagination', '==|!=|==', 'carousel|ticker|true', true ),
											),
										),
									),
									array(
										'id'         => 'carousel_pagination_type',
										'type'       => 'image_select',
										'class'      => 'hide-active-sign',
										'title'      => __( 'Pagination Style', 'team-free' ),
										'subtitle'   => __( 'Select a style for pagination.', 'team-free' ),
										'only_pro'   => true,
										'options'    => array(
											'bullets'   => array(
												'image' => SPT_PLUGIN_ROOT . 'src/Admin/img/pagination-types/bullets.svg',
												'option_name' => __( 'Bullets', 'team-free' ),
											),
											'dynamic'   => array(
												'image'    => SPT_PLUGIN_ROOT . 'src/Admin/img/pagination-types/dynamic.svg',
												'option_name' => __( 'Dynamic', 'team-free' ),
												'pro_only' => true,
											),
											'strokes'   => array(
												'image'    => SPT_PLUGIN_ROOT . 'src/Admin/img/pagination-types/strokes.svg',
												'option_name' => __( 'Strokes', 'team-free' ),
												'pro_only' => true,
											),
											'scrollbar' => array(
												'image'    => SPT_PLUGIN_ROOT . 'src/Admin/img/pagination-types/scrollbar.svg',
												'option_name' => __( 'Scrollbar', 'team-free' ),
												'pro_only' => true,
											),
											'fraction'  => array(
												'image'    => SPT_PLUGIN_ROOT . 'src/Admin/img/pagination-types/fraction.svg',
												'option_name' => __( 'Fraction', 'team-free' ),
												'pro_only' => true,
											),
											'numbers'   => array(
												'image'    => SPT_PLUGIN_ROOT . 'src/Admin/img/pagination-types/numbers.svg',
												'option_name' => __( 'Numbers', 'team-free' ),
												'pro_only' => true,
											),
										),
										'default'    => 'bullets',
										'dependency' => array( 'carousel_pagination|carousel_mode', '==|!=', 'true|ticker', true ),
									),
									array(
										'id'         => 'carousel_pagination_color',
										'type'       => 'color_group',
										'title'      => __( 'Color', 'team-free' ),
										'subtitle'   => __( 'Set color for the pagination dots.', 'team-free' ),
										'options'    => array(
											'color'        => __( 'Color', 'team-free' ),
											'active_color' => __( 'Active Color', 'team-free' ),
										),
										'default'    => array(
											'color'        => '#aaaaaa',
											'active_color' => '#63a37b',
										),
										'dependency' => array( 'carousel_pagination|carousel_mode', '==|==', 'true|standard', true ),
									),
									array(
										'type'    => 'notice',
										'content' => __( 'Want even more fine-tuned control over your team carousel pagination display? <a href="https://getwpteam.com/pricing/?ref=1" target="_blank"><b>Upgrade to Pro!</b></a>', 'team-free' ),
									),
								),
							),
							array(
								'title'  => __( 'Miscellaneous', 'team-free' ),
								'icon'   => '<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"><g clip-path="url(#A)" fill="#343434"><path d="M12.4 3.9h-6c-.4 0-.8.4-.8.8s.4.8.8.8h6c.4 0 .8-.3.8-.8 0-.4-.3-.8-.8-.8zm0 3.3h-6c-.4 0-.8.4-.8.8s.4.8.8.8h6c.4 0 .8-.3.8-.8 0-.4-.3-.8-.8-.8zm-6 3.2h6c.5 0 .8.4.8.8 0 .5-.4.8-.8.8h-6c-.4 0-.8-.4-.8-.8s.4-.8.8-.8zM4.9 4.8a.94.94 0 0 1-1 1c-.5 0-1-.4-1-1a.94.94 0 0 1 1-1 .94.94 0 0 1 1 1zM3.9 9a.94.94 0 0 0 1-1 .94.94 0 0 0-1-1 .94.94 0 0 0-1 1c0 .6.5 1 1 1zm1 2.2a.94.94 0 0 1-1 1c-.5 0-1-.4-1-1a.94.94 0 0 1 1-1 .94.94 0 0 1 1 1z"/><path fill-rule="evenodd" d="M13.2 0H2.9C1.3 0 0 1.3 0 2.9v10.2C0 14.7 1.3 16 2.9 16h10.2c1.6 0 2.9-1.3 2.9-2.8V2.9C16 1.3 14.7 0 13.2 0zm1.4 13.2c0 .8-.6 1.4-1.4 1.4H2.9c-.8 0-1.4-.6-1.4-1.4V2.9c0-.8.6-1.4 1.4-1.4h10.3c.8 0 1.4.6 1.4 1.4v10.3z"/></g><defs><clipPath id="A"><path fill="#fff" d="M0 0h16v16H0z"/></clipPath></defs></svg></span>',
								'fields' => array(
									array(
										'id'         => 'carousel_auto_height',
										'type'       => 'switcher',
										'title'      => __( 'Auto Height', 'team-free' ),
										'subtitle'   => __( 'Enable/Disable auto height for the carousel.', 'team-free' ),
										'text_on'    => __( 'Enabled', 'team-free' ),
										'text_off'   => __( 'Disabled', 'team-free' ),
										'text_width' => 95,
										'default'    => 'true',
										'dependency' => array( 'carousel_mode', '==', 'standard', true ),
									),
									array(
										'id'         => 'touch_swipe',
										'type'       => 'switcher',
										'title'      => __( 'Touch Swipe', 'team-free' ),
										'subtitle'   => __( 'Enable/Disable touch swipe mode.', 'team-free' ),
										'text_on'    => __( 'Enabled', 'team-free' ),
										'text_off'   => __( 'Disabled', 'team-free' ),
										'text_width' => 100,
										'default'    => 'true',
										'dependency' => array( 'carousel_mode', '==', 'standard', true ),
									),
									array(
										'id'         => 'slider_draggable',
										'type'       => 'switcher',
										'title'      => __( 'Mouse Draggable', 'team-free' ),
										'subtitle'   => __( 'Enable/Disable mouse draggable mode.', 'team-free' ),
										'text_on'    => __( 'Enabled', 'team-free' ),
										'text_off'   => __( 'Disabled', 'team-free' ),
										'text_width' => 100,
										'default'    => 'true',
										'dependency' => array( 'carousel_mode', '==', 'standard', true ),
									),
									array(
										'id'         => 'free_mode',
										'type'       => 'switcher',
										'title'      => __( 'Free Mode', 'team-free' ),
										'subtitle'   => __( 'Enable/Disable free mode.', 'team-free' ),
										'text_on'    => __( 'Enabled', 'team-free' ),
										'text_off'   => __( 'Disabled', 'team-free' ),
										'text_width' => 100,
										'default'    => false,
										'dependency' => array( 'carousel_mode', '==', 'standard', true ),
									),
									array(
										'id'         => 'slider_mouse_wheel',
										'type'       => 'switcher',
										'title'      => __( 'Mousewheel Control', 'team-free' ),
										'subtitle'   => __( 'Enable/Disable mousewheel control.', 'team-free' ),
										'text_on'    => __( 'Enabled', 'team-free' ),
										'text_off'   => __( 'Disabled', 'team-free' ),
										'text_width' => 100,
										'default'    => false,
										'dependency' => array( 'carousel_mode', '==', 'standard', true ),
									),
								),
							),
						),
					),
				),
			)
		);
	}
}
