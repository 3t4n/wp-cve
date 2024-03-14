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

    .wlr-myaccount-page .wlr-coupons-expired-content .wlr-card-icon-container i {
        color: <?php echo esc_attr($heading_color);?>;
    }

    .wlr-myaccount-page .wlr-user-reward-titles {
        border-bottom: 0.5px solid<?php echo $border_color;?>;
    }
</style>
<div class="wlr-myaccount-page">
    <?php do_action('wlr_before_customer_reward_cart_page_content'); ?>
    <?php if ((isset($user) && is_object($user) && isset($user->id) && $user->id > 0) || get_current_user_id()): ?>
        <div class="wlr-user-details">
            <div class="wlr-heading-container">
                <h3 class="wlr-heading"><?php echo esc_html(sprintf(__('My %s', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel(3))); ?></h3>
            </div>

            <div class="wlr-points-container">
                <?php do_action('wlr_before_customer_reward_page_my_points_content'); ?>
                <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-points') ?>">
                    <div id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-available-points'); ?>" class="wlr-border-color">
                        <div>
                            <?php $img_icon = isset($branding) && is_array($branding) && isset($branding["available_point_icon"]) && !empty($branding["available_point_icon"]) ? $branding["available_point_icon"] : ""; ?>
                            <?php echo \Wlr\App\Helpers\Base::setImageIcon($img_icon, "available-points", array("alt" => __("Available point", "wp-loyalty-rules"), "height" => 64, "width" => 64)); ?>
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
                            <?php echo \Wlr\App\Helpers\Base::setImageIcon($img_icon, "redeem-points", array("alt" => __("Redeem point", "wp-loyalty-rules"), "height" => 64, "width" => 64)); ?>
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
                                    <p> <?php echo sprintf(__('%s to Coupons : %s ', 'wp-loyalty-rules'), ucfirst($earn_campaign_helper->getPointLabel(3)), $user->total_coupon_count); ?></p>
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
                            <?php if (isset($user) && !empty($user) && isset($used_reward_currency_values) && !empty($used_reward_currency_values)): ?>
                                <div class="wlr-text-color">
                                    <p id="<?php echo esc_attr(WLR_PLUGIN_PREFIX . '-used-reward-value') ?>">
                                        <?php echo $used_reward_currency_values[$current_currency]; ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php do_action('wlr_before_customer_reward_cart_page_user_rewards_content'); ?>
    <!--    customer rewards start here -->
    <?php
    if (isset($user_rewards) && !empty($user_rewards)): ?>
        <div class="wlr-your-reward" id="wlr-your-reward">
            <div class="wlr-heading-container"><h3
                    class="wlr-heading"><?php echo esc_html(sprintf(__('My %s', 'wp-loyalty-rules'), $earn_campaign_helper->getRewardLabel(3))); ?></h3>
            </div>
            <?php if (isset($is_show_new_my_reward_section) && $is_show_new_my_reward_section == 'yes'):
                if (isset($new_my_reward_section) && !empty($new_my_reward_section)):
                    echo $new_my_reward_section;
                endif;
            endif; ?>
        </div>
    <?php endif; ?>
    <!--    customer rewards end here -->
    <?php do_action('wlr_after_customer_reward_cart_page_content'); ?>
</div>
