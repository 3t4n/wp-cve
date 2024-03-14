<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class BOPO_Elementor_Bundle_Widget extends Elementor\Widget_Base {
	public static $slug = 'bopobb-elementor-bundle-widget';

	public function get_name() {
		return 'woo-bopo-bundle';
	}

	public function get_title() {
		return esc_html__( 'Bopo Bundle', 'woo-bopo-bundle' );
	}

	public function get_icon() {
		return 'eicon-lightbox';
	}

	public function get_categories() {
		return [ 'woocommerce-elements' ];
	}

	protected function _register_controls() {
		$bopobb_settings = VI_WOO_BOPO_BUNDLE_DATA::get_instance();
		$query_args      = array(
			'post_type' => 'product',
			'tax_query' => array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => 'bopobb',
				),
			),
		);
		$the_query       = new WP_Query( $query_args );
		$bundles_of_bopo = array(
			'' => 'Current product'
		);
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$prd                             = wc_get_product( get_the_ID() );
				$bundles_of_bopo[ get_the_ID() ] = '[' . get_the_ID() . '] ' . $prd->get_title();
				wp_reset_postdata();
			}
		}
		$this->start_controls_section(
			'general',
			[
				'label' => esc_html__( 'General', 'woo-bopo-bundle' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'bundle_id',
			[
				'label'       => esc_html__( 'Bundle to show', 'woo-bopo-bundle' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => __( 'Which bundle display in this shortcode?', 'woo-bopo-bundle' ),
				'default'     => '',
				'options'     => $bundles_of_bopo,
				'label_block' => false,
			]
		);
		$this->add_control(
			'bundle_title',
			[
				'label'       => esc_html__( 'Bundle title', 'woo-bopo-bundle' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => [
					'active' => false,
				],
			]
		);
		$this->add_control(
			'bundle_template',
			[
				'label'       => esc_html__( 'Bundle template', 'woo-bopo-bundle' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'vertical-bundle',
				'options'     => [
					'vertical-bundle'   => esc_html__( 'Vertical bundle', 'woo-bopo-bundle' ),
					'horizontal-bundle' => esc_html__( 'Horizontal bundle', 'woo-bopo-bundle' ),
				],
				'label_block' => false,
			]
		);
		$this->add_control(
			'bundle_cols',
			[
				'label'       => esc_html__( 'Bundle column', 'woo-bopo-bundle' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '3',
				'options'     => [
					'2' => esc_html__( '2', 'woo-bopo-bundle' ),
					'3' => esc_html__( '3', 'woo-bopo-bundle' ),
					'4' => esc_html__( '4', 'woo-bopo-bundle' ),
				],
				'label_block' => false,
				'condition'   => [
					'bundle_template' => 'vertical-bundle',
				]
			]
		);
		$this->end_controls_section();
	}

	protected function register_controls() {
		$this->_register_controls();
	}

	public function get_shortcode_text() {
		$settings = $this->get_settings_for_display();

		return "[bopobb_bundle id='{$settings['bundle_id']}' template='{$settings['bundle_template']}' column='{$settings['bundle_cols']}' title='{$settings['bundle_title']}']";
	}

	protected function render() {
		echo do_shortcode( $this->get_shortcode_text() );
	}

	public function render_plain_content() {
		echo $this->get_shortcode_text();
	}
}