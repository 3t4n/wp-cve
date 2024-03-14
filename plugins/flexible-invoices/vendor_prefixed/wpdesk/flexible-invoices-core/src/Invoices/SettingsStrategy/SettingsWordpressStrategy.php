<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
/**
 * WordPress Settings.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Strategy
 */
class SettingsWordpressStrategy extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\AbstractSettingsStrategy
{
    /**
     * @param Settings $settings
     */
    /**
     * @return array
     */
    public function get_order_statuses() : array
    {
        return ['' => \esc_html__('Do not issue', 'flexible-invoices')];
    }
}
