<?php

/*---------------------------------
    REMOVE CF7 UNWANTED P TAG.
----------------------------------*/
add_filter('wpcf7_autop_or_not', '__return_false');

/*---------------------------------
    Asset Optimize
----------------------------------*/
add_action( 'wp_enqueue_scripts', 'element_ready_lite_optimize_widgets_scripts', 301);
add_action( 'wp_enqueue_scripts', 'element_ready_lite_optimize_gl_style', 301);
add_action( 'wp_enqueue_scripts', 'element_ready_lite_optimize_scripts', 300);
add_action( 'elementor/frontend/after_enqueue_styles', 'element_ready_lite_after_enqueue_styles', 100);

/************ ****************
 * Widget js optimize
 *****************/
function element_ready_lite_optimize_widgets_scripts(){

    include( dirname(__FILE__) . '/dashboard/controls/Components.php' );
    $global_widget_min = ELEMENT_READY_DIR_PATH.'assets/js/active.min.js';
    $global_widget_old = ELEMENT_READY_DIR_PATH.'assets/js/active.old.js';
    $js_results        = [];

    foreach( $return_arr as $key => $item ){
        if( element_ready_get_components_option($key) ){
            if(isset($item['js'])){
                $js_assets = $item['js'];
                foreach($js_assets as $file){
                    $js_results[] = $file;
                }
            }
        }
    }

    $js_results = array_unique($js_results);
    /*********** File Update ************/ 
  element_ready_lite_optimize_widgets_update_scripts($js_results,$global_widget_min);
  
}

