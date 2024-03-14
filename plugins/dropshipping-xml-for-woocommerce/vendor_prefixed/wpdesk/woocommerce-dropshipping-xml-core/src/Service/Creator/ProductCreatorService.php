<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Creator;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportMapperDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportOptionsDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductDAO;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Logger\ImportLoggerService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\MapperServiceFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductAttributeMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductCategoryMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductImageMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductMapperService;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField;
use WC_Product_Factory;
use WC_Product_Variable;
use WC_Product_Variation;
use WC_Product_Simple;
use WC_Product_External;
use WC_Product;
use Exception;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Exception\ConditionalLogicException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ConditionalLogicServiceFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\ConditionalLogic\ConditionalLogicService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ProductVariationFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductEmbeddedMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductEmbeddedImageMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductEmbeddedAttributeMapperService;
/**
 * Class ProductCreatorService, creates and updates woocommerce product.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Creator
 */
class ProductCreatorService
{
    const TITLE_TYPES = [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::UNIQUE_PRODUCT_SELECTOR_NAME, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_TITLE_VALUE];
    const SKU_TYPES = [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::UNIQUE_PRODUCT_SELECTOR_SKU, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_SKU_VALUE];
    const CUSTOM_ID_TYPES = [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_CUSTOM_VALUE];
    const GROUPED_TYPES = [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_GROUP_VALUE];
    const FILTER_EMBEDDED_VARIATIONS_TO_ADD = 'wpdesk_dropshipping_mapper_embedded_variations_to_add';
    /**
     * @var MapperServiceFactory
     */
    private $mapper_factory;
    /**
     * @var DataProviderFactory
     */
    private $data_provider_factory;
    /**
     * @var XmlAnalyser
     */
    private $analyser;
    /**
     * @var ProductDAO
     */
    private $product_dao;
    /**
     * @var ImportLoggerService
     */
    private $logger;
    /**
     * @var FileLocatorService
     */
    private $file_locator;
    /**
     * @var bool
     */
    private $is_created = \false;
    /**
     *
     * @var string
     */
    private $last_loaded_file_uid;
    /**
     * @var ProductVariationFactory
     */
    private $variation_factory;
    /**
     *
     * @var ConditionalLogicServiceFactory
     */
    private $conditional_logic_service_factory;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory $data_provider_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\MapperServiceFactory $product_mapper_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser $analyser, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductDAO $product_dao, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Logger\ImportLoggerService $logger, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService $file_locator, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ConditionalLogicServiceFactory $conditional_logic_service_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ProductVariationFactory $variation_factory)
    {
        $this->data_provider_factory = $data_provider_factory;
        $this->mapper_factory = $product_mapper_factory;
        $this->analyser = $analyser;
        $this->product_dao = $product_dao;
        $this->logger = $logger;
        $this->file_locator = $file_locator;
        $this->conditional_logic_service_factory = $conditional_logic_service_factory;
        $this->variation_factory = $variation_factory;
    }
    public function create_product_from_import(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import) : \WC_Product
    {
        $mapper = $this->create_mapper($this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportMapperDataProvider::class, ['postfix' => $file_import->get_uid()]), $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportOptionsDataProvider::class, ['postfix' => $file_import->get_uid()]), $this->create_product_analyser_from_import($file_import));
        if (\DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $mapper->get_raw_option_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::FIELD_TURN_ON_LOGICAL_CONDITION)) {
            $conditional_service = $this->conditional_logic_service_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\ConditionalLogic\ConditionalLogicService::class, ['mapper' => $mapper]);
            if (!$conditional_service->is_valid()) {
                throw new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Exception\ConditionalLogicException(\__('The product does not meet the conditional logic requiments.', 'dropshipping-xml-for-woocommerce'));
            }
        }
        $is_variable = \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_TYPE_OPTION_VARIABLE === $mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_TYPE);
        if ($is_variable) {
            $wc_product = $this->create_product_variable_from_import($file_import, $mapper);
        } else {
            $wc_product = $this->create_product_simple_from_import($file_import, $mapper);
        }
        $this->update_import_information($wc_product, $file_import);
        $this->product_dao->save($wc_product);
        return $wc_product;
    }
    public function is_created() : bool
    {
        return $this->is_created;
    }
    private function is_variation(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface $mapper) : bool
    {
        $analysers = \array_values($mapper->get_analysers());
        if (\count($analysers) > 1) {
            if (isset($analysers[0]) && isset($analysers[1])) {
                return $analysers[0]->get_as_xml() != $analysers[1]->get_as_xml();
            }
        }
        return \false;
    }
    private function create_product_variable_from_import(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface $mapper)
    {
        $variable_mapper = $this->create_variable_mapper($mapper, $file_import);
        $is_variation = $this->is_variation($variable_mapper);
        if (\false === $is_variation) {
            $variable_mapper->get_analysers();
        }
        try {
            $this->is_created = \false;
            $this->logger->notice(\__('Searching product in the database.', 'dropshipping-xml-for-woocommerce'));
            $variable_product = $this->find_product_variable($variable_mapper, $file_import);
            if ('variation' !== $variable_product->get_type()) {
                $variable_product = $this->convert_product_type($variable_product, 'variable');
            }
            $this->logger->notice(\__('Product found, and it will be updated', 'dropshipping-xml-for-woocommerce'));
        } catch (\Exception $e) {
            $this->logger->notice(\__('Product not found.', 'dropshipping-xml-for-woocommerce'));
            if (\DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $mapper->get_raw_option_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::FIELD_UPDATE_ONLY_EXISTING_PRODUCTS)) {
                throw new \Exception(\__('Product can\'t be created and will be skipped.', 'dropshipping-xml-for-woocommerce'));
            }
            $this->is_created = \true;
            $variable_product = new \WC_Product_Variable();
            $this->logger->notice('Creating new product.');
            $this->logger->notice('New product created.');
        }
        if (\false === $is_variation || \true === $this->is_created) {
            if (!$this->is_variation_has_parent($variable_mapper)) {
                $variable_mapper->set_mapped_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SKU, '');
                $variable_mapper->set_mapped_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE, '');
                $variable_mapper->set_mapped_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SALE_PRICE, '');
                $variable_mapper->set_mapped_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_MANAGE_STOCK, \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_FALSE);
            }
            $variable_product = $this->map_product($variable_product, $variable_mapper);
        }
        if ($this->has_embedded_variations($mapper)) {
            $variable_product = $this->map_product_embedded_variation($variable_product, $mapper, $file_import);
        } elseif ($is_variation || !$this->is_variation_has_parent($mapper)) {
            $variable_product = $this->map_product_variation($variable_product, $mapper, $file_import);
        }
        $variable_product = $this->update_product_variable_information($variable_product, $file_import, $mapper, $variable_mapper);
        return $variable_product;
    }
    private function has_embedded_variations(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface $mapper) : bool
    {
        return \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_EMBEDDED_VALUE === $mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE);
    }
    private function create_product_simple_from_import(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface $mapper) : \WC_Product
    {
        $is_external = \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_TYPE_OPTION_EXTERNAL === $mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_TYPE);
        try {
            $this->is_created = \false;
            $this->logger->notice(\__('Searching product in the database.', 'dropshipping-xml-for-woocommerce'));
            $wc_product = $this->find_product($mapper, $file_import);
            if ('variation' !== $wc_product->get_type()) {
                $product_type = \true === $is_external ? 'external' : 'simple';
                $wc_product = $this->convert_product_type($wc_product, $product_type);
            }
            $this->logger->notice(\__('Product found, and it will be updated', 'dropshipping-xml-for-woocommerce'));
        } catch (\Exception $e) {
            $this->logger->notice(\__('Product not found.', 'dropshipping-xml-for-woocommerce'));
            if (\DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $mapper->get_raw_option_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::FIELD_UPDATE_ONLY_EXISTING_PRODUCTS)) {
                throw new \Exception(\__('Product can\'t be created and will be skipped.', 'dropshipping-xml-for-woocommerce'));
            }
            $this->is_created = \true;
            $wc_product = \true === $is_external ? new \WC_Product_External() : new \WC_Product_Simple();
            $this->logger->notice(\__('Creating new product.', 'dropshipping-xml-for-woocommerce'));
            $this->logger->notice(\__('New product created.', 'dropshipping-xml-for-woocommerce'));
        }
        $wc_product = $this->map_product($wc_product, $mapper);
        return $wc_product;
    }
    private function update_import_information(\WC_Product $wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import) : \WC_Product
    {
        $wc_product->update_meta_data(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductDAO::PRODUCT_IMPORT_ID_META, $file_import->get_uid());
        $wc_product->update_meta_data(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductDAO::PRODUCT_IMPORT_STARTED_AT_META, $file_import->get_start_date());
        return $wc_product;
    }
    private function update_product_variable_information(\WC_Product $variable_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface $mapper, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface $variable_mapper) : \WC_Product
    {
        $selector = $mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE);
        switch ($selector) {
            case \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_CUSTOM_VALUE:
                $custom_id_value = $variable_mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_JOIN_CUSTOM_XPATH);
                if (!empty($custom_id_value)) {
                    $variable_product->update_meta_data($this->product_dao->generate_custom_id($file_import->get_uid()), $custom_id_value);
                }
                break;
            case \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_GROUP_VALUE:
                $group_value = $variable_mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_GROUP_XPATH);
                if (!empty($group_value)) {
                    $variable_product->update_meta_data($this->product_dao->generate_group_id($file_import->get_uid()), $group_value);
                }
                break;
        }
        $variable_product->update_meta_data(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductDAO::HAS_PARENT_META_KEY, $this->is_variation_has_parent($mapper) ? \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductDAO::HAS_PARENT_VALUE_YES : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductDAO::HAS_PARENT_VALUE_NO);
        $variable_product->update_meta_data(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductDAO::RESYNC_META_KEY, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductDAO::RESYNC_VALUE_YES);
        return $variable_product;
    }
    private function is_variation_has_parent(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface $mapper) : bool
    {
        $selector = $mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE);
        if ($selector === \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_TITLE_VALUE) {
            if (\DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_FALSE === $mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_TITLE_PARENT_EXISTS)) {
                return \false;
            }
        } elseif ($selector === \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_GROUP_VALUE) {
            if (\DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_FALSE === $mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_GROUP_PARENT_EXISTS)) {
                return \false;
            }
        }
        return \true;
    }
    private function convert_product_type(\WC_Product $product, string $product_type) : \WC_Product
    {
        $product_id = $product->get_id();
        $product_type = empty($product_type) ? $product->get_type() : \sanitize_title(\wp_unslash($product_type));
        $classname = \WC_Product_Factory::get_product_classname($product_id, $product_type);
        $product = new $classname($product_id);
        return $product;
    }
    private function find_product_variable(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface $mapper, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import) : \WC_Product
    {
        $selector = $mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE);
        $keyword = '';
        switch ($selector) {
            case \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_SKU_VALUE:
                $keyword = \trim($mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_SKU_PARENT_XPATH));
                if (empty($keyword)) {
                    $keyword = $mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SKU);
                }
                break;
            case \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_CUSTOM_VALUE:
                $keyword = $mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_JOIN_CUSTOM_PARENT_XPATH);
                if (empty($keyword)) {
                    $keyword = $mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_JOIN_CUSTOM_XPATH);
                }
                break;
            case \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_GROUP_VALUE:
                $keyword = $mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_GROUP_XPATH);
                break;
            default:
                $keyword = $mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::TITLE);
        }
        return $this->find_product_in_db($selector, \wc_clean(\trim($keyword)), $file_import->get_uid());
    }
    private function create_variable_mapper(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface $mapper, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface
    {
        $search = '';
        $field_xpath = '';
        $new_mapper = clone $mapper;
        $selector = $new_mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE);
        try {
            switch ($selector) {
                case \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_SKU_VALUE:
                    $field = $new_mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SKU);
                    $search = $new_mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_SKU_PARENT_XPATH);
                    $field_xpath = $new_mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SKU);
                    $parent_field_xpath = $new_mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_SKU_PARENT_XPATH);
                    if ($field == $search || $field_xpath == $parent_field_xpath || empty($field)) {
                        return $new_mapper;
                    }
                    break;
                case \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_CUSTOM_VALUE:
                    $field = $new_mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_JOIN_CUSTOM_XPATH);
                    $search = $new_mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_JOIN_CUSTOM_PARENT_XPATH);
                    $field_xpath = $new_mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_JOIN_CUSTOM_XPATH);
                    $parent_field_xpath = $new_mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_JOIN_CUSTOM_PARENT_XPATH);
                    if ($field == $search || $field_xpath == $parent_field_xpath || empty($field)) {
                        return $new_mapper;
                    }
                    break;
                case \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_GROUP_VALUE:
                    $search = $new_mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_GROUP_XPATH);
                    $field_xpath = $new_mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_TYPE_GROUP_XPATH);
                    break;
                default:
                    $search = $new_mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::TITLE);
                    $field_xpath = $new_mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::TITLE);
            }
            if (!empty($field_xpath) && !empty($search)) {
                $analyser = $this->create_analyser_from_xpath($file_import->get_node_element(), $new_mapper->get_xpath_from_content($field_xpath), $search);
                $old_analyser = null;
                $analysers = $new_mapper->get_analysers();
                if (\is_array($analysers) && !empty($analysers)) {
                    $old_analyser = \end($analysers);
                }
                if (\is_object($old_analyser)) {
                    if ($analyser->get_as_xml() != $old_analyser->get_as_xml()) {
                        $new_mapper->add_analyser($analyser, \true);
                    }
                } else {
                    $new_mapper->add_analyser($analyser, \true);
                }
            }
        } catch (\Exception $e) {
            return $new_mapper;
        }
        return $new_mapper;
    }
    private function create_analyser_from_xpath(string $root_element, string $xpath, string $search) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser
    {
        $xpath = $this->generate_xpath($xpath, $search, $root_element);
        $content = $this->analyser->get_content_by_xpath($xpath);
        return $this->create_product_analyser($content);
    }
    private function generate_xpath(string $node_xpath, string $search, string $root_element)
    {
        $find = $node_xpath;
        $type = 'text()';
        if (\strpos($node_xpath, '/@') !== \false) {
            $find_parts = \explode('/@', $node_xpath, 2);
            $find = $find_parts[0];
            $type = '@' . $find_parts[1];
        }
        return $find . '[contains(' . $type . ",'" . $search . "')]/ancestor::" . $root_element;
    }
    private function find_product(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface $mapper, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import) : \WC_Product
    {
        $options_data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportOptionsDataProvider::class, ['postfix' => $file_import->get_uid()]);
        $selector = $options_data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::UNIQUE_PRODUCT_SELECTOR);
        $keyword = '';
        if ($selector === \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::UNIQUE_PRODUCT_SELECTOR_NAME) {
            $keyword = \wc_clean(\trim($mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::TITLE)));
        } elseif ($selector === \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::UNIQUE_PRODUCT_SELECTOR_SKU) {
            $keyword = \wc_clean(\trim($mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SKU)));
        }
        return $this->find_product_in_db($selector, $keyword);
    }
    private function find_product_in_db(string $type, string $keyword, string $custom_id = '') : \WC_Product
    {
        if (!empty($type) && !empty($keyword)) {
            if (\in_array($type, self::SKU_TYPES)) {
                return $this->product_dao->find_by_sku($keyword);
            } elseif (\in_array($type, self::CUSTOM_ID_TYPES) && !empty($custom_id)) {
                return $this->product_dao->find_by_custom_id($custom_id, $keyword);
            } elseif (\in_array($type, self::GROUPED_TYPES) && !empty($custom_id)) {
                return $this->product_dao->find_by_group_id($custom_id, $keyword);
            } else {
                return $this->product_dao->find_by_name($keyword);
            }
        }
        throw new \Exception('Product not found.');
    }
    private function map_product_variation(\WC_Product_Variable $wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface $mapper, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import) : \WC_Product
    {
        $parameters = ['mapper' => $mapper];
        $product_mapper = $this->mapper_factory->create_product_mapper(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductMapperService::class, $parameters);
        $product_attr_mapper = $this->mapper_factory->create_product_mapper(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductAttributeMapperService::class, $parameters);
        $product_image_mapper = $this->mapper_factory->create_product_mapper(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductImageMapperService::class, $parameters);
        $variation = $product_attr_mapper->create_variation($wc_product);
        $this->is_created = !($variation->get_id() > 0);
        $variation->save();
        $wc_product = \WC_Product_Variable::sync($wc_product);
        $wc_product->save();
        $variation = $product_mapper->update_product($variation);
        $variation = $product_image_mapper->update_product($variation);
        $this->update_import_information($variation, $file_import);
        $variation->save();
        return $wc_product;
    }
    private function map_product_embedded_variation(\WC_Product_Variable $wc_product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface $mapper, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import) : \WC_Product
    {
        $variations_objects = [];
        $xpath = $mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_VARIATION_XPATH, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
        $xpath = \trim(\str_replace(['{', '}'], '', $xpath));
        $analysers = $mapper->get_analysers();
        $first_variation = null;
        if (!empty($analysers) && \is_array($analysers)) {
            $analyser = \end($analysers);
            $variations_objects = $analyser->get_objects_by_xpath($xpath);
        }
        if (empty($variations_objects)) {
            $create_as_simple = \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_CREATE_AS_SIMPLE, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
            if (\true === $create_as_simple) {
                return $this->create_product_simple_from_import($file_import, $mapper);
            }
        } else {
            foreach ($variations_objects as $variation_object) {
                $analyser = $this->create_product_analyser($variation_object->asXML());
                $tmp_mapper = clone $mapper;
                $tmp_mapper->add_analyser($analyser, \true);
                $parameters = ['mapper' => $tmp_mapper, 'variable' => $wc_product];
                $product_mapper = $this->mapper_factory->create_product_mapper(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductEmbeddedMapperService::class, $parameters);
                $product_attr_mapper = $this->mapper_factory->create_product_mapper(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductEmbeddedAttributeMapperService::class, $parameters);
                $product_image_mapper = $this->mapper_factory->create_product_mapper(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductEmbeddedImageMapperService::class, $parameters);
                if (!\apply_filters(self::FILTER_EMBEDDED_VARIATIONS_TO_ADD, \true, $tmp_mapper)) {
                    continue;
                }
                $variation = $product_attr_mapper->create_variation($wc_product);
                if ($first_variation === null) {
                    $first_variation = $variation;
                }
                $variation->save();
                $wc_product = \WC_Product_Variable::sync($wc_product);
                $wc_product->save();
                $variation = $product_mapper->update_product($variation);
                $variation = $product_image_mapper->update_product($variation);
                $this->update_import_information($variation, $file_import);
                $variation->save();
            }
        }
        if (\is_object($first_variation) && \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_CREATE_AS_SIMPLE, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED)) {
            $default_attributes = [];
            foreach ($first_variation->get_variation_attributes() as $key => $value) {
                $taxonomy = \str_replace('attribute_', '', $key);
                $default_attributes[$taxonomy] = $value;
            }
            $wc_product->set_default_attributes($default_attributes);
        }
        return $wc_product;
    }
    private function map_product(\WC_Product $product, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface $mapper) : \WC_Product
    {
        $parameters = ['mapper' => $mapper];
        $product_mapper = $this->mapper_factory->create_product_mapper(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductMapperService::class, $parameters);
        $product_attr_mapper = $this->mapper_factory->create_product_mapper(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductAttributeMapperService::class, $parameters);
        $product_image_mapper = $this->mapper_factory->create_product_mapper(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductImageMapperService::class, $parameters);
        $product_cat_mapper = $this->mapper_factory->create_product_mapper(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductCategoryMapperService::class, $parameters);
        $product_mapper->update_product($product);
        $product_cat_mapper->update_product($product);
        if ($product instanceof \WC_Product_Variable && !$this->has_embedded_variations($mapper)) {
            $product_attr_mapper->update_for_variation(\true);
        }
        $product_attr_mapper->update_product($product);
        $product_image_mapper->update_product($product);
        return $product;
    }
    private function create_mapper(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportMapperDataProvider $mapper_data_provider, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportOptionsDataProvider $options_data_provider, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser $analyser) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Abstraction\ImportMapperServiceInterface
    {
        $mapper = $this->mapper_factory->create_import_mapper(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\ImportMapperService::class, ['mapper_data_provider' => $mapper_data_provider, 'options_data_provider' => $options_data_provider]);
        $mapper->add_analyser($analyser);
        return $mapper;
    }
    private function create_product_analyser_from_import(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser
    {
        if ($this->last_loaded_file_uid !== $file_import->get_uid()) {
            $this->analyser->load_from_file($this->file_locator->get_converted_file($file_import->get_uid()));
            $this->last_loaded_file_uid = $file_import->get_uid();
        }
        $element_xml = $this->analyser->get_element_as_xml($file_import->get_node_element(), $file_import->get_last_position(), \true);
        return $this->create_product_analyser($element_xml);
    }
    private function create_product_analyser(string $xml_content) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser
    {
        $element_analyser = new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser();
        $element_analyser->load_from_content($xml_content);
        return $element_analyser;
    }
}
