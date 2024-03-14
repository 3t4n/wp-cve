<?php
/**
 * Trending view.
 *
 * @package GoogleAnalytics
 */

?>
<div class="wrap ga-wrap">
	<h3 class="ga-trending-h3">Google Analytics</h3>
	<h2 class="ga-trending-h2"><?php esc_html_e( 'Trending content', 'googleanalytics' ); ?></h2>
	<div class="ga_container <?php echo esc_attr( false === Ga_Helper::are_features_enabled() ? 'label-grey ga-tooltip' : '' ); ?>"
		id="exTab2">
		<?php if ( false === empty( $data['error_message'] ) ) : ?>
			<?php echo esc_html( $data['error_message'] ); ?>
		<?php endif; ?>
		<?php if ( false === empty( $data['ga_msg'] ) ) : ?>
			<?php echo esc_html( $data['ga_msg'] ); ?>
		<?php endif; ?>
		<span class="ga-tooltiptext ga-tooltiptext-trending"><?php echo esc_html( $tooltip ); ?></span>
		<div class="ga-trending-loader">
			<div class="ga-trending-loader-wrapper">
				<div class="ga-loader"></div>
			</div>
			<div class="ga-trending-loading-text"><?php echo esc_html__( 'Please wait. Trending Content Alerts are loading.', 'googleanalytics' ); ?></div>
		</div>
		<?php if ( true === Ga_Helper::are_features_enabled() && true === empty( $errors ) ) : ?>
			<?php if ( false === empty( $alerts ) && true === empty( $alerts->error ) ) : ?>
				<div class="trending-table-container">
					<table class="ga-table ga-table-trending">
						<tr>
							<th>
								<?php echo esc_html__( 'Top 5 Recent alerts', 'googleanalytics' ); ?>
							</th>
							<th class="weight-normal">
								<?php echo esc_html__( 'Views', 'googleanalytics' ); ?>
							</th>
							<th class="weight-normal trending-time">
								<?php echo esc_html__( 'Time Notified', 'googleanalytics' ); ?>
							</th>
						</tr>
						<?php foreach ( $alerts as $key => $alert ) : ?>
							<tr>
								<td>
									<a class="trending-link" href="<?php echo esc_url( $alert->{'url'} ); ?>">
										<?php echo esc_html( $alert->{'url'} ); ?>
									</a>
								</td>
								<td>
								<?php
								echo esc_html(
									property_exists(
										$alert,
										'pageviews'
									) ? $alert->{'pageviews'} : '0'
								);
								?>
										</td>
								<td><?php echo esc_html( gmdate( 'F jS, g:ia', strtotime( $alert->{'sent_at'} ) ) ); ?></td>
							</tr>
							<?php
							if ( $key >= 4 ) {
								break;
							}
							?>
						<?php endforeach; ?>
					</table>
				</div>
			<?php elseif ( ! empty( $alerts->error ) ) : ?>
				<div class="ga-alert ga-alert-danger">
					<?php echo wp_kses_post( $alerts->error ); ?>
				</div>
			<?php else : ?>
				<div class="ga-alert ga-alert-warning">
					<?php echo esc_html__( 'You will see a history of trending content here once the first article takes off.', 'googleanalytics' ); ?>
					<a class="ga-alert-link"
					href="http://tiny.cc/trending/"><?php echo esc_html__( 'Click here to learn more', 'googleanalytics' ); ?></a>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<div>
			<form method="post">
				<?php
				wp_nonce_field(
					Ga_Admin_Controller::ACTION_SHARETHIS_INVITE,
					Ga_Admin_Controller::GA_NONCE_FIELD_NAME
				);
				?>
				<input type="hidden" name="<?php echo esc_attr( Ga_Controller_Core::ACTION_PARAM_NAME ); ?>"
					value="<?php echo esc_attr( Ga_Admin_Controller::ACTION_SHARETHIS_INVITE ); ?>">
				<table>
					<tr class="ga-ta-header">
						<th>
							<?php echo esc_html__( 'Trending alerts', 'googleanalytics' ); ?>
						</th>
					</tr>
					<tr>
						<td>
							<?php
							echo esc_html__(
								'Interested in receiving alerts via slack or email? Sign into your Social Optimization Platform account and activate it!
Enter your email and we\'ll send you an invite',
								'googleanalytics'
							);
							?>
						</td>
					</tr>
					<tr>
						<td><?php echo esc_html__( 'Enter your email to receive an invite', 'googleanalytics' ); ?>
							<input name="sharethis_invite_email" type="email" value=""
								<?php echo disabled( false === Ga_Helper::are_features_enabled() ); ?>
								placeholder="Your email address">
							<button <?php echo disabled( false === Ga_Helper::are_features_enabled() ); ?>
									type="submit" class="button button-primary"><?php echo esc_html__( 'Send' ); ?></button>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	<?php if ( Ga_Helper::are_features_enabled() ) : ?>
	ga_trending_loader.show();
	<?php endif; ?>
</script>
