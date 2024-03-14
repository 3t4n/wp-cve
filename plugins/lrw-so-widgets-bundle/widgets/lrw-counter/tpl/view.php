<?php
if ( ! empty( $trigger ) ) $settings['trigger'] = $trigger;
if ( ! empty( $decimals ) ) $settings['decimals'] = $decimals;
if ( ! empty( $decimals ) ) $settings['duration'] = $decimals;
if ( ! empty( $easing ) ) $settings['easing'] = $easing;
if ( ! empty( $group ) ) $settings['group'] = $group;
if ( ! empty( $separator ) ) $settings['separator'] = $separator;
if ( ! empty( $decimal ) ) $settings['decimal'] = $decimal;

echo '<div class="lrw-counter-content counter-align-' . $align . '">';
if ( $prefix ) echo '<span class="pfx">' . $prefix . '</span>';
echo '<span class="lrw-counter" data-start="' . $min . '" data-end="' . $max . '" data-settings="' . esc_attr( json_encode( $settings ) ) . '">' . $min . '</span>';
if ( $suffix ) echo '<span class="sfx">' . $suffix . '</span>';
if ( $title ) echo '<div class="lrw-counter-note">' . $title . '</div>';
echo '</div>';