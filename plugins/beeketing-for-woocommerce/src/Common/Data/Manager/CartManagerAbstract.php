<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:04 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Manager;

use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Cart;
use Symfony\Component\HttpFoundation\Request;

abstract class CartManagerAbstract extends BaseManager
{

    /**
     * index for response get many method
     * @return string
     */
    public function getResponseKeyGetMany()
    {
        return Cart::$MODELS;
    }

    /**
     * Get cart
     * @param $useCookie
     * @return Cart
     */
    abstract public function get($useCookie = true);

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

    /**
     * Add items to cart
     * @param $arg
     * @return CartItem
     */
    abstract public function post($arg);

    /**
     * Build argument for post method
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function buildPostArgument(Request $request)
    {
        return $this->buildArgumentFromRequest($request, [
            'type', 'product_id', 'variant_id', 'quantity', 'attributes',
        ], [
            'quantity' => [],
            'attributes' => [],
            'type' => null,
        ]);
    }

    /**
     * Update cart items quantity
     * @param $arg
     * @return Cart
     */
    abstract public function put($arg);

    /**
     * Build argument for put method
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function buildPutArgument(Request $request)
    {
        return $this->buildArgumentFromRequest($request, [
            'id',
        ]);
    }
}
