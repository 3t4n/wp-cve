<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Creator;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportMapperDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportFileDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ImportFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportFileFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Scheduler\ImportSchedulerService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportSidebarDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportSidebarFormFields;
/**
 * Class ImportCreatorService, create import entity.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Creator
 */
class ImportCreatorService implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ImportDAO
     */
    private $import_dao;
    /**
     * @var ImportFactory
     */
    private $file_import_factory;
    /**
     * @var DataProviderFactory
     */
    private $data_provider_factory;
    /**
     * @var FileLocatorService
     */
    private $file_locator;
    /**
     * @var XmlAnalyser
     */
    private $analyser;
    /**
     * @var ImportSchedulerService
     */
    private $scheduler;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory $data_provider_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO $import_dao, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ImportFactory $file_import_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService $file_locator, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser $analyser, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Scheduler\ImportSchedulerService $scheduler)
    {
        $this->request = $request;
        $this->import_dao = $import_dao;
        $this->file_import_factory = $file_import_factory;
        $this->data_provider_factory = $data_provider_factory;
        $this->file_locator = $file_locator;
        $this->analyser = $analyser;
        $this->scheduler = $scheduler;
    }
    public function init()
    {
        $uid = $this->request->get_param('get.uid')->get();
        $this->create_import($uid);
    }
    public function create_import($uid, string $status = \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_IN_PROGRESS)
    {
        if (!empty($uid)) {
            if (!$this->import_dao->is_uid_exists($uid)) {
                $sidebar_data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportSidebarDataProvider::class, ['postfix' => $uid]);
                $connector_data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportFileDataProvider::class, ['postfix' => $uid]);
                $mapper_data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportMapperDataProvider::class, ['postfix' => $uid]);
                $node_element = $mapper_data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::NODE_ELEMENT);
                $count = 0;
                if ($this->file_locator->is_converted_file_exists($uid)) {
                    $this->analyser->load_from_file($this->file_locator->get_converted_file($uid));
                    $count = $this->analyser->count_element($node_element);
                }
                $entity = $this->file_import_factory->create([\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO::UID => $uid, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO::META_KEY_STATUS => $status, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO::META_KEY_URL => $connector_data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportFileFormFields::FILE_URL), \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO::META_KEY_LAST_POSITION => 0, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO::META_KEY_PRODUCTS_COUNT => $count, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO::META_KEY_END_DATE => 0, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO::META_KEY_START_DATE => \time(), \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO::META_KEY_NEXT_IMPORT => 0, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO::META_KEY_NODE_ELEMENT => $node_element, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO::META_KEY_CRON_SCHEDULE => $this->scheduler->get_formated_schedule($uid), \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO::META_KEY_IMPORT_NAME => $sidebar_data_provider->has(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportSidebarFormFields::IMPORT_NAME) ? $sidebar_data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportSidebarFormFields::IMPORT_NAME) : '']);
                $this->import_dao->add($entity);
            }
        }
    }
}
