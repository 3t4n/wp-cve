<?php
/*
Plugin Name: WP Tracking Manager
Description: Very Simple plugin to add any type of tracking code on your website in a second and also block the direct access to thank page.
Author: WP Experts Team
Author URI: https://www.wp-experts.in
Version: 1.5
*/
/**
License GPL2
Copyright 2018-2021  WP Experts Team  (email raghunath.0087@gmail.com)

This program is free software; you can redistribute it andor modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!class_exists('WpTrackingManager'))
{
    class WpTrackingManager
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
			// Installation and uninstallation hooks
			register_activation_hook(__FILE__, array(&$this, 'wp_tracking_manager_activate'));
			register_deactivation_hook(__FILE__, array(&$this, 'wp_tracking_manager_deactivate'));
			// admin settings links
			add_filter("plugin_action_links_".plugin_basename(__FILE__), array(&$this,'wp_tracking_manager_settings_link'));
            // register actions
			add_action('admin_menu', array(&$this, 'wp_tracking_manager_add_menu'));
			add_action('admin_init', array(&$this, 'wp_tracking_manager_init_settings'));
			
			add_action( 'admin_bar_menu', array(&$this,'toolbar_link_to_wtm'), 999 );
			
			// Is enable settings from admin
			$isEnable = get_option('wtm_enable');
			if(!$isEnable){
			return;
			}
			//define action for create new meta boxes
			add_action( 'add_meta_boxes', array(&$this, 'add_wp_tracking_manager_post_meta_box') );
			//Define action for save to "Video" Meta Box fields Value
			add_action( 'save_post', array(&$this, 'save_wp_tracking_manager_post_meta_box') );
        } // END public function __construct

       /**
		* hook to add link under adminmenu bar
		*/	
			 
		public function toolbar_link_to_wtm( $wp_admin_bar ) {
			$args = array(
				'id'    => 'wtm_menu_bar',
				'title' => 'Tracking Manager',
				'href'  => admin_url('options-general.php?page=wp_tracking_manager'),
				'meta'  => array( 'class' => 'wtm-toolbar-page' )
			);
			$wp_admin_bar->add_node( $args );
			//second lavel
			$wp_admin_bar->add_node( array(
				'id'    => 'wtm-second-sub-item',
				'parent' => 'wtm_menu_bar',
				'title' => 'Settings',
				'href'  => admin_url('options-general.php?page=wp_tracking_manager'),
				'meta'  => array(
					'title' => __('Settings'),
					'target' => '_self',
					'class' => 'sm_menu_item_class'
				),
			));
		}
		/*-------------------------------------------------
				 Start Taxonomy Meta Boxes
		 ------------------------------------------------- */
		/**
		  * Taxonomy Term Meta Fields
		  */
		public function wp_tracking_manager_meta_fields($prefix='')
		{		 
		  $prefix = '_wtm_page_';
		  $meta_box=array(
			'id'      => 'wp-tracking-meta-box',
			'title'   => 'WP Tracking Manager',
			'fields'  => array(
								array(
								'title' => 'WP Tracking Manager',
								'desc' => 'Modify meta title by editing it right here',
								'id'   => $prefix.'seom_heading',
								'name'   => $prefix.'seom_heading',
								'type' => 'heading',
								'placeholder'  => '',
								'std'  => ''
								),
								array(
								'title' => 'Is this thank-you page?',
								'desc' => 'Block direct access to this page.',
								'id'   => $prefix.'thank_you',
								'name'   => $prefix.'thank_you',
								'type' => 'checkbox',
								'std'  => 'yes'
								),
								array(
								'title' => 'Header Tracking code:',
								'desc' => '',
								'id'   => $prefix.'header',
								'name'   => $prefix.'header',
								'type' => 'textarea',
								'placeholder'  => '',
								'std'  => ''
								),
								array('title' => 'Footer Tracking code:',
								'desc' => '',
								'id'   => $prefix.'footer',
								'name'   => $prefix.'footer',
								'type' => 'textarea',
								'placeholder'  => '',
								'std'  => ''
								)
							));
		   return $meta_box;
		}

		/*-------------------------------------------------
				 Start POST Meta Boxes
		 ------------------------------------------------- */
	   public function wtm_sanitize_fields($type='',$val='')
       {
		// Is this textarea
		if($type='textarea')
		{
		  $val = balanceTags($val);
		}else
		{
			$val = sanitize_text_field($val);
		}
		return $val;
	   }
		public function add_wp_tracking_manager_post_meta_box(){
				$screens = array('page');
				foreach ( $screens as $screen ) {
					add_meta_box(
						'wp-tracking-manager-meta-box',
						__( 'WP Tracking Manager', 'mrwebsolution' ),
						array(&$this,'show_wp_tracking_manager_meta_box'),
						$screen
					);
				}
			}
		 public function show_wp_tracking_manager_meta_box()
			{
				global $post;
				$wp_tracking_manager_meta_box = $this->wp_tracking_manager_meta_fields('_wp_tracking_manager_post_');
				wp_nonce_field( '_wp_tracking_manager_comman_box_field', '_wp_tracking_manager_comman_box_meta_box_once' );
				echo '<table class="form-table"><tbody>';
				foreach ($wp_tracking_manager_meta_box['fields'] as $field) {
					// get current post meta data
					$meta = get_post_meta($post->ID, $field['id'], true);
					echo '<tr>';
					if($field['type']!=='heading'){
					echo '<td><label for="', $field['id'], '">', $field['title'], '</label>','</td>';}
					switch ($field['type']) {
					case 'text':
					echo '<td><input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" placeholder="', $field['placeholder'] ? $field['placeholder'] : '', '" size="60" />', '<br />', $field['desc'],'</td>';
					break;
					case 'checkbox':
					echo '<td><input type="checkbox" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'],'"', checked( $meta, 'yes' ),'/>', '<br /><i>', $field['desc'],'</i></td>';
					break;
					case 'textarea':
					echo '<td><textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4"  placeholder="', $field['placeholder'] ? $field['placeholder'] : '', '">', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'],'</td>';
					break;
					case 'select':
					echo '<td><select name="', $field['id'], '" id="', $field['id'], '" >';
					$optionVal=$field['options'];
					foreach($optionVal as $optVal):
					if($meta==$optVal){
					$valseleted =' selected="selected"';}else {
						 $valseleted ='';
						}
					echo '<option value="', $optVal, '" ',$valseleted,' id="', $field['id'], '">', $optVal, '</option>';
				endforeach;
				echo '</select>','<br />',$field['desc'],'</td>';
				break;
				echo '</tr>';
				}

				}
				
			echo '</tbody></table>';
		}
		 public function save_wp_tracking_manager_post_meta_box($post_id) {
			global $post_types;
			 $post_types = array('page');
			// Check if our nonce is set.
			 if ( ! isset( $_POST['_wp_tracking_manager_comman_box_meta_box_once'] ) ) {
					return;
				}
			$wp_tracking_manager_meta_box = $this->wp_tracking_manager_meta_fields('_wp_tracking_manager_post_');
			// check autosave
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
			}

			// check permissions
			if (!in_array($_POST['post_type'],$post_types)) 
			{
				if (!current_user_can('edit_page', $post_id))
				return $post_id;
			} 
			elseif(!current_user_can('edit_post', $post_id)){
			return $post_id;
			}
			
			foreach ($wp_tracking_manager_meta_box['fields'] as $field) 
			{
				if($field['type']=='heading')
				continue;
				
				$old = get_post_meta($post_id, $field['id'], true);
				$new = $this->wtm_sanitize_fields($field['type'],$_POST[$field['id']]);
				if ($new && $new != $old){
				 update_post_meta($post_id, $field['id'], $new);
				} 
				elseif ('' == $new && $old) {
				delete_post_meta($post_id, $field['id'], $old);
				}
			}
		}
		/*-------------------------------------------------
				 End POST Meta Boxes
		 ------------------------------------------------- */
		/**
		 * Initialize some custom settings
		 */     
		public function wp_tracking_manager_init_settings()
		{
			// register the settings for this plugin
			register_setting('wp-tracking-manager-group', 'wtm_enable');
			register_setting('wp-tracking-manager-group', 'wtm_header_script');
			register_setting('wp-tracking-manager-group', 'wtm_footer_script');
			$post_types = $custompostype = get_post_types(array('public' => true,'_builtin' => false),'names','and'); 
			array_push($post_types,'post');array_push($post_types,'page');
			//register post type of header
			foreach($post_types as $val)
			{
				register_setting('wp-tracking-manager-group', 'wtm_header_script_'.$val);
			}
			//register post type of footer
			foreach($post_types as $val)
			{
				register_setting('wp-tracking-manager-group', 'wtm_footer_script_'.$val);
			}
		} // END public function init_custom_settings()
		/**
		 * add a menu
		 */     
		public function wp_tracking_manager_add_menu()
		{
			add_options_page('WP Tracking Manager Settings', 'WP Tracking Manager', 'manage_options', 'wp_tracking_manager', array(&$this, 'wp_tracking_manager_settings_page'));
		} // END public function add_menu()

		/**
		 * Menu Callback
		 */     
		public function wp_tracking_manager_settings_page()
		{
			if(!current_user_can('manage_options'))
			{
				wp_die(__('You do not have sufficient permissions to access this page.'));
			}

			// Render the settings template
			include(sprintf("%s/lib/settings.php", dirname(__FILE__)));
			//include(sprintf("%s/css/admin.css", dirname(__FILE__)));
			// Style Files
			wp_register_style( 'wp_tracking_manager_admin_style', plugins_url( 'css/wtm-admin.css',__FILE__ ) );
			wp_enqueue_style( 'wp_tracking_manager_admin_style' );
			// JS files
			wp_register_script('wp_tracking_manager_admin_script', plugins_url('/js/wtm.js',__FILE__ ), array('jquery'));
            wp_enqueue_script('wp_tracking_manager_admin_script');
		} // END public function plugin_settings_page()
        /**
         * Activate the plugin
         */
        public static function wp_tracking_manager_activate()
        {
            // Do nothing
        } // END public static function activate
    
        /**
         * Deactivate the plugin
         */     
        public static function wp_tracking_manager_deactivate()
        {
            // Do nothing
        } // END public static function deactivate
        // Add the settings link to the plugins page
		function wp_tracking_manager_settings_link($links)
		{ 
			$settings_link = '<a href="options-general.php?page=wp_tracking_manager">Settings</a>'; 
			array_unshift($links, $settings_link); 
			return $links; 
		}
    } // END class WpTrackingManager
} // END if(!class_exists('WpTrackingManager'))
if(class_exists('WpTrackingManager'))
{
    // instantiate the plugin class
    $wp_tracking_manager_plugin_template = new WpTrackingManager;
}
// Render the hooks functions
include(sprintf("%s/lib/class.php", dirname(__FILE__)));
