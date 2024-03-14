<?php

namespace WPDesk\DropshippingXmlFree\Config;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config\ActionConfig as ActionConfigCore;
use WPDesk\DropshippingXmlFree\Action\Installer\PluginUnistallerAction;
use WPDesk\DropshippingXmlFree\Action\Loader\Plugin\PluginLinksLoaderAction;
use WPDesk\DropshippingXmlFree\Action\Loader\Assets\PluginAssetsLoaderAction;
use WPDesk\DropshippingXmlFree\Action\Loader\Plugin\PluginFiltersRemover;
/**
 * Class ActionConfig, configuration class for services and it's dependencies.
 */
class ActionConfig extends ActionConfigCore {

	public function get() : array {
		$actions = parent::get();

		$actions = \array_merge(
			[
				PluginFiltersRemover::class,
				PluginUnistallerAction::class,
				PluginLinksLoaderAction::class,
				PluginAssetsLoaderAction::class,
			],
			$actions
		);

		return $actions;
	}
}
