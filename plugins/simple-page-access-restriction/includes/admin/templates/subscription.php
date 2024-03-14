<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="simple-par-subscription-callout-wrapper">
	<div class="simple-par-subscription-callout">
		<div class="simple-par-subscription-callout-main">
			<h3><?php _e( 'Subscribe to our Newsletter', 'simple-page-access-restriction' ); ?></h3>
			<p><?php _e( 'Receive updates from <a href="https://www.pluginsandsnippets.com" target="_blank">Plugins & Snippets</a> with respect to WordPress plugins aimed to enhance the conversion rates of your web stores.', 'simple-page-access-restriction' ); ?></p>

			<div class="simple-par-subscription-error" style="display: none;"><?php _e( 'There was an error in processing your request, please try again.', 'simple-page-access-restriction' ); ?></div>

			<form method="POST" class="simple-par-subscription-form">
				<input type="email" required value="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>">
				
				<div class="simple-par-subscription-actions">
					<button class="button-primary"><?php _e( 'Subscribe', 'simple-page-access-restriction' ); ?></button>
				</div>
			</form>
		</div>

		<div class="simple-par-subscription-callout-thanks" style="display: none;">
			<h3><?php _e( 'Thank you for signing up to our Newsletter!', 'simple-page-access-restriction' ); ?></h3>
		</div>

	</div>
</div>