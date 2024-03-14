<?php

namespace Avecdo\SDK\POPO;

class Product
{
    /**
     * @var string
     */
    protected $sku = null;

    /**
     * @var string
     */
    protected $internalId = null;

    /**
     * @var string
     */
    protected $productId = null;

    /**
     * @var string
     */
    protected $parentId = null;

    /**
     * @var string
     */
    protected $variationName = null;

    /**
     * @var string
     */
    protected $name = null;

    /**
     * @var string
     */
    protected $url = null;

    /**
     * @var array
     */
    protected $categories = array();

    /**
     * @var array
     */
    protected $images = array();

    /**
     * @var string
     */
    protected $description = null;

    /**
     * @var array
     */
    protected $weight = array(
        'weight'    => '',
        'unit'      => ''
    );

    /**
     * @var array
     */
    protected $dimensions = array(
        'depth'     => '',
        'width'     => '',
        'height'    => '',
        'unit'      => ''
    );

    /**
     * @var array
     */
    protected $attributes = array();

    /**
     * @var string
     */
    protected $state = null;

    /**
     * @var string
     */
    protected $warranty = null;

    /**
     * @var array
     */
    protected $gtin = array(
        'ean'   => '',
        'isbn'  => '',
        'jan'   => '',
        'upc'   => ''
    );

    /**
     * @var array
     */
    protected $tags = array();

    /**
     * @var array
     */
    protected $relatedProducts = array();

    /**
     * @var float
     */
    protected $price = null;

    /**
     * @var float
     */
    protected $priceSale = null;

    /**
     * @var string
     */
    protected $currency = null;

    /**
     * @var string
     */
    protected $manufacturer = null;

    /**
     * @var string
     */
    protected $manufacturerSku = null;

    /**
     * @var string
     */
    protected $brand = null;

    /**
     * @var string
     */
    protected $mpn = null;

    /**
     * @var int
     */
    protected $stockQuantity = 0;

    /**
     * 1 = in stock, 0 = not in stock
     *
     * @var bool
     */
    protected $stockStatus = null;

    /**
     * @var string
     */
    protected $deliveryTime = null;

    /**
     * @var float
     */
    protected $shippingCost = null;

    /**
     * @var array
     */
    protected $combinations = array();

    /**
     * @var array
     */
    protected $extras = array();

    /**
     * @return array
     */
    public function getAll()
    {
        return get_object_vars($this);
    }

    /**
     * @param $sku
     * @return $this
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setInternalId($id)
    {
        $this->internalId = $id;

        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setProductId($id)
    {
        $this->productId = $id;

        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setParentId($id)
    {
        $this->parentId = $id;

        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setVariationName($name)
    {
        $this->variationName = $name;

        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param $id
     * @param $parentId
     * @param $name
     * @return $this
     */
    public function addToCategories($id, $parentId, $name)
    {
        array_push($this->categories, array(
            'id'        => $id,
            'parentId' => $parentId,
            'name'      => $name
        ));

        return $this;
    }

    /**
     * @param string  $url
     * @param string  $text
     * @param int     $id
     * @param int     $position
     * @param string  $url
     * @param string  $text
     * @param boolean $main
     * @param int     $width
     * @param int     $height
     * @return $this
     */
    public function addToImages($url, $text, $id = null, $position = null, $main = null, $width = null, $height = null)
    {
        array_push($this->images, array(
            'url'      => $url,
            'text'     => $text,
            'id'       => $id,
            'position' => $position,
            'main'     => $main,
            'width'    => $width,
            'height'   => $height
        ));

        return $this;
    }

    /**
     * @param $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param $weightUnit
     * @return $this
     */
    public function setWeightUnit($weightUnit)
    {
        $this->weight['unit'] = $weightUnit;

        return $this;
    }

    /**
     * @param $weight
     * @return $this
     */
    public function setWeight($weight)
    {
        $this->weight['weight'] = $weight;

        return $this;
    }

    /**
     * @param $dimensionUnit
     * @return $this
     */
    public function setDimensionUnit($dimensionUnit)
    {
        $this->dimensions['unit'] = $dimensionUnit;

        return $this;
    }

    /**
     * @param $width
     * @return $this
     */
    public function setDimensionWidth($width)
    {
        $this->dimensions['width'] = $width;

        return $this;
    }

    /**
     * @param $height
     * @return $this
     */
    public function setDimensionHeight($height)
    {
        $this->dimensions['height'] = $height;

        return $this;
    }

    /**
     * @param $depth
     * @return $this
     */
    public function setDimensionDepth($depth)
    {
        $this->dimensions['depth'] = $depth;

        return $this;
    }

    /**
     * @param $attributeId
     * @param $name
     * @param $text
     * @return $this
     */
    public function addToAttributes($attributeId, $name, $text)
    {
        array_push($this->attributes, array(
            'attributeId' => $attributeId,
            'name' => $name,
            'text' => $text
        ));

        return $this;
    }

    /**
     * @param $state
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @param $warranty
     * @return $this
     */
    public function setWarranty($warranty)
    {
        $this->warranty = $warranty;

        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function addToTags($name)
    {
        array_push($this->tags, array(
            'name' => $name
        ));

        return $this;
    }

    /**
     * @param $id
     * @param $name
     * @return $this
     */
    public function addToRelatedProducts($id, $name)
    {
        array_push($this->relatedProducts, array(
            'id'    => $id,
            'name'  => $name
        ));

        return $this;
    }

    /**
     * @param $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @param $priceSale
     * @return $this
     */
    public function setPriceSale($priceSale)
    {
        $this->priceSale = $priceSale;

        return $this;
    }

    /**
     * @param $currency
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @param $manufacturer
     * @return $this
     */
    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * @param $manufacturerSku
     * @deprecated
     * @return $this
     */
    public function setManufacturerSku($manufacturerSku)
    {
        return $this->setMpn($manufacturerSku);
    }

    /**
     * @param $brand
     * @return $this
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @param $mpn
     * @return $this
     */
    public function setMpn($mpn)
    {
        $this->mpn = $mpn;

        return $this;
    }

    /**
     * @param $ean
     * @return $this
     */
    public function setEan($ean)
    {
        $this->gtin['ean'] = $ean;

        return $this;
    }

    public function setUpc($upc)
    {
        $this->gtin['upc'] = $upc;

        return $this;
    }

    public function setJan($jan)
    {
        $this->gtin['jan'] = $jan;

        return $this;
    }

    public function setIsbn($isbn)
    {
        $this->gtin['isbn'] = $isbn;

        return $this;
    }

    /**
     * @param $stockQuantity
     * @return $this
     */
    public function setStockQuantity($stockQuantity)
    {
        $this->stockQuantity = $stockQuantity;

        return $this;
    }

    /**
     * Should be of Product\ShopSystem type
     *
     * @param $stockStatus
     * @return $this
     */
    public function setStockStatus($stockStatus)
    {
        $this->stockStatus = $stockStatus;

        return $this;
    }

    /**
     * @param $deliveryTime
     * @return $this
     */
    public function setDeliveryTime($deliveryTime)
    {
        $this->deliveryTime = $deliveryTime;

        return $this;
    }

    /**
     * @param $shippingCost
     * @return $this
     */
    public function setShippingCost($shippingCost)
    {
        $this->shippingCost = $shippingCost;

        return $this;
    }

    /**
     * @param $combination
     * @deprecated since 1.2.3
     * @return $this
     */
    public function addToCombinations($combination)
    {
        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function addToExtras($key, $value)
    {
        array_push($this->extras, array(
            'key'   => $key,
            'value' => $value
        ));

        return $this;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }
}
