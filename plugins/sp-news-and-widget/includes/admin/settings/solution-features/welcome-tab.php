<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package WP News and Scrolling Widgets
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div id="wpnw_welcome_tabs" class="wpnw-vtab-cnt wpnw_welcome_tabs wpnw-clearfix">
	<!-- Start - Welcome Box -->
	<div class="wpnw-sf-welcome-wrap" style="padding: 30px;border-radius: 10px;border: 1px solid #e5ecf6;">
		<div class="wpnw-sf-welcome-inr wpnw-sf-center">
			<div style="font-size: 24px; font-weight:700; margin-bottom: 15px;">Display customizable  <span class="wpnw-sf-blue">news layouts, vertical scrolling news widgets</span> in the most engaging and customized way</div>
			<h5 class="wpnw-sf-content" style="font-size: 20px; font-weight:700; margin-bottom: 15px;">Experience <span class="wpnw-sf-blue">7 Layouts</span>, <span class="wpnw-sf-blue">70+ stunning designs</span>. </h5>
			<h5 class="wpnw-sf-content" style="font-size: 18px; font-weight:700; margin-bottom: 15px;"><span class="wpnw-sf-blue">20,000+ </span>websites are using <span class="wpnw-sf-blue">News Builder</span>.</h5>
		</div>
		<div style="margin: 30px 0; text-transform: uppercase; text-align:center;">
			<a href="<?php echo esc_url( $wpnw_add_link ); ?>" class="wpnw-sf-btn">Launch News With Free Features</a>
		</div>
	</div>

</div>