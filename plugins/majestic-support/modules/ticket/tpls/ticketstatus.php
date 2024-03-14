<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="ms-main-up-wrapper">
    <?php
if (majesticsupport::$_config['offline'] == 2) {
    MJTC_message::MJTC_getMessage();
    ?>
    <?php include_once(MJTC_PLUGIN_PATH . 'includes/header.php'); ?>
    <div class="mjtc-support-top-sec-header">
        <img class="mjtc-transparent-header-img1" alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
            src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/tp-image.png" />
        <div class="mjtc-support-top-sec-left-header">
            <div class="mjtc-support-main-heading">
                <?php echo esc_html(__("Ticket Status",'majestic-support')); ?>
            </div>
            <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageBreadcrumps('ticketstatus'); ?>
        </div>
    </div>
    <div class="mjtc-support-cont-main-wrapper">
        <div class="mjtc-support-cont-wrapper mjtc-support-cont-wrapper-color">
            <?php if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid() != 0 || majesticsupport::$_config['visitor_can_create_ticket'] == 1) { ?>
            <div class="mjtc-support-checkstatus-wrp">
                <form class="mjtc-support-form form-validate" action="<?php echo esc_url(wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'ticket','task'=>'showticketstatus')),"show-ticket-status")); ?>"
                    method="post" id="adminForm" enctype="multipart/form-data">
                    <div class="mjtc-support-checkstatus-field-wrp">
                        <div class="mjtc-support-field-title">
                            <?php echo esc_html(__('Email','majestic-support')); ?>&nbsp;<span style="color: red">*</span>
                        </div>
                        <div class="mjtc-support-field-wrp">
                            <input class="inputbox mjtc-support-form-input-field required validate-email" data-validation="email" type="text" name="email" id="email" size="40" maxlength="255" value="<?php if (isset(majesticsupport::$_data['0']->email)) echo esc_attr(majesticsupport::$_data['0']->email); ?>" required />
                        </div>
                    </div>
                    <div class="mjtc-support-checkstatus-field-wrp">
                        <div class="mjtc-support-field-title">
                            <?php echo esc_html(__('Ticket ID','majestic-support')); ?>&nbsp;<span style="color: red">*</span>
                        </div>
                        <div class="mjtc-support-field-wrp">
                            <input class="inputbox mjtc-support-form-input-field required" type="text" name="ticketid"
                                id="ticketid" size="40" maxlength="255" value="" required />
                        </div>
                    </div>
                    <div class="mjtc-support-form-btn-wrp">
                        <input class="tk_dft_btn mjtc-support-save-button" type="submit" name="submit_app"
                            value="<?php echo esc_html(__('Check Status', 'majestic-support')); ?>" />
                    </div>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('checkstatus', 1), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('mspageid',get_the_ID()), MJTC_ALLOWED_TAGS); ?>
                </form>
            </div>
            <?php
    }else {// User is guest
        $redirect_url = majesticsupport::makeUrl(array('mjsmod'=>'ticket','mjslay'=>'ticketstatus'));
        $redirect_url = MJTC_majesticsupportphplib::MJTC_safe_encoding($redirect_url);
        MJTC_layout::MJTC_getUserGuest($redirect_url);
    }
} else { // System is offline
    MJTC_layout::MJTC_getSystemOffline();
}
?>
        </div>
    </div>
</div>
