<?php
namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;

class Thim_Ekit_Widget_Course_Item_Search_Form extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-course-item-search-form';
	}

	public function get_title() {
		return esc_html__( 'Course Search Form', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-search';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_SINGLE_COURSE_ITEM );
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'placeholder',
			[
				'label' => esc_html__( 'Placeholder', 'thim-elementor-kit' ),
				'type' => Controls_Manager::TEXT,
				'separator' => 'before',
				'default' => esc_html__( 'Search ...', 'thim-elementor-kit' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'heading_button_content',
			[
				'label' => esc_html__( 'Button', 'thim-elementor-kit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_type',
			[
				'label' => esc_html__( 'Type', 'thim-elementor-kit' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'icon' => esc_html__( 'Icon', 'thim-elementor-kit' ),
					'text' => esc_html__( 'Text', 'thim-elementor-kit' ),
				],
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Text', 'thim-elementor-kit' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Search', 'thim-elementor-kit' ),
				'condition' => [
					'button_type' => 'text',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'fas fa-search',
					'library' => 'fa-solid',
				),
				'skin'        => 'inline',
				'label_block' => false,
				'condition' => [
					'button_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label' => esc_html__( 'Position', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default'     => 'out',
				'options' => [
					'in' => 'Inside',
					'out' => 'Outside',
				],
				'condition' => [
					'button_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'size',
			[
				'label' => esc_html__( 'Size', 'thim-elementor-kit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default' => [
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .search-course' => 'min-height: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important',
					'{{WRAPPER}} [type=submit]' => 'min-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} [type=submit]' => 'padding-left: calc({{SIZE}}{{UNIT}} / 3); padding-right: calc({{SIZE}}{{UNIT}} / 3)',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toggle_align',
			[
				'label' => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} form' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_input_style',
			array(
				'label' => esc_html__( 'Input', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'input_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-course-item__search-form__input',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->start_controls_tabs( 'tabs_input_colors' );

		$this->start_controls_tab(
			'tab_input_normal',
			[
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			]
		);

		$this->add_control(
			'input_text_color',
			[
				'label' => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .thim-ekit-single-course-item__search-form__input' => 'color: {{VALUE}} !important; fill: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'input_background_color',
			[
				'label' => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekit-single-course-item__search-form__input' => 'background-color: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'input_border_color',
			[
				'label' => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekit-single-course-item__search-form__input' => 'border-color: {{VALUE}} !important',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_focus',
			[
				'label' => esc_html__( 'Focus', 'thim-elementor-kit' ),
			]
		);

		$this->add_control(
			'input_text_color_focus',
			[
				'label' => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekit-single-course-item__search-form__input:focus' => 'color: {{VALUE}} !important; fill: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'input_background_color_focus',
			[
				'label' => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekit-single-course-item__search-form__input:focus' => 'background-color: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'input_border_color_focus',
			[
				'label' => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekit-single-course-item__search-form__input:focus' => 'border-color: {{VALUE}} !important',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'thim-elementor-kit' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .thim-ekit-single-course-item__search-form__input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_padding',
			[
				'label' => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .thim-ekit-single-course-item__search-form__input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .thim-ekit-single-course-item__search-form__input' => 'border-radius: {{SIZE}}{{UNIT}} !important',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Button', 'thim-elementor-kit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_align',
			[
				'label' => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'render_type' => 'ui',
				'condition' => [
					'icon_position' => 'in',
				]
			]
		);

		$this->add_responsive_control(
			'button_offset_x',
			array(
				'label'       => esc_html__( 'Offset', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => -100,
				'step'        => 1,
				'default'     => 5,
				'selectors'   => array(
					'{{WRAPPER}} .thim-ekit-single-course-item__search-form.button-inside form button' => '{{button_align.VALUE}}:{{VALUE}}px',
				),
				'condition' => [
					'icon_position' => 'in',
				]
			)
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-course-item__search-form__submit',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'condition' => [
					'button_type' => 'text',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_colors' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekit-single-course-item__search-form__submit' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .thim-ekit-single-course-item__search-form__submit' => 'background-color: {{VALUE}} !important',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekit-single-course-item__search-form__submit:hover' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .thim-ekit-single-course-item__search-form__submit:focus' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'button_background_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekit-single-course-item__search-form__submit:hover' => 'background-color: {{VALUE}} !important',
					'{{WRAPPER}} .thim-ekit-single-course-item__search-form__submit:focus' => 'background-color: {{VALUE}} !important',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'thim-elementor-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .thim-ekit-single-course-item__search-form__submit' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'button_type' => 'icon',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label' => esc_html__( 'Width', 'thim-elementor-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .thim-ekit-single-course-item__search-form__submit' => 'min-width: calc( {{SIZE}} * {{size.SIZE}}{{size.UNIT}} )',
				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		do_action( 'thim-ekit/modules/single-course-item/before-preview-query' );

		$settings = $this->get_settings_for_display();

		$class = '';

		if ( !empty($settings['icon_position']) && 'in' === $settings['icon_position']) {
			$class = 'button-inside';
		}

		// TODO: khong the xoa "search-course" vi js su dung class nay.
		?>

		<div class="thim-ekit-single-course-item__search-form <?php echo esc_attr($class) ?>">
			<form method="post" class="search-course">
				<input class="thim-ekit-single-course-item__search-form__input" type="text" name="s" placeholder="<?php echo esc_attr( $settings['placeholder'] ); ?>">
				<button class="thim-ekit-single-course-item__search-form__submit" type="submit" aria-label="<?php esc_attr_e( 'Search', 'thim-elementor-kit' ); ?>">
					<?php if ( 'icon' === $settings['button_type'] ) : ?>
						<?php $this->render_search_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
						<span class="elementor-screen-only"><?php esc_html_e( 'Search', 'thim-elementor-kit' ); ?></span>
					<?php elseif ( ! empty( $settings['button_text'] ) ) : ?>
						<?php $this->print_unescaped_setting( 'button_text' ); ?>
					<?php endif; ?>
				</button>
			</form>
		</div>

		<?php
		do_action( 'thim-ekit/modules/single-course-item/after-preview-query' );
	}

	private function render_search_icon( $icon, $attributes = [] ) {
		if (  Plugin::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) ) {
			$icon_html = Icons_Manager::render_font_icon( $icon, $attributes );

			Utils::print_unescaped_internal_string( sprintf( '<div class="e-font-icon-svg-container">%s</div>', $icon_html ) );
		} else {
			$migration_allowed = Icons_Manager::is_migration_allowed();

			if ( ! $migration_allowed || ! Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ) ) {
				Utils::print_unescaped_internal_string( sprintf( '<i %s aria-hidden="true"></i>', esc_attr( $this->get_render_attribute_string( 'icon' ) ) ) );
			}
		}
	}
}
