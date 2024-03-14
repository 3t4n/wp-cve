<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:04 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Manager;

use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Count;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Order;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Constants;
use Symfony\Component\HttpFoundation\Request;

abstract class OrderManagerAbstract extends BaseManager
{

    /**
     * index for response get many method
     * @return string
     */
    public function getResponseKeyGetMany()
    {
        return Order::$MODELS;
    }

    /**
     * Get order
     * @param $arg
     * @return Order
     */
    abstract public function get($arg);

    /**
     * Get many orders
     * @param $arg
     * @return array
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
            'limit', 'page', 'status',
        ], [
            'page' => self::DEFAULT_PAGE,
            'limit' => self::DEFAULT_ITEMS_PER_PAGE,
            'status' => null,
        ]);
    }

    /**
     * Count order by status
     * @param $arg
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
            'status',
        ], [
            'status' => null,
        ]);
    }
}
