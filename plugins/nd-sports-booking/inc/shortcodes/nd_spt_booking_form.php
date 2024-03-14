<?php


//START
function nd_spt_shortcode_booking_form($nd_spt_atts) {

    //parameters
    $nd_spt_shortcode_booking_form = shortcode_atts( 
    array(
        'layout' => '',
        'calendar-bg' => '',
    ), 
    $nd_spt_atts );


    $nd_spt_result = '';


    //START get layout
    if ( $nd_spt_shortcode_booking_form['layout'] == '' ) { 

        //import style
        wp_enqueue_style( 'nd_spt_style_layout', esc_url(plugins_url('css/booking-form/layout-0.css', __FILE__ )) );

    } else { 

        //import style
        wp_enqueue_style( 'nd_spt_style_layout', esc_url(plugins_url('css/booking-form/', __FILE__ )).''.$nd_spt_shortcode_booking_form['layout'].'.css'  );

        //get colors
        $nd_spt_customizer_color_dark_1 = get_option( 'nd_spt_customizer_color_dark_1', '#2d2d2d' );
        $nd_spt_customizer_color_1 = get_option( 'nd_spt_customizer_color_1', '#c0a58a' );
        $nd_spt_customizer_color_2 = get_option( 'nd_spt_customizer_color_2', '#b66565' );


        //START inline script
        $nd_spt_style_layout_inline = '

            /*dark 1*/
            #nd_spt_steps_container h5 span{background-color: '.$nd_spt_customizer_color_dark_1.';  }
            #nd_spt_cal_occa_section { background-color: '.$nd_spt_customizer_color_dark_1.'; }
            #nd_spt_booking_step_datas_form label span { background-color: '.$nd_spt_customizer_color_dark_1.'; }
            #nd_spt_booking_step_resume_all_info { background-color: '.$nd_spt_customizer_color_dark_1.'; }
            #nd_spt_checkout_step_resume_all_info { background-color: '.$nd_spt_customizer_color_dark_1.'; }
            .nd_spt_ul_sport li.nd_spt_bg_color_blue { background-color: '.$nd_spt_customizer_color_dark_1.';}
            .nd_spt_ul_occasion li.nd_spt_bg_color_blue {background-color: '.$nd_spt_customizer_color_dark_1.';}
            #nd_spt_time_section .nd_spt_time.nd_spt_bg_color_blue { background-color: '.$nd_spt_customizer_color_dark_1.'; }
            .nd_spt_toogle_icon { background-color: '.$nd_spt_customizer_color_dark_1.'; }

            /*color 1*/
            #nd_spt_steps_container .nd_spt_step_active h5 span { background-color: '.$nd_spt_customizer_color_1.'; }
            .nd_spt_legend_selected span { background-color: '.$nd_spt_customizer_color_1.'; }
            .nd_spt_legend_not_available span { background-color: '.$nd_spt_customizer_color_1.'; }
            .nd_spt_cal_active.nd_spt_calendar_date{ background-color: '.$nd_spt_customizer_color_1.' !important; }
            #nd_spt_time_section p { background-color: '.$nd_spt_customizer_color_1.'; }
            #nd_spt_btn_go_to_booking { box-shadow: 5px 5px 0px 1px '.$nd_spt_customizer_color_2.'; background-color: '.$nd_spt_customizer_color_1.'; }
            #nd_spt_booking_step_datas_form button { background-color: '.$nd_spt_customizer_color_1.'; box-shadow: 5px 5px 0px 1px '.$nd_spt_customizer_color_2.'; }
            .nd_spt_checkout_container_3 button { background-color: '.$nd_spt_customizer_color_1.'; }
            #nd_spt_checkout_step_datas_form button,#nd_spt_checkout_step_datas_form input[type="submit"] { background-color: '.$nd_spt_customizer_color_1.'; box-shadow: 3px 3px 0px 1px '.$nd_spt_customizer_color_2.'; }
            .nd_spt_cal_not_set:after { background-color: '.$nd_spt_customizer_color_1.'; }

            /*color 2*/
            .nd_spt_legend_current span { background-color: '.$nd_spt_customizer_color_dark_1.'; }
            .nd_spt_cal_today.nd_spt_calendar_date{ background-color: rgb(0 0 0 / 10%); }


            .nd_spt_cal_ex_close:after { background-color:'.$nd_spt_customizer_color_1.';}

          
        ';

        /*Add img bg on calendar if is set*/
        if ( $nd_spt_shortcode_booking_form['calendar-bg'] != '' ) { 
            $nd_spt_style_layout_inline .= '#nd_spt_cal_occa_section {background-position: center;background-repeat: no-repeat;background-size: cover;background-image: url('.$nd_spt_shortcode_booking_form['calendar-bg'].');}';
        }

        wp_add_inline_style( 'nd_spt_style_layout', $nd_spt_style_layout_inline );
        //END inline script


    }
    //END get layout



    if ( isset($_GET['tx']) ) {

        //recover datas from plugin settings
        $nd_spt_paypal_email = get_option('nd_spt_paypal_email');
        $nd_spt_paypal_currency = get_option('nd_spt_paypal_currency');
        $nd_spt_paypal_token = get_option('nd_spt_paypal_token');
        if ( get_option('nd_spt_paypal_dev_mode') == 1 ) { 
          $nd_spt_paypal_action_1 = 'https://www.sandbox.paypal.com/cgi-bin';
          $nd_spt_paypal_action_2 = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; 
        }else{
          $nd_spt_paypal_action_1 = 'https://www.paypal.com/cgi-bin';
          $nd_spt_paypal_action_2 = 'https://www.paypal.com/cgi-bin/webscr'; 
        }
        $nd_spt_paypal_tx = sanitize_text_field($_GET['tx']);
        $nd_spt_tx = $nd_spt_paypal_tx;
        $nd_spt_paypal_url = $nd_spt_paypal_action_2;


        //prepare the request
        $nd_spt_paypal_response = wp_remote_post( 

            $nd_spt_paypal_url, 

            array(
            
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'body' => array( 
                    'cmd' => '_notify-synch',
                    'tx' => $nd_spt_paypal_tx,
                    'at' => $nd_spt_paypal_token
                ),
                'cookies' => array()
            
            )
        );

        $nd_spt_http_paypal_response_code = wp_remote_retrieve_response_code( $nd_spt_paypal_response );


        //START if is 200
        if ( $nd_spt_http_paypal_response_code == 200 ) {
            
            $nd_spt_paypal_response_body = wp_remote_retrieve_body( $nd_spt_paypal_response );

            //START SUCCESS
            if ( strpos($nd_spt_paypal_response_body, 'SUCCESS') === 0 ) {

                $nd_spt_paypal_response = substr($nd_spt_paypal_response_body, 7);
                $nd_spt_paypal_response = urldecode($nd_spt_paypal_response);
                preg_match_all('/^([^=\s]++)=(.*+)/m', $nd_spt_paypal_response, $m, PREG_PATTERN_ORDER);
                $nd_spt_paypal_response = array_combine($m[1], $m[2]);  


                if(isset($nd_spt_paypal_response['charset']) AND strtoupper($nd_spt_paypal_response['charset']) !== 'UTF-8')
                {
                  foreach($nd_spt_paypal_response as $key => &$value)
                  {
                    $value = mb_convert_encoding($value, 'UTF-8', $nd_spt_paypal_response['charset']);
                  }
                  $nd_spt_paypal_response['charset_original'] = $nd_spt_paypal_response['charset'];
                  $nd_spt_paypal_response['charset'] = 'UTF-8';
                }
                ksort($nd_spt_paypal_response);


                //START RECOVER ALL VARIABLES
                $nd_spt_booking_date = $nd_spt_paypal_response['payment_date'];
                $nd_spt_deposit = $nd_spt_paypal_response['mc_gross'];
                $nd_spt_booking_form_name = $nd_spt_paypal_response['first_name'];
                $nd_spt_booking_form_surname = $nd_spt_paypal_response['last_name'];
                $nd_spt_booking_form_email = $nd_spt_paypal_response['payer_email'];
                $nd_spt_sport = $nd_spt_paypal_response['item_number'];
                $nd_spt_currency = $nd_spt_paypal_response['mc_currency'];
                $nd_spt_custom_field_array = explode('[ndbcpm]', $nd_spt_paypal_response['custom']);
                $nd_spt_players = $nd_spt_custom_field_array[0];
                $nd_spt_date = $nd_spt_custom_field_array[1];
                $nd_spt_time_start = $nd_spt_custom_field_array[2];
                $nd_spt_booking_form_phone = $nd_spt_custom_field_array[3];
                $nd_spt_occasion_title = $nd_spt_custom_field_array[4];
                $nd_spt_booking_form_requests = $nd_spt_custom_field_array[5];
                $nd_spt_occasion = $nd_spt_custom_field_array[6];
                $nd_spt_order_type_name = 'paypal';
                $nd_spt_order_status = 'confirmed';
                //END RECOVER ALL VARIABLES

                //calculate end time
                $nd_spt_booking_duration = get_option('nd_spt_booking_duration');
                $nd_spt_booking_duration_insert = $nd_spt_booking_duration-1;
                $nd_spt_time_end = date("G:i", strtotime('+'.$nd_spt_booking_duration_insert.' minutes', strtotime($nd_spt_time_start))); //add minutes slot to start time

                //add reservation in db
                nd_spt_add_booking_in_db(
                    $nd_spt_sport,
                    $nd_spt_players,
                    $nd_spt_date,
                    $nd_spt_time_start,
                    $nd_spt_time_end,
                    $nd_spt_occasion,
                    $nd_spt_booking_form_name,
                    $nd_spt_booking_form_surname,
                    $nd_spt_booking_form_email,
                    $nd_spt_booking_form_phone,
                    $nd_spt_booking_form_requests,
                    $nd_spt_order_type_name,
                    $nd_spt_order_status,
                    $nd_spt_deposit,
                    $nd_spt_tx,
                    $nd_spt_currency
                );


            }
            //END SUCCESS

        }
        //END if is 200


        $nd_spt_result .= '
        <!--START step index-->
        <div id="nd_spt_steps_container" class="nd_spt_section nd_spt_text_align_center">
            <div class="nd_spt_float_left nd_spt_width_20_percentage "><div class="nd_spt_section nd_spt_height_1"></div></div>
            <div class="nd_spt_float_left nd_spt_width_20_percentage nd_spt_single_step nd_spt_step_first ">
                <h5 class="nd_options_color_grey"><span>1</span>'.__('SELECT','nd-sports-booking').'</h5>
            </div>
            <div class="nd_spt_float_left nd_spt_width_20_percentage nd_spt_single_step nd_spt_step_booking">
                <h5 class="nd_options_color_grey"><span>2</span>'.__('DETAILS','nd-sports-booking').'</h5>
            </div>
            <div class="nd_spt_float_left nd_spt_width_20_percentage nd_spt_single_step nd_spt_step_checkout nd_spt_step_active">
                <h5 class="nd_options_color_grey"><span>3</span>'.__('THANKS','nd-sports-booking').'</h5>
            </div>
            <div class="nd_spt_float_left nd_spt_width_20_percentage "><div class="nd_spt_section nd_spt_height_1"></div></div>
        </div>
        <!--END step index-->';

        include realpath(dirname( __FILE__ ).'/include/nd_spt_booking_thanks.php');

        $nd_spt_result .= $nd_spt_add_to_db_result;


    }elseif( isset($_POST['nd_spt_arrive_from_stripe']) ){

        //START STRIPE
        $nd_spt_players = sanitize_text_field($_POST['nd_spt_players']);

        //static stripe variables
        $nd_spt_deposit = get_option('nd_spt_stripe_deposit');
        //set deposit
        if ( get_option('nd_spt_deposit_players') == 1 ){ $nd_spt_deposit = $nd_spt_deposit * $nd_spt_players; }

        $nd_spt_amount = $nd_spt_deposit*100;
        $nd_spt_currency = get_option('nd_spt_stripe_currency');
        $nd_spt_stripe_secret_key = get_option('nd_spt_stripe_secret_key');

        //get datas
        $nd_spt_stripe_token = sanitize_text_field($_POST['stripeToken']);
        $nd_spt_sport = sanitize_text_field($_POST['nd_spt_sport']);
        $nd_spt_date = sanitize_text_field($_POST['nd_spt_date']);
        $nd_spt_time_start = sanitize_text_field($_POST['nd_spt_time']);
        $nd_spt_occasion = sanitize_text_field($_POST['nd_spt_occasion']);
        $nd_spt_booking_form_name = sanitize_text_field($_POST['nd_spt_booking_form_name']);
        $nd_spt_booking_form_surname = sanitize_text_field($_POST['nd_spt_booking_form_surname']);
        $nd_spt_booking_form_email = sanitize_email($_POST['nd_spt_booking_form_email']);
        $nd_spt_booking_form_phone = sanitize_text_field($_POST['nd_spt_booking_form_phone']);
        $nd_spt_booking_form_requests = sanitize_text_field($_POST['nd_spt_booking_form_requests']);
        $nd_spt_order_type_name = 'stripe';
        $nd_spt_order_status = 'pending';

        //occasion
        $nd_spt_occasions = get_option('nd_spt_occasions');
        $nd_spt_occasions_array = explode(',', $nd_spt_occasions );
        $nd_spt_occasion_title = $nd_spt_occasions_array[$nd_spt_occasion];

        //calculate end time
        $nd_spt_booking_duration = get_option('nd_spt_booking_duration');
        $nd_spt_booking_duration_insert = $nd_spt_booking_duration-1;
        $nd_spt_time_end = date("G:i", strtotime('+'.$nd_spt_booking_duration_insert.' minutes', strtotime($nd_spt_time_start))); //add minutes slot to start time

        
        //call the api stripe only if we are not in dev mode
        if ( get_option('nd_spt_dev_mode') == 1 ){

             

        }else{

            //convert date format
            $nd_spt_dat_new = new DateTime($nd_spt_date);
            $nd_spt_date_visual = date_format($nd_spt_dat_new, get_option('date_format'));
            $nd_spt_tim_new = new DateTime($nd_spt_time_start);
            $nd_spt_time_visual = date_format($nd_spt_tim_new, get_option('time_format'));
            
            //stripe data
            $nd_spt_description = get_the_title($nd_spt_sport).' : '.$nd_spt_booking_form_name.' '.$nd_spt_booking_form_surname.', '.$nd_spt_players.' '.__('players','nd-sports-booking').', '.$nd_spt_date_visual.', '.$nd_spt_time_visual;
            $nd_spt_source = $nd_spt_stripe_token;
            $nd_spt_stripe_url = 'https://api.stripe.com/v1/charges';


            //prepare the request
            $nd_spt_stripe_response = wp_remote_post( 

                $nd_spt_stripe_url, 

                array(
                
                    'method' => 'POST',
                    'timeout' => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => array(
                        'Authorization' => 'Bearer '.$nd_spt_stripe_secret_key
                    ),
                    'body' => array( 
                        'amount' => $nd_spt_amount,
                        'currency' => $nd_spt_currency,
                        'description' => $nd_spt_description,
                        'source' => $nd_spt_source,
                        'metadata[sport]' => get_the_title($nd_spt_sport),
                        'metadata[players]' => $nd_spt_players,
                        'metadata[date]' => $nd_spt_date,
                        'metadata[time]' => $nd_spt_time_start,
                        'metadata[name]' => $nd_spt_booking_form_name,
                        'metadata[surname]' => $nd_spt_booking_form_surname,
                        'metadata[email]' => $nd_spt_booking_form_email,
                        'metadata[phone]' => $nd_spt_booking_form_phone,
                        'metadata[requests]' => $nd_spt_booking_form_requests
                    ),
                    'cookies' => array()
                
                )
            );

            // START check the response
            $nd_spt_http_stripe_response_code = wp_remote_retrieve_response_code( $nd_spt_stripe_response );


            if ( $nd_spt_http_stripe_response_code == 200 ) {

                $nd_spt_response_body = wp_remote_retrieve_body( $nd_spt_stripe_response );
                $nd_spt_stripe_data = json_decode( $nd_spt_response_body );

                if ( $nd_spt_stripe_data->paid == 1 ) { $nd_spt_order_status = 'confirmed'; }

                $nd_spt_tx = $nd_spt_stripe_data->id;

                //add reservation in db
                nd_spt_add_booking_in_db(
                    $nd_spt_sport,
                    $nd_spt_players,
                    $nd_spt_date,
                    $nd_spt_time_start,
                    $nd_spt_time_end,
                    $nd_spt_occasion,
                    $nd_spt_booking_form_name,
                    $nd_spt_booking_form_surname,
                    $nd_spt_booking_form_email,
                    $nd_spt_booking_form_phone,
                    $nd_spt_booking_form_requests,
                    $nd_spt_order_type_name,
                    $nd_spt_order_status,
                    $nd_spt_deposit,
                    $nd_spt_tx,
                    $nd_spt_currency
                );

            }


        }


        $nd_spt_result .= '
        <!--START step index-->
        <div id="nd_spt_steps_container" class="nd_spt_section nd_spt_text_align_center">
            <div class="nd_spt_float_left nd_spt_width_20_percentage "><div class="nd_spt_section nd_spt_height_1"></div></div>
            <div class="nd_spt_float_left nd_spt_width_20_percentage nd_spt_single_step nd_spt_step_first ">
                <h5 class="nd_options_color_grey"><span>1</span>'.__('SELECT','nd-sports-booking').'</h5>
            </div>
            <div class="nd_spt_float_left nd_spt_width_20_percentage nd_spt_single_step nd_spt_step_booking">
                <h5 class="nd_options_color_grey"><span>2</span>'.__('DETAILS','nd-sports-booking').'</h5>
            </div>
            <div class="nd_spt_float_left nd_spt_width_20_percentage nd_spt_single_step nd_spt_step_checkout nd_spt_step_active">
                <h5 class="nd_options_color_grey"><span>3</span>'.__('THANKS','nd-sports-booking').'</h5>
            </div>
            <div class="nd_spt_float_left nd_spt_width_20_percentage "><div class="nd_spt_section nd_spt_height_1"></div></div>
        </div>
        <!--END step index-->';

        include realpath(dirname( __FILE__ ).'/include/nd_spt_booking_thanks.php');

        $nd_spt_result .= $nd_spt_add_to_db_result;

        //END STRIPE



    //START FIRST STEP SHORTCODE
    }else{



        //get options
        $nd_spt_max_players = get_option('nd_spt_max_players'); if ( $nd_spt_max_players == '' ) { $nd_spt_max_players = 10; }
        $nd_spt_occasions = get_option('nd_spt_occasions');

        
        //ajax results
        if ( get_option('nd_spt_stripe_enable') == 1 ){ 
            wp_enqueue_script( 'nd_spt_stripe_script', 'https://js.stripe.com/v3/'); 
        }


        //ajax results
        $nd_spt_ajax_params = array(
            'nd_spt_ajaxurl' => admin_url('admin-ajax.php'),
            'nd_spt_ajaxnonce' => wp_create_nonce('nd_spt_nonce'),
        );
        wp_enqueue_script( 'nd_spt_calendar_script', esc_url( plugins_url( 'nd_spt_calendar.js', __FILE__ ) ), array( 'jquery' ) ); 
        wp_localize_script( 'nd_spt_calendar_script', 'nd_spt_my_vars_calendar', $nd_spt_ajax_params );


        //START get all sports
        $nd_spt_rest_args = array(
          'post_type' => 'nd_spt_cpt_1',
          'posts_per_page' => -1,
          'order' => 'ASC',
          'orderby' => 'date'
        );
        $nd_spt_rest_the_query = new WP_Query( $nd_spt_rest_args );

        $nd_spt_rest_list = '';
        $nd_spt_sport_i = 0;
        $nd_spt_rest_single = '';
        $nd_spt_rest_list .= '<ul class="nd_spt_ul_sport nd_spt_display_none">';

        while ( $nd_spt_rest_the_query->have_posts() ) : $nd_spt_rest_the_query->the_post();

            //get datas
            $nd_spt_sport_image = '';
            $nd_spt_sport_id = get_the_ID();
            $nd_spt_sport_title = get_the_title($nd_spt_sport_id);

            //image
            if ( has_post_thumbnail() ) {

                $nd_spt_image_id = get_post_thumbnail_id( $nd_spt_sport_id );
                $nd_spt_image_src = wp_get_attachment_image_src( $nd_spt_image_id,'large');

                $nd_spt_sport_image .= '
                <div class="nd_spt_section nd_spt_position_relative nd_spt_sport_image">
                    <img class="nd_spt_section" src="'.$nd_spt_image_src[0].'">

                    <div class="nd_spt_sport_image_filter nd_spt_position_absolute nd_spt_left_0 nd_spt_height_100_percentage nd_spt_width_100_percentage nd_spt_box_sizing_border_box">

                        <div class="nd_spt_position_absolute nd_spt_bottom_20 nd_spt_sport_image_content">
                            <h3>'.$nd_spt_sport_title.'</h3>    
                        </div>

                    </div>

                </div>
                ';    
            }else{
                $nd_spt_sport_image .= '';
            }

            if ( $nd_spt_sport_i == 0 ) { 
                $nd_spt_sport_id_first = $nd_spt_sport_id; 
                $nd_spt_sport_class = ' nd_spt_bg_color_blue ';
                $nd_spt_class_single = ''; 
            }else{
                $nd_spt_sport_class = ''; 
                $nd_spt_class_single = 'nd_spt_display_none';
            }


            $nd_spt_rest_single .= '
            <div class="nd_spt_rest_single '.$nd_spt_class_single.' nd_spt_rest_single_'.$nd_spt_sport_id.' ">

                '.$nd_spt_sport_image.'

            </div>';

            $nd_spt_rest_list .= '<li data-sport="'.$nd_spt_sport_id.'" class="nd_spt_ulli_sport '.$nd_spt_sport_class.' ">'.$nd_spt_sport_title.'</li>';

            $nd_spt_sport_i = $nd_spt_sport_i + 1;

        endwhile;

        $nd_spt_rest_list .= '</ul>';

        if ( $nd_spt_sport_i == 0 ) {
            $nd_spt_rest_class = ' nd_spt_display_none ';
            $nd_spt_sport_id_first = 0;
        }else{
            $nd_spt_rest_class = '';
        }

        wp_reset_postdata();
        //END get all sports


        //date default
        $nd_spt_date_default = sanitize_text_field($_GET['nd_spt_send_date']);
        
        if ( $nd_spt_date_default == '' ) { 

            $nd_spt_date_default = date("Y-m-d");
            $nd_spt_day_today = date('d');
            $nd_spt_month_today = date('m');
            $nd_spt_year_today = date('Y');

        }else{
            $nd_spt_year_today = substr($nd_spt_date_default,0,4);
            $nd_spt_month_today = substr($nd_spt_date_default,5,2);
            $nd_spt_day_today = substr($nd_spt_date_default,8,2);
        }
        
        //players default
        $nd_spt_players_default = sanitize_text_field($_GET['nd_spt_send_players']);
        if ( $nd_spt_players_default == '' ) { 
            $nd_spt_players_default = 1; 
        }

        include realpath(dirname( __FILE__ ).'/include/nd_spt_booking_calendar.php');

        //START inline script
        $nd_spt_search_comp_rest_code = '

            jQuery(document).ready(function() {

                jQuery( function ( $ ) {


                    $(".nd_spt_rest_single").click(function() {

                        $(".nd_spt_ul_sport").removeClass("nd_spt_display_none");

                    }); 


                    $(".nd_spt_ulli_sport").click(function() {

                        $(".nd_spt_rest_single").removeClass("nd_spt_display_block");
                        $(".nd_spt_ulli_sport").removeClass("nd_spt_bg_color_blue");
                        var nd_spt_rest_select = $(this).attr("data-sport");
                        $(this).addClass("nd_spt_bg_color_blue");

                        $("#nd_spt_sport").val(nd_spt_rest_select);
                        
                        var nd_spt_calendar_date_select = $("#nd_spt_date").val();';

                        //call the update slots only if not dev mode
                        if ( get_option('nd_spt_dev_mode') != 1 ){ $nd_spt_search_comp_rest_code .= 'nd_spt_update_timing(nd_spt_calendar_date_select);'; }
                        
                        $nd_spt_search_comp_rest_code .= '
                        $(".nd_spt_rest_single").addClass("nd_spt_display_none");
                        $(".nd_spt_rest_single_"+nd_spt_rest_select).addClass("nd_spt_display_block");
                        $(".nd_spt_ul_sport").addClass("nd_spt_display_none");

                    }); 
                  
                });

            });
          
        ';
        wp_add_inline_script( 'nd_spt_calendar_script', $nd_spt_search_comp_rest_code );
        //END inline script

        //START inline script
        $nd_spt_search_compd_players_code = '

            jQuery(document).ready(function() {

                jQuery( function ( $ ) {

                  $(".nd_spt_players_increase").click(function() {
                    var value = $(".nd_spt_players_number").text();
                            
                    if ( value < '.$nd_spt_max_players.' ){
                        value++;
                        $(".nd_spt_players_number").text(value);
                        $("#nd_spt_players").val(value);   

                        var nd_spt_calendar_date_select = $("#nd_spt_date").val();';

                        //call the update slots only if not dev mode
                        if ( get_option('nd_spt_dev_mode') != 1 ){ $nd_spt_search_compd_players_code .= 'nd_spt_update_timing(nd_spt_calendar_date_select);'; }

                    $nd_spt_search_compd_players_code .= '
                    } 

                  }); 

                  $(".nd_spt_players_decrease").click(function() {
                    var value = $(".nd_spt_players_number").text();
                    
                    if ( value > 1 ) {
                      value--;
                      $(".nd_spt_players_number").text(value);
                      $("#nd_spt_players").val(value);

                      var nd_spt_calendar_date_select = $("#nd_spt_date").val();';
                      
                      //call the update slots only if not dev mode
                      if ( get_option('nd_spt_dev_mode') != 1 ){ $nd_spt_search_compd_players_code .= 'nd_spt_update_timing(nd_spt_calendar_date_select);'; }
                      
                    $nd_spt_search_compd_players_code .= '
                    }
                    
                  }); 
                  
                });

            });
            

        ';
        wp_add_inline_script( 'nd_spt_calendar_script', $nd_spt_search_compd_players_code );
        //END inline script


        $nd_spt_result .= '

            <div id="nd_spt_component_container" class="nd_spt_section nd_spt_padding_30 nd_spt_box_sizing_border_box nd_spt_border_1_solid_grey">

               
                <input id="nd_spt_action_return" type="hidden" name="nd_spt_action_return" value="'.get_the_permalink().'">


                <!--START step index-->
                <div id="nd_spt_steps_container" class="nd_spt_section nd_spt_text_align_center">
                    <div class="nd_spt_float_left nd_spt_width_20_percentage "><div class="nd_spt_section nd_spt_height_1"></div></div>
                    <div class="nd_spt_float_left nd_spt_width_20_percentage nd_spt_single_step nd_spt_step_first nd_spt_step_active">
                        <h5 class="nd_options_color_grey"><span>1</span>'.__('SELECT','nd-sports-booking').'</h5>
                    </div>
                    <div class="nd_spt_float_left nd_spt_width_20_percentage nd_spt_single_step nd_spt_step_booking">
                        <h5 class="nd_options_color_grey"><span>2</span>'.__('DETAILS','nd-sports-booking').'</h5>
                    </div>
                    <div class="nd_spt_float_left nd_spt_width_20_percentage nd_spt_single_step nd_spt_step_checkout">
                        <h5 class="nd_options_color_grey"><span>3</span>'.__('CONFIRM','nd-sports-booking').'</h5>
                    </div>
                    <div class="nd_spt_float_left nd_spt_width_20_percentage "><div class="nd_spt_section nd_spt_height_1"></div></div>
                </div>
                <!--END step index-->


                <div class="nd_spt_section nd_spt_booking_container_all">
                <div class="nd_spt_section nd_spt_booking_container_1">
                

                    <div id="nd_spt_rest_players_legend_section" class="nd_spt_section">

                        <!--START sport-->
                        <div id="nd_spt_section_sport" class="nd_spt_section '.$nd_spt_rest_class.' ">
                            <label class="" for="nd_spt_sport">'.__('sport','nd-sports-booking').'</label>
                            
                            <div class="nd_spt_section nd_spt_position_relative">
                                '.$nd_spt_rest_single.'
                                '.$nd_spt_rest_list.'
                            </div>
                            
                            <input readonly class="nd_spt_display_none_important" type="number" name="nd_spt_sport" id="nd_spt_sport" value="'.$nd_spt_sport_id_first.'">

                        </div>
                        <!--END sport-->



                        <div id="nd_spt_players_legend_section" class="nd_spt_section">
                            
                            <!--START playerS-->
                            <div id="nd_spt_players_section" class="nd_spt_section">
                                
                                <h3 class="">'.__('players','nd-sports-booking').'</h3>

                                <div class=" nd_spt_section">

                                    <div class=" nd_spt_player_number  nd_spt_section">
                                        <h1 class="nd_spt_players_number nd_spt_margin_0 nd_spt_padding_0">'.$nd_spt_players_default.'</h1> 


                                        <div class=" nd_spt_player_number_add  nd_spt_width_50_percentage">
                                            <button class="nd_spt_players_increase" type="button">'.__('Add','nd-sports-booking').'</button>
                                        </div>

                                        <div class=" nd_spt_player_number_remove  nd_spt_width_50_percentage">
                                            <button class="nd_spt_players_decrease" type="button">'.__('Remove','nd-sports-booking').'</button>
                                        </div>


                                    </div>

                                    

                                </div>

                                <input readonly class="nd_spt_display_none_important" type="number" name="nd_spt_players" id="nd_spt_players" min="1" value="'.$nd_spt_players_default.'">

                            </div>
                            <!--END playerS-->            

                            <!--START LEGEND-->
                            <div id="nd_spt_legend_section" class="nd_spt_section">
                                <p class="nd_spt_legend_current"><span></span>'.__('Current Day','nd-sports-booking').'</p>
                                <p class="nd_spt_legend_selected"><span></span>'.__('Selected Day','nd-sports-booking').'</p>
                                <p class="nd_spt_legend_not_available"><span></span>'.__('Not Available','nd-sports-booking').'</p>
                            </div>
                            <!--END LEGEND-->


                        </div>

                    </div>



                    <div id="nd_spt_cal_occa_section" class="nd_spt_section">

                        <h1 id="nd_spt_calendar_word_bg">'.__('Service','nd-sports-booking').'</h1>';

                        
                        if ( get_option('nd_spt_dev_mode') != 1 ) {

                            $nd_spt_result .= '
                            <!--START CALENDAR-->
                            <div id="nd_spt_calendar_section" class="">
                                '.$nd_spt_calendar.'
                                <input readonly class="nd_spt_display_none_important" type="text" name="nd_spt_date" id="nd_spt_date" value="'.$nd_spt_date_default.'">
                            </div>
                            <!--END CALENDAR-->';

                        }else{

                            //script for calendar
                            wp_enqueue_script('jquery-ui-datepicker');
                            wp_enqueue_style('jquery-ui-datepicker-dev', esc_url(plugins_url('css/datepicker-dev.css', __FILE__ )) );

                            //get colors
                            $nd_spt_customizer_color_text = get_option( 'nd_options_customizer_font_color_p', '#7e7e7e' );
                            $nd_spt_customizer_color_1 = get_option( 'nd_spt_customizer_color_1', '#c0a58a' );
                            $nd_spt_customizer_color_2 = get_option( 'nd_spt_customizer_color_2', '#b66565' );


                            //START inline script
                            $nd_spt_searc_comp_l1_datepicker_code = '

                            jQuery(document).ready(function() {

                              jQuery( function ( $ ) {

                                  $( "#nd_spt_datepicker_dev" ).datepicker({
                                    defaultDate : "'.$nd_spt_date_default.'",
                                    minDate: 0,
                                    altField: "#nd_spt_date",
                                    altFormat: "yy-mm-dd",
                                    firstDay: 0,
                                    dateFormat: "yy-mm-dd",
                                    monthNames: ["'.__('January','nd-sports-booking').'","'.__('February','nd-sports-booking').'","'.__('March','nd-sports-booking').'","'.__('April','nd-sports-booking').'","'.__('May','nd-sports-booking').'","'.__('June','nd-sports-booking').'", "'.__('July','nd-sports-booking').'","'.__('August','nd-sports-booking').'","'.__('September','nd-sports-booking').'","'.__('October','nd-sports-booking').'","'.__('November','nd-sports-booking').'","'.__('December','nd-sports-booking').'"],
                                    monthNamesShort: [ "'.__('Jan','nd-sports-booking').'", "'.__('Feb','nd-sports-booking').'", "'.__('Mar','nd-sports-booking').'", "'.__('Apr','nd-sports-booking').'", "'.__('Maj','nd-sports-booking').'", "'.__('Jun','nd-sports-booking').'", "'.__('Jul','nd-sports-booking').'", "'.__('Aug','nd-sports-booking').'", "'.__('Sep','nd-sports-booking').'", "'.__('Oct','nd-sports-booking').'", "'.__('Nov','nd-sports-booking').'", "'.__('Dec','nd-sports-booking').'" ],
                                    dayNamesMin: ["'.__('S','nd-sports-booking').'","'.__('M','nd-sports-booking').'","'.__('T','nd-sports-booking').'","'.__('W','nd-sports-booking').'","'.__('T','nd-sports-booking').'","'.__('F','nd-sports-booking').'", "'.__('S','nd-sports-booking').'"],
                                    nextText: "'.__('NEXT','nd-sports-booking').'",
                                    prevText: "'.__('PREV','nd-sports-booking').'",
                                    changeMonth: false,
                                    numberOfMonths: 1
                                  });
                                  
                              });

                            });
                              
                            ';
                            wp_add_inline_script( 'jquery-ui-datepicker', $nd_spt_searc_comp_l1_datepicker_code );
                            //END inline script



                            $nd_spt_datepickerdev_style = '

                                /* color */
                                #nd_spt_datepicker_dev .ui-datepicker-today { background-color: rgb(0 0 0 / 10%); }
                                #nd_spt_datepicker_dev .ui-datepicker-current-day { background-color: '.$nd_spt_customizer_color_1.'; }
                                #nd_spt_datepicker_dev .ui-state-disabled span{ color: #fff !important; opacity: 0.5; }

                            ';
                            wp_add_inline_style( 'jquery-ui-datepicker-dev', $nd_spt_datepickerdev_style );




                            $nd_spt_result .= '
                            <div id="nd_spt_datepicker_dev"></div>
                            <input readonly class="nd_spt_display_none_important" type="text" name="nd_spt_date" id="nd_spt_date" value="'.$nd_spt_date_default.'">';   

                        }
                        



                        //START inline script
                        $nd_spt_search_comp_occasions_code = '

                        jQuery(document).ready(function() {

                            jQuery( function ( $ ) {


                                $(".nd_spt_occas_single").click(function() {

                                    $(".nd_spt_ul_occasion").removeClass("nd_spt_display_none");

                                }); 


                                $(".nd_spt_ulli_occasion").click(function() {

                                    $(".nd_spt_occas_single").removeClass("nd_spt_display_block");
                                    $(".nd_spt_ulli_occasion").removeClass("nd_spt_bg_color_blue");
                                    var nd_spt_occas_select = $(this).attr("data-occasion");
                                    $(this).addClass("nd_spt_bg_color_blue");

                                    $("#nd_spt_occasion").val(nd_spt_occas_select);

                                    $(".nd_spt_occas_single").addClass("nd_spt_display_none");
                                    $(".nd_spt_occas_single_"+nd_spt_occas_select).addClass("nd_spt_display_block");
                                    $(".nd_spt_ul_occasion").addClass("nd_spt_display_none");

                                }); 
                              
                            });

                        });    

                        ';
                        wp_add_inline_script( 'nd_spt_calendar_script', $nd_spt_search_comp_occasions_code );
                        //END inline script



                        if ( $nd_spt_occasions == '' ) {
                            $nd_spt_occasion_class = 'nd_spt_display_none'; 
                            $nd_spt_occasion_default_value = 'null';  
                        }else { 
                            $nd_spt_occasion_class = ''; 
                            $nd_spt_occasions_array = explode(',', $nd_spt_occasions );
                            $nd_spt_occasion_default_value = 0;  
                        }

                        $nd_spt_result .= '
                        <!--START SERVICE-->
                        <div id="nd_spt_occasion_section" class="nd_spt_section">
                            <div id="nd_spt_occasion_cont" class="nd_spt_section '.$nd_spt_occasion_class.' ">
                                <h3 class="">'.__('Service','nd-sports-booking').' :</h3>

                                <div id="nd_spt_occasion_cont_change" class="nd_spt_section nd_spt_position_relative">';


                                    for ( $nd_spt_occasions_array_i = 0; $nd_spt_occasions_array_i < count($nd_spt_occasions_array); $nd_spt_occasions_array_i++) { 
                                        
                                        if ( $nd_spt_occasions_array_i != 0 ) { $nd_spt_oc_class = ' nd_spt_display_none '; } else { $nd_spt_oc_class = ''; }
                                        
                                        $nd_spt_result .= '<div class="nd_spt_occas_single '.$nd_spt_oc_class.' nd_spt_occas_single_'.$nd_spt_occasions_array_i.'">'.$nd_spt_occasions_array[$nd_spt_occasions_array_i].'</div>';   
                                        
                                         
                                    }   
                                
                                    $nd_spt_result .= '<ul class="nd_spt_ul_occasion nd_spt_display_none">';

                                    for ( $nd_spt_occasions_array_i = 0; $nd_spt_occasions_array_i < count($nd_spt_occasions_array); $nd_spt_occasions_array_i++) { 
                                        
                                        if ( $nd_spt_occasions_array_i == 0 ) { $nd_spt_oc_class = ' nd_spt_bg_color_blue '; } else { $nd_spt_oc_class = ' '; }
                                        
                                        $nd_spt_result .= '<li data-occasion="'.$nd_spt_occasions_array_i.'" class="nd_spt_ulli_occasion '.$nd_spt_oc_class.' ">'.$nd_spt_occasions_array[$nd_spt_occasions_array_i].'</li>';   
                                          
                                    } 

                                    $nd_spt_result .= '</ul>';


                                $nd_spt_result .= '
                                </div>
                            
                            </div>

                            <input readonly class="nd_spt_display_none_important" type="text" name="nd_spt_occasion" id="nd_spt_occasion" value="'.$nd_spt_occasion_default_value.'">

                        </div>
                        <!--END SERVICE-->

                    </div>';




                    //START inline script
                    $nd_spt_searc_comp_timee_code = '
                        
                        jQuery(document).ready(function() {

                        jQuery( function ( $ ) {

                          $(".nd_spt_time").click(function() {

                            $(".nd_spt_time").removeClass("nd_spt_bg_color_blue");
                            var nd_spt_calendar_time_select = $(this).attr("data-time");
                            $(this).addClass("nd_spt_bg_color_blue");

                            $("#nd_spt_time").val(nd_spt_calendar_time_select);

                          }); 
                          
                        });

                      });

                    ';
                    wp_add_inline_script( 'nd_spt_calendar_script', $nd_spt_searc_comp_timee_code );
                    //END inline script


                    $nd_spt_result .= '
                    <div id="nd_spt_time_section" class="nd_spt_section">
                        <h3 class="">'.__('Time','nd-sports-booking').' :</h3>
                    
                        <ul id="nd_spt_all_time_slots_container" class="nd_spt_margin_0 nd_spt_padding_0 nd_spt_list_style_none">
                            '.nd_spt_get_timing($nd_spt_date_default,$nd_spt_players_default,$nd_spt_sport_id_first).'
                        </ul>
                    </div>
                    <!--END TIME-->



                    <div id="nd_spt_btn_go_to_booking_container" class="nd_spt_section">
                        <button class="nd_options_first_font" id="nd_spt_btn_go_to_booking" onclick="nd_spt_go_to_booking()">'.__('BOOK NOW','nd-sports-booking').'</button>
                    </div>


                </div>

            </div>
            </div>

        ';

    }





    return $nd_spt_result;
  

}
//END
add_shortcode('nd_spt_booking_form', 'nd_spt_shortcode_booking_form');





