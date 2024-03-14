<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportCsvSelectorDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportFileDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportCsvSelectorFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportFileFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Connector\FileConnectorService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Converter\FileConverterService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Logger\ImportLoggerService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Scheduler\ImportSchedulerService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Importer\ProductImporterService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Cleaner\ProductCleanerService;
use InvalidArgumentException;
use Exception;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\CsvAnalyser;
/**
 * Class ImportProcessAction, class that handles import process over ajax and cron.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Action\Process
 */
class ImportProcessAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable
{
    const AJAX_ACTION = 'import_products';
    const AJAX_NONCE = 'nonce_import_products';
    const PROCESS_IMPORT_KEY = 'dropshipping_import_process';
    /**
     * @var ImportDAO
     */
    private $import_dao;
    /**
     * @var ProductImporterService
     */
    private $import_service;
    /**
     * @var ProductCleanerService
     */
    private $cleaner_service;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ImportSchedulerService
     */
    private $scheduler;
    /**
     * @var ImportLoggerService
     */
    private $logger;
    /**
     * @var FileLocatorService
     */
    private $file_locator;
    /**
     * @var XmlAnalyser
     */
    private $xml_analyser;
    /**
     * @var CSVAnalyser
     */
    private $csv_analyser;
    /**
     * @var FileConnectorService
     */
    private $file_connector;
    /**
     * @var DataProviderFactory
     */
    private $data_provider_factory;
    /**
     * @var FileConverterService
     */
    private $converter_service;
    /**
     * @var bool
     */
    private $finished = \false;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO $import_dao, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory $data_provider_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Importer\ProductImporterService $import_service, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Cleaner\ProductCleanerService $cleaner_service, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Scheduler\ImportSchedulerService $scheduler, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService $file_locator, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Connector\FileConnectorService $file_connector, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Converter\FileConverterService $converter_service, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Logger\ImportLoggerService $logger, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser $xml_analyser, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\CsvAnalyser $csv_analyser)
    {
        $this->import_dao = $import_dao;
        $this->import_service = $import_service;
        $this->cleaner_service = $cleaner_service;
        $this->request = $request;
        $this->scheduler = $scheduler;
        $this->logger = $logger;
        $this->file_locator = $file_locator;
        $this->xml_analyser = $xml_analyser;
        $this->csv_analyser = $csv_analyser;
        $this->file_connector = $file_connector;
        $this->converter_service = $converter_service;
        $this->data_provider_factory = $data_provider_factory;
    }
    public function hooks()
    {
        \add_action('wp_ajax_' . self::AJAX_ACTION, [$this, 'process_ajax']);
    }
    public function process()
    {
        $file_import = null;
        try {
            if ($this->import_dao->has_import_processing()) {
                $file_import = $this->import_dao->find_processing_import();
                $this->process_import($file_import);
            } elseif ($this->import_dao->has_next_to_import()) {
                $file_import = $this->import_dao->find_next_to_import();
                $this->process_next_import($file_import);
            }
        } catch (\Exception $e) {
            if (\is_object($file_import)) {
                $this->stop_import_with_error($file_import, $e->getMessage());
            }
        }
    }
    public function process_ajax()
    {
        try {
            $uid = $this->request->get_param('post.uid')->get();
            if (empty($uid)) {
                throw new \InvalidArgumentException(\__('Error: uid is empty', 'dropshipping-xml-for-woocommerce'));
            }
            $file_import = $this->import_dao->find_by_uid($uid);
            if (\in_array($file_import->get_status(), [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_ERROR, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_STOPPED])) {
                $this->reset_import_to_default($file_import);
                $this->import_dao->update($file_import);
            }
            \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_WAITING === $file_import->get_status() ? $this->process_next_import($file_import) : $this->process_import($file_import);
            \wp_send_json(['success' => \true, 'created' => $file_import->get_created(), 'updated' => $file_import->get_updated(), 'skipped' => $file_import->get_skipped(), 'total' => $file_import->get_products_count(), 'progress' => $file_import->get_formated_progress(), 'log' => $this->logger->get_formated_messages(), 'finished' => $this->finished]);
        } catch (\Exception $e) {
            if (\is_object($file_import)) {
                $this->stop_import_with_error($file_import, $e->getMessage());
            }
            \wp_send_json(['success' => \false, 'message' => $e->getMessage()]);
        }
    }
    private function process_import(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import)
    {
        if (\true === $this->lock_import()) {
            switch ($file_import->get_status()) {
                case \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_DOWNLOADING:
                    $this->process_downloading($file_import);
                    break;
                case \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_CONVERTING:
                    $this->process_converting($file_import);
                    break;
                case \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_IN_PROGRESS:
                    $this->process_import_progress($file_import);
                    break;
                case \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_CLEANING:
                    $this->process_cleaning($file_import);
                    break;
            }
            $this->lock_import(\false);
        } else {
            $this->logger->notice(\__('Waiting, another process is importing now.', 'dropshipping-xml-for-woocommerce'));
            \sleep(3);
        }
    }
    private function process_downloading(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import)
    {
        $this->logger->notice(\__('Downloading', 'dropshipping-xml-for-woocommerce'));
        $data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportFileDataProvider::class, ['postfix' => $file_import->get_uid()]);
        $parameters = $data_provider->get_all();
        $tmp_file_path = $this->file_locator->generate_tmp_file_path($file_import->get_uid());
        $this->file_connector->get_file($tmp_file_path, $parameters);
        $this->logger->notice(\__('Downloading is finished', 'dropshipping-xml-for-woocommerce'));
        $file_import->set_status(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_CONVERTING);
        $this->import_dao->update($file_import);
    }
    private function process_converting(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import)
    {
        $this->logger->notice(\__('Converting', 'dropshipping-xml-for-woocommerce'));
        $import_data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportFileDataProvider::class, ['postfix' => $file_import->get_uid()]);
        $parameters = [];
        $data_format = $import_data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportFileFormFields::ORIGINAL_FILE_FORMAT);
        $source_file = $this->file_locator->get_source_file($file_import->get_uid());
        if (\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat::CSV == $data_format) {
            $csv_data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportCsvSelectorDataProvider::class, ['postfix' => $file_import->get_uid()]);
            $parameters = ['separator' => $csv_data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportCsvSelectorFormFields::INPUT_SEPARATOR), 'source_encoding' => $this->csv_analyser->resolve_source_encoding($source_file)];
        }
        $this->converter_service->convert_from_format($data_format);
        $converted_file = $this->converter_service->convert($source_file, $parameters);
        $this->xml_analyser->load_from_file($converted_file);
        $this->logger->notice(\__('Converting is finished', 'dropshipping-xml-for-woocommerce'));
        $this->xml_analyser->load_from_file($this->file_locator->get_converted_file($file_import->get_uid()));
        $file_import->set_products_count($this->xml_analyser->count_element($file_import->get_node_element()));
        $file_import->set_status(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_IN_PROGRESS);
        $this->import_dao->update($file_import);
    }
    private function process_import_progress(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import)
    {
        if (0 === $file_import->get_last_position()) {
            $this->logger->notice(\__('Import in progress', 'dropshipping-xml-for-woocommerce'));
        }
        if (empty(\get_current_user_id())) {
            $user_id = $file_import->get_user_id();
            if (empty(\get_user_by('id', 1))) {
                $user_id = 1;
            }
            \wp_set_current_user($user_id);
        }
        $file_import = $this->import_service->import($file_import);
        $file_save_import = clone $file_import;
        if ($this->import_service->is_finished()) {
            $this->logger->notice(\__('Import is finished', 'dropshipping-xml-for-woocommerce'));
            $this->logger->notice(\__('Cleaning', 'dropshipping-xml-for-woocommerce'));
            $file_save_import->set_status(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_CLEANING);
        }
        $this->import_dao->update($file_save_import);
    }
    private function process_cleaning(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import)
    {
        $file_import = $this->cleaner_service->clean($file_import);
        $file_save_import = clone $file_import;
        if ($this->cleaner_service->is_finished()) {
            $this->finish_process($file_save_import);
        }
        $this->import_dao->update($file_save_import);
    }
    private function finish_process(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import)
    {
        $this->logger->notice(\__('Finish', 'dropshipping-xml-for-woocommerce'));
        $this->reset_import_to_default($file_import);
        $this->finished = \true;
    }
    private function reset_import_to_default(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import
    {
        $file_import->set_next_import($this->scheduler->estimate_time($file_import->get_uid()));
        $file_import->set_last_position(0);
        $file_import->set_updated(0);
        $file_import->set_skipped(0);
        $file_import->set_created(0);
        $file_import->set_products_count(0);
        $file_import->set_status(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_WAITING);
        return $file_import;
    }
    private function process_next_import(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import)
    {
        if (\true === $this->lock_import()) {
            $this->logger->notice(\__('Starting new import for uid: ' . $file_import->get_uid(), 'dropshipping-xml-for-woocommerce'));
            $file_import->set_status(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_DOWNLOADING);
            $file_import->set_start_date(\time());
            $this->import_dao->update($file_import);
            $this->lock_import(\false);
        } else {
            $this->logger->notice(\__('Waiting, another process is importing now.', 'dropshipping-xml-for-woocommerce'));
            \sleep(3);
        }
    }
    private function stop_import_with_error(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $file_import, string $error_message = '')
    {
        $file_import->set_status(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_ERROR);
        $file_import->set_error_message($error_message);
        $this->import_dao->update($file_import);
    }
    private function lock_import(bool $lock = \true) : bool
    {
        global $wpdb;
        $uid = \uniqid();
        $time = \time() + \MINUTE_IN_SECONDS * 2;
        $set_lock = function (string $key, $value) use($wpdb) : bool {
            $key = \sanitize_title($key);
            $value = \serialize($value);
            $row_id = $wpdb->get_var($wpdb->prepare("SELECT option_id FROM {$wpdb->options} WHERE option_name = %s LIMIT 1", $key));
            if (\is_numeric($row_id)) {
                $row = $wpdb->query($wpdb->prepare("UPDATE {$wpdb->options} SET option_value=%s WHERE option_id=%d", $value, $row_id));
            } else {
                $row = $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->options} (option_name, option_value, autoload) VALUES (%s, %s, 'no')", $key, $value));
            }
            return \boolval($row);
        };
        $get_lock = function (string $key) use($wpdb) {
            $row = $wpdb->get_var($wpdb->prepare("SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1", $key));
            return \unserialize($row);
        };
        $has_active_lock = function ($db_data) : bool {
            if (\is_array($db_data)) {
                if (isset($db_data['time'])) {
                    if (\intval($db_data['time']) > \time()) {
                        return \true;
                    }
                }
            }
            return \false;
        };
        $is_uid_valid = function ($db_data, $uid) : bool {
            if (\is_array($db_data)) {
                if (isset($db_data['uid'])) {
                    if ($db_data['uid'] === $uid) {
                        return \true;
                    }
                }
            }
            return \false;
        };
        if (\false === $lock) {
            $set_lock(self::PROCESS_IMPORT_KEY, null);
            return \true;
        }
        if (!$has_active_lock($get_lock(self::PROCESS_IMPORT_KEY))) {
            $set_lock(self::PROCESS_IMPORT_KEY, ['uid' => $uid, 'time' => $time]);
            if ($is_uid_valid($get_lock(self::PROCESS_IMPORT_KEY), $uid)) {
                return \true;
            }
        }
        return \false;
    }
}
