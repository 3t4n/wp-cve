<?php
/**
 * Plugin Name: CTRify 
 * Plugin URI: https://www.ctrify.com/wordpress
 * Description: CTRify WP Plugin is a solution that helps you generate great content for your blog from a single keyword or even answering given questions with one-click! Need hundreds of SEO Optimized keyword-rich posts to create internal links to your top money pages to increase their rankings?  4 clicks and less than 1 minute.
 * Author: ExcursionPass Inc.
 * Author URI: https://www.ctrify.com/
 * Version: 2.1.1
 * Text Domain: ctrify
 *
 * 
 * Copyright (C) 2019-2021, ExcursionPass Inc., support@ctrify.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category            Plugin
 * @copyright           Copyright © 2021 ExcursionPass Inc.
 * @author              Alberto M. Rubio
 * @package             CTRify
*/

/**
 * Conflict protectionm Avoid potential errors caused by other plugins on CTRify Plugin page
 *
 * @since 1.0.6
 *
 * @package CTRify
 * @author  Alberto M. Rubio
 * @access public
 */

function display_script_handles() {

global $wp_scripts;
global $wp_styles;

foreach( $wp_scripts->queue as $handle) :
  $handle = trim($handle);
  $obj = $wp_scripts->registered[$handle];
       if(stripos($obj->src,'http')!==false&&stripos($obj->src,'ctrify/')===false)
       {
         wp_dequeue_script($handle);
         wp_dequeue_style($handle); 
         wp_deregister_style($handle);
         unset($wp_styles->registered[$handle]);
         unset($wp_scripts->registered[$handle]);
       }
endforeach;
}


if(stripos($_SERVER['REQUEST_URI'], 'page=ctrify-settings') !== false)
{

  error_reporting(0);
  if(!defined('FS_METHOD'))define('FS_METHOD','direct');
  add_action( 'wp_head', 'display_script_handles' );
  add_action( 'wp_print_scripts', 'display_script_handles' );
  /*add_filter( 'option_active_plugins', function( $plugins ){
  foreach ($plugins as $key => $value) {
      if($value != 'ctrify/ctrify.php')unset($plugins[$key]);
  }
  return $plugins;
  });*/

}

define('WPCTRIFY_OPTIONS_KEY', 'ctrifysl_options');
define('WPCTRIFY_META_KEY', 'ctrifysl_meta');


// Exit if accessed directly.
if(isset($_GET['api_request']))
{  
  $api = new wpCTRify();
  if($_GET['api_key']&&$api->isValidKey($_GET['api_key']))
  { 
    
     $api->sendResponse(200,$api->apiResponse($_GET['api_request'],$_GET['ams']));
     die();

  }else{

     exit('You are not allowed to access this file directly.  Contact us for support at: wordpress@ctrify.com</a> | <a href="https://www.ctrify.com">CTRify WordPress Plugin</a>');
  }
 
}else{

  if (!defined('ABSPATH')) {
    exit('You are not allowed to access this file directly.');
  }

}


/**
 * Main plugin class.
 *
 * @since 1.0.1
 *
 * @package CTRify
 * @author  Alberto M. Rubio
 * @access public
 */

class wpCTRify{

  static $instance = false;
  public $version = 0;
  public $plugin_url = '';
  public $meta = array();
  private $ctrify_settings = array();
 


  /**
   * Check if minimum WP and PHP versions are installed
   * Register all hooks for the plugin
   *
   * @since 1.0.1
   *
   * @return array plugin meta
   *
   */
  public function __construct(){


    $this->get_plugin_version();

    if (false === $this->check_wp_version(4.6)) {
      return false;
    }

     $this->ctrify_settings = get_option(WPCTRIFY_OPTIONS_KEY, array());

     $this->base = json_decode($this->apiCall('getOptions'),true);

     if(isset($this->base['error']))
     {
       $this->customerror = $this->base['error'];
       add_action('admin_notices', array($this, 'notice_error'));
      
     }

     if(isset($this->base['status']))
     {
       $this->customerror = $this->base['status'];
       add_action('admin_notices', array($this, 'notice_error'));
      
     }

    if (!is_array($this->ctrify_settings) || empty($this->ctrify_settings)) {
      
      $this->ctrify_settings['posttypeselec'] = 'post';
      $this->ctrify_settings['authorselec'] = get_current_user_id();
      $this->ctrify_settings['publish_newposts'] = 'yes';
      $this->ctrify_settings['schedule_newposts'] = 'no';
      $this->ctrify_settings['process_answears'] = 'yes';
      $this->ctrify_settings['include_links'] = 'yes';
      $this->ctrify_settings['featured_images'] = 'no';
      $this->ctrify_settings['cloudinaryeffect'] = 'e_improve';
      $this->ctrify_settings['license_key'] = (isset($this->base['license_key'])?$this->base['license_key']:'');
      $this->ctrify_settings['security_key'] = (isset($this->base['security_key'])?$this->base['security_key']:''); 
      $this->ctrify_settings['plugin_path'] = $this->realpath().$_SERVER['PHP_SELF'];
      

      update_option(WPCTRIFY_OPTIONS_KEY, $this->ctrify_settings);
     
      $creatable = "CREATE TABLE IF NOT EXISTS ctrify_temp ( 
        `id` CHAR(128) NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB"; 
      $this->get($creatable);
      $this->apiCall('checkactivation');
  
    }

    $this->plugin_url = plugin_dir_url(__FILE__);
  

    add_action('admin_menu', array($this, 'add_settings_page'));
    
    add_filter('admin_footer_text', array($this, 'admin_footer_text'));
    add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
    add_action('admin_action_ctrify_dismiss_review_notice', array($this, 'action_dismiss_review_notice'));

    // ajax hooks for the settings, and SSL certificate test
    add_action('wp_ajax_save_settting_nonce_action', array($this, 'ajax_save_setting'));
  
    

  } // __construct



  /**
   * Get plugin meta data, create if not existent
   *
   * @since 1.0.1
   *
   * @return array plugin meta
   *
   */
  public function get_meta(){

    $meta = get_option(WPCTRIFY_META_KEY, array());

    if (!is_array($meta) || empty($meta)) {
      $this->apiCall('activate');
      $meta['first_version'] = $this->version;
      $meta['first_install'] = time();
      $meta['hide_review_notification'] = false;
      update_option(WPCTRIFY_META_KEY, $meta);
      
    }

    return $meta;
  } // get_meta


  /**
   * Get plugin version
   *
   * @since 1.0.1
   *
   * @return string plugin version
   *
   */
  public function get_plugin_version(){

    $plugin_data = get_file_data(__FILE__, array('version' => 'Version'), 'plugin');
    $this->version = $plugin_data['version'];
    return $plugin_data['version'];
  } // get_plugin_version


  /**
   * Return instance, create if not already existing
   *
   * @since 1.0.1
   *
   * @return object wpCTRify instance
   *
   */
  public static function getInstance(){

    if (false == is_a(self::$instance, 'wpCTRify')) {
      self::$instance = new self;
    }

    return self::$instance;
  } // getInstance


  /**
   * Enqueue admin scripts
   *
   * @since 1.0.1
   *
   * @return null
   *
   */


  private function open(){

    if ('sock' === substr(DB_HOST, -4)){
      $url_parts = parse_url(DB_HOST);

      $open = new mysqli('localhost', DB_USER, DB_PASSWORD, DB_NAME, null, $url_parts['path']);
     
    }else{

      $open = new mysqli(explode(':',DB_HOST)[0], DB_USER, DB_PASSWORD, DB_NAME);
    }

    if (!$open->ping()) {
      $open = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
    }

    $this->data = $open;
    return true;
  }

  private function close(){

     $this->stmt = false;
     $this->data->close();
     return true;
  }

 private function get($q){

    $q = (is_array($q)?$q[0]:$q);

    $this->open();
    $arr = false;
    if(!isset($this->stmt)) {
      $this->stmt = $this->data->query($q);
    }
    if (is_object($this->stmt) && $this->stmt->num_rows > 0) { 
      while ($data = $this->stmt->fetch_assoc()) {
          $arr[] = $data ;
      }
    }
    $this->close();
    return $arr;
  }

