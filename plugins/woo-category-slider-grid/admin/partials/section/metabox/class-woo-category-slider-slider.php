<?php
/**
 * Slider settings tab.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin/partials/section/metabox
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

// Cannot access directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * This class is responsible for Slider settings tab.
 *
 * @since 1.0.0
 */
class SP_WCS_Slider {
	/**
	 * Slider section.
	 *
	 * @param string $prefix slider section prefix.
	 * @return void
	 */
	public static function section( $prefix ) {

		SP_WCS::createSection(
			$prefix,
			array(
				'title'  => __( 'Slider Controls', 'woo-category-slider-grid' ),
				'icon'   => 'fa fa-sliders',
				'fields' => array(

					array(
						'id'         => 'wcsp_auto_play',
						'type'       => 'switcher',
						'title'      => __( 'AutoPlay', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Enable/Disable auto play.', 'woo-category-slider-grid' ),
						'text_on'    => __( 'Enabled', 'woo-category-slider-grid' ),
						'text_off'   => __( 'Disabled', 'woo-category-slider-grid' ),
						'text_width' => 94,
						'default'    => true,
					),
					array(
						'id'              => 'wcsp_auto_play_speed',
						'type'            => 'spacing',
						'class'           => 'wcsp-auto-play-speed',
						'title'           => __( 'AutoPlay Speed', 'woo-category-slider-grid' ),
						'subtitle'        => __( 'Set auto play speed. Default value is 3000 milliseconds.', 'woo-category-slider-grid' ),
						'all'             => true,
						'all_text'        => false,
						'all_placeholder' => 'speed',
						'min'             => 1,
						'default'         => array(
							'all' => '3000',
						),
						'units'           => array(
							esc_html__( 'ms', 'woo-category-slider-grid' ),
						),
						'dependency'      => array(
							'wcsp_auto_play',
							'==',
							'true',
						),
					),
					array(
						'id'              => 'wcsp_standard_scroll_speed',
						'type'            => 'spacing',
						'class'           => 'wcsp-standard-scroll-speed',
						'title'           => __( 'Scroll Speed', 'woo-category-slider-grid' ),
						'subtitle'        => __( 'Set pagination/slide scroll speed. Default value is 600 milliseconds.', 'woo-category-slider-grid' ),
						'all'             => true,
						'all_text'        => false,
						'all_placeholder' => 'speed',
						'min'             => 1,
						'default'         => array(
							'all' => '600',
						),
						'units'           => array(
							esc_html__( 'ms', 'woo-category-slider-grid' ),
						),
					),
					array(
						'id'         => 'wcsp_pause_on_hover',
						'type'       => 'switcher',
						'title'      => __( 'Pause on Hover', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Enable/Disable slider pause on hover.', 'woo-category-slider-grid' ),
						'text_on'    => __( 'Enabled', 'woo-category-slider-grid' ),
						'text_off'   => __( 'Disabled', 'woo-category-slider-grid' ),
						'text_width' => 94,
						'default'    => true,
						'dependency' => array(
							'wcsp_auto_play',
							'==',
							'true',
						),
					),
					array(
						'id'         => 'wcsp_infinite_loop',
						'type'       => 'switcher',
						'title'      => __( 'Infinite Loop', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Enable/Disable infinite loop mode.', 'woo-category-slider-grid' ),
						'text_on'    => __( 'Enabled', 'woo-category-slider-grid' ),
						'text_off'   => __( 'Disabled', 'woo-category-slider-grid' ),
						'text_width' => 94,
						'default'    => true,
					),
					array(
						'id'         => 'wcsp_slider_row',
						'type'       => 'column',
						'class'      => 'pro_only_field',
						'attributes' => array( 'disabled' => 'disabled' ),
						'title'      => __( 'Row', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Set slider row(s) in different devices.', 'woo-category-slider-grid' ),
						'min'        => '1',
						'max'        => '12',
						'default'    => array(
							'large_desktop' => '1',
							'desktop'       => '1',
							'laptop'        => '1',
							'tablet'        => '1',
							'mobile'        => '1',
						),
					),
					array(
						'id'       => 'wcsp_slide_to_scroll',
						'type'     => 'column',
						'title'    => __( 'Slide To Scroll', 'woo-category-slider-grid' ),
						'subtitle' => __( 'Set slide to scroll in different devices.', 'woo-category-slider-grid' ),
						'min'      => '1',
						'default'  => array(
							'large_desktop' => '1',
							'desktop'       => '1',
							'laptop'        => '1',
							'tablet'        => '1',
							'mobile'        => '1',
						),
					),

					// Navigation.
					array(
						'type'    => 'subheading',
						'content' => __( 'Navigation', 'woo-category-slider-grid' ),
					),
					array(
						'id'       => 'wcsp_navigation',
						'type'     => 'button_set',
						'title'    => __( 'Navigation', 'woo-category-slider-grid' ),
						'subtitle' => __( 'Show/Hide slider navigation.', 'woo-category-slider-grid' ),
						'options'  => array(
							'show'        => __( 'Show', 'woo-category-slider-grid' ),
							'hide'        => __( 'Hide', 'woo-category-slider-grid' ),
							'hide_mobile' => __( 'Hide on Mobile', 'woo-category-slider-grid' ),
						),
						'default'  => 'hide_mobile',
					),
					array(
						'id'         => 'wcsp_nav_colors',
						'type'       => 'color_group',
						'title'      => __( 'Navigation Color', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Set color for the slider navigation.', 'woo-category-slider-grid' ),
						'options'    => array(
							'color'            => __( 'Color', 'woo-category-slider-grid' ),
							'hover_color'      => __( 'Hover Color', 'woo-category-slider-grid' ),
							'background'       => __( 'Background', 'woo-category-slider-grid' ),
							'hover_background' => __( 'Hover Background', 'woo-category-slider-grid' ),
						),
						'default'    => array(
							'color'            => '#aaaaaa',
							'hover_color'      => '#ffffff',
							'background'       => 'transparent',
							'hover_background' => '#cc2b5e',
						),
						'dependency' => array(
							'wcsp_navigation',
							'!=',
							'hide',
						),
					),
					array(
						'id'          => 'wcsp_nav_border',
						'type'        => 'border',
						'class'       => 'wcsp-nav-border',
						'title'       => __( 'Navigation Border', 'woo-category-slider-grid' ),
						'subtitle'    => __( 'Set border for the slider navigation.', 'woo-category-slider-grid' ),
						'all'         => true,
						'hover_color' => true,
						'default'     => array(
							'all'         => '1',
							'color'       => '#aaaaaa',
							'hover_color' => '#cc2b5e',
						),
						'dependency'  => array(
							'wcsp_navigation',
							'!=',
							'hide',
						),
					),

					// Pagination.
					array(
						'type'    => 'subheading',
						'content' => __( 'Pagination', 'woo-category-slider-grid' ),
					),
					array(
						'id'       => 'wcsp_pagination',
						'type'     => 'button_set',
						'title'    => __( 'Pagination', 'woo-category-slider-grid' ),
						'subtitle' => __( 'Show/Hide slider pagination.', 'woo-category-slider-grid' ),
						'options'  => array(
							'show'        => __( 'Show', 'woo-category-slider-grid' ),
							'hide'        => __( 'Hide', 'woo-category-slider-grid' ),
							'hide_mobile' => __( 'Hide on Mobile', 'woo-category-slider-grid' ),
						),
						'default'  => 'hide_mobile',
					),
					array(
						'id'         => 'wcsp_pagination_colors',
						'type'       => 'color_group',
						'title'      => __( 'Pagination Color', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Set color for the slider pagination.', 'woo-category-slider-grid' ),
						'options'    => array(
							'color'        => __( 'Color', 'woo-category-slider-grid' ),
							'active_color' => __( 'Active Color', 'woo-category-slider-grid' ),
						),
						'default'    => array(
							'color'        => '#aaaaaa',
							'active_color' => '#cc2b5e',
						),
						'dependency' => array(
							'wcsp_pagination',
							'!=',
							'hide',
						),
					),

					// Misc.
					array(
						'type'    => 'subheading',
						'content' => __( 'Miscellaneous', 'woo-category-slider-grid' ),
					),
					array(
						'id'         => 'wcsp_touch_swipe',
						'type'       => 'switcher',
						'title'      => __( 'Touch Swipe', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Enable/Disable touch swipe.', 'woo-category-slider-grid' ),
						'text_on'    => __( 'Enabled', 'woo-category-slider-grid' ),
						'text_off'   => __( 'Disabled', 'woo-category-slider-grid' ),
						'text_width' => 94,
						'default'    => true,
					),
					array(
						'id'         => 'wcsp_slider_mouse_wheel',
						'type'       => 'switcher',
						'title'      => __( 'Mousewheel Control', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Enable/Disable mousewheel control.', 'woo-category-slider-grid' ),
						'text_on'    => __( 'Enabled', 'woo-category-slider-grid' ),
						'text_off'   => __( 'Disabled', 'woo-category-slider-grid' ),
						'text_width' => 94,
						'default'    => false,
					),
					array(
						'id'         => 'wcsp_slider_mouse_draggable',
						'type'       => 'switcher',
						'title'      => __( 'Mouse Draggable', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Enable/Disable mouse draggable.', 'woo-category-slider-grid' ),
						'text_on'    => __( 'Enabled', 'woo-category-slider-grid' ),
						'text_off'   => __( 'Disabled', 'woo-category-slider-grid' ),
						'text_width' => 94,
						'default'    => false,
					),
					array(
						'id'         => 'free_mode',
						'type'       => 'switcher',
						'title'      => __( 'Free Mode', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Enable/Disable free mode.', 'woo-category-slider-grid' ),
						'text_on'    => __( 'Enabled', 'woo-category-slider-grid' ),
						'text_off'   => __( 'Disabled', 'woo-category-slider-grid' ),
						'text_width' => 94,
						'default'    => false,
					),
					array(
						'id'         => 'wcsp_auto_height',
						'type'       => 'switcher',
						'title'      => __( 'Auto Height', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Enable/Disable auto height.', 'woo-category-slider-grid' ),
						'text_on'    => __( 'Enabled', 'woo-category-slider-grid' ),
						'text_off'   => __( 'Disabled', 'woo-category-slider-grid' ),
						'text_width' => 94,
						'default'    => true,
					),
				),
			)
		); // Slider Controls section end.
	}
}
