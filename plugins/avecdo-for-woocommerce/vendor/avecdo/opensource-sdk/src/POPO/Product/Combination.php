<?php

namespace Avecdo\SDK\POPO\Product;

use Avecdo\SDK\POPO\Product;

class Combination
{
    /**
     * @var string
     */
    protected $parentProductId = null;

    /**
     * @var string
     */
    protected $combinationId = null;

    /**
     * @var string
     */
    protected $combinationName = null;

    /**
     * @var string
     */
    protected $combinationSku = null;

    /**
     * @var string
     */
    protected $combinationManufacturerSku = null;

    /**
     * @var string
     */
    protected $combinationUrl = null;

    /**
     * @var string
     */
    protected $combinationLocation = null;

    /**
     * @var float
     */
    protected $combinationPrice = null;

    /**
     * @var int
     */
    protected $combinationQuantity = 0;

    /**
     * @var array
     */
    protected $combinationImages = array();

    /**
     * @var array
     */
    protected $combinationAttributes = array();

    /**
     * @var array
     */
    protected $combinationGtin = array(
        'ean'   => '',
        'isbn'  => '',
        'jan'   => '',
        'upc'   => ''
    );

    /**
     * @var array
     */
    protected $combinationWeight = array(
        'weight'    => '',
        'unit'      => ''
    );

    /**
     * @var array
     */
    protected $combinationDimensions = array(
        'depth'     => '',
        'width'     => '',
        'height'    => '',
        'unit'      => ''
    );

    /**
     * @var Product
     */
    private $parentProduct = null;

    /**
     * Combination constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->parentProduct = $product;
        $this->instantiateParentValues();
    }

    /**
     * Instantiates some values from the parent product.
     * TODO - Evaluate which values should be inherited
     */
    private function instantiateParentValues()
    {
        foreach ($this->parentProduct->getAll() as $key => $value) {
            switch ($key) {
                case 'productId':
                    $this->parentProductId = $value;
                    break;

                case 'sku':
                    $this->combinationSku = $value;
                    break;

                case 'manufacturerSku':
                    $this->combinationManufacturerSku = $value;
                    break;

                case 'gtin':
                    $this->combinationGtin = $value;
                    break;

                case 'weight':
                    $this->combinationWeight = $value;
                    break;

                case 'dimensions':
                    $this->combinationDimensions = $value;
                    break;
            }
        }
    }


    /**
     * @param bool $excludeParentObject
     * @return array
     */
    public function getAll($excludeParentObject = true)
    {
        if ($excludeParentObject && isset($this->parentProduct)) {
            unset($this->parentProduct);
        }

        return get_object_vars($this);
    }

    /**
     * @param $parentProductId
     * @return $this
     */
    public function setParentProductId($parentProductId)
    {
        $this->parentProductId = $parentProductId;

        return $this;
    }

    /**
     * @param $combinationId
     * @return $this
     */
    public function setCombinationId($combinationId)
    {
        $this->combinationId = $combinationId;

        return $this;
    }

    /**
     * @param $combinationName
     * @return $this
     */
    public function setCombinationName($combinationName)
    {
        $this->combinationName = $combinationName;

        return $this;
    }

    /**
     * @param $combinationSku
     * @return $this
     */
    public function setCombinationSku($combinationSku)
    {
        $this->combinationSku = $combinationSku;

        return $this;
    }

    /**
     * @param $combinationManufacturerSku
     * @return $this
     */
    public function setCombinationManufacturerSku($combinationManufacturerSku)
    {
        $this->combinationManufacturerSku = $combinationManufacturerSku;

        return $this;
    }

    /**
     * @param $combinationUrl
     * @return $this
     */
    public function setCombinationUrl($combinationUrl)
    {
        $this->combinationUrl = $combinationUrl;

        return $this;
    }

    /**
     * @param $combinationLocation
     * @return $this
     */
    public function setCombinationLocation($combinationLocation)
    {
        $this->combinationLocation = $combinationLocation;

        return $this;
    }


    /**
     * @param $combinationPrice
     * @return $this
     */
    public function setCombinationPrice($combinationPrice)
    {
        $this->combinationPrice = $combinationPrice;

        return $this;
    }

    /**
     * @param $combinationQuantity
     * @return $this
     */
    public function setCombinationQuantity($combinationQuantity)
    {
        $this->combinationQuantity = $combinationQuantity;

        return $this;
    }

    /**
     * @param $combinationEan
     * @return $this
     */
    public function setCombinationEan($combinationEan)
    {
        $this->combinationGtin['ean'] = $combinationEan;

        return $this;
    }

    /**
     * @param $combinationUpc
     * @return $this
     */
    public function setCombinationUpc($combinationUpc)
    {
        $this->combinationGtin['upc'] = $combinationUpc;

        return $this;
    }

    /**
     * @param $combinationJan
     * @return $this
     */
    public function setCombinationJan($combinationJan)
    {
        $this->combinationGtin['jan'] = $combinationJan;

        return $this;
    }

    /**
     * @param $combinationIsbn
     * @return $this
     */
    public function setCombinationIsbn($combinationIsbn)
    {
        $this->combinationGtin['isbn'] = $combinationIsbn;

        return $this;
    }

    /**
     * @param $combinationWeightUnit
     * @return $this
     */
    public function setCombinationWeightUnit($combinationWeightUnit)
    {
        $this->combinationWeight['unit'] = $combinationWeightUnit;

        return $this;
    }

    /**
     * @param $combinationWeight
     * @return $this
     */
    public function setCombinationWeight($combinationWeight)
    {
        $this->combinationWeight['weight'] = $combinationWeight;

        return $this;
    }

    /**
     * @param $combinationDimensionUnit
     * @return $this
     */
    public function setCombinationDimensionUnit($combinationDimensionUnit)
    {
        $this->combinationDimensions['unit'] = $combinationDimensionUnit;

        return $this;
    }

    /**
     * @param $combinationWidth
     * @return $this
     */
    public function setCombinationDimensionWidth($combinationWidth)
    {
        $this->combinationDimensions['width'] = $combinationWidth;

        return $this;
    }

    /**
     * @param $combinationHeight
     * @return $this
     */
    public function setCombinationDimensionHeight($combinationHeight)
    {
        $this->combinationDimensions['height'] = $combinationHeight;

        return $this;
    }

    /**
     * @param $combinationDepth
     * @return $this
     */
    public function setDimensionDepth($combinationDepth)
    {
        $this->combinationDimensions['depth'] = $combinationDepth;

        return $this;
    }

    /**
     * @param $attributeId
     * @param $name
     * @param $value
     * @return $this
     */
    public function addToCombinationAttributes($attributeId, $name, $value)
    {
        array_push($this->combinationAttributes, array(
            'attributeId' => $attributeId,
            'name'        => $name,
            'value'       => $value
        ));

        return $this;
    }

    /**
     * @param $url
     * @param $text
     * @return $this
     */
    public function addToCombinationImages($url, $text)
    {
        array_push($this->combinationImages, array(
            'url'   => $url,
            'text'  => $text
        ));

        return $this;
    }

}
