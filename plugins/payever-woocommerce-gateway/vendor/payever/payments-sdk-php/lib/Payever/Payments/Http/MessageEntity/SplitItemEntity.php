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
 * This class represents SplitItem Entity
 *
 * @method string getType()
 * @method string getIdentifier()
 * @method float  getReference()
 * @method string getDescription()
 * @method SplitAmountEntity getAmount()
 * @method self setType(string $value)
 * @method self setIdentifier(float $id)
 * @method self setReference(string $reference)
 * @method self setDescription(string $description)
 */
class SplitItemEntity extends MessageEntity
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $reference;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var SplitAmountEntity
     */
    protected $amount;

    /**
     * @param SplitAmountEntity|array $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        if (!$amount) {
            return $this;
        }

        if (is_string($amount)) {
            $amount = json_decode($amount);
        }

        if (!is_array($amount) && !is_object($amount)) {
            return $this;
        }

        $this->amount = new SplitAmountEntity($amount);

        return $this;
    }
}
