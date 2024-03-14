<?php
/*
Plugin Name: EasyFonts
Plugin URI: 
Description: Automatically Host existing google fonts locally on your server
Version: 1.1.2
Author: Uzair
Author URI: https://easywpstuff.com
License: GPL2
*/

// If this file is called directly, abort.
if (!defined("WPINC")){
	die;
}

require_once dirname(__FILE__) . '/lib/simple_html_dom.php';

include_once dirname(__FILE__) . '/inc/options.php';

include_once dirname(__FILE__) . '/inc/notices.php';

function get_base_url() {
return is_ssl() ? set_url_scheme(wp_upload_dir()['baseurl'], 'https') : wp_upload_dir()['baseurl'];
}

function wp_get_remote_file($url) {
$backtrace = debug_backtrace();
$function_name = isset($backtrace[1]['function']) ? $backtrace[1]['function'] : '';


$response = wp_remote_get($url, array('user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',));


if (is_wp_error($response)) {
add_settings_error($function_name, 'http_error', $response->get_error_message(), 'error');
return false;
}


return wp_remote_retrieve_body($response);
}

function easyfont_process_html_with_dom($content, $callback) {
	// using simple html dom
  $html = hgfl_str_get_html($content, false, true, 'UTF-8', false, PHP_EOL, ' ');
  
  if (empty($html)) {
    return $content;
  }
  
  $callback($html);

  $content = $html->save(); 
  return $content;
}

function easyfonts_process_font_face_declarations($css, $easyfonts_dir) {
    if (preg_match_all('/@font-face\s*\{([^}]+)\}/', $css, $matches)) {
        foreach ($matches[1] as $match) {
            if (preg_match_all('/src\s*:\s*([^;]+);/', $match, $srcs)) {
                foreach ($srcs[1] as $src) {
                    if (preg_match('/url\(([^)]+)\)/', $src, $url_match)) {
                        $font_url = trim($url_match[1], "'\"");
                        $font_filename = hash('sha256', $font_url, false);
                        $font_filename = substr($font_filename, 0, 10) . '.' . pathinfo($font_url, PATHINFO_EXTENSION);
                        if (!file_exists($easyfonts_dir . '/' . $font_filename)) {
                            $font_data = wp_get_remote_file($font_url);
                            // Save the font file to the 'wp-content/uploads/easyfonts' directory
                            file_put_contents($easyfonts_dir . '/' . $font_filename, $font_data);
                        }
                        // Replace the src property with a local reference to the downloaded font file
                        $finalbase = get_base_url();
                        $css = str_replace($src, "url('" . $finalbase . "/easyfonts/" . $font_filename . "')", $css);
                    }
                }
            }
        }
    }
    return $css;
}


function easyfont_process_content_link_tag($content) {
	return easyfont_process_html_with_dom($content, function($html) {
    // Check if the 'easyfonts' directory exists and is writable
    $easyfonts_dir = wp_upload_dir() ['basedir'] . '/easyfonts';
    if (!wp_mkdir_p($easyfonts_dir)) {
        return $content; 
    }
    // Find all <link> tags with a rel attribute of 'stylesheet' and a href attribute that points to a Google Fonts stylesheet
    foreach ($html->find('link[rel=stylesheet][href*=fonts.googleapis.com]') as $link) {
        $url = $link->href;
		if(strpos($url,'//') === 0){
                    $url = 'https:'.$url;
        }
		$decoded_url = rawurldecode(htmlspecialchars_decode($url));
        // Generate a local filename for the stylesheet
        $filename = hash('sha256', $decoded_url, false);
        $filename = substr($filename, 0, 10) . '.css';
        if (!file_exists($easyfonts_dir . '/' . $filename)) {
            $css = wp_get_remote_file($decoded_url);
            if (!preg_match_all('/@font-face\s*\{([^}]+)\}/', $css, $matches)) continue;
			
			$css = easyfonts_process_font_face_declarations($css, $easyfonts_dir);
            // Save the modified stylesheet to the 'wp-content/uploads/easyfonts' directory
            file_put_contents($easyfonts_dir . '/' . $filename, $css);
        }
        $finalbasecss = get_base_url();
        // Replace the href attribute with a local reference to the downloaded stylesheet
        $link->href = $finalbasecss . '/easyfonts/' . $filename;
    }
    // Return the modified content
   });
}

