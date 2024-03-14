<?php
/**
 * Plugin Name: WP Custom HTML Pages
 * Plugin URI: https://wit.rs/wordpress-plugins/wp-custom-html-pages/
 * Description: Display full custom HTML on custom permalink address, or put it inside content as a shortcode
 * Version: 0.6.2
 * Author: Milos Stojanovic
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 */


// Copyright (c), Milos Stojanovic

/*
WP Custom HTML Pages is free software: you can redistribute it and/or modify it 
under the terms of the GNU General Public License as published by the Free Software Foundation, 
either version 2 of the License, or any later version.

WP Custom HTML Pages is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with WP Custom HTML Pages. 
If not, see https://www.gnu.org/licenses/gpl-2.0.html .
*/
 
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Direct access not allowed.';
	exit;
}



register_activation_hook( __FILE__, 'WPCHTMLP_install' );
//register_activation_hook( __FILE__, 'WPCHTMLP_install_data' );

function WPCHTMLP_install($network_wide) {
  global $wpdb;
  if ( is_multisite() && $network_wide ) {
      $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
      foreach ( $blog_ids as $blog_id ) { //iterate sites in multisite
          switch_to_blog( $blog_id );
          WPCHTMLP_create_tables();
          WPCHTMLP_install_data();
          restore_current_blog();
      }
  } else { //single site, not network wide
      WPCHTMLP_create_tables();
      WPCHTMLP_install_data();
  }
  
}

