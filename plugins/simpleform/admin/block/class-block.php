<?php

 /**
 * Defines the block of the plugin.
 *
 * @since      2.0
 */

class SimpleForm_Block {

	/**
	 * The ID of this plugin.
	 *
	 */
	
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 */
	
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 */
	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the block.
     *
	 * @since    2.0
	 */
	 
	public function register_block() {
		
      $asset_file = include( plugin_dir_path( __FILE__ ) . 'build/index.asset.php');
      $metadata = (array)json_decode(file_get_contents(__DIR__ . '/block.json'), true);
    
      wp_register_script(
        'sform-editor-script',
        plugins_url( 'build/index.js', __FILE__ ),
        $asset_file['dependencies'],
        $asset_file['version']  
      );	

	  global $wpdb; 
	  $forms = $wpdb->get_results( "SELECT id, name FROM {$wpdb->prefix}sform_shortcodes WHERE widget = '0' AND status != 'trash'", 'ARRAY_A' );
      $empty_value = array( 'id' => '', 'name' => __( 'Select an existing form', 'simpleform' ) );
      array_unshift($forms , $empty_value);
      $id_list = array_column($forms, 'id');           
      $above_ids = array();
      $below_ids = array();
      $default_ids = array();
      $basic_ids = array();
      $rounded_ids = array();
      $minimal_ids = array();
      $transparent_ids = array();
      $highlighted_ids = array();
              
      foreach ($id_list as $id) {
	    if ($id) { 
		  switch ($id) {
          case $id > '1':
          $form_attributes = get_option('sform_'.$id.'_attributes');
          $form_settings = get_option('sform_'.$id.'_settings');
          break;
          default:
          $form_attributes = get_option('sform_attributes');
          $form_settings = get_option('sform_settings');
          }
	    if ( ! empty($form_attributes['introduction_text']) ) { array_push($above_ids, $id); }	
	    if ( ! empty($form_attributes['bottom_text']) ) { array_push($below_ids, $id); }
 	    if ( ! empty($form_settings['form_template']) ) { 
	       if ( $form_settings['form_template'] == 'default' ) { array_push($default_ids, $id); }	    
  	       if ( $form_settings['form_template'] == 'basic' ) { array_push($basic_ids, $id); }	    
 	       if ( $form_settings['form_template'] == 'rounded' ) { array_push($rounded_ids, $id); }	    
 	       if ( $form_settings['form_template'] == 'minimal' ) { array_push($minimal_ids, $id); }	    
 	       if ( $form_settings['form_template'] == 'transparent' ) { array_push($transparent_ids, $id); }	    
 	       if ( $form_settings['form_template'] == 'highlighted' ) { array_push($highlighted_ids, $id); }	    
		}	    
        }	
      }
	
      wp_localize_script('sform-editor-script', 'sformblockData', array(
	    'forms' => $forms,
        'cover_url' => plugins_url( 'img/block-preview.png', __FILE__ ),
        'logo_url' => plugins_url( 'img/simpleform-icon.png', __FILE__ ),
		'above' => $above_ids,
		'below' =>	$below_ids,
        'default_style' => $default_ids,
        'basic_style' => $basic_ids,
        'rounded_style' => $rounded_ids,
        'minimal_style' => $minimal_ids,
        'transparent_style' => $transparent_ids,
        'highlighted_style' => $highlighted_ids,
      ));
      
      wp_register_style('sform-editor-style', plugins_url( 'build/index.css', __FILE__ ),[], filemtime( plugin_dir_path( __FILE__ ) . 'build/index.css' ) );	

      register_block_type('simpleform/form-selector', 
        array_merge($metadata,
        array(
	    'title' => __( 'SimpleForm', 'simpleform' ),
	    'description' => __( 'Display a contact form', 'simpleform' ),
        'render_callback' => array( $this, 'sform_render_block' ),
        'editor_script' => 'sform-editor-script',
        'editor_style' => 'sform-editor-style'
        )
        )
      );
      
      wp_set_script_translations( 'sform-editor-script', 'simpleform' );
    
	}

