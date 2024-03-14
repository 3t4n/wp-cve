<?php


use WPDeskFIVendor\WPDesk\Library\Marketing\Boxes\MarketingBoxes;

/**
 * @var MarketingBoxes $boxes
 */
$boxes = $params['boxes'] ?? false;
if ( ! $boxes ) {
	return;
}
?>
<div class="wrap">
	<div id="marketing-page-wrapper">
		<?php echo $boxes->get_boxes()->get_all(); ?>

		<div class="marketing-buttons">
			<a class="button button-primary button-support confirm" data-confirm="confirm-support" href="#"><?php esc_html_e( 'Get support', 'flexible-invoices' ); ?></a>
			<a class="button button-primary button-idea" href="https://flexibleinvoices.com/ideas/?utm_source=fi-support-tab&utm_campaign=fi-support-tab&utm_medium=button"><?php esc_html_e( 'Share idea', 'flexible-invoices' ); ?></a>
		</div>

		<div class="wpdesk-tooltip-shadow"></div>
		<div id="confirm-support" class="wpdesk-tooltip wpdesk-tooltip-confirm">
			<span class="close-modal close-modal-button"><span class="dashicons dashicons-no-alt"></span></span>
			<h3><?php esc_html_e( 'Before sending a message please:', 'flexible-invoices' ); ?></strong></h3>
			<ul>
				<li><?php esc_html_e( 'Prepare the information about the version of WordPress, WooCommerce, and Flexible Invoices (preferably your system status from WooCommerce->Status)', 'flexible-invoices' ); ?></li>
				<li><?php esc_html_e( 'Describe the issue you have', 'flexible-invoices' ); ?></li>
				<li><?php esc_html_e( 'Attach any log files & printscreens of the issue', 'flexible-invoices' ); ?></li>
				<li><?php _e( 'Read also <a target="_blank" href="https://wpde.sk/fi-support-tab-common">common issues in WordPress & WooCommerce</a>', 'flexible-invoices' ); ?></li>
			</ul>
			<div class="confirm-buttons">
				<a target="_blank" href="https://wpde.sk/fi-support-tab-contact" class="confirm-url"><?php esc_html_e( 'Ok, take me to support', 'flexible-invoices' ); ?></a>
				<a href="#" class="close-confirm close-modal"><?php esc_html_e( 'No, I\'ll wait', 'flexible-invoices' ); ?></a>
			</div>
		</div>
	</div>
</div>
