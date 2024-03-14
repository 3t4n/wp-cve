<?php

/**
 * Class: LaStudioKit_Advanced_Carousel
 * Name: Advanced Carousel
 * Slug: lakit-carousel
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

use Elementor\Core\Base\Document;
use Elementor\Core\Files\CSS\Post as Post_CSS;
use LaStudioKitExtensions\Elementor\Controls\Control_Query as QueryControlModule;

/**
 * Advanced_Carousel Widget
 */
class LaStudioKit_Advanced_Carousel extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()){
		    $this->add_script_depends( 'lastudio-kit-base' );
		    if(!lastudio_kit()->is_optimized_css_mode()) {
			    wp_register_style( 'lakit-banner', lastudio_kit()->plugin_url('assets/css/addons/banner.min.css'), ['lastudio-kit-base'], lastudio_kit()->get_version());
			    wp_register_style( $this->get_name(), lastudio_kit()->plugin_url('assets/css/addons/advanced-carousel.min.css'), ['lakit-banner'], lastudio_kit()->get_version());
			    $this->add_style_depends( $this->get_name() );
		    }
	    }
    }

	public function get_inline_css_depends() {
        if('true' === $this->get_settings_for_display('disable_default_css')){
            return [];
        }
		return [
			'lakit-banner1',
			'lakit-banner2',
		];
	}

	public function get_widget_css_config($widget_name){
		switch ($widget_name){
			case 'lakit-banner1':
				$file_url = lastudio_kit()->plugin_url(  'assets/css/addons/banner1.min.css' );
				$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/banner1.min.css' );
				break;
			case 'lakit-banner2':
				$file_url = lastudio_kit()->plugin_url(  'assets/css/addons/banner2.min.css' );
				$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/banner2.min.css' );
				break;
			default:
				$file_url = lastudio_kit()->plugin_url(  'assets/css/addons/advanced-carousel.min.css' );
				$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/advanced-carousel.min.css' );
				break;
		}
		return [
			'key' => $widget_name,
			'version' => lastudio_kit()->get_version(true),
			'file_path' => $file_path,
			'data' => [
				'file_url' => $file_url
			]
		];
	}

    public function get_name() {
        return 'lakit-advanced-carousel';
    }

    protected function get_widget_title() {
        return esc_html__( 'Advanced Carousel', 'lastudio-kit');
    }

    public function get_icon() {
        return 'lastudio-kit-icon-carousel';
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_slides',
            array(
                'label' => esc_html__( 'Slides', 'lastudio-kit' ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_image',
            array(
                'label'   => esc_html__( 'Image', 'lastudio-kit' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_content_type',
            array(
                'label'   => esc_html__( 'Content Type', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => array(
                    'default'  => esc_html__( 'Default', 'lastudio-kit' ),
                    'template' => esc_html__( 'Template', 'lastudio-kit' ),
                ),
            )
        );

	    $repeater->add_control(
		    'item_icon',
		    [
			    'label'            => __( 'Icon', 'lastudio-kit' ),
			    'type'             => Controls_Manager::ICONS,
			    'fa4compatibility' => 'icon',
			    'skin'             => 'inline',
			    'label_block'      => false,
		    ]
	    );

        $repeater->add_control(
            'item_title',
            array(
                'label'       => esc_html__( 'Item Title', 'lastudio-kit' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => array( 'active' => true ),
                'condition'   => array(
                    'item_content_type' => 'default',
                ),
            )
        );

        $repeater->add_control(
            'item_text',
            array(
                'label'       => esc_html__( 'Item Description', 'lastudio-kit' ),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => array( 'active' => true ),
                'condition'   => array(
                    'item_content_type' => 'default',
                ),
            )
        );

        $repeater->add_control(
            'item_link',
            array(
                'label'       => esc_html__( 'Item Link', 'lastudio-kit' ),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __( 'https://your-link.com', 'lastudio-kit' ),
                'condition'   => array(
                    'item_content_type' => 'default',
                ),
            )
        );

        $repeater->add_control(
            'item_button_text',
            array(
                'label'       => esc_html__( 'Item Button Text', 'lastudio-kit' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'dynamic'     => array(
                    'active' => true
                ),
                'condition'   => array(
                    'item_content_type' => 'default',
                ),
            )
        );

        $repeater->add_control(
            'template_id',
            array(
                'label'       => esc_html__( 'Choose Template', 'lastudio-kit' ),
                'label_block' => 'true',
                'type'        => QueryControlModule::QUERY_CONTROL_ID,
                'autocomplete' => [
                    'object' => QueryControlModule::QUERY_OBJECT_LIBRARY_TEMPLATE,
                    'query' => [
                        'posts_per_page' => -1,
                        'post_status' => [ 'publish', 'private' ],
                        'meta_query' => [
                            [
                                'key' => Document::TYPE_META_KEY,
                                'value' => ['section', 'container'],
                                'compare' => 'IN'
                            ],
                        ],
                    ],
                ],
                'condition'   => array(
                    'item_content_type' => 'template',
                ),
            )
        );
	    $repeater->add_control(
		    'item_enable_ajax',
		    array(
			    'label'        => esc_html__( 'Enable Ajax Load', 'lastudio-kit' ),
			    'type'         => Controls_Manager::SWITCHER,
			    'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
			    'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
			    'return_value' => 'yes',
			    'default'      => 'false',
			    'condition'   => array(
				    'item_content_type' => 'template',
			    ),
		    )
	    );

        $this->_add_control(
            'items_list',
            array(
                'type'    => Controls_Manager::REPEATER,
                'fields'  => $repeater->get_controls(),
                'default' => array(
                    array(
                        'item_image' => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title' => esc_html__( 'Item #1', 'lastudio-kit' ),
                        'item_text'  => esc_html__( 'Item #1 Description', 'lastudio-kit' ),
                    ),
                ),
                'title_field' => '{{{ item_title }}}',
            )
        );

        $this->_add_control(
            'item_link_type',
            array(
                'label'   => esc_html__( 'Item link type', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'link',
                'options' => array(
                    'link'     => esc_html__( 'Url', 'lastudio-kit' ),
                    'lightbox' => esc_html__( 'Lightbox', 'lastudio-kit' ),
                ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Settings', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'disable_default_css',
            array(
                'label'     => esc_html__( 'Disable default CSS', 'lastudio-kit' ),
                'description' => 'This option will remove the default css files if content type is template',
                'type'      => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default'   => ''
            )
        );

        $this->_add_control(
            'item_layout',
            array(
                'label'   => esc_html__( 'Items Layout', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'simple',
                'options' => array(
                    'banners'=> esc_html__( 'Banners', 'lastudio-kit' ),
                    'simple' => esc_html__( 'Simple', 'lastudio-kit' ),
                ),
            )
        );

        $this->_add_control(
            'animation_effect',
            array(
                'label'   => esc_html__( 'Animation Effect', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'lily',
                'options' => array(
                    'lily'   => esc_html__( 'Lily', 'lastudio-kit' ),
                    'sadie'  => esc_html__( 'Sadie', 'lastudio-kit' ),
                    'layla'  => esc_html__( 'Layla', 'lastudio-kit' ),
                    'oscar'  => esc_html__( 'Oscar', 'lastudio-kit' ),
                    'marley' => esc_html__( 'Marley', 'lastudio-kit' ),
                    'ruby'   => esc_html__( 'Ruby', 'lastudio-kit' ),
                    'roxy'   => esc_html__( 'Roxy', 'lastudio-kit' ),
                    'bubba'  => esc_html__( 'Bubba', 'lastudio-kit' ),
                    'romeo'  => esc_html__( 'Romeo', 'lastudio-kit' ),
                    'sarah'  => esc_html__( 'Sarah', 'lastudio-kit' ),
                    'chico'  => esc_html__( 'Chico', 'lastudio-kit' ),
                ),
                'condition' => array(
                    'item_layout' => 'banners',
                ),
            )
        );

        $this->_add_control(
            'img_size',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Images Size', 'lastudio-kit' ),
                'default'    => 'full',
                'options'    => lastudio_kit_helper()->get_image_sizes(),
            )
        );

        $this->_add_control(
            'title_html_tag',
            array(
                'label'   => esc_html__( 'Title HTML Tag', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => lastudio_kit_helper()->get_available_title_html_tags(),
                'default' => 'h5',
            )
        );

        $this->_add_control(
            'link_title',
            array(
                'label'     => esc_html__( 'Link Title', 'lastudio-kit' ),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => '',
                'condition' => array(
                    'item_layout' => 'simple',
                ),
            )
        );

        $this->_add_control(
            'equal_height_cols',
            array(
                'label'        => esc_html__( 'Equal Columns Height', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'true',
                'default'      => '',
            )
        );

        $this->_add_control(
            'equal_custom_img_height',
            array(
                'label'        => esc_html__( 'Custom Image Height?', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'true',
                'default'      => '',
                'prefix_class'  => 'la-ch-',
            )
        );

        $this->_add_responsive_control(
            'custom_img_height',
            array(
                'label'      => esc_html__( 'Image Height', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', 'vw', 'vh', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}}' => '--lakit-banner-image-height: {{SIZE}}{{UNIT}}',
                ),
                'condition' => array(
                    'equal_custom_img_height' => 'true',
                ),
            )
        );
	    $this->_add_control(
		    'custom_img_type',
		    array(
			    'label'   => esc_html__( 'Cropped Type', 'lastudio-kit' ),
			    'type'    => Controls_Manager::SELECT,
			    'options' => [
				    'none' => esc_html__( 'None', 'lastudio-kit' ),
				    'cover' => esc_html__( 'Cover', 'lastudio-kit' ),
				    'fill' => esc_html__( 'Fill', 'lastudio-kit' ),
				    'contain' => esc_html__( 'Contain', 'lastudio-kit' ),
				    'scale-down' => esc_html__( 'Scale Down', 'lastudio-kit' ),
			    ],
			    'default' => 'cover',
			    'condition' => array(
				    'equal_custom_img_height' => 'true',
			    ),
			    'selectors'  => array(
				    '{{WRAPPER}} .lakit-carousel .lakit-banner__img,{{WRAPPER}} .lakit-carousel .lakit-carousel__item-img' => 'object-fit: {{value}}',
			    ),
		    )
	    );
        $this->_add_control(
            'custom_img_position',
            array(
                'label'   => esc_html__( 'Cropped Position', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'center' => esc_html__( 'Center', 'lastudio-kit' ),
                    'top' => esc_html__( 'Top', 'lastudio-kit' ),
                    'bottom' => esc_html__( 'Bottom', 'lastudio-kit' ),
                ],
                'default' => 'center',
                'condition' => array(
                    'equal_custom_img_height' => 'true',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-carousel .lakit-banner__img,{{WRAPPER}} .lakit-carousel .lakit-carousel__item-img' => 'object-position: {{value}}',
                ),
            )
        );

        $this->_end_controls_section();

        $this->register_carousel_section([], false, false);

        $css_scheme = apply_filters(
            'lastudio-kit/advanced-carousel/css-schema',
            array(
                'arrow_next'     => '.lakit-carousel .lakit-arrow.next-arrow',
                'arrow_prev'     => '.lakit-carousel .lakit-arrow.prev-arrow',
                'arrow_next_hov' => '.lakit-carousel .lakit-arrow.next-arrow:hover',
                'arrow_prev_hov' => '.lakit-carousel .lakit-arrow.prev-arrow:hover',
                'dot'            => '.lakit-carousel .lakit-carousel__dots .swiper-pagination-bullet',
                'dot_hover'      => '.lakit-carousel .lakit-carousel__dots .swiper-pagination-bullet:hover',
                'dot_active'     => '.lakit-carousel .lakit-carousel__dots .swiper-pagination-bullet-active',
                'wrap'           => '.lakit-carousel',
                'carousel_inner' => '.lakit-carousel-inner',
                'column'         => '.lakit-carousel .lakit-carousel__item',
                'image'          => '.lakit-carousel__item-img',
                'items'          => '.lakit-carousel__content',
                'items_title'    => '.lakit-carousel__content .lakit-carousel__item-title',
                'items_text'     => '.lakit-carousel__content .lakit-carousel__item-text',
                'items_icon'     => '.lakit-carousel__item-icon',
                'items_icon_inner' => '.lakit-icon-inner',
                'items_button'   => '.lakit-carousel__item-button',
                'button_icon'    => '.lakit-carousel__item-button .btn-icon',
                'banner'         => '.lakit-banner',
                'banner_content' => '.lakit-banner__content',
                'banner_overlay' => '.lakit-banner__overlay',
                'banner_title'   => '.lakit-banner__title',
                'banner_text'    => '.lakit-banner__text',
            )
        );

        $this->_start_controls_section(
            'section_column_style',
            array(
                'label'      => esc_html__( 'Item', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_responsive_control(
            'item_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'top'    => array(
                        'title' => esc_html__( 'Top', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Middle', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'bottom' => array(
                        'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'selectors_dictionary' => [
                    'top'    => 'justify-content: flex-start;',
                    'center' => 'justify-content: center;',
                    'bottom' => 'justify-content: flex-end;',
                ],
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-carousel__item-inner' => '{{VALUE}}',
                ),
                'condition'  => array(
                    'carousel_direction' => 'vertical',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'column_padding',
            array(
                'label'       => esc_html__( 'Item Margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => ['px', '%', 'em', 'custom'],
                'render_type' => 'template',
                'selectors'   => array(
                    '{{WRAPPER}} ' . $css_scheme['column'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' => '--lakit-carousel-item-top-space: {{TOP}}{{UNIT}}; --lakit-carousel-item-right-space: {{RIGHT}}{{UNIT}};--lakit-carousel-item-bottom-space: {{BOTTOM}}{{UNIT}};--lakit-carousel-item-left-space: {{LEFT}}{{UNIT}};--lakit-gcol-top-space: {{TOP}}{{UNIT}}; --lakit-gcol-right-space: {{RIGHT}}{{UNIT}};--lakit-gcol-bottom-space: {{BOTTOM}}{{UNIT}};--lakit-gcol-left-space: {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'column_margin',
            array(
                'label'       => esc_html__( 'Item Padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => ['px', '%', 'em', 'custom'],
                'selectors'   => array(
                    '{{WRAPPER}} .lakit-carousel__item-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'column_bg',
                'selector' => '{{WRAPPER}} .lakit-carousel__item-inner'
            ),
            25
        );
        $this->_add_responsive_control(
            'column_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-carousel__item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'column_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector' => '{{WRAPPER}} .lakit-carousel__item-inner'
            ),
            50
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'column_shadow',
                'selector' => '{{WRAPPER}} .lakit-carousel__item-inner'
            ),
            75
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_simple_item_style',
            array(
                'label'      => esc_html__( 'Simple Item', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition'  => array(
                    'item_layout' => 'simple',
                    'disable_default_css!' => 'true'
                ),
            )
        );

        $this->_add_control(
            'item_image_heading',
            array(
                'label' => esc_html__( 'Image', 'lastudio-kit' ),
                'type'  => Controls_Manager::HEADING,
            ),
            75
        );

        $this->add_responsive_control(
            'item_image_size',
            array(
                'label' => esc_html__( 'Image Size', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh', 'custom' ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['image'] => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'item_image_maxheight',
            array(
                'label' => esc_html__( 'Image Max Height', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh', 'custom'),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['image'] => 'max-height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'item_image_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors_dictionary' => [
                    'left'    => 'text-align:left; align-items: flex-start;',
                    'center' => 'text-align:center; align-items: center;',
                    'right' => 'text-align:right; align-items: flex-end;',
                ],
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-carousel__item-link' => '{{VALUE}}',
                ),
            ),
            25
        );

        $this->_start_controls_tabs( 'simple_image_effects' );

        $this->_start_controls_tab( 'simple_image_effects_normal',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'simple_image_scale',
            [
                'label' => esc_html__( 'Scale', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 2,
                        'min' => 0.0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-carousel__item-inner img' => 'transform: scale({{SIZE}});',
                ],
            ]
        );

        $this->_add_control(
            'simple_image_opacity',
            [
                'label' => esc_html__( 'Opacity', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-carousel__item-inner img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'simple_image_css_filters',
                'selector' => '{{WRAPPER}} .lakit-carousel__item-inner img',
            ]
        );

        $this->end_controls_tab();

        $this->_start_controls_tab( 'simple_image_effects_hover',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'simple_image_scale_hover',
            [
                'label' => esc_html__( 'Scale', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 2,
                        'min' => 0.0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-carousel__item-inner:hover img' => 'transform: scale({{SIZE}});',
                ],
            ]
        );

        $this->_add_control(
            'simple_image_opacity_hover',
            [
                'label' => esc_html__( 'Opacity', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-carousel__item-inner:hover img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'simple_image_css_filters_hover',
                'selector' => '{{WRAPPER}} .lakit-carousel__item-inner:hover img',
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_responsive_control(
            'item_image_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['image'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            75
        );

        $this->_add_responsive_control(
            'item_image_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['image'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_responsive_control(
            'item_image_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['image'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'item_image_bg',
                'selector' => '{{WRAPPER}} ' . $css_scheme['image'],
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'item_image_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['image'],
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'item_image_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['image'],
            ),
            75
        );

        $this->_add_control(
            'item_content_heading',
            array(
                'label'     => esc_html__( 'Content', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ),
            25
        );

	    $this->_add_responsive_control(
		    'items_alignment',
		    array(
			    'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
			    'type'    => Controls_Manager::CHOOSE,
			    'default' => 'left',
			    'options' => array(
				    'left'    => array(
					    'title' => esc_html__( 'Left', 'lastudio-kit' ),
					    'icon'  => 'eicon-text-align-left',
				    ),
				    'center' => array(
					    'title' => esc_html__( 'Center', 'lastudio-kit' ),
					    'icon'  => 'eicon-text-align-center',
				    ),
				    'right' => array(
					    'title' => esc_html__( 'Right', 'lastudio-kit' ),
					    'icon'  => 'eicon-text-align-right',
				    ),
			    ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['items'] => 'text-align: {{VALUE}};',
			    ),
		    ),
		    25
	    );

	    $this->_add_control(
		    'enable_zoom_on_hover',
		    array(
			    'label'     => esc_html__( 'Enable Zoom on hover', 'lastudio-kit' ),
			    'type'      => Controls_Manager::SWITCHER,
			    'return_value' => 'lakit--enable-zoom-hover',
			    'default'   => '',
			    'prefix_class' => ''
		    )
	    );
	    $this->_add_responsive_control(
		    'content_zoom_level',
		    [
			    'label' => esc_html__( 'Level', 'lastudio-kit' ),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => [
					    'min' => 0.5,
					    'max' => 2.0,
					    'step' => 0.1
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}}' => '--lakit-content-zoom-lv: {{SIZE}}',
			    ],
			    'condition'   => array(
				    'enable_zoom_on_hover!' => '',
			    ),
		    ]
	    );

        $this->_start_controls_tabs( 'tabs_item_style' );

        $this->_start_controls_tab(
            'tab_item_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'simple_item_bg',
                'selector' => '{{WRAPPER}} ' . $css_scheme['items'],
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'item_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['items'],
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'item_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['items'],
            ),
            100
        );

	    $this->_add_responsive_control(
		    'items_padding',
		    array(
			    'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['items'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    ),
		    50
	    );

	    $this->_add_responsive_control(
		    'items_margin',
		    array(
			    'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['items'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    ),
		    50
	    );

	    $this->_add_responsive_control(
		    'items_border_radius',
		    array(
			    'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['items'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    ),
		    75
	    );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_item_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'simple_item_bg_hover',
                'selector' => '{{WRAPPER}} .lakit-carousel__item:hover ' . $css_scheme['items'],
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'item_border_hover',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .lakit-carousel__item:hover ' . $css_scheme['items'],
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'item_box_shadow_hover',
                'selector' => '{{WRAPPER}} .lakit-carousel__item:hover ' . $css_scheme['items'],
            ),
            100
        );

	    $this->_add_responsive_control(
		    'items_padding_hover',
		    array(
			    'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
			    'selectors'  => array(
				    '{{WRAPPER}} .lakit-carousel__item:hover ' . $css_scheme['items'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    ),
		    50
	    );

	    $this->_add_responsive_control(
		    'items_margin_hover',
		    array(
			    'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
			    'selectors'  => array(
				    '{{WRAPPER}} .lakit-carousel__item:hover ' . $css_scheme['items'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    ),
		    50
	    );

	    $this->_add_responsive_control(
		    'items_border_radius_hover',
		    array(
			    'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%' ),
			    'selectors'  => array(
				    '{{WRAPPER}} .lakit-carousel__item:hover ' . $css_scheme['items'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    ),
		    75
	    );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_banner_item_style',
            array(
                'label'      => esc_html__( 'Banner Item', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition'  => array(
                    'item_layout' => 'banners',
                    'disable_default_css!' => 'true'
                ),
            )
        );

	    $this->_add_responsive_control(
		    'text_alignment',
		    array(
			    'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
			    'type'    => Controls_Manager::CHOOSE,
			    'options' => array(
				    'left'    => array(
					    'title' => esc_html__( 'Left', 'lastudio-kit' ),
					    'icon'  => 'eicon-text-align-left',
				    ),
				    'center' => array(
					    'title' => esc_html__( 'Center', 'lastudio-kit' ),
					    'icon'  => 'eicon-text-align-center',
				    ),
				    'right' => array(
					    'title' => esc_html__( 'Right', 'lastudio-kit' ),
					    'icon'  => 'eicon-text-align-right',
				    ),
			    ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['banner_content'] => 'text-align: {{VALUE}};',
			    ),
		    ),
		    25
	    );

        $this->_start_controls_tabs( 'tabs_background' );

        $this->_start_controls_tab(
            'tab_background_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'items_content_color',
            array(
                'label'     => esc_html__( 'Additional Elements Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-effect-layla ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-layla ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-oscar ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-marley ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-ruby ' . $css_scheme['banner_text'] => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-roxy ' . $css_scheme['banner_text'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-roxy ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-bubba ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-bubba ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-romeo ' . $css_scheme['banner_content'] . '::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-romeo ' . $css_scheme['banner_content'] . '::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-sarah ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-chico ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['banner_overlay'],
            ),
            25
        );

        $this->_add_control(
            'normal_opacity',
            array(
                'label'   => esc_html__( 'Opacity', 'lastudio-kit' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '0',
                'min'     => 0,
                'max'     => 1,
                'step'    => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_overlay'] => 'opacity: {{VALUE}};',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_background_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'items_content_hover_color',
            array(
                'label'     => esc_html__( 'Additional Elements Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-effect-layla:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-layla:hover ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-oscar:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-marley:hover ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-ruby:hover ' . $css_scheme['banner_text'] => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-roxy:hover ' . $css_scheme['banner_text'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-roxy:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-bubba:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-bubba:hover ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-romeo:hover ' . $css_scheme['banner_content'] . '::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-romeo:hover ' . $css_scheme['banner_content'] . '::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-sarah:hover ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-effect-chico:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'background_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['banner'] . ':hover ' . $css_scheme['banner_overlay'],
            ),
            25
        );

        $this->_add_control(
            'hover_opacity',
            array(
                'label'   => esc_html__( 'Opacity', 'lastudio-kit' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '0.4',
                'min'     => 0,
                'max'     => 1,
                'step'    => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner'] . ':hover ' . $css_scheme['banner_overlay'] => 'opacity: {{VALUE}};',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

	    $this->_start_controls_section(
		    'section_icon_style',
		    array(
			    'label'      => esc_html__( 'Item Icon', 'lastudio-kit' ),
			    'tab'        => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'disable_default_css!' => 'true'
                ]
		    )
	    );

	    $this->_add_responsive_control(
		    'item_icon_size',
		    [
			    'label' => esc_html__( 'Size', 'lastudio-kit' ),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => [
					    'min' => 6,
					    'max' => 300,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} ' . $css_scheme['items_icon_inner'] => 'font-size: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );
	    $this->_add_responsive_control(
		    'item_icon_padding',
		    array(
			    'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['items_icon_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    ),
		    50
	    );
	    $this->_add_responsive_control(
		    'item_icon_margin',
		    array(
			    'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['items_icon_inner'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    ),
		    50
	    );

	    $this->_start_controls_tabs( 'tabs_item_icon' );
	    $this->_start_controls_tab(
		    'tab_item_icon_normal',
		    [
			    'label' => esc_html__( 'Normal', 'lastudio-kit' ),
		    ]
	    );
	    $this->_add_control(
		    'item_icon_color',
		    [
			    'label' => esc_html__( 'Color', 'lastudio-kit' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} ' . $css_scheme['items_icon_inner'] => 'color: {{VALUE}};',
			    ],
		    ]
	    );
	    $this->_add_control(
		    'item_icon_bgcolor',
		    [
			    'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} ' . $css_scheme['items_icon_inner'] => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );
	    $this->_add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name' => 'item_icon_border',
			    'label' => esc_html__( 'Border', 'lastudio-kit' ),
			    'selector' => '{{WRAPPER}} ' . $css_scheme['items_icon_inner'],
		    ]
	    );
	    $this->_add_responsive_control(
		    'item_icon_radius',
		    [
			    'label' =>esc_html__( 'Border Radius', 'lastudio-kit' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px'],
			    'selectors' => [
				    '{{WRAPPER}} ' . $css_scheme['items_icon_inner'] =>  'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );
	    $this->_add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'item_icon_shadow',
			    'selector' => '{{WRAPPER}} ' . $css_scheme['items_icon_inner'],
		    ]
	    );

	    $this->_end_controls_tab();
	    $this->_start_controls_tab(
		    'tab_item_icon_hover',
		    [
			    'label' => esc_html__( 'Hover', 'lastudio-kit' ),
		    ]
	    );
	    $this->_add_control(
		    'item_icon_color_hover',
		    [
			    'label' => esc_html__( 'Color', 'lastudio-kit' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .lakit-carousel__item:hover ' . $css_scheme['items_icon_inner'] => 'color: {{VALUE}}',
			    ],
		    ]
	    );
	    $this->_add_control(
		    'item_icon_bgcolor_hover',
		    [
			    'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .lakit-carousel__item:hover ' . $css_scheme['items_icon_inner'] => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );
	    $this->_add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name' => 'item_icon_border_hover',
			    'label' => esc_html__( 'Border', 'lastudio-kit' ),
			    'selector' => '{{WRAPPER}} .lakit-carousel__item:hover ' . $css_scheme['items_icon_inner'],
		    ]
	    );
	    $this->_add_responsive_control(
		    'item_icon_radius_hover',
		    [
			    'label' =>esc_html__( 'Border Radius', 'lastudio-kit' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px'],
			    'selectors' => [
				    '{{WRAPPER}} .lakit-carousel__item:hover ' . $css_scheme['items_icon_inner'] =>  'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );
	    $this->_add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'item_icon_shadow_hover',
			    'selector' => '{{WRAPPER}} .lakit-carousel__item:hover ' . $css_scheme['items_icon_inner'],
		    ]
	    );
	    $this->_end_controls_tab();
	    $this->_end_controls_tabs();

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_item_title_style',
            array(
                'label'      => esc_html__( 'Item Title', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'disable_default_css!' => 'true'
                ]
            )
        );

        $this->_start_controls_tabs( 'tabs_title_style' );

        $this->_start_controls_tab(
            'tab_title_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'items_title_color',
            array(
                'label'     => esc_html__( 'Title Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['items_title'] => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['banner_title'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_title_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'items_title_color_hover',
            array(
                'label'     => esc_html__( 'Title Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel__item:hover ' . $css_scheme['items_title'] => 'color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-carousel__item:hover ' . $css_scheme['banner_title'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'items_title_link_color_hover',
            array(
                'label'     => esc_html__( 'Link Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel__item ' . $css_scheme['items_title'] . ' a:hover' => 'color: {{VALUE}}',
                ),
                'condition' => array(
                    'item_layout' => 'simple',
                    'link_title' => 'yes',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'items_title_typography',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['items_title'] . ', {{WRAPPER}}  ' . $css_scheme['items_title'] . ' a, {{WRAPPER}} ' . $css_scheme['banner_title'],
                'separator' => 'before',
            ),
            50
        );

        $this->_add_responsive_control(
            'items_title_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['items_title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['banner_title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );
        $this->_add_responsive_control(
            'items_title_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['items_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['banner_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_item_text_style',
            array(
                'label'      => esc_html__( 'Item Description', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'disable_default_css!' => 'true'
                ]
            )
        );

        $this->_start_controls_tabs( 'tabs_text_style' );

        $this->_start_controls_tab(
            'tab_text_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'items_text_color',
            array(
                'label'     => esc_html__( 'Content Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['items_text'] => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['banner_text'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_text_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'items_text_color_hover',
            array(
                'label'     => esc_html__( 'Content Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel__item:hover ' . $css_scheme['items_text'] => 'color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-carousel__item:hover ' . $css_scheme['banner_text'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'items_text_typography',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['items_text'] . ', {{WRAPPER}} ' . $css_scheme['banner_text'],
                'separator' => 'before',
            ),
            50
        );

        $this->_add_responsive_control(
            'items_text_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['items_text'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['banner_text'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'items_text_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['items_text'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['banner_text'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_end_controls_section();

        /**
         * Action Button Style Section
         */
        $this->_start_controls_section(
            'section_action_button_style',
            array(
                'label'      => esc_html__( 'Action Button', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'disable_default_css!' => 'true'
                ]
            )
        );

	    $this->_add_control(
		    'show_btn_hover',
		    array(
			    'label'     => esc_html__( 'Show Button On Hover', 'lastudio-kit' ),
			    'type'      => Controls_Manager::SWITCHER,
			    'default'   => '',
			    'prefix_class' => 'lakit--show-btn-hover-'
		    )
	    );

	    $this->_add_control(
		    'full_btn',
		    array(
			    'label'     => esc_html__( 'Button Fullwidth', 'lastudio-kit' ),
			    'type'      => Controls_Manager::SWITCHER,
			    'default'   => '',
			    'return_value'   => 'lakit--btn-fullwidth',
			    'prefix_class' => ''
		    )
	    );

	    $this->_add_icon_control(
		    'btn_icon',
		    [
			    'label'       => __( 'Add Icon', 'lastudio-kit' ),
			    'type'        => Controls_Manager::ICON,
			    'file'        => '',
			    'skin'        => 'inline',
			    'label_block' => false
		    ]
	    );

	    $this->_add_control(
		    'btn_icon_position',
		    array(
			    'label'     => esc_html__( 'Icon Position', 'lastudio-kit' ),
			    'type'      => Controls_Manager::SELECT,
			    'options'   => array(
				    'row-reverse' => esc_html__( 'Before Text', 'lastudio-kit' ),
				    'row'         => esc_html__( 'After Text', 'lastudio-kit' ),
			    ),
			    'default'   => 'row',
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['items_button'] => 'flex-direction: {{VALUE}}',
			    ),
		    )
	    );

	    $this->add_responsive_control(
		    'btn_icon_size',
		    array(
			    'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
			    'type'  => Controls_Manager::SLIDER,
			    'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['button_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->_add_responsive_control(
		    'button_icon_padding',
		    array(
			    'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['button_icon'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
			    'separator' => 'after',
		    ),
		    50
	    );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['items_button'],
            ),
            50
        );

        $this->_add_responsive_control(
            'button_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['items_button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'button_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['items_button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_responsive_control(
            'button_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['items_button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_start_controls_tabs( 'tabs_button_style' );

        $this->_start_controls_tab(
            'tab_button_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'button_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['items_button'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'button_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['items_button'] => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'button_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['items_button'],
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'button_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['items_button'],
            ),
            100
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_button_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'button_hover_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel__content ' . $css_scheme['items_button'] . ':hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-banner:hover' . $css_scheme['items_button'] => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'primary_button_hover_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel__content ' . $css_scheme['items_button'] . ':hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-banner:hover' . $css_scheme['items_button'] => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'button_hover_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'selector'    => '{{WRAPPER}} .lakit-carousel__content ' . $css_scheme['items_button'] . ':hover, {{WRAPPER}} .lakit-banner:hover' . $css_scheme['items_button']
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'button_hover_box_shadow',
                'selector'    => '{{WRAPPER}} .lakit-carousel__content ' . $css_scheme['items_button'] . ':hover, {{WRAPPER}} .lakit-banner:hover' . $css_scheme['items_button']
            ),
            100
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

        $this->register_carousel_arrows_dots_style_section();

    }

    protected function render() {

        $this->_context = 'render';
        $css_selector = sprintf('.elementor-element-%1$s .swiper-wrapper', $this->get_id());
        $css = lastudio_kit_helper()->get_css_by_responsive_columns( lastudio_kit_helper()->get_attribute_with_all_breakpoints('carousel_columns', $this->get_settings_for_display()), $css_selector );
        if(!empty($css)){
            echo sprintf('<style>%1$s</style>', $css);
        }
        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();
    }

    public function get_advanced_carousel_options( $carousel_columns = false, $widget_id = '', $settings = null ) {
        $opts = parent::get_advanced_carousel_options($carousel_columns, $widget_id, $settings);
        $opts = array_merge([
            'content_selector' => 'banners' == $this->get_settings_for_display('item_layout') ? '.lakit-banner__content' : '.lakit-carousel__content',
            'content_effect_in' => 'fadeInUp',
            'content_effect_out' => 'fadeOutDown',
        ], $opts);
        return $opts;
    }

    public function get_advanced_carousel_img( $class = '' ) {

        $settings = $this->get_settings_for_display();
        $size     = isset( $settings['img_size'] ) ? $settings['img_size'] : 'full';

        $item_settings = $this->_processed_item;
        $item_settings['item_image_size'] = $size;

        if(empty( $item_settings['item_image']['url'] )){
            return;
        }

        $img_html = Group_Control_Image_Size::get_attachment_image_html( $item_settings, 'item_image' );

        $img_html = str_replace('class="', 'class="' . $class . ' ', $img_html);

        return $img_html;

    }

	protected function _loop_icon( $format ){
		$item = $this->_processed_item;
		return $this->_get_icon_setting( $item['item_icon'], $format );
	}

	protected function _btn_icon( $format ){
    	$settings = $this->get_settings_for_display();
		return $this->_get_icon_setting( $settings['selected_btn_icon'], $format );
	}

    /**
     * Get item template content.
     *
     * @return string|void
     */
    protected function _loop_item_template_content() {
        $template_id = $this->_processed_item['template_id'];

        $template_id = apply_filters('wpml_object_id', $template_id, 'elementor_library', true);

        if ( empty( $template_id ) ) {
            return;
        }

        // for multi-language plugins
        $template_id = apply_filters( 'lastudio-kit/widgets/template_id', $template_id, $this );

	    $item_enable_ajax = !empty($this->_processed_item['item_enable_ajax']) && filter_var($this->_processed_item['item_enable_ajax'], FILTER_VALIDATE_BOOLEAN);

	    if($item_enable_ajax && !Plugin::instance()->editor->is_edit_mode()){
		    $content = '<div data-lakit_ajax_loadtemplate="true" data-template-id="'.esc_attr($template_id).'"><span class="lakit-css-loader"></span></div>';
	    }
	    else{
			ob_start();
            if(Plugin::instance()->editor->is_edit_mode()){
                $css_file = Post_CSS::create( $template_id );
                echo sprintf('<link rel="stylesheet" id="elementor-post-%1$s-css" href="%2$s" type="text/css" media="all" />', $template_id, $css_file->get_url() );
            }
		    echo Plugin::$instance->frontend->get_builder_content( $template_id, false );
		    $content  = ob_get_clean();
	    }

        if ( lastudio_kit()->elementor()->editor->is_edit_mode() ) {
            $edit_url = add_query_arg(
                array(
                    'elementor' => '',
                ),
                get_permalink( $template_id )
            );
            $edit_link = sprintf(
                '<a class="lastudio-kit-edit-template-link" data-template-edit-link="%1$s" href="%1$s" title="%2$s" target="_blank"><span class="dashicons dashicons-edit"></span></a>',
                esc_url( $edit_url ),
                esc_html__( 'Edit Template', 'lastudio-kit' )
            );
            $content .= $edit_link;
        }
        return $content;
    }
}