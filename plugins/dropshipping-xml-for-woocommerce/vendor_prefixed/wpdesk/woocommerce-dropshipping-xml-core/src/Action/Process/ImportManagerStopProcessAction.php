<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportCsvSelectorFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO;
use Exception;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportManagerViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import;
/**
 * Class ImportManagerDeleteProcessAction, import delete process over import manager.
 * @package WPDesk\Library\DropshippingXmlCore\Action\Process
 */
class ImportManagerStopProcessAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable
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
     * @var PluginHelper
     */
    private $plugin_helper;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO $import_dao, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper $plugin_helper)
    {
        $this->request = $request;
        $this->import_dao = $import_dao;
        $this->plugin_helper = $plugin_helper;
    }
    public function isActive() : bool
    {
        return $this->request->get_param('get.stop')->isNumeric() && $this->plugin_helper->is_plugin_page($this->request->get_param('get.page')->getAsString());
    }
    public function init()
    {
        $this->validate_form_data();
        $this->stop_import();
    }
    private function stop_import()
    {
        $id = (int) $this->request->get_param('get.stop')->get();
        $import = $this->import_dao->find_by_id($id);
        $import->set_status(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_STOPPED);
        $this->import_dao->update($import);
        $url = $this->plugin_helper->generate_url_by_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportManagerViewAction::class);
        \wp_redirect($url);
        exit;
    }
    private function validate_form_data()
    {
        if (!\wp_verify_nonce($this->request->get_param('get.nonce')->getAsString(), \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportManagerViewAction::MANAGER_NONCE)) {
            \wp_die('Error, security code is not valid');
        }
        if (!\current_user_can('manage_options')) {
            \wp_die('Error, you are not allowed to do this action');
        }
    }
}
