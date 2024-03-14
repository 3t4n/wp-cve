<?php
if ( empty( $instance['images'] ) )
	return;

if ( ! empty( $instance['slidemode'] ) ) $slider_attr['slidemode'] = $instance['slidemode'];
if ( ! empty( $instance['slidespeed'] ) ) $slider_attr['slidespeed'] = $instance['slidespeed'];
if ( ! empty( $instance['captions'] ) ) $slider_attr['captions'] = $instance['captions'];
if ( ! empty( $instance['auto'] ) ) $slider_attr['auto'] = $instance['auto'];
if ( ! empty( $instance['pausehover'] ) ) $slider_attr['pausehover'] = $instance['pausehover'];
if ( ! empty( $instance['slidetype'] ) ) $slider_attr['slidetype'] = $instance['slidetype'];

if ( $instance['slidetype'] == 'carousel' ) :
	if ( ! empty( $instance['slider_carousel']['minslides'] ) ) $slider_attr['minslides'] = $instance['slider_carousel']['minslides'];
	if ( ! empty( $instance['slider_carousel']['maxslides'] ) ) $slider_attr['maxslides'] = $instance['slider_carousel']['maxslides'];
	if ( ! empty( $instance['slider_carousel']['moveslides'] ) ) $slider_attr['moveslides'] = $instance['slider_carousel']['moveslides'];
endif;

if ( ! empty( $instance['slider_carousel']['slidewidth'] ) ) $item_attr['slidewidth'] = $instance['slider_carousel']['slidewidth'];
if ( ! empty( $instance['slider_carousel']['slidemargin'] ) ) $item_attr['slidemargin'] = $instance['slider_carousel']['slidemargin'];

// get output items attributes as a string
$items_attr = '';
foreach ( $item_attr as $name => $val ) {
	$items_attr .= 'data-' . $name . '="' . $val . '" ';
}

// output slider
echo '<div id="lrw-slider" class="lrw-widget-slider">
    	<div class="slides" data-settings="' . esc_attr( json_encode( $slider_attr ) ) . '">';

    	foreach ( $instance['images'] as $image ) :

    		// get image attr
			$image_slider = '';
			if ( empty( $image['image_slider'] ) ) :
			    $image_slider = false;
			else :
			    $image_slider = wp_get_attachment_image_src( $image['image_slider'], 'full' );
			endif;

    		echo '<div class="slide-item" ' . ( $instance['slidetype'] == 'carousel' ? $items_attr : '' ) . '>';
    		echo ( ! empty( $image['url'] ) ? '<a href="' . esc_url( $image['url'] ) . '">' : '' );
    		echo ( ! empty( $image_slider[0] ) ? '<img src="' . $image_slider[0] . '" alt="' . $image['title']. '" title="' . $image['title'] . '" >' : '' );
    		echo ( ! empty( $image['url'] ) ? '</a>' : '' );
    		echo '</div>';

    	endforeach;

	echo '</div>';
echo '</div><!-- #lrw-slider -->';
