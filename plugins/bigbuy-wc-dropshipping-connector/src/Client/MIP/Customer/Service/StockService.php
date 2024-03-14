<?php
/**
 * @author    BigBuy
 * @copyright 2010-2020 BigBuy
 * @license   license
 */

namespace WcMipConnector\Client\MIP\Customer\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Client\Base\Exception\ClientErrorException;
use WcMipConnector\Client\MIP\Base\Service\AbstractService;
use WcMipConnector\Client\MIP\Factory\StocksFactory;
use WcMipConnector\Client\MIP\Model\Stock;

class StockService extends AbstractService
{
    const CUSTOMER_STOCKS = '/rest/customer/stocks';

    /** @var SellingChannelService */
    public static $instance;

    /** @var StocksFactory */
    private $stocksFactory;

    public function __construct($stocksFactory, $accessToken)
    {
        $this->stocksFactory = $stocksFactory;

        parent::__construct($accessToken);
    }

    /**
     * @param string $accessToken
     * @return SellingChannelService
     */
    public static function getInstance($accessToken)
    {
        if (!self::$instance) {
            $customerPublicationOptionsFactory = StocksFactory::getInstance();
            self::$instance = new self($customerPublicationOptionsFactory, $accessToken);
        }

        return self::$instance;
    }

    /**
     * @return Stock[]
     * @throws ClientErrorException
     */
    public function getStocks()
    {
        $response = $this->get(self::CUSTOMER_STOCKS);

        if (empty($response)) {
            return [];
        }

        return $this->stocksFactory->create($response);
    }
}
