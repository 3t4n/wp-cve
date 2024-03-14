<footer>
 <?php if (get_option('gs-api-key') != '') { ?>
    <a id="gs-backToTop" href="javascript:void(0)" class="gs-button gs-primary"><i class="fa fa-angle-up"></i> Back to Top</a>
    <?php if (!$GS->is_pro()) { ?>
    &nbsp;&nbsp;&nbsp;&nbsp;or&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="<?php echo $GS->gs_account() ?>/sites/gs-wordpress/billing/select_tier?api_key=<?php echo $GS->api_key ?>&amp;source=wordpress" target="_blank" class="gs-button plan-one">Upgrade to TOOLS</a>
    <?php } ?>

    <div class="alert-block plan-free gs-primary alert-info" style="max-width: 600px; padding: 20px 30px; margin: 60px auto 0; border-radius: 4px;">
        <p class="alert-title" style="margin-bottom: 20px; font-size: 18px; font-weight: 600;">
        Love GetSocial? Here are a couple of ways you can help:</p>
        <ol style="text-align: left; line-height: 18px; color: #fff; padding-left: 15px; font-size: 15px; list-style-type: decimal; font-weight: 500;">
            <li style="margin-bottom: 5px">Go to WordPress.org now and give <a style="color: inherit;" href="https://wordpress.org/support/view/plugin-reviews/wp-share-buttons-analytics-by-getsocial?filter=5#postform" target="_blank"><strong>this plugin a 5 <i class="fa fa-star"></i> rating</strong></a></li>
            <li>Blog about GetSocial and <a style="color: inherit;" href="https://wordpress.org/plugins/wp-share-buttons-analytics-by-getsocial/" target="_blank">link to the plugin page</a> or <strong><a style="color: inherit;" href="http://getsocial.io" target="_blank">getsocial.io</strong></a>.
        </ol>
    </div>

    <div style="text-align: center; margin-top: 15px; font-size: 16px;">
        <a target="_blank" href="http://getsocial.io/" style="color: #339ed5 !important; font-weight: 500;">GetSocial Share Buttons <?php echo $GS->plugin_version; ?></a>
    </div>
    <a href="javascript:void(0)" id="thankyou" class="ssba-btn-thank-you pull-right gs-button gs-primary" style="position: fixed; bottom: 150px; right: 0; border-radius: 4px 0 0 4px; font-size: 20px; padding-top: 6px;"><i class="fa fa-star" style="color:yellow"></i></a>
    <?php } ?>
</footer>

<!-- Thank you Modal -->
<div id="thankyou-modal" class="modal-wrapper hide">
    <div class="gs-modal small text-center">
        <div class="modal-title">
            <p class="title" style="padding-bottom: 10px;">Thank you!</p>
        </div>
        <p class="alert-block" style="text-align: left; padding-bottom: 0; font-weight: 500;">
            We’re thrilled you chose <a style="color:#02b6b3" target="_blank" href="http://getsocial.io/">Social Sharing, Smart Popup & Share Buttons by GetSocial.io</a> to increase your traffic, shares and subscribers on WordPress.<br><br><strong>We honestly hope the plugin is working out for you and your website.</strong><br><br>
            <div style="padding: 20px 10px; background: #02b6b3; color: #fff; border-radius: 4px; margin-top: 10px; font-weight: 500;">If you like the plugin, please consider supporting us through a <a style="color:#fff; font-weight: bold; text-decoration: underline;" target="_blank" href="https://wordpress.org/support/view/plugin-reviews/wp-share-buttons-analytics-by-getsocial?filter=5#postform">positive review on WordPress.org</a>.</div>
        </p>
    </div>
    <div class="modal-cover modal-close"></div>
</div>
