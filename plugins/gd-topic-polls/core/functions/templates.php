<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'gdpol_response_edit_template' ) ) {
	function gdpol_response_edit_template( $seq_id, $response_id, $response ) {
		?>

        <input type="hidden" name="gdpol[poll][responses][<?php echo $seq_id; ?>][id]" value="<?php echo esc_attr( $response_id ); ?>"/>
        <span class="_label"
        ><input title="<?php _e( 'Response', 'gd-topic-polls' ); ?>" type="text" name="gdpol[poll][responses][<?php echo $seq_id; ?>][response]" value="<?php echo esc_attr( $response ); ?>"/></span>
        <span class="_minus _button"
        ><button type="button" title="<?php _e( 'Remove response', 'gd-topic-polls' ); ?>"><?php echo gdpol()->get_button_text( 'remove' ); ?></button></span>
        <span class="_down _button"
        ><button type="button" title="<?php _e( 'Move response down', 'gd-topic-polls' ); ?>"><?php echo gdpol()->get_button_text( 'down' ); ?></button></span>
        <span class="_up _button"
        ><button type="button" title="<?php _e( 'Move response up', 'gd-topic-polls' ); ?>"><?php echo gdpol()->get_button_text( 'up' ); ?></button></span>

		<?php
	}
}

if ( ! function_exists( 'gdpol_response_result_info_template' ) ) {
	function gdpol_response_result_info_template( $label, $votes, $percent, $color, $width ) {
		?>

        <div class="gdpol-response-info"><span class="gdpol-response-label"><?php echo $label; ?></span
            ><span class="gdpol-response-bar"
            ><span style="width: <?php echo $width; ?>%; background-color: <?php echo $color; ?>"></span></span
            ><span class="gdpol-response-percent"><?php echo $percent; ?>%</span
            ><span class="gdpol-response-votes"><?php echo $votes . ' ' . _n( 'vote', 'votes', $votes, 'gd-topic-polls' ); ?></span
            ></div>

		<?php
	}
}
