<?php
/**
 * @author    BigBuy
 * @copyright 2010-2020 BigBuy
 * @license   license
 */

namespace WcMipConnector\Client\MIP\Customer\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Client\MIP\Model\PublicationSummary;
use WcMipConnector\Client\MIP\Base\Service\AbstractService;
use WcMipConnector\Client\MIP\Factory\CustomerPublicationOptionsFactory;

class SellingChannelService extends AbstractService
{
    const CUSTOMER_PUBLITACION_OPTIONS = '/rest/customer/publicationoptions';

    /** @var SellingChannelService */
    public static $instance;
    /** @var CustomerPublicationOptionsFactory */
    private $customerPublicationOptionsFactory;

    /**
     * SellingChannelService constructor.
     * @param CustomerPublicationOptionsFactory $customerPublicationOptionsFactory
     * @param string $apiKey
     */
    public function __construct(CustomerPublicationOptionsFactory $customerPublicationOptionsFactory, $apiKey)
    {
        $this->customerPublicationOptionsFactory = $customerPublicationOptionsFactory;

        parent::__construct($apiKey);
    }

    /**
     * @param string $accessToken
     * @return SellingChannelService
     */
    public static function getInstance($accessToken)
    {
        if (!self::$instance) {
            $customerPublicationOptionsFactory = CustomerPublicationOptionsFactory::getInstance();
            self::$instance = new self($customerPublicationOptionsFactory, $accessToken);
        }

        return self::$instance;
    }

    /**
     * @return PublicationSummary
     */
    public function getCustomerPublicationOptions()
    {
        try {
            $customerPublicationOptions = $this->get(self::CUSTOMER_PUBLITACION_OPTIONS);

            if (empty($customerPublicationOptions)) {
                return new PublicationSummary();
            }

            return $this->customerPublicationOptionsFactory->create($customerPublicationOptions);
        } catch (\Exception $e) {
            return new PublicationSummary();
        }
    }
}
