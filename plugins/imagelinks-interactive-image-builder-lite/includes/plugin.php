<?php
defined('ABSPATH') || exit;

class ImageLinks_Builder {
	private $pluginBasename = NULL;
	
	private $ajax_action_item_update = NULL;
	private $ajax_action_item_update_status = NULL;
	private $ajax_action_marker_template_save = NULL;
	private $ajax_action_marker_template_delete = NULL;
	private $ajax_action_settings_update = NULL;
	private $ajax_action_settings_get = NULL;
	private $ajax_action_delete_data = NULL;
	private $ajax_action_modal = NULL;
	
	private $shortcodes = array();
	
	function __construct($pluginBasename) {
		$this->pluginBasename = $pluginBasename;
	}
	
	function run() {
		$upload_dir = wp_upload_dir();
		$plugin_url = plugin_dir_url(dirname(__FILE__));
		
		define('IMAGELINKS_PLUGIN_MARKER_TEMPLATES_DIR', wp_normalize_path(dirname(dirname(__FILE__))) . '/assets/templates');
		define('IMAGELINKS_PLUGIN_UPLOAD_DIR', wp_normalize_path($upload_dir['basedir'] . '/imagelinks'));
		define('IMAGELINKS_PLUGIN_UPLOAD_URL', $upload_dir['baseurl'] . '/imagelinks/');
		
		define('IMAGELINKS_PLUGIN_PLAN', 'lite');
		
		if(is_admin()) {
			$this->ajax_action_item_update = 'imagelinks_ajax_item_update';
			$this->ajax_action_item_update_status = 'imagelinks_ajax_item_update_status';
			$this->ajax_action_marker_template_save = 'imagelinks_ajax_marker_template_save';
			$this->ajax_action_marker_template_delete = 'imagelinks_ajax_marker_template_delete';
			$this->ajax_action_settings_update = 'imagelinks_ajax_settings_update';
			$this->ajax_action_settings_get = 'imagelinks_ajax_settings_get';
			$this->ajax_action_delete_data = 'imagelinks_ajax_delete_data';
			$this->ajax_action_modal = 'imagelinks_ajax_modal';
			
			load_plugin_textdomain('imagelinks', false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/');
			
			add_action('admin_menu', [$this, 'admin_menu']);
			add_action('admin_notices', [$this, 'admin_notices']);
			add_action('wp_loaded', [$this, 'page_redirects']);
			
			// important, because ajax has another url
			add_action('wp_ajax_' . $this->ajax_action_item_update, array($this, 'ajax_item_update'));
			add_action('wp_ajax_' . $this->ajax_action_item_update_status, array($this, 'ajax_item_update_status'));
			add_action('wp_ajax_' . $this->ajax_action_marker_template_save, array($this, 'ajax_marker_template_save'));
			add_action('wp_ajax_' . $this->ajax_action_marker_template_delete, array($this, 'ajax_marker_template_delete'));
			add_action('wp_ajax_' . $this->ajax_action_settings_update, array($this, 'ajax_settings_update'));
			add_action('wp_ajax_' . $this->ajax_action_settings_get, array($this, 'ajax_settings_get'));
			add_action('wp_ajax_' . $this->ajax_action_delete_data, array($this, 'ajax_delete_data'));
			add_action('wp_ajax_' . $this->ajax_action_modal, array($this, 'ajax_modal'));
		} else {
			add_shortcode(IMAGELINKS_SHORTCODE_NAME, array($this, 'shortcode'));
		}
		
		// permissions for the preview feature
		$user = wp_get_current_user();
		$allowed_roles = array('editor', 'administrator', 'author');
		if( array_intersect($allowed_roles, $user->roles ) ) {
			add_filter('query_vars', array($this, 'query_vars'));
			add_action('init', array($this, 'add_rewrite_rule'));
			add_action('template_redirect', array($this, 'rewrite_catch'));
		}
	}
	
	function query_vars($vars) {
		$vars[] = 'imagelinks';
		return $vars;
	}
	
	function add_rewrite_rule() {
		add_rewrite_tag('%imagelinks%', '([^&]+)');
		add_rewrite_rule('^imagelinks/([^/]*)/?', 'index.php?imagelinks=$matches[1]', 'top');
	}
	
	function rewrite_catch() {
		global $wp_query;
		
		if(array_key_exists('imagelinks', $wp_query->query_vars)) {
			require_once( plugin_dir_path( dirname(__FILE__) ) . 'includes/page-preview.php' );
			exit;
		}
	}
	
	function IsNullOrEmptyString($str) {
		return(!isset($str) || trim($str)==='');
	}
	
	/**
	 * generate main css text
	 */
	function getMainCss($itemData, $itemId) {
		$upload_dir = wp_upload_dir();
		
		// create main css
		$main_css = '';
		$main_css .= '.imgl-map-' . $itemId . ' {' . PHP_EOL;
		
		$main_css .= (!$this->IsNullOrEmptyString($itemData->background->color) ? 'background-color:' . $itemData->background->color . ';' . PHP_EOL : '');
		if(!$this->IsNullOrEmptyString($itemData->background->image->url)) {
			$imageUrl = ($itemData->background->image->relative ? $upload_dir['baseurl'] : '') . $itemData->background->image->url;
			$main_css .= 'background-image:url(' . $imageUrl . ');' . PHP_EOL;
		}
		$main_css .= ($itemData->background->size ? 'background-size:' . $itemData->background->size . ';' . PHP_EOL : '');
		$main_css .= ($itemData->background->repeat ? 'background-repeat:' . $itemData->background->repeat . ';' . PHP_EOL : '');
		$main_css .= ($itemData->background->position ? 'background-position:' . $itemData->background->position . ';' . PHP_EOL : '');
		
		$main_css .= '}' . PHP_EOL;
		
		$markerId = 0;
		foreach($itemData->markers as $markerKey => $marker) {
			if(!$marker->visible) {
				continue;
			}
			
			$markerId++;
			$markerSelector = '.imgl-map-' . $itemId . ' .imgl-pin-' . $markerId;
			
			// main
			$main_css .= $markerSelector . ' .imgl-pin-data {' . PHP_EOL;
			
			$main_css .= (!$this->IsNullOrEmptyString($marker->view->background->color) ? 'background-color:' . $marker->view->background->color . ';' . PHP_EOL : '');
			if(!$this->IsNullOrEmptyString($marker->view->background->image->url)) {
				$imageUrl = ($marker->view->background->image->relative ? $upload_dir['baseurl'] : '') . $marker->view->background->image->url;
				$main_css .= 'background-image:url(' . $imageUrl . ');' . PHP_EOL;
			}
			$main_css .= ($marker->view->background->size ? 'background-size:' . $marker->view->background->size . ';' . PHP_EOL : '');
			$main_css .= ($marker->view->background->repeat ? 'background-repeat:' . $marker->view->background->repeat . ';' . PHP_EOL : '');
			$main_css .= ($marker->view->background->position ? 'background-position:' . $marker->view->background->position . ';' . PHP_EOL : '');
			
			if($marker->view->border->all->active) {
				$main_css .= ($marker->view->border->all->width->value ? 'border-width:' . $marker->view->border->all->width->value . $marker->view->border->all->width->type . ';' . PHP_EOL : '');
				$main_css .= ($marker->view->border->all->style ? 'border-style:' . $marker->view->border->all->style . ';' . PHP_EOL : '');
				$main_css .= ($marker->view->border->all->color ? 'border-color:' . $marker->view->border->all->color . ';' . PHP_EOL : '');
			}
			
			
			if($marker->view->border->top->active) {
				$main_css .= ($marker->view->border->top->width->value ? 'border-top-width:' . $marker->view->border->top->width->value . $marker->view->border->top->width->type . ';' . PHP_EOL : '');
				$main_css .= ($marker->view->border->top->style ? 'border-top-style:' . $marker->view->border->top->style . ';' . PHP_EOL : '');
				$main_css .= ($marker->view->border->top->color ? 'border-top-color:' . $marker->view->border->top->color . ';' . PHP_EOL : '');
			}
			
			if($marker->view->border->right->active) {
				$main_css .= ($marker->view->border->right->width->value ? 'border-right-width:' . $marker->view->border->right->width->value . $marker->view->border->right->width->type . ';' . PHP_EOL : '');
				$main_css .= ($marker->view->border->right->style ? 'border-right-style:' . $marker->view->border->right->style . ';' . PHP_EOL : '');
				$main_css .= ($marker->view->border->right->color ? 'border-right-color:' . $marker->view->border->right->color . ';' . PHP_EOL : '');
			}
			
			if($marker->view->border->bottom->active) {
				$main_css .= ($marker->view->border->bottom->width->value ? 'border-bottom-width:' . $marker->view->border->bottom->width->value . $marker->view->border->bottom->width->type . ';' . PHP_EOL : '');
				$main_css .= ($marker->view->border->bottom->style ? 'border-bottom-style:' . $marker->view->border->bottom->style . ';' . PHP_EOL : '');
				$main_css .= ($marker->view->border->bottom->color ? 'border-bottom-color:' . $marker->view->border->bottom->color . ';' . PHP_EOL : '');
			}
			
			if($marker->view->border->left->active) {
				$main_css .= ($marker->view->border->left->width->value ? 'border-left-width:' . $marker->view->border->left->width->value . $marker->view->border->left->width->type . ';' . PHP_EOL : '');
				$main_css .= ($marker->view->border->left->style ? 'border-left-style:' . $marker->view->border->left->style . ';' . PHP_EOL : '');
				$main_css .= ($marker->view->border->left->color ? 'border-left-color:' . $marker->view->border->left->color . ';' . PHP_EOL : '');
			}
			
			$borderRadius = ($marker->view->border->radius->all->value ? $marker->view->border->radius->all->value . $marker->view->border->radius->all->type : NULL);
			$main_css .= ($marker->view->border->radius->topLeft->value ? 'border-top-left-radius:' . $marker->view->border->radius->topLeft->value . $marker->view->border->radius->topLeft->type . ';' . PHP_EOL : ($borderRadius ? 'border-top-left-radius:' . $borderRadius . ';' . PHP_EOL : ''));
			$main_css .= ($marker->view->border->radius->topRight->value ? 'border-top-right-radius:' . $marker->view->border->radius->topRight->value . $marker->view->border->radius->topRight->type . ';' . PHP_EOL : ($borderRadius ? 'border-top-right-radius:' . $borderRadius . ';' . PHP_EOL : ''));
			$main_css .= ($marker->view->border->radius->bottomRight->value ? 'border-bottom-right-radius:' . $marker->view->border->radius->bottomRight->value . $marker->view->border->radius->bottomRight->type . ';' . PHP_EOL : ($borderRadius ? 'border-bottom-right-radius:' . $borderRadius . ';' . PHP_EOL : ''));
			$main_css .= ($marker->view->border->radius->bottomLeft->value ? 'border-bottom-left-radius:' . $marker->view->border->radius->bottomLeft->value . $marker->view->border->radius->bottomLeft->type . ';' . PHP_EOL : ($borderRadius ? 'border-bottom-left-radius:' . $borderRadius . ';' . PHP_EOL : ''));
			
			$main_css .= '}' . PHP_EOL;
			
			// icon
			$main_css .= $markerSelector . ' .imgl-ico-wrap {' . PHP_EOL;
			if(!$this->IsNullOrEmptyString($marker->view->icon->name) || !$this->IsNullOrEmptyString($marker->view->icon->label)) {
				$main_css .= ($marker->view->icon->size->value ? 'font-size:' . $marker->view->icon->size->value . $marker->view->icon->size->type . ';' . PHP_EOL : '');
				$main_css .= ($marker->view->icon->size->value ? 'line-height:' . $marker->view->icon->size->value . $marker->view->icon->size->type . ';' . PHP_EOL : '');
				$main_css .= ($marker->view->icon->color ? 'color:' . $marker->view->icon->color . ';' . PHP_EOL : '');
			}
			$margin = ($marker->view->icon->margin->all->value ? $marker->view->icon->margin->all->value . $marker->view->icon->margin->all->type : NULL);
			$main_css .= ($marker->view->icon->margin->top->value ? 'margin-top:' . $marker->view->icon->margin->top->value . $marker->view->icon->margin->top->type . ';' . PHP_EOL : ($margin ? 'margin-top:' . $margin . ';' . PHP_EOL : ''));
			$main_css .= ($marker->view->icon->margin->right->value ? 'margin-right:' . $marker->view->icon->margin->right->value . $marker->view->icon->margin->right->type . ';' . PHP_EOL : ($margin ? 'margin-right:' . $margin . ';' . PHP_EOL : ''));
			$main_css .= ($marker->view->icon->margin->bottom->value ? 'margin-bottom:' . $marker->view->icon->margin->bottom->value . $marker->view->icon->margin->bottom->type . ';' . PHP_EOL : ($margin ? 'margin-bottom:' . $margin . ';' . PHP_EOL : ''));
			$main_css .= ($marker->view->icon->margin->left->value ? 'margin-left:' . $marker->view->icon->margin->left->value . $marker->view->icon->margin->left->type . ';' . PHP_EOL : ($margin ? 'margin-left:' . $margin . ';' . PHP_EOL : ''));
			$main_css .= '}' . PHP_EOL;
			
			if($marker->view->pulse->active) {
				$main_css .= $markerSelector . ' .imgl-pin-pulse {' . PHP_EOL;
				
				$main_css .= ($marker->view->pulse->color ? 'background-color:' . $marker->view->pulse->color . ';' . PHP_EOL : '');
				$main_css .= ($marker->view->pulse->duration ? 'animation-duration:' . $marker->view->pulse->duration . 'ms;' . PHP_EOL : '');
				
				$borderRadius = ($marker->view->border->radius->all->value ? $marker->view->border->radius->all->value . $marker->view->border->radius->all->type : NULL);
				$main_css .= ($marker->view->border->radius->topLeft->value ? 'border-top-left-radius:' . $marker->view->border->radius->topLeft->value . $marker->view->border->radius->topLeft->type . ';' . PHP_EOL : ($borderRadius ? 'border-top-left-radius:' . $borderRadius . ';' . PHP_EOL : ''));
				$main_css .= ($marker->view->border->radius->topRight->value ? 'border-top-right-radius:' . $marker->view->border->radius->topRight->value . $marker->view->border->radius->topRight->type . ';' . PHP_EOL : ($borderRadius ? 'border-top-right-radius:' . $borderRadius . ';' . PHP_EOL : ''));
				$main_css .= ($marker->view->border->radius->bottomRight->value ? 'border-bottom-right-radius:' . $marker->view->border->radius->bottomRight->value . $marker->view->border->radius->bottomRight->type . ';' . PHP_EOL : ($borderRadius ? 'border-bottom-right-radius:' . $borderRadius . ';' . PHP_EOL : ''));
				$main_css .= ($marker->view->border->radius->bottomLeft->value ? 'border-bottom-left-radius:' . $marker->view->border->radius->bottomLeft->value . $marker->view->border->radius->bottomLeft->type . ';' . PHP_EOL : ($borderRadius ? 'border-bottom-left-radius:' . $borderRadius . ';' . PHP_EOL : ''));
				
				$main_css .= '}' . PHP_EOL;
			}
		}
		
		return $main_css;
	}
	
	/**
	 * Shortcode output for the plugin
	 */
	function shortcode($atts) {
        $keys_valid = ['id', 'class'];
        $atts_valid = array_filter($atts, function($key) use ($keys_valid) {
            return in_array($key, $keys_valid);
        }, ARRAY_FILTER_USE_KEY);

        extract(shortcode_atts(['id' => 0, 'class' => NULL], $atts_valid, IMAGELINKS_SHORTCODE_NAME));

		if(!$id) {
			return '<p>' . esc_html__('Error: invalid imagelinks shortcode attributes', 'imagelinks') . '</p>';
		}

        $id = intval($id, 10);
        $class = sanitize_text_field($class);

		global $wpdb;
		$table = $wpdb->prefix . IMAGELINKS_PLUGIN_NAME;
		$upload_dir = wp_upload_dir();
		
		$query = $wpdb->prepare("SELECT * FROM {$table} WHERE id=%s", $id);
		$item = $wpdb->get_row($query, OBJECT);
		if($item) {
			if(!$item->active) {
				return;
			}
			
			$version = strtotime(mysql2date('d M Y H:i:s', $item->modified));
			$itemData = unserialize($item->data);
			$id = $item->id;
			$id_postfix = strtolower(wp_generate_password(5, false)); // generate unique postfix for $id to avoid clashes with multiple same shortcode use
			$id_element = 'imgl-' . $id . '-' . $id_postfix;
			
			array_push($this->shortcodes, ['id' => $item->id, 'version' => $version]);
			
			if(sizeof($this->shortcodes) == 1) {
				$plugin_url = plugin_dir_url(dirname(__FILE__));
				wp_enqueue_script('imagelinks_loader_js', $plugin_url . 'assets/js/loader.min.js', ['jquery'], IMAGELINKS_PLUGIN_VERSION, true);

				$globals = [
					'plan' => IMAGELINKS_PLUGIN_PLAN,
					'version' => $version,
					'fontawesome_url' => $plugin_url . 'assets/css/font-awesome.min.css',
					'effects_url' => $plugin_url . 'assets/js/lib/imagelinks/imagelinks-effects.min.css',
					'theme_base_url' => $plugin_url . 'assets/themes/',
					'plugin_base_url' => $plugin_url . 'assets/js/lib/imagelinks/',
					'plugin_upload_base_url' => IMAGELINKS_PLUGIN_UPLOAD_URL
				];
				wp_localize_script('imagelinks_loader_js', 'imagelinks_globals', $globals);
			}

			ob_start(); // turn on buffering
			
			echo '<!-- imagelinks begin -->' . PHP_EOL;
			echo '<div ';
			echo 'id="' . esc_attr($id_element) . '" ';
			echo 'class="imgl-map imgl-map-' . esc_attr($id . ' ' . ($class ? ' ' . $class : '')) . '"'; // $class from shortcode
			echo 'data-json-src="'. esc_url(IMAGELINKS_PLUGIN_UPLOAD_URL . $item->id . '/config.json?ver=' . $version) . '" ';
			echo 'data-item-id="' . esc_attr($item->id) . '" ';
			echo 'style="display:none;" ';
			echo '>' . PHP_EOL;
				
				//=============================================
				// STORE BEGIN
				echo '<div class="imgl-store">' . PHP_EOL;
				
				$markerId = 0;
				foreach($itemData->markers as $markerKey => $marker) {
					if(!$marker->visible) {
						continue;
					}
					
					$markerId++;
					
					//=============================================
					// MARKER BEGIN
					echo '<div class="imgl-pin imgl-pin-' . esc_attr($markerId) . '" data-id="' . esc_attr($markerId) . '">' . PHP_EOL;
					
					if($marker->view->pulse->active) {
						echo '<div class="imgl-pin-pulse"></div>' . PHP_EOL;
					}
					
					echo '<div class="imgl-pin-data">' . PHP_EOL;
					if(!$this->IsNullOrEmptyString($marker->view->icon->name) || !$this->IsNullOrEmptyString($marker->view->icon->label)) {
						echo '<div class="imgl-ico-wrap">' . PHP_EOL;
						if(!$this->IsNullOrEmptyString($marker->view->icon->name)) {
							echo '<div class="imgl-ico"><i class="fa ' . esc_attr($marker->view->icon->name) . '"></i></div>' . PHP_EOL;
						}
						if(!$this->IsNullOrEmptyString($marker->view->icon->label)) {
							echo '<div class="imgl-ico-lbl">' . esc_html($marker->view->icon->label) . '</div>' . PHP_EOL;
						}
						echo '</div>' . PHP_EOL;
					}
					echo '</div>' . PHP_EOL;
					
					echo '</div>' . PHP_EOL;
					// MARKER END
					//=============================================
				}
				
				$markerId = 0;
				foreach($itemData->markers as $markerKey => $marker) {
					if(!$marker->visible) {
						continue;
					}
					
					$markerId++;
					
					//=============================================
					// TOOLTIP BEGIN
					echo '<div ';
					echo 'class="imgl-tt imgl-tt-' . esc_attr($markerId) . '" ';
					echo 'data-id="' . esc_attr($markerId) . '" ';
					echo '>' . PHP_EOL;
					echo do_shortcode($marker->tooltip->data);
					echo '</div>' . PHP_EOL;
					// TOOLTIP END
					//=============================================
				}
				
				echo '</div>' . PHP_EOL;
				// STORE END
				//=============================================
				
			echo '</div>' . PHP_EOL;
			echo '<!-- imagelinks end -->' . PHP_EOL;
			
			$output = ob_get_contents(); // get the buffered content into a var
			ob_end_clean(); // clean buffer
			
			return $output;
		} else {
			return '<p>' . esc_html__('Error: invalid imagelinks database record', 'imagelinks') . '</p>';
		}
	}
	
	/**
	 * Prepare upload directory
	 */
	function admin_notices() {
		$page = sanitize_key(filter_input(INPUT_GET, 'page', FILTER_DEFAULT));
		
		if(!($page==='imagelinks' || $page==='imagelinks_settings')) {
            return;
		}
		
		if(!file_exists(IMAGELINKS_PLUGIN_UPLOAD_DIR)) {
			wp_mkdir_p(IMAGELINKS_PLUGIN_UPLOAD_DIR);
		}
		
		if(!file_exists(IMAGELINKS_PLUGIN_UPLOAD_DIR)) {
			echo '<div class="notice notice-error is-dismissible">';
			echo '<p>' . esc_html__('The "imagelinks" directory could not be created', 'imagelinks') . '</p>';
			echo '<p>' . esc_html__('Please run the following commands in order to make the directory', 'imagelinks') . '<br>';
			echo '<b>mkdir ' . IMAGELINKS_PLUGIN_UPLOAD_DIR . '</b><br>';
			echo '<b>chmod 777 ' . IMAGELINKS_PLUGIN_UPLOAD_DIR . '</b></p>';
			echo '</div>';
			return;
		}
		
		if(!wp_is_writable(IMAGELINKS_PLUGIN_UPLOAD_DIR)) {
			echo '<div class="notice notice-error is-dismissible">';
			echo '<p>' . esc_html__('The "imagelinks" directory is not writable, therefore the css and js files cannot be saved.', 'imagelinks') . '</p>';
			echo '<p>' . esc_html__('Please run the following commands in order to make the directory', 'imagelinks') . '<br>';
			echo '<b>chmod 777 ' . IMAGELINKS_PLUGIN_UPLOAD_DIR . '</b></p>';
			echo '</div>';
			return;
		}
		
		if(!file_exists(IMAGELINKS_PLUGIN_UPLOAD_DIR . '/' . 'index.php')) {
			$data = '<?php' . PHP_EOL . '// silence is golden' . PHP_EOL . '?>';
			@file_put_contents(IMAGELINKS_PLUGIN_UPLOAD_DIR . '/' . 'index.php', $data);
		}
	}
	
	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 */
	function admin_menu() {
		// add "edit_posts" if we want to give access to author, editor and contributor roles
		add_menu_page(esc_html__('ImageLinks', 'imagelinks'), esc_html__('ImageLinks', 'imagelinks'), 'manage_options', 'imagelinks', [$this, 'admin_menu_page_items'], 'dashicons-location');
		add_submenu_page('imagelinks', esc_html__('ImageLinks', 'imagelinks'), esc_html__('All Items', 'imagelinks'), 'manage_options', 'imagelinks', [$this, 'admin_menu_page_items']);
		add_submenu_page('imagelinks', esc_html__('ImageLinks', 'imagelinks'), esc_html__('Add New', 'imagelinks'), 'manage_options', 'imagelinks_item', [$this, 'admin_menu_page_item']);
		add_submenu_page('imagelinks', esc_html__('ImageLinks', 'imagelinks'), esc_html__('Settings', 'imagelinks'), 'manage_options', 'imagelinks_settings', [$this, 'admin_menu_page_settings']);
	}
	
	/**
	 * Custom redirects
	 */
	function page_redirects() {
		$page = sanitize_key(filter_input(INPUT_GET, 'page', FILTER_DEFAULT ));
		
		if($page==='imagelinks') {
			$action = sanitize_key(filter_input(INPUT_GET, 'action', FILTER_DEFAULT ));
			if($action == 'duplicate' || $action == 'delete') {
				$url = admin_url('admin.php?page=' . $page);
				header('Refresh:0; url="' . $url . '"', true, 303);
				//wp_redirect($url); // does not work delete and dublicate operations on XAMPP
			}
		}
	}
	
	/**
	 * Show admin menu items page
	 */
	function admin_menu_page_items() {
		$page = sanitize_key(filter_input(INPUT_GET, 'page', FILTER_DEFAULT ));

		if($page==='imagelinks') {
			$plugin_url = plugin_dir_url( dirname(__FILE__) );
			$upload_dir = wp_upload_dir();

			wp_enqueue_style('imagelinks_admin_css', $plugin_url . 'assets/css/admin.min.css', [], IMAGELINKS_PLUGIN_VERSION, 'all');
			wp_enqueue_style('imagelinks_font_icons_css', $plugin_url . 'assets/css/imagelinks-font-icons.min.css', [], IMAGELINKS_PLUGIN_VERSION, 'all');
			wp_enqueue_script('imagelinks_ace_js', $plugin_url . 'assets/js/lib/ace/ace.js', [], IMAGELINKS_PLUGIN_VERSION, false);
			wp_enqueue_script('imagelinks_admin_js', $plugin_url . 'assets/js/admin.min.js', ['jquery'], IMAGELINKS_PLUGIN_VERSION, false);
			
			// global settings to help ajax work
			$globals = [
				'plan' => IMAGELINKS_PLUGIN_PLAN,
				'msg_pro_title' => esc_html__('Available only in the pro version', 'imagelinks'),
				'upload_url' => $upload_dir['baseurl'],
				'ajax_url' => admin_url('admin-ajax.php'),
				'ajax_nonce' => wp_create_nonce('imagelinks_ajax'),
				'ajax_msg_error' => esc_html__('Uncaught Error', 'imagelinks') //Look at the console (F12 or Ctrl+Shift+I, Console tab) for more information
            ];
			
			$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
			$action = sanitize_key(filter_input(INPUT_GET, 'action', FILTER_DEFAULT ));
			$nonce = sanitize_key(filter_input(INPUT_GET, '_wpnonce', FILTER_DEFAULT ));
			
			if($action && $nonce && wp_verify_nonce($nonce, 'imagelinks')) {
				global $wpdb;
				$table = $wpdb->prefix . IMAGELINKS_PLUGIN_NAME;
				
				if($action == 'duplicate') {
					$result = false;
					
					$query = $wpdb->prepare("SELECT * FROM {$table} WHERE id=%s", $id);
					$item = $wpdb->get_row($query, OBJECT);
					
					if($item && (current_user_can('administrator') || get_current_user_id()==$item->author) ) {
						$itemData = unserialize($item->data);
						$itemData->title = esc_html__('[Duplicate] ', 'imagelinks') . $itemData->title;
						$itemConfig = unserialize($item->config);
						
						$result = $wpdb->insert(
							$table,
							array(
								'title' => $itemData->title,
								'active' => $itemData->active,
								'data' => serialize($itemData),
								'config' => serialize($itemConfig),
								'author' => get_current_user_id(),
								'date' => current_time('mysql', 1),
								'modified' => current_time('mysql', 1)
						));

						// [filemanager] create an external file
						if($result && wp_is_writable(IMAGELINKS_PLUGIN_UPLOAD_DIR)) {
							$file_json = 'config.json';
							$file_main_css = 'main.css';
							$file_custom_css = 'custom.css';
							$file_root_path = IMAGELINKS_PLUGIN_UPLOAD_DIR . '/' . $wpdb->insert_id . '/';
							
							if(!is_dir($file_root_path)) {
								mkdir($file_root_path);
							}
							
							@file_put_contents($file_root_path . $file_json, json_encode($itemConfig));
							@file_put_contents($file_root_path . $file_main_css, $this->getMainCss($itemData, $wpdb->insert_id));
							@file_put_contents($file_root_path . $file_custom_css, $itemData->customCSS->data);
						}
						exit;
					}
				}
				if($action=='delete') {
					$result = false;
					
					$query = $wpdb->prepare("SELECT * FROM {$table} WHERE id=%s", $id);
					$item = $wpdb->get_row($query, OBJECT);
					if($item && (current_user_can('administrator') || get_current_user_id()==$item->author) ) {
						$result = $wpdb->delete( $table, ['id'=>$id], ['%d']);
						
						//======================================
						// [filemanager] delete file
						if($result && wp_is_writable(IMAGELINKS_PLUGIN_UPLOAD_DIR)) {
							$file_json = 'config.json';
							$file_main_css = 'main.css';
							$file_custom_css = 'custom.css';
							$file_root_path = IMAGELINKS_PLUGIN_UPLOAD_DIR . '/' . $id . '/';
							
							wp_delete_file($file_root_path . $file_json);
							wp_delete_file($file_root_path . $file_main_css);
							wp_delete_file($file_root_path . $file_custom_css);
							
							if(is_dir($file_root_path)) {
								rmdir($file_root_path);
							}
						}
						//======================================
						exit;
					}
				}
			}
			$globals['ajax_action_update'] = $this->ajax_action_item_update_status;
			
			require_once( plugin_dir_path( dirname(__FILE__) ) . 'includes/list-table-items.php' );
			require_once( plugin_dir_path( dirname(__FILE__) ) . 'includes/page-items.php' );

			wp_localize_script('imagelinks_admin_js', 'imagelinks_globals', $globals);
		}
	}
	
	/**
	 * Show admin menu item page
	 */
	function admin_menu_page_item() {
		$page = sanitize_key(filter_input(INPUT_GET, 'page', FILTER_DEFAULT ));
		
		if($page==='imagelinks_item') {
			$plugin_url = plugin_dir_url(dirname(__FILE__));
			$upload_dir = wp_upload_dir();

			wp_enqueue_style('imagelinks_admin_css', $plugin_url . 'assets/css/admin.min.css', [], IMAGELINKS_PLUGIN_VERSION, 'all' );
			wp_enqueue_style('imagelinks_font_icons_css', $plugin_url . 'assets/css/imagelinks-font-icons.min.css', [], IMAGELINKS_PLUGIN_VERSION, 'all' );
			wp_enqueue_style('imagelinks_fontawesome_css', $plugin_url . 'assets/css/font-awesome.min.css', [], IMAGELINKS_PLUGIN_VERSION, 'all' );
			wp_enqueue_style('imagelinks_effects_css', $plugin_url . 'assets/js/lib/imagelinks/imagelinks-effects.min.css', [], IMAGELINKS_PLUGIN_VERSION, 'all' );
			wp_enqueue_script('imagelinks_ace_js', $plugin_url . 'assets/js/lib/ace/ace.js', [], IMAGELINKS_PLUGIN_VERSION, false );
			wp_enqueue_script('imagelinks_admin_js', $plugin_url . 'assets/js/admin.min.js', ['jquery'], IMAGELINKS_PLUGIN_VERSION, false );
			wp_enqueue_media();
			
			// global settings to help ajax work
			$globals = array(
				'plan' => IMAGELINKS_PLUGIN_PLAN,
				'msg_pro_title' => esc_html__('Available only in the pro version', 'imagelinks'),
				'msg_edit_text' => esc_html__('Edit your text here', 'imagelinks'),
				'msg_custom_js_error' => esc_html__('Custom js code error', 'imagelinks'),
				'wp_base_url' => get_site_url(),
				'upload_base_url' => $upload_dir['baseurl'],
				'plugin_base_url' => $plugin_url,
				'ajax_url' => admin_url('admin-ajax.php'),
				'ajax_nonce' => wp_create_nonce('imagelinks_ajax'),
				'ajax_msg_error' => esc_html__('Uncaught Error', 'imagelinks') //Look at the console (F12 or Ctrl+Shift+I, Console tab) for more information
			);
			
			$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
			
			$globals['ajax_action_get'] = $this->ajax_action_settings_get;
			$globals['ajax_action_update'] = $this->ajax_action_item_update;
			$globals['ajax_action_modal'] = $this->ajax_action_modal;
			$globals['ajax_action_template_save'] = $this->ajax_action_marker_template_save;
			$globals['ajax_action_template_delete'] = $this->ajax_action_marker_template_delete;
			$globals['ajax_item_id'] = $id;
			$globals['settings'] = NULL;
			$globals['config'] = NULL;
			
			$settings_key = 'imagelinks_settings';
			$settings_value = get_option($settings_key);
			if($settings_value) {
				$globals['settings'] = json_encode(unserialize($settings_value));
			}
			
			// get item data from DB
			if($id) {
				global $wpdb;
				$table = $wpdb->prefix . IMAGELINKS_PLUGIN_NAME;
				
				$query = $wpdb->prepare("SELECT * FROM {$table} WHERE id=%s", $id);
				$item = $wpdb->get_row($query, OBJECT);
				if($item) {
					$globals['config'] = json_encode(unserialize($item->data));
				}
			} else {
				// new item
				$item = (object) array(
					'author' => get_current_user_id(),
					'date' => current_time('mysql', 1),
					'modified' => current_time('mysql', 1)
				);
			}
			
			require_once(plugin_dir_path( dirname(__FILE__) ) . 'includes/page-item.php');

			wp_localize_script('imagelinks_admin_js', 'imagelinks_globals', $globals);
		}
	}
	
	/**
	 * Show admin menu settings page
	 */
	function admin_menu_page_settings() {
		$page = sanitize_key(filter_input(INPUT_GET, 'page', FILTER_DEFAULT ));
		
		if($page==='imagelinks_settings') {
			$plugin_url = plugin_dir_url(dirname(__FILE__));

			wp_enqueue_style('imagelinks_admin_css', $plugin_url . 'assets/css/admin.min.css', [], IMAGELINKS_PLUGIN_VERSION, 'all' );
			wp_enqueue_style('imagelinks_font_icons_css', $plugin_url . 'assets/css/imagelinks-font-icons.min.css', [], IMAGELINKS_PLUGIN_VERSION, 'all' );
			wp_enqueue_script('imagelinks_admin_js', $plugin_url . 'assets/js/admin.min.js', ['jquery'], IMAGELINKS_PLUGIN_VERSION, false );
			
			// global settings to help ajax work
			$globals = array(
				'plan' => IMAGELINKS_PLUGIN_PLAN,
				'msg_pro_title' => esc_html__('Available only in the pro version', 'imagelinks'),
				'ajax_url' => admin_url('admin-ajax.php'),
				'ajax_nonce' => wp_create_nonce('imagelinks_ajax'),
				'ajax_msg_error' => esc_html__('Uncaught Error', 'imagelinks') //Look at the console (F12 or Ctrl+Shift+I, Console tab) for more information
			);
			
			$globals['ajax_action_update'] = $this->ajax_action_settings_update;
			$globals['ajax_action_get'] = $this->ajax_action_settings_get;
			$globals['ajax_action_delete_data'] = $this->ajax_action_delete_data;
			$globals['config'] = NULL;
			
			// read settings
			$settings_key = 'imagelinks_settings';
			$settings_value = get_option($settings_key);
			if($settings_value) {
				$globals['config'] = json_encode(unserialize($settings_value));
			}
			
			require_once(plugin_dir_path( dirname(__FILE__) ) . 'includes/page-settings.php');

			wp_localize_script('imagelinks_admin_js', 'imagelinks_globals', $globals);
		}
	}
	
	/**
	 * Ajax update item state
	 */
	function ajax_item_update_status() {
		$error = false;
		$data = array();
		$config = filter_input(INPUT_POST, 'config', FILTER_UNSAFE_RAW);
		
		if(check_ajax_referer('imagelinks_ajax', 'nonce', false)) {
			global $wpdb;
			$table = $wpdb->prefix . IMAGELINKS_PLUGIN_NAME;

            $config = json_decode($config);
			$result = false;
			
			if(isset($config->id) && isset($config->active)) {
				$query = $wpdb->prepare("SELECT * FROM {$table} WHERE id=%s", $config->id);
				$item = $wpdb->get_row($query, OBJECT );
				
				if($item && (current_user_can('administrator') || get_current_user_id()==$item->author) ) {
					$itemData = unserialize($item->data);
					$itemData->active = $config->active;
					
					$result = $wpdb->update(
						$table,
						array(
							'active'=> $itemData->active,
							'data' => serialize($itemData)
						),
						array('id'=>$config->id));
				}
			}
			
			if($result) {
				$data['id'] = $config->id;
				$data['msg'] = esc_html__('Item updated', 'imagelinks');
			} else {
				$error = true;
				$data['msg'] = esc_html__('The operation failed, can\'t update the item', 'imagelinks');
			}
		} else {
			$error = true;
			$data['msg'] = esc_html__('The operation failed', 'imagelinks');
		}
		
		if($error) {
			wp_send_json_error($data);
		} else {
			wp_send_json_success($data);
		}
		
		wp_die(); // this is required to terminate immediately and return a proper response
	}
	
	/**
	 * Ajax update item data
	 */
	function ajax_item_update() {
		$error = false;
		$data = array();
		
		if(check_ajax_referer('imagelinks_ajax', 'nonce', false)) {
			global $wpdb;
			$table = $wpdb->prefix . IMAGELINKS_PLUGIN_NAME;
			
			$inputId = filter_input(INPUT_POST, 'id', FILTER_UNSAFE_RAW);
			$inputData = filter_input(INPUT_POST, 'data', FILTER_UNSAFE_RAW);
			$inputConfig = filter_input(INPUT_POST, 'config', FILTER_UNSAFE_RAW);
			$itemData = json_decode($inputData);
			$itemConfig = json_decode($inputConfig);
			$flag = true;
			
			if(IMAGELINKS_PLUGIN_PLAN == 'lite') {
				$rowcount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table}"));
				
				if(!($rowcount == 0 || ($rowcount == 1 && $inputId))) {
					$flag = false;
					$error = true;
					$data['msg'] = esc_html__('The operation failed, you can work only with one item. To create more, buy the pro version.', 'imagelinks');
				}
			}
			
			if($flag) {
				if($inputId) {
					$result = false;
					
					$query = $wpdb->prepare('SELECT * FROM ' . $table . ' WHERE id=%s', $inputId);
					$item = $wpdb->get_row($query, OBJECT);
					if($item && (current_user_can('administrator') || get_current_user_id()==$item->author) ) {
						$result = $wpdb->update(
							$table,
							array(
								'title' => $itemData->title,
								'active' => $itemData->active,
								'data' => serialize($itemData),
								'config' => serialize($itemConfig),
								'author' => get_current_user_id(),
								//'date' => NULL,
								'modified' => current_time('mysql', 1)
							),
							array('id'=>$inputId));
					}
					
					if($result) {
						$data['id'] = $inputId;
						$data['msg'] = esc_html__('Item updated', 'imagelinks');
					} else {
						$error = true;
						$data['msg'] = esc_html__('The operation failed, can\'t update the item', 'imagelinks');
					}
				} else {
					$result = $wpdb->insert(
						$table,
						array(
							'title' => $itemData->title,
							'active' => $itemData->active,
							'data' => serialize($itemData),
							'config' => serialize($itemConfig),
							'author' => get_current_user_id(),
							'date' => current_time('mysql', 1),
							'modified' => current_time('mysql', 1)
						));
					
					if($result) {
						$data['id'] = $inputId = $wpdb->insert_id;
						$data['msg'] = esc_html__('Item created', 'imagelinks');
					} else {
						$error = true;
						$data['msg'] = esc_html__('The operation failed, can\'t create the item', 'imagelinks');
					}
				}
			}
			
			//======================================
			// [filemanager] create an external file
			if(!$error && wp_is_writable(IMAGELINKS_PLUGIN_UPLOAD_DIR)) {
				$file_json = 'config.json';
				$file_main_css = 'main.css';
				$file_custom_css = 'custom.css';
				$file_root_path = IMAGELINKS_PLUGIN_UPLOAD_DIR . '/' . $inputId . '/';
				
				if(!is_dir($file_root_path)) {
					mkdir($file_root_path);
				}
				
				@file_put_contents($file_root_path . $file_json, json_encode($itemConfig));
				@file_put_contents($file_root_path . $file_main_css, $this->getMainCss($itemData, $inputId));
				@file_put_contents($file_root_path . $file_custom_css, $itemData->customCSS->data);
			}
			//======================================
		} else {
			$error = true;
			$data['msg'] = esc_html__('The operation failed', 'imagelinks');
		}
		
		if($error) {
			wp_send_json_error($data);
		} else {
			wp_send_json_success($data);
		}
		
		wp_die(); // this is required to terminate immediately and return a proper response
	}
	
