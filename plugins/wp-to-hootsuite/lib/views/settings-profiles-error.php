<?php
/**
 * Outputs Settings View when an error occured fetching Profiles from the API
 *
 * @since    4.6.9
 *
 * @package WP_To_Social_Pro
 * @author  WP Zinc
 */

?>
<div class="postbox">
	<div class="wpzinc-option">
		<p class="description">
			<?php echo esc_html( $profiles->get_error_message() ); ?>
		</p>
		<p class="description">
			<?php
			echo esc_html(
				sprintf(
				/* translators: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
					__( 'Visit your %s account to resolve this error.', 'wp-to-hootsuite' ),
					$this->base->plugin->account
				)
			);
			?>
		</p>
	</div>
	<div class="wpzinc-option">
		<a href="<?php echo esc_attr( $this->base->get_class( 'api' )->get_connect_profiles_url() ); ?>" target="_blank" rel="nofollow noopener" class="button button-primary">
			<?php
			echo esc_html(
				sprintf(
				/* translators: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
					__( 'Visit %s', 'wp-to-hootsuite' ),
					$this->base->plugin->account
				)
			);
			?>
		</a>
	</div>
</div>
