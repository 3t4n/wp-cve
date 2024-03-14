<?php

if(!empty($isBackendCall)){

    if(empty($_POST['cgGalleryHash'])){
        echo 0;die;
    }else{

        $galleryHash = $_POST['cgGalleryHash'];
        $galleryHashDecoded = wp_salt( 'auth').'---cngl1---'.$_POST['GalleryID'];
        $galleryHashToCompare = md5($galleryHashDecoded);

        if ($galleryHash != $galleryHashToCompare){
            echo 0;die;
        }

    }

}


$GalleryID = absint($_POST['GalleryID']);

$wp_upload_dir = wp_upload_dir();
$optionsPath = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-options.json';
$options = json_decode(file_get_contents($optionsPath),true);

if(!isset($options['interval'])){
    $options['interval'] = [];
}


$_POST[$_POST['shortcodeType']]['TextWhenShortcodeIntervalIsOn']  = contest_gal1ery_htmlentities_and_preg_replace($_POST[$_POST['shortcodeType']]['TextWhenShortcodeIntervalIsOn']);
$_POST[$_POST['shortcodeType']]['TextWhenShortcodeIntervalIsOff']  = contest_gal1ery_htmlentities_and_preg_replace($_POST[$_POST['shortcodeType']]['TextWhenShortcodeIntervalIsOff']);

$options['interval'][$_POST['shortcodeType']] = $_POST[$_POST['shortcodeType']];

file_put_contents($optionsPath,json_encode($options));

$intervalConf = cg_shortcode_interval_check($GalleryID,$options,$_POST['shortcodeType']);
$isShortcodeIsActive = true;
if(!$intervalConf['shortcodeIsActive']){
    $isShortcodeIsActive = false;
}

$shortcodesToCheck = ['cg_gallery','cg_gallery_user','cg_gallery_no_voting','cg_gallery_winner','cg_users_contact','cg_users_reg','cg_users_login','cg_google_sign_in'];

?>
<script data-cg-processing="true">
    cgJsClassAdmin.index.vars.cgOptionsJson = <?php echo json_encode($options);?>;

    var shortcodeType = <?php echo json_encode($_POST['shortcodeType']);?>;

    var isShortcodeIsActive = <?php echo json_encode($isShortcodeIsActive);?>;
    var shortcodesToCheck = <?php echo json_encode($shortcodesToCheck);?>;
    var shortcodeCheckIsActivated = <?php echo json_encode($intervalConf['shortcodeCheckIsActivated']);?>;

    if(shortcodeCheckIsActivated){
        cgJsClassAdmin.index.vars.isShortcodeIntervalConfActive[shortcodeType] = isShortcodeIsActive;

        if(cgJsClassAdmin.index.vars.isShortcodeIntervalConfActive[shortcodeType]===true){
            jQuery('.td_gallery_info_shortcode_conf_status_on[data-cg-shortcode="'+shortcodeType+'"]').removeClass('cg_hide');
            jQuery('.td_gallery_info_shortcode_conf_status_off[data-cg-shortcode="'+shortcodeType+'"]').addClass('cg_hide');
        }
        if(cgJsClassAdmin.index.vars.isShortcodeIntervalConfActive[shortcodeType]===false){
            jQuery('.td_gallery_info_shortcode_conf_status_off[data-cg-shortcode="'+shortcodeType+'"]').removeClass('cg_hide');
            jQuery('.td_gallery_info_shortcode_conf_status_on[data-cg-shortcode="'+shortcodeType+'"]').addClass('cg_hide');
        }

        var $cg_shortcode_table = jQuery('#cg_shortcode_table');

        for(var shortcodeType in cgJsClassAdmin.index.vars.isShortcodeIntervalConfActive){
            if(!cgJsClassAdmin.index.vars.isShortcodeIntervalConfActive.hasOwnProperty(shortcodeType)){
                break;
            }
            if(cgJsClassAdmin.index.vars.isShortcodeIntervalConfActive[shortcodeType]===true){
                $cg_shortcode_table.find('.td_gallery_info_shortcode_conf_status_on[data-cg-shortcode="'+shortcodeType+'"]').removeClass('cg_hide');
                $cg_shortcode_table.find('.td_gallery_info_shortcode_conf_status_off[data-cg-shortcode="'+shortcodeType+'"]').addClass('cg_hide');
            }
            if(cgJsClassAdmin.index.vars.isShortcodeIntervalConfActive[shortcodeType]===false){
                $cg_shortcode_table.find('.td_gallery_info_shortcode_conf_status_off[data-cg-shortcode="'+shortcodeType+'"]').removeClass('cg_hide');
                $cg_shortcode_table.find('.td_gallery_info_shortcode_conf_status_on[data-cg-shortcode="'+shortcodeType+'"]').addClass('cg_hide');
            }
        }
    }else{
        if(typeof cgJsClassAdmin.index.vars.isShortcodeIntervalConfActive[shortcodeType] != 'undefined'){
            delete cgJsClassAdmin.index.vars.isShortcodeIntervalConfActive[shortcodeType];
        }
    }

    var $cg_shortcode_table = jQuery('#cg_shortcode_table');

    for(var index in shortcodesToCheck){
        if(!shortcodesToCheck.hasOwnProperty(index)){
            break;
        }
        if(typeof cgJsClassAdmin.index.vars.isShortcodeIntervalConfActive[shortcodesToCheck[index]] == 'undefined'){
            $cg_shortcode_table.find('.td_gallery_info_shortcode_conf_status_off[data-cg-shortcode="'+shortcodesToCheck[parseInt(index)]+'"]').addClass('cg_hide');
            $cg_shortcode_table.find('.td_gallery_info_shortcode_conf_status_on[data-cg-shortcode="'+shortcodesToCheck[parseInt(index)]+'"]').addClass('cg_hide');
        }
    }

    cgJsClassAdmin.intervalConf.functions.isShortcodeIntervalConfActiveCheck();


</script>
