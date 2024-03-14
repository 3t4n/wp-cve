<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Assets;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\ImportProcessAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\FileImportAjaxAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\ConvertCsvImportAjaxAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\PreviewCsvImportAjaxAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\PreviewXmlImportAjaxAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\ConvertXmlImportAjaxAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\StopImportAjaxAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\PreviewVariationsAjaxAction;
/**
 * Class AjaxAssetsLoaderAction, loads variables required by javascript.
 */
class AjaxAssetsLoaderAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var PluginHelper
     */
    private $plugin_helper;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config $config, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper $helper)
    {
        $this->config = $config;
        $this->request = $request;
        $this->plugin_helper = $helper;
    }
    public function isActive() : bool
    {
        return $this->plugin_helper->is_plugin_page($this->request->get_param('get.page')->getAsString(), $this->request->get_param('get.action')->getAsString());
    }
    public function hooks()
    {
        \add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'), 91);
    }
    public function enqueue_scripts()
    {
        \wp_localize_script('dropshipping_admin', \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\FileImportAjaxAction::AJAX_ACTION, array('nonce' => \wp_create_nonce(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\FileImportAjaxAction::AJAX_NONCE)));
        \wp_localize_script('dropshipping_admin', \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\ConvertCsvImportAjaxAction::AJAX_ACTION, array('nonce' => \wp_create_nonce(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\ConvertCsvImportAjaxAction::AJAX_NONCE)));
        \wp_localize_script('dropshipping_admin', \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\ConvertXmlImportAjaxAction::AJAX_ACTION, array('nonce' => \wp_create_nonce(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\ConvertXmlImportAjaxAction::AJAX_NONCE)));
        \wp_localize_script('dropshipping_admin', \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\PreviewCsvImportAjaxAction::AJAX_ACTION, array('nonce' => \wp_create_nonce(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\PreviewCsvImportAjaxAction::AJAX_NONCE)));
        \wp_localize_script('dropshipping_admin', \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\PreviewXmlImportAjaxAction::AJAX_ACTION, array('nonce' => \wp_create_nonce(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\PreviewXmlImportAjaxAction::AJAX_NONCE)));
        \wp_localize_script('dropshipping_admin', \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\PreviewVariationsAjaxAction::AJAX_ACTION, array('nonce' => \wp_create_nonce(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\PreviewVariationsAjaxAction::AJAX_NONCE)));
        \wp_localize_script('dropshipping_admin', \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\ImportProcessAction::AJAX_ACTION, array('nonce' => \wp_create_nonce(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\ImportProcessAction::AJAX_NONCE)));
        \wp_localize_script('dropshipping_admin', \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\StopImportAjaxAction::AJAX_ACTION, array('nonce' => \wp_create_nonce(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Ajax\StopImportAjaxAction::AJAX_NONCE)));
    }
}
