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
        <a href="<?php echo esc_url(admin_url('admin.php?page=jsjobs_fieldordering&ff='.jsjobs::$_data['fieldfor'])); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php
        $heading = isset(jsjobs::$_data[0]['fieldvalues']) ? __('Edit', 'js-jobs') : __('Add New', 'js-jobs');
        echo esc_html($heading) . ' ' . __('User Field', 'js-jobs');
        ?>
    </span>
    <?php
    $yesno = array(
        (object) array('id' => 1, 'text' => __('Yes', 'js-jobs')),
        (object) array('id' => 0, 'text' => __('No', 'js-jobs')));

    $sectionarray = array(
        (object) array('id' => 1, 'text' => __('Personal Information', 'js-jobs')),
        (object) array('id' => 2, 'text' => __('Addresses', 'js-jobs')),
        (object) array('id' => 3, 'text' => __('Education', 'js-jobs')),
        (object) array('id' => 4, 'text' => __('Employer', 'js-jobs')),
        (object) array('id' => 5, 'text' => __('Skills', 'js-jobs')),
        (object) array('id' => 6, 'text' => __('Resume', 'js-jobs')),
        (object) array('id' => 7, 'text' => __('References', 'js-jobs')),
        (object) array('id' => 8, 'text' => __('Languages', 'js-jobs')));

