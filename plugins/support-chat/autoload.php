<?php
spl_autoload_register('wp_saio_autoloader');
function wp_saio_autoloader($class_name)
{
    if (false !== strpos($class_name, 'WpSaio') ) {
        $classes_dir = WP_SAIO_DIR . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
        $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.class.php';
        require_once $classes_dir . $class_file;
    }
}
