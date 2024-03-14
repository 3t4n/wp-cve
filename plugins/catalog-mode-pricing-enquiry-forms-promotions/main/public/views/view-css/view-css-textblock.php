<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Views_CSS_Text_Block' ) ) {

    class WModes_Views_CSS_Text_Block {

        private $ui_type = 'textblock';

        public function compose_css( $css, $options ) {
            $ui_options = $this->get_options( $options );

            foreach ( $ui_options as $ui_option ) {

                $css = $this->get_css( $css, $ui_option );
            }

            return $css;
        }

        private function get_css( $css, $ui_option ) {

            $textblock_selector = $this->get_selector( $ui_option, '' );
            $css[ $textblock_selector ] = $this->get_css_props( $ui_option );

            return $css;
        }

        private function get_css_props( $ui_option ) {

            $css_props = array();

            $css_props[ 'font-size' ] = WModes_Views_CSS_Util::get_size_css_prop( $ui_option[ 'font_size' ], '' );
            $css_props[ 'line-height' ] = WModes_Views_CSS_Util::get_size_css_prop( $ui_option[ 'line_height' ], '' );
            $css_props[ 'font-weight' ] = $ui_option[ 'font_weight' ];
            $css_props[ 'font-family' ] = $ui_option[ 'font_family' ];
            $css_props[ 'color' ] = $ui_option[ 'text_color' ];
            $css_props[ 'text-align' ] = $ui_option[ 'justify_contents' ];
            $css_props[ 'width' ] = WModes_Views_CSS_Util::get_size_css_prop( $ui_option[ 'width' ], '' );
            $css_props[ 'background-color' ] = $ui_option[ 'bg_color' ];
            $css_props[ 'padding' ] = WModes_Views_CSS_Util::get_boundary_css_prop( $ui_option[ 'padding' ], '0px' );
            $css_props[ 'margin' ] = WModes_Views_CSS_Util::get_boundary_css_prop( $ui_option[ 'margin' ], '0px' );
            $css_props[ 'border-radius' ] = WModes_Views_CSS_Util::get_size_css_prop( $ui_option[ 'border_radius' ], '' );

            return $this->get_border_css_props( $css_props, $ui_option );
        }

        private function get_border_css_props( $css_props, $ui_option ) {

            $border_style = $ui_option[ 'border_style' ];

            if ( empty( $border_style ) || 'none' == $border_style ) {

                $css_props[ 'border-style' ] = $border_style;

                return $css_props;
            }

            $css_props[ 'border-style' ] = $border_style;
            $css_props[ 'border-color' ] = $ui_option[ 'border_color' ];
            $css_props[ 'border-width' ] = WModes_Views_CSS_Util::get_boundary_css_prop( $ui_option[ 'border_width' ], '1px' );

            return $css_props;
        }

        private function get_selector( $ui_option, $suffix = '' ) {

            return WModes_Views_CSS::get_css_selector( $ui_option[ 'ui_id' ], $this->ui_type, '', $suffix );
        }

        private function get_options( $options ) {

            return WModes::get_option( 'ui_textblocks', $this->get_default_options(), $options );
        }

        private function get_default_options() {

            $defaults = array();

            $defaults[] = array(
                'ui_id' => '2234343',
                'text_color' => WModes_Views_CSS_Util::get_theme_prop( 'color_1' ),
                'font_size' => array(
                    'size' => 12,
                    'unit' => 'px'
                ),
                'line_height' => array(
                    'size' => 26,
                    'unit' => 'px'
                ),
                'font_weight' => '',
                'font_family' => '',
                'justify_contents' => 'center',
                'bg_color' => WModes_Views_CSS_Util::get_theme_prop( 'color_4' ),
                'width' => array(
                    'size' => '',
                    'unit' => 'px'
                ),
                'padding' => array(
                    'top' => 5,
                    'right' => 8,
                    'bottom' => 5,
                    'left' => 8,
                    'unit' => 'px'
                ),
                'margin' => array(
                    'top' => '',
                    'right' => '',
                    'bottom' => 15,
                    'left' => '',
                    'unit' => 'px'
                ),
                'border_radius' => array(
                    'size' => '',
                    'unit' => 'px'
                ),
                'border_style' => 'none',
                'border_color' => WModes_Views_CSS_Util::get_theme_prop( 'color_1' ),
                'border_width' => array(
                    'top' => '1',
                    'right' => '1',
                    'bottom' => '1',
                    'left' => '1',
                    'unit' => 'px'
                ),
            );

            return $defaults;
        }

    }

}
