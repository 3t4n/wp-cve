<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Helpers;

use Valitron\Validator;
use Wlr\App\Models\Levels;

defined('ABSPATH') or die;

class Validation
{
    static function validateRenderPage($post){
        $validator = new Validator($post);
        $validator->labels(array(
            'type' => __('Type', 'wp-loyalty-rules'),
            'page_number' => __('Page number', 'wp-loyalty-rules'),
        ));
        $validator->stopOnFirstFail(false);
        $validator->rule('required', array('type'))->message(__('{field} is required', 'wp-loyalty-rules'));
        $validator->rule('numeric',
            array(
                'page_number',
            )
        )->message(__('{field} must contain only numbers 0-9', 'wp-loyalty-rules'));
        if ($validator->validate()) {
            return true;
        } else {
            return $validator->errors();
        }
    }
    static function validateDashboard($post, $task = '')
    {
        $validation = array();
        if (empty($task)) {
            return $validation;
        }
        $settings_validator = new Validator($post);
        $settings_validator->labels(array(
            'fil_type' => __('Filter', 'wp-loyalty-rules'),
            'currency' => __('Currency Code', 'wp-loyalty-rules'),
            'from_date' => __('From Date', 'wp-loyalty-rules'),
            'to_date' => __('To Date', 'wp-loyalty-rules'),
        ));
        $settings_validator->stopOnFirstFail(false);
        if (in_array($task, array('getActivityLoyalData', 'getChartData', 'getCustomerRecentActivity'))) {
            Validator::addRule('alphaNumWithUnderscore', array(__CLASS__, 'validateAlphaNumWithUnderscore'), __('accepts only letters,numbers and underscore', 'wp-loyalty-rules'));
            Validator::addRule('dateORNull', array(__CLASS__, 'validateDateORNull'), __('{field} should be a valid date', 'wp-loyalty-rules'));
            $settings_validator->rule('alphaNumWithUnderscore',
                array(
                    'fil_type'
                )
            )->message(__('{field} must contain only letters a-z and/or numbers 0-9 and/or under score', 'wp-loyalty-rules'));
            $settings_validator->rule('alpha',
                array(
                    'currency'
                )
            )->message(__('{field} must contain only letters A-Z', 'wp-loyalty-rules'));
            $settings_validator->rule('dateORNull',
                array(
                    'from_date',
                    'to_date'
                )
            )->message(__('{field} should be a valid date', 'wp-loyalty-rules'));
            if ($settings_validator->validate()) {
                return true;
            } else {
                $validation = $settings_validator->errors();
            }
        }
        return $validation;
    }

    static function validateCommonFields($post)
    {
        $settings_validator = new Validator($post);
        $settings_validator->labels(array(
            'search' => __('Search', 'wp-loyalty-rules'),
            'filter_order' => __('Filter order', 'wp-loyalty-rules'),
            'limit' => __('Limit', 'wp-loyalty-rules'),
            'offset' => __('Offset', 'wp-loyalty-rules'),
            'filter_order_dir' => __('Filter Direction', 'wp-loyalty-rules'),
        ));
        $settings_validator->stopOnFirstFail(false);
        Validator::addRule('sanitizeText', array(__CLASS__, 'validateSanitizeText'), __('Invalid characters', 'wp-loyalty-rules'));
        /*Validator::addRule('search', array(__CLASS__, 'validateSearch'), __('validation has failed', 'wp-loyalty-rules'));*/
        Validator::addRule('alphaNumWithUnderscore', array(__CLASS__, 'validateAlphaNumWithUnderscore'), __('validation has failed', 'wp-loyalty-rules'));
        $settings_validator->rule('sanitizeText',
            array(
                'search'
            )
        )->message(__('{field} must only contain letters a-z and/or numbers 0-9 and/or under score,@,- and/or space ', 'wp-loyalty-rules'));
        $settings_validator->rule('alphaNumWithUnderscore',
            array(
                'filter_order'
            )
        )->message(__('{field} must contain only letters a-z and/or numbers 0-9 and/or under score', 'wp-loyalty-rules'));
        $settings_validator->rule('numeric',
            array(
                'limit',
                'offset',
            )
        )->message(__('{field} must contain only numbers 0-9', 'wp-loyalty-rules'));
        $settings_validator->rule('alpha',
            array(
                'filter_order_dir'
            )
        )->message(__('{field} must contain only letters a-z', 'wp-loyalty-rules'));
        if ($settings_validator->validate()) {
            return true;
        } else {
            return $settings_validator->errors();
        }
    }

    static function validateSettingsTab($post)
    {
        $settings_validator = new Validator($post);
        $labels_array_fields = array(
            'wlr_point_label', 'wlr_point_singular_label', 'license_key', 'wlr_point_rounding_type',
            'wlr_redeem_when_other_coupon_apply', 'wlr_cart_earn_point_display', 'wlr_cart_redeem_point_display',
            'wlr_thank_you_position', 'wlr_my_account_label_icon_position', 'wlr_cart_earn_points_message', 'wlr_checkout_earn_points_message',
            'wlr_earn_point_order_summary_text', 'wlr_cart_redeem_points_message', 'wlr_checkout_redeem_points_message', 'wlr_thank_you_message',
            'product_message_display_position',
            'redeem_button_text', 'apply_coupon_button_text',
            'redeem_point_icon', 'available_point_icon',
            'redeem_button_color', 'redeem_button_text_color',
            'apply_coupon_border_color', 'apply_coupon_button_text_color',
            'apply_coupon_button_color', 'apply_coupon_background',
            'theme_color', 'heading_color', 'wlr_is_cart_earn_message_enable', 'wlr_is_cart_redeem_message_enable',
            'wlr_is_checkout_redeem_message_enable', 'wlr_is_thank_you_message_enable', 'earn_cart_text_color',
            'earn_cart_border_color', 'earn_cart_background_color', 'redeem_cart_text_color', 'redeem_cart_border_color',
            'redeem_cart_background_color', 'earn_message_icon', 'redeem_message_icon',
            'reward_code_prefix','wlr_referral_prefix'
        );
        $this_field = __("This field", "wp-loyalty-rules");
        foreach ($labels_array_fields as $label) {
            $labels_array[$label] = $this_field;
        }
        $settings_validator->labels($labels_array);
        $settings_validator->stopOnFirstFail(false);
        Validator::addRule('cleanHtml', array(__CLASS__, 'validateCleanHtml'), __('Invalid characters', 'wp-loyalty-rules'));
        Validator::addRule('basicHtmlTags', array(__CLASS__, 'validateBasicHtmlTags'), __('Invalid characters', 'wp-loyalty-rules'));
        Validator::addRule('alphaNumWithSpace', array(__CLASS__, 'validateAlphaNumWithSpace'), __('accepts only letters,numbers and space', 'wp-loyalty-rules'));
        Validator::addRule('radioButtonAndSelectBox', array(__CLASS__, 'validateRadioButtonAndSelectBox'), __('must contain any of the following values: yes,no,1,0', 'wp-loyalty-rules'));
        Validator::addRule('alphaNumWithUnderscore', array(__CLASS__, 'validateAlphaNumWithUnderscore'), __('validation has failed', 'wp-loyalty-rules'));
        Validator::addRule('sanitizeText', array(__CLASS__, 'validateSanitizeText'), __('Invalid characters', 'wp-loyalty-rules'));
        Validator::addRule('limitLength', array(__CLASS__, 'validateLimitLength'), __('Character length must be less than 20', 'wp-loyalty-rules'));
        Validator::addRule('hexColor', array(__CLASS__, 'validateColor'), __('invalid color format use hex color', 'wp-loyalty-rules'));
        Validator::addRule('alphaUnderscoreHyphen', array(__CLASS__, 'validateAlphaHyphenUnderscore'), __('Invalid characters', 'wp-loyalty-rules'));
        $settings_validator->rule('alphaNum',
            array(
                'license_key',
            )
        )->message(__('{field} accepts only letters and numbers', 'wp-loyalty-rules'));
        $settings_validator->rule('required', array(
            'wlr_point_label',
            'wlr_point_singular_label', 'apply_coupon_button_text', 'redeem_button_text',
        ))->message(__('{field} is required', 'wp-loyalty-rules'));
        $settings_validator->rule('alpha',
            array(
                'wlr_point_rounding_type',
                'wlr_redeem_when_other_coupon_apply',
                'wlr_cart_earn_point_display',
                'wlr_cart_redeem_point_display',
                'wlr_thank_you_position'
            )
        )->message(__('{field} accepts only letters', 'wp-loyalty-rules'));
        $settings_validator->rule('sanitizeText',
            array(
                'wlr_point_label',
                'wlr_point_singular_label'
            )
        )->message(__('{field} accepts only letters,numbers and space', 'wp-loyalty-rules'));
        $settings_validator->rule('radioButtonAndSelectBox',
            array(
                'wlr_my_account_label_icon_position',
                'radioButtonAndSelectBox',
                'wlr_is_cart_redeem_message_enable',
                'wlr_is_checkout_redeem_message_enable', 'wlr_is_thank_you_message_enable',
            )
        )->message(__('{field} must contain only yes,no,0,1 and/or boolean', 'wp-loyalty-rules'));
        $settings_validator->rule('cleanHtml',
            array(
                'wlr_cart_earn_points_message',
                'wlr_checkout_earn_points_message',
                'wlr_earn_point_order_summary_text',
                'wlr_cart_redeem_points_message',
                'wlr_checkout_redeem_points_message',
                'wlr_thank_you_message', 'redeem_button_text',
                'apply_coupon_button_text',
                'redeem_button_text'
            )
        )->message(__('{field} has invalid characters', 'wp-loyalty-rules'));
        $settings_validator->rule('basicHtmlTags',
            array(
                'wlr_cart_earn_points_message',
                'wlr_checkout_earn_points_message',
                'wlr_earn_point_order_summary_text',
                'wlr_cart_redeem_points_message',
                'wlr_checkout_redeem_points_message',
                'wlr_thank_you_message', 'redeem_button_text',
                'apply_coupon_button_text',
                'redeem_button_text'
            )
        )->message(__('{field} has invalid characters', 'wp-loyalty-rules'));
        $settings_validator->rule('alphaNumWithUnderscore',
            array(
                'product_message_display_position'
            )
        )->message(__('{field} accepts only letters,numbers and under score', 'wp-loyalty-rules'));
        $settings_validator->rule('limitLength',
            array(
                'apply_coupon_button_text',
                'redeem_button_text'
            )
        )->message(__('{field} length must be 5 to 20 character', 'wp-loyalty-rules'));
        $settings_validator->rule('hexColor', array('redeem_button_color', 'redeem_button_text_color',
            'apply_coupon_border_color', 'apply_coupon_button_text_color',
            'apply_coupon_button_color', 'apply_coupon_background',
            'theme_color', 'heading_color', 'earn_cart_text_color', 'earn_cart_border_color',
            'earn_cart_background_color', 'redeem_cart_text_color', 'redeem_cart_border_color',
            'redeem_cart_background_color'));
        $settings_validator->rule('alphaUnderscoreHyphen',array( 'reward_code_prefix','wlr_referral_prefix'))->message(__('{field} has invalid characters', 'wp-loyalty-rules'));
        if ($settings_validator->validate()) {
            return true;
        } else {
            return $settings_validator->errors();
        }
    }

