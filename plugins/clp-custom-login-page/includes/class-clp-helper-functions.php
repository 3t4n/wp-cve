<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
};


class CLP_Helper_Functions {

    /**
     * Returns attachment URL from ID via AJAX
     * @since 1.0.0
    **/
    public static function clp_wp_get_attachment_url_ajax( ) {
        
        // verify user rights
        if( !current_user_can('publish_pages') ) {
            die('Sorry, but this request is invalid');
        }

        $id = '';

        if ( !empty($_REQUEST["id"]) && is_numeric($_REQUEST["id"]) ) {
            $id = filter_var($_REQUEST["id"], FILTER_SANITIZE_NUMBER_INT);
        }

        echo wp_get_attachment_url($id);

        wp_die();
    }
    
    /**
     * Render customizer link
     * @since 1.0.0
    **/
    public static function customizer_icon_link( $section, $label = 'element', $icon = 'dashicons-edit' ) {
        // bail out early if not in customizer
        if ( !is_customize_preview() ) {
            return;
        }
        
        echo '<span id="'.esc_attr($section).'" class="customize-partial-edit-shortcut clp-customizer-preview" data-section="'.esc_attr($section).'"><button aria-label="Click to edit '.esc_attr($label).'." title="Click to edit '.esc_attr($label).'." class="customize-partial-edit-shortcut-button"><span class="dashicons '.$icon.'"></span></button></span>';

        return;
    }

    /**
     * Return Font Variant and Font Weight from Google Font Variant - 300italic => 300, italic
     * @since 1.0.0
     * @return array
    **/
    public static function process_google_font_variant( $variant ) {
        $split = preg_split('#(?<=\d)(?=[a-z])#i', $variant);

        $return = array(
            'weight' => is_numeric($split[0]) ? $split[0] : '400',
            'style' => isset($split[1]) || $split[0] === 'italic' ? 'italic' : 'normal'
        );
        
        return $return;
    }

    /**
     * checks user agent for mobile device
     * @since 1.0.0
     * @return boolean
    **/
    public static function is_mobile() {

        if ( isset($_SERVER["HTTP_USER_AGENT"]) ) {
            return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);

        } else {
            return false;
        }
    }

    /**
     * return image size based on desktop / mobile
     * @since 1.0.0
     * @return boolean
    **/
    public static function get_image_size() {
        return self::is_mobile() ? 'large' : 'full';
    }

    /**
     * Insert an attachment from an URL address.
     * by https://gist.github.com/m1r0/f22d5237ee93bcccb0d9
     *
     * @since 1.2.0
     * @param  String $url
     * @param  Int    $parent_post_id
     * @return Int    Attachment ID
     */
    public static function insert_attachment_from_url($url, $parent_post_id = null) {

        if( !class_exists( 'WP_Http' ) ) {
            include_once( ABSPATH . WPINC . '/class-http.php' );
        }
            
        $http = new WP_Http();

        $response = $http->request( $url );

        if( is_wp_error( $response ) || $response['response']['code'] != 200 ) {
            return false;
        }

        $upload = wp_upload_bits( basename($url), null, $response['body'] );

        if( !empty( $upload['error'] ) ) {
            return false;
        }

        $file_path = $upload['file'];
        $file_name = basename( $file_path );
        $file_type = wp_check_filetype( $file_name, null );
        $attachment_title = sanitize_file_name( pathinfo( $file_name, PATHINFO_FILENAME ) );
        $wp_upload_dir = wp_upload_dir();
        $post_info = array(
            'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
            'post_mime_type' => $file_type['type'],
            'post_title'     => $attachment_title,
            'post_content'   => '',
            'post_status'    => 'inherit',
        );

        // Create the attachment
        $attach_id = wp_insert_attachment( $post_info, $file_path, $parent_post_id );
        // Include image.php
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        // Define attachment metadata
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );
        // Assign metadata to attachment
        wp_update_attachment_metadata( $attach_id,  $attach_data );

        return $attach_id;
    }

    /**
     * Compare values with passed operator
     *
     * @since 1.3.0
     * @return Boolean
     */
    public static function num_cond($value1, $value2, $op = '=') {
        switch ($op) {
            case '=':  return $value1 == $value2;
            case '!=': return $value1 != $value2;
            case '>=': return $value1 >= $value2;
            case '<=': return $value1 <= $value2;
            case '>':  return $value1 >  $value2;
            case '<':  return $value1 <  $value2;
        default:       return true;
        }   
    }
    
    /**
     * returns Asset version based on CLP_DEV constant
     *
     * @since 1.3.0
     * @return string
     */
    public static function assets_version( $asset ) {
        return CLP_DEV ? filemtime(CLP_PLUGIN_DIR . $asset ) : CLP_VERSION;
    }

    /**
     * Returns locale settings
     * @since 1.4.3
    **/
    public static function get_locale() {
        $locale = apply_filters( 'clp_get_locale', get_locale() );
        return $locale;
    }

    /**
     * Returns sanitized checkbox
     * @since 1.4.4
    **/    
    public static function sanitize_checkbox( $input ) {
        return ( ( isset( $input ) && true == $input ) ? '1' : '0' );
    }

    /**
     * helper function to get seconds based on the passed unit
     * @since 1.4.0
     * @param string
     * @return int
    **/
    public static function time_unit_to_seconds( $time_unit ) {
        switch ($time_unit) {
            case 'minute':
                return MINUTE_IN_SECONDS;
            case 'hour':
                return HOUR_IN_SECONDS;
            case 'day':
                return DAY_IN_SECONDS;
            case 'month':
                return MONTH_IN_SECONDS;
            default:
                break;
        }

        return DAY_IN_SECONDS;
    }

    public static function get_opacity_from_color_code( $color ) {
        $arr = explode(',', $color);
        $opacity = 1;
        if ( count($arr) === 4 ) {
            $opacity = (float) str_replace(')', '', $arr[3]);
        }

        return $opacity;
    }

}