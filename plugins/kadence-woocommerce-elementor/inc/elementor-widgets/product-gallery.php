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
 * Elementor Element Product Gallery
 */
class Product_Gallery_Element extends \Elementor\Widget_Base {

	public function get_name() {
		return 'product-gallery';
	}

	public function get_title() {
		return __( 'Product Gallery', 'kadence-woocommerce-elementor' );
	}

	public function get_icon() {
		return 'eicon-image';
	}

	public function get_categories() {
		return array( 'woocommerce-elements' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Product Gallery', 'kadence-woocommerce-elementor' ),
			)
		);
		$this->add_control(
			'important_note',
			array(
				'label' => __( 'Element Information', 'kadence-woocommerce-elementor' ),
				'show_label' => false,
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'This outputs the products gallery.', 'kadence-woocommerce-elementor' ),
				'content_classes' => 'kadence-woo-ele-info',
			)
		);

		$this->end_controls_section();

	}


	protected function render() {
		$post_type = get_post_type();
		if ( 'product' == $post_type ) {
			echo '<div class="product-img-case" style="width:auto;">';
				global $kt_product_gallery, $kt_woo_extras, $product;
				if ( isset( $kt_woo_extras ) && isset( $kt_woo_extras['product_gallery'] ) && $kt_woo_extras['product_gallery'] == 1 ) {
					woocommerce_show_product_sale_flash();
					$kt_product_gallery->kt_woo_product_gallery();
				} else {
					woocommerce_show_product_sale_flash();
					woocommerce_show_product_images();
				}
			echo '</div>';
	    } else if ( 'ele-product-template' == $post_type ) {
	    	echo '<div class="woocommerce"><div class="product">';
	    	$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
	    	$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
				'woocommerce-product-gallery',
				'woocommerce-product-gallery--columns-' . absint( $columns ),
				'images',
			) );
	    	?>
			<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 1; transition: opacity .25s ease-in-out; width:auto; float:none;">
				<figure class="woocommerce-product-gallery__wrapper">
					<?php
						$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
						$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
						$html .= '</div>';

					echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, null );

					?>
				</figure>
			</div>
			<?php
			echo '</div></div>';
	    } else {
	    	echo  __('This element is not designed for this post type', 'kadence-woocommerce-elementor' );
	    }
	}

	protected function _content_template() {}
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Product_Gallery_Element());
