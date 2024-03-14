<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSincluder {

    function __construct() {
        
    }

    /*
     * Includes files
     */

    public static function include_file($filename, $module_name = null) {
        if ($module_name != null) {
            if (file_exists(JSJOBS_PLUGIN_PATH . 'modules/' . $module_name . '/tmpl/' . $filename . '.inc.php')) {
                require_once(JSJOBS_PLUGIN_PATH . 'modules/' . $module_name . '/tmpl/' . $filename . '.inc.php');
            }
            if (locate_template('js-jobs/' . $module_name . '-' . $filename . '.php', 1, 1)) {
                return;
            // } elseif (locate_template($module_name . '-' . $filename . '.php', 1, 1)) { // to add layout in root template directory
            //     return;
            } else {
                include_once JSJOBS_PLUGIN_PATH . 'modules/' . $module_name . '/tmpl/' . $filename . '.php';
            }
        } else {
            include_once JSJOBS_PLUGIN_PATH . 'modules/' . $filename . '/controller.php';
        }
        return;
    }

    /*
     * Static function to handle the page slugs
     */

    public static function include_slug($page_slug) {
        include_once JSJOBS_PLUGIN_PATH . 'modules/js-jobs-controller.php';
    }

    /*
     * Static function for the model object
     */

    public static function getJSModel($modelname) {
        include_once JSJOBS_PLUGIN_PATH . 'modules/' . $modelname . '/model.php';
        $classname = "JSJOBS" . $modelname . 'Model';
        $obj = new $classname();
        return $obj;
    }

    /*
     * Static function for the classes objects
     */

    public static function getObjectClass($classname) {
        include_once JSJOBS_PLUGIN_PATH . 'includes/classes/' . $classname . '.php';
        $classname = 'JSJOBS'.$classname;
        $obj = new $classname();
        return $obj;
    }

    /*
     * Static function for the classes not objects
     */

    public static function getClassesInclude($classname) {
        include_once JSJOBS_PLUGIN_PATH . 'includes/classes/' . $classname . '.php';
    }

    /*
     * Static function for the table object
     */

    public static function getJSTable($tableclass) {
        require_once JSJOBS_PLUGIN_PATH . 'includes/tables/table.php';
        include_once JSJOBS_PLUGIN_PATH . 'includes/tables/' . $tableclass . '.php';
        $classname = "JSJOBS" . $tableclass . 'Table';
        $obj = new $classname();
        return $obj;
    }

    /*
     * Static function for the controller object
     */

    public static function getJSController($controllername) {
        include_once JSJOBS_PLUGIN_PATH . 'modules/' . $controllername . '/controller.php';
        $classname = "JSJOBS" . $controllername . "Controller";
        $obj = new $classname();
        return $obj;
    }

}

$includer = new JSJOBSincluder();
if (!defined('JCONSTS'))
    define('JCONSTS', 'http://www.joomsky.com/index.php?option=com_jsproductlisting&task=aagjcwp');

if (!defined('JCONSTV'))
    define('JCONSTV', 'https://setup.joomsky.com/jsjobswp/pro/index.php');
?>
