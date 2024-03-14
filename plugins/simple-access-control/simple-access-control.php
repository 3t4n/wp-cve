<?php
/*
Plugin Name: Simple Access Control
Plugin URI: http://devondev.com/blog/simple-access-control/
Description: Allows authors to restrict access to pages and posts to logged in users. 
Version: 1.6.0
Author: Peter Wooster
Author URI: http://www.devondev.com/
*/

/*  Copyright (C) 2011 Devondev Inc.  (http://devondev.com)

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
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


/* =========================================================================
 * Non-admin filters
 * these perform the actual changes seen by visiters
 * =========================================================================*/


/**
 * add filters used on non-admin screens
 * filter out pages from menus, replace content with message 
 */
add_filter('wp_get_nav_menu_items', 'sac_filter_nav_items');
add_filter('wp_list_pages_excludes', 'sac_pages_excludes');
add_filter('get_pages', 'sac_filter_pages');
add_filter('the_posts', 'sac_filter_pages');
add_action('template_redirect', 'sac_redirect');


/**
 * exclude locked pages from the list used in the default main menu
 * @global type $wpdb wordpress database access
 * @param type $excludes the list of excludes 
 * @return type the modified list of excludes
 */
function sac_pages_excludes($excludes){
    global $current_user, $wpdb;
    $sql = "SELECT post_id, meta_value from $wpdb->postmeta WHERE meta_key = 'sac_locked'";
    
    $rows = $wpdb->get_results($sql, ARRAY_A );
    $ids = array();    
    foreach($rows as $row) {
        if(!sac_allowed($row['post_id'], true, $row['meta_value'])){
            $ids[] = $row['post_id'];
        }
    }
    
    $excludes = array_merge($excludes, $ids);
    return $excludes;
}

/**
 * filter the navigation menu items. returns all if the user is logged in (ID > 0)
 * or those that do not have the sac_locked meta set if the user is not logged in 
 * 
 * @global type $current_user the object representing th ecurren user
 * @param type $items the menu items
 * @return type the filtered menu items
 */
function sac_filter_nav_items($items) {
    global $current_user;
    foreach($items as $item) {
        $allowed  = sac_allowed($item->object_id, true);   
        if($allowed)$filtered[] = $item;
    }
    
    return $filtered;
}


/**
 * replace the content of posts and pages with the message that the page is locked
 * will not reduce the list to empty, always returns at least one post
 * @global type $current_user
 * @param type $posts a list of posts or pages
 * @return type  the filtered list
 */
function sac_filter_pages($pages) {
    $filtered = array();
    foreach($pages as $page) {
        
        $allowed = sac_allowed($page->ID);
        $id = $page->ID;
        if($allowed) {
            $filtered[] = $page;
        } else {
            if($page->post_type == 'page') {
                $filtered[] = sac_set_locked_text($page);
            }
        }
    }
    if(!$filtered && $pages){
        $filtered[] = sac_set_locked_text($pages[0]);
    }
    return $filtered;
}

/* 
 * check for direct access to page or post 
 * and produce 404 if requested
 */
function sac_redirect() {
    global $post;
    $pid = $post->ID;
    $allowed = sac_allowed($post->ID);
    error_log("allowed=$allowed");
    if($allowed)return;
    
    if (sac_force_404($post->ID)) {
        error_log("forcing 404");
        status_header( 404 );
        nocache_headers();
        include( get_query_template( '404' ) );
        die();
    }
}


/**
 * set the text for pages that should not be displayed, also turns off comments
 * @global type $current_user
 * @param type $page
 * @return type 
 */
function sac_set_locked_text($page) {
    global $current_user;
    if(0 < $current_user->ID) {
        $page->post_content = sac_build_loginout(get_option('sac_logout_text', sac_default_logout_text()), 'logout');
    } else {
        $page->post_content = sac_build_loginout(get_option('sac_locked_text', sac_default_login_text()), 'login');
    }
    $page->comment_status = 'close';
    return $page;
}

/**
 * build the content for a login or logout message
 * @param type $value the test string for the message
 * @param type $action either login or logout
 * @return type the completed string
 */
function sac_build_loginout($value, $action='login') {
    if(false === strpos($value, $action))return $value;
    if($action == 'login')$url = wp_login_url($_SERVER['REQUEST_URI']);
    else $url = wp_logout_url($_SERVER['REQUEST_URI']);
    $anchor = '<a href="'.$url . '">'.$action.'</a>';
    $value = str_replace($action, $anchor, $value);
    return $value;
}


/**
 * determine if content is to be displayed
 * always display if in admin pages
 * otherwise display based on sac locked value and loggedin status
 * @global type $current_user
 * @param type $post_id of the content
 * @param type $menu true if this is for a menu 
 * @return type true if not restricted
 */
