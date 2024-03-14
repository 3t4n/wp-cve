<?php

namespace WcMipConnector\View\Module;

defined('ABSPATH') || exit;

use WcMipConnector\Service\ShippingService;
use WcMipConnector\View\Module\ShippingService\ShippingServiceList;

class ShippingServiceView
{
    /** @var ShippingService  */
    protected $shippingService;
    /** @var ShippingServiceList  */
    protected $shippingServiceView;

    public function __construct()
    {
        $this->shippingService = new ShippingService();
        $this->shippingServiceView = new ShippingServiceList();
    }

    public function showView(): void
    {
        $this->shippingService->updateShippingServices();
        ?>
            <div id="shipping-body">
                <form id="saveConfiguration" method="post">
                    <h3><?php esc_html_e('Shipping Services', 'WC-Mipconnector');?></h3>
                    <?php
                        $this->shippingServiceView->loadShippingServices();
                    ?>
                </form>
            </div>
        <?php
    }
}


