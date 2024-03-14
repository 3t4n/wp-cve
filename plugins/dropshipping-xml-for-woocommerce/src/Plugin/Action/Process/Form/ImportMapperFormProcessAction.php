<?php

namespace WPDesk\DropshippingXmlFree\Action\Process\Form;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportMapperDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportMapperForm;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportManagerViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportOptionsViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\Form\ImportMapperFormProcessAction as ImportMapperFormProcessActionCore;
use WPDesk\DropshippingXmlFree\Form\Fields\ImportMapperFormFields;
/**
 * Class ImportMapperFormProcessAction, import mapper view form process.
 */
class ImportMapperFormProcessAction extends ImportMapperFormProcessActionCore {

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var ImportMapperForm
	 */
	private $form;

	/**
	 * @var PluginHelper
	 */
	private $plugin_helper;

	/**
	 * @var DataProviderFactory
	 */
	private $data_provider_factory;
	public function __construct( Request $request, ImportMapperForm $form, DataProviderFactory $data_provider_factory, PluginHelper $helper ) {
		$this->request               = $request;
		$this->form                  = $form;
		$this->plugin_helper         = $helper;
		$this->data_provider_factory = $data_provider_factory;
	}
	public function isActive() : bool {
		$settings = $this->request->get_param( 'post.' . ImportMapperForm::get_id() );
		return $settings->isArray() && ! $settings->isEmpty();
	}
	public function init() {
		$this->save_form_data();
	}
	private function save_form_data() {
		$uid  = $this->request->get_param( 'get.uid' )->getAsString();
		$post = $this->request->get_param( 'post.' . ImportMapperForm::get_id() )->get();

		foreach ( $this->get_fields_to_remove() as $field ) {
			if ( isset( $post[ $field ] ) ) {
				unset( $post[ $field ] );
			}
		}

		$this->form->handle_request( $post );
		if ( $this->form->is_valid() && \current_user_can( 'manage_options' ) ) {
			$data_provider = $this->data_provider_factory->create_by_class_name( ImportMapperDataProvider::class, [ 'postfix' => $uid ] );
			$data_provider->update( $this->form );
			$edit = $this->request->get_param( 'get.mode' )->get() === 'edit';
			$mode = $this->request->get_param( 'get.mode' )->getAsString();
			if ( ! empty( $mode ) ) {
				$args = [
					'uid'  => $uid,
					'mode' => $mode,
				];
			} else {
				$args = [ 'uid' => $uid ];
			}

			$url = $edit ? $this->plugin_helper->generate_url_by_view( ImportManagerViewAction::class ) : $this->plugin_helper->generate_url_by_view( ImportOptionsViewAction::class, $args );
			$this->redirect( $url );
		}
	}
	private function redirect( string $url ) {
		\wp_redirect( $url );
		exit;
	}

	private function get_fields_to_remove():array {
		return ImportMapperFormFields::FIELDS_TO_DISABLE;
	}
}
