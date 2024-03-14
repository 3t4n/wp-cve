<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction\AbstractSingleConfig;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Cron\ImportCronAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Installer\PluginInstallerAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Assets\PluginAssetsLoaderAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Assets\AjaxAssetsLoaderAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Assets\WooAssetsLoaderAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Menu\AdminMenuLoaderAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Plugin\PluginLinksLoaderAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\PostType\ImportPostTypeLoaderAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\Form\ImportManagerFormProcessAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\ImportProcessAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\ConvertCsvImportAjaxAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\ConvertXmlImportAjaxAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\PreviewCsvImportAjaxAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\PreviewXmlImportAjaxAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\StopImportAjaxAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\FileImportAjaxAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\PreviewVariationsAjaxAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Cron\ClearTempFilesCronAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\Form\ImportSidebarFormProcessAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Product\ProductColumnLoaderAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Notification\FileLimitNotificationAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Assets\MenuPluginAssetsLoaderAction;
/**
 * Class ActionConfig, configuration class for actions.
 * @package WPDesk\Library\DropshippingXmlCore\Config
 */
class ActionConfig extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction\AbstractSingleConfig
{
    const ID = 'action';
    public function get() : array
    {
        return [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Installer\PluginInstallerAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Notification\FileLimitNotificationAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\FileImportAjaxAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\ConvertCsvImportAjaxAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\PreviewCsvImportAjaxAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\ConvertXmlImportAjaxAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\PreviewXmlImportAjaxAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\PreviewVariationsAjaxAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\StopImportAjaxAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Cron\ClearTempFilesCronAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Cron\ImportCronAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\ImportProcessAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\Form\ImportManagerFormProcessAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\Form\ImportSidebarFormProcessAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Assets\AjaxAssetsLoaderAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Assets\PluginAssetsLoaderAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Assets\MenuPluginAssetsLoaderAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Assets\WooAssetsLoaderAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\PostType\ImportPostTypeLoaderAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Plugin\PluginLinksLoaderAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Menu\AdminMenuLoaderAction::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Product\ProductColumnLoaderAction::class];
    }
    public function get_id() : string
    {
        return self::ID;
    }
}
