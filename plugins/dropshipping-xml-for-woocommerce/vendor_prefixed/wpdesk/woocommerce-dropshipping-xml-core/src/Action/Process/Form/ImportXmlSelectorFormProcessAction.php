<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\Form;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportXmlSelectorDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportXmlSelectorForm;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportMapperViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportXmlSelectorFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO;
/**
 * Class ImportXmlSelectorFormProcessAction, import xml selector view form process.
 * @package WPDesk\Library\DropshippingXmlCore\Action\Process\Form
 */
class ImportXmlSelectorFormProcessAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ImportXmlSelectorForm
     */
    private $form;
    /**
     * @var PluginHelper
     */
    private $plugin_helper;
    /**
     * @var DataProviderFactory
     */
    private $data_provider_factory;
    /**
     * @var ImportDAO
     */
    private $import_dao;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory $data_provider_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportXmlSelectorForm $form, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper $helper, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO $import_dao)
    {
        $this->request = $request;
        $this->form = $form;
        $this->plugin_helper = $helper;
        $this->data_provider_factory = $data_provider_factory;
        $this->import_dao = $import_dao;
    }
    public function isActive() : bool
    {
        $settings = $this->request->get_param('post.' . $this->form->get_form_id());
        return $settings->isArray() && !$settings->isEmpty();
    }
    public function init()
    {
        $this->save_form_data();
    }
    private function save_form_data()
    {
        $uid = $this->request->get_param('get.uid')->getAsString();
        $this->form->handle_request($this->request->get_param('post.' . $this->form->get_form_id())->get());
        if ($this->form->is_valid() && \current_user_can('manage_options')) {
            $data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportXmlSelectorDataProvider::class, ['postfix' => $uid]);
            $data_provider->update($this->form);
            $mode = $this->request->get_param('get.mode')->getAsString();
            if (!empty($mode)) {
                $args = ['uid' => $uid, 'mode' => $mode];
                if ($this->import_dao->is_uid_exists($uid)) {
                    $import = $this->import_dao->find_by_uid($uid);
                    $element = $data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportXmlSelectorFormFields::NODE_ELEMENT);
                    if ($element != $import->get_node_element()) {
                        $import->set_node_element($element);
                        $this->import_dao->update($import);
                    }
                }
            } else {
                $args = ['uid' => $uid];
            }
            $url = $this->plugin_helper->generate_url_by_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportMapperViewAction::class, $args);
            $this->redirect($url);
        }
    }
    private function redirect(string $url)
    {
        \wp_redirect($url);
        exit;
    }
}
