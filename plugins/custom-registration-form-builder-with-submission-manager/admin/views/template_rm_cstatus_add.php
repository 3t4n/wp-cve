<?php
if (!defined('WPINC')) {
    die('Closed');
}
if (defined('REGMAGIC_ADDON')) { include_once(RM_ADDON_ADMIN_DIR . 'views/template_rm_cstatus_add.php'); } else {
wp_enqueue_style( 'rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css' );
$other_status_list= array(''=>'Select Status'); 
if(is_array($data->form_options->custom_status))
{ 
    foreach($data->form_options->custom_status as $key=>$value){
        if(!empty($data->custom_status) && $data->custom_status['label']==$value['label'])
            continue;
        $other_status_list[$key]=$value['label'];
    }
}
?>
<div class="rmagic">
    <!--Dialogue Box Starts-->
    <div class="rmcontent">
        <?php
        require_once RM_EXTERNAL_DIR.'icons/icons_list.php';

        $form = new RM_PFBC_Form("add-cstatus");

        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
        
        //$trail_text = RM_UI_Strings::get("MSG_BUY_PRO_INLINE");
        $trail_text = "";
        $single_trail_text = RM_UI_Strings::get("MSG_BUY_PRO_ACTIONS_INLINE");

        $form->addElement(new Element_HTML('<div class="rmheader">' . RM_UI_Strings::get("TITLE_NEW_CSTATUS") . '</div>'));
        //$form->addElement(new Element_HTML('<div class="rmnotice-row rm-custom-status-note"><div class="rmnotice">'.__('You can assign statuses to registrations from the ','custom-registration-form-builder-with-submission-manager').'<a href="admin.php?page=rm_submission_manage">inbox</a>.'.'</div></div>'));
        $form->addElement(new Element_HTML('<div class="rmrow"><h3>'.__('Label Properties','custom-registration-form-builder-with-submission-manager').'</h3></div>'));
            
        $form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_NAME') . "</b>", "cstatus_label", array("id" => "cstatus_label", "required" => "1", "value" => (isset($data->custom_status['label'])?$data->custom_status['label']:''), "longDesc" => RM_UI_Strings::get('HELP_ADD_CSTATUS_TITLE'))));
        $form->addElement(new Element_Textarea("<b>" . RM_UI_Strings::get('LABEL_FORM_DESC') . "</b>", "cstatus_desc", array("id" => "cstatus_desc", "value" => (isset($data->custom_status['label'])?$data->custom_status['desc']:''), "longDesc" => RM_UI_Strings::get('HELP_ADD_CSTATUS_DESC'))));
        $form->addElement(new Element_Color("<b>" . RM_UI_Strings::get('LABEL_COLOR') . "</b>", "cstatus_color", array("class" => "cstatus_color","required"=>1, "value"=>isset($data->custom_status['label'])?$data->custom_status['color']:'000', "longdesc" => RM_UI_Strings::get('HELP_CSTATUS_ADD_COLOR'))));
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_SHOW_FRONTEND') . "</b>", "cstatus_frontend", array(1 => ""), array("class" => "rm-has-child rm-static-field rm_input_type", "value" => isset($data->custom_status['cs_show_frontend']) ? absint($data->custom_status['cs_show_frontend']) : 0, "longdesc" => RM_UI_Strings::get('HELP_STATUS_FRONTEND'))));
        $form->addElement(new Element_Hidden("rm_form_id", $data->form_id));
        if($data->new!=true){
            $form->addElement(new Element_Hidden("cstatus_id", $data->cstatus_id));
        }
        $form->addElement(new Element_HTML('<div class="rmrow"><h3>'.__('Associated Actions','custom-registration-form-builder-with-submission-manager').'</h3><span class="rm-status-action-note">'.RM_UI_Strings::get('STATUS_ACTION_NOTE').'</span> <div class="rmnote-wrap"><div class="rmnote">'.$single_trail_text.'</div></div></div>'));
        // Other statuses actions
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_OTHER_STATUSES') . "</b>", "buy_pro_1", array(1 => ""), array("onchange"=>"show_child(this,'rm_cs_other_st_actions')","class" => "rm-has-child rm-static-field rm_input_type", "value" => 0, 'disabled' => 1, "longdesc" => RM_UI_Strings::get('HELP_OTHER_STATUS').$trail_text)));
        $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_cs_other_st_actions" style="display:none" >'));
            $form->addElement(new Element_Radio(RM_UI_Strings::get('LABEL_STATUS_ACTION').":", "buy_pro_2", array('do_nothing'=>'Do Nothing','clear_all'=>'Clear All Other Statuses','clear_specific'=>'Clear Specific Status(es)'), array("onchange"=>"show_other(this,'rm_cs_other_st_specific')","value" => 'do_nothing', 'disabled' => 1, "longDesc"=>'')));
                $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_cs_other_st_specific" style="display:none" >'));
                    $form->addElement(new Element_Select("<b>" . RM_UI_Strings::get('LABEL_STATUSES') . "</b>", "cs_act_status_specific", $other_status_list, array("multiple"=>"multiple","value" => isset($data->custom_status['cs_act_status_specific']) ? $data->custom_status['cs_act_status_specific'] : 99999, "class" => "rm_static_field", "longDesc"=>RM_UI_Strings::get('HELP_CLEAR_STATUSES').$trail_text)));
                $form->addElement(new Element_HTML('</div>'));     
        $form->addElement(new Element_HTML('</div>'));
        
        // Email to user options
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_CS_EMAIL_TO_USER') . "</b>", "buy_pro_3", array(1 => ""), array("onchange"=>"show_child(this,'rm_cs_email_user')","class" => "rm-has-child rm-static-field rm_input_type", "value" => 0, 'disabled' => 1, "longdesc" => RM_UI_Strings::get('HELP_CS_EMAIL_USER_EN').$trail_text)));
        $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_cs_email_user" style="display:none" >'));
            $form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_SUBJECT') . "</b>", "buy_pro_4", array("value" => '', 'disabled' => 1, "longDesc" => RM_UI_Strings::get('HELP_CS_USER_EMAIL_SUBJECT'))));
            $form->addElement(new Element_TinyMCEWP("<b>" . RM_UI_Strings::get('LABEL_BODY') . "</b>", '', "buy_pro_5", array('editor_class' => 'rm_TinydMCE rm_TinydMCE-disabled','disabled' => 1, "longDesc" => __('Contents of the email to be sent to the user. To dynamically place the Submission ID or Unique ID use: {{SUB_ID}},{{UNIQUE_TOKEN}}','custom-registration-form-builder-with-submission-manager'))));
        $form->addElement(new Element_HTML('</div>'));
        
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_CS_EMAIL_TO_ADMIN') . "</b>", "buy_pro_6", array(1 => ""), array("onchange"=>"show_child(this,'rm_cs_email_admin')","class" => "rm-has-child rm-static-field rm_input_type", "value" => 0, 'disabled' => 1, "longdesc" => RM_UI_Strings::get('HELP_CS_EMAIL_ADMIN_EN').$trail_text)));
        $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_cs_email_admin" style="display:none" >'));
            $form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_SUBJECT') . "</b>", "buy_pro_7", array("value" => '', 'disabled' => 1, "longDesc" => RM_UI_Strings::get('HELP_CS_ADMIN_EMAIL_SUBJECT'))));
            $form->addElement(new Element_TinyMCEWP("<b>" . RM_UI_Strings::get('LABEL_BODY') . "</b>", '', "buy_pro_8", array('editor_class' => 'rm_TinydMCE rm_TinydMCE-disabled', 'editor_height' => '100px'), array('disabled' => 1, "longDesc" => __('Contents of the email to be sent to the admin. To dynamically place the Submission ID or Unique ID use: {{SUB_ID}},{{UNIQUE_TOKEN}}','custom-registration-form-builder-with-submission-manager'))));
        $form->addElement(new Element_HTML('</div>'));
        
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_CS_USER_ACTIONS_EN') . "</b>", "buy_pro_9", array(1 => ""), array("onchange"=>"show_child(this,'rm_cs_user_actions')","class" => "rm-has-child rm-static-field rm_input_type", "value" => 0, 'disabled' => 1, "longdesc" => RM_UI_Strings::get('HELP_CS_USER_ACTION').$trail_text)));
        $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_cs_user_actions" style="display:none" >'));
            $form->addElement(new Element_Radio(RM_UI_Strings::get('LABEL_CS_USER_ACTIONS').":", "buy_pro_10", array('create_account'=>'Create User Account','deactivate_user'=>'Deactivate User Account','activate_user'=>'Activate User Account','delete_user'=>'Delete User Account'), array("value" => 'create_account', 'disabled' => 1, "longDesc"=>'')));
        $form->addElement(new Element_HTML('</div>'));
        
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_ATTACH_NOTE') . "</b>", "buy_pro_11", array(1 => ""), array("onchange"=>"show_child(this,'rm_cs_attach_note')","class" => "rm-has-child rm-static-field rm_input_type", "value" => 0, 'disabled' => 1, "longdesc" => RM_UI_Strings::get('HELP_ATTACH_NOTE').$trail_text)));
        $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_cs_attach_note" style="display:none" >'));
            $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_NOTE_PUBLIC') . "</b>", "buy_pro_12", array(1 => ""), array("class" => "rm-has-child rm-static-field rm_input_type", "value" => 0, 'disabled' => 1, "longdesc" => RM_UI_Strings::get('HELP_LABEL_NOTE_PUBLIC'))));
            $form->addElement(new Element_Textarea("<b>" . RM_UI_Strings::get('LABEL_NOTE_TEXT') . "</b>", "buy_pro_13", array('class' => 'rm_TinydMCE rm_TinydMCE-disabled',"value" => '', 'disabled' => 1, "longDesc" => RM_UI_Strings::get('HELP_NOTE_TEXT'))));
        $form->addElement(new Element_HTML('</div>'));
        
        $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_BLACKLIST') . "</b>", "buy_pro_14", array(1 => ""), array("onchange"=>"show_child(this,'rm_cs_blacklist')","class" => "rm-has-child rm-static-field rm_input_type", "value" => 0, 'disabled' => 1, "longdesc" => RM_UI_Strings::get('HELP_BLACKLIST').$trail_text)));
        $form->addElement(new Element_HTML('<div class="childfieldsrow" id="rm_cs_blacklist" style="display:none" >'));
            $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_BLOCK_EMAIL') . "</b>", "buy_pro_15",array(1 => ""), array("value" => 0, 'disabled' => 1, "longDesc" => RM_UI_Strings::get('HELP_BLOCK_EMAIL'))));
            $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_BLOCK_IP') . "</b>", "buy_pro_16",array(1 => ""), array("value" => 0, 'disabled' => 1, "longDesc" => RM_UI_Strings::get('HELP_BLOCK_IP'))));
            $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_UNBLOCK_EMAIL') . "</b>", "buy_pro_17",array(1 => ""), array("value" => 0, 'disabled' => 1, "longDesc" => RM_UI_Strings::get('HELP_UNBLOCK_EMAIL'))));
            $form->addElement(new Element_Checkbox("<b>" . RM_UI_Strings::get('LABEL_UNBLOCK_IP') . "</b>", "buy_pro_18",array(1 => ""), array("value" => 0, 'disabled' => 1, "longDesc" => RM_UI_Strings::get('HELP_UNBLOCK_IP'))));
        $form->addElement(new Element_HTML('</div>'));
        
        $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','custom-registration-form-builder-with-submission-manager'), '?page=rm_form_manage_cstatus&rm_form_id='.$data->form_id, array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit", "onClick" => "jQuery.prevent_field_add(event,'".__('This is a required field.','custom-registration-form-builder-with-submission-manager')."')")));
        $form->render();
        ?>  
    </div>
</div>
<script>
function show_child(obj,container){
    var containerEl= jQuery('#' + container);

    if(containerEl.length>0){
        if(jQuery(obj).is(':checked'))
            containerEl.slideDown();
        else
            containerEl.slideUp();
    }
}

function show_other(obj,container){
    var containerEl= jQuery('#' + container);

    if(containerEl.length>0){
        if(jQuery(obj).val()=='clear_specific')
            containerEl.slideDown();
        else
            containerEl.slideUp();
    }
}

jQuery(document).ready(function(){
    jQuery('.rm-has-child').trigger('change');
});
</script>
<?php } ?>