<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  RequestEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Http\RequestEntity;

use Payever\Sdk\Core\Http\RequestEntity;

/**
 * This class represents Refund Payment items RequestInterface Entity
 *
 * @method float getDeliveryFee()
 * @method float getPaymentItems()
 * @method self  setPaymentItems(array $paymentItems)
 */
class CancelItemsPaymentRequest extends RequestEntity
{
    /** @var float $deliveryFee */
    protected $deliveryFee;

    /** @var PaymentItemEntity[] $paymentItems */
    protected $paymentItems;

    /**
     * @param mixed $deliveryFee
     * @return $this
     */
    public function setDeliveryFee($deliveryFee)
    {
        $this->deliveryFee = (float)$deliveryFee;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        return parent::isValid() && (!$this->paymentItems || is_array($this->paymentItems));
    }
}
