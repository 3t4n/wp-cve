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
 * This class represents Shipping Option entity
 *
 * @method string getName()
 * @method string getCarrier()
 * @method string getCategory()
 * @method float getPrice()
 * @method float getTaxRate()
 * @method float getTaxAmount()
 * @method ShippingOptionDetailsEntity|array getDetails()
 * @method self setName(string $value)
 * @method self setCarrier(string $value)
 * @method self setCategory(string $value)
 * @method self setPrice(float $value)
 * @method self setTaxRate(float $value)
 * @method self setTaxAmount(float $value)
 */
class ShippingOptionEntity extends MessageEntity
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $carrier;

    /**
     * @var string
     */
    protected $category;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var float
     */
    protected $taxRate;

    /**
     * @var float
     */
    protected $taxAmount;

    /**
     * @var string
     */
    protected $details;

    /**
     * {@inheritdoc}
     */
    public function getRequired()
    {
        return [
            'name',
            'price',
            'tax_rate',
            'tax_amount',
            'details'
        ];
    }

    /**
     * Sets Details.
     *
     * @param ShippingOptionDetailsEntity|array $address
     * @return self
     */
    public function setDetails($details)
    {
        if (!$details) {
            return $this;
        }

        if (is_string($details)) {
            $details = json_decode($details);
        }

        if (!is_array($details) && !is_object($details)) {
            return $this;
        }

        $this->details = new ShippingOptionDetailsEntity($details);

        return $this;
    }
}
