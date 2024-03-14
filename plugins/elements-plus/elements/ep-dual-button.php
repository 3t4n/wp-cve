<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor dual button widget based on the original Elementor button widget.
 *
 * Elementor widget that displays a button with the ability to control every
 * aspect of the button design.
 *
 * @since 1.0.0
 */
class Widget_Dual_Button_Plus extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve button widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'dual-button';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve button widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Dual Button Plus!', 'elements-plus' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve button widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'ep-icon ep-icon-dual_button';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the button widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'elements-plus' ];
	}

	/**
	 * Get button sizes.
	 *
	 * Retrieve an array of button sizes for the button widget.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return array An array containing button sizes.
	 */
	public static function get_button_sizes() {
		return [
			'xs' => __( 'Extra Small', 'elements-plus' ),
			'sm' => __( 'Small', 'elements-plus' ),
			'md' => __( 'Medium', 'elements-plus' ),
			'lg' => __( 'Large', 'elements-plus' ),
			'xl' => __( 'Extra Large', 'elements-plus' ),
		];
	}

	/**
	 * Register button widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_button_1',
			[
				'label' => __( 'Button', 'elements-plus' ),
			]
		);

		$this->add_control(
			'button_1_text',
			[
				'label' => __( 'Text', 'elements-plus' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Click here', 'elements-plus' ),
				'placeholder' => __( 'Click here', 'elements-plus' ),
			]
		);

		$this->add_control(
			'button_1_link',
			[
				'label' => __( 'Link', 'elements-plus' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'elements-plus' ),
				'default' => [
					'url' => '#',
				],
			]
		);

		$this->add_responsive_control(
			'button_1_align',
			[
				'label' => __( 'Alignment', 'elements-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'elements-plus' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elements-plus' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elements-plus' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elements-plus' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
			]
		);

		$this->add_control(
			'button_1_size',
			[
				'label' => __( 'Size', 'elements-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_button_sizes(),
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'button_1_icon_fa5',
			[
				'label'            => __( 'Icon', 'elements-plus' ),
				'type'             => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'button_1_icon',
				'label_block'      => true,
				'default'          => array(),
			]
		);

		$this->add_control(
			'button_1_icon_align',
			[
				'label' => __( 'Icon Position', 'elements-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __( 'Before', 'elements-plus' ),
					'right' => __( 'After', 'elements-plus' ),
				],
				'condition' => [
					'button_1_icon!' => '',
				],
			]
		);

		$this->add_control(
			'button_1_icon_indent',
			[
				'label' => __( 'Icon Spacing', 'elements-plus' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'button_1_icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button_1 .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button_1 .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_1_view',
			[
				'label' => __( 'View', 'elements-plus' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->add_control(
			'button_1_button_css_id',
			[
				'label' => __( 'Button ID', 'elements-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'elements-plus' ),
				'label_block' => false,
				'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'elements-plus' ),
				'separator' => 'before',

			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_2',
			[
				'label' => __( 'Button 2', 'elements-plus' ),
			]
		);

		$this->add_control(
			'button_2_text',
			[
				'label' => __( 'Text', 'elements-plus' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Click here', 'elements-plus' ),
				'placeholder' => __( 'Click here', 'elements-plus' ),
			]
		);

		$this->add_control(
			'button_2_link',
			[
				'label' => __( 'Link', 'elements-plus' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'elements-plus' ),
				'default' => [
					'url' => '#',
				],
			]
		);

		$this->add_control(
			'button_2_size',
			[
				'label' => __( 'Size', 'elements-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_button_sizes(),
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'button_2_icon_fa5',
			[
				'label'            => __( 'Icon', 'elements-plus' ),
				'type'             => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'button_2_icon',
				'label_block'      => true,
				'default'          => array(),
			]
		);

		$this->add_control(
			'button_2_icon_align',
			[
				'label' => __( 'Icon Position', 'elements-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __( 'Before', 'elements-plus' ),
					'right' => __( 'After', 'elements-plus' ),
				],
				'condition' => [
					'button_2_icon!' => '',
				],
			]
		);

		$this->add_control(
			'button_2_icon_indent',
			[
				'label' => __( 'Icon Spacing', 'elements-plus' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'button_2_icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button_2 .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button_2 .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_2_view',
			[
				'label' => __( 'View', 'elements-plus' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->add_control(
			'button_2_button_css_id',
			[
				'label' => __( 'Button ID', 'elements-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'elements-plus' ),
				'label_block' => false,
				'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'elements-plus' ),
				'separator' => 'before',

			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_layout',
			[
				'label' => __( 'Button Layout', 'elements-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'buttons_layout',
			[
				'label' => __( 'Button Layout', 'elements-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'vertical',
				'options' => [
					'vertical'  => __( 'Vertical', 'elements-plus' ),
					'horizontal' => __( 'Horizontal', 'elements-plus' ),
				],
			]
		);

		$this->add_responsive_control(
			'button_1_text_margin',
			[
				'label' => __( 'Margin', 'elements-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'allowed_dimensions' => [ 'right', 'bottom' ],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button_1, {{WRAPPER}} .elementor-button_1' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'default' => [
					'right' => '-3',
					'bottom' => '0',
					]
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'important_note',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Use the margin controls above to change the spacing between the buttons. Right margin affects the horizontal layout spacing and bottom margin the vertical one.', 'elements-plus' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Button 1', 'elements-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_1_typography',
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} a.elementor-button_1, {{WRAPPER}} .elementor-button_1',
			]
		);

		$this->start_controls_tabs( 'button_1_tabs_button_style' );

		$this->start_controls_tab(
			'button_1_tab_button_normal',
			[
				'label' => __( 'Normal', 'elements-plus' ),
			]
		);

		$this->add_control(
			'button_1_button_text_color',
			[
				'label' => __( 'Text Color', 'elements-plus' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a.elementor-button_1, {{WRAPPER}} .elementor-button_1' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_1_background_color',
			[
				'label' => __( 'Background Color', 'elements-plus' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button_1, {{WRAPPER}} .elementor-button_1' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_1_tab_button_hover',
			[
				'label' => __( 'Hover', 'elements-plus' ),
			]
		);

		$this->add_control(
			'button_1_hover_color',
			[
				'label' => __( 'Text Color', 'elements-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.elementor-button_1:hover, {{WRAPPER}} .elementor-button_1:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_1_button_background_hover_color',
			[
				'label' => __( 'Background Color', 'elements-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.elementor-button_1:hover, {{WRAPPER}} .elementor-button_1:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_1_button_hover_border_color',
			[
				'label' => __( 'Border Color', 'elements-plus' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button_1:hover, {{WRAPPER}} .elementor-button_1:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_1_hover_animation',
			[
				'label' => __( 'Hover Animation', 'elements-plus' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_1_border',
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .elementor-button_1',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_1_border_radius',
			[
				'label' => __( 'Border Radius', 'elements-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button_1, {{WRAPPER}} .elementor-button_1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_1_button_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-button_1',
			]
		);

		$this->add_responsive_control(
			'button_1_text_padding',
			[
				'label' => __( 'Padding', 'elements-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button_1, {{WRAPPER}} .elementor-button_1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_2',
			[
				'label' => __( 'Button 2', 'elements-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_2_typography',
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} a.elementor-button_2, {{WRAPPER}} .elementor-button_2',
			]
		);

		$this->start_controls_tabs( 'button_2_tabs_button_style' );

		$this->start_controls_tab(
			'button_2_tab_button_normal',
			[
				'label' => __( 'Normal', 'elements-plus' ),
			]
		);

		$this->add_control(
			'button_2_button_text_color',
			[
				'label' => __( 'Text Color', 'elements-plus' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a.elementor-button_2, {{WRAPPER}} .elementor-button_2' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_2_background_color',
			[
				'label' => __( 'Background Color', 'elements-plus' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button_2, {{WRAPPER}} .elementor-button_2' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_2_tab_button_hover',
			[
				'label' => __( 'Hover', 'elements-plus' ),
			]
		);

		$this->add_control(
			'button_2_hover_color',
			[
				'label' => __( 'Text Color', 'elements-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.elementor-button_2:hover, {{WRAPPER}} .elementor-button_2:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_2_button_background_hover_color',
			[
				'label' => __( 'Background Color', 'elements-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.elementor-button_2:hover, {{WRAPPER}} .elementor-button_2:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_2_button_hover_border_color',
			[
				'label' => __( 'Border Color', 'elements-plus' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button_2:hover, {{WRAPPER}} .elementor-button_2:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_2_hover_animation',
			[
				'label' => __( 'Hover Animation', 'elements-plus' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_2_border',
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .elementor-button_2',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_2_border_radius',
			[
				'label' => __( 'Border Radius', 'elements-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button_2, {{WRAPPER}} .elementor-button_2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_2_button_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-button_2',
			]
		);

		$this->add_responsive_control(
			'button_2_text_padding',
			[
				'label' => __( 'Padding', 'elements-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button_2, {{WRAPPER}} .elementor-button_2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );

		if ( ! empty( $settings['buttons_layout'] ) && 'horizontal' === $settings['buttons_layout'] ) {
			$this->add_render_attribute( 'container', 'class', 'ep-dual-button-horizontal' );
		}

		if ( ! empty( $settings['button_1_link']['url'] ) ) {
			$this->add_render_attribute( 'button_1', 'href', $settings['button_1_link']['url'] );
			$this->add_render_attribute( 'button_1', 'class', 'elementor-button-link' );

			if ( $settings['button_1_link']['is_external'] ) {
				$this->add_render_attribute( 'button_1', 'target', '_blank' );
			}

			if ( $settings['button_1_link']['nofollow'] ) {
				$this->add_render_attribute( 'button_1', 'rel', 'nofollow' );
			}
		}

		$this->add_render_attribute( 'button_1', 'class', 'elementor-button elementor-button_1' );
		$this->add_render_attribute( 'button_1', 'role', 'button_1' );

		if ( ! empty( $settings['button_1_button_css_id'] ) ) {
			$this->add_render_attribute( 'button_1', 'id', $settings['button_1_button_css_id'] );
		}

		if ( ! empty( $settings['button_1_size'] ) ) {
			$this->add_render_attribute( 'button_1', 'class', 'elementor-size-' . $settings['button_1_size'] );
		}

		if ( $settings['button_1_hover_animation'] ) {
			$this->add_render_attribute( 'button_1', 'class', 'elementor-animation-' . $settings['button_1_hover_animation'] );
		}

		if ( ! empty( $settings['button_2_link']['url'] ) ) {
			$this->add_render_attribute( 'button_2', 'href', $settings['button_2_link']['url'] );
			$this->add_render_attribute( 'button_2', 'class', 'elementor-button-link' );

			if ( $settings['button_2_link']['is_external'] ) {
				$this->add_render_attribute( 'button_2', 'target', '_blank' );
			}

			if ( $settings['button_2_link']['nofollow'] ) {
				$this->add_render_attribute( 'button_2', 'rel', 'nofollow' );
			}
		}

		$this->add_render_attribute( 'button_2', 'class', 'elementor-button elementor-button_2' );
		$this->add_render_attribute( 'button_2', 'role', 'button_2' );

		if ( ! empty( $settings['button_2_button_css_id'] ) ) {
			$this->add_render_attribute( 'button_2', 'id', $settings['button_2_button_css_id'] );
		}

		if ( ! empty( $settings['button_2_size'] ) ) {
			$this->add_render_attribute( 'button_2', 'class', 'elementor-size-' . $settings['button_2_size'] );
		}

		if ( $settings['button_2_hover_animation'] ) {
			$this->add_render_attribute( 'button_2', 'class', 'elementor-animation-' . $settings['button_2_hover_animation'] );
		}

		?>
		<div <?php echo $this->get_render_attribute_string( 'container' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<a <?php echo $this->get_render_attribute_string( 'button_1' ); ?>>
					<?php $this->render_text( 'button_1' ); ?>
				</a>
			</div>
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<a <?php echo $this->get_render_attribute_string( 'button_2' ); ?>>
					<?php $this->render_text( 'button_2' ); ?>
				</a>
			</div>
		</div>
		<?php
	}

	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @since 1.5.0
	 * @access protected
	 */
	protected function render_text( $button = 'button_1' ) {
		$settings = $this->get_settings_for_display();
		$migrated = isset( $settings['__fa4_migrated'][ $button . '_icon_fa5' ] );
		$is_new   = empty( $settings[ $button . '_icon' ] );

		$this->add_render_attribute( [
			'content-wrapper' => [
				'class' => 'elementor-button-content-wrapper',
			],
			'icon-align' => [
				'class' => [
					'elementor-button-icon',
					'elementor-align-icon-' . $settings[$button . '_icon_align'],
				],
			],
			'text' => [
				'class' => 'elementor-button-text',
			],
		] );

		$this->add_inline_editing_attributes( $button . '_text', 'none' );
		?>
		<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! $is_new || ! empty( $settings[$button . '_icon_fa5'] ) ) : ?>
			<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
				<?php
				if ( $is_new || $migrated ) {
					Icons_Manager::render_icon( $settings[ $button . '_icon_fa5' ], array( 'aria-hidden' => 'true' ) );
				} else {
					?>
					<i class="<?php echo $settings[ $button . '_icon' ]; ?>" aria-hidden="true"></i>
					<?php
				}
				?>
			</span>
			<?php endif; ?>
			<span <?php echo $this->get_render_attribute_string( $button . '_text' ); ?>><?php echo $settings[$button . '_text']; ?></span>
		</span>
		<?php
	}
}

add_action( 'elementor/widgets/register', function ( $widgets_manager ) {
	$widgets_manager->register( new Widget_Dual_Button_Plus() );
} );
