<?php
/**
 * Outputs Settings View when no Profiles are connected to the API
 *
 * @since    3.0.0
 *
 * @package WP_To_Social_Pro
 * @author  WP Zinc
 */

?>
<div class="postbox">
	<div class="wpzinc-option">
		<p class="description">
			<?php
			echo esc_html(
				sprintf(
					/* translators: %1$s: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
					__( 'You must connect at least one social media account in %1$s for this Plugin to send status updates to it.', 'wp-to-hootsuite' ),
					$this->base->plugin->account
				)
			);
			?>
		</p>
		<p class="description">
			<?php esc_html_e( 'Once complete, refresh this page to enable and configure statuses for each social media account.', 'wp-to-hootsuite' ); ?>
		</p>
	</div>
	<div class="wpzinc-option">
		<a href="<?php echo esc_attr( $this->base->get_class( 'api' )->get_connect_profiles_url() ); ?>" target="_blank" rel="nofollow noopener" class="button button-primary">
			<?php esc_html_e( 'Connect Profiles', 'wp-to-hootsuite' ); ?>
		</a>
	</div>
</div>
