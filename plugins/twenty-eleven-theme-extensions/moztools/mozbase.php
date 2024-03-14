<?php
/*  Copyright 2011  Michael J. Walker (email: mike@moztools.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include_once 'mozstore.php';
/**
 * Base class for all plugins.
 *
 * @author Michael J Walker
 */
class MozBaseClass_MozTheme2011 {
    protected $ajaxRequest;   // True if this is an Ajax request
    protected $pluginDir;     // Home directory of the plugin
    protected $pluginUrl;     // Home URL of the plugin
    protected $pluginFile;    // Filename of the plugin
    protected $pluginOptions; // Name of the options key in database
    
    private $store;        // Storage object for options
    private $options;      // Retrieved options (if set)

    /**
     * Main hook for initializing the plugin class. Tells the hook to
     * call the static method "create" which should then instantiate
     * the plugin class. This allows the plugin to delay instantiation
     * until it is really needed.
     * 
     * @param string $className name of the class to instantiate 
     * @param string $hook WordPress hook to trigger the class instantiation
     */
    public static function registerPlugin($className, $hook = 'wp') {
        if (!empty($className) && !is_admin()) {
            add_action($hook, $className.'::create');
        }
    }

    /**
     * Main hook for initializing the plugin's administration class. Tells the hook to
     * call the static method "create" which should then instantiate
     * the plugin class. This allows the plugin to delay instantiation
     * until it is really needed.
     * 
     * Note: do not specify a hook that gets called any later than 'admin_menu' if 
     *       you are displaying an administration page, since MozAdminClass registers the
     *       next hook in sequence 'admin_ init' during instantiation to initiate the
     *       displaying of the admin page.
     * 
     * @param string $className name of the administration class to instantiate 
     * @param string $hook WordPress hook to trigger the class instantiation, defaults to 'admin_menu'
     */
    public static function registerPluginAdmin($className, $hook = 'admin_menu') {
        if (is_admin() && !defined('DOING_AJAX') && !empty($className)) {
            include_once 'mozadmin.php';
            add_action($hook, $className.'::create');
        }
    }
    
    /**
     * Main hook for initializing the plugin's ajax administration class. Tells the hook to
     * call the static method "create" which should then instantiate
     * the plugin class. This allows the plugin to delay instantiation
     * until it is really needed.
     *
     * @param string $className name of the ajax administration class to instantiate
     * @param string $hook WordPress hook to trigger the class instantiation, defaults to 'admin_init'
    */
    public static function registerPluginAjaxAdmin($className, $hook = 'admin_init') {
        if (is_admin() && defined('DOING_AJAX') && !empty($className)) {
            include_once 'mozadmin.php';
            add_action($hook, $className.'::create');
        }
    }
    
    public static function create() {
    }
    
    /**
     * Base class constructor.
     */
    protected function __construct() {
        $this->ajaxRequest = defined('DOING_AJAX');
        $path = strtr(dirname(__FILE__), '\\', '/');  // Must convert for Windows platform
        $path = substr($path, 0, strrpos($path, '/moztools')); // Must remove moztools subdirectory
        $this->pluginDir = $path;
        $dir = substr($path, strrpos($path, '/') + 1);
        $this->pluginUrl = WP_PLUGIN_URL.'/'.$dir;
        $this->pluginFile = $dir.'/'.$dir.'.php';
    }

    /**
     * Register one or more CSS stylesheet files for the plugin
     *
     * @param string/array $cssfiles single or multiple css files
     */
    protected function registerStyles($cssfiles) {
        if (!empty($cssfiles)) {
            $cssfiles = is_array($cssfiles) ? $cssfiles : array($cssfiles);
            foreach ($cssfiles as $file) {
                wp_register_style($file, $this->pluginUrl.'/'.$file.'.css');
                wp_enqueue_style($file);
            }
        }
    }
    
    protected function registerFilter($filter, $fname, $priority = 10, $count = 1) {
        add_filter($filter, array(&$this, $fname), $priority, $count);
    }

    protected function registerAction($action, $fname, $priority = 10, $count = 1) {
        add_action($action, array(&$this, $fname), $priority, $count);
    }

    /**
     * Register the specified version of JQuery from Google's servers.
     * @param string $version version of JQuery to load
     */
    protected function registerJQuery($version = '1.6.2') {
        wp_deregister_script('jquery');
        wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/'.$version.'/jquery.min.js', false);
        wp_enqueue_script('jquery');
    }

    /**
     * Register the specified version of JQueryUI from Google's servers.
     * @param string $version version of JQueryUI to load
     */
    protected function registerJQueryUI($version = '1.8.15') {
        wp_deregister_script('jquery-ui');
        wp_register_script('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/'.$version.'/jquery-ui.min.js', array('jquery'));
        wp_enqueue_script('jquery-ui');
    }

    /**
     * Register one or more Javascript files for the plugin
     *
     * @param string/array $jsfiles single or multiple Javascript files
     * @param string/array $dependencies single or multiple dependencies
     *
     * Note: if different scripts have different dependencies, call this
     * function once for each set of scripts with different dependencies.
     */
    protected function registerScripts($jsfiles, $dependencies) {
        if (!empty($jsfiles)) {
            $jsfiles = $this->toArray($jsfiles);
            $dependencies = $this->toArray($dependencies);
            foreach ($jsfiles as $file) {
                wp_register_script($file, $this->pluginUrl.'/'.$file.'.js', $dependencies);
                wp_enqueue_script($file);
            }
        }
    }

    /**
     * Hook into a shortcode
     *
     * @param string $shortcode name of the shortcode
     * @param string $function function to call when shortcode encountered
     */
    protected function addShortcode($shortcode, $function) {
        add_shortcode($shortcode, array(&$this, $function));
    }

    /**
     * Convert a scalar type into an array (but leave arrays alone).
     * @param any $obj object to wrap in array, if not already an array
     */
    protected function toArray($obj) {
        return is_array($obj) ? $obj : array($obj);
    }
    
    protected function fn($functionName) {
        return array(&$this, $functionName);
    }
    
    private function getStore() {
        if (!isset($this->store)) {
            $this->store = MozOptionsStore_MozTheme2011::initStore($this->pluginOptions);
        }
        return $this->store;
    }
    
    protected function getOptions() {
        if (!isset($this->options)) {
            $this->options = $this->getStore()->getItem();
        }
        return $this->options;
    }
    
    protected function updateOptions($item) {
        $this->getStore()->update($item);
        $this->options = $item;
    }
    
    protected function deleteOptions() {
        $this->getStore()->deleteAllItems();
        unset($this->options);
    }
    
    protected function getOption($option) {
        $options = $this->getOptions();
        return isset($options[$option]) ? $options[$option] : false;
    }
    
    protected function areOptionsSet($items) {
        $options = $this->getOptions();
        $array = array_intersect_key($options, $items);
        return count($array) == count($items);
    }
    
    protected function isOptionSet($option) {
        $options = $this->getOptions();
        return isset($options[$option]);
    }
}

if (!function_exists('_pr')) {
    function _pr($var, $prefix = '', $suffix = '') {
        global $user_ID;
        if (!empty($user_ID)) {
            echo $prefix.str_replace(chr(10), '<br>', print_r($var, true)).$suffix;
        }
    }
}