<?php
if ( ! empty( $trigger ) ) $settings['trigger'] = $trigger;
if ( $e_easing == 'yes' && ! empty( $easing ) ) $settings['easing'] = $easing;
if ( ! empty( $scalelength ) ) $settings['scalelength'] = $scalelength;
if ( ! empty( $linecap ) ) $settings['linecap'] = $linecap;
if ( ! empty( $linewidth ) ) $settings['linewidth'] = $linewidth;
if ( ! empty( $trackwidth ) ) $settings['trackwidth'] = $trackwidth;
if ( ! empty( $size ) ) $settings['size'] = $size;
if ( ! empty( $rotate ) ) $settings['rotate'] = $rotate;
if ( ! empty( $animate ) ) $settings['animate'] = $animate;

echo '<div class="lrw-pie-chart" data-percent="' . $value . '" data-unit="' . $unit . '" data-bar-color="' . $barcolor . '" data-track-color="' . $trackcolor . '" data-scale-color="' . $scalecolor . '" data-settings="' . esc_attr( json_encode( $settings ) ) . '">';
		echo '<div class="lrw-pie-wrapper">';
			echo '<' . $tag . ' class="lrw-pie-percent"></' . $tag . '>';
		echo '</div>';
echo '</div>';
