<?php
/**
 * Sync - global - partial modal page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="modalSync">
	<img src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/modals/modal_sync.svg" alt="" />
	<h1 class="text-lg mb-4">
		<?php esc_html_e( 'First of all, tell us if you already use our products for this website or if you want to start from scratch', 'iubenda' ); ?>
	</h1>
	<button class="btn btn-gray-lighter btn-block btn-sm mb-3 show-modal" data-modal-name="#modal-have-existing-products"><?php esc_html_e( 'I’ve already made the set up on iubenda.com', 'iubenda' ); ?></button>
	<div class="mb-3"><?php esc_html_e( 'or', 'iubenda' ); ?></div>
	<button class="btn btn-gray-lighter btn-block btn-sm mb-3 show-modal" data-modal-name="#modal-select-language"><?php esc_html_e( 'I want to start from scratch', 'iubenda' ); ?></button>
</div>
