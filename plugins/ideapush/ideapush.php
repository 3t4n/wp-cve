<?php
/*
*		Plugin Name: IdeaPush - Feature Request Manager
*		Plugin URI: https://www.northernbeacheswebsites.com.au
*		Description: IdeaPush is a feature request management system for WordPress 
*		Version: 8.58
*		Author: Martin Gibson
*		Text Domain: ideapush   
*		Support: https://www.northernbeacheswebsites.com.au/contact
*		Licence: GPL2
*/

// Assign global variables
global $ideapush_is_pro;

if(file_exists(dirname( __FILE__ ).'/inc/pro-ip')){
    $ideapush_is_pro = "YES";    
} else {
    $ideapush_is_pro = "NO";    
}

//the first YES/NO in the array is if the feature is pro, the second YES/NO in the array is if a save settings button is necessary
global $ideapush_pro_features;
$ideapush_pro_features = array('Boards' => array('NO','YES'),'Notifications' => array('NO','YES'), 'Statuses' => array('NO','YES'), 'Design' => array('NO','YES'), 'Idea Form' => array('NO','YES'), 'Tag Page' => array('NO','YES'), 'IdeaPush Support' => array('NO','NO'),'IdeaPush Pro' => array('YES','YES'),'Integrations' => array('YES','YES'));

//get plugin version number
function idea_push_plugin_get_version() {
	if ( ! function_exists( 'get_plugins' ) )
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	$plugin_file = basename( ( __FILE__ ) );
	return $plugin_folder[$plugin_file]['Version'];
}


