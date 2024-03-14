<?php
defined('ABSPATH') or die();
$earn_campaign_helper = \Wlr\App\Helpers\EarnCampaign::getInstance();
$today_expire_point = isset($today_expire_point) && !empty($today_expire_point) ? $today_expire_point : 0;
if (!empty($today_expire_point)): ?>
<div class="wlr-today-expire-points-section wlr-border-color">
	<i class="wlrf-clock wlr-theme-color-apply"></i>
	<p class="wlrborder wlr-text-color" style="margin: 0;"><?php echo sprintf(__('Alert! Your %d %s expire today.Use them now to avoid missing out.','wp-loyalty-rules'),$today_expire_point,$earn_campaign_helper->getPointLabel($today_expire_point));?></p>
    <?php if (isset($show_redeem) && $show_redeem):?>
    <p onclick="wlr_jquery( 'body' ).trigger( 'wlr_my_reward_section',[ 'rewards'])" class="wlr-theme-color-apply wlr-cursor"  style="margin: 0;text-decoration: none;"><?php _e('Redeem Now!','wp-loyalty-rules');?></p>
    <?php endif;?>
</div>
<?php endif;?>
