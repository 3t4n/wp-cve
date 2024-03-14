<?php
/**
 * Created by PhpStorm.
 * User: quaninte
 * Date: 8/14/18
 * Time: 11:54 AM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Model;

class Product
{

    /**
     * Model code
     */
    public static $MODEL = 'product';
    public static $MODELS = 'products';

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $published_at;

    /**
     * @var string
     */
    public $handle;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $vendor;

    /**
     * @var array
     */
    public $tags = [];

    /**
     * @var string
     */
    public $description;

    /**
     * @var Image[] $images Array of images
     */
    public $images = [];

    /**
     * @var string
     */
    public $image;

    /**
     * @var Variant[]
     */
    public $variants;

    /**
     * @var array
     */
    public $collection_ids = [];

    /**
     * Available statuses: in_stock, out_of_stock
     * @var string
     */
    public $stock_status = '';

    /**
     * @var array
     */
    public $options = [];

    /**
     * @var string
     */
    public $type;

    /**
     * @var bool
     */
    public $is_downloadable = false;

    /**
     * @var bool
     */
    public $is_virtual = false;

    /**
     * @var int
     */
    public $inventory_management = 0;
}
