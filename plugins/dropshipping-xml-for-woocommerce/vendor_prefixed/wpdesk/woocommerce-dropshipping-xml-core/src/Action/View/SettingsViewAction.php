<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View;

use DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\SettingsDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\SettingsForm;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Registrable\Registrable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\Form\SettingsFormProcessAction;
/**
 * Class SettingsViewAction, settings view action.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Action\View
 */
class SettingsViewAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Registrable\Registrable
{
    /**
     * @var SettingsForm
     */
    private $form;
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var DataProviderFactory
     */
    private $data_provider_factory;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer $renderer, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory $data_provider_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\SettingsForm $settings_form)
    {
        $this->form = $settings_form;
        $this->renderer = $renderer;
        $this->data_provider_factory = $data_provider_factory;
    }
    public function register() : array
    {
        return [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\Form\SettingsFormProcessAction::class];
    }
    public function init()
    {
        $data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\SettingsDataProvider::class);
        $this->form->set_data($data_provider);
    }
    public function show()
    {
        $data = ['title' => \__('Settings', 'dropshipping-xml-for-woocommerce'), 'form' => new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView($this->form, $this->renderer), 'renderer' => $this->renderer];
        $this->renderer->output_render('Settings/Settings', $data);
    }
}
