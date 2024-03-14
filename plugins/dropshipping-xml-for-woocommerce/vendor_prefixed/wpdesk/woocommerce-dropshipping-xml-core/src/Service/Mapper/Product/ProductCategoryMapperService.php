<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product;

use DropshippingXmlFreeVendor\Automattic\WooCommerce\Vendor\League\Container\Exception\NotFoundException;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Logger\ImportLoggerService;
use WC_Product;
use WP_Term;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\Abstraction\ProductMapperServiceInterface;
use RuntimeException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Exception\WPTermNotCreatedException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Exception\WPTermNotFoundException;
/**
 * Class ProductCreatorService, creates woocommerce product.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Creator
 */
class ProductCategoryMapperService implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\Abstraction\ProductMapperServiceInterface
{
    const CATEGORY_TAXONOMY_NAME = 'product_cat';
    const CATEGORY_META_FULL_NAME = 'dropshipping_full_category_name';
    const FILTER_CATEGORY_SEPARATOR = 'wpdesk_dropshipping_mapper_category_separator';
    /**
     * @var ImportMapperService
     */
    private $mapper;
    /**
     * @var ImportLoggerService
     */
    private $logger;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService $mapper, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Logger\ImportLoggerService $logger)
    {
        $this->logger = $logger;
        $this->mapper = $mapper;
    }
    public function update_product(\WC_Product $wc_product) : \WC_Product
    {
        if ($this->mapper->is_product_field_group_should_be_mapped($wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::SYNC_FIELD_OPTION_CATEGORIES)) {
            $categories = $this->get_categories();
            if (!empty($categories)) {
                $wc_product->set_category_ids($categories);
            }
        }
        return $wc_product;
    }
    private function get_mapped_categories(string $mapped_field) : array
    {
        $result = [];
        $categories = $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES_MULTI_MAP);
        $items_nr = \is_array(\reset($categories)) ? \count(\reset($categories)) : 1;
        for ($i = 0; $i < $items_nr; $i++) {
            if (isset($categories[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES_MULTI_MAP_CATEGORY][$i]) && isset($categories[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES_MULTI_MAP_VALUE][$i])) {
                $category_id = \trim($categories[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES_MULTI_MAP_CATEGORY][$i]);
                $value = \trim($categories[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES_MULTI_MAP_VALUE][$i]);
                if ($mapped_field == $value) {
                    $result[] = $category_id;
                }
            }
        }
        return $result;
    }
    private function get_categories() : array
    {
        $result = [];
        $category_import_type = $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES);
        if (\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES_SINGLE_VALUE === $category_import_type) {
            $result = $this->get_single_category();
        } elseif (\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES_MULTI_VALUE === $category_import_type) {
            $result = $this->get_multiple_mapped_categories();
        } elseif (\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES_TREE_VALUE === $category_import_type) {
            $result = $this->get_tree_categories();
        }
        return $result;
    }
    private function get_tree_categories() : array
    {
        $result = [];
        $parent_separator = \apply_filters(self::FILTER_CATEGORY_SEPARATOR, ',');
        $field_value = \trim($this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES_TREE_FIELD_VALUE));
        $separator = \trim($this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES_TREE_SEPARATOR_VALUE));
        $add_to_all_categories = \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES_TREE_ADD_ALL_VALUE);
        if (!empty($field_value) && !empty($separator)) {
            $categories = \explode($parent_separator, $field_value);
            foreach ($categories as $category_tree) {
                $categorie_names = \explode($separator, $category_tree);
                $parent_id = 0;
                $term = null;
                foreach ($categorie_names as $category_name) {
                    $category_name = \wc_clean($category_name);
                    if (!empty($category_name)) {
                        try {
                            $term = $this->find_category_by_name($category_name, $parent_id);
                        } catch (\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Exception\WPTermNotFoundException $e) {
                            $term = $this->create_category_by_name($category_name, $parent_id);
                        }
                        $parent_id = \is_numeric($term->term_id) ? $term->term_id : $parent_id;
                        if (\true === $add_to_all_categories) {
                            $result = \array_merge($result, [$term->term_id]);
                        }
                    }
                }
                if (\false === $add_to_all_categories && \is_object($term) && $term instanceof \WP_Term) {
                    $result = \array_merge($result, [$term->term_id]);
                }
            }
        }
        return $result;
    }
    private function get_single_category() : array
    {
        $result = [];
        $result[] = $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES_SINGLE_CATEGORY);
        return $result;
    }
    private function get_multiple_mapped_categories() : array
    {
        $result = [];
        $parent_separator = \apply_filters(self::FILTER_CATEGORY_SEPARATOR, ',');
        $import_to_mapped_only = \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES_MULTI_MAP_IMPORT);
        $mapped_category_field = $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES_MULTI_FIELD);
        $auto_create_categories = \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES_MULTI_MAP_IMPORT_AUTO_CREATE);
        if (!empty($mapped_category_field)) {
            $categories_to_map = \explode($parent_separator, $mapped_category_field);
            foreach ($categories_to_map as $category_to_map) {
                $mapped_category = \wc_clean(\trim($category_to_map));
                if (!empty($mapped_category)) {
                    $mapped_categories = $this->get_mapped_categories($mapped_category);
                    $result = \array_merge($result, $mapped_categories);
                    if (!$import_to_mapped_only && $auto_create_categories && empty($mapped_categories)) {
                        try {
                            $term = $this->find_category_by_name($mapped_category);
                            $result = \array_merge($result, [$term->term_id]);
                        } catch (\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Exception\WPTermNotFoundException $e) {
                            $term = $this->create_category_by_name($mapped_category);
                            $result = \array_merge($result, [$term->term_id]);
                        }
                    }
                }
            }
        }
        if ($import_to_mapped_only && empty($result)) {
            throw new \RuntimeException(\__('Category for product is not mapped and product will be skipped.', 'dropshipping-xml-for-woocommerce'));
        }
        return $result;
    }
    private function find_category_by_name(string $name, int $parent = 0) : \WP_Term
    {
        $args = ['hide_empty' => \false, 'name' => $name, 'taxonomy' => self::CATEGORY_TAXONOMY_NAME, 'parent' => $parent];
        $terms = \get_terms($args);
        if (!\is_array($terms) || \is_array($terms) && empty($terms)) {
            $args = ['hide_empty' => \false, 'meta_query' => [['key' => self::CATEGORY_META_FULL_NAME, 'value' => $name, 'compare' => '=']], 'taxonomy' => self::CATEGORY_TAXONOMY_NAME, 'parent' => $parent];
            $terms = \get_terms($args);
        }
        if (\is_array($terms) && !empty($terms)) {
            $term = \reset($terms);
            if (\is_object($term) && $term instanceof \WP_Term) {
                return $term;
            }
        }
        throw new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Exception\WPTermNotFoundException('Category ' . $name . ' not found');
    }
    private function create_category_by_name(string $name, int $parent = 0) : \WP_Term
    {
        $term_id = null;
        $term_data = \wp_insert_term($name, self::CATEGORY_TAXONOMY_NAME, ['parent' => $parent]);
        if (\is_wp_error($term_data)) {
            $error_data = $term_data->error_data;
            if (\is_array($error_data) && isset($error_data['term_exists'])) {
                $term_id = (int) $error_data['term_exists'];
            }
        } elseif (\is_array($term_data) && isset($term_data['term_id'])) {
            $term_id = (int) $term_data['term_id'];
        }
        if (\is_int($term_id)) {
            $term = \get_term($term_id, self::CATEGORY_TAXONOMY_NAME);
            if (\is_object($term) && $term instanceof \WP_Term) {
                \update_term_meta($term_id, self::CATEGORY_META_FULL_NAME, $name);
                return $term;
            }
        }
        throw new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Exception\WPTermNotCreatedException('Category ' . $name . ' can\'t be created');
    }
}
