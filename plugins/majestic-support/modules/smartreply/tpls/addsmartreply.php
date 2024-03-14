<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$majesticsupport_js ="
    jQuery(document).ready(function ($) {
        jQuery.validate();
        jQuery('form input').keydown(function (e) {
            if (e.keyCode == 13) {
                var inputs = $(this).parents('form').eq(0).find(':input');
                if (inputs[inputs.index(this) + 1] != null) {
                    //inputs[inputs.index(this) + 1].focus();
                }
                e.preventDefault();
                return false;
            }
        });
    });

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  
<div class="ms-main-up-wrapper">
    <?php
    if (majesticsupport::$_config['offline'] == 2) {
        if (majesticsupport::$_data['permission_granted'] == 1) {
            if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid() != 0) {
                if ( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()) {
                    if (majesticsupport::$_data['staff_enabled']) {
                        $status = array((object) array('id' => '1', 'text' => esc_html(__('Active', 'majestic-support'))),
                        (object) array('id' => '0', 'text' => esc_html(__('Disabled', 'majestic-support'))));
                        ?>
                        <?php
                        $majesticsupport_js ="
                            jQuery(document).ready(function ($) {
                                $.validate();
                            });

                        ";
                        wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
                        ?>  
                        <?php include_once(MJTC_PLUGIN_PATH . 'includes/header.php'); ?>
                        <div class="mjtc-support-top-sec-header">
                            <img class="mjtc-transparent-header-img1" alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                                src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/tp-image.png" />
                            <div class="mjtc-support-top-sec-left-header">
                                <div class="mjtc-support-main-heading">
                                    <?php echo esc_html(__("Add Smart Reply",'majestic-support')); ?>
                                </div>
                                <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageBreadcrumps('addsmartreply'); ?>
                            </div>
                        </div>
                        <div class="mjtc-support-cont-main-wrapper">
                            <div class="mjtc-support-cont-wrapper mjtc-support-cont-wrapper-color">
                                <div class="mjtc-support-add-form-wrapper">
                                    <form class="mjtc-support-form" method="post" action="<?php echo esc_url(wp_nonce_url(majesticsupport::makeUrl(array('mjsmod'=>'smartreply', 'task'=>'savesmartreply')),"save-smart-reply")); ?>">
                                        <div class="mjtc-support-from-field-wrp">
                                            <div class="mjtc-support-from-field-title">
                                                <?php echo esc_html(__('Title', 'majestic-support')); ?>&nbsp;<span style="color: red;" >*</span>
                                            </div>
                                            <div class="mjtc-support-from-field">
                                                <?php echo wp_kses(MJTC_formfield::MJTC_text('title', isset(majesticsupport::$_data[0]->title) ? majesticsupport::$_data[0]->title : '', array('maxlength' => '255', 'class' => 'inputbox mjtc-support-form-field-input', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) ?>
                                            </div>
                                        </div>
                                        <div class="mjtc-form-smartreply-wrapper" id="ticket_subjects_div" >
                                            <div id="ms-ticket-subject" class="mjtc-support-from-field-wrp">
                                                <div class="mjtc-support-from-field-title">
                                                    <?php echo esc_html(__('Ticket Subject', 'majestic-support')); ?>&nbsp;<span style="color: red;" >*</span>
                                                </div>
                                                <div class="mjtc-support-from-field" onclick="addtext();">
                                                    <?php echo wp_kses(MJTC_formfield::MJTC_text('ticketsubjects[]', isset(majesticsupport::$_data[0]->smartreplycolour) ? majesticsupport::$_data[0]->smartreply : '', array('class' => 'inputbox mjtc-support-form-field-input', 'placeholder' =>  esc_html(__('Enter Ticket Subject', 'majestic-support')), 'autocomplete' => 'off')), MJTC_ALLOWED_TAGS); ?>
                                                    <span class="ms-add-ticket-subject-overall-wrapper">
                                                        <img class="ms-add-ticket-subject" alt="<?php echo esc_html(__('Add','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/smart-reply/PLUS-ICON-new.png" />
                                                    </span>
                                                </div>
                                            </div>
                                            <?php
                                            if (isset(majesticsupport::$_data[0]->ticketsubjects)) {
                                                foreach (majesticsupport::$_data[0]->ticketsubjects as $key => $message) {
                                                    $count =  $key+1;
                                                    $divid = "divedit_".$count;
                                                    ?>
                                                    <div id="<?php echo esc_attr($divid) ?>" class="mjtc-support-from-field-wrp fullwidth">
                                                        <div class="mjtc-support-from-field del-btn-wrp">
                                                            <?php echo wp_kses(MJTC_formfield::MJTC_text('ticketsubjects[]', isset($message) ? majesticsupport::MJTC_getVarValue($message) : '', array('class' => 'inputbox one mjtc-support-form-field-input usr-input1', 'data-validation' => 'required', 'placeholder' => 'Ticket Subject Here')),MJTC_ALLOWED_TAGS); ?>
                                                            <button type="button" onClick="deleteMsg(<?php echo esc_js($divid) ?>)" class="del-btn">
                                                                <img src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/smart-reply/delete.png" alt="<?php echo esc_html(__('delete','majestic-support')); ?>">
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            } ?>
                                        </div>
                                        <div class="mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width">
                                            <div class="mjtc-support-from-field-title">
                                                <?php echo esc_html(__('Response', 'majestic-support')); ?>
                                            </div>
                                            <div class="mjtc-support-from-field">
                                                <?php
                                                wp_editor(isset(majesticsupport::$_data[0]->reply) ? majesticsupport::$_data[0]->reply : '', 'reply', array('media_buttons' => false));
                                                ?>
                                            </div>
                                        </div>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('id', isset(majesticsupport::$_data[0]->id) ? majesticsupport::$_data[0]->id : '' ), MJTC_ALLOWED_TAGS); ?>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('action', 'smartreply_savesmartreply'), MJTC_ALLOWED_TAGS); ?>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('mspageid', get_the_ID()), MJTC_ALLOWED_TAGS); ?>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('uid', MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid()), MJTC_ALLOWED_TAGS); ?>
                                        <div class="mjtc-support-form-btn-wrp">
                                            <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('save', esc_html(__('Save Smart Reply', 'majestic-support')), array('class' => 'button mjtc-support-save-button','onClick' => 'return validateInput();')), MJTC_ALLOWED_TAGS); ?>
                                        </div>
                                    </form>
                                </div>
                            <?php
                    } else {
                        MJTC_layout::MJTC_getStaffMemberDisable();
                    }
                } else { // user not Staff
                    MJTC_layout::MJTC_getNotStaffMember();
                }
            } else {// User is guest
                $redirect_url = majesticsupport::makeUrl(array('mjsmod'=>'smartreply', 'mjslay'=>'addsmartreply'));
                $redirect_url = MJTC_majesticsupportphplib::MJTC_safe_encoding($redirect_url);
                MJTC_layout::MJTC_getUserGuest($redirect_url);
            }
        } else { // User permission not granted
            MJTC_layout::MJTC_getPermissionNotGranted();
        }
    } else { // System is offline
        MJTC_layout::MJTC_getSystemOffline();
    }?>
