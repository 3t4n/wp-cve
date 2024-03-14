<?php
/**
 * Plugin Name: Merge + Minify + Refresh
 * Plugin URI: https://wordpress.org/plugins/merge-minify-refresh
 * Description: Merge/Concatenate & Minify CSS & JS.
 * Version: 2.7
 * Author: Launch Interactive
 * Author URI: http://launchinteractive.com.au
 * Requires PHP: 7.0
 * License: GPL2

Copyright 2023  Marc Castles  (email : marc@launchinteractive.com.au)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require __DIR__ . '/HandlesList.php';

use MergeMinifyRefresh\HandlesList;

class MergeMinifyRefresh
{
	private const VERSION = '2.6';

	private $host = '';
	private $root = '';
	private $refreshed = false;

	private $mergecss = true;
	private $checkcssimports = true;
	private $mergejs = true;
	private $cssmin = true;
	private $jsmin = true;
	private $http2pushCSS = false;
	private $http2pushJS = false;
	private $outputbuffering = false;
	private $buffering = false;
	private $gzip = false;
	private $ignore = array();

	private $wordpressdir = '';

	private $scriptcount = 0;

	private $hasMerged = false;

	private $rootRelativeWPContentDir = '';

	/**
	 * The maximum integer value.
	 * Used when enqueuing scripts and init action to ensure MMR runs after everything else.
	 *
	 * @var int
	 */
	private $max = 0;

	public function __construct()
	{
		//turn on output buffering as early as possible if required
		$this->outputbuffering = get_option('mmr-outputbuffering');
		if(!is_admin() && $this->outputbuffering)
		{
			$this->buffering = ob_start();
		}

		$this->max = defined('PHP_INT_MAX') ? PHP_INT_MAX : 9223372036854775807;

		/*
		Init MMR after all other inits.
		WordPress loads plugins in alphabetical order so we do this to ensure that should_mmr filter will run after everything is ready.
		*/
		add_action('init', [$this, 'init'], $this->max);
	}

	function init()
	{
		/*
		Valid Configs:

		MMR_CACHE_DIR + MMR_CACHE_URL
		MMR_CACHE_DIR + MMR_JS_CACHE_URL + MMR_CSS_CACHE_URL
		MMR_CACHE_DIR + MMR_CACHE_URL + MMR_JS_CACHE_URL + MMR_CSS_CACHE_URL // MMR_CACHE_URL becomes unnecessary
		MMR_CACHE_DIR + MMR_CACHE_URL + MMR_JS_CACHE_URL
		MMR_CACHE_DIR + MMR_CACHE_URL + MMR_CSS_CACHE_URL
		MMR_CACHE_URL
		MMR_JS_CACHE_URL + MMR_CSS_CACHE_URL
		MMR_CACHE_URL + MMR_JS_CACHE_URL + MMR_CSS_CACHE_URL // MMR_CACHE_URL becomes unnecessary
		MMR_CACHE_URL + MMR_JS_CACHE_URL
		MMR_CACHE_URL + MMR_CSS_CACHE_URL
		MMR_CSS_CACHE_URL
		MMR_JS_CACHE_URL
		*/

		if(!defined('MMR_CACHE_DIR'))
		{
			define('MMR_CACHE_DIR', WP_CONTENT_DIR . '/mmr' . (is_multisite() ? "-" . get_current_blog_id() : ""));

			if(!defined('MMR_CACHE_URL'))
			{
				define('MMR_CACHE_URL', WP_CONTENT_URL . '/mmr' . (is_multisite() ? "-" . get_current_blog_id() : ""));
			}
		}
		else if(WP_DEBUG && !defined('MMR_CACHE_URL') && (!defined('MMR_JS_CACHE_URL') || !defined('MMR_CSS_CACHE_URL')))
		{
			wp_die("You must specify MMR_CACHE_URL or MMR_JS_CACHE_URL & MMR_CSS_CACHE_URL");
		}

		if(!defined('MMR_JS_CACHE_URL'))
		{
			define('MMR_JS_CACHE_URL', MMR_CACHE_URL);
		}
		if(!defined('MMR_CSS_CACHE_URL'))
		{
			define('MMR_CSS_CACHE_URL', MMR_CACHE_URL);
		}

		if(!is_dir(MMR_CACHE_DIR))
		{
			mkdir(MMR_CACHE_DIR);
		}

		/* Calculate Root Relative path to WP Content */
		if(defined('WP_CONTENT_URL'))
		{
			$this->rootRelativeWPContentDir = parse_url(WP_CONTENT_URL,PHP_URL_PATH);
		}
		else
		{
			$this->rootRelativeWPContentDir = str_replace($_SERVER['DOCUMENT_ROOT'],'', WP_CONTENT_DIR);
		}

		$this->root = $_SERVER["DOCUMENT_ROOT"];
		$this->wordpressdir = rtrim(parse_url(network_site_url(), PHP_URL_PATH) ?: "",'/');

		add_action('mmr_minify', array($this, 'minify_action'), 10, 1);
		add_action('mmr_minify_check', array($this, 'minify_action'), 10, 1);

		add_action('compress_css', array($this, 'minify_action'), 10, 1); // Depreciated
		add_action('compress_js', array($this, 'minify_action'), 10, 1); // Depreciated

		//https://wordpress.org/support/topic/upgrade-fix-disable-mmr-in-customize-view-purge-on-customize-update/
		add_action('customize_save_after', array($this, 'purgeAll'));

		if(is_admin())
		{
			if(current_user_can('administrator'))
			{
				add_action( 'admin_menu', array($this, 'admin_menu') );
				add_action( 'admin_enqueue_scripts', array($this, 'load_admin_jscss') );
				add_action( 'wp_ajax_mmr_files', array($this, 'mmr_files_callback') );
				add_action( 'admin_init', array($this, 'mmr_register_settings') );
				register_deactivation_hook( __FILE__, array($this, 'plugin_deactivate') );

				if(!wp_next_scheduled('mmr_minify_check'))
				{
					wp_schedule_event(time(), 'hourly', 'mmr_minify_check');
				}

				add_action('in_plugin_update_message-merge-minify-refresh/merge-minify-refresh.php', array($this, 'showUpgradeNotification'), 10, 2);
			}
		}
		else if(apply_filters('should_mmr', !is_customize_preview()))
		{
			//https://wordpress.org/support/topic/php-notice-with-wp-5-1-1/#post-11494275
			if(array_key_exists('HTTP_HOST', $_SERVER))
			{
				$this->host = $_SERVER['HTTP_HOST'];
				//php < 5.4.7 returns null if host without scheme entered
				if(mb_substr($this->host, 0, 4) !== 'http')
				{
					$this->host = 'http' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 's' : '') . '://' . $this->host;
				}
				$this->host = parse_url($this->host, PHP_URL_HOST);
			}

			$this->mergecss = !get_option('mmr-nomergecss');
			$this->checkcssimports = !get_option('mmr-nocheckcssimports');
			$this->mergejs = !get_option('mmr-nomergejs');
			$this->cssmin = !get_option('mmr-nocssmin');
			$this->jsmin = !get_option('mmr-nojsmin');

			/* Depreciated mmr-http2push - remove June 2020 */
			if(get_option('mmr-http2push') && get_option('mmr-http2push-css', 'undefined') == 'undefined' && get_option('mmr-http2push-js', 'undefined')  == 'undefined')
			{
				add_option('mmr-http2push-css', true);
				add_option('mmr-http2push-js', true);
			}
			delete_option('mmr-http2push');
			/* End Depreciated mmr-http2push - remove June 2020 */

			$this->http2pushCSS = get_option('mmr-http2push-css');
			$this->http2pushJS = get_option('mmr-http2push-js');

			$this->gzip = get_option('mmr-gzip');
			$this->ignore = array_map('trim',explode(PHP_EOL,get_option('mmr-ignore')));

			add_action( 'wp_print_scripts', array($this,'inspect_scripts'), $this->max);
			add_action( 'wp_print_styles', array($this,'inspect_styles'), $this->max);

			add_filter( 'style_loader_src', array($this,'remove_cssjs_ver'), 10, 2);
			add_filter( 'script_loader_src', array($this,'remove_cssjs_ver'), 10, 2);

			add_action( 'wp_print_footer_scripts', array($this,'inspect_stylescripts_footer'), 9); //10 = Internal WordPress Output

			add_action('shutdown', array($this, 'refreshed'), 10);

			//Disable inlining small CSS files
			add_filter("styles_inline_size_limit", function(){return 0;});
		}
		else if($this->buffering) //stop output buffering if we started but didn't need to
		{
			$this->buffering = false;
			ob_end_flush();
		}

		//Allow triggering minify action manually
		if(WP_DEBUG && isset($_GET["mmr_do_minify"]))
		{
			$this->minify_action();
			exit;
		}
	}

	public function purgeAll()
	{
		wp_clear_scheduled_hook('mmr_minify');
		if (is_dir(MMR_CACHE_DIR))
		{
			$this->rrmdir(MMR_CACHE_DIR);
		}
	}

	public function mmr_files_callback()
	{
		if(isset($_POST['purge']) && $_POST['purge'] == 'all')
		{
			$this->purgeAll();
		}
		else if(isset($_POST['purge']))
		{
			array_map('unlink', glob(MMR_CACHE_DIR . '/' . basename($_POST['purge']) . '*'));
		}

		$return = array('js'=>array(),'css'=>array(),'stamp'=>$_POST['stamp']);

		$files = glob(MMR_CACHE_DIR . '/*.log', GLOB_BRACE);

		if(count($files) > 0)
		{
			foreach($files as $file)
			{
				$script_path = substr($file, 0, -4);

				$ext = pathinfo($script_path, PATHINFO_EXTENSION);

				$log = file_get_contents($file);

				$error = false;
				if(strpos($log,'COMPRESSION FAILED') !== false)
				{
					$error = true;
				}

				$filename = basename($script_path);

				switch($ext)
				{
					case 'css':
						$minpath = substr($script_path,0,-4) . '.min.css';
					break;
					case 'js':
						$minpath = substr($script_path,0,-3) . '.min.js';
					break;
				}

				if(file_exists($minpath))
				{
					$filename = basename($minpath);
				}

				$hash = substr($filename,0,strpos($filename,'-'));
				$accessed = 'Unknown';
				if( file_exists($script_path . '.accessed'))
				{
					$accessed = file_get_contents($script_path . '.accessed');
					if(strtotime('today') <= $accessed)
					{
						$accessed = 'Today';
					}
					else if(strtotime('yesterday') <= $accessed)
					{
						$accessed = 'Yesterday';
					}
					else if(strtotime('this week') <= $accessed)
					{
						$accessed = 'This Week';
					}
					else if(strtotime('this month') <= $accessed)
					{
						$accessed = 'This Month';
					}
					else
					{
						$accessed = date(get_option('date_format'), $accessed);
					}
				}
				$return[$ext][] = [
					'hash' => $hash,
					'filename'=> $filename,
					'log' => $log,
					'error' => $error,
					'accessed' => $accessed
				];
			}
		}

		header('Content-Type: application/json');
		echo json_encode($return);

		wp_die(); // this is required to terminate immediately and return a proper response
	}

	public function plugin_deactivate()
	{
		if(is_multisite() && is_main_site())
		{
			$sites = get_sites();
			foreach($sites as $site)
			{
				switch_to_blog($site->blog_id);
				wp_clear_scheduled_hook('mmr_minify');
				wp_clear_scheduled_hook('mmr_minify_check');
				restore_current_blog();

				// Build the cache directory for this site (replaces the number at the end of the dir)
				$site_cache_dir = preg_replace('/-[0-9]*$/', '-' . $site->blog_id, MMR_CACHE_DIR);
				$this->rrmdir($site_cache_dir);
			}

			return;
		}

		wp_clear_scheduled_hook('mmr_minify');
		wp_clear_scheduled_hook('mmr_minify_check');
		if(is_dir(MMR_CACHE_DIR))
		{
			$this->rrmdir(MMR_CACHE_DIR);
		}
	}

	private function rrmdir($dir)
	{
		foreach(glob($dir . '/{,.}*', GLOB_BRACE) as $file)
		{
			if(basename($file) != '.' && basename($file) != '..')
			{
				if(is_dir($file)) $this->rrmdir($file); else unlink($file);
			}
		}
		rmdir($dir);
	}

	public function load_admin_jscss($hook)
	{
		if('settings_page_merge-minify-refresh' != $hook)
		{
			return;
		}
		$pluginDir = plugin_dir_path(__FILE__);
		wp_enqueue_style( 'merge-minify-refresh', plugins_url('admin.css', __FILE__), array(), filemtime($pluginDir . 'admin.css'));
		wp_enqueue_script( 'merge-minify-refresh', plugins_url('admin.js', __FILE__), array(), filemtime($pluginDir . 'admin.js'), true );
	}

	public function admin_menu()
	{
		add_options_page('Merge + Minify + Refresh Settings', 'Merge + Minify + Refresh', 'manage_options', 'merge-minify-refresh', array($this,'merge_minify_refresh_settings'));
	}

	public function mmr_register_settings()
	{
		register_setting('mmr-group', 'mmr-nomergecss');
		register_setting('mmr-group', 'mmr-nocheckcssimports');
		register_setting('mmr-group', 'mmr-nomergejs');
		register_setting('mmr-group', 'mmr-nocssmin');
		register_setting('mmr-group', 'mmr-nojsmin');
		register_setting('mmr-group', 'mmr-http2push-css');
		register_setting('mmr-group', 'mmr-http2push-js');
		register_setting('mmr-group', 'mmr-outputbuffering');
		register_setting('mmr-group', 'mmr-gzip');
		register_setting('mmr-group', 'mmr-global-styles');
		register_setting('mmr-group', 'mmr-merge-inline');
		register_setting('mmr-group', 'mmr-ignore');
	}

	public function merge_minify_refresh_settings()
	{
		if(!current_user_can('manage_options'))
		{
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

		$files = glob(MMR_CACHE_DIR . '/*.{js,css}', GLOB_BRACE);

		echo '<div id="merge-minify-refresh">
				<h2>Merge + Minify + Refresh Settings</h2>
				<p>When a CSS or JS file is modified MMR will automatically re-process the files. However, when a dependancy changes these files may become stale.</p>

				<div id="mmr_processed">
					<a href="#" class="button button-secondary purgeall">Purge All</a>

					<div id="mmr_jsprocessed">
						<h4>The following Javascript files have been processed:</h4>
						<ul class="processed"></ul>
					</div>

					<div id="mmr_cssprocessed">
						<h4>The following CSS files have been processed:</h4>
						<ul class="processed"></ul>
					</div>
				</div>

				<p id="mmr_noprocessed"><strong>No files have been processed</strong></p>

			</div>
		';

		echo '<form method="post" id="mmr_options" action="options.php">';
		settings_fields('mmr-group');
		do_settings_sections('mmr-group');
		echo '<p><label><input type="checkbox" name="mmr-nomergecss" value="1" ' . checked(1 == get_option('mmr-nomergecss') , true, false) . '/> Don\'t Merge CSS</label>';
		echo '<label><input type="checkbox" name="mmr-nomergejs" value="1" ' . checked(1 == get_option('mmr-nomergejs'), true, false) . '/> Don\'t Merge JS</label>';
		echo '<br/><em>Note: Selecting these will increase requests but may be required for some themes. e.g. Themes using @import</em></p>';

		echo '<p><label><input type="checkbox" name="mmr-nocssmin" value="1" ' . checked(1 == get_option('mmr-nocssmin'), true, false) . '/> Disable CSS Minification</label>';

		echo '<label><input type="checkbox" name="mmr-nojsmin" value="1" ' . checked(1 == get_option('mmr-nojsmin'), true, false) . '/> Disable JS Minification</label>';
		echo '<br/><em>Note: Disabling CSS/JS minification may require a "Purge All" to take effect.</em></p>';

		echo '<p><label><input type="checkbox" name="mmr-nocheckcssimports" value="1" ' . checked(1 == get_option('mmr-nocheckcssimports'), true, false) . '/> Skip checking for @import in CSS.</label>';
		echo '<br/><em>Check this if you are sure your CSS doesn\'t have any @import statements. Merging will be faster.</em></p>';

		echo '<p><label><input type="checkbox" name="mmr-http2push-css" value="1" ' . checked(1 == get_option('mmr-http2push-css'), true, false) . '/> Enable Preload/Push Headers for CSS</label>';
		echo '<br/>';

		echo '<label><input type="checkbox" name="mmr-http2push-js" value="1" ' . checked(1 == get_option('mmr-http2push-js'), true, false) . '/> Enable Preload/Push Headers for Javascript</label>';
		echo '<br/><em>Add response headers for CSS or JS to allow browsers to start downloading assets before parsing the DOM.</em></p>';

		echo '<p><label><input type="checkbox" name="mmr-outputbuffering" value="1" ' . checked(1 == get_option('mmr-outputbuffering'), true, false) . '/> Enable Output Buffering</label>';
		echo '<br/><em>Output buffering may be required for compatibility with some plugins.</em></p>';

		echo '<p><label><input type="checkbox" name="mmr-gzip" value="1" ' . checked(1 == get_option('mmr-gzip'), true, false) . '/> Enable Gzip Encoding</label>';
		echo '<br/><em>Checking this option will generate additional .css.gz and .js.gz files. Your webserver may need to be configured to use these files.</em></p>';

		echo '<p><label><input type="checkbox" name="mmr-global-styles" value="1" ' . checked(1 == get_option('mmr-global-styles'), true, false) . '/> Disable merge of WordPress Global Styles</label>';
		echo '<br/><em>By default MMR writes the WordPress global inline styles to a file so it can be cached. Disabling this will inline the styles in the page.</em></p>';

		echo '<p><label><input type="checkbox" name="mmr-merge-inline" value="1" ' . checked(1 == get_option('mmr-merge-inline'), true, false) . '/> Merge Inline Style &amp; Scripts</label>';
		echo '<br/><em>By default MMR will split merged files when an inline script is detected. Enabling this will reduce the amount of merged files but may break some plugins and themes.</em></p>';

		echo '<p><label class="textlabel">Ignore these files (one per line):<textarea name="mmr-ignore" placeholder="file paths (view logs to get paths)">' . get_option('mmr-ignore') . '</textarea></label></p>';

		echo '<p><button type="submit" class="button">SAVE</button></p></form>';

		echo '<a href="#" id="mmr_advanced_toggle">Advanced Options</a>';
		echo '<div id="mmr_advanced">';
			echo '<p>The behaviour of MMR can be customised by adding the following constants to wp-config.php:<p>';
			echo '<table>';
			echo '<tr><th>Constant:</th><th>Example</th><th>Description</th></tr>';

			$options = [
				'MMR_USE_CLOSURE' => [
					'example' => 'define(\'MMR_USE_CLOSURE\', false);',
					'description' => 'Disable Google Closure Javascript minification.'
				],
				'MMR_REMOVE_EXPIRED' => [
					'example' => 'define(\'MMR_REMOVE_EXPIRED\', false);',
					'description' => 'Disable removing expired files. This can be useful when page HTML is cached and referencing an old asset.'
				],
				'MMR_CACHE_DIR' => [
					'example' => 'define(\'MMR_CACHE_DIR\', \'/var/www/html/cache\');',
					'description' => 'Override path to store minified files. Must be full server path.'
				],
				'MMR_JS_CACHE_URL' => [
					'example' => 'define(\'MMR_JS_CACHE_URL\', \'https://www.website.com/cache/js\');',
					'description' => 'Override URL for minified Javascript files. Must be an absolute URL for this to work correctly.'
				],
				'MMR_CSS_CACHE_URL' => [
					'example' => 'define(\'MMR_CSS_CACHE_URL\', \'https://www.website.com/cache/js\');',
					'description' => 'Override URL for minified Stylesheets. Must be an absolute URL for this to work correctly.'
				]
			];

			foreach($options as $key => $option)
			{
				echo '<tr><td>' . $key . '</td><td>' . $option['example'] . '</td><td>' . $option['description'] . '</td></tr>';
			}
			echo '</table>';
		echo '</div>';
	}

	public function remove_cssjs_ver($src)
	{
		if($src && strpos($src,'?ver=') !== false)
		{
			$src = remove_query_arg('ver', $src);
		}
		return $src;
	}

	private function http2push_reseource($url, $type = '')
	{
		if(headers_sent())
		{
			return false;
		}

		if($type == 'style' && !$this->http2pushCSS)
		{
			return false;
		}

		if($type == 'script' && !$this->http2pushJS)
		{
			return false;
		}

		//ignore external urls //push only works with paths
		$parsedURL = parse_url($this->ensure_scheme($url));
		if(!empty($parsedURL['host']) && $parsedURL['host'] != $this->host)
		{
			return false;
		}
		$http_link_header = array('Link: <' . $parsedURL['path'] . '>; rel=preload');

		if($type != '')
		{
			$http_link_header[] = 'as=' . $type;
		}

		header( implode('; ', $http_link_header), false);
	}

	/**
	 * Check if a URL is local or remote path
	 *
	 * @param string $url - the url to check
	 *
	 * @return bool - wether or not the url is a local or remote url path
	 */
	private function host_match($url)
	{
		if(empty($url))
		{
			return false;
		}

		$url = $this->ensure_scheme($url);
		$url_host = parse_url($url, PHP_URL_HOST);

		//check added by AbdulRaheem to allow a decoupled WordPress config to work
		$wp_host = parse_url(get_site_url(), PHP_URL_HOST);

		if(!$url_host || $url_host == $this->host || $url_host == $wp_host)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	//php < 5.4.7 parse_url returns null if host without scheme entered
	private function ensure_scheme($url)
	{
		return preg_replace("/(http(s)?:\/\/|\/\/)(.*)/i", "http$2://$3", $url);
	}

	private function remove_scheme($url)
	{
		return preg_replace("/(http(s)?:\/\/|\/\/)(.*)/i", "//$3", $url);
	}

	private function fix_wp_subfolder($file_path)
	{
		if(!is_main_site() && defined('SUBDOMAIN_INSTALL') && !SUBDOMAIN_INSTALL) //WordPress site is within a subfolder
		{
			$details = get_blog_details();
			$file_path = preg_replace('|^' . $details->path . '|', '/', $file_path);
		}
		/* WordPress includes files relative to its core. This fixes paths when WordPress isn't in the document root. */
		if(
			$this->wordpressdir != '' && //WordPress core is within a subfolder
			substr($file_path, 0, strlen($this->wordpressdir) + 1) != $this->wordpressdir . '/' && //File is not in WordPress core directory
			substr($file_path, 0, strlen($this->rootRelativeWPContentDir) + 1) != $this->rootRelativeWPContentDir . '/' //File is not in the wp-content directory
		) {
			$file_path = $this->wordpressdir . $file_path;
		}
		return $file_path;
	}

	public function inspect_styles()
	{
		wp_styles(); //ensure styles is initialised

		global $wp_styles;
		$this->process_scripts($wp_styles, 'css');
	}

	public function inspect_scripts()
	{
		wp_scripts(); //ensure scripts is initialised

		global $wp_scripts;
		$this->process_scripts($wp_scripts, 'js');
	}

	public function inspect_stylescripts_footer()
	{
		global $wp_scripts;
		$this->process_scripts($wp_scripts, 'js', true);

		global $wp_styles;
		$this->process_scripts($wp_styles, 'css', true);

		if($this->buffering)
		{
			$this->buffering = false;
			ob_end_flush();
		}

		if($this->hasMerged)
		{
			wp_schedule_single_event(time(), 'mmr_minify');

			// https://wordpress.org/support/topic/merge_minify_refresh_done-action/#post-11866992
			do_action('merge_minify_refresh_merged');
		}
	}

	/**
	 * process_scripts function.
	 *
	 * @param mixed &$script_list - copy of the global wp list
	 * @param mixed $ext - type of script to check 'css' or 'js'
	 * @param bool $in_footer (default: false)
	 * @return void
	 */
	private function process_scripts(&$script_list, $ext, $in_footer = false)
	{
		if($script_list)
		{
			//make a copy of the script list
			$scripts = clone $script_list;

			//determine script dependancies
			$scripts->all_deps($scripts->queue);

			//get all the handles for the scripts that need to be output
			$handles = $this->get_handles($ext, $scripts, !$in_footer)->getHandles();

			//store the scripts that have already been output
			$done = $scripts->done;

			//loop through header scripts and merge + schedule wpcron
			for($i=0, $l=count($handles); $i<$l; $i++)
			{
				if(!isset($handles[$i]['handle']))
				{
					$done = array_merge($done, $handles[$i]['handles']);

					/*
					VERSION ensures new files are generated when MMR updates
					*/
					$hash = hash("adler32", implode('', $handles[$i]['handles']) . self::VERSION);

					$file_path = '/' . $hash . '-' . $handles[$i]['modified'] . '.' . $ext;

					$full_path = MMR_CACHE_DIR . $file_path;

					$min_path = '/' . $hash . '-' . $handles[$i]['modified'] . '.min.' . $ext;

					$min_exists = file_exists(MMR_CACHE_DIR . $min_path);

					if(!file_exists($full_path) && !$min_exists)
					{
						$this->hasMerged = true;

						$output = '';
						$log = "";

						foreach( $handles[$i]['handles'] as $index => $handle)
						{
							$log .= " - " . $handle . " - " . $scripts->registered[$handle]->src;

							$script_path = parse_url($this->ensure_scheme($scripts->registered[$handle]->src), PHP_URL_PATH);
							$script_path = $this->fix_wp_subfolder($script_path);

							//https://wordpress.org/support/topic/php-warning-failed-to-open-stream-no-such-file-or-directory/
							if(!file_exists($this->root . $script_path))
							{
								continue;
							}

							$contents = "";
							$scriptContent = file_get_contents($this->root . $script_path);

							if(WP_DEBUG)
							{
								$contents .= "\n\n/******************\nMMR MERGED: " . $script_path . "\n***************/\n\n";
							}

							/*
								MMR expects encoding to be UTF-8 or ASCII
								PHP can easily convert ISO-8859-1 so we do that if required.
								It is difficult to detect other encoding types so please make sure UTF-8 files are used.
							*/
							if(extension_loaded('mbstring') && mb_detect_encoding($scriptContent, 'UTF-8,ISO-8859-1', true) == 'ISO-8859-1')
							{
								$scriptContent = utf8_encode($scriptContent);
							}

							// Remove the UTF-8 BOM
							$contents .= preg_replace("/^\xEF\xBB\xBF/", '', $scriptContent);

							// If JS add semicolon to ensure script finished and start a new line
							$contents .= $ext == 'js' ? ";\n" : "\n";

							if($ext == 'css')
							{
								//convert relative paths to absolute & ignore data:, ids or absolute paths (starts with /)
								$contents = preg_replace("/url\(\s*['\"]?(?!data:)(?!http)(?![\/'\"#])(.+?)['\"]?\s*\)/i", "url(" . dirname($script_path) . "/$1)", $contents);
							}

							if($ext == 'js')
							{
								$unmergedPath = $full_path . '-' . str_pad($index, 3, "0", STR_PAD_LEFT) . ".part";

								//file is already minified
								if(substr($script_path, -7) == '.min.' . $ext)
								{
									$unmergedPath .= '.min';
								}

								file_put_contents($unmergedPath, $this->removeUseStrict($contents), LOCK_EX);
							}

							$output .= $contents;
							$log .= "\n";
						}

						//remove existing expired files
						if(!defined('MMR_REMOVE_EXPIRED') || filter_var(MMR_REMOVE_EXPIRED, FILTER_VALIDATE_BOOLEAN) == true)
						{
							array_map('unlink', glob(MMR_CACHE_DIR . '/' . $hash . '-*.' . $ext));
						}

						//Filter Output - https://wordpress.org/support/topic/filter-minified-merged-scripts-file
						$output = apply_filters('modify_' . $ext . '_output_before_save', $output);

						file_put_contents($full_path , $output, LOCK_EX);
						if(count($handles[$i]['handles']) > 1)
						{
							file_put_contents($full_path . '.log', date('c') . " - MERGED:\n" . $log, LOCK_EX);
						}
						else
						{
							file_put_contents($full_path . '.log', date('c') . "\n" . $log, LOCK_EX);
						}

						if(!empty($handles[$i]["strategy"]))
						{
							file_put_contents($full_path . '.log', "&lt;script&gt; element is " . $handles[$i]["strategy"] . "\n", FILE_APPEND | LOCK_EX);
						}
					}
					else
					{
						file_put_contents($full_path . '.accessed', current_time('timestamp'), LOCK_EX);
					}


					$data = '';
					$before = [];
					$after = [];

					foreach( $handles[$i]['handles'] as $handle)
					{
						if(isset($scripts->registered[$handle]->extra['data']))
						{
							$data .= $scripts->registered[$handle]->extra['data'];
						}

						if(isset($scripts->registered[$handle]->extra['before']))
						{
							$before = array_merge($before, $scripts->registered[$handle]->extra['before']);
						}

						if(isset($scripts->registered[$handle]->extra['after']))
						{
							$after = array_merge($after, $scripts->registered[$handle]->extra['after']);
						}
					}

					$newHandle = $ext . '-' . $this->scriptcount;

					if($ext == 'js')
					{
						$args = [
							"in_footer" => $in_footer
						];
						if(!empty($handles[$i]["strategy"]))
						{
							$args["strategy"] = $handles[$i]["strategy"];
						}
						if($min_exists)
						{
							$this->http2push_reseource(MMR_JS_CACHE_URL . $min_path, 'script');
							wp_register_script($newHandle, MMR_JS_CACHE_URL . $min_path, $handles[$i]["deps"], false, $args);
						}
						else
						{
							$this->http2push_reseource(MMR_JS_CACHE_URL . $file_path, 'script');
							wp_register_script($newHandle, MMR_JS_CACHE_URL . $file_path, $handles[$i]["deps"], false, $args);
						}
					}
					else
					{
						if($min_exists)
						{
							$this->http2push_reseource(MMR_CSS_CACHE_URL . $min_path, 'style');
							wp_register_style($newHandle, MMR_CSS_CACHE_URL . $min_path, $handles[$i]["deps"], false, $handles[$i]['media']);
						}
						else
						{
							$this->http2push_reseource(MMR_CSS_CACHE_URL . $file_path, 'style');
							wp_register_style($newHandle, MMR_CSS_CACHE_URL . $file_path, $handles[$i]["deps"], false, $handles[$i]['media']);
						}
					}

					//set any existing data that was added with wp_localize_script
					if($data != '')
					{
						$script_list->registered[$newHandle]->extra['data'] = $data;
					}

					call_user_func('wp_enqueue_' . ($ext == "js" ? "script" : "style"), $newHandle);

					//includes any scripts that were added to any of the handles using wp_add_inline_script or wp_add_inline_style
					if(!empty($before))
					{
						$script_list->registered[$newHandle]->extra['before'] = $before;
					}
					if(!empty($after))
					{
						$script_list->registered[$newHandle]->extra['after'] = $after;
					}

					$this->scriptcount++;

				}
				else //external
				{
					$handle = $handles[$i]['handle'];
					if($ext == 'js')
					{
						wp_dequeue_script($handle); //need to do this so the order of scripts is retained
						wp_enqueue_script($handle);
						$this->http2push_reseource($scripts->registered[$handle]->src, 'script');
					}
					else
					{
						wp_dequeue_style($handle); //need to do this so the order of scripts is retained
						wp_enqueue_style($handle);
						$this->http2push_reseource($scripts->registered[$handle]->src, 'style');
					}
				}
			}

			$script_list->done = $done;
		}
	}

	/**
	 * get_handles function.
	 * 	Returns a list of the handles in $ourList in the order and grouping that mmr will need to merge them
	 * @access private
	 * @param mixed $type - type of script to check 'css' or 'js'
	 * @param mixed &$ourList - copy of the global wp list
	 * @param bool $ignoreFooterScripts (default: false) - whether to ignore scripts marked for the footer
	 * @return MMRHandlesList - MMR script handles list
	 */
	private function get_handles($type, &$ourList, $ignoreFooterScripts = false)
	{
		/*
		 * mmr_ignored_js_sources
		 * mmr_ignored_css_sources
		 *
		 * Allows the ignored files to be modified by plugins
		 */
		$ignoredSources = apply_filters('mmr_ignored_' . $type . '_sources', $this->ignore);

		$mergeInline = get_option("mmr-merge-inline");

		switch($type)
		{
			case 'js':
				$ext = 'js';
				$dontMerge = !$this->mergejs;
				$srcFilter = 'script_loader_src';
				$checkMedia = false;
				$checkForCSSImports = false;
				break;

			case 'css':
				$ext = 'css';
				$dontMerge = !$this->mergecss;
				$srcFilter = 'style_loader_src';
				$checkMedia = true;
				$checkForCSSImports = $this->checkcssimports;
				break;

			default:
				return array();
		}

		$handles = new HandlesList();
		foreach($ourList->to_do as $handle)
		{
			if($handle == "global-styles" && get_option("mmr-global-styles") == false)
			{
				$filename = MMR_CACHE_DIR . '/global-styles.css';
				$old = is_file($filename) ? file_get_contents($filename) : '';
				$new = implode("", $ourList->registered["global-styles"]->extra["after"]);
				if($new !== $old)
				{
					file_put_contents($filename, $new, LOCK_EX);
					// Need to clear the cache so that the filemtime call gets the correct modified time for this file
					clearstatcache();
				}
				$ourList->registered["global-styles"]->src = MMR_CACHE_URL . '/global-styles.css';
				unset($ourList->registered["global-styles"]->extra["after"]);
				$ourList->registered["global-styles"]->args = null;
			}

			// src may be set to false or null for inline styles and javascript.
			// It is safer to allow the inline code and move on. writing these scripts to files is likely to break.
			if(empty($ourList->registered[$handle]->src))
			{
				$handles->addNonMerged($handle);
				continue;
			}

			if(apply_filters($srcFilter, $ourList->registered[$handle]->src, $handle) !== false) //is valid src
			{
				if($ignoreFooterScripts)
				{
					$is_footer = isset($ourList->registered[$handle]->extra['group']);
					if($is_footer)
					{
						//ignore this script, so go on to the next one
						continue;
					}
				}
				$script_path = parse_url($this->ensure_scheme($ourList->registered[$handle]->src), PHP_URL_PATH);
				$script_path = $this->fix_wp_subfolder($script_path);

				$extension = pathinfo($script_path, PATHINFO_EXTENSION);

				if(
					file_exists($this->root . $script_path) &&
					$extension == $ext &&
					$this->host_match($ourList->registered[$handle]->src) && //is a local script
					!in_array($ourList->registered[$handle]->src, $ignoredSources) &&
					!isset($ourList->registered[$handle]->extra["conditional"])
				)
				{
					// We shouldn't be merging at all
					$shouldStartNewFileGroup = $dontMerge;

					//check if the file uses CSS @import
					if(!$shouldStartNewFileGroup && $checkForCSSImports)
					{
						$contents = file_get_contents($this->root . $script_path);
						$shouldStartNewFileGroup = strpos($contents, '@import') !== false;
					}

					// Check if we should start a new group if there is extra data
					if(!$shouldStartNewFileGroup && !$mergeInline)
					{
						if([] != ($ourList->registered[$handle]->extra["data"] ?? []))
						{
							$shouldStartNewFileGroup = true;
						}
					}

					// Check if we should start a new group if there is something to output before the script
					if(!$shouldStartNewFileGroup && !$mergeInline)
					{
						if([] != ($ourList->registered[$handle]->extra["before"] ?? []))
						{
							$shouldStartNewFileGroup = true;
						}
					}

					if($shouldStartNewFileGroup)
					{
						$handles->nextIsNewGroup();
					}

					$modifed = 0;
					if(is_file($this->root . $script_path))
					{
						$modified = filemtime($this->root . $script_path);
					}

					$media = null;
					if($checkMedia)
					{
						$media = isset($ourList->registered[$handle]->args) ? $ourList->registered[$handle]->args : 'all';
					}

					$strategy = $ourList->registered[$handle]->extra["strategy"] ?? null;

					$handles->addToCurrentGroup($handle, $modified, $ourList->registered[$handle]->deps, $media, $strategy);

					//start a new group if needed to respect inline script/style order
					if(!$mergeInline && [] != ($ourList->registered[$handle]->extra["after"] ?? []))
					{
						$handles->nextIsNewGroup();
					}
				}
				else //external script or not able to be processed
				{
					$handles->addNonMerged($handle);
				}
			}
		}

		return $handles;
	}

	private function compress_css($full_path)
	{
		if(is_file($full_path))
		{
			$min_path = str_replace('.css', '.min.css', $full_path);

			$this->refreshed = true;

			require_once('Minify/src/Minify.php');
			require_once('Minify/src/CSS.php');
			require_once('Minify/ConverterInterface.php');
			require_once('Minify/Converter.php');
			require_once('Minify/src/Exception.php');

			file_put_contents($full_path . '.log', date('c') . " - COMPRESSING CSS\n", FILE_APPEND | LOCK_EX);

			$file_size_before = filesize($full_path);

			$minifier = new MatthiasMullie\Minify\CSS($full_path);

			$minifier->minify($min_path);

			$file_size_after = filesize($min_path);

			file_put_contents($full_path . '.log', date('c') . " - COMPRESSION COMPLETE - " . $this->human_filesize($file_size_before-$file_size_after) . " saved\n", FILE_APPEND | LOCK_EX);
		}
	}

	/**
	 * Get the reason for not using Closure
	 *
	 * @return string - reason not to use closure. Blank string to use it.
	 */
	private function notUsingClosureReason()
	{
		static $closureReason = null;
		if(null !== $closureReason)
		{
			return $closureReason;
		}

		if(defined('MMR_USE_CLOSURE') && filter_var(MMR_USE_CLOSURE, FILTER_VALIDATE_BOOLEAN) == false)
		{
			return $closureReason = "Closure Disabled";
		}

		if(!function_exists("exec"))
		{
			return $closureReason = "PHP exec disabled";
		}

		if(exec('command -v java >/dev/null && echo "yes" || echo "no"') == 'no')
		{
			return $closureReason = "Java Not Found";
		}

		exec('java -version 2>&1', $jvoutput);
		if(!preg_match("/version\ \"(1\.[7-9]{1}+|[7-9]|[0-9]{2,})/", $jvoutput[0]))
		{
			return $closureReason = "Incorrect Java Version";
		}

		return $closureReason = "";
	}

	/**
	 * Checks if a file is minified Javascript.
	 * A file is considered minified if it has:
	 * - A line of greater than 700 bytes, or
	 * - More than 80 bytes per line
	 *
	 * @param string $full_path - the full path to the file to analyse
	 *
	 * @return bool - true if the file is considered minified
	 */
	private function isFileMinified($full_path)
	{
		$lines = 0;
		$fh = fopen($full_path, "r");
		$isMinified = false;
		while(false !== ($str = fgets($fh)))
		{
			$lines++;
			if(strlen($str) > 700)
			{
				$isMinified = true;
				break;
			}
		}
		fclose($fh);

		if($isMinified)
		{
			return true;
		}

		$ratio = filesize($full_path) / $lines;
		return $ratio > 80;
	}

	/**
	 * Compresses a JS file
	 *
	 * @param string $full_path - the full path to the file to compress
	 * @param string $log_path - the full path to the log file to write to
	 *
	 * @return string - the contents of the compressed file
	 */
	private function compress_js($full_path, $log_path)
	{
		if(!is_file($full_path))
		{
			file_put_contents($log_path, date('c') . " - COMPRESSING NON-EXISTANT FILE: " . basename($full_path) . "\n", FILE_APPEND | LOCK_EX);
			return "";
		}

		// If the file is already minified
		if(substr($full_path, -3) == "min")
		{
			file_put_contents($log_path, date('c') . " - FILE ALREADY MINIFIED " . basename($full_path) . "\n", FILE_APPEND | LOCK_EX);
			return file_get_contents($full_path);
		}

		// If the file looks like it has already been minified, but not marked as such
		if($this->isFileMinified($full_path))
		{
			file_put_contents($log_path, date('c') . " - FILE APPEARS TO BE MINIFIED " . basename($full_path) . "\n", FILE_APPEND | LOCK_EX);
			return file_get_contents($full_path);
		}

		$file_size_before = filesize($full_path);

		$this->refreshed = true;

		$reason = $this->notUsingClosureReason();
		// If there is no reason not to use closure
		if($reason == "")
		{
			file_put_contents($log_path, date('c') . " - COMPRESSING " . basename($full_path) . " WITH CLOSURE\n", FILE_APPEND | LOCK_EX);

			$cmd = 'java -jar \'' . WP_PLUGIN_DIR . '/merge-minify-refresh/closure-compiler.jar\' --warning_level QUIET --js \'' . $full_path . '\' --js_output_file \'' . $full_path . '.tmp\'';

			exec($cmd . ' 2>&1', $output);

			if(count($output) != 0)
			{
				ob_start();
				var_dump($output);
				$error=ob_get_contents();
				ob_end_clean();

				file_put_contents($log_path, date('c') . " - COMPRESSION FAILED\n" . $error, FILE_APPEND | LOCK_EX);
				unlink($full_path . '.tmp');
				return file_get_contents($full_path);
			}

			$content = file_get_contents($full_path . '.tmp');
			unlink($full_path . '.tmp');
		}
		else
		{
			require_once('Minify/src/Minify.php');
			require_once('Minify/src/JS.php');

			file_put_contents($log_path, date('c') . " - COMPRESSING " . basename($full_path) . " WITH MINIFY (" . $reason . ")\n", FILE_APPEND | LOCK_EX);

			$minifier = new MatthiasMullie\Minify\JS($full_path);

			$content = $minifier->minify();
		}

		$file_size_after = strlen($content);
		file_put_contents($log_path, date('c') . " - COMPRESSION COMPLETE - " . $this->human_filesize($file_size_before-$file_size_after) . " saved\n", FILE_APPEND | LOCK_EX);

		return $content;
	}

	/**
	 * Remove Javascript strict mode directives from provided contents.
	 * After testing it is safer to disable strict mode. Also, closure removes strict mode.
	 *
	 * @param string $contents - the javascript to remove strict mode from
	 *
	 * @return string - the filtered javascript
	 */
	private function removeUseStrict($contents)
	{
		return str_replace(
			[
				'"use strict";',
				"'use strict';"
			],
			"",
			$contents);
	}

	public function minify_action()
	{
		foreach($this->get_files_to_minify('css') as $path)
		{
			$cssPath = $path;

			if($this->cssmin)
			{
				$this->compress_css($path);
				$cssPath = str_replace('.css', '.min.css', $path);
			}

			$this->maybeGzip($cssPath, $path . '.log');
		}

		foreach($this->get_files_to_minify('js') as $path)
		{
			$jsPath = $path;

			if($this->jsmin)
			{
				$jsPath = str_replace('.js', '.min.js', $path);

				list($hash) = explode("-", basename($path));

				$pieces = glob(MMR_CACHE_DIR . '/' . $hash . '-*-*.part*');

				$tempMergedFile = $path . '.min';
				//open the minified master file for writing
				$handle = fopen($tempMergedFile, "w");

				if(flock($handle, LOCK_EX))
				{
					foreach($pieces as $piece)
					{
						// Returns contents of file (pre-minified, newly minified, or unminified if errored)
						$contents = $this->removeUseStrict($this->compress_js($piece, $path . '.log'));
						// append it to the file
						fwrite($handle, $contents);

						//Concatenated scripts that do not end in a semicolon can cause syntax issues, so we make sure they have one
						if(substr($contents, -1) != ';')
						{
							fwrite($handle, ';');
						}

						//remove part file
						if(!unlink($piece))
						{
							file_put_contents($path . '.log', date('c') . ' - FAILED TO DELETE - ' . basename($piece) . "\n", FILE_APPEND | LOCK_EX);
						}
					}
				}

				//close the file
				fclose($handle);

				// rename to remove incomplete suffix
				rename($tempMergedFile, $jsPath);
			}

			$this->maybeGzip($jsPath, $path . '.log');
		}
	}

	private function get_files_to_minify($ext)
	{
		return array_filter(glob(MMR_CACHE_DIR . '/*.' . $ext), function($file) use ($ext)
		{
			//echo '<li>' . $file;
			if(strpos($file, '.min.' . $ext))
			{
				return false;
			}
			return !file_exists(str_replace('.' . $ext, '.min.' . $ext, $file));
		});
	}

	//thanks to http://php.net/manual/en/function.filesize.php#106569
	private function human_filesize($bytes, $decimals = 2)
	{
		$sz = 'BKMGTP';
		$factor = intval(floor((strlen($bytes) - 1) / 3));
		return sprintf('%.' . $decimals . 'f', $bytes / pow(1024, $factor)) . $sz[$factor];
	}

	//thanks to Marcus Svensson
	/**
	 * Create a gzip version of file if gzip is enabled
	 *
	 * @param string $path - the path to the file to gzip
	 * @param string $logPath - the path to log file to write the result
	 *
	 * @return void
	 */
	private function maybeGzip($path, $logPath)
	{
		if($this->gzip)
		{
			try
			{
				$this->gzcompressfile($path);
				file_put_contents($logPath, date('c') . ' - GZIPPED - ' . $path . ".gz\n", FILE_APPEND | LOCK_EX);
			}
			catch(Exception $e)
			{
				file_put_contents($logPath, date('c') . ' - GZIP FAILED - ' . $path . ".gz" . $e->getMessage() . "\n", FILE_APPEND | LOCK_EX);
			}
		}
	}

	/**
	 * Compress a file using gzip
	 * https://stackoverflow.com/a/56140427/313272
	 *
	 * Rewritten from Simon East's version here:
	 * https://stackoverflow.com/a/22754032/3499843
	 *
	 * @param string $inFilename Input filename
	 * @param int    $level      Compression level (default: 9)
	 *
	 * @throws Exception if the input or output file can not be opened
	 *
	 * @return string Output filename
	 */
	private function gzcompressfile(string $inFilename, int $level = 9): string
	{
		// Is the file gzipped already?
		$extension = pathinfo($inFilename, PATHINFO_EXTENSION);
		if ($extension == "gz")
		{
			return $inFilename;
		}

		// Open input file
		$inFile = fopen($inFilename, "rb");

		if($inFile === false)
		{
			throw new \Exception("Unable to open input file: $inFilename");
		}

		// Open output file
		$gzFilename = $inFilename.".gz";
		$mode = "wb".$level;
		$gzFile = gzopen($gzFilename, $mode);

		if($gzFile === false)
		{
			fclose($inFile);
			throw new \Exception("Unable to open output file: $gzFilename");
		}

		// Stream copy
		$length = 512 * 1024; // 512 kB
		while(!feof($inFile))
		{
			gzwrite($gzFile, fread($inFile, $length));
		}

		gzclose($gzFile);
		fclose($inFile);

		// Return the new filename
		return $gzFilename;
	}

	/* thanks to @lucasbustamante */
	public function refreshed()
	{
		// only fire action if css or js compression has occurred
		if($this->refreshed === true)
		{
			do_action('merge_minify_refresh_done');
		}
	}

	public function showUpgradeNotification($data, $response)
	{
		if(isset($data['upgrade_notice']))
		{
			echo '<br/><strong style="color: red;">' . strip_tags($data['upgrade_notice']) . '</strong>';
		}
	}
}

$mergeminifyrefresh = new MergeMinifyRefresh();
