<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

use Thim_EL_Kit\Elementor;
use Thim_EL_Kit\Settings;

class Thim_Ekit_Widget_Categories extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-categories';
	}

	public function get_title() {
		return esc_html__( 'Categories', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-product-categories';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY );
	}

	public function get_keywords() {
		return [
			'thim',
			'categories',
			'list categories',
		];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Thim Categories', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Select Category Type', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post_cate',
				'options' => array(
					'post_cate'    => esc_html__( 'Blog Categories', 'thim-elementor-kit' ),
					'product_cat' => esc_html__( 'Product Categories', 'thim-elementor-kit' ),
				),
			)
		);

		$this->add_control(
			'show_counts',
			array(
				'label'        => esc_html__( 'Show Category Counts', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'thim-elementor-kit' ),
				'label_off'    => esc_html__( 'Hide', 'thim-elementor-kit' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'label'        => esc_html__( 'Hide Empty Category', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'thim-elementor-kit' ),
				'label_off'    => esc_html__( 'No', 'thim-elementor-kit' ),
				'return_value' => 'false',
				'default'      => 'false',
			)
		);

		$this->end_controls_section();

		$this->register_section_style_category();

		$this->register_tab_style_counts();
	}

	protected function register_section_style_category() {
		$this->start_controls_section(
			'style_category',
			array(
				'label' => esc_html__( 'Style General', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .thim-categories-wrapper ul > li',
			)
		);

		$this->add_control(
			'cat_link_color',
			array(
				'label'     => esc_html__( 'Link Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-categories-wrapper ul > li a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'cat_link_color_hover',
			array(
				'label'     => esc_html__( 'Link Color Hover', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-categories-wrapper ul > li a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'cat_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-categories-wrapper ul > li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'cat_margin',
			array(
				'label'      => esc_html__( 'Margin', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-categories-wrapper ul > li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_tab_style_counts() {
		$this->start_controls_section(
			'style_category_counts',
			array(
				'label' => esc_html__( 'Category Counts', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,

			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'counts_typography',
				'selector' => '{{WRAPPER}} .thim-categories-wrapper ul > li span',
			)
		);

		$this->add_control(
			'color_counts',
			array(
				'label'     => esc_html__( 'Color Counts', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-categories-wrapper ul > li span' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'cat_align',
			array(
				'label'       => esc_html__( 'Display count', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'unset',
				'options'     => array(
					'unset'         => array(
						'title' => esc_html__( 'Default', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-left',
					),
					'space-between' => array(
						'title' => esc_html__( 'End', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .thim-categories-wrapper ul > li' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings   = $this->get_settings_for_display();
		$hide_empty = $settings['hide_empty'];

		if ( $settings['layout'] == 'post_cate' ) {
			$args = array(
				'type'       => 'post',
				'hide_empty' => $hide_empty,
				'parent'     => 0,
			);

			$categories = get_categories( $args );

		} elseif ( $settings['layout'] == 'product_cat' ) {
			$orderby  = 'name';
			$order    = 'asc';
			$cat_args = array(
				'orderby'    => $orderby,
				'order'      => $order,
				'hide_empty' => $hide_empty,
			);

			$categories = get_terms( 'product_cat', $cat_args );
		}

		?>

		<div class="thim-categories-wrapper">
			<ul class="thim-categories-nav">
				<?php foreach ( $categories as $category ) { ?>
					<li class="thim-categories-items">
						<?php if ( $settings['layout'] == 'post_cate' ) { ?>
							<a href="<?php echo esc_url( get_term_link( $category->slug, 'category' ) ); ?>"> <?php echo esc_attr( $category->name ); ?> </a>
							<?php
						} elseif ( $settings['layout'] == 'product_cat' ) {
							?>
							<a href="<?php echo esc_url( get_term_link( $category ) ); ?>"> <?php echo esc_attr( $category->name ); ?> </a>
						<?php } ?>

						<?php if ( $settings['show_counts'] == 'yes' ) { ?>
							<span class="count"><?php echo esc_attr( $category->count ); ?></span>
						<?php } ?>

					</li>
				<?php } ?>
			</ul>
		</div>

		<?php
	}
}
