<?php
/**
 * The main plugin file
 *
 * @package WordPress_Plugins
 * @subpackage Changelogger
 */
 
/*
Plugin Name: Changelogger
Version: 1.3.3
Plugin URI: https://www.schloebe.de/wordpress/changelogger-plugin/
Description: <strong>WordPress 2.7+ only.</strong> For many many people a changelog is a very important thing; it is all about justifying to your users why they should upgrade to the latest version of a plugin. Changelogger shows the latest changelog right on the plugin listing page, whenever there's a plugin ready to be updated.
Author: Oliver Schl&ouml;be
Author URI: https://www.schloebe.de/

Copyright 2010-2019 Oliver Schlöbe (email : scripts@schloebe.de)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
 * Define the plugin version
 */
define("CLOSVERSION", "1.3.3");

/**
 * Define the global var CLOSISWP27, returning bool if at least WP 2.7 is running
 */
define('CLOSISWP27', version_compare($GLOBALS['wp_version'], '2.6.999', '>'));

/**
 * Define the global var CLOSMINWP28, returning bool if at least WP 2.8 is running
 */
define('CLOSMINWP28', version_compare($GLOBALS['wp_version'], '2.7.999', '>'));

/**
 * Define the global var CLOSMINWP30, returning bool if at least WP 3.0 is running
 */
define('CLOSMINWP30', version_compare($GLOBALS['wp_version'], '2.9.999', '>'));


/** 
* The Changelogger class
*
* @package 		WordPress_Plugins
* @subpackage 	Changelogger
* @since 		1.0
* @author 		scripts@schloebe.de
*/
class Changelogger {
	
	
	/**
 	* The Changelogger class constructor
 	* initializing required stuff for the plugin
 	* 
	* PHP 5 Constructor
 	*
 	* @since 		1.0
 	* @author 		scripts@schloebe.de
 	*/		
	function __construct() {
		$this->textdomain_loaded = false;
		
		if ( !CLOSISWP27 ) {
			add_action('admin_notices', array(&$this, 'require_wpversion_message'));
			return;
		}
		
		add_action('admin_init', array(&$this, 'load_textdomain'));
		add_action('admin_init', array(&$this, 'init'));
		add_action('after_plugin_row', array(&$this, 'display_info_row'), 50, 2);
		add_action('wp_ajax_clos_ajax_load_changelog', array(&$this, 'clos_ajax_load_changelog'));
		if( CLOSMINWP28 ) {
			add_filter('pre_update_option_active_plugins', array(&$this, 'flush_changelog_cache'));
		}
	}
	
