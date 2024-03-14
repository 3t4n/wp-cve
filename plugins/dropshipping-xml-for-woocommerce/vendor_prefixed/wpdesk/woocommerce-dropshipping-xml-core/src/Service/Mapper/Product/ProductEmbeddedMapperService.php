<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Logger\ImportLoggerService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\Abstraction\ProductMapperServiceInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductDAO;
use WC_Product_Variation;
use WC_Product;
use WC_Product_External;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\ConditionalLogic\PriceModificatorService;
/**
 * Class ProductEmbeddedMapperService, embedded variation mapper.
 */
class ProductEmbeddedMapperService implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\Abstraction\ProductMapperServiceInterface
{
    /**
     * @var ImportMapperService
     */
    private $mapper;
    /**
     * @var WC_Product
     */
    private $variable;
    /**
     * @var ImportLoggerService
     */
    private $logger;
    /**
     * @var PriceModificatorService
     */
    private $price_modificator;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService $mapper, \WC_Product $variable, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Logger\ImportLoggerService $logger, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\ConditionalLogic\PriceModificatorService $price_modificator)
    {
        $this->price_modificator = $price_modificator;
        $this->logger = $logger;
        $this->mapper = $mapper;
        $this->variable = $variable;
        $this->price_modificator->set_mapper($mapper);
    }
    public function update_product(\WC_Product $wc_product) : \WC_Product
    {
        $wc_product->set_virtual(\DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_VIRTUAL, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED));
        $wc_product = $this->update_description($wc_product);
        $wc_product = $this->update_pricing($wc_product);
        $wc_product = $this->update_tax($wc_product);
        $wc_product = $this->update_stock($wc_product);
        $wc_product = $this->update_shipping($wc_product);
        $wc_product = $this->update_sku($wc_product);
        return $wc_product;
    }
    private function is_field_mapped_by_parent(string $string) : bool
    {
        if (\DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_PARENT_SELECTOR, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED)) {
            $options = $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_PARENT_OPTIONS, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
            return \is_array($options) ? \in_array($string, $options) : \false;
        }
        return \false;
    }
    private function update_description(\WC_Product $wc_product) : \WC_Product
    {
        $description = '';
        if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_DESCRIPTION, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED)) {
            $description = $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_DESCRIPTION, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
        }
        if (\is_string($description)) {
            $wc_product->set_description(\apply_filters(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductMapperService::FILTER_NAME_DESCRIPTION, $description));
        }
        return $wc_product;
    }
    private function update_sku(\WC_Product $wc_product) : \WC_Product
    {
        $sku = '';
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_STOCK_SKU)) {
            if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_SKU, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED)) {
                $sku = $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_SKU, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
                $sku = \wc_clean(\trim($sku));
            }
            if (\is_string($sku)) {
                if (!(!empty($wc_product->get_id()) && !\wc_product_has_unique_sku($wc_product->get_id(), $sku))) {
                    $wc_product->set_sku($sku);
                }
            }
        }
        return $wc_product;
    }
    private function update_pricing(\WC_Product $wc_product) : \WC_Product
    {
        $calculated_price = null;
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_GENERAL_PRICE)) {
            if ($this->is_field_mapped_by_parent(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_PARENT_OPTIONS_REGULAR_PRICE_VALUE)) {
                $wc_product->set_price($this->variable->get_price());
                $wc_product->set_regular_price($this->variable->get_regular_price());
            } else {
                if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_PRICE, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED)) {
                    if (!empty($this->format_number($this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_PRICE, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED)))) {
                        $calculated_price = $this->get_regular_price();
                        $wc_product->set_price(\strval($calculated_price));
                        $wc_product->set_regular_price(\strval($calculated_price));
                    }
                }
            }
        }
        $calculated_price = null;
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_GENERAL_SALE_PRICE)) {
            if ($this->is_field_mapped_by_parent(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_PARENT_OPTIONS_SALE_PRICE_VALUE)) {
                $wc_product->set_sale_price($this->variable->get_sale_price());
            } else {
                if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SALE_PRICE, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED)) {
                    $calculated_price = $this->get_sale_price();
                    $wc_product->set_sale_price(\strval($calculated_price));
                }
            }
        }
        return $wc_product;
    }
    private function update_tax(\WC_Product $wc_product) : \WC_Product
    {
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_GENERAL_TAX_STATUS)) {
            $tax_status = $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_TAX_STATUS, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
            if (!empty($tax_status)) {
                $wc_product->set_tax_status($tax_status);
            }
        }
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_GENERAL_TAX_CLASS)) {
            $wc_product->set_tax_class($this->get_tax_class());
        }
        return $wc_product;
    }
    private function update_shipping(\WC_Product $wc_product) : \WC_Product
    {
        $weight = $length = $width = $height = $shipping_class_id = null;
        // phpcs:ignore
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_SHIPPING_WEIGHT)) {
            if ($this->is_field_mapped_by_parent(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_PARENT_OPTIONS_WEIGHT_VALUE)) {
                $wc_product->set_weight($this->variable->get_weight());
            } else {
                if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_WEIGHT, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED)) {
                    $weight = \apply_filters(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductMapperService::FILTER_NAME_WEIGHT, $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_WEIGHT, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED));
                    $weight = $this->format_number($weight);
                }
                if (\is_numeric($weight)) {
                    $wc_product->set_weight($weight);
                }
            }
        }
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_SHIPPING_DIMENSIONS)) {
            if ($this->is_field_mapped_by_parent(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_PARENT_OPTIONS_DIMENSIONS_VALUE)) {
                $wc_product->set_length($this->variable->get_length());
                $wc_product->set_width($this->variable->get_width());
                $wc_product->set_height($this->variable->get_height());
            } else {
                if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_LENGTH, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED)) {
                    $length = $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_LENGTH, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
                    if ($length !== '') {
                        $length = $this->format_number($length);
                    }
                }
                if (\is_numeric($length)) {
                    $wc_product->set_length($length);
                }
                if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_WIDTH, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED)) {
                    $width = $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_WIDTH, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
                    if ($width !== '') {
                        $width = $this->format_number($width);
                    }
                }
                if (\is_numeric($width)) {
                    $wc_product->set_width($width);
                }
                if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_HEIGHT, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED)) {
                    $height = $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_HEIGHT, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
                    if ($height !== '') {
                        $height = $this->format_number($height);
                    }
                }
                if (\is_numeric($height)) {
                    $wc_product->set_height($height);
                }
            }
        }
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_SHIPPING_CLASS)) {
            if ($this->is_field_mapped_by_parent(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_PARENT_OPTIONS_SHIPPING_VALUE)) {
                $wc_product->set_shipping_class_id($this->variable->get_shipping_class_id());
            } else {
                if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_SHIPPING_CLASS, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED)) {
                    $shipping_class_id = $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_SHIPPING_CLASS, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
                }
                if ($shipping_class_id !== null) {
                    $wc_product->set_shipping_class_id($shipping_class_id);
                }
            }
        }
        return $wc_product;
    }
    private function update_stock(\WC_Product $wc_product) : \WC_Product
    {
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_STOCK_MANAGMENT)) {
            if ($this->is_field_mapped_by_parent(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_PARENT_OPTIONS_STOCK_VALUE)) {
                $wc_product->set_manage_stock($this->variable->get_manage_stock());
                $wc_product->set_stock_quantity($this->variable->get_stock_quantity());
                $wc_product->set_stock_status($this->variable->get_stock_status());
                $wc_product->set_backorders($this->variable->get_backorders());
                \wc_update_product_stock($wc_product->get_id(), $this->variable->get_stock_quantity());
            } else {
                $stock_enabled = \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_MANAGE_STOCK, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
                $wc_product->set_manage_stock($stock_enabled);
                if ($stock_enabled) {
                    $has_stock_value = $this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_STOCK, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
                    $stock = $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_STOCK, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
                    if ($stock !== '') {
                        $stock = $this->format_number(\apply_filters(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductMapperService::FILTER_NAME_STOCK, $stock));
                    }
                    $stock = !empty($stock) && \is_numeric($stock) ? $stock : 0;
                    if ($wc_product->get_id() > 0) {
                        if ($has_stock_value) {
                            $wc_product->set_stock_quantity($stock);
                            $wc_product->set_stock_status($stock > 0 ? 'instock' : 'outofstock');
                            \wc_update_product_stock($wc_product->get_id(), $stock);
                        }
                    } else {
                        $wc_product->set_stock_quantity($stock);
                        $wc_product->set_stock_status($stock > 0 ? 'instock' : 'outofstock');
                    }
                    if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_BACKORDERS, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED)) {
                        $wc_product->set_backorders($this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_BACKORDERS, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED));
                    }
                } else {
                    if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_STOCK_STATUS, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED)) {
                        $wc_product->set_stock_status($this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_STOCK_STATUS, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED));
                    }
                }
            }
        }
        return $wc_product;
    }
    private function get_regular_price() : float
    {
        $raw_price = $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_PRICE, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
        $result = 0;
        $price = $this->format_number($raw_price);
        $price = \apply_filters(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductMapperService::FILTER_NAME_PRICE_BEFORE_MOD, $price, $raw_price);
        $price = (float) $price;
        $result = $this->price_modificator->get_regular_price($price);
        $result = \apply_filters(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductMapperService::FILTER_NAME_PRICE_AFTER_MOD, $result);
        return (float) $result;
    }
    private function get_sale_price() : float
    {
        $raw_price = $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SALE_PRICE, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
        $result = 0;
        $price = $this->format_number($raw_price);
        $price = \apply_filters(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductMapperService::FILTER_NAME_PRICE_BEFORE_MOD, $price, $raw_price);
        $price = (float) $price;
        $result = $this->price_modificator->get_sale_price($price);
        $result = \apply_filters(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductMapperService::FILTER_NAME_PRICE_AFTER_MOD, $result);
        return (float) $result;
    }
    private function format_number(string $number) : float
    {
        if (!empty($number)) {
            $number = \filter_var(\str_replace(',', '.', $number), \FILTER_SANITIZE_NUMBER_FLOAT, \FILTER_FLAG_ALLOW_FRACTION);
        } else {
            $number = 0;
        }
        return (float) $number;
    }
    private function get_mapped_tax_class(string $mapped_field) : string
    {
        $result = '';
        $tax_classes = $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_TAX_CLASS_MULTI_MAP, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
        $items_nr = \is_array(\reset($tax_classes)) ? \count(\reset($tax_classes)) : 1;
        for ($i = 0; $i < $items_nr; $i++) {
            if (isset($tax_classes[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_TAX_CLASS_MULTI_MAP_ID][$i]) && isset($tax_classes[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_TAX_CLASS_MULTI_MAP_VALUE][$i])) {
                $tax_class_id = \trim($tax_classes[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_TAX_CLASS_MULTI_MAP_ID][$i]);
                $value = \trim($tax_classes[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_TAX_CLASS_MULTI_MAP_VALUE][$i]);
                if ($mapped_field === $value) {
                    return $tax_class_id;
                }
            }
        }
        return $result;
    }
    private function get_tax_class() : string
    {
        $result = $mapped_tax_class_field = $mapped_tax_class = '';
        $single = \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_FALSE === $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_TAX_CLASS_XPATH_SWITCHER, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
        if ($single) {
            $result = $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_TAX_CLASS, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
        } else {
            $mapped_tax_class_field = $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_TAX_CLASS_MAPPER_FIELD, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
            if (!empty($mapped_tax_class_field)) {
                foreach (\explode(',', $mapped_tax_class_field) as $tax_class_to_map) {
                    $mapped_tax_class = '';
                    $mapped_tax_class = \trim($this->mapper->get_mapped_content(\trim($tax_class_to_map)));
                    if (!empty($mapped_tax_class)) {
                        $result = $this->get_mapped_tax_class($mapped_tax_class);
                    }
                }
            }
        }
        return $result;
    }
}
