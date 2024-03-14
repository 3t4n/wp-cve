<?php
/**
 * Plugin Boxes Doc Comment
 *
 * @category  Views
 * @package   gdpr-cookie-compliance
 * @author    Moove Agency
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$gdpr_controller = new Moove_GDPR_Controller();
$plugin_details  = $gdpr_controller->get_gdpr_plugin_details( 'gdpr-cookie-compliance' );
?>
<div class="moove-plugins-info-boxes">

	<?php ob_start(); ?>
	<div class="m-plugin-box m-plugin-box-highlighted">
		<div class="box-header">
			<h4><?php esc_html_e( 'Premium Add-On', 'gdpr-cookie-compliance' ); ?></h4>
		</div>
		<!--  .box-header -->
		<div class="box-content">
			<div class="gdpr-faq-forum-content">
				<p><span class="gdpr-chevron-left">&#8250;</span> <?php esc_html_e( 'Includes a cookiewall layout', 'gdpr-cookie-compliance' ); ?></p>
				<p><span class="gdpr-chevron-left">&#8250;</span> <?php esc_html_e( 'Export & import', 'gdpr-cookie-compliance' ); ?></p>
        <p><span class="gdpr-chevron-left">&#8250;</span> <?php esc_html_e( 'Consent Log', 'gdpr-cookie-compliance' ); ?></p>
				<p><span class="gdpr-chevron-left">&#8250;</span> <?php esc_html_e( 'WordPress Multisite support', 'gdpr-cookie-compliance' ); ?></p>
				<p><span class="gdpr-chevron-left">&#8250;</span> <?php esc_html_e( 'Accept cookies on scroll', 'gdpr-cookie-compliance' ); ?></p>
				<p><span class="gdpr-chevron-left">&#8250;</span> <?php esc_html_e( 'Consent Analytics', 'gdpr-cookie-compliance' ); ?></p>
				<p><span class="gdpr-chevron-left">&#8250;</span> <?php esc_html_e( 'Display cookie banner for EU visitors only', 'gdpr-cookie-compliance' ); ?></p>
				<p><span class="gdpr-chevron-left">&#8250;</span> <?php esc_html_e( 'Language specific scripts', 'gdpr-cookie-compliance' ); ?></p>
				<p><span class="gdpr-chevron-left">&#8250;</span> <?php esc_html_e( 'and more...', 'gdpr-cookie-compliance' ); ?></p>
      </div>
			<!-- gdpr-faq-forum-content -->
			<hr />
			<a href="https://www.mooveagency.com/wordpress-plugins/gdpr-cookie-compliance/" target="_blank" class="plugin-buy-now-btn"><?php esc_html_e( 'Buy Now', 'gdpr-cookie-compliance' ); ?></a>

		</div>
		<!--  .box-content -->
	</div>
	<!--  .m-plugin-box -->
	<?php
		$content = apply_filters( 'gdpr_cookie_compliance_premium_section', ob_get_clean() );
		apply_filters( 'gdpr_cc_keephtml', $content, true );
		$support_class = apply_filters( 'gdpr_support_sidebar_class', '' );
	?>
	
	<div class="m-plugin-box">
		<div class="box-header">
			<h4><?php esc_html_e( 'Find this plugin useful?', 'gdpr-cookie-compliance' ); ?></h4>
		</div>
		<!--  .box-header -->
		<div class="box-content">

			<p><?php esc_html_e( 'You can help other users find it too by', 'gdpr-cookie-compliance' ); ?> <a href="https://wordpress.org/support/plugin/gdpr-cookie-compliance/reviews/?rate=5#new-post" target="_blank"><?php esc_html_e( 'rating this plugin', 'gdpr-cookie-compliance' ); ?></a>.</p>
			<?php if ( $plugin_details ) : ?>
				<hr />
				<div class="plugin-stats">
					<div class="plugin-download-ainstalls-cnt">
						<div class="plugin-downloads">
							<?php esc_html_e( 'Downloads', 'gdpr-cookie-compliance' ); ?>: <strong><?php echo number_format( $plugin_details->downloaded, 0, '', ',' ); ?></strong>
						</div>
						<!--  .plugin-downloads -->
						<div class="plugin-active-installs">
							<?php esc_html_e( 'Active installations', 'gdpr-cookie-compliance' ); ?>: <strong><?php echo number_format( $plugin_details->active_installs, 0, '', ',' ); ?>+</strong>
						</div>
						<!--  .plugin-downloads -->
					</div>
					<!--  .plugin-download-ainstalls-cnt -->

					<div class="plugin-rating">
						<a href="https://wordpress.org/support/plugin/gdpr-cookie-compliance/reviews/" target="_blank">
							<span class="plugin-stars">
								<?php
									$rating_val = $plugin_details->rating * 5 / 100;
								if ( $rating_val > 0 ) :
									$args   = array(
										'rating' => $rating_val,
										'number' => $plugin_details->num_ratings,
										'echo'   => false,
									);
									$rating = wp_star_rating( $args );
									endif;
								if ( $rating ) :
									apply_filters( 'gdpr_cc_keephtml', $rating, true );
									endif;
								?>
							</span>
						</a>
					</div>
					<!--  .plugin-rating -->
				</div>
				<!--  .plugin-stats -->
			<?php endif; ?>
		</div>
		<!--  .box-content -->
	</div>
	<!--  .m-plugin-box -->

	<div class="m-plugin-box">
		<div class="box-header">
			<h4><?php esc_html_e( 'User Activity Tracking and Log', 'gdpr-cookie-compliance' ); ?></h4>
		</div>
		<!--  .box-header -->
		<div class="box-content">
			<a href="https://www.mooveagency.com/wordpress-plugins/user-activity-tracking-and-log/" target="_blank">
				<img src='<?php echo trailingslashit( moove_gdpr_get_plugin_directory_url() ); ?>dist/images/uat-promo-wp.png?rev=<?php echo MOOVE_GDPR_VERSION; ?>'/>
			</a>
			<hr>
			<p><?php esc_html_e( 'Track user activity & duration on your website with this incredibly powerful, easy-to-use and well supported plugin.', 'gdpr-cookie-compliance' ); ?></p>

			<hr />
			<a href="https://www.mooveagency.com/wordpress-plugins/user-activity-tracking-and-log/" target="_blank" class="plugin-buy-now-btn"><?php esc_html_e( 'Free trial', 'gdpr-cookie-compliance' ); ?></a>
		</div>
		<!--  .box-content -->
	</div>
	<!--  .m-plugin-box -->
</div>
<!--  .moove-plugins-info-boxes -->
