<?php

if (!function_exists('psn_option_get')) {
    /**
     * Get the value of an psn option.
     *
     * @since 1.9.9
     * @package Options
     *
     * @param string $key The option key
     * @param mixed $default Default value to return if option is empty. Default: null
     * @return mixed
     */
    function psn_option_get($key, $default = null) {

        try {
            if (psn_pm()->hasOption($key)) {
                return IfwPsn_Wp_Plugin_Manager::getInstance('Psn')->getOption($key, $default);
            }
        } catch (IfwPsn_Wp_Plugin_Exception $e) {
            // error in pm init
            // return $default in case of error
        } catch (Exception $e) {
            // return $default in case of error
        }
        return $default;
    }
}

if (!function_exists('psn_option_is')) {
    /**
     * Checks if an option has a positive value, what means activated in case of a checkbox.
     *
     * @since 1.9.9
     * @package Options
     *
     * @param string $key The option key
     * @return bool
     */
    function psn_option_is($key) {
        $result = psn_option_get($key);
        return !empty($result);
    }
}

if (!function_exists('psn_option_is_empty')) {
    /**
     * Checks whether an option has an empty value.
     *
     * @since 1.9.9
     * @package Options
     *
     * @param string $key The option key
     * @return bool
     */
    function psn_option_is_empty($key) {
        $result = psn_option_get($key);
        return empty($result);
    }
}

if (!function_exists('psn_option_is_not_empty')) {
    /**
     * Checks whether an option has no empty value.
     *
     * @since 1.8.0
     * @package Options
     *
     * @param string $key The option key
     * @return bool
     */
    function psn_option_is_not_empty($key) {
        return !psn_option_is_empty($key);
    }
}