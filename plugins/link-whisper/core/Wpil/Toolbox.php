<?php

/**
 * A holder for utility methods that are useful to multiple classes.
 * Not intended as a catch-all for any method that doesn't seem to have a place to live
 */
class Wpil_Toolbox
{

    private static $pillar_ids = null;

    /**
     * Escapes strings for "LIKE" queries
     **/
    public static function esc_like($string = ''){
        global $wpdb;
        return '%' . $wpdb->esc_like($string) . '%';
    }

    /**
     * Gets if custom rules have been added to the .htaccess file
     **/
    public static function is_using_custom_htaccess(){
        // Check if a .htaccess file exists.
		if(defined('ABSPATH') && is_file(ABSPATH . '.htaccess')){
			// If the file exists, grab the content of it.
			$htaccess_content = file_get_contents(ABSPATH . '.htaccess');

			// Filter away the core WordPress rules.
			$filtered_htaccess_content = trim(preg_replace('/\# BEGIN WordPress[\s\S]+?# END WordPress/si', '', $htaccess_content));

            // return if there's anything still in the file
            return !empty($filtered_htaccess_content);
		}

        return false;
    }

    /**
     * Gets the current action hook priority that is being executed.
     * 
     * @return int|bool Returns the priority of the currently executed hook if possible, and false if it is not.
     **/
    public static function get_current_action_priority(){
        global $wp_filter;

        $filter_name = current_filter();
        if(isset($wp_filter[$filter_name])){
            $filter_instance = $wp_filter[$filter_name];
            if(method_exists($filter_instance, 'current_priority')){
                return $filter_instance->current_priority();
            }
        }

        return false;
    }

