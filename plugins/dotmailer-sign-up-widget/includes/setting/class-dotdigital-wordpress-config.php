<?php

/**
 * The config provider class.
 *
 * This class provides the default config values and paths.
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Includes\Setting;

class Dotdigital_WordPress_Config
{
    public const DEFAULT_TAB = 'about';
    public const API_ENDPOINT = 'https://r1-api.dotdigital.com';
    public const SETTING_CREDENTIALS_PATH_USERNAME = 'dm_API_username';
    public const SETTING_CREDENTIALS_PATH_PASSWORD = 'dm_API_password';
    public const SETTING_CREDENTIALS_PATH = 'dm_API_credentials';
    public const SETTING_LISTS_PATH = 'dm_API_address_books';
    public const SETTING_DATAFIELDS_PATH = 'dm_API_data_fields';
    public const SETTING_MESSAGES_PATH = 'dm_API_messages';
    public const SETTING_REDIRECTS_PATH = 'dm_redirections';
    public const SETTING_INTEGRATION_INSIGHTS = 'dotdigital_for_wordpress_integration_insights';
    /**
     * Get the settings value.
     *
     * @param string     $path
     * @param array      $path_array
     * @param array|null $current_option_dimension
     *
     * @return mixed
     */
    public static function get_option(string $path, array &$path_array = array(), array $current_option_dimension = null)
    {
        $form_path_array = \preg_split('/[\\[\\]]/', $path, -1, \PREG_SPLIT_NO_EMPTY);
        if (\count($form_path_array) > 1) {
            if (\is_null($current_option_dimension)) {
                $current_option_dimension = get_option(\array_shift($form_path_array), array());
            }
            if (\count($form_path_array) > 1) {
                $key = \array_shift($form_path_array);
                if (!\array_key_exists($key, $current_option_dimension)) {
                    return \false;
                }
                $current_option_dimension = $current_option_dimension[$key];
                return static::get_option($path, $form_path_array, $current_option_dimension);
            }
            $option_value = $form_path_array[0];
            if (\is_array($current_option_dimension) && \array_key_exists($option_value, $current_option_dimension)) {
                return $current_option_dimension[$option_value];
            }
            return \false;
        }
        return get_option($path);
    }
}
