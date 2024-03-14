<?php


//convert date format
$nd_spt_dat_new = new DateTime($nd_spt_date);
$nd_spt_date_visual = date_format($nd_spt_dat_new, get_option('date_format'));
$nd_spt_tim_new = new DateTime($nd_spt_time);
$nd_spt_time_visual = date_format($nd_spt_tim_new, get_option('time_format'));


$nd_spt_checkout_result .= '
    <div class="nd_spt_section nd_spt_booking_container_3">

        <!--info booking-->
        <input type="hidden" name="nd_spt_sport" id="nd_spt_sport" value="'.$nd_spt_sport.'">
        <input type="hidden" name="nd_spt_players" id="nd_spt_players" value="'.$nd_spt_players.'">
        <input type="hidden" name="nd_spt_date" id="nd_spt_date" value="'.$nd_spt_date.'">
        <input type="hidden" name="nd_spt_time" id="nd_spt_time" value="'.$nd_spt_time.'">
        <input type="hidden" name="nd_spt_occasion" id="nd_spt_occasion" value="'.$nd_spt_occasion.'">
        <input type="hidden" name="nd_spt_booking_form_name" id="nd_spt_booking_form_name" value="'.$nd_spt_booking_form_name.'">
        <input type="hidden" name="nd_spt_booking_form_surname" id="nd_spt_booking_form_surname" value="'.$nd_spt_booking_form_surname.'">
        <input type="hidden" name="nd_spt_booking_form_email" id="nd_spt_booking_form_email" value="'.$nd_spt_booking_form_email.'">
        <input type="hidden" name="nd_spt_booking_form_phone" id="nd_spt_booking_form_phone" value="'.$nd_spt_booking_form_phone.'">
        <input type="hidden" name="nd_spt_booking_form_requests" id="nd_spt_booking_form_requests" value="'.$nd_spt_booking_form_requests.'">
        
        <input type="hidden" name="nd_spt_order_type" id="nd_spt_order_type" value="request">
        <input type="hidden" name="nd_spt_order_status" id="nd_spt_order_status" value="'.get_option('nd_spt_default_order_status').'">


        <div id="nd_spt_checkout_all_container_3" class="nd_spt_section">


        <!--START Resume-->
        <div id="nd_spt_checkout_step_resume" class="nd_spt_section">

            <div class="nd_spt_section nd_spt_position_relative">
                
                <img class="nd_spt_section" src="'.$nd_spt_image_src[0].'">

                <div id="nd_spt_checkout_step_resume_filter"></div>

                <p class="nd_spt_margin_0 nd_spt_checkout_resume_sport ">'.get_the_title($nd_spt_sport).'</p>

            </div>


            

            <div id="nd_spt_checkout_step_resume_all_info" class="nd_spt_section">

                <h1 id="nd_spt_checkout_step_resume_all_info_word" class="nd_options_third_font">'.__('Details','nd-sports-booking').'</h1>

                <div class="nd_spt_section">
                    <div class="nd_spt_float_left nd_spt_width_50_percentage nd_spt_text_align_left">
                        <p class="nd_spt_margin_0"><span>'.__('players','nd-sports-booking').' :</span> '.$nd_spt_players.'</p>
                        <p class="nd_spt_margin_0 nd_spt_step_resume_check_occasion nd_spt_margin_top_6"><span>'.__('Service','nd-sports-booking').' :</span> '.$nd_spt_occasion_title.'</p>
                    </div>
                    <div class="nd_spt_float_left nd_spt_width_50_percentage nd_spt_text_align_right">
                        <p class="nd_spt_margin_0"><span>'.__('Time','nd-sports-booking').' :</span> '.$nd_spt_time_visual.'</p>
                        <p class="nd_spt_margin_0 nd_spt_margin_top_6"><span>'.__('Date','nd-sports-booking').' :</span> '.$nd_spt_date_visual.'</p>
                    </div>
                </div> 

                <div class="nd_spt_section nd_spt_height_15"></div>

                <div class="nd_spt_section">
                    <div class="nd_spt_float_left nd_spt_width_50_percentage nd_spt_text_align_left">
                        <p class="nd_spt_margin_0"><span>'.__('Name','nd-sports-booking').':</span> '.$nd_spt_booking_form_name.'</p>
                        <p class="nd_spt_margin_0 nd_spt_margin_top_6"><span>'.__('Email','nd-sports-booking').' :</span> '.$nd_spt_booking_form_email.'</p>
                    </div>
                    <div class="nd_spt_float_left nd_spt_width_50_percentage nd_spt_text_align_right">
                        <p class="nd_spt_margin_0"><span>'.__('Surname','nd-sports-booking').' :</span> '.$nd_spt_booking_form_surname.'</p>
                        <p class="nd_spt_margin_0 nd_spt_margin_top_6"><span>'.__('Phone','nd-sports-booking').' :</span> '.$nd_spt_booking_form_phone.'</p>
                    </div>
                </div>   
                
            </div>

            
        </div>
        <!--END resume-->


        <!--START FORM-->
        <div id="nd_spt_checkout_step_datas_form" class="nd_spt_section">

            <div id="nd_spt_checkout_step_datas_form_container" class="nd_spt_section">

                <div id="nd_spt_checkout_form_name_container" class="nd_spt_section">
                    

                    <h3>'.__('Booking Methods','nd-sports-booking').' :</h3>

                    <div class="nd_spt_section nd_spt_height_30"></div>

                    <p class="nd_spt_checkout_form_description">'.get_option('nd_spt_general_description').'</p>

                    <div class="nd_spt_section nd_spt_height_30"></div>';





                    if ( get_option('nd_spt_stripe_enable') == 1 ) {

                      //static stripe variables
                      $nd_spt_stripe_public_key = get_option('nd_spt_stripe_public_key');
                      $nd_spt_stripe_currency = get_option('nd_spt_stripe_currency');
                      $nd_spt_return_page_s = $nd_spt_action_return;
                      $nd_spt_stripe_deposit = get_option('nd_spt_stripe_deposit');

                      //color
                      $nd_spt_customizer_color_1 = get_option( 'nd_spt_customizer_color_1', '#c0a58a' );
                      $nd_spt_customizer_font_color_p = get_option( 'nd_options_customizer_font_color_p', '#7e7e7e' );

                      //font
                      $nd_spt_customizer_font_family_p = get_option( 'nd_options_customizer_font_family_p', 'Montserrat:400,700' );
                      $nd_spt_font_family_p_array = explode(":", $nd_spt_customizer_font_family_p);
                      $nd_spt_font_family_p = str_replace("+"," ",$nd_spt_font_family_p_array[0]);

                      //set deposit
                      if ( get_option('nd_spt_deposit_players') == 1 ){ $nd_spt_stripe_deposit = $nd_spt_stripe_deposit * $nd_spt_players; }

                      $nd_spt_checkout_result .= '
                      <style>
                      #nd_spt_section_confirm_stripe { font-weight:normal; color: '.$nd_spt_customizer_font_color_p.'; }
                      </style>

                      <div class="nd_spt_section nd_spt_height_15 nd_spt_border_bottom_1_solid_grey"></div>
                      <div class="nd_spt_section nd_spt_height_15 "></div>

                      <div id="nd_spt_section_confirm_stripe" class="nd_spt_section ">

                          <div class="nd_spt_section nd_spt_box_sizing_border_box">
                            <p class="nd_spt_toogle_title nd_spt_position_relative nd_spt_padding_left_45 nd_options_color_greydark">
                              <span class=" nd_spt_toogle_icon nd_spt_cursor_pointer nd_spt_text_align_center nd_spt_toogle_title_open_3 nd_spt_width_25 nd_spt_display_none nd_spt_height_25  nd_spt_position_absolute nd_spt_top_0 nd_spt_left_0">
                                <img alt="" class="nd_spt_margin_top_6" width="12" src="'.esc_url(plugins_url('icon-add-white.png', __FILE__ )).'">
                              </span> 
                              <span class=" nd_spt_toogle_icon nd_spt_cursor_pointer nd_spt_text_align_center nd_spt_toogle_title_close_3 nd_spt_width_25  nd_spt_height_25 nd_spt_position_absolute nd_spt_top_0 nd_spt_left_0">
                                <img alt="" class="nd_spt_margin_top_6" width="12" src="'.esc_url(plugins_url('icon-less-white.png', __FILE__ )).'">
                              </span>
                              '.__('CREDIT CARD','nd-sports-booking').'
                            </p>
                          </div>
                          
                          <div class=" nd_spt_padding_20 nd_spt_padding_left_0 nd_spt_padding_right_0 nd_spt_toogle_content_3 nd_spt_section nd_spt_box_sizing_border_box">
                            <p class="nd_spt_line_height_1_6">'.get_option('nd_spt_stripe_description').'</p>
                            <div class="nd_spt_section nd_spt_height_20"></div>


                              <form action="'.$nd_spt_return_page_s.'" method="post" id="payment-form">
                                  
                                  <div class="form-row nd_spt_margin_top_20 nd_spt_margin_bottom_20">
                                      <div id="card-element"></div>
                                      <div class="nd_spt_margin_top_10" id="card-errors" role="alert"></div>
                                  </div>

                                  
                                  <input type="hidden" name="nd_spt_sport" id="nd_spt_sport" value="'.$nd_spt_sport.'">
                                  <input type="hidden" name="nd_spt_players" id="nd_spt_players" value="'.$nd_spt_players.'">
                                  <input type="hidden" name="nd_spt_date" id="nd_spt_date" value="'.$nd_spt_date.'">
                                  <input type="hidden" name="nd_spt_time" id="nd_spt_time" value="'.$nd_spt_time.'">
                                  <input type="hidden" name="nd_spt_occasion" id="nd_spt_occasion" value="'.$nd_spt_occasion.'">
                                  <input type="hidden" name="nd_spt_booking_form_name" id="nd_spt_booking_form_name" value="'.$nd_spt_booking_form_name.'">
                                  <input type="hidden" name="nd_spt_booking_form_surname" id="nd_spt_booking_form_surname" value="'.$nd_spt_booking_form_surname.'">
                                  <input type="hidden" name="nd_spt_booking_form_email" id="nd_spt_booking_form_email" value="'.$nd_spt_booking_form_email.'">
                                  <input type="hidden" name="nd_spt_booking_form_phone" id="nd_spt_booking_form_phone" value="'.$nd_spt_booking_form_phone.'">
                                  <input type="hidden" name="nd_spt_booking_form_requests" id="nd_spt_booking_form_requests" value="'.$nd_spt_booking_form_requests.'">
                                  <input type="hidden" name="nd_spt_order_type" id="nd_spt_order_type" value="stripe">
                                  <input type="hidden" name="nd_spt_arrive_from_stripe" id="nd_spt_arrive_from_stripe" value="1">
                                  

                                  <input class="nd_spt_margin_top_20" type="submit" id="" name="" value="'.__('DEPOSIT','nd-sports-booking').' '.$nd_spt_stripe_deposit.' '.get_option('nd_spt_stripe_currency').'">

                              </form>

                              <script type="text/javascript">

                                  var stripe = Stripe("'.$nd_spt_stripe_public_key.'");
                                  var elements = stripe.elements();

                                  var style = {
                                    base: {
                                      color: "'.$nd_spt_customizer_font_color_p.'",
                                      lineHeight: "18px",
                                      fontFamily: "'.$nd_spt_font_family_p.', sans-serif",
                                      fontWeight: "normal",
                                      fontSize: "14px",
                                      "::placeholder": {
                                        color: "'.$nd_spt_customizer_font_color_p.'"
                                      }
                                    },
                                    invalid: {
                                      color: "'.$nd_spt_customizer_color_1.'",
                                      iconColor: "'.$nd_spt_customizer_color_1.'"
                                    }
                                  };

                                  var card = elements.create("card", {style: style});
                                  card.mount("#card-element");

                                  card.addEventListener("change", function(event) {
                                    var displayError = document.getElementById("card-errors");
                                    if (event.error) {
                                      displayError.textContent = event.error.message;
                                    } else {
                                      displayError.textContent = "";
                                    }
                                  });

                                  var form = document.getElementById("payment-form");
                                  form.addEventListener("submit", function(event) {
                                    event.preventDefault();
                                    stripe.createToken(card).then(function(result) {
                                      if (result.error) {
                                        var errorElement = document.getElementById("card-errors");
                                        errorElement.textContent = result.error.message;
                                      } else {
                                        stripeTokenHandler(result.token);
                                      }
                                    });
                                  });

                                  function stripeTokenHandler(token) {
                                    var form = document.getElementById("payment-form");
                                    var hiddenInput = document.createElement("input");
                                    hiddenInput.setAttribute("type", "hidden");
                                    hiddenInput.setAttribute("name", "stripeToken");
                                    hiddenInput.setAttribute("value", token.id);
                                    form.appendChild(hiddenInput);
                                    form.submit();
                                  }

                              </script>
                              
                          </div>

                      </div>
                      <!--END STRIPE-->';

                    }




                    if ( get_option('nd_spt_paypal_enable') == 1 ) {

                      //static paypal variables
                      if ( get_option('nd_spt_paypal_dev_mode') == 1 ) { 
                        $nd_spt_paypal_action_1 = 'https://www.sandbox.paypal.com/cgi-bin';
                      }else{
                        $nd_spt_paypal_action_1 = 'https://www.paypal.com/cgi-bin';
                      }
                      $nd_spt_paypal_email = get_option('nd_spt_paypal_email');
                      $nd_spt_price = get_option('nd_spt_paypal_deposit');
                      $nd_spt_paypal_currency = get_option('nd_spt_paypal_currency');
                      $nd_spt_paypal_deposit = get_option('nd_spt_paypal_deposit');

                      //set deposit
                      if ( get_option('nd_spt_deposit_players') == 1 ){ $nd_spt_paypal_deposit = $nd_spt_paypal_deposit * $nd_spt_players; }
                      $nd_spt_price = $nd_spt_paypal_deposit;

                      $nd_spt_return_page = $nd_spt_action_return;

                      $nd_spt_checkout_result .= '
                      <div class="nd_spt_section nd_spt_height_15 nd_spt_border_bottom_1_solid_grey"></div>
                      <div class="nd_spt_section nd_spt_height_15 "></div>

                      <div id="nd_spt_section_confirm_paypal" class="nd_spt_section ">

                          <div class="nd_spt_section nd_spt_box_sizing_border_box">
                            <p class="nd_spt_toogle_title nd_spt_position_relative nd_spt_padding_left_45 nd_options_color_greydark">
                              <span class=" nd_spt_toogle_icon nd_spt_cursor_pointer nd_spt_text_align_center nd_spt_toogle_title_open_2 nd_spt_width_25 nd_spt_height_25  nd_spt_position_absolute nd_spt_top_0 nd_spt_left_0">
                                <img alt="" class="nd_spt_margin_top_6" width="12" src="'.esc_url(plugins_url('icon-add-white.png', __FILE__ )).'">
                              </span> 
                              <span class=" nd_spt_toogle_icon nd_spt_cursor_pointer nd_spt_text_align_center nd_spt_toogle_title_close_2 nd_spt_width_25 nd_spt_display_none nd_spt_height_25 nd_spt_position_absolute nd_spt_top_0 nd_spt_left_0">
                                <img alt="" class="nd_spt_margin_top_6" width="12" src="'.esc_url(plugins_url('icon-less-white.png', __FILE__ )).'">
                              </span>
                              '.__('PAYPAL','nd-sports-booking').'
                            </p>
                          </div>
                          
                          <div class="nd_spt_display_none nd_spt_padding_20 nd_spt_padding_left_0 nd_spt_padding_right_0 nd_spt_toogle_content_2 nd_spt_section nd_spt_box_sizing_border_box">
                            <p class="nd_spt_line_height_1_6">'.get_option('nd_spt_paypal_description').'</p>
                            <div class="nd_spt_section nd_spt_height_20"></div>


                            <form target="paypal" action="'.$nd_spt_paypal_action_1.'" method="post" >
                                
                              <input type="hidden" name="cmd" value="_xclick">
                              <input type="hidden" name="business" value="'.$nd_spt_paypal_email.'">
                              <input type="hidden" name="lc" value="">
                              <input type="hidden" name="item_name" value="'.get_the_title($nd_spt_sport).' : '.$nd_spt_players.' '.__('players','nd-sports-booking').', '.$nd_spt_date_visual.', '.$nd_spt_time_visual.'">
                              <input type="hidden" name="item_number" value="'.$nd_spt_sport.'">
                              <input type="hidden" name="custom" value="'.$nd_spt_players.'[ndbcpm]'.$nd_spt_date.'[ndbcpm]'.$nd_spt_time.'[ndbcpm]'.$nd_spt_booking_form_phone.'[ndbcpm]'.$nd_spt_occasion_title.'[ndbcpm]'.$nd_spt_booking_form_requests.'[ndbcpm]'.$nd_spt_occasion.'[ndbcpm]">
                              <input type="hidden" name="amount" value="'.$nd_spt_price.'">
                              <input type="hidden" name="currency_code" value="'.$nd_spt_paypal_currency.'">
                              <input type="hidden" name="rm" value="2" />
                              <input type="hidden" name="return" value="'.$nd_spt_return_page.'" />
                              <input type="hidden" name="cancel_return" value="" />
                              <input type="hidden" name="button_subtype" value="services">
                              <input type="hidden" name="no_note" value="0">
                              <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedplayer">
                          
                              <input class="" type="submit" id="" name="" value="'.__('DEPOSIT','nd-sports-booking').' '.$nd_spt_paypal_deposit.' '.get_option('nd_spt_paypal_currency').'">

                            </form>




                          </div>

                      </div>
                      <!--END PAYPAL-->';

                    }






                    $nd_spt_checkout_result .= '
                    <div class="nd_spt_section nd_spt_height_15 nd_spt_border_bottom_1_solid_grey"></div>
                    <div class="nd_spt_section nd_spt_height_15 "></div>

                    <div id="nd_spt_section_confirm_br" class="nd_spt_section">

                        <div class="nd_spt_section nd_spt_box_sizing_border_box">
                          <p class="nd_spt_toogle_title nd_spt_position_relative nd_spt_padding_left_45 nd_options_color_greydark">
                            <span class=" nd_spt_toogle_icon nd_spt_cursor_pointer nd_spt_text_align_center nd_spt_toogle_title_open_1 nd_spt_width_25 nd_spt_height_25  nd_spt_position_absolute nd_spt_top_0 nd_spt_left_0">
                              <img alt="" class="nd_spt_margin_top_6" width="12" src="'.esc_url(plugins_url('icon-add-white.png', __FILE__ )).'">
                            </span> 
                            <span class=" nd_spt_toogle_icon nd_spt_cursor_pointer nd_spt_text_align_center nd_spt_toogle_title_close_1 nd_spt_width_25 nd_spt_display_none nd_spt_height_25 nd_spt_position_absolute nd_spt_top_0 nd_spt_left_0">
                              <img alt="" class="nd_spt_margin_top_6" width="12" src="'.esc_url(plugins_url('icon-less-white.png', __FILE__ )).'">
                            </span>
                            '.__('BOOKING REQUEST','nd-sports-booking').'
                          </p>
                        </div>
                        
                        <div class=" nd_spt_display_none nd_spt_padding_20 nd_spt_padding_left_0 nd_spt_padding_right_0 nd_spt_toogle_content_1 nd_spt_section nd_spt_box_sizing_border_box">
                          <p class="nd_spt_line_height_1_6">'.get_option('nd_spt_br_description').'</p>
                          <div class="nd_spt_section nd_spt_height_20"></div>
                          <button class="nd_options_first_font" onclick="nd_spt_add_to_db()">'.__('SEND REQUEST','nd-sports-booking').'</button>
                        </div>

                    </div>
                    <!--END BOOKING REQUEST-->';






                    



                    
                $nd_spt_checkout_result .= '
                </div>

            </div>

        </div>
        <!--END FORM-->


        </div>


    </div>

    ';