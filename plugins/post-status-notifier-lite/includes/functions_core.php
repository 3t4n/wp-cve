<?php
if (!function_exists('psn_pm')) {
    /**
     * Shortcut to retrieve psn's plugin manager
     * @since 1.9.9
     * @ignore
     * @return IfwPsn_Wp_Plugin_Manager
     */
    function psn_pm() {
        if (class_exists('IfwPsn_Wp_Plugin_Manager')) {
            return IfwPsn_Wp_Plugin_Manager::getInstance('Psn');
        }
        return null;
    }
}

if (!function_exists('psn_config')) {
    /**
     * Shortcut to retrieve psn's config object
     * @since 1.9.9
     * @ignore
     * @return IfwPsn_Wp_Plugin_Config
     */
    function psn_config() {
        if (class_exists('IfwPsn_Wp_Plugin_Manager')) {
            return IfwPsn_Wp_Plugin_Manager::getInstance('Psn')->getConfig();
        }
        return null;
    }
}

if (!function_exists('psn_env')) {
    /**
     * Shortcut to retrieve psn's environment object
     *
     * @since 1.9.9
     * @ignore
     * @return IfwPsn_Wp_Env_Plugin
     */
    function psn_env() {
        if (class_exists('IfwPsn_Wp_Plugin_Manager')) {
            return IfwPsn_Wp_Plugin_Manager::getInstance('Psn')->getEnv();
        }
        return null;
    }
}

if (!function_exists('psn_pathinfo')) {
    /**
     * Shortcut to retrieve psn's pathinfo object
     *
     * @since 1.9.9
     * @ignore
     * @return IfwPsn_Wp_Pathinfo_Plugin
     */
    function psn_pathinfo() {
        if (class_exists('IfwPsn_Wp_Plugin_Manager')) {
            return IfwPsn_Wp_Plugin_Manager::getInstance('Psn')->getPathinfo();
        }
        return null;
    }
}
