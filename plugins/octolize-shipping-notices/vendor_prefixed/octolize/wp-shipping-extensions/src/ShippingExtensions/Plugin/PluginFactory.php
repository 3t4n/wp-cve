<?php

namespace OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin;

class PluginFactory
{
    const CATEGORY_ALL = 'all';
    const CATEGORY_LIVE_RATES = 'live_rates';
    const CATEGORY_CUSTOMIZABLE_RATES = 'customizable_rates';
    const CATEGORY_SHIPPING_LABELS = 'shipping_labels';
    const GROUP_FS = \OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin\Plugin::DEFAULT_GROUP;
    const GROUP_UPS = 'ups';
    /**
     * @return Plugin[]
     */
    public static function get_plugins() : array
    {
        $categories = self::get_categories();
        $plugins = [];
        $plugin = new \OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin\Plugin(\__('Flexible Shipping PRO', 'octolize-shipping-notices'), \__('The best and the most powerful Table Rate shipping plugin for WooCommerce. Define the shipping rules based on numerous conditions and configure even the most complex shipping scenarios with ease.', 'octolize-shipping-notices'), 'flexible-shipping-pro.svg', 'flexible-shipping-pro/flexible-shipping-pro.php', $categories[self::CATEGORY_CUSTOMIZABLE_RATES], ' https://octol.io/fs-extensions');
        $plugin->add_url('https://octol.io/fs-extensions-pl', 'pl_PL');
        $plugins[] = $plugin;
        $plugin = new \OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin\Plugin(\__('UPS Live Rates and Access Points PRO', 'octolize-shipping-notices'), \__('WooCommerce UPS integration packed with many advanced features. Display the dynamically calculated live rates for UPS shipping methods and adjust them to your needs.', 'octolize-shipping-notices'), 'flexible-shipping-ups-pro.svg', 'flexible-shipping-ups-pro/flexible-shipping-ups-pro.php', $categories[self::CATEGORY_LIVE_RATES], 'https://octol.io/ups-extensions', self::GROUP_UPS);
        $plugin->add_url('https://octol.io/ups-extensions-pl', 'pl_PL');
        $plugins[] = $plugin;
        $plugin = new \OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin\Plugin(\__('UPS Labels and Tracking', 'octolize-shipping-notices'), \__('Create the shipments, generate the printable UPS shipping labels for the placed orders and track the parcels directly from your WooCommerce store.', 'octolize-shipping-notices'), 'flexible-shipping-ups-labels.svg', 'flexible-shipping-ups-labels/flexible-shipping-ups-labels.php', $categories[self::CATEGORY_SHIPPING_LABELS], 'https://octol.io/ups-labels-extensions', self::GROUP_UPS);
        $plugin->add_url('https://octol.io/ups-labels-extensions-pl', 'pl_PL');
        $plugins[] = $plugin;
        $plugin = new \OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin\Plugin(\__('FedEx WooCommerce Live Rates PRO', 'octolize-shipping-notices'), \__('Enable the FedEx live rates for international delivery and integrate it with your shop in less than 5 minutes. Save your time and money – let the shipping cost be calculated automatically.', 'octolize-shipping-notices'), 'flexible-shipping-fedex-pro.svg', 'flexible-shipping-fedex-pro/flexible-shipping-fedex-pro.php', $categories[self::CATEGORY_LIVE_RATES], 'https://octol.io/fedex-extensions', self::GROUP_UPS);
        $plugin->add_url('https://octol.io/fedex-extensions-pl', 'pl_PL');
        $plugins[] = $plugin;
        $plugin = new \OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin\Plugin(\__('Distance Based Shipping Rates', 'octolize-shipping-notices'), \__('Offer shipping rates based on Distance or Total Travel Time calculated by Google Distance Matrix API and don\'t overpay for shipping.', 'octolize-shipping-notices'), 'octolize-distance-based-shipping-rates.svg', 'octolize-distance-based-shipping-rates/octolize-distance-based-shipping-rates.php', $categories[self::CATEGORY_CUSTOMIZABLE_RATES], 'https://octol.io/dbs-extensions');
        $plugin->add_url('https://octol.io/dbs-extensions-pl', 'pl_PL');
        $plugins[] = $plugin;
        $plugin = new \OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin\Plugin(\__('Flexible Shipping Locations', 'octolize-shipping-notices'), \__('Calculate the shipping cost based on location. Define your own custom locations, use the WooCommerce defaults or the ones created by 3rd party plugins.', 'octolize-shipping-notices'), 'flexible-shipping-locations.svg', 'flexible-shipping-locations/flexible-shipping-locations.php', $categories[self::CATEGORY_CUSTOMIZABLE_RATES], 'https://octol.io/locations-extensions');
        $plugin->add_url('https://octol.io/locations-extensions-pl', 'pl_PL');
        $plugins[] = $plugin;
        $plugin = new \OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin\Plugin(\__('Flexible Printing', 'octolize-shipping-notices'), \__('Automate your shipping process. Print the shipping labels on thermal printers via PrintNode service. Let the labels be printed automatically the same time the order is placed.', 'octolize-shipping-notices'), 'flexible-printing.svg', 'flexible-printing/flexible-printing.php', $categories[self::CATEGORY_SHIPPING_LABELS], 'https://octol.io/printing-extensions');
        $plugin->add_url('https://octol.io/printing-extensions-pl', 'pl_PL');
        $plugins[] = $plugin;
        $plugin = new \OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin\Plugin(\__('Flexible Shipping Import / Export', 'octolize-shipping-notices'), \__('Use the CSV files to import or export your shipping methods. Edit, update, move or backup the ready configurations and shipping scenarios.', 'octolize-shipping-notices'), 'flexible-shipping-import-export.svg', 'flexible-shipping-import-export/flexible-shipping-import-export.php', $categories[self::CATEGORY_CUSTOMIZABLE_RATES], 'https://octol.io/fsie-extensions');
        $plugin->add_url('https://octol.io/fsie-extensions-pl', 'pl_PL');
        $plugins[] = $plugin;
        $plugin = new \OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin\Plugin(\__('DHL Express Live Rates PRO', 'octolize-shipping-notices'), \__('WooCommerce DHL Express integration packed with many advanced features. Display the dynamically calculated live rates for DHL Express shipping methods and adjust them to your needs.', 'octolize-shipping-notices'), 'flexible-shipping-dhl-express-pro.svg', 'flexible-shipping-dhl-express-pro/flexible-shipping-dhl-express-pro.php', $categories[self::CATEGORY_LIVE_RATES], 'https://octol.io/dhl-express-extensions', self::GROUP_UPS);
        $plugin->add_url('https://octol.io/dhl-express-extensions-pl', 'pl_PL');
        $plugins[] = $plugin;
        $plugin = new \OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin\Plugin(\__('Multi Vendor Shipping', 'octolize-shipping-notices'), \__('Define precisely the shipping cost calculation rules for each Vendor / Product Author in your marketplace or multivendor store.', 'octolize-shipping-notices'), 'flexible-shipping-vendors.svg', 'flexible-shipping-vendors/flexible-shipping-vendors.php', $categories[self::CATEGORY_CUSTOMIZABLE_RATES], 'https://octol.io/mvs-extensions', self::GROUP_UPS);
        $plugin->add_url('https://octol.io/mvs-extensions-pl', 'pl_PL');
        $plugins[] = $plugin;
        $plugin = new \OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin\Plugin(\__('Shipping Packages', 'octolize-shipping-notices'), \__('Split the WooCommerce cart content into multiple packages based on various conditions like shipping class.', 'octolize-shipping-notices'), 'flexible-shipping-packages.svg', 'flexible-shipping-packages/flexible-shipping-packages.php', $categories[self::CATEGORY_CUSTOMIZABLE_RATES], 'https://octol.io/packages-extensions');
        $plugin->add_url('https://octol.io/packages-extensions-pl', 'pl_PL');
        $plugins[] = $plugin;
        $plugin = new \OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin\Plugin(\__('Conditional Shipping Methods', 'octolize-shipping-notices'), \__('Conditionally display and hide the shipping methods in your WooCommerce store. Define the rules when the specific shipping methods, e.g., live rates should be available to pick and when not to.', 'octolize-shipping-notices'), 'flexible-shipping-conditional-methods.svg', 'flexible-shipping-conditional-methods/flexible-shipping-conditional-methods.php', $categories[self::CATEGORY_LIVE_RATES], 'https://octol.io/csm-extensions');
        $plugin->add_url('https://octol.io/csm-extensions-pl', 'pl_PL');
        $plugins[] = $plugin;
        $plugin = new \OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin\Plugin(\__('Delivery Date Picker', 'octolize-shipping-notices'), \__('Let your customers choose a convenient delivery date for the ordered products and make the shipping cost dependent on the date they choose.', 'octolize-shipping-notices'), 'octolize-delivery-date-picker.svg', 'octolize-delivery-date-picker/octolize-delivery-date-picker.php', $categories[self::CATEGORY_CUSTOMIZABLE_RATES], 'https://octol.io/ddp-extensions');
        $plugin->add_url('https://octol.io/ddp-extensions-pl', 'pl_PL');
        $plugins[] = $plugin;
        $plugin = new \OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin\Plugin(\__('USPS Live Rates PRO', 'octolize-shipping-notices'), \__('Serve your customers the automatically and real-time calculated USPS shipping rates. Add the handling fees, insurance and adjust them to your needs with just a few clicks.', 'octolize-shipping-notices'), 'flexible-shipping-usps-pro.svg', 'flexible-shipping-usps-pro/flexible-shipping-usps-pro.php', $categories[self::CATEGORY_LIVE_RATES], 'https://octol.io/usps-extensions', self::GROUP_UPS);
        $plugin->add_url('https://octol.io/usps-extensions-pl', 'pl_PL');
        $plugins[] = $plugin;
        $plugin = new \OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin\Plugin(\__('Shipping Cost on Product Page PRO', 'octolize-shipping-notices'), \__('Let your customers calculate and see the shipping cost on product pages based on the entered shipping destination and cart contents. Decide how and when exactly you want the shipping cost calculator to display.', 'octolize-shipping-notices'), 'octolize-shipping-cost-on-product-page-pro.svg', 'octolize-shipping-cost-on-product-page-pro/octolize-shipping-cost-on-product-page-pro.php', $categories[self::CATEGORY_CUSTOMIZABLE_RATES], 'https://octol.io/scpp-extensions');
        $plugin->add_url('https://octol.io/scpp-extensions-pl', 'pl_PL');
        $plugins[] = $plugin;
        $plugin = new \OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin\Plugin(\__('Flexible Shipping Box Packing', 'octolize-shipping-notices'), \__('Use the advanced box packing WooCommerce algorithm to fit the ordered products into your shipping boxes the most optimal way. Configure the shipping cost based on the type and number of the used shipping boxes.', 'octolize-shipping-notices'), 'octolize-box-packing.svg', 'octolize-box-packing/octolize-box-packing.php', $categories[self::CATEGORY_CUSTOMIZABLE_RATES], 'https://octol.io/bp-extensions');
        $plugin->add_url('https://octol.io/bp-extensions-pl', 'pl_PL');
        $plugins[] = $plugin;
        $plugin = new \OctolizeShippingNoticesVendor\Octolize\ShippingExtensions\Plugin\Plugin(\__('DPD UK & DPD Local', 'octolize-shipping-notices'), \__('Ship your DPD orders faster with advanced DPD UK & DPD Local WooCommerce integration. Gather shipping details, download printable shipping labels and track parcels - everything is automated.', 'octolize-shipping-notices'), 'woocommerce-dpd-uk.svg', 'woocommerce-dpd-uk/woocommerce-dpd-uk.php', $categories[self::CATEGORY_SHIPPING_LABELS], 'https://octol.io/dpd-uk-extensions');
        $plugin->add_url('https://octol.io/dpd-uk-extensions-pl', 'pl_PL');
        $plugins[] = $plugin;
        return self::filter_plugins($plugins);
    }
    /**
     * @param Plugin[] $plugins
     *
     * @return Plugin[]
     */
    private static function filter_plugins(array $plugins) : array
    {
        $active_plugins = \get_option('active_plugins', array());
        foreach ($plugins as $key => $plugin) {
            if (\in_array($plugin->get_plugin_file(), $active_plugins, \true)) {
                unset($plugins[$key]);
            }
        }
        return $plugins;
    }
    /**
     * @return array
     */
    public static function get_categories() : array
    {
        return [self::CATEGORY_ALL => \__('All', 'octolize-shipping-notices'), self::CATEGORY_LIVE_RATES => \__('Live Rates', 'octolize-shipping-notices'), self::CATEGORY_CUSTOMIZABLE_RATES => \__('Customizable Rates', 'octolize-shipping-notices'), self::CATEGORY_SHIPPING_LABELS => \__('Shipping Labels', 'octolize-shipping-notices')];
    }
}
