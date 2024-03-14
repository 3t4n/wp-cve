<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Importer;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\SettingsDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\SettingsFormFields;
use Exception;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Creator\ProductCreatorService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Logger\ImportLoggerService;
/**
 * Class ProductImporterService, manages the product creation process.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Importer
 */
class ProductImporterService
{
    const MAX_PROCESS_TIME_DELAY = 5;
    const MAX_PROCESS_TIME = 30;
    const SINGLE_PRODUCT_COUNT = 1;
    /**
     * @var ProductCreatorService
     */
    private $product_creator;
    /**
     * @var ImportLoggerService
     */
    private $logger;
    /**
     * @var DataProviderFactory
     */
    private $data_provider_factory;
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
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory $data_provider_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Creator\ProductCreatorService $product_creator, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Logger\ImportLoggerService $logger)
    {
        $this->product_creator = $product_creator;
        $this->logger = $logger;
        $this->data_provider_factory = $data_provider_factory;
        $this->start_time = \time();
        $this->max_execution_time = $this->get_max_execution_time();
    }
    public function import(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import
    {
        for ($i = 1; $i <= $this->get_products_in_batch(); $i++) {
            if ($this->is_import_should_finish()) {
                return $file_import;
            }
            if ($file_import->get_last_position() >= $file_import->get_products_count()) {
                $file_import->set_end_date(\time());
                $this->is_finished = \true;
                return $file_import;
            }
            $this->import_product($file_import);
        }
        return $file_import;
    }
    public function is_finished() : bool
    {
        return $this->is_finished;
    }
    private function is_import_should_finish() : bool
    {
        return $this->max_execution_time <= \time() - $this->start_time;
    }
    private function get_products_in_batch() : int
    {
        $settings_data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\SettingsDataProvider::class);
        return $settings_data_provider->has(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\SettingsFormFields::INPUT_TEXT_BATCH) ? $settings_data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\SettingsFormFields::INPUT_TEXT_BATCH) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\SettingsFormFields::DEFAULT_IN_BATCH;
    }
    private function import_product(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import)
    {
        $file_import->add_to_last_position(self::SINGLE_PRODUCT_COUNT);
        try {
            \wc_transaction_query('start');
            $this->product_creator->create_product_from_import($file_import);
            $this->product_creator->is_created() ? $file_import->add_to_created(self::SINGLE_PRODUCT_COUNT) : $file_import->add_to_updated(self::SINGLE_PRODUCT_COUNT);
            \wc_transaction_query('commit');
        } catch (\Exception $e) {
            $file_import->add_to_skipped(self::SINGLE_PRODUCT_COUNT);
            $this->logger->notice($e->getMessage());
            \wc_transaction_query('rollback');
        }
    }
    private function get_max_execution_time()
    {
        $max_execution_time = \intval(\ini_get('max_execution_time'));
        $time = self::MAX_PROCESS_TIME - self::MAX_PROCESS_TIME_DELAY;
        if ($max_execution_time < self::MAX_PROCESS_TIME && $max_execution_time > 0) {
            $time = $max_execution_time - self::MAX_PROCESS_TIME_DELAY;
        }
        return $time;
    }
}
