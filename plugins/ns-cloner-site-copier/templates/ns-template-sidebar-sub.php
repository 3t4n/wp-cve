<?php
/**
 * Sidebar for settings pages
 *
 * @package NS_Cloner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<div class="ns-cloner-sidebar">

	<div class="ns-side-widget ns-links-widget">
		<div class="ns-side-widget-content">
			<img src="<?php echo esc_url( NS_CLONER_V4_PLUGIN_URL . 'images/neversettle-logo.svg' ); ?>" alt="NeverSettle Logo"/>
			<h3><?php esc_html_e( 'More Than Just Plugins', 'ns-cloner-site-copier' ); ?></h3>
			<a class="button transparent-button button-bottom" href="http://neversettle.it/?utm_campaign=in+plugin+referral&utm_source=ns-cloner&utm_medium=plugin&utm_content=social+button+to+ns" target="_blank">
				<?php esc_html_e( 'Check us out', 'ns-cloner-site-copier' ); ?>
			</a>
		</div>
	</div>

	<div class="ns-side-widget ns-support-widget">
		<div class="ns-side-widget-content">
			<h3 class="title"><?php esc_html_e( 'Here to help', 'ns-cloner-site-copier' ); ?></h3>
			<p class="sub-title">
				<?php esc_html_e( 'Need assistance? Got a great idea?', 'ns-cloner-site-copier' ); ?>
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
