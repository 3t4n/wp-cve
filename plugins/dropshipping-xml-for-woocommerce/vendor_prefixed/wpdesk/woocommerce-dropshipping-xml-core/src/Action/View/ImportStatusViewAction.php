<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View;

use DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Registrable\Registrable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\View\ImportStatusView;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Creator\ImportCreatorService;
/**
 * Class ImportStatusViewAction, import status view action.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Action\View
 */
class ImportStatusViewAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Registrable\Registrable
{
    /**
     * @var ImportDAO
     */
    private $import_dao;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var PluginHelper
     */
    private $plugin_helper;
    /**
     * @var Renderer
     */
    private $renderer;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer $renderer, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO $import_dao, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper $plugin_helper)
    {
        $this->import_dao = $import_dao;
        $this->request = $request;
        $this->plugin_helper = $plugin_helper;
        $this->renderer = $renderer;
    }
    public function register() : array
    {
        return [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Creator\ImportCreatorService::class];
    }
    public function show()
    {
        $import = $this->import_dao->find_by_uid($this->request->get_param('get.uid')->get());
        $data = ['title' => \__('Import', 'dropshipping-xml-for-woocommerce'), 'edit' => \false, 'mode' => $this->request->get_param('get.mode')->getAsString(), 'import' => $import, 'import_manager_url' => $this->get_import_manager_url(), 'products_url' => $this->get_products_url(), 'renderer' => $this->renderer];
        $this->renderer->output_render('Import/Import_status', $data);
    }
    private function get_products_url()
    {
        return \add_query_arg(['post_type' => 'product'], \admin_url('edit.php'));
    }
    private function get_import_manager_url()
    {
        return $this->plugin_helper->generate_url_by_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportManagerViewAction::class);
    }
}
