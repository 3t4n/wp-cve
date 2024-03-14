<?php


/////////////////////////////////////////////////// START MAIN PLUGIN SETTINGS ///////////////////////////////////////////////////////////////
add_action('admin_menu', 'nd_spt_create_menu');
function nd_spt_create_menu() {
  
  add_menu_page('Sports B.', __('Sports Booking','nd-sports-booking'), 'manage_options', 'nd-sports-booking-settings', 'nd_spt_settings_page', 'dashicons-admin-generic' );
  add_action( 'admin_init', 'nd_spt_settings' );

}

function nd_spt_settings() {
  register_setting( 'nd_spt_settings_group', 'nd_spt_max_players' );
  register_setting( 'nd_spt_settings_group', 'nd_spt_booking_duration' );
  register_setting( 'nd_spt_settings_group', 'nd_spt_slot_interval' );
  register_setting( 'nd_spt_settings_group', 'nd_spt_occasions' );
  register_setting( 'nd_spt_settings_group', 'nd_spt_timing_qnt' );
  register_setting( 'nd_spt_settings_group', 'nd_spt_exceptions_qnt' );
  register_setting( 'nd_spt_settings_group', 'nd_spt_terms_page' );
}

function nd_spt_settings_page() {

?>


<form method="post" action="options.php">
    
  <?php settings_fields( 'nd_spt_settings_group' ); ?>
  <?php do_settings_sections( 'nd_spt_settings_group' ); ?>


  <div class="nd_spt_section nd_spt_padding_right_20 nd_spt_padding_left_2 nd_spt_box_sizing_border_box nd_spt_margin_top_25 ">

    

    <div style="background-color:<?php echo esc_attr(nd_spt_get_profile_bg_color(0)); ?>; border-bottom:3px solid <?php echo esc_attr(nd_spt_get_profile_bg_color(2)); ?>;" class="nd_spt_section nd_spt_padding_20 nd_spt_box_sizing_border_box">
      <h2 class="nd_spt_color_ffffff nd_spt_display_inline_block"><?php _e('ND Sports Booking','nd-sports-booking'); ?></h2><span class="nd_spt_margin_left_10 nd_spt_color_a0a5aa"><?php echo esc_html(nd_spt_get_plugin_version()); ?></span>
    </div>

    

    <div class="nd_spt_section  nd_spt_box_shadow_0_1_1_000_04 nd_spt_background_color_ffffff nd_spt_border_1_solid_e5e5e5 nd_spt_border_top_width_0 nd_spt_border_left_width_0 nd_spt_overflow_hidden nd_spt_position_relative">

      <!--START menu-->
      <div style="background-color:<?php echo esc_attr(nd_spt_get_profile_bg_color(1)); ?>;" class="nd_spt_width_20_percentage nd_spt_float_left nd_spt_box_sizing_border_box nd_spt_min_height_3000 nd_spt_position_absolute">

        <ul class="nd_spt_navigation">
          <li><a style="background-color:<?php echo esc_attr(nd_spt_get_profile_bg_color(2)); ?>;" class="" href="#"><?php _e('Plugin Settings','nd-sports-booking'); ?></a></li>    
          <li><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-add-timing')); ?>"><?php _e('Timing','nd-sports-booking'); ?></a></li>
          <li><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-add-exception')); ?>"><?php _e('Exceptions','nd-sports-booking'); ?></a></li>
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
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Plugin Settings','nd-sports-booking'); ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Below some important plugin settings.','nd-sports-booking'); ?></p>
          </div>
        </div>
        <!--END-->
        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>

      

        <!--START-->
        <div class="nd_spt_section">
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Max players number','nd-sports-booking'); ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Maximum number of players','nd-sports-booking'); ?></p>
          </div>
          <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            
            <input class="nd_spt_width_100_percentage" type="text" name="nd_spt_max_players" value="<?php echo esc_attr( get_option('nd_spt_max_players') ); ?>" />
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('Insert the maximum number of players ( Only number ). Eg: 10','nd-sports-booking'); ?></p>

          </div>
        </div>
        <!--END-->
        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>






        <!--START-->
        <div class="nd_spt_section">
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Booking Duration','nd-sports-booking'); ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Average booking duration','nd-sports-booking'); ?></p>
          </div>
          <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            
            <select class="nd_spt_width_100_percentage" name="nd_spt_booking_duration">
              <?php $nd_spt_booking_durations = array("60","120","180","240","300","360","420","480"); ?>
              <?php foreach ($nd_spt_booking_durations as $nd_spt_booking_duration) : ?>
                  <option 

                  <?php 
                    if( get_option('nd_spt_booking_duration') == $nd_spt_booking_duration ) { 
                      echo esc_attr('selected="selected"');
                    } 
                  ?>

                  value="<?php echo esc_attr($nd_spt_booking_duration); ?>">
                      <?php echo esc_html($nd_spt_booking_duration); ?>
                  </option>
              <?php endforeach; ?>
            </select>

            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('Set the average duration for each booking ( number in mimutes )','nd-sports-booking'); ?></p>

          </div>
        </div>
        <!--END-->
        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>











        <!--START-->
        <div class="nd_spt_section">
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Slot Interval','nd-sports-booking'); ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Slot booking interval','nd-sports-booking'); ?></p>
          </div>
          <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            
            <select class="nd_spt_width_100_percentage" name="nd_spt_slot_interval">
              <?php $nd_spt_slot_intervals = array("30","60"); ?>
              <?php foreach ($nd_spt_slot_intervals as $nd_spt_slot_interval) : ?>
                  <option 

                  <?php 
                    if( get_option('nd_spt_slot_interval') == $nd_spt_slot_interval ) { 
                      echo esc_attr('selected="selected"');
                    } 
                  ?>

                  value="<?php echo esc_attr($nd_spt_slot_interval); ?>">
                      <?php echo esc_html($nd_spt_slot_interval); ?>
                  </option>
              <?php endforeach; ?>
            </select>

            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('Set the slot booking interval ( number in minutes )','nd-sports-booking'); ?></p>

          </div>
        </div>
        <!--END-->
        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>









        <!--START-->
        <div class="nd_spt_section nd_spt_plugin_settings_occasion_section">
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Services','nd-sports-booking'); ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Insert all services','nd-sports-booking'); ?></p>
          </div>
          <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            
            <input class="nd_spt_width_100_percentage" type="text" name="nd_spt_occasions" value="<?php echo esc_attr( get_option('nd_spt_occasions') ); ?>" />
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('Insert services divided by comma. Eg: lights,heating,balls','nd-sports-booking'); ?></p>

          </div>
        </div>
        <!--END-->
        <div class="nd_spt_section nd_spt_plugin_settings_occasion_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>







        <!--START-->
        <div class="nd_spt_section">
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Max Timing','nd-sports-booking'); ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Insert max timing','nd-sports-booking'); ?></p>
          </div>
          <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            
            <select class="nd_spt_width_100_percentage" name="nd_spt_timing_qnt">
              <?php $nd_spt_timing_qnts = array("1","2","3","4","5","6","7","8","9","10"); ?>
              <?php foreach ($nd_spt_timing_qnts as $nd_spt_timing_qnt) : ?>
                  <option 

                  <?php 
                    if( get_option('nd_spt_timing_qnt') == $nd_spt_timing_qnt ) { 
                      echo esc_attr('selected="selected"');
                    } 
                  ?>

                  value="<?php echo esc_attr($nd_spt_timing_qnt); ?>">
                      <?php echo esc_html($nd_spt_timing_qnt); ?>
                  </option>
              <?php endforeach; ?>
            </select>

            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('Enter the maximum number of times that you can create in the timing tab','nd-sports-booking'); ?></p>

          </div>
        </div>
        <!--END-->
        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>








        <!--START-->
        <div class="nd_spt_section">
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Max Exceptions','nd-sports-booking'); ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Insert max exceptions','nd-sports-booking'); ?></p>
          </div>
          <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            
            <select class="nd_spt_width_100_percentage" name="nd_spt_exceptions_qnt">
              <?php $nd_spt_exception_qnts = array("1","2","3","4","5","6","7","8","9","10"); ?>
              <?php foreach ($nd_spt_exception_qnts as $nd_spt_exceptions_qnt) : ?>
                  <option 

                  <?php 
                    if( get_option('nd_spt_exceptions_qnt') == $nd_spt_exceptions_qnt ) { 
                      echo esc_attr('selected="selected"');
                    } 
                  ?>

                  value="<?php echo esc_attr($nd_spt_exceptions_qnt); ?>">
                      <?php echo esc_html($nd_spt_exceptions_qnt); ?>
                  </option>
              <?php endforeach; ?>
            </select>

            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('Enter the maximum number of exceptions that you can create in the exceptions tab','nd-sports-booking'); ?></p>

          </div>
        </div>
        <!--END-->
        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>










        <!--START-->
        <div class="nd_spt_section">
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Terms and conditions','nd-sports-booking'); ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Select your terms and conditions page','nd-sports-booking'); ?></p>
          </div>
          <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            
            <select class="nd_spt_width_100_percentage" name="nd_spt_terms_page">
              <?php $nd_spt_pages = get_pages(); ?>
              <?php foreach ($nd_spt_pages as $nd_spt_page) : ?>
                  <option

                  <?php 
                    if( get_option('nd_spt_terms_page') == $nd_spt_page->ID ) { 
                      echo esc_attr('selected="selected"');
                    } 
                  ?>

                   value="<?php echo esc_attr($nd_spt_page->ID); ?>">
                      <?php echo esc_html($nd_spt_page->post_title); ?>
                  </option>
              <?php endforeach; ?>
            </select>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('Select the page where you have added your terms and conditions informations','nd-sports-booking'); ?></p>

          </div>
        </div>
        <!--END-->
        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>




        <div class="nd_spt_section nd_spt_padding_left_20 nd_spt_padding_right_20 nd_spt_box_sizing_border_box">
          <?php submit_button(); ?>
        </div>      


      </div>
      <!--END content-->


    </div>

  </div>
</form>

<?php } 
/////////////////////////////////////////////////// END MAIN PLUGIN SETTINGS ///////////////////////////////////////////////////////////////




//get all options
foreach ( glob ( plugin_dir_path( __FILE__ ) . "*/index.php" ) as $file ){
  include_once realpath($file);
}
