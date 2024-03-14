<?php
class DirectoryPress_Static_Files
{
    const DIRECTORYPRESS_OPTIONS_CSS = 'directorypress.css';
    function __construct($with_actions = true) {
        global $directorypress_dev;
		require_once DIRECTORYPRESS_PATH. 'includes/minify/css-minifier.php';
        $directorypress_dev = (defined('DIRECTORYPRESS_DEV') ? constant("DIRECTORYPRESS_DEV") : false);

        add_action('wp_head', array(&$this, 'init_global_vars'),2);

        add_action('wp_enqueue_scripts', array(&$this, 'process_global_styles'), 99);

        add_action('get_footer', array(&$this,
           'addThemeOptionsCSSToEnqueue'
        ));
    }



    static function is_referer_admin_ajax()
    {
        global $pagenow;

        $result = in_array($pagenow, array(
            'admin-ajax.php'
        ));

        if($result) {
            return true;
        }
    }


        static function is_page_backend()
    {
        $is_admin = !(!is_numeric(strpos($_SERVER["REQUEST_URI"],"?wc-ajax")) and !is_admin() and !( in_array( $GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php','admin-ajax.php')) and !is_numeric(strpos($_SERVER["REQUEST_URI"],"/wp-admin")) and !is_numeric(strpos($_SERVER["REQUEST_URI"],"wc-ajax"))   ));

        if($is_admin) {
            return true;
        }
    }

    static function addGlobalStyle($styles) {
        global $directorypress_app_global_dynamic_styles;
        
        $directorypress_app_global_dynamic_styles .= $styles;
    }
    
    static function addLocalStyle($styles) {
        global $directorypress_app_local_dynamic_styles;
        
        $directorypress_app_local_dynamic_styles .= $styles;
    }

    public function include_files() {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$styles_dir = DIRECTORYPRESS_PATH . 'dynamic/*/*.php';
		$styles = glob($styles_dir);
       
        foreach ($styles as $style) {
            include_once ($style);
        }
    }
	
    public function init_globals() {
        
        $directorypress_app_dynamic_styles = array();
        $directorypress_app_global_dynamic_styles = $directorypress_app_local_dynamic_styles = '';
        
        global $directorypress_app_dynamic_styles, $directorypress_app_global_dynamic_styles, $directorypress_app_local_dynamic_styles;
    }

    function init_global_vars() {
        global $directorypress_shortcode_order;

        $directorypress_shortcode_order = 0;
    }
    
    static function shortcode_id() {
        global $directorypress_shortcode_order;
        
        $directorypress_shortcode_order++;
        return $directorypress_shortcode_order;
    }

    static function addCSS($app_styles, $id) {
        global $directorypress_app_dynamic_styles;
        
        $directorypress_app_dynamic_styles[] = array(
            'id' => $id,
            'inject' => $app_styles
        );

        if(self::is_referer_admin_ajax()) {
            echo '<style type="text/css">'. $app_styles .'</style>';  // phpcs:ignore WordPress.Security.EscapeOutput
        }

    }

    function insert_shortcode_styles($id) {
        if (!$id) return;
        $css = '';
        $styles = unserialize(base64_decode(get_post_meta($id, '_directorypress_dynamic_styles', true)));
        if (!empty($styles)) {
            foreach ($styles as $style) {
                $css.= $style['inject'];
            }
        }
        return $css;
    }

    static function addThemeOptionsCSSToEnqueue() {
        global $directorypress_dev;
        global $dynamic_directorypress_options_css;

        if (get_option("global_directorypress_options") == "" or $directorypress_dev or !file_exists(self::get_global_asset_upload_folder("directory") . get_option("global_directorypress_options"))) {
            $dynamic_directorypress_options_css = true;
        }

        if ((get_option("global_directorypress_options") != "" and file_exists(self::get_global_asset_upload_folder("directory") . get_option("global_directorypress_options")))) {
            $dynamic_directorypress_options_css = false;
            $theme_options_css = self::get_global_asset_upload_folder("url") . get_option("global_directorypress_options");
            if ($theme_options_css) {
                if ($directorypress_dev == false) wp_enqueue_style('directorypress-options', $theme_options_css, array(), self::global_assets_timestamp());
            }
        }
    }

    function process_global_styles() {

       // wp_enqueue_style('directorypress-style', get_stylesheet_uri() , false, false, 'all');

        // declaring the globals
        global $directorypress_app_local_dynamic_styles, $directorypress_app_global_dynamic_styles;

        $this->init_globals();
        $this->include_files();
		
        $output  = $directorypress_app_local_dynamic_styles;
        if(!get_option('global_directorypress_options')) {
            $output .= $directorypress_app_global_dynamic_styles;
        }
        $output .= $directorypress_app_global_dynamic_styles;
		$output.= $this->insert_shortcode_styles(directorypress_global_get_post_id());
        $minifier = new DirectoryPress_SimpleCssMinifier();
        $output = $minifier->minify($output);


        $time = "dynamic";
        $filename = str_replace(".css", "-" . $time . ".css", self::DIRECTORYPRESS_OPTIONS_CSS);
        $folder = self::get_global_asset_upload_folder("directory");
        self::StoreAsset($folder, $filename, $directorypress_app_global_dynamic_styles);
        update_option("global_directorypress_options", $filename, true);
		//wp_register_style( 'directorypress-dynamic-styles', false );
		wp_enqueue_style('directorypress-dynamic-styles', DIRECTORYPRESS_RESOURCES_URL . 'css/custom.css');
		wp_add_inline_style('directorypress-dynamic-styles', $output);
    }

    static function StoreAsset($folder, $filename, $file_content) {
        global $wp_filesystem;

        if (empty($wp_filesystem)) {
            require_once (ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }

        self::createPath($folder);
        $sha1_concat_string = sha1($file_content);
        $file_path = directorypress_path_convert($folder . '' . $filename);
        if (get_option($filename . "_sha1") != $sha1_concat_string or !file_exists($file_path)) {
            $comment = "/* ".time()." */";
            $minifier = new DirectoryPress_SimpleCssMinifier();
            $file_content = $minifier->minify($file_content);
			$chmod = (defined( 'FS_CHMOD_FILE' ))? FS_CHMOD_FILE: '0644';
            $wp_filesystem->put_contents($file_path, $comment . $file_content, $chmod);
            update_option($filename . "_sha1", $sha1_concat_string);
        }
    } 

    static function createPath($path) {
        if (is_dir($path)) return true;
        $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1);
        $return = self::createPath($prev_path);
        return ($return && is_writable($prev_path)) ? mkdir($path) : false;
    }
	
    static function get_global_asset_upload_folder($type) {
        $upload_folder_name = "directorypress_assets";
        $wp_upload_dir = wp_upload_dir();
        if ($type == "directory") {
            $upload_dir = $wp_upload_dir['basedir'] . '/' . $upload_folder_name . '/';
        }
        else if ($type == "url") {
            $upload_dir = $wp_upload_dir['baseurl'] . '/' . $upload_folder_name . '/';
        }
        else {
            return "";
        }

        return $upload_dir;
    }

    static function deleteFile($filename) {
        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once (ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }

        if (!$wp_filesystem->exists($filename)) return true;

        return $wp_filesystem->delete($filename);
    }

    public function DeleteThemeOptionStyles() {
        $filename = get_option('global_directorypress_options');
        $folder = $this->get_global_asset_upload_folder("directory");

        if ($this->deleteFile($folder . '' . $filename) != true and $filename != "") {
            die("A problem occurred while trying to delete theme-options css file");
        }
        else {
            update_option("global_directorypress_options", "", true);
            return true;
        }
    }

    static function global_assets_timestamp() {

        $timestamp = get_option('global_assets_timestamp');

        if(!is_numeric($timestamp)) {
            $timestamp = time();
            update_option('global_assets_timestamp',$timestamp);
        }

        return $timestamp;
    }

}
new DirectoryPress_Static_Files();