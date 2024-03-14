<?php

/**
 * Class BlackoutLeadDaysSettingsDefinitionDecoratorFactory
 *
 * @package WPDesk\AbstractShipping\Settings\SettingsDecorators
 */
namespace UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDecorators;

/**
 * Can create Blackout Lead Days settings decorator.
 */
class BlackoutLeadDaysSettingsDefinitionDecoratorFactory extends \UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDecorators\AbstractDecoratorFactory
{
    const OPTION_ID = 'blackout_lead_days';
    /**
     * @return string
     */
    public function get_field_id()
    {
        return self::OPTION_ID;
    }
    /**
     * @return array
     */
    protected function get_field_settings()
    {
        return array('title' => \__('Blackout Lead Days', 'flexible-shipping-ups'), 'type' => 'multiselect', 'description' => \__('Blackout Lead Days are used to define days of the week when shop is not processing orders.', 'flexible-shipping-ups'), 'options' => array('1' => \__('Monday', 'flexible-shipping-ups'), '2' => \__('Tuesday', 'flexible-shipping-ups'), '3' => \__('Wednesday', 'flexible-shipping-ups'), '4' => \__('Thursday', 'flexible-shipping-ups'), '5' => \__('Friday', 'flexible-shipping-ups'), '6' => \__('Saturday', 'flexible-shipping-ups'), '7' => \__('Sunday', 'flexible-shipping-ups')), 'custom_attributes' => array('size' => 7), 'class' => 'wc-enhanced-select', 'desc_tip' => \true, 'default' => '');
    }
}
