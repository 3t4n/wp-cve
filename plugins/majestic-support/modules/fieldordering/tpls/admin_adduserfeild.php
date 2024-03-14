<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$majesticsupport_js ="
    ajaxurl = '". esc_url(admin_url('admin-ajax.php'))."';
    jQuery(document).ready(function ($) {
        $.validate();
    });
    function getChildForVisibleCombobox(val) {
        jQuery.post(ajaxurl, {action: 'mjsupport_ajax', val: val, mjsmod: 'fieldordering', task: 'getChildForVisibleCombobox', '_wpnonce':'". esc_attr(wp_create_nonce("get-child-for-visible-combobox"))."'}, function (data) {
            if (data != false) {
                jQuery('#visibleValue').html(MJTC_msDecodeHTML(data));
            }else{
                jQuery('#visibleValue').html(\"<div class='premade-no-rec'>". esc_html(__('No response found','majestic-support'))."</div>\");
            }
        });//jquery closed
    }

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('adduserfield'); ?>
        <?php
        $yesno = array(
            (object) array('id' => 1, 'text' => esc_html(__('Yes', 'majestic-support'))),
            (object) array('id' => 0, 'text' => esc_html(__('No', 'majestic-support'))));
        $equalnotequal = array(
            (object) array('id' => 1, 'text' => esc_html(__('Equal', 'majestic-support'))),
            (object) array('id' => 0, 'text' => esc_html(__('Not Equal', 'majestic-support'))));
        if(isset(majesticsupport::$_data[0]['userfield']->userfieldtype) && majesticsupport::$_data[0]['userfield']->userfieldtype != 'depandant_field'){
            $fieldtypes = array(
                (object) array('id' => 'text', 'text' => esc_html(__('Text Field', 'majestic-support'))),
                (object) array('id' => 'checkbox', 'text' => esc_html(__('Check Box', 'majestic-support'))),
                (object) array('id' => 'date', 'text' => esc_html(__('Date', 'majestic-support'))),
                (object) array('id' => 'combo', 'text' => esc_html(__('Drop Down', 'majestic-support'))),
                (object) array('id' => 'email', 'text' => esc_html(__('Email Address', 'majestic-support'))),
                (object) array('id' => 'textarea', 'text' => esc_html(__('Text Area', 'majestic-support'))),
                (object) array('id' => 'radio', 'text' => esc_html(__('Radio Button', 'majestic-support'))),
                (object) array('id' => 'file', 'text' => esc_html(__('Upload File', 'majestic-support'))),
                (object) array('id' => 'multiple', 'text' => esc_html(__('Multi Select', 'majestic-support'))),
                (object) array('id' => 'admin_only', 'text' => esc_html(__('Admin Only', 'majestic-support'))),
                (object) array('id' => 'termsandconditions', 'text' => esc_html(__('Terms and Conditions', 'majestic-support'))));
        }else{
            $fieldtypes = array(
                (object) array('id' => 'text', 'text' => esc_html(__('Text Field', 'majestic-support'))),
                (object) array('id' => 'checkbox', 'text' => esc_html(__('Check Box', 'majestic-support'))),
                (object) array('id' => 'date', 'text' => esc_html(__('Date', 'majestic-support'))),
                (object) array('id' => 'combo', 'text' => esc_html(__('Drop Down', 'majestic-support'))),
                (object) array('id' => 'email', 'text' => esc_html(__('Email Address', 'majestic-support'))),
                (object) array('id' => 'textarea', 'text' => esc_html(__('Text Area', 'majestic-support'))),
                (object) array('id' => 'radio', 'text' => esc_html(__('Radio Button', 'majestic-support'))),
                (object) array('id' => 'depandant_field', 'text' => esc_html(__('Dependent Field', 'majestic-support'))),
                (object) array('id' => 'file', 'text' => esc_html(__('Upload File', 'majestic-support'))),
                (object) array('id' => 'multiple', 'text' => esc_html(__('Multi Select', 'majestic-support'))),
                (object) array('id' => 'admin_only', 'text' => esc_html(__('Admin Only', 'majestic-support'))),
                (object) array('id' => 'termsandconditions', 'text' => esc_html(__('Terms and Conditions', 'majestic-support'))));
        }
        $fieldsize = array(
             (object) array('id' => 50, 'text' => esc_html(__('50%', 'majestic-support'))),
            (object) array('id' => 100, 'text' => esc_html(__('100%', 'majestic-support'))));
        ?>
        <div id="msadmin-data-wrp">
            <?php if(isset(majesticsupport::$_data['formid'])){ $mformid = majesticsupport::$_data['formid']; }else{ $mformid = MJTC_includer::MJTC_getModel('ticket')->getDefaultMultiFormId(); } ?>
            <form class="msadmin-form" id="adminForm" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_fieldordering&task=saveuserfeild&formid=$mformid"),"save-userfeild")); ?>">
                <div class="mjtc-form-wrapper">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Field Type', 'majestic-support')); ?><font class="required-notifier">*</font></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_select('userfieldtype', $fieldtypes, isset(majesticsupport::$_data[0]['userfield']->userfieldtype) ? majesticsupport::$_data[0]['userfield']->userfieldtype : 'text', '', array('class' => 'inputbox one mjtc-form-select-field', 'data-validation' => 'required', 'onchange' => 'toggleType(this.options[this.selectedIndex].value);')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <div class="mjtc-form-wrapper">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Field Title', 'majestic-support')); ?><font class="required-notifier">*</font></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_text('fieldtitle', isset(majesticsupport::$_data[0]['userfield']->fieldtitle) ? majesticsupport::$_data[0]['userfield']->fieldtitle : '', array('class' => 'inputbox one mjtc-form-input-field', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <div class="mjtc-form-wrapper for-terms-condtions-hide" id="for-combo-wrapper" style="display:none;">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Select','majestic-support')) .esc_html('&nbsp;'). esc_html(__('Parent Field', 'majestic-support')); ?><font class="required-notifier">*</font></div>
                    <div class="mjtc-form-value" id="for-combo"></div>
                </div>
                <div class="mjtc-form-wrapper for-terms-condtions-hide">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Show On Listing', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_select('showonlisting', $yesno, isset(majesticsupport::$_data[0]['userfield']->showonlisting) ? majesticsupport::$_data[0]['userfield']->showonlisting : 0, '', array('class' => 'inputbox one mjtc-form-select-field')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <div class="mjtc-form-wrapper for-terms-condtions-hide">
                    <div class="mjtc-form-title"><?php echo esc_html(__('User Published', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_select('published', $yesno, isset(majesticsupport::$_data[0]['userfield']->published) ? majesticsupport::$_data[0]['userfield']->published : 1, '', array('class' => 'inputbox one mjtc-form-select-field')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <div class="mjtc-form-wrapper for-terms-condtions-hide for-admin-only-hide">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Visitor Published', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_select('isvisitorpublished', $yesno, isset(majesticsupport::$_data[0]['userfield']->isvisitorpublished) ? majesticsupport::$_data[0]['userfield']->isvisitorpublished : 1, '', array('class' => 'inputbox one mjtc-form-select-field')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <div class="mjtc-form-wrapper for-terms-condtions-hide">
                    <div class="mjtc-form-title"><?php echo esc_html(__('User Search', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_select('search_user', $yesno, isset(majesticsupport::$_data[0]['userfield']->search_user) ? majesticsupport::$_data[0]['userfield']->search_user : 1, '', array('class' => 'inputbox one mjtc-form-select-field')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <div class="mjtc-form-wrapper for-terms-condtions-hide">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Required', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_select('required', $yesno, isset(majesticsupport::$_data[0]['userfield']->required) ? majesticsupport::$_data[0]['userfield']->required : 0, '', array('class' => 'inputbox one mjtc-form-select-field')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <div class="mjtc-form-wrapper for-terms-condtions-hide">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Field Size', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_select('size', $fieldsize, isset(majesticsupport::$_data[0]['userfield']->size) ? majesticsupport::$_data[0]['userfield']->size : 0, '', array('class' => 'inputbox one mjtc-form-select-field')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <div id="for-combo-options" >
                    <?php
                    $arraynames = '';
                    $comma = '';
                    if (isset(majesticsupport::$_data[0]['userfieldparams']) && majesticsupport::$_data[0]['userfield']->userfieldtype == 'depandant_field') {
                        foreach (majesticsupport::$_data[0]['userfieldparams'] as $key => $val) {
                            $textvar = $key;
                            if($textvar != ''){
                                $textvar = MJTC_majesticsupportphplib::MJTC_str_replace(' ','__',$textvar);
                                $textvar = MJTC_majesticsupportphplib::MJTC_str_replace('.','___',$textvar);
                            }
                            $divid = $textvar;
                            $textvar .='[]';
                            $arraynames .= $comma . "$key";
                            $comma = ',';
                            ?>
                            <div class="ms-user-dd-field-wrap">
                                <div class="ms-user-dd-field-title">
                                    <?php echo esc_html($key); ?>
                                </div>
                                <div class="ms-user-dd-field-value combo-options-fields" id="<?php echo esc_attr($divid); ?>">
                                    <?php
                                    if (!empty($val)) {
                                        foreach ($val as $each) {
                                            ?>
                                            <span class="input-field-wrapper">
                                                <input name="<?php echo esc_attr($textvar); ?>" id="<?php echo esc_attr($textvar); ?>" value="<?php echo esc_attr($each); ?>" class="inputbox one user-field" type="text">
                                                <img alt="<?php echo esc_html(__('Delete', 'majestic-support')) ?>" class="input-field-remove-img" src="<?php echo esc_url(MJTC_PLUGIN_URL) ?>includes/images/delete.png">
                                            </span><?php
                                        }
                                    }
                                    ?>
                                    <input id="depandant-field-button" class="ms-button-link button user-field-val-button" onclick="getNextField( &quot;<?php echo esc_js($divid); ?>&quot;, this );" value="Add More" type="button">
                                </div>
                            </div><?php
                        }
                    }
                    ?>
                </div>

                <div id="divText" class="mjtc-form-wrapper">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Max Length', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_text('maxlength', isset(majesticsupport::$_data[0]['userfield']->maxlength) ? majesticsupport::$_data[0]['userfield']->maxlength : '', array('class' => 'inputbox one mjtc-form-input-field')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <div class="mjtc-form-wrapper divColsRows">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Columns', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_text('cols', isset(majesticsupport::$_data[0]['userfield']->cols) ? majesticsupport::$_data[0]['userfield']->cols : '', array('class' => 'inputbox one mjtc-form-input-field')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <div class="mjtc-form-wrapper divColsRows">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Rows', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_text('rows', isset(majesticsupport::$_data[0]['userfield']->rows) ? majesticsupport::$_data[0]['userfield']->rows : '', array('class' => 'inputbox one mjtc-form-input-field')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <?php if (majesticsupport::$_data['fieldfor'] == 1) { ?>
                    <div class="mjtc-form-wrapper mjtc-form-visible-wrapper">
                        <div class="mjtc-form-title"><?php echo esc_html(__('Visible', 'majestic-support')); ?></div>
                        <div class="mjtc-form-value">
                            <?php echo wp_kses(MJTC_formfield::MJTC_select('visibleParent', MJTC_includer::MJTC_getModel('fieldordering')->getFieldsForVisibleCombobox(majesticsupport::$_data['fieldfor'], $mformid,isset(majesticsupport::$_data[0]['userfield']->field) ? majesticsupport::$_data[0]['userfield']->field : '',isset(majesticsupport::$_data[0]['userfield']->id) ? majesticsupport::$_data[0]['userfield']->id : ''), isset(majesticsupport::$_data[0]['visibleparams']['visibleParent']) ? majesticsupport::$_data[0]['visibleparams']['visibleParent'] : '', esc_html(__('Select Parent', 'majestic-support')), array('class' => 'inputbox mjtc-form-select-field mjtc-form-input-field-visible', 'onchange' => 'getChildForVisibleCombobox(this.value);')), MJTC_ALLOWED_TAGS); ?>
                            <span id="visibleValue">
                                <?php echo wp_kses(MJTC_formfield::MJTC_select('visibleValue', isset(majesticsupport::$_data[0]['visibleValue']) ? majesticsupport::$_data[0]['visibleValue'] : '', isset(majesticsupport::$_data[0]['visibleparams']['visibleValue']) ? majesticsupport::$_data[0]['visibleparams']['visibleValue'] : '', esc_html(__('Select Child', 'majestic-support')), array('class' => 'inputbox one mjtc-form-select-field mjtc-form-input-field-visible')), MJTC_ALLOWED_TAGS); ?>
                            </span>
                            <?php echo wp_kses(MJTC_formfield::MJTC_select('visibleCondition', $equalnotequal, isset(majesticsupport::$_data[0]['visibleparams']['visibleCondition']) ? majesticsupport::$_data[0]['visibleparams']['visibleCondition'] : '2', esc_html(__('Select Condition', 'majestic-support')), array('class' => 'inputbox one mjtc-form-select-field mjtc-form-input-field-visible')), MJTC_ALLOWED_TAGS); ?>
                        </div>
                        <div class="mjtc-form-desc">
                            <?php echo esc_html(__('To use this feature, please fill in the above three fields.', 'majestic-support')); ?>
                        </div>
                    </div>
                <?php } ?>
                <div id="divValues" class="msadmin-add-user-fields-wrp divColsRowsno-margin">
                    <h3 class="msadmin-add-user-fields-title"><?php echo esc_html(__('Use the table below to add new values', 'majestic-support')); ?></h3>
                    <div class="page-actions no-margin">
                        <div id="user-field-values" class="white-background" class="no-padding">
                            <?php
                            if (isset(majesticsupport::$_data[0]['userfield']) && majesticsupport::$_data[0]['userfield']->userfieldtype != 'depandant_field') {
                                if (isset(majesticsupport::$_data[0]['userfieldparams']) && !empty(majesticsupport::$_data[0]['userfieldparams'])) {
                                    foreach (majesticsupport::$_data[0]['userfieldparams'] as $key => $val) {
                                        ?>
                                        <span class="input-field-wrapper">
                                            <?php echo wp_kses(MJTC_formfield::MJTC_text('values['.esc_attr($val).']', isset($val) ? $val : '', array('class' => 'inputbox one user-field')), MJTC_ALLOWED_TAGS); ?>
                                            <img alt="<?php echo esc_html(__('Delete', 'majestic-support')) ?>" class="input-field-remove-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/delete.png" />
                                        </span>
                                    <?php
                                    }
                                } else {
                                    $val_new = isset($val) ? $val : ''; ?>
                                    <span class="input-field-wrapper">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_text('values['.esc_attr($val_new).']', $val_new, array('class' => 'inputbox one user-field')), MJTC_ALLOWED_TAGS); ?>
                                        <img alt="<?php echo esc_html(__('Delete', 'majestic-support')) ?>" class="input-field-remove-img" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/delete.png" />
                                    </span>
                                <?php
                                }
                            }
                            ?>
                            <a title="<?php echo esc_attr(__('Add Value', 'majestic-support')) ?>" class="ms-button-link button user-field-val-button" id="user-field-val-button" onclick="insertNewRow();"><?php echo esc_html(__('Add Value', 'majestic-support')) ?></a>
                        </div>
                    </div>
                </div>
                <div class="for-terms-condtions-show" >
                    <?php
                    $termsandconditions_text = '';
                    $termsandconditions_linktype = '';
                    $termsandconditions_link = '';
                    $termsandconditions_page = '';
                    if( isset(majesticsupport::$_data[0]['userfieldparams']) && majesticsupport::$_data[0]['userfieldparams'] != '' && is_array(majesticsupport::$_data[0]['userfieldparams']) && !empty(majesticsupport::$_data[0]['userfieldparams'])){
                        $termsandconditions_text = isset(majesticsupport::$_data[0]['userfieldparams']['termsandconditions_text']) ? majesticsupport::$_data[0]['userfieldparams']['termsandconditions_text'] :'' ;
                        $termsandconditions_linktype = isset(majesticsupport::$_data[0]['userfieldparams']['termsandconditions_linktype']) ? majesticsupport::$_data[0]['userfieldparams']['termsandconditions_linktype'] :'' ;
                        $termsandconditions_link = isset(majesticsupport::$_data[0]['userfieldparams']['termsandconditions_link']) ? majesticsupport::$_data[0]['userfieldparams']['termsandconditions_link'] :'' ;
                        $termsandconditions_page = isset(majesticsupport::$_data[0]['userfieldparams']['termsandconditions_page']) ? majesticsupport::$_data[0]['userfieldparams']['termsandconditions_page'] :'' ;
                    } ?>
                    <div class="mjtc-form-wrapper ">
                        <div class="mjtc-form-title"><?php echo esc_html(__('Terms and Conditions Text', 'majestic-support')); ?></div>
                        <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_text('termsandconditions_text', $termsandconditions_text , array('class' => 'inputbox one mjtc-form-input-field')), MJTC_ALLOWED_TAGS); ?></div>
                        <div class="mjtc-form-desc">
                            <?php echo esc_html(__('e.g "  I have read and agree to the [link] Terms and Conditions[/link].  " The text between [link] and [/link] will be linked to provided url or wordpress page.', 'majestic-support')); ?>
                        </div>
                    </div>
                    <div class="mjtc-form-wrapper ">
                        <div class="mjtc-form-title"><?php echo esc_html(__('Terms and Conditions Link Type', 'majestic-support')); ?></div>
                        <?php
                        $linktype = array(
                            (object) array('id' => 1, 'text' => esc_html(__('Direct Link', 'majestic-support'))),
                            (object) array('id' => 2, 'text' => esc_html(__('Wordpress Page', 'majestic-support'))));
                        ?>
                        <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_select('termsandconditions_linktype', $linktype, $termsandconditions_linktype, esc_html(__('Select Link Type', 'majestic-support')), array('class' => 'inputbox one mjtc-form-select-field')), MJTC_ALLOWED_TAGS); ?></div>
                    </div>
                    <div class="mjtc-form-wrapper for-terms-condtions-linktype1" style="display: none;">
                        <div class="mjtc-form-title"><?php echo esc_html(__('Terms and Conditions Link', 'majestic-support')); ?></div>
                        <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_text('termsandconditions_link', $termsandconditions_link , array('class' => 'inputbox one mjtc-form-input-field')), MJTC_ALLOWED_TAGS); ?></div>
                    </div>
                    <div class="mjtc-form-wrapper for-terms-condtions-linktype2" style="display: none;">
                        <div class="mjtc-form-title"><?php echo esc_html(__('Terms and Conditions Page', 'majestic-support')); ?></div>
                        <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_select('termsandconditions_page', MJTC_includer::MJTC_getModel('configuration')->getPageList(), $termsandconditions_page, esc_html(__('Select Wordpress page','majestic-support')), array('class' => 'inputbox one mjtc-form-select-field')), MJTC_ALLOWED_TAGS); ?></div>
                    </div>
                </div>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('multiformid', $mformid), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('id', isset(majesticsupport::$_data[0]['userfield']->id) ? majesticsupport::$_data[0]['userfield']->id : ''), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('fieldfor', majesticsupport::$_data['fieldfor']), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('ordering', isset(majesticsupport::$_data[0]['userfield']->ordering) ? majesticsupport::$_data[0]['userfield']->ordering : '' ), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('isuserfield', 1), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('fieldname', isset(majesticsupport::$_data[0]['userfield']->field) ? majesticsupport::$_data[0]['userfield']->field : '' ), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('depandant_field', isset(majesticsupport::$_data[0]['userfield']->depandant_field) ? majesticsupport::$_data[0]['userfield']->depandant_field : '' ), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('field', isset(majesticsupport::$_data[0]['userfield']->field) ? majesticsupport::$_data[0]['userfield']->field : '' ), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('arraynames2', $arraynames), MJTC_ALLOWED_TAGS); ?>
                <div class="mjtc-form-button">
                    <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('save', esc_html(__('Save Field', 'majestic-support')), array('class' => 'button mjtc-form-save')), MJTC_ALLOWED_TAGS); ?>
                </div>
            </form>
        </div>
        <?php
        $majesticsupport_js ="
            jQuery(document).ready(function () {
                toggleType(jQuery('select#userfieldtype').val());
                jQuery('#termsandconditions_linktype').on('change', function() {
                    if(this.value == 1){
                        jQuery('.for-terms-condtions-linktype1').slideDown();
                        jQuery('.for-terms-condtions-linktype2').hide();
                    }else{
                        jQuery('.for-terms-condtions-linktype1').hide();
                        jQuery('.for-terms-condtions-linktype2').slideDown();
                    }
                });

                var intial_val = jQuery('#termsandconditions_linktype').val();
                if(intial_val == 1){
                    jQuery('.for-terms-condtions-linktype1').slideDown();
                    jQuery('.for-terms-condtions-linktype2').hide();
                }else{
                    jQuery('.for-terms-condtions-linktype1').hide();
                    jQuery('.for-terms-condtions-linktype2').slideDown();
                }
            });
            function disableAll() {
                jQuery('#divValues').slideUp();
                jQuery('.divColsRows').slideUp();
                jQuery('#divText').slideUp();
            }
            function toggleType(type) {
                disableAll();
                selType(type);
            }
            function prep4SQL(field) {
                if (field.value != '') {
                    field.value = field.value.replace('mjtc_', '');
                    field.value = 'mjtc_' + field.value.replace(/[^a-zA-Z]+/g, '');
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
                        jQuery('div.for-terms-condtions-hide').show();
                        jQuery('#divText').slideUp();
                        jQuery('#divValues').slideUp();
                        jQuery('.divColsRows').slideUp();
                        jQuery('div#for-combo-wrapper').hide();
                        jQuery('div#for-combo-options').hide();
                        jQuery('div#for-combo-options-head').hide();
                        jQuery('div.for-terms-condtions-show').slideUp();
                        break;
                    case 'textarea':
                        jQuery('div.for-terms-condtions-hide').show();
                        jQuery('#divText').slideUp();
                        jQuery('.divColsRows').slideDown();
                        jQuery('#divValues').slideUp();
                        jQuery('div#for-combo-wrapper').hide();
                        jQuery('div#for-combo-options').hide();
                        jQuery('div#for-combo-options-head').hide();
                        jQuery('div.for-terms-condtions-show').slideUp();
                        break;
                    case 'email':
                    case 'password':
                    case 'text':
                    case 'file':
                    case 'date':
                        jQuery('div.for-terms-condtions-hide').show();
                        jQuery('#divText').slideDown();
                        jQuery('div#for-combo-wrapper').hide();
                        jQuery('div#for-combo-options').hide();
                        jQuery('div#for-combo-options-head').hide();
                        jQuery('div.for-terms-condtions-show').slideUp();
                        break;
                    case 'termsandconditions':
                        jQuery('div#for-combo-wrapper').hide();
                        jQuery('div#for-combo-options').hide();
                        jQuery('div#for-combo-options-head').hide();
                        jQuery('#divText').slideUp();
                        jQuery('.divColsRows').slideUp();
                        jQuery('#divValues').slideUp();
                        jQuery('div#for-combo-wrapper').hide();
                        jQuery('div#for-combo-options').hide();
                        jQuery('div#for-combo-options-head').hide();
                        jQuery('div.for-terms-condtions-hide').hide();
                        jQuery('div.for-terms-condtions-show').slideDown();
                        break;
                    case 'combo':
                    case 'multiple':
                        jQuery('div.for-terms-condtions-hide').show();
                        jQuery('#divValues').slideDown();
                        jQuery('div#for-combo-wrapper').hide();
                        jQuery('div#for-combo-options').hide();
                        jQuery('div#for-combo-options-head').hide();
                        jQuery('div.for-terms-condtions-show').slideUp();
                        break;
                    case 'depandant_field':
                        jQuery('div.for-terms-condtions-hide').show();
                        comboOfFields();
                        jQuery('div.for-terms-condtions-show').slideUp();
                        break;
                    case 'radio':
                    case 'checkbox':
                        jQuery('div.for-terms-condtions-hide').show();
                        jQuery('#divValues').slideDown();
                        jQuery('div#for-combo-wrapper').hide();
                        jQuery('div#for-combo-options').hide();
                        jQuery('div#for-combo-options-head').hide();
                        jQuery('div.for-terms-condtions-show').slideUp();
                        break;
                    case 'admin_only':
                        jQuery('div.for-terms-condtions-hide').show();
                        jQuery('#divText').slideDown();
                        jQuery('div#for-combo-wrapper').hide();
                        jQuery('div#for-combo-options').hide();
                        jQuery('div#for-combo-options-head').hide();
                        jQuery('div.for-admin-only-hide').hide();
                        jQuery('div.for-terms-condtions-show').slideUp();
                        break;
                    case 'delimiter':
                    default:
                }
                return;
            }
            function comboOfFields() {
                ajaxurl = '". esc_url(admin_url('admin-ajax.php'))."';
                var ff = jQuery('input#fieldfor').val();
                var pf = jQuery('input#fieldname').val();
                jQuery.post(ajaxurl, {action: 'mjsupport_ajax', mjsmod: 'fieldordering', task: 'getFieldsForComboByFieldFor', fieldfor: ff,parentfield:pf, '_wpnonce':'". esc_attr(wp_create_nonce("get-fields-for-combo-by-fieldfor"))."'}, function (data) {
                    if (data) {
                        console.log(data);
                        var d = jQuery.parseJSON(data);
                        jQuery('div#for-combo').html(MJTC_msDecodeHTML(d));
                        jQuery('div#for-combo-wrapper').show();
                    }
                });
            }
            function getDataOfSelectedField() {
                ajaxurl = '". esc_url(admin_url('admin-ajax.php'))."';
                var field = jQuery('select#parentfield').val();
                jQuery.post(ajaxurl, {action: 'mjsupport_ajax', mjsmod: 'fieldordering', task: 'getSectionToFillValues', pfield: field, '_wpnonce':'". esc_attr(wp_create_nonce("get-section-to-fill-values"))."'}, function (data) {
                    if (data) {
                        var d = jQuery.parseJSON(data);
                        jQuery('div#for-combo-options-head').show();
                        jQuery('div#for-combo-options').html(MJTC_msDecodeHTML(d));
                        jQuery('div#for-combo-options').show();
                    }else{
                        jQuery('div#for-combo-options-head').hide();
                        jQuery('div#for-combo-options').html();
                        jQuery('div#for-combo-options').hide();
                    }
                });
            }
            function getNextField(divid, object) {
                var textvar = divid + '[]';
                var fieldhtml = \"<span class='input-field-wrapper' ><input type='text' name='\" + textvar + \"' class='inputbox one user-field'  /><img alt='". esc_html(__('Delete', 'majestic-support')) ."' class='input-field-remove-img' src='". esc_url(MJTC_PLUGIN_URL)."includes/images/delete.png' /></span>\";
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
                var fieldhtml = '<span class=\"input-field-wrapper\" ><input name=\"values[]\" id=\"values[]\" value=\"\" class=\"inputbox one user-field\" type=\"text\" /><img alt=\"". esc_html(__('Delete', 'majestic-support')) ."\" class=\"input-field-remove-img\" src=\"". esc_url(MJTC_PLUGIN_URL)."includes/images/delete.png\" /></span>';
                jQuery('#user-field-val-button').before(fieldhtml);
            }
            jQuery(document).ready(function () {
                jQuery('body').delegate('img.input-field-remove-img', 'click', function () {
                    jQuery(this).parent().remove();
                });
            });

        ";
        wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
        ?>  
    </div>
</div>
