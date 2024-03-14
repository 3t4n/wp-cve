<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields;

use DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\HiddenField;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\NoOnceField;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\RadioField;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\SubmitField;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\TextAreaField;
use DropshippingXmlFreeVendor\WPDesk\Forms\FieldProvider;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\WyswigField;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\AttributesComponent;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\MappedCategoriesComponent;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\MappedTaxClassComponent;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent;
/**
 * Class ImportMapperFormFields, import mapper form fields.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Form\Fields
 */
class ImportMapperFormFields implements \DropshippingXmlFreeVendor\WPDesk\Forms\FieldProvider
{
    const DEFAULT_DIMENSION_SIZE = 6;
    const NONE_SHIPPING_CLASS_VALUE = 'none';
    const TITLE = 'title';
    const EXCERPT = 'excerpt';
    const EXCERPT_ID = 'excerpt';
    const EXCERPT_EDITOR_CLASS = 'dropshipping-plugin-editor-excerpt';
    const EXCERPT_ROWS = 10;
    const CONTENT_EDITOR_CLASS = 'dropshipping-plugin-editor';
    const CONTENT_ROWS = 10;
    const CONTENT = 'content';
    const CONTENT_ID = 'content';
    const PRODUCT_TYPE = 'product_type';
    const PRODUCT_TYPE_ID = 'product-type';
    const PRODUCT_TYPE_OPTION_SIMPLE = 'simple';
    const PRODUCT_TYPE_OPTION_EXTERNAL = 'external';
    const PRODUCT_TYPE_OPTION_VARIABLE = 'variable';
    const PRODUCT_VIRTUAL = 'virtual';
    const PRODUCT_VIRTUAL_ID = '_virtual';
    const PRODUCT_EXTERNAL_URL = 'external_url';
    const PRODUCT_EXTERNAL_BUTTON_TEXT = 'button_text';
    const PRODUCT_PRICE = 'price';
    const PRODUCT_PRICE_MODIFICATOR = 'price_mod';
    const PRODUCT_PRICE_MODIFICATOR_OPTION_FIXED = 'fixed';
    const PRODUCT_PRICE_MODIFICATOR_OPTION_PERCENT = 'percent';
    const PRODUCT_PRICE_MODIFICATOR_VALUE = 'price_mod_value';
    const PRODUCT_PRICE_MODIFICATOR_CONDITIONS = 'price_mod_conditions';
    const PRODUCT_SALE_PRICE = 'sale_price';
    const PRODUCT_TAX_STATUS = 'tax_status';
    const PRODUCT_TAX_STATUS_OPTION_TAXABLE = 'taxable';
    const PRODUCT_TAX_STATUS_OPTION_SHIPPING = 'shipping';
    const PRODUCT_TAX_STATUS_OPTION_NONE = 'none';
    const PRODUCT_TAX_CLASS = 'tax_class';
    const PRODUCT_TAX_CLASS_TYPE = 'tax_class_type';
    const PRODUCT_TAX_CLASS_VALUE_SINGLE = 'single';
    const PRODUCT_TAX_CLASS_VALUE_MAPPED = 'mapped';
    const PRODUCT_TAX_CLASS_ID_SINGLE = 'product_tax_class_single';
    const PRODUCT_TAX_CLASS_ID_MAPPED = 'product_tax_class_mapped';
    const PRODUCT_TAX_CLASS_MAPPER_FIELD = 'tax_class_mapper_field';
    const PRODUCT_TAX_CLASS_MULTI_MAP = 'tax_class_map';
    const PRODUCT_TAX_CLASS_MULTI_MAP_ID = 'tax_class_map_id';
    const PRODUCT_TAX_CLASS_MULTI_MAP_VALUE = 'tax_class_map_value';
    const PRODUCT_SKU = 'SKU';
    const PRODUCT_MANAGE_STOCK = 'manage_stock';
    const PRODUCT_MANAGE_STOCK_ID = '_manage_stock';
    const PRODUCT_STOCK = 'stock';
    const PRODUCT_STOCK_ID = '_stock';
    const PRODUCT_BACKORDERS = 'backorders';
    const PRODUCT_BACKORDERS_ID = '_backorders';
    const PRODUCT_LOW_STOCK = 'low_stock_amount';
    const PRODUCT_LOW_STOCK_ID = '_low_stock_amount';
    const PRODUCT_STOCK_STATUS = 'stock_status';
    const PRODUCT_STOCK_STATUS_ID = '_stock_status';
    const PRODUCT_SOLD_INDIVIDUALLY = 'sold_individually';
    const PRODUCT_SOLD_INDIVIDUALLY_ID = '_sold_individually';
    const PRODUCT_WEIGHT = 'weight';
    const PRODUCT_WEIGHT_ID = '_weight';
    const PRODUCT_LENGTH = 'product_length';
    const PRODUCT_WIDTH = 'product_width';
    const PRODUCT_HEIGHT = 'product_height';
    const PRODUCT_SHIPPING_CLASS_SYNC_DISABLED = 'product_shipping_class_sync';
    const PRODUCT_SHIPPING_CLASS = 'product_shipping_class';
    const PRODUCT_ATTRIBUTE_SYNC_DISABLED = 'attribute_sync_disabled';
    const PRODUCT_ATTRIBUTE_AS_TAXONOMY = 'attribute_as_taxonomy';
    const PRODUCT_ATTRIBUTE = 'attribute';
    const PRODUCT_ATTRIBUTE_NAME = 'attribute_name';
    const PRODUCT_ATTRIBUTE_VALUE = 'attribute_value';
    const PRODUCT_IMAGES = 'images';
    const PRODUCT_IMAGES_ID = 'product-images';
    const PRODUCT_IMAGES_SEPARATOR = 'images_separator';
    const PRODUCT_IMAGES_SCAN = 'images_scan';
    const PRODUCT_IMAGES_FEATURED_NOT_IN_GALLERY = 'images_featured_not_in_gallery';
    const PRODUCT_IMAGES_APPEND_TO_EXISTING = 'images_append_to_existing';
    const PRODUCT_CATEGORIES_SYNC_DISABLED = 'product_categories_sync_disabled';
    const PRODUCT_CATEGORIES = 'product_categories';
    const PRODUCT_CATEGORIES_SINGLE_ID = 'product_categories_single_id';
    const PRODUCT_CATEGORIES_MULTI_ID = 'product_categories_multi_map_id';
    const PRODUCT_CATEGORIES_TREE_ID = 'product_categories_tree_id';
    const PRODUCT_CATEGORIES_SINGLE_VALUE = 'single';
    const PRODUCT_CATEGORIES_MULTI_VALUE = 'map';
    const PRODUCT_CATEGORIES_SINGLE_CATEGORY = 'category_single_id';
    const PRODUCT_CATEGORIES_MULTI_FIELD = 'category_field';
    const PRODUCT_CATEGORIES_MULTI_MAP_IMPORT = 'category_map_import';
    const PRODUCT_CATEGORIES_MULTI_MAP_IMPORT_ID = 'category_map_import_id';
    const PRODUCT_CATEGORIES_MULTI_MAP_IMPORT_AUTO_CREATE = 'category_map_import_auto_create';
    const PRODUCT_CATEGORIES_MULTI_MAP = 'category_map';
    const PRODUCT_CATEGORIES_MULTI_MAP_CATEGORY = 'category_map_id';
    const PRODUCT_CATEGORIES_MULTI_MAP_VALUE = 'category_map_value';
    const PRODUCT_CATEGORIES_TREE_VALUE = 'tree';
    const PRODUCT_CATEGORIES_TREE_FIELD_VALUE = 'category_tree_field';
    const PRODUCT_CATEGORIES_TREE_SEPARATOR_VALUE = 'category_tree_separator';
    const PRODUCT_CATEGORIES_TREE_ADD_ALL_VALUE = 'category_tree_add_all';
    const NODE_ELEMENT = 'node_element';
    const NODE_ELEMENT_ID = 'dropshipping-node-element';
    const NONCE_ACTION = 'product_mapper_action';
    const NONCE_NAME = 'product_mapper_nonce';
    const SUBMIT_NEXT_STEP = 'next_step';
    const VARIATION_TYPE = 'variation_type';
    const VARIATION_TYPE_SKU_VALUE = 'sku';
    const VARIATION_TYPE_TITLE_VALUE = 'title';
    const VARIATION_TYPE_CUSTOM_VALUE = 'custom';
    const VARIATION_TYPE_GROUP_VALUE = 'group';
    const VARIATION_TYPE_EMBEDDED_VALUE = 'embedded';
    const VARIATION_TYPE_SKU_ID = 'variation_type_sku';
    const VARIATION_TYPE_TITLE_ID = 'variation_type_title';
    const VARIATION_TYPE_CUSTOM_ID = 'variation_type_custom';
    const VARIATION_TYPE_GROUP_ID = 'variation_type_group';
    const VARIATION_TYPE_EMBEDDED_ID = 'variation_type_embedded';
    const VARIATION_TYPE_SKU_PARENT_XPATH = 'variation_sku_parent_xpath';
    const VARIATION_TYPE_TITLE_PARENT_EXISTS = 'variation_title_parent_exists';
    const VARIATION_JOIN_CUSTOM_XPATH = 'variation_custom_xpath';
    const VARIATION_JOIN_CUSTOM_PARENT_XPATH = 'variation_custom_parent_xpath';
    const VARIATION_TYPE_GROUP_XPATH = 'variation_group_xpath';
    const VARIATION_TYPE_GROUP_PARENT_EXISTS = 'variation_group_parent_exists';
    const VARIATION_EMBEDDED = 'variation_embedded';
    /**
     * @see FieldProvider::get_fields()
     */
    public function get_fields()
    {
        $beacon = $this->get_beacon_translations();
        return [(new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\RadioField())->set_name(self::VARIATION_TYPE)->set_default_value(self::VARIATION_TYPE_TITLE_VALUE)->set_attribute('type', 'radio')->set_attribute('id', self::VARIATION_TYPE_TITLE_ID)->add_class('mapper-type-selector')->set_label(\__('Variable products have the same name.', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField())->set_name(self::VARIATION_TYPE_TITLE_PARENT_EXISTS)->add_class('mapper-has-parent-selector')->set_label(\__('Check if variable products have a parent product in the file structure.', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\RadioField())->set_name(self::VARIATION_TYPE)->set_default_value(self::VARIATION_TYPE_SKU_VALUE)->set_attribute('type', 'radio')->set_attribute('id', self::VARIATION_TYPE_SKU_ID)->add_class('mapper-type-selector')->set_label(\__('Variable products uses SKU number of the main product.', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::VARIATION_TYPE_SKU_PARENT_XPATH)->add_class('width-100')->set_placeholder(\__('XPath to the parent SKU', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\RadioField())->set_name(self::VARIATION_TYPE)->set_default_value(self::VARIATION_TYPE_CUSTOM_VALUE)->set_attribute('type', 'radio')->set_attribute('id', self::VARIATION_TYPE_CUSTOM_ID)->add_class('mapper-type-selector')->set_label(\__('Variable products and the main product have different identifiers.', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::VARIATION_JOIN_CUSTOM_XPATH)->add_class('width-100')->set_placeholder(\__('XPath to the variation identifier', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::VARIATION_JOIN_CUSTOM_PARENT_XPATH)->add_class('width-100')->set_placeholder(\__('XPath to the parent product identifier', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\RadioField())->set_name(self::VARIATION_TYPE)->set_default_value(self::VARIATION_TYPE_GROUP_VALUE)->set_attribute('type', 'radio')->set_attribute('id', self::VARIATION_TYPE_GROUP_ID)->add_class('mapper-type-selector')->set_label(\__('Variable products have the same identifier.', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::VARIATION_TYPE_GROUP_XPATH)->add_class('width-100')->set_placeholder(\__('XPath to the grouped field', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField())->set_name(self::VARIATION_TYPE_GROUP_PARENT_EXISTS)->add_class('mapper-has-parent-selector')->set_label(\__('Check if variable products have a parent product in the file structure.', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\RadioField())->set_name(self::VARIATION_TYPE)->set_default_value(self::VARIATION_TYPE_EMBEDDED_VALUE)->set_attribute('type', 'radio')->set_attribute('id', self::VARIATION_TYPE_EMBEDDED_ID)->add_class('mapper-type-selector')->set_label(\__('Variable products are embedded as child tags in XML.', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField())->set_name(self::PRODUCT_ATTRIBUTE_AS_TAXONOMY)->set_label(\__('Add attributes as taxonomy', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\AttributesComponent())->set_label(\__('Attributes', 'dropshipping-xml-for-woocommerce'))->set_name(self::PRODUCT_ATTRIBUTE)->set_items([(new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::PRODUCT_ATTRIBUTE_NAME)->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['attributes'])->set_placeholder(\__('Name', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::PRODUCT_ATTRIBUTE_VALUE)->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['attributes'])->set_placeholder(\__('Value', 'dropshipping-xml-for-woocommerce'))]), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\WyswigField())->set_label(\__('Description', 'dropshipping-xml-for-woocommerce'))->set_name(self::CONTENT)->set_attribute('wpautop', \true)->set_attribute('media_buttons', \false)->set_attribute('editor_class', self::CONTENT_EDITOR_CLASS)->set_attribute('textarea_rows', self::CONTENT_ROWS)->set_attribute('id', self::CONTENT_ID), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\WyswigField())->set_label(\__('Excerpt', 'dropshipping-xml-for-woocommerce'))->set_name(self::EXCERPT)->set_attribute('wpautop', \true)->set_attribute('media_buttons', \false)->set_attribute('editor_class', self::EXCERPT_EDITOR_CLASS . ' hs-beacon-search')->set_attribute('textarea_rows', self::EXCERPT_ROWS)->set_attribute('data-beacon_search', $beacon['excerpt'])->set_attribute('id', self::EXCERPT_ID), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::TITLE)->set_placeholder(\__('Drag and drop product title here', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::PRODUCT_EXTERNAL_URL)->set_placeholder(\__('https://', 'dropshipping-xml-for-woocommerce'))->set_description(\__('Enter the external URL to the product.', 'dropshipping-xml-for-woocommerce'))->set_label(\__('Product URL', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::PRODUCT_EXTERNAL_BUTTON_TEXT)->set_placeholder(\__('Buy product', 'dropshipping-xml-for-woocommerce'))->set_description(\__('This text will be shown on the button linking to the external product.', 'dropshipping-xml-for-woocommerce'))->set_label(\__('Button text', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::PRODUCT_PRICE)->set_placeholder(\sprintf(\__('Price (%s)', 'dropshipping-xml-for-woocommerce'), \get_woocommerce_currency_symbol()))->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['general'])->set_label(\sprintf(\__('Price (%s)', 'dropshipping-xml-for-woocommerce'), \get_woocommerce_currency_symbol())), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::PRODUCT_SALE_PRICE)->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['general'])->set_label(\sprintf(\__('Sale price (%s)', 'dropshipping-xml-for-woocommerce'), \get_woocommerce_currency_symbol())), (new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent())->set_name(self::PRODUCT_PRICE_MODIFICATOR_CONDITIONS), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::PRODUCT_SKU)->set_label(\__('SKU', 'dropshipping-xml-for-woocommerce'))->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['inventory'])->set_attribute('id', self::PRODUCT_SKU), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField())->set_name(self::PRODUCT_MANAGE_STOCK)->set_label(\__('Manage stock?', 'dropshipping-xml-for-woocommerce'))->set_description(\__('Enable stock management at product level', 'dropshipping-xml-for-woocommerce'))->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['inventory'])->set_attribute('id', self::PRODUCT_MANAGE_STOCK_ID), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->add_class('input-text regular-input padding-xs hs-beacon-search')->set_name(self::PRODUCT_STOCK)->set_label(\__('Stock quantity', 'dropshipping-xml-for-woocommerce'))->set_default_value(0)->set_attribute('data-beacon_search', $beacon['inventory'])->set_attribute('id', self::PRODUCT_STOCK_ID), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField())->set_label(\__('Allow backorders?', 'dropshipping-xml-for-woocommerce'))->set_name(self::PRODUCT_BACKORDERS)->add_class('select short hs-beacon-search')->set_attribute('id', self::PRODUCT_BACKORDERS_ID)->set_attribute('data-beacon_search', $beacon['inventory'])->set_options(\wc_get_product_backorder_options()), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->add_class('input-text regular-input padding-xs hs-beacon-search')->set_name(self::PRODUCT_LOW_STOCK)->set_label(\__('low stock threshold', 'dropshipping-xml-for-woocommerce'))->set_default_value(0)->set_attribute('data-beacon_search', $beacon['inventory'])->set_attribute('id', self::PRODUCT_LOW_STOCK_ID), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField())->set_label(\__('Availability', 'dropshipping-xml-for-woocommerce'))->set_name(self::PRODUCT_STOCK_STATUS)->add_class('select short hs-beacon-search')->set_attribute('data-beacon_search', $beacon['inventory'])->set_attribute('id', self::PRODUCT_STOCK_STATUS_ID)->set_options(\wc_get_product_stock_status_options()), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField())->set_name(self::PRODUCT_SOLD_INDIVIDUALLY)->set_label(\__('Sold individually', 'dropshipping-xml-for-woocommerce'))->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['inventory'])->set_description(\__('Enable this to only allow one of this item to be bought in a single order', 'dropshipping-xml-for-woocommerce'))->set_attribute('id', self::PRODUCT_SOLD_INDIVIDUALLY_ID), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::PRODUCT_WEIGHT)->set_label(\sprintf(\__('Weight (%s)', 'dropshipping-xml-for-woocommerce'), \get_option('woocommerce_weight_unit')))->set_placeholder(0)->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['shipping'])->set_attribute('id', self::PRODUCT_WEIGHT_ID), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::PRODUCT_LENGTH)->add_class('input-text wc_input_decimal hs-beacon-search')->set_label(\sprintf(\__('Dimensions (%s)', 'dropshipping-xml-for-woocommerce'), \get_option('woocommerce_dimension_unit')))->set_placeholder(\__('Length', 'dropshipping-xml-for-woocommerce'))->set_attribute('data-beacon_search', $beacon['shipping'])->set_attribute('size', self::DEFAULT_DIMENSION_SIZE), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::PRODUCT_WIDTH)->add_class('input-text wc_input_decimal hs-beacon-search')->set_label(\sprintf(\__('Width (%s)', 'dropshipping-xml-for-woocommerce'), \get_option('woocommerce_dimension_unit')))->set_placeholder(\__('Width', 'dropshipping-xml-for-woocommerce'))->set_attribute('data-beacon_search', $beacon['shipping'])->set_attribute('size', self::DEFAULT_DIMENSION_SIZE), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::PRODUCT_HEIGHT)->add_class('input-text wc_input_decimal last hs-beacon-search')->set_label(\sprintf(\__('Height (%s)', 'dropshipping-xml-for-woocommerce'), \get_option('woocommerce_dimension_unit')))->set_placeholder(\__('Height', 'dropshipping-xml-for-woocommerce'))->set_attribute('data-beacon_search', $beacon['shipping'])->set_attribute('size', self::DEFAULT_DIMENSION_SIZE), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField())->set_label(\__('Shipping class', 'dropshipping-xml-for-woocommerce'))->set_name(self::PRODUCT_SHIPPING_CLASS)->add_class('select short hs-beacon-search')->set_attribute('data-beacon_search', $beacon['shipping'])->set_options(self::get_shipping_classes()), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField())->set_label(\__('Tax status', 'dropshipping-xml-for-woocommerce'))->set_name(self::PRODUCT_TAX_STATUS)->add_class('select short hs-beacon-search')->set_attribute('data-beacon_search', $beacon['general'])->set_options([self::PRODUCT_TAX_STATUS_OPTION_TAXABLE => \__('Taxable', 'dropshipping-xml-for-woocommerce'), self::PRODUCT_TAX_STATUS_OPTION_SHIPPING => \__('Shipping only', 'dropshipping-xml-for-woocommerce'), self::PRODUCT_TAX_STATUS_OPTION_NONE => \__('None', 'dropshipping-xml-for-woocommerce')]), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\RadioField())->set_name(self::PRODUCT_TAX_CLASS_TYPE)->set_attribute('id', self::PRODUCT_TAX_CLASS_ID_SINGLE)->set_attribute('type', 'radio')->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['tax'])->set_default_value(self::PRODUCT_TAX_CLASS_VALUE_SINGLE)->set_label(\__('Set a single tax class to all imported products', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\RadioField())->set_name(self::PRODUCT_TAX_CLASS_TYPE)->set_attribute('id', self::PRODUCT_TAX_CLASS_ID_MAPPED)->set_attribute('type', 'radio')->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['tax'])->set_default_value(self::PRODUCT_TAX_CLASS_VALUE_MAPPED)->set_label(\__('Tax class mapper', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField())->set_label(\__('Tax class', 'dropshipping-xml-for-woocommerce'))->set_name(self::PRODUCT_TAX_CLASS)->add_class('select short hs-beacon-search')->set_attribute('data-beacon_search', $beacon['tax'])->set_options(\wc_get_product_tax_class_options()), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::PRODUCT_TAX_CLASS_MAPPER_FIELD)->set_label(\__('Tax class field', 'dropshipping-xml-for-woocommerce'))->set_attribute('style', 'width:100%!important')->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['tax'])->set_placeholder(\__('Drag & drop columns from right side here', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\MappedTaxClassComponent())->set_label(\__('Tax class mapper fields', 'dropshipping-xml-for-woocommerce'))->set_name(self::PRODUCT_TAX_CLASS_MULTI_MAP)->set_items([(new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField())->set_label(\__('Select tax class', 'dropshipping-xml-for-woocommerce'))->set_name(self::PRODUCT_TAX_CLASS_MULTI_MAP_ID)->add_class('select short hs-beacon-search')->set_attribute('type', 'select')->set_attribute('data-beacon_search', $beacon['tax'])->set_options(\wc_get_product_tax_class_options()), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::PRODUCT_TAX_CLASS_MULTI_MAP_VALUE)->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['tax'])->set_placeholder(\__('External tax class', 'dropshipping-xml-for-woocommerce'))]), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField())->set_name(self::PRODUCT_TYPE)->set_attribute('id', self::PRODUCT_TYPE_ID)->set_options([self::PRODUCT_TYPE_OPTION_SIMPLE => \__('Simple product', 'dropshipping-xml-for-woocommerce'), self::PRODUCT_TYPE_OPTION_VARIABLE => \__('Variable product', 'dropshipping-xml-for-woocommerce'), self::PRODUCT_TYPE_OPTION_EXTERNAL => \__('External/Affiliate product', 'dropshipping-xml-for-woocommerce')]), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField())->set_name(self::PRODUCT_VIRTUAL)->set_label(\__('Sold individually', 'dropshipping-xml-for-woocommerce'))->set_attribute('id', self::PRODUCT_VIRTUAL_ID), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\HiddenField())->set_name(self::NODE_ELEMENT)->set_attribute('id', self::NODE_ELEMENT_ID), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\TextAreaField())->set_attribute('rows', 8)->set_attribute('id', self::PRODUCT_IMAGES_ID)->set_placeholder(\__('Drag and drop tags containing image URLs to this field.', 'dropshipping-xml-for-woocommerce'))->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['images'])->set_name(self::PRODUCT_IMAGES), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::PRODUCT_IMAGES_SEPARATOR)->set_attribute('style', 'width:30px;')->set_label(\__('Images separator', 'dropshipping-xml-for-woocommerce'))->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['images'])->set_default_value(','), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField())->set_name(self::PRODUCT_IMAGES_SCAN)->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['images'])->set_label(\__('Scan &lt;img&gt; tags and import images', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField())->set_name(self::PRODUCT_IMAGES_FEATURED_NOT_IN_GALLERY)->set_label(\__('Do not add Product Image to the Product Gallery', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField())->set_name(self::PRODUCT_IMAGES_APPEND_TO_EXISTING)->set_label(\__('Do not replace the existing images. Instead, append new photos to the product.', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\RadioField())->set_name(self::PRODUCT_CATEGORIES)->set_attribute('id', self::PRODUCT_CATEGORIES_SINGLE_ID)->set_attribute('type', 'radio')->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['categories'])->set_default_value(self::PRODUCT_CATEGORIES_SINGLE_VALUE)->set_label(\__('Set a single category to all imported products', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField())->set_label(\__('Set default category', 'dropshipping-xml-for-woocommerce'))->set_name(self::PRODUCT_CATEGORIES_SINGLE_CATEGORY)->set_attribute('data-beacon_search', $beacon['categories'])->add_class('select short hs-beacon-search')->set_options($this->get_woocommerce_categories()), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\RadioField())->set_name(self::PRODUCT_CATEGORIES)->set_attribute('id', self::PRODUCT_CATEGORIES_MULTI_ID)->set_attribute('type', 'radio')->set_default_value(self::PRODUCT_CATEGORIES_MULTI_VALUE)->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['categories'])->set_label(\__('Map categories', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField())->set_name(self::PRODUCT_CATEGORIES_MULTI_MAP_IMPORT)->add_class('hs-beacon-search')->set_attribute('id', self::PRODUCT_CATEGORIES_MULTI_MAP_IMPORT_ID)->set_label(\__('Import only products from mapped categories', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField())->set_name(self::PRODUCT_CATEGORIES_MULTI_MAP_IMPORT_AUTO_CREATE)->add_class('hs-beacon-search')->set_label(\__('Create or select categories automatically', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::PRODUCT_CATEGORIES_MULTI_FIELD)->set_label(\__('Product category field', 'dropshipping-xml-for-woocommerce'))->set_attribute('style', 'width:100%')->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['categories'])->set_placeholder(\__('Drag & drop columns from right side here', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\MappedCategoriesComponent())->set_label(\__('Categories mapper', 'dropshipping-xml-for-woocommerce'))->set_name(self::PRODUCT_CATEGORIES_MULTI_MAP)->set_items([(new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField())->set_label(\__('Select shop category', 'dropshipping-xml-for-woocommerce'))->set_name(self::PRODUCT_CATEGORIES_MULTI_MAP_CATEGORY)->add_class('select short hs-beacon-search')->set_attribute('type', 'select')->set_attribute('data-beacon_search', $beacon['categories'])->set_options($this->get_woocommerce_categories()), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::PRODUCT_CATEGORIES_MULTI_MAP_VALUE)->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['categories'])->set_placeholder(\__('External ID', 'dropshipping-xml-for-woocommerce'))]), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\RadioField())->set_name(self::PRODUCT_CATEGORIES)->set_attribute('id', self::PRODUCT_CATEGORIES_TREE_ID)->set_attribute('type', 'radio')->set_default_value(self::PRODUCT_CATEGORIES_TREE_VALUE)->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['categories'])->set_label(\__('Create category trees', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::PRODUCT_CATEGORIES_TREE_FIELD_VALUE)->set_label(\__('Product category field', 'dropshipping-xml-for-woocommerce'))->set_attribute('style', 'width:100%')->add_class('hs-beacon-search')->set_attribute('data-beacon_search', $beacon['categories'])->set_placeholder(\__('Drag & drop columns from right side here', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField())->set_name(self::PRODUCT_CATEGORIES_TREE_ADD_ALL_VALUE)->set_label(\__('Add product to all subcategories?', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\InputTextField())->set_name(self::PRODUCT_CATEGORIES_TREE_SEPARATOR_VALUE)->set_label(\__('Category tree separator', 'dropshipping-xml-for-woocommerce'))->set_attribute('maxlength', '1')->set_attribute('style', 'width:60px')->set_default_value('>'), (new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent())->set_name(self::VARIATION_EMBEDDED), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SubmitField())->set_name(self::SUBMIT_NEXT_STEP)->set_label(\__('Go to the next step &rarr;', 'dropshipping-xml-for-woocommerce'))->add_class('button button-primary button-hero')->set_attribute('id', self::SUBMIT_NEXT_STEP), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\NoOnceField(self::NONCE_ACTION))->set_name(self::NONCE_NAME)];
    }
    public static function get_shipping_classes() : array
    {
        $result = [self::NONE_SHIPPING_CLASS_VALUE => \__('No shipping class', 'dropshipping-xml-for-woocommerce')];
        $shipping_classes = \get_terms(['taxonomy' => 'product_shipping_class', 'hide_empty' => \false]);
        foreach ($shipping_classes as $term) {
            $result[$term->term_id] = $term->name;
        }
        return $result;
    }
    private function get_woocommerce_categories() : array
    {
        $result = [];
        $args = ['taxonomy' => 'product_cat', 'orderby' => 'name', 'show_count' => \false, 'pad_counts' => \false, 'hierarchical' => \true, 'title_li' => '', 'hide_empty' => \false];
        $all_categories = \get_categories($args);
        if (\is_array($all_categories)) {
            $tree = $this->build_tree($all_categories);
            \ksort($tree);
            $result = $this->create_dropdown_array($tree);
        }
        return $result;
    }
    private function create_dropdown_array(array $branch, int $depth = 0) : array
    {
        $result = [];
        foreach ($branch as $leaf) {
            $result[$leaf['id']] = \str_repeat('-', $depth) . $leaf['name'];
            if (isset($leaf['children'])) {
                $result = $result + $this->create_dropdown_array($leaf['children'], $depth + 1);
            }
        }
        return $result;
    }
    private function build_tree(array &$all_categories, int $parentId = 0) : array
    {
        $branch = [];
        foreach ($all_categories as $key => $category) {
            if ($category->parent === $parentId) {
                $children = $this->build_tree($all_categories, $category->term_id);
                $branch[$category->term_id]['id'] = $category->term_id;
                $branch[$category->term_id]['name'] = $category->name;
                if (!empty($children)) {
                    $branch[$category->term_id]['children'] = $children;
                }
                unset($all_categories[$key]);
            }
        }
        return $branch;
    }
    private function get_beacon_translations() : array
    {
        return ['inventory' => \__('Inventory', 'dropshipping-xml-for-woocommerce'), 'general' => \__('General product parameters', 'dropshipping-xml-for-woocommerce'), 'shipping' => \__('Shipping', 'dropshipping-xml-for-woocommerce'), 'attributes' => \__('Attributes', 'dropshipping-xml-for-woocommerce'), 'excerpt' => \__('Short product description', 'dropshipping-xml-for-woocommerce'), 'images' => \__('Images mapping', 'dropshipping-xml-for-woocommerce'), 'categories' => \__('Categories mapping', 'dropshipping-xml-for-woocommerce'), 'tax' => \__('Tax class mapping', 'dropshipping-xml-for-woocommerce')];
    }
}
