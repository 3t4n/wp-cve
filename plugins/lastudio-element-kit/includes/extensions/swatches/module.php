<?php

namespace LaStudioKitExtensions\Swatches;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

use LaStudioKitExtensions\Module_Base;
use LaStudioKitExtensions\Swatches\Classes\Configuration;
use LaStudioKitExtensions\Swatches\Classes\Product_Term;
use LaStudioKitExtensions\Swatches\Classes\Swatch_Term;

class Module extends Module_Base {

    public function __construct()
    {
        $this->init_term_metaboxes();
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue'], 99 );
        add_filter('woocommerce_product_data_tabs', [$this, 'add_swatches_to_product_tab']);
        add_action('woocommerce_product_data_panels', [$this, 'add_swatches_to_product_tab_panel']);
        add_action('woocommerce_process_product_meta', [$this, 'save_metabox'], 10, 2);
        add_action('lastudio-kit/ajax/register_actions', [ $this, 'register_ajax_actions' ] );
        add_filter('woocommerce_dropdown_variation_attribute_options_html', [ $this, 'override_dropdown_attributes' ], 101, 2 );
        add_action('wp_enqueue_scripts', [$this, 'frontend_enqueue'], 99 );
        add_filter('woocommerce_ajax_variation_threshold', [$this, 'increase_ajax_variation_threshold'], 30);
        add_filter('woocommerce_ajax_variation_threshold_in_list', [$this, 'increase_ajax_variation_threshold_in_list'], 30);
        add_filter('woocommerce_post_class', [$this, 'post_class'], 10, 2);

        add_action('woocommerce_product_after_variable_attributes', [ $this, 'render_variation_gallery' ], 20, 3);
        add_action('woocommerce_save_product_variation', [ $this, 'save_gallery_variation' ], 20, 2);
        add_filter('woocommerce_available_variation', [ $this, 'add_gallery_to_json' ], 10, 3);
        add_action('template_redirect', [ $this, 'flush_all_gallery_cache' ] );


        add_filter('lastudio-kit/products/loop/product-attribute', [ $this, 'render_swatches_in_shortcode' ], 10, 3);

        add_action('edit_attachment', [ $this, 'save_360_and_video_metabox' ] );
        add_filter('attachment_fields_to_edit', [ $this, 'render_360_and_video_metabox' ], 5, 2);
        add_filter('woocommerce_single_product_image_thumbnail_html', [ $this, 'render_360_and_video_frontend' ], 20, 2);
    }

    public static function is_active(){
        if( lastudio_kit()->get_theme_support('lakit-swatches') && class_exists( 'WooCommerce' ) && 'yes' !== lastudio_kit_settings()->get('swatches__is_disable', '')){
            return true;
        }
        return false;
    }

    public function frontend_enqueue(){
        wp_enqueue_script(
            'lastudio-kit-swatches-frontend',
            lastudio_kit()->plugin_url('includes/extensions/swatches/assets/js/swatches.js'),
            array( 'jquery' ),
            lastudio_kit()->get_version(true),
            true
        );
    }


    public function admin_enqueue(){
        $screen = get_current_screen();
        if(!empty($screen)){
            if( in_array($screen->base, ['term', 'edit-tags', 'post']) && ($screen->id == 'product' || false !== strpos($screen->id, 'edit-pa_')) ) {

                wp_enqueue_style(
                    'lastudio-kit-swatches-css',
                    lastudio_kit()->plugin_url('includes/extensions/swatches/assets/css/swatches-admin.css'),
                    null,
                    lastudio_kit()->get_version(true)
                );

                if($screen->base == 'post' && $screen->id == 'product'){
                    $module_data = lastudio_kit()->module_loader->get_included_module_data( 'cherry-x-vue-ui.php' );
                    $ui          = new \CX_Vue_UI( $module_data );
                    $ui->enqueue_assets();
                    add_action( 'admin_footer', array( $this, 'add_page_template' ) );

                    wp_enqueue_script(
                        'lastudio-kit-swatches-list',
                        lastudio_kit()->plugin_url('includes/extensions/swatches/assets/js/swatches-admin.js'),
                        array( 'jquery' ),
                        lastudio_kit()->get_version(true),
                        true
                    );

                    wp_localize_script(
                        'lastudio-kit-swatches-list',
                        'LaStudioKitSwatchesListConfig',
                        array(
                            'controlData' => $this->get_control_data_for_js(),
                            'ajaxNonce' => lastudio_kit()->ajax_manager->create_nonce(),
                            'ajaxUrl' => admin_url('admin-ajax.php'),
                            'currentID' => get_the_ID()
                        )
                    );
                }
            }
        }
    }

    public function init_term_metaboxes(){

        $fields = [
            '_lakit_swatch_type' => array(
                'type'        => 'select',
                'title'       => esc_html__( 'Swatch Type', 'lastudio-kit' ),
                'value'       => '',
                'options'     => array(
                    '' => esc_html__('None', 'lastudio-kit'),
                    'color'  => esc_html__('Color Swatch', 'lastudio-kit'),
                    'photo'  => esc_html__('Photo Swatch', 'lastudio-kit'),
                ),
            ),
            '_lakit_swatch_color' => array(
                'type'        => 'colorpicker',
                'title'       => esc_html__( 'Color', 'lastudio-kit' ),
                'conditions'  => array(
                    '_lakit_swatch_type' => 'color'
                )
            ),
            '_lakit_swatch_color2' => array(
                'type'        => 'colorpicker',
                'title'       => esc_html__( 'Color 2', 'lastudio-kit' ),
                'description' => esc_html__( 'Use for gradients', 'lastudio-kit' ),
                'conditions'  => array(
                    '_lakit_swatch_type' => 'color'
                )
            ),
            '_lakit_swatch_photo' => array(
                'type'               => 'media',
                'title'              => esc_html__( 'Photo', 'lastudio-kit' ),
                'upload_button_text' => esc_html__( 'Set Photo', 'lastudio-kit' ),
                'library_type'       => 'image',
                'multi_upload'       => false,
                'value_format'       => 'id',
                'conditions'  => array(
                    '_lakit_swatch_type' => 'photo'
                )
            ),
        ];

        $attribute_taxonomies = wc_get_attribute_taxonomies();

        if(!empty($attribute_taxonomies)){
            foreach ($attribute_taxonomies as $tax){
                lastudio_kit_term_meta()->add_options( array (
                    'tax'        => 'pa_' . $tax->attribute_name,
                    'id'         => 'lakit-swatches-term-metaboxes',
                    'fields'     => $fields,
                    'admin_columns' => [
                        'lakit_swatch_type' => array(
                            'label'    => __( 'Swatch Type', 'lastudio-kit' ),
                            'callback' => array( $this, 'show_column_swatches_type' ),
                            'position' => 1,
                        ),
                    ]
                ) );
            }
        }
    }

