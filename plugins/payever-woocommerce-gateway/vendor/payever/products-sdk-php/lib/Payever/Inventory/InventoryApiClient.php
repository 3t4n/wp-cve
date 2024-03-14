<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  Inventory
 * @package   Payever\Inventory
 * @author    payever GmbH <service@payever.de>
 * @author    Hennadii.Shymanskyi <gendosua@gmail.com>
 * @copyright 2017-2021 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Inventory;

use Payever\Sdk\Core\CommonProductsThirdPartyApiClient;
use Payever\Sdk\Core\Http\RequestBuilder;
use Payever\Sdk\Core\Http\ResponseEntity\DynamicResponse;
use Payever\Sdk\Inventory\Base\InventoryApiClientInterface;
use Payever\Sdk\Inventory\Base\InventoryIteratorInterface;
use Payever\Sdk\Inventory\Http\RequestEntity\InventoryChangedRequestEntity;
use Payever\Sdk\Inventory\Http\RequestEntity\InventoryCreateRequestEntity;

class InventoryApiClient extends CommonProductsThirdPartyApiClient implements InventoryApiClientInterface
{
    const SUB_URL_INVENTORY_CREATE = 'api/inventory/%s';
    const SUB_URL_INVENTORY_ADD = 'api/inventory/%s/add';
    const SUB_URL_INVENTORY_SUBTRACT = 'api/inventory/%s/subtract';

    /**
     * @inheritdoc
     */
    public function createInventory(InventoryCreateRequestEntity $entity)
    {
        $this->getConfiguration()->assertLoaded();
        $url = $this->getCreateInventoryUrl($entity->getExternalId());

        $request = RequestBuilder::post($url)
            ->contentTypeIsJson()
            ->addRawHeader(
                $this->getToken()->getAuthorizationString()
            )
            ->setRequestEntity($entity)
            ->setResponseEntity(new DynamicResponse())
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * @inheritdoc
     */
    public function addInventory(InventoryChangedRequestEntity $entity)
    {
        $this->getConfiguration()->assertLoaded();
        $url = $this->getAddInventoryUrl($entity->getExternalId());

        $request = RequestBuilder::post($url)
            ->contentTypeIsJson()
            ->addRawHeader(
                $this->getToken()->getAuthorizationString()
            )
            ->setRequestEntity($entity)
            ->setResponseEntity(new DynamicResponse())
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * @inheritdoc
     */
    public function subtractInventory(InventoryChangedRequestEntity $entity)
    {
        $this->getConfiguration()->assertLoaded();
        $url = $this->getSubtractInventoryUrl($entity->getExternalId());

        $request = RequestBuilder::post($url)
            ->contentTypeIsJson()
            ->addRawHeader(
                $this->getToken()->getAuthorizationString()
            )
            ->setRequestEntity($entity)
            ->setResponseEntity(new DynamicResponse())
            ->build();

        return $this->executeRequest($request);
    }

    /**
     * @inheritdoc
     */
    public function exportInventory(InventoryIteratorInterface $inventoryIterator, $externalId)
    {
        $this->getConfiguration()->assertLoaded();
        $successCount = 0;

        foreach ($inventoryIterator as $requestEntity) {
            try {
                $requestEntity->setExternalId($externalId);
                $this->createInventory($requestEntity);
            } catch (\Exception $exception) {
                $this->getConfiguration()->getLogger()
                    ->critical(
                        'Inventory item failed to export',
                        [
                            'sku' => $requestEntity->getSku(),
                            'exception' => $exception->getMessage(),
                        ]
                    );
                throw $exception;
            }
        }

        return $successCount;
    }

    /**
     * @param string $externalId
     *
     * @return string
     */
    private function getCreateInventoryUrl($externalId)
    {
        return $this->getBaseUrl() . sprintf(static::SUB_URL_INVENTORY_CREATE, $externalId);
    }

    /**
     * @param string $externalId
     * @return string
     */
    private function getAddInventoryUrl($externalId)
    {
        return $this->getBaseUrl() . sprintf(static::SUB_URL_INVENTORY_ADD, $externalId);
    }

    /**
     * @param string $externalId
     * @return string
     */
    private function getSubtractInventoryUrl($externalId)
    {
        return $this->getBaseUrl() . sprintf(static::SUB_URL_INVENTORY_SUBTRACT, $externalId);
    }
}