function WPCHTMLP_create_tables() {
  global $wpdb;

    $table_name = $wpdb->prefix . "wpchtmlp_pages"; 

    $charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE $table_name (
  post_id mediumint(9) NOT NULL,
  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  name tinytext NOT NULL,
  html mediumtext NOT NULL,
  url varchar(55) DEFAULT '' NOT NULL,
  UNIQUE KEY post_id (post_id)
) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

function WPCHTMLP_install_data() {
    /*global $wpdb;
    
    $example_name = 'Mr. WordPress';
    $example_html = '<!DOCTYPE html><html><head><title>WP Custom HTML Pages plugin example page</title></head><body style="text-align:center;"><h1>Welcome to the example page of WP Custom HTML Pages plugin</h1><br><br></body></html>';
    $example_url = 'wp-custom-html-page-example';
    
    $table_name = $wpdb->prefix . 'wpchtmlp_pages';
    $wpdb->show_errors();
    $wpdb->insert( 
        $table_name, 
        array( 
            'post_id' => 0,
            'time' => current_time( 'mysql' ), 
            'name' => $example_name, 
            'html' => $example_html, 
            'url' => $example_url
        ) 
    );*/
}


add_action('admin_enqueue_scripts', 'WPCHTMLP_include_scripts');

function WPCHTMLP_include_scripts() {
    wp_register_style( 'wpchtmlp_style', plugins_url('css/style.css',__FILE__ ) );
    wp_enqueue_style( 'wpchtmlp_style' );
    
    if( get_option('wpchtmlp_opt_editor_type') != 2 ) {
        wp_register_script( 'wpchtmlp_script_ace', plugins_url('include/ace-builds-master/src-min-noconflict/ace.js',__FILE__ ));
        wp_enqueue_script('wpchtmlp_script_ace');
    }
}


function WPCHTMLP_page_post_type()
{
    register_post_type('wpchtmlp_page',
                       [
                           'labels'      => [
                               'name'          => __('HTML Pages'),
                               'singular_name' => __('HTML Page'),
                           ],
                           'public'      => true,
                           'has_archive' => true,
                           'rewrite'     => ['slug' => 'html-pages'],
                           'show_in_menu'   => 'edit.php?post_type=page',
                           'supports'   => ['title']
                       ]
    );
}
add_action('init', 'WPCHTMLP_page_post_type');

function WPCHTMLP_page_meta_box() {
	add_meta_box(
		'WPCHTMLP_page_meta_box', // $id
		'Page Settings', // $title
		'WPCHTMLP_page_meta_box_show', // $callback
		'wpchtmlp_page', // $screen
		'normal', // $context
		'high' // $priority
	);
}
add_action( 'add_meta_boxes', 'WPCHTMLP_page_meta_box' );
function WPCHTMLP_page_meta_box_show() {
	global $post;  
		$meta = get_post_meta( $post->ID, 'WPCHTMLP_page_meta_box', true ); ?>

	<input type="hidden" name="WPCHTMLP_page_meta_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

    <!-- WPCHTMLP fields -->
    
    <p>
	<label for="WPCHTMLP_page_meta_box[html_permalink]">Page Permalink (yourwebsite.com/page-permalink will link to the raw HTML page, the Wordpress default permalink isn't used.)</label>
	<br>
	<span><?php echo get_site_url()."/";?></span><input type="text" name="WPCHTMLP_page_meta_box[html_permalink]" id="WPCHTMLP_page_meta_box[html_permalink]" class="regular-text" value="<?php echo (isset($meta['html_permalink'])?$meta['html_permalink']:"enter-permalink-here"); ?>">
    </p>
    
    <p>
	<label for="WPCHTMLP_page_meta_box[html_code]">HTML Page Code</label>
	<br>
	<textarea name="WPCHTMLP_page_meta_box[html_code]" id="WPCHTMLP_page_meta_box[html_code]" rows="30" cols="90" style="width:1000px;<?php if( get_option('wpchtmlp_opt_editor_type') != 2 ) {?>display:none;<?php } ?>"><?php if(isset($meta['html_code'])) echo $meta['html_code']; ?>
<?php if(!isset($meta['html_code'])) {?>
 <!DOCTYPE html>
 <html>
 <head>
 <title>Page Title</title>
 <style type="text/css"></style>
 </head>
 <body>
 </body>
 </html>
<?php } ?></textarea>
    

    
    <?php if( get_option('wpchtmlp_opt_editor_type') != 2 ) {?>
    <div id="wpchtmlp_ace_editor"></div>
    <script>
        var wpchtmlp_ace_editor = ace.edit("wpchtmlp_ace_editor");
        wpchtmlp_ace_editor.setTheme("ace/theme/twilight");
        wpchtmlp_ace_editor.getSession().setMode("ace/mode/html");
        wpchtmlp_ace_editor.getSession().setValue(jQuery("#WPCHTMLP_page_meta_box\\[html_code\\]").text());
        //jQuery('#wpchtmlp_ace_editor').resizable();
        //jQuery('#wpchtmlp_ace_editor').on("resize", function() { wpchtmlp_ace_editor.resize() }); 
        wpchtmlp_ace_editor.getSession().on("change", function () {
            jQuery("#WPCHTMLP_page_meta_box\\[html_code\\]").val(wpchtmlp_ace_editor.getSession().getValue());
        });
    </script>
    <?php } ?>

    </p>
    

    
    
    <br>
    <p>
    <ul>
    <?php 
    if(isset($meta['html_permalink'])) {
        $perma_link = get_site_url()."/".$meta['html_permalink'];
        echo ($meta['html_permalink']?"<li><strong>Direct link to raw HTML page: </strong> <a href='".$perma_link."' target='_blank'>".$perma_link."</a></li>":"");
    }
    echo "<li><strong>Shortcode:</strong> [wpchtmlp id=".$post->ID."]</li>";
    ?>
    </ul>
    </p>
    
<?php }

function WPCHTMLP_page_meta_box_save( $post_id ) {   
	// verify nonce
	if ( !isset($_POST['WPCHTMLP_page_meta_nonce']) || !wp_verify_nonce( $_POST['WPCHTMLP_page_meta_nonce'], basename(__FILE__) ) ) {
		return $post_id; 
	}
	// check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}
	// check permissions
	if ( 'wpchtmlp_page' === $_REQUEST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		} elseif ( !current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}  
	}
	
	$old = get_post_meta( $post_id, 'WPCHTMLP_page_meta_box', true );
	$new = $_POST['WPCHTMLP_page_meta_box'];
    
    if(!$old) {
        //brand new. use insert
        global $wpdb;
        $table_name = $wpdb->prefix . 'wpchtmlp_pages';
        $wpdb->show_errors();
        $wpdb->insert( 
            $table_name, 
            array( 
                'post_id' => $post_id,
                'time' => current_time( 'mysql' ), 
                'name' => get_the_title( $post_id ), 
                'html' => $new['html_code'], 
                'url' => $new['html_permalink']
            ) 
        );
    }
    
	if ( $new && $new !== $old ) {
		update_post_meta( $post_id, 'WPCHTMLP_page_meta_box', $new );
        
        //now the database part
        if($old) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'wpchtmlp_pages';
            $wpdb->show_errors();
            $wpdb->update( 
                $table_name, 
                array( 
                    'time' => current_time( 'mysql' ), 
                    'name' => get_the_title( $post_id ), 
                    'html' => $new['html_code'], 
                    'url' => $new['html_permalink']
                ),
                array('post_id'=>$post_id)
            );
        }
        
	} elseif ( '' === $new && $old ) {
		delete_post_meta( $post_id, 'WPCHTMLP_page_meta_box', $old );
	}
}
add_action( 'save_post', 'WPCHTMLP_page_meta_box_save' );    

