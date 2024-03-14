<?php
/**
 * Build Elementor Element
 *
 * @package Kadence Woocommerce Elementor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Element Product Single Category
 */
class Product_Single_Category_Element extends \Elementor\Widget_Base {

	public function get_name() {
		return 'product-single-category';
	}

	public function get_title() {
		return __( 'Product Single Category', 'kadence-woocommerce-elementor' );
	}

	public function get_icon() {
		return 'eicon-folder';
	}

	public function get_categories() {
		return array( 'woocommerce-elements' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Product Single Category', 'kadence-woocommerce-elementor' ),
			)
		);
		$this->add_control(
			'important_note',
			array(
				'label' => __( 'Element Information', 'kadence-woocommerce-elementor' ),
				'show_label' => false,
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'This outputs the products category name.', 'kadence-woocommerce-elementor' ),
				'content_classes' => 'kadence-woo-ele-info',
			)
		);
		$this->add_control(
			'category_link',
			[
				'label' => __( 'Output Options', 'kadence-woocommerce-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'name',
				'options' => [
					'name' => __( 'Just Name', 'kadence-woocommerce-elementor' ),
					'link' => __( 'Name is link to Category', 'kadence-woocommerce-elementor' ),
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Style Category', 'kadence-woocommerce-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'product_single_cat_color',
			[
				'label'     => __( 'Text Color', 'kadence-woocommerce-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product_title_cat, {{WRAPPER}} .product_title_cat a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'      => 'product_single_cat_typography',
				'label'     => __( 'Typography', 'kadence-woocommerce-elementor' ),
				'selector'  => '{{WRAPPER}} .product_title_cat, {{WRAPPER}} .product_title_cat a',
			)
		);

		$this->add_responsive_control(
			'product_single_cat_align',
			[
				'label'        => __( 'Alignment', 'kadence-woocommerce-elementor' ),
				'type'         => \Elementor\Controls_Manager::CHOOSE,
				'options'      => [
					'left'   => [
						'title' => __( 'Left', 'kadence-woocommerce-elementor' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'kadence-woocommerce-elementor' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'kadence-woocommerce-elementor' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default'      => 'left',
			]
		);

		$this->end_controls_section();

	}


	protected function render() {

		$post_type = get_post_type();
		if ( 'product' == $post_type ) {
			$settings = $this->get_settings_for_display();
			global $post;
			echo '<div class="product_title_cat">';
				// check if yoast category set and there is a primary
				if (class_exists('WPSEO_Primary_Term') ) {
					$WPSEO_term = new WPSEO_Primary_Term('product_cat', $post->ID);
					$WPSEO_term = $WPSEO_term->get_primary_term();
					$WPSEO_term = get_term($WPSEO_term);
					if (is_wp_error($WPSEO_term)) { 
						if ( $terms = wp_get_post_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
							$main_term = $terms[0];
						}
					} else {
						$main_term = $WPSEO_term;
					}
					if( 'link' == $settings['category_link'] ) {
						echo '<a href="' . esc_url( get_term_link( $main_term->slug, 'product_cat' ) ) . '" class="product_title_cat_link">'; 
					} 
					echo $main_term->name;
					if( 'link' == $settings['category_link'] ) {
						echo '</a>'; 
					} 
				} else if ( $terms = wp_get_post_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
					$main_term = $terms[0];
					if( 'link' == $settings['category_link'] ) {
						echo '<a href="' . esc_url( get_term_link( $main_term->slug, 'product_cat' ) ) . '" class="product_title_cat_link">'; 
					} 
					echo $main_term->name;
					if( 'link' == $settings['category_link'] ) {
						echo '</a>'; 
					} 
				}
			echo '</div>';
	    } else if ( 'ele-product-template' == $post_type ) {
	    	echo '<div class="woocommerce"><div class="product">';
	    	echo '<div class="product_title_cat">' . __('Example Category', 'kadence-woocommerce-elementor').'</div>';
			echo '</div></div>';
	    }
	}

	protected function _content_template() {}
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Product_Single_Category_Element());
