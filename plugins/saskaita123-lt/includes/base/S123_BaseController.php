<?php
/**
 * @link https://www.invoice123.com
 * @package Saskaita123Plugin
 */

namespace S123\Includes\Base;

if (!defined('ABSPATH')) exit;

class S123_BaseController
{
    /**
     * Plugin path.
     *
     * @var string
     */
    public $plugin_path;

    /**
     * Plugin url.
     *
     * @var string
     */
    public $plugin_url;

    /**
     * Plugin basename.
     *
     * @var string
     */
    public $plugin_basename;

    /**
     * Plugin option keys.
     *
     * @var array|null
     */
    protected $plugin_options = null;

    public const PLUGIN_NAME = 's123-invoices';

    public function __construct() {
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
        $this->plugin_basename = plugin_basename(dirname(__FILE__, 3)) . '/s123-invoices.php';
    }

    public function s123_get_option($key, $default = null)
    {
        $options = $this->s123_get_options();
        return $options[$key] ?? $default;
    }

    public function s123_update_options($options)
    {
        update_option(self::PLUGIN_NAME, $options);
    }

    public function s123_get_options()
    {
        if (empty($this->plugin_options)) {
            $this->plugin_options = get_option(self::PLUGIN_NAME);
        }
        return is_array($this->plugin_options) ? $this->plugin_options : [];
    }

    /*
     * Get user versions (php, plugin, woo)
     */
    public function getVersions()
    {
        return [
            'versions' => [
                $this->versions()
            ]
        ];
    }

    public function versions()
    {
        return [
            'php' => phpversion(),
            'plugin' => get_file_data($this->plugin_path . 's123-invoices.php', ['Version' => 'Version', 'TextDomain' => 'Text Domain'], 'plugin')['Version'],
            'woocommerce' => get_file_data(plugin_dir_path(dirname(__FILE__, 3)) . 'woocommerce/woocommerce.php', ['Version' => 'Version', 'TextDomain' => 'Text Domain'], 'plugin')['Version'],
        ];
    }
}