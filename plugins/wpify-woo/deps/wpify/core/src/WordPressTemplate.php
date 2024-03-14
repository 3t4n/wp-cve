<?php

namespace WpifyWooDeps\Wpify\Core;

use WpifyWooDeps\Wpify\Core\Abstracts\AbstractComponent;
use WpifyWooDeps\Wpify\Core\Interfaces\TemplateInterface;
class WordPressTemplate extends AbstractComponent implements TemplateInterface
{
    /**
     * Path to the plugin dir
     * @var string
     */
    private $plugin_dir;
    /**
     * Setups the templates
     * @return void
     */
    public function setup()
    {
        $this->plugin_dir = $this->plugin->get_plugin_dir();
    }
    /**
     * Renders the template and prints the result.
     *
     * @param string      $slug The slug name for the generic template.
     * @param string|null $name The name of the specialised template.
     * @param array       $args Additional arguments passed to the template.
     */
    public function print(string $slug, string $name = null, array $args = array()) : void
    {
        echo $this->render($slug, $name, $args);
        // phpcs:ignore
    }
    /**
     * Renders the template and returns the result.
     *
     * @param string      $slug The slug name for the generic template.
     * @param string|null $name The name of the specialised template.
     * @param array       $args Additional arguments passed to the template.
     *
     * @return string
     */
    public function render(string $slug, string $name = null, array $args = array()) : string
    {
        $templates_folder = $this->get_templates_folder();
        $templates = array();
        if (!empty($name)) {
            $templates[] = $templates_folder . $slug . '-' . $name . '.php';
        }
        $templates[] = $templates_folder . $slug . '.php';
        foreach ($templates as $template) {
            if (\file_exists($template)) {
                \ob_start();
                load_template($template, \false, $args);
                return \ob_get_clean();
            }
        }
        \ob_start();
        get_template_part($slug, $name, $args);
        return \ob_get_clean();
    }
    public function get_templates_folder()
    {
        return apply_filters('wpify_plugin_templates_dir', $this->plugin_dir . '/templates/');
    }
    /**
     * @return string
     */
    public function get_plugin_dir()
    {
        return $this->plugin_dir;
    }
}
