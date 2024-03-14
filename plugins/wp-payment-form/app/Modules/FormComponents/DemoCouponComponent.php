<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\App\Modules\FormComponents\BaseComponent;
use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}
class DemoCouponComponent extends BaseComponent
{
    private $coupons = 'coupon';

    public function __construct()
    {
        parent::__construct('coupon', 20);
    }

    public function component()
    {
        $components = array(
            'type' => 'coupon',
            'editor_title' => 'Coupon',
            'group' => 'payment',
            'is_pro' => 'yes',
            'postion_group' => 'payment',
            'is_system_field' => false,
            'is_payment_field' => false,
            'disabled_message' => array(
                'tabular_products' => array(
                    'editor_title' => __('Tabular Product Items', 'wp-payment-form'),
                    'postion_group' => 'payment',
                    'type' => 'tabular_products',
                ),
                'recurring_payment_item' => array(
                    'editor_title' => __('Subscription Payment', 'wp-payment-form'),
                    'postion_group' => 'payment',
                    'type' => 'recurring_payment_item',
                ),
                'tax_payment_input' => array(
                    'type' => 'tax_payment_input',
                    'editor_title' => 'Tax Calculated Amount',
                    'postion_group' => 'payment',
                ),
                'donation_item' => array(
                    'type' => 'donation_item',
                    'editor_title' => 'Donation Progress Item',
                    'postion_group' => 'payment',
                ),
                'coupon' => array(
                    'type' => 'coupon',
                    'editor_title' => 'Coupon',
                    'group' => 'payment',
                    'postion_group' => 'payment',
                ),
                'currency_switcher' => array(
                    'type' => 'currency_switcher',
                    'editor_title' => 'Currency Switcher',
                    'group' => 'payment',
                    'postion_group' => 'payment',
                ),

            ),
            'editor_elements' => array(
                'label' => array(
                    'label' => 'Field Label',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'placeholder' => array(
                    'label' => 'Placeholder',
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
            ),
            'field_options' => array(
                'label' => 'Coupon Code',
                'placeholder' => '',
                'required' => 'no'
            )
        );

        $hasTable = get_option('wppayform_coupon_status', false);

        if (!$hasTable == 'yes') {
            $migrateInfo = array(
                'migrate' => true,
                'migrate_message' => 'Please activate coupon module from Payment settings. And reload this page.',
                'url' => admin_url('admin.php?page=wppayform_settings#coupons'),
                'btnText' => 'Activate Coupon Module'
            );

            $components = array_merge($components, $migrateInfo);
        }

        return $components;
    }

    public function render($element, $form, $elements)
    {
        return '';
    }
}
