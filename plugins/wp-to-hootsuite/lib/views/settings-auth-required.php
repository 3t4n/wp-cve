<?php
/**
 * Outputs a screen with a button/link/form to authenticate the Plugin
 * with the third party API service.
 *
 * @package WP_To_Social_Pro
 * @author  WP Zinc
 */

?>
<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php echo esc_html( $this->base->plugin->displayName ); ?>
	</h1>

	<div class="wrap-inner">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-1">
				<div id="post-body-content">
					<div id="normal-sortables" class="meta-box-sortables ui-sortable">
						<div class="postbox"> 
							<div class="wpzinc-option">
								<p class="description">
									<?php
									echo esc_html(
										sprintf(
										/* translators: %1$s: Social Media Service Name (Buffer, Hootsuite, SocialPilot), %2$s: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
											__( 'To allow this Plugin to post updates to your social media profiles using %1$s, please authorize %2$s below.', 'wp-to-hootsuite' ),
											$this->base->plugin->account,
											$this->base->plugin->account
										)
									);
									?>
								</p>
								<p class="description">
									<?php
									echo esc_html(
										sprintf(
										/* translators: %1$s: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
											__( 'Don\'t have a %1$s account?', 'wp-to-hootsuite' ),
											$this->base->plugin->account
										)
									);
									?>
									<a href="<?php echo esc_attr( $this->base->get_class( 'api' )->get_registration_url() ); ?>" target="_blank" rel="nofollow noopener">
										<?php esc_html_e( 'Sign up for free', 'wp-to-hootsuite' ); ?>
									</a>
								</p>
							</div>

							<?php
							/**
							 * Allow the API to output its authentication button link or form, to authenticate
							 * with the API.
							 *
							 * @since   4.2.0
							 *
							 * @param   array   $schedule   Schedule Options
							 */
							do_action( $this->base->plugin->filter_name . '_output_auth' );
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