	/**
	 * Ajax save marker as a template
	 */
	function ajax_marker_template_save() {
		$error = false;
		$data = array();
		
		if(check_ajax_referer('imagelinks_ajax', 'nonce', false)) {
			$inputName = sanitize_text_field(filter_input(INPUT_POST, 'name', FILTER_DEFAULT));
			$inputData = filter_input(INPUT_POST, 'data', FILTER_UNSAFE_RAW);
			$itemData = json_decode($inputData);
			
			if($this->IsNullOrEmptyString($inputName)) {
				$error = true;
				$data['msg'] = esc_html__('The operation failed, can\'t create the template. Set the template name right', 'imagelinks');
			} else {
				//======================================
				// [filemanager] create an external file
				if(wp_is_writable(IMAGELINKS_PLUGIN_UPLOAD_DIR)) {
					$file_json = $inputName . '.json';
					$file_root_path = IMAGELINKS_PLUGIN_UPLOAD_DIR . '/templates/';
					
					if(!is_dir($file_root_path)) {
						mkdir($file_root_path);
					}
					
					if(!file_exists($file_root_path . $file_json)) {
						@file_put_contents($file_root_path . $file_json, json_encode($itemData));
						$data['msg'] = esc_html__('Template created', 'imagelinks');
					} else {
						$error = true;
						$data['msg'] = esc_html__('The operation failed, can\'t create the template. The template already exists with the same name', 'imagelinks');
					}
				} else {
					$error = true;
					$data['msg'] = esc_html__('The operation failed, can\'t create the template. The template folder is unwritable', 'imagelinks');
				}
				//======================================
			}
		} else {
			$error = true;
			$data['msg'] = esc_html__('The operation failed', 'imagelinks');
		}
		
		if($error) {
			wp_send_json_error($data);
		} else {
			wp_send_json_success($data);
		}
		
		wp_die(); // this is required to terminate immediately and return a proper response
	}
	
