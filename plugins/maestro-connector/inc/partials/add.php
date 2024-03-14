<div class="message">
	<p class="thin"><?php esc_html_e( 'Your web professional shared a secret key with you. By entering the key below, you\'re giving them administrative access to your WordPress website. Revoke access at any time.', 'maestro-connector' ); ?></p>
</div>
<div class="details bold"></div>
<div class="actions">
	<form method="POST" action="" class="maestro-key-form">
		<input type="text" name="key" class="key" placeholder="<?php esc_html_e( 'Enter secret key here', 'maestro-connector' ); ?>" />
		<button class="maestro-button primary submit" disabled><span class="next"><?php esc_html_e( 'Next', 'maestro-connector' ); ?></span></button>
	</form>
</div>