if(isset(jsjobs::$_data[0]['userfield']->userfieldtype)){
    if(jsjobs::$_data[0]['userfield']->userfieldtype == 'text' || jsjobs::$_data[0]['userfield']->userfieldtype == 'email' || jsjobs::$_data[0]['userfield']->userfieldtype == 'date' || jsjobs::$_data[0]['userfield']->userfieldtype == 'textarea'){
        $fieldtypes = array(
            (object) array('id' => 'text', 'text' => __('Text Field', 'js-jobs')),
            (object) array('id' => 'date', 'text' => __('Date', 'js-jobs')),
            (object) array('id' => 'email', 'text' => __('Email Address', 'js-jobs')),
            (object) array('id' => 'textarea', 'text' => __('Text Area', 'js-jobs')));
            $fieldtype_Array = array('class' => 'inputbox one', 'data-validation' => 'required', 'onchange' => 'toggleType(this.options[this.selectedIndex].value);');
    }else{
        $fieldtypes = array(
        (object) array('id' => 'text', 'text' => __('Text Field', 'js-jobs')),
        (object) array('id' => 'checkbox', 'text' => __('Check Box', 'js-jobs')),
        (object) array('id' => 'date', 'text' => __('Date', 'js-jobs')),
        (object) array('id' => 'combo', 'text' => __('Drop Down', 'js-jobs')),
        (object) array('id' => 'email', 'text' => __('Email Address', 'js-jobs')),
        (object) array('id' => 'textarea', 'text' => __('Text Area', 'js-jobs')),
        (object) array('id' => 'radio', 'text' => __('Radio Button', 'js-jobs')),
        (object) array('id' => 'depandant_field', 'text' => __('Dependent Field', 'js-jobs')),
        (object) array('id' => 'multiple', 'text' => __('Multi Select', 'js-jobs')));
        $fieldtype_Array = array('class' => 'inputbox one', 'data-validation' => 'required', 'onchange' => 'toggleType(this.options[this.selectedIndex].value);','disabled'=>'disabled');
    }
}else{
    $fieldtypes = array(
        (object) array('id' => 'text', 'text' => __('Text Field', 'js-jobs')),
        (object) array('id' => 'checkbox', 'text' => __('Check Box', 'js-jobs')),
        (object) array('id' => 'date', 'text' => __('Date', 'js-jobs')),
        (object) array('id' => 'combo', 'text' => __('Drop Down', 'js-jobs')),
        (object) array('id' => 'email', 'text' => __('Email Address', 'js-jobs')),
        (object) array('id' => 'textarea', 'text' => __('Text Area', 'js-jobs')),
        (object) array('id' => 'radio', 'text' => __('Radio Button', 'js-jobs')),
        (object) array('id' => 'depandant_field', 'text' => __('Dependent Field', 'js-jobs')),
        (object) array('id' => 'multiple', 'text' => __('Multi Select', 'js-jobs')));
        $fieldtype_Array = array('class' => 'inputbox one', 'data-validation' => 'required', 'onchange' => 'toggleType(this.options[this.selectedIndex].value);');
}


    ?>
    <form id="jsjobs-form" method="post" action="<?php echo esc_url(admin_url("admin.php?page=jsjobs_fieldordering&task=saveuserfield")); ?>">
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Field Type', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::select('userfieldtype', $fieldtypes, isset(jsjobs::$_data[0]['userfield']->userfieldtype) ? jsjobs::$_data[0]['userfield']->userfieldtype : 'text', '',$fieldtype_Array), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin" id="for-combo-wrapper" style="display:none;">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Select','js-jobs') .'&nbsp;'. __('Parent Field', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding" id="for-combo"></div>
            <span class="js-field-warning">
                <?php echo __('Parent Field cannot be changeable in edit case','js-jobs') ?>
            </span>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Field Title', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::text('fieldtitle', isset(jsjobs::$_data[0]['userfield']->fieldtitle) ? jsjobs::$_data[0]['userfield']->fieldtitle : '', array('class' => 'inputbox one', 'data-validation' => 'required', 'maxlength' => '50')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin divSection">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Show on listing', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::select('showonlisting', $yesno, isset(jsjobs::$_data[0]['userfield']->showonlisting) ? jsjobs::$_data[0]['userfield']->showonlisting : 0, '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <?php if (jsjobs::$_data[0]['fieldfor'] == 3) { ?>
            <div class="js-field-wrapper js-row no-margin">
                <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Resume Section', 'js-jobs'); ?><font class="required-notifier">*</font></div>
                <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding">
                    <?php 
                        if(isset(jsjobs::$_data[0]['userfield']->section)){
                            $farray = array('class' => 'inputbox one', 'data-validation' => 'required', 'disabled' => 'true', 'onchange' => 'toggleSection(this.options[this.selectedIndex].value);');
                        }else{
                            $farray = array('class' => 'inputbox one', 'data-validation' => 'required', 'onchange' => 'toggleSection(this.options[this.selectedIndex].value);');
                        }
                        echo wp_kses(JSJOBSformfield::select('section', $sectionarray, isset(jsjobs::$_data[0]['userfield']->section) ? jsjobs::$_data[0]['userfield']->section : '', '', $farray), JSJOBS_ALLOWED_TAGS); echo '<span class="jsjobs-fieldordering-warning">[&nbsp;'.__('Section cannot be changeable in edit case','js-jobs').'&nbsp;]</span>';
                    ?>
                </div>
            </div>
        <?php } ?>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('User Published', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::select('published', $yesno, isset(jsjobs::$_data[0]['userfield']->published) ? jsjobs::$_data[0]['userfield']->published : 1, '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Visitor Published', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::select('isvisitorpublished', $yesno, isset(jsjobs::$_data[0]['userfield']->isvisitorpublished) ? jsjobs::$_data[0]['userfield']->isvisitorpublished : 1, '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <?php if (jsjobs::$_data[0]['fieldfor'] != 1) { ?>
            <div class="js-field-wrapper js-row no-margin divSection">
                <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('User Search', 'js-jobs'); ?></div>
                <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::select('search_user', $yesno, isset(jsjobs::$_data[0]['userfield']->search_user) ? jsjobs::$_data[0]['userfield']->search_user : 1, '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
            </div>
            <div class="js-field-wrapper js-row no-margin divSection">
                <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Visitor Search', 'js-jobs'); ?></div>
                <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::select('search_visitor', $yesno, isset(jsjobs::$_data[0]['userfield']->search_visitor) ? jsjobs::$_data[0]['userfield']->search_visitor : 1, '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
            </div>
        <?php } else { ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('search_user', 0), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('search_visitor', 0), JSJOBS_ALLOWED_TAGS); ?>
            <?php echo wp_kses(JSJOBSformfield::hidden('cannotsearch', 1), JSJOBS_ALLOWED_TAGS); ?>
        <?php } ?>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Required', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::select('required', $yesno, isset(jsjobs::$_data[0]['userfield']->required) ? jsjobs::$_data[0]['userfield']->required : 0, '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <?php /*
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Field Size', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::text('size', isset(jsjobs::$_data[0]['userfield']->size) ? jsjobs::$_data[0]['userfield']->size : '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Java Script', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::textarea('j_script', isset(jsjobs::$_data[0]['userfield']->j_script) ? jsjobs::$_data[0]['userfield']->j_script : '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        */ ?>

        <div id="for-combo-options-head" >
            <span class="js-admin-title"><?php echo __('Use The Table Below To Add New Values', 'js-jobs'); ?></span>

        </div>
        <div id="for-combo-options" >
            <?php
            $arraynames = '';
            $comma = '';
            if (isset(jsjobs::$_data[0]['userfieldparams']) && jsjobs::$_data[0]['userfield']->userfieldtype == 'depandant_field') {
                foreach (jsjobs::$_data[0]['userfieldparams'] as $key => $val) {
                    $textvar = $key;
                    $textvar = jsjobslib::jsjobs_str_replace(' ','__',$textvar);
                    $textvar = jsjobslib::jsjobs_str_replace('.','___',$textvar);
                    $divid = $textvar;
                    $textvar .='[]';
                    $arraynames .= $comma . "$key";
                    $comma = ',';
                    ?>
                    <div class="js-field-wrapper js-row no-margin">
                        <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding">
                            <?php echo esc_html($key); ?>
                        </div>
                        <div class="js-col-lg-9 js-col-md-9 no-padding combo-options-fields" id="<?php echo esc_attr($divid); ?>">

                            <?php
                            if (!empty($val)) {
                                foreach ($val as $each) {
                                    ?>
                                    <span class="input-field-wrapper">
                                        <input name="<?php echo esc_attr($textvar); ?>" id="<?php echo esc_attr($textvar); ?>" value="<?php echo esc_attr($each); ?>" class="inputbox one user-field" type="text">
                                        <img class="input-field-remove-img" src="<?php echo JSJOBS_PLUGIN_URL ?>includes/images/remove.png">
                                    </span><?php
                                }
                            }
                            ?>
                            <input id="depandant-field-button" onclick="getNextField( &quot;<?php echo esc_attr($divid); ?>&quot;,this );" value="Add More" type="button">
                        </div>
                    </div><?php
                }
            }
            ?>
        </div>

        <div id="divText" class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Max Length', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::text('maxlength', isset(jsjobs::$_data[0]['userfield']->maxlength) ? jsjobs::$_data[0]['userfield']->maxlength : '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <?php /*<div class="js-field-wrapper divColsRows js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Columns', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::text('cols', isset(jsjobs::$_data[0]['userfield']->cols) ? jsjobs::$_data[0]['userfield']->cols : '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div> */?>
        <div class="js-field-wrapper divColsRows js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Rows', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::text('rows', isset(jsjobs::$_data[0]['userfield']->rows) ? jsjobs::$_data[0]['userfield']->rows : '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div id="divValues" class="js-field-wrapper divColsRows js-row no-margin">
            <span class="js-admin-title"><?php echo __('Use The Table Below To Add New Values', 'js-jobs'); ?></span>
            <div class="page-actions js-row no-margin">
                <div id="user-field-values" class="no-padding">
                    <?php
                    if (isset(jsjobs::$_data[0]['userfield']) && jsjobs::$_data[0]['userfield']->userfieldtype != 'depandant_field') {
                        if (isset(jsjobs::$_data[0]['userfieldparams'])) {
                            foreach (jsjobs::$_data[0]['userfieldparams'] as $key => $val) {
                                ?>
                                <span class="input-field-wrapper">
                                    <?php echo wp_kses(JSJOBSformfield::text('values[]', isset($val) ? $val : '', array('class' => 'inputbox one user-field')), JSJOBS_ALLOWED_TAGS); ?>
                                    <img class="input-field-remove-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/remove.png" />
                                </span>
                            <?php
                            }
                        } else {
                            ?>
                            <span class="input-field-wrapper">
                            <?php echo wp_kses(JSJOBSformfield::text('values[]', isset($val) ? $val : '', array('class' => 'inputbox one user-field')), JSJOBS_ALLOWED_TAGS); ?>
                                <img class="input-field-remove-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/remove.png" />
                            </span>
                        <?php
                        }
                    }
                    ?>
                    <a class="js-button-link button user-field-val-button" id="user-field-val-button" onclick="insertNewRow();"><?php echo __('Add Value', 'js-jobs') ?></a>
                </div>	
            </div>
        </div>
        <?php echo wp_kses(JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]['userfield']->id) ? jsjobs::$_data[0]['userfield']->id : ''), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('isuserfield', 1), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('fieldfor', jsjobs::$_data[0]['fieldfor']), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('ordering', isset(jsjobs::$_data[0]['userfield']->ordering) ? jsjobs::$_data[0]['userfield']->ordering : '' ), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('action', 'fieldordering_saveuserfield'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('fieldname', isset(jsjobs::$_data[0]['userfield']->field) ? jsjobs::$_data[0]['userfield']->field : '' ), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('parentvalue', isset(jsjobs::$_data[0]['userfield']->parentfield) ? jsjobs::$_data[0]['userfield']->parentfield : '' ), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('field', isset(jsjobs::$_data[0]['userfield']->field) ? jsjobs::$_data[0]['userfield']->field : '' ), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('arraynames2', $arraynames), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('save-fieldordering')), JSJOBS_ALLOWED_TAGS); ?>
        <div class="js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
            <a id="form-cancel-button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_fieldordering&ff='.$_GET['ff']),"fieldordering")); ?>" ><?php echo __('Cancel', 'js-jobs'); ?></a>
            <?php echo wp_kses(JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('User Field', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
        </div>
    </form>
    <script >
        jQuery(document).ready(function () {
            toggleType(jQuery('#userfieldtype').val());
            toggleSection(jQuery('#section').val());
        });
        function disableAll() {
            jQuery("#divValues").slideUp();
            jQuery(".divColsRows").slideUp();
            jQuery("#divText").slideUp();
        }
        function toggleType(type) {
            disableAll();
            selType(type);
        }
        function toggleSection(val) {
            var ff = jQuery("input#fieldfor").val();
            if (ff == 3) {
                if (val != 1) {
                    jQuery(".divSection").slideUp();
                } else {
                    jQuery(".divSection").slideDown();
                }
            }
        }
        function selType(sType) {
            var elem;
            /*
             text
             checkbox
             date
             combo
             email
             textarea
             radio
             editor
             depandant_field
             multiple*/

            switch (sType) {
                case 'editor':
                    jQuery("#divText").slideUp();
                    jQuery("#divValues").slideUp();
                    jQuery(".divColsRows").slideUp();
                    jQuery("div#for-combo-wrapper").hide();
                    jQuery("div#for-combo-options").hide();
                    jQuery("div#for-combo-options-head").hide();
                    break;
                case 'textarea':
                    jQuery("#divText").slideUp();
                    jQuery(".divColsRows").slideDown();
                    jQuery("#divValues").slideUp();
                    jQuery("div#for-combo-wrapper").hide();
                    jQuery("div#for-combo-options").hide();
                    jQuery("div#for-combo-options-head").hide();
                    break;
                case 'email':
                case 'password':
                case 'text':
                    jQuery("#divText").slideDown();
                    jQuery("div#for-combo-wrapper").hide();
                    jQuery("div#for-combo-options").hide();
                    jQuery("div#for-combo-options-head").hide();
                    break;
                case 'combo':
                case 'multiple':
                    jQuery("#divValues").slideDown();
                    jQuery("div#for-combo-wrapper").hide();
                    jQuery("div#for-combo-options").hide();
                    jQuery("div#for-combo-options-head").hide();
                    break;
                case 'depandant_field':
                    comboOfFields();
                    break;
                case 'radio':
                case 'checkbox':
                    //jQuery(".divColsRows").slideDown();
                    jQuery("#divValues").slideDown();
                    jQuery("div#for-combo-wrapper").hide();
                    jQuery("div#for-combo-options").hide();
                    jQuery("div#for-combo-options-head").hide();
                    /*
                     if (elem=getObject('jsNames[0]')) {
                     elem.setAttribute('mosReq',1);
                     }
                     */
                    break;
                case 'delimiter':
                default:
            }
        }

        function comboOfFields() {
            var ff = jQuery("input#fieldfor").val();
            var pf = jQuery("input#fieldname").val();
            jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'fieldordering', task: 'getFieldsForComboByFieldFor', fieldfor: ff, parentfield: pf, wpnoncecheck:common.wp_jm_nonce}, function (data) {
                if (data) {
                    console.log(data);
                    var d = (data);
                    jQuery("div#for-combo").html(d);
                    jQuery("div#for-combo-wrapper").show();
                }
            });
        }

        function getDataOfSelectedField() {
            var field = jQuery("select#parentfield").val();
            jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'fieldordering', task: 'getSectionToFillValues', pfield: field, wpnoncecheck:common.wp_jm_nonce}, function (data) {
                if (data) {
                    console.log(data);
                    var d = (data);
                    jQuery("div#for-combo-options-head").show();
                    jQuery("div#for-combo-options").html(d);
                    jQuery("div#for-combo-options").show();
                }
            });
        }

        function getNextField(divid,object) {
            var textvar = divid + '[]';
            var fieldhtml = "<span class='input-field-wrapper' ><input type='text' name='" + textvar + "' class='inputbox one user-field'  /><img class='input-field-remove-img' src='<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/remove.png' /></span>";
            jQuery(object).before(fieldhtml);
        }

        function getObject(obj) {
            var strObj;
            if (document.all) {
                strObj = document.all.item(obj);
            } else if (document.getElementById) {
                strObj = document.getElementById(obj);
            }
            return strObj;
        }

        function insertNewRow() {
            var fieldhtml = '<span class="input-field-wrapper" ><input name="values[]" id="values[]" value="" class="inputbox one user-field" type="text" /><img class="input-field-remove-img" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/remove.png" /></span>';
            jQuery("#user-field-val-button").before(fieldhtml);
        }
        jQuery(document).ready(function () {
            jQuery("body").delegate("img.input-field-remove-img", "click", function () {
                jQuery(this).parent().remove();
            });
        });

    </script>
</div>
</div>
