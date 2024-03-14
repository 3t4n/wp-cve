<?php

namespace LaStudioKitExtensions\Swatches\Classes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Product_Term extends Swatch_Term{

    protected $attribute_options;

    /**
     * @param Configuration $config
     * @param $option
     * @param $taxonomy
     * @param $selected
     */
    public function __construct( $config = null, $option = '', $taxonomy = '', $selected = false ) {

        $this->layout = $config->get_swatch_layout();
        $this->type = $config->get_swatch_type();
        $this->attribute_options = $config->get_configs();

        $this->taxonomy_slug = $taxonomy;
        if (taxonomy_exists($taxonomy)) {
            $this->term = get_term($option, $taxonomy);
            $this->term_label = $this->term->name;
            $this->term_slug = $this->term->slug;
            $this->term_name = $this->term->name;
        }
        else {
            $this->term = false;
            $this->term_label = $option;
            $this->term_slug = $option;
        }

        $this->selected = $selected;

        $this->init_term_data();

    }

    private function init_term_data(){

        $attribute_options = $this->attribute_options;
        if(isset($attribute_options['swatch_size'])){
            $this->size = $attribute_options['swatch_size'];
        }
        if(isset($attribute_options['style'])){
            $this->style = $attribute_options['style'];
        }
        if(isset($attribute_options['layout'])){
            $this->layout = $attribute_options['layout'];
        }

        $this->init_swatch_sizes();

        $key = md5( sanitize_title($this->term_slug) );
        $old_key = sanitize_title($this->term_slug);
        $lookup_key = '';
        if (isset($attribute_options['attributes'][$key])) {
            $lookup_key = $key;
        }
        elseif (isset($attribute_options['attributes'][$old_key])) {
            $lookup_key = $old_key;
        }

        $this->type = isset($attribute_options['attributes'][$lookup_key]['type']) ? $attribute_options['attributes'][$lookup_key]['type'] : 'color';
        $this->color = isset($attribute_options['attributes'][$lookup_key]['color']) ? $attribute_options['attributes'][$lookup_key]['color'] : '';
        $this->color2 = isset($attribute_options['attributes'][$lookup_key]['color2']) ? $attribute_options['attributes'][$lookup_key]['color2'] : '';
        $this->thumbnail_src = WC()->plugin_url() . '/assets/images/placeholder.png';

        if (isset($attribute_options['attributes'][$lookup_key]['photo']) && $attribute_options['attributes'][$lookup_key]['photo']) {
            $this->thumbnail_id = $attribute_options['attributes'][$lookup_key]['photo'];
            $current_img = apply_filters('lastudio-kit/swatches/get_attribute_thumbnail_src', wp_get_attachment_image_url($this->thumbnail_id, $this->size), $this->thumbnail_id, $this->size, $this);
            if($current_img){
                $this->thumbnail_src = $current_img;
            }
        }
    }
}