<?php
/*
Plugin Name: Specify Missing Image Dimensions
Description: A plugin that helps to add missing width and height attributes to images.
Author: WPHowKnow
Author URI: https://wphowknow.com/
Version: 1.0.2
Text Domain: specify-missing-image-dimensions
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

include( plugin_dir_path( __FILE__ ) . 'admin/admin-settings.php');

add_action('init', 'SMID_start_gathering_data' );

function SMID_start_gathering_data() {
    
	if( !is_admin() ) {
		ob_start( 'SMID_end_gathering_data');
	}
	
}

function SMID_end_gathering_data( $content ) {
	
	$set_images_regex = '<img(?:[^>](?!(height|width)=[\'\"](?:\S+)[\'\"]))*+>';
	
	preg_match_all( "/{$set_images_regex}/is", $content, $images_match );
	
	$images_to_replace_array = [];
	
	$images = $images_match[0];
	
	foreach ( $images as $image ) {

        $image_url = SMID_get_image_url($image); // Get both type of URLs Full or Relative AND also handle layload attribute
	    
	    if( empty($image_url) ) {
	        continue;
	    }	    
	    
	    if( SMID_is_image_excluded_by_class($image) == true ) {
	        continue;
	    }
	    
	    if( SMID_is_image_excluded_by_id($image) == true ) {
	        continue;
	    }
	    
	    $image_url = SMID_relative_to_full_URL($image_url); // Convert Relative URLs to Full
	
	    if( $image_url == false ) {
	        continue;
	    }
	    
	    $image_extension = strtolower(pathinfo($image_url, PATHINFO_EXTENSION)); 
	    
	    if( SMID_is_image_excluded_by_extension($image_extension) == true ) {
	        continue;
	    }
	    
	    if( SMID_is_image_excluded_by_name($image_url) == true ) {
	        continue;
	    }
	    
		if( strtolower($image_extension) == 'svg' ) {
			$svgfile = simplexml_load_file($image_url);
	    	if( !empty($svgfile) ) {
		    	$xmlattributes = $svgfile->attributes();
		    	$sizes[3] = 'width="'.$xmlattributes->width.'" height="'.$xmlattributes->height.'"' ;
	    	}
		}
		else {
			$sizes = getimagesize( $image_url ); // Get image height and width
		}
	    
	    if( empty($sizes[3]) ) {
	        continue;
	    }
	
	    $images_to_replace_array[ $image ] = SMID_specify_image_width_height( $image, $sizes[3] );
	
	}
	
	return str_replace( array_keys( $images_to_replace_array ), $images_to_replace_array, $content );
	
}


function SMID_get_image_url( $image ) {
	
	$lazyload_attribute = strtolower(get_option('SMID_data_Src_Value')); // Get lazyload attribute
	
	if( empty($lazyload_attribute) ) {
	 
	    preg_match( '/\s+data-src\s*=\s*[\'"](?<url>[^\'"]+)/i', $image, $src_match );

        if( !empty($src_match['url']) ) {
            return $src_match['url'];        
        }
    
    	preg_match( '/\s+src\s*=\s*[\'"](?<url>[^\'"]+)/i', $image, $src_match );
    	
    	if( !empty($src_match['url']) ) {
            return $src_match['url'];        
        }
    	    
	}
	
	else {
	    
	    preg_match( '/\s+'.$lazyload_attribute.'\s*=\s*[\'"](?<url>[^\'"]+)/i', $image, $src_match );

        if( !empty($src_match['url']) ) {
            return $src_match['url'];        
        }
    
    	preg_match( '/\s+src\s*=\s*[\'"](?<url>[^\'"]+)/i', $image, $src_match );
    	
    	if( !empty($src_match['url']) ) {
            return $src_match['url'];        
        }
	        
	}

}


function SMID_is_image_excluded_by_class($image) {
	
	$is_excluded = false;
		 
	preg_match( '/\sclass=[\'\"](.*?)[\'\"]/i', $image, $class_name ); // Get Classes of image
	
	$excluded_image_classes = get_option('SMID_excluded_Image_Classes'); // Get excluded classes for image
	
	if( empty($class_name) || empty($excluded_image_classes) ) {
	    return $is_excluded;
	}
	
    $image_class_name = $class_name[1];
    
    $image_class_name_array = explode(" ",$image_class_name);
    
    $excluded_image_classes = str_replace(" ","", $excluded_image_classes ); // Remove Spaces
    
    $excluded_image_classes_array = explode(",",$excluded_image_classes); // Explode Classes into Array
    
    $excluded_image_classes_array = str_replace(".","", $excluded_image_classes_array ); // Remove .
    
    foreach ( $excluded_image_classes_array as $excluded_image_class ) {
        
        if( in_array($excluded_image_class, $image_class_name_array)  ) {
            $is_excluded = true;
            break;
        }
        
    }
    
    return $is_excluded;

}

function SMID_is_image_excluded_by_id($image) {
	
	$is_excluded = false;
		 
	preg_match( '/\sid=[\'\"](.*?)[\'\"]/i', $image, $id_name ); // Get ID of image
	
	$excluded_image_id = get_option('SMID_excluded_Image_ID'); // Get excluded ID's for image
	
	if( empty($id_name) || empty($excluded_image_id) ) {
	    return $is_excluded;
	}
	    
    $image_class_name = $id_name[1];
    
    $image_id_name_array = explode(" ",$image_class_name);
    
    $excluded_image_id = str_replace(" ","", $excluded_image_id ); // Remove Spaces
    
    $excluded_image_id_array = explode(",",$excluded_image_id); // Explode ID's into Array
    
    $excluded_image_id_array = str_replace("#","", $excluded_image_id_array ); // Remove #
    
    foreach ( $excluded_image_id_array as $excluded_image_id ) {
        
        if( in_array($excluded_image_id, $image_id_name_array)  ) {
            $is_excluded = true;
            break;
        }
        
    }
    
    return $is_excluded;

}

function SMID_relative_to_full_URL($image_url) {
    
    $url_host = wp_parse_url( $image_url, PHP_URL_HOST );
    $site_host = wp_parse_url( site_url(), PHP_URL_HOST );
    
	if ( empty( $url_host ) ) {
		$relative_url        = ltrim( wp_make_link_relative( $image_url ), '/' );
		$site_url_components = wp_parse_url( site_url( '/' ) );
		$image_url = $site_url_components['scheme'] . '://' . $site_url_components['host'] . '/' . $relative_url;
		$url_host = wp_parse_url( $image_url, PHP_URL_HOST );
	}
    
    if( !empty($image_url) ) {
        return $image_url;
   	}
   	else {
       return false;
   	}
}

function SMID_is_image_excluded_by_extension($image_extension) {
    
    $is_excluded = false;
	
	$excluded_image_extensions = strtolower(get_option('SMID_excluded_Image_Extension')); // Get excluded image extensions
	
	if( empty($excluded_image_extensions) ) {
	    return $is_excluded;
	}
	
	$excluded_image_extensions = str_replace(" ","", $excluded_image_extensions ); // Remove Spaces
    
    $excluded_image_extension_array = explode(",",$excluded_image_extensions); // Explode ID's into Array
    
    $excluded_image_extension_array = str_replace(".","", $excluded_image_extension_array ); // Remove .
    
    foreach ( $excluded_image_extension_array as $excluded_image_extension ) {
        
        if( $excluded_image_extension == $image_extension  ) {
            $is_excluded = true;
            break;
        }
        
    }
    
    return $is_excluded;
	
}


function SMID_is_image_excluded_by_name($image_url) {
    
    $is_excluded = false;
	
	$excluded_image_names = strtolower(get_option('SMID_excluded_Image_Name')); // Get excluded image extensions
	
	$excluded_image_name = strtolower(pathinfo($image_url)['filename']);
	
	if( empty($excluded_image_names) || empty($excluded_image_name) ) {
	    return $is_excluded;
	}
	
	$excluded_image_names = str_replace(" ","", $excluded_image_names ); // Remove Spaces
    
    $excluded_image_name_arr = explode(",",$excluded_image_names); // Explode Names into Array
    
    foreach ( $excluded_image_name_arr as $excluded_name ) {
        
        if( in_array($excluded_image_name, $excluded_image_name_arr)  ) {
            $is_excluded = true;
            break;
        }
        
    }
    
    return $is_excluded;
	
}

function SMID_specify_image_width_height( $image, $image_size ) {
	// Remove old width and height attributes if found.
	$modified_image = preg_replace( '/(height|width)=[\'"](?:\S+)*[\'"]/i', '', $image );
	$modified_image = preg_replace( '/<\s*img/i', '<img ' . $image_size, $modified_image );

	if ( $modified_image === null ) {
		return $image;
	}
	
	return $modified_image;
}