//START function for AJAX
function nd_spt_calendar_php() {

    check_ajax_referer( 'nd_spt_nonce', 'nd_spt_calendar_security' );

    $nd_spt_real_month_today = date('m');
    $nd_spt_real_day_today = date('d');
    $nd_spt_real_year_today = date('Y');

    //recover var
    $nd_spt_month_today = sanitize_text_field($_GET['nd_spt_month']);
    $nd_spt_year_today = sanitize_text_field($_GET['nd_spt_year']);
    
    $nd_spt_selected_date = sanitize_text_field($_GET['nd_spt_selected_date']);
    $nd_spt_selected_dates = explode("-", $nd_spt_selected_date);
    $nd_spt_selected_year = $nd_spt_selected_dates[0];
    $nd_spt_selected_month = $nd_spt_selected_dates[1];
    $nd_spt_selected_day = $nd_spt_selected_dates[2];

    $nd_spt_day_today = date("d");
    $nd_spt_new_date = $nd_spt_year_today.'-'.$nd_spt_month_today.'-'.$nd_spt_day_today;
    $nd_spt_tot_days_this_month = cal_days_in_month(CAL_GREGORIAN, $nd_spt_month_today, $nd_spt_year_today);

    //calculate next and prev date
    $nd_spt_next_month = nd_spt_get_next_prev_month_year($nd_spt_new_date,'month','next');
    $nd_spt_next_year = nd_spt_get_next_prev_month_year($nd_spt_new_date,'year','next');
    $nd_spt_prev_month = nd_spt_get_next_prev_month_year($nd_spt_new_date,'month','prev');
    $nd_spt_prev_year = nd_spt_get_next_prev_month_year($nd_spt_new_date,'year','prev');

    //variables
    $nd_spt_date_cell_width = 100/$nd_spt_tot_days_this_month;
    $nd_spt_get_month_name_date = $nd_spt_year_today.'-'.$nd_spt_month_today.'-1';
    $nd_spt_calendar = '';

    //prev button
    if ( $nd_spt_month_today == $nd_spt_real_month_today AND $nd_spt_year_today == $nd_spt_real_year_today ) { 
        $nd_spt_button_prev = '
        <div class="nd_spt_section nd_spt_height_1"></div>';
    }else{
        $nd_spt_button_prev = '
        <input type="hidden" name="nd_spt_prev_month" id="nd_spt_prev_month" value="'.$nd_spt_prev_month.'">
        <input type="hidden" name="nd_spt_prev_year" id="nd_spt_prev_year" value="'.$nd_spt_prev_year.'">
        <button onclick="nd_spt_calendar(1)" class="nd_spt_prev_next_cal nd_spt_float_left" type="button">'.__('Prev','nd-sports-booking').'</button>';
    }

    //START CALENDAR CONTENT
    $nd_spt_calendar .= '
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
        

        <div class="nd_spt_section nd_spt_height_10"></div> 


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

                if ( strlen($nd_spt_i) == 1 ) {  
                    $nd_spt_i_visual = '0'.$nd_spt_i;    
                }else{
                    $nd_spt_i_visual = $nd_spt_i; 
                }
                $nd_spt_calendar .= '<div class="nd_spt_float_left nd_spt_width_14_percentage"><p data-date="'.$nd_spt_year_today.'-'.$nd_spt_month_today.'-'.$nd_spt_i_visual.'" class="'.$nd_spt_class.'">'.$nd_spt_i.'</p></div>';      

            }

        $nd_spt_calendar .= '
        </div>';

    $nd_spt_calendar .= '
    </div>';
    //END CALENDAR

    $nd_spt_calendar_result = '';
    $nd_spt_calendar_result .= $nd_spt_calendar;

    $nd_spt_allowed_html = [
        'div'      => [ 
            'style' => [],
            'id' => [],
            'class' => [],
        ],
        'h3'      => [ 
            'style' => [],
            'id' => [],
            'class' => [],
        ],
        'input'      => [  
            'type' => [], 
            'name' => [],
            'value' => [],
            'style' => [],
            'id' => [],
            'class' => [],
        ],
        'button'      => [  
            'onclick' => [],
            'type' => [],
            'style' => [],
            'id' => [],
            'class' => [],
        ],
        'p'      => [  
            'data-date' => [],
            'style' => [],
            'id' => [],
            'class' => [],
        ],
        'strong'      => [ 
            'style' => [],
            'id' => [],
            'class' => [],
        ],
    ];
 
    echo wp_kses( $nd_spt_calendar_result, $nd_spt_allowed_html );

    die();

}
add_action( 'wp_ajax_nd_spt_calendar_php', 'nd_spt_calendar_php' );
add_action( 'wp_ajax_nopriv_nd_spt_calendar_php', 'nd_spt_calendar_php' );





