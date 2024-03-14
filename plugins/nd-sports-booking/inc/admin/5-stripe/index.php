<?php

add_action('admin_menu','nd_spt_add_settings_menu_stripe');
function nd_spt_add_settings_menu_stripe(){

  add_submenu_page( 'nd-sports-booking-settings','Stripe', __('Stripe','nd-sports-booking'), 'manage_options', 'nd-sports-booking-stripe', 'nd_spt_settings_menu_stripe' );
  add_action( 'admin_init', 'nd_spt_reservation_settings_stripe' );

}



function nd_spt_reservation_settings_stripe() {

  register_setting( 'nd_booking_reservation_settings_stripe_group', 'nd_spt_stripe_enable' );
  register_setting( 'nd_booking_reservation_settings_stripe_group', 'nd_spt_stripe_deposit' );
  register_setting( 'nd_booking_reservation_settings_stripe_group', 'nd_spt_stripe_currency' );
  register_setting( 'nd_booking_reservation_settings_stripe_group', 'nd_spt_stripe_description' );
  register_setting( 'nd_booking_reservation_settings_stripe_group', 'nd_spt_stripe_public_key' );
  register_setting( 'nd_booking_reservation_settings_stripe_group', 'nd_spt_stripe_secret_key' );

}




function nd_spt_settings_menu_stripe() {

?>


  <form method="post" action="options.php">

  <?php settings_fields( 'nd_booking_reservation_settings_stripe_group' ); ?>
  <?php do_settings_sections( 'nd_booking_reservation_settings_stripe_group' ); ?>
  
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
            <li><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-reservation-settings')); ?>"><?php _e('Booking Settings','nd-sports-booking'); ?></a></li>
            <li class="nd_spt_admin_menu_stripe"><a style="background-color:<?php echo esc_attr(nd_spt_get_profile_bg_color(2)); ?>;"class="" href="#"><?php _e('Stripe','nd-sports-booking'); ?></a></li>
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
              <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Stripe Settings','nd-sports-booking'); ?></h2>
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Below you can find all stripe settings','nd-sports-booking'); ?></p>
            </div>
          </div>
          <!--END-->
          <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>



          <!--START-->
        <div class="nd_spt_section">
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Stripe Enable','nd-sports-booking'); ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Enable or disable Stripe','nd-sports-booking'); ?></p>
          </div>
          <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            
            <input <?php if( get_option('nd_spt_stripe_enable') == 1 ) { echo esc_attr('checked="checked"'); } ?> name="nd_spt_stripe_enable" type="checkbox" value="1">
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('Check for enable stripe booking method','nd-sports-booking'); ?></p>

          </div>
        </div>
        <!--END-->
        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>



          <!--option-->
          <div class="nd_spt_section">
            
            <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Deposit Value','nd-sports-booking'); ?></h2>
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Set deposit','nd-sports-booking'); ?></p>
            </div>
            
            <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              <input class="nd_spt_width_100_percentage" type="text" name="nd_spt_stripe_deposit" value="<?php echo esc_attr( get_option('nd_spt_stripe_deposit') ); ?>" />
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('Set your deposit value for stripe ( ONLY NUMBER )','nd-sports-booking'); ?></p>
            </div>

          </div>
          <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>
          <!--option-->



          <!--option-->
          <div class="nd_spt_section">
            
            <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Currency','nd-sports-booking'); ?></h2>
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Set the currency','nd-sports-booking'); ?></p>
            </div>
            
            <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
                

                <select class="nd_spt_width_100_percentage" name="nd_spt_stripe_currency">
                <?php $nd_spt_stripe_currencies = array(
                  
                  "USD","AED","AFN","ALL","AMD","ANG","AOA","ARS","AUD","AWG","AZN","BAM","BBD","BDT","BGN","BIF","BMD","BND","BOB","BRL","BSD","BWP","BZD","CAD","CDF","CHF","CLP","CNY","COP","CRC","CVE","CZK","DJF","DKK","DOP","DZD","EGP","ETB","EUR","FJD","FKP","GBP","GEL","GIP","GMD","GNF","GTQ","GYD","HKD","HNL","HRK","HTG","HUF","IDR","ILS","INR","ISK","JMD","JPY","KES","KGS","KHR","KMF","KRW","KYD","KZT","LAK","LBP","LKR","LRD","LSL","MAD","MDL","MGA","MKD","MMK","MNT","MOP","MRO","MUR","MVR","MWK","MXN","MYR","MZN","NAD","NGN","NIO","NOK","NPR","NZD","PAB","PEN","PGK","PHP","PKR","PLN","PYG","QAR","RON","RSD","RUB","RWF","SAR","SBD","SCR","SEK","SGD","SHP","SLL","SOS","SRD","STD","SZL","THB","TJS","TOP","TRY","TTD","TWD","TZS","UAH","UGX","UYU","UZS","VND","VUV","WST","XAF","XCD","XOF","XPF","YER","ZAR","ZMW"

                  ); ?>
                <?php foreach ($nd_spt_stripe_currencies as $nd_spt_stripe_currency) : ?>
                    <option 

                    <?php 
                      if( get_option('nd_spt_stripe_currency') == $nd_spt_stripe_currency ) { 
                        echo esc_attr('selected="selected"');
                      } 
                    ?>

                    value="<?php echo esc_attr($nd_spt_stripe_currency); ?>">
                        <?php echo esc_html($nd_spt_stripe_currency); ?>
                    </option>
                <?php endforeach; ?>
              </select>



              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('Set the currency that you want to use for Stripe payment','nd-sports-booking'); ?></p>
            </div>

          </div>
          <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>
          <!--option-->



          <!--option-->
          <div class="nd_spt_section">
            
            <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Public Key','nd-sports-booking'); ?></h2>
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Set the key','nd-sports-booking'); ?></p>
            </div>
            
            <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              <input class="nd_spt_width_100_percentage" type="text" name="nd_spt_stripe_public_key" value="<?php echo esc_attr( get_option('nd_spt_stripe_public_key') ); ?>" />
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('Insert here your Stripe public key','nd-sports-booking'); ?></p>
            </div>

          </div>
          <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>
          <!--option-->


          <!--option-->
          <div class="nd_spt_section">
            
            <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Secret Key','nd-sports-booking'); ?></h2>
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Set the key','nd-sports-booking'); ?></p>
            </div>
            
            <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              <input class="nd_spt_width_100_percentage" type="text" name="nd_spt_stripe_secret_key" value="<?php echo esc_attr( get_option('nd_spt_stripe_secret_key') ); ?>" />
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('Insert here your Stripe secret key','nd-sports-booking'); ?></p>
            </div>

          </div>
          <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>
          <!--option-->



          <!--option-->
          <div class="nd_spt_section">
            
            <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Stripe Description','nd-sports-booking'); ?></h2>
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Insert the description','nd-sports-booking'); ?></p>
            </div>
            
            <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              <textarea id="nd_spt_stripe_description" class=" nd_spt_width_100_percentage" name="nd_spt_stripe_description" rows="6"><?php echo esc_attr( get_option('nd_spt_stripe_description') ); ?></textarea>
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_20"><?php _e('Insert the description that is shown in the Stripe toogle on the confirm step','nd-sports-booking'); ?></p>
            </div>

          </div>
          <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>
          <!--option-->



          <div class="nd_spt_section nd_spt_padding_left_20 nd_spt_padding_right_20 nd_spt_box_sizing_border_box">
            <?php submit_button(); ?>
          </div> 


        </div>
        <!--END content-->

             


      </div>
      <!--END content-->

    </div>

  </form>

  

<?php } 