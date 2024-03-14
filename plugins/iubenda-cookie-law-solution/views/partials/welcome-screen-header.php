<?php
/**
 * Welcome screen header - global - partial page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div>
	<h1 class="text-xl mb-1 m-0"><?php esc_html_e( 'Welcome to iubenda!', 'iubenda' ); ?></h1>
	<p class="text-md m-0"><?php esc_html_e( 'Our plugin will help you to make your website compliant in minutes.', 'iubenda' ); ?></p>
</div>
