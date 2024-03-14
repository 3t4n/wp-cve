<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportFileFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportFileForm;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Connector\FileConnectorService;
use Exception;
use RuntimeException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Validator\AjaxActionValidatorService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Resolver\FileFormatResolverService;
/**
 * Class FileImportAjaxAction, class that handles file import via ajax.
 */
class FileImportAjaxAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable
{
    const AJAX_ACTION = 'download_product_data';
    const AJAX_NONCE = 'nonce_download_product_data';
    /**
     * @var Request
     */
    private $request;
    /**
     * @var FileLocatorService
     */
    private $file_locator;
    /**
     * @var FileConnectorService
     */
    private $file_connector;
    /**
     * @var AjaxActionValidatorService
     */
    private $validator_service;
    /**
     * @var FileFormatResolverService
     */
    private $file_format_resolver_service;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService $file_locator, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Connector\FileConnectorService $file_connector, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Validator\AjaxActionValidatorService $validator_service, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Resolver\FileFormatResolverService $file_format_resolver_service)
    {
        $this->request = $request;
        $this->file_locator = $file_locator;
        $this->file_connector = $file_connector;
        $this->validator_service = $validator_service;
        $this->file_format_resolver_service = $file_format_resolver_service;
    }
    public function isActive() : bool
    {
        return \wp_doing_ajax();
    }
    public function hooks()
    {
        \add_action('wp_ajax_' . self::AJAX_ACTION, array($this, 'ajax_download_data'));
    }
    public function ajax_download_data()
    {
        try {
            $this->validate_form_data();
            $form_data = $this->prepare_form_data();
            $uid = $form_data[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportFileFormFields::UID];
            $tmp_file_path = $this->file_locator->generate_tmp_file_path($uid);
            $file = $this->file_connector->get_file($tmp_file_path, $form_data);
            $file_format = $this->file_format_resolver_service->resolve_data_format($file);
            \wp_send_json(['success' => \true, 'message' => \__('File downloaded successfully. Please move to the next step by clicking the button below.', 'dropshipping-xml-for-woocommerce'), 'original_file_format' => $file_format, 'original_file_name' => $file->getFilename()]);
        } catch (\Exception $e) {
            \wp_send_json(['success' => \false, 'message' => \sprintf(\__('Download failed, error message: %s ', 'dropshipping-xml-for-woocommerce'), $e->getMessage())]);
        }
    }
    private function validate_form_data()
    {
        $this->validator_service->is_valid($this->request->get_param('post.security')->getAsString(), self::AJAX_NONCE);
        $post_data = $this->request->get_param('post.data');
        if (!$post_data->isSet()) {
            throw new \RuntimeException(\__('Error with the post data.', 'dropshipping-xml-for-woocommerce'));
        }
        $form_data = [];
        \parse_str($post_data->getAsString(), $form_data);
        if (!isset($form_data[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportFileForm::ID][\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportFileFormFields::UID])) {
            throw new \RuntimeException(\__('Error empty uid in form data.', 'dropshipping-xml-for-woocommerce'));
        }
    }
    private function prepare_form_data() : array
    {
        $form_data = [];
        $post_data = $this->request->get_param('post.data')->getAsString();
        \parse_str($post_data, $form_data);
        return $form_data[\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportFileForm::ID];
    }
}
