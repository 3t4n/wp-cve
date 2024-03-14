<?php
if (!function_exists('is_plugin_active'))
    require_once(ABSPATH . '/wp-admin/includes/plugin.php');

class OfficeGuyMultiVendor
{
    private static $Marketplaces;

    public static function InitMarketplacePlugins()
    {
        if (!isset(self::$Marketplaces))
            self::$Marketplaces = array(new OfficeGuyDokanMarketplace(), new OfficeGuyWCFMMarketplace(), new OfficeGuyWCVendorsMarketplace());
    }

    public static function GetProductVendorCredentials()
    {
        foreach (self::$Marketplaces as $Plugin)
            if ($Plugin->PluginIsActive())
                return $Plugin->GetProductVendorCredentials();
        return null;
    }

    public static function PluginIsActive()
    {
        foreach (self::$Marketplaces as $Plugin)
            if ($Plugin->PluginIsActive())
                return true;
        return false;
    }

    public static function HasVendorInCart()
    {
        foreach (self::$Marketplaces as $Plugin)
            if ($Plugin->PluginIsActive() && $Plugin->VendorsInCartCount() > 0)
                return true;
        return false;
    }

    public static function HasMultipleVendorsInCart()
    {
        foreach (self::$Marketplaces as $Plugin)
            if ($Plugin->PluginIsActive() && $Plugin->VendorsInCartCount() > 1)
                return true;
        return false;
    }

    public static function UpdateAvailableGateways($AvailableGateways)
    {
        if (!is_checkout() || empty($AvailableGateways))
            return $AvailableGateways;

        if (OfficeguyMultiVendor::HasVendorInCart())
        {
            if (isset($AvailableGateways['officeguybit']))
                unset($AvailableGateways['officeguybit']);

            $OfficeGuyGateway = GetOfficeGuyGateway();
            if (isset($AvailableGateways['officeguy']) && $OfficeGuyGateway->settings['authorizeonly'] == 'yes')
                unset($AvailableGateways['officeguy']);
        }

        return $AvailableGateways;
    }
}

OfficeguyMultiVendor::InitMarketplacePlugins();
add_filter('woocommerce_available_payment_gateways', 'OfficeguyMultiVendor::UpdateAvailableGateways');
