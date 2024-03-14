<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:04 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Manager;

use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Collection;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Count;
use Symfony\Component\HttpFoundation\Request;

abstract class CollectionManagerAbstract extends BaseManager
{

    /**
     * index for response get many method
     * @return string
     */
    public function getResponseKeyGetMany()
    {
        return Collection::$MODELS;
    }

    /**
     * Get one collection
     * @param $arg
     * @return Collection
     */
    abstract public function get($arg);

    /**
     * Get many collections
     * @param $arg
     * @return array
     */
    abstract public function getMany($arg);

    /**
     * Build arg for getMany method
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
     * Count collections in db
     * @return Count
     */
    abstract public function count();
}
