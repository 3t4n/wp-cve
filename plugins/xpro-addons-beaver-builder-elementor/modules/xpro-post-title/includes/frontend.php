<?php

$product_id = $module->get_product_id( $i );

if ( is_page() ) {
	$post_title = get_the_title();
} elseif ( is_home() ) {
	$post_title = __( 'Blog', 'xpro-elementor-addons' );
} elseif ( is_singular( 'post' ) ) {
	$post_title = get_the_title();
} elseif ( is_tag() ) {
	$post_title = sprintf( '%s', single_tag_title( '', false ) );
} elseif ( is_author() ) {
	$post_title = sprintf( '%s', get_the_author() );
} elseif ( is_category() ) {
	$post_title = sprintf( '%s', single_tag_title( '', false ) );
} elseif ( is_year() ) {
	$post_title = sprintf( '%s', get_the_date( _x( 'Y', 'yearly archives date format', 'xpro' ) ) );
} elseif ( is_month() ) {
	$post_title = sprintf( '%s', get_the_date( _x( 'F Y', 'monthly archives date format', 'xpro' ) ) );
} elseif ( is_day() ) {
    // phpcs:ignore WordPress.WP.I18n.NoEmptyStrings
	$post_title = sprintf( '%s', get_the_date( _x( '', 'daily archives date format', 'xpro' ) ) );
} elseif ( is_search() ) {
	$post_title = __( 'Search Results For ', 'xpro-elementor-addons' ) . get_search_query();
} elseif ( is_404() ) {
	$post_title = __( 'Not Found', 'xpro-elementor-addons' );
} elseif ( class_exists( 'WooCommerce' ) && is_shop() ) {
	$post_title = woocommerce_page_title( false );
} elseif ( $product_id ) {
	$post_title = get_the_title( $product_id );
} else {
	$post_title = get_the_title();
}

?>

<<?php echo esc_attr( $settings->title_tag ); ?> class="xpro-post-title-wrapper">

	<!-- Title -->
	<span class="xpro-post-title-text">
		<?php echo esc_attr( $post_title ); ?>
	</span>

</<?php echo esc_attr( $settings->title_tag ); ?>>

