<?php

/**
 * PHP version 5.4 and 8.1
 *
 * @category  RequestEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Http\MessageEntity;

use Payever\Sdk\Core\Http\RequestEntity;

/**
 * This class represents Widget Payments Entity
 *
 * @method string                    getId()
 * @method string                    getPaymentMethod()
 * @method string                    getConnectionId()
 * @method boolean                   getEnabled()
 * @method boolean                   getIsBNPL()
 * @method self                      setId(string $id)
 * @method self                      setPaymentMethod(string $paymentMethod)
 * @method self                      setConnectionId(string $connectionId)
 * @method self                      setEnabled(boolean $enabled)
 * @method boolean                   setIsBNPL(boolean $isBNPL)
 */
class PaymentWidgetPaymentsEntity extends RequestEntity
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $paymentMethod;

    /**
     * @var string
     */
    protected $connectionId;

    /**
     * @var boolean
     */
    protected $enabled;

    /**
     * @var boolean
     */
    protected $isBNPL;
}