function element_ready_lite_optimize_widgets_update_scripts($js_results , $global_widget_min){

    global $wp_filesystem; 
    if (empty($wp_filesystem)) {
        require_once (ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();
    }
  
    $js_content = '';
    foreach( $js_results as $file_path ){
       $js_content .=  $wp_filesystem->get_contents($file_path);
    }
    $js_content = str_replace([';;'],[';'],$js_content);
    if(!$wp_filesystem->put_contents( $global_widget_min , $js_content , 0644) ) {
        return __('Failed to create js file','element-ready-lite');
    }
}

/************ ****************
 * Widget Style optimize
 *****************/
function element_ready_lite_optimize_gl_style(){
 
    $optmize_css = [
      'section_perticle_did' => 'element-ready-particle',
    ];
    $optmize_css_file = [
     'section_perticle_did' => ELEMENT_READY_DIR_PATH.'assets/js/classic-particles.min.js',
    ];

}

function element_ready_lite_optimize_scripts(){

    global $wp_filesystem; 
    $global_widget_min = ELEMENT_READY_DIR_PATH.'assets/js/globalwidget.min.js';
  
    if (empty($wp_filesystem)) {
        require_once (ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();
    }
      
    $optmize_js = [

        'section_perticles'       => 'element-ready-particle',
        'sticky_section'          => 'element-ready-sticky-section',
        'color_section'           => 'element-ready-animated-color-section',
        'section_dismiss'         => 'element-ready-dismissable-section',
        'widget_tooltip'          => 'element-ready-tool-tip',
        'pro_conditional_content' => 'element-ready-er-gl-conditional',
        'cookie'                  => 'element-ready-er-cookie',
        'column_wrapper_link'     => 'element-ready-column-wrapper'
       
    ];

    $optmize_js_file = [

        'section_perticles'       => ELEMENT_READY_DIR_PATH.'assets/js/classic-particles.min.js',
        'sticky_section'          => ELEMENT_READY_DIR_PATH.'assets/js/sticky_section.min.js',
        'color_section'           => ELEMENT_READY_DIR_PATH.'assets/js/animated-color-section.min.js',
        'section_dismiss'         => ELEMENT_READY_DIR_PATH.'assets/js/dismissable-section.min.js',
        'widget_tooltip'          => ELEMENT_READY_DIR_PATH.'assets/js/tooltip-gl.min.js',
        'pro_conditional_content' => ELEMENT_READY_DIR_PATH.'assets/js/er-gl-conditional.min.js',
        'cookie'                  => ELEMENT_READY_DIR_PATH.'assets/js/er-cookie.min.js',
        'column_wrapper_link'     => ELEMENT_READY_DIR_PATH.'assets/js/er-gl-col-wrapper.min.js',
       
    ];
 
    global $enqueued_scripts;

    $flip_js = array_flip($optmize_js);
    
    $js_results = [];
   
    global $wp_scripts;
   
    foreach( $wp_scripts->registered as $key => $script ) :
        if( in_array( $key , $optmize_js ) ){
            if(isset($flip_js[$key])){
                $current_key = $flip_js[$key];
                if(element_ready_get_modules_option($current_key)){
                    $js_results[$flip_js[$key]] = $optmize_js_file[$current_key];
                    wp_deregister_script( $key );
                }
                
            }
           
        }
      
    endforeach;
    /********** File Update **********/
  
    $js_results = apply_filters('er_element_ready/global/script',$js_results);
    element_ready_lite_optimize_global_scripts($js_results,$global_widget_min);

}

function element_ready_lite_optimize_global_scripts($js_results , $global_widget_min){

    global $wp_filesystem; 
  
    if (empty($wp_filesystem)) {
        require_once (ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();
    }
 

    $js_content = '';

    foreach( $js_results as $file_path ){
       $js_content .=  $wp_filesystem->get_contents($file_path);
    }

    $js_content = str_replace([';;'],[';'],$js_content);
               
    if(!$wp_filesystem->put_contents( $global_widget_min , $js_content , 0644) ) {
        return __('Failed to create js file','element-ready-lite');
    }
}

function element_ready_lite_after_enqueue_styles(){
    global $wp_styles;
    
}
function element_ready_lite_remove_wp_block_library_css(){
   
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-block-style' ); //wc

}

use Element_Ready\Base\Elementor_Helper;

add_action('element_ready_footer_before',function(){
  
    if(is_page()){

        $fix_footer_div = Elementor_Helper::page_settings('er_footer_div_missing');
        if($fix_footer_div == 'yes'){
            echo '</div>';
        }

    }elseif(element_ready_lite_is_global_blog()){
        
        $fix_footer_div = Elementor_Helper::get_global_setting('er_blog_footer_missing_div');
        if($fix_footer_div == 'yes'){
            echo '</div>';
        }

    }
 
});

function er_body_line_animation_enable(){
    $animation_enable = Elementor_Helper::get_global_setting('er_body_line_animation_enable');
    $conditional_display = Elementor_Helper::get_global_setting('er_body_line_animation_conditional_display');
    $pages_ids = Elementor_Helper::get_global_setting('er_body_line_animation_page_option');
    
    if($animation_enable !='yes'){
        return false;
    } 

    if($conditional_display === 'global'){

        return true;

    }elseif($conditional_display  === 'page_specific'){
       $array_ids = explode(',' ,$pages_ids );

       if(in_array(get_queried_object_id(),$array_ids)){
         return true;
       }else{
         return false;
       }
    }

    return true;

}

add_action( 'wp_body_open' , function(){
 
    if(!er_body_line_animation_enable()){
        return;
    }
    ?>
   
        <div class="er-full-page-lines">
        <div class="er-full-page-line"></div>
        <div class="er-full-page-line"></div>
        <div class="er-full-page-line"></div>
       </div>
    <?php
});

//
add_action('wp_enqueue_scripts', function(){

    if(!er_body_line_animation_enable()){
        return;
    }

    $direction_type = Elementor_Helper::get_global_setting('er_body_line_animation_direction');
    $custom_css = 
    ' .er-full-page-lines {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 100%;
      margin: auto;
      width: 90vw;
      z-index: 9999;
    }
    
    .er-full-page-line {
      position: absolute;
      width: 1px;
      height: 100%;
      top: 0;
      left: 50%;
      background: rgba(255, 255, 255, 0.1);
      overflow: hidden;
    }
    .er-full-page-line::after {
      content: "";
      display: block;
      position: absolute;
      height: 15vh;
      width: 100%;
      top: -50%;
      left: 0;
      background: linear-gradient(to bottom, rgba(255, 255, 255, 0) 0%, #ffffff 75%, #ffffff 100%);
      -webkit-animation: er-full-page-drop 7s 0s infinite;
              animation: er-full-page-drop 7s 0s infinite;
      -webkit-animation-fill-mode: forwards;
              animation-fill-mode: forwards;
      -webkit-animation-timing-function: cubic-bezier(0.4, 0.26, 0, 0.97);
              animation-timing-function: cubic-bezier(0.4, 0.26, 0, 0.97);
    }
    .er-full-page-line:nth-child(1) {
      margin-left: -25%;
    }
    .er-full-page-line:nth-child(1)::after {
      -webkit-animation-delay: 2s;
              animation-delay: 2s;
    }
    .er-full-page-line:nth-child(3) {
      margin-left: 25%;
    }
    .er-full-page-line:nth-child(3)::after {
      -webkit-animation-delay: 2.5s;
              animation-delay: 2.5s;
    }
    
   ';
   
   if($direction_type == 'down'){
    $custom_css .= ' @-webkit-keyframes er-full-page-drop {
        0% {
          top: -50%;
        }
        100% {
          top: 110%;
        }
      }
      
      @keyframes er-full-page-drop {
        0% {
          top: -50%;
        }
        100% {
          top: 110%;
        }
      }';
   }elseif($direction_type == 'up'){
    $custom_css .= ' @-webkit-keyframes er-full-page-drop {
        0% {
          top: 110%;
        }
        100% {
          top: -50%;
        }
      }
      
      @keyframes er-full-page-drop {
        0% {
          top: 110%;
        }
        100% {
          top: -50%;
        }
      }';
   }
  wp_add_inline_style( 'element-ready-widgets', $custom_css );
});

add_filter( 'template_include', 'er__elementor_editor_section___page_template', 99 );
function er__elementor_editor_section___page_template( $template ) {
          
           if(
              isset( $_GET['post_type'] ) && 
              $_GET['post_type'] =='elementor_library' && 
              isset( $_GET['p'] )
              ){
                
                $template_type = get_post_meta(sanitize_text_field($_GET['p']), '_elementor_template_type', true);
                if($template_type == 'section' || $template_type == 'container' || $template_type == 'page'){
                  $new_template = element_ready_fix_path(dirname( __FILE__ ) . '/er-full-width.php');
                 
                  if ( '' != $new_template && file_exists($new_template) ) {
                   
                      return $new_template;
                  }
                }
  
            }

           if(
              isset( $_GET['elementor-preview'] ) &&
              isset( $_GET['elementor_library'] )
              ){
                
                $template_type = get_post_meta(sanitize_text_field($_GET['elementor-preview']), '_elementor_template_type', true);
                if($template_type == 'section' || $template_type == 'container' || $template_type == 'page'){
                  $new_template = element_ready_fix_path(dirname( __FILE__ ) . '/er-full-width.php');
                 
                  if ( '' != $new_template && file_exists($new_template) ) {
                      return $new_template;
                  }
                }
  
            }

            if(
                isset( $_GET['elementor_library'] ) && (isset($_GET['preview_id']) && $_GET['preview_nonce'])
            ){
                  
                  $template_type = get_post_meta(sanitize_text_field($_GET['preview_id']), '_elementor_template_type', true);
                  if($template_type == 'section' || $template_type == 'container' || $template_type == 'page'){
                    $new_template = element_ready_fix_path(dirname( __FILE__ ) . '/er-full-width.php');
                   
                    if ( '' != $new_template && file_exists($new_template) ) {
                        return $new_template;
                    }
                  }
    
            }
     
      return $template;
  }


  function elementsready_2022_rec_insert_fb_in_head() {

    global $post;
  
    if ( !is_singular('post') ){
      return;
    } 
   
    if(!has_post_thumbnail( $post->ID )) { //the post does not have featured image, use a default image
    
    }
    else{
      $allowed_html = array(

          'meta' => array(
            'property' => [],
            'content' => [],
            'name' => [],
          ),
          'link' => array(
            'rel' => [],
            'href' => [],
            'name' => [],
          )
      );

      $desc = wp_trim_words( esc_html( get_the_excerpt($post->ID) ) ,18,'' );
      $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
      echo wp_kses( sprintf('<meta property="og:image" content="%s"/>', esc_attr( $thumbnail_src[0] ) ), $allowed_html );
      echo wp_kses( sprintf( '<meta name="description" content="%s">' , esc_html($desc)), $allowed_html);
      echo wp_kses( sprintf( '<link rel="apple-touch-icon" href="%s">', esc_url( $thumbnail_src[0] )), $allowed_html );
    }
  
  }
  add_action( 'wp_head', 'elementsready_2022_rec_insert_fb_in_head', 5 );
