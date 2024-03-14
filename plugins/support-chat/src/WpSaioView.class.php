<?php
class WpSaioView
{
    protected static $dir = '';
    protected static $view_root_folder = '';

    public function __construct()
    {
        
    }
    public static function load($path, $args = array())
    {
        self::setDefaultValues();

        if (count($args) > 0) {
            extract($args);
        }
        $path = self::$dir . '/' . self::$view_root_folder . '/' . str_replace('.', '/', $path) . '.php';
        ob_start();
        require $path;
        return ob_get_clean();
    }
    protected static function setDefaultValues()
    {
        self::$dir = WP_SAIO_DIR;
        self::$view_root_folder = 'views';
    }
}
