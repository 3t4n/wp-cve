<?php

/**
 * Class: LaStudioKit_Images_Layout
 * Name: Images Layout
 * Slug: lakit-images-layout
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

use Elementor\Modules\DynamicTags\Module as TagsModule;

/**
 * Images_Layout Widget
 */
class LaStudioKit_Images_Layout extends LaStudioKit_Base {
    
    /**
     * [$item_counter description]
     * @var integer
     */
    public $item_counter = 0;

    protected function enqueue_addon_resources(){
	    $this->add_script_depends( 'jquery-isotope' );
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_script_depends( 'lastudio-kit-base' );
		    if ( ! lastudio_kit()->is_optimized_css_mode() ) {
			    wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/images-layout.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
			    $this->add_style_depends( $this->get_name() );
		    }
	    }
    }

	public function get_widget_css_config($widget_name){
		$file_url = lastudio_kit()->plugin_url(  'assets/css/addons/images-layout.min.css' );
		$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/images-layout.min.css' );
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
        return 'lakit-images-layout';
    }

    protected function get_widget_title() {
        return esc_html__( 'Images Layout', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

	public function get_keywords() {
		return [ 'image', 'gallery', 'carousel', 'slide' ];
	}


    protected function register_controls() {

        $css_scheme = apply_filters(
            'lastudio-kit/images-layout/css-schema',
            array(
                'instance'          => '.lakit-images-layout',
                'list_container'    => '.lakit-images-layout__list',
                'item'              => '.lakit-images-layout__item',
                'inner'             => '.lakit-images-layout__inner',
                'image_wrap'        => '.lakit-images-layout__image',
                'image_instance'    => '.lakit-images-layout__image-instance',
                'content_wrap'      => '.lakit-images-layout__content',
                'icon'              => '.lakit-images-layout__icon',
                'title'             => '.lakit-images-layout__title',
                'desc'              => '.lakit-images-layout__desc',
                'button'            => '.lakit-images-layout__button',
                'button_icon'       => '.lakit-images-layout__button .btn-icon',
            )
        );

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
                'default' => 'grid',
                'options' => array(
                    'grid'    => esc_html__( 'Grid', 'lastudio-kit' ),
                    'list'    => esc_html__( 'List', 'lastudio-kit' ),
                ),
            )
        );

        $this->add_control(
            'preset',
            array(
                'label'   => esc_html__( 'Preset', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'prefix_class' => 'imagelayout-preset-',
                'options' => apply_filters('lastudio-kit/images-layout/preset', [
                    'default' => esc_html__( 'Default', 'lastudio-kit' ),
                    'type-1' => esc_html__( 'Type 1', 'lastudio-kit' ),
                    'type-2' => esc_html__( 'Type 2', 'lastudio-kit' ),
                    'type-3' => esc_html__( 'Type 3', 'lastudio-kit' ),
                ])
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'   => esc_html__( 'Columns', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 3,
                'options' => lastudio_kit_helper()->get_select_range( 6 ),
                'condition' => array(
                    'layout_type' => ['grid']
                ),
            )
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

        $this->start_controls_section(
            'section_items_data',
            array(
                'label' => esc_html__( 'Items', 'lastudio-kit' ),
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
            'item_icon',
            array(
                'label'       => esc_html__( 'Icon', 'lastudio-kit' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false
            )
        );

        $repeater->add_control(
            'item_title',
            array(
                'label'   => esc_html__( 'Title', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_desc',
            array(
                'label'   => esc_html__( 'Description', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXTAREA,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_link_type',
            array(
                'label'   => esc_html__( 'Link type', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'lightbox',
                'options' => array(
                    'lightbox' => esc_html__( 'Lightbox', 'lastudio-kit' ),
                    'external' => esc_html__( 'External', 'lastudio-kit' ),
                    'none'     => esc_html__( 'None', 'lastudio-kit' )
                ),
            )
        );
	    $repeater->add_control(
		    'item_link_text',
		    array(
			    'label'   => esc_html__( 'Button Title', 'lastudio-kit' ),
			    'type'    => Controls_Manager::TEXT,
			    'condition' => array(
				    'item_link_type' => 'external',
			    ),
			    'dynamic' => array( 'active' => true ),
		    )
	    );
        $repeater->add_control(
            'item_url',
            array(
                'label'   => esc_html__( 'External Link', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '#',
                'condition' => array(
                    'item_link_type' => 'external',
                ),
                'dynamic' => array(
                    'active' => true,
                    'categories' => array(
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY,
                    ),
                ),
            )
        );

        $repeater->add_control(
            'item_target',
            array(
                'label'        => esc_html__( 'Open external link in new window', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => '_blank',
                'default'      => '',
                'condition'    => array(
                    'item_link_type' => 'external',
                ),
            )
        );

        $repeater->add_control(
            'item_css_class',
            array(
                'label'   => esc_html__( 'Item CSS class', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'image_list',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #1', 'lastudio-kit' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio-kit' ),
                        'item_url'         => '#',
                        'item_target'      => '',
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #2', 'lastudio-kit' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio-kit' ),
                        'item_url'         => '#',
                        'item_target'      => '',
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #3', 'lastudio-kit' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio-kit' ),
                        'item_url'         => '#',
                        'item_target'      => '',
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #4', 'lastudio-kit' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio-kit' ),
                        'item_url'         => '#',
                        'item_target'      => '',
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #5', 'lastudio-kit' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio-kit' ),
                        'item_url'         => '#',
                        'item_target'      => '',
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #6', 'lastudio-kit' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio-kit' ),
                        'item_url'         => '#',
                        'item_target'      => '',
                    ),
                ),
                'title_field' => '{{{ item_title }}}',
            )
        );

        $this->add_control(
            'title_html_tag',
            array(
                'label'   => esc_html__( 'Title HTML Tag', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'h1'   => esc_html__( 'H1', 'lastudio-kit' ),
                    'h2'   => esc_html__( 'H2', 'lastudio-kit' ),
                    'h3'   => esc_html__( 'H3', 'lastudio-kit' ),
                    'h4'   => esc_html__( 'H4', 'lastudio-kit' ),
                    'h5'   => esc_html__( 'H5', 'lastudio-kit' ),
                    'h6'   => esc_html__( 'H6', 'lastudio-kit' ),
                    'div'  => esc_html__( 'div', 'lastudio-kit' ),
                    'span' => esc_html__( 'span', 'lastudio-kit' ),
                    'p'    => esc_html__( 'p', 'lastudio-kit' ),
                ),
                'default' => 'h5',
                'separator' => 'before',
            )
        );

        $this->end_controls_section();

        $this->register_masonry_setting_section( [ 'enable_masonry' => 'yes' ], false );

        $this->register_carousel_section( [ 'enable_masonry!' => 'yes' ], 'columns');

        /**
         * General Style Section
         */
        $this->start_controls_section(
            'section_images_layout_general_style',
            array(
                'label'      => esc_html__( 'General', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'item_margin',
            array(
                'label' => esc_html__( 'Item Spacing', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => '--lakit-carousel-item-left-space: {{SIZE}}{{UNIT}};--lakit-carousel-item-right-space: {{SIZE}}{{UNIT}};--lakit-gcol-left-space: {{SIZE}}{{UNIT}};--lakit-gcol-right-space: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['item']          => 'padding: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['list_container'] . ':not(.swiper-wrapper)' => 'margin-left: -{{SIZE}}{{UNIT}};margin-right: -{{SIZE}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'item_row_gap',
            array(
                'label' => esc_html__( 'Row Spacing', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item']          => 'margin-bottom: {{SIZE}}{{UNIT}};',
                )
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

        $this->end_controls_section();

        /**
         * Icon Style Section
         */
        $this->start_controls_section(
            'section_images_layout_icon_style',
            array(
                'label'      => esc_html__( 'Icon', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'icon_color',
            array(
                'label' => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lakit-images-layout-icon-inner' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'icon_bg_color',
            array(
                'label' => esc_html__( 'Icon Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lakit-images-layout-icon-inner' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_font_size',
            array(
                'label'      => esc_html__( 'Icon Font Size', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em' ,
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lakit-images-layout-icon-inner' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_size',
            array(
                'label'      => esc_html__( 'Icon Box Size', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lakit-images-layout-icon-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'icon_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lakit-images-layout-icon-inner',
            )
        );

        $this->add_control(
            'icon_box_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lakit-images-layout-icon-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_box_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lakit-images-layout-icon-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'icon_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lakit-images-layout-icon-inner',
            )
        );


        $this->add_control(
            'icon_horizontal_alignment',
            array(
                'label'   => esc_html__( 'Horizontal Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => esc_html__( 'Left', 'lastudio-kit' ),
                    'center'        => esc_html__( 'Center', 'lastudio-kit' ),
                    'flex-end'      => esc_html__( 'Right', 'lastudio-kit' ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} '. $css_scheme['icon'] => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'icon_vertical_alignment',
            array(
                'label'   => esc_html__( 'Vertical Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => esc_html__( 'Top', 'lastudio-kit' ),
                    'center'        => esc_html__( 'Center', 'lastudio-kit' ),
                    'flex-end'      => esc_html__( 'Bottom', 'lastudio-kit' ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} '. $css_scheme['icon'] => 'align-items: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Title Style Section
         */
        $this->start_controls_section(
            'section_images_layout_title_style',
            array(
                'label'      => esc_html__( 'Title', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            'title_bg',
            array(
                'label'     => esc_html__( 'Background', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
            )
        );

        $this->add_responsive_control(
            'title_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'title_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'title_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Description Style Section
         */
        $this->start_controls_section(
            'section_images_layout_desc_style',
            array(
                'label'      => esc_html__( 'Description', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'desc_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'desc_bg',
            array(
                'label'     => esc_html__( 'Background', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'desc_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['desc'],
            )
        );

        $this->add_responsive_control(
            'desc_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'desc_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'desc_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
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
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();



	    /**
	     * Button Style Section
	     */
	    $this->start_controls_section(
		    'section_button_style',
		    array(
			    'label'      => esc_html__( 'Button', 'lastudio-kit' ),
			    'tab'        => Controls_Manager::TAB_STYLE,
			    'show_label' => false,
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
				    '{{WRAPPER}} ' . $css_scheme['button_icon'] => 'flex-direction: {{VALUE}}',
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
			    'fields_options' => array(
				    'background' => array(
					    'default' => 'classic',
				    ),
			    ),
			    'exclude' => array(
				    'image',
				    'position',
				    'attachment',
				    'attachment_alert',
				    'repeat',
				    'size',
			    ),
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
			    'fields_options' => array(
				    'background' => array(
					    'default' => 'classic',
				    ),
			    ),
			    'exclude' => array(
				    'image',
				    'position',
				    'attachment',
				    'attachment_alert',
				    'repeat',
				    'size',
			    ),
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

        $this->start_controls_section(
            '_section_il_image',
            array(
                'label'      => esc_html__( 'Image', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
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
                'condition' => array(
                    'layout_type!' => 'list'
                ),
            )
        );

        $this->add_responsive_control(
            'item_height',
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
                'size_units' => ['px', '%', 'vh'],
                'default' => [
                    'size' => 300,
                    'unit' => 'px'
                ],
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['image_wrap'] => 'padding-bottom: {{SIZE}}{{UNIT}};'
                ),
                'condition' => [
                    'layout_type!' => 'list',
                    'enable_custom_image_height!' => ''
                ]
            )
        );
        $this->start_controls_tabs( 'tabs_image_style' );

        $this->start_controls_tab(
            'tabs_image_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit'),
            )
        );
        $this->add_control(
            'image_opacity',
            array(
                'label'    => esc_html__( 'Opacity', 'lastudio-kit' ),
                'type'     => Controls_Manager::NUMBER,
                'min'      => 0,
                'max'      => 1,
                'step'     => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-images-layout__image-instance' => 'opacity: {{VALUE}};',
                )
            )
        );
        $this->add_responsive_control(
            'image_scale',
            [
                'label' => __( 'Image Scale', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .lakit-images-layout__image' => 'transform: scale({{SIZE}});',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filter',
                'selector' => '{{WRAPPER}} .lakit-images-layout__image img',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tabs_image_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit'),
            )
        );
        $this->add_control(
            'image_opacity_hover',
            array(
                'label'    => esc_html__( 'Opacity', 'lastudio-kit' ),
                'type'     => Controls_Manager::NUMBER,
                'min'      => 0,
                'max'      => 1,
                'step'     => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-images-layout__inner:hover .lakit-images-layout__image-instance' => 'opacity: {{VALUE}};',
                )
            )
        );
        $this->add_responsive_control(
            'image_scale_hover',
            [
                'label' => __( 'Image Scale', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .lakit-images-layout__inner:hover .lakit-images-layout__image' => 'transform: scale({{SIZE}});',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filter_hover',
                'selector' => '{{WRAPPER}} .lakit-images-layout__inner:hover img',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
	    $this->end_controls_section();


        /**
         * Overlay Style Section
         */
        $this->start_controls_section(
            'section_images_layout_overlay_style',
            array(
                'label'      => esc_html__( 'Overlay', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
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
                'selector' => '{{WRAPPER}} .lakit-images-layout__content:before,{{WRAPPER}} .lakit-images-layout__image:after',
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
                    '{{WRAPPER}} .lakit-images-layout__content:before' => 'opacity: {{VALUE}};',
                    '{{WRAPPER}} .lakit-images-layout__image:after' => 'opacity: {{VALUE}};'
                )
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
                'name'     => 'overlay_h_background',
                'selector' => '{{WRAPPER}} .lakit-images-layout__inner:hover .lakit-images-layout__content:before,{{WRAPPER}} .lakit-images-layout__inner:hover .lakit-images-layout__image:after'
            )
        );

        $this->add_control(
            'overlay_h_opacity',
            array(
                'label'    => esc_html__( 'Opacity', 'lastudio-kit' ),
                'type'     => Controls_Manager::NUMBER,
                'default'  => 0.6,
                'min'      => 0,
                'max'      => 1,
                'step'     => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-images-layout__inner:hover .lakit-images-layout__content:before' => 'opacity: {{VALUE}};',
                    '{{WRAPPER}} .lakit-images-layout__inner:hover .lakit-images-layout__image:after' => 'opacity: {{VALUE}};'
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'overlay_paddings',
            array(
                'label'      => __( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['content_wrap'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Order Style Section
         */
        $this->start_controls_section(
            'section_order_style',
            array(
                'label'      => esc_html__( 'Content Order and Alignment', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'item_title_order',
            array(
                'label'   => esc_html__( 'Title Order', 'lastudio-kit' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 1,
                'min'     => 1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['title'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'item_content_order',
            array(
                'label'   => esc_html__( 'Content Order', 'lastudio-kit' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 2,
                'min'     => 1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['desc'] => 'order: {{VALUE}};',
                ),
            )
        );
        $this->add_control(
            'item_button_order',
            array(
                'label'   => esc_html__( 'Button Order', 'lastudio-kit' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 3,
                'min'     => 1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['button'] => 'order: {{VALUE}};',
                ),
            )
        );

	    $this->add_responsive_control(
		    'item_content_halignment',
		    array(
			    'label'   => esc_html__( 'Content Alignment', 'lastudio-kit' ),
			    'type'    => Controls_Manager::CHOOSE,
			    'options' => array(
				    'flex-start'    => array(
					    'title' => esc_html__( 'Left', 'lastudio-kit' ),
					    'icon'  => 'eicon-h-align-left',
				    ),
				    'center' => array(
					    'title' => esc_html__( 'Center', 'lastudio-kit' ),
					    'icon'  => 'eicon-h-align-center',
				    ),
				    'flex-end' => array(
					    'title' => esc_html__( 'Right', 'lastudio-kit' ),
					    'icon'  => 'eicon-h-align-right',
				    ),
			    ),
			    'selectors'  => array(
				    '{{WRAPPER}} '. $css_scheme['content_wrap']  => 'align-items: {{VALUE}};',
			    ),
		    )
	    );

	    $this->add_responsive_control(
		    'item_content_alignment',
		    array(
			    'label'   => esc_html__( 'Content Vertical Alignment', 'lastudio-kit' ),
			    'type'    => Controls_Manager::CHOOSE,
			    'default' => 'flex-end',
			    'options' => array(
				    'flex-start'    => array(
					    'title' => esc_html__( 'Top', 'lastudio-kit' ),
					    'icon'  => 'eicon-v-align-top',
				    ),
				    'center' => array(
					    'title' => esc_html__( 'Middle', 'lastudio-kit' ),
					    'icon'  => 'eicon-v-align-middle',
				    ),
				    'flex-end' => array(
					    'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
					    'icon'  => 'eicon-v-align-bottom',
				    ),
			    ),
			    'selectors'  => array(
				    '{{WRAPPER}} '. $css_scheme['content_wrap']  => 'justify-content: {{VALUE}};',
			    ),
		    )
	    );

	    $this->add_control(
		    'content_bg',
		    array(
			    'label'     => esc_html__( 'Content Background', 'lastudio-kit' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['content_wrap'] => 'background-color: {{VALUE}}',
			    ),
		    )
	    );

	    $this->add_responsive_control(
		    'item_content_padding',
		    array(
			    'label'      => __( 'Content Padding', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%', 'vh'],
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['content_wrap'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

        $this->end_controls_section();

        $this->register_carousel_arrows_dots_style_section( [ 'enable_masonry!' => 'yes' ] );
    }
    /**
     * Get loop image html
     *
     */

    public function get_loop_image_item() {

        $image_data = $this->_loop_image_item('item_image', '', false);
        $title = $this->_loop_item(['item_title']);
        if(!empty($image_data)){
	        $giflazy = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
	        $giflazy = $image_data[0];
            $srcset = sprintf('width="%1$d" height="%2$d" style="--img-height:%3$dpx"', $image_data[1], $image_data[2], $image_data[2]);
            return sprintf( apply_filters('lastudio-kit/images-layout/image-format', '<img src="%1$s" alt="%5$s" loading="lazy" class="%3$s" %4$s>'), $giflazy, $image_data[0], 'lakit-images-layout__image-instance' , $srcset, esc_attr(wp_strip_all_tags($title)));
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

    protected function _loop_icon( $format ){
        $item = $this->_processed_item;
        return $this->_get_icon_setting( $item['item_icon'], $format );
    }

	protected function _btn_icon( $format ){
		$settings = $this->get_settings_for_display();
		return $this->_get_icon_setting( $settings['selected_btn_icon'], $format );
	}

    protected function render() {

        $this->_context = 'render';

        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();
    }

}