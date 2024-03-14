<?php
defined( 'ABSPATH' ) or exit;

if ( ! class_exists( 'ScrollStylerProcess' ) ) {

    class ScrollStylerProcess extends ScrollStyler {

        public $data = array();
        public $langs = array();

        /**
         * Constructor
         */
        public function __construct() {
            $this->setLang();
            $this->sanitizeFormData();
            $this->setDefaultOptions();
            $this->dataSend();
        }

        /**
         * Get Instance
         */
        public static function getInstance() {

            if ( isset( $instance ) ) return;

            $instance = new ScrollStylerProcess;
            return $instance;
        }

        /**
         * Set lang
         */
        public function setLang() {
            $this->langs = array(
                'formDesc' => __( 'Please note if the <i>scrollbarTrackPadding</i> parameter is greater than <i>0</i>, full fence is not available on the scroll thumb.', 'scrollStyler' ),
                'formSuccessDesc' => __( 'Your settings have been successfully saved.', 'scrollStyler' ),
                'scrollbarWidthLabel' => __( 'Scrollbar Width', 'scrollStyler' ),
                'scrollbarTrackPaddingLabel' => __( 'Scrollbar Track Padding', 'scrollStyler' ),
                'scrollbarTrackBgColorLabel' => __( 'Scrollbar Track Background Color', 'scrollStyler' ),
                'scrollbarThumbBgColorLabel' => __( 'Scrollbar Thumb Background Color', 'scrollStyler' ),
                'scrollbarThumbBgColorHoverLabel' => __( 'Scrollbar Thumb Hover Background Color', 'scrollStyler' ),
                'scrollbarThumbBgColorActiveLabel' => __( 'Scrollbar Thumb Active Background Color', 'scrollStyler' ),
                'scrollbarThumbBorderRadiusLabel' => __( 'Scrollbar Thumb Border Radius', 'scrollStyler' ),
                'minLabel' => __( 'min:', 'scrollStyler' ),
                'maxLabel' => __( 'max:', 'scrollStyler' ),
                'saveButtonText' => __( 'Save Settings', 'scrollStyler' )
            );
        }

        /**
         * Get lang
         */
        public function getLang( $value ) {
            return $this->langs[ $value ];
        }

        /**
         * Escape form data
         */
        private function sanitizeFormData() {
            foreach ( $_POST as $key => $value ) {
                $this->data[ $key ] = sanitize_text_field( $value );
            }
        }

        /**
         * Set default options
         */
        private function setDefaultOptions() {

            if ( ! get_option( parent::$pluginDbOptionName ) ) {

                // Add options
                add_option( parent::$pluginDbOptionName, json_encode( parent::$dataDefaults ) );

                // Get options
                $this->data = parent::$dataDefaults;

            } else {

                // Get options
                $this->data = json_decode( get_option( parent::$pluginDbOptionName ), true );
                $this->data = array_merge( parent::$dataDefaults, $this->data );

            }
        }

        /**
         * Form data send
         */
        private function dataSend() {

            if ( ! isset( $_POST[parent::$pluginDbOptionName . 'Enabled'] ) ) return;

            // Escape form data
            $this->sanitizeFormData();

            // Update options
            update_option( parent::$pluginDbOptionName, json_encode( $this->data ) );

            // Get options after update
            $this->data = json_decode( get_option( parent::$pluginDbOptionName ), true );
        }
    }
}

/**
 * Create an instance
 */
if ( class_exists( 'ScrollStylerProcess' ) ) {
    $scrollStylerProcess = ScrollStylerProcess::getInstance();
}