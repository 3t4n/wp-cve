<?php



add_action('admin_menu','nd_spt_add_settings_menu_add_exceptions');
function nd_spt_add_settings_menu_add_exceptions(){

  add_submenu_page( 'nd-sports-booking-settings','Add Exceptions', __('Add Exceptions','nd-sports-booking'), 'manage_options', 'nd-sports-booking-add-exception', 'nd_spt_settings_menu_add_exception' );
  add_action( 'admin_init', 'nd_spt_exceptions_settings' );

}



function nd_spt_exceptions_settings() {

  $nd_spt_exceptions_qnt = get_option('nd_spt_exceptions_qnt');

  for ($nd_spt_i = 1; $nd_spt_i <= $nd_spt_exceptions_qnt; $nd_spt_i++) {
      
    register_setting( 'nd_booking_exception_settings_group', 'nd_spt_exception_date_'.$nd_spt_i );
    register_setting( 'nd_booking_exception_settings_group', 'nd_spt_exception_close_'.$nd_spt_i );
    register_setting( 'nd_booking_exception_settings_group', 'nd_spt_exception_start_'.$nd_spt_i );
    register_setting( 'nd_booking_exception_settings_group', 'nd_spt_exception_end_'.$nd_spt_i );  

  }

  

}



function nd_spt_settings_menu_add_exception() {

?>

  

  <form method="post" action="options.php">

    <?php settings_fields( 'nd_booking_exception_settings_group' ); ?>
    <?php do_settings_sections( 'nd_booking_exception_settings_group' ); ?>
  
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
          <li><a style="background-color:<?php echo esc_attr(nd_spt_get_profile_bg_color(2)); ?>;"class="" href="#"><?php _e('Exceptions','nd-sports-booking'); ?></a></li>
          <li><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-reservation-settings')); ?>"><?php _e('Booking Settings','nd-sports-booking'); ?></a></li>
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
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Exceptions','nd-sports-booking'); ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Insert the date and check the checkbox for block the bookings and set the timing for a different hours.','nd-sports-booking'); ?></p>
          </div>
        </div>
        <!--END-->
        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>

      



        <!--START container-->
        <div id="nd_spt_all_timing" class="nd_spt_section">


          <?php $nd_spt_exceptions_qnt = get_option('nd_spt_exceptions_qnt'); ?>
          <?php for ( $nd_spt_i = 1; $nd_spt_i <= $nd_spt_exceptions_qnt; $nd_spt_i++ ) { ?>


          <!--START-->
          <div id="nd_spt_timing_<?php echo esc_attr($nd_spt_i); ?>" class="nd_spt_section ">
            

            <div class="nd_spt_width_50_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">

              <input class="nd_spt_width_100_percentage" type="text" name="nd_spt_exception_date_<?php echo esc_attr($nd_spt_i); ?>" value="<?php echo esc_attr( get_option('nd_spt_exception_date_'.$nd_spt_i) ); ?>" />
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_14"><?php _e('Insert the date ( YYYY-MM-DD ) Eg: 2022-01-30','nd-sports-booking'); ?></p>

            </div>


            <div class="nd_spt_width_50_percentage nd_spt_box_sizing_border_box nd_spt_float_left">
            <div class="nd_spt_width_33_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              
              <div class="nd_spt_section">

                <div class="nd_spt_section nd_spt_box_sizing_border_box nd_spt_float_left nd_spt_text_align_center">
                  <input class=" nd_spt_margin_top_6_important nd_spt_margin_0_important" <?php if( get_option('nd_spt_exception_close_'.$nd_spt_i) == 1 ) { echo esc_attr('checked="checked"'); } ?> name="nd_spt_exception_close_<?php echo esc_attr($nd_spt_i); ?>" type="checkbox" value="1">
                  <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_18"><?php _e('Closing','nd-sports-booking'); ?></p>
                </div> 
                 
              </div>

            </div>
            
            <div class="nd_spt_width_33_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              
              <select class="nd_spt_width_100_percentage" name="nd_spt_exception_start_<?php echo esc_attr($nd_spt_i); ?>">
                <?php $nd_spt_exception_sols = array('00:00','00:30','01:00','01:30','02:00','02:30','03:00','03:30','04:00','04:30','05:00','05:30','06:00','06:30','07:00','07:30','08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30','20:00','20:30','21:00','21:30','22:00','22:30','23:00','23:30'); ?>
                <?php foreach ($nd_spt_exception_sols as $nd_spt_exceptions_sol) : ?>
                    <option 

                    <?php 
                      if( get_option('nd_spt_exception_start_'.$nd_spt_i) == $nd_spt_exceptions_sol ) { 
                        echo esc_attr('selected="selected"');
                      } 
                    ?>

                    value="<?php echo esc_attr($nd_spt_exceptions_sol); ?>">
                        <?php echo esc_html($nd_spt_exceptions_sol); ?>
                    </option>
                <?php endforeach; ?>
              </select>

              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_11"><?php _e('Start','nd-sports-booking'); ?></p>

            </div>

            <div class="nd_spt_width_33_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              
              <select class="nd_spt_width_100_percentage" name="nd_spt_exception_end_<?php echo esc_attr($nd_spt_i); ?>">
                <?php $nd_spt_exception_sols = array('00:00','00:30','01:00','01:30','02:00','02:30','03:00','03:30','04:00','04:30','05:00','05:30','06:00','06:30','07:00','07:30','08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30','20:00','20:30','21:00','21:30','22:00','22:30','23:00','23:30'); ?>
                <?php foreach ($nd_spt_exception_sols as $nd_spt_exceptions_sol) : ?>
                    <option 

                    <?php 
                      if( get_option('nd_spt_exception_end_'.$nd_spt_i) == $nd_spt_exceptions_sol ) { 
                        echo esc_attr('selected="selected"');
                      } 
                    ?>

                    value="<?php echo esc_attr($nd_spt_exceptions_sol); ?>">
                        <?php echo esc_html($nd_spt_exceptions_sol); ?>
                    </option>
                <?php endforeach; ?>
              </select>

              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_11"><?php _e('End','nd-sports-booking'); ?></p>

            </div>
            </div>

          </div>
          
          <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>
          <!--END-->



          <?php } ?>





      </div>
      <!--END container-->



      <div class="nd_spt_section nd_spt_padding_left_20 nd_spt_padding_right_20 nd_spt_box_sizing_border_box">
        <?php submit_button(); ?>
      </div>      


      </div>
      <!--END content-->


    </div>

  </div>
</form>

  

<?php } 






