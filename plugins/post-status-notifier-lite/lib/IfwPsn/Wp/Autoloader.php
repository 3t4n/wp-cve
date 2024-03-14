<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * Autoloader
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Autoloader.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 */
class IfwPsn_Wp_Autoloader
{
    /**
     * Lib dir store
     * @var array
     */
    protected static $_libDir = [];

    /**
     * @var array
     */
    protected static $_modules = [];

    /**
     * @var array
     */
    protected static $_isLibDir = [];

    /**
     * @var string[]
     */
    protected static $_ownPluginNs = ['Asa2', 'Psn'];



    /**
     * Initializes the autoloader
     * @param string $libDir
     * @return bool
     */
    public static function init($libDir)
    {
        if (!in_array($libDir, self::$_libDir) && is_dir($libDir)) {
            self::$_libDir[] = $libDir;
        }
        return spl_autoload_register(array('IfwPsn_Wp_Autoloader', 'autoload'));
    }
    
    /**
     * Loads a class file
     * @param string $className
     * @return bool
     */
    public static function autoload($className)
    {
        $result = false;
        $class_path = self::getClassPath($className);

        if ($class_path !== false) {
            if (!class_exists($className)) {
                $result = include_once $class_path;
            }
        } elseif (($class_path = self::getNamespacePath($className)) !== false) {
            if (!class_exists($className)) {
                $result = include_once $class_path;
            }
        }

        return $result !== false;
    }
    
    /**
     * Gets the path of a class
     * @param string $className
     * @return string|false
     */
    public static function getClassPath($className)
    {
        foreach (self::$_libDir as $libDir) {
            $path = self::_getPath($className, $libDir);
            if ($path !== null) {
                return $path;
            }
        }

        // search in modules
        if (count(self::$_modules) > 0) {
            foreach(self::$_modules as $prefix => $libDir) {
                if (strpos($className, $prefix) === 0) {
                    $path = self::_getPath(str_replace($prefix, '', $className), $libDir);
                    if ($path !== null) {
                        return $path;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param $class
     * @return bool|string
     */
    public static function getNamespacePath($class)
    {
        $classPath = null;
        $pluginPrefix = null;
        $ds = DIRECTORY_SEPARATOR;

        $pos = strpos($class, '\\');
        if ($pos) {
            $ns = substr($class, 0, $pos);

            if ($ns === 'PhPease') { // todo: better solution
                foreach (self::$_ownPluginNs as $plAbbr) {
                    if (is_dir(self::getLibRoot($plAbbr) . 'PhPease')) {
                        $ns = $plAbbr;
                        break;
                    }
                }
            }

            $libRoot = self::getLibRoot($ns);

            if ($libRoot !== null) {
                if (!array_key_exists($ns, self::$_isLibDir)) {
                    self::$_isLibDir[$ns] = is_dir($libRoot . $ns);
                }
                if (self::$_isLibDir[$ns]) {

                    $className = str_replace('\\', DIRECTORY_SEPARATOR, $class);

                    if (self::isOnwPluginNs($ns) && strpos($className, 'Module') !== false && strpos($className, 'Modules' . $ds) === false) {

                        $modClassName = str_replace($ns . $ds . 'Module' . $ds, '', $className);

                        $pos = strpos($modClassName, $ds);
                        if ($pos !== false) {
                            $modClassName = substr_replace($modClassName, $ds . 'lib' . $ds, $pos, strlen($ds));
                        }

                        if (defined(strtoupper($ns) . '_PLUGIN_ROOT')) {
                            $classPath = constant(strtoupper($ns) . '_PLUGIN_ROOT');
                        } else {
                            $classPath = IFW_PLUGIN_ROOT;
                        }

                        $classPath .= 'modules' . DIRECTORY_SEPARATOR . $modClassName . '.php';

                    } else {

                        $classPath = $libRoot . $className . '.php';
                    }

                }
            }
        }

        if ($classPath !== null && file_exists($classPath)) {
            return $classPath;
        }
        return false;
    }

    /**
     * @param $ns
     * @return mixed|string|null
     */
    protected static function getLibRoot($ns)
    {
        $result = IFW_PSN_LIB_ROOT;

        if (self::isOnwPluginNs($ns) && defined(strtoupper($ns) . '_LIB_ROOT')) {
            $result = constant(strtoupper($ns) . '_LIB_ROOT');
        }

        return $result;
    }

    /**
     * @param $ns
     * @return bool
     */
    public static function isOnwPluginNs($ns)
    {
        return in_array($ns, self::$_ownPluginNs);
    }

    /**
     * @param $className
     * @param $dir
     * @return null|string
     */
    protected static function _getPath($className, $dir)
    {
        $path = $dir . implode(DIRECTORY_SEPARATOR, explode('_', $className)) . '.php';
        if (is_readable($path)) {
            return $path;
        }
        return null;
    }

    /**
     * @param $classNamePrefix
     * @param $libDir
     */
    public static function registerModule($classNamePrefix, $libDir)
    {
        if (!isset(self::$_modules[$classNamePrefix])) {
            self::$_modules[$classNamePrefix] = $libDir;
        }
    }

    /**
     * @return array
     */
    public static function getAllRegisteredAutoloadFunctions()
    {
        return spl_autoload_functions();
    }
}