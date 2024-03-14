<?php

namespace Skt_Addons_Elementor\Elementor;

use Elementor\Element_Base;

defined('ABSPATH') || die();

class Widgets_Manager
{

	const WIDGETS_DB_KEY = 'sktaddonselementor_inactive_widgets';

	/**
	 * Initialize
	 */
	public static function init()
	{
		add_action('elementor/widgets/widgets_registered', [__CLASS__, 'register']);
		add_action('elementor/frontend/before_render', [__CLASS__, 'add_global_widget_render_attributes']);
	}

	public static function add_global_widget_render_attributes(Element_Base $widget)
	{
		if ($widget->get_data('widgetType') === 'global' && method_exists($widget, 'get_original_element_instance')) {
			$original_instance = $widget->get_original_element_instance();
			if (method_exists($original_instance, 'get_html_wrapper_class') && strpos($original_instance->get_data('widgetType'), 'skt-') !== false) {
				$widget->add_render_attribute('_wrapper', [
					'class' => $original_instance->get_html_wrapper_class(),
				]);
			}
		}
	}

	public static function get_inactive_widgets()
	{
		return get_option(self::WIDGETS_DB_KEY, []);
	}

	public static function save_inactive_widgets($widgets = [])
	{
		update_option(self::WIDGETS_DB_KEY, $widgets);
	}

	public static function get_widgets_map()
	{
		$widgets_map = [
			self::get_base_widget_key() => [
				'css' => ['common'],
				'js' => [],
				'vendor' => [
					'js' => [],
					'css' => ['skt-icons', 'font-awesome']
				]
			],
		];

		$local_widgets_map = self::get_local_widgets_map();
		$widgets_map = array_merge($widgets_map, $local_widgets_map);

		// return array_merge($widgets_map, $pro_widget_map);
		return apply_filters('sktaddonselementor_get_widgets_map', $widgets_map);
	}

	/**
	 * Get the pro widgets map for dashboard only
	 *
	 * @return array
	 */
	public static function get_pro_widget_map(){
		return [];
	}

