<?php
namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Course_Graduation extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-course-graduation';
	}

	public function get_title() {
		return esc_html__( ' Course Graduation', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-check-circle';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_SINGLE_COURSE );
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_course_graduation_style',
			array(
				'label' => esc_html__( 'Style', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__graduation' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__graduation' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-course__graduation',
			)
		);

		$this->add_control(
			'passed',
			array(
				'label'     => esc_html__( 'Passed', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'color_pass',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__graduation--passed' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'failed',
			array(
				'label'     => esc_html__( 'Failed', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'color_failed',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__graduation--failed' => 'color: {{VALUE}}',
				),
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

		if ( ! $user->has_finished_course( $course->get_id() ) ) {
			return;
		}

		$graduation = $user->get_course_grade( $course->get_id() );

		$settings = $this->get_settings_for_display();
		?>

		<div class="thim-ekit-single-course__graduation <?php echo esc_attr( $graduation === 'passed' ? 'thim-ekit-single-course__graduation--passed' : ( $graduation === 'failed' ? 'thim-ekit-single-course__graduation--failed' : '' ) ); ?>">
			<span><?php learn_press_course_grade_html( $graduation ); ?></span>
		</div>

		<?php
		do_action( 'thim-ekit/modules/single-course/after-preview-query' );
	}
}
