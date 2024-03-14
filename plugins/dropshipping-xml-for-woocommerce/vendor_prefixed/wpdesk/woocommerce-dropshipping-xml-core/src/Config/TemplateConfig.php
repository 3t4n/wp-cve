<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction\AbstractSingleConfig;
/**
 * Class TemplateConfig, configuration class for templates.
 * @package WPDesk\Library\DropshippingXmlCore\Config
 */
class TemplateConfig extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction\AbstractSingleConfig
{
    const ID = 'templates';
    public function get() : array
    {
        $dir = $this->get_config()->get_param('plugin.dir')->get();
        $core_dir = $this->get_config()->get_param('plugin.core_dir')->get();
        return ['dir' => $dir . 'src/Plugin/Template/', 'form_fields_dir' => $dir . 'src/Plugin/Template/FormFields/', 'core_dir' => $core_dir . 'src/Template/', 'core_form_fields_dir' => $core_dir . 'src/Template/FormFields/'];
    }
    public function get_id() : string
    {
        return self::ID;
    }
}
