<?php

namespace ShopWP\Render\Storefront;

defined('ABSPATH') ?: exit();

use ShopWP\Utils\Data;

class Defaults
{
    public $Render_Attributes;
    public $Products_Defaults;

    public function __construct($Render_Attributes, $Products_Defaults)
    {
        $this->Render_Attributes = $Render_Attributes;
        $this->Products_Defaults = $Products_Defaults;
    }

    public function create_product_query($user_atts) {
        return $this->Products_Defaults->create_product_query($user_atts);
    }

    public function storefront($attrs) {
        return array_replace([], $attrs);
    }

    public function all_attrs($attrs = [])
    {

        // TODO: Remove hook in 6.0
        $settings = apply_filters('shopwp_storefront_default_payload_settings', array_merge(
            $this->Products_Defaults->all_attrs($attrs),
            [
                'query' => $this->Render_Attributes->attr($attrs, 'query', '*'),
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
                'show_tags' => $this->Render_Attributes->attr(
                    $attrs,
                    'show_tags',
                    true
                ),
                'show_vendors' => $this->Render_Attributes->attr(
                    $attrs,
                    'show_vendors',
                    true
                ),
                'show_types' => $this->Render_Attributes->attr(
                    $attrs,
                    'show_types',
                    true
                ),
                'show_collections' => $this->Render_Attributes->attr(
                    $attrs,
                    'show_collections',
                    true
                ),
                'show_price' => $this->Render_Attributes->attr(
                    $attrs,
                    'show_price',
                    true
                ),
                'show_selections' => $this->Render_Attributes->attr(
                    $attrs,
                    'show_selections',
                    true
                ),
                'show_sorting' => $this->Render_Attributes->attr(
                    $attrs,
                    'show_sorting',
                    true
                ),
                'show_pagination' => $this->Render_Attributes->attr(
                    $attrs,
                    'show_pagination',
                    true
                ),
                'show_options_heading' => $this->Render_Attributes->attr(
                    $attrs,
                    'show_options_heading',
                    true
                ),
                'dropzone_payload' => $this->Render_Attributes->attr(
                    $attrs,
                    'dropzone_payload',
                    '#shopwp-storefront-payload'
                ),
                'dropzone_options' => $this->Render_Attributes->attr(
                    $attrs,
                    'dropzone_options',
                    '#shopwp-storefront-options'
                ),
                'dropzone_selections' => $this->Render_Attributes->attr(
                    $attrs,
                    'dropzone_selections',
                    '#shopwp-storefront-selections'
                ),
                'dropzone_sorting' => $this->Render_Attributes->attr(
                    $attrs,
                    'dropzone_sorting',
                    '#shopwp-storefront-sort'
                ),
                'dropzone_page_size' => $this->Render_Attributes->attr(
                    $attrs,
                    'dropzone_page_size',
                    '#shopwp-storefront-page-size'
                ),            
                'dropzone_heading' => $this->Render_Attributes->attr(
                    $attrs,
                    'dropzone_heading',
                    false
                ),
                'dropzone_load_more' => $this->Render_Attributes->attr(
                    $attrs,
                    'dropzone_load_more',
                    true
                ),
                'dropzone_loader' => $this->Render_Attributes->attr(
                    $attrs,
                    'dropzone_loader',
                    false
                ),
                'dropzone_notices' => $this->Render_Attributes->attr(
                    $attrs,
                    'dropzone_notices',
                    false
                ),
                'pagination' => $this->Render_Attributes->attr(
                    $attrs,
                    'pagination',
                    true
                ),
                'no_results_text' => $this->Render_Attributes->attr(
                    $attrs,
                    'no_results_text',
                    __('No results found', 'shopwp')
                ),
                'excludes' => $this->Render_Attributes->attr($attrs, 'excludes', [
                    'description',
                ]),
                'items_per_row' => $this->Render_Attributes->attr(
                    $attrs,
                    'items_per_row',
                    3
                ),
                'limit' => $this->Render_Attributes->attr($attrs, 'limit', false),
                'skip_initial_render' => $this->Render_Attributes->attr(
                    $attrs,
                    'skip_initial_render',
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
                    'products'
                ),
                'price' => $this->Render_Attributes->attr(
                    $attrs,
                    'price',
                    false
                ),
                'connective' => $this->Render_Attributes->attr(
                    $attrs,
                    'connective',
                    'OR'
                ),
                'filter_option_open_on_load' => $this->Render_Attributes->attr(
                    $attrs,
                    'filter_option_open_on_load',
                    false
                ),
                'sorting_options_collections' => $this->Render_Attributes->attr(
                    $attrs,
                    'sorting_options_collections',
                    [
                        [
                            'label' => __('Title (A-Z)', 'shopwp'),
                            'value' => 'TITLE',
                        ],
                        [
                            'label' => __('Title (Z-A)', 'shopwp'),
                            'value' => 'TITLE-REVERSE',
                        ],
                        [
                            'label' => __('Price (Low to high)', 'shopwp'),
                            'value' => 'PRICE',
                        ],
                        [
                            'label' => __('Price (high to low)', 'shopwp'),
                            'value' => 'PRICE-REVERSE',
                        ],
                        [
                            'label' => __('Best Selling', 'shopwp'),
                            'value' => 'BEST_SELLING',
                        ],
                        [
                            'label' => __('Recently Added', 'shopwp'),
                            'value' => 'CREATED',
                        ],
                        [
                            'label' => __('Collection default', 'shopwp'),
                            'value' => 'COLLECTION_DEFAULT',
                        ],
                        [
                            'label' => __('Manual', 'shopwp'),
                            'value' => 'MANUAL',
                        ]
                    ]
                ),
                'sorting_options_products' => $this->Render_Attributes->attr(
                    $attrs,
                    'sorting_options_products',
                    [
                        [
                            'label' => __('Title (A-Z)', 'shopwp'),
                            'value' => 'TITLE',
                        ],
                        [
                            'label' => __('Title (Z-A)', 'shopwp'),
                            'value' => 'TITLE-REVERSE',
                        ],
                        [
                            'label' => __('Price (Low to high)', 'shopwp'),
                            'value' => 'PRICE',
                        ],
                        [
                            'label' => __('Price (High to low)', 'shopwp'),
                            'value' => 'PRICE-REVERSE',
                        ],
                        [
                            'label' => __('Best Selling', 'shopwp'),
                            'value' => 'BEST_SELLING',
                        ],
                        [
                            'label' => __('Recently Added', 'shopwp'),
                            'value' => 'CREATED_AT',
                        ],
                        [
                            'label' => __('Recently Updated', 'shopwp'),
                            'value' => 'UPDATED_AT',
                        ],
                        [
                            'label' => __('Product Type', 'shopwp'),
                            'value' => 'PRODUCT_TYPE',
                        ],
                        [
                            'label' => __('Product Vendor', 'shopwp'),
                            'value' => 'VENDOR',
                        ]
                    ]
                ),
                'sorting_options_page_size' => $this->Render_Attributes->attr(
                    $attrs,
                    'sorting_options_page_size',
                    [
                        [
                            'label' => '10',
                            'value' => 10,
                        ],
                        [
                            'label' => '25',
                            'value' => 25,
                        ],
                        [
                            'label' => '50',
                            'value' => 50,
                        ],
                        [
                            'label' => '100',
                            'value' => 100,
                        ]
                    ]
                ),
                'type' => $this->Render_Attributes->attr(
                    $attrs,
                    'type',
                    'storefront'
                ),
                'filterable_price_values' => $this->Render_Attributes->attr(
                    $attrs,
                    'filterable_price_values',
                    [
                        __('$0.00 - $15.00', 'shopwp'),
                        __('$15.00 - $25.00', 'shopwp'),
                        __('$25.00 - $50.00', 'shopwp'),
                        __('$50.00 - $100.00', 'shopwp'),
                        __('$100.00 +', 'shopwp'),
                    ]
                ),
                'no_filter_group_found_text' => $this->Render_Attributes->attr(
                    $attrs,
                    'no_filter_group_found_text',
                    __('No items found', 'shopwp')
                ),
                'filter_by_label_text' => $this->Render_Attributes->attr(
                    $attrs,
                    'filter_by_label_text',
                    __('Filter by:', 'shopwp')
                ),
                'page_size_label_text' => $this->Render_Attributes->attr(
                    $attrs,
                    'page_size_label_text',
                    __('Page size:', 'shopwp')
                ),
                'clear_filter_selections_text' => $this->Render_Attributes->attr(
                    $attrs,
                    'clear_filter_selections_text',
                    __('Clear all', 'shopwp')
                ),
                'selections_available_for_sale_text' => $this->Render_Attributes->attr(
                    $attrs,
                    'selections_available_for_sale_text',
                    __('Available for sale', 'shopwp')
                ),
                'sort_by_label_text' => $this->Render_Attributes->attr(
                    $attrs,
                    'sort_by_label_text',
                    __('Sort by:', 'shopwp')
                ),
                'load_more_collections_busy_text' => $this->Render_Attributes->attr(
                    $attrs,
                    'load_more_collections_busy_text',
                    __('Loading...', 'shopwp')
                ),
                'load_more_collections_text' => $this->Render_Attributes->attr(
                    $attrs,
                    'load_more_collections_text',
                    __('See more', 'shopwp')
                ),
                'collections_heading' => $this->Render_Attributes->attr(
                    $attrs,
                    'collections_heading',
                    __('Collections', 'shopwp')
                ),
                'price_heading' => $this->Render_Attributes->attr(
                    $attrs,
                    'price_heading',
                    __('Price', 'shopwp')
                ),
                'tags_heading' => $this->Render_Attributes->attr(
                    $attrs,
                    'tags_heading',
                    __('Tags', 'shopwp')
                ),
                'types_heading' => $this->Render_Attributes->attr(
                    $attrs,
                    'types_heading',
                    __('Types', 'shopwp')
                ),
                'vendors_heading' => $this->Render_Attributes->attr(
                    $attrs,
                    'vendors_heading',
                    __('Vendors', 'shopwp')
                ),
                'show_search' => $this->Render_Attributes->attr(
                    $attrs,
                    'show_search',
                    false
                ),
            ]
        ));

        return apply_filters('shopwp_storefront_default_settings', $settings);
    }
}
