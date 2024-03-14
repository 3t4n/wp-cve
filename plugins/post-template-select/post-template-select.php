<?php
/*
  Plugin Name: Post Template Select
  Plugin URI: https://wordpress.org/plugins/post-template-select/
  Description: Use templates in Posts and Custom Post Types.
  Version: 1.3
  Author: munishsandy
  Author URI: https://profiles.wordpress.org/munishsandy
*/
/* Templates For Posts */
if(!class_exists('single_post_template_select_m') ) {
	class single_post_template_select_m {
		/*
		* Auto Load Hooks
		*/
		public function __construct() {
			add_filter( 'single_template', array(&$this, 'ulm_filter_single_template' ));
            add_action("add_meta_boxes", array(&$this, "add_ulm_custom_meta_box"));
			add_action("save_post",  array(&$this, "save_ulm_custom_meta_box"), 10, 3);
			add_action('admin_menu', array(&$this, 'ulm_single_template_admin_menu'));
	        register_activation_hook(__FILE__, array(&$this, 'ulm_single_template_install'));
		}
		/*
		* Admin Menu
		*/
		public function ulm_single_template_admin_menu() {
			add_menu_page( 
				__( 'Post Template', 'post-template-select' ),
				'Post Template',
				'manage_options',
				'ulm_post_template',
				 array(&$this,'ulm_post_template_callback'),
				 'dashicons-heart' // will replace this later
				 );	
		}
		/*
		* Install Process
		*/
		public function ulm_single_template_install() {
			        $defaultsettings = array(
											 'ulm_single_template_cpt' => array('post'),// def post
											 'ult_single_template_type' => 'page'
											 );
					$opt = get_option('ulm_single_template_options');
					if(!$opt['ulm_single_template_cpt'] || !$opt['ult_single_template_type']) {
						update_option('ulm_single_template_options', $defaultsettings);
					}   
		}
		/*
		* menu call Back
		*/
		public function ulm_post_template_callback() {
			if(is_admin() && current_user_can('manage_options')) {
				include('inc/settings.php');
			}
		}
		/*
		* Single Template Filter
		*/
		public function ulm_filter_single_template($template)
	    {
			global $wp_query;
			$postID = $wp_query->post->ID;
			$postType = $wp_query->post->post_type;
			$template_file = get_post_meta($postID , "ulm_custom_get_post_template", true);  
			$allowed_post_types = get_option('ulm_single_template_options');
			if(!empty($allowed_post_types['ulm_single_template_cpt'])) {
			if(in_array($postType, $allowed_post_types['ulm_single_template_cpt'])):
			if ( ! $template_file )
				return $template;	
				if ( file_exists( trailingslashit( STYLESHEETPATH ) . $template_file ) ):
				return STYLESHEETPATH . DIRECTORY_SEPARATOR . $template_file;
				elseif ( file_exists( TEMPLATEPATH . DIRECTORY_SEPARATOR . $template_file ) ):
				return TEMPLATEPATH . DIRECTORY_SEPARATOR . $template_file;
				endif;
	       endif;
			}
			return $template;
	  }
	  /*
	  * Template Selector
	  */
	   public function add_ulm_custom_meta_box()
	    {
			    $allowed_post_types = get_option('ulm_single_template_options');
				if(!empty($allowed_post_types['ulm_single_template_cpt'])) {
				add_meta_box("mu-meta-box-sigle", __("Choose Template"), array(&$this, "ulm_meta_box_markup"), $allowed_post_types['ulm_single_template_cpt'], "side", "high", null);
				}
	   }
	  /*
	  * Template Selector Html
	  */ 
	  public function ulm_meta_box_markup($object)
		{
		$opts = get_option('ulm_single_template_options');	
		wp_nonce_field(basename(__FILE__), "mu-meta-box-nonce");
		$theme = wp_get_theme();
				$post_templates = array();
				$files = (array) $theme->get_files( 'php', 1 ); 
				$getFile = get_post_meta($object->ID , "ulm_custom_get_post_template", true);       
				foreach ( $files as $file => $full_path ) {					
					if(isset($opts['ult_single_template_type'])) {	
					if($opts['ult_single_template_type'] == 'custom') {									
					   $headers = get_file_data( $full_path, array( 'Template Name' => 'Post Template Name'));
					} else if($opts['ult_single_template_type'] == 'page') {
					   $headers = get_file_data( $full_path, array( 'Template Name' => 'Template Name'));
					} else {
					   $headers = get_file_data( $full_path, array( 'Template Name' => 'Template Name'));
					}
					} else {
					$headers = get_file_data( $full_path, array( 'Template Name' => 'Template Name'));
					}
					    if ( empty( $headers['Template Name']))
							continue;	
						 $post_templates[$file] = $headers['Template Name'];	
					 
			}
		?>
				<div class="ulm_SINGLE">
			<select name="ulm_custom_get_post_template" id="ulm_custom_get_post_template">
				<option value='default'	<?php if ( empty($post_templates) ) { echo "selected='selected'";}?>><?php _e( 'Default Template', 'post-template-select' ); ?></option>
				<?php
				 if ( $post_templates ) :
				 foreach( $post_templates as $filename => $name ) { ?>
					<option 
						value='<?php echo $filename; ?>'
						<?php
							if ( $getFile == $filename ) {
								echo "selected='selected'";
							}
						?>><?php echo $name; ?></option>
				<?php }  endif; ?>
			</select>
				</div>
			<?php  
		}
		/*
		* Save template
		*/
		public function save_ulm_custom_meta_box($post_id, $post, $update)
		{
			if (!isset($_POST["mu-meta-box-nonce"]) || !wp_verify_nonce($_POST["mu-meta-box-nonce"], basename(__FILE__)))
				return $post_id;
		
			if(!current_user_can("edit_post", $post_id))
				return $post_id;
		
			if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
				return $post_id;
		    $allowed_post_types = get_option('ulm_single_template_options');
			if(!empty($allowed_post_types['ulm_single_template_cpt'])) {
			if(!in_array($post->post_type, $allowed_post_types['ulm_single_template_cpt']))
			  return $post_id;
			$template = sanitize_text_field($_POST[ 'ulm_custom_get_post_template']);	
				 delete_post_meta( $post_id, 'ulm_custom_get_post_template' );
				if ( ! $template || $template == 'default' ) return;
				add_post_meta( $post_id , 'ulm_custom_get_post_template', $template );
			}
		}
		/*
		* Post Types
		*/
		public function ulm_get_post_types() {
			$defaultPost = array('post');
			$args = array(
				'public'   => true,
				'_builtin' => false
		    );
	      $post_types = get_post_types($args);
	      $post_types = array_merge($post_types, $defaultPost); // merging post
		  return $post_types;
		}
		/*
		* Custom  Redirection
		*/
		public function redirect($url){
			echo '<script>window.location.href="'.$url.'"</script>';
		}
		/*
		*  Message
		*/
		public function message($type = '', $msg) {
		 if($type != '') {	
			if($type == 1) {
			 $class = 'updated';	
			} else if($type == 2) {
			  $class = 'error';	
			}
			 _e( '<div class="'.$class.' settings-error notice is-dismissible" id="setting-error-settings_updated"> 
<p><strong>'.$msg.'</strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>', 'post-template-select');
		 }
		}
	}
	new single_post_template_select_m;
}