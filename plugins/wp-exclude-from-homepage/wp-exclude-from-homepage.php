<?php /*
 * Plugin Name: 	WP Exclude From Homepage
 * Plugin URI:		http://wordpress.org/extend/plugins/wp-exclude-from-homepage/ 
 * Description: 	Exclude categories, tags, posts or pages from your homepage (without breaking pagination)
 * Tags: 			Categories, Tags, Posts, Pages, Pagination
 * Version: 		1.1.3
 * Author: 			iziwebavenir
 * Author URI: 		http://www.iziwebavenir.com
 * Date:			16 Février 2014
 * License: 		GPL2
 * 
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 * 
 * @package WP Exclude From Homepage
 * @Version 1.1.3
 * @author iziwebavenir <iziwebavenir@gmail.com>
 * @copyright Copyright (c) 2013, iziwebavenir
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

global $wp_version;

$exit_msg = 'wp-assets require WordPress 2.6 or newer.  <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a>';

if (version_compare($wp_version, "2.6", "<")) {

	exit( $exit_msg );

}

if ( !class_exists('EFHomepage') ) : 
 	 
	class EFHomepage {
	
		private $plugin_domain = "wp-exclude-from-homepage"; 
		private $plugin_page_option = "wp-exclude-from-homepage-settings";
		 
		private $plugin_options_cat = "cat-to-exclude-from-homepage";
		private $plugin_options_post = "post-to-exclude-from-homepage";
		private $plugin_options_tag = "tag-to-exclude-from-homepage";
		private $plugin_options_disable = "disable-exclude-from-homepage"; // To disable options without delete them
		 
		private $plugin_url;
		
		function EFHomepage(){
		
			$this->plugin_url = trailingslashit( WP_PLUGIN_URL.'/'. dirname( plugin_basename( __FILE__ ) ) );
				
			add_action('plugins_loaded', array( &$this , 'EFHomepage_language' ));
			
			add_action( 'admin_enqueue_scripts', array(&$this,'EFHomepage_admin_style'));
			
			add_action('admin_enqueue_scripts', array( &$this, 'EFHomepage_admin_script' )); 
 			 
			add_action( 'admin_menu',  array(&$this, 'admin_menu'));
		       
			add_filter('pre_get_posts',array(&$this,'EFHchange_home'));
			 
		}
		 
		function admin_menu(){ // Administration Settings Menu
		
			add_options_page(__('Settings | WP Exclude From Homepage',$this->plugin_domain),__('Exclude From Homepage',$this->plugin_domain), 8 , $this->plugin_page_option , array(&$this, 'handle_options'));
		
		}
		
		function handle_options() { // Load Settings Page
				
			if($_POST) $this->EFHomepage_settings_forms_action();
				
			include( 'inc/wp-exclude-from-homepage-settings.php');
		
		}
		
		function EFHomepage_language(){ // Load localization file
				 
			load_plugin_textdomain($this->plugin_domain,false,'/'.dirname( plugin_basename( __FILE__ ) ) .'/lang/');
		
		}
  
		function EFHomepage_admin_style(){ // Admin CSS
		
			wp_enqueue_style($this->plugin_domain,$this->plugin_url.'css/style.css');
		
		}
		
		function EFHomepage_admin_script() { // Admin javasript
			 	
			wp_enqueue_script( $this->plugin_domain , $this->plugin_url.'js/script.js', array( 'jquery'), true);
			
		}
	 
		function EFHomepage_settings_form($type){ // Forms ?>
		 
		 	<?php $action_url = "options-general.php?page={$this->plugin_page_option}&_wpnonce=".wp_create_nonce($type);?>
		 	
		 	<form action="<?php echo admin_url($action_url)?>" method="POST">
				 
				<?php if($type=="cat"){ 
					
					$options_recorded = get_option($this->plugin_options_cat);
					 
					if($options_recorded){
						
						$cats_ids_to_exclude = $options_recorded;
					
					}else{ 
						
						$cats_ids_to_exclude = array();
					
					}
					
					$args_cat = array ('hide_empty'=> 0);

					$cats = get_categories($args_cat); 
					
					foreach($cats as $cat){ ?>
					
						<input type="checkbox" name="cat[]" value="<?php echo $cat->term_id;?>" <?php if(in_array($cat->term_id, $cats_ids_to_exclude)){ echo " checked "; }?> /> <?php echo $cat->name;?> <span class="span-count">(<?php echo $cat->count;?> <?php _e("posts",$this->plugin_domain);?>)</span><br />
						 
					<?php } ?>
						 
				<?php }elseif($type=="tag"){
					 
					$options_recorded = get_option($this->plugin_options_tag);
					 
					if($options_recorded){
						
						$tags_ids_to_exclude = $options_recorded;
					
					}else{ 
						
						$tags_ids_to_exclude = array();
					
					}
					
					$tags = get_tags(); 
					
					foreach($tags as $tag){ ?>
					
						<input type="checkbox" name="tag[]" value="<?php echo $tag->term_id;?>" <?php if(in_array($tag->term_id, $tags_ids_to_exclude)){ echo " checked "; }?> /> <?php echo $tag->name;?> <span class="span-count">(<?php echo $tag->count;?> <?php _e("posts",$this->plugin_domain);?>)</span><br />
						 
					<?php } ?>
						 
				<?php }elseif($type=="post"){ 
				
					$options_recorded = get_option($this->plugin_options_post);
					  
					if($options_recorded){
						
						$posts_ids_to_exclude = $options_recorded;
					
					}else{ 
						
						$posts_ids_to_exclude = array();
					
					} ?>
					
					<label for="add_posts_ids" ><?php _e("Separate posts id with coma",$this->plugin_domain);?></label> <input type="text" value="" name="add_posts_ids" /> <br />
					
					<?php if(!empty($posts_ids_to_exclude)){?>
						
						<br /><span class="EFHspan-posts-excluded"><?php _e('Posts excluded',$this->plugin_domain);?> : </span><br /> 
						
						<?php 
						foreach($posts_ids_to_exclude as $post_id){ ?>
							
								<input type="checkbox" name="post[]" value="<?php echo $post_id;?>"  checked  /> <?php echo get_the_title($post_id)!="" ? get_the_title($post_id) : __("No title",$this->plugin_domain);?> <span class="span-count">(<?php _e("Post ID",$this->plugin_domain);?> : <?php echo $post_id?>)</span><br />
								 
							<?php } ?>
							
					<?php } ?>
					
				<?php }elseif($type=="disable"){ ?>
					
					<input type="checkbox" name="disable" value="1"  <?php if(get_option($this->plugin_options_disable)){ echo "checked";} ?>  /> <?php _e("Disable options",$this->plugin_domain);?> <br />
					
					<input type="hidden" value="1" name="disable_form" />								
					
				<?php }?>
				
					<input type="hidden" value="1" name="add_type_<?php echo $type;?>" />
					
					<br /><input type="submit" value="<?php _e('Save',$this->plugin_domain);?>" class="button-primary"/>
					
				</form>
			
		<?php }
		
		function EFHomepage_settings_forms_action(){  // Forms action after submissions
			
			if($_POST){
				
				$nonce=$_REQUEST['_wpnonce'];
				
				if (! (wp_verify_nonce($nonce, 'reset') OR wp_verify_nonce($nonce, 'cat') OR wp_verify_nonce($nonce, 'tag') OR wp_verify_nonce($nonce, 'post') OR wp_verify_nonce($nonce, 'disable') )) die('Security check : you try to open a page without good security key. Are you sure to open a secure link?');
				 
				if(isset($_POST['add_type_cat']) && isset($_POST['cat'])){  // CATEGORIES
					 
					$cats_to_exclude = $_POST['cat'];
					 
					update_option($this->plugin_options_cat,$cats_to_exclude);
					 
				}elseif (isset($_POST['add_type_cat']) && !isset($_POST['cat'])){
					
					delete_option($this->plugin_options_cat);
					
				}elseif(isset($_POST['add_type_tag']) && isset($_POST['tag'])){ // TAGS
					
					$cats_to_exclude = $_POST['tag'];
					
					update_option($this->plugin_options_tag,$cats_to_exclude);
					 
				}elseif (isset($_POST['add_type_tag']) && !isset($_POST['tag'])){
					
					delete_option($this->plugin_options_tag);
					
				}elseif(isset($_POST['add_type_post'])){ // POSTS AND PAGES
					 
					if(isset($_POST['post'])){ 
						
						$ids_to_exclude_from_form  = $_POST['post']; 
					
					} else {
	
						$ids_to_exclude_from_form = array();
	
					}
					
					$ids_input_text = trim($_POST['add_posts_ids']); 
					
					if($ids_input_text!="" && strpos($ids_input_text,',')>0){
	
						$explode_ids_input_text = explode(',',$ids_input_text);
						
						foreach ($explode_ids_input_text as $post_id_form_input){
	
							if($post_id = $this->EFHomepage_check_post_id($post_id_form_input)){
	
								$ids_to_exclude_from_form[] = $post_id;
							
							}
						
						}
					
					}elseif($ids_input_text!=""){
	
						if($post_id = $this->EFHomepage_check_post_id($ids_input_text)){
	
							$ids_to_exclude_from_form[] = $post_id;
						
						}
					
					} 
					
					if(!empty($ids_to_exclude_from_form)){
	
						$ids_to_exclude_from_form = array_unique($ids_to_exclude_from_form);
						
						update_option($this->plugin_options_post,$ids_to_exclude_from_form);
					
					}else{
	
						delete_option($this->plugin_options_post);
					
					}
					
				}elseif(isset($_POST['disable']) && $_POST['disable_form']){ // DISABLE PLUGIN OPTIONS
					
					update_option($this->plugin_options_disable,1);
	
				}elseif(!isset($_POST['disable']) && $_POST['disable_form']){ 
	
					delete_option($this->plugin_options_disable);
	
				}elseif(isset($_POST['EFHreset_options'])){ // RESET OPTIONS
	
					$this->EFHomepage_reset_options();
	
				}
				
			}
			
		}
		
		function EFHomepage_check_post_id($post_id){ // CHECK IF AN INTEGER IS REALLY A POST ID OR PAGE ID
			
			global $wpdb;

			$post_id = intval(trim($post_id));  

			$post_exists = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE ID = $post_id AND (post_type = 'post' OR post_type = 'page') ", 'ARRAY_A'));
			
			if ($post_exists) {

				return $post_id;

			}

		}
		
		function EFHomepage_disable($disable = 1){ // 1 = disable options ; 0 = able options
				
			if($disable==1){

				update_option($this->plugin_options_disable,1);

			}elseif($disable==0){

				delete_option($this->plugin_options_disable);

			}
		
		}
		
		function EFHomepage_reset_options(){ // RESET ALL OPTIONS
		 
			delete_option($this->plugin_options_cat);
			
			delete_option($this->plugin_options_post);

			delete_option($this->plugin_options_tag);
			
			delete_option($this->plugin_options_disable);
			  
		}
		
		function EFHomepage_has_options(){ // has options and disable_option off
				
			if(get_option($this->plugin_options_disable)){ // If the disable options is not recorded

				return false;
			
			}else{

				if(get_option($this->plugin_options_cat) OR get_option($this->plugin_options_post) OR get_option($this->plugin_options_tag)){

					return true;

				}else{

					return false;

				}

			}
				
		}
	
		function EFHchange_home($query) {  // TO INSERT IN HOOK FILTER pre_get_posts
			
			if($query->is_home && $this->EFHomepage_has_options()){
			 
				global $wpdb;
				 
				$cats_to_exclude = get_option($this->plugin_options_cat); 

				if($cats_to_exclude){  
 
					(array) $cats_exclude_before = $query->get("category__not_in");
					
					if(!empty($cat_exclude_before) && is_array($cats_exclude_before)){ 
						
						$cats_to_exclude = array_unique(array_merge($cats_to_exclude,$cats_exclude_before));
					
					}
					
					$query->set("category__not_in",$cats_to_exclude);

				}
				
				$tags_to_exclude = get_option($this->plugin_options_tag);

				if($tags_to_exclude){

					(array) $tags_exclude_before = $query->get("tag__not_in");
						
					if(!empty($tags_exclude_before) && is_array($tags_exclude_before)){
					
						$tags_to_exclude = array_unique(array_merge($tags_to_exclude,$tags_exclude_before));
							
					}
					
					$query->set("tag__not_in",$tags_to_exclude);

				}
				
				$posts_to_exclude = get_option($this->plugin_options_post);
				
				if($posts_to_exclude){

					(array) $posts_exclude_before = $query->get("post__not_in");
					
					if(!empty($posts_exclude_before) && is_array($posts_exclude_before)){
							
						$posts_to_exclude = array_unique(array_merge($posts_to_exclude,$posts_exclude_before));
							
					} 
						
					$query->set("post__not_in",$posts_to_exclude);

				}
			 
			}
			
			return $query;
			
		}
		
		function activate() {
		 
		}
		
		function deactivate() {
		 
		}
		 
	}
	
	
else :

	exit ("Class EFHomepage already declared!");
	
endif;

if(!isset($EFHomepage)){

	$EFHomepage = new EFHomepage;
	
}

if (isset($EFHomepage)){

	register_activation_hook( __FILE__, array(&$EFHomepage, 'activate') );
	
	register_deactivation_hook( __FILE__, array(&$EFHomepage,'deactivate' ) );

} ?>