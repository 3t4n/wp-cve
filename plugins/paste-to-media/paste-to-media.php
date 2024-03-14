<?php 

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              reci.pe
 * @since             0.0.5
 * @package           Paste_to_media
 *
 * @wordpress-plugin
 * Plugin Name:       Paste To Media
 * Description:       This is a plugin that allows to paste images and image url links directly to the Media Library
 * Version:           0.0.5
 * Author:            Davis @Reci.pe
 * Author URI:        reci.pe
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       paste-to-media
 * Domain Path:       /languages
 */


class Paste_to_media {

  public function __construct() {

    add_action( 'wp_ajax_paste_save', array( $this, 'paste_save_fn' ) );
    
    add_action( 'admin_enqueue_scripts', array($this ,'paste_enqueue_scripts'));

  }

  public function paste_enqueue_scripts()
  {
    wp_enqueue_script( 'paste-upload', plugin_dir_url( __FILE__ ) . 'paste-upload.js', array( 'jquery'), '0.0.2', false );

    $params = array(
      'ajaxurl' => admin_url('admin-ajax.php'),
      'ajax_nonce' => wp_create_nonce('paste_nonce')
    );
    wp_localize_script( 'paste-upload', "photo_upload", $params);
  }


  public function paste_save_fn()
  {
    check_ajax_referer( 'paste_nonce', 'security' );
    if( current_user_can('editor') || current_user_can('administrator') ) {
      $param = isset($_REQUEST['param']) ? filter_var($_REQUEST['param'], FILTER_SANITIZE_STRING) : '';
      $allowedMimes = array(
          'jpg|jpeg|jpe' => 'image/jpeg',
          'gif'          => 'image/gif',
          'png'          => 'image/png',
      );

      if($param == "add_image"){
        $pastepicture = $_FILES['file'];

        $fileInfo = wp_check_filetype(basename($pastepicture['name']), $allowedMimes);

        if (!empty($fileInfo['ext'])) {

          $wordpress_upload_dir = wp_upload_dir();

          $i = 1;
           
          $new_file_path = $wordpress_upload_dir['path'] . '/' . $pastepicture['name'];
          $new_file_mime = mime_content_type( $pastepicture['tmp_name'] );
           
          if( empty( $pastepicture ) )
            die( 'File is not selected.' );
           
          if( $pastepicture['error'] )
            die( $pastepicture['error'] );
           
          if( $pastepicture['size'] > wp_max_upload_size() )
            die( 'It is too large than expected.' );
           
          if( !in_array( $new_file_mime, get_allowed_mime_types() ) )
            die( 'WordPress doesn\'t allow this type of uploads.' );
           
          while( file_exists( $new_file_path ) ) {
            $i++;
            $new_file_path = $wordpress_upload_dir['path'] . '/' . $i . '_' . $pastepicture['name'];
          }
           
          if( move_uploaded_file( $pastepicture['tmp_name'], $new_file_path ) ) {
            $this->store_image_db($new_file_path, $new_file_mime, $pastepicture['name']);   
          }
        }
      }    
      if($param == "add_image_text"){
        $imgURL = filter_var($_REQUEST['string'], FILTER_SANITIZE_STRING);

        if (@getimagesize($imgURL)) {
          $url_info = pathinfo($imgURL);

          $imagedata = file_get_contents($imgURL);
          $file_info = new finfo(FILEINFO_MIME_TYPE);

          $mime_type = $file_info->buffer($imagedata);

          if(in_array($mime_type, $allowedMimes)){
            $basename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $url_info['basename']);
            $basename = mb_ereg_replace("([\.]{2,})", '', $basename);
            $extension = explode('/', $mime_type )[1];

            $filename = $basename . '.' . $extension;

            $wordpress_upload_dir = wp_upload_dir();
            $new_file_path = $wordpress_upload_dir['path'] . '/' . $filename;

            if( file_put_contents($new_file_path, $imagedata) ) {

              $new_file_mime = mime_content_type( $new_file_path );

              $this->store_image_db($new_file_path, $new_file_mime, $filename);
             
            }
          }
        }
      }
    }
  }

  private function store_image_db($new_file_path, $new_file_mime, $basename)
  {
    $upload_id = wp_insert_attachment( array(
      'guid'           => $new_file_path, 
      'post_mime_type' => $new_file_mime,
      'post_title'     => preg_replace( '/\.[^.]+$/', '', $basename ),
      'post_content'   => '',
      'post_status'    => 'inherit'
    ), $new_file_path );
   
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
   
    wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );
  }

}
new Paste_to_media();

?>