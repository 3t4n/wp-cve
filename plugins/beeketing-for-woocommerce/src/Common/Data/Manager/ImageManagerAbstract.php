<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:04 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Manager;

use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Image;

abstract class ImageManagerAbstract extends BaseManager
{

    /**
     * index for response get many method
     * @return string
     */
    public function getResponseKeyGetMany()
    {
        return Image::$MODELS;
    }
}
