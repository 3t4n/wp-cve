<?php
/**
 * Plugin Name: LH Dashboard Notes
 * Plugin URI: https://lhero.org/plugins/lh-dashboard-notes/
 * Description: Add your own custom help boxes to the admin section of WordPress
 * Version: 1.09
 * Text Domain: lh_dashboard_notes
 * Domain Path: /languages
 * Author: Peter Shaw
 * Author URI: https://shawfactor.com
*/



/**
* LH Dashboard Notes Class
*/

if (!class_exists('LH_dashboard_notes_plugin')) {
    

class LH_dashboard_notes_plugin {


var $posttype = 'lh-dashboard-note';
var $namespace = 'lh_dashboard_notes';
var $hidden_field_name = 'lh_dashboard_notes-hidden_field_name';
var $whitelisted_sites_field_name = 'lh_dashboard_notes-whitelisted_sites_field_name';
var $path = 'lh-dashboard-notes/lh-dashboard-notes.php';
var $filename;

private static $instance;

private function is_this_plugin_network_activated(){

if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

if ( is_plugin_active_for_network( $this->path ) ) {
    // Plugin is activated

return true;

} else  {


return false;


}

}

private function return_dashboard_notes_posts(){

if ($this->is_this_plugin_network_activated()){

switch_to_blog(BLOG_ID_CURRENT_SITE);


$args = array(
        'posts_per_page'   => 5,
	'post_type' => $this->posttype
	);

$posts = get_posts($args);


restore_current_blog();

} else {


$args = array(
        'posts_per_page'   => 5,
	'post_type' => $this->posttype
	);

$posts = get_posts($args);

}

return $posts;


}


private function register_widget_post_type(){
    
    $capabilities  = array(
        'edit_post' => 'edit_others_posts',
        'edit_posts' => 'edit_others_posts',
        'edit_others_posts' => 'edit_others_posts',
        'publish_posts' => 'edit_others_posts',
        'read_post' => 'read_post',
        'read_private_posts' => 'read_private_posts',
        'delete_posts' => 'delete_others_posts',
        'delete_post' => 'delete_others_posts'
        
    );
    
    
$capabilities =  apply_filters( 'lh_dashboard_notes_capabilities_filter', $capabilities );
    

  $labels = array(
    'name'               => _x( 'Dash Notes', 'post type general name' ),
    'singular_name'      => _x( 'DashBoard Note', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'widget' ),
    'add_new_item'       => __( 'Add New Dashboard Note' ),
    'edit_item'          => __( 'Edit Dashboard Note' ),
    'new_item'           => __( 'New Dashboard Note' ),
    'all_items'          => __( 'All Notes' ),
    'view_item'          => __( 'View Dashboard Note' ),
    'search_items'       => __( 'Search Dashboard Notes' ),
    'not_found'          => __( 'No Dashboard Notes found' ),
    'not_found_in_trash' => __( 'No dashboard notes found in the Trash' ), 
    'parent_item_colon'  => '',
    'menu_name'          => 'Dash Notes'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds our Dashboard Notes and Dashboard Note specific data',
   'public'                => false,
   'capabilities' => $capabilities,
   'show_ui' => true,
   'show_in_menu' => true,
   'menu_icon'             => 'dashicons-welcome-widgets-menus',
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail'),
    'has_archive'   => false,
  );
  register_post_type( $this->posttype, $args );




}


static function widget_callback( $var, $args ) {
    

echo do_shortcode(wpautop($args['args']->post_content));
    
    
}


	/**
	* Register Custom Post Type
	*/
	
public function register_post_types() {

if ($this->is_this_plugin_network_activated()){

//makes sure that this is only available on the main site if network active

if (is_main_site()){

$this->register_widget_post_type();


}

} else {


$this->register_widget_post_type();


}
}

public function add_dashboard_notes() {

global $wp_meta_boxes;

$dashboard_notes_posts = $this->return_dashboard_notes_posts();


foreach ( $dashboard_notes_posts as $lhdashboardwidgetspost ){



	wp_add_dashboard_widget('lh_dashboard_widgets_'.$lhdashboardwidgetspost->post_name,  $lhdashboardwidgetspost->post_title, array('LH_dashboard_notes_plugin','widget_callback' ) , null, $lhdashboardwidgetspost);



// Get the regular dashboard widgets array 
 	// (which has our new widget already but at the end)
 
 	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
 	
 	// Backup and delete our new dashboard widget from the end of the array
 
 	$example_widget_backup = array( 'lh_dashboard_widgets_'.$lhdashboardwidgetspost->post_name => $normal_dashboard['lh_dashboard_widgets_'.$lhdashboardwidgetspost->post_name] );
 	unset( $normal_dashboard['lh_dashboard_widgets_'.$lhdashboardwidgetspost->post_name] );
 
 	// Merge the two arrays together so our widget is at the beginning
 
 	$sorted_dashboard = array_merge( $example_widget_backup, $normal_dashboard );
 
 	// Save the sorted array back into the original metaboxes 
 
 	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
	



}



}


public function plugins_loaded(){


load_plugin_textdomain( 'lh_dashboard_notes', false, basename( dirname( __FILE__ ) ) . '/languages' ); 

}



    /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
    public static function get_instance(){
        if (null === self::$instance) {
            self::$instance = new self();
        }
 
        return self::$instance;
    }



	/**
	* Constructor
	*/
	public function __construct() {


$this->filename = plugin_basename( __FILE__ );

// Hooks
add_action('init', array( $this, 'register_post_types'));
add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_notes'));

//add events at plugins_loaded, right now just translations
add_action( 'plugins_loaded', array($this,"plugins_loaded"));

}


}

$lh_dashboard_notes_instance = LH_dashboard_notes_plugin::get_instance();


}


?>