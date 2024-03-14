<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://webmuehle.at
 * @since      1.1.0
 *
 * @package    Settings
 * @subpackage Courtres/admin/settigns
 */
?>

<?php
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die();
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php
require 'courtres-notice-upgrade.php';
?>

	<div class="wrap">
		<h1 class="wp-heading-inline"><?php echo esc_html__( 'Upgrade', 'court-reservation' ); ?></h1>
		<hr class="wp-header-end">

		<div class="cr-tabs-wrap">
			<div class="item1">
				<div class="cr-widget-right">
					<?php
					require 'courtres-widget-upgrade.php';
					?>
				</div>
			</div>
			<div  class="item2">
				<h2 class="nav-tab-wrapper wp-clearfix">
					<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=0' )); ?>" class="nav-tab">
						<?php echo esc_html__( 'Courts', 'court-reservation' ); ?>
					</a>
					<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=1' )); ?>" class="nav-tab">
						<?php echo esc_html__( 'Pyramids', 'court-reservation' ); ?>
					</a>
					<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=2' )); ?>" class="nav-tab"><?php echo esc_html__( 'Settings', 'court-reservation' ); ?></a>
					<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=3' )); ?>" class="nav-tab">
						<?php echo esc_html__( 'User Interface', 'court-reservation' ); ?>
					</a>
					<?php if ( ! cr_fs()->is_plan( 'ultimate' ) ) { ?>
						<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=4' )); ?>" class="nav-tab nav-tab-active">
							<?php echo esc_html__( 'Upgrade', 'court-reservation' ); ?>
						</a>
					<?php } ?>
				</h2>

				<div id="packages" style="width: 100%;">
					<ul>
						<li class="plan free-pricing">
							<article class="card current" data-plan-id="4902">
								<header>
									<h2><?php echo esc_html__( 'Lite', 'court-reservation' ); ?></h2>
									<h3><?php echo esc_html__( 'Basic Features', 'court-reservation' ); ?></h3>
								</header>
								<div class="body">
									<div class="offer">
										<span class="price"><var><?php echo esc_html__( 'Free', 'court-reservation' ); ?></var></span>
										<span class="renewals-amount" style="display: none;"></span>
										<span class="period"><?php echo esc_html__( 'Billed Annually', 'court-reservation' ); ?></span>
										<div class="tooltip-wrapper info-icon">
											<span class="license" title="If you are running a multi-site network, each site in the network requires a license.">
												<?php echo esc_html__( 'Single Site', 'court-reservation' ); ?>
											</span>
										</div>
									</div>
									<div class="support">
										<span><var><?php echo esc_html__( 'No Support', 'court-reservation' ); ?></var></span>
									</div>
								</div>
							</article>
						</li>
						<li class="plan premium">
							<article class="card featured" data-plan-id="4903">
								<header>
									<div class="ribbon">
										<h4><?php echo esc_html__( 'Most Popular', 'court-reservation' ); ?></h4><i class="left"></i><i class="right"></i>
									</div>
									<h2><?php echo esc_html__( 'Premium', 'court-reservation' ); ?></h2>
									<h3><?php echo esc_html__( 'more courtes', 'court-reservation' ); ?></h3>
									<h3><?php echo esc_html__( 'more members', 'court-reservation' ); ?></h3>
									<h3><?php echo esc_html__( 'E-Mail notifications', 'court-reservation' ); ?></h3>
									<h3><?php echo esc_html__( '24/7 E-Mail-Support', 'court-reservation' ); ?></h3>
								</header>
								<div class="body">
									<div class="offer">
										<span class="price">
											<span>
												<b class="currency">$</b><var>129</var>
											</span>
											<span>
												<b class="price-decimal">99</b>
												<sub class="billing-cycle">/ <?php echo esc_html__( 'Year', 'court-reservation' ); ?></sub>
											</span>
										</span>
										<span class="period"><?php echo esc_html__( 'Billed Annually', 'court-reservation' ); ?></span>
										<div class="tooltip-wrapper info-icon">
											<span class="license" title="If you are running a multi-site network, each site in the network requires a license.">
												<?php echo esc_html__( 'Single Site', 'court-reservation' ); ?>
											</span
										></div>
									</div>
									<div class="support">
										<span><var><?php echo esc_html__( 'Priority E-Mail & Help Center Support', 'court-reservation' ); ?></var></span>
									</div>
								</div>
							</article>
						</li>
						<li class="plan ultimate">
							<article class="card featured" data-plan-id="4903">
								<header>
									<h2><?php echo esc_html__( 'Ultimate', 'court-reservation' ); ?></h2>
									<h3><?php echo esc_html__( 'more courtes', 'court-reservation' ); ?></h3>
									<h3><?php echo esc_html__( 'more members', 'court-reservation' ); ?></h3>
									<h3><?php echo esc_html__( 'E-Mail notifications', 'court-reservation' ); ?></h3>
									<h3><?php echo esc_html__( '24/7 E-Mail-Support', 'court-reservation' ); ?></h3>
									<h3 class='ultimate-h'><?php echo esc_html__( 'Ladder Competitions', 'court-reservation' ); ?></h3>
								</header>
								<div class="body">
									<div class="offer">
										<span class="price">
											<span>
												<b class="currency">$</b><var>199</var>
											</span>
											<span>
												<b class="price-decimal">99</b>
												<sub class="billing-cycle">/ <?php echo esc_html__( 'Year', 'court-reservation' ); ?></sub>
											</span>
										</span>
										<span class="period"><?php echo esc_html__( 'Billed Annually', 'court-reservation' ); ?></span>
										<div class="tooltip-wrapper info-icon">
											<span class="license" title="If you are running a multi-site network, each site in the network requires a license.">
												<?php echo esc_html__( 'Single Site', 'court-reservation' ); ?>
											</span
										></div>
									</div>
									<div class="support">
										<span><var><?php echo esc_html__( 'Priority E-Mail & Help Center Support', 'court-reservation' ); ?></var></span>
									</div>
								</div>
							</article>
						</li>
					</ul>


				</div>

		</div>

	</div>
