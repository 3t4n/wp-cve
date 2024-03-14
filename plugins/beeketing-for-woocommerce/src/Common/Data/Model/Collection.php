<?php
/**
 * Created by PhpStorm.
 * User: quaninte
 * Date: 8/14/18
 * Time: 11:54 AM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Model;

class Collection
{

    /**
     * Model code
     */
    public static $MODEL = 'collection';
    public static $MODELS = 'collections';

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $handle;

    /**
     * @var string
     */
    public $published_at;

    /**
     * @var string
     */
    public $image_url;
}
