<?php
/**
 * The Template for products comparison page
 * This template can be overridden by copying it to yourtheme/fami-wccp/compare-table.php.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WPML Suppot:  Localize Ajax Call
 */
global $sitepress;

//$lang = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : false;
//
//if ( defined( 'ICL_LANGUAGE_CODE' ) && $lang && isset( $sitepress ) ) {
//	$sitepress->switch_lang( $lang, false );
//}

$all_settings    = Fami_Woocompare_Helper::get_all_settings();
$compare_fields  = Fami_Woocompare_Helper::get_selected_compare_fields_with_texts();
$CompareFrontend = new Fami_Woocompare_Frontend();
$products_list   = isset( $args['products_list'] ) ? $CompareFrontend->get_products_list( $args['products_list'] ) : $CompareFrontend->get_products_list();
$total_products  = count( $products_list );
$total_slots     = $total_products < 3 ? 3 : $total_products + 1;

$have_compare = $compare_fields && $total_products;


?>

<div class="fami-wccp-content-wrap">
	<?php
	if ( $have_compare ) {
		$html                 = '';
		$field_name_cols_html = '';
		$products_cols_html   = '';
		$compare_slider       = $all_settings['compare_slider'];
		$slider_class         = 'fami-wccp-' . $compare_slider . '-slider';
		
		$slider_responsive = array(
			0    => array(
				'items' => 1,
			),
			480  => array(
				'items' => 1,
			),
			600  => array(
				'items' => 2,
			),
			1024 => array(
				'items' => 3,
			),
		);
		
		if ( $compare_slider == 'slick' ) {
			$slider_responsive = array(
				array(
					'breakpoint' => 1024,
					'settings'   => array(
						'slidesToShow'   => 3,
						'slidesToScroll' => 3
					)
				),
				array(
					'breakpoint' => 600,
					'settings'   => array(
						'slidesToShow'   => 2,
						'slidesToScroll' => 2
					)
				),
				array(
					'breakpoint' => 480,
					'settings'   => array(
						'slidesToShow'   => 1,
						'slidesToScroll' => 1
					)
				),
			);
		} else {
			$slider_class .= ' owl-carousel';
		}
		
		$img_size = Fami_Woocompare_Helper::get_image_size( 'compare' );
		
		// Add more products
		$add_more_products_html = '';
		$no_img_url             = Fami_Woocompare_Helper::no_images( $img_size );
		
		foreach ( $compare_fields as $field_key => $field_val ) {
			if ( $field_key == 'title' ) {
				continue;
			}
			
			if ( $field_key != 'image' ) {
				$field_name_cols_html   .= '<div class="fami-wccp-field field-' . esc_attr( $field_key ) . '">' . esc_html( $field_val ) . '</div>';
				$add_more_products_html .= '<div class="fami-wccp-field field-' . esc_attr( $field_key ) . ' ">' . esc_html__( '  ---  ', 'fami-woocommerce-compare' ) . '</div>';
			} else {
				$field_name_cols_html   .= '<div class="fami-wccp-field field-' . esc_attr( $field_key ) . '">' . esc_html__( 'Product', 'fami-woocommerce-compare' ) . '</div>';
				$add_more_products_html .= '<div class="fami-wccp-field field-' . esc_attr( $field_key ) . ' ">';
				$add_more_products_html .= '<div class="image-wrap fami-wccp-no-img"><img width="' . esc_attr( $img_size['width'] ) . '" height="' . esc_attr( $img_size['height'] ) . '" src="' . esc_url( $no_img_url ) . '"/></div>';
				$add_more_products_html .= '<h5 class="product-title"><a class="fami-wccp-add-more-product" href="#">' . esc_html__( 'Add products', 'fami-woocommerce-compare' ) . '</a></h5>';
				$add_more_products_html .= '</div>';
			}
		}
		
		$add_more_products_html .= '<div class="fami-wccp-field fami-wccp-add-more-product-field product_0"><a href="#" class="fami-wccp-add-more-product"
		                           title="' . esc_attr__( 'Add more', 'fami-woocommerce-compare' ) . '">+</a></div>';
		$add_more_products_html = '<div class="fami-wccp-col fami-wccp-add-more-col">' . $add_more_products_html . '</div>';
		
		$add_more_products_html_tmp = $add_more_products_html;
		for ( $i = ( $total_products + 1 ); $i < $total_slots; $i ++ ) {
			$add_more_products_html .= $add_more_products_html_tmp;
		}
		
		$html .= '<div class="fami-wccp-left-part"><div class="field-names-col fami-wccp-col">' . $field_name_cols_html . '</div></div>';
		
		foreach ( $products_list as $product_id => $_product ) {
			$col_html = '';
			foreach ( $compare_fields as $field_key => $field_val ) {
				if ( $field_key == 'title' ) {
					continue;
				}
				$product_class = 'fami-wccp-field field-' . esc_attr( $field_key ) . ' product_' . $product_id;
				ob_start();
				
				echo '<div class="' . $product_class . '">';
				switch ( $field_key ) {
					case 'image':
						$img = Fami_Woocompare_Helper::resize_image( get_post_thumbnail_id( $product_id ), null, $img_size['width'], $img_size['height'], true, true, false );
						echo '<div class="image-wrap"><img width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" src="' . esc_url( $img['url'] ) . '"/></div>';
						echo '<h5 class="product-title"><a href="' . esc_url( get_permalink( $product_id ) ) . '">' . esc_html( $_product->get_name() ) . '</a></h5>';
						break;
					
					case 'add-to-cart':
						global $product;
						$product = wc_get_product( $product_id );
						woocommerce_template_loop_add_to_cart();
						break;
					
					default:
						echo empty( $_product->fields[ $field_key ] ) ? '&nbsp;' : $_product->fields[ $field_key ];
						break;
				}
				echo '</div>';
				$col_html .= ob_get_clean();
			}
			
			$col_html .= '<div class="fami-wccp-field field-remove product_' . $product_id . '"><a href="' . esc_url( $CompareFrontend->remove_product_url( $product_id ) ) . '"
		                           data-product_id="' . esc_attr( $product_id ) . '" class="fami-wccp-remove-product"
		                           title="' . esc_attr__( 'Remove', 'fami-woocommerce-compare' ) . '">x</a></div>';
			
			$products_cols_html .= '<div class="fami-wccp-col">' . $col_html . '</div>';
		}
		
		$products_cols_html = '<div class="fami-wccp-right-part"><div class="products-list-cols ' . esc_attr( $slider_class ) . '" data-responsive="' . esc_attr( htmlentities2( wp_json_encode( $slider_responsive ) ) ) . '">' . $products_cols_html . $add_more_products_html . '</div></div>';
		$html               .= $products_cols_html;
		
		echo $html;
	} // No products to compare
	else {
		?>
        <p class="compare-empty"><?php esc_html_e( 'There are no products to compare. You need to add some products to the comparison list before view.', 'fami-woocommerce-compare' ) ?></p>
		<?php
		if ( function_exists( 'wc_get_page_id' ) ) {
			if ( wc_get_page_id( 'shop' ) > 0 ) { ?>
                <p class="return-to-shop">
                    <a class="button wc-backward"
                       href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
						<?php esc_html_e( 'Return to shop', 'fami-woocommerce-compare' ); ?>
                    </a>
                </p>
			<?php }
		}
	}
	?>
</div>

