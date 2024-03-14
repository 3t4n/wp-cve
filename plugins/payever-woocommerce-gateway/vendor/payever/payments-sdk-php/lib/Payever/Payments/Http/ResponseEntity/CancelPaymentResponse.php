<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  ResponseEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Http\ResponseEntity;

use Payever\Sdk\Core\Http\ResponseEntity;
use Payever\Sdk\Payments\Http\MessageEntity\CancelPaymentResultEntity;
use Payever\Sdk\Payments\Http\MessageEntity\PaymentCallEntity;

/**
 * This class represents Cancel Payment ResponseInterface Entity
 *
 * @method PaymentCallEntity getCall()
 * @method CancelPaymentResultEntity getResult()
 */
class CancelPaymentResponse extends ResponseEntity
{
    /**
     * {@inheritdoc}
     */
    public function setCall($call)
    {
        $this->call = new PaymentCallEntity($call);
    }

    /**
     * {@inheritdoc}
     */
    public function setResult($result)
    {
        $this->result = new CancelPaymentResultEntity($result);
    }
}
