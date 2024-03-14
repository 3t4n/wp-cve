<?php
/*
Plugin Name: WP Prayer II
Plugin URI: https://www.goministry.com/
Description: Prayer Management System
Version: 2.4.6
Author: Go Prayer
Author URI: https://www.goprayer.com/
License: GPLv2 or later
Text Domain: wp-prayers-request
Domain Path: /languages
Requires at least: 5.0
Tested up to: 6.3
*/

if ( ! defined( 'ABSPATH' ) )  die( 'Nope, not accessing this' ); 
define('UPR_PLUGIN', esc_html__('User Prayers Request', 'upr'));
define('UPR_PATH', plugin_basename(__FILE__));

//include shortcodes
include(plugin_dir_path(__FILE__) . 'inc/upr_shortcode.php');
add_action('admin_menu', 'upr_prayer_sub_menu');

class user_prayers_request
{	
	//magic function (triggered on initialization)
	public function __construct(){	
		add_action('init', array($this,'upr_prayers_management_init')); //register prayers content type
		add_action('add_meta_boxes', array($this,'upr_add_prayers_meta_boxes')); //add meta boxes
		add_action('save_post_prayers', array($this,'upr_save_prayers')); //save prayers
		add_action('wp_enqueue_scripts', array($this,'upr_enqueue_public_scripts_and_styles')); //public scripts and styles
			
		register_activation_hook(__FILE__, array($this,'upr_plugin_activate')); //activate hook
		register_deactivation_hook(__FILE__, array($this,'upr_plugin_deactivate')); //deactivate hook	
		add_action( 'wp_ajax_ajax_pray_response',array($this,'upr_ajax_pray_response'));
		add_action( 'wp_ajax_nopriv_ajax_pray_response', array($this,'upr_ajax_pray_response'));
		add_action( 'wp_ajax_ajax_do_pray',array($this,'upr_ajax_do_pray'));
		add_action( 'wp_ajax_nopriv_ajax_do_pray', array($this,'upr_ajax_do_pray'));
		
		// edit column
		add_filter( 'manage_prayers_posts_columns', array($this,'upr_set_custom_edit_prayers_columns') );
		add_action( 'manage_prayers_posts_custom_column' , array($this,'upr_custom_prayers_column'), 10, 2 );
	}
	function upr_set_custom_edit_prayers_columns($columns) {
			unset( $columns['author'] );
			unset( $columns['comments'] );
			unset( $columns['date'] );
			unset( $columns['taxonomy-prayertypes'] );
			$columns['prayers_name'] = __('Author');
			$columns['taxonomy-prayertypes'] = __('Categories');
			$columns['date'] = __('Date');
			$columns['comments'] = __('Comments');		
			return $columns;
	}		
	function upr_custom_prayers_column( $column, $post_id ) {
			switch ( $column ) {		
				case 'prayers_name' :
				$post = get_post($post_id);
				$author_id = $post->post_author;				
				$post_author = get_user_by( 'ID', $author_id);
				if(isset($post_author->display_name)){ $author_display_name = $post_author->display_name;} else {$author_display_name='';}
				if($author_display_name!='' || !empty($author_display_name)){
					$authorLink = get_author_posts_url($author_id);
					echo '<a href="'.$authorLink.'" target="_blank">'.esc_html($author_display_name).'</a>';
				} else {
					$author = get_post_meta($post_id,'prayers_name',true);
					echo esc_html(ucwords($author));
				}
					break;		
			}
		}
	function upr_ajax_do_pray(){
		global $current_user; wp_get_current_user();
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			//check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		if(is_user_logged_in()) {
			$user_id = $current_user->display_name;
		} else {
			$user_id = $ip;
		}
		$prayer_id = $_REQUEST['prayer_id'];
		$prayer_post = array(
		  'post_title'    => $user_id,
		  'post_content'  => $prayer_id,
		  'post_type'  => 'prayers_performed',
		  'post_status' => 'publish'
		);
		// Insert the post into the database
		$post_id = wp_insert_post( $prayer_post );
		if($post_id){
			$prayer_count = get_post_meta($prayer_id,'prayers_count',true);if (empty($prayer_count)){$prayer_count=0;}
			$prayer_count = $prayer_count + 1;
			update_post_meta($prayer_id,'prayers_count',$prayer_count);
			update_post_meta($post_id,'prayer_id',$prayer_id);
			update_post_meta($post_id,'user_id',$user_id);
			_e('Prayed','wp-prayers-request'); exit;
		}
	}
	function upr_ajax_pray_response(){			
	global $current_user; wp_get_current_user();
	$time = current_time('mysql');
	$comment_approved = 1 ;
	if(get_option('comment_moderation')==1) $comment_approved = 0;

	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$user_ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$user_ip = $_SERVER['REMOTE_ADDR'];
	}
 	$user_agent = $_SERVER['HTTP_USER_AGENT']; 
 	$comment_type = "pray";
	if(is_user_logged_in()) {
		$author_user_id = $current_user->ID;
		$comment_author = $current_user->display_name;
		$comment_author_email = $current_user->user_email;
		$comment_content = sanitize_textarea_field($_REQUEST['pray_reply']);
		$comment_author_url = $current_user->user_url;
	} else {
		$author_user_id = '';
		$comment_author = sanitize_text_field($_REQUEST['comment_author']);
		$comment_author_email = sanitize_email($_REQUEST['comment_author_email']);
		$comment_content = sanitize_textarea_field($_REQUEST['pray_reply']);
		$comment_author_url = sanitize_text_field($_REQUEST['comment_author_url']);
	}
	$data = array(
		'comment_post_ID' => sanitize_text_field($_REQUEST['prayer_id']),
		'comment_author' => $comment_author,
		'user_id' => $author_user_id,
		'comment_author_email' => $comment_author_email,
		'comment_content' => $comment_content,
		'comment_type' => 'pray',
		'comment_parent' => sanitize_text_field($_REQUEST['comment_parent']),
		'comment_date' => $time,
		'comment_approved' => $comment_approved,
		'comment_author_url' => $comment_author_url,
		'comment_author_IP' => $user_ip,
		'comment_agent' => $user_agent
	);	
	$comment_id = wp_insert_comment($data);
	$comment = get_comment( $comment_id );
	if($comment_id){
		$moderation_notify = get_option( 'moderation_notify' );
		$blacklisted = wp_blacklist_check($comment->comment_author, $comment->comment_author_email, $comment->comment_author_url, $comment->comment_content, $comment->comment_author_IP, $comment->comment_agent);
		if($blacklisted){
			wp_spam_comment( $comment_id );
		} else {
			if ( check_comment($comment->comment_author, $comment->comment_author_email, $comment->comment_author_url, $comment->comment_content, $comment->comment_author_IP, $comment->comment_agent, $comment->comment_type) ){
				wp_set_comment_status( $comment_id, 1 );			
			} else {
				wp_notify_moderator( $comment_id );	
				wp_set_comment_status( $comment_id, 0 );
			}			
		}
		echo $comment_id;
	} else {
		echo "Error";
	}
	exit;}
	
	//register the prayers custom post type
	public function upr_prayers_management_init() {
		load_plugin_textdomain( 'wp-prayers-request', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => __('Categories'),
			'singular_name'     => __( 'Category'),
			'search_items'      => __( 'Search Category'),
			'all_items'         => __( 'All Categories'),
			'parent_item'       => __( 'Parent Category'),
			'parent_item_colon' => __( 'Parent Category'),
			'edit_item'         => __( 'Edit Category'),
			'update_item'       => __( 'Update Category'),
			'add_new_item'      => __( 'Add New Category'),
			'new_item_name'     => __( 'New Category Name'),
			'menu_name'         => __('Categories'),
		);	
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'genre' ),
		);	
		register_taxonomy( 'prayertypes', array( 'prayers' ), $args );
		
		$labels = array(
			'name'               => __('Prayers','wp-prayers-request'),
			'singular_name'      => __('Prayers','wp-prayers-request'),
			'menu_name'          => __('Prayer Request','wp-prayers-request'),
			'name_admin_bar'     => __('Manage Prayers','wp-prayers-request'),
			'add_new'            => __('Add Prayer','wp-prayers-request'),
			'add_new_item'       => __('Title','wp-prayers-request'),
			'new_item'           => __('Add Prayer','wp-prayers-request'),
			'edit_item'          => __('Title','wp-prayers-request'),
			'view_item'          => __('View Pray','wp-prayers-request'),
			'all_items'          => __('List Prayers','wp-prayers-request'),
			'search_items'       => __('Search','wp-prayers-request'),
			'parent_item_colon'  => __('Parent Pray','wp-prayers-request'),
			'not_found'          => __('No Pray Found','wp-prayers-request'),
			'not_found_in_trash' => __('No pray found in trash','wp-prayers-request'),
		);
		$args = array(
			'labels'  => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'prayers' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'menu_icon' 		 => plugins_url( 'assets/images/pray.png' , __FILE__ ),
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'author', 'comments')
		);
		register_post_type( 'prayers', $args );
		
		// perforamnce 
		$labels = array(
			'name'               => __('Prayers Performed','wp-prayers-request'),
			'singular_name'      => __('Prayers Performed','wp-prayers-request'),
			'menu_name'          => __('Prayers Performed','wp-prayers-request'),
			'name_admin_bar'     => __('Prayers Performed','wp-prayers-request'),
			'search_items'       => __('Search','wp-prayers-request'),
		);
		$args = array(
			'labels'  => $labels,
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=prayers',
			'query_var'          => false,
			'rewrite'            => array( 'slug' => 'prayers_performed' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor')
		);
		register_post_type( 'prayers_performed', $args );
		
		// add options
		// add_option('upr_prayer_list_title', 'Prayers');
		add_option('upr_no_prayer_per_page', 10);
		add_option('upr_login_not_required_request', 1);			
		add_option('upr_prayer_send_email', 1);
		add_option('upr_prayer_send_admin_email', 1);			
		add_option('upr_prayer_default_status_pending', 1);
		add_option('upr_hide_prayer_button', 0);
		add_option('upr_hide_prayer_count', 0);
		add_option('upr_display_username_on_prayer_listing', 0);			
		add_option('upr_prayer_hide_captcha', 0);
		add_option('upr_prayer_show_country', 0);
		add_option('upr_show_prayer_category', 0);
		add_option('upr_time_interval_pray_prayed_button', 0);
	}
	//adding meta boxes for the location content type*/
	public function upr_add_prayers_meta_boxes(){	
		add_meta_box(
			'wp_prayer_meta_box', //id
			__('Details'), //name
			array($this,'upr_prayers_meta_box_display'), //display function
			'prayers', //post type
			'side', //location
			'high' //priority
		);
	}
	
	//display function used for our custom location meta box*/
	public function upr_prayers_meta_box_display($post){
	
		//set nonce field
		wp_nonce_field('prayers_nonce', 'prayers_nonce_field');
	
		//collect variables
		$prayers_name = get_post_meta($post->ID,'prayers_name',true);
		$prayers_email = get_post_meta($post->ID,'prayers_email',true);
		$prayers_website = get_post_meta($post->ID,'prayers_website',true);
		$prayers_count = get_post_meta($post->ID,'prayers_count',true);
		$prayers_country = get_post_meta($post->ID,'prayers_country',true);
		?>
		<div class="field-container">
			<?php 
			//before main form elementst hook
			do_action('upr_prayers_admin_form_start'); 
			?>
			<div class="field">
				<p><strong><?php _e('Name','wp-prayers-request')?></strong><br>
				<input type="text" name="prayers_name" size="30" id="prayers_name" value="<?php echo esc_html($prayers_name);?>"/></p>
			</div>
			<div class="field">
				<p><strong><?php _e('Email','wp-prayers-request')?></strong><br>
				<input type="email" name="prayers_email"  size="30" id="prayers_email" value="<?php echo esc_html($prayers_email);?>"/></p>
			</div>
			<div class="field">
				<p><strong><?php _e('Website','wp-prayers-request')?></strong><br>
				<input type="url" name="prayers_website" size="30" id="prayers_website" value="<?php echo esc_html($prayers_website);?>"/></p>
			</div>
			<div class="field">
				<p><strong><?php _e('Prayer Count','wp-prayers-request')?></strong><br>
				<input type="number" name="prayers_count" size="30" id="prayers_count" value="<?php echo esc_html($prayers_count);?>"/></p>
			</div>
		<?php 
		//after main form elementst hook
		do_action('upr_prayers_admin_form_end'); 
		?>
		</div>
		<?php	
	}
		
	//triggered on activation of the plugin (called only once)
	public function upr_plugin_activate(){  
		//call our custom content type function
		$this->upr_prayers_management_init();
		//flush permalinks
		flush_rewrite_rules();
	}
		
	//triggered when adding or editing a location
	public function upr_save_prayers($post_id){
	
		//check for nonce
		if(!isset($_POST['prayers_nonce_field'])){
			return $post_id;
		}   
		//verify nonce
		if(!wp_verify_nonce($_POST['prayers_nonce_field'], 'prayers_nonce')){
			return $post_id;
		}
		//check for autosave
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
			return $post_id;
		}
	
		//get our name, email and website fields
		$prayers_name = isset($_POST['prayers_name']) ? sanitize_text_field($_POST['prayers_name']) : '';
		$prayers_email = isset($_POST['prayers_email']) ? sanitize_email($_POST['prayers_email']) : '';
		$prayers_website = isset($_POST['prayers_website']) ? esc_url($_POST['prayers_website']) : '';
		$prayers_country = isset($_POST['prayers_country']) ? sanitize_text_field($_POST['prayers_country']) : '';
		
	
		//update phone, memil and address fields
		update_post_meta($post_id, 'prayers_name', $prayers_name);
		update_post_meta($post_id, 'prayers_email', $prayers_email);
		update_post_meta($post_id, 'prayers_website', $prayers_website);
		update_post_meta($post_id, 'prayers_country', $prayers_country);
	
		//location save hook 
		//used so you can hook here and save additional post fields added via 'wp_location_meta_data_output_end' or 'wp_location_meta_data_output_end'
		do_action('prayers_admin_save',$post_id, $_POST);
	
	}
		
	//enqueues scripts and styled on the front end
	public function upr_enqueue_public_scripts_and_styles(){
		wp_enqueue_script('jquery');
		global $post;
		if(isset($post->post_content)){
		if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'upr_list_prayers') || has_shortcode( $post->post_content, 'upr_form') ) {
			wp_enqueue_script('upr_scripts', plugin_dir_url(__FILE__). '/assets/upr_prayers.js');
		}}
		wp_enqueue_style('upr_frontend', plugin_dir_url(__FILE__). '/assets/css/frontend.css');	
	}
	
	//trigered on deactivation of the plugin (called only once)
	public function upr_plugin_deactivate(){
		//flush permalinks
		// add options
		delete_option('upr_prayer_list_title');
		delete_option('upr_no_prayer_per_page');
		delete_option('upr_login_not_required_request');			
		delete_option('upr_prayer_send_email');		
		delete_option('upr_prayer_send_admin_email');			
		delete_option('upr_prayer_default_status_pending');
		delete_option('upr_hide_prayer_button');
		delete_option('upr_hide_prayer_count');
		delete_option('upr_display_username_on_prayer_listing');			
		delete_option('upr_prayer_hide_captcha');
		delete_option('upr_prayer_show_country');
		delete_option('upr_show_prayer_category');
		delete_option('upr_pray_prayed_button_ip');
		delete_option('upr_time_interval_pray_prayed_button');
		flush_rewrite_rules();
	}
}
function upr_pray_email_settings() 
{ 
	include(plugin_dir_path(__FILE__) . 'inc/upr_email_settings.php');
}
function upr_pray_settings(){
	include(plugin_dir_path(__FILE__) . 'inc/upr_settings.php');
}
function upr_prayer_sub_menu(){
	add_submenu_page('edit.php?post_type=prayers', __('Email Settings','wp-prayers-request'), __('Email Settings','wp-prayers-request'), 'manage_options', 'pray-email-settings', 'upr_pray_email_settings');
	add_submenu_page('edit.php?post_type=prayers', __('Settings'), __('Settings'), 'manage_options', 'pray-settings', 'upr_pray_settings');
}

// initialize
global $userprayersrequests, $prayers_shortcode;
$userprayersrequests = new user_prayers_request();
$prayers_shortcode = new upr_shortcode();
?>