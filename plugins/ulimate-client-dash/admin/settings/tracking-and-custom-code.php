<?php


// Tracking and Custom Code Settings

add_action( 'ucd_settings_content', 'ucd_tracking_code_page' );
function ucd_tracking_code_page() {
    global $ucd_active_tab;
    if ( 'tracking-and-custom-code' != $ucd_active_tab )
    return;
?>

  	<h3><?php _e( 'Tracking/Custom Code', 'ultimate-client-dash' ); ?></h3>

    <form action="options.php" method="post">
        <?php
        settings_fields( 'ultimate-client-dash-tracking' );
        do_settings_sections( 'ultimate-client-dash-tracking' );
        $ucd_message_body = get_option( 'ucd_message_body' );
        $ucd_widget_body = get_option( 'ucd_widget_body' );
        ?>

            <!-- Dashboard Styling Option Section -->

            <div class="ucd-inner-wrapper settings-tracking">
            <p class="ucd-settings-desc">Manage websites custom code and tracking. This adds the code directly to the header of the website so you don't need to custom change any theme files. This also makes sure that you do not lose your tracking or custom code when you update your theme.</p>

            <div class="ucd-form-message"><?php settings_errors('ucd-notices'); ?></div>

                <table class="form-table">
                <tbody>

                <tr class="ucd-title-holder">
                <th><h2 class="ucd-inner-title"><?php _e( 'Tracking', 'ultimate-client-dash' ) ?></h2></th>
                </tr>

                      <!-- Tracking Option Section -->
                      <tr>
                      <th><?php _e( 'Google Analytics ID', 'ultimate-client-dash' ) ?>
                      <p>With this field you can monitor traffic on your website.</p>
                      </th>
                      <td><input class="regular-text" type="text" placeholder="UA-XXXXX-X" name="ucd_tracking_google_analytics" value="<?php echo esc_attr( get_option('ucd_tracking_google_analytics') ); ?>" size="30" /></td>
                      </tr>

                      <tr>
                      <th><?php _e( 'Facebook Pixel ID', 'ultimate-client-dash' ) ?>
                      <p>Enter your Facebook Pixel ID to track visitor activity on your website.</p>
                      </th>
                      <td><input class="regular-text" type="text" placeholder="" name="ucd_tracking_facebook_pixel" value="<?php echo esc_attr( get_option('ucd_tracking_facebook_pixel') ); ?>" size="30" /></td>
                      </tr>

                <tr class="ucd-title-holder">
                <th><h2 class="ucd-inner-title"><?php _e( 'Custom Code', 'ultimate-client-dash' ) ?></h2></th>
                </tr>

                      <tr>
                      <th><?php _e( 'Head Scripts', 'ultimate-client-dash' ) ?>
                      <p>Enter your custom scripts here. This is not enclosed in the script tag. Can be useful for adding 3rd party scripts or Google verification codes to your website.</p>
                      </th>
                      <td><textarea rows="10" cols="100" type="textarea" placeholder='Example: <meta name="google-site-verification" content="your verification string">' name="ucd_tracking_custom_script" value="<?php echo esc_attr( get_option('ucd_tracking_custom_script') ); ?>" ><?php echo esc_attr( get_option('ucd_tracking_custom_script') ); ?></textarea></td>
                      </tr>

                      <tr>
                      <th><?php _e( 'Frontend Custom CSS', 'ultimate-client-dash' ) ?>
                      <p>Enter your frontend custom CSS here.</p>
                      </th>
                      <td><textarea rows="10" cols="100" type="textarea" placeholder="" name="ucd_tracking_custom_css" value="<?php echo esc_attr( get_option('ucd_tracking_custom_css') ); ?>" ><?php echo esc_attr( get_option('ucd_tracking_custom_css') ); ?></textarea>
                      <p>In case if you need to overwrite any CSS setting, you can add !important at the end of CSS property. eg: color: #da2234 !important;</p>
                      </td>
                      </tr>

                      <tr>
                      <th><?php _e( 'Custom JavaScript', 'ultimate-client-dash' ) ?>
                      <p>Enter your custom JavaScript here. This is enclosed in the script tag.</p>
                      </th>
                      <td><textarea rows="10" cols="100" type="textarea" placeholder="" name="ucd_tracking_custom_js" value="<?php echo esc_attr( get_option('ucd_tracking_custom_js') ); ?>" ><?php echo esc_attr( get_option('ucd_tracking_custom_js') ); ?></textarea></td>
                      </tr>


                <tr class="ucd-float-option">
                <th class="ucd-save-section">
                <?php submit_button(); ?>
                </th>
                </tr>

                </tbody>
                </table>
            </div>
      </form>
<?php }
