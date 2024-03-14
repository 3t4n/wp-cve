<?php
/**
 * @package Sandbox-Plugin
 * @version 0.4
 */
/*
Plugin Name: Sandbox Plugin
Plugin URI: http://wordpress.think-bowl.com/sandbox-plugin/
Description: Creates a completely independent sandbox based on your existing live site that is not accessible to the general public and search engines.
Author: Eric Bartel
Version: 0.4
Author URI: http://think-bowl.com

<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_URI} !^/sandbox/.* [NC]
RewriteCond %{HTTP_COOKIE} sandbox=([^;]+) [NC]
RewriteRule ^(.*)$ /sandbox/%1/$1 [NC,L,QSA,S=10]
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>

*/

// Includes
require_once 'admin-errors.php';
require_once 'admin-form.php';
require_once 'admin-menu.php';
require_once 'admin-table.php';
require_once 'sandbox-ajax.php';
require_once 'class-sandbox.php';

// Globals
global $sandbox_db_version, $sandboxes, $sandbox_dir, $wp_dir, $sandbox_errors, $valid_php_version,$htaccess_config;
$sandbox_db_version = "0.1";
$sandboxes = array();
$wp_dir = sandbox_find_wordpress_base_path();
$sandbox_dir = $wp_dir."sandbox/";
$valid_php_version = true;
$htaccess_config = "# BEGIN Sandbox
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_URI} !^/sandbox/.* [NC]
RewriteCond %{HTTP_COOKIE} sandbox=([^;]+) [NC]
RewriteRule ^(.*)$ /sandbox/%1/$1 [NC,L,QSA,S=10]
</IfModule>
# END Sandbox";

register_activation_hook( __FILE__, 'sandbox_activate' );
function sandbox_activate() {
    global $wp_dir,$sandbox_dir,$sandbox_errors,$htaccess_config;
    // Force load of error text array because function will not be called until plugin is activated
    sandbox_load_errors();
    $htaccess_path = $wp_dir.".htaccess";
    
    try {
        if(file_exists($sandbox_dir)){
            if(!is_dir($sandbox_dir)){
                throw new Sandbox_Exception($sandbox_errors['path_exists_not_dir']);
            }
        } else {
            if (!mkdir($sandbox_dir, 0755)) {
                throw new Sandbox_Exception($sandbox_errors['path_exists_not_dir']);
            }
        }
        
        update_htaccess();
//        if(file_exists($htaccess_path)){
//            $handle = fopen($htaccess_path, 'r');
//            if($handle === FALSE) {
//                throw new Sandbox_Exception($sandbox_errors['htaccess_denied']);
//            }
//            $data = fread($handle,filesize($htaccess_path)); 
//            fclose($handle);
//            if(stripos($data, $htaccess_config) === FALSE){
//                $handle = fopen($htaccess_path, 'a'); 
//                if($handle === FALSE) { 
//                    throw new Sandbox_Exception($sandbox_errors['htaccess_denied']);
//                }
//                fwrite($handle, $htaccess_config);
//                fclose($handle);
//            }
//        } else {
//            $handle = fopen($htaccess_path, 'w');
//            if($handle === FALSE) {
//                throw new Sandbox_Exception($sandbox_errors['htaccess_no_create']);
//            }
//            fwrite($handle, $htaccess_config);
//            fclose($handle);
//        }
    } catch (Sandbox_Exception $sandbox_exception) {   
        // If error occurs, provide error to user and deactivate plugin    
        sandbox_trigger_error($sandbox_exception->sandbox_error->get_error_message()."</strong>");
    }
}

register_deactivation_hook( __FILE__, 'sandbox_deactivate' );
function sandbox_deactivate() {
    global $wp_dir,$sandbox_dir,$sandbox_errors, $htaccess_config;
    $htaccess_path = $wp_dir.".htaccess";
    try {
        update_htaccess(false);
        if(file_exists($sandbox_dir) && is_dir($sandbox_dir) && is_readable($sandbox_dir) && count(scandir($sandbox_dir)) == 2) {
            rmdir($sandbox_dir);
        }
    } catch (Sandbox_Exception $sandbox_exception) {          
            die($sandbox_exception->sandbox_error->get_error_message());
    }
}