//START function for AJAX
function nd_spt_booking_php() {

    check_ajax_referer( 'nd_spt_nonce', 'nd_spt_go_to_booking_security' );

    //recover var
    $nd_spt_sport = sanitize_text_field($_GET['nd_spt_sport']);
    $nd_spt_players = sanitize_text_field($_GET['nd_spt_players']);
    $nd_spt_date = sanitize_text_field($_GET['nd_spt_date']);
    $nd_spt_time = sanitize_text_field($_GET['nd_spt_time']);
    $nd_spt_occasion = sanitize_text_field($_GET['nd_spt_occasion']);
    $nd_spt_action_return = sanitize_text_field($_GET['nd_spt_action_return']);

    //set variables
    $nd_spt_booking_result = '';
    $nd_spt_booking_result_image = '';


    //image rest
    $nd_spt_image_id = get_post_thumbnail_id( $nd_spt_sport );
    $nd_spt_image_src = wp_get_attachment_image_src( $nd_spt_image_id,'large');
    $nd_spt_booking_result_image .= '

    <style>
    #nd_spt_booking_step_resume {

        background-image:url('.$nd_spt_image_src[0].');
        background-repeat:no-repeat;
        background-size: cover;
        background-position: center;
    }  
    </style>';


    //occasion
    $nd_spt_occasions = get_option('nd_spt_occasions');
    $nd_spt_occasions_array = explode(',', $nd_spt_occasions );
    $nd_spt_occasion_title = $nd_spt_occasions_array[$nd_spt_occasion];

    include realpath(dirname( __FILE__ ).'/include/nd_spt_booking_details.php');

    $nd_spt_allowed_html = [
        'div'      => [  
            'id' => [],
            'class' => [],
            'style' => [],
        ],
        'input'      => [  
            'type' => [], 
            'name' => [], 
            'value' => [],
            'checked' => [],
            'id' => [],
            'class' => [],
            'style' => [],
        ],
        'img'      => [  
            'src' => [],
            'id' => [],
            'class' => [],
            'style' => [],
        ],
        'p'      => [ 
            'id' => [],
            'class' => [],
            'style' => [],
        ],
        'h1'      => [ 
            'id' => [],
            'class' => [],
            'style' => [],
        ],
        'h3'      => [ 
            'id' => [],
            'class' => [],
            'style' => [],
        ],
        'span'      => [ 
            'id' => [],
            'class' => [],
            'style' => [],
        ],
        'label'      => [  
            'for' => [],
            'id' => [],
            'class' => [],
            'style' => [],
        ],
        'textarea'      => [  
            'rows' => [],
            'name' => [],
            'id' => [],
            'class' => [],
            'style' => [],
        ],
        'a'      => [  
            'target' => [], 
            'href' => [],
            'id' => [],
            'class' => [],
            'style' => [],
        ],
        'button'      => [  
            'onclick' => [],
            'id' => [],
            'class' => [],
            'style' => [],
        ],
    ];
 
    echo wp_kses( $nd_spt_booking_result, $nd_spt_allowed_html );

    die();

}
add_action( 'wp_ajax_nd_spt_booking_php', 'nd_spt_booking_php' );
add_action( 'wp_ajax_nopriv_nd_spt_booking_php', 'nd_spt_booking_php' );















