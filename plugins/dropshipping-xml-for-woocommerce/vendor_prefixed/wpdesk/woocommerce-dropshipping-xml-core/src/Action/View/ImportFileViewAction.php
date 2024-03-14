<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View;

use DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportFileForm;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Registrable\Registrable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\Form\ImportFileFormProcessAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportFileDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\View\ImportFileView;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
/**
 * Class ImportFileViewAction, import file view action.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Action\View
 */
class ImportFileViewAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Registrable\Registrable, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable
{
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var ImportFileForm
     */
    private $form;
    /**
     * @var DataProviderFactory
     */
    private $data_provider_factory;
    /**
     * @var Request
     */
    private $request;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer $renderer, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportFileForm $form, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory $data_provider_factory)
    {
        $this->request = $request;
        $this->renderer = $renderer;
        $this->form = $form;
        $this->data_provider_factory = $data_provider_factory;
    }
    public function register() : array
    {
        return [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\Form\ImportFileFormProcessAction::class];
    }
    public function init()
    {
        $uid = $this->request->get_param('get.uid')->getAsString();
        if (!empty($uid)) {
            $data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportFileDataProvider::class, ['postfix' => $uid]);
            $this->form->set_data($data_provider);
        }
    }
    public function show()
    {
        $mode = $this->request->get_param('get.mode')->getAsString();
        $data = ['title' => \__('File import', 'dropshipping-xml-for-woocommerce'), 'mode' => $mode, 'edit' => \false, 'has_ftp_addon' => $this->has_ftp_addon(), 'form' => new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView($this->form, $this->renderer), 'renderer' => $this->renderer];
        $this->renderer->output_render('Import/ImportConnector', $data);
    }
    private function has_ftp_addon()
    {
        return \is_plugin_active('advanced-import-for-dropshipping/advanced-import-for-dropshipping.php');
    }
}
