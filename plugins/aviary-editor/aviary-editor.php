<?php
/*
Plugin Name: Aviary Editor
Plugin URI: http://en.bainternet.info
Description: A plugin that integrates The Awesome Aviary editor In the WordPress Media Library. 
Version: 0.3
Author: Bainternet
Author URI: http://en.bainternet.info
*/
/*
    *  Copyright (C) 2011-2014  Ohad Raz
    *  http://en.bainternet.info
    *  admin@bainternet.info

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/* Disallow direct access to the plugin file */
if (basename($_SERVER['PHP_SELF']) == basename (__FILE__)) {
  die('Sorry, but you cannot access this page directly.');
}

if (!class_exists("WP_EX_Aviary_editor")){
  /**
  * WP_EX_Aviary_editor
  * @author Ohad Raz
  * @since 0.1
  */
  class WP_EX_Aviary_editor
  {
    
    public $path;
    /**
     * Class Constructor
     * @author Ohad Raz
     * @since 0.1
     * @access public
     */
    public function __construct(){
      $this->path = plugins_url('aviary', __FILE__);
      //admin menu
      add_action('admin_menu', array($this,'create_options_page'));
      //options panel
      add_action('admin_init', array($this,'register_and_build_fields'));
      //media columns
      add_filter( 'manage_media_columns', array($this,'wh_column' ));
      add_action( 'manage_media_custom_column', array($this,'wh_value'), 10, 2 );
      add_filter( 'manage_media_columns', array($this,'aviary_column') );
      add_action( 'manage_media_custom_column', array($this,'aviary'), 10, 2 );
      //ajax save
      add_action('wp_ajax_aviary_save_ajax', array($this,'aviary_ajax_save'));
      //print JavaScript
      add_action('admin_print_scripts-upload.php',array($this,'aviary_js'));
      //plugin row meta
      add_filter( 'plugin_row_meta', array($this,'_my_plugin_links'), 10, 2 );


    }

    /**
     * _my_plugin_links 
     * @author Ohad Raz
     * @since 0.1
     * @access public
     * @param  array $links 
     * @param  string $file
     * @return array
     */
    public function _my_plugin_links($links, $file) {
        $plugin = plugin_basename(__FILE__); 
        if ($file == $plugin) // only for this plugin
          return array_merge( $links,
            array( '<a href="http://en.bainternet.info/category/plugins">' . __('Other Plugins by this author' ) . '</a>' ),
            array( '<a href="http://wordpress.org/support/plugin/aviary-editor">' . __('Plugin Support') . '</a>' ),
            array( '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=K4MMGF5X3TM5L" target="_blank">' . __('Donate') . '</a>' )
          );
        return $links;
    }

    /**
     * aviary_js
     * @author Ohad Raz
     * @since 0.1
     * @access public
     * @return void
     */
    public function aviary_js(){
      $options = get_option('aviary_options');

      ?>
      <!-- Load Feather code -->
      <script type="text/javascript" src="https://dme0ih8comzn4.cloudfront.net/js/feather.js"></script>
      <style>
      .avpw .avpw_text_input {
        -moz-box-sizing: inherit;
        -webkit-box-sizing: inherit;
        box-sizing: inherit;
      }
      </style>
      <!-- Instantiate Feather -->
      <script type="text/javascript">
        var AVIARY_CURRENT_IMAGE ='';

        function saved_new_image_aviary (imageID,newURL){
          featherEditor.showWaitIndicator();
          var data = {
            action: 'aviary_save_ajax',
            aviary_nonce: jQuery('#'+imageID).prev().prev().prev().val(),
            org_id: jQuery('#'+imageID).prev().val(),
            avaiary_url: newURL,
            fileFormat: '<?php echo (isset($options['fileFormat']))? $options['fileFormat'] : 'original';?>',
            oriImageFormat: AVIARY_CURRENT_IMAGE
          };
          jQuery.post(ajaxurl, data, function(response) {
            alert(response);
            featherEditor.close();
            location.reload();
          });
        }

        var featherEditor = new Aviary.Feather({
          apiKey       : '<?php echo(isset($options['apikey']))? $options['apikey'] : 'en';?>',
          apiVersion   : 3,
          tools        : 'all',
          theme        : '<?php echo(isset($options['editor_theme']))? $options['editor_theme'] : 'dark';?>',
          appendTo     : '',
          noCloseButton: false,
          fileFormat   : '<?php echo (isset($options['fileFormat']))? $options['fileFormat'] : 'original';?>',
          language     : '<?php echo (isset($options['aviavry_language']))? $options['aviavry_language'] : 'en'; ?>',
          cropPresets  : <?php echo $this->cropPresets().",";?>
          onSave: function(imageID, newURL) {
            saved_new_image_aviary(imageID, newURL);
          },
          onError: function(errorObj) {
            alert(errorObj.message);
          }
        });

        function launchEditor(id, src) {
          AVIARY_CURRENT_IMAGE = src.split('.').pop();
          featherEditor.launch({
            image: id,
            url: src
          });
          return false;
        }
    </script>

    <?php
    }

    /**
     * wh_column adds width and height column to media library
     * @author Ohad Raz
     * @since 0.1
     * @access public
     * @param  array $cols
     * @return array
     */
    public function wh_column( $cols ) {
      $cols["dimensions"] = __("Dimensions (w, h)");
      return $cols;
    }

    /**
     * wh_value renders width and height column to media library
     * @author Ohad Raz
     * @since 0.1
     * @access public
     * @param  string $column_name
     * @param  int $id          
     * @return void
     */
    public function wh_value( $column_name, $id ) {
      if ( $column_name == "dimensions" ){
        $meta = wp_get_attachment_metadata($id);
        if(isset($meta['width']))
          echo $meta['width'].' x '.$meta['height'];
      }
    }

    /**
     * aviary_column adds Aviary column to media library
     * @author Ohad Raz
     * @since 0.1
     * @access public
     * @param  array $cols
     * @return array
     */
    public function aviary_column( $cols ) {
      $cols["aviary"] = __("advanced edit");
      return $cols;
    }

    /**
     * aviary renders Aviary column to media library
     * @author Ohad Raz
     * @since 0.1
     * @access public
     * @param  string $column_name
     * @param  int $id          
     * @return void
     */
    public function aviary( $column_name, $id ) {
      if ( $column_name == "aviary" ){
        $image_attributes = wp_get_attachment_image_src( $id ,'full');
        wp_nonce_field('avaiary_saved'.$id,'aviary_nonce');
        ?>
          <input type="hidden" name="org_id" value="<?php echo $id; ?>">
          <img src="<?php echo $image_attributes[0]; ?>" style="display:none" id="aviary_<?php echo $id; ?>">
          <p><input type="image" src="<?php echo $this->path; ?>/edit-photo.png" value="Edit photo" onclick="return launchEditor('aviary_<?php echo $id; ?>', '<?php echo $image_attributes[0]; ?>');" /></p>
          <?php
      }
    }

    /**
     * aviary_ajax_save  function to save the new image
     * @author Ohad Raz
     * @since 0.1
     * @access public
     * @return void
     */
    public function aviary_ajax_save(){
      if (isset($_POST['org_id']))
        $action_name = 'avaiary_saved'.$_POST['org_id'];
      else{
        echo __('Sorry, your nonce did not verify.');
        die();
      }
      if ( empty($_POST) || !isset($_POST['aviary_nonce']) || !isset($_POST['avaiary_url']) ||  !wp_verify_nonce($_POST['aviary_nonce'],$action_name) ){
        echo __('Sorry, your nonce did not verify.');
        die();
      }else{
        $url = $_POST['avaiary_url'];
        $tmp = download_url( $url );
        $n   = basename( $url );
        if (isset($_POST['fileFormat']) && $_POST['fileFormat'] != 'original'){
          $n = str_replace('.txt','.'.$_POST['fileFormat'],$n);
        }else{
          $n = str_replace('.txt','.'.$_POST['oriImageFormat'],$n);
        }
        $post_id = 0;
        $desc = "";

        $file_array = array(
            'name' => $n, //basename( $url ),
            'tmp_name' => $tmp
        );

        // If error storing temporarily, unlink
        if ( is_wp_error( $tmp ) ) {
          @unlink($file_array['tmp_name']);
          $file_array['tmp_name'] = '';
        }

        if (!function_exists('media_handle_sideload')){
          require_once(ABSPATH . "wp-admin" . '/includes/image.php');
          require_once(ABSPATH . "wp-admin" . '/includes/file.php');
          require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        }

        // do the validation and storage stuff
        $id = media_handle_sideload( $file_array, $post_id, $desc );

        // If error storing permanently, unlink
        if ( is_wp_error($id) ) {
          @unlink($file_array['tmp_name']);
          echo __('Sorry, Unknown error when storing image localy.');
          die();
        }

        $src = wp_get_attachment_url( $id );
        echo __('Image saved refreshing the page, to see it :).');
        die();
      }
    }
    /**
     * create_options_page
     * @author Ohad Raz
     * @since 0.1
     * @access public
     * @return void
     */
    public function create_options_page() {
       add_options_page(__('Aviary Options'), __('Aviary Options'), 'manage_options', __FILE__, array($this,'options_page_fn'));
    }

    /**
     * register_and_build_fields
     * @author Ohad Raz
     * @since 0.1
     * @access public
     * @return void
     */
    public function register_and_build_fields() {
       register_setting('aviary_options', 'aviary_options', array($this,'validate_setting'));
       add_settings_section('main_section', 'Main Settings', array($this,'section_cb'), __FILE__);
       add_settings_field('apikey', __('API-Key:'), array($this,'api_key_filed'), __FILE__, 'main_section'); 
       add_settings_field('aviavry_language', __('Editor Language:'), array($this,'widget_lnguage'), __FILE__, 'main_section');
       add_settings_field('editor_theme', __('Editor theme:'), array($this,'editor_theme_select'), __FILE__, 'main_section');
       add_settings_field('fileFormat', __('Saved File Format:'), array($this,'save_file_format'), __FILE__, 'main_section');
    }

    /**
     * options_page_fn
     * @author Ohad Raz
     * @since 0.1
     * @access public
     * @return void
     */
    public function options_page_fn() {
    ?>
      <div id="theme-options-wrap" class="widefat">
        <div class="icon32" id="icon-tools"></div>

        <h2><?php _e('Aviary Editor Options'); ?></h2>
        <p><?php _e('This will let you configure the Aviary image editor.');?></p>

        <form method="post" action="options.php" enctype="multipart/form-data">
          <?php settings_fields('aviary_options'); ?>
          <?php do_settings_sections(__FILE__); ?>
          <p class="submit">
            <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
          </p>
        </form>
      </div>
    <?php
    }

    /**
     * api_key_filed
     * @author Ohad Raz
     * @since 0.1
     * @access public
     * @return void
     */
    public function api_key_filed() {
      $options = get_option('aviary_options');
      echo "<input name='aviary_options[apikey]' type='text' value='{$options['apikey']}' />";
      echo "<br/>".__('To get your api key simply <a href="http://www.aviary.com/web-key" target="_blank">register here</a> for free');
    }

    
    /**
     * file format filed
     * @author Ohad Raz
     * @since 0.1
     * @access public
     * @return void
     */
    public function save_file_format() {
      $options = get_option('aviary_options');
      $items = array(
        'png'      => 'PNG',
        'jpg'      => 'JPG', 
        'original' => 'original'
      );

      echo "<select name='aviary_options[fileFormat]'>";
      foreach ($items as $key => $val) {
        $selected = ( $options['fileFormat'] === $key ) ? 'selected = "selected"' : '';
        echo "<option value='$key' $selected>$val</option>";
      }
      echo "</select>";
    }

    /**
     * editor_theme_select 
     * @author Ohad Raz
     * @since 0.3
     * @access public
     * @return void
     */
    function editor_theme_select(){
      $options = get_option('aviary_options');
      $items = array(
        'light' => 'Light theme',
        'dark'  => 'Dark theme'
      );

      echo "<select name='aviary_options[editor_theme]'>";
      foreach ($items as $key => $val) {
        $selected = ( $options['editor_theme'] === $key ) ? 'selected = "selected"' : '';
        echo "<option value='$key' $selected>$val</option>";
      }
      echo "</select>";
    }


    /**
     * language filed
     * @author Ohad Raz
     * @since 0.1
     * @access public
     * @return void
     */
    public  function widget_lnguage() {
      $options = get_option('aviary_options');
      $items = array(
        'en'      => 'English (default)',
        'bg'      => 'Bulgarian',
        'ca'      => ' Catalan',
        'zh_HANS' => 'Chinese (simplified)',
        'zh_HANT' => 'Chinese (traditional)',
        'cs'      =>  'Czech',
        'da'      => 'Danish',
        'nl'      => 'Dutch',
        'fi'      => 'Finnish',
        'fr'      => 'French',
        'de'      => 'German',
        'el'      => 'Greek',
        'he'      => 'Hebrew',
        'hu'      =>  'Hungarian',
        'id'      => 'Indonesian',
        'it'      => 'Italian',
        'ja'      => 'Japanese',
        'ko'      => 'Korean',
        'lv'      => 'Latvian',
        'lt'      => 'Lithuanian',
        'no'      => 'Norwegian',
        'pl'      => 'Polish',
        'pt'      => ' Portuguese',
        'pt_BR'   => 'Portuguese (Brazilian)',
        'ru'      => 'Russian',
        'es'      => 'Spanish',
        'sl'      => 'Slovak',
        'sv'      => 'Swedish',
        'tr'      => 'Turkish',
        'vi'      => 'Vietnamese'
      );
        
      echo "<select name='aviary_options[aviavry_language]'>";
      foreach ($items as $key => $val) {
        $selected = ( $options['aviavry_language'] === $key ) ? 'selected = "selected"' : '';
        echo "<option value='$key' $selected>$val</option>";
      }
      echo "</select>";
    }

    
    /**
     * validate_setting
     * @author Ohad Raz
     * @since 0.1
     * @access public
     * @return array
     */
    public function validate_setting($aviary_options) {
      return $aviary_options;
    }

    /**
     * section_cb 
     * @author Ohad Raz
     * @since 0.1
     * @access public
     * @return Void
     */
    public function section_cb() {}

    /**
     * cropPresets
     * @author Ohad Raz
     * @since 0.1
     * @access public
     * @return string
     */
    public function cropPresets(){
      global $_wp_additional_image_sizes;
      $image_sizes = get_intermediate_image_sizes();
      $labled = '';
      foreach ($image_sizes  as $_size){

        if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
          $labled .= "['".str_replace('_', ' ', $_size)."', '".get_option( $_size . '_size_w' ) ."x".get_option( $_size . '_size_h' )."'],"."\n";
        } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
          $labled .= "['".str_replace('_', ' ', $_size)."', '".$_wp_additional_image_sizes[ $_size ]['width'] ."x".$_wp_additional_image_sizes[ $_size ]['height']."'],"."\n";
        }        
      }
      //$labled."]";
      return "[
        'Original',
        $labled
        ['Square', '1:1'],
        'Custom',
        '3:2', '3:5', '4:3', '4:6', '5:7', '8:10', '16:9'
        \n]";
    }
  }//end class
}//end if
$aviary = new WP_EX_Aviary_editor;