//php function for validation fields on booking form
function nd_spt_validate_fields_php_function() {

check_ajax_referer( 'nd_spt_nonce', 'nd_spt_validate_fields_security' );

    //validate if a number is numeric
function nd_spt_is_numeric($nd_spt_number){

  if ( is_numeric($nd_spt_number) ) {
    return 1;
  }else{
    return 0;
  }

}


//validate if email is valid
function nd_spt_is_email($nd_spt_email){

  if (filter_var($nd_spt_email, FILTER_VALIDATE_EMAIL)) {
    return 1;  
  } else {
    return 0;
  }


}



  //recover datas
  $nd_spt_name = sanitize_text_field($_GET['nd_spt_name']);
  $nd_spt_surname = sanitize_text_field($_GET['nd_spt_surname']);
  $nd_spt_email = sanitize_email($_GET['nd_spt_email']);
  $nd_spt_message = sanitize_text_field($_GET['nd_spt_message']);
  $nd_spt_phone = sanitize_text_field($_GET['nd_spt_phone']);
  $nd_spt_term = sanitize_text_field($_GET['nd_spt_term']);
  
  //declare
  $nd_spt_string_result = '';


  //name
  if ( $nd_spt_name == '' ) {

    $nd_spt_result_name = 0; 

    $nd_spt_string_result .= '<span class="nd_spt_validation_errors nd_spt_margin_left_20 nd_spt_color_red">'.__('MANDATORY','nd-sports-booking').'[divider]'.'</span>';     

  }else{

    $nd_spt_result_name = 1;

    $nd_spt_string_result .= ' [divider]';   

  }

  //surname
  if ( $nd_spt_surname == '' ) {

    $nd_spt_result_surname = 0; 

    $nd_spt_string_result .= '<span class="nd_spt_validation_errors nd_spt_margin_left_20 nd_spt_color_red">'.__('MANDATORY','nd-sports-booking').'[divider]'.'</span>';     

  }else{

    $nd_spt_result_surname = 1;

    $nd_spt_string_result .= ' [divider]'; 

  }


  //email
  if ( $nd_spt_email == '' ) {

    $nd_spt_result_email = 0; 

    $nd_spt_string_result .= '<span class="nd_spt_validation_errors nd_spt_margin_left_20 nd_spt_color_red">'.__('MANDATORY','nd-sports-booking').'[divider]'.'</span>';     

  }elseif ( nd_spt_is_email($nd_spt_email) == 0 ) {

    $nd_spt_result_email = 0; 

    $nd_spt_string_result .= '<span class="nd_spt_validation_errors nd_spt_margin_left_20 nd_spt_color_red">'.__('NOT VALID','nd-sports-booking').'[divider]'.'</span>';  

  }else{

    $nd_spt_result_email = 1;

    $nd_spt_string_result .= ' [divider]'; 

  }



  //phone
  if ( $nd_spt_phone == '' ) {

    $nd_spt_result_phone = 0; 

    $nd_spt_string_result .= '<span class="nd_spt_validation_errors nd_spt_margin_left_20 nd_spt_color_red">'.__('MANDATORY','nd-sports-booking').'[divider]'.'</span>';     

  }elseif ( 
($nd_spt_phone) == 0 ) {

    $nd_spt_result_phone = 0; 

    $nd_spt_string_result .= '<span class="nd_spt_validation_errors nd_spt_margin_left_20 nd_spt_color_red">'.__('NOT VALID','nd-sports-booking').'[divider]'.'</span>';  

  }else{

    $nd_spt_result_phone = 1;

    $nd_spt_string_result .= ' [divider]'; 

  }



  //message
  if ( strlen($nd_spt_message) >= 250 ) {

    $nd_spt_result_message = 0; 

    $nd_spt_string_result .= '<span class="nd_spt_validation_errors nd_spt_margin_left_20 nd_spt_color_red">'.__('THE MAXIMUM ALLOWED CHARACTERS IS 250','nd-sports-booking').'[divider]'.'</span>';     

  }else{

    $nd_spt_result_message = 1;

    $nd_spt_string_result .= ' [divider]'; 

  }


  //term
  if ( $nd_spt_term == 0 ){

    $nd_spt_result_term = 0; 

    $nd_spt_string_result .= '<span class="nd_spt_validation_errors nd_spt_margin_left_20 nd_spt_color_red">'.__('MANDATORY','nd-sports-booking').'[divider]'.'</span>';     


  }else{

    $nd_spt_result_term = 1;

    $nd_spt_string_result .= ' [divider]'; 

  }



  //coupon
  if ( $nd_spt_coupon == '' ) {

    $nd_spt_result_coupon = 1; 

    $nd_spt_string_result .= ' [divider]'; 

  }else{

    if ( nd_spt_is_coupon_valid($nd_spt_coupon) == 1 ){

      $nd_spt_result_coupon = 1; 

      $nd_spt_string_result .= ' [divider]'; 

    }else{

      $nd_spt_result_coupon = 0;

      $nd_spt_string_result .= '<span class="nd_spt_validation_errors nd_spt_margin_left_20 nd_spt_color_red">'.__('NOT VALID','nd-sports-booking').'[divider]'.'</span>';     

    }
    
  }



  //Determiante the final result
  if ( $nd_spt_result_name == 1 AND  $nd_spt_result_surname == 1 AND $nd_spt_result_email == 1 AND $nd_spt_result_phone == 1 AND $nd_spt_result_message == 1 AND $nd_spt_result_term == 1 AND $nd_spt_result_coupon == 1 ){
    
    echo esc_attr(1);
  
  }else{
    
    $nd_spt_allowed_html = [
        'span'      => [ 
            'class' => [],
        ],
    ];
 
    echo wp_kses( $nd_spt_string_result, $nd_spt_allowed_html );

  }

  
     
  //close the function to avoid wordpress errors
  die();

}
add_action( 'wp_ajax_nd_spt_validate_fields_php_function', 'nd_spt_validate_fields_php_function' );
add_action( 'wp_ajax_nopriv_nd_spt_validate_fields_php_function', 'nd_spt_validate_fields_php_function' );
















