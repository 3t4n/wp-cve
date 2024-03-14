<?php
/**
 *  View information about the type of subscription page.
 *
 * @uses at ht-cc-admin-settings-form.php
 * @param $type string
 * @param $page array
 * @param $billing_info object
 */

if (!defined('ABSPATH')) exit;
$plan->outgoing_messages_limit >= 1000?$limit = number_format(($plan->outgoing_messages_limit / 1000), 0) . 'K':$limit =$plan->outgoing_messages_limit;
if ($message_statistic->count){
    if ($message_statistic->count >= 1000){
		$message = number_format(($message_statistic->count / 1000), 0) . 'K';
    }else{
		$message = $message_statistic->count;
    }
}else{
	$message_statistic = (object)array();
	$message_statistic->count=0;
	$message = 0;
}

if ($subscribe_info) {
		?>
        <h1><?php _e($plan->short_name . ' - $' . number_format(($plan->unit_amount_in_cents /100), 0, '.', ' ').'/'. $plan->billing_period) ?></h1>
        <div class="subscribe__wrap">
            <div class="subscribe_info__wrap">
                <div class="main_info__wrap">
                    <div class="allotment_wrap">
                        <p><?php _e("Send Allotment: "); ?><?php _e($limit?$limit." sends":"unlimited"); ?></p>
                    </div>
                    <div class="billing_date_wrap">
                        <p><?php $subscribe_info->canceled_at ? _e("Expires on: "):_e("Next Billing Date: "); _e(date('m/d/Y', strtotime($subscribe_info->ends_at))); ?></p>
                    </div>
                    <div class="billed_wrap">
                        <p><?php _e("Billed with: "); ?><?php _e("****-****-****-".$account->last_card_numbers); ?></p>
                    </div>
                    <div class="invoice_info_wrap">
                        <a target="_blank" rel="noopener noreferrer"
                           href="<?php _e($account->view_invoices_link);?>"><?php _e('View Invoices') ?></a>
                        <a target="_blank" rel="noopener noreferrer"
                           href="<?php _e($account->edit_billing_link);?>"><?php _e('Edit Billing Information') ?></a>
                    </div>
                </div>
                <?php if(!$account->canceled_at){ ?>
					<?php
                    if (!$subscribe_info->canceled_at){
                    if ($page['is_wp']){?>
                        <div class="button__wrap">
                            <a href="#" class="button_cancel"><?php _e('Deactivate') ?></a>
                        </div>
					<?php }else{ ?>
                        <div class="button__wrap">
                            <a target="_blank" rel="noopener noreferrer" href="<?php _e($account->view_invoices_link);?>" class="button_edit"><?php _e('Edit in MobileMonkey') ?></a>
                        </div>
					<?php }
					?>
                <?php }} ?>


            </div>
            <div class="page_info__wrap">
                <div class="page_main_info_wrap">
                    <div class="page_name__wrap">
                        <h2 class="subscribe_page_name"><?php _e($page['page_name']); ?></h2>
                    </div>
                    <div class="subscribe_connect">
                        <p><?php _e('Connected since ' .date('m/d/Y', strtotime($page['since']))) ?></p>
                    </div>
                    <?php if($plan->outgoing_messages_limit){ ?>
                        <div class="message_sent__wrap">
                            <label for="message_sent"><?php _e('Messages Sent: ') ?></label>
                            <div class="percentage-bar__body-wrapper">
                                <div class="percentage-bar__bar">
                                    <div class="percentage-bar__used-percentage"></div>
                                </div>
                                <div class="number__wrap">
                                    <div class="percentage-bar__card-used-number" data-value="<?php _e($message_statistic->count)?>"><?php _e($message)?></div>
                                    <div class="percentage-bar__card-max-number" data-value="<?php _e($plan->outgoing_messages_limit)?>"><?php _e($limit)?></div>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
        </div>

		<?php
} else {
	?>
    <h1><?php _e('WP-Chatbot Free Plan') ?></h1>
    <div class="subscribe__wrap">
        <div class="subscribe_info__wrap">
            <div class="main_info__wrap">
                <div class="allotment_wrap">
                    <p><?php _e("Send Allotment: "); ?><?php _e("1K sends"); ?></p>
                </div>
            </div>
        </div>
        <div class="page_info__wrap">
            <div class="page_main_info_wrap">
                <div class="page_name__wrap">
                    <h2 class="subscribe_page_name"><?php _e($page['page_name']); ?></h2>
                </div>
                <div class="subscribe_connect">
                    <p><?php _e('Connected since ' . date('m/d/Y', strtotime($page['since']))) ?></p>
                </div>
                <div class="message_sent__wrap">
                    <label for="message_sent"><?php _e('Messages Sent: ') ?></label>
                    <div class="percentage-bar__body-wrapper">
                        <div class="percentage-bar__bar">
                            <div class="percentage-bar__used-percentage" style="width: 31%;"></div>
                        </div>
                        <div class="number__wrap">
                            <div class="percentage-bar__card-used-number" data-value="<?php _e($message_statistic->count)?>"><?php _e($message)?></div>
                            <div class="percentage-bar__card-max-number" data-value="1000"><?php _e('1K')?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="button__wrap">
                <a href="#" id="button_update" class="button_update"><?php _e('Upgrade') ?></a>
            </div>
        </div>
    </div>
	<?php
}
?>