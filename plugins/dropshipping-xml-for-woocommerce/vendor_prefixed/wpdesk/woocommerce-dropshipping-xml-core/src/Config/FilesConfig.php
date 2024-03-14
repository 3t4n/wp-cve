<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction\AbstractSingleConfig;
/**
 * Class FilesConfig, configuration class for external files.
 * @package WPDesk\Library\DropshippingXmlCore\Config
 */
class FilesConfig extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction\AbstractSingleConfig
{
    const ID = 'files';
    public function get() : array
    {
        $dir = \trailingslashit(\wp_upload_dir()['basedir']);
        $dir_url = \trailingslashit(\wp_upload_dir()['baseurl']);
        return ['dir' => $dir . 'dropshipping/', 'dir_url' => $dir_url . 'dropshipping/', 'tmp' => ['dir' => $dir . 'dropshipping/tmp/', 'dir_url' => $dir_url . 'dropshipping/tmp/']];
    }
    public function get_id() : string
    {
        return self::ID;
    }
}
