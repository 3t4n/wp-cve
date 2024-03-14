<?php

namespace Hyperpay\Gateways;
//use class here

use Hyperpay\Gateways\App\Hyperpay_Blocks_Support;
use Hyperpay\Gateways\App\Log;
use Hyperpay\Gateways\App\Webhook;
use Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry;
use Exception;
use Hyperpay\Gateways\App\View;

class Main
{

    public const ROOT_PATH = __DIR__;
    /**
     * First function fire when click on active plugin
     * 
     * @return void
     */

    public static function load(): void
    {

        // do not load the plugin if wooCommerce not installed
        if (!self::isWooCommerceLoaded()) return;


        /**
         * this filter documented in wooCommerce to assign all gateways to [payments tab] inside wooCommerce settings
         * 
         * @param string filter_name 
         * @param array[class_name,function_name]
         * @return void
         */

        add_filter('woocommerce_payment_gateways', [self::class, 'get_gateways']);
        add_action('rest_api_init', [Webhook::class, 'hyperpay_rest_orders']); //register the webhook class


        // Registers WooCommerce Blocks integration.
        add_action('woocommerce_blocks_loaded', [self::class, 'my_extension_woocommerce_blocks_support']);


        if (is_admin()) {
            add_action('admin_menu', function () {
                add_options_page('Hyperpay logs', 'Hyperpay logs', 'manage_options', 'hyperpay-logs', [Log::class, "load"]);
                add_options_page('Hyperpay Options', 'Hyperpay Options', 'manage_options', 'hyperpay-options', [Main::class, "settings"]);
            });
        }
    }


    public static function settings()
    {
        if (isset($_POST['gateways'])) {

            $gateways = "";

            foreach ($_POST['gateways'] as $gateway) {
                if (\class_exists("\\Hyperpay\\Gateways\\Brands\\$gateway")) {
                    $gateways  .=  "\t\\Hyperpay\\Gateways\\Brands\\$gateway::class,\n";
                }
            };

            if ($gateways) {
                $content = "<?php\nreturn [\n$gateways];";
                file_put_contents(self::ROOT_PATH . "/Settings.php", $content);
            }
        }

        $selected = require(Main::ROOT_PATH . '/Settings.php');
        $selected = array_map(function ($class) {
            return \str_replace("Hyperpay\\Gateways\\Brands\\", "", $class);
        }, $selected);


        $files = scandir(Main::ROOT_PATH . "/Brands");
        $files = \array_filter($files, function ($name) {
            return !preg_match('/^[.]/', $name);
        });

        $files = array_map(function ($file) {
            return \str_replace(".php", "", $file);
        }, $files);

        return View::render('settings.html', \compact('selected', 'files'));
    }



    /**
     * Check if the wooCommerce plugin is installed and active
     * @return boolean
     */

    private static function isWooCommerceLoaded()
    {
        if (!class_exists('woocommerce')) {

            add_action(
                'admin_notices',
                function () {
?>
                <div class="notice notice-error">
                    <p>
                        <?php
                        printf(
                            esc_html__('Hyperpay plugin could not be loaded without wooCommerce %1$splease install wooCommerce first %2$s',  'hyperpay-payments'),
                            '<a href="' . esc_url('/wp-admin/plugin-install.php?s=WooCommerce&tab=search&type=term') . '" rel="noopener noreferrer">',
                            '</a>'
                        );
                        ?>
                    </p>
                </div>
<?php
                }
            );
            return false;
        }
        return true;
    }


    /**
     * Merge the previous gateways with hyperPay gateways
     * 
     * @param array $gateways
     * @return array $updated_gateways
     */

    public static function get_gateways(array $gateways): array
    {
        $HP_gateways = require_once(Main::ROOT_PATH . '/Settings.php');

        return array_merge($gateways, $HP_gateways);
    }


    public static function my_extension_woocommerce_blocks_support()
    {
        if (class_exists('Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType')) {
            add_action(
                'woocommerce_blocks_payment_method_type_registration',
                function (PaymentMethodRegistry $payment_method_registry) {
                    $payment_method_registry->register(new Hyperpay_Blocks_Support);
                }
            );
        }
    }
}
