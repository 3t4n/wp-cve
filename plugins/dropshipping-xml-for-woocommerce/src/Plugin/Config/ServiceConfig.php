<?php

namespace WPDesk\DropshippingXmlFree\Config;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config\ServiceConfig as ServiceConfigCore;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields as FieldsImportMapperFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields as FieldsImportOptionsFormFields;
use WPDesk\DropshippingXmlFree\Form\Fields\ImportMapperFormFields;
use WPDesk\DropshippingXmlFree\Form\Fields\ImportOptionsFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\Form\ImportMapperFormProcessAction as ImportMapperFormProcessActionCore;
use WPDesk\DropshippingXmlFree\Action\Process\Form\ImportMapperFormProcessAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\Form\ImportOptionsFormProcessAction as ImportOptionsFormProcessActionCore;
use WPDesk\DropshippingXmlFree\Action\Process\Form\ImportOptionsFormProcessAction;
use WPDesk\DropshippingXmlFree\Service\Mapper\Product\ProductImageMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductImageMapperService as ProductImageMapperServiceCore;


/**
 * Class ServiceConfig, configuration class for services and it's dependencies.
 */
class ServiceConfig extends ServiceConfigCore {

	public function get() : array {
		$services = parent::get();
		$request  = $services['bind'][ Request::class ];

		$services['bind'][ FieldsImportMapperFormFields::class ]       = ImportMapperFormFields::class;
		$services['bind'][ FieldsImportOptionsFormFields::class ]      = ImportOptionsFormFields::class;
		$services['bind'][ ImportOptionsFormFields::class ]            = [ 'uid' => $this->get_uid( $request ) ];
		$services['bind'][ ImportMapperFormProcessActionCore::class ]  = ImportMapperFormProcessAction::class;
		$services['bind'][ ImportOptionsFormProcessActionCore::class ] = ImportOptionsFormProcessAction::class;
		$services['bind'][ ProductImageMapperServiceCore::class ]      = ProductImageMapperService::class;

		return $services;
	}
}
