<?php
if (!defined('ABSPATH'))
    exit;
$result = '';
global $wpdb;
$zohocrmformname = 'crmformswpbuilder';
$cform_module = sanitize_text_field($_REQUEST['third_module']);
$cform_form_name = sanitize_text_field($_REQUEST['form_title']);
$cform_layout_name = sanitize_text_field($_REQUEST['layoutname']);
$cform_pluginname = sanitize_text_field($_REQUEST['third_plugin']);
$shortcode = $zohocrmformname . "_zcf_contact" . $cform_form_name;
$config = get_option($shortcode);
$contact_config = $config['fields'];
require_once( ZCF_BASE_DIR_URI . 'includes/crmcontactformfieldsmapping.php' );
$mapping_ui_fields = new zcfcontactformfieldmapping();
$assigned_to_user = $mapping_ui_fields->zcfget_user_assignedto($shortcode);
$layoutname = isset($config['layoutname']);
 $layoutId = isset($config['layoutId']);
$contact_config = $config['fields'];
$cform_fieldmapping = '';
$cform_fieldmapping .= "<div></div>";
$cform_fieldmapping .= "<div><div class='form-group col-md-12'> <div class='assign_leads exist_mapping col-md-6'> <label id='innertext' class='leads-builder-label' >";

if ($cform_module == "Leads") {
    $cform_fieldmapping .= "Lead Owner";
    $cform_fieldmapping .= "</label></div><div class='exist_mapping col-md-4'> $assigned_to_user</div></div><div class='form-group col-md-12'><div col-md-6>";
} else if ($cform_module == "Contacts") {
    $cform_fieldmapping .= "Contact Owner";
    $cform_fieldmapping .= "</div><div class='exist_mapping col-md-4'> $assigned_to_user</div>";
}
$cform_fieldmapping .= "</label></div></div>";


if ($cform_form_name != 'None') {
    $get_json_array = $wpdb->get_results($wpdb->prepare("select ID,post_content from $wpdb->posts where ID=%d", $cform_form_name));
    $contact_post_content = $get_json_array[0]->post_content;
    $fields = $mapping_ui_fields->zcfgetTBetBrackets($contact_post_content);
    $i = 0;
    foreach ($fields as $cfkey => $cfval) {
        if (preg_match('/\s/', $cfval)) {
            $final_arr = explode(' ', $cfval);
            $contact_form_labels[$i] = rtrim($final_arr[1], ']');
            $i++;
        }
    }
    $crmformsfieldDataObj = new zcffieldlistDatamanage();
    $crm_fields = $crmformsfieldDataObj->zcffieldsPropsettings($zohocrmformname, $cform_module, $cform_layout_name);
    $j = 1;

    $js_mandatory_fields = array();

    foreach ($crm_fields as $crm_field_key => $crm_fields_vals) {
        $crm_field_labels[$crm_fields_vals->field_name] = $crm_fields_vals->field_label;
        if ($crm_fields_vals->field_mandatory == 1) {
            if (!in_array($crm_fields_vals->field_name, $js_mandatory_fields))
                $js_mandatory_fields[] = $crm_fields_vals->field_name;
        }
        $j++;
    }
    $js_mandatory_array = json_encode($js_mandatory_fields);
}

?>

<div class="mt10 mb20 pR">
    <span id="inneroptions" class="leads-builder-sub-heading mr10"><span class="headerlabel">Thirdparity name:</span> <span class="headerValue"> Contact Form 7</span></span>
    <span id="inneroptions" class="leads-builder-sub-heading mr10"><span class="headerlabel">Module:</span> <span class="headerValue"> <?php echo esc_html($_REQUEST['third_module']); ?></span></span>
    <span id="inneroptions" class="leads-builder-sub-heading mr10"><span class="headerlabel">Layout:</span> <span class="headerValue"><?php echo esc_html($_REQUEST['layoutname']); ?></span></span>
    <?php $modulename = esc_html($_REQUEST['third_module']); ?>
    <span id="inneroptions" class="leads-builder-sub-heading mr10"><a onclick="syncfields('', 'crmformswpbuilder','<?php echo $modulename;?>', '', 'Oncreate', '', '', '', '', '')" class='pR pl20 cP' >Fetch Fields</a></span>

