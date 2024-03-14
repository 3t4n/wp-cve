<?php

namespace LaStudioKitIntegrations\WPML;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

class Base {
    public function __construct(){
        add_filter( 'wpml_elementor_widgets_to_translate', [ $this, 'translate_fields' ] );
    }
    public static function translate_fields( $nodes ){

        $nodes['lakit-advanced-carousel'] = [
            'conditions'        => [
                'widgetType' => 'lakit-advanced-carousel'
            ],
            'fields'            => [],
            'fields_in_item'    => [
                'items_list'  => [
                    [
                        'field'       => 'item_title',
                        'type'        => __( 'Carousel Item: Title', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'item_text',
                        'type'        => __( 'Carousel Item: Description', 'lastudio-kit' ),
                        'editor_type' => 'AREA'
                    ],
                    'item_link' => [
                        'field'       => 'url',
                        'type'        => __( 'Carousel Item: Link', 'lastudio-kit' ),
                        'editor_type' => 'LINK'
                    ],
                    [
                        'field'       => 'item_button_text',
                        'type'        => __( 'Carousel Item: Button Text', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ]
            ]
        ];

        $nodes['lakit-animated-box'] = [
            'conditions'        => [
                'widgetType' => 'lakit-animated-box'
            ],
            'fields'            => [
                [
                    'field'       => 'front_side_title',
                    'type'        => __( 'AnimatedBox: Front Title', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'front_side_subtitle',
                    'type'        => __( 'AnimatedBox: Front SubTitle', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'front_side_description',
                    'type'        => __( 'AnimatedBox: Front Description', 'lastudio-kit' ),
                    'editor_type' => 'AREA'
                ],
                [
                    'field'       => 'back_side_title',
                    'type'        => __( 'AnimatedBox: Back Title', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'back_side_subtitle',
                    'type'        => __( 'AnimatedBox: Back SubTitle', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'back_side_description',
                    'type'        => __( 'AnimatedBox: Back Description', 'lastudio-kit' ),
                    'editor_type' => 'AREA'
                ],
                [
                    'field'       => 'back_side_button_text',
                    'type'        => __( 'AnimatedBox: Button Text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                'back_side_button_link' => [
                    'field'       => 'url',
                    'type'        => __( 'AnimatedBox: Button Link', 'lastudio-kit' ),
                    'editor_type' => 'LINK'
                ],
            ],
        ];

        $nodes['lakit-animated-text'] = [
            'conditions'        => [
                'widgetType' => 'lakit-animated-text'
            ],
            'fields'            => [
                [
                    'field'       => 'before_text_content',
                    'type'        => __( 'AnimatedText: Before Text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'after_text_content',
                    'type'        => __( 'AnimatedText: After Text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
            'fields_in_item'    => [
                'animated_text_list'  => [
                    [
                        'field'       => 'item_text',
                        'type'        => __( 'AnimatedText: Item Text', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ]
            ]
        ];

        $nodes['lakit-author-box'] = [
            'conditions'        => [
                'widgetType' => 'lakit-author-box'
            ],
            'fields'            => [
                [
                    'field'       => 'author_name',
                    'type'        => __( 'AuthorBox: Name', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                'author_website' => [
                    'field'       => 'url',
                    'type'        => __( 'AuthorBox: Website', 'lastudio-kit' ),
                    'editor_type' => 'LINK'
                ],
                [
                    'field'       => 'author_bio',
                    'type'        => __( 'AuthorBox: BIO', 'lastudio-kit' ),
                    'editor_type' => 'AREA'
                ],
                'posts_url' => [
                    'field'       => 'url',
                    'type'        => __( 'AuthorBox: Archive Button', 'lastudio-kit' ),
                    'editor_type' => 'LINK'
                ],
                [
                    'field'       => 'link_text',
                    'type'        => __( 'AuthorBox: Button Text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
        ];

        $nodes['lakit-banner'] = [
            'conditions'        => [
                'widgetType' => 'lakit-banner'
            ],
            'fields'            => [
                [
                    'field'       => 'banner_title',
                    'type'        => __( 'Banner: Title', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'banner_text',
                    'type'        => __( 'Banner: Description', 'lastudio-kit' ),
                    'editor_type' => 'AREA'
                ],
                [
                    'field'       => 'banner_link',
                    'type'        => __( 'Banner: Link', 'lastudio-kit' ),
                    'editor_type' => 'LINK'
                ],
            ],
        ];

        $nodes['lakit-banner-list'] = [
            'conditions'        => [
                'widgetType' => 'lakit-banner-list'
            ],
            'fields'            => [],
            'fields_in_item'    => [
                'image_list'  => [
                    [
                        'field'       => 'subtitle',
                        'type'        => __( 'BannerList: Subtitle', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'title',
                        'type'        => __( 'BannerList: Title', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'description',
                        'type'        => __( 'BannerList: Description', 'lastudio-kit' ),
                        'editor_type' => 'AREA'
                    ],
                    [
                        'field'       => 'subdescription',
                        'type'        => __( 'BannerList: SubDescription', 'lastudio-kit' ),
                        'editor_type' => 'AREA'
                    ],
                    [
                        'field'       => 'button_text',
                        'type'        => __( 'BannerList: Button', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    'link' => [
                        'field'       => 'url',
                        'type'        => __( 'BannerList: Link', 'lastudio-kit' ),
                        'editor_type' => 'LINK'
                    ],
                ]
            ]
        ];

        $nodes['lakit-breadcrumbs'] = [
            'conditions'        => [
                'widgetType' => 'lakit-breadcrumbs'
            ],
            'fields'            => [
                [
                    'field'       => 'custom_page_title',
                    'type'        => __( 'Breadcrumb: Custom Page Title', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'browse_label',
                    'type'        => __( 'Breadcrumb: Prefix for the breadcrumb path', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'custom_home_page_label',
                    'type'        => __( 'Breadcrumb: Label for home page', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'custom_separator',
                    'type'        => __( 'Breadcrumb: Custom Separator', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
        ];

        $nodes['lakit-button'] = [
            'conditions'        => [
                'widgetType' => 'lakit-button'
            ],
            'fields'            => [
                [
                    'field'       => 'text',
                    'type'        => __( 'Button: Text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                'link' => [
                    'field'       => 'url',
                    'type'        => __( 'Button: Link', 'lastudio-kit' ),
                    'editor_type' => 'Link'
                ],
            ],
        ];

        $nodes['lakit-countdown-timer'] = [
            'conditions'        => [
                'widgetType' => 'lakit-countdown-timer'
            ],
            'fields'            => [
                [
                    'field'       => 'label_days',
                    'type'        => __( 'Countdown: Day', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'label_hours',
                    'type'        => __( 'Countdown: Hour', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'label_min',
                    'type'        => __( 'Countdown: Minute', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'label_sec',
                    'type'        => __( 'Countdown: Seconds', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
        ];

        $nodes['lakit-google-maps'] = [
            'conditions'        => [
                'widgetType' => 'lakit-google-maps'
            ],
            'fields'            => [
                [
                    'field'       => 'map_center',
                    'type'        => __( 'Maps: Center Address', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
            'fields_in_item'    => [
                'pins'  => [
                    [
                        'field'       => 'pin_address',
                        'type'        => __( 'Maps: Pin Address', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'pin_desc',
                        'type'        => __( 'Maps: Pin Description', 'lastudio-kit' ),
                        'editor_type' => 'AREA'
                    ],
                ]
            ]
        ];

        $nodes['lakit-hamburger-panel'] = [
            'conditions'        => [
                'widgetType' => 'lakit-hamburger-panel'
            ],
            'fields'            => [
                [
                    'field'       => 'panel_toggle_label',
                    'type'        => __( 'Hamburger: Toggle Text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
        ];

        $nodes['lakit-hotspots'] = [
            'conditions'        => [
                'widgetType' => 'lakit-hotspots'
            ],
            'fields'            => [],
            'fields_in_item'    => [
                'hotspot'  => [
                    [
                        'field'       => 'hotspot_label',
                        'type'        => __( 'Hotspot: Label', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    'hotspot_link' => [
                        'field'       => 'url',
                        'type'        => __( 'Hotspot: Link', 'lastudio-kit' ),
                        'editor_type' => 'LINK'
                    ],
                    [
                        'field'       => 'hotspot_tooltip_content',
                        'type'        => __( 'Hotspot: Content', 'lastudio-kit' ),
                        'editor_type' => 'VISUAL'
                    ],
                ]
            ]
        ];

        $nodes['lakit-icon-box'] = [
            'conditions'        => [
                'widgetType' => 'lakit-icon-box'
            ],
            'fields'            => [
                [
                    'field'       => 'badge_title',
                    'type'        => __( 'IconBox: Badge', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'title_text',
                    'type'        => __( 'IconBox: Title', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'subtitle_text',
                    'type'        => __( 'IconBox: SubTitle', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'description_text',
                    'type'        => __( 'IconBox: Description', 'lastudio-kit' ),
                    'editor_type' => 'AREA'
                ],
                [
                    'field'       => 'btn_text',
                    'type'        => __( 'IconBox: Button', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                'btn_url' => [
                    'field'       => 'url',
                    'type'        => __( 'IconBox: Button Link', 'lastudio-kit' ),
                    'editor_type' => 'LINK'
                ],
                'global_link' => [
                    'field'       => 'url',
                    'type'        => __( 'IconBox: Global Link', 'lastudio-kit' ),
                    'editor_type' => 'LINK'
                ],
            ],
        ];

        $nodes['lakit-icon-list'] = [
            'conditions'        => [
                'widgetType' => 'lakit-icon-list'
            ],
            'fields'            => [],
            'fields_in_item'    => [
                'icon_list'  => [
                    [
                        'field'       => 'text',
                        'type'        => __( 'IconList: Title', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    'link' => [
                        'field'       => 'url',
                        'type'        => __( 'IconList: Link', 'lastudio-kit' ),
                        'editor_type' => 'LINK'
                    ],
                ]
            ]
        ];

        $nodes['lakit-image-box'] = [
            'conditions'        => [
                'widgetType' => 'lakit-image-box'
            ],
            'fields'            => [
                [
                    'field'       => 'box_title_text',
                    'type'        => __( 'ImageBox: Title', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'box_description_text',
                    'type'        => __( 'ImageBox: Description', 'lastudio-kit' ),
                    'editor_type' => 'AREA'
                ],
                [
                    'field'       => 'box_btn_text',
                    'type'        => __( 'ImageBox: Button', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                'box_btn_url' => [
                    'field'       => 'url',
                    'type'        => __( 'ImageBox: button Link', 'lastudio-kit' ),
                    'editor_type' => 'LINK'
                ],
                'box_website_link' => [
                    'field'       => 'url',
                    'type'        => __( 'ImageBox: Global Link', 'lastudio-kit' ),
                    'editor_type' => 'LINK'
                ],
            ],
        ];

        $nodes['lakit-images-layout'] = [
            'conditions'        => [
                'widgetType' => 'lakit-images-layout'
            ],
            'fields'            => [],
            'fields_in_item'    => [
                'image_list'  => [
                    [
                        'field'       => 'item_title',
                        'type'        => __( 'ImageLayout: Title', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'item_desc',
                        'type'        => __( 'ImageLayout: Description', 'lastudio-kit' ),
                        'editor_type' => 'AREA'
                    ],
                    [
                        'field'       => 'item_link_text',
                        'type'        => __( 'ImageLayout: Button', 'lastudio-kit' ),
                        'editor_type' => 'AREA'
                    ],
                    'item_url' => [
                        'field'       => 'url',
                        'type'        => __( 'ImageLayout: Link', 'lastudio-kit' ),
                        'editor_type' => 'LINK'
                    ],
                ]
            ]
        ];

        $nodes['lakit-login-frm'] = [
            'conditions'        => [
                'widgetType' => 'lakit-images-layout'
            ],
            'fields'            => [
                [
                    'field'       => 'label_username',
                    'type'        => __( 'Login: Username label', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'username_placeholder',
                    'type'        => __( 'Login: Username placeholder', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'label_password',
                    'type'        => __( 'Login: Password label', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'password_placeholder',
                    'type'        => __( 'Login: Password placeholder', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'label_remember',
                    'type'        => __( 'Login: Remmeber label', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'label_log_in',
                    'type'        => __( 'Login: Button Text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'login_redirect_url',
                    'type'        => __( 'Login: Redirect URL', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'label_logged_in',
                    'type'        => __( 'Login: Logged Message', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'lost_password_link_text',
                    'type'        => __( 'Login: Lost Password Text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
        ];

        $nodes['lakit-logo'] = [
            'conditions'        => [
                'widgetType' => 'lakit-logo'
            ],
            'fields'            => [
                [
                    'field'       => 'logo_text',
                    'type'        => __( 'Logo: Custom Text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ]
            ],
        ];

        $nodes['lakit-nav-menu'] = [
            'conditions'        => [
                'widgetType' => 'lakit-nav-menu'
            ],
            'fields'            => [
                [
                    'field'       => 'toggle_text',
                    'type'        => __( 'Menu: Toggle Text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'logo_text',
                    'type'        => __( 'Menu: Logo Text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ]
            ],
        ];

        $nodes['lakit-portfolio'] = [
            'conditions'        => [
                'widgetType' => 'lakit-portfolio'
            ],
            'fields'            => [
                [
                    'field'       => 'nothing_found_message',
                    'type'        => __( 'Portfolio: Nothing found message', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
            'fields_in_item'    => [
                'metadata1'  => [
                    [
                        'field'       => 'item_label',
                        'type'        => __( 'Portfolio Meta1: Label', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ],
                'metadata2'  => [
                    [
                        'field'       => 'item_label',
                        'type'        => __( 'Portfolio Meta2: Label', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ]
            ]
        ];

        $nodes['lakit-portfolio-meta'] = [
            'conditions'        => [
                'widgetType' => 'lakit-portfolio-meta'
            ],
            'fields'            => [],
            'fields_in_item'    => [
                'metadata'  => [
                    [
                        'field'       => 'item_label',
                        'type'        => __( 'Portfolio Meta: Label', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'item_fb',
                        'type'        => __( 'Portfolio Meta: Fallback', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ],
            ]
        ];

        $nodes['lakit-post-author'] = [
            'conditions'        => [
                'widgetType' => 'lakit-post-author'
            ],
            'fields'            => [
                'link' => [
                    'field'       => 'url',
                    'type'        => __( 'Post Author: Link', 'lastudio-kit' ),
                    'editor_type' => 'LINK'
                ],
            ],
        ];

        $nodes['lakit-post-date'] = [
            'conditions'        => [
                'widgetType' => 'lakit-post-date'
            ],
            'fields'            => [
                'link' => [
                    'field'       => 'url',
                    'type'        => __( 'Post Date: Link', 'lastudio-kit' ),
                    'editor_type' => 'LINK'
                ],
            ],
        ];

        $nodes['lakit-post-excerpt'] = [
            'conditions'        => [
                'widgetType' => 'lakit-post-excerpt'
            ],
            'fields'            => [
                'link' => [
                    'field'       => 'url',
                    'type'        => __( 'Post Excerpt: Link', 'lastudio-kit' ),
                    'editor_type' => 'LINK'
                ],
            ],
        ];

        $nodes['lakit-post-featured-image'] = [
            'conditions'        => [
                'widgetType' => 'lakit-post-featured-image'
            ],
            'fields'            => [
                'link' => [
                    'field'       => 'url',
                    'type'        => __( 'Post Image: Link', 'lastudio-kit' ),
                    'editor_type' => 'LINK'
                ],
            ],
        ];

        $nodes['lakit-post-info'] = [
            'conditions'        => [
                'widgetType' => 'lakit-post-info'
            ],
            'fields'            => [],
            'fields_in_item'    => [
                'metadata'  => [
                    [
                        'field'       => 'item_label',
                        'type'        => __( 'Post Meta: Label', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'item_fb',
                        'type'        => __( 'Post Meta: Fallback', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ],
            ]
        ];

        $nodes['lakit-post-meta'] = [
            'conditions'        => [
                'widgetType' => 'lakit-post-meta'
            ],
            'fields'            => [],
            'fields_in_item'    => [
                'metadata'  => [
                    [
                        'field'       => 'item_label',
                        'type'        => __( 'Post Meta: Label', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'item_fb',
                        'type'        => __( 'Post Meta: Fallback', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ],
            ]
        ];

        $nodes['lakit-post-navigation'] = [
            'conditions'        => [
                'widgetType' => 'lakit-post-navigation'
            ],
            'fields'            => [
                [
                    'field'       => 'prev_label',
                    'type'        => __( 'Post Navigation: Previous Label', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'next_label',
                    'type'        => __( 'Post Navigation: Next Label', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'menu_label',
                    'type'        => __( 'Post Navigation: Menu Label', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
        ];

        $nodes['lakit-post-title'] = [
            'conditions'        => [
                'widgetType' => 'lakit-post-title'
            ],
            'fields'            => [
                'link' => [
                    'field'       => 'url',
                    'type'        => __( 'Post Title: Link', 'lastudio-kit' ),
                    'editor_type' => 'LINK'
                ],
            ],
        ];

        $nodes['lakit-posts'] = [
            'conditions'        => [
                'widgetType' => 'lakit-posts'
            ],
            'fields'            => [
                [
                    'field'       => 'nothing_found_message',
                    'type'        => __( 'Post: Nothing found message', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'loadmore_text',
                    'type'        => __( 'Post: load more text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
            'fields_in_item'    => [
                'metadata1'  => [
                    [
                        'field'       => 'item_label',
                        'type'        => __( 'Post Meta1: Label', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ],
                'metadata2'  => [
                    [
                        'field'       => 'item_label',
                        'type'        => __( 'Post Meta2: Label', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ]
            ]
        ];

        $nodes['lakit-price-list'] = [
            'conditions'        => [
                'widgetType' => 'lakit-price-list'
            ],
            'fields'            => [],
            'fields_in_item'    => [
                'price_list'  => [
                    [
                        'field'       => 'item_title',
                        'type'        => __( 'PriceList: Title', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'item_price',
                        'type'        => __( 'PriceList: Price', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'item_text',
                        'type'        => __( 'PriceList: Description', 'lastudio-kit' ),
                        'editor_type' => 'AREA'
                    ],
                    'item_url' => [
                        'field'       => 'url',
                        'type'        => __( 'PriceList: Link', 'lastudio-kit' ),
                        'editor_type' => 'LINK'
                    ],
                ],
            ]
        ];

        $nodes['lakit-pricing-table'] = [
            'conditions'        => [
                'widgetType' => 'lakit-pricing-table'
            ],
            'fields'            => [
                [
                    'field'       => 'title',
                    'type'        => __( 'PriceTable: Title', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'subtitle',
                    'type'        => __( 'PriceTable: SubTitle', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'price_prefix',
                    'type'        => __( 'PriceTable: Price prefix', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'price',
                    'type'        => __( 'PriceTable: Price value', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'price_suffix',
                    'type'        => __( 'PriceTable: Price suffix', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'price_desc',
                    'type'        => __( 'PriceTable: Price Description', 'lastudio-kit' ),
                    'editor_type' => 'AREA'
                ],
                [
                    'field'       => 'button_before',
                    'type'        => __( 'PriceTable: Text Before Action Button', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'button_after',
                    'type'        => __( 'PriceTable: Text After Action Button', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'button_text',
                    'type'        => __( 'PriceTable: Button Text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                'button_url' => [
                    'field'       => 'url',
                    'type'        => __( 'PriceTable: Button Link', 'lastudio-kit' ),
                    'editor_type' => 'LINK'
                ],
            ],
            'fields_in_item'    => [
                'features_list'  => [
                    [
                        'field'       => 'item_text',
                        'type'        => __( 'PriceList Feature: Title', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ],
            ]
        ];

        $nodes['lakit-progress-bar'] = [
            'conditions'        => [
                'widgetType' => 'lakit-progress-bar'
            ],
            'fields'            => [
                [
                    'field'       => 'title',
                    'type'        => __( 'Progressbar: title', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ]
            ],
        ];

        $nodes['lakit-register-frm'] = [
            'conditions'        => [
                'widgetType' => 'lakit-register-frm'
            ],
            'fields'            => [
                [
                    'field'       => 'label_email',
                    'type'        => __( 'Register Form: Email label', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'placeholder_email',
                    'type'        => __( 'Register Form: Email placeholder', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'label_username',
                    'type'        => __( 'Register Form: Username label', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'placeholder_username',
                    'type'        => __( 'Register Form: Username placeholder', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'label_pass',
                    'type'        => __( 'Register Form: Password label', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'placeholder_pass',
                    'type'        => __( 'Register Form: Password placeholder', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'label_pass_confirm',
                    'type'        => __( 'Register Form: Confirm Password Label', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'placeholder_pass_confirm',
                    'type'        => __( 'Register Form: Confirm Password Placeholder', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'label_submit',
                    'type'        => __( 'Register Form: Submit Text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'register_redirect_url',
                    'type'        => __( 'Register Form: Redirect URL', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'label_registered',
                    'type'        => __( 'Register Form: User Registered Message', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ]
            ],
        ];

        $nodes['lakit-search'] = [
            'conditions'        => [
                'widgetType' => 'lakit-search'
            ],
            'fields'            => [
                [
                    'field'       => 'search_placeholder',
                    'type'        => __( 'Search: placeholder', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'search_submit_label',
                    'type'        => __( 'Search: Submit Text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'search_trigger_label',
                    'type'        => __( 'Search: Trigger Label', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'search_tax_dropdown_opt_all',
                    'type'        => __( 'Search: Dropdown All Text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
        ];

        $nodes['lakit-slides'] = [
            'conditions'        => [
                'widgetType' => 'lakit-slides'
            ],
            'fields'            => [],
            'fields_in_item'    => [
                'slides'  => [
                    [
                        'field'       => 'subheading',
                        'type'        => __( 'Slides: Sub title', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'heading',
                        'type'        => __( 'Slides: title', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'description',
                        'type'        => __( 'Slides: description', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'subdescription1',
                        'type'        => __( 'Slides: sub description', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'subdescription2',
                        'type'        => __( 'Slides: sub description 2', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'button_text',
                        'type'        => __( 'Slides: button text', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    'link' => [
                        'field'       => 'url',
                        'type'        => __( 'Slides: button link', 'lastudio-kit' ),
                        'editor_type' => 'LINK'
                    ]
                ]
            ]
        ];

        $nodes['lakit-social-share'] = [
            'conditions'        => [
                'widgetType' => 'lakit-social-share'
            ],
            'fields'            => [
                [
                    'field'       => 'heading',
                    'type'        => __( 'SocialShare: Custom Heading', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ]
            ],
            'fields_in_item'    => [
                'share_buttons'  => [
                    [
                        'field'       => 'text',
                        'type'        => __( 'SocialShare: Network Custom Label', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ]
                ]
            ]
        ];

        $nodes['lakit-subscribe-form'] = [
            'conditions'        => [
                'widgetType' => 'lakit-subscribe-form'
            ],
            'fields'            => [
                [
                    'field'       => 'submit_button_text',
                    'type'        => __( 'Subscribe: button text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'submit_placeholder',
                    'type'        => __( 'Subscribe: input placeholder', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'target_list_id',
                    'type'        => __( 'Subscribe: Mailchimp list id', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                'redirect_url' => [
                    'field'       => 'url',
                    'type'        => __( 'Subscribe: redirect url', 'lastudio-kit' ),
                    'editor_type' => 'LINK'
                ]
            ],
            'fields_in_item'    => [
                'additional_fields'  => [
                    [
                        'field'       => 'placeholder',
                        'type'        => __( 'Subscribe: input place holder', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ]
                ]
            ]
        ];

        $nodes['lakit-table-of-contents'] = [
            'conditions'        => [
                'widgetType' => 'lakit-table-of-contents'
            ],
            'fields'            => [
                [
                    'field'       => 'title',
                    'type'        => __( 'TableOfContents: Title', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ]
            ]
        ];

        $nodes['lakit-tabs'] = [
            'conditions'        => [
                'widgetType' => 'lakit-tabs'
            ],
            'fields'            => [
                [
                    'field'       => 'tab_text_intro',
                    'type'        => __( 'Tab: Intro Text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
            'fields_in_item'    => [
                'tabs'  => [
                    [
                        'field'       => 'item_label',
                        'type'        => __( 'Tab item: title', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'item_sublabel',
                        'type'        => __( 'Tab item: sub title', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ]
                ]
            ]
        ];

        $nodes['lakit-team-member'] = [
            'conditions'        => [
                'widgetType' => 'lakit-team-member'
            ],
            'fields'            => [
                [
                    'field'       => 'loadmore_text',
                    'type'        => __( 'Member: Load more text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
            'fields_in_item'    => [
                'items'  => [
                    [
                        'field'       => 'name',
                        'type'        => __( 'Member: name', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'role',
                        'type'        => __( 'Member: role', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'description',
                        'type'        => __( 'Member: description', 'lastudio-kit' ),
                        'editor_type' => 'AREA'
                    ],
                    'link' => [
                        'field'       => 'url',
                        'type'        => __( 'Member: link', 'lastudio-kit' ),
                        'editor_type' => 'LINK'
                    ],
                ]
            ]
        ];

        $nodes['lakit-testimonials'] = [
            'conditions'        => [
                'widgetType' => 'lakit-testimonials'
            ],
            'fields'            => [],
            'fields_in_item'    => [
                'items'  => [
                    [
                        'field'       => 'item_title',
                        'type'        => __( 'Testimonial: title', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'item_comment',
                        'type'        => __( 'Testimonial: comment', 'lastudio-kit' ),
                        'editor_type' => 'VISUAL'
                    ],
                    [
                        'field'       => 'item_name',
                        'type'        => __( 'Testimonial: name', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'item_position',
                        'type'        => __( 'Testimonial: role', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ]
            ]
        ];

        $nodes['lakit-timeline-horizontal'] = [
            'conditions'        => [
                'widgetType' => 'lakit-timeline-horizontal'
            ],
            'fields'            => [],
            'fields_in_item'    => [
                'cards_list'  => [
                    [
                        'field'       => 'item_title',
                        'type'        => __( 'Timeline: title', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'item_meta',
                        'type'        => __( 'Timeline: meta', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'item_desc',
                        'type'        => __( 'Timeline: description', 'lastudio-kit' ),
                        'editor_type' => 'AREA'
                    ],
                    [
                        'field'       => 'item_point_text',
                        'type'        => __( 'Timeline: point', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ]
            ]
        ];

        $nodes['lakit-timeline-vertical'] = [
            'conditions'        => [
                'widgetType' => 'lakit-timeline-vertical'
            ],
            'fields'            => [],
            'fields_in_item'    => [
                'cards_list'  => [
                    [
                        'field'       => 'item_title',
                        'type'        => __( 'Timeline: title', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'item_meta',
                        'type'        => __( 'Timeline: meta', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'item_desc',
                        'type'        => __( 'Timeline: description', 'lastudio-kit' ),
                        'editor_type' => 'AREA'
                    ],
                    [
                        'field'       => 'item_point_text',
                        'type'        => __( 'Timeline: point', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ]
            ]
        ];

        $nodes['lakit-woofilters'] = [
            'conditions'        => [
                'widgetType' => 'lakit-woofilters'
            ],
            'fields'            => [
                [
                    'field'       => 'filter_label',
                    'type'        => __( 'Filters: title', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
            'fields_in_item'    => [
                'filters'  => [
                    [
                        'field'       => 'filter_label',
                        'type'        => __( 'Filter Item: title', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field'       => 'filter_price_list',
                        'type'        => __( 'Filter Item: price list', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ]
            ]
        ];

        $nodes['lakit-menucart'] = [
            'conditions'        => [
                'widgetType' => 'lakit-menucart'
            ],
            'fields'            => [
                [
                    'field'       => 'cart_label',
                    'type'        => __( 'Cart: label', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'count_format',
                    'type'        => __( 'Cart: Products Count Format', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'total_format',
                    'type'        => __( 'Cart: Subtotal Format', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'cart_list_label',
                    'type'        => __( 'Cart: Shopping cart title', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
        ];

        if( lastudio_kit()->get_theme_support('elementor::product-grid-v2') ) {
            $nodes['lakit-wooproducts'] = [
                'conditions' => [
                    'widgetType' => 'lakit-wooproducts'
                ],
                'fields' => [
                    [
                        'field' => 'nothing_found_message',
                        'type' => __('Products: Nothing Found Message', 'lastudio-kit'),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field' => 'heading',
                        'type' => __('Products: Custom Heading', 'lastudio-kit'),
                        'editor_type' => 'LINE'
                    ],
                    [
                        'field' => 'loadmore_text',
                        'type' => __('Products: Load more text', 'lastudio-kit'),
                        'editor_type' => 'LINE'
                    ]
                ],
                'fields_in_item' => [
                    'product_image_zone_1' => [
                        [
                            'field' => 'item_label',
                            'type' => __('ProductZone1: Normal Text', 'lastudio-kit'),
                            'editor_type' => 'LINE'
                        ],
                        [
                            'field' => 'item_label2',
                            'type' => __('ProductZone1: Added Text', 'lastudio-kit'),
                            'editor_type' => 'LINE'
                        ],
                    ],
                    'product_image_zone_2' => [
                        [
                            'field' => 'item_label',
                            'type' => __('ProductZone2: Normal Text', 'lastudio-kit'),
                            'editor_type' => 'LINE'
                        ],
                        [
                            'field' => 'item_label2',
                            'type' => __('ProductZone2: Added Text', 'lastudio-kit'),
                            'editor_type' => 'LINE'
                        ],
                    ],
                    'product_content_buttons' => [
                        [
                            'field' => 'item_label',
                            'type' => __('ProductButtons: Normal Text', 'lastudio-kit'),
                            'editor_type' => 'LINE'
                        ],
                        [
                            'field' => 'item_label2',
                            'type' => __('ProductButtons: Added Text', 'lastudio-kit'),
                            'editor_type' => 'LINE'
                        ],
                    ],
                    'product_image_zone_3' => [
                        [
                            'field' => 'stock_progress_label',
                            'type' => __('ProductZone3: stock process bar label', 'lastudio-kit'),
                            'editor_type' => 'LINE'
                        ],
                        [
                            'field' => 'countdown_label_day',
                            'type' => __('ProductZone3: day label', 'lastudio-kit'),
                            'editor_type' => 'LINE'
                        ],
                        [
                            'field' => 'countdown_label_hour',
                            'type' => __('ProductZone3: hour label', 'lastudio-kit'),
                            'editor_type' => 'LINE'
                        ],
                        [
                            'field' => 'countdown_label_minute',
                            'type' => __('ProductZone3: minute label', 'lastudio-kit'),
                            'editor_type' => 'LINE'
                        ],
                        [
                            'field' => 'countdown_label_second',
                            'type' => __('ProductZone3: seconds label', 'lastudio-kit'),
                            'editor_type' => 'LINE'
                        ],
                    ],
                    'product_content_zone' => [
                        [
                            'field' => 'stock_progress_label',
                            'type' => __('ProductContent: stock process bar label', 'lastudio-kit'),
                            'editor_type' => 'LINE'
                        ],
                        [
                            'field' => 'countdown_label_day',
                            'type' => __('ProductContent: day label', 'lastudio-kit'),
                            'editor_type' => 'LINE'
                        ],
                        [
                            'field' => 'countdown_label_hour',
                            'type' => __('ProductContent: hour label', 'lastudio-kit'),
                            'editor_type' => 'LINE'
                        ],
                        [
                            'field' => 'countdown_label_minute',
                            'type' => __('ProductContent: minute label', 'lastudio-kit'),
                            'editor_type' => 'LINE'
                        ],
                        [
                            'field' => 'countdown_label_second',
                            'type' => __('ProductContent: seconds label', 'lastudio-kit'),
                            'editor_type' => 'LINE'
                        ],
                    ]
                ]
            ];
        }

        $nodes['lakit-wooproduct-meta'] = [
            'conditions'        => [
                'widgetType' => 'lakit-wooproduct-meta'
            ],
            'fields'            => [
                [
                    'field'       => 'category_caption_single',
                    'type'        => __( 'ProductMeta: Category single', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'category_caption_plural',
                    'type'        => __( 'ProductMeta: Category plural', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'tag_caption_single',
                    'type'        => __( 'ProductMeta: tag single', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'tag_caption_plural',
                    'type'        => __( 'ProductMeta: tag plural', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'sku_caption',
                    'type'        => __( 'ProductMeta: sku', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'sku_missing_caption',
                    'type'        => __( 'ProductMeta: sku missing', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
        ];

        $nodes['lastudiokit-woo-product'] = [
            'conditions'        => [
                'widgetType' => 'lastudiokit-woo-product'
            ],
            'fields'            => [
                [
                    'field'       => 'text',
                    'type'        => __( 'Wishlist/Compare: Text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
        ];


        $nodes['lakit-events'] = [
            'conditions'        => [
                'widgetType' => 'lakit-events'
            ],
            'fields'            => [
                [
                    'field'       => 'nothing_found_message',
                    'type'        => __( 'Event: Nothing found message', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'loadmore_text',
                    'type'        => __( 'Event: load more text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
            'fields_in_item'    => [
                'metadata1'  => [
                    [
                        'field'       => 'item_label',
                        'type'        => __( 'Event Meta1: Label', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ],
                'metadata2'  => [
                    [
                        'field'       => 'item_label',
                        'type'        => __( 'Event Meta2: Label', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ]
            ]
        ];

        $nodes['lakit-album-lists'] = [
            'conditions'        => [
                'widgetType' => 'lakit-album-lists'
            ],
            'fields'            => [
                [
                    'field'       => 'nothing_found_message',
                    'type'        => __( 'Album: Nothing found message', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'loadmore_text',
                    'type'        => __( 'Album: load more text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'play_album_text',
                    'type'        => __( 'Album: Play button text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
            'fields_in_item'    => [
                'metadata1'  => [
                    [
                        'field'       => 'item_label',
                        'type'        => __( 'Album Meta1: Label', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ],
                'metadata2'  => [
                    [
                        'field'       => 'item_label',
                        'type'        => __( 'Album Meta2: Label', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ]
            ]
        ];

        $nodes['lakit-give-form-grid'] = [
            'conditions'        => [
                'widgetType' => 'lakit-give-form-grid'
            ],
            'fields'            => [
                [
                    'field'       => 'nothing_found_message',
                    'type'        => __( 'Give Form: Nothing found message', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'loadmore_text',
                    'type'        => __( 'Give Form: Load more text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'raised_text',
                    'type'        => __( 'Give Form: Raised text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'goal_text',
                    'type'        => __( 'Give Form: Goal text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
                [
                    'field'       => 'donate_text',
                    'type'        => __( 'Give Form: Donate button text', 'lastudio-kit' ),
                    'editor_type' => 'LINE'
                ],
            ],
            'fields_in_item'    => [
                'metadata1'  => [
                    [
                        'field'       => 'item_label',
                        'type'        => __( 'Album Meta1: Label', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ],
                'metadata2'  => [
                    [
                        'field'       => 'item_label',
                        'type'        => __( 'Album Meta2: Label', 'lastudio-kit' ),
                        'editor_type' => 'LINE'
                    ],
                ]
            ]
        ];

        return $nodes;
    }
}