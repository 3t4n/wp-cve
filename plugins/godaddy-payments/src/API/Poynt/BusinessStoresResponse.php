<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API\Poynt;

use GoDaddy\WooCommerce\Poynt\API\Responses\AbstractResponse;
use GoDaddy\WooCommerce\Poynt\Helpers\PoyntHelper;

defined('ABSPATH') or exit;

/**
 * Store devices response.
 *
 * @since 1.3.1
 */
class BusinessStoresResponse extends AbstractResponse
{
    /**
     * Gets the store devices.
     *
     * @since 1.3.1
     *
     * @return array
     */
    public function getStoreDevices()
    {
        if (! is_array($this->response_data)) {
            return [];
        }

        return $this->response_data[0]->storeDevices ?? [];
    }

    /**
     * Gets the store devices.
     *
     * @since 1.3.1
     *
     * @return string|null
     */
    public function getStoreId()
    {
        $storeId = null;

        $storeDevices = $this->getStoreDevices();

        if ($storeDevices && isset($storeDevices[0])) {
            $storeId = $storeDevices[0]->storeId;
        }

        return $storeId;
    }

    /**
     * Determines if the user has any activated Poynt smart terminal.
     *
     * @throws Exception
     *
     * @return bool has any active terminal devices?
     */
    public function hasActiveTerminalDevices() : bool
    {
        foreach ($this->getStoreDevices() as $device) {
            if (! PoyntHelper::isActivePoyntSmartTerminal($device)) {
                continue;
            }

            // @NOTE: Return early here as we have already found an active device
            return true;
        }

        return false;
    }
}
