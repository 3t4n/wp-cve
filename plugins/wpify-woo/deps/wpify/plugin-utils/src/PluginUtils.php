<?php

namespace WpifyWooDeps\Wpify\PluginUtils;

use WP_Filesystem_Base;
class PluginUtils
{
    private $plugin_file;
    private $plugin_info;
    private $plugin_basename;
    private $plugin_slug;
    private $text_domain;
    /**
     * AbstractPlugin constructor.
     *
     * @param string $plugin_file
     */
    public function __construct(string $plugin_file)
    {
        $plugin_file_data = array('version' => 'Version', 'name' => 'Plugin Name', 'description' => 'Description', 'text_domain' => 'Text Domain', 'domain_path' => 'Domain Path');
        $this->plugin_file = $plugin_file;
        $this->plugin_info = get_file_data($this->plugin_file, $plugin_file_data);
        $this->plugin_basename = plugin_basename($this->plugin_file);
        $this->plugin_slug = \dirname($this->plugin_basename);
        $this->text_domain = $this->plugin_info['text_domain'];
    }
    /**
     * @param string $file
     *
     * @return string
     */
    public function get_plugin_path(string $file = '') : string
    {
        return wp_normalize_path(plugin_dir_path($this->plugin_file) . $file);
    }
    /**
     * @param string $file
     *
     * @return string
     */
    public function get_plugin_url(string $file = '') : string
    {
        return plugins_url($file, $this->plugin_file);
    }
    /**
     * @param string $file
     *
     * @return string
     */
    public function get_theme_path(string $file = '') : string
    {
        return wp_normalize_path(get_template_directory() . '/' . $file);
    }
    /**
     * @param string $file
     *
     * @return string
     */
    public function get_theme_url(string $file = '') : string
    {
        return get_template_directory_uri() . '/' . \ltrim($file, '/');
    }
    /**
     * @return array
     */
    public function get_plugin_info() : array
    {
        return $this->plugin_info;
    }
    /**
     * @return string|null
     */
    public function get_plugin_version() : ?string
    {
        return $this->plugin_info['version'] ?? null;
    }
    /**
     * @return string|null
     */
    public function get_plugin_name() : ?string
    {
        return $this->plugin_info['name'] ?? null;
    }
    /**
     * @return string|null
     */
    public function get_plugin_description() : ?string
    {
        return $this->plugin_info['description'] ?? null;
    }
    /**
     * @return string
     */
    public function get_plugin_basename() : string
    {
        return $this->plugin_basename;
    }
    /**
     * @return string
     */
    public function get_plugin_slug() : string
    {
        return $this->plugin_slug;
    }
    /**
     * @param array $args
     *
     * @return WP_Filesystem_Base
     */
    public function get_filesystem(array $args = array())
    {
        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once ABSPATH . '/wp-admin/includes/file.php';
            WP_Filesystem($args);
        }
        return $wp_filesystem;
    }
    public function get_text_domain() : string
    {
        return $this->text_domain;
    }
}
