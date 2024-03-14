<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       elementorplus.net
 * @since      1.0.0
 *
 * @package    Kitpack_Lite
 * @subpackage Kitpack_Lite/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Kitpack_Lite
 * @subpackage Kitpack_Lite/includes
 * @author     elementorplus <plugin@elementorplus.net>
 */
class Kitpack_Lite_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'kitpack-lite',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

	public function load_custom_textdomain(){
		//translate languge default plugins
		$domains = [
			"elementor" => "elementor-translate-farsi",
			"elementor-pro" => "elementor-pro-translate-farsi",
		];
		
 		//translate languge custom plugins
		if ( is_plugin_active('elementskit-lite/elementskit-lite.php') ) {
			$domains['elementskit-lite'] = 'elementskit-lite-translate-farsi' ;
		} 

		foreach($domains as $key => $value){
			$kpe_elementor_lang = KITPACK_PATH . "/languages/$key/$key-fa_IR.mo";
			
			if ( Kitpack_Lite_Admin::kpe_get_option($value)) {
				if (get_locale() == 'fa_IR' ) {
					//echo "$key=>$value</br>";
					unload_textdomain($key);
					load_textdomain($key, $kpe_elementor_lang );
				}
			}
		}
	}


}
