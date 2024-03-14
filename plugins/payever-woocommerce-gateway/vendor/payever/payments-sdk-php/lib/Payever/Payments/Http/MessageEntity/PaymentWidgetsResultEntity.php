<?php

/**
 * PHP version 5.4 and 8.1
 *
 * @category  MessageEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Http\MessageEntity;

use Payever\Sdk\Core\Http\MessageEntity\ResultEntity;
use Payever\Sdk\Payments\Http\MessageEntity\PaymentWidgetPaymentsEntity;

/**
 * This class represents PaymentWidgetsResult Entity
 *
 * @method string                        getId()
 * @method string                        getBusinessId()
 * @method string                        getCheckoutId()
 * @method string                        getCheckoutMode()
 * @method string                        getType()
 * @method boolean                       getIsVisible()
 * @method PaymentWidgetPaymentsEntity[] getPayments()
 * @method self                          setId(string $id)
 * @method self                          setBusinessId(string $businessId)
 * @method self                          setCheckoutId(string $checkoutId)
 * @method self                          setCheckoutMode(string $checkoutMode)
 * @method self                          setType(string $type)
 * @method self                          setIsVisible(boolean $isVisible)
 */
class PaymentWidgetsResultEntity extends ResultEntity
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $businessId;

    /**
     * @var string
     */
    protected $checkoutId;

    /**
     * @var string
     */
    protected $checkoutMode;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var boolean
     */
    protected $isVisible;

    /**
     * @var PaymentWidgetPaymentsEntity[]
     */
    protected $payments;

    /**
     * Set Payments.
     *
     * @param $payments
     * @return $this
     */
    public function setPayments($payments)
    {
        $this->payments = [];

        if (is_array($payments)) {
            foreach ($payments as $payment) {
                $this->payments[] = new PaymentWidgetPaymentsEntity($payment);
            }
        }

        return $this;
    }
}
