<?php
namespace Adminz\Admin;

class Adminz {
	static $name= 'Administrator Z';
	public $options_pageslug = 'tools.php';
	static $slug = 'adminz';
	public $rand;
	public $title="";	
	function __construct(){
		add_filter( 'adminz_setting_tab', [$this, 'register_tab']);
		add_action( 'admin_enqueue_scripts', [$this,'adiminz_enqueue_js'] );
		add_filter( 'plugin_action_links_' . ADMINZ_BASENAME, [$this,'add_action_links']);
		add_action( 'init', array( $this, 'add_shortcodes') );		
		add_filter( 'adminz_output_debug',[$this,'debug_output']);
		add_shortcode( 'adminz_test', [$this,'test'] );		
	}	
	static function is_woocommerce(){
		return class_exists( 'WooCommerce' );
	}
	static function is_flatsome(){
		return in_array('Flatsome', [wp_get_theme()->name, wp_get_theme()->parent_theme]);
	}
 	function register_tab($tabs) {
 		if(!$this->title) return;
 		if($this->tab_icon){ 			
 			$this->title = $this->get_icon_html($this->tab_icon).$this->title;
		}
        $tabs[$this->$slug] = array(
            'title' => $this->title,
            'slug' => self::$slug,
            'html' => apply_filters( 'adminz_tab_html', $this->tab_html()),
            'type'=> false
        );
        return $tabs;
    }
    function add_shortcodes(){
		$shortcodefiles = glob(ADMINZ_DIR.'shortcodes/function*.php');
		if(!empty($shortcodefiles)){
			foreach ($shortcodefiles as $file) {
				require_once $file;
			}
		}
	}
	function add_action_links ( $actions ) {
	   $mylinks = array(
	      '<a href="' . admin_url( $this->options_pageslug.'?page='.self::$slug ) . '">Open tools</a>',
	   );
	   $actions = array_merge( $actions, $mylinks );
	   return $actions;
	}
	static function get_adminz_slug(){
		return apply_filters( 'adminz_slug', self::$slug);
	}
	static function get_adminz_menu_title(){
		return apply_filters( 'adminz_menu_title', self::$name);
	}	
	function adiminz_enqueue_js() {
		if ( ! did_action( 'wp_enqueue_media' ) ) {wp_enqueue_media(); }
	 	wp_register_script( 'adminz_media_upload', plugin_dir_url(ADMINZ_BASENAME).'assets/js/media-uploader.js', array( 'jquery' ) );
	 	wp_enqueue_script( 'adminz_media_upload');
	}
	static function get_support_icons(){
		$files = array_map('basename',glob(ADMINZ_DIR.'/assets/icons/*.svg'));		
		return $files;
 	}
 	/*
 	function get svg code
	Example: $adminz->get_icon_html('zalo',['style'=>['width'=>'1em;','fill'=>'white']]);
 	*/	
	static function get_icon_html($icon = 'info-circle' ,$attr=[]){
		$return = "";
		if(file_exists($icon)){
			$iconurl = $icon;
		} else if(filter_var($icon, FILTER_VALIDATE_URL) === false){
			//$iconurl = plugin_dir_url(ADMINZ_BASENAME).'assets/icons/'.$icon.'.svg';
			$iconurl = ADMINZ_DIR.'assets/icons/'.$icon.'.svg';

			if(!file_exists($iconurl)){
				$iconurl = ADMINZ_DIR.'assets/icons/info-circle.svg';
			}
		}else{
			$iconurl = $icon;
			
			if($image_id = attachment_url_to_postid( $iconurl )){
				$iconurl = get_attached_file($image_id);
			}
		}

		$convert_attr = [];
		foreach ($attr as $key => $value) {
			if(!is_array($value)){
				$value = explode(",", $value);
			}			
			$convert_attr[$key] = $value;
		}

		// set fixed attr
		if(!array_key_exists('class', $convert_attr)){
			$convert_attr['class'] = [];
		}		
		if(!in_array('adminz_svg', $convert_attr['class'])){
			$convert_attr['class'][] = 'adminz_svg';						
		}
		if(!array_key_exists('style', $convert_attr)){
			$convert_attr['style'] = [];
		}		
		if(!array_key_exists('fill', $convert_attr['style'])){
			$convert_attr['style']['fill'] = 'currentColor';			
		}		
		$attr_item = "";	

		foreach ($convert_attr as $key => $attr_data) {
			$tmpattr = [];
			if(!empty($attr_data)){
				foreach($attr_data as $keydata => $valuedata){
					if(is_int($keydata)){
						$tmpattr[]= $valuedata;
					}else{
						$tmpattr[]= $keydata.":".$valuedata.";";
					}
				}
			}
			$attr_item .= $key.'="'.implode(" ",$tmpattr).'" ';						
		}


		if(!(pathinfo($iconurl)['extension'] == 'svg')){
			$return = '<img '.$attr_item.' src="'.$iconurl.'"/>';
		}else{			
			$response = @file_get_contents($iconurl);
		    $return = self::cleansvg($response, $attr_item);
		}
		return apply_filters('the_title',$return);
	}

