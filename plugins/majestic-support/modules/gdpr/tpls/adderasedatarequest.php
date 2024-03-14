<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="ms-main-up-wrapper">
    <?php
    if (majesticsupport::$_config['offline'] == 2) {
            $yesno = array((object) array('id' => '1', 'text' => esc_html(__('Yes', 'majestic-support'))),
                (object) array('id' => '0', 'text' => esc_html(__('No', 'majestic-support')))
            );
            ?>
    <?php
    $majesticsupport_js ="
        jQuery(document).ready(function($) {
            $.validate();
        });

    ";
    wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
    ?>  
    <?php MJTC_message::MJTC_getMessage(); ?>
    <?php $formdata = MJTC_formfield::MJTC_getFormData(); ?>
    <?php include_once(MJTC_PLUGIN_PATH . 'includes/header.php'); ?>
    <div class="mjtc-support-top-sec-header">
        <img class="mjtc-transparent-header-img1" alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
        src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/tp-image.png" />
        <div class="mjtc-support-top-sec-left-header">
            <div class="mjtc-support-main-heading">
                <?php echo esc_html(__("User Data",'majestic-support')); ?>
            </div>
            <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageBreadcrumps('userdata'); ?>
        </div>
    </div>
    <div class="mjtc-support-cont-main-wrapper">
        <div class="mjtc-support-cont-wrapper mjtc-support-cont-wrapper-color">
        <?php if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid() != 0) { ?>
            <div class="mjtc-support-add-form-wrapper">
                <div class="mjtc-support-top-search-wrp">
                    <div class="mjtc-support-search-heading-wrp">
                        <div class="mjtc-support-heading-left">
                            <?php echo esc_html(__('Export your data', 'majestic-support')) ?>
                        </div>
                        <div class="mjtc-support-heading-right">
                            <a class="mjtc-support-add-download-btn" href="<?php echo esc_url(wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'gdpr','task'=>'exportusereraserequest','action'=>'mstask','majesticsupportid'=> MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid() ,'mspageid'=>get_the_ID())),'export-usereraserequest')); ?>"><span
                                    class="mjtc-support-add-img-wrp"></span><?php echo esc_html(__('Export', 'majestic-support')) ?></a>
                        </div>
                    </div>
                </div>
                <?php if(isset(majesticsupport::$_data[0]) && !empty(majesticsupport::$_data[0])) { ?>
                <div class="mjtc-support-top-search-wrp second-style">
                    <div class="mjtc-support-search-heading-wrp second-style">
                        <div class="mjtc-support-heading-left">
                            <?php echo esc_html(__('You have filed a request to remove your data.', 'majestic-support')) ?>
                        </div>
                        <div class="mjtc-support-heading-right">
                            <a class="mjtc-support-add-download-btn" href="<?php echo esc_url(wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'gdpr','task'=>'removeusereraserequest','action'=>'mstask','majesticsupportid'=> majesticsupport::$_data[0]->id ,'mspageid'=>get_the_ID())),'delete-usereraserequest')); ?>"><span
                                    class="mjtc-support-add-img-wrp"></span><?php echo esc_html(__('To withdraw erases data request', 'majestic-support')) ?></a>
                        </div>
                    </div>
                </div>
                <?php }else{ ?>
                <div class="mjtc-support-top-search-wrp second-style">
                    <div class="mjtc-support-search-heading-wrp second-style">
                        <div class="mjtc-support-heading-left">
                            <?php echo esc_html(__('Request the removal of data from the system.', 'majestic-support')) ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <form class="mjtc-support-form" method="post" action="<?php echo esc_url(wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'gdpr', 'task'=>'saveusereraserequest')),"save-usereraserequest")); ?>">
                    <div class="mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width">
                        <div class="mjtc-support-from-field-title">
                            <?php echo esc_html(__('Subject', 'majestic-support')); ?>&nbsp;<span style="color: red;">*</span>
                        </div>
                        <div>

                        </div>

                        <div class="mjtc-support-from-field">
                            <?php
                                if(isset($formdata['subject'])) $subject = $formdata['subject'];
                                elseif(isset(majesticsupport::$_data[0]->subject)) $subject = majesticsupport::$_data[0]->subject;
                                else $subject = '';
                                echo wp_kses(MJTC_formfield::MJTC_text('subject', $subject, array('class' => 'inputbox mjtc-support-form-field-input', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS);
                            ?>
                        </div>
                    </div>
                    <div class="mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width">
                        <div class="mjtc-support-from-field-title">
                            <?php echo esc_html(__('Message', 'majestic-support')); ?>&nbsp;<span style="color: red;">*</span>
                        </div>
                        <div class="mjtc-support-from-field">
                            <?php wp_editor(isset(majesticsupport::$_data[0]->message) ? majesticsupport::$_data[0]->message : '', 'message', array('media_buttons' => false)); ?>
                        </div>
                    </div>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('mspageid', get_the_ID()), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('id', isset(majesticsupport::$_data[0]->id) ?majesticsupport::$_data[0]->id :'' ), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                    <div class="mjtc-support-form-btn-wrp">
                        <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('save', esc_html(__('Save', 'majestic-support')), array('class' => 'mjtc-support-save-button')), MJTC_ALLOWED_TAGS); ?>
                        <a href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'majesticsupport', 'mjslay'=>'controlpanel')));?>" class="mjtc-support-cancel-button"><?php echo esc_html(__('Cancel','majestic-support')); ?></a>
                    </div>
                </form>
            </div>
            <?php
        } else {
            MJTC_layout::MJTC_getUserGuest();
        }
    } else {
        MJTC_layout::MJTC_getSystemOffline();
} ?>
        </div>
    </div>
</div>
