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
 * This class represents Dimensions Entity of Attributes
 *
 * @method float getHeight()
 * @method float getWidth()
 * @method float getLength()
 * @method self setHeight(float $value)
 * @method self setWidth(float $value)
 * @method self setLength(float $value)
 */
class DimensionsEntity extends MessageEntity
{
    /**
     * @var float
     */
    protected $height;

    /**
     * @var float
     */
    protected $width;

    /**
     * @var float
     */
    protected $length;
}
