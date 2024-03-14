<?php
/**
 * Class: Soft_Template_Logo
 * Name: Logo
 * Slug: soft-template-logo
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Soft_Template_Logo extends SoftTemplate_Base {
    public function get_name() {
		return 'soft-template-logo';
	}

	public function get_title() {
		return esc_html__( 'Site Logo', 'soft-template-core' );
	}

    public function get_icon() {
		return 'eicon-site-logo';
	}

    public function get_jet_help_url() {
		return '#';
	}

    public function get_categories() {
		return array( 'soft-template-core' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'soft-template-core' ),
			)
		);

		$this->add_control(
			'logo_type',
			array(
				'type'    => 'select',
				'label'   => esc_html__( 'Logo Type', 'soft-template-core' ),
				'default' => 'site_default',
				'options' => array(
					'site_default'  => esc_html__( 'Site Default', 'soft-template-core' ),
					'text'  => esc_html__( 'Text', 'soft-template-core' ),
					'image' => esc_html__( 'Image', 'soft-template-core' ),
					'both'  => esc_html__( 'Both Text and Image', 'soft-template-core' ),
				),
			)
		);

		$this->add_control(
			'logo_image',
			array(
				'label'     => esc_html__( 'Logo Image', 'soft-template-core' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'logo_type!' => 'text',
					'logo_type!' => 'site_default',
				),
			)
		);

		$this->add_control(
			'logo_image_2x',
			array(
				'label'     => esc_html__( 'Retina Logo Image', 'soft-template-core' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'logo_type!' => 'text',
                    'logo_type!' => 'site_default',
				),
			)
		);

		$this->add_control(
			'logo_text_from',
			array(
				'type'       => 'select',
				'label'      => esc_html__( 'Logo Text From', 'soft-template-core' ),
				'default'    => 'site_name',
				'options'    => array(
					'site_name' => esc_html__( 'Site Name', 'soft-template-core' ),
					'custom'    => esc_html__( 'Custom', 'soft-template-core' ),
				),
				'condition' => array(
					'logo_type!' => 'image',
                    'logo_type!' => 'site_default',
				),
			)
		);

		$this->add_control(
			'logo_text',
			array(
				'label'     => esc_html__( 'Custom Logo Text', 'soft-template-core' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'logo_text_from' => 'custom',
					'logo_type!'     => 'image',
                    'logo_type!' => 'site_default',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'soft-template-core' ),
			)
		);

		$this->add_control(
			'linked_logo',
			array(
				'label'        => esc_html__( 'Linked Logo', 'soft-template-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'soft-template-core' ),
				'label_off'    => esc_html__( 'No', 'soft-template-core' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'remove_link_on_front',
			array(
				'label'        => esc_html__( 'Remove Link on Front Page', 'soft-template-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'soft-template-core' ),
				'label_off'    => esc_html__( 'No', 'soft-template-core' ),
				'return_value' => 'true',
				'default'      => '',
			)
		);

		$this->add_control(
			'logo_display',
			array(
				'type'        => 'select',
				'label'       => esc_html__( 'Display Logo Image and Text', 'soft-template-core' ),
				'label_block' => true,
				'default'     => 'block',
				'options'     => array(
					'inline' => esc_html__( 'Inline', 'soft-template-core' ),
					'block'  => esc_html__( 'Text Below Image', 'soft-template-core' ),
				),
				'condition' => array(
					'logo_type' => 'both',
				),
			)
		);

		$this->end_controls_section();

		$this->__start_controls_section(
			'logo_style',
			array(
				'label'      => esc_html__( 'Logo', 'soft-template-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_responsive_control(
			'logo_alignment',
			array(
				'label'   => esc_html__( 'Logo Alignment', 'soft-template-core' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'soft-template-core' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'soft-template-core' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'End', 'soft-template-core' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .soft-template-logo' => 'justify-content: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'vertical_logo_alignment',
			array(
				'label'       => esc_html__( 'Image and Text Vertical Alignment', 'soft-template-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'center',
				'label_block' => true,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'soft-template-core' ),
						'icon' => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Middle', 'soft-template-core' ),
						'icon' => 'eicon-v-align-middle',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'soft-template-core' ),
						'icon' => 'eicon-v-align-bottom',
					),
					'baseline' => array(
						'title' => esc_html__( 'Baseline', 'soft-template-core' ),
						'icon' => 'eicon-v-align-bottom',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .soft-template-logo__link' => 'align-items: {{VALUE}}',
				),
				'condition' => array(
					'logo_type'    => 'both',
					'logo_display' => 'inline',
				),
			),
			25
		);

		$this->__end_controls_section();

		$this->__start_controls_section(
			'text_logo_style',
			array(
				'label'      => esc_html__( 'Text', 'soft-template-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_control(
			'text_logo_color',
			array(
				'label'     => esc_html__( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .soft-template-logo__text' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_logo_typography',
				'selector' => '{{WRAPPER}} .soft-template-logo__text',
			),
			50
		);

		$this->__add_control(
			'text_logo_gap',
			array(
				'label'      => esc_html__( 'Gap', 'soft-template-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 5,
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .soft-template-logo-display-block .soft-template-logo__img'  => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .soft-template-logo-display-inline .soft-template-logo__img' => 'margin-right: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'logo_type' => 'both',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'image_logo_width',
			array(
				'label'      => esc_html__( 'WIdth', 'soft-template-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .soft-template-logo__img'  => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'logo_type' => 'image',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'text_logo_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'soft-template-core' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'soft-template-core' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'soft-template-core' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'soft-template-core' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .soft-template-logo__text' => 'text-align: {{VALUE}}',
				),
				'condition' => array(
					'logo_type'    => 'both',
					'logo_display' => 'block',
				),
			),
			50
		);

		$this->__end_controls_section();

	}

    protected function render() {
        $this->__context = 'render';

		$this->__open_wrap();
		include $this->__get_global_template( 'index' );
		$this->__close_wrap();
    }

    	/**
	 * Check if logo is linked
	 * @return [type] [description]
	 */
	public function __is_linked() {

		$settings = $this->get_settings();

		if ( empty( $settings['linked_logo'] ) ) {
			return false;
		}

		if ( 'true' === $settings['remove_link_on_front'] && is_front_page() ) {
			return false;
		}

		return true;

	}

	/**
	 * Returns logo text
	 *
	 * @return string Text logo HTML markup.
	 */
	public function __get_logo_text() {

		$settings    = $this->get_settings();
		$type        = isset( $settings['logo_type'] ) ? esc_attr( $settings['logo_type'] ) : 'text';
		$text_from   = isset( $settings['logo_text_from'] ) ? esc_attr( $settings['logo_text_from'] ) : 'site_name';
		$custom_text = isset( $settings['logo_text'] ) ? esc_attr( $settings['logo_text'] ) : '';

		if ( 'image' === $type ) {
			return;
		}

		if ( 'site_name' === $text_from ) {
			$text = get_bloginfo( 'name' );
		} else {
			$text = $custom_text;
		}

		$format = apply_filters(
			'soft-template-core/widgets/logo/text-foramt',
			'<div class="soft-template-logo__text">%s</div>'
		);

		return sprintf( $format, $text );
	}

	/**
	 * Returns logo classes string
	 *
	 * @return string
	 */
	public function __get_logo_classes() {

		$settings = $this->get_settings();

		$classes = array(
			'soft-template-logo',
			'soft-template-logo-type-' . $settings['logo_type'],
			'soft-template-logo-display-' . $settings['logo_display'],
		);

		return implode( ' ', $classes );
	}

	/**
	 * Returns logo image
	 *
	 * @return string Image logo HTML markup.
	 */
	public function __get_logo_image() {

		$settings = $this->get_settings();
		$type     = isset( $settings['logo_type'] ) ? esc_attr( $settings['logo_type'] ) : 'text';
		$image    = isset( $settings['logo_image'] ) ? $settings['logo_image'] : false;
		$image_2x = isset( $settings['logo_image_2x'] ) ? $settings['logo_image_2x'] : false;

		if ( 'text' === $type || ! $image ) {
			return;
		}

		$image_src    = $this->__get_logo_image_src( $image );
		$image_2x_src = $this->__get_logo_image_src( $image_2x );

		if ( empty( $image_src ) && empty( $image_2x_src ) ) {
			return;
		}

		$format = apply_filters(
			'soft-template-core/widgets/logo/image-format',
			'<img src="%1$s" class="soft-template-logo__img" alt="%2$s"%3$s>'
		);

		$image_data = ! empty( $image['id'] ) ? wp_get_attachment_image_src( $image['id'], 'full' ) : array();
		$width      = isset( $image_data[1] ) ? $image_data[1] : false;
		$height     = isset( $image_data[2] ) ? $image_data[2] : false;

		$attrs = sprintf(
			'%1$s%2$s%3$s',
			$width ? ' width="' . $width . '"' : '',
			$height ? ' height="' . $height . '"' : '',
			( ! empty( $image_2x_src ) ? ' srcset="' . esc_url( $image_2x_src ) . ' 2x"' : '' )
		);

		return sprintf( $format, esc_url( $image_src ), get_bloginfo( 'name' ), $attrs );
	}

	public function __get_logo_image_src( $args = array() ) {

		if ( ! empty( $args['id'] ) ) {
			$img_data = wp_get_attachment_image_src( $args['id'], 'full' );

			return ! empty( $img_data[0] ) ? $img_data[0] : false;
		}

		if ( ! empty( $args['url'] ) ) {
			return $args['url'];
		}

		return false;
	}
}