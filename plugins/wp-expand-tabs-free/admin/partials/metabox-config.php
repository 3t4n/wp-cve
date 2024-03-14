<?php
/**
 * Metabox config file.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access pages directly.

//
// Metabox of the content source settings section.
// Set a unique slug-like ID.
//
$sptpro_content_source_settings = 'sp_tab_source_options';

/**
 * Preview metabox.
 *
 * @param string $prefix The metabox main Key.
 * @return void
 */
SP_WP_TABS::createMetabox(
	'sp_tab_live_preview',
	array(
		'title'        => __( 'Live Preview', 'wp-expand-tabs-free' ),
		'post_type'    => 'sp_wp_tabs',
		'show_restore' => false,
		'context'      => 'normal',
	)
);
SP_WP_TABS::createSection(
	'sp_tab_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

//
// Create a metabox for content source settings.
//
SP_WP_TABS::createMetabox(
	$sptpro_content_source_settings,
	array(
		'title'     => __( 'WP Tabs', 'wp-expand-tabs-free' ),
		'post_type' => 'sp_wp_tabs',
		'context'   => 'normal',
	)
);

//
// Create a section for content source settings.
//
SP_WP_TABS::createSection(
	$sptpro_content_source_settings,
	array(
		'fields' => array(
			array(
				'type'  => 'heading',
				'image' => plugin_dir_url( __DIR__ ) . 'partials/img/wp-tabs-logo.svg',
				'after' => '<i class="fa fa-life-ring"></i> Support',
				'link'  => 'https://shapedplugin.com/support/?user=lite',
				'class' => 'sp-tab__admin-header',
			),
			array(
				'id'      => 'sptpro_tab_type',
				'type'    => 'button_set',
				'title'   => __( 'Tabs Type', 'wp-expand-tabs-free' ),
				'class'   => 'sp_wp_tab_type',
				'options' => array(
					'content-tabs' => __( 'Content', 'wp-expand-tabs-free' ),
					'post-tabs'    => __( 'Post', 'wp-expand-tabs-free' ),
				),
				'default' => 'content-tabs',
			),
			// Content Tabs.
			array(
				'id'                     => 'sptpro_content_source',
				'type'                   => 'group',
				'title'                  => __( 'Tabs Content', 'wp-expand-tabs-free' ),
				'button_title'           => __( 'Add New Tab', 'wp-expand-tabs-free' ),
				'class'                  => 'sp-tab__content_wrapper',
				'accordion_title_prefix' => __( 'Tab :', 'wp-expand-tabs-free' ),
				'accordion_title_number' => true,
				'accordion_title_auto'   => true,
				'fields'                 => array(
					array(
						'id'         => 'tabs_content_title',
						'class'      => 'tabs_content_title',
						'type'       => 'text',
						'wrap_class' => 'sp-tab__content_source',
						'title'      => __( 'Title', 'wp-expand-tabs-free' ),
					),
					array(
						'id'         => 'tabs_content_subtitle',
						'type'       => 'text',
						'class'      => 'tabs_content_subtitle',
						'class'      => 'tabs-custom-text-pro',
						'wrap_class' => 'sp-tab__content_source',
						'title'      => __( 'Subtitle (Pro)', 'wp-expand-tabs-free' ),
					),
					array(
						'id'           => 'tabs_content_icon',
						'type'         => 'media',
						'class'        => 'tabs-custom-icon-pro',
						'library'      => 'image',
						'url'          => false,
						'button_title' => __( 'Font Icon', 'wp-expand-tabs-free' ),
					),
					array(
						'type'    => 'content',
						'content' => __( 'Or', 'wp-expand-tabs-free' ),
					),
					array(
						'id'           => 'tabs_custom_icon',
						'type'         => 'media',
						'library'      => 'image',
						'url'          => false,
						'class'        => 'tabs-custom-icon-pro',
						'button_title' => __( 'Custom Icon', 'wp-expand-tabs-free' ),
					),
					array(
						'id'         => 'tabs_linking',
						'type'       => 'checkbox',
						'wrap_class' => 'sp-tab__content_source',
						'title'      => __( 'Make it Deep-Linking', 'wp-expand-tabs-free' ),
						'title_help' => __( '<div class="wptabspro-info-label">Make it Deep-Linking (Pro)</div><div class="wptabspro-short-content">Check to enable the ability to associate a direct link or URL with a specific tab</div><a class="wptabspro-open-docs" href="https://docs.shapedplugin.com/docs/wp-tabs-pro/configurations/how-to-deep-linking-a-tab/" target="_blank">Open Docs</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/deep-linking/" target="_blank">Live Demo</a>', 'wp-expand-tabs-free' ),
						'default'    => false,
						'class'      => 'sp-tab__tab-linking',
					),
					array(
						'id'         => 'tabs_content_description',
						'type'       => 'wp_editor',
						'wrap_class' => 'sp-tab__content_source',
						'title'      => __( 'Description', 'wp-expand-tabs-free' ),
						'height'     => '150px',
					),
				),
			), // End of Content Tabs.

		), // End of fields array.
	)
);

//
// Metabox for the tabs.
// Set a unique slug-like ID.
//
$sptpro_shortcode_settings = 'sp_tab_shortcode_options';

//
// Create a metabox.
//
SP_WP_TABS::createMetabox(
	$sptpro_shortcode_settings,
	array(
		'title'     => __( 'Shortcode Section', 'wp-expand-tabs-free' ),
		'post_type' => 'sp_wp_tabs',
		'context'   => 'normal',
		'theme'     => 'light',
	)
);

//
// Create a section for tabs settings.
//
SP_WP_TABS::createSection(
	$sptpro_shortcode_settings,
	array(
		'title'  => __( 'Tabs Settings', 'wp-expand-tabs-free' ),
		'icon'   => 'fa fa-cog',
		'fields' => array(
			array(
				'id'         => 'sptpro_tabs_layout',
				'type'       => 'image_select',
				'class'      => 'sp_wp_tabs_layout',
				'title'      => __( 'Tabs Layout', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Choose a tabs layout.', 'wp-expand-tabs-free' ),
				'title_help' => __( '<div class="wptabspro-info-label">Tabs Layout</div><div class="wptabspro-short-content">Choose a layout from five individual layout styles to customize how your tabs are displayed in the frontend.</div><a class="wptabspro-open-docs" href="https://docs.shapedplugin.com/docs/wp-tabs-pro/configurations/how-to-choose-tabs-layout/" target="_blank">Open Docs</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-layout/" target="_blank">Live Demo</a>', 'wp-expand-tabs-free' ),
				'desc'       => __( 'To unlock Horizontal Bottom, Vertical (Left & Right), Tabs Carousel, and more settings, <a href="https://wptabs.com/pricing/?ref=1"  target="_blank"><b>Upgrade To Pro!</b></a>', 'wp-expand-tabs-free' ),
				'options'    => array(
					'horizontal'        => array(
						'image'       => WP_TABS_URL . '/admin/img/tabs-layout/horizontal-top.svg',
						'option_name' => __( 'Horizontal', 'wp-expand-tabs-free' ),
					),
					'horizontal-bottom' => array(
						'image'       => WP_TABS_URL . '/admin/img/tabs-layout/horizontal-bottom.svg',
						'option_name' => __( 'Horizontal Bottom', 'wp-expand-tabs-free' ),
					),
					'vertical'          => array(
						'image'       => WP_TABS_URL . '/admin/img/tabs-layout/vertical-left.svg',
						'option_name' => __( 'Vertical Left', 'wp-expand-tabs-free' ),
					),
					'vertical-right'    => array(
						'image'       => WP_TABS_URL . '/admin/img/tabs-layout/vertical-right.svg',
						'option_name' => __( 'Vertical Right', 'wp-expand-tabs-free' ),
					),
					'tabs-carousel'     => array(
						'image'       => WP_TABS_URL . '/admin/img/tabs-layout/tabs-carousel.svg',
						'option_name' => __( 'Tabs Carousel', 'wp-expand-tabs-free' ),
					),
				),
				'radio'      => true,
				'default'    => 'horizontal',

			),

			array(
				'id'         => 'sptpro_tabs_horizontal_alignment',
				'type'       => 'image_select',
				'class'      => 'sptpro_tabs_horizontal_alignment',
				'title'      => __( 'Tabs Alignment', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Select an alignment for tabs.', 'wp-expand-tabs-free' ),
				'title_help' => __( '<div class="wptabspro-info-label">Tabs Alignment</div><div class="wptabspro-short-content">Choose where you want your tabs to appear – at the top, right, bottom, or left of your content, allowing you to customize their position to best suit your layout and design.</div><a class="wptabspro-open-docs" href="https://docs.shapedplugin.com/docs/wp-tabs-pro/configurations/how-to-select-tabs-alignment/" target="_blank">Open Docs</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-position-alignment/" target="_blank">Live Demo</a>', 'wp-expand-tabs-free' ),
				'options'    => array(
					'tab-horizontal-alignment-left'      => array(
						'image'       => WP_TABS_URL . '/admin/img/tabs-alignment/horizontal-top/horizontal-left.svg',
						'option_name' => __( 'Left', 'wp-expand-tabs-free' ),
					),
					'tab-horizontal-alignment-right'     => array(
						'image'       => WP_TABS_URL . '/admin/img/tabs-alignment/horizontal-top/horizontal-right.svg',
						'option_name' => __( 'Right', 'wp-expand-tabs-free' ),
					),
					'tab-horizontal-alignment-center'    => array(
						'image'       => WP_TABS_URL . '/admin/img/tabs-alignment/horizontal-top/horizontal-center.svg',
						'option_name' => __( 'Center', 'wp-expand-tabs-free' ),
					),
					'tab-horizontal-alignment-justified' => array(
						'image'       => WP_TABS_URL . '/admin/img/tabs-alignment/horizontal-top/horizontal-justified.svg',
						'option_name' => __( 'Justified', 'wp-expand-tabs-free' ),
					),
				),
				'default'    => 'tab-horizontal-alignment-left',
			),
			array(
				'id'         => 'sptpro_tabs_activator_event',
				'type'       => 'radio',
				'class'      => 'only_for_pro_event',
				'title'      => __( 'Activator Event', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Select an activator event for tabs.', 'wp-expand-tabs-free' ),
				'title_help' => __( '<div class="wptabspro-info-label">Activator Event</div><div class="wptabspro-short-content">Set an event to switch between tabs with Autoplay, On Click, or Mouse Hover.</div><a class="wptabspro-open-docs" href="https://docs.shapedplugin.com/docs/wp-tabs-pro/configurations/how-to-set-tabs-activator-events/" target="_blank">Open Docs</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/activator-events/" target="_blank">Live Demo</a>', 'wp-expand-tabs-free' ),
				'options'    => array(
					'tabs-activator-event-click' => __( 'On Click', 'wp-expand-tabs-free' ),
					'tabs-activator-event-hover' => __( 'Mouseover', 'wp-expand-tabs-free' ),
					'tabs-activator-event-auto'  => __( 'AutoPlay (Pro)', 'wp-expand-tabs-free' ),
				),
				'default'    => 'tabs-activator-event-click',
			),
			array(
				'id'         => 'sptpro_tab_opened',
				'type'       => 'spinner',
				'class'      => 'only_pro_spinner',
				'title'      => __( 'Initial Open Tab', 'wp-expand-tabs-free' ),
				'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/initial-tab-opened.svg" alt="Initial Open Tab"></div><div class="wptabspro-info-label">' . __( 'Initial Open Tab', 'wp-expand-tabs-free' ) . '</div>',
				'subtitle'   => __( 'The tab which will remain initially opened on page load.', 'wp-expand-tabs-free' ),
				'min'        => 1,
				'default'    => 1,
			),
			array(
				'id'         => 'sptpro_preloader',
				'type'       => 'switcher',
				'title'      => __( 'Preloader', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Tabs will be hidden until page load completed.', 'wp-expand-tabs-free' ),
				'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
				'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
				'text_width' => 94,
				'default'    => true,
			),
		), // Fields array end.
	)
); // End of tabs settings.

//
// Carousel settings section begin.
//
SP_WP_TABS::createSection(
	$sptpro_shortcode_settings,
	array(
		'title'  => __( 'Display Options', 'wp-expand-tabs-free' ),
		'icon'   => 'fa fa-th-large',
		'fields' => array(
			array(
				'id'         => 'sptpro_section_title',
				'type'       => 'switcher',
				'title'      => __( 'Tabs Set Section Title', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Show/hide tabs set section title.', 'wp-expand-tabs-free' ),
				'default'    => true,
				'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
				'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
				'text_width' => 75,
			),
			array(
				'id'              => 'sptpro_margin_between_tabs',
				'type'            => 'spacing',
				'title'           => __( 'Margin Between Tabs', 'wp-expand-tabs-free' ),
				'subtitle'        => __( 'Set a space between tabs.', 'wp-expand-tabs-free' ),
				'title_help'      => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/margin-between-tabs.svg" alt="Margin Between Tabs"></div><div class="wptabspro-info-label">' . __( 'Margin between Tabs', 'wp-expand-tabs-free' ) . '</div>',
				'all'             => true,
				'all_icon'        => '<i class="fa fa-arrows-h"></i>',
				'all_placeholder' => 'margin',
				'default'         => array(
					'all' => '10',
				),
				'units'           => array(
					'px',
				),
			),

			array(
				'type'    => 'subheading',
				'content' => __( 'Tabs Icon', 'wp-expand-tabs-free' ),
			),
			array(
				'type'    => 'notice',
				'class'   => 'only_pro_notice',
				'content' => __( 'To unlock the following essential Tabs Icon options, <a href="https://wptabs.com/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a>', 'wp-expand-tabs-free' ),
			),
			array(
				'id'         => 'sptpro_tabs_icon',
				'type'       => 'switcher',
				'class'      => 'only_pro_switcher',
				'title'      => __( 'Tabs Icon', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Show/hide the tabs icon.', 'wp-expand-tabs-free' ),
				'default'    => true,
				'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
				'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
				'text_width' => 75,
			),
			array(
				'id'              => 'sptpro_tab_icon_size',
				'type'            => 'spacing',
				'class'           => 'only_pro_spinner',
				'title'           => __( 'Icon Size', 'wp-expand-tabs-free' ),
				'subtitle'        => __( 'Set tabs icon size.', 'wp-expand-tabs-free' ),
				'all'             => true,
				'all_text'        => false,
				'all_placeholder' => 'size',
				'default'         => array(
					'all' => '16',
				),
				'units'           => array(
					'px',
				),
				'dependency'      => array(
					'sptpro_tabs_icon',
					'==',
					'true',
				),
			),
			array(
				'id'              => 'sptpro_icon_space_title',
				'type'            => 'spacing',
				'class'           => 'only_pro_spinner',
				'title'           => __( 'Space Between Title and Icon', 'wp-expand-tabs-free' ),
				'subtitle'        => __( 'Set space between title and icon.', 'wp-expand-tabs-free' ),
				'title_help'      => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/space-between-title-and-icon.svg" alt="Space Between Title and Icon"></div><div class="wptabspro-info-label">' . __( 'Space Between Title and Icon', 'wp-expand-tabs-free' ) . '</div>',
				'all'             => true,
				'all_text'        => false,
				'all_placeholder' => 'size',
				'default'         => array(
					'all' => '10',
				),
				'units'           => array(
					'px',
				),
				'dependency'      => array(
					'sptpro_tabs_icon',
					'==',
					'true',
				),
			),
			array(
				'id'         => 'sptpro_tab_icon_color',
				'type'       => 'color_group',
				'class'      => 'only_pro_tabs_icon_section tab_icon_position',
				'title'      => __( 'Icon Color', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Set tab icon color.', 'wp-expand-tabs-free' ),
				'options'    => array(
					'tab-icon-color'        => __( 'Color', 'wp-expand-tabs-free' ),
					'tab-icon-color-active' => __( 'Active Color', 'wp-expand-tabs-free' ),
					'tab-icon-color-hover'  => __( 'Hover Color', 'wp-expand-tabs-free' ),
				),
				'default'    => array(
					'tab-icon-color'        => '#444',
					'tab-icon-color-active' => '#444',
					'tab-icon-color-hover'  => '#444',
				),
				'dependency' => array(
					'sptpro_tabs_icon',
					'==',
					'true',
				),
			),
			array(
				'id'         => 'sptpro_tab_icon_position',
				'type'       => 'image_select',
				'class'      => 'only_pro_tabs_icon_section tab_icon_position',
				'title'      => __( 'Icon Position', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Select tab icon position.', 'wp-expand-tabs-free' ),
				'title_help' => __( '<div class="wptabspro-info-label">Icon Position (Pro)</div><div class="wptabspro-short-content">This option allows you to specify the position of icons within your tab interface. You can place icons to the Top, Right, Bottom and Left of tab\'s title.</div><a class="wptabspro-open-docs" href="https://docs.shapedplugin.com/docs/wp-tabs-pro/configurations/how-to-select-tabs-icon-position/" target="_blank">Open Docs</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-icon-position/" target="_blank">Live Demo</a>', 'wp-expand-tabs-free' ),
				'options'    => array(
					'tab-icon-position-left'  => array(
						'image'       => WP_TABS_URL . '/admin/img/icon-positioning/horizontal-icon-position-left.svg',
						'option_name' => __( 'Left', 'wp-expand-tabs-free' ),
					),
					'tab-icon-position-top'   => array(
						'image'       => WP_TABS_URL . '/admin/img/icon-positioning/horizontal-icon-position-top.svg',
						'option_name' => __( 'Top', 'wp-expand-tabs-free' ),
					),
					'tab-icon-position-right' => array(
						'image'       => WP_TABS_URL . '/admin/img/icon-positioning/horizontal-icon-position-right.svg',
						'option_name' => __( 'Right', 'wp-expand-tabs-free' ),
					),
				),
				'radio'      => true,
				'default'    => 'tab-icon-position-left',
			),

			array(
				'type'    => 'subheading',
				'content' => __( 'Tabs Title and Description', 'wp-expand-tabs-free' ),
			),
			array(
				'id'         => 'sptpro_showhide_subtitle',
				'type'       => 'switcher',
				'class'      => 'only_pro_switcher vertical-gap',
				'title'      => __( 'Tabs Subtitle', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Show/hide tabs subtitle.', 'wp-expand-tabs-free' ),
				'default'    => false,
				'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
				'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
				'text_width' => 75,
			),
			array(
				'id'       => 'sptpro_title_heading_tag',
				'type'     => 'select',
				'class'    => 'vertical-gap',
				'title'    => __( 'Title HTML Tag', 'wp-expand-tabs-free' ),
				'subtitle' => __( 'Select a tag for tabs title.', 'wp-expand-tabs-free' ),
				'options'  => array(
					'H1' => 'H1',
					'H2' => 'H2',
					'H3' => 'H3',
					'H4' => 'H4',
					'H5' => 'H5',
					'H6' => 'H6',
				),
				'default'  => 'H4',
				'radio'    => true,
			),
			array(
				'id'         => 'sptpro_active_indicator_arrow',
				'type'       => 'switcher',
				'class'      => 'only_pro_switcher vertical-gap',
				'title'      => __( 'Active Tab Indicator Arrow', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Show/hide active tab indicator arrow.', 'wp-expand-tabs-free' ),
				'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/active-tab-indicator-arrow.svg" alt="Active Tab Indicator"></div><div class="wptabspro-info-label">' . __( 'Active Tab Indicator Arrow (Pro)', 'wp-expand-tabs-free' ) . '</div><a class="wptabspro-open-docs" href="https://docs.shapedplugin.com/docs/wp-tabs-pro/configurations/how-to-configure-active-tab-indicator-arrow/" target="_blank">Open Docs</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/active-tab-indicator-arrow/" target="_blank">' . __( 'Live Demo', 'wp-expand-tabs-free' ) . '</a>',
				'default'    => false,
				'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
				'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
				'text_width' => 75,
			),
			array(
				'id'         => 'sptpro_tabs_bg_color_type',
				'type'       => 'button_set',
				'class'      => 'sptpro_tabs_bg_color_type',
				'title'      => __( 'Title Background Color Type', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Choose a color type for the title background.', 'wp-expand-tabs-free' ),
				'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/title-background-color-type.svg" alt="Title Background Color Type"></div><div class="wptabspro-info-label">' . __( 'Title Background Color Type', 'wp-expand-tabs-free' ) . '</div><a class="wptabspro-open-docs" href="https://docs.shapedplugin.com/docs/wp-tabs-pro/configurations/how-to-set-tabs-title-background-color/" target="_blank">Open Docs</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/title-background-color/" target="_blank">Live Demo</a>',
				'options'    => array(
					'solid'    => __( 'Solid', 'wp-expand-tabs-free' ),
					'gradient' => __( 'Gradient', 'wp-expand-tabs-free' ),
				),
				'default'    => 'solid',
			),
			array(
				'id'         => 'sptpro_title_bg_color',
				'type'       => 'color_group',
				'title'      => __( 'Title Background Color', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Set tabs title background color.', 'wp-expand-tabs-free' ),
				'options'    => array(
					'title-bg-color'        => __( 'Background', 'wp-expand-tabs-free' ),
					'title-active-bg-color' => __( 'Active Background', 'wp-expand-tabs-free' ),
					'title-hover-bg-color'  => __( 'Hover Background', 'wp-expand-tabs-free' ),
				),
				'default'    => array(
					'title-bg-color'        => '#eee',
					'title-active-bg-color' => '#fff',
					'title-hover-bg-color'  => '#fff',
				),
				'dependency' => array( 'sptpro_tabs_bg_color_type', '==', 'solid' ),
			),
			array(
				'id'         => 'sptpro_title_padding',
				'type'       => 'spacing',
				'title'      => __( 'Title Padding', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Set tabs title padding.', 'wp-expand-tabs-free' ),
				'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/title-padding.svg" alt="Title Padding"></div><div class="wptabspro-info-label">' . __( 'Title Padding', 'wp-expand-tabs-free' ) . '</div>',
				'units'      => array( 'px' ),
				'default'    => array(
					'left'   => '15',
					'top'    => '15',
					'bottom' => '15',
					'right'  => '15',
				),
			),
			array(
				'id'            => 'sptpro_tabs_border',
				'type'          => 'border',
				'class'         => 'sptpro_tabs_border',
				'title'         => __( 'Border', 'wp-expand-tabs-free' ),
				'subtitle'      => __( 'Set tabs border.', 'wp-expand-tabs-free' ),
				'all'           => true,
				'border_radius' => true,
				'default'       => array(
					'all'           => 1,
					'style'         => 'solid',
					'color'         => '#cccccc',
					'border_radius' => '2',
				),
			),
			array(
				'id'         => 'sptpro_active_tab_style_horizontal',
				'type'       => 'image_select',
				'class'      => 'only_pro_tabs_icon_section',
				'title'      => __( 'Active Tab Style', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Choose active tab style.', 'wp-expand-tabs-free' ),
				'title_help' => __( '<div class="wptabspro-info-label">Active Tab Style (Pro)</div><div class="wptabspro-short-content">Choose how the currently selected tab looks. You can add a line to the Top or Bottom of tab\'s title to make it stand out.</div><a class="wptabspro-open-docs" href="https://docs.shapedplugin.com/docs/wp-tabs-pro/configurations/how-to-set-active-tab-style/" target="_blank">Open Docs</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/active-tab-style/" target="_blank">Live Demo</a>', 'wp-expand-tabs-free' ),
				'options'    => array(
					'horizontal-active-tab-normal'      => array(
						'image'       => WP_TABS_URL . '/admin/img/active-tab-style/horizontal-active-tab-normal.svg',
						'option_name' => __( 'Normal', 'wp-expand-tabs-free' ),
					),
					'horizontal-active-tab-top-line'    => array(
						'image'       => WP_TABS_URL . '/admin/img/active-tab-style/horizontal-active-tab-top-line.svg',
						'option_name' => __( 'Top', 'wp-expand-tabs-free' ),
					),
					'horizontal-active-tab-bottom-line' => array(
						'image'       => WP_TABS_URL . '/admin/img/active-tab-style/horizontal-active-tab-bottom-line.svg',
						'option_name' => __( 'Bottom', 'wp-expand-tabs-free' ),
					),
				),
				'radio'      => true,
				'default'    => 'horizontal-active-tab-normal',
			),
			array(
				'id'              => 'sptpro_margin_between_tabs_and_desc',
				'type'            => 'spacing',
				'bottom_icon'     => '<i class="fa fa-arrows-v"></i>',
				'class'           => 'only_pro_spinner',
				'title'           => __( 'Margin Between Tabs and Description', 'wp-expand-tabs-free' ),
				'title_help'      => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/margin-between-tabs-and-description.svg" alt="Margin Between Tabs and Description"></div><div class="wptabspro-info-label">' . __( 'Margin Between Tabs and Description', 'wp-expand-tabs-free' ) . '</div><a class="wptabspro-open-docs" href="https://docs.shapedplugin.com/docs/wp-tabs-pro/configurations/how-to-set-margin-between-tabs-and-description/" target="_blank">Open Docs</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/margin-between-tabs-and-description/" target="_blank">Live Demo</a>',
				'subtitle'        => __( 'Set a space between tabs and description.', 'wp-expand-tabs-free' ),
				'all_placeholder' => 'margin',
				'bottom_text'     => '',
				'top'             => false,
				'left'            => false,
				'bottom'          => true,
				'right'           => false,
				'default'         => array(
					'bottom' => '0',
				),
				'units'           => array(
					'px',
				),
			),
			array(
				'id'       => 'sptpro_desc_bg_color',
				'type'     => 'color',
				'title'    => __( 'Description Background Color', 'wp-expand-tabs-free' ),
				'subtitle' => __( 'Set description background color.', 'wp-expand-tabs-free' ),
				'default'  => '#ffffff',
			),
			array(
				'id'         => 'sptpro_desc_padding',
				'type'       => 'spacing',
				'title'      => __( 'Description Padding', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Set description padding.', 'wp-expand-tabs-free' ),
				'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/description-padding.svg" alt="Description Padding"></div><div class="wptabspro-info-label"> ' . __( 'Description Padding', 'wp-expand-tabs-free' ) . '</div>',
				'units'      => array( 'px' ),
				'default'    => array(
					'left'   => '20',
					'top'    => '20',
					'bottom' => '20',
					'right'  => '20',
				),
			),
			array(
				'id'       => 'sptpro_desc_border',
				'type'     => 'border',
				'title'    => __( 'Description Border', 'wp-expand-tabs-free' ),
				'subtitle' => __( 'Set description border.', 'wp-expand-tabs-free' ),
				'all'      => true,
				'style'    => true,
				'default'  => array(
					'all'   => 1,
					'style' => 'solid',
					'color' => '#cccccc',
				),
			),
			array(
				'id'         => 'sptpro_flat_tab_style_horizontal',
				'type'       => 'image_select',
				'class'      => 'only_pro_tabs_icon_section',
				'title'      => __( 'Flat Tab Style', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Choose flat tab style.', 'wp-expand-tabs-free' ),
				'title_help' => __( '<div class="wptabspro-info-label">Flat Tab Style (Pro)</div><div class="wptabspro-short-content">Select the Underline option to enhance your tabs with flat underline positioned below the tab navigation for a clean and modern look.</div><a class="wptabspro-open-docs" href="https://docs.shapedplugin.com/docs/wp-tabs-pro/configurations/how-to-make-tab-flat-style/" target="_blank">Open Docs</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/flat-contained-tabs/" target="_blank">Live Demo</a>', 'wp-expand-tabs-free' ),
				'options'    => array(
					'horizontal-flat-tab-normal'    => array(
						'image'       => WP_TABS_URL . '/admin/img/flat-tab-style/horizontal-flat-tab-normal.svg',
						'option_name' => __( 'Normal', 'wp-expand-tabs-free' ),
					),
					'horizontal-flat-tab-underline' => array(
						'image'       => WP_TABS_URL . '/admin/img/flat-tab-style/horizontal-flat-tab-underline.svg',
						'option_name' => __( 'Underline', 'wp-expand-tabs-free' ),
					),
				),
				'radio'      => true,
				'default'    => 'horizontal-flat-tab-normal',
			),
			array(
				'id'         => 'sptpro_fixed_height',
				'type'       => 'button_set',
				'class'      => 'only_pro_fixed_height',
				'title'      => __( 'Content Height', 'wp-expand-tabs-free' ),
				'title_help' => '<div class="wptabspro-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'partials/models/assets/images/help-visuals/content-height.svg" alt="Content Height"></div><div class="wptabspro-info-label"> ' . __( 'Content Height', 'wp-expand-tabs-free' ) . '</div><a class="wptabspro-open-docs" href="https://docs.shapedplugin.com/docs/wp-tabs-pro/configurations/how-to-set-tabs-content-height/" target="_blank">Open Docs</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-fixed-content-height/" target="_blank">Live Demo</a>',
				'subtitle'   => __( 'Select content height.', 'wp-expand-tabs-free' ),
				'options'    => array(
					'auto'   => __( 'Auto', 'wp-expand-tabs-free' ),
					'custom' => __( 'Custom', 'wp-expand-tabs-free' ),
				),
				'default'    => 'auto',
			),
			array(
				'type'    => 'notice',
				'class'   => 'only_pro_notice',
				'content' => __( 'To unlock the more settings for <b>Tabs Title and Description</b>, <a href="https://wptabs.com/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a>', 'wp-expand-tabs-free' ),
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Small Screen', 'wp-expand-tabs-free' ),
			),
			array(
				'id'              => 'sptpro_set_small_screen',
				'type'            => 'spacing',
				'title'           => __( 'When Screen Width is Less Than', 'wp-expand-tabs-free' ),
				'all'             => true,
				'all_icon'        => '<i class="fa fa-arrows-h"></i>',
				'all_placeholder' => 'margin',
				'default'         => array(
					'all' => '480',
				),
				'units'           => array(
					'px',
				),
			),
			array(
				'id'         => 'sptpro_tabs_on_small_screen',
				'type'       => 'radio',
				'title'      => __( 'Tabs Mode on Small Screen', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Choose a tabs mode on small screen.', 'wp-expand-tabs-free' ),
				'title_help' => __( '<div class="wptabspro-info-label">Tabs Mode on Small Screen</div><div class="wptabspro-short-content">Choose how your tabs behave on small screens, such as mobile devices. You can select "Full Width" to maintain the current layout or "Accordion" to switch to a collapsible format, ensuring the best user experience on mobile.</div><a class="wptabspro-open-docs" href="https://docs.shapedplugin.com/docs/wp-tabs-pro/configurations/how-to-configure-tabs-mood-on-small-screen/" target="_blank">Open Docs</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-mood-on-small-screen/" target="_blank">Live Demo</a>', 'wp-expand-tabs-free' ),
				'options'    => array(
					'full_widht'     => __( 'Full Width', 'wp-expand-tabs-free' ),
					'accordion_mode' => __( 'Accordion', 'wp-expand-tabs-free' ),
				),
				'default'    => 'full_widht',
			),
			array(
				'id'         => 'sptpro_expand_and_collapse_icon',
				'type'       => 'switcher',
				'title'      => __( 'Expand and Collapse Icon', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Show/Hide expand and collapse icon.', 'wp-expand-tabs-free' ),
				'default'    => true,
				'text_on'    => __( 'Show', 'wp-expand-tabs-free' ),
				'text_off'   => __( 'Hide', 'wp-expand-tabs-free' ),
				'text_width' => 75,
				'dependency' => array( 'sptpro_tabs_on_small_screen', '==', 'accordion_mode' ),
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Animation', 'wp-expand-tabs-free' ),
			),
			array(
				'id'         => 'sptpro_tabs_animation',
				'type'       => 'switcher',
				'title'      => __( 'Animation', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Enable/Disable animation for tabs content.', 'wp-expand-tabs-free' ),
				'title_help' => __( '<div class="wptabspro-info-label">Animation</div><div class="wptabspro-short-content">You can select animation to enhance your tab with over 50+ fascinating animations to add dynamic and eye-catching effects to your content.</div><a class="wptabspro-open-docs" href="https://docs.shapedplugin.com/docs/wp-tabs-pro/configurations/how-to-configure-tabs-animation/" target="_blank">Open Docs</a><a class="wptabspro-open-live-demo" href="https://wptabs.com/tabs-animation/" target="_blank">Live Demo</a>', 'wp-expand-tabs-free' ),
				'text_on'    => __( 'Enabled', 'wp-expand-tabs-free' ),
				'text_off'   => __( 'Disabled', 'wp-expand-tabs-free' ),
				'text_width' => 94,
				'default'    => true,
			),
			array(
				'id'         => 'sptpro_tabs_animation_type',
				'type'       => 'select',
				'class'      => 'sptpro-tabs-animation-type',
				'title'      => __( 'Animation Style', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Choose an animation style for tabs content.', 'wp-expand-tabs-free' ),
				'options'    => array(
					'fadeIn'            => __( 'fadeIn', 'wp-expand-tabs-free' ),
					'fadeInDown'        => __( 'fadeInDown', 'wp-expand-tabs-free' ),
					'fadeInLeft'        => __( 'fadeInLeft (Pro)', 'wp-expand-tabs-free' ),
					'fadeInRight'       => __( 'fadeInRight (Pro)', 'wp-expand-tabs-free' ),
					'fadeInUp'          => __( 'fadeInUp (Pro)', 'wp-expand-tabs-free' ),
					'fadeInDownBig'     => __( 'fadeInDownBig (Pro)', 'wp-expand-tabs-free' ),
					'fadeInLeftBig'     => __( 'fadeInLeftBig (Pro)', 'wp-expand-tabs-free' ),
					'fadeInRightBig'    => __( 'fadeInRightBig (Pro)', 'wp-expand-tabs-free' ),
					'fadeInUpBig'       => __( 'fadeInUpBig (Pro)', 'wp-expand-tabs-free' ),
					'zoomIn'            => __( 'zoomIn (Pro)', 'wp-expand-tabs-free' ),
					'zoomInDown'        => __( 'zoomInDown (Pro)', 'wp-expand-tabs-free' ),
					'zoomInLeft'        => __( 'zoomInLeft (Pro)', 'wp-expand-tabs-free' ),
					'zoomInRight'       => __( 'zoomInRight (Pro)', 'wp-expand-tabs-free' ),
					'zoomInUp'          => __( 'zoomInUp (Pro)', 'wp-expand-tabs-free' ),
					'zoomOut'           => __( 'zoomOut (Pro)', 'wp-expand-tabs-free' ),
					'slideInDown'       => __( 'slideInDown (Pro)', 'wp-expand-tabs-free' ),
					'slideInLeft'       => __( 'slideInLeft (Pro)', 'wp-expand-tabs-free' ),
					'slideInRight'      => __( 'slideInRight (Pro)', 'wp-expand-tabs-free' ),
					'slideInUp'         => __( 'slideInUp (Pro)', 'wp-expand-tabs-free' ),
					'flip'              => __( 'flip (Pro)', 'wp-expand-tabs-free' ),
					'flipInX'           => __( 'flipInX (Pro)', 'wp-expand-tabs-free' ),
					'flipInY'           => __( 'flipInY (Pro)', 'wp-expand-tabs-free' ),
					'bounce'            => __( 'bounce (Pro)', 'wp-expand-tabs-free' ),
					'bounceIn'          => __( 'bounceIn (Pro)', 'wp-expand-tabs-free' ),
					'bounceInLeft'      => __( 'bounceInLeft (Pro)', 'wp-expand-tabs-free' ),
					'bounceInRight'     => __( 'bounceInRight (Pro)', 'wp-expand-tabs-free' ),
					'bounceInUp'        => __( 'bounceInUp (Pro)', 'wp-expand-tabs-free' ),
					'bounceInDown'      => __( 'bounceInDown (Pro)', 'wp-expand-tabs-free' ),
					'rotateIn'          => __( 'rotateIn (Pro)', 'wp-expand-tabs-free' ),
					'rotateInDownLeft'  => __( 'rotateInDownLeft (Pro)', 'wp-expand-tabs-free' ),
					'rotateInDownRight' => __( 'rotateInDownRight (Pro)', 'wp-expand-tabs-free' ),
					'rotateInUpLeft'    => __( 'rotateInUpLeft (Pro)', 'wp-expand-tabs-free' ),
					'rotateInUpRight'   => __( 'rotateInUpRight (Pro)', 'wp-expand-tabs-free' ),
					'rotateInDownLeft'  => __( 'rotateInDownLeft (Pro)', 'wp-expand-tabs-free' ),
					'rotateInDownRight' => __( 'rotateInDownRight (Pro)', 'wp-expand-tabs-free' ),
					'backInDown'        => __( 'backInDown (Pro)', 'wp-expand-tabs-free' ),
					'backInLeft'        => __( 'backInLeft (Pro)', 'wp-expand-tabs-free' ),
					'flash'             => __( 'flash (Pro)', 'wp-expand-tabs-free' ),
					'pulse'             => __( 'pulse (Pro)', 'wp-expand-tabs-free' ),
					'shake'             => __( 'shake (Pro)', 'wp-expand-tabs-free' ),
					'swing'             => __( 'swing (Pro)', 'wp-expand-tabs-free' ),
					'tada'              => __( 'tada (Pro)', 'wp-expand-tabs-free' ),
					'wobble'            => __( 'wobble (Pro)', 'wp-expand-tabs-free' ),
					'rubberBand'        => __( 'rubberBand (Pro)', 'wp-expand-tabs-free' ),
					'heartBeat'         => __( 'heartBeat (Pro)', 'wp-expand-tabs-free' ),
					'jello'             => __( 'jello (Pro)', 'wp-expand-tabs-free' ),
					'headShake'         => __( 'headShake (Pro)', 'wp-expand-tabs-free' ),
					'lightSpeedIn'      => __( 'lightSpeedIn (Pro)', 'wp-expand-tabs-free' ),
					'jackInTheBox'      => __( 'jackInTheBox (Pro)', 'wp-expand-tabs-free' ),
					'rollIn'            => __( 'rollIn (Pro)', 'wp-expand-tabs-free' ),
				),
				'default'    => 'fadeIn',
				'dependency' => array( 'sptpro_tabs_animation', '==', 'true' ),
			),
			array(
				'id'         => 'sptpro_animation_time',
				'type'       => 'spinner',
				'title'      => __( 'Transition Delay', 'wp-expand-tabs-free' ),
				'subtitle'   => __( 'Set animation transition delay in milisecond.', 'wp-expand-tabs-free' ),
				'unit'       => 'ms',
				'min'        => 10,
				'max'        => 100000,
				'default'    => 500,
				'dependency' => array( 'sptpro_tabs_animation', '==', 'true' ),
			),
			array(
				'type'    => 'notice',
				'class'   => 'only_pro_notice',
				'content' => __( 'To unlock <strong>50+ elegant Tabs Animations</strong> settings, <a href="https://wptabs.com/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a>', 'wp-expand-tabs-free' ),
			),
		),
	)
); // Carousel settings section end.

//
// Typography section begin.
//
SP_WP_TABS::createSection(
	$sptpro_shortcode_settings,
	array(
		'title'           => __( 'Typography', 'wp-expand-tabs-free' ),
		'icon'            => 'fa fa-font',
		'enqueue_webfont' => true,
		'fields'          => array(
			array(
				'type'    => 'notice',
				'class'   => 'only_pro_notice_typo',
				'content' => __( 'To unlock These Typography (940+ Google Fonts) options, <a href="https://wptabs.com/pricing/?ref=1" target="_blank"><b>Upgrade To Pro</b></a>! P.S. Note: The <b class="sptpro-notice-typo-exception">color fields and margin bottom for section title</b> work in the lite version.', 'wp-expand-tabs-free' ),
			),
			array(
				'id'       => 'sptpro_section_title_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Tabs Section Title Font', 'wp-expand-tabs-free' ),
				'subtitle' => __( 'On/Off google font for the section title.', 'wp-expand-tabs-free' ),
				'default'  => false,
			),
			array(
				'id'            => 'sptpro_section_title_typo',
				'type'          => 'typography',
				'class'         => 'sptpro_tabs_section_title_typo',
				'title'         => __( 'Tabs Section Title', 'wp-expand-tabs-free' ),
				'subtitle'      => __( 'Set tabs section title font properties.', 'wp-expand-tabs-free' ),
				'margin_bottom' => true,
				'default'       => array(
					'color'          => '#444444',
					'font-family'    => '',
					'font-style'     => '600',
					'font-size'      => '28',
					'line-height'    => '28',
					'letter-spacing' => '0',
					'text-align'     => 'left',
					'text-transform' => 'none',
					'type'           => 'google',
					'unit'           => 'px',
					'margin-bottom'  => '30',
				),
				'preview'       => 'always',
				'preview_text'  => 'Tabs Section Title',
			),
			array(
				'id'       => 'sptpro_tabs_title_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Tabs Title Font', 'wp-expand-tabs-free' ),
				'subtitle' => __( 'On/Off google font for the tabs title.', 'wp-expand-tabs-free' ),
				'default'  => false,
			),
			array(
				'id'           => 'sptpro_tabs_title_typo',
				'type'         => 'typography',
				'title'        => __( 'Tabs Title', 'wp-expand-tabs-free' ),
				'subtitle'     => __( 'Set tabs title font properties.', 'wp-expand-tabs-free' ),
				'default'      => array(
					'font-family'    => '',
					'font-weight'    => '600',
					'font-style'     => 'normal',
					'font-size'      => '16',
					'line-height'    => '22',
					'letter-spacing' => '0',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'color'          => '#444',
					'hover_color'    => '#444',
					'active_color'   => '#444',
					'type'           => 'google',
				),
				'preview_text' => 'Tabs Title',
				'preview'      => 'always',
				'color'        => true,
				'hover_color'  => true,
				'active_color' => true,
			),
			array(
				'id'       => 'sptpro_subtitle_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Subtitle Font', 'wp-expand-tabs-free' ),
				'subtitle' => __( 'On/Off google font for the tabs subtitle.', 'wp-expand-tabs-free' ),
				'default'  => false,
			),
			array(
				'id'           => 'sptpro_subtitle_typo',
				'type'         => 'typography',
				'title'        => __( 'Tabs Subtitle', 'wp-expand-tabs-free' ),
				'subtitle'     => __( 'Set tabs subtitle font properties.', 'wp-expand-tabs-free' ),
				'class'        => 'disable-color-picker',
				'default'      => array(
					'font-family'    => '',
					'font-style'     => '400',
					'font-size'      => '14',
					'line-height'    => '18',
					'letter-spacing' => '0',
					'color'          => '#616161',
					'active_color'   => '#616161',
					'hover_color'    => '#616161',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'type'           => 'google',
				),
				'preview_text' => 'Tabs Sub Title',
				'preview'      => 'always',
				'color'        => true,
				'hover_color'  => true,
				'active_color' => true,
			),
			array(
				'id'       => 'sptpro_desc_font_load',
				'type'     => 'switcher',
				'title'    => __( 'Load Description Font', 'wp-expand-tabs-free' ),
				'subtitle' => __( 'On/Off google font for the tabs description.', 'wp-expand-tabs-free' ),
				'default'  => false,
			),
			array(
				'id'           => 'sptpro_desc_typo',
				'type'         => 'typography',
				'title'        => __( 'Description', 'wp-expand-tabs-free' ),
				'subtitle'     => __( 'Set description font properties.', 'wp-expand-tabs-free' ),
				'default'      => array(
					'color'          => '#444',
					'font-family'    => '',
					'font-style'     => '400',
					'font-size'      => '16',
					'line-height'    => '24',
					'letter-spacing' => '0',
					'text-align'     => 'left',
					'text-transform' => 'none',
					'type'           => 'google',
				),
				'preview'      => 'always',
				'preview_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
			),
		), // End of fields array.
	)
); // Style settings section end.

//
// Metabox of the footer section / shortocde section.
// Set a unique slug-like ID.
//
$sptpro_display_shortcode = 'sp_tab_display_shortcode';

//
// Create a metabox.
//
SP_WP_TABS::createMetabox(
	$sptpro_display_shortcode,
	array(
		'title'     => 'WP Tabs',
		'post_type' => 'sp_wp_tabs',
		'context'   => 'normal',
	)
);

//
// Create a section.
//
SP_WP_TABS::createSection(
	$sptpro_display_shortcode,
	array(
		'fields' => array(
			array(
				'type'  => 'shortcode',
				'class' => 'sp-tab__admin-footer',
			),
		),
	)
);
