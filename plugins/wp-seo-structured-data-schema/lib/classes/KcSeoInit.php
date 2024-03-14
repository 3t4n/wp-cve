<?php

if (! class_exists('KcSeoInit')):

	class KcSeoInit {
		protected $version;

		public function __construct() {
			$this->version = (defined('WP_DEBUG') && WP_DEBUG) ? time() : KCSEO_WP_SCHEMA_VERSION;
			add_action('init', [$this, 'kcSeoScript']);
			add_action('admin_menu', [$this, 'kcSeo_Wp_Schema_menu']);
			add_action('plugins_loaded', [$this, 'kcSeo_pluginInit']);
			add_action('wp_ajax_kcSeoWpSchemaSettings', [$this, 'kcSeoWpSchemaSettings']);
			add_action('wp_ajax_kcSeoMainSettings_action', [$this, 'kcSeoMainSettings_action']);
			add_action('wp_ajax_newSocial', [$this, 'newSocial']);
			add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);

			// for MU Site
			add_action('activated_plugin', [$this, 'update_queue'], 10, 2);
			add_action('deactivated_plugin', [$this, 'update_queue'], 10, 2);

			register_activation_hook(KCSEO_WP_SCHEMA_PLUGIN_ACTIVE_FILE_NAME, [$this, 'activePlugin']);
//	        register_deactivation_hook(KCSEO_WP_SCHEMA_PLUGIN_ACTIVE_FILE_NAME, array($this, 'uninstall'));
			// Uninstall hook

			add_filter(
				'plugin_action_links_' . KCSEO_WP_SCHEMA_PLUGIN_ACTIVE_FILE_NAME,
				[$this, 'schema_marketing']
			);
		}

		public function schema_marketing($links) {
			$links[] = '<a target="_blank" href="' . esc_url('https://wpsemplugins.com/documentation/') . '">' . __('Documentation', 'wp-seo-structured-data-schema') . '</a>';
			$links[] = '<a target="_blank" href="' . esc_url('https://wpsemplugins.com/downloads/wordpress-schema-plugin/') . '">' . __('Get Pro', 'wp-seo-structured-data-schema') . '</a>';

			return $links;
		}

		public function update_queue($plugin, $network_wide = null) {
			if (! $network_wide) {
				return;
			}
			list($action) = explode('_', current_filter(), 2);

			$action = str_replace('activated', 'activate', $action);
			$queue  = get_site_option("network_{$action}_queue", []);

			$queue[$plugin] = (has_filter($action . '_' . $plugin) || has_filter($action . '_plugin'));
			update_site_option("network_{$action}_queue", $queue);
		}

		public function admin_enqueue_scripts() {
			global $pagenow;
			// validate page
			$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : null;
			if ($pagenow == 'admin.php' && ($page == 'wp-seo-schema' || $page == 'wp-seo-schema-settings')) {
				// scripts
				wp_enqueue_media();
				wp_enqueue_script([
					'jquery',
					'kcseo-select2-js',
					'kcseo-tooltip-js',
					'kcseo-admin-js',
				]);

				// styles
				wp_enqueue_style([
					'kcseo-select2-css',
					'kcseo-tooltip-css',
					'kcseo-admin-css',
				]);
			}
		}

		public function kcSeoScript() {
			global $KcSeoWPSchema;
			// register team scripts and styles
			$scripts = [];
			$styles  = [];
			if (is_admin()) {
				$scripts[]                   = [
					'handle' => 'kcseo-select2-js',
					'src'    => $KcSeoWPSchema->assetsUrl . 'js/select2.min.js',
					'deps'   => ['jquery'],
					'footer' => true,
				];
				$scripts[]                   = [
					'handle' => 'kcseo-tooltip-js',
					'src'    => $KcSeoWPSchema->assetsUrl . 'js/jquery.qtip.js',
					'deps'   => ['jquery'],
					'footer' => true,
				];
				$scripts[]                   = [
					'handle' => 'kcseo-admin-js',
					'src'    => $KcSeoWPSchema->assetsUrl . 'js/admin.js',
					'deps'   => ['jquery'],
					'footer' => true,
				];
				$styles['kcseo-select2-css'] = $KcSeoWPSchema->assetsUrl . 'css/select2.min.css';
				$styles['kcseo-tooltip-css'] = $KcSeoWPSchema->assetsUrl . 'css/jquery.qtip.css';
				$styles['kcseo-admin-css']   = $KcSeoWPSchema->assetsUrl . 'css/admin.css';
			}
			foreach ($scripts as $script) {
				wp_register_script($script['handle'], $script['src'], $script['deps'], $this->version, $script['footer']);
			}
			foreach ($styles as $k => $v) {
				wp_register_style($k, $v, false, $this->version);
			}
		}

		public function newSocial() {
			$schemaModel = new KcSeoSchemaModel;
			$id          = ($_REQUEST['id'] ? $_REQUEST['id'] + 1 : 0);
			$html        = null;
			$html        = "<div class='sfield'>";
			$html .= "<select name='social[$id][id]'>";
			foreach (KcSeoOptions::getSocialList() as $skey => $social) {
				$html .= "<option value='$skey'>$social</option>";
			}
			$html .= '</select>';
			$html .= "<input type='text' name='social[$id][link]'>";
			$html .= '<span class="dashicons dashicons-trash social-remove"></span>';
			$html .= '</div>';

			wp_send_json(['data' => $html]);
			die();
		}

		public function kcSeoWpSchemaSettings() {
			global $KcSeoWPSchema;
			$error = true;
			$msg   = null;
			if ($KcSeoWPSchema->verifyNonce()) {
				unset($_REQUEST['action']);
				update_option($KcSeoWPSchema->options['settings'], $_REQUEST);
				$error = false;
				$msg   = __('Settings successfully updated', KCSEO_WP_SCHEMA_SLUG);
			} else {
				$msg = __('Security Error !!', KCSEO_WP_SCHEMA_SLUG);
			}
			$response = [
				'error' => $error,
				'msg'   => $msg,
			];
			wp_send_json($response);
			die();
		}

		public function kcSeoMainSettings_action() {
			global $KcSeoWPSchema;
			$error = true;
			$msg   = null;
			if ($KcSeoWPSchema->verifyNonce()) {
				unset($_REQUEST['action']);
				unset($_REQUEST['_kcseo_nonce']);
				unset($_REQUEST['_wp_http_referer']);
				update_option($KcSeoWPSchema->options['main_settings'], $_REQUEST);
				$error = false;
				$msg   = __('Settings successfully updated', KCSEO_WP_SCHEMA_SLUG);
			} else {
				$msg = __('Security Error !!', KCSEO_WP_SCHEMA_SLUG);
			}
			$response = [
				'error' => $error,
				'msg'   => $msg,
			];
			wp_send_json($response);
			die();
		}

		public function wp_schema_page() {
			global $KcSeoWPSchema;
			$KcSeoWPSchema->render('schema-options');
		}

		public function wp_schema_setting_page() {
			global $KcSeoWPSchema;
			$KcSeoWPSchema->render('settings');
		}

		public function kcSeo_Wp_Schema_menu() {
			global $KcSeoWPSchema;
			add_menu_page(
				__('WP SEO Structured Data Schema', 'wp-seo-structured-data-schema'),
				__('WP SEO Schema', 'wp-seo-structured-data-schema'),
				'manage_options',
				'wp-seo-schema',
				[$this, 'wp_schema_page'],
				$KcSeoWPSchema->assetsUrl . 'images/wp-seo-schema.png'
			);
			add_submenu_page(
				'wp-seo-schema',
				__('WP SEO Schema settings', 'wp-seo-structured-data-schema'),
				__('Settings', 'wp-seo-structured-data-schema'),
				'manage_options',
				'wp-seo-schema-settings',
				[$this, 'wp_schema_setting_page']
			);
		}

		public function kcSeo_pluginInit() {
			load_plugin_textdomain(KCSEO_WP_SCHEMA_SLUG, false, KCSEO_WP_SCHEMA_LANGUAGE_PATH);
			$this->updateVariableAndFixIssue();
		}

		public function activePlugin() {
			$this->updateVariableAndFixIssue();
		}

		public function updateVariableAndFixIssue() {
			global $KcSeoWPSchema;
			$KcSeoWPSchema->fix1_2DataMigration();
			$KcSeoWPSchema->fix2_5_7_to_2_5_8();
			update_option($KcSeoWPSchema->options['installed_version'], $KcSeoWPSchema->options['version']);
		}
	}
endif;
