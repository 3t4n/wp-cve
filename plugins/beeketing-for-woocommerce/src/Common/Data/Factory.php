<?php
/**
 * User: dzungtran
 * Date: 10/28/18
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data;

use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Cart;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\CartItem;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Collect;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Collection;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Count;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Customer;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\FileSync;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Image;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Order;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\OrderItem;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Product;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\ResourceParams;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Shop;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Variant;

/**
 * A Simple Class Factory
 * @package BeeketingConnect_beeketing_woocommerce\Common\Data
 */
class Factory
{
    /**
     * @var array
     */
    private $objectMap;

    /**
     * Factory constructor.
     * @param Cart $cart
     * @param Collect $collect
     * @param Collection $collection
     * @param Count $count
     * @param Customer $customer
     * @param FileSync $fileSync
     * @param Image $image
     * @param Order $order
     * @param Product $product
     * @param ResourceParams $resourceParams
     * @param Shop $shop
     * @param Variant $variant
     * @param OrderItem $orderItem
     * @param CartItem $cartItem
     */
    public function __construct(
        Cart $cart,
        Collect $collect,
        Collection $collection,
        Count $count,
        Customer $customer,
        FileSync $fileSync,
        Image $image,
        Order $order,
        Product $product,
        ResourceParams $resourceParams,
        Shop $shop,
        Variant $variant,
        OrderItem $orderItem,
        CartItem $cartItem
    ) {
        $this->objectMap = [
            Cart::class => $cart,
            Order::class => $order,
            Count::class => $count,
            Collect::class => $collect,
            Collection::class => $collection,
            Customer::class => $customer,
            FileSync::class => $fileSync,
            Image::class => $image,
            Product::class => $product,
            ResourceParams::class => $resourceParams,
            Shop::class => $shop,
            Variant::class => $variant,
            OrderItem::class => $orderItem,
            CartItem::class => $cartItem,
        ];
    }

    /**
     * Create Data Model
     *
     * @param $className
     * @param array $sourceData
     * @return mixed|null
     */
    public function create($className, $sourceData = [])
    {
        if (!isset($this->objectMap[$className])) {
            throw new \RuntimeException(sprintf(
                'Can not factory class %s because class mapping not exists',
                get_class($className)
            ));
        }

        if ($className === ResourceParams::class) {
            $sourceData = ['params' => $sourceData];
        }

        return $this->setModelData(clone $this->objectMap[$className], $sourceData);
    }

    /**
     * Set data for model class
     * @param $obj
     * @param array $data
     * @return mixed
     */
    private function setModelData($obj, $data = [])
    {
        if (!is_array($data) || empty($data)) {
            throw new \RuntimeException(sprintf(
                'Can not factory class %s because data is empty',
                get_class($obj)
            ));
        }

        $objectVars = get_object_vars($obj);

        if (count($objectVars) !== \count($data)) {
            throw new \RuntimeException(sprintf(
                'Can not factory class %s because the property length is not matched',
                get_class($obj)
            ));
        }

        foreach ($data as $key => $value) {
            if (!array_key_exists($key, $objectVars)) {
                throw new \RuntimeException(sprintf(
                    'Can not factory class %s because the property "%s" is not defined',
                    get_class($obj),
                    $key
                ));
            }

            $obj->{$key} = $value;
        }
        return $obj;
    }
}
