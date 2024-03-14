<?php
namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Course_Category extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-course-category';
	}

	public function get_title() {
		return esc_html__( ' Course Categories', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-post';
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
			'category_singular',
			array(
				'label'       => esc_html__( 'Singular', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Category:', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'category_plural',
			array(
				'label'       => esc_html__( 'Plural', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Categories:', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'separator',
			array(
				'label'       => esc_html__( 'Separator', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => ', ',
				'description' => esc_html__( 'String to use between the category.', 'thim-elementor-kit' ),
			)
		);
		$this->add_control(
			'category_icon',
			array(
				'label'       => esc_html__( 'Icon', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'separator'   => 'before',
			)
		);
		$this->end_controls_section();

		$this->register_style_control();
	}

	protected function register_style_control() {
		$this->start_controls_section(
			'section_style_category',
			array(
				'label' => esc_html__( 'Category', 'thim-elementor-kit' ),
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
					'{{WRAPPER}} .thim-ekit-single-course__category__label' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-course__category__label',
			)
		);

		$this->add_responsive_control(
			'label_margin',
			array(
				'label'      => esc_html__('Margin', 'thim-elementor-kit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array('px', 'em'),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekit-single-course__category__label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
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
					'{{WRAPPER}} .thim-ekit-single-course__category__content, {{WRAPPER}} .thim-ekit-single-course__category__content > *' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'content_color_hover',
			array(
				'label'     => esc_html__( 'Color Hover', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__category__content a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-course__category__content, {{WRAPPER}} .thim-ekit-single-course__category__content > *',
			)
		);

		$this->end_controls_section();
	}

	public function render() {
		do_action( 'thim-ekit/modules/single-course/before-preview-query' );

		$settings = $this->get_settings_for_display();

		$terms = get_the_terms( '', 'course_category' );

		if ( is_wp_error( $terms ) ) {
			return;
		}

		if ( empty( $terms ) ) {
			return;
		}

		$label = ! empty( $settings['category_singular'] ) ? $settings['category_singular'] : esc_html__( 'Category:', 'thim-elementor-kit' );

		if ( count( $terms ) > 1 ) {
			$label = ! empty( $settings['category_plural'] ) ? $settings['category_plural'] : esc_html__( 'Categories:', 'thim-elementor-kit' );
		}

		$categories = get_the_term_list( '', 'course_category', '', $settings['separator'] );

		if ( empty( $categories ) ) {
			return;
		}
		?>

		<div class="thim-ekit-single-course__category">
			<span class="thim-ekit-single-course__category__label"><?php echo esc_html( $label ); ?></span>
			<span class="thim-ekit-single-course__category__content">
				<?php echo wp_kses_post( $categories ); ?>
			</span>
		</div>

		<?php
		do_action( 'thim-ekit/modules/single-course/after-preview-query' );
	}
}
