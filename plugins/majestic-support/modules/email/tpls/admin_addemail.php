<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    $smtphost = array(
        (object) array('id' => '1', 'text' => esc_html(__('Gmail', 'majestic-support'))),
        (object) array('id' => '2', 'text' => esc_html(__('Yahoo', 'majestic-support'))),
        (object) array('id' => '3', 'text' => esc_html(__('Hotmail', 'majestic-support'))),
        (object) array('id' => '4', 'text' => esc_html(__('Aol', 'majestic-support'))),
        (object) array('id' => '5', 'text' => esc_html(__('Other', 'majestic-support')))
    );
    $emailtype = array(
        (object) array('id' => '0', 'text' => esc_html(__('Default', 'majestic-support'))),
        (object) array('id' => '1', 'text' => esc_html(__('SMTP', 'majestic-support')))
    );
    $truefalse = array(
        (object) array('id' => '0', 'text' => esc_html(__('False', 'majestic-support'))),
        (object) array('id' => '1', 'text' => esc_html(__('True', 'majestic-support')))
    );
    $securesmtp = array(
        (object) array('id' => '1', 'text' => esc_html(__('TLS', 'majestic-support'))),
        (object) array('id' => '0', 'text' => esc_html(__('SSL', 'majestic-support')))
    );
?>
<?php
$majesticsupport_js ="
    jQuery(document).ready(function ($) {
        $.validate();
    });

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('addsystememail'); ?>
        <div id="msadmin-data-wrp">
            <form class="msadmin-form" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("?page=majesticsupport_email&task=saveemail"),"save-email")); ?>">
                <div class="mjtc-form-wrapper">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Email', 'majestic-support')); ?>&nbsp;<span style="color: red;" >*</span></div>
                    <div class="mjtc-form-field"><?php echo wp_kses(MJTC_formfield::MJTC_text('email', isset(majesticsupport::$_data[0]->email) ? majesticsupport::$_data[0]->email : '', array('class' => 'inputbox mjtc-form-input-field', 'data-validation' => 'required email')), MJTC_ALLOWED_TAGS) ?></div>
                </div>
                <?php if(in_array('smtp', majesticsupport::$_active_addons)){ ?>
                    <div class="mjtc-form-wrapper">
                        <div class="mjtc-form-title"><?php echo esc_html(__('Send Email By', 'majestic-support')); ?></div>
                        <div class="mjtc-form-field"><?php echo wp_kses(MJTC_formfield::MJTC_select('smtpemailauth', $emailtype , isset(majesticsupport::$_data[0]->email) ? majesticsupport::$_data[0]->smtpemailauth : '' , esc_html(__('Select Type', 'majestic-support')) , array('class' => 'mjtc-smtp-select mjtc-form-select-field')), MJTC_ALLOWED_TAGS)?></div>
                    </div>
                    <div id="smtpauthselect" style="display: none;">
                        <div class="mjtc-form-wrapper">
                            <div class="mjtc-form-title"><?php echo esc_html(__('SMTP host type', 'majestic-support')); ?></div>
                            <div class="mjtc-form-field"><?php echo wp_kses(MJTC_formfield::MJTC_select('smtphosttype', $smtphost , isset(majesticsupport::$_data[0]->email) ? majesticsupport::$_data[0]->smtphosttype : '', esc_html(__('Select Type', 'majestic-support')) , array('class' => 'mjtc-smtp-select mjtc-form-select-field')), MJTC_ALLOWED_TAGS)?></div>
                        </div>
                        <div class="mjtc-form-wrapper">
                            <div class="mjtc-form-title"><?php echo esc_html(__('SMTP host', 'majestic-support')); ?>&nbsp;<span style="color: red;" >*</span></div>
                            <div class="mjtc-form-field"><?php echo wp_kses(MJTC_formfield::MJTC_text('smtphost', isset(majesticsupport::$_data[0]->email) ? majesticsupport::$_data[0]->smtphost : '', array('class' => 'inputbox mjtc-form-select-field')), MJTC_ALLOWED_TAGS) ?></div>
                        </div>
                        <div class="mjtc-form-wrapper">
                            <div class="mjtc-form-title"><?php echo esc_html(__('SMTP Authentication', 'majestic-support')); ?>&nbsp;<span style="color: red;" >*</span></div>
                            <div class="mjtc-form-field"><?php echo wp_kses(MJTC_formfield::MJTC_select('smtpauthencation', $truefalse , isset(majesticsupport::$_data[0]->email) ? majesticsupport::$_data[0]->smtpauthencation : '' , esc_html(__('Select Type', 'majestic-support')) , array('class' => 'mjtc-smtp-select mjtc-form-select-field')), MJTC_ALLOWED_TAGS)?></div>
                        </div>
                        <div class="mjtc-form-wrapper">
                            <div class="mjtc-form-title"><?php echo esc_html(__('Username', 'majestic-support')); ?>&nbsp;<span style="color: red;" >*</span></div>
                            <div class="mjtc-form-field"><?php echo wp_kses(MJTC_formfield::MJTC_text('name', isset(majesticsupport::$_data[0]->email) ? majesticsupport::$_data[0]->name : '', array('class' => 'inputbox mjtc-form-input-field')), MJTC_ALLOWED_TAGS) ?></div>
                        </div>
                        <div class="mjtc-form-wrapper">
                            <div class="mjtc-form-title"><?php echo esc_html(__('Password', 'majestic-support')); ?>&nbsp;<span style="color: red;" >*</span></div>
                            <div class="mjtc-form-field"><?php echo wp_kses(MJTC_formfield::MJTC_password('password', isset(majesticsupport::$_data[0]->email) ? majesticsupport::$_data[0]->password : '', array('class' => 'inputbox mjtc-form-input-field')), MJTC_ALLOWED_TAGS) ?></div>
                        </div>
                        <div class="mjtc-form-wrapper">
                            <div class="mjtc-form-title"><?php echo esc_html(__('SMTP Secure', 'majestic-support')); ?>&nbsp;<span style="color: red;" >*</span></div>
                            <div class="mjtc-form-field"><?php echo wp_kses(MJTC_formfield::MJTC_select('smtpsecure', $securesmtp , isset(majesticsupport::$_data[0]->email) ? majesticsupport::$_data[0]->smtpsecure : '' , esc_html(__('Select Type', 'majestic-support')) , array('class' => 'mjtc-smtp-select mjtc-form-select-field')), MJTC_ALLOWED_TAGS)?></div>
                        </div>
                        <div class="mjtc-form-wrapper">
                            <div class="mjtc-form-title"><?php echo esc_html(__('SMTP Port', 'majestic-support')); ?>&nbsp;<span style="color: red;" >*</span></div>
                            <div class="mjtc-form-field"><?php echo wp_kses(MJTC_formfield::MJTC_text('mailport', isset(majesticsupport::$_data[0]->email) ? majesticsupport::$_data[0]->mailport : '', array('class' => 'inputbox mjtc-form-input-field')), MJTC_ALLOWED_TAGS) ?></div>
                        </div>
                        <div class="mjtc-col-md-12 mjtc-col-md-offset-2 mjtc-admin-ticketviaemail-wrapper-checksetting">
                            <a title="<?php echo esc_attr(__('Check Settings','majestic-support')); ?>" href="#" id="mjtc-admin-ticketviaemail"><img alt="<?php echo esc_html(__('check','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/tick_ticketviaemail.png" /><?php echo esc_html(__('Check Settings','majestic-support')); ?></a>
                            <div id="mjtc-admin-ticketviaemail-bar"></div>
                            <div class="mjtc-col-md-12" id="mjtc-admin-ticketviaemail-text"><?php echo esc_html(__('If the system doesnot respond in 30 seconds','majestic-support')).', '.esc_html(__('it means system unable to connect email server','majestic-support')); ?></div>
                            <div class="mjtc-col-md-12">
                               <div id="mjtc-admin-ticketviaemail-msg"></div>
                           </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="mjtc-form-wrapper">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Auto Response', 'majestic-support')); ?></div>
                    <div class="mjtc-form-field"><?php echo wp_kses(MJTC_formfield::MJTC_radiobutton('autoresponse', array('1' => esc_html(__('Yes', 'majestic-support')), '0' => esc_html(__('No', 'majestic-support'))), isset(majesticsupport::$_data[0]->autoresponse) ? majesticsupport::$_data[0]->autoresponse : '1', array('class' => 'radiobutton mjtc-form-radio-field')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <div class="mjtc-form-wrapper">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Status', 'majestic-support')); ?></div>
                    <div class="mjtc-form-field"><?php echo wp_kses(MJTC_formfield::MJTC_radiobutton('status', array('1' => esc_html(__('Active', 'majestic-support')), '0' => esc_html(__('Disabled', 'majestic-support'))), isset(majesticsupport::$_data[0]->status) ? majesticsupport::$_data[0]->status : '1', array('class' => 'radiobutton mjtc-form-radio-field')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('id', isset(majesticsupport::$_data[0]->id) ? majesticsupport::$_data[0]->id : '' ), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('created', isset(majesticsupport::$_data[0]->created) ? majesticsupport::$_data[0]->created : '' ), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('updated', isset(majesticsupport::$_data[0]->updated) ? majesticsupport::$_data[0]->updated : '' ), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('action', 'email_saveemail'), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                <div class="mjtc-form-button">
                    <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('save', esc_html(__('Save Email', 'majestic-support')), array('class' => 'button mjtc-form-save')), MJTC_ALLOWED_TAGS); ?>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$majesticsupport_js ="
    jQuery(document).ready(function($){
        smtpAuthSelect();
        if(jQuery('#host').val() == '')
            smtphosttype(1);
        $('select#smtpemailauth').change(function(){
            smtpAuthSelect();
        });
        $('#smtphosttype').change(function(){
            smtphosttype(1);
        });

        function smtpAuthSelect(){
            if(jQuery('select#smtpemailauth').val() == 1){
                jQuery('div#smtpauthselect').show();
            }else{
                jQuery('div#smtpauthselect').hide();
            }
        }

        function smtphosttype(n){
            if(n==1 || jQuery('#host').val() == ''){
                if(jQuery('#smtphosttype').val() == 1){
                    jQuery('#host').val('smtp.gmail.com');
                }else if(jQuery('#smtphosttype').val() == 2){
                    jQuery('#host').val('smtp.mail.yahoo.com');
                }else if(jQuery('#smtphosttype').val() == 3){
                    jQuery('#host').val('smtp.live.com');
                }else if(jQuery('#smtphosttype').val() == 4){
                    jQuery('#host').val('smtp.aol.com');
                }else{
                    jQuery('#host').val('');
                }
            }
        }

        $('form').submit(function(e){
            if(jQuery('select#smtpemailauth').val() == 1){
                if($('#host').val() == '' || $('#name').val() == '' || $('#password').val() == '' || $('#smtpsecure').val() == '' || $('#port').val() == '' || $('#smtpauthencation').val() == ''){
                    e.preventDefault();
                    alert('".esc_html(__('Some values are not acceptable please retry', 'majestic-support'))."');
                }
            }
            if(jQuery('select#smtpemailauth').val() == 0){
                $('#host').val('');
                $('#name').val('');
                $('#password').val('');
                $('#smtpsecure').val('');
                $('#port').val('');
                $('#smtpauthencation').val('');
            }
        });
        jQuery('a#mjtc-admin-ticketviaemail').click(function(e){
            e.preventDefault();

                var hosttype = jQuery('select#smtphosttype').val();
                var hostname = jQuery('input#smtphost').val();
                if(hosttype == 4){
                    var hostname = jQuery('input#hostname').val();
                    if(hostname != ''){
                        var hostname = jQuery('input#hostname').val();
                    }else{
                        alert(\"". esc_html(__('Please enter the hostname first','majestic-support'))."\");
                        return;
                    }
                }
                var emailaddress = jQuery('input#name').val();
                var password = jQuery('input#password').val();
                var ssl = jQuery('select#smtpsecure').val();
                var hostportnumber = jQuery('input#mailport').val();
                var smtpauthencation_val = jQuery('select#smtpauthencation').val();
                jQuery('div#mjtc-admin-ticketviaemail-bar').show();
                jQuery('div#mjtc-admin-ticketviaemail-text').show();
                jQuery.post(ajaxurl, {action: 'mjsupport_ajax', hosttype: hosttype,hostname:hostname, emailaddress: emailaddress,password:password,ssl:ssl,hostportnumber:hostportnumber, smtpauthencation:smtpauthencation_val , mjsmod: 'email', task: 'sendTestEmail', '_wpnonce':'". esc_attr(wp_create_nonce("send-test-email"))."'}, function (data) {
                    if (data) {
                        jQuery('div#mjtc-admin-ticketviaemail-bar').hide();
                        jQuery('div#mjtc-admin-ticketviaemail-text').hide();
                        var obj = jQuery.parseJSON(data);
                        if(obj.type == 0){
                            jQuery('div#mjtc-admin-ticketviaemail-msg').html(obj.text).addClass('no-error');
                        }else{
                            jQuery('div#mjtc-admin-ticketviaemail-msg').html(obj.text).addClass('imap-error');
                        }
                        jQuery('div#mjtc-admin-ticketviaemail-msg').show();
                    }
                });//jquery closed

        });
    });

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  
