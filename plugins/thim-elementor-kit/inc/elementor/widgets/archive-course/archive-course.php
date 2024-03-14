<?php
namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use LP_Course;
use LP_Course_Filter;
use Thim_EL_Kit\GroupControlTrait;

if ( ! class_exists( '\Elementor\Thim_Ekits_Course_Base' ) ) {
	include THIM_EKIT_PLUGIN_PATH . 'inc/elementor/widgets/global/course-base.php';
}

class Thim_Ekit_Widget_Archive_Course extends Thim_Ekits_Course_Base {
	use GroupControlTrait;
	protected $current_permalink;

	public $is_skeleton = false;

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


		$this->register_style_pagination_archive('.thim-ekits-archive-course__pagination');
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

		$this->add_control(
			'template_id',
			array(
				'label'         => esc_html__( 'Choose a template', 'thim-elementor-kit' ),
				'type'          => Controls_Manager::SELECT2,
				'default'       => '0',
				'options'       => array( '0' => esc_html__( 'None', 'thim-elementor-kit' ) ) + \Thim_EL_Kit\Functions::instance()->get_pages_loop_item( 'lp_course' ),
				//				'frontend_available' => true,
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
 					'result'    => 'Result Count',
					'order'     => 'Order',
					'search'    => 'Search',
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
					'{{WRAPPER}} .thim-ekits-archive-course__topbar__search button' => 'min-width: {{SIZE}}{{UNIT}};',
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

	public function render() {
		global $post;

		$filter = new \LP_Course_Filter();

		$total_rows = 0;
		$data_attr  = '';
		$settings   = $this->get_settings_for_display();

		if ( $settings['is_ajax'] === 'yes' ) {
			$this->is_skeleton = true;
			$data_atts         = array(
				'settings' => array(
					'show_image'                 => $settings['show_image'],
					'image_size'                 => $settings['image_size_size'],
					'meta_data_inner_image'      => $settings['meta_data_inner_image'],
					'read_more_text_inner_image' => $settings['read_more_text_inner_image'],
					'thim_header_repeater'       => $settings['thim_header_repeater'],
					'repeater'                   => $settings['repeater'],
					'open_new_tab'               => $settings['open_new_tab'],
					'pagination_type'            => $settings['pagination_type'],
					'pagination_numbers_shorten' => $settings['pagination_numbers_shorten'],
					'pagination_prev_label'      => $settings['pagination_prev_label'],
					'pagination_next_label'      => $settings['pagination_next_label'],
					'build_loop_item' 		  => $settings['build_loop_item'],
					'template_id' 			  => $settings['template_id'],
				),
			);
			$data_attr         = ' data-atts="' . htmlentities( json_encode( $data_atts ) ) . '"';
		}

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->is_skeleton = false;
		}

		if ( ! $this->is_skeleton ) {
			$param          = $_GET ?? [];
			$param['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			$filter         = new LP_Course_Filter();

			if ( learn_press_is_course_category() || learn_press_is_course_tag() ) {
				$cat = get_queried_object();

				$param['term_id']  = $cat->term_id;
				$param['taxonomy'] = $cat->taxonomy;
			}

			if ( method_exists( 'LP_Course', 'handle_params_for_query_courses' ) ) {
				LP_course::handle_params_for_query_courses( $filter, $param );
			}
			$courses = LP_Course::get_courses( $filter, $total_rows );
		}
		?>

		<div class="thim-ekits-archive-course thim-ekits-course <?php echo esc_attr( $this->is_skeleton ? 'thim-ekits-archive-course__skeleton' : '' ); ?>"<?php echo wp_kses_post( $data_attr ); ?>>

			<?php
				if ( ! empty( $courses ) ) {
					$this->render_topbar( $filter, $total_rows, $settings );
				}
				?>

			<div class="thim-ekits-course__inner">
				<?php
				if ( ! $this->is_skeleton ) {
					if ( $courses ) {
						foreach ( $courses as $course_id ) {
							$post = get_post( $course_id );
							setup_postdata( $post );

							$this->current_permalink = get_permalink();
							$this->render_course( $settings, 'thim-ekits-course__item' );
						}
					}else{
						echo '<div class="courses-not-found"><span>'.__('No courses found','thim-elementor-kit').'</span></div>';
					}
				}

				wp_reset_postdata();
				?>
			</div>

			<?php
			if ( ! $this->is_skeleton ) {
				$this->render_loop_footer( $filter, $total_rows, $settings );
			}
			?>
		</div>

		<?php
	}

	public function render_topbar( $filter, $total_rows, $settings ) {
		if ( $settings['thim_header_repeater'] ) {
			?>
			<div class="thim-ekits-archive-course__topbar">
				<?php
				foreach ( $settings['thim_header_repeater'] as $item ) {
					switch ( $item['header_key'] ) {
 						case 'result':
							$this->render_result_count( $filter, $total_rows, $item );
							break;
						case 'order':
							$this->render_orderby( $item );
							break;
						case 'search':
							$this->render_search( $item );
							break;
					}
				}
				?>
			</div>
			<?php
		}
	}

	public function render_result_count( $filter, $total_rows, $settings ) {
		if ( ! $this->is_skeleton ) {
			$from = 1 + ( $filter->page - 1 ) * $filter->limit;
			$to   = ( $filter->page * $filter->limit > $total_rows ) ? $total_rows : $filter->page * $filter->limit;
		}
		?>
		<span class="thim-ekits-archive-course__topbar__result">
			<?php
			if ( ! $this->is_skeleton ) {
				if ( 0 === $total_rows ) {
					echo '';
				} elseif ( 1 === $total_rows ) {
					echo esc_html__( 'Showing only one result', 'thim-elementor-kit' );
				} else {
					if ( $from == $to ) {
						echo sprintf( esc_html__( 'Showing last course of %s results', 'thim-elementor-kit' ), $total_rows );
					} else {
						$from_to = absint( $from ) . '-' . absint( $to );
						echo sprintf( esc_html__( 'Showing %1$s of %2$s results', 'thim-elementor-kit' ), $from_to, absint( $total_rows ) );
					}
				}
			}
			?>
		</span>
		<?php
	}

	public function render_orderby( $settings ) {
		$catalog_orderby_options = apply_filters(
			'thim_ekit_archive_course_catalog_orderby',
			array(
				'menu_order' => esc_html__( 'Default sorting', 'thim-elementor-kit' ),
				'post_date'  => esc_html__( 'Sort by latest', 'thim-elementor-kit' ),
				'post_title' => esc_html__( 'Sort by title', 'thim-elementor-kit' ),
			)
		);

		$orderby = isset( $_GET['order_by'] ) ? sanitize_text_field( wp_unslash( $_GET['order_by'] ) ) : 'post_date';

		if ( ! array_key_exists( $orderby, $catalog_orderby_options ) ) {
			$orderby = current( array_keys( $catalog_orderby_options ) );
		}
		?>
		<form class="thim-ekits-archive-course__topbar__orderby " method="get">
			<select name="order_by" class="orderby courses-order-by" aria-label="<?php esc_attr_e( 'Course order', 'thim-elementor-kit' ); ?>">
				<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
					<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
				<?php endforeach; ?>
			</select>
			<input type="hidden" name="paged" value="1">
		</form>
		<?php
	}

	public function render_search( $settings ) {
		?>
		<form class="thim-ekits-archive-course__topbar__search" method="get">
			<input type="search" name="c_search" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr( $settings['placeholder'] ); ?>">
			<button type="submit">
				<?php Icons_Manager::render_icon( $settings['search_icon'] ); ?>
			</button>
		</form>
		<?php
	}

	public function render_loop_footer( $filter, $total_rows, $settings ) {
		$ajax_pagination = in_array( $settings['pagination_type'], array( 'load_more_on_click', 'load_more_infinite_scroll' ), true );

		if ( '' === $settings['pagination_type'] ) {
			return;
		}

		$page_limit = \LP_Database::get_total_pages( $filter->limit, $total_rows );

		if ( 2 > $page_limit ) {
			return;
		}

		$has_numbers   = in_array( $settings['pagination_type'], array( 'numbers', 'numbers_and_prev_next' ) );
		$has_prev_next = in_array( $settings['pagination_type'], array( 'prev_next', 'numbers_and_prev_next' ) );

		$load_more_type = $settings['pagination_type'];

		if ( $settings['pagination_type'] === '' ) {
			$paged = 1;
		} else {
			$paged = $filter->page;
		}

		$current_page = $this->get_current_page( $settings );
		$next_page = intval( $current_page ) + 1;

		if ( $ajax_pagination ) {
			$this->render_load_more_pagination( $settings, $load_more_type, $paged, $page_limit, $next_page );
			return;
		}

		$links = array();

		if ( $has_numbers ) {
			$paginate_args = array(
				'type'               => 'array',
				'current'            => $paged,
				'total'              => $page_limit,
				'prev_next'          => false,
				'show_all'           => 'yes' !== $settings['pagination_numbers_shorten'],
				'before_page_number' => '<span class="elementor-screen-only">' . esc_html__( 'Page', 'thim-elementor-kit' ) . '</span>',
			);

			if ( is_singular() && ! is_front_page() ) {
				global $wp_rewrite;

				if ( $wp_rewrite->using_permalinks() ) {
					$paginate_args['base']   = trailingslashit( get_permalink() ) . '%_%';
					$paginate_args['format'] = user_trailingslashit( '%#%', 'single_paged' );
				} else {
					$paginate_args['format'] = '?page=%#%';
				}
			}

			$links = paginate_links( $paginate_args );
		}

		if ( $has_prev_next ) {
			$prev_next = $this->get_posts_nav_link( $filter, $total_rows, $paged, $page_limit, $settings );
			array_unshift( $links, $prev_next['prev'] );
			$links[] = $prev_next['next'];
		}
		?>
		<nav class="thim-ekits-archive-course__pagination" aria-label="<?php esc_attr_e( 'Pagination', 'thim-elementor-kit' ); ?>">
			<?php echo wp_kses_post( implode( PHP_EOL, $links ) ); ?>
		</nav>
		<?php
	}

	public function get_posts_nav_link( $filter, $total_rows, $paged, $page_limit = null, $settings = array() ) {
		if ( ! $page_limit ) {
			$page_limit = \LP_Database::get_total_pages( $filter->limit, $total_rows );
		}

		$return = array();

		$link_template     = '<a class="page-numbers %s" href="%s">%s</a>';
		$disabled_template = '<span class="page-numbers %s">%s</span>';

		if ( $paged > 1 ) {
			$next_page = intval( $paged ) - 1;

			if ( $next_page < 1 ) {
				$next_page = 1;
			}

			$return['prev'] = sprintf( $link_template, 'prev', $this->get_wp_link_page( $next_page ), $settings['pagination_prev_label'] );
		} else {
			$return['prev'] = sprintf( $disabled_template, 'prev', $settings['pagination_prev_label'] );
		}

		$next_page = intval( $paged ) + 1;

		if ( $next_page <= $page_limit ) {
			$return['next'] = sprintf( $link_template, 'next', $this->get_wp_link_page( $next_page ), $settings['pagination_next_label'] );
		} else {
			$return['next'] = sprintf( $disabled_template, 'next', $settings['pagination_next_label'] );
		}

		return $return;
	}

	public function get_current_page( $settings ) {
		if ( '' === $settings['pagination_type'] ) {
			return 1;
		}

		return max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
	}

	private function get_wp_link_page( $i ) {
		if ( ! is_singular() || is_front_page() ) {
			return get_pagenum_link( $i );
		}

		// Based on wp-includes/post-template.php:957 `_wp_link_page`.
		global $wp_rewrite;
		$post       = get_post();
		$query_args = array();
		$url        = get_permalink();

		if ( $i > 1 ) {
			if ( '' === get_option( 'permalink_structure' ) || in_array( $post->post_status, array( 'draft', 'pending' ) ) ) {
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

}