function sac_allowed($post_id, $menu=false, $value=null) {
    if(is_admin())return true;
    
    $logged_in = is_user_logged_in(); 
    if(!$value)$value = get_post_meta($post_id, 'sac_locked', true);
error_log("sac_allowed, post_id=$post_id, value=$value, logged_in=$logged_in");
    if(!$value)return true;

    $va = sac_clean_value($value);
    $value = $va[0];
    $showMenu = $va[1];
    
    if(!$showMenu || $showMenu != 'show menu')$showMenu = '';
    if($menu && $showMenu == 'show menu')return true;
    $restrict = false;
    if($value == 'not logged in' && $logged_in)$restrict=true;
    if($value == 'logged in' && !$logged_in)$restrict= true;
    error_log ("post_id=$post_id, restrict=$restrict");
    return !$restrict;
}

/*
 * sac_force_404
 * determine if a 404 error should be returned instead of the notice
 */
function sac_force_404($post_id) {
    $value = get_post_meta($post_id, 'sac_locked', true);
    $va = sac_clean_value($value);
    return $va[2];
}

/* =========================================================================
 * Page and post admin, used by authors, editors and administrators
 * filter the list of pages and posts
 * allow the user to set the locked status of a page or post
 * =========================================================================*/

/**
 * set up filers for the posts and pages lists
 * add action to add the locked column
 */    
add_filter('manage_posts_columns', 'sac_posts_columns');
add_filter('manage_pages_columns', 'sac_posts_columns');
add_action('manage_posts_custom_column',  'sac_show_columns');
add_action('manage_pages_custom_column',  'sac_show_columns');



/**
 * add the locked column and set its headre
 * @param array $columns list of columns
 * @return string list of columns with locked column added
 */
function sac_posts_columns($columns) {
    $columns['sac_locked'] = 'Simple Access Control';
    return $columns;
}

/**
 * display the content of the locked column
 * @global type $post the post for this row and column
 * @param type $name the name of the column
 */
function sac_show_columns($name) {
    sac_show_column_value($name, 'sac_locked');
}

/**
 * display the value for a column
 * @param type $name the column being processed
 * @param type $key the post meta key that should match the column
 */
function sac_show_column_value($name, $key) {
    global $post;
    if($name == $key) {
        $value =  sac_clean_value(get_post_meta($post->ID, $key));
        echo $value;
    }
}


    
/**
 * set up meta box support on the page and post edit pages
 */
add_action('add_meta_boxes', 'sac_add_metas');
add_action('save_post', 'sac_save_meta');

/**
 * add actions to create meta boxes, this must be delayed until the add_meta-box
 * these allow the user to set the locked status
 */
function sac_add_metas() {
    add_meta_box('sac_show_meta', 'Simple Access Control', 'sac_show_meta', 'post', 'side');
    add_meta_box('sac_show_meta', 'Simple Access Control', 'sac_show_meta', 'page', 'side');
}

/**
 * display the locked meta box 
 * @param type $post
 */
function sac_show_meta($post) {
    $id = $post->ID;
    $type= ucwords($post->post_type);
    $value = get_post_meta($id, 'sac_locked', true);
    $va = sac_clean_value($value);
    $users = $va[0];
    $menu= $va[1];
    $force = $va[2];
    
    // echo "<p>locked=$value, users=$users, menu=$menu</p>";
    $sel_all = $sel_li = $sel_nli = '';
    if($users == 'logged in')$sel_li = 'selected';
    else if ($users == 'not logged in')$sel_nli = 'selected';
    else $sel_all = 'selected';
    if($menu == 'show menu')$checked='checked'; else $checked='';
    if($force == 'yes')$forced='checked'; else $forced='';
    
    $html = <<<QEND
    <p><strong>Show this content to</strong></p>
    <select name="sac_locked">
        <option $sel_all value="all">All Users</option>
        <option $sel_li value="logged in">Users who are logged in</option>
        <option $sel_nli value="not logged in">Users who are not logged in</option>
    </select>
    <p><strong>Always show in menus</strong>
    <input type="checkbox" $checked name="sac_showmenu"/>
    </p> 
    <p><strong>Force 404 on direct access</strong>
    <input type="checkbox" $forced name="sac_force404"/>
    </p> 
QEND;
    echo $html;    
}

/**
 * process the meta box result data
 * @param type $post_id 
 */
function sac_save_meta($post_id) {
    $field = 'sac_locked';
    $old = sac_clean_value(get_post_meta($post_id, $field));
    
    if (isset($_POST['sac_locked']))$newUsers = $_POST['sac_locked']; else $newUsers = '';
    if (isset($_POST['sac_showmenu']))$newMenu = 'show menu'; else $newMenu = '';
    if (isset($_POST['sac_force404']))$force404= 'yes'; else $force404 = '';
    
    $new = sac_clean_value("$newUsers,$newMenu,$force404");
    
    $newV= implode(',', $new);
    $oldV = implode(',', $old);
    error_log("oldV=$old; newV=$new");
    if ($oldV != $newV) {
        if ($new[0] == 'all')delete_post_meta($post_id, $field);
        else update_post_meta($post_id, $field, $newV);
    }
}

