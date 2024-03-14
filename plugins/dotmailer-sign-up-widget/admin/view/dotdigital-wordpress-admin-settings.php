<?php

namespace Dotdigital_WordPress_Vendor;

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @package    Dotdigital_WordPress
 */
?>
	<?php 
do_action(\DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '_settings_head');
?>
	<?php 
settings_errors(\DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '_settings_notices');
?>
	<div class="dotdigital-settings wrap">

		<div id="icon-dotdigital" class="icon32"></div>
		<h2 style="padding:9px 15px 4px 0;">Dotdigital for WordPress</h2>

		<?php 
do_action(\DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '_settings_tab_links');
?>
		<?php 
do_action(\DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '_settings_tabs');
?>

	</div>
<?php 
