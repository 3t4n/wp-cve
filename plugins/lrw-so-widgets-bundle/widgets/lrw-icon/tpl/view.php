<?php
$icon_wrapper   = array();
$icon_wrapper[] = 'element-' . ( ! empty( $image ) ? 'shape_image' : 'shape_icon' );
$icon_classes   = array();
$icon_classes[] = ( $shape_format ? 'icon-shape-' . $shape_format : '' );
$icon_classes[] = ( ! empty( $icon_size ) ) ? 'icon-size-' . $icon_size : '';
$icon_classes[] = ( $shape_format == 'outline-circle' or $shape_format == 'outline-square' or $shape_format == 'outline-rounded' ) ? 'icon-element-outline' : 'icon-element-background';
$icon_classes[] = ( $icon_type == 'type_image' && $image_overflow == false ? 'overflow-hidden' : '' );
$icon_classes[] = ( ! empty( $hover ) ? 'has-hover' : '' );
$url_target 	= ( $new_window ? 'target="_blank"' : '' );
$markup 		= ( ! empty( $url ) ? 'a href="' . sow_esc_url( $url ) . '"' . $url_target : 'div' );
$src 			= wp_get_attachment_image( $image, $image_size );

echo '<div class="lrw-icon">
	<div class="lrw-icon-element ' . esc_attr( implode( ' ', array_filter( $icon_wrapper ) ) ) . '">';
		if ( $icon_type == 'type_icon' ) :
			echo '<' . $markup . ' class="icon-inner ' . esc_attr( implode( ' ', array_filter( $icon_classes ) ) ) . '">' . siteorigin_widget_get_icon( $icon ) . '</' . $markup . '>';
		elseif ( $icon_type == 'type_image' ) :
			echo '<' . $markup . ' class="image-wrapper ' . esc_attr( implode( ' ', array_filter( $icon_classes ) ) ) . '">' . $src . '</' . $markup . '>';
		endif;
	echo '</div>
</div>';