function sac_clean_value($value) {
    if (!$value)$value = 'all,';
    if($value == 'yes')$value='logged in,';
    $value = explode(',', $value . ',,,');
    $users = $value[0];
    $menu = $value[1];
    $force = $value[2];
    if($users != 'logged in' && $users != 'not logged in')$users = 'all';
    if($menu != 'show menu')$menu = '';
    if($force != 'yes')$force = '';
    return array($users, $menu, $force);
}

/**
 * save the contents of a checkbox field into the postmeta with the field name as key
 * @param type $post_id
 * @param type $field 
 */
function sac_save_meta_checkbox($post_id, $field, $value='logged in') {
    $old = get_post_meta($post_id, $field, true);
    if(!$old)$old = 'no';
    if (isset($_POST[$field]))$new = $value; else $new = '';
    if ($old != $new) {
        if ($new == $value)update_post_meta($post_id, $field, $value);
        else delete_post_meta($post_id, $field);
    }
}

/* =========================================================================
 * Build settings used by administrators either as a separate page or as a 
 * section in the general settings page.
 * 
 * This code uses the new settings api
 * thanks to http://ottopress.com/2009/wordpress-settings-api-tutorial/ for the helpful tutorial
 * =========================================================================*/

/**
 * set up actions to link into the admin settings 
 */
// $sac_options_location = 'general';        // on the general settings page
$sac_options_location = 'sac_options'; // on a separate page

add_action('admin_init', 'sac_admin_init');
if($sac_options_location == 'sac_options') {
    add_action('admin_menu', 'sac_add_option_page');
}
    
/**
 * run when admin initializes
 * register our settings as part of the sac_options_group
 * add the section sac_options:sac_options_main
 * add the fields to that section
 */

function sac_admin_init() {
    global $sac_options_location;    
    
    register_setting('sac_options_group', 'sac_locked_text');
    add_settings_section  ('sac_options_main', '', 'sac_main_section_text', $sac_options_location);
    add_settings_field('sac_locked_text', 'Text to display in place of locked content', 'sac_locked_text', $sac_options_location, 'sac_options_main');
}



/**
 * action to add the custom options page, for users with manage_options capabilities 
 */
function sac_add_option_page() {
    add_options_page('Simple Access Control Settings', 'Simple Access Control',
            'manage_options', 'sac_options', 'sac_options_page');
}

/**
 * display the custom options page
 */
function sac_options_page() {
    ?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <h2>Simple Access Control Settings</h2>
        <form action="options.php" method="post">
            <?php settings_fields('sac_options_group');?>
            <?php do_settings_sections('sac_options')?>
            <p class="submit"> <input name="submit" class="button-primary" type="submit" value="Save changes"/></p>
        </form>
    </div>
    <?php
}

/**
 * display the section title, empty if separate page
 */
function sac_main_section_text() {
    global $sac_options_location;
    if($sac_options_location != 'sac_options'){
        echo "<strong>Simple Access Control</strong>";
    }
}

/**
 * display the form field for the locked page content text
 */
function sac_locked_text() {
    $value=trim(get_option('sac_locked_text', ''));
    if(!$value) $value = sac_default_login_text ();
    echo '<textarea id="sac_locked_text" name="sac_locked_text" rows="5" cols="40" >'.$value.'</textarea>';
}

function sac_default_login_text() {
    return '<h3>Please login to view this content</h3>';
}

function sac_default_logout_text() {
    return '<h3>Please logout to view this content</h3>';
}



/**
 * Logged In Text widget class
 *
 * @since 4.0
 */

/*
 * this code now requires the Text Widget code
 */
require_once ABSPATH . '/wp-includes/widgets/class-wp-widget-text.php';

/*
 * construct the widget by extending the Text Widget
 */
class Logggedin_Widget_Text extends WP_Widget_Text {
	function __construct() {
		parent::__construct();
		$this->id_base = 'litext';
		$this->name = __( 'Loggedin Text!' );
		$this->option_name = 'widget_' . $this->id_base;
		$this->widget_options['description'] = __( 'Text or HTML that shows when the user is logged-in', 'simple-access-control' );
		$this->control_options['id_base'] = $this->id_base;
	}

/*
 * Only show the widget when logged in
 */        
	function widget( $args, $instance ) {
		if(is_user_logged_in() ) parent::widget($args, $instance);
	}
}

/* Add our function to the widgets_init hook. */
add_action( 'widgets_init', 'sac_load_widgets' );

/* Function that registers our widget. */
function sac_load_widgets() {
	register_widget( 'Logggedin_Widget_Text' );
}


/* =========================================================================
 * end of program, php close tag intentionally omitted
 * ========================================================================= */
