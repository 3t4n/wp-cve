<?php
namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Course_User_Progress extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-course-user-progress';
	}

	public function get_title() {
		return esc_html__( ' Course User Progress', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-progress-tracker';
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

		$this->add_control(
			'lesson_label',
			array(
				'label'       => esc_html__( 'Lesson label', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Lessons completed:', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'quiz_label',
			array(
				'label'       => esc_html__( 'Quiz label', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Quizzes completed:', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'assignment_label',
			array(
				'label'       => esc_html__( 'Assignment label', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Assignments evaluated:', 'thim-elementor-kit' ),
				'description' => esc_html__( 'Use for Assignment add-on', 'thim-elementor-kit' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Style', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'style_label',
			array(
				'label'     => esc_html__( 'Label', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__user-progress__heading' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-course__user-progress__heading',
			)
		);

		$this->add_control(
			'style_content',
			array(
				'label'     => esc_html__( 'Content', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'content_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__user-progress__number' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-course__user-progress__number',
			)
		);

		$this->end_controls_section();
	}

	public function render() {
		do_action( 'thim-ekit/modules/single-course/before-preview-query' );

		$course = learn_press_get_course();
		$user   = learn_press_get_current_user();

		if ( ! $user || ! $course ) {
			return;
		}

		if ( ! $user->has_enrolled_or_finished( $course->get_id() ) ) {
			return;
		}

		$course_data       = $user->get_course_data( $course->get_id() );
		$course_results    = $course_data->calculate_course_results();
		$passing_condition = $course->get_passing_condition();
		$quiz_false        = 0;

		if ( ! empty( $course_results['items'] ) ) {
			$quiz_false = $course_results['items']['quiz']['completed'] - $course_results['items']['quiz']['passed'];
		}

		$settings = $this->get_settings_for_display();

		$lesson_label     = ! empty( $settings['lesson_label'] ) ? $settings['lesson_label'] : esc_html__( 'Lessons completed:', 'thim-elementor-kit' );
		$quiz_label       = ! empty( $settings['quiz_label'] ) ? $settings['quiz_label'] : esc_html__( 'Quizzes completed:', 'thim-elementor-kit' );
		$assignment_label = ! empty( $settings['assignment_label'] ) ? $settings['assignment_label'] : esc_html__( 'Assignments evaluated:', 'thim-elementor-kit' );
		?>

		<div class="thim-ekit-single-course__user-progress">
			<div class="thim-ekit-single-course__user-progress__item">
				<strong class="thim-ekit-single-course__user-progress__heading">
					<?php echo esc_html( $lesson_label ); ?>
				</strong>
				<span class="thim-ekit-single-course__user-progress__number"><?php printf( '%1$d/%2$d', absint( $course_results['items']['lesson']['completed'] ), absint( $course_results['items']['lesson']['total'] ) ); ?></span>
			</div>

			<div class="thim-ekit-single-course__user-progress__item">
				<strong class="thim-ekit-single-course__user-progress__heading">
					<?php echo esc_html( $quiz_label ); ?>
				</strong>
				<span class="thim-ekit-single-course__user-progress__number" title="<?php esc_attr( sprintf( __( 'Failed %1$d, Passed %2$d', 'thim-elementor-kit' ), absint( $quiz_false ), absint( $course_results['items']['quiz']['passed'] ) ) ); ?>"><?php printf( '%1$d/%2$d', absint( $course_results['items']['quiz']['completed'] ), absint( $course_results['items']['quiz']['total'] ) ); ?></span>
			</div>

			<?php if ( ! empty( $course_results['items']['assignment'] ) ) : ?>
				<?php $assignment_complete = $course_results['pass'] == 1 ? $course_results['items']['assignment']['passed'] : $course_results['items']['assignment']['completed']; ?>
				<div class="thim-ekit-single-course__user-progress__item">
					<strong class="thim-ekit-single-course__user-progress__heading">
						<?php echo esc_html( $assignment_label ); ?>
					</strong>
					<span class="thim-ekit-single-course__user-progress__number"><?php printf( '%1$d/%2$d', absint( $assignment_complete ), absint( $course_results['items']['assignment']['total'] ) ); ?></span>
				</div>
			<?php endif; ?>
		</div>

		<?php
		do_action( 'thim-ekit/modules/single-course/after-preview-query' );
	}
}
