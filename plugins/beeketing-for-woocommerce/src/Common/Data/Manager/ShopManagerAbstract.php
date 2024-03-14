<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:04 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Manager;

use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Shop;
use Symfony\Component\HttpFoundation\Request;

abstract class ShopManagerAbstract extends BaseManager
{

    /**
     * index for response get many method
     * @return string
     */
    public function getResponseKeyGetMany()
    {
        return Shop::$MODELS;
    }

    /**
     * Get shop
     * @return Shop
     */
    abstract public function get();

    /**
     * @param Request $request
     * @return boolean
     * @throws \Exception
     */
    public function buildGetArgument(Request $request)
    {
        // Don't need any argument
        return false;
    }
}
