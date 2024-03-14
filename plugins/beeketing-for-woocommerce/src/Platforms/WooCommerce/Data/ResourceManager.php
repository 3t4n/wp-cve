<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:18 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Data;


use BeeketingConnect_beeketing_woocommerce\Common\Data\Manager\ResourceManagerAbstract;

class ResourceManager extends ResourceManagerAbstract
{

    /**
     * @var ImageManager
     */
    public $imageManager;

    /**
     * @var VariantManager
     */
    public $variantManager;

    /**
     * ResourceManager constructor.
     */
    public function __construct()
    {
        parent::__construct(new CartManager, new CollectionManager, new CollectManager, new CustomerManager,
            new OrderManager($this), new ProductManager($this), new ShopManager, new VariantManager($this), new ImageManager());
    }

}