	/**
	 * Render a form given the specified attributes
     *
	 * @since    2.0
	 */

	public function sform_render_block($attributes) {
      
  	  $form_id = ! empty( $attributes['formId'] ) && absint($attributes['formId']) ? $attributes['formId'] : '';
        
	  if ( empty( $form_id ) ) {
	    return '';
	  }
      
      $settings = $form_id != '' && $form_id != '1' && get_option('sform_'.$form_id.'_settings') != false ? get_option('sform_'.$form_id.'_settings') : get_option('sform_settings');
      $form_attributes = $form_id != '' && $form_id != '1' && get_option('sform_'.$form_id.'_attributes') != false ? get_option('sform_'.$form_id.'_attributes') : get_option('sform_attributes');
      $css_settings = '';
  	  $bgcolor = ! empty( $attributes['bgColor'] ) ? $attributes['bgColor'] : '';
  	  $labelcolor = ! empty( $attributes['labelColor'] ) ? $attributes['labelColor'] : '';
  	  $fieldsbordercolor = ! empty( $attributes['fieldsBorderColor'] ) ? $attributes['fieldsBorderColor'] : '';
  	  $checkedcolor = ! empty( $attributes['checkedColor'] ) ? $attributes['checkedColor'] : '';
  	  $borderradius = ! empty( $attributes['borderRadius'] ) ? $attributes['borderRadius'] : '';
      $css_settings .= ! empty($bgcolor) ? '#form-wrap-'.$form_id.' {background-color: '.$bgcolor.';}' : '';
      $css_settings .= ! empty($labelcolor) ? '#form-wrap-'.$form_id.' label.sform {color: '.$labelcolor.';}' : '';
      $css_settings .= ! empty($fieldsbordercolor) ? '#form-'.$form_id.':not(.highlighted) input, #form-'.$form_id.':not(.highlighted) textarea, #form-'.$form_id.':not(.highlighted) div.captcha, #form-'.$form_id.':not(.highlighted) input.checkbox:not(:checked)+label .checkmark {border-color: '.$fieldsbordercolor.';} #form-'.$form_id.'.rounded input.checkbox:not(:checked)+label .checkmark {background-color: '.$fieldsbordercolor.';}' : '';
      $css_settings .= ! empty($checkedcolor) ? '#form-'.$form_id.' input.checkbox:checked+label .checkmark {border-color: '.$checkedcolor.'; background-color: '.$checkedcolor.';}' : '';
      $css_settings .= ! empty($borderradius) ? '#form-wrap-'.$form_id.' {border-radius: '.$borderradius.'px;}' : '';
   	  $buttoncolor = ! empty( $attributes['buttonColor'] ) ? $attributes['buttonColor'] : '';
  	  $buttonbordercolor = ! empty( $attributes['buttonBorderColor'] ) ? $attributes['buttonBorderColor'] : '';
  	  $buttontextcolor = ! empty( $attributes['buttonTextColor'] ) ? $attributes['buttonTextColor'] : '';
  	  $hoverbuttoncolor = ! empty( $attributes['hoverButtonColor'] ) ? $attributes['hoverButtonColor'] : '';
  	  $hoverbuttonbordercolor = ! empty( $attributes['hoverButtonBorderColor'] ) ? $attributes['hoverButtonBorderColor'] : '';
  	  $hoverbuttontextcolor = ! empty( $attributes['hoverButtonTextColor'] ) ? $attributes['hoverButtonTextColor'] : '';
      $css_settings .= ! empty($buttoncolor) ? '#submission-'.$form_id.' {background-color: '.$buttoncolor.';}' : '';
      $css_settings .= ! empty($buttonbordercolor) ? '#submission-'.$form_id.' {border-color: '.$buttonbordercolor.';}' : '';
      $css_settings .= ! empty($buttontextcolor) ? '#submission-'.$form_id.' {color: '.$buttontextcolor.';}' : '';
      $css_settings .= ! empty($hoverbuttoncolor) ? '#submission-'.$form_id.':hover {background-color: '.$hoverbuttoncolor.';}' : '';
      $css_settings .= ! empty($hoverbuttonbordercolor) ? '#submission-'.$form_id.':hover {border-color: '.$hoverbuttonbordercolor.';}' : '';
      $css_settings .= ! empty($hoverbuttontextcolor) ? '#submission-'.$form_id.':hover {color: '.$hoverbuttontextcolor.';}' : '';
      $additionalStyle = ! empty( $form_attributes['additional_css'] ) ? esc_attr($form_attributes['additional_css']) : '';
      
      // Update style to enqueue    
      $util = new SimpleForm_Util();
      $util->block_style($form_id,$css_settings);
      
	  $anchor = ! empty( $attributes['formAnchor'] ) ? 'id="' . $attributes['formAnchor'] . '"' : '';
      $topmargin = ! empty( $attributes['topMargin'] ) && absint( $attributes['topMargin'] ) ? 'margin-top:'. $attributes['topMargin'] .'px;' : '';
      $rightmargin = ! empty( $attributes['rightMargin'] ) && absint( $attributes['rightMargin'] ) ? 'margin-right:'. $attributes['rightMargin'] .'px;' : '';
      $bottommargin = ! empty( $attributes['bottomMargin'] ) && absint( $attributes['bottomMargin'] ) ? 'margin-bottom:'. $attributes['bottomMargin'] .'px;' : '';
      $leftmargin = ! empty( $attributes['leftMargin'] ) && absint( $attributes['leftMargin'] ) ? 'margin-left:'. $attributes['leftMargin'] .'px;' : '';
  	  $toppadding = ! empty( $attributes['topPadding'] ) && absint( $attributes['topPadding'] ) ? 'padding-top:'. $attributes['topPadding'] .'px;' : '';
      $rightpadding = ! empty( $attributes['rightPadding'] ) && absint( $attributes['rightPadding'] ) ? 'padding-right:'. $attributes['rightPadding'] .'px;' : '';
      $bottompadding = ! empty( $attributes['bottomPadding'] ) && absint( $attributes['bottomPadding'] ) ? 'padding-bottom:'. $attributes['bottomPadding'] .'px;' : '';
      $leftpadding = ! empty( $attributes['leftPadding'] ) && absint( $attributes['leftPadding'] ) ? 'padding-left:'. $attributes['leftPadding'] .'px;' : '';
      $spacing = ! empty($topmargin) || ! empty($rightmargin) || ! empty($bottommargin) || ! empty($leftmargin) || ! empty($toppadding) || ! empty($rightpadding) || ! empty($bottompadding) || ! empty($leftpadding) ? true : false;
	  $anchor_tag = $spacing ? '' : $anchor;
      $frontend_notice = ! empty( $settings['frontend_notice'] ) ? esc_attr($settings['frontend_notice']) : 'true';
      $form_template = ! empty( $settings['form_template'] ) ? esc_attr($settings['form_template']) : 'default'; 
      $form_direction = ! empty( $form_attributes['form_direction'] ) ? esc_attr($form_attributes['form_direction']) : 'ltr';
      $class_direction = $form_direction == 'rtl' ? 'rtl' : '';
      $shortcode = $form_id != '1' ? '[simpleform id="'.$form_id.'" type="block"]' : '[simpleform type="block"]';         
      $title  = ! empty( $attributes['displayTitle'] ) ? true : false;
      $heading = $title == true && ! empty( $attributes['titleHeading'] ) && in_array($attributes['titleHeading'], array('h1','h2','h3','h4','h5','h6' )) ? esc_attr( $attributes['titleHeading'] ) : '';
      $alignment = $title == true && ! empty( $attributes['titleAlignment'] ) && in_array($attributes['titleAlignment'], array('left','center','right' )) ? esc_attr( $attributes['titleAlignment'] ) : '';
      $title_alignment = ! empty($alignment) ? 'class="sform align-'. $alignment .'"' : 'class="sform"';
      $start_tag = ! empty($heading) ? '<'. $heading .' '.$anchor_tag.' '.$title_alignment.'>' : '';
      $end_tag = ! empty($heading) ? '</'. $heading .'>' : '';
      $form_title = $title == true && ! empty( $form_attributes['form_name'] ) ? $start_tag . esc_attr($form_attributes['form_name'] ) . $end_tag : '';
      $success_class = isset( $_GET['sending'] ) && $_GET['sending'] == 'success' && isset( $_GET['form'] ) && $_GET['form'] == $form_id ? 'success' : '';
      $start_wrap = $css_settings || $spacing || ! empty($anchor) ? '<div id="form-wrap-'.$form_id.'" ' .$anchor.' style="'.$topmargin.$rightmargin.$bottommargin.$leftmargin.$toppadding.$rightpadding.$bottompadding.$leftpadding.'" class="form-wrap '.$success_class.'">' : '';
      $end_wrap = $css_settings || $spacing || ! empty($anchor) ? '</div>' : '';
      $description  = ! empty( $attributes['formDescription'] ) ? true : false;
      $ending  = ! empty( $attributes['formEnding'] ) ? true : false;
      $form_description = $description == true && ! empty( $form_attributes['introduction_text'] ) ? '<div id="sform-introduction-'.$form_id.'" class="sform-introduction '.$class_direction.'">'.stripslashes(wp_kses_post($form_attributes['introduction_text'])).'</div>' : '';
      $bottom_text = $ending == true && ! empty( $form_attributes['bottom_text'] ) ? '<div id="sform-bottom-'.$form_id.'" class="sform-bottom '.$class_direction.'">'.stripslashes(wp_kses_post($form_attributes['bottom_text'])).'</div>' : '';
      $is_gb_editor = defined( 'REST_REQUEST' ) && REST_REQUEST;

	  if ( $is_gb_editor ) {
      	$return_html = $start_wrap . $form_title . $form_description . '<fieldset disabled>' . do_shortcode($shortcode) .'</fieldset>' . $bottom_text . $end_wrap . '<style>' . $css_settings . $additionalStyle . '</style>';	
      } 

      else {
	    $form = do_shortcode($shortcode);
        $above_form = isset( $_GET['sending'] ) && $_GET['sending'] == 'success' && isset( $_GET['form'] ) && $_GET['form'] == $form_id ? '' : $form_description;
        $below_form = isset( $_GET['sending'] ) && $_GET['sending'] == 'success' && isset( $_GET['form'] ) && $_GET['form'] == $form_id ? '' : $bottom_text;
	    $contact_form = strpos( $form, __('SimpleForm Admin Notice', 'simpleform') ) !== false ? $form : $start_wrap . $form_title . $above_form . $form . $below_form . $end_wrap;
 	    $return_html = $form != '' ? $contact_form : ''; 
      }
        
	  return $return_html;    
    
    }

