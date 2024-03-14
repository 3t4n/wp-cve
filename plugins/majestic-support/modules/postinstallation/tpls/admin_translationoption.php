<?php
if (!defined('ABSPATH')) die('Restricted Access');
$tran_data = json_decode(majesticsupport::$_data[0]['mstran']);
?>
<div id="mjtc-spt-admin-wrapper">
    <div id="mjtc-spt-cparea">
        <div id="ms-main-wrapper" class="post-installation">
            <div class="mjtc-admin-title-installtion">
                <span class="ms_heading"><?php echo esc_html(__('Majestic Support Settings','majestic-support')); ?></span>
                <div class="close-button-bottom">
                    <a href="?page=majesticsupport" class="close-button">
                        <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/postinstallation/close-icon.png';?>" />
                    </a>
                </div>
            </div>
            <div class="post-installtion-content-wrapper">
                <div class="post-installtion-content-header">
                    <ul class="update-header-img step-1">
                        <li class="header-parts first-part">
                            <a href="<?php echo esc_url(admin_url("admin.php?page=majesticsupport_postinstallation&mjslay=stepone")); ?>" title="link" class="tab_icon">
                                <img class="start" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/postinstallation/general-settings.png';?>" />
                                <span class="text"><?php echo esc_html(__('General','majestic-support')); ?></span>
                            </a>
                        </li>
                        <li class="header-parts second-part">
                            <a href="<?php echo esc_url(admin_url("admin.php?page=majesticsupport_postinstallation&mjslay=steptwo")); ?>" title="link" class="tab_icon">
                                <img class="start" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/postinstallation/ticket.png';?>" />
                                <span class="text"><?php echo esc_html(__('Ticket Settings','majestic-support')); ?></span>
                            </a>
                        </li>
                        <?php if($tran_data){ ?>
                            <li class="header-parts third-part active">
                               <a href="<?php echo esc_url(admin_url("admin.php?page=majesticsupport_postinstallation&mjslay=translationoption")); ?>" title="link" class="tab_icon">
                                   <img class="start" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/postinstallation/translation.png';?>" />
                                    <span class="text"><?php echo esc_html(__('Translation','majestic-support')); ?></span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if(in_array('feedback', majesticsupport::$_active_addons)){ ?>
                            <li class="header-parts third-part">
                               <a href="<?php echo esc_url(admin_url("admin.php?page=majesticsupport_postinstallation&mjslay=stepthree")); ?>" title="link" class="tab_icon">
                                   <img class="start" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/postinstallation/feedback.png';?>" />
                                    <span class="text"><?php echo esc_html(__('Feedback Settings','majestic-support')); ?></span>
                                </a>
                            </li>
                        <?php } ?>
                        <li class="header-parts forth-part">
                            <a href="<?php echo esc_url(admin_url("admin.php?page=majesticsupport_postinstallation&mjslay=settingcomplete")); ?>" title="link" class="tab_icon">
                               <img class="start" src="<?php echo esc_url(MJTC_PLUGIN_URL).'includes/images/postinstallation/complete.png';?>" />
                                <span class="text"><?php echo esc_html(__('Complete','majestic-support')); ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="post-installtion-content_wrapper_right">
                    <div class="ms-config-topheading">
                        <span class="heading-post-ins ms-configurations-heading"><?php echo esc_html(__('Download Translation File','majestic-support'));?></span>
                    </div>
                    <div class="post-installtion-content">
                        <div id="black_wrapper_translation"></div>
                        <div id="mstran_loading">
                            <img alt="<?php echo esc_html(__('spinning wheel','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/spinning-wheel.gif" />
                        </div>
                        <form id="majesticsupport-form-ins" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_postinstallation&task=save&action=mstask"),"save")); ?>">
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo esc_html(__('Language code','majestic-support'));?>:
                                </div>
                                <div class="field">
                                    <?php echo wp_kses(MJTC_formfield::MJTC_text('codelang', isset($tran_data->code) ? $tran_data->lang_fullname . " (" . $tran_data->code . ")" : '' , array('class' => 'inputbox ms-postsetting', 'data-validation' => 'required' , 'readonly' => true)), MJTC_ALLOWED_TAGS) ?>
                                </div>
                                <div class="desc">
                                    <?php echo esc_html(__('Want to download translation file? Click on download. It will take sometime.','majestic-support'));?>
                                </div>
                            </div>
                            <div id="mjtc-emessage-wrapper">
                                <img alt="<?php echo esc_html(__('c error','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/c_error.png" />
                                <div id="jslang_em_text"></div>
                            </div>
                            <div id="mjtc-emessage-wrapper_ok">
                                <img alt="<?php echo esc_html(__('saved','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/saved.png" />
                                <div id="jslang_em_text_ok"></div>
                            </div>
                            <div class="pic-button-part pic-3-button">
                                <a class="next-step" href="javascript:void(0);" id="jsdownloadbutton">
                                    <?php echo esc_html(__('Download & Next','majestic-support')); ?>
                                </a>
                                <a class="skip-step" href="javascript:void(0);" onclick="document.getElementById('majesticsupport-form-ins').submit();">
                                    <?php echo esc_html(__('Skip this step','majestic-support')); ?>
                                </a>
                            </div>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('action', 'postinstallation_save'), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('step', 'translationoption'), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('translations', isset($tran_data->name->lang_name) ? $tran_data->name->lang_name: ''), MJTC_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(MJTC_formfield::MJTC_hidden('languagecode', isset($tran_data->code) ? $tran_data->code: ''), MJTC_ALLOWED_TAGS); ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$majesticsupport_js ="
    jQuery(document).ready(function(){
        jQuery('#jsdownloadbutton').click(function(){
            jQuery('#mjtc-emessage-wrapper_ok').hide();
            var lang_name = jQuery('#translations').val();
            var file_name = jQuery('#languagecode').val();
            if(lang_name != '' && file_name != ''){
                jsShowLoading();
                jQuery.post(ajaxurl, {action: 'mjsupport_ajax', mjsmod: 'majesticsupport', task: 'getlanguagetranslation',langname:lang_name , filename: file_name, '_wpnonce':'". esc_attr(wp_create_nonce("get-language-translation"))."'}, function (data) {
                    if (data) {
                        jsHideLoading();
                        data = JSON.parse(data);
                        if(data['error']){
                            jQuery('#mjtc-emessage-wrapper div').html('File not be able to download');
                            jQuery('#mjtc-emessage-wrapper').show();
                        }else{
                            jQuery('#mjtc-emessage-wrapper').hide();
                            jQuery('#mjtc-emessage-wrapper_ok div').html('File Downloaded Successfully');
                            jQuery('#mjtc-emessage-wrapper_ok').slideDown();
                            document.getElementById('majesticsupport-form-ins').submit();
                        }
                    }
                });
            }
        });
    });

    function jsShowLoading(){
        jQuery('div#black_wrapper_translation').show();
        jQuery('div#mstran_loading').show();
    }

    function jsHideLoading(){
        jQuery('div#black_wrapper_translation').hide();
        jQuery('div#mstran_loading').hide();
    }

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  
