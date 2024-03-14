<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$majesticsupport_js ="
    jQuery(document).ready(function ($) {
        $.validate();
    });

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  

<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('adddepertment'); ?>
        <div id="msadmin-data-wrp">
            <form class="msadmin-form" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_department&task=savedepartment"),"save-department")); ?>">
                <div class="mjtc-form-wrapper">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Title', 'majestic-support')); ?>&nbsp;<span style="color: red;" >*</span></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_text('departmentname', isset(majesticsupport::$_data[0]->departmentname) ? majesticsupport::$_data[0]->departmentname : '', array('class' => 'inputbox mjtc-form-input-field', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) ?></div>
                </div>
                <div class="mjtc-form-wrapper">
                    <div class="mjtc-form-title">
                        <?php echo esc_html(__('Outgoing Email', 'majestic-support')); ?>&nbsp;<span style="color: red;" >*</span>
                        <a title="<?php echo esc_attr(__('Add New Email','majestic-support')); ?>" class="mjtc-form-link" href="?page=majesticsupport_email&mjslay=addemail"><?php echo esc_html(__('Add New Email','majestic-support')); ?></a>
                    </div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_select('emailid', MJTC_includer::MJTC_getModel('email')->getEmailForDepartment(), isset(majesticsupport::$_data[0]->emailid) ? majesticsupport::$_data[0]->emailid : '', esc_html(__('Select Email', 'majestic-support')), array('class' => 'inputbox mjtc-form-select-field', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS); ?>
                    </div>
                    <div class="mjtc-form-desc">(<?php echo esc_html(__('The user of this department will receive an email with the new ticket','majestic-support')); ?>)</div>
                </div>
                <div class="mjtc-form-wrapper" style="display:none;">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Public', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_radiobutton('ispublic', array('1' => esc_html(__('Public', 'majestic-support')), '0' => esc_html(__('Private', 'majestic-support'))), isset(majesticsupport::$_data[0]->ispublic) ? majesticsupport::$_data[0]->ispublic : '1', array('class' => 'radiobutton')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <div class="mjtc-form-wrapper" >
                    <div class="mjtc-form-title"><?php echo esc_html(__('Receive Email', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_radiobutton('sendmail', array('1' => esc_html(__('Yes', 'majestic-support')), '0' => esc_html(__('No', 'majestic-support'))), isset(majesticsupport::$_data[0]->sendmail) ? majesticsupport::$_data[0]->sendmail : '0', array('class' => 'radiobutton')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <div class="mjtc-form-wrapper fullwidth">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Signature', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php wp_editor(isset(majesticsupport::$_data[0]->departmentsignature) ? majesticsupport::$_data[0]->departmentsignature : '', 'departmentsignature', array('media_buttons' => false)); ?></div>
                </div>
                <div class="mjtc-form-wrapper">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Append Signature', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value">
                        <div class="mjtc-form-chkbox-field">
                            <?php echo wp_kses(MJTC_formfield::MJTC_checkbox('canappendsignature', array('1' => esc_html(__('Append signature with a reply', 'majestic-support'))), isset(majesticsupport::$_data[0]->canappendsignature) ? majesticsupport::$_data[0]->canappendsignature : '1', array('class' => 'radiobutton')), MJTC_ALLOWED_TAGS); ?>
                        </div>
                    </div>
                </div>
                <div class="mjtc-form-wrapper">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Status', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_radiobutton('status', array('1' => esc_html(__('Enabled', 'majestic-support')), '0' => esc_html(__('Disabled', 'majestic-support'))), isset(majesticsupport::$_data[0]->status) ? majesticsupport::$_data[0]->status : '1', array('class' => 'radiobutton')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <div class="mjtc-form-wrapper" >
                    <div class="mjtc-form-title"><?php echo esc_html(__('Default', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_radiobutton('isdefault', array('2' => esc_html(__('Default with auto assign', 'majestic-support')), '1' => esc_html(__('Yes', 'majestic-support')), '0' => esc_html(__('No', 'majestic-support'))), isset(majesticsupport::$_data[0]->isdefault) ? majesticsupport::$_data[0]->isdefault : '0', array('class' => 'radiobutton')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('id', isset(majesticsupport::$_data[0]->id) ? majesticsupport::$_data[0]->id : ''), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('created', isset(majesticsupport::$_data[0]->created) ? majesticsupport::$_data[0]->created : ''), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('updated', isset(majesticsupport::$_data[0]->updated) ? majesticsupport::$_data[0]->updated : ''), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('ordering', isset(majesticsupport::$_data[0]->ordering) ? majesticsupport::$_data[0]->ordering : ''), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                <div class="mjtc-form-button">
                    <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('save', esc_html(__('Save Department', 'majestic-support')), array('class' => 'button mjtc-form-save')), MJTC_ALLOWED_TAGS); ?>
                </div>
            </form>
        </div>
    </div>
</div>
