<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Viewer;

use DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportXmlSelectorDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportXmlSelectorFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Viewer\SidebarViewerService;
/**
 * Class XmlSidebarViewerService
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Viewer
 */
class XmlSidebarViewerService implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable
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
     * @var XmlAnalyser
     */
    private $xml_analyser;
    /**
     * @var FileLocatorService
     */
    private $file_locator;
    /**
     * @var SidebarViewerService
     */
    private $sidebar_viewer_service;
    /**
     * @var string
     */
    private $uid = '';
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer $renderer, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory $data_provider_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser $xml_analyser, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService $file_locator, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Viewer\SidebarViewerService $sidebar_viewer_service, string $uid)
    {
        $this->renderer = $renderer;
        $this->data_provider_factory = $data_provider_factory;
        $this->file_locator = $file_locator;
        $this->xml_analyser = $xml_analyser;
        $this->uid = $uid;
        $this->sidebar_viewer_service = $sidebar_viewer_service;
    }
    public function show()
    {
        $this->sidebar_viewer_service->show();
        $this->show_xml();
    }
    private function show_xml()
    {
        $process_data = $this->process_xml_file($this->uid);
        $data = ['title' => \__('Xml data view', 'dropshipping-xml-for-woocommerce'), 'renderer' => $this->renderer];
        $data = \array_merge($data, $process_data);
        $this->renderer->output_render('Import/Sidebar_XML', $data);
    }
    private function process_xml_file(string $uid) : array
    {
        $data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportXmlSelectorDataProvider::class, ['postfix' => $uid]);
        $element = $data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportXmlSelectorFormFields::NODE_ELEMENT);
        $converted = $this->file_locator->get_converted_file($uid);
        $this->xml_analyser->load_from_file($converted);
        return ['items' => $this->xml_analyser->count_element($element), 'item_nr' => self::FIRST_PAGE, 'element' => $element, 'content' => $this->xml_analyser->get_element_as_xml($element, self::FIRST_PAGE, \true), 'format' => \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat::CSV];
    }
}