add_action( 'before_delete_post', 'WPCHTMLP_page_before_delete' );
function WPCHTMLP_page_before_delete( $post_id ){

    global $post_type;   
    if ( $post_type != 'wpchtmlp_page' ) return;
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpchtmlp_pages';
    $wpdb->delete( 
        $table_name, 
        array('post_id'=>$post_id)
    );
}

// template file for reading page within Wordpress templating system
add_filter('single_template', 'WPCHTMLP_page_template', 999);

function WPCHTMLP_page_template($single) {

    global $wp_query, $post;

    /* Checks for single template by post type */
    if ( $post->post_type == 'wpchtmlp_page' ) {
        if ( file_exists( PLUGIN_PATH . '/single-wpchtmlp_page.php' ) ) {
            return PLUGIN_PATH . '/single-wpchtmlp_page.php';
        }
    }

    return $single;

}


add_action('parse_request', 'WPCHTMLP_permalink_handler');
function WPCHTMLP_permalink_handler() {
    
    $query_uri = esc_url($_SERVER["REQUEST_URI"]);

    //if WP is in a multisite subdirectory
    if ( is_multisite() ) {
      $wp_path = get_blog_details()->path;
      $wp_path_length = strlen($wp_path);
      if( $wp_path_length > 1 ) {
        if(substr($query_uri, 0, $wp_path_length) === $wp_path) {
          $query_uri = substr($query_uri, $wp_path_length); // remove subfolder from request uri
        }
      } // if in subfolder
    } else { //if not multisite check if wp has subfolder in path
      $subfolder = "";
      $full_url = site_url(); //get root url with no trailing slash
      $full_url_arr = explode("//",$full_url);
      if(count($full_url_arr)>1) {
        $path_arr = explode("/",$full_url_arr[1]); //split everything after http(s):// with "/"
        if(count($path_arr)>1) { //more than one element, subfolder or path structure found
          array_shift($path_arr); //remove domain
          $wp_path = join("/",$path_arr) . "/"; //subdirectory, or join with / if multiple, add trailing slash
          $query_uri = substr($query_uri, strlen($wp_path)); // remove subdirectories(s) from request uri
        }
      }
    } // if in subdirectory
    

    if(strlen($query_uri) > 0 && $query_uri[0]=="/")  $query_uri = substr($query_uri, 1);
    
    if($query_uri=="wp-admin" && !get_option('wpchtmlp_opt_allow_wp-admin'))
        return;
    
    //handle requests with parameters
    $query_uri_params_string = "";
    $query_uri_params = [];
    //find occurance of ? unless if first char
    if(get_option('wpchtmlp_opt_filter_params') && strlen($query_uri) > 0 && strpos($query_uri, '?', 1) !== false) {
      $query_uri_arr = explode("?", $query_uri);

      $query_uri = $query_uri_arr[0]; //set proper uri to be everything before params

      if( count($query_uri_arr) > 1 ) {
        $query_uri_params_string = $query_uri_arr[1];
        if(strpos($query_uri_params_string, '&') !== false) {
          //parse parameters TODO
          $query_uri_params = explode("&", $query_uri_params_string);
        }
      } // if has parameters after '?'
    } // if opt enabled and has '?'

    if(get_option("wpchtmlp_ignore_trailing_slash"))
      if(strlen($query_uri)>1)
        if($query_uri[strlen($query_uri)-1]=="/")  $query_uri = substr($query_uri, 0, strlen($query_uri)-1); // remove trailing slash

    global $wpdb;
    $table_name = $wpdb->prefix . 'wpchtmlp_pages';
    $results = $wpdb->get_results( 
        $wpdb->prepare("SELECT * FROM `".$table_name."` WHERE `url`=%s", $query_uri), ARRAY_A
     );
     
     if(count($results)>0) {
       if(get_post_status( $results[0]['post_id'] ) == 'publish') {
         echo stripslashes($results[0]['html']);
         exit;
       }
       
     }
     
}

