<?php

if (!defined('ABSPATH')) {
    exit;
}


class SS_Shipping_Autoloader
{

    /**
     * Path to the includes directory.
     *
     * @var string
     */
    private $include_path = '';

    /**
     * The Constructor.
     */
    public function __construct()
    {
        spl_autoload_register(array($this, 'autoload'));

        $this->include_path = untrailingslashit(plugin_dir_path(SS_SHIPPING_PLUGIN_FILE)) . '/includes/';
    }

    /**
     * Take a class name and turn it into a file name.
     *
     * @param  string $class
     * @return string
     */
    private function get_file_name_from_class($class)
    {
        return 'class-' . str_replace('_', '-', $class) . '.php';
    }

    /**
     * Include a class file.
     *
     * @param  string $path
     * @return bool successful or not
     */
    private function load_file($path)
    {
        if ($path && is_readable($path)) {
            include_once($path);
            return true;
        }
        return false;
    }

    /**
     * Auto-load WC classes on demand to reduce memory consumption.
     *
     * @param string $class
     */
    public function autoload($class)
    {
        $class = strtolower($class);
        $file = $this->get_file_name_from_class($class);
        $path = '';

        if (strpos($class, 'ss_api') !== false) {
            $path = $this->include_path . 'smart-send-api/';
        }

        if (strpos($class, 'frontend') !== false) {
            $path = $this->include_path . 'frontend/';
        }

        if (empty($path) || !$this->load_file($path . $file)) {
            $this->load_file($this->include_path . $file);
        }
    }
}

new SS_Shipping_Autoloader();
