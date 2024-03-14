<?php
/**
 * ECAE about page
 *
 * @package ECAE
 */

?>
<div class="wrap">
	<h2 class="nav-tab-wrapper">
		<a class="nav-tab" id="opt-overview-tab" href="#opt-overview"><?php esc_html_e( 'Overview', 'easy-custom-auto-excerpt' ); ?></a>
		<a class="nav-tab" id="opt-other-tab" href="#opt-other"><?php esc_html_e( 'Other Cool Stuff for Your Website', 'easy-custom-auto-excerpt' ); ?></a>
	</h2>
	<div id="ecae-boarding">
		<div id="opt-overview" class="group">
			<div class="ecae-content">
				<div class="row">
					<div class="col-half">

						<img class="logo-ecae" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>assets/about/ecae_about.png" alt="">

						<p><?php esc_html_e( 'Easy Custom Auto Excerpt is a WordPress plugin to cut/excerpt your posts displayed on home, search or archive pages. This plugin also enables you to customize the read more button text and thumbnail image. Just activate the plugin, configure some options and you are good to go.', 'easy-custom-auto-excerpt' ); ?></p>

						<div class="main-cta">
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=tonjoo_excerpt' ) ); ?>" class="btn btn-blue-welcome"><?php esc_html_e( 'Configure Excerpt', 'easy-custom-auto-excerpt' ); ?></a>
							<?php if ( ! function_exists( 'is_ecae_premium_exist' ) ) : ?>
								<a href="https://tonjoostudio.com/product/easy-custom-auto-excerpt-premium/?utm_source=wp_ecae&utm_medium=onboarding_overview&utm_campaign=upsell" class="btn btn-red-welcome"><?php esc_html_e( 'Upgrade to PRO version!', 'easy-custom-auto-excerpt' ); ?></a>
							<?php endif; ?>
						</div>
					</div>
					<div class="col-half">
						<div class="frame"><iframe css="display:block;margin:0px auto;max-height:300px" width="100%" height="300px" src="https://www.youtube.com/embed/ZZaXfrB4-68?ecver=1" frameborder="0" allowfullscreen=""></iframe></div>
					</div>
				</div>
			</div>

			<?php if ( ! function_exists( 'is_ecae_premium_exist' ) ) : ?>
				<div class="ecae-learn-more">
					<div class="row">
						<div class="col-half">
							<div class="banner-content">
								<div>
									<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>assets/about/banner_logo.png" alt="">
									<p><?php esc_html_e( 'Upgrade to PRO Version and get full benefit of ECAE', 'easy-custom-auto-excerpt' ); ?></p>
								</div>
								<div class="main-cta">
									<a href="https://tonjoostudio.com/product/easy-custom-auto-excerpt-premium/?utm_source=wp_ecae&utm_medium=onboarding_overview&utm_campaign=upsell" class="btn btn-orange-welcome">
										<i class="fa fa-rocket" aria-hidden="true"></i>
										<?php esc_html_e( 'Upgrade Now', 'easy-custom-auto-excerpt' ); ?>
									</a>
									<a href="http://wpexcerptplugin.com/wordpress-pro/?utm_source=wp_ecae&utm_medium=onboarding_overview&utm_campaign=upsell" class="btn btn-white-welcome">
										<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
										<?php esc_html_e( 'Learn More', 'easy-custom-auto-excerpt' ); ?>
									</a>
								</div>
							</div>
						</div>
						<div class="col-half">
							<div class="banner-content ecae-features">
								<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>assets/about/banner_feature_1.png" alt="">
								<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>assets/about/banner_feature_2.png" alt="">
								<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>assets/about/banner_feature_3.png" alt="">
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<div class="ecae-more" class="group">
				<div class="more-content">
					<div class="more-text">
						<h3><?php esc_html_e( 'Documentation', 'easy-custom-auto-excerpt' ); ?></h3>
						<p><?php esc_html_e( "Our online documentation will give you  important information about the plugin. This is an exceptional resource to start discovering the plugin's true potential.", 'easy-custom-auto-excerpt' ); ?></p>
					</div>
					<div class="more-btn">
						<a href="http://pustaka.tonjoostudio.com/plugins/easy-custom-auto-excerpt/" class="button-primary">
							<?php esc_html_e( 'Learn More', 'easy-custom-auto-excerpt' ); ?>
							<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
						</a>
					</div>
				</div>

				<div class="more-content">
					<div class="more-text">
						<h3><?php esc_html_e( 'Support Forum', 'easy-custom-auto-excerpt' ); ?></h3>
						<p><?php esc_html_e( 'We offer outstanding support through our forum. To get support first you need to register (create an account) and open a thread in our forum.', 'easy-custom-auto-excerpt' ); ?></p>
					</div>
					<div class="more-btn">
						<a href="https://forum.tonjoostudio.com/?utm_source=wp_ecae&utm_medium=onboarding_overview&utm_campaign=upsell" class="button-primary">
							<?php esc_html_e( 'Learn More', 'easy-custom-auto-excerpt' ); ?>
							<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
						</a>
					</div>
				</div>

				<div class="more-content">
					<div class="more-text">
						<h3><?php esc_html_e( 'Rate Us', 'easy-custom-auto-excerpt' ); ?></h3>
						<p><?php esc_html_e( 'If you have a moment, please help us spread the word by reviewing the plugin on WordPress.', 'easy-custom-auto-excerpt' ); ?></p>
					</div>
					<div class="more-btn">
						<a href="https://wordpress.org/support/plugin/easy-custom-auto-excerpt/reviews/#new-post" class="button-primary">
							<?php esc_html_e( 'Review Our Plugin', 'easy-custom-auto-excerpt' ); ?>
							<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
						</a>
					</div>
				</div>
			</div>
		</div>

		<div id="opt-other" class="group">
			<?php
			require 'class-tonjoo-plugins-upsell.php';
			$upsell = new Tonjoo_Plugins_Upsell( 'ecae-premium' );
			$upsell->render();
			?>
		</div>
	</div>
</div>
