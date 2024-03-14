<?php
/*
Plugin Name: Nepali Date Utilities
Description: A Nepali Date Utitlities plugin for wordpress
Version: 1.0.11
Author: Ashok Basnet
Author URI: http://ashokbasnet.com.np
*/
if ( !defined( 'NPD_BASENAME' ) ) define( 'NPD_BASENAME', plugin_basename( __FILE__ ) );
if( !defined('NPD_PLUGIN_URL' )) define('NPD_PLUGIN_URL', plugin_dir_url(__FILE__));
if( !defined('NPD_PLUGIN_DIR' )) define('NPD_PLUGIN_DIR', plugin_dir_path(__FILE__));
function ndu_init() {
    require_once( NPD_PLUGIN_DIR . 'class.nepali.date.php' );
    require_once( NPD_PLUGIN_DIR . 'class.todaydate.php' );
}

add_action( 'plugins_loaded', 'ndu_init', 15 );

function ndu_convert_unicode_number($unicode = null){
    $conversionNumber = array('1'=>'१' ,'2'=>'२' ,'3'=> '३' , '4'=>'४' , '5'=>'५' , '6'=>'६', '7'=>'७' , '8'=>'८' , '9'=>'९', '0'=>'०');
    foreach ($conversionNumber as $key => $number) {
        $unicode = str_replace($key, $number, $unicode);
    }
    return $unicode;
}

function ndu_human_time_diff_nepali( $from, $to = '' ) {
    if ( empty( $to ) ) {
        $to = time();
    }

    $diff = (int) abs( $to - $from );

    if ( $diff < HOUR_IN_SECONDS ) {
        $mins = round( $diff / MINUTE_IN_SECONDS );
        if ( $mins <= 1 )
            $mins = 1;
        /* translators: min=minute */
        $since = sprintf( _n( '%s मिनेट', '%s मिनेट', $mins ), $mins );
    } elseif ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {
        $hours = round( $diff / HOUR_IN_SECONDS );
        if ( $hours <= 1 )
            $hours = 1;
        $since = sprintf( _n( '%s घण्टा', '%s घण्टा', $hours ), $hours );
    } elseif ( $diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS ) {
        $days = round( $diff / DAY_IN_SECONDS );
        if ( $days <= 1 )
            $days = 1;
        $since = sprintf( _n( '%s दिन', '%s दिन', $days ), $days );
    } elseif ( $diff < MONTH_IN_SECONDS && $diff >= WEEK_IN_SECONDS ) {
        $weeks = round( $diff / WEEK_IN_SECONDS );
        if ( $weeks <= 1 )
            $weeks = 1;
        $since = sprintf( _n( '%s हप्ता', '%s हप्ता', $weeks ), $weeks );
    } elseif ( $diff < YEAR_IN_SECONDS && $diff >= MONTH_IN_SECONDS ) {
        $months = round( $diff / MONTH_IN_SECONDS );
        if ( $months <= 1 )
            $months = 1;
        $since = sprintf( _n( '%s महिना', '%s महिना', $months ), $months );
    } elseif ( $diff >= YEAR_IN_SECONDS ) {
        $years = round( $diff / YEAR_IN_SECONDS );
        if ( $years <= 1 )
            $years = 1;
        $since = sprintf( _n( '%s वर्ष', '%s वर्ष', $years ), $years );
    }

    return apply_filters( 'ndu_human_time_diff_nepali', $since, $diff, $from, $to );
}

function ndu_date_ago(){
    echo ndu_convert_unicode_number(ndu_human_time_diff_nepali( get_the_time('U'), current_time('timestamp') ) . ' अघि');
}

function ndu_today_date(){
    date_default_timezone_set( 'Asia/Kathmandu' );
    $post_date = time();
    $date = new Nepali_Date();
    $nepali_calender = $date->eng_to_nep( date( 'Y', $post_date ), date( 'm', $post_date ), date( 'd', $post_date ) );
    
    $nepali_year = $date->convert_to_nepali_number( $nepali_calender['year'] );
    $nepali_month = $nepali_calender['nmonth'];
    $nepali_day = $nepali_calender['day'];
    $nepali_date = $date->convert_to_nepali_number( $nepali_calender['date'] );
    $nepali_hour = $date->convert_to_nepali_number( date( 'H', $post_date ));
    $nepali_minute = $date->convert_to_nepali_number( date( 'i', $post_date ) );
    $nepali_seconds = $date->convert_to_nepali_number( date( 's', $post_date ) );
    $nepali_am_pm = date('a', $post_date ) == 'am' ? 'बिहान' : 'मध्यान्ह';

    $format = get_option('ndu_today_date_format','y m d');
    if (strpos($format, 'H') !== false) {
        $nepali_hour = $date->convert_to_nepali_number( date( 'H', $post_date ));
        $converted_date = str_replace(
            array( 'l', 'd', 'm', 'y', 'H','i','s','a' ),
            array( $nepali_day, $nepali_date, $nepali_month, $nepali_year,$nepali_hour, $nepali_minute,$nepali_seconds ,$nepali_am_pm),
            $format );
    }else{
        $nepali_hour = $date->convert_to_nepali_number( date( 'h', $post_date ));
        $converted_date = str_replace(
            array( 'l', 'd', 'm', 'y', 'h','i','s','a' ),
            array( $nepali_day, $nepali_date, $nepali_month, $nepali_year,$nepali_hour, $nepali_minute, $nepali_seconds, $nepali_am_pm ),
            $format );
    }
    return $converted_date;
}

