<?php


//START CALENDAR
//dates variables
$nd_spt_tot_days_this_month = cal_days_in_month(CAL_GREGORIAN, $nd_spt_month_today, $nd_spt_year_today);

//calculate next and prev date
$nd_spt_next_month = nd_spt_get_next_prev_month_year($nd_spt_date_default,'month','next');
$nd_spt_next_year = nd_spt_get_next_prev_month_year($nd_spt_date_default,'year','next');
$nd_spt_prev_month = nd_spt_get_next_prev_month_year($nd_spt_date_default,'month','prev');
$nd_spt_prev_year = nd_spt_get_next_prev_month_year($nd_spt_date_default,'year','prev');

//variables
$nd_spt_date_cell_width = 100/$nd_spt_tot_days_this_month;
$nd_spt_get_month_name_date = $nd_spt_year_today.'-'.$nd_spt_month_today.'-1';
$nd_spt_calendar = '';


//prev button
$nd_spt_real_month_today = date('m');
$nd_spt_real_year_today = date('Y');
$nd_spt_real_day_today = date('d');
if ( $nd_spt_month_today == $nd_spt_real_month_today AND $nd_spt_year_today == $nd_spt_real_year_today ) { 
    $nd_spt_button_prev = '
    <div class="nd_spt_section nd_spt_height_1"></div>';
}else{
    $nd_spt_button_prev = '
    <input type="hidden" name="nd_spt_prev_month" id="nd_spt_prev_month" value="'.$nd_spt_prev_month.'">
    <input type="hidden" name="nd_spt_prev_year" id="nd_spt_prev_year" value="'.$nd_spt_prev_year.'">
    <button onclick="nd_spt_calendar(1)" class="nd_spt_prev_next_cal nd_spt_float_left" type="button">'.__('Prev','nd-sports-booking').'</button>';
}



//START inline script
$nd_spt_bat_cal_date_code = '
    
    jQuery(document).ready(function() {

        jQuery( function ( $ ) {

          $(".nd_spt_calendar_date").click(function() {

            $(".nd_spt_calendar_date").removeClass("nd_spt_cal_active");
            var nd_spt_calendar_date_select = $(this).attr("data-date");
            $(this).addClass("nd_spt_cal_active");

            $("#nd_spt_date").val(nd_spt_calendar_date_select);';

            //call the update slots only if not dev mode
            if ( get_option('nd_spt_dev_mode') != 1 ){ $nd_spt_bat_cal_date_code .= 'nd_spt_update_timing(nd_spt_calendar_date_select);'; }

          $nd_spt_bat_cal_date_code .= '
          }); 
          
        });

    });
  
';
wp_add_inline_script( 'nd_spt_calendar_script', $nd_spt_bat_cal_date_code );
//END inline script




//START CALENDAR CONTENT
$nd_spt_calendar .= '