	public static function cleansvg($response, $attr_item){
		$return = "";
		// Tìm thẻ <svg>
    	preg_match('/<svg[^>]*>(.*?)<\/svg>/is', $response, $matches);
    	if (isset($matches[0])) {
    		$response = $matches[0];
    		$return = str_replace(
				'<svg',
				'<svg '.$attr_item,
				$response
			);
			$return = preg_replace('/<!--(.*)-->/', '', $return);
			// $return = preg_replace('/width="[^"]+"/i', '', $return);
	        // $return = preg_replace('/height="[^"]+"/i', '', $return);
    	}
		return $return;
	}
	/*function get_icon($name,$attr=[]){		
		return self::get_icon_html($name,$attr);
		
		if(!(strpos($name, 'http') === false)){
			return '<img class="svg" width="35px" height="35px" src="'.$name.'"/>';
		}else{
			return Adminz::get_icon_html($name,$attr);
		}
	}*/

	function enable_codemirror_helper($tabname){
		// coditional tab name = $tabname
		if(is_admin()){
			if(!(isset($_GET['tab']) and sanitize_text_field($_GET['tab']) == $tabname)){return ; }
			add_action('admin_enqueue_scripts',function(){
				$editor['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/html'));
			  	wp_localize_script('jquery', "adminz_editor", $editor);	  	
			  	wp_enqueue_script('wp-theme-plugin-editor',array('jquery', 'wp-codemirror'));
			  	wp_enqueue_style('wp-codemirror');
			});
		}
	}
	function test($atts, $content = null ) {
		extract(shortcode_atts(array(
			'content'=> $this->get_adminz_menu_title()
	    ), $atts));    
		return '<div style="background: #71cedf; border: 2px dashed #000; display: flex; color: white; justify-content: center; align-items: center; "> '.$content.'</div>';
	}
	
	function debug_output($return){
		if(is_user_logged_in()){
			return $return;
		}
		return str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), ' ', $return);		
	}
	


	/*Trong table option thi dung check_option*/
	function check_option($child,$subchild=false,$checkvalue=true){
		$parent = $this::$options;		
		if($subchild and isset($parent[$child][$subchild]) and $parent[$child][$subchild] == $checkvalue){
			return true;
		}
		if(isset($parent[$child]) and $parent[$child] == $checkvalue){
			return true;
		}
		return false; 
	}
	// lay gia tri thi dung get_option_value
	function get_option_value($child,$subchild =false,$default=""){				
		$parent = $this::$options;
		if($subchild){
			if(isset($parent[$child][$subchild])){
				return $parent[$child][$subchild];
			}
		}else{
			if(isset($parent[$child]) and $parent[$child]){			
				return $parent[$child];
			}
		}
		
		return $default;
	}
}