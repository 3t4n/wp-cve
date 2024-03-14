<?php
echo '<div class="lrw-progress-bar-vert" data-trigger="' . esc_attr( json_encode( $trigger ) ) . '">';
	echo '<div class="lrw-progress-bar-wrapper">';
   		$vl_styles = array();
		if ( ! empty( $vl_fontsize ) ) $vl_styles[] = 'font-size: ' . $vl_fontsize . 'px';
		if ( ! empty( $vl_lineheight ) ) $vl_styles[] = 'line-height: ' . $vl_lineheight;
		$lb_styles = array();
		if ( ! empty( $lb_fontsize ) ) $lb_styles[] = 'font-size: ' . $lb_fontsize . 'px';
		if ( ! empty( $lb_lineheight ) ) $lb_styles[] = 'line-height: ' . $lb_lineheight;
	    echo '<div class="lrw-progress-bar-area" data-perc="' . $value . '">';
	        echo '<' . $vl_type . ' class="lrw-bar-value countto" data-to="' . $value . '" data-speed="3000" '. ( ! empty( $vl_styles ) ? 'style="' . esc_attr( implode( '; ', $vl_styles ) ) . '"' : '' ) . '>' . $value . ' ' . $unit . '</' . $vl_type . '>';
	    echo '</div>';
    echo '</div>';
    echo '<' . $lb_type . ' class="lrw-bar-label" '. ( ! empty( $lb_styles ) ? 'style="' . esc_attr( implode( '; ', $lb_styles ) ) . '"' : '' ) . '>' . $label . '</' . $lb_type . '>';
echo '</div>';