function easyfont_process_content_import($content) {
    // using simple html dom
    return easyfont_process_html_with_dom($content, function($html) {
    // Check if the 'easyfonts' directory exists and is writable
    $easyfonts_dir = wp_upload_dir() ['basedir'] . '/easyfonts';
    if (!wp_mkdir_p($easyfonts_dir)) {
        return $content;     
    }
    // Find all <style> tags with @import statements that import a Google Fonts stylesheet
    foreach ($html->find('style') as $style) {
        if (preg_match_all('/@import\s+(url\()?\s*([^\)]+)\s*(\))?/', $style->innertext, $matches)) {
            foreach ($matches[2] as $match) {
                if (strpos($match, 'fonts.googleapis.com') !== false) {
                    $url = trim($match, "'\"");
                    if (strpos($url, '//') === 0) {
                        $url = 'https:' . $url;
                    }
					$decoded_url = rawurldecode(htmlspecialchars_decode($url));
                    $filename = hash('sha256', $decoded_url, false);
                    $filename = substr($filename, 0, 10) . '.css';
                    // Check if the stylesheet file already exists
                    if (!file_exists($easyfonts_dir . '/' . $filename)) {
                        $css = wp_get_remote_file($decoded_url);
                        $css = easyfonts_process_font_face_declarations($css, $easyfonts_dir);
                        // Save the modified stylesheet to the 'wp-content/uploads/easyfonts' directory
                        file_put_contents($easyfonts_dir . '/' . $filename, $css);
                    }
					$finalbasecss = get_base_url();
                    // Replace the @import statement with a local reference to the downloaded stylesheet
                    $style->innertext = str_replace($match, "" . $finalbasecss . '/easyfonts/' . $filename . "", $style->innertext);
                }
            }
        }
    }
    // Return the modified content
	});
}
function easyfonts_remove_resource_hints($content) {
  // Load the HTML into the Simple HTML DOM library
  return easyfont_process_html_with_dom($content, function($html) {
  
  // Find all <link> elements with the preload, preconnect, or prefetch attributes
  $links = $html->find('link[rel=preload],link[rel=preconnect],link[rel=dns-prefetch]');
  
  // Loop through the <link> elements
  foreach($links as $link) {
    // Check if the <link> element has a href attribute with the fonts.googleapis.com or fonts.gstatic.com domain
    if (preg_match('/(https:\/\/|\/\/)(fonts\.googleapis\.com|fonts\.gstatic\.com)/', $link->href)) {
      // Remove the <link> element
      $link->outertext = '';
    }
  }
  
  // Find all <style> elements
  $styles = $html->find('style');
  
  // Loop through the <style> elements
  foreach($styles as $style) {
    // Remove comments that contain the fonts.googleapis.com or fonts.gstatic.com domain
    if (strpos($style->innertext, 'fonts.googleapis.com') !== false || strpos($style->innertext, 'fonts.gstatic.com') !== false) {
        $style->innertext = preg_replace('#/\*(?:[^*]*(?:\*(?!/))*)*\*/#','',$style->innertext);
    }
  }
  // Return the modified HTML
  });
}


function easyfont_remove_font_scripts($content) {
  // Load the HTML string into a Simple HTML DOM object
   return easyfont_process_html_with_dom($content, function($html) {
  
  // Find all `script` elements
  $scripts = $html->find('script');
  foreach($scripts as $script) {
    // Check if the `script` element contains the `WebFontConfig` or `webfont.js` strings
    if(strpos($script->innertext, 'WebFontConfig') !== false || strpos($script->innertext, 'webfont.js') !== false) {
      // Remove the `script` element
      $script->outertext = '';
    }
  }
  
  // Return the modified HTML as a string
   });
}

