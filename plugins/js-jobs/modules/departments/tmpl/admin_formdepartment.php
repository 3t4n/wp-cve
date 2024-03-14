<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script >
    jQuery(document).ready(function ($) {
        $.validate();
    });
</script>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php $status = array((object) array('id' => 0, 'text' => __('Pending', 'js-jobs')), (object) array('id' => 1, 'text' => __('Approve', 'js-jobs')), (object) array('id' => -1, 'text' => __('Reject'))); ?>
    <span class="js-admin-title">
        <a href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_departments')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php
        $heading = isset(jsjobs::$_data[0]) ? __('Edit', 'js-jobs') : __('Add New', 'js-jobs');
        echo esc_html($heading) . ' ' . __('Department', 'js-jobs');
        ?>
    </span>
    <form id="department_form" class="jsjobs-form" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jsjobs_departments&task=savedepartment")); ?>">
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Company', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::select('companyid', JSJOBSincluder::getJSModel('company')->getCompaniesForCombo(), isset(jsjobs::$_data[0]->companyid) ? jsjobs::$_data[0]->companyid : '', __('Select','js-jobs') .' '. __('Company', 'js-jobs'), array('class' => 'inputbox one', 'data-validation' => 'required')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Department Name', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::text('name', isset(jsjobs::$_data[0]->name) ? jsjobs::$_data[0]->name : '', array('maxlength' => '70', 'class' => 'inputbox one', 'data-validation' => 'required')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Description', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php wp_editor(isset(jsjobs::$_data[0]->description) ? jsjobs::$_data[0]->description : '', 'description', array('media_buttons' => false)); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin status-field-on-form">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Status', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::select('status', $status, isset(jsjobs::$_data[0]->status) ? jsjobs::$_data[0]->status : 1, __('Select Status', 'js-jobs'), array('class' => 'inputbox one', 'data-validation' => 'required')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <?php echo wp_kses(JSJOBSformfield::hidden('isqueue', isset($_GET['isqueue']) ? 1 : 0), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]->id) ? jsjobs::$_data[0]->id : ''), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('action', 'department_savedepartment'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('isadmin', '1'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('payment', ''), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('creditid', ''), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('tast', 'savedepartment'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('uid', ''), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('save-department')), JSJOBS_ALLOWED_TAGS); ?>
        <div class="js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
            <a id="form-cancel-button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_departments'),"department")); ?>" ><?php echo __('Cancel', 'js-jobs'); ?></a>
            <?php
               echo wp_kses(JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Department', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); 
            ?>
        </div>
    </form>
</div>
</div>
