<?php

//convert date format
$nd_spt_dat_new = new DateTime($nd_spt_date);
$nd_spt_date_visual = date_format($nd_spt_dat_new, get_option('date_format'));
$nd_spt_tim_new = new DateTime($nd_spt_time);
$nd_spt_time_visual = date_format($nd_spt_tim_new, get_option('time_format'));


$nd_spt_booking_result .= '
<!--START CONTAINER-->
<div class="nd_spt_section nd_spt_booking_container_2">


     <!--info booking-->
    <input type="hidden" name="nd_spt_sport" id="nd_spt_sport" value="'.$nd_spt_sport.'">
    <input type="hidden" name="nd_spt_players" id="nd_spt_players" value="'.$nd_spt_players.'">
    <input type="hidden" name="nd_spt_date" id="nd_spt_date" value="'.$nd_spt_date.'">
    <input type="hidden" name="nd_spt_time" id="nd_spt_time" value="'.$nd_spt_time.'">
    <input type="hidden" name="nd_spt_occasion" id="nd_spt_occasion" value="'.$nd_spt_occasion.'">


    <div id="nd_spt_booking_all_container_2" class="nd_spt_section">
    
        


        <!--START Resume-->
        <div id="nd_spt_booking_step_resume" class="nd_spt_section">

            <div class="nd_spt_section nd_spt_position_relative">
                
                <img class="nd_spt_section" src="'.$nd_spt_image_src[0].'">

                <div id="nd_spt_booking_step_resume_filter"></div>

                <p class="nd_spt_margin_0 nd_spt_booking_resume_sport ">'.get_the_title($nd_spt_sport).'</p>

            </div>


            

            <div id="nd_spt_booking_step_resume_all_info" class="nd_spt_section">

                <h1 id="nd_spt_booking_step_resume_all_info_word" class="nd_options_third_font">'.__('Details','nd-sports-booking').'</h1>

                <div class="nd_spt_section">
                    <div class="nd_spt_float_left nd_spt_width_50_percentage nd_spt_text_align_left">
                        <p class="nd_spt_margin_0"><span>'.__('players','nd-sports-booking').' :</span>  '.$nd_spt_players.'</p>
                        <p class="nd_spt_margin_0 nd_spt_margin_top_6 nd_spt_step_resume_occasion"><span>'.__('Service','nd-sports-booking').' :</span>  '.$nd_spt_occasion_title.'</p>
                    </div>
                    <div class="nd_spt_float_left nd_spt_width_50_percentage nd_spt_text_align_right">
                        <p class="nd_spt_margin_0"><span>'.__('Time','nd-sports-booking').' :</span>  '.$nd_spt_time_visual.'</p>
                        <p class="nd_spt_margin_0 nd_spt_margin_top_6"><span>'.__('Date','nd-sports-booking').' :</span>  '.$nd_spt_date_visual.'</p>
                    </div>
                </div>   
                
            </div>

            
        </div>
        <!--END resume-->





        <!--START FORM-->
        <div id="nd_spt_booking_step_datas_form" class="nd_spt_section">

            <div id="nd_spt_booking_step_datas_form_container" class="nd_spt_section">

                <input type="hidden" id="nd_spt_action_return" name="nd_spt_action_return" value="'.$nd_spt_action_return.'">

                <div class="nd_spt_section">
                    <h3>'.__('Your Datas','nd-sports-booking').' :</h3>
                </div>

                <div id="nd_spt_booking_form_name_container" class="nd_spt_section">
                    <label class="" for="nd_spt_booking_form_name">'.__('Name','nd-sports-booking').' *</label>
                    <input class="" type="text" name="nd_spt_booking_form_name" id="nd_spt_booking_form_name" value="">
                </div>

                <div id="nd_spt_booking_form_surname_container" class="nd_spt_section">
                    <label class="" for="nd_spt_booking_form_surname">'.__('Surname','nd-sports-booking').' *</label>
                    <input class="" type="text" name="nd_spt_booking_form_surname" id="nd_spt_booking_form_surname" value="">
                </div>

                <div id="nd_spt_booking_form_email_container" class="nd_spt_section">
                    <label class="" for="nd_spt_booking_form_email">'.__('Email','nd-sports-booking').' *</label>
                    <input class="" type="text" name="nd_spt_booking_form_email" id="nd_spt_booking_form_email" value="">
                </div>

                <div id="nd_spt_booking_form_phone_container" class="nd_spt_section">
                    <label class="" for="nd_spt_booking_form_phone">'.__('Phone','nd-sports-booking').' *</label>
                    <input class="" type="text" name="nd_spt_booking_form_phone" id="nd_spt_booking_form_phone" value="">
                </div>

                <div class="nd_spt_section nd_spt_height_20"></div>

                <div id="nd_spt_booking_form_requests_container" class="nd_spt_section">
                    <label class="" for="nd_spt_booking_form_requests">'.__('Message','nd-sports-booking').'</label>
                    <textarea rows="4" class="" name="nd_spt_booking_form_requests" id="nd_spt_booking_form_requests"></textarea>
                </div>

                <div class="nd_spt_section nd_spt_height_20"></div>

                <div id="nd_spt_booking_form_term_container" class="nd_spt_section">
                    <label class="" for="nd_spt_booking_form_requests">
                        <input class="" id="nd_spt_booking_form_term" name="nd_spt_booking_form_term" type="checkbox" checked value="1">
                        <a target="_blank" href="'.get_permalink(get_option("nd_spt_terms_page")).'">'.__('Terms and Conditions','nd-sports-booking').' </a>
                    </label>
                </div>

                <div class="nd_spt_section nd_spt_height_20"></div>

                <button class="nd_options_first_font" onclick="nd_spt_validate_fields()">'.__('CHECKOUT','nd-sports-booking').'</button>
                <button onclick="nd_spt_go_to_checkout()" id="nd_spt_submit_go_to_checkout" class="nd_spt_display_none nd_options_first_font">'.__('CHECKOUT','nd-sports-booking').'</button>
            
            </div>
        
        </div>
        <!--END FORM-->

    </div>

</div>
<!--END CONTAINER-->


';