	/**
	 * Extract the SimpleForm block from found blocks (To be used when editing a page)
     *
	 * @since    2.0
	 */
	 
    public function get_simpleform_block($block) {
      
      if ($block['blockName'] === 'simpleform/form-selector') {
        return $block;
      }
      
      if ($block['innerBlocks']) { 
        foreach ($block['innerBlocks'] as $innerblock) {
          if ($innerblock['blockName'] === 'simpleform/form-selector') {
	          return $innerblock;
          }
          if ($innerblock['innerBlocks']) {
            foreach ($innerblock['innerBlocks'] as $innerblock2) {
              if ($innerblock2['blockName'] === 'simpleform/form-selector') {
	              return $innerblock2;
              }
              if ($innerblock2['innerBlocks']) {
                foreach ($innerblock2['innerBlocks'] as $innerblock3) {
                  if ($innerblock3['blockName'] === 'simpleform/form-selector') {
	                  return $innerblock3;
                  }
                  if ($innerblock3['innerBlocks']) {
                    foreach ($innerblock3['innerBlocks'] as $innerblock4) {
                      if ($innerblock4['blockName'] === 'simpleform/form-selector') {
	                      return $innerblock4;
                      }
                      if ($innerblock4['innerBlocks']) {
                        foreach ($innerblock4['innerBlocks'] as $innerblock5) {
                          if ($innerblock5['blockName'] === 'simpleform/form-selector') {
	                          return $innerblock5;
                          }
                          if ($innerblock5['innerBlocks']) {
                            foreach ($innerblock5['innerBlocks'] as $innerblock6) {
                              if ($innerblock6['blockName'] === 'simpleform/form-selector') {
	                            return $innerblock6;
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
      
    }

	/**
	 * Hide widget blocks if the form already appears in the post content.
	 *
	 * @since    2.0.4
     * @version  2.1.3
	 */

    public function hide_widgets( $sidebars_widgets ) {

       if ( is_admin() )
       return $sidebars_widgets;

       $post_id = get_the_ID();
       $post_content = get_the_content();
       $util = new SimpleForm_Util();
       $used_forms = $post_id && ! empty($post_content) ? $util->used_forms($post_content,$type = 'all') : array();

	   if ( empty($used_forms) )
	   return $sidebars_widgets;

  	   $widget_block = get_option("widget_block") != false ? get_option("widget_block") : array();
       $sform_widget = get_option('widget_sform_widget') != false ? get_option("widget_sform_widget") : array();
  	   foreach ( $sidebars_widgets as $sidebar => $widgets ) {
	     if ( is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {
           foreach ( $widgets as $key => $value ) {
	         if ( strpos($value, 'block-' ) !== false ) {
		        $block_id = substr($value, 6);
		        foreach ($widget_block as $block_key => $block_value ) {
			      if ( $block_key == $block_id )  {
                    $string = implode('',$block_value);
  				    if ( strpos($string, 'wp:simpleform/form-selector' ) !== false ) {
                 	  $split_id = ! empty($string) ? explode('formId":"', $string) : '';
	                  $form_id = isset($split_id[1]) ? explode('"', $split_id[1])[0] : '';
                      if (in_array($form_id, $used_forms) ) {
		                unset( $sidebars_widgets[$sidebar][$key] );
	                  }
                    }
                    if ( strpos($string,'[simpleform') !== false ) {
	                  $split_shortcode = ! empty($string) ? explode('[simpleform', $string) : '';
	                  $split_id = isset($split_shortcode[1]) ? explode(']', $split_shortcode[1])[0] : '';
	                  $form_id = empty($split_id) ? '1' : filter_var($split_id, FILTER_SANITIZE_NUMBER_INT);
	                  if (in_array($form_id, $used_forms) ) {
		                unset( $sidebars_widgets[$sidebar][$key] );
	                  }
 		            }
                  }
		        }
		     }
		 	 if ( strpos($value, 'sform_widget-' ) !== false ) {
                $id =  explode("sform_widget-", $value)[1];
		        global $wpdb;
		        if ( isset( $sform_widget[$id]['shortcode_id'] ) ) { $form_id = $sform_widget[$id]['shortcode_id']; }
			    else { $form_id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}sform_shortcodes WHERE widget = %d", $id ) ); }
	            if (in_array($form_id, $used_forms) ) {
		           unset( $sidebars_widgets[$sidebar][$key] );
	            }
             }
           }
         }
       }

       return $sidebars_widgets;

    }

	/**
	 * Extract the SimpleForm block ID from found blocks (To be used when editing a page)
     *
	 * @since    2.0
	 */
	 
    public function get_sform_block_ids($content) {
	    
	  $ids = array();
	  $blocks = parse_blocks( $content );
	  
	  if ($blocks){
	  foreach ( $blocks as $block ) {
      
      if ($block['blockName'] === 'simpleform/form-selector' && isset($block['attrs']['formId']) && $block['attrs']['formId'] != '' ) {
	    $id = $block['attrs']['formId'];  
	    $ids[] = $block['attrs']['formId'];
      }
      if ($block['innerBlocks']) { 
        foreach ($block['innerBlocks'] as $innerblock) {
          if ($innerblock['blockName'] === 'simpleform/form-selector' && $innerblock['attrs']['formId'] != '' ) {
	          $ids[] = $innerblock['attrs']['formId'];
          }
          if ($innerblock['innerBlocks']) {
            foreach ($innerblock['innerBlocks'] as $innerblock2) {
              if ($innerblock2['blockName'] === 'simpleform/form-selector' && $innerblock2['attrs']['formId'] != '' ) {
	              $ids[] = $innerblock2['attrs']['formId'];
              }
              if ($innerblock2['innerBlocks']) {
                foreach ($innerblock2['innerBlocks'] as $innerblock3) {
                  if ($innerblock3['blockName'] === 'simpleform/form-selector' && $innerblock3['attrs']['formId'] != '' ) {
	                  $ids[] = $innerblock3['attrs']['formId'];
                  }
                  if ($innerblock3['innerBlocks']) {
                    foreach ($innerblock3['innerBlocks'] as $innerblock4) {
                      if ($innerblock4['blockName'] === 'simpleform/form-selector' && $innerblock4['attrs']['formId'] != '' ) {
	                      $ids[] = $innerblock4['attrs']['formId'];
                      }
                      if ($innerblock4['innerBlocks']) {
                        foreach ($innerblock4['innerBlocks'] as $innerblock5) {
                          if ($innerblock5['blockName'] === 'simpleform/form-selector' && $innerblock5['attrs']['formId'] != '' ) {
	                          $ids[] = $innerblock5['attrs']['formId'];
                          }
                          if ($innerblock5['innerBlocks']) {
                            foreach ($innerblock5['innerBlocks'] as $innerblock6) {
                              if ($innerblock6['blockName'] === 'simpleform/form-selector' && $innerblock6['attrs']['formId'] != '' ) {
	                            $ids[] = $innerblock6['attrs']['formId'];
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
      
      }
      }
      
      return $ids;
      
    }
    
    /**
     * Add the theme support to load the form's stylesheet in the editor
     *
	 * @since    2.1.8.1
     */

    public function editor_styles_support() {
	    
       add_theme_support( 'editor-styles' );
       
    }

    /**
     * Add the form's stylesheet to use in the editor
     *
	 * @since    2.1.8.1
     */

    public function add_editor_styles() {
	    
      $settings = get_option('sform_settings');
      $stylesheet = ! empty( $settings['stylesheet'] ) ? esc_attr($settings['stylesheet']) : 'false';
      $cssfile = ! empty( $settings['stylesheet_file'] ) ? esc_attr($settings['stylesheet_file']) : 'false';
   	  $additionalStyle = get_option('sform_additional_style') != false ? get_option('sform_additional_style') : '';
 	  $blockStyle = get_option('sform_block_style') != false ? get_option('sform_block_style') : '';

      if ( $stylesheet == 'false' ) {	      
        add_editor_style( plugins_url( 'simpleform/public/css/public-min.css' ) );
      }
      
      else {
	    add_editor_style( plugins_url( 'simpleform/public/css/simpleform-style.css' ) );  
        if ( $cssfile == 'true' && file_exists( get_theme_file_path( '/simpleform/custom-style.css' ) ) ) {
          add_editor_style( get_theme_file_uri( 'simpleform/custom-style.css') );
        }
      }
    
    }
    	
}