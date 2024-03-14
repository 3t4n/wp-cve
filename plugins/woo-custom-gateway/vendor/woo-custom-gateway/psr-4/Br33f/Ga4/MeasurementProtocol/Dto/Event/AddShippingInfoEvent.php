<?php
/**
 * User: Damian Zamojski (br33f)
 * Date: 25.06.2021
 * Time: 13:33
 */

namespace RichardMuvirimi\WooCustomGateway\Vendor\Br33f\Ga4\MeasurementProtocol\Dto\Event;

use RichardMuvirimi\WooCustomGateway\Vendor\Br33f\Ga4\MeasurementProtocol\Dto\Parameter\AbstractParameter;
use RichardMuvirimi\WooCustomGateway\Vendor\Br33f\Ga4\MeasurementProtocol\Enum\ErrorCode;
use RichardMuvirimi\WooCustomGateway\Vendor\Br33f\Ga4\MeasurementProtocol\Exception\ValidationException;

/**
 * Class AddShippingInfoEvent
 * @package RichardMuvirimi\WooCustomGateway\Vendor\Br33f\Ga4\MeasurementProtocol\Dto\Event
 * @method string getCurrency()
 * @method AddShippingInfoEvent setCurrency(string $currency)
 * @method float getValue()
 * @method AddShippingInfoEvent setValue(float $value)
 * @method string getCoupon()
 * @method AddShippingInfoEvent setCoupon(string $coupon)
 * @method string getShippingTier()
 * @method AddShippingInfoEvent setShippingTier(string $shippingTier)
 */
class AddShippingInfoEvent extends ItemBaseEvent
{
    private $eventName = 'add_shipping_info';

    /**
     * AddShippingInfoEvent constructor.
     * @param AbstractParameter[] $paramList
     */
    public function __construct(array $paramList = [])
    {
        parent::__construct($this->eventName, $paramList);
    }

    public function validate()
    {
        parent::validate();

        if (!empty($this->getValue())) {
            if (empty($this->getCurrency())) {
                throw new ValidationException('Field "currency" is required if "value" is set', ErrorCode::VALIDATION_FIELD_REQUIRED, 'currency');
            }
        }

        return true;
    }
}