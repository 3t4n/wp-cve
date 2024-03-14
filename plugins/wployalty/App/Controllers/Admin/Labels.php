<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Controllers\Admin;

defined('ABSPATH') or die;

use Wlr\App\Controllers\Base;
use Wlr\App\Helpers\EarnCampaign;
use Wlr\App\Helpers\Woocommerce;
use Wlr\App\Models\Rewards;

class Labels extends Base
{
    function getPluginLabels()
    {
        $earn_campaign_helper = EarnCampaign::getInstance();
        $json = array(
            'available' => true,
            'plugin_title' => __(WLR_PLUGIN_NAME, 'wp-loyalty-rules'),
            'version' => WLR_PLUGIN_VERSION,
            'common' => array(
                'action_types' => self::$woocommerce->getAllActionTypes(),
                'user_point_types' => array(
                    'available_point' => __('Current point balance', 'wp-loyalty-rules'),
                    'total_earned_point' => __('Total earned points', 'wp-loyalty-rules'),
                    'total_used_point' => __('Total used points', 'wp-loyalty-rules')
                ),
                'achievement_type' => array(
                    array('value' => 'daily_login', 'label' => __('Daily Login'), 'wp-loyalty-rules'),
                    array('value' => 'level_update', 'label' => __('Level Update', 'wp-loyalty-rules')),
                    array('value' => 'custom_action', 'label' => __('Custom Action', 'wp-loyalty-rules')),
                ),
                'unlimited' => __('Unlimited', 'wp-loyalty-rules'),
                'no_expire' => __('No expiry email', 'wp-loyalty-rules'),
                'active' => __('Active', 'wp-loyalty-rules'),
                'in_active' => __('InActive', 'wp-loyalty-rules'),
                'id_asc' => __('Id Asc', 'wp-loyalty-rules'),
                'id_desc' => __('Id Desc', 'wp-loyalty-rules'),
                'name_asc' => __('Name Asc', 'wp-loyalty-rules'),
                'name_desc' => __('Name Desc', 'wp-loyalty-rules'),
                'active_asc' => __('Active Asc', 'wp-loyalty-rules'),
                'active_desc' => __('Active Desc', 'wp-loyalty-rules'),
                'disabled' => __('Disabled', 'wp-loyalty-rules'),
                'edit' => __('Edit', 'wp-loyalty-rules'),
                'delete' => __('Delete', 'wp-loyalty-rules'),
                'adjust_value' => __('Adjust Value', 'wp-loyalty-rules'),
                'hide' => __('Hide', 'wp-loyalty-rules'),
                'show' => __('Show', 'wp-loyalty-rules'),
                'advanced_settings' => __('Advanced Settings', 'wp-loyalty-rules'),
                'select_conditions' => __('Select conditions', 'wp-loyalty-rules'),
                'no_conditions' => __('No conditions found', 'wp-loyalty-rules'),
                'and' => __('and', 'wp-loyalty-rules'),
                'or' => __('or', 'wp-loyalty-rules'),
                'match_all' => __('Match All', 'wp-loyalty-rules'),
                'match_any' => __('Match Any', 'wp-loyalty-rules'),
                'add_condition' => __('Add Conditions', 'wp-loyalty-rules'),
                'back' => __('Back', 'wp-loyalty-rules'),
                'close' => __('Close', 'wp-loyalty-rules'),
                'update_email_template' => __('Update email template', 'wp-loyalty-rules'),
                'save' => __('Save', 'wp-loyalty-rules'),
                'save_close' => __('Save & Close', 'wp-loyalty-rules'),
                'cancel' => __('Cancel', 'wp-loyalty-rules'),
                'premium' => __('Upgrade to Pro', 'wp-loyalty-rules'),
                'premium_msg' => __('This feature is only available for pro user', 'wp-loyalty-rules'),
                'invalid_date' => __('Invalid Date', 'wp-loyalty-rules'),
                'check_here' => __('Check Here', 'wp-loyalty-rules'),
                'reward_used' => __('Can\'t edit. Reward has Used.', 'wp-loyalty-rules'),
                'reward_expired' => __('Can\'t edit. Reward has expired.', 'wp-loyalty-rules'),
                'reward_unlimited' => __('Can\'t edit. This reward has unlimited validity.', 'wp-loyalty-rules'),
                'documentation' => __('Documentation', 'wp-loyalty-rules'),
                'documentation_url' => 'https://docs.wployalty.net/',
                'video_label' => __('Video Tutorials', 'wp-loyalty-rules'),
                'video_link' => 'https://wployalty.net/video-tutorials/?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=video-tutorials',
                'all_video_links' => array(
                    'point_users' => 'https://wployalty.net/video/customers_points?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=point_users',
                    'campaigns' => array(
                        'point_for_purchase' => 'https://wployalty.net/video/campaign/point_for_purchase?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=point_for_purchase',
                        'subtotal' => 'https://wployalty.net/video/campaign/subtotal?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=subtotal',
                        'referral' => 'https://wployalty.net/video/campaign/referral?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=referral',
                        'signup' => 'https://wployalty.net/video/campaign/signup?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=signup',
                        'product_review' => 'https://wployalty.net/video/campaign/product_review?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=product_review',
                        'birthday' => 'https://wployalty.net/video/campaign/birthday?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=birthday',
                        'facebook_share' => 'https://wployalty.net/video/campaign/facebook_share?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=facebook_share',
                        'twitter_share' => 'https://wployalty.net/video/campaign/twitter_share?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=twitter_share',
                        'whatsapp_share' => 'https://wployalty.net/video/campaign/whatsapp_share?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=whatsapp_share',
                        'email_share' => 'https://wployalty.net/video/campaign/email_share?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=email_share',
                        'followup_share' => 'https://wployalty.net/video/campaign/followup_share?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=followup_share',
                        'achievement' => 'https://wployalty.net/video/campaign/achievement?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=achievement',
                    ),
                    'rewards' => array(
                        'points_conversion' => 'https://wployalty.net/video/reward/points_conversion?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=points_conversion',
                        'fixed_cart' => 'https://wployalty.net/video/reward/fixed_cart?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=fixed_cart',
                        'percent' => 'https://wployalty.net/video/reward/percent?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=percent',
                        'free_product' => 'https://wployalty.net/video/reward/free_product?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=free_product',
                        'free_shipping' => 'https://wployalty.net/video/reward/free_shipping?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=free_shipping',
                    ),
                    'levels' => 'https://wployalty.net/video/user_level?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=levels',
                    'settings' => array(
                        //'display_message' => 'https://wployalty.net/setting/display_message?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=display_message',
                        //'customer_reward_page' => 'https://wployalty.net/setting/customer_reward_page?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=customer_reward_page',
                        'emails' => 'https://wployalty.net/setting/emails?utm_campaign=wployalty_in_app_tutorial_campaign&utm_source=inapp&utm_medium=emails',
                    ),
                ),
                'schedule_call_link' => 'https://zcal.co/rameshelamathi/wployalty',
                'support_link' => 'https://wployalty.net/support/?utm_campaign=wployalty-link&utm_medium=on_boarding&utm_source=support',
                'search' => __('Search', 'wp-loyalty-rules'),
                'copy' => __('Copied!', 'wp-loyalty-rules'),
                'hidden' => __('Hidden', 'wp-loyalty-rules'),
                'visible' => __('Visible', 'wp-loyalty-rules'),
                'choose' => __('Choose', 'wp-loyalty-rules'),
                'format' => __('The CSV file format is not correct. Please refer the documentation for correct CSV format', 'wp-loyalty-rules'),
                'view_all' => __('View All', 'wp-loyalty-rules'),
                'recent_activities' => __('Recent Activities', 'wp-loyalty-rules'),
                'actions' => __('Actions', 'wp-loyalty-rules'),
                'learn_more' => __('Learn more', 'wp-loyalty-rules'),
                'conditions' => __('Conditions', 'wp-loyalty-rules'),
                'save_changes' => __('Save Changes', 'wp-loyalty-rules'),
                'previous' => __('Prev', 'wp-loyalty-rules'),
                'next' => __('Next', 'wp-loyalty-rules'),
                'copy_text' => __('Copy', 'wp-loyalty-rules'),
                'dash_character' => __('-', 'wp-loyalty-rules'),
                'search_order_number' => __('Search order number', 'wp-loyalty-rules'),
                'search_reward_code' => __('Search reward code', 'wp-loyalty-rules'),
                'earned_redeemed' => __('(Redeemed / Earned)', 'wp-loyalty-rules'),
                'points_text' => __('Points', 'wp-loyalty-rules'),
                'active_all' => __('All', 'wp-loyalty-rules'),
                'inactive' => __('Inactive', 'wp-loyalty-rules'),
                'filter' => __('Filter :', 'wp-loyalty-rules'),
                'sort_by' => __('Sort By :', 'wp-loyalty-rules'),
                'click_to_copy' => __("click to copy", 'wp-loyalty-rules'),
                'view' => __("view", 'wp-loyalty-rules'),
                'delete_text' => __("delete", 'wp-loyalty-rules'),
                'select_text' => __("select", 'wp-loyalty-rules'),
                'edit_text' => __("edit", 'wp-loyalty-rules'),
                'update_text' => __("Update", 'wp-loyalty-rules'),
                'show_tooltip' => __("click to show", 'wp-loyalty-rules'),
                'hide_tooltip' => __("click to hide", 'wp-loyalty-rules'),
                'select_media' => __("Select media", 'wp-loyalty-rules'),
                'activate' => __('Activate', 'wp-loyalty-rules'),
                'deactivate' => __('Deactivate', 'wp-loyalty-rules'),
                'point' => __('point', 'wp-loyalty-rules'),
                'coupon' => __('coupon', 'wp-loyalty-rules'),
                'level_ascending' => __('Level ASC', 'wp-loyalty-rules'),
                'level_descending' => __('Level DESC', 'wp-loyalty-rules'),
                'email_descending' => __('Email DESC', 'wp-loyalty-rules'),
                'email_ascending' => __('Email ASC', 'wp-loyalty-rules'),
                'point_ascending' => __('Point ASC', 'wp-loyalty-rules'),
                'point_descending' => __('Point DESC', 'wp-loyalty-rules'),
                'no_record_found' => __('No records found', 'wp-loyalty-rules'),
                'no_data_text' => __('No data available', 'wp-loyalty-rules'),
                'reset' => __('Reset', 'wp-loyalty-rules'),
                'show_more' => __('Show more', 'wp-loyalty-rules'),
                'show_less' => __('Show less', 'wp-loyalty-rules'),
                'duplicate_text' => __('copy', 'wp-loyalty-rules'),
                'shortcodes_text' => __('Short codes', 'wp-loyalty-rules'),
                'pro_text' => __('Pro', 'wp-loyalty-rules'),
                'loading_text' => __('Loading... ,If loading takes a while, please refresh the screen...!', 'wp-loyalty-rules'),
                'enable_send_email' => __('Email Opt-in', 'wp-loyalty-rules'),
                'command_label' => __('Comment: ', 'wp-loyalty-rules'),
                'id_text' => __('ID: ', 'wp-loyalty-rules'),
                'created_date_text' => __('Created Date: ', 'wp-loyalty-rules'),
                'include_end_date_text' => __('Include end date', 'wp-loyalty-rules'),
                'referral_code_text' => __('Referral Code', 'wp-loyalty-rules'),
                'balance_text' => __('Balance', 'wp-loyalty-rules'),
                'earned_text' => __('Earned', 'wp-loyalty-rules'),
                'redeemed_text' => __('Redeemed', 'wp-loyalty-rules'),
                'watch_text' => __('watch', 'wp-loyalty-rules'),
                'description_text' => __('Description', 'wp-loyalty-rules'),
                'set_points_text' => __('Set points', 'wp-loyalty-rules'),
                'set_points_desc' => __('Points to be earned by this campaign.', 'wp-loyalty-rules'),
                'discount_value_text' => __('Discount value', 'wp-loyalty-rules'),
                'done_text' => __('Done', 'wp-loyalty-rules'),
                'leave_popup_title' => __('Are you sure want to leave?', 'wp-loyalty-rules'),
                'leave_popup_message' => __('Make sure you want to stop this process?', 'wp-loyalty-rules'),
                'leave_popup_ok_button_text' => __('Yes, Exit', 'wp-loyalty-rules'),
                'leave_popup_cancel_button_text' => __('No, Keep', 'wp-loyalty-rules'),

                'ban_popup_ok_button_text' => __('Yes, Ban', 'wp-loyalty-rules'),
                'unban_popup_ok_button_text' => __('Yes, Unban', 'wp-loyalty-rules'),
                'ban_popup_title' => __('Ban User', 'wp-loyalty-rules'),
                'ban_popup_message' => __('Are you sure want to Ban this user?', 'wp-loyalty-rules'),
                'unban_popup_message' => __('Are you sure want to Unban this user?', 'wp-loyalty-rules'),

                'op_tin_email_popup_ok_button_text' => __('Yes, Opt-in', 'wp-loyalty-rules'),
                'op_tout_email_popup_ok_button_text' => __('Yes, Opt-out', 'wp-loyalty-rules'),
                'op_tin_email_popup_title' => __('Email Opt-in', 'wp-loyalty-rules'),
                'op_tin_email_popup_message' => __('Make sure you want to Enable Email Opt-in?', 'wp-loyalty-rules'),
                'op_tout_email_popup_message' => __('Make sure you want to Disable Email Opt-in?', 'wp-loyalty-rules'),
                'error' => array(
                    'required_message' => __('Please complete all required fields.', 'wp-loyalty-rules'),
                    'greater_message' => __('Should be greater than 0', 'wp-loyalty-rules'),
                    'percentage_message' => __('Discount value should be less than 100.', 'wp-loyalty-rules'),
                    'theme_required' => __('Theme color is required.', 'wp-loyalty-rules'),
                    'min_three' => __('Minimum 3 characters required.', 'wp-loyalty-rules'),
                    'enter_4_or_6' => __('Enter 4 or 6 characters.', 'wp-loyalty-rules'),
                    'max_6' => __('Maximum 6 characters only allowed.', 'wp-loyalty-rules'),
                ),
                'select' => array(
                    'reward_point' => __('Reward for Points', 'wp-loyalty-rules'),
                    'reward_coupon' => __('Reward as a coupon immediately', 'wp-loyalty-rules'),
                    'this_month' => __('This Month', 'wp-loyalty-rules'),
                    'last_month' => __('Last Month', 'wp-loyalty-rules'),
                    'ninety_days' => __('90 Days', 'wp-loyalty-rules'),
                    'last_year' => __('Last Year', 'wp-loyalty-rules'),
                    'custom' => __('Custom', 'wp-loyalty-rules'),
                    'days' => __('Day(s)', 'wp-loyalty-rules'),
                    'weeks' => __('Week(s)', 'wp-loyalty-rules'),
                    'months' => __('Month(s)', 'wp-loyalty-rules'),
                    'years' => __('Year(s)', 'wp-loyalty-rules'),
                    'point_txt' => __('Points', 'wp-loyalty-rules'),
                    'coupon_txt' => __('Coupon reward', 'wp-loyalty-rules'),
                    'on_their_birthday' => __('On their birthday', 'wp-loyalty-rules'),
                    'when_providing_birthday' => __('When providing birthday date', 'wp-loyalty-rules'),
                    'fixed_point' => __('Fixed Points', 'wp-loyalty-rules'),
                    'sub_percentage' => __('Percentage of the referral sale value', 'wp-loyalty-rules'),
                    'all' => __('Both list and single product pages', 'wp-loyalty-rules'),
                    'hide' => __('Hide the message', 'wp-loyalty-rules'),
                    'list' => __('Product List Pages only', 'wp-loyalty-rules'),
                    'single' => __('Single Product Pages only', 'wp-loyalty-rules'),
                    'yes' => __('Yes', 'wp-loyalty-rules'),
                    'no' => __('No', 'wp-loyalty-rules'),
                    'in_list' => __('In list', 'wp-loyalty-rules'),
                    'not_in_list' => __('Not in list', 'wp-loyalty-rules'),
                    'less_than' => __('Less than', 'wp-loyalty-rules'),
                    'less_than_or_equal' => __('Less than or equal', 'wp-loyalty-rules'),
                    'greater_than_or_equal' => __('Greater than or equal', 'wp-loyalty-rules'),
                    'greater_than' => __('Greater than', 'wp-loyalty-rules'),
                    'all_item_count' => __('All item count', 'wp-loyalty-rules'),
                    'all_item_qty' => __('All item quantity', 'wp-loyalty-rules'),
                    'each_item_qty' => __('Each item quantity', 'wp-loyalty-rules'),
                    'all_item_weight' => __('All item weight', 'wp-loyalty-rules'),
                    'each_item_weight' => __('Each item weight', 'wp-loyalty-rules'),
                    'round' => __('Round to nearest integer', 'wp-loyalty-rules'),
                    'round_floor' => __('Always round down', 'wp-loyalty-rules'),
                    'ceil' => __('Always round up', 'wp-loyalty-rules'),
                    'no_icon' => __('No Icon', 'wp-loyalty-rules'),
                    'position_floor' => __('Icon display before label', 'wp-loyalty-rules'),
                    'type_instruction' => __('Please enter 2 or more character', 'wp-loyalty-rules'),
                    'search_products' => __('Search Products', 'wp-loyalty-rules'),
                    'no_products' => __('No Products Available', 'wp-loyalty-rules'),
                    'search_customers' => __('Search customers', 'wp-loyalty-rules'),
                    'no_customers' => __('No Customers Available', 'wp-loyalty-rules'),
                    'search_product_attributes' => __('Search Product Attributes', 'wp-loyalty-rules'),
                    'no_product_attributes' => __('No Product Attributes Available', 'wp-loyalty-rules'),
                    'search_product_tags' => __('Search Product tags', 'wp-loyalty-rules'),
                    'no_product_tags' => __('No Product tags Available', 'wp-loyalty-rules'),
                    'search_product_categories' => __('Search Product categories', 'wp-loyalty-rules'),
                    'no_product_categories' => __('No Product categories Available', 'wp-loyalty-rules'),
                    'search_product_sku' => __('Search Product SKU', 'wp-loyalty-rules'),
                    'no_product_sku' => __('No Product SKU Available', 'wp-loyalty-rules'),
                    'select_coupon' => __('Select Coupon', 'wp-loyalty-rules'),
                    'select_user_level' => __('Select customer level', 'wp-loyalty-rules'),
                    'select_status' => __('Select status', 'wp-loyalty-rules'),
                    'select_product_action' => __('Select product action', 'wp-loyalty-rules'),
                    'all_time' => __('All Time', 'wp-loyalty-rules'),
                    'two_years' => __('2 Years', 'wp-loyalty-rules'),
                    'add' => sprintf(__('Add %s', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel(3)),
                    'sub' => sprintf(__('Subtract %s', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel(3)),
                    'equal' => sprintf(__('Overwrite %s', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel(3)),
                    'inherit' => __('Inherit from WooCommerce', 'wp-loyalty-rules'),
                    'including' => __('Including Tax', 'wp-loyalty-rules'),
                    'excluding' => __('Excluding Tax', 'wp-loyalty-rules')
                )
            ),
            'calculate_point' => array(
                'before_discount' => __('before discount', 'wp-loyalty-rules'),
                'after_discount' => __('after discount', 'wp-loyalty-rules'),
            ),

            /*---------- conditions labels start here---------*/

            'conditions' => array(
                'user_role' => array(
                    'name' => __('User Role', 'wp-loyalty-rules'),
                    'condition' => __('User roles should be ', 'wp-loyalty-rules'),
                    'role' => __('Select user role(s)', 'wp-loyalty-rules'),
                ),
                'user_point' => array(
                    'name' => __('Customer Points', 'wp-loyalty-rules'),
                    'condition' => __('Choose the conditional operator', 'wp-loyalty-rules'),
                    'type' => __('Choose the condition', 'wp-loyalty-rules'),
                    'value' => __('Points value', 'wp-loyalty-rules'),
                ),
                'customer' => array(
                    'name' => __('WPLoyalty Customer', 'wp-loyalty-rules'),
                    'condition' => __('Customers should be ', 'wp-loyalty-rules'),
                    'customers' => __('Select customer(s)', 'wp-loyalty-rules'),
                ),
                'language' => array(
                    'name' => __('Language', 'wp-loyalty-rules'),
                    'condition' => __('Select language', 'wp-loyalty-rules'),
                ),
                'currency' => array(
                    'name' => __('Currency', 'wp-loyalty-rules'),
                    'condition' => __('Select currency', 'wp-loyalty-rules'),
                ),
                'user_level' => array(
                    'name' => __('Customer Level', 'wp-loyalty-rules'),
                    'condition' => __('Select customer level', 'wp-loyalty-rules'),
                ),
                'cart_subtotal' => array(
                    'name' => __('Cart Subtotal', 'wp-loyalty-rules'),
                    'value_condition' => __('Cart subtotal should be ', 'wp-loyalty-rules'),
                    'value' => __('Cart subtotal amount', 'wp-loyalty-rules'),
                ),
                'cart_line_item' => array(
                    'name' => __('Cart Line Item Count', 'wp-loyalty-rules'),
                    'condition' => __('Cart should be ', 'wp-loyalty-rules'),
                    'value_condition' => __('Cart quantity should be ', 'wp-loyalty-rules'),
                    'all_item_count' => __('All item count ', 'wp-loyalty-rules'),
                    'all_item_quantity' => __('All item quantity', 'wp-loyalty-rules'),
                    'each_item_quantity' => __('Each item quantity', 'wp-loyalty-rules'),
                ),
                'cart_weight' => array(
                    'name' => __('Cart Weight', 'wp-loyalty-rules'),
                    'condition' => __('Cart should be ', 'wp-loyalty-rules'),
                    'value_condition' => __('Cart weight should be ', 'wp-loyalty-rules'),
                    'all_item_weight' => __('All item weight', 'wp-loyalty-rules'),
                    'each_item_weight' => __('Each item weight', 'wp-loyalty-rules'),
                ),
                'product' => array(
                    'name' => __('Products', 'wp-loyalty-rules'),
                    'condition' => __('Product should be ', 'wp-loyalty-rules'),
                    'product' => __('Select Product(s) ', 'wp-loyalty-rules'),
                    'value_condition' => __('Product(s) quantity should be ', 'wp-loyalty-rules'),
                    'value' => __('Product(s) quantity', 'wp-loyalty-rules'),
                ),
                'product_attributes' => array(
                    'name' => __('Product Attributes', 'wp-loyalty-rules'),
                    'condition' => __('Product attribute should be ', 'wp-loyalty-rules'),
                    'product' => __('Select Product attribute(s) ', 'wp-loyalty-rules'),
                    'value_condition' => __('Product attribute(s) quantity should be ', 'wp-loyalty-rules'),
                    'value' => __('Product attribute(s) quantity', 'wp-loyalty-rules'),
                ),
                'product_category' => array(
                    'name' => __('Product Category', 'wp-loyalty-rules'),
                    'condition' => __('Product category should be ', 'wp-loyalty-rules'),
                    'product' => __('Select Product category(s) ', 'wp-loyalty-rules'),
                    'value_condition' => __('Product category(s) quantity should be ', 'wp-loyalty-rules'),
                    'value' => __('Product category(s) quantity', 'wp-loyalty-rules'),
                ),
                'product_sku' => array(
                    'name' => __('Product SKU', 'wp-loyalty-rules'),
                    'condition' => __('Product SKU should be ', 'wp-loyalty-rules'),
                    'product' => __('Select Product SKU ', 'wp-loyalty-rules'),
                    'value_condition' => __('Product SKU quantity should be ', 'wp-loyalty-rules'),
                    'value' => __('Product SKU quantity', 'wp-loyalty-rules'),
                ),
                'product_tag' => array(
                    'name' => __('Product Tags', 'wp-loyalty-rules'),
                    'condition' => __('Product tag should be ', 'wp-loyalty-rules'),
                    'product' => __('Select Product tag(s) ', 'wp-loyalty-rules'),
                    'value_condition' => __('Product tag(s) quantity should be ', 'wp-loyalty-rules'),
                    'value' => __('Product tag(s) quantity', 'wp-loyalty-rules'),
                ),
                'product_on_sale' => array(
                    'name' => __('Product On Sale', 'wp-loyalty-rules'),
                    'condition' => __('Product on sale should be ', 'wp-loyalty-rules'),
                ),
                'payment' => array(
                    'name' => __('Payments', 'wp-loyalty-rules'),
                    'condition' => __('Payment should be ', 'wp-loyalty-rules'),
                    'payment' => __('Select payment method(s) ', 'wp-loyalty-rules'),
                ),
                'order_status' => array(
                    'name' => __('Order Status', 'wp-loyalty-rules'),
                    'condition' => __('Order status should be ', 'wp-loyalty-rules'),
                    'status' => __('Select order status ', 'wp-loyalty-rules'),
                ),
                'purchase_history' => array(
                    'name' => __('Purchase History', 'wp-loyalty-rules'),
                    'condition' => __('Purchase history should be ', 'wp-loyalty-rules'),
                    'value' => __('Purchase history count', 'wp-loyalty-rules'),
                    'status' => __('Select purchase status ', 'wp-loyalty-rules'),
                ),
                'purchase_history_qty' => array(
                    'name' => __('Purchase History Quantity', 'wp-loyalty-rules'),
                    'condition' => __('Purchase history quantity should be ', 'wp-loyalty-rules'),
                    'value' => __('Purchase history quantity value', 'wp-loyalty-rules'),
                    'status' => __('Select purchase status ', 'wp-loyalty-rules'),
                    'time' => __('Purchase time should be', 'wp-loyalty-rules'),
                ),
                'lifetime_sale_value' => array(
                    'name' => __('Lifetime Sale Value', 'wp-loyalty-rules'),
                    'condition' => __('Sale value should be ', 'wp-loyalty-rules'),
                    'value' => __('Sale value', 'wp-loyalty-rules'),
                    'status' => __('Sale value status ', 'wp-loyalty-rules'),
                ),
                'usage_limits' => array(
                    'name' => __('Campaign usage limit per customer', 'wp-loyalty-rules'),
                    'value_condition' => __('Usage limits should be ', 'wp-loyalty-rules'),
                    'value' => __("Use this condition to limit the number of times a campaign can reward a customer. Useful when you are giving bonus rewards / one-time rewards like: $50 instant coupon if customer's life time spend crosses $1000.", 'wp-loyalty-rules'),
                ),
                'first_order' => __('First order', 'wp-loyalty-rules'),
                'last_order' => __('Last order', 'wp-loyalty-rules'),
                'last_order_amount' => __('Last order amount', 'wp-loyalty-rules'),
                'number_of_orders_made' => __('Number of orders made ', 'wp-loyalty-rules'),
                'number_of_orders_made_with_amount' => __('Number of orders with order value or count ', 'wp-loyalty-rules'),
                'number_of_orders_made_products' => __('Number of orders made with following products ', 'wp-loyalty-rules'),
                'number_of_quantities_made_products' => __('Number of quantities made with following products', 'wp-loyalty-rules'),
                'total_spent' => __('Total spent', 'wp-loyalty-rules'),

                'order_amount_should' => __('Order amount should be', 'wp-loyalty-rules'),
                'order_amount' => __('Order amount', 'wp-loyalty-rules'),
                'order_time' => __('Order time', 'wp-loyalty-rules'),
                'purchase_before' => __('Order period', 'wp-loyalty-rules'),
                'order_quantity_should' => __('Order quantity should', 'wp-loyalty-rules'),
                'order_should' => __('Order should be', 'wp-loyalty-rules'),
                'purchase_count' => __('Order count of the customer should be', 'wp-loyalty-rules'),
                'purchased_quantity' => __('Purchased quantity', 'wp-loyalty-rules'),
                'purchase_quantity' => __('Purchase quantity', 'wp-loyalty-rules'),
                'search_product' => __('Search product', 'wp-loyalty-rules'),
                'purchased_quantity_should' => __('Purchased quantity should be', 'wp-loyalty-rules'),
                'purchased_amount_should' => __('Purchased amount should be', 'wp-loyalty-rules'),
                'amount' => __('Amount', 'wp-loyalty-rules'),
                'is_first_order' => __('Is first order?', 'wp-loyalty-rules'),
                'min_amount' => __('Min amount', 'wp-loyalty-rules'),
                'max_amount' => __('Max amount', 'wp-loyalty-rules'),
            ),


            'dashboard' => array(
                'name' => __('Dashboard', 'wp-loyalty-rules'),
                'from_date' => __('From Date', 'wp-loyalty-rules'),
                'to_date' => __('To Date', 'wp-loyalty-rules'),
                'get_result' => __('Get Result', 'wp-loyalty-rules'),
//                'rewards' => __('Rewards', 'wp-loyalty-rules'),
//                'points' => __('Points', 'wp-loyalty-rules'),
                'revenue_txt' => __('Revenue', 'wp-loyalty-rules'),
                'no_of_orders' => array(
                    'name' => __('Number Of Orders', 'wp-loyalty-rules'),
                    'description' => __('Number of orders made by customers.', 'wp-loyalty-rules'),
                ),
                'order_total' => array(
                    'name' => __('Total value of Orders', 'wp-loyalty-rules'),
                    'description' => __('Total value of the orders placed by customers', 'wp-loyalty-rules'),
                ),
                'tot_points' => array(
                    'name' => __('Total points', 'wp-loyalty-rules'),
                    'description' => __('Total number of points awarded to customers from all campaigns.', 'wp-loyalty-rules'),
                ),
                'tot_rewards' => array(
                    'name' => __('Total Rewards', 'wp-loyalty-rules'),
                    'description' => __('All claimed rewards by the customers.', 'wp-loyalty-rules'),
                ),
                'tot_rewards_redeemed' => array(
                    'name' => __('Total value Redeemed', 'wp-loyalty-rules'),
                    'description' => __('Includes the value of the total redeemed rewards.', 'wp-loyalty-rules'),
                ),
                'revenue' => array(
//                    'name' => __('Revenue', 'wp-loyalty-rules'),
                    'description' => __('Revenue generated by customers in the loyalty rewards program.', 'wp-loyalty-rules'),
                ),
                'points_reward' => array(
                    'name' => __('Points and Rewards', 'wp-loyalty-rules'),
                    'description' => __('Points and rewards earned by customers', 'wp-loyalty-rules'),
                ),
                /*  ---------------------------------design------------------------------------- */
            ),

            'manage_points' => array(
                'important_label' => __('IMPORTANT:', 'wp-loyalty-rules'),
                'import_notice' => __('The CSV should be formatted with the following columns exactly: email, points, referral_code, comment (If not formatted correctly, it will not be imported)', 'wp-loyalty-rules'),
                'name' => __('Customers', 'wp-loyalty-rules'),
                'import_txt' => __('Import', 'wp-loyalty-rules'),
                'export_txt' => __('Export', 'wp-loyalty-rules'),
                'exported_txt' => __('Show Exported File', 'wp-loyalty-rules'),
                'add_new_customer_txt' => __('Add New Customer', 'wp-loyalty-rules'),
                'no_customers' => __('No Customers to display', 'wp-loyalty-rules'),
                'customers' => __('customer(s)', 'wp-loyalty-rules'),
//                'available_points' => __('Points', 'wp-loyalty-rules'),
                'earned_rewards' => __('Rewards Earned', 'wp-loyalty-rules'),
                'used_rewards' => __('Rewards Redeemed', 'wp-loyalty-rules'),
                'levels_image' => __('Level', 'wp-loyalty-rules'),
                'referral_url' => __('Referral URL', 'wp-loyalty-rules'),
//                'referral_code' => __('Referral Code', 'wp-loyalty-rules'),
                'birthday' => __('Birthday', 'wp-loyalty-rules'),
//                'search' => __('Search', 'wp-loyalty-rules'),
//                'update' => __('Update', 'wp-loyalty-rules'),
                'search_customer' => __('Search customers', 'wp-loyalty-rules'),
                'ban_user_text' => __('Ban User', 'wp-loyalty-rules'),
                /* --------------------2.0------------------------------------- */
                'import_customers' => __('Import Customers', 'wp-loyalty-rules'),
                'export_customers' => __('Export Customers', 'wp-loyalty-rules'),
                'download_exports' => __('Download Exports', 'wp-loyalty-rules'),
                'update_points' => __('Update Points', 'wp-loyalty-rules'),
                'delete_alert_message' => __('Are you sure want to delete this customer?', 'wp-loyalty-rules'),
                'delete_multi_alert_message' => __('Are you sure want to delete this selected customer?', 'wp-loyalty-rules'),
                "delete_ok" => __('Yes, Delete Customer', 'wp-loyalty-rules'),
                "delete_cancel" => __('No, Keep Customer', 'wp-loyalty-rules'),
                "delete_customer" => __('Delete Customer?', 'wp-loyalty-rules'),
                "statictics" => __('Statistics', 'wp-loyalty-rules'),
                "no_customers_description" => __('The search did not yield any results', 'wp-loyalty-rules'),
                "empty_revenue_value" => __('$0', 'wp-loyalty-rules'),
                'empty' => array(
                    'name' => __('Do you want to add new customers?', 'wp-loyalty-rules'),
                    'description' => __('View all customer related data like their names, points balance, rewards, levels, birthday', 'wp-loyalty-rules'),
                ),
                'progress' => array(
                    'upload' => __('Upload', 'wp-loyalty-rules'),
                    'preview' => __('Preview', 'wp-loyalty-rules'),
                    'completed' => __('Completed', 'wp-loyalty-rules'),
//                    'next' => __('Next', 'wp-loyalty-rules'),
//                    'done' => __('Done', 'wp-loyalty-rules'),
                    'total' => __('Total Items', 'wp-loyalty-rules'),
                    'processed' => __('Processed Items', 'wp-loyalty-rules'),
                    'field_value' => __('Field Value', 'wp-loyalty-rules'),
                    'field_name' => __('Field Name', 'wp-loyalty-rules'),
                    'link' => __('Link', 'wp-loyalty-rules'),
                    'download' => __('Download', 'wp-loyalty-rules'),
                ),
                'import' => array(
//                    'name' => __('Import Customers', 'wp-loyalty-rules'),
                    'update_points' => __('Update the points when customer already exists', 'wp-loyalty-rules'),
                    'records_per_batch' => __('How many records to import per batch', 'wp-loyalty-rules'),
                    'drag_drop' => __('Drag and Drop or', 'wp-loyalty-rules'),
                    'upload_data' => __('to upload your data', 'wp-loyalty-rules'),
                    'choose_file' => __('Choose file', 'wp-loyalty-rules'),
                    'choose_another_file' => __('Choose Another file', 'wp-loyalty-rules'),
                    'replace' => __('to replace this file', 'wp-loyalty-rules'),
                    'added' => __('Added', 'wp-loyalty-rules'),
                    'points_update_type' => __('How do you want update customers points by type', 'wp-loyalty-rules'),

                ),
//                'export' => array(
////                    'name' => __('Export Customers', 'wp-loyalty-rules'),
//                ),
//                'exported' => array(
//                    'name' => __('Download Exports', 'wp-loyalty-rules'),
//                ),
                'add_new_customer' => array(
//                    'name' => __('Add New Customer', 'wp-loyalty-rules'),
                    'email' => array(
                        'name' => __('Email', 'wp-loyalty-rules'),
                        'placeholder' => __('Enter customer Email here', 'wp-loyalty-rules'),
                    ),
//                    'points' => __('Points', 'wp-loyalty-rules'),
                    'comments' => __('Comments', 'wp-loyalty-rules'),
                ),

                'point_update' => array(
                    'name' => __('Add Customer Point', 'wp-loyalty-rules'),
                    'action_type_title' => __('Action', 'wp-loyalty-rules'),
                    'action_type_description' => __('Choose the action', 'wp-loyalty-rules'),
//                    'point_title' => __('Points', 'wp-loyalty-rules'),
                    'point_description' => __('Enter the points needs to be update with customer points', 'wp-loyalty-rules'),
//                    'comments_title' => __('Comments', 'wp-loyalty-rules'),
                    'comments_description' => __('Enter the Comments', 'wp-loyalty-rules'),

                ),
                'user_details' => array(
                    'name' => __('Customer Details', 'wp-loyalty-rules'),
                    'customer_point_details' => array(
                        'name' => __('Customer Point Details', 'wp-loyalty-rules'),
                        'points_earned' => __('Point Balance', 'wp-loyalty-rules'),
//                        'rewards_earned' => __('Rewards Earned', 'wp-loyalty-rules'),
                        'rewards_used' => __('Rewards Used', 'wp-loyalty-rules'),
                        'revenue' => __('Reward Value', 'wp-loyalty-rules'),
                        'total_earned' => __('Total Earned', 'wp-loyalty-rules')
                    ),
//                    'customer_details' => array(
//                        'name' => __('Customer Details', 'wp-loyalty-rules'),
//                        'email' => __('Email', 'wp-loyalty-rules'),
//                        'referral_code' => __('Referral Code', 'wp-loyalty-rules'),
//                    ),
                    'transaction' => array(
                        'name' => __('Transaction Details', 'wp-loyalty-rules'),
                        'type' => __('Type', 'wp-loyalty-rules'),
                        'no_transaction' => __('No Transactions Available', 'wp-loyalty-rules'),
                        'sno' => __('S.No', 'wp-loyalty-rules'),
                        'order_no' => __('Order No', 'wp-loyalty-rules'),
                        'purchase_value' => __('Purchase Value', 'wp-loyalty-rules'),
                        'action_name' => __('Action Name', 'wp-loyalty-rules'),
                        'points_earned' => __('Points Earned', 'wp-loyalty-rules'),
//                        'rewards_earned' => __('Rewards Earned', 'wp-loyalty-rules'),
                    ),
                    'reward' => array(
                        'name' => __('Reward Details', 'wp-loyalty-rules'),
                        'sno' => __('S.No', 'wp-loyalty-rules'),
                        'reward_name' => __('Reward Name', 'wp-loyalty-rules'),
                        'code' => __('Reward Code', 'wp-loyalty-rules'),
                        'reward_status' => __('Status', 'wp-loyalty-rules'),
                        'expiry' => __('Expiry', 'wp-loyalty-rules'),
                        'expiry_date' => __('Expiry Date', 'wp-loyalty-rules'),
                        'expiry_email' => __('Expiry Email', 'wp-loyalty-rules'),
                        'no_reward' => __('No Rewards Claimed', 'wp-loyalty-rules'),

                    ),
                    'activity' => array(
                        'name' => __('Activity', 'wp-loyalty-rules'),
                        'no_activity' => __('No Activities found', 'wp-loyalty-rules'),
                    ),
                ),


            ),

            'earn_campaign' => array(
                'name' => __('Campaigns', 'wp-loyalty-rules'),
//                'choose_campaign_button' => __('Choose', 'wp-loyalty-rules'),
                'add_new_campaign_button' => __('Choose your campaign type', 'wp-loyalty-rules'),
                'add_new_campaign_description' => __('You can reward customers for purchases, sign up, writing reviews, social sharing, referring their friends and more. Choose a type to get started. (You can create more than one reward campaign)', 'wp-loyalty-rules'),
                'empty_title' => __('Ready to launch your loyalty rewards program?', 'wp-loyalty-rules'),
                'empty_supporting_text' => __('Drive repeat purchases and build loyalty by rewarding your customers. Create a new campaign to get started', 'wp-loyalty-rules'),
                'no_campaign_title' => __('No campaigns found!', 'wp-loyalty-rules'),
                'no_campaign_text' => __('No campaigns found in this name, Create new Campaign.', 'wp-loyalty-rules'),
//                'add_this_campaign_button' => __('Create new campaign', 'wp-loyalty-rules'),
                'campaign_type' => __('Campaign Type', 'wp-loyalty-rules'),
                'search' => __('Search campaign', 'wp-loyalty-rules'),
                'campaign_created' => __('Campaign created', 'wp-loyalty-rules'),
                /* -------------------------------------design ------------------------------- */
                'create' => __('Create Campaign', 'wp-loyalty-rules'),
                'edit_campaign' => __('Edit Campaign', 'wp-loyalty-rules'),
                'create_new_campaign' => __('Create New Campaign', 'wp-loyalty-rules'),
                'campaign_name' => __('Campaign name', 'wp-loyalty-rules'),
                'campaign_description' => __('Campaign description', 'wp-loyalty-rules'),
                'conditional_rules' => __('Conditional Rules', 'wp-loyalty-rules'),
                'optional' => __('(Optional)', 'wp-loyalty-rules'),
                'delete_alert_message' => __('Are you sure want to delete this campaign?', 'wp-loyalty-rules'),
                'delete_multi_alert_message' => __('Are you sure want to delete this selected campaign?', 'wp-loyalty-rules'),
                'delete_ok' => __('Yes, Delete Campaign', 'wp-loyalty-rules'),
                'delete_cancel' => __('No, Keep Campaign', 'wp-loyalty-rules'),
                'delete_campaign' => __('Delete Campaign?', 'wp-loyalty-rules'),
                'valid_till' => __('Valid Till', 'wp-loyalty-rules'),
                'achievement_campaign_name' => __('Achievement Campaign', 'wp-loyalty-rules'),
                'social_share_title' => __('Please note: Customers will be credited with points / rewards as soon as they click the social share options from your rewards / my account page.', 'wp-loyalty-rules'),
                'campaign_title_text' => __('Give a name to your Campaign', 'wp-loyalty-rules'),
                'campaign_description_text' => __('Give a description to your Campaign', 'wp-loyalty-rules'),
                'campaign_start_date_text' => __('Campaign Start date', 'wp-loyalty-rules'),
                'campaign_end_date_text' => __('Campaign End date', 'wp-loyalty-rules'),
                'choose_type_of_reward_text' => __('Choose the type of reward', 'wp-loyalty-rules'),
                'choose_type_of_reward_desc' => __('The reward can be either points or a discount reward.  The discount reward will be automatically created and awarded to the customers as soon as he meets this campaign eligibility.', 'wp-loyalty-rules'),
                'choose_coupon_reward_text' => __('Choose the coupon reward', 'wp-loyalty-rules'),
                'choose_coupon_reward_desc' => __('Make sure you created the reward at the Rewards section.', 'wp-loyalty-rules'),
                'share_message_text' => __('Share Message', 'wp-loyalty-rules'),
                'share_message_desc' => __('Enter a text that can be shared by the customer in the social media. Customers can alter this text before they share.Shortcodes: {wlr_points}, {wlr_points_label}, {wlr_rewards}, {wlr_referral_url}, {wlr_reward_label}', 'wp-loyalty-rules'),

                'social_share_alert_message' => __("In general, Facebook, Twitter, and Instagram don't allow third-party programs like WPLoyalty to pull information from their websites, making it impossible to award social points after a verification. As a result, the social share campaigns work on the honour system with your customers. When creating the social share rewards, it is recommended to keep this in mind and make sure they align with your goals.", 'wp-loyalty-rules'),
                'choose_level' => array(
                    'name' => __('Choose levels', 'wp-loyalty-rules'),
                    'description' => __('Make sure you created the level at the Levels section.', 'wp-loyalty-rules'),
                ),
                'choose_achivement_event' => array(
                    'name' => __('Choose the type of achievement', 'wp-loyalty-rules'),
                    'description' => __('Choose the type of achievement that makes the customer eligible for the reward. NOTE: The Custom option allows you to programmatically trigger this achievement campaign. Using the option will require a developer assistance.', 'wp-loyalty-rules'),
                ),
//                'empty' => array(
////                    'name' => __('Ready to launch your loyalty rewards program?', 'wp-loyalty-rules'),
//                    'description' => __('Drive repeat purchases and build loyalty by rewarding your customers. Create a new campaign to get started', 'wp-loyalty-rules'),
//                ),
                'ordering' => array(
                    'name' => __('Ordering', 'wp-loyalty-rules'),
                    'description' => __('Visible order for campaign list.', 'wp-loyalty-rules'),
                ),
                'way_to_show_campaign' => array(
                    'name' => __('Campaign visibility on “Ways to earn” section', 'wp-loyalty-rules'),
//                    'description' => __('If you disable it, It wont shown in way to earn reward page.', 'wp-loyalty-rules'),
                ),
                'show_reward' => array(
                    'name' => __('Reward visibility on “Reward Opportunities” section', 'wp-loyalty-rules'),
                    'description' => __('If you disable it, It wont shown in way to earn reward page.', 'wp-loyalty-rules'),
                ),
                'image' => array(
                    'name' => __('Campaign image', 'wp-loyalty-rules'),
                    'description' => __('Upload an image for this Campaign.', 'wp-loyalty-rules'),
                ),

                'create_campaign' => array(
                    'point_for_purchase' => array(
                        'title' => __('Points for Purchase', 'wp-loyalty-rules'),
                        'supporting_text' => __('Reward customers for their purchases. Example:  10 points for every $100 spent in the store.', 'wp-loyalty-rules'),
                    ),
                    'subtotal' => array(
                        'title' => __('Reward based on spending', 'wp-loyalty-rules'),
                        'supporting_text' => __('Let customers earn points or rewards for their spending. Example: Spend $100 and get 100 points or $10 reward. This helps you increase average order value.', 'wp-loyalty-rules'),
                    ),
                    'purchase_histories' => array(
                        'title' => __('Order Goals', 'wp-loyalty-rules'),
                        'supporting_text' => __('Drive repeat purchases by rewarding customers. Example: $100 reward for customers who placed 10 or more orders.', 'wp-loyalty-rules'),
                    ),
                    'birthday' => array(
                        'title' => __('Birthday', 'wp-loyalty-rules'),
                        'supporting_text' => __('Reward customers for sharing their date of birth with you. This will help you offer special offers on their birthday.', 'wp-loyalty-rules'),
                    ),
                    'referral' => array(
                        'title' => __('Referral', 'wp-loyalty-rules'),
                        'supporting_text' => __('Reward customers for referring their friends to your store. Turn your customers into brand advocates by launching a Referral / Refer-a-friend campaign.', 'wp-loyalty-rules'),
                    ),
                    'signup' => array(
                        'title' => __('Sign Up', 'wp-loyalty-rules'),
                        'supporting_text' => __('Reward customers for creating / registering an account with your store.  Useful to turn visitors into loyal customers.', 'wp-loyalty-rules'),
                    ),
                    'product_review' => array(
                        'title' => __('Write a review', 'wp-loyalty-rules'),
                        'supporting_text' => __('Reward customers when they write a review for a product they purchased. This increases the social proof for your store and grows revenue.', 'wp-loyalty-rules'),
                    ),
                    'facebook_share' => array(
                        'title' => __('Facebook Share', 'wp-loyalty-rules'),
                        'supporting_text' => __('Reward customers for sharing your store / products in Facebook. This helps you boost sales through more social media visibility and proof.', 'wp-loyalty-rules'),
                    ),
                    'twitter_share' => array(
                        'title' => __('Twitter Share', 'wp-loyalty-rules'),
                        'supporting_text' => __('Reward customers for sharing your store / products in Twitter. This helps you boost sales through more social media visibility and proof.', 'wp-loyalty-rules'),
                    ),
                    'whatsapp_share' => array(
                        'title' => __('WhatsApp Share', 'wp-loyalty-rules'),
                        'supporting_text' => __('Let customers share your products via WhatsApp. Reward them for sharing.', 'wp-loyalty-rules'),
                    ),
                    'email_share' => array(
                        'title' => __('Email Share', 'wp-loyalty-rules'),
                        'supporting_text' => __('Reward customers for sharing your products via Email or referring their friends to your store via email.', 'wp-loyalty-rules'),
                    ),
                    'order_value' => array(
                        'title' => __('Reward based on order value', 'wp-loyalty-rules'),
                        'supporting_text' => __('Let customers earn points or rewards for their order value. Example: Spend $100 and get 100 points or $10 reward. This helps you increase average order value.', 'wp-loyalty-rules'),
                    ),
                    'followup_share' => array(
                        'title' => __('Follow', 'wp-loyalty-rules'),
                        'supporting_text' => __('Let customers follow your pages in social media like Facebook, Instagram. Reward them for following our pages.', 'wp-loyalty-rules'),
                    ),
                    'achievement' => array(
                        'title' => __('Achievement', 'wp-loyalty-rules'),
                        'supporting_text' => __('Let customers earn points and rewards for achievements like Moving Up a level, Daily Sign In.', 'wp-loyalty-rules'),
                    ),

                ),

                'point_for_purchase' => array(
                    'name' => array(
                        'name' => __('Point for purchase', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a name to your Campaign', 'wp-loyalty-rules'),
                    ),
//                    'description' => array(
//                        'name' => __('Description', 'wp-loyalty-rules'),
////                        'description' => __('A description for your reference', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a description to your Campaign', 'wp-loyalty-rules'),
//                    ),
                    'set_points' => array(
                        'name' => __('Customer earns points', 'wp-loyalty-rules'),
                        'description' => __('Example: Reward customers with 1 point for every $1 spent on purchases.', 'wp-loyalty-rules'),
                    ),
                    'for_each_price' => array(
                        'name' => __('For every X amount spent', 'wp-loyalty-rules'),
                        'description' => __('Set the number of points to be awarded.', 'wp-loyalty-rules'),
                    ),
                    'minimum_points' => array(
                        'name' => __('Minimum points a customer can earn for each order.', 'wp-loyalty-rules'),
                        'description' => __('You can set a minimum number of points to be awarded for each order.', 'wp-loyalty-rules'),
                    ),
                    'maximum_points' => array(
                        'name' => __('Maximum Points a customer can earn for each order.', 'wp-loyalty-rules'),
                        'description' => __('You can set a maximum number of points to be awarded for each order.', 'wp-loyalty-rules'),
                    ),
//                    'start_date' => array(
//                        'name' => __('Campaign Start date', 'wp-loyalty-rules'),
//                    ),
//                    'end_date' => array(
//                        'name' => __('Campaign End date', 'wp-loyalty-rules'),
//                    ),
                    'message_box' => array(
                        'name' => __('Message to show in product pages', 'wp-loyalty-rules'),
                        'description' => __('(Display a message on points in the product page so that the customer knows about the reward)', 'wp-loyalty-rules'),
                    ),
                    'show_message_in' => array(
                        'name' => __('Where to show the message?', 'wp-loyalty-rules'),
                    ),
                    'single_product_message' => array(
                        'name' => __('Message for simple products', 'wp-loyalty-rules'),
                        'description' => __('Shortcodes: {wlr_product_points},{wlr_points_label}', 'wp-loyalty-rules')
                    ),
                    'variable_product_message' => array(
                        'name' => __('Message for variable products', 'wp-loyalty-rules'),
                    ),
                    'text_color' => array(
                        'name' => __('Text Color', 'wp-loyalty-rules'),
                    ),
                    'background_color' => array(
                        'name' => __('Background Color', 'wp-loyalty-rules'),
                    ),
                    'border_color' => array(
                        'name' => __('Border Color', 'wp-loyalty-rules'),
                    ),
                    'rounded_edge' => array(
                        'name' => __('Rounded Edge', 'wp-loyalty-rules'),
                    ),

                ),
                'subtotal' => array(
                    'name' => array(
                        'name' => __('Reward based on spending', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a name to your Campaign', 'wp-loyalty-rules'),
                    ),
//                    'description' => array(
//                        'name' => __('Description', 'wp-loyalty-rules'),
////                        'description' => __('Add some description to this campaign', 'wp-loyalty-rules'),
////                        'placeholder' => __('Give a description to your Campaign', 'wp-loyalty-rules'),
//
//                    ),
                    'set_points' => array(
//                        'name' => __('Set points', 'wp-loyalty-rules'),
//                        'description' => __('Set the number of points to be awarded', 'wp-loyalty-rules'),
                    ),
//                    'what_kind_of_reward' => array(
////                        'name' => __('Choose the type of reward', 'wp-loyalty-rules'),
////                        'description' => __('The reward can be either points or a discount reward.  The discount reward will be automatically created and awarded to the customers as soon as he meets this campaign eligibility.', 'wp-loyalty-rules'),
//                    ),
//                    'choose_coupon' => array(
//                        'name' => __('Choose the coupon reward', 'wp-loyalty-rules'),
//                        'description' => __('Make sure you created the reward at the Rewards section.', 'wp-loyalty-rules'),
//                    ),
                    'minimum_subtotal' => array(
//                        'name' => __('Minimum spend', 'wp-loyalty-rules'),
                        'description' => __('How much a customer should spend in an order (cart subtotal) to get this reward ? Default: 0', 'wp-loyalty-rules'),
                    ),
                    'maximum_subtotal' => array(
//                        'name' => __('Maximum spend', 'wp-loyalty-rules'),
                        'description' => __('The maximum amount a customer can  spend in an order (cart subtotal) to get this reward ? Leave as 0 for no limit.', 'wp-loyalty-rules'),
                    ),
//                    'start_date' => array(
//                        'name' => __('Campaign Start date', 'wp-loyalty-rules'),
//                    ),
//                    'end_date' => array(
//                        'name' => __('Campaign End date', 'wp-loyalty-rules'),
//                    ),
                ),
                'order_goals' => array(
                    'name' => array(
                        'name' => __('Number of Goal Purchase', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a name to your Campaign', 'wp-loyalty-rules'),
                    ),
//                    'description' => array(
//                        'name' => __('Description', 'wp-loyalty-rules'),
//                        'description' => __('Add some description to this campaign', 'wp-loyalty-rules'),
////                        'placeholder' => __('Give a description to your Campaign', 'wp-loyalty-rules'),

//                    ),
//                    'set_points' => array(
////                        'name' => __('Set points', 'wp-loyalty-rules'),
//                        'description' => __('Points to be earned by this campaign', 'wp-loyalty-rules'),
//                    ),
//                    'what_kind_of_reward' => array(
//                        'name' => __('Choose the type of reward', 'wp-loyalty-rules'),
//                        'description' => __('The reward can be either points or a discount reward.  The discount reward will be automatically created and awarded to the customers as soon as he meets this campaign eligibility.', 'wp-loyalty-rules'),
//                    ),
//                    'choose_coupon' => array(
//                        'name' => __('Choose the coupon reward', 'wp-loyalty-rules'),
//                        'description' => __('Make sure you created the reward at the Rewards section.', 'wp-loyalty-rules')
//                    ),
                    'set_goal' => array(
                        'name' => __('Number of orders required', 'wp-loyalty-rules'),
                        'description' => __('How many orders a customer should have to claim this reward?', 'wp-loyalty-rules'),
                    ),
                    'minimum_order' => array(
                        'name' => __('Minimum spend value for each order', 'wp-loyalty-rules'),
                        'description' => __('Minimum value of each order.', 'wp-loyalty-rules'),
                    ),
//                    'start_date' => array(
//                        'name' => __('Campaign Start date', 'wp-loyalty-rules'),
//                    ),
//                    'end_date' => array(
//                        'name' => __('Campaign End date', 'wp-loyalty-rules'),
//                    ),
                ),
                'referral' => array(
                    'name' => array(
                        'name' => __('Referral Campaign', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a name to your Campaign', 'wp-loyalty-rules'),
                    ),
//                    'description' => array(
//                        'name' => __('Description', 'wp-loyalty-rules'),
//                        'description' => __('Add some description to this campaign', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a description to your Campaign', 'wp-loyalty-rules'),

//                    ),
//                    'what_kind_of_reward' => array(
//                        'name' => __('Choose the type of reward', 'wp-loyalty-rules'),
//                        'description' => __('The reward can be either points or a discount reward.  The discount reward will be automatically created and awarded to the customers as soon as he meets this campaign eligibility. ', 'wp-loyalty-rules'),
//                    ),
//                    'choose_coupon' => array(
//                        'name' => __('Choose the coupon reward', 'wp-loyalty-rules'),
//                        'description' => __('Make sure you created the reward at the Rewards section.', 'wp-loyalty-rules')
//                    ),
                    'point_type' => array(
                        'name' => __('Point Reward Type', 'wp-loyalty-rules'),
                        'description' => __('Select the type of the points to be rewarded.', 'wp-loyalty-rules'),
                    ),
                    'set_points' => array(
//                        'name' => __('Set points', 'wp-loyalty-rules'),
//                        'description' => __('Points to be earned by this campaign.', 'wp-loyalty-rules'),
                    ),
                    'set_percentage' => array(
                        'name' => __('Set percentage', 'wp-loyalty-rules'),
                        'description' => __('Enter the percentage of points to be earned based on the subtotal. Example: 10%.  if customer spends $100, then he will be awarded 10 percentage of $100, which is 10 points', 'wp-loyalty-rules'),
                    ),
//                    'start_date' => array(
//                        'name' => __('Campaign Start date', 'wp-loyalty-rules'),
//                    ),
//                    'end_date' => array(
//                        'name' => __('Campaign End date', 'wp-loyalty-rules'),
//                    ),
                    'existing' => array(
                        'name' => __('Existing Customer', 'wp-loyalty-rules'),
                    ),
                    'new' => array(
                        'name' => __('New Customer / Referred Person / Friend Reward', 'wp-loyalty-rules'),
                    ),
                ),
                'signup' => array(
                    'name' => array(
                        'name' => __('Sign Up campaign', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a name to your Campaign', 'wp-loyalty-rules'),
                    ),
//                    'description' => array(
////                        'name' => __('Description', 'wp-loyalty-rules'),
////                        'description' => __('Add some description to this campaign', 'wp-loyalty-rules'),
////                        'placeholder' => __('Give a description to your Campaign', 'wp-loyalty-rules'),
//
//                    ),
//                    'what_kind_of_reward' => array(
//                        'name' => __('Choose the type of reward', 'wp-loyalty-rules'),
//                        'description' => __('The reward can be either points or a discount reward.  The discount reward will be automatically created and awarded to the customers as soon as he meets this campaign eligibility. ', 'wp-loyalty-rules'),
//                    ),
//                    'choose_coupon' => array(
//                        'name' => __('Choose the coupon reward', 'wp-loyalty-rules'),
//                        'description' => __('Make sure you created the reward at the Rewards section.', 'wp-loyalty-rules')
//                    ),

                    'set_points' => array(
//                        'name' => __('Set points', 'wp-loyalty-rules'),
//                        'description' => __('Points to be earned by this campaign.', 'wp-loyalty-rules'),
                    ),
                    'message' => array(
                        'name' => __('Message to display on the Account Creation / Sign up page', 'wp-loyalty-rules'),
                        'description' => __('You can show this message at the account registration / creation page or at the account creation section in checkout page. Shortcodes: {wlr_points}, {wlr_points_label}, {wlr_rewards}, {wlr_reward_label}', 'wp-loyalty-rules'),
                    ),
//                    'start_date' => array(
//                        'name' => __('Campaign Start date', 'wp-loyalty-rules'),
//                    ),
//                    'end_date' => array(
//                        'name' => __('Campaign End date', 'wp-loyalty-rules'),
//                    ),

                ),
                'review' => array(
                    'name' => array(
                        'name' => __('Review campaign', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a name to your Campaign', 'wp-loyalty-rules'),
                    ),
//                    'description' => array(
////                        'name' => __('Description', 'wp-loyalty-rules'),
////                        'description' => __('Add some description to this campaign', 'wp-loyalty-rules'),
////                        'placeholder' => __('Give a description to your Campaign', 'wp-loyalty-rules'),
//
//                    ),
//                    'what_kind_of_reward' => array(
//                        'name' => __('Choose the type of reward', 'wp-loyalty-rules'),
//                        'description' => __('The reward can be either points or a discount reward.  The discount reward will be automatically created and awarded to the customers as soon as he meets this campaign eligibility. ', 'wp-loyalty-rules'),
//                    ),
//                    'choose_coupon' => array(
//                        'name' => __('Choose the coupon reward', 'wp-loyalty-rules'),
//                        'description' => __('Make sure you created the reward at the Rewards section.', 'wp-loyalty-rules')
//                    ),

                    'set_points' => array(
//                        'name' => __('Set points', 'wp-loyalty-rules'),
//                        'description' => __('Points to be earned by this campaign.', 'wp-loyalty-rules'),
                    ),
                    'message' => array(
                        'name' => __('Message to display on the product reviews section of your store.', 'wp-loyalty-rules'),
                        'description' => __('You can show this message at the product reviews section. Shortcodes: {wlr_points}, {wlr_points_label}, {wlr_rewards}, {wlr_reward_label}', 'wp-loyalty-rules'),
                    ),
//                    'start_date' => array(
//                        'name' => __('Campaign Start date', 'wp-loyalty-rules'),
//                    ),
//                    'end_date' => array(
//                        'name' => __('Campaign End date', 'wp-loyalty-rules'),
//                    ),

                ),
                'birthday' => array(
                    'name' => array(
                        'name' => __('Birthday Campaign', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a name to your Campaign', 'wp-loyalty-rules'),
                    ),
//                    'description' => array(
//                        'name' => __('Description', 'wp-loyalty-rules'),
//                        'description' => __('Add some description to this campaign', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a description to your Campaign', 'wp-loyalty-rules'),

//                    ),
//                    'what_kind_of_reward' => array(
//                        'name' => __('Choose the type of reward', 'wp-loyalty-rules'),
//                        'description' => __('The reward can be either points or a discount reward.  The discount reward will be automatically created and awarded to the customers as soon as he meets this campaign eligibility. ', 'wp-loyalty-rules'),
//                    ),
//                    'choose_coupon' => array(
//                        'name' => __('Choose the coupon reward', 'wp-loyalty-rules'),
//                        'description' => __('Make sure you created the reward at the Rewards section.', 'wp-loyalty-rules')
//                    ),

                    'set_points' => array(
//                        'name' => __('Set points', 'wp-loyalty-rules'),
//                        'description' => __('Points to be earned by this campaign.', 'wp-loyalty-rules'),
                    ),
                    'apply_reward_event' => array(
                        'name' => __('When this reward be given ?', 'wp-loyalty-rules'),
                        'description' => __('Select the event to apply reward.', 'wp-loyalty-rules'),
                    ),
                    'message' => array(
                        'name' => __('Message to show customers', 'wp-loyalty-rules'),
                        'description' => __('The message shows on the Customer Reward page.Shortcodes: {wlr_points}, {wlr_points_label}, {wlr_rewards}', 'wp-loyalty-rules'),
                    ),
//                    'start_date' => array(
//                        'name' => __('Campaign Start date', 'wp-loyalty-rules'),
//                    ),
//                    'end_date' => array(
//                        'name' => __('Campaign End date', 'wp-loyalty-rules'),
//                    ),

                ),
                'facebook' => array(
                    'name' => array(
                        'name' => __('Facebook Share', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a name to your Campaign', 'wp-loyalty-rules'),
                    ),
//                    'description' => array(
//                        'name' => __('Description', 'wp-loyalty-rules'),
//                        'description' => __('Add some description to this campaign', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a description to your Campaign', 'wp-loyalty-rules'),

//                    ),
//                    'what_kind_of_reward' => array(
//                        'name' => __('Choose the type of reward', 'wp-loyalty-rules'),
//                        'description' => __('The reward can be either points or a discount reward.  The discount reward will be automatically created and awarded to the customers as soon as he meets this campaign eligibility. ', 'wp-loyalty-rules'),
//                    ),
//                    'choose_coupon' => array(
//                        'name' => __('Choose the coupon reward', 'wp-loyalty-rules'),
//                        'description' => __('Make sure you created the reward at the Rewards section.', 'wp-loyalty-rules')
//                    ),

                    'set_points' => array(
//                        'name' => __('Set points', 'wp-loyalty-rules'),
//                        'description' => __('Points to be earned by this campaign.', 'wp-loyalty-rules'),
                    ),
                    'message' => array(
//                        'name' => __('Share Message', 'wp-loyalty-rules'),
                        'description' => __('Enter a text that can be shared by the customer in the social media. Customers can alter this text before they share.Shortcodes: {wlr_points}, {wlr_points_label}, {wlr_rewards}, {wlr_reward_label}', 'wp-loyalty-rules'),
                    ),
//                    'start_date' => array(
//                        'name' => __('Campaign Start date', 'wp-loyalty-rules'),
//                    ),
//                    'end_date' => array(
//                        'name' => __('Campaign End date', 'wp-loyalty-rules'),
//                    ),

                ),
                'twitter' => array(
                    'name' => array(
                        'name' => __('Twitter Share', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a name to your Campaign', 'wp-loyalty-rules'),
                    ),
//                    'description' => array(
//                        'name' => __('Description', 'wp-loyalty-rules'),
//                        'description' => __('Add some description to this campaign', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a description to your Campaign', 'wp-loyalty-rules'),

//                    ),
//                    'what_kind_of_reward' => array(
//                        'name' => __('Choose the type of reward', 'wp-loyalty-rules'),
//                        'description' => __('The reward can be either points or a discount reward.  The discount reward will be automatically created and awarded to the customers as soon as he meets this campaign eligibility. ', 'wp-loyalty-rules'),
//                    ),
//                    'choose_coupon' => array(
//                        'name' => __('Choose the coupon reward', 'wp-loyalty-rules'),
//                        'description' => __('Make sure you created the reward at the Rewards section.', 'wp-loyalty-rules')
//                    ),

//                    'set_points' => array(
//                        'name' => __('Set points', 'wp-loyalty-rules'),
//                        'description' => __('Points to be earned by this campaign.', 'wp-loyalty-rules'),
//                    ),
//                    'message' => array(
//                        'name' => __('Share Message', 'wp-loyalty-rules'),
//                        'description' => __('Enter a text that can be shared by the customer in the social media. Customers can alter this text before they share.Shortcodes: {wlr_points}, {wlr_points_label}, {wlr_rewards}, {wlr_referral_url}, {wlr_reward_label}', 'wp-loyalty-rules'),
//                    ),
//                    'start_date' => array(
//                        'name' => __('Campaign Start date', 'wp-loyalty-rules'),
//                    ),
//                    'end_date' => array(
//                        'name' => __('Campaign End date', 'wp-loyalty-rules'),
//                    ),

                ),
                'whatsapp' => array(
                    'name' => array(
                        'name' => __('Whatsapp Share', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a name to your Campaign', 'wp-loyalty-rules'),
                    ),
//                    'description' => array(
//                        'name' => __('Description', 'wp-loyalty-rules'),
//                        'description' => __('Add some description to this campaign', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a description to your Campaign', 'wp-loyalty-rules'),
//                    ),
//                    'what_kind_of_reward' => array(
//                        'name' => __('Choose the type of reward', 'wp-loyalty-rules'),
//                        'description' => __('The reward can be either points or a discount reward.  The discount reward will be automatically created and awarded to the customers as soon as he meets this campaign eligibility. ', 'wp-loyalty-rules'),
//                    ),
//                    'choose_coupon' => array(
//                        'name' => __('Choose the coupon reward', 'wp-loyalty-rules'),
//                        'description' => __('Make sure you created the reward at the Rewards section.', 'wp-loyalty-rules')
//                    ),

//                    'set_points' => array(
//                        'name' => __('Set points', 'wp-loyalty-rules'),
//                        'description' => __('Points to be earned by this campaign.', 'wp-loyalty-rules'),
//                    ),
//                    'message' => array(
//                        'name' => __('Share Message', 'wp-loyalty-rules'),
//                        'description' => __('Enter a text that can be shared by the customer in the social media. Customers can alter this text before they share.Shortcodes: {wlr_points}, {wlr_points_label}, {wlr_rewards}, {wlr_referral_url}, {wlr_reward_label}', 'wp-loyalty-rules'),
//                    ),
//                    'start_date' => array(
//                        'name' => __('Campaign Start date', 'wp-loyalty-rules'),
//                    ),
//                    'end_date' => array(
//                        'name' => __('Campaign End date', 'wp-loyalty-rules'),
//                    ),

                ),
                'email' => array(
                    'name' => array(
                        'name' => __('Email Share', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a name to your Campaign', 'wp-loyalty-rules'),
                    ),
//                    'description' => array(
//                        'name' => __('Description', 'wp-loyalty-rules'),
//                        'description' => __('Add some description to this campaign', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a description to your Campaign', 'wp-loyalty-rules'),
//                    ),
//                    'what_kind_of_reward' => array(
//                        'name' => __('Choose the type of reward', 'wp-loyalty-rules'),
//                        'description' => __('The reward can be either points or a discount reward.  The discount reward will be automatically created and awarded to the customers as soon as he meets this campaign eligibility. ', 'wp-loyalty-rules'),
//                    ),
//                    'choose_coupon' => array(
//                        'name' => __('Choose the coupon reward', 'wp-loyalty-rules'),
//                        'description' => __('Make sure you created the reward at the Rewards section.', 'wp-loyalty-rules')
//                    ),

                    'set_points' => array(
//                        'name' => __('Set points', 'wp-loyalty-rules'),
//                        'description' => __('Points to be earned by this campaign.', 'wp-loyalty-rules'),
                    ),
                    'subject' => array(
                        'name' => __('Email Subject', 'wp-loyalty-rules'),
                        'description' => __('Enter a predefined subject line for the email. This can be altered by the customer.Shortcodes: {wlr_points}, {wlr_points_label}, {wlr_rewards}, {wlr_reward_label}, {wlr_referral_url}', 'wp-loyalty-rules'),
                    ),
                    'body' => array(
                        'name' => __('Email Body', 'wp-loyalty-rules'),
                        'description' => __('Enter a predefined body line for the email. This can be altered by the customer.Shortcodes: {wlr_points}, {wlr_points_label}, {wlr_rewards}, {wlr_reward_label}, {wlr_referral_url}', 'wp-loyalty-rules'),
                    ),
//                    'start_date' => array(
//                        'name' => __('Campaign Start date', 'wp-loyalty-rules'),
//                    ),
//                    'end_date' => array(
//                        'name' => __('Campaign End date', 'wp-loyalty-rules'),
//                    ),

                ),
                'order_value' => array(
                    'name' => array(
                        'name' => __('Reward based on Order value', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a name to your Campaign', 'wp-loyalty-rules'),
                    ),
                    'description' => array(
//                        'name' => __('Description', 'wp-loyalty-rules'),
//                        'description' => __('Add some description to this campaign', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a description to your Campaign', 'wp-loyalty-rules'),
                    ),
//                    'set_points' => array(
//                        'name' => __('Set points', 'wp-loyalty-rules'),
//                        'description' => __('Set the number of points to be awarded', 'wp-loyalty-rules'),
//                    ),
//                    'what_kind_of_reward' => array(
//                        'name' => __('Choose the type of reward', 'wp-loyalty-rules'),
//                        'description' => __('The reward can be either points or a discount reward.  The discount reward will be automatically created and awarded to the customers as soon as he meets this campaign eligibility.', 'wp-loyalty-rules'),
//                    ),
//                    'choose_coupon' => array(
//                        'name' => __('Choose the coupon reward', 'wp-loyalty-rules'),
//                        'description' => __('Make sure you created the reward at the Rewards section.', 'wp-loyalty-rules'),
//                    ),
                    'minimum_order_value' => array(
                        'name' => __('Minimum spend', 'wp-loyalty-rules'),
                        'description' => __('How much a customer should spend in an order (cart order value) to get this reward ? Default: 0', 'wp-loyalty-rules'),
                    ),
                    'maximum_order_value' => array(
                        'name' => __('Maximum spend', 'wp-loyalty-rules'),
                        'description' => __('The maximum amount a customer can  spend in an order (cart order value) to get this reward ? Leave as 0 for no limit.', 'wp-loyalty-rules'),
                    ),
//                    'start_date' => array(
//                        'name' => __('Campaign Start date', 'wp-loyalty-rules'),
//                    ),
//                    'end_date' => array(
//                        'name' => __('Campaign End date', 'wp-loyalty-rules'),
//                    ),
                ),
                'followup' => array(
                    'name' => array(
                        'name' => __('Follow', 'wp-loyalty-rules'),
                        'placeholder' => __('Let customers follow your pages in social media like Facebook, Instagram. Reward them for following our pages.', 'wp-loyalty-rules'),
                    ),
                    'follow_up_url' => array(
                        'name' => __('URL for your page', 'wp-loyalty-rules'),
                        'description' => __('Enter the url of the social media page that you would like the customer to follow up. Example: Your instagram page url. NOTE: The customer will be redirected to this page url when they click on this campaign.', 'wp-loyalty-rules'),
                    ),
//                    'description' => array(
//                        'name' => __('Description', 'wp-loyalty-rules'),
//                        'description' => __('Add some description to this campaign', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a description to your Campaign', 'wp-loyalty-rules'),
//                    ),
//                    'what_kind_of_reward' => array(
//                        'name' => __('Choose the type of reward', 'wp-loyalty-rules'),
//                        'description' => __('The reward can be either points or a discount reward.  The discount reward will be automatically created and awarded to the customers as soon as he meets this campaign eligibility. ', 'wp-loyalty-rules'),
//                    ),
//                    'choose_coupon' => array(
//                        'name' => __('Choose the coupon reward', 'wp-loyalty-rules'),
//                        'description' => __('Make sure you created the reward at the Rewards section.', 'wp-loyalty-rules')
//                    ),

//                    'set_points' => array(
//                        'name' => __('Set points', 'wp-loyalty-rules'),
//                        'description' => __('Points to be earned by this campaign.', 'wp-loyalty-rules'),
//                    ),
//                    'message' => array(
////                        'name' => __('Share Message', 'wp-loyalty-rules'),
////                        'description' => __('Enter a text that can be shared by the customer in the social media. Customers can alter this text before they share.Shortcodes: {wlr_points}, {wlr_points_label}, {wlr_rewards}, {wlr_referral_url}, {wlr_reward_label}', 'wp-loyalty-rules'),
//                    ),
//                    'start_date' => array(
//                        'name' => __('Campaign Start date', 'wp-loyalty-rules'),
//                    ),
//                    'end_date' => array(
//                        'name' => __('Campaign End date', 'wp-loyalty-rules'),
//                    ),

                ),
            ),
            'rewards' => array(
                'name' => __('Rewards', 'wp-loyalty-rules'),
                'create_reward_text' => __('Create Reward', 'wp-loyalty-rules'),
                'edit_reward' => __('Edit Reward', 'wp-loyalty-rules'),
                'empty_title' => __('You’ve not created any rewards for your customers yet.', 'wp-loyalty-rules'),
                'empty_supporting_text' => __('Create pre-defined rewards that can be redeemed for points or can be used a direct coupon rewards for earn campaigns.', 'wp-loyalty-rules'),
                'no_reward_title' => __('No rewards found!', 'wp-loyalty-rules'),
                'no_reward_text' => __('No rewards found in this name, Create new reward.', 'wp-loyalty-rules'),
                'add_reward' => __('Create a reward', 'wp-loyalty-rules'),
                'add_new_reward' => __('Choose reward type', 'wp-loyalty-rules'),
                'add_new_reward_description' => __('Choose the reward type', 'wp-loyalty-rules'),
                'reward_type' => __('Reward Type', 'wp-loyalty-rules'),
                'success_message' => __('Reward created successfully', 'wp-loyalty-rules'),
                'search' => __('Search rewards', 'wp-loyalty-rules'),
//                'used_reward_campaign_list' => __('Campaigns', 'wp-loyalty-rules'),
                'reward_used_campaign_header_text' => __('USED IN CAMPAIGNS ', 'wp-loyalty-rules'),


                /* ---------------------------------2.0------------------------ */
                'create_new_reward' => __('Create New Reward', 'wp-loyalty-rules'),
                'title_description' => __('Title / Description', 'wp-loyalty-rules'),
                'enable_disable' => __('Enable / Disable', 'wp-loyalty-rules'),
//                'actions' => __('actions', 'wp-loyalty-rules'),
                'reward_title' => __('Reward Title', 'wp-loyalty-rules'),
                'reward_description' => __('Reward description', 'wp-loyalty-rules'),
                'delete_alert_message' => __('Are you sure want to delete this reward?', 'wp-loyalty-rules'),
                'delete_multi_alert_message' => __('Are you sure want to delete this selected reward?', 'wp-loyalty-rules'),
                'delete_ok' => __('Yes, Delete Reward', 'wp-loyalty-rules'),
                'delete_cancel' => __('No, Keep Reward', 'wp-loyalty-rules'),
                'delete_reward' => __('Delete Reward?', 'wp-loyalty-rules'),
                'campaign_list_text' => __('Reward used in list of campaigns', 'wp-loyalty-rules'),
                'used' => __('Used', 'wp-loyalty-rules'),
                'set_points_desc' => __('Enter value of points to be used to redeem this reward', 'wp-loyalty-rules'),
                'choose_reward_type_text' => __('Choose how this reward should be used?', 'wp-loyalty-rules'),
                'choose_reward_type_point_desc' => __('This reward will be provided for redeeming their points', 'wp-loyalty-rules'),
                'choose_reward_type_coupon_desc' => __('Reward as a coupon code immediately after completing a campaign.', 'wp-loyalty-rules'),
                'display_coupon_name_text' => __('Display name for the coupon (when redeeming)', 'wp-loyalty-rules'),
                'display_coupon_name_desc' => __('What would be the name to show for the discount when customer redeems', 'wp-loyalty-rules'),
                'display_coupon_name_placeholder' => __('Display name for this reward', 'wp-loyalty-rules'),
                'coupon_expiry_text' => __('Coupon Expiry', 'wp-loyalty-rules'),
                'expire_email_text' => __('Wait Period', 'wp-loyalty-rules'),
                'expire_email_desc' => __('An expiry email will be sent from the date the customer redeems the points/reward. Set how many days to wait before sending the expiry notification email', 'wp-loyalty-rules'),
                'enable_expiry_email_text' => __('Would you like to send an expiry email?', 'wp-loyalty-rules'),
                'discount_value_desc' => __('Enter the value of Percentage discount to be earned', 'wp-loyalty-rules'),

                'empty' => array(
                    'name' => __('Ready to launch a tiered loyalty program?', 'wp-loyalty-rules'),
                    'description' => __('Treat your VIP customers with the best rewards by ranking them on different loyalty levels. Create loyalty levels & classify customers.', 'wp-loyalty-rules'),
                ),
                'image' => array(
                    'name' => __('Reward image', 'wp-loyalty-rules'),
                    'description' => __('Upload an image for this Reward.', 'wp-loyalty-rules'),
                ),
                'redeem_count' => array(
                    'title' => __('Redeem Count', 'wp-loyalty-rules'),
                    'description' => __('Useful if you want to limit the number of times a customer can redeem this reward. NOTE: Only applicable for "Points" based redeems. Set this to 0 for unlimited redeems. Default is 0', 'wp-loyalty-rules'),
                ),
                'create_reward' => array(
                    'points_conversion' => array(
                        'name' => __("Points Conversion", 'wp-loyalty-rules'),
                        'description' => __("Convert points to $", 'wp-loyalty-rules'),
                    ),
                    'fixed_discount' => array(
                        'name' => __('Fixed Discount', 'wp-loyalty-rules'),
                        'description' => __('Reward with Fixed $ discount.', 'wp-loyalty-rules'),
                    ),
                    'percentage_discount' => array(
                        'name' => __('Percentage Discount', 'wp-loyalty-rules'),
                        'description' => __('Reward with a percentage discount.', 'wp-loyalty-rules'),
                    ),
                    'free_shipping' => array(
                        'name' => __('Free Shipping', 'wp-loyalty-rules'),
                        'description' => __('Customer get Free Shipping as a Reward.', 'wp-loyalty-rules'),
                    ),
                    'free_product' => array(
                        'name' => __('Free Product', 'wp-loyalty-rules'),
                        'description' => __('Provide a Specific product as a gift.', 'wp-loyalty-rules'),
                    ),
                ),
                'fixed_discount' => array(
                    'name' => array(
//                        'name' => __('Fixed Discount', 'wp-loyalty-rules'),
//                        'placeholder' => __('Title for the Reward', 'wp-loyalty-rules'),
                    ),
//                    'description' => array(
//                        'name' => __('Description', 'wp-loyalty-rules'),
//                        'description' => __('Enter a description for the reward.', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a description to your reward', 'wp-loyalty-rules'),
//                    ),
//                    'set_points' => array(
//                        'name' => __('Set points', 'wp-loyalty-rules'),
//                        'description' => __('Enter value of points to be used to redeem this reward', 'wp-loyalty-rules'),
//                    ),
//                    'choose_reward' => array(
//                        'name' => __('Choose how this reward should be used?', 'wp-loyalty-rules'),
//                        'point' => __('This reward will be provided for redeeming their points', 'wp-loyalty-rules'),
//                        'coupon' => __('Reward as a coupon code immediately after completing a campaign.', 'wp-loyalty-rules')
//                    ),
//                    'display_name' => array(
//                        'name' => __('Display name for the coupon (when redeeming)', 'wp-loyalty-rules'),
//                        'description' => __('What would be the name to show for the discount when customer redeems', 'wp-loyalty-rules'),
//                        'placeholder' => __('Display name for this reward', 'wp-loyalty-rules')
//                    ),
                    'discount_value' => array(
//                        'name' => __('Discount value', 'wp-loyalty-rules'),
                        'description' => __('Set the discount amount.', 'wp-loyalty-rules'),
                    ),
//                    'expire' => array(
//                        'name' => __('Coupon Expiry', 'wp-loyalty-rules'),
//                        'description' => __('Set when the coupon expires once claimed.', 'wp-loyalty-rules'),
//                    ),
//                    'expiry_email' => array(
//                        'name' => __('Wait Period', 'wp-loyalty-rules'),
//                        'description' => __('An expiry email will be sent from the date the customer redeems the points/reward. Set how many days to wait before sending the expiry notification email', 'wp-loyalty-rules'),
//                    ),
//                    'enable_expiry_email' => array(
//                        'label' => __('Would you like to send an expiry email?', 'wp-loyalty-rules'),
//                    )
                ),
                'points_conversion' => array(
//                    'name' => array(
//                        'name' => __('Points Conversion', 'wp-loyalty-rules'),
//                        'placeholder' => __('Title for the Reward', 'wp-loyalty-rules'),
//                    ),
//                    'description' => array(
//                        'name' => __('Description', 'wp-loyalty-rules'),
//                        'description' => __('Enter a description for the reward.', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a description to your reward', 'wp-loyalty-rules'),
//                    ),
                    'conversion' => array(
                        'name' => __('Conversion Rate', 'wp-loyalty-rules'),
                        'description' => __('NOTE: The above values will be used to calculate the conversion ratio. WPLoyalty will automatically calculate the value of each point using the following formula: value of the discount / number of points required = value of each point. Example: if you set 500 points for $5 value, then each point is worth: $5 / 500 = 0.01 . If a customer redeems 100 points, then he will be given $1 based on the conversion ratio.', 'wp-loyalty-rules'),
                    ),
                    'set_points' => array(
                        'name' => __('Redeem points', 'wp-loyalty-rules'),
                        'hint' => __('points', 'wp-loyalty-rules'),
                        'description' => __('Set how many points can be redeemed for a discount amount. Example: 500 points for $5.', 'wp-loyalty-rules'),
                    ),
                    'discount_value' => array(
                        'name' => __('Value of the discount', 'wp-loyalty-rules'),
                        'hint' => __('discount', 'wp-loyalty-rules'),
//                        'description' => __('Enter value of points to be used to redeem this reward', 'wp-loyalty-rules'),
                    ),
//                    'display_name' => array(
//                        'name' => __('Display name for the coupon (when redeeming)', 'wp-loyalty-rules'),
//                        'description' => __('What would be the name to show for the discount when customer redeems', 'wp-loyalty-rules'),
//                        'placeholder' => __('Display name for this reward', 'wp-loyalty-rules')
//                    ),
                    'minimum_points' => array(
                        'name' => __('Minimum points a customer can redeem per coupon', 'wp-loyalty-rules'),
                        'description' => __('You can set a minimum number of points to be redeemed per coupon.', 'wp-loyalty-rules'),
                    ),
                    'maximum_points' => array(
                        'name' => __('Maximum points a customer can redeem per coupon', 'wp-loyalty-rules'),
                        'description' => __('You can set a maximum number of points to be redeemed per coupon.', 'wp-loyalty-rules'),
                    ),
//                    'expire' => array(
//                        'name' => __('Coupon Expiry', 'wp-loyalty-rules'),
//                        'description' => __('Set when the coupon expires once claimed.', 'wp-loyalty-rules'),
//                    ),
//                    'expiry_email' => array(
//                        'name' => __('Wait Period', 'wp-loyalty-rules'),
//                        'description' => __('An expiry email will be sent from the date the customer redeems the points/reward. Set how many days to wait before sending the expiry notification email', 'wp-loyalty-rules'),
//                    ),
//                    'enable_expiry_email' => array(
//                        'label' => __('Would you like to send an expiry email?', 'wp-loyalty-rules'),
//                    )
                ),
                'percentage_discount' => array(
//                    'name' => array(
//                        'name' => __('Percentage Discount', 'wp-loyalty-rules'),
//                        'placeholder' => __('Title for the Reward', 'wp-loyalty-rules'),
//                    ),
//                    'description' => array(
//                        'name' => __('Description', 'wp-loyalty-rules'),
//                        'description' => __('Enter a description for the reward.', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a description to your reward', 'wp-loyalty-rules'),
//                    ),
//                    'set_points' => array(
//                        'name' => __('Set points', 'wp-loyalty-rules'),
//                        'description' => __('Enter value of points to be used to redeem this reward', 'wp-loyalty-rules'),
//                    ),
//                    'choose_reward' => array(
//                        'name' => __('Choose how this reward should be used?', 'wp-loyalty-rules'),
//                        'point' => __('This reward will be provided for redeeming their points', 'wp-loyalty-rules'),
//                        'coupon' => __('Reward as a coupon code immediately after completing a campaign.', 'wp-loyalty-rules')
//                    ),
//                    'display_name' => array(
//                        'name' => __('Display name for the coupon (when redeeming)', 'wp-loyalty-rules'),
//                        'description' => __('What would be the name to show for the discount when customer redeems', 'wp-loyalty-rules'),
//                        'placeholder' => __('Display name for this reward', 'wp-loyalty-rules')
//                    ),

//                    'discount_value' => array(
//                        'name' => __('Discount value', 'wp-loyalty-rules'),
//                        'description' => __('Enter value of points to be used to redeem this reward', 'wp-loyalty-rules'),
//                    ),
//                    'expire' => array(
//                        'name' => __('Coupon Expiry', 'wp-loyalty-rules'),
//                        'description' => __('Set when the coupon expires once claimed.', 'wp-loyalty-rules'),
//                    ),
//                    'expiry_email' => array(
//                        'name' => __('Wait Period', 'wp-loyalty-rules'),
//                        'description' => __('An expiry email will be sent from the date the customer redeems the points/reward. Set how many days to wait before sending the expiry notification email', 'wp-loyalty-rules'),
//                    ),
//                    'enable_expiry_email' => array(
//                        'label' => __('Would you like to send an expiry email?', 'wp-loyalty-rules'),
//                    )
                ),
                'free_shipping' => array(
//                    'name' => array(
//                        'name' => __('Free shipping', 'wp-loyalty-rules'),
//                        'placeholder' => __('Title for the Reward', 'wp-loyalty-rules'),
//                    ),
//                    'description' => array(
//                        'name' => __('Description', 'wp-loyalty-rules'),
//                        'description' => __('Enter a description for the reward.', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a description to your reward', 'wp-loyalty-rules'),
//                    ),
//                    'set_points' => array(
//                        'name' => __('Set points', 'wp-loyalty-rules'),
//                        'description' => __('Enter value of points to be used to redeem this reward', 'wp-loyalty-rules'),
//                    ),
//                    'choose_reward' => array(
//                        'name' => __('Choose how this reward should be used?', 'wp-loyalty-rules'),
//                        'point' => __('This reward will be provided for redeeming their points', 'wp-loyalty-rules'),
//                        'coupon' => __('Reward as a coupon code immediately after completing a campaign.', 'wp-loyalty-rules')
//                    ),
//                    'display_name' => array(
//                        'name' => __('Display name for the coupon (when redeeming)', 'wp-loyalty-rules'),
//                        'description' => __('What would be the name to show for the discount when customer redeems', 'wp-loyalty-rules'),
//                        'placeholder' => __('Display name for this reward', 'wp-loyalty-rules')
//                    ),

//                    'discount_value' => array(
//                        'name' => __('Discount value', 'wp-loyalty-rules'),
//                        'description' => __('Enter value of points to be used to redeem this reward', 'wp-loyalty-rules'),
//                    ),
//                    'expire' => array(
//                        'name' => __('Coupon Expiry', 'wp-loyalty-rules'),
//                        'description' => __('Set when the coupon expires once claimed.', 'wp-loyalty-rules'),
//                    ),
//                    'expiry_email' => array(
//                        'name' => __('Wait Period', 'wp-loyalty-rules'),
//                        'description' => __('An expiry email will be sent from the date the customer redeems the points/reward. Set how many days to wait before sending the expiry notification email', 'wp-loyalty-rules'),
//                    ),
//                    'enable_expiry_email' => array(
//                        'label' => __('Would you like to send an expiry email?', 'wp-loyalty-rules'),
//                    )
                ),
                'free_product' => array(
//                    'name' => array(
//                        'name' => __('Free Product', 'wp-loyalty-rules'),
//                        'placeholder' => __('Title for the Reward', 'wp-loyalty-rules'),
//                    ),
//                    'description' => array(
//                        'name' => __('Description', 'wp-loyalty-rules'),
//                        'description' => __('Enter a description for the reward.', 'wp-loyalty-rules'),
//                        'placeholder' => __('Give a description to your reward', 'wp-loyalty-rules'),
//                    ),
//                    'set_points' => array(
//                        'name' => __('Set points', 'wp-loyalty-rules'),
//                        'description' => __('Enter value of points to be used to redeem this reward', 'wp-loyalty-rules'),
//                    ),
//                    'choose_reward' => array(
//                        'name' => __('Choose how this reward should be used?', 'wp-loyalty-rules'),
//                        'point' => __('This reward will be provided for redeeming their points', 'wp-loyalty-rules'),
//                        'coupon' => __('Reward as a coupon code immediately after completing a campaign.', 'wp-loyalty-rules')
//                    ),
//                    'display_name' => array(
//                        'name' => __('Display name for the coupon (when redeeming)', 'wp-loyalty-rules'),
//                        'description' => __('What would be the name to show for the discount when customer redeems', 'wp-loyalty-rules'),
//                        'placeholder' => __('Display name for this reward', 'wp-loyalty-rules')
//                    ),

                    'free_product' => array(
//                        'name' => __('Free Product', 'wp-loyalty-rules'),
                        'description' => __('select product(s) you want to give as reward.', 'wp-loyalty-rules'),

                    ),
//                    'expire' => array(
//                        'name' => __('Coupon Expiry', 'wp-loyalty-rules'),
//                        'description' => __('Set when the coupon expires once claimed.', 'wp-loyalty-rules'),
//                    ),
//                    'expiry_email' => array(
//                        'name' => __('Wait Period', 'wp-loyalty-rules'),
//                        'description' => __('An expiry email will be sent from the date the customer redeems the points/reward. Set how many days to wait before sending the expiry notification email', 'wp-loyalty-rules'),
//                    ),
//                    'enable_expiry_email' => array(
//                        'label' => __('Would you like to send an expiry email?', 'wp-loyalty-rules'),
//                    )
                ),
            ),
            'levels' => array(
                'name' => __('Levels', 'wp-loyalty-rules'),
                'search_levels' => __('Search levels', 'wp-loyalty-rules'),
                'level_created' => __('Level created successfully', 'wp-loyalty-rules'),
                'update_levels_txt' => __('Update Levels', 'wp-loyalty-rules'),
                'create_new_levels_txt' => __('Create New Level', 'wp-loyalty-rules'),
                'edit_levels_text' => __('Edit Level', 'wp-loyalty-rules'),
//                'level_name_label' => __('Title / Description', 'wp-loyalty-rules'),
                'level_name_label_text' => __('Level name', 'wp-loyalty-rules'),
                'points_to_collect_label' => __('Points to collect', 'wp-loyalty-rules'),
                'badge_label' => __('Badge', 'wp-loyalty-rules'),
                'created_on_label' => __('Created On', 'wp-loyalty-rules'),
                'status_label' => __('Status', 'wp-loyalty-rules'),
//                'action_label' => __('actions', 'wp-loyalty-rules'),
//                'enable_disable' => __('Enable / Disable', 'wp-loyalty-rules'),
                'progress_preview_total' => __('Total items', 'wp-loyalty-rules'),
                'progress_completed_total' => __('Total Completed items', 'wp-loyalty-rules'),
//                'progress_next' => __('Next', 'wp-loyalty-rules'),
//                'progress_done' => __('Done', 'wp-loyalty-rules'),
                'no_levels_title' => __('No levels found!', 'wp-loyalty-rules'),
                'no_levels_description' => __('No levels found in this name, Create new level.', 'wp-loyalty-rules'),
//                'empty_level_title' => __('Create new level', 'wp-loyalty-rules'),
                'empty_level_description' => __('Create a new level to get started', 'wp-loyalty-rules'),
                'choose_an_image' => __('Choose an image', 'wp-loyalty-rules'),
                'delete_alert_message' => __('Are you sure want to delete this level?', 'wp-loyalty-rules'),
                'delete_multi_alert_message' => __('Are you sure want to delete this selected level?', 'wp-loyalty-rules'),
                'delete_ok' => __('Yes, Delete Level', 'wp-loyalty-rules'),
                'delete_cancel' => __('No, Keep Level', 'wp-loyalty-rules'),
                'delete_level' => __('Delete Level?', 'wp-loyalty-rules'),
                'upload_tooltip' => __('upload', 'wp-loyalty-rules'),
                'reset_tooltip' => __('reset', 'wp-loyalty-rules'),
                'level_name' => array(
//                    'name' => __('Level name', 'wp-loyalty-rules'),
                    'description' => __('Enter a title for this level.', 'wp-loyalty-rules'),
                    "placeholder" => __('Ex: Top ', 'wp-loyalty-rules'),
                ),
                'description' => array(
                    'name' => __('Level description', 'wp-loyalty-rules'),
                ),
                'points_to_collect' => array(
//                    'name' => __('Points to collect', 'wp-loyalty-rules'),
                    'from' => __('From', 'wp-loyalty-rules'),
                    'to' => __('To', 'wp-loyalty-rules'),
                    'from_placeholder' => __('1001', 'wp-loyalty-rules'),
                    'to_placeholder' => __('1600', 'wp-loyalty-rules'),
                    'main_description' => __('How many points a customer need to collect to earn this level', 'wp-loyalty-rules'),
                    "sub_description" => __('Set a maximum points applicable for this level. Leave empty if this is the last level in your points program', 'wp-loyalty-rules'),
                ),
                'add_a_badge_image' => array(
                    'name' => __('Add a badge image', 'wp-loyalty-rules'),
                    'description' => __('Enable if you want to upload a badge image to identify this level', 'wp-loyalty-rules'),
                ),
                'upload_badge' => array(
                    'name' => __('Badge image for this level', 'wp-loyalty-rules'),
                    'description' => __('Upload an image for this level. This will be displayed as the badge to the customer.', 'wp-loyalty-rules'),
                ),
                'level_text_color' => array(
                    'name' => __('Level text color', 'wp-loyalty-rules'),
                    'description' => __('Set the color for level label text (shown in shortcodes and widgets)', 'wp-loyalty-rules'),
                ),


            ),
            'apps' => array(
                'title' => __('Add-ons', 'wp-loyalty-rules'),
                'name' => __('Installed Add-ons', 'wp-loyalty-rules'),
                'search_apps' => __('Search add-ons', 'wp-loyalty-rules'),
                'open_app' => __('Open', 'wp-loyalty-rules'),
                'add_new_app' => __('Add New Add-on', 'wp-loyalty-rules'),
            ),
            'settings' => array(
                'name' => __('Settings', 'wp-loyalty-rules'),
                'display_message_settings' => array(
                    'product_message' => array(
                        'name' => __('Products', 'wp-loyalty-rules'),
                    ),
                    'cart_message' => array(
                        'name' => __('Cart', 'wp-loyalty-rules'),
                    ),
                    'checkout_message' => array(
                        'name' => __('Checkout', 'wp-loyalty-rules'),
                    ),
                    'thank_you_message' => array(
                        'name' => __('Thank You Page', 'wp-loyalty-rules'),
                    ),
                    'display_style_message' => array(
                        'name' => __('Branding', 'wp-loyalty-rules'),
                    ),
                ),
                'license' => array(
                    'name' => __('License', 'wp-loyalty-rules'),
                    'validate' => __('Verify Key', 'wp-loyalty-rules'),
                    'description' => __('You can get your license key from your', 'wp-loyalty-rules'),
                    'flycart' => __('account at WPLoyalty website', 'wp-loyalty-rules'),
                    'license_key' => array(
                        'name' => __('License Key', 'wp-loyalty-rules'),
                        'placeholder' => __('Enter your license key here', 'wp-loyalty-rules'),
                    ),
                    'error_message' => __('Error in validating license', 'wp-loyalty-rules'),
                ),
                'earn_point' => array(
                    'name' => __('General Settings', 'wp-loyalty-rules'),
                    'description' => __('General settings related to points and rewards', 'wp-loyalty-rules'),
                    'earn_point' => array(
                        'name' => __('Rounding Mode for points earned', 'wp-loyalty-rules'),
                        'description' => __('Ex: If a user has spent 5.50 on a product, round up or to the nearest integer would make 6 points whereas round down will earn him 5 points.', 'wp-loyalty-rules'),
                    ),
                    'earn_order' => array(
                        'name' => __('Success order status', 'wp-loyalty-rules'),
                        'description' => __('Customer will be rewarded when the order is successful. Choose the order status that are considered as order success.', 'wp-loyalty-rules'),
                    ),
                    'failed_order' => array(
                        'name' => __('Unsuccessful order status', 'wp-loyalty-rules'),
                        'description' => __('The points and rewards will be canceled / removed if the order status turns to these unsuccessful status. Example: After placing a successful order, the customer cancels the order. In this case, any points / rewards earned will be removed.', 'wp-loyalty-rules'),
                    ),
                    'point_text' => array(
                        'name' => __('Label for the "points" - plural', 'wp-loyalty-rules'),
                        'description' => __('Enter the plural form of label for points. If you want to call the points as beans, you can enter "beans" here. So customer will see points messages like:  Earn 20 beans for purchasing this product', 'wp-loyalty-rules'),
                    ),
                    'point_text_singular' => array(
                        'name' => __('Label for the "point" - singular', 'wp-loyalty-rules'),
                        'description' => __('Enter the singular form of label for point. If you want to call the point as bean, you can enter "bean" here. So customer will see point messages like:  Earn 1 bean for purchasing this product', 'wp-loyalty-rules'),
                    ),
                    'reward_text_plural' => array(
                        'name' => __('Label for the "rewards" - plural', 'wp-loyalty-rules'),
                        'description' => __('Enter the plural form of label for rewards. If you want to call the rewards as beans, you can enter "beans" here. So customer will see rewards messages like:  Earn 20 beans for purchasing this product', 'wp-loyalty-rules'),
                    ),
                    'reward_text_singular' => array(
                        'name' => __('Label for the "reward" - singular', 'wp-loyalty-rules'),
                        'description' => __('Enter the singular form of label for reward. If you want to call the reward as bean, you can enter "bean" here. So customer will see reward messages like:  Earn 1 bean for purchasing this product', 'wp-loyalty-rules'),
                    ),
                    'my_account_label' => array(
                        'name' => __('My Account Label Icon Display Position', 'wp-loyalty-rules'),
                        'description' => __('In My Account Page, Point label icon display position.', 'wp-loyalty-rules'),
                    ),
                    'prefix_reward' => array(
                        'name' => __('Prefix to use for reward coupons', 'wp-loyalty-rules'),
                        'description' => __('This will be used as a prefix when generating a reward coupon', 'wp-loyalty-rules')
                    ),
                    'prefix_referral' => array(
                        'name' => __('Prefix for referral code', 'wp-loyalty-rules'),
                        'description' => __('The text here will be used as a prefix when generating a unique referral code for the customer', 'wp-loyalty-rules')
                    ),
                    'add_customer_wpl_customer' => array(
                        'name' => __('Create a customer record in WPLoyalty automatically for following actions', 'wp-loyalty-rules'),
                        'description' => __('Useful to automatically add the customer to WPLoyalty instead of importing your existing customers. Example: When you selected "Sign in", then your existing customers will be automatically be added to WPLoyalty customer section.', 'wp-loyalty-rules'),
                    ),
                    'automatic_create_coupon' => array(
                        'name' => __('Create a coupon instantly when customers get an instant reward ?', 'wp-loyalty-rules'),
                        'description' => __('Useful if you want to issue the coupon immediately after a customer earns a coupon reward. Default: Yes.  Set this to NO, if you want to issue the coupon at the time of the customer redeeming the reward.', 'wp-loyalty-rules'),
                    ),
                    'individual' => array(
                        'name' => __('Force "individual use only" for coupons when creating the rewards for the customers ?', 'wp-loyalty-rules'),
                        'description' => __('Useful if you would like to prevent customers from using other coupons in conjunction with the WPLoyalty reward coupons.', 'wp-loyalty-rules'),
                    ),
                    'pagination_limit' => array(
                        'name' => __('Pagination Limit (default limit)', 'wp-loyalty-rules'),
                        'description' => __('If you want to change default pagination limit choose any limit value here.', 'wp-loyalty-rules'),
                    ),
                    'revert_point' => array(
                        'name' => __('Show an option to return points after converting to a coupon ?', 'wp-loyalty-rules'),
                        'description' => __('Sometimes customers may accidentally redeem the points or make a mistake on the number of points to redeem. At that time, this option will be useful for the customer to return the coupon and get their points back.', 'wp-loyalty-rules'),
                    ),
                    'debug_mode' => array(
                        'name' => __('Debug mode', 'wp-loyalty-rules'),
                        'description' => __('Turn on this option only if the support team requests for it. Default is NO.', 'wp-loyalty-rules'),
                    ),
                    'is_earn_point_after_discount' => array(
                        'name' => __('Calculate earn points', 'wp-loyalty-rules'),
                        'description' => __('By default, earn points will be calculated based on the amount - before the discount . You can change this to "After discount" to let WPLoyalty calculate the points for the amount after reducing the discount. NOTE: This option supports discounts that are based on the Coupon object. If a discount is applied in any other forms like a "minus fee" using the fee object, it will not be supported.', 'wp-loyalty-rules'),
                    ),
                    'display_birthday_date_at' => array(
                        'name' => __("Capture customer's birthday", 'wp-loyalty-rules'),
                        'description' => __("Choose the pages where you can capture the birthday of the customer (in addition to the Customer's Reward page). Example: You can capture at the checkout page.", 'wp-loyalty-rules'),
                        'options' => array(
                            array(
                                'label' => __('Checkout', 'wp-loyalty-rules'),
                                'value' => 'checkout',
                            ),
                            array(
                                'label' => __('Registration (WooCommerce)', 'wp-loyalty-rules'),
                                'value' => 'registration',
                            ),
                            array(
                                'label' => __('Account details', 'wp-loyalty-rules'),
                                'value' => 'account_details',
                            ),
                        ),

                    ),
                    'user_display_conditions' => array(
                        'name' => __('Show the earning opportunities based on conditions', 'wp-loyalty-rules'),
                        'description' => __("Filter the earning opportunities based on the selected conditions. Example: You can show Earning Opportunities based on the current user role or the level.", 'wp-loyalty-rules'),
                        'options' => array(
                            array(
                                'label' => __('Customer Role', 'wp-loyalty-rules'),
                                'value' => 'user_role',
                            ),
                            array(
                                'label' => __('Current Level', 'wp-loyalty-rules'),
                                'value' => 'user_level',
                            ),
                            array(
                                'label' => __('Current and next user level ', 'wp-loyalty-rules'),
                                'value' => 'user_level_with_next_level',
                            ),
                        ),
                    ),
                    'is_one_time_birthdate_edit' => array(
                        'name' => __('Allow customers to edit their birthday after entering it ?', 'wp-loyalty-rules'),
                        'description' => __('Default is NO. When set to yes, customers will be able to change their birthday on their own. Even if they do, they wont be able to earn points / rewards multiple times. It will be given once per year - when the "on their birthday" option is chosen.', 'wp-loyalty-rules'),
                    ),
                    'is_campaign_level_batch_display' => array(
                        'name' => __('Display the "Level" icon for rewards assigned to selected levels (in conditions)', 'wp-loyalty-rules'),
                        'description' => __('Useful for customers to understand what Level they need to unlock before they can earn a particular reward.', 'wp-loyalty-rules'),
                    ),
                    'tax_calculation_type' => array(
                        'name' => __('Tax calculation should be based on', 'wp-loyalty-rules'),
                        'description' => __('Choose how the tax calculation should work. By default, WPLoyalty inherits the settings from WooCommerce.', 'wp-loyalty-rules')
                    )
                ),
                'pages' => array(
                    'name' => __('Customer Reward Page', 'wp-loyalty-rules'),
                    'description' => __('Customer Reward Page options and settings', 'wp-loyalty-rules'),
                    'create_page' => array(
                        'name' => __('Create Customer Reward Page', 'wp-loyalty-rules'),
                        'description' => __('Create customer reward page for displaying customer points and rewards', 'wp-loyalty-rules'),
                        'link_text' => __('Create', 'wp-loyalty-rules'),
                    ),
                    'campagin_display' => array(
                        "name" => __('Show Ways to Earn section', 'wp-loyalty-rules'),
                    ),
                    'reward_display' => array(
                        "name" => __('Show Reward Opportunities section', 'wp-loyalty-rules'),
                    ),
                    'is_sent_email_display' => array(
                        "name" => __('Show email opt-in section', 'wp-loyalty-rules'),
                    ),
                    'show_transaction_section' => array(
                        "name" => __('Show recent activities section', 'wp-loyalty-rules'),
                    ),
                    'show_campaign_point_display' => array(
                        "name" => __('Display potential points to be earned', 'wp-loyalty-rules'),
                    ),
                ),
                'display_message' => array(
                    'name' => __('Display Messages', 'wp-loyalty-rules'),
                    'description' => __('Includes product / cart and checkout pages', 'wp-loyalty-rules'),
                    'colors_heading' => __('Display Message Styles', 'wp-loyalty-rules'),
                    'product_message_display' => array(
                        'name' => __('Display position of earn points product page message', 'wp-loyalty-rules'),
                        'description' => __('Choose where to show the earned points message on the product page', 'wp-loyalty-rules'),
                    ),
                    'is_cart_earn_message_enable' => array(
                        'name' => __('Enable cart page earn message ?', 'wp-loyalty-rules')
                    ),
                    'message_for_earn_points' => array(
                        'name' => __('Cart page message for earn points', 'wp-loyalty-rules'),
                        'description' => __('This text will be displayed in Cart when points are earned. Shortcodes: {wlr_cart_points}, {wlr_points_label}, {wlr_cart_rewards}, {wlr_reward_label}, {wlr_cart_point_or_reward}', 'wp-loyalty-rules'),
                    ),
                    'is_cart_redeem_message_enable' => array(
                        'name' => __('Enable cart page redeem message ?', 'wp-loyalty-rules')
                    ),
                    'is_checkout_redeem_message_enable' => array(
                        'name' => __('Enable checkout page redeem message ?', 'wp-loyalty-rules')
                    ),
                    'is_checkout_earn_message_enable' => array(
                        'name' => __('Enable checkout page earn message ?', 'wp-loyalty-rules')
                    ),
                    'message_for_checkout_earn_points' => array(
                        'name' => __('Checkout page message for earn points', 'wp-loyalty-rules'),
                        'description' => __('This text will be displayed in Checkout when points are earned. Shortcodes: {wlr_cart_points}, {wlr_points_label}, {wlr_cart_rewards}, {wlr_reward_label}, {wlr_cart_point_or_reward}', 'wp-loyalty-rules'),
                    ),
                    'earn_points_cart_page_message' => array(
                        'name' => __('Display position of earn points cart page message', 'wp-loyalty-rules'),
                        'description' => __('Choose where to show the earned points message on the cart page - before or after cart items', 'wp-loyalty-rules'),
                    ),
                    'earn_points_checkout_page_message' => array(
                        'name' => __('Display position of earn points checkout page message', 'wp-loyalty-rules'),
                        'description' => __('Choose where to show the earned points message on the checkout page - before or after checkout items', 'wp-loyalty-rules'),
                    ),
                    'message_for_redeemed_points' => array(
                        'name' => __('Cart page message for Redeem points', 'wp-loyalty-rules'),
                        'description' => __('This text will be displayed on the Cart page when points are available for Redemption. Shortcodes: {wlr_redeem_cart_points}, {wlr_points_label}, {wlr_reward_link}', 'wp-loyalty-rules'),
                    ),
                    'message_for_checkout_redeemed_points' => array(
                        'name' => __('Checkout page message for Redeem points', 'wp-loyalty-rules'),
                        'description' => __('This text will be displayed on the Checkout page when points are available for Redemption. Shortcodes: {wlr_redeem_cart_points}, {wlr_points_label}, {wlr_reward_link}', 'wp-loyalty-rules'),
                    ),
                    'redeem_points_cart_page_message' => array(
                        'name' => __('Display Position Of Redeem Points Cart Page Message', 'wp-loyalty-rules'),
                        'description' => __('Choose where to show the redeem points message on the cart page - before/after cart items', 'wp-loyalty-rules'),
                    ),
                    'redeem_points_checkout_page_message' => array(
                        'name' => __('Display Position Of Redeem Points Checkout Page Message', 'wp-loyalty-rules'),
                        'description' => __('Choose where to show the redeem points message on the Checkout page - before/after Checkout items', 'wp-loyalty-rules'),
                    ),
                    'thank_you_page' => array(
                        'name' => __('Message on Thank you page ', 'wp-loyalty-rules'),
                        'description' => __('This text will be displayed on the thank you / order received page when points were earned. Shortcodes: {wlr_earned_points}, {wlr_total_points}, {wlr_points_label}, {wlr_earned_rewards}, {wlr_reward_label}, {wlr_cart_point_or_reward}.', 'wp-loyalty-rules'),
                    ),
                    'thank_you_message' => array(
                        'name' => __('Thank you message display position', 'wp-loyalty-rules'),
                        'description' => __('Choose where to show the message on thank you page', 'wp-loyalty-rules'),
                    ),
                    'order_review' => array(
                        'name' => __('Order Review Earn Point Text', 'wp-loyalty-rules'),
                    ),
                    'border_color' => array(
                        'name' => __('Border color', 'wp-loyalty-rules'),
                        'description' => '',
                    ),
                    'text_color' => array(
                        'name' => __('Text color', 'wp-loyalty-rules'),
                        'description' => '',
                    ),
                    'background_color' => array(
                        'name' => __('Background color', 'wp-loyalty-rules'),
                        'description' => '',
                    ),
                    'used_point_image' => array(
                        'name' => __('Icon for Redeemed Points', 'wp-loyalty-rules'),
                        'description' => __('Choose an icon / image for the redeemed points section', 'wp-loyalty-rules'),
                    ),
                    'active_point_image' => array(
                        'name' => __('Icon for Available Points', 'wp-loyalty-rules'),
                        'description' => __('Choose an icon / image for the available points section', 'wp-loyalty-rules'),
                    ),
                    'used_reward_image' => array(
                        'name' => __('Icon for Used rewards', 'wp-loyalty-rules'),
                        'description' => __('Choose an icon / image for the used rewards section', 'wp-loyalty-rules'),
                    ),
                    'earn_message_image' => array(
                        'name' => __('Icon / image for the Earn Point message', 'wp-loyalty-rules'),
                        'description' => __('Choose an icon or image', 'wp-loyalty-rules'),
                    ),
                    'redeem_message_image' => array(
                        'name' => __('Icon / image for the Redeem Point message', 'wp-loyalty-rules'),
                        'description' => __('Choose an icon or image', 'wp-loyalty-rules'),
                    ),
                    'redeem_button_text' => array(
                        'name' => __('Redeem button text', 'wp-loyalty-rules'),
                        'description' => __('Redeem Now button text', 'wp-loyalty-rules'),

                    ),
                    'apply_coupon_button_text' => array(
                        'name' => __('Apply coupon button text', 'wp-loyalty-rules'),
                        'description' => __('Apply coupon button text', 'wp-loyalty-rules'),
                    ),
                    'redeem_button_color' => array(
                        'name' => __('Redeem button color', 'wp-loyalty-rules'),
                    ),
                    'redeem_button_text_color' => array(
                        'name' => __('Redeem button text color', 'wp-loyalty-rules'),
                    ),

                    'apply_coupon_border_color' => array(
                        'name' => __('Coupon code / border color', 'wp-loyalty-rules'),
                    ),
                    'apply_coupon_button_text_color' => array(
                        'name' => __('Apply coupon button text color', 'wp-loyalty-rules'),
                    ),
                    'apply_coupon_button_color' => array(
                        'name' => __('Apply coupon button color', 'wp-loyalty-rules'),
                    ),
                    'apply_coupon_background' => array(
                        'name' => __('Coupon code background color', 'wp-loyalty-rules'),
                    ),
                    'theme_color' => array(
                        'name' => __('Base theme color for customer reward page', 'wp-loyalty-rules'),
                    ),
                    'heading_color' => array(
                        'name' => __('Text color', 'wp-loyalty-rules'),
                    ),
                    'button_text_color' => array(
                        'name' => __('Button text color', 'wp-loyalty-rules'),
                    ),

                    'earn_message' => __('Earn Message', 'wp-loyalty-rules'),
                    'redeem_message' => __('Redeem Message', 'wp-loyalty-rules'),
                    'is_thankyou_page_message_enable' => array(
                        'name' => __('Enable Thank you page message ?', 'wp-loyalty-rules')
                    ),
                    'allowed_campaign_conditions' => array(
                        'name' => __('Allowed Campaign conditions', 'wp-loyalty-rules'),
                        'description' => __('You can choose conditions for allowed campaigns.', 'wp-loyalty-rules'),
                    ),

                ),
                'email' => array(
                    'name' => __('Emails', 'wp-loyalty-rules'),
                    'description' => __('Customize the emails by using the default WooCommerce email editor (based on theme override) or you can also use the  <a href="https://www.flycart.org/products/wordpress/woocommerce-email-customizer" style="color: blue;" target="_blank">"Email Customizer Plus"</a> with drag and drop editor to customize the emails.', 'wp-loyalty-rules'),
                    'subject' => __('Subject:', 'wp-loyalty-rules'),
                    'manage' => __('Manage', 'wp-loyalty-rules'),
                ),
            ),
            'onboard' => array(
                'skip' => __('Skip Onboarding', 'wp-loyalty-rules'),
//                'campaigns' => __('Campaigns', 'wp-loyalty-rules'),
//                'rewards' => __('Rewards', 'wp-loyalty-rules'),
                'support' => __('Support', 'wp-loyalty-rules'),
                'help' => __('Help Docs', 'wp-loyalty-rules'),
                'previous_step' => __('Previous Step', 'wp-loyalty-rules'),
                'book_demo_call' => __('Book a Demo Call', 'wp-loyalty-rules'),
                'referral_points' => __('Points', 'wp-loyalty-rules'),
                'continue' => __('Continue', 'wp-loyalty-rules'),
                'choose_program_type' => __('choose_program_type', 'wp-loyalty-rules'),
                'create_campaign' => __('create_campaign', 'wp-loyalty-rules'),
                'choose_referral_txt' => __('choose_referral', 'wp-loyalty-rules'),
                'create_reward' => __('create_reward', 'wp-loyalty-rules'),
                'choose_color' => __('choose_color', 'wp-loyalty-rules'),
                'summary' => __('summary', 'wp-loyalty-rules'),
                'completed_onboard' => __('completed_onboard', 'wp-loyalty-rules'),
//                'point_for_purchase' => __('Point for purchase', 'wp-loyalty-rules'),
                'order_goals' => __('Order Goals', 'wp-loyalty-rules'),
//                'birthday' => __('Birthday', 'wp-loyalty-rules'),
                'signup' => __('Signup', 'wp-loyalty-rules'),
                'point_conversion' => __('Point Conversion', 'wp-loyalty-rules'),
//                'fixed_discount' => __('Fixed Discount', 'wp-loyalty-rules'),
//                'percentage_discount' => __('Percentage Discount', 'wp-loyalty-rules'),
                'required_points' => __('Required points', 'wp-loyalty-rules'),
//                'discount_value' => __('Discount value', 'wp-loyalty-rules'),
//                'fixed_cart' => __('Fixed Discount', 'wp-loyalty-rules'),
//                'percent' => __('Percentage Discount', 'wp-loyalty-rules'),
                'points' => __('Points: ', 'wp-loyalty-rules'),
                'amount' => __('Amount: ', 'wp-loyalty-rules'),
                'discount' => __('Discount: ', 'wp-loyalty-rules'),

                'onboard_types' => array(
                    "choose_program_type", "create_campaign", "choose_referral", "create_referral",
                    "create_reward", "choose_color", "summary", "completed_onboard"
                ),

                'choose_type_program' => array(
                    'name' => __('What type of loyalty programs do you like to offer?', 'wp-loyalty-rules'),
                    'description' => __('We’ll guide you all over the process.', 'wp-loyalty-rules'),
                    'create' => __('Create', 'wp-loyalty-rules'),
                    'points' => array(
//                        'name' => __('Points', 'wp-loyalty-rules'),
                        'description' => __('Reward customers points for sign up, purchase and for every dollar they spent.', 'wp-loyalty-rules'),
                    ),
                    'referrals' => array(
                        'name' => __('Referrals', 'wp-loyalty-rules'),
                        'description' => __('Reward customers with coupons or discounts for referring their friends.', 'wp-loyalty-rules'),
                    ),
                ),
                'choose_campaign' => array(
                    'name' => __("Choose customer actions that you'd like to reward", 'wp-loyalty-rules'),
                    'description' => __('You can always change or create more actions anytime you want.', 'wp-loyalty-rules'),
                ),
                'choose_referral' => array(
                    'name' => __("Set up your referral reward", 'wp-loyalty-rules'),
                    'description' => __('Configure the reward for the friend and advocate accordingly.', 'wp-loyalty-rules'),
                    'friend' => __('Friend', 'wp-loyalty-rules'),
                    'advocate' => __('Advocate', 'wp-loyalty-rules'),
                ),
                'create_reward_label' => array(
                    'name' => __("Create rewards for customers to redeem their points", 'wp-loyalty-rules'),
                    'description' => __('You can always customize them anytime you want.', 'wp-loyalty-rules'),
                ),
                'choose_color_label' => array(
                    'name' => __("Pick a theme for your brand", 'wp-loyalty-rules'),
                    'label' => __("Theme color", 'wp-loyalty-rules'),
                    'description' => __('You can change them anytime.', 'wp-loyalty-rules'),
//                    'continue' => __('Continue', 'wp-loyalty-rules'),
                ),
                'summary_label' => array(
                    'name' => __("Here is an summary of your previous steps", 'wp-loyalty-rules'),
                    'description' => __('Check your preferences and click continue.', 'wp-loyalty-rules'),
                    'points_heading' => __('POINTS', 'wp-loyalty-rules'),
                    'referrals_heading' => __('REFERRALS', 'wp-loyalty-rules'),
                    'brand_theme_heading' => __('BRAND THEME', 'wp-loyalty-rules'),
                ),
                'completed_onboard_label' => array(
                    'name' => __('Progress completed!', 'wp-loyalty-rules'),
                    'description' => __('Yes! It’s done. You can now start using the WPLoyalty', 'wp-loyalty-rules'),
                    'launch' => __('Launch WPLoyalty', 'wp-loyalty-rules'),
                    'schedule_call' => __('Schedule a call', 'wp-loyalty-rules'),
                    'document_link' => __('Go to docs', 'wp-loyalty-rules'),
                    'support' => array(
                        'name' => __('Support', 'wp-loyalty-rules'),
                        'description' => __('Have any queries? Get instant support from our team anytime, anywhere.', 'wp-loyalty-rules'),
                    ),
                    'help_documents' => array(
                        'name' => __('Help Documents', 'wp-loyalty-rules'),
                        'description' => __('Make use of the help docs to get a better understanding of  how it works.', 'wp-loyalty-rules'),
                    ),
                ),

            ),
        );
        wp_send_json($json);
    }

    function localData()
    {
        $user_roles = self::$woocommerce->getUserRoles();
        $user_role_list = array();
        foreach ($user_roles as $value) {
            $user_role_list[] = array(
                'label' => $value['text'],
                'value' => $value['id']
            );
        }
        $is_pro = EarnCampaign::getInstance()->isPro();
        $setting_option = self::$woocommerce->getOptions('wlr_settings');
        if (!empty($setting_option)) update_option('wlr_is_on_boarding_completed', true, true);
        $export_files = self::$woocommerce->exportFileList();
        $rewards_table = new Rewards();
        $rewards = $rewards_table->getCouponRewardDropList();
        $order_status = self::$woocommerce->get_order_statuses();
        $order_status_list = $successful_order_status_list = $unsuccessful_order_status_list = array();
        $earning_status = (isset($setting_option['wlr_earning_status']) && !empty($setting_option['wlr_earning_status']) ? $setting_option['wlr_earning_status'] : array('processing', 'completed'));
	    if (is_string($earning_status)) {
		    $earning_status = explode(',', $earning_status);
	    }
        $removing_status = (isset($setting_option['wlr_removing_status']) && !empty($setting_option['wlr_removing_status']) ? $setting_option['wlr_removing_status'] : array());
	    if (is_string($removing_status)) {
		    $removing_status = explode(',', $removing_status);
	    }
        $un_success_order_list = array('failed', 'cancelled', 'refunded');
        $un_success_order_list = apply_filters('wlr_setting_unsuccessful_order_status_list', $un_success_order_list);
        foreach ($order_status as $key => $value) {
            if ($key == 'checkout-draft') {
                continue;
            }
            $order_status_list[] = array(
                'label' => $value,
                'value' => $key
            );
            if (!in_array($key, $un_success_order_list) || in_array($key,$earning_status)) {
                $successful_order_status_list[] = array(
                    'label' => $value,
                    'value' => $key
                );
            }
            if (in_array($key, $un_success_order_list) || in_array($key,$removing_status)) {
                $unsuccessful_order_status_list[] = array(
                    'label' => $value,
                    'value' => $key
                );
            }
        }
        $localize = array(
            'is_pro' => $is_pro,
            'is_on_boarding_completed' => get_option('wlr_is_on_boarding_completed', false),
            'common' => array(
                'campaign_condition_list' => self::$woocommerce->getCampaignConditionList(),
                'pagination_limit' => (int)isset($setting_option['pagination_limit']) && $setting_option['pagination_limit'] ? $setting_option['pagination_limit'] : 10,
                'asset_file_path' => WLR_PLUGIN_URL,
                'wlr_apps_nonce' => Woocommerce::create_nonce('wlr_apps_nonce'),
                'wlr_common_user_nonce' => Woocommerce::create_nonce('wlr_common_user_nonce'),
                'select2_nonce' => Woocommerce::create_nonce('wlr_ajax_select2'),
                'site_url' => site_url(),
                'version' => WLR_PLUGIN_VERSION,
                'edit_campaign_url' => admin_url('admin.php?page=wp-loyalty-rules#/edit_earn_campaign'),
                'edit_reward_url' => admin_url('admin.php?page=wp-loyalty-rules#/edit_reward'),
                'dashboard_url' => admin_url('admin.php?page=wp-loyalty-rules#/dashboard'),
                'point_users_url' => admin_url('admin.php?page=wp-loyalty-rules#/point_users'),
                'earn_campaign_url' => admin_url('admin.php?page=wp-loyalty-rules#/earn_campaign'),
                'rewards_url' => admin_url('admin.php?page=wp-loyalty-rules#/rewards'),
                'setting_url' => admin_url('admin.php?page=wp-loyalty-rules#/settings'),
                'product_action_list' => array(
                    'products' => __('Products', 'wp-loyalty-rules'),
                    //'productAttributes' => __('Product Attributes', 'wp-loyalty-rules'),
                    'productCategory' => __('Product Category', 'wp-loyalty-rules'),
                    'productSku' => __('Product SKU', 'wp-loyalty-rules'),
                    //'product_onsale' => __('On sale products', 'wp-loyalty-rules'),
                    'productTags' => __('Tags', 'wp-loyalty-rules'),
                ),
                'purchase_before_list' => array(
                    array(
                        'label' => __('All time', 'wp-loyalty-rules'),
                        'value' => 'all_time'
                    ),
                    array(
                        'label' => __('Current day', 'wp-loyalty-rules'),
                        'value' => 'now'
                    ),
                    array(
                        'label' => __('Current week', 'wp-loyalty-rules'),
                        'value' => 'this_week'
                    ),
                    array(
                        'label' => __('Current month', 'wp-loyalty-rules'),
                        'value' => 'first_day_of_this_month'
                    ),
                    array(
                        'label' => __('Current year', 'wp-loyalty-rules'),
                        'value' => 'first_day_of_january_this_year'
                    ),
                    array(
                        'label' => __('1 Day', 'wp-loyalty-rules'),
                        'value' => '-1_day'
                    ),
                    array(
                        'label' => __('2 Days', 'wp-loyalty-rules'),
                        'value' => '-2_days'
                    ),
                    array(
                        'label' => __('3 Days', 'wp-loyalty-rules'),
                        'value' => '-3_days'
                    ),
                    array(
                        'label' => __('4 Days', 'wp-loyalty-rules'),
                        'value' => '-4_days'
                    ),
                    array(
                        'label' => __('5 Days', 'wp-loyalty-rules'),
                        'value' => '-5_days'
                    ),
                    array(
                        'label' => __('6 Days', 'wp-loyalty-rules'),
                        'value' => '-6_days'
                    ),
                    array(
                        'label' => __('1 Week', 'wp-loyalty-rules'),
                        'value' => '-1_week'
                    ),
                    array(
                        'label' => __('2 Weeks', 'wp-loyalty-rules'),
                        'value' => '-2_weeks'
                    ),
                    array(
                        'label' => __('3 Weeks', 'wp-loyalty-rules'),
                        'value' => '-3_weeks'
                    ),
                    array(
                        'label' => __('4 Weeks', 'wp-loyalty-rules'),
                        'value' => '-4_weeks'
                    ),
                    array(
                        'label' => __('1 Month', 'wp-loyalty-rules'),
                        'value' => '-1_month'
                    ),
                    array(
                        'label' => __('2 Months', 'wp-loyalty-rules'),
                        'value' => '-2_months'
                    ),
                    array(
                        'label' => __('3 Months', 'wp-loyalty-rules'),
                        'value' => '-3_months'
                    ),
                    array(
                        'label' => __('4 Months', 'wp-loyalty-rules'),
                        'value' => '-4_months'
                    ), array(
                        'label' => __('5 Months', 'wp-loyalty-rules'),
                        'value' => '-5_months'
                    ),
                    array(
                        'label' => __('6 Months', 'wp-loyalty-rules'),
                        'value' => '-6_months'
                    ),
                    array(
                        'label' => __('7 Months', 'wp-loyalty-rules'),
                        'value' => '-7_months'
                    ),
                    array(
                        'label' => __('8 Months', 'wp-loyalty-rules'),
                        'value' => '-8_months'
                    ),
                    array(
                        'label' => __('9 Months', 'wp-loyalty-rules'),
                        'value' => '-9_months'
                    ),
                    array(
                        'label' => __('10 Months', 'wp-loyalty-rules'),
                        'value' => '-10_months'
                    ),
                    array(
                        'label' => __('11 Months', 'wp-loyalty-rules'),
                        'value' => '-11_months'
                    ),
                    array(
                        'label' => __('12 Months', 'wp-loyalty-rules'),
                        'value' => '-12_months'
                    ),
                    array(
                        'label' => __('2 Years', 'wp-loyalty-rules'),
                        'value' => '-2_years'
                    ),
                    array(
                        'label' => __('3 Years', 'wp-loyalty-rules'),
                        'value' => '-3_years'
                    ),
                )
            ),
            'level_icon' => WLR_PLUGIN_URL . 'Assets/Site/image/default-level.png',
            'home_url' => get_home_url(),
            'admin_url' => admin_url(),
            'plugin_url' => WLR_PLUGIN_URL,
            'ajax_url' => admin_url('admin-ajax.php'),
            'action_types' => self::$woocommerce->getAllActionTypes(),
            'point_users' => array(
                'wlr_user_nonce' => Woocommerce::create_nonce('wlr-user-nonce'),
                'href_link' => admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG, 'view' => 'point_user_details'))),
                'show_export_file_download' => count($export_files),
                'export_csv_file_list' => $export_files,
            ),
            'point_user_details' => array(
                'wlr_user_detail_nonce' => Woocommerce::create_nonce('wlr-user-detail-nonce'),
            ),
            'earn_campaign' => array(
                'wlr_earn_campaign_nonce' => Woocommerce::create_nonce('wlr-earn-campaign-nonce'),
                'add_new_campaign' => admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG, 'view' => 'add_new_campaign'))),
            ),
            'edit_earn_campaign' => array(
                'wlr_campaign_nonce' => Woocommerce::create_nonce('wlr-campaign-nonce'),
                'action_accept_conditions' => self::$woocommerce->getActionAcceptConditions(),
                'reward_list' => $rewards,
            ),
            'rewards' => array(
                'wlr_reward_nonce' => Woocommerce::create_nonce('wlr-reward-nonce'),
                'add_new_reward_url' => admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG, 'view' => 'add_new_reward'))),
            ),
            'edit_reward' => array(
                'wlr_edit_reward_nonce' => Woocommerce::create_nonce('wlr-edit-reward-nonce'),
                'expire_period_list' => self::$woocommerce->getDatePeriod(),
            ),
            'common_condition' => array(
                'action_accept_conditions' => self::$woocommerce->getRewardAcceptConditions(),
            ),
            'user_role' => array(
                'user_role_list' => $user_role_list
            ),
            'settings' => array(
                'wlr_setting_nonce' => Woocommerce::create_nonce('wlr_setting_nonce'),
            ),
            'purchase_point' => array(
                'order_status_list' => $order_status_list,
                'successful_order_status_list' => $successful_order_status_list,
                'unsuccessful_order_status_list' => $unsuccessful_order_status_list,
                'customer_add_action_list' => array(
                    array('value' => 'signin', 'label' => __('When Signin', 'wp-loyalty-rules')),
                    array('value' => 'signup', 'label' => __('When Signup', 'wp-loyalty-rules'))
                )
            ),
            'front_message' => array(
                'product_event_list' => array(
                    'before_price' => __('Before Product Price', 'wp-loyalty-rules'),
                    'after_price' => __('After Product Price', 'wp-loyalty-rules'),
                    'before_add_to_cart' => __('Before Add to Cart', 'wp-loyalty-rules'),
                    'after_add_to_cart' => __('After Add to Cart', 'wp-loyalty-rules'),
                    'before_title' => __('Before Product Title', 'wp-loyalty-rules'),
                    'after_title' => __('After Product Title', 'wp-loyalty-rules'),
                ),
                'cart_earn_point_message_position_event' => array(
                    'before' => __('Before Cart items', 'wp-loyalty-rules'),
                    'after' => __('After Cart items', 'wp-loyalty-rules'),
                    //'hide' => __('Hide Message', 'wp-loyalty-rules'),
                    /*'content' => __('Before Update Cart', 'wp-loyalty-rules'),
                    'summary' => __('Order Review', 'wp-loyalty-rules')*/
                ),
                'cart_redeem_point_message_position_event' => array(
                    'before' => __('Before Cart items', 'wp-loyalty-rules'),
                    'after' => __('After Cart items', 'wp-loyalty-rules'),
                    //'hide' => __('Hide Message', 'wp-loyalty-rules'),
                    //'content' => __('Before Update Cart', 'wp-loyalty-rules')
                ),
                'thank_you_page_position_event' => array(
                    'before' => __('Top of the thank you message', 'wp-loyalty-rules'), 'after' => __('After order details', 'wp-loyalty-rules')),
            ),
            'dashboard' => array(
                'wlr_dashboard_nonce' => Woocommerce::create_nonce('wlr_dashboard_nonce'),
                'default_currency' => get_woocommerce_currency(),
                'list_of_currency' => get_woocommerce_currencies()
            ),
            'levels' => array('levels_nonce' => Woocommerce::create_nonce('levels_nonce'))
        );
        $localize = apply_filters('wlr_pro_local_data', $localize);
        wp_send_json($localize);
    }
}