add_shortcode('wpchtmlp', 'WPCHTMLP_shortcode_handler');

function WPCHTMLP_shortcode_handler($atts = [], $content = null, $tag = '') {
    $atts = array_change_key_case((array)$atts, CASE_LOWER);
    $id = $atts['id'];
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpchtmlp_pages';
    $results = $wpdb->get_results( 
        $wpdb->prepare("SELECT * FROM `".$table_name."` WHERE `post_id`=%s", $id), ARRAY_A
     );
     
     if(count($results)>0) {
       if(get_post_status( $results[0]['post_id'] ) == 'publish') {
         $retStr = stripslashes($results[0]['html']);
       }
     }
    
    return $retStr;
}

add_filter('post_type_link', 'WPCHTMLP_permalink_custom_output', 10, 2 );

//Set WP's internal permalink to match custom one
function WPCHTMLP_permalink_custom_output($post_url, $post) {
  if ( 'wpchtmlp_page' === get_post_type( $post->ID ) ) {
      $meta = get_post_meta( $post->ID, 'WPCHTMLP_page_meta_box', true );
      if(isset($meta['html_permalink']))
        return get_site_url()."/".$meta['html_permalink'];
  }
  return $post_url; //otherwise, default permalink
}


add_action( 'admin_init', 'WPCHTMLP_admin_init' );

function WPCHTMLP_admin_init() {
    register_setting( 'wpchtmlp-options', 'wpchtmlp_opt_editor_type' );
    register_setting( 'wpchtmlp-options', 'wpchtmlp_opt_editor_style' );
    register_setting( 'wpchtmlp-options', 'wpchtmlp_opt_allow_wp-admin' );
    register_setting( 'wpchtmlp-options', 'wpchtmlp_opt_remove_table_on_uninstall' );
    register_setting( 'wpchtmlp-options', 'wpchtmlp_opt_filter_params' );
    register_setting( 'wpchtmlp-options', 'wpchtmlp_ignore_trailing_slash' );
}


add_action( 'plugins_loaded', 'WPCHTMLP_upgrade_check' );

function WPCHTMLP_upgrade_check() {

   $current_version = '0.6.0';
   $installed_version = get_option('WPCHTMLP_activity_log_version');
 
   if ( !$installed_version ) { //fresh install
       add_option('WPCHTMLP_activity_log_version', $current_version);
   } else if ( $installed_version != $current_version ) {
      //different version
    
         //upgrade db if v < 0.5 (html from text to medium text)
         /*if ( version_compare('0.5.0', $installed_version) ) {
             
            global $wpdb;
            $table_name = $wpdb->prefix . "wpchtmlp_pages"; 
            $charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE $table_name (
  post_id mediumint(9) NOT NULL,
  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  name tinytext NOT NULL,
  html mediumtext NOT NULL,
  url varchar(55) DEFAULT '' NOT NULL,
  UNIQUE KEY  (post_id)
) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );

         }*/
 
         //update version in db
         update_option('WPCHTMLP_activity_log_version', $current_version);
   }

}


add_action( 'admin_menu', 'WPCHTMLP_menu' );

function WPCHTMLP_menu() {
	add_options_page( 'WP Custom HTML Pages Options', 'WP Custom HTML Pages', 'manage_options', 'wpchtmlp-menu-identifier', 'WPCHTMLP_menu_options' );
}

