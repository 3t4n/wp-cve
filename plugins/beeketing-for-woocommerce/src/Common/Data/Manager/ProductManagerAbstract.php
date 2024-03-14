<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:04 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Manager;

use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Count;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Product;
use Symfony\Component\HttpFoundation\Request;

abstract class ProductManagerAbstract extends BaseManager
{

    /**
     * index for response get many method
     * @return string
     */
    public function getResponseKeyGetMany()
    {
        return Product::$MODELS;
    }

    /**
     * Get product
     * @param $arg
     * @return Product
     */
    abstract public function get($arg);

    /**
     * Get product
     * @param $arg
     * @return Product[]
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
            'limit', 'page', 'title',
        ], [
            'page' => self::DEFAULT_PAGE,
            'limit' => self::DEFAULT_ITEMS_PER_PAGE,
            'title' => null,
        ]);
    }

    /**
     * Count products
     * @return Count
     */
    abstract public function count();

    /**
     * Update product
     * @param $arg
     * @return Product
     */
    abstract public function put($arg);

    /**
     * Build arg for put method
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function buildPutArgument(Request $request)
    {
        $arg = $this->buildArgumentFromRequest($request, [
            'resource_id',
        ]);

        $content = json_decode($request->getContent(), true);
        if (!isset($content['product'])) {
            throw new \Exception('Cant get product from request content');
        }
        $arg['product'] = $content['product'];

        return $arg;
    }
}
