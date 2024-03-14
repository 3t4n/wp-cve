<?php 

namespace LitExtension;

/**
 * Class LitMain
 */
class LitMain
{
	
	//const APP_LINK = 'http://127.0.0.1/laravel/cartmigration_ui_ver3/public/';
    const APP_LINK = 'https://cm.litextension.com/';
	const APP_LINK_HOME = 'https://litextension.com/';

	public $litView;
	public $litType;

	public function __construct()
	{
		$this->litView = new LitView();
        $this->litType = new LitType();
		$this->_addActions();
	}

	public static function init(){
		new self;
	}

	public function _addActions(){
        add_action('admin_init', array($this, 'startLitSession'));
		add_action('admin_menu', array($this, 'createMenuAdminPanel'));
        add_action('admin_init', array($this, 'enqueue_scripts'));
		add_action('login_head', array($this, 'add_favicon'));
		add_action('admin_head', array($this, 'add_favicon'));
//        add_action('wp_logout', array($this, 'clearLitSession'));
//        add_action('wp_login', array($this, 'clearLitSession'));
	}

	public function createMenuAdminPanel(){
	    add_menu_page('LitExtension', 'LitExtension', 'manage_options', 'litextension', 'litMigration', LIT_URL_PLUGIN . 'assets/images/logo.png');
	    add_submenu_page('litextension', 'Shopping Cart to WooCommerce Migration', 'Shopping Cart to WooCommerce Migration', 'manage_options', 'migrate-to-woocommerce', array($this, 'migrateToWooCommerce'));

        add_submenu_page('', '', '', 'manage_options', 'add-session', array($this, 'litAddSession'));
        add_submenu_page('', '', '', 'manage_options', 'clear-session', array($this, 'litClearSession'));
        add_submenu_page('', '', '', 'manage_options', 'install-connector', array($this, 'litInstallConnector'));

	    remove_submenu_page('litextension', 'litextension');
	}

	public function add_favicon() {
	    $favicon_url = LIT_URL_PLUGIN . 'assets/images/favicon.ico';
	    echo '<link rel="shortcut icon" href="' . esc_url($favicon_url) . '" />';
	}

	public function startLitSession() {
        if(!session_id()) {
            session_start();
        }
    }

    public function clearLitSession() {
        session_destroy ();
    }

    public function enqueue_scripts(){
        if(isset($_GET['page']) && ($_GET['page'] == 'litextension' || $_GET['page'] == 'migrate-to-woocommerce' )) {
            wp_enqueue_style('custom-style-lit', plugins_url('../assets/css/litextension.css',__FILE__ ));
            wp_enqueue_script('custom-script-lit', plugins_url('../assets/js/litextension.js',__FILE__ ));
        }
    }

	public function migrateToWooCommerce(){
	    $src = self::APP_LINK . 'app-create-migration?target_type=woocommerce&target_url=' . esc_url(get_home_url()) . '&app_mode=true';
	    $_param = array(
	        'src' => $src,
            'url_register' => self::APP_LINK . 'register',
            'url_forgot' => self::APP_LINK . 'password/reset',
            'plugin_url' => plugins_url('..', __FILE__),
            'app_link' => self::APP_LINK . 'app-login',
            'list_cart' => $this->litType->sourceCarts()
        );
	    $this->litView->litView('index', $_param);
	}

	public function pricing(){
//	    $src = self::APP_LINK_HOME . 'pricing';
//	    echo $this->litView->litView('index', $src);
	}

	public function howItWorks(){
//	    $src = self::APP_LINK_HOME . 'how-litextension-works/migrate-from-Shopping-Carts-to-woocommerce.html';
//	    echo $this->litView->litView('index', $src);
	}

	public function additionalServices(){}

	public function liveChatHelp(){}

	public function litAddSession(){
	    if (isset($_GET['litEmail'])){
            $_SESSION['lit-login-plugin'] = sanitize_email($_GET['litEmail']);
            $_SESSION['lit-security-token'] = sanitize_text_field($_GET['security_token']);
        }
    }

    public function litClearSession(){
        if (isset($_SESSION['lit-login-plugin'])){
            unset($_SESSION['lit-login-plugin']);
            unset($_SESSION['security_token']);
        }
    }

    public function litInstallConnector(){
		$token = '';
		if(isset($_REQUEST['token'])){
	        $token = sanitize_text_field(@$_REQUEST['token']);
		}
	    $connector = new LitConnector();
        echo "<p id='litextension-response'>".sanitize_text_field($connector->execute(LitConnector::ACTION_INSTALL, $token))."</p>";
    }

	public function redirect($url){
		echo  '<script type="text/javascript">'.
	     'window.location = "' . esc_url($url) . '"' .
	    '</script>';
	}
}