	/**
	 * Ajax save marker as a template
	 */
	function ajax_marker_template_delete() {
		$error = false;
		$data = array();
		
		if(check_ajax_referer('imagelinks_ajax', 'nonce', false)) {
			$inputName = sanitize_text_field(filter_input(INPUT_POST, 'name', FILTER_DEFAULT));

			// [filemanager] delete file
			if(wp_is_writable(IMAGELINKS_PLUGIN_UPLOAD_DIR)) {
				$file_json = $inputName . '.json';
				$file_root_path = IMAGELINKS_PLUGIN_UPLOAD_DIR . '/templates/';
				
				if(file_exists($file_root_path . $file_json)) {
					wp_delete_file($file_root_path . $file_json);
					$data['msg'] = esc_html__('Template deleted', 'imagelinks');
				} else {
					$error = true;
					$data['msg'] = esc_html__('The operation failed, can\'t delete the template. The template does not exists', 'imagelinks');
				}
				
				if(is_dir($file_root_path)) {
					rmdir($file_root_path);
				}
			}
			//======================================
		} else {
			$error = true;
			$data['msg'] = esc_html__('The operation failed', 'imagelinks');
		}
		
		if($error) {
			wp_send_json_error($data);
		} else {
			wp_send_json_success($data);
		}
		
		wp_die(); // this is required to terminate immediately and return a proper response
	}
	
