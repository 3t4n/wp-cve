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

namespace Payever\Sdk\Payments\Http\MessageEntity;

use Payever\Sdk\Core\Base\MessageEntity;

/**
 * This class represents SplitAmount Entity of SplitItemEntity
 *
 * @method float getValue()
 * @method string getCurrency()
 * @method self setValue(float $value)
 * @method self setCurrency(string $value)
 */
class SplitAmountEntity extends MessageEntity
{
    /**
     * @var float
     */
    protected $value;

    /**
     * @var string
     */
    protected $currency;
}
