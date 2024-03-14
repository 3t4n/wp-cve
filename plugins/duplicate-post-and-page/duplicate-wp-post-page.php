<?php
/**
 * @package  Duplicate_Post_Page
 * @version 1.0
 */
/*
Plugin Name: Duplicate Post and Page
Plugin URI: https://wordpress.org/plugins/duplicate-wp-post-page/
Description: This plugin creates a duplicate of post or page using single click and supports Gutenberg.
Author: Falcon Solutions
Author URI: http://falconsolutions.co
Version: 1.0
License: GPLv2
Text Domain: duplicate-post-page
*/
if ( ! defined( 'ABSPATH' ) ) exit;
if (!defined("duplicate_wp_post_directory")) define("duplicate_wp_post_directory", plugin_basename(dirname(__FILE__)));
if (!class_exists('duplicate_wp_post')) {
	class duplicate_wp_post {

		/* AutoLoad Hooks */
		public function __construct() {
			$option = get_option('duplicate_wp_post_options');
			register_activation_hook(__FILE__, array(&$this, 'duplicate_wp_post_install'));
			add_action('admin_menu', array(&$this, 'duplicate_post_options_page'));
			add_filter( 'plugin_action_links', array(&$this, 'duplicate_post_plugin_action_links'), 10, 2 );
			add_action( 'admin_action_duplicate_post_as_draft', array(&$this,'duplicate_post_as_draft') ); 
			add_filter( 'post_row_actions', array(&$this,'duplicate_post_link'), 10, 2);
			add_filter( 'page_row_actions', array(&$this,'duplicate_post_link'), 10, 2);
			if (isset($option['duplicate_posteditor']) && $option['duplicate_posteditor'] == 'gutenberg') {
				add_action('admin_head', array(&$this, 'duplicate_post_button_guten'));
			} else {
				add_action( 'post_submitbox_misc_actions', array(&$this,'duplicate_wp_post_custom_button'));
			}
			add_action( 'wp_before_admin_bar_render', array(&$this, 'duplicate_wp_post_admin_bar_link'));
		}  
		
		/* Activation plugin Hook */
		public function duplicate_wp_post_install() {
			$defaultsettings = array('duplicate_post_status'    	=> 'draft',
									 'duplicate_post_redirect'   	=> 'to_list',
									 'duplicate_posteditor' 		=> 'classic',
									 'duplicate_post_suffix'    	=> '',
									 'duplicate_post_link_title'  	=> '', );
			$option = get_option('duplicate_wp_post_options');
			if(!$option['duplicate_post_status']) {
				update_option('duplicate_wp_post_options', $defaultsettings);
			} 
		}
		
		/* Plugin Action Links */
		public function duplicate_post_plugin_action_links($links, $file){
			return $links;
		}

		/* Page Title and Dashboard Menu */
		public function duplicate_post_options_page(){
			add_options_page( __( 'Duplicate Post and Page', 'duplicate_wp_post' ), __( 'Duplicate Post', 'duplicate_wp_post' ), 'manage_options', 'duplicate_post_settings',array(&$this, 'duplicate_post_settings'));
		}

		/*Include plugin setting file*/
		public function duplicate_post_settings(){
			if(current_user_can( 'manage_options' )){
			   include('duplicate-wp-post-page-setting.php');
			}
		}
	   
		/*Important function*/
		public function duplicate_post_as_draft() {
			global $wpdb;

			/* sanitize GET POST REQUEST */
			$post_copy = sanitize_text_field( $_POST["post"] );
			$get_copy = sanitize_text_field( $_GET['post'] );
			$request_copy = sanitize_text_field( $_REQUEST['action'] );

			$option = get_option('duplicate_wp_post_options');
			$suffix = !empty($option['duplicate_post_suffix']) ? ' -- '.$option['duplicate_post_suffix'] : '';

			$post_status = !empty($option['duplicate_post_status']) ? $option['duplicate_post_status'] : 'draft';
			$redirectit = !empty($option['duplicate_post_redirect']) ? $option['duplicate_post_redirect'] : 'to_list';

			if (! ( isset( $get_copy ) || isset( $post_copy ) || ( isset($request_copy) && 'duplicate_post_as_draft' == $request_copy ) ) ) {
				wp_die('No post!');
			}
			$returnpage = '';

			/* Get post id */
			$post_id = (isset($get_copy) ? $get_copy : $post_copy );

			$post = get_post( $post_id );
			
			$current_user = wp_get_current_user();
			$new_post_author = $current_user->ID;

			/* Create the post Copy */
			if (isset( $post ) && $post != null) {
				/* Post data array */
				$args = array('comment_status' => $post->comment_status,
				'ping_status' => $post->ping_status,
				'post_author' => $new_post_author,
				'post_content' => (isset($option['duplicate_posteditor']) && $option['duplicate_posteditor'] == 'gutenberg') ? wp_slash($post->post_content) : $post->post_content,
				'post_excerpt' => $post->post_excerpt,
				'post_name' => $post->post_name,
				'post_parent' => $post->post_parent,
				'post_password' => $post->post_password,
				'post_status' => $post_status,
				'post_title' => $post->post_title.$suffix,
				'post_type' => $post->post_type,
				'to_ping' => $post->to_ping,
				'menu_order' => $post->menu_order );
				$new_post_id = wp_insert_post( $args );

				$taxonomies = get_object_taxonomies($post->post_type);
				if(!empty($taxonomies) && is_array($taxonomies)) {
					foreach ($taxonomies as $taxonomy) {
						$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
						wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
					}
				}
				  
				$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
				if (count($post_meta_infos)!=0) {
					$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
					foreach ($post_meta_infos as $meta_info) {
					$meta_key = $meta_info->meta_key;
					$meta_value = addslashes($meta_info->meta_value);
					$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
					}
					$sql_query.= implode(" UNION ALL ", $sql_query_sel);
					$wpdb->query($sql_query);
				}

				/* Redirect after clone */
				if($post->post_type != 'post') {
					$returnpage = '?post_type='.$post->post_type;
				}
				if(!empty($redirectit) && $redirectit == 'to_list') {
					wp_redirect( admin_url( 'edit.php'.$returnpage ) );
				} else if(!empty($redirectit) && $redirectit == 'to_page') {
					wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
				} else {
					wp_redirect( admin_url( 'edit.php'.$returnpage ) );
				}
				exit;
			} else {
				wp_die('Error! Post creation failed: ' . $post_id);
			}
		}

		/* Add link to action */
		public function duplicate_post_link( $actions, $post ) {
			$option = get_option('duplicate_wp_post_options');
			$link_title = !empty($option['duplicate_post_link_title']) ? $option['duplicate_post_link_title'] : 'Duplicate';    
			$option = get_option('duplicate_wp_post_options');
			$post_status = !empty($option['duplicate_post_status']) ? $option['duplicate_post_status'] : 'draft';
			if (current_user_can('edit_posts')) {
				$actions['dpp'] = '<a href="admin.php?action=duplicate_post_as_draft&amp;post=' . $post->ID . '" title="Clone this as '.$post_status.'" rel="permalink">'.$link_title.'</a>';
			}
			return $actions;
		}
		
		/* Add link to edit Post */
		public function duplicate_wp_post_custom_button(){
			$option = get_option('duplicate_wp_post_options');
			$link_title = !empty($option['duplicate_post_link_title']) ? $option['duplicate_post_link_title'] : 'Duplicate';
			$option = get_option('duplicate_wp_post_options');
			$post_status = !empty($option['duplicate_post_status']) ? $option['duplicate_post_status'] : 'draft';
			global $post;
			$html  = '<div id="major-publishing-actions">';
			$html .= '<div id="export-action">';
			$html .= '<a href="admin.php?action=duplicate_post_as_draft&amp;post=' . $post->ID . '" title="Duplicate this as '.$post_status.'" rel="permalink">'.$link_title.'</a>';
			$html .= '</div>';
			$html .= '</div>';
			echo $html;
		}
		 
		/* Add the duplicate link to edit screen - gutenberg */
		public function duplicate_post_button_guten() {
			global $post;
			if ($post) {
				$option = get_option('duplicate_wp_post_options');
				$post_status = !empty($option['duplicate_post_status']) ? $option['duplicate_post_status'] : 'draft';
				if (isset($option['duplicate_posteditor']) && $option['duplicate_posteditor'] == 'gutenberg') { ?>
					<style> .link_gutenberg {text-align: center; margin-top: 15px;} .link_gutenberg a {text-decoration: none; display: block; height: 40px; line-height: 28px; padding: 3px 12px 2px; background: #0073AA; border-radius: 3px; border-width: 1px; border-style: solid; color: #ffffff; font-size: 16px; } .link_gutenberg a:hover { background: #23282D; border-color: #23282D; }</style>       
					<script>
						jQuery(window).load(function(e){var duplicate_post_id = "<?php echo $post->ID; ?>";
						var duplicate_post_title = "Duplicate this as <?php echo $post_status; ?>";
						var duplicate_post_link = '<div class="link_gutenberg">';
							duplicate_post_link += '<a href="admin.php?action=duplicate_post_as_draft&amp;post='+duplicate_post_id+'" title="'+duplicate_post_title+'">Duplicate</a>';
							duplicate_post_link += '</div>';
						jQuery('.edit-post-post-status').append(duplicate_post_link);
						});
					</script>
				<?php 
				}
			}
		}    
		
		/* Admin Bar clone*/
		public function duplicate_wp_post_admin_bar_link() {
			global $wp_admin_bar;
			global $post;
			$option = get_option('duplicate_wp_post_options');
			$post_status = !empty($option['duplicate_post_status']) ? $option['duplicate_post_status'] : 'draft';
			$current_object = get_queried_object();
			if ( empty($current_object) )
				return;
			if ( ! empty( $current_object->post_type )	&& ( $post_type_object = get_post_type_object( $current_object->post_type ) )&& ( $post_type_object->show_ui || $current_object->post_type  == 'attachment') ) {
				$wp_admin_bar->add_menu( array(
				'parent' => 'edit',
				'id' => 'dpp_this',
				'title' => __("Duplicate this as ".$post_status."", 'duplicate_wp_post'),
				'href' => admin_url().'admin.php?action=duplicate_post_as_draft&amp;post=' . $post->ID
				) );
			}
		}

		/* Url Redirect */	
		static function duplicate_page_redirect($url) {
			echo '<script>window.location.href="'.$url.'"</script>';
		}
	}
	new duplicate_wp_post;
}
?>