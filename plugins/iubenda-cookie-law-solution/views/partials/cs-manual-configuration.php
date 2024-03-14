<?php
/**
 * Manual configuration - cs - partial page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="text-gray">
	<p><?php esc_html_e( 'Configure your cookie banner on our website and paste here the embed code to integrate it to your website.', 'iubenda' ); ?></p>
	<div class="d-flex align-items-center">
		<div class="steps flex-shrink mr-2">1</div>
		<p class="text-bold"> <?php esc_html_e( 'Configure cookie banner by', 'iubenda' ); ?>
			<a target="_blank" href="<?php echo esc_url( iubenda()->settings->links['flow_page'] ); ?>" class="link-underline text-gray-lighter"> <?php esc_html_e( 'clicking here', 'iubenda' ); ?></a>
		</p>
	</div>
	<div class="d-flex align-items-center">
		<div class="steps flex-shrink mr-2">2</div>
		<p class="text-bold"> <?php esc_html_e( 'Paste your privacy controls and cookie solution embed code here', 'iubenda' ); ?>
		</p>
	</div>
	<div class="pl-5 mt-3">
		<?php
		// Including partial languages-tabs.
		require_once IUBENDA_PLUGIN_PATH . 'views/partials/languages-tabs.php';
		?>
	</div>
</div>
