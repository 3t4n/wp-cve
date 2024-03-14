<?php

/**
 * Meta data interpreter.
 *
 * @package WPDesk\WooCommerceShipping\Ups
 */
namespace UpsFreeVendor\WPDesk\WooCommerceShipping\Ups;

use UpsFreeVendor\WPDesk\UpsShippingService\UpsShippingService;
use UpsFreeVendor\WPDesk\View\Renderer\Renderer;
use UpsFreeVendor\WPDesk\WooCommerceShipping\OrderMetaData\FrontOrderMetaDataDisplay;
use UpsFreeVendor\WPDesk\WooCommerceShipping\Ups\MetaDataInterpreters\UpsSingleFrontMetaDataInterpreter;
/**
 * Can interpret UPS meta data on order.
 */
class UpsFrontOrderMetaDataDisplay extends \UpsFreeVendor\WPDesk\WooCommerceShipping\OrderMetaData\FrontOrderMetaDataDisplay
{
    const META_FALLBACK_REASON = 'fallback_reason';
    /**
     * Renderer.
     *
     * @var Renderer
     */
    private $renderer;
    /**
     * UpsOrderMetaDataInterpreter constructor.
     */
    public function __construct(\UpsFreeVendor\WPDesk\View\Renderer\Renderer $renderer)
    {
        parent::__construct(\UpsFreeVendor\WPDesk\UpsShippingService\UpsShippingService::UNIQUE_ID);
        $this->renderer = $renderer;
    }
    /**
     * Init interpreters.
     */
    public function init_interpreters()
    {
        $this->add_interpreter(new \UpsFreeVendor\WPDesk\WooCommerceShipping\Ups\MetaDataInterpreters\UpsSingleFrontMetaDataInterpreter(\UpsFreeVendor\WPDesk\WooCommerceShipping\Ups\UpsMetaDataBuilder::META_UPS_ACCESS_POINT_ADDRESS, \__('UPS Access Point Address', 'flexible-shipping-ups'), 'order-details-after-table-access-point-address', $this->renderer));
    }
}
