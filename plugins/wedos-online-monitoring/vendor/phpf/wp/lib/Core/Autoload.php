<?php
namespace PHPF\WP\Core;

/**
 * Classes autoloading
 *
 * @author  Petr Stastny <petr@stastny.eu>
 * @license GPLv3
 */
final class Autoload
{
    /**
     * Chars allowed in class name
     */
    const CLASSNAME_CHARS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890_\\';

    /**
     * Array of folder with classes for autoloading
     *
     * $path => $prefix (or NULL)
     *
     * @var array
     */
    private static $paths = [];


    /**
     * Autoloading initialization
     *
     * @return void
     */
    public static function init()
    {
        self::$paths[__DIR__.'/..'] = 'PHPF\\WP';

        // register PHP autoload function
        spl_autoload_register(__CLASS__.'::autoLoad');
    }


    /**
     * Add directory where to look up for classes
     *
     * @param string $path full path
     * @param string|null $prefix class path prefix
     * @return void
     */
    public static function addPath($path, $prefix = null)
    {
        self::$paths[$path] = $prefix;
    }


    /**
     * Convert full class name to path
     *
     * Based od PSR-0 standard (http://www.php-fig.org/psr/psr-0/)
     *
     * @param string $className class name
     * @return string
     */
    public static function classToPath($className)
    {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        $lastNsPos = strrpos($className, '\\');

        if ($lastNsPos) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }

        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        return $fileName;
    }


    /**
     * Lookup for class file by class name
     *
     * When found, class file path and name are returned. Otherwise returns false.
     *
     * @param string $className class name
     * @return string|bool
     */
    public static function classLookup($className)
    {
        // validate class name syntax
        if (!self::validateClassName($className)) {
            throw new \Exception('Invalid class name: '.$className);
        }

        foreach (self::$paths as $path => $prefix) {

            if ($prefix) {
                $prefixLen = strlen($prefix);
                if (substr($className, 0, $prefixLen + 1) != $prefix.'\\') {
                    // prefix does not match
                    continue;
                }

                $classNameLookup = substr($className, $prefixLen + 1);

            } else {
                $classNameLookup = $className;
            }

            // translation fully qualified class name -> path (PSR-0)
            $file = $path.'/'.self::classToPath($classNameLookup);

            if (file_exists($file)) {
                return $file;
            }
        }

        return false;
    }


    /**
     * Perform autoload
     *
     * This method is called by PHP when trying to use unknown class.
     *
     * @param string $className class name
     * @return void
     */
    public static function autoLoad($className)
    {
        $classFile = self::classLookup($className);

        if ($classFile) {
            // class file found, include it
            require_once $classFile;
        }
    }


    /**
     * Checks whether string is valid class name
     *
     * @param string $className
     * @return bool
     */
    public static function validateClassName($className)
    {
        return Validator::allowedChars($className, self::CLASSNAME_CHARS);
    }
}
