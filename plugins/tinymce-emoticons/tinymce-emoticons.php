<?php
 /*
 Plugin Name: TinyMCE Emoticons
 Version: 1.3
 Plugin URI: http://nazmurrahman.com/tinymce-emoticons-wordpress-plugin/
 Author: Nazmur Rahman
 Author URI: http://nazmurrahman.com/
 Description: Easy way to add emoticons in posts and pages
 */
 global $wp_version;
 $exit_msg='TinyMCE Emoticons requires WordPress 3.0 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a>';
 if (version_compare($wp_version,"3.0","<"))
 {
     exit ($exit_msg);
 }
 // Avoid name collisions.
 if ( !class_exists('TinyMCE_Emoticons') ) :
 class TinyMCE_Emoticons
 {

      // the plugin URL
     var $plugin_url;
     var $select_option;
     // Initialize WordPress hooks
     function TinyMCE_Emoticons()
     {
     $this->plugin_url = trailingslashit( WP_PLUGIN_URL.'/'.dirname( plugin_basename(__FILE__) ));
       $this->select_option = get_option('tinyemoopt');
        // print scripts action

        add_action('admin_print_scripts-post.php',  array(&$this,'scripts_action'));
        add_action('admin_print_scripts-page.php',  array(&$this,'scripts_action'));
        add_action('admin_print_scripts-post-new.php',  array(&$this,'scripts_action'));
        add_action('admin_print_scripts-page-new.php',  array(&$this,'scripts_action'));
        // add tinyMCE handlig
        add_action( 'init', array( &$this, 'add_tinymce' ));
        // admin_menu hook
        add_action('admin_menu', array( &$this, 'tinyemo_plugin_settings' ));
     }

     function add_tinymce()
 {

 wp_register_script( 'tinyemo', $this->plugin_url.'js/tinymce-emoticons.js' );
 if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) )
     return;
     if ( get_user_option('rich_editing') == 'true' )
     {
         add_filter( 'mce_external_plugins', array( &$this,'add_tinymce_plugin' ) );
         add_filter( 'mce_buttons', array( &$this,'add_tinymce_button' ));
     }
 }

 function add_tinymce_plugin( $plugin_array )
    {
        $plugin_array['tinyemo'] = $this->plugin_url.'js/tinyemo-mceplugin.js';
        return $plugin_array;
    }
    function add_tinymce_button( $buttons )
    {
        array_push( $buttons, "separator", 'btnTinyEmo' );
        return $buttons;
    }


     // Set up everything
     function install()
     {

     }

     function tinyemo_plugin_settings() {
$plugin_hook = add_submenu_page('options-general.php','TinyMCE Emoticons Settings','TinyMCE Emoticons','administrator','tinyemo_settings',array( &$this, 'tinyemo_display_settings' ));
add_action('admin_print_scripts-' . $plugin_hook, array(&$this,'scripts_action'));
}

function tinyemo_display_settings() {
$html = '<style>#tinyemo-options a.active img{border: 4px solid #21759b;;}</style><div class="wrap"><form action="options.php" method="post" name="options" id="tinyemo-options">';
$this->select_option = 'basic';
$this->select_option = get_option('tinyemoopt');
$html .= '<h2>TinyMCE Emoticons Settings</h2>
<p>Activate a set of emoticons you want to use and click save.</p>
' . wp_nonce_field('update-options') . '
<table class="form-table" width="100%" cellpadding="10">
<tbody>
<tr>
<td scope="row" align="left" style="width: 26%; vertical-align: top;">';
if($this->select_option == 'basic')
$html .= '<a href="#" class="active" title="basic emoticons" alt="basic"><img src="'.$this->plugin_url.'images/basic.jpg" /></a>';
else
$html .= '<a href="#" title="basic emoticons" alt="basic"><img src="'.$this->plugin_url.'images/basic.jpg" /></a>';
$html .= '</td>
<td scope="row" align="left" style="width: 28%; vertical-align: top;">';
if($this->select_option == 'animated')
$html .= '<a href="#" class="active" title="animated emoticons" alt="animated"><img src="'.$this->plugin_url.'images/animated.jpg" /></a>';
else
$html .= '<a href="#" title="animated emoticons" alt="animated"><img src="'.$this->plugin_url.'images/animated.jpg" /></a>';
$html .= '</td>
<td scope="row" align="left" style="vertical-align: top;">';
if($this->select_option == 'outlined')
$html .= '<a href="#" class="active" title="outlined emoticons" alt="outlined"><img src="'.$this->plugin_url.'images/outlined.jpg" /></a>';
else
$html .= '<a href="#" title="outlined emoticons" alt="outlined"><img src="'.$this->plugin_url.'images/outlined.jpg" /></a>';
$html .= '</td>
</tr>
</tr>
</table>
<input type="hidden" name="action" value="update" />
 <input type="hidden" name="tinyemoopt" id="option-value" value="basic" />
 <input type="hidden" name="page_options" value="tinyemoopt" />

 <input type="submit" name="Submit" value="Save" class="button button-primary"/></form>

</div>';

echo $html;

}

 // prints the scripts
     function scripts_action($hook)
     {

        $nonce=wp_create_nonce('tinyemo-nonce');
        wp_enqueue_script('jquery');
        wp_register_style( 'tinyemo-scroll-style', plugins_url('css/jquery.mCustomScrollbar.css', __FILE__) );
        wp_enqueue_style( 'tinyemo-scroll-style' );

         wp_enqueue_script('tinyemo-scroll', $this->plugin_url.'js/jquery.mCustomScrollbar.concat.min.js', array('jquery'));
         wp_enqueue_script('tinyemo', $this->plugin_url.'js/tinymce-emoticons.js', array('jquery'));
         wp_localize_script('tinyemo', 'tinyEmoSettings',array('tinyEmo_url' => $this->plugin_url, 'select_option' => $this->select_option, 'nonce' => $nonce));
     }
    }

    endif;

 if ( class_exists('TinyMCE_Emoticons') ) :
     $TinyMCE_Emoticons = new TinyMCE_Emoticons();
     if (isset($TinyMCE_Emoticons))
     {
         register_activation_hook( __FILE__,array(&$TinyMCE_Emoticons, 'install') );
     }
 endif;
 ?>