<?php 
    global $wp_query;
    $post_id = $wp_query->get_queried_object_id();
    
    if ( is_page($post_id) OR is_single($post_id) ){
        $hidecon = get_post_meta($post_id, 'lead_call_buttons_options_hide_lead_call_buttons', true);
    } else {
        $hidecon = '';
    }
    
    if($hidecon != 'hide-lead-call-buttons'){
           
        $count          = 0;
        $bg_color       = LCB_get_setting( 'lead_call_buttons', 'general', 'bg-color' );
        $text_color     = LCB_get_setting( 'lead_call_buttons', 'general', 'text-color' );
        $btn_animation  = LCB_get_setting( 'lead_call_buttons', 'general', 'btn-animation' );
        $lcb_main_class = 'main_buttons';

        if ( $btn_animation ) { 
            $lcb_main_class .= ' main_buttons_animate';
        }
        
        if( empty($text_color) ) $text_color = "#fff";
        
        $callnow_title    = LCB_get_setting( 'lead_call_buttons', 'general', 'callnow-title' );
        $callnow_icon     = LCB_get_setting( 'lead_call_buttons', 'general', 'callnow-icon' );
        $callnow_number   = LCB_get_setting( 'lead_call_buttons', 'general', 'callnow-number' );
        $callnow_onclick  = LCB_get_setting( 'lead_call_buttons', 'general', 'callnow-onclick' );
        
        $schedule_title   = LCB_get_setting( 'lead_call_buttons', 'general', 'schedule-title' );
        $schedule_icon    = LCB_get_setting( 'lead_call_buttons', 'general', 'schedule-icon' );
        $schedule_link    = LCB_get_setting( 'lead_call_buttons', 'general', 'schedule-link' );
        $schedule_onclick = LCB_get_setting( 'lead_call_buttons', 'general', 'schedule-onclick' );
        
        $map_title        = LCB_get_setting( 'lead_call_buttons', 'general', 'map-title' );
        $map_icon         = LCB_get_setting( 'lead_call_buttons', 'general', 'map-icon' );
        $map_link         = LCB_get_setting( 'lead_call_buttons', 'general', 'map-link' );
        $map_onclick      = LCB_get_setting( 'lead_call_buttons', 'general', 'map-onclick' );
        
        if ( !empty ($callnow_number) ) { $count++; }
        if ( !empty ($schedule_link) ) { $count++; }
        if ( !empty ($map_link) ) { $count++; }
              
        if ( $count == 0) { 
            $layout_class = "";
            $main_div = " ";
        } if ( $count == 1 ) {
            $layout_class = "one-whole";
            $main_div = "<div class='".$lcb_main_class."' id='lcb_main_area'>";       
        } if ( $count == 2) { 
            $layout_class = "one-half";
            $main_div = "<div class='".$lcb_main_class."' id='lcb_main_area'>";     
        } if ( $count == 3) { 
            $layout_class = "one-third";
            $main_div = "<div class='".$lcb_main_class."' id='lcb_main_area'>";      
        } 
    ?>

    <!--Start Lead Call Buttons-->

    <?php

        echo $main_div;
        	 
    	if ( !empty ($callnow_number) ) { 
    	   
    	    $callnow_onclick   = ($callnow_onclick) ? $callnow_onclick : ''; 
            $callnow_onclick   = (!empty($callnow_onclick)) ? 'onclick="'.$callnow_onclick.'"' : ''; 
            $button_uniq_name  = (!empty($callnow_title)) ? strtolower(str_replace(' ', '_', $callnow_title)) : '';
            $button_uniq_class = ($button_uniq_name) ? 'lcb_'.$button_uniq_name.'_area' : ''; 
            $button_uniq_id    = ($button_uniq_name) ? 'id="lcb_'.$button_uniq_name.'_area"' : ''; ?>
                	
        	<div class="callnow_area on <?php echo esc_attr($layout_class.' '.$button_uniq_class); ?>" <?php echo esc_attr($button_uniq_id);?>>
                <a <?php echo $callnow_onclick; ?> href="<?php echo $callnow_number;?>">
            		<div class="callnow_bottom">
            			<span class="b_callnow">
                            <?php echo $callnow_icon; ?>
                            <?php echo $callnow_title; ?>
                        </span>
            		</div>
                </a>
        	</div>
    
    	<?php } if ( !empty ($schedule_link) ) { 
            
            $schedule_onclick  = ($schedule_onclick) ? $schedule_onclick : ''; 
            $schedule_onclick  = (!empty($schedule_onclick)) ? 'onclick="'.$schedule_onclick.'"' : ''; 
            $button_uniq_name  = (!empty($schedule_title)) ? strtolower(str_replace(' ', '_', $schedule_title)) : '';
            $button_uniq_class = ($button_uniq_name) ? 'lcb_'.$button_uniq_name.'_area' : ''; 
            $button_uniq_id    = ($button_uniq_name) ? 'id="lcb_'.$button_uniq_name.'_area"' : ''; ?>
    	
        	<div class="schedule_area on <?php echo esc_attr($layout_class.' '.$button_uniq_class); ?>" <?php echo esc_attr($button_uniq_id);?>>
                <a <?php echo $schedule_onclick; ?> href="<?php echo $schedule_link; ?>">
            		<div class="schedule_bottom">
            			<span class="b_schedule">
                            <?php echo $schedule_icon; ?>
                            <?php echo $schedule_title; ?>
                        </span>
            		</div>
                </a>
        	</div>
    
    	<?php } if ( !empty ($map_link) ) { 
    	    
            $map_onclick       = ($map_onclick) ? $map_onclick : ''; 
            $map_onclick       = (!empty($map_onclick)) ? 'onclick="'.$map_onclick.'"' : ''; 
            $button_uniq_name  = (!empty($map_title)) ? strtolower(str_replace(' ', '_', $map_title)) : '';
            $button_uniq_class = ($button_uniq_name) ? 'lcb_'.$button_uniq_name.'_area' : ''; 
            $button_uniq_id    = ($button_uniq_name) ? 'id="lcb_'.$button_uniq_name.'_area"' : ''; ?>
    	
        	<div class="map_area on <?php echo esc_attr($layout_class.' '.$button_uniq_class); ?>" <?php echo esc_attr($button_uniq_id);?>>
                <a <?php echo $map_onclick; ?> href="<?php echo $map_link; ?>">
            		<div class="map_bottom">
            			<span class="b_map">
                            <?php echo $map_icon; ?>
                            <?php echo $map_title; ?>
                        </span>
            		</div>
                </a>
        	</div>
    
	<?php }  if ( $count != 0) {  ?>
        </div>
    <?php }  ?>
             
        <style>
            @media (max-width: 790px) { 
                body {
                	margin-bottom: 104px;
                }
             }                       
            <?php 
                if($bg_color == 1) { 
                    $bg_gd_color1 = LCB_get_setting( 'lead_call_buttons', 'general', 'bg-gd-color1' );
                    $bg_gd_color2 = LCB_get_setting( 'lead_call_buttons', 'general', 'bg-gd-color2' );               
            ?>
                    body .main_buttons {
                        background: <?php echo $bg_gd_color1; ?>;
                        background-image: -webkit-gradient( linear, left top, left bottom, color-stop(0, <?php echo $bg_gd_color1; ?>), color-stop(1, <?php echo $bg_gd_color2; ?>) );
                        background-image: -o-linear-gradient(bottom, <?php echo $bg_gd_color1; ?> 0%, <?php echo $bg_gd_color2; ?> 100%);
                        background-image: -moz-linear-gradient(bottom, <?php echo $bg_gd_color1; ?> 0%, <?php echo $bg_gd_color2; ?> 100%);
                        background-image: -webkit-linear-gradient(bottom, <?php echo $bg_gd_color1; ?> 0%, <?php echo $bg_gd_color2; ?> 100%);
                        background-image: -ms-linear-gradient(bottom, <?php echo $bg_gd_color1; ?> 0%, <?php echo $bg_gd_color2; ?> 100%);
                        background-image: linear-gradient(to bottom, <?php echo $bg_gd_color1; ?> 0%, <?php echo $bg_gd_color2; ?> 100%);
                    }                    
            <?php  
                    if(($count == 2) OR ($count > 2)) {
            ?>
                        body .main_buttons .on:first-child {
                            border-right: 1px solid <?php echo $bg_gd_color1; ?>;
                        }  
                        body .main_buttons .on:last-child {
                            border-left: 1px solid <?php echo $bg_gd_color2; ?>;
                        } 
            <?php   } 
                    if($count > 2){ ?>
                        body .main_buttons .on:not(:first-child):not(:last-child) {
                            border-right: 1px solid <?php echo $bg_gd_color1; ?>;
                            border-left: 1px solid <?php echo $bg_gd_color2; ?>;
                        }  
            <?php   }
                } else if($bg_color == 0) { 
                    $bg_color = LCB_get_setting( 'lead_call_buttons', 'general', 'bg-sl-color' ); ?>
                    body .main_buttons {
                         background: <?php echo $bg_color; ?>;
                         color: <?php echo $text_color; ?>;
                    }
            <?php  
                    if($count == 2){
            ?>
                        body .main_buttons .on:last-child {
                            border-left: 1px solid #666;
                        } 
            <?php   } 
                    if($count > 2){ ?>
                        body .main_buttons .on:not(:first-child):not(:last-child) {
                            border-left: 1px solid #666;
                            border-right: 1px solid #666;
                        }  
            <?php   } 
                } else {  
                    $bg_color = "#000"; ?>
                    body .main_buttons {
                         background: <?php echo $bg_color; ?>;
                         color: <?php echo $text_color; ?>;
                    }     
            <?php 
                }  
            ?>          
            .main_buttons .on a {
                color: <?php echo $text_color; ?>;
            }
        </style>

        <!--End Lead Call Buttons-->   
<?php 
    }   
?>