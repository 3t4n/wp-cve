<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.madebytribe.com
 * @since      1.0.0
 *
 * @package    Caddy
 * @subpackage Caddy/public/partials
 */
?>

<?php do_action( 'caddy_display_action_notice' ); ?>

<?php do_action( 'caddy_compass_icon' ); ?>

<!-- The expanded modal -->
<div class="cc-window disable-scrollbars">
	<div class="cc-window-wrapper">
		<?php Caddy_Public::cc_window_screen(); ?>
	</div>
</div>
<div class="cc-overlay"></div>