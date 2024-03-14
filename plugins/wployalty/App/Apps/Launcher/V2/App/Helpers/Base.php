<?php
/**
 * @author      Wployalty (Ilaiyaraja)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wll\V2\App\Helpers;

defined('ABSPATH') or die();

class Base
{
    public function opt($key, $default, $settings_type)
    {
        $settings_helper = new \Wll\V2\App\Helpers\Settings();
        $saved_data = $settings_helper->getSavedSettings($settings_type); //getting saved data with type design,content,launcher
        if (strpos($key, ".") !== false) {
            $identifiers = explode(".", $key);//splitting keys for sub array values
	        $value = $this->getOptValue($saved_data, $identifiers);
        }
        $value = (isset($value) && !empty($value)) ? $value : $default;
        return apply_filters('wlr_launcher_option_setting', $value, $key);
    }

    function getOptValue($data, $identifiers)
    {
        $identifier = array_shift($identifiers); //shifting first key from array
        if (is_object($data) && isset($data->$identifier) && is_object($data->$identifier)) {
            $value = $this->getOptValue($data->$identifier, $identifiers);
        } elseif (is_object($data) && isset($data->$identifier)) {
            $value = !empty($data->$identifier) ? $data->$identifier : '';
        } elseif (is_array($data) && isset($data[$identifier]) && is_array($data[$identifier])) {
            $value = empty($identifiers) ? $data[$identifier] : $this->getOptValue($data[$identifier], $identifiers);
        } else {
	        $value = isset($data[$identifier]) ? $data[$identifier] : '';
        }
        return $value;
    }

}