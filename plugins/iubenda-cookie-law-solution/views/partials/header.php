<?php
/**
 * Header - global - partial page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<header class="text-center">
	<a href="https://www.iubenda.com" target="_blank">
		<img src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/iubenda_logo.svg" alt="iubenda logo">
	</a>
</header>

<div id="iubenda-alert" class="p-2 hidden" style="width: 90%;max-width: 1140px;margin: 0 auto;" >
	<div class="alert alert--failure">
		<div class="alert__icon p-4" style="display: flex; align-items:center; justify-content: center;">
			<img src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/banner_failure.svg">
		</div>
		<p class="text-md text-lg-left" id="iubenda-alert-content"></p>
	</div>
</div>
