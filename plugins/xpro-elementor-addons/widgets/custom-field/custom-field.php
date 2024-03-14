<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;
use ElementorPro\Modules\ThemeBuilder\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Xpro Elementor Addons
 *
 * Elementor widget.
 *
 * @since 1.0.0
 */
class Custom_Field extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve image widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'xpro-custom-field';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Custom Field', 'xpro-elementor-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'xi-add-new-item xpro-widget-label';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the image widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'xpro-widgets' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_keywords() {
		return array( 'acf', 'fields', 'custom fields', 'meta', 'settings' );
	}

	public function xpro_filter_oembed_result( $html ) {
		$settings = $this->get_settings_for_display();

		$params = array();

		if ( 'youtube' === $settings['cf_video_type'] ) {
			$youtube_options = array( 'autoplay', 'rel', 'controls', 'showinfo' );

			foreach ( $youtube_options as $option ) {

				$value             = ( 'yes' === $settings[ 'cf_yt_' . $option ] ) ? '1' : '0';
				$params[ $option ] = $value;
			}

			$params['wmode'] = 'opaque';
		}

		if ( 'vimeo' === $settings['cf_video_type'] ) {
			$vimeo_options = array( 'autoplay', 'loop', 'title', 'portrait', 'byline', 'muted', 'background' );

			foreach ( $vimeo_options as $option ) {

				$value             = ( 'yes' === $settings[ 'vimeo_' . $option ] ) ? '1' : '0';
				$params[ $option ] = $value;
				if ( 'yes' === $settings['vimeo_background'] ) {
					unset( $params ['autoplay'] );
					unset( $params ['loop'] );
					unset( $params ['title'] );
				}
			}

			$params['color'] = str_replace( '#', '', $settings['vimeo_color'] );

		}

		if ( ! empty( $params ) ) {
			preg_match( '/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $html, $matches );
			$url = esc_url( add_query_arg( $params, $matches[1] ) );

			$html = str_replace( $matches[1], $url, $html );
		}

		return $html;
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'custom-field',
			array(
				'label'       => __( 'Name', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your custom field name', 'xpro-elementor-addons' ),
				'default'     => __( 'key_name', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'cf_type',
			array(
				'label'   => __( 'Type', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'text'   => __( 'Default', 'xpro-elementor-addons' ),
					'html'   => __( 'Html', 'xpro-elementor-addons' ),
					'link'   => __( 'Link', 'xpro-elementor-addons' ),
					'image'  => __( 'Image', 'xpro-elementor-addons' ),
					'video'  => __( 'Video', 'xpro-elementor-addons' ),
					'audio'  => __( 'Audio', 'xpro-elementor-addons' ),
					'oembed' => __( 'oEmbed', 'xpro-elementor-addons' ),
					'date'   => __( 'Date', 'xpro-elementor-addons' ),
				),
				'default' => 'text',
			)
		);

		$this->add_control(
			'link_type',
			array(
				'label'     => __( 'Link Type', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'default' => __( 'Default', 'xpro-elementor-addons' ),
					'email'   => __( 'Email', 'xpro-elementor-addons' ),
					'tel'     => __( 'Telephone', 'xpro-elementor-addons' ),
				),
				'default'   => 'default',
				'condition' => array(
					'cf_type' => 'link',
				),
			)
		);

		if ( class_exists( 'ACF' ) ) {
			$this->add_control(
				'acf_support',
				array(
					'label'     => __( 'ACF Formatting', 'xpro-elementor-addons' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_off' => __( 'No', 'xpro-elementor-addons' ),
					'label_on'  => __( 'Yes', 'xpro-elementor-addons' ),
					'condition' => array(
						'cf_type' => array( 'text', 'link', 'audio', 'date' ),
					),

				)
			);
		}

		$date_format = array(
			'F j, Y g:i a'     => gmdate( 'F j, Y g:i a' ),
			'F j, Y'           => gmdate( 'F j, Y' ),
			'F, Y'             => gmdate( 'F, Y' ),
			'g:i a'            => gmdate( 'g:i a' ),
			'g:i:s a'          => gmdate( 'g:i:s a' ),
			'l, F jS, Y'       => gmdate( 'l, F jS, Y' ),
			'M j, Y @ G:i'     => gmdate( 'M j, Y @ G:i' ),
			'Y/m/d \a\t g:i A' => gmdate( 'Y/m/d \a\t g:i A' ),
			'Y/m/d \a\t g:ia'  => gmdate( 'Y/m/d \a\t g:ia' ),
			'Y/m/d g:i:s A'    => gmdate( 'Y/m/d g:i:s A' ),
			'Y/m/d'            => gmdate( 'Y/m/d' ),
			'Y-m-d \a\t g:i A' => gmdate( 'Y-m-d \a\t g:i A' ),
			'Y-m-d \a\t g:ia'  => gmdate( 'Y-m-d \a\t g:ia' ),
			'Y-m-d g:i:s A'    => gmdate( 'Y-m-d g:i:s A' ),
			'Y-m-d'            => gmdate( 'Y-m-d' ),
			'custom'           => __( 'Custom', 'xpro-elementor-addons' )
		);

		$date_format['default'] = 'Default';

		$date_format_condition = array(
			'cf_type' => 'date'
		);

		if ( class_exists( 'ACF' ) ) {
			$date_format_condition = array(
				'acf_support' => '',
				'cf_type'     => 'date',
			);
		}

		$this->add_control(
			'date_format',
			array(
				'label'       => __( 'Date format', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => $date_format,
				'default'     => 'F j, Y',
				'condition'   => $date_format_condition,
				'description' => '<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank"> Click here</a> for documentation on date and time formatting.',
			)
		);

		$this->add_control(
			'date_custom_format',
			array(
				'label'       => __( 'Date Format', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Date Format', 'xpro-elementor-addons' ),
				'default'     => 'y:m:d',
				'condition'   => array(
					'date_format' => 'custom',
				),
			)
		);

		$this->add_control(
			'cf_video_type',
			array(
				'label'     => __( 'Video Type', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'youtube' => __( 'Youtube Video', 'xpro-elementor-addons' ),
					'vimeo'   => __( 'Vimeo Video', 'xpro-elementor-addons' ),
				),
				'default'   => 'youtube',
				'condition' => array(
					'cf_type' => 'video',
				),
			)
		);

		$this->add_control(
			'aspect_ratio',
			array(
				'label'              => __( 'Aspect Ratio', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'frontend_available' => true,
				'options'            => array(
					'169' => '16:9',
					'43'  => '4:3',
					'32'  => '3:2',
				),
				'default'            => '169',
				'condition'          => array(
					'cf_type' => 'video',
				),
			)
		);

		$this->youtube_video_options();
		$this->vimeo_video_options();

		$this->add_control(
			'link_text_type',
			array(
				'label'     => __( 'Links To', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'static'       => __( 'Static', 'xpro-elementor-addons' ),
					'custom_field' => __( 'Custom Field', 'xpro-elementor-addons' ),
					'post'         => __( 'Post', 'xpro-elementor-addons' ),
				),
				'default'   => 'static',
				'condition' => array(
					'cf_type' => 'link',
				),
			)
		);

		$this->add_control(
			'cf_link_text',
			array(
				'label'       => __( 'Static Text', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Link Text', 'xpro-elementor-addons' ),
				'condition'   => array(
					'cf_type'        => 'link',
					'link_text_type' => 'static',
				),
			)
		);

		$this->add_control(
			'cf_link_dynamic_text',
			array(
				'label'       => __( 'Enter Field Key', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Field Key', 'xpro-elementor-addons' ),
				'condition'   => array(
					'cf_type'        => 'link',
					'link_text_type' => 'custom_field',
				),
				'description' => __( 'Data from this custom field will be used for anchor text', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'cf_link_target',
			array(
				'label'     => __( 'Open in new tab', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'xpro-elementor-addons' ),
				'label_on'  => __( 'Yes', 'xpro-elementor-addons' ),
				'condition' => array(
					'cf_type' => array( 'link', 'image' ),
				),
			)
		);

		$this->add_control(
			'cf_label',
			array(
				'label'       => __( 'Label', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Label', 'xpro-elementor-addons' ),
				'condition'   => array(
					'cf_type' => array( 'text', 'link', 'date' ),
				),
			)
		);

		$this->add_control(
			'cf_icon',
			array(
				'label'       => __( 'Icon', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => true,
				'condition'   => array(
					'cf_type' => array( 'text', 'link', 'date' ),
				),
			)
		);

		$this->add_control(
			'header_size',
			array(
				'label'     => __( 'HTML Tag', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'   => __( 'H1', 'xpro-elementor-addons' ),
					'h2'   => __( 'H2', 'xpro-elementor-addons' ),
					'h3'   => __( 'H3', 'xpro-elementor-addons' ),
					'h4'   => __( 'H4', 'xpro-elementor-addons' ),
					'h5'   => __( 'H5', 'xpro-elementor-addons' ),
					'h6'   => __( 'H6', 'xpro-elementor-addons' ),
					'div'  => __( 'div', 'xpro-elementor-addons' ),
					'span' => __( 'span', 'xpro-elementor-addons' ),
					'p'    => __( 'p', 'xpro-elementor-addons' ),
				),
				'default'   => 'span',
				'condition' => array(
					'cf_type' => array( 'text', 'date' ),
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'cf_type!' => 'video',
				),
			)
		);
		$this->add_control(
			'links_to',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => __( 'Link to', 'xpro-elementor-addons' ),
				'options'   => array(
					''             => __( 'None', 'xpro-elementor-addons' ),
					'post'         => __( 'Post', 'xpro-elementor-addons' ),
					'media'        => __( 'Lightbox', 'xpro-elementor-addons' ),
					'static'       => __( 'Custom URL', 'xpro-elementor-addons' ),
					'custom_field' => __( 'Custom Field', 'xpro-elementor-addons' ),
				),
				'condition' => array(
					'cf_type' => 'image',
				),
			)
		);

		$this->add_control(
			'cf_link_image',
			array(
				'label'       => __( 'Custom URL', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter URL', 'xpro-elementor-addons' ),
				'condition'   => array(
					'cf_type'  => 'image',
					'links_to' => 'static',
				),
			)
		);
		$this->add_control(
			'cf_link_dynamic_image',
			array(
				'label'       => __( 'Enter Field Key', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Field Key', 'xpro-elementor-addons' ),
				'condition'   => array(
					'cf_type'  => 'image',
					'links_to' => 'custom_field',
				),
				'description' => __( 'Data from this custom field will be used for image link', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image',
				'label'     => __( 'Image Size', 'xpro-elementor-addons' ),
				'default'   => 'large',
				'exclude'   => array( 'custom' ),
				'condition' => array(
					'cf_type' => 'image',
				),

			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_general_field_style',
			array(
				'label'     => __( 'General', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'cf_type!' => 'video',
				),

			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'typography',
				'selector'  => '{{WRAPPER}} .xpro-element-custom-field',
				'condition' => array(
					'cf_type' => array( 'text', 'link', 'html', 'date' ),
				),
			)
		);

		$this->add_control(
			'custom_field_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-element-custom-field' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'cf_type' => array( 'text', 'html', 'link', 'date' ),
				),
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'      => __( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
				),
				'size_units' => array( 'px', '%', 'vw' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-element-custom-field img' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'cf_type' => 'image',
				),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'          => __( 'Height', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( 'px', 'vh' ),
				'range'          => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .xpro-element-custom-field img' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'cf_type' => 'image',
				),
			)
		);

		$this->add_responsive_control(
			'object-fit',
			array(
				'label'     => __( 'Object Fit', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => array(
					'height[size]!' => '',
					'cf_type'       => 'image',
				),
				'options'   => array(
					''        => __( 'Default', 'xpro-elementor-addons' ),
					'fill'    => __( 'Fill', 'xpro-elementor-addons' ),
					'cover'   => __( 'Cover', 'xpro-elementor-addons' ),
					'contain' => __( 'Contain', 'xpro-elementor-addons' ),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-element-custom-field img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'cf_text_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-element-custom-field' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_general_icon_style',
			array(
				'label'     => __( 'Icon', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'cf_icon!' => '',
					'cf_type'  => array( 'text', 'link', 'date' ),
				),

			)
		);

		$this->add_control(
			'icon_size',
			array(
				'label'     => __( 'Size', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-element-custom-field-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'cf_icon!' => '',
					'cf_type'  => array( 'text', 'link', 'date' ),
				),
			)
		);

		$this->add_responsive_control(
			'icon_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-element-custom-field-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'custom_field_icon' );

		$this->start_controls_tab(
			'custom_field_icon_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-element-custom-field-icon i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'custom_field_icon_hover',
			array(
				'label' => __( 'Color', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'icon_hover_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ,xpro-cf-wrapper:hover .xpro-element-custom-field-icon i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		//Label
		$this->start_controls_section(
			'section_general_label_style',
			array(
				'label'     => __( 'Label', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'cf_label!' => '',
					'cf_type'   => array( 'text', 'link', 'date' ),
				),

			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cf_label_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-element-custom-field-label',
			)
		);

		$this->add_control(
			'cf_label_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-element-custom-field-label' => 'color: {{VALUE}};',
				),

			)
		);

		$this->add_control(
			'cf_label_bg',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-element-custom-field-label' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'label_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-element-custom-field-label',
			)
		);

		$this->add_control(
			'label_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-element-custom-field-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'cf_label_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-element-custom-field-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'cf_label_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-element-custom-field-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	public function youtube_video_options() {
		$this->add_control(
			'heading_youtube',
			array(
				'label'     => __( 'Youtube Video Options', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'cf_type'       => 'video',
					'cf_video_type' => 'youtube',
				),
			)
		);

		// YouTube
		$this->add_control(
			'cf_yt_autoplay',
			array(
				'label'     => __( 'Autoplay', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'xpro-elementor-addons' ),
				'label_on'  => __( 'Yes', 'xpro-elementor-addons' ),
				'condition' => array(
					'cf_type'       => 'video',
					'cf_video_type' => 'youtube',
				),
			)
		);

		$this->add_control(
			'cf_yt_rel',
			array(
				'label'     => __( 'Suggested Videos', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'xpro-elementor-addons' ),
				'label_on'  => __( 'Show', 'xpro-elementor-addons' ),
				'condition' => array(
					'cf_type'       => 'video',
					'cf_video_type' => 'youtube',
				),
			)
		);

		$this->add_control(
			'cf_yt_controls',
			array(
				'label'     => __( 'Player Control', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'xpro-elementor-addons' ),
				'label_on'  => __( 'Show', 'xpro-elementor-addons' ),
				'default'   => 'yes',
				'condition' => array(
					'cf_type'       => 'video',
					'cf_video_type' => 'youtube',
				),
			)
		);

		$this->add_control(
			'cf_yt_showinfo',
			array(
				'label'     => __( 'Player Title & Actions', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'xpro-elementor-addons' ),
				'label_on'  => __( 'Show', 'xpro-elementor-addons' ),
				'default'   => 'yes',
				'condition' => array(
					'cf_type'       => 'video',
					'cf_video_type' => 'youtube',
				),
			)
		);
	}

	public function vimeo_video_options() {
		$this->add_control(
			'heading_vimeo',
			array(
				'label'     => __( 'Vimeo Video Options', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'cf_type'       => 'video',
					'cf_video_type' => 'vimeo',
				),
			)
		);

		// Vimeo

		$this->add_control(
			'vimeo_background',
			array(
				'label'     => __( 'Background Mode', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'xpro-elementor-addons' ),
				'lablel_on' => __( 'Yes', 'xpro-elementor-addons' ),
				'default'   => '',
				'condition' => array(
					'cf_type'       => 'video',
					'cf_video_type' => 'vimeo',
				),
			)
		);

		$this->add_control(
			'vimeo_autoplay',
			array(
				'label'     => __( 'Autoplay', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'xpro-elementor-addons' ),
				'label_on'  => __( 'Yes', 'xpro-elementor-addons' ),
				'condition' => array(
					'cf_type'          => 'video',
					'cf_video_type'    => 'vimeo',
					'vimeo_background' => '',
				),
			)
		);

		$this->add_control(
			'vimeo_loop',
			array(
				'label'     => __( 'Loop', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'xpro-elementor-addons' ),
				'label_on'  => __( 'Yes', 'xpro-elementor-addons' ),
				'condition' => array(
					'cf_type'          => 'video',
					'cf_video_type'    => 'vimeo',
					'vimeo_background' => '',
				),
			)
		);

		$this->add_control(
			'vimeo_title',
			array(
				'label'     => __( 'Intro Title', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'xpro-elementor-addons' ),
				'label_on'  => __( 'Show', 'xpro-elementor-addons' ),
				'default'   => 'yes',
				'condition' => array(
					'cf_type'          => 'video',
					'cf_video_type'    => 'vimeo',
					'vimeo_background' => '',
				),
			)
		);

		$this->add_control(
			'vimeo_portrait',
			array(
				'label'     => __( 'Intro Portrait', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'xpro-elementor-addons' ),
				'label_on'  => __( 'Show', 'xpro-elementor-addons' ),
				'default'   => 'yes',
				'condition' => array(
					'cf_type'          => 'video',
					'cf_video_type'    => 'vimeo',
					'vimeo_background' => '',
				),
			)
		);

		$this->add_control(
			'vimeo_byline',
			array(
				'label'     => __( 'Intro Byline', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'xpro-elementor-addons' ),
				'label_on'  => __( 'Show', 'xpro-elementor-addons' ),
				'default'   => 'yes',
				'condition' => array(
					'cf_type'          => 'video',
					'cf_video_type'    => 'vimeo',
					'vimeo_background' => '',
				),
			)
		);

		$this->add_control(
			'vimeo_color',
			array(
				'label'     => __( 'Controls Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_ACCENT,
				),
				'condition' => array(
					'cf_type'          => 'video',
					'cf_video_type'    => 'vimeo',
					'vimeo_background' => '',
				),
			)
		);

		$this->add_control(
			'vimeo_muted',
			array(
				'label'     => __( 'Muted', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'xpro-elementor-addons' ),
				'lablel_on' => __( 'Yes', 'xpro-elementor-addons' ),
				'default'   => '',
				'condition' => array(
					'cf_type'       => 'video',
					'cf_video_type' => 'vimeo',
				),
			)
		);
	}

	public function is_repeater_block_layout() {
		$repeater_data = array();

		$doc = Plugin::$instance->documents->get_current();

		if ( ! isset( $doc ) || is_null( $doc ) ) {
			$repeater_data['is_repeater'] = false;

			return $repeater_data;
		}
		$doc_post = $doc->get_post();

		if ( 'revision' === $doc_post->post_type ) {
			$doc_post = get_post( $doc_post->post_parent );
		}

		$render_mode = get_post_meta( $doc_post->ID, 'xpro_render_mode', true );

		if ( $GLOBALS['post']->ID === $doc_post->ID && 'acf_repeater_layout' === $render_mode ) {
			$repeater_data['is_repeater'] = true;
			$repeater_data['field']       = get_post_meta( $doc_post->ID, 'xpro_acf_repeater_name', true );

		} elseif ( 'xpro_global_templates' === $doc_post->post_type && 'acf_repeater_layout' === $render_mode ) {
			$repeater_data['is_repeater'] = true;
		} else {
			$repeater_data['is_repeater'] = false;
		}

		return $repeater_data;
	}

	/**
	 * Render image widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		if ( ! isset( $settings['cf_type'] ) || '' === $settings['cf_type'] ) {
			$settings['cf_type'] = 'text';
		}

		$post_data  = get_demo_post_data();
		$post_id    = $post_data->ID;
		$post_title = $post_data->post_title;

		$custom_field = $settings['custom-field'];

		if ( class_exists( 'ACF' ) && in_array( $settings['cf_type'], array( 'text', 'link', 'audio', 'date' ), true ) && 'yes' === $settings['acf_support'] ) {
			$custom_field_val = get_field( $custom_field, $post_id );
		} else {
			$custom_field_val = get_post_meta( $post_id, $custom_field, true );
		}

		$cf_link_dynamic_text = '';
		if ( '' !== $settings['cf_link_dynamic_text'] ) {
			$cf_link_dynamic_text = get_post_meta( $post_id, $settings['cf_link_dynamic_text'], true );
		}

		$cf_link_dynamic_image = '';
		if ( 'image' === $settings['cf_type'] && 'custom_field' === $settings['links_to'] && '' !== $settings['cf_link_dynamic_image'] ) {
			$cf_link_dynamic_image = get_post_meta( $post_id, $settings['cf_link_dynamic_image'], true );
		}

		$repeater = $this->is_repeater_block_layout();

		if ( $repeater['is_repeater'] ) {
			if ( isset( $repeater['field'] ) ) {
				$repeater_field   = get_field( $repeater['field'], $post_id );
				$custom_field_val = $repeater_field[0][ $custom_field ];
				if ( 'link' === $settings['cf_type'] && '' !== $settings['cf_link_dynamic_text'] ) {
					$cf_link_dynamic_text = $repeater_field[0][ $settings['cf_link_dynamic_text'] ];
				}
				if ( 'image' === $settings['cf_type'] && 'custom_field' === $settings['links_to'] && '' !== $settings['cf_link_dynamic_image'] ) {
					$cf_link_dynamic_image = $repeater_field[0][ $settings['cf_link_dynamic_image'] ];
				}
			} else {
				$custom_field_val = get_sub_field( $custom_field );
				if ( 'link' === $settings['cf_type'] ) {
					$cf_link_dynamic_text = get_sub_field( $settings['cf_link_dynamic_text'] );
				}
				if ( 'image' === $settings['cf_type'] && 'custom_field' === $settings['links_to'] && '' !== $settings['cf_link_dynamic_image'] ) {
					$cf_link_dynamic_image = get_sub_field( $settings['cf_link_dynamic_image'] );
				}
			}
		}

		$this->add_render_attribute( 'cf-wrapper', 'class', 'cf-type-' . $settings['cf_type'] );
		$this->add_render_attribute( 'cf-wrapper', 'class', 'xpro-cf-wrapper' );
		$this->add_render_attribute( 'custom-field-class', 'class', 'xpro-element-custom-field' );
		$this->add_render_attribute( 'custom-field-label-class', 'class', 'xpro-element-custom-field-label' );
		$this->add_render_attribute( 'post-cf-icon-class', 'class', 'xpro-element-custom-field-icon' );
		$this->add_render_attribute( 'post-cf-icon', 'class', $settings['cf_icon'] );

		if ( empty( $custom_field_val ) ) {
			return;
		}
		if ( empty( $custom_field_val ) ) {
			$this->add_render_attribute( 'cf-wrapper', 'class', 'hide' );
		}

		if ( 'yes' === $settings['cf_link_target'] ) {
			$this->add_render_attribute( 'custom-field-class', 'target', '_blank' );
		}

		$cf_type           = $settings['cf_type'];
		$eid               = $this->get_id();
		$custom_field_html = '';
		switch ( $cf_type ) {

			case 'html':
				if ( ! empty( $custom_field_val ) ) {

					if ( is_array( $custom_field_val ) ) {
						$custom_field_html = '<div ' . $this->get_render_attribute_string( 'custom-field-class' ) . '>' . wpautop( do_shortcode( implode( ', ', $custom_field_val ) ) ) . '</div>';
					} else {
						$custom_field_html = '<div ' . $this->get_render_attribute_string( 'custom-field-class' ) . '>' . wpautop( do_shortcode( $custom_field_val ) ) . '</div>';
					}
				}
				break;

			case 'link':
				if ( 'email' === $settings['link_type'] ) {
					$custom_field_val = 'mailto:' . $custom_field_val;
				} elseif ( 'tel' === $settings['link_type'] ) {
					$custom_field_val = 'tel:' . $custom_field_val;
				}

				if ( ! empty( $settings['cf_link_text'] ) && 'static' === $settings['link_text_type'] ) {
					$custom_field_html = '<a ' . $this->get_render_attribute_string( 'custom-field-class' ) . '  href="' . $custom_field_val . '">' . $settings['cf_link_text'] . '</a>';
				} elseif ( ! empty( $cf_link_dynamic_text ) && 'custom_field' === $settings['link_text_type'] ) {
					$custom_field_html = '<a ' . $this->get_render_attribute_string( 'custom-field-class' ) . '  href="' . $custom_field_val . '">' . $cf_link_dynamic_text . '</a>';
				} else {
					if ( 'default' !== $settings['link_type'] ) {
						$custom_field_html = '<a ' . $this->get_render_attribute_string( 'custom-field-class' ) . ' href="' . $custom_field_val . '">' . get_post_meta( $post_id, $custom_field, true ) . '</a>';
					} else {
						$custom_field_html = '<a ' . $this->get_render_attribute_string( 'custom-field-class' ) . ' href="' . $custom_field_val . '">' . $custom_field_val . '</a>';
					}
				}

				if ( 'post' === $settings['link_text_type'] ) {
					$custom_field_html = '<a ' . $this->get_render_attribute_string( 'custom-field-class' ) . ' href="' . get_permalink( $post_id ) . '">' . $custom_field_val . '</a>';
				}

				break;

			case 'image':
				$post_image_size = $settings['image_size'];

				if ( 'post' === $settings['links_to'] ) {
					$post_link = get_permalink( $post_id );
				} elseif ( 'media' === $settings['links_to'] ) {
					$media_link = wp_get_attachment_image_src( $custom_field_val, 'full' );
					$post_link  = $media_link[0];
				} elseif ( 'static' === $settings['links_to'] ) {
					$post_link = $settings['cf_link_image'];
				} elseif ( 'custom_field' === $settings['links_to'] ) {
					$post_link = $cf_link_dynamic_image;
				}

				if ( is_numeric( $custom_field_val ) ) {
					$custom_field_html = '<div ' . $this->get_render_attribute_string( 'custom-field-class' ) . '>';
					if ( '' !== $settings['links_to'] ) {
						$image_target = '';
						if ( 'yes' === $settings['cf_link_target'] ) {
							$image_target = ' target="_blank"';
						}
						$custom_field_html .= '<a href="' . $post_link . '" title="' . $post_title . '"' . $image_target . '>';
					}
					$custom_field_html .= wp_get_attachment_image( $custom_field_val, $post_image_size );
					if ( '' !== $settings['links_to'] ) {
						$custom_field_html .= '</a>';
					}
					$custom_field_html .= '</div>';
				} else {
					$custom_field_html = '<div ' . $this->get_render_attribute_string( 'custom-field-class' ) . '>';
					if ( '' !== $settings['links_to'] ) {
						$custom_field_html .= '<a href="' . $post_link . '" title="' . $post_title . '">';
					}
					$custom_field_html .= '<img src="' . $custom_field_val . '" />';
					if ( '' !== $settings['links_to'] ) {
						$custom_field_html .= '</a>';
					}
					$custom_field_html .= '</div>';
				}

				break;

			case 'video':
				add_filter( 'oembed_result', array( $this, 'xpro_filter_oembed_result' ), 50, 3 );

				$custom_field_html  = wp_oembed_get( $custom_field_val, wp_embed_defaults() );
				$custom_field_html .= "<script type='text/javascript'>
                                             jQuery(document).ready(function(){
                                                jQuery(document).trigger('elementor/render/cf-video',['" . $eid . "','" . $settings['aspect_ratio'] . "']);
                                             });
                                             jQuery(window).resize(function(){
                                                jQuery(document).trigger('elementor/render/cf-video',['" . $eid . "','" . $settings['aspect_ratio'] . "']);
                                             });
                                             jQuery(document).trigger('elementor/render/cf-video',['" . $eid . "','" . $settings['aspect_ratio'] . "']);
                                         </script>";
				remove_filter( 'oembed_result', array( $this, 'xpro_filter_oembed_result' ), 50 );
				break;

			case 'audio':
				$custom_field_html = wp_audio_shortcode(
					array(
						'src' => $custom_field_val,
					)
				);
				break;

			case 'oembed':
				if ( $repeater['is_repeater'] ) {
					$custom_field_html = $custom_field_val;
				} else {
					$custom_field_html = wp_oembed_get( $custom_field_val, wp_embed_defaults() );
				}
				break;

			case 'date':
				if ( empty( $custom_field_val ) ) {
					break;
				}

				$format = 'g:i A';
				if ( 'custom' === $settings['date_format'] ) {
					$format = $settings['date_custom_format'];
				} elseif ( 'default' === $settings['date_format'] ) {
					$format = get_option( 'date_format' );
				} else {
					$format = $settings['date_format'];
				}
				$custom_field_html = date_i18n( $format, strtotime( $custom_field_val ) );

				if ( isset( $settings['acf_support'] ) && '' !== $settings['acf_support'] ) {
					$custom_field_html = $custom_field_val;
				}

				$custom_field_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], $this->get_render_attribute_string( 'custom-field-class' ), do_shortcode( $custom_field_html ) );
				break;

			default:
				if ( is_array( $custom_field_val ) ) {
					$custom_field_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], $this->get_render_attribute_string( 'custom-field-class' ), do_shortcode( implode( ', ', $custom_field_val ) ) );
				} else {
					$custom_field_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], $this->get_render_attribute_string( 'custom-field-class' ), do_shortcode( $custom_field_val ) );
				}
				break;
		} ?>

		<div <?php $this->print_render_attribute_string( 'cf-wrapper' ); ?>>
			<?php
			if ( ( 'text' === $settings['cf_type'] ) || ( 'link' === $settings['cf_type'] ) || ( 'date' === $settings['cf_type'] ) ) {
				if ( ! empty( $settings['cf_icon']['value'] ) ) {
					?>
					<span <?php $this->print_render_attribute_string( 'post-cf-icon-class' ); ?>>
						<?php \Elementor\Icons_Manager::render_icon( $settings['cf_icon'], array( 'aria-hidden' => 'true' ) ); ?>
					</span>
					<?php
				}

				if ( ! empty( $settings['cf_label'] ) && ! empty( $custom_field_val ) ) {
					?>
					<span <?php $this->print_render_attribute_string( 'custom-field-label-class' ); ?>>
					<?php echo esc_html( $settings['cf_label'] ); ?>
					</span>
					<?php
				}
			}
			echo $custom_field_html;
			?>
		</div>
		<?php
	}

}
