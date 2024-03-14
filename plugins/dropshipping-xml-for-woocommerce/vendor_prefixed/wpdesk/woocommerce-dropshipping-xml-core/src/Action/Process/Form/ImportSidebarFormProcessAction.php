<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\Form;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportManagerForm;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportSidebarDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportSidebarForm;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportSidebarFormFields;
/**
 * Class ImportSidebarFormProcessAction, sidebar process.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Action\Process\Form
 */
class ImportSidebarFormProcessAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ImportManagerForm
     */
    private $form;
    /**
     * @var DataProviderFactory
     */
    private $data_provider_factory;
    /**
     * @var ImportDAO
     */
    private $import_dao;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportSidebarForm $form, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory $data_provider_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO $import_dao)
    {
        $this->request = $request;
        $this->form = $form;
        $this->data_provider_factory = $data_provider_factory;
        $this->import_dao = $import_dao;
    }
    public function isActive() : bool
    {
        $settings = $this->request->get_param('post.' . \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportSidebarForm::ID);
        return $settings->isArray() && !$settings->isEmpty();
    }
    public function init()
    {
        $this->process_form_data();
    }
    private function process_form_data()
    {
        $file_form_id = \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportSidebarForm::get_id();
        $uid = $this->request->get_param('get.uid');
        $this->form->handle_request($this->request->get_param('post.' . $file_form_id)->get());
        if ($this->form->is_valid() && \current_user_can('manage_options')) {
            $data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportSidebarDataProvider::class, ['postfix' => $uid]);
            $data_provider->update($this->form);
        }
        if ($this->import_dao->is_uid_exists($uid)) {
            $import = $this->import_dao->find_by_uid($uid);
            $import_name = $data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportSidebarFormFields::IMPORT_NAME);
            $import->set_import_name($import_name);
            $this->import_dao->update($import);
        }
    }
}