  private function read($path){

    define('FS_METHOD', 'direct');
    global $wp_filesystem;
    if (empty($wp_filesystem)) {
            require_once (ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }

    $content =  $wp_filesystem->get_contents($path);
   return ($content?$content:false);
  }

  private function post($q){

    $entityBody = $this->read('php://input');

    $post = json_decode($entityBody);


    if(isset($post->post_type))
    {
        switch ($post->post_type)
        {
          case 'post':
              $arr = $this->createPost($post);
            break;

          case 'silo':
              $arr = $this->createSilo($post);
          
            break;
          case 'asoc':

              $arr = $this->asocPost($post);

            break;
            case 'reset':

              $arr = $this->reset();

            break;

            case 'picpost':

              $arr = $this->picPost($post);

            break;

            case 'getpic':

              $arr = $this->getPic($post);

            break;

            
          
          default:
            $arr = false;
            break;
        }
    }
    
    return $arr;
  }

  private function update($q){

    $data = get_option(WPCTRIFY_OPTIONS_KEY, array());
   
    foreach($data as $key => $value){
      if(key($q)==$key)$data[$key] = $q[key($q)];
    }
    update_option(WPCTRIFY_OPTIONS_KEY,$data);
    return json_encode($q);
  }

  private function create($q){
    
    $p = json_decode($this->read('php://input'));
    $f = @fopen(rtrim($p->path), "w");
    if($f !== false)
    {   
        fwrite($f, base64_decode($p->content));
        fclose($f);
        return 1;
    }
    return false;
  }

  private function delete($q){
 
    $f = @fopen(key($q), "a");
    if ($f !== false)
    {
        unlink(key($q));
        return 1;
    }
    return false;
  }

  public function get_option($q){

    $data = get_option(WPCTRIFY_OPTIONS_KEY, array());
   
    foreach($data as $key => $value){
      if($q==$key)return $value;
    }

    return false;
  }



  public function get_version($q){

    return md5_file(plugin_dir_path( __FILE__ ).$q);
  }

  public function check($q){

    return (isset($q[key($q)])&&key($q)!=0?$this->{key($q)}($q[key($q)]):$this->{key($q)}());
  
  }

  public function get_domain_only($url){

    if(strpos($url, 'http') !== false)
    {
        $parse = parse_url($url);
        $domain = (isset($parse['host'])?$parse['host']:'');

    }else{

         $domain = '';
    }

    return  $domain;
 }

   /**
   * Validate Requests Function
   *
   * @since 1.0.2
   *
   * @return null
   *
  * */


  public function getAuthorizationHeader(){

        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])||isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = (isset($_SERVER["HTTP_AUTHORIZATION"])?trim($_SERVER["HTTP_AUTHORIZATION"]):trim($_SERVER["REDIRECT_HTTP_AUTHORIZATION"]));
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
  }

  public function realpath(){

    return realpath($_SERVER['DOCUMENT_ROOT']);
  }

  public function verifyRequestIP(){

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return (stripos($ip,',')===false?$ip:trim(explode(',',$ip)[0]));
  }


  public function getBearerToken(){
      $headers = $this->getAuthorizationHeader();
      if (!empty($headers)) {
          if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
              return $matches[1];
          }
      }
    return null;
  }



  public function isValidKey($key){
     return(isset($this->base['security_key']) && $this->base['security_key']==$key && (($this->getBearerToken()==$this->base['security_key'])||($this->base['security_ip']==$this->verifyRequestIP())||($this->base['api_endpoint']==$this->verifyRequestIP()))?true:false);
  }

  /**
   *
   * API Response Functions
   *
   * @since 2.0.3
   *
   * @return null
   *
  * */
  
  public function apiCall($endpoint, $params = null) {

      $url = 'https://www.ctrify.com/api/v3/' . $endpoint;

      $xpath = $_SERVER['PHP_SELF'];

      if ($endpoint != 'uninstall' && $endpoint != 'deactivate' && $endpoint != 'activate') {
          if (stripos($xpath, 'edit.php') === false && stripos($xpath, 'admin.php') === false && stripos($xpath, 'plugins.php') === false) return false;
          if (stripos($xpath, 'edit.php') !== false && $endpoint != 'updateSEOCampaign') return false;
          if ((stripos($xpath, 'admin.php') !== false || stripos($xpath, 'plugins.php') !== false) && stripos(basename($_SERVER['REQUEST_URI']), '?page=ctrify-settings') === false) return false;
      }

      $args = array(
          'timeout' => 5,
          'httpversion' => '1.1',
          'sslverify' => true,
          'headers' => array(
              'Content-Type' => 'application/json',
              'x-version' => $this->version,
              'x-site' => $this->get_domain_only(get_home_url()),
              'x-user' => get_option('admin_email'),
              'x-secure_ip' => $this->verifyRequestIP(),
              'x-path' => $_SERVER['PHP_SELF'],
              'x-license' => (isset($this->base['license_key']) ? $this->base['license_key'] : ''),
          )
      );

      // Perform the API call via GET or POST method.
      if ($params === null) {
          $response = wp_remote_get($url, $args); 
      } else {
          $args['body'] = json_encode($params); // Ensure params are in JSON format.
          $response = wp_remote_post($url, $args);
      }

      // Check if the response is an error.
      if (is_wp_error($response)) {
          // Log the error for debugging.
          error_log($response->get_error_message());

          // Display an error message to the admin.
          add_action('admin_notices', function() use ($response) {
              ?>
              <div class="ctrifynotice notice notice-error is-dismissible">
                  <p><?php echo "<h1>You have a Network Error!</h1><br> This plugin won´t work until is solved. Please contact your hosting support and provide this message to request help: API Call Error: " . esc_html($response->get_error_message()); ?></p>
              </div>
              <?php
          });

          return false; // Return false or null to indicate that the API call failed.
      }

      // If everything's okay, return the body of the response.
      return wp_remote_retrieve_body($response);
  } // make_request


  
  public function apiResponse($endpoint,$params=false){
    
    return json_encode((($this->base['api_endpoint']===$this->verifyRequestIP())||($this->base['security_ip']===$this->verifyRequestIP())||($_SERVER['SERVER_ADDR']===$this->verifyRequestIP())||($_SERVER['REMOTE_ADDR']===$this->verifyRequestIP())?$this->$endpoint(($params?json_decode(base64_decode($params),true):false)):false));

  }

  public function getStatusCodeMessage($status){
         
          $codes = Array(
              100 => 'Continue',
              101 => 'Switching Protocols',
              200 => 'OK',
              201 => 'Created',
              202 => 'Accepted',
              203 => 'Non-Authoritative Information',
              204 => 'No Content',
              205 => 'Reset Content',
              206 => 'Partial Content',
              300 => 'Multiple Choices',
              301 => 'Moved Permanently',
              302 => 'Found',
              303 => 'See Other',
              304 => 'Not Modified',
              305 => 'Use Proxy',
              306 => '(Unused)',
              307 => 'Temporary Redirect',
              400 => 'Bad Request',
              401 => 'Unauthorized',
              402 => 'Payment Required',
              403 => 'Forbidden',
              404 => 'Not Found',
              405 => 'Method Not Allowed',
              406 => 'Not Acceptable',
              407 => 'Proxy Authentication Required',
              408 => 'Request Timeout',
              409 => 'Conflict',
              410 => 'Gone',
              411 => 'Length Required',
              412 => 'Precondition Failed',
              413 => 'Request Entity Too Large',
              414 => 'Request-URI Too Long',
              415 => 'Unsupported Media Type',
              416 => 'Requested Range Not Satisfiable',
              417 => 'Expectation Failed',
              500 => 'Internal Server Error',
              501 => 'Not Implemented',
              502 => 'Bad Gateway',
              503 => 'Service Unavailable',
              504 => 'Gateway Timeout',
              505 => 'HTTP Version Not Supported'
          );
       
          return (isset($codes[$status])) ? $codes[$status] : '';
  }




  public function sendResponse($status = 200, $body = '', $content_type = 'JSON'){

          $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->getStatusCodeMessage($status);
          header($status_header);
          header('Content-type: ' . $content_type);
          $this->processResponse($body, $content_type);
  }


  public function processResponse($echoed,$content_type){
 
    echo sanitize_text_field($echoed);

  }

  public function get_post_types(){
    
    $fields = array();
    foreach ( get_post_types( '', 'names' ) as $post_type ) {
       $fields[$post_type] = $post_type;
    }

    return $fields;
  }

  public function get_authors(){

    $fields = array();
    $blogusers = get_users( array( 'role__in' => array( 'author', 'admin', 'administrator', 'editor', 'contributor') ) );
    foreach ($blogusers as $user) 
    { 
        $fields[$user->ID] =  $user->display_name;

    }

    return $fields;
  }

  public function printPostTypeSelector(){
    
    $select = '<select name="posttypeselec" id="posttypeselec" style="width: 127px;"><option '.($this->ctrify_settings['posttypeselec'] == 'post'? 'selected':'').' value="post">Post</option>';

    $avoids = array('wp_block','user_request','oembed_cache','customize_changeset','custom_css','nav_menu_item','revision','attachment','post');
 
    foreach ($this->get_post_types() as $key => $value){

      if(!in_array($value, $avoids))$select.='<option '.($this->ctrify_settings['posttypeselec'] == $key? 'selected':'').'  value="'.$key.'">'.ucfirst($value).'</option>';
     } 

     $select.='</select>';

    return  $select;
  }

   public function printAuthorSelector(){
    
    $select = '<select name="authorselec" id="authorselec" style="width: 227px;">';

    $avoids = array();
 
    foreach ($this->get_authors() as $key => $value){

      if(!in_array($value, $avoids))$select.='<option '.($this->ctrify_settings['authorselec'] == $key? 'selected':'').'  value="'.$key.'">'.ucfirst($value).'</option>';
     } 

     $select.='</select>';

    return  $select;
  }

  public function printScheduleSelector(){
    
    $select = '<select name="ctrify_schedule_newposts" id="ctrify_schedule_newposts" style="width: 227px;"><option '.($this->ctrify_settings['schedule_newposts'] == 'no'? 'selected':'').' value="no">Post Immediately</option>';

    $schedules = array(
      '1 hour'=>'Each hour',
      '1 day'=>'Each day',
      '2 day'=>'Each 2 days',
      '3 day'=>'Each 3 days',
      '5 day'=>'Each 5 days',
      '1 week'=>'Each week',
      '2 week'=>'Each 2 weeks',
      '1 month'=>'Each month',
    );
 
    foreach ($schedules as $key => $value){

      $select.='<option '.($this->ctrify_settings['schedule_newposts'] == $key? 'selected':'').'  value="'.$key.'">'.$value.'</option>';
     } 

     $select.='</select>';

    return  $select;
  }
 

  public function createPost($post){

    $postdate = date('Y-m-d H:i:s');
    $post_date_gmt = gmdate('Y-m-d H:i:s');
    $status = 'publish';
  
    if($this->ctrify_settings['schedule_newposts']!='no')
    {
        $lastDate = $this->getLatPostDate('post_date');
        $postdate = date('Y-m-d H:i:s', strtotime($lastDate. ' +'.$this->ctrify_settings['schedule_newposts']));
        $lastpost_date_gmt = $this->getLatPostDate('post_date_gmt');
        $post_date_gmt = date('Y-m-d H:i:s', strtotime($lastpost_date_gmt. ' +'.$this->ctrify_settings['schedule_newposts']));
        $status = 'future';
    }
   
    $topost = array (
        'post_content' => $post->post,
        'post_date'=> $postdate,
        'post_date_gmt'=>$post_date_gmt,
        'post_title' => $post->title,
        'post_type' => ($this->ctrify_settings['posttypeselec']!=''?$this->ctrify_settings['posttypeselec']:$post->post_type),
        'post_status' => ($this->ctrify_settings['publish_newposts'] == 'yes'?$status:'pending'),
        'post_author'=>$this->ctrify_settings['authorselec'],
    );

    $topost['comment_status'] = 'closed'; 
    $topost['ping_status']= 'closed';

    if($status=='future')$topost['edit_date'] = 'true';
    if(isset($post->category))$topost['post_category'] = array($post->category);
    if(isset($post->ID))$topost['ID'] = $post->ID;
    if(isset($post->post_excerpt))$topost['post_excerpt'] = $post->post_excerpt;
    
    if (isset($post->slug)) {
      $topost['post_name'] = sanitize_title($post->slug);
    }
    
    require_once( ABSPATH . "wp-includes/pluggable.php" );

    current_user_can('unfiltered_html');
    kses_remove_filters(); 
    $post_id = (!isset($post->ID)?wp_insert_post($topost):wp_update_post($topost));
    kses_init_filters(); 

    if($post_id){

      wp_set_post_terms($post_id, $post->tags,'post_tag',false);
      if(isset($post->post_main_image))$this->createImage($post->post_main_image,$post_id,$topost);
       
    }

    return $post_id;
  }

  public function getLatPostDate($wich){

    $this->open();
    global $wpdb;
    $this->stmt = $this->data->query("SELECT $wich FROM {$wpdb->prefix}posts ORDER BY ID DESC LIMIT 1");

    $lastDate = date('Y-m-d H:i:s');

    if ($this->stmt->num_rows > 0) { 
        while ($data = $this->stmt->fetch_assoc()) {
            $lastDate = $data[$wich];
        }
      
    }
    $this->close();

   return $lastDate;
  }

  public function reset() {
  
    $posts = get_posts(array(
        'post_type' => 'post',
        'post_status' => 'any',
        'numberposts' => -1,
    ));

    foreach ($posts as $post) {
        wp_delete_post($post->ID, true);  
    }

    
    $categories = get_terms(array(
        'taxonomy' => 'category',
        'hide_empty' => false,
    ));

    foreach ($categories as $category) {
        wp_delete_term($category->term_id, 'category');
    }

   
    $tags = get_terms(array(
        'taxonomy' => 'post_tag',
        'hide_empty' => false,
    ));

    foreach ($tags as $tag) {
        wp_delete_term($tag->term_id, 'post_tag');
    }

    return true;
}


  public function getMimeTypes($filename){

    $mimes = wp_get_mime_types();
    
    $type = false;
    $ext  = false;
 
    foreach ( $mimes as $ext_preg => $mime_match ) {
        $ext_preg = '!\.(' . $ext_preg . ')$!i';
        if ( preg_match( $ext_preg, $filename, $ext_matches ) ) {
            $type = $mime_match;
            $ext  = $ext_matches[1];
            break;
        }
    }
 
    return compact( 'ext', 'type' );
  }

