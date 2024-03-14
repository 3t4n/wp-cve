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
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_careerlevel'),"careerlevel")); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php
        $heading = isset(jsjobs::$_data[0]) ? __('Edit', 'js-jobs') : __('Add New','js-jobs');
        echo esc_html($heading) . ' ' . __('Career Levels', 'js-jobs');
        ?>
    </span>
    <form id="jsjobs-form" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jsjobs_careerlevel&task=savecareerlevel")); ?>">
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Title', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::text('title', isset(jsjobs::$_data[0]->title) ? __(jsjobs::$_data[0]->title,'js-jobs') : '', array('class' => 'inputbox one', 'data-validation' => 'required')), JSJOBS_ALLOWED_TAGS) ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Published', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::radiobutton('status', array('1' => __('Yes', 'js-jobs'), '0' => __('No', 'js-jobs')), isset(jsjobs::$_data[0]->status) ? jsjobs::$_data[0]->status : 1, array('class' => 'radiobutton')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Default', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::radiobutton('isdefault', array('1' => __('Yes', 'js-jobs'), '0' => __('No', 'js-jobs')), isset(jsjobs::$_data[0]->isdefault) ? jsjobs::$_data[0]->isdefault : 0, array('class' => 'radiobutton')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <?php echo wp_kses(JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]->id) ? jsjobs::$_data[0]->id : ''), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('ordering', isset(jsjobs::$_data[0]->ordering) ? jsjobs::$_data[0]->ordering : '' ), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('jsjobs_isdefault', isset(jsjobs::$_data[0]->isdefault) ? jsjobs::$_data[0]->isdefault : ''), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('action', 'careerlevel_savecareerlevel'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('save-careerlevel')), JSJOBS_ALLOWED_TAGS); ?>
        <div class="js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
            <a id="form-cancel-button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_careerlevel'),"careerlevel")); ?>" ><?php echo __('Cancel', 'js-jobs'); ?></a>
            <?php echo wp_kses(JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Career Level', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
        </div>
    </form>
</div>
</div>
