<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportCsvSelectorFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use RuntimeException;
use Exception;
use DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Validator\AjaxActionValidatorService;
/**
 * Class PreviewCsvImportAjaxAction, class that handles csv file preview via ajax.
 */
class PreviewCsvImportAjaxAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable
{
    const AJAX_ACTION = 'preview_csv_product_data';
    const AJAX_NONCE = 'nonce_preview_csv_product_data';
    const NODE_ELEMENT = 'node';
    /**
     * @var Request
     */
    private $request;
    /**
     * @var FileLocatorService
     */
    private $file_locator;
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var XmlAnalyser
     */
    private $xml_analyser;
    /**
     * @var AjaxActionValidatorService
     */
    private $validator_service;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer $renderer, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService $file_locator, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser $xml_analyser, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Validator\AjaxActionValidatorService $validator_service)
    {
        $this->request = $request;
        $this->file_locator = $file_locator;
        $this->renderer = $renderer;
        $this->xml_analyser = $xml_analyser;
        $this->validator_service = $validator_service;
    }
    public function isActive() : bool
    {
        return \wp_doing_ajax();
    }
    public function hooks()
    {
        \add_action('wp_ajax_' . self::AJAX_ACTION, array($this, 'ajax_import_data'));
    }
    public function ajax_import_data()
    {
        try {
            $this->validate_form_data();
            $post_data = $this->prepare_form_data();
            $converted = $this->file_locator->get_converted_file($post_data['uid']);
            $this->xml_analyser->load_from_file($converted);
            $node = $this->xml_analyser->get_element_as_object(self::NODE_ELEMENT, $post_data['page']);
            $content = $this->renderer->render('Import/ImportSelectorCsvTable', ['node' => $node]);
            \wp_send_json(['success' => \true, 'message' => \__('Preview refreshed. Make sure that all information are correct and go to next step by clicking the button below.', 'dropshipping-xml-for-woocommerce'), 'content' => $content]);
        } catch (\Exception $e) {
            \wp_send_json(['success' => \false, 'message' => $e->getMessage()]);
        }
    }
    private function validate_form_data()
    {
        $this->validator_service->is_valid($this->request->get_param('post.security')->getAsString(), self::AJAX_NONCE);
        $post_data = $this->request->get_param('post.' . \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportCsvSelectorFormFields::ITEM_NUMBER);
        if (!$post_data->isSet()) {
            throw new \RuntimeException(\__('Error page number is empty.', 'dropshipping-xml-for-woocommerce'));
        }
    }
    private function prepare_form_data() : array
    {
        return ['page' => (int) $this->request->get_param('post.' . \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportCsvSelectorFormFields::ITEM_NUMBER)->get(), 'uid' => $this->request->get_param('post.uid')->getAsString()];
    }
}
