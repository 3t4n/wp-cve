<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields;

use DropshippingXmlFreeVendor\WPDesk\Forms\Field\NoOnceField;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\SubmitField;
use DropshippingXmlFreeVendor\WPDesk\Forms\FieldProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportOptionsDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportMapperDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent;
use DropshippingXmlFreeVendor\WPDesk\Forms\Field\HiddenField;
/**
 * Class ImportOptionsFormFields, import options form fields.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Form\Fields
 */
class ImportOptionsFormFields implements \DropshippingXmlFreeVendor\WPDesk\Forms\FieldProvider
{
    const UNIQUE_PRODUCT_ID = 'unique_product_id';
    const UNIQUE_PRODUCT_SELECTOR = 'unique_product_selector';
    const UNIQUE_PRODUCT_SELECTOR_SKU = 'sku';
    const UNIQUE_PRODUCT_SELECTOR_EAN = 'ean';
    const UNIQUE_PRODUCT_SELECTOR_NAME = 'name';
    const UNIQUE_PRODUCT_SELECTOR_CUSTOM_PRODUCT = 'custom_product_id';
    const LOGICAL_CONDITIONS = 'logical_conditions';
    const LOGICAL_CONDITIONS_ID = 'logical_conditions';
    const SYNC_FIELD = 'sync_field';
    const SYNC_FIELD_OPTION_TITLE = 'title';
    const SYNC_FIELD_OPTION_DESCRIPTION = 'description';
    const SYNC_FIELD_OPTION_SHORT_DESCRIPTION = 'short_description';
    const SYNC_FIELD_OPTION_GENERAL_PRICE = 'general_price';
    const SYNC_FIELD_OPTION_GENERAL_SALE_PRICE = 'general_sale_price';
    const SYNC_FIELD_OPTION_GENERAL_TAX_STATUS = 'general_tax_status';
    const SYNC_FIELD_OPTION_GENERAL_TAX_CLASS = 'general_tax_class';
    const SYNC_FIELD_OPTION_STOCK_SKU = 'stock_sku';
    const SYNC_FIELD_OPTION_STOCK_AVAILABILITY = 'stock_availability';
    const SYNC_FIELD_OPTION_STOCK_MANAGMENT = 'stock_managment';
    const SYNC_FIELD_OPTION_STOCK_QUANTITY = 'stock_quantity';
    const SYNC_FIELD_OPTION_STOCK_ALLOW_BACKORDERS = 'stock_allow_backorders';
    const SYNC_FIELD_OPTION_STOCK_LOW_AMOUNT = 'stock_low_amount';
    const SYNC_FIELD_OPTION_STOCK_SOLD_INDIVIDUALLY = 'stock_sold_individually';
    const SYNC_FIELD_OPTION_SHIPPING_WEIGHT = 'shipping_weight';
    const SYNC_FIELD_OPTION_SHIPPING_DIMENSIONS = 'shipping_dimensions';
    const SYNC_FIELD_OPTION_SHIPPING_CLASS = 'shipping_class';
    const SYNC_FIELD_OPTION_ATTRIBUTES = 'attributes';
    const SYNC_FIELD_OPTION_IMAGES = 'images';
    const SYNC_FIELD_OPTION_CATEGORIES = 'categories';
    const CRON_WEEK_DAY = 'cron_week_days';
    const CRON_HOURS = 'cron_hours';
    const NONCE_ACTION = 'product_options_action';
    const NONCE_NAME = 'product_options_nonce';
    const NEXT_STEP = 'next_step';
    const FIELD_REMOVED_PRODUCTS = 'removed_products';
    const OPTION_NO_PRODUCT_DO_NOTHING = 'do_nothing';
    const OPTION_NO_PRODUCT_EMPTY_STOCK = 'empty_stock';
    const OPTION_NO_PRODUCT_TRASH = 'move_trash';
    const FIELD_TURN_ON_LOGICAL_CONDITION = 'turn_logical_condition';
    const FIELD_TURN_ON_LOGICAL_CONDITION_ID = 'turn_logical_condition';
    const FIELD_UPDATE_ONLY_EXISTING_PRODUCTS = 'update_only_existing';
    const FIELD_CREATE_NEW_PRODUCTS_AS_DRAFT = 'create_new_products_as_draft';
    const NODE_ELEMENT = 'node_element';
    const NODE_ELEMENT_ID = 'dropshipping-node-element';
    /**
     *
     * @var ImportOptionsDataProvider
     */
    private $options_data_provider;
    /**
     *
     * @var ImportMapperDataProvider
     */
    private $mapper_data_provider;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory $data_provider_factory, string $uid)
    {
        $this->options_data_provider = $data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportOptionsDataProvider::class, ['postfix' => $uid]);
        $this->mapper_data_provider = $data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportMapperDataProvider::class, ['postfix' => $uid]);
    }
    /**
     * @see FieldProvider::get_fields()
     */
    public function get_fields()
    {
        $beacon = $this->get_beacon_translations();
        $product_fields = self::get_grouped_fields();
        return [(new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField())->set_label(\__('Import into products on the basis of:', 'dropshipping-xml-for-woocommerce'))->set_name(self::UNIQUE_PRODUCT_SELECTOR)->add_class('dropshipping-select2 width-100 hs-beacon-search')->set_attribute('data-beacon_search', $beacon['import'])->set_description(\__('Choose a parameter which will be used for the identification of the products. If the product will not be found in the shop, it will be created. If the product will be found, it will be updated. The plugin will overwrite the data of the products in the shop with the values from the file.', 'dropshipping-xml-for-woocommerce'))->set_options($this->get_identity_map_options()), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField())->set_label(\__('Synchronize product fields:', 'dropshipping-xml-for-woocommerce'))->set_name(self::SYNC_FIELD)->add_class('dropshipping-select2 width-100 hs-beacon-search')->set_attribute('data-beacon_search', $beacon['cron'])->set_description(\__('Select product fields to update.', 'dropshipping-xml-for-woocommerce'))->set_multiple()->set_default_value($this->get_grouped_form_default_values())->set_options($product_fields), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField())->set_name(self::FIELD_TURN_ON_LOGICAL_CONDITION)->add_class('hs-beacon-search')->set_attribute('id', self::FIELD_TURN_ON_LOGICAL_CONDITION_ID)->set_label(\__('Enable conditional logic', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent())->set_label(\__('Logical conditions', 'dropshipping-xml-for-woocommerce'))->set_attribute('id', self::LOGICAL_CONDITIONS_ID)->set_name(self::LOGICAL_CONDITIONS), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField())->set_name(self::FIELD_UPDATE_ONLY_EXISTING_PRODUCTS)->add_class('hs-beacon-search')->set_label(\__('Don\'t create, only update existing products', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField())->set_name(self::FIELD_CREATE_NEW_PRODUCTS_AS_DRAFT)->add_class('hs-beacon-search')->set_label(\__('Create new products as drafts', 'dropshipping-xml-for-woocommerce')), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField())->set_label(\__('Cron schedule:', 'dropshipping-xml-for-woocommerce'))->set_name(self::CRON_WEEK_DAY)->add_class('dropshipping-select2 width-100 hs-beacon-search')->set_attribute('data-beacon_search', $beacon['cron'])->set_description(\__('Select days of the week for the processes to run automatically.', 'dropshipping-xml-for-woocommerce'))->set_multiple()->set_options(self::get_week_days()), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField())->set_label('')->set_name(self::CRON_HOURS)->add_class('dropshipping-select2 width-100 hs-beacon-search')->set_attribute('data-beacon_search', $beacon['cron'])->set_description(\__('Select hours for the processes to run automatically.', 'dropshipping-xml-for-woocommerce'))->set_multiple()->set_options($this->get_hours_data()), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SelectField())->set_label(\__('No product in XML/CSV file', 'dropshipping-xml-for-woocommerce'))->set_name(self::FIELD_REMOVED_PRODUCTS)->add_class('dropshipping-select2 width-100 hs-beacon-search')->set_attribute('data-beacon_search', $beacon['no_products'])->set_description(\__('Choose what should happen to products that have been imported into the shop from an XML file and then removed from the file by file provider.', 'dropshipping-xml-for-woocommerce'))->set_options($this->get_no_products_options()), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\HiddenField())->set_name(self::NODE_ELEMENT)->set_attribute('id', self::NODE_ELEMENT_ID), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\SubmitField())->set_name(self::NEXT_STEP)->set_label(\__('Start import', 'dropshipping-xml-for-woocommerce'))->add_class('button button-primary button-hero')->set_attribute('id', self::NEXT_STEP), (new \DropshippingXmlFreeVendor\WPDesk\Forms\Field\NoOnceField(self::NONCE_ACTION))->set_name(self::NONCE_NAME)];
    }
    private function get_identity_map_options() : array
    {
        return [self::UNIQUE_PRODUCT_SELECTOR_SKU => 'SKU', self::UNIQUE_PRODUCT_SELECTOR_NAME => \__('Product name', 'dropshipping-xml-for-woocommerce')];
    }
    public static function get_week_days() : array
    {
        return ['1' => \__('Monday', 'dropshipping-xml-for-woocommerce'), '2' => \__('Tuesday', 'dropshipping-xml-for-woocommerce'), '3' => \__('Wednesday', 'dropshipping-xml-for-woocommerce'), '4' => \__('Thursday', 'dropshipping-xml-for-woocommerce'), '5' => \__('Friday', 'dropshipping-xml-for-woocommerce'), '6' => \__('Saturday', 'dropshipping-xml-for-woocommerce'), '7' => \__('Sunday', 'dropshipping-xml-for-woocommerce')];
    }
    private function get_hours_data() : array
    {
        $result = [];
        $minutes = ['00', '15', '30', '45'];
        for ($i = 0; $i < 24; $i++) {
            foreach ($minutes as $minute) {
                $time = $i . ':' . $minute;
                $result[$time] = $i . ':' . $minute;
            }
        }
        return $result;
    }
    private function get_beacon_translations() : array
    {
        return ['import' => \__('Import into products on the basis of', 'dropshipping-xml-for-woocommerce'), 'cron' => \__('Cron schedule', 'dropshipping-xml-for-woocommerce'), 'no_products' => \__('No product in XML/CSV file', 'dropshipping-xml-for-woocommerce')];
    }
    private function get_no_products_options() : array
    {
        return [self::OPTION_NO_PRODUCT_DO_NOTHING => \__('Do nothing', 'dropshipping-xml-for-woocommerce'), self::OPTION_NO_PRODUCT_EMPTY_STOCK => \__('Change shop products stock to 0', 'dropshipping-xml-for-woocommerce'), self::OPTION_NO_PRODUCT_TRASH => \__('Move products to the trash', 'dropshipping-xml-for-woocommerce')];
    }
    private function get_grouped_form_default_values() : array
    {
        if ($this->options_data_provider->has(self::SYNC_FIELD)) {
            return [];
        } else {
            $params = self::get_grouped_fields();
            if ($this->mapper_data_provider->has(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SHIPPING_CLASS_SYNC_DISABLED)) {
                if (\DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->mapper_data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_SHIPPING_CLASS_SYNC_DISABLED)) {
                    if (isset($params[self::SYNC_FIELD_OPTION_SHIPPING_CLASS])) {
                        unset($params[self::SYNC_FIELD_OPTION_SHIPPING_CLASS]);
                    }
                }
            }
            if ($this->mapper_data_provider->has(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES_SYNC_DISABLED)) {
                if (\DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->mapper_data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_CATEGORIES_SYNC_DISABLED)) {
                    if (isset($params[self::SYNC_FIELD_OPTION_CATEGORIES])) {
                        unset($params[self::SYNC_FIELD_OPTION_CATEGORIES]);
                    }
                }
            }
            if ($this->mapper_data_provider->has(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_ATTRIBUTE_SYNC_DISABLED)) {
                if (\DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->mapper_data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_ATTRIBUTE_SYNC_DISABLED)) {
                    if (isset($params[self::SYNC_FIELD_OPTION_ATTRIBUTES])) {
                        unset($params[self::SYNC_FIELD_OPTION_ATTRIBUTES]);
                    }
                }
            }
            return \array_keys($params);
        }
    }
    public static function get_grouped_fields() : array
    {
        return [self::SYNC_FIELD_OPTION_TITLE => \__('Product title', 'dropshipping-xml-for-woocommerce'), self::SYNC_FIELD_OPTION_DESCRIPTION => \__('Product description', 'dropshipping-xml-for-woocommerce'), self::SYNC_FIELD_OPTION_SHORT_DESCRIPTION => \__('Product short description', 'dropshipping-xml-for-woocommerce'), self::SYNC_FIELD_OPTION_GENERAL_PRICE => \__('Product price', 'dropshipping-xml-for-woocommerce'), self::SYNC_FIELD_OPTION_GENERAL_SALE_PRICE => \__('Product sale price', 'dropshipping-xml-for-woocommerce'), self::SYNC_FIELD_OPTION_GENERAL_TAX_STATUS => \__('Product tax status', 'dropshipping-xml-for-woocommerce'), self::SYNC_FIELD_OPTION_GENERAL_TAX_CLASS => \__('Product tax class', 'dropshipping-xml-for-woocommerce'), self::SYNC_FIELD_OPTION_STOCK_SKU => \__('Product SKU', 'dropshipping-xml-for-woocommerce'), self::SYNC_FIELD_OPTION_STOCK_MANAGMENT => \__('Product stock', 'dropshipping-xml-for-woocommerce'), self::SYNC_FIELD_OPTION_SHIPPING_WEIGHT => \__('Product shipping weight', 'dropshipping-xml-for-woocommerce'), self::SYNC_FIELD_OPTION_SHIPPING_DIMENSIONS => \__('Product shipping dimensions', 'dropshipping-xml-for-woocommerce'), self::SYNC_FIELD_OPTION_SHIPPING_CLASS => \__('Product shipping class', 'dropshipping-xml-for-woocommerce'), self::SYNC_FIELD_OPTION_ATTRIBUTES => \__('Product attributes', 'dropshipping-xml-for-woocommerce'), self::SYNC_FIELD_OPTION_IMAGES => \__('Product images', 'dropshipping-xml-for-woocommerce'), self::SYNC_FIELD_OPTION_CATEGORIES => \__('Product categories', 'dropshipping-xml-for-woocommerce')];
    }
}
