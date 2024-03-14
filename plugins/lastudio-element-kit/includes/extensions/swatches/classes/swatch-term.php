<?php

namespace LaStudioKitExtensions\Swatches\Classes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Swatch_Term{

    protected $term_id;
    protected $term;
    protected $term_label;
    protected $term_slug;
    protected $taxonomy_slug;
    protected $selected;
    protected $type;
    protected $color;
    protected $color2;
    protected $thumbnail_src;
    protected $thumbnail_id;
    protected $size = 'default';
    protected $width = 40;
    protected $height = 40;
    protected $layout = 'default';
    protected $style = 'default';

    /**
     * @param Configuration $config
     * @param $term_id
     * @param $taxonomy
     * @param $selected
     * @param $size
     */
    public function __construct( $config = null, $term_id = 0, $taxonomy = '', $selected = false, $size = 'default' ) {

        if(NULL != $config){
            $this->layout = $config->get_swatch_layout();
            $this->style = $config->get_swatch_style();
        }

        $term_object = get_term( $term_id, $taxonomy );
        if(!is_wp_error($term_object)){
            $this->term_id = $term_object->term_id;
            $this->term_label = $term_object->name;
            $this->term_slug = $term_object->slug;
        }
        else{
            $this->term_id = 0;
            $this->term_label = 'none';
            $this->term_slug = 'none';
        }

        $this->taxonomy_slug = $taxonomy;
        $this->selected = $selected;

        $this->size = $size;

        $this->init_swatch_sizes();
        $this->on_init();

    }

    protected function init_swatch_sizes() {
        $size = $this->size;

        if( $size == 'default' ) {
            $swatches_configs = [
                'image_size' => [
                    'width' => lastudio_kit_settings()->get('swatches_swatches_size_width', 40),
                    'height' => lastudio_kit_settings()->get('swatches_swatches_size_height', 40),
                ]
            ];
            if(isset($swatches_configs['image_size']['width'])) {
                $this->width = $swatches_configs['image_size']['width'];
            }
            else{
                $this->width = 40;
            }
            if(isset($swatches_configs['image_size']['height'])) {
                $this->height = $swatches_configs['image_size']['height'];
            }
            else{
                $this->height = 40;
            }
        }
        else{
            $_wp_additional_image_sizes = wp_get_additional_image_sizes();
            $the_size = isset( $_wp_additional_image_sizes[$size] ) ? $_wp_additional_image_sizes[$size] : $_wp_additional_image_sizes['thumbnail'];
            if ( isset( $the_size['width'] ) && isset( $the_size['height'] ) ) {
                $this->width = $the_size['width'];
                $this->height = $the_size['height'];
            }
            else {
                $this->width = 40;
                $this->height = 40;
            }
        }
    }

    protected function on_init() {

        $term_type = get_term_meta( $this->term_id, '_lakit_swatch_type', true );
        $type = !empty($term_type) ? $term_type : 'none';
        $this->type = $type;
        $this->thumbnail_src = WC()->instance()->plugin_url() . '/assets/images/placeholder.png';
        if ( $type == 'photo' ) {
            $term_photo = get_term_meta( $this->term_id, '_lakit_swatch_photo', true );
            if ( !empty($term_photo) ) {
                $this->thumbnail_id = $term_photo;
                $current_img = apply_filters('lastudio-kit/swatches/get_attribute_thumbnail_src', wp_get_attachment_image_url($this->thumbnail_id, $this->size), $this->thumbnail_id, $this->size, $this);
                if ( $current_img ) {
                    $this->thumbnail_src = $current_img;
                }
            }
        }
        elseif ( $type == 'color' ) {
            $term_color = get_term_meta( $this->term_id, '_lakit_swatch_color', true );
            $term_color2 = get_term_meta( $this->term_id, '_lakit_swatch_color2', true );
            $this->color = !empty($term_color) ? $term_color : '';
            $this->color2 = !empty($term_color2) ? $term_color2 : '';
        }
        else{
            $this->layout = 'only_label';
        }
    }

    public function get_output( $product_url = '#', $data_thumb = '', $placeholder = true, $placeholder_src = 'default' ) {

        $anchor_class = 'swatch-anchor';
        $term_label = $this->term_label;
        $inline_style = '--lakit-swatch--width:' . $this->width . 'px;';
        $inline_style .= '--lakit-swatch--height:' . $this->height . 'px;';

        if ( $this->type == 'photo' || $this->type == 'image' ) {
            if(!empty($this->thumbnail_src)){
                $inline_style .= sprintf('--lakit-swatch--url: url(%1$s);', $this->thumbnail_src);
            }
        }
        elseif ( $this->type == 'color' ) {
            if(!empty($this->color2)){
                $anchor_class .= ' has-gradient';
                $inline_style .= sprintf('--lakit-swatch--color-2: %1$s;', $this->color2);
            }
            if(!empty($this->color)){
                $inline_style .= sprintf('--lakit-swatch--color-1: %1$s;', $this->color);
            }
        }
        elseif ( $placeholder ) {
            if ( $placeholder_src == 'default' ) {
                $src = WC()->instance()->plugin_url() . '/assets/images/placeholder.png';
            } else {
                $src = $placeholder_src;
            }
            $inline_style .= sprintf('--lakit-swatch--url: url(%1$s)', $src);
        }

        $picker = sprintf(
            '<span style="%1$s" class="%2$s"><span>%3$s</span></span>',
            esc_attr( $inline_style ),
            esc_attr( $anchor_class ),
            esc_html( $term_label )
        );

        $picker .= sprintf(
            '<span class="swatch-anchor-label">%s</span>',
            esc_attr( $term_label )
        );

        $html_class_wrap = array('select-option', 'swatch-wrapper', 'lakit-hint', 'lakit-hint--top');
        $html_class_wrap[] = 'lakit-swatch-item-layout-' . $this->layout;
        $html_class_wrap[] = 'lakit-swatch-item-style-' . $this->style;
        $html_class_wrap[] = 'lakit-swatch-item-type-' . $this->type;

        if( $this->type == 'none' || $this->layout == 'only_label' ) {
            $html_class_wrap[] = 'swatch-only-label';
        }
        if( $this->selected ) {
            $html_class_wrap[] = 'selected';
        }

        $html_output = '<div data-thumb="'.esc_attr($data_thumb).'" class="' . esc_attr(implode(' ', $html_class_wrap )) . '" data-attribute="' . esc_attr($this->taxonomy_slug) . '" data-hint="'.esc_html($this->term_label).'" data-value="' . esc_attr( $this->term_slug ) . '">';
        $html_output .= apply_filters( 'lastudio-kit/swatches/picker_output', $picker, $this );
        $html_output .= '</div>';

        return $html_output;
    }
}