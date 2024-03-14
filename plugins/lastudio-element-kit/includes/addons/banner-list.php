<?php

/**
 * Class: LaStudioKit_Banner_List
 * Name: Banner List
 * Slug: lakit-banner-list
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * LaStudioKit_Banner_List Widget
 */
class LaStudioKit_Banner_List extends LaStudioKit_Base {
    
    /**
     * [$item_counter description]
     * @var integer
     */
    public $item_counter = 0;

    protected function enqueue_addon_resources(){
	    $this->add_script_depends( 'jquery-isotope' );
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    if(!lastudio_kit()->is_optimized_css_mode()){
			    wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/banner-list.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
			    $this->add_style_depends( $this->get_name() );
			}
		    $this->add_script_depends( 'lastudio-kit-base' );
	    }
    }

	public function get_widget_css_config($widget_name){
		$file_url = lastudio_kit()->plugin_url( 'assets/css/addons/banner-list.min.css' );
		$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/banner-list.min.css' );
		return [
			'key' => $widget_name,
			'version' => lastudio_kit()->get_version(true),
//			'version' => '1.0.0',
			'file_path' => $file_path,
			'data' => [
				'file_url' => $file_url
			]
		];
	}

    public function get_name() {
        return 'lakit-banner-list';
    }

    protected function get_widget_title() {
        return esc_html__( 'Banner List', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

	public function get_keywords() {
		return [ 'banner', 'image', 'gallery', 'carousel', 'slide' ];
	}

    protected function register_controls() {

        $css_scheme = apply_filters(
            'lastudio-kit/banner-list/css-schema',
            array(
                'instance'          => '.lakit-bannerlist',
                'list_container'    => '.lakit-bannerlist__list',
                'item'              => '.lakit-bannerlist__item',
                'inner'             => '.lakit-bannerlist__inner',
                'image'             => '.lakit-bannerlist__image',
                'image_instance'    => '.lakit-bannerlist__image-instance',
                'content'           => '.lakit-bannerlist__content',
                'content_inner'     => '.lakit-bannerlist__content-inner',
                'subtitle'          => '.lakit-bannerlist__subtitle',
                'title'             => '.lakit-bannerlist__title',
                'desc'              => '.lakit-bannerlist__desc',
                'subdesc'           => '.lakit-bannerlist__subdesc',
                'button'            => '.lakit-bannerlist__btn',
                'button_icon'       => '.btn-icon',
            )
        );

        $this->_register_section_setting($css_scheme);

        $this->_register_section_items($css_scheme);

        $this->register_masonry_setting_section( [ 'enable_masonry' => 'yes' ], false );

        $this->register_carousel_section( [ 'enable_masonry!' => 'yes' ], 'columns');

        $this->_register_section_general_styles($css_scheme);

        $this->register_carousel_arrows_dots_style_section( [ 'enable_masonry!' => 'yes' ] );
    }
    /**
     * Get loop image html
     *
     */

	public function render_loop_image_item( $format = '%s' , $echo = true ){

		$html = '';
		$image_data = $this->_loop_image_item('item_image', '', false);
		if(!empty($image_data[0])){
			$giflazy = $image_data[0];
			$srcset = sprintf('width="%1$d" height="%2$d" style="--img-height:%3$dpx"', $image_data[1], $image_data[2], $image_data[2]);
			$_img_html = sprintf( apply_filters('lastudio-kit/banner-list/image-format', '<img src="%1$s" alt="" loading="lazy" class="%3$s" %4$s>'), $giflazy, $image_data[0], 'lakit-bannerlist__image-instance' , $srcset);
			$html = sprintf($format, $_img_html);
		}
        if($echo){
            echo $html;
        }
		else{
            return $html;
        }
	}

    public function get_loop_image_item() {
        $image_data = $this->_loop_image_item('item_image', '', false);
        if(!empty($image_data[0])){
	        $giflazy = $image_data[0];
            $srcset = sprintf('width="%1$d" height="%2$d" style="--img-height:%3$dpx"', $image_data[1], $image_data[2], $image_data[2]);
            return sprintf( apply_filters('lastudio-kit/banner-list/image-format', '<img src="%1$s" alt="" loading="lazy" class="%3$s" %4$s>'), $giflazy, $image_data[0], 'lakit-bannerlist__image-instance' , $srcset);
        }
        return '';
    }

    /**
     * Get loop image html
     *
     */
    protected function _loop_image_item( $key = '', $format = '%s', $html_return = true ) {
        $item = $this->_processed_item;
        $params = [];

        if ( ! array_key_exists( $key, $item ) ) {
            return false;
        }

        $image_item = $item[ $key ];

        if ( ! empty( $image_item['id'] ) && wp_attachment_is_image($image_item['id']) ) {
            $image_data = wp_get_attachment_image_src( $image_item['id'], 'full' );

            $params[] = apply_filters('lastudio_wp_get_attachment_image_url', $image_data[0]);
            $params[] = $image_data[1];
            $params[] = $image_data[2];
        }
        else {
            $params[] = isset($image_item['url']) ? $image_item['url'] : Utils::get_placeholder_image_src();
            $params[] = 1200;
            $params[] = 800;
        }

        if($html_return){
            return vsprintf( $format, $params );
        }
        else{
            return $params;
        }
    }

    protected function render() {

        $this->_context = 'render';
        $css_selector = sprintf('.elementor-element-%1$s .swiper-wrapper', $this->get_id());
        $css = lastudio_kit_helper()->get_css_by_responsive_columns( lastudio_kit_helper()->get_attribute_with_all_breakpoints('columns', $this->get_settings_for_display()), $css_selector );
        if(!empty($css)){
            echo sprintf('<style>%1$s</style>', $css);
        }
        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();
    }

    private function _register_section_setting( $css_scheme ){
        $this->start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Settings', 'lastudio-kit' ),
            )
        );
        $this->add_control(
            'layout_type',
            array(
                'label'   => esc_html__( 'Layout type', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'overlay',
                'options' => array(
                    'overlay'    => esc_html__( 'Overlay', 'lastudio-kit' ),
                    'flat'    => esc_html__( 'Flat', 'lastudio-kit' ),
                ),
            )
        );

        $this->add_control(
            'preset_overlay',
            array(
                'label'   => esc_html__( 'Preset', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'prefix_class' => 'bannerlist--preset-',
                'options' => apply_filters('lastudio-kit/banner-list/preset_overlay', [
                    'default' => esc_html__( 'Default', 'lastudio-kit' ),
                ]),
                'condition' => [
                    'layout_type' => 'overlay'
                ]
            )
        );
        $this->add_control(
            'preset_flat',
            array(
                'label'   => esc_html__( 'Preset', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'prefix_class' => 'bannerlist--preset-',
                'options' => apply_filters('lastudio-kit/banner-list/preset_flat', [
                    'default' => esc_html__( 'Default', 'lastudio-kit' ),
                    'flat02' => esc_html__( 'Type 02', 'lastudio-kit' ),
                    'flat03' => esc_html__( 'Type 03', 'lastudio-kit' ),
                ]),
                'render_type' => 'template',
                'condition' => [
                    'layout_type' => 'flat'
                ]
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'   => esc_html__( 'Columns', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 3,
                'options' => lastudio_kit_helper()->get_select_range( 10 ),
            )
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__( 'Title HTML Tag', 'lastudio-kit' ),
                'type'  => Controls_Manager::SELECT,
                'options' => array(
                    'h1'  => 'H1',
                    'h2'  => 'H2',
                    'h3'  => 'H3',
                    'h4'  => 'H4',
                    'h5'  => 'H5',
                    'h6'  => 'H6',
                    'div' => 'div',
                ),
                'default' => 'div'
            ]
        );

        $this->_add_control(
            'enable_masonry',
            array(
                'type'         => 'switcher',
                'label'        => esc_html__( 'Enable Masonry?', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $this->end_controls_section();
    }

    private function _register_section_items( $css_scheme ){

        $this->start_controls_section(
            'section_items_data',
            array(
                'label' => esc_html__( 'Items', 'lastudio-kit' ),
            )
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs( 'items_repeater' );

        $repeater->start_controls_tab( 'background', [ 'label' => __( 'Image', 'lastudio-kit' ) ] );

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

        $repeater->end_controls_tab();

        $repeater->start_controls_tab( 'content', [ 'label' => __( 'Content', 'lastudio-kit' ) ] );

        $repeater->add_control(
            'subtitle',
            [
                'label' => __( 'Sub Title', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => __( 'Title', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Banner Title', 'lastudio-kit' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => __( 'Description', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'subdescription',
            [
                'label' => __( 'Sub-Description', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'button_text',
            [
                'label' => __( 'Button Text', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Click Here', 'lastudio-kit' ),
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __( 'Link', 'lastudio-kit' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'lastudio-kit' ),
            ]
        );

        $repeater->add_control(
            'link_click',
            [
                'label' => __( 'Apply Link On', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'item' => __( 'Whole item', 'lastudio-kit' ),
                    'button' => __( 'Button Only', 'lastudio-kit' ),
                ],
                'default' => 'item',
                'condition' => [
                    'link[url]!' => '',
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab( 'style', [ 'label' => __( 'Style', 'lastudio-kit' ) ] );

        $repeater->add_control(
            'el_class',
            [
                'label' => __( 'Item CSS Class', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT
            ]
        );
         $repeater->add_control(
            'custom_attributes',
            [
                'label' => __( 'Custom Attributes', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __( 'key|value', 'lastudio-kit' ),
                'description' => __( 'Set custom attributes for the link element. Separate attribute keys from values using the | (pipe) character. Separate key-value pairs with a comma.', 'lastudio-kit' ),
                'classes' => 'elementor-control-direction-ltr',
            ]
        );


        $repeater->add_control(
            'custom_style',
            [
                'label' => __( 'Custom', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'description' => __( 'Set custom style that will only affect this specific item.', 'lastudio-kit' ),
            ]
        );

        $repeater->add_responsive_control(
            'icontent_width',
            [
                'label' => __( 'Content Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--lakit-bannerlist-content-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'icontent_horizontal',
            [
                'label' => esc_html__( 'Horizontal Orientation', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => is_rtl() ? 'right' : 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'icontent_offset_x',
            [
                'label' => esc_html__( 'Offset', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['content_inner'] => 'left: initial; right: initial;{{icontent_horizontal.VALUE}}: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'icontent_vertical',
            [
                'label' => esc_html__( 'Vertical Orientation', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => esc_html__( 'Top', 'lastudio-kit' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'top',
                'toggle' => false,
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'icontent_offset_y',
            [
                'label' => esc_html__( 'Offset', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['content_inner'] => 'top: initial; bottom: initial;{{icontent_vertical.VALUE}}: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'icontent_padding',
            [
                'label' => __( 'Content Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--lakit-bannerlist-content-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'icontent_margin',
            [
                'label' => __( 'Content Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--lakit-bannerlist-content-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'itext_align',
            [
                'label' => __( 'Text Align', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors_dictionary' => [
                    'left' => '--e_bnl-text-align: left;--e_bnl-align: flex-start;',
                    'center' => '--e_bnl-text-align: center;--e_bnl-align: center;',
                    'right' => '--e_bnl-text-align: right;--e_bnl-align: flex-end;',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '{{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'isubtitle_color',
            [
                'label' => __( 'Sub-Title Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--e-subtitle-color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

		$repeater->add_control(
            'isubtitle_color_hover',
            [
                'label' => __( 'Sub-Title Hover Color ', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--e-subtitle-hover-color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'ititle_color',
            [
                'label' => __( 'Title Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
	                '{{WRAPPER}} {{CURRENT_ITEM}}' => '--e-title-color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

		$repeater->add_control(
            'ititle_color_hover',
            [
                'label' => __( 'Title Hover Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
	                '{{WRAPPER}} {{CURRENT_ITEM}}' => '--e-title-hover-color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'idesc_color',
            [
                'label' => __( 'Description Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
	                '{{WRAPPER}} {{CURRENT_ITEM}}' => '--e-desc-color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );


		$repeater->add_control(
            'idesc_color_hover',
            [
                'label' => __( 'Description Hover Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
	                '{{WRAPPER}} {{CURRENT_ITEM}}' => '--e-desc-hover-color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'isubdesc_color',
            [
                'label' => __( 'SubDescription Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
	                '{{WRAPPER}} {{CURRENT_ITEM}}' => '--e-subdesc-color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

		$repeater->add_control(
            'isubdesc_color_hover',
            [
                'label' => __( 'SubDescription Hover Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
	                '{{WRAPPER}} {{CURRENT_ITEM}}' => '--e-subdesc-hover-color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'ibtn_color',
            [
                'label' => __( 'Button Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-bannerlist__btn:not(:hover)' => 'color: {{VALUE}}; border-color: {{VALUE}}; background-color: transparent',
                ],
                'condition' => [
                    'custom_style' => 'yes',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'subtitle_fontsize',
                'label' => __( 'Subtitle font size', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['subtitle'],
            )
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_fontsize',
                'label' => __( 'Title font size', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['title'],
            )
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'desc_fontsize',
                'label' => __( 'Description font size', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['desc'],
            )
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'subdesc_fontsize',
                'label' => __( 'SubDescription font size', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['subdesc'],
            )
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'image_list',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(),
                'title_field' => '{{{ title }}}',
            )
        );

        $this->end_controls_section();
    }

    private function _register_section_general_styles( $css_scheme ){
        $this->start_controls_section(
            'section_style_items',
            array(
                'label' => esc_html__( 'Item', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
            )
        );
        $this->add_responsive_control(
            'item_gap',
            array(
                'label' => esc_html__( 'Item Gap', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => '--lakit-bannerlist-col-gap: {{SIZE}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'item_row_gap',
            array(
                'label' => esc_html__( 'Row Spacing', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'selectors' => array(
                    '{{WRAPPER}}' => '--lakit-bannerlist-row-gap: {{SIZE}}{{UNIT}};',
                )
            )
        );

	    $this->_start_controls_tabs( 'item__tabs' );
		$this->_start_controls_tab('item__tab_normal', [ 'label' => __( 'Normal', 'lastudio-kit' ) ]);
	    $this->add_group_control(
		    Group_Control_Background::get_type(),
		    array(
			    'name'     => 'item_bg',
			    'selector' => '{{WRAPPER}} ' . $css_scheme['inner'],
		    )
	    );
	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    array(
			    'name'        => 'item_border',
			    'label'       => esc_html__( 'Border', 'lastudio-kit' ),
			    'placeholder' => '1px',
			    'default'     => '1px',
			    'selector'    => '{{WRAPPER}} ' . $css_scheme['inner'],
		    )
	    );

	    $this->add_responsive_control(
		    'item_border_radius',
		    array(
			    'label'      => __( 'Border Radius', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->add_responsive_control(
		    'item_padding',
		    array(
			    'label'      => __( 'Padding', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    array(
			    'name' => 'item_shadow',
			    'exclude' => array(
				    'box_shadow_position',
			    ),
			    'selector' => '{{WRAPPER}} ' . $css_scheme['inner'],
		    )
	    );
		$this->_end_controls_tab();
		$this->_start_controls_tab('item__tab_hover', [ 'label' => __( 'Hover', 'lastudio-kit' ) ]);
	    $this->add_group_control(
		    Group_Control_Background::get_type(),
		    array(
			    'name'     => 'item_bg_hover',
			    'selector' => '{{WRAPPER}} ' . $css_scheme['inner'] . ':hover',
		    )
	    );
	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    array(
			    'name'        => 'item_border_hover',
			    'label'       => esc_html__( 'Border', 'lastudio-kit' ),
			    'placeholder' => '1px',
			    'default'     => '1px',
			    'selector'    => '{{WRAPPER}} ' . $css_scheme['inner'] . ':hover',
		    )
	    );

	    $this->add_responsive_control(
		    'item_border_radius_hover',
		    array(
			    'label'      => __( 'Border Radius', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['inner'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->add_responsive_control(
		    'item_padding_hover',
		    array(
			    'label'      => __( 'Padding', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['inner'] . ':hover'=> 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    array(
			    'name' => 'item_shadow_hover',
			    'exclude' => array(
				    'box_shadow_position',
			    ),
			    'selector' => '{{WRAPPER}} ' . $css_scheme['inner']. ':hover',
		    )
	    );
		$this->_end_controls_tab();
		$this->_end_controls_tabs();


        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_image',
            array(
                'label' => esc_html__( 'Image', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'image_width',
            array(
                'label' => esc_html__( 'Image Width', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 1000,
                    ),
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ),
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'size' => 100,
                    'unit' => '%'
                ],
                'selectors' => array(
                    '{{WRAPPER}} .lakit-bannerlist' => '--e_bnl-iwidth: {{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_control(
            'enable_custom_image_height',
            array(
                'label'        => esc_html__( 'Enable Custom Image Height', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'true',
                'default'      => '',
                'prefix_class' => 'enable-c-height-',
            )
        );

        $this->add_responsive_control(
            'image_height',
            array(
                'label' => esc_html__( 'Image Height', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 1000,
                    ),
                    '%' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    'vh' => array(
                        'min' => 0,
                        'max' => 100,
                    )
                ),
                'size_units' => ['px', '%', 'vh', 'custom'],
                'default' => [
                    'size' => 300,
                    'unit' => 'px'
                ],
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['image'] . ':before' => 'padding-bottom: {{SIZE}}{{UNIT}};'
                ),
                'condition' => [
                    'enable_custom_image_height!' => ''
                ]
            )
        );

        $this->_add_control(
            'image_object_fit',
            [
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
                'selectors'  => array(
                    '{{WRAPPER}} '. $css_scheme['image_instance'] => 'object-fit: {{value}}',
                ),
//                'condition' => [
//                    'enable_custom_image_height!' => ''
//                ]
            ]
        );

        $this->start_controls_tabs( 'tabs_image_style' );

        $this->start_controls_tab(
            'tabs_image_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit'),
            )
        );
        $this->add_control(
            'image_bgcolor',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-bannerlist__image'=> 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            'image_opacity',
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
                    '{{WRAPPER}} .lakit-bannerlist__image img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_scale',
            [
                'label' => __( 'Image Scale', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-bannerlist-image-scale: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filter',
                'selector' => '{{WRAPPER}} .lakit-bannerlist__image img',
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            array(
                'label'      => esc_html__( 'Image Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em'),
                'selectors'  => array(
                    '{{WRAPPER}}' => '--lakit-bannerlist-image-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em'),
                'selectors'  => array(
                    '{{WRAPPER}}' => '--lakit-bannerlist-image-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'image_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .lakit-bannerlist__image'
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'image_shadow',
                'selector'    => '{{WRAPPER}} .lakit-bannerlist__image'
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_image_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit'),
            )
        );
        $this->add_control(
            'image_bgcolor_hover',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-bannerlist__link:hover .lakit-bannerlist__image'=> 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            'image_opacity_hover',
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
                    '{{WRAPPER}} .lakit-bannerlist__link:hover .lakit-bannerlist__image img' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_scale_hover',
            [
                'label' => __( 'Image Scale', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-bannerlist-image-scale-hover: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filter_hover',
                'selector' => '{{WRAPPER}} .lakit-bannerlist__link:hover .lakit-bannerlist__image img',
            ]
        );

        $this->add_responsive_control(
            'image_padding_hover',
            array(
                'label'      => esc_html__( 'Image Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em'),
                'selectors'  => array(
                    '{{WRAPPER}}' => '--lakit-bannerlist-image-hover-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_radius_hover',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em'),
                'selectors'  => array(
                    '{{WRAPPER}}' => '--lakit-bannerlist-image-hover-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'image_border_hover',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .lakit-bannerlist__link:hover .lakit-bannerlist__image'
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'image_shadow_hover',
                'selector'    => '{{WRAPPER}} .lakit-bannerlist__link:hover .lakit-bannerlist__image'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_image_overlay',
            array(
                'label' => esc_html__( 'Image Overlay', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
            )
        );

        $this->start_controls_tabs( 'tabs_overlay_style' );

        $this->start_controls_tab(
            'tabs_overlay_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit'),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'overlay_background',
                'selector' => '{{WRAPPER}} .lakit-bannerlist__image:after',
            )
        );

        $this->add_control(
            'overlay_opacity',
            array(
                'label'    => esc_html__( 'Opacity', 'lastudio-kit' ),
                'type'     => Controls_Manager::NUMBER,
                'default'  => 0.6,
                'min'      => 0,
                'max'      => 1,
                'step'     => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-bannerlist__image:after' => 'opacity: {{VALUE}};'
                )
            )
        );

        $this->add_control(
            'overlay_zindex',
            array(
                'label'    => esc_html__( 'Z-Index', 'lastudio-kit' ),
                'type'     => Controls_Manager::NUMBER,
                'min'      => -1,
                'max'      => 10,
                'step'     => 1,
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-bannerlist__image:after' => 'z-index: {{VALUE}};'
                )
            )
        );

        $this->add_responsive_control(
            'overlay_position',
            array(
                'label'      => __( 'Position', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-bannerlist__image:after' => 'top:{{TOP}}{{UNIT}};right:{{RIGHT}}{{UNIT}};bottom:{{BOTTOM}}{{UNIT}};left:{{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'overlay_blend_mode',
            [
                'label' => esc_html__( 'Blend Mode', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => lastudio_kit_helper()->get_blend_mode_options(),
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-banner-overlay-mix-blend-mode: {{VALUE}}',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'overlay_background_image[url]',
                            'operator' => '!==',
                            'value' => '',
                        ],
                        [
                            'name' => 'overlay_background_color',
                            'operator' => '!==',
                            'value' => '',
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'overlay_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em'),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-bannerlist__image:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'overlay_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .lakit-bannerlist__image:after'
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'overlay_shadow',
                'selector'    => '{{WRAPPER}} .lakit-bannerlist__image:after'
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_overlay_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit'),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'overlay_background_hover',
                'selector' => '{{WRAPPER}} .lakit-bannerlist__inner:hover .lakit-bannerlist__image:after'
            )
        );

        $this->add_control(
            'overlay_opacity_hover',
            array(
                'label'    => esc_html__( 'Opacity', 'lastudio-kit' ),
                'type'     => Controls_Manager::NUMBER,
                'default'  => 0.6,
                'min'      => 0,
                'max'      => 1,
                'step'     => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-bannerlist__inner:hover .lakit-bannerlist__image:after' => 'opacity: {{VALUE}};'
                )
            )
        );
        $this->add_responsive_control(
            'overlay_position_hover',
            array(
                'label'      => __( 'Position', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-bannerlist__inner:hover .lakit-bannerlist__image:after' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'overlay_radius_hover',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em'),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-bannerlist__inner:hover .lakit-bannerlist__image:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'overlay_border_hover',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .lakit-bannerlist__inner:hover .lakit-bannerlist__image:after'
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'overlay_shadow_hover',
                'selector'    => '{{WRAPPER}} .lakit-bannerlist__inner:hover .lakit-bannerlist__image:after'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_content',
            array(
                'label' => esc_html__( 'Content', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
            )
        );

        $this->_add_control(
            'content_visible_on_hover',
            [
                'label'        => esc_html__( 'Show on hover', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'return_value' => 'yes',
                'prefix_class' => 'content-visible-hover-',
                'condition' => [
                    'layout_type' => 'overlay'
                ]
            ]
        );

        $this->add_responsive_control(
            'content_width',
            [
                'label' => __( 'Content Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-bannerlist-content-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout_type' => 'overlay'
                ]
            ]
        );

        $this->_add_control(
            'content_horizontal',
            [
                'label' => esc_html__( 'Horizontal Orientation', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => is_rtl() ? 'right' : 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
                'condition' => [
                    'layout_type' => 'overlay'
                ]
            ]
        );

        $this->add_responsive_control(
            'content_offset_x',
            [
                'label' => esc_html__( 'Offset', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['content_inner'] => '{{content_horizontal.VALUE}}: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout_type' => 'overlay'
                ]
            ]
        );

        $this->_add_control(
            'content_vertical',
            [
                'label' => esc_html__( 'Vertical Orientation', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => esc_html__( 'Top', 'lastudio-kit' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'top',
                'toggle' => false,
                'condition' => [
                    'layout_type' => 'overlay'
                ]
            ]
        );

        $this->add_responsive_control(
            'content_offset_y',
            [
                'label' => esc_html__( 'Offset', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['content_inner'] => '{{content_vertical.VALUE}}: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout_type' => 'overlay'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'content_bg',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content_inner'],
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'content_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit'),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['content_inner'],
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'content_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content_inner'],
            )
        );

        $this->add_responsive_control(
            'content_radius',
            [
                'label' => __( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-bannerlist-content-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Content Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-bannerlist-content-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => __( 'Content Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} '=> '--lakit-bannerlist-content-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

	    $this->add_control(
		    'content__heading',
		    [
			    'label' => __( 'Content Layout', 'lastudio-kit' ),
			    'type' => Controls_Manager::HEADING,
			    'separator' => 'before'
		    ]
	    );
	    $this->add_responsive_control(
		    'content_l_direction',
		    [
			    'label' => esc_html_x( 'Row Direction', 'Flex Container Control', 'lastudio-kit'),
			    'type' => Controls_Manager::CHOOSE,
			    'options' => [
				    'row' => [
					    'title' => esc_html_x( 'Row', 'Flex Container Control', 'lastudio-kit' ),
					    'icon' => 'eicon-arrow-right',
				    ],
				    'column' => [
					    'title' => esc_html_x( 'Column', 'Flex Container Control', 'lastudio-kit' ),
					    'icon' => 'eicon-arrow-down',
				    ],
			    ],
			    'default' => '',
			    'selectors' => [
				    '{{WRAPPER}} .lakit-bannerlist__content-inner' => 'flex-direction: {{VALUE}}; display: flex',
			    ],
		    ]
	    );
	    $this->add_responsive_control(
		    'content_l_justify',
		    [
			    'label' => esc_html_x( 'Justify Content', 'Flex Container Control', 'lastudio-kit' ),
			    'type' => Controls_Manager::CHOOSE,
			    'label_block' => true,
			    'default' => '',
			    'options' => [
				    'flex-start' => [
					    'title' => esc_html_x( 'Flex Start', 'Flex Container Control', 'lastudio-kit' ),
					    'icon' => 'eicon-flex eicon-justify-start-h',
				    ],
				    'center' => [
					    'title' => esc_html_x( 'Center', 'Flex Container Control', 'lastudio-kit' ),
					    'icon' => 'eicon-flex eicon-justify-center-h',
				    ],
				    'flex-end' => [
					    'title' => esc_html_x( 'Flex End', 'Flex Container Control', 'lastudio-kit' ),
					    'icon' => 'eicon-flex eicon-justify-end-h',
				    ],
				    'space-between' => [
					    'title' => esc_html_x( 'Space Between', 'Flex Container Control', 'lastudio-kit' ),
					    'icon' => 'eicon-flex eicon-justify-space-between-h',
				    ],
				    'space-around' => [
					    'title' => esc_html_x( 'Space Around', 'Flex Container Control', 'lastudio-kit' ),
					    'icon' => 'eicon-flex eicon-justify-space-around-h',
				    ],
				    'space-evenly' => [
					    'title' => esc_html_x( 'Space Evenly', 'Flex Container Control', 'lastudio-kit' ),
					    'icon' => 'eicon-flex eicon-justify-space-evenly-h',
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .lakit-bannerlist__content-inner' => 'justify-content: {{VALUE}};',
			    ],
		    ]
	    );
		$this->add_responsive_control(
		    'content_l_align',
		    [
			    'label' => esc_html_x( 'Align Items', 'Flex Container Control', 'lastudio-kit' ),
			    'type' => Controls_Manager::CHOOSE,
			    'default' => '',
			    'options' => [
				    'flex-start' => [
					    'title' => esc_html_x( 'Flex Start', 'Flex Container Control', 'lastudio-kit' ),
					    'icon' => 'eicon-flex eicon-align-start-v',
				    ],
				    'center' => [
					    'title' => esc_html_x( 'Center', 'Flex Container Control', 'lastudio-kit' ),
					    'icon' => 'eicon-flex eicon-align-center-v',
				    ],
				    'flex-end' => [
					    'title' => esc_html_x( 'Flex End', 'Flex Container Control', 'lastudio-kit' ),
					    'icon' => 'eicon-flex eicon-align-end-v',
				    ],
				    'stretch' => [
					    'title' => esc_html_x( 'Stretch', 'Flex Container Control', 'lastudio-kit' ),
					    'icon' => 'eicon-flex eicon-align-stretch-v',
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .lakit-bannerlist__content-inner' => 'align-items: {{VALUE}};',
			    ],
		    ]
	    );
	    $this->add_responsive_control(
		    'content_l_gap',
		    [
			    'label' => esc_html__( 'Items gap', 'lastudio-kit' ),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => [ 'px', '%' ],
			    'selectors' => [
				    '{{WRAPPER}} .lakit-bannerlist__content-inner' => 'gap: {{SIZE}}{{UNIT}};'
			    ]
		    ]
	    );
	    $this->add_responsive_control(
		    'content_l_wrap',
		    [
			    'label' => esc_html__( 'Wrap', 'lastudio-kit' ),
			    'type' => Controls_Manager::CHOOSE,
			    'options' => [
				    'nowrap' => [
					    'title' => esc_html_x( 'No Wrap', 'Flex Container Control', 'lastudio-kit' ),
					    'icon' => 'eicon-flex eicon-nowrap',
				    ],
				    'wrap' => [
					    'title' => esc_html_x( 'Wrap', 'Flex Container Control', 'lastudio-kit' ),
					    'icon' => 'eicon-flex eicon-wrap',
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .lakit-bannerlist__content-inner' => 'flex-wrap: {{VALUE}};'
			    ]
		    ]
	    );

        $this->add_responsive_control(
            'content_text_align',
            [
                'label' => __( 'Text Align', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors_dictionary' => [
                    'left' => '--e_bnl-text-align: left;--e_bnl-align: flex-start;',
                    'center' => '--e_bnl-text-align: center;--e_bnl-align: center;',
                    'right' => '--e_bnl-text-align: right;--e_bnl-align: flex-end;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-bannerlist' => '{{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $sections = [
            'title' => __('Title', 'lastudio-kit'),
            'subtitle' => __('Sub Title', 'lastudio-kit'),
            'desc' => __('Description', 'lastudio-kit'),
            'subdesc' => __('Sub Description', 'lastudio-kit'),
        ];
        foreach ($sections as $section_key => $section_label){
            $this->start_controls_section(
                'section_style_' . $section_key,
                array(
                    'label' => $section_label,
                    'tab'        => Controls_Manager::TAB_STYLE,
                )
            );

	        $this->add_responsive_control(
		        $section_key .'_width',
		        [
			        'label' => esc_html__( 'Custom Width', 'lastudio-kit' ),
			        'type' => Controls_Manager::SLIDER,
			        'size_units' => [ 'px', '%', 'custom' ],
			        'selectors' => [
				        '{{WRAPPER}} .lakit-bannerlist__' . $section_key => 'width: {{SIZE}}{{UNIT}};'
			        ]
		        ]
	        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'     => $section_key . '_typography',
                    'selector' => '{{WRAPPER}} ' . $css_scheme[$section_key],
                )
            );
            $this->add_control(
                $section_key .'_color',
                array(
                    'label' => esc_html__( 'Color', 'lastudio-kit' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => array(
						'{{WRAPPER}} '   => sprintf('--e-%1$s-color: {{VALUE}}', $section_key),
                    ),
                )
            );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => $section_key .'_bgcolor',
                    'selector' => '{{WRAPPER}} ' . $css_scheme[$section_key],
                    'fields_options' => [
                        'background' => [
                            'label' => 'Background'
                        ],
                    ],
                ]
            );
//			$this->add_control(
//                $section_key .'_bgcolor',
//                array(
//                    'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
//                    'type' => Controls_Manager::COLOR,
//                    'selectors' => array(
//						'{{WRAPPER}} '   => sprintf('--e-%1$s-bgcolor: {{VALUE}}', $section_key),
//                    ),
//                )
//            );

			$this->add_control(
                $section_key .'_hover_color',
                array(
                    'label' => esc_html__( 'Hover Color', 'lastudio-kit' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => array(
	                    '{{WRAPPER}}'   => sprintf('--e-%1$s-hover-color: {{VALUE}}', $section_key),
                    ),
                )
            );

//			$this->add_control(
//                $section_key .'_hover_bgcolor',
//                array(
//                    'label' => esc_html__( 'Hover Background Color', 'lastudio-kit' ),
//                    'type' => Controls_Manager::COLOR,
//                    'selectors' => array(
//	                    '{{WRAPPER}}'   => sprintf('--e-%1$s-hover-bgcolor: {{VALUE}}', $section_key),
//                    ),
//                )
//            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => $section_key .'_hover_bgcolor',
                    'selector' => '{{WRAPPER}} .lakit-bannerlist__inner:hover ' . $css_scheme[$section_key],
                    'fields_options' => [
                        'background' => [
                            'label' => 'Hover Background'
                        ],
                    ],
                ]
            );

	        $this->add_group_control(
		        Group_Control_Text_Stroke::get_type(),
		        [
			        'name' => $section_key . '_text_stroke',
			        'selector' => '{{WRAPPER}} ' . $css_scheme[$section_key],
		        ]
	        );

	        $this->add_group_control(
		        Group_Control_Text_Shadow::get_type(),
		        [
			        'name' => $section_key . '_text_shadow',
			        'selector' => '{{WRAPPER}} ' . $css_scheme[$section_key],
		        ]
	        );
            $this->add_responsive_control(
                $section_key .'_padding',
                array(
                    'label'      => __( 'Padding', 'lastudio-kit' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%', 'em', 'custom' ),
                    'selectors'  => array(
                        '{{WRAPPER}} ' . $css_scheme[$section_key] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );
            $this->add_responsive_control(
                $section_key .'_margin',
                array(
                    'label'      => __( 'Margin', 'lastudio-kit' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%', 'em', 'custom' ),
                    'selectors'  => array(
                        '{{WRAPPER}} ' . $css_scheme[$section_key] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                array(
                    'name'        => $section_key . '_border',
                    'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                    'placeholder' => '1px',
                    'default'     => '1px',
                    'selector'    => '{{WRAPPER}} ' . $css_scheme[$section_key],
                )
            );
            $this->end_controls_section();
        }

        /**
         * Button Style Section
         */
        $this->start_controls_section(
            'section_style_button',
            array(
                'label'      => esc_html__( 'Button', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );
        $this->_add_control(
            'btn_on_hover',
            [
                'label'        => esc_html__( 'Show on hover', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'return_value' => 'yes',
                'prefix_class' => 'btn-visible-hover-',
                'condition' => [
                    'layout_type' => 'flat'
                ]
            ]
        );

	    $this->_add_control(
		    'btn_fullwidth',
		    array(
			    'label'        => esc_html__( 'Enable Fullwidth', 'lastudio-kit' ),
			    'type'         => Controls_Manager::SWITCHER,
			    'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
			    'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
			    'return_value' => 'true',
			    'default'      => '',
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['button'] => 'width: 100%',
			    ),
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
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'flex-direction: {{VALUE}}',
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
                'label'      => esc_html__( 'Icon Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button_icon'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            ),
            50
        );


        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'button_bg',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            )
        );

        $this->add_control(
            'button_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'button_i_color',
            array(
                'label'     => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button_icon'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['button'],
            )
        );

        $this->add_responsive_control(
            'button_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'button_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'button_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'button_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['button'],
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'button_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'button_bg_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            )
        );

        $this->add_control(
            'button_color_hover',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            'button_i_color_hover',
            array(
                'label'     => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover ' . $css_scheme['button_icon'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'button_typography_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            )
        );

        $this->add_responsive_control(
            'button_padding_hover',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'button_margin_hover',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'button_border_radius_hover',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'button_border_hover',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover'
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'button_box_shadow_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }
}