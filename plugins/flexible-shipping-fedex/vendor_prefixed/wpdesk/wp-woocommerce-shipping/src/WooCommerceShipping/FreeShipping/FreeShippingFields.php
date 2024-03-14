<?php

namespace FedExVendor\WPDesk\WooCommerceShipping\FreeShipping;

/**
 * Can replace fake free_shipping field with custom free shipping fields to shipping method settings fields.
 *
 * @package WPDesk\WooCommerceShipping\FreeShipping
 */
class FreeShippingFields
{
    const FIELD_TYPE_FREE_SHIPPING = 'free_shipping';
    const FIELD_STATUS = 'free_shipping_status';
    const FIELD_AMOUNT = 'free_shipping_amount';
    /**
     * Replace free_shipping fake field with checkbox and input fields in settings.
     *
     * @param array $settings
     *
     * @return array
     */
    public function replace_fields(array $settings)
    {
        $new_settings = [];
        foreach ($settings as $key => $field) {
            if ($field['type'] === self::FIELD_TYPE_FREE_SHIPPING) {
                $new_settings[self::FIELD_STATUS] = ['title' => \__('Free Shipping', 'flexible-shipping-fedex'), 'type' => 'checkbox', 'label' => \__('Enable the free shipping over amount', 'flexible-shipping-fedex'), 'description' => \__('Tick this checkbox to enter the order amount above which the shipping becomes free.', 'flexible-shipping-fedex'), 'desc_tip' => \true, 'default' => 'no'];
                $new_settings[self::FIELD_AMOUNT] = ['title' => \__('Free Shipping Threshold', 'flexible-shipping-fedex'), 'type' => 'number', 'required' => \true, 'default' => '', 'description' => \__('Enter only a numeric value without the currency symbol.', 'flexible-shipping-fedex'), 'desc_tip' => \true, 'custom_attributes' => ['min' => 0, 'step' => 'any']];
            } else {
                $new_settings[$key] = $field;
            }
        }
        return $new_settings;
    }
}
