<?php


//convert date format
$nd_spt_dat_new = new DateTime($nd_spt_date);
$nd_spt_date_visual = date_format($nd_spt_dat_new, get_option('date_format'));
$nd_spt_tim_new = new DateTime($nd_spt_time_start);
$nd_spt_time_visual = date_format($nd_spt_tim_new, get_option('time_format'));


$nd_spt_add_to_db_result .= '
    
    <div id="nd_spt_thanks_step" class="nd_spt_section nd_spt_padding_40 nd_spt_box_siing_border_box">

    	<h3 class="nd_spt_text_align_center">'.__('Thanks For Your Booking','nd-sports-booking').' :</h3>

    	<div class="nd_spt_section nd_spt_height_30"></div> 

    	<div id="nd_spt_thanks_step_resume" class="nd_spt_section">

    		
    		<!--START icons-->
    		<div id="nd_spt_thanks_step_resume_icons" class="nd_spt_section">

    			<div class="nd_spt_width_25_percentage nd_spt_float_left nd_spt_text_align_center">
    				
    				<img alt="" class="" width="50" src="'.esc_url(plugins_url('004-sport.png', __FILE__ )).'">
    				<p>'.get_the_title($nd_spt_sport).'</p>

    			</div>	

    			<div class="nd_spt_width_25_percentage nd_spt_float_left nd_spt_text_align_center">
    				
    				<img alt="" class="" width="50" src="'.esc_url(plugins_url('003-users.png', __FILE__ )).'">
    				<p><strong class="nd_options_color_greydark">'.__('playerS','nd-sports-booking').' : </strong><span>'.$nd_spt_players.'</span></p>

    			</div>

    			<div class="nd_spt_width_25_percentage nd_spt_float_left nd_spt_text_align_center">
    				
    				<img alt="" class="" width="50" src="'.esc_url(plugins_url('002-calendar.png', __FILE__ )).'">
    				<p><strong class="nd_options_color_greydark">'.__('DATE','nd-sports-booking').' : </strong><span>'.$nd_spt_date_visual.'</span></p>

    			</div>

    			<div class="nd_spt_width_25_percentage nd_spt_float_left nd_spt_text_align_center">
    				
    				<img alt="" class="" width="50" src="'.esc_url(plugins_url('001-time.png', __FILE__ )).'">
    				<p><strong class="nd_options_color_greydark">'.__('TIME','nd-sports-booking').' : </strong><span>'.$nd_spt_time_visual.'</span></p>

    			</div>	

    		</div>
    		<!--END icons-->

    		<div id="nd_spt_thanks_step_resume_table" class="nd_spt_section">

	    		<div class="nd_spt_section nd_spt_thanks_step_resume_left ">

	    			<p><strong class="nd_options_color_greydark">'.__('NAME','nd-sports-booking').' : </strong><span>'.$nd_spt_booking_form_name.'</span></p>
	    			<div class="nd_spt_section nd_spt_height_10"></div> 
                    <p><strong class="nd_options_color_greydark">'.__('SURNAME','nd-sports-booking').' : </strong><span>'.$nd_spt_booking_form_surname.'</span></p>
		    		<div class="nd_spt_section nd_spt_height_10"></div> 
                    <p><strong class="nd_options_color_greydark">'.__('EMAIL','nd-sports-booking').' : </strong><span>'.$nd_spt_booking_form_email.'</span></p>
                    <div class="nd_spt_section nd_spt_height_10"></div> 
                    <p><strong class="nd_options_color_greydark">'.__('PHONE','nd-sports-booking').' : </strong><span>'.$nd_spt_booking_form_phone.'</span></p>
                    <div class="nd_spt_section nd_spt_height_10"></div> 
                    <p class="nd_spt_thanks_step_resume_table_occasion"><strong class="nd_options_color_greydark">'.__('SERVICE','nd-sports-booking').' : </strong><span>'.$nd_spt_occasion_title.'</span></p>
                    <div class="nd_spt_section nd_spt_height_10"></div> 
                    <p class="nd_spt_thanks_step_resume_table_booking_method"><strong class="nd_options_color_greydark">'.__('BOOKING METHOD','nd-sports-booking').' : </strong><span>'.$nd_spt_order_type_name.'</span></p>
                    <div class="nd_spt_section nd_spt_height_10"></div> 
                    <p class="nd_spt_thanks_step_resume_table_deposit"><strong class="nd_options_color_greydark">'.__('DEPOSIT VALUE','nd-sports-booking').' : </strong><span>'.$nd_spt_deposit.' '.$nd_spt_currency.'</span></p>
		    		
	    		</div>

    		</div>

    	</div>

    </div>
    

';