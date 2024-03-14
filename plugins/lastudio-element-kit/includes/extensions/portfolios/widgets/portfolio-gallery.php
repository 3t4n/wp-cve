<?php


namespace LaStudioKitExtensions\Portfolios\Widgets;

if (!defined('WPINC')) {
    die;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\LaStudioKit_Base;


class Portfolio_Gallery extends LaStudioKit_Base {
    
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
                wp_register_style( 'lakit-images-layout', lastudio_kit()->plugin_url( 'assets/css/addons/images-layout.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
                $this->add_style_depends( 'lakit-images-layout' );
            }
        }
    }

    public function get_inline_css_depends() {
        return [
            [
                'name' => 'lakit-images-layout'
            ]
        ];
    }

    public function get_widget_css_config($widget_name){
        if( $widget_name === 'lakit-portfolio-gallery' ){
            return parent::get_widget_css_config($widget_name);
        }
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
        return 'lakit-portfolio-gallery';
    }

    protected function get_widget_title() {
        return esc_html__( 'Portfolio Gallery', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

	public function get_categories() {
		return [ 'lastudiokit-builder' ];
	}

    protected function set_template_output(){
        return lastudio_kit()->plugin_path('includes/extensions/portfolios/widget-templates');
    }

    protected function register_controls() {

        $css_scheme = apply_filters(
            'lastudio-kit/portfolio-gallery/css-schema',
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

        $this->add_responsive_control(
            'columns',
            array(
                'label'   => esc_html__( 'Columns', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 1,
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

        $this->_add_control(
            'include_featured_image',
            array(
                'type'         => 'switcher',
                'label'        => esc_html__( 'Include Featured Image', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => 'true',
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

        $this->register_carousel_arrows_dots_style_section( [ 'enable_masonry!' => 'yes' ] );
    }
    /**
     * Get loop image html
     *
     */

    public function get_loop_image_item() {

        $image_data = $this->_loop_image_item('item_image', '', false);

        if(!empty($image_data)){
	        $giflazy = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
	        $giflazy = $image_data[0];
            $srcset = sprintf('width="%d" height="%d" srcset="%s" style="--img-height:%dpx"', $image_data[1], $image_data[2], $giflazy, $image_data[2]);
            return sprintf( apply_filters('lastudio-kit/images-layout/image-format', '<img src="%1$s" data-src="%2$s" alt="" loading="lazy" class="%3$s" %4$s>'), $giflazy, $image_data[0], 'lakit-images-layout__image-instance' , $srcset);
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
            $params[] = $image_item['url'];
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

	    global $post;

	    if( !$post instanceof \WP_Post){
			return;
	    }

        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();
    }

}