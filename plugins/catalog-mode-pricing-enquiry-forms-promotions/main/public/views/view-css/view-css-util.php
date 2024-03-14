<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Views_CSS_Util' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Views_CSS_Util {

        public static function get_size_css_prop( $ui_size = array(), $default = '', $use_force = false, $size_key = 'size' ) {

            $important = '';

            if ( $use_force ) {
                $important = '!important';
            }

            if ( !isset( $ui_size[ $size_key ] ) || empty( $ui_size[ $size_key ] ) ) {

                return $default . $important;
            }

            if ( !isset( $ui_size[ 'unit' ] ) || empty( $ui_size[ 'unit' ] ) ) {

                $ui_size[ 'unit' ] = 'px';
            }

            return $ui_size[ $size_key ] . $ui_size[ 'unit' ] . $important;
        }

        public static function get_boundary_css_prop( $ui_boundary_size = array(), $default = array(), $use_force = false ) {

            $important = '';

            if ( $use_force ) {
                $important = '!important';
            }


            $unit = 'px';

            if ( isset( $ui_boundary_size[ 'unit' ] ) && !empty( $ui_boundary_size[ 'unit' ] ) ) {

                $unit = $ui_boundary_size[ 'unit' ];
            }

            $top = self::get_boundary_side( 'top', $ui_boundary_size, $unit, $default );
            $right = self::get_boundary_side( 'right', $ui_boundary_size, $unit, $default );
            $bottom = self::get_boundary_side( 'bottom', $ui_boundary_size, $unit, $default );
            $left = self::get_boundary_side( 'left', $ui_boundary_size, $unit, $default );

            return $top . ' ' . $right . ' ' . $bottom . ' ' . $left;
        }

        public static function get_css_prop_with_default( $prop, $default = '', $use_force = false ) {

            $important = '';

            if ( $use_force ) {
                $important = '!important';
            }

            $value = $prop;

            if ( empty( $value ) ) {

                $value = $default;
            }

            if ( empty( $value ) ) {

                $important = '';
            }

            return $value . $important;
        }

        private static function get_boundary_side( $side, $ui_boundary_size, $unit, $default ) {


            if ( !isset( $ui_boundary_size[ $side ] ) || '' == $ui_boundary_size[ $side ] ) {

                if ( !is_array( $default ) ) {

                    return $default;
                }

                if ( 1 == count( $default ) ) {

                    return $default[ 0 ];
                }

                if ( isset( $default[ $side ] ) ) {

                    return $default[ $side ];
                }

                return '';
            } else {

                return $ui_boundary_size[ $side ] . $unit;
            }
        }

        public static function get_theme_prop( $key ) {

            $theme_values = array(
                'accent_color_1' => '#ffc000',
                'accent_color_2' => '#ff0',
                'color_1' => '#222',
                'color_2' => '#999',
                'color_3' => '#ddd',
                'color_4' => '#eee',
                'color_5' => '#fff',
            );

            return $theme_values[ $key ];
        }

    }

}