function WPCHTMLP_menu_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
    echo '<h2>WP Custom HTML Pages Plugin Options</h2>';
	echo '<p>To add and work with custom HTML pages go to Pages->HTML Pages screen found in admin side menu.</p>';
    echo '<p>Custom URI and shortcode info is found on HTML page edit screen.</p>';
    /*echo '<p>To report plugin problems, review or submit feature requests <a href="https://witserbia.com/wordpress-plugins/wp-custom-html-pages/" target="_blank">go here</a>.</p>';*/
    /*echo '<p>Like the plugin? You can give it a good review or <a href="https://witserbia.com/wordpress-plugins/wp-custom-html-pages/" target="_blank">donate</a>.</p>';*/
    echo '<form method="post" action="options.php"> ';
    settings_fields( 'wpchtmlp-options' );
    do_settings_sections( 'wpchtmlp-options' );
    //0.5.1 added wpchtmlp_opt_filter_params
    //0.6.0 added wpchtmlp_ignore_trailing_slash
    echo '<table class="form-table">

        <tr valign="top">
        <th><td><h3>URI Options</h3></td></th>
        </tr>
        
        <tr valign="top">
        <th scope="row">Handle parameter queries</th>
        <td><input type="checkbox" name="wpchtmlp_opt_filter_params" id="wpchtmlp_opt_filter_params" value="1" '.checked(1,get_option('wpchtmlp_opt_filter_params'),false).' />
        <span>&nbsp;&nbsp;Check ON to have your permalinks work with variable parameters in URL ("..?params=example..."). Check OFF if your custom permalinks use "?" character.</span></td>
        </tr>

        <tr valign="top">
        <th scope="row">Ignore trailing slash</th>
        <td><input type="checkbox" name="wpchtmlp_ignore_trailing_slash" id="wpchtmlp_ignore_trailing_slash" value="1" '.checked(1,get_option('wpchtmlp_ignore_trailing_slash'),false).' />
        <span>&nbsp;&nbsp;Check ON to remove trailing slash in URL that was typed in ("website.com/url-with-trailing-slash/") when matching the URL with custom URIs in the database (recommended in most cases). Check OFF if you wish URLs with and without trailing slash to return different result.</span></td>
        </tr>
    
        <tr valign="top">
        <th><td><h3>Editor Options</h3></td></th>
        </tr>
        
        <tr valign="top">
        <th scope="row">Editor Type</th>
        <td>
        <input type="radio" name="wpchtmlp_opt_editor_type" value="1" '.checked(1, get_option('wpchtmlp_opt_editor_type'), false).'>ACE Editor&nbsp;&nbsp;
        <input type="radio" name="wpchtmlp_opt_editor_type" value="2" '.checked(2, get_option('wpchtmlp_opt_editor_type'), false).'>Textarea&nbsp;&nbsp;
        <span>&nbsp;&nbsp;Ace editor has syntax highlighting and error checking for HTML, CSS and JavaScript. Textarea is a common multirow input field.</span></td>
        </tr>
        
        <tr valign="top">
        <th><td><h3>Safety Options</h3></td></th>
        </tr>
        
        <tr valign="top">
        <th scope="row">Allow custom URI to be set as /wp-admin</th>
        <td><input type="checkbox" name="wpchtmlp_opt_allow_wp-admin" id="wpchtmlp_opt_allow_wp-admin" value="1" '.checked(1, get_option('wpchtmlp_opt_allow_wp-admin'),false).' />
        <span>&nbsp;&nbsp;Leave this OFF to prevent accidently locking yourself out of admin section. If you know what you are doing and wish to disable the admin login set it to ON.</span></td>
        </tr>
         
        <tr valign="top">
        <th><td><h3>Uninstallation Options</h3></td></th>
        </tr>
         
        <tr valign="top">
        <th scope="row">Completely remove WP Custom HTML Pages database table on uninstall</th>
        <td><input type="checkbox" name="wpchtmlp_opt_remove_table_on_uninstall" id="wpchtmlp_opt_remove_table_on_uninstall" value="1" '.checked(1,get_option('wpchtmlp_opt_remove_table_on_uninstall'),false).' />
        <span>&nbsp;&nbsp;If you check this ON, the database table used for mapping HTML pages to custom URI will be completely removed after uninstalling plugin. Set to ON only if you plan to never use this plugin again as all the custom URIs will permanently stopped working.</span></td>
        </tr>
    </table>';
    
    submit_button();
    echo '</form>';
	echo '</div>';
}

// Multisite

//On new multisite blog
function WPCHTMLP_on_crate_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
    if ( is_plugin_active_for_network( 'wp-custom-html-pages/wp-custom-html-pages.php' ) ) {
        switch_to_blog( $blog_id );
        WPCHTMLP_create_tables();
        WPCHTMLP_install_data();
        restore_current_blog();
    }
}
add_action( 'wpmu_new_blog', 'WPCHTMLP_on_crate_blog', 10, 6 );

//On drop tables for deleted multisite blog
function WPCHTMLP_on_delete_blog( $tables ) {
    global $wpdb;
    $tables[] = $wpdb->prefix . 'wpchtmlp_pages';
    return $tables;
}
add_filter( 'wpmu_drop_tables', 'WPCHTMLP_on_delete_blog' );