    /**
     * Checks if the link is relative.
     * Ported from URLChanger at version 2.1.6
     * 
     * @param string $link
     **/
    public static function isRelativeLink($link = ''){
        if(empty($link) || empty(trim($link))){
            return false;
        }

        if(strpos($link, 'http') === false && substr($link, 0, 1) === '/'){
            return true;
        }

        // parse the URL to see if it only contains a path
        $parsed = wp_parse_url($link);
        if( !isset($parsed['host']) && 
            !isset($parsed['scheme']) && 
            isset($parsed['path']) && !empty($parsed['path'])
        ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Checks to see if the current post is a pillar content post.
     * Currently only checks for Rank Math setting
     * 
     * @param int $post_id The id of the post that we're checking
     * @return bool Is this pillar content?
     **/
    public static function check_pillar_content_status($post_id = 0){
        global $wpdb;
        
        if(empty($post_id) || !defined('RANK_MATH_VERSION')){
            return false;
        }

        if(is_null(self::$pillar_ids)){
            $ids = $wpdb->get_col("SELECT DISTINCT `post_id` FROM {$wpdb->postmeta} WHERE `meta_key` = 'rank_math_pillar_content' AND `meta_value` = 'on'");
            self::$pillar_ids = (!empty($ids)) ? $ids: array();
        }

        return in_array($post_id, self::$pillar_ids);
    }

    /**
     * Compresses and base64's the given data so it can be saved in the db.
     * 
     * @param string $data The data to be compressed
     * @return null|string Returns a string of compressed and base64 encoded data 
     **/
    public static function compress($data = false){
        // first serialize the data
        $data = serialize($data);

        // if zlib is available
        if(extension_loaded('zlib')){
            // use it to compress the data
            $data = gzcompress($data);
        }elseif(extension_loaded('Bz2')){// if zlib isn't available, but bzip2 is
            // use that to compress the data
            $data = bzcompress($data);
        }

        // now base64 and return the (hopefully) compressed data
        return base64_encode($data);
    }

    /**
     * Decompresses stored data that was compressed with compress.
     * 
     * @param string $data The data to be decompressed
     * @return mixed $data 
     **/
    public static function decompress($data){
        // if there's no data or it's not a string
        if(empty($data) || !is_string($data)){
            // return the data unchanged
            return $data;
        }elseif(!Wpil_Link::checkIfBase64ed($data, true)){
            // if the data is not base64ed, try unserializing it when we send it back
            return maybe_unserialize($data);
        }

        // first un-64 the data
        $data = base64_decode($data);
        // then determine what our flavor of encoding is and decode the data
        // if zlib is available
        if(extension_loaded('zlib')){
            // if the data is zipped
            if(self::is_gz_compressed($data)){
                // use it to decompress the data
                $data = gzuncompress($data);
            }
        }elseif(extension_loaded('Bz2')){// if zlib isn't available, but bzip2 is
            // use that to decompress the data
            $data = bzdecompress($data);
        }

        // and return our unserialized and hopefully de-compressed data
        return maybe_unserialize($data);
    }

    /**
     * Gets post meta that _should_ be encoded and compressed and decompresses and decodes it before returning it
     **/
    public static function get_encoded_post_meta($id, $key, $single = false){
        $data = get_post_meta($id, $key, $single);

        if(!empty($data) && is_string($data)){
            // do a double check just to make sure that plain serialized data hasn't been handed to us
            if(is_serialized($data)){
                $data = maybe_unserialize($data);
            }else{
                $dat = self::decompress($data);
                if($dat !== false && $dat !== $data){
                    $data = $dat;
                }
            }
        }

        return $data;
    }

    /**
     * Compresses and encodes object and array based meta data and then saves it
     **/
    public static function update_encoded_post_meta($id, $key, $data, $prev_value = ''){
        if(!empty($data) && (is_array($data) || is_object($data))){
            $dat = self::compress($data);
            if(!empty($dat) && $dat !== $data){
                $data = $dat;
            }
        }

        update_post_meta($id, $key, $data, $prev_value);
    }

    /**
     * Gets term meta that _should_ be encoded and compressed and decompresses and decodes it before returning it
     **/
    public static function get_encoded_term_meta($id, $key, $single = false){
        $data = get_term_meta($id, $key, $single);

        if(!empty($data) && is_string($data)){
            // do a double check just to make sure that plain serialized data hasn't been handed to us
            if(is_serialized($data)){
                $data = maybe_unserialize($data);
            }else{
                $dat = self::decompress($data);
                if($dat !== false && $dat !== $data){
                    $data = $dat;
                }
            }
        }

        return $data;
    }

    /**
     * Compresses and encodes object and array based term meta data and then saves it
     **/
    public static function update_encoded_term_meta($id, $key, $data, $prev_value = ''){
        if(!empty($data) && (is_array($data) || is_object($data))){
            $dat = self::compress($data);
            if(!empty($dat) && $dat !== $data){
                $data = $dat;
            }
        }

        update_term_meta($id, $key, $data, $prev_value);
    }

    /**
     * Helper function. Checks to see if a supplied string is gzcompressed
     * @return bool
     **/
    public static function is_gz_compressed($encoded = ''){
        // first confirm that we're dealing with a possibly encoded string
        if(empty(trim($encoded)) || !is_string($encoded) || strlen($encoded) < 2){
            return false;
        }

        $header = substr($encoded, 0, 2);

        // check to make sure that the header is valid
        $byte1 = ord(substr($encoded, 0, 1));
        $byte2 = ord(substr($encoded, 1, 1));

        if(($byte1 * 256 + $byte2) % 31 !== 0){
            return false;
        }

        // check it against the most common zlib headers
        $zlib_headers = array("\x78\x01", "\x78\x9C", "\x78\xDA", "\x78\x20", "\x78\x5E");
        foreach($zlib_headers as $zheader){
            if($header === $zheader){
                return true;
            }
        }

        // if the first pass didn't work, try checking against less common but still possible headers
        $zlib_headers = array(
            "\x08\x1D",   "\x08\x5B",   "\x08\x99",   "\x08\xD7",
            "\x18\x19",   "\x18\x57",   "\x18\x95",   "\x18\xD3",
            "\x28\x15",   "\x28\x53",   "\x28\x91",   "\x28\xCF",
            "\x38\x11",   "\x38\x4F",   "\x38\x8D",   "\x38\xCB",
            "\x48\x0D",   "\x48\x4B",   "\x48\x89",   "\x48\xC7",
            "\x58\x09",   "\x58\x47",   "\x58\x85",   "\x58\xC3",
            "\x68\x05",   "\x68\x43",   "\x68\x81",   "\x68\xDE"
        );

        foreach($zlib_headers as $zheader){
            if($header === $zheader){
                return true;
            }
        }

        return false;
    }

    public static function output_dropdown_wrapper_atts($data = array()){
        if(empty($data) || !isset($data['report_type'])){
            return;
        }
        $output = '';
        switch($data['report_type']){
            case 'autolinks':
                if(isset($data['keyword_id'])){
                    $output .= ' data-keyword-id="' . (int)$data['keyword_id'] . '"';
                }
                if(isset($data['keyword'])){
                    $output .= ' data-keyword="' . esc_attr($data['keyword']) . '"';
                }
                if(isset($data['dropdown_type'])){
                    $output .= ' data-dropdown-type="' . esc_attr($data['dropdown_type']) . '"';
                }
                break;
            case 'links':
                if(isset($data['post_id'])){
                    $output .= ' data-wpil-report-post-id="' . (int)$data['post_id'] . '"';
                }
                if(isset($data['post_type'])){
                    $output .= ' data-wpil-report-post-type="' . esc_attr($data['post_type']) . '"';
                }
                break;
            default:
                break;
        }

        if(isset($data['nonce']) && !empty($data['nonce'])){
            $output .= ' data-wpil-collapsible-nonce="' . esc_attr($data['nonce']) . '"';
        }

        return $output;
    }

    /**
     * Takes an array of inline styles and validates them to make sure that we don't output anything we don't want to.
     * Also stringifies the styles so we can easily stick them in a tag
     * 
     * Expects the args to be 'property_name' => 'value'
     * Returns measurements in 'px'
     * 
     **/
    public static function validate_inline_styles($styles = array(), $create_style_tag = false){
        $output = '';
        
        if(empty($styles) || !is_array($styles)){
            return $output;
        }

        foreach($styles as $property_name => $value){
            switch ($property_name) {
                case 'height':
                case 'width':
                    $output .= $property_name . ':' . intval($value) . 'px; ';
                    break;
                case 'fill':
                case 'stroke':
                    preg_match('/#(?:[A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})/', $value, $color);
                    if(isset($color[0]) && !empty($color[0])){
                        $output .= $property_name . ':' . $color[0] . '; ';
                    }
                    break;
                case 'display':
                    switch($value){
                        case 'block':
                        case 'inline-block':
                        case 'inline':
                        case 'flex':
                            $output .= $property_name . ':' . $value . '; ';
                        break;
                    }
                    break;
                default:
                    break;
            }
        }

        $output = trim($output);

        if($create_style_tag){
            $output = 'style="' . $output . '"';
        }

        return $output;
    }

    /**
     * Converts the site's date format into a format we can use in our JS calendars.
     * Confirms that the format contains Months, Days and Years, as well as confirming that the user has a set date format.
     * If any of these aren't true, it defaults to the normal MM/DD/YYYY format
     **/
    public static function convert_date_format_for_js(){
        $format = get_option('date_format', 'F d, Y');
        $day = false;
        $month = false;
        $year = false;

        $new_format = '';
        for($i = 0; $i < strlen($format); $i++){
            if(!empty($format[$i])){
                switch($format[$i]){
                    case 'd':
                    case 'j':
                        $new_format .= 'DD/';
                        $day = true;
                        break;
                    case 'F':
                    case 'm':
                    case 'n':
                        $new_format .= 'MM/';
                        $month = true;
                        break;
                    case 'M':
                        $new_format .= 'MMM/';
                        $month = true;
                        break;
                    case 'y':
                        $new_format .= 'YY/';
                        $year = true;
                        break;
                    case 'x':
                    case 'X':
                    case 'Y':
                        $new_format .= 'YYYY/';
                        $year = true;
                        break;
                }
            }
        }

        $new_format = trim($new_format, '/');

        return !empty($new_format) && ($day && $month && $year) ? $new_format: 'MM/DD/YYYY';
    }

    /**
     * Reconverts the site's date format from the JS to one useable by PHP.
     * That way, we'll be sure that both formats add up when we use them
     **/
    public static function convert_date_format_from_js(){
        $format = self::convert_date_format_for_js();

        $bits = explode('/', $format);
        $new_format = '';
        foreach($bits as $bit){
            if(!empty($bit)){
                switch($bit){
                    case 'DD':
                        $new_format .= 'd/';
                        break;
                    case 'MM':
                        $new_format .= 'm/';
                        break;
                    case 'MMM':
                        $new_format .= 'M/';
                        break;
                    case 'YY':
                        $new_format .= 'y/';
                        break;
                    case 'YYYY':
                        $new_format .= 'Y/';
                        break;
                }
            }
        }

        $new_format = trim($new_format, '/');

        return !empty($new_format) ? $new_format: 'd/m/y';
    }

    /**
     * Gets all post ids that are related to the current post.
     * Pulls the post's parent id, and all of it's sibling post ids.
     * @param object Wpil_Modal_Post post object
     * @return array
     **/
    public static function get_related_post_ids($post = array()){
        global $wpdb;

        if(empty($post) || (isset($post->type) && $post->type === 'term')){
            return array();
        }

        $ids = array();
        $ancestors = get_post_ancestors($post->id);

        if(!empty($ancestors)){
            $ancestors = array_map(function($id){ return (int) $id; }, $ancestors);
            $ids = $ancestors;
            $ancestors = implode(',', $ancestors);
            $results = $wpdb->get_col("SELECT DISTINCT ID FROM {$wpdb->posts} WHERE `post_parent` IN ($ancestors)");

            if(!empty($results)){
                $ids = array_merge($ids, $results);
            }
        }

        $children = get_children(array('post_parent' => $post->id));

        if(!empty($children)){
            $ids[] = $post->id;
            foreach($children as $child){
                $ids[] = $child->ID;
                $grandchildren = get_children(array('post_parent' => $child->ID));
                if(!empty($grandchildren)){
                    foreach($grandchildren as $grandchild){
                        $ids[] = $grandchild->ID;
                    }
                }
            }
        }

        if(!empty($ids)){
            $ids = array_flip(array_flip($ids));
        }

        return $ids;
    }

    /**
     * 
     **/
    public static function get_site_meta_row_count(){
        global $wpdb;

        return $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->postmeta}");
    }
}
