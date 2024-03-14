<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use RuntimeException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject;
use Exception;
/**
 * Class UploadFileLocatorService, service that helps to check and locate uploaded file.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Locator
 */
class FileLocatorService
{
    /**
     * @var Config
     */
    private $config;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config $config)
    {
        $this->config = $config;
    }
    public function get_source_file(string $uid) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject
    {
        $tmp_dir = $this->get_tmp_dir($uid);
        $file_location = $tmp_dir . $uid;
        if (!$this->is_path_secure($tmp_dir, $file_location)) {
            throw new \RuntimeException('Directory traversal security error');
        }
        return new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject($file_location);
    }
    public function get_converted_file(string $uid) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject
    {
        $tmp_dir = $this->get_tmp_dir($uid);
        $file_location = $tmp_dir . $uid . '.' . \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat::XML;
        if (!$this->is_path_secure($tmp_dir, $file_location)) {
            throw new \RuntimeException('Directory traversal security error');
        }
        return new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject($file_location);
    }
    public function is_source_file_exists(string $uid) : bool
    {
        try {
            $this->get_source_file($uid);
            return \true;
        } catch (\Exception $e) {
            return \false;
        }
    }
    public function is_converted_file_exists(string $uid) : bool
    {
        try {
            $this->get_converted_file($uid);
            return \true;
        } catch (\Exception $e) {
            return \false;
        }
    }
    public function create_directory_path_if_not_exists(string $file_path)
    {
        if (!\file_exists($file_path)) {
            \wp_mkdir_p($file_path);
        }
    }
    public function is_path_secure(string $expected_dir_location, string $file_path) : bool
    {
        $base = \realpath($expected_dir_location);
        $user_path = \realpath($file_path);
        return !($user_path === \false || \strpos($user_path, $base) !== 0);
    }
    public function get_tmp_dir(string $uid = '') : string
    {
        return \trailingslashit($this->config->get_param('files.tmp.dir')->get() . $uid);
    }
    public function generate_tmp_file_path(string $uid) : string
    {
        return $this->get_tmp_dir($uid) . $uid;
    }
    public function check_if_dir_exists(string $save_location) : bool
    {
        $pathinfo = \pathinfo($save_location);
        return \file_exists($pathinfo['dirname']);
    }
    public function check_if_file_is_dir(string $save_location) : bool
    {
        return \is_dir($save_location);
    }
    public function clone_uid(string $from_uid, string $to_uid)
    {
        $tmp_dir = $this->get_tmp_dir($from_uid);
        $source_file_location = $tmp_dir . $from_uid;
        $converted_file_location = $source_file_location . '.' . \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat::XML;
        $dest_source_file_location = $this->generate_tmp_file_path($to_uid);
        $dest_converted_file_location = $dest_source_file_location . '.' . \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat::XML;
        $path_info = \pathinfo($dest_source_file_location);
        $this->create_directory_path_if_not_exists($path_info['dirname']);
        if (\file_exists($source_file_location)) {
            \copy($source_file_location, $dest_source_file_location);
        }
        if (\file_exists($converted_file_location)) {
            \copy($converted_file_location, $dest_converted_file_location);
        }
    }
}
