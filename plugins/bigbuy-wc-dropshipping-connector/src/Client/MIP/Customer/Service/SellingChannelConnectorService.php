<?php
/**
 * @author    BigBuy
 * @copyright 2010-2020 BigBuy
 * @license   license
 */

namespace WcMipConnector\Client\MIP\Customer\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Client\Base\Exception\ClientErrorException;
use WcMipConnector\Client\MIP\Exception\SellingChannelConnectorException;
use WcMipConnector\Client\MIP\Model\SellingChannelConnector;
use WcMipConnector\Client\MIP\Base\Service\AbstractService;
use WcMipConnector\Client\MIP\Factory\SellingChannelConnectorFactory;

class SellingChannelConnectorService extends AbstractService
{
    const SELLING_CHANNEL_CONNECTOR = '/rest/selling_channel_connector';

    /** @var SellingChannelConnectorService */
    public static $instance;

    /** @var SellingChannelConnectorFactory */
    private $sellingChannelConnectorFactory;

    public function __construct($customerPublicationOptionsFactory, $accessToken)
    {
        $this->sellingChannelConnectorFactory = $customerPublicationOptionsFactory;

        parent::__construct($accessToken);
    }

    /**
     * @param string $accessToken
     * @return SellingChannelConnectorService
     */
    public static function getInstance($accessToken)
    {
        if (!self::$instance) {
            $customerPublicationOptionsFactory = SellingChannelConnectorFactory::getInstance();
            self::$instance = new self($customerPublicationOptionsFactory, $accessToken);
        }

        return self::$instance;
    }

    /**
     * @return SellingChannelConnector
     * @throws ClientErrorException|SellingChannelConnectorException
     */
    public function getSellingChannelConnector($connectorId)
    {
        $response = $this->get(self::SELLING_CHANNEL_CONNECTOR.'/'.$connectorId);

        return $this->sellingChannelConnectorFactory->create($response);
    }
}
