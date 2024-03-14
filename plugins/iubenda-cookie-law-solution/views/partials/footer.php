<?php
/**
 * Footer - global - partial page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<footer class="py-4">
	<div class="d-flex justify-content-center">
		<div class="text-center">
			<p>All-in-one Compliance for GDPR / CCPA Cookie Consent + more | v<?php echo esc_html( get_option( 'iubenda_cookie_law_version' ) ); ?></p>
			<a target="_blank" class="text-bold text-xs text-gray mr-4" href="<?php echo esc_url( iubenda()->settings->links['documentation'] ); ?>"><?php esc_html_e( 'Documentation', 'iubenda' ); ?></a>
		</div>
	</div>
</footer>
