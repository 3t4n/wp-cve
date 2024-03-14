<?php

$atts = array();
$attr = ' ';
$style = 'min-height:1px;';

$attrs['data-numposts'] = $settings->comments_number;
$attrs['data-order-by'] = $settings->order_by;

if ( 'current_page' == $settings->url_type ) {
	$permalink = get_permalink();
} else {
	$permalink = esc_url( $settings->url );
}

$attrs['data-href']  = $permalink;
$attrs['data-width'] = $settings->width;

if ( isset( $settings->width_unit ) && '%' === $settings->width_unit ) {
	$attrs['data-width'] .= '%';
}

$attrs = apply_filters( 'pp_facebook_comments_html_attrs', $attrs, $settings );

foreach ( $attrs as $key => $value ) {
	$attr .= $key;
	if ( ! empty( $value ) ) {
		$attr .= '=' . $value;
	}

	$attr .= ' ';
}
?>

<div class="pp-facebook-widget fb-comments" <?php echo $attr; ?> style="<?php echo $style; ?>">
</div>
