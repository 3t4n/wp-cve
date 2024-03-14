<?php
/**
 * Short description: Booking calendar created by Innate Images, LLC
 * PHP Version 8.0
 
 * @category  VRCalendarShortcode_Class
 * @package   VRCalendarShortcode_Class
 * @author    Innate Images, LLC <info@innateimagesllc.com>
 * @copyright 2015 Innate Images, LLC
 * @license   GPL-2.0+ http://www.vrcalendarsync.com
 * @link      http://www.vrcalendarsync.com
 */

 /**
  * Short description: Booking calendar created by Innate Images, LLC
  * VRCalendarShortcode Class Doc Comment
  * 
  * VRCalendarShortcode Class
  * 
  * @category  VRCalendarShortcode_Class
  * @package   VRCalendarShortcode_Class
  * @author    Innate Images, LLC <info@innateimagesllc.com>
  * @copyright 2015 Innate Images, LLC
  * @license   GPL-2.0+ http://www.vrcalendarsync.com
  * @link      http://www.vrcalendarsync.com
  */
class VRCalendarShortcode extends VRCShortcode
{

    protected $slug = 'vrcalendar';

    /**
     * Shortcode handler based on web instance
     * 
     * @param array  $atts    attribute 
     * @param string $content content
     * 
     * @return String
     */
    function shortcodeHandler($atts, $content = "")
    {
        
        $this->atts = shortcode_atts(
            array(
                'id'=>false
            ), $atts, 'vrcalendar'
        );

        if (!$this->atts['id']) {
            return __('Calendar id is missing', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        }

        $VRCalendarEntity = VRCalendarEntity::getInstance();
        $VRCalendarSettings = VRCalendarSettings::getInstance();
        
        $cal_data = $VRCalendarEntity->getCalendar($this->atts['id']);

        $months_per_page =  $cal_data->calendar_layout_options['rows'] * $cal_data->calendar_layout_options['columns'];
        $headout = '';
        $pages = ceil(36 / $months_per_page);
        $headout .= '<input type="hidden" value="'.$pages.'" class="total_pages">';
        $headout .= '<input type="hidden" value="1" class="next_page-'.$cal_data->calendar_id.'">';

        $lang_update = __('Calendar Updated on', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $lang_available = __('Available', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $lang_unavailable = __('Unavailable', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $last_sync_date = get_date_from_gmt($cal_data->calendar_last_synchronized, 'F d, Y \a\t h:i a');

        //Attribution Code here which adds a Powered by VR Cal Sync under the Calendar.
        //First, make sure it is set, if its not, set it to yes by default
        $doattribution = $VRCalendarSettings->getSettings('attribution');
        if (!isset($doattribution) ) {
            $doattribution = 'yes';
        }
        
        //Next, If attribution is desired print it, otherwise print blank string
        
        if ($doattribution == 'yes') {
            $printattribution = "<div class=\"calendar-info\">".__('Powered By', VRCALENDAR_PLUGIN_TEXT_DOMAIN)." <a href=\"http://www.vrcalendarsync.com\" target=\"_blank\">Vacation Rental Calendar Sync</a></div>";
        } else {
            $printattribution = "";
        }
        
        //Color Debug Statements
        //$tempbgcolor = $cal_data->calendar_layout_options['default_bg_color'];
        //$tempfontcolor = $cal_data->calendar_layout_options['default_font_color'];

        $calendar_html = $this->getCalendar($cal_data, 36);
        $calendar_css =$this->getCalendarCSS($cal_data);
        $uid = uniqid();
        $calid = $cal_data->calendar_id;
        $url = VRCALENDAR_PLUGIN_URL;
        $siteurl = site_url().'/wp-admin/admin-ajax.php';
        $dir = VRCALENDAR_PLUGIN_URL;

        $output = '';
        $output .= '<div class="vrc vrc-calendar vrc-calendar-booking-no vrc-calendar-'.$cal_data->calendar_layout_options['size'].' vrc-calendar-id-'.$calid.'" id="vrc-calendar-uid-'.$uid.'">';
        $output .= $headout;
        $output .= '<div class="calendar-header">';
        $output .= '<div class="date_update">'.$lang_update.' '.$last_sync_date.'</div>';
        $output .= '<div class="avail_labl pull-left">';
        $output .= '<div class="calendar-legend">';
            $output .= '<div class="day-number normal-day day_number_header"></div>';
            $output .= '<div class="calendar-legend-text">'.$lang_available.'  </div>';
            $output .= '<div class="day-number event-day day_number_header"></div>';
            $output .= '<div class="calendar-legend-text">'.$lang_unavailable.'</div>';
            $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="pull-right">';
            $output .= '<div class="button_calaner_header">';
                $output .= '<div class="customNavigation">';
                $output .= '<a class="a1 btn-prev-single-'.$calid.' pull-left" style="cursor:pointer;width: 30px;height: 30px;">&nbsp;</a>';
                $output .= '<a class="a2 btn-next-single-'.$calid.' pull-right" style="cursor:pointer;width: 30px;height: 30px;">&nbsp;</a>';
                $output .= '</div>';
            $output .= '</div>';
        $output .= '</div>';
        $output .= '<div id ="indicator-single-'.$calid.'" style="margin: 0 auto; width: 100%; display: none;position: absolute;top: 0;left: 0;height: 100%;z-index: 1;background: #fff;opacity: 0.5;">';
            $output .= '<img src="'.$url.'/assets/images/spinner.gif" alt="Smiley face" height="35" width="35">';
        $output .= '</div>';
    $output .= '</div>';
    $output .= '<div class="calendar-slides calid-'.$calid.'">';
        $output .= $calendar_html;
    $output .= '</div>';

    $output .= '<script>';
    $output .= 'jQuery(".vrc-calendar .btn-next-single-'.$calid.'").on("click", function(){';
		$output .= 'jQuery("#indicator-single-'.$calid.'").show();';
		$output .= 'var id = '.$calid.';';
		$output .= 'var calendar = jQuery(".calendar-month-name").html();';
		$output .= 'var next_page = parseInt(jQuery(".next_page-'.$calid.'").val());';
		$output .= 'var next_page_no  = next_page +1;';
		
		$output .= 'var total_pages = parseInt(jQuery(".total_pages").val());';
		$output .= 'if(total_pages == next_page){';
			$output .= 'next_page_no = 1;';
        $output .= '}';
		$output .= 'var detailresponseurl = "'.$siteurl.'";';

		$output .= 'jQuery.ajax({
                            type : "post",
                            dataType : "html",
                            url : detailresponseurl,
                            data : {action: "getSingleCalendarCustome", calendar: calendar, type: "next",id:id, next_page:next_page_no },
                            success: function(response) {
                                jQuery(".calid-'.$calid.'").html(response);
                                jQuery("#indicator-single-'.$calid.'").hide();
                                var next_cal_page = next_page + 1;
                                if(total_pages == next_page){
                                    next_cal_page = 1;
                                }
                                jQuery(".next_page-'.$calid.'").val(next_cal_page);
                                load_js();
                            }
                        });
                    });';

    $output .= 'jQuery(".vrc-calendar .btn-prev-single-'.$calid.'").on("click", function(){
                    jQuery("#indicator-single-'.$calid.'").show();
                    var id = "'.$calid.'";
                    var calendar = jQuery(".calendar-month-name").html();
                    var next_page = parseInt(jQuery(".next_page-'.$calid.'").val());
                    var next_page_no  = next_page - 1;
                    var total_pages = parseInt(jQuery(".total_pages").val());
                    if(next_page_no < 1){
                        next_page_no = 1;
                    }
                    var detailresponseurl = "'.$siteurl.'";
                    jQuery.ajax({
                        type : "post",
                        dataType : "html",
                        url : detailresponseurl,
                        data : {action: "getSingleCalendarCustome", calendar: calendar, type: "next",id:id, next_page:next_page_no },
                        success: function(response) {
                            jQuery(".calid-'.$calid.'").html(response);

                            var next_cal_page = next_page-1;
                            if(next_cal_page < 1){
                                next_cal_page = 1;
                            }

                            jQuery("#indicator-single-'.$calid.'").hide();
                            var next_cal_page = next_page - 1;
                            if(next_page == 1){
                                jQuery(".next_page-'.$calid.'").val(total_pages);
                            }else{
                                jQuery(".next_page-'.$calid.'").val(next_cal_page);
                            }
                            if(isNaN(total_pages) || isNaN(next_page)){
                                jQuery(".next_page-'.$calid.'").val(1);
                            }else{
                                jQuery(".next_page-'.$calid.'").val(next_cal_page);
                            }
                            load_js();
                        }
                    });
                });';
				
    $output .= 'function load_js(){
		  var head= document.getElementsByTagName("head")[0];
		  var script= document.createElement("script");
		  script.type= "text/javascript";
		  script.src="'.$dir.'assets/js/public.js?ver=1.0.0";
		  head.appendChild(script);
	   };';
    $output .= '</script>';
    $output .= '</div>';
    $output .= $printattribution;
    $output .= $calendar_css;
        return $output;
    }

    /**
     * Shortcode handler based on web instance
     * 
     * @param array $cal_data calendar data
     * 
     * @return String
     */
    function getCalendarCSS($cal_data)
    {
        $style = "";
        $style .= "<style>
    #indicator-single-{$cal_data->calendar_id} img{
		top: 50%;
		position: relative;
		opacity: 1;
		display: block;
		margin: 0 auto;
	}
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} .calendar-month-container {
        background:{$cal_data->calendar_layout_options['default_bg_color']};
        color:{$cal_data->calendar_layout_options['default_font_color']};
        border-color:{$cal_data->calendar_layout_options['calendar_border_color']};
    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} * {
        color:{$cal_data->calendar_layout_options['default_font_color']};

    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} td.calendar-day-head {
        background:{$cal_data->calendar_layout_options['week_header_bg_color']};
        color:{$cal_data->calendar_layout_options['week_header_font_color']};
    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} td.calendar-day .day-number,
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} .day-number{
        background:{$cal_data->calendar_layout_options['available_bg_color']};
        color:{$cal_data->calendar_layout_options['available_font_color']};
    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} td.calendar-day .day-number.event-day,
     .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} .day-number.event-day{
        background:{$cal_data->calendar_layout_options['unavailable_bg_color']};
        color:{$cal_data->calendar_layout_options['unavailable_font_color']};
    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} td.calendar-day .day-number.event-start {
        background: {$cal_data->calendar_layout_options['available_bg_color']}; /* Old browsers */
        background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIxMDAlIiB5Mj0iMTAwJSI+CiAgICA8c3RvcCBvZmZzZXQ9IjAlIiBzdG9wLWNvbG9yPSIjZGRmZmNjIiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iNTAlIiBzdG9wLWNvbG9yPSIjZGRmZmNjIiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iNTAlIiBzdG9wLWNvbG9yPSIjZmZjMGJkIiBzdG9wLW9wYWNpdHk9IjEiLz4KICA8L2xpbmVhckdyYWRpZW50PgogIDxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9InVybCgjZ3JhZC11Y2dnLWdlbmVyYXRlZCkiIC8+Cjwvc3ZnPg==);
        background: -moz-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['available_bg_color']} 0%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 50%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,{$cal_data->calendar_layout_options['available_bg_color']}), color-stop(50%,{$cal_data->calendar_layout_options['available_bg_color']}), color-stop(50%,{$cal_data->calendar_layout_options['unavailable_bg_color']})); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['available_bg_color']} 0%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['unavailable_bg_color']} 50%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['available_bg_color']} 0%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['unavailable_bg_color']} 50%); /* Opera 11.10+ */
        background: -ms-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['available_bg_color']} 0%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['unavailable_bg_color']} 50%); /* IE10+ */
        background: linear-gradient(135deg,  {$cal_data->calendar_layout_options['available_bg_color']} 0%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['unavailable_bg_color']} 50%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$cal_data->calendar_layout_options['available_bg_color']}', endColorstr='{$cal_data->calendar_layout_options['unavailable_bg_color']}',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} td.calendar-day .day-number.event-end {
        background: {$cal_data->calendar_layout_options['unavailable_bg_color']}; /* Old browsers */
        /* IE9 SVG, needs conditional override of 'filter' to 'none' */
        background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIxMDAlIiB5Mj0iMTAwJSI+CiAgICA8c3RvcCBvZmZzZXQ9IjUwJSIgc3RvcC1jb2xvcj0iI2ZmYzBiZCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjUwJSIgc3RvcC1jb2xvcj0iI2RkZmZjYyIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNkZGZmY2MiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
        background: -moz-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['unavailable_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, right bottom, color-stop(50%,{$cal_data->calendar_layout_options['unavailable_bg_color']}), color-stop(50%,{$cal_data->calendar_layout_options['available_bg_color']}), color-stop(100%,{$cal_data->calendar_layout_options['available_bg_color']})); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['unavailable_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']}c 50%,{$cal_data->calendar_layout_options['available_bg_color']} 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['unavailable_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']} 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['unavailable_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']} 100%); /* IE10+ */
        background: linear-gradient(135deg,  {$cal_data->calendar_layout_options['unavailable_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']} 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$cal_data->calendar_layout_options['unavailable_bg_color']}', endColorstr='{$cal_data->calendar_layout_options['available_bg_color']}',GradientType=1 ); /* IE6-8 fallback on horizontal gradient */
    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} td.calendar-day .day-number.start-end-day {
        background: {$cal_data->calendar_layout_options['unavailable_bg_color']};
        background: -moz-linear-gradient(-45deg, {$cal_data->calendar_layout_options['unavailable_bg_color']} 0%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 46%, {$cal_data->calendar_layout_options['available_bg_color']} 47%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 54%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 55%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 100%);
        background: -webkit-gradient(left top, right bottom, color-stop(0%, {$cal_data->calendar_layout_options['unavailable_bg_color']}), color-stop(46%, {$cal_data->calendar_layout_options['unavailable_bg_color']}), color-stop(47%, {$cal_data->calendar_layout_options['available_bg_color']}), color-stop(50%, {$cal_data->calendar_layout_options['available_bg_color']}), color-stop(54%, {$cal_data->calendar_layout_options['available_bg_color']}), color-stop(55%, {$cal_data->calendar_layout_options['unavailable_bg_color']}), color-stop(100%, {$cal_data->calendar_layout_options['unavailable_bg_color']}));
        background: -webkit-linear-gradient(-45deg, {$cal_data->calendar_layout_options['unavailable_bg_color']} 0%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 46%, {$cal_data->calendar_layout_options['available_bg_color']} 47%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 54%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 55%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 100%);
        background: -o-linear-gradient(-45deg, {$cal_data->calendar_layout_options['unavailable_bg_color']} 0%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 46%, {$cal_data->calendar_layout_options['available_bg_color']} 47%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 54%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 55%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 100%);
        background: -ms-linear-gradient(-45deg, {$cal_data->calendar_layout_options['unavailable_bg_color']} 0%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 46%, {$cal_data->calendar_layout_options['available_bg_color']} 47%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 54%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 55%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 100%);
        background: linear-gradient(135deg, {$cal_data->calendar_layout_options['unavailable_bg_color']} 0%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 46%, {$cal_data->calendar_layout_options['available_bg_color']} 47%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 54%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 55%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 100%);
        color: #000000 !important;
        /* filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$cal_data->calendar_layout_options['available_bg_color']}', endColorstr='{$cal_data->calendar_layout_options['unavailable_bg_color']}',GradientType=1 ); */
    }
