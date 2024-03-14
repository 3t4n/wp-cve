<?php

add_action( 'admin_menu', 'register' );
add_action( 'wp_ajax_update_ip2location_world_clock_database', 'download_database');
add_action('wp_ajax_ip2location_world_clock_submit_feedback', 'ip2location_world_clock_submit_feedback');
add_action('admin_footer_text', 'ip2location_world_clock_admin_footer_text');

function register() {

    add_menu_page (
        'IP2Location World Clock Options',           // page title
        'IP2Location World Clock',                   // menu title
        'manage_options',                 // capability
        'ip2location-world-clock-options',                   // menu slug
        'menu',                                 // function
        'dashicons-clock',                // icon
        '7'                               // positions
    );
}

function all_fields() {

    add_settings_section(
        'section',                // section id
        null,                    // title
        null,                    // function
        'control-options'        // page
    );

    add_settings_field(
        'type',                // id
        'Clock Type',        // title
        'ip2location_world_clock_type',            // function
        'control-options',        // page
        'section'                // section
    );

    add_settings_field(
        'design',                // id
        'Clock Design',        // title
        'ip2location_world_clock_design',            // function
        'control-options',        // page
        'section'                // section
    );

    add_settings_field(
        'timeformat',                // id
        'Display Time Format',        // title
        'ip2location_world_clock_time_format',            // function
        'control-options',        // page
        'section'                // section
    );
    
        add_settings_field(
        'time',                // id
        'Display Time',        // title
        'ip2location_world_clock_display_time',            // function
        'control-options',        // page
        'section'                // section
    );
    
        add_settings_field(
        'shortcode',                // id
        'Shortcode',        // title
        'ip2location_world_clock_shortcode_params',            // function
        'control-options',        // page
        'section'                // section
    );
    
        add_settings_field(
        'database',                // id
        'IP2Location BIN Database Information',        // title
        'database',            // function
        'control-options',        // page
        'section'                // section
    );

    register_setting('section','ip2location_world_clock_type');
    register_setting('section','ip2location_world_clock_design');
    register_setting('section','ip2location_world_clock_time_format');
    register_setting('section','ip2location_world_clock_display_time');
    register_setting('section','ip2location_world_clock_display_time2');
    register_setting('section','ip2location_world_clock_shortcode_params');

}

add_action('admin_init','all_fields');

function download_database() {
    WP_Filesystem();
    global $wp_filesystem;

    try {
        $code = (isset($_POST['database'])) ? sanitize_text_field($_POST['database']) : '';
        $token = (isset($_POST['token'])) ? sanitize_text_field($_POST['token']) : '';

        $working_dir = IP2LOCATION_DIR . 'working' . DIRECTORY_SEPARATOR;
        $zip_file = $working_dir . 'database.zip';

        // Remove existing working directory
        $wp_filesystem->delete($working_dir, true);

        // Create working directory
        $wp_filesystem->mkdir($working_dir);

        // Start downloading BIN database from IP2Location website.
        if (!class_exists('WP_Http')) {
            include_once ABSPATH . WPINC . '/class-http.php';
        }

        $request = new WP_Http();
        $response = $request->request('https://www.ip2location.com/download?' . http_build_query([
            'file'  => $code,
            'token' => $token,
            'source' => 'wp_clock',
        ]), [
            'timeout' => 120,
        ]);

        if ((isset($response->errors)) || (!(in_array('200', $response['response'])))) {
            $wp_filesystem->delete($working_dir, true);
            die('CONNECTION ERROR');
        }

        // Save downloaded package.
        $fp = fopen($zip_file, 'w');

        if (!$fp) {
            die('NO PERMISSION TO WRITE INTO FILE SYSTEM');
        }

        fwrite($fp, $response['body']);
        fclose($fp);
        if (filesize($zip_file) < 51200) {
            $message = file_get_contents($zip_file);
            $wp_filesystem->delete($working_dir, true);
            die($message);
        }

        // Unzip the package to working directory
        $result = unzip_file($zip_file, $working_dir);

        // Once extracted, delete the package.
        unlink($zip_file);

        if (is_wp_error($result)) {
            $wp_filesystem->delete($working_dir, true);
            die('UNZIP ERROR');
        }

        // File the BIN database
        $bin_database = '';
        $files = scandir($working_dir);

        foreach ($files as $file) {
            if (strtoupper(substr($file, -4)) == '.BIN') {
                $bin_database = $file;
                break;
            }
        }

        // Move file to IP2Location directory
        $wp_filesystem->move($working_dir . $bin_database, IP2LOCATION_DIR . $bin_database, true);

        update_option('ip2location_world_clock_database', $bin_database);

        // Remove working directory
        $wp_filesystem->delete($working_dir, true);

        update_option('ip2location_world_clock_token', $token);

        die('SUCCESS');
    } catch (Exception $e) {
        die('ERROR');
    }

    die('ERROR');
}


