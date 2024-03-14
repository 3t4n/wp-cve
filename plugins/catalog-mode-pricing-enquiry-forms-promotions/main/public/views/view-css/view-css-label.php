<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Views_CSS_Label' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Views_CSS_Label {

        private $ui_type = 'label';

        public function compose_css( $css, $options ) {

            $ui_options = $this->get_options( $options );

            foreach ( $ui_options as $ui_option ) {

                $css = $this->get_css( $css, $ui_option );
            }

            return $css;
        }

        private function get_css( $css, $ui_option ) {

            $label_selector = $this->get_selector( $ui_option, '' );

            $css[ $label_selector ] = $this->get_css_props( $ui_option );

            $label_text_selector = $this->get_selector( $ui_option, ' .wmd-lbl-text' );

            $css[ $label_text_selector ] = $this->get_text_css_props( $ui_option );

            return $css;
        }

        private function get_css_props( $ui_option ) {

            $css_props = array();

            $css_props[ 'width' ] = WModes_Views_CSS_Util::get_size_css_prop( $ui_option[ 'width' ], 'auto' );
            $css_props[ 'height' ] = WModes_Views_CSS_Util::get_size_css_prop( $ui_option[ 'height' ], 'auto' );
            $css_props[ 'background-color' ] = $ui_option[ 'bg_color' ];
            $css_props[ 'color' ] = $ui_option[ 'color' ];
            $css_props[ 'margin' ] = WModes_Views_CSS_Util::get_boundary_css_prop( $ui_option[ 'margin' ], '0px' );
            $css_props[ 'padding' ] = WModes_Views_CSS_Util::get_boundary_css_prop( $ui_option[ 'padding' ], '0px' );
            $css_props[ 'border-radius' ] = WModes_Views_CSS_Util::get_size_css_prop( $ui_option[ 'border_radius' ], '0px' );

            $css_props = $this->get_border_css_props( $css_props, $ui_option );

            return $css_props;
        }

        private function get_border_css_props( $css_props, $ui_option ) {

            if ( empty( $ui_option[ 'border_style' ] ) || 'none' == $ui_option[ 'border_style' ] ) {

                $css_props[ 'border-style' ] = 'none';

                return $css_props;
            }

            $css_props[ 'border-style' ] = $ui_option[ 'border_style' ];
            $css_props[ 'border-color' ] = $ui_option[ 'border_color' ];
            $css_props[ 'border-width' ] = WModes_Views_CSS_Util::get_boundary_css_prop( $ui_option[ 'border_width' ], '1px' );

            return $css_props;
        }

        private function get_text_css_props( $ui_option ) {

            $css_props = array();

            $css_props[ 'font-size' ] = WModes_Views_CSS_Util::get_size_css_prop( $ui_option[ 'font_size' ], '12px' );
            $css_props[ 'line-height' ] = WModes_Views_CSS_Util::get_size_css_prop( $ui_option[ 'line_height' ], '12px' );

            return $css_props;
        }

        private function get_selector( $ui_option, $suffix = '' ) {

            return WModes_Views_CSS::get_css_selector( $ui_option[ 'ui_id' ], $this->ui_type, '', $suffix );
        }

        private function get_options( $options ) {

            return WModes::get_option( 'ui_labels', $this->get_default_options(), $options );
        }

        private function get_default_options() {

            $defaults = array();

            $defaults[] = array(
                'ui_id' => '2234343',
                'width' => array(
                    'size' => '110',
                    'unit' => 'px'
                ),
                'height' => array(
                    'size' => '32',
                    'unit' => 'px'
                ),
                'color' => WModes_Views_CSS_Util::get_theme_prop( 'color_2' ),
                'bg_color' => WModes_Views_CSS_Util::get_theme_prop( 'color_4' ),
                'margin' => array(
                    'top' => '',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                    'unit' => 'px'
                ),
                'padding' => array(
                    'top' => '',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                    'unit' => 'px'
                ),
                'border_radius' => array(
                    'size' => '4',
                    'unit' => 'px'
                ),
                'border_style' => 'none',
                'border_color' => WModes_Views_CSS_Util::get_theme_prop( 'color_2' ),
                'border_width' => array(
                    'top' => '1',
                    'right' => '1',
                    'bottom' => '1',
                    'left' => '1',
                    'unit' => 'px'
                ),
                'font_size' => array(
                    'size' => '12',
                    'unit' => 'px'
                ),
                'line_height' => array(
                    'size' => '12',
                    'unit' => 'px'
                ),
            );

            return $defaults;
        }

    }

}
