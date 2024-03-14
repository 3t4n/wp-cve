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
 * This class represents Channel Entity
 *
 * @method string           getName()
 * @method string           getType()
 * @method string           getSource()
 * @method string           getChannelSetId()
 * @method self             setName(string $name)
 * @method self             setType(string $type)
 * @method self             setSource(string $source)
 * @method self             setChannelSetId(string $channelSetId)
 */
class ChannelEntity extends MessageEntity
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $source;

    /**
     * @deprecated
     * @var int
     */
    protected $channelSetId;

    /**
     * {@inheritdoc}
     */
    public function toArray($object = null)
    {
        return $object ? get_object_vars($object) : get_object_vars($this);
    }
}
