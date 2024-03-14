<?php
	
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines the general utilities class.

 *
 * @since      2.0.2
 */

class SimpleForm_Util {

	/**
	 * Get form settings
     *
	 * @since    2.1.7
	 */
	
	public static function sform_settings($form_id) {
	   
       if ( $form_id == '1' ) { 
 	         $settings = get_option('sform_settings');
       } else { 
             $settings_option = get_option('sform_'.$form_id.'_settings');
             $settings = $settings_option != false ? $settings_option : get_option('sform_settings');
       }
       
       return $settings;
  
	}

	/**
	 * Get form attributes
     *
	 * @since    2.1.7
	 */
	
	public static function sform_attributes($form_id) {
	   
       if ( $form_id == '1' ) { 
             $attributes = get_option('sform_attributes');
       } else { 
             $attributes_option = get_option('sform_'.$form_id.'_attributes');
             $attributes = $attributes_option != false ? $attributes_option : get_option('sform_attributes');
       }
       
       return $attributes;
  
	}

	/**
	 * Search all shortcodes ids
     *
	 * @since    2.0.2
	 */
	
	public static function sform_ids() {
	   
       global $wpdb;
       $table_name = "{$wpdb->prefix}sform_shortcodes"; 
       $form_ids = $wpdb->get_col( "SELECT id FROM $table_name" );
       
       return $form_ids;
  
	}

	/**
	 * Search for all forms used in the post content
     *
	 * @since    2.0.5
	 */

	public static function used_forms($content,$type) {
	
	   $used_forms = array();
	   
	   // Search for any use of SimpleForm as shortcode
	   if ($type == 'shortcode') {
	     
         $lastPos = 0;
         $positions = array();
         while ( ( $lastPos = strpos($content, '[simpleform', $lastPos)) !== false ) {
           $positions[] = $lastPos;
           $lastPos = $lastPos + strlen('[simpleform');
         }
         foreach ($positions as $value) {
	       $split = substr($content, $value);
           $shortcode = explode(']', $split)[0];
           if ( $shortcode == '[simpleform' ) { 
	         $form_id = '1'; 
	       } 
	       else { 
		     $form_id = strpos($shortcode, 'id') !== false && isset(explode('id', $shortcode)[1]) && trim(str_replace(array( '=', '"' ), '', explode('id', $shortcode)[1])) != '' ? str_replace(array( '=', '"' ), '', explode('id', $shortcode)[1]) : ''; 
		   }
           $used_forms[] = $form_id;
         }
         
       }

	   // Search for any use of SimpleForm
	   if ($type == 'all') {
		   	   
	     // Search for shortcodes
         $lastPos = 0;
         $positions = array();
         while ( ( $lastPos = strpos($content, '[simpleform', $lastPos)) !== false ) {
           $positions[] = $lastPos;
           $lastPos = $lastPos + strlen('[simpleform');
         }
         foreach ($positions as $value) {
	       $split = substr($content, $value);
           $shortcode = explode(']', $split)[0];
           if ( $shortcode == '[simpleform' ) { 
	         $form_id = '1'; 
	       } 
	       else { 
		     $form_id = strpos($shortcode, 'id') !== false && isset(explode('id', $shortcode)[1]) && trim(str_replace(array( '=', '"' ), '', explode('id', $shortcode)[1])) != '' ? str_replace(array( '=', '"' ), '', explode('id', $shortcode)[1]) : ''; 
		   }
           $used_forms[] = $form_id;
         }
       
	     // Search for blocks
         if ( class_exists('SimpleForm_Block') ) {
         if ( has_blocks( $content ) ) {
	       $block_class = new SimpleForm_Block(SIMPLEFORM_NAME,SIMPLEFORM_VERSION);
	       $ids = $block_class->get_sform_block_ids($content);
           $used_forms = array_merge($used_forms, $ids);
         }
         }

	   }
	   
	   return $used_forms;
	   
	}
	
	/**
	 * Get a pages list that use simpleform in the post content
     *
	 * @since    2.0.2
     * @version  2.1.3
	 */
	 	 
