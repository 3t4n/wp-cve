<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */
defined('ABSPATH') or die;
$earn_campaign_helper = \Wlr\App\Helpers\EarnCampaign::getInstance();
$woocommerce_helper = new \Wlr\App\Helpers\Woocommerce();
$theme_color = isset($branding) && is_array($branding) && isset($branding["theme_color"]) && !empty($branding["theme_color"]) ? $branding["theme_color"] : "#4F47EB";
$border_color = isset($branding) && is_array($branding) && isset($branding["border_color"]) && !empty($branding["border_color"]) ? $branding["border_color"] : "#CFCFCF";
$heading_color = isset($branding) && is_array($branding) && isset($branding["heading_color"]) && !empty($branding["heading_color"]) ? $branding["heading_color"] : "#1D2327";
$background_color = isset($branding) && is_array($branding) && isset($branding["background_color"]) && !empty($branding["background_color"]) ? $branding["background_color"] : "#ffffff";
$button_text_color = isset($branding) && is_array($branding) && isset($branding["button_text_color"]) && !empty($branding["button_text_color"]) ? $branding["button_text_color"] : "#ffffff";
$is_right_to_left = is_rtl();
?>
<style>
    .wlr-myaccount-page {
    <?php echo !empty($background_color) ? esc_attr("background-color:".$background_color.";") : "";?>
    }

    .wlr-myaccount-page .wlr-heading {
    <?php echo !empty($heading_color) ? esc_attr("color:" . $heading_color . " !important;") : "";?><?php echo !empty($theme_color) ? esc_attr("border-left: 3px solid " . $theme_color . " !important;") : "";?>
    }

    .wlr-myaccount-page .wlr-theme-color-apply {
    <?php echo isset($theme_color) && !empty($theme_color) ?  esc_attr("color :".$theme_color.";") : "";?>;
    }

    .wlr-myaccount-page .wlr-earning-options .wlr-card .wlr-date {
    <?php echo $is_right_to_left ? "left: 0;right:unset;": "right:0;left:unset;";?>
    }

    .wlr-myaccount-page .wlr-your-reward .wlr-reward-type-name {
    <?php echo $is_right_to_left ? "float: left;border-radius: 8px 0 2px 0;": "float:right;";?>
    }

    .wlr-myaccount-page .wlr-progress-bar .wlr-progress-level {
        background-color: <?php echo esc_attr($theme_color);?>;
    }

    .wlr-myaccount-page .wlr-text-color {
        color: <?php echo esc_attr($heading_color);?>
    }

    .wlr-myaccount-page .wlr-border-color {
        border-color: <?php echo esc_attr($border_color);?>;
    }

    .wlr-myaccount-page .wlr-button-text-color {
        color: <?php echo esc_attr($button_text_color);?>
    }

    .wlr-myaccount-page table:not( .has-background ) th {
        background-color: <?php echo esc_attr($theme_color."30");?>;
    }

    .wlr-myaccount-page table thead {
        outline: solid 1px<?php echo esc_attr($border_color);?>
    }

    .alertify .ajs-ok {
        color: <?php echo esc_attr($button_text_color);?>;
        background: <?php echo esc_attr($theme_color);?>;
    }

    .alertify .ajs-cancel {
        border: <?php echo esc_attr("1px solid ".$theme_color);?>;
        color: <?php echo esc_attr($theme_color);?>;
        background: unset;
    }

    .wlr-myaccount-page .wlr-my-rewards-title.active {
        border-bottom: 3px solid<?php echo esc_attr($theme_color);?>;
    }

    .wlr-myaccount-page .wlr-my-rewards-title.active h4,
    .wlr-myaccount-page .wlr-my-rewards-title.active i {
        color: <?php echo esc_attr($theme_color);?>;
    }
    .wlr-myaccount-page .wlr-coupons-expired-content .wlr-card-icon-container i{
        color:<?php echo esc_attr($heading_color);?>;
    }
