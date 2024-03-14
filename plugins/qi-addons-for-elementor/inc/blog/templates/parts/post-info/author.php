<?php
$show_info_icons = isset( $show_info_icons ) && ! empty( $show_info_icons ) ? $show_info_icons : 'no';
?>
<div class="qodef-e-info-item qodef-e-info-author">
	<a itemprop="author" class="qodef-e-info-author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
		<?php
		if ( 'yes' === $show_info_icons ) {
			qi_addons_for_elementor_render_svg_icon( 'author', 'qodef-e-info-item-icon' );
		}
		?>
		<?php the_author_meta( 'display_name' ); ?>
	</a>
</div>
