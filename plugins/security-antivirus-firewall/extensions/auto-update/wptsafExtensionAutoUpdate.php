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

class wptsafExtensionAutoUpdate extends wptsafAbstractExtension{

	protected static $instance;

	public function __construct(){
		$this->name = 'auto-update';
		$this->title = __('Auto Update', 'wptsaf_security');
		$this->description = __("Keep your site up to date to stay safe and secured. Auto update module provide you easy tool to enable updates of your wordpress update core files, plugins and themes installed on your website. All scripts, even wordpress core files, third-party plugins and themes may potentially be vulnerable to different type of attacks. Making sure you always have the newest versions of Wordpress, all plugins and themes installed on your website minimizes the risk to be hacked. Keep everything up to date to protect your website and your information. ", 'wptsaf_security');
		
		parent::__construct();
	}

	public static function getInstance(){
		if (!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function init(){
		parent::init();
		
		$settings = $this->getSettings();

		if ($settings->get('is_update_core')) {
			add_filter('auto_update_core', '__return_true');
		} else {
			add_filter('auto_update_core', '__return_false');
		}

		if ($settings->get('is_update_plugins')) {
			add_filter('auto_update_plugin', '__return_true');
		} else {
			add_filter('auto_update_plugin', '__return_false');
		}

		if ($settings->get('is_update_themes')) {
			add_filter('auto_update_theme', '__return_true');
		} else {
			add_filter('auto_update_theme', '__return_false');
		}
	}

	public function isEnabled(){
		return true;
	}
}
