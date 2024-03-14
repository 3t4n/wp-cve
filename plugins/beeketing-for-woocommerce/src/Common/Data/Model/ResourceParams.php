<?php

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Model;

/**
 * Help to get common resource params
 *
 * Class ResourceParams
 * @package BeeketingConnect_beeketing_woocommerce\Common\Data\Model
 */
class ResourceParams
{
    const DEFAULT_PAGE = 1;
    const DEFAULT_ITEMS_PER_PAGE = 20;

    public $params;

    /**
     * @param $fieldName
     * @param null $default
     * @return mixed|null
     */
    public function getParamByFieldName($fieldName, $default = null)
    {
        return isset($this->params[$fieldName]) ? $this->params[$fieldName] : $default;
    }

    /**
     * Get Data Id
     *
     * @return int
     */
    public function getResourceId()
    {
        return $this->getParamByFieldName('resource_id')
            ? (int) $this->getParamByFieldName('resource_id')
            : 0;
    }

    /**
     * Get Product Id
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->getParamByFieldName('product_id')
            ? (int) $this->getParamByFieldName('product_id')
            : 0;
    }

    /**
     * Get Item Id
     *
     * @return int
     */
    public function getItemId()
    {
        return $this->getParamByFieldName('item_id')
            ? (int) $this->getParamByFieldName('item_id')
            : 0;
    }

    /**
     * Get Quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->getParamByFieldName('quantity')
            ? (int) $this->getParamByFieldName('quantity')
            : 0;
    }

    /**
     * Get Collection Id
     *
     * @return int
     */
    public function getCollectionId()
    {
        return $this->getParamByFieldName('collection_id')
            ? (int) $this->getParamByFieldName('collection_id')
            : 0;
    }

    /**
     * Get Variant Id
     *
     * @return int
     */
    public function getVariantId()
    {
        return $this->getParamByFieldName('variant_id')
            ? (int) $this->getParamByFieldName('variant_id')
            : 0;
    }

    /**
     * Get Customer Id
     *
     * @return int
     */
    public function getCustomerId()
    {
        return $this->getResourceId();
    }

    /**
     * Get page
     *
     * @return int
     */
    public function getPage()
    {
        return $this->getParamByFieldName('page')
            ? (int) $this->getParamByFieldName('page')
            : self::DEFAULT_PAGE;
    }

    /**
     * Get limit
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->getParamByFieldName('limit')
            ? (int) $this->getParamByFieldName('limit')
            : self::DEFAULT_ITEMS_PER_PAGE;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return is_string($this->getParamByFieldName('title', ''))
            ? $this->getParamByFieldName('title', '')
            : '';
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return is_string($this->getParamByFieldName('status', ''))
            ? $this->getParamByFieldName('status', '')
            : '';
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return is_string($this->getParamByFieldName('type', ''))
            ? $this->getParamByFieldName('type', '')
            : '';
    }
}