    public function show_column_swatches_type( $column, $term_id ){
        $swatch_type = get_term_meta( $term_id, '_lakit_swatch_type', true );
        if($swatch_type == 'color'){
            $color1 = get_term_meta( $term_id, '_lakit_swatch_color', true );
            $color2 = get_term_meta( $term_id, '_lakit_swatch_color2', true );
            $styles = [];
            $styles[] = '--lakit-swatch--color-1: '. $color1;
            $styles[] = '--lakit-swatch--color-2: '. $color2;
            $html = sprintf('<span class="type-color %2$s" style="%1$s"></span>', esc_attr( join(';', $styles)), !empty($color2) ? 'has-gradient' : '');
        }
        elseif ($swatch_type == 'photo'){
            $photo_id = get_term_meta( $term_id, '_lakit_swatch_photo', true );
            $html = sprintf('<span class="type-photo">%1$s</span>', wp_get_attachment_image($photo_id));
        }
        else{
            $html = sprintf('<span class="type-none">%1$s</span>', __('None', 'lastudio-kit'));
        }
        return $html;
    }

    public function add_swatches_to_product_tab( $tabs ){
        $tabs['lastudiokit_swatches'] = array(
            'label'    => __( 'LaStudioKit Swatches', 'lastudio-kit' ),
            'target'   => 'lastudiokit_swatches',
            'class'    => array('show_if_variable'),
            'priority' => 55
        );
        return $tabs;
    }

    public function add_swatches_to_product_tab_panel(){
        ?><div id="lastudiokit_swatches" class="panel lastudiokit_swatches woocommerce_options_panel wc-metaboxes-wrapper" style="display: none;"><div id="lastudiokit_swatches_list"></div></div><?php
    }

    public function add_page_template(){
        ob_start();
        include 'view/metabox.php';
        $content = ob_get_clean();
        printf( '<script type="text/x-template" id="lastudiokit-swatches-list">%s</script>', $content );
    }

    public function save_metabox( $post_id, $post ){
        if( !empty( $_POST['lakit_swatch_data'] ) ){
            $swatch_type = 'default';
            foreach ($_POST['lakit_swatch_data'] as $datum){
                if( !in_array($datum['type'], ['default', 'none', 'radio']) ){
                    $swatch_type = 'picker';
                }
            }
            update_post_meta( $post_id, 'lakit_swatch_data', $_POST['lakit_swatch_data'] );
            update_post_meta( $post_id, 'lakit_swatch_type', $swatch_type );
        }
    }

    /**
     * @param \LaStudio_Kit_Ajax_Manager $ajax_manager
     */
    public function register_ajax_actions( $ajax_manager ){
        $ajax_manager->register_ajax_action( 'swatches_get_variation_attributes', [ $this, 'ajax_swatches_get_variation_attributes' ] );
        $ajax_manager->register_ajax_action( 'swatches_get_product_variations', [ $this, 'ajax_get_product_variations' ] );
    }

    /**
     * @param $request
     * @return array
     */
    public function ajax_swatches_get_variation_attributes( $request ){
        $variation_attribute_found = true;
        $response = [];

        $product_id = isset($request['product_id']) ? $request['product_id'] : false;
        $product_type_array = array('variable', 'variable-subscription');

        $product = wc_get_product($product_id);

        if( !$product ){
            return $response;
        }

        if ( !in_array( $product->get_type(), $product_type_array ) ) {
            $variation_attribute_found = false;
        }
        $attributes_name = wp_list_pluck( wc_get_attribute_taxonomies(),'attribute_label' ,'attribute_name' );
        $product_swatches_data = $product->get_meta('lakit_swatch_data', true);
        if(!is_array($product_swatches_data)){
            $product_swatches_data = [];
        }

        if($variation_attribute_found){
            $attributes = $product->get_variation_attributes();
            if(!empty($attributes)){
                foreach ( $attributes as $taxonomy_key => $attribute_list ){
                    if(empty($attribute_list)){
                        continue;
                    }
                    $attribute_terms = array();
                    $current_is_tax = taxonomy_exists($taxonomy_key);
                    $key_attr = md5( str_replace( '-', '_', sanitize_title( $taxonomy_key ) ) );
                    $tax_title = $taxonomy_key;
                    if($current_is_tax){
                        $tax_title = $attributes_name[str_replace('pa_', '', $taxonomy_key)];
                        $terms = get_terms( $taxonomy_key, array('hide_empty' => false) );
                        foreach( $terms as $term ){
                            if ( in_array( $term->slug, $attribute_list ) ) {
                                $sub_key = md5( $term->slug );
                                $photo_id = !empty($product_swatches_data[$key_attr]['attributes'][$sub_key]['photo']) ? $product_swatches_data[$key_attr]['attributes'][$sub_key]['photo'] : '';
                                $photo_url = wp_get_attachment_image_url($photo_id);

                                $attribute_terms[$sub_key] = [
                                    '_id' => $sub_key,
                                    'name'=> $term->name,
                                    'type'=> !empty($product_swatches_data[$key_attr]['attributes'][$sub_key]['type']) ? $product_swatches_data[$key_attr]['attributes'][$sub_key]['type'] : 'color',
                                    'color'=> !empty($product_swatches_data[$key_attr]['attributes'][$sub_key]['color']) ? $product_swatches_data[$key_attr]['attributes'][$sub_key]['color'] : '',
                                    'color2'=> !empty($product_swatches_data[$key_attr]['attributes'][$sub_key]['color2']) ? $product_swatches_data[$key_attr]['attributes'][$sub_key]['color2'] : '',
                                    'photo'=> $photo_id,
                                    'photo_url'=> $photo_url,
                                ];
                            }
                        }
                    }
                    else{
                        foreach ( $attribute_list as $term ) {
                            $sub_key = md5( sanitize_title( strtolower( $term ) ) );
                            $photo_id = !empty($product_swatches_data[$key_attr]['attributes'][$sub_key]['photo']) ? $product_swatches_data[$key_attr]['attributes'][$sub_key]['photo'] : '';
                            $photo_url = wp_get_attachment_image_url($photo_id);
                            $attribute_terms[$sub_key] = [
                                '_id' => $sub_key,
                                'name'=> $term,
                                'type'=> !empty($product_swatches_data[$key_attr]['attributes'][$sub_key]['type']) ? $product_swatches_data[$key_attr]['attributes'][$sub_key]['type'] : 'color',
                                'color'=> !empty($product_swatches_data[$key_attr]['attributes'][$sub_key]['color']) ? $product_swatches_data[$key_attr]['attributes'][$sub_key]['color'] : '',
                                'color2'=> !empty($product_swatches_data[$key_attr]['attributes'][$sub_key]['color2']) ? $product_swatches_data[$key_attr]['attributes'][$sub_key]['color2'] : '',
                                'photo'=> $photo_id,
                                'photo_url'=> $photo_url,
                            ];
                        }
                    }
                    if(empty($attribute_terms)){
                        continue;
                    }
                    $response[$key_attr] = [
                        '_id' => $key_attr,
                        'name' => $tax_title,
                        'is_custom' => !$current_is_tax,
                        'type' => !empty($product_swatches_data[$key_attr]['type']) ? $product_swatches_data[$key_attr]['type'] : 'default',
                        'swatch_size' => !empty($product_swatches_data[$key_attr]['swatch_size']) ? $product_swatches_data[$key_attr]['swatch_size'] : 'default',
                        'layout' => !empty($product_swatches_data[$key_attr]['layout']) ? $product_swatches_data[$key_attr]['layout'] : 'default',
                        'style' => !empty($product_swatches_data[$key_attr]['style']) ? $product_swatches_data[$key_attr]['style'] : 'default',
                        'attributes' => array_values($attribute_terms),
                    ];
                }
            }
        }
        return array_values($response);
    }