// Shortcode for printing today's date
add_shortcode('ndu_today_date','ndu_today_date');

// Override the default date format for the display
$convertPostDate = get_option('ndu_convert_post_date','1');
if($convertPostDate == "1" && ! is_admin()){
    add_filter( 'get_the_date', 'ndu_convert_to_nepali_date_time', 10, 1 ); //override date display
    add_filter( 'the_date', 'ndu_convert_to_nepali_date_time' , 10, 1 ); //override date display
    add_filter( 'get_the_time', 'ndu_convert_to_nepali_date_time' , 10, 1 ); //override time display
    add_filter( 'the_time', 'ndu_convert_to_nepali_date_time' , 10, 1 ); //override time display

    add_filter( 'the_modified_time', 'ndu_convert_to_nepali_date_time_modified' , 10, 1 );
    add_filter( 'get_the_modified_time', 'ndu_convert_to_nepali_date_time_modified', 10, 1  );
    add_filter( 'the_modified_date', 'ndu_convert_to_nepali_date_time_modified', 10, 1  );
    add_filter( 'get_the_modified_date', 'ndu_convert_to_nepali_date_time_modified', 10, 1  );
}

/* Convert post date to nepali for both published and modified date */
function ndu_convert_post_date_common($post_date){
    $date = new Nepali_Date();
    $nepali_calender = $date->eng_to_nep( date( 'Y', $post_date ), date( 'm', $post_date ), date( 'd', $post_date ) );
    
    $nepali_year = $date->convert_to_nepali_number( $nepali_calender['year'] );
    $nepali_month = $nepali_calender['nmonth'];
    $nepali_day = $nepali_calender['day'];
    $nepali_date = $date->convert_to_nepali_number( $nepali_calender['date'] );
    $nepali_minute = $date->convert_to_nepali_number( date( 'i', $post_date ) );
    $nepali_seconds = $date->convert_to_nepali_number( date( 's', $post_date ) );
    $nepali_am_pm = date('a', $post_date ) == 'am' ? 'बिहान' : 'मध्यान्ह';

    if(is_home()){
        $ndu_time_ago_format_for_home = get_option('ndu_date_format_for_home','0');
        if($ndu_time_ago_format_for_home == '1'){
            return ndu_convert_unicode_number(ndu_human_time_diff_nepali( $post_date, current_time('timestamp') ) . ' अघि');
        }elseif($ndu_time_ago_format_for_home == '2'){
            return '';
        }
    }

    $format = get_option('ndu_date_format','y m d, l H:i');
    if (strpos($format, 'H') !== false) {
        $nepali_hour = $date->convert_to_nepali_number( date( 'H', $post_date ));
        $converted_date = str_replace( 
        array( 'l', 'd', 'm', 'y', 'H','i','s','a' ),
        array( $nepali_day, $nepali_date, $nepali_month, $nepali_year,$nepali_hour, $nepali_minute,$nepali_seconds,$nepali_am_pm ), 
        $format );
    }else{
        $nepali_hour = $date->convert_to_nepali_number( date( 'h', $post_date ));
        $converted_date = str_replace( 
        array( 'l', 'd', 'm', 'y', 'h','i','s','a' ),
        array( $nepali_day, $nepali_date, $nepali_month, $nepali_year,$nepali_hour, $nepali_minute,$nepali_seconds,$nepali_am_pm ), 
        $format );
    }

    return $converted_date;

}

/* Callback function for post modified time and date filter hooks */
function ndu_convert_to_nepali_date_time_modified($orig_time){
    global $post;
    if($post->post_date != $post->post_modified){
        $post_date = ( !empty( $post->post_modified ) ) ? strtotime( $post->post_modified ) : time();
        $converted_date = ndu_convert_post_date_common($post_date);
        return $converted_date;  
    }else{
        return '';
    }
}


/* Callback function for post time and date filter hooks */
function ndu_convert_to_nepali_date_time($orig_time){
    global $post;
    $post_date = ( !empty( $post->post_date ) ) ? strtotime( $post->post_date ) : time();
    $converted_date = ndu_convert_post_date_common($post_date);
    return $converted_date;  
}