function update_htaccess($activate = true){
    global $wp_dir;
    
    $url = parse_url(site_url());
    $path = $url['path'];
    if(empty($url) || !preg_match('/.*\/$/', $url['path'])){
        $path = $path."/";
    }
    
    $sandbox_rewrite = array();
    
    if($activate) {
        $sandbox_rewrite[] = "<IfModule mod_rewrite.c>";
        $sandbox_rewrite[] = "RewriteEngine On";
        $sandbox_rewrite[] = "RewriteBase ".$path;
        $sandbox_rewrite[] = "RewriteCond %{REQUEST_URI} !^".$path."sandbox/.* [NC]";
        $sandbox_rewrite[] = "RewriteCond %{HTTP_COOKIE} sandbox=([^;]+) [NC]";
        $sandbox_rewrite[] = "RewriteRule ^(.*)$ ".$path."sandbox/%1/$1 [NC,L,QSA,S=10]";
        $sandbox_rewrite[] = "</IfModule>";
    }
    
    if(!preg_match('/.*\/$/', $url['path'])){
        $wp_dir = $wp_dir."/";
    }
    
    // Insure # BEGIN Sandbox is on new line
    $htaccess = explode( "\n", implode( '', file( $wp_dir.".htaccess" ) ) );
    $newhtaccess = array();
    foreach($htaccess as $line){
        if(preg_match("/.+# BEGIN Sandbox/", $line))
            $line = str_replace("# BEGIN Sandbox", "\n# BEGIN Sandbox",$line);
        $newhtaccess[] = $line;
    }
    file_put_contents($wp_dir.".htaccess", implode("\n", $newhtaccess));
    
    if(insert_with_markers($wp_dir.".htaccess", "Sandbox", $sandbox_rewrite)) error_log("success");        
}

add_action('init', 'sandbox_check_php_version');
function sandbox_check_php_version(){
    global $valid_php_version;
    
    if (version_compare(PHP_VERSION, '5.1.0') < 0) {
        $valid_php_version = false;
        
    }
}

add_action('init', 'handle_cookie');
function handle_cookie(){
    if(isset($_REQUEST['page']) and $_REQUEST['page'] == 'sandbox' and isset($_REQUEST['action'])){
        $path = parse_url(get_option('siteurl'), PHP_URL_PATH);
        $host = parse_url(get_option('siteurl'), PHP_URL_HOST);
        switch($_REQUEST['action']){
            case 'activate':
                $sandbox = $_REQUEST['shortname'];
                setcookie('sandbox', $sandbox, strtotime('+1 month'), "/", $host);
                Header('Location: '.admin_url());
                Exit(); 
                break;
            case 'deactivate':
                if(isset($_COOKIE['sandbox'])){
                    setcookie("sandbox", "", time()-3600, "/", $host);
                    Header('Location: '.admin_url());
                    Exit(); 
                }
                break;
        }
        
    }
}

add_action('init', 'sandbox_devel_options');
function sandbox_devel_options(){
    global $sandboxes;
    
    switch($_REQUEST['debug']){
            case 'full_reset':
                $sandboxes = array();
                update_option('sandboxes', $sandboxes);
                break;
            case 'dump_sandboxes':
                var_dump($sandboxes);
                break;
            case 'reset_acknowledged':
                delete_option("sandbox_backup_acknowledged");
                break;
    }
}

add_action('plugins_loaded', 'sandbox_plugins_init', 1);
function sandbox_plugins_init() {
    global $sandboxes;
    
    $sandboxes = get_option( "sandboxes" );
    
    if ( !$sandboxes ) {
      $sandboxes = array();
      add_option( 'sandboxes', $sandboxes );
    }     
}