</span>
</div>
<div>
  <div id="loading-image" style="display: none;"></div>

    <div style="width:98%;">
        <div >
            <div>

                <div class="form-group row mb20">
                    <div class="col-md-12 ml20">
                        <input type="text" disabled data-value="Unititled" id="form-name" name="form-name" class="textField" value=<?php echo esc_html($_REQUEST['third_module_pluginname']); ?>>
                        <input type="hidden" id="lead_crmtype" name="lead_crmtype" value="crmformswpbuilder">
                        <input type="hidden" id="savefields" name="savefields" value="Apply">
                    </div>
                </div>

                <div>

                    <input type="hidden" name="field-form-hidden" value="field-form">
                    <div style="max-height:500px;overflow:auto;">
                        <form>
                            <table class="new_table  mb0 " cellpadding="0" cellspacing="0"     width="700px">
                                <thead>
                                    <tr class="crmforms_highlight crmforms_alt table-heading">
                                        <th  style="text-align:right" class="pR">Contact Form 7 <span class="arrow-icon"></span></th>
                                        <th> Zoho CRM Fields</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $j = 1;
                                    foreach ($contact_form_labels as $cont_id => $cont_label) {
                                        $thirdpartyfield = "thirdpartyfield_" . $j;
                                        $crmfieldlabel = 'crm_fields_' . $j;
                                        
                                        ?>
                                        <tr class="crmforms_highlight">
                                            <td  align="right" class="border-topTrans"><label class='leads-builder-label'> <?php echo esc_html($cont_label); ?></label></div>
                                                <input type='hidden' name=<?php echo esc_html($thirdpartyfield); ?> id=<?php echo esc_html($thirdpartyfield); ?> value=<?php echo esc_html($cont_label); ?> /></td>

                                            <td class="border-topTrans">
                                                <select class="form-control" name=<?php echo esc_html($crmfieldlabel); ?> id=<?php echo esc_html($crmfieldlabel); ?> tabindex="-1" aria-hidden="true">
                                                    <option value='None'>None</option>
                                                    <?php
                                                    $crm_field_options = '';

                                                    foreach ($crm_field_labels as $field_key => $crm_field_label) { // Prepare crm fileds drop down
                                                        $crm_field_options .= "<option value='{$field_key}'";
                                                        if (isset($contact_config)) {

                                                            foreach ($contact_config as $config_key => $config_val) { // configuration

                                                                if ($cont_label == $config_key && $field_key == $config_val) { //match label and fieldname
                                                                    $crm_field_options .= "selected=selected"; //select when the configuration exist
                                                                }
                                                            }
                                                        }
                                                        $crm_field_options .= "> $crm_field_label</option>";
                                                    }
                                                    $allowedposttags = zcf_allowed_tag();
                                                    printf($crm_field_options);
                                                    ?>

                                            </td>
                                        </tr>
                                        <?php
                                        $j++;
                                    }
                                    ?>

                                </tbody>
                            </table>
                            <input type='hidden' value=<?php echo esc_html($j); ?> id='total_field_count'>
                            <input type='hidden' value=<?php echo esc_html($cform_module); ?> id='module'>
                            <input type='hidden' value=<?php echo esc_html($zohocrmformname); ?> id='active_crm'>
                            <input type='hidden' value=<?php echo esc_html($cform_form_name); ?> id='form_name'>
                            <input type='hidden' value=<?php echo esc_html($cform_pluginname); ?> id='thirdparty_plugin'>
                            <input type='hidden' value=<?php echo esc_html($js_mandatory_array); ?> id='crm_mandatory_fields'>
                            <input type="hidden" name="modulename" id="modulename"  value="<?php echo esc_html($_REQUEST['third_module']); ?>">
                            <input type="hidden" name="layoutname" id="layoutname"  value="<?php echo esc_html($_REQUEST['layoutname']); ?>">
                            <?php
                                 $query_layout = "select layoutID from zcf_zohocrm_moduleLists where modulename='".$cform_module."' and Layoutname='".sanitize_text_field($_REQUEST['layoutname'])."'";
                                $get_layoutjson_array = $wpdb->get_results($query_layout);
                                $layoutIDarray = $get_layoutjson_array[0]->layoutID;
                            ?>
                            <input type="hidden" name="layoutId" id="layoutId" value=<?php echo esc_html($layoutIDarray); ?>>

                            </div>
                            <div class="mt20">
                                <input type="button" name="thirdparty-field-mapping-cancel" value="Cancel" class="newgraybtn" onclick="removemapppingcontents()">
                                <input type="button" name="thirdparty-field-mapping-save" value="Save" class="primarybtn" onclick="mapping_crmforms_fields()">
                            </div>
                        </form>
                        <!-- </div> -->
                    </div>
                </div>
            </div>

            <div id="loading-image" style="display: none; "></div>
        </div>
        <div class="freezelayer"></div>

        <div class="clear"></div>
    </div>
