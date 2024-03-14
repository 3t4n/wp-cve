<?php

namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Course_Meta extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-course-meta';
	}

	public function get_title() {
		return esc_html__( ' Course Meta', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-post-info';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_SINGLE_COURSE );
	}

	public function get_help_url() {
		return '';
	}

	function register_field_course_meta() {
		$register_type = array(
			'last_update' => esc_html__( 'Last Updated', 'thim-elementor-kit' ),
			'duration'    => esc_html__( 'Duration', 'thim-elementor-kit' ),
			'level'       => esc_html__( 'Level', 'thim-elementor-kit' ),
			'lesson'      => esc_html__( 'Count Lesson', 'thim-elementor-kit' ),
			'quiz'        => esc_html__( 'Count Quiz', 'thim-elementor-kit' ),
			'student'     => esc_html__( 'Count Student Enrolled', 'thim-elementor-kit' ),
			'assessments' => esc_html__( 'Assessments', 'thim-elementor-kit' ),
		);

		if ( class_exists( '\LP_Addon_Certificates' ) ) {
			$register_type['certificates'] = esc_html__( 'Certificates', 'thim-elementor-kit' );
		}
		// fix for eduma
		if ( apply_filters( 'thim-ekit-course-key-language', '' ) ) {
			$register_type['language'] = esc_html__( 'Language', 'thim-elementor-kit' );
		}

		$register_type['custom'] = esc_html__( 'Custom', 'thim-elementor-kit' );

		return $register_type;
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
				'default' => 'duration',
				'options' => $this->register_field_course_meta(),
			)
		);
		$repeater->add_control(
			'icon',
			array(
				'label'       => esc_html__( 'Icon', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
			)
		);
		$repeater->add_control(
			'lifetime',
			array(
				'label'       => esc_html__( 'Lifetime', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Lifetime access', 'thim-elementor-kit' ),
				'description' => esc_html__( 'Show when duration is null', 'thim-elementor-kit' ),
				'condition'   => array(
					'type' => 'duration',
				),
			)
		);

		$repeater->add_control(
			'singular_lesson',
			array(
				'label'       => esc_html__( 'Singular', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'lesson', 'thim-elementor-kit' ),
				'condition'   => array(
					'type' => 'lesson',
				),
			)
		);

		$repeater->add_control(
			'plural_lesson',
			array(
				'label'       => esc_html__( 'Plural', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'lessons', 'thim-elementor-kit' ),
				'condition'   => array(
					'type' => 'lesson',
				),
			)
		);

		$repeater->add_control(
			'singular_quiz',
			array(
				'label'       => esc_html__( 'Singular', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'quiz', 'thim-elementor-kit' ),
				'condition'   => array(
					'type' => 'quiz',
				),
			)
		);

		$repeater->add_control(
			'plural_quiz',
			array(
				'label'       => esc_html__( 'Plural', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'quizzes', 'thim-elementor-kit' ),
				'condition'   => array(
					'type' => 'quiz',
				),
			)
		);

		$repeater->add_control(
			'singular_student',
			array(
				'label'       => esc_html__( 'Singular', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'student', 'thim-elementor-kit' ),
				'condition'   => array(
					'type' => 'student',
				),
			)
		);

		$repeater->add_control(
			'plural_student',
			array(
				'label'       => esc_html__( 'Plural', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'students', 'thim-elementor-kit' ),
				'condition'   => array(
					'type' => 'student',
				),
			)
		);

		$repeater->add_control(
			'custom_text',
			array(
				'label'       => esc_html__( 'Label', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'   => [
					'active' => true,
				],
				'condition'   => array(
					'type!' => [ 'lesson', 'quiz', 'student' ],
				),
			)
		);
		$repeater->add_control(
			'date_format',
			array(
				'label'       => esc_html__( 'Date Format', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'F d, Y',
				'label_block' => true,
				'condition'   => array(
					'type' => 'last_update',
				),
			)
		);

//		$repeater->add_control(
//			'custom_link',
//			array(
//				'label'     => esc_html__( 'Custom Link', 'thim-elementor-kit' ),
//				'type'      => Controls_Manager::SWITCHER,
//				'default'   => 'yes',
//				'condition' => array(
//					'type' => 'custom',
//				),
//			)
//		);

		$repeater->add_control(
			'custom_url',
			array(
				'label'     => esc_html__( 'Custom URL', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::URL,
				'dynamic'   => [
					'active' => true,
				],
				'condition' => array(
					'type'        => 'custom',
//					'custom_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'item_list',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'type' => 'duration',
					),
					array(
						'type' => 'level',
					),
					array(
						'type' => 'lesson',
					),
					array(
						'type' => 'quiz',
					),
				),
				'title_field' => '<span style="text-transform: capitalize;">{{{ type.replace("_", " ") }}} </span>',
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	protected function register_style_controls() {
		$this->start_controls_section(
			'section_style_image',
			array(
				'label' => esc_html__( 'General', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'display',
			array(
				'label'     => esc_html__( 'Display', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'column' => array(
						'title' => esc_html__( 'Block', 'thim-elementor-kit' ),
						'icon'  => 'eicon-editor-list-ul',
					),
					'row'    => array(
						'title' => esc_html__( 'Inline', 'thim-elementor-kit' ),
						'icon'  => 'eicon-ellipsis-h',
					),
				),
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__meta' => 'flex-direction: {{VALUE}} ',
				),
			)
		);
		$this->add_responsive_control(
			'space_between',
			array(
				'label'      => esc_html__( 'Space Between', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
				),
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekit-single-course__meta' => '--thim-ekit-single-course--meta: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_align',
			array(
				'label'     => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'End', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__meta' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_style',
			array(
				'label' => esc_html__( 'Text', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__meta'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .thim-ekit-single-course__meta *' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-course__meta',
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_icon_style',
			array(
				'label' => esc_html__( 'Icon', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__meta span i'        => 'color: {{VALUE}};',
					'{{WRAPPER}} .thim-ekit-single-course__meta span svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 2,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekit-single-course__meta span i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .thim-ekit-single-course__meta span svg' => 'max-width: {{SIZE}}{{UNIT}}; height: auto',
				),
			)
		);
		$this->add_responsive_control(
			'icon_spacing',
			array(
				'label'     => esc_html__( 'Icon Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
					'unit' => 'px',
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__meta span i, {{WRAPPER}} .thim-ekit-single-course__meta span svg' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}

	public function render() {
		do_action( 'thim-ekit/modules/single-course/before-preview-query' );

		$settings = $this->get_settings_for_display();

		$course = learn_press_get_course();

		if ( ! $course ) {
			return;
		}
		?>
		<div class="thim-ekit-single-course__meta">
			<?php
			if ( ! empty( $settings['item_list'] ) ) {
				foreach ( $settings['item_list'] as $repeater_item ) {
					$this->render_item( $repeater_item, $course );
				}
			}
			?>
		</div>
		<?php
		do_action( 'thim-ekit/modules/single-course/after-preview-query' );
	}

	protected function render_item( $setting, $course ) {
		switch ( $setting['type'] ) {
			case 'duration':
				$this->render_duration( $setting );
				break;
			case 'level':
				$this->render_level( $setting );
				break;
			case 'lesson':
				$this->render_lesson( $setting, $course );
				break;
			case 'quiz':
				$this->render_quiz( $setting, $course );
				break;
			case 'student':
				$this->render_student( $setting, $course );
				break;
			case 'last_update':
				$this->render_last_update( $setting );
				break;
			case 'assessments':
				$this->render_assessments( $setting, $course );
				break;
			case 'certificates':
				$this->render_certificates( $setting, $course );
				break;
			case 'language':
				$this->render_language( $setting, $course );
				break;
			case 'custom':
				$this->render_custom( $setting );
				break;
		}
	}

	protected function render_duration( $settings ) {
		$label = ! empty( $settings['lifetime'] ) ? $settings['lifetime'] : esc_html__( 'Lifetime access', 'thim-elementor-kit' );
		$text  = $settings['custom_text'];
		?>
		<span class="thim-ekit-single-course__meta__duration">
			<?php Icons_Manager::render_icon( $settings['icon'] ); ?>
			<?php echo wp_kses_post( $text ); ?>
			<?php echo '<span class="value">' . esc_html( learn_press_get_post_translated_duration( get_the_ID(), $label ) ) . '</span>'; ?>
		</span>
		<?php
	}

	protected function render_level( $settings ) {
		$level = learn_press_get_post_level( get_the_ID() );
		$text  = $settings['custom_text'];
		if ( empty( $level ) ) {
			return;
		}
		?>
		<span class="thim-ekit-single-course__meta__level">
			<?php Icons_Manager::render_icon( $settings['icon'] ); ?>
			<?php echo wp_kses_post( $text ); ?>
			<?php echo '<span class="value">' . esc_html( $level ) . '</span>'; ?>
		</span>
		<?php
	}

	protected function render_lesson( $settings, $course ) {
		$lessons = $course->get_items( LP_LESSON_CPT );
		$lessons = count( $lessons );

		$suffix = ! empty( $settings['singular_lesson'] ) ? $settings['singular_lesson'] : esc_html__( 'lesson', 'thim-elementor-kit' );

		if ( $lessons > 1 ) {
			$suffix = ! empty( $settings['plural_lesson'] ) ? $settings['plural_lesson'] : esc_html__( 'lessons', 'thim-elementor-kit' );
		}
		?>
		<span class="thim-ekit-single-course__meta__count-lesson">
			<?php Icons_Manager::render_icon( $settings['icon'] ); ?>
			<?php printf( '<span class="value">%1$d</span> %2$s',  absint( $lessons ) , esc_html( $suffix ) ); ?>
		</span>
		<?php
	}

	protected function render_quiz( $settings, $course ) {
		$quizzes = $course->get_items( LP_QUIZ_CPT );
		$quizzes = count( $quizzes );

		$suffix = ! empty( $settings['singular_quiz'] ) ? $settings['singular_quiz'] : esc_html__( 'quiz', 'thim-elementor-kit' );

		if ( $quizzes > 1 ) {
			$suffix = ! empty( $settings['plural_quiz'] ) ? $settings['plural_quiz'] : esc_html__( 'quizzes', 'thim-elementor-kit' );
		}
		?>
		<span class="thim-ekit-single-course__meta__count-quiz">
			<?php Icons_Manager::render_icon( $settings['icon'] ); ?>
			<?php printf( '<span class="value">%1$d</span> %2$s', absint( $quizzes ), esc_html( $suffix ) ); ?>
		</span>
		<?php
	}

	protected function render_student( $settings, $course ) {
		$students = $course->count_students();

		$suffix = ! empty( $settings['singular_student'] ) ? $settings['singular_student'] : esc_html__( 'student', 'thim-elementor-kit' );

		if ( absint( $students ) > 1 ) {
			$suffix = ! empty( $settings['plural_student'] ) ? $settings['plural_student'] : esc_html__( 'students', 'thim-elementor-kit' );
		}
		?>
		<span class="thim-ekit-single-course__meta__count-student">
			<?php Icons_Manager::render_icon( $settings['icon'] ); ?>
			<?php printf( '<span class="value">%1$d</span> %2$s', absint( $students ), esc_html( $suffix ) ); ?>
		</span>
		<?php
	}

	protected function render_assessments( $settings, $course ) {
		$course_id = $course->get_id();
		$text      = $settings['custom_text'];
		?>
		<span class="thim-ekit-single-course__meta__assessments">
			<?php Icons_Manager::render_icon( $settings['icon'] ); ?>
			<?php echo wp_kses_post( $text ); ?>
			<span
				class="value"><?php echo ( get_post_meta( $course_id, '_lp_course_result', true ) == 'evaluate_lesson' ) ? esc_html__( 'Yes', 'thim-elementor-kit' ) : esc_html__( 'Self', 'thim-elementor-kit' ); ?></span>
		</span>
		<?php
	}

	protected function render_certificates( $settings, $course ) {
		$course_id = $course->get_id();
		$text      = $settings['custom_text'];
		?>
		<span class="thim-ekit-single-course__meta__certificates">
			<?php Icons_Manager::render_icon( $settings['icon'] ); ?>
			<?php echo wp_kses_post( $text ); ?>
			<span
				class="value"><?php echo ( get_post_meta( $course_id, '_lp_cert', true ) ) ? esc_html__( 'Yes', 'thim-elementor-kit' ) : esc_html__( 'No', 'thim-elementor-kit' ); ?></span>
		</span>
		<?php
	}

	protected function render_language( $settings, $course ) {
		$course_id = $course->get_id();
		$text      = $settings['custom_text'];
		$language  = get_post_meta( $course_id, apply_filters( 'thim-ekit-course-key-language', '_lp_language' ), true );
		if ( $language ) { ?>
			<span class="thim-ekit-single-course__meta__language">
			<?php Icons_Manager::render_icon( $settings['icon'] ); ?>
				<?php echo wp_kses_post( $text ); ?>
			<span class="value"><?php echo wp_kses_post( $language ); ?></span>
		</span>
		<?php }
	}

	protected function render_custom( $settings ) {
		$text        = $settings['custom_text'];
 		if ( ! empty( $settings['custom_url']['url'] ) ) {
			$this->add_link_attributes( 'link-' . sanitize_title( $settings['custom_text'] ), $settings['custom_url'] );
		}
		?>
		<span class="thim-ekit-single-course__meta__custom">
			<?php Icons_Manager::render_icon( $settings['icon'] ); ?>
			<?php if ( ! empty( $settings['custom_url']['url'] ) ) : ?>
				<a <?php echo $this->get_render_attribute_string( 'link-' . sanitize_title( $settings['custom_text'] ) ); ?>>
			<?php endif; ?>

			<?php echo wp_kses_post( $text ); ?>

			<?php if ( ! empty( $settings['custom_url']['url'] ) ) : ?>
				</a>
			<?php endif; ?>
		</span>
		<?php
	}

	protected function render_last_update( $settings ) {
		$text = $settings['custom_text'];
		?>
		<span class="thim-ekit-single-course__meta__last_update">
			<?php Icons_Manager::render_icon( $settings['icon'] ); ?>
			<?php echo wp_kses_post( $text ); ?>
			<?php echo '<span class="value">' . esc_attr( get_post_modified_time( $settings['date_format'], true ) ) . '</span>'; ?>
		</span>
		<?php
	}
}