</style>
<div class="wlr-myaccount-page">
    <?php do_action('wlr_before_customer_reward_page_content'); ?>
    <?php if ((isset($user) && is_object($user) && isset($user->id) && $user->id > 0) || get_current_user_id()): ?>
        <div class="wlr-user-details">
            <div class="wlr-heading-container">
                <h3 class="wlr-heading"><?php echo esc_html(sprintf(__('My %s', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel(3))); ?></h3>
            </div>

            <div class="wlr-points-container">
                <?php do_action('wlr_before_customer_reward_page_my_points_content'); ?>
                <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-points') ?>">
                    <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-available-points'); ?>"
                         class="wlr-border-color">
                        <div>
                            <?php $img_icon = isset($branding) && is_array($branding) && isset($branding["available_point_icon"]) && !empty($branding["available_point_icon"]) ? $branding["available_point_icon"] : ""; ?>
                            <?php echo \Wlr\App\Helpers\Base::setImageIcon($img_icon, "available-points", array(
                                "alt" => __("Available point", "wp-loyalty-rules"),
                                "height" => 64,
                                "width" => 64
                            )); ?>
                        </div>
                        <div>
                            <?php $user_points = (int)(isset($user) && !empty($user) && isset($user->points) && !empty($user->points) ? $user->points : 0); ?>
                            <span id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-available-points-heading'); ?>"
                                  class="wlr-text-color">
        <?php echo esc_html(sprintf(__('Available %s', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel($user_points))) ?></span>
                            <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-available-point-value') ?>"
                                 class="wlr-text-color">
                                <?php echo $user_points; ?>
                            </div>
                            <?php if (isset($user->earn_total_point) && !empty($user->earn_total_point)): ?>
                                <div class="wlr-text-color">
                                    <p> <?php echo sprintf(__('Total %s earned: %s', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel($user->earn_total_point), $user->earn_total_point); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-redeemed-points') ?>" class="wlr-border-color">
                        <div>
                            <?php $img_icon = isset($branding) && is_array($branding) && isset($branding["redeem_point_icon"]) && !empty($branding["redeem_point_icon"]) ? $branding["redeem_point_icon"] : ""; ?>
                            <?php echo \Wlr\App\Helpers\Base::setImageIcon($img_icon, "redeem-points", array(
                                "alt" => __("Redeem point", "wp-loyalty-rules"),
                                "height" => 64,
                                "width" => 64
                            )); ?>
                        </div>
                        <div>
                            <?php $user_total_points = (int)(isset($user) && !empty($user) && isset($user->used_total_points) && !empty($user->used_total_points) ? $user->used_total_points : 0); ?>
                            <span id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-redeemed-points-heading') ?>"
                                  class="wlr-text-color">
        <?php echo esc_html(sprintf(__('Redeemed %s', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel($user_total_points))) ?></span>
                            <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-redeemed-point-value') ?>"
                                 class="wlr-text-color">
                                <?php echo $user_total_points; ?>
                            </div>
                            <?php if (isset($user) && !empty($user) && isset($user->total_coupon_count) && !empty($user->total_coupon_count)): ?>
                                <div class="wlr-text-color">
                                    <p> <?php echo sprintf(__('%s to Coupons : %s ', 'wp-loyalty-rules'), ucfirst($earn_campaign_helper->getPointLabel(3)),$user->total_coupon_count); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-used-rewards'); ?>" class="wlr-border-color">
                        <div style="display: flex;justify-content: space-between;align-items:center;">
                            <div>
                                <?php $img_icon = isset($branding) && is_array($branding) && isset($branding["used_reward_icon"]) && !empty($branding["used_reward_icon"]) ? $branding["used_reward_icon"] : ""; ?>
                                <?php echo \Wlr\App\Helpers\Base::setImageIcon($img_icon, "used-rewards", array("alt" => __("User used rewards", "wp-loyalty-rules"), "height" => 64, "width" => 64)); ?>

                            </div>
                            <div>
                                <?php if (isset($used_reward_currency_values) && !empty($used_reward_currency_values) && isset($current_currency_list) && isset($used_reward_currency_value_count)) : ?>
                                    <select id="wlr_currency_list" class="wlr-border-color wlr-text-color"
                                            data-user-used-reward='<?php echo json_encode($used_reward_currency_values); ?>'
                                            data-user-used-reward-count='<?php echo json_encode($used_reward_currency_value_count); ?>'
                                            onchange="wlr_jquery( 'body' ).trigger( 'wlr_get_used_reward')">
                                        <?php foreach ($current_currency_list as $currency_key => $currency_label): ?>
                                            <option value="<?php echo $currency_key; ?>"
                                                <?php echo (isset($current_currency) && !empty($current_currency) && ($currency_key === $current_currency)) ? "selected" : ""; ?>><?php echo $currency_key; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            </div>

                        </div>
                        <div>
                            <span id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-used-rewards-heading'); ?>"
                                  class="wlr-text-color">
        <?php echo esc_html(sprintf(__('Used %s', 'wp-loyalty-rules'), $earn_campaign_helper->getRewardLabel())) ?></span>


                            <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-used-reward-value-count') ?>"
                                 class="wlr-text-color">
                                <?php echo isset($used_reward_currency_value_count) && !empty($used_reward_currency_value_count)
                                && isset($current_currency) && !empty($current_currency) ? $used_reward_currency_value_count[$current_currency] : 0 ?>
                            </div>
                            <div class="wlr-text-color">
                                <p id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-used-reward-value') ?>">
                                    <?php echo isset($used_reward_currency_values) && !empty($used_reward_currency_values)
                                    && isset($current_currency) && !empty($current_currency) ? $used_reward_currency_values[$current_currency] : "" ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $is_user_available = (isset($user) && is_object($user) && isset($user->id) && $user->id > 0);
        $level_check = $is_user_available && isset($user->level_data) && is_object($user->level_data) && isset($user->level_data->current_level_name) && !empty($user->level_data->current_level_name); ?>
        <?php if ($is_user_available && isset($user->level_id) && $user->level_id > 0 && $level_check): ?>
            <div class="wlr-level-details">
                <div class="wlr-heading-container">
                    <h3 class="wlr-heading"><?php echo esc_html(__('My Levels', 'wp-loyalty-rules')); ?></h3>
                </div>
                <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-levels') ?>" class="wlr-border-color">
                    <div class="wlr-level-name-section">
                        <div class="wlr-current-level-container">
                            <div class="wlr-level-image">
                                <?php if (isset($user->level_data->current_level_image) && !empty($user->level_data->current_level_image)): ?>
                                    <?php echo \Wlr\App\Helpers\Base::setImageIcon($user->level_data->current_level_image, "", array(
                                        "alt" => __("Level image", "wp-loyalty-rules"),
                                        "height" => 40,
                                        "width" => 40
                                    )); ?>
                                <?php endif; ?>
                            </div>
                            <div class="wlr-level-title-section">
                                <p id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-level-name'); ?>"
                                   class="wlr-points-name wlr-text-color">
                                    <?php echo !empty($user->level_data) && !empty($user->level_data->current_level_name) ? esc_html(__($user->level_data->current_level_name, 'wp-loyalty-rules')) : '' ?>
                                </p>
                                <p class="wlr-text-color"><?php echo __('Current level', 'wp-loyalty-rules'); ?></p>
                            </div>
                        </div>
                        <div class="wlr-next-level-container wlr-level-title-section">
                            <p id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-next-level-name'); ?>"
                               class="wlr-points-name wlr-text-color">
                                <?php echo !empty($user->level_data) && !empty($user->level_data->next_level_name) ? esc_html(__($user->level_data->next_level_name, 'wp-loyalty-rules')) : '' ?>
                            </p>
                            <?php if (!empty($user->level_data) && !empty($user->level_data->next_level_name)): ?>
                                <p class="wlr-text-color"><?php echo __('Next level', 'wp-loyalty-rules'); ?></p>
                            <?php else: ?>
                                <p class="wlr-text-color"><?php echo __('No next level', 'wp-loyalty-rules'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="wlr-level-data-section">
                        <div class="wlr-level-content">
                            <?php
                            if (isset($user->level_data->current_level_start) && isset($user->level_data->next_level_start) && $user->level_data->next_level_start > 0):
                                $css_width = (($user->earn_total_point - $user->level_data->current_level_start) / ($user->level_data->next_level_start - $user->level_data->current_level_start)) * 100;
                                $needed_point = $user->level_data->next_level_start - $user->earn_total_point;
                                ?>
                                <div class="level-points wlr-border-color">
                                    <p class="wlr-progress-content wlr-text-color">
                                        <?php echo esc_html(sprintf(__('%d %s more needed to unlock next level', 'wp-loyalty-rules'), (int)$needed_point, $earn_campaign_helper->getPointLabel($needed_point))); ?>
                                    </p>
                                    <div class="wlr-level-bar-container">
                                        <i class="wlrf-tick_circle wlr-theme-color-apply"></i>
                                        <div class="wlr-progress-bar">
                                            <div class="wlr-progress-level"
                                                 style="<?php echo esc_attr("width:" . $css_width . '%'); ?>">
                                            </div>
                                        </div>
                                        <i class="wlrf-progress-donut  wlr-text-color"></i>

                                    </div>
                                    <div class="wlr-levels-bar-footer">
                                        <b class="wlr-text-color"><?php echo isset($user->level_data->from_points) ? $user->level_data->from_points : ''; ?></b>
                                        <b class="wlr-text-color"><?php echo isset($user->level_data->next_level_start) ? $user->level_data->next_level_start : ''; ?></b>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="level-points wlr-border-color">
                                    <h4 class="wlr-progress-content wlr-text-color"><?php echo __('Congratulations!', 'wp-loyalty-rules'); ?></h4>
                                    <p class="wlr-progress-content wlr-text-color">
                                        <?php echo esc_html__('You have reached the final level', 'wp-loyalty-rules'); ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php do_action('wlr_before_customer_reward_page_referral_url_content'); ?>
    <!--    customer referral start here -->
    <?php
    if ((isset($is_referral_action_available) && in_array($is_referral_action_available, array(
            'yes',
            1
        )) && isset($referral_url) && !empty($referral_url))): ?>
        <div class="wlr-referral-blog">
            <div class="wlr-heading-container">
                <h3 class="wlr-heading"><?php echo esc_html__('Referral link', 'wp-loyalty-rules'); ?></h3>
            </div>

            <div class="wlr-referral-box wlr-border-color">
                <input type="text" value="<?php echo esc_url($referral_url); ?>" id="wlr_referral_url_link"
                       class="wlr_referral_url wlr-text-color" disabled/>
                <div class="input-group-append"
                     onclick="wlr_jquery( 'body' ).trigger( 'wlr_copy_link',[ 'wlr_referral_url_link'])">
                    <span class="input-group-text wlr-button-text-color"
                          style="<?php echo isset($theme_color) && !empty($theme_color) ? esc_attr("background:" . $theme_color . ";") : ""; ?>">
                        <i class="wlr wlrf-copy wlr-icon wlr-button-text-color"
                           title="<?php esc_html_e("copy to clipboard", 'wp-loyalty-rules'); ?>"
                           style="font-size:20px;margin-top:4px"></i>
                        <?php echo esc_html__('Copy Link', 'wp-loyalty-rules'); ?>
                    </span>
                </div>
            </div>

            <?php if (isset($social_share_list) && !empty($social_share_list)): ?>
                <div class="wlr-social-share">
                    <?php foreach ($social_share_list as $action => $social_share): ?>
                        <a class="wlr-icon-list"
                           onclick="wlr_jquery( 'body' ).trigger( 'wlr_apply_social_share', [ '<?php echo esc_js($social_share['url']); ?>','<?php echo esc_js($action); ?>' ] )"
                           target="_parent">
                            <?php $social_icon = isset($social_share['icon']) && !empty($social_share['icon']) ? $social_share['icon'] : "";
                            $social_image_icon = isset($social_share['image_icon']) && !empty($social_share['image_icon']) ? $social_share['image_icon'] : "social";
                            ?>
                            <?php echo \Wlr\App\Helpers\Base::setImageIcon($social_image_icon, $social_icon, array("alt" => $social_share["name"])); ?>

                            <span
                                class="wlr-social-text wlr-text-color"><?php echo esc_html($social_share['name']); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <!--    customer referral end here -->
    <?php do_action('wlr_before_customer_reward_page_user_rewards_content'); ?>
    <!--    customer rewards start here -->
    <?php
    if (isset($user_rewards) && !empty($user_rewards)): ?>
        <div class="wlr-your-reward" id="wlr-your-reward">
            <div class="wlr-heading-container"><h3
                    class="wlr-heading"><?php echo esc_html(sprintf(__('My %s', 'wp-loyalty-rules'), $earn_campaign_helper->getRewardLabel(3))); ?></h3>
            </div>
            <div class="wlr-customer-reward">
                    <?php $card_key = 1;
                    foreach ($user_rewards as $u_reward): ?>
                        <?php
                        $button_text = isset($branding) && is_array($branding) && isset($branding["redeem_button_text"]) && !empty($branding["redeem_button_text"]) ? $branding["redeem_button_text"] : "";
                        $revert_button = sprintf(__('Revert to %s', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel(3));
                        $redeem_button_color = isset($branding) && is_array($branding) && isset($branding["redeem_button_color"]) && !empty($branding["redeem_button_color"]) ? $branding["redeem_button_color"] : "";
                        $button_color = $redeem_button_color ? "background:" . $redeem_button_color . ";" : "background:" . $theme_color . ";";
                        $redeem_button_text_color = isset($branding) && is_array($branding) && isset($branding["redeem_button_text_color"]) && !empty($branding["redeem_button_text_color"]) ? $branding["redeem_button_text_color"] : "";
                        $button_text_color = $redeem_button_text_color ? "color:" . $redeem_button_text_color . ";" : "";
                        $css_class_name = 'wlr-button-reward wlr-button wlr-button-action';
                        ?>
                        <div
                            class="<?php echo (isset($u_reward->discount_code) && !empty($u_reward->discount_code)) ? 'wlr-coupon-card' : 'wlr-reward-card'; ?> wlr-border-color">
                            <div
                                style="<?php echo $is_right_to_left ? "margin-left: -12px;" : "margin-right: -12px;"; ?>">
                                <p class="wlr-reward-type-name wlr-text-color wlr-border-color">
                                    <?php echo $u_reward->reward_type_name; ?>
                                    <?php $discount_value = isset($u_reward->discount_value) && !empty($u_reward->discount_value) && ($u_reward->discount_value != 0) ? ($u_reward->discount_value) : ''; ?>
                                    <?php if ($discount_value > 0 && isset($u_reward->discount_type) && in_array($u_reward->discount_type, array(
                                            'percent',
                                            'fixed_cart',
                                            'points_conversion'
                                        ))): ?>
                                        <?php if (($u_reward->discount_type == 'points_conversion') && !empty($u_reward->discount_code)) : ?>
                                            <?php echo " - " . $woocommerce_helper->getCustomPrice($discount_value); ?>
                                        <?php elseif ($u_reward->discount_type != 'points_conversion'): ?>
                                            <?php echo ($u_reward->discount_type == 'percent') ? " - " . round($discount_value) . "%" : " - " . $woocommerce_helper->getCustomPrice($discount_value); ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="wlr-card-container">
                                <div class="wlr-card-icon-container">
                                    <div class="wlr-card-icon">
                                        <?php $discount_type = isset($u_reward->discount_type) && !empty($u_reward->discount_type) ? $u_reward->discount_type : "" ?>
                                        <?php $img_icon = isset($u_reward->icon) && !empty($u_reward->icon) ? $u_reward->icon : "" ?>
                                        <?php echo \Wlr\App\Helpers\Base::setImageIcon($img_icon, $discount_type, array("alt" => $u_reward->name)); ?>
                                    </div>

                                </div>
                                <div class="wlr-card-inner-container">
                                    <h4 class="wlr-name wlr-text-color">
                                        <?php echo \Wlr\App\Helpers\Base::readMoreLessContent($u_reward->name, $card_key, 60, __("Show more", "wp-loyalty-rules"), __("Show less", "wp-loyalty-rules"), 'card-my-reward-name', 'wlr-name wlr-pre-text wlr-text-color'); ?>
                                    </h4>
                                    <?php $description = apply_filters('wlr_my_account_reward_desc', $u_reward->description, $u_reward); ?>
                                    <?php if (isset($u_reward->discount_code) && !empty($u_reward->discount_code)): ?>
                                        <?php
                                        $button_text = isset($branding) && is_array($branding) && isset($branding["apply_coupon_button_text"]) && !empty($branding["apply_coupon_button_text"]) ? $branding["apply_coupon_button_text"] : "";
                                        $apply_coupon_button_color = isset($branding) && is_array($branding) && isset($branding["apply_coupon_button_color"]) && !empty($branding["apply_coupon_button_color"]) ? $branding["apply_coupon_button_color"] : "";
                                        $button_color = $apply_coupon_button_color ? "background:" . $apply_coupon_button_color . ";" : "background:" . $theme_color . ";";
                                        $apply_coupon_button_text_color = isset($branding) && is_array($branding) && isset($branding["apply_coupon_button_text_color"]) && !empty($branding["apply_coupon_button_text_color"]) ? $branding["apply_coupon_button_text_color"] : "";
                                        $button_text_color = $apply_coupon_button_text_color ? "color:" . $apply_coupon_button_text_color . ";" : "";
                                        $apply_coupon_border_color = isset($branding) && is_array($branding) && isset($branding["apply_coupon_border_color"]) && !empty($branding["apply_coupon_border_color"]) ? $branding["apply_coupon_border_color"] : "";
                                        $coupon_border = $apply_coupon_border_color ? "border:1px dashed " . $apply_coupon_border_color . ";" : "";
                                        $coupon_copy_icon_color = $apply_coupon_border_color ? "background:" . $apply_coupon_border_color . ";" : "";
                                        $apply_coupon_background = isset($branding) && is_array($branding) && isset($branding["apply_coupon_background"]) && !empty($branding["apply_coupon_background"]) ? $branding["apply_coupon_background"] : "";
                                        $coupon_background = $apply_coupon_background ? "background:" . $apply_coupon_background . ";" : "";
                                        $css_class_name = 'wlr-button-reward-apply wlr-button wlr-button-action';
                                        ?>
                                        <div class="wlr-code" style="<?php echo esc_attr($coupon_border); ?>">
                                            <div class="wlr-coupon-code"
                                                 style="<?php echo esc_attr($coupon_background); ?>">
                                                <p title="<?php esc_html_e('Coupon Code', 'wp-loyalty-rules'); ?>"
                                                   onclick="wlr_jquery( 'body' ).trigger( 'wlr_copy_coupon',[ '<?php echo esc_js('#wlr-' . $u_reward->discount_code) ?>','<?php echo esc_js('#wlr-icon-' . $u_reward->discount_code) ?>'])">
                                                <span
                                                    style="<?php echo !empty($apply_coupon_border_color) ? esc_attr("color:" . $apply_coupon_border_color . ";") : ""; ?>"
                                                    id="<?php echo esc_attr('wlr-' . $u_reward->discount_code) ?>"><?php echo esc_html($u_reward->discount_code); ?></span>
                                                </p>
                                            </div>
                                            <div class="wlr-coupon-copy-icon"
                                                 style="<?php echo esc_attr("color:" . $apply_coupon_border_color . ";" . $coupon_background); ?>">
                                                <i id="<?php echo esc_attr('wlr-icon-' . $u_reward->discount_code) ?>"
                                                   class="wlr wlrf-copy wlr-icon"
                                                   title="<?php esc_html_e('copy to clipboard', 'wp-loyalty-rules'); ?>"
                                                   onclick="wlr_jquery( 'body' ).trigger( 'wlr_copy_coupon',[ '<?php echo esc_js('#wlr-' . $u_reward->discount_code) ?>','<?php echo esc_js('#wlr-icon-' . $u_reward->discount_code) ?>'])"
                                                   style="font-size:20px;"></i>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($description) && !(isset($u_reward->discount_code) && !empty($u_reward->discount_code))): ?>
                                        <?php echo \Wlr\App\Helpers\Base::readMoreLessContent($description, $card_key, 90, __("Show more", "wp-loyalty-rules"), __("Show less", "wp-loyalty-rules"), 'card-my-reward-description', 'wlr-description wlr-pre-text wlr-text-color'); ?>

                                    <?php endif; ?>
                                </div>
                                <?php if (isset($u_reward->discount_type) && $u_reward->discount_type == 'points_conversion' && $u_reward->reward_table != 'user_reward'): ?>
                                    <div style="display: none;" class="wlr-point-conversion-section wlr-border-color"
                                         id="<?php echo esc_attr('wlr_point_conversion_div_' . $u_reward->id); ?>">
                                        <div><i class="wlrf-close wlr-cursor wlr-text-color"
                                                title="<?php _e('Close', 'wp-loyalty-rules'); ?>"
                                                onclick="wlr_jquery('<?php echo esc_js('#wlr_point_conversion_div_' . $u_reward->id); ?>').hide();wlr_jquery('<?php echo esc_js('#wlr-button-action-' . $card_key) ?>').show();">
                                            </i></div>
                                        <div style="display: flex;gap: 15%">
                                            <div class="wlr-input-point-section">
                                                <div class="wlr-input-point-conversion wlr-border-color">
                                                    <input type="text" min="1" pattern="/^[0-9]+$/"
                                                           class="wlr-point-conversion-box wlr-text-color"
                                                           onkeypress="return wlr_jquery('body').trigger('wlr_validate_number');"
                                                           onchange="wlr_jquery('body').trigger('wlr_calculate_point_conversion',
                                                               ['<?php echo esc_js('wlr_point_conversion_' . $u_reward->id); ?>','<?php echo esc_js('wlr_point_conversion_' . $u_reward->id . '_value'); ?>']);"
                                                           onkeyup="wlr_jquery('body').trigger('wlr_calculate_point_conversion',
                                                               ['<?php echo esc_js('wlr_point_conversion_' . $u_reward->id); ?>','<?php echo esc_js('wlr_point_conversion_' . $u_reward->id . '_value'); ?>']);"
                                                           id="<?php echo esc_attr('wlr_point_conversion_' . $u_reward->id); ?>"
                                                           value="<?php echo esc_attr($u_reward->input_point); ?>"
                                                           oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                                           data-require-point="<?php echo esc_attr($u_reward->require_point); ?>"
                                                           data-discount-value="<?php echo esc_attr($woocommerce_helper->getCustomPrice($u_reward->discount_value, false)); ?>"
                                                           data-available-point="<?php echo esc_attr($u_reward->available_point); ?>"
                                                           data-cart-amount="<?php echo esc_attr($u_reward->cart_amount); ?>"
                                                    ></div>
                                                <div class="wlr-point-label-content wlr-border-color">
                                                    <p class="wlr-input-point-title wlr-text-color"><?php
                                                        $woocommerce_currency = $woocommerce_helper->getDisplayCurrency();
                                                        echo sprintf(__('=(%s)%s', 'wp-loyalty-rules'), $woocommerce_currency, $woocommerce_helper->getCurrencySymbols($woocommerce_currency)); ?>
                                                        &nbsp;<span
                                                            id="<?php echo esc_attr('wlr_point_conversion_' . $u_reward->id . '_value'); ?>"
                                                            class="wlr-point-conversion-discount-label">
                                                    <?php echo $u_reward->input_value; ?>
                                                </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            id="<?php echo esc_attr('wlr_point_conversion_' . $u_reward->id . '_button'); ?>"
                                            class="wlr-button wlr-button-action"
                                            style="<?php echo esc_attr($button_color); ?>"
                                            onclick="wlr_jquery( 'body' ).trigger( 'wlr_apply_point_conversion_reward',['<?php echo esc_js($u_reward->id); ?>', '<?php echo esc_js($u_reward->reward_table); ?>', '<?php echo esc_js($u_reward->available_point); ?>','<?php echo esc_js('#wlr_point_conversion_' . $u_reward->id); ?>' ,'<?php echo esc_js('#wlr_point_conversion_' . $u_reward->id . '_button'); ?>'] );">
                                        <span class="wlr-action-text"
                                              style="<?php echo esc_attr($button_text_color); ?>"><?php echo esc_html__('Redeem', 'wp-loyalty-rules'); ?></span>
                                        </div>
                                    </div>
                                    <div class="<?php echo esc_attr($css_class_name); ?>"
                                         style="<?php echo esc_attr($button_color); ?>"
                                         id="<?php echo esc_attr('wlr-button-action-' . $card_key) ?>"
                                         onclick="wlr_jquery('<?php echo esc_js('#wlr_point_conversion_div_' . $u_reward->id); ?>').show();wlr_jquery('<?php echo esc_js('#wlr-button-action-' . $card_key) ?>').hide();wlr_jquery('body').trigger('wlr_calculate_point_conversion',
                                             ['<?php echo esc_js('wlr_point_conversion_' . $u_reward->id); ?>','<?php echo esc_js('wlr_point_conversion_' . $u_reward->id . '_value'); ?>']);">
                                    <span class="wlr-action-text"
                                          style="<?php echo esc_attr($button_text_color); ?>"><?php echo esc_html($button_text); ?></span>
                                    </div>
                                <?php else: ?>
                                    <div class="<?php echo esc_attr($css_class_name); ?> "
                                         id="<?php echo esc_attr('wlr-button-action-' . $card_key); ?>"
                                         style="<?php echo esc_attr($button_color); ?>"
                                         onclick="wlr_jquery( 'body' ).trigger( 'wlr_apply_reward_action', [ '<?php echo esc_js($u_reward->id); ?>', '<?php echo esc_js($u_reward->reward_table); ?>', '<?php echo esc_js('#wlr-button-action-' . $card_key); ?>'] )">
                                    <span class="wlr-action-text"
                                          style="<?php echo esc_attr($button_text_color); ?>"><?php echo esc_html($button_text); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if (isset($u_reward->expiry_date) && !empty($u_reward->expiry_date) && !empty($u_reward->discount_code)): ?>
                                    <p class="wlr-expire-date wlr-text-color">
                                        <?php echo esc_html(sprintf(__("Expires on %s", "wp-loyalty-rules"), $u_reward->expiry_date)); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($revert_button) && ($u_reward->reward_type == 'redeem_point') && !empty($u_reward->discount_code) && isset($is_revert_enabled) && $is_revert_enabled): ?>
                                    <div style="display: flex;gap: 3px;float: right">
                                        <div class="wlr-revert-tool"
                                             onclick="wlr_jquery( 'body' ).trigger('revertEnable',['<?php echo esc_js('#wlr-' . $u_reward->id . '-' . $u_reward->discount_code); ?>']);">
                                        <span
                                            class="wlr-text-color"><?php echo __('Options', 'wp-loyalty-rules'); ?></span>
                                            <i class="wlr-text-color wlrf-arrow_right"></i>
                                        </div>
                                    </div>
                                    <div class="wlr-revert"
                                         id="<?php echo esc_attr('wlr-' . $u_reward->id . '-' . $u_reward->discount_code); ?>"
                                         onclick="wlr_jquery( 'body' ).trigger('wlr_revoke_coupon',['<?php echo esc_js($u_reward->id); ?>','<?php echo esc_js($u_reward->discount_code); ?>']);">
                                    <span
                                        class="wlr-revert-reward wlr-theme-color-apply"><?php echo esc_html($revert_button); ?></span>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                        <?php $card_key++; endforeach; ?>
                </div>
        </div>
    <?php endif; ?>
    <!--    customer rewards end here -->
    <?php do_action('wlr_before_customer_reward_page_transactions_content'); ?>
    <!--    customer transactions start here -->
    <?php
    if (isset($trans_details) && is_array($trans_details) && isset($trans_details['transactions']) && !empty($trans_details['transactions'])): ?>
        <div class="wlr-transaction-blog"
             id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-transaction-details-table') ?>">
            <div class="wlr-heading-container">
                <h3 class="wlr-heading"><?php echo esc_html__('Recent Activities', 'wp-loyalty-rules'); ?></h3>
            </div>
            <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-transaction-table') ?>">
                <table class="wlr-table">
                    <thead id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-transaction-table-header') ?>"
                           class="wlr-table-header"
                    >
                    <tr>
                        <th class="set-center wlr-text-color"><?php echo esc_html__('Order No.', 'wp-loyalty-rules') ?></th>
                        <th class="wlr-text-color"><?php echo esc_html__('Action Type', 'wp-loyalty-rules') ?></th>
                        <th class="wlr-text-color"><?php echo esc_html__('Message', 'wp-loyalty-rules') ?></th>
                        <th class="set-center wlr-text-color"><?php echo esc_html($earn_campaign_helper->getPointLabel(3)); ?></th>
                        <th class="wlr-text-color"><?php echo esc_html($earn_campaign_helper->getRewardLabel(3)) ?></th>
                    </tr>
                    </thead>
                    <?php foreach ($trans_details['transactions'] as $transaction): ?>
                        <tr>
                            <td class="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-transaction-table-body set-center wlr-text-color wlr-border-color') ?> ">
                                <?php if ($transaction->order_id > 0):
                                    $order = wc_get_order($transaction->order_id);
                                    if (isset($order) && is_object($order) && method_exists($order, 'get_view_order_url')): ?>
                                        <?php if ($transaction->action_type != 'referral'): ?>
                                            <a class="wlr-theme-color-apply wlr-nowrap"
                                               href="<?php echo esc_url($order->get_view_order_url()); ?>">
                                                <?php echo '#' . $order->get_order_number(); ?>
                                            </a>
                                        <?php else: ?>
                                            <?php echo '#' . $order->get_order_number(); ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php echo esc_html('#' . $transaction->order_id); ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-transaction-table-body wlr-text-color wlr-border-color') ?>"><?php echo esc_html($earn_campaign_helper->getActionName($transaction->action_type)); ?></td>
                            <td class="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-transaction-table-body wlr-text-color wlr-border-color') ?>">
                                <?php echo isset($transaction->processed_custom_note) && !empty($transaction->processed_custom_note) ? $transaction->processed_custom_note : $transaction->customer_note;
                                ?></td>
                            <td class="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-transaction-table-body set-center wlr-text-color wlr-border-color') ?> ">
                                <?php echo ($transaction->points == 0) ? "-" : (int)$transaction->points; ?>
                            </td>
                            <td class="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-transaction-table-body wlr-text-color wlr-border-color') ?>"><?php echo esc_html(!empty($transaction->reward_display_name) ? __($transaction->reward_display_name, "wp-loyalty-rules") : '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?php if (isset($trans_details['transaction_total']) && $trans_details['transaction_total'] > 0):
                    $endpoint_url = wc_get_endpoint_url('loyalty_reward'); ?>
                    <div style="text-align: right">
                        <?php if (isset($trans_details['offset']) && 1 !== (int)$trans_details['offset']) :
                            $endpoint_url_with_params = add_query_arg(array('transaction_page' => $trans_details['offset'] - 1), $endpoint_url); ?>
                            <a class="woocommerce-button woocommerce-button--previous woocommerce-Button wlr-cursor wlr-text-color"
                               onclick="wlr_jquery( 'body' ).trigger( 'wlr_redirect_url', [ '<?php echo esc_url($endpoint_url_with_params . '#wlr-transaction-details-table') ?>'] )"
                               id="<?php echo WLR_PLUGIN_PREFIX . '-prev-button' ?>">
                                <?php esc_html_e('Prev', 'wp-loyalty-rules'); ?>
                            </a>
                        <?php endif; ?>
                        <?php if (isset($trans_details['current_trans_count']) && intval($trans_details['current_trans_count']) < $trans_details['transaction_total']) :
                            $endpoint_url_with_params = add_query_arg(array('transaction_page' => $trans_details['offset'] + 1), $endpoint_url); ?>
                            <a class="woocommerce-button woocommerce-button--next woocommerce-Button  wlr-cursor wlr-text-color"
                               id="<?php echo WLR_PLUGIN_PREFIX . '-next-button' ?>"
                               onclick="wlr_jquery( 'body' ).trigger( 'wlr_redirect_url', [ '<?php echo esc_url($endpoint_url_with_params . '#wlr-transaction-details-table') ?>'] )">
                                <?php esc_html_e('Next', 'wp-loyalty-rules'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    <!--    customer transactions end here -->
    <?php do_action('wlr_before_customer_reward_page_ways_to_earn_content'); ?>
    <!--    campaign list start here -->
    <?php
    if (isset($campaign_list) && !empty($campaign_list)) : ?>
        <div class="wlr-earning-options">
            <div class="wlr-heading-container">
                <h3 class="wlr-heading"><?php echo sprintf(__('Ways to earn %s', 'wp-loyalty-rules'),$earn_campaign_helper->getRewardLabel()) ?></h3>
            </div>
            <div class="wlr-campaign-container">
                <?php $card_key = 1;
                foreach ($campaign_list as $campaign) : ?>
                    <?php if (isset($campaign->is_show_way_to_earn) && $campaign->is_show_way_to_earn == 1): ?>
                        <div class="wlr-card wlr-earning-option wlr-border-color">
                            <?php if (isset($campaign->level_batch) && is_array($campaign->level_batch) && !empty($campaign->level_batch)): ?>
                                <div class="wlr-campaign-level-batch">
                                    <?php $check_level_count = 1;
                                    foreach ($campaign->level_batch as $batch_label):
                                        if ($check_level_count > 2): ?>
                                            <span
                                                class="wlr-text-color wlr-border-color"><?php echo sprintf(__('+%s', 'wp-loyalty-rules'), $campaign->level_batch_count_show); ?></span>
                                            <?php break;
                                        else: $check_level_count++; ?>
                                            <img class="wlr-border-color"
                                                 src="<?php echo esc_url($batch_label['badge']); ?>"
                                                 alt="<?php echo esc_attr($batch_label['name']); ?>"
                                                 title="<?php echo esc_attr($batch_label['name']); ?>">
                                        <?php endif;
                                    endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <div class="wlr-card-container">
                                <?php $action_type = isset($campaign->action_type) && !empty($campaign->action_type) ? $campaign->action_type : ""; ?>
                                <?php $img_icon = isset($campaign->icon) && !empty($campaign->icon) ? $campaign->icon : ""; ?>
                                <?php echo \Wlr\App\Helpers\Base::setImageIcon($img_icon, $action_type, array("alt" => $campaign->name)); ?>
                                <h4 class="wlr-name">
                                    <?php echo \Wlr\App\Helpers\Base::readMoreLessContent($campaign->name, $card_key, 60, __("Show more", "wp-loyalty-rules"), __("Show less", "wp-loyalty-rules"), 'card-campaign-name', 'wlr-name wlr-pre-text wlr-text-color'); ?>
                                </h4>
                                <div style="display: flex;align-items: center;gap:5px;justify-content: space-between;">
                                    <?php if (isset($campaign->campaign_title_discount) && !empty($campaign->campaign_title_discount)) : ?>
                                        <div class="wlr-campaign-points">
                                            <p class="wlr-discount-point wlr-text-color"><?php _e($campaign->campaign_title_discount, 'wp-loyalty-rules'); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (((isset($user) && is_object($user) && $user->id > 0) || get_current_user_id() > 0) && isset($campaign->action_type) && $campaign->action_type == 'followup_share') : ?>
                                        <?php $point_rule = $woocommerce_helper->isJson($campaign->point_rule) ? json_decode($campaign->point_rule) : new \stdClass();
                                        $share_url = isset($point_rule->share_url) && !empty($point_rule->share_url) ? $point_rule->share_url : ''; ?>
                                        <div class="wlr-date wlr-followup-section"
                                             style="position:relative;border-radius: 6px;padding:4px;background: <?php echo $theme_color; ?>;<?php echo $is_right_to_left ? "float: left;" : "float:right;"; ?>">
                                            <i class="wlrf-followup wlr-button-text-color wlr-cursor"
                                               onclick="wlr_jquery( 'body' ).trigger( 'wlr_apply_followup_share', [ '<?php echo esc_js($campaign->id); ?>','<?php echo esc_js($share_url); ?>','<?php echo esc_js($campaign->action_type); ?>' ] )"></i>
                                            <a class="wlr-button-text-color"
                                               onclick="wlr_jquery( 'body' ).trigger( 'wlr_apply_followup_share', [ '<?php echo esc_js($campaign->id); ?>','<?php echo esc_js($share_url); ?>','<?php echo esc_js($campaign->action_type); ?>' ] )">
                                            <span class="wlr wlr-button-text-color">
                                                <?php echo esc_html__('Follow', 'wp-loyalty-rules'); ?>
                                            </span>
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($campaign->action_type) && $campaign->action_type == 'birthday') : ?>
                                        <?php
                                        $date_format_orders = apply_filters("wlr_my_account_birthday_date_format", array(
                                            "format" => array("d", "m", "Y"),
                                            "separator" => "-"
                                        ));
                                        $birth_date = isset($user->birthday_date) && !empty($user->birthday_date) && $user->birthday_date != '0000-00-00' ? $woocommerce_helper->beforeDisplayDate(strtotime($user->birthday_date)) : (isset($user->birth_date) && !empty($user->birth_date) ? $woocommerce_helper->beforeDisplayDate($user->birth_date) : '');
                                        $is_one_time_birthdate_edit = isset($is_one_time_birthdate_edit) && $is_one_time_birthdate_edit == 'yes';
                                        $show_edit_birthday = $is_one_time_birthdate_edit || empty($birth_date);
                                        $wp_user = wp_get_current_user();
                                        $user_can_edit_birthdate = (isset($user) && isset($user->id) && $user->id > 0) || (is_object($wp_user) && isset($wp_user->ID) && $wp_user->ID > 0);
                                        $show_edit_birthday = apply_filters("wlr_allow_my_account_edit_birth_date", $show_edit_birthday, $user_can_edit_birthdate, isset($user) && !empty($user) ? $user : new \stdClass());
                                        ?>
                                        <?php if ($user_can_edit_birthdate): ?>
                                            <div class="wlr-date wlr-birthday-edit-button">
                                                <i class="wlrf-calendar-date wlr-text-color" <?php echo $show_edit_birthday ? 'onclick="jQuery(\'' . esc_js("#wlr-birth-date-input-" . $campaign->id) . '\').toggle();"' : ''; ?>></i>
                                                <span class="wlr-birthday-date wlr-text-color"
                                                      id="<?php echo esc_attr("wlr-birth-date-" . $campaign->id); ?>">
                                                <?php echo esc_attr($birth_date); ?>
                                            </span>
                                                <?php if ($show_edit_birthday): ?>
                                                    <a class="wlr-button-text-color"
                                                       onclick="jQuery('<?php echo esc_js("#wlr-birth-date-input-" . $campaign->id); ?>').toggle();">
                                            <span class="wlr wlr-theme-color-apply" style="font-weight: bold;">
                                                <?php echo !empty($birth_date) ? esc_html__('Edit', 'wp-loyalty-rules') : esc_html__('Set Birthday', 'wp-loyalty-rules'); ?>
                                            </span>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($user_can_edit_birthdate && $show_edit_birthday): ?>
                                            <div class="wlr-date-editor wlr-birthday-date-editor"
                                                 id="<?php echo esc_attr("wlr-birth-date-input-" . $campaign->id); ?>"
                                                 style="display: none;">
                                                <div class="wlr-date-editor-layer"></div>
                                                <i class="wlrf-close wlr-cursor wlr-text-color"
                                                   style="float:right;margin-top:10px; margin-right:10px;color:white;font-weight:bold;font-size: 30px;"
                                                   onclick="jQuery('<?php echo esc_js("#wlr-birth-date-input-" . $campaign->id); ?>').toggle();">
                                                </i>
                                                <div class="wlr-date-editor-container">
                                                    <div class="wlr-date-container">
                                                        <?php if (!empty($date_format_orders) && is_array($date_format_orders)): ?>
                                                            <?php foreach ($date_format_orders['format'] as $date_format_order): ?>
                                                                <?php if ($date_format_order == "d"): ?>
                                                                    <div>
                                                                        <label
                                                                            for="<?php echo esc_attr("wlr-customer-birth-date-day-" . $campaign->id); ?>"><?php esc_html_e('Day', 'wp-loyalty-rules'); ?></label>
                                                                        <input type="text" placeholder="dd" name="day"
                                                                               oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                                                               id="<?php echo esc_attr("wlr-customer-birth-date-day-" . $campaign->id); ?>"
                                                                               min="1" max="31"
                                                                               maxlength="2"
                                                                        >
                                                                    </div>
                                                                <?php elseif ($date_format_order == "m"): ?>
                                                                    <div>
                                                                        <label
                                                                            for="<?php echo esc_attr("wlr-customer-birth-date-month-" . $campaign->id); ?>"><?php esc_html_e('Month', 'wp-loyalty-rules'); ?></label>
                                                                        <input type="text" placeholder="mm" name="month"
                                                                               oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                                                               id="<?php echo esc_attr("wlr-customer-birth-date-month-" . $campaign->id); ?>"
                                                                               min="1" max="12"
                                                                               maxlength="2"
                                                                        >
                                                                    </div>
                                                                <?php elseif ($date_format_order == "Y"): ?>
                                                                    <div>
                                                                        <label
                                                                            for="<?php echo esc_attr("wlr-customer-birth-date-year-" . $campaign->id); ?>"><?php esc_html_e('Year', 'wp-loyalty-rules'); ?></label>
                                                                        <input type="text" placeholder="yyyy"
                                                                               name="year"
                                                                               oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                                                               id="<?php echo esc_attr("wlr-customer-birth-date-year-" . $campaign->id); ?>"
                                                                               min="" maxlength="4"
                                                                        >
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                    <a class="wlr-date-action wlr-update-birthday wlr-button-text-color"
                                                       style="<?php echo !empty($theme_color) ? esc_attr("background:" . $theme_color . ";") : ""; ?>"
                                                       onclick="wlr_jquery( 'body' ).trigger( 'wlr_update_birthday_date_action', [ '<?php echo esc_js($campaign->id); ?>','<?php echo esc_js($campaign->id); ?>', 'update' ] )">
                                                        <?php esc_html_e('Update Birthday', 'wp-loyalty-rules') ?>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <?php if (is_object($campaign) && isset($campaign->description) && !empty($campaign->description) && $campaign->description != 'null') : ?>
                                    <?php echo \Wlr\App\Helpers\Base::readMoreLessContent($campaign->description, $card_key, 90, __("Show more", "wp-loyalty-rules"), __("Show less", "wp-loyalty-rules"), 'card-campaign-description', 'wlr-description wlr-pre-text wlr-text-color'); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php $card_key++;
                    endif;
                endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    <!--    campaign list end here -->
    <?php do_action('wlr_before_customer_reward_page_reward_opportunity_content'); ?>
    <!--    rewards list start here -->
    <?php if (isset($reward_list) && !empty($reward_list)) : ?>
        <div class="wlr-earning-options">
            <div class="wlr-heading-container">
                <h3 class="wlr-heading"><?php echo esc_html(sprintf(__('%s opportunities', 'wp-loyalty-rules'), $earn_campaign_helper->getRewardLabel(3))) ?></h3>
            </div>
            <div class="wlr-campaign-container">
                <?php $card_key = 1;
                foreach ($reward_list as $reward) : ?>
                    <?php if (isset($reward->is_show_reward) && $reward->is_show_reward == 1): ?>
                        <div class="wlr-card wlr-earning-option wlr-border-color">
                            <div class="wlr-card-container">
                                <?php $discount_type = isset($reward->discount_type) && !empty($reward->discount_type) ? $reward->discount_type : "" ?>
                                <?php $img_icon = isset($reward->icon) && !empty($reward->icon) ? $reward->icon : "" ?>
                                <?php echo \Wlr\App\Helpers\Base::setImageIcon($img_icon, $discount_type, array("alt" => $reward->name)); ?>
                                <h4 class="wlr-name">
                                    <?php echo \Wlr\App\Helpers\Base::readMoreLessContent($reward->name, $card_key, 60, __("Show more", "wp-loyalty-rules"), __("Show less", "wp-loyalty-rules"), 'card-ways-to-earn-name', 'wlr-name wlr-pre-text wlr-text-color'); ?>
                                </h4>
                                <?php if (isset($reward->description) && !empty($reward->description) && $reward->description != 'null') : ?>
                                    <?php echo \Wlr\App\Helpers\Base::readMoreLessContent($reward->description, $card_key, 90, __("Show more", "wp-loyalty-rules"), __("Show less", "wp-loyalty-rules"), 'card-ways-to-earn-description', 'wlr-description wlr-pre-text wlr-text-color'); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php $card_key++; endif; endforeach; ?>
            </div>

        </div>
    <?php endif; ?>
    <!--    rewards list end here -->
    <?php do_action('wlr_before_customer_reward_page_notification_preference_content'); ?>
    <?php if ((isset($is_sent_email_display) && $is_sent_email_display === "yes") &&
        (isset($user) && is_object($user) && isset($user->id) && $user->id > 0)): ?>
        <div class="wlr-enable-email-sent-blog">
            <div class="wlr-heading-container">
                <h3 class="wlr-heading"><?php echo __('Notification Preference', 'wp-loyalty-rules'); ?></h3>
            </div>
            <div class="wlr-sent-email">
                <input type="checkbox" name="wlr_enable_email_sent" id="wlr-enable-email-sent"
                    <?php echo (isset($user->is_allow_send_email) && $user->is_allow_send_email == 1) ? 'checked' : ''; ?>
                       onclick="wlr_jquery('body').trigger('wlr_enable_email_sent',['wlr-enable-email-sent']);">
                <label for="wlr-enable-email-sent" class="wlr-text-color"
                ><?php echo sprintf(__('Opt-in for receiving %s & %s emails', 'wp-loyalty-rules'),$earn_campaign_helper->getPointLabel(3),$earn_campaign_helper->getRewardLabel()); ?></label>
            </div>
        </div>
    <?php endif; ?>
    <?php do_action('wlr_after_customer_reward_page_content'); ?>
</div>
