<?php

namespace Elementor;

use Elementor\Controls_Manager;

use Elementor\Group_Control_Typography;
use Thim_EL_Kit\Utilities\Widget_Loop_Trait;

defined( 'ABSPATH' ) || exit;

class Thim_Ekit_Widget_Loop_Product_Ratting extends Widget_Base {

	use Widget_Loop_Trait;

	public function get_name() {
		return 'thim-loop-product-ratting';
	}

	public function show_in_panel() {
		$post_type = get_post_meta( get_the_ID(), 'thim_loop_item_post_type', true );
		if ( ! empty( $post_type ) && $post_type == 'product' ) {
			return true;
		}

		return false;
	}

	public function get_title() {
		return esc_html__( 'Product Rating', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-product-rating';
	}

	public function get_keywords() {
		return array( 'product', 'ratting' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_product_rating_style',
			array(
				'label' => esc_html__( 'Style', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'star_color',
			array(
				'label'     => esc_html__( 'Star Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.thim-ekits-loop-ratting .star-rating span:before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'empty_star_color',
			array(
				'label'     => esc_html__( 'Empty Star Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.thim-ekits-loop-ratting .star-rating::before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'star_size',
			array(
				'label'     => esc_html__( 'Star Size', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => 'em',
				),
				'range'     => array(
					'em' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}.thim-ekits-loop-ratting .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'show_number',
			array(
				'label'   => esc_html__( 'Number Review', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes'
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'text_typography',
				'selector'  => '{{WRAPPER}} .number-ratting',
				'condition' => array(
					'show_number' => 'yes',
				),
			)
		);

		$this->add_control(
			'space_between',
			array(
				'label'      => esc_html__( 'Space Between', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'unit' => 'em',
				),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .number-ratting' => 'margin-left: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'show_number' => 'yes',
				),
			)
		);
		$this->add_control(
			'number_color',
			array(
				'label'     => esc_html__( 'Number Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .number-ratting' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$product = wc_get_product( false );

 		if ( ! $product ) {
			return;
		}

		if ( ! wc_review_ratings_enabled() ) {  
			return;
		}

		$this->set_render_attribute( '_wrapper', 'class', 'thim-ekits-loop-ratting' );

		echo wc_get_rating_html( $product->get_average_rating() ); 

		if ( $settings['show_number'] == 'yes' ) {
			$count_review = $product->get_review_count();
			if($count_review > 0){
				echo '<span class="number-ratting">(';
				printf( _n( '%s review', '%s reviews', $count_review, 'thim-elementor-kit' ), $count_review );
				echo ')</span>';
			}
		}
	}

	public function render_plain_content() {
	}
}