	public static function form_pages($form_id) {
		
       global $wpdb;
       if ( $form_id != '0' ) {
         $query = $wpdb->get_var($wpdb->prepare("SELECT form_pages FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $form_id));
         $form_pages = isset($query) ? explode(',', $query) : array();
       }
       if ( $form_id == '0' ) {
	      $form_pages = array();
          $sform_pages = $wpdb->get_col( "SELECT form_pages FROM {$wpdb->prefix}sform_shortcodes" );
           foreach ($sform_pages as $list) { 
	        if ( ! empty($list) ) { 
	           $form_pages = array_unique(array_merge($form_pages,explode(',',$list)));
	        }
          }
         update_option('sform_pages',$form_pages);
       }
              
       return $form_pages;
  
	}
		
	/**
	 * Get widget area name
     *
	 * @since    2.0.2
	 */

	public static function widget_area_name($key) {
		
	   $widget_area = '';
	   $sidebars_widgets = get_option('sidebars_widgets');
       global $wp_registered_sidebars;

	   foreach ( $sidebars_widgets as $sidebar => $widgets ) {
	     if ( is_array( $widgets ) ) {
            $search = 'block-'.$key;
            if ( in_array($search, $widgets) ) {
                 $widget_area = isset($wp_registered_sidebars[$sidebar]['name']) ? $wp_registered_sidebars[$sidebar]['name'] : ''; 
            }
         }
       }

       return $widget_area;
  
	}
	
    /**
     * Update additional style to enqueue.
     *
     * @since    2.1.8
     */

    public function additional_style($id, $additional_css) {

      if ( $additional_css ) { 
	      
	    $additionalStyle = get_option('sform_additional_style') != false ? get_option('sform_additional_style') : '';
	    
        if ( $additionalStyle ) {
	      $search_form_style_start = '/*'.$id.'*/';
	      $split_style = explode($search_form_style_start, $additionalStyle);
   	      if ( isset($split_style[1]) ) {
		    $search_form_style_end = '/* END '.$id.'*/';
		    $split_form_style = explode($search_form_style_end, $split_style[1]);
	        $previous_style = isset($split_form_style[1]) ? $split_style[0] . $split_form_style[1] : $split_style[0]; 
          }
	      else {
	        $previous_style = $additionalStyle; 
          }	        
	    }
        else {
	      $previous_style = ''; 
        }
        
	    $form_style = '/*'.$id.'*/' . $additional_css . '/* END '.$id.'*/';
        $forms_style = $previous_style.$form_style;
	    update_option('sform_additional_style',$forms_style);
	      
      }
      
    }

    /**
     * Update block style to enqueue.
     *
     * @since    2.1.8
     */

    public function block_style($form_id, $css_settings) {

      if ( $css_settings ) { 
	      
	    $blockStyle = get_option('sform_block_style') != false ? get_option('sform_block_style') : '';
	    
        if ( $blockStyle ) {
	      $search_form_style_start = '/*'.$form_id.'*/';
	      $split_style = explode($search_form_style_start, $blockStyle);
   	      if ( isset($split_style[1]) ) {
		    $search_form_style_end = '/* END '.$form_id.'*/';
		    $split_form_style = explode($search_form_style_end, $split_style[1]);
	        $previous_style = isset($split_form_style[1]) ? $split_style[0] . $split_form_style[1] : $split_style[0]; 
          }
	      else {
	        $previous_style = $blockStyle; 
          }	        
	    }
        else {
	      $previous_style = ''; 
        }
        
	    $form_style = '/*'.$form_id.'*/' . $css_settings . '/* END '.$form_id.'*/';
        $forms_style = $previous_style.$form_style;
	    update_option('sform_block_style',$forms_style);
	      
      }
      
    }
	
    /**
     * Update additional scripts to enqueue.
     *
     * @since    2.1.8
     */

    public function additional_script($form_id,$settings) {
      
      $ajax = ! empty( $settings['ajax_submission'] ) ? esc_attr($settings['ajax_submission']) : 'false'; 
      $ajax_error = ! empty( $settings['ajax_error'] ) ? stripslashes(esc_attr($settings['ajax_error'])) : __( 'Error occurred during AJAX request. Please contact support!', 'simpleform' );
      $outside_error = ! empty( $settings['outside_error'] ) ? esc_attr($settings['outside_error']) : 'bottom';
      $outside = $outside_error == 'top' || $outside_error == 'bottom' ? 'true' : 'false';
      $multiple_spaces = ! empty( $settings['multiple_spaces'] ) ? esc_attr($settings['multiple_spaces']) : 'false';

      if ( $multiple_spaces == 'true' || $ajax == 'true' ) { 

	    $current_block_script = get_option('sform_additional_script') != false ? get_option('sform_additional_script') : '';
        if ( $current_block_script ) {
	      $search_form_script_start = '/*'.$form_id.'*/';
	      $split_script = explode($search_form_script_start, $current_block_script);
   	      if ( isset($split_script[1]) ) {
		    $search_form_script_end = '/* END '.$form_id.'*/';
		    $split_form_script = explode($search_form_script_end, $split_script[1]);
	        $previous_script = isset($split_form_script[1]) ? $split_script[0] . $split_form_script[1] : $split_script[0]; 
          }
	      else {
	        $previous_script = $current_block_script; 
          }	        
	    }
        else {
	      $previous_script = ''; 
        }
        
        $spaces_script = $multiple_spaces == 'true' ? 'jQuery(document).ready(function(){jQuery("input[parent=\''.$form_id.'\'],textarea[parent=\''.$form_id.'\']").on("input",function(){jQuery(this).val(jQuery(this).val().replace(/\s\s+/g," "));});});' : '' ;
        $ajax_script = $ajax == 'true' ? 'var outside'. $form_id .' = "' .$outside .'"; var ajax_error'. $form_id .' = "' .$ajax_error .'";' : '' ;
	    $block_script = '/*'.$form_id.'*/' . $spaces_script . $ajax_script . '/* END '.$form_id.'*/';
        $additional_scripts = $previous_script.$block_script;
	    update_option('sform_additional_script',$additional_scripts);
	    
      }
      
    }
	
}

new SimpleForm_Util();