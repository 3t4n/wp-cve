<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

//total stats widget start
function jsjobs_dashboard_widgets_totalstats() {

    wp_add_dashboard_widget(
            'jsjobs_dashboard_widgets_totalstats', // Widget slug.
            __('Total Stats', 'js-jobs'), // Title.
            'jsjobs_dashboard_widget_function_totalstats' // Display function.
    );
}

add_action('wp_dashboard_setup', 'jsjobs_dashboard_widgets_totalstats');

function jsjobs_dashboard_widget_function_totalstats() {
    jsjobs::addStyleSheets();
    $data = JSJOBSincluder::getJSModel('jsjobs')->widgetTotalStatsData();
    if ($data == true) {
        $html = '<div id="js-jobs-widget-wrapper">
					<div class="total-stats-widget-data">
						<img class="total-jobs" src="' . JSJOBS_PLUGIN_URL . '/includes/images/control_panel/latest-icons-admin/top-icons/job.png"/>
						<div class="widget-data-right">
							<div class="data-number">	
								' . jsjobs::$_data['widget']['jobs']->totaljobs . '
							</div>
							<div class="data-text">	
								' . __('Jobs', 'js-jobs') . '
							</div>
						</div>	
					</div>
					<div class="total-stats-widget-data">
						<img class="total-companies" src="' . JSJOBS_PLUGIN_URL . '/includes/images/control_panel/latest-icons-admin/top-icons/companies.png"/>
						<div class="widget-data-right">
							<div class="data-number">	
								' . jsjobs::$_data['widget']['companies']->totalcompanies . '
							</div>
							<div class="data-text">	
								' . __('Companies', 'js-jobs') . '
							</div>
						</div>	
					</div>
					<div class="total-stats-widget-data">
						<img class="total-resumes" src="' . JSJOBS_PLUGIN_URL . '/includes/images/control_panel/latest-icons-admin/top-icons/reume.png"/>
						<div class="widget-data-right">
							<div class="data-number">	
								' . jsjobs::$_data['widget']['resumes']->totalresumes . '
							</div>
							<div class="data-text">	
								' . __('Resume', 'js-jobs') . '
							</div>
						</div>	
					</div>
					<div class="total-stats-widget-data">
						<img class="active-jobs" src="' . JSJOBS_PLUGIN_URL . '/includes/images/control_panel/latest-icons-admin/top-icons/active-jobs.png"/>
						<div class="widget-data-right">
							<div class="data-number">	
								' . jsjobs::$_data['widget']['jobs']->activejobs . '
							</div>
							<div class="data-text">	
								' . __('Active Jobs', 'js-jobs') . '
							</div>
						</div>	
					</div>
					<div class="total-stats-widget-data">
						<img class="applied-jobs" src="' . JSJOBS_PLUGIN_URL . '/includes/images/control_panel/latest-icons-admin/top-icons/job-applied.png"/>
						<div class="widget-data-right">
							<div class="data-number">	
								' . jsjobs::$_data['widget']['aplliedjobs']->appliedjobs . '
							</div>
							<div class="data-text">	
								' . __('Applied Jobs', 'js-jobs') . '
							</div>
						</div>	
					</div>
				</div>';
				echo wp_kses($html, JSJOBS_ALLOWED_TAGS);
    } else {
    	$msg = __('No record found','js-jobs');
        JSJOBSlayout::getNoRecordFound($msg);
    }
}

//total stats widge end;
//
//last week stats widget start
//

function jsjobs_dashboard_widgets_last_week_stats() {

    wp_add_dashboard_widget(
            'jsjobs_dashboard_widgets_last_week_stats', // Widget slug.
            __('Last Week Stats', 'js-jobs'), // Title.
            'jsjobs_dashboard_widget_function_lastweestats' // Display function.
    );
}

add_action('wp_dashboard_setup', 'jsjobs_dashboard_widgets_last_week_stats');

