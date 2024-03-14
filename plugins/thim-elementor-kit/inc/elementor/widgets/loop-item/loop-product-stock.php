<?php

namespace Elementor;

use Thim_EL_Kit\Custom_Post_Type;
use Thim_EL_Kit\Utilities\Widget_Loop_Trait;

class Thim_Ekit_Widget_Loop_Product_Stock extends Widget_Base {
	use Widget_Loop_Trait;

	public function get_name() {
		return 'thim-ekits-product-stock';
	}

	public function get_title() {
		return esc_html__( 'Product Stock', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-product-stock';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'stock', 'quantity', 'product' ];
	}

	public function show_in_panel() {
		$type      = get_post_meta( get_the_ID(), Custom_Post_Type::TYPE, true );
		$post_type = get_post_meta( get_the_ID(), 'thim_loop_item_post_type', true );

		if ( ( ! empty( $post_type ) && $post_type == 'product' ) || $type == 'single-product' ) {
			return true;
		}

		return false;
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {

		if ( get_option( 'woocommerce_stock_format' ) == 'low_amount' ) {
			$this->register_controls_progress_bar();
		}

		$this->start_controls_section(
			'section_product_stock_style',
			array(
				'label' => esc_html__( 'Style', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Stock Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-product__stock .stock' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'out_of_stock_color',
			array(
				'label'     => esc_html__( 'Out of stock Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-product__stock .stock.out-of-stock' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .thim-ekit-single-product__stock .stock',
			)
		);

		$this->end_controls_section();
	}

	public function register_controls_progress_bar() {
		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Progress', 'storepify' ),
			]
		);
		$this->add_control(
			'show_progress',
			[
				'label'        => esc_html__( 'Show Progress', 'storepify' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'storepify' ),
				'label_off'    => esc_html__( 'Off', 'storepify' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_progress_style',
			array(
				'label'     => esc_html__( 'Progress', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_progress' => 'yes',
				),
			)
		);
		$this->add_control(
			'progress_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-product__stock .progress span' => 'background: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'progress_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-product__stock .progress' => 'background: {{VALUE}}',
				),
			)
		);
		$this->add_responsive_control(
			'progress_height',
			array(
				'label'      => esc_html__( 'Height', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekit-single-product__stock .progress' => 'height: {{SIZE}}{{UNIT}} !important',
				),
			)
		);
		$this->add_responsive_control(
			'progress_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekit-single-product__stock .progress' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_control(
			'progress_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekit-single-product__stock .progress'      => 'border-radius: {{SIZE}}px;',
					'{{WRAPPER}} .thim-ekit-single-product__stock .progress span' => 'border-radius: {{SIZE}}px;',
				),
			)
		);
		$this->end_controls_section();
	}

	public function render() {
		do_action( 'thim-ekit/modules/single-product/before-preview-query' );

		$product = wc_get_product( false );

		if ( ! $product ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		?>

		<div class="thim-ekit-single-product__stock">
			<?php
			if ( get_option( 'woocommerce_stock_format' ) == 'low_amount' ) {
				if ( $settings['show_progress'] == 'yes' ) {
					$quantity_stock = $product->get_stock_quantity();
					if ( isset( $quantity_stock ) && $quantity_stock <= wc_get_low_stock_amount( $product ) ) {
						echo '<div class="progress"><span style="width: ' . $quantity_stock / wc_get_low_stock_amount( $product ) * 100 . '%"></span></div>';
					}
				}
			}
			?>
			<?php echo wc_get_stock_html( $product ); ?>
		</div>
		<?php
		do_action( 'thim-ekit/modules/single-product/after-preview-query' );
	}

	public function render_plain_content() {
	}
}
