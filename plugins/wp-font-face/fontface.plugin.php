<?php
/*
Plugin Name: WP font-face
Plugin URI: http://forze.gotdns.com/wp/?release=fontface-for-wordpress
Description: CSS @font-face font replacement for Wordpress
Author: Robbert Langezaal
Author URI: http://forze.gotdns.com/wp/
Version: 1.1
Tags: @font-face, font replace, CSS, fontface
License: GPL2
*/

/*
Copyright (C) 2012  Robbert Langezaal

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

new fontface;
	
class fontface {

	function fontface()
	{
		$this->__construct();		
	} 

	function __construct()
	{	
		//initialize hook
		add_action( 'init', array( &$this, 'init' ) );		
		
		//hook for admin menu
		add_action('admin_menu', array( &$this, 'fontface_create_menu') );		

		//hook to add CSS to tinyMCE when admin options are set
		if (get_option('fontface-load-css-in-tinymce' ))
			add_filter( 'mce_css', array( &$this, 'fontface_mce_css') );
		
		//hook to add styles to tinyMCE when admin options are set
		if (get_option('fontface-load-css-style-in-tinymce' ))
			add_filter( 'tiny_mce_before_init', array( &$this, 'fontface_tinymce_style_format') );		

		register_activation_hook(__FILE__, array( &$this, 'prefix_on_activate') );									
		register_deactivation_hook(__FILE__, array( &$this, 'prefix_on_deactivate') );									
	} 

	function prefix_on_activate() {
    update_option('fontface-load-in-admin', true);
    update_option('fontface-load-css-in-tinymce', true);
    update_option('fontface-generate-css-class', true);
    update_option('fontface-load-css-style-in-tinymce', true);		    
    update_option('fontface_font_list_count', 0 );
	}
		
	function prefix_on_deactivate() {
    delete_option('fontface-css');
    delete_option('fontface_font_list');
    delete_option('fontface-load-in-admin');
    delete_option('fontface-load-css-in-tinymce');
    delete_option('fontface-generate-css-class');
    delete_option('fontface-load-css-style-in-tinymce');		    
    delete_option('fontface_font_list_count' );
	}

	function fontface_tinymce_style_format( $initArray )
	{	
			// get old styles
			if (!empty($initArray['style_formats']))
				$style_formats = json_decode( $initArray['style_formats'],true );
			
			// set styles title			
			if (count($old_style_formats) > 0)
				$style_formats[] = array('title' => 'FontFace');
			
			// update tinyMCE settings with new style(s)
			$fonts = get_option('fontface_font_list');			
			foreach ($fonts as $font)
			{
					$style_formats[] = array('title' => 'FontFace '.$font, 'selector' => 'p,h1,h2,h3,h4,h5,h6', 'classes' => $font);
			}								 
						     	   	   	
    	//json_encode results
    	$initArray['style_formats'] = json_encode( $style_formats );
				
			return $initArray;
	}	

	function get_familyname($file)
	{
			$contents = file_get_contents($file);
			
			// set pattern to look for the font-family-name
			$pattern = "/font-family.*?\'(.*?)\';/is";
			
			// match and return the font-family-name if found
			if(preg_match_all($pattern, $contents, $matches)){
			  return $matches[1][0];
			}
			else{
				return false;
			}	
	}
	
	function init()
	{
		// get list of folders in the ./font path
		$fontlist = glob(plugin_dir_path(__FILE__).'fonts/*', GLOB_ONLYDIR);
		$fonts = array();
		foreach ($fontlist as $font_path)
		{					
				if (file_exists($font_path.'/stylesheet.css'))
				{
						$fontname = basename($font_path);        				
    				$fonts[] = $fontname;
				}
		}												
		
		// check if more fonts are found than last loop -- only generate css when fontcount changes
		if ( get_option('fontface_font_list_count') != count($fonts))
			$update_css = true;
		
		// check if generated file exists -- only generate css when file is missing
		if (get_option('fontface-generate-css-class'))
			if ( !file_exists(plugin_dir_path( __FILE__ ).'generated.css') )
				$update_css = true;
		
		// check if additional css file exists -- only generates css when file is missing
		if ( !file_exists(plugin_dir_path( __FILE__ ).'additional.css') )
			file_put_contents ( plugin_dir_path( __FILE__ ).'additional.css' , get_option('fontface-css') );
			
		// add font folders to database
		update_option( 'fontface_font_list_count', count($fonts) );
		update_option( 'fontface_font_list', $fonts );										
	
	
		if ( !is_admin() || get_option('fontface-load-in-admin') ) 
		{
			global $pagenow;
			if ($pagenow!='wp-login.php') 
			{
				
				if (get_option('fontface-generate-css-class') && $update_css)
				{		
						$file = plugin_dir_path( __FILE__ ).'generated.css';
						file_put_contents($file, '/* font face generated file */');
				}
				
				$fonts = get_option('fontface_font_list');
				foreach ($fonts as $font)
				{
						$cssfile_url = plugin_dir_url(__FILE__).'fonts/'.$font.'/stylesheet.css';
						wp_register_style( 'font-face-'.$font, $cssfile_url);
    				wp_enqueue_style( 'font-face-'.$font );						        				
    
    				if (get_option('fontface-generate-css-class') && $update_css)
						{		
    						$cssfile_dir = plugin_dir_path(__FILE__).'fonts/'.$font.'/stylesheet.css';
    						$familyname = $this->get_familyname($cssfile_dir);
    						file_put_contents($file, '.'.$font.' { font-family: '.$familyname.'; }', FILE_APPEND);					
    				}
				}
				
				if (get_option('fontface-generate-css-class'))
				{		
						wp_register_style( 'font-face-gen', plugin_dir_url(__FILE__).'generated.css');
 						wp_enqueue_style( 'font-face-gen' );						        				
 				}
 				
 				wp_register_style( 'font-face', plugin_dir_url(__FILE__).'additional.css');
 				wp_enqueue_style( 'font-face' );						        				
			}
		}
	}


	function fontface_mce_css( $mce_css ) {
	  if ( !empty( $mce_css ) )
	    $mce_css .= ',';
	    
	    $mce_css .= plugins_url( 'additional.css', __FILE__ );
	    
			if (get_option('fontface-generate-css-class'))
			{		
			    $mce_css .= ','.plugins_url( 'generated.css', __FILE__ );
			}
						
			$fonts = get_option('fontface_font_list');
			foreach ($fonts as $font)
			{
					$mce_css .= ",".plugins_url('fonts/'.$font.'/stylesheet.css',__FILE__);	
			}
						
	    return $mce_css;
	}	

	function fontface_create_menu() 
	{	
		//create new top-level menu
		add_submenu_page( 'options-general.php', 'Fontface Plugin Settings', 'FontFace', 'administrator', __FILE__, array( &$this, 'fontface_settings_page') );
	
		//call register settings function
		add_action( 'admin_init', array( &$this, 'register_fontface_settings') );
	}
	
	
	function register_fontface_settings() {
		//register settings
		register_setting( 'fontface-settings-group', 'fontface-css', array( &$this, 'save_fontface_css') );
		register_setting( 'fontface-settings-group', 'fontface-load-in-admin' );
		register_setting( 'fontface-settings-group', 'fontface-load-css-in-tinymce' );
		register_setting( 'fontface-settings-group', 'fontface-generate-css-class' );
		register_setting( 'fontface-settings-group', 'fontface-load-css-style-in-tinymce' );
		
	}
		
	function save_fontface_css( $options )
	{
	    // add additional css to file for easy loading    
	    print_r($options);
	    file_put_contents ( plugin_dir_path( __FILE__ ).'additional.css' , $options );
	    return $options;
	}

	function fontface_settings_page() {
	?>
			
			<div class="wrap">
			<h2>Fontface</h2>
			
			<form method="post" action="options.php">
			    <?php settings_fields('fontface-settings-group'); ?>
			    <table class="form-table">
			    	
			    		<tr valign="top">
			        <th scope="row">Load in admin?</th>
			        <td><input type="checkbox" name="fontface-load-in-admin" value="1" <?php checked( get_option('fontface-load-in-admin'), 1 ); ?> /></td>
			        </tr>			         
			        
			        <tr valign="top">
			        <th scope="row">Load CSS in tinyMCE?</th>
			        <td><input type="checkbox" name="fontface-load-css-in-tinymce" value="1" <?php checked( get_option('fontface-load-css-in-tinymce'), 1 ); ?> /></td>
			        </tr>			         
			        
			        <tr valign="top">
			        <th scope="row">Generate CSS class for each font?</th>
			        <td><input type="checkbox" name="fontface-generate-css-class" value="1" <?php checked( get_option('fontface-generate-css-class'), 1 ); ?> /></td>
			        </tr>			         
			        
			        <tr valign="top">
			        <th scope="row">Load generated CSS class(es) to tinyMCE styles?</th>
			        <td><input type="checkbox" name="fontface-load-css-style-in-tinymce" value="1" <?php checked( get_option('fontface-load-css-style-in-tinymce'), 1 ); ?> /></td>
			        </tr>			         
			        
			        <tr valign="top">
			        <th scope="row">Additional CSS</th>
			        <td><textarea rows=15 cols=50 type="text" name="fontface-css" /><?php echo get_option('fontface-css'); ?></textarea></td>
			        </tr>			         
			        
			    </table>
			    
			    <?php submit_button(); ?>
			
			</form>
			</div>
	<?php }


	
} // class

?>