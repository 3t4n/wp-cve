<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportXmlSelectorFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use RuntimeException;
use Exception;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Converter\FileConverterService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Validator\AjaxActionValidatorService;
/**
 * Class ConvertXmlImportAjaxAction, class that handles xml file conversion via ajax.
 */
class ConvertXmlImportAjaxAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable
{
    const AJAX_ACTION = 'evaluate_xml_product_data';
    const AJAX_NONCE = 'nonce_evaluate_xml_product_data';
    const NODE_NUMBER = 1;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var FileLocatorService
     */
    private $file_locator;
    /**
     * @var FileConverterService
     */
    private $converter_service;
    /**
     * @var XmlAnalyser
     */
    private $analyser;
    /**
     * @var DataProviderFactory
     */
    private $data_provider_factory;
    /**
     * @var AjaxActionValidatorService
     */
    private $validator_service;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Converter\FileConverterService $converter_service, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory $data_provider_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser $analyser, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService $file_locator, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Validator\AjaxActionValidatorService $validator_service)
    {
        $this->request = $request;
        $this->file_locator = $file_locator;
        $this->converter_service = $converter_service;
        $this->analyser = $analyser;
        $this->data_provider_factory = $data_provider_factory;
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
            $form_data = $this->prepare_form_data();
            $source_file = $this->file_locator->get_source_file($form_data['uid']);
            $this->converter_service->convert_from_format(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat::XML);
            $converted_file = $this->converter_service->convert($source_file, $form_data);
            $this->analyser->load_from_file($converted_file);
            $content = $this->analyser->get_element_as_xml($form_data['element_id'], self::NODE_NUMBER, \true);
            $items = $this->analyser->count_element($form_data['element_id']);
            \wp_send_json(['success' => \true, 'message' => \__('Preview refreshed. Make sure that selected tag contains all information about the product and go to next step by clicking the button below.', 'dropshipping-xml-for-woocommerce'), 'content' => $content, 'items' => $items]);
        } catch (\Exception $e) {
            \wp_send_json(['success' => \false, 'message' => $e->getMessage()]);
        }
    }
    private function validate_form_data()
    {
        $this->validator_service->is_valid($this->request->get_param('post.security')->getAsString(), self::AJAX_NONCE);
        $uid = $this->request->get_param('post.uid');
        if (!$uid->isSet()) {
            throw new \RuntimeException(\__('UID is not set, something is wrong with your import.', 'dropshipping-xml-for-woocommerce'));
        }
        $post_data = $this->request->get_param('post.' . \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportXmlSelectorFormFields::NODE_ELEMENT);
        if (!$post_data->isSet()) {
            throw new \RuntimeException(\__('Error: node element is empty.', 'dropshipping-xml-for-woocommerce'));
        }
    }
    private function prepare_form_data() : array
    {
        return ['element_id' => $this->request->get_param('post.' . \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportXmlSelectorFormFields::NODE_ELEMENT)->getAsString(), 'uid' => $this->request->get_param('post.uid')->getAsString()];
    }
}
