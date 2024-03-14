<?php
if (!defined('ABSPATH'))
    exit;
$result = '';
global $wpdb, $adminmenulable;
$shortcode = sanitize_text_field($_REQUEST['EditShortcode']);
$module = sanitize_text_field($_REQUEST['module']);
$onAction = sanitize_text_field($_REQUEST['onAction']);

$data = $wpdb->get_results("select *from zcf_zohoshortcode_manager");
if ($result != '') {
    $allowedposttags = zcf_allowed_tag();
    ?>
<div style="font-weight:bold; padding-left:20px; color:red;"><?php echo wp_kses( $result, $allowedposttags );  ?> </div>;
<?php } else {
    $siteurl = site_url();
    $plug_url = ZCF_PLUGIN_BASE_URL;
    $field_form_action = add_query_arg(array('__module' => 'ManageShortcodes', '__action' => 'zcfCrmManageFieldsLists', 'crmtype' => 'crmformswpbuilder', 'module' => sanitize_text_field($_REQUEST['module']), 'EditShortcode' => sanitize_text_field($_REQUEST['EditShortcode']), $plug_url));
    ?>
    <input type="hidden" name="currentpageUrl" id="currentpageUrl" value=""/>

    <form id="field-form" action="<?php echo esc_url("" . site_url() . "/wp-admin/admin.php?page=create-leadform-builder&__module=ManageShortcodes&__action=zcfCrmManageFieldsLists&onAction=" . sanitize_text_field($_REQUEST['onAction']) . "&crmtype=" . sanitize_text_field($_REQUEST['crmtype']) . "&module=" . sanitize_text_field($_REQUEST['module']) . "&EditShortcode=" . $shortcode . ""); ?>" method="post">

        <div class='mt10 mb20 pR' >

            <?php
            global $zohocrmdetails;
            $content = "";
            if ($shortcode !=='') {
                $content .= "<span id='inneroptions' class='leads-builder-sub-heading'>";
                $content .= "<span> ";
                foreach ($zohocrmdetails as $crm_key => $crm_value) {
                    if (sanitize_text_field(isset($_REQUEST['crmtype'])) && ($crm_key == 'crmformswpbuilder')) {
                        $select_option = " {$crm_value['crmname']} ";
                    }
                }
                $content .= $select_option;
                $content .= "</span>";
                $content .= "</span>";
            } else {
                $content .= "<span id='inneroptions'>CRM Type  : <select id='crmtype' name='crmtype' style='margin-left:8px;height:27px;' class=''
onchange = \"ChooseFields('{$siteurl}','{$module}','{zcf_crmfields_shortcodes}', '{onEditShortcode}')\">";
                $select_option = "";
                // $Data->crmtype = "" ;

                $select_option .= "<option> --" . __('Select', ZCF_PLUGIN_BASE_URL) . "-- </option>";
                foreach ($zohocrmdetails as $crm_key => $crm_value) {
                    if (sanitize_text_field(isset($_REQUEST['crmtype'])) && ($crm_key == sanitize_text_field($_REQUEST['crmtype']))) {
                        $select_option .= "<option value='{$crm_key}' selected=selected > {$crm_value['crmname']} </option>";
                    } else {
                        $select_option .= "<option value='{$crm_key}'> {$crm_value['crmname']} </option>";
                    }
                }

                $content .= $select_option;

                $content .= "</select></span>";
                $allowedposttags = zcf_allowed_tag();
                echo wp_kses( $content, $allowedposttags );
            }
            ?>
            <?php
            global $zohocrmdetails;
            global $zohocrmbasename;

            $content = "";
            if (isset($shortcode)) {
                $content .= "<span id='inneroptions' class='leads-builder-sub-heading mr10' ><span class='headerlabel'>Module:</span> ";
                foreach ($zohocrmdetails['crmformswpbuilder']['modulename'] as $key => $value) {
                    if (sanitize_text_field(isset($_REQUEST['module'])) && (sanitize_text_field($_REQUEST['module']) == $key )) {
                        $select_option = " {$value} ";
                    }
                }
                if (sanitize_text_field(isset($_REQUEST['LayoutName']))) {
                    $LayoutName = sanitize_text_field($_REQUEST['LayoutName']);
                }
                $content .= "<span class='headerValue'>" . sanitize_text_field($_REQUEST['module']) . "</span>";
                $content .= "</span>";
                $content .= "<span id='inneroptions' class='leads-builder-sub-heading mr10' ><span class='headerlabel'>Layout:</span> ";
                $content .= "<span class='headerValue'>" . $LayoutName . "</span>";
                $content .= "</span> ";
                $content .= "<span id='inneroptions' class='leads-builder-sub-heading' ><span class='headerlabel'>Shortcode:</span> ";
                $content .= "<span class='shortcode-id headerValue' id='shortcode-id'>[zohocrm-web-form id=" . $shortcode . "]</span>";
                $content .= "</span><span class='clicktocopied copyshortcode' onclick='clicktocopyshortcode(this)' data-toggle='tooltip' data-placement='top' title='Click to copy shortcode'></span> ";
                $allowedposttags = zcf_allowed_tag();
                echo wp_kses( $content, $allowedposttags );
            } else {
                $content .= "<span id='inneroptions' style='position:relative;left:40px;'>Module: <select id='module' name='module' style='margin-left:8px;height:27px;' onchange = \"ChooseFields('{$siteurl}','{$module}','{zcf_crmfields_shortcodes}', '{onEditShortcode}')\" >";
                $select_option = "";
                $select_option .= "<option> --" . __('Select', ZCF_PLUGIN_BASE_URL) . "-- </option>";
                foreach (sanitize_text_field($zohocrmdetails[$_REQUEST['crmtype']]['modulename']) as $key => $value) {
                    if (sanitize_text_field(isset($_REQUEST['module'])) && (sanitize_text_field($_REQUEST['module']) == $key )) {
                        $select_option .= "<option value = '{$key}' selected=selected  > {$value}</option>";
                    } else {
                        $select_option .= "<option value = '{$key}' > {$value}</option>";
                    }
                }
                $content .= $select_option;

                $content .= "</select></span>";
                $allowedposttags = zcf_allowed_tag();
                echo wp_kses( $content, $allowedposttags );
            }

             $contentsync = "<a onclick=\"syncfields('', 'crmformswpbuilder','{$module}', '', 'Oncreate', '', '', 'leads', '{$shortcode}', '{$shortcode}') \" class='pR pl20 cP' title='Modules of Zoho CRM will be synchronized with WordPress' data-toggle='tooltip' data-placement='top'>Fetch Fields</a>";
             echo wp_kses( $contentsync, $allowedposttags );

            ?>

        </div>

        <div>
            <div style="width:98%;">
                <div>
                    <div>
                        <div class="clearfix"></div>
                        <div class=" mt20 width85per">
                            <?php
                            $shortcode = sanitize_text_field($_REQUEST['EditShortcode']);
                            $formName = sanitize_text_field($_REQUEST['formName']);
                            $formname = $wpdb->get_results("SELECT form_name FROM `zcf_zohoshortcode_manager` WHERE `shortcode_name` LIKE '" . $shortcode . "' ");
                            ?>

                            <input class="textField" type="text"  data-value="<?php echo __($formname[0]->form_name); ?>" id="form-name" name="form-name" data-value="<?php echo ($formName); ?>"
                            value="<?php echo $formName; ?>" onblur="formTitleupdate(this, '<?php sanitize_title_with_dashes($formname[0]->form_name); ?>', '<?php echo esc_url_raw($siteurl); ?>', '<?php echo sanitize_text_field($shortcode); ?>')"/>
                            <input type='hidden' id='lead_crmtype' name="lead_crmtype" value="crmformswpbuilder">
                            <input type="hidden" id="savefields" name="savefields" value="<?php echo esc_attr__('Apply', ZCF_PLUGIN_BASE_URL); ?>"/>
                            <?php
                            if (isset($shortcode)) {
                                $content = "";
                            }
                            ?>
                            <div class="dIB fR "><input class="primarybtn dIB vat" type="button"   name="add-new-field" value="Add More Fields" onclick="fieldlistpopup()" />
                                <span id="showmore" class="pR ml30 cP dIB vam" onclick="fieldSetting()"><span class="form-setting-icon"></span></span></div>
                        </div>

                        <?php
                        if (isset($shortcode)) {
                            $content = "";
                            $content .= "<input class='crmforms-btn crmforms-btn-primary btn-radius' id='generate_forms' type='hidden' value='" . __("Apply", ZCF_PLUGIN_BASE_URL) . "' onclick =  \" return updateStatus(false,'" . site_url() . "','{$module}','zcf_crmfields_shortcodes','{$shortcode}', '{$onAction}')\" />";
                            $allowedposttags = zcf_allowed_tag();
                            echo wp_kses( $content, $allowedposttags );
                        }
                        ?>

                        <div id="fieldtable">
                            <?php
                            require_once( ZCF_BASE_DIR_URI . "includes/crmshortcodefunctions.php" );
                            $FieldOperations = new zcffieldoptions();
                            $allowedposttags = zcf_allowed_tag();

                            if (isset($shortcode))
                                echo wp_kses($FieldOperations->zcfformFields("zcf_crmfields_shortcodes", sanitize_text_field($_REQUEST['onAction']), $shortcode, 'post', sanitize_text_field($_REQUEST['module']), sanitize_text_field($_REQUEST['LayoutName'])),$allowedposttags);
                            else
                                echo wp_kses($FieldOperations->zcfformFields("zcf_crmfields_shortcodes", sanitize_text_field($_REQUEST['onAction']), '', 'post', sanitize_text_field($_REQUEST['onAction']), sanitize_text_field($_REQUEST['module']), sanitize_text_field($_REQUEST['LayoutName'])),$allowedposttags);


                            ?>


                        </div>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
    </form>

      <div id="loading-image" style="display: none;"></div>
    <?php
}
?>
</div>
</form>
<div class="newPopup ppTop"  id="field-setting-popup">
    <div class="form-options">
        <div id="settingsavedmessage" style="height: 42px; display:none; color:red;">   </div>
        <div id="savedetails" style="height: 90px; display:none; color:blue;">   </div>
        <div id="url_post_id" style="display:none; color:blue;">  </div>
        <div id="formtext" class="h1 mB0 pp-header m0"> <?php echo esc_html__('Form Settings', ZCF_PLUGIN_BASE_URL); ?> :</div>
        <div class="pp-content pb10">
            <div>

                <?php
                $shortcode = sanitize_text_field($_REQUEST['EditShortcode']);
                $formObj = new zcffieldlistDatamanage();

                if (isset($shortcode) && ( $shortcode != "" )) {
                    $config_fields = $formObj->zcfFormPropSettings($shortcode);
                }
                $content = "";
                $content .= "<input type='hidden'  class='form-control'  name='formtype' value='post'>";
                $allowedposttags = zcf_allowed_tag();
                echo wp_kses( $content, $allowedposttags );
                ?>


            </div>

            <div>
                <div class="form-group col-md-12">
                    <?php
                    $resultaiss = $wpdb->get_results($wpdb->prepare("select * from zcf_zohocrm_assignmentrule where modulename=%s", sanitize_text_field($_REQUEST['module'])));
                    ?>
                    <div id='innertext' class="col-md-4">
                        <label class="leads-builder-label">
                            <?php
                            $HelperObj = new zcfmaincorehelpers();
                            $module = $HelperObj->Module;
                            $resultmodule = $wpdb->get_results($wpdb->prepare("select * from zcf_zohocrm_assignmentrule where modulename=%s", sanitize_text_field($_REQUEST['module'])));
                            echo esc_html__("Assign to User", ZCF_PLUGIN_BASE_URL);
                            ?></label>
                    </div>

                    <?php if (!empty($resultaiss)) { ?>
                        <div class='col-md-2 mr20'>
                            <span id="circlecheck">
                                <label for="check_assigenduser"  id='innertext' class="leads-builder-label">
                                    <input type='radio'  name='check_assigenduser' id='check_assigenduser' value="updateuser"
                                    <?php
                                    if (( isset($config_fields->assignmentrule_enable) && ($config_fields->assignmentrule_enable == 'updateuser')) || $config_fields->assignmentrule_enable != 'updaterule') {
                                        echo esc_html__("checked=checked");
                                    }
                                    ?> onclick="assignedUser(this)">
                                    <?php echo esc_html__("Choose a user", ZCF_PLUGIN_BASE_URL); ?></label>
                            </span>
                        </div>
                        <?php if ($resultmodule != '') { ?>
                            <div class='col-md-4'>
                                <span id="circlecheck">
                                    <label for="check_assigendrule" id='innertext' class="leads-builder-label">
                                        <input type='radio'  name='check_assigenduser' id='check_assigendrule' value= "updaterule"
                                        <?php
                                        if (isset($config_fields->assignmentrule_enable) && ($config_fields->assignmentrule_enable == 'updaterule')) {
                                            echo esc_html__("checked=checked");
                                        }
                                        ?> onclick="assignedUser(this)">
                                        <?php echo esc_html__('Choose an assignment rule', ZCF_PLUGIN_BASE_URL); ?></label>
                                </span>
                            </div>
                            <?php
                        }
                    }
                    ?>

                    <!-- Check both Leads, Contacts and Skip -->

                    <?php
                    if (( isset($config_fields->assignmentrule_enable) && ($config_fields->assignmentrule_enable == 'updateuser')) || $config_fields->assignmentrule_enable != 'updaterule') {
                        $userAssignedClass = "dB";
                        $userAssignedruleClass = "dN";
                    } else {
                        $userAssignedClass = "dN";
                        $userAssignedruleClass = "dB";
                    }
                    ?>
                    <div class='col-md-4'></div>
                    <div  id="assignedto_td" class="col-md-2 mt10 <?php echo sanitize_text_field($userAssignedClass); ?>">
                        <?php
                        $crm_type_tmp = 'crmformswpbuilder';
                        require_once(ZCF_BASE_DIR_URI . "includes/crmwebformfieldsfuntions.php");
                        $FunctionsObj = new zcfcoreGetFields();
                        if (isset($shortcode)) {
                            $UsersListHtml = $FunctionsObj->zcfgetUsersListHtml($shortcode);
                        } else {
                            $UsersListHtml = $FunctionsObj->zcfgetUsersListHtml();
                        }
                        $allowedposttags = zcf_allowed_tag();
                        echo wp_kses( $UsersListHtml, $allowedposttags );

                        $first_userid = "";
                        ?>
                        <input type='hidden' id='rr_first_userid' value="<?php echo esc_attr__($first_userid); ?>">
                    </div>

                    <?php

                    $resultlayout = $wpdb->get_results($wpdb->prepare("select sm.assignmentrule_ID from zcf_zohocrm_assignmentrule ar join zcf_zohoshortcode_manager sm where sm.assignmentrule_ID=ar.assignmentrule_ID and sm.shortcode_name=%s",sanitize_text_field($_REQUEST['EditShortcode'])));

                    if ($resultmodule != '') {
                        ?>


                        <div class="col-md-2 mt10 <?php echo sanitize_text_field($userAssignedruleClass); ?>" id="assignmentRule">

                            <select id='assignmentRule_ID' class=" form-control" data-live-search='false' name='assignmentRule_ID'>";
                                <option value=''>None</option>
                                <?php
                                foreach ($resultaiss as $key => $value) {
                                    if ($resultlayout[0]->assignmentrule_ID == $value->assignmentrule_ID) {
                                      ?>
                                        <option  value="<?php echo esc_html($value->assignmentrule_ID)?>" selected='selected'> <?php echo esc_html($value->assignmentrrule_name) ?> </option>;

                                    <?php
                                    } else {
                                      ?>
                                        <option  value="<?php echo esc_html($value->assignmentrule_ID) ?>" > <?php echo esc_html($value->assignmentrrule_name) ?> </option>;
                                  <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>


                    <?php } ?>

                </div>



            </div>


            <div>
                <div class="form-group col-md-12">
                    <div class="col-md-4">
                        <label id='innertext' class="leads-builder-label"><?php echo esc_html__('Enable URL Redirection', ZCF_PLUGIN_BASE_URL); ?> </label>
                    </div>
                    <div class="col-md-6">
                        <label for="enableurlredirection" class="dIB mt5 mr10">
                            <input id="enableurlredirection" type='checkbox'  class="onOffcb" name='enableurlredirection' onclick="toggleredirecturl(this.id);" value="on" <?php
                            if (isset($config_fields->is_redirection) && ($config_fields->is_redirection == '1')) {
                                echo "checked=checked";
                            }
                            ?> />
                            <span class="onOffSwt">
                                <span class="onOffBtn"></span>
                            </span>
                        </label>

                        <?php if (isset($config_fields->is_redirection) && ($config_fields->is_redirection == '1')) { ?>


                            <input  style="width:235px" class='form-contro' id="redirecturl" type="text" name="redirecturl" <?php
                            if (!isset($config_fields->is_redirection) == '1') {
                                echo "disabled=disabled";
                            }
                            ?> value="<?php if (isset($config_fields->url_redirection)) echo esc_url($config_fields->url_redirection); ?>" placeholder = "<?php echo esc_attr__('Page url or Post url', ZCF_PLUGIN_BASE_URL); ?>"/>

                        <?php }else { ?>

                            <input style="width:235px" class='vH form-contro' id="redirecturl" type="text" name="redirecturl" <?php
                            if (!isset($config_fields->is_redirection) == '1') {
                                echo "disabled=disabled";
                            }
                            ?> value="<?php if (isset($config_fields->url_redirection)) echo esc_url($config_fields->url_redirection); ?>" placeholder = "<?php echo esc_attr__('Page url or Post url', ZCF_PLUGIN_BASE_URL); ?>"/>

                        <?php } ?>

                    </div>
                </div>
                <?php $optionCatpcha = get_option('zcf_captcha_settings');
                ?>
                <div class="form-group col-md-12">
                    <div class="col-md-4">
                        <label id='innertext' class="leads-builder-label"><?php echo esc_html__("Enable Google Captcha", "zoho-crm-form-builder"); ?>
                        </label>
                    </div>
                    <div class="col-md-2">
                        <label for="enablecaptcha" class="dIB">
                            <input id="enablecaptcha" style="display:none" type='checkbox' class="onOffcb" name='enablecaptcha' value="on" <?php
                            if (isset($config_fields->google_captcha) && ($config_fields->google_captcha == '1')) {
                                echo "checked=checked";
                            }
                            ?> />
                            <span class="onOffSwt">
                                <span class="onOffBtn"></span>
                        </label>

                    </div>

                </div>

                <?php
                $thirdparty_form = get_option('Thirdparty_' . $shortcode);
                ?>
                <div class="form-group col-md-12">
                    <div class="col-md-4">
                        <label id='innertext' class="leads-builder-label"><?php echo esc_html__("Would you like to create this form as contact form 7", "zoho-crm-form-builder"); ?>
                        </label>
                    </div>
                    <div class="col-md-1">
                        <label for="customthirdpartyplugin" class="dIB">
                            <input type="checkbox" class="onOffcb" name="customthirdpartyplugin" id="customthirdpartyplugin" <?php
                            if (isset($config_fields->thirtparty_enable) && ($config_fields->thirtparty_enable == '1')) {
                                echo "checked=checked";
                            }
                            ?> style="display:none" onclick="toggleCustomPlugin(this.id)">
                            <span class="onOffSwt">
                                <span class="onOffBtn"></span>
                            </span>
                        </label>
                    </div>
                    <input type="hidden" value="contactform" name="thirdparty_form_type" />

                </div>

                <?php
                $thirdparty_title_key = $shortcode;
                $check_thirdparty_title = get_option($thirdparty_title_key);
                if (isset($config_fields->thirtparty_enable) && ($config_fields->thirtparty_enable == '1')) {
                    ?>
                    <div class="form-group col-md-12  update-thirdparty_title">
                        <div class="col-md-4">
                            <label id='innertext' class="leads-builder-label"><?php echo esc_html__("Specify a title to this form", "zoho-crm-form-builder"); ?>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="thirdparty_form_title" id="thirdparty_form_title" <?php if (!empty($check_thirdparty_title)) { ?> value="<?php echo sanitize_text_field($check_thirdparty_title); ?>" <?php } ?> />
                        </div>

                    </div>
                <?php } else { ?>

                    <div class="form-group vH col-md-12  update-thirdparty_title">
                        <div class="col-md-4">
                            <label id='innertext' class="leads-builder-label"><?php echo esc_html__("Specify a title to this form", "zoho-crm-form-builder"); ?>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="thirdparty_form_title" id="thirdparty_form_title" <?php if (!empty($check_thirdparty_title)) { ?> value="<?php echo sanitize_text_field($check_thirdparty_title); ?>" <?php } ?> />
                        </div>

                    </div>
                <?php } ?>



            </div> </div>
        <div class="alignright pb20 pr20">

            <?php
            $check_thirparty_val_exist = get_option('Thirdparty_' . $shortcode);
            $thirdparty_option_available = 'no';
            if ($check_thirparty_val_exist != '') {
                $thirdparty_option_available = 'yes';
            }
            ?>
            <input type="hidden" name='thirdparty_option_available' id='thirdparty_option_available' value="<?php echo esc_attr__($thirdparty_option_available); ?>">
            <input class="newgraybtn" type="button" onclick="cancelFormSettings();" value="<?php echo esc_attr__("Cancel", ZCF_PLUGIN_BASE_URL); ?>" name="CancelFormSettings" />
            <input class="primarybtn" type="button" onclick="saveFormSettings('<?php echo sanitize_text_field($shortcode); ?>');" value="<?php echo esc_attr__("Save Form Settings", ZCF_PLUGIN_BASE_URL); ?>" name="SaveFormSettings" />


        </div>

    </div>
</div>
<div class="newPopup ppTop dB" id="create-fields-list-popup" role="dialog">

    <div class="h1 mB0 pp-header m0"> Add more fields</div>
    <!-- Modal content-->

    <div class="pp-content">
        <?php

        $shortcode_array = $wpdb->get_row($wpdb->prepare("select shortcode_id from zcf_zohoshortcode_manager where shortcode_name=%s",sanitize_text_field($_REQUEST['EditShortcode'])));
        $fields_array = $wpdb->get_results($wpdb->prepare("select * from zcf_zohocrm_formfield_manager where shortcode_id=%s and state=0 ",$shortcode_array->shortcode_id));
        ?>
        <input type="hidden" id="shortcode_id" value="<?php echo sanitize_text_field($shortcode_array->shortcode_id); ?>" />
        <div class="col-md-12  m10">
            <div class="f13 fontSmooth" id="profileNote">Select the fields(s).</div>
            <div class="field-list dIB cchosen" id="choose-fields">
                <select name="select-fields" id="select-fields"  multiple style="width: 450px;" onchange="jQuery('#form-field-submit').attr('disabled', false)">
                    <?php
                    foreach ($fields_array as $key => $value) {
                        if ($value->state == '1') {
                          ?>
                            <option  value="<?php echo esc_html($value->rel_id) ?>" disabled> <?php echo esc_html($value->display_label) ?> </option>;
                        <?php
                        } else {
                          ?>
                              <option  value="<?php echo esc_html($value->rel_id) ?>"> <?php echo esc_html($value->display_label) ?> </option>;
                      <?php
                        }
                    }

                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="pp-footer">
        <button type="button" id="close" class="newgraybtn " data-dismiss="modal" onclick="cancelNewFormPopup();">Cancel</button>
        <input type="button" id="form-field-submit" class="primarybtn " value="Next" onclick="zcfupdateState()" disabled>
    </div>
</form>
</div>
<div class="freezelayer"></div>
