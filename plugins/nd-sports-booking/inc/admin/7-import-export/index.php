<?php


add_action('admin_menu','nd_spt_add_settings_menu_import_export');
function nd_spt_add_settings_menu_import_export(){

  add_submenu_page( 'nd-sports-booking-settings','Import Export', __('Import Export','nd-sports-booking'), 'manage_options', 'nd-sports-booking-settings-import-export', 'nd_spt_settings_menu_import_export' );

}



function nd_spt_settings_menu_import_export() {

  $nd_spt_import_settings_params = array(
      'nd_spt_ajaxurl_import_settings' => admin_url('admin-ajax.php'),
      'nd_spt_ajaxnonce_import_settings' => wp_create_nonce('nd_spt_import_settings_nonce'),
  );

  wp_enqueue_script( 'nd_spt_import_sett', esc_url( plugins_url( 'js/nd_spt_import_settings.js', __FILE__ ) ), array( 'jquery' ) ); 
  wp_localize_script( 'nd_spt_import_sett', 'nd_spt_my_vars_import_settings', $nd_spt_import_settings_params ); 

?>

  
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
            <li class="nd_spt_admin_menu_stripe"><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-stripe')); ?>"><?php _e('Stripe','nd-sports-booking'); ?></a></li>
            <li class="nd_spt_admin_menu_paypal"><a class="" href="<?php echo esc_url(admin_url('admin.php?page=nd-sports-booking-paypal')); ?>"><?php _e('Paypal','nd-sports-booking'); ?></a></li>
            <li><a style="background-color:<?php echo esc_attr(nd_spt_get_profile_bg_color(2)); ?>;"class="" href="#"><?php _e('Import Export','nd-sports-booking'); ?></a></li>
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
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Import/Export','nd-sports-booking'); ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Export or Import your theme options.','nd-sports-booking'); ?></p>
          </div>
        </div>
        <!--END-->

        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>


        <?php


          $nd_spt_all_options = wp_load_alloptions();
          $nd_spt_my_options  = array();

          $nd_spt_name_write = '';
           
          foreach ( $nd_spt_all_options as $nd_spt_name => $nd_spt_value ) {
              if ( stristr( $nd_spt_name, 'nd_spt_' ) ) {
                
                if ( stristr( $nd_spt_name, 'nd_spt_exception_' ) OR stristr( $nd_spt_name, 'nd_spt_timing_' ) ) {

                }else{

                  $nd_spt_my_options[ $nd_spt_name ] = $nd_spt_value;
                  $nd_spt_name_write .= $nd_spt_name.'[nd_spt_option_value]'.$nd_spt_value.'[nd_spt_end_option]';

                }

              }
          }

          $nd_spt_name_write_new_1 = str_replace(" ", "%20", $nd_spt_name_write);
          $nd_spt_name_write_new = str_replace("#", "[SHARP]", $nd_spt_name_write_new_1);
           
        ?>


        <!--START-->
        <div class="nd_spt_section">
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Export Settings','nd-sports-booking'); ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Export plugin and customizer options.','nd-sports-booking'); ?></p>
          </div>
          <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            
            <div class="nd_spt_section nd_spt_padding_left_20 nd_spt_padding_right_20 nd_spt_box_sizing_border_box">
              
                <a class="button button-primary" href="data:application/octet-stream;charset=utf-8,<?php echo esc_attr($nd_spt_name_write_new); ?>" download="nd-sports-booking-export.txt"><?php _e('Export','nd-sports-booking'); ?></a>
              
            </div>

          </div>
        </div>
        <!--END-->

        
        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>

        

        <!--START-->
        <div class="nd_spt_section">
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Import Settings','nd-sports-booking'); ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Paste in the textarea the text of your export file','nd-sports-booking'); ?></p>
          </div>
          <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            
            <div class="nd_spt_section nd_spt_padding_left_20 nd_spt_padding_right_20 nd_spt_box_sizing_border_box">
              
                <textarea id="nd_spt_import_settings" class="nd_spt_margin_bottom_20 nd_spt_width_100_percentage" name="nd_spt_import_settings" rows="10"><?php echo esc_textarea( get_option('nd_spt_textarea') ); ?></textarea>
                
                <a onclick="nd_spt_import_settings()" class="button button-primary"><?php _e('Import','nd-sports-booking'); ?></a>

                <div class="nd_spt_margin_top_20 nd_spt_section" id="nd_spt_import_settings_result_container"></div>
                
            </div>

          </div>
        </div>
        <!--END-->


      </div>
      <!--END content-->


    </div>

  </div>