	/**
	 * Ajax update settings data
	 */
	function ajax_settings_update() {
		$error = false;
		$data = array();
		$config = filter_input(INPUT_POST, 'config', FILTER_UNSAFE_RAW);
		
		if(check_ajax_referer('imagelinks_ajax', 'nonce', false)) {
			$settings_key = 'imagelinks_settings';
			$settings_value = serialize(json_decode($config));
			$result = false;
			
			if(get_option($settings_key) == false) {
				$deprecated = null;
				$autoload = 'no';
				$result = add_option($settings_key, $settings_value, $deprecated, $autoload);
			} else {
				$old_settings_value = get_option($settings_key);
				if($old_settings_value === $settings_value) {
					$result = true;
				} else {
					$result = update_option($settings_key, $settings_value);
				}
			}
			
			if($result) {
				$data['msg'] = esc_html__('Settings updated', 'imagelinks');
			} else {
				$error = true;
				$data['msg'] = esc_html__('The operation failed, can\'t update settings', 'imagelinks');
			}
		}
		
		if($error) {
			wp_send_json_error($data);
		} else {
			wp_send_json_success($data);
		}
		
		wp_die(); // this is required to terminate immediately and return a proper response
	}
	
	/**
	 * Ajax settings get data
	 */
	function ajax_settings_get() {
		$error = false;
		$data = array();
		$type = sanitize_key(filter_input(INPUT_POST, 'type', FILTER_DEFAULT));
		
		if(check_ajax_referer('imagelinks_ajax', 'nonce', false)) {
			switch($type) {
				case 'templates': {
					$plugin_url = plugin_dir_url(dirname(__FILE__));
					$plugin_url = substr($plugin_url, 0, -1); // remove last '/'
					
					$data['list'] = array();
					
					$files = glob(IMAGELINKS_PLUGIN_MARKER_TEMPLATES_DIR . '/*.json');
					foreach($files as $file) {
						$filename = basename($file, '.json');
						$filedata = file_get_contents($file);
						
						$filedata = str_replace('[plugin]', $plugin_url, $filedata);
						
						$jsonData = json_decode($filedata);
						
						array_push($data['list'], array('name' => $filename,'data' => $jsonData, 'predefined' => true));
					}
					
					$files = glob(IMAGELINKS_PLUGIN_UPLOAD_DIR . '/templates/*.json');
					foreach($files as $file) {
						$filename = basename($file, '.json');
						
						$filedata = file_get_contents($file);
						$jsonData = json_decode($filedata);
						
						array_push($data['list'], array('name' => $filename,'data' => $jsonData, 'predefined' => false));
					}
				}
				break;
				case 'themes': {
					$data['list'] = array();
					
					$files = glob(plugin_dir_path( dirname(__FILE__) ) . 'assets/themes/*.min.css');
					foreach($files as $file) {
						$filename = basename($file, '.min.css');
						array_push($data['list'], array('id' => $filename, 'title' => str_replace('-', ' ', $filename)));
					}
				}
				break;
				case 'editor-themes': {
					$data['list'] = array();
					
					$files = glob(plugin_dir_path( dirname(__FILE__) ) . 'assets/js/lib/ace/theme-*.js');
					foreach($files as $file) {
						$filename = str_replace('theme-','',basename($file, '.js'));
						array_push($data['list'], array('id' => $filename, 'title' => str_replace('_', ' ', $filename)));
					}
				}
				break;
				case 'fonts': {
					$data['list'] = array(
						array('fontname' => 'none'),
						array('fontname' => 'Aclonica'),
						array('fontname' => 'Allan'),
						array('fontname' => 'Annie+Use+Your+Telescope'),
						array('fontname' => 'Anonymous+Pro'),
						array('fontname' => 'Allerta+Stencil'),
						array('fontname' => 'Allerta'),
						array('fontname' => 'Amaranth'),
						array('fontname' => 'Anton'),
						array('fontname' => 'Architects+Daughter'),
						array('fontname' => 'Arimo'),
						array('fontname' => 'Artifika'),
						array('fontname' => 'Arvo'),
						array('fontname' => 'Asset'),
						array('fontname' => 'Astloch'),
						array('fontname' => 'Bangers'),
						array('fontname' => 'Bentham'),
						array('fontname' => 'Bevan'),
						array('fontname' => 'Bigshot+One'),
						array('fontname' => 'Bowlby+One'),
						array('fontname' => 'Bowlby+One+SC'),
						array('fontname' => 'Brawler'),
						//array('fontname' => 'Buda:300'),
						array('fontname' => 'Cabin'),
						array('fontname' => 'Calligraffitti'),
						array('fontname' => 'Candal'),
						array('fontname' => 'Cantarell'),
						array('fontname' => 'Cardo'),
						array('fontname' => 'Carter One'),
						array('fontname' => 'Caudex'),
						array('fontname' => 'Cedarville+Cursive'),
						array('fontname' => 'Cherry+Cream+Soda'),
						array('fontname' => 'Chewy'),
						array('fontname' => 'Coda'),
						array('fontname' => 'Coming+Soon'),
						array('fontname' => 'Copse'),
						//array('fontname' => 'Corben:700'),
						array('fontname' => 'Cousine'),
						array('fontname' => 'Covered+By+Your+Grace'),
						array('fontname' => 'Crafty+Girls'),
						array('fontname' => 'Crimson+Text'),
						array('fontname' => 'Crushed'),
						array('fontname' => 'Cuprum'),
						array('fontname' => 'Damion'),
						array('fontname' => 'Dancing+Script'),
						array('fontname' => 'Dawning+of+a+New+Day'),
						array('fontname' => 'Didact+Gothic'),
						array('fontname' => 'Droid+Sans'),
						array('fontname' => 'Droid+Sans+Mono'),
						array('fontname' => 'Droid+Serif'),
						array('fontname' => 'EB+Garamond'),
						array('fontname' => 'Expletus+Sans'),
						array('fontname' => 'Fontdiner+Swanky'),
						array('fontname' => 'Forum'),
						array('fontname' => 'Francois+One'),
						array('fontname' => 'Geo'),
						array('fontname' => 'Give+You+Glory'),
						array('fontname' => 'Goblin+One'),
						array('fontname' => 'Goudy+Bookletter+1911'),
						array('fontname' => 'Gravitas+One'),
						array('fontname' => 'Gruppo'),
						array('fontname' => 'Hammersmith+One'),
						array('fontname' => 'Holtwood+One+SC'),
						array('fontname' => 'Homemade+Apple'),
						array('fontname' => 'Inconsolata'),
						array('fontname' => 'Indie+Flower'),
						array('fontname' => 'IM+Fell+DW+Pica'),
						array('fontname' => 'IM+Fell+DW+Pica+SC'),
						array('fontname' => 'IM+Fell+Double+Pica'),
						array('fontname' => 'IM+Fell+Double+Pica+SC'),
						array('fontname' => 'IM+Fell+English'),
						array('fontname' => 'IM+Fell+English+SC'),
						array('fontname' => 'IM+Fell+French+Canon'),
						array('fontname' => 'IM+Fell+French+Canon+SC'),
						array('fontname' => 'IM+Fell+Great+Primer'),
						array('fontname' => 'IM+Fell+Great+Primer+SC'),
						array('fontname' => 'Irish+Grover'),
						array('fontname' => 'Irish+Growler'),
						array('fontname' => 'Istok+Web'),
						array('fontname' => 'Josefin+Sans'),
						array('fontname' => 'Josefin+Slab'),
						array('fontname' => 'Judson'),
						array('fontname' => 'Jura'),
						//array('fontname' => 'Jura:500'),
						//array('fontname' => 'Jura:600'),
						array('fontname' => 'Just+Another+Hand'),
						array('fontname' => 'Just+Me+Again+Down+Here'),
						array('fontname' => 'Kameron'),
						array('fontname' => 'Kenia'),
						array('fontname' => 'Kranky'),
						array('fontname' => 'Kreon'),
						array('fontname' => 'Kristi'),
						array('fontname' => 'La+Belle+Aurore'),
						//array('fontname' => 'Lato:100'),
						//array('fontname' => 'Lato:300'), 
						array('fontname' => 'Lato'),
						//array('fontname' => 'Lato:bold'),
						//array('fontname' => 'Lato:900'),
						array('fontname' => 'League+Script'),
						array('fontname' => 'Lekton'),  
						array('fontname' => 'Limelight'),  
						array('fontname' => 'Lobster'),
						array('fontname' => 'Lobster Two'),
						array('fontname' => 'Lora'),
						array('fontname' => 'Love+Ya+Like+A+Sister'),
						array('fontname' => 'Loved+by+the+King'),
						array('fontname' => 'Luckiest+Guy'),
						array('fontname' => 'Maiden+Orange'),
						array('fontname' => 'Mako'),
						array('fontname' => 'Maven+Pro'),
						//array('fontname' => 'Maven+Pro:500'),
						//array('fontname' => 'Maven+Pro:700'),
						//array('fontname' => 'Maven+Pro:900'),
						array('fontname' => 'Meddon'),
						array('fontname' => 'MedievalSharp'),
						array('fontname' => 'Megrim'),
						array('fontname' => 'Merriweather'),
						array('fontname' => 'Metrophobic'),
						array('fontname' => 'Michroma'),
						array('fontname' => 'Miltonian+Tattoo'),
						array('fontname' => 'Miltonian'),
						array('fontname' => 'Modern Antiqua'),
						array('fontname' => 'Monofett'),
						array('fontname' => 'Molengo'),
						array('fontname' => 'Mountains of Christmas'),
						//array('fontname' => 'Muli:300'),
						array('fontname' => 'Muli'), 
						array('fontname' => 'Neucha'),
						array('fontname' => 'Neuton'),
						array('fontname' => 'News+Cycle'),
						array('fontname' => 'Nixie+One'),
						array('fontname' => 'Nobile'),
						array('fontname' => 'Nova+Cut'),
						array('fontname' => 'Nova+Flat'),
						array('fontname' => 'Nova+Mono'),
						array('fontname' => 'Nova+Oval'),
						array('fontname' => 'Nova+Round'),
						array('fontname' => 'Nova+Script'),
						array('fontname' => 'Nova+Slim'),
						array('fontname' => 'Nova+Square'),
						//array('fontname' => 'Nunito:light'),
						array('fontname' => 'Nunito'),
						array('fontname' => 'OFL+Sorts+Mill+Goudy+TT'),
						array('fontname' => 'Old+Standard+TT'),
						//array('fontname' => 'Open+Sans:300'),
						array('fontname' => 'Open+Sans'),
						//array('fontname' => 'Open+Sans:600'),
						//array('fontname' => 'Open+Sans:800'),
						//array('fontname' => 'Open+Sans+Condensed:300'),
						array('fontname' => 'Orbitron'),
						//array('fontname' => 'Orbitron:500'),
						//array('fontname' => 'Orbitron:700'),
						//array('fontname' => 'Orbitron:900'),
						array('fontname' => 'Oswald'),
						array('fontname' => 'Over+the+Rainbow'),
						array('fontname' => 'Reenie+Beanie'),
						array('fontname' => 'Pacifico'),
						array('fontname' => 'Patrick+Hand'),
						array('fontname' => 'Paytone+One'), 
						array('fontname' => 'Permanent+Marker'),
						array('fontname' => 'Philosopher'),
						array('fontname' => 'Play'),
						array('fontname' => 'Playfair+Display'),
						array('fontname' => 'Podkova'),
						array('fontname' => 'PT+Sans'),
						array('fontname' => 'PT+Sans+Narrow'),
						//array('fontname' => 'PT+Sans+Narrow:regular,bold'),
						array('fontname' => 'PT+Serif'),
						array('fontname' => 'PT+Serif Caption'),
						array('fontname' => 'Puritan'),
						array('fontname' => 'Quattrocento'),
						array('fontname' => 'Quattrocento+Sans'),
						array('fontname' => 'Radley'),
						//array('fontname' => 'Raleway:100'),
						array('fontname' => 'Redressed'),
						array('fontname' => 'Rock+Salt'),
						array('fontname' => 'Rokkitt'),
						array('fontname' => 'Ruslan+Display'),
						array('fontname' => 'Schoolbell'),
						array('fontname' => 'Shadows+Into+Light'),
						array('fontname' => 'Shanti'),
						array('fontname' => 'Sigmar+One'),
						array('fontname' => 'Six+Caps'),
						array('fontname' => 'Slackey'),
						array('fontname' => 'Smythe'),
						//array('fontname' => 'Sniglet:800'),
						array('fontname' => 'Special+Elite'),
						array('fontname' => 'Stardos+Stencil'),
						array('fontname' => 'Sue+Ellen+Francisco'),
						array('fontname' => 'Sunshiney'),
						array('fontname' => 'Swanky+and+Moo+Moo'),
						array('fontname' => 'Syncopate'),
						array('fontname' => 'Tangerine'),
						array('fontname' => 'Tenor+Sans'),
						array('fontname' => 'Terminal+Dosis+Light'),
						array('fontname' => 'The+Girl+Next+Door'),
						array('fontname' => 'Tinos'),
						array('fontname' => 'Ubuntu'),
						array('fontname' => 'Ultra'),
						array('fontname' => 'Unkempt'),
						//array('fontname' => 'UnifrakturCook:bold'),
						array('fontname' => 'UnifrakturMaguntia'),
						array('fontname' => 'Varela'),
						array('fontname' => 'Varela Round'),
						array('fontname' => 'Vibur'),
						array('fontname' => 'Vollkorn'),
						array('fontname' => 'VT323'),
						array('fontname' => 'Waiting+for+the+Sunrise'),
						array('fontname' => 'Wallpoet'),
						array('fontname' => 'Walter+Turncoat'),
						array('fontname' => 'Wire+One'),
						array('fontname' => 'Yanone+Kaffeesatz'),
						//array('fontname' => 'Yanone+Kaffeesatz:300'),
						//array('fontname' => 'Yanone+Kaffeesatz:400'),
						//array('fontname' => 'Yanone+Kaffeesatz:700'),
						array('fontname' => 'Yeseva+One'),
						array('fontname' => 'Zeyada')
					);
				}
				break;
				case 'icons': {
					$data['list'] = array(
						array('name' => 'fa-glass'),
						array('name' => 'fa-music'),
						array('name' => 'fa-search'),
						array('name' => 'fa-envelope-o'),
						array('name' => 'fa-heart'),
						array('name' => 'fa-star'),
						array('name' => 'fa-star-o'),
						array('name' => 'fa-user'),
						array('name' => 'fa-film'),
						array('name' => 'fa-th-large'),
						array('name' => 'fa-th'),
						array('name' => 'fa-th-list'),
						array('name' => 'fa-check'),
						array('name' => 'fa-times'),
						array('name' => 'fa-search-plus'),
						array('name' => 'fa-search-minus'),
						array('name' => 'fa-power-off'),
						array('name' => 'fa-signal'),
						array('name' => 'fa-cog'),
						array('name' => 'fa-trash-o'),
						array('name' => 'fa-home'),
						array('name' => 'fa-file-o'),
						array('name' => 'fa-clock-o'),
						array('name' => 'fa-road'),
						array('name' => 'fa-download'),
						array('name' => 'fa-arrow-circle-o-down'),
						array('name' => 'fa-arrow-circle-o-up'),
						array('name' => 'fa-inbox'),
						array('name' => 'fa-play-circle-o'),
						array('name' => 'fa-repeat'),
						array('name' => 'fa-refresh'),
						array('name' => 'fa-list-alt'),
						array('name' => 'fa-lock'),
						array('name' => 'fa-flag'),
						array('name' => 'fa-headphones'),
						array('name' => 'fa-volume-off'),
						array('name' => 'fa-volume-down'),
						array('name' => 'fa-volume-up'),
						array('name' => 'fa-qrcode'),
						array('name' => 'fa-barcode'),
						array('name' => 'fa-tag'),
						array('name' => 'fa-tags'),
						array('name' => 'fa-book'),
						array('name' => 'fa-bookmark'),
						array('name' => 'fa-print'),
						array('name' => 'fa-camera'),
						array('name' => 'fa-font'),
						array('name' => 'fa-bold'),
						array('name' => 'fa-italic'),
						array('name' => 'fa-text-height'),
						array('name' => 'fa-text-width'),
						array('name' => 'fa-align-left'),
						array('name' => 'fa-align-center'),
						array('name' => 'fa-align-right'),
						array('name' => 'fa-align-justify'),
						array('name' => 'fa-list'),
						array('name' => 'fa-dedent'),
						array('name' => 'fa-outdent'),
						array('name' => 'fa-indent'),
						array('name' => 'fa-video-camera'),
						array('name' => 'fa-image'),
						array('name' => 'fa-pencil'),
						array('name' => 'fa-map-marker'),
						array('name' => 'fa-adjust'),
						array('name' => 'fa-tint'),
						array('name' => 'fa-edit'),
						array('name' => 'fa-share-square-o'),
						array('name' => 'fa-check-square-o'),
						array('name' => 'fa-arrows'),
						array('name' => 'fa-step-backward'),
						array('name' => 'fa-fast-backward'),
						array('name' => 'fa-backward'),
						array('name' => 'fa-play'),
						array('name' => 'fa-pause'),
						array('name' => 'fa-stop'),
						array('name' => 'fa-forward'),
						array('name' => 'fa-fast-forward'),
						array('name' => 'fa-step-forward'),
						array('name' => 'fa-eject'),
						array('name' => 'fa-chevron-left'),
						array('name' => 'fa-chevron-right'),
						array('name' => 'fa-plus-circle'),
						array('name' => 'fa-minus-circle'),
						array('name' => 'fa-times-circle'),
						array('name' => 'fa-check-circle'),
						array('name' => 'fa-question-circle'),
						array('name' => 'fa-info-circle'),
						array('name' => 'fa-crosshairs'),
						array('name' => 'fa-times-circle-o'),
						array('name' => 'fa-check-circle-o'),
						array('name' => 'fa-ban'),
						array('name' => 'fa-arrow-left'),
						array('name' => 'fa-arrow-right'),
						array('name' => 'fa-arrow-up'),
						array('name' => 'fa-arrow-down'),
						array('name' => 'fa-share'),
						array('name' => 'fa-expand'),
						array('name' => 'fa-compress'),
						array('name' => 'fa-plus'),
						array('name' => 'fa-minus'),
						array('name' => 'fa-asterisk'),
						array('name' => 'fa-exclamation-circle'),
						array('name' => 'fa-gift'),
						array('name' => 'fa-leaf'),
						array('name' => 'fa-fire'),
						array('name' => 'fa-eye'),
						array('name' => 'fa-eye-slash'),
						array('name' => 'fa-warning'),
						array('name' => 'fa-plane'),
						array('name' => 'fa-calendar'),
						array('name' => 'fa-random'),
						array('name' => 'fa-comment'),
						array('name' => 'fa-magnet'),
						array('name' => 'fa-chevron-up'),
						array('name' => 'fa-chevron-down'),
						array('name' => 'fa-retweet'),
						array('name' => 'fa-shopping-cart'),
						array('name' => 'fa-folder'),
						array('name' => 'fa-folder-open'),
						array('name' => 'fa-arrows-v'),
						array('name' => 'fa-arrows-h'),
						array('name' => 'fa-bar-chart'),
						array('name' => 'fa-twitter-square'),
						array('name' => 'fa-facebook-square'),
						array('name' => 'fa-camera-retro'),
						array('name' => 'fa-key'),
						array('name' => 'fa-cogs'),
						array('name' => 'fa-comments'),
						array('name' => 'fa-thumbs-o-up'),
						array('name' => 'fa-thumbs-o-down'),
						array('name' => 'fa-star-half'),
						array('name' => 'fa-heart-o'),
						array('name' => 'fa-sign-out'),
						array('name' => 'fa-linkedin-square'),
						array('name' => 'fa-thumb-tack'),
						array('name' => 'fa-external-link'),
						array('name' => 'fa-sign-in'),
						array('name' => 'fa-trophy'),
						array('name' => 'fa-github-square'),
						array('name' => 'fa-upload'),
						array('name' => 'fa-lemon-o'),
						array('name' => 'fa-phone'),
						array('name' => 'fa-square-o'),
						array('name' => 'fa-bookmark-o'),
						array('name' => 'fa-phone-square'),
						array('name' => 'fa-twitter'),
						array('name' => 'fa-github'),
						array('name' => 'fa-unlock'),
						array('name' => 'fa-credit-card'),
						array('name' => 'fa-feed'),
						array('name' => 'fa-hdd-o'),
						array('name' => 'fa-bullhorn'),
						array('name' => 'fa-bell'),
						array('name' => 'fa-certificate'),
						array('name' => 'fa-hand-o-right'),
						array('name' => 'fa-hand-o-left'),
						array('name' => 'fa-hand-o-up'),
						array('name' => 'fa-hand-o-down'),
						array('name' => 'fa-arrow-circle-left'),
						array('name' => 'fa-arrow-circle-right'),
						array('name' => 'fa-arrow-circle-up'),
						array('name' => 'fa-arrow-circle-down'),
						array('name' => 'fa-globe'),
						array('name' => 'fa-wrench'),
						array('name' => 'fa-tasks'),
						array('name' => 'fa-filter'),
						array('name' => 'fa-briefcase'),
						array('name' => 'fa-arrows-alt'),
						array('name' => 'fa-users'),
						array('name' => 'fa-link'),
						array('name' => 'fa-cloud'),
						array('name' => 'fa-flask'),
						array('name' => 'fa-cut'),
						array('name' => 'fa-copy'),
						array('name' => 'fa-paperclip'),
						array('name' => 'fa-save'),
						array('name' => 'fa-square'),
						array('name' => 'fa-bars'),
						array('name' => 'fa-list-ul'),
						array('name' => 'fa-list-ol'),
						array('name' => 'fa-strikethrough'),
						array('name' => 'fa-underline'),
						array('name' => 'fa-table'),
						array('name' => 'fa-magic'),
						array('name' => 'fa-truck'),
						array('name' => 'fa-pinterest'),
						array('name' => 'fa-pinterest-square'),
						array('name' => 'fa-google-plus-square'),
						array('name' => 'fa-google-plus'),
						array('name' => 'fa-money'),
						array('name' => 'fa-caret-down'),
						array('name' => 'fa-caret-up'),
						array('name' => 'fa-caret-left'),
						array('name' => 'fa-caret-right'),
						array('name' => 'fa-columns'),
						array('name' => 'fa-sort'),
						array('name' => 'fa-sort-desc'),
						array('name' => 'fa-sort-asc'),
						array('name' => 'fa-envelope'),
						array('name' => 'fa-linkedin'),
						array('name' => 'fa-undo'),
						array('name' => 'fa-legal'),
						array('name' => 'fa-dashboard'),
						array('name' => 'fa-comment-o'),
						array('name' => 'fa-comments-o'),
						array('name' => 'fa-flash'),
						array('name' => 'fa-sitemap'),
						array('name' => 'fa-umbrella'),
						array('name' => 'fa-paste'),
						array('name' => 'fa-lightbulb-o'),
						array('name' => 'fa-exchange'),
						array('name' => 'fa-cloud-download'),
						array('name' => 'fa-cloud-upload'),
						array('name' => 'fa-user-md'),
						array('name' => 'fa-stethoscope'),
						array('name' => 'fa-suitcase'),
						array('name' => 'fa-bell-o'),
						array('name' => 'fa-coffee'),
						array('name' => 'fa-cutlery'),
						array('name' => 'fa-file-text-o'),
						array('name' => 'fa-building-o'),
						array('name' => 'fa-hospital-o'),
						array('name' => 'fa-ambulance'),
						array('name' => 'fa-medkit'),
						array('name' => 'fa-fighter-jet'),
						array('name' => 'fa-beer'),
						array('name' => 'fa-h-square'),
						array('name' => 'fa-plus-square'),
						array('name' => 'fa-angle-double-left'),
						array('name' => 'fa-angle-double-right'),
						array('name' => 'fa-angle-double-up'),
						array('name' => 'fa-angle-double-down'),
						array('name' => 'fa-angle-left'),
						array('name' => 'fa-angle-right'),
						array('name' => 'fa-angle-up'),
						array('name' => 'fa-angle-down'),
						array('name' => 'fa-desktop'),
						array('name' => 'fa-laptop'),
						array('name' => 'fa-tablet'),
						array('name' => 'fa-mobile'),
						array('name' => 'fa-circle-o'),
						array('name' => 'fa-quote-left'),
						array('name' => 'fa-quote-right'),
						array('name' => 'fa-spinner'),
						array('name' => 'fa-circle'),
						array('name' => 'fa-reply'),
						array('name' => 'fa-github-alt'),
						array('name' => 'fa-folder-o'),
						array('name' => 'fa-folder-open-o'),
						array('name' => 'fa-smile-o'),
						array('name' => 'fa-frown-o'),
						array('name' => 'fa-meh-o'),
						array('name' => 'fa-gamepad'),
						array('name' => 'fa-keyboard-o'),
						array('name' => 'fa-flag-o'),
						array('name' => 'fa-flag-checkered'),
						array('name' => 'fa-terminal'),
						array('name' => 'fa-code'),
						array('name' => 'fa-reply-all'),
						array('name' => 'fa-star-half-o'),
						array('name' => 'fa-location-arrow'),
						array('name' => 'fa-crop'),
						array('name' => 'fa-code-fork'),
						array('name' => 'fa-unlink'),
						array('name' => 'fa-question'),
						array('name' => 'fa-info'),
						array('name' => 'fa-exclamation'),
						array('name' => 'fa-superscript'),
						array('name' => 'fa-subscript'),
						array('name' => 'fa-eraser'),
						array('name' => 'fa-puzzle-piece'),
						array('name' => 'fa-microphone'),
						array('name' => 'fa-microphone-slash'),
						array('name' => 'fa-shield'),
						array('name' => 'fa-calendar-o'),
						array('name' => 'fa-fire-extinguisher'),
						array('name' => 'fa-rocket'),
						array('name' => 'fa-maxcdn'),
						array('name' => 'fa-chevron-circle-left'),
						array('name' => 'fa-chevron-circle-right'),
						array('name' => 'fa-chevron-circle-up'),
						array('name' => 'fa-chevron-circle-down'),
						array('name' => 'fa-html5'),
						array('name' => 'fa-css3'),
						array('name' => 'fa-anchor'),
						array('name' => 'fa-unlock-alt'),
						array('name' => 'fa-bullseye'),
						array('name' => 'fa-ellipsis-h'),
						array('name' => 'fa-ellipsis-v'),
						array('name' => 'fa-rss-square'),
						array('name' => 'fa-play-circle'),
						array('name' => 'fa-ticket'),
						array('name' => 'fa-minus-square'),
						array('name' => 'fa-minus-square-o'),
						array('name' => 'fa-level-up'),
						array('name' => 'fa-level-down'),
						array('name' => 'fa-check-square'),
						array('name' => 'fa-pencil-square'),
						array('name' => 'fa-external-link-square'),
						array('name' => 'fa-share-square'),
						array('name' => 'fa-compass'),
						array('name' => 'fa-toggle-down'),
						array('name' => 'fa-toggle-up'),
						array('name' => 'fa-toggle-right'),
						array('name' => 'fa-euro'),
						array('name' => 'fa-gbp'),
						array('name' => 'fa-dollar'),
						array('name' => 'fa-rupee'),
						array('name' => 'fa-yen'),
						array('name' => 'fa-rub'),
						array('name' => 'fa-won'),
						array('name' => 'fa-bitcoin'),
						array('name' => 'fa-file'),
						array('name' => 'fa-file-text'),
						array('name' => 'fa-sort-alpha-asc'),
						array('name' => 'fa-sort-alpha-desc'),
						array('name' => 'fa-sort-amount-asc'),
						array('name' => 'fa-sort-amount-desc'),
						array('name' => 'fa-sort-numeric-asc'),
						array('name' => 'fa-sort-numeric-desc'),
						array('name' => 'fa-thumbs-up'),
						array('name' => 'fa-thumbs-down'),
						array('name' => 'fa-youtube-square'),
						array('name' => 'fa-youtube'),
						array('name' => 'fa-xing'),
						array('name' => 'fa-xing-square'),
						array('name' => 'fa-youtube-play'),
						array('name' => 'fa-dropbox'),
						array('name' => 'fa-stack-overflow'),
						array('name' => 'fa-instagram'),
						array('name' => 'fa-flickr'),
						array('name' => 'fa-adn'),
						array('name' => 'fa-bitbucket'),
						array('name' => 'fa-bitbucket-square'),
						array('name' => 'fa-tumblr'),
						array('name' => 'fa-tumblr-square'),
						array('name' => 'fa-long-arrow-down'),
						array('name' => 'fa-long-arrow-up'),
						array('name' => 'fa-long-arrow-left'),
						array('name' => 'fa-long-arrow-right'),
						array('name' => 'fa-apple'),
						array('name' => 'fa-windows'),
						array('name' => 'fa-android'),
						array('name' => 'fa-linux'),
						array('name' => 'fa-dribbble'),
						array('name' => 'fa-skype'),
						array('name' => 'fa-foursquare'),
						array('name' => 'fa-trello'),
						array('name' => 'fa-female'),
						array('name' => 'fa-male'),
						array('name' => 'fa-gratipay'),
						array('name' => 'fa-sun-o'),
						array('name' => 'fa-moon-o'),
						array('name' => 'fa-archive'),
						array('name' => 'fa-bug'),
						array('name' => 'fa-vk'),
						array('name' => 'fa-weibo'),
						array('name' => 'fa-renren'),
						array('name' => 'fa-pagelines'),
						array('name' => 'fa-stack-exchange'),
						array('name' => 'fa-arrow-circle-o-right'),
						array('name' => 'fa-arrow-circle-o-left'),
						array('name' => 'fa-toggle-left'),
						array('name' => 'fa-dot-circle-o'),
						array('name' => 'fa-wheelchair'),
						array('name' => 'fa-vimeo-square'),
						array('name' => 'fa-try'),
						array('name' => 'fa-plus-square-o'),
						array('name' => 'fa-space-shuttle'),
						array('name' => 'fa-slack'),
						array('name' => 'fa-envelope-square'),
						array('name' => 'fa-wordpress'),
						array('name' => 'fa-openid'),
						array('name' => 'fa-bank'),
						array('name' => 'fa-mortar-board'),
						array('name' => 'fa-yahoo'),
						array('name' => 'fa-google'),
						array('name' => 'fa-reddit'),
						array('name' => 'fa-reddit-square'),
						array('name' => 'fa-stumbleupon-circle'),
						array('name' => 'fa-stumbleupon'),
						array('name' => 'fa-delicious'),
						array('name' => 'fa-digg'),
						array('name' => 'fa-pied-piper-pp'),
						array('name' => 'fa-pied-piper-alt'),
						array('name' => 'fa-drupal'),
						array('name' => 'fa-joomla'),
						array('name' => 'fa-language'),
						array('name' => 'fa-fax'),
						array('name' => 'fa-building'),
						array('name' => 'fa-child'),
						array('name' => 'fa-paw'),
						array('name' => 'fa-spoon'),
						array('name' => 'fa-cube'),
						array('name' => 'fa-cubes'),
						array('name' => 'fa-behance'),
						array('name' => 'fa-behance-square'),
						array('name' => 'fa-steam'),
						array('name' => 'fa-steam-square'),
						array('name' => 'fa-recycle'),
						array('name' => 'fa-car'),
						array('name' => 'fa-taxi'),
						array('name' => 'fa-tree'),
						array('name' => 'fa-spotify'),
						array('name' => 'fa-deviantart'),
						array('name' => 'fa-soundcloud'),
						array('name' => 'fa-database'),
						array('name' => 'fa-file-pdf-o'),
						array('name' => 'fa-file-word-o'),
						array('name' => 'fa-file-excel-o'),
						array('name' => 'fa-file-powerpoint-o'),
						array('name' => 'fa-file-image-o'),
						array('name' => 'fa-file-zip-o'),
						array('name' => 'fa-file-sound-o'),
						array('name' => 'fa-file-video-o'),
						array('name' => 'fa-file-code-o'),
						array('name' => 'fa-vine'),
						array('name' => 'fa-codepen'),
						array('name' => 'fa-jsfiddle'),
						array('name' => 'fa-support'),
						array('name' => 'fa-circle-o-notch'),
						array('name' => 'fa-resistance'),
						array('name' => 'fa-empire'),
						array('name' => 'fa-git-square'),
						array('name' => 'fa-git'),
						array('name' => 'fa-hacker-news'),
						array('name' => 'fa-tencent-weibo'),
						array('name' => 'fa-qq'),
						array('name' => 'fa-wechat'),
						array('name' => 'fa-send'),
						array('name' => 'fa-send-o'),
						array('name' => 'fa-history'),
						array('name' => 'fa-circle-thin'),
						array('name' => 'fa-header'),
						array('name' => 'fa-paragraph'),
						array('name' => 'fa-sliders'),
						array('name' => 'fa-share-alt'),
						array('name' => 'fa-share-alt-square'),
						array('name' => 'fa-bomb'),
						array('name' => 'fa-soccer-ball-o'),
						array('name' => 'fa-tty'),
						array('name' => 'fa-binoculars'),
						array('name' => 'fa-plug'),
						array('name' => 'fa-slideshare'),
						array('name' => 'fa-twitch'),
						array('name' => 'fa-yelp'),
						array('name' => 'fa-newspaper-o'),
						array('name' => 'fa-wifi'),
						array('name' => 'fa-calculator'),
						array('name' => 'fa-paypal'),
						array('name' => 'fa-google-wallet'),
						array('name' => 'fa-cc-visa'),
						array('name' => 'fa-cc-mastercard'),
						array('name' => 'fa-cc-discover'),
						array('name' => 'fa-cc-amex'),
						array('name' => 'fa-cc-paypal'),
						array('name' => 'fa-cc-stripe'),
						array('name' => 'fa-bell-slash'),
						array('name' => 'fa-bell-slash-o'),
						array('name' => 'fa-trash'),
						array('name' => 'fa-copyright'),
						array('name' => 'fa-at'),
						array('name' => 'fa-eyedropper'),
						array('name' => 'fa-paint-brush'),
						array('name' => 'fa-birthday-cake'),
						array('name' => 'fa-area-chart'),
						array('name' => 'fa-pie-chart'),
						array('name' => 'fa-line-chart'),
						array('name' => 'fa-lastfm'),
						array('name' => 'fa-lastfm-square'),
						array('name' => 'fa-toggle-off'),
						array('name' => 'fa-toggle-on'),
						array('name' => 'fa-bicycle'),
						array('name' => 'fa-bus'),
						array('name' => 'fa-ioxhost'),
						array('name' => 'fa-angellist'),
						array('name' => 'fa-cc'),
						array('name' => 'fa-shekel'),
						array('name' => 'fa-meanpath'),
						array('name' => 'fa-buysellads'),
						array('name' => 'fa-connectdevelop'),
						array('name' => 'fa-dashcube'),
						array('name' => 'fa-forumbee'),
						array('name' => 'fa-leanpub'),
						array('name' => 'fa-sellsy'),
						array('name' => 'fa-shirtsinbulk'),
						array('name' => 'fa-simplybuilt'),
						array('name' => 'fa-skyatlas'),
						array('name' => 'fa-cart-plus'),
						array('name' => 'fa-cart-arrow-down'),
						array('name' => 'fa-diamond'),
						array('name' => 'fa-ship'),
						array('name' => 'fa-user-secret'),
						array('name' => 'fa-motorcycle'),
						array('name' => 'fa-street-view'),
						array('name' => 'fa-heartbeat'),
						array('name' => 'fa-venus'),
						array('name' => 'fa-mars'),
						array('name' => 'fa-mercury'),
						array('name' => 'fa-transgender'),
						array('name' => 'fa-transgender-alt'),
						array('name' => 'fa-venus-double'),
						array('name' => 'fa-mars-double'),
						array('name' => 'fa-venus-mars'),
						array('name' => 'fa-mars-stroke'),
						array('name' => 'fa-mars-stroke-v'),
						array('name' => 'fa-mars-stroke-h'),
						array('name' => 'fa-neuter'),
						array('name' => 'fa-genderless'),
						array('name' => 'fa-facebook-official'),
						array('name' => 'fa-pinterest-p'),
						array('name' => 'fa-whatsapp'),
						array('name' => 'fa-server'),
						array('name' => 'fa-user-plus'),
						array('name' => 'fa-user-times'),
						array('name' => 'fa-bed'),
						array('name' => 'fa-viacoin'),
						array('name' => 'fa-train'),
						array('name' => 'fa-subway'),
						array('name' => 'fa-medium'),
						array('name' => 'fa-y-combinator'),
						array('name' => 'fa-optin-monster'),
						array('name' => 'fa-opencart'),
						array('name' => 'fa-expeditedssl'),
						array('name' => 'fa-battery-4'),
						array('name' => 'fa-battery-3'),
						array('name' => 'fa-battery-2'),
						array('name' => 'fa-battery-1'),
						array('name' => 'fa-battery-0'),
						array('name' => 'fa-mouse-pointer'),
						array('name' => 'fa-i-cursor'),
						array('name' => 'fa-object-group'),
						array('name' => 'fa-object-ungroup'),
						array('name' => 'fa-sticky-note'),
						array('name' => 'fa-sticky-note-o'),
						array('name' => 'fa-cc-jcb'),
						array('name' => 'fa-cc-diners-club'),
						array('name' => 'fa-clone'),
						array('name' => 'fa-balance-scale'),
						array('name' => 'fa-hourglass-o'),
						array('name' => 'fa-hourglass-1'),
						array('name' => 'fa-hourglass-2'),
						array('name' => 'fa-hourglass-3'),
						array('name' => 'fa-hourglass'),
						array('name' => 'fa-hand-grab-o'),
						array('name' => 'fa-hand-stop-o'),
						array('name' => 'fa-hand-scissors-o'),
						array('name' => 'fa-hand-lizard-o'),
						array('name' => 'fa-hand-spock-o'),
						array('name' => 'fa-hand-pointer-o'),
						array('name' => 'fa-hand-peace-o'),
						array('name' => 'fa-trademark'),
						array('name' => 'fa-registered'),
						array('name' => 'fa-creative-commons'),
						array('name' => 'fa-gg'),
						array('name' => 'fa-gg-circle'),
						array('name' => 'fa-tripadvisor'),
						array('name' => 'fa-odnoklassniki'),
						array('name' => 'fa-odnoklassniki-square'),
						array('name' => 'fa-get-pocket'),
						array('name' => 'fa-wikipedia-w'),
						array('name' => 'fa-safari'),
						array('name' => 'fa-chrome'),
						array('name' => 'fa-firefox'),
						array('name' => 'fa-opera'),
						array('name' => 'fa-internet-explorer'),
						array('name' => 'fa-tv'),
						array('name' => 'fa-contao'),
						array('name' => 'fa-500px'),
						array('name' => 'fa-amazon'),
						array('name' => 'fa-calendar-plus-o'),
						array('name' => 'fa-calendar-minus-o'),
						array('name' => 'fa-calendar-times-o'),
						array('name' => 'fa-calendar-check-o'),
						array('name' => 'fa-industry'),
						array('name' => 'fa-map-pin'),
						array('name' => 'fa-map-signs'),
						array('name' => 'fa-map-o'),
						array('name' => 'fa-map'),
						array('name' => 'fa-commenting'),
						array('name' => 'fa-commenting-o'),
						array('name' => 'fa-houzz'),
						array('name' => 'fa-vimeo'),
						array('name' => 'fa-black-tie'),
						array('name' => 'fa-fonticons'),
						array('name' => 'fa-reddit-alien'),
						array('name' => 'fa-edge'),
						array('name' => 'fa-credit-card-alt'),
						array('name' => 'fa-codiepie'),
						array('name' => 'fa-modx'),
						array('name' => 'fa-fort-awesome'),
						array('name' => 'fa-usb'),
						array('name' => 'fa-product-hunt'),
						array('name' => 'fa-mixcloud'),
						array('name' => 'fa-scribd'),
						array('name' => 'fa-pause-circle'),
						array('name' => 'fa-pause-circle-o'),
						array('name' => 'fa-stop-circle'),
						array('name' => 'fa-stop-circle-o'),
						array('name' => 'fa-shopping-bag'),
						array('name' => 'fa-shopping-basket'),
						array('name' => 'fa-hashtag'),
						array('name' => 'fa-bluetooth'),
						array('name' => 'fa-bluetooth-b'),
						array('name' => 'fa-percent'),
						array('name' => 'fa-gitlab'),
						array('name' => 'fa-wpbeginner'),
						array('name' => 'fa-wpforms'),
						array('name' => 'fa-envira'),
						array('name' => 'fa-universal-access'),
						array('name' => 'fa-wheelchair-alt'),
						array('name' => 'fa-question-circle-o'),
						array('name' => 'fa-blind'),
						array('name' => 'fa-audio-description'),
						array('name' => 'fa-volume-control-phone'),
						array('name' => 'fa-braille'),
						array('name' => 'fa-assistive-listening-systems'),
						array('name' => 'fa-asl-interpreting'),
						array('name' => 'fa-deaf'),
						array('name' => 'fa-glide'),
						array('name' => 'fa-glide-g'),
						array('name' => 'fa-signing'),
						array('name' => 'fa-low-vision'),
						array('name' => 'fa-viadeo'),
						array('name' => 'fa-viadeo-square'),
						array('name' => 'fa-snapchat'),
						array('name' => 'fa-snapchat-ghost'),
						array('name' => 'fa-snapchat-square'),
						array('name' => 'fa-pied-piper'),
						array('name' => 'fa-first-order'),
						array('name' => 'fa-yoast'),
						array('name' => 'fa-themeisle'),
						array('name' => 'fa-google-plus-circle'),
						array('name' => 'fa-font-awesome'),
						array('name' => 'fa-handshake-o'),
						array('name' => 'fa-envelope-open'),
						array('name' => 'fa-envelope-open-o'),
						array('name' => 'fa-linode'),
						array('name' => 'fa-address-book'),
						array('name' => 'fa-address-book-o'),
						array('name' => 'fa-address-card'),
						array('name' => 'fa-address-card-o'),
						array('name' => 'fa-user-circle'),
						array('name' => 'fa-user-circle-o'),
						array('name' => 'fa-user-o'),
						array('name' => 'fa-id-badge'),
						array('name' => 'fa-id-card'),
						array('name' => 'fa-id-card-o'),
						array('name' => 'fa-quora'),
						array('name' => 'fa-free-code-camp'),
						array('name' => 'fa-telegram'),
						array('name' => 'fa-thermometer-4'),
						array('name' => 'fa-thermometer-3'),
						array('name' => 'fa-thermometer-2'),
						array('name' => 'fa-thermometer-1'),
						array('name' => 'fa-thermometer-0'),
						array('name' => 'fa-shower'),
						array('name' => 'fa-bath'),
						array('name' => 'fa-podcast'),
						array('name' => 'fa-window-maximize'),
						array('name' => 'fa-window-minimize'),
						array('name' => 'fa-window-restore'),
						array('name' => 'fa-window-close'),
						array('name' => 'fa-times-rectangle-o'),
						array('name' => 'fa-window-close-o'),
						array('name' => 'fa-bandcamp'),
						array('name' => 'fa-grav'),
						array('name' => 'fa-etsy'),
						array('name' => 'fa-imdb'),
						array('name' => 'fa-ravelry'),
						array('name' => 'fa-eercast'),
						array('name' => 'fa-microchip'),
						array('name' => 'fa-snowflake-o'),
						array('name' => 'fa-superpowers'),
						array('name' => 'fa-wpexplorer'),
						array('name' => 'fa-meetup')
					);
				} break;
				case 'import-items' : {
					$data['list'] = array();
					
					global $wpdb;
					$table = $wpdb->prefix . 'posts';
                    $sql = $wpdb->prepare("SELECT ID, post_title, post_modified FROM {$table} WHERE post_type='imgl_item'");
					foreach($wpdb->get_results($sql) as $key => $item) {
						array_push($data['list'], array('id' => $item->ID, 'title' => $item->post_title, 'modified' => (mysql2date('d M Y H:i:s', $item->post_modified))));
					}
				} break;
				case 'import-items-count' : {
					global $wpdb;
					$table = $wpdb->prefix . 'posts';
                    $sql = $wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE post_type='imgl_item'");
					$count = (int)$wpdb->get_var($sql);
					$data['count'] = ($count ? $count : 0);
				} break;
				case 'import-item' : {
					$id = sanitize_key(filter_input(INPUT_POST, 'id', FILTER_DEFAULT));
					$data['item'] = unserialize(get_post_meta( $id, 'imgl-meta-imagelinks-cfg', true));
				} break;
				default: {
					$error = true;
					$data['msg'] = esc_html__('The operation failed', 'imagelinks');
				}
				break;
			}
		} else {
			$error = true;
			$data['msg'] = esc_html__('The operation failed', 'imagelinks');
		}
		
		if($error) {
			wp_send_json_error($data);
		} else {
			wp_send_json_success($data);
		}
		
		wp_die(); // this is required to terminate immediately and return a proper response
	}
	