function ip2location_world_clock_admin_footer_text($footer_text)
{
    $plugin_name = "ip2location-world-clock";
    $current_screen = get_current_screen();

    if (($current_screen && strpos($current_screen->id, $plugin_name) !== false)) {
        $footer_text .= sprintf(
            __('Enjoyed %1$s? Please leave us a %2$s rating. A huge thanks in advance!', $plugin_name),
            '<strong>' . __('IP2Location World Clock', $plugin_name) . '</strong>',
            '<a href="https://wordpress.org/support/plugin/' . $plugin_name . '/reviews/?filter=5/#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
        );
    }

    if ($current_screen->id == 'plugins') {
        return $footer_text . '
        <div id="ip2location-world-clock-feedback-modal" class="hidden" style="max-width:800px">
            <span id="ip2location-world-clock-feedback-response"></span>
            <p>
                <strong>Would you mind sharing with us the reason to deactivate the plugin?</strong>
            </p>
            <p>
                <label>
                    <input type="radio" name="ip2location-world-clock-feedback" value="1"> I no longer need the plugin
                </label>
            </p>
            <p>
                <label>
                    <input type="radio" name="ip2location-world-clock-feedback" value="2"> I couldn\'t get the plugin to work
                </label>
            </p>
            <p>
                <label>
                    <input type="radio" name="ip2location-world-clock-feedback" value="3"> The plugin doesn\'t meet my requirements
                </label>
            </p>
            <p>
                <label>
                    <input type="radio" name="ip2location-world-clock-feedback" value="4"> Other concerns
                    <br><br>
                    <textarea id="ip2location-world-clock-feedback-other" style="display:none;width:100%"></textarea>
                </label>
            </p>
            <p>
                <div style="float:left">
                    <input type="button" id="ip2location-world-clock-submit-feedback-button" class="button button-danger" value="Submit & Deactivate" />
                </div>
                <div style="float:right">
                    <a href="#">Skip & Deactivate</a>
                </div>
            </p>
        </div>';
    }

    return $footer_text;
}

function ip2location_world_clock_submit_feedback()
{
    $feedback = (isset($_POST['feedback'])) ? sanitize_text_field($_POST['feedback']) : '';
    $others = (isset($_POST['others'])) ? sanitize_text_field($_POST['others']) : '';

    $options = [
        1 => 'I no longer need the plugin',
        2 => 'I couldn\'t get the plugin to work',
        3 => 'The plugin doesn\'t meet my requirements',
        4 => 'Other concerns' . (($others) ? (' - ' . $others) : ''),
    ];

    if (isset($options[$feedback])) {
        if (!class_exists('WP_Http')) {
            include_once ABSPATH . WPINC . '/class-http.php';
        }

        $request = new WP_Http();
        $response = $request->request('https://www.ip2location.com/wp-plugin-feedback?' . http_build_query([
            'name'    => 'ip2location-world-clock',
            'message' => $options[$feedback],
        ]), ['timeout' => 5]);
    }
}


function menu() {
    ?>
        <h1 style="padding: 5px 0 10px 0;">IP2Location World Clock Settings</h1>

        <p><?php settings_errors(); ?></p>

            <form action="options.php" method="post" onsubmit="return ValidateForm()" name="menu">
                <?php
                    if (get_option('ip2location_world_clock_database') == '') {
                        if (file_exists(IP2LOCATION_DIR)) {
                            // Find any .BIN files in current directory
                            $files = scandir(IP2LOCATION_DIR);

                            foreach($files as $file){
                                if (strtoupper(substr($file, -4)) == '.BIN'){
                                    update_option('ip2location_world_clock_database', $file);
                                    break;
                                }
                            }
                        }
                    }

                    if (get_option('ip2location_world_clock_token') == '') {
                        $token = (isset($_POST['token'])) ? sanitize_text_field($_POST['token']) : '';
                        update_option('ip2location_world_clock_token', $token);
                    }

                    settings_fields('section');
                    do_settings_sections('control-options');
                    submit_button();
                ?>
            </form>

    <?php
}