	/**
 	* The Changelogger class constructor
 	* initializing required stuff for the plugin
 	* 
	* PHP 4 Compatible Constructor
 	*
 	* @since 		1.0
 	* @author 		scripts@schloebe.de
 	*/
	function Changelogger() {
		$this->__construct();
	}
	
	
	/**
 	* Enqueue the scripts and styles
 	* 
 	* @since 		1.2.10
 	* @author 		scripts@schloebe.de
 	*/		
	function enqueue_scripts_and_styles() {
		wp_enqueue_script(
			'clos-generalscripts',
			$this->_plugins_url( 'js/admin_scripts.js', __FILE__ ),
			array('jquery', 'sack'),
			CLOSVERSION
		);
		wp_enqueue_style(
			'clos-generalstyles',
			$this->_plugins_url( 'css/style.css', __FILE__ ),
			array(),
			CLOSVERSION,
			'screen'
		);
	}
	
	
	/**
 	* Initialize and load the plugin stuff
 	*
 	* @since 		1.0
 	* @uses 		$pagenow
 	* @author 		scripts@schloebe.de
 	*/
	function init() {
		global $pagenow;
		if ( !function_exists("add_action") ) return;
		
		if( $pagenow == 'plugins.php' && !isset( $_GET['action'] ) ) {
			$this->enqueue_scripts_and_styles();
			add_action('admin_print_scripts', array(&$this, 'js_admin_header') );
		}
	}
	
	
	/**
 	* Add a plugin row to display changelog via WP plugin API
 	*
 	* @since 		1.0
 	* @param 		string $file
 	* @param 		array $plugin_data
 	* @author 		scripts@schloebe.de
 	*/
	function display_info_row( $file, $plugin_data ) {
		global $wp_version;
		
		if( is_plugin_active( 'wp-manage-plugins/wp-manage-plugins.php' ) ) {
			$plugins_ignored = get_option('plugin_update_ignore');
			if ( in_array( $file, array_keys($plugins_ignored) ) )
				return false;
		}
		
		$cur_wp_version = preg_replace('/-.*$/', '', $wp_version);
		if( CLOSMINWP30 )
			$current = get_site_transient( 'update_plugins' );
		elseif( CLOSMINWP28 )
			$current = get_transient( 'update_plugins' );
		else
			$current = get_option( 'update_plugins' );
			
		if (!isset($current->response[$file])) return false;
		$output = '';
		
		$r = $current->response[ $file ];
		include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
		$columns = CLOSMINWP28 ? 3 : 5;
		
		$cache_key = 'changelogger_plugin_changelog_' . $r->slug;
		$output = wp_cache_get($cache_key, 'changelogger');
		
		if (false === $output) {
			$api = plugins_api('plugin_information', array('slug' => $r->slug, 'fields' => array('tested' => true, 'requires' => false, 'rating' => false, 'downloaded' => false, 'downloadlink' => false, 'last_updated' => false, 'homepage' => false, 'tags' => false, 'sections' => true) ));
			if ( !is_wp_error( $api ) && current_user_can('update_plugins') ) {
				$is_active = is_plugin_active( $file );
				$class = $is_active ? 'active' : 'inactive';
				$class_tr = CLOSMINWP28 ? ' class="plugin-update-tr second ' . $class . '"' : '';
				if( isset($api->sections['changelog']) ) {
					echo '';
					$changelog = $api->sections['changelog'];
					$section_exists = preg_match_all('/(<h4>|<p><strong>|<strong>|[\n|\r]{0,})(.*)([\n|\r]{0,}|<\/strong>|<\/strong><\/p>|<\/h4>)[\n|\r]{0,}<ul>(.*)<\/ul>[\w|\W]{0,}/isU', $changelog, $changelog_result);
					if( $section_exists ) {
						$search = array("<strong>Version ", "<h4>Version ", "Version ", "<strong>v", "<h4>v");
						$replace = array("<strong>", "<h4>", "", "<strong>", "<h4>");
						$output .= '<tr' . $class_tr . '><td class="plugin-update clos-plugin-update colspanchange" colspan="' . $columns . '"><div class="update-message clos-message notice inline notice-info notice-alt" id="clos-message-' . $r->slug . '">';
						$changelog = trim( str_replace($search, $replace, $changelog_result[0][0]) );
						$l_arrw = '&laquo; ';
						$r_arrw = ' <a href="#" onclick="clos_ajax_load_changelog( \'' . $r->slug . '\', \'1\' );return false;" title="' . $this->_esc_attr__('Previous version') . '" class="clos-arrw clos-arrw-r">&raquo;</a>';
						$changelog = preg_replace( "#<h4>(.*)<\/h4>#i", $l_arrw . '\0' . $r_arrw, $changelog );
						$changelog = preg_replace( "#<p><strong>(.*)<\/strong><\/p>#i", $l_arrw . '\0' . $r_arrw, $changelog );
						#$changelog = preg_replace( "#[\n|\r]{0,}(.*)[\n|\r]{2,}#iU", $l_arrw . '<strong>\1</strong>' . $r_arrw, $changelog );
						$output .= sprintf(__('What has changed in version %1$s', 'changelogger'), $changelog);
						$output .= '<p>';
						if ( isset($api->tested) && version_compare($api->tested, $cur_wp_version, '>=') ) {
							$output .= '' . sprintf(__('Compatibility with WordPress %1$s: 100%% (according to its author).', 'changelogger'), $cur_wp_version);
						} elseif ( isset($api->compatibility[$cur_wp_version][$r->new_version]) ) {
							$compat = $api->compatibility[$cur_wp_version][$r->new_version];
							$output .= ' ' . sprintf(__('Compatibility with WordPress %1$s: %2$d%% (%3$d "works" votes out of %4$d total).', 'changelogger'), $cur_wp_version, $compat[0], $compat[2], $compat[1]);
						} else {
							$output .= ' ' . sprintf(__('Compatibility with WordPress %1$s: Unknown.', 'changelogger'), $cur_wp_version);
						}
						$output .= ' ' . sprintf(__('If you are interested, check out the plugin\'s <a href="http://plugins.trac.wordpress.org/log/%s/trunk" target="_blank">Revision Log</a>!', 'changelogger'), $r->slug) . '</div></td></tr>';
						$output .= '</p>';
					} else {
						#print_r($api);
						$output .= '<tr' . $class_tr . '><td class="plugin-update clos-plugin-update" colspan="' . $columns . '"><div class="update-message clos-message notice inline notice-warning notice-alt">';
						$output .= '<span style="color:#A36300;">' . sprintf(__('There is a changelog section for this plugin, but it is not readable, propably because it <strong>does not match the <a href="http://wordpress.org/extend/plugins/about/readme.txt" target="_blank">readme.txt standards</a></strong>!', 'changelogger')) . ' ' . sprintf(__('If you are interested, check out the plugin\'s <a href="http://plugins.trac.wordpress.org/log/%s/trunk" target="_blank">Revision Log</a>!', 'changelogger'), $r->slug) . '</span>';
						$output .= '</div></td></tr>';
					}
				} else {
					#print_r($api);
					$output .= '<tr' . $class_tr . '><td class="plugin-update colspanchange clos-plugin-update" colspan="' . $columns . '"><div class="update-message clos-message notice inline notice-warning notice-alt">';
					if( isset($api->author) ) {
						$output .= '<span style="color:#A36300;">' . sprintf(__('There is <strong>no changelog section provided for this plugin</strong>. Please encourage the plugin author to add a changelog section to the plugin\'s readme! Contact %s! [<a href="http://westi.wordpress.com/2009/06/20/changelogs-changelogs-changelogs/" target="_blank">More</a>]', 'changelogger'), $api->author) . ' ' . sprintf(__('If you are interested, check out the plugin\'s <a href="http://plugins.trac.wordpress.org/log/%s/trunk" target="_blank">Revision Log</a>!', 'changelogger'), $r->slug) . '</span>';
					} else {
						$output .= '<span style="color:#A36300;">' . __('There is <strong>no changelog section provided for this plugin</strong>. Please encourage the plugin author to add a changelog section to the plugin\'s readme! [<a href="http://westi.wordpress.com/2009/06/20/changelogs-changelogs-changelogs/" target="_blank">More</a>]', 'changelogger') . ' ' . sprintf(__('If you are interested, check out the plugin\'s <a href="http://plugins.trac.wordpress.org/log/%s/trunk" target="_blank">Revision Log</a>!', 'changelogger'), $r->slug) . '</span>';
					}
					$output .= '</div></td></tr>';
				}
			} else {
				$output .= '<tr class="plugin-update-tr"><td colspan="' . $columns . '" class="plugin-update colspanchange"><div class="update-message clos-message">';
				$output .= sprintf(__('<strong>ERROR</strong>: %s', 'changelogger'), $api->get_error_message());
				$output .= '</div></td></tr>';
			}
			wp_cache_set($cache_key, $output, 'changelogger', 60*60*3);
		}
		echo $output;
	}
	
	
	/**
 	* SACK response function for loading a changlog inline
 	*
 	* @since 		1.2
 	* @author 		scripts@schloebe.de
 	*/
	function clos_ajax_load_changelog() {
		$sectionid = absint( $_POST['sectionid'] );
		$pluginslug = $_POST['pluginslug'];
		
		header("Cache-Control: max-age=10800");
		$offset = 60 * 60 * 24 * 3;
		$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
		header($ExpStr);
		
		if( CLOSMINWP28 ) {
			$current_changelog = get_transient( 'clos_changelog_' . $pluginslug . '_' . $sectionid );
			if ( $current_changelog ) {
				$versioninfo = $current_changelog;
				die("// Cached output" . chr(012) . "jQuery('div#clos-message-" . $pluginslug . "').fadeOut('fast', function() { jQuery(this).html(\"" . str_replace( chr(012), "", addslashes_gpc( $versioninfo ) ) . "\").fadeIn('normal') });");
			}
		}
		
		include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
		$api = plugins_api('plugin_information', array('slug' => $pluginslug, 'fields' => array('tested' => false, 'requires' => false, 'rating' => false, 'downloaded' => false, 'downloadlink' => false, 'last_updated' => false, 'homepage' => false, 'tags' => false, 'sections' => true) ));
		if( isset($api->sections['changelog']) ) {
			$changelog = $api->sections['changelog'];
			$section_exists = preg_match_all('/(<h4>|<p><strong>|<strong>|[\n|\r]{0,})(.*)([\n|\r]{0,}|<\/strong>|<\/strong><\/p>|<\/h4>)[\n|\r]{0,}<ul>(.*)<\/ul>[\w|\W]{0,}/isU', $changelog, $changelog_result);
			if( $section_exists ) {
				$sectionidl = ($sectionid-1);
				if( trim($changelog_result[0][$sectionidl]) != '' || !empty($changelog_result[0][$sectionidl]) )
					$l_arrw = sprintf('<a href="#" onclick="clos_ajax_load_changelog( \'%1$s\', \'' . $sectionidl . '\' );return false;" class="clos-arrw clos-arrw-l" title="' . $this->_esc_attr__('Next version') . '">&laquo;</a> ', $pluginslug);
				else
					$l_arrw = '&laquo; ';
				
				$sectionidn = ($sectionid+1);
				if( trim($changelog_result[0][$sectionidn]) != '' || !empty($changelog_result[0][$sectionidn]) )
					$r_arrw = sprintf(' <a href="#" onclick="clos_ajax_load_changelog( \'%1$s\', \'' . $sectionidn . '\' );return false;" class="clos-arrw clos-arrw-r" title="' . $this->_esc_attr__('Previous version') . '">&raquo;</a>', $pluginslug);
				else
					$r_arrw = ' &raquo;';
				
				$search = array("<strong>Version ", "<h4>Version ", "Version ", "<strong>v", "<h4>v");
				$replace = array("<strong>", "<h4>", "", "<strong>", "<h4>");
				$str_changelog = trim( str_replace($search, $replace, $changelog_result[0][$sectionid]) );
				$str_changelog = preg_replace( "#<h4>(.*)<\/h4>#i", $l_arrw . '\0' . $r_arrw, $str_changelog );
				$str_changelog = preg_replace( "#<p><strong>(.*)<\/strong><\/p>#i", $l_arrw . '\0' . $r_arrw, $str_changelog );
				#$str_changelog = preg_replace( "#[\n|\r]{0,}(.*)[\n|\r]{2,}#iU", $l_arrw . '<strong>\1</strong>' . $r_arrw, $str_changelog );
				$versioninfo = sprintf(__('What has changed in version %1$s', 'changelogger'), $str_changelog);
				
				if( CLOSMINWP28 && !$current_changelog )
					set_transient('clos_changelog_' . $pluginslug . '_' . $sectionid, $versioninfo, 60*60*3);
				
				die("jQuery('div#clos-message-" . $pluginslug . "').fadeOut('fast', function() { jQuery(this).html(\"" . str_replace( chr(012), "", addslashes_gpc( $versioninfo ) ) . "\").fadeIn('normal') });");
			}
		}
	}
	
	
	/**
 	* Delete the cached plugin changelog data
 	*
 	* @since 		1.2.6
 	* @author 		scripts@schloebe.de
 	*/
	function flush_changelog_cache( $new_value ) {
		$plugins_updated = false;
		$old_value = (array)get_option('active_plugins');
		
		if ($new_value !== $old_value && in_array(plugin_basename( (__FILE__) ), (array)$new_value) && in_array(plugin_basename( (__FILE__) ), $old_value)) {
			$plugins_updated = true;
			if( count($old_value) < count((array)$new_value) )
				$diff_value = array_diff((array)$new_value, $old_value);
			else
				$diff_value = array_diff($old_value, (array)$new_value);
			
			$slug_value = array();
			foreach( $diff_value as $diff_plugin ) {
				$slug_value[] = dirname( $diff_plugin );
			}
		}
		
		if( $plugins_updated == true ) {
			foreach( $slug_value as $slug ) {
				for($count = 0; $count < 20; $count++) {
					delete_transient( 'clos_changelog_' . $slug . '_' . $count );
					delete_transient( 'timeout_clos_changelog_' . $slug . '_' . $count );
				}
			}
		}
		return $new_value;
	}
	
	
	/**
 	* Initialize and load the plugin textdomain
 	*
 	* @since 		1.0
 	* @author 		scripts@schloebe.de
 	*/
	function load_textdomain() {
		if($this->textdomain_loaded) return;
		load_plugin_textdomain('changelogger', false, dirname(plugin_basename(__FILE__)) . '/languages');
		$this->textdomain_loaded = true;
	}
	
	
	/**
 	* Fallback function for the esc_attr__ function
 	* introduced in 2.8
 	*
 	* @since 		1.2
 	* @author 		scripts@schloebe.de
 	*/
	function _esc_attr__( $str ) {
		if( CLOSMINWP28 )
			return esc_attr__( $str, 'changelogger' );
		else
			return attribute_escape( __( $str, 'changelogger' ) );
	}
	
	
	/**
 	* Fallback function for the esc_js function
 	* introduced in 2.8
 	*
 	* @since 		1.2
 	* @author 		scripts@schloebe.de
 	*/
	function _esc_js( $str ) {
		if( CLOSMINWP28 )
			return esc_js( $str );
		else
			return js_escape( $str );
	}
	
	
	
