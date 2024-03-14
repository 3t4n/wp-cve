<?php
namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Course_User_Time extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-course-user-time';
	}

	public function get_title() {
		return esc_html__( ' Course User Time', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-clock-o';
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
			'start_label',
			array(
				'label'       => esc_html__( 'Start label', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'You started on:', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'finish_label',
			array(
				'label'       => esc_html__( 'Finish label', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'You finished on:', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'explanation_label',
			array(
				'label'       => esc_html__( 'Explanation label', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Course will end:', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'duaration_label',
			array(
				'label'       => esc_html__( 'Duration label', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Duration:', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'duaration_value',
			array(
				'label'       => esc_html__( 'Lifetime label', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Lifetime', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'date_format',
			array(
				'label'     => esc_html__( 'Date Format', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => array(
					'default' => 'Default',
					'0'       => esc_html_x( 'March 6, 2021 (F j, Y)', 'Date Format', 'thim-elementor-kit' ),
					'1'       => '2021-03-06 (Y-m-d)',
					'2'       => '03/06/2021 (m/d/Y)',
					'3'       => '06/03/2021 (d/m/Y)',
					'custom'  => esc_html__( 'Custom', 'thim-elementor-kit' ),
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'custom_date_format',
			array(
				'label'       => esc_html__( 'Custom Date Format', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'F j, Y',
				'condition'   => array(
					'date_format' => 'custom',
				),
				'description' => sprintf(
					esc_html__( 'Use the letters: %s', 'thim-elementor-kit' ),
					'l D d j S F m M n Y y'
				),
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
					'{{WRAPPER}} .thim-ekit-single-course__user-time__row strong' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-course__user-time__row strong',
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
					'{{WRAPPER}} .thim-ekit-single-course__user-time__row .thim-ekit-single-course__user-time__entry-date' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-course__user-time__row .thim-ekit-single-course__user-time__entry-date',
			)
		);

		$this->end_controls_section();
	}

	public function render() {
		do_action( 'thim-ekit/modules/single-course/before-preview-query' );

		$course  = learn_press_get_course();
		$user_id = get_current_user_id();

		if ( ! $course || ! $user_id ) {
			return;
		}

		$user = learn_press_get_user( $user_id );

		if ( ! $user ) {
			return;
		}

		if ( ! $user->has_enrolled_or_finished( $course->get_id() ) ) {
			return;
		}

		$user_course = $user->get_course_data( $course->get_id() );

		if ( ! $user_course ) {
			return;
		}

		$status          = $user_course->get_status();
		$start_time      = $user_course->get_start_time();
		$end_time        = $user_course->get_end_time();
		$expiration_time = $user_course->get_expiration_time();

		$settings = $this->get_settings_for_display();

		$start_label = ! empty( $settings['start_label'] ) ? $settings['start_label'] : esc_html__( 'You started on:', 'thim-elementor-kit' );
		$end_label   = ! empty( $settings['end_label'] ) ? $settings['end_label'] : esc_html__( 'You finished on:', 'thim-elementor-kit' );
		$explanation = ! empty( $settings['explanation_label'] ) ? $settings['explanation_label'] : esc_html__( 'Course will end:', 'thim-elementor-kit' );
		$duration    = ! empty( $settings['duaration_label'] ) ? $settings['duaration_label'] : esc_html__( 'Duration:', 'thim-elementor-kit' );
		$lifetime    = ! empty( $settings['duaration_value'] ) ? $settings['duaration_value'] : esc_html__( 'Lifetime', 'thim-elementor-kit' );

		$custom_date_format = empty( $repeater_item['custom_date_format'] ) ? 'F j, Y' : $repeater_item['custom_date_format'];

		$format_options = array(
			'default' => 'i18n',
			'0'       => 'F j, Y',
			'1'       => 'Y-m-d',
			'2'       => 'm/d/Y',
			'3'       => 'd/m/Y',
			'custom'  => $custom_date_format,
		);

		$format = $format_options[ $settings['date_format'] ];
		?>

		<div class="thim-ekit-single-course__user-time">
			<div class="thim-ekit-single-course__user-time__row">
				<strong><?php echo esc_html( $start_label ); ?></strong>
				<time class="thim-ekit-single-course__user-time__entry-date thim-ekit-single-course__user-time__enrolled"><?php echo wp_kses_post( $start_time->format( $format ) ); ?></time>
			</div>
			<?php if ( in_array( $status, array( learn_press_user_item_in_progress_slug(), 'enrolled' ) ) ) : ?>
				<?php if ( $expiration_time ) : ?>
					<div class="thim-ekit-single-course__user-time__row">
						<strong><?php echo esc_html( $explanation ); ?></strong>
						<time class="thim-ekit-single-course__user-time__entry-date thim-ekit-single-course__user-time__expire"><?php echo wp_kses_post( $expiration_time->format( $format ) ); ?></time>
					</div>
				<?php else : ?>
					<div class="thim-ekit-single-course__user-time__row">
						<strong><?php echo esc_html( $duration ); ?></strong>
						<span class="thim-ekit-single-course__user-time__entry-date thim-ekit-single-course__user-time__lifetime"><?php echo esc_html( $lifetime ); ?></span>
					</div>
				<?php endif; ?>
			<?php elseif ( $status === 'finished' && $end_time ) : ?>
				<div class="thim-ekit-single-course__user-time__row">
					<strong><?php echo esc_html( $end_label ); ?></strong>
					<time class="thim-ekit-single-course__user-time__entry-date entry-date thim-ekit-single-course__user-time__finished"><?php echo wp_kses_post( $end_time->format( $format ) ); ?></time>
				</div>
			<?php endif; ?>
		</div>

		<?php
		do_action( 'thim-ekit/modules/single-course/after-preview-query' );
	}
}
