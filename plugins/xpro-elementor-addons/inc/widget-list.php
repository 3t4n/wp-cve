<?php

namespace XproElementorAddons\Inc;

use XproElementorAddons\Libs\Xpro_Elementor_Dashboard;
use XproElementorAddonsPro\Inc\Xpro_Elementor_Widget_Pro_List;
use XproElementorAddonsPro\Libs\Xpro_Elementor_License;

defined( 'ABSPATH' ) || exit;

class Xpro_Elementor_Widget_List {


	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var Xpro_Elementor_Widget_List The single instance of the class.
	 */

	private static $instance = null;
	private static $list     = array(
		'heading'                 => array(
			'slug'    => 'heading',
			'title'   => 'Heading',
			'package' => 'free',
		),
		'auto-content'            => array(
			'slug'    => 'auto_content',
			'title'   => 'AI Content',
			'package' => 'free',
		),
		'icon-box'                => array(
			'slug'    => 'icon_box',
			'title'   => 'Icon Box',
			'package' => 'free',
		),
		'simple-gallery'          => array(
			'slug'    => 'simple_gallery',
			'title'   => 'Simple Gallery',
			'package' => 'free',
		),
		'simple-portfolio'        => array(
			'slug'    => 'simple_portfolio',
			'title'   => 'Simple Portfolio',
			'package' => 'free',
		),
		'pricing'                 => array(
			'slug'    => 'pricing',
			'title'   => 'Pricing',
			'package' => 'free',
		),
		'info-list'               => array(
			'slug'    => 'info_list',
			'title'   => 'Info List',
			'package' => 'free',
		),
		'lottie'                  => array(
			'slug'    => 'lottie',
			'title'   => 'Lottie',
			'package' => 'free',
		),
		'progress-bar'            => array(
			'slug'    => 'progress_bar',
			'title'   => 'Progress Bar',
			'package' => 'free',
		),
		'pie-chart'               => array(
			'slug'    => 'pie_chart',
			'title'   => 'Pie Chart',
			'package' => 'free',
		),
		'counter'                 => array(
			'slug'    => 'counter',
			'title'   => 'Counter',
			'package' => 'free',
		),
		'button'                  => array(
			'slug'    => 'button',
			'title'   => 'Button',
			'package' => 'free',
		),
		'horizontal-menu'         => array(
			'slug'    => 'horizontal_menu',
			'title'   => 'Horizontal Menu',
			'package' => 'free',
		),
		'team'                    => array(
			'slug'    => 'team',
			'title'   => 'Team',
			'package' => 'free',
		),
		'wp-forms'                => array(
			'slug'    => 'wp_forms',
			'title'   => 'WP Forms',
			'package' => 'free',
		),
		'before-after'            => array(
			'slug'    => 'before_after',
			'title'   => 'Before After',
			'package' => 'free',
		),
		'testimonial'             => array(
			'slug'    => 'testimonial',
			'title'   => 'Testimonial',
			'package' => 'free',
		),
		'logo-grid'               => array(
			'slug'    => 'logo_grid',
			'title'   => 'Logo Grid',
			'package' => 'free',
		),
		'social-icon'             => array(
			'slug'    => 'social_icon',
			'title'   => 'Social Icon',
			'package' => 'free',
		),
		'social-share'            => array(
			'slug'    => 'social_share',
			'title'   => 'Social Share',
			'package' => 'free',
		),
		'content-toggle'          => array(
			'slug'    => 'content_toggle',
			'title'   => 'Content Toggle',
			'package' => 'free',
		),
		'news-ticker'             => array(
			'slug'    => 'news_ticker',
			'title'   => 'News Ticker',
			'package' => 'free',
		),
		'step-flow'               => array(
			'slug'    => 'step_flow',
			'title'   => 'Step Flow',
			'package' => 'free',
		),
		'contact-form-7'          => array(
			'slug'    => 'contact_form_7',
			'title'   => 'Contact Form 7',
			'package' => 'free',
		),
		'table'                   => array(
			'slug'    => 'table',
			'title'   => 'Table',
			'package' => 'free',
		),
		'site-title'              => array(
			'slug'    => 'site_title',
			'title'   => 'Site Title',
			'package' => 'free',
		),
		'site-logo'               => array(
			'slug'    => 'site_logo',
			'title'   => 'Site Logo',
			'package' => 'free',
		),
		'page-title'              => array(
			'slug'    => 'page_title',
			'title'   => 'Page Title',
			'package' => 'free',
		),
		'post-title'              => array(
			'slug'    => 'post_title',
			'title'   => 'Post Title',
			'package' => 'free',
		),
		'featured-image'          => array(
			'slug'    => 'featured_image',
			'title'   => 'Featured Image',
			'package' => 'free',
		),
		'post-content'            => array(
			'slug'    => 'post_content',
			'title'   => 'Post Content',
			'package' => 'free',
		),
		'author-box'              => array(
			'slug'    => 'author_box',
			'title'   => 'Author Box',
			'package' => 'free',
		),
		'post-grid'               => array(
			'slug'    => 'post_grid',
			'title'   => 'Post Grid',
			'package' => 'free',
		),
		'taxonomy'                => array(
			'slug'    => 'taxonomy',
			'title'   => 'Taxonomy',
			'package' => 'free',
		),
		'hot-spot'                => array(
			'slug'    => 'hot_spot',
			'title'   => 'Hot Spot',
			'package' => 'free',
		),
		'drop-cap'                => array(
			'slug'    => 'drop_cap',
			'title'   => 'Drop Cap',
			'package' => 'free',
		),
		'block-quote'             => array(
			'slug'    => 'block_quote',
			'title'   => 'Block Quote',
			'package' => 'free',
		),
		'ninja-forms'             => array(
			'slug'    => 'ninja_forms',
			'title'   => 'Ninja Forms',
			'package' => 'free',
		),
		'image-scroller'          => array(
			'slug'    => 'image_scroller',
			'title'   => 'Image Scroller',
			'package' => 'free',
		),
		'business-hours'          => array(
			'slug'    => 'business_hours',
			'title'   => 'Business Hours',
			'package' => 'free',
		),
		'horizontal-timeline'     => array(
			'slug'    => 'horizontal_timeline',
			'title'   => 'Timeline Horizontal',
			'package' => 'free',
		),
		'gravity-forms'           => array(
			'slug'    => 'gravity_forms',
			'title'   => 'Gravity Forms',
			'package' => 'free',
		),
		'contact-form'            => array(
			'slug'    => 'contact_form',
			'title'   => 'Contact Form',
			'package' => 'free',
		),
		'promo-box'               => array(
			'slug'    => 'promo_box',
			'title'   => 'Promo Box',
			'package' => 'free',
		),
		'search'                  => array(
			'slug'    => 'search',
			'title'   => 'Search',
			'package' => 'free',
		),
		'hero-slider'             => array(
			'slug'    => 'hero_slider',
			'title'   => 'Hero Slider',
			'package' => 'free',
		),
		'woo-product-title'       => array(
			'slug'    => 'woo_product_title',
			'title'   => 'Product Title',
			'package' => 'free',
		),
		'woo-product-description' => array(
			'slug'    => 'woo_product_description',
			'title'   => 'Product Description',
			'package' => 'free',
		),
		'woo-product-price'       => array(
			'slug'    => 'woo_product_price',
			'title'   => 'Product Price',
			'package' => 'free',
		),
		'woo-product-images'      => array(
			'slug'    => 'woo_product_images',
			'title'   => 'Product Images',
			'package' => 'free',
		),
		'woo-product-rating'      => array(
			'slug'    => 'woo_product_rating',
			'title'   => 'Product Rating',
			'package' => 'free',
		),
		'woo-add-to-cart'         => array(
			'slug'    => 'woo_add_to_cart',
			'title'   => 'Wo AddToCart',
			'package' => 'free',
		),
		'woo-product-grid'        => array(
			'slug'    => 'woo_product_grid',
			'title'   => 'Woo Product Grid',
			'package' => 'free',
		),
		'custom-field'            => array(
			'slug'    => 'custom_field',
			'title'   => 'Custom Field',
			'package' => 'free',
		),
		'template'                => array(
			'slug'    => 'template',
			'title'   => 'Template',
			'package' => 'free',
		),
		'donation-form-grid'      => array(
			'slug'    => 'donation_form_grid',
			'title'   => 'Donation Form Grid',
			'package' => 'free',
		),
		'course-grid'             => array(
			'slug'    => 'course_grid',
			'title'   => 'Course Grid',
			'package' => 'free',
		),
		'animated-link'           => array(
			'slug'    => 'animated_link',
			'title'   => 'Animated Link',
			'package' => 'free',
		),
		'advance-gallery'         => array(
			'slug'    => 'advance_gallery',
			'title'   => 'Advanced Gallery',
			'package' => 'pro-disabled',
		),
		'carousel-gallery'        => array(
			'slug'    => 'carousel_gallery',
			'title'   => 'Carousel Gallery',
			'package' => 'pro-disabled',
		),
		'advance-portfolio'       => array(
			'slug'    => 'advance_portfolio',
			'title'   => 'Advanced Portfolio',
			'package' => 'pro-disabled',
		),
		'carousel-portfolio'      => array(
			'slug'    => 'carousel_portfolio',
			'title'   => 'Carousel Portfolio',
			'package' => 'pro-disabled',
		),
		'advanced-posts'          => array(
			'slug'    => 'advanced_posts',
			'title'   => 'Advanced Posts',
			'package' => 'pro-disabled',
		),
		'list-portfolio'          => array(
			'slug'    => 'list_portfolio',
			'title'   => 'List Portfolio',
			'package' => 'pro-disabled',
		),
		'advance-heading'         => array(
			'slug'    => 'advance_heading',
			'title'   => 'Advanced Heading',
			'package' => 'pro-disabled',
		),
		'animated-heading'        => array(
			'slug'    => 'animated_heading',
			'title'   => 'Animated Headline',
			'package' => 'pro-disabled',
		),
		'image-masking'           => array(
			'slug'    => 'image_masking',
			'title'   => 'Image Masking',
			'package' => 'pro-disabled',
		),
		'advance-tabs'            => array(
			'slug'    => 'advance_tabs',
			'title'   => 'Advanced Tabs',
			'package' => 'pro-disabled',
		),
		'advance-accordion'       => array(
			'slug'    => 'advance_accordion',
			'title'   => 'Advanced Accordion',
			'package' => 'pro-disabled',
		),
		'pricing-carousel'        => array(
			'slug'    => 'pricing_carousel',
			'title'   => 'Pricing Carousel',
			'package' => 'pro-disabled',
		),
		'pricing-matrix'          => array(
			'slug'    => 'pricing_matrix',
			'title'   => 'Pricing Matrix',
			'package' => 'pro-disabled',
		),
		'info-box'                => array(
			'slug'    => 'info_box',
			'title'   => 'Info Box',
			'package' => 'pro-disabled',
		),
		'dual-button'             => array(
			'slug'    => 'dual_button',
			'title'   => 'Dual Button',
			'package' => 'pro-disabled',
		),
		'vertical-menu'           => array(
			'slug'    => 'vertical_menu',
			'title'   => 'Vertical Menu',
			'package' => 'pro-disabled',
		),
		'hamburger'               => array(
			'slug'    => 'hamburger',
			'title'   => 'Hamburger',
			'package' => 'pro-disabled',
		),
		'product-view-360'        => array(
			'slug'    => 'product_view_360',
			'title'   => '360° Product View',
			'package' => 'pro-disabled',
		),
		'slider'                  => array(
			'slug'    => 'slider',
			'title'   => 'Multi Layer Slider',
			'package' => 'pro-disabled',
		),
		'team-carousel'           => array(
			'slug'    => 'team_carousel',
			'title'   => 'Team Carousel',
			'package' => 'pro-disabled',
		),
		'testimonial-carousel'    => array(
			'slug'    => 'testimonial_carousel',
			'title'   => 'Testimonial Carousel',
			'package' => 'pro-disabled',
		),
		'logo-carousel'           => array(
			'slug'    => 'logo_carousel',
			'title'   => 'Logo Carousel',
			'package' => 'pro-disabled',
		),
		'hover-card'              => array(
			'slug'    => 'hover_card',
			'title'   => 'Hover Card',
			'package' => 'pro-disabled',
		),
		'countdown'               => array(
			'slug'    => 'countdown',
			'title'   => 'Countdown',
			'package' => 'pro-disabled',
		),
		'flip-box'                => array(
			'slug'    => 'flip_box',
			'title'   => 'Flip Box',
			'package' => 'pro-disabled',
		),
		'post-meta'               => array(
			'slug'    => 'post_meta',
			'title'   => 'Post Meta',
			'package' => 'pro-disabled',
		),
		'post-comments'           => array(
			'slug'    => 'post_comments',
			'title'   => 'Post Comments',
			'package' => 'pro-disabled',
		),
		'post-navigation'         => array(
			'slug'    => 'post_navigation',
			'title'   => 'Post Navigation',
			'package' => 'pro-disabled',
		),
		'post-carousel'           => array(
			'slug'    => 'post_carousel',
			'title'   => 'Post Carousel',
			'package' => 'pro-disabled',
		),
		'post-list'               => array(
			'slug'    => 'post_list',
			'title'   => 'Post List',
			'package' => 'pro-disabled',
		),
		'post-tiles'              => array(
			'slug'    => 'post_tiles',
			'title'   => 'Post Tiles',
			'package' => 'pro-disabled',
		),
		'draw-svg'                => array(
			'slug'    => 'draw_svg',
			'title'   => 'Draw SVG',
			'package' => 'pro-disabled',
		),
		'modal-popup'             => array(
			'slug'    => 'modal_popup',
			'title'   => 'Modal Popup',
			'package' => 'pro-disabled',
		),
		'breadcrumb'              => array(
			'slug'    => 'breadcrumb',
			'title'   => 'Breadcrumb',
			'package' => 'pro-disabled',
		),
		'restaurant-menu'         => array(
			'slug'    => 'restaurant_menu',
			'title'   => 'Restaurant Menu',
			'package' => 'pro-disabled',
		),
		'image-accordion'         => array(
			'slug'    => 'image_accordion',
			'title'   => 'Image Accordion',
			'package' => 'pro-disabled',
		),
		'device-slider'           => array(
			'slug'    => 'device_slider',
			'title'   => 'Device Slider',
			'package' => 'pro-disabled',
		),
		'google-map'              => array(
			'slug'    => 'google_map',
			'title'   => 'Google Map',
			'package' => 'pro-disabled',
		),
		'street-map'              => array(
			'slug'    => 'street_map',
			'title'   => 'Street Map',
			'package' => 'pro-disabled',
		),
		'calendly'                => array(
			'slug'    => 'calendly',
			'title'   => 'Calendly',
			'package' => 'pro-disabled',
		),
		'vertical-timeline'       => array(
			'slug'    => 'vertical_timeline',
			'title'   => 'Vertical Timeline',
			'package' => 'pro-disabled',
		),
		'creative-button'         => array(
			'slug'    => 'creative_button',
			'title'   => 'Creative Button',
			'package' => 'pro-disabled',
		),
		'slide-anything'          => array(
			'slug'    => 'slide_anything',
			'title'   => 'Slide Anything',
			'package' => 'pro-disabled',
		),
		'unfold'                  => array(
			'slug'    => 'unfold',
			'title'   => 'Unfold',
			'package' => 'pro-disabled',
		),
		'scroll-to-top'           => array(
			'slug'    => 'scroll_to_top',
			'title'   => 'Scroll To Top',
			'package' => 'pro-disabled',
		),
		'cookies'                 => array(
			'slug'    => 'cookies',
			'title'   => 'Cookies',
			'package' => 'pro-disabled',
		),
		'alert-box'               => array(
			'slug'    => 'alert_box',
			'title'   => 'Alert Box',
			'package' => 'pro-disabled',
		),
		'woo-product-meta'        => array(
			'slug'    => 'xpro_elementor_woo_product_meta',
			'title'   => 'Product Meta',
			'package' => 'pro-disabled',
		),
		'woo-product-tabs'        => array(
			'slug'    => 'xpro_elementor_woo_product_tabs',
			'title'   => 'Product Tabs',
			'package' => 'pro-disabled',
		),
		'woo-cart'                => array(
			'slug'    => 'xpro_elementor_woo_cart',
			'title'   => 'Woo Cart',
			'package' => 'pro-disabled',
		),
		'preloader'               => array(
			'slug'    => 'preloader',
			'title'   => 'Preloader',
			'package' => 'pro-disabled',
		),
		'video'                   => array(
			'slug'    => 'video',
			'title'   => 'Video',
			'package' => 'pro-disabled',
		),
		'lightbox'                => array(
			'slug'    => 'lightbox',
			'title'   => 'Lightbox',
			'package' => 'pro-disabled',
		),
		'woo-product-carousel'    => array(
			'slug'    => 'xpro_elementor_woo_product_carousel',
			'title'   => 'Woo Products Carousel',
			'package' => 'pro-disabled',
		),
		'ajax-live-search'        => array(
			'slug'    => 'xpro_elementor_ajax_live_search',
			'title'   => 'Ajax Live Search',
			'package' => 'pro-disabled',
		),
		'one-page-navigation'     => array(
			'slug'    => 'one_page_navigation',
			'title'   => 'One Page Navigation',
			'package' => 'pro-disabled',
		),
		'source-code'             => array(
			'slug'    => 'source_code',
			'title'   => 'Source Code',
			'package' => 'pro-disabled',
		),
		'image-magnify'           => array(
			'slug'    => 'image_magnify',
			'title'   => 'Image Magnify',
			'package' => 'pro-disabled',
		),
		'instagram-feed'          => array(
			'slug'    => 'instagram_feed',
			'title'   => 'Instagram Feed',
			'package' => 'pro-disabled',
		),
		'facebook-feed'           => array(
			'slug'    => 'facebook_feed',
			'title'   => 'Facebook Feed',
			'package' => 'pro-disabled',
		),
		'woo-category'            => array(
			'slug'    => 'woo_category',
			'title'   => 'Woo Category Grid',
			'package' => 'pro-disabled',
		),
		'woo-category-carousel'   => array(
			'slug'    => 'woo_category_carousel',
			'title'   => 'Woo Category Carousel',
			'package' => 'pro-disabled',
		),
		'woo-mini-cart'           => array(
			'slug'    => 'woo_mini_cart',
			'title'   => 'Woo Mini Cart',
			'package' => 'pro-disabled',
		),
		'woo-user-profile'        => array(
			'slug'    => 'woo_user_profile',
			'title'   => 'Woo User Profile',
			'package' => 'pro-disabled',
		),
		'woo-my-account'          => array(
			'slug'    => 'woo_my_account',
			'title'   => 'Woo My Account',
			'package' => 'pro-disabled',
		),
		'woo-product-filters'     => array(
			'slug'    => 'woo_product_filters',
			'title'   => 'Woo Products Filter',
			'package' => 'pro-disabled',
		),
		'woo-checkout'            => array(
			'slug'    => 'woo_checkout',
			'title'   => 'Woo Checkout',
			'package' => 'pro-disabled',
		),
		'woo-notices'             => array(
			'slug'    => 'woo_notices',
			'title'   => 'Woo Notices',
			'package' => 'pro-disabled',
		),
		'split-slider'            => array(
			'slug'    => 'split_slider',
			'title'   => 'Split Slider',
			'package' => 'pro-disabled',
		),
		'text-marquee'            => array(
			'slug'    => 'text_marquee',
			'title'   => 'Text Marquee',
			'package' => 'pro-disabled',
		),
		'image-marquee'           => array(
			'slug'    => 'image_marquee',
			'title'   => 'Image Marquee',
			'package' => 'pro-disabled',
		),
		'textual-showcase'        => array(
			'slug'    => 'textual_showcase',
			'title'   => 'Textual Showcase',
			'package' => 'pro-disabled',
		),
		'audio-player'            => array(
			'slug'    => 'audio_player',
			'title'   => 'Audio Player',
			'package' => 'pro-disabled',
		),
		'coupon-code'             => array(
			'slug'    => 'coupon_code',
			'title'   => 'Coupon Code',
			'package' => 'pro-disabled',
		),
		'loop-builder'            => array(
			'slug'    => 'loop_builder',
			'title'   => 'Loop Builder',
			'package' => 'pro-disabled',
		),
		'video-gallery'           => array(
			'slug'    => 'video_gallery',
			'title'   => 'Video Gallery',
			'package' => 'pro-disabled',
		),
		'mailchimp'               => array(
			'slug'    => 'mailchimp',
			'title'   => 'MailChimp',
			'package' => 'pro-disabled',
		),
		'loop-carousel'           => array(
			'slug'    => 'loop_carousel',
			'title'   => 'Loop Carousel',
			'package' => 'pro-disabled',
		),
		'remote-arrows'           => array(
			'slug'    => 'remote_arrows',
			'title'   => 'Remote Arrows',
			'package' => 'pro-disabled',
		),
		'video-carousel'          => array(
			'slug'    => 'video_carousel',
			'title'   => 'Video Carousel',
			'package' => 'pro-disabled',
		),
		'flip-book-3d'            => array(
			'slug'    => 'flip_book_3d',
			'title'   => '3D Flip book',
			'package' => 'pro-disabled',
		),
	);

