<?php

/**
 *
 * Class A5 Dynamic Files
 *
 * @ A5 Plugin Framework
 * Version: 1.0 beta 20160127
 *
 * Handels styles or javascript in either dynamical files or inline
 * 
 * @ parameter $place = 'wp' selects where to attach the file or print inline (wp, admin, login), $priority (of style inline)
 * @ optional $type = 'css' the filetype that should be generated (css, js, export)
 * @ optional $media = 'all' (for css), can be used as 'footer' to load JS to the footer
 * @ optional [(array) $hooks], [(bool) $inline], [(int) $priority],[(array) $args] for exporting only
 *
 */

class A5_DynamicFiles {
	
	public static $wp_styles = '', $admin_styles = '', $login_styles = '', $wp_scripts = '', $admin_scripts = '', $login_scripts = '';
	
	private static $type, $media, $hooks = array(), $printed = array();
	
	function __construct($place = 'wp', $type = 'css', $media = false, $hooks = false, $inline = false, $priority = false, $args = false) {
		
		self::$media = ($media) ? $media : 'all';
		
		self::$type = $type;
		
		if (false === $hooks) $hooks = array();
		
		if (!is_array($hooks)) $hooks = (array) $hooks;
		
		self::$hooks = array_merge(self::$hooks, $hooks);
		
		if (true === $inline) :
		
			add_action($place.'_head', array($this, 'print_'.$place.'_inline_'.$type), $priority);
		
		else :
		
			add_action('init', array ($this, 'add_rewrite'));
			add_action('template_redirect', array ($this, 'file_template'));
			add_action ($place.'_enqueue_scripts', array ($this, $place.'_enqueue_'.$type), $priority);
			
		endif;

	}
	
	// preparing for the virtual file
	
	function add_rewrite() {
		
		global $wp;
		$wp->add_query_var('A5_file');
		
		add_rewrite_rule('a5-framework-frontend.css', 'index.php?A5_file=wp_css', 'top');
		add_rewrite_rule('a5-framework-frontend.js', 'index.php?A5_file=wp_js', 'top');
		add_rewrite_rule('a5-framework-backend.css', 'index.php?A5_file=admin_css', 'top');
		add_rewrite_rule('a5-framework-backend.js', 'index.php?A5_file=admin_js', 'top');
		add_rewrite_rule('a5-framework-login.css', 'index.php?A5_file=login_css', 'top');
		add_rewrite_rule('a5-framework-login.js', 'index.php?A5_file=login_js', 'top');
		add_rewrite_rule('a5-export-settings', 'index.php?A5_file=export', 'top');
	
	}
	
	function file_template() {
		
		$A5_file = get_query_var('A5_file');
		
		switch ($A5_file) :
		
			case 'wp_css' :
			
				$this->write_wp_dss();
				
				break;
				
			case 'admin_css' :
			
				$this->write_admin_dss();
				
				break;
				
			case 'login_css' :
			
				$this->write_login_dss();
				
				break;
				
			case 'wp_js' :
			
				$this->write_wp_djs();
				
				break;
				
			case 'admin_js' :
			
				$this->write_admin_djs();
				
				break;
				
			case 'login_js' :
			
				$this->write_login_djs();
				
				break;
			
			case 'export' :
			
				extract($args);
				
				header('Content-Description: File Transfer');
				header('Content-Disposition: attachment; filename="'.$name.'-' . str_replace('.','-', $_SERVER['SERVER_NAME']) . '-' . date('Y') . date('m') . date('d') . '.txt"');
				header('Content-Type: text/plain; charset=utf-8');
				
				echo json_encode($options);
				
				exit;
			
				break;
		
		endswitch;
	
	}
	
	// getting scripts to frontend
	
	function wp_enqueue_css () {
		
		$file = (false === get_option('rewrite_rules')) ? '?A5_file=wp_css' : '/a5-framework-frontend.css';
		
		$A5_css_file=get_bloginfo('url').$file;
					
		wp_register_style('A5-framework', $A5_css_file, false, A5_FormField::version, self::$media);
		wp_enqueue_style('A5-framework');
		
	}
	
	function wp_enqueue_js () {
		
		$footer = ('footer' == self::$media) ? true : false;
		
		$file = (false === get_option('rewrite_rules')) ? '?A5_file=wp_js' : '/a5-framework-frontend.js';
		
		$A5_js_file=get_bloginfo('url').$file;
			
		wp_register_script('A5-framework', $A5_js_file, false, A5_FormField::version, $footer);
		wp_enqueue_script('A5-framework');
		
	}
	
