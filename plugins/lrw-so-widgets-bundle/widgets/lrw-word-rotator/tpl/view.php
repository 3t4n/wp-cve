<?php
if ( ! empty( $trigger ) ) $settings['trigger'] = $trigger;
if ( ! empty( $animation ) ) $settings['animation'] = $animation;
if ( ! empty( $speed ) ) $settings['speed'] = $speed;

$classes   = array();
$classes[] = 'lrw-word-text';
$classes[] = ( ! empty( $align ) ) ? 'rotator-align-' . $align : '';

$styles = array();
$styles[] = ( ! empty( $fontsize ) ? 'font-size: ' . $fontsize . ';' : '' );
$styles[] = ( ! empty( $lineheight ) ? 'line-height: ' . $lineheight . ';' : '' );

echo '<div class="lrw-word-rotator rt-no-display" data-settings="' . esc_attr( json_encode( $settings ) ) . '">';
	echo '<' . $tag . ' class="' . esc_attr( implode( ' ', $classes ) ) . '" ' . ( ! empty( $styles ) ? 'style="' . esc_attr( implode( ' ', $styles ) ) . '"' : '' ) . '>' . wp_kses_post( $prefix ) . ' <span class="lrw-rotating ' . ( ! empty( $bold ) ? 'rotator-is-bold' : '' ) . '">' . esc_attr( preg_replace( '/\r|\n/', ',' , $strings ) ) . '</span> ' . wp_kses_post( $suffix ) . '</' . $tag . '>';
echo '</div>';