/**
 * Setting page for updating the settings related to the date
 */
function ndu_nepali_date_utilities_page(){
    if(array_key_exists('submit_ndu_settings', $_POST)){
        // print_r($_POST);exit;
            update_option('ndu_date_format',$_POST['ndu_date_format']);
            update_option('ndu_today_date_format',$_POST['ndu_today_date_format']);
            update_option('ndu_date_format_for_home',$_POST['ndu_date_format_for_home']);
            if(isset($_POST['ndu_convert_post_date']) && $_POST['ndu_convert_post_date'] == 'on'){
                update_option('ndu_convert_post_date',"1");
            }else{
                update_option('ndu_convert_post_date',"0");
            }

            ?>
            <div id="setting-error-settings_updated" class="update_settings-error notice is-dismissible">
                <p><strong>Yay !!! Settings has been saved.</strong></p>
            </div>
            <?php
    }

    $dateformat = get_option('ndu_date_format','y m d, l H:i गते');
    $todayDateformat = get_option('ndu_today_date_format','y m d');
    $dateFormatForHome = get_option('ndu_date_format_for_home','0');
    $convertPostDate = get_option('ndu_convert_post_date','1');
    ?>
    <h1>Nepali Date Utilties Plugin Settings</h1><hr>

    <div class="wrap">
        <form method="POST">
                <label name="ndu_convert_post_date">Convert Post Date to Nepali:</label>
                <input type="checkbox" name="ndu_convert_post_date" <?php echo ($convertPostDate == "1") ? 'checked': '';?>  style="margin-top: 0px;" /> <br /><br />
                <label for="ndu_date_format">Post Date Format( y m d l H/h i s a) </label>
                <input type="text" name="ndu_date_format" class="large-text" value="<?php echo $dateformat;?>" placeholder="Format"/><br /><br />
                <label for "ndu_time_ago_format_for_home">Home Page Date Format</label><br />
                <select name="ndu_date_format_for_home">
                    <option value="0" <?php echo $dateFormatForHome == "0" ? 'selected': '';?>>Default Post Setting</option>
                    <option value="1" <?php echo $dateFormatForHome == "1" ? 'selected': '';?>>Use Time Ago</option>
                    <option value="2" <?php echo $dateFormatForHome == "2" ? 'selected': '';?>>Hide Date</option>
                </select><br /><br />

                <label for="ndu_today_date_format">Today's Date Format( y m d l H/h i s a) </label>
                <input type="text" name="ndu_today_date_format" class="large-text" value="<?php echo $todayDateformat;?>" placeholder="Format"/><br />
                <br><code>Example Today's Date: <?php echo ndu_today_date();?></code><br />
                <br />
                <input type="submit" name="submit_ndu_settings" value="Save" class="button button-primary">
            </form>
    </div>
    <hr>
    <h2>Snippets, shortcodes and usages</h2>
    <ol>
        <li>
            <code><?php echo htmlspecialchars('<?php echo ndu_today_date();?>');?></code> to print today's date.
        </li>
        <li>
            <code><?php echo htmlspecialchars("<?php echo do_shortcode('[ndu_today_date]');?>");?></code> to print today's date using shortcode.
        </li>
    </ol>
    <hr>
    <h2>Help [<small>Date format has the following options</small>]</h2>
    <ol>
        <li>y - बर्ष ( E.g. २०७५ )</li>
        <li>m - महिना ( E.g. बैशाख)</li>
        <li>d - गते (अंकमा)</li>
        <li>l - दिन (E.g. आइतबार)</li>
        <li>H / h - घण्टा ( H - 24 hour format / h - 12 hour format)</li>
        <li>i - मिनेट</li>
    </ol>
    <p>Home page date format can be altered to default, time ago and hide options.</p>
    <hr>
    <h2>About the Plugin</h2>
    <p>
        "Nepali Date Utilities" plugin is developed in order to provide necessary dates conversion from English to Nepali dates. The plugin contains post dates and today's date shortcode to help sites display nepali dates.
    </p>
    <a href="https://wordpress.org/plugins/nepali-date-utilities" target="_blank">Go to plugin page</a> /
    <a href="mailto:mail@ashokbasnet.com.np"> Provide Feedback</a>

    <?php
}

// Add to admin menu
function ndu_nepali_date_utilities_menu(){
    add_submenu_page('options-general.php','Nepali Date Utilities','Nepali Date Utilities','manage_options','nepali-date-utilities','ndu_nepali_date_utilities_page',111);
}
add_action('admin_menu','ndu_nepali_date_utilities_menu');

// Add Settings link in plugin page
function ndu_add_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=nepali-date-utilities">' . __( 'Settings' ) . '</a>';
    array_push( $links, $settings_link );
    return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'ndu_add_settings_link' );