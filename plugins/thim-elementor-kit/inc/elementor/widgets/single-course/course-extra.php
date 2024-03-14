<?php
namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Course_Extra extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-course-extra';
	}

	public function get_title() {
		return esc_html__( ' Course Extra', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-accordion';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_SINGLE_COURSE );
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

		$repeater = new Repeater();

		$repeater->add_control(
			'type',
			array(
				'label'   => esc_html__( 'Type', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'requirements',
				'options' => array(
					'requirements'     => esc_html__( 'Requirements', 'thim-elementor-kit' ),
					'key_features'     => esc_html__( 'Features', 'thim-elementor-kit' ),
					'target_audiences' => esc_html__( 'Target audiences', 'thim-elementor-kit' ),
					'custom'           => esc_html__( 'Custom', 'thim-elementor-kit' ),
				),
			)
		);

		$repeater->add_control(
			'tab_title',
			array(
				'label' => esc_html__( 'Title', 'thim-elementor-kit' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'tab_content',
			array(
				'label'      => esc_html__( 'Content', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => esc_html__( 'Extra Content', 'thim-elementor-kit' ),
				'show_label' => false,
				'condition'  => array(
					'type' => 'custom',
				),
			)
		);

		$this->add_control(
			'tab_list',
			array(
				'label'       => esc_html__( 'Extra Items', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'type' => 'requirements' ),
					array( 'type' => 'key_features' ),
					array( 'type' => 'target_audiences' ),
				),
				'title_field' => '<span style="text-transform: capitalize;">{{{ type.replace("_", " ") }}}</span>',
			)
		);

		$this->add_control(
			'selected_icon',
			array(
				'label'       => esc_html__( 'Icon', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'separator'   => 'before',
				'default'     => array(
					'value'   => 'fas fa-plus',
					'library' => 'fa-solid',
				),
				'recommended' => array(
					'fa-solid'   => array(
						'chevron-down',
						'angle-down',
						'angle-double-down',
						'caret-down',
						'caret-square-down',
					),
					'fa-regular' => array(
						'caret-square-down',
					),
				),
				'skin'        => 'inline',
				'label_block' => false,
			)
		);

		$this->add_control(
			'selected_active_icon',
			array(
				'label'       => esc_html__( 'Active Icon', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'fas fa-minus',
					'library' => 'fa-solid',
				),
				'recommended' => array(
					'fa-solid'   => array(
						'chevron-up',
						'angle-up',
						'angle-double-up',
						'caret-up',
						'caret-square-up',
					),
					'fa-regular' => array(
						'caret-square-up',
					),
				),
				'skin'        => 'inline',
				'label_block' => false,
				'condition'   => array(
					'selected_icon[value]!' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	public function register_style_controls() {
		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => esc_html__( 'Item', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'border_width',
			array(
				'label'     => esc_html__( 'Border Width', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__extra__item' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .thim-ekit-single-course__extra__content' => 'border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__extra__item' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .thim-ekit-single-course__extra__content' => 'border-top-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style_title',
			array(
				'label' => esc_html__( 'Title', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_background',
			array(
				'label'     => esc_html__( 'Background', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} summary' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__extra__icon, {{WRAPPER}} summary' => 'color: {{VALUE}};',
					'{{WRAPPER}} .thim-ekit-single-course__extra__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_active_color',
			array(
				'label'     => esc_html__( 'Active Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__extra__item[open] .thim-ekit-single-course__extra__icon, {{WRAPPER}} .thim-ekit-single-course__extra__item[open] summary' => 'color: {{VALUE}};',
					'{{WRAPPER}} .thim-ekit-single-course__extra__item[open] .thim-ekit-single-course__extra__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} summary',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'title_shadow',
				'selector' => '{{WRAPPER}} summary',
			)
		);

		$this->add_responsive_control(
			'title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} summary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style_icon',
			array(
				'label'     => esc_html__( 'Icon', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'selected_icon[value]!' => '',
				),
			)
		);

		$this->add_control(
			'icon_align',
			array(
				'label'        => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'  => array(
						'title' => esc_html__( 'Start', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => esc_html__( 'End', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'      => 'left',
				'toggle'       => false,
				'prefix_class' => 'thim-ekit-single-course__extra__icon--',
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__extra__icon i:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .thim-ekit-single-course__extra__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_active_color',
			array(
				'label'     => esc_html__( 'Active Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__extra__item[open] .thim-ekit-single-course__extra__icon i:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .thim-ekit-single-course__extra__item[open] .thim-ekit-single-course__extra__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_space',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} summary' => 'column-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style_content',
			array(
				'label' => esc_html__( 'Content', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_background_color',
			array(
				'label'     => esc_html__( 'Background', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__extra__content' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'content_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__extra__content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-course__extra__content',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'content_shadow',
				'selector' => '{{WRAPPER}} .thim-ekit-single-course__extra__content',
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekit-single-course__extra__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	public function render() {
		do_action( 'thim-ekit/modules/single-course/before-preview-query' );

		$course = learn_press_get_course();

		if ( ! $course ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		?>

		<div class="thim-ekit-single-course__extra">
			<?php
			if ( ! empty( $settings['tab_list'] ) ) {
				foreach ( $settings['tab_list'] as $tab ) {
					$this->render_course_extra( $tab, $settings, $course );
				}
			}
			?>
		</div>

		<?php
		do_action( 'thim-ekit/modules/single-course/after-preview-query' );
	}

	protected function render_course_extra( $tab, $settings, $course ) {
		$items = $course->get_extra_info( $tab['type'] );
		$label = array(
			'requirements'     => esc_html__( 'Requirements', 'thim-elementor-kit' ),
			'key_features'     => esc_html__( 'Features', 'thim-elementor-kit' ),
			'target_audiences' => esc_html__( 'Target Audiences', 'thim-elementor-kit' ),
			'custom'           => esc_html__( 'Extra Title', 'thim-elementor-kit' ),
		);

		$title = ! empty( $tab['tab_title'] ) ? $tab['tab_title'] : $label[ $tab['type'] ];
		?>

		<div class="thim-ekit-single-course__extra__items">
			<details class="thim-ekit-single-course__extra__item">
				<summary>
					<?php if ( ! empty( $settings['selected_icon']['value'] ) ) : ?>
						<span class="thim-ekit-single-course__extra__icon thim-ekit-single-course__extra__icon-closed"><?php Icons_Manager::render_icon( $settings['selected_icon'] ); ?></span>
					<?php endif; ?>

					<?php if ( ! empty( $settings['selected_active_icon']['value'] ) ) : ?>
						<span class="thim-ekit-single-course__extra__icon thim-ekit-single-course__extra__icon-opened"><?php Icons_Manager::render_icon( $settings['selected_active_icon'] ); ?></span>
					<?php endif; ?>

					<span class="thim-ekit-single-course__extra__title"><?php echo esc_html( $title ); ?></span>
				</summary>

				<div class="thim-ekit-single-course__extra__content">
					<?php if ( $tab['type'] !== 'custom' ) : ?>
						<ul>
							<?php foreach ( $items as $item ) : ?>
								<li><?php echo esc_html( $item ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php else : ?>
						<?php $this->print_text_editor( $tab['tab_content'] ); ?>
					<?php endif; ?>
				</div>
			</details>
		</div>

		<?php
	}
}
