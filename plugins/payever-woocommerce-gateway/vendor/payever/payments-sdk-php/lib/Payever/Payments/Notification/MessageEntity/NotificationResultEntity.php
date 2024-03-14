<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  Notification
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @author    Hennadii.Shymanskyi <gendosua@gmail.com>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Notification\MessageEntity;

use Payever\Sdk\Payments\Http\MessageEntity\RetrievePaymentResultEntity;

/**
 * This class represents Notification Result Entity
 *
 * @method float                getRefundAmount()
 * @method float                getCaptureAmount()
 * @method float                getCancelAmount()
 * @method float                getTotalCapturedAmount()
 * @method float                getTotalRefundedAmount()
 * @method float                getTotalCanceledAmount()
 * @method array                getRefundedItems()
 * @method array                getCapturedItems()
 * @method self                 setRefundAmount()
 * @method self                 setCaptureAmount()
 * @method self                 setCancelAmount()
 * @method self                 setRefundedItems()
 * @method self                 setCapturedItems()
 * @method self                 setTotalCapturedAmount()
 * @method self                 setTotalRefundedAmount()
 * @method self                 setTotalCanceledAmount()
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class NotificationResultEntity extends RetrievePaymentResultEntity
{
    /**
     * Returns the total (partial) refunded amount for this transaction
     * @var float|null
     */
    protected $refundAmount;

    /**
     * Returns the total (partial) captured amount for this transaction
     * @var float|null
     */
    protected $captureAmount;

    /**
     * Returns the total (partial) cancelled amount for this transaction
     * @var float|null
     */
    protected $cancelAmount;

    /**
     * Refunded items (only when cart was provided)
     * @var array
     */
    protected $refundedItems;

    /**
     * Captured items (only when cart was provided)
     * @var array
     */
    protected $capturedItems;

    /**
     * Returns the total captured amount for payment
     * @var float|null
     */
    protected $totalCapturedAmount;

    /**
     * Returns the total refunded amount for payment
     * @var float|null
     */
    protected $totalRefundedAmount;

    /**
     * Returns the total cancelled amount for payment
     * @var float|null
     */
    protected $totalCanceledAmount;
}
