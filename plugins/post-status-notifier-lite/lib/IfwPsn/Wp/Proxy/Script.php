<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * Wp Script Proxy
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Script.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 */
class IfwPsn_Wp_Proxy_Script
{
    /**
     * Container for scripts to enqueue
     * @var array
     */
    private static $_scripts = array();

    /**
     * Container for admin scripts to enqueue
     * @var array
     */
    private static $_scriptsAdmin = array();

    /**
     * Container for localize data
     * @var array
     */
    private static $_localize = array();

    /**
     * If enqueue function is set
     * @var bool
     */
    private static $_enqueueSet = false;

    /**
     * If admin enqueue function is set
     * @var bool
     */
    private static $_enqueueAdminSet = false;



    /**
     * @see wp_register_script() for parameter information
     */
    public static function register($handle, $src, $deps=array(), $ver=false, $in_footer=false)
    {
        wp_register_script($handle, $src, $deps, $ver, $in_footer);
    }

    /**
     * @see WP_Scripts::remove() wp_localize_script
     */
    public static function deregister($handle)
    {
        wp_deregister_script($handle);
    }

    /**
     * @see wp_enqueue_script() for parameter information
     */
    public static function enqueue($handle, $src=false, $deps=array(), $ver=false, $in_footer=false)
    {
        wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
    }

    /**
     * @param $handle
     */
    public static function dequeue($handle)
    {
        wp_dequeue_script($handle);
    }

    /**
     * Registers and enqueues a js file in one process
     * @param string $handle
     * @param bool|string $src
     * @param array $deps
     * @param bool $ver
     * @param bool $in_footer
     * @param array $localize
     * @return void
     */
    public static function load($handle, $src=false, $deps=array(), $ver=false, $in_footer=false, $localize=array())
    {
        if (!isset(self::$_scripts[$handle])) {
            self::$_scripts[$handle] = array(
                'src' => $src,
                'deps' => $deps,
                'ver' => $ver,
                'in_footer' => $in_footer
            );
        }

        if (is_array($localize) && !empty($localize)) {
            self::localize($handle, key($localize), array_values($localize));
        }

        if (self::$_enqueueSet == false) {
            IfwPsn_Wp_Proxy_Action::addEnqueueScripts(array('IfwPsn_Wp_Proxy_Script', '_enqueueScripts'));
            self::$_enqueueSet = true;
        }
    }

    /**
     * @param IfwPsn_Wp_Plugin_Manager $pm
     * @param $handle
     * @param bool $src
     * @param array $deps
     * @param bool|string $ver
     * @param bool $in_footer
     * @param array $localize
     */
    public static function loadMinimized(IfwPsn_Wp_Plugin_Manager $pm, $handle, $src=false, $deps=array(), $ver=false, $in_footer=false, $localize=array())
    {
        if ($pm->isProduction()) {
            $src = self::getMinimizedName($src);
        }
        self::load($handle, $src, $deps, $ver, $in_footer, $localize);
    }

    /**
     * @param $handle
     * @param bool $src
     * @param array $deps
     * @param bool|string $ver
     * @param bool $in_footer
     * @param array $localize
     * @return void
     */
    public static function loadAdmin($handle, $src=false, $deps=array(), $ver=false, $in_footer=false, $localize=array())
    {
        if (!isset(self::$_scriptsAdmin[$handle])) {
            self::$_scriptsAdmin[$handle] = array(
                'src' => $src,
                'deps' => $deps,
                'ver' => $ver,
                'in_footer' => $in_footer
            );
        }

        if (is_array($localize) && !empty($localize)) {
            self::localize($handle, key($localize), $localize[key($localize)]);
        }

        if (self::$_enqueueAdminSet == false) {
            IfwPsn_Wp_Proxy_Action::addAdminEnqueueScripts(array('IfwPsn_Wp_Proxy_Script', '_enqueueAdminScripts'));
            self::$_enqueueAdminSet = true;
        }
    }

    /**
     * @param IfwPsn_Wp_Plugin_Manager $pm
     * @param $handle
     * @param bool $src
     * @param array $deps
     * @param bool $ver
     * @param bool $in_footer
     * @param array $localize
     */
    public static function loadAdminMinimized(IfwPsn_Wp_Plugin_Manager $pm, $handle, $src=false, $deps=array(), $ver=false, $in_footer=false, $localize=array())
    {
        if ($pm->isProduction()) {
            $src = self::getMinimizedName($src);
        }
        if ($ver === false) {
            $ver = $pm->getEnv()->getVersion();
        }
        self::loadAdmin($handle, $src, $deps, $ver, $in_footer, $localize);
    }

    /**
     * @param IfwPsn_Wp_Plugin_Manager $pm
     * @param $handle
     * @param false $src
     * @param array $deps
     * @param false $ver
     * @param false $in_footer
     * @param array $localize
     */
    public static function loadAdminMinimizedAsModule(IfwPsn_Wp_Plugin_Manager $pm, $handle, $src=false, $deps=array(), $ver=false, $in_footer=false, $localize=array())
    {
        self::loadAdminMinimized($pm, $handle, $src, $deps, $ver, $in_footer, $localize);

        $outer_handle = $handle;
        add_filter('script_loader_tag', function ($tag, $handle, $src) use ($outer_handle) {
            // if not your script, do nothing and return original $tag
            if ( $outer_handle !== $handle ) {
                return $tag;
            }
            // change the script tag by adding type="module" and return it.
            $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
            return $tag;
        } , 10, 3);
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function getMinimizedName($name)
    {
        return str_replace('.js', '.min.js', $name);
    }

    /**
     * @see wp_localize_script() for parameter information
     */
    public static function localize($handle, $object_name, $l10n)
    {
        if (!isset(self::$_localize[$handle])) {
            self::$_localize[$handle] = array();
        }

        if (!isset(self::$_localize[$handle][$object_name])) {
            self::$_localize[$handle][$object_name] = $l10n;
        } else {
            self::$_localize[$handle][$object_name] = array_merge(self::$_localize[$handle][$object_name], $l10n);
        }
    }

    /**
     * Finally enqueues the script at the right moment (action)
     */
    public static function _enqueueScripts()
    {
        foreach (self::$_scripts as $handle => $data) {
            self::enqueue($handle, $data['src'], $data['deps'], $data['ver'], $data['in_footer']);
            if (isset(self::$_localize[$handle])) {
                foreach (self::$_localize[$handle] as $object_name => $l10n) {
                    if (isset($l10n[0]) && is_array($l10n[0])) {
                        $l10n = $l10n[0];
                    }
                    wp_localize_script($handle, $object_name, $l10n);
                }
            }
        }
    }

    /**
     * Finally enqueues the script at the right moment (action)
     */
    public static function _enqueueAdminScripts()
    {
        foreach (self::$_scriptsAdmin as $handle => $data) {
            self::enqueue($handle, $data['src'], $data['deps'], $data['ver'], $data['in_footer']);
            if (isset(self::$_localize[$handle])) {
                foreach (self::$_localize[$handle] as $object_name => $l10n) {
                    wp_localize_script($handle, $object_name, $l10n);
                }
            }
        }
    }

    /**
     * @param $component
     */
    public static function loadJqueryUiAdmin($component)
    {
        switch ($component) {
            case 'resizable':
                IfwPsn_Wp_Proxy_Script::loadAdmin('jquery-ui-resizable', 'jquery-ui-resizable');
                IfwPsn_Wp_Proxy_Style::loadAdmin('jquery-ui-css', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
                break;
        }
    }
}