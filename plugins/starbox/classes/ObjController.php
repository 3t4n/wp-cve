<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<?php

/**
 * The class creates object for plugin classes
 */
class ABH_Classes_ObjController {

    /** @var array of instances */
    public static $instances;

    /** @var array from core config */
    public static $config;

    /**
     * Get the instance of the specified class
     *
     * @param string $className
     * @param bool $core TRUE is the class is a core class or FALSE if it is from classes directory
     *
     * @return object of the class|false
     */
    public static function getController($className) {
        if ($class = self::getClassPath($className)) {
            if (!isset(self::$instances[$className])) {
                /* check if class is already defined */
                if (!class_exists($className)) {
                    self::includeController($class['dir'], $class['name']);
                    self::$instances[$className] = new $className;
                    return self::$instances[$className];
                }
            }
            else
                return self::$instances[$className];
        }
        return false;
    }

    private static function includeController($classDir, $className) {

        if (file_exists($classDir . $className . '.php'))
            try {
                include_once($classDir . $className . '.php');
            } catch (Exception $e) {
                echo 'Controller Error: ' . $e->getMessage();
            }
    }

    /**
     * Get the instance of the specified model class
     *
     * @param string $className
     *
     * @return object of the class
     */
    public static function getModel($className) {
        if ($class = self::getClassPath($className)) {
            //set the model name for this class
            $className = _ABH_NAMESPACE_ . '_Models_' . $class['name'];

            if (!isset(self::$instances[$className])) {
                /* if $core == true then call the class from core directory */
                self::includeModel(_ABH_MODEL_DIR_, $class['name']);

                //echo $className . '<br />';
                if (class_exists($className)) {
                    self::$instances[$className] = new $className;
                    return self::$instances[$className];
                }
            }
            else
                return self::$instances[$className];
        }
        return;
    }

    private static function includeModel($classDir, $className) {

        /* check if class is already defined */
        if (file_exists($classDir . $className . '.php'))
            try {
                include_once($classDir . $className . '.php');
            } catch (Exception $e) {
                echo 'Model Error: ' . $e->getMessage();
            }
    }

    /**
     * Get the instance of the specified block from core directory
     *
     * @param string $className
     *
     * @return object of the class
     */
    public static function getBlock($className) {
        if ($class = self::getClassPath($className)) {
            $className = _ABH_NAMESPACE_ . '_Core_' . $class['name'];

            //set the model name for this class
            if (!isset(self::$instances[$className])) {

                /* if $core == true then call the class from core directory */
                self::includeBlock(_ABH_CORE_DIR_, $class['name']);

                if (class_exists($className)) {
                    self::$instances[$className] = new $className;
                    return self::$instances[$className];
                }
                else
                    exit("Block error: Can't call $className class");
            }
            else
                return self::$instances[$className];
        }
        return;
    }

    private static function includeBlock($classDir, $className) {

        if (file_exists($classDir . $className . '.php'))
            try {
                require_once($classDir . $className . '.php');
            } catch (Exception $e) {
                echo 'Model Error: ' . $e->getMessage();
            }
    }

    /**
     * Get all core classes from config.xml in core directory
     *
     * @param string $for
     */
    public function getBlocks($for) {
        /* if config allready in cache */
        if (!isset(self::$config)) {
            $config_file = _ABH_ROOT_DIR_ . 'config.json';
            if (!file_exists($config_file))
                return;

            /* load configuration blocks data from core config files */
            self::$config = json_decode(file_get_contents($config_file), 1);
        }
        //print_r(self::$config);
        if (is_array(self::$config))
            foreach (self::$config['blocks']['block'] as $block) {
                if (isset($block['active']) && $block['active'] == 1)
                    if (isset($block['controllers']['controller']))
                        if (!is_array($block['controllers']['controller'])) {
                            /* if the block should load for the current controller */
                            if ($for == $block['controllers']['controller']) {
                                ABH_Classes_ObjController::getBlock($block['name'])->init();
                            }
                        } else {
                            foreach ($block['controllers']['controller'] as $controller) {
                                /* if the block should load for the current controller */
                                if ($for == $controller) {
                                    ABH_Classes_ObjController::getBlock($block['name'])->init();
                                }
                            }
                        }
            }
    }

    /**
     * Check if the class is correctly set
     *
     * @param string $className
     * @return boolean
     */
    private static function checkClassPath($className) {
        $path = preg_split('/[_]+/', $className);
        if (is_array($path) && count($path) > 1) {
            if (in_array(_ABH_NAMESPACE_, $path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the path of the class and name of the class
     *
     * @param string $className
     * @return array | boolean
     * array(
     * dir - absolute path of the class
     * name - the name of the file
     * }
     */
    public static function getClassPath($className) {
        $path = array();
        $dir = '';

        if (self::checkClassPath($className)) {
            $path = preg_split('/[_]+/', $className);
            for ($i = 1; $i < sizeof($path) - 1; $i++)
                $dir .= strtolower($path[$i]) . '/';

            return array('dir' => _ABH_ROOT_DIR_ . '/' . $dir,
                'name' => $path[sizeof($path) - 1]);
        }
        return false;
    }

}