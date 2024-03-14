<?php

use Automattic\WooCommerce\Admin\BlockTemplates\BlockInterface;

function add_meta_fields($after_image_section) {

    $after_image_section->add_hide_condition("editedProduct.type === 'variable'");
    $general_group = $after_image_section->get_parent();

    $section = $general_group->add_section(
            [
                'id' => 'wt-feed-product-section',
                'order' => $after_image_section->get_order() + 5,
                'attributes' => [
                    'title' => __('WebToffee Product Feed', 'woocommerce'),
                    'description' => __('Add WooCommerce Product Feed data for different channels', 'woocommerce'),
                ],
                'hideConditions' => [
                    [
                        'expression' => 'editedProduct.type === "variable"',
                    ],
                ],
            ]
    );

    $wt_feed_columns1 = $section->add_block(
            [
                'id' => 'wt-product-feed-columns1',
                'blockName' => 'core/columns',
                'order' => 30,
            ]
    );
    $wt_feed_column1_sec1 = $wt_feed_columns1->add_block(
            [
                'id' => 'wt-product-feed-column-1',
                'blockName' => 'core/column',
                'order' => 10,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );
    $wt_feed_column1_sec1->add_block(
            [
                'id' => '_wt_feed_brand-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 13,
                'attributes' => [
                    'label' => __('Brand'),
                    'property' => 'meta_data._wt_feed_brand',
                    'required' => false,
                    'help' => __('The Brand of the product'),
                    'tooltip' => __('The Brand of the product')
                ],
            ]
    );
    $wt_feed_column1_sec2 = $wt_feed_columns1->add_block(
            [
                'id' => 'wt-product-feed-column-2',
                'blockName' => 'core/column',
                'order' => 20,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );
    $wt_feed_column1_sec2->add_block(
            [
                'id' => '_wt_feed_gtin-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 16,
                'attributes' => [
                    'label' => __('GTIN'),
                    'property' => 'meta_data._wt_feed_gtin',
                    'required' => false,
                    'help' => __('The Global Trade Item Number (GTIN) is an identifier for trade items.'),
                    'tooltip' => __('The Global Trade Item Number (GTIN) is an identifier for trade items.')
                ]
            ],
    );

    
    $wt_feed_columns01 = $section->add_block(
            [
                'id' => 'wt-product-feed-columns01',
                'blockName' => 'core/columns',
                'order' => 30,
            ]
    );
    $wt_feed_column01_sec1 = $wt_feed_columns01->add_block(
            [
                'id' => 'wt-product-feed-column-01',
                'blockName' => 'core/column',
                'order' => 10,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );
    $wt_feed_column01_sec1->add_block(
            [
                'id' => '_wt_feed_han-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 13,
                'attributes' => [
                    'label' => __('HAN'),
                    'property' => 'meta_data._wt_feed_han',
                    'required' => false,
                    'help' => __('A Manufacturer Article Number (HAN) is a unique identification number assigned by manufacturers to identify their own products.'),
                    'tooltip' => __('A Manufacturer Article Number (HAN) is a unique identification number assigned by manufacturers to identify their own products.')
                ],
            ]
    );
    $wt_feed_column01_sec2 = $wt_feed_columns01->add_block(
            [
                'id' => 'wt-product-feed-column-02',
                'blockName' => 'core/column',
                'order' => 20,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );
    $wt_feed_column01_sec2->add_block(
            [
                'id' => '_wt_feed_ean-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 16,
                'attributes' => [
                    'label' => __('EAN'),
                    'property' => 'meta_data._wt_feed_ean',
                    'required' => false,
                    'help' => __('A European Article Number (EAN) is a unique identification number assigned by manufacturers to identify their own products.'),
                    'tooltip' => __('A European Article Number (EAN) is a unique identification number assigned by manufacturers to identify their own products.')
                ]
            ],
    );
    
    
    
    
    $wt_feed_columns2 = $section->add_block(
            [
                'id' => 'wt-product-feed-columns2',
                'blockName' => 'core/columns',
                'order' => 30,
            ]
    );

    $wt_feed_column2_sec1 = $wt_feed_columns2->add_block(
            [
                'id' => 'wt-product-feed-column-3',
                'blockName' => 'core/column',
                'order' => 10,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );

    $wt_feed_column2_sec1->add_block(
            [
                'id' => '_wt_feed_mpn-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 16,
                'attributes' => [
                    'label' => __('MPN'),
                    'property' => 'meta_data._wt_feed_mpn',
                    //'suffix' => 'suffix',
                    //'placeholder' => 'Placeholder',
                    'required' => false,
                    'help' => __('A manufacturer part number (MPN) is a series of numbers and/or letters given to a part by its manufacturer.'),
                    'tooltip' => __('A manufacturer part number (MPN) is a series of numbers and/or letters given to a part by its manufacturer.')
                ]
            ],
    );
    
    
    $wt_feed_column2_sec2 = $wt_feed_columns2->add_block(
            [
                'id' => 'wt-product-feed-column-4',
                'blockName' => 'core/column',
                'order' => 20,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );

    $product_conditions = Webtoffee_Product_Feed_Sync_Common_Helper::wt_feed_get_product_conditions();

    $product_condition_list = [];
    $i = 0;
    foreach ($product_conditions as $value => $label) {
        $product_condition_list[$i]['label'] = $label;
        $product_condition_list[$i]['value'] = $value;
        $i++;
    }

    $wt_feed_column2_sec2->add_block(
            [
                'id' => '_wt_feed_conditon-meta',
                'blockName' => 'woocommerce/product-radio-field',
                'order' => 30,
                'attributes' => [
                    'title' => __('Condition'),
                    'property' => 'meta_data._wt_feed_condition',
                    'options' => $product_condition_list,
                    'description' => __('The product condition.')
                ],
            ]
    );

    $wt_feed_columns3 = $section->add_block(
            [
                'id' => 'wt-product-feed-columns3',
                'blockName' => 'core/columns',
                'order' => 30,
            ]
    );

    $wt_feed_column3_sec1 = $wt_feed_columns3->add_block(
            [
                'id' => 'wt-product-feed-column-5',
                'blockName' => 'core/column',
                'order' => 10,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );

    $age_group = Webtoffee_Product_Feed_Sync_Common_Helper::get_age_group();

    $age_group_list = [];
    $i = 0;
    foreach ($age_group as $value => $label) {
        $age_group_list[$i]['label'] = $label;
        $age_group_list[$i]['value'] = $value;
        $i++;
    }

    $wt_feed_column3_sec1->add_block(
            [
                'id' => '_wt_feed_agegroup-meta',
                'blockName' => 'woocommerce/product-radio-field',
                'order' => 30,
                'attributes' => [
                    'title' => __('Age group'),
                    'property' => 'meta_data._wt_feed_agegroup',
                    'options' => $age_group_list,
                    'description' => __('The product age group.')
                ],
            ]
    );
    $wt_feed_column3_sec2 = $wt_feed_columns3->add_block(
            [
                'id' => 'wt-product-feed-column-6',
                'blockName' => 'core/column',
                'order' => 20,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );

    $genders = Webtoffee_Product_Feed_Sync_Common_Helper::get_geneder_list();

    $gender_list = [];
    $i = 0;
    foreach ($genders as $value => $label) {
        $gender_list[$i]['label'] = $label;
        $gender_list[$i]['value'] = $value;
        $i++;
    }

    $wt_feed_column3_sec2->add_block(
            [
                'id' => '_wt_feed_gender-meta',
                'blockName' => 'woocommerce/product-radio-field',
                'order' => 30,
                'attributes' => [
                    'title' => __('Gender'),
                    'property' => 'meta_data._wt_feed_gender',
                    'options' => $gender_list,
                    'description' => __('The product gender.')
                ],
            ]
    );

    $wt_feed_columns4 = $section->add_block(
            [
                'id' => 'wt-product-feed-columns4',
                'blockName' => 'core/columns',
                'order' => 30,
            ]
    );
    $wt_feed_column4_sec1 = $wt_feed_columns4->add_block(
            [
                'id' => 'wt-product-feed-column-7',
                'blockName' => 'core/column',
                'order' => 10,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );
    $wt_feed_column4_sec1->add_block(
            [
                'id' => '_wt_feed_size-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 13,
                'attributes' => [
                    'label' => __('Size'),
                    'property' => 'meta_data._wt_feed_size',
                    'required' => false,
                    'help' => __('The Size of the product'),
                    'tooltip' => __('The Size of the product')
                ],
            ]
    );
    $wt_feed_column4_sec2 = $wt_feed_columns4->add_block(
            [
                'id' => 'wt-product-feed-column-8',
                'blockName' => 'core/column',
                'order' => 20,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );
    $wt_feed_column4_sec2->add_block(
            [
                'id' => '_wt_feed_color-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 16,
                'attributes' => [
                    'label' => __('Color'),
                    'property' => 'meta_data._wt_feed_color',
                    'required' => false,
                    'help' => __('The Color of the product.'),
                    'tooltip' => __('The Color of the product.')
                ]
            ],
    );

    $wt_feed_columns5 = $section->add_block(
            [
                'id' => 'wt-product-feed-columns5',
                'blockName' => 'core/columns',
                'order' => 30,
            ]
    );
    $wt_feed_column5_sec1 = $wt_feed_columns5->add_block(
            [
                'id' => 'wt-product-feed-column-9',
                'blockName' => 'core/column',
                'order' => 10,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );
    $wt_feed_column5_sec1->add_block(
            [
                'id' => '_wt_feed_material-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 13,
                'attributes' => [
                    'label' => __('Material'),
                    'property' => 'meta_data._wt_feed_material',
                    'required' => false,
                    'help' => __('The Material of the product'),
                    'tooltip' => __('The Material of the product')
                ],
            ]
    );
    $wt_feed_column5_sec2 = $wt_feed_columns5->add_block(
            [
                'id' => 'wt-product-feed-column-10',
                'blockName' => 'core/column',
                'order' => 20,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );
    $wt_feed_column5_sec2->add_block(
            [
                'id' => '_wt_feed_pattern-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 16,
                'attributes' => [
                    'label' => __('Pattern'),
                    'property' => 'meta_data._wt_feed_pattern',
                    'required' => false,
                    'help' => __('The Pattern of the product.'),
                    'tooltip' => __('The Pattern of the product.')
                ]
            ],
    );

    $wt_feed_columns6 = $section->add_block(
            [
                'id' => 'wt-product-feed-columns6',
                'blockName' => 'core/columns',
                'order' => 30,
            ]
    );
    $wt_feed_column6_sec1 = $wt_feed_columns6->add_block(
            [
                'id' => 'wt-product-feed-column-11',
                'blockName' => 'core/column',
                'order' => 10,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );
    $wt_feed_column6_sec1->add_block(
            [
                'id' => '_wt_feed_unit_pricing_measure-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 13,
                'attributes' => [
                    'label' => __('Unit pricing measure'),
                    'property' => 'meta_data._wt_feed_unit_pricing_measure',
                    'required' => false,
                    'help' => __('Use the unit pricing measure [unit_pricing_measure] attribute to define the measure and dimension of your product. This value allows users to understand the exact cost per unit for your product.'),
                    'tooltip' => __('Use the unit pricing measure [unit_pricing_measure] attribute to define the measure and dimension of your product. This value allows users to understand the exact cost per unit for your product.')
                ],
            ]
    );
    $wt_feed_column6_sec2 = $wt_feed_columns6->add_block(
            [
                'id' => 'wt-product-feed-column-12',
                'blockName' => 'core/column',
                'order' => 20,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );
    $wt_feed_column6_sec2->add_block(
            [
                'id' => '_wt_feed_unit_pricing_base_measure-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 16,
                'attributes' => [
                    'label' => __('Unit pricing base measure'),
                    'property' => 'meta_data._wt_feed_unit_pricing_base_measure',
                    'required' => false,
                    'help' => __('The unit pricing base measure [unit_pricing_base_measure] attribute lets you include the denominator for your unit price. For example, you might be selling "150ml" of perfume, but customers are interested in seeing the price per "100ml".'),
                    'tooltip' => __('The unit pricing base measure [unit_pricing_base_measure] attribute lets you include the denominator for your unit price. For example, you might be selling "150ml" of perfume, but customers are interested in seeing the price per "100ml".')
                ]
            ],
    );

    $wt_feed_columns7 = $section->add_block(
            [
                'id' => 'wt-product-feed-columns7',
                'blockName' => 'core/columns',
                'order' => 30,
            ]
    );
    $wt_feed_column7_sec1 = $wt_feed_columns7->add_block(
            [
                'id' => 'wt-product-feed-column-13',
                'blockName' => 'core/column',
                'order' => 10,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );
    $wt_feed_column7_sec1->add_block(
            [
                'id' => '_wt_feed_energy_efficiency_class-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 13,
                'attributes' => [
                    'label' => __('Energy efficiency class'),
                    'property' => 'meta_data._wt_feed_energy_efficiency_class',
                    'required' => false,
                    'help' => __('The [energy_efficiency_class] attributes to tell customers the energy label of your product.'),
                    'tooltip' => __('The [energy_efficiency_class] attributes to tell customers the energy label of your product.')
                ],
            ]
    );
    $wt_feed_column7_sec2 = $wt_feed_columns7->add_block(
            [
                'id' => 'wt-product-feed-column-14',
                'blockName' => 'core/column',
                'order' => 20,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );
    $wt_feed_column7_sec2->add_block(
            [
                'id' => '_wt_feed_min_energy_efficiency_class-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 16,
                'attributes' => [
                    'label' => __('Minimum Energy efficiency class'),
                    'property' => 'meta_data._wt_feed_min_energy_efficiency_class',
                    'required' => false,
                    'help' => __('The [min_energy_efficiency_class] attributes to tell customers the energy label of your product.'),
                    'tooltip' => __('The [min_energy_efficiency_class] attributes to tell customers the energy label of your product.')
                ]
            ],
    );

    $wt_feed_columns8 = $section->add_block(
            [
                'id' => 'wt-product-feed-columns8',
                'blockName' => 'core/columns',
                'order' => 30,
            ]
    );
    $wt_feed_column8_sec1 = $wt_feed_columns8->add_block(
            [
                'id' => 'wt-product-feed-column-15',
                'blockName' => 'core/column',
                'order' => 10,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );
    $wt_feed_column8_sec1->add_block(
            [
                'id' => '_wt_feed_max_energy_efficiency_class-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 13,
                'attributes' => [
                    'label' => __('Maximum Energy efficiency class'),
                    'property' => 'meta_data._wt_feed_max_energy_efficiency_class',
                    'required' => false,
                    'help' => __('The [max_energy_efficiency_class] attributes to tell customers the energy label of your product.'),
                    'tooltip' => __('The [max_energy_efficiency_class] attributes to tell customers the energy label of your product.')
                ],
            ]
    );
    $wt_feed_column8_sec2 = $wt_feed_columns8->add_block(
            [
                'id' => 'wt-product-feed-column-16',
                'blockName' => 'core/column',
                'order' => 20,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );

    $glpi_pickup_methods = [
        '' => __('Default'),
        'buy' => __('Buy'),
        'reserve' => __('Reserve'),
        'ship to store' => __('Ship to store'),
        'not supported' => __('Not supported'),
    ];

    $pickup_methods_list = [];
    $i = 0;
    foreach ($glpi_pickup_methods as $value => $label) {
        $pickup_methods_list[$i]['label'] = $label;
        $pickup_methods_list[$i]['value'] = $value;
        $i++;
    }

    $wt_feed_column8_sec2->add_block(
            [
                'id' => '_wt_feed_glpi_pickup_method-meta',
                'blockName' => 'woocommerce/product-radio-field',
                'order' => 30,
                'attributes' => [
                    'title' => __('Pickup method'),
                    'property' => 'meta_data._wt_feed_glpi_pickup_method',
                    'options' => $pickup_methods_list,
                    'description' => __('The product Pickup method, used in google local product inventory.')
                ],
            ]
    );

    $wt_feed_columns9 = $section->add_block(
            [
                'id' => 'wt-product-feed-columns9',
                'blockName' => 'core/columns',
                'order' => 30,
            ]
    );
    $wt_feed_column9_sec1 = $wt_feed_columns9->add_block(
            [
                'id' => 'wt-product-feed-column-17',
                'blockName' => 'core/column',
                'order' => 10,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );

    $glpi_pickup_sla = [
        '' => __('Default'),
        'same day' => __('Same day'),
        'next day' => __('Next day'),
        '2-day' => __('2 Day'),
        '3-day' => __('3 Day'),
        '4-day' => __('4 Day'),
        '5-day' => __('5 Day'),
        '6-day' => __('6 Day'),
        'multi-week' => __('Multi week'),
    ];

    $glpi_pickup_sla_list = [];
    $i = 0;
    foreach ($glpi_pickup_sla as $value => $label) {
        $glpi_pickup_sla_list[$i]['label'] = $label;
        $glpi_pickup_sla_list[$i]['value'] = $value;
        $i++;
    }

    $wt_feed_column9_sec1->add_block(
            [
                'id' => '_wt_feed_glpi_pickup_sla-meta',
                'blockName' => 'woocommerce/product-radio-field',
                'order' => 30,
                'attributes' => [
                    'title' => __('Pickup SLA'),
                    'property' => 'meta_data._wt_feed_glpi_pickup_sla',
                    'options' => $glpi_pickup_sla_list,
                    'description' => __('The product Pickup SLA, used in google local product inventory.')
                ],
            ]
    );
    $wt_feed_column9_sec2 = $wt_feed_columns9->add_block(
            [
                'id' => 'wt-product-feed-column-18',
                'blockName' => 'core/column',
                'order' => 20,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );

    $wt_feed_column9_sec2->add_block(
            [
                'id' => '_wt_feed_custom_label_0-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 13,
                'attributes' => [
                    'label' => __('Custom label 0'),
                    'property' => 'meta_data._wt_feed_custom_label_0',
                    'required' => false,
                    'help' => __('Additional custom label for the item. Character limit: 100. eg:- Summer Sale.'),
                    'tooltip' => __('Additional custom label for the item. Character limit: 100. eg:- Summer Sale.')
                ],
            ]
    );

    $wt_feed_columns10 = $section->add_block(
            [
                'id' => 'wt-product-feed-columns10',
                'blockName' => 'core/columns',
                'order' => 30,
            ]
    );
    $wt_feed_column10_sec1 = $wt_feed_columns10->add_block(
            [
                'id' => 'wt-product-feed-column-19',
                'blockName' => 'core/column',
                'order' => 10,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );
    $wt_feed_column10_sec1->add_block(
            [
                'id' => '_wt_feed_custom_label_1-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 13,
                'attributes' => [
                    'label' => __('Custom label 1'),
                    'property' => 'meta_data._wt_feed_custom_label_1',
                    'required' => false,
                    'help' => __('Additional custom label for the item. Character limit: 100. eg:- Summer Sale.'),
                    'tooltip' => __('Additional custom label for the item. Character limit: 100. eg:- Summer Sale.')
                ],
            ]
    );
    $wt_feed_column10_sec2 = $wt_feed_columns10->add_block(
            [
                'id' => 'wt-product-feed-column-20',
                'blockName' => 'core/column',
                'order' => 20,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );
    $wt_feed_column10_sec2->add_block(
            [
                'id' => '_wt_feed_custom_label_2-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 16,
                'attributes' => [
                    'label' => __('Custom label 2'),
                    'property' => 'meta_data._wt_feed_custom_label_2',
                    'required' => false,
                    'help' => __('Additional custom label for the item. Character limit: 100. eg:- Summer Sale.'),
                    'tooltip' => __('Additional custom label for the item. Character limit: 100. eg:- Summer Sale.')
                ]
            ],
    );

    $wt_feed_columns11 = $section->add_block(
            [
                'id' => 'wt-product-feed-columns11',
                'blockName' => 'core/columns',
                'order' => 30,
            ]
    );
    $wt_feed_column11_sec1 = $wt_feed_columns11->add_block(
            [
                'id' => 'wt-product-feed-column-21',
                'blockName' => 'core/column',
                'order' => 10,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );
    $wt_feed_column11_sec1->add_block(
            [
                'id' => '_wt_feed_custom_label_3-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 13,
                'attributes' => [
                    'label' => __('Custom label 3'),
                    'property' => 'meta_data._wt_feed_custom_label_3',
                    'required' => false,
                    'help' => __('Additional custom label for the item. Character limit: 100. eg:- Summer Sale.'),
                    'tooltip' => __('Additional custom label for the item. Character limit: 100. eg:- Summer Sale.')
                ],
            ]
    );
    $wt_feed_column11_sec2 = $wt_feed_columns11->add_block(
            [
                'id' => 'wt-product-feed-column-22',
                'blockName' => 'core/column',
                'order' => 20,
                'attributes' => [
                    'templateLock' => 'all',
                ],
            ]
    );
    $wt_feed_column11_sec2->add_block(
            [
                'id' => '_wt_feed_custom_label_4-meta',
                'blockName' => 'woocommerce/product-text-field',
                'order' => 16,
                'attributes' => [
                    'label' => __('Custom label 4'),
                    'property' => 'meta_data._wt_feed_custom_label_4',
                    'required' => false,
                    'help' => __('Additional custom label for the item. Character limit: 100. eg:- Summer Sale.'),
                    'tooltip' => __('Additional custom label for the item. Character limit: 100. eg:- Summer Sale.')
                ]
            ],
    );
}



function webtoffee_feed_hook_meta_field() {
    add_action(
            'woocommerce_block_template_area_product-form_after_add_block_product-variation-images-section',
            'add_meta_fields'
    );
    add_action(
            'woocommerce_block_template_area_product-form_after_add_block_product-images-section',
            'add_meta_fields'
    );
}

add_action('init', 'webtoffee_feed_hook_meta_field', 0);