    public function get_control_data_for_js(){
        return [
            'type' => [
                'value' => 'default',
                'options' => [
                    [
                        'label' => esc_html__('None', 'lastudio-kit'),
                        'value' => 'none'
                    ],
                    [
                        'label' => esc_html__('Default', 'lastudio-kit'),
                        'value' => 'default'
                    ],
                    [
                        'label' => esc_html__('Taxonomy Colors and Images', 'lastudio-kit'),
                        'value' => 'term_options'
                    ],
                    [
                        'label' => esc_html__('Custom Colors and Images', 'lastudio-kit'),
                        'value' => 'product_custom'
                    ],
                    [
                        'label' => esc_html__('Radio Button', 'lastudio-kit'),
                        'value' => 'radio'
                    ],
                ]
            ],
            'type_custom' => [
                'value' => 'default',
                'options' => [
                    [
                        'label' => esc_html__('None', 'lastudio-kit'),
                        'value' => 'none'
                    ],
                    [
                        'label' => esc_html__('Default', 'lastudio-kit'),
                        'value' => 'default'
                    ],
                    [
                        'label' => esc_html__('Custom Colors and Images', 'lastudio-kit'),
                        'value' => 'product_custom'
                    ],
                    [
                        'label' => esc_html__('Radio Button', 'lastudio-kit'),
                        'value' => 'radio'
                    ],
                ]
            ],
            'swatch_size' => [
                'value' => 'default',
                'options' => [
                    [
                        'label' => esc_html__('Default', 'lastudio-kit'),
                        'value' => 'default'
                    ],
                    [
                        'label' => esc_html__('Thumbnail', 'lastudio-kit'),
                        'value' => 'thumbnail'
                    ],
                    [
                        'label' => esc_html__('Product thumbnails', 'lastudio-kit'),
                        'value' => 'woocommerce_gallery_thumbnail'
                    ],
                    [
                        'label' => esc_html__('Catalog images', 'lastudio-kit'),
                        'value' => 'woocommerce_thumbnail'
                    ],
                ]
            ],
            'layout' => [
                'value' => 'default',
                'options' => [
                    [
                        'label' => esc_html__('Default', 'lastudio-kit'),
                        'value' => 'default'
                    ],
                    [
                        'label' => esc_html__('Show Only Label', 'lastudio-kit'),
                        'value' => 'only_label'
                    ],
                ]
            ],
            'style' => [
                'value' => 'default',
                'options' => [
                    [
                        'label' => esc_html__('Default', 'lastudio-kit'),
                        'value' => 'default'
                    ],
                    [
                        'label' => esc_html__('Circle', 'lastudio-kit'),
                        'value' => 'circle'
                    ],
                    [
                        'label' => esc_html__('Square', 'lastudio-kit'),
                        'value' => 'square'
                    ],
                    [
                        'label' => esc_html__('Rounder', 'lastudio-kit'),
                        'value' => 'rounder'
                    ],
                ]
            ],
            'subtype' => [
                'value' => 'color',
                'options' => [
                    [
                        'label' => esc_html__('Color', 'lastudio-kit'),
                        'value' => 'color'
                    ],
                    [
                        'label' => esc_html__('Image', 'lastudio-kit'),
                        'value' => 'photo'
                    ],
                ]
            ]
        ];
    }

