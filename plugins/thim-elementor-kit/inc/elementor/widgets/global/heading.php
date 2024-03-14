<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Ekit_Widget_Heading extends Widget_Base {

	public function get_name() {
		return 'thim-ekits-heading';
	}

	public function get_title() {
		return esc_html__( 'Heading', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-t-letter';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY );
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_tabs',
			[
				'label' => __( 'Title', 'thim-elementor-kit' )
			]
		);

		$this->add_control(
			'title', [
				'label'       => esc_html__( 'Title', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Add your {{text here}}', 'thim-elementor-kit' ),
				'default'     => esc_html__( 'Add your {{text here}}', 'thim-elementor-kit' ),
				'label_block' => true,
				'description' => esc_html__( '"Focused Title" Settings will be worked, If you use this {{something}} format', 'thim-elementor-kit' ),
			]
		);

		$this->add_control(
			'size',
			[
				'label'   => esc_html__( 'HTML Tag', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default' => 'h3',
			]
		);

		$this->add_control(
			'show_title_border',
			[
				'label'   => esc_html__( 'Show Border', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->add_control(
			'title_border_position',
			[
				'label'     => esc_html__( 'Border Position', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'start',
				'options'   => [
					'left'  => esc_html__( 'Start', 'thim-elementor-kit' ),
					'right' => esc_html__( 'End', 'thim-elementor-kit' ),
				],
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-heading .title::before' => '{{VALUE}}:0;',
				],
				'condition' => [
					'show_title_border' => 'yes',
				]
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_subtitle',
			array(
				'label' => esc_html__( 'Subtitle', 'thim-elementor-kit' ),
			)
		);
		$this->add_control(
			'sub_heading',
			[
				'label'       => esc_html__( 'Sub Title', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Add your text here', 'thim-elementor-kit' ),
				'label_block' => true
			]
		);
		$this->add_control(
			'sub_tag',
			[
				'label'   => esc_html__( 'HTML Tag', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default' => 'h5',
			]
		);
		$this->add_control(
			'sub_position',
			[
				'label'   => esc_html__( 'Sub Title Position', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'before_title' => esc_html__( 'Before Title', 'thim-elementor-kit' ),
					'after_title'  => esc_html__( 'After Title', 'thim-elementor-kit' )
				],
				'default' => 'after_title',
			]
		);
		$this->add_control(
			'sub_title_tag',
			[
				'label'     => esc_html__( 'Sub Title HTML Tag', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default'   => 'h3',
				'condition' => [
					'sub_title_show' => 'yes'
				]
			]
		);
		$this->end_controls_section();

		//Title Description
		$this->start_controls_section(
			'section_desc',
			array(
				'label' => esc_html__( 'Description', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'show_desc',
			[
				'label'   => esc_html__( 'Show Description', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->add_control(
			'desc',
			[
				'label'       => esc_html__( 'Description', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::WYSIWYG,
				'dynamic'     => [
					'active' => true,
				],
				'rows'        => 10,
				'label_block' => true,
				'default'     => esc_html__( 'A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradise ', 'thim-elementor-kit' ),
				'placeholder' => esc_html__( 'Description', 'thim-elementor-kit' ),
				'condition'   => [
					'show_desc' => 'yes'
				],
			]
		);

		$this->add_responsive_control(
			'desciption_width',
			[
				'label'      => __( 'Maximum Width', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .desc' => 'max-width: {{SIZE}}{{UNIT}};'
				],
				'condition'  => [
					'show_desc' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_seperator',
			array(
				'label' => esc_html__( 'Separator', 'thim-elementor-kit' ),
			)
		);


		$this->add_control(
			'show_seperator', [
				'label'     => esc_html__( 'Show Separator', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => esc_html__( 'Yes', 'thim-elementor-kit' ),
				'label_off' => esc_html__( 'No', 'thim-elementor-kit' ),
			]
		);

		$this->add_control(
			'seperator_style',
			[
				'label'     => esc_html__( 'Separator Style', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'seperator-dashed' => esc_html__( 'Solid with dashed', 'thim-elementor-kit' ),
					'seperator-solid'  => esc_html__( 'Solid', 'thim-elementor-kit' ),
					'seperator-star'   => esc_html__( 'Solid with star', 'thim-elementor-kit' ),
					'seperator-bullet' => esc_html__( 'Solid with bullet', 'thim-elementor-kit' ),
					'seperator_custom' => esc_html__( 'Custom', 'thim-elementor-kit' ),
				],
				'default'   => 'seperator-dotted',
				'condition' => [
					'show_seperator' => 'yes',
				],
			]
		);

		$this->add_control(
			'seperator_position',
			[
				'label'     => esc_html__( 'Separator Position', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'top'    => esc_html__( 'Top', 'thim-elementor-kit' ),
					'before' => esc_html__( 'Before Title', 'thim-elementor-kit' ),
					'after'  => esc_html__( 'After Title', 'thim-elementor-kit' ),
					'bottom' => esc_html__( 'Bottom', 'thim-elementor-kit' ),
				],
				'default'   => 'after',
				'condition' => [
					'show_seperator' => 'yes',
				],
			]
		);

		$this->add_control(
			'seperator_icons',
			[
				'label'       => esc_html__( 'Select Icon', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => false,
				'skin'        => 'inline',
				'condition'   => [
					'show_seperator'  => 'yes',
					'seperator_style' => 'seperator_custom',
				],

			]
		);
		$this->add_responsive_control(
			'seperator_icon_size',
			[
				'label'     => esc_html__( 'Icon Size', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
				],
				'condition' => [
					'show_seperator'  => 'yes',
					'seperator_style' => 'seperator_custom',
				],
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-heading .seperator i'   => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .thim-ekits-heading .seperator svg' => 'width: {{SIZE}}px;',
				],
			]
		);
		$this->end_controls_section();

		$this->register_section_style_general();

		$this->register_section_style_title();

		$this->register_section_style_sub_title();

		$this->register_section_style_desc();

		$this->register_section_style_focused_title();

		$this->register_section_style_separator();
	}

	protected function register_section_style_general() {
		$this->start_controls_section(
			'ekit_heading_section_general',
			array(
				'label' => esc_html__( 'General', 'thim-elementor-kit' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'horizon_align',
			array(
				'label'     => esc_html__( 'Horizontal Align', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => ' eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'End', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'   => 'flex-start',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-heading' => 'align-items: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'text_align',
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
					'{{WRAPPER}} .thim-ekits-heading' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_section_style_title() {
		$this->start_controls_section(
			'heading_settings',
			[
				'label' => esc_html__( 'Title', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'heading_margin',
			[
				'label'      => esc_html__( 'Margin', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .thim-ekits-heading .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_typography',
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .thim-ekits-heading .title',
			]
		);
		$this->add_control(
			'textcolor',
			[
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-heading .title' => 'color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'title_border_heading', [
				'label'     => esc_html__( 'Border', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_title_border' => 'yes'
				]
			]
		);

		$this->add_control(
			'title_border_w', [
				'label'      => __( 'Border Width', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 32,
						'step' => 1,
					]
				],
				'default'    => [ 'unit' => 'px', 'size' => 4 ],
				'selectors'  => [
					'{{WRAPPER}} .thim-ekits-heading .title::before' => 'width: {{SIZE}}{{UNIT}};'
				],
				'condition'  => [
					'show_title_border' => 'yes'
				]
			]
		);
		$this->add_control(
			'border_title_color',
			[
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}  .thim-ekits-heading .title::before' => 'background-color: {{VALUE}};'
				],
				'condition' => [
					'show_title_border' => 'yes'
				]
			]
		);
		$this->add_responsive_control(
			'border_title_margin',
			[
				'label'      => esc_html__( 'Border Title Margin', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .thim-ekits-heading .has_border-title::before' => 'top:{{TOP}}{{UNIT}};bottom: {{BOTTOM}}{{UNIT}} ;',
					'{{WRAPPER}} .thim-ekits-heading .has_border-title'         => 'padding-left: {{LEFT}}{{UNIT}};padding-right: {{RIGHT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function register_section_style_separator() {
		$this->start_controls_section(
			'separator_settings',
			[
				'label'     => esc_html__( 'Separator', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_seperator' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'w_separator',
			[
				'label'     => esc_html__( 'Width (px)', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-heading .seperator' => 'width: {{SIZE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'h_separator',
			[
				'label'     => esc_html__( 'Height (px)', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-heading .seperator' => 'height: {{SIZE}}px;',
				],
			]
		);
		$this->add_control(
			'bg_line',
			[
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .seperator' => '--thim-heading-seperator-bg-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'mg_separator',
			[
				'label'      => esc_html__( 'Margin', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .thim-ekits-heading .seperator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_section_style_sub_title() {
		$this->start_controls_section(
			'sub_title_settings',
			[
				'label' => esc_html__( 'Sub Title', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sub_title_margin',
			[
				'label'      => esc_html__( 'Margin', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .thim-ekits-heading .sub-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sub_typography',
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .thim-ekits-heading .sub-heading',
			]
		);
		$this->add_control(
			'sub_heading_color',
			[
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-heading .sub-heading' => 'color: {{VALUE}};',
				]
			]
		);
		$this->end_controls_section();
	}

	protected function register_section_style_desc() {
		$this->start_controls_section(
			'desc_settings',
			[
				'label'     => esc_html__( 'Description', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_desc' => 'yes'
				]
			]
		);
		$this->add_responsive_control(
			'desc_margin',
			[
				'label'      => esc_html__( 'Margin', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .thim-ekits-heading .desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'desc_typography',
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .thim-ekits-heading .desc',
			]
		);
		$this->add_control(
			'desc__color',
			[
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-heading .desc *,{{WRAPPER}} .thim-ekits-heading .desc' => 'color: {{VALUE}};',
				]
			]
		);
		$this->end_controls_section();
	}

	protected function register_section_style_focused_title() {
		$this->start_controls_section(
			'focused_style',
			[
				'label' => esc_html__( 'Focused Title', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'focused_color',
			[
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-heading .title > span' => 'color: {{VALUE}};',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'focused_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-heading .title > span',
			]
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo '<div class="thim-ekits-heading thim-ekit__heading">';
		$this->render_title( $settings );
		echo '</div>';
	}

	protected function render_title( $settings, $open_url = '', $close_url = '' ) {
		$seperator   = '';
		$class_title = 'title';
		$class_title .= $settings['show_title_border'] == 'yes' ? ' has_border-title' : '';
		if ( $settings['show_seperator'] ) {
			$seperator .= '<span class="seperator ' . esc_attr( $settings['seperator_style'] ) . '">';
			if ( ! empty( $settings['seperator_icons'] ) ) {
				ob_start();
				Icons_Manager::render_icon( $settings['seperator_icons'], [ 'aria-hidden' => 'true' ] );
				$seperator .= ob_get_clean();
			}
			$seperator .= '</span>';
		}
		//seperator
		if ( $settings ['seperator_position'] == 'top' ) {
			print_r( $seperator );
		}

		if ( $settings['sub_position'] == 'before_title' && isset( $settings['sub_heading'] ) && $settings['sub_heading'] <> '' ) {
			echo '<' . $settings['sub_tag'] . ' class="sub-heading">' . wp_kses_post( $settings['sub_heading'] ) . '</' .
				$settings['sub_tag'] . '>';
		}

		if ( isset( $settings['title'] ) && $settings['title'] ) {
			//seperator
			if ( $settings ['seperator_position'] == 'before' ) {
				print_r( $seperator );
			}

			echo '<' . $settings['size'] . ' class="' . esc_attr( $class_title ) . '">' . $open_url . str_replace(
					array( '{{', '}}' ), array( '<span>', '</span>' ), $settings['title'] ) . $close_url . '</' .
				$settings['size']
				. '>';
			//seperator
			if ( $settings ['seperator_position'] == 'after' ) {
				print_r( $seperator );
			}
		}
		if ( $settings['sub_position'] == 'after_title' && isset( $settings['sub_heading'] ) && $settings['sub_heading'] <> '' ) {
			echo '<' . $settings['sub_tag'] . ' class="sub-heading">' . wp_kses_post( $settings['sub_heading'] ) . '</' . $settings['sub_tag'] . '>';
		}

		if ( ( ! empty( $settings['desc'] ) ) && ( $settings['show_desc'] == 'yes' ) ):
			?>
			<div class='desc'>
				<?php echo wp_kses_post( $settings['desc'] ); ?>
			</div>
		<?php endif;
		//seperator
		if ( $settings ['seperator_position'] == 'bottom' ) {
			print_r( $seperator );
		}
	}
}
