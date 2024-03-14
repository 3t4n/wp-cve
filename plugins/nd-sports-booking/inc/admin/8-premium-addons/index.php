<?php


if ( get_option('nicdark_theme_author') != 1 ){



  add_action('admin_menu','nd_spt_add_settings_menu_premium_addons');
  function nd_spt_add_settings_menu_premium_addons(){

    add_submenu_page( 'nd-sports-booking-settings','Premium Addons', __('Premium Addons','nd-sports-booking'), 'manage_options', 'nd-sports-booking-settings-premium-addons', 'nd_spt_settings_menu_premium_addons' );

  }



  function nd_spt_settings_menu_premium_addons() {

  ?>

    
    <div class="nd_spt_section nd_spt_padding_right_20 nd_spt_padding_left_2 nd_spt_box_sizing_border_box nd_spt_margin_top_25 ">

      

      <div style="background-color:<?php echo esc_attr(nd_spt_get_profile_bg_color(0)); ?>; border-bottom:3px solid <?php echo esc_attr(nd_spt_get_profile_bg_color(2)); ?>;" class="nd_spt_section nd_spt_padding_20  nd_spt_box_sizing_border_box">
        <h2 class="nd_spt_color_ffffff nd_spt_display_inline_block"><?php _e('ND Sports Booking','nd-sports-booking'); ?></h2><span class="nd_spt_margin_left_10 nd_spt_color_a0a5aa"><?php echo esc_html(nd_spt_get_plugin_version()); ?></span>
      </div>

      

      <div class="nd_spt_section nd_spt_min_height_400  nd_spt_box_shadow_0_1_1_000_04 nd_spt_background_color_ffffff nd_spt_border_1_solid_e5e5e5 nd_spt_border_top_width_0 nd_spt_border_left_width_0 nd_spt_overflow_hidden nd_spt_position_relative">
      
        

        <!--START menu-->
        <div style="background-color:<?php echo esc_attr(nd_spt_get_profile_bg_color(1)); ?>;" class="nd_spt_width_20_percentage nd_spt_float_left nd_spt_box_sizing_border_box nd_spt_min_height_3000 nd_spt_position_absolute">

          <ul class="nd_spt_navigation">
            <li><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-settings')); ?>"><?php _e('Plugin Settings','nd-sports-booking'); ?></a></li>   
            <li><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-add-timing')); ?>"><?php _e('Timing','nd-sports-booking'); ?></a></li>
            <li><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-add-exception')); ?>"><?php _e('Exceptions','nd-sports-booking'); ?></a></li>
            <li><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-reservation-settings')); ?>"><?php _e('Booking Settings','nd-sports-booking'); ?></a></li>
            <li class="nd_spt_admin_menu_stripe"><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-stripe')); ?>"><?php _e('Stripe','nd-sports-booking'); ?></a></li>
            <li class="nd_spt_admin_menu_paypal"><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-paypal')); ?>"><?php _e('Paypal','nd-sports-booking'); ?></a></li>
            <li><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-settings-import-export')); ?>"><?php _e('Import Export','nd-sports-booking'); ?></a></li>
            <li><a target="_blank" class="" href="http://documentations.nicdark.com/"><?php _e('Documentation','nd-sports-booking'); ?></a></li>
          
            <?php 

            if ( get_option('nicdark_theme_author') != 1 ){ ?>

              <li><a style="background-color:<?php echo esc_attr(nd_spt_get_profile_bg_color(2)); ?>;" class="" href="" ><?php _e('Premium Addons','nd-sports-booking'); ?></a></li>

            <?php }
            
            ?>

          </ul>
        </div>
        <!--END menu-->


        <!--START content-->
        <div class="nd_spt_width_80_percentage nd_spt_margin_left_20_percentage nd_spt_float_left nd_spt_box_sizing_border_box nd_spt_padding_20">


          <!--START-->
          <div class="nd_spt_section">
            
              


               <div class="nd_spt_section nd_spt_padding_20 nd_spt_box_sizing_border_box">
                <div class="nd_spt_section nd_spt_padding_30 nd_spt_box_sizing_border_box nd_spt_border_1_solid_e5e5e5 nd_spt_position_relative">
                  <h2 class="nd_spt_font_size_21 nd_spt_line_height_21 nd_spt_margin_0"><?php _e('Get All Addons','nd-sports-booking'); ?></h2>
                  <p class="nd_spt_margin_top_10 nd_spt_color_666666 nd_spt_font_size_16 nd_spt_line_height_16 nd_spt_margin_0"><?php _e('Get all addons and an amazing Sport WP theme all in one pack.','nd-sports-booking'); ?></p>
                  <a target="_blank" class="button button-primary button-hero nd_spt_top_30 nd_spt_right_30 nd_spt_position_absolute" href="http://www.nicdarkthemes.com/themes/tennis/wp/demo/intro/?action=nd-sports-booking"><?php _e('CHECK IT NOW !','nd-sports-booking'); ?></a>
                </div>
              </div>





              <table id="nd_spt_table_premium_addons" class="nd_spt_width_60_percentage nd_spt_margin_auto nd_spt_border_collapse_collapse">
                
                <thead class="nd_spt_text_align_center">
                  <tr>
                    <td>
                    </td>
                    <td>
                      <h2><?php _e('FREE','nd-sports-booking'); ?></h2>
                    </td>
                    <td>
                      <h2><?php _e('PREMIUM','nd-sports-booking'); ?></h2>
                    </td>
                  </tr>
                </thead>

                <tbody>
                  

                  <tr>
                    <td>
                      <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Sport Booking','nd-sports-booking'); ?></h2>
                      <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('[nd_spt_booking_form] sport booking shortcode built in ajax','nd-sports-booking'); ?>. <a target="_blank" href="http://www.nicdarkthemes.com/themes/tennis/wp/demo/intro/?action=nd-sports-booking"><?php _e('View Demo','nd-sports-booking'); ?></a></p>
                    </td>

                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-yes.svg', __FILE__ )); ?>">
                    </td>
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-yes.svg', __FILE__ )); ?>">
                    </td>
                  </tr>


                  <tr>
                    <td>
                      <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Email Template','nd-sports-booking'); ?></h2>
                      <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Email notification on each booking','nd-sports-booking'); ?>. <a target="_blank" href="http://www.nicdarkthemes.com/themes/tennis/wp/demo/intro/?action=nd-sports-booking"><?php _e('View Demo','nd-sports-booking'); ?></a></p>
                    </td>

                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-yes.svg', __FILE__ )); ?>">
                    </td>
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-yes.svg', __FILE__ )); ?>">
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Exceptions','nd-sports-booking'); ?></h2>
                      <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Create timing exceptions for special dates and hours','nd-sports-booking'); ?>. <a target="_blank" href="http://www.nicdarkthemes.com/themes/tennis/wp/demo/intro/?action=nd-sports-booking"><?php _e('View Demo','nd-sports-booking'); ?></a></p>
                    </td>

                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-yes.svg', __FILE__ )); ?>">
                    </td>
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-yes.svg', __FILE__ )); ?>">
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Cool Design','nd-sports-booking'); ?></h2>
                      <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Amazing design and color managment for your site','nd-sports-booking'); ?>. <a target="_blank" href="http://www.nicdarkthemes.com/themes/tennis/wp/demo/intro/?action=nd-sports-booking"><?php _e('View Demo','nd-sports-booking'); ?></a></p>
                    </td>
                    
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-not.svg', __FILE__ )); ?>">
                    </td>
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-yes.svg', __FILE__ )); ?>">
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Sport Components','nd-sports-booking'); ?></h2>
                      <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('A lot of solutions to show your matches','nd-sports-booking'); ?>. <a target="_blank" href="http://www.nicdarkthemes.com/themes/tennis/wp/demo/intro/?action=nd-sports-booking"><?php _e('View Demo','nd-sports-booking'); ?></a></p>
                    </td>
                    
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-not.svg', __FILE__ )); ?>">
                    </td>
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-yes.svg', __FILE__ )); ?>">
                    </td>
                  </tr>


                  <tr>
                    <td>
                      <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Paypal & Stripe','nd-sports-booking'); ?></h2>
                      <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Different payment methods for manage your deposit on sport booking','nd-sports-booking'); ?>. <a target="_blank" href="http://www.nicdarkthemes.com/themes/tennis/wp/demo/intro/?action=nd-sports-booking"><?php _e('View Demo','nd-sports-booking'); ?></a></p>
                    </td>
                    
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-not.svg', __FILE__ )); ?>">
                    </td>
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-yes.svg', __FILE__ )); ?>">
                    </td>
                  </tr>


                  <tr>
                    <td>
                      <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Sports','nd-sports-booking'); ?></h2>
                      <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Possibility to reserve different sports','nd-sports-booking'); ?>. <a target="_blank" href="http://www.nicdarkthemes.com/themes/tennis/wp/demo/intro/?action=nd-sports-booking"><?php _e('View Demo','nd-sports-booking'); ?></a></p>
                    </td>
                    
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-not.svg', __FILE__ )); ?>">
                    </td>
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-yes.svg', __FILE__ )); ?>">
                    </td>
                  </tr>

                  <tr>
                    <td>
                      <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Services','nd-sports-booking'); ?></h2>
                      <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Possibility to add service on your booking','nd-sports-booking'); ?>. <a target="_blank" href="http://www.nicdarkthemes.com/themes/tennis/wp/demo/intro/?action=nd-sports-booking"><?php _e('View Demo','nd-sports-booking'); ?></a></p>
                    </td>
                    
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-not.svg', __FILE__ )); ?>">
                    </td>
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-yes.svg', __FILE__ )); ?>">
                    </td>
                  </tr>


                  <tr>
                    <td>
                      <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Calendar View','nd-sports-booking'); ?></h2>
                      <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Possibility to filter all bookings on a specific day','nd-sports-booking'); ?>. <a target="_blank" href="http://www.nicdarkthemes.com/themes/tennis/wp/demo/intro/?action=nd-sports-booking"><?php _e('View Demo','nd-sports-booking'); ?></a></p>
                    </td>
                    
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-not.svg', __FILE__ )); ?>">
                    </td>
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-yes.svg', __FILE__ )); ?>">
                    </td>
                  </tr>


                  <tr>
                    <td>
                      <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Add New Booking','nd-sports-booking'); ?></h2>
                      <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Add booking through your dashboard easily','nd-sports-booking'); ?>. <a target="_blank" href="http://www.nicdarkthemes.com/themes/tennis/wp/demo/intro/?action=nd-sports-booking"><?php _e('View Demo','nd-sports-booking'); ?></a></p>
                    </td>
                    
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-not.svg', __FILE__ )); ?>">
                    </td>
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-yes.svg', __FILE__ )); ?>">
                    </td>
                  </tr>


                  <tr>
                    <td>
                      <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Steps','nd-sports-booking'); ?></h2>
                      <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Show the booking steps above the sport booking shortcode','nd-sports-booking'); ?>. <a target="_blank" href="http://www.nicdarkthemes.com/themes/tennis/wp/demo/intro/?action=nd-sports-booking"><?php _e('View Demo','nd-sports-booking'); ?></a></p>
                    </td>
                    
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-not.svg', __FILE__ )); ?>">
                    </td>
                    <td class="nd_spt_text_align_center">
                      <img width="25" height="25" src="<?php echo esc_url(plugins_url('icon-yes.svg', __FILE__ )); ?>">
                    </td>
                  </tr>






                </tbody>

              </table>




          </div>
          <!--END-->


          


        </div>
        <!--END content-->


      </div>

    </div>

  <?php } 
  /*END 1*/




  function nd_spt_admin_style_2() {
  
    wp_enqueue_style( 'nd_spt_admin_style_2', esc_url(plugins_url('admin-style-2.css', __FILE__ )), array(), false, false );
    
  }
  add_action( 'admin_enqueue_scripts', 'nd_spt_admin_style_2' );



}



