<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt;

use GoDaddy\WooCommerce\Poynt\REST\Controllers\SupportController;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1\Payment_Gateway\REST_API;

defined('ABSPATH') or exit;

/**
 * The WooCommerce REST API handler.
 *
 * @since 1.2.0
 */
class REST extends REST_API
{
    /** @var SupportController|null instance */
    private $supportController;

    /**
     * Registers new WooCommerce REST API routes.
     *
     * @since 1.2.0
     */
    public function register_routes()
    {
        parent::register_routes();

        $this->getSupportController()->register_routes();
    }

    /**
     * Gets the support controller instance.
     *
     * @since 1.2.0
     *
     * @return SupportController
     */
    public function getSupportController() : SupportController
    {
        if (null === $this->supportController) {
            $this->supportController = new SupportController();
        }

        return $this->supportController;
    }
}
