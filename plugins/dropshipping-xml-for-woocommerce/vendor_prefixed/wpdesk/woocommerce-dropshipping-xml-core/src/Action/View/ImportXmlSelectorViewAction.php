<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View;

use DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportXmlSelectorDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportXmlSelectorForm;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Registrable\Registrable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\Form\ImportXmlSelectorFormProcessAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Converter\FileConverterService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportXmlSelectorFormFields;
/**
 * Class ImportXmlSelectorViewAction, xml selector view action.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Action\View
 */
class ImportXmlSelectorViewAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Registrable\Registrable
{
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var ImportXmlSelectorForm
     */
    private $form;
    /**
     * @var PluginHelper
     */
    private $plugin_helper;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var FileConverterService
     */
    private $converter_service;
    /**
     * @var DataProviderFactory
     */
    private $data_provider_factory;
    /**
     * @var XmlAnalyser
     */
    private $analyser;
    /**
     * @var FileLocatorService
     */
    private $file_locator;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer $renderer, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportXmlSelectorForm $form, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Converter\FileConverterService $converter, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory $data_provider_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser $xml_analyser, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService $file_locator_service, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper $plugin_helper)
    {
        $this->renderer = $renderer;
        $this->form = $form;
        $this->plugin_helper = $plugin_helper;
        $this->request = $request;
        $this->converter_service = $converter;
        $this->data_provider_factory = $data_provider_factory;
        $this->analyser = $xml_analyser;
        $this->file_locator = $file_locator_service;
    }
    public function register() : array
    {
        return [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\Form\ImportXmlSelectorFormProcessAction::class];
    }
    public function show()
    {
        $uid = $this->request->get_param('get.uid')->getAsString();
        $process_data = $this->process_xml_file($uid);
        $data = ['title' => \__('File preview', 'dropshipping-xml-for-woocommerce'), 'edit' => \false, 'mode' => $this->request->get_param('get.mode')->getAsString(), 'form' => new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView($this->form, $this->renderer), 'previous_step' => $this->plugin_helper->generate_url_by_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportFileViewAction::class, ['uid' => $uid]), 'renderer' => $this->renderer];
        $data = \array_merge($data, $process_data);
        $this->renderer->output_render('Import/ImportSelector', $data);
    }
    private function process_xml_file(string $uid) : array
    {
        $is_url_changed = $this->request->get_param('get.changed')->getAsString() === 'yes';
        $data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportXmlSelectorDataProvider::class, ['postfix' => $uid]);
        $this->form->set_data($data_provider);
        $this->converter_service->convert_from_format(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat::XML);
        if (!$this->file_locator->is_converted_file_exists($uid) || $is_url_changed) {
            $converted_file = $this->converter_service->convert($this->file_locator->get_source_file($uid));
            $this->analyser->load_from_file($converted_file);
            $element = $this->analyser->find_item_element();
        } else {
            $converted_file = $this->file_locator->get_converted_file($uid);
            $this->analyser->load_from_file($converted_file);
            $element = $data_provider->has(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportXmlSelectorFormFields::NODE_ELEMENT) ? $data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportXmlSelectorFormFields::NODE_ELEMENT) : $this->analyser->find_item_element();
        }
        $this->form->handle_request([\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportXmlSelectorFormFields::NODE_ELEMENT => $element]);
        return ['items' => $this->analyser->count_element($element), 'all_elements' => $this->get_nodes_without_root($this->analyser->get_all_elements()), 'item_element' => $element, 'rendered_xml' => $this->analyser->get_element_as_xml($element, 1, \true), 'format' => \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat::XML];
    }
    /**
     * Get all nodes from xml file.
     *
     * @return array
     */
    private function get_nodes_without_root(array $all_elements) : array
    {
        return \array_filter($all_elements, function ($v, $k) {
            return isset($v['depth']) && $v['depth'] > 0;
        }, \ARRAY_FILTER_USE_BOTH);
    }
}
