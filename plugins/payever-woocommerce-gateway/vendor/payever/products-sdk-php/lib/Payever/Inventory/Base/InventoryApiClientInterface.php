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

namespace Payever\Sdk\Inventory\Base;

use Payever\Sdk\Inventory\Http\RequestEntity\InventoryChangedRequestEntity;
use Payever\Sdk\Inventory\Http\RequestEntity\InventoryCreateRequestEntity;

interface InventoryApiClientInterface
{
    /**
     * Create an inventory record on payever side.
     *
     * Only first request will actually create an inventory record for SKU,
     * all subsequent requests will be ignored.
     *
     * @param InventoryCreateRequestEntity $entity
     *
     * @return \Payever\Sdk\Core\Http\Response
     * @throws \Exception
     */
    public function createInventory(InventoryCreateRequestEntity $entity);

    /**
     * Inform payever about increased inventory for product
     *
     * @param InventoryChangedRequestEntity $entity
     *
     * @return \Payever\Sdk\Core\Http\Response
     * @throws \Exception
     */
    public function addInventory(InventoryChangedRequestEntity $entity);

    /**
     * Inform payever about decreased inventory for product
     *
     * @param InventoryChangedRequestEntity $entity
     *
     * @return \Payever\Sdk\Core\Http\Response
     * @throws \Exception
     */
    public function subtractInventory(InventoryChangedRequestEntity $entity);

    /**
     * Batch export inventory records to payever
     *
     * @param InventoryIteratorInterface $inventoryIterator
     * @param string $externalId
     *
     * @return int - Number of successfully exported records
     * @throws \Exception
     */
    public function exportInventory(InventoryIteratorInterface $inventoryIterator, $externalId);
}
