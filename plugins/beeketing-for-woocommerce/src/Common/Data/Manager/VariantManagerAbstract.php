<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:04 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Manager;

use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Variant;
use Symfony\Component\HttpFoundation\Request;

abstract class VariantManagerAbstract extends BaseManager
{

    /**
     * index for response get many method
     * @return string
     */
    public function getResponseKeyGetMany()
    {
        return Variant::$MODELS;
    }

    /**
     * Get one
     * @param $arg
     * @return Variant|boolean
     */
    abstract public function get($arg);

    /**
     * Update variant
     * @param $arg
     * @return Variant
     */
    abstract public function put($arg);

    /**
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
        if (!isset($content['variant'])) {
            throw new \Exception('Cant get variant from request content');
        }
        $arg['variant'] = $content['variant'];

        return $arg;
    }

    /**
     * Create new variant for product
     * @param $arg
     * @return Variant
     */
    abstract public function post($arg);

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function buildPostArgument(Request $request)
    {
        $arg = $this->buildArgumentFromRequest($request, [
            'product_id',
        ]);

        $content = json_decode($request->getContent(), true);
        if (!isset($content['variant'])) {
            throw new \Exception('Cant get variant from request content');
        }
        $arg['variant'] = $content['variant'];

        return $arg;
    }

    /**
     * Delete variant
     * @param $arg
     * @return boolean
     */
    abstract public function delete($arg);

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function buildDeleteArgument(Request $request)
    {
        return $this->buildArgumentFromRequest($request, [
            'variant_id',
        ]);
    }
}
