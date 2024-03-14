<?php
class Captionpix_Plugin {
 	private $help = CAPTIONPIX_HELP;
  	private $home = CAPTIONPIX_HOME;
  	private $icon = CAPTIONPIX_ICON;
 	private $name = CAPTIONPIX_PLUGIN_NAME;
	private $newsfeeds = array(CAPTIONPIX_NEWS,DIYWEBMASTERY_NEWS);
 	private $path = CAPTIONPIX_PATH;
 	private $slug = CAPTIONPIX_SLUG;
 	private $updater = CAPTIONPIX_UPDATER;
 	private $version = CAPTIONPIX_VERSION;
 	
	private $defaults = array(
	    'theme'=> 'crystal',
	    'align' => 'left',
	    'framebackground' => '',
	    'frameborder' => '',
	    'framebordercolor' => '',    	
	    'framebordersize' => '',
	    'framecolor' => '',
	    'framesize'=> '',
	    'marginbottom' => '10',	
	    'marginside' => '15',
	    'margintop' => '7',
	    'nostyle' => '',
	    'width' => '300',
	    'imgsrc' => '',
	    'imglink' => '',	
	    'imglinkrel' => '',
	    'imglinkclass' => '',
	    'imgtitle' => '',
	    'imgalt' => '',
	    'imgborder' => 'none',	
	    'imgbordercolor' => '',
	    'imgbordersize' => '',    
	    'imgmargin' => '0',	
	    'imgpadding' => '0',
	    'captionalign' => 'center',
	    'captionclass' => '',
	    'captionfontcolor' => '#FFFFFF',	
	    'captionfontfamily' => 'inherit',
	    'captionfontsize' => '12',
	    'captionfontstyle' => 'normal',
	    'captionfontweight' => 'normal',
	    'captionpaddingleft' => '10',
	    'captionpaddingright' => '10',
	    'captionpaddingtop' => '10',
	    'captionpaddingbottom' => '5',
	    'captionmaxwidth' => '',
	    'captiontext' => '',
	    'autocaption' => 'none'
	    );

	private $modules = array(
		'core' => array('class'=> 'Captionpix_Core', 'heading' => 'Core', 'tip' => 'Core Module for processing links in posts, pages and widgets'),
		'api' => array('class'=> 'Captionpix_API','heading' => 'API Keys', 'tip' => 'Check your Captionpix license is up to date.'),
		'theme' => array('class'=> 'Captionpix_Theme','heading' => 'Amazon', 'tip' => 'Theme factory for framed imaged'),
		'lightbox' => array('class'=> 'Captionpix_Lightbox', 'heading' => 'Lightbox', 'tip' => 'Add lightbox for captioned images.'),
	);
		
	private $news;
  	private $options;
	private $tooltips;
  	private $utils;
	private $admin_modules = array();
	private $public_modules = array();

	public function init() {
		$d = dirname(__FILE__) . '/';
		require_once ($d . 'class-options.php');
		require_once ($d . 'class-utils.php');
		require_once ($d . 'class-tooltip.php');
		require_once ($d . 'class-module.php'); 
		require_once ($d . 'class-display.php'); 
		$this->utils = new Captionpix_Utils();
		$this->tooltips = new Captionpix_Tooltip();
		$this->options = new Captionpix_Options( 'captionpix_options', $this->defaults);
		$this->newsfeeds = apply_filters('captionpix_news', $this->newsfeeds);
		foreach ($this->modules as $module => $settings) $this->init_module($module);
	}

	public function admin_init() {
        $d = dirname(__FILE__) . '/';		
        require_once ($d . 'class-news.php');
		require_once ($d . 'class-admin.php');
		require_once ($d . 'class-dashboard.php');
        $this->news = new Captionpix_News($this->version);
		new Captionpix_Dashboard($this);
		foreach ($this->modules as $module => $settings) $this->init_module($module, true);
 		if ($this->get_activation_key()) add_action('admin_init',array($this, 'upgrade'));  
	}
	
    static function get_instance() {
        static $instance = null;
        if (null === $instance) {
            $instance = new self(); 
            register_activation_hook($instance->path, array($instance, 'activate'));            
            add_action('init', array($instance, 'init'),0);
            if (is_admin()) add_action('init', array($instance, 'admin_init'),0);
        }
        return $instance;
    }
   
