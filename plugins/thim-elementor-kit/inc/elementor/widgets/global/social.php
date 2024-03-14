<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Ekit_Widget_Social extends Widget_Base {

	public function get_name() {
		return 'thim-ekits-social';
	}

	public function get_title() {
		return esc_html__( 'Social', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-social-icons';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY );
	}

	public function get_keywords() {
		return [
			'thim',
			'social',
			'socials'
		];
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	protected function register_controls() {
		// start content section for social media
		$this->start_controls_section(
			'social_icon_section_tab_content',
			array(
				'label' => esc_html__( 'Social Icons', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'social_icon_style',
			array(
				'label'   => esc_html__( 'Choose Style', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => array(
					'icon' => esc_html__( 'Icon', 'thim-elementor-kit' ),
					'text' => esc_html__( 'Text', 'thim-elementor-kit' ),
					'both' => esc_html__( 'Both', 'thim-elementor-kit' ),
				),
			)
		);

		$this->add_control(
			'social_icon_style_icon_position',
			array(
				'label'     => esc_html__( 'Icon Position', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'before',
				'options'   => array(
					'before' => esc_html__( 'Before', 'thim-elementor-kit' ),
					'after'  => esc_html__( 'After', 'thim-elementor-kit' ),
				),
				'condition' => array(
					'social_icon_style' => 'both',
				),
			)
		);

		$this->add_responsive_control(
			'social_icon_icon_padding_right',
			array(
				'label'      => esc_html__( 'Spacing Right', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 5,
				),
				'selectors'  => array(
					'{{WRAPPER}} a > i' => 'padding-right: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'social_icon_style'               => 'both',
					'social_icon_style_icon_position' => 'before',
				),
			)
		);

		$this->add_responsive_control(
			'social_icon_icon_padding_left',
			array(
				'label'      => esc_html__( 'Spacing Left', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 5,
				),
				'selectors'  => array(
					'{{WRAPPER}} a > i' => 'padding-left: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'social_icon_style'               => 'both',
					'social_icon_style_icon_position' => 'after',
				),
			)
		);

		$this->add_responsive_control(
			'socialicon_list_align',
			array(
				'label'     => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'left',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .thim-social-media' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->register_social_repeater_controls();

		$this->end_controls_section();


		$this->register_style_controls();
	}

	protected function register_social_repeater_controls() {


		$social_repeater = new Repeater();

		$social_repeater->add_control(
			'social_icon_icons',
			array(
				'label'            => esc_html__( 'Icon', 'thim-elementor-kit' ),
				// 'label_block'      => true,
				'type'             => Controls_Manager::ICONS,
				'skin'             => 'inline',
				'label_block'      => false,
				'fa4compatibility' => 'social_icon_icon',
				'default'          => array(
					'value'   => 'fab fa-facebook-f',
					'library' => 'Font Awesome 5 Brands',
				),
			)
		);

		$this->social_icon_link_type( $social_repeater );
		$social_repeater->add_control(
			'social_icon_label',
			array(
				'label'   => esc_html__( 'Label', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Facebook',
			)
		);

		$social_repeater->start_controls_tabs(
			'social_icon_tabs'
		);

		$social_repeater->start_controls_tab(
			'social_icon_normal',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$social_repeater->add_responsive_control(
			'social_icon_icon_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#222222',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} > a'          => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} > a svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$social_repeater->add_responsive_control(
			'social_icon_icon_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} > a' => 'background-color: {{VALUE}};',
				),
			)
		);

		$social_repeater->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'social_icon_border',
				'label'    => esc_html__( 'Border', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} > a',
			)
		);

		$social_repeater->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'socialicon_list_box_shadow',
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} > a',
			)
		);

		$social_repeater->end_controls_tab();

		$social_repeater->start_controls_tab(
			'social_icon_hover',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);

		$social_repeater->add_responsive_control(
			'social_icon_icon_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3b5998',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} > a:hover'          => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} > a:hover svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$social_repeater->add_responsive_control(
			'social_icon_icon_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} > a:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$social_repeater->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'social_icon_border_hover',
				'label'    => esc_html__( 'Border', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} > a:hover',
			)
		);

		$social_repeater->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'socialicon_list_box_shadow_hover',
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} > a:hover',
			)
		);

		$social_repeater->end_controls_tab();
		//end hover tab

		$social_repeater->end_controls_tabs();

		$this->add_control(
			'social_icon_add_icons',
			array(
				'label'       => esc_html__( 'Add Social Media', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $social_repeater->get_controls(),
				'default'     => $this->thim_ekit_social_value_default(),
				'title_field' => '{{{ social_icon_label }}}',
			)
		);

	}

	protected function register_style_controls() {
		// start Social media tab
		$this->start_controls_section(
			'social_icon_section_tab_style',
			array(
				'label' => esc_html__( 'Social Media', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'socialicon_list_display',
			array(
				'label'     => esc_html__( 'Display', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'block'        => array(
						'title' => esc_html__( 'Default', 'thim-elementor-kit' ),
						'icon'  => 'eicon-editor-list-ul',
					),
					'inline-block' => array(
						'title' => esc_html__( 'Inline', 'thim-elementor-kit' ),
						'icon'  => 'eicon-ellipsis-h',
					),
				),
				'default'   => 'inline-block',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .thim-social-media > li'   => 'display: {{VALUE}};',
					'{{WRAPPER}} .thim-social-media > li a' => 'display: inline-block; text-align:center;',
				),
				'condition' => array(
					'social_icon_style!' => 'toggle',
				),
			)
		);

		$this->add_responsive_control(
			'socialicon_list_decoration_box',
			array(
				'label'     => esc_html__( 'Decoration', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'none'         => esc_html__( 'None', 'thim-elementor-kit' ),
					'underline'    => esc_html__( 'Underline', 'thim-elementor-kit' ),
					'overline'     => esc_html__( 'Overline', 'thim-elementor-kit' ),
					'line-through' => esc_html__( 'Line Through', 'thim-elementor-kit' ),

				),
				'condition' => array(
					'social_icon_style' => array( 'text', 'both' ),
				),
				'selectors' => array( '{{WRAPPER}} .thim-social-media > li > a' => 'text-decoration: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'socialicon_list_border_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'    => '50',
					'right'  => '50',
					'bottom' => '50',
					'left'   => '50',
					'unit'   => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-social-media > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'socialicon_list_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-social-media > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'socialicon_list_margin',
			array(
				'label'      => esc_html__( 'Margin', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'    => '5',
					'right'  => '5',
					'bottom' => '5',
					'left'   => '5',
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-social-media > li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'socialicon_list_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 5,
					),
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-social-media > li > a i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .thim-social-media > li > a svg' => 'max-width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'social_icon_style!' => 'text',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'socialicon_list_typography',
				'label'     => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector'  => '{{WRAPPER}} .thim-social-media > li > a', 
				'condition' => array(
					'social_icon_style!' => ['icon','toggle'],
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'socialicon_toggle_list_typography_',
				'label'     => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector'  => '{{WRAPPER}} .text-label', 
				'condition' => array(
					'social_icon_style' => 'toggle',
				),
			)
		);

		$this->add_control(
			'socialicon_list_style_use_height_and_width',
			array(
				'label'        => esc_html__( 'Use Height Width', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'thim-elementor-kit' ),
				'label_off'    => esc_html__( 'Hide', 'thim-elementor-kit' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_responsive_control(
			'ekit_socialmedai_list_width',
			array(
				'label'      => esc_html__( 'Width', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 30,
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-social-media > li > a' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'socialicon_list_style_use_height_and_width' => 'yes',
					'social_icon_style'                          => ['icon','toggle'],
				),
			)
		);

		$this->add_responsive_control(
			'ekit_socialmedai_list_height',
			array(
				'label'      => esc_html__( 'Height', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 30,
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-social-media > li > a' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'socialicon_list_style_use_height_and_width' => 'yes',
					'social_icon_style'                          => ['icon','toggle'],
				),
			)
		);

		$this->add_responsive_control(
			'socialicon_list_line_height',
			array(
				'label'      => esc_html__( 'Line Height', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 30,
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-social-media > li > a'   => 'line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .thim-social-media > li > a i' => 'line-height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'socialicon_list_style_use_height_and_width' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function social_icon_link_type( $social_repeater ) {
		$social_repeater->add_control(
			'social_icon_link',
			array(
				'label'   => esc_html__( 'Link', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::URL,
				'default' => array(
					'url' => 'https://facebook.com',
				),
			)
		);
	}

	function thim_ekit_social_value_default() {
		return array(
			array(
				'social_icon_icons'            => array(
					'value'   => 'fab fa-facebook-f',
					'library' => 'Font Awesome 5 Brands',
				),
				'social_icon_label'            => 'Facebook',
				'social_icon_icon_hover_color' => '#3b5998',
			),
			array(
				'social_icon_icons'            => array(
					'value'   => 'fab fa-twitter',
					'library' => 'Font Awesome 5 Brands',
				),
				'social_icon_label'            => 'Twitter',
				'social_icon_icon_hover_color' => '#1da1f2',
			),
			array(
				'social_icon_icons'            => array(
					'value'   => 'fab fa-linkedin-in',
					'library' => 'Font Awesome 5 Brands',
				),
				'social_icon_label'            => 'LinkedIn',
				'social_icon_icon_hover_color' => '#0077b5',
			),
		);
	}

	protected function render() {
		?>
		<div class="social-swapper">
			<?php $this->render_raw(); ?>
		</div>
		<?php
	}

	protected function render_raw() {
		$settings = $this->get_settings();
		?>

		<ul class="thim-social-media">
			<?php foreach ( $settings['social_icon_add_icons'] as $key => $icon ) : ?>
				<?php
				if ( $icon['social_icon_icons'] != '' ) :
					if ( ! empty( $icon['social_icon_link']['url'] ) ) {
						$this->add_link_attributes( 'button-' . esc_attr( $key ), $icon['social_icon_link'] );
					}
					?>
					<li class="elementor-repeater-item-<?php echo esc_attr( $icon['_id'] ); ?>">
						<a <?php $this->print_render_attribute_string( 'button-' . esc_attr( $key ) ); ?>>
							<?php if ( $settings['social_icon_style'] != 'text' && $settings['social_icon_style_icon_position'] == 'before' ) : ?>
								<?php Icons_Manager::render_icon( $icon['social_icon_icons'], array( 'aria-hidden' => 'true' ) ); ?>
							<?php endif; ?>

							<?php if ( $settings['social_icon_style'] != 'icon' ) : ?>
								<?php echo esc_html( $icon['social_icon_label'] ); ?>
							<?php endif; ?>

							<?php if ( $settings['social_icon_style'] != 'text' && $settings['social_icon_style_icon_position'] == 'after' ) : ?>
								<?php Icons_Manager::render_icon( $icon['social_icon_icons'], array( 'aria-hidden' => 'true' ) ); ?>
							<?php endif; ?>
						</a>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
		<?php
	}
}