    public function override_dropdown_attributes( $html, $args ){
        $new_html = '';

        $args = wp_parse_args( $args, array(
            'options'           => false,
            'attribute'         => false,
            'product'           => false,
            'selected'          => false,
            'name'              => '',
            'id'                => '',
            'class'             => '',
            'show_option_none'  => apply_filters('lastudio-kit/swatches/args/show_option_none', __( 'Choose an option', 'woocommerce' ))
        ) );

        // Get selected value.
        if ( false === $args['selected'] && $args['attribute'] && $args['product'] instanceof \WC_Product ) {
            $selected_key     = 'attribute_' . sanitize_title( $args['attribute'] );
            $args['selected'] = isset( $_REQUEST[ $selected_key ] ) ? wc_clean( urldecode( wp_unslash( $_REQUEST[ $selected_key ] ) ) ) : $args['product']->get_variation_default_attribute( $args['attribute'] ); // WPCS: input var ok, CSRF ok, sanitization ok.
        }

        $options            = $args['options'];
        $product            = $args['product'];
        $attribute          = $args['attribute'];
        $name               = $args['name'] ?? 'attribute_' . sanitize_title( $attribute );
        $id                 = $args['id'] ?? sanitize_title( $attribute );

        $swatch_config_object = new Configuration( $product, $attribute );

        if ( $swatch_config_object->get_swatch_type() == 'radio' ) {
            do_action('lastudio-kit/swatches/before_render_picker', $swatch_config_object);
            $new_html .= '<div id="picker_' . esc_attr($id) . '" class="radio-select select swatch-control">';
            $args['hide'] = true;
            $new_html .= $this->render_dropdown_variation($args);
            $new_html .= $this->render_radio_variation($args);
            $new_html .= '</div>';
        }

        elseif ( $swatch_config_object->get_swatch_type() != 'none' ) {

            $new_html .= '<div class="attribute_' . $id . '_picker_label swatch-label"></div>';

            if ($swatch_config_object->get_swatch_layout() == 'label_above') {
                $new_html .= '<div class="attribute_' . $id . '_picker_label swatch-label"></div>';
            }

            do_action('lastudio-kit/swatches/before_render_picker', $swatch_config_object);

            $new_html .= '<div id="picker_' . esc_attr($id) . '" class="select swatch-control">';
            $args['hide'] = true;
            $new_html .= $this->render_dropdown_variation($args);

            if (!empty($options)) {

                if ($product && taxonomy_exists($attribute)) {
                    // Get terms if this is a taxonomy - ordered. We need the names too.
                    $terms = wc_get_product_terms($product->get_id(), $attribute, array('fields' => 'all'));

                    foreach ($terms as $term) {
                        if (in_array($term->slug, $options)) {
                            switch($swatch_config_object->get_swatch_type()){
                                case 'term_options':
                                    $swatch_term = new Swatch_Term($swatch_config_object, $term->term_id, $attribute, $args['selected'] == $term->slug, $swatch_config_object->get_swatch_size());
                                    break;
                                case 'product_custom':
                                    $swatch_term = new Product_Term($swatch_config_object, $term->term_id, $attribute, $args['selected'] == $term->slug);
                                    break;
                            }
                            if( !empty($swatch_term) && $swatch_term instanceof Swatch_Term ) {
                                do_action('lastudio-kit/swatches/before_render_picker_item', $swatch_term);
                                $new_html .= $swatch_term->get_output();
                                do_action('lastudio-kit/swatches/after_render_picker_item', $swatch_term);
                            }
                        }
                    }

                }
                else {
                    $swatch_config_object_custom = new Configuration( $product, $attribute );
                    $swatch_config_object_custom->set_config('layout', 'only_label');
                    $swatch_config_object_custom->set_config('style', 'square');
                    foreach ($options as $option) {
                        $selected = sanitize_title($args['selected']) === $args['selected'] ? selected($args['selected'], sanitize_title($option), false) : selected($args['selected'], $option, false);
                        $swatch_term = new Product_Term($swatch_config_object_custom, $option, $name, $selected);

                        do_action('lastudio-kit/swatches/before_render_picker_item', $swatch_term);
                        $new_html .= $swatch_term->get_output();
                        do_action('lastudio-kit/swatches/after_render_picker_item', $swatch_term);
                    }
                }
            }
            $new_html .= '</div>';

            if ($swatch_config_object->get_swatch_layout() == 'label_below') {
                $new_html .= '<div class="attribute_' . $id . '_picker_label swatch-label">&nbsp;</div>';
            }
        }
        else {
            $args['hide'] = false;
            $args['class'] .= (!empty( $args['class'] ) ? ' ' : '') . 'wc-default-select';
            $new_html .= $this->render_dropdown_variation($args);
        }

        return $new_html;
    }

    private function render_dropdown_variation( $args ) {
        $options                = $args['options'];
        $product                = $args['product'];
        $attribute              = $args['attribute'];
        $name                   = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
        $id                     = $args['id'] ? $args['id'] : sanitize_title( $attribute );
        $class                  = $args['class'];
        $show_option_none       = (bool) $args['show_option_none'];

        if ( empty( $options ) && !empty( $product ) && !empty( $attribute ) ) {
            $attributes = $product->get_variation_attributes();
            $options = $attributes[$attribute];
        }

        $html  = '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';

        if ( $show_option_none ) {
            $html .= '<option value="">' . esc_html( $args['show_option_none'] ) . '</option>';
        }

        if ( ! empty( $options ) ) {
            if ( $product && taxonomy_exists( $attribute ) ) {
                // Get terms if this is a taxonomy - ordered. We need the names too.
                $terms = wc_get_product_terms( $product->get_id(), $attribute, array(
                    'fields' => 'all',
                ) );

                foreach ( $terms as $term ) {
                    if ( in_array( $term->slug, $options, true ) ) {
                        $html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
                    }
                }
            } else {
                foreach ( $options as $option ) {
                    // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
                    $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
                    $html    .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
                }
            }
        }

        $html .= '</select>';

        return $html;
    }

    private function render_radio_variation( $args ) {
        $options        = $args['options'];
        $product        = $args['product'];
        $attribute      = $args['attribute'];
        $name           = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute ) . '_' . uniqid();
        $id             = $args['id'] ? $args['id'] : sanitize_title( $attribute ) . uniqid();
        $class          = $args['class'];

        if ( empty( $options ) && !empty( $product ) && !empty( $attribute ) ) {
            $attributes = $product->get_variation_attributes();
            $options = $attributes[$attribute];
        }

        $html = '<ul id="radio_select_' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">';

        if ( !empty( $options ) ) {
            if ( $product && taxonomy_exists( $attribute ) ) {
                // Get terms if this is a taxonomy - ordered. We need the names too.
                $terms = wc_get_product_terms( $product->get_id(), $attribute, array('fields' => 'all') );

                foreach ( $terms as $term ) {
                    if ( in_array( $term->slug, $options ) ) {
                        $html .= '<li>';
                        $html .= '<input class="radio-option" name="' . esc_attr( $name ) . '" id="radio_' . esc_attr( $id ) . '_' . esc_attr( $term->slug ) . '" type="radio" data-value="' . esc_attr( $term->slug ) . '" value="' . esc_attr( $term->slug ) . '" ' . checked( sanitize_title( $args['selected'] ), $term->slug, false ) . ' /><label for="radio_' . esc_attr( $id ) . '_' . esc_attr( $term->slug ) . '">' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</label>';
                        $html .= '</li>';
                    }
                }
            } else {
                foreach ( $options as $option ) {
                    // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
                    $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? checked( $args['selected'], sanitize_title( $option ), false ) : checked( $args['selected'], $option, false );
                    $html .= '<li>';
                    $html .= '<input class="radio-option" name="' . esc_attr( $name ) . '" id="radio_' . esc_attr( $id ) . '_' . esc_attr( $option ) . '" type="radio" data-value="' . esc_attr( $option ) . '" value="' . esc_attr( $option ) . '" ' . $selected . ' /><label for="radio_' . esc_attr( $id ) . '_' . esc_attr( $option ) . '">' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</label>';
                    $html .= '</li>';
                }
            }
        }

        $html .= '</ul>';

