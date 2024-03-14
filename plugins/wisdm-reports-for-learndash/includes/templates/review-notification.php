<?php
/**
 * Template to display the upgrade notice
 *
 * @package learndash-reports-by-wisdmlabs
 */

?>
<div class="wrap">
	<div class="wrld-admin-notice  error" wp_nonce=<?php echo esc_html( $wp_nonce ); ?>>
		<div class="wrld-admin-notice-structure">
			<div class='image-column'>
				<div class="wrld-admin-notice-image">
					<img src="<?php echo esc_attr( $wisdm_logo ); ?>">
				</div>
			</div>
			<div class="content-column">
				<div class="wrld-admin-notice-head">
					<p>
						<span class="wisdm-reports-for-ld-message-head"><?php echo esc_html( $message_head ); ?></span>
						<br>
					</p>
				</div>
				<div class="review-notice-description">
					<p>
						<?php echo esc_html( $message ); ?>
					</p>
				</div>
				<div class="review-notice-actions">
					<div class="action-one">
						<a href="<?php echo esc_attr( $link ); ?>" target="_blank">
							<button id="wisdm-reports-for-ld-post-review" class="wrld-admin-notice-action button"><?php echo esc_html( $button_text ); ?></button>
						</a>
					</div>
					<div class="action-two">
						<a href="#">
							<span id="wisdm-reports-for-ld-remind-later" class="wrld-admin-notice-action text"><?php echo esc_html__( 'Maybe later', 'learndash-reports-by-wisdmlabs' ); ?></span>
						</a>
					</div>
					<div class="action-three">
						<a href="#">
							<span id="wisdm-reports-for-ld-dissmiss" class="wrld-admin-notice-action text"><?php echo esc_html__( 'I already did', 'learndash-reports-by-wisdmlabs' ); ?></span>
						</a>
					</div>
				</div>
			</div>
			<span class="wrld-notification-close">+<span>
		</div>
	</div>
</div>
