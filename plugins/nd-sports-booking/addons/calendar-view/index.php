<?php




add_action('nd_spt_add_menu_page_after_order','nd_spt_add_settings_menu_calendar_view');
function nd_spt_add_settings_menu_calendar_view(){

  add_submenu_page( 'nd-sports-booking-settings','Calendar', __('Calendar View','nd-sports-booking'), 'manage_options', 'nd-sports-booking-settings-calendar-view', 'nd_spt_add_calendar_view' );

}


function nd_spt_add_calendar_view(){

    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-datepicker-css', esc_url(plugins_url('jquery-ui-datepicker.css', __FILE__ )) );

    //recover variables
    $nd_spt_arrive_from_filter = sanitize_text_field($_POST['nd_spt_arrive_from_filter']); if ( $nd_spt_arrive_from_filter == '' ) { $nd_spt_arrive_from_filter = 0; }
    $nd_spt_order_status = sanitize_text_field($_POST['nd_spt_order_status']); if ( $nd_spt_order_status == '' ) { $nd_spt_order_status = 'confirmed'; }
    $nd_spt_date = sanitize_text_field($_POST['nd_spt_date']); if ( $nd_spt_date == '' ) { $nd_spt_date = date("Y-m-d"); }
    $nd_spt_sport = sanitize_text_field($_POST['nd_spt_sport']); if ( $nd_spt_sport == '' ) { 

        $nd_spt_rooms_args = array( 'posts_per_page' => 1, 'post_type'=> 'nd_spt_cpt_1', 'order' => 'ASC' );
        $nd_spt_rooms = get_posts($nd_spt_rooms_args);
        foreach ($nd_spt_rooms as $nd_spt_room) { $nd_spt_sport = $nd_spt_room->ID; } 

    }

    //get datas
    $nd_spt_get_opening_hour = nd_spt_get_opening_hour();
    $nd_spt_get_closing_hour = nd_spt_get_closing_hour();

    //db
    global $wpdb;
    $nd_spt_table_name = $wpdb->prefix . 'nd_spt_booking';
    

    //query
    $nd_spt_orders = $wpdb->get_results( "SELECT * FROM $nd_spt_table_name WHERE nd_spt_date = '$nd_spt_date' AND nd_spt_order_status = '$nd_spt_order_status' AND nd_spt_sport = '$nd_spt_sport'");
   

    $nd_spt_add_calendar_view = '';
    $nd_spt_add_calendar_view .= '

    <div class="nd_spt_section nd_spt_padding_right_20 nd_spt_padding_left_2 nd_spt_box_sizing_border_box nd_spt_margin_top_25">
        
        <h1 class="nd_spt_margin_0" style="font-size: 23px; font-weight: 400;">'.__('Calendar View','nd-sports-booking').'</h1>

        <ul class="subsubsub">
            <li class=""><a href="#" class="">'.__('All Bookings','nd-sports-booking').' <span class="count">('.count($nd_spt_orders).')</span></a></li>
        </ul>

        <div class="nd_spt_section nd_spt_height_10"></div>

        <div class="nd_spt_section">
            
            <form method="POST"> 

                <input type="hidden" name="nd_spt_arrive_from_filter" value="1">';

                $nd_spt_add_calendar_view .= '
                <div class="nd_spt_display_table">
                    <div class="nd_spt_display_table_cell nd_spt_vertical_align_middle nd_spt_padding_right_10">';

                    //sport
                    $nd_spt_rooms_args = array( 'posts_per_page' => -1, 'post_type'=> 'nd_spt_cpt_1' );
                    $nd_spt_rooms = get_posts($nd_spt_rooms_args); 
                    $nd_spt_add_calendar_view .= '
                    <select class="nd_spt_min_width_150" name="nd_spt_sport">';
                    foreach ($nd_spt_rooms as $nd_spt_room) { 
                        $nd_spt_add_calendar_view .= '<option '; if ( $nd_spt_sport == $nd_spt_room->ID ){ $nd_spt_add_calendar_view .= 'selected="selected"'; } $nd_spt_add_calendar_view .= ' value="'.$nd_spt_room->ID.'">'.$nd_spt_room->post_title.'</option>';
                    }
                    $nd_spt_add_calendar_view .= '
                    </select>

                </div>';
                //end sports



                //date
                $nd_spt_add_calendar_view .= '
                <div class="nd_spt_display_table_cell nd_spt_vertical_align_middle nd_spt_padding_right_10">
                    <input style="line-height:20px;" type="text" id="nd_spt_datepicker" name="nd_spt_date" value="'.$nd_spt_date.'">
                </div>
                ';
                //end date


                //START inline script
                $nd_spt_search_comp_l1_datepicker_code = '

                    jQuery(document).ready(function() {

                        jQuery( function ( $ ) {

                            $( function() {

                                $( "#nd_spt_datepicker" ).datepicker({
                                  defaultDate: "+1w",
                                  firstDay: 0,
                                  dateFormat: "yy-mm-dd",
                                  monthNames: ["'.__('January','nd-sports-booking').'","'.__('February','nd-sports-booking').'","'.__('March','nd-sports-booking').'","'.__('April','nd-sports-booking').'","'.__('May','nd-sports-booking').'","'.__('June','nd-sports-booking').'", "'.__('July','nd-sports-booking').'","'.__('August','nd-sports-booking').'","'.__('September','nd-sports-booking').'","'.__('October','nd-sports-booking').'","'.__('November','nd-sports-booking').'","'.__('December','nd-sports-booking').'"],
                                  monthNamesShort: [ "'.__('Jan','nd-sports-booking').'", "'.__('Feb','nd-sports-booking').'", "'.__('Mar','nd-sports-booking').'", "'.__('Apr','nd-sports-booking').'", "'.__('Maj','nd-sports-booking').'", "'.__('Jun','nd-sports-booking').'", "'.__('Jul','nd-sports-booking').'", "'.__('Aug','nd-sports-booking').'", "'.__('Sep','nd-sports-booking').'", "'.__('Oct','nd-sports-booking').'", "'.__('Nov','nd-sports-booking').'", "'.__('Dec','nd-sports-booking').'" ],
                                  dayNamesMin: ["'.__('S','nd-sports-booking').'","'.__('M','nd-sports-booking').'","'.__('T','nd-sports-booking').'","'.__('W','nd-sports-booking').'","'.__('T','nd-sports-booking').'","'.__('F','nd-sports-booking').'", "'.__('S','nd-sports-booking').'"],
                                  nextText: "'.__('NEXT','nd-sports-booking').'",
                                  prevText: "'.__('PREV','nd-sports-booking').'",
                                  beforeShow : function() {
                                    $("#ui-datepicker-div").addClass( "nd_spt_calendar_view_backend" );
                                  }
                                });
                                

                            } );
                          
                        });

                      });
                  
                ';
                wp_add_inline_script( 'jquery-ui-datepicker', $nd_spt_search_comp_l1_datepicker_code );
                //END inline script


                //order status
                $nd_spt_add_calendar_view .= '
                <div class="nd_spt_display_table_cell nd_spt_vertical_align_middle nd_spt_padding_right_10">
                    <select class="nd_spt_min_width_150" name="nd_spt_order_status">
                        <option '; if( $nd_spt_order_status == 'confirmed' ){ $nd_spt_add_calendar_view .= 'selected="selected"'; }  $nd_spt_add_calendar_view .= ' value="confirmed">'.__('Confirmed','nd-sports-booking').'</option>
                        <option '; if( $nd_spt_order_status == 'pending' ){ $nd_spt_add_calendar_view .= 'selected="selected"'; }  $nd_spt_add_calendar_view .= ' value="pending">'.__('Pending','nd-sports-booking').'</option>
                    </select>
                </div>';
                //end order status


                $nd_spt_add_calendar_view .= '
                <div class="nd_spt_display_table_cell nd_spt_vertical_align_middle nd_spt_padding_right_10">
                    <input type="submit" class="button" value="'.__('Filter','nd-sports-booking').'">
                </div>';

                
                $nd_spt_add_calendar_view .= '
                </div>';


            $nd_spt_add_calendar_view .= '
            </form>

        </div>


        <div class="nd_spt_section">

            <div style="background-color:#fff; border:1px solid #e1e1e1; width:30%; border-right-width: 0px;" class="nd_spt_section nd_spt_margin_top_20 nd_spt_box_sizing_border_box">
            
                <div id="nd_spt_order_info_container" class="nd_spt_float_left nd_spt_width_100_percentage">
                    <div style="border-bottom: 1px solid #e1e1e1;" class="nd_spt_section">
 
                            <p style="padding:0px 12px;" class="nd_spt_section"><span class="nd_spt_section">'.__('Bookings','nd-sports-booking').'</span></p>

                    </div>';

                    $nd_spt_bg_i = 0;
                    $nd_spt_add_calendar_view .= '<div class="nd_spt_section">'; 
                    foreach ( $nd_spt_orders as $nd_spt_order ) 
                    {

                        //bg class
                        if ( $nd_spt_bg_i & 1 ) {
                            $nd_spt_bg_class = ' nd_spt_tr_lightt ';
                        }else{
                            $nd_spt_bg_class = ' nd_spt_tr_darkk ';
                        }

                        //get avatar
                        $nd_spt_account_avatar_url_args = array( 'size'   => 100 );
                        $nd_spt_account_avatar_url = get_avatar_url($nd_spt_order->nd_spt_booking_form_email, $nd_spt_account_avatar_url_args);

                        $nd_spt_add_calendar_view .= '

                            <div style="padding:12px; height:65px;" class=" '.$nd_spt_bg_class.' nd_spt_box_sizing_border_box nd_spt_section">


                                <div style="width:50px;" class="nd_spt_float_left">
                                  <img width="40" src="'.$nd_spt_account_avatar_url.'">
                                </div>
                                <div class="nd_spt_float_left">
                                  <span class="nd_spt_section">'.$nd_spt_order->nd_spt_booking_form_name.'</span>
                                  <form action="'.admin_url('admin.php?page=nd-sports-booking-settings-orders').'" class="nd_spt_float_left" method="POST">
                                    <input type="hidden" name="edit_order_id" value="'.$nd_spt_order->id.'">
                                    <input type="submit" class="nd_spt_edit" value="'.__('View','nd-sports-booking').'">
                                  </form>
                                  <form action="'.admin_url('admin.php?page=nd-sports-booking-settings-orders').'" class="nd_spt_float_left nd_spt_padding_left_10" method="POST">
                                    <input type="hidden" name="delete_order_id" value="'.$nd_spt_order->id.'">
                                    <input type="submit" class="nd_spt_delete" value="'.__('Delete','nd-sports-booking').'">
                                  </form>
                                </div>


                            </div>

                        ';  

                         $nd_spt_bg_i = $nd_spt_bg_i + 1;

                    }
                    $nd_spt_add_calendar_view .= '</div>'; 

                $nd_spt_add_calendar_view .= '
                </div>

            </div>


        
            <div style="background-color:#fff; border:1px solid #e1e1e1; width:70%; overflow: scroll; border-left-width: 0px;" class="nd_spt_section nd_spt_margin_top_20 nd_spt_box_sizing_border_box">

                <div style="cursor:move;" id="nd_spt_order_container" class="nd_spt_float_left nd_spt_width_100_percentage">';
            
                $nd_spt_time_slot = $nd_spt_get_opening_hour;
                
                $nd_spt_width_i = 0;

                $nd_spt_add_calendar_view .= '<div style="border-bottom: 1px solid #e1e1e1;" class="nd_spt_section">';
                while ( strtotime($nd_spt_time_slot) <= strtotime($nd_spt_get_closing_hour) ) {

                    $nd_spt_add_calendar_view .= '
                    <div class="nd_spt_float_left nd_spt_text_align_center" style="width:50px">    
                        <p class="nd_spt_section"><span class="nd_spt_section">'.$nd_spt_time_slot.'</span></p>
                    </div>
                    ';

                    $nd_spt_time_slot = date("H:i", strtotime('+ 30 minutes', strtotime($nd_spt_time_slot)));
                    $nd_spt_width_i = $nd_spt_width_i + 1;

                }
                $nd_spt_add_calendar_view .= '</div>';

                $nd_spt_section_width = 50*$nd_spt_width_i;


                //START inline script
                $nd_spt_cal_view_style = '

                    #nd_spt_order_container { width:'.$nd_spt_section_width.'px; }

                    .nd_spt_order_active.pending{ background-color:#e68843; }
                    .nd_spt_order_active.confirmed{ background-color:#54ce59; }

                    .nd_spt_tr_lightt { background-color:#fff; }
                    .nd_spt_tr_darkk { background-color:#f9f9f9; }

                    .nd_spt_edit {
                        color: #0073aa;
                        cursor: pointer;
                        background: none;
                        border: 0px;
                        font-size: 13px;
                        padding: 0px; 
                      }
                      .nd_spt_edit:hover {
                        color:#00a0d2;  
                      }
                      .nd_spt_delete {
                        color: #a00;
                        cursor: pointer;
                        background: none;
                        border: 0px;
                        font-size: 13px;
                        padding: 0px; 
                      }
                  
                ';
                wp_add_inline_style( 'jquery-ui-datepicker-css', $nd_spt_cal_view_style );
                //END inline script



                //START ALL ORDERS
                if ( empty($nd_spt_orders) ) { 
                    //any orders
                }else{
        




                    $nd_spt_add_calendar_view .= '<div class="nd_spt_section nd_spt_all_order_content">';
                    $nd_spt_bg_i = 0;
                    foreach ( $nd_spt_orders as $nd_spt_order ) 
                    {
                
                        $nd_spt_order_time_start = $nd_spt_order->nd_spt_time_start;
                        $nd_spt_time_end = $nd_spt_order->nd_spt_time_end;

                        //bg class
                        if ( $nd_spt_bg_i & 1 ) {
                            $nd_spt_bg_class = ' nd_spt_tr_lightt ';
                        }else{
                            $nd_spt_bg_class = ' nd_spt_tr_darkk ';
                        }

                        $nd_spt_add_calendar_view .= '<div style="padding:12px 0px; height:65px;" class=" '.$nd_spt_bg_class.' nd_spt_box_sizing_border_box nd_spt_section nd_spt_single_order_content nd_spt_single_order_content_'.$nd_spt_order->id.'">';
                        $nd_spt_time_slot = $nd_spt_get_opening_hour;
                        while ( strtotime($nd_spt_time_slot) <= strtotime($nd_spt_get_closing_hour) ) {

                            if ( strtotime($nd_spt_time_slot) >= strtotime($nd_spt_order_time_start) AND strtotime($nd_spt_time_slot) <= strtotime($nd_spt_time_end) ) {
                                $nd_spt_slot_class = ' nd_spt_order_active '.$nd_spt_order->nd_spt_order_status.' ';
                            }else{
                                $nd_spt_slot_class = '';   
                            }

                            $nd_spt_add_calendar_view .= '
                            <div class=" '.$nd_spt_slot_class.' nd_spt_single_slot nd_spt_single_slot_'.str_replace(':','_',$nd_spt_time_slot).' nd_spt_float_left nd_spt_text_align_center" style="width:50px; margin-top:7px;">    
                                <p class="nd_spt_section"><span class="nd_spt_section"></span></p>
                            </div>
                            ';

                            $nd_spt_time_slot = date("H:i", strtotime('+ 30 minutes', strtotime($nd_spt_time_slot))); 

                        }
                        $nd_spt_add_calendar_view .= '</div>';

                        $nd_spt_bg_i = $nd_spt_bg_i+1;


                    }
                    $nd_spt_add_calendar_view .= '</div>';

                }


                $nd_spt_add_calendar_view .= '
                </div>
            </div>
        </div>
    </div>';  


    $nd_spt_allowed_html = [
        'div'      => [ 
            'class' => [],
            'id' => [],
            'style' => [],
        ],
        'h1'      => [
            'class' => [],
            'id' => [],
            'style' => [],
        ],
        'p'      => [
            'class' => [],
            'id' => [],
            'style' => [],
        ],
        'ul'      => [
            'class' => [],
            'id' => [],
            'style' => [],
        ],
        'li'      => [
            'class' => [],
            'id' => [],
            'style' => [],
        ],
        'a'      => [
            'href' => [],
            'class' => [],
            'id' => [],
            'style' => [],
        ],
        'span'      => [
            'class' => [],
            'id' => [],
            'style' => [],
        ],
        'form'      => [
            'method' => [],
            'class' => [],
            'id' => [],
            'style' => [],
            'action' => [],
        ],
        'input'      => [
            'type' => [],
            'name' => [],
            'value' => [],
            'class' => [],
            'id' => [],
            'style' => [],
        ],
        'select'      => [
            'name' => [],
            'class' => [],
            'id' => [],
            'style' => [],
        ],
        'img'      => [
            'width' => [],
            'src' => [],
        ],
        'option'      => [
            'value' => [],
            'selected' => [],
            'class' => [],
            'id' => [],
            'style' => [],
        ],
    ];

    echo wp_kses( $nd_spt_add_calendar_view, $nd_spt_allowed_html );

}


