<?php
defined("ABSPATH") or die();
$earn_campaign_helper = \Wlr\App\Helpers\EarnCampaign::getInstance();
$woocommerce_helper = \Wlr\App\Helpers\Woocommerce::getInstance();
$theme_color = isset($branding) && is_array($branding) && isset($branding["theme_color"]) && !empty($branding["theme_color"]) ? $branding["theme_color"] : "#4F47EB";
$button_text_color = isset($branding) && is_array($branding) && isset($branding["button_text_color"]) && !empty($branding["button_text_color"]) ? $branding["button_text_color"] : "#ffffff";
$endpoint_url = isset($endpoint_url) && !empty($endpoint_url) ? $endpoint_url : '';
if (isset($user_rewards) && !empty($user_rewards)):
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
    $page_type_status = !((isset($page_type) && $page_type == 'cart'));
    ?>
    <div class="wlr-coupons-list">
        <input type="hidden" id="wlr-endpoint-url" data-url="<?php echo $endpoint_url; ?>" >
        <?php
        $card_key = 1;
        $coupons_count = 0;
        if (isset($user_rewards) && !empty($user_rewards)):
            foreach ($user_rewards as $u_reward):
                $revert_button = sprintf(__('Revert to %s', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel(3));
                if (isset($u_reward->discount_code) && !empty($u_reward->discount_code) && isset($u_reward->status) && !empty($u_reward->status) && !in_array($u_reward->status, array(
                        'used',
                        'expired'
                    ))):
                    $coupons_count++;
                    ?>
                    <div
                        class="wlr-coupons-content <?php echo (isset($u_reward->discount_code) && !empty($u_reward->discount_code)) ? 'wlr-new-coupon-card' : ''; ?> wlr-border-color">
                        <div class="wlr-card-container">
                            <div class="wlr-coupon-card-header">
                                <div class="wlr-title-icon">
                                    <div class="wlr-card-icon-container">
                                        <div class="wlr-card-icon">
                                            <?php $discount_type = isset($u_reward->discount_type) && !empty($u_reward->discount_type) ? $u_reward->discount_type : "" ?>
                                            <?php $img_icon = isset($u_reward->icon) && !empty($u_reward->icon) ? $u_reward->icon : "" ?>
                                            <?php echo \Wlr\App\Helpers\Base::setImageIcon($img_icon, $discount_type, array("alt" => $u_reward->name)); ?>
                                        </div>
                                    </div>
                                    <div class="wlr-name-container">
                                        <h4 class="wlr-name wlr-text-color">
                                            <?php echo \Wlr\App\Helpers\Base::readMoreLessContent($u_reward->name, $card_key, 60, __("Show more", "wp-loyalty-rules"), __("Show less", "wp-loyalty-rules"), 'card-my-reward-name', 'wlr-name wlr-pre-text wlr-text-color'); ?>
                                        </h4>
                                        <p class="wlr-theme-color-apply">
                                            <?php echo $u_reward->reward_type_name; ?>
                                            <?php $discount_value = isset($u_reward->discount_value) && !empty($u_reward->discount_value) && ($u_reward->discount_value != 0) ? ($u_reward->discount_value) : ''; ?>
                                            <?php if ($discount_value > 0 && isset($u_reward->discount_type) && in_array($u_reward->discount_type, array(
                                                    'percent',
                                                    'fixed_cart',
                                                    'points_conversion'
                                                ))): ?>
                                                <?php if (($u_reward->discount_type == 'points_conversion') && !empty($u_reward->discount_code)) : ?>
                                                    <?php echo " - " . $woocommerce_helper->convertPrice($discount_value, true, $u_reward->reward_currency); ?>
                                                <?php elseif ($u_reward->discount_type != 'points_conversion'): ?>
                                                    <?php echo ($u_reward->discount_type == 'percent') ? " - " . round($discount_value) . "%" : " - " . $woocommerce_helper->convertPrice($discount_value, true, $u_reward->reward_currency); ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="wlr-code-button">
                                    <div class="wlr-code"
                                         style="<?php echo esc_attr($coupon_border); ?>">
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
                                    <?php if (isset($u_reward->discount_type) && $u_reward->discount_type == 'points_conversion' && $u_reward->reward_table != 'user_reward'): ?>
                                        <div style="display: none;"
                                             class="wlr-point-conversion-section wlr-border-color"
                                             id="<?php echo esc_attr('wlr_point_conversion_div_' . $u_reward->id); ?>">
                                            <div><i class="wlrf-close wlr-cursor wlr-text-color"
                                                    title="<?php _e('Close', 'wp-loyalty-rules'); ?>"
                                                    onclick="wlr_jquery('<?php echo esc_js('#wlr_point_conversion_div_' . $u_reward->id); ?>').hide();wlr_jquery('<?php echo esc_js('#wlr-button-action-' . $card_key) ?>').show();">
                                                </i></div>
                                            <div style="display: flex;gap: 15%">
                                                <div class="wlr-input-point-section">
                                                    <div
                                                        class="wlr-input-point-conversion wlr-border-color">
                                                        <input type="text" min="1"
                                                               pattern="/^[0-9]+$/"
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
                                                    <div
                                                        class="wlr-point-label-content wlr-border-color">
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
                                             onclick="wlr_jquery( 'body' ).trigger( 'wlr_apply_reward_action', [ '<?php echo esc_js($u_reward->id); ?>', '<?php echo esc_js($u_reward->reward_table); ?>', '<?php echo esc_js('#wlr-button-action-' . $card_key); ?>',<?php echo $page_type_status; ?>,'<?php
                                             $endpoint_url_with_params = add_query_arg(array('active_reward_page' => 'coupons'), $endpoint_url);
                                             echo esc_url($endpoint_url_with_params . '#wlr-my-rewards-sections') ?>'] )">
                                    <span class="wlr-action-text"
                                          style="<?php echo esc_attr($button_text_color); ?>"><?php echo esc_html($button_text); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="wlr-coupon-card-footer">
                                <div class="wlr-coupon-date-section">
                                    <?php if (isset($u_reward->expiry_date) && !empty($u_reward->expiry_date) && !empty($u_reward->discount_code)): ?>
                                        <div class="wlr-flex"><i class="wlrf-clock wlr-text-color"></i>
                                            <p class="wlr-expire-date wlr-text-color">
                                                <?php echo esc_html(sprintf(__("Expires on %s", "wp-loyalty-rules"), $u_reward->expiry_date)); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <?php if (!empty($revert_button) && ($u_reward->reward_type == 'redeem_point') && !empty($u_reward->discount_code) && isset($is_revert_enabled) && $is_revert_enabled): ?>
                                        <div class="wlr-revert wlr-revert-active wlr-flex"
                                             id="<?php echo esc_attr('wlr-' . $u_reward->id . '-' . $u_reward->discount_code); ?>"
                                             onclick="wlr_jquery( 'body' ).trigger('wlr_revoke_coupon',['<?php echo esc_js($u_reward->id); ?>','<?php echo esc_js($u_reward->discount_code); ?>']);">
                                            <i class="wlrf-refresh_2 wlr-theme-color-apply"></i>
                                            <span
                                                class="wlr-revert-reward wlr-theme-color-apply"><?php echo esc_html($revert_button); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php
                endif;
                $card_key++;
            endforeach;
            if (isset($user_coupon_rewards['coupons_total']) && $user_coupon_rewards['coupons_total'] > 0):
                $endpoint_url = wc_get_endpoint_url('loyalty_reward'); ?>
                <div class="wlr-coupon-pagination">
                    <div>
                        <div style="text-align: right">
                            <?php if (isset($user_coupon_rewards['offset']) && 1 !== (int)$user_coupon_rewards['offset']) : ?>
                                <a class="woocommerce-button woocommerce-button--previous woocommerce-Button wlr-cursor wlr-text-color"
                                   onclick="wlr_jquery( 'body' ).trigger( 'wlr_my_rewards_pagination',['coupons','<?php echo $user_coupon_rewards['offset'] - 1; ?>','<?php echo $page_type; ?>'])"
                                   id="<?php echo WLR_PLUGIN_PREFIX . '-prev-button' ?>">
                                    <?php esc_html_e('Prev', 'wp-loyalty-rules'); ?>
                                </a>
                            <?php endif; ?>
                            <?php if (isset($user_coupon_rewards['current_coupon_count']) && intval($user_coupon_rewards['current_coupon_count']) < $user_coupon_rewards['coupons_total']) : ?>
                                <a class="woocommerce-button woocommerce-button--next woocommerce-Button  wlr-cursor wlr-text-color"
                                   id="<?php echo WLR_PLUGIN_PREFIX . '-next-button' ?>"
                                   onclick="wlr_jquery( 'body' ).trigger( 'wlr_my_rewards_pagination', ['coupons','<?php echo $user_coupon_rewards['offset'] + 1; ?>','<?php echo $page_type; ?>'])">
                                    <?php esc_html_e('Next', 'wp-loyalty-rules'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif;
        endif;
        if (empty($coupons_count)):?>
            <div class="wlr-norecords-container">
                <div><i class="wlrf-coupon-empty wlr-text-color"></i></div>
                <div><h4 class="wlr-text-color"><?php _e('Transform your points into savings! Convert to coupons now.', 'wp-loyalty-rules'); ?></h4></div>
                <div>
                    <p class="wlr-text-color"><?php _e("Maximize the value of your earned points by converting them into discount coupons. Make your shopping experience even more rewarding!", "wp-loyalty-rules"); ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