function easyfont_download_gstatic_fonts($content) {
    // Create the "easyfonts" directory if it doesn't exist
    $easyfonts_dir = wp_upload_dir()['basedir'] . '/easyfonts';
    if (!wp_mkdir_p($easyfonts_dir)) {
        return $content;
    }
	$content = preg_replace('/(url\s?\(["\']?)\/\/(fonts\.gstatic\.com[^"\']+)/', '$1https://$2', $content);
    $html = hgfl_str_get_html($content, false, true, 'UTF-8', false, PHP_EOL, ' ');
	if (empty($html)) {
		return $content;
	}
    foreach ($html->find('style') as $style) {
        if (preg_match_all('/url\(([^)]+)\)/', $style->innertext, $matches)) {
            foreach ($matches[1] as $match) {
                $url = trim($match, "'\"");
                if(strpos($url,'//') === 0){
                    $url = 'https:'.$url;
                }
                if (strpos($url, 'fonts.gstatic.com') !== false) {
                    $path_parts = pathinfo($url);
                    $extension = isset($path_parts['extension'])?$path_parts['extension']:'';
                    if (empty($extension)) {
                       if(strpos($url,'/l/font') !== false){
                         $extension = 'svg';
                    }else{
                         $extension = 'woff2';
                         }
                    }
                    $filename = hash('sha256', $url, false);
                    $filename = substr($filename, 0, 10) . '.' . $extension;
                    // Check if the font file already exists
                    if (!file_exists($easyfonts_dir . '/' . $filename)) {
                        $font_data = wp_get_remote_file($url);
                        $file = fopen($easyfonts_dir . '/' . $filename, 'w');
                        fwrite($file, $font_data);
                        fclose($file);
                    }
                    $finalbase = get_base_url();
                    $local_url = $finalbase . '/easyfonts/' . $filename;
                    $content = str_replace($url, $local_url, $content);
                }
            }
        }
    }
    return $content;
}


function easy_fonts_combined_callback($buffer) {
    if (get_option('easyfonts_host_google_fonts_locally_link', false)) {
        $buffer = easyfont_process_content_link_tag($buffer);
    }

    if (get_option('easyfonts_host_google_fonts_locally_import', false)) {
        $buffer = easyfont_process_content_import($buffer);
    }

    if (get_option('easyfonts_remove_inline_css_fontface', false)) {
        $buffer = easyfont_download_gstatic_fonts($buffer);
    }

    if (get_option('easyfonts_remove_resource_hints', false)) {
        $buffer = easyfonts_remove_resource_hints($buffer);
    }

    if (get_option('easyfonts_remove_inline_script_font', false)) {
        $buffer = easyfont_remove_font_scripts($buffer);
    }

    return $buffer;
}

function easy_fonts_run_template_redirect() {
	
	ob_start('easy_fonts_combined_callback', 0, PHP_OUTPUT_HANDLER_REMOVABLE);
	add_filter( 'wordpress_prepare_output', 'easyfont_run_after_smart_slider', 11 );
	add_filter('groovy_menu_final_output', 'easyfont_run_after_smart_slider', 11);

	
}
add_action( 'template_redirect', 'easy_fonts_run_template_redirect', 999 );


function easyfont_run_after_smart_slider( $buffer ) {
	if (get_option('easyfonts_host_google_fonts_locally_link', false)) {
    $buffer = easyfont_process_content_link_tag( $buffer );
	}
    return $buffer;
}

function easyfonts_enqueue_options_styles() {
    if ( 'settings_page_easyfonts' == get_current_screen()->id ) {
        wp_enqueue_style( 'easyfonts-options-styles', plugin_dir_url( __FILE__ ) . 'assets/style.css', array(), '1.0.0' );
    }
}
add_action( 'admin_enqueue_scripts', 'easyfonts_enqueue_options_styles' );

function easyfonts_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=easyfonts">Settings</a>';
    array_push($links, $settings_link);
    return $links;
}
add_filter("plugin_action_links_easyfonts/easyfonts.php", "easyfonts_settings_link");