<div id="nd_spt_calendar_container" class="nd_spt_section nd_spt_text_align_center">

    <div id="nd_spt_calendar_content" class="nd_spt_section">

        <div class="nd_spt_display_table nd_spt_section">

            <div class="nd_spt_display_table_cell nd_spt_vertical_align_middle nd_spt_width_25_percentage">
                '.$nd_spt_button_prev.'       
            </div>

            <div class="nd_spt_display_table_cell nd_spt_vertical_align_middle nd_spt_width_50_percentage">
                <h3 class="nd_spt_margin_0 nd_spt_padding_0">'.nd_spt_get_month_name($nd_spt_get_month_name_date).' '.$nd_spt_year_today.'</h3>
            </div>

            <div class="nd_spt_display_table_cell nd_spt_vertical_align_middle nd_spt_width_25_percentage">
                    
                <input type="hidden" name="nd_spt_next_month" id="nd_spt_next_month" value="'.$nd_spt_next_month.'">
                <input type="hidden" name="nd_spt_next_year" id="nd_spt_next_year" value="'.$nd_spt_next_year.'">
                <button onclick="nd_spt_calendar(2)" class="nd_spt_prev_next_cal nd_spt_float_right" type="button">'.__('Next','nd-sports-booking').'</button>

            </div>

        </div>
        

        <div class="nd_spt_section nd_spt_height_20"></div> 

        <div class="nd_spt_section nd_spt_calendar_week">
            <div class="nd_spt_float_left nd_spt_width_14_percentage"><p><strong>'.__('M','nd-sports-booking').'</strong></p></div>
            <div class="nd_spt_float_left nd_spt_width_14_percentage"><p><strong>'.__('T','nd-sports-booking').'</strong></p></div>
            <div class="nd_spt_float_left nd_spt_width_14_percentage"><p><strong>'.__('W','nd-sports-booking').'</strong></p></div>
            <div class="nd_spt_float_left nd_spt_width_14_percentage"><p><strong>'.__('T','nd-sports-booking').'</strong></p></div>
            <div class="nd_spt_float_left nd_spt_width_14_percentage"><p><strong>'.__('F','nd-sports-booking').'</strong></p></div>
            <div class="nd_spt_float_left nd_spt_width_14_percentage"><p><strong>'.__('S','nd-sports-booking').'</strong></p></div>
            <div class="nd_spt_float_left nd_spt_width_14_percentage"><p><strong>'.__('S','nd-sports-booking').'</strong></p></div>
        </div>

        <div class="nd_spt_section">';

            for ($nd_spt_i = 1; $nd_spt_i <= $nd_spt_tot_days_this_month; $nd_spt_i++) {

                $nd_spt_date = $nd_spt_month_today.'/'.$nd_spt_i.'/'.$nd_spt_year_today;

                if ( $nd_spt_i == 1 ) {

                    $nd_spt_n = date("N",strtotime($nd_spt_date));

                    for ($i = 1; $i <= $nd_spt_n-1; $i++) {
                       $nd_spt_calendar .= '<div class="nd_spt_float_left nd_spt_width_14_percentage nd_spt_height_1"></div>';
                    }

                }else{
                    $test = '';   
                }

                //days classes
                $nd_spt_class = '';
                if ( $nd_spt_real_month_today == $nd_spt_month_today AND $nd_spt_real_year_today == $nd_spt_year_today ) { 
                   
                    //today class
                    if ( $nd_spt_i == $nd_spt_real_day_today ) { $nd_spt_class .= " nd_spt_cal_today"; }
                    
                    if ( $nd_spt_i >= $nd_spt_real_day_today ) { 

                        $nd_spt_date_total = $nd_spt_year_today.'-'.$nd_spt_month_today.'-'.$nd_spt_i;

                        //call the update slots only if not dev mode
                        if ( get_option('nd_spt_dev_mode') != 1 ){ 

                            //check if date is close
                            if ( nd_spt_close_day($nd_spt_date_total) == 1 ) { $nd_spt_class .= "  nd_spt_cal_ex_close ";  }
                            if ( nd_spt_close_day($nd_spt_date_total) == 2 ) { $nd_spt_class .= " nd_spt_cal_ex_hour_change "; }
                            if ( nd_spt_set_day($nd_spt_date_total) == 1 ) { }else{ $nd_spt_class .= " nd_spt_cal_not_set "; }   

                        }
                        //end dev mode

                        $nd_spt_class .= " nd_spt_cursor_pointer nd_spt_calendar_date ";  

                    } else { $nd_spt_class .= ''; }
                    if ( $nd_spt_month_today == $nd_spt_selected_month AND $nd_spt_year_today == $nd_spt_selected_year AND $nd_spt_i == $nd_spt_selected_day ) { $nd_spt_class .= " nd_spt_cal_active"; }
                
                }else{

                    $nd_spt_date_total = $nd_spt_year_today.'-'.$nd_spt_month_today.'-'.$nd_spt_i;

                    //call the update slots only if not dev mode
                    if ( get_option('nd_spt_dev_mode') != 1 ){ 

                        //check if date is close
                        if ( nd_spt_close_day($nd_spt_date_total) == 1 ) { $nd_spt_class .= "  nd_spt_cal_ex_close ";  }
                        if ( nd_spt_close_day($nd_spt_date_total) == 2 ) { $nd_spt_class .= " nd_spt_cal_ex_hour_change "; }
                        if ( nd_spt_set_day($nd_spt_date_total) == 1 ) { }else{ $nd_spt_class .= " nd_spt_cal_not_set "; }   

                    }
                    //end dev mode

                    $nd_spt_class .= " nd_spt_cursor_pointer nd_spt_calendar_date ";  

                    if ( $nd_spt_month_today == $nd_spt_selected_month AND $nd_spt_year_today == $nd_spt_selected_year AND $nd_spt_i == $nd_spt_selected_day ) { $nd_spt_class .= " nd_spt_cal_active"; }  
                }

                if ( $nd_spt_i == $nd_spt_day_today ) { $nd_spt_class .= ' nd_spt_cal_active '; }

                if ( strlen($nd_spt_i) == 1 ) {  
                    $nd_spt_i_visual = '0'.$nd_spt_i;    
                }else{
                    $nd_spt_i_visual = $nd_spt_i; 
                }
                $nd_spt_calendar .= '<div class="nd_spt_float_left nd_spt_width_14_percentage"><p data-date="'.$nd_spt_year_today.'-'.$nd_spt_month_today.'-'.$nd_spt_i_visual.'" class="'.$nd_spt_class.'">'.$nd_spt_i.'</p></div>';      

            }

        $nd_spt_calendar .= '
        </div></div>';


$nd_spt_calendar .= '</div>';
//END CALENDAR












