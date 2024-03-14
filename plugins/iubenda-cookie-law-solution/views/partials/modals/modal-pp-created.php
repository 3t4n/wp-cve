<?php
/**
 * Privacy policy created - pp - partial modal page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="modalSync">
	<img src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/modals/modal_pp_created.svg" alt="" />

	<h1 class="text-xl">
		<?php esc_html_e( 'Your privacy policy has been created!', 'iubenda' ); ?>
	</h1>
	<p class="mb-4"><?php esc_html_e( 'From here you can customize your privacy policy by adding the services you use within your website or you can customize the style of the button that displays your privacy policy.', 'iubenda' ); ?></p>

	<a class="btn-green-primary btn-block btn-sm" href="<?php echo esc_url( add_query_arg( array( 'view' => 'integrate-setup' ), iubenda()->base_url ) ); ?>"><?php esc_html_e( 'Got it', 'iubenda' ); ?></a>

</div>
