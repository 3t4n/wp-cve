<?php
$show_info_icons = isset( $show_info_icons ) && ! empty( $show_info_icons ) ? $show_info_icons : 'no';
?>
<div class="qodef-e-info-item qodef-e-info-category">
	<?php
	if ( 'yes' === $show_info_icons ) {
		qi_addons_for_elementor_render_svg_icon( 'category', 'qodef-e-info-item-icon' );
	}
	?>
	<?php the_category( '<span class="qodef-category-separator"></span>' ); ?>
</div>
