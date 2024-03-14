<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Emails;

use Exception;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1\SV_WC_Helper;

class Emails
{
    /**
     * Emails handler constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->addHooks();
    }

    /**
     * Adds the hooks to handle ready for pickup emails.
     *
     * @since 1.3.0
     *
     * @throws Exception
     */
    public function addHooks()
    {
        add_filter('woocommerce_email_classes', [$this, 'addReadyForPickupEmail']);
    }

    /**
     * Adds our email to the list of emails WooCommerce should load.
     *
     * @since 1.3.0
     *
     * @internal
     *
     * @param array $emailClasses available email classes
     * @return array filtered available email classes
     * @throws Exception
     */
    public function addReadyForPickupEmail(array $emailClasses) : array
    {
        return SV_WC_Helper::array_insert_after($emailClasses, 'WC_Email_Customer_Completed_Order', ['ReadyForPickupEmail' => new ReadyForPickupEmail()]);
    }
}
