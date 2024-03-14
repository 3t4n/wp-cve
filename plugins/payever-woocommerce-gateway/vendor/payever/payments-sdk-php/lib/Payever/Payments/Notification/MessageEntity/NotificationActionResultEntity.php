<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  MessageEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Notification\MessageEntity;

use Payever\Sdk\Core\Http\MessageEntity\ResultEntity;

/**
 * @method null|float  getAmount()
 * @method null|string getReference()
 * @method null|string getSource()
 * @method null|string getType()
 * @method null|string getUniqueIdentifier()
 * @method self        setAmount(float $amount)
 * @method self        setReference(string $reference)
 * @method self        setSource(string $source)
 * @method self        setType(string $type)
 * @method self        setUniqueIdentifier(string $uniqueIdentifier)
 */
class NotificationActionResultEntity extends ResultEntity
{
    /** @var null|float $amount */
    protected $amount;

    /** @var null|string $reference */
    protected $reference;

    /** @var null|string $source */
    protected $source;

    /** @var null|string $type */
    protected $type;

    /** @var null|string $uniqueIdentifier */
    protected $uniqueIdentifier;
}
