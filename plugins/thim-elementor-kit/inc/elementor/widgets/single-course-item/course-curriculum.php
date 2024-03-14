<?php
namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Course_Curriculum extends Widget_Base {

	public function get_name() {
		return 'thim-ekits-course-curricilum';
	}

	public function get_title() {
		return esc_html__( 'Course Curriculum', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-post-list';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_SINGLE_COURSE_ITEM );
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'style_curriculum_content',
			array(
				'label' => esc_html__( 'Section', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'curriculum_max_height',
			array(
				'label'      => esc_html__( 'Max Height', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'vh', 'custom' ),
				'default'    => array(
					'unit' => 'vh',
					'size' => 80,
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekit-single-item__curriculum #learn-press-course-curriculum' => 'max-height: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_control(
			'section_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .course-curriculum .course-item' => 'background-color: transparent;',
					'{{WRAPPER}} .course-curriculum .section' => 'background-color: {{VALUE}}; border-bottom: none!important',
				),
			)
		);
		$this->add_responsive_control(
			'section_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .course-curriculum .section' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .course-curriculum .section-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
				),
			)
		);
		$this->add_responsive_control(
			'section_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 120,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .course-curriculum .section:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_control(
			'curriculum_heading_section',
			array(
				'label'     => esc_html__( 'Title Section', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Title Typography', 'thim-elementor-kit' ),
				'name'     => 'title_sc_typo',
				'selector' => '{{WRAPPER}} #learn-press-course-curriculum .curriculum-sections .section-title',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Description Typography', 'thim-elementor-kit' ),
				'name'     => 'desc_sc_typo',
				'selector' => '{{WRAPPER}} #learn-press-course-curriculum  .curriculum-sections .section-desc',
			)
		);
		$this->add_responsive_control(
			'sc_title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} #learn-press-course-curriculum .curriculum-sections .section-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .curriculum-sections .section-header' => '--section-title-padding-top: {{TOP}}{{UNIT}};--section-title-padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'sc_title_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #learn-press-course-curriculum .section-header' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'sc_title_color',
			array(
				'label'     => esc_html__( 'Color Title', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .course-curriculum .section-header' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'sc_desc_color',
			array(
				'label'     => esc_html__( 'Color Description', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .course-curriculum .section-desc' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'curriculum_heading_lesson',
			array(
				'label'     => esc_html__( 'Lesson', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Title Typography', 'thim-elementor-kit' ),
				'name'     => 'lesson_typo',
				'selector' => '{{WRAPPER}} .course-curriculum .course-item .item-name',
			)
		);
		$this->add_responsive_control(
			'lesson__padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .course-curriculum .course-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .course-curriculum' => '--thim-ekit-padding-lesson: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'lesson_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .course-curriculum .course-item' => 'border-top: 1px solid {{VALUE}};margin-bottom: 0;',
				),
			)
		);
		$this->add_control(
			'lesson_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .course-curriculum .course-item .section-item-link' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'completed_color',
			array(
				'label'     => esc_html__( 'Completed Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .course-curriculum' => '--thim-curriculum-bg-status-completed: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'failed_color',
			array(
				'label'     => esc_html__( 'Failed Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .course-curriculum' => '--thim-curriculum-bg-status-failed: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();
	}
	public function render() {
		do_action( 'thim-ekit/modules/single-course-item/before-preview-query' );

		$settings = $this->get_settings_for_display();

		$item = \LP_Global::course_item();

		if ( ! $item ) {
			return;
		}

		$item_id   = $item->get_id();
		$course_id = get_the_ID();

		$filters                    = new \LP_Section_Filter();
		$filters->section_course_id = $course_id;
		$filters->limit             = -1;
		$filters->page              = 1;
		$filters->order             = 'ASC';

		if ( ! class_exists( '\LP_Model_User_Can_View_Course_Item' ) ) {
			require_once LP_PLUGIN_PATH . 'inc/course/class-model-user-can-view-course-item.php';
		}

		if ( ! class_exists( '\LP_REST_Lazy_Load_Controller' ) ) {
			require_once LP_PLUGIN_PATH . 'inc/rest-api/v1/frontend/class-lp-rest-lazy-load-controller.php';
		}

		$section_id = \LP_Section_DB::getInstance()->get_section_id_by_item_id( absint( $item_id ) );
		$sections   = \LP_Section_DB::getInstance()->get_sections_by_course_id( $filters );
		?>

		<div class="thim-ekit-single-item__curriculum">
			<?php if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) : ?>
				<?php learn_press_get_template( 'single-course/tabs/curriculum-v2', compact( 'sections', 'course_id', 'filters' ) ); ?>
			<?php else : ?>
				<div class="learnpress-course-curriculum" data-section="<?php echo esc_attr( $section_id ?? '' ); ?>" data-id="<?php echo esc_attr( $item_id ?? '' ); ?>">
					<ul class="lp-skeleton-animation">
						<li style="width: 100%; height: 50px"></li>
						<li style="width: 100%; height: 20px"></li>
						<li style="width: 100%; height: 20px"></li>
						<li style="width: 100%; height: 20px"></li>

						<li style="width: 100%; height: 50px; margin-top: 40px;"></li>
						<li style="width: 100%; height: 20px"></li>
						<li style="width: 100%; height: 20px"></li>
						<li style="width: 100%; height: 20px"></li>
					</ul>
				</div>
			<?php endif; ?>
		</div>

		<?php
		do_action( 'thim-ekit/modules/single-course-item/after-preview-query' );
	}
}
