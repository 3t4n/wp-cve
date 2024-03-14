<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$content                 .= '<div class="ccew-wrapper ccew-price-label ccew-bg">
    <ul class="ccew-label-wrapper">
        <li id="' . esc_attr( $coin_id ) . '">
            <div class="ccew-coin-container">
                <span class="ccew_icon">';
				$content .= $coin_logo_html;
				$content .= '</span>
                <span class="ccew-coin-name ccew-primary">' . esc_html( $coin_name ) . '</span>
                <span class="ccew-coin-price ccew-secondary">' . esc_html( $price ) . '</span>';
if ( $display_24h_changes == 'yes' ) {
	$content .= ccew_changes_up_down( $change_24_h );
	$content .= '<span class="ccew-changes-time">' . __( '24H', 'ccew' ) . '</span>';
}
			$content .= '</div>
        </li>
   </ul>
</div>';