	// getting scripts to backend
	
	function admin_enqueue_css ($hook) {
		
		if (!in_array($hook, self::$hooks)) return;
		
		$file = (false === get_option('rewrite_rules')) ? '?A5_file=admin_css' : '/a5-framework-backend.css';
		
		$A5_css_file=get_bloginfo('url').$file;
					
		wp_register_style('A5-framework', $A5_css_file, false, A5_FormField::version, self::$media);
		wp_enqueue_style('A5-framework');
		
	}
	
	function admin_enqueue_js ($hook) {
		
		if (!in_array($hook, self::$hooks)) return;
		
		$footer = ('footer' == self::$media) ? true : false;
		
		$file = (false === get_option('rewrite_rules')) ? '?A5_file=admin_js' : '/a5-framework-backend.js';
	
		$A5_js_file=get_bloginfo('url').$file;
			
		wp_register_script('A5-framework', $A5_js_file, false, A5_FormField::version, $footer);
		wp_enqueue_script('A5-framework');
		
	}
	
	// getting scripts to login screen
	
	function login_enqueue_css () {
		
		$file = (false === get_option('rewrite_rules')) ? '?A5_file=login_css' : '/a5-framework-login.css';
		
		$A5_css_file=get_bloginfo('url').$file;
			
		wp_register_style('A5-framework', $A5_css_file, false, A5_FormField::version, self::$media);
		wp_enqueue_style('A5-framework');
		
	}
	
	function login_enqueue_js () {
		
		$footer = ('footer' == self::$media) ? true : false;
		
		$file = (false === get_option('rewrite_rules')) ? '?A5_file=login_js' : '/a5-framework-login.js';
		
		$A5_css_file=get_bloginfo('url').$file;
			
		wp_register_script('A5-framework', $A5_js_file, false, A5_FormField::version, $footer);
		wp_enqueue_script('A5-framework');
		
	}
	
	// writing the styles to a dynamic file
	
	private function write_wp_dss() {
	
		$eol = "\n";
		
		header('Content-type: text/css');
		
		$css_text = '@charset "UTF-8";'.$eol.'/* CSS Document createtd by the A5 Plugin Framework */'.$eol;
		
		$css_text .= self::$wp_styles;
		
		echo $css_text;
		
		exit;
		
	}
	
	private function write_admin_dss() {
		
		$eol = "\n";
		
		header('Content-type: text/css');
		
		$css_text = '@charset "UTF-8";'.$eol.'/* CSS Document createtd by the A5 Plugin Framework */'.$eol;
		
		$css_text .= self::$admin_styles;
		
		echo $css_text;
		
		exit;
		
	}
	
	private function write_login_dss() {
	
		$eol = "\n";
		
		header('Content-type: text/css');
		
		$css_text = '@charset "UTF-8";'.$eol.'/* CSS Document createtd by the A5 Plugin Framework */'.$eol;
		
		$css_text .= self::$login_styles;
		
		echo $css_text;
		
		exit;
		
	}
	
	// writing the javascript to a dynamic file
	
	function write_wp_djs() {
	
		$eol = "\n";
		
		header('Content-type: text/javascript');
		
		$js_text = '// JavaScript Document createtd by the A5 Plugin Framework'.$eol;
		
		$js_text .= self::$wp_scripts;
		
		echo $js_text;
		
		exit;
		
	}
	
	function write_admin_djs() {
	
		$eol = "\n";
		
		header('Content-type: text/javascript');
		
		$js_text = '// JavaScript Document createtd by the A5 Plugin Framework'.$eol;
		
		$js_text .= self::$admin_scripts;
		
		echo $js_text;
		
		exit;	
		
	}
	
	function write_login_djs() {
	
		$eol = "\n";
		
		header('Content-type: text/javascript');
		
		$js_text = '// JavaScript Document createtd by the A5 Plugin Framework'.$eol;
		
		$js_text .= self::$login_scripts;
		
		echo $js_text;
		
		exit;
		
	}
	
	// writing styles or scripts inline
	
	function print_wp_inline_css() {
		
		$eol = "\n";
		
		echo '<style id="A5-framework-inline-css" type="text/css" media="'.self::$media.'">'.$eol.'/* CSS Styles created by the A5 Plugin Framework */'.$eol.self::$wp_styles.'</style>'.$eol;	
		
	}
	
