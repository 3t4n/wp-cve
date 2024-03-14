<?php

namespace Wcd\DiscountRules;
use Wcd\DiscountRules\Types\ProductCategory;

if (!defined('ABSPATH')) exit;

class Main
{
    public static $init;
    private $Wcd,$rule_table;

    /**
     * Initiate the Plugin
     * @return Main
     */
    public static function instance()
    {
        return self::$init = (self::$init === NULL) ? new self() : self::$init;
    }

    /**
     * Main constructor.
     */
    function __construct()
    {
        $this->Wcd = new DiscountRules();
        $this->rule_table = new ProductCategory();
        $this->activateEvents();
    }

    /**
     * Activate the required actions
     */
    function activateEvents()
    {
        //Check for dependencies
        add_action('plugins_loaded', array($this, 'checkDependencies'));
        //Activate CMB2 functions
        add_action('init', function () {
            $this->Wcd->init();
        });
        //Override the Item Price on product page
        add_action('woocommerce_get_price_html', array($this->Wcd, 'overrideItemPrice'), 100, 2);
        //Override the Cart item price
        add_action('woocommerce_cart_item_price', array($this->Wcd, 'overrideCartItemPrice'), 100, 2);
        //Reset the original price with discounted price
        add_action('woocommerce_before_calculate_totals', array($this->Wcd, 'initDiscountAdjustments'));
        // Show the applied discount message in cart and checkout page
        add_action('woocommerce_before_checkout_form', array($this->Wcd, 'displayAppliedDiscountMessage'));
        add_action('woocommerce_before_cart', array($this->Wcd, 'displayAppliedDiscountMessage'));
        //Add discount details of the order to item meta
        add_action('woocommerce_add_order_item_meta', array($this->Wcd, 'addDiscountsToMetaData'), 100, 1);
        //Show discount details for users
        add_action('woocommerce_order_item_meta_start', array($this->Wcd, 'showDiscountDetailsInOrders'), 100, 2);
        //Hide order item meta for admin
        add_action('woocommerce_hidden_order_itemmeta', array($this->Wcd, 'hideMetaDetailsOnAdminOrderPage'), 100, 1);
        //Show applied discounts in admin order page
        add_action('woocommerce_admin_order_totals_after_discount', array($this->Wcd, 'adminOrderItemDiscountDetails'), 100, 1);
        //Add js
        add_action('admin_enqueue_scripts', array($this->Wcd, 'adminEnqueueJs'));
        //Settings link
        add_filter('plugin_action_links_' . WCD_BASE_FILE, array($this->Wcd, 'pluginActionLinks'));
        //Rule Table
        $tablePlacementSetting = $this->rule_table->tablePlacementSetting();
        if($tablePlacementSetting === 'before_cart'){
            add_action('woocommerce_before_add_to_cart_form', array($this->Wcd, 'discountPriceTable'), 10, 0);
        }elseif($tablePlacementSetting === 'after_cart'){
            add_filter('woocommerce_after_add_to_cart_form', array($this->Wcd, 'discountPriceTable'),10, 0);
        }
        //min max Rule
        add_action( 'woocommerce_before_calculate_totals', array( $this->Wcd, 'minMaxDiscountRule'), 100, 0);

    }

    /**
     * Dependency check for our plugin
     */
    function checkDependencies()
    {
        if (!defined('WC_VERSION')) {
            $this->showAdminNotice(__('Woocommerce must be activated for Woocommerce category discount to work', WCD_TEXT_DOMAIN));
        } else {
            if (version_compare(WC_VERSION, '2.0', '<')) {
                $this->showAdminNotice('Your woocommerce version is ' . WC_VERSION . '. Some of the features of Woocommerce category discount will not work properly on this woocommerce version.');
            }
        }

        //load_plugin_textdomain( WCD_TEXT_DOMAIN, false, WCD_LANGUAGE_PATH );

    }

    /**
     * Show notices for user..if anything unusually happen in our plugin
     * @param string $message - message to notice users
     */
    function showAdminNotice($message = "")
    {
        if (!empty($message)) {
            add_action('admin_notices', function () use ($message) {
                echo '<div class="error notice"><p>' . $message . '</p></div>';
            });
        }
    }
}
