<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Kirimemail Autoloader.
 *
 * @class        Ke_Autoloader
 * @version        1.0.0
 * @category    Class
 */
class Kemail_Wpform_Autoloader
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
        if (function_exists("__autoload")) {
            spl_autoload_register("__autoload");
        }

        spl_autoload_register(array($this, 'autoload'));

        $this->include_path = untrailingslashit(plugin_dir_path(KIRIMEMAIL_WPFORM_PLUGIN_FILE)) . '/includes/lib/';
    }

    /**
     * Take a class name and turn it into a file name.
     *
     * @param string $class
     * @return string
     */
    private function get_file_name_from_class($class)
    {
        return 'class-' . str_replace('_', '-', $class) . '.php';
    }

    /**
     * Include a class file.
     *
     * @param string $path
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
     * Auto-load kemail classes on demand to reduce memory consumption.
     *
     * @param string $class
     */
    public function autoload($class, $register = true)
    {
        $class = strtolower($class);

        if (0 !== strpos($class, 'kemail_')) {
            return;
        }

        $file = $this->get_file_name_from_class($class);
        $path = '';

        if (empty($path) || !$this->load_file($path . $file)) {
            $this->load_file($this->include_path . $file);
        }

        if ($register) {
            $class::register();
        }
    }
}
