<?php
/**
 * Plugin Class.
 *
 * @package talentlms-wordpress
 */

namespace TalentlmsIntegration;

use TalentlmsIntegration\Services\PluginService;

final class Plugin
{
    private $services = array(
            Pages\Admin::class,
            Pages\Errors::class,
            Pages\Help::class,
            Ajax::class,
            Database::class,
            Enqueue::class,
            Woocommerce::class,
            ShortCodes::class,
            TLMSWidget::class
    );

    /**
     * @return PluginService[]
     */
    public function get_services(): array
    {
        return $this->services;
    }

    /**
     * Loop through the classes, initialize them,
     * and call the register() method if it exists
     *
     * @return Plugin
     */
    public function register_services(): Plugin
    {
        foreach ($this->get_services() as $class) {
            $service = new $class();
            if (! $service instanceof PluginService) {
                throw new \RuntimeException('A plugin must conform PluginService contract');
            }
            $service->register();
        }

        return $this;
    }

    public static function init(): Plugin
    {
        return ( new self() )->register_services();
    }
}
