<?php
if ( empty( $instance['values'] ) )
	return;

echo '<div class="lrw-progress-bar" data-trigger="' . esc_attr( json_encode( $trigger ) ) . '">';
   	foreach ( $instance['values'] as $value ) :
		$label_styles = array();
		if ( ! empty( $value['label_color'] ) ) $label_styles[] = 'color: ' . $value['label_color'];
   		$bar_styles = array();
		if ( ! empty( $value['bar_color'] ) ) $bar_styles[] = 'background-color: ' . $value['bar_color'];

		echo '<div class="lrw-progress-bar-wrapper">';
			if ( ! empty( $value['label'] ) ) :
				echo '<div class="lrw-progress-bar-label' . ( $label_inner == 'yes' ? ' label-inner' : ' label-out' ) . '" '. ( ! empty( $label_styles ) ? 'style="' . esc_attr( implode( '; ', $label_styles ) ) . '"' : '' ) . '><span class="bar-lb">' . $value['label'] . ' ' . '</span><span class="bar-vl">' . $value['value'] . $value['unit'] . '</span></div>';
	    	endif;
	    	echo '<div class="lrw-progress-bar-content">';
	    		echo '<div class="lrw-progress-bar-area" data-perc="' . esc_attr( $value['value'] ) . '" '. ( ! empty( $bar_styles ) ? 'style="' . esc_attr( implode( '; ', $bar_styles ) ) . '"' : '' ) .'></div>';
	    	echo '</div>';
    	echo '</div>';
	endforeach;
echo '</div>';