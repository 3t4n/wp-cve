<?php

add_action('admin_menu','nd_spt_add_settings_menu_add_reservation_set');
function nd_spt_add_settings_menu_add_reservation_set(){

  add_submenu_page( 'nd-sports-booking-settings','Booking Settings', __('Booking Settings','nd-sports-booking'), 'manage_options', 'nd-sports-booking-reservation-settings', 'nd_spt_settings_menu_reservation_settings' );
  add_action( 'admin_init', 'nd_spt_reservation_settings_settings' );

}



function nd_spt_reservation_settings_settings() {

  register_setting( 'nd_booking_reservation_settings_settings_group', 'nd_spt_general_description' );
  register_setting( 'nd_booking_reservation_settings_settings_group', 'nd_spt_deposit_players' );
  register_setting( 'nd_booking_reservation_settings_settings_group', 'nd_spt_br_description' );
  register_setting( 'nd_booking_reservation_settings_settings_group', 'nd_spt_default_order_status' );
  register_setting( 'nd_booking_reservation_settings_settings_group', 'nd_spt_dev_mode' );
  register_setting( 'nd_booking_reservation_settings_settings_group', 'nd_spt_email_template' );

}




function nd_spt_settings_menu_reservation_settings() {

?>


  <form method="post" action="options.php">

  <?php settings_fields( 'nd_booking_reservation_settings_settings_group' ); ?>
  <?php do_settings_sections( 'nd_booking_reservation_settings_settings_group' ); ?>
  
    <div class="nd_spt_section nd_spt_padding_right_20 nd_spt_padding_left_2 nd_spt_box_sizing_border_box nd_spt_margin_top_25 ">

      <div style="background-color:<?php echo esc_attr(nd_spt_get_profile_bg_color(0)); ?>; border-bottom:3px solid <?php echo esc_attr(nd_spt_get_profile_bg_color(2)); ?>;" class="nd_spt_section nd_spt_padding_20 nd_spt_box_sizing_border_box">
        <h2 class="nd_spt_color_ffffff nd_spt_display_inline_block"><?php _e('ND Sports Booking','nd-sports-booking'); ?></h2><span class="nd_spt_margin_left_10 nd_spt_color_a0a5aa"><?php echo esc_html(nd_spt_get_plugin_version()); ?></span>
      </div>

    
      <div class="nd_spt_section  nd_spt_box_shadow_0_1_1_000_04 nd_spt_background_color_ffffff nd_spt_border_1_solid_e5e5e5 nd_spt_border_top_width_0 nd_spt_border_left_width_0 nd_spt_overflow_hidden nd_spt_position_relative">

        <!--START menu-->
        <div style="background-color:<?php echo esc_attr(nd_spt_get_profile_bg_color(1)); ?>;" class="nd_spt_width_20_percentage nd_spt_float_left nd_spt_box_sizing_border_box nd_spt_min_height_3000 nd_spt_position_absolute">

          <ul class="nd_spt_navigation">
            <li><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-settings')); ?>"><?php _e('Plugin Settings','nd-sports-booking'); ?></a></li>    
            <li><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-add-timing')); ?>"><?php _e('Timing','nd-sports-booking'); ?></a></li>
            <li><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-add-exception')); ?>"><?php _e('Exceptions','nd-sports-booking'); ?></a></li>
            <li><a style="background-color:<?php echo esc_attr(nd_spt_get_profile_bg_color(2)); ?>;"class="" href="#"><?php _e('Booking Settings','nd-sports-booking'); ?></a></li>
            <li class="nd_spt_admin_menu_stripe"><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-stripe')); ?>"><?php _e('Stripe','nd-sports-booking'); ?></a></li>
            <li class="nd_spt_admin_menu_paypal"><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-paypal')); ?>"><?php _e('Paypal','nd-sports-booking'); ?></a></li>
            <li><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-settings-import-export')); ?>"><?php _e('Import Export','nd-sports-booking'); ?></a></li>
            <li><a target="_blank" class="" href="http://documentations.nicdark.com/"><?php _e('Documentation','nd-sports-booking'); ?></a></li>
          
            <?php 

            if ( get_option('nicdark_theme_author') != 1 ){ ?>

              <li><a style="background-color:<?php echo esc_attr(nd_spt_get_profile_bg_color(2)); ?>;" class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-settings-premium-addons')); ?>" ><?php _e('Premium Addons','nd-sports-booking'); ?></a></li>

            <?php }
            
            ?>

          </ul>
        </div>
        <!--END menu-->

        <!--START content-->
        <div class="nd_spt_width_80_percentage nd_spt_margin_left_20_percentage nd_spt_float_left nd_spt_box_sizing_border_box nd_spt_padding_20">


          <!--START-->
          <div class="nd_spt_section">
            <div class="nd_spt_width_100_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Booking Settings','nd-sports-booking'); ?></h2>
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Set the booking options','nd-sports-booking'); ?></p>
            </div>
          </div>
          <!--END-->
          <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>



          <!--START-->
        <div class="nd_spt_section">
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Order Status','nd-sports-booking'); ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Default Order Status','nd-sports-booking'); ?></p>
          </div>
          <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            
            <select class="nd_spt_width_100_percentage" name="nd_spt_default_order_status">
              <?php $nd_spt_orders_status = array("confirmed","pending"); ?>
              <?php foreach ($nd_spt_orders_status as $nd_spt_order_status) : ?>
                  <option 

                  <?php 
                    if( get_option('nd_spt_default_order_status') == $nd_spt_order_status ) { 
                      echo esc_attr('selected="selected"');
                    } 
                  ?>

                  value="<?php echo esc_attr($nd_spt_order_status); ?>">
                      <?php echo esc_html($nd_spt_order_status); ?>
                  </option>
              <?php endforeach; ?>
            </select>

            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('Select the default order status for your booking','nd-sports-booking'); ?></p>

          </div>
        </div>
        <!--END-->
        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>






        <!--START-->
        <div class="nd_spt_section">
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Developer Mode','nd-sports-booking'); ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Enable developer mode','nd-sports-booking'); ?></p>
          </div>
          <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            
            <input <?php if( get_option('nd_spt_dev_mode') == 1 ) { echo esc_attr('checked="checked"'); } ?> name="nd_spt_dev_mode" type="checkbox" value="1">
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('In this mode all requests will not be saved in your database','nd-sports-booking'); ?></p>

          </div>
        </div>
        <!--END-->
        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>



        <!--START-->
        <div class="nd_spt_section">
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Email Template','nd-sports-booking'); ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Set email notifications','nd-sports-booking'); ?></p>
          </div>
          <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            
            <select class="nd_spt_width_100_percentage" name="nd_spt_email_template">
              <?php $nd_spt_email_temps = array("layout-1"); ?>
              <?php foreach ($nd_spt_email_temps as $nd_spt_email_temp) : ?>
                  <option 

                  <?php 
                    if( get_option('nd_spt_email_template') == $nd_spt_email_temp ) { 
                      echo esc_attr('selected="selected"');
                    } 
                  ?>

                  value="<?php echo esc_attr($nd_spt_email_temp); ?>">
                      <?php echo esc_html($nd_spt_email_temp); ?>
                  </option>
              <?php endforeach; ?>
            </select>

            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('Select the template that you want to use for email notifications','nd-sports-booking'); ?></p>

          </div>
        </div>
        <!--END-->
        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>





        <!--START-->
          <div class="nd_spt_section nd_spt_plugin_settings_deposit_section">
            <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Deposit','nd-sports-booking'); ?></h2>
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Deposit per players','nd-sports-booking'); ?></p>
            </div>
            <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              
              <input <?php if( get_option('nd_spt_deposit_players') == 1 ) { echo esc_attr('checked="checked"'); } ?> name="nd_spt_deposit_players" type="checkbox" value="1">
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('Multiply the deposit value by number of players.','nd-sports-booking'); ?></p>

            </div>
          </div>
          <!--END-->
          <div class="nd_spt_section nd_spt_plugin_settings_deposit_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>
          


          <!--option-->
          <div class="nd_spt_section nd_spt_plugin_settings_descr_confirm_section">
            
            <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Description on Confirm Step','nd-sports-booking'); ?></h2>
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Insert the description','nd-sports-booking'); ?></p>
            </div>
            
            <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              <textarea id="nd_spt_general_description" class=" nd_spt_width_100_percentage" name="nd_spt_general_description" rows="6"><?php echo esc_attr( get_option('nd_spt_general_description') ); ?></textarea>
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('Add the description that is shown in the confirm step below the title','nd-sports-booking'); ?></p>
            </div>

          </div>
          <div class="nd_spt_section nd_spt_plugin_settings_descr_confirm_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>
          <!--option-->



          <!--option-->
          <div class="nd_spt_section">
            
            <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Booking Request','nd-sports-booking'); ?></h2>
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Insert the description','nd-sports-booking'); ?></p>
            </div>
            
            <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              <textarea id="nd_spt_br_description" class=" nd_spt_width_100_percentage" name="nd_spt_br_description" rows="6"><?php echo esc_attr( get_option('nd_spt_br_description') ); ?></textarea>
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('Insert the description that is shown in the booking request toogle on the confirm step','nd-sports-booking'); ?></p>
            </div>

          </div>
          <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>
          <!--option-->




          <div class="nd_spt_section nd_spt_padding_left_20 nd_spt_padding_right_20 nd_spt_box_sizing_border_box"><?php submit_button(); ?></div> 



        </div>
        <!--END content-->

             


      </div>
      <!--END content-->

    </div>

  </form>

  

<?php } 