<?php } 
/*END 1*/







//START nd_spt_import_settings_php_function for AJAX
function nd_spt_import_settings_php_function() {

  check_ajax_referer( 'nd_spt_import_settings_nonce', 'nd_spt_import_settings_security' );

  //recover datas
  $nd_spt_value_import_settings = sanitize_text_field($_GET['nd_spt_value_import_settings']);

  $nd_spt_import_settings_result .= '';



  //START import and update options only if is superadmin
  if ( current_user_can('manage_options') ) {


    if ( $nd_spt_value_import_settings != '' ) {

      $nd_spt_array_options = explode("[nd_spt_end_option]", $nd_spt_value_import_settings);

      foreach ($nd_spt_array_options as $nd_spt_array_option) {
          
        $nd_spt_array_single_option = explode("[nd_spt_option_value]", $nd_spt_array_option);
        $nd_spt_option = $nd_spt_array_single_option[0];
        $nd_spt_new_value = $nd_spt_array_single_option[1];
        $nd_spt_new_value = str_replace("[SHARP]","#",$nd_spt_new_value);

        if ( $nd_spt_new_value != '' ){


          //START update option only it contains the plugin suffix
          if ( strpos($nd_spt_option, 'nd_spt_') !== false ) {

            $nd_spt_update_result = update_option($nd_spt_option,$nd_spt_new_value);  

            if ( $nd_spt_update_result == 1 ) {
              $nd_spt_import_settings_result .= '

                <div class="notice updated is-dismissible nd_spt_margin_0_important">
                  <p>'.__('Updated option','nd-sports-booking').' "'.$nd_spt_option.'" '.__('with','nd-sports-booking').' '.$nd_spt_new_value.'.</p>
                </div>

                ';

            }else{
              $nd_spt_import_settings_result .= '

                <div class="notice updated is-dismissible nd_spt_margin_0_important">
                  <p>'.__('Updated option','nd-sports-booking').' "'.$nd_spt_option.'" '.__('with the same value','nd-sports-booking').'.</p>
                </div>

              ';    
            }


          }else{

            $nd_spt_import_settings_result .= '
              <div class="notice notice-error is-dismissible nd_spt_margin_0">
                <p>'.__('You do not have permission to change this option','nd-sports-booking').'</p>
              </div>
            ';

          }
          //END update option only it contains the plugin suffix


        }else{

          if ( $nd_spt_option != '' ){
            $nd_spt_import_settings_result .= '

          <div class="notice notice-warning is-dismissible nd_spt_margin_0">
            <p>'.__('No value founded for','nd-sports-booking').' "'.$nd_spt_option.'" '.__('option.','nd-sports-booking').'</p>
          </div>
          ';
          }

          
        }
        
      }

    }else{

      $nd_spt_import_settings_result .= '
        <div class="notice notice-error is-dismissible nd_spt_margin_0">
          <p>'.__('Empty textarea, please paste your export options.','nd-sports-booking').'</p>
        </div>
      ';

    }



  }else{

    $nd_spt_import_settings_result .= '
      <div class="notice notice-error is-dismissible nd_spt_margin_0">
        <p>'.__('You do not have the privileges to do this.','nd-sports-booking').'</p>
      </div>
    ';

  }
  //START import and update options only if is superadmin
  
  
  $nd_spt_allowed_html = [
    'div'      => [
      'id' => [],
      'class' => [],
      'style' => [],
    ], 
    'p'      => [
      'id' => [],
      'class' => [],
      'style' => [],
    ],      
  ];

  echo wp_kses( $nd_spt_import_settings_result, $nd_spt_allowed_html );
  
  die();


}
add_action( 'wp_ajax_nd_spt_import_settings_php_function', 'nd_spt_import_settings_php_function' );
//END