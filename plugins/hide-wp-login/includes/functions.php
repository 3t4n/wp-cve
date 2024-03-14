<?php

if (!defined('ABSPATH')) {
	exit;
}
if (!class_exists('HMWL_Functions')) {

	class HMWL_Functions {

		public function __construct() {
			global $wp, $wp_rewrite, $he_slug;
			$he_slug = 'elem';
			
			if(!function_exists('wp_get_current_user')) {
    			include(ABSPATH . "wp-includes/pluggable.php"); 
			}
			
			$hmwl_hide_elementor = get_option('hmwl_hide_elementor', 'no');
			if ('yes' == $hmwl_hide_elementor && !current_user_can('manage_options') ) {
				add_action('init', array(&$this, 'init'), 1);
				add_action('after_setup_theme', array(&$this, 'ob_starter'), 99);
				add_action('mod_rewrite_rules', array(&$this, 'elementor_mod_rewrite_rules'), 9999);

				add_filter('posts_request', array(&$this, 'disable_main_wp_query'), 110, 2);
				add_action('wp', array(&$this, 'elementor_assets_filter'), 9999);
			 	add_action('wp_footer', array($this,'buffer_end'));
			}
			
			
		}

		/**
		 * Add Query Variables
		 */
		public function init() {
			global $wp;
			$wp->add_query_var('he_wrapper_js');
			$wp->add_query_var('he_wrapper_css');
		}

		public function ob_starter() {
			ob_start(array(&$this, "global_html_filter"));
			/**
			 * Fix some WooCommerce themes bug
			 */
			if (class_exists('WooCommerce')) {
				ob_start();
			}
		}
		/**
		 * End Flush The HTML Content
		 */
		public function buffer_end() {  ob_end_flush(); }

		/**
		 * Filter HTML output
		 */
		public function global_html_filter($buffer) {
			if (!$this->is_html($buffer) && (isset($_GET['die_message']) || isset($_GET['doing_wp_cron']) || isset($_GET['he_wrapper_css']) || isset($_GET['he_wrapper_js']))) {
				return $buffer;
			}
			if (is_admin() || defined('DOING_AJAX')) {
				return $buffer;
			}
			$replace_array = array(
				'elementor-' => 'elem-',
				'elementor' => 'elem',
			);
			if (!empty($replace_array)) {
				foreach ($replace_array as $old => $new) {
					$buffer = str_replace($old, $new, $buffer);
				}
			}
			return $buffer;
		}

		/**
		 * Add Rewrite rules
		 */
		public function elementor_mod_rewrite_rules($rules) {
			global $wp_rewrite, $wp, $he_slug;
			if (!isset($_GET['preview']) ) {
				$wp_content = basename(WP_CONTENT_DIR);
				$plugins = basename(WP_PLUGIN_DIR);
				$upload_dirs = wp_upload_dir();
				$uploads = basename($upload_dirs['basedir']);
				$he_upload_path = "{$wp_content}/{$uploads}/{$he_slug}/css";
				$he_rules = PHP_EOL . "#BEGIN - Hide Elementor Rules" . PHP_EOL
					. "<IfModule mod_rewrite.c>" . PHP_EOL
					. "RewriteEngine On" . PHP_EOL
					. "RewriteCond %{THE_REQUEST} ^GET\ /{$wp_content}/{$plugins}/{$he_slug}/assets/" . PHP_EOL
					. "RewriteRule ^{$wp_content}/{$plugins}/{$he_slug}/assets/(.*).js index.php?he_wrapper_js={$wp_content}/{$plugins}/elementor/assets/$1.js" . PHP_EOL
					. "RewriteRule ^{$wp_content}/{$plugins}/{$he_slug}/assets/(.*).css index.php?he_wrapper_css={$wp_content}/{$plugins}/elementor/assets/$1.css" . PHP_EOL
					. "RewriteRule ^{$wp_content}/{$plugins}/{$he_slug}/assets/(.*).woff {$wp_content}/{$plugins}/elementor/assets/$1.woff" . PHP_EOL
					. "RewriteRule ^{$wp_content}/{$plugins}/{$he_slug}/assets/(.*).png {$wp_content}/{$plugins}/elementor/assets/$1.png" . PHP_EOL
					. "RewriteRule ^{$wp_content}/{$plugins}/{$he_slug}-pro/assets/(.*).js index.php?he_wrapper_js={$wp_content}/{$plugins}/elementor-pro/assets/$1.js" . PHP_EOL
					. "RewriteRule ^{$wp_content}/{$plugins}/{$he_slug}-pro/assets/(.*).css index.php?he_wrapper_css={$wp_content}/{$plugins}/elementor-pro/assets/$1.css" . PHP_EOL
					. "RewriteRule ^{$wp_content}/{$plugins}/{$he_slug}-pro/assets/(.*).woff {$wp_content}/{$plugins}/elementor-pro/assets/$1.woff" . PHP_EOL
					. "RewriteRule ^{$wp_content}/{$plugins}/{$he_slug}-pro/assets/(.*).png {$wp_content}/{$plugins}/elementor-pro/assets/$1.png" . PHP_EOL
					. "RewriteRule ^{$he_upload_path}/global\.css /index\.php?he_wrapper_css={$wp_content}/{$uploads}/elementor/css/global\.css" . PHP_EOL
					. "RewriteRule ^{$he_upload_path}/post-([0-9-_\\.]+)\.css /index\.php?he_wrapper_css={$wp_content}/{$uploads}/elementor/css/post-$1\.css" . PHP_EOL
					. "</IfModule>" . PHP_EOL
					. "#END - Hide Elementor Rules" . PHP_EOL . PHP_EOL;
				$rules = $he_rules . $rules;
			}
			return $rules;
		}

		/**
		 * Filter JS & CSS files
		 */
		public function elementor_assets_filter() {
			global $wp, $wp_query;
			if (!is_admin()) {
				$request_path = $filepath = '';
				$is_js = $is_css = false;
				if (isset($wp_query->query_vars['he_wrapper_js']) && $wp_query->query_vars['he_wrapper_js'] && $this->is_permalink()) {
					$is_js = true;
					$request_path = str_replace('elem-', 'elementor-', $wp_query->query_vars['he_wrapper_js']);
				}
				if (isset($wp_query->query_vars['he_wrapper_css']) && $wp_query->query_vars['he_wrapper_css'] && $this->is_permalink()) {
					$is_css = true;
					$request_path = str_replace('elem-', 'elementor-', $wp_query->query_vars['he_wrapper_css']);
				}
				if (!empty($request_path) && file_exists(ABSPATH . '/' . $request_path)) {
					$filepath = ABSPATH . '/' . $request_path;
					status_header(200);
					header("Pragma: public");
					$expires = 60 * 60 * 24 * 10;
					if ($is_js) {
						header('Content-type: application/javascript');
					} elseif ($is_css) {
						$expires = 60 * 60 * 24 * 3;
						header('Content-type: text/css');
					}
					header("Cache-Control: maxage=" . $expires);
					header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
					
					$code = file_get_contents($filepath);
					$code = str_replace('elementor-', 'elem-', $code);
					echo wp_kses_post($code); 
					exit;
				}
			}
		}

		public function disable_main_wp_query($sql, WP_Query $wpQuery) {
			if ($wpQuery->is_main_query() && (isset($_GET['he_wrapper_css']) || isset($_GET['he_wrapper_js']))) {
				/* prevent SELECT FOUND_ROWS() query */
				$wpQuery->query_vars['no_found_rows'] = true;
				/* prevent post term and meta cache update queries */
				$wpQuery->query_vars['cache_results'] = false;
				return false;
			}
			return $sql;
		}

		public function is_permalink() {
			global $wp_rewrite;
			if (!isset($wp_rewrite) || !is_object($wp_rewrite) || !$wp_rewrite->using_permalinks()) {
				return false;
			}
			return true;
		}

		public function is_html($content) {
			if (strlen($content) > 1000) {
				$content = substr($content, 0, 1000);
			}
			$content = ltrim($content, "\x00\x09\x0A\x0D\x20\xBB\xBF\xEF");
			return stripos($content, '{[') !== false || stripos($content, '{"') !== false || stripos($content, '<?xml') !== false || stripos($content, '<html') !== false || stripos($content, '<!DOCTYPE') !== false;
		}

	}

}
global $HMWL_Functions;
$HMWL_Functions = new HMWL_Functions();
