<?php
/**
 * Template to display the upgrade notice
 *
 * @package learndash-reports-by-wisdmlabs
 */

?>
<div class="wrap">
	<div class="wisdm-reports-for-ld-notice  notice-error">
		<table class="wisdm-reports-for-ld-notice-structure">
			<tr>
				<td class="wisdm-reports-for-ld-notice-image">
					<img src="<?php echo esc_attr( $wisdm_logo ); ?>">
				</td>
				<td class="wisdm-reports-for-ld-notice-text">
					<p>
						<span class="wisdm-reports-for-ld-message-head"><?php echo esc_html( $message_head ); ?></span>
						<br><?php echo esc_html( $message ); ?></p>
				</td>
				<td class="wisdm-reports-for-ld-notice-button-div">
					<a href="<?php echo esc_attr( $link ); ?>" target="_blank">
						<button id="btn-wisdm-reports-for-ld-notice" class="wisdm-reports-for-ld-notice-button"><?php echo esc_html( $button_text ); ?></button>
					</a>
					<a href="<?php echo esc_attr( add_query_arg( array( $dismiss_attribute => true ) ) ); ?>">
						<button id="btn-wisdm-reports-for-ld-notice" class="wisdm-reports-for-ld-notice-button"><?php echo esc_html__( 'Dismiss', 'learndash-reports-by-wisdmlabs' ); ?></button>
					</a>
				</td>
			</tr>
		</table>
	</div>
</div>
