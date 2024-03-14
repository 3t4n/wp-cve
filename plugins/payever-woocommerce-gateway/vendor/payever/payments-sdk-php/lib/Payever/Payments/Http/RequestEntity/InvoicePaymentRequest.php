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
 * This class represents Invoice Payment RequestInterface Entity
 *
 * @method float getAmount()
 * @method self  setAmount(float $amount)
 */
class InvoicePaymentRequest extends RequestEntity
{
    /** @var float $amount */
    protected $amount;

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        return parent::isValid() &&
            (!$this->amount || is_numeric($this->amount))
        ;
    }
}
