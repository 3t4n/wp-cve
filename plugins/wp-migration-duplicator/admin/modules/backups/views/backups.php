<?php
if (!defined('ABSPATH')) {
    exit;
}
$reasons = array(
    array(
        'id' => 'pro-feature-suggestion',
        'text' => __('Other', 'wp-migration-duplicator'),
        'type' => 'textarea',
        'placeholder' => __('Could you tell us more about the feature?', 'wp-migration-duplicator')
    ),
);
?>
<style>
    .help-guide .cols {
        display: flex;
    }
    .help-guide .inner-panel {
        padding: 55px 40px 65px 40px;
        background-color: #FFF;
        margin: 15px 10px;
        box-shadow: 1px 1px 5px 1px rgba(0,0,0,.1);
        text-align: center;
    }
    .help-guide .inner-panel p{
        margin-bottom: 20px;
    }
    .help-guide .inner-panel img{
        margin:30px 15px 0;
        height: 88px;
        width: 88px;
    }
    .wt_mgdp_title{
        background: #f0f7ff;
        border-radius: 9px; 
        padding: 10px 10px 10px 8px;
        margin-bottom: 16px;
    }


    .wt_mgdp_pro_features{
        width: 83%;
        border: 2px solid #F6F4FA;
        box-sizing: border-box;
        border-radius: 9px;
        margin-bottom: 8px;
    }

    .wt_premium_features li::before {
        font-family: dashicons;
        text-decoration: inherit;
        font-weight: 300;
        font-style: normal;
        vertical-align: top;
        text-align: center;
        content: "\2B50";
        padding-right: 8px;
        padding-left: 6px;
        font-size: 9px;
        color: #FF9212;
    }
    .wt_mgdp_title_val{
        font-family: Arial;
        font-style: normal;
        font-weight: normal;
        font-size: 16px;
        line-height: 20px;
        color: #135e96;
        width: 94%;
        padding-left: 4px;
    }


</style>
<div class="pipe-main-box">
    <div class="tool-box bg-white p-20p pipe-view">
        <div id="tab-help" class="coltwo-col panel help-guide">
            <div class="cols">
                <div class="inner-panel" style="width:25% ;margin-left: 2px;">
                    <img src="<?php echo esc_url(plugins_url(basename(plugin_dir_path(WT_MGDP_PLUGIN_FILENAME))) . '/admin/images/documentation.png'); ?>"/>
                    <h3><?php _e('Documentation', 'wp-migration-duplicator'); ?></h3>
                    <p style=""><?php _e('Troubleshoot any issues with our extensive documentation', 'wp-migration-duplicator'); ?></p>
                    <a href="https://www.webtoffee.com/wordpress-backup-migration-user-guide/" target="_blank" class="button button-primary">
                        <?php _e('Documentation', 'wp-migration-duplicator'); ?></a> 
                </div>


                <div class="inner-panel" style="width:25%">
                    <img src="<?php echo esc_url(plugins_url(basename(plugin_dir_path(WT_MGDP_PLUGIN_FILENAME))) . '/admin/images/support.png'); ?>"/>
                    <h3><?php _e('Support', 'wp-migration-duplicator'); ?></h3>
                    <p style=""><?php _e('We would love to help you on any queries or issues.', 'wp-migration-duplicator'); ?></p>
                    <a href="https://www.webtoffee.com/contact/" target="_blank" class="button button-primary">
                        <?php _e('Contact Us', 'wp-migration-duplicator'); ?></a>
                </div>
                
                  <div class="inner-panel" style="width:25%">
                    <h3><?php _e('Watch setup video', 'wp-migration-duplicator'); ?></h3>
                   <iframe src="//www.youtube.com/embed/hIaM_xeWa_8" allowfullscreen="allowfullscreen" frameborder="0" align="middle" style="width:100%;height: 70%;margin-bottom: 1em;"></iframe>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="wt-mgdp-modal" id="wt-mgdp-wt-mgdp-modal">
    <div class="wt-mgdp-modal-wrap">
        <div class="wt-mgdp-modal-header">
            <h3><?php _e('Please tell us about the feature that you want to see next in our plugin', 'wp-migration-duplicator'); ?></h3>
        </div>
        <div class="wt-mgdp-modal-body">
            <ul class="reasons">
                <?php
                foreach ($reasons as $reason) {
                    ?>
                    <li data-type="<?php echo esc_attr($reason['type']); ?>" data-placeholder="<?php echo esc_attr(isset($reason['placeholder']) ? $reason['placeholder'] : ''); ?>">
                        <?php
                        if ($reason['id'] == 'pro-feature-suggestion') {
                            ?>
                            <textarea text-align:start id ="wt_suggested_feature" rows="5" cols="45" value=''></textarea>
                            <?php
                        }
                        ?>
                    </li>
                        <?php
                    }
                    ?>
            </ul>

            <div class="wt-mgdp_policy_infobox">
