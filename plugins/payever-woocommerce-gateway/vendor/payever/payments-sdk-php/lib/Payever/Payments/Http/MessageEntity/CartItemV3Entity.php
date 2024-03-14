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
 * This class represents Cart Item V3 Entity
 *
 * @method string getName()
 * @method float  getUnitPrice()
 * @method float  getTaxRate()
 * @method float  getQuantity()
 * @method float  getTotalAmount()
 * @method float  getTotalTaxAmount()
 * @method string getDescription()
 * @method string getCategory()
 * @method string getImageUrl()
 * @method string getProductUrl()
 * @method string getSku()
 * @method string getIdentifier()
 * @method AttributesEntity getAttributes()
 * @method string getBrand()
 * @method self   setName(string $name)
 * @method self   setUnitPrice(float $price)
 * @method self   setTaxRate(float $taxRate)
 * @method self   setQuantity(float $quantity)
 * @method self   setTotalAmount(float $total)
 * @method self   setTotalTaxAmount(float $total)
 * @method self   setDescription(string $description)
 * @method self   setCategory(string $category)
 * @method self   setImageUrl(string $url)
 * @method self   setThumbnail(string $thumbnail)
 * @method self   setProductUrl(string $url)
 * @method self   setSku(string $sku)
 * @method self   setIdentifier(string $identifier)
 * @method self   setBrand(string $value)
 */
class CartItemV3Entity extends MessageEntity
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var float
     */
    protected $unitPrice;

    /**
     * @var float
     */
    protected $taxRate;

    /**
     * @var float
     */
    protected $quantity;

    /**
     * @var float
     */
    protected $totalAmount;

    /**
     * @var float
     */
    protected $totalTaxAmount;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $category;

    /**
     * @var string
     */
    protected $imageUrl;

    /**
     * @var string
     */
    protected $productUrl;

    /**
     * @var string
     */
    protected $sku;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var AttributesEntity
     */
    protected $attributes;

    /**
     * @var string
     */
    protected $brand;

    /**
     * Sets Attributes
     *
     * @param AttributesEntity|string $attributes
     *
     * @return $this
     */
    public function setAttributes($attributes)
    {
        if (!$attributes) {
            return $this;
        }

        if (is_string($attributes)) {
            $attributes = json_decode($attributes);
        }

        if (!is_array($attributes) && !is_object($attributes)) {
            return $this;
        }

        $this->attributes = new AttributesEntity($attributes);

        return $this;
    }
}
