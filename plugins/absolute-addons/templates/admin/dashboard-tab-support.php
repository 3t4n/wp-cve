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

use AbsoluteAddons\AbsolutePluginsServices\Absolute_Addons_Services;

$service = Absolute_Addons_Services::get_instance();
?>
	<div class="absp-settings-panel support">
		<div class="absp-settings-panel__body">
			<div class="support-wrapper">
				<div class="support-item">
					<img src="<?php absp_plugin_url( 'assets/images/video-icon.png' ); ?>" alt="<?php esc_attr_e( 'Check Our Video Tutorials', 'absolute-addons' ); ?>">
					<h3><?php esc_html_e( 'Video Tutorials', 'absolute-addons' ); ?></h3>
					<p><?php esc_html_e( 'Learn, how to use Absolute Widgets and manage them?', 'absolute-addons' ); ?></p>
					<div class="row">
						<div class="video-thumb">
							<a href="https://www.youtube.com/watch?v=59knMAjukUw" data-fancybox>
								<img src="<?php absp_plugin_url( 'assets/images/advance_accordion.jpg') ?>" alt="<?php esc_attr_e( 'Advance Accordion', 'absolute-addons' ); ?>">
								<h5><?php esc_html_e( 'Advance Accordion', 'absolute-addons' ); ?></h5>
							</a>
						</div>
						<div class="video-thumb">
							<a href="https://www.youtube.com/watch?v=Uyxm0XX9Y2A" data-fancybox>
								<img src="<?php absp_plugin_url( 'assets/images/advance_tab.jpg') ?>" alt="<?php esc_attr_e( 'Advance Tab', 'absolute-addons' ); ?>">
								<h5><?php esc_html_e( 'Advance Tab', 'absolute-addons' ); ?></h5>
							</a>
						</div>
						<div class="video-thumb">
							<a href="https://www.youtube.com/watch?v=fPpMO6kEWWU" data-fancybox>
								<img src="<?php absp_plugin_url( 'assets/images/call_to_action.jpg') ?>" alt="<?php esc_attr_e( 'Call To Action', 'absolute-addons' ); ?>">
								<h5><?php esc_html_e( 'Call To Action', 'absolute-addons' ); ?></h5>
							</a>
						</div>
						<div class="video-thumb">
							<a href="https://www.youtube.com/watch?v=K76eWrXz6TM" data-fancybox>
								<img src="<?php absp_plugin_url( 'assets/images/counter.jpg') ?>" alt="<?php esc_attr_e( 'Counter', 'absolute-addons' ); ?>">
								<h5><?php esc_html_e( 'Counter', 'absolute-addons' ); ?></h5>
							</a>
						</div>
						<div class="video-thumb">
							<a href="https://www.youtube.com/watch?v=3IQTWcSCPM0" data-fancybox>
								<img src="<?php absp_plugin_url( 'assets/images/faq.jpg') ?>" alt="<?php esc_attr_e( 'FAQ', 'absolute-addons' ); ?>">
								<h5><?php esc_html_e( 'FAQ', 'absolute-addons' ); ?></h5>
							</a>
						</div>
						<div class="video-thumb">
							<a href="https://www.youtube.com/watch?v=nho_QQ-rXgc" data-fancybox>
								<img src="<?php absp_plugin_url( 'assets/images/fun_fact.jpg') ?>" alt="<?php esc_attr_e( 'Fun Fact', 'absolute-addons' ); ?>">
								<h5><?php esc_html_e( 'Fun Fact', 'absolute-addons' ); ?></h5>
							</a>
						</div>
						<div class="video-thumb">
							<a href="https://www.youtube.com/watch?v=dT52WL9vzn8" data-fancybox>
								<img src="<?php absp_plugin_url( 'assets/images/icon_box.jpg') ?>" alt="<?php esc_attr_e( 'Icon Box', 'absolute-addons' ); ?>">
								<h5><?php esc_html_e( 'Icon Box', 'absolute-addons' ); ?></h5>
							</a>
						</div>
						<div class="video-thumb">
							<a href="https://www.youtube.com/watch?v=qNzc1Xi4vdA" data-fancybox>
								<img src="<?php absp_plugin_url( 'assets/images/image_carousel.jpg' ) ?>"  alt="<?php esc_attr_e( 'Image Carousel', 'absolute-addons' ); ?>">
								<h5><?php esc_html_e( 'Image Carousel', 'absolute-addons' ); ?></h5>
							</a>
						</div>
					</div>
					<div class="support-btn">
						<a href="https://www.youtube.com/watch?v=Pn840eDpNC4&list=UUPo-5ZNIiNBux6ciUlNcgfw"><?php esc_html_e( 'Check More Video', 'absolute-addons' ); ?></a>
					</div>
				</div>
			</div>

			<div class="support-wrapper">
				<div class="support-item">
					<img src="<?php absp_plugin_url( 'assets/images/question.png' ); ?>" alt="<?php esc_attr_e( 'Have questions?', 'absolute-addons' ); ?>">
					<h3><?php esc_html_e( 'FAQ', 'absolute-addons' ); ?></h3>
					<p><?php esc_html_e( 'Frequently Asked Questions', 'absolute-addons' ); ?></p>

					<div class="advance-accordion">
						<article class="content-entry accordion">
							<h4 class="collapse-head">
								<?php esc_html_e( 'How many free widgets Absolute Addons provide? ', 'absolute-addons' ); ?>
								<span class="accordion-icon-closed dashicons dashicons-plus-alt2"></span>
								<span class="accordion-icon-opened dashicons dashicons-minus"></span>
							</h4>
							<div class="collapse-body">
								<p><?php esc_html_e( 'To enhance your Elementor working experience Absolute addons will offer 13 free creative and professionally developed widgets.', 'absolute-addons' ); ?></p>
							</div>
						</article>
						<article class="content-entry accordion">
							<h4 class="collapse-head">
								<?php esc_html_e( 'Where to find the documentation for Absolute addon? ', 'absolute-addons' ); ?>
								<span class="accordion-icon-closed dashicons dashicons-plus-alt2"></span>
								<span class="accordion-icon-opened dashicons dashicons-minus"></span>
							</h4>
							<div class="collapse-body">
								<p><?php esc_html_e( 'You will find Absolute Addons documentation from here. If you are stuck with anything, feel free to contact our friendly and professional support team. ', 'absolute-addons' ); ?></p>
							</div>
						</article>
						<article class="content-entry accordion">
							<h4 class="collapse-head">
								<?php esc_html_e( 'How often do you update Absolute Addons? ', 'absolute-addons' ); ?>
								<span class="accordion-icon-closed dashicons dashicons-plus-alt2"></span>
								<span class="accordion-icon-opened dashicons dashicons-minus"></span>
							</h4>
							<div class="collapse-body">
								<p><?php esc_html_e( 'With our fully dedicated team, we are always working to improve and add new features to Absolute Addons. In general, we always try to update our plugin on a weekly basis.', 'absolute-addons' ); ?></p>
							</div>
						</article>
						<article class="content-entry accordion">
							<h4 class="collapse-head">
								<?php esc_html_e( 'Is Absolute Addons responsive for the smaller devices? ', 'absolute-addons' ); ?>
								<span class="accordion-icon-closed dashicons dashicons-plus-alt2"></span>
								<span class="accordion-icon-opened dashicons dashicons-minus"></span>
							</h4>
							<div class="collapse-body">
								<p><?php esc_html_e( 'Yes, all the widgets and elements are fully responsive for all the available devices. ', 'absolute-addons' ); ?></p>
							</div>
						</article>
						<article class="content-entry accordion">
							<h4 class="collapse-head">
								<?php esc_html_e( 'Does Absolute Addons support multisite? ', 'absolute-addons' ); ?>
								<span class="accordion-icon-closed dashicons dashicons-plus-alt2"></span>
								<span class="accordion-icon-opened dashicons dashicons-minus"></span>
							</h4>
							<div class="collapse-body">
								<p><?php esc_html_e( 'Yes, Absolute Addons is fully multisite compatible.', 'absolute-addons' ); ?></p>
							</div>
						</article>
						<article class="content-entry accordion">
							<h4 class="collapse-head">
								<?php esc_html_e( 'Do Absolute Addons require any coding skills? ', 'absolute-addons' ); ?>
								<span class="accordion-icon-closed dashicons dashicons-plus-alt2"></span>
								<span class="accordion-icon-opened dashicons dashicons-minus"></span>
							</h4>
							<div class="collapse-body">
								<p><?php esc_html_e( 'No, you don’t need any coding skills to use our widgets. All of our widgets come with drag & drop capability. ', 'absolute-addons' ); ?></p>
							</div>
						</article>
					</div>

					<div class="support-btn">
						<a href="https://absoluteplugins.com/docs/docs/absolute-addons/?utm_source=plugin-dashboard&utm_medium=support-tab&utm_campaign=show-more-docs"><?php esc_html_e( 'Show More', 'absolute-addons' ); ?></a>
					</div>
				</div>
			</div>
		</div>

		<div class="absp-settings-panel__footer">
			<div class="absp-cta-area">
				<div class="absp-cta-wrapper" style="align-items: flex-start;">
					<div class="absp-left-content">
						<h3><?php esc_html_e( 'Need Support Faster!', 'absolute-addons' ); ?></h3>
						<p style="margin-bottom:0;"><?php esc_html_e( "We don't share your data with third parties, while keeping them encrypted in our server.", 'absolute-addons' ); ?></p>
						<p style="font-size:14px;margin-top:0;"><?php esc_html_e( "We use this data for support, debugging and for improving this plugin and it's performance.", 'absolute-addons' ); ?></p>
						<ul class="what-collected" style="display: none">
							<?php foreach ( $service->get_data_we_collect() as $line ) { ?>
								<li><?php echo esc_html( $line ); ?></li>
							<?php } ?>
						</ul>
					</div>
					<div class="absp-cta-btn" style="margin: 17px auto;text-align:center;display:block;">
						<?php if ( ! $service->is_tracking_allowed() ) { ?>
						<a href="<?php echo esc_url( $service->opt_in_url() ); ?>#support" style="display:inline-block" class="cta-btn"><?php esc_html_e( 'Enable Data Sharing', 'absolute-addons' ); ?></a>
						<?php } else { ?>
						<a
							href="<?php echo esc_url( $service->opt_out_url() ); ?>#support"
							onclick="return confirm('<?php esc_attr_e( 'Are you sure you want disable data sharing?', 'absolute-addons' ); ?>')"
							style="--clr-white: #f02f5e;background: transparent;border: 1px solid #f02f5e;padding: 10px 20px;font-weight: 400;font-size:14px;"
							class="cta-btn"
						><?php esc_html_e( 'Disable Data Sharing', 'absolute-addons' ); ?></a>
						<?php } ?>
						<span class="terms" style="font-size:0.75em;margin-top:10px;">
							<a href="#" onclick="jQuery('.what-collected').toggleSlide(); return false;"><?php esc_html_e( 'What we collect collecting?', 'absolute-addons' ); ?></a>
							<?php
							printf(
								/* translators: 1: Privacy Policy Link, 2: Terms Links */
								esc_html__( 'Please read our %1$s and %2$s', 'absolute-addons' ),
								'<a href="https://absoluteplugins.com/privacy-policy/?utm_source=plugin-dashboard&utm_medium=support-tab&utm_campaign=tracker-policy" target="_blank" rel="noopener">' . esc_html__( 'Privacy Policy', 'absolute-addons' ) . '</a>',
								'<a href="https://absoluteplugins.com/terms-of-service/?utm_source=plugin-dashboard&utm_medium=support-tab&utm_campaign=tracker-terms" target="_blank" rel="noopener">' . esc_html__( 'Terms of Services', 'absolute-addons' ) . '</a>'
							);
							?>
						</span>
					</div>
				</div>
			</div>

			<div class="support-ticket">
				<div class="support-ticket-thumb">
					<img src="<?php absp_plugin_url( 'assets/images/ticket-thumb.png' ); ?>" alt="<?php esc_attr_e( 'Get Support', 'absolute-addons' ); ?>">
				</div>
				<div class="support-ticket-content">
					<h3><?php esc_html_e( 'Need more support?', 'absolute-addons' ); ?></h3>
					<p></p>
					<span>
					<?php
					printf(
						/* translators: 1. WordPress Support Forum Link, 2. Facebook Page Link, 3. Live Chat Site Link. */
						esc_html__( 'Stuck with something? Get help from the community on %1$s or %2$s. In case of emergency, initiate a live chat at %3$s website.', 'absolute-addons' ),
						'<a href="https://wordpress.org/support/plugin/absolute-addons/">' . esc_html__( 'WordPress.org Forum', 'absolute-addons' ) . '</a>',
						'<a href="https://www.facebook.com/AbsoluteAddons/">' . esc_html__( 'Facebook Community', 'absolute-addons' ) . '</a>',
						'<a href="https://absoluteplugins.com/?utm_source=plugin-dashboard&utm_medium=support-tab&utm_campaign=support-live-chat">' . esc_html__( 'Absolute Addons', 'absolute-addons' ) . '</a>'
					);
					?>
					</span>
				</div>
				<div class="support-button">
					<a href="https://absoluteplugins.com/open-support-request/?utm_source=plugin-dashboard&utm_medium=open-ticket&utm_campaign=support-request"><?php esc_html_e( 'Open a Ticket', 'absolute-addons' ); ?></a>
				</div>
			</div>
		</div>
	</div>
<?php
// End of file dashboard-tab-support.php.
