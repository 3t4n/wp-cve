<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Cleaner;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductDAO;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportOptionsDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportMapperDataProvider;
use WC_Product_Factory;
use WC_Product_Variation;
use WC_Product_Variable;
use WC_Product;
/**
 * Class ProductImporterService, manages the product creation process.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Importer
 */
class ProductCleanerService
{
    const MAX_PROCESS_TIME_DELAY = 5;
    const MAX_PROCESS_TIME = 60;
    const MAX_PRODUCTS_TO_CHECK = 50;
    /**
     * @var DataProviderFactory
     */
    private $data_provider_factory;
    /**
     * @var ProductDAO
     */
    private $product_dao;
    /**
     * @var int
     */
    private $start_time = 0;
    /**
     * @var int
     */
    private $max_execution_time = 0;
    /**
     * @var bool
     */
    private $is_finished = \false;
    /**
     *
     * @var WC_Product_Factory
     */
    private $product_factory;
    /**
     *
     * @var ImportOptionsDataProvider
     */
    private $options_data_provider;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory $data_provider_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductDAO $product_dao, \WC_Product_Factory $product_factory)
    {
        $this->data_provider_factory = $data_provider_factory;
        $this->product_dao = $product_dao;
        $this->product_factory = $product_factory;
        $this->start_time = \time();
        $max_execution_time = \intval(\ini_get('max_execution_time'));
        $this->max_execution_time = $max_execution_time > 0 ? $max_execution_time - self::MAX_PROCESS_TIME_DELAY : self::MAX_PROCESS_TIME;
    }
    public function clean(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import
    {
        $mapper_data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportMapperDataProvider::class, ['postfix' => $file_import->get_uid()]);
        $this->options_data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportOptionsDataProvider::class, ['postfix' => $file_import->get_uid()]);
        $stock_action = $this->options_data_provider->has(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::FIELD_REMOVED_PRODUCTS) ? $this->options_data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::FIELD_REMOVED_PRODUCTS) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::OPTION_NO_PRODUCT_DO_NOTHING;
        $leave_main_product = \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::OPTION_NO_PRODUCT_DO_NOTHING === $stock_action;
        $is_variable = \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_TYPE_OPTION_VARIABLE === $mapper_data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_TYPE);
        while (\false === $this->is_cleaner_should_finish() && \false === $file_import->is_cleaner_finished()) {
            $products = [];
            if ($is_variable) {
                $products = $this->product_variation_cleaner($file_import);
                if (empty($products)) {
                    $products = $this->product_variable_sync($file_import);
                }
            }
            if (empty($products)) {
                $products = \true === $leave_main_product ? [] : $this->product_cleaner($file_import, $stock_action);
            }
            if (empty($products)) {
                $this->finish_cleaner($file_import);
                return $file_import;
            }
        }
        return $file_import;
    }
    public function is_finished() : bool
    {
        return $this->is_finished;
    }
    private function product_variation_cleaner(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import) : array
    {
        $products = $this->product_dao->find_not_imported_variations($file_import->get_uid(), $file_import->get_start_date(), self::MAX_PRODUCTS_TO_CHECK);
        $products = $this->remove_products($products);
        return $products;
    }
    private function product_variable_sync(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import) : array
    {
        $products = $this->product_dao->find_variable_products_to_sync($file_import->get_uid(), $file_import->get_start_date(), self::MAX_PRODUCTS_TO_CHECK);
        foreach ($products as $product) {
            \WC_Product_Variable::sync($product);
            if ($product instanceof \WC_Product_Variable) {
                if ($product->get_meta(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductDAO::HAS_PARENT_META_KEY) === \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductDAO::HAS_PARENT_VALUE_NO) {
                    $product = $this->regenerate_images_for_product_variable($product);
                }
                $product = $this->regenerate_attributes($product);
                \WC_Product_Variable::sync($product);
            }
            $product->update_meta_data(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductDAO::RESYNC_META_KEY, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductDAO::RESYNC_VALUE_NO);
            $this->product_dao->save($product);
        }
        return $products;
    }
    private function regenerate_attributes(\WC_Product_Variable $variable) : \WC_Product_Variable
    {
        $attributes = $variable->get_attributes();
        $children = $variable->get_children();
        $items_array = $items_array_keys = [];
        foreach ($children as $child_id) {
            $child = $this->product_factory->get_product($child_id);
            if (\is_object($child) && $child instanceof \WC_Product_Variation) {
                $items_array[] = $child->get_attributes();
            }
            unset($child);
        }
        $items_array_keys = $this->get_only_used_attributes_keys($items_array);
        $attributes = \array_filter($attributes, function ($v, $k) use($items_array_keys) {
            return \false === $v->get_variation() || \in_array($k, $items_array_keys);
        }, \ARRAY_FILTER_USE_BOTH);
        $variable->set_attributes([]);
        $variable->set_attributes($attributes);
        $variable->save();
        return $variable;
    }
    private function get_only_used_attributes_keys(array $items_array) : array
    {
        $result = [];
        foreach ($items_array as $item) {
            foreach ($item as $key => $val) {
                if ($val !== '') {
                    $result[] = $key;
                }
            }
        }
        return \array_unique($result);
    }
    private function regenerate_images_for_product_variable(\WC_Product_Variable $product) : \WC_Product_Variable
    {
        $images = [];
        $sync_fields = $this->options_data_provider->has(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD) ? $this->options_data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD) : [];
        if (\in_array(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_IMAGES, $sync_fields)) {
            foreach ($product->get_children() as $child_id) {
                $child = $this->product_factory->get_product($child_id);
                if (\is_object($child) && $child instanceof \WC_Product_Variation) {
                    $images[] = $child->get_image_id();
                }
                unset($child);
            }
            if (!empty($images)) {
                $product->set_image_id(\reset($images));
                $product->set_gallery_image_ids($images);
            }
        }
        return $product;
    }
    private function product_cleaner(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import, string $stock_action) : array
    {
        $products = [];
        switch ($stock_action) {
            case \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::OPTION_NO_PRODUCT_TRASH:
                $products = $this->product_dao->find_not_imported_products($file_import->get_uid(), $file_import->get_start_date(), self::MAX_PRODUCTS_TO_CHECK);
                $products = $this->move_products_to_trash($products);
                break;
            default:
                $products = $this->product_dao->find_not_imported_products($file_import->get_uid(), $file_import->get_start_date(), self::MAX_PRODUCTS_TO_CHECK, \true);
                $products = $this->set_empty_stock($products);
        }
        return $products;
    }
    private function move_products_to_trash(array $products) : array
    {
        $result = [];
        foreach ($products as $product) {
            if (\is_object($product) && $product instanceof \WC_Product) {
                $result[] = $product;
                $this->product_dao->delete($product);
            }
        }
        return $result;
    }
    private function remove_products(array $products) : array
    {
        $result = [];
        foreach ($products as $product) {
            if (\is_object($product) && $product instanceof \WC_Product) {
                $result[] = $product;
                $this->product_dao->delete($product, \true);
            }
        }
        return $result;
    }
    private function set_empty_stock(array $products) : array
    {
        $result = [];
        foreach ($products as $product) {
            if (\is_object($product)) {
                if ($product instanceof \WC_Product_Variable) {
                    $children = $product->get_children();
                    if (!empty($children)) {
                        foreach ($children as $child_id) {
                            $child = $this->product_factory->get_product($child_id);
                            if (\is_object($child) && $child instanceof \WC_Product_Variation) {
                                $child->set_stock_quantity(0);
                                $child->set_stock_status('outofstock');
                                $this->product_dao->save($child);
                            }
                        }
                    }
                    $product->set_stock_status('outofstock');
                    \WC_Product_Variable::sync($product);
                    $this->product_dao->save($product);
                } else {
                    \wc_update_product_stock($product->get_id(), 0);
                }
                $result[] = $product;
            }
        }
        return $result;
    }
    private function is_cleaner_should_finish() : bool
    {
        return $this->max_execution_time <= \time() - $this->start_time;
    }
    private function finish_cleaner(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import)
    {
        $this->is_finished = \true;
    }
}
