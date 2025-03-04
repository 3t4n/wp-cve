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
 * Class PurchaseEvent
 * @package RichardMuvirimi\WooCustomGateway\Vendor\Br33f\Ga4\MeasurementProtocol\Dto\Event
 * @method string getCurrency()
 * @method PurchaseEvent setCurrency(string $currency)
 * @method string getTransactionId()
 * @method PurchaseEvent setTransactionId(string $transactionId)
 * @method float getValue()
 * @method PurchaseEvent setValue(float $value)
 * @method string getAffiliation()
 * @method PurchaseEvent setAffiliation(string $affiliation)
 * @method string getCoupon()
 * @method PurchaseEvent setCoupon(string $coupon)
 * @method float getShipping()
 * @method PurchaseEvent setShipping(float $shipping)
 * @method float getTax()
 * @method PurchaseEvent setTax(float $tax)
 */
class PurchaseEvent extends ItemBaseEvent
{
    private $eventName = 'purchase';

    /**
     * PurchaseEvent constructor.
     * @param AbstractParameter[] $paramList
     */
    public function __construct(array $paramList = [])
    {
        parent::__construct($this->eventName, $paramList);
    }

    public function validate()
    {
        parent::validate();

        if (empty($this->getTransactionId())) {
            throw new ValidationException('Field "transaction_id" is required if "value" is set', ErrorCode::VALIDATION_FIELD_REQUIRED, 'curtransaction_idrency');
        }

        if (!empty($this->getValue())) {
            if (empty($this->getCurrency())) {
                throw new ValidationException('Field "currency" is required if "value" is set', ErrorCode::VALIDATION_FIELD_REQUIRED, 'currency');
            }
        }

        return true;
    }
}