//START function for AJAX
function nd_spt_checkout_php() {

    check_ajax_referer( 'nd_spt_nonce', 'nd_spt_go_to_checkout_security' );

    //recover var
    $nd_spt_sport = sanitize_text_field($_GET['nd_spt_sport']);
    $nd_spt_players = sanitize_text_field($_GET['nd_spt_players']);
    $nd_spt_date = sanitize_text_field($_GET['nd_spt_date']);
    $nd_spt_time = sanitize_text_field($_GET['nd_spt_time']);
    $nd_spt_occasion = sanitize_text_field($_GET['nd_spt_occasion']);
    $nd_spt_booking_form_name = sanitize_text_field($_GET['nd_spt_booking_form_name']);
    $nd_spt_booking_form_surname = sanitize_text_field($_GET['nd_spt_booking_form_surname']);
    $nd_spt_booking_form_email = sanitize_email($_GET['nd_spt_booking_form_email']);
    $nd_spt_booking_form_phone = sanitize_text_field($_GET['nd_spt_booking_form_phone']);
    $nd_spt_booking_form_requests = sanitize_text_field($_GET['nd_spt_booking_form_requests']);
    $nd_spt_action_return = sanitize_text_field($_GET['nd_spt_action_return']);


    //set variables
    $nd_spt_checkout_result = '';
    $nd_spt_checkout_result_image = '';


    //image rest
    $nd_spt_image_id = get_post_thumbnail_id( $nd_spt_sport );
    $nd_spt_image_src = wp_get_attachment_image_src( $nd_spt_image_id,'large');
    $nd_spt_checkout_result_image .= '

    <style>
    #nd_spt_checkout_step_resume {

        background-image:url('.$nd_spt_image_src[0].');
        background-repeat:no-repeat;
        background-size: cover;
        background-position: center;
    }  
    </style>';


    //occasion
    $nd_spt_occasions = get_option('nd_spt_occasions');
    $nd_spt_occasions_array = explode(',', $nd_spt_occasions );
    $nd_spt_occasion_title = $nd_spt_occasions_array[$nd_spt_occasion];

    include realpath(dirname( __FILE__ ).'/include/nd_spt_booking_confirm.php');

    $nd_spt_allowed_html = [
        'div'      => [ 
          'id' => [],
          'class' => [],
          'style' => [],
          'role' => [],
        ],
        'input'      => [
          'type' => [],
          'name' => [],
          'value' => [],
          'id' => [],
          'class' => [],
          'style' => [],
        ],
        'img'      => [
          'alt' => [],
          'width' => [],
          'src' => [],
          'id' => [],
          'class' => [],
          'style' => [],
        ],
        'p'      => [
          'id' => [],
          'class' => [],
          'style' => [],
        ],
        'h1'      => [
          'id' => [],
          'class' => [],
          'style' => [],
        ],
        'h3'      => [
          'id' => [],
          'class' => [],
          'style' => [],
        ],
        'span'      => [
          'id' => [],
          'class' => [],
          'style' => [],
        ],
        'style'      => [
          'id' => [],
          'class' => [],
          'style' => [],
        ],
        'form'      => [
          'action' => [], 
          'method' => [],
          'target' => [],
          'id' => [],
          'class' => [],
          'style' => [],
        ],             
        'iframe'      => [ 
          'name' => [], 
          'frameborder' => [], 
          'allowtransparency' => [], 
          'scrolling' => [], 
          'allow' => [], 
          'src' => [],
          'title' => [], 
          'id' => [],
          'class' => [],
          'style' => [],
        ],            
        'input'      => [ 
          'type' => [],
          'name' => [],
          'value' => [],
          'aria-hidden' => [],
          'aria-label' => [], 
          'autocomplete' => [], 
          'maxlength' => [],
          'id' => [],
          'class' => [],
          'style' => [],
        ],
        'script'      => [ 
          'type' => [],
          'id' => [],
          'class' => [],
          'style' => [],
        ],                
        'button'      => [ 
          'onclick' => [],
          'id' => [],
          'class' => [],
          'style' => [],
        ],                       
    ];

    echo wp_kses( $nd_spt_checkout_result, $nd_spt_allowed_html );

    die();

}
add_action( 'wp_ajax_nd_spt_checkout_php', 'nd_spt_checkout_php' );
add_action( 'wp_ajax_nopriv_nd_spt_checkout_php', 'nd_spt_checkout_php' );