	/**
	 * Ajax delete all data from tables
	 */
	function ajax_delete_data() {
		$error = true;
		$data = array();
		$data['msg'] = esc_html__('The operation failed, can\'t delete data', 'imagelinks');
		
		if(check_ajax_referer('imagelinks_ajax', 'nonce', false)) {
			global $wpdb;
			$table = $wpdb->prefix . IMAGELINKS_PLUGIN_NAME;
			
			foreach($wpdb->get_results($wpdb->prepare("SELECT id FROM {$table}")) as $key=>$item) {
				// [filemanager] delete file
				if(wp_is_writable(IMAGELINKS_PLUGIN_UPLOAD_DIR)) {
					$file_json = 'config.json';
					$file_main_css = 'main.css';
					$file_custom_css = 'custom.css';
					$file_root_path = IMAGELINKS_PLUGIN_UPLOAD_DIR . '/' . $item->id . '/';
					
					wp_delete_file($file_root_path . $file_json);
					wp_delete_file($file_root_path . $file_main_css);
					wp_delete_file($file_root_path . $file_custom_css);
					
					if(is_dir($file_root_path)) {
						rmdir($file_root_path);
					}
				}
			}
			
			//======================================
			// [filemanager] delete templates
			if(wp_is_writable(IMAGELINKS_PLUGIN_UPLOAD_DIR)) {
				$file_root_path = IMAGELINKS_PLUGIN_UPLOAD_DIR . '/templates/';
				
				$files = glob($file_root_path . '/*'); // get all file names
				foreach($files as $file){ // iterate files
					if(is_file($file)) {
						unlink($file); // delete file
					}
				}
				
				if(is_dir($file_root_path)) {
					rmdir($file_root_path);
				}
			}
			//======================================
			
			//======================================
			// delete old version data
			// ???
			//======================================
			
			$query = 'TRUNCATE TABLE ' . $table;
			$result = $wpdb->query($query);
			
			if($result) {
				$error = false;
				$data['msg'] = esc_html__('All data deleted', 'imagelinks');
			}
		}
		
		if($error) {
			wp_send_json_error($data);
		} else {
			wp_send_json_success($data);
		}
		
		wp_die(); // this is required to terminate immediately and return a proper response
	}
	