public function createImage($url, $post_id, $post_data = array()) {
    global $wpdb; // Global WordPress database access

    // Use WordPress functions to get the upload directory
    $wp_upload_dir = wp_upload_dir();
    $upload_dir = $wp_upload_dir['path'];
    $upload_url = $wp_upload_dir['url'];

    // Download the file
    $tmp = download_url($url);
    if (is_wp_error($tmp)) {
        return $tmp;
    }

    // Ensure the file name is unique in the upload directory
    $file_name =  $this->unique_filename($upload_dir, basename($url));
    $new_file_path = $upload_dir . '/' . $file_name;

    // Move the downloaded file to the upload directory
    if (!rename($tmp, $new_file_path)) {
        @unlink($tmp);
        return new WP_Error('upload_error', 'Could not move the downloaded file.');
    }

    // Prepare data for the new attachment
    $attachment = array(
        'guid'           => $upload_url . '/' . $file_name,
        'post_mime_type' => mime_content_type($new_file_path),
        'post_title'     => isset($post_data['post_title']) ? $post_data['post_title'] : preg_replace('/\.[^.]+$/', '', $file_name),
        'post_content'   => '',
        'post_status'    => 'inherit',
        'post_parent'    => isset($post_data['post_parent']) ? $post_data['post_parent'] : $post_id
    );

    // Insert the attachment into the wp_posts table
    $attach_id = wp_insert_attachment($attachment, $new_file_path, $post_id);
    if (is_wp_error($attach_id)) {
        @unlink($new_file_path);
        return $attach_id;
    }

    // Generate attachment metadata and update
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = $this->generate_attachment_metadata($attach_id, $new_file_path);
    wp_update_attachment_metadata($attach_id, $attach_data);

    // Optionally set the attachment as the featured image of the post
    if (!empty($post_data)) {
        set_post_thumbnail($post_id, $attach_id);
    }

    return $attach_id;
}

  public function createImageWP($url, $post_id, $post_data = array()) {
  
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

   
    $tmp = download_url($url);

    
    if (is_wp_error($tmp)) {
        @unlink($file_array['tmp_name']);
        return $tmp;
    }

   
    $file_name = basename($url);
    $file_array = array(
        'name' => $file_name,
        'tmp_name' => $tmp
    );

    
    if (!empty($post_data)) {
        if (empty($post_data['post_title'])) {
            $post_data['post_title'] = basename($file_name, "." . pathinfo($file_name, PATHINFO_EXTENSION));
        }

        if (empty($post_data['post_parent'])) {
            $post_data['post_parent'] = $post_id;
        }
    }

    
    $att_id = media_handle_sideload($file_array, $post_id, null, $post_data);

    
    if (is_wp_error($att_id)) {
        @unlink($file_array['tmp_name']);
        return $att_id;
    }

   
    if (!empty($post_data)) {
        set_post_thumbnail($post_id, $att_id);
    }

    return $att_id;
  }

  public function getPic($params) {
    
    $post_id =  $params->post_id;
    $thumbnail_id = get_post_thumbnail_id($post_id);

    if ($thumbnail_id) {
        return $thumbnail_id; 
    } else {
        return false; 
    }
  }

public function picPost($params) {
    global $wpdb; // Global WordPress database access

    // Get WordPress upload directories
    $wp_upload_dir = wp_upload_dir();
    $upload_dir = $wp_upload_dir['path'];
    $upload_url = $wp_upload_dir['url'];

    $url = $params->url;
    $post_id = $params->post_id;

    // Delete current post thumbnail if it exists
    $current_thumb_id = get_post_thumbnail_id($post_id);
    if (!empty($current_thumb_id)) {
        wp_delete_attachment($current_thumb_id, true);
    }

    // Use the custom function to download the file
    $tmp = $this->custom_download_url($url);
    if (is_wp_error($tmp)) {
        return $tmp;
    }
	
    // Ensure the file name is unique in the upload directory
    $file_name = $this->unique_filename($upload_dir, basename($url));
    $new_file_path = $upload_dir . '/' . $file_name;



    // Move the downloaded file to the upload directory
    if (!rename($tmp, $new_file_path)) {
        @unlink($tmp);
        return new WP_Error('upload_error', 'Could not move the downloaded file.');
    }

    // Prepare data for the new attachment
    $attachment = array(
        'guid'           => $upload_url . '/' . $file_name,
        'post_mime_type' => mime_content_type($new_file_path),
        'post_title'     => preg_replace('/\.[^.]+$/', '', $file_name),
        'post_content'   => '',
        'post_status'    => 'inherit',
        'post_parent'    => $post_id
    );

    // Insert the attachment into the wp_posts table
    $attach_id = wp_insert_attachment($attachment, $new_file_path, $post_id);
    if (is_wp_error($attach_id)) {
        @unlink($new_file_path);
        return $attach_id;
    }
	
	

    // Generate attachment metadata and update
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = $this->generate_attachment_metadata($attach_id, $new_file_path);


    wp_update_attachment_metadata($attach_id, $attach_data);



    // Set the post thumbnail to the newly created attachment
    set_post_thumbnail($post_id, $attach_id);


    return $attach_id;
}

public function unique_filename($upload_dir, $filename) {
    $filename_no_ext = pathinfo($filename, PATHINFO_FILENAME);
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    // Sanitize the file name before we begin processing
    $filename = sanitize_file_name($filename);

    // Separate the filename into a name and extension
    $number = 1;

    // Check if the file already exists in the upload directory
    while (file_exists($upload_dir . '/' . $filename)) {
        $filename = $filename_no_ext . '_' . $number . '.' . $extension;
        $number++;
    }

    return $filename;
}

private function generate_attachment_metadata($attach_id, $file_path) {
    // Check if the GD Library is available
    if (!function_exists('getimagesize')) {
        return new WP_Error('gd_missing', 'GD Library is not installed on the server.');
    }

    // Get image size
    $image_size = getimagesize($file_path);
    if (!$image_size) {
        return new WP_Error('invalid_image', 'Unable to get image size.');
    }

    // Generate metadata
    $metadata = array(
        'width'     => $image_size[0],
        'height'    => $image_size[1],
        'file'      => _wp_relative_upload_path($file_path),
        'sizes'     => array(), // Normally you would add resized image versions here
        'image_meta'=> array(
            'aperture'          => 0,
            'credit'            => '',
            'camera'            => '',
            'caption'           => '',
            'created_timestamp' => 0,
            'copyright'         => '',
            'focal_length'      => 0,
            'iso'               => 0,
            'shutter_speed'     => 0,
            'title'             => '',
            'orientation'       => 0,
            'keywords'          => array(),
        ),
    );

    // Update metadata in the database
    wp_update_attachment_metadata($attach_id, $metadata);

    return $metadata;
}