function idea_push_create_custom_post_type() {
    
    //SVG of the icon
    $menu_icon_svg = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIyLjAuMSwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMiIgYmFzZVByb2ZpbGU9InRpbnkiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIgoJIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMjAgMjAiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8cGF0aCBmaWxsPSIjNzhDMTQ4IiBkPSJNMTAsMEM0LjUsMCwwLDQuNSwwLDEwczQuNSwxMCwxMCwxMHMxMC00LjUsMTAtMTBTMTUuNSwwLDEwLDB6IE04LjEsMTIuMWwtMS42LDEuNQoJYy0wLjIsMC4yLTAuNCwwLjMtMC43LDAuM2MtMC4zLDAtMC41LTAuMS0wLjctMC4zbC0xLjYtMS41Yy0wLjItMC4yLTAuMi0wLjYsMC0wLjljMC4yLTAuMiwwLjYtMC4yLDAuOSwwbDEuNCwxLjRsMS40LTEuNAoJQzcuMywxMS4xLDcuNSwxMSw3LjYsMTFzMC4zLDAuMSwwLjQsMC4yQzguMywxMS40LDguMywxMS44LDguMSwxMi4xeiBNOC4xLDlDNy45LDkuMSw3LjgsOS4yLDcuNiw5LjJTNy4zLDkuMSw3LjIsOUw1LjgsNy42TDQuNCw5CglDNC4yLDkuMiwzLjgsOS4yLDMuNiw5Yy0wLjItMC4yLTAuMi0wLjYsMC0wLjlsMS42LTEuNWMwLjItMC4yLDAuNC0wLjMsMC43LTAuM2MwLjMsMCwwLjUsMC4xLDAuNywwLjNsMS42LDEuNQoJQzguMyw4LjQsOC4zLDguOCw4LjEsOXogTTE1LjIsMTRoLTQuNmMtMC44LDAtMS40LTAuNi0xLjQtMS40YzAtMC44LDAuNi0xLjQsMS40LTEuNGg0LjZjMC44LDAsMS40LDAuNiwxLjQsMS40CglDMTYuNiwxMy4zLDE2LDE0LDE1LjIsMTR6IE0xNS4yLDkuMmgtNC42Yy0wLjgsMC0xLjQtMC42LTEuNC0xLjRjMC0wLjgsMC42LTEuNCwxLjQtMS40aDQuNmMwLjgsMCwxLjQsMC42LDEuNCwxLjQKCUMxNi42LDguNSwxNiw5LjIsMTUuMiw5LjJ6Ii8+Cjwvc3ZnPgo=';
    
    // Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Ideas', 'Post Type General Name', 'ideapush' ),
        'singular_name'       => _x( 'Idea', 'Post Type Singular Name', 'ideapush' ),
        'menu_name'           => __( 'IdeaPush', 'ideapush' ),
        'parent_item_colon'   => __( 'Parent Idea', 'ideapush' ),
        'all_items'           => __( 'All Ideas', 'ideapush' ),
        'view_item'           => __( 'View Idea', 'ideapush' ),
        'add_new_item'        => __( 'Add New Idea', 'ideapush' ),
        'add_new'             => __( 'Add New', 'ideapush' ),
        'edit_item'           => __( 'Edit Idea', 'ideapush' ),
        'update_item'         => __( 'Update Idea', 'ideapush' ),
        'search_items'        => __( 'Search Idea', 'ideapush' ),
        'not_found'           => __( 'Not Found', 'ideapush' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'ideapush' ),
    );
     
    // Set other options for Custom Post Type
    $args = array(
        'label'               => __( 'ideas', 'ideapush' ),
        'description'         => __( 'IdeaPush ideas', 'ideapush' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
    //    'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields',),
        'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'comments', 'revisions','excerpt'),
        
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'idea-boards' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'menu_icon'           => $menu_icon_svg,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => apply_filters( 'ideapush_display_single_ideas', true ),
        'capability_type'     => array('ip_idea','ip_ideas'),
        'map_meta_cap'        => true,
    );
     
    // Registering your Custom Post Type
    register_post_type( 'idea', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'idea_push_create_custom_post_type', 0 );


//when activating or deactivating the plugin lets fluch the rewrite rules because otherwise the single post pages may produce an error

register_activation_hook( __FILE__, 'idea_push_flush_rewrites' );
function idea_push_flush_rewrites() {
	// call your CPT registration function here (it should also be hooked into 'init')
	idea_push_create_custom_post_type();
}









//run on activating the plugin
function idea_push_plugin_activate() {

    // remove_role( 'idea_push_guest' );
    add_role( 'idea_push_guest', 'IdeaPush Guest', array('read' => true, 'level_1' => true ));
    add_role( 'idea_push_manager', 'IdeaPush Manager', array('read' => true, 'level_1' => true,'manage_idea_push_settings' => true ));
}
register_activation_hook( __FILE__, 'idea_push_plugin_activate' );


//adds new role to wordpress
//this is poorly implemented but I don't know a nicer way to do it because adding the role upon updating was not working
function idea_push_check_if_manager_role_exists(){
    if(get_role("idea_push_manager") == null){
        add_role( 'idea_push_manager', 'IdeaPush Manager', array('read' => true, 'level_1' => true,'manage_idea_push_settings' => true ));
    }    
}
add_action( 'admin_init', 'idea_push_check_if_manager_role_exists' );




//add role capabilities for custom post type
add_action('admin_init','idea_push_role_capabilities',999);
function idea_push_role_capabilities() {

    // Add the roles you'd like to administer the custom post types
    $roles = array('idea_push_manager','editor','administrator');
    $capability_normal = 'ip_idea';
    $capability_plural = 'ip_ideas';

    // Loop through each role and assign capabilities
    foreach($roles as $the_role) { 

        $role = get_role($the_role);

        if($role){
            $role->add_cap( 'read' );
            $role->add_cap( 'manage_tags' );
            $role->add_cap( 'edit_tags' );
            $role->add_cap( 'read_'.$capability_normal);
            $role->add_cap( 'delete_'.$capability_normal );
            $role->add_cap( 'read_private_'.$capability_plural );
            $role->add_cap( 'edit_'.$capability_normal );
            $role->add_cap( 'edit_'.$capability_plural );
            $role->add_cap( 'edit_others_'.$capability_plural );
            $role->add_cap( 'edit_published_'.$capability_plural );
            $role->add_cap( 'edit_private_'.$capability_plural );
            $role->add_cap( 'publish_'.$capability_plural );
            $role->add_cap( 'delete_others_'.$capability_plural );
            $role->add_cap( 'delete_private_'.$capability_plural );
            $role->add_cap( 'delete_published_'.$capability_plural );
            $role->add_cap( 'delete_'.$capability_plural );
            $role->add_cap( 'manage_idea_push_settings');
        }

        
    }
}





//run on deactivating the plugin
function idea_push_plugin_deactivate(){
    remove_role( 'idea_push_guest' );
    remove_role( 'idea_push_manager' );
}
register_deactivation_hook( __FILE__, 'idea_push_plugin_deactivate' );


//enable idea push managers to save the settings
function idea_push_manager_save_settings(){
    return 'manage_idea_push_settings';
}
add_filter( 'option_page_capability_ip_notifications', 'idea_push_manager_save_settings');
add_filter( 'option_page_capability_ip_statuses', 'idea_push_manager_save_settings');
add_filter( 'option_page_capability_ip_design', 'idea_push_manager_save_settings');
add_filter( 'option_page_capability_ip_idea_form', 'idea_push_manager_save_settings');
add_filter( 'option_page_capability_ip_boards', 'idea_push_manager_save_settings');
add_filter( 'option_page_capability_ip_licence', 'idea_push_manager_save_settings');
add_filter( 'option_page_capability_ip_ideapush_support', 'idea_push_manager_save_settings');
add_filter( 'option_page_capability_ip_ideapush_pro', 'idea_push_manager_save_settings');
add_filter( 'option_page_capability_ip_integrations', 'idea_push_manager_save_settings');



//add a settings link on the plugin page
function idea_push_add_settings_link( $links ) {
    $settings_link = '<a href="edit.php?post_type=idea&page=idea_push_settings">' . __( 'Settings','ideapush' ) . '</a>';
    $ideas_link = '<a href="edit.php?post_type=idea">' . __( 'All Ideas','ideapush' ) . '</a>';
    array_unshift( $links, $settings_link );
    array_unshift( $links, $ideas_link );
    
    
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'idea_push_add_settings_link' );



//create boards taxonomy 
add_action( 'init', 'idea_push_create_taxonomy_boards', 0 );
 
function idea_push_create_taxonomy_boards() {
 
// Labels part for the GUI
 
  $labels = array(
    'name' => _x( 'Boards', 'taxonomy general name' ),
    'singular_name' => _x( 'Board', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Boards' ),
    'popular_items' => __( 'Popular Boards' ),
    'all_items' => __( 'All Boards' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Board' ), 
    'update_item' => __( 'Update Board' ),
    'add_new_item' => __( 'Add New Board' ),
    'new_item_name' => __( 'New Board Name' ),
    'separate_items_with_commas' => __( 'Separate boards with commas' ),
    'add_or_remove_items' => __( 'Add or remove boards' ),
    'choose_from_most_used' => __( 'Choose from the most used boards' ),
    'menu_name' => __( 'Boards' ),
  ); 
 
// Now register the non-hierarchical taxonomy like tag
 
  register_taxonomy('boards','idea',array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_menu' => false,
    'show_in_nav_menus' => false,
    'show_admin_column' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'ip-board' ),
    'meta_box_cb' => 'idea_push_taxonomy_radio_meta_box',
    'capabilities' => array (
        'manage_terms' => 'read', //by default only admin
        'edit_terms' => 'manage_tags',
        'delete_terms' => 'manage_tags',
        'assign_terms' => 'edit_tags' 
        ),  
  ));
       
}


//create tags taxonomy 
add_action( 'init', 'idea_push_create_taxonomy_tags', 0 );
 
function idea_push_create_taxonomy_tags() {
 
// Labels part for the GUI
 
  $labels = array(
    'name' => _x( 'Tags', 'taxonomy general name' ),
    'singular_name' => _x( 'Tag', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search tags' ),
    'popular_items' => __( 'Popular Tags' ),
    'all_items' => __( 'All Tags' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Tag' ), 
    'update_item' => __( 'Update Tag' ),
    'add_new_item' => __( 'Add New Tag' ),
    'new_item_name' => __( 'New Tag Name' ),
    'separate_items_with_commas' => __( 'Separate tags with commas' ),
    'add_or_remove_items' => __( 'Add or remove tags' ),
    'choose_from_most_used' => __( 'Choose from the most used tags' ),
    'menu_name' => __( 'Tags' ),
  ); 
 
// Now register the non-hierarchical taxonomy like tag
 
  register_taxonomy('tags','idea',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => false,
    'show_admin_column' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'ip-tag' ),
    'capabilities' => array (
                'manage_terms' => 'read', //by default only admin
                'edit_terms' => 'manage_tags',
                'delete_terms' => 'manage_tags',
                'assign_terms' => 'edit_tags' 
                ),  
  ));
       
}



//create  status taxonomy 
add_action( 'init', 'idea_push_create_taxonomy_status', 0 );
 
function idea_push_create_taxonomy_status() {
 
// Labels part for the GUI
 
  $labels = array(
    'name' => _x( 'Status', 'taxonomy general name' ),
    'singular_name' => _x( 'Status', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Statuses' ),
    'popular_items' => __( 'Popular Statuses' ),
    'all_items' => __( 'All Statuses' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Status' ), 
    'update_item' => __( 'Update Status' ),
    'add_new_item' => __( 'Add New Status' ),
    'new_item_name' => __( 'New Status Name' ),
    'separate_items_with_commas' => __( 'Separate statuses with commas' ),
    'add_or_remove_items' => __( 'Add or remove statuses' ),
    'choose_from_most_used' => __( 'Choose from the most used statuses' ),
    'menu_name' => __( 'Statuses' ),
  ); 
 
// Now register the non-hierarchical taxonomy like tag
 
  register_taxonomy('status','idea',array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_quick_edit' => true,  
    'show_in_menu' => true,
    'show_in_nav_menus' => false,
    'show_admin_column' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'ip-status' ),
    'meta_box_cb' => 'idea_push_taxonomy_radio_meta_box_status',
    'capabilities' => array (
        'manage_terms' => 'read', //by default only admin
        'edit_terms' => 'manage_tags',
        'delete_terms' => 'manage_tags',
        'assign_terms' => 'edit_tags' 
        ),  
  ));
       
}

	
	
	











//create predefined statuses
add_action( 'init', 'idea_push_create_predefined_statuses', 0 );
 
function idea_push_create_predefined_statuses() {
    
    if(get_term_by('name', 'Open', 'status') == false){
        wp_insert_term(
          'Open', // the term 
          'status', // the taxonomy
          array(
            'description'=> '',  
            'slug' => 'open'
          )
        );
    }
    
    if(get_term_by('name', 'Reviewed', 'status') == false){
        wp_insert_term(
          'Reviewed', // the term 
          'status', // the taxonomy
          array(
            'description'=> '',  
            'slug' => 'reviewed'
          )
        );
   }
    
    if(get_term_by('name', 'Approved', 'status') == false){
        wp_insert_term(
          'Approved', // the term 
          'status', // the taxonomy
          array(
            'description'=> '',  
            'slug' => 'approved'
          )
        );
   }
    
    if(get_term_by('name', 'Declined', 'status') == false){
        wp_insert_term(
          'Declined', // the term 
          'status', // the taxonomy
          array(
            'description'=> '',  
            'slug' => 'declined'
          )
        );
   }
    
    if(get_term_by('name', 'In Progress', 'status') == false){
        wp_insert_term(
          'In Progress', // the term 
          'status', // the taxonomy
          array(
            'description'=> '',  
            'slug' => 'in-progress'
          )
        );
   }
    
    if(get_term_by('name', 'Completed', 'status') == false){
        wp_insert_term(
          'Completed', // the term 
          'status', // the taxonomy
          array(
            'description'=> '',  
            'slug' => 'completed'
          )
        );
   }

   if(get_term_by('name', 'Duplicate', 'status') == false){
        wp_insert_term(
        'Duplicate', // the term 
        'status', // the taxonomy
        array(
            'description'=> '',  
            'slug' => 'duplicate'
        )
        );
    }
    
    
    
    
}










//lets add our settings page
add_action( 'admin_menu', 'idea_push_add_settings_page' );
add_action( 'admin_init', 'idea_push_settings_init' );

function idea_push_add_settings_page() {
    
    global $idea_push_wp_settings_page;
    
    $idea_push_wp_settings_page = add_submenu_page('edit.php?post_type=idea', __('Settings','ideapush'), __('Settings','ideapush'),'manage_idea_push_settings', 'idea_push_settings', 'idea_push_settings_page_content');    
    
}

//callback function of setting page
function idea_push_settings_page_content(){
    require('inc/options/options-page-wrapper.php');  
}

//Gets, sets and renders options
require('inc/options/options-output.php');

//Renders the board
require('inc/options/options-board-render.php');

//produces the shortcode
require('inc/shortcode/ideaboard-shortcode.php');

//function that generates list items
require('inc/shortcode/idea-list-items.php');

//function that creates the idea form
require('inc/shortcode/create-idea-form.php');

//function that creates the idea table header
require('inc/shortcode/idea-table-header.php');

//function that creates ideas
require('inc/functions/create-idea.php');

//function that creates votes
require('inc/functions/create-vote.php');

//function that changes the status
require('inc/functions/status-change.php');

//common functions that assist other functions run
require('inc/functions/helper-functions.php');

//function that creates a user
require('inc/functions/create-user.php');

//function that updates a user
require('inc/functions/update-user.php');

//function puts idea stuff on the single idea page
require('inc/functions/single-idea.php');

//function that adds our metabox
require('inc/functions/create-metaboxes.php');

//function that creates user profile image and votes remaining
require('inc/functions/user-profile-meta.php');



//get pro options
if($ideapush_is_pro=="YES"){
    require('inc/pro-ip/idea-push-pro.php');
}




// Load admin style and scripts
function idea_push_register_admin_styles($hook)
{

    global $pagenow;
    
    global $idea_push_wp_settings_page;
    global $idea_push_wp_reports_page;
    
    if('profile.php' == $pagenow || 'user-edit.php' == $pagenow){
        //display on user profile page
        wp_enqueue_media();  
        wp_enqueue_script( 'user-profile-script-ideapush', plugins_url( '/inc/js/userprofile.js', __FILE__ ), array( 'jquery' ),idea_push_plugin_get_version());
    }
    
    
    if($hook != $idea_push_wp_reports_page && $hook != $idea_push_wp_settings_page ){
        return;    
        
    } else {
        //css
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'custom-admin-style-ideapush', plugins_url( '/inc/css/adminstyle.css', __FILE__ ),array(),idea_push_plugin_get_version());
        wp_enqueue_style( 'font-awesome', plugins_url( '/inc/css/font-awesome.min.css', __FILE__ ));
        wp_enqueue_style('tippy', plugins_url('/inc/css/tippy.css', __FILE__ ));    

        //js
        wp_enqueue_script( 'custom-admin-script-ideapush', plugins_url( '/inc/js/adminscript.js', __FILE__ ), array( 'jquery','wp-color-picker' ),idea_push_plugin_get_version());
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script('jquery-ui-accordion'); 
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-form');

        wp_enqueue_script('alertify', plugins_url('/inc/js/alertify.js', __FILE__ ), array( 'jquery'),null,true);  
        wp_enqueue_script('clipboard', plugins_url('/inc/js/clipboard.min.js', __FILE__ ), array( 'jquery'),'2.0.4');  
        wp_enqueue_script('tippy', plugins_url('/inc/js/tippy.min.js', __FILE__ ), array( 'jquery'),'1.0');    
        
    }
    
    
    
    
    
    
    
    
}
add_action( 'admin_enqueue_scripts', 'idea_push_register_admin_styles' );