    protected function __construct() {}

    private function __clone() {}

    private function __wakeup() {}

	public function get_help(){
		return $this->help;
	}

	public function get_home(){
		return $this->home;
	}

	public function get_icon(){
		return $this->icon;
	}

	public function get_modules(){
		return $this->modules;
	}

	public function get_name(){
		return $this->name;
	}

	public function get_news(){
		return $this->news;
	}

	public function get_newsfeeds(){
		return $this->newsfeeds;
	}

	public function get_options(){
		return $this->options;
	}

    public function get_path(){
		return $this->path;
	}

    public function get_slug(){
		return $this->slug;
	}

	public function get_tooltips(){
		return $this->tooltips;
	}

	public function get_updater($backup = false){
		return sprintf($this->updater, $backup?'2':'1');
	}

	public function get_utils(){
		return $this->utils;
	}

	public function get_version(){
		return $this->version;
	}
	
	public function upgrade() { //apply any upgrades
		$modules = array_keys($this->modules);
		foreach ($modules as $module) 
			if ($this->is_module_enabled($module))
				$this->upgrade_module($module);
		$this->options->upgrade_options();
		$this->unset_activation_key();
	}	

	private function upgrade_module($module) {	
		if (array_key_exists($module, $this->modules)
		&& ($class = $this->modules[$module]['class'])) {
			if (array_key_exists($module, $this->admin_modules)
			&& is_callable(array( $this->admin_modules[$module],'upgrade'))) 
				call_user_func(array($this->admin_modules[$module], 'upgrade'));
		}
	}

	private function deactivate($path ='') {
		if (empty($path)) $path = $this->path;
		if (is_plugin_active($path)) deactivate_plugins( $path );
	}

	public function activate() { //called on plugin activation
    	$this->set_activation_key();
    }
	
    private function get_activation_key() { 
    	return get_option($this->activation_key_name()); 
    }

    private function set_activation_key() { 
    	return update_option($this->activation_key_name(), true); 
    }

    private function unset_activation_key() { 
    	return delete_option($this->activation_key_name(), true); 
    }

    private function activation_key_name() { 
    	return strtolower(__CLASS__) . '_activation'; 
    }

	
	function is_post_type_enabled($post_type){
		return in_array($post_type, array('post', 'page')) || $this->is_custom_post_type_enabled($post_type);
	}

	function is_custom_post_type_enabled($post_type){
		return in_array($post_type, (array)$this->options->get_option('custom_post_types'));
	}
	
	function custom_post_types_exist() {
       $cpt = get_post_types(array('public' => true, '_builtin' => false));
       return is_array($cpt) && (count($cpt) > 0);
	}

    public function get_module($module, $is_admin = false) {
	   $modules = $is_admin ? $this->admin_modules: $this->public_modules;
		return array_key_exists($module, $modules) ? $modules[$module] : false;
	}

    function get_modules_present(){
    	$modules = array();
    	$module_names = array_keys($this->modules);
		foreach ($module_names as $module_name) 
			if ($this->module_exists($module_name)) 
				$modules[$module_name] = $this->modules[$module_name];  	
		return $modules;
	}

	function module_exists($module) {
		return file_exists( dirname(__FILE__) .'/class-'. $module . '.php');
	}

	function is_module_enabled($module) {
		return in_array($module, $this->modules);
	}

	private function init_module($module, $admin=false) {
		if (array_key_exists($module, $this->modules)
		&& ($class = $this->modules[$module]['class'])) {
			$prefix =  dirname(__FILE__) .'/class-'. $module;
			if ($admin) {
				$class = $class .'_Admin';
				$file = $prefix . '-admin.php';
				if (!class_exists($class) && file_exists($file)) {
					require_once($file);
					$this->admin_modules[$module] = new $class($this, $module);
 				}
			} else {
				$file = $prefix . '.php';
				$widgets = $prefix . '-widgets.php';
				if (!class_exists($class) && file_exists($file)) {
					require_once($file);
					if (file_exists($widgets)) require_once($widgets);
					$this->public_modules[$module] = new $class();
				}
			} 
		}
	}

	public function get_link_url($module) {
		if ($m = $this->get_module($module, true))
			return $m->get_url();
		else
			return '';
	}

}
