<?php
/*
Plugin Name: Hoo Hreflang Tags
Plugin URI: 
Description: Add Hreflang meta tags to the head of your multiple languages WordPress sites. It is compatible with the elementor plugin.
Version: 1.1
Author: Jimmy
Author URI: https://hoothemes.com
License: GPLv2 or later
Text Domain: hoo-hreflang-tags
*/


include_once('includes/metabox/options.php');
class hooHreflang{
	
	public function __construct(){
		
		add_action( 'admin_enqueue_scripts', array(&$this,'admin_scripts') );
		add_action( 'wp_head', array(&$this, 'register_alternate_link') );
		add_action( 'admin_menu', array(&$this, 'my_plugin_menu') );
		add_action( 'plugins_loaded', array(&$this, 'plugins_loaded' ) );
		add_filter( "plugin_action_links_".plugin_basename(__FILE__), array(&$this, "plugin_settings_link") );
		
		}
	
	public static function plugins_loaded() {
		
		load_plugin_textdomain( 'hoo-hreflang-tags', false,  basename( dirname( __FILE__ ) ) . '/languages' );
	}
	
	// Load backend scripts
	function admin_scripts(){
		global $pagenow;

		if( $pagenow == 'post.php' ){
			wp_enqueue_style( 'hoo-hreflang-admin', plugins_url('assets/css/admin.css', __FILE__) );
		}
			
	}
	
	function register_alternate_link(){
		
		if( is_singular() ){
			$tags = get_post_meta( get_the_ID(), '_hoo_alternative_tags', true);
			
			if( $tags != '' && is_array($tags) ){
				echo "<!-- Hoo hreflang tags -->\r\n";
				foreach( $tags as $tag ){
					if( $tag['alternative_url'] !='' )
						echo '<link rel="alternate" href="'.esc_url($tag['alternative_url']).'" hreflang="'.esc_attr(strtolower(str_replace('_','-', $tag['language']))).'" />'."\r\n";
				}
				echo "<!-- / Hoo hreflang tags -->\r\n";
				}
		}
		
		}
		
	/**
	 * Register settings
	*/
	function register_mysettings() {
		register_setting( 'hoo-hreflang-group', 'hoo_hreflang_post_types', array(&$this,'text_validate') );
	}

	function my_plugin_menu() {
		add_options_page( 'HREFLANG', 'HREFLANG', 'manage_options', 'hoo-hreflang-tags', array(&$this, 'my_plugin_options') );
		add_action( 'admin_init', array(&$this,'register_mysettings') );
	}
	
	
	function my_plugin_options() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'hoo-hreflang-tags' ) );
		}
		
		echo '<form action="'.esc_url(admin_url('options.php')).'" method="post"> ';
		echo '<div class="wrap">';
		echo '<h3>'.__( 'Post Types', 'hoo-hreflang-tags' ).'</h3>';
		$args = array(
		  'public'   => true,
		 // '_builtin' => true
		  );
		  
		  settings_fields( 'hoo-hreflang-group' );
		  $options     = get_option('hoo_hreflang_post_types',array('page','post'));
		  		  
		  $output = 'objects';
		  $operator = 'and';
		  
		  $post_types = get_post_types( $args, $output, $operator ); 
		  
		  foreach ( $post_types  as $post_type ) {
			$checked = '';
		  	if(  in_array( $post_type->name, $options) )
				$checked = 'checked="checked"';
		 	echo '<p><label><input name="hoo_hreflang_post_types[]" type="checkbox" '.$checked.' value="' . esc_attr($post_type->name) . '" />' . esc_html($post_type->label) . ' </label></p>';
		  }
		  
          echo '<p class="submit">';
          echo '<input type="submit" class="button-primary" value="'. esc_attr('Save Changes','hoo-hreflang-tags').'" />';
          echo '</p>';
		  
		  echo '</div>';
		  
		  echo '</form>';

	}

	// Add settings link on plugin page
	function plugin_settings_link($links) {

  		$links[] = '<a href="'.esc_url(admin_url('options-general.php?page=hoo-hreflang-tags')).'">'.__( 'Settings', 'hoo-hreflang-tags' ).'</a>';
  		return $links;
	}
		
}

new hooHreflang;