// Load styles and scripts for the single idea page
function idea_push_register_admin_styles_single(){
    
    global $post_type;
    
    if('idea' != $post_type)
    return;

    //css

    //js
    wp_enqueue_script( 'custom-frontend-script-single-idea', plugins_url( '/inc/js/singleideascript.js', __FILE__ ), array( 'jquery'), idea_push_plugin_get_version());

}
add_action( 'admin_enqueue_scripts', 'idea_push_register_admin_styles_single' );













// Load frontend  style and scripts
function idea_push_register_frontend_styles()
{
    //get settings
    $options = get_option('idea_push_settings');
        
    //css
    wp_register_style( 'custom-frontend-style-ideapush', plugins_url( '/inc/css/frontendstyle.css', __FILE__ ),array(),idea_push_plugin_get_version());
    wp_register_style( 'ideapush-font', plugins_url( '/inc/css/ideapush-font.css', __FILE__ ));


    //js
    wp_register_script( 'custom-frontend-script-ideapush', plugins_url( '/inc/js/frontendscript.js', __FILE__ ), array( 'jquery'), idea_push_plugin_get_version());
    
    wp_localize_script('custom-frontend-script-ideapush','get_new_ideas', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_localize_script('custom-frontend-script-ideapush','submit_vote', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_localize_script('custom-frontend-script-ideapush','create_user', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_localize_script('custom-frontend-script-ideapush','header_render', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_localize_script('custom-frontend-script-ideapush','form_render', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_localize_script('custom-frontend-script-ideapush','create_idea', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_localize_script('custom-frontend-script-ideapush','delete_idea', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_localize_script('custom-frontend-script-ideapush','change_status', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_localize_script('custom-frontend-script-ideapush','update_vote_counter', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_localize_script('custom-frontend-script-ideapush','below_title_header', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_localize_script('custom-frontend-script-ideapush','is_person_able_to_add_tag', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_localize_script('custom-frontend-script-ideapush','update_user_profile', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    
    
    wp_register_script('alertify', plugins_url('/inc/js/alertify.js', __FILE__ ), array( 'jquery'),null,true);  
    wp_register_script('scroll-reveal', plugins_url('/inc/js/scrollreveal.min.js', __FILE__ ), array( 'jquery'));
    wp_register_script('read-more', plugins_url('/inc/js/readmore.min.js', __FILE__ ), array( 'jquery'));
    // wp_register_script('jquery-ui-dialog');

  
    
}
add_action( 'wp_enqueue_scripts', 'idea_push_register_frontend_styles' );







//create taxonomy item
//this is used to create a taxonomy for the board when the user creates a board from the plugin settings
function idea_push_add_taxonomy_item() {
    
    //add better protection of this function
    //check if user is admin or manager
    $nonce = $_POST['nonce'];

    if( wp_verify_nonce( $nonce, 'ideapush_create_board' ) ){

        if(current_user_can('administrator') || current_user_can('idea_push_manager')){


            //get options
            $options = get_option('idea_push_settings');
            
            //get is pro
            global $ideapush_is_pro;
            
            //get board name from ajax call
            $boardName = idea_push_sanitization_validation($_POST['boardName'],'boardname');
            
            if($boardName == false){
                wp_die();   
            }
            
            
            
            
            //lets first check if the board already exists
            if(get_term_by('name', $boardName, 'boards') == false){
                
                //lets check if licenced
                if(($ideapush_is_pro == 'YES' && strlen($options['idea_push_purchase_email'])>0 && strlen($options['idea_push_order_id'])>0) || wp_count_terms('boards',array( "hide_empty" => 0 )) == 0){
                    
                    idea_push_create_taxonomy_boards();

                    $boardNameSlug = strtolower($boardName);
                    $boardNameSlug = preg_replace('/\s+/', '-', $boardNameSlug);

                    wp_insert_term(
                    $boardName, // the term 
                    'boards', // the taxonomy
                    array(
                        'description'=> '',  
                        'slug' => $boardNameSlug
                    )
                    );

                    //lets return the item id

                    $getTermId = get_term_by('name', $boardName, 'boards');
                    $termId = $getTermId->term_id;

                    //return board html
                    echo idea_push_render_board('',$termId,$boardName);
                
                
                } else {
                    echo 'NOT-PRO'; 
                }
                
                
                
                
            } else {
                
                echo 'TERM-EXISTS';

            }

        } //end manager/admin check
    } //end nonce check

    //die
    wp_die();

 
}
add_action( 'wp_ajax_add_taxonomy_item', 'idea_push_add_taxonomy_item' );









//lets delete existing taxonomies and rename others where necessary
function idea_push_taxonomy_save_routine() {
    

    //get board name from ajax call
    $comparisonData = $_POST['comparisonData'];
    $explodeTheData = explode("^^^", $comparisonData);
    
    $boardIdArray = array();
    
    foreach($explodeTheData as $board){
        
        $anotherExplosion = explode("|", $board);
        $boardId = $anotherExplosion[0];
        $boardName = $anotherExplosion[1];
                
        array_push($boardIdArray,$boardId);
        
        //now lets change the name of existing taxonomies
        $termObject = get_term($boardId,'boards');
        
        //if the there's a match lets update the name if it has changed
        if(!is_wp_error($termObject)){
            
            $termName = $termObject->name;
            
            if($boardName !== $termName){
                //they don't match so lets update this term name with the new board name
                wp_update_term($boardId,'boards',array('name' => $boardName)); 
            }   
        }

    }
    
    
    //lets cycle through the existing terms in the taxonomy
    $existingTerms = get_terms('boards',array( "hide_empty" => 0 ));
    
    foreach($existingTerms as $term){
        
        $termId = $term->term_id;
        
        if(!in_array($termId,$boardIdArray)){
            //if the term is not in our array delete it because it's not authorised to exist and it will cause confusion
            wp_delete_term($termId,'boards'); 
  
        }  
    }
    
    
    //die
    wp_die();

 
}
add_action( 'wp_ajax_taxonomy_save_routine', 'idea_push_taxonomy_save_routine' );








//add additional body class to the shortcode page for good measure
function idea_push_add_body_class($class) {

    global $post;

    if( isset($post->post_content) && has_shortcode( $post->post_content, 'ideapush' ) ) {
        $class[] = 'idea-push';
    }
    return $class;
}
add_filter( 'body_class', 'idea_push_add_body_class' );



//add notification icon to menu item

add_action( 'admin_menu', 'idea_push_menu_bubble' );

function idea_push_menu_bubble() {
  global $menu;

  $count = idea_push_count_pending_ideas() + idea_push_count_ideas_review_status();


  if ($count > 0) {

    foreach ( $menu as $key => $value ) {

      if ( $menu[$key][2] == 'edit.php?post_type=idea' ) {

        $menu[$key][0] .= ' <span class="update-plugins"><span class="plugin-count">' . $count . '</span></span>';

        return;
      }
    }
  }
}


function idea_push_above_all_ideas_table() {
        
    $screen = get_current_screen();
    
    if ( $screen->id == 'edit-idea' ) {
        
        echo '<style>
        
        .idea-tasks div {
            display: inline-block;
            background: #fff;
            padding: 10px;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            margin-right: 10px;
            margin-top: 10px;
            margin-bottom: -10px;
        
        }
        
        .idea-tasks a {
            text-decoration: none;
        
        }
        
        .pending-count, .reviewed-count {
            display: inline-block;
            vertical-align: top;
            margin: 1px 0 0 5px;
            padding: 0 5px;
            min-width: 7px;
            height: 17px;
            border-radius: 11px;
            background-color: #ca4a1f;
            color: #fff;
            font-size: 9px;
            line-height: 17px;
            text-align: center;
            z-index: 26;
            
        }
        
        .column-status .open {
            background-color: #4eb5e1;
            color: #fff !important;
        }
        
        .column-status .in-progress {
            background-color: #a7a9ac;
            color: #fff !important;
        }
        
        
        .column-status .completed {
            background-color: #414042;
            color: #fff !important;
        }
        
        .column-status .reviewed {
            background-color: #fbbf67;
            color: #fff !important;
        }
        
        .column-status .approved {
            background-color: #5eb46a;
            color: #fff !important;
        }
        
        .column-status .declined {
            background-color: #f05d55;
            color: #fff !important;
        }

        .column-status .duplicate {
            background-color: #e2e0e0;
            color: #fff !important;
        }
        
        
        .column-status a {
            
            text-transform: uppercase;
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: 600;
            font-size: smaller;
        }
        
        </style>';
        
        $pendingEditPage = get_admin_url().'edit.php?post_status=pending&post_type=idea';
        $reviewEditPage = get_admin_url().'edit.php?post_type=idea&status=reviewed';
        
        $countPendingIdeas = idea_push_count_pending_ideas();
        $countReviewStatus = idea_push_count_ideas_review_status();
        
        
        echo '<div class="idea-tasks">';
        
            if($countPendingIdeas > 0){
                echo '<div class="pending-approval">';
                    echo '<a href="'.$pendingEditPage.'" class="pending-count-description">'.__( 'Ideas Pending Release', 'ideapush' ).'</a>';
                    echo '<span class="pending-count">'.$countPendingIdeas.'</span>';
                echo '</div>';
            }
        
            if($countReviewStatus > 0){
                echo '<div class="reviewed-status">';
                    echo '<a href="'.$reviewEditPage.'" class="reviewed-count-description">'.__( 'Ideas Needing Review', 'ideapush' ).'</a>';
                    echo '<span class="reviewed-count">'.$countReviewStatus.'</span>';
                echo '</div>';
            }
        
        echo '</div>';
        
        
    }
    
 
}
add_action( 'admin_notices', 'idea_push_above_all_ideas_table' );





add_filter( 'manage_edit-idea_columns', 'mycustomposttype_columns_definition' ) ;


function mycustomposttype_columns_definition( $columns ) {

    $new = array();
    
$columns['votes'] = __( 'Votes','ideapush'); 
unset( $new['taxonomy-status'] );    
$columns['status'] = __( 'Status','ideapush'); 

$customOrder = array('cb','title', 'author','taxonomy-boards' ,'status','taxonomy-tags','votes', 'comments','date');


foreach ($customOrder as $colname)
    $new[$colname] = $columns[$colname];    
return $new;
}




add_action( 'manage_idea_posts_custom_column' , 'idea_push_vote_column_content', 10, 2 );

function idea_push_vote_column_content( $column, $post_id ) {
    switch ( $column ) {

        // display a thumbnail photo
        case 'votes' :

            echo get_post_meta( $post_id , 'votes' , true ); 
            break;

        case 'status' :

            $getTerms = get_the_terms( $post_id, 'status' );  
            
            if($getTerms !== false){
                foreach($getTerms as $term) {
                    $status = $term->name;    
                }      
                    
                $className = strtolower($status);   
                $className = str_replace(" ","-",$className);   
                    
                echo '<a class="'.$className.'" href="'.get_admin_url().'edit.php?post_type=idea&status='.$className.'">'.idea_push_translate_status($status).'</a>';
                
            }

            break;

    }
}


add_filter( 'manage_edit-idea_sortable_columns', 'idea_push_sortable_vote_column' );

function idea_push_sortable_vote_column( $columns ) {
    $columns['votes'] = 'votes';    
    return $columns;
}


add_action( 'pre_get_posts', 'idea_push_custom_orderby' );

function idea_push_custom_orderby( $query ) {
    if ( ! is_admin() )
    return;

    $orderby = $query->get( 'orderby');

    if ( 'votes' == $orderby ) {
        $query->set( 'meta_key', 'votes' );
        $query->set( 'orderby', 'meta_value_num' );
    }
    
}




add_filter( 'template_include', 'idea_push_ip_tag_template', 99 );

function idea_push_ip_tag_template( $template ) {

    
    
    if ( is_tax( 'tags' )) {
        $template = dirname( __FILE__ ) . '/inc/templates/ip-tag.php';
        return $template; 
    } elseif( is_author() && is_post_type_archive( array( 'idea' ) ) ){
        $template = dirname( __FILE__ ) . '/inc/templates/ip-tag.php';
        return $template; 

    } else {
        return $template;    
    }

	
}


function idea_push_languages() {
  load_plugin_textdomain( 'ideapush', false, 'ideapush/inc/languages' );
}
add_action('init', 'idea_push_languages');





//hides boards from WordPress search
add_action( 'pre_get_posts', function ( $query ) {
    
    if(get_option('idea_push_settings') && array_key_exists('idea_push_board_configuration',get_option('idea_push_settings'))){

        //get options
        $options = get_option('idea_push_settings'); 
        
        //get board configuration
        $boardConfiguration = $options['idea_push_board_configuration'];
        
        if(isset($boardConfiguration) && strlen($boardConfiguration)>0){


            //split board configuration
            $splitBoardConfiguration = explode('^^^',$boardConfiguration);
            
            $boardsToHide = array();
            
            //cycle through boards
            foreach($splitBoardConfiguration as $boardConfig){
                
                if( !empty($boardConfig) && strlen($boardConfig)>0){
                    
                    $furtherSplitBoardConfiguration = explode('|',$boardConfig);   
                
                    if(isset($furtherSplitBoardConfiguration[13]) && $furtherSplitBoardConfiguration[13] == 'Yes'){

                        $term = get_term($furtherSplitBoardConfiguration[0], 'boards' );

                        if( ! is_null($term) && ! is_wp_error($term) ){
                            $slug = $term->slug;
                            array_push($boardsToHide,$slug);
                        } 
                    }     
                }
        
            }
            

            global $wp_the_query;
            if($query === $wp_the_query && $query->is_search() && !is_admin()) {
                $tax_query = array(
                    array(
                        'taxonomy' => 'boards',
                        'field' => 'slug',
                        'terms' => $boardsToHide,
                        'operator' => 'NOT IN',
                    )
                );
                $query->set( 'tax_query', $tax_query );
            }

        }
    }
 
});













//add custom css to the frontend
function idea_push_custom_css() {
    
    //get options
    $options = get_option('idea_push_settings'); 
    
    
    if(isset($options['idea_push_custom_css']) && strlen($options['idea_push_custom_css'])>0){  
        wp_register_style( 'custom-frontend-style-ideapush', plugins_url( '/inc/css/frontendstyle.css', __FILE__ ),array(),idea_push_plugin_get_version());  
        wp_add_inline_style( 'custom-frontend-style-ideapush', $options['idea_push_custom_css'] ); 

    }
        
}
add_action( 'wp_enqueue_scripts', 'idea_push_custom_css', 999 );

















//do pro updates
//initialise the update check
if($ideapush_is_pro == 'YES'){

    require 'inc/pro-ip/plugin-update-checker/plugin-update-checker.php';

    global $plugin_update_checker_ideapush;
    $plugin_update_checker_ideapush = Puc_v4_Factory::buildUpdateChecker(
        'https://northernbeacheswebsites.com.au/?update_action=get_metadata&update_slug=ideapush', //Metadata URL.
        __FILE__, //Full path to the main plugin file.
        'ideapush' //Plugin slug. Usually it's the same as the name of the directory.
    );


    //add queries to the update call
    $plugin_update_checker_ideapush->addQueryArgFilter('filter_update_checks_ideapush');
    function filter_update_checks_ideapush($queryArgs) {


        $pluginSettings = get_option('idea_push_settings');

        if(isset($pluginSettings['idea_push_purchase_email']) && isset($pluginSettings['idea_push_order_id'])){

            $purchaseEmailAddress = $pluginSettings['idea_push_purchase_email'];
            $orderId = $pluginSettings['idea_push_order_id'];
            $siteUrl = get_site_url();
            $siteUrl = parse_url($siteUrl);
            $siteUrl = $siteUrl['host'];

            if (!empty($purchaseEmailAddress) &&  !empty($orderId)) {
                $queryArgs['purchaseEmailAddress'] = $purchaseEmailAddress;
                $queryArgs['orderId'] = $orderId;
                $queryArgs['siteUrl'] = $siteUrl;
                $queryArgs['productId'] = '9719';
            }

        }

        return $queryArgs;   
    }



    // define the puc_request_info_result-<slug> callback 
    $plugin_update_checker_ideapush->addFilter(
        'request_info_result', 'filter_puc_request_info_result_slug_ideapush', 10, 2
    );
    function filter_puc_request_info_result_slug_ideapush( $plugininfo, $result ) { 
        //get the message from the server and set as transient
        set_transient('ideapush-update',$plugininfo->{'message'},YEAR_IN_SECONDS * 1);

        return $plugininfo; 
    }; 






    $path = plugin_basename( __FILE__ );

    add_action("after_plugin_row_{$path}", function( $plugin_file, $plugin_data, $status ) {

        //get plugin settings
        $pluginSettings = get_option('idea_push_settings');


        if (!empty($pluginSettings['idea_push_purchase_email']) &&  !empty($pluginSettings['idea_push_order_id'])) {

            $order_id = $pluginSettings['idea_push_order_id'];

            //get transient
            $message = get_transient('ideapush-update');

            if($message !== 'Yes' && $message !== false){

                $purchaseLink = 'https://northernbeacheswebsites.com.au/ideapush-pro/';

                if($message == 'Incorrect Details'){
                    $displayMessage = 'The Order ID and Purchase ID you entered is not correct. Please double check the details you entered to receive product updates.';    
                } elseif ($message == 'Licence Expired'){
                    $displayMessage = 'Your licence has expired. Please <a href="'.$purchaseLink.'" target="_blank">purchase a new licence</a> to receive further updates for this plugin.';    
                } elseif ($message == 'Website Mismatch') {
                    $displayMessage = 'This plugin has already been registered on another website using your details. Under the licence terms this plugin can only be used on one website. Please <a href="'.$purchaseLink.'" target="_blank">click here</a> to purchase an additional licence. To change the website assigned to your licence, please click <a href="https://northernbeacheswebsites.com.au/my-account/view-order/'.$order_id.'/" target="_blank">here</a>.';    
                } else {
                    $displayMessage = '';    
                }

                echo '<tr class="plugin-update-tr active"><td colspan="3" class="plugin-update colspanchange"><div class="update-message notice inline notice-error notice-alt"><p class="installer-q-icon">'.$displayMessage.'</p></div></td></tr>';

            }

        } else {

            echo '<tr class="plugin-update-tr active"><td colspan="3" class="plugin-update colspanchange"><div class="update-message notice inline notice-error notice-alt"><p class="installer-q-icon">Please enter your Order ID and Purchase ID in the plugin settings to receive automatics updates.</p></div></td></tr>';

        }


    }, 10, 3 );

    /**
    * 
    *
    *
    * Force check for updates
    */
    function idea_push_force_check_for_updates(){
        global $plugin_update_checker_ideapush;
        
        $plugin_update_checker_ideapush->checkForUpdates();
    }


}
/**
* 
*
*
* Add custom links to plugin on plugins page
*/
function idea_push_plugin_links( $links, $file ) {
    if ( strpos( $file, 'ideapush.php' ) !== false ) {
       $new_links = array(
                '<a href="https://northernbeacheswebsites.com.au/product/donate-to-northern-beaches-websites/" target="_blank">' . __('Donate') . '</a>',
                '<a href="https://wordpress.org/support/plugin/ideapush" target="_blank">' . __('Support Forum') . '</a>',
             );
       $links = array_merge( $links, $new_links );
    }
    return $links;
 }
 add_filter( 'plugin_row_meta', 'idea_push_plugin_links', 10, 2 );
/**
* 
*
*
* save tab memory
*/
function idea_push_save_tab_memory() {
    
    //get board name from ajax call
    $tabName = sanitize_text_field($_POST['tab']);
 
    //set a transient for the tab memory
    set_transient('ideapush-tab-memory',$tabName,WEEK_IN_SECONDS*1);

    echo 'SUCCESS';

    //die
    wp_die();

 
}
add_action( 'wp_ajax_save_tab_memory', 'idea_push_save_tab_memory' );
/**
* 
*
*
* Change archive title for tag archives which have board text
*/
add_filter( 'get_the_archive_title', 'idea_push_change_the_tag_title');

function idea_push_change_the_tag_title( $title ) {

    if (strpos($title, 'BoardTag') !== false) {
        $title_exploded = explode('-',$title);
        $title = $title_exploded[2];
    }

    return $title;

}






?>