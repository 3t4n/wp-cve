<?php

/**
 * Dashboard Main Layout
 *
 * @package ABSP
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

$changelogs = self::get_changelogs();
?>
	<div class="absp-settings-panel home">
		<div class="absp-settings-panel__header">
			<div class="absp-header-banner">
				<img class="d-md-none" src="<?php absp_plugin_url( 'assets/images/mobile-banner.svg' ); ?>" alt="<?php esc_attr_e( 'AbsoluteAddons', 'absolute-addons' ); ?>">
				<img class="d-none d-md-block d-lg-none" src="<?php absp_plugin_url( 'assets/images/tab-banner.svg' ); ?>" alt="<?php esc_attr_e( 'AbsoluteAddons', 'absolute-addons' ); ?>">
				<img class="d-none d-lg-block" src="<?php absp_plugin_url( 'assets/images/dashboard-banner.svg' ); ?>" alt="<?php esc_attr_e( 'AbsoluteAddons', 'absolute-addons' ); ?>">
				<div class="banner-shadow"></div>
			</div>
		</div>
		<div class="absp-settings-panel__body">
			<div class="dashboard-card-wrapper">
				<div class="col-md-4">
					<div class="dashboard-card">
						<img src="<?php absp_plugin_url( 'assets/images/card1.png' ); ?>" alt="" class="card-image">
						<h3 class="card-title mt-50"><?php esc_html_e( 'Documentation', 'absolute-addons' ); ?></h3>
						<p><?php esc_html_e( 'Let’s spend some time with our detailed documentation to become familiar with Absolute Addons. Build a full featured website with our awesome widgets for you and your clients.', 'absolute-addons' ); ?></p>
						<a href="https://absoluteplugins.com/docs/docs/absolute-addons/" class="card-btn" target="_blank"><?php esc_html_e( 'Read Now', 'absolute-addons' ); ?></a>
					</div>
				</div>
				<div class="col-md-4">
					<div class="dashboard-card">
						<img src="<?php absp_plugin_url( 'assets/images/card2.png' ); ?>" alt="" class="card-image">
						<h3 class="card-title"><?php esc_html_e( 'Request a feature', 'absolute-addons' ); ?></h3>
						<p><?php esc_html_e( 'Are we missing any features for your website? Feel free to submit a feature request and help us to make Absolute Addons better.', 'absolute-addons' ); ?></p>
						<a href="https://absoluteplugins.com/request-new-features/" class="card-btn" target="_blank"><?php esc_html_e( 'Request', 'absolute-addons' ); ?></a>
					</div>
				</div>
				<div class="col-md-4">
					<div class="dashboard-card">
						<img src="<?php absp_plugin_url( 'assets/images/card3.png' ); ?>" alt="" class="card-image">
						<h3 class="card-title"><?php esc_html_e( 'Show your love', 'absolute-addons' ); ?></h3>
						<p><?php esc_html_e( 'It’s our pleasure to have you in our Absolute Addons family. If our plugin brings happiness to your working, review our plugin and spread the love to encourage us.', 'absolute-addons' ); ?></p>
						<a href="https://wordpress.org/plugins/absolute-addons/#reviews" class="card-btn" target="_blank"><?php esc_html_e( 'Rate Us', 'absolute-addons' ); ?></a>
					</div>
				</div>
			</div>
			<?php if ( ! empty( $changelogs ) ) { ?>
				<?php
				$current_version = reset( $changelogs );
				$social          = self::get_social_links();
				$badges          = self::get_badges_translations();
				?>
			<div class="dashboard-changelog">
				<div class="changelog-top-content">
					<div class="changelog-left">
						<img src="<?php absp_plugin_url( 'assets/images/changelog.png' ); ?>" alt="<?php esc_attr_e( 'Changelogs', 'absolute-addons' ); ?>">
						<h3><?php esc_html_e( 'Changelogs', 'absolute-addons' ); ?></h3>
					</div>
					<div class="changelog-right">
						<div class="update-version">
							<span class="version-text"><?php esc_html_e( 'Last update:', 'absolute-addons' ); ?></span>
							<span class="version-link">
								<a href="<?php echo esc_url( $current_version['url'] ); ?>">
									<?php
									printf(
									/* translators: 1. Version Number. */
										esc_html__( 'Version %1$s', 'absolute-addons' ),
										esc_attr( $current_version['version'] )
									);
									?>
								</a>
								<?php
								$format = get_option( 'date_format' );
								$date   = $current_version['date'];
								$date   = is_numeric( $date ) ? gmdate( $format, $date ) : gmdate( $format, strtotime( $date ) );
								printf(
									/* translators: 1. Version release date. */
									esc_html__( 'on %s', 'absolute-addons' ),
									'<span>' . esc_html( $date ) . '</span>'
								);
								?>
							</span>
						</div>
						<?php if ( ! empty( $social ) ) { ?>
						<div class="social-content">
							<span class="social-content-text"><?php esc_html_e('Follow us:', 'absolute-addons'); ?></span>
							<div class="social-link">
								<?php foreach ( $social as $item ) { ?>
									<a href="<?php echo esc_url( $item['url'] ); ?>">
										<span class="<?php echo esc_attr( $item['icon'] ); ?>" aria-hidden="true"></span>
										<span class="screen-reader-text"><?php echo esc_html( $item['label'] ); ?></span>
									</a>
								<?php } ?>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="changelog-bottom-content">
					<?php foreach ( $changelogs as $changelog ) { ?>
						<?php
						if ( empty( $changelog['logs'] ) ) {
							continue;
						}
						?>
						<div class="changelog-details">
							<div class="details-text">
								<span>
									<?php
									printf(
									/* translators: 1. Version Number. */
										esc_html__( 'Version %1$s', 'absolute-addons' ),
										esc_attr( $current_version['version'] )
									);
									?>
								</span>
								<?php
								$format = get_option( 'date_format' );
								$date   = $current_version['date'];
								$date   = is_numeric( $date ) ? gmdate( $format, $date ) : gmdate( $format, strtotime( $date ) );
								printf(
									/* translators: 1. Version release date. */
									esc_html__( 'on %s', 'absolute-addons' ),
									'<span>' . esc_html( $date ) . '</span>'
								);
								?>
							</div>
							<ul class="details">
								<?php foreach ( $changelog['logs'] as $log ) { ?>
									<?php
									list( $badge, $message ) = $log;
									$badge = $badges['map'][ $badge ];
									?>
									<li>
										<div class="details-info">
											<span class="badge <?php echo esc_attr( $badge ); ?>">
												<?php echo esc_html( $badges['i18n'][ $badge ] ); ?>
											</span>
										</div>
										<p><?php echo esc_html( $message ); ?></p>
									</li>
								<?php } ?>
							</ul>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
		</div>
		<div class="absp-settings-panel__footer"></div>
	</div>
<?php
// End of file dashboard-tab-dashboard.php.
