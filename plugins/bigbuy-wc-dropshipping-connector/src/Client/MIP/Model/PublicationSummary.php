<?php
/**
 * @author    BigBuy
 * @copyright 2010-2020 BigBuy
 * @license   license
 */

namespace WcMipConnector\Client\MIP\Model;

defined('ABSPATH') || exit;

class PublicationSummary
{
    /** @var int */
    public $notSelectedProductsToUpdateCount = 0;

    /** @var float */
    public $conversionFactor = 1.0;

    /** @var string|null */
    public $shippingRateIncludedCountryIsoCode = null;
}