	/**
	 * Ajax import all data from old version
	 */
	function ajax_import_data() {
		$error = true;
		$data = array();
		$data['msg'] = esc_html__('The operation failed, can\'t import data', 'imagelinks');
		
		if(check_ajax_referer('imagelinks_ajax', 'nonce', false)) {
			global $wpdb;
			$tablePosts = $wpdb->prefix . 'posts';
			
			$data['list'] = array();
			foreach($wpdb->get_results($wpdb->prepare("SELECT ID, post_title FROM {$tablePosts} WHERE post_type='imgl_item'")) as $key => $item) {
				$json = unserialize(get_post_meta($item->ID, 'imgl-meta-imagelinks-cfg', true));
				array_push($data['list'], array('id' => $item->ID, 'title' => $item->post_title, 'cfg' => $json));
			}
			
			$error = false;
			$data['msg'] = esc_html__('All data imported', 'imagelinks');
		}
		
		if($error) {
			wp_send_json_error($data);
		} else {
			wp_send_json_success($data);
		}
		
		wp_die(); // this is required to terminate immediately and return a proper response
	}
	
	/**
	 * Ajax settings get data
	 */
	function ajax_modal() {
		if(check_ajax_referer('imagelinks_ajax', 'nonce', false)) {
			$modalName = sanitize_key(filter_input(INPUT_GET, 'name', FILTER_DEFAULT ));
			$modalPath = plugin_dir_path( dirname(__FILE__) ) . 'includes/modal-' . $modalName . '.php';
			
			if(file_exists($modalPath)) {
				require_once( $modalPath );
			}
		}
		wp_die(); // this is required to terminate immediately and return a proper response
	}
}
?>