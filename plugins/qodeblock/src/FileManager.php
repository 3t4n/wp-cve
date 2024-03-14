<?php

namespace WordressLaravel\Wp;

class FileManager {

    /**
     * @var string
     */
    private $mainFile;

    /**
     * @var string
     */
    private $pluginDirectory;

    /**
     * @var string
     */
    private $pluginName;

    /**
     * @var string
     */
    private $pluginUrl;

    /**
     * @var string
     */
    private $themeDirectory;

    /**
     * PluginManager constructor.
     *
     * @param string $mainFile
     * @param string|null $themeDirectory
     */
    public function __construct($mainFile, $themeDirectory = NULL) {
        $this->mainFile = $mainFile;
        $this->pluginDirectory = plugin_dir_path($this->mainFile);
        $this->pluginName = basename($this->pluginDirectory);
        $this->themeDirectory = $themeDirectory ?: $this->pluginName;
        $this->pluginUrl = plugin_dir_url($this->get_main_file());
    }

    /**
     * @return string
     */
    public function getPluginDirectory() {
        return $this->pluginDirectory;
    }

    /**
     * @return string
     */
    public function getPluginName() {
        return $this->pluginName;
    }

    /**
     * @return string
     */
    public function get_main_file() {
        return $this->mainFile;
    }

    /**
     * @return string
     */
    public function get_plugin_url() {
        return $this->pluginUrl;
    }

    /**
     * @param string $__template
     * @param array $__variables
     */
    public function include_template( $__template, array $__variables = array() ) {
        if ($__template = $this->locate_template($__template)) {
            extract($__variables);
            include $__template;
        }
    }

    /**
     * @param string $template
     * @param array $variables
     *
     * @return string
     */
    public function renderTemplate( $template, array $variables = array() ) {
        ob_start();
        $this->include_template($template, $variables);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * @param string $file
     *
     * @return string
     */
    public function locateAsset($file) {
        return $this->pluginUrl . 'assets/' . $file;
    }

    /**
     * @param string $template
     *
     * @return string
     */
    public function locate_template($template) {
        if (strpos($template, 'frontend/') === 0) {
            $frontendTemplate = str_replace('frontend/', '', $template);
            if ($file = locate_template($this->themeDirectory . '/' . $frontendTemplate)) {
                return $file;
            }
        }
        return $this->pluginDirectory . 'views/' . $template;
    }
}