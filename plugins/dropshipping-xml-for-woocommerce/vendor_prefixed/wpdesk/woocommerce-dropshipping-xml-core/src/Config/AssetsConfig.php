<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction\AbstractSingleConfig;
/**
 * Class AssetsConfig, configuration class for assets.
 * @package WPDesk\Library\DropshippingXmlCore\Config
 */
class AssetsConfig extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction\AbstractSingleConfig
{
    const ID = 'assets';
    public function get() : array
    {
        $dir = $this->get_config()->get_param('plugin.dir')->get();
        $dir_url = $this->get_config()->get_param('plugin.dir_url')->get();
        $core_dir = $this->get_config()->get_param('plugin.core_dir')->get();
        $core_dir_url = $this->get_config()->get_param('plugin.core_dir_url')->get();
        return ['dir' => $dir . 'assets/', 'dir_url' => $dir_url . 'assets/', 'core_dir' => $core_dir . 'assets/', 'core_dir_url' => $core_dir_url . 'assets/', 'css' => ['dir' => $dir . 'assets/css/', 'dir_url' => $dir_url . 'assets/css/', 'core_dir' => $core_dir . 'assets/css/', 'core_dir_url' => $core_dir_url . 'assets/css/'], 'js' => ['dir' => $dir . 'assets/js/', 'dir_url' => $dir_url . 'assets/js/', 'core_dir' => $core_dir . 'assets/js/', 'core_dir_url' => $core_dir_url . 'assets/js/'], 'img' => ['dir' => $dir . 'assets/img/', 'dir_url' => $dir_url . 'assets/img/', 'core_dir' => $core_dir . 'assets/img/', 'core_dir_url' => $core_dir_url . 'assets/img/']];
    }
    public function get_id() : string
    {
        return self::ID;
    }
}
