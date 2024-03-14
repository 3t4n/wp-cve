<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script >
    jQuery(document).ready(function() {
        //for notifications
        jQuery("div.notifications").hide();
                jQuery("img.notifications").on("click", function(){
        jQuery("div.notifications, div.notifications").slideToggle();
        });
                jQuery("span.count_notifications").on("click", function(){
        jQuery("div.notifications, div.notifications").slideToggle();
        });
                //for messages
                jQuery("div.messages").hide();
                jQuery("img.messages").on("click", function(){
        jQuery("div.messages, div.messages").slideToggle();
        });
                jQuery("span.count_messages").on("click", function(){
        jQuery("div.messages, div.messages").slideToggle();
        });
                jQuery('div#jsjobs-popup-background, img#popup_cross').click(function(){
        jQuery('div#jsjobs-popup').hide();
                jQuery('div#jsjobs-popup-background').hide();
        });
    });

</script>
<script >

    function showLoginPopup(){
        jQuery('div#jsjobs-popup-background').show();
        jQuery('div#jsjobs-popup').show();
    }

</script>
<div id="jsjobs-popup-background"></div>
<div id="jsjobs-popup" class="loginpopup">
    <span class="popup-title"><?php echo __('Login','js-jobs'); ?><img id="popup_cross" src="<?php echo JSJOBS_PLUGIN_URL . 'includes/images/popup-close.png'; ?>"></span>
    <div class="popup-row name">
        <div class="login-heading"><?php echo __('Login into your account', 'js-jobs'); ?></div>
        <?php
            if (!is_user_logged_in()) { // Display WordPress login form:
                $args = array(
                    'redirect' => get_permalink(),
                    'form_id' => 'loginform-custom',
                    'label_username' => __('Username', 'js-jobs'),
                    'label_password' => __('Password', 'js-jobs'),
                    'label_remember' => __('keep me login', 'js-jobs'),
                    'label_log_in' => __('Login', 'js-jobs'),
                    'remember' => true
                );
                wp_login_form($args);
            }
        ?>
        <div id="status"></div>   
    </div>
    <div class="loginintocomment">
        <hr class="loginhr"/>
        <span class="logintext"><?php echo __('Login into your account', 'js-jobs'); ?></span>
    </div>
    <div class="popup-row button">
        <?php
            if (!is_user_logged_in()) {
                $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('login');
                if ($config_array['loginwithfacebook'] == 1) {
                    $wpnonce = wp_create_nonce("social-login");
                    echo '<a class="sociallogin facebook" href="' . esc_url(jsjobs::makeUrl(array('jsjobsme'=>'user', 'action'=>'jsjobtask', 'task'=>'sociallogin', 'media'=>'facebook', 'jsjobspageid'=>jsjobs::getPageid(), '_wpnonce'=>$wpnonce))) . '" ><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/scico/fb.png"/>' . __('Login with facebook', 'js-jobs') . '</a>';
                }
                if ($config_array['loginwithlinkedin'] == 1) {
                    $wpnonce = wp_create_nonce("social-login");
                    echo '<a class="sociallogin linkedin" href="' . esc_url(jsjobs::makeUrl(array('jsjobsme'=>'user', 'action'=>'jsjobtask', 'task'=>'sociallogin', 'media'=>'linkedin', 'jsjobspageid'=>jsjobs::getPageid(), '_wpnonce'=>$wpnonce))) . '" ><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/scico/in.png"/>' . __('Login with linkedin', 'js-jobs') . '</a>';
                }
                if ($config_array['loginwithxing'] == 1) {
                    $wpnonce = wp_create_nonce("social-login");
                    echo '<a class="sociallogin xing" href="' . esc_url(jsjobs::makeUrl(array('jsjobsme'=>'user', 'action'=>'jsjobtask', 'task'=>'sociallogin', 'media'=>'xing', 'jsjobspageid'=>jsjobs::getPageid(), '_wpnonce'=>$wpnonce))) . '" ><img src="' . JSJOBS_PLUGIN_URL . 'includes/images/scico/xing.png"/>' . __('Login with xing', 'js-jobs') . '</a>';
                }            
            } 
        ?>
    </div>
</div>

<?php

function jobseekercheckLinks($name) {
    $print = false;
    switch ($name) {
        case 'formresume': $visname = 'vis_jsformresume';
            break;
        case 'jobcat': $visname = 'vis_jsjobcat';
            break;
        case 'myresumes': $visname = 'vis_jsmyresumes';
            break;
        case 'listnewestjobs': $visname = 'vis_jslistnewestjobs';
            break;
        case 'listallcompanies': $visname = 'vis_jslistallcompanies';
            break;
        case 'listjobbytype': $visname = 'vis_jslistjobbytype';
            break;
        case 'formcoverletter': $visname = 'vis_jsformcoverletter';
            break;
        case 'myappliedjobs': $visname = 'vis_jsmyappliedjobs';
            break;
        case 'mycoverletters': $visname = 'vis_jsmycoverletters';
            break;
        case 'jobsearch': $visname = 'vis_jsjobsearch';
            break;
        case 'jsmy_stats': $visname = 'vis_jsmy_stats';
            break;
        case 'jsregister': $visname = 'vis_jsregister';
            break;
        case 'jsmystats': $visname = 'vis_jsmystats';
            break;
        case 'jobsloginlogout': $visname = 'jobsloginlogout';
            break;
        case 'temp_jobseeker_dashboard_jobs_graph': $visname = 'vis_temp_jobseeker_dashboard_jobs_graph';
            break;
        case 'temp_jobseeker_dashboard_useful_links': $visname = 'vis_temp_jobseeker_dashboard_useful_links';
            break;
        case 'temp_jobseeker_dashboard_apllied_jobs': $visname = 'vis_temp_jobseeker_dashboard_apllied_jobs';
            break;
        case 'temp_jobseeker_dashboard_shortlisted_jobs': $visname = 'vis_temp_jobseeker_dashboard_shortlisted_jobs';
            break;
        case 'temp_jobseeker_dashboard_credits_log': $visname = 'vis_temp_jobseeker_dashboard_credits_log';
            break;
        case 'temp_jobseeker_dashboard_purchase_history': $visname = 'vis_temp_jobseeker_dashboard_purchase_history';
            break;
        case 'temp_jobseeker_dashboard_newest_jobs': $visname = 'vis_temp_jobseeker_dashboard_newest_jobs';
            break;

        default:$visname = 'vis_js' . $name;
            break;
    }

    $isouruser = JSJOBSincluder::getObjectClass('user')->isJSJobsUser();
    $isguest = JSJOBSincluder::getObjectClass('user')->isguest();

    $guest = false;

    if($isguest == true){
        $guest = true;
    }
    if($isguest == false && $isouruser == false){
        $guest = true;
    }

    $config_array = jsjobs::$_data['config'];

    if ($guest == false) {
        if (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
            if (isset($config_array[$name]) && $config_array[$name] == 1)
                $print = true;
        }elseif (JSJOBSincluder::getObjectClass('user')->isemployer()) {
            if ($config_array['employerview_js_controlpanel'] == 1)
                if (isset($config_array["$visname"]) && $config_array["$visname"] == 1) {
                    $print = true;
                }
        }
    } else {
        if (isset($config_array["$visname"]) && $config_array["$visname"] == 1)
            $print = true;
    }
    return $print;
}

?>
