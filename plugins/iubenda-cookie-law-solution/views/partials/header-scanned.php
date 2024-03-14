<?php
/**
 * Header scanned - global - partial page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="my-5">
	<div class="circularBar" id="iubendaRadarCircularBar" data-perc="<?php echo esc_html( iubenda()->service_rating->services_percentage() ); ?>"></div>
	<h2 class="text-md mt-2 mb-1"><?php esc_html_e( 'Your rating', 'iubenda' ); ?></h2>
	<p class="m-0"><?php esc_html_e( 'We have analyzed your website in background and this is the result.', 'iubenda' ); ?></p>
</div>
