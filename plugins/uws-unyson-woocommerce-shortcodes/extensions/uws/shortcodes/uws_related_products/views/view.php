<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$uws_products_params = '';
$uws_limit_param     = '';
$uws_limit           = (int) $atts['limit'];
if ( $uws_limit ) {
	$uws_products_params .= ' limit="' . $uws_limit . '"';
}

$uws_products_params .= ' columns="' . $atts['columns'] . '"';
$uws_products_params .= ' orderby="' . $atts['orderby'] . '"';
?>
<div class="uws-related-products <?php echo esc_attr( $atts['class'] ); ?>">
	<?php echo do_shortcode( '[related_products ' . $uws_products_params . ']' ); ?>
</div>