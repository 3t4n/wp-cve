<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<div class="postbox">
	<form id="woo_image_seo_feedback">
        <input
            type="hidden"
            name="action"
            value="woo_image_seo_send_feedback"
        >

		<div class="form__head">
			<h2><?php _e( 'Contact the author', 'woo-image-seo' ) ?></h2>
		</div><!-- /.form__head -->

		<div
			class="form__body"
			data-sent="<?php _e( 'Your message has been sent.', 'woo-image-seo' ) ?>"
			data-thanks="<?php _e( 'Thank you!', 'woo-image-seo' ) ?>"
		>
			<p>
				<?php _e( 'Feel free to contact me with any questions or feedback.', 'woo-image-seo' ) ?>
			</p>

			<input
				type="email"
				name="email"
				placeholder="<?php _e( 'your email', 'woo-image-seo' ) ?>"
				required
			>

			<textarea
				name="message"
				rows="5"
				placeholder="<?php _e( 'your message', 'woo-image-seo' ) ?>"
				required
			></textarea>

			<input
				type="submit"
				value="<?php _e( 'Submit', 'woo-image-seo' ) ?>"
				data-submitting="<?php _e( 'Submitting...', 'woo-image-seo' ) ?>"
			>
		</div><!-- /.form__body -->

		<?php wp_nonce_field( 'woo_image_seo_send_feedback' ); ?>
	</form>
</div><!-- /.postbox -->