<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 3:57 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Manager;

use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Cart;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Collect;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Collection;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Customer;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Image;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Order;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Product;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Shop;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Variant;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class ResourceManagerAbstract
{
    /**
     * @var CartManagerAbstract
     */
    public $cartManager;

    /**
     * @var CollectionManagerAbstract
     */
    public $collectionManager;

    /**
     * @var CollectManagerAbstract
     */
    public $collectManager;

    /**
     * @var CustomerManagerAbstract
     */
    public $customerManager;

    /**
     * @var OrderManagerAbstract
     */
    public $orderManager;

    /**
     * @var ProductManagerAbstract
     */
    public $productManager;

    /**
     * @var ShopManagerAbstract
     */
    public $shopManager;

    /**
     * @var VariantManagerAbstract
     */
    public $variantManager;

    /**
     * @var ImageManagerAbstract
     */
    public $imageManager;

    /**
     * @var array
     */
    public $allowedAccess = [];

    /**
     * ResourceManagerAbstract constructor.
     * @param CartManagerAbstract $cartManager
     * @param CollectionManagerAbstract $collectionManager
     * @param CollectManagerAbstract $collectManager
     * @param CustomerManagerAbstract $customerManager
     * @param OrderManagerAbstract $orderManager
     * @param ProductManagerAbstract $productManager
     * @param ShopManagerAbstract $shopManager
     * @param VariantManagerAbstract $variantManager
     * @param ImageManagerAbstract $imageManager
     */
    public function __construct(
        CartManagerAbstract $cartManager,
        CollectionManagerAbstract $collectionManager,
        CollectManagerAbstract $collectManager,
        CustomerManagerAbstract $customerManager,
        OrderManagerAbstract $orderManager,
        ProductManagerAbstract $productManager,
        ShopManagerAbstract $shopManager,
        VariantManagerAbstract $variantManager,
        ImageManagerAbstract $imageManager
    ) {

        $this->cartManager = $cartManager;
        $this->collectionManager = $collectionManager;
        $this->collectManager = $collectManager;
        $this->customerManager = $customerManager;
        $this->orderManager = $orderManager;
        $this->productManager = $productManager;
        $this->shopManager = $shopManager;
        $this->variantManager = $variantManager;
        $this->imageManager = $imageManager;
    }

    /**
     * Get resource manager for model
     * @param $modelName
     * @return BaseManager
     * @throws \Exception
     */
    public function getManager($modelName)
    {
        $managerMap = [
            Cart::$MODEL        => $this->cartManager,
            Collection::$MODEL  => $this->collectionManager,
            Collect::$MODEL     => $this->collectManager,
            Customer::$MODEL    => $this->customerManager,
            Image::$MODEL       => $this->imageManager,
            Order::$MODEL       => $this->orderManager,
            Product::$MODEL     => $this->productManager,
            Shop::$MODEL        => $this->shopManager,
            Variant::$MODEL     => $this->variantManager,
        ];

        if (isset($managerMap[$modelName])) {
            return $managerMap[$modelName];
        } else {
            throw new \Exception(sprintf('Cant find manager for model %s', $modelName));
        }
    }

    /**
     * Handle a request
     * @param $modelName
     * @param $method
     * @param Request $request
     * @param bool $return
     * @return array|bool|mixed
     * @throws \Exception
     */
    public function handleRequest($modelName, $method, Request $request, $return = false)
    {
        // Check if this request has permission to access data
        if (!in_array($modelName, $this->allowedAccess) && !in_array('all', $this->allowedAccess)) {
            throw new \Exception(sprintf('Access for model %s is not allowed', $modelName));
        }

        $manager = $this->getManager($modelName);

        // If manager is not initialised
        if (!$manager) {
            throw new \Exception(sprintf('Manager for model %s is not initialised', $modelName));
        }

        if (!method_exists($manager, $method)) {
            throw new \Exception(sprintf('Method %s for model manager %s not existed', $method, $modelName));
        }

        // Get model data
        $result = $this->getModelData($method, $manager, $request);

        // Reformat responses
        $result = $this->reformatResponse($method, $result, $manager);

        // Return result before send output
        if ($return) {
            return $result;
        }

        // Response
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($result));
        $response->send();
    }

    /**
     * @param $method
     * @param $manager
     * @param Request $request
     * @return mixed
     */
    private function getModelData($method, $manager, Request $request)
    {
        // Build argument
        $argumentMethod = 'build' . ucfirst($method) . 'Argument';
        // Get result from method
        // if we need to build an argument
        if (method_exists($manager, $argumentMethod)) {
            $arg = $manager->{$argumentMethod}($request);

            if ($arg !== false) {
                $result = $manager->{$method}($arg);
            } else {
                // No argument needed
                $result = $manager->{$method}();
            }
        } else {
            // No argument needed
            $result = $manager->{$method}();
        }

        return $result;
    }

    /**
     * @param $method
     * @param $result
     * @param $manager
     * @return array|bool
     */
    private function reformatResponse($method, $result, $manager)
    {
        $index = false;
        if (is_bool($result)) {
            $index = 'success';
        } elseif (is_object($result)) {
            if (isset($result::$MODEL)) {
                $index = $result::$MODEL;
            }
        } elseif (is_array($result)) {
            if ($method === 'getMany' && method_exists($manager, 'getResponseKeyGetMany')) {
                $index = $manager->getResponseKeyGetMany();
            }
        }

        // If index is defined
        if ($index) {
            $result = [
                $index => $result,
            ];
        }

        return $result;
    }
}
