<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Installer;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\SettingsFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\SettingsDataProvider;
use DropshippingXmlFreeVendor\WPDesk\DeactivationModal;
/**
 * Class PluginInstallerAction, activates when plugin is installed or uninstalled.
 */
class PluginInstallerAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable
{
    const ACTIVATION_OPTION_NAME = 'plugin_activation_%s';
    /**
     * @var Config
     */
    private $config;
    /**
     *
     * @var DataProviderFactory
     */
    private $data_provider_factory;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config $config, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory $data_provider_factory)
    {
        $this->config = $config;
        $this->data_provider_factory = $data_provider_factory;
    }
    public function hooks()
    {
        \add_action('plugins_loaded', [$this, 'on_deactivation']);
    }
    public function on_deactivation()
    {
        new \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Modal($this->config->get_param('plugin.slug')->getAsString(), new \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Model\FormTemplate($this->config->get_param('plugin.name')->getAsString()), new \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Model\DefaultFormOptions(), (new \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Model\FormValues())->set_value(new \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Model\FormValue('is_localhost', [$this, 'is_localhost']))->set_value(new \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Model\FormValue('plugin_using_time', [$this, 'get_time_of_plugin_using']))->set_value(new \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Model\FormValue('settings_saved', [$this, 'check_if_plugin_settings_saved'])), new \DropshippingXmlFreeVendor\WPDesk\DeactivationModal\Sender\DataWpdeskSender($this->config->get_param('plugin.file')->getAsString(), $this->config->get_param('plugin.name')->getAsString()));
    }
    /**
     * @internal
     */
    public function is_localhost() : bool
    {
        return \in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], \true);
    }
    /**
     * @return int|null
     * @internal
     */
    public function get_time_of_plugin_using()
    {
        $option_activation = \sprintf(self::ACTIVATION_OPTION_NAME, $this->config->get_param('plugin.file')->getAsString());
        $activation_date = \get_option($option_activation, null);
        if ($activation_date === null) {
            return null;
        }
        $current_date = \current_time('mysql');
        return \strtotime((string) $current_date) - \strtotime($activation_date);
    }
    /**
     * @internal
     */
    public function check_if_plugin_settings_saved() : bool
    {
        $options_data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\SettingsDataProvider::class);
        return $options_data_provider->has(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\SettingsFormFields::INPUT_TEXT_BATCH);
    }
}
