<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:04 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Manager;

use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Collect;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Count;
use Symfony\Component\HttpFoundation\Request;

abstract class CollectManagerAbstract extends BaseManager
{

    /**
     * index for response get many method
     * @return string
     */
    public function getResponseKeyGetMany()
    {
        return Collect::$MODELS;
    }

    /**
     * Get many collects
     * @param $arg
     * @return Collect[]
     */
    abstract public function getMany($arg);

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function buildGetManyArgument(Request $request)
    {
        return $this->buildArgumentFromRequest($request, [
            'limit', 'page', 'collection_id', 'product_id',
        ], [
            'page' => self::DEFAULT_PAGE,
            'limit' => self::DEFAULT_ITEMS_PER_PAGE,
            'collection_id' => null,
            'product_id' => null,
        ]);
    }

    /**
     * Count collects
     * @return Count
     */
    abstract public function count($arg);

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function buildCountArgument(Request $request)
    {
        return $this->buildArgumentFromRequest($request, [
            'collection_id', 'product_id',
        ], [
            'collection_id' => null,
            'product_id' => null,
        ]);
    }
}
