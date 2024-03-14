<?php
namespace Enteraddons\Inc;

/**
 * Enteraddons admin class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

if( !defined( 'WPINC' ) ) {
    die;
}

if( !class_exists('Widgets_List') ) {

class Widgets_List extends \Enteraddons\Core\Base\Elements_Map {

    public function getElements() {
        return [
            'pro_list' => $this->widgets_list_pro(),
            'Lite_list' => $this->widgets_list_lite()
        ];
    } 

    public static function getAllWidgets() {
        return array_merge( self::widgets_list_lite(), self::widgets_list_pro() );
    }

	/**
     * Pro version widget lists
     *
     *
     */
	public static function widgets_list_pro() {
        $wiggets = [
            [
                'label'     => esc_html__( 'Accordion Tab', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-accordion-tab',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Advanced Data Table', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-advance-data-table',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Marquee Image', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-marquee-image',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Team Carousel', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-team-carousel',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Product Category Carousel', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-category-carousel',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Product Category Grid', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-category-grid',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Product Single Category', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-single-category',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Product Grid', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-product-grid',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Photo Frame', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-photo-frame',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Source Code', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-source-code',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Mini Cart', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-mini-cart',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Masonry Gallery', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-masonry-gallery',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Image Swap', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-image-swap',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( '360d Product Viewer', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-product-viewer-360d',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Iframe', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-iframe',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Panorama Viewer', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-panorama',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Image Hover Effect', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-image-hover-effect',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Domain Search', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-domain-search',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Comparison Table', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-comparison-table',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Modal Popup', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Banner Slider', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Bar Chart', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Creative Button', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Infobox Carousel', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Line Chart', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'PDF Viewer', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Pie Chart', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Polar Chart', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Post Carousel', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Promo Box', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'QR and Barcode', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Radar Chart', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Single Image Scroll', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'unfold', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Circle Info Graphic', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'NFT Carousel', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Filterable Gallery', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'NFT Gallery', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-modal-popup',
                'demo_link' => '#',
                'is_pro'    => true
            ]
        ];
        return apply_filters( 'ea_pro_widgets_list', $wiggets );
	}

    /**
     * Lite version widget lists
     *
     * Name is required 
     * If name more than 1 word it's should be concatenates with _  like ( heading_option )
     *
     */
    public static function widgets_list_lite() {

        $wiggets = [
            [
                'label'     => esc_html__( 'Accordion', 'enteraddons' ),
                'name'      => 'accordion',
                'icon'      => 'entera entera-accordion',
                'demo_link' => '#'
            ],
            [
                'label'     => esc_html__( 'Accordion Gallery', 'enteraddons' ),
                'name'      => 'accordion_gallery',
                'icon'      => 'entera entera-accordion-gallery',
                'demo_link' => '#'
            ],
            [
                'label'     => esc_html__( 'Advanced Tabs', 'enteraddons' ),
                'name'      => 'advanced_tabs',
                'icon'      => 'entera entera-advance-tab',
                'demo_link' => '#'
            ],
            [
                'label'     => esc_html__( 'Animation Title', 'enteraddons' ),
                'name'      => 'advanced_animation_title',
                'icon'      => 'entera entera-title-reveal-animation',
                'demo_link' => '#'
            ],
            [
                'label'     => esc_html__( 'Button', 'enteraddons' ),
                'name'      => 'button',
                'icon'      => 'entera entera-button',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Business Hours', 'enteraddons' ),
                'name'      => 'business_hours',
                'icon'      => 'entera entera-business-hours',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Breadcrumbs', 'enteraddons' ),
                'name'      => 'breadcrumbs',
                'icon'      => 'entera entera-breadcrumbs',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Counter', 'enteraddons' ),
                'name'      => 'counter',
                'icon'      => 'entera entera-counter',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Call To Action', 'enteraddons' ),
                'name'      => 'calltoaction',
                'icon'      => 'entera entera-call-to-action',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Card Carousel', 'enteraddons' ),
                'name'      => 'card_carousel',
                'icon'      => 'entera entera-card-carousel',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Contact Form 7', 'enteraddons' ),
                'name'      => 'contact_f7',
                'icon'      => 'entera entera-contact-form-7',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Countdown Timer', 'enteraddons' ),
                'name'      => 'countdown_timer',
                'icon'      => 'entera entera-countdown-timer',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Collection Box', 'enteraddons' ),
                'name'      => 'collection_box',
                'icon'      => 'entera entera-collection-box',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Content Ticker', 'enteraddons' ),
                'name'      => 'content_ticker',
                'icon'      => 'entera entera-content-ticker',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Coupon Code', 'enteraddons' ),
                'name'      => 'coupon_code',
                'icon'      => 'entera entera-coupon-code',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Color Scheme', 'enteraddons' ),
                'name'      => 'color_scheme',
                'icon'      => 'entera entera-color-scheme',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Data Table', 'enteraddons' ),
                'name'      => 'data_table',
                'icon'      => 'entera entera-data-table',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Events Card', 'enteraddons' ),
                'name'      => 'events_card',
                'icon'      => 'entera entera-events-card',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Feature Card', 'enteraddons' ),
                'name'      => 'feature_card',
                'icon'      => 'entera entera-featured-card',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Flip Card', 'enteraddons' ),
                'name'      => 'flip_card',
                'icon'      => 'entera entera-flip-card',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Food Menu List', 'enteraddons' ),
                'name'      => 'food_menu_list',
                'icon'      => 'entera entera-food-menu-list',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Google Map', 'enteraddons' ),
                'name'      => 'google_map',
                'icon'      => 'entera entera-featured-card',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Heading', 'enteraddons' ),
                'name'      => 'heading',
                'icon'      => 'entera entera-section-title',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Icon Card', 'enteraddons' ),
                'name'      => 'icon_card',
                'icon'      => 'entera entera-icon-card',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Info Box', 'enteraddons' ),
                'name'      => 'infobox',
                'icon'      => 'entera entera-info-box',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Image Compare', 'enteraddons' ),
                'name'      => 'image_compare',
                'icon'      => 'entera entera-image-compare',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Image Hotspot', 'enteraddons' ),
                'name'      => 'image_hotspot',
                'icon'      => 'entera entera-image-hotspot',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Image Slider', 'enteraddons' ),
                'name'      => 'image_slider',
                'icon'      => 'entera entera-image-slider',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Image Gallery', 'enteraddons' ),
                'name'      => 'image_gallery',
                'icon'      => 'entera entera-image-gallery',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Image Zoom Magnifier', 'enteraddons' ),
                'name'      => 'image_zoom_magnifier',
                'icon'      => 'entera entera-image-zoom-magnifier',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Image Icon Card', 'enteraddons' ),
                'name'      => 'image_icon_card',
                'icon'      => 'entera entera-image-icon-card',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Logo Carousel', 'enteraddons' ),
                'name'      => 'logo_carousel',
                'icon'      => 'entera entera-logo-carousel',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Lottie Animation', 'enteraddons' ),
                'name'      => 'lottie_animation',
                'icon'      => 'entera eicon-lottie',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Nav Logo', 'enteraddons' ),
                'name'      => 'nav_logo',
                'icon'      => 'entera-nav-logo',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Nav Menu', 'enteraddons' ),
                'name'      => 'nav_menu',
                'icon'      => 'entera-nav-menu',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Nav Menu Offcanvas', 'enteraddons' ),
                'name'      => 'nav_menu_offcanvas',
                'icon'      => 'entera-nav-menu',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Nav Search', 'enteraddons' ),
                'name'      => 'nav_search',
                'icon'      => 'entera-nav-search',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Advanced List', 'enteraddons' ),
                'name'      => 'advanced-list',
                'icon'      => 'entera entera-advanced-list',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Progressbar', 'enteraddons' ),
                'name'      => 'progressbar',
                'icon'      => 'entera entera-skill-bar',
                'demo_link' => '#'
            ],
            [
                'label'     => esc_html__( 'Post Grid', 'enteraddons' ),
                'name'      => 'post_grid',
                'icon'      => 'entera entera-post-grid',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Recent Posts', 'enteraddons' ),
                'name'      => 'recent_posts',
                'icon'      => 'entera entera-post-grid',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Product Carousel', 'enteraddons' ),
                'name'      => 'product_carousel',
                'icon'      => 'entera entera-product-carousel',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Pricing Table', 'enteraddons' ),
                'name'      => 'pricing_table',
                'icon'      => 'entera entera-pricing-plan',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Pricing Table Tab', 'enteraddons' ),
                'name'      => 'pricing_table_tab',
                'icon'      => 'entera entera-pricing-table-tab',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Horizontal Pricing Table', 'enteraddons' ),
                'name'      => 'horizontal_pricing_table',
                'icon'      => 'entera entera-horizontal-pricing-table',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Photo Stack', 'enteraddons' ),
                'name'      => 'photo_stack',
                'icon'      => 'entera entera-photo-stack',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Photo Reveal Animation', 'enteraddons' ),
                'name'      => 'photo_reveal_animation',
                'icon'      => 'entera entera-photo-reveal-animation',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Photo Hanger', 'enteraddons' ),
                'name'      => 'photo_hanger',
                'icon'      => 'entera entera-photo-hanger',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Profile Card', 'enteraddons' ),
                'name'      => 'profile_card',
                'icon'      => 'entera entera-profile-card',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Review Badge', 'enteraddons' ),
                'name'      => 'review_badge',
                'icon'      => 'entera entera-review-badge',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Service Card', 'enteraddons' ),
                'name'      => 'service_card',
                'icon'      => 'entera entera-service-card',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Social Icon', 'enteraddons' ),
                'name'      => 'social_icon',
                'icon'      => 'entera entera-social-icon',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Team', 'enteraddons' ),
                'name'      => 'team',
                'icon'      => 'entera entera-team',
                'demo_link' => '#'
            ],
            [
                'label'     => esc_html__( 'Testimonial Carousel', 'enteraddons' ),
                'name'      => 'testimonial',
                'icon'      => 'entera entera-testimonial',
                'demo_link' => '#'
            ],
            [
                'label'     => esc_html__( 'Single Testimonial', 'enteraddons' ),
                'name'      => 'testimonial_grid',
                'icon'      => 'entera entera-grid-testimonial',
                'demo_link' => '#'
            ],
            [
                'label'     => esc_html__( 'Testimonial Multi Rows', 'enteraddons' ),
                'name'      => 'testimonial_multi_rows',
                'icon'      => 'entera entera-testimonial-multi-rows',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Timeline', 'enteraddons' ),
                'name'      => 'timeline',
                'icon'      => 'entera entera-timeline',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Title Reveal Animation', 'enteraddons' ),
                'name'      => 'title_reveal_animation',
                'icon'      => 'entera entera-title-reveal-animation',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Travel Gallery', 'enteraddons' ),
                'name'      => 'travel_gallery',
                'icon'      => 'entera entera-travel-gallery',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Typing Animation', 'enteraddons' ),
                'name'      => 'typing_animation',
                'icon'      => 'entera entera-typing-animation',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Newsletter', 'enteraddons' ),
                'name'      => 'newsletter',
                'icon'      => 'entera entera-news-letter',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Video Button', 'enteraddons' ),
                'name'      => 'video_button',
                'icon'      => 'entera entera-video-button',
                'demo_link' => '#',
            ],
            [
                'label'     => esc_html__( 'Vertical Testimonial', 'enteraddons' ),
                'name'      => 'vertical_testimonial',
                'icon'      => 'entera entera-vertical-testimonial',
                'demo_link' => '#',
            ]
            

        ];

        return apply_filters( 'ea_lite_widgets_list', $wiggets );

    }

}


}