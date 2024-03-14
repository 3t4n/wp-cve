<?php
/**
 * Created by PhpStorm.
 * User: quaninte
 * Date: 8/14/18
 * Time: 11:54 AM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Model;

class Collect
{

    /**
     * Model code
     */
    public static $MODEL = 'collect';
    public static $MODELS = 'collects';

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $collection_id;

    /**
     * @var int
     */
    public $product_id;

    /**
     * @var int
     */
    public $position;
}
