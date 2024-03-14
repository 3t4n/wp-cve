<?php

namespace LaStudioKitExtensions\Swatches\Classes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Configuration{

    /**
     * @var array 
     */
    private $_swatch_options;

    /**
     *
     * @param \WC_Product $product
     * @param string $attribute The name of the attribute.
     */

    public function __construct( $product, $attribute ) {

        $swatch_options = get_post_meta( $product->get_id(), 'lakit_swatch_data', true );

        if ( !empty( $swatch_options ) ) {

            $st_name = str_replace('-', '_', sanitize_title( $attribute ));
            $hashed_name = md5( $st_name );
            $lookup_name = '';

            //Normalize the key we use, this is for backwards compatibility.
            if ( isset( $swatch_options[$hashed_name] ) ) {
                $lookup_name = $hashed_name;
            }
            elseif ( isset( $swatch_options[$st_name] ) ) {
                $lookup_name = $st_name;
            }

            $key_defaults = [
                'type' => 'default',
                'swatch_size' => 'default',
                'layout' => 'default',
                'style' => 'default',
            ];

            foreach ($key_defaults as $key_default => $value_default){
                if(!isset($swatch_options[$lookup_name][$key_default])){
                    $swatch_options[$lookup_name][$key_default] = $value_default;
                }
            }

            $this->_swatch_options = $swatch_options[$lookup_name];
        }

    }

    /**
     * Returns the type of input to display.
     */
    public function get_swatch_type() {
        $type = apply_filters('lastudio-kit/swatches/configuration_object/get_swatch_type', $this->get_setting('type', 'default' ), $this);
        if($type == 'default'){
            $type = 'term_options';
            $this->set_config('type', $type);
        }
        return $type;
    }

    public function get_swatch_size() {
        return apply_filters( 'lastudio-kit/swatches/configuration_object/get_swatch_size', $this->get_setting( 'swatch_size', 'default' ), $this );
    }

    public function get_swatch_layout() {
        return apply_filters( 'lastudio-kit/swatches/configuration_object/get_swatch_layout', $this->get_setting('layout', 'default'), $this );
    }

    public function get_swatch_style() {
        return apply_filters( 'lastudio-kit/swatches/configuration_object/get_swatch_style', $this->get_setting('style', 'default'), $this );
    }

    public function get_configs() {
        return apply_filters( 'lastudio-kit/swatches/configuration_object/get_config', $this->_swatch_options, $this );
    }

    public function set_config( $key = '', $value = '' ){
        if( !empty($key) && !empty($value)){
            $this->_swatch_options[$key] = $value;
        }
    }

    /**
     * Safely get a configuration value from the swatch options.
     * @param string $key
     * @param mixed $default
     * @return string
     */
    private function get_setting( $key, $default = null ) {
        return isset( $this->_swatch_options[$key] ) ? $this->_swatch_options[$key] : $default;
    }
}