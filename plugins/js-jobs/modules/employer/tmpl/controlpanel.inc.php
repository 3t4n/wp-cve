<?php if (!defined('ABSPATH')) die('Restricted Access');
do_action('wp_enqueue_scripts');
?>
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
    var counter = jQuery('span.count_notifications').text();
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
    google.load("visualization", "1", {packages:["corechart"]});

    function showLoginPopup(){
        jQuery('div#jsjobs-popup-background').show();
        jQuery('div#jsjobs-popup').show();
    }
</script>
<?php
function employercheckLinks($name) {
    $print = false;
    switch ($name) {
        case 'formcompany': $visname = 'vis_emformcompany';
        break;
        case 'alljobsappliedapplications': $visname = 'vis_emalljobsappliedapplications';
        break;
        case 'mycompanies': $visname = 'vis_emmycompanies';
        break;
        case 'resumesearch': $visname = 'vis_emresumesearch';
        break;
        case 'formjob': $visname = 'vis_emformjob';
        break;
        case 'my_resumesearches': $visname = 'vis_emmy_resumesearches';
        break;
        case 'myjobs': $visname = 'vis_emmyjobs';
        break;
        case 'formdepartment': $visname = 'vis_emformdepartment';
        break;
        case 'my_stats': $visname = 'vis_emmy_stats';
        break;
        case 'empresume_rss': $visname = 'vis_resume_rss';
        break;
        case 'newfolders': $visname = 'vis_emnewfolders';
        break;
        case 'empregister': $visname = 'vis_emempregister';
        break;
        case 'empcredits': $visname = 'vis_empcredits';
        break;
        case 'empcreditlog': $visname = 'vis_empcreditlog';
        break;
        case 'emppurchasehistory': $visname = 'vis_emppurchasehistory';
        break;
        case 'empmessages': $visname = 'vis_emmessages';
        break;
        case 'empregister': $visname = 'vis_emregister';
        break;
        case 'empratelist': $visname = 'vis_empratelist';
        break;
        case 'jobs_graph': $visname = 'vis_jobs_graph';
        break;
        case 'resume_graph': $visname = 'vis_resume_graph';
        break;
        case 'box_newestresume': $visname = 'vis_box_newestresume';
        break;
        case 'box_appliedresume': $visname = 'vis_box_appliedresume';
        break;
        case 'emploginlogout': $visname = 'emploginlogout';
        break;
        case 'empmystats': $visname = 'vis_empmystats';
        break;
        case 'emresumebycategory': $visname = 'vis_emresumebycategory';
        break;
        case 'temp_employer_dashboard_stats_graph': $visname = 'vis_temp_employer_dashboard_stats_graph';
        break;
        case 'temp_employer_dashboard_useful_links': $visname = 'vis_temp_employer_dashboard_useful_links';
        break;
        case 'temp_employer_dashboard_applied_resume': $visname = 'vis_temp_employer_dashboard_applied_resume';
        break;
        case 'temp_employer_dashboard_saved_search': $visname = 'vis_temp_employer_dashboard_saved_search';
        break;
        case 'temp_employer_dashboard_credits_log': $visname = 'vis_temp_employer_dashboard_credits_log';
        break;
        case 'temp_employer_dashboard_purchase_history': $visname = 'vis_temp_employer_dashboard_purchase_history';
        break;
        case 'temp_employer_dashboard_newest_resume': $visname = 'vis_temp_employer_dashboard_newest_resume';
        break;
        default:$visname = 'vis_em' . $name;
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
        if (JSJOBSincluder::getObjectClass('user')->isemployer()) {
            if ($config_array[$name] == 1)
                $print = true;
        }elseif (JSJOBSincluder::getObjectClass('user')->isjobseeker()) {
            if ($config_array["$visname"] == 1) {
                $print = true;
            }
        }
    } else {
        if ($config_array["$visname"] == 1)
            $print = true;
    }
    return $print;
}

function jobWrapper($resumeid, $path, $first_name, $middle_name, $last_name, $application_title, $email_address, $Category) {
    $html = '<div class="job-wrapper">
    <div class="img">
        <a href="' . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jsjobsid'=>$resumeid)) . '">
            <img src="' . $path . '">
        </a>
    </div>
    <div class="detail">
       <div class="upper">
          <a href="' . jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'viewresume', 'jsjobsid'=>$resumeid)) . '">' . $first_name . ' ' . $middle_name . ' ' . $last_name . '</a>
      </div>
      <div class="lower">
          <div class="resume_title">(' . $application_title . ')</div>
          <div class="for-rtl">
             <span class="text">'. __('Email','js-jobs') .': </span>
             <span class="get-text ">' . $email_address . '</span>
         </div>
         <div class="for-rtl">
             <span class="text">'. __('Category','js-jobs') .': </span>
             <span class="get-text">' . __($Category,'js-jobs') . '</span>
         </div>
     </div>
 </div>		
</div>';
return $html;
}
?>
