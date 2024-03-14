<?php
defined( 'ABSPATH' ) or die;
$earn_campaign_helper = \Wlr\App\Helpers\EarnCampaign::getInstance();
$page_type = isset($page_type) && !empty($page_type) ? $page_type : '';
if ( isset( $user_rewards ) && ! empty( $user_rewards ) ): ?>

    <div class="wlr-my-rewards-sections" id="wlr-my-rewards-sections">
        <div class="wlr-user-reward-titles">
            <div
                    class="wlr-my-rewards-title wlr-rewards-title <?php echo ( isset( $active_used_expired_reward_page ) && $active_used_expired_reward_page == 'rewards' ) ? 'active' : ''; ?>"
                    onclick="wlr_jquery( 'body' ).trigger( 'wlr_my_reward_section',[ 'rewards',true,'<?php echo $page_type;?>'])"
                    data-reward-type="rewards">
                <i class="wlrf-rewards wlr-text-color"></i>
                <h4 class="wlr-text-color"><?php echo sprintf( __( '%s', 'wp-loyalty-rules' ), ucfirst( $earn_campaign_helper->getRewardLabel() ) ); ?></h4>
            </div>
            <div
                    class="wlr-my-rewards-title wlr-coupons-title <?php echo ( isset( $active_used_expired_reward_page ) && $active_used_expired_reward_page == 'coupons' ) ? 'active' : ''; ?>"
                    onclick="wlr_jquery( 'body' ).trigger( 'wlr_my_reward_section',[ 'coupons',true,'<?php echo $page_type;?>'])"
                    data-reward-type="coupons">
                <i class="wlrf-reward-show wlr-text-color"></i>
                <h4 class="wlr-text-color"><?php echo esc_html__( 'Coupons', 'wp-loyalty-rules' ); ?></h4>
            </div>
			<?php if ( isset( $page_type ) && ! empty( $page_type ) && $page_type != 'cart' ): ?>
                <div
                        class="wlr-my-rewards-title wlr-coupons-expired-title <?php echo ( isset( $active_used_expired_reward_page ) && $active_used_expired_reward_page == 'coupons-expired' ) ? 'active' : ''; ?>"
                        onclick="wlr_jquery( 'body' ).trigger( 'wlr_my_reward_section',[ 'coupons-expired',true,'<?php echo $page_type;?>'])"
                        data-reward-type="coupons-expired">
                    <i class="wlrf-clock wlr-text-color"></i>
                    <h4 class="wlr-text-color"><?php echo esc_html__( 'Used & Expired Coupons', 'wp-loyalty-rules' ); ?></h4>
                </div>
			<?php endif; ?>
        </div>
        <div class="wlr-user-reward-contents">
            <div class="wlr-rewards-container <?php echo ( isset( $active_used_expired_reward_page ) && $active_used_expired_reward_page == 'rewards' ) ? 'active' : ''; ?>">
				<?php echo ( isset( $new_reward_section ) && ! empty( $new_reward_section ) ) ? $new_reward_section : ''; ?>
            </div>
            <div class="wlr-coupons-container <?php echo ( isset( $active_used_expired_reward_page ) && $active_used_expired_reward_page == 'coupons' ) ? 'active' : ''; ?>">
				<?php echo ( isset( $new_coupon_section ) && ! empty( $new_coupon_section ) ) ? $new_coupon_section : ''; ?>
            </div>
			<?php if ( isset( $page_type ) && ! empty( $page_type ) && $page_type != 'cart' ): ?>
                <div class="wlr-coupons-expired-container <?php echo ( isset( $active_used_expired_reward_page ) && $active_used_expired_reward_page == 'coupons-expired' ) ? 'active' : ''; ?>">
					<?php echo ( isset( $new_expired_coupon_section ) && ! empty( $new_expired_coupon_section ) ) ? $new_expired_coupon_section : ''; ?>
                </div>
			<?php endif; ?>
        </div>
    </div>

<?php endif; ?>