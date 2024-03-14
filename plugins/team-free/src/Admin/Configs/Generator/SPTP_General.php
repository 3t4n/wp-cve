<?php
/**
 * General tab.
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
 * This class is responsible for General tab in Team page.
 *
 * @since      2.0.0
 */
class SPTP_General {

	/**
	 * General settings.
	 *
	 * @since 2.0.0
	 * @param string $prefix _sptp_generator.
	 */
	public static function section( $prefix ) {
		SPF_TEAM::createSection(
			$prefix,
			array(
				'title'  => __( 'General Settings', 'team-free' ),
				'icon'   => 'fa fa-gear',
				'fields' => array(
					array(
						'id'         => 'responsive_columns',
						'class'      => 'sptp_responsive_columns',
						'type'       => 'column',
						'title'      => __( 'Column(s)', 'team-free' ),
						'subtitle'   => __( 'Set number of column(s) in different responsive devices.', 'team-free' ),
						'dependency' => array( 'layout_preset', '!=', 'list', true ),
						'default'    => array(
							'desktop' => '4',
							'laptop'  => '3',
							'tablet'  => '2',
							'mobile'  => '1',
						),
						'title_info' => '<i class="fa fa-desktop"></i> <strong>DESKTOP</strong> - Screens larger than 1024px.<br/>
						<i class="fa fa-laptop"></i> <strong>LAPTOP</strong> - Screens smaller than 1024px.<br/>
						<i class="fa fa-tablet"></i> <strong>TABLET</strong> - Screens smaller than 768px.<br/>
						<i class="fa fa-mobile"></i> <strong>MOBILE</strong> - Screens smaller than 414px.<br/>',
					),
					array(
						'id'         => 'responsive_columns_list',
						'class'      => 'sptp_responsive_columns_list',
						'type'       => 'column',
						'title'      => __( 'Column(s)', 'team-free' ),
						'subtitle'   => __( 'Set number of column(s) in different responsive devices.', 'team-free' ),
						'dependency' => array( 'layout_preset', '==', 'list', true ),
						'default'    => array(
							'desktop' => '1',
							'laptop'  => '1',
							'tablet'  => '1',
							'mobile'  => '1',
						),
						'title_info'       => '<i class="fa fa-desktop"></i> DESKTOP - Screens larger than 1024px.<br/>
						<i class="fa fa-laptop"></i> LAPTOP - Screens smaller than 1024px.<br/>
						<i class="fa fa-tablet"></i> TABLET - Screens smaller than 768px.<br/>
						<i class="fa fa-mobile"></i> MOBILE - Screens smaller than 414px.<br/>',
					),
					array(
						'id'          => 'style_margin_between_member',
						'class'       => 'sptp_style_margin_between_member',
						'type'        => 'spacing',
						'title'       => __( 'Space', 'team-free' ),
						'subtitle'    => __( 'Set a space or margin between members.', 'team-free' ),
						'gap_between' => true,
						'units'       => array( 'px' ),
						'all_icon'    => '<i class="fa fa-arrows"></i>',
						'default'     => array(
							'top-bottom' => 24,
							'left-right' => 24,
						),
						'title_info'  => '<div class="spf-img-tag"><img src="' . SPT_PLUGIN_ROOT . 'src/Admin/img/visual/space.svg" alt="Space Between"></div><div class="spf-info-label img">' . __( 'Space Between', 'team-free' ) . '</div>',
					),
					array(
						'id'       => 'total_member_display',
						'class'    => 'sptp_total_member_display',
						'type'     => 'spinner',
						'title'    => __( 'Limit', 'team-free' ),
						'default'  => '12',
						'subtitle' => __( 'Number of total members to display.  For all leave it empty.', 'team-free' ),
						'min'      => 1,
					),
					array(
						'id'       => 'order_by',
						'type'     => 'select',
						'title'    => __( 'Order By', 'team-free' ),
						'options'  => array(
							'title'    => __( 'Name', 'team-free' ),
							'id'       => __( 'ID', 'team-free' ),
							'date'     => __( 'Date', 'team-free' ),
							'rand'     => __( 'Random', 'team-free' ),
							'modified' => __( 'Modified', 'team-free' ),
						),
						'default'  => 'date',
						'subtitle' => __( 'Select an order by option.', 'team-free' ),
					),
					array(
						'id'       => 'order',
						'type'     => 'select',
						'title'    => __( 'Order', 'team-free' ),
						'options'  => array(
							'ASC'  => __( 'Ascending', 'team-free' ),
							'DESC' => __( 'Descending', 'team-free' ),
						),
						'default'  => 'DESC',
						'subtitle' => __( 'Select an order option.', 'team-free' ),
					),
					array(
						'id'         => 'member_search',
						'class'      => 'sptp_pro_only_field',
						'type'       => 'switcher',
						'title'      => __( 'Ajax Member Search', 'team-free' ),
						'subtitle'   => __( 'Enable/Disable ajax search for member.', 'team-free' ),
						'text_on'    => __( 'Enabled', 'team-free' ),
						'text_off'   => __( 'Disabled', 'team-free' ),
						'default'    => false,
						'text_width' => 100,
						'dependency' => array( 'layout_preset', '!=', 'thumbnail-pager', true ),
					),
					array(
						'id'         => 'preloader_switch',
						'type'       => 'switcher',
						'title'      => __( 'Preloader', 'team-free' ),
						'subtitle'   => __(
							'Team members will be hidden until page load completed.
						',
							'team-free'
						),
						'text_on'    => __( 'Enabled', 'team-free' ),
						'text_off'   => __( 'Disabled', 'team-free' ),
						'text_width' => 100,
						'default'    => true,
					),
					array(
						'id'         => 'member_live_filter',
						'class'      => 'member_live_filter sptp_pro_only_field',
						'type'       => 'switcher',
						'title'      => __( 'Ajax Live Filters', 'team-free' ),
						'subtitle'   => __( 'Enable/Disable ajax live filtering for member groups.', 'team-free' ),
						'text_on'    => __( 'Enabled', 'team-free' ),
						'text_off'   => __( 'Disabled', 'team-free' ),
						'default'    => false,
						'text_width' => 100,
						'title_info' => __( '<div class="spf-info-label">Ajax Member Live Filters</div> <div class="spf-short-content">Make your visitor\'s member search easier by enabling the Ajax live filter. This powerful feature allows effortless navigation through member categories,they can easily find exactly what they\'re looking for.</div><div class="info-button"><a class="spf-open-docs" href="https://getwpteam.com/docs/how-to-enable-ajax-live-filter-and-member-search/" target="_blank">Open Docs</a><a class="spf-open-live-demo" href="https://getwpteam.com/advanced-ajax-live-filtering-and-ajax-member-search/" target="_blank">Live Demo</a></div>', 'team-free' ),
						'dependency' => array( 'layout_preset', 'not-any', 'filter,thumbnail-pager', true ),
					),
					array(
						'type'       => 'subheading',
						'content'    => __( 'Ajax Pagination', 'team-free' ),
						'dependency' => array( 'layout_preset', 'not-any', 'thumbnail-pager,carousel', true ),
					),
					array(
						'type'       => 'notice',
						'content'    => __( 'Want to unleash the power of Ajax Pagination and take your team page to the next level?</b> <a href="https://getwpteam.com/pricing/?ref=1" target="_blank"><b>Upgrade to Pro!</b></a>', 'team-free' ),
						'dependency' => array( 'layout_preset', 'not-any', 'thumbnail-pager,filter,carousel', true ),
					),
					array(
						'id'         => 'pagination_fields',
						'type'       => 'fieldset',
						'class'      => 'sptp-pagination-group sptp_pro_only_field',
						'dependency' => array( 'layout_preset', 'not-any', 'thumbnail-pager,filter,carousel', true ),
						'fields'     => array(
							array(
								'id'         => 'pagination_universal',
								'type'       => 'switcher',
								'title'      => __( 'Pagination', 'team-free' ),
								'subtitle'   => __( 'Enabled/Disabled pagination', 'team-free' ),
								'text_on'    => __( 'Enabled', 'team-free' ),
								'text_off'   => __( 'Disabled', 'team-free' ),
								'text_width' => 100,
								'default'    => false,
								'class'      => 'sptp-pagination',
							),
							array(
								'id'       => 'universal_pagination_type',
								'type'     => 'radio',
								'class'    => 'sptp_pro_field',
								'title'    => __( 'Pagination Type', 'team-free' ),
								'subtitle' => __( 'Choose a pagination type.', 'team-free' ),
								'options'  => array(
									'pagination_normal' => __( 'Normal Pagination', 'team-free' ),
									'pagination_number' => __( 'Ajax Number Pagination', 'team-free' ),
									'pagination_btn'    => __( 'Load More Button (Ajax)', 'team-free' ),
									'pagination_scrl'   => __( 'Load More on Scroll (Ajax)', 'team-free' ),
								),
								'default'  => 'pagination_normal',
							),
							array(
								'id'       => 'pagination_show_per_page',
								'type'     => 'spinner',
								'title'    => __( 'Member(s) To Show Per Page', 'team-free' ),
								'subtitle' => __( 'Set number of member(s) to show in per page.', 'team-free' ),
								'default'  => 8,
							),
							array(
								'id'       => 'pagination_show_per_click',
								'type'     => 'spinner',
								'title'    => __( 'Member(s) To Load Per Click', 'team-free' ),
								'subtitle' => __( 'Set number of member(s) to load in per click.', 'team-free' ),
								'default'  => 8,
							),
							array(
								'id'         => 'load_more_label',
								'type'       => 'text',
								'title'      => __( 'Load more button label', 'team-free' ),
								'default'    => __( 'Load More', 'team-free' ),
								'dependency' => array( 'pagination_universal|universal_pagination_type', '==|==', 'true|pagination_btn' ),
							),
							array(
								'id'         => 'scroll_load_more_label',
								'type'       => 'text',
								'title'      => __( 'Scroll Load more button label', 'team-free' ),
								'default'    => __( 'Scroll to Load More', 'team-free' ),
								'dependency' => array( 'pagination_universal|universal_pagination_type', '==|==', 'true|pagination_scrl' ),
							),
							array(
								'id'       => 'pagination_color',
								'class'    => 'pagination_color',
								'type'     => 'color_group',
								'title'    => __( 'Pagination Color', 'team-free' ),
								'subtitle' => __( 'Set pagination color.', 'team-free' ),
								'options'  => array(
									'color'       => __( 'Color', 'team-free' ),
									'hover_color' => __( 'Hover Color', 'team-free' ),
									'bg'          => __( 'Background', 'team-free' ),
									'hover_bg'    => __( 'Hover Background', 'team-free' ),
								),
								'default'  => array(
									'color'       => '#5e5e5e',
									'hover_color' => '#ffffff',
									'bg'          => '#ffffff',
									'hover_bg'    => '#63a37b',
								),
							),
							// New option.
							array(
								'id'            => 'pagination_border',
								'type'          => 'border',
								'title'         => __( 'Border', 'team-free' ),
								'subtitle'      => __( 'Set border for the pagination button.', 'team-free' ),
								'all'           => true,
								'border_radius' => true,
								'default'       => array(
									'all'           => '2',
									'style'         => 'solid',
									'unit'          => 'px',
									'color'         => '#dddddd',
									'hover_color'   => '#559173',
									'border_radius' => '2',
								),
							),
							// New option.
							array(
								'id'      => 'load_more_end_label',
								'type'    => 'text',
								'title'   => __( 'End of Members Button Label', 'team-free' ),
								'default' => __( 'End of Members', 'team-free' ),
							),
							array(
								'id'       => 'pagination_alignment',
								'type'     => 'button_set',
								'title'    => __( 'Alignment', 'team-free' ),
								'subtitle' => __( 'Choose pagination field alignment.', 'team-free' ),
								'options'  => array(
									'left'   => '<i class="fa fa-align-left" title="Left"></i>',
									'center' => '<i class="fa fa-align-center" title="Center"></i>',
									'right'  => '<i class="fa fa-align-right" title="Right"></i>',
								),
								'default'  => 'center',
							),
						),
					), // End of the Pagination Settings Fieldset.
				),
			)
		);

	}
}
