<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Viewer;

use DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportCsvSelectorFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable;
/**
 * Class CsvSidebarViewerService
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Viewer
 */
class CsvSidebarViewerService implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable
{
    const FIRST_PAGE = 1;
    /**
     * @var Renderer
     */
    private $renderer;
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
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer $renderer, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser $xml_analyser, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService $file_locator, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Viewer\SidebarViewerService $sidebar_viewer_service, string $uid)
    {
        $this->renderer = $renderer;
        $this->xml_analyser = $xml_analyser;
        $this->file_locator = $file_locator;
        $this->uid = $uid;
        $this->sidebar_viewer_service = $sidebar_viewer_service;
    }
    public function show()
    {
        $this->sidebar_viewer_service->show();
        $this->show_csv();
    }
    private function show_csv()
    {
        $process_data = $this->process_csv($this->uid);
        $data = ['title' => \__('Csv table view', 'dropshipping-xml-for-woocommerce'), 'renderer' => $this->renderer];
        $data = \array_merge($data, $process_data);
        $this->renderer->output_render('Import/Sidebar_CSV', $data);
    }
    private function process_csv(string $uid) : array
    {
        $converted_file = $this->file_locator->get_converted_file($uid);
        $this->xml_analyser->load_from_file($converted_file);
        return ['items' => $this->xml_analyser->count_element(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportCsvSelectorFormFields::NODE_ELEMENT_VALUE), 'item_nr' => self::FIRST_PAGE, 'table_data' => ['node' => $this->xml_analyser->get_element_as_object(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportCsvSelectorFormFields::NODE_ELEMENT_VALUE, self::FIRST_PAGE)], 'format' => \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat::CSV];
    }
}