function sandbox_admin_notice(){
    global $sandboxes, $valid_php_version;

    if(isset($_COOKIE['sandbox'])) {
        echo '<div class="updated">
            <p>Currently in '.$sandboxes[$_COOKIE['sandbox']]->name.' sandbox. To deactivate sandbox, click <a href="admin.php?page=sandbox&action=deactivate">here</a>.</p>
        </div>';
    }
    
    if(!$valid_php_version){
        echo '<div class="updated">
            <p>Invalid PHP version. Must be 5.1.0 or higher.</p>
        </div>';
    }
    
    $acknowledged = get_option( "sandbox_backup_acknowledged" );
    
    if ( !$acknowledged && $_REQUEST['page'] == 'sandbox' && $_REQUEST['action'] != 'acknowledge_backup') {
        echo '<div class="updated">
            <p>
            Given the infinite variations of Wordpress installs, this plugin could never be tested in all possible scenarios. Make sure you have an offline backup before continuing.<br/> 
            <a href="admin.php?page=sandbox&action=acknowledge_backup">Of course I\'ve backed up my site!</a>
            </p>
        </div>';
    }
    
    foreach($sandboxes as $sandbox){
      if(get_class($sandbox) != "Sandbox") $sandbox_errors['corrupt_saved_sandboxes']->print_error();  
    }
}
add_action('admin_notices', 'sandbox_admin_notice');

// Utility functions
function sandbox_find_wordpress_base_path() {
//    $dir = dirname(__FILE__);
//    do {
//        //it is possible to check for other files here
//        if( file_exists($dir."/wp-config.php") ) {
//            return $dir."/";
//        }
//    } while( $dir = realpath("$dir/..") );
//    return null;
    $home = get_option( 'home' );
	$siteurl = get_option( 'siteurl' );
	if ( ! empty( $home ) && 0 !== strcasecmp( $home, $siteurl ) ) {
		$wp_path_rel_to_home = str_ireplace( $home, '', $siteurl ); /* $siteurl - $home */
		$pos = strripos( str_replace( '\\', '/', $_SERVER['SCRIPT_FILENAME'] ), trailingslashit( $wp_path_rel_to_home ) );
		$home_path = substr( $_SERVER['SCRIPT_FILENAME'], 0, $pos );
		$home_path = trailingslashit( $home_path );
	} else {
		$home_path = ABSPATH;
	}

	return str_replace( '\\', '/', $home_path );
}

class Sandbox_Error extends WP_Error{
    function print_error(){
        error_log("Sandbox Error Code:".$this->get_error_code());
        
        print '<div class="error">
            <p>'.$this->get_error_message();

        $data = $this->get_error_data();
        if(!empty($data)) print '<br/>'.$data;
                
        print "</p>\n</div>";
    }
}

class Sandbox_Exception extends Exception{
    public $sandbox_error;
    function __construct($error) {
        if(is_object($error) && get_class($error) == "Sandbox_Error") {
            parent::__construct($error->get_error_message());
            $this->sandbox_error = $error;
        } else {
            parent::__construct($error);
        }
    }
}
 
function sandbox_trigger_error($message, $errno = E_USER_ERROR) {
    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'error_scrape') {
        echo '<strong>' . $message . '</strong>';
        exit;
    } else {
        trigger_error($message, $errno);
    }
 
}

function sandbox_query($sql, $error_code){
    global $wpdb, $sandbox_errors;
    
    //Debug
    //$wpdb->show_errors();
    
    if($wpdb->query($sql) === FALSE){
        $error = $sandbox_errors[$error_code];
        $error->add_data("SQL: ".$sql, $error_code);
        throw new Sandbox_Exception($error);
    }
}

function sandbox_get_results($sql, $return_type, $error_code){
    global $wpdb, $sandbox_errors;
    
    //Debug
    //$wpdb->show_errors();
    $results = $wpdb->get_results($sql, $return_type);
    if( $results === FALSE){
        throw new Sandbox_Exception($sandbox_errors[$error_code]);
    }
    
    return $results;
}

?>