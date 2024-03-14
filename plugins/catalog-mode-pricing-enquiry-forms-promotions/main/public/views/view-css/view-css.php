<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Views_CSS' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    require_once 'view-css-util.php';
    require_once 'view-css-label.php';
    require_once 'view-css-textblock.php';

    class WModes_Views_CSS {

        private static $css_pre = 'wmd-css-';
        private static $css_dir = '/wmodes';
        private static $css_file_name = '/custom.css';

        public static function is_using_external_css() {

            $default = 'no';

            $opt = WModes::get_option( 'using_external_css', $default );

            return ('yes' == $opt);
        }

        public static function get_custom_css_ver() {

            $default = 0;

            return WModes::get_option( 'custom_css_vertion', $default );
        }

        public static function get_custom_css_url() {

            $upload_dir = wp_upload_dir();

            return $upload_dir[ 'baseurl' ] . self::$css_dir . self::$css_file_name;
        }

        public static function get_css_class( $ui_id, $ui_type ) {

            return self::$css_pre . $ui_type . '-' . $ui_id;
        }

        public static function get_css_selector( $ui_id, $ui_type, $prefix = '', $suffix = '' ) {

            $ui_selector = '.' . self::$css_pre . $ui_type . '-' . $ui_id;

            return $prefix . $ui_selector . $suffix;
        }

        public static function get_css() {

            $options = WModes::get_all_options();

            return self::compile_css( self::compose_css( $options ), $options );
        }

        public static function save_css( $options ) {

            if ( !('yes' == $options[ 'use_external_css' ]) ) {

                return false;
            }

            global $wp_filesystem;

            if ( !is_a( $wp_filesystem, 'WP_Filesystem_Base' ) ) {

                $creds = request_filesystem_credentials( site_url() );

                WP_Filesystem( $creds );
            }

            $upload_dir = wp_upload_dir();

            $wmodes_dir = $upload_dir[ 'basedir' ] . self::$css_dir;

            if ( !file_exists( $wmodes_dir ) ) {

                wp_mkdir_p( $wmodes_dir );
            }

            $css_file_name = trailingslashit( $wmodes_dir ) . self::$css_file_name;

            $css_content = self::compile_css( self::compose_css( $options ), $options );

            return $wp_filesystem->put_contents( $css_file_name, $css_content, FS_CHMOD_FILE );
        }

        private static function compose_css( $options ) {

            $label_css = new WModes_Views_CSS_Label();
            $css = $label_css->compose_css( array(), $options );

            $textblock_css = new WModes_Views_CSS_Text_Block();
            $css = $textblock_css->compose_css( $css, $options );



            return $css;
        }

        private static function compile_css( $css, $options ) {

            $css_content = '';

            foreach ( $css as $css_selector => $raw_css_props ) {

                $css_props = self::sanitize_css_props( $raw_css_props );

                if ( !count( $css_props ) ) {

                    continue;
                }

                $selector = esc_attr( $css_selector );

                if ( empty( $selector ) ) {
                    continue;
                }

                $css_content .= $selector . '{' . self::compile_css_props( $css_props ) . '}';
            }

            if ( isset( $options[ 'custom_css' ] ) && !empty( $options[ 'custom_css' ] ) ) {

                $css_content .= $options[ 'custom_css' ];
            }

            return self::minify_css( $css_content );
        }

        private static function compile_css_props( $css_props ) {

            $css_prop_content = '';

            foreach ( $css_props as $css_prop_key => $css_prop ) {

                $prop_key = esc_attr( $css_prop_key );
                $prop = esc_attr( $css_prop );

                if ( empty( $prop_key ) ) {
                    continue;
                }

                if ( '' == $prop ) {
                    continue;
                }

                $css_prop_content .= $prop_key . ': ' . $prop . ';';
            }

            return $css_prop_content;
        }

        private static function sanitize_css_props( $css_props ) {

            $sanitized_css_props = array();

            foreach ( $css_props as $css_prop_key => $css_prop ) {

                $prop_key = esc_attr( $css_prop_key );
                $prop = esc_attr( $css_prop );

                if ( empty( $prop_key ) ) {

                    continue;
                }

                if ( '' == $prop ) {
                    continue;
                }

                $sanitized_css_props[ $prop_key ] = $prop;
            }

            return $sanitized_css_props;
        }

        private static function minify_css( $css_content ) {

            $css_content = preg_replace( '/\s+/', ' ', $css_content );

            $css_content = preg_replace( '/;(?=\s*})/', '', $css_content );

            $css_content = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css_content );

            $css_content = preg_replace( '/ (,|;|\{|})/', '$1', $css_content );

            $css_content = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css_content );

            $css_content = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css_content );

            return trim( $css_content );
        }

    }

}