</style>";
        return $style;
    }

    /**
     * Get calendar function
     * 
     * @param array $cal_data  calendar data
     * @param array $months    calendar months
     * @param int   $next_page next page
     * 
     * @return String
     */
    function getCalendar($cal_data, $months, $next_page = 0)
    {

        $calendar_html = '';

        $months_per_page =  $cal_data->calendar_layout_options['rows'] * $cal_data->calendar_layout_options['columns'];

        $pages = ceil($months / $months_per_page);
        $show_months = $months_per_page-1;
        $next_month = 0;
        $page = 0;
        if ($next_page) {
            $show_months = $next_page*$months_per_page - 1;
            $next_month = ($next_page - 1) * $months_per_page;
        }
        while ($page < $pages) {
            $calendar_html .= '<div class="calendar-page" >';
            for ($row=1; $row<=$cal_data->calendar_layout_options['rows'] && $next_month<=$show_months; $row++) {
                $calendar_html .= '<div class="row">';
                for ($col=1; $col<=$cal_data->calendar_layout_options['columns'] && $next_month<=$show_months; $col++) {
                    //$next_data = date('Y-m-d', strtotime("+{$next_month} months"));
                    $next_data = date('Y-m-d', mktime(0, 0, 0, date('m')+$next_month, 1, date('Y')));
                    $month = date('n', strtotime($next_data));
                    $year =  date('Y', strtotime($next_data));
                    $col_class = '';
                    if ($cal_data->calendar_layout_options['columns']) {
                        $col_class = floor(12/$cal_data->calendar_layout_options['columns']);
                    }
                    $calendar_html .= '<div class="col-md-'.$col_class.'">';
                    $calendar_html .= $this->getMonthCalendar($cal_data, $month, $year);
                    $calendar_html .= '</div>';
                    $next_month++;
                }
                $calendar_html .= '</div>';
            }
            $calendar_html .= '</div>';
            $page++;
        }

        return $calendar_html;
    }

    /**
     * Get month calendar function
     * 
     * @param array $cal_data calendar data
     * @param array $month    calendar month
     * @param int   $year     calendar year
     * 
     * @return String
     */
    function getMonthCalendar($cal_data, $month, $year)
    {

        $VRCalendarBooking = VRCalendarBooking::getInstance();
        
        //Set the Date for the top of the Calendar
        $month_name = date_i18n('F', strtotime("{$year}-{$month}-1"));
        $year_name = date_i18n('Y', strtotime("{$year}-{$month}-1"));

        /* draw table */
        $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

        $headings = array(
            __('Sun', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            __('Mon', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            __('Tue', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            __('Wed', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            __('Thu', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            __('Fri', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            __('Sat', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
        );

        $calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">', $headings).'</td></tr>';

        /* days and weeks vars now ... */
        $running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
        $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
        $days_in_this_week = 1;
        $day_counter = 0;
        $dates_array = array();

        /* row for week one */
        $calendar.= '<tr class="calendar-row">';

        /* print "blank" days until the first of the current week */
        for($x = 0; $x < $running_day; $x++):
            $calendar.= '<td class="calendar-day-np"> </td>';
            $days_in_this_week++;
        endfor;

        /* keep going with days.... */
        for($list_day = 1; $list_day <= $days_in_month; $list_day++):
            $cDate = date('Y-m-d', mktime(0, 0, 0, $month, $list_day, $year));
            if ($VRCalendarBooking->isStartEndDate($cal_data, $cDate)) {
                $booked_class = 'start-end-day';
            } else if ($VRCalendarBooking->isStartDate($cal_data, $cDate)) {
                $booked_class = 'event-start';
            } else if ($VRCalendarBooking->isEndDate($cal_data, $cDate)) {
                $booked_class = 'no-event-day event-end';
            } else if ($VRCalendarBooking->isDateAvailable($cal_data, $cDate)) {
                $booked_class = 'no-event-day';
            } else {
                $booked_class = 'event-day';
            }

            $calendar.= '<td class="calendar-day">';
            /* add in the day number */
            $calendar.= '<div class="day-number '.$booked_class.'" data-calendar-id="'.$cal_data->calendar_id.'" data-booking-date="'.$cDate.'">'.$list_day.'</div>';

            $calendar.= '</td>';
            if($running_day == 6) :
                $calendar.= '</tr>';
                if(($day_counter+1) != $days_in_month) :
                    $calendar.= '<tr class="calendar-row">';
                endif;
                $running_day = -1;
                $days_in_this_week = 0;
            endif;
            $days_in_this_week++; $running_day++; $day_counter++;
        endfor;

        /* finish the rest of the days in the week */
        if($days_in_this_week < 8 && $days_in_this_week>1) :
            for($x = 1; $x <= (8 - $days_in_this_week); $x++):
                $calendar.= '<td class="calendar-day-np"> </td>';
            endfor;
        endif;

        /* final row */
        $calendar.= '</tr>';

        /* end the table */
        $calendar.= '</table>';

        $result = '';
        $result .= '<div class="calendar-month-container">';
            $result .= '<div class="calendar-month-name">'.$month_name.' '.$year_name.'</div>';
            $result .= $calendar;
        $result .= '</div>';

        return $result;
    }

}
