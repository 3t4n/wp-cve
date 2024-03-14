<?php

namespace WPDesk\DropshippingXmlFree\Action\Loader\Plugin;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\CsvAnalyser;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ConnectorClientFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductImageMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductMapperService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Converter\File\CsvFileConverter;

/**
 * Class PluginLinksLoaderAction, plugin links loader.
 */
class PluginFiltersRemover implements Hookable {

	public function hooks() {
		add_action(
			'init',
			function() {
				remove_all_filters( CsvAnalyser::FILTER_SOURCE_ENCODING );
				remove_all_filters( XmlAnalyser::FILTER_NAME_MAX_ANALYSE_DEPTH );
				remove_all_filters( ConnectorClientFactory::FILTER_BYPASS_SSL );
				remove_all_filters( ConnectorClientFactory::FILTER_HTTP_CLIENT_OPTIONS );
				remove_all_filters( ProductImageMapperService::FILTER_NAME_IMAGES );
				remove_all_filters( ProductMapperService::FILTER_NAME_DESCRIPTION );
				remove_all_filters( ProductMapperService::FILTER_NAME_SHORT_DESCRIPTION );
				remove_all_filters( ProductMapperService::FILTER_NAME_SHORT_TITLE );
				remove_all_filters( ProductMapperService::FILTER_NAME_STOCK );
				remove_all_filters( CsvFileConverter::FILTER_ENCODE_STRING );
			}
		);
	}
}
