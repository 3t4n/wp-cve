<?php
/**
 * @package Google_Image_Proxy
 * @author muzuiget
 * @version 0.1
 */
/*
Plugin Name: Google Image Proxy
Plugin URI: http://code.google.com/p/muzuiget-toolbox/
Description: If you can not directly access image links or the loading is slowly, this script will rewrite the image links to googleusercontent.com proxy address. With Google server you can display image normally and load faster.
Version: 0.1
License: GPLv3
Author: muzuiget
Author URI: http://qixinglu.com/
*/

function google_image_proxy($content) {
    $prefix = 'https://images2-focus-opensocial.googleusercontent.com/gadgets/proxy?url=';
    $suffix = '&container=focus&gadget=a&no_expand=1&resize_h=0&rewriteMime=image%2F*';
    $pattern = '/(<img [^>]* ?src=)["\']?(https?:\/\/[^"\' ]+)["\']?([^>]+>)/ism';

    /* method 1, does not encode url */
    //$replacement = '${1}"'.$prefix.'${2}'.$suffix.'"${3}';
    //return preg_replace($pattern, $replacement, $content);

    /* method 2, preg_match then str_replace */
    preg_match_all($pattern, $content, $matches);
    foreach ($matches[2] as $match_url) {
        //if (strpos($match_url, $prefix) !== false) { continue; }
        $replacement = $prefix.urlencode($match_url).$suffix;
        $content = str_replace($match_url, $replacement, $content);
    }
    return $content;
}

add_filter('the_content', 'google_image_proxy');

?>
