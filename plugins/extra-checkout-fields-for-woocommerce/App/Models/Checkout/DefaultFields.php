<?php

namespace ECFFW\App\Models\Checkout;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class DefaultFields
{
    /**
     * Default Address Fields.
     */
    public static function address()
    {
        return array(
            (object) array(
                'type' => 'text',
                'required' => true,
                'label' => __('First name', 'extra-checkout-fields-for-woocommerce'),
                'name' => 'first_name',
                'subtype' => 'text',
                'rowType' => 'form-row-first',
            ),
            (object) array(
                'type' => 'text',
                'required' => true,
                'label' => __('Last name', 'extra-checkout-fields-for-woocommerce'),
                'name' => 'last_name',
                'subtype' => 'text',
                'rowType' => 'form-row-last',
            ),
            (object) array(
                'type' => 'text',
                'required' => false,
                'label' => __('Company name', 'extra-checkout-fields-for-woocommerce'),
                'name' => 'company',
                'subtype' => 'text',
                'rowType' => 'form-row-wide',
            ),
            (object) array(
                'type' => 'text',
                'required' => true,
                'label' => __('Country / Region', 'extra-checkout-fields-for-woocommerce'),
                'name' => 'country',
                'subtype' => 'country',
                'rowType' => 'form-row-wide address-field',
            ),
            (object) array(
                'type' => 'text',
                'required' => true,
                'label' => 'Street address',
                'placeholder' => esc_attr__('House number and street name', 'extra-checkout-fields-for-woocommerce'),
                'name' => 'address_1',
                'subtype' => 'text',
                'rowType' => 'form-row-wide address-field',
            ),
            (object) array(
                'type' => 'text',
                'required' => false,
                'label' => __('Apartment, suite, unit, etc.', 'extra-checkout-fields-for-woocommerce'),
                'placeholder' => esc_attr(__('Apartment, suite, unit, etc.', 'extra-checkout-fields-for-woocommerce')),
                'name' => 'address_2',
                'subtype' => 'text',
                'rowType' => 'form-row-wide address-field',
            ),
            (object) array(
                'type' => 'text',
                'required' => true,
                'label' => __('Town / City', 'extra-checkout-fields-for-woocommerce'),
                'name' => 'city',
                'subtype' => 'text',
                'rowType' => 'form-row-wide address-field',
            ),
            (object) array(
                'type' => 'text',
                'required' => true,
                'label' => __('State / County', 'extra-checkout-fields-for-woocommerce'),
                'name' => 'state',
                'subtype' => 'state',
                'rowType' => 'form-row-wide address-field',
            ),
            (object) array(
                'type' => 'text',
                'required' => true,
                'label' => __('Postcode / ZIP', 'extra-checkout-fields-for-woocommerce'),
                'name' => 'postcode',
                'subtype' => 'text',
                'rowType' => 'form-row-wide address-field',
            ),
        );
    }

    /**
     * Default Billing Fields.
     */
    public static function billing()
    {
        $fields = self::address();

        foreach ($fields as &$field) {
            $field->name = 'billing_' . $field->name;
        }

        $fields[] = (object) array(
            'type' => 'text',
            'required' => true,
            'label' => __('Phone', 'extra-checkout-fields-for-woocommerce'),
            'name' => 'billing_phone',
            'subtype' => 'tel',
            'rowType' => 'form-row-wide',
        );

        $fields[] = (object) array(
            'type' => 'text',
            'required' => true,
            'label' => __('Email address', 'extra-checkout-fields-for-woocommerce'),
            'name' => 'billing_email',
            'subtype' => 'email',
            'rowType' => 'form-row-wide',
        );

        return $fields;
    }

    /**
     * Default Shipping Fields.
     */
    public static function shipping()
    {
        $fields = self::address();

        foreach ($fields as &$field) {
            $field->name = 'shipping_' . $field->name;
        }

        return $fields;
    }

    /**
     * Default Order Fields.
     */
    public static function order()
    {
        $fields = [];

        $fields[] = (object) array(
            'type' => 'textarea',
            'required' => false,
            'label' => __('Order notes', 'extra-checkout-fields-for-woocommerce'),
            'name' => 'order_comments',
            'placeholder' => esc_attr__(
                'Notes about your order, e.g. special notes for delivery.',
                'extra-checkout-fields-for-woocommerce'
            ),
            'rowType' => 'notes',
        );

        return $fields;
    }
}
