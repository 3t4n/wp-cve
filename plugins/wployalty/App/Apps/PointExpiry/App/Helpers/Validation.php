<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlpe\App\Helpers;

use Valitron\Validator;
use Wlr\App\Helpers\Input;

defined('ABSPATH') or die();

class Validation
{

    static function validateCommonFields($post)
    {
        $settings_validator = new Validator($post);
        $settings_validator->stopOnFirstFail(false);
        Validator::addRule('search', array(__CLASS__, 'validateSearch'), __('Validation error', 'wp-loyalty-rules'));
        Validator::addRule('alphaNumWithUnderscore', array(__CLASS__, 'validateAlphaNumWithUnderscore'), __('Validation error', 'wp-loyalty-rules'));
        $settings_validator->rule('search',
            array(
                'search'
            )
        );
        $settings_validator->rule('alphaNumWithUnderscore',
            array(
                'sort_order'
            )
        );
        $settings_validator->rule('numeric',
            array(
                'per_page',
                'page_number',
            )
        );
        $settings_validator->rule('alpha',
            array(
                'sort_order_dir'
            )
        );

        if ($settings_validator->validate()) {
            return true;
        } else {
            return $settings_validator->errors();
        }
    }

    static function validateSettingsTab($post)
    {
        /*
         * 1. wlpr_enable_launcher
         * 2. wlpr_launcher_icon
         * 3. wlpr_launcher_text
         * 4. wlpr_launcher_button_width
         * */
        $settings_validator = new Validator($post);
        $labels_array_fields = array(
            'expire_email_after'
        );
        $this_field = __("This field", "wp-loyalty-rules");
        foreach ($labels_array_fields as $label) {
            $labels_array[$label] = $this_field;
        }
        $settings_validator->labels($labels_array);
        $settings_validator->stopOnFirstFail(false);
        $settings_validator->rule('numeric', array('expire_after'));
        $settings_validator->rule('required', array('expire_after'));
        $settings_validator->rule('min', array('expire_after'), 0);
        $settings_validator->rule('alpha', array('expire_period'));
        $input = new Input();
        $is_expiry_email_enable = $input->post_get('enable_expire_email', 0);
        if (!empty($is_expiry_email_enable)) {
            Validator::addRule('expire_email_after', array(__CLASS__, 'validateDateFields'), __('must be less than expiry date', 'wp-loyalty-rules'));
            $settings_validator->rule('numeric', array('expire_email_after'))->message(__('{field} is required', 'wp-loyalty-rules'));
            $settings_validator->rule('min', array('expire_email_after'), 0);
            $settings_validator->rule('required', array('expire_email_after'));
            $settings_validator->rule('alpha', array('expire_email_period'));
            $settings_validator->rule('expire_email_after', array('expire_email_after'));
        }
        if ($settings_validator->validate()) {
            return true;
        } else {
            return $settings_validator->errors();
        }
    }

    static function validateInputAlpha($input)
    {
        return preg_replace('/[^A-Za-z0-9_\-]/', '', $input);
    }

    static function validateSearch($field, $value, array $params, array $fields)
    {
        return preg_match('/^([@_.a-z0-9- ])+$/i', $value);
    }

    static function validateAlphaNumWithUnderscore($field, $value, array $params, array $fields)
    {
        return (bool)preg_match('/^[a-zA-Z0-9_-]+$/', $value);
    }

    static function validateExpiryDate($data)
    {
        $expiry_date_validator = new Validator($data);
        $expiry_date_validator->stopOnFirstFail(false);
        $expiry_date_validator->rule('date', array('expiry_point_date', 'expiry_email_date'));
        $expiry_date_validator->rule('dateFormat', array('expiry_point_date', 'expiry_email_date'), 'Y-m-d');
        if ($expiry_date_validator->validate()) {
            return true;
        } else {
            return $expiry_date_validator->errors();
        }
    }

    static function validateDateFields($field, $value, array $params, array $fields)
    {
        $input = new Input();
        $expire_after = $input->post_get('expire_after', 0);
        $expire_period = $input->post_get('expire_period', 0);
        $email_after = $input->post_get('expire_email_after', 0);
        $email_period = $input->post_get('expire_email_period', 0);
        $date_1 = date_create();
        if ($expire_after === '') return false;
        if ($email_after === '') return false;
        date_modify($date_1, '+' . $expire_after . ' ' . $expire_period);
        $expiry_time_period = date_format($date_1, 'Y-m-d');
        $date_2 = date_create();
        date_modify($date_2, '+' . $email_after . ' ' . $email_period);
        $expiry_email_period = date_format($date_2, 'Y-m-d');
        if ($expiry_time_period < $expiry_email_period) {
            return false;
        }
        return true;
    }
}