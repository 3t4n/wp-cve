<?php

namespace ShopWP\Render\Collections;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Utils\Data;

class Defaults
{
    public $Render_Attributes;
    public $Products_Defaults;
    public $plugin_settings;

    public function __construct(
        $Render_Attributes,
        $Products_Defaults,
        $plugin_settings
    ) {
        $this->Render_Attributes = $Render_Attributes;
        $this->Products_Defaults = $Products_Defaults;
        $this->plugin_settings = $plugin_settings;
    }

    public function create_product_query($user_atts) {
        return $this->Products_Defaults->create_product_query($user_atts);
    }

    public function create_collections_query($all_attrs)
    {
        $filter_params = $this->Render_Attributes->get_collections_filter_params_from_shortcode(
            Data::format_shortcode_attrs($all_attrs)
        );

        if (!isset($all_attrs['connective'])) {
            if (empty($all_attrs)) {
                $all_attrs = [];
            }

            $all_attrs['connective'] = 'AND';
        }

        return $this->Render_Attributes->build_query(
            $filter_params,
            $all_attrs
        );
    }

    public function collections($attrs) {
        return array_replace([], $attrs);
    }

    public function all_attrs($attrs = [])
    {
        $default_products_query = [
            'sort_by' => 'collection_default',
            'reverse' => false,
            'page_size' => 10,
            'query' => '',
            'query_type' => 'collectionProducts'
        ];

        if (empty($attrs['products'])) {
            $attrs_prods = $default_products_query;
        } else {
            $attrs_prods = array_merge(
                $default_products_query,
                $attrs['products']
            );
        }

        // TODO: Delete this filter in 6.0
        $settings = apply_filters('shopwp_collections_default_payload_settings', array_merge(
            [
                'products' => $this->Products_Defaults->all_attrs(
                    $attrs_prods
                ),
            ],
            [
                'query' => $this->create_collections_query($attrs),
                'sort_by' => $this->Render_Attributes->attr(
                    $attrs,
                    'sort_by',
                    'TITLE'
                ),
                'reverse' => $this->Render_Attributes->attr(
                    $attrs,
                    'reverse',
                    false
                ),
                'page_size' => $this->Render_Attributes->attr(
                    $attrs,
                    'page_size',
                    10
                ),
                'single' => $this->Render_Attributes->attr($attrs, 'single', false),
                'collection_type' => $this->Render_Attributes->attr(
                    $attrs,
                    'collection_type',
                    false
                ),
                'images_sizing_toggle' => $this->Render_Attributes->attr(
                    $attrs,
                    'images_sizing_toggle',
                    $this->plugin_settings['general'][
                        'collections_images_sizing_toggle'
                    ]
                ),
                'images_sizing_toggle' => $this->Render_Attributes->attr(
                    $attrs,
                    'images_sizing_width',
                    $this->plugin_settings['general'][
                        'collections_images_sizing_width'
                    ]
                ),
                'images_sizing_toggle' => $this->Render_Attributes->attr(
                    $attrs,
                    'images_sizing_height',
                    $this->plugin_settings['general'][
                        'collections_images_sizing_height'
                    ]
                ),
                'images_sizing_toggle' => $this->Render_Attributes->attr(
                    $attrs,
                    'images_sizing_crop',
                    $this->plugin_settings['general'][
                        'collections_images_sizing_crop'
                    ]
                ),
                'images_sizing_toggle' => $this->Render_Attributes->attr(
                    $attrs,
                    'images_sizing_scale',
                    $this->plugin_settings['general'][
                        'collections_images_sizing_scale'
                    ]
                ),
                'collection_id' => $this->Render_Attributes->attr(
                    $attrs,
                    'collection_id',
                    false
                ),
                'post_id' => $this->Render_Attributes->attr(
                    $attrs,
                    'post_id',
                    false
                ),
                'connective' => $this->Render_Attributes->attr(
                    $attrs,
                    'connective',
                    'OR'
                ),
                'title' => $this->Render_Attributes->attr($attrs, 'title', false),
                'collection' => $this->Render_Attributes->attr(
                    $attrs,
                    'collection',
                    false
                ),
                'items_per_row' => $this->Render_Attributes->attr(
                    $attrs,
                    'items_per_row',
                    3
                ),
                'limit' => $this->Render_Attributes->attr($attrs, 'limit', false),
                'post_meta' => $this->Render_Attributes->attr(
                    $attrs,
                    'post_meta',
                    false
                ),
                'excludes' => $this->Render_Attributes->attr($attrs, 'excludes', [
                    'products',
                ]),
                'pagination' => $this->Render_Attributes->attr(
                    $attrs,
                    'pagination',
                    true
                ),
                'dropzone_page_size' => $this->Render_Attributes->attr(
                    $attrs,
                    'dropzone_page_size',
                    false
                ),
                'dropzone_load_more' => $this->Render_Attributes->attr(
                    $attrs,
                    'dropzone_load_more',
                    false
                ),
                'skip_initial_render' => $this->Render_Attributes->attr(
                    $attrs,
                    'skip_initial_render',
                    false
                ),
                'dropzone_collection_title' => $this->Render_Attributes->attr(
                    $attrs,
                    'dropzone_collection_title',
                    false
                ),
                'dropzone_collection_image' => $this->Render_Attributes->attr(
                    $attrs,
                    'dropzone_collection_image',
                    false
                ),
                'dropzone_collection_description' => $this->Render_Attributes->attr(
                    $attrs,
                    'dropzone_collection_description',
                    false
                ),
                'dropzone_collection_products' => $this->Render_Attributes->attr(
                    $attrs,
                    'dropzone_collection_products',
                    false
                ),
                'infinite_scroll' => $this->Render_Attributes->attr(
                    $attrs,
                    'infinite_scroll',
                    false
                ),
                'infinite_scroll_offset' => $this->Render_Attributes->attr(
                    $attrs,
                    'infinite_scroll_offset',
                    -200
                ),
                'query_type' => $this->Render_Attributes->attr(
                    $attrs,
                    'query_type',
                    'collections'
                ),
                'is_singular' => is_singular(SHOPWP_COLLECTIONS_POST_TYPE_SLUG),
                'link_to' => $this->Render_Attributes->attr(
                    $attrs,
                    'link_to',
                    'wordpress'
                ),
                'link_target' => $this->Render_Attributes->attr(
                    $attrs,
                    'link_target',
                    '_self'
                ),
                'no_results_text' => $this->Render_Attributes->attr(
                    $attrs,
                    'no_results_text',
                    __('No collections left to show', 'shopwp')
                ),
                'type' => $this->Render_Attributes->attr(
                    $attrs,
                    'type',
                    'collection'
                ),
                'sorting' => $this->Render_Attributes->attr(
                    $attrs,
                    'sorting',
                    false
                ),
                'pagination_load_more_text' => $this->Render_Attributes->attr(
                    $attrs, 
                    'pagination_load_more_text', 
                    __('Load more', 'shopwp')
                ),
            ]
        ));

        return apply_filters('shopwp_collections_default_settings', $settings);
    }
}
