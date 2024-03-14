<?php
   if(!defined('ABSPATH'))
    die('Restricted Access');

/* Note
* WP auto translate it from it file, no need to add majestic-support as text domain
*/
?>
<div id="MJTC_majesticsupportadmin-wrapper">
    <?php MJTC_message::MJTC_getMessage(); ?>

<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('translations'); ?>
        <div id="msadmin-data-wrp" class="p0">
            <div id="black_wrapper_translation"></div>
            <div id="mstran_loading">
                <img alt="<?php echo esc_html(__('spinning wheel','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/spinning-wheel.gif" />
            </div>

            <div id="mjtc-language-wrapper">
                <div class="mstopheading"><?php echo esc_html(__('Get')).' Majestic Support '.esc_html(__('Translations')); ?></div>
                <div id="gettranslation" class="gettranslation"><img alt="<?php echo esc_html(__('Download')); ?>" style="width:18px; height:auto;" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/download-icon.png" /><?php echo esc_html(__('Get')).' '.esc_html(__('Translations')); ?></div>
                <div id="mjtc_ddl">
                    <span class="title"><?php echo esc_html(__('Select')).' '.esc_html(__('Translation')); ?>:</span>
                    <span class="combo" id="mjtc_combo"></span>
                    <span class="button" id="jsdownloadbutton"><img alt="<?php echo esc_html(__('Download')); ?>" style="width:14px; height:auto;" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/download-icon.png" /><?php echo esc_html(__('Download')); ?></span>
                    <div id="mscodeinputbox" class="mjtc-some-disc"></div>
                    <div class="mjtc-some-disc"><img alt="<?php echo esc_html(__('info','majestic-support')); ?>" style="width:18px; height:auto;" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/info-icon.png" /><?php echo esc_html(__('When WordPress language change to fr, Majestic Support language will auto change to fr','majestic-support')); ?></div>
                </div>
                <div id="mjtc-emessage-wrapper">
                    <img alt="<?php echo esc_html(__('c error','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/c_error.png" />
                    <div id="jslang_em_text"></div>
                </div>
                <div id="mjtc-emessage-wrapper_ok">
                    <img alt="<?php echo esc_html(__('saved','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/saved.png" />
                    <div id="jslang_em_text_ok"></div>
                </div>
            </div>
            <div id="mjtc-lang-toserver">
                <div class="col"><a class="anc one" href="#" target="_blank" title="<?php echo esc_attr(__('Contribute In Translation','majestic-support')); ?>"><img alt="<?php echo esc_html(__('translate','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/translation-icon.png" /><?php echo esc_html(__('Contribute In Translation','majestic-support')); ?></a></div>
                <div class="col"><a class="anc two" href="http://www.joomsky.com/translations.html" target="_blank" title="<?php echo esc_attr(__('Manual Download','majestic-support')); ?>"><img alt="<?php echo esc_html(__('Manual Download','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/manual-download.png" /><?php echo esc_html(__('Manual Download','majestic-support')); ?></a></div>
            </div>
        </div>
</div>

<?php
$majesticsupport_js ="
    var ajaxurl = '". esc_url(admin_url('admin-ajax.php'))."';
    jQuery(document).ready(function(){
        jQuery('#gettranslation').click(function(){
            jsShowLoading();
            jQuery.post(ajaxurl, {action: 'mjsupport_ajax', mjsmod: 'majesticsupport', task: 'getListTranslations', '_wpnonce':'". esc_attr(wp_create_nonce("get-list-translations"))."'}, function (data) {
                if (data) {
                    console.log(data);
                    jsHideLoading();
                    data = JSON.parse(data);
                    if(data['error']){
                        jQuery('#mjtc-emessage-wrapper div').html(data['error']);
                        jQuery('#mjtc-emessage-wrapper').show();
                    }else{
                        jQuery('#mjtc-emessage-wrapper').hide();
                        jQuery('#gettranslation').hide();
                        jQuery('div#mjtc_ddl').show();
                        jQuery('span#mjtc_combo').html(MJTC_msDecodeHTML(data['data']));
                    }
                }
            });
        });

        jQuery(document).on('change', 'select#translations' ,function() {
            var lang_name = jQuery( this ).val();
            if(lang_name != ''){
                jQuery('#mjtc-emessage-wrapper_ok').hide();
                jsShowLoading();
                jQuery.post(ajaxurl, {action: 'mjsupport_ajax', mjsmod: 'majesticsupport', task: 'validateandshowdownloadfilename',langname:lang_name, '_wpnonce':'". esc_attr(wp_create_nonce("validate-and-show-download-filename"))."'}, function (data) {
                    console.log(data);
                    if (data) {
                        jsHideLoading();
                        data = JSON.parse(data);
                        if(data['error']){
                            jQuery('#mjtc-emessage-wrapper div').html(data['error']);
                            jQuery('#mjtc-emessage-wrapper').show();
                            jQuery('#mscodeinputbox').slideUp('400' , 'swing' , function(){
                                jQuery('input#languagecode').val('');
                            });
                        }else{
                            jQuery('#mjtc-emessage-wrapper').hide();
                            jQuery('#mscodeinputbox').html(data['path']+': '+MJTC_msDecodeHTML(data['input']));
                            jQuery('#mscodeinputbox').slideDown();
                        }
                    }
                });
            }
        });

        jQuery('#jsdownloadbutton').click(function(){
            jQuery('#mjtc-emessage-wrapper_ok').hide();
            var lang_name = jQuery('#translations').val();
            var file_name = jQuery('#languagecode').val();
            if(lang_name != '' && file_name != ''){
                jsShowLoading();
                jQuery.post(ajaxurl, {action: 'mjsupport_ajax', mjsmod: 'majesticsupport', task: 'getlanguagetranslation',langname:lang_name , filename: file_name,langname:lang_name , filename: file_name, '_wpnonce':'". esc_attr(wp_create_nonce("get-language-translation"))."'}, function (data) {
                    if (data) {
                        console.log(data);
                        jsHideLoading();
                        data = JSON.parse(data);
                        if(data['error']){
                            jQuery('#mjtc-emessage-wrapper div').html(data['error']);
                            jQuery('#mjtc-emessage-wrapper').show();
                        }else{
                            jQuery('#mjtc-emessage-wrapper').hide();
                            jQuery('#mjtc-emessage-wrapper_ok div').html(data['data']);
                            jQuery('#mjtc-emessage-wrapper_ok').slideDown();
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
</div>
</div>
