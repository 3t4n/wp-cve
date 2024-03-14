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
 * This class represents Purchase Entity
 *
 * @method float getAmount()
 * @method string getCurrency()
 * @method string getCountry()
 * @method float getDeliveryFee()
 * @method float getDownPayment()
 * @method self setAmount(float $value)
 * @method self setCurrency(string $value)
 * @method self setCountry(string $value)
 * @method self setDeliveryFee(float $value)
 * @method self setDownPayment(float $value)
 */
class PurchaseEntity extends MessageEntity
{
    /**
     * @var float
     */
    protected $amount;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var float
     */
    protected $deliveryFee;

    /**
     * @var float
     */
    protected $downPayment;

    /**
     * {@inheritdoc}
     */
    public function getRequired()
    {
        return [
            'amount',
            'currency'
        ];
    }
}