//Display clock type
function ip2location_world_clock_type(){

        $clocktype = get_option('ip2location_world_clock_type');
        ?>
        
<script>
    //Display clock design & manage display for time format
    function checktype() {
        if (document.getElementById('ac').checked) {
            document.getElementById('ac_design').style.display = 'block';
            document.getElementById('dc_design').style.display = 'none';
            document.getElementById('f1').disabled = true;
            document.getElementById('f2').disabled = true;
        } 
        else if (document.getElementById('dc').checked){
            document.getElementById('ac_design').style.display = 'none';
            document.getElementById('dc_design').style.display = 'block';
            document.getElementById('f1').disabled = false;
            document.getElementById('f2').disabled = false;
        }
    }

    //To display dropdown menu when custom time zone selection is checked
    function Check() {
        if (document.getElementById('t3').checked) {
            document.getElementById('custom').style.display = 'block';
        } 
        else {
            document.getElementById('custom').style.display = 'none';
        }
    }

    //To validate form when submit
    function ValidateForm(){
        var t = document.forms["menu"]["ip2location_world_clock_type"].value;
        var c = document.forms["menu"]["ip2location_world_clock_design"].value;
        var r = document.forms["menu"]["ip2location_world_clock_display_time"].value;
        var d = document.forms["menu"]["ip2location_world_clock_display_time2"].value;
        var f = document.forms["menu"]["ip2location_world_clock_time_format"].value;
        var dmatch = c.match(/d(.*)/i);
        var amatch = c.match(/a(.*)/i);

        if (t == ''){
            alert ("Please select your clock type."); 
            return false;
        } 

        if(c == ''){
            alert ("Please select your clock design."); 
            return false;
        }

        if(typeof(dmatch) !== "undefined") {
            if(dmatch !== null){
                if (t == 'ac' && c == dmatch[0]){
                    alert ("Please select your analog clock design."); 
                    return false;
                } 
            }
        }
        if(typeof(amatch) !== "undefined") {
            if(amatch !== null){
                if (t == 'dc' && c == amatch[0]){
                    alert ("Please select your digital clock design."); 
                    return false;
                }
            }
        }

        if (f == ''){
            alert ("Please select your time format."); 
            return false;
        }

        if (r == ''){
            alert ("Please select your display time."); 
            return false;
        }
        if (r == 't3' && d ==''){
            alert ("Please select your time zone."); 
            return false;
        }
     }
</script>

        <input onchange="checktype()" <?php if ( $clocktype == '' ) echo 'checked="checked"' ; if ( $clocktype == 'ac' ) echo 'checked="checked"' ;?>type="radio" name="ip2location_world_clock_type" value="ac" id="ac">Analog Clock<br><br>
        <input onchange="checktype()" <?php if ( $clocktype == 'dc' ) echo 'checked="checked"' ; ?>type="radio" name="ip2location_world_clock_type" value="dc" id="dc">Digital Clock
       
        <?php
    }


