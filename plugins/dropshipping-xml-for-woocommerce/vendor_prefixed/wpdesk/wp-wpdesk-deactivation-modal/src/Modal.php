<?php

namespace DropshippingXmlFreeVendor\WPDesk\DeactivationModal;

use DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Model\FormOptions;
use DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Model\FormTemplate;
use DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Model\FormValues;
use DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Sender\Sender;
use DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Service\AssetsPrinterService;
use DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Service\RequestSenderService;
use DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Service\TemplateGeneratorService;
/**
 * Manages the modal displayed when the plugin is deactivated.
 */
class Modal
{
    const MODAL_TEMPLATE_PATH = __DIR__ . '/../templates/modal.php';
    const MODAL_ASSETS_PATH_CSS = __DIR__ . '/../assets/build/css/styles.css';
    const MODAL_ASSETS_PATH_JS = __DIR__ . '/../assets/build/js/scripts.js';
    /**
     * @var string
     */
    private $plugin_slug;
    /**
     * @var string
     */
    private $form_template;
    /**
     * @var FormOptions
     */
    private $form_options;
    /**
     * @var FormValues
     */
    private $form_values;
    /**
     * @var Sender
     */
    private $request_sender;
    /**
     * @param string       $plugin_slug    Example: "plugin-name".
     * @param FormTemplate $form_template  Information about the the deactivation modal template.
     * @param FormOptions  $form_options   List of plugin deactivation reasons to choose from.
     * @param FormValues   $form_values    Values sent in the request that reports the plugin deactivation.
     * @param Sender       $request_sender Sends a request with a plugin deactivation report.
     */
    public function __construct(string $plugin_slug, \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Model\FormTemplate $form_template, \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Model\FormOptions $form_options, \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Model\FormValues $form_values, \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Sender\Sender $request_sender)
    {
        $this->plugin_slug = $plugin_slug;
        $this->form_template = $form_template;
        $this->form_options = $form_options;
        $this->form_values = $form_values;
        $this->request_sender = $request_sender;
        (new \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Service\AssetsPrinterService($this->plugin_slug))->hooks();
        (new \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Service\TemplateGeneratorService($this->plugin_slug, $this->form_template, $this->form_options, $this->form_values))->hooks();
        (new \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Service\RequestSenderService($this->plugin_slug, $this->form_options, $this->form_values, $this->request_sender))->hooks();
    }
}