	/**
	 * Get the free widgets map
	 *
	 * @return array
	 */
	public static function get_local_widgets_map()
	{
		// All the widgets are listed below with respective map

		return [
			'infobox' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Info Box', 'skt-addons-elementor'),
				'icon' => 'skti skti-info',
				'css' => ['btn', 'infobox',],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'card' => [
				'cat' => 'creative',
				'is_active' => false,
				'title' => __('Card', 'skt-addons-elementor'),
				'icon' => 'skti skti-card',
				'css' => ['btn', 'badge', 'card'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'cf7' => [
				'cat' => 'forms',
				'is_active' => true,
				'title' => __('Contact Form 7', 'skt-addons-elementor'),
				'icon' => 'skti skti-form',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'icon-box' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Icon Box', 'skt-addons-elementor'),
				'icon' => 'skti skti-icon-box',
				'css' => ['badge', 'icon-box'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'member' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Team Member', 'skt-addons-elementor'),
				'icon' => 'skti skti-team-member',
				'css' => ['btn', 'member'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'review' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Review', 'skt-addons-elementor'),
				'icon' => 'skti skti-review',
				'css' => ['review'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'image-compare' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Image Compare', 'skt-addons-elementor'),
				'icon' => 'skti skti-image-compare',
				'css' => ['image-comparison'],
				'js' => [],
				'vendor' => [
					'css' => ['twentytwenty'],
					'js' => ['jquery-event-move', 'jquery-twentytwenty', 'imagesloaded'],
				],
			],
			'justified-gallery' => [
				'cat' => 'creative',
				'is_active' => false,
				'title' => __('Justified Grid', 'skt-addons-elementor'),
				'icon' => 'skti skti-brick-wall',
				'css' => ['justified-gallery', 'gallery-filter'],
				'js' => [],
				'vendor' => [
					'css' => ['justifiedGallery'],
					'js' => ['jquery-justifiedGallery'],
				],
			],
			'image-grid' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Image Grid', 'skt-addons-elementor'),
				'icon' => 'skti skti-grid-even',
				'css' => ['image-grid', 'gallery-filter'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['jquery-isotope', 'imagesloaded'],
				],
			],
			'slider' => [
				'cat' => 'slider-&-carousel',
				'is_active' => true,
				'title' => __('Slider', 'skt-addons-elementor'),
				'icon' => 'skti skti-image-slider',
				'css' => ['slider-carousel'],
				'js' => [],
				'vendor' => [
					'css' => ['slick', 'slick-theme'],
					'js' => ['jquery-slick'],
				],
			],
			'carousel' => [
				'cat' => 'slider-&-carousel',
				'is_active' => true,
				'title' => __('Carousel', 'skt-addons-elementor'),
				'icon' => 'skti skti-carousal',
				'css' => ['slider-carousel'],
				'js' => [],
				'vendor' => [
					'css' => ['slick', 'slick-theme'],
					'js' => ['jquery-slick'],
				],
			],
			'skills' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Skill Bars', 'skt-addons-elementor'),
				'icon' => 'skti skti-progress-bar',
				'css' => ['skills'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['elementor-waypoints', 'jquery-numerator'],
				],
			],
			'gradient-heading' => [
				'cat' => 'creative',
				'is_active' => true,
				'title' => __('Gradient Heading', 'skt-addons-elementor'),
				'icon' => 'skti skti-drag',
				'css' => ['gradient-heading'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'wpform' => [
				'cat' => 'forms',
				'is_active' => true,
				'title' => __('WPForms', 'skt-addons-elementor'),
				'icon' => 'skti skti-form',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'ninjaform' => [
				'cat' => 'forms',
				'is_active' => true,
				'title' => __('Ninja Forms', 'skt-addons-elementor'),
				'icon' => 'skti skti-form',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'calderaform' => [
				'cat' => 'forms',
				'is_active' => true,
				'title' => __('Caldera Forms', 'skt-addons-elementor'),
				'icon' => 'skti skti-form',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'weform' => [
				'cat' => 'forms',
				'is_active' => true,
				'title' => __('weForms', 'skt-addons-elementor'),
				'icon' => 'skti skti-form',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'logo-grid' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Logo Grid', 'skt-addons-elementor'),
				'icon' => 'skti skti-logo-grid',
				'css' => ['logo-grid'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'dual-button' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Dual Button', 'skt-addons-elementor'),
				'icon' => 'skti skti-accordion-horizontal',
				'css' => ['dual-btn'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'testimonial' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Testimonial', 'skt-addons-elementor'),
				'icon' => 'skti skti-testimonial',
				'css' => ['testimonial'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'number' => [
				'cat' => 'creative',
				'is_active' => true,
				'title' => __('Number', 'skt-addons-elementor'),
				'icon' => 'skti skti-madel',
				'css' => ['number'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['elementor-waypoints', 'jquery-numerator'],
				],
			],
			'calendly' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Calendly', 'skt-addons-elementor'),
				'icon' => 'skti skti-calendar',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'step-flow' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Step Flow', 'skt-addons-elementor'),
				'icon' => 'skti skti-step-flow',
				'css' => ['steps-flow'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'gravityforms' => [
				'cat' => 'forms',
				'is_active' => true,
				'title' => __('Gravity Forms', 'skt-addons-elementor'),
				'icon' => 'skti skti-form',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'news-ticker' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('News Ticker', 'skt-addons-elementor'),
				'icon' => 'skti skti-slider',
				'css' => ['news-ticker'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['jquery-keyframes'],
				],
			],
			'fun-factor' => [
				'cat' => 'creative',
				'is_active' => true,
				'title' => __('Fun Factor', 'skt-addons-elementor'),
				'icon' => 'skti skti-slider',
				'css' => ['fun-factor'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['elementor-waypoints', 'jquery-numerator'],
				],
			],
			'bar-chart' => [
				'cat' => 'chart',
				'is_active' => true,
				'demo' => '',
				'title' => __('Bar Chart', 'skt-addons-elementor'),
				'icon' => 'skti skti-graph-bar',
				'css' => ['chart'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['chart-js'],
				],
			],
			'social-icons' => [
				'cat' => 'social-media',
				'is_active' => true,
				'title' => __('Social Icons', 'skt-addons-elementor'),
				'icon' => 'skti skti-bond2',
				'css' => ['social-icons'],
				'js' => [],
				'vendor' => [
					'css' => ['hover-css'],
					'js' => [],
				]
			],
			'post-list' => [
				'cat' => 'post',
				'is_active' => true,
				'title' => __('Post List', 'skt-addons-elementor'),
				'icon' => 'skti skti-post-list',
				'css' => ['post-list'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'post-tab' => [
				'cat' => 'post',
				'is_active' => true,
				'title' => __('Post Tab', 'skt-addons-elementor'),
				'icon' => 'skti skti-post-tab',
				'css' => ['post-tab'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'taxonomy-list' => [
				'cat' => 'post',
				'is_active' => true,
				'title' => __('Taxonomy List', 'skt-addons-elementor'),
				'icon' => 'skti skti-clip-board',
				'css' => ['taxonomy-list'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'threesixty-rotation' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('360° Rotation', 'skt-addons-elementor'),
				'icon' => 'skti skti-3d-rotate',
				'css' => ['threesixty-rotation'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['circlr', 'skt-simple-magnify'],
				],
			],
			'fluent-form' => [
				'cat' => 'forms',
				'is_active' => true,
				'title' => __('Fluent Form', 'skt-addons-elementor'),
				'icon' => 'skti skti-form',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'data-table' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Data Table', 'skt-addons-elementor'),
				'icon' => 'skti skti-data-table',
				'css' => ['data-table'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'horizontal-timeline' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Horizontal Timeline', 'skt-addons-elementor'),
				'icon' => 'skti skti-timeline',
				'css' => ['horizontal-timeline'],
				'js' => [],
				'vendor' => [
					'css' => ['slick', 'slick-theme'],
					'js' => ['jquery-slick'],
				],
			],
			'social-share' => [
				'cat' => 'social-media',
				'is_active' => true,
				'title' => __('Social Share', 'skt-addons-elementor'),
				'icon' => 'skti skti-share',
				'css' => ['social-share'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['sharer-js'],
				]
			],
			'image-hover-effect' => [
				'cat' => 'creative',
				'is_active' => true,
				'title' => __('Image Hover Effect', 'skt-addons-elementor'),
				'icon' => 'skti skti-cursor-hover-click',
				'css' => ['image-hover-effect'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				]
			],
			'event-calendar' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Event Calendar', 'skt-addons-elementor'),
				'icon' => 'skti skti-event-calendar',
				'css' => ['event-calendar'],
				'js' => [],
				'vendor' => [
					'css' => ['skt-fullcalendar'],
					'js' => ['skt-fullcalendar', 'skt-fullcalendar-locales'],
				],
			],
			'link-hover' => [
				'cat' => 'creative',
				'is_active' => true,
				'title' => __('Animated Link', 'skt-addons-elementor'),
				'icon' => 'skti skti-animated-link',
				'css' => ['link-hover'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'mailchimp' => [
				'cat' => 'forms',
				'is_active' => true,
				'title' => __('MailChimp', 'skt-addons-elementor'),
				'icon' => 'skti skti-mail-chimp',
				'css' => ['mailchimp'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'image-accordion' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Image Accordion', 'skt-addons-elementor'),
				'icon' => 'skti skti-slider-image',
				'css' => ['image-accordion'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'content-switcher' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Content Switcher', 'skt-addons-elementor'),
				'icon' => 'skti skti-switcher',
				'css' => ['content-switcher'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'image-stack-group' => [
				'cat' => 'creative',
				'is_active' => true,
				'title' => __('Image Stack Group', 'skt-addons-elementor'),
				'icon' => 'skti skti-lens',
				'css' => ['circle-image-group'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'creative-button' => [
				'cat' => 'creative',
				'is_active' => true,
				'title' => __('Creative Button', 'skt-addons-elementor'),
				'icon' => 'skti skti-motion-button',
				'css' => ['creative-button'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				]
			],
			'pdf-view' => [
				'cat' => 'creative',
				'is_active' => true,
				'title' => __('PDF View', 'skt-addons-elementor'),
				'icon' => 'skti skti-pdf2',
				'css' => ['pdf'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['pdf-js'],
				],
			],
			'comparison-table' => [
				'cat' => 'creative',
				'is_active' => true,
				'title' => __('Comparison Table', 'skt-addons-elementor'),
				'icon' => 'skti skti-scale',
				'css' => ['comparison-table'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],

//////////////////////////////////////////////////////////////////////////////////////////////////

			'accordion' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Advanced Accordion', 'skt-addons-elementor'),
				'icon' => 'skti skti-accordion-vertical',
				'css' => ['accordion'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'advanced-data-table' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Advanced Data Table', 'skt-addons-elementor'),
				'icon' => 'skti skti-data-table',
				'css' => ['advanced-data-table'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['data-table'],
				],
			],
			'advanced-heading' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Advanced Heading', 'skt-addons-elementor'),
				'icon' => 'skti skti-advanced-heading',
				'css' => ['advanced-heading'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'advanced-tabs' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Advanced Tabs', 'skt-addons-elementor'),
				'icon' => 'skti skti-tab',
				'css' => ['advanced-tabs'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'toggle' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Advanced Toggle', 'skt-addons-elementor'),
				'icon' => 'skti skti-accordion-vertical',
				'css' => ['toggle'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'breadcrumbs' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Breadcrumbs', 'skt-addons-elementor'),
				'icon' => 'skti skti-breadcrumbs',
				'css' => ['breadcrumbs'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'business-hour' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Business Hour', 'skt-addons-elementor'),
				'icon' => 'skti skti-hand-watch',
				'css' => ['business-hour'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'countdown' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Countdown', 'skt-addons-elementor'),
				'icon' => 'skti skti-refresh-time',
				'css' => ['countdown'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['jquery-countdown'],
				],
			],
			'feature-list' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Feature List', 'skt-addons-elementor'),
				'icon' => 'skti skti-list-2',
				'css' => ['feature-list'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'list-group' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('List Group', 'skt-addons-elementor'),
				'icon' => 'skti skti-list-group',
				'css' => ['list-group'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'modal-popup' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Modal Popup', 'skt-addons-elementor'),
				'icon' => 'skti skti-popup',
				'css' => ['modal-popup'],
				'js' => [],
				'vendor' => [
					'css' => ['animate-css'],
					'js' => [],
				],
			],
			'source-code' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Source Code', 'skt-addons-elementor'),
				'icon' => 'skti skti-code-browser',
				'css' => ['source-code'],
				'js' => [],
				'vendor' => [
					'css' => ['prism'],
					'js' => ['prism'],
				],
			],
			'sticky-video' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Sticky Video', 'skt-addons-elementor'),
				'icon' => 'skti skti-sticky-video',
				'css' => ['sticky-video'],
				'js' => [],
				'vendor' => [
					'css' => ['plyr'],
					'js' => ['plyr'],
				],
			],
			'timeline' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Timeline', 'skt-addons-elementor'),
				'icon' => 'skti skti-timeline',
				'css' => ['timeline'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['elementor-waypoints'],
				],
			],
			'flip-box' => [
				'cat' => 'creative',
				'is_active' => true,
				'title' => __('Flip Box', 'skt-addons-elementor'),
				'icon' => 'skti skti-flip-card1',
				'css' => ['flip-box'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'pricing-table' => [
				'cat' => 'marketing',
				'is_active' => true,
				'title' => __('Pricing Table', 'skt-addons-elementor'),
				'icon' => 'skti skti-file-cabinet',
				'css' => ['pricing-table'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'price-menu' => [
				'cat' => 'marketing',
				'is_active' => true,
				'title' => __('Price Menu', 'skt-addons-elementor'),
				'icon' => 'skti skti-menu-price',
				'css' => ['price-menu'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'advanced-slider' => [
				'cat' => 'slider-&-carousel',
				'is_active' => true,
				'title' => __('Advanced Slider', 'skt-addons-elementor'),
				'icon' => 'skti skti-slider',
				'css' => ['advanced-slider'],
				'js' => [],
				'vendor' => [
					'css' => ['skt-swiper'],
					'js' => ['skt-swiper'],
				],
			],
			'logo-carousel' => [
				'cat' => 'slider-&-carousel',
				'is_active' => true,
				'title' => __('Logo Carousel', 'skt-addons-elementor'),
				'icon' => 'skti skti-logo-carousel',
				'css' => ['logo-carousel'],
				'js' => [],
				'vendor' => [
					'css' => ['slick', 'slick-theme'],
					'js' => ['jquery-slick'],
				],
			],
			'team-carousel' => [
				'cat' => 'slider-&-carousel',
				'is_active' => true,
				'title' => __('Team Carousel', 'skt-addons-elementor'),
				'icon' => 'skti skti-team-carousel',
				'css' => ['team-carousel'],
				'js' => [],
				'vendor' => [
					'css' => ['slick', 'slick-theme'],
					'js' => ['jquery-slick'],
				],
			],
			'testimonial-carousel' => [
				'cat' => 'slider-&-carousel',
				'is_active' => true,
				'title' => __('Testimonial Carousel', 'skt-addons-elementor'),
				'icon' => 'skti skti-testimonial-carousel',
				'css' => ['testimonial-carousel'],
				'js' => [],
				'vendor' => [
					'css' => ['slick', 'slick-theme'],
					'js' => ['jquery-slick'],
				],
			],
			'animated-text' => [
				'cat' => 'creative',
				'is_active' => true,
				'title' => __('Animated Text', 'skt-addons-elementor'),
				'icon' => 'skti skti-text-animation',
				'css' => ['animated-text'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['animated-text'],
				],
			],
			'hotspots' => [
				'cat' => 'creative',
				'is_active' => true,
				'title' => __('Hotspots', 'skt-addons-elementor'),
				'icon' => 'skti skti-accordion-vertical',
				'css' => ['hotspots'],
				'js' => [],
				'vendor' => [
					'css' => ['tipso'],
					'js' => ['jquery-tipso'],
				],
			],
			'hover-box' => [
				'cat' => 'creative',
				'is_active' => true,
				'title' => __('Hover Box', 'skt-addons-elementor'),
				'icon' => 'skti skti-finger-point',
				'css' => ['hover-box'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'promo-box' => [
				'cat' => 'marketing',
				'is_active' => true,
				'title' => __('Promo Box', 'skt-addons-elementor'),
				'icon' => 'skti skti-promo',
				'css' => ['promo-box'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'image-scroller' => [
				'cat' => 'creative',
				'is_active' => true,
				'title' => __('Single Image Scroll', 'skt-addons-elementor'),
				'icon' => 'skti skti-image-scroll',
				'css' => ['image-scroller'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'off-canvas' => [
				'cat' => 'creative',
				'is_active' => true,
				'title' => __('Off Canvas', 'skt-addons-elementor'),
				'icon' => 'skti skti-offcanvas-menu',
				'css' => ['off-canvas'],
				'js' => [],
				'vendor' => [
					'css' => ['hamburgers'],
					'js' => [],
				]
			],
			'one-page-nav' => [
				'cat' => 'creative',
				'is_active' => true,
				'title' => __('One Page Nav', 'skt-addons-elementor'),
				'icon' => 'skti skti-dot-navigation',
				'css' => ['one-page-nav'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'scrolling-image' => [
				'cat' => 'creative',
				'is_active' => true,
				'title' => __('Scrolling Image', 'skt-addons-elementor'),
				'icon' => 'skti skti-scrolling-image',
				'css' => ['scrolling-image'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['jquery-keyframes'],
				],
			],
			'author-list' => [
				'cat' => 'post',
				'is_active' => true,
				'title' => __('Author List', 'skt-addons-elementor'),
				'icon' => 'skti skti-user-male',
				'css' => ['author-list'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'post-carousel' => [
				'cat' => 'post',
				'is_active' => true,
				'title' => __('Post Carousel', 'skt-addons-elementor'),
				'icon' => 'skti skti-graph-pie',
				'css' => ['post-carousel'],
				'js' => [],
				'vendor' => [
					'css' => ['slick', 'slick-theme'],
					'js' => ['jquery-slick'],
				],
			],
			'post-grid-new' => [
				'cat' => 'post',
				'is_active' => true,
				'title' => __('Post Grid', 'skt-addons-elementor'),
				'icon' => 'skti skti-post-grid',
				'css' => ['post-grid'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'post-tiles' => [
				'cat' => 'post',
				'is_active' => true,
				'title' => __('Post Tiles', 'skt-addons-elementor'),
				'icon' => 'skti skti-article',
				'css' => ['post-tiles'],
				'js' => [],
				'vendor' => [
					'css' => [],
				],
			],
			'smart-post-list' => [
				'cat' => 'post',
				'is_active' => true,
				'title' => __('Smart Post List', 'skt-addons-elementor'),
				'icon' => 'skti skti-post-list',
				'css' => ['smart-post-list'],
				'js' => [],
				'vendor' => [
					'css' => ['nice-select'],
					'js' => ['jquery-nice-select'],
				],
			],
			'line-chart' => [
				'cat' => 'chart',
				'is_active' => true,
				'title' => __('Line Chart', 'skt-addons-elementor'),
				'icon' => 'skti skti-line-graph-pointed',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['chart-js'],
				],
			],
			'pie-chart' => [
				'cat' => 'chart',
				'is_active' => true,
				'title' => __('Pie & Doughnut Chart', 'skt-addons-elementor'),
				'icon' => 'skti skti-graph-pie',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['chart-js'],
				],
			],
			'polar-chart' => [
				'cat' => 'chart',
				'is_active' => true,
				'title' => __('Polar Area Chart', 'skt-addons-elementor'),
				'icon' => 'skti skti-graph-pie',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['chart-js'],
				],
			],
			'radar-chart' => [
				'cat' => 'chart',
				'is_active' => true,
				'title' => __('Radar Chart', 'skt-addons-elementor'),
				'icon' => 'skti skti-graph-pie',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['chart-js'],
				],
			],
			'mini-cart' => [
				'cat' => 'woocommerce',
				'is_active' => true,
				'title' => __('Mini Cart', 'skt-addons-elementor'),
				'icon' => 'skti skti-mini-cart',
				'css' => ['mini-cart'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'product-carousel-new' => [
				'cat' => 'woocommerce',
				'is_active' => true,
				'title' => __('Product Carousel', 'skt-addons-elementor'),
				'icon' => 'skti skti-Product-Carousel',
				'css' => ['product-carousel', 'product-quick-view'],
				'js' => [],
				'vendor' => [
					'css' => ['slick', 'slick-theme'],
					'js' => ['jquery-slick'],
				]
			],
			'product-category-carousel-new' => [
				'cat' => 'woocommerce',
				'is_active' => true,
				'title' => __('Product Category Carousel', 'skt-addons-elementor'),
				'icon' => 'skti skti-Category-Carousel',
				'css' => ['product-category-carousel'],
				'js' => [],
				'vendor' => [
					'css' => ['slick', 'slick-theme'],
					'js' => ['jquery-slick'],
				],
			],
			'product-category-grid-new' => [
				'cat' => 'woocommerce',
				'is_active' => true,
				'title' => __('Product Category Grid', 'skt-addons-elementor'),
				'icon' => 'skti skti-Category-Carousel',
				'css' => ['product-category-grid'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'product-grid-new' => [
				'cat' => 'woocommerce',
				'is_active' => true,
				'title' => __('Product Grid', 'skt-addons-elementor'),
				'icon' => 'skti skti-Product-Grid',
				'css' => ['product-grid'],
				'js' => [],
				'vendor' => [
					'css' => ['elementor-icons-fa-solid'],
					'js' => [],
				],
			],
			'single-product-new' => [
				'cat' => 'woocommerce',
				'is_active' => true,
				'title' => __('Single Product', 'skt-addons-elementor'),
				'icon' => 'skti skti-product-list-single',
				'css' => ['single-product'],
				'js' => [],
				'vendor' => [
					'css' => ['elementor-icons-fa-solid'],
					'js' => [],
				],
			],
			'wc-cart' => [
				'cat' => 'woocommerce',
				'is_active' => true,
				'title' => __('WooCommerce Cart', 'skt-addons-elementor'),
				'icon' => 'skti skti-cart',
				'css' => ['wc-cart'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'wc-checkout' => [
				'cat' => 'woocommerce',
				'is_active' => true,
				'title' => __('WooCommerce Checkout', 'skt-addons-elementor'),
				'icon' => 'skti skti-checkout-2',
				'css' => ['wc-checkout'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['wc-checkout'],
				],
			],
			'age-gate' => [
				'cat' => 'general',
				'is_active' => true,
				'title' => __('Age Gate', 'skt-elementor-addons'),
				'icon' => 'skti skti-age-gate',
				'css' => ['age-gate'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'image-swap' => [
				'cat' => 'general',
				'title' => __( 'Image Swap', 'skt-elementor-addons' ),
				'icon' => 'skti skti-image-scroll',
				'css' => ['image-swap'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				]
			],
			'archive-title' => [
				'cat' => 'theme-builder',
				'is_active' => true,
				'title' => __('Archive Title', 'skt-elementor-addons'),
				'icon' => 'skti skti-tb-archieve-title',
				'css' => [''],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'author-meta' => [
				'cat' => 'theme-builder',
				'is_active' => true,
				'title' => __('Author Meta', 'skt-elementor-addons'),
				'icon' => 'skti skti-tb-author-meta',
				'css' => ['author'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'page-title' => [
				'cat' => 'theme-builder',
				'is_active' => false,
				'title' => __('Page Title', 'skt-elementor-addons'),
				'icon' => 'skti skti-tb-page-title',
				'css' => [''],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'post-comments' => [
				'cat' => 'theme-builder',
				'is_active' => true,
				'title' => __('Post Comments', 'skt-elementor-addons'),
				'icon' => 'skti skti-comment-square',
				'css' => [''],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'post-content' => [
				'cat' => 'theme-builder',
				'is_active' => true,
				'title' => __('Post Content', 'skt-elementor-addons'),
				'icon' => 'skti skti-tb-post-content',
				'css' => [''],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'post-excerpt' => [
				'cat' => 'theme-builder',
				'is_active' => false,
				'title' => __('Post Excerpt', 'skt-elementor-addons'),
				'icon' => 'skti skti-tb-post-excerpt',
				'css' => [''],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'post-featured-image' => [
				'cat' => 'theme-builder',
				'is_active' => true,
				'title' => __('Post featured image', 'skt-elementor-addons'),
				'icon' => 'skti skti-tb-featured-image',
				'css' => [''],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'post-info' => [
				'cat' => 'theme-builder',
				'is_active' => true,
				'title' => __('Post Meta', 'skt-elementor-addons'),
				'icon' => 'skti skti-tb-post-info',
				'css' => ['post-info'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'post-navigation' => [
				'cat' => 'theme-builder',
				'is_active' => true,
				'title' => __('Post Navigation', 'skt-elementor-addons'),
				'icon' => 'skti skti-breadcrumbs',
				'css' => ['post-navigation'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'post-title' => [
				'cat' => 'theme-builder',
				'is_active' => true,
				'title' => __('Post Title', 'skt-elementor-addons'),
				'icon' => 'skti skti-tb-page-title',
				'css' => [''],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'site-logo' => [
				'cat' => 'theme-builder',
				'is_active' => true,
				'title' => __('Site Logo', 'skt-elementor-addons'),
				'icon' => 'skti skti-tb-site-logo',
				'css' => [''],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'site-tagline' => [
				'cat' => 'theme-builder',
				'is_active' => true,
				'title' => __('Site Tagline', 'skt-elementor-addons'),
				'icon' => 'skti skti-tag',
				'css' => ['site-tagline'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'site-title' => [
				'cat' => 'theme-builder',
				'is_active' => true,
				'title' => __('Site Title', 'skt-elementor-addons'),
				'icon' => 'skti skti-tb-site-title',
				'css' => ['site-title'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'lordicon'            => [
				'cat'       => 'creative',
				'is_active' => true,
				'title'     => __('LordIcon', 'skt-elementor-addons'),
				'icon'      => 'skti skti-icon-box',
				'css'       => ['lordicon'],
				'js'        => [],
				'vendor'    => [
					'css' => [],
					'js'  => ['lord-icon'],
				],
			],
			'unfold' => [
				'cat' => 'general',
				'title' => __( 'Unfold', 'skt-elementor-addons' ),
				'icon' => 'skti skti-unfold-paper',
				'css' => ['unfold'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				]
			],
			'creative-slider' => [
				'cat' => 'slider-&-carousel',
				'title' => __('Creative Slider', 'skt-elementor-addons'),
				'icon' => 'skti skti-slider',
				'css' => ['creative-slider'],
				'js' => [],
				'vendor' => [
					'css' => ['owl-carousel', 'owl-theme-default', 'animate'],
					'js' => ['owl-carousel-js'],
				],
			],
			'table-of-contents' => [
				'cat' => 'general',
				'title' => __('Table of Contents', 'skt-elementor-addons'),
				'icon' => 'skti skti-list-2',
				'css' => ['table-of-contents'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['ha-toc'],
				],
			],
			'edd-cart' => [
				'cat' => 'easy-digital-downloads',
				'title' => __('EDD Cart', 'skt-elementor-addons'),
				'icon' => 'skti skti-cart',
				'css' => ['edd-cart'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'edd-checkout' => [
				'cat' => 'easy-digital-downloads',
				'title' => __('EDD Checkout', 'skt-elementor-addons'),
				'icon' => 'skti skti-checkout-2',
				'css' => ['edd-checkout'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'edd-download' => [
				'cat' => 'easy-digital-downloads',
				'title' => __('EDD Download', 'skt-elementor-addons'),
				'icon' => 'skti skti-Download-circle',
				'css' => ['edd-purchase'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'edd-login' => [
				'cat' => 'easy-digital-downloads',
				'title' => __('EDD Login', 'skt-elementor-addons'),
				'icon' => 'skti skti-checkout-2',
				'css' => ['edd-login'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'edd-purchase' => [
				'cat' => 'easy-digital-downloads',
				'title' => __('EDD Purchase', 'skt-elementor-addons'),
				'icon' => 'skti skti-user-plus',
				'css' => ['edd-purchase'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'edd-register' => [
				'cat' => 'easy-digital-downloads',
				'title' => __('EDD Register', 'skt-elementor-addons'),
				'icon' => 'skti skti-user-plus',
				'css' => ['edd-register'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'edd-category-carousel' => [
				'cat' => 'easy-digital-downloads',
				'title' => __('EDD Category Carousel', 'skt-elementor-addons'),
				'icon' => 'skti skti-Category-Carousel',
				'css' => ['edd-category-carousel'],
				'js' => [],
				'vendor' => [
					'css' => ['slick', 'slick-theme'],
					'js' => ['jquery-slick'],
				],
			],
			'edd-category-grid' => [
				'cat' => 'easy-digital-downloads',
				'title' => __('EDD Category Grid', 'skt-elementor-addons'),
				'icon' => 'skti skti-Category-Carousel',
				'css' => ['product-category-grid'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'edd-product-carousel' => [
				'cat' => 'easy-digital-downloads',
				'title' => __('EDD Product Carousel', 'skt-elementor-addons'),
				'icon' => 'skti skti-Product-Carousel',
				'css' => ['edd-product-carousel', 'edd-quick-view'],
				'js' => [],
				'vendor' => [
					'css' => ['slick', 'slick-theme', 'magnific-popup'],
					'js' => ['jquery-slick', 'jquery-magnific-popup'],
				]
			],
			'edd-product-grid' => [
				'cat' => 'easy-digital-downloads',
				'title' => __('EDD Product Grid', 'skt-elementor-addons'),
				'icon' => 'skti skti-product-grid',
				'css' => ['edd-product-grid', 'edd-quick-view'],
				'js' => [],
				'vendor' => [
					'css' => ['elementor-icons-fa-solid', 'magnific-popup'],
					'js' => ['jquery-magnific-popup'],
				],
			],
			'edd-single-product' => [
				'cat' => 'easy-digital-downloads',
				'title' => __('EDD Single Product', 'skt-elementor-addons'),
				'icon' => 'skti skti-Category-Carousel',
				'css' => ['edd-single-product', 'edd-quick-view'],
				'js' => [],
				'vendor' => [
					'css' => ['elementor-icons-fa-solid', 'magnific-popup'],
					'js' => ['jquery-magnific-popup'],
				],
			],
			'photo-stack'         => [
				'cat'       => 'creative',
				'is_active' => true,
				'title'     => __('Photo Stack', 'skt-elementor-addons'),
				'icon'      => 'skti skti-lens',
				'css'       => ['photo-stack'],
				'js'        => [],
				'vendor'    => [
					'css' => [],
					'js'  => [],
				],
			],			
		];
	}

	public static function get_base_widget_key()
	{
		return apply_filters('sktaddonselementor_get_base_widget_key', '_sktaddonselementor_base');
	}

	public static function get_default_active_widget()
	{
		$default_active = array_filter(self::get_local_widgets_map(), function ($var) {
			return $var['is_active'] == true;
		});
		return array_keys($default_active);
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0
	 *
	 * @access public
	 */
	public static function register()
	{
		include_once(SKT_ADDONS_ELEMENTOR_DIR_PATH . 'base/widget-base.php');
		include_once(SKT_ADDONS_ELEMENTOR_DIR_PATH . 'traits/lazy-query-builder.php');
		include_once(SKT_ADDONS_ELEMENTOR_DIR_PATH . 'traits/button-renderer.php');
		include_once(SKT_ADDONS_ELEMENTOR_DIR_PATH . 'traits/link-hover-markup.php');
		include_once(SKT_ADDONS_ELEMENTOR_DIR_PATH . 'traits/creative-button-markup.php');
		include_once(SKT_ADDONS_ELEMENTOR_DIR_PATH . 'traits/lazy-query-builder.php');

		$inactive_widgets = self::get_inactive_widgets();

		foreach (self::get_local_widgets_map() as $widget_key => $data) {
			if (!in_array($widget_key, $inactive_widgets)) {
				self::register_widget($widget_key);
			}
		}
	}

	protected static function register_widget($widget_key)
	{
		$widget_file = SKT_ADDONS_ELEMENTOR_DIR_PATH . 'widgets/' . $widget_key . '/widget.php';

		if (is_readable($widget_file)) {

			include_once($widget_file);

			$widget_class = '\Skt_Addons_Elementor\Elementor\Widget\\' . str_replace('-', '_', $widget_key);
			if (class_exists($widget_class)) {
				skt_addons_elementor()->widgets_manager->register_widget_type(new $widget_class);
			}
		}
	}
}

Widgets_Manager::init();