<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="ms-main-up-wrapper">
    <?php
if (majesticsupport::$_config['offline'] == 2) {
    if (majesticsupport::$_data['permission_granted'] == 1) {
        if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid() != 0) {
            if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
                if (majesticsupport::$_data['staff_enabled']) {
                    $type = array((object) array('id' => '1', 'text' => esc_html(__('Public', 'majestic-support'))),
                        (object) array('id' => '0', 'text' => esc_html(__('Private', 'majestic-support')))
                    );
                    $status = array((object) array('id' => '1', 'text' => esc_html(__('Enabled', 'majestic-support'))),
                        (object) array('id' => '0', 'text' => esc_html(__('Disabled', 'majestic-support')))
                    );
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
    <?php include_once(MJTC_PLUGIN_PATH . 'includes/header.php'); ?>
    <div class="mjtc-support-top-sec-header">
        <img class="mjtc-transparent-header-img1" alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
            src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/tp-image.png" />
        <div class="mjtc-support-top-sec-left-header">
            <div class="mjtc-support-main-heading">
                <?php echo esc_html(__("Add Department",'majestic-support')); ?>
            </div>
            <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageBreadcrumps('adddepartment'); ?>
        </div>
    </div>
    <div class="mjtc-support-cont-main-wrapper">
        <div class="mjtc-support-cont-wrapper mjtc-support-cont-wrapper-color">
            <div class="mjtc-support-add-form-wrapper">
                <form class="mjtc-support-form" method="post" action="<?php echo esc_url(wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'department', 'task'=>'savedepartment')),"save-department")); ?>">
                    <div class="mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width">
                        <div class="mjtc-support-from-field-title">
                            <?php echo esc_html(__('Title', 'majestic-support')); ?>&nbsp;<span style="color: red;">*</span>
                        </div>
                        <div class="mjtc-support-from-field">
                            <?php echo wp_kses(MJTC_formfield::MJTC_text('departmentname', isset(majesticsupport::$_data[0]->departmentname) ? majesticsupport::$_data[0]->departmentname : '', array('class' => 'inputbox mjtc-support-form-field-input', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) ?>
                        </div>
                    </div>
                    <div class="mjtc-support-from-field-wrp">
                        <div class="mjtc-support-from-field-title">
                            <?php echo esc_html(__('Outgoing Email', 'majestic-support')); ?>&nbsp;<span
                                style="color: red;">*</span>
                        </div>
                        <div class="mjtc-support-from-field mjtc-support-form-field-select">
                            <?php echo wp_kses(MJTC_formfield::MJTC_select('emailid', MJTC_includer::MJTC_getModel('email')->getEmailForDepartment(), isset(majesticsupport::$_data[0]->emailid) ? majesticsupport::$_data[0]->emailid : '', esc_html(__('Select Email', 'majestic-support')), array('class' => 'inputbox mjtc-support-form-field-select', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS); ?>
                        </div>
                        <span
                            class="majestic-support-outgoing-email-message">(<?php echo esc_html(__('Tickets of this department will receive emails from this email','majestic-support'));?>)</span>
                    </div>
                    <div class="mjtc-support-from-field-wrp">
                        <div class="mjtc-support-from-field-title">
                            <?php echo esc_html(__('Receive Email', 'majestic-support')); ?>&nbsp;<span
                                style="color: red;">*</span>
                        </div>
                        <div class="mjtc-support-from-field">
                            <div class="mjtc-support-radio-btn-wrp">
                                <?php echo wp_kses(MJTC_formfield::MJTC_radiobutton('sendmail', array('1' => esc_html(__('Yes', 'majestic-support')), '0' => esc_html(__('No', 'majestic-support'))), isset(majesticsupport::$_data[0]->sendmail) ? majesticsupport::$_data[0]->sendmail : '0', array('class' => 'radiobutton mjtc-support-form-field-radio-btn')), MJTC_ALLOWED_TAGS); ?>
                            </div>
                        </div>
                    </div>
                    <div class="mjtc-support-append-signature-wrp mjtc-support-append-signature-wrp-full-width">
                        <!-- Append Signature -->
                        <div class="mjtc-support-append-field-title">
                            <?php echo esc_html(__('Append Signature', 'majestic-support')); ?></div>
                        <div class="mjtc-support-append-field-wrp">
                            <div class="mjtc-support-signature-radio-box mjtc-support-signature-radio-box-full-width ">
                                <?php echo wp_kses(MJTC_formfield::MJTC_checkbox('canappendsignature', array('1' => esc_html(__('Append signature with a reply', 'majestic-support'))), isset(majesticsupport::$_data[0]->canappendsignature) ? majesticsupport::$_data[0]->canappendsignature : '', array('class' => 'radiobutton mjtc-support-append-radio-btn')), MJTC_ALLOWED_TAGS); ?>
                            </div>

                        </div>
                    </div>
                    <div class="mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width">
                        <div class="mjtc-support-from-field-title">
                            <?php echo esc_html(__('Signature', 'majestic-support')); ?>&nbsp;<span style="color: red;">*</span>
                        </div>
                        <div class="mjtc-support-from-field">
                            <?php wp_editor(isset(majesticsupport::$_data[0]->departmentsignature) ? majesticsupport::$_data[0]->departmentsignature : '', 'departmentsignature', array('media_buttons' => false)); ?>
                        </div>
                    </div>
                    <div class="mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width">
                        <div class="mjtc-support-from-field-title">
                            <?php echo esc_html(__('Status', 'majestic-support')); ?>&nbsp;<span style="color: red;">*</span>
                        </div>
                        <div class="mjtc-support-from-field mjtc-support-form-field-select">
                            <?php echo wp_kses(MJTC_formfield::MJTC_select('status', $status, isset(majesticsupport::$_data[0]->status) ? majesticsupport::$_data[0]->status : '', esc_html(__('Select Status', 'majestic-support')), array('class' => 'inputbox mjtc-support-form-field-input')), MJTC_ALLOWED_TAGS); ?>
                        </div>
                    </div>
                    <div class="mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width">
                        <div class="mjtc-support-from-field-title">
                            <?php echo esc_html(__('Default', 'majestic-support')); ?>&nbsp;<span style="color: red;">*</span>
                        </div>
                        <div class="mjtc-support-from-field mjtc-support-form-field-select">
                            <?php echo wp_kses(MJTC_formfield::MJTC_radiobutton('isdefault', array('2' => esc_html(__('Default with auto assign', 'majestic-support')), '1' => esc_html(__('Yes', 'majestic-support')), '0' => esc_html(__('No', 'majestic-support'))), isset(majesticsupport::$_data[0]->isdefault) ? majesticsupport::$_data[0]->isdefault : '0', array('class' => 'radiobutton mjtc-support-form-field-radio-btn')), MJTC_ALLOWED_TAGS); ?>

                        </div>
                    </div>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('id', isset(majesticsupport::$_data[0]->id) ? majesticsupport::$_data[0]->id : ''), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('created', isset(majesticsupport::$_data[0]->created) ? majesticsupport::$_data[0]->created : ''), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('updated', isset(majesticsupport::$_data[0]->updated) ? majesticsupport::$_data[0]->updated : ''), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('ordering', isset(majesticsupport::$_data[0]->ordering) ? majesticsupport::$_data[0]->ordering : ''), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('action', 'department_savedepartment'), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('mspageid', get_the_ID()), MJTC_ALLOWED_TAGS); ?>
                    <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                    <div class="mjtc-support-form-btn-wrp">
                        <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('save', esc_html(__('Save Department', 'majestic-support')), array('class' => 'mjtc-support-save-button')), MJTC_ALLOWED_TAGS); ?>
                        <a href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'department', 'mjslay'=>'departments')));?>"
                            class="mjtc-support-cancel-button"><?php echo esc_html(__('Cancel','majestic-support')); ?></a>
                    </div>
                </form>
            </div>
            <?php
                } else {
                    MJTC_layout::MJTC_getStaffMemberDisable();
                }
            } else { // user not Staff
                MJTC_layout::MJTC_getNotStaffMember();
            }
        } else {
            $redirect_url = majesticsupport::makeUrl(array('mjsmod'=>'department', 'mjslay'=>'adddepartment'));
            $redirect_url = MJTC_majesticsupportphplib::MJTC_safe_encoding($redirect_url);
            MJTC_layout::MJTC_getUserGuest($redirect_url);
        }
    } else { // User permission not granted
        MJTC_layout::MJTC_getPermissionNotGranted();
    }
} else {
    MJTC_layout::MJTC_getSystemOffline();
} ?>
        </div>
    </div>
</div>
