<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Conditions;
defined('ABSPATH') or die();

class UserRole extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->name = 'user_role';
        $this->label = __('User role', 'wp-loyalty-rules');
        $this->group = __('Customer', 'wp-loyalty-rules');
    }

    public function isProductValid($options, $data)
    {
        return $this->check($options, $data);
    }

    public function check($options, $data)
    {
        if (!is_object($options) || !isset($options->operator) || !isset($options->value)) {
            return false;
        }
        $user_email = isset($data['user_email']) && !empty($data['user_email']) ? $data['user_email'] : '';
        $user = '';
        if (is_string($user_email) && !empty($user_email)) {
            $user = get_user_by('email', $user_email);
            $user = apply_filters('wlr_rule_user_role_on_condition_check', $user);
        }
        if (!empty($user)) {
            $current_user_role = self::$woocommerce_helper->getRole($user);
            return $this->doCompareInListOperation($options->operator, $current_user_role, $options->value);
        }
        $current_user_role = array('wlr_rules_guest');
        return $this->doCompareInListOperation($options->operator, $current_user_role, $options->value);
    }
}
