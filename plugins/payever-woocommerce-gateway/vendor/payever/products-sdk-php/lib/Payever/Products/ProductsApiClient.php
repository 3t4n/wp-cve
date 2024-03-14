<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  Products
 * @package   Payever\Products
 * @author    payever GmbH <service@payever.de>
 * @author    Hennadii.Shymanskyi <gendosua@gmail.com>
 * @copyright 2017-2021 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Products;

use Payever\Sdk\Core\CommonProductsThirdPartyApiClient;
use Payever\Sdk\Core\Http\RequestBuilder;
use Payever\Sdk\Core\Http\ResponseEntity\DynamicResponse;
use Payever\Sdk\Products\Base\ProductsApiClientInterface;
use Payever\Sdk\Products\Base\ProductsIteratorInterface;
use Payever\Sdk\Products\Http\RequestEntity\ProductRemovedRequestEntity;
use Payever\Sdk\Products\Http\RequestEntity\ProductRequestEntity;

class ProductsApiClient extends CommonProductsThirdPartyApiClient implements ProductsApiClientInterface
{
    const SUB_URL_PRODUCT = 'api/product/%s';

    /**
     * @inheritdoc
     */
    public function createProduct(ProductRequestEntity $entity)
    {
        $this->getConfiguration()->assertLoaded();
        $url = $this->getProductUrl($entity->getExternalId());

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
    public function updateProduct(ProductRequestEntity $entity)
    {
        $this->getConfiguration()->assertLoaded();
        $url = $this->getProductUrl($entity->getExternalId());

        $request = RequestBuilder::patch($url)
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
    public function createOrUpdateProduct(ProductRequestEntity $entity)
    {
        $this->getConfiguration()->assertLoaded();
        $url = $this->getProductUrl($entity->getExternalId());

        $request = RequestBuilder::put($url)
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
    public function removeProduct(ProductRemovedRequestEntity $entity)
    {
        $this->getConfiguration()->assertLoaded();
        $url = $this->getProductUrl($entity->getExternalId());

        $request = RequestBuilder::delete($url)
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
    public function exportProducts(ProductsIteratorInterface $productsIterator, $externalId)
    {
        $this->getConfiguration()->assertLoaded();
        $successCount = 0;

        foreach ($productsIterator as $productRequestEntity) {
            try {
                $productRequestEntity->setExternalId($externalId);
                $this->createOrUpdateProduct($productRequestEntity);
                $successCount++;
            } catch (\Exception $exception) {
                $this->getConfiguration()->getLogger()
                    ->critical(
                        'Product failed to export',
                        [
                            'sku' => $productRequestEntity->getSku(),
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
     * @return string
     */
    protected function getProductUrl($externalId)
    {
        return $this->getBaseUrl() . sprintf(static::SUB_URL_PRODUCT, $externalId);
    }
}
