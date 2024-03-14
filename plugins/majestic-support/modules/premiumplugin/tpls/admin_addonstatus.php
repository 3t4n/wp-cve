<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
    require_once MJTC_PLUGIN_PATH.'includes/addon-updater/msupdater.php';
    $MJTC_SUPPORTTICKETUpdater  = new MJTC_SUPPORTTICKETUpdater();
    $cdnversiondata = $MJTC_SUPPORTTICKETUpdater->MJTC_getPluginVersionDataFromCDN();
    $not_installed = array();

    $majesticsupport_addons = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_getAddonsArray();
?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('admin_addons_status'); ?>
    	<div id="msadmin-data-wrp"class="msadmin-addons-list-data">
    		<!-- admin addons status -->
            <div id="black_wrapper_translation"></div>
            <div id="mstran_loading">
                <img alt="<?php echo esc_html(__('spinning wheel','majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/spinning-wheel.gif" />
            </div>
            <div class="msadmin-addons-list-wrp">
                <?php
                $installed_plugins = get_plugins();
                ?>
                <?php
                    foreach ($majesticsupport_addons as $key1 => $value1) {
                        $matched = 0;
                        $version = "";
                        foreach ($installed_plugins as $name => $value) {
                            $install_plugin_name = MJTC_majesticsupportphplib::MJTC_str_replace(".php","",MJTC_majesticsupportphplib::MJTC_basename($name));
                            if($key1 == $install_plugin_name){
                                $matched = 1;
                                $version = $value["Version"];
                                $install_plugin_matched_name = $install_plugin_name;
                            }
                        }
                        if($matched == 1){ //installed
                            $name = $key1;
                            $title = $value1['title'];
                            $img = MJTC_majesticsupportphplib::MJTC_str_replace("majestic-support-", "", $key1).'.png';
                            $cdnavailableversion = "";
                            foreach ($cdnversiondata as $cdnname => $cdnversion) {
                                $install_plugin_name_simple = MJTC_majesticsupportphplib::MJTC_str_replace("-", "", $install_plugin_matched_name);
                                if($cdnname == MJTC_majesticsupportphplib::MJTC_str_replace("-", "", $install_plugin_matched_name)){
                                    if($cdnversion > $version){ // new version available
                                        $status = 'update_available';
                                        $cdnavailableversion = $cdnversion;
                                    }else{
                                        $status = 'updated';
                                    }
                                }    
                            }
                            mjtc_printAddoneStatus($name, $title, $img, $version, $status, $cdnavailableversion);
                        }else{ // not installed
                            $img = MJTC_majesticsupportphplib::MJTC_str_replace("majestic-support-", "", $key1).'.png';
                            $not_installed[] = array("name" => $key1, "title" => $value1['title'], "img" => $img, "status" => 'not-installed', "version" => "---");
                        }
                    }
                    foreach ($not_installed as $notinstall_addon) {
                        mjtc_printAddoneStatus($notinstall_addon["name"], $notinstall_addon["title"], $notinstall_addon["img"], $notinstall_addon["version"], $notinstall_addon["status"]);
                    }
                ?>
            </div>
		</div>
	</div>
</div>

