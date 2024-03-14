<?php

defined( 'ABSPATH' ) || exit;

$deactivation_url    = wp_nonce_url( admin_url( 'plugins.php?action=cpt-feedback' ), CPT_NONCE_KEY, 'nonce' );
$deactivation_reason = array(
	__( 'Error / Bug', 'custom-post-types' ),
	__( 'Safety / performance', 'custom-post-types' ),
	__( 'Compatibility', 'custom-post-types' ),
	__( 'Bad UI experience', 'custom-post-types' ),
	__( 'Bad features', 'custom-post-types' ),
	__( 'Use another plugin', 'custom-post-types' ),
);
shuffle( $deactivation_reason );
?>
<div id="cpt-feedback-modal" style="display:none;">
	<div class="cpt-modal-wrap">
		<div class="cpt-modal-title">
			<h3><?php printf( _x( 'Deactivate %s', 'plugin' ) . ' - ' . __( 'Send your feedback', 'custom-post-types' ), CPT_NAME ); ?></h3>
		</div>
		<div class="cpt-modal-content">
			<p><?php _e( 'Our mission is to improve the plugin to make it a complete and reliable tool for extending WordPress.', 'custom-post-types' ); ?></p>
			<p><?php _e( 'Your anonymous feedback can help us improve. Thank you!', 'custom-post-types' ); ?></p>
			<form action="#">
				<div class="cpt-modal-input-label">
					<?php _e( 'Choose the reasons for deactivation (multiple accepted):', 'custom-post-types' ); ?><br>
					<div class="cpt-modal-input-checkbox-wrap">
						<?php
						foreach ( $deactivation_reason as $reason ) {
							printf(
								'<label class="cpt-modal-input-checkbox" aria-label="%1$s"><input type="checkbox" name="reason" value="%1$s" aria-hidden="true"><button class="button button-secondary">%1$s</button></label>',
								$reason
							);
						}
						?>
					</div>
				</div>
				<label class="cpt-modal-input-label">
					<?php _e( 'Your suggestion for improvement:', 'custom-post-types' ); ?><br>
					<textarea class="cpt-modal-input-text" name="suggestion" placeholder="<?php _e( '...', 'custom-post-types' ); ?>"></textarea>
				</label>
			</form>
		</div>
		<div class="cpt-modal-actions">
			<?php
			printf(
				'<button class="button button-secondary" aria-label="%1$s" title="%1$s" onclick="tb_remove();">%1$s</button>',
				__( 'Cancel', 'custom-post-types' )
			);
			printf(
				'<a class="button button-primary" href="%1$s" data-href="%1$s" aria-label="%2$s" title="%2$s">%3$s</a>',
				$deactivation_url,
				esc_attr( sprintf( _x( 'Deactivate %s', 'plugin' ), CPT_NAME ) ),
				__( 'Deactivate' )
			);
			?>
		</div>
	</div>
</div>