//Display clock design selection
function ip2location_world_clock_design() {
    ?>

<style>
/* Hide radio*/
.hiddenradio [type=radio] { 
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

/* Image */
.hiddenradio [type=radio] + img {
    cursor: pointer;
}

/* Checked */
.hiddenradio [type=radio]:checked + img {
    background:#808284;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    border: 4px solid transparent;
    margin-bottom: 0;
}
</style>

        <div class="hiddenradio" style="width:850px;">

        <?php 
            $clock = get_option('ip2location_world_clock_design'); 
            $clocktype = get_option('ip2location_world_clock_type');
        ?>

            <div id="ac_design" style="display:<?php if($clocktype == '' || $clocktype == 'ac') echo'block'; else echo'none'; ?>">
            <!--Analog Clock-->
            <label>
                <input <?php if($clock == '' || $clock == 'a1' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a1" id="clock1">
                <img src="<?php echo plugins_url( '/assets/images/example/clock1.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>

            <label>
                <input <?php if ( $clock == 'a2' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a2" id="clock2">
                <img src="<?php echo plugins_url( '/assets/images/example/clock2.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'a3' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a3" id="clock3">
                <img src="<?php echo plugins_url( '/assets/images/example/clock3.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>

            <label>
                <input <?php if ( $clock == 'a4' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a4" id="clock4">
                <img src="<?php echo plugins_url( '/assets/images/example/clock4.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'a5' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a5" id="clock5">
                <img src="<?php echo plugins_url( '/assets/images/example/clock5.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'a6' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a6" id="clock6">
                <img src="<?php echo plugins_url( '/assets/images/example/clock6.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'a7' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a7" id="clock7">
                <img src="<?php echo plugins_url( '/assets/images/example/clock7.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>

            <label>
                <input <?php if ( $clock == 'a8' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a8" id="clock8">
                <img src="<?php echo plugins_url( '/assets/images/example/clock8.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'a9' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a9" id="clock9">
                <img src="<?php echo plugins_url( '/assets/images/example/clock9.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>

            <label>
                <input <?php if ( $clock == 'a10' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a10" id="clock10">
                <img src="<?php echo plugins_url( '/assets/images/example/clock10.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'a11' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a11" id="clock15">
                <img src="<?php echo plugins_url( '/assets/images/example/clock15.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'a12' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a12" id="clock16">
                <img src="<?php echo plugins_url( '/assets/images/example/clock16.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'a13' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a13" id="clock19">
                <img src="<?php echo plugins_url( '/assets/images/example/clock19.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'a14' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a14" id="clock20">
                <img src="<?php echo plugins_url( '/assets/images/example/clock20.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'a15' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a15" id="clock23">
                <img src="<?php echo plugins_url( '/assets/images/example/clock23.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'a16' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a16" id="clock24">
                <img src="<?php echo plugins_url( '/assets/images/example/clock24.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>

            <label>
                <input <?php if ( $clock == 'a17' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a17" id="clock27">
                <img src="<?php echo plugins_url( '/assets/images/example/clock27.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'a18' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a18" id="clock28">
                <img src="<?php echo plugins_url( '/assets/images/example/clock28.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'a19' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a19" id="clock29">
                <img src="<?php echo plugins_url( '/assets/images/example/clock29.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'a20' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a20" id="clock30">
                <img src="<?php echo plugins_url( '/assets/images/example/clock30.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'a21' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a21" id="clock31">
                <img src="<?php echo plugins_url( '/assets/images/example/clock31.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'a22' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a22" id="clock32">
                <img src="<?php echo plugins_url( '/assets/images/example/clock32.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'a23' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a23" id="clock33">
                <img src="<?php echo plugins_url( '/assets/images/example/clock33.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>

            <label>
                <input <?php if ( $clock == 'a24' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a24" id="clock39">
                <img src="<?php echo plugins_url( '/assets/images/example/clock39.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>

            <label>
                <input <?php if ( $clock == 'a25' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a25" id="clock40">
                <img src="<?php echo plugins_url( '/assets/images/example/clock40.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>

            <label>
                <input <?php if ( $clock == 'a26' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a26" id="clock43">
                <img src="<?php echo plugins_url( '/assets/images/example/clock43.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>

            <label>
                <input <?php if ( $clock == 'a27' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="a27" id="clock44">
                <img src="<?php echo plugins_url( '/assets/images/example/clock44.png', __FILE__ );?>" width="150px; " height="150px;">
            </label>
            </div>

            <!--Digital Clock-->
            <div id="dc_design" style="display:<?php if($clocktype == 'dc') echo'block'; else echo'none'; ?>">
            <label>
                <input <?php if ($clock == 'd1' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d1" id="clock11">
                <img src="<?php echo plugins_url( '/assets/images/example/clock11.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>

            <label>
                <input <?php if ( $clock == 'd2' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d2" id="clock12">
                <img src="<?php echo plugins_url( '/assets/images/example/clock12.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'd3' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d3" id="clock13">
                <img src="<?php echo plugins_url( '/assets/images/example/clock13.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>

            <label>
                <input <?php if ( $clock == 'd4' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d4" id="clock14">
                <img src="<?php echo plugins_url( '/assets/images/example/clock14.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'd5' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d5" id="clock17">
                <img src="<?php echo plugins_url( '/assets/images/example/clock17.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'd6' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d6" id="clock18">
                <img src="<?php echo plugins_url( '/assets/images/example/clock18.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'd7' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d7" id="clock21">
                <img src="<?php echo plugins_url( '/assets/images/example/clock21.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'd8' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d8" id="clock22">
                <img src="<?php echo plugins_url( '/assets/images/example/clock22.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'd9' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d9" id="clock25">
                <img src="<?php echo plugins_url( '/assets/images/example/clock25.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'd10' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d10" id="clock26">
                <img src="<?php echo plugins_url( '/assets/images/example/clock26.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'd11' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d11" id="clock34">
                <img src="<?php echo plugins_url( '/assets/images/example/clock34.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'd12' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d12" id="clock35">
                <img src="<?php echo plugins_url( '/assets/images/example/clock35.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'd13' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d13" id="clock36">
                <img src="<?php echo plugins_url( '/assets/images/example/clock36.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'd14' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d14" id="clock37">
                <img src="<?php echo plugins_url( '/assets/images/example/clock37.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>
            
            <label>
                <input <?php if ( $clock == 'd15' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d15" id="clock38">
                <img src="<?php echo plugins_url( '/assets/images/example/clock38.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>

            <label>
                <input <?php if ( $clock == 'd16' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d16" id="clock41">
                <img src="<?php echo plugins_url( '/assets/images/example/clock41.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>

            <label>
                <input <?php if ( $clock == 'd17' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d17" id="clock42">
                <img src="<?php echo plugins_url( '/assets/images/example/clock42.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>

            <label>
                <input <?php if ( $clock == 'd18' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d18" id="clock45">
                <img src="<?php echo plugins_url( '/assets/images/example/clock45.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>

            <label>
                <input <?php if ( $clock == 'd19' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_design" value="d19" id="clock46">
                <img src="<?php echo plugins_url( '/assets/images/example/clock46.png', __FILE__ );?>" width="200px; " height="89px;">
            </label>
            </div>

        </div>


    <?php
}

    //Display time format
    function ip2location_world_clock_time_format(){
        $clocktype = get_option('ip2location_world_clock_type');
        $timeformat = get_option('ip2location_world_clock_time_format'); 
    ?>

        <input onchange="checktype()" <?php if ( $timeformat == '' ) echo 'checked="checked"' ; if ( $timeformat == 'f1' ) echo 'checked="checked"' ; if($clocktype == '' || $clocktype == 'ac') echo 'disabled '; ?>type="radio" name="ip2location_world_clock_time_format" value="f1" id="f1">12 hour format<br><br>
        <input onchange="checktype()" <?php  if ( $timeformat == 'f2' ) echo 'checked="checked"' ;if($clocktype == '' || $clocktype == 'ac') echo 'disabled '; ?>type="radio" name="ip2location_world_clock_time_format" value="f2" id="f2">24 hour format

        <?php
    }

    //Display Time Selection
    function ip2location_world_clock_display_time(){

         $time = get_option('ip2location_world_clock_display_time'); ?>

        <input <?php if ( $time == '' || $time == 't1' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_display_time" value="t1" id="t1" onchange="Check()"> Local Time<br><br>
        <input <?php if ( $time == 't2' ) echo 'checked="checked"' ; ?> <?php if( !file_exists( IP2LOCATION_DIR . get_option( 'ip2location_world_clock_database' ) ) || get_option( 'ip2location_world_clock_database' ) == '' ) echo 'disabled'; else echo '';?> type="radio" name="ip2location_world_clock_display_time" value="t2" id="t2" onchange="Check()"> Visitor's Local Time (Require IP2Location BIN Database)<br><br>
        <input <?php if ( $time == 't3' ) echo 'checked="checked"' ; ?> type="radio" name="ip2location_world_clock_display_time" value="t3" id="t3" onchange="Check()" > Custom Time Zone

        <?php //Time Zone Selection Menu for Custom Time Zone ?>
        <select id="custom" name="ip2location_world_clock_display_time2" style="display:<?php if ( $time=='t3')echo 'block'; else echo'none';?>" >
        <?php $utc_value = get_option('ip2location_world_clock_display_time2'); ?>
        
            <option value=''>Select Time Zone</option>
            <option <?php if ( $utc_value == '-12' ) echo 'selected' ;?> value='-12'>(UTC - 12:00) Baker Island Time</option>
            <option <?php if ( $utc_value == '-11' ) echo 'selected' ;?> value='-11'>(UTC - 11:00) Niue Time, Samoa Standard Time</option>
            <option <?php if ( $utc_value == '-10' ) echo 'selected' ;?> value='-10'>(UTC - 10:00) Hawaii-Aleutian Standard Time, Cook Island Time</option>
            <option <?php if ( $utc_value == '-9.5' ) echo 'selected' ;?> value='-9.5'>(UTC - 9:30) Marquesas Islands Time</option>
            <option <?php if ( $utc_value == '-9' ) echo 'selected' ;?> value='-9'>(UTC - 9:00) Alaska Standard Time, Gambier Island Time</option>
            <option <?php if ( $utc_value == '-8' ) echo 'selected' ;?> value='-8'>(UTC - 8:00) Pacific Standard Time</option>
            <option <?php if ( $utc_value == '-7' ) echo 'selected' ;?> value='-7'>(UTC - 7:00) Mountain Standard Time</option>
            <option <?php if ( $utc_value == '-6' ) echo 'selected' ;?> value='-6'>(UTC - 6:00) Central Standard Time</option>
            <option <?php if ( $utc_value == '-5' ) echo 'selected' ;?> value='-5'>(UTC - 5:00) Eastern Standard Time</option>
            <option <?php if ( $utc_value == '-4.5' ) echo 'selected' ;?> value='-4.5'>(UTC - 4:30) Venezuelan Standard Time</option>
            <option <?php if ( $utc_value == '-4' ) echo 'selected' ;?> value='-4'>(UTC - 4:00) Atlantic Standard Time</option>
            <option <?php if ( $utc_value == '-3.5' ) echo 'selected' ;?> value='-3.5'>(UTC - 3:30) Newfoundland Standard Time</option>
            <option <?php if ( $utc_value == '-3' ) echo 'selected' ;?> value='-3'>(UTC - 3:00) Amazon Standard Time, Central Greenland Time</option>
            <option <?php if ( $utc_value == '-2' ) echo 'selected' ;?> value='-2'>(UTC - 2:00) Fernando de Noronha Time, South Georgia &amp; the </option>
            <option <?php if ( $utc_value == '-1' ) echo 'selected' ;?> value='-1'>(UTC - 1:00) Azores Standard Time, Cape Verde Time, Eastern Gre</option>
            <option <?php if ( $utc_value == '0' ) echo 'selected' ;?> value='0'>(UTC) Western European Time, Greenwich Mean Time</option>
            <option <?php if ( $utc_value == '+1' ) echo 'selected' ;?> value='+1'>(UTC + 1:00) Central European Time, West African Time</option>
            <option <?php if ( $utc_value == '+2' ) echo 'selected' ;?> value='+2'>(UTC + 2:00) Eastern European Time, Central African Time</option>
            <option <?php if ( $utc_value == '+3' ) echo 'selected' ;?> value='+3'>(UTC + 3:00) Moscow Standard Time, Eastern African Time</option>
            <option <?php if ( $utc_value == '+3.5' ) echo 'selected' ;?> value='+3.5'>(UTC + 3:30) Iran Standard Time</option>
            <option <?php if ( $utc_value == '+4' ) echo 'selected' ;?> value='+4'>(UTC + 4:00) Gulf Standard Time, Samara Standard Time</option>
            <option <?php if ( $utc_value == '+4.5' ) echo 'selected' ;?> value='+4.5'>(UTC + 4:30) Afghanistan Time</option>
            <option <?php if ( $utc_value == '+5' ) echo 'selected' ;?> value='+5'>(UTC + 5:00) Pakistan Standard Time, Yekaterinburg Standard Tim</option>
            <option <?php if ( $utc_value == '+5.5' ) echo 'selected' ;?> value='+5.5'>(UTC + 5:30) Indian Standard Time, Sri Lanka Time</option>
            <option <?php if ( $utc_value == '+5.75' ) echo 'selected' ;?> value='+5.75'>(UTC + 5:45) Nepal Time</option>
            <option <?php if ( $utc_value == '+6' ) echo 'selected' ;?> value='+6'>(UTC + 6:00) Bangladesh Time, Bhutan Time, Novosibirsk Standard</option>
            <option <?php if ( $utc_value == '+6.5' ) echo 'selected' ;?> value='+6.5'>(UTC + 6:30) Cocos Islands Time, Myanmar Time</option>
            <option <?php if ( $utc_value == '+7' ) echo 'selected' ;?> value='+7'>(UTC + 7:00) Indochina Time, Krasnoyarsk Standard Time</option>
            <option <?php if ( $utc_value == '+8' ) echo 'selected' ;?> value='+8'>(UTC + 8:00) Chinese Standard Time, Australian Western Standard</option>
            <option <?php if ( $utc_value == '+8.75' ) echo 'selected' ;?> value='+8.75'>(UTC + 8:45) Southeastern Western Australia Standard Time</option>
            <option <?php if ( $utc_value == '+9' ) echo 'selected' ;?> value='+9'>(UTC + 9:00) Japan Standard Time, Korea Standard Time, Chita St</option>
            <option <?php if ( $utc_value == '+9.5' ) echo 'selected' ;?> value='+9.5'>(UTC + 9:30) Australian Central Standard Time</option>
            <option <?php if ( $utc_value == '+10' ) echo 'selected' ;?> value='+10'>(UTC + 10:00) Australian Eastern Standard Time, Vladivostok Sta</option>
            <option <?php if ( $utc_value == '+10.5' ) echo 'selected' ;?> value='+10.5'>(UTC + 10:30) Lord Howe Standard Time</option>
            <option <?php if ( $utc_value == '+11' ) echo 'selected' ;?> value='+11'>(UTC + 11:00) Solomon Island Time, Magadan Standard Time</option>
            <option <?php if ( $utc_value == '+11.5' ) echo 'selected' ;?> value='+11.5'>(UTC + 11:30) Norfolk Island Time</option>
            <option <?php if ( $utc_value == '+12' ) echo 'selected' ;?> value='+12'>(UTC + 12:00) New Zealand Time, Fiji Time, Kamchatka Standard T</option>
            <option <?php if ( $utc_value == '+12.75' ) echo 'selected' ;?> value='+12.75'>(UTC + 12:45) Chatham Islands Time</option>
            <option <?php if ( $utc_value == '+13' ) echo 'selected' ;?> value='+13'>(UTC + 13:00) Tonga Time, Phoenix Islands Time</option>
            <option <?php if ( $utc_value == '+14' ) echo 'selected' ;?> value='+14'>(UTC + 14:00) Line Island Time</option>
      
    </select>


        <?php
    }

    function ip2location_world_clock_shortcode_params(){
        ?>
        <style>
        .shortcode-table{width:85%;}
        .shortcode-table th{width:50px;}
        </style>

        <div>
            <h2 class="title">Shortcode Examples</h2>
            <table class="form-table">
                <tr>
                    <th scope="row" style="width:320px;"><label>Display default clock design</label></th>
                    <td>[ip2location_world_clock]</td>
                </tr>
                <tr>
                    <th scope="row" style="width:320px;"><label>Display 2nd design of analog clock in local time</label></th>
                    <td>[ip2location_world_clock design="a2" time="local"]</td>
                </tr>
                <tr>
                    <th scope="row"><label>Display 5th design of digital clock in visitor’s local time</label></th>
                    <td>[ip2location_world_clock design="d5" time="visitor"]</td>
                </tr>
                 <tr>
                    <th scope="row"><label>Display 1st design of digital clock in custom time zone of +4:00</label></th>
                    <td>[ip2location_world_clock design="d1" time="custom" utc="+4"]</td>
                </tr>
            </table>
        </div>

        <div>
            <h2 class="title">Shortcode Parameters</h2>
            <table class="form-table shortcode-table">
                <tr scope="row"><th>Parameters</th><th>Description</th></tr>
                <tr scope="row"><th>design</th><td>Use 'a' followed by the position of the analog clock design shown above whereas use 'd' followed by the position of the digital clock design shown above. The position of clock design is counted from top to bottom and left to right. For instance, use 'a1' for first analog clock design wheareas use 'd5' for fifth digital clock design.</td></tr>
                <tr scope="row"><th>time</th><td>Use 'local' for Local Time, 'visitor' for Visitor’s Local Time and 'custom' for Custom Time Zone.</td></tr>
                <tr scope="row"><th>utc</th><td>Use this parameters when you are using Custom Time Zone. Values available are from -12 until +14. You may refer the values in Custom Time Zone option shown above.</td></tr>
            </table>
        </div>
        <?php
    }
    
    //Display IP2Location BIN Database Information
    function database(){
        if ( !file_exists( IP2LOCATION_DIR . get_option( 'ip2location_world_clock_database' ) ) || get_option( 'ip2location_world_clock_database' ) == '' ) {
            echo '
                <div id="message" class="error">
                    <p>
                        Unable to find the IP2Location BIN database! Please download the database at <a href="https://www.ip2location.com/?r=wordpress" target="_blank">IP2Location commercial database</a> | <a href="https://lite.ip2location.com/?r=wordpress" target="_blank">IP2Location LITE database (free edition)</a>.
                    </p>
                </div>';
        }
        else {
            if ( ! class_exists( 'IP2Location\\Database' ) ) {
                    require_once( IP2LOCATION_WORLD_CLOCK_ROOT . 'class.IP2Location.php' );
                }

            if (!is_file(IP2LOCATION_DIR . get_option('ip2location_world_clock_database'))) {
                return;
            }

            $ipl = new \IP2Location\Database(IP2LOCATION_DIR . get_option('ip2location_world_clock_database'), \IP2Location\Database::FILE_IO);
            $dbVersion = $ipl->getDatabaseVersion();
            $curdbVersion = str_replace(".", "-", $dbVersion);
            echo '
                <div id="bin_database">
                    <h2 class="title">IP2Location BIN Database Information</h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label>File Name</label>
                            </th>
                            <td>
                                <div>' . ((!file_exists(IP2LOCATION_DIR . get_option('ip2location_world_clock_database'))) ? '<span class="dashicons dashicons-warning" title="Database file not found."></span>' : '') . get_option('ip2location_world_clock_database') . '
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label>Database Date</label>
                            </th>
                            <td>
                                ' . (($curdbVersion) ? $curdbVersion : '-') . '
                            </td>
                        </tr>
                    </table>
                </div>';
        }
        wp_enqueue_script('script', plugins_url('assets/js/script.js', __FILE__ ) ,array('jquery'), true );
        echo '
            <div id="bin_download">
                <h2 class="title">Download & Update IP2Location BIN Database</h2>

                <div id="download_status"></div>

                <table class="form-table">
                <form method="POST" id="ip2location_db_table">
                    <tr>
                        <th scope="row">
                            <label for="database_name">Database Name</label>
                        </th>
                        <td>
                            <select name="database_name" id="database_name">
                                <option value="">Select Database</option>
                                <option value="DB11LITEBIN"> IP2Location LITE DB11</option>
                                <option value="DB11BIN"> IP2Location DB11</option>
                                <option value="DB11LITEBINIPV6">IP2Location LITE DB11 (IPv6)</option>
                                <option value="DB11BINIPV6">IP2Location DB11 (IPv6)</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="token">Download Token</label></th>
                        <td>
                            <input name="token" type="text" id="token" value="' . get_option('ip2location_world_clock_token') . '" class="regular-text" />
                            <p class="description">
                                Get your download token from <a href="https://lite.ip2location.com/database-download" target="_blank">https://lite.ip2location.com/database-download</a> or <a href="https://www.ip2location.com/file-download" target="_blank">https://www.ip2location.com/file-download</a>.
                                <br><br>
                                If you failed to download the BIN database using this automated downloading tool, please follow the procedures below to update the BIN database manually.

                                <ol>
                                    <li>
                                        Download the BIN database at <a href="http://www.ip2location.com/?r=wordpress" target="_blank">IP2Location commercial database</a> | <a href="http://lite.ip2location.com/?r=wordpress" target="_blank">IP2Location LITE database (free edition)</a>.</li>
                                    <li>
                                        Decompress the zip file and update the BIN database to <code>' . IP2LOCATION_DIR . '</code>.
                                    </li>
                                    <li>
                                        Once completed, please refresh the information by reloading the setting page.
                                    </li>
                                </ol>
                            </p>
                            <p class="description">
                                You may implement automated monthly database update as well. <a href="https://www.ip2location.com/resources/how-to-automate-ip2location-bin-database-download" target="_balnk">Learn more...</a>
                            </p>
                        </td>
                    </tr>
                </form>
                </table>

                <div id="ip2location-download-progress" style="display:none;">
                    <div class="loading-admin-ip2location"></div> Downloading...
                </div>

                <p class="submit">
                    <input type="button" name="download" id="download" class="button button-primary" value="Download/Update Now" />
                </p>
            </div>';
    }