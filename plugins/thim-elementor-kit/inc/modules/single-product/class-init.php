<?php
namespace Thim_EL_Kit\Modules\SingleProduct;

use Thim_EL_Kit\Custom_Post_Type;
use Thim_EL_Kit\Modules\Modules;
use Thim_EL_Kit\SingletonTrait;

class Init extends Modules {
	use SingletonTrait;

	public function __construct() {
		$this->tab      = 'single-product';
		$this->tab_name = esc_html__( 'Single Product', 'thim-elementor-kit' );

		parent::__construct();
//		add_theme_support( 'wc-product-gallery-slider' );

		add_action( 'elementor/frontend/before_get_builder_content', array( $this, 'before_get_content' ) );
		add_action( 'elementor/frontend/get_builder_content', array( $this, 'after_get_content' ) );
	}

	public function template_include( $template ) {
		if ( ! class_exists( '\WooCommerce' ) ) {
			return $template;
		}

		$this->template_include = is_product();

		return parent::template_include( $template );
	}

	public function get_preview_id() {
		global $post;

		$output = false;

		if ( $post ) {
			$document = \Elementor\Plugin::$instance->documents->get( $post->ID );

			if ( $document ) {
				$preview_id = $document->get_settings( 'thim_ekits_preview_id' );

				$output = ! empty( $preview_id ) ? absint( $preview_id ) : false;
			}
		}

		return $output;
	}

	public function before_preview_query() {
		$preview_id = $this->get_preview_id();

		if ( $preview_id ) {
			$query = array(
				'p'         => absint( $preview_id ),
				'post_type' => 'product',
			);
		} else {
			$query_vars = array(
				'post_type'      => 'product',
				'posts_per_page' => 1,
			);

			$posts = get_posts( $query_vars );

			if ( ! empty( $posts ) ) {
				$query = array(
					'p'         => $posts[0]->ID,
					'post_type' => 'product',
				);
			}
		}

		if ( ! empty( $query ) ) {
			\Elementor\Plugin::instance()->db->switch_to_query( $query, true );
		}
	}

	public function before_get_content() {
		if ( ! class_exists( '\WooCommerce' ) ) {
			return;
		}

		if ( ! get_the_ID() ) {
			return;
		}

		if ( ! is_product() ) {
			return;
		}

		$type = get_post_meta( get_the_ID(), Custom_Post_Type::TYPE, true );

		if ( $type === $this->tab || ! empty( $option ) ) {
			$option = $this->get_layout_id( $this->tab );

			if ( ! empty( $option ) ) {
				global $product;

				if ( ! is_object( $product ) ) {
					$product = wc_get_product( get_the_ID() );
				}

				do_action( 'woocommerce_before_single_product' );
			}
		}
	}

	public function after_get_content() {
		if ( ! class_exists( '\WooCommerce' ) ) {
			return;
		}

		if ( ! get_the_ID() ) {
			return;
		}

		if ( ! is_product() ) {
			return;
		}

		$type = get_post_meta( get_the_ID(), Custom_Post_Type::TYPE, true );

		if ( $type === $this->tab ) {
			$option = $this->get_layout_id( $this->tab );

			if ( ! empty( $option ) ) {
				wp_reset_postdata();

				do_action( 'woocommerce_after_single_product' );
			}
		}
	}

	public function is( $condition ) {
		switch ( $condition['type'] ) {
			case 'all':
				return is_singular( 'product' );
			case 'product_id':
				return is_singular( 'product' ) && get_the_ID() === (int) $condition['query'];
			case 'product_category':
				$terms = get_the_terms( get_the_ID(), 'product_cat' );
				if ( ! $terms ) {
					return false;
				}

				$term_ids = wp_list_pluck( $terms, 'term_id' );

				return in_array( (int) $condition['query'], $term_ids );
			case 'product_tag':
				$terms = get_the_terms( get_the_ID(), 'product_tag' );
				if ( ! $terms ) {
					return false;
				}

				$term_ids = wp_list_pluck( $terms, 'term_id' );

				return in_array( (int) $condition['query'], $term_ids );
		}

		return false;
	}

	public function priority( $type ) {
		$priority = 100;

		switch ( $type ) {
			case 'all':
				$priority = 10;
				break;
			case 'product_category':
				$priority = 20;
				break;
			case 'product_tag':
				$priority = 30;
				break;
			case 'product_id':
				$priority = 40;
				break;
		}

		return apply_filters( 'thim_ekit/condition/priority', $priority, $type );
	}

	public function get_conditions() {
		return array(
			array(
				'label'    => esc_html__( 'All products', 'thim-elementor-kit-pro' ),
				'value'    => 'all',
				'is_query' => false,
			),
			array(
				'label'    => esc_html__( 'Select product', 'thim-elementor-kit-pro' ),
				'value'    => 'product_id',
				'is_query' => true,
			),
			array(
				'label'    => esc_html__( 'Product category', 'thim-elementor-kit-pro' ),
				'value'    => 'product_category',
				'is_query' => true,
			),
			array(
				'label'    => esc_html__( 'Product tag', 'thim-elementor-kit-pro' ),
				'value'    => 'product_tag',
				'is_query' => true,
			),
		);
	}
}

Init::instance();
