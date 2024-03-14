<?php

/**
 * Introduce special type for controllers which render pages inside admin area
 * 
 * @author Pavel Kulbakin <p.kulbakin@gmail.com>
 */
abstract class PMLC_Controller_Admin extends PMLC_Controller {
	/**
	 * Admin page base url (request url without all get parameters but `page`)
	 * @var string
	 */
	public $baseUrl;
	/**
	 * Parameters which is left when baseUrl is detected
	 * @var array
	 */
	public $baseUrlParamNames = array('page', 'pagenum', 'order', 'order_by', 'type', 's', 'f');
	/**
	 * Constructor
	 */
	public function __construct() {
		$remove = array_diff(array_keys($_GET), $this->baseUrlParamNames);
		if ($remove) {
			$this->baseUrl = remove_query_arg($remove);
		} else {
			$this->baseUrl = $_SERVER['REQUEST_URI'];
		}
		parent::__construct();
		
		// add special filter for url fields
		$this->input->addFilter(create_function('$str', 'return "http://" == $str ? "" : $str;'));
		
		// enqueue required sripts and styles
		global $wp_styles;
		if ( ! is_a($wp_styles, 'WP_Styles'))
			$wp_styles = new WP_Styles();
		
		wp_enqueue_style('jquery-ui', PMLC_Plugin::getInstance()->getRelativePath() . '/static/js/jquery/css/smoothness/jquery-ui.css');
		wp_enqueue_style('jquery-tipsy', PMLC_Plugin::getInstance()->getRelativePath() . '/static/js/jquery/css/smoothness/jquery.tipsy.css');
		wp_enqueue_style('jquery-jqplot', PMLC_Plugin::getInstance()->getRelativePath() . '/static/js/jquery/css/smoothness/jquery.jqplot.css');
		wp_enqueue_style('jquery-farbtastic', PMLC_Plugin::getInstance()->getRelativePath() . '/static/js/jquery/farbtastic/farbtastic.css');
		wp_enqueue_style('pmlc-admin-style', PMLC_Plugin::getInstance()->getRelativePath() . '/static/css/admin.css');
		wp_enqueue_style('pmlc-admin-style-ie', PMLC_Plugin::getInstance()->getRelativePath() . '/static/css/admin-ie.css');
		$wp_styles->add_data('pmlc-admin-style-ie', 'conditional', 'lte IE 7');
		
		$scheme_color = get_user_option('admin_color') and is_file(PMLC_Plugin::ROOT_DIR . '/static/css/admin-colors-' . $scheme_color . '.css') or $scheme_color = 'fresh';
		if (is_file(PMLC_Plugin::ROOT_DIR . '/static/css/admin-colors-' . $scheme_color . '.css')) {
			wp_enqueue_style('pmlc-admin-style-color', PMLC_Plugin::getInstance()->getRelativePath() . '/static/css/admin-colors-' . $scheme_color . '.css');
		}
		
		wp_enqueue_script('jquery-ui-datepicker', PMLC_Plugin::getInstance()->getRelativePath() . '/static/js/jquery/ui.datepicker.js', 'jquery-ui-core');
		wp_enqueue_script('jquery-tipsy', PMLC_Plugin::getInstance()->getRelativePath() . '/static/js/jquery/jquery.tipsy.js', 'jquery');
		wp_enqueue_script('jquery-farbtastic', PMLC_Plugin::getInstance()->getRelativePath() . '/static/js/jquery/farbtastic/farbtastic.js', 'jquery');
		wp_enqueue_script('jquery-ajaxfileupload', PMLC_Plugin::getInstance()->getRelativePath() . '/static/js/jquery/ajaxfileupload.js', 'jquery');
		
		wp_enqueue_script('excanvas', PMLC_Plugin::getInstance()->getRelativePath() . '/static/js/jquery/jqplot/excanvas.js');
		wp_enqueue_script('jquery-jqplot', PMLC_Plugin::getInstance()->getRelativePath() . '/static/js/jquery/jqplot/jquery.jqplot.js', array('jquery', 'excanvas'));
		wp_enqueue_script('jquery-jqplot-categoryAxisRenderer', PMLC_Plugin::getInstance()->getRelativePath() . '/static/js/jquery/jqplot/plugins/jqplot.categoryAxisRenderer.js', 'jquery-jqplot');
		wp_enqueue_script('jquery-jqplot-dateAxisRenderer', PMLC_Plugin::getInstance()->getRelativePath() . '/static/js/jquery/jqplot/plugins/jqplot.dateAxisRenderer.js', 'jquery-jqplot-categoryAxisRenderer');
		wp_enqueue_script('jquery-jqplot-separatorTickFormatter', PMLC_Plugin::getInstance()->getRelativePath() . '/static/js/jquery/jqplot/plugins/jqplot.separatorTickFormatter.js', 'jquery-jqplot');
		wp_enqueue_script('jquery-jqplot-highlighter', PMLC_Plugin::getInstance()->getRelativePath() . '/static/js/jquery/jqplot/plugins/jqplot.highlighter.js', 'jquery-jqplot');
		
		wp_enqueue_script('pmlc-admin-script', PMLC_Plugin::getInstance()->getRelativePath() . '/static/js/admin.js', array(
			'jquery-ui-dialog',
			'jquery-ui-datepicker',
			'jquery-tipsy',
			'jquery-farbtastic',
			'jquery-ajaxfileupload',
			'jquery-jqplot-dateAxisRenderer',
			'jquery-jqplot-highlighter',
			'jquery-jqplot-separatorTickFormatter',
		));
	}
	/**
	 * Help Tab hook for WP 3.0+ support
	 */
	function help_tab($contextual_help, $screen_id, $screen) {
		return $this->help_text;
	}
	
	protected $help_text;
	
	/**
	 * @see Controller::render()
	 */
	protected function render($viewPath = NULL)
	{
		add_filter('admin_body_class', create_function('', 'return "' . PMLC_Plugin::PREFIX . 'plugin";'));
		// assume template file name depending on calling function
		if (is_null($viewPath)) {
			$trace = debug_backtrace();
			$viewPath = str_replace('_', '/', preg_replace('%^' . preg_quote(PMLC_Plugin::PREFIX, '%') . '%', '', strtolower($trace[1]['class']))) . '/' . $trace[1]['function'];
		}
		
		// render contextual help automatically
		$viewHelpPath = $viewPath;
		// append file extension if not specified
		if ( ! preg_match('%\.php$%', $viewHelpPath)) {
			$viewHelpPath .= '.php';
		}
		$viewHelpPath = preg_replace('%\.php$%', '-help.php', $viewHelpPath);
		$fileHelpPath = PMLC_Plugin::ROOT_DIR . '/views/' . $viewHelpPath;
		if (is_file($fileHelpPath)) { // there is help file defined
			ob_start();
			include $fileHelpPath;
			$this->help_text = ob_get_clean();
			// WP 3.0+ filter support
			add_filter('contextual_help', array($this, 'help_tab'), 10, 3);
		}
		
		parent::render($viewPath);
	}
}