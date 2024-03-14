<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportXmlSelectorFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use RuntimeException;
use Exception;
use DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Validator\AjaxActionValidatorService;
/**
 * Class PreviewXmlImportAjaxAction, class that handles xml file preview via ajax.
 */
class PreviewVariationsAjaxAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable
{
    const AJAX_ACTION = 'preview_variations_product_data';
    const AJAX_NONCE = 'nonce_preview_variations_product_data';
    const NODE_ELEMENT = 'node';
    const PARENT_NODE = 'parent_node';
    const PARENT_PAGE = 'parent_page';
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
        \add_action('wp_ajax_' . self::AJAX_ACTION, [$this, 'ajax_import_data']);
    }
    public function ajax_import_data()
    {
        try {
            $this->validate_form_data();
            $post_data = $this->prepare_form_data();
            $converted = $this->file_locator->get_converted_file($post_data['uid']);
            $this->xml_analyser->load_from_file($converted);
            $parent_content = $this->xml_analyser->get_element_as_xml($post_data['parent_element'], $post_data['parent_page'], \true);
            $analyser = $this->create_product_analyser($parent_content);
            $element = $this->get_node_element($post_data);
            $content = $analyser->get_element_as_xml($element, $post_data['item_number'], \true);
            $items = \count($analyser->get_objects_by_xpath('//' . $element));
            \wp_send_json(['success' => \true, 'message' => \__('Preview has been refreshed.', 'dropshipping-xml-for-woocommerce'), 'content' => $content, 'items' => $items]);
        } catch (\Exception $e) {
            \wp_send_json(['success' => \false, 'message' => $e->getMessage()]);
        }
    }
    private function create_product_analyser(string $xml_content) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser
    {
        $element_analyser = new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser();
        $element_analyser->load_from_content($xml_content);
        return $element_analyser;
    }
    private function get_node_element(array $post_data) : string
    {
        $element = \str_replace(['{//', '}'], '', $post_data['element']);
        return $element;
    }
    private function validate_form_data()
    {
        $this->validator_service->is_valid($this->request->get_param('post.security')->getAsString(), self::AJAX_NONCE);
        $item_number = $this->request->get_param('post.' . \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportXmlSelectorFormFields::ITEM_NUMBER);
        if (!$item_number->isSet()) {
            throw new \RuntimeException(\__('Error: item number is empty.', 'dropshipping-xml-for-woocommerce'));
        }
        $node_element = $this->request->get_param('post.' . \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportXmlSelectorFormFields::NODE_ELEMENT);
        if (!$node_element->isSet()) {
            throw new \RuntimeException(\__('Error: element is empty.', 'dropshipping-xml-for-woocommerce'));
        }
        $parent_element = $this->request->get_param('post.args.' . self::PARENT_NODE);
        if (!$parent_element->isSet()) {
            throw new \RuntimeException(\__('Error: parent node is empty.', 'dropshipping-xml-for-woocommerce'));
        }
        $parent_page = $this->request->get_param('post.args.' . self::PARENT_PAGE);
        if (!$parent_page->isSet()) {
            throw new \RuntimeException(\__('Error: parent page is empty.', 'dropshipping-xml-for-woocommerce'));
        }
    }
    private function prepare_form_data() : array
    {
        return ['item_number' => (int) $this->request->get_param('post.' . \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportXmlSelectorFormFields::ITEM_NUMBER)->get(), 'element' => $this->request->get_param('post.' . \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportXmlSelectorFormFields::NODE_ELEMENT)->getAsString(), 'uid' => $this->request->get_param('post.uid')->getAsString(), 'parent_element' => $this->request->get_param('post.args.' . self::PARENT_NODE)->getAsString(), 'parent_page' => $this->request->get_param('post.args.' . self::PARENT_PAGE)->getAsString()];
    }
}
