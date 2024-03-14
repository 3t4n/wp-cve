<?php
defined("ABSPATH") or die();
$woocommerce_helper = \Wlr\App\Helpers\Woocommerce::getInstance();
$border_color = isset($branding) && is_array($branding) && isset($branding["border_color"]) && !empty($branding["border_color"]) ? $branding["border_color"] : "#CFCFCF";
$heading_color = isset($branding) && is_array($branding) && isset($branding["heading_color"]) && !empty($branding["heading_color"]) ? $branding["heading_color"] : "#1D2327";
?>
<div class="wlr-coupons-list">
    <?php
    if (isset($used_expired_rewards['expired_used_coupons']) && !empty($used_expired_rewards['expired_used_coupons'])): ?>
        <?php $card_key = 1;
        foreach ($used_expired_rewards['expired_used_coupons'] as $used_expired_reward): ?>
            <div
                class="wlr-coupons-expired-content <?php echo (isset($used_expired_reward->discount_code) && !empty($used_expired_reward->discount_code)) ? 'wlr-new-coupon-card wlr-expired-card' : ''; ?> wlr-border-color">
                <div class="wlr-card-container">
                    <div class="wlr-coupon-card-header">
                        <div class="wlr-title-icon">
                            <div class="wlr-card-icon-container">
                                <div class="wlr-card-icon">
                                    <?php $discount_type = isset($used_expired_reward->discount_type) && !empty($used_expired_reward->discount_type) ? $used_expired_reward->discount_type : "" ?>
                                    <?php $img_icon = isset($used_expired_reward->icon) && !empty($used_expired_reward->icon) ? $used_expired_reward->icon : "" ?>
                                    <?php echo \Wlr\App\Helpers\Base::setImageIcon($img_icon, $discount_type, array("alt" => $used_expired_reward->name)); ?>
                                </div>
                            </div>
                            <div class="wlr-name-container">
                                <h4 class="wlr-name wlr-text-color">
                                    <?php echo \Wlr\App\Helpers\Base::readMoreLessContent($used_expired_reward->name, $card_key, 60, __("Show more", "wp-loyalty-rules"), __("Show less", "wp-loyalty-rules"), 'card-my-reward-name', 'wlr-name wlr-pre-text wlr-text-color'); ?>
                                </h4>
                                <p class="wlr-text-color">
                                    <?php echo $used_expired_reward->reward_type_name; ?>
                                    <?php $discount_value = isset($used_expired_reward->discount_value) && !empty($used_expired_reward->discount_value) && ($used_expired_reward->discount_value != 0) ? ($used_expired_reward->discount_value) : ''; ?>
                                    <?php if ($discount_value > 0 && isset($used_expired_reward->discount_type) && in_array($used_expired_reward->discount_type, array(
                                            'percent',
                                            'fixed_cart',
                                            'points_conversion'
                                        ))): ?>
                                        <?php if (($used_expired_reward->discount_type == 'points_conversion') && !empty($used_expired_reward->discount_code)) : ?>
                                            <?php echo " - " . $woocommerce_helper->convertPrice($discount_value, true, $used_expired_reward->reward_currency); ?>
                                        <?php elseif ($used_expired_reward->discount_type != 'points_conversion'): ?>
                                            <?php echo ($used_expired_reward->discount_type == 'percent') ? " - " . round($discount_value) . "%" : " - " . $woocommerce_helper->convertPrice($discount_value, true, $used_expired_reward->reward_currency); ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <div class="wlr-code-button">
                            <?php if (isset($used_expired_reward->discount_code) && !empty($used_expired_reward->discount_code)): ?>
                                <div class="wlr-code"
                                     style="<?php echo !empty($border_color) ? esc_attr("align-items:center;justify-content:center;color:" . $border_color . ";background:unset;border:1px dashed " . $border_color . ";") : ""; ?>">
                                    <div class="wlr-coupon-code">
                                        <p title="<?php esc_html_e('Coupon Code', 'wp-loyalty-rules'); ?>">
                                                <span class="wlr-border-color wlr-text-color"
                                                      id="<?php echo esc_attr('wlr-' . $used_expired_reward->discount_code) ?>"><?php echo esc_html($used_expired_reward->discount_code); ?></span>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="wlr-coupon-card-footer">
                        <div class="wlr-coupon-date-section">
                            <?php if (isset($used_expired_reward->expiry_date) && !empty($used_expired_reward->expiry_date) && !empty($used_expired_reward->discount_code) && isset($used_expired_reward->status) && $used_expired_reward->status == 'expired'): ?>
                                <div class="wlr-flex"><i class="wlrf-clock wlr-text-color"></i>
                                    <p class="wlr-expire-date wlr-text-color">
                                        <?php echo esc_html(sprintf(__("Expired on %s", "wp-loyalty-rules"), $used_expired_reward->expiry_date)); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $card_key++;
        endforeach;
        if (isset($used_expired_rewards['expired_used_coupons_total']) && $used_expired_rewards['expired_used_coupons_total'] > 0):
            $endpoint_url = wc_get_endpoint_url('loyalty_reward'); ?>
            <div class="wlr-coupon-pagination">
                <div>
                    <div style="text-align: right">
                        <?php if (isset($used_expired_rewards['offset']) && 1 !== (int)$used_expired_rewards['offset']) : ?>
                            <a class="woocommerce-button woocommerce-button--previous woocommerce-Button wlr-cursor wlr-text-color"
                               onclick="wlr_jquery( 'body' ).trigger( 'wlr_my_rewards_pagination', ['coupons-expired','<?php echo $used_expired_rewards['offset'] - 1; ?>' ] )"
                               id="<?php echo WLR_PLUGIN_PREFIX . '-prev-button' ?>">
                                <?php esc_html_e('Prev', 'wp-loyalty-rules'); ?>
                            </a>
                        <?php endif; ?>
                        <?php if (isset($used_expired_rewards['current_expired_coupon_count']) && intval($used_expired_rewards['current_expired_coupon_count']) < $used_expired_rewards['expired_used_coupons_total']) : ?>
                            <a class="woocommerce-button woocommerce-button--next woocommerce-Button  wlr-cursor wlr-text-color"
                               id="<?php echo WLR_PLUGIN_PREFIX . '-next-button' ?>"
                               onclick="wlr_jquery( 'body' ).trigger( 'wlr_my_rewards_pagination', [ 'coupons-expired','<?php echo $used_expired_rewards['offset'] + 1; ?>'] )">
                                <?php esc_html_e('Next', 'wp-loyalty-rules'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif;
    else:
        ?>
        <div class="wlr-norecords-container">
            <div><i class="wlrf-used-expired-coupons wlr-text-color"></i></div>
            <div><h4 class="wlr-text-color"><?php _e('Used/Expired Coupons', 'wp-loyalty-rules'); ?></h4></div>
            <div>
                <p class="wlr-text-color"><?php _e("The following are a list of coupons that you've used or got expired.", "wp-loyalty-rules"); ?></p>
            </div>
        </div>
    <?php endif; ?>
</div>

