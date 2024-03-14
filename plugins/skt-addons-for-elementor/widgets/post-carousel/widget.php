<?php
/**
 * Post Carousel widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Icons_Manager;
use Skt_Addons_Elementor\Elementor\Traits\Lazy_Query_Builder;

defined( 'ABSPATH' ) || die();

class Post_Carousel extends Base {

    use Lazy_Query_Builder;

    /**
     * Get widget title.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Post Carousel', 'skt-addons-elementor' );
    }

    /**
     * Get widget icon.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'skti skti-flip-card1';
    }

    public function get_keywords() {
        return [ 'post', 'carousel', 'blog' ];
    }

	/**
     * Register widget content controls
     */
    protected function register_content_controls() {
		$this->__layout_content_controls();
		$this->__query_content_controls();
		$this->__settings_content_controls();
	}

    protected function __layout_content_controls() {

        $this->start_controls_section(
            '_section_post_layout',
            [
                'label' => __( 'Layout', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'post_image',
				'default' => 'large',
				'exclude' => [
					'custom'
				],
				'condition' => [
					'posts_only_with_featured_image' => 'yes'
				]
			]
		);

		$this->add_control(
			'post_category',
			[
				'label' => __( 'Show Badge', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );

		$this->add_control(
			'excerpt_show',
			[
				'label' => __( 'Show Excerpt', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Excerpt Length', 'skt-addons-elementor' ),
				'default' => 15,
				'condition' => [
					'excerpt_show' => 'yes'
				],
			]
		);

		$this->add_control(
			'active_meta',
			[
				'type' => Controls_Manager::SELECT2,
				'label' => __( 'Active Meta', 'skt-addons-elementor' ),
				'description' => __( 'Select to show and unselect to hide', 'skt-addons-elementor' ),
				'label_block' => true,
				'multiple' => true,
				'default' => ['author', 'date'],
				'options' => [
					'author' => __( 'Author', 'skt-addons-elementor' ),
					'date' => __( 'Date', 'skt-addons-elementor' )
				]
			]
		);

        $this->add_control(
			'post_author_meta',
			[
				'label' => __( 'Author Avatar', 'skt-addons-elementor' ),
				'label_block' => false,
				'type' => Controls_Manager::SELECT,
                'default' => 'image',
				'toggle' => false,
				'condition' => [
					'active_meta' => 'author'
				],
				'options' => [
					'none' => __( 'None', 'skt-addons-elementor' ),
					'image' => __( 'Image', 'skt-addons-elementor' ),
					'icon' => __( 'Icon', 'skt-addons-elementor' )
				]
			]
		);

		$this->add_control(
			'author_name_show',
			[
				'label' => __( 'Author Name', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'active_meta' => 'author'
				],
			]
        );

        $this->add_control(
			'author_icon',
			[
				'label' => __( 'Author Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => false,
				'skin' => 'inline',
				'exclude_inline_options' => [ 'svg' ],
				'default' => [
					'value' => 'skti skti-user-male',
					'library' => 'skt-icons',
				],
				'condition' => [
					'post_author_meta' => 'icon',
					'active_meta' => 'author'
				],
			]
		);

        $this->add_control(
			'date_icon',
			[
				'label' => __( 'Date Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => false,
				'skin' => 'inline',
				'default' => [
					'value' => 'skti skti-calendar2',
					'library' => 'skt-icons',
				],
				'condition' => [
					'active_meta' => 'date'
				],
			]
		);

        $this->add_control(
			'layout',
			[
				'label' => __( 'Layout', 'skt-addons-elementor' ),
				'description' => __( 'Make sure that <strong>Query > With Featured Image</strong> is enabled.', 'skt-addons-elementor' ),
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
                'default' => 'under_image',
                'prefix_class' => 'skt-layout-',
				'toggle' => false,
				'separator' => 'before',
				'options' => [
					'under_image' => [
						'title' => __( 'Content Under Image', 'skt-addons-elementor' ),
						'icon' => 'eicon-menu-bar',
					],
					'over_image' => [
						'title' => __( 'Content Over Image', 'skt-addons-elementor' ),
						'icon' => 'eicon-clone',
					],
				]
			]
        );

        $this->add_control(
			'author_meta_position',
			[
				'label' => __( 'Author Meta Position', 'skt-addons-elementor' ),
				'label_block' => false,
				'type' => Controls_Manager::SELECT,
                'default' => 'after_title',
				'options' => [
					'after_title' => __( 'After Title', 'skt-addons-elementor' ),
					'after_content' => __( 'After Content', 'skt-addons-elementor' )
                ],
                'selectors_dictionary' => [
					'after_title' => 'flex-direction: column',
					'after_content' => 'flex-direction: column-reverse'
                ],
                'prefix_class' => 'skt-author-meta-',
                'selectors' => [
					'{{WRAPPER}} .skt-posts-carousel__content-text' => '{{VALUE}};'
				]
			]
        );

        $this->add_control(
			'imge_position',
			[
				'label' => __( 'Image Position', 'skt-addons-elementor' ),
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
                'default' => 'under_image',
				'options' => [
					'top' => [
						'title' => __( 'Top', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
                ],
                'condition' => [
                    'layout' => 'under_image'
                ],
                'selectors_dictionary' => [
					'top' => 'flex-direction: column',
					'bottom' => 'flex-direction: column-reverse'
                ],
                'selectors' => [
					'{{WRAPPER}} .skt-posts-carousel' => '{{VALUE}};'
				]
			]
        );

        $this->add_control(
            'content_position',
            [
                'label' => __( 'Content Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'top_left',
                'prefix_class' => 'skt-content-position-',
                'condition' => [
                    'layout' => 'over_image'
                ],
                'options' => [
					'top_left' => __( 'Top Left', 'skt-addons-elementor' ),
					'top_center' => __( 'Top Center', 'skt-addons-elementor' ),
                    'top_right' => __( 'Top Right', 'skt-addons-elementor' ),
					'bottom_left' => __( 'Bottom Left', 'skt-addons-elementor' ),
					'bottom_center' => __( 'Bottom Center', 'skt-addons-elementor' ),
                    'bottom_right' => __( 'Bottom Right', 'skt-addons-elementor' ),
					'center_left' => __( 'Center Left', 'skt-addons-elementor' ),
					'center_center' => __( 'Center Center', 'skt-addons-elementor' ),
					'center_right' => __( 'Center Right', 'skt-addons-elementor' ),
                ]
            ]
		);

		$this->add_control(
			'date_position',
			[
				'label' => __( 'Date Position', 'skt-addons-elementor' ),
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
                'default' => 'block',
                'toggle' => false,
				'options' => [
					'inline' => [
						'title' => __( 'Inline', 'skt-addons-elementor' ),
						'icon' => 'eicon-navigation-horizontal',
					],
					'block' => [
						'title' => __( 'Block', 'skt-addons-elementor' ),
						'icon' => 'eicon-menu-bar',
					],
                ],
                'selectors_dictionary' => [
					'inline' => 'flex-direction: row',
					'block' => 'flex-direction: column'
                ],
                'prefix_class' => 'skt-date-position-',
                'selectors' => [
					'{{WRAPPER}} .skt-posts-carousel__meta-author-name' => '{{VALUE}};'
				]
			]
        );

        $this->add_control(
			'content_alignment',
			[
				'label' => __( 'Content Alignment', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'toggle' => true,
                'prefix_class' => 'skt-content-',
				'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__meta' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .skt-posts-carousel__content-wrap' => 'align-items: {{VALUE}};'
				]
			]
		);

        $this->add_control(
			'image_alignment',
			[
				'label' => __( 'Image Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
                'toggle' => true,
                'condition' => [
                    'feature_image' => 'yes',
                    'layout' => 'under_image'
                ],
				'selectors' => [
					'{{WRAPPER}} .skt-posts-carousel__image' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->end_controls_section();
	}

    protected function __query_content_controls() {

		$this->start_controls_section(
            '_post_query',
            [
                'label' => __( 'Query', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

		$this->register_query_controls();

		$this->add_control(
            'post_per_page',
            [
                'label' => __( 'Post Per Page', 'skt-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'max' => 10000,
                'default' => 5,
            ]
        );

        $this->end_controls_section();
	}

    protected function __settings_content_controls() {

        $this->start_controls_section(
            '_section_settings',
            [
                'label' => __( 'Carousel Settings', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'animation_speed',
            [
                'label' => __( 'Animation Speed', 'skt-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'step' => 10,
                'max' => 10000,
                'default' => 800,
                'description' => __( 'Slide speed in milliseconds', 'skt-addons-elementor' ),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __( 'Autoplay?', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'skt-addons-elementor' ),
                'label_off' => __( 'No', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => __( 'Autoplay Speed', 'skt-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'step' => 100,
                'max' => 10000,
                'default' => 2000,
                'description' => __( 'Autoplay speed in milliseconds', 'skt-addons-elementor' ),
                'condition' => [
                    'autoplay' => 'yes'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __( 'Infinite Loop?', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'skt-addons-elementor' ),
                'label_off' => __( 'No', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'vertical',
            [
                'label' => __( 'Vertical Mode?', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'skt-addons-elementor' ),
                'label_off' => __( 'No', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'frontend_available' => true,
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'navigation',
            [
                'label' => __( 'Navigation', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => __( 'None', 'skt-addons-elementor' ),
                    'arrow' => __( 'Arrow', 'skt-addons-elementor' ),
                    'dots' => __( 'Dots', 'skt-addons-elementor' ),
                    'both' => __( 'Arrow & Dots', 'skt-addons-elementor' ),
                ],
                'default' => 'arrow',
                'frontend_available' => true,
                'style_transfer' => true,
            ]
        );

        $this->add_responsive_control(
            'slides_to_show',
            [
                'label' => __( 'Slides To Show', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    1 => __( '1 Slide', 'skt-addons-elementor' ),
                    2 => __( '2 Slides', 'skt-addons-elementor' ),
                    3 => __( '3 Slides', 'skt-addons-elementor' ),
                    4 => __( '4 Slides', 'skt-addons-elementor' ),
                    5 => __( '5 Slides', 'skt-addons-elementor' ),
                    6 => __( '6 Slides', 'skt-addons-elementor' ),
                ],
                'desktop_default' => 2,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'frontend_available' => true,
                'style_transfer' => true,
            ]
        );

        $this->end_controls_section();
    }

	/**
     * Register widget style controls
     */
    protected function register_style_controls() {
		$this->__items_style_controls();
		$this->__image_style_controls();
		$this->__badge_style_controls();
		$this->__content_style_controls();
		$this->__arrow_style_controls();
		$this->__dots_style_controls();
	}

    protected function __items_style_controls() {

        $this->start_controls_section(
            '_section_common_style',
            [
                'label' => __( 'Carousel Item', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_responsive_control(
            'carousel_item_heght',
            [
                'label' => __( 'Height', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 1200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel' => 'height: {{SIZE}}{{UNIT}};'
                ],
            ]
		);

        $this->add_responsive_control(
            'carousel_item_spacing',
            [
                'label' => __( 'Item Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel-slick' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_group_control(
            Group_Control_Border::get_type(),
            [
				'name' => 'carousel_item_border',
				'condition' => [
                    'layout' => 'under_image'
                ],
                'selector' => '{{WRAPPER}} .skt-posts-carousel',
            ]
		);

		$this->add_responsive_control(
            'carousel_item_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'condition' => [
                    'layout' => 'under_image'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'selector' => '{{WRAPPER}} .skt-posts-carousel',
                'condition' => [
                    'layout' => 'under_image'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'carousel_item_background',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .skt-posts-carousel',
                'condition' => [
                    'layout' => 'under_image'
                ]
            ]
        );

        $this->end_controls_section();
	}

    protected function __image_style_controls() {

		$this->start_controls_section(
            '_section_feature_image',
            [
                'label' => __( 'Image', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
		);

        $this->add_control(
            'feature_image_note',
            [
                'label' => false,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( '<strong>Image</strong> is Switched off on "Query"', 'skt-addons-elementor' ),
                'condition' => [
                    'posts_only_with_featured_image!' => 'yes',
                ],
            ]
		);

		$this->add_responsive_control(
            'feature_image_spacing',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'condition' => [
                    'layout' => 'under_image'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
		);

		$this->add_responsive_control(
            'feature_image_inner_spacing',
            [
                'label' => __( 'Inner Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'condition' => [
                    'layout' => 'over_image'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__content-position' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

		$this->add_responsive_control(
            'feature_image_width',
            [
                'label' => __( 'Width', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 2000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__feature-img img' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

		$this->add_responsive_control(
            'feature_image_height',
            [
                'label' => __( 'Height', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 2000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__feature-img img' => 'height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'feature_image_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__feature-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
			'image_animation',
			[
				'label' => __( 'Hover Animation', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
                'default' => 'yes',
                'prefix_class' => 'skt-image-animation-',
                'condition' => [
                    'layout' => 'under_image'
				],
			]
        );

        $this->add_control(
			'image_overlay_color',
			[
				'label' => __( 'Hover Overlay Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__image.skt-image-link a:hover .skt-posts-carousel__image-overlay' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .skt-posts-carousel__feature-img:hover .skt-posts-carousel__image-overlay' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.skt-layout-over_image .skt-posts-carousel__feature-img .skt-posts-carousel__image-overlay' => 'background-color: {{VALUE}};'
				],
			]
        );

		$this->end_controls_section();
	}

    protected function __badge_style_controls() {

		$this->start_controls_section(
            '_section_category_style',
            [
                'label' => __( 'Badge', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
		);

		$this->add_control(
            'category_note',
            [
                'label' => false,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( '<strong>Badge</strong> is Switched off on "Layout"', 'skt-addons-elementor' ),
                'condition' => [
                    'post_category!' => 'yes',
                ],
            ]
		);

        $this->add_responsive_control(
            'category_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__meta-category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);

		$this->add_responsive_control(
            'category_spacing',
            [
                'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
                'selectors' => [
					'{{WRAPPER}} .skt-posts-carousel__meta-category' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
				'name' => 'category_border',
                'selector' => '{{WRAPPER}} .skt-posts-carousel__meta-category a',
            ]
		);

        $this->add_responsive_control(
            'category_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__meta-category a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
				'name' => 'category_box_shadow',
                'selector' => '.skt-posts-carousel__meta-category a',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'category_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'selector' => '{{WRAPPER}} .skt-posts-carousel__meta-category a',
            ]
        );

        $this->start_controls_tabs( '_category_button' );

        $this->start_controls_tab(
            '_tab_category_normal',
            [
				'label' => __( 'Normal', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'category_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
				'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__meta-category a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'category_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__meta-category a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_category_hover',
            [
				'label' => __( 'Hover', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'category_hover_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__meta-category a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'category_hover_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__meta-category a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'category_hover_border_color',
            [
                'label' => __( 'Border Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
					'category_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__meta-category a:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

		$this->end_controls_section();
	}

    protected function __content_style_controls() {

        $this->start_controls_section(
            '_section_content_style',
            [
                'label' => __( 'Content', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding_under_image',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'condition' => [
					'layout' => 'under_image'
				],
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__content-position' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);

		$this->add_responsive_control(
            'content_padding_over_image',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'condition' => [
					'layout' => 'over_image'
				],
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'condition' => [
                    'layout' => 'over_image'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__content-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_over_image_background',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .skt-posts-carousel__content-wrap',
                'condition' => [
                    'layout' => 'over_image'
                ]
            ]
        );

        $this->add_control(
            '_heading_title',
            [
                'label' => __( 'Title', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
					'{{WRAPPER}} .skt-posts-carousel__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-posts-carousel__title, {{WRAPPER}} .skt-posts-carousel__title a',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .skt-posts-carousel__title a' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => __( 'Hover Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__title a:hover' => 'color: {{VALUE}};',
                ],
            ]
		);

		$this->add_control(
            '_heading_author_meta',
            [
                'label' => __( 'Author Avatar', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
		);

        $this->add_responsive_control(
            'author_meta_space',
            [
                'label' => __( 'Right Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
				],
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__meta-author-img' => 'margin-right: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'author_icon_size',
            [
                'label' => __( 'Icon/Image Size', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
				],
                'selectors' => [
					'{{WRAPPER}} .skt-posts-carousel__meta-author-img i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-posts-carousel__meta-author-img svg' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-posts-carousel__meta-author-img img' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'author_meta_icon_color',
            [
                'label' => __( 'Icon Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
					'post_author_meta' => 'icon',
                ],
                'selectors' => [
					'{{WRAPPER}} .skt-posts-carousel__meta-author-img i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .skt-posts-carousel__meta-author-img svg' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_heading_author_name',
            [
                'label' => __( 'Author Name', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
		);

		$this->add_control(
            'author_name_note',
            [
                'label' => false,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( '<strong>Author Name</strong> is Switched off on "Layout"', 'skt-addons-elementor' ),
                'condition' => [
                    'author_name_show!' => 'yes',
                ],
            ]
		);

        $this->add_responsive_control(
            'author_name_space',
            [
                'label' => __( 'Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
				],
                'selectors' => [
                    '{{WRAPPER}}.skt-date-position-block .skt-posts-carousel__meta-author-name a' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.skt-date-position-inline .skt-posts-carousel__meta-author-name a' => 'margin-right: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'author_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-posts-carousel__meta-author-name a',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
            ]
        );

        $this->add_control(
            'author_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
				'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__meta-author-name a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'author_hover_color',
            [
                'label' => __( 'Hover Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__meta-author-name a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_heading_date',
            [
                'label' => __( 'Date', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'date_note',
            [
                'label' => false,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( '<strong>Date</strong> is not selected in "Layout > Active Meta"', 'skt-addons-elementor' ),
                'condition' => [
                    'active_meta!' => 'date'
                ],
            ]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'label' => __( 'Typography', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .skt-posts-carousel__meta-date',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
            ]
        );

        $this->add_control(
            'date_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__meta-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_heading_content_excerpt',
            [
                'label' => __( 'Excerpt', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
		);

		$this->add_control(
            'content_excerpt_note',
            [
                'label' => false,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( '<strong>Excerpt</strong> switched off on "Layout"', 'skt-addons-elementor' ),
                'condition' => [
                    'excerpt_show!' => 'yes',
                ],
            ]
		);

        $this->add_responsive_control(
            'content_excerpt_spacing',
            [
                'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 80,
                    ],
				],
                'selectors' => [
                    '{{WRAPPER}}.skt-author-meta-after_content .skt-posts-carousel__content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_excerpt_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-posts-carousel__content',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
            ]
        );

        $this->add_control(
            'content_excerpt_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-posts-carousel__content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
	}

    protected function __arrow_style_controls() {

        $this->start_controls_section(
            '_section_style_arrow',
            [
                'label' => __( 'Navigation - Arrow', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'arrow_position_toggle',
            [
                'label' => __( 'Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __( 'None', 'skt-addons-elementor' ),
                'label_on' => __( 'Custom', 'skt-addons-elementor' ),
                'return_value' => 'yes',
            ]
        );

		$this->start_popover();

		$this->add_control(
			'arrow_sync_position',
			[
				'label' => __( 'Sync Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'yes' => [
						'title' => __( 'Yes', 'skt-addons-elementor' ),
						'icon' => 'eicon-sync',
					],
					'no' => [
						'title' => __( 'No', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-stretch',
					]
				],
				'condition' => [
					'arrow_position_toggle' => 'yes'
				],
				'default' => 'no',
				'toggle' => false,
				'prefix_class' => 'skt-arrow-sync-'
			]
		);

		$this->add_control(
			'sync_position_alignment',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					]
				],
				'condition' => [
					'arrow_position_toggle' => 'yes',
					'arrow_sync_position' => 'yes'
				],
				'default' => 'center',
				'toggle' => false,
				'selectors_dictionary' => [
					'left' => 'left: 0',
					'center' => 'left: 50%',
					'right' => 'left: 100%',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => '{{VALUE}}'
				]
			]
		);

		$this->add_responsive_control(
			'arrow_position_y',
			[
				'label' => __( 'Vertical', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition' => [
					'arrow_position_toggle' => 'yes'
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'top: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'arrow_position_x',
			[
				'label' => __( 'Horizontal', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition' => [
					'arrow_position_toggle' => 'yes'
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 1200,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.skt-arrow-sync-no .slick-prev' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.skt-arrow-sync-no .slick-next' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.skt-arrow-sync-yes .slick-next, {{WRAPPER}}.skt-arrow-sync-yes .slick-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_spacing',
			[
				'label' => __( 'Space between Arrows', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition' => [
					'arrow_position_toggle' => 'yes',
					'arrow_sync_position' => 'yes'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
                ],
                'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}}.skt-arrow-sync-yes .slick-next' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'arrow_size',
			[
				'label' => __( 'Box Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 70,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .slick-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_font_size',
			[
				'label' => __( 'Icon Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 2,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .slick-next' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'arrow_border',
                'selector' => '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next',
            ]
        );

        $this->add_responsive_control(
            'arrow_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->start_controls_tabs( '_tabs_arrow' );

        $this->start_controls_tab(
            '_tab_arrow_normal',
            [
                'label' => __( 'Normal', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_arrow_hover',
            [
                'label' => __( 'Hover', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-prev:hover, {{WRAPPER}} .slick-next:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-prev:hover, {{WRAPPER}} .slick-next:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_border_color',
            [
                'label' => __( 'Border Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'arrow_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-prev:hover, {{WRAPPER}} .slick-next:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
	}

    protected function __dots_style_controls() {

         $this->start_controls_section(
            '_section_style_dots',
            [
                'label' => __( 'Navigation - Dots', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'dots_nav_position_y',
            [
                'label' => __( 'Vertical Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-dots' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_nav_spacing',
            [
                'label' => __( 'Space Between', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_nav_align',
            [
                'label' => __( 'Alignment', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .slick-dots' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->start_controls_tabs( '_tabs_dots' );
        $this->start_controls_tab(
            '_tab_dots_normal',
            [
                'label' => __( 'Normal', 'skt-addons-elementor' ),
            ]
        );

		$this->add_control(
			'dots_nav_size',
			[
				'label' => __( 'Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .slick-dots li button:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'dots_nav_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li button:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_dots_hover',
            [
                'label' => __( 'Hover', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'dots_nav_hover_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li button:hover:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_dots_active',
            [
                'label' => __( 'Active', 'skt-addons-elementor' ),
            ]
        );

		$this->add_control(
			'dots_nav_active_size',
			[
				'label' => __( 'Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .slick-dots li.slick-active button:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'dots_nav_active_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-dots .slick-active button:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->post_carousel($settings);
    }

    protected function post_carousel($settings) {
        $args = $this->get_query_args();
        $args['numberposts'] = $settings['post_per_page'];

        $posts = get_posts( $args );

        if ( empty( $posts ) ) {
            if ( is_admin() ) {
                return printf( '<div class="skt-posts-carousel-error">%s</div>', __( 'Nothing Found. Please Add/Select Posts.', 'skt-addons-elementor' ) );
            }
		}
        ?>
        <div class="skt-posts-carousel-wrapper">

            <?php foreach ( $posts as $post ): ?>
                <div class="skt-posts-carousel-slick">
                <div class="skt-posts-carousel">

                    <?php
                    if ( array_key_exists( 'meta_key', $args ) && $args['meta_key'] == '_thumbnail_id' ) : ?>
                        <div class="skt-posts-carousel__image skt-image-link">
                            <div class="skt-posts-carousel__feature-img">
                                <a href="<?php echo esc_url( get_the_permalink( $post->ID ) ); ?>">
                                    <span class="skt-posts-carousel__image-overlay"></span>
                                    <?php echo wp_kses_post(get_the_post_thumbnail( $post->ID, $settings['post_image_size'] )); ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="skt-posts-carousel__content-position">
                    <div class="skt-posts-carousel__content-wrap">
                        <?php
                        if ( $settings['post_category'] == 'yes' ) :
                            $categories = get_the_category( $post->ID );
                            if ( !empty( $categories ) ) :
                            ?>
                                <div class="skt-posts-carousel__meta-category">
                                    <a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>">
                                        <?php echo esc_html( $categories[0]->name ); ?>
                                    </a>
                                </div>
                            <?php
                            endif;
                        endif;
                        ?>

						<div class="skt-posts-carousel__title">
							<a href="<?php echo esc_url( get_the_permalink( $post->ID ) ); ?>">
								<?php echo esc_html( get_the_title( $post->ID ) ); ?>
							</a>
						</div>

                        <div class="skt-posts-carousel__content-text">
                            <div class="skt-posts-carousel__meta">
                                <?php
                                $author_nicename = get_the_author_meta( 'display_name', $post->post_author );
								$author_link = get_the_author_meta( 'user_url', $post->post_author );

								if ( $settings['post_author_meta'] == 'icon' && ! empty( $settings['author_icon']['value'] ) ) : ?>
									<div class="skt-posts-carousel__meta-author-img">
										<?php Icons_Manager::render_icon( $settings['author_icon'], [ 'aria-hidden' => 'true' ] ); ?>
									</div>
								<?php elseif ( $settings['post_author_meta'] == 'image' ) : ?>
									<div class="skt-posts-carousel__meta-author-img">
										<a href="<?php echo esc_url( $author_link ); ?>">
											<img src="<?php echo esc_url(get_avatar_url( $post->post_author, ['size' => '45'] )); ?>" alt="<?php echo esc_attr( $author_nicename ); ?>" class="skt-posts-carousel__meta-image">
										</a>
									</div>
								<?php
								else: null;
								endif;
								?>

                                <div class="skt-posts-carousel__meta-author-name">
									<?php if ( $settings['author_name_show'] == 'yes' ) : ?>
										<a href="<?php echo esc_url( $author_link ); ?>">
											<?php echo esc_html( $author_nicename ); ?>
										</a>
									<?php endif; ?>

                                    <div class="skt-posts-carousel__meta-date">
										<?php
										if ( isset( $settings['date_icon'] ) ) :
											if ( ! empty( $settings['date_icon']['value'] ) ) : ?>
												<?php Icons_Manager::render_icon( $settings['date_icon'], [ 'aria-hidden' => 'true' ] ); ?>
											<?php endif; ?>
                                        	<span><?php echo esc_html( get_the_date( 'd M Y', $post->ID ) ); ?></span>
										<?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <?php if ( $settings['excerpt_show'] == 'yes' ) : ?>
                            <div class="skt-posts-carousel__content">
								<?php echo wp_kses_post(skt_addons_elementor_pro_get_excerpt( $post->ID, $settings['excerpt_length'] )); ?>
                            </div>
                            <?php endif; ?>
                        </div>

                    </div>
                    </div>

                </div>
                </div>
            <?php endforeach; ?>

        </div>
        <?php
    }
}