<?php
// if called directly, abort.
if ( !defined('WPINC') ) { die; }

if ( ! function_exists( 'rsgd_summary' ) ){
    function rsgd_summary($max_words){
        global $post;
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        $content = get_the_content();
        $content = strip_shortcodes( $content );
        $content = preg_replace($reg_exUrl, '', $content);
                
        if($max_words != '-1'){
            
            $content = wp_trim_words( $content , $max_words , '' );    
        
        } else if(has_excerpt( $post->ID )){
            
            $content = get_the_excerpt();
                
        } else if( strpos( $post->post_content, '<!--more-->' ) ) {
            
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
        
        }
        
        return $content;
    } 
}

if ( ! function_exists( 'rsgd_post_types' ) ) {
    function rsgd_post_types(){
        $types = [];
        $exclude_cpts = [
            'attachment',
            'revision',
            'nav_menu_item',
            'custom_css',
            'customize_changeset',
            'vc4_templates',
            'page',
            'wpcf7_contact_form',
            'vc_grid_item',
            'mc4wp-form'
        ];
        $builtin = [
            'post',
        ];
        $cpts = get_post_types( [
            '_builtin' => false
        ]);
        foreach($exclude_cpts as $exclude_cpt)
            unset($cpts[$exclude_cpt]);
        $post_types = array_merge($builtin, $cpts);
        
        foreach( $post_types as $type ) {
            $obj = get_post_type_object( $type );
            $types[$type] = $obj->labels->singular_name;
        }
        
        return $types;
        
    }
}

if( ! function_exists( 'rsgd_post_media' ) ) {
  function rsgd_post_media( $content ) {
    $media    = rsgd_getUrl( $content );
    if( ! empty( $media ) ) {
      global $wp_embed;
      $content  = do_shortcode( $wp_embed->run_shortcode( '[embed]'. $media .'[/embed]' ) );
    } else {
      $pattern = get_shortcode_regex( rsgd_wp_tagregexp() );
      preg_match( '/'.$pattern.'/s', $content, $media );
      if ( ! empty( $media[2] ) ) {
        if( $media[2] == 'embed' ) {
          global $wp_embed;
          $content = do_shortcode( $wp_embed->run_shortcode( $media[0] ) );
        } else {
          $content = do_shortcode( $media[0] );
        }
      }
    }
    if( ! empty( $media ) ) {
      if(get_post_format() == 'gallery'){
          $output  = '<div class="post-gallery">';
      }else{
          $output  = '<div class="post-media">';
      }
        
      $output .= $content;
      $output .= '</div>';
      return $output;
    }
    return false;
  }
}

if( ! function_exists( 'rsgd_wp_tagregexp' ) ) {
  function rsgd_wp_tagregexp() {
    apply_filters( 'wp_custom_tagregexp', 'video|media|audio|playlist|video-playlist|embed' );
  }
}

if( ! function_exists( 'rsgd_getUrl' ) ) {
  function rsgd_getUrl( $html ) {
    $regex  = "/^\b(?:(?:https?|ftp):\/\/)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
    preg_match( $regex, $html, $matches );
    return ( !empty( $matches[0] ) ) ? $matches[0] : false;
  }
}

if ( ! function_exists( 'rsgd_hex2RGB' ) ) {
    function rsgd_hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
        $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
        $rgbArray = array();
        if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
            $colorVal = hexdec($hexStr);
            $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
            $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
            $rgbArray['blue'] = 0xFF & $colorVal;
        } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
            $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
            $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
            $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
        } else {
            return false; //Invalid hex color code
        }
        return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
    }
}