function jsjobs_dashboard_widget_function_lastweestats() {
    jsjobs::addStyleSheets();
    $data = JSJOBSincluder::getJSModel('jsjobs')->widgetLastWeekData();
    if ($data == true) {
        $html = '<div id="js-jobs-widget-wrapper">
					<div class="header-date">
						' . jsjobs::$_data['widget']['startdate'] . ' - ' . jsjobs::$_data['widget']['enddate'] . '
					</div>
					<div class="last-week-stats-widget-data" onclick="getWidgetPopup(1)">
						<img class="new-jobs" src="' . JSJOBS_PLUGIN_URL . '/includes/images/control_panel/latest-icons-admin/lower-icons/jobs.png"/>
						<div class="middle-part">
							' . __('New Jobs', 'js-jobs') . '
						<img class="hover-img" src="' . JSJOBS_PLUGIN_URL . '/includes/images/widget-link.png" style="display:none;float:right;"/>
						</div>
						<div class="right-part">
						' . jsjobs::$_data['widget']['newjobs'] . '
						</div>
					</div>

					<div class="last-week-stats-widget-data" onclick="getWidgetPopup(2)">
						<img class="new-companies" src="' . JSJOBS_PLUGIN_URL . '/includes/images/control_panel/latest-icons-admin/lower-icons/companies.png"/>
						<div class="middle-part">
							' . __('New Companies', 'js-jobs') . '
						<img class="hover-img" src="' . JSJOBS_PLUGIN_URL . '/includes/images/widget-link.png" style="display:none;float:right;"/>
						</div>
						<div class="right-part">
						' . jsjobs::$_data['widget']['newcompanies'] . '
						</div>
					</div>

					<div class="last-week-stats-widget-data" onclick="getWidgetPopup(3)">
						<img class="new-resumes" src="' . JSJOBS_PLUGIN_URL . '/includes/images/control_panel/latest-icons-admin/lower-icons/reume.png"/>
						<div class="middle-part">
							' . __('New Resume', 'js-jobs') . '
						<img class="hover-img" src="' . JSJOBS_PLUGIN_URL . '/includes/images/widget-link.png" style="display:none;float:right;"/>
						</div>
						<div class="right-part">
						' . jsjobs::$_data['widget']['newresume'] . '
						</div>
					</div>

					<div class="last-week-stats-widget-data" onclick="getWidgetPopup(4)">
						<img class="job-applied-new" src="' . JSJOBS_PLUGIN_URL . '/includes/images/control_panel/latest-icons-admin/lower-icons/job-applied.png"/>
						<div class="middle-part">
							' . __('Job Applied', 'js-jobs') . '
						<img class="hover-img" src="' . JSJOBS_PLUGIN_URL . '/includes/images/widget-link.png" style="display:none;float:right;"/>
						</div>
						<div class="right-part">
						' . jsjobs::$_data['widget']['newjobapply'] . '
						</div>
					</div>

				</div>';
        $html .='<div id="jsjobsadmin-wrapper">
							<div id="full_background" style="display:none;">
							</div>
						    <div id="popup_main" style="display:none;">
						    </div>
						</div>';

        $ajaxurl = admin_url('admin-ajax.php');

        $html .= '<script>
					jQuery(document).ready(function(){
						jQuery("div#full_background").click(function(){
        				   closePOPUP(); 
       					 });
						jQuery("body").on("click", "img#popup_cross", function(){
						   closePOPUP(); 
       					 });
						jQuery("div.last-week-stats-widget-data").hover(function(){
								jQuery(this).find("img.hover-img").show();
						},function (){
							jQuery(this).find("img.hover-img").hide();
						   }
						);

					});
					function closePOPUP(){
		        		jQuery("div#popup_main").fadeOut(300);
						jQuery("div#full_background").hide();
		        		
					}
					function getWidgetPopup(popupfor){
						var ajaxurl = "' . $ajaxurl . '";
				        jQuery.post(ajaxurl, {action:"jsjobs_ajax",jsjobsme:"jsjobs",task: "getDataForWidgetPopup",dataid:popupfor,wpnoncecheck:common.wp_jm_nonce},function(data){
				            if(data){
				        	    console.log(data);
				        	    jQuery("div#popup_main").html("");
				        	    jQuery("div#full_background").show();
				        		jQuery("div#popup_main").fadeIn(300);
				        		jQuery("div#popup_main").html(data);
				        	}
	                	});
					}
				</script>';
				echo wp_kses($html, JSJOBS_ALLOWED_TAGS);
    } else {
    	$msg = __('No record found','js-jobs');
        JSJOBSlayout::getNoRecordFound($msg);
    }
}

//last week stats widge end;
//newest jobseeker widget start

function jsjobs_dashboard_widgets_jobseeker() {

    wp_add_dashboard_widget(
            'jsjobs_dashboard_widgets_jobseeker', // Widget slug.
            __('Newest Job seeker', 'js-jobs'), // Title.
            'jsjobs_dashboard_widget_function_newestJobseeker' // Display function.
    );
}

add_action('wp_dashboard_setup', 'jsjobs_dashboard_widgets_jobseeker');

function jsjobs_dashboard_widget_function_newestJobseeker() {
    jsjobs::addStyleSheets();
    $data = JSJOBSincluder::getJSModel('jsjobs')->getNewestUsers(2); // 2 for jobseeker
    if ($data != null) {
		echo wp_kses($data, JSJOBS_ALLOWED_TAGS);
    } else {
    	$msg = __('No record found','js-jobs');
        JSJOBSlayout::getNoRecordFound($msg);
    }
}

//newest jobseeker widge end;
//newest employer widget start

function jsjobs_dashboard_widgets_employer() {

    wp_add_dashboard_widget(
            'jsjobs_dashboard_widgets_employer', // Widget slug.
            __('Newest Employer', 'js-jobs'), // Title.
            'jsjobs_dashboard_widget_function_newestemployer' // Display function.
    );
}

add_action('wp_dashboard_setup', 'jsjobs_dashboard_widgets_employer');

function jsjobs_dashboard_widget_function_newestemployer() {
    jsjobs::addStyleSheets();
    $data = JSJOBSincluder::getJSModel('jsjobs')->getNewestUsers(1); // 1 for employer
    if ($data != null) {
        echo wp_kses($data, JSJOBS_ALLOWED_TAGS);
    } else {
    	$msg = __('No record found','js-jobs');
        JSJOBSlayout::getNoRecordFound($msg);
    }
}

//newest jobseeker widge end;
?>
