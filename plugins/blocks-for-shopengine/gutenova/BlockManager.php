<?php

namespace Wpmet\Gutenova;

defined('ABSPATH') || exit;
class BlockManager {

	private $blocks = [];
	private $version = '1.0.0';
	private $textdomain = 'gutenova';
	private $block_root_path = '';
	private $block_root_url = '';
	private $current_dir = '';
	private $current_url = '';

	public function __construct() {
		include 'Helper.php';

		add_action('enqueue_block_editor_assets', [$this, 'enqueue_editor_assets']);
		add_action('enqueue_block_assets', [$this, 'enqueue_frontend_assets'], 99999);
		add_action('wp_ajax_nopriv_gutenova_store_css', [$this, 'store_css']);
		add_action('wp_ajax_gutenova_store_css', [$this, 'store_css']);
		add_filter('the_content', [$this, 'add_css']);
		add_filter( 'block_categories_all', [$this, 'custom_block_cat'] );

		$this->current_dir = plugin_dir_path(__FILE__);
		$this->current_url = plugin_dir_url(__FILE__);
	}

	public function custom_block_cat( $categories ) {
		
		$shopengine_custom_cat = [
			[
				'slug'  => 'shopengine-general',
				'title' => 'Shopengine General'
			],
			[
				'slug'  => 'shopengine-single',
				'title' => 'Shopengine Single'
			],
			[
				'slug'  => 'shopengine-cart',
				'title' => 'Shopengine Cart'
			],
			[
				'slug'  => 'shopengine-archive',
				'title' => 'Shopengine Archive'
			],
			[
				'slug'  => 'shopengine-checkout',
				'title' => 'Shopengine Checkout'
			],
			[
				'slug'  => 'shopengine-my-account',
				'title' => 'Shopengine My Account'
			],
			[
				'slug'  => 'shopengine-order',
				'title' => 'Shopengine Order'
			],
		];

		$categories = array_merge($categories, $shopengine_custom_cat);

		return $categories;
	} 

	public function set_block_root_path($block_root_path) {
		$this->block_root_path = trailingslashit($block_root_path);

		return $this;
	}

	public function set_block_root_url($block_root_url) {
		$this->block_root_url = trailingslashit($block_root_url);

		return $this;
	}

	public function set_block($block, $data = []) {
		$this->blocks[$block] = $this->generate_block_data($block, $data) ?? [];

		return $this;
	}

	public function set_version($version) {
		$this->version = $version;

		return $this;
	}

	public function set_textdomain($textdomain) {
		$this->textdomain = $textdomain;

		return $this;
	}

	public function get_block($block) {
		return ($this->blocks[$block] ?? null);
	}

	private function generate_block_data($block, $data = []) {
		
		if(isset($_REQUEST['_wpnonce']) && !empty($_REQUEST['_wpnonce']) && !wp_verify_nonce( sanitize_text_field(wp_unslash( $_REQUEST['_wpnonce']))) && isset($_GET['action']) && sanitize_text_field(wp_unslash($_GET['action'])) === 'edit'){
			wp_die(" You're Not allowed ");
		}

		$json_file = $this->block_root_path . $block . '/controls.json';
		$advanced_json_file = $this->current_dir . 'advancedOptions/controls.json';

		if(!file_exists($json_file)) {
			return null;
		}
		$attributes = file_get_contents($json_file);
		$attributes = json_decode($attributes, true);

		if(file_exists($advanced_json_file)) {
			$advanced_attributes = file_get_contents($advanced_json_file);
			$advanced_attributes = json_decode($advanced_attributes, true);

			$attributes = array_merge($attributes, $advanced_attributes);
		}

		if(is_null($attributes)) {
			return null;
		}

		$tpl_type = '';


		if(!empty($_GET['post']) && !empty($_GET['action']) && sanitize_text_field(wp_unslash($_GET['action'])) === 'edit') {
			
			$p_obj = get_post(sanitize_text_field(wp_unslash($_GET['post'])));

			if($p_obj->post_type === \ShopEngine\Core\Template_Cpt::TYPE) {

				$tpl_type = \ShopEngine\Utils\Helper::get_template_type($p_obj->ID);
			}
		}

		$conf = new \Shopengine_Gutenberg_Addon\Block_Config();

		$data['attributes']    = array_merge($attributes, ['blockId' => ['type' => 'string'], 'force_render' => ['type' => 'string']]);
		$data['name']          = $this->textdomain . '/' . $block;
		$data['server_render'] = $data['server_render'] ?? false;
		$data['key']           = str_replace(['-', '/'], '_', $data['name']);
		$data['territory']     = empty($data['territory']) ? [] : $data['territory'];
		$data['template_type'] = $tpl_type;
		$data['is_active']     = $conf->is_active($block);

		if($data['server_render'] === true) {
			$block_file = $this->block_root_path . $block . '/screen.php';
			if(!file_exists($block_file)) {
				return $data;
			}
			$block                   = new Helper($block, $data['name'], $data['key'], $this->block_root_path, $this->block_root_url);
			$data['render_callback'] = function ($settings) use ($block, $block_file) {
				ob_start();
				echo wp_kses($block->is_editor
					? '<div class="gutenova-block-rendered">'
					: '<div class="wp-block gutenova-block" id="' . ($settings['blockId'] ?? '') . '" data-type="' . $block->block_name . '">'
				, \ShopEngine\Utils\Helper::get_kses_array());
				include $block_file;

				echo($block->is_editor ? '' : '</div>');

				return ob_get_clean();
			};
		}

		return $data;
	}

