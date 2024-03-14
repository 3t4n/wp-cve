<?php

namespace WpLHLAdminUi\Settings;

class SettingsLink {

    private $plugin_name;
    private $text_domain = "";
    private $path_to_settings = "#";

    public function __construct($plugin_name, $text_domain, $path_to_settings) {
        $this->plugin_name = $plugin_name;
        $this->text_domain = $text_domain;
        $this->path_to_settings = $path_to_settings;
    }

    /**
     * Add a settings link to the Plugins page
     *
     * @param array $links
     * @param string $file
     *
     * @return array
     */
    public function add_settings_link($links, $file) {

        if (is_null($this->plugin_name)) {
            $this->plugin_name = plugin_basename(__FILE__);
        }

        $file_name = strtok($file, '/');
        if ($file_name == $this->plugin_name) {
            $settings_link = '<a href="' . $this->path_to_settings . '">' . esc_html__('Settings', $this->text_domain) . '</a>';
            array_unshift($links, $settings_link);
        }

        return $links;
    }
}
