<?php
   if(!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <?php
    $msgkey = JSJOBSincluder::getJSModel('jsjobs')->getMessagekey();
    JSJOBSMessages::getLayoutMessage($msgkey);
    ?>
    <span class="js-admin-title">
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
        <?php echo __('Translations'); ?>
    </span>
    <div id="jsjobs-content">
        <div id="black_wrapper_translation"></div>
        <div id="jstran_loading">
            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/spinning-wheel.gif" />
        </div>

        <div id="js-language-wrapper">
            <div class="jstopheading"><?php echo __('Get JS Jobs ', 'js-jobs') . __('Translations');?></div>
            <div id="gettranslation" class="gettranslation"><img style="width:18px; height:auto;" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/download-icon.png" /><?php echo __('Get','js-jobs') . ' ' . __('Translations');?></div>
            <div id="js_ddl">
                <span class="title"><?php echo __('Select Translation','js-jobs');?>:</span>
                <span class="combo" id="js_combo"></span>
                <span class="button" id="jsdownloadbutton"><img style="width:14px; height:auto;" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/download-icon.png" /><?php echo __('Download','js-jobs');?></span>
                <div id="jscodeinputbox" class="js-some-disc"></div>
                <div class="js-some-disc"><img style="width:18px; height:auto;" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/info-icon.png" /><?php echo __('When WordPress language change to ro, JS Jobs language will auto change to ro');?></div>
            </div>
            <div id="js-emessage-wrapper">
                <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/c_error.png" />
                <div id="jslang_em_text"></div>
            </div>
            <div id="js-emessage-wrapper_ok">
                <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/saved.png" />
                <div id="jslang_em_text_ok"></div>
            </div>
        </div>
        <div id="js-lang-toserver">
            <div class="js-col-xs-12 js-col-md-8 col"><a class="anc one" href="https://www.transifex.com/joom-sky/js_jobs" target="_blank"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/translation-icon.png" /><?php echo __('Contribute In Translation','js-jobs');?></a></div>
            <div class="js-col-xs-12 js-col-md-4 col"><a class="anc two" href="http://www.joomsky.com/translations.html" target="_blank"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/manual-download.png" /><?php echo __('Manual Download','js-jobs');?></a></div>
        </div>
    </div>
</div>
</div>

<script >
    var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
    jQuery(document).ready(function(){
        jQuery('#gettranslation').click(function(){
            jsShowLoading();
            jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'jsjobs', task: 'getListTranslations',wpnoncecheck:common.wp_jm_nonce}, function (data) {
                if (data) {
                    jsHideLoading();
                    data = JSON.parse(data);
                    if(data['error']){
                        jQuery('#js-emessage-wrapper div').html(data['error']);
                        jQuery('#js-emessage-wrapper').show();
                    }else{
                        var decodeHTML = function (html) {
                            var txt = document.createElement('textarea');
                            txt.innerHTML = html;
                            return txt.value;
                        };
                        jQuery('#js-emessage-wrapper').hide();
                        jQuery('#gettranslation').hide();
                        jQuery('div#js_ddl').show();
                        jQuery('span#js_combo').html(decodeHTML(data['data']));
                    }
                }
            });
        });

        jQuery(document).on('change', 'select#translations' ,function() {
            var lang_name = jQuery( this ).val();
            if(lang_name != ''){
                jQuery('#js-emessage-wrapper_ok').hide();
                jsShowLoading();
                jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'jsjobs', task: 'validateandshowdownloadfilename',langname:lang_name, wpnoncecheck:common.wp_jm_nonce}, function (data) {
                    if (data) {
                        jsHideLoading();
                        data = JSON.parse(data);
                        if(data['error']){
                            jQuery('#js-emessage-wrapper div').html(data['error']);
                            jQuery('#js-emessage-wrapper').show();
                            jQuery('#jscodeinputbox').slideUp('400' , 'swing' , function(){
                                jQuery('input#languagecode').val("");
                            });
                        }else{
                            jQuery('#js-emessage-wrapper').hide();
                            jQuery('#jscodeinputbox').html(data['path']+': '+data['input']);
                            jQuery('#jscodeinputbox').slideDown();
                        }
                    }
                });
            }
        });

        jQuery('#jsdownloadbutton').click(function(){
            jQuery('#js-emessage-wrapper_ok').hide();
            var lang_name = jQuery('#translations').val();
            var file_name = jQuery('#languagecode').val();
            if(lang_name != '' && file_name != ''){
                jsShowLoading();
                jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'jsjobs', task: 'getlanguagetranslation',langname:lang_name , filename: file_name, wpnoncecheck:common.wp_jm_nonce}, function (data) {
                    if (data) {
                        jsHideLoading();
                        data = JSON.parse(data);
                        if(data['error']){
                            jQuery('#js-emessage-wrapper div').html(data['error']);
                            jQuery('#js-emessage-wrapper').show();
                        }else{
                            jQuery('#js-emessage-wrapper').hide();
                            jQuery('#js-emessage-wrapper_ok div').html(data['data']);
                            jQuery('#js-emessage-wrapper_ok').slideDown();
                        }
                    }
                });
            }
        });
    });

    function jsShowLoading(){
        jQuery('div#black_wrapper_translation').show();
        jQuery('div#jstran_loading').show();
    }

    function jsHideLoading(){
        jQuery('div#black_wrapper_translation').hide();
        jQuery('div#jstran_loading').hide();
    }
</script>
