<?php

declare(strict_types=1);

namespace WcMipConnector\Model;

defined('ABSPATH') || exit;

class CatalogHealthReport
{
    /** @var int|null */
    public $TotalShopProductsCount;

    /** @var int|null */
    public $TotalMappedProductsCount;

    /** @var int|null */
    public $TotalShopProductsActiveCount;

    /** @var int|null */
    public $TotalShopProductsDisabledCount;

    /** @var int|null */
    public $TotalMappedProductsActiveCount;

    /** @var int|null */
    public $TotalMappedProductsDisabledCount;

    /** @var int|null */
    public $ProductErrorCount;

    /** @var int|null */
    public $VariationErrorCount;

    /** @var int|null */
    public $CategoryErrorCount;

    /** @var int|null */
    public $BrandErrorCount;

    /** @var int|null */
    public $AttributeGroupErrorCount;

    /** @var int|null */
    public $AttributeErrorCount;

    /** @var int|null */
    public $TagErrorCount;
}