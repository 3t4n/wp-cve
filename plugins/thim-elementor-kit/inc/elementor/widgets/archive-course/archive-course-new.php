<?php
/**
 * Elementor Widget: Thim_Archive_Course
 * Description: Show list courses
 *
 * @version: 1.0.0
 * @since 1.1.8
 */

namespace Elementor;

use LearnPress\Helpers\Template;
use LearnPress\Models\Courses;
use LearnPress\TemplateHooks\Course\ListCoursesTemplate;
use LearnPress\TemplateHooks\Course\SingleCourseTemplate;
use LearnPress\TemplateHooks\TemplateAJAX;
use LP_Course;
use LP_Course_Filter;
use LP_Database;
use LP_Helper;
use LP_Settings;
use stdClass;
use Thim_EL_Kit\GroupControlTrait;
use Throwable;

if ( ! class_exists( '\Elementor\Thim_Ekits_Course_Base' ) ) {
	include THIM_EKIT_PLUGIN_PATH . 'inc/elementor/widgets/global/course-base.php';
}

class Thim_Ekit_Widget_Archive_Course extends Thim_Ekits_Course_Base {
	use GroupControlTrait;

	protected $current_permalink;

	public $is_skeleton = false;

	public static $_instance = null;

	public static function instance() {
		if ( ! isset( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-archive-course';
	}

	public function get_title() {
		return esc_html__( 'Archive Course', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-archive-posts';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_ARCHIVE_COURSE );
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->_register_options_archive();

		$this->_register_topbar_header();

		$this->_register_style_topbar_header();

		parent::register_controls();

		$this->register_navigation_archive();

		$this->register_style_pagination_archive( '.thim-ekits-archive-course__pagination' );
	}

	protected function _register_options_archive() {
		$this->start_controls_section(
			'section_options',
			array(
				'label' => esc_html__( 'Options', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'is_ajax',
			array(
				'label'     => esc_html__( 'Enable full Ajax', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'build_loop_item',
			array(
				'label'     => esc_html__( 'Build Loop Item', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'separator' => 'before',
			)
		);

		$template_loops = [];
		if ( is_admin() ) {
			$template_loops = [ '0' => esc_html__( 'None', 'thim-elementor-kit' ) ] + \Thim_EL_Kit\Functions::instance()->get_pages_loop_item( 'lp_course' );
		}
		$this->add_control(
			'template_id',
			array(
				'label'         => esc_html__( 'Choose a template', 'thim-elementor-kit' ),
				'type'          => Controls_Manager::SELECT2,
				'default'       => '0',
				'options'       => $template_loops,
				//              'frontend_available' => true,
				'prevent_empty' => false,
				'condition'     => array(
					'build_loop_item' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'              => esc_html__( 'Columns', 'thim-elementor-kit' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '3',
				'tablet_default'     => '2',
				'mobile_default'     => '1',
				'options'            => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'selectors'          => array(
					'{{WRAPPER}}' => '--thim-ekits-course-columns: repeat({{VALUE}}, 1fr)',
				),
				'frontend_available' => true,
			)
		);

		$this->end_controls_section();
	}

	protected function _register_topbar_header() {
		$this->start_controls_section(
			'section_archive_topbar',
			array(
				'label' => esc_html__( 'Top Bar', 'thim-elementor-kit' ),
			)
		);
		$repeater_header = new \Elementor\Repeater();

		$repeater_header->add_control(
			'header_key',
			array(
				'label'   => esc_html__( 'Type', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'order',
				'options' => array(
					'result' => 'Result Count',
					'order'  => 'Order',
					'search' => 'Search',
				),
			)
		);

		$repeater_header->add_control(
			'placeholder',
			array(
				'label'     => esc_html__( 'Placeholder', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Search...', 'thim-elementor-kit' ),
				'condition' => array(
					'header_key' => 'search',
				),
			)
		);

		$repeater_header->add_control(
			'search_icon',
			array(
				'label'       => esc_html__( 'Search Icon', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'fas fa-search',
					'library' => 'fa-solid',
				),
				'skin'        => 'inline',
				'label_block' => false,
				'condition'   => array(
					'header_key' => 'search',
				),
			)
		);

		$this->add_control(
			'thim_header_repeater',
			array(
				'label'       => esc_html__( 'Top Bar', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater_header->get_controls(),
				'default'     => array(
					array(
						'header_key' => 'result',
					),
					array(
						'header_key' => 'order',
					),
				),
				'title_field' => '<span style="text-transform: capitalize;">{{{ header_key.replace("_", " ") }}}</span>',
			)
		);

		$this->end_controls_section();
	}

	protected function _register_style_topbar_header() {
		$this->start_controls_section(
			'section_style_topbar',
			array(
				'label' => esc_html__( 'Top Bar', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'topbar_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'topbar_gap',
			array(
				'label'     => esc_html__( 'Gap', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar' => '--thim-ekits-archive-course-topbar-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'result_count_style',
			array(
				'label'     => esc_html__( 'Result Count', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'result_count_align',
			array(
				'label'        => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'prefix_class' => 'thim-ekits-archive-course__topbar__result-',
			)
		);

		$this->add_control(
			'result_count_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__result' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'result_count_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-archive-course__topbar__result',
			)
		);

		$this->add_control(
			'search_style',
			array(
				'label'     => esc_html__( 'Search', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'search_align',
			array(
				'label'        => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'prefix_class' => 'thim-ekits-archive-course__topbar__search-',
			)
		);

		$this->add_responsive_control(
			'search_width',
			array(
				'label'     => esc_html__( 'Width', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 200,
						'max' => 500,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'search_height',
			array(
				'label'     => esc_html__( 'Height', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search' => 'min-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Input Typography', 'thim-elementor-kit' ),
				'name'     => 'search_input_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-archive-course__topbar__search input[type="search"]',
			)
		);

		$this->start_controls_tabs( 'search_input_colors' );

		$this->start_controls_tab(
			'search_input_normal',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'search_input_color',
			array(
				'label'     => esc_html__( 'Input Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7a7a7a',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search input[type="search"]' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'search_input_bg_color',
			array(
				'label'     => esc_html__( 'Input Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eceeef',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search input[type="search"]' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'search_border_color',
			array(
				'label'     => esc_html__( 'Input Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eceeef',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search input[type="search"]' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'search_input_focus',
			array(
				'label' => esc_html__( 'Focus', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'search_input_focus_color',
			array(
				'label'     => esc_html__( 'Input Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7a7a7a',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search input[type="search"]:focus' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'search_input_focus_bg_color',
			array(
				'label'     => esc_html__( 'Input Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search input[type="search"]:focus' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'search_border_focus_color',
			array(
				'label'     => esc_html__( 'Input Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eceeef',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search input[type="search"]:focus' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'search_input_border_width',
			array(
				'label'     => esc_html__( 'Input Border Size', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search input[type="search"]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'search_input_border_radius',
			array(
				'label'     => esc_html__( 'Input Border Radius', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search input[type="search"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'search_button_spacing',
			array(
				'label'     => esc_html__( 'Button Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search button' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'search_button_size',
			array(
				'label'     => esc_html__( 'Button Size', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 50,
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search button'               => 'min-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search input[type="search"]' => 'padding-left: calc( {{SIZE}}{{UNIT}} / 3 );padding-right: calc( {{SIZE}}{{UNIT}} / 3 );',
				),
			)
		);

		$this->start_controls_tabs( 'search_button_colors' );

		$this->start_controls_tab(
			'search_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'search_button_bg_color',
			array(
				'label'     => esc_html__( 'Button Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eceeef',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search button' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'search_button_color',
			array(
				'label'     => esc_html__( 'Button Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7a7a7a',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search button' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'search_button_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search button' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'search_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'search_button_bg_color_hover',
			array(
				'label'     => esc_html__( 'Button Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eceeef',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search button:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'search_button_color_hover',
			array(
				'label'     => esc_html__( 'Button Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search button:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'search_button_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search button:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'search_button_border_width',
			array(
				'label'     => esc_html__( 'Button Border Size', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'search_button_border_radius',
			array(
				'label'     => esc_html__( 'Button Border Radius', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'orderby_style',
			array(
				'label'     => esc_html__( 'Orderby', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'name'     => 'orderby_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-archive-course__topbar__orderby select',
			)
		);

		$this->add_control(
			'orderby_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eceeef',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__orderby select' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'orderby_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__orderby select' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'orderby_border',
				'selector' => '{{WRAPPER}} .thim-ekits-archive-course__topbar__orderby select',
			)
		);

		$this->add_control(
			'orderby_border_radius',
			array(
				'label'     => esc_html__( 'Orderby Radius', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__orderby select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'orderby_padding',
			array(
				'label'     => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__orderby select' => '--thim-ekits-archive-course-topbar-orderby-padding: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @return void
	 * @since 1.0.0
	 * @version 1.0.1
	 */
	public function render() {
		try {
			$settings                = $this->get_settings_for_display();
			$is_load_restapi         = $settings['is_ajax'] ?? 0;
			$settings['url_current'] = LP_Helper::getUrlCurrent();

			// Merge params filter form url
			$settings = array_merge(
				$settings,
				lp_archive_skeleton_get_args()
			);

			$html_wrapper_widget = [
				'<div class="thim-ekits-archive-course thim-ekits-course lp-list-courses-default">' => '</div>',
			];

			$callback = [
				'class'  => get_class( $this ),
				'method' => 'render_courses',
			];

			// No load AJAX
			if ( 'yes' !== $is_load_restapi || Plugin::$instance->editor->is_edit_mode() ) {
				$content_obj                         = static::render_courses( $settings );
				$settings['html_no_load_ajax_first'] = $content_obj->content;
				$content                             = TemplateAJAX::load_content_via_ajax( $settings, $callback );
			} else { // Load AJAX
				$content = TemplateAJAX::load_content_via_ajax( $settings, $callback );
			}

			echo Template::instance()->nest_elements( $html_wrapper_widget, $content );
		} catch ( Throwable $e ) {
			echo $e->getMessage();
		}
	}

	/**
	 * Render template list courses with settings param.
	 *
	 * @param array $settings
	 *
	 * @return stdClass { content: string_html }
	 * @since 4.2.5.7
	 * @version 1.0.1
	 */
	public static function render_courses( array $settings = [] ): stdClass {
		$courses_order_by  = $settings['order_by'] ?? $settings['courses_order_by_default'] ?? 'post_date';
		$build_loop_item   = $settings['build_loop_item'] ?? 'no';
		$courses_per_page  = LP_Settings::get_option( 'archive_course_limit', 8 );
		$settings['limit'] = $courses_per_page;

		$filter               = new LP_Course_Filter();
		$filter->limit        = $courses_per_page;
		$settings['order_by'] = $courses_order_by;
		Courses::handle_params_for_query_courses( $filter, $settings );

		// Check is in category page.
		if ( ! empty( $settings['page_term_id_current'] ) && empty( $settings['term_id'] ) ) {
			$filter->term_ids[] = $settings['page_term_id_current'];
		} // Check is in tag page.
		elseif ( ! empty( $settings['page_tag_id_current'] ) && empty( $settings['tag_id'] ) ) {
			$filter->tag_ids[] = $settings['page_tag_id_current'];
		}
		$total_rows  = 0;
		$courses     = Courses::get_courses( $filter, $total_rows );
		$total_pages = LP_Database::get_total_pages( $filter->limit, $total_rows );

		// Handle layout
		ob_start();

		if ( ! empty( $courses ) ) {
			self::render_topbar( $settings, $total_rows );
		}

		echo '<div class="thim-ekits-course__inner lp-list-courses-no-css">';

		if ( ! empty( $courses ) ) {
			// Loop courses
			foreach ( $courses as $courseObj ) {
				$course = learn_press_get_course( $courseObj->ID );
				if ( $build_loop_item === 'yes' ) {
					global $post;
					$post = get_post( $course->get_id() );
				}

				self::render_item_course( $course, $settings, 'thim-ekits-course__item' );
			}
		} else {
			echo '<div class="courses-not-found"><span>' . __( 'No courses found', 'thim-elementor-kit' ) . '</span></div>';
		}

		echo '</div>';

		if ( ! empty( $courses ) ) {
			self::render_loop_footer( $filter, $total_rows, $settings );
		}

		$content              = new stdClass();
		$content->content     = ob_get_clean();
		$content->total_pages = $total_pages;
		$content->paged       = $settings['paged'] ?? 1;

		return $content;
	}

	public static function render_item_course( LP_Course $course, $settings, $class_item ) {
		$build_loop_item = $settings['build_loop_item'] ?? 'no';
		?>
		<div <?php post_class( array( $class_item ) ); ?>>
			<?php
			if ( $build_loop_item === 'yes' ) {
				echo self::render_course_loop_item( $course, $settings );
			} else {
				self::instance()->render_thumbnail( $settings, $course );
				echo '<div class="thim-ekits-course__content">';

				if ( $settings['repeater'] ) {
					foreach ( $settings['repeater'] as $item ) {
						switch ( $item['key'] ) {
							case 'title':
								self::instance()->render_title( $course, $settings, $item );
								break;
							case 'price':
								self::instance()->render_price( $course );
								break;
							case 'content':
								self::instance()->render_excerpt( $settings, $item, $course );
								break;
							case 'meta_data':
								self::instance()->render_meta_data( $settings, $item, $course );
								break;
							case 'read_more':
								self::instance()->render_read_more( $settings, $item['read_more_text'], $item['read_more_icon'], $course );
								break;
						}
					}
				}

				echo '</div>';
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render single item course
	 *
	 * @param LP_Course $course
	 * @param array $settings
	 *
	 * @return string
	 */
	public static function render_item_course_default( LP_Course $course, array $settings = [] ): string {
		$html_item            = '';
		$singleCourseTemplate = SingleCourseTemplate::instance();

		try {
			$html_wrapper = [
				'<li class="course-item">' => '</li>',
			];

			$title = sprintf( '<a href="%s">%s</a>', $course->get_permalink(), $course->get_title() );

			$section = [
				'image'  => [ 'text_html' => $singleCourseTemplate->html_image( $course ) ],
				'author' => [ 'text_html' => sprintf( '<div>%s</div>', $course->get_instructor_html() ) ],
				'title'  => [ 'text_html' => $title ],
				'price'  => [ 'text_html' => sprintf( '<div>%s</div>', $singleCourseTemplate->html_price( $course ) ) ],
				'button' => [ 'text_html' => sprintf( '<div><a href="%s">%s</a></div>', $course->get_permalink(), __( 'View More' ) ) ],
			];

			ob_start();
			Template::instance()->print_sections( $section );
			$html_item = ob_get_clean();
			$html_item = Template::instance()->nest_elements( $html_wrapper, $html_item );
		} catch ( Throwable $e ) {
			$html_item = $e->getMessage();
		}

		return $html_item;
	}

	/**
	 * Render single item course
	 *
	 * @param LP_Course $course
	 * @param array $settings
	 *
	 * @return string
	 */
	public static function render_course_loop_item( LP_Course $course, array $settings = [] ): string {
		$id = $settings['template_id'] ?? 0;

		ob_start();
		\Thim_EL_Kit\Utilities\Elementor::instance()->render_loop_item_content( $id );

		return ob_get_clean();
	}

	public static function render_topbar( $settings, $total_rows ) {
		$pagination_type = $settings['pagination_type'] ?? 'number';
		switch ( $pagination_type ) {
			case 'load_more_on_click':
				$pagination_type = 'load-more';
				break;
			case 'load_more_infinite_scroll':
				$pagination_type = 'infinite';
				break;
			default:
				$pagination_type = 'number';
		}
		$header_repeat = $settings['thim_header_repeater'] ?? [];

		if ( ! empty( $header_repeat ) ) {
			$data_rs = [
				'total_rows'       => $total_rows,
				'paged'            => $settings['paged'] ?? 1,
				'courses_per_page' => LP_Settings::get_option( 'archive_course_limit', 8 ),
				'pagination_type'  => $pagination_type,
			];
			?>
			<div class="thim-ekits-archive-course__topbar">
				<?php
				foreach ( $header_repeat as $item ) {
					switch ( $item['header_key'] ) {
						case 'result':
							self::render_result_count( $data_rs );
							break;
						case 'order':
							self::render_orderby( $settings );
							break;
						case 'search':
							self::render_search( $settings );
							break;
					}
				}
				?>
			</div>
			<?php
		}
	}

	public static function render_result_count( $data_rs ) {
		$listCoursesTemplate = ListCoursesTemplate::instance();
		?>
		<span class="thim-ekits-archive-course__topbar__result">
			<?php echo $listCoursesTemplate->html_courses_page_result( $data_rs ); ?>
		</span>
		<?php
	}

	public static function render_orderby( $settings ) {
		$listCoursesTemplate = ListCoursesTemplate::instance();
		$courses_order_by    = $settings['order_by'] ?? $settings['courses_order_by_default'] ?? 'post_date';
		?>
		<form class="thim-ekits-archive-course__topbar__orderby " method="get">
			<?php
			echo $listCoursesTemplate->html_order_by( $courses_order_by );
			?>
		</form>
		<?php
	}

	public static function render_search( $settings ) {
		$listCoursesTemplate = ListCoursesTemplate::instance();
		?>
		<form class="thim-ekits-archive-course__topbar__search" method="get">
			<?php
			echo $listCoursesTemplate->html_search_form( $settings );
			?>
		</form>
		<?php
	}

	public static function render_loop_footer( $filter, $total_rows, $settings ) {
		$ajax_pagination = in_array(
			$settings['pagination_type'],
			array(
				'load_more_on_click',
				'load_more_infinite_scroll',
			),
			true
		);

		if ( '' === $settings['pagination_type'] ) {
			return;
		}

		$total_pages = \LP_Database::get_total_pages( $filter->limit, $total_rows );

		if ( 2 > $total_pages ) {
			return;
		}

		$has_numbers   = in_array( $settings['pagination_type'], array( 'numbers', 'numbers_and_prev_next' ) );
		$has_prev_next = in_array( $settings['pagination_type'], array( 'prev_next', 'numbers_and_prev_next' ) );
		$paged         = $filter->page;

		if ( $ajax_pagination ) {
			if ( $paged >= $total_pages ) {
				return;
			}

			self::render_load_more_pagination( $settings );

			return;
		}

		$links = array();

		if ( $has_numbers ) {
			$paginate_args = array(
				'base'      => add_query_arg( 'paged', '%#%', $settings['url_current'] ),
				'type'      => 'array',
				'current'   => $paged,
				'total'     => $total_pages,
				'prev_next' => false,
				'show_all'  => 'yes' !== $settings['pagination_numbers_shorten'],
			);

			$links = paginate_links( $paginate_args );
		}

		if ( $has_prev_next ) {
			$prev_next = self::get_posts_nav_link( $filter, $total_rows, $paged, $total_pages, $settings );
			array_unshift( $links, $prev_next['prev'] );
			$links[] = $prev_next['next'];
		}
		?>
		<nav class="thim-ekits-archive-course__pagination"
			 aria-label="<?php esc_attr_e( 'Pagination', 'thim-elementor-kit' ); ?>">
			<?php echo wp_kses_post( implode( PHP_EOL, $links ) ); ?>
		</nav>
		<?php
	}

	public static function get_posts_nav_link( $filter, $total_rows, $paged, $page_limit = null, $settings = array() ) {
		if ( ! $page_limit ) {
			$page_limit = \LP_Database::get_total_pages( $filter->limit, $total_rows );
		}

		$return = array();

		$link_template     = '<a class="page-numbers %s" href="%s">%s</a>';
		$disabled_template = '<span class="page-numbers %s disabled">%s</span>';

		if ( $paged > 1 ) {
			$next_page = intval( $paged ) - 1;

			if ( $next_page < 1 ) {
				$next_page = 1;
			}

			$return['prev'] = sprintf( $link_template, 'prev', self::get_wp_link_page( $next_page ), $settings['pagination_prev_label'] );
		} else {
			$return['prev'] = sprintf( $disabled_template, 'prev', $settings['pagination_prev_label'] );
		}

		$next_page = intval( $paged ) + 1;

		if ( $next_page <= $page_limit ) {
			$return['next'] = sprintf( $link_template, 'next', self::get_wp_link_page( $next_page ), $settings['pagination_next_label'] );
		} else {
			$return['next'] = sprintf( $disabled_template, 'next', $settings['pagination_next_label'] );
		}

		return $return;
	}

	public static function get_current_page( $settings ) {
		if ( '' === $settings['pagination_type'] ) {
			return 1;
		}

		return max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
	}

	public static function get_wp_link_page( $i ) {
		if ( ! is_singular() || is_front_page() ) {
			return get_pagenum_link( $i );
		}

		// Based on wp-includes/post-template.php:957 `_wp_link_page`.
		global $wp_rewrite;
		$post       = get_post();
		$query_args = array();
		$url        = get_permalink();

		if ( $i > 1 ) {
			if ( '' === get_option( 'permalink_structure' ) || in_array(
					$post->post_status,
					array(
						'draft',
						'pending',
					)
				) ) {
				$url = add_query_arg( 'page', $i, $url );
			} elseif ( get_option( 'show_on_front' ) === 'page' && (int) get_option( 'page_on_front' ) === $post->ID ) {
				$url = trailingslashit( $url ) . user_trailingslashit( "$wp_rewrite->pagination_base/" . $i, 'single_paged' );
			} else {
				$url = trailingslashit( $url ) . user_trailingslashit( $i, 'single_paged' );
			}
		}

		if ( is_preview() ) {
			if ( ( 'draft' !== $post->post_status ) && isset( $_GET['preview_id'], $_GET['preview_nonce'] ) ) {
				$query_args['preview_id']    = absint( wp_unslash( $_GET['preview_id'] ) );
				$query_args['preview_nonce'] = sanitize_text_field( wp_unslash( $_GET['preview_nonce'] ) );
			}

			$url = get_preview_post_link( $post, $query_args, $url );
		}

		return $url;
	}

	public static function render_load_more_pagination( $settings ) {
		$pagination_type = $settings['pagination_type'];
		$class           = $pagination_type === 'load_more_on_click' ? 'courses-btn-load-more-no-css' : 'courses-load-infinite-no-css';
		$class_hide      = Plugin::$instance->editor->is_edit_mode() ? '' : 'hide';
		?>

		<div class="thim-ekits-archive__loadmore-button <?php echo $class; ?>">
			<?php if ( 'load_more_on_click' === $pagination_type ) : ?>
				<a class="thim-ekits-archive__loadmore-btn" href="#">
					<?php if ( ! empty( $settings['load_more_selected_icon']['value'] ) && $settings['load_more_icon_align'] === 'left' ) : ?>
						<span class="thim-ekits-archive__loadmore-icon">
								<?php Icons_Manager::render_icon( $settings['load_more_selected_icon'], array( 'aria-hidden' => 'true' ) ); ?>
							</span>
					<?php endif; ?>

					<?php echo esc_html( $settings['load_more_button_text'] ); ?>

					<?php if ( ! empty( $settings['load_more_selected_icon']['value'] ) && $settings['load_more_icon_align'] === 'right' ) : ?>
						<span class="thim-ekits-archive__loadmore-icon">
								<?php Icons_Manager::render_icon( $settings['load_more_selected_icon'], array( 'aria-hidden' => 'true' ) ); ?>
							</span>
					<?php endif; ?>
				</a>
			<?php endif; ?>

			<?php if ( ! empty( $settings['load_more_spinner']['value'] ) ) : ?>
				<div class="thim-ekits-archive__loadmore-spinner lp-loading-no-css <?php echo $class_hide; ?>">
					<?php Icons_Manager::render_icon( $settings['load_more_spinner'], array( 'aria-hidden' => 'true' ) ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
