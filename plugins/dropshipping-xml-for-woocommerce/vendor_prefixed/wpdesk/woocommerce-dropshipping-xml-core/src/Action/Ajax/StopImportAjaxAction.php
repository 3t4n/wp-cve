<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use Exception;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Validator\AjaxActionValidatorService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import;
/**
 * Class StopImportAjaxAction, class that handles import to stop.
 */
class StopImportAjaxAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable
{
    const AJAX_ACTION = 'stop_import_products';
    const AJAX_NONCE = 'nonce_stop_import_products';
    /**
     * @var Request
     */
    private $request;
    /**
     * @var AjaxActionValidatorService
     */
    private $validator_service;
    /**
     * @var ImportDAO
     */
    private $import_dao;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Validator\AjaxActionValidatorService $validator_service, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO $import_dao)
    {
        $this->request = $request;
        $this->validator_service = $validator_service;
        $this->import_dao = $import_dao;
    }
    public function isActive() : bool
    {
        return \wp_doing_ajax();
    }
    public function hooks()
    {
        \add_action('wp_ajax_' . self::AJAX_ACTION, array($this, 'ajax_process'));
    }
    public function ajax_process()
    {
        try {
            $this->validate_form_data();
            $post_data = $this->prepare_form_data();
            $import = $this->import_dao->find_by_uid($post_data['uid']);
            $import->set_status(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_STOPPED);
            $this->import_dao->update($import);
            \wp_send_json(['success' => \true, 'message' => \__('Import stopped.', 'dropshipping-xml-for-woocommerce')]);
        } catch (\Exception $e) {
            \wp_send_json(['success' => \false, 'message' => $e->getMessage()]);
        }
    }
    private function validate_form_data()
    {
        $this->validator_service->is_valid($this->request->get_param('post.security')->getAsString(), self::AJAX_NONCE);
    }
    private function prepare_form_data() : array
    {
        return ['uid' => $this->request->get_param('post.uid')->getAsString()];
    }
}
