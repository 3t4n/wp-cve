<?php
/**
 * Integrate footer - global - partial page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<hr>
<div class="px-3 m-4 d-flex align-items-center justify-content-between">
	<button type='reset' class="btn btn-gray-lighter btn-sm"><?php esc_html_e( 'Reset settings', 'iubenda' ); ?></button>
	<button type="submit" class="btn btn-green-primary btn-sm"><span class="button__text"><?php esc_html_e( 'Integrate', 'iubenda' ); ?></span></button>
</div>