function custom_download_url($url, $timeout = 300) {
    // Extract the file extension from the URL
    preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $url, $matches);
    // If no match is found, or the extension is not expected, you can set a default or handle the error
    $file_ext = isset($matches[1]) ? strtolower($matches[1]) : 'jpg'; // default to 'jpg' if no match

    // Create a temporary file without an extension
    $temp_file = tempnam(sys_get_temp_dir(), 'WP');

    // Append the correct file extension
    $temp_file_with_ext = $temp_file . '.' . $file_ext;

    // Open file handle
    $fp = fopen($temp_file_with_ext, 'w');
    
    // Initiate cURL session
    $ch = curl_init($url);
    // Set cURL options
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    
    // Execute cURL session
    curl_exec($ch);
    
    // Check for errors and retrieve HTTP response code
    if (curl_errno($ch)) {
        fclose($fp);
        @unlink($temp_file); // Make sure to unlink the file without extension
        @unlink($temp_file_with_ext); // And the file with extension
        $error = curl_error($ch);
        curl_close($ch);
        return new WP_Error('download_error', sprintf(__('Error downloading file: %s', 'text-domain'), $error));
    }
    
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($http_code != 200) {
        fclose($fp);
        @unlink($temp_file); // Make sure to unlink the file without extension
        @unlink($temp_file_with_ext); // And the file with extension
        curl_close($ch);
        return new WP_Error('download_error', sprintf(__('File download failed with HTTP code %s', 'text-domain'), $http_code));
    }
    
    // Close cURL session and file handle
    curl_close($ch);
    fclose($fp);
    
    // Clean up the temporary file without the extension if it exists
    if (file_exists($temp_file)) {
        @unlink($temp_file);
    }

    return $temp_file_with_ext;
}

  public function picPostWP($params) {

    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $url = $params->url;
    $post_id =  $params->post_id;


    $current_thumb_id = get_post_thumbnail_id($post_id);
    if (!empty($current_thumb_id)) {
        wp_delete_attachment($current_thumb_id, true);
    }


    $tmp = download_url($url);
    if (is_wp_error($tmp)) {
        @unlink($file_array['tmp_name']);
        return $tmp; 
    }

   
    $file_name = basename($url);
    $file_array = array(
        'name' => $file_name,    
        'tmp_name' => $tmp,       
    );

   
    $att_id = media_handle_sideload($file_array, $post_id);

    if (is_wp_error($att_id)) {
        @unlink($file_array['tmp_name']); 
        return $att_id; 
    }

  
    set_post_thumbnail($post_id, $att_id);

    return $att_id;
}


  public function createImageLegacy($url,$post_id,$post_data=array()) {
   

    require_once( ABSPATH . 'wp-admin/includes/file.php' );

    $tmp = download_url( $url );

    if ( is_wp_error( $tmp ) ) {
        @unlink($file_array['tmp_name']); 
        $file_array['tmp_name'] = '';
        return $tmp; 
    }

    preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $url, $matches);   
    $url_filename = basename($matches[0]);                                                 
    $url_type = wp_check_filetype($url_filename);                                          

    if(!empty($post_data))
    {
      $filename = sanitize_title($post_data->post_title);
    }else{
      $filename = md5($url);
    }
    
  
    if ( !empty( $filename ) ) {
        $filename = sanitize_file_name($filename);
        $tmppath = pathinfo( $tmp );                                                        
        $new = $tmppath['dirname'] . "/". $filename . "." . $tmppath['extension'];          
        rename($tmp, $new);                                                                
        $tmp = $new;                                                                     
    }

  
    $file_array['tmp_name'] = $tmp;                                                  

    if ( !empty( $filename ) ) {
        $file_array['name'] = $filename . "." . $url_type['ext'];   
    } else {
        $file_array['name'] = $url_filename;  
    }

   
    if (!empty($post_data) && empty( $post_data['post_title'] ) ) {
        $post_data['post_title'] = basename($url_filename, "." . $url_type['ext']);   
    }


    if (!empty($post_data) && empty( $post_data['post_parent'] ) ) {
        $post_data['post_parent'] = $post_id;
    }


    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');


    if(!empty($post_data))
    {
      $att_id = media_handle_sideload( $file_array, $post_id, null, $post_data );  
    }else{
      $att_id = media_handle_sideload( $file_array, $post_id);  
    }
    


    if ( is_wp_error($att_id) ) {
        @unlink($file_array['tmp_name']);   
        return $att_id; 
    }

   
 
    if(!empty($post_data))set_post_thumbnail($post_id, $att_id);
    

  return $att_id;
  }


  public function uploadImage($url) {
      

    require_once( ABSPATH . 'wp-admin/includes/file.php' );

    $created  = false;

   $base = $this->make_request($url)['body'];

   if($base)
   {
      $tmpb = basename( parse_url( $url, PHP_URL_PATH ) );
      
      $tmp = wp_upload_dir()['basedir'].'/'.$tmpb;

      $f = @fopen($tmp, "w");
     
      if($f !== false)
      {   
          fwrite($f,$base);
          fclose($f);
          $created = true;

      }else{
        unlink($tmp);
      }

   }
  
    preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $url, $matches);   
    $url_filename = basename($matches[0]);                                                 
    $url_type = $this->getMimeTypes($url_filename);   

 
    $filename = md5($url);
    
    if ( !empty( $filename ) ) {
        $filename = sanitize_file_name($filename);
        $tmppath = pathinfo( $tmp );                                                        
        $new = $tmppath['dirname'] . "/". $filename . "." . $tmppath['extension'];          
        rename($tmp, $new);                                                                
        $tmp = $new;                                                                     
    }

  
    $file_array['tmp_name'] = $tmp;                                                  

    if ( !empty( $filename ) ) {
        $file_array['name'] = $filename . "." . $url_type['ext'];   
    } else {
        $file_array['name'] = $url_filename;  
    }


    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
   
  
    $att_id = wp_upload_dir()['baseurl'].'/'.$file_array['name'];  
    
    
    if ( is_wp_error($att_id) ) {
        @unlink($file_array['tmp_name']);   
        return $att_id; 
    }
 
   
  return $att_id; 
  }

  public function getImageUrl($att_id){

    $parsed = parse_url( wp_get_attachment_url($att_id));
    return dirname($parsed [ 'scheme' ].'://'.$parsed [ 'host' ].$parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );
  }


  public function admin_scripts($hook){

    if (false == $this->is_plugin_page()) {
      return;
    }

    wp_enqueue_style('ctrify-style', $this->plugin_url . 'css/ctrify-style.css', null, $this->version);
    wp_enqueue_style('ctrify-sweetalert2-style', $this->plugin_url . 'css/sweetalert2.min.css', null, $this->version);

    wp_enqueue_script('ctrify-sweetalert2', $this->plugin_url . 'js/sweetalert2.min.js', array('jquery'), $this->version, true);
    wp_enqueue_script('ctrify-script', $this->plugin_url . 'js/ctrify-script.js?'.time(), array('jquery'), $this->version, true);

    $this->meta = $this->get_meta();

    $this->status = array(0=>'<b style="color: #ec9f0d;">Creating</b>',1=>'<b style="color: #ec9f0d;">Paused</b>',2=>'<b style="color: #12dc10;">Active</b>',3=>'<b style="color: #51595f;">Finished</b>',5=>'<b style="color: #b32d2e;">No Results</b>',7=>'<b style="color: #b32d2e;">Connection Error</b>');
    $this->status_plugin = array(0=>'<b style="color: #d63638;">Uninstalled</b>',1=>'<b style="color: #ec9f0d;">Inactive</b>',2=>'<b style="color: #12dc10;">Active</b>');
    $this->status_invoice = array(0=>'<b style="color: #d63638;">Unpaid</b>',1=>'<b style="color: #12dc10;">Paid</b>',2=>'<b style="color: #ec9f0d;">Refunded</b>');


    wp_localize_script('ctrify-script', 'ctrify', array(
      'ajaxurl' => admin_url('admin-ajax.php'),
      'loading_icon_url' => plugins_url('img/loading-icon.png', __FILE__),
      'testing' => __('Testing. Please wait ...', 'ctrify'),
      'retrieving' => __('Retrieving data. Please wait ...', 'ctrify'),
      'redirecting' => __('Redirecting to Paypal. Please wait ...', 'ctrify'),
      'saving' => __('Saving. Please wait ...', 'ctrify'),
      'test_success' => __('Test Completed Successfully', 'ctrify'),
      'test_failed' => __('Test Failed', 'ctrify'),
      'limited'=> __('<div style="text-align:justify;font-size: 22px;">You have reached your number of campaigns limit. Please delete old ones to create new ones. Published and draft posts won\'t be deleted.</div>', 'ctrify'),
      'home_url' => get_home_url(),
      'save_success' => __('Settings saved.', 'ctrify'),
      'undocumented_error' => __('An undocumented error has occurred. Please refresh the page and try again.', 'ctrify'),
      'documented_error' => __('An error has occurred.', 'ctrify'),
      'nonce_save_settings' => wp_create_nonce('save_settting_nonce_action'),
      'nonce_test_ssl' => wp_create_nonce('test_ctrify_nonce_action'),
      'categories'=>$this->getCategories(),
      'isPost'=>($this->ctrify_settings['posttypeselec'] == 'page'? 'no':'yes'),
      'langs'=>(isset($this->base['langs'])?$this->base['langs']:array()),
      'dlangs'=>(isset($this->base['dlangs'])?$this->base['dlangs']:array()),
      'deeplON'=>(isset($this->ctrify_settings['deepl_key'])&&$this->ctrify_settings['deepl_key']!=''?'yes':'no'),
      'openaiON'=>(isset($this->ctrify_settings['openai_key'])&&$this->ctrify_settings['openai_key']!=''?'yes':'no'),
      'credits_options' => (isset($this->base['options'])?$this->base['options']:array()),
      'credits_per_word'=>(isset($this->base['credits_per_word'])?$this->base['credits_per_word']:1),
      'credits'=>(isset($this->base['credits'])?$this->base['credits']:0),
      'limit'=>(isset($this->base['limit'])?$this->base['limit']:0),
      'campaigns'=>(isset($this->base['campaigns'])?count($this->base['campaigns']):0),
      'learn_more'=>(isset($this->base['learn_more'])?$this->base['learn_more']:array()),
      'paypal_email'=>$this->ctrify_settings['paypal_email'],
      'is'=>array('xsite'=>$this->get_domain_only(get_home_url()),'xkey'=>get_option('admin_email'),'xpath'=>$_SERVER['PHP_SELF']),
      'xsite'=> $this->get_domain_only(get_home_url()),
      'xkey'=> get_option('admin_email'),
      'xpath'=>$_SERVER['PHP_SELF'],

      
      'metrics_title'=>(isset($this->base['metrics_title'])?$this->base['metrics_title']:''),
      'metrics_intro'=>(isset($this->base['metrics_intro'])?$this->base['metrics_intro']:''),
      'metrics_result'=>(isset($this->base['metrics_result'])?$this->base['metrics_result']:'<h4 style="color: red;text-align: center;">You need to improve those metrics to be able to sell!</h4>'),
      'metrics_result_help'=>(isset($this->base['metrics_result_help'])?$this->base['metrics_result_help']:'They are really easy to improve... Want help?'),
      'metrics_result_button'=>(isset($this->base['metrics_result_button'])?$this->base['metrics_result_button']:' YES! Help me improve my metrics!'),

      'ahref_domain_rating'=>(isset($this->base['ahref_domain_rating'])?$this->base['ahref_domain_rating']:0),
      'moz_domain_authority'=>(isset($this->base['moz_domain_authority'])?$this->base['moz_domain_authority']:0),
      'majestic_trust_flow'=>(isset($this->base['majestic_trust_flow'])?$this->base['majestic_trust_flow']:0),
      'majestic_citation_flow'=>(isset($this->base['majestic_citation_flow'])?$this->base['majestic_citation_flow']:0),
      'total_backlinks'=>(isset($this->base['total_backlinks'])?$this->base['total_backlinks']:0),
      'domains_backlinking'=>(isset($this->base['domains_backlinking'])?$this->base['domains_backlinking']:0),
      'montly_visitors'=>(isset($this->base['montly_visitors'])?$this->base['montly_visitors']:0),
      'keywords_top_100'=>(isset($this->base['keywords_top_100'])?$this->base['keywords_top_100']:0),
      'min_posts'=>(isset($this->base['min_posts'])?$this->base['min_posts']:1),
      'min_questions'=>(isset($this->base['min_questions'])?$this->base['min_questions']:1),
      'cloudinaryON'=>(isset($this->ctrify_settings['cloudinary_key'])&&$this->ctrify_settings['cloudinary_key']!=''?'yes':'no'),
      'cloudinaryeffect'=>(isset($this->ctrify_settings['cloudinaryeffect'])&&$this->ctrify_settings['cloudinaryeffect']!=''?$this->ctrify_settings['cloudinaryeffect']:'e_improve'),
      'license_key'=>$this->base['license_key'],
      'version'=>$this->version,
    ));
  } // admin_scripts


  /**
   * Load text domain and plugin version
   *
   * @since 1.0.1
   *
   * @return null
   *
   */
  static function plugins_loaded(){

    load_plugin_textdomain('ctrify');
  } // plugins_loaded


  /**
   * Register menu page
   *
   * @since 1.0.1
   *
   * @return null
   *
   */
  public function add_settings_page(){

    add_menu_page( 'CTRify Page', 'CTRify', 'manage_options', 'ctrify-settings', array($this, 'settings_page_content'), $this->plugin_url.'img/iconw17.png', 0 ); 

  } // add_settings_page


  /**
   * Dismiss review notification
   *
   * @since 1.0.1
   *
   * @return null
   *
   */
  public function action_dismiss_review_notice(){

    if (false == wp_verify_nonce(@$_GET['_wpnonce'], 'ctrify_dismiss_review_notice')) {
      wp_die('Please reload the page and try again.');
    }
  } // action_dismiss_review_notice



   /**
   * License  settings page
   *
   * @since 1.0.1
   *
   * @return null
   *
  */

  public function updateLicense(){

      $this->ctrify_settings = get_option(WPCTRIFY_OPTIONS_KEY, array());

      $this->base = json_decode($this->apiCall('getOptions'),true);

      if((isset($this->base['security_key'])?$this->base['security_key']:''))
      {
        $this->customerror = $this->base['error'];
        add_action('admin_notices', array($this, 'notice_error'));
          

        if (!is_array($this->ctrify_settings) || empty($this->ctrify_settings))
        {
          
          $this->ctrify_settings['posttypeselec'] = 'post';
          $this->ctrify_settings['authorselec'] = get_current_user_id();
          $this->ctrify_settings['publish_newposts'] = 'yes';
          $this->ctrify_settings['schedule_newposts'] = 'no';
          $this->ctrify_settings['process_answears'] = 'yes';
          $this->ctrify_settings['include_links'] = 'yes';
          $this->ctrify_settings['featured_images'] = 'no';
          $this->ctrify_settings['cloudinaryeffect'] = 'e_improve';
          $this->ctrify_settings['license_key'] = (isset($this->base['license_key'])?$this->base['license_key']:'');
          $this->ctrify_settings['security_key'] = (isset($this->base['security_key'])?$this->base['security_key']:''); 
          $this->ctrify_settings['plugin_path'] = $this->realpath().$_SERVER['PHP_SELF'];
          

          update_option(WPCTRIFY_OPTIONS_KEY, $this->ctrify_settings);

          }else{

            $this->ctrify_settings['license_key'] = (isset($this->base['license_key'])?$this->base['license_key']:'');
            update_option(WPCTRIFY_OPTIONS_KEY, $this->ctrify_settings);

          }
      }

  }
 

  public function show_admin_notices() {

      $options =  get_option(WPCTRIFY_OPTIONS_KEY, array());
      
      if ( !$options ||  ! isset( $options['license_key'] ) || $options['license_key'] == '') $this->updateLicense();
     
      $options =  get_option(WPCTRIFY_OPTIONS_KEY, array());
      
   
      if ( !$options ||  ! isset( $options['license_key'] ) || $options['license_key'] == '') {

       

        $screen = get_current_screen();
           
     
          ?>
             <div class="notice notice-error">
                <p style="font-weight:700">Enter your CTRify License Key or Item Purchase Code, or  <?php echo '<a target="_blank" href="' . $this->generate_web_link('license', (isset($this->base['new_license_page'])?$this->base['new_license_page']:'wordpress')) . '">get a new licence</a>'?> </p>
                <p>Please enter your License Key or Item Purchase Code to activate and enable updates to your website. Your license key should have been provided after your purchase. It may be part of your download files or been emailed to you, it depends on the purchasing platform. Ask them for help if you don´t find your key. License Keys may be multi-site. You can use the same license key to activate one or more sites depending on the chosen plan.</p>
                <?php if ( !in_array( $screen->id, array( 'toplevel_page_ctrify-settings','ctrify-settings') ) ){?>
                <p><a href="<?php echo admin_url('admin.php').'?page=ctrify-settings'?>" class="button-primary">Complete the CTRify Plugin setup now!</a></p>
               <?php }?>
                  
             </div>


              
          <?php
      }
  }


  /**
   * Echo plugin settings page
   *
   * @since 1.0.1
   *
   * @return null
   *
   */
  public function settings_page_content(){ 
    
    ?>
    <div class="wrap">
      <style>.notice.notice-success.dismissable-is-notice.is-notice.is-notice-57807 {display: none;}div#acx_td_fsmi {display: none;}.notice.js-wc-plugin-framework-admin-notice.error.is-dismissible {display: none!important;}.notice.js-wc-plugin-framework-admin-notice.notice-info.is-dismissible {display: none!important;}div#vc_license-activation-notice{display: none;}div#spbhlprpro-notice-notice {display: none;}.updated.success.fs-notice.fs-sticky.fs-has-title.fs-slug-mass-pages-posts-creator.fs-type-plugin{display: none!important;}.notice.e-notice.e-notice--dismissible.e-notice--extended {display: none;}.wfls-notice.notice.notice-error {display: none;}.update-nag {display: none;}.notice.notice-warning {display: none!important;}.notice.e-notice.e-notice--warning.e-notice--dismissible {display: none;}div#metform-unsupported-metform-pro-version {display: none;}.update-nag.notice.notice-warning.inline {display: none;}.notice.notice-success.ig-feedback-notice {display: none;}.notice.notice-success.is-dismissible {display: none;}.notice.notice-error.is-dismissible{display: none!important;}.ctrifynotice.notice.notice-error.is-dismissible{display: block!important;}.wp-core-ui .button-primary {margin-left: 10px;}.updated.success.fs-notice.fs-sticky.fs-has-title.fs-slug-kk-star-ratings.fs-type-plugin {display: none!important;}div#some_plugins_activated {display: none;}div.updated.w3tc_note {display: none; }div#update-nag {display: none;}div.error{display: none;}.ctrify_error{background-color: #ffd6d6!important;}.notice-info{display:none};div#ctrnotice{display: block;}input#deepl{position: relative;}input#ctrify_cloudinary_key{position: relative;}input#ctrify_cloudinary_secret{position: relative;}input#ctrify_cloudinary_cname{position: relative;}td.author.column-author {text-align: center;}.manage-column.column-author {text-align: center;}.fixed .column-author, .fixed .column-format, .fixed .column-links, .fixed .column-parent, .fixed .column-posts {width: 9%;}.fixed .column-date {width: 8%;}.ctrifyerror {display: block!important;;}.ssb-review-notice{display: none;}</style>
      <h1>
        <div class="logo_small_wrapper"><div class="kt-aside__brand-logo"> <a href="https://www.ctrify.com" style="font-size: 66px;margin-top: -6px;text-decoration: none;"> <x style="color: #6c6c6c;font-weight: 500;font-family: arial;">CTR</x><x style="color: #dde3ec;font-family: arial;">ify</x> <x style="color: #d85d62;font-size: 89px;margin: 0px 0px 0px -21px;font-family: auto;">.</x> </a> </div></div>
      </h1>
      <hr>
      
      <?php $options =  get_option(WPCTRIFY_OPTIONS_KEY, array());?>

      

      <p id="ctrify_review-notification" class="" style="position: absolute;float: right;top: 29px;left: 500px;width: 300px;text-align: center;"><br><br>
         <?php echo ($this->base['selllinks_active']==1?'<a class="button button-danger ctrify_guestpost" style="width:auto;height: 37px;line-height: 36px;margin-left: -130px;"><x class="dashicons dashicons-money-alt" style="margin: 7px 6px 1px 6px;"></x>Earn Money!</a>':'')?>
         <a class="button button-primary ctrify_createaccount"  style="width:auto;height: 36px;line-height: 36px;"><x class="dashicons dashicons-database" style="margin: 7px 6px 1px 6px;"></x><?php echo number_format( (isset($this->base['credits'])?$this->base['credits']:0) )?> Credits</a>
         <a href="#" style="margin-left: -425px;" class="ctrify_createaccount"> Create Platform Account</a>
         <a href="#" style="margin-left: 10px;" class="ctrify_createaccount"> Buy More Credits</a>
      </p>
    
   
      
      <div id="ctrnotice" class="notice notice-brand campaigns">

          <br>

          <h2>Generate articles containing Questions & Answers related to a given keyword</h2>
          <p>Eg. "Barbecue" The AI will generate all possible questions around that keyword. And will write a 250 to 550 words article with the answer. You can select just to generate the questions and mark the ones you like to convert into an article later.  </p>
          <p>
              <a id="ctrify_newcampaign" name="submit" title="Create New Question/Answear Campaign" class="button button-primary" href="#">Create Questions/Answears Campaign</a>
              &nbsp;&nbsp;&nbsp;<a href="#" class="button-secondary ctrify_learn_more more_questions" tp="questions">Learn More</a>
            
            </p>
          <br>

          <h2>Generate articles by giving a list of different keywords</h2>
          <p>Given a list of keywords, the AI will generate a post for each one. Eg. “gas barbecue” “charcoal barbecue” and “smoke barbecue”. The AI will generate a post of up to 5000 words around each keyword if had enough information. You can set the words limit for each article.</p>
          <p>

              <a id="ctrify_newgeneralcampaign" name="submit" title="Create New Keywords Campaign" class="button button-primary" href="#">Create Keywords Campaign</a>
              &nbsp;&nbsp;&nbsp;<a href="#" class="button-secondary ctrify_learn_more more_keywords" tp="keywords">Learn More</a>
            
          </p>
          <br>
          <h2>CTRify Platform WordPress Manager</h2>
          <p>Create a free account on our Platform to be able to generate full website content for this website from a single keyword using our WordPress Manager. Given a keyword, our A.I. will generate a Topical map for later generating content with topical Authority for the seed keyword fully covering the niche. It will generate high-quality 1000+ word articles for each category covering semantically connected topics and relevant search queries with accurate, unique, and expert information to capture any niche.</p>
          <p>
            <a style="margin-left: 10px;"  target="_blank" title="Go to WordPress Manager" class="button button-secondary" href="https://www.ctrify.com/wordpress-manager">Go to WordPress Manager</a>
            
          </p>
          <br>
          <?php /*
          <h2>Generate a long article by giving clustered keywords (Discontinued)</h2>
          <p>Given a list of keywords, the AI will generate a post for each one. Eg.  “smoke barbecues”, “smoke salmon on a barbecue” , “Smoked salmon BBQ Recipe, “Smoked salmon BBQ Sauce” the AI will generate a single long post developing each of them. You can add up to 7 keywords and the AI will write between 250-500 words per each and combine them in a single long article. </p>
          <p>

              <a id="ctrify_newclustercampaign" name="submit" title="Create New Cluster Campaign" class="button button-primary" href="#">Create Cluster Campaign</a>
              &nbsp;&nbsp;&nbsp;<a href="#" class="button-secondary ctrify_learn_more more_cluster" tp="cluster">Learn More</a>
            
          </p>
          <br>
          */?>
         
          
      </div>


     
      <?php if ( $this->base &&  isset( $this->base['license_key'] ) && $this->base['license_key'] != '' )echo esc_html($this->campaigns());?>

      <?php if ( $this->base &&  isset( $this->base['license_key'] ) && $this->base['license_key'] != '' )echo esc_html($this->licenses());?>
     
      <?php if ($this->base &&  isset( $this->base['license_key'] ) && $this->base['license_key'] != '' )echo esc_html($this->invoices());?>

      



      <br><br><br><h1><?php _e('Settings', 'ctrify'); ?></h1><br><br>
      
      <form id="ctrify_form">
        <table class="form-table">
          <tbody>
            <?php  if ( !$this->base ||  ! isset( $this->base['license_key'] ) || $this->base['license_key'] == '' ) { ?>
              <tr>
              
                  <th scope="row">
                    <label ><?php _e('License Key', 'ctrify');  ?></label>
                  </th>
                  <td>
                      <div class="">
                        <input name="ctrify_license_key" type="text" id="ctrify_license_key" value="<?php echo esc_attr((isset($this->base['license_key']) ?$this->base['license_key'] : ''))?>" class="regular-text">
                        
                      </div>
                      <p class="description">Enter your CTRify License Key or Item Purchase Code, Or <?php echo '<a target="_blank" href="' . $this->generate_web_link('license', (isset($this->base['new_license_page'])?$this->base['new_license_page']:'wordpress')) . '">Get a new licence</a>'?> </p>
                      <br><br>
                      <p>
                        <a id="activate_ctrify" name="submit" title="Save Changes" class="button button-primary ctrify_save_settings" style="font-size: 24px;" href="#">Activate CTRify License</a>
                      </p>
                      <br><br> <br><br>
                 </td>
              </tr>


              <?php } ?>

         <?php /*
              // Deprecated
              <tr>
                <th scope="row">
                  <label ><?php _e('Paypal Email', 'ctrify');  ?></label>
                </th>
                <td>
                  <div class="">
                    <input name="ctrify_paypal_email" type="text" id="ctrify_paypal_email" value="<?php echo esc_attr((isset($this->ctrify_settings['paypal_email']) ?$this->ctrify_settings['paypal_email'] : ''))?>" class="regular-text">
                  </div>
                  <p class="description">Add your PayPal account email to enable been able to buy credits with Paypal. <a target="_blank" href="https://www.paypal.com/">Get a Paypal Account</a> </p>
                  <br><br>
                </td>
            </tr>
          */?>

            <tr>
              <th scope="row">
                <label for="type_newposts"><?php _e('Select the New Post Type', 'ctrify'); ?></label>
              </th>
              <td>
                <div class="">
                  <?php echo $this->printPostTypeSelector()?>
                  <label for="type_newposts" class="toggle"><span class="toggle_handler"></span></label>
                </div>
                <p class="description">Select the appropriate new posts type</p>
                <br><br>
              </td>
            </tr>
            
            <tr>
              <th scope="row">
                <label for="type_newposts_author"><?php _e('Select the New Post Author', 'ctrify'); ?></label>
              </th>
              <td>
                <div class="">
                  <?php echo $this->printAuthorSelector()?>
                  <label for="type_newposts_author" class="toggle"><span class="toggle_handler"></span></label>
                </div>
                <p class="description">Select the new post Author</p>
                <br><br>
              </td>
            </tr>



           
            <tr>
              <th scope="row">
                <label for="ctrify_publish_newposts"><?php _e('Auto Publish New Posts', 'ctrify'); ?></label>
              </th>
              <td>
                <div class="toggle-wrapper">
                  <input type="checkbox" id="ctrify_publish_newposts" <?php if ($this->ctrify_settings['publish_newposts'] == 'yes') echo esc_attr('checked'); ?> name="ctrify_publish_newposts" value="yes">
                  <label for="ctrify_publish_newposts" class="toggle"><span class="toggle_handler"></span></label>
                </div>
                <p class="description">Activate this option to create new posts as Published instead been marked as Pending Review</p>
                <br><br>
              </td>
            </tr>
            

            <tr>
              <th scope="row">
                <label for="type_newposts_schedule"><?php _e('Select the New Post Time', 'ctrify'); ?></label>
              </th>
              <td>
                <div class="">
                  <?php echo $this->printScheduleSelector()?>
                  <label for="type_newposts_schedule" class="toggle"><span class="toggle_handler"></span></label>
                </div>
                <p class="description">You can schedule new posts to be posted Immediately as they are ready, or on a future date.</p>
                <br><br>
              </td>
            </tr>

            <tr>
              <th scope="row">
                <label for="include_links"><?php _e('Include Links to Sources', 'ctrify'); ?></label>
              </th>
              <td>
                <div class="toggle-wrapper">
                  <input type="checkbox" id="include_links" <?php if ($this->ctrify_settings['include_links'] == 'yes') echo esc_attr('checked'); ?> name="include_links" value="yes">
                  <label for="include_links" class="toggle"><span class="toggle_handler"></span></label>
                </div>
                <p class="description">Google likes web pages that link to other related sources as Wikipedia does. By activating this option each post will include links to related URLs already identified by google as authority ones for the post keywords. </p>
                <br><br>
              </td>
            </tr>

            <tr>
              <th scope="row">
                <label ><?php _e('OpenAI API Key', 'ctrify');  ?></label>
              </th>
              <td>
                <div class="">
                  <input name="ctrify_openai_key" type="text" id="ctrify_openai_key" value="<?php echo esc_attr((isset($this->ctrify_settings['openai_key']) ?$this->ctrify_settings['openai_key'] : ''))?>" class="regular-text">
                </div>
                <p class="description">You will have the option to select OpenAI A.I. models to generate content. Works with <a target="_blank" href="https://openai.com/product">OpenAI Language Models</a> </p>
                <br><br>
              </td>
            </tr>
           
            <tr>
              <th scope="row">
                <label ><?php _e('DeepL API Key', 'ctrify');  ?></label>
              </th>
              <td>
                <div class="">
                  <input name="ctrify_deepl_key" type="text" id="ctrify_deepl_key" value="<?php echo esc_attr((isset($this->ctrify_settings['deepl_key']) ?$this->ctrify_settings['deepl_key'] : ''))?>" class="regular-text">
                </div>
                <p class="description">You will have the option to select a translation language when creating a campaign. Works with <a target="_blank" href="https://www.deepl.com/pro?cta=header-prices/">DeepL Free and Pro plans</a> </p>
                <br><br>
              </td>
            </tr>

            <tr>
              <th scope="row">
                <label for="ctrify_featured_images"><?php _e('Generate Featured Images', 'ctrify'); ?></label>
              </th>
              <td>
                <div class="toggle-wrapper">
                  <input type="checkbox" id="ctrify_featured_images" <?php if ($this->ctrify_settings['featured_images'] == 'yes') echo esc_attr('checked'); ?> name="ctrify_featured_images" value="yes">
                  <label for="ctrify_featured_images" class="toggle"><span class="toggle_handler"></span></label>
                </div>
                <p class="description">When Cloudinary API Keys are added at the bottom you will be able to activate this option. This will generate a new featured image for each post. Each image will be related to the text. Will be images found over the internet.  dynamically modified by Cloudinary.</p>
                <br><br>
                <div id="ctrify_cloudinary_options"></div>
              </td>
            </tr>
     

            <tr>
              <th scope="row">
                <label ><?php _e('Cloudinary Cloud Name', 'ctrify');  ?></label>
              </th>
              <td>
                <div class="">
                  <input name="ctrify_cloudinary_cname" type="text" id="ctrify_cloudinary_cname" value="<?php echo esc_attr((isset($this->ctrify_settings['cloudinary_cname']) ?$this->ctrify_settings['cloudinary_cname'] : ''))?>" class="regular-text ctrify_cloud_key">
                </div>

              </td>
            </tr>

            <tr>
              <th scope="row">
                <label ><?php _e('Cloudinary Key', 'ctrify');  ?></label>
              </th>
              <td>
                <div class="">
                  <input name="ctrify_cloudinary_key" type="text" id="ctrify_cloudinary_key" value="<?php echo esc_attr((isset($this->ctrify_settings['cloudinary_key']) ?$this->ctrify_settings['cloudinary_key'] : ''))?>" class="regular-text ctrify_cloud_key">
                </div>

              </td>
            </tr>

            <tr>
              <th scope="row">
                <label><?php _e('Cloudinary Secret', 'ctrify');  ?></label>
              </th>
              <td>
                <div class="">
                  <input name="ctrify_cloudinary_secret" type="text" id="ctrify_cloudinary_secret" value="<?php echo esc_attr((isset($this->ctrify_settings['cloudinary_secret']) ?$this->ctrify_settings['cloudinary_secret'] : ''))?>" class="regular-text ctrify_cloud_key">
                </div>
                  <p class="description">You will have the option to automatically generate  images for each post <a target="_blank" href="https://cloudinary.com/invites/lpov9zyyucivvxsnalc5/fnhtgogdeouainam0hwg">Cloudinary Free and Pro plans</a> </p>
              
              </td>
            </tr>

           
          
          </tbody>
        </table>
        <p>
          <a id="ctrify_save_settings" name="submit" title="Save Changes" class="button button-primary ctrify_save_settings" style="font-size: 24px;" href="#">Save Changes</a>
         
        </p>
      </form>

   


     

      <br><br><br><h1><?php _e('Support', 'ctrify'); ?></h1><br>
       
      <p style="font-size: 18px;">Click contact to email us directly if you need technical assistance, or help with your billing or receipts.</p>
      
      <br>
      <a href="mailto:alberto@ctrify.com"><img src="<?php _e($this->plugin_url . 'img/alberto.png')?>" style="width: 100%;"></a>
      <a href="mailto:nathalie@ctrify.com"><img src="<?php _e($this->plugin_url . 'img/nathalie.png')?>" style="width: 100%;"></a>
      <a href="mailto:alejandra@ctrify.com"><img src="<?php _e($this->plugin_url . 'img/alejandra.png')?>" style="width: 100%;"></a>
    
    </div>
<?php
  } // settings_page_content





  /**
   * Echo selectors settings page
   *
   * @since 1.0.1
   *
   * @return null
   *
   */

  public function printKeywordsStatusCount($keywords){

      $totals['processed'] = 0;
      $totals['pending'] = 0;
      if(is_array($keywords))
      {
        foreach ($keywords as $key => $value){
          if($value['processed']=='')
          {
              $totals['pending']++;
           }else{
            $totals['processed']++;
          }
        }
      }
      
    return $totals;
  }

  public function printKeywords($keywords){

      $line = '';
      $limit = 4;
      if(is_array($keywords))
      {
        foreach ($keywords as $key => $value) {
          if($limit>0)$line .= $value['keyword'].', ';
          $limit--;
        }
      return mb_substr($line, 0, -2).(count($keywords)>4?', ...':'');
    }
    return $line;
  }

  public function campaigns(){

  ?>

      <form id="posts-filter" method="get">

            <br><h1><?php _e('Campaigns', 'ctrify'); ?></h1>
            <div class="tablenav top">
                <br class="clear">
            </div>
            <table class="wp-list-table widefat fixed striped table-view-list posts">
            <thead>
              <tr>
                <td id="cb" class="manage-column column-cb check-column">
                  <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                  <input id="cb-select-all-1" type="checkbox">
                </td>
                <th scope="col" class="manage-column column-title column-primary">Campaign</th>
                <th scope="col" class="manage-column column-author" style="width: 10%;">Categories</th>
                <th scope="col" class="manage-column column-author" style="width: 5%;">Locale</th>
                <th scope="col" class="manage-column column-author" style="width: 5%;">Translate</th>
                <th scope="col" class="manage-column column-author" style="width: 8%;">Limit</th>
                <th scope="col" class="manage-column column-author">Processed</th>
                <th scope="col" class="manage-column column-author">Pending</th>
                <th scope="col" class="manage-column column-author" style="width: 5%;">Status</th>
                <th scope="col" class="manage-column column-date" style="width: 6%;">Created</th>  
              </tr>
            </thead>

            <tbody id="the-list">

                  <?php if(isset($this->base['campaigns'])): foreach ($this->base['campaigns'] as $key => $camp):?>
                  
                  <tr id="post-<?php echo esc_attr($key)?>" class="iedit author-self level-0 post-1 type-post status-publish format-standard hentry category-uncategorized entry">
                        <th scope="row" class="check-column">     
                            
                            <input id="cb-select-<?php echo esc_attr($key)?>" class="campaignsins" type="checkbox" name="post[]" value="<?php echo esc_attr($key)?>">
                            
                        </th>
                        <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
                          
                          <strong><a class="row-title ctrify_noclick" href="#" ><?php echo esc_html(($camp['autoAnswear']!=''?'Auto Answer ':'Manual ').ucfirst($camp['type']))?>s Campaign</a></strong><?php echo esc_html(($camp['type']=='keyword'?$this->printKeywords($camp['keywords']):$camp['keyword'])); ?>
                           
                           <?php if($camp['status']!=0):?> <div class="row-actions">
                              <span class="edit ctrify_pausecampaign" capid="<?php echo esc_attr($key)?>"><a href="#">Pause</a> | </span><span class="ctrify_editcampaign" capid="<?php echo esc_attr($key)?>"><a href="#">Activate</a> | </span>  <?php if($camp['type']=='question'):?><span class="edit"><a qid="<?php echo esc_attr($key)?>" class="ctrify_viewquestions" href="#">View Questions</a> | </span><span class="trash"><a href="#" class="submitdelete ctrify_increaselimit" id="limit-<?php echo esc_attr($key)?>" capid="<?php echo esc_attr($key)?>" limit="<?php echo esc_attr($camp['max_questions'])?>">Get More Questions</a></span><?php else :?><span class="edit"><a qid="<?php echo esc_attr($key)?>" class="ctrify_viewkeywords" href="#">View Keywords</a> <?php endif;?>
                            </div>
                          <?php endif;?>
                            
                        </td>
                       
                        <td class="author column-author" data-colname="Categories">
                          <?php echo esc_html($this->getCategories()[$camp['wp_cat']])?>
                        </td>
                         <td class="author column-author" data-colname="Author">
                             <?php echo esc_html($camp['country'])?>
                        </td>
                         <td class="author column-author" data-colname="Author">
                             <?php echo esc_html($camp['wp_translate_to'])?>
                        </td>
                        <td class="author column-author" data-colname="Tags">
                          <span aria-hidden="true"><?php echo esc_html(($camp['type']=='question'?$camp['max_questions'].' questions':(isset($camp['keywords'])?count($camp['keywords']):'').' keywords'))?></span><span class="screen-reader-text"></span>
                        </td>
                        <td class="author column-author" data-colname="Tags">
                          <span aria-hidden="true"><?php echo esc_html(($camp['type']=='question'?$camp['processed_questions'].' questions':$this->printKeywordsStatusCount($camp['keywords'])['processed'].' keywords'))?></span><span class="screen-reader-text"></span>
                        </td>
                        <td class="author column-author" data-colname="Comments"> 
                          <span aria-hidden="true"><?php echo esc_html(($camp['type']=='question'?intval($camp['pending_questions']).' questions':$this->printKeywordsStatusCount($camp['keywords'])['pending'].' keywords'))?></span><span class="screen-reader-text"></span>
                        </td>
                        <td class="author column-author"><?php echo $this->status[$camp['status']]?></td> 
                        <td class="date column-date" data-colname="Date"><?php echo esc_html(date("d-m-Y", strtotime($camp['created'])))?></td>   
                  </tr>

                <?php endforeach; endif;?>
                       
             </tbody>

            <tfoot>
              <tr></tr>
            </tfoot>

          </table>

          <div class="tablenav bottom">
              <div class="alignleft actions bulkactions">
                <label for="bulk-action-selector-bottom" class="screen-reader-text">Select bulk action</label>
                  <select name="action2" id="bulk-action-selector-bottom">
                    <option value="-1">Bulk actions</option>
                    <option value="trash">Move to Trash</option>
                  </select>
                  <input type="submit" id="ctrify_doactioncampaign" class="button action" value="Apply">
              </div>
              <div class="alignleft actions"></div>
              <div class="tablenav-pages one-page"> </div>
              <br class="clear">
          </div>
              
      </form>


 <?php
  }


  public function licenses(){

    ?>
        <br><br>
        <form id="licenses-filter" method="get">
  
              <br><h1><?php _e('Licenses Usage', 'ctrify'); ?></h1>
              <div class="tablenav top">
                  <br class="clear">
              </div>
              <table class="wp-list-table widefat fixed striped table-view-list posts">
              <thead>
                <tr>
                  <td id="cb" class="manage-column column-cb check-column">
                   
                  </td>
                  <th scope="col" class="manage-column column-title column-primary">Licensed Domain</th>
                  <th scope="col" class="manage-column column-author" style="width: 30%;">License Key</th>
  
                  <th scope="col" class="manage-column column-author" style="width: 9%;">Credits Balance</th>
                 
                  <th scope="col" class="manage-column column-author" style="width: 8%;">Plugin Status</th>
                  <th scope="col" class="manage-column column-date" style="width: 8%;">Activation Date</th>  
                </tr>
              </thead>
  
              <tbody id="the-list">
  
                    <?php if(isset($this->base['licensed'])): foreach ($this->base['licensed'] as $domain => $camp):?>
                    
                    <tr id="post-<?php echo esc_attr($domain)?>" class="iedit author-self level-0 post-1 type-post status-publish format-standard hentry category-uncategorized entry">
                          <th scope="row" class="check-column">     
                              <?php if($this->get_domain_only(get_home_url())!=$domain):?>
                              <input id="cb-select-<?php echo esc_attr($domain)?>" class="domainsins" type="checkbox" name="post[]" value="<?php echo esc_attr($domain)?>">
                              <?php endif;?>
                          </th>

                          <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
                          
                          <strong><a class="row-title ctrify_noclick" href="#" ><?php echo esc_html($domain)?></a></strong>
                           
                           
                            
                        </td>
                        
                         
                          <td class="author column-author" data-colname="Categories">
                            <?php echo esc_html($camp['license_key'])?>
                          </td>
                           <td class="author column-author" data-colname="Author">
                               <?php echo esc_html(number_format( $camp['credits']))?>
                          </td>
                         
                        
                          <td class="author column-author"><?php echo $this->status_plugin[$camp['status']]?></td> 
                          <td class="date column-date" data-colname="Date"><?php echo esc_html(date("d-m-Y", strtotime($camp['created'])))?></td>   
                    </tr>
  
                  <?php endforeach; endif;?>
                         
               </tbody>
  
              <tfoot>
                <tr></tr>
              </tfoot>
  
            </table>
  
            <div class="tablenav bottom">
                <div class="alignleft actions bulkactions">
                  <label for="bulk-action-selector-credits" class="screen-reader-text">Select bulk action</label>
                    <select name="action3" id="bulk-action-selector-credits">
                      <option value="-1">Bulk actions</option>
                      <option value="credits">Transfer Credit Balance from selected to <?php echo $this->get_domain_only(get_home_url())?></option>
                    </select>
                    <input type="submit" id="ctrify_doactiontransfer" class="button action" value="Apply">
                </div>
                <div class="alignleft actions"></div>
                <div class="tablenav-pages one-page"> </div>
                <br class="clear">
            </div>
                
        </form>
  
  
   <?php
    }


    public function invoices(){

      ?>
          <br><br>
          <h1><?php _e('Invoices', 'ctrify'); ?></h1>
          <form id="invoices-tables" method="get" >
  
                <div class="tablenav top">
                    <br class="clear">
                </div>
                <table class="wp-list-table widefat fixed striped table-view-list posts">
                <thead>
                  <tr>
                    <td id="cb" class="manage-column column-cb check-column">
                     
                    </td>
                    <th scope="col" class="manage-column column-title column-primary">Item</th>
                    <th scope="col" class="manage-column column-author" style="width: 10%;">Invoice</th>

                    <th scope="col" class="manage-column column-author" style="width: 9%;">Method</th>
                    <th scope="col" class="manage-column column-author" style="width: 9%;">Amount</th>
                   
                    <th scope="col" class="manage-column column-author" style="width: 8%;">Status</th>
                    <th scope="col" class="manage-column column-date" style="width: 8%;">Invoice Date</th>  
                  </tr>
                </thead>
    
                <tbody id="the-list">
    
                      <?php if(isset($this->base['invoices'])): foreach ($this->base['invoices'] as $key => $value):?>
                      
                      <tr id="post-<?php echo esc_attr($value['id'])?>" class="iedit author-self level-0 post-1 type-post status-publish format-standard hentry category-uncategorized entry">
                            <th scope="row" class="check-column">     
                               
                            </th>
  
                            <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
                            
                              <strong><a class="row-title ctrify_noclick" href="#" ><?php echo esc_html($value['long'])?></a></strong>
                             
                      
                            </td>
                          
                           
                            <td class="author column-author" data-colname="Categories">
                            <a target="_blank" class="row-title" href="https://www.ctrify.com/invoices/<?php echo esc_html($value['id'])?>">View Invoice</a>
                            </td>
                            <td class="author column-author" data-colname="Author">
                                  <?php echo esc_html($value['payment'])?>
                            </td>
                             <td class="author column-author" data-colname="Author">
                                  $<?php echo esc_html(number_format( $value['amount']))?> USD
                            </td>
                            <td class="author column-author"><?php echo $this->status_invoice[$value['status']]?></td> 
                            <td class="date column-date" data-colname="Date"><?php echo esc_html(date("d-m-Y", strtotime($value['date'])))?></td>   
                      </tr>
    
                    <?php endforeach; endif;?>
                           
                 </tbody>
    
                <tfoot>
                  <tr></tr>
                </tfoot>
    
              </table>
    
            
                  
          </form>
    
    
     <?php
    }

  /**
  * Adds "Request CTRify Post Rebuild" button on posts list page
   *
   * @since 1.0.3
   *
   * @return null
   *
 */
  public function addRequestRebuildButton(){
   
    global $current_screen;

    if ('page' != $current_screen->post_type && 'post' != $current_screen->post_type) {
        return;

    }else{

        wp_register_script('ctrify-script', plugins_url('js/ctrify-script.js', __FILE__) );
        wp_enqueue_script('ctrify-script');

        wp_enqueue_style('ctrify-sweetalert2-style', plugins_url('css/sweetalert2.min.css', __FILE__));
        wp_enqueue_style('ctrify-sweetalert2-style');

        wp_enqueue_script('ctrify-sweetalert2', plugins_url('js/sweetalert2.min.js?'.time(), __FILE__));
        wp_enqueue_script('ctrify-sweetalert2');


        wp_localize_script('ctrify-script', 'ctrify', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'loading_icon_url' => plugins_url('img/loading-icon.png', __FILE__),
        'is'=>array('xsite'=>$this->get_domain_only(get_home_url()),'xkey'=>get_option('admin_email'),'xpath'=>$_SERVER['PHP_SELF']),
        'xsite'=> $this->get_domain_only(get_home_url()),
        'xkey'=> get_option('admin_email'),
        'xpath'=>$_SERVER['PHP_SELF'],
        'save_success' => __('<div style="line-height: 35px;">Changes Scheduled!</div>', 'ctrify'),
        'notfound' => __('<div style="line-height: 35px;">Sorry...<br><br> Looks like this post was already regenerated or, we have not generated this post, or the campaign has been deleted.</div>', 'ctrify'),
        'rebuild_limit' => __('<div style="line-height: 26px;font-size: 21px;text-align: justify;">You have reached the maximum amount of Post regenerations! We are sorry that you aren\'t satisfied with the outcome. <br><br>Please contact us to see if we can help you get a better result.</div>', 'ctrify'),
        'undocumented_error' => __('<div style="line-height: 35px;">An undocumented error has occurred. Please refresh the page and try again.</div>', 'ctrify'),
      ));


    }

    ?>
        <script type="text/javascript">
            jQuery(document).ready( function($)
            {   
               
                var list = jQuery(".row-actions");

                for (var i = list.length - 1; i >= 0; i--) {
                  
                  var newEl = document.createElement('span');
                  newEl.className = "trash";
                  newEl.innerHTML = ' | <a href="#" class="requestctriftreview" rel="review" aria-label="Request CTRify Post Rebuild">Request CTRify Post Rebuild</a>';
                  list[i].appendChild(newEl);

                }                
            
            });
        </script>
    <?php
  }

  /**
   * Perform the redirect to HTTPS if loaded over HTTP
   *
   * @since 1.0.1
   *
   * @return null
   *
   */
  public function ctrify_core(){

    if (!is_ssl()) {
      wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);
      exit();
    }
  } // ctrify_core


  /**
   * Check if user has the minimal WP version required by CTRify
   *
   * @since 1.0.1
   *
   * @return bool
   *
   */
  public function check_wp_version($min_version){

    if (!version_compare(get_bloginfo('version'), $min_version,  '>=')) {
      add_action('admin_notices', array($this, 'notice_min_wp_version'));
      return false;
    } else {
      return true;
    }
  } // check_wp_version
 
  public function getCategories(){

    $cats = get_categories(array('hide_empty'=>0));
    $categories = array();
    foreach($cats as $category) {

      $categories[$category->term_id] = $category->name;
    }

    return $categories;
  } // check_wp_version

  /**
   * Display error message if WP version is too low
   *
   * @since 1.0.1
   *
   * @return null
   *
   */
  public function notice_min_wp_version(){

    echo '<div class="ctrifynotice notice notice-error is-dismissible"><p>' . sprintf(__('CTRify plugin <b>requires WordPress version 4.6</b> or higher to function properly. You are using WordPress version %s. Please <a href="%s">update it</a>.', 'ctrify'), get_bloginfo('version'), admin_url('update-core.php')) . '</p></div>';
  } // notice_min_wp_version_error


  /**
   * Display error
   *
   * @since 1.0.1
   *
   * @return null
   *
   */
  public function notice_error(){

    echo '<div class="ctrifynotice notice notice-error is-dismissible"><p>' . sprintf(__($this->customerror.'. Please contact wp@ctrify.com for support.', 'ctrify'), get_bloginfo('version'), admin_url('update-core.php')) . '</p></div>';
  } // notice_error
  
   /**
   * Display new update notice
   *
   * @since 1.0.1
   *
   * @return null
   *
   */

  public function new_update_notice() {

    if(isset($this->base['update'])&&$this->base['update']['version']!=$this->version):
    ?>
    <div class="ctrifynotice notice notice-error is-dismissible">
      <p style="font-weight:700">New update Available for CTRify Plugin</p>
      <?php echo esc_html($this->base['update']['log'])?>
      <p><a href="<?php echo esc_url($this->base['update']['url'])?>" class="button-primary upgrade">Update Now</a>&nbsp;&nbsp;&nbsp;<a href="<?php echo admin_url('admin.php').'?page=ctrify-settings'?>" class="button-secondary">Go to Plugin Settings</a></p>
        
    </div>
    <?php endif;
  }
 

  /**
   * Send the HTTP Strict Transport Security (HSTS) header.
   *
   * @since 1.0.1
   *
   * @return null
   */
  public function to_strict_transport_security(){

    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
  } // to_strict_transport_security


  /**
   * Save plugin settings received via AJAX
   *
   * @since 1.0.1
   *
   * @return null
   */


  public function ajax_save_setting(){

    check_ajax_referer('save_settting_nonce_action');

     $paypal_email = isset($_POST['ctrify_paypal_email']) ? sanitize_email($_POST['ctrify_paypal_email']) : '';
     $cloudinary_key = isset($_POST['ctrify_cloudinary_key']) ? sanitize_text_field($_POST['ctrify_cloudinary_key']) : '';
     $cloudinary_secret = isset($_POST['ctrify_cloudinary_secret']) ?sanitize_text_field($_POST['ctrify_cloudinary_secret']) : '';
     $cloudinary_cname = isset($_POST['ctrify_cloudinary_cname']) ? sanitize_text_field($_POST['ctrify_cloudinary_cname']) : '';
     $deepl_key = isset($_POST['ctrify_deepl_key']) ? sanitize_text_field($_POST['ctrify_deepl_key']) : '';
     $openai_key = isset($_POST['ctrify_openai_key']) ? sanitize_text_field($_POST['ctrify_openai_key']) : '';
     $publish_newposts = isset($_POST['ctrify_publish_newposts'])||isset($_POST['ctrify_license_key']) ?'yes' : 'no';
     $process_answears = isset($_POST['process_answears']) ?'yes' : 'no';
     $schedule_newposts = isset($_POST['ctrify_schedule_newposts']) ?sanitize_text_field($_POST['ctrify_schedule_newposts']) : 'no';
     
     $include_links = isset($_POST['include_links'])||isset($_POST['ctrify_license_key']) ?'yes' : 'no';
     $featured_images = isset($_POST['ctrify_featured_images']) ? 'yes' : 'no';
     $posttypeselec = isset($_POST['posttypeselec']) ? sanitize_text_field($_POST['posttypeselec']) : 'post';
     $authorselec = isset($_POST['authorselec']) ? sanitize_text_field($_POST['authorselec']) : get_current_user_id();
     $cloudinaryeffect =  isset($_POST['ctrify_cloudinary_image_options']) ? sanitize_text_field($_POST['ctrify_cloudinary_image_options']) : 'e_improve'; 
     $license_key = isset($_POST['ctrify_license_key']) ? sanitize_text_field($_POST['ctrify_license_key']) : $this->ctrify_settings['license_key'];
     

    $ctrify_settings = array(
      'posttypeselec'=>$posttypeselec,
      'authorselec'=>$authorselec,
      'paypal_email'=>$paypal_email,
      'cloudinary_key' => $cloudinary_key,
      'cloudinary_secret' => $cloudinary_secret,
      'cloudinary_cname' => $cloudinary_cname,
      'deepl_key' => $deepl_key,
      'openai_key' => $openai_key,
      'publish_newposts'=>$publish_newposts,
      'schedule_newposts'=>$schedule_newposts,
      'process_answears'=>$process_answears,
      'featured_images'=>$featured_images,
      'include_links'=>$include_links,
      'cloudinaryeffect'=>$cloudinaryeffect,
      'security_key'=>$this->base['security_key'],
      'license_key'=>$license_key,
      'plugin_path' => $this->realpath().$_SERVER['PHP_SELF']
    );
    update_option(WPCTRIFY_OPTIONS_KEY, $ctrify_settings);

    wp_send_json_success();
  } // ajax_save_setting


 


  /**
   * Perform a POST request
   *
   * @since 1.0.1
   *
   * @param string $url
   *
   * @return array $response
   */

  public function make_request($url = null, $post = null){

    if (empty($url)) {
      $url = home_url(false, 'https');
    }

    $args = array(
      'timeout' => 5,
      'httpversion' => '1.1',
      'sslverify' => true,
    );

    if($post===null)
    { 

      $response = wp_remote_get($url,  $args);

    }else{

      $args['body'] = $post;
      $response = wp_remote_post($url, $args);
    }
    

    $error = '';
    if (is_wp_error($response)) {
      $error = $response->get_error_message();
    }

    $out  = array(
      'code' => wp_remote_retrieve_response_code($response),
      'body' => wp_remote_retrieve_body($response),
      'error' => $error
    );

    return $out;
  } // make_request


  /**
   * Check if currently on CTRify settings page
   *
   * @since 1.0.1
   *
   * @return bool is on CTRify settings page
   */
  public function is_plugin_page(){

    if ( !function_exists( 'get_current_screen' ) ) { 
       require_once ABSPATH . '/wp-admin/includes/screen.php'; 
    } 

    $current_screen = get_current_screen();
    if ($current_screen->id === 'toplevel_page_ctrify-settings') {
      return true;
    } else {
      return false;
    }
  } // is_plugin_page


  /**
   * Create Silo
   *
   * @since 1.3.0
   *
   * 
   */


   public function createSilo($post){

  
  
    $silo = $post->silo;
    $sila = array(); 
  
    foreach ($silo->content_silo_plan as $c => $cat) {
      
      $category = $cat->category;
  
      $cat_term_info = wp_insert_term($category, 'category');
  
      if (is_wp_error($cat_term_info)) {
          continue; 
      }
  
      $cat_term_id = $cat_term_info['term_id'];
  
     
      $sila[$cat_term_id] = array(
        'term_id' => $cat_term_id,
        'name' => $category,
        'children' => array()
      );
  
      foreach ($cat->subcategories as $k => $sub) {
        $subcategory = $sub->subcategory;
  
       
        $sub_term_info = wp_insert_term($subcategory, 'category', array('parent' => $cat_term_id));
  
        if (is_wp_error($sub_term_info)) {
            continue; 
        }
  
        $sub_term_id = $sub_term_info['term_id'];
  
  
        $sila[$cat_term_id]['children'][$sub_term_id] = array(
          'term_id' => $sub_term_id,
          'name' => $subcategory,
          'children' => array()
        );
  
        foreach ($sub->subtopics as $t => $topic) {
  
          $topic_term_info = wp_insert_term($topic, 'category', array('parent' => $sub_term_id));
  
          if (is_wp_error($topic_term_info)) {
              continue; 
          }
  
          $topic_term_id = $topic_term_info['term_id'];
  
          $sila[$cat_term_id]['children'][$sub_term_id]['children'][$topic_term_id] = array(
            'term_id' => $topic_term_id,
            'name' => $topic
          );
        }
      }
    }
  
    return json_encode($sila); 
  }
  
  /**
   * Link Post to Term
   *
   * @since 1.3.3
   *
   */
  

  public function asocPost($post) {

    $post_id = $post->post_id;
    $term_id_id = $post->cat_id;
  
    if (term_exists($term_id, 'category')) {
        
        $result = wp_set_post_terms($post_id, array($term_id), 'category', true);
        if (is_wp_error($result)) {   
          return false;
        }
        return true;
    } else {
        return false;
    }
  }



   /**
   * Convert old cats to tags
   *
   * @since 1.3.0
   *
   */
  
  public function convertOldCatsToTags() {
  
    $categories = get_terms( array('taxonomy' => 'category', 'hide_empty' => false) );
  
    
    if (is_wp_error($categories)) {
        return; 
    }
  
    foreach ($categories as $category) {
      wp_update_term($category->term_id, 'category', array('taxonomy' => 'post_tag'));
    }
  }





  /**
   * Change admin footer text to show plugin information
   *
   * @since 1.0.1
   *
   * @param string $text_org original footer text
   *
   * @return string footer text html
   */
  public function admin_footer_text($text_org){

    if (false === $this->is_plugin_page()) {
      return $text_org;
    }

    $text = '<i><a target="_blank" href="' . $this->generate_web_link('admin_footer','wordpress') . '">CTRify</a> v' . $this->version . ' by <a href="https://www.ctrify.com/" title="' . __('Visit our site to get more great plugins', 'ctrify') . '" target="_blank">ExcursionPass Inc</a>.';
    $text .= ' Please <a target="_blank" href="https://wordpress.org/support/plugin/ctrify/reviews/#new-post" title="' . __('Rate the plugin', 'ctrify') . '">' . __('Rate the plugin ★★★★★', 'ctrify') . '</a>.</i> ';
    return $text;
  } // admin_footer_text


  /**
   * Generate web link
   *
   * @since 1.0.1
   *
   * @return string link html
   */
  public function generate_web_link($placement = '', $page = '/', $params = array(), $anchor = ''){

    $base_url = 'https://www.ctrify.com';
    if ('/' != $page) {
      $page = '/' . trim($page, '/');
    }
    if ($page == '//') {
      $page  =  '/';
    }

    $parts = array_merge(array('utm_source' => 'ctrify', 'utm_medium' => 'plugin', 'utm_content' => $placement, 'utm_campaign' => 'ctrify' . $this->version), $params);
    if (!empty($anchor)) {
      $anchor = '#' . trim($anchor, '#');
    }

    $out = $base_url . $page . '?' . http_build_query($parts, '', '&amp;') . $anchor;
    return $out;
  } // generate_web_link


  /**
   * Activation hook, check if trying to activate on WP Network and exit if that's the case
   *
   * @since 1.0.1
   *
   * @return null
   */
  public function activate(){

    // Bail if activating from network, or bulk
    if (is_network_admin() || isset($_GET['activate-multi'])) {
      wp_die(__('Sorry, CTRify is currently not compatible with WPMU.', 'ctrify'));
    }

  } // activate


  /**
   * Redirect to CTRify settings page on activation
   *
   * @since 1.0.1
   *
   * @return null
   *
   */
  public function redirect_to_settings_page($plugin){

    if ($plugin == plugin_basename(__FILE__)) {
      wp_safe_redirect(
        add_query_arg(
          array(
            'page' => 'ctrify-settings'
          ),
          admin_url('admin.php')
        )
      );
      exit();
    }
  } // redirect_to_settings_page


  /**
   * Clean-up on uninstall
   *
   * @since 1.0.1
   *
   * @return null
   */
  public function uninstall(){
    
    $this->apiCall('uninstall');
    $this->get("DROP TABLE `ctrify_temp`");
    delete_option(WPCTRIFY_OPTIONS_KEY);
    delete_option(WPCTRIFY_META_KEY);
   
  } // uninstall

  public function deactivate(){

    $this->apiCall('deactivate');
    $this->get("DROP TABLE `ctrify_temp`");
    delete_option(WPCTRIFY_OPTIONS_KEY);
    delete_option(WPCTRIFY_META_KEY);
    

  } // deactivate
  /**
   * Disabled; we use singleton pattern so magic functions need to be disabled.
   *
   * @since 1.0.1
   *
   * @return null
   */
  private function __clone()
  { }


  /**
   * Disabled; we use singleton pattern so magic functions need to be disabled.
   *
   * @since 1.0.1
   *
   * @return null
   */
  public function __sleep()
  { }


  /**
   * Disabled; we use singleton pattern so magic functions need to be disabled.
   *
   * @since 1.0.1
   *
   * @return null
   */
  public function __wakeup()
  { }
  // end class
}
   

$ctrify = wpCTRify::getInstance();

add_action('plugins_loaded', array($ctrify, 'plugins_loaded'));
add_action('activated_plugin', array($ctrify, 'redirect_to_settings_page'));
register_activation_hook(__FILE__, array($ctrify, 'activate'));
register_deactivation_hook(__FILE__, array($ctrify, 'deactivate'));
register_uninstall_hook( __FILE__, 'uninstall');
add_action( 'admin_notices', array($ctrify, 'new_update_notice' ));
add_action( 'admin_notices', array($ctrify, 'show_admin_notices' ) );
add_action('admin_head-edit.php', array($ctrify, 'addRequestRebuildButton'));


