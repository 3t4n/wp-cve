<?php
/**
 * User: Damian Zamojski (br33f)
 * Date: 24.06.2021
 * Time: 15:22
 */

namespace RichardMuvirimi\WooCustomGateway\Vendor\Br33f\Ga4\MeasurementProtocol\Dto\Event;

use RichardMuvirimi\WooCustomGateway\Vendor\Br33f\Ga4\MeasurementProtocol\Dto\Parameter\AbstractParameter;

/**
 * Class ViewItemEvent
 * @package RichardMuvirimi\WooCustomGateway\Vendor\Br33f\Ga4\MeasurementProtocol\Dto\Event
 * @method string getCurrency()
 * @method ViewItemEvent setCurrency(string $currency)
 * @method string getValue()
 * @method ViewItemEvent setValue(string $value)
 */
class ViewItemEvent extends ItemBaseEvent
{
    private $eventName = 'view_item';

    /**
     * ViewItemEvent constructor.
     * @param AbstractParameter[] $paramList
     */
    public function __construct(array $paramList = [])
    {
        parent::__construct($this->eventName, $paramList);
    }
}