<?php
/**
 * Class: LaStudioKit_Banner
 * Name: Banner
 * Slug: lakit-banner
 */

namespace Elementor;

use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class LaStudioKit_Banner extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    if(!lastudio_kit()->is_optimized_css_mode()) {
			    wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/banner.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
			    $this->add_style_depends( $this->get_name() );
		    }
	    }
    }

	public function get_inline_css_depends() {
		return [
			'lakit-banner2',
		];
	}

	public function get_widget_css_config($widget_name){
		switch ($widget_name){
			case 'lakit-banner2':
				$file_url = lastudio_kit()->plugin_url(  'assets/css/addons/banner2.min.css' );
				$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/banner2.min.css' );
				break;
			default:
				$file_url = lastudio_kit()->plugin_url(  'assets/css/addons/banner1.min.css' );
				$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/banner1.min.css' );
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
		return 'lakit-banner';
	}

	public function get_widget_title() {
		return esc_html__( 'Banner', 'lastudio-kit' );
	}

	public function get_icon() {
		return 'lastudio-kit-icon-banner';
	}


	protected function register_controls() {

		$this->_start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'lastudio-kit' ),
			)
		);

		$this->_add_control(
			'banner_image',
			array(
				'label'   => esc_html__( 'Image', 'lastudio-kit' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->_add_control(
			'banner_image_size',
			array(
				'type'       => 'select',
				'label'      => esc_html__( 'Image Size', 'lastudio-kit' ),
				'default'    => 'full',
				'options'    => lastudio_kit_helper()->get_image_sizes(),
			)
		);

		$this->_add_control(
			'banner_title',
			array(
				'label'   => esc_html__( 'Title', 'lastudio-kit' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->_add_control(
			'banner_title_html_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'lastudio-kit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => lastudio_kit_helper()->get_available_title_html_tags(),
				'default' => 'h5',
			)
		);

		$this->_add_control(
			'banner_text',
			array(
				'label'   => esc_html__( 'Description', 'lastudio-kit' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->_add_control(
			'banner_link',
			array(
				'label'   => esc_html__( 'Link', 'lastudio-kit' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					),
				),
			)
		);

		$this->_add_control(
			'banner_link_target',
			array(
				'label'        => esc_html__( 'Open link in new window', 'lastudio-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '_blank',
				'condition'    => array(
					'banner_link!' => '',
				),
			)
		);

		$this->_add_control(
			'banner_link_rel',
			array(
				'label'        => esc_html__( 'Add nofollow', 'lastudio-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'nofollow',
				'condition'    => array(
					'banner_link!' => '',
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
			'animation_effect',
			array(
				'label'   => esc_html__( 'Animation Effect', 'lastudio-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'lily',
				'options' => array(
					'none'   => esc_html__( 'None', 'lastudio-kit' ),
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
			)
		);

		$this->_end_controls_section();

		$css_scheme = apply_filters(
			'lastudio-kit/banner/css-schema',
			array(
				'banner'         => '.lakit-banner',
				'banner_content' => '.lakit-banner__content',
				'banner_overlay' => '.lakit-banner__overlay',
				'banner_title'   => '.lakit-banner__title',
				'banner_text'    => '.lakit-banner__text',
			)
		);

		$this->_start_controls_section(
			'section_banner_item_style',
			array(
				'label'      => esc_html__( 'General', 'lastudio-kit' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'banner_container_heading',
			array(
				'label'     => esc_html__( 'Container', 'lastudio-kit' ),
				'type'      => Controls_Manager::HEADING,
			),
			100
		);

		$this->_add_responsive_control(
			'banner_padding',
			array(
				'label'      => __( 'Padding', 'lastudio-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['banner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'banner_margin',
			array(
				'label'      => __( 'Margin', 'lastudio-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['banner'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'banner_border',
				'label'       => esc_html__( 'Border', 'lastudio-kit' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['banner'],
			),
			100
		);

		$this->_add_responsive_control(
			'banner_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['banner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'banner_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['banner'],
			),
			100
		);

		$this->_add_control(
			'banner_overlay_heading',
			array(
				'label'     => esc_html__( 'Overlay', 'lastudio-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
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
					'{{WRAPPER}} .lakit-ef-layla ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-layla ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-oscar ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-marley ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-ruby ' . $css_scheme['banner_text'] => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-roxy ' . $css_scheme['banner_text'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-roxy ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-bubba ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-bubba ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-romeo ' . $css_scheme['banner_content'] . '::before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-romeo ' . $css_scheme['banner_content'] . '::after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-sarah ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-chico ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
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
					'{{WRAPPER}} .lakit-ef-layla:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-layla:hover ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-oscar:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-marley:hover ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-ruby:hover ' . $css_scheme['banner_text'] => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-roxy:hover ' . $css_scheme['banner_text'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-roxy:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-bubba:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-bubba:hover ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-romeo:hover ' . $css_scheme['banner_content'] . '::before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-romeo:hover ' . $css_scheme['banner_content'] . '::after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-sarah:hover ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .lakit-ef-chico:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
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

		$this->_add_control(
			'banner_order_heading',
			array(
				'label'     => esc_html__( 'Order', 'lastudio-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			100
		);

		$this->_add_control(
			'banner_title_order',
			array(
				'label'   => esc_html__( 'Title Order', 'lastudio-kit' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 1,
				'max'     => 2,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['banner_title'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'banner_text_order',
			array(
				'label'   => esc_html__( 'Description Order', 'lastudio-kit' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 1,
				'max'     => 2,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['banner_text'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_banner_title_style',
			array(
				'label'      => esc_html__( 'Title', 'lastudio-kit' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);


		$this->_add_responsive_control(
			'title_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
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
					'{{WRAPPER}} ' . $css_scheme['banner_title'] => 'text-align: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'banner_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['banner_title'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'banner_title_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['banner_title'],
			),
			50
		);

		$this->_add_responsive_control(
			'title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['banner_title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['banner_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_banner_text_style',
			array(
				'label'      => esc_html__( 'Description', 'lastudio-kit' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'text_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
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
					'{{WRAPPER}} ' . $css_scheme['banner_text'] => 'text-align: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'banner_text_color',
			array(
				'label'     => esc_html__( 'Description Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['banner_text'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'banner_text_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['banner_text'],
			),
			50
		);

		$this->_add_responsive_control(
			'text_margin',
			array(
				'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['banner_text'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();

	}

	protected function render() {

		$this->_context = 'render';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

	public function _get_banner_image() {

		$image = $this->get_settings_for_display( 'banner_image' );

		if ( empty( $image['id'] ) && empty( $image['url'] ) ) {
			return;
		}

		$format = apply_filters( 'lastudio-kit/banner/image-format', '<img src="%1$s" alt="%2$s" class="lakit-banner__img">' );

		if ( empty( $image['id'] ) ) {
			return sprintf( $format, $image['url'], '' );
		}

		$size = $this->get_settings_for_display( 'banner_image_size' );

		if ( ! $size ) {
			$size = 'full';
		}

		$image_url = wp_get_attachment_image_url( $image['id'], $size );
		$alt       = esc_attr( Control_Media::get_image_alt( $image ) );

		return sprintf( $format, $image_url, $alt );
	}

}
