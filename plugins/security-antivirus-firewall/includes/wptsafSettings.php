<?php
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;

class wptsafSettings{

	protected $extension;
	protected $optionKey;
	protected $defaultOptions;
	protected $options;

	public function __construct(wptsafAbstractExtension $extension){
		$this->extension = $extension;
		$this->read();
	}

	protected function read(){
		$this->options = get_site_option($this->optionKey);
		if (!is_array($this->options)) {
			$this->options = array();
		}
		$this->options = array_merge($this->defaultOptions, $this->options);
	}

	public function save(){
		$result = update_site_option($this->optionKey, $this->options);
		do_action('wptsaf_security_extension_settings_save', $this->extension);
		return $result;
	}

	public function delete(){
		return delete_site_option($this->optionKey);
	}

	public function set($path, $value){
		$pieces = explode('/', $path);
		$lastPiece = array_pop($pieces);
		$config = &$this->options;

		foreach ($pieces as $piece) {
			if (!isset($config[$piece]) || !is_array($config[$piece])) {
				$config[$piece] = array();
			}
			$config = &$config[$piece];
		}
		$config[$lastPiece] = $value;
	}

	public function get($path = null){
		$pieces = $path ? explode('/', $path) : array();
		$config = &$this->options;

		foreach ($pieces as $piece) {
			if (!isset($config[$piece])) {
				return null;
			}
			$config = &$config[$piece];
		}

		return $config;
	}
}
