<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:04 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Manager;

use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Count;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Customer;
use Symfony\Component\HttpFoundation\Request;

abstract class CustomerManagerAbstract extends BaseManager
{

    /**
     * index for response get many method
     * @return string
     */
    public function getResponseKeyGetMany()
    {
        return Customer::$MODELS;
    }

    /**
     * Get a customer
     * @param $arg
     * @return Customer
     */
    abstract public function get($arg);

    /**
     * Get many customers
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
            'limit', 'page', 'ids',
        ], [
            'page' => self::DEFAULT_PAGE,
            'limit' => self::DEFAULT_ITEMS_PER_PAGE,
            'ids' => false,
        ]);
    }

    /**
     * Count customers
     * @return Count
     */
    abstract public function count();
}