<?php _e("We do not collect any personal data when you submit this form. It's your feedback that we value.", "wp-migration-duplicator"); ?>
                <a href="https://www.webtoffee.com/privacy-policy/" target="_blank"><?php _e('Privacy Policy', 'wp-migration-duplicator'); ?></a>        
            </div>
        </div>
        <div class="wt-mgdp-modal-footer">
            <button class="button-primary wt-mgdp-model-submit"><?php _e('Submit', 'wp-migration-duplicator'); ?></button> 
            <button class="button-secondary wt-mgdp-model-cancel"><?php _e('Cancel', 'wp-migration-duplicator'); ?></button>

        </div>
    </div>
</div>
<?php include WT_MGDP_PLUGIN_PATH . '/admin/partials/wt_migrator_upgrade_to_pro.php'; ?>
<style type="text/css">
    .wt-mgdp-modal {
        position: fixed;
        z-index: 99999;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: rgba(0,0,0,0.5);
        display: none;
    }
    .wt-mgdp-modal.modal-active {display: block;}
    .wt-mgdp-modal-wrap {
        width: 50%;
        position: relative;
        margin: 10% auto;
        background: #fff;
    }
    .wt-mgdp-modal-header {
        border-bottom: 1px solid #eee;
        padding: 8px 20px;
    }
    .wt-mgdp-modal-header h3 {
        line-height: 150%;
        margin: 0;
    }
    .wt-mgdp-modal-body {padding: 5px 20px 5px 20px;}
    .wt-mgdp-modal-body .input-text,.wt-mgdp-modal-body textarea {width:75%;}
    .wt-mgdp-modal-body .input-text::placeholder,.wt-mgdp-modal-body textarea::placeholder{ font-size:12px; }
    .wt-mgdp-modal-body .reason-input {
        margin-top: 5px;
        margin-left: 20px;
    }
    .wt-mgdp-modal-footer {
        border-top: 1px solid #eee;
        padding: 12px 20px;
        text-align: left;
    }
    .wt-mgdp_policy_infobox{font-style:italic; text-align:left; font-size:12px; color:#aaa; line-height:14px; margin-top:35px;}
    .wt-mgdp_policy_infobox a{ font-size:11px; color:#4b9cc3; text-decoration-color: #99c3d7; }
    .sub_reasons{ display:none; margin-left:15px; margin-top:10px; }
    a.dont-bother-me{ color:#939697; text-decoration-color:#d0d3d5; float:right; margin-top:7px; }
    .reasons li{ padding-top:5px; }
</style>
<script type="text/javascript">
    (function ($) {
        $(function () {
            var modal = $('#wt-mgdp-wt-mgdp-modal');
            var deactivateLink = '';
            $('#wt_suggest').on('click', function (e) {
                e.preventDefault();
                modal.addClass('modal-active');
                modal.find('input[type="radio"]:checked').prop('checked', false);
            });
            modal.on('click', 'button.wt-mgdp-model-cancel', function (e) {
                e.preventDefault();
                modal.removeClass('modal-active');
            });
            modal.on('click', 'button.wt-mgdp-model-submit', function (e) {
                e.preventDefault();
                var button = $(this);
                if (button.hasClass('disabled')) {
                    return;
                }
                var reason_id = 'none';
                var reason_info = '';
                var textarea = document.getElementById("wt_suggested_feature").value;
                if (textarea !== '')
                {
                    reason_id = 'pro-feature-suggestion';
                    reason_info = document.getElementById("wt_suggested_feature").value;
                }
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'wt-mgdp_submit_feature',
                        reason_id: reason_id,
                        reason_info: reason_info
                    },
                    beforeSend: function () {
                        button.addClass('disabled');
                        button.text('Processing...');
                    },
                    complete: function () {
                        modal.removeClass('modal-active');
                    }
                });
            });
        });
    }(jQuery));
</script>
