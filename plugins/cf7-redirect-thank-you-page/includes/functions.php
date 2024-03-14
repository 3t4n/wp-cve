<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// display activation notice
function cf7rl_my_plugin_admin_notices() {
	if (!get_option('cf7rl_my_plugin_notice_shown')) {
		echo "<div class='updated'><p><a href='admin.php?page=cf7rl_admin_table'>Click here to view the plugin settings</a>.</p></div>";
		update_option("cf7rl_my_plugin_notice_shown", "true");
	}
}
add_action('admin_notices', 'cf7rl_my_plugin_admin_notices');