//START function for AJAX
function nd_spt_add_to_db_php() {

    check_ajax_referer( 'nd_spt_nonce', 'nd_spt_add_to_db_security' );

    //recover var
    $nd_spt_sport = sanitize_text_field($_GET['nd_spt_sport']);
    $nd_spt_players = sanitize_text_field($_GET['nd_spt_players']);
    $nd_spt_date = sanitize_text_field($_GET['nd_spt_date']);
    $nd_spt_time_start = sanitize_text_field($_GET['nd_spt_time']);
    $nd_spt_occasion = sanitize_text_field($_GET['nd_spt_occasion']);
    $nd_spt_booking_form_name = sanitize_text_field($_GET['nd_spt_booking_form_name']);
    $nd_spt_booking_form_surname = sanitize_text_field($_GET['nd_spt_booking_form_surname']);
    $nd_spt_booking_form_email = sanitize_email($_GET['nd_spt_booking_form_email']);
    $nd_spt_booking_form_phone = sanitize_text_field($_GET['nd_spt_booking_form_phone']);
    $nd_spt_booking_form_requests = sanitize_text_field($_GET['nd_spt_booking_form_requests']);
    $nd_spt_order_type = sanitize_text_field($_GET['nd_spt_order_type']);
    $nd_spt_order_status = sanitize_text_field($_GET['nd_spt_order_status']);

    $nd_spt_deposit = 0;
    $nd_spt_tx = rand(100000000,999999999);
    $nd_spt_currency = __('Not Set','nd-sports-booking');

    //sport
    $nd_spt_image_id = get_post_thumbnail_id( $nd_spt_sport );
    $nd_spt_image_src = wp_get_attachment_image_src( $nd_spt_image_id,'thumbnail');

    //occasion
    $nd_spt_occasions = get_option('nd_spt_occasions');
    $nd_spt_occasions_array = explode(',', $nd_spt_occasions );
    $nd_spt_occasion_title = $nd_spt_occasions_array[$nd_spt_occasion];

    //calculate end time
    $nd_spt_booking_duration = get_option('nd_spt_booking_duration');
    $nd_spt_booking_duration_insert = $nd_spt_booking_duration-1;
    $nd_spt_time_end = date("G:i", strtotime('+'.$nd_spt_booking_duration_insert.' minutes', strtotime($nd_spt_time_start))); //add minutes slot to start time

    //order type name
    if ( $nd_spt_order_type == 'request' ) { $nd_spt_order_type_name = __('Request','nd-sports-booking');  }


    //add reservation in d
    nd_spt_add_booking_in_db(
        $nd_spt_sport,
        $nd_spt_players,
        $nd_spt_date,
        $nd_spt_time_start,
        $nd_spt_time_end,
        $nd_spt_occasion,
        $nd_spt_booking_form_name,
        $nd_spt_booking_form_surname,
        $nd_spt_booking_form_email,
        $nd_spt_booking_form_phone,
        $nd_spt_booking_form_requests,
        $nd_spt_order_type,
        $nd_spt_order_status,
        $nd_spt_deposit,
        $nd_spt_tx,
        $nd_spt_currency
    );


    $nd_spt_add_to_db_result = '';
    
    include realpath(dirname( __FILE__ ).'/include/nd_spt_booking_thanks.php');

    $nd_spt_allowed_html = [
        'div'      => [ 
            'id' => [],
            'class' => [],
            'style' => [],
        ],
        'h3'      => [
            'id' => [],
            'class' => [],
            'style' => [],
        ],
        'img'      => [
            'alt' => [],
            'width' => [],
            'src' => [],
            'id' => [],
            'class' => [],
            'style' => [],
        ],
        'p'      => [
            'id' => [],
            'class' => [],
            'style' => [],
        ],
        'strong'      => [
            'id' => [],
            'class' => [],
            'style' => [],
        ],
        'span'      => [
            'id' => [],
            'class' => [],
            'style' => [],
        ],
    ];
 
    echo wp_kses( $nd_spt_add_to_db_result, $nd_spt_allowed_html );

    die();

}
add_action( 'wp_ajax_nd_spt_add_to_db_php', 'nd_spt_add_to_db_php' );
add_action( 'wp_ajax_nopriv_nd_spt_add_to_db_php', 'nd_spt_add_to_db_php' );







