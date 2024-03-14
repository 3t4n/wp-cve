<?php
    if (!defined('ABSPATH')) die('Restricted Access');
?>
<?php
$majesticsupport_js ="
    jQuery(document).ready(function ($) {
        jQuery.validate();
        jQuery('form input').keydown(function (e) {
            if (e.keyCode == 13) {
                var inputs = $(this).parents('form').eq(0).find(':input');
                if (inputs[inputs.index(this) + 1] != null) {
                }
                e.preventDefault();
                return false;
            }
        });
    });

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('addsmartreply'); ?>
        <div id="msadmin-data-wrp">
            <form class="msadmin-form" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("?page=majesticsupport_smartreply&task=savesmartreply"),"save-smart-reply")); ?>">
                <div class="mjtc-form-wrapper">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Title', 'majestic-support')); ?>&nbsp;<span style="color: red;" >*</span></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_text('title', isset(majesticsupport::$_data[0]->title) ? majesticsupport::$_data[0]->title : '', array('maxlength' => '255', 'class' => 'inputbox mjtc-form-input-field', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) ?></div>
                </div>
                <div class="mjtc-form-smartreply-wrapper" id="ticket_subjects_div" >
                    <div id="ms-ticket-subject" class="mjtc-form-wrapper">
                        <div class="mjtc-form-title"><?php echo esc_html(__('Ticket Subject', 'majestic-support')); ?>&nbsp;<span style="color: red;" >*</span></div>
                        <div class="mjtc-form-value" onclick="addtext();">
                            <?php echo wp_kses(MJTC_formfield::MJTC_text('ticketsubjects[]', isset(majesticsupport::$_data[0]->smartreplycolour) ? majesticsupport::$_data[0]->smartreply : '', array('class' => 'inputbox mjtc-form-input-field', 'placeholder' =>  esc_html(__('Enter Ticket Subject', 'majestic-support')), 'autocomplete' => 'off')), MJTC_ALLOWED_TAGS); ?>
                           <span class="ms-add-ticket-subject-overall-wrapper"> <img class="ms-add-ticket-subject" alt="<?php echo esc_html(__('Add','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/smart-reply/PLUS-ICON-new.png" /></span>
                        </div>
                    </div>
                    <?php
                    if (isset(majesticsupport::$_data[0]->ticketsubjects)) {
                        foreach (majesticsupport::$_data[0]->ticketsubjects as $key => $message) {
                            $count =  $key+1;
                            $divid = "divedit_".$count;
                            ?>
                            <div id="<?php echo esc_attr($divid) ?>" class="mjtc-form-wrapper fullwidth">
                            <div class="mjtc-form-value del-btn-wrp">
                                <?php echo wp_kses(MJTC_formfield::MJTC_text('ticketsubjects[]', isset($message) ? majesticsupport::MJTC_getVarValue($message) : '', array('class' => 'inputbox one mjtc-form-input-field usr-input1', 'data-validation' => 'required', 'placeholder' => 'Ticket Subject Here')),MJTC_ALLOWED_TAGS); ?>
                                <button type="button" onClick="deleteMsg(<?php echo esc_js($divid) ?>)" class="del-btn">
                                    <img src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/smart-reply/delete.png" alt="<?php echo esc_html(__('delete','majestic-support')); ?>">
                                </button>
                            </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <div class="mjtc-form-wrapper fullwidth">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Response', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value">
                        <?php
                            wp_editor(isset(majesticsupport::$_data[0]->reply) ? majesticsupport::$_data[0]->reply : '', 'reply', array('media_buttons' => false));
                        ?>
                    </div>
                </div>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('id', isset(majesticsupport::$_data[0]->id) ? majesticsupport::$_data[0]->id : '' ), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('action', 'smartreply_savesmartreply'), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('uid', MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid()), MJTC_ALLOWED_TAGS); ?>
                <div class="mjtc-form-button">
                    <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('save', esc_html(__('Save Smart Reply', 'majestic-support')), array('class' => 'button mjtc-form-save','onClick' => 'return validateInput();')), MJTC_ALLOWED_TAGS); ?>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$majesticsupport_js ="
    var divid = 1;
    function addtext(){
        var reqvalue = jQuery('#ms-ticket-subject :input').val();
        if(reqvalue!='') {
            var str = '<div id=\"div_'+divid+'\" class=\"mjtc-form-wrapper fullwidth\"><div class=\"mjtc-form-value del-btn-wrp\"><input type=\"text\" name=\"ticketsubjects[]\" id=\"ticketsubjects\" value=\"'+reqvalue+'\" class=\"inputbox one mjtc-form-input-field\" data-validation=\"required\" placeholder=\"Ticket Subject Here\"><button type=\"button\" onClick=\"deleteMsg(div_'+divid+')\" class=\"del-btn\"><img src=\"". esc_url(MJTC_PLUGIN_URL)."includes/images/smart-reply/delete.png\" alt=\"". esc_html(__('delete','majestic-support'))."\"></button></div></div>';
            jQuery('#ms-ticket-subject').after(str);

            jQuery('#ms-ticket-subject :input').val('');
            jQuery('#ms-ticket-subject :input').focus();
            divid++;
            console.log(str);
        }
    }

    function deleteMsg(divid){
         jQuery(divid).remove();
    }

    var msgs = document.getElementById('ticketsubjects[]');
    msgs.addEventListener('keydown', function (e) {
        if (e.keyCode === 13) {
            validate(e);
        }
    });

    var divid = 1;
    function validate(e) {
        var reqvalue = jQuery('#ms-ticket-subject :input').val();
        if(reqvalue!='') {
            var str = '<div id=\"div_'+divid+'\" class=\"mjtc-form-wrapper fullwidth\"><div class=\"mjtc-form-value del-btn-wrp\"><input type=\"text\" name=\"ticketsubjects[]\" id=\"ticketsubjects\" value=\"'+reqvalue+'\" class=\"inputbox one mjtc-form-input-field\" data-validation=\"required\" placeholder=\"Ticket Subject Here\"><button type=\"button\" onClick=\"deleteMsg(div_'+divid+')\" class=\"del-btn\"><img src=\"". esc_url(MJTC_PLUGIN_URL)."includes/images/smart-reply/delete.png\" alt=\"". esc_html(__('delete','majestic-support'))."\"></button></div></div>';
            jQuery('#ms-ticket-subject').after(str);

            jQuery('#ms-ticket-subject :input').val('');
            jQuery('#ms-ticket-subject :input').focus();

            divid++;
            console.log(str);
        }
    }

    var counter = 1;
    function validateInput() {
        var uservalue = jQuery('#ms-ticket-subject :input').val();
        var requvalue = jQuery('.del-btn-wrp :input').length;

        if(uservalue!='' || requvalue>0 ){
            document.getElementById('intent_form').submit();
            return true;
        } else {
            jQuery('#ms-ticket-subject :input').attr('data-validation', 'required');
            jQuery('#ms-ticket-subject :input').addClass('error');
            jQuery('#ms-ticket-subject :input').css('border-color','red');
            if(counter==1) {
                jQuery('#ms-ticket-subject :input').after('<span class=\"help-block form-error\">".esc_html(__("You have not answered all required fields",'majestic-support'))."</span>');
            }
            counter++;
            return false;
        }
    }

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>
