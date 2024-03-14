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
class Product_Single_Navigation_Element extends \Elementor\Widget_Base {

	public function get_name() {
		return 'product-single-navigation';
	}

	public function get_title() {
		return __( 'Product Single Navigation', 'kadence-woocommerce-elementor' );
	}

	public function get_icon() {
		return 'eicon-post-navigation';
	}

	public function get_categories() {
		return array( 'woocommerce-elements' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Product Single Navigation', 'kadence-woocommerce-elementor' ),
			)
		);
		$this->add_control(
			'important_note',
			array(
				'label' => __( 'Element Information', 'kadence-woocommerce-elementor' ),
				'show_label' => false,
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'This outputs the navigation for previous and next products.', 'kadence-woocommerce-elementor' ),
				'content_classes' => 'kadence-woo-ele-info',
			)
		);
		$this->add_control(
			'same_terms',
			[
				'label' => __( 'Link only within same categories', 'kadence-woocommerce-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'true',
				'options' => [
					'true' => __( 'True', 'kadence-woocommerce-elementor' ),
					'false' => __( 'False', 'kadence-woocommerce-elementor' ),
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Style Navigation', 'kadence-woocommerce-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'product_title_color',
			[
				'label'     => __( 'Product Title Color', 'kadence-woocommerce-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .kadence_product_nav_title_link, {{WRAPPER}} .kadence-next-product-link a, {{WRAPPER}} .kadence-previous-product-link a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'      => 'kadence_product_nav_title',
				'label'     => __( 'Typography', 'kadence-woocommerce-elementor' ),
				'selector'  => '{{WRAPPER}} .kadence_product_nav_title_link',
			)
		);
		$this->add_control(
			'product_meta_color',
			[
				'label'     => __( 'Previous/Next Label Color', 'kadence-woocommerce-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .kadence_product_nav_meta' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'      => 'kadence_product_nav_meta',
				'label'     => __( 'Typography', 'kadence-woocommerce-elementor' ),
				'selector'  => '{{WRAPPER}} .kadence_product_nav_meta',
			)
		);

		$this->end_controls_section();

	}


	protected function render() {

		$post_type = get_post_type();
		if ( 'product' == $post_type ) {
			$settings = $this->get_settings_for_display();
			global $post;
			echo '<div class="kadence-product-nav-section">';
					echo '<div class="kadence-post-navigation kwe-clearfix">';
						if ( 'true' === $settings['same_terms'] ) {
							$prev_post = get_adjacent_post( true, null, true, 'product_cat' );
						} else {
							$prev_post = get_adjacent_post( false, null, true );
						}
						if ( ! empty( $prev_post ) ) : 
				        	echo '<div class="kadence-previous-product-link">';
								echo '<a href="' . get_permalink( $prev_post->ID ) . '"><span class="kadence_product_nav_meta">' . __( 'Previous Product', 'kadence-woocommerce-elementor' ) . '</span><span class="kadence_product_nav_title_link">' . $prev_post->post_title . '</span>';
								echo '<svg class="kadence-previous-product-arrow" version="1.1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
									<title>' . __( 'Previous Product', 'kadence-woocommerce-elementor' ) . '</title>
									<path d="M10.5 16l1.5-1.5-6.5-6.5 6.5-6.5-1.5-1.5-8 8 8 8z"></path>
									</svg>';
									echo '</a>';
							echo '</div>';
				        endif;
						if ( 'true' === $settings['same_terms'] ) {
							$next_post = get_adjacent_post( true, null, false, 'product_cat');
						} else {
							$next_post = get_adjacent_post( false, null, false );
						}
				   		if ( ! empty( $next_post ) ) :
				   			echo '<div class="kadence-next-product-link">';
				        		echo '<a href="' . get_permalink( $next_post->ID ) . '"><span class="kadence_product_nav_meta">' . __( 'Next Product', 'kadence-woocommerce-elementor' ) . '</span><span class="kadence_product_nav_title_link">'. $next_post->post_title.'</span>';
				        			echo '<svg class="kadence-next-product-arrow" version="1.1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
									<title>' . __( 'Next Product', 'kadence-woocommerce-elementor' ) . '</title>
									<path d="M5.5 0l-1.5 1.5 6.5 6.5-6.5 6.5 1.5 1.5 8-8-8-8z"></path>
									</svg>';
									echo '</a>';
				        	echo '</div>';
				        endif;
					echo '</div> <!-- end navigation -->';
				echo '</div>';
	    } else if ( 'ele-product-template' == $post_type ) {
	    	echo '<div class="woocommerce"><div class="product">';
	    	echo ' <div class="kadence-product-nav-section"><div class="kadence-post-navigation kwe-clearfix"><div class="kadence-previous-product-link"><a href="#"><span class="kadence_product_nav_meta">' . __( 'Previous Product', 'kadence-woocommerce-elementor' ) . '</span><span class="kadence_product_nav_title_link">' . __( 'Example Product Title', 'kadence-woocommerce-elementor' ) . '</span><svg class="kadence-previous-product-arrow" version="1.1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
				<title>' . __( 'Previous Product', 'kadence-woocommerce-elementor' ) . '</title>
				<path d="M10.5 16l1.5-1.5-6.5-6.5 6.5-6.5-1.5-1.5-8 8 8 8z"></path>
				</svg></a></div><div class="kadence-next-product-link"><a href="#"><span class="kadence_product_nav_meta">' . __( 'Next Product', 'kadence-woocommerce-elementor' ) . '</span><span class="kadence_product_nav_title_link">' . __( 'Example Product Title', 'kadence-woocommerce-elementor' ) . '</span><svg class="kadence-next-product-arrow" version="1.1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
				<title>' . __( 'Next Product', 'kadence-woocommerce-elementor' ) . '</title>
				<path d="M5.5 0l-1.5 1.5 6.5 6.5-6.5 6.5 1.5 1.5 8-8-8-8z"></path>
				</svg></a></div></div>';
			echo '</div></div>';
	    }
	}

	protected function _content_template() {}
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Product_Single_Navigation_Element());
