<?php

namespace DhlVendor\WPDesk\WooCommerceShipping\ThirdParty\Germanized;

use DhlVendor\WPDesk\Notice\Notice;
use DhlVendor\WPDesk\PluginBuilder\Plugin\Hookable;
class TaxSettingsNotice implements \DhlVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    private string $plugin_name = '';
    private string $link_url = '';
    public function __construct(string $plugin_name, string $link_url)
    {
        $this->plugin_name = $plugin_name;
        $this->link_url = $link_url;
    }
    public function hooks()
    {
        \add_action('admin_notices', [$this, 'show_notice_when_needed']);
    }
    public function show_notice_when_needed()
    {
        $function_name = 'wc_gzd_get_additional_costs_tax_calculation_mode';
        if (\function_exists($function_name) && wc_gzd_get_additional_costs_tax_calculation_mode() !== 'none') {
            $this->show_notice();
        }
    }
    private function show_notice()
    {
        (new \DhlVendor\WPDesk\Notice\Notice($this->get_notice_content(), \DhlVendor\WPDesk\Notice\Notice::NOTICE_TYPE_WARNING))->showNotice();
    }
    private function get_notice_content() : string
    {
        return \sprintf(\__('We noticed that you are using the Germanized plugin along with %1$s. In order to display the accurate rates, please select the WooCommerce default option in the Germanized Taxes > Additional Costs > Tax calculation mode settings. %2$sNeed assistance? Get in touch →%3$s', 'flexible-shipping-dhl-express'), $this->plugin_name, '<a href="' . $this->link_url . '" target="_blank">', '</a>');
    }
}
