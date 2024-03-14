<?php
if (!defined('PRICELIST_WC_DEBUG')) define('PRICELIST_WC_DEBUG', false);

if (!class_exists('pricelist_wc')) {
    
    class pricelist_wc {
        public $isPro = false;
        protected $settings_file = 'settings_free';
        protected $plugin_file;
        public $classes;
        
        public function __construct($plugin_file) {
            $this->plugin_file = $plugin_file;
            $this->init_classes();
        }
        
        public function init() {
            $this->add_hooks();
            require_once $this->include_file('option');
        }
        
        protected function init_classes() {
            $this->classes = [];
            $this->classes['controller'] = 'controller';
            $this->classes['data'] = 'model';
            $this->classes['option'] = 'options';
            $this->classes['outputter_html'] = 'output-html';
            $this->classes['outputter_pdf_button'] = 'output-pdf-button';
            $this->classes['outputter_pdf'] = 'output-pdf';
            $this->classes['outputter'] = 'output';
            $this->classes[''] = 'plugin';
            $this->classes['profiler'] = 'util-'.(PRICELIST_WC_DEBUG ? 'debug' : 'release');
            $this->classes['generate_html'] = 'view-html';
            $this->classes['generate_pdf_button'] = 'view-pdf-button';
            $this->classes['generate_pdf'] = 'view-pdf';
            $this->classes['generate_output'] = 'view';
            $this->classes['tcpdf'] = ['libraries/tcpdf', 'tcpdf'];
        }
        private function use_pro(&$class, $usePro) {
            if ($usePro === null) $usePro = $this->isPro;
            if ($usePro && array_key_exists('pro_'.$class, $this->classes)) $class = 'pro_'.$class;
        }
        public function include_file($class, $usePro = null) {
            $this->use_pro($class, $usePro);
            $filename = $this->classes[$class];
            $dirname = trailingslashit(dirname($this->plugin_file));
            if (is_array($filename)) {
                $dirname .= trailingslashit($filename[0]);
                return $dirname.$filename[1].'.php';
            }
            return $dirname.'pricelist-for-woocommerce-'.$filename.'.php';
        }
        public function include_class($class, $usePro = null) {
            $this->use_pro($class, $usePro);
            return 'pricelist_wc_'.$class;
        }
        public function instantiate($class, $usePro = null, ...$args) {
            require_once($this->include_file($class, $usePro));
            $classname = $this->include_class($class, $usePro);
            return new $classname(...$args);
        }
        
        protected function add_hooks() {
            require_once $this->include_file('controller');
            add_action('wp_enqueue_scripts', $this->include_class('controller').'::pricelist_scripts');
            add_action('init', array(&$this, 'registerShortcode'));
            add_action('admin_post_generate_pdf', array(&$this, 'generatePDF'));
            add_action('admin_post_nopriv_generate_pdf', array(&$this, 'generatePDF'));
            add_action('admin_post_nopriv_register_generate_pdf', array(&$this, 'generatePDF'));
            
            register_activation_hook($this->plugin_file, array(&$this, 'activate'));
            register_deactivation_hook($this->plugin_file, array(&$this, 'deactivate'));
            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_menu', array(&$this, 'add_menu'));
            add_action( 'whitelist_options', 'pricelist_wc_option::whitelist_custom_options_page', 11 );
            
            $slug = plugin_basename($this->plugin_file);
            add_filter("plugin_action_links_$slug", array(&$this, 'action_links'));
            add_filter('plugin_row_meta', array(&$this, 'meta_links_wrapper'), 10, 2);
        }
        
        public function registerShortcode() {
            add_shortcode('pricelist', array(&$this, 'runShortcode'));
        }
        public function runShortcode($args) {
            return $this->instantiate('controller')->pricelist_wc_shortcode($args);
        }
        public function generatePDF() {
            $this->instantiate('controller')->generatePDF();
        }
        
        public function action_links($links)
        {
            if (is_plugin_active(plugin_basename($this->plugin_file))) {
                $settings_link = '<a href="admin.php?page=pricelist-wc-settings">Settings</a>';
                array_unshift($links, $settings_link);
                $pro_link = '<a href="https://inner-join.nl/product/price-list-pro/" style="color:#82AAD7;font-weight:bold;" target="_blank">Go Pro</a>';
                array_push($links, $pro_link);
            }
            return $links; 
        }
        
        public function meta_links_wrapper( $links, $file ) {
            if ($file === plugin_basename($this->plugin_file)) {
                $this->meta_links($links);
            }
            return $links;
        }
        
        public function meta_links(&$links) {}

        public function activate() {
            if (is_admin() && current_user_can('activate_plugins') && is_plugin_active($this->other_version())) {
                set_transient(get_called_class().'_other_version_is_active', true, 50);
            }
        }
        
        protected function other_version() {
            $pro = 'pricelist-for-woocommerce-pro/pricelist-for-woocommerce-pro.php';
            $free = 'pricelist-for-woocommerce/pricelist-for-woocommerce.php';
            return $pro === plugin_basename($this->plugin_file) ? $free : $pro;
        }

        public function deactivate() {
        }

        public function admin_init() {

            if (is_admin() && current_user_can('activate_plugins') && !is_plugin_active('woocommerce/woocommerce.php')) {
                
                add_action('admin_notices', get_called_class().'::woocommerce_notice');
                
                deactivate_plugins(plugin_basename($this->plugin_file));

                if (isset($_GET['activate'])) {
                    unset($_GET['activate']);
                }
            }

            if (get_transient(get_called_class().'_other_version_is_active')) {
                add_action('admin_notices', get_called_class().'::pricelist_other_version_is_active_notice');
                deactivate_plugins($this->other_version());
                delete_transient(get_called_class().'_other_version_is_active');
            }
            
            pricelist_wc_option::RegisterSettings();
        }

        public static function woocommerce_notice() {
            echo '<div class="notice notice-error"><p>The PriceList for WooCommerce plugin requires WooCommerce to be installed, if this is not installed this plugin will be deactivated</p></div>';
        }

        public static function pricelist_other_version_is_active_notice() {
            echo '<div class="notice notice-warning"><p>While activating the Free version of PriceList for WooCommerce, the Pro version has been deactivated to avoid conflicts.</p></div>';
        }

        public function add_menu() {
            $slug = $this->add_menu_main();
            $this->add_menu_settings($slug);
            $this->add_menu_gopro($slug);
        }
        
        protected function add_menu_main() {
            $page_title = __('PriceList for WooCommerce', 'textdomain');
            $menu_title = 'PriceList';
            $capability = 'manage_options';
            $menu_slug = 'pricelist-wc';
            $function = '';
            $icon_url = 'dashicons-media-spreadsheet';
            $position = 58;
            add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
            return $menu_slug;
        }
        
        protected function add_menu_settings($parent_slug) {
            $page_title = 'Settings';
            $menu_title = 'Settings';
            $capability = 'manage_options';
            $menu_slug = 'pricelist-wc-settings';
            $function = function() {
                $this->plugin_settings_page('settings', $this->settings_file);
            };
            $position = 0;
            $hook_suffix = add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function, $position);
            add_action('load-' . $hook_suffix, array(&$this, 'settings_scripts'));
            remove_submenu_page($parent_slug, $parent_slug);
        }
        
        protected function add_menu_gopro($parent_slug) {
            $page_title = 'Go Pro';
            $menu_title = '<span style="color:#82AAD7 !important;font-weight:bold;">Go Pro</span>';
            $capability = 'manage_options';
            $menu_slug = 'pricelist-wc-gopro';
            $function = array(&$this, 'go_pro_link');
            $position = 1;
            $hook_suffix = add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function, $position);
        }

        public function settings_scripts() {
            wp_enqueue_script('color-picker', 'https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.4.8/jscolor.min.js');
            wp_enqueue_script('fa', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js');
            wp_enqueue_script('tipso-script', 'https://cdnjs.cloudflare.com/ajax/libs/tipso/1.0.8/tipso.min.js', array('jquery-core'));
            wp_enqueue_style('tipso', 'https://cdnjs.cloudflare.com/ajax/libs/tipso/1.0.8/tipso.min.css');
            wp_enqueue_script('tipso-config', plugins_url('templates/js/tipso-config.js', $this->plugin_file));
            wp_enqueue_style('pricelist-settings', plugins_url('templates/settings.css', $this->plugin_file ));
        }

        public function go_pro_link() {
            wp_redirect("https://inner-join.nl/product/price-list-pro/");
        }
        
        protected function plugin_settings_page($context, $file) {
            if (!current_user_can('manage_options')){
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
            
            if (function_exists('check_admin_referrer')){
                check_admin_referrer(get_called_class().'_option_page_check_action');
            }
            
            $options = pricelist_wc_option::Update($_POST, $context, $this->isPro);
            extract($options, EXTR_PREFIX_ALL, 'pricelist');
            include(sprintf("%s/templates/{$file}.php", dirname($this->plugin_file)));
        }
    }
}
?>