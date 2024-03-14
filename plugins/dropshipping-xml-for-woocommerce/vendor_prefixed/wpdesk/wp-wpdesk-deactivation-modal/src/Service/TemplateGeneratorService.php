<?php

namespace DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Service;

use DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Hookable;
use DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Modal;
use DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Model\FormOptions;
use DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Model\FormTemplate;
use DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Model\FormValues;
/**
 * Prints the deactivation modal template on the plugin list page.
 */
class TemplateGeneratorService implements \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Hookable
{
    /**
     * @var string
     */
    private $plugin_slug;
    /**
     * @var FormTemplate
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
    public function __construct(string $plugin_slug, \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Model\FormTemplate $form_template, \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Model\FormOptions $form_options, \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Model\FormValues $form_values)
    {
        $this->plugin_slug = $plugin_slug;
        $this->form_template = $form_template;
        $this->form_options = $form_options;
        $this->form_values = $form_values;
    }
    /**
     * {@inheritdoc}
     */
    public function hooks()
    {
        \add_action('admin_print_footer_scripts-plugins.php', [$this, 'load_template'], 0);
    }
    public function load_template()
    {
        $params = ['api_url' => \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Service\RequestSenderService::generate_ajax_url($this->plugin_slug), 'plugin_slug' => $this->plugin_slug, 'field_name_reason' => \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Service\RequestSenderService::FORM_FIELD_REASON, 'field_name_message' => \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Service\RequestSenderService::FORM_FIELD_MESSAGE, 'form_template' => $this->form_template, 'form_options' => $this->form_options, 'form_values' => $this->form_values];
        \extract($params);
        // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
        require_once \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Modal::MODAL_TEMPLATE_PATH;
    }
}
