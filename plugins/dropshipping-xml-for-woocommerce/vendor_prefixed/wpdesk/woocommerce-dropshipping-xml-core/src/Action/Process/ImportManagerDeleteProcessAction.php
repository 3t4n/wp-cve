<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO;
use Exception;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportManagerViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
/**
 * Class ImportManagerDeleteProcessAction, import delete process over import manager.
 */
class ImportManagerDeleteProcessAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable
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
    /**
     *
     * @var FileLocatorService
     */
    private $file_locator_service;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO $import_dao, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper $plugin_helper, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService $file_locator)
    {
        $this->request = $request;
        $this->import_dao = $import_dao;
        $this->plugin_helper = $plugin_helper;
        $this->file_locator_service = $file_locator;
    }
    public function isActive() : bool
    {
        return $this->request->get_param('get.delete')->isNumeric() && $this->plugin_helper->is_plugin_page($this->request->get_param('get.page')->getAsString());
    }
    public function init()
    {
        $this->validate_form_data();
        $this->delete_import();
    }
    private function delete_import()
    {
        $id = (int) $this->request->get_param('get.delete')->get();
        $this->delete_import_by_id($id);
        $url = $this->plugin_helper->generate_url_by_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportManagerViewAction::class);
        \wp_redirect($url);
        exit;
    }
    public function delete_import_by_id(int $id)
    {
        $import = $this->import_dao->find_by_id($id);
        $dir_location = $this->file_locator_service->get_tmp_dir($import->get_uid());
        if (\is_dir($dir_location)) {
            $this->remove_dir_with_files($dir_location);
        }
        $this->import_dao->remove_by_id($id);
    }
    private function remove_dir_with_files(string $dir)
    {
        $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()) {
                \rmdir($file->getRealPath());
            } else {
                \unlink($file->getRealPath());
            }
        }
        \rmdir($dir);
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