	/**
	 * Check if a widget is active or not, free package are considered inactive
	 *
	 *
	 * @param $widget - widget slug
	 *
	 * @return bool
	 */
	public function is_active( $widget ) {

		$act = self::instance()->get_list( true, $widget, 'active' );

		return empty( $act['package'] ) ? false : ( ( 'free' === $act['package'] || 'pro-disabled' === $act['package'] ) );
	}

	/**
	 *
	 * Usage :
	 *  get full list >> get_list() []
	 *  get full list of active widgets >> get_list(true, '', 'active') // []
	 *  get specific widget data >> get_list(true, 'image-accordion') [] or false
	 *  get specific widget data (if active) >> get_list(true, 'image-accordion', 'active') [] or false
	 *
	 * @param bool $filtered
	 * @param string $widget
	 * @param string $check_method - active|list
	 *
	 * @return array|bool|mixed
	 */
	public function get_list( $filtered = true, $widget = '', $check_method = 'list' ) {
		$all_list = self::$list;

		if ( true === $filtered ) {
			$all_list = apply_filters( 'xpro_elementor_addons_widgets_list', self::$list );
		}

		if ( did_action( 'xpro_elementor_addons_pro_loaded' ) && class_exists( '\XproElementorAddonsPro\Libs\Xpro_Elementor_License' ) && 'valid' === Xpro_Elementor_License::$license_activate ) {
			$widget_pro = Xpro_Elementor_Widget_Pro_List::instance()->get_list();
			$all_list   = array_merge( $all_list, $widget_pro );
		}

		if ( 'active' === $check_method ) {
			$active_list = Xpro_Elementor_Dashboard::instance()->utils->get_option( 'xpro_elementor_widget_list', array_keys( $all_list ) );

			foreach ( $all_list as $widget_slug => $info ) {
				if ( is_array( $active_list ) && ! in_array( $widget_slug, $active_list, true ) ) {
					unset( $all_list[ $widget_slug ] );
				}
			}
		}

		if ( '' !== $widget ) {
			return ( isset( $all_list[ $widget ] ) ? $all_list[ $widget ] : false );
		}

		return $all_list;
	}

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Xpro_Elementor_Widget_List An instance of the class.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
