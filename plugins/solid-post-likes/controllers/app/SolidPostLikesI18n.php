<?php
namespace OACS\SolidPostLikes\Controllers\App;

if ( ! defined( 'WPINC' ) ) { die; }
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
*/

class SolidPostLikesI18n
{
	public function oacs_spl_load_plugin_textdomain()
	{
		load_plugin_textdomain(
			'oaspl',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages'
		);
	}

}
