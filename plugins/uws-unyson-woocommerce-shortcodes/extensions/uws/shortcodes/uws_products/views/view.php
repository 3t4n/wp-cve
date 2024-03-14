<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$uws_products_params = '';
$uws_limit_param     = '';
$uws_limit           = (int) $atts['limit'];
if ( $uws_limit ) {
	$uws_products_params .= ' limit="' . $uws_limit . '"';
}

if ( ! empty( $atts['category'] ) ) {
	$uws_products_params .= ' category="' . implode( ",", uws_get_product_taxonomies_slug_by_id( $atts['category'] ) ) . '"';
}

$uws_products_params .= ' columns="' . $atts['columns'] . '"';
$uws_products_params .= ' orderby="' . $atts['orderby'] . '"';
$uws_products_params .= ' order="' . $atts['order'] . '"';
if ( $atts['type'] == 'on_sale' ) {
	$uws_products_params .= ' on_sale="true"';
} elseif ( $atts['type'] == 'best_selling' ) {
	$uws_products_params .= ' best_selling="true"';
} elseif ( $atts['type'] == 'top_rated' ) {
	$uws_products_params .= ' top_rated="true"';
}

?>
<div class="uws-products <?php echo esc_attr( $atts['class'] ); ?>">
	<?php echo do_shortcode( '[products ' . $uws_products_params . ']' ); ?>
</div>