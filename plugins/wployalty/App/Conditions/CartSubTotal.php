<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Conditions;
defined('ABSPATH') or die();

class CartSubTotal extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->name = 'cart_subtotal';
        $this->label = __('Subtotal', 'wp-loyalty-rules');
        $this->group = __('Cart', 'wp-loyalty-rules');
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
        $operator = sanitize_text_field($options->operator);
        $value = apply_filters('wlr_convert_cart_subtotal', $options->value);
        $is_calculate_base = $this->getCalculateBased($data);
        if (!$this->isValidCalculateBased($is_calculate_base)) {
            return false;
        }
        $subtotal = 0;
        if ($is_calculate_base === 'cart' && isset($data[$is_calculate_base]) && !empty($data[$is_calculate_base])) {
            $subtotal = self::$woocommerce_helper->getCartSubtotal($data[$is_calculate_base]);
        } elseif ($is_calculate_base === 'order' && isset($data[$is_calculate_base]) && !empty($data[$is_calculate_base])) {
            $subtotal = self::$woocommerce_helper->getOrderSubtotal($data[$is_calculate_base]);
        } elseif ($is_calculate_base === 'product') {
            return true;
        }
        $subtotal = apply_filters('wlr_subtotal_condition', $subtotal, $options, $data);
        return $this->doComparisionOperation($operator, $subtotal, $value);
    }

    public function getMinimumAmount($condition)
    {
        $min_amount = 0;
        if (isset($condition->operator) && isset($condition->value) && in_array($condition->operator, array('greater_than_or_equal', 'greater_than'))) {
            $min_amount = $condition->value;
        }
        return $min_amount;
    }

    public function getMaximumAmount($condition)
    {
        $max_amount = 0;
        if (isset($condition->operator) && isset($condition->value) && in_array($condition->operator, array('less_than', 'less_than_or_equal'))) {
            $max_amount = $condition->value;
        }
        return $max_amount;
    }
}
