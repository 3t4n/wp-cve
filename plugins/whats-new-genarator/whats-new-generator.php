<?php
/*
 Plugin Name: What's New Generator
Plugin URI: http://residentbird.main.jp/bizplugin/
Description: What's New(新着情報)を指定した固定ページや投稿に自動的に表示するプラグインです。
Version: 2.0.2
Author:Hideki Tanaka
Author URI: http://residentbird.main.jp/bizplugin/
Text Domain: whats-new-genarator
*/

include_once( dirname(__FILE__) . "/admin-ui.php" );
new WhatsNewPlugin();

class WNG
{
	const VERSION = "2.0.2";
	const SHORTCODE = "showwhatsnew";
	const OPTIONS = "whats_new_options";

	public static function get_option(){
		return get_option(self::OPTIONS);
	}

	public static function update_option( $options ){
		if (empty($options)){
			return;
		}
		update_option(self::OPTIONS, $options);
	}

	public static function enqueue_css_js(){
		wp_enqueue_style( 'whats-new-style', plugins_url('whats-new.css', __FILE__ ), array(), self::VERSION );
	}

	public static function enqueue_admin_css_js(){
		wp_enqueue_style( 'whats-new-style', plugins_url('whats-new.css', __FILE__ ), array(), self::VERSION);
	}
}

class WhatsNewPlugin{

	var $adminUi;

	public function __construct(){
		register_activation_hook(__FILE__, array(&$this,'on_activation'));
		add_action( 'admin_init', array(&$this,'on_admin_init') );
		add_action( 'admin_menu', array(&$this, 'on_admin_menu'));
		add_action( 'wp_enqueue_scripts', array(&$this,'on_enqueue_css_js'));
		add_shortcode( WNG::SHORTCODE, array(&$this,'show_shortcode'));
		add_filter( 'widget_text', 'do_shortcode');
	}

	function on_activation() {
		$options = WNG::get_option();
		if (empty($options)){
			$options = array();
		}
		$options['wng_title'] =  isset($options['wng_title']) ? $options['wng_title'] : "新着情報";
		$options['wng_content_type'] =  isset($options['wng_content_type']) ? $options['wng_content_type'] : "投稿";
		$options['wng_orderby'] =  isset($options['wng_orderby']) ? $options['wng_orderby'] : "公開日順";
		$options['wng_category_name'] =  isset($options['wng_category_name']) ? $options['wng_category_name'] : "";
		$options['wng_title_tag'] =  isset($options['wng_title_tag']) ? $options['wng_title_tag'] : "h1";
		$options['wng_newmark'] =  isset($options['wng_newmark']) ? $options['wng_newmark'] : "7";
		$options['wng_number'] =  isset($options['wng_number']) ? $options['wng_number'] : "10";
		$options['wng_latest_new'] =  isset($options['wng_latest_new']) ? $options['wng_latest_new'] : false;
		WNG::update_option( $options );
	}

	function on_admin_init() {
		WNG::enqueue_admin_css_js();
		$this->adminUi = new WNGAdminUi(__FILE__);
	}

	public function on_admin_menu() {
		add_options_page("What's New 設定", "What's New 設定", 'administrator', __FILE__, array(&$this->adminUi, 'show_admin_page'));
	}

	function on_enqueue_css_js() {
		if ( is_admin() ){
			return;
		}
		WNG::enqueue_css_js();
	}

	function show_whatsnew(){
		$info = new WhatsNewInfo();
		include( dirname(__FILE__) . '/whatsnew-view.php');
	}

	function show_shortcode(){
		ob_start();
		$this->show_whatsnew();
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
}

class WhatsNewInfo{
	var $title;
	var $items = array();

	public function __construct(){
		$options = WNG::get_option();
		$this->title = esc_html( $options['wng_title'] );
		$this->title_tag = $options['wng_title_tag'];

		$condition = array();
		if ( $options['wng_content_type'] == '投稿'){
			$condition['post_type'] = 'post';
		}else if ( $options['wng_content_type'] == '固定ページ' ){
			$condition['post_type'] = 'page';
		}else{
			$condition['post_type'] = array('page', 'post');
		}
		$condition['numberposts'] = $options['wng_number'];
		$condition['order'] = 'desc';
		$condition['orderby'] = $options['wng_orderby'] == '公開日順' ? 'post_date' : 'modified';
		$condition['category_name'] = $options['wng_category_name'];

		$posts = get_posts( $condition );
		if ( !is_array($posts) ){
			return;
		}
		foreach($posts as $post){
			$this->items[] = new WhatsNewItem($post);
		}
	}
}

class WhatsNewItem{
	var $date;
	var $raw_date;
	var $title;
	var $url;
	var $newmark;
	private static $number = 0;

	public function __construct( $post ){
		$options = WNG::get_option();
		$orderby = $options['wng_orderby'];
		$this->raw_date = $orderby == '公開日順' ? $post->post_date : $post->post_modified;
		$this->date = date(get_option('date_format'), strtotime($this->raw_date));
		$this->title = esc_html( $post->post_title );
		$this->url = get_permalink($post->ID);
		$this->newmark = $this->is_new();
		self::$number++;
	}

	public function is_new(){
		$options = WNG::get_option();
		if ( isset($options['wng_latest_new']) && self::$number == 0){
			return true;
		}
		$term = $options['wng_newmark'];
		if ( !isset($term) || $term == 0){
			return false;
		}
		$today = date_i18n('U');
		$post_date = date('U', strtotime($this->raw_date));
		$diff = ( $today - $post_date ) / ( 24 * 60 * 60 );
		if ($term > $diff){
			return true;
		}
		return false;
	}
}