    static function validateReward($post)
    {
        $rule_validator = new Validator($post);
        $labels_array = array(
            'expire_email' => __('Expiry notification', 'wp-loyalty-rules'),
            'minimum_point' => __('Minimum points', 'wp-loyalty-rules'),
            'maximum_point' => __('Maximum points', 'wp-loyalty-rules'),
        );
        $labels_array_fields = array(
            'name', 'reward_type', 'display_name', 'discount_type', 'discount_value',
            'require_point',
        );
        $this_field = __("This field", "wp-loyalty-rules");
        foreach ($labels_array_fields as $label) {
            $labels_array[$label] = $this_field;
        }
        $rule_validator->labels($labels_array);
        $rule_validator->stopOnFirstFail(false);
        Validator::addRule('cleanHtml', array(__CLASS__, 'validateCleanHtml'), __('Invalid characters', 'wp-loyalty-rules'));
        Validator::addRule('basicHtmlTags', array(__CLASS__, 'validateBasicHtmlTags'), __('Invalid characters', 'wp-loyalty-rules'));
        Validator::addRule('alphaNumWithSpace', array(__CLASS__, 'validateAlphaNumWithSpace'), __('accepts only letters,numbers and space', 'wp-loyalty-rules'));
        Validator::addRule('inputAlpha', array(__CLASS__, 'validateInputAlpha'), __('accepts only letters,numbers and underscore', 'wp-loyalty-rules'));
        Validator::addRule('alphaNumWithUnderscore', array(__CLASS__, 'validateAlphaNumWithUnderscore'), __('validation has failed', 'wp-loyalty-rules'));
        Validator::addRule('number', array(__CLASS__, 'validateNumber'), __('accepts only numbers', 'wp-loyalty-rules'));
        Validator::addRule('numberGeZero', array(__CLASS__, 'validateNumberGeZero'), __('required field', 'wp-loyalty-rules'));
        Validator::addRule('isEmpty', array(__CLASS__, 'validateIsEmpty'), __('is empty', 'wp-loyalty-rules'));
        Validator::addRule('greaterThen', array(__CLASS__, 'validateGreaterThen'), __('must be greater than {field1}', 'wp-loyalty-rules'));
        Validator::addRule('nameLengthLimit', array(__CLASS__, 'validateNameLimitLength'), __(' is too length', 'wp-loyalty-rules'));
	    Validator::addRule('isProductExist', array(__CLASS__, 'checkIsProductExist'), __('product is not available', 'wp-loyalty-rules'));
		$required_fields = array(
            'name',
            'reward_type',
            'display_name',
            'discount_type',
        );
        $number_non_zero_field = array();
        if (isset($post['discount_type']) && !in_array($post['discount_type'], array('free_product', 'free_shipping'))) {
            $required_fields[] = 'discount_value';
            $number_non_zero_field[] = 'discount_value';
            if ($post['discount_type'] == 'percent') {
                $rule_validator->rule('max', array('discount_value'), 100)->message(__('{field} is must be less then 100', 'wp-loyalty-rules'));
            }
        } elseif (isset($post['discount_type']) && $post['discount_type'] == 'free_product') {
            $rule_validator->rule('isEmpty', array('free_product'))->message(__('{field} is required', 'wp-loyalty-rules'));
        }
        if (isset($post['reward_type']) && $post['reward_type'] == 'redeem_point') {
            $required_fields[] = 'require_point';
            $number_non_zero_field[] = 'require_point';
        }
        if (isset($post['maximum_point']) && $post['maximum_point'] > 0) {
            $rule_validator->rule('greaterThen', 'maximum_point', 'minimum_point');
        }
        $empty_check_fields = $condition_label = $condition_clean = $check_product_exist = array();
        if (isset($post['conditions']) && is_array($post['conditions']) && !empty($post['conditions'])) {
            $condition_label_fields = array(
                'conditions.user_point.value', 'conditions.user_point.operator', 'conditions.user_level.value', 'conditions.language.operator',
                'conditions.language.value', 'conditions.currency.operator', 'conditions.currency.value',
                'conditions.product_onsale.operator', 'conditions.product_tags.operator', 'conditions.product_tags.value',
                'conditions.product_tags.condition', 'conditions.product_tags.qty', 'conditions.cart_line_items_count.value',
                'conditions.cart_line_items_count.operator', 'conditions.cart_line_items_count.sub_condition_type', 'conditions.cart_weights.value',
                'conditions.cart_weights.operator', 'conditions.cart_weights.sub_condition_type', 'conditions.cart_subtotal.value',
                'conditions.cart_subtotal.operator', 'conditions.products.value', 'conditions.products.operator', 'conditions.products.condition',
                'conditions.products.qty', 'conditions.product_attributes.value', 'conditions.product_attributes.condition', 'conditions.product_attributes.qty',
                'conditions.product_attributes.operator', 'conditions.product_category.value', 'conditions.product_category.operator', 'conditions.product_category.condition',
                'conditions.product_category.qty', 'conditions.product_sku.value', 'conditions.product_sku.operator', 'conditions.product_sku.qty',
                'conditions.product_sku.condition', 'conditions.payment_method.value', 'conditions.payment_method.operator', 'conditions.purchase_history.operator',
                'conditions.purchase_history.value', 'conditions.purchase_history.order_status', 'conditions.life_time_sale_value.operator',
                'conditions.life_time_sale_value.value', 'conditions.life_time_sale_value.order_status',
                'conditions.purchase_previous_orders_with_amount.status', 'conditions.purchase_previous_orders_with_amount.value',
            );
            foreach ($condition_label_fields as $label) {
                $condition_label_text[$label] = $this_field;
            }
            foreach ($post['conditions'] as $key => $condition) {
                switch ($condition['type']) {
                    case 'user_level':
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.value';
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        $rule_validator->rule('number', array('conditions.' . $key . '.options.value'))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
                        break;
                    case 'user_point':
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . '.options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $condition_clean[] = 'conditions.' . $key . '.options.operator';
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        break;
                    case 'product_onsale':
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . '.options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $condition_clean[] = 'conditions.' . $key . '.options.operator';
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        break;
                    case 'language':
                    case 'currency':
                    case 'cart_subtotal':
                    case 'payment_method':
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . 'options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $condition_clean[] = 'conditions.' . $key . '.options.operator';
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        if ($condition['type'] === 'cart_subtotal') {
                            $rule_validator->rule('number', array('conditions.' . $key . '.options.value'))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
                        } else {
                            $empty_check_fields[] = 'conditions.' . $key . '.options.value';
                        }
                        break;
                    case 'cart_line_items_count':
                    case 'cart_weights':
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . '.options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $condition_clean[] = 'conditions.' . $key . '.options.operator';
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        $rule_validator->rule('number', array('conditions.' . $key . '.options.value'))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
                        $required_fields[] = 'conditions.' . $key . '.options.sub_condition_type';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.sub_condition_type';
                        $condition_label['conditions.' . $key . '.options.sub_condition_type'] = $condition_label_text['conditions.' . $condition['type'] . '.sub_condition_type'];
                        $condition_clean[] = 'conditions.' . $key . '.options.sub_condition_type';
                        break;
                    case 'life_time_sale_value':
                    case 'purchase_history':
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . '.options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $condition_clean[] = 'conditions.' . $key . '.options.operator';
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        $rule_validator->rule('number', array('conditions.' . $key . '.options.value'))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
                        $required_fields[] = 'conditions.' . $key . '.options.order_status';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.order_status';
                        $condition_label['conditions.' . $key . '.options.order_status'] = $condition_label_text['conditions.' . $condition['type'] . '.order_status'];
                        break;
                    case 'products':
	                    $required_fields[] = 'conditions.' . $key . '.options.operator';
	                    $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
	                    $condition_label['conditions.' . $key . '.options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
	                    $condition_clean[] = 'conditions.' . $key . '.options.operator';
	                    $required_fields[] = 'conditions.' . $key . '.options.value';
	                    $empty_check_fields[] = 'conditions.' . $key . '.options.value';
	                    $check_product_exist[] = 'conditions.' . $key . '.options.value';
	                    $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
	                    $required_fields[] = 'conditions.' . $key . '.options.condition';
	                    $empty_check_fields[] = 'conditions.' . $key . '.options.condition';
	                    $condition_label['conditions.' . $key . '.options.condition'] = $condition_label_text['conditions.' . $condition['type'] . '.condition'];
	                    $condition_clean[] = 'conditions.' . $key . '.options.condition';
	                    $required_fields[] = 'conditions.' . $key . '.options.qty';
	                    $number_non_zero_field[] = 'conditions.' . $key . '.options.qty';
	                    $condition_label['conditions.' . $key . '.options.qty'] = $condition_label_text['conditions.' . $condition['type'] . '.qty'];
	                    break;
                    case 'product_attributes':
                    case 'product_category':
                    case 'product_sku':
                    case 'product_tags':
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . '.options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $condition_clean[] = 'conditions.' . $key . '.options.operator';
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.value';
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        $required_fields[] = 'conditions.' . $key . '.options.condition';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.condition';
                        $condition_label['conditions.' . $key . '.options.condition'] = $condition_label_text['conditions.' . $condition['type'] . '.condition'];
                        $condition_clean[] = 'conditions.' . $key . '.options.condition';
                        $required_fields[] = 'conditions.' . $key . '.options.qty';
                        $number_non_zero_field[] = 'conditions.' . $key . '.options.qty';
                        $condition_label['conditions.' . $key . '.options.qty'] = $condition_label_text['conditions.' . $condition['type'] . '.qty'];
                        break;
                    case 'purchase_previous_orders_with_amount':
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . '.options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $condition_clean[] = 'conditions.' . $key . '.options.operator';
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        $rule_validator->rule('number', array('conditions.' . $key . '.options.value'))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
                        $required_fields[] = 'conditions.' . $key . '.options.status';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.status';
                        $condition_label['conditions.' . $key . '.options.status'] = $condition_label_text['conditions.' . $condition['type'] . '.status'];
                        $required_fields[] = 'conditions.' . $key . '.options.time';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.time';
                        $condition_label['conditions.' . $key . '.options.time'] = $condition_label_text['conditions.' . $condition['type'] . '.time'];
                        $condition_clean[] = 'conditions.' . $key . '.options.time';
                        if (isset($condition['options']) && isset($condition['options']['max_amount']) > 0) {
                            $condition_label['conditions.' . $key . '.options.min_amount'] = __('Min Amount', 'wp-loyalty-rules');
                            $condition_label['conditions.' . $key . '.options.max_amount'] = __('Max Amount', 'wp-loyalty-rules');
                            $rule_validator->rule('greaterThen', 'conditions.' . $key . '.options.max_amount', 'conditions.' . $key . '.options.min_amount');
                        }
                        break;
                }
            }
            $rule_validator->labels($condition_label);
            $rule_validator->rule('cleanHtml', $condition_clean)->message(__('{field} has invalid characters', 'wp-loyalty-rules'));
            $rule_validator->rule('basicHtmlTags', $condition_clean)->message(__('{field} has invalid characters', 'wp-loyalty-rules'));
	        $rule_validator->rule('isProductExist', $check_product_exist)->message(__('{field} has one of deleted product', 'wp-loyalty-rules'));
        }
        $rule_validator->rule('isEmpty', $empty_check_fields)->message(__('{field} is empty', 'wp-loyalty-rules'));
        $rule_validator->rule('cleanHtml',
            array(
                'description'
            )
        )->message(__('{field} has invalid characters', 'wp-loyalty-rules'));
        $rule_validator->rule('basicHtmlTags',
            array(
                'description'
            )
        )->message(__('{field} has invalid characters', 'wp-loyalty-rules'));
        $rule_validator->rule('alpha', array(
            'expire_period',
            'expire_email_period',
            'condition_relationship'

        ))->message(__('{field} accepts only letters A-Z', 'wp-loyalty-rules'));
        $rule_validator->rule('alphaNumWithUnderscore',
            array(
                'discount_type',
                'conditions.*.options.sub_condition_type',
                'conditions.*.options.operator',
                'conditions.*.options.condition',
            )
        )->message(__('{field} accepts only letters,numbers and underscore', 'wp-loyalty-rules'));
        $error_message = '';
        if (isset($post['enable_expiry_email']) && $post['enable_expiry_email'] > 0) {
            $number_non_zero_field[] = 'expire_email';
            $required_fields[] = 'expire_email';
        }
        if (!empty($number_non_zero_field)) {
            $rule_validator->rule('numberGeZero', $number_non_zero_field)->message(__('{field} must be greater than 0', 'wp-loyalty-rules'));
        }
        $rule_validator->rule('number', array(
            'conditions.*.options.qty',
            'discount_value',
            'require_point',
            'expire_after',
            'enable_expiry_email',
            'expire_email',
            'usage_limits',
            'active',
            'maximum_point',
            'minimum_point'
        ))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
        $rule_validator->rule('inputAlpha',
            array(
                'reward_type'
            )
        )->message(__('{field} accepts only letters,numbers and underscore', 'wp-loyalty-rules'));
        if (isset($post['enable_expiry_email']) && $post['enable_expiry_email'] > 0) {
            if (isset($post['expire_after']) && !empty($post['expire_after'])) {
                $expire_after = isset($post['expire_after']) && !empty($post['expire_after']) ? $post['expire_after'] : 0;
                $max_value = 0;
                //'day','week','month','year'
                switch ($post['expire_period']) {
                    case 'year':
                        switch ($post['expire_email_period']) {
                            case 'year':
                                $max_value = $expire_after;
                                break;
                            case 'month':
                                $max_value = $expire_after * 12;
                                break;
                            case 'week':
                                $max_value = $expire_after * 52;
                                break;
                            case 'day':
                                $max_value = $expire_after * 365;
                                break;
                        }
                        break;
                    case 'month':
                        switch ($post['expire_email_period']) {
                            case 'year':
                                $max_value = -1;
                                break;
                            case 'month':
                                $max_value = $expire_after;
                                break;
                            case 'week':
                                $max_value = $expire_after * 4;
                                break;
                            case 'day':
                                $max_value = $expire_after * 30;
                                break;
                        }
                        break;
                    case 'week':
                        switch ($post['expire_email_period']) {
                            case 'year':
                            case 'month':
                                $max_value = -1;
                                break;
                            case 'week':
                                $max_value = $expire_after;
                                break;
                            case 'day':
                                $max_value = $expire_after * 7;
                                break;
                        }
                        break;
                    case 'day':
                        switch ($post['expire_email_period']) {
                            case 'year':
                            case 'month':
                            case 'week':
                                $max_value = -1;
                                break;
                            case 'day':
                                $max_value = $expire_after;
                                break;
                        }
                        break;
                }
                if ($max_value <= 0) {
                    $error_message = sprintf(__('The date range is wrong', 'wp-loyalty-rules'), $max_value);
                } elseif ($max_value < $post['expire_email']) {
                    $error_message = sprintf(__('The value should be less than %d', 'wp-loyalty-rules'), $max_value);
                }
            }
        }
        $rule_validator->rule('required', $required_fields)->message(__('{field} is required', 'wp-loyalty-rules'));
        $rule_validator->rule('nameLengthLimit', array('name'));
        if ($rule_validator->validate()) {
            if (!empty($error_message)) {
                return array('expire_email' => array($error_message));
            }
            return true;
        } else {
            $field_errors = $rule_validator->errors();
            if (!isset($field_errors['expire_email']) && !empty($error_message)) {
                $field_errors['expire_email'] = array($error_message);
            }
            return $field_errors;
        }
    }

    static function validateRuleTab($post)
    {
        $rule_validator = new Validator($post);
        $labels_array = array(
            'point_rule.minimum_point' => __('Minimum points', 'wp-loyalty-rules'),
            'point_rule.maximum_point' => __('Maximum points', 'wp-loyalty-rules'),
            'point_rule.min_subtotal' => __('Minimum spend', 'wp-loyalty-rules'),
            'point_rule.max_subtotal' => __('Maximum spend', 'wp-loyalty-rules'),
            'point_rule.level_ids' => __('Level', 'wp-loyalty-rules')
        );
        $labels_array_fields = array(
            'name', 'action_type',
            'campaign_type', 'point_rule.share_message', 'point_rule.share_body',
            'point_rule.share_subject', 'point_rule.birthday_message', 'point_rule.signup_message', 'point_rule.review_message',
            'point_rule.earn_point', 'point_rule.wlr_point_earn_price', 'point_rule.earn_reward', 'point_rule.advocate.earn_point',
            'point_rule.advocate.earn_reward', 'point_rule.friend.earn_point', 'point_rule.friend.earn_reward',
            'point_rule.variable_product_message', 'point_rule.single_product_message', 'point_rule.no_of_purchase', 'point_rule.minimum_spend_on_order',
            'conditions.*.options.qty', 'point_rule.advocate.campaign_type', 'point_rule.friend.campaign_type', 'point_rule.is_rounded_edge',
            'point_rule.display_product_message_page', 'point_rule.birthday_earn_type', 'point_rule.advocate.earn_type', 'point_rule.friend.earn_type',
            'conditions.*.options.sub_condition_type', 'conditions.*.options.operator', 'conditions.*.options.condition', 'point_rule.share_url'
        );
        $this_field = __("This field", "wp-loyalty-rules");
        foreach ($labels_array_fields as $label) {
            $labels_array[$label] = $this_field;
        }
        $rule_validator->labels($labels_array);
        $rule_validator->stopOnFirstFail(false);
        Validator::addRule('cleanHtml', array(__CLASS__, 'validateCleanHtml'), __('Invalid characters', 'wp-loyalty-rules'));
        Validator::addRule('basicHtmlTags', array(__CLASS__, 'validateBasicHtmlTags'), __('Invalid characters', 'wp-loyalty-rules'));
        Validator::addRule('alphaNumWithSpace', array(__CLASS__, 'validateAlphaNumWithSpace'), __('accepts only letters,numbers and space', 'wp-loyalty-rules'));
        Validator::addRule('alphaNumWithUnderscore', array(__CLASS__, 'validateAlphaNumWithUnderscore'), __('validation has failed', 'wp-loyalty-rules'));
        Validator::addRule('inputAlpha', array(__CLASS__, 'validateInputAlpha'), __('accepts only letters,numbers and underscore', 'wp-loyalty-rules'));
        Validator::addRule('dateORNull', array(__CLASS__, 'validateDateORNull'), __('{field} should be a valid date', 'wp-loyalty-rules'));
        Validator::addRule('number', array(__CLASS__, 'validateNumber'), __('accepts only numbers', 'wp-loyalty-rules'));
        Validator::addRule('numberGeZero', array(__CLASS__, 'validateNumberGeZero'), __('required field', 'wp-loyalty-rules'));
        Validator::addRule('greaterThen', array(__CLASS__, 'validateGreaterThen'), __('must be greater than {field1}', 'wp-loyalty-rules'));
        Validator::addRule('isEmpty', array(__CLASS__, 'validateIsEmpty'), __(' is empty', 'wp-loyalty-rules'));
        Validator::addRule('checkOrderSpend', array(__CLASS__, 'validateOrderAndSpend'), __(' is required', 'wp-loyalty-rules'));
        Validator::addRule('nameLengthLimit', array(__CLASS__, 'validateNameLimitLength'), __('is too length', 'wp-loyalty-rules'));
        Validator::addRule('isProductExist', array(__CLASS__, 'checkIsProductExist'), __('product is not available', 'wp-loyalty-rules'));
        $required_fields = array('name',
            'action_type',
            'campaign_type',
        );
        $number_non_zero_field = array();
        $number_non_zero_reward_field = array();
        $empty_check_fields = array();
        if (isset($post['action_type']) && !empty($post['action_type']) && in_array($post['action_type'], array('facebook_share', 'twitter_share', 'whatsapp_share'))) {
            $required_fields[] = 'point_rule.share_message';
        } elseif (isset($post['action_type']) && !empty($post['action_type']) && $post['action_type'] == 'email_share') {
            $required_fields[] = 'point_rule.share_body';
            $required_fields[] = 'point_rule.share_subject';
        } /*elseif (isset($post['action_type']) && !empty($post['action_type']) && $post['action_type'] == 'birthday') {
            $required_fields[] = 'point_rule.birthday_message';
        } */ elseif (isset($post['action_type']) && !empty($post['action_type']) && $post['action_type'] == 'signup') {
            $required_fields[] = 'point_rule.signup_message';
        } elseif (isset($post['action_type']) && !empty($post['action_type']) && $post['action_type'] == 'product_review') {
            $required_fields[] = 'point_rule.review_message';
        } elseif (isset($post['action_type']) && !empty($post['action_type']) && $post['action_type'] == 'followup_share') {
            $required_fields[] = 'point_rule.share_url';
        } elseif (isset($post['action_type']) && !empty($post['action_type']) && $post['action_type'] == 'achievement'
            && isset($post['achievement_type']) && $post['achievement_type'] == 'level_update') {
            $required_fields[] = 'point_rule.level_ids';
            $empty_check_fields[] = 'point_rule.level_ids';
        }
        if (isset($post['campaign_type']) && $post['campaign_type'] == 'point' && isset($post['action_type']) && !empty($post['action_type']) && $post['action_type'] != 'referral') {
            $required_fields[] = 'point_rule.earn_point';
            $number_non_zero_field[] = 'point_rule.earn_point';
            if ($post['action_type'] === 'point_for_purchase') {
                $required_fields[] = 'point_rule.wlr_point_earn_price';
                $number_non_zero_field[] = 'point_rule.wlr_point_earn_price';
            }
        }
        if (isset($post['campaign_type']) && $post['campaign_type'] == 'coupon' && isset($post['action_type']) && !empty($post['action_type']) && $post['action_type'] != 'referral') {
            $required_fields[] = 'point_rule.earn_reward';
            $number_non_zero_reward_field[] = 'point_rule.earn_reward';
        }
        if (isset($post['action_type']) && !empty($post['action_type']) && in_array($post['action_type'], array('referral'))) {
            if (isset($post['point_rule']) && isset($post['point_rule']['advocate']) && isset($post['point_rule']['advocate']['campaign_type']) && $post['point_rule']['advocate']['campaign_type'] == 'point') {
                $required_fields[] = 'point_rule.advocate.earn_point';
                //$number_non_zero_field[] = 'point_rule.advocate.earn_point';
            }
            if (isset($post['point_rule']) && isset($post['point_rule']['advocate']) && isset($post['point_rule']['advocate']['campaign_type']) && $post['point_rule']['advocate']['campaign_type'] == 'coupon') {
                $required_fields[] = 'point_rule.advocate.earn_reward';
                $number_non_zero_reward_field[] = 'point_rule.advocate.earn_reward';
            }

            if (isset($post['point_rule']) && isset($post['point_rule']['friend']) && isset($post['point_rule']['friend']['campaign_type']) && $post['point_rule']['friend']['campaign_type'] == 'point') {
                $required_fields[] = 'point_rule.friend.earn_point';
                //$number_non_zero_field[] = 'point_rule.friend.earn_point';
            }
            if (isset($post['point_rule']) && isset($post['point_rule']['friend']) && isset($post['point_rule']['friend']['campaign_type']) && $post['point_rule']['friend']['campaign_type'] == 'coupon') {
                $required_fields[] = 'point_rule.friend.earn_reward';
                $number_non_zero_reward_field[] = 'point_rule.friend.earn_reward';
            }
        }

        if (isset($post['conditions']) && is_array($post['conditions']) && !empty($post['conditions'])) {
            $condition_label_text = $condition_label = $condition_clean = $check_product_exist = array();
            $condition_label_fields = array(
                'conditions.user_point.value', 'conditions.user_point.operator', 'conditions.user_level.value', 'conditions.usage_limits.value',
                'conditions.product_onsale.operator', 'conditions.user_role.value', 'conditions.user_role.operator',
                'conditions.customer.operator', 'conditions.customer.value', 'conditions.language.operator', 'conditions.language.value',
                'conditions.currency.operator', 'conditions.currency.value', 'conditions.cart_subtotal.value', 'conditions.cart_subtotal.operator',
                'conditions.payment_method.value', 'conditions.payment_method.operator', 'conditions.order_status.value', 'conditions.order_status.operator',
                'conditions.cart_line_items_count.value', 'conditions.cart_line_items_count.operator', 'conditions.cart_line_items_count.sub_condition_type',
                'conditions.cart_weights.value', 'conditions.cart_weights.operator', 'conditions.cart_weights.sub_condition_type',
                'conditions.life_time_sale_value.operator', 'conditions.life_time_sale_value.value', 'conditions.life_time_sale_value.order_status',
                'conditions.purchase_history.operator', 'conditions.purchase_history.value', 'conditions.purchase_history.order_status',
                'conditions.product_tags.operator', 'conditions.product_tags.value', 'conditions.product_tags.condition', 'conditions.product_tags.qty',
                'conditions.usage_limit.operator', 'conditions.usage_limit.value', 'conditions.products.value', 'conditions.products.operator', 'conditions.products.condition',
                'conditions.products.qty', 'conditions.order_status.value', 'conditions.order_status.operator', 'conditions.product_attributes.value', 'conditions.product_attributes.condition',
                'conditions.product_attributes.qty', 'conditions.product_attributes.operator', 'conditions.product_category.value', 'conditions.product_category.operator',
                'conditions.product_category.condition', 'conditions.product_category.qty', 'conditions.product_sku.value', 'conditions.product_sku.operator',
                'conditions.product_sku.qty', 'conditions.product_sku.condition', 'conditions.purchase_history_qty.order_status', 'conditions.purchase_history_qty.product_action_type',
                'conditions.purchase_history_qty.value', 'conditions.purchase_history_qty.purchase_before', 'conditions.purchase_history_qty.operator', 'conditions.purchase_history_qty.condition',
                'conditions.purchase_history_qty.qty', 'conditions.purchase_first_order.value', 'conditions.purchase_last_order.operator', 'conditions.purchase_last_order.value', 'conditions.purchase_last_order.status',
                'conditions.purchase_last_order_amount.operator', 'conditions.purchase_last_order_amount.value', 'conditions.purchase_last_order_amount.status',
                'conditions.purchase_previous_orders.operator', 'conditions.purchase_previous_orders.value', 'conditions.purchase_previous_orders.status', 'conditions.purchase_previous_orders.time',
                'conditions.purchase_previous_orders_for_specific_product.operator', 'conditions.purchase_previous_orders_for_specific_product.value', 'conditions.purchase_previous_orders_for_specific_product.status',
                'conditions.purchase_previous_orders_for_specific_product.time', 'conditions.purchase_previous_orders_for_specific_product.products',
                'conditions.purchase_quantities_for_specific_product.operator', 'conditions.purchase_quantities_for_specific_product.value', 'conditions.purchase_quantities_for_specific_product.status',
                'conditions.purchase_quantities_for_specific_product.time', 'conditions.purchase_quantities_for_specific_product.products',
                'conditions.purchase_spent.operator', 'conditions.purchase_spent.value', 'conditions.purchase_spent.status', 'conditions.purchase_spent.time',
                'conditions.purchase_previous_orders_with_amount.status', 'conditions.purchase_previous_orders_with_amount.value',
            );
            foreach ($condition_label_fields as $label) {
                $condition_label_text[$label] = $this_field;
            }
            foreach ($post['conditions'] as $key => $condition) {
                switch ($condition['type']) {
                    case 'user_point':
                    case 'user_level':
                    case 'usage_limits':
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        if ($condition['type'] === 'usage_limits') {
                            $number_non_zero_field[] = 'conditions.' . $key . '.options.value';
                        }
                        if ($condition['type'] === 'user_level') {
                            $empty_check_fields[] = 'conditions.' . $key . '.options.value';
                        }
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        $rule_validator->rule('number', array('conditions.' . $key . '.options.value'))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
                        break;
                    case 'product_onsale':
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . '.options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $condition_clean[] = 'conditions.' . $key . '.options.operator';
                        break;
                    case 'user_role':
                    case 'customer':
                    case 'language':
                    case 'currency':
                    case 'cart_subtotal':
                    case 'payment_method':
                    case 'order_status':
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . 'options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $condition_clean[] = 'conditions.' . $key . '.options.operator';
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        if ($condition['type'] !== 'cart_subtotal') {
                            $empty_check_fields[] = 'conditions.' . $key . '.options.value';
                        }
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        if ($condition['type'] === 'cart_subtotal') {
                            $rule_validator->rule('number', array('conditions.' . $key . '.options.value'))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
                        }
                        break;
                    case 'cart_line_items_count':
                    case 'cart_weights':
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . '.options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $condition_clean[] = 'conditions.' . $key . '.options.operator';
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        $rule_validator->rule('number', array('conditions.' . $key . '.options.value'))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
                        $required_fields[] = 'conditions.' . $key . '.options.sub_condition_type';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.sub_condition_type';
                        $condition_label['conditions.' . $key . '.options.sub_condition_type'] = $condition_label_text['conditions.' . $condition['type'] . '.sub_condition_type'];
                        $condition_clean[] = 'conditions.' . $key . '.options.sub_condition_type';
                        break;
                    case 'life_time_sale_value':
                    case 'purchase_history':
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . '.options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $condition_clean[] = 'conditions.' . $key . '.options.operator';
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        $rule_validator->rule('number', array('conditions.' . $key . '.options.value'))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
                        $required_fields[] = 'conditions.' . $key . '.options.order_status';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.order_status';
                        $condition_label['conditions.' . $key . '.options.order_status'] = $condition_label_text['conditions.' . $condition['type'] . '.order_status'];
                        break;
                    case 'products':
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . '.options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $condition_clean[] = 'conditions.' . $key . '.options.operator';
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.value';
                        $check_product_exist[] = 'conditions.' . $key . '.options.value';
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        $required_fields[] = 'conditions.' . $key . '.options.condition';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.condition';
                        $condition_label['conditions.' . $key . '.options.condition'] = $condition_label_text['conditions.' . $condition['type'] . '.condition'];
                        $condition_clean[] = 'conditions.' . $key . '.options.condition';
                        $required_fields[] = 'conditions.' . $key . '.options.qty';
                        $number_non_zero_field[] = 'conditions.' . $key . '.options.qty';
                        $condition_label['conditions.' . $key . '.options.qty'] = $condition_label_text['conditions.' . $condition['type'] . '.qty'];
                        break;
                    case 'product_attributes':
                    case 'product_category':
                    case 'product_sku':
                    case 'product_tags':
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . '.options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $condition_clean[] = 'conditions.' . $key . '.options.operator';
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.value';
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        $required_fields[] = 'conditions.' . $key . '.options.condition';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.condition';
                        $condition_label['conditions.' . $key . '.options.condition'] = $condition_label_text['conditions.' . $condition['type'] . '.condition'];
                        $condition_clean[] = 'conditions.' . $key . '.options.condition';
                        $required_fields[] = 'conditions.' . $key . '.options.qty';
                        $number_non_zero_field[] = 'conditions.' . $key . '.options.qty';
                        $condition_label['conditions.' . $key . '.options.qty'] = $condition_label_text['conditions.' . $condition['type'] . '.qty'];
                        break;
                    case 'purchase_history_qty':
                        $required_fields[] = 'conditions.' . $key . '.options.order_status';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.order_status';
                        $condition_label['conditions.' . $key . '.options.order_status'] = $condition_label_text['conditions.' . $condition['type'] . '.order_status'];
                        $required_fields[] = 'conditions.' . $key . '.options.product_action_type';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.product_action_type';
                        $condition_label['conditions.' . $key . '.options.product_action_type'] = $condition_label_text['conditions.' . $condition['type'] . '.product_action_type'];
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.value';
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        $required_fields[] = 'conditions.' . $key . '.options.purchase_before';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.purchase_before';
                        $condition_label['conditions.' . $key . '.options.purchase_before'] = $condition_label_text['conditions.' . $condition['type'] . '.purchase_before'];
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . '.options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $required_fields[] = 'conditions.' . $key . '.options.condition';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.condition';
                        $condition_label['conditions.' . $key . '.options.condition'] = $condition_label_text['conditions.' . $condition['type'] . '.condition'];
                        $required_fields[] = 'conditions.' . $key . '.options.qty';
                        $rule_validator->rule('number', array('conditions.' . $key . '.options.qty'))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
                        $condition_label['conditions.' . $key . '.options.qty'] = $condition_label_text['conditions.' . $condition['type'] . '.qty'];
                        break;
                    case 'purchase_first_order':
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        $condition_label['conditions.' . $key . 'options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        break;
                    case 'purchase_last_order':
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . 'options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $condition_clean[] = 'conditions.' . $key . '.options.operator';
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        $required_fields[] = 'conditions.' . $key . '.options.status';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.status';
                        $condition_label['conditions.' . $key . '.options.status'] = $condition_label_text['conditions.' . $condition['type'] . '.status'];
                        break;
                    case 'purchase_last_order_amount':
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . '.options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $condition_clean[] = 'conditions.' . $key . '.options.operator';
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        $rule_validator->rule('number', array('conditions.' . $key . '.options.value'))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
                        $required_fields[] = 'conditions.' . $key . '.options.status';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.status';
                        $condition_label['conditions.' . $key . '.options.status'] = $condition_label_text['conditions.' . $condition['type'] . '.status'];
                        break;
                    case 'purchase_previous_orders':
                    case 'purchase_spent':
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . '.options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $condition_clean[] = 'conditions.' . $key . '.options.operator';
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        $rule_validator->rule('number', array('conditions.' . $key . '.options.value'))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
                        $required_fields[] = 'conditions.' . $key . '.options.status';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.status';
                        $condition_label['conditions.' . $key . '.options.status'] = $condition_label_text['conditions.' . $condition['type'] . '.status'];
                        $required_fields[] = 'conditions.' . $key . '.options.time';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.time';
                        $condition_label['conditions.' . $key . '.options.time'] = $condition_label_text['conditions.' . $condition['type'] . '.time'];
                        $condition_clean[] = 'conditions.' . $key . '.options.time';
                        break;
                    case 'purchase_previous_orders_with_amount':
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . '.options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $condition_clean[] = 'conditions.' . $key . '.options.operator';
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        $rule_validator->rule('number', array('conditions.' . $key . '.options.value'))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
                        $required_fields[] = 'conditions.' . $key . '.options.status';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.status';
                        $condition_label['conditions.' . $key . '.options.status'] = $condition_label_text['conditions.' . $condition['type'] . '.status'];
                        $required_fields[] = 'conditions.' . $key . '.options.time';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.time';
                        $condition_label['conditions.' . $key . '.options.time'] = $condition_label_text['conditions.' . $condition['type'] . '.time'];
                        $condition_clean[] = 'conditions.' . $key . '.options.time';
                        if (isset($condition['options']) && isset($condition['options']['max_amount']) > 0) {
                            $condition_label['conditions.' . $key . '.options.min_amount'] = __('Min Amount', 'wp-loyalty-rules');
                            $condition_label['conditions.' . $key . '.options.max_amount'] = __('Max Amount', 'wp-loyalty-rules');
                            $rule_validator->rule('greaterThen', 'conditions.' . $key . '.options.max_amount', 'conditions.' . $key . '.options.min_amount');
                        }
                        break;
                    case 'purchase_previous_orders_for_specific_product':
                    case 'purchase_quantities_for_specific_product':
                        $required_fields[] = 'conditions.' . $key . '.options.operator';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.operator';
                        $condition_label['conditions.' . $key . '.options.operator'] = $condition_label_text['conditions.' . $condition['type'] . '.operator'];
                        $condition_clean[] = 'conditions.' . $key . '.options.operator';
                        $required_fields[] = 'conditions.' . $key . '.options.value';
                        $condition_label['conditions.' . $key . '.options.value'] = $condition_label_text['conditions.' . $condition['type'] . '.value'];
                        $rule_validator->rule('number', array('conditions.' . $key . '.options.value'))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
                        $required_fields[] = 'conditions.' . $key . '.options.status';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.status';
                        $condition_label['conditions.' . $key . '.options.status'] = $condition_label_text['conditions.' . $condition['type'] . '.status'];
                        $required_fields[] = 'conditions.' . $key . '.options.time';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.time';
                        $condition_label['conditions.' . $key . '.options.time'] = $condition_label_text['conditions.' . $condition['type'] . '.time'];
                        $condition_clean[] = 'conditions.' . $key . '.options.time';
                        $required_fields[] = 'conditions.' . $key . '.options.products';
                        $empty_check_fields[] = 'conditions.' . $key . '.options.products';
                        $condition_label['conditions.' . $key . '.options.products'] = $condition_label_text['conditions.' . $condition['type'] . '.products'];
                        break;
                }
            }
            $rule_validator->labels($condition_label);
            $rule_validator->rule('cleanHtml', $condition_clean)->message(__('{field} has invalid characters', 'wp-loyalty-rules'));
            $rule_validator->rule('basicHtmlTags', $condition_clean)->message(__('{field} has invalid characters', 'wp-loyalty-rules'));
            $rule_validator->rule('isProductExist', $check_product_exist)->message(__('{field} has one of deleted product', 'wp-loyalty-rules'));
        }
        // case 1: minimum_spend_on_order > 0  no_of_purchase > 0
        if (isset($post['point_rule']) && isset($post['point_rule']['minimum_spend_on_order']) && $post['point_rule']['minimum_spend_on_order'] > 0) {
            $required_fields[] = 'point_rule.no_of_purchase';
            $number_non_zero_field[] = 'point_rule.no_of_purchase';
        }
        // case 1: no_of_purchase > 0 minimum_spend_on_order = 0  && > 0

        if (isset($post['point_rule']) && isset($post['point_rule']['no_of_purchase']) && $post['point_rule']['no_of_purchase'] > 0
            && isset($post['point_rule']['minimum_spend_on_order']) && empty($post['point_rule']['minimum_spend_on_order'])) {
            if ($post['point_rule']['minimum_spend_on_order'] !== 0) {
                $required_fields[] = 'point_rule.minimum_spend_on_order';
            }
        }
        $rule_validator->rule('required', $required_fields)->message(__('{field} is required', 'wp-loyalty-rules'));
        $rule_validator->rule('nameLengthLimit', array('name'));
        //$rule_validator->rule('checkOrderSpend', 'point_rule.no_of_purchase', 'point_rule.minimum_spend_on_order');
        $rule_validator->rule('isEmpty', $empty_check_fields)->message(__('{field} is empty', 'wp-loyalty-rules'));
        if (!empty($number_non_zero_field)) {
            $rule_validator->rule('numberGeZero', $number_non_zero_field)->message(__('{field} must be greater than 0', 'wp-loyalty-rules'));
        }
        if (!empty($number_non_zero_reward_field)) {
            $rule_validator->rule('numberGeZero', $number_non_zero_reward_field)->message(__('{field} is required', 'wp-loyalty-rules'));
        }
        $rule_validator->rule('cleanHtml',
            array(
                'description',
                'point_rule.variable_product_message',
                'point_rule.single_product_message',
                'point_rule.signup_message',
                'point_rule.review_message',
                'point_rule.birthday_message',
                'point_rule.share_message',
                'point_rule.share_body',
                'point_rule.share_subject',
            )
        )->message(__('{field} has invalid characters', 'wp-loyalty-rules'));

        $rule_validator->rule('basicHtmlTags',
            array(
                'description',
                'point_rule.variable_product_message',
                'point_rule.single_product_message',
                'point_rule.signup_message',
                'point_rule.review_message',
                'point_rule.birthday_message',
                'point_rule.share_message',
                'point_rule.share_body',
                'point_rule.share_subject',
            )
        )->message(__('{field} has invalid characters', 'wp-loyalty-rules'));

        $rule_validator->rule('number', array(
            'active',
            'point_rule.earn_point',
            'point_rule.wlr_point_earn_price',
            'point_rule.minimum_point',
            'point_rule.maximum_point',
            'point_rule.min_subtotal',
            'point_rule.max_subtotal',
            'point_rule.no_of_purchase',
            'point_rule.minimum_spend_on_order',
            'point_rule.advocate.earn_point',
            'point_rule.friend.earn_point',
            'conditions.*.options.qty',
        ))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
        if (isset($post['point_rule']) && isset($post['point_rule']['maximum_point']) && $post['point_rule']['maximum_point'] > 0) {
            $rule_validator->rule('greaterThen', 'point_rule.maximum_point', 'point_rule.minimum_point');
        }
        if (isset($post['point_rule']) && isset($post['point_rule']['max_subtotal']) && $post['point_rule']['max_subtotal'] > 0) {
            $rule_validator->rule('greaterThen', 'point_rule.max_subtotal', 'point_rule.min_subtotal');
        }
        $rule_validator->rule('alpha',
            array(
                'point_rule.advocate.campaign_type',
                'point_rule.friend.campaign_type',
            )
        )->message(__('{field} accepts only letters A-Z', 'wp-loyalty-rules'));
        $rule_validator->rule('in',
            array(
                'point_rule.is_rounded_edge',
            ), array('yes', 'no')

        )->message(__('{field} must contain only Yes or No', 'wp-loyalty-rules'));
        $rule_validator->rule('alphaNumWithSpace',
            array(
                'point_rule.display_product_message_page'
            )
        )->message(__('{field} accepts only letters,numbers and space', 'wp-loyalty-rules'));
        $rule_validator->rule('alphaNumWithUnderscore',
            array(
                'point_rule.earn_reward',
                'point_rule.birthday_earn_type',
                'point_rule.advocate.earn_type',
                'point_rule.friend.earn_type',
                'point_rule.advocate.earn_reward',
                'point_rule.friend.earn_reward',
                'conditions.*.options.sub_condition_type',
                'conditions.*.options.operator',
                'conditions.*.options.condition',
            )
        )->message(__('{field} accepts only letters,numbers and underscore', 'wp-loyalty-rules'));
        $rule_validator->rule('dateORNull',
            array(
                'start_at',
                'end_at'
            )
        )->message(__('{field} should be a valid date', 'wp-loyalty-rules'));

        if ($rule_validator->validate()) {
            return true;
        } else {
            return $rule_validator->errors();
        }

    }
    static function checkIsProductExist($field, $value, array $params, array $fields)
    {
        $status = true;
        if (!empty($value) && is_array($value)) {
            foreach ($value as $product) {
                $product_id = is_array($product) && isset($product['value']) ? $product['value'] : 0;
                $product_obj = wc_get_product($product_id);
                if (empty($product_obj)) {
                    $status = false;
                }
            }
        }
        return $status;
    }
    static function validateEditLevelFields($post)
    {
        $rule_validator = new Validator($post);
        $rule_validator->stopOnFirstFail(false);

        $labels_array_fields = array(
            'name', 'from_points', 'to_points',
        );
        $this_field = __("This field", "wp-loyalty-rules");
        $labels_array = array();
        foreach ($labels_array_fields as $label) {
            $labels_array[$label] = $this_field;
        }
        $rule_validator->labels($labels_array);
        Validator::addRule('number', array(__CLASS__, 'validateNumber'), __('accepts only numbers', 'wp-loyalty-rules'));
        Validator::addRule('numberGeZero', array(__CLASS__, 'validateNumberGeZero'), __('required field', 'wp-loyalty-rules'));
        Validator::addRule('greaterThen', array(__CLASS__, 'validateGreaterThen'), __('must be greater than {field1}', 'wp-loyalty-rules'));
        Validator::addRule('nameLengthLimit', array(__CLASS__, 'validateNameLimitLength'), __('is too length', 'wp-loyalty-rules'));
        Validator::addRule('isEmpty', array(__CLASS__, 'validateIsEmpty'), __(' is empty', 'wp-loyalty-rules'));
        Validator::addRule('levelPoints', array(__CLASS__, 'validateLevelPoints'), __(' is empty', 'wp-loyalty-rules'));
        $required_fields = array(
            'name',
        );
        $number_non_zero_field = array();
        $rule_validator->rule('required', $required_fields)->message(__('{field} is required', 'wp-loyalty-rules'));
        $rule_validator->rule('nameLengthLimit', array('name'));
        $rule_validator->rule('number', array(
            'from_points',
            'to_points',
            'active',
        ))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
        if (!empty($number_non_zero_field)) {
            $rule_validator->rule('numberGeZero', $number_non_zero_field)->message(__('{field} must be greater than 0', 'wp-loyalty-rules'));
        }
        /* level points validations start here */
        // case 1: from 0 , to 0 - validate require field
        // case 2: from "" and to "" - validate required
        if (isset($post["from_points"]) && empty($post["from_points"])) {
            // case 1: from 0 ,to 0 - validate required field
            if (empty($post["from_points"]) && empty($post["to_points"])) {
                $rule_validator->rule('isEmpty', array('from_points', 'to_points'))->message(__('{field} can not be 0', 'wp-loyalty-rules'));
            }
            if ($post['from_points'] == "" || $post['to_points'] == "") {
                $rule_validator->rule('required', array('from_points', 'to_points'))->message(__('{field} is required', 'wp-loyalty-rules'));
            }
        }
        if (!empty($number_non_zero_field)) {
            $rule_validator->rule('numberGeZero', $number_non_zero_field)->message(__('{field} must be greater than 0', 'wp-loyalty-rules'));
        }
        if (isset($post['from_points']) && isset($post['to_points']) && ($post['from_points'] || $post['to_points'])) {
            $rule_validator->rule('levelPoints', 'from_points', 'to_points')->message(__('You seem to have another level configured that conflicts with this range. Check and make sure that the ranges are correct. Example: if you already have a level with points range 1 to 100, then you cannot create another level with points range like: 50 to 200. It should be 101 to 200', 'wp-loyalty-rules'));
            if ($post['from_points'] > $post['to_points'] && $post['to_points'] != 0) {
                $rule_validator->rule('greaterThen', 'to_points', 'from_points');
            }
        }
        /* level points validations end here */
        if ($rule_validator->validate()) {
            return true;
        } else {
            return $rule_validator->errors();
        }
    }

    static function validateCustomerPointUpdate($post)
    {
        $rule_validator = new Validator($post);
        $rule_validator->stopOnFirstFail(false);
        $labels_array_fields = array(
            'id', 'action_type', 'points', 'comments'
        );
        $this_field = __("This field", "wp-loyalty-rules");
        $labels_array = array();
        foreach ($labels_array_fields as $label) {
            $labels_array[$label] = $this_field;
        }
        $rule_validator->labels($labels_array);
        Validator::addRule('number', array(__CLASS__, 'validateNumber'), __('accepts only numbers', 'wp-loyalty-rules'));
        Validator::addRule('cleanHtml', array(__CLASS__, 'validateCleanHtml'), __('Invalid characters', 'wp-loyalty-rules'));
        Validator::addRule('basicHtmlTags', array(__CLASS__, 'validateBasicHtmlTags'), __('Invalid characters', 'wp-loyalty-rules'));
        $required_fields = array(
            'id', 'action_type', 'points'
        );
        $rule_validator->rule('required', $required_fields)->message(__('{field} is required', 'wp-loyalty-rules'));
        $rule_validator->rule('number', array(
            'id',
            'points',
        ))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
        $rule_validator->rule('cleanHtml',
            array(
                'comments'
            )
        )->message(__('{field} has invalid characters', 'wp-loyalty-rules'));

        $rule_validator->rule('basicHtmlTags',
            array(
                'comments'
            )
        )->message(__('{field} has invalid characters', 'wp-loyalty-rules'));
        if ($rule_validator->validate()) {
            return true;
        }
        return $rule_validator->errors();
    }

	static function validateAddNewCustomer($post){
		$rule_validator = new Validator($post);
		$rule_validator->stopOnFirstFail(false);
		$labels_array_fields = array(
			'user_email', 'points', 'comments'
		);
		$this_field = __("This field", "wp-loyalty-rules");
		$labels_array = array();
		foreach ($labels_array_fields as $label) {
			$labels_array[$label] = $this_field;
		}
		$rule_validator->labels($labels_array);
		Validator::addRule('number', array(__CLASS__, 'validateNumber'), __('accepts only numbers', 'wp-loyalty-rules'));
		Validator::addRule('cleanHtml', array(__CLASS__, 'validateCleanHtml'), __('Invalid characters', 'wp-loyalty-rules'));
		Validator::addRule('basicHtmlTags', array(__CLASS__, 'validateBasicHtmlTags'), __('Invalid characters', 'wp-loyalty-rules'));
		Validator::addRule('validateEmail', array(__CLASS__, 'validateEmail'), __('{field} has invalid email address', 'wp-loyalty-rules'));
		$required_fields = array(
			'user_email', 'points'
		);
		$rule_validator->rule('required', $required_fields)->message(__('{field} is required', 'wp-loyalty-rules'));
		$rule_validator->rule('validateEmail', array('user_email'));
		$rule_validator->rule('number', array(
			'points',
		))->message(__('{field} accepts only numbers', 'wp-loyalty-rules'));
		$rule_validator->rule('cleanHtml',
			array(
				'comments'
			)
		)->message(__('{field} has invalid characters', 'wp-loyalty-rules'));

		$rule_validator->rule('basicHtmlTags',
			array(
				'comments'
			)
		)->message(__('{field} has invalid characters', 'wp-loyalty-rules'));
		if ($rule_validator->validate()) {
			return true;
		}
		return $rule_validator->errors();
	}
	static function validateEmail($field,$value,array $params, array $fields){
		return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
	}

    static function validateAlphaHyphenUnderscore($field,$value,array $params, array $fields){
        return preg_match('/^[a-zA-Z_-]+$/',$value);
    }
    static function validateLevelPoints($field, $value, array $params, array $fields)
    {
        $status = true;
        if (isset($params[0]) && $params[0]) {
            $second_field = self::getSecondFieldValue($params[0], $fields);
            $level = new Levels();
            $points_valid = $level->getAll(array('name', 'from_points', 'to_points', 'id'));
            $current_level_id = (int)(isset($fields['id']) && !empty($fields['id'])) ? $fields['id'] : 0;
            $from_point = (int)$value;
            $to_point = (int)$second_field;
            if (isset($points_valid) && !empty($points_valid) && is_array($points_valid)) {
                $max = $max_from_point = 0;
                foreach ($points_valid as $points) {
                    if ($current_level_id > 0 && $current_level_id == $points->id) {
                        continue;
                    }
                    $db_from_points = (int)isset($points->from_points) ? $points->from_points : 0;
                    $db_to_points = (int)isset($points->to_points) ? $points->to_points : 0;
                    //case 1: from_points 0 to to_points 10
                    //case 2: from_points 11 to to_points 20
                    //case 3: from_points 21 to to_points 0
                    if ($to_point == 0) {
                        if ($from_point <= 0 || ($db_to_points == 0)) {
                            $status = false;
                            break;
                        }
                        if (($db_from_points <= $from_point) && ($db_to_points >= $from_point)) {
                            $status = false;
                            break;
                        }
                        /* 1. already 0 points present current to_points 0 is not allowed
                           2. from and to points are in between points range then to_points cant be zero(0) */
                        if (($max_from_point >= 0) || ($max_from_point < $db_from_points)){
                            $max_from_point = $db_from_points;
                        }
                        if (($from_point < $max_from_point ) || ($db_to_points == 0)){
                            $status = false;
                            break;
                        }

                    }
                    if ($to_point > 0) {
                        //Current: 300 to 1000
                        // available: 200 to 299
                        //available: 350 to 500
                        if (($db_from_points <= $from_point) && ($db_to_points >= $from_point)) {
                            $status = false;
                            break;
                        }
                        if (($db_from_points <= $to_point) && ($db_to_points >= $to_point || $db_to_points == 0)) {
                            $status = false;
                            break;
                        }
                        if (($max <= 0) || ($max < $db_to_points)) {
                            $max = $db_to_points;
                            if ($db_to_points == 0) {
                                $max = $db_from_points;
                            }
                        }
                        // 2 inside max or out side max point
                        if (!(($max > $from_point && $max > $to_point) || ($max < $from_point && $max < $to_point))) {
                            $status = false;
                            break;
                        }
                    }
                }
            }
        }
        return $status;

    }

    /**
     * @param $params
     * @param array $fields
     * @return int|mixed
     */
    public static function getSecondFieldValue($params, array $fields)
    {
        $second_field = 0;
        $field_array = explode('.', $params);
        foreach ($fields as $field_key => $field_value) {
            if (in_array($field_key, $field_array)) {
                if (is_array($field_value)) {
                    $key = array_search($field_key, $field_array);
                    unset($field_array[$key]);
                    /*foreach ($field_value as $f_key => $f_value) {
                        if (in_array($f_key, $field_array)) {
                            $second_field = $f_value;
                        }
                    }*/
                    $second_field = self::recursive_process($field_value, $field_array);
                } else {
                    $second_field = $field_value;
                }
            }
        }
        return $second_field;
    }

    public static function recursive_process($field_value, $field_array)
    {
        if (is_array($field_value)) {
            foreach ($field_value as $f_key => $f_value) {
                if (in_array($f_key, $field_array)) {
                    $field_value = $f_value;
                    if (is_array($field_value)) {
                        return self::recursive_process($field_value, $field_array);
                    }
                }
            }
        }
        return $field_value;
    }

    static function validateDateORNull($field, $value, array $params, array $fields)
    {
        if (empty($value) || in_array($value, array('', null, 0, '-'))) { //is_null($value) || $value == 0 || $value === 'null' || $value == '-'
            return true;
        }
        $isDate = false;
        if ($value instanceof \DateTime) {
            $isDate = true;
        } else {
            $isDate = strtotime($value) !== false;
        }
        return $isDate;
    }

    static function validateSearch($field, $value, array $params, array $fields)
    {
        return preg_match('/^([@_.a-z0-9- ])+$/i', $value);
    }

    static function validateAlphaNumWithSpace($field, $value, $params, $fields)
    {
        return preg_match('/^([a-z0-9 ])+$/i', $value);
    }

    static function validateNumber($field, $value, $params, $fields)
    {
        $value = (int)$value;
        return preg_match('/^([0-9])+$/i', $value);
    }

    static function validateAlphaNumWithUnderscore($field, $value, array $params, array $fields)
    {
        return (bool)preg_match('/^[a-zA-Z0-9_-]+$/', $value);
    }

    static function validateInputAlpha($input)
    {
        return preg_replace('/[^A-Za-z0-9_\-]/', '', $input);
    }

    /**
     * validate the conditional values
     * @param $field
     * @param $value
     * @param array $params
     * @param array $fields
     * @return bool
     */
    static function validateCleanHtml($field, $value, array $params, array $fields)
    {
        $html = Woocommerce::getCleanHtml($value);
        $value = str_replace('&amp;', '&', $value);
        $html = str_replace('&amp;', '&', $html);
        if ($html != $value) {
            return false;
        }
        return true;
    }

    /**
     * validate Input Text Html Tags
     *
     * @param $field
     * @param $value
     * @param array $params
     * @param array $fields
     * @return bool
     */
    static function validateBasicHtmlTags($field, $value, array $params, array $fields)
    {
        $value = stripslashes($value);
        $value = html_entity_decode($value);
        $invalid_tags = array("script", "iframe", "style");
        foreach ($invalid_tags as $tag_name) {
            $pattern = "#<\s*?$tag_name\b[^>]*>(.*?)</$tag_name\b[^>]*>#s";;
            preg_match($pattern, $value, $matches);
            //script or iframe found
            if (!empty($matches)) {
                return false;
            }
        }
        return true;
    }

    /**
     * validate Radio Button And Select Box
     *
     * @param $field
     * @param $value
     * @param array $params
     * @param array $fields
     * @return bool
     */
    static function validateRadioButtonAndSelectBox($field, $value, array $params, array $fields)
    {
        $acceptable = array('yes', 'no', 1, '1', true, 0, '0');
        return in_array($value, $acceptable, true);
    }

    static function validateSanitizeText($field, $value, array $params, array $fields)
    {
        $after_value = sanitize_text_field($value);
        $status = false;
        if ($value === $after_value) {
            $status = true;
        }
        return $status;
    }

    static function validateNumberGeZero($field, $value, array $params, array $fields)
    {
        $value = (int)$value;
        if ($value == 0 || empty($value)) {
            return false;
        }
        return filter_var($value, \FILTER_VALIDATE_INT) !== false;
    }

    static function validateOrderAndSpend($field, $value, array $params, array $fields)
    {
        $status = false;
        if (isset($params[0]) && $params[0]) {
            $second_field = self::getSecondFieldValue($params[0], $fields);
            if ((empty($value) && empty($second_field)) || ($value > 0 && $second_field > 0)) {
                $status = true;
            }
        }
        return $status;
    }

    static function validateIsEmpty($field, $value, array $params, array $fields)
    {
        $status = false;
        if (!empty($value)) {
            $status = true;
        }
        return $status;
    }

    static function validateGreaterThen($field, $value, array $params, array $fields)
    {
        $status = false;
        if (isset($params[0]) && $params[0]) {
            $second_field = self::getSecondFieldValue($params[0], $fields);
            if ((int)$value >= (int)$second_field) {
                $status = true;
            }
        }
        return $status;
    }

    static function validateConditions($conditions)
    {
        $conditionParams = array(
            'type' => array('user_role', 'customer', 'language', 'currency', 'user_level', 'products', 'product_attributes',
                'product_category', 'product_sku', 'product_onsale', 'product_tags', 'purchase_history', 'life_time_sale_value', 'usage_limit'),
            'options' => array('value', 'operator', 'condition', 'qty')
        );

    }

    static function validateLimitLength($field, $value, array $params, array $fields)
    {
        $status = false;
        if ((strlen($value) >= 5) && (strlen($value) <= 20)) {
            $status = true;
        }
        return $status;
    }

    static function validateNameLimitLength($field, $value, array $params, array $fields)
    {
        $status = false;
        if (empty($value)) return false;
        if ((strlen($value) > 0) && (strlen($value) <= 170)) {
            $status = true;
        }
        return $status;
    }

    static function validateImage($field, $value, array $params, array $fields)
    {
        $path = parse_url($value, PHP_URL_PATH);
        $encoded_path = array_map('urlencode', explode('/', $path));
        $parse_url = str_replace($path, implode('/', $encoded_path), $value);
        if (!filter_var($parse_url, FILTER_VALIDATE_URL))
            return false;
        $image_url = esc_url($value);
        preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|png|PNG)/', $image_url, $matches);
        return (is_array($matches) && !empty($matches)) ? $matches : false;
    }

    static function validateColor($field, $value, array $params, array $fields)
    {
        $status = false;
        if (preg_match('/^#[a-fA-F0-9]{6}$/i', $value) || preg_match('/^#[a-fA-F0-9]{3}$/i', $value)) {
            $status = true;
        }
        return $status;
    }
}