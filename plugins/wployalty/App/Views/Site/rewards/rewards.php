<?php
defined("ABSPATH") or die();
$earn_campaign_helper = \Wlr\App\Helpers\EarnCampaign::getInstance();
$woocommerce_helper = \Wlr\App\Helpers\Woocommerce::getInstance();
$theme_color = isset($branding) && is_array($branding) && isset($branding["theme_color"]) && !empty($branding["theme_color"]) ? $branding["theme_color"] : "#4F47EB";
$button_text_color = isset($branding) && is_array($branding) && isset($branding["button_text_color"]) && !empty($branding["button_text_color"]) ? $branding["button_text_color"] : "#ffffff";
$is_right_to_left = is_rtl();
if (isset($user_rewards) && !empty($user_rewards)):
    $button_text = isset($branding) && is_array($branding) && isset($branding["redeem_button_text"]) && !empty($branding["redeem_button_text"]) ? $branding["redeem_button_text"] : "";
    $revert_button = sprintf(__('Revert to %s', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel(3));
    $redeem_button_color = isset($branding) && is_array($branding) && isset($branding["redeem_button_color"]) && !empty($branding["redeem_button_color"]) ? $branding["redeem_button_color"] : "";
    $button_color = $redeem_button_color ? "background:" . $redeem_button_color . ";" : "background:" . $theme_color . ";";
    $redeem_button_text_color = isset($branding) && is_array($branding) && isset($branding["redeem_button_text_color"]) && !empty($branding["redeem_button_text_color"]) ? $branding["redeem_button_text_color"] : "";
    $button_text_color = $redeem_button_text_color ? "color:" . $redeem_button_text_color . ";" : "";
    $css_class_name = 'wlr-button-reward wlr-button wlr-button-action';
	$page_type = ! ( ( isset( $page_type ) && $page_type == 'cart' ) );
    ?>
    <div class="wlr-customer-reward" >
        <?php
        $card_key = 1;
        $reward_count = 0;
        foreach ($user_rewards as $u_reward):
            if (!isset($u_reward->discount_code) || empty($u_reward->discount_code)):
                $reward_count++;
                ?>
                <div
                    class="wlr-rewards-content wlr-reward-card wlr-border-color">
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
                            <?php if (!empty($description) && !(isset($u_reward->discount_code) && !empty($u_reward->discount_code))): ?>
                                <?php echo \Wlr\App\Helpers\Base::readMoreLessContent($description, $card_key, 90, __("Show more", "wp-loyalty-rules"), __("Show less", "wp-loyalty-rules"), 'card-my-reward-description', 'wlr-description wlr-pre-text wlr-text-color'); ?>
                            <?php endif; ?>
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
                                    onclick="wlr_jquery( 'body' ).trigger( 'wlr_apply_point_conversion_reward',['<?php echo esc_js($u_reward->id); ?>','<?php echo esc_js($u_reward->reward_table); ?>','<?php echo esc_js($u_reward->available_point); ?>','<?php echo esc_js('#wlr_point_conversion_' . $u_reward->id); ?>' ,'<?php echo esc_js('#wlr_point_conversion_' . $u_reward->id . '_button'); ?>','<?php echo $page_type;?>','<?php
                                    $endpoint_url = wc_get_endpoint_url('loyalty_reward');
                                    $endpoint_url_with_params = add_query_arg(array('active_reward_page' => 'coupons'), $endpoint_url);
                                    echo esc_url($endpoint_url_with_params . '#wlr-my-rewards-sections') ?>'] );">
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
                                 onclick="wlr_jquery( 'body' ).trigger( 'wlr_apply_reward_action', [ '<?php echo esc_js($u_reward->id); ?>', '<?php echo esc_js($u_reward->reward_table); ?>', '<?php echo esc_js('#wlr-button-action-' . $card_key); ?>','<?php echo $page_type;?>','<?php
                                 $endpoint_url = wc_get_endpoint_url('loyalty_reward');
                                 $endpoint_url_with_params = add_query_arg(array('active_reward_page' => 'coupons'), $endpoint_url);
                                 echo esc_url($endpoint_url_with_params . '#wlr-my-rewards-sections') ?>'] )">
                                    <span class="wlr-action-text"
                                          style="<?php echo esc_attr($button_text_color); ?>"><?php echo esc_html($button_text); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
            endif;
            $card_key++;
        endforeach; ?>
    </div>
    <?php if (empty($reward_count)): ?>
        <div class="wlr-customer-reward" style="display: flex;align-items: center;justify-content: center;">
            <div class="wlr-rewards-content wlr-norecords-container active">
                <div><i class="wlrf-reward-empty-hand wlr-text-color"></i></div>
                <div><h4 class="wlr-text-color"><?php echo sprintf(__('Begin Your %s Journey!', 'wp-loyalty-rules'),ucfirst($earn_campaign_helper->getRewardLabel(3))); ?></h4></div>
                <div>
                    <p class="wlr-text-color"><?php echo sprintf(__("Shop more and unlock amazing %s! Discover all the opportunities below!", "wp-loyalty-rules"),
		                    $earn_campaign_helper->getRewardLabel(3)); ?></p>
                </div>
            </div>
         </div>
        <?php endif; ?>
<?php endif;?>