function nd_spt_get_timing_php(){

    check_ajax_referer( 'nd_spt_nonce', 'nd_spt_update_timing_security' );

    //recover date
    $nd_spt_date_select = sanitize_text_field($_GET['nd_spt_date_select']);
    $nd_spt_player_select = sanitize_text_field($_GET['nd_spt_player_select']);
    $nd_spt_sport = sanitize_text_field($_GET['nd_spt_sport']);

    $nd_spt_get_timing_result = '';

    $nd_spt_get_timing_result .= nd_spt_get_timing($nd_spt_date_select,$nd_spt_player_select,$nd_spt_sport);

    $nd_spt_allowed_html = [
        'div'      => [
            'id' => [],
            'class' => [],
            'style' => [],
        ],
        'ul'      => [
            'id' => [],
            'class' => [],
            'style' => [],
        ],
        'li'      => [
            'id' => [],
            'class' => [],
            'style' => [],
        ],
        'script'      => [],
        'p'      => [
            'id' => [],
            'class' => [],
            'style' => [],
            'data-time' => [],
        ],
        'input'      => [ 
            'readonly' => [], 
            'class' => [],
            'type' => [],
            'name' => [],
            'id' => [],
            'value' => [],
        ],
    ];
 
    echo wp_kses( $nd_spt_get_timing_result, $nd_spt_allowed_html );

    die();


}
add_action( 'wp_ajax_nd_spt_get_timing_php', 'nd_spt_get_timing_php' );
add_action( 'wp_ajax_nopriv_nd_spt_get_timing_php', 'nd_spt_get_timing_php' );






