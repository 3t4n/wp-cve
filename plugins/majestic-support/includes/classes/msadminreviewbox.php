<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
class MJTC_reviewbox {

    public function __construct() {
        add_action('admin_notices', array($this, 'MJTC_admin_notices'));
    }

    public function MJTC_admin_notices() {
        $is_hidden = get_option("majesticsupport_hide_review_box");
        if($is_hidden !== false) {
            return;
        }
        $current_count = get_option("majesticsupport_show_review_box_after");
        if($current_count === false) {
            $date = date("Y-m-d", MJTC_majesticsupportphplib::MJTC_strtotime("+30 days"));
            add_option("majesticsupport_show_review_box_after", $date);
            return;
        } else if($current_count < 35) {
            
        }
        $date_to_show = get_option("majesticsupport_show_review_box_after");
        if($date_to_show !== false) {
            $current_date = date("Y-m-d");
            if($current_date < $date_to_show) {
                return;
            }
        }
        ?>
        <div class="majesticsupport-premio-review-box">
            <div class="mjtc-support-review-default" id="default-review-box-majesticsupport">
                <div class="mjtc-support-review-default-cnt">
                    <div class="mjtc-support-review-default-cnt-left">
                        <img alt="<?php echo esc_attr(__('stars','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/review/stars-icon.png">
                    </div>
                    <div class="mjtc-support-review-default-cnt-right">
                        <div class="mjtc-support-review-row review-head">
                            <?php echo esc_html(__("Please write appreciated review at WP Extension Directory",'majestic-support')); ?>
                        </div>
                        <div class="mjtc-support-review-row review-description">
                            <?php echo esc_html(__("We'd love to hear from you. It'll only take 2 minutes of your time, and will really help us spread the word",'majestic-support')); ?>
                        </div>
                        <div class="mjtc-support-review-row">
                            <a data-mode="love" class="majesticsupport-premio-review-box-hide-btn review-love" href="https://wordpress.org/support/plugin/majestic-support/reviews/?filter=5" target="_blank">
                                <img alt="<?php echo esc_attr(__('love','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/review/love.png">
                                <?php echo esc_html(__("I'd love to help :)",'majestic-support')); ?>
                            </a>
                            <a class="majesticsupport-premio-review-box-future-btn review-sad" href="javascript:;">
                                <img alt="<?php echo esc_attr(__('sad','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/review/sad.png">
                                <?php echo esc_html(__("Not this time",'majestic-support')); ?>
                            </a>
                            <a data-mode="happy" class="majesticsupport-premio-review-box-hide-btn review-happy" href="javascript:;">
                                <img alt="<?php echo esc_attr(__('happy','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/review/happy.png">
                                <?php echo esc_html(__("I've already rated you",'majestic-support')); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <img  class="mjtc-support-review-default-img" alt="<?php echo esc_attr(__('stars','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/review/star.png">
                <a href="javascript:;" class="dismiss-btn majesticsupport-premio-review-dismiss-btn">
                    <img alt="<?php echo esc_attr(__('close','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/review/close_icon.png">
                </a>
            </div>
            <div class="mjtc-support-review-thanks-box" id="review-thanks-majesticsupport">
                <div class="majesticsupport-thanks-box-popup">
                    <div class="majesticsupport-thanks-box-popup-content">
                        <div class="majesticsupport-thanks-box-popup-content-wrp">
                            <button class="majesticsupport-close-thanks-btn mjtc-support-review-thanks-btn">
                                <img alt="<?php echo esc_attr(__('close','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/review/close_icon.png">
                            </button>
                            <div class="mjtc-support-review-thanks-img">
                                <img alt="<?php echo esc_attr(__('thanks','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/review/thank_you.png">
                            </div>
                            <div class="mjtc-support-review-thanks-msg">
                                <div class="thanks-msg-title"><?php echo esc_html(__("You are awesome !......",'majestic-support')); ?></div>
                                <div class="thanks-msg-desc"><?php echo esc_html(__("Thanks for your support, we really appreciate it.",'majestic-support')); ?></div>
                                <div class="thanks-msg-footer"><?php echo esc_html(__("Majestic Support Team",'majestic-support')); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear clearfix"></div>
            </div>
        </div>
        <div class="majesticsupport-review-box-popup">
            <div class="majesticsupport-review-box-popup-content">
                <div class="majesticsupport-review-box-popup-content-wrp">
                    <div class="majesticsupport-review-box-popup-content-left">
                        <img alt="<?php echo esc_attr(__('stars','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/review/popup-start-icon.png">
                    </div>
                    <div class="majesticsupport-review-box-popup-content-right">
                        <button class="majesticsupport-close-review-box-popup">
                            <img alt="<?php echo esc_attr(__('close','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/review/close_icon.png">
                        </button>
                        <div class="majesticsupport-review-box-title">
                            <?php echo esc_html(__("Remind you about this later?",'majestic-support')); ?>
                        </div>
                        <div class="majesticsupport-review-box-options">
                            <a class="three-days" href="javascript:;" data-days="3">
                                <?php echo esc_html(__("Remind me in 3 days",'majestic-support')); ?>
                            </a>
                            <a class="seven-days" href="javascript:;" data-days="10">
                                <?php echo esc_html(__("Remind me in 10 days",'majestic-support')); ?>
                            </a>
                            <a class="ten-days" href="javascript:;" data-days="30" class="dismiss">
                                <?php echo esc_html(__("Remind me in 30 days",'majestic-support')); ?>
                            </a>
                            <a class="zero-days" href="javascript:;" data-days="30" class="dismiss">
                                <?php echo esc_html(__("Don't remind me about this",'majestic-support')); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
        $majesticsupport_js ="
            jQuery(document).ready(function(){
                jQuery('body').addClass('has-premio-box');
                jQuery(document).on('click', '.majesticsupport-premio-review-dismiss-btn, .majesticsupport-premio-review-box-future-btn', function(){
                    jQuery('.majesticsupport-review-box-popup').show();
                });
                jQuery(document).on('click', '.majesticsupport-close-review-box-popup', function(){
                    jQuery('.majesticsupport-review-box-popup').hide();
                });
                jQuery(document).on('click', '.majesticsupport-close-thanks-btn', function(){
                    jQuery('.majesticsupport-review-box-popup').remove();
                    jQuery('.majesticsupport-premio-review-box').remove();
                });
                jQuery(document).on('click','.majesticsupport-premio-review-box-hide-btn',function(){
                    jQuery('#default-review-box-majesticsupport').hide();
                    jQuery('#review-thanks-majesticsupport').show();
                    jQuery('.majesticsupport-thanks-box-popup').show();
                    var dataMode = jQuery(this).attr('data-mode');
                    if (dataMode == 'happy') {
                        var dataDays = '-1';
                    }else{
                        var dataDays = '30';
                    }
                    jQuery.post(ajaxurl, {action: 'mjsupport_ajax', mjsmod: 'majesticsupport', task: 'reviewBoxAction', days:dataDays, '_wpnonce':'". esc_attr(wp_create_nonce("review-box-action"))."'}, function (data) {
                        
                    });
                });
                jQuery(document).on('click', '.majesticsupport-review-box-options a', function(){
                    var dataDays = jQuery(this).attr('data-days');
                    jQuery('.majesticsupport-review-box-popup').remove();
                    jQuery('.majesticsupport-premio-review-box').remove();
                    jQuery('body').removeClass('has-premio-box');
                    jQuery.post(ajaxurl, {action: 'mjsupport_ajax', mjsmod: 'majesticsupport', task: 'reviewBoxAction', days:dataDays, '_wpnonce':'". esc_attr(wp_create_nonce("review-box-action"))."'}, function (data) {
                        if (data) {
                            jQuery('.majesticsupport-review-box-popup').remove();
                            jQuery('.majesticsupport-premio-review-box').remove();
                        }
                    });
                });
            });";
            wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
    }
}
$MJTC_reviewbox = new MJTC_reviewbox();