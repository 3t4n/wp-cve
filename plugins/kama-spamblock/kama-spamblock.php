<?php
/**
 * Plugin Name: Kama SpamBlock
 *
 * Description: Block spam when comment is posted by a robot. Check pings/trackbacks for real backlink.
 *
 * Text Domain: kama-spamblock
 * Domain Path: /languages
 *
 * Author:     Kama
 * Author URI: https://wp-kama.ru
 * Plugin URI: https://wp-kama.ru/95
 *
 * Requires PHP: 5.6
 * Requires at least: 2.7
 *
 * Version: 1.8.2
 */

require_once __DIR__ . '/Kama_Spamblock.php';
require_once __DIR__ . '/Kama_Spamblock_Options.php';

add_action( 'init', 'kama_spamblock_init', 11 );


function kama_spamblock_init() {
	return kama_spamblock()->init_plugin();
}

/**
 * @return Kama_Spamblock
 */
function kama_spamblock() {
	static $inst;

	$inst || $inst = new Kama_Spamblock( __FILE__ );

	return $inst;
}
