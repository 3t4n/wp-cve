<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\Framework\Support\Arr;
use WPPayForm\App\Models\Form;


if (!defined('ABSPATH')) {
    exit;
}

class DemoTabularProductsComponent extends BaseComponent
{
    public function __construct()
    {
        parent::__construct('tabular_products', 202);
        add_filter('wppayform/validate_component_on_save_tabular_products', array($this, 'validateOnSave'), 1, 3);
    }

    public function component()
    {
        return array(
            'type' => 'tabular_products',
            'editor_title' => 'Tabular Product Items',
            'group' => 'payment',
            'is_pro' => 'yes',
            'isNumberic' => 'yes',
            'postion_group' => 'payment',
            // 'conditional_hide' => true,
            'editor_elements' => array(
                'label' => array(
                    'label' => 'Field Label',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'products' => array(
                    'label' => 'Setup Your Tabular products',
                    'group' => 'general',
                    'type' => 'tabular_products',
                ),
                'show_sub_total' => array(
                    'label' => 'Show Subtotal',
                    'type' => 'switch',
                    'group' => 'general',
                    'info' => 'If enabled then user can see subtotal after the table'
                ),
                'table_photo_label' => array(
                    'label' => 'Photo Column Label',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'table_item_label' => array(
                    'label' => 'Table Item Column Label',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'table_price_label' => array(
                    'label' => 'Table Price Column Label',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'table_quantity_label' => array(
                    'label' => 'Table Quantity Column Label',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'table_subtotal_label' => array(
                    'label' => __('Table Sub Total Label', 'wp-payment-form-pro'),
                    'type' => 'text',
                    'group' => 'general'
                ),
                'admin_label' => array(
                    'label' => 'Admin Label',
                    'type' => 'text',
                    'group' => 'advanced'
                ),
                'wrapper_class' => array(
                    'label' => 'Field Wrapper CSS Class',
                    'type' => 'text',
                    'group' => 'advanced'
                ),
                'element_class' => array(
                    'label' => 'Input Element CSS Class',
                    'type' => 'text',
                    'group' => 'advanced'
                ),
                'conditional_render' => array(
                    'type' => 'conditional_render',
                    'group' => 'advanced',
                    'label' => 'Conditional render',
                    'selection_type' => 'Conditional logic',
                    'conditional_logic' => array(
                        'yes' => 'Yes',
                        'no' => 'No'
                    ),
                    'conditional_type' => array(
                        'any' => 'Any',
                        'all' => 'All'
                    ),
                ),
            ),
            'is_system_field' => true,
            'is_payment_field' => true,
            'field_options' => array(
                'label' => 'Add Quantity Of The Products',
                'show_sub_total' => 'yes',
                'table_item_label' => __('Product', 'wp-payment-form-pro'),
                'table_description_label' => __('Description', 'wp-payment-form-pro'),
                'table_price_label' => __('Item Price', 'wp-payment-form-pro'),
                'table_quantity_label' => __('Quantity', 'wp-payment-form-pro'),
                'table_subtotal_label' => __('Sub Total', 'wp-payment-form-pro'),
                'table_photo_label' => __('Photo', 'wp-payment-form-pro'),
                'layout' => 'table',
                'enable_quantity' => 'yes',
                'enable_product_size' => 'yes',
                'enable_search' => 'yes',
                'enable_feature' => 'yes',
                'categories' => [
                    array(
                        'label' => __('Product Category', 'wp-payment-form-pro'),
                        'value' => 'product_category'
                    ),
                ],
                'products' => array(
                    [
                        'product_name' => 'Product 1',
                        'default_quantity' => 1,
                        'min_quantity' => 0,
                        'max_quantity' => 100,
                        'product_price' => '10',
                        'product_description' => __('Product Description', 'wp-payment-form-pro'),
                        'category' => array(),
                        'enable_product' => 'no',
                        'photo' => [
                            array(
                                'alt_text' => 'default product image',
                                'image_full' => '/images/form/default_product.png',
                                'image_thumb' => '/images/form/default_product.png',
                            )
                        ],
                        'product_size' =>  array(
                            [
                                array(
                                    'label' => 'Size',
                                    'options' =>  [
                                        array(
                                            'label' => 'S',
                                            'value' => 's',
                                            'price' => ''
                                        ),
                                    ]
                                )
                            ]
                        ),
                    ],
                    [
                        'product_name' => 'Product 2',
                        'default_quantity' => 0,
                        'min_quantity' => 0,
                        'max_quantity' => 100,
                        'product_price' => '20',
                        'product_description' => __('Product Description', 'wp-payment-form-pro'),
                        'categories' => array('all'),
                        'enable_product' => 'no',
                        'photo' => [
                            array(
                                'alt_text' => 'default product image',
                                'image_full' => '/images/form/default_product.png',
                                'image_thumb' => '/images/form/default_product.png',
                            )
                        ],
                        'product_size' =>  [
                            [
                                array(
                                    'label' => 'Size',
                                    'options' =>  [
                                        array(
                                            'label' => 'S',
                                            'value' => 's',
                                            'price' => ''
                                        ),
                                    ]
                                ),
                            ],
                            [
                                array(
                                    'label' => 'Test',
                                    'options' =>  [
                                        array(
                                            'label' => 'test',
                                            'value' => 'test opt',
                                            'price' => ''
                                        ),
                                    ]
                                )
                            ]
                        ],
                    ]
                )
            )
        );
    }

    public function validateOnSave($error, $element, $formId)
    {
        return $error;
    }

    public function render($element, $form, $elements)
    {
        return;
    }

    public function renderGridAndRowProductCard($element, $itemId, $showSubtotal, $tableAttributes, $currenySettings)
    {
        return;
    }

    public function checkAndAddMetaPrice($product)
    {
        return;
    }

    private function renderImage($product, $lightboxed = false)
    {
        return;
    }
}