</div>
</div>
</div>
<?php
$majesticsupport_js ="
    var divid = 1;
    function addtext(){
        var reqvalue = jQuery('#ms-ticket-subject :input').val();
        if(reqvalue!='') {
            var str = '<div id=\"div_'+divid+'\" class=\"mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width fullwidth\"><div class=\"mjtc-form-value del-btn-wrp\"><input type=\"text\" name=\"ticketsubjects[]\" id=\"ticketsubjects\" value=\"'+reqvalue+'\" class=\"inputbox one mjtc-support-form-field-input\" data-validation=\"required\" placeholder=\"Ticket Subject Here\"><button type=\"button\" onClick=\"deleteMsg(div_'+divid+')\" class=\"del-btn\"><img src=\"". esc_url(MJTC_PLUGIN_URL)."includes/images/smart-reply/delete.png\" alt=\"". esc_html(__('delete','majestic-support'))."\"></button></div></div>';
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
            var str = '<div id=\"div_'+divid+'\" class=\"mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width\"><div class=\"mjtc-support-from-field del-btn-wrp\"><input type=\"text\" name=\"ticketsubjects[]\" id=\"ticketsubjects\" value=\"'+reqvalue+'\" class=\"inputbox one mjtc-support-form-field-input\" data-validation=\"required\" placeholder=\"Ticket Subject Here\"><button type=\"button\" onClick=\"deleteMsg(div_'+divid+')\" class=\"del-btn\"><img src=\"". esc_url(MJTC_PLUGIN_URL)."includes/images/smart-reply/delete.png\" alt=\"". esc_html(__('delete','majestic-support'))."\"></button></div></div>';
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
