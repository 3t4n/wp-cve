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
 * This class represents Verify entity
 *
 * @method string getType()
 * @method string getTwoFactor()
 * @method self setType(string $value)
 * @method self setTwoFactor(string $value)
 */
class VerifyEntity extends MessageEntity
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $twoFactor;
}
