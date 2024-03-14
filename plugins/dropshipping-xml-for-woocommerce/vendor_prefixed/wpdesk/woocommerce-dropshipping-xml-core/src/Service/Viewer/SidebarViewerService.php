<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Viewer;

use DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportSidebarDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportSidebarForm;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable;
/**
 * Class SidebarViewerService
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Viewer
 */
class SidebarViewerService implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable
{
    const FIRST_PAGE = 1;
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var DataProviderFactory
     */
    private $data_provider_factory;
    /**
     * @var ImportSidebarForm
     */
    private $form;
    /**
     * @var string
     */
    private $uid = '';
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer $renderer, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory $data_provider_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportSidebarForm $form, string $uid)
    {
        $this->renderer = $renderer;
        $this->data_provider_factory = $data_provider_factory;
        $this->form = $form;
        $this->uid = $uid;
    }
    public function init()
    {
        $data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportSidebarDataProvider::class, ['postfix' => $this->uid]);
        $this->form->set_data($data_provider);
    }
    public function show()
    {
        // $this->form->handle_request( [ ImportMapperFormFields::NODE_ELEMENT => $node_element ] );
        $data = ['title' => \__('Import Name', 'dropshipping-xml-for-woocommerce'), 'renderer' => $this->renderer, 'form' => new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView($this->form, $this->renderer)];
        $this->renderer->output_render('Import/Sidebar', $data);
    }
}