        return $html;
    }

    public function render_variation_gallery( $loop, $variation_data, $variation ){
        $variation_id = $variation->ID;
        $attachments = get_post_meta($variation_id, '_product_image_gallery', true);
        $attachmentsExp = array_filter(explode(',', $attachments));
        $image_ids = [];
?>
        <div class="lakit-gallery-for-variation">
            <div class="lakit_variation_thumb lakit_variation_thumb--<?php echo $variation_id; ?>">
                <h4><?php esc_html_e('Additional Images', 'lastudio-kit'); ?></h4>
                <ul class="lakit_variation_thumbs"><?php if (!empty($attachmentsExp)) {
                        foreach ($attachmentsExp as $id) { $image_ids[] = $id; ?><li class="image" data-attachment_id="<?php echo $id; ?>">
                            <a href="#" class="delete" title="Delete image"><span style="background-image: url(<?php echo esc_url(wp_get_attachment_image_url($id, 'woocommerce_thumbnail')) ?>)"></span></a>
                            </li><?php } } ?></ul>
                <input type="hidden" class="lakit_variation_image_gallery" name="lakit_variation_image_gallery[<?php echo $variation_id; ?>]" value="<?php echo $attachments; ?>"/>
                <a href="#" class="lakit_swatches--manage_variation_thumbs button"><?php esc_html_e('Add Additional Images', 'lastudio'); ?></a>
            </div>
        </div>
        <?php
    }

    public function save_gallery_variation( $variation_id, $i ){
        $this->flush_variation_gallery_cache( $variation_id, false);
        if ( isset( $_POST['lakit_variation_image_gallery'][$variation_id] ) ) {
            update_post_meta($variation_id, '_product_image_gallery', $_POST['lakit_variation_image_gallery'][$variation_id]);
        }
    }

    private function get_cache_key( $id, $type ){
        $cache_key = false;
        if( $type == 'all-images' ){
            $id = apply_filters('wpml_object_id', $id, 'product_variation', true);
            $cache_key = sprintf('lakit_swc_ids_%d', $id);
        }
        elseif ( $type == 'sizes' ){
            $cache_key = sprintf('lakit_swc_sizes_%d', $id);
        }
        elseif ( $type == 'variation' ){
            $cache_key = sprintf('lakit_swc_variation_%d', $id);
        }
        return $cache_key;
    }

    /**
     * @param \WC_Product $product
     * @return array
     */
    private function get_available_variations( $product ){
        $transient_name = $this->get_cache_key( $product->get_id(), 'variation' );
        $transient_data = get_transient($transient_name);
        if (!empty($transient_data)){
            return $transient_data;
        }
        $available_variations = array();
        //Get the children all in one call.
        //This will prime the WP_Post cache so calls to get_child are much faster.
        $args = array(
            'post_parent' => $product->get_id(),
            'post_type' => 'product_variation',
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'post_status' => 'publish',
            'numberposts' => -1,
            'no_found_rows' => true
        );
        $children = get_posts( $args );
        if(empty($children)){
            return $available_variations;
        }
        foreach ( $children as $child ) {
            $variation = wc_get_product( $child );
            $variation_id = $variation->get_id();
            $variation_is_in_stock = $variation->is_in_stock();
            // Hide out of stock variations if 'Hide out of stock items from the catalog' is checked
            if ( empty( $variation_id ) || ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) && !$variation_is_in_stock ) ) {
                continue;
            }
            // Filter 'woocommerce_hide_invisible_variations' to optionally hide invisible variations (disabled variations and variations with empty price)
            if ( apply_filters( 'woocommerce_hide_invisible_variations', false, $product->get_id(), $variation ) && !$variation->variation_is_visible() ) {
                continue;
            }

            $available_variations[] = array(
                'variation_id' => $variation_id,
                'variation_is_active' => $variation->variation_is_active(),
                'attributes' => $variation->get_variation_attributes(),
            );
        }
        set_transient( $transient_name, $available_variations, 10 * HOUR_IN_SECONDS );
        return $available_variations;
    }

    public function ajax_get_product_variations( $request ){
        $product_id = isset($request['product_id']) ? $request['product_id'] : false;
        $product = wc_get_product($product_id);
        if( !$product ){
            return [];
        }
        return $this->get_available_variations( $product );
    }

    private function get_all_variation_image_ids( $id ){
        $cache_key = $this->get_cache_key($id, 'all-images');
        $all_images = get_transient($cache_key);
        if ( empty($all_images) ) {
            $all_images = array();
            $show_gallery = false;
            $has_featured_image = has_post_thumbnail( $id );
            $product = wc_get_product($id);
            $post_type = $product->get_type();
            $parent_id = $product->get_parent_id( $product );

            // Main Image
            if ( $has_featured_image ) {
                $all_images['featured'] = get_post_thumbnail_id( $id );
            }
            else {
                if( $parent_id > 0 ) {
                    if ( has_post_thumbnail( $parent_id ) ) {
                        $all_images['featured'] = get_post_thumbnail_id( $parent_id );
                    }
                    else {
                        $all_images[] = 'placeholder';
                    }
                    $show_gallery = true;
                }
                else {
                    $all_images[] = 'placeholder';
                }
            }

            // Gallery Attachments

            if ( $post_type == 'variation' ) {
                $wt_attachments = $product->get_gallery_image_ids();
                if( !empty( $wt_attachments ) ) {
                    $all_images = array_merge($all_images, $wt_attachments);
                    // if there was no featured image, set the first woothumbs attachment as the featured image
                    if( !$has_featured_image ) {
                        $all_images['featured'] = $all_images[0];
                        unset( $all_images[0] );
                        $show_gallery = false;
                    }
                }
                else{
                    $show_gallery = apply_filters('lastudio-kit/swatches/get_variation_gallery_from_parent_if_missing', true);
                }
            }
            // Gallery Attachments
            if ( $post_type == 'product' || $show_gallery ) {
                $id = !empty( $parent_id ) ? $parent_id : $id;
                $gallery_product = wc_get_product( $id );
                $attach_ids = $gallery_product->get_gallery_image_ids();
                if ( !empty( $attach_ids ) ) {
                    $all_images = array_merge($all_images, $attach_ids);
                }
            }
            $all_images = array_map( function ( $media_id ) {
                return apply_filters('wpml_object_id', $media_id, 'attachment', true);
            }, $all_images );
            $all_images = apply_filters( 'lastudio-kit/swatches/get_variation_image_ids_before_transient', $all_images, $id );
            set_transient( $cache_key, $all_images, 10 * HOUR_IN_SECONDS );
        }
        return apply_filters( 'lastudio-kit/swatches/get_variation_image_ids', $all_images, $id );
    }

    private function get_all_variation_image_sizes( $variation_id ){
        $image_ids = $this->get_all_variation_image_ids( absint($variation_id) );
        $images = array();
        if ( !empty($image_ids) ) {
            foreach ($image_ids as $image_id):
                $transient_name = $this->get_cache_key( $image_id, "sizes" );
                $image_sizes = get_transient( $transient_name );
                if ( empty($image_sizes) ) {
                    $image_sizes = false;
                    if ($image_id == 'placeholder') {
                        $image_sizes = array(
                            'large' => array( wc_placeholder_img_src() ),
                            'single' => array( wc_placeholder_img_src() ),
                            'thumb' => array( wc_placeholder_img_src() ),
                            'alt' => '',
                            'title' => ''
                        );
                    }
                    else {
                        if (!array_key_exists($image_id, $images)) {
                            $large = wp_get_attachment_image_src( $image_id, 'full' );
                            $single = wp_get_attachment_image_src( $image_id, 'woocommerce_single' );
                            $thumb = wp_get_attachment_image_src( $image_id, 'woocommerce_thumbnail' );
                            $srcset = wp_get_attachment_image_srcset( $image_id, 'woocommerce_single' );
                            $sizes = wp_get_attachment_image_sizes( $image_id, 'woocommerce_single' );
                            $thumb_srcset = wp_get_attachment_image_srcset( $image_id, 'woocommerce_thumbnail' );
                            $thumb_sizes = wp_get_attachment_image_sizes( $image_id, 'woocommerce_thumbnail' );

                            $sprite = get_post_meta( $image_id, 'lakit_attach__sprite', true );
                            $sprite_source = wp_get_attachment_image_url($sprite, 'full');

                            $image_sizes = array(
                                'large' => $large,
                                'single' => $single,
                                'thumb' => $thumb,
                                'alt' => get_post_field( 'post_title', $image_id ),
                                'title' => get_post_field( 'post_title', $image_id ),
                                'caption' => get_post_field( 'post_excerpt', $image_id ),
                                'srcset' => $srcset ?? "",
                                'sizes' => $sizes ?? "",
                                'thumb_srcset' => $thumb_srcset ?? "",
                                'thumb_sizes' => $thumb_sizes ?? "",
                                'lakit_extra' => [
                                    'type'          => get_post_meta( $image_id, 'lakit_attach_type', true),
                                    'videoUrl'      => get_post_meta( $image_id, 'lakit_attach__videourl', true ),
                                    'spriteSource'  => $sprite_source,
                                    'totalFrames'   => get_post_meta( $image_id, 'lakit_attach__sprite_tf', true ),
                                    'framesPerRow'  => get_post_meta( $image_id, 'lakit_attach__sprite_fpr', true ),
                                ]
                            );
                        }
                    }
                    $image_sizes = apply_filters( 'lastudio-kit/swatches/get_variation_image_sizes_before_transient', $image_sizes );
                    set_transient( $transient_name, $image_sizes, 10 * HOUR_IN_SECONDS );
                }

                if( $image_sizes ){
                    $images[$image_id] = $image_sizes;
                }

            endforeach;
        }
        return apply_filters( 'lastudio-kit/swatches/get_variation_image_sizes', $images );
    }

    public function add_gallery_to_json( $variation_data, $wc_product_variable, $variation_obj ){
        $images = $this->get_all_variation_image_sizes( $variation_data['variation_id'] );
        $variation_data['lakit_additional_images'] = array_values($images);
        return $variation_data;
    }

    public function flush_variation_gallery_cache( $product_id = 0, $force = false ){
        if($force || isset($_REQUEST['lakit-delete-gallery-caches'])){
            global $wpdb;
            $transients = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->options WHERE `option_name` LIKE '%s'",'%lakit_swc_%'));
            if( $transients ) {
                foreach( $transients as $transient ) {
                    $transient_name = str_replace(array('_transient_timeout_lakit_swc_', '_transient_lakit_swc_'), 'lakit_swc_', $transient->option_name);
                    delete_transient( $transient_name );
                }
            }
        }
        if( $product_id ) {
            $transient_keys = array("all-images", "sizes", "variation");
            foreach ($transient_keys as $n){
                $ts_name = $this->get_cache_key($product_id, $n);
                delete_transient( $ts_name );
            }
        }
    }

    public function flush_all_gallery_cache(){
        if(isset($_GET['lakit-clear-swatches-caches'])){
            $this->flush_variation_gallery_cache(false, true);
            $current_url = add_query_arg(null, null);
            $current_url = remove_query_arg('lakit-clear-swatches-caches', $current_url);
            wp_safe_redirect($current_url);
            exit;
        }
    }

    public function increase_ajax_variation_threshold( $threshold ){
        return lastudio_kit_settings()->get('swatches_threshold', $threshold);
    }

    public function increase_ajax_variation_threshold_in_list( $threshold ){
        return lastudio_kit_settings()->get('swatches_threshold_list', $threshold);
    }

    public function post_class( $classes, $product ){
        $enabled = lastudio_kit_settings()->get('swatches_swatches_variation_form', '');
        if($enabled == 'enabled' && $product->get_type() == 'variable'){
            $classes[] = 'has-variation-form';
        }
        return $classes;
    }

    private function get_product_images_by_variations( $product_variations ){
        $array = array();
        if ( !empty( $product_variations ) ) {
            foreach($product_variations as $product_variation) {
                if($product_variation['variation_is_active'] && $product_variation['variation_is_visible']){
                    $array[$product_variation['variation_id']] = array(
                        'image_id' => $product_variation['image_id'],
                        'attributes' => $product_variation['attributes']
                    );
                }
            }
        }
        return $array;
    }

    private function get_product_variation_image_url_by_attribute($product_variations, $attribute_name, $attribute_value ){
        $attribute_name = 'attribute_' . $attribute_name;
        $image_url = '';
        $image_id = false;
        $_tmp = $this->get_product_images_by_variations($product_variations);
        if(!empty($_tmp)){
            foreach($_tmp as $p_id => $val ){
                if(isset($val['attributes'][$attribute_name]) && $val['attributes'][$attribute_name] == $attribute_value){
                    $image_id = $val['image_id'];
                    break;
                }
            }
        }
        if($image_id){
            return apply_filters('lastudio-kit/swatches/get_product_variation_image_url_by_attribute', wp_get_attachment_image_url($image_id, apply_filters( 'single_product_archive_thumbnail_size', 'woocommerce_thumbnail' )), $image_id);
        }
        return $image_url;
    }

    public function get_variation_image_src( $product_id, $attribute_name, $attribute_value ){
        global $wpdb;
        $meta_attribute_name = 'attribute_' . sanitize_title( $attribute_name );
        $query = $wpdb->prepare(
            "
SELECT rMeta.meta_value as thumbnail_id FROM {$wpdb->postmeta} as rMeta
WHERE rMeta.post_id IN (
    SELECT postmeta.post_id FROM {$wpdb->postmeta} as postmeta
    LEFT JOIN {$wpdb->posts} as posts ON postmeta.post_id=posts.ID
    WHERE postmeta.post_id IN (
        SELECT ID FROM {$wpdb->posts}
        WHERE {$wpdb->posts}.post_parent = %d
        AND {$wpdb->posts}.post_status = 'publish'
        AND {$wpdb->posts}.post_type = 'product_variation'
    )
    AND postmeta.meta_key = %s
    AND postmeta.meta_value = %s
    ORDER BY posts.menu_order ASC, postmeta.post_id ASC
)
AND rMeta.meta_key = '_thumbnail_id'
AND rMeta.meta_value > 0;
			",
            $product_id,
            $meta_attribute_name,
            $attribute_value
        );

        $thumbnail = $wpdb->get_row( $query, ARRAY_N );
        return wp_get_attachment_image_url($thumbnail[0] ?? 0, apply_filters( 'single_product_archive_thumbnail_size', 'woocommerce_thumbnail' ));
    }

    public function render_swatches_item_by_attribute__bk( $product = null, $attr_selected = [] ){
        $output = '';
        if(!$product instanceof \WC_Product){
            return $output;
        }
        if( $product->get_type() != 'variable' || empty($attr_selected) ) {
            return $output;
        }

        $list_threshold = apply_filters( 'woocommerce_ajax_variation_threshold_in_list', 100, $product );

        $get_variations = sizeof( $product->get_children() ) <= $list_threshold;

        $available_variations = $get_variations ? $product->get_available_variations() : false;

        if($available_variations){
            $attributes = $product->get_variation_attributes();
            foreach ( $attributes as $attribute_name => $options ) {
                $_tmp = str_replace('pa_', '', $attribute_name);
                if(!in_array( $_tmp, $attr_selected)){
                    continue;
                }
                $swatch_config_object = new Configuration( $product, $attribute_name );
                if($swatch_config_object->get_swatch_type() != 'none'){
                    if ( !empty( $options ) ) {
                        if ( $product && taxonomy_exists( $attribute_name ) ) {
                            // Get terms if this is a taxonomy - ordered. We need the names too.
                            $terms = wc_get_product_terms( $product->get_id(), $attribute_name, array('fields' => 'all') );

                            $output .= '<div class="lakit-swatch-control">';
                            foreach ( $terms as $term ) {
                                if ( in_array( $term->slug, $options ) ) {
                                    $attribute_link = add_query_arg(
                                        'attribute_' . sanitize_title($attribute_name),
                                        $term->slug,
                                        $product->get_permalink()
                                    );

                                    $image_url = $this->get_product_variation_image_url_by_attribute($available_variations, $attribute_name, $term->slug);
                                    if ( $swatch_config_object->get_swatch_type() == 'term_options' ) {
                                        $swatch_term = new Swatch_Term( $swatch_config_object, $term->term_id, $attribute_name, false, $swatch_config_object->get_swatch_size() );
                                        $output .= $swatch_term->get_output($attribute_link, $image_url);
                                    }
                                    elseif ( $swatch_config_object->get_swatch_type() == 'product_custom' ) {
                                        $swatch_term = new Product_Term( $swatch_config_object, $term->term_id, $attribute_name, false );
                                        $output .= $swatch_term->get_output($attribute_link, $image_url);
                                    }
                                }
                            }
                            $output .= '</div>';
                        }
                    }
                }
            }

        }
        return $output;
    }
    public function render_swatches_item_by_attribute( $product = null, $attr_selected = [] ){
        $output = '';
        if(!$product instanceof \WC_Product){
            return $output;
        }
        if( $product->get_type() != 'variable' || empty($attr_selected) ) {
            return $output;
        }

        $variation_attributes = $product->get_variation_attributes();
        if($variation_attributes){

            foreach ( $variation_attributes as $attribute_name => $options ) {
                $_tmp = str_replace('pa_', '', $attribute_name);
                if(!in_array( $_tmp, $attr_selected)){
                    continue;
                }
                $swatch_config_object = new Configuration( $product, $attribute_name );
                if($swatch_config_object->get_swatch_type() != 'none'){
                    if ( !empty( $options ) ) {
                        if ( $product && taxonomy_exists( $attribute_name ) ) {
                            // Get terms if this is a taxonomy - ordered. We need the names too.
                            $terms = wc_get_product_terms( $product->get_id(), $attribute_name, array('fields' => 'all') );

                            $output .= '<div class="lakit-swatch-control">';
                            foreach ( $terms as $term ) {
                                if ( in_array( $term->slug, $options ) ) {
                                    $attribute_link = add_query_arg(
                                        'attribute_' . sanitize_title($attribute_name),
                                        $term->slug,
                                        $product->get_permalink()
                                    );

                                    $image_url = $this->get_variation_image_src($product->get_id(), $attribute_name, $term->slug);
                                    if ( $swatch_config_object->get_swatch_type() == 'term_options' ) {
                                        $swatch_term = new Swatch_Term( $swatch_config_object, $term->term_id, $attribute_name, false, $swatch_config_object->get_swatch_size() );
                                        $output .= $swatch_term->get_output($attribute_link, $image_url);
                                    }
                                    elseif ( $swatch_config_object->get_swatch_type() == 'product_custom' ) {
                                        $swatch_term = new Product_Term( $swatch_config_object, $term->term_id, $attribute_name, false );
                                        $output .= $swatch_term->get_output($attribute_link, $image_url);
                                    }
                                }
                            }
                            $output .= '</div>';
                        }
                    }
                }
            }

        }
        return $output;
    }

    public function render_swatches_in_product_listing( $product ){
        if($product->get_type() != 'variable'){
            return;
        }
        $display_as_form = lastudio_kit_settings()->get('swatches_swatches_variation_form', '');
        $attribute_allow = lastudio_kit_settings()->get('swatches_swatches_attribute_in_list', []);
        if($display_as_form == 'enabled'){
            woocommerce_variable_add_to_cart();
        }
        else{
            add_filter('lastudio-kit/swatches/configuration_object/get_swatch_style', [ $this, 'modify_swatches_style_in_product_listing' ], 1001 );
            echo $this->render_swatches_item_by_attribute( $product, $attribute_allow );
            remove_filter('lastudio-kit/swatches/configuration_object/get_swatch_style', [ $this, 'modify_swatches_style_in_product_listing' ], 1001 );
        }
    }

    public function modify_swatches_style_in_product_listing( $style ){
        return 'inlist';
    }

    public function render_swatches_in_shortcode( $html, $elClass, $product ){
        ob_start();
        $this->render_swatches_in_product_listing($product);
        $output = ob_get_clean();
        if(!empty($output)){
            return sprintf(
                '<div class="%2$s lakitp-zone-item product_item--attributes">%1$s</div>',
                $output,
                esc_attr($elClass)
            );
        }
        return $html;
    }

    /**
     * @param array   $form_fields
     * @param \WP_Post $post
     *
     * @return array
     */
    public function render_360_and_video_metabox( $form_fields, $post ){
        if (!is_admin() || !function_exists('get_current_screen')) return $form_fields;

        /**
         * In media modal get_current_screen() return null or id = 'async-upload' We don't need add extra fields elsewhere.
         */
        $current_screen = get_current_screen();
        if (null !== $current_screen && 'async-upload' != $current_screen->id) return $form_fields;

        /**
         * Don't show additional fields for non-image attachments.
         */
        if (!wp_attachment_is_image($post->ID)) return $form_fields;

        $type = get_post_meta( $post->ID, 'lakit_attach_type', true );
        $video_url = get_post_meta( $post->ID, 'lakit_attach__videourl', true );
        $sprite = get_post_meta( $post->ID, 'lakit_attach__sprite', true );
        $spriteTotalFrames = get_post_meta( $post->ID, 'lakit_attach__sprite_tf', true );
        $spriteFramesPerRow = get_post_meta( $post->ID, 'lakit_attach__sprite_fpr', true );

        ob_start();
        ?>
        <div class="lakit-admin__attach-media-wrap">
            <p class="description">Any changes are saved automatically</p>
            <div class="frm-fields">
                <div class="frm-field" data-control_id="type">
                    <label for="attachments-<?php echo $post->ID ?>-lakit_attach_type">Media Attach</label>
                    <select id="attachments-<?php echo $post->ID ?>-lakit_attach_type" name="attachments[<?php echo $post->ID ?>][lakit_attach_type]">
                        <option value="" <?php selected($type, '') ?>>None</option>
                        <option value="video" <?php selected($type, 'video') ?>>Product Video</option>
                        <option value="threesixty" <?php selected($type, 'threesixty') ?>>Product 360</option>
                    </select>
                </div>
                <?php if($type === 'video'): ?>
                <div class="frm-field frm-field--depends" data-condition="video">
                    <label for="attachments-<?php echo $post->ID ?>-lakit_attach__videourl">Media URL</label>
                    <input type="text" id="attachments-<?php echo $post->ID ?>-lakit_attach__videourl" name="attachments[<?php echo $post->ID ?>][lakit_attach__videourl]" value="<?php echo esc_attr($video_url); ?>"/>
                    <button type="button" class="button" data-type="video">Attach MP4</button>
                </div>
                <?php endif; ?>
                <?php if($type === 'threesixty'): ?>
                <div class="frm-field frm-field--depends" data-condition="threesixty">
                    <label>Sprite Image</label>
                    <div class="frm-field--preview">
                        <?php
                        if($sprite){ echo wp_get_attachment_image($sprite); }
                        ?>
                    </div>
                    <input type="hidden" name="attachments[<?php echo $post->ID ?>][lakit_attach__sprite]" value="<?php echo esc_attr($sprite); ?>"/>
                    <button type="button" class="button" data-type="image">Select Image</button>
                </div>
                <div class="frm-field frm-field--depends" data-condition="threesixty">
                    <label for="attachments-<?php echo $post->ID ?>-lakit_attach__sprite_tf">Total Frames</label>
                    <input type="number" min="0" id="attachments-<?php echo $post->ID ?>-lakit_attach__sprite_tf" name="attachments[<?php echo $post->ID ?>][lakit_attach__sprite_tf]" value="<?php echo esc_attr($spriteTotalFrames); ?>"/>
                    <p class="description">Set the total number of frames to show. The 6x6 sprite might contain 36 images, but it only has 34 frames, hence we set it to 34 here.</p>
                </div>
                <div class="frm-field frm-field--depends" data-condition="threesixty">
                    <label for="attachments-<?php echo $post->ID ?>-lakit_attach__sprite_fpr">Frames per row</label>
                    <input type="number" min="0" id="attachments-<?php echo $post->ID ?>-lakit_attach__sprite_fpr" name="attachments[<?php echo $post->ID ?>][lakit_attach__sprite_fpr]" value="<?php echo esc_attr($spriteFramesPerRow); ?>"/>
                    <p class="description">The 6x6 sprite sheet contains 6 frames in one row</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        $metabox_html = ob_get_contents();
        ob_end_clean();

        $form_fields['lakit__product_settings'] = array(
            'value'			=> '',
            'label'         => __('LaStudioKit Media Attach', 'lastudio-kit'),
            'input'         => 'html',
            'html'          => $metabox_html,
        );

        return $form_fields;
    }

    public function save_360_and_video_metabox( $attachment_id ){
        $keys = [
            'lakit_attach_type',
            'lakit_attach__videourl',
            'lakit_attach__sprite',
            'lakit_attach__sprite_tf',
            'lakit_attach__sprite_fpr',
        ];
        foreach ($keys as $key){
            if ( isset( $_REQUEST['attachments'][$attachment_id][$key] ) ) {
                $_val = $_REQUEST['attachments'][$attachment_id][$key];
                update_post_meta( $attachment_id, $key, $_val );
            }
        }
    }

    public function render_360_and_video_frontend( $html, $image_id ){
        $type = get_post_meta( $image_id, 'lakit_attach_type', true );
        $video_url = get_post_meta( $image_id, 'lakit_attach__videourl', true );
        $sprite = get_post_meta( $image_id, 'lakit_attach__sprite', true );
        $spriteTotalFrames = get_post_meta( $image_id, 'lakit_attach__sprite_tf', true );
        $spriteFramesPerRow = get_post_meta( $image_id, 'lakit_attach__sprite_fpr', true );
        $settings = [];

        if($type === 'video'){
            if( !empty($video_url) ){
                $settings['data-media-attach-type'] = 'video';
                $settings['data-media-attach-video'] = $video_url;
            }
        }
        if($type === 'threesixty'){
            $sprite_source = wp_get_attachment_image_url($sprite, 'full');
            if(!empty($sprite_source)){
                $settings['data-media-attach-type'] = 'threesixty';
                $settings['data-media-attach-threesixty'] = [
                    'source'        => $sprite_source,
                    'totalframes'   => !empty($spriteTotalFrames) ? $spriteTotalFrames : 0,
                    'framesperrow'  => !empty($spriteFramesPerRow) ? $spriteFramesPerRow : 0,
                ];
            }
        }

        if(!empty($settings)){
            $extra = '';
            foreach ($settings as $k => $v){
                if(!is_scalar($v)){
                    $v = json_encode($v);
                }
                $extra .= sprintf('%1$s="%2$s" ', esc_attr($k), esc_attr($v));
            }
            $html = str_replace('data-thumb=', $extra . 'data-thumb=', $html);
        }
        return $html;
    }
}