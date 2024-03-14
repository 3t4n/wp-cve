<?php
$show_info_icons = isset( $show_info_icons ) && ! empty( $show_info_icons ) ? $show_info_icons : 'no';
$date_link       = empty( get_the_title() ) && ! is_single() ? get_the_permalink() : get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) );
$classes         = '';
if ( is_single() || is_page() || is_archive() ) { // This check is to prevent classes for Gutenberg block
	$classes = 'published updated';
}
?>
<div itemprop="dateCreated" class="qodef-e-info-item qodef-e-info-date entry-date <?php echo esc_attr( $classes ); ?>">
	<a itemprop="url" href="<?php echo esc_url( $date_link ); ?>">
		<?php
		if ( 'yes' === $show_info_icons ) {
			qi_addons_for_elementor_render_svg_icon( 'date', 'qodef-e-info-item-icon' );
		}
		?>
		<?php the_time( get_option( 'date_format' ) ); ?>
	</a>
</div>
