<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Logger\ImportLoggerService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\Abstraction\ProductMapperServiceInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService;
use WC_Product_Variation;
use WC_Product;
use WC_Product_External;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\ConditionalLogic\PriceModificatorService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields;
/**
 * Class ProductMapperService, basic product information mapper.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Mapper
 */
class ProductMapperService implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\Abstraction\ProductMapperServiceInterface
{
    const FILTER_NAME_DESCRIPTION = 'wpdesk_dropshipping_mapper_description';
    const FILTER_NAME_SHORT_DESCRIPTION = 'wpdesk_dropshipping_mapper_short_description';
    const FILTER_NAME_SHORT_TITLE = 'wpdesk_dropshipping_mapper_title';
    const FILTER_NAME_STOCK = 'wpdesk_dropshipping_mapper_stock';
    const FILTER_NAME_WEIGHT = 'wpdesk_dropshipping_mapper_weight';
    const FILTER_NAME_LENGTH = 'wpdesk_dropshipping_mapper_length';
    const FILTER_NAME_WIDTH = 'wpdesk_dropshipping_mapper_width';
    const FILTER_NAME_HEIGHT = 'wpdesk_dropshipping_mapper_height';
    const FILTER_NAME_PRICE_BEFORE_MOD = 'wpdesk_dropshipping_mapper_price_before_mod';
    const FILTER_NAME_PRICE_AFTER_MOD = 'wpdesk_dropshipping_mapper_price_after_mod';
    /**
     * @var ImportMapperService
     */
    private $mapper;
    /**
     * @var ImportLoggerService
     */
    private $logger;
    /**
     * @var PriceModificatorService
     */
    private $price_modificator;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService $mapper, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Logger\ImportLoggerService $logger, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\ConditionalLogic\PriceModificatorService $price_modificator)
    {
        $this->price_modificator = $price_modificator;
        $this->logger = $logger;
        $this->mapper = $mapper;
        $this->price_modificator->set_mapper($mapper);
    }
    public function update_product(\WC_Product $wc_product) : \WC_Product
    {
        if ('variation' !== $wc_product->get_type()) {
            $wc_product = $this->update_product_status($wc_product);
            $wc_product = $this->update_title($wc_product);
            $wc_product = $this->update_short_description($wc_product);
            if (!$this->is_product_created($wc_product)) {
                $wc_product->set_featured(\false);
                $wc_product->set_catalog_visibility('visible');
                $wc_product->set_reviews_allowed(\true);
            }
        }
        $wc_product->set_virtual(\DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_VIRTUAL));
        $wc_product = $this->update_description($wc_product);
        $wc_product = $this->update_pricing($wc_product);
        $wc_product = $this->update_tax($wc_product);
        $wc_product = $this->update_stock($wc_product);
        $wc_product = $this->update_shipping($wc_product);
        $wc_product = $this->update_sku($wc_product);
        if ('external' === $wc_product->get_type()) {
            $this->update_external_data($wc_product);
        }
        return $wc_product;
    }
    private function update_product_status(\WC_Product $wc_product) : \WC_Product
    {
        if ($this->is_product_created($wc_product)) {
            if ('draft' !== $wc_product->get_status()) {
                $wc_product->set_status('publish');
            }
            return $wc_product;
        }
        $is_draft_default_status = \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->mapper->get_raw_option_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::FIELD_CREATE_NEW_PRODUCTS_AS_DRAFT);
        $status = \true === $is_draft_default_status ? 'draft' : 'publish';
        $wc_product->set_status($status);
        return $wc_product;
    }
    private function update_title(\WC_Product $wc_product) : \WC_Product
    {
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_TITLE)) {
            if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::TITLE)) {
                $wc_product->set_name(\wc_clean(\apply_filters(self::FILTER_NAME_SHORT_TITLE, $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::TITLE))));
            }
        }
        return $wc_product;
    }
    private function update_description(\WC_Product $wc_product) : \WC_Product
    {
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_DESCRIPTION)) {
            if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::CONTENT)) {
                $wc_product->set_description(\apply_filters(self::FILTER_NAME_DESCRIPTION, $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::CONTENT)));
            }
        }
        return $wc_product;
    }
    private function update_short_description(\WC_Product $wc_product) : \WC_Product
    {
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_SHORT_DESCRIPTION)) {
            if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::EXCERPT)) {
                $wc_product->set_short_description(\apply_filters(self::FILTER_NAME_SHORT_DESCRIPTION, $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::EXCERPT)));
            }
        }
        return $wc_product;
    }
    private function update_sku(\WC_Product $wc_product) : \WC_Product
    {
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_STOCK_SKU)) {
            if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SKU)) {
                $sku = \wc_clean(\trim($this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SKU)));
                if (!(!empty($wc_product->get_id()) && !\wc_product_has_unique_sku($wc_product->get_id(), $sku))) {
                    $wc_product->set_sku($sku);
                }
            }
        }
        return $wc_product;
    }
    private function update_pricing(\WC_Product $wc_product) : \WC_Product
    {
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_GENERAL_PRICE)) {
            if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE)) {
                $regular_price = $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE);
                if (\is_numeric($regular_price) || !empty($this->format_number($regular_price))) {
                    $calculated_price = $this->get_regular_price();
                    $wc_product->set_regular_price(\strval($calculated_price));
                    $wc_product->set_price(\strval($calculated_price));
                } else {
                    $wc_product->set_regular_price('');
                    $wc_product->set_price('');
                }
            }
        }
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_GENERAL_SALE_PRICE)) {
            if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SALE_PRICE)) {
                $sale_price = $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SALE_PRICE);
                if (\is_numeric($sale_price) || !empty($this->format_number($sale_price))) {
                    $calculated_price = $this->get_sale_price();
                    $wc_product->set_sale_price(\strval($calculated_price));
                    $wc_product->set_price(\strval($calculated_price));
                } else {
                    $wc_product->set_sale_price('');
                }
            }
        }
        return $wc_product;
    }
    private function get_regular_price() : float
    {
        $raw_price = $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE);
        $result = 0;
        $price = $this->format_number($raw_price);
        $price = \apply_filters(self::FILTER_NAME_PRICE_BEFORE_MOD, $price, $raw_price);
        $price = (float) $price;
        $result = $this->price_modificator->get_regular_price($price);
        $result = \apply_filters(self::FILTER_NAME_PRICE_AFTER_MOD, $result);
        return (float) $result;
    }
    private function get_sale_price() : float
    {
        $raw_price = $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SALE_PRICE);
        $result = 0;
        $price = $this->format_number($raw_price);
        $price = \apply_filters(self::FILTER_NAME_PRICE_BEFORE_MOD, $price, $raw_price);
        $price = (float) $price;
        $result = $this->price_modificator->get_sale_price($price);
        $result = \apply_filters(self::FILTER_NAME_PRICE_AFTER_MOD, $result);
        return (float) $result;
    }
    private function is_product_created(\WC_Product $wc_product) : bool
    {
        return $wc_product->get_id() > 0;
    }
    private function update_tax(\WC_Product $wc_product) : \WC_Product
    {
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_GENERAL_TAX_STATUS)) {
            $tax_status = $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_TAX_STATUS);
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
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_SHIPPING_WEIGHT)) {
            if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_WEIGHT)) {
                $weight = \apply_filters(self::FILTER_NAME_WEIGHT, $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_WEIGHT));
                $weight = $this->format_number($weight);
                if (!empty($weight)) {
                    $wc_product->set_weight($weight);
                }
            }
        }
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_SHIPPING_DIMENSIONS)) {
            if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_LENGTH)) {
                $length = \apply_filters(self::FILTER_NAME_LENGTH, $this->format_number($this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_LENGTH)));
                if (!empty($length)) {
                    $wc_product->set_length($length);
                }
            }
            if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_WIDTH)) {
                $width = \apply_filters(self::FILTER_NAME_WIDTH, $this->format_number($this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_WIDTH)));
                if (!empty($width)) {
                    $wc_product->set_width($width);
                }
            }
            if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_HEIGHT)) {
                $height = \apply_filters(self::FILTER_NAME_HEIGHT, $this->format_number($this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_HEIGHT)));
                if (!empty($height)) {
                    $wc_product->set_height($height);
                }
            }
        }
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_SHIPPING_CLASS)) {
            if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SHIPPING_CLASS)) {
                $shipping_class_id = $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SHIPPING_CLASS);
                $wc_product->set_shipping_class_id($shipping_class_id);
            }
        }
        return $wc_product;
    }
    private function update_stock(\WC_Product $wc_product) : \WC_Product
    {
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_STOCK_MANAGMENT)) {
            $stock_enabled = \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_MANAGE_STOCK);
            $wc_product->set_manage_stock($stock_enabled);
            if ($stock_enabled) {
                $has_stock_value = $this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_STOCK);
                $stock = \apply_filters(self::FILTER_NAME_STOCK, $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_STOCK));
                $stock = $this->format_number($stock);
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
                if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_BACKORDERS)) {
                    $wc_product->set_backorders($this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_BACKORDERS));
                }
                if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_LOW_STOCK)) {
                    $wc_product->set_low_stock_amount($this->format_number($this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_LOW_STOCK)));
                }
            } else {
                if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_STOCK_STATUS)) {
                    $wc_product->set_stock_status($this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_STOCK_STATUS));
                }
            }
            $wc_product->set_sold_individually($this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SOLD_INDIVIDUALLY) === \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE ? \true : \false);
        }
        return $wc_product;
    }
    private function update_external_data(\WC_Product_External $wc_product) : \WC_Product_External
    {
        if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_EXTERNAL_URL)) {
            $wc_product->set_product_url($this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_EXTERNAL_URL));
        }
        if ($this->mapper->has_value_to_map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_EXTERNAL_BUTTON_TEXT)) {
            $wc_product->set_button_text($this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_EXTERNAL_BUTTON_TEXT));
        }
        return $wc_product;
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
        $tax_classes = $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_TAX_CLASS_MULTI_MAP);
        $items_nr = \is_array(\reset($tax_classes)) ? \count(\reset($tax_classes)) : 1;
        for ($i = 0; $i < $items_nr; $i++) {
            if (isset($tax_classes[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_TAX_CLASS_MULTI_MAP_ID][$i]) && isset($tax_classes[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_TAX_CLASS_MULTI_MAP_VALUE][$i])) {
                $tax_class_id = \trim($tax_classes[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_TAX_CLASS_MULTI_MAP_ID][$i]);
                $value = \trim($tax_classes[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_TAX_CLASS_MULTI_MAP_VALUE][$i]);
                if ($mapped_field === $value) {
                    return $tax_class_id;
                }
            }
        }
        return $result;
    }
    private function get_tax_class() : string
    {
        $result = '';
        $single = \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_TAX_CLASS_VALUE_SINGLE === $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_TAX_CLASS_TYPE);
        if ($single) {
            $result = $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_TAX_CLASS);
        } else {
            $mapped_tax_class_field = $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_TAX_CLASS_MAPPER_FIELD);
            if (!empty($mapped_tax_class_field)) {
                foreach (\explode(',', $mapped_tax_class_field) as $tax_class_to_map) {
                    $mapped_tax_class = \trim($this->mapper->get_mapped_content(\trim($tax_class_to_map)));
                    if ('' !== $mapped_tax_class) {
                        $result = $this->get_mapped_tax_class($mapped_tax_class);
                    }
                }
            }
        }
        return $result;
    }
}
