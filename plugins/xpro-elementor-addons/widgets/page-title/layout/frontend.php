<?php

use Elementor\Icons_Manager;

$html_tag = $settings['title_tag'];
$class    = 'xpro-page-title';
$class   .= ( $settings['icon']['value'] && $settings['icon_align'] ) ? ' xpro-page-title-icon-' . $settings['icon_align'] : '';

if ( is_page() ) {
	$title_text = get_the_title();
} elseif ( is_home() ) {
	$title_text = __( 'Blog', 'xpro-elementor-addons' );
} elseif ( class_exists( 'WooCommerce' ) && is_shop() ) {
	$title_text = woocommerce_page_title( false );
} elseif ( is_singular( 'post' ) ) {
	$title_text = get_the_title();
} elseif ( is_tag() ) {
	$title_text = sprintf( '%s', single_tag_title( '', false ) );
} elseif ( is_author() ) {
	$title_text = sprintf( '%s', get_the_author() );
} elseif ( is_category() ) {
	$title_text = sprintf( '%s', single_tag_title( '', false ) );
} elseif ( is_year() ) {
	$title_text = sprintf( '%s', get_the_date( _x( 'Y', 'yearly archives date format', 'xpro-elementor-addons' ) ) );
} elseif ( is_month() ) {
	$title_text = sprintf( '%s', get_the_date( _x( 'F Y', 'monthly archives date format', 'xpro-elementor-addons' ) ) );
} elseif ( is_day() ) {
	$title_text = sprintf( '%s', get_the_date( _x( '', 'daily archives date format', 'xpro-elementor-addons' ) ) ); //phpcs:ignore WordPress.WP.I18n.NoEmptyStrings
} elseif ( is_search() ) {
	$title_text = __( 'Search Results For ', 'xpro-elementor-addons' ) . get_search_query();
} elseif ( is_404() ) {
	$title_text = __( 'Not Found', 'xpro-elementor-addons' );
} elseif ( is_archive() ) {
	$title_text = get_the_archive_title();
} else {
	$title_text = get_the_title();
}
?>

<<?php echo esc_attr( $html_tag ); ?> class="<?php echo esc_attr( $class ); ?>">
<?php if ( $settings['icon']['value'] ) : ?>
	<span class="xpro-page-title-icon">
			<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
		</span>
<?php endif; ?>
<span class="xpro-page-title-text">
		<?php xpro_elementor_kses( $title_text ); ?>
	</span>
</<?php echo esc_attr( $html_tag ); ?>>
