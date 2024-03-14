<?php
/**
 * Template for sidebar on main cloning admin page.
 *
 * @package NS_Cloner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

$slider_headers = apply_filters(
	'ns_cloner_header_slider',
	array(
		'mcf'    => array(
			'deps'   => is_plugin_active( 'woocommerce/woocommerce.php' ) && ! is_plugin_active( 'woocommerce-amazon-fulfillment/woocommerce-amazon-fulfillment.php' ),
			'header' => array(
				'image' => NS_CLONER_V4_PLUGIN_URL . 'images/mcf-amazon-logo.png',
				'title' => __( 'You sell it, Amazon ships it', 'ns-cloner-site-copier' ),
				'text'  => __( 'Take advantage of Amazon’s fulfillment network and streamline your shipping processes with the WooCommerce Amazon Fulfillment plugin. Another plugin built by Never Settle & supported by Amazon.', 'ns-cloner-site-copier' ),
			),
			'cta'    => array(
				'button' => array(
					'link' => 'https://neversettle.it/woocommerce-amazon-fulfilment?utm_source=cloner&utm_medium=banner&utm_campaign=sidebar',
					'text' => __( 'Check It Out', 'ns-cloner-site-copier' ),
				),
			),
		),
		'agency' => array(
			'deps'   => true,
			'header' => array(
				'image' => NS_CLONER_V4_PLUGIN_URL . 'images/neversettle-logo.svg',
				'title' => __( 'More than just plugins', 'ns-cloner-site-copier' ),
				'text'  => __( 'Never Settle are an award-winning world-class digital agency that helps brands build bigger stories through design & tech.', 'ns-cloner-site' ),
			),
			'cta'    => array(
				'button' => array(
					'link' => 'http://neversettle.it/?utm_campaign=in+plugin+referral&utm_source=ns-cloner&utm_medium=plugin&utm_content=social+button+to+ns',
					'text' => __( 'Check Us Out', 'ns-cloner-site-copier' ),
				),
			),
		),
		'pro'    => array(
			'deps'   => ! is_plugin_active( 'ns-cloner-pro/ns-cloner-pro.php' ),
			'header' => array(
				'image' => NS_CLONER_V4_PLUGIN_URL . 'images/ns-cloner-top-logo.png',
				'title' => __( 'Want even more cloning power?', 'ns-cloner-site-copier' ),
				'text'  => '',
			),
			'cta'    => array(
				'button' => array(
					'link' => NS_CLONER_PRO_URL,
					'text' => __( 'Go Pro', 'ns-cloner-site-copier' ),
				),
			),
		),
	)
);

?>
<div class="ns-cloner-sidebar">

	<div class="ns-side-widget ns-slider-widget ns-cloner-slider">
		<div class="ns-side-widget-content">
			<?php
			foreach ( $slider_headers as $key => $slider_header ) {
				if ( $slider_header['deps'] ) {
					?>
					<div class="ns-cloner-banner-header ns-cloner-banner">
						<div class="ns-cloner-banner-header-text">
							<?php if ( isset( $slider_header['header']['image'] ) ) : ?>
								<a href="<?php echo esc_url( $slider_header['cta']['button']['link'] ); ?>" target="_blank">
									<img class="app-logo" src="<?php echo esc_url( $slider_header['header']['image'] ); ?>" />
								</a>
							<?php endif; ?>
							<?php if ( isset( $slider_header['header']['title'] ) ) : ?>
								<h3 class="heading"><?php echo esc_html( $slider_header['header']['title'] ); ?></h3>
							<?php endif; ?>
							<?php if ( isset( $slider_header['header']['text'] ) ) : ?>
								<p class="center"><?php echo esc_html( $slider_header['header']['text'] ); ?></p>
							<?php endif; ?>
						</div>
						<a class="button transparent-button button-bottom" href="<?php echo esc_url( $slider_header['cta']['button']['link'] ); ?>" target="_blank">
							<?php echo esc_html( $slider_header['cta']['button']['text'] ); ?>
						</a>
					</div>
					<?php
				}
			}
			?>
		</div>
	</div>

	<?php if ( NS_Cloner_Reviews::instance()->review_request() ) : ?>
		<div class="ns-side-widget ns-rate-widget">
			<div class="ns-side-widget-content">
				<h3 class="title"><?php esc_html_e( 'Love Cloner?', 'ns-cloner-site-copier' ); ?></h3>
				<p class="sub-title">
					<?php esc_html_e( 'We’d love it if you reviewed it!', 'ns-cloner-site-copier' ); ?>
				</p>
				<p>
					<?php esc_html_e( 'If the Cloner has saved you lots of time, tell everyone with a 5-star rating!', 'ns-cloner-site-copier' ); ?>
				</p>
				<p>
					<a href="http://wordpress.org/support/view/plugin-reviews/ns-cloner-site-copier?rate=5#postform" target="_blank" class="button button-yellow button-bottom ns-cloner-submit-review">
						<?php esc_html_e( 'Rate it 5 Stars', 'ns-cloner-site-copier' ); ?>
					</a>
				</p>
			</div>
		</div>
	<?php endif; ?>

	<div class="ns-side-widget ns-support-widget">
		<div class="ns-side-widget-content">
			<h3 class="title"><?php esc_html_e( 'Here to help', 'ns-cloner-site-copier' ); ?></h3>
			<p class="sub-title">
				<?php esc_html_e( 'Need assistance? Got a great idea?.', 'ns-cloner-site-copier' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'Got a question? Got stuck? Or just have an awesome idea on how we can make Cloner even better? Our support teams are ready to help you out.', 'ns-cloner-site-copier' ); ?>
			</p>
			<p>
				<a href="https://wpsitecloner.com/support" class="button button-purple button-bottom" data-cloner-modal="copy-logs" target="_blank">
					<?php esc_html_e( 'Support & Feature Requests', 'ns-cloner-site-copier' ); ?>
				</a>
			</p>
		</div>
	</div>

	<?php if ( ! empty( ns_cloner()->log->get_recent_logs() ) ) : ?>
	<div class="ns-cloner-extra-modal" id="copy-logs">
		<div class="ns-cloner-extra-modal-content">
			<h3><?php esc_html_e( 'Before you go...', 'ns-cloner-site-copier' ); ?></h3>
			<p>
				<?php esc_html_e( 'If you\'re going to open a support request, could you please copy the log urls listed below and paste them at the bottom of your support request so we can give you better and faster help? Thank you!', 'ns-cloner-site-copier' ); ?>
			</p>
			<p class="description">
				<?php esc_html_e( '(Please send privately, not on a forum - some sensitive info from your database could be included in the logs.)', 'ns-cloner-site-copier' ); ?>
			</p>
			<textarea onclick="this.select();return false;"><?php echo esc_textarea( join( "\n", ns_cloner()->log->get_recent_logs() ) ); ?></textarea>
			<p>
				<a href="https://wpsitecloner.com/support" class="button" target="_blank"><?php esc_html_e( 'Continue to Support', 'ns-cloner-site-copier' ); ?></a>
			</p>
		</div>
	</div>
	<?php endif; ?>

</div>
