<div class="Main  lpagery-container-with-sidebar" data-title="Settings">
    <div id="lpagery_settings_container_skeleton" class="lpagery_skeleton" style="width: 452px; height: 759px">

    </div>
    <div id="lpagery_settings_container">

        <div license="extended" class="license-needed">

            <div id="lpagery_spintax-setting-section" class="settings-area">

                <label class="select-label settings-element">Spintax
                    <div class="tooltip">?
                        <span class="tooltiptext">Check to enable spintax processing For more on this check out our tutorial on spintax: <a
                                    target="_blank" rel="noopener noreferrer"
                                    href="https://lpagery.io/docs/create-unique-content-using-the-spintax-function/">Click here</a>
                        If the pages are not looking as expected, try to disable this feature and contact info@lpagery.io
                        </span>
                    </div>
                </label>

                <label class="switch">
                    <input class="lpagery-settings-input" type="checkbox" id="lpagery_spintax-enabled"
                           name="spintax-enabled">
                    <span class="slider round"></span>
                </label>
                <img style="width: 40px" class="img-pro"
                     src="<?php 
echo  plugin_dir_url( dirname( __FILE__ ) ) . '/../assets/img/pro.svg' ;
?>" alt="pro-image">


            </div>


            <div id="lpagery_image-processing-setting-section" class="settings-area">

                <label class="select-label settings-element">Image Processing
                    <div class="tooltip">?
                        <span class="tooltiptext">Check to enable image processing For more on this check out our tutorial on image processing:  <a
                                    target="_blank" rel="noopener noreferrer"
                                    href="https://lpagery.io/docs/how-to-use-the-image-processing-feature/">Click here</a> (Only supported for some page builders) It might take more time to create the pages when this feature is enabled
                        </span>
                    </div>
                </label>


                <label class="switch">

                    <input class="lpagery-settings-input" type="checkbox" id="lpagery_image-processing-enabled"
                           name="image-processing-enabled">
                    <span class="slider round"></span>


                </label>

                <img style="width: 40px" class="img-pro"
                     src="<?php 
echo  plugin_dir_url( dirname( __FILE__ ) ) . '/../assets/img/pro.svg' ;
?>" alt="pro-image">

            </div>

            <div id="lpagery_spintax-setting-section" class="settings-area">
                <label for="lpagery_custom_post_types" class="select-label">Select Custom Post Types
                    <div class="tooltip">?
                        <span class="tooltiptext">Choose, which custom post types you want to be able to use as a template page. </span>
                    </div>
                </label>


                <select class="js-example-basic-multiple lpagery-settings-input" name="custom_post_types"
                        id="lpagery_custom_post_types"
                        style="margin-bottom: 20px" multiple="multiple">
                    <?php 
$post_types = LPagerySettingsController::lpagery_get_post_types();
$allowed_html = array(
    'option' => array(
    'value' => array(),
),
);
foreach ( $post_types as $type ) {
    $option = '<option value="' . esc_html( $type ) . '">';
    $option .= esc_html( $type );
    $option .= '</option>';
    echo  wp_kses( $option, $allowed_html ) ;
}
?>
                </select><br>
            </div>


            <div id="lpagery_user-setting-section" class="settings-area">
                <label for="lpagery_author_settings" class="select-label">Select Author
                    <div class="tooltip">?
                        <span class="tooltiptext">Choose, which user should be assigned as author to the created pages</span>
                    </div>
                </label>


                <select class="js-example-basic-single lpagery-settings-input" name="lpagery_author_settings"
                        id="lpagery_author_settings"
                        style="margin-bottom: 20px">
                    <?php 
$users = get_users();
foreach ( $users as $user ) {
    $option = '<option value="' . esc_html( $user->ID ) . '">';
    $option .= esc_html( $user->display_name . " (" . $user->user_email . ")" );
    $option .= '</option>';
    echo  wp_kses( $option, $allowed_html ) ;
}
?>
                </select><br>
            </div>

            <div id="lpagery_sheet-setting-section" class="settings-area">
                <label for="lpagery_google_sync_interval" class="select-label">Select Google Sheet Sync Interval
                    <div class="tooltip">?
                        <span class="tooltiptext">Choose how often you want to synchronize data between your Google Sheet and WordPress. This interval determines the frequency of updates. If you're using a server cron (recommended), ensure it has at least the same interval to maintain consistent synchronization.</span>
                    </div>
                </label>


                <select name="google_sync_interval" id="lpagery_google_sync_interval"
                        class="js-example-basic-single lpagery-settings-input">
                    <?php 
?>
                        <option value="hourly" selected>
                            -
                        </option>
                    <?php 
?>
                </select><br>
                <div style="margin-top: 10px">
                    <label for="lpagery_next_sheet_run" class="select-label">Next Sync
                        <div class="tooltip">?
                            <span class="tooltiptext">This field indicates the timestamp for the next scheduled synchronization between your Google Sheet and WordPress. Edit this field to customize the synchronization time. Changes made here will affect the timing of the next sync.</span>
                        </div>
                    </label>

                    <input type="datetime-local" class="labels lpagery-settings-input" id="lpagery_next_sheet_run"
                           name="lpagery_next_sheet_run"
                           size="25"
                           required>
                </div>
                <div>
                    <ul style="margin-top: 10px; margin-bottom: 10px; border: #0a0a0a 1px">
                        <?php 
$last_sync_finished = get_option( "lpagery_last_sync_finished" );
if ( $last_sync_finished ) {
    echo  '<li><strong>Last Sync Finished: </strong>' . lpagery_time_ago( $last_sync_finished ) ;
}
$last_sync_duration = get_option( "lpagery_sync_duration" );
$ram_usage = maybe_unserialize( get_option( "lpagery_sheet_sync_ram_usage" ) );
if ( $last_sync_duration ) {
    echo  '
                     <li><span><strong>Last Sync Duration:</strong> ' . $last_sync_duration . ' Seconds</span>
                      <div class="tooltip">?
                        <span class="tooltiptext">If your sync relies on WordPress or a direct request to the cron endpoint (not server cron via crontab), it\'s crucial to ensure the PHP Timeout isn\'t shorter than the time your sync needs to finish. Think of it like giving your sync enough time to complete its tasks without being cut off</span>
                    </div>
                    
                     </li>' ;
}
if ( $ram_usage && $ram_usage["percent"] >= 70 ) {
    echo  ' <li><strong>Last Sync Max RAM-Usage:</strong> ' . $ram_usage["pretty_usage"] . '/' . $ram_usage["pretty_limit"] . '(' . $ram_usage["percent"] . '%)' . '</li> ' ;
}

if ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) {
    $site_url = site_url();
    $wp_cron_url = trailingslashit( $site_url ) . 'wp-cron.php';
    echo  ' <li><strong>Cron URL: </strong><a target="_blank" rel="noopener noreferrer" href="' . $wp_cron_url . '">' . $wp_cron_url . '</a></li>' ;
}

echo  '</ul>' ;
?>
                </div>

                <?php 
?>
                <button type="button" value="Save Settings" class="lpagery-button" name="save_settings"
                        id="lpagery_save_settings"
                        disabled>
                    <span class="button__text">Save Settings</span>
                </button>
            </div>
        </div>
    </div>