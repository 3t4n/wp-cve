<?php

namespace Wcd\DiscountRules\Types;
if (!defined('ABSPATH')) exit;

abstract class AbstractType
{
    public $type_info, $prefix = 'Wcd_', $tab_group = "Wcd_tabs", $applied_rule = array('show' => 1);

    /**
     * Initiate the Discount info
     */
    public function init()
    {
        $this->type_info = $this->setDiscountTypeInfo();
        $this->validateDiscountTypeInfo();
        $this->renderDiscountField();
    }

    /**
     * Validate the discount Info
     */
    private function validateDiscountTypeInfo()
    {
        if (!array_key_exists('slug', $this->type_info) || !array_key_exists('label', $this->type_info)) {
            throw new \Error('type_info must have "slug" and "label" as keys');
        }
    }

    abstract protected function setDiscountTypeInfo();

    abstract protected function renderDiscountField();

    abstract protected function calculateDiscount($item);
}