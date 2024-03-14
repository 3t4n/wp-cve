<?php
/*
 *  SwiftSignature Help Setup
 */

function ssign_help_setup_cb() {
    $SSIGN_MESSAGES = swiftsign_global_msg();
    ?>
    <div class="wrap">
        <h2 class="help-setup-title"><?php echo $SSIGN_MESSAGES['ssing_welcome_to']; ?></h2><hr>
        <div class="inner_content help-setup-content">
            <div class="help-setup-page-blue-div">
                <h2><?php echo $SSIGN_MESSAGES['ssing_setup_instructions']; ?></h2>
                <a href="https://SwiftCloud.AI/support/onboarding-electronic-signature" target="_blank">https://SwiftCloud.AI/support/onboarding-electronic-signature</a>
            </div>
            <p><?php echo $SSIGN_MESSAGES['ssing_we_recommend']; ?></p>
            <p><?php echo $SSIGN_MESSAGES['ssing_further_help']; ?><br/>
                <a href="https://SwiftCloud.AI/support/tag/e-sign" target="_blank">https://SwiftCloud.AI/support/tag/e-sign</a>
            </p>
            <p><?php echo $SSIGN_MESSAGES['ssing_a_full_list']; ?><br/>
                <a href="https://SwiftCloud.AI/support/e-sign-shortcodes" target="_blank">https://SwiftCloud.AI/support/e-sign-shortcodes</a>
            </p>

            <!--Send box testing mode-->
            <?php // ssing_testing_mode(); ?>
            <!--Send box testing mode-->

        </div>
    </div>
    <?php
}