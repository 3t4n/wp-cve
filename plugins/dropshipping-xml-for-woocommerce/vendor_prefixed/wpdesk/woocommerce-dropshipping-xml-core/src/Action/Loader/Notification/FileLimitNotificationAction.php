<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Notification;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
/**
 * Class FileLimitNotificationAction.
 */
class FileLimitNotificationAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable
{
    const FILTER_FILES_LIMIT = 'wpdesk_dropshipping_files_limit_mb';
    const FILTER_FILES_LIMIT_MESSAGE = 'wpdesk_dropshipping_files_limit_message';
    /**
     * @var Config
     */
    private $config;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config $config)
    {
        $this->config = $config;
    }
    public function hooks()
    {
        \add_action('admin_init', function () {
            if ($this->is_file_limit_reached()) {
                \add_action('admin_notices', [$this, 'show_error_notification'], 10);
            }
        });
    }
    public function is_file_limit_reached() : bool
    {
        $max_file_size_mb = (int) \apply_filters(self::FILTER_FILES_LIMIT, 0);
        return $max_file_size_mb > 0 && $this->get_files_size() > $max_file_size_mb;
    }
    public function show_error_notification()
    {
        $max_file_size_message = \apply_filters(self::FILTER_FILES_LIMIT_MESSAGE, \__('You have reached the maximum file size limit in the dropshipping xml plugin. Delete old imports to be able to create new ones.', 'dropshipping-xml-for-woocommerce'));
        $class = 'notice notice-error';
        \printf('<div class="%1$s"><p>%2$s</p></div>', \esc_attr($class), \esc_html($max_file_size_message));
    }
    private function get_files_size() : float
    {
        $dir = $this->config->get_param('files.dir')->getAsString();
        if (!\is_dir($dir)) {
            return (float) 0;
        }
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->config->get_param('files.dir')->getAsString()));
        $total_size = 0;
        foreach ($iterator as $file) {
            $total_size += $file->getSize();
        }
        if ($total_size > 0) {
            $total_size = $total_size / (1024 * 1024);
        }
        return (float) $total_size;
    }
}
