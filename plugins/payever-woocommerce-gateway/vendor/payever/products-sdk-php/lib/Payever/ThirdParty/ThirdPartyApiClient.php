<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  ThirdParty
 * @package   Payever\ThirdParty
 * @author    payever GmbH <service@payever.de>
 * @author    Hennadii.Shymanskyi <gendosua@gmail.com>
 * @copyright 2017-2021 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\ThirdParty;

use Payever\Sdk\Core\CommonProductsThirdPartyApiClient;
use Payever\Sdk\Core\Http\RequestBuilder;
use Payever\Sdk\Core\Http\ResponseEntity\DynamicResponse;
use Payever\Sdk\ThirdParty\Base\ThirdPartyApiClientInterface;
use Payever\Sdk\ThirdParty\Http\RequestEntity\SubscriptionRequestEntity;
use Payever\Sdk\ThirdParty\Http\ResponseEntity\BusinessResponseEntity;
use Payever\Sdk\ThirdParty\Http\ResponseEntity\SubscriptionResponseEntity;

class ThirdPartyApiClient extends CommonProductsThirdPartyApiClient implements ThirdPartyApiClientInterface
{
    const SUB_URL_BUSINESS_INFO = 'api/business/%s/plugins';
    const SUB_URL_CONNECTION = 'api/business/%s/connection/authorization/%s';
    const SUB_URL_INTEGRATION = 'api/business/%s/integration/%s';

    /**
     * @inheritdoc
     */
    public function getBusinessRequest()
    {
        $this->getConfiguration()->assertLoaded();

        $request = RequestBuilder::get($this->getBusinessInfoUrl($this->getConfiguration()->getBusinessUuid()))
            ->addRawHeader(
                $this->getToken()->getAuthorizationString()
            )
            ->setResponseEntity(new BusinessResponseEntity())
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * @inheritdoc
     */
    public function getSubscriptionStatus(SubscriptionRequestEntity $requestEntity)
    {
        $this->getConfiguration()->assertLoaded();

        $this->fillSubscriptionEntityFromConfiguration($requestEntity);

        $request = RequestBuilder::get($this->getConnectionUrl($requestEntity))
            ->addRawHeader(
                $this->getToken()->getAuthorizationString()
            )
            ->setResponseEntity(new SubscriptionResponseEntity())
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * @inheritdoc
     */
    public function subscribe(SubscriptionRequestEntity $requestEntity)
    {
        $this->getConfiguration()->assertLoaded();

        $this->fillSubscriptionEntityFromConfiguration($requestEntity);

        $request = RequestBuilder::post($this->getIntegrationUrl($requestEntity))
            ->contentTypeIsJson()
            ->addRawHeader(
                $this->getToken()->getAuthorizationString()
            )
            ->setRequestEntity($requestEntity)
            ->setResponseEntity(new SubscriptionResponseEntity())
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * @inheritdoc
     */
    public function unsubscribe(SubscriptionRequestEntity $requestEntity)
    {
        $this->getConfiguration()->assertLoaded();

        $this->fillSubscriptionEntityFromConfiguration($requestEntity);

        $request = RequestBuilder::delete($this->getConnectionUrl($requestEntity))
            ->addRawHeader(
                $this->getToken()->getAuthorizationString()
            )
            ->setResponseEntity(new DynamicResponse())
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * @param string $businessUuid
     *
     * @return string
     */
    protected function getBusinessInfoUrl($businessUuid)
    {
        return $this->getBaseUrl() . sprintf(static::SUB_URL_BUSINESS_INFO, $businessUuid);
    }

    /**
     * @param SubscriptionRequestEntity $requestEntity
     * @return string
     */
    protected function getConnectionUrl(SubscriptionRequestEntity $requestEntity)
    {
        $path = sprintf(
            static::SUB_URL_CONNECTION,
            $requestEntity->getBusinessUuid(),
            $requestEntity->getExternalId()
        );

        return $this->getBaseUrl() . $path;
    }

    /**
     * @param SubscriptionRequestEntity $requestEntity
     * @return string
     */
    protected function getIntegrationUrl(SubscriptionRequestEntity $requestEntity)
    {
        $path = sprintf(
            static::SUB_URL_INTEGRATION,
            $requestEntity->getBusinessUuid(),
            $requestEntity->getThirdPartyName()
        );

        return $this->getBaseUrl() . $path;
    }

    /**
     * @param SubscriptionRequestEntity $requestEntity
     */
    private function fillSubscriptionEntityFromConfiguration(SubscriptionRequestEntity $requestEntity)
    {
        if (!$requestEntity->getBusinessUuid()) {
            $requestEntity->setBusinessUuid($this->getConfiguration()->getBusinessUuid());
        }
        if (!$requestEntity->getThirdPartyName()) {
            $requestEntity->setThirdPartyName($this->getConfiguration()->getChannelSet());
        }
    }
}