	public function register_blocks() {
		$printable_js_data = [];

		foreach($this->blocks as $block) {
			if(empty($block)) {
				continue;
			}

			$printable_js_data[$block['key']] = [
				'block_name'    => $block['name'],
				'block_title'   => $block['title'],
				'icon'          => $block['icon'],
				'keywords'      => $block['keywords'],
				'category'		=> $block['category'],
				'server_render' => $block['server_render'],
				'territory' => $block['territory'],
				'template_type' => $block['template_type'],
				'is_active' => $block['is_active']
			];

			if($block['is_active']){
				register_block_type($block['name'], $block);
			}
		}

		add_action('enqueue_block_editor_assets', function () use ($printable_js_data) {
			wp_add_inline_script('gutenova-editor', '
                window.gutenova = window.gutenova ?? {};
                window.gutenova.blockData = Object.assign({}, window.gutenova.blockData, ' . wp_json_encode($printable_js_data) . ');
            ');
		});
	}

	public function get_attributes($block) {
		$block_data = $this->get_block($block);
		if(is_null($block_data)) {
			return null;
		}

		return $block_data['attributes'];
	}

	public function enqueue_editor_assets() {
		wp_enqueue_script('gutenova-editor', $this->current_url . 'dist/editor.js', [], $this->version, true);
		wp_enqueue_style('gutenova-editor', $this->current_url . 'dist/editor.css', [], $this->version);

		wp_add_inline_script('gutenova-editor', '
            window.gutenova = window.gutenova ?? {};
            window.gutenova.blockData = {};
            window.gutenova.isEditor = true;
            window.gutenova.cssApiUrl = "' . admin_url('admin-ajax.php?action=gutenova_store_css') . '";
            window.gutenova.font_link = "' . get_post_meta(get_the_ID(), 'gutenova_font_link', true) . '";
            window.gutenova.restUrl = "' . rest_url() . '";
            window.gutenova.apiVersion = "2";
            window.gutenova.postId = "' . get_the_ID() . '";
            window.gutenova.scriptVersion = "' . $this->version . '";
            window.gutenova.editorNonce = "' . wp_create_nonce("editor_nonce") . '";

        ');
	}

	public function enqueue_frontend_assets() {
		wp_enqueue_script('gutenova-frontend', $this->current_url . 'dist/frontend.js', [], $this->version, true);
		wp_add_inline_script('gutenova-frontend', '
        window.gutenova.isEditor = false;
        jQuery(document).ready(function(){
            jQuery( window ).trigger( "gutenova/frontend/init", [{ addCallbacks: window.gutenova.frontendManager.addCallbacks }] )
            
            if(window.gutenova.isEditor === false) {
                window.gutenova.frontendManager.enqueueCallBacksAllBlocks();
            }
        });
    ');
	}


	public function store_css() {

		if( ! isset($_POST['wp_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wp_nonce'])), 'editor_nonce')){
			
			return;

		}
		
		$css       = isset($_POST['css']) ? sanitize_text_field(wp_unslash($_POST['css'])) : null;
		$font_link = isset($_POST['font_link']) ? sanitize_text_field(wp_unslash($_POST['font_link'])) : null;
		$post_id   = isset($_POST['post_id']) ? sanitize_text_field(wp_unslash($_POST['post_id'])) : 0;

		update_post_meta($post_id, 'gutenova_css', $css);
		update_post_meta($post_id, 'gutenova_font_link', $font_link);


		echo wp_json_encode(['stored' => true]);
		wp_die();
	}


	public function render_css($post_id) {
		$css = get_post_meta($post_id, 'gutenova_css', true);
		if(!empty($css)) {
			return '<style id="gutenova-css-' . $post_id . '">' . $css . '</style>';
		}

		return '';
	}

	public function add_css($content) {
		$post_id = get_the_ID();
		$css     = $this->render_css($post_id);
		if(!empty($css)) {
			$content .= $css;
		}

		return $content;
	}


	private static $instance = null;

	public static function instance() {
		if(!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
