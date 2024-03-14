<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View;

use DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportFileDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportMapperDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ViewActionFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportFileFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportXmlSelectorFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportMapperForm;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Registrable\Registrable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\Form\ImportMapperFormProcessAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportXmlSelectorDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportCsvSelectorFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent;
/**
 * Class ImportMapperViewAction, import mapper view action.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Action\View
 */
class ImportMapperViewAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Registrable\Registrable, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var ImportMapperForm
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
     * @var ViewActionFactory
     */
    private $view_factory;
    /**
     * @var DataProviderFactory
     */
    private $data_provider_factory;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config $config, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer $renderer, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportMapperForm $form, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ViewActionFactory $view_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory $data_provider_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper $helper)
    {
        $this->config = $config;
        $this->renderer = $renderer;
        $this->plugin_helper = $helper;
        $this->request = $request;
        $this->form = $form;
        $this->view_factory = $view_factory;
        $this->data_provider_factory = $data_provider_factory;
    }
    public function register() : array
    {
        return [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\Form\ImportMapperFormProcessAction::class];
    }
    public function init()
    {
        $uid = $this->request->get_param('get.uid')->getAsString();
        $data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportMapperDataProvider::class, ['postfix' => $uid]);
        if ($this->should_convert_price_conditions($data_provider)) {
            $all_data = $data_provider->get_all();
            $all_data = \is_array($all_data) ? $all_data : [];
            $all_data[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE_MODIFICATOR_CONDITIONS] = $this->get_converted_old_price_modificator($data_provider);
            $this->form->set_data($all_data);
        } else {
            $this->form->set_data($data_provider);
        }
    }
    private function should_convert_price_conditions(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportMapperDataProvider $data_provider) : bool
    {
        if ($data_provider->has(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE_MODIFICATOR_CONDITIONS)) {
            return \false;
        }
        $price_mod = $data_provider->has(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE_MODIFICATOR);
        $price_mod_value = $data_provider->has(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE_MODIFICATOR_VALUE);
        if ($price_mod && $price_mod_value) {
            $price_mod = !empty($data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE_MODIFICATOR));
            $price_mod_value = !empty($data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE_MODIFICATOR_VALUE));
        }
        return $price_mod && $price_mod_value;
    }
    private function get_converted_old_price_modificator(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportMapperDataProvider $data_provider) : array
    {
        $result = [];
        $result[1] = [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent::FIELD_PRICE_MOD => $data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE_MODIFICATOR), \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent::FIELD_PRICE_MOD_VALUE => $data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::PRODUCT_PRICE_MODIFICATOR_VALUE)];
        return $result;
    }
    public function show()
    {
        $uid = $this->request->get_param('get.uid')->getAsString();
        $import_data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportFileDataProvider::class, ['postfix' => $uid]);
        $format = $import_data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportFileFormFields::ORIGINAL_FILE_FORMAT);
        $sidebar_view = $this->view_factory->create_sidebar_by_data_type($format, ['uid' => $uid]);
        if (\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat::XML == $format) {
            $page_id = \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportXmlSelectorViewAction::class;
            $xml_data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportXmlSelectorDataProvider::class, ['postfix' => $uid]);
            $node_element = $xml_data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportXmlSelectorFormFields::NODE_ELEMENT);
        } else {
            $page_id = \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportCsvSelectorViewAction::class;
            $node_element = \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportCsvSelectorFormFields::NODE_ELEMENT_VALUE;
        }
        $this->form->handle_request([\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::NODE_ELEMENT => $node_element]);
        $data = ['title' => \__('Product mapper', 'dropshipping-xml-for-woocommerce'), 'edit' => $this->request->get_param('get.mode')->get() === 'edit', 'mode' => $this->request->get_param('get.mode')->getAsString(), 'form' => new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView($this->form, $this->renderer), 'format' => $format, 'sidebar' => $sidebar_view, 'items' => 1, 'previous_step' => $this->plugin_helper->generate_url_by_view($page_id, ['uid' => $this->request->get_param('get.uid')->get()]), 'mapper_img_assets' => $this->config->get_param('assets.img.core_dir_url')->get() . 'mapper/', 'renderer' => $this->renderer];
        $this->renderer->output_render('Import/Product_mapper', $data);
    }
}
