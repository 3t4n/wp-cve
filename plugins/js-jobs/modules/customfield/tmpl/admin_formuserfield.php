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
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs_fieldordering'),"fieldordering")); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php
        $heading = isset(jsjobs::$_data[0]['fieldvalues']) ? __('Edit', 'js-jobs') : __('Add', 'js-jobs');
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

    if (jsjobs::$_data[0]['fieldfor'] == 3) {
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
    } else {
        $fieldtypes = array(
            (object) array('id' => 'text', 'text' => __('Text Field', 'js-jobs')),
            (object) array('id' => 'checkbox', 'text' => __('Check Box', 'js-jobs')),
            (object) array('id' => 'date', 'text' => __('Date', 'js-jobs')),
            (object) array('id' => 'combo', 'text' => __('Drop Down', 'js-jobs')),
            (object) array('id' => 'email', 'text' => __('Email Address', 'js-jobs')),
            (object) array('id' => 'textarea', 'text' => __('Text Area', 'js-jobs')),
            (object) array('id' => 'radio', 'text' => __('Radio Button', 'js-jobs')),
            (object) array('id' => 'editor', 'text' => __('Text Editor', 'js-jobs')),
            (object) array('id' => 'depandant_field', 'text' => __('Dependent Field', 'js-jobs')),
            (object) array('id' => 'multiple', 'text' => __('Multi Select', 'js-jobs')));
    }
    ?>
    <form id="jsjobs-form" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=jsjobs_fieldordering&task=saveuserfield"),"save-fieldordering")); ?>">
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Field Type', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::select('userfieldtype', $fieldtypes, isset(jsjobs::$_data[0]['userfield']->userfieldtype) ? jsjobs::$_data[0]['userfield']->userfieldtype : 'text', '', array('class' => 'inputbox one', 'data-validation' => 'required', 'onchange' => 'toggleType(this.options[this.selectedIndex].value);')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin" id="for-combo-wrapper" style="display:none;">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Select','js-jobs') .' '. __('Parent Field', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding" id="for-combo"></div>    	
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Field Name', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::text('field', isset(jsjobs::$_data[0]['userfield']->field) ? jsjobs::$_data[0]['userfield']->field : '', array('class' => 'inputbox one', 'data-validation' => 'required', 'onchange' => 'prep4SQL(this);')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Field Title', 'js-jobs'); ?><font class="required-notifier">*</font></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::text('fieldtitle', isset(jsjobs::$_data[0]['userfield']->fieldtitle) ? jsjobs::$_data[0]['userfield']->fieldtitle : '', array('class' => 'inputbox one', 'data-validation' => 'required', 'maxlength' => '50')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Show on listing', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::select('showonlisting', $yesno, isset(jsjobs::$_data[0]['userfield']->showonlisting) ? jsjobs::$_data[0]['userfield']->showonlisting : 0, '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <?php if (jsjobs::$_data[0]['fieldfor'] == 3) { ?>
            <div class="js-field-wrapper js-row no-margin">
                <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Resume Section', 'js-jobs'); ?><font class="required-notifier">*</font></div>
                <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::select('section', $sectionarray, isset(jsjobs::$_data[0]['userfield']->section) ? jsjobs::$_data[0]['userfield']->section : '', '', array('class' => 'inputbox one', 'data-validation' => 'required')), JSJOBS_ALLOWED_TAGS); ?></div>
            </div>
        <?php } ?>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Read Only', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::select('readonly', $yesno, isset(jsjobs::$_data[0]['userfield']->readonly) ? jsjobs::$_data[0]['userfield']->readonly : 0, '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('User Published', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::select('published', $yesno, isset(jsjobs::$_data[0]['userfield']->published) ? jsjobs::$_data[0]['userfield']->published : 1, '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Visitor Published', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::select('isvisitorpublished', $yesno, isset(jsjobs::$_data[0]['userfield']->isvisitorpublished) ? jsjobs::$_data[0]['userfield']->isvisitorpublished : 1, '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('User Search', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::select('Search_user', $yesno, isset(jsjobs::$_data[0]['userfield']->search_user) ? jsjobs::$_data[0]['userfield']->search_user : 1, '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Visitor Search', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::select('search_visitor', $yesno, isset(jsjobs::$_data[0]['userfield']->search_visitor) ? jsjobs::$_data[0]['userfield']->search_visitor : 1, '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Required', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::select('required', $yesno, isset(jsjobs::$_data[0]['userfield']->required) ? jsjobs::$_data[0]['userfield']->required : 0, '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Field Size', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::text('size', isset(jsjobs::$_data[0]['userfield']->size) ? jsjobs::$_data[0]['userfield']->size : '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>

        <div class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Java Script', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::textarea('j_script', isset(jsjobs::$_data[0]['userfield']->j_script) ? jsjobs::$_data[0]['userfield']->j_script : '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>


        <div class="js-field-wrapper js-row no-margin" id="for-combo-options" style="display:none;"></div>

        <div id="divText" class="js-field-wrapper js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Max Length', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::text('maxlength', isset(jsjobs::$_data[0]['userfield']->maxlength) ? jsjobs::$_data[0]['userfield']->maxlength : '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper divColsRows js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Columns', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::text('cols', isset(jsjobs::$_data[0]['userfield']->cols) ? jsjobs::$_data[0]['userfield']->cols : '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div class="js-field-wrapper divColsRows js-row no-margin">
            <div class="js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Rows', 'js-jobs'); ?></div>
            <div class="js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo wp_kses(JSJOBSformfield::text('rows', isset(jsjobs::$_data[0]['userfield']->rows) ? jsjobs::$_data[0]['userfield']->rows : '', array('class' => 'inputbox one')), JSJOBS_ALLOWED_TAGS); ?></div>
        </div>
        <div id="divValues" class="js-field-wrapper divColsRows js-row no-margin">
            <span class="js-admin-title"><?php echo __('Use The Table Below To Add New Values', 'js-jobs'); ?></span>
            <div class="page-actions js-row no-margin">
                <div class="js-col-lg-8 js-col-md-8 no-padding">
                    <span class="sample-text"><?php echo __('Sample Text', 'js-jobs'); ?></span>
                </div>
                <div class="add-action js-col-lg-4 js-col-md-4 no-padding">
                    <a class="js-button-link button" href="javascript:void(0);" onclick="insertRow();"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/add_icon.png" /><?php echo __('Add Value', 'js-jobs') ?></a>
                </div>
            </div>
            <table id="js-table">
                <thead>
                    <tr>
                        <th class="centered"><?php echo __('Title', 'js-jobs'); ?></th>
                        <th class="centered"><?php echo __('Value', 'js-jobs'); ?></th>
                        <th class="action"><?php echo __('Action', 'js-jobs'); ?></th>
                    </tr>
                </thead>
                <tbody id="fieldValuesBody">
                    <?php
                    $i = 0;
                    if (isset(jsjobs::$_data[0]['userfield']->type) && jsjobs::$_data[0]['userfield']->type == 'select') {
                        foreach (jsjobs::$_data[0]['fieldvalues'] as $value) {
                            ?>
                            <tr id="jsjobs_trcust<?php echo esc_attr($i); ?>">
                        <input type="hidden" value="<?php echo esc_attr($value->id); ?>" name="jsIds[<?php echo esc_attr($i); ?>]" />
                        <td><input type="text" value="<?php echo esc_attr($value->fieldtitle); ?>" name="jsNames[<?php echo esc_attr($i); ?>]" /></td>
                        <td><input type="text" value="<?php echo esc_attr($value->fieldvalue); ?>" name="jsValues[<?php echo esc_attr($i); ?>]" /></td>
                        <td width="10%"><a href="javascript:void(0);" data-rowid="jsjobs_trcust<?php echo esc_attr($i); ?>" data-optionid="<?php echo esc_attr($value->id); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/remove.png"></a></td>
                        </tr>
                        <?php
                        $i++;
                    }
                    $i--; //for value to store correctly
                } else {
                    ?>
                    <tr id="jsjobs_trcust0">
                        <td><input type="text" value="" name="jsNames[0]" /></td>
                        <td><input type="text" value="" name="jsValues[0]" /></td>
                        <td width="10%"><a data-rowid="jsjobs_trcust0" href="javascript:void(0);"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/remove.png"></a></td>
                    </tr>
                <?php } ?>		
                </tbody>
            </table>
        </div>
        <?php echo wp_kses(JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]['userfield']->id) ? jsjobs::$_data[0]['userfield']->id : ''), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('valueCount', $i), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('fieldfor', jsjobs::$_data[0]['fieldfor']), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('ordering', isset(jsjobs::$_data[0]['userfield']->ordering) ? jsjobs::$_data[0]['userfield']->ordering : '' ), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('action', 'customfield_saveuserfield'), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('isuserfield', 1), JSJOBS_ALLOWED_TAGS); ?>
        <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
        <div class="js-submit-container js-col-lg-8 js-col-md-8 js-col-md-offset-2 js-col-md-offset-2">
            <?php echo wp_kses(JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('User Field', 'js-jobs'), array('class' => 'button')), JSJOBS_ALLOWED_TAGS); ?>
        </div>
    </form>
    <script >
        jQuery(document).ready(function () {
            toggleType(jQuery('#userfieldtype').val());
        });
        function disableAll() {
            jQuery("#divValues").slideUp();
            jQuery(".divColsRows").slideUp();
            jQuery("#divText").slideUp();
        }
        function toggleType(type) {
            disableAll();
            prep4SQL(document.forms['jsjobs-form'].elements['field']);
            selType(type);
        }
        function prep4SQL(field) {
            if (field.value != '') {
                field.value = field.value.replace('js_', '');
                field.value = 'js_' + field.value.replace(/[^a-zA-Z]+/g, '');
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
                    break;
                case 'textarea':
                    jQuery("#divText").slideUp();
                    jQuery(".divColsRows").slideDown();
                    jQuery("#divValues").slideUp();
                    jQuery("div#for-combo-wrapper").hide();
                    jQuery("div#for-combo-options").hide();
                    break;
                case 'email':
                case 'password':
                case 'text':
                    jQuery("#divText").slideDown();
                    jQuery("div#for-combo-wrapper").hide();
                    jQuery("div#for-combo-options").hide();
                    break;
                case 'combo':
                case 'multiple':
                    jQuery("#divValues").slideDown();
                    jQuery("div#for-combo-wrapper").hide();
                    jQuery("div#for-combo-options").hide();
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
            jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'fieldordering', task: 'getFieldsForComboByFieldFor', fieldfor: ff, wpnoncecheck:common.wp_jm_nonce}, function (data) {
                if (data) {
                    console.log(data);
                    var d = jQuery.parseJSON(data);
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
                    var d = jQuery.parseJSON(data);
                    jQuery("div#for-combo-options").html(d);
                    jQuery("div#for-combo-options").show();
                }
            });
        }

        function getNextField(divid) {
            var textvar = divid + '[]';
            var fieldhtml = "<input type='text' name='" + textvar + "' />";
            jQuery("div#" + divid).append(fieldhtml);
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
        function insertRow() {
            var oTable = getObject("fieldValuesBody");
            var oRow, oCell, oCellCont, oInput, oSpan;
            var i, j;
            i = document.forms['jsjobs-form'].elements['valueCount'].value;
            i++;
            // Create and insert rows and cells into the first body.
            oRow = document.createElement("TR");
            jQuery(oRow).attr('id', "jsjob_trcust" + i);
            oTable.appendChild(oRow);

            oCell = document.createElement("TD");
            oInput = document.createElement("INPUT");
            oInput.name = "jsNames[" + i + "]";
            oInput.setAttribute('id', "jsNames_" + i);
            oCell.appendChild(oInput);
            oRow.appendChild(oCell);

            oCell = document.createElement("TD");
            oInput = document.createElement("INPUT");
            oInput.name = "jsValues[" + i + "]";
            oInput.setAttribute('id', "jsValues_" + i);
            oCell.appendChild(oInput);
            oRow.appendChild(oCell);

            oCell = document.createElement("TD");
            oCell.setAttribute('width', "10%");
            oAction = document.createElement("A");
            oAction.setAttribute('href', "javascript:void(0);");
            oImg = document.createElement("IMG");
            oImg.setAttribute('src', "<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/remove.png");
            oAction.appendChild(oImg);
            oCell.appendChild(oAction);
            jQuery(oAction).click(function () {
                jQuery('#jsjob_trcust' + i).remove();
                document.forms['jsjobs-form'].elements['valueCount'].value = document.forms['jsjobs-form'].elements['valueCount'].value - 1;
            });
            oRow.appendChild(oCell);

            document.forms['jsjobs-form'].elements['valueCount'].value = i;
        }
    </script>
</div>
</div>
