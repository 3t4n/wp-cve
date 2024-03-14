<?php

if (!defined('ABSPATH')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	die();
}

function wpt_env_ok($plugin_name, $plugin_textdomain, $plugin_file_path, $min_php_ver, $min_wp_ver) {
	$env = new WPT_JLWP_Env($plugin_name, $plugin_textdomain, $plugin_file_path, $min_php_ver, $min_wp_ver);
	return $env->ok();
}

class WPT_JLWP_Env {
	
	var $plugin_name;
	var $plugin_textdomain;
	var $min_php_ver;
	var $min_wp_ver;
	
	function WPT_JLWP_Env($plugin_name, $plugin_textdomain, $plugin_file_path, $min_php_ver, $min_wp_ver) {
		$this->plugin_name = $plugin_name;
		$this->plugin_textdomain = $plugin_textdomain;
		$this->plugin_file_path = $plugin_file_path;
		$this->min_php_ver = $min_php_ver;
		$this->min_wp_ver  = $min_wp_ver;
	}
	
	function ok() {
		
		$ok = true;
		
		if (version_compare(PHP_VERSION, $this->min_php_ver, '<')) {
			add_action('admin_notices', array(&$this, 'php_incompat_notice'));
			$ok = false;
		}
		
		global $wp_version;
		if (version_compare($wp_version, $this->min_wp_ver, '<')) {
			add_action('admin_notices', array(&$this, 'wp_incompat_notice'));
			$ok = false;
		}
		
		if (!$ok)
			add_action('init', array(&$this, 'load_textdomain'));
		
		return $ok;
	}
	
	function load_textdomain() {
		load_plugin_textdomain($this->plugin_textdomain, false, plugin_basename(dirname($this->plugin_file_path)));
	}
	
	function php_incompat_notice() {
		echo '<div class="error"><p>';
		printf(__('%1$s requires PHP %2$s or above. You&#8217;ll need to ask your webhost to upgrade your PHP installation before you can use %1$s. In the meantime, you can remove this notice by deactivating %1$s on the Plugins page.', $this->plugin_textdomain)
			, $this->plugin_name, $this->min_php_ver);
		echo "</p></div>\n";
	}
	
	function wp_incompat_notice() {
		echo '<div class="error"><p>';
		printf(__('%1$s requires WordPress %2$s or above. Please upgrade to the latest version of WordPress to enable %1$s on your blog, or deactivate %1$s on the Plugins page to remove this notice.', $this->plugin_textdomain)
			, $this->plugin_name, $this->min_wp_ver);
		echo "</p></div>\n";
	}
}