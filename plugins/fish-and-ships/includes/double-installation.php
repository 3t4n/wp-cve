<?php
/**
 * Double installation warning. Only will occur if there is 2 copies of the plugin activated at once (free+pro)?
 *
 * @package Fish and Ships
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;


if ( !function_exists('woocommerce_fish_n_ships_duble_install') ) {
	function woocommerce_fish_n_ships_duble_install() {
		echo '<div class="error">
		<p><strong>Fish and Ships Plugin</strong>: There is two versions of the plugin actived at once. Please, go to the <a href="' . admin_url('plugins.php'). '">plugins page</a> and deactivate one of them.</p>
		<p><strong>Note:</strong> Maybe you just installed the Pro version? In this case, deactivate the free version to get the Pro features. Don\'t worry about, your previous shipping methods will continue working with it.</p>
		</div>';
	}
	add_action('admin_notices', 'woocommerce_fish_n_ships_duble_install');
}