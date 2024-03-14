<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://webmuehle.at
 * @since      1.1.0
 *
 * @package    Settings
 * @subpackage Courtres/admin/settigns
 */
?>

<?php
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die();
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php
require 'courtres-notice-upgrade.php';
?>



<div class="wrap">
	<p></p>
</div>
