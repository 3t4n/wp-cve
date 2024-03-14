<?php
/**
 * @author    BigBuy
 * @copyright 2010-2020 BigBuy
 * @license   license
 */

namespace WcMipConnector\Client\MIP\Model;

defined('ABSPATH') || exit;

class Stock
{
    /**
     * @var string
     */
    public $productId;

    /**
     * @var string
     */
    public $productVariationId;

    /**
     * @var int
     */
    public $stockQuantity;

    /**
     * @var bool
     */
    public $productActive;

}
