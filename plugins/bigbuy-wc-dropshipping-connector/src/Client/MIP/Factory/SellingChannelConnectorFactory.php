<?php
/**
 * @author    BigBuy
 * @copyright 2010-2020 BigBuy
 * @license   license
 */

namespace WcMipConnector\Client\MIP\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Client\MIP\Exception\SellingChannelConnectorException;
use WcMipConnector\Client\MIP\Model\SellingChannelConnector;

class SellingChannelConnectorFactory
{
    /** @var SellingChannelConnectorFactory */
    private static $instance;

    /**
     * @return SellingChannelConnectorFactory
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param array $sellingChannelConnectorData
     * @return SellingChannelConnector
     * @throws SellingChannelConnectorException
     */
    public function create(array $sellingChannelConnectorData)
    {
        $sellingChannelConnector = new SellingChannelConnector();

        if (!\array_key_exists('url', $sellingChannelConnectorData)) {
            throw new SellingChannelConnectorException('Missing url index in data');
        }

        if (!\array_key_exists('version', $sellingChannelConnectorData)) {
            throw new SellingChannelConnectorException('Missing version index in data');
        }

        $sellingChannelConnector->url = $sellingChannelConnectorData['url'];

        if (\array_key_exists('name', $sellingChannelConnectorData)) {
            $sellingChannelConnector->name = $sellingChannelConnectorData['name'];
        }

        $sellingChannelConnector->version = $sellingChannelConnectorData['version'];

        return $sellingChannelConnector;
    }
}