	/**
 	* Retrieve the url to the plugins directory or to a specific file within that directory.
 	* The original function has been changed in 2.8 but we need it for 2.7 as well
 	* so we can pass a second argument
 	*
 	* @since 		1.2
 	* @author 		scripts@schloebe.de
 	*/
	function _plugins_url($path = '', $plugin = '') {
		if( CLOSMINWP28 ) {
			return plugins_url($path, $plugin);
		} else {
			$scheme = ( is_ssl() ? 'https' : 'http' );

			if ( $plugin !== '' && preg_match('#^' . preg_quote(WPMU_PLUGIN_DIR . DIRECTORY_SEPARATOR, '#') . '#', $plugin) ) {
				$url = WPMU_PLUGIN_URL;
			} else {
				$url = WP_PLUGIN_URL;
			}

			if ( 0 === strpos($url, 'http') ) {
				if ( is_ssl() )
					$url = str_replace( 'http://', "{$scheme}://", $url );
			}

			if ( !empty($plugin) && is_string($plugin) ) {
				$folder = dirname(plugin_basename($plugin));
				if ('.' != $folder)
					$url .= '/' . ltrim($folder, '/');
			}

			if ( !empty($path) && is_string($path) && strpos($path, '..') === false )
				$url .= '/' . ltrim($path, '/');
	
			return apply_filters('plugins_url', $url, $path, $plugin);
		}
	}



	/**
	 * Writes javascript stuff into plugin page header
	 *
	 * @since 		1.2
	 * @author 		scripts@schloebe.de
	 */
	function js_admin_header() {
		wp_print_scripts( array( 'sack' ));
		if( version_compare($GLOBALS['wp_version'], '2.7.999', '>') ) {
	?>
<script type="text/javascript">
var clos_ajaxurl = ajaxurl;
</script>
	<?php
		} else {
	?>
<script type="text/javascript">
var clos_ajaxurl = "<?php echo $this->_esc_js( get_bloginfo( 'wpurl' ) . '/wp-admin/admin-ajax.php' ); ?>";
</script>
	<?php
		}
	}
	
	
	
	/**
 	* Checks for the version of WordPress,
 	* and adds a message to inform the user
 	* if required WP version is less than 2.7
 	*
 	* @since 		1.0
 	* @author 		scripts@schloebe.de
 	*/
	function require_wpversion_message() {
		echo "<div id='wpversionfailedmessage' class='error fade'><p>" . __('Changelogger requires at least WordPress 2.7!', 'changelogger') . "</p></div>";
	}
	
}

if ( class_exists('Changelogger') && is_admin() ) {
	$Changelogger = new Changelogger();
}
?>