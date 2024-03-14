<?php
// If this file is called directly, abandon ship.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if (!class_exists('LH_Register_file_class')) {
    


class LH_Register_file_class {
    
 var $script_atts;
 var $name;
 var $our_handle;
 var $has_run;
 
 
static function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
 
 static function get_script_src_by_handle($handle) {
    global $wp_scripts;
    if(isset($wp_scripts->registered[$handle]->src)) {
        
        return add_query_arg('ver', $wp_scripts->registered[$handle]->ver, $wp_scripts->registered[$handle]->src);
    } else {
        
        return false;
        
    }
}


 static function get_style_src_by_handle($handle) {
    global $wp_styles;
    
    if(isset($wp_styles->registered[$handle]->src)) {
        
        return add_query_arg('ver', $wp_styles->registered[$handle]->ver, $wp_styles->registered[$handle]->src);
    } else {
        
        return false;
        
    }
}


 


public function add_attributes($tag, $handle, $src){
     
if (!empty($this->our_handle) && ($handle == $this->our_handle) && !empty($tag)){
    
if (!empty($this->script_atts)){
    

   



if (empty($this->has_run)){
    
     
     
     
     

    
    $dom = new DOMDocument;

    //$tag = mb_convert_encoding( $tag, 'HTML-ENTITIES', 'UTF-8' );
      $dom->loadHTML( $tag, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
    
    $node = $dom->getElementsByTagName( 'script' )[0];
    
    if (empty($node)){
        
        $node = $dom->getElementsByTagName( 'link' )[0];
        
    }
    
    foreach ($this->script_atts as $key => $value){


        try {
            
            $node->setAttribute( $key, $value );
    
        } catch (DOMException $exception) {
            // Output expected DOMException.
            self::write_log('lh- the key was '.$key.' and the value was '.$value.' and the handle was '.$handle);
            self::write_log($exception);
            return null;
        } catch (Exception $exception) {
            // Output unexpected Exceptions.
            self::write_log('lh- the key was '.$key.' and the value was '.$value.' and the handle was '.$handle);
            self::write_log($exception);
            return null;
        }
    
        
    }

$this->script_atts = false;
$this->our_handle = false;
$this->has_run = true;
$tag = $dom->saveHTML();

$tag = str_replace( ' defer', ' defer="defer"', $tag );
$tag = str_replace( ' async', ' async="async"', $tag );

}



    
}   
    
}

return $tag;
     
     
 }
 
 
 public function maybe_prefetch(){
     

if (!wp_script_is( 'lh_web_application-script', 'done' )){ 
    
    
if (isset($this->is_script) && !wp_script_is( $this->name, 'done' ) && ($script = self::get_script_src_by_handle($this->name))){
    

    
echo '<link rel="prefetch" href="'.esc_url(apply_filters('script_loader_src', $script,$this->name)).'" />
';  
    
    
} elseif (empty($this->is_script) && !wp_style_is( $this->name, 'done' ) && ($style = self::get_style_src_by_handle($this->name))){
    
echo '<link rel="prefetch" href="'.esc_url(apply_filters('style_loader_src', $style,$this->name)).'" />
';  


}
    
}   
    
}
    
    
    public function __construct( $name, $file_path, $url, $is_script = false, $deps = array(), $in_footer = true, $atts = array(), $media = 'all', $prefetch = true) {
        
$this->has_run = false; 
$this->name = $name; 
$this->is_script = $is_script;

        if ( isset($file_path) && file_exists( $file_path ) ) {
            if ( $is_script ) {
                wp_register_script( $name, $url, $deps, filemtime($file_path), $in_footer ); 

            } else {
                wp_register_style( $name, $url, $deps, filemtime($file_path), $media );

            } // end if

if (!empty($prefetch)) {          
            
//asynchronously prime the cache
add_action( 'wp_footer', array($this,'maybe_prefetch'), PHP_INT_MAX );
add_action( 'embed_footer', array($this,'maybe_prefetch'), PHP_INT_MAX );

}
            
         $this->our_handle = $name;    
            
        } // end if
        
add_filter( 'lh_web_application_precache_static_urls_filter', function($precache_static_urls) {
    // do something
   

    
if (empty($this->is_script)){
    

    
if ($style = self::get_style_src_by_handle($this->name)){
    
$add = apply_filters('style_loader_src', $style ,$this->name);

if(!in_array($add, $precache_static_urls)){
    
$precache_static_urls[] = $add;

}
    
}

} elseif (isset($this->is_script)){
    
if ($script = self::get_script_src_by_handle($this->name)){

$add = apply_filters('script_loader_src', $script ,$this->name);    

if(!in_array($add, $precache_static_urls)){
    
$precache_static_urls[] = $add;

}
    
}

}
    
    
    return $precache_static_urls;
} );
	  
if (isset($atts) and is_array($atts) and isset($is_script) && empty($this->has_run)){
    
    $this->script_atts = $atts;
	      
	      
	    add_filter( 'script_loader_tag', array($this, 'add_attributes'),100,3);
	    add_filter( 'style_loader_tag', array($this, 'add_attributes'),100,3);	
		
}
		
    } // end load_file
    
    
}


}


?>