	function print_wp_inline_js() {
		
		$eol = "\n";
		
		echo '<script id="A5-framework-inline-js" type="text/javascript">'.$eol.'// JavaScript createtd by the A5 Plugin Framework'.$eol.self::$wp_scripts.'</script>'.$eol;	
		
	}
	
	function print_admin_inline_css() {
		
		$screen = get_current_screen();
		
		if (!isset($screen)) return;
		
		if (in_array('admin_css', self::$printed) || !in_array($screen->id, self::$hooks)) return;
		
		$eol = "\n";
		
		echo '<style id="A5-framework-admin-css" type="text/css" media="'.self::$media.'">'.$eol.'/* CSS Styles created by the A5 Plugin Framework */'.$eol.self::$admin_styles.'</style>'.$eol;
		
		self::$printed[] = 'admin_css';
		
	}
	
	function print_admin_inline_js() {
		
		$screen = get_current_screen();
		
		if (!isset($screen)) return;
		
		if (in_array('admin_js', self::$printed) || !in_array($screen->id, self::$hooks)) return;
		
		$eol = "\n";
		
		echo '<script id="A5-framework-admin-js" type="text/javascript">'.$eol.'// JavaScript createtd by the A5 Plugin Framework'.$eol.self::$admin_scripts.'</script>'.$eol;
		
		self::$printed[] = 'admin_js';
		
	}
	
	function print_login_inline_css() {
		
		$eol = "\n";
		
		echo '<style id="A5-framework-login-css" type="text/css" media="'.self::$media.'">'.$eol.'/* CSS Styles created by the A5 Plugin Framework */'.$eol.self::$login_styles.'</style>'.$eol;	
		
	}
	
	function print_login_inline_js() {
		
		$eol = "\n";
		
		echo '<script id="A5-framework-login-js" type="text/javascript">'.$eol.'// JavaScript createtd by the A5 Plugin Framework'.$eol.self::$login_scripts.'</script>'.$eol;	
		
	}
	
	static function build_widget_css($selector, $element, $compress = true) {
		
		$eol = ($compress) ? '' : "\n";
		$spc = ($compress) ? '' : ' ';
		
		$return = 'div.'.$selector.' '.$element.','.$eol;
		$return .= 'li.'.$selector.' '.$element.','.$eol;
		$return .= 'aside.'.$selector.' '.$element.','.$eol;
		$return .= 'section.'.$selector.' '.$element.$spc;
		
		return $return;
		
	}
	
	/***************************************************************************************************
 
		List of file functions and their parameters:
		
		a5_styles([$place], [$media], [$inline], [$priority])
		
		a5_script([$place], [$hooks], [$inline], [$priority])
		
	/**************************************************************************************************/ 
	
	/**
	 *
	 * function to get css
	 *
	 * @ param $place = 'wp' selects where to attach the file or print inline (wp, admin, login)
	 * @ param $media = 'all'
	 * @ param $inline = false (whether or not to print styles inline)
	 * @ param $priority = false (to move the styles up or down)
	 * @ param $hooks = false (hooks for admin styles)
	 *
	 */
	 
	function a5_styles($place = 'wp', $media = 'all', $inline = false, $priority = false, $hooks = false) {
		
		self::__construct($place, 'css', $media, $hooks, $inline, $priority);
		
	}
	
	/**
	 *
	 * function to get javascript
	 *
	 * @ param $place = 'wp' selects where to attach the file or print inline (wp, admin, login)
	 * @ param $hooks = false (for wp admin scripts)
	 * @ param $inline = false (whether or not to print scripts inline)
	 * @ param $footer = false (whether or not to print scripts into the footer)
	 * @ param $priority = false (to move the scripts up or down)
	 *
	 */
	 
	function a5_scripts($place = 'wp', $hooks = false, $inline = false, $footer = false, $priority = false) {
		
		if (true == $footer) $footer = 'footer';
		
		self::__construct($place, 'js', $footer, $hooks, $inline, $priority);
		
	}
	
} // A5_Dynamic Files

/**
 *
 * function to export settings
 *
 * @ param $settings = plugin options
 * @ param $plugin_name = will be used to create the name of the downloaded file
 *
 */
 
function a5_export($settings, $plugin_name) {
	
	$args = array (
	
		'name' => $plugin_name,
		'options' => $settings
		
		);
	
	new A5_DynamicFiles('wp', 'export', false, false, false, false, $args);
	
}

?>