<?php
function mjtc_printAddoneStatus($name, $title, $img, $version, $status, $cdnavailableversion = ''){
    $addoneinfo = MJTC_includer::MJTC_getModel('premiumplugin')->MJTC_checkAddoneInfo($name);
    if ($status == 'update_available') {
        $wrpclass = 'ms-admin-addon-status ms-admin-addons-status-update-wrp';
        $btnclass = 'ms-admin-addons-update-btn';
        $btntxt = 'Update Now';
        $btnlink = 'id="ms-admin-addons-update" data-for="'.esc_attr($name).'"';
        $msg = '<span id="ms-admin-addon-status-cdnversion">'.esc_html(__('New Update Version','majestic-support'));
        $msg .= '<span>'." ".$cdnavailableversion." ".'</span>';
        $msg .= esc_html(__('is Available','majestic-support')).'</span>';
    } elseif ($status == 'expired') {
        $wrpclass = 'ms-admin-addon-status ms-admin-addons-status-expired-wrp';
        $btnclass = 'ms-admin-addons-expired-btn';
        $btntxt = 'Expired';
        $btnlink = '';
        $msg = '';
    } elseif ($status == 'updated') {
        $wrpclass = 'ms-admin-addon-status';
        $btnclass = '';
        $btntxt = 'Updated';
        $btnlink = '';
        $msg = '';
    } else {
        $wrpclass = 'ms-admin-addon-status';
        $btnclass = 'ms-admin-addons-buy-btn';
        $btntxt = 'Buy Now';
        $btnlink = 'href="https://majesticsupport.com/add-ons/"';
        $msg = '';
    }
    $html = '
    <div class="'.esc_attr($wrpclass).'" id="'.esc_attr($name).'">
        <div class="ms-addon-status-image-wrp">
            <img alt="Addone image" src="'.esc_url(MJTC_PLUGIN_URL).'includes/images/admincp/addon/'.esc_attr($img).'" />
        </div>
        <div class="ms-admin-addon-status-title-wrp">
            <h2>'. esc_html(majesticsupport::MJTC_getVarValue($title)) .'</h2>
            <a class="'. esc_attr($addoneinfo["actionClass"]) .'" href="'. esc_url($addoneinfo["url"]) .'">
                '. esc_html(majesticsupport::MJTC_getVarValue($addoneinfo["action"])) .'
            </a>
            '.wp_kses($msg, MJTC_ALLOWED_TAGS).'
        </div>
        <div class="ms-admin-addon-status-addonstatus-wrp">
            <span>'. esc_html(__('Status: ','majestic-support')) .'</span>
            <span class="ms-admin-adons-status-Active" href="#">
                '. esc_html(majesticsupport::MJTC_getVarValue($addoneinfo["status"])) .'
            </span>
        </div>
        <div class="ms-admin-addon-status-addonsversion-wrp">
            <span id="ms-admin-addon-status-cversion">
                '. esc_html(__('Version','majestic-support')).': 
                <span>
                    '. esc_html($version) .'
                </span>
            </span>
        </div>
        <div class="msadmin-addon-status-addonstatusbtn-wrp">
            <a '.esc_attr($btnlink).' class="'.esc_attr($btnclass).'">'.esc_html(majesticsupport::MJTC_getVarValue($btntxt)) .'</a>
        </div>
        <div class="msadmin-addon-status-msg msadmin_success">
            <img src="'. esc_url(MJTC_PLUGIN_URL) .'includes/images/admincp/addon/success.png" />
            <span class="msadmin-addon-status-msg-txt"></span>
        </div>
        <div class="msadmin-addon-status-msg msadmin_error">
            <img src="'. esc_url(MJTC_PLUGIN_URL) .'includes/images/admincp/addon/error.png" />
            <span class="msadmin-addon-status-msg-txt"></span>
        </div>
    </div>';
        echo wp_kses($html, MJTC_ALLOWED_TAGS);
    }

?>
<?php
$majesticsupport_js ="
    jQuery(document).ready(function(){
        jQuery(document).on('click', 'a#ms-admin-addons-update', function(){
            jsShowLoading();
            var dataFor = jQuery(this).attr('data-for');
            var cdnVer = jQuery('#'+ dataFor +' #ms-admin-addon-status-cdnversion span').text();
            var currentVer = jQuery('#'+ dataFor +' #ms-admin-addon-status-cversion span').text();
            var cdnVersion = cdnVer.trim();
            var currentVersion = currentVer.trim();
            jQuery.post(ajaxurl, {action: 'mjsupport_ajax', mjsmod: 'premiumplugin', task: 'downloadandinstalladdonfromAjax', dataFor:dataFor, currentVersion:currentVersion, cdnVersion:cdnVersion, '_wpnonce':'". esc_attr(wp_create_nonce("download-and-install-addon"))."'}, function (data) {
                if (data) {
                    jsHideLoading();
                    data = JSON.parse(data);
                    if(data['error']){
                        jQuery('#' + dataFor).css('background-color', '#fff');
                        jQuery('#' + dataFor).css('border-color', '#FF4F4E');
                        jQuery('#' + dataFor + ' .ms-admin-addon-status-title-wrp span').hide();
                        jQuery('#' + dataFor + ' .msadmin-addon-status-msg.msadmin_error').show();
                        jQuery('#' + dataFor + ' .msadmin-addon-status-msg.msadmin_error span.msadmin-addon-status-msg-txt').html(data['error']);
                        jQuery('#' + dataFor + ' .msadmin-addon-status-msg.mmsadmin_error').slideDown('slow');
                    } else if(data['success']) {
                        jQuery('#' + dataFor).css('background-color', '#fff');
                        jQuery('#' + dataFor).css('border-color', '#0C6E45');
                        jQuery('#' + dataFor + ' a#ms-admin-addons-update').hide();
                        jQuery('#' + dataFor + ' .ms-admin-addon-status-title-wrp span').hide();
                        jQuery('#' + dataFor + ' .msadmin-addon-status-msg.msadmin_success').show();
                        jQuery('#' + dataFor + ' .msadmin-addon-status-msg.msadmin_success span.msadmin-addon-status-msg-txt').html(data['success']);
                        jQuery('#' + dataFor + ' .msadmin-addon-status-msg.msadmin_success').slideDown('slow');
                    }
                }
            });
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
