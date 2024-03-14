<?php

/*
Plugin Name: Better WishList API
Plugin URI: http://www.bureauram.nl
Description: A better version of the WishList Member API. Created to make the connection to external services like ActiveCampaign and Autorespond a lot easier. Also gives option to send email notifications after succesfully adding an user through the API.
Version: 1.1.2
Author: Rick Heijster @ Bureau RAM
Author URI: http://www.bureauram.nl
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: better-wlm-api
*/

if (!defined('BWA_VERSION_KEY'))
    define('BWA_VERSION_KEY', 'bwa_version');

if (!defined('BWA_VERSION_NUM'))
    define('BWA_VERSION_NUM', '1.1.2');

/* !0. TABLE OF CONTENTS */

/*
	
	1. HOOKS

	2. SHORTCODES

	3. FILTERS
		3.1 bwa_admin_menus()
		3.2 bwa_plugin_action_links()

	4. EXTERNAL SCRIPTS
        4.1 bwa_custom_css()
		
	5. ACTIONS
		5.1 bwa_install()
        5.2 bwa_upgrade()
        5.3 bwa_update_check()
        5.4 bwa_create_tables()
        5.5 bwa_log_event()
        5.6 bwa_download_log_csv()

	6. HELPERS
		6.1 bwa_check_is_wlm_active()
        6.2 bwa_get_yesno_select()
        6.3 bwa_get_current_options()
        6.4 bwa_send_email_confirmation()
        6.5 bwa_mail_contents()
		6.6 bwa_register_user_data()

	7. CUSTOM POST TYPES
	
	8. ADMIN PAGES
		8.1 bwa_admin_page() - Main Admin Page

	9. SETTINGS
        9.1 bwa_register_options()

    10. API

*/

/* !1. HOOKS */
// hint: register our custom menus
add_action('admin_menu', 'bwa_admin_menus');

// hint: register plugin options
add_action('admin_init', 'bwa_register_options');

// hint: register custom css
add_action('admin_head', 'bwa_custom_css');

// hint: put the API in the loop
add_action('init', 'bwa_better_wlm_api');

// hint: run install/upgrade
register_activation_hook( __FILE__, 'bwa_install' );
add_action( 'plugins_loaded', 'bwa_update_check' );

// hint: fire download when clicked on link in admin page
add_action('wp_ajax_bwa_download_log_csv', 'bwa_download_log_csv'); // admin users

// hint: Add settings link to Plugin page
add_filter('plugin_action_links', 'bwa_plugin_action_links', 10, 2);

/* !2. SHORTCODES */

/* !3. FILTERS */

// 3.1
// hint: registers custom plugin admin menus
function bwa_admin_menus() {

    $top_menu_item = 'bwa_admin_page';
    add_submenu_page( 'options-general.php', 'Better WishList API', 'Better WishList API', 'manage_options', $top_menu_item, $top_menu_item );

}

// 3.2
// hint: add Settings link to Plugins page

function bwa_plugin_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=bwa_admin_page">Settings</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}


/* !4. EXTERNAL SCRIPTS */

// 4.1
// hint: adds custom css to head
function bwa_custom_css() {
    echo '<link rel="stylesheet" href="'. plugins_url( 'assets/css/style-admin.css', __FILE__ ) .'" type="text/css" media="all" />';
}

/* !5. ACTIONS */

// 5.1
// hint: Create table wp_bwa_log
function bwa_install() {
    add_option(BWA_VERSION_KEY, BWA_VERSION_NUM);

    bwa_create_tables();
}

// 5.2
// hint: Runs upgrade scripts
function bwa_upgrade() {
    bwa_create_tables();

    update_option(BWA_VERSION_KEY, BWA_VERSION_NUM);
}

// 5.3
// Checks if upgrade is needed
function bwa_update_check() {
    //Check for version and upgrade if necessary
    if (get_option('bwa_version') != BWA_VERSION_NUM) bwa_upgrade();
}

// 5.4
// hint: creates tables
function bwa_create_tables() {
    global $wpdb;

    // setup return value
    $return_value = false;

    try {

        $table_name = $wpdb->prefix . "bwa_log";
        $charset_collate = $wpdb->get_charset_collate();

        // sql for our table creation
        $sql = "CREATE TABLE ".$table_name." (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                  datetime timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  request text NOT NULL,
                  status varchar(25) NOT NULL,
                  result text NOT NULL,
                  PRIMARY KEY (id)
			) $charset_collate;";

        // make sure we include wordpress functions for dbDelta
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // dbDelta will create a new table if none exists or update an existing one
        dbDelta($sql);

        // return true
        $return_value = true;

    } catch( Exception $e ) {

        // php error

    }

    // return result
    return $return_value;
}

// 5.5
// hint: adds events to logfile
function bwa_log_event( $query_string, $array_result ) {

    global $wpdb;

    // setup our return value
    $return_value = false;

    $req = $query_string;
    $status = $array_result['status'];
    $result = $array_result['message'];
    if ($status == "Success") { $log_type = "info"; } else { $log_type = "warning"; }

    try {

        $table_name = $wpdb->prefix . "bwa_log";

        $wpdb->insert(
            $table_name,
            array(
                'request' => $req,
                'status' => $status,
                'result' => $result,
            ),
            array(
                '%s',
                '%s',
                '%s',
            )
        );

        // return true
        $return_value = true;

    } catch( Exception $e ) {

        // php error

    }

    bwa_log_event_simple_history($result, $log_type);

    // return result
    return $return_value;

}

function bwa_log_event_simple_history($message, $type="info") {
//If Simple History is installed, send result to this log too

    if ( function_exists("SimpleLogger") ) {
        if ($type == "info") {
            apply_filters( 'simple_history_log', "Received request through Better WishList API: ".$message, null, 'info' );            
        } elseif ($type == "warning") {
            apply_filters( 'simple_history_log', "Received request through Better WishList API: ".$message, null, 'warning' );            
        } elseif ($type == "debug") {            
            apply_filters( 'simple_history_log', "Received request through Better WishList API: ".$message, null, 'debug' );            
        }
    }
}

// 5.6
// hint: generates a .csv file of subscribers data
// expects $_GET['list_id'] to be set in the URL
function bwa_download_log_csv() {
    global $wpdb;

    // setup our return data
    $csv = '';
    $table_name = $wpdb->prefix . "bwa_log";

    // get the records in the log table
    $logs = $wpdb->get_results(
                "SELECT datetime, request, status, result
	             FROM ".$table_name
    );

    // IF we have rows in the log
    if( $wpdb->num_rows > 0 ):
        $now = new DateTime();

        // setup a unique filename for the generated export file
        $fn1 = 'bwa-log'. $now->format('Ymd'). '.csv';
        $fn2 = plugin_dir_path( __FILE__ ) .'exports/'.$fn1;

        // open new file in write mode
        $fp = fopen($fn2, 'w');

        $csv_headers = array("Datetime", "Request", "Status", "Result");

        // append $csv_headers to our csv file
        fputcsv($fp, $csv_headers);

        // loop over all our subscribers
        foreach ( $logs as $log ):

            $array_log = array("datetime" => $log->datetime,
                        "request" => $log->request,
                        "status" => $log->status,
                        "result" => $log->result);

            // append this subscriber's data to our csv file
            fputcsv($fp, $array_log);

        endforeach;

        // read open our new file is read mode
        $fp = fopen($fn2, 'r');
        // read our new csv file and store it's contents in $fc
        $fc = fread($fp, filesize($fn2) );
        // close our open file pointer
        fclose($fp);

        // setup file headers
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=".$fn1);
        // echo the contents of our file and return it to the browser
        echo($fc);
        // exit php processes
        exit;

    endif;

    // return false if we were unable to download our csv
    return false;

}

function bwa_show_log() {
    global $wpdb;

    // setup our return data
    if (!isset($_GET['bwa_log_page'])) {
        $page = 0;
    } else {
        $page = $_GET['bwa_log_page'];
    }

    $first_record = $page * 50;

    $table_name = $wpdb->prefix . "bwa_log";

    $log_count = $wpdb->get_var( "SELECT COUNT(*)
	             FROM ".$table_name);

    $query = $wpdb->prepare("SELECT datetime, request, status, result
	             FROM ".$table_name."
	             ORDER BY datetime DESC
	             LIMIT ".$first_record.", 50", array());

    $last_page = false;
    $min_count = ($page) * 50;
    $max_count = ($page + 1) * 50;

    if (($page + 1) * 50 > $log_count) {
        $max_item = $log_count;
        $last_page = true;
    }

    // get the records in the log table
    $logs = $wpdb->get_results($query);

    // IF we have rows in the log
    if( $wpdb->num_rows > 0 ) {

        echo "<p><strong>Log items ".$min_count." tot ".$max_count."</strong></p>";

        echo "<div class='bwa-log-items-wrap'>
                <ul class='bwa-log-items'>";
        // loop over all our subscribers
        foreach ($logs as $log) {            
            echo "
                <li class='bwa-log-item'>
                    <div class='div-bwa-log-item-header'>
                        <span class='bwa-log-item-date-label'>Date</span>: <span class='bwa-log-item-date'>" . $log->datetime . "</span> <span class='bwa-log-item-status-label'>Status</span>: <span class='bwa-log-item-status bwa-status-".$log->status."'>" . $log->status . "</span><br/>
                        <span class='bwa-log-item-result-label'>Result</span>: <span class='bwa-log-item-result'>" . $log->result . "</span><br/>
                    </div>
                    <div class='div-bwa-log-item-request'>    
                        <span class='bwa-log-item-request-label'>Request</span>: <span class='bwa-log-item-request'><pre>" . bwa_prettyPrint($log->request) . "</pre></span><br/>
                    </div>
                    <hr>
                </li>";                        
        }

        echo "</ul></div>";

        if ($page > 0) echo "<a href='/wp-admin/options-general.php?page=bwa_admin_page&tab=log&bwa_log_page=".($page - 1)."'>Vorige 50</a>";
        if ($page > 0 || !$last_page) echo " | ";
        if (!$last_page) echo "<a href='/wp-admin/options-general.php?page=bwa_admin_page&tab=log&bwa_log_page=".($page + 1)."'>Volgende 50</a>";

    } else {
        echo "<p>No items in log found</p>";
    }

}

function bwa_prettyPrint( $json ) {
    $json = str_replace(";", ";&nbsp;", $json);
    $json = str_replace("{", "&nbsp;{&nbsp;", $json);

    return $json;
}

/* !6. HELPERS */

// 6.1
// hint: Checks if WishList Member is active
function bwa_check_is_wlm_active() {
	//include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	//$wlm_is_active = false;
	//if ( is_plugin_active( 'wishlist-member/wpm.php' ) ) {
	//	$wlm_is_active = true;
	//}
	
	global $WishListMemberInstance;
	if(isset($WishListMemberInstance)) {
		$wlm_is_active = true;
	}

	return $wlm_is_active;
}

function bwa_check_is_rmt_active() {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    $rmt_is_active = false;

    if ( is_plugin_active( 'ram_membership_tools/ram_membership_tools.php' ) ) {
        $rmt_is_active = true;
    }

    if ( is_plugin_active( 'ram-membership-tools/ram-membership-tools.php' ) ) {
        $rmt_is_active = true;
    }

    return $rmt_is_active;
}

// 6.2
// hint: returns html for a page selector
function bwa_get_yesno_select( $input_name="bwa_yesno", $input_id="", $selected_value="" ) {

	// setup our select html
	$select = '<select name="'. $input_name .'" ';

	// IF $input_id was passed in
	if( strlen($input_id) ):
		// add an input id to our select html
		$select .= 'id="'. $input_id .'" ';

	endif;

	// setup our first select option
	$select .= '><option value="">- Select One -</option>';

	//Add Yes
	// check if this option is the currently selected option
	$selected = '';
	if( $selected_value == "1" ):
		$selected = ' selected="selected" ';
	endif;

	// build our option html
	$option = '<option value="1" '. $selected .'>Ja</option>';

	// append our option to the select html
	$select .= $option;

	//Add No
	// check if this option is the currently selected option
	$selected = '';
	if( $selected_value == "0" ):
		$selected = ' selected="selected" ';
	endif;

	// build our option html
	$option = '<option value="0" '. $selected .'>Nee</option>';

	// append our option to the select html
	$select .= $option;
	// close our select html tag
	$select .= '</select>';

	// return our new select
	return $select;

}

// 6.3
// hint: get's the current options and returns values in associative array
function bwa_get_current_options() {

	// setup our return variable
	$current_options = array();

	try {

		$bwa_option_send_confirmation_email = (get_option('bwa_option_send_confirmation_email', null) !== null) ? get_option('bwa_option_send_confirmation_email') : 0;
		$bwa_option_check_if_user_exists = (get_option('bwa_option_check_if_user_exists', null) !== null) ? get_option('bwa_option_check_if_user_exists') : 1;
        $bwa_option_extend_if_user_exists = (get_option('bwa_option_extend_if_user_exists', null) !== null) ? get_option('bwa_option_extend_if_user_exists') : 0;
		$bwa_option_update_user_data = (get_option('bwa_option_update_user_data', null) !== null) ? get_option('bwa_option_update_user_data') : 1;
		$bwa_option_destination_email = (get_option('bwa_option_destination_email')) ? get_option('bwa_option_destination_email') : get_option('admin_email');
        $bwa_options_email_include_password = (get_option('bwa_options_email_include_password')) ? get_option('bwa_options_email_include_password') : 0;

		// build our current options associative array
		$current_options = array(
			'bwa_option_send_confirmation_email' => $bwa_option_send_confirmation_email,
			'bwa_option_check_if_user_exists' => $bwa_option_check_if_user_exists,
            'bwa_option_extend_if_user_exists' => $bwa_option_extend_if_user_exists,
			'bwa_option_destination_email' => $bwa_option_destination_email,
			'bwa_option_update_user_data' => $bwa_option_update_user_data,
            'bwa_options_email_include_password' => $bwa_options_email_include_password,
		);

	} catch( Exception $e ) {

		// php error

	}

	// return current options
	return $current_options;

}

// 6.4
// hint: Send email confirmations
function bwa_send_email_confirmation($action, $user, $user_pass, $level) {
	// setup return variable
	$email_sent = false;

	$options = bwa_get_current_options();

	$email_destination = explode(";", $options['bwa_option_destination_email']);
    $email_include_password = $options['bwa_options_email_include_password'];

	// get email data
	$email_contents = bwa_mail_contents($action, $user, $user_pass, $level, $email_include_password);


	// IF email template data was found
	if( !empty( $email_contents ) ):

		// set wp_mail headers
		$wp_mail_headers = array('Content-Type: text/html; charset=UTF-8');

		// use wp_mail to send email
		$email_sent = wp_mail( $email_destination , $email_contents['subject'], $email_contents['contents'], $wp_mail_headers );

	endif;

	return $email_sent;
}

// 6.5
// hint: create email contents
function bwa_mail_contents($action, $user, $user_pass, $level, $email_include_password) {

	$email_contents = array();

	if ($action == "new_user") {
		$email_contents['subject'] = "Nieuwe gebruiker toegevoegd aan WishList Member via extern systeem";
		$email_contents['contents'] = '
		<p>Hallo,</p>
		<p>Er is zojuist een nieuwe gebruiker toegevoegd aan WishList Member via extern systeem:</p>
		<p>Gebruiker: '.$user.'<br/>';
		   if ($email_include_password) $email_contents['contents'] = $email_contents['contents'].'Wachtwoord: '.$user_pass.'<br/>';


        $email_contents['contents'] = $email_contents['contents'].'
		Level: '.$level.'</p>
		<p>Met vriendelijke groet,<br/>
		   Better WishList API</p>
		';

	} elseif ($action == "add_level") {
		$email_contents['subject'] = "Nieuw level toegevoegd aan gebruiker in WishList Member via extern systeem";
		$email_contents['contents'] = '
		<p>Hallo,</p>
		<p>Er is zojuist een nieuw level toegevoegd aan gebruiker '.$user.' in WishList Member via extern systeem:</p>
		<p>Gebruiker: '.$user.'<br/>
		   Toegevoegd level: '.$level.'</p>
		<p>Met vriendelijke groet,<br/>
		   Better WishList API</p>
		';
	}

	return $email_contents;

}

// 6.6
// hint: updates user data with first name, last name and display name
function bwa_register_user_data ($userid, $fname, $lname) {
	$first = 0;
	$last = 0;
	$user_array = array( 'ID' => $userid);

    $user_info = get_userdata($userid);

    if (!strlen($user_info->first_name && !strlen($user_info->last_name))) {
        // Only update if there is no current first name and/or last name registered
        if (strlen($fname)) {
            $first_name = esc_attr($fname);
            $user_array['first_name'] = $first_name;
            $first = 1;
        }

        if (strlen($lname)) {
            $last_name = esc_attr($lname);
            $user_array['last_name'] = $last_name;
            $last = 1;
        }

        if ($first && !$last) {
            $user_array['display_name'] = $first_name;
            $result = "Name and Display name set to ".$first_name.".";
        } elseif ($first && $last) {
            $user_array['display_name'] = $first_name . " " . $last_name;
            $result = "Name and Display name set to ".$first_name. " " . $last_name.".";
        } elseif (!$first && $last) {
            $user_array['display_name'] = $last_name;
            $result = "Name and Display name set to ".$last_name.".";
        }

        if ($first || $last) {
            $user_id = wp_update_user($user_array);
            if (is_wp_error($user_id)) {
                $error_string = $user_id->get_error_message();
                return "Error: ".$error_string;
            } else {
                return $result;
            }
        } else {
            return "No first or last name received. Name not set.";
        }
    } else {
        return "First name and/or last name already registered. Name not set.";
    }
}

function bwa_get_levels_array($level_string) {
	if ( ! empty( $level_string ) ) {

		$level_string = str_replace(' ', '', $level_string); //First, remove all spaces

		if ( strpos( $level_string, ";" ) !== false ) {
			//Multiple levels, delimeter is ;
			$array_levels = explode( ";", $level_string);
		} elseif ( strpos( $level_string, "," ) !== false ) {
			//Multiple levels, delimeter is ,
			$array_levels = explode( ",", $level_string );
		} else {
			//Single level
			$array_levels[0] = $level_string;
		}
	} else {
		$array_levels = array(); //Empty array'
	}

	return $array_levels;
}

function bwa_get_levels_id_string($array_levels) {
	$str_levels_id = "";

	foreach ($array_levels as $level) {

		if ($str_levels_id == "") {
			$str_levels_id = $level;
		} else {
			$str_levels_id = $str_levels_id.", ".$level;
		}
	}

	return $str_levels_id;
}

function bwa_get_levels_names_string($array_levels) {
	$wlm_api_methods = new WLMAPIMethods();
	$str_levels_name = "";

	foreach ($array_levels as $level) {
		$array_info_level = $wlm_api_methods->get_level($level);

		if ($str_levels_name == "") {
			$str_levels_name = $array_info_level['level']['name'];
		} else {
			$str_levels_name = $str_levels_name.", ".$array_info_level['level']['name'];
		}
	}

	return $str_levels_name;
}

function bwa_get_wishlist_levels() {
	$wlm_api_methods = new WLMAPIMethods();
	$wishlist_array = $wlm_api_methods->get_levels();

	$result = $wishlist_array["levels"]["level"];

	return $result;

}


/* !8. ADMIN PAGES */

// 8.1 Main Admin Menu
// hint: create Admin menu

function bwa_admin_page() {

	$options = bwa_get_current_options();

    $error_simple_wlm_api_active = "";

    if (isset($_GET['downloadlog'])) bwa_download_log_csv();

    if ( function_exists('simple_wlm_api') ) {
        $error_simple_wlm_api_active = '
            <div class="error">
                <p>
                    <strong>De orignele Simple WLM API plugin is actief.</strong>
                </p>
                <p>
                    De werking van Better WishList API is uitgeschakeld, om te voorkomen dat er conflicten optreden.
                </p>
                <p>
                    Deactiveer en/of verwijder de plugin Simple WLM API in je Plugins overzicht als je Better WishList API wilt gebruiken
                </p>
            </div>';
    }

	if (!bwa_check_is_wlm_active()) {
		$error_wlm_not_active = '
            <div class="error">
                <p>
                    <strong>De plugin WishList Member is niet gevonden.</strong>
                </p>
                <p>
                    Deze plugin is een uitbreiding van de plugin WishList Member.
                </p>
                <p>
                    Zonder WishList Member heeft deze plugin geen functie.
                </p>
            </div>';
	}

    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'front_page_options';

    $active_class_front_page_options = $active_tab == "front_page_options" ? "nav-tab-active" : "";
	$active_class_connect = $active_tab == "connect" ? "nav-tab-active" : "";
    $active_class_log = $active_tab == "log" ? "nav-tab-active" : "";

	echo '
		<div class="wrap">
			<h2>Better WishList Member API</h2>
            <h2 class="nav-tab-wrapper">
                <a href="?page=bwa_admin_page&tab=front_page_options" class="nav-tab ' . $active_class_front_page_options . '">Instellingen</a>
                <a href="?page=bwa_admin_page&tab=connect" class="nav-tab ' . $active_class_connect . '">Koppelingen</a>     
                <a href="?page=bwa_admin_page&tab=log" class="nav-tab ' . $active_class_log . '">Log</a>     
            </h2> <!-- nav-tab-wrapper -->			
			<div class="bwa-wrapper">
                <div id="content" class="wrapper-cell">';
                if ($active_tab == "front_page_options") {
	                echo '
                    ' . $error_simple_wlm_api_active . $error_wlm_not_active . '
                    <h2>Better WishList API Opties</h2>
                    <p>Deze plugin geeft je meer opties voor het integreren van externe systemen, zoals Autorespond en ActiveCampaign en WishList Member</p>
                    <p>Met name:</p>
                    <ol>
                        <li>De mogelijkheid om via het externe systeem een tweede level aan een gebruiker toe te kennen</li>
                        <li>De mogelijkheid om van je website een bevestiging te krijgen van de aanmelding van de nieuwe gebruiker of het toekennen van het nieuwe level</li>
                        <li>De mogelijkheid om ook de voor- en achternaam in te vullen</li>
                    </ol>

                    <form action="options.php" method="post">';
	                // outputs a unique nounce for our plugin options
	                settings_fields( 'bwa_plugin_options' );
	                // generates a unique hidden field with our form handling url
	                @do_settings_fields( 'bwa_plugin_options', 'default' );

	                echo '<table class="form-table">

                            <tbody>

                                <tr>
                                    <th scope="row"><label for="bwa_option_check_if_user_exists">Controleren bestaande gebruiker</label></th>
                                    <td>
                                        ' . bwa_get_yesno_select( "bwa_option_check_if_user_exists", "bwa_option_check_if_user_exists", $options['bwa_option_check_if_user_exists'] ) . '
                                        <p class="description" id="bwa_option_check_if_user_exists-description">Als deze optie is ingeschakeld, zal Better WishList API controleren of de gebruiker al bestaat, door het opgegeven e-mailadres te vergelijken met bestaande gebruikers. Als een bestaande gebruiker wordt gevonden, zal er geen nieuwe gebruiker worden toegevoegd. In plaats daarvan zal het nieuwe level aan de bestaande gebruiker worden toegekend.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="bwa_option_extend_if_user_exists">Pas registratiedatum aan als gebruiker al lid is van een level</label></th>
                                    <td>
                                        ' . bwa_get_yesno_select( "bwa_option_extend_if_user_exists", "bwa_option_check_if_user_exists", $options['bwa_option_extend_if_user_exists'] ) . '
                                        <p class="description" id="bwa_option_extend_if_user_exists-description">Als deze optie is ingeschakeld, zal Better WishList API controleren of een gebruiker al lid is van een level. Als dat zo is, dan past hij de registratiedatum aan naar de datum van vandaag. Dit is vooral zinvol als je levels met een verloopdatum wilt verlengen.</p>
                                    </td>
                                </tr>                              
                                <tr>
                                    <th scope="row"><label for="bwa_option_update_user_data">Registreer Naam</label></th>
                                    <td>
                                        ' . bwa_get_yesno_select( "bwa_option_update_user_data", "bwa_option_update_user_data", $options['bwa_option_update_user_data'] ) . '
                                        <p class="description" id="bwa_option_update_user_data-description">Als deze optie is ingeschakeld, worden de voornaam, achternaam en schermnaam toegevoegd aan de gebruiker, als deze niet al zijn ingevuld.</p>
                                    </td>
                                </tr>
                                <tr>
                                <tr>
                                    <th scope="row"><label for="bwa_option_send_confirmation_email">Bevesting e-mail</label></th>
                                    <td>
                                        ' . bwa_get_yesno_select( "bwa_option_send_confirmation_email", "bwa_option_send_confirmation_email", $options['bwa_option_send_confirmation_email'] ) . '
                                        <p class="description" id="bwa_option_send_confirmation_email-description">Als deze optie is ingeschakeld, krijg je een e-mail als er via Better WishList API een nieuwe gebruiker is toegevoegd, of als er een level is toegevoegd aan een bestaande gebruiker.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="bwa_options_email_include_password">Vermeld wachtwoord in e-mail</label></th>
                                    <td>
                                        ' . bwa_get_yesno_select( "bwa_options_email_include_password", "bwa_options_email_include_password", $options['bwa_options_email_include_password'] ) . '
                                        <p class="description" id="bwa_options_email_include_password-description">Als deze optie is ingeschakeld, wordt in de bevestigingsmail ook het ingestelde wachtwoord vermeld bij een nieuwe gebruiker.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="bwa_option_destination_email">E-mail adres</label></th>
                                    <td>
                                        <input type="text" id="bwa_option_destination_email" name="bwa_option_destination_email" value="' . $options['bwa_option_destination_email'] . '" size="100"/]<br/>
                                        <p class="description" id="bwa_option_destination_email-description">Op welk e-mailadres wil je de bevestigingsmails ontvangen? Als je wilt dat de bevestigingen naar meerdere e-mailadressen gestuurd worden, zet ze dan achter elkaar, geschieden door een puntkomma (;)</p>
                                    </td>
                                </tr>
                            </tbody>

                        </table>';

	                @submit_button();

	                echo '</form>';

                } elseif ($active_tab == "connect") {
	                echo '
                    ' . $error_simple_wlm_api_active . $error_wlm_not_active . '
                    <h2>Better WishList API - Koppelingen</h2>
                    <p>Better WishList API geeft je mogelijkheden om beter te koppelen met diverse systemen, zoals Autorespond en Active Campaign. Dit koppelen vindt niet hier (op je website) plaats, maar bij de externe systemen.</p>
                    <p>Twee voorbeelden van hoe je deze koppeling maakt, vind je hieronder: Autorespond en ActiveCampaign</p>
                    <p>&nbsp;</p>
                    <h3>Autorespond</h3>
                    <h2>Toevoegen van een level</h2>
                    <p>Welk level wil je koppelen?</p>
                    ';
                    echo '
	                <form action="'.basename($_SERVER['REQUEST_URI']).'" method="get">
	                	<input type="hidden" name="page" value="bwa_admin_page">
	                	<input type="hidden" name="tab" value="connect">
	                	<select id="level_ar" name="level_ar">
                    		<option value="-1">Kies level</option>';
                    	foreach (bwa_get_wishlist_levels() as $level) {
                    		$selected = ($_GET['level_ar'] == $level['id']) ? " selected": "";

                    	    echo '<option value="'.$level['id'].'"'.$selected.'>'.$level['name']."</option>";
	                    }
                    echo '
						</select>';
                    echo '
						<input type="submit" value="Geef instructies" name="submit">
                    </form>                    
                    ';

                    if ($_GET["level_ar"] > 0) {
	                    global $WishListMemberInstance;
	                    if ( isset( $WishListMemberInstance ) ) {
		                    $wlm_api_key = $WishListMemberInstance->GetOption( 'WLMAPIKey' );
	                    }

	                    echo '
                    	<ol>
                    		<li>Maak een lijstmanager.</li>
                    		<li>Ga naar het laatste tabblad: Extra.</li>
                    		<li>Vul bij "Extern Systeem" de volgende waarde in: <strong>WishListSimpleApiAction</strong></li>
                    		<li>Vul bij "Configuratie-instellingen" de volgende waardes in:
                         <div class="bwa-code-example">
                    		<pre>cmd=add<br/>level=' . $_GET['level_ar'] . '<br/>email={categorie van je bevestigingsmail}<br/>url=' . get_site_url() . '<br/>key=' . $wlm_api_key . '</pre>
                    	</div></li>
                         </ol>
                    	';
                    }
                    
                    echo '
                    <h2>Verwijderen van een level</h2>
                    <p>Welk level wil je verwijderen?</p>
                    ';
                    echo '
	                <form action="'.basename($_SERVER['REQUEST_URI']).'" method="get">
	                	<input type="hidden" name="page" value="bwa_admin_page">
	                	<input type="hidden" name="tab" value="connect">
	                	<select id="level_ar_remove" name="level_ar_remove">
                    		<option value="-1">Kies level</option>';
                    	foreach (bwa_get_wishlist_levels() as $level) {
                    		$selected = ($_GET['level_ar_remove'] == $level['id']) ? " selected": "";

                    	    echo '<option value="'.$level['id'].'"'.$selected.'>'.$level['name']."</option>";
	                    }
                    echo '
						</select>';
                    echo '
						<input type="submit" value="Geef instructies" name="submit">
                    </form>                    
                    ';
                    if ($_GET["level_ar_remove"] > 0) {
	                    global $WishListMemberInstance;
	                    if ( isset( $WishListMemberInstance ) ) {
		                    $wlm_api_key = $WishListMemberInstance->GetOption( 'WLMAPIKey' );
	                    }

	                    echo '
                    	<ol>
                    		<li>Maak een lijstmanager.</li>
                    		<li>Ga naar het laatste tabblad: Extra.</li>
                    		<li>Vul bij "Extern Systeem" de volgende waarde in: <strong>WishListSimpleApiAction</strong></li>
                    		<li>Vul bij "Configuratie-instellingen" de volgende waardes in:
                         <div class="bwa-code-example">
                            <pre>removeFromLevel=' . $_GET['level_ar_remove'] . '<br/>url=' . get_site_url() . '<br/>key=' . $wlm_api_key . '</pre>
                    	</div></li>
                         </ol>
                    	';
                    }

                    echo '
                    <h2>Annuleren van een level (Cancel)</h2>
                    <p>Welk level wil je annuleren?</p>
                    ';
                    echo '
	                <form action="'.basename($_SERVER['REQUEST_URI']).'" method="get">
	                	<input type="hidden" name="page" value="bwa_admin_page">
	                	<input type="hidden" name="tab" value="connect">
	                	<select id="level_ar_cancel" name="level_ar_cancel">
                    		<option value="-1">Kies level</option>';
                    	foreach (bwa_get_wishlist_levels() as $level) {
                    		$selected = ($_GET['level_ar_cancel'] == $level['id']) ? " selected": "";

                    	    echo '<option value="'.$level['id'].'"'.$selected.'>'.$level['name']."</option>";
	                    }
                    echo '
						</select>';
                    echo '
						<input type="submit" value="Geef instructies" name="submit">
                    </form>                    
                    ';
                    if ($_GET["level_ar_cancel"] > 0) {
	                    global $WishListMemberInstance;
	                    if ( isset( $WishListMemberInstance ) ) {
		                    $wlm_api_key = $WishListMemberInstance->GetOption( 'WLMAPIKey' );
	                    }

	                    echo '
                    	<ol>
                    		<li>Maak een lijstmanager.</li>
                    		<li>Ga naar het laatste tabblad: Extra.</li>
                    		<li>Vul bij "Extern Systeem" de volgende waarde in: <strong>WishListSimpleApiAction</strong></li>
                    		<li>Vul bij "Configuratie-instellingen" de volgende waardes in:
                         <div class="bwa-code-example">
                            <pre>cancelFromLevel=' . $_GET['level_ar_cancel'] . '<br/>url=' . get_site_url() . '<br/>key=' . $wlm_api_key . '</pre>
                    	</div></li>
                         </ol>
                    	';
                    }

                    echo '
                    <h2>Herstellen van een level (Uncancel)</h2>
                    <p>Welk level wil je herstellen?</p>
                    ';
                    echo '
	                <form action="'.basename($_SERVER['REQUEST_URI']).'" method="get">
	                	<input type="hidden" name="page" value="bwa_admin_page">
	                	<input type="hidden" name="tab" value="connect">
	                	<select id="level_ar_uncancel" name="level_ar_uncancel">
                    		<option value="-1">Kies level</option>';
                    	foreach (bwa_get_wishlist_levels() as $level) {
                    		$selected = ($_GET['level_ar_uncancel'] == $level['id']) ? " selected": "";

                    	    echo '<option value="'.$level['id'].'"'.$selected.'>'.$level['name']."</option>";
	                    }
                    echo '
						</select>';
                    echo '
						<input type="submit" value="Geef instructies" name="submit">
                    </form>                    
                    ';
                    if ($_GET["level_ar_uncancel"] > 0) {
	                    global $WishListMemberInstance;
	                    if ( isset( $WishListMemberInstance ) ) {
		                    $wlm_api_key = $WishListMemberInstance->GetOption( 'WLMAPIKey' );
	                    }

	                    echo '
                    	<ol>
                    		<li>Maak een lijstmanager.</li>
                    		<li>Ga naar het laatste tabblad: Extra.</li>
                    		<li>Vul bij "Extern Systeem" de volgende waarde in: <strong>WishListSimpleApiAction</strong></li>
                    		<li>Vul bij "Configuratie-instellingen" de volgende waardes in:
                         <div class="bwa-code-example">
                    		<pre>uncancelFromLevel=' . $_GET['level_ar_uncancel'] . '<br/>url=' . get_site_url() . '<br/>key=' . $wlm_api_key . '</pre>
                    	</div></li>
                         </ol>
                    	';
                    }

                    echo '<p>&nbsp;</p><h3>ActiveCampaign</h3>
					<h2>Toevoegen van een level</h2>
	                <form action="'.basename($_SERVER['REQUEST_URI']).'" method="get">
	                	<input type="hidden" name="page" value="bwa_admin_page">
	                	<input type="hidden" name="tab" value="connect">
	                	<select id="level_ac" name="level_ac">
                    		<option value="-1">Kies een level</option>';
                    	foreach (bwa_get_wishlist_levels() as $level) {
                    		$selected = ($_GET['level_ac'] == $level['id']) ? " selected": "";

                    	    echo '<option value="'.$level['id'].'"'.$selected.'>'.$level['name']."</option>";
	                    }
                    echo '</select>';

                    if ($_GET['password_ac'] == 1) {
                    	$selected1 = " selected";
                    	$wachtwoord_methode = "%FIRSTNAME%%LASTNAME%";
                    }
	                if ($_GET['password_ac'] == 2) {
                    	$selected2 = " selected";
                    	$wachtwoord_methode = "%FIRSTNAME%%CONTACTID%";

	                }

	                echo '
						<select id="password_ac" name="password_ac">
                    		<option value="-1">Kies een wachtwoord-optie</option>
                    		<option value="2"'.$selected2.'>Voornaam-ContactID (aanbevolen)</option>
                    		<option value="1"'.$selected1.'>Voornaam-Achternaam</option>
                    	</select>';
                    echo '
						<input type="submit" value="Geef instructies" name="submit">
                    </form>
                      ';
	                if ($_GET["level_ac"] > 0 && $_GET['password_ac'] > 0) {
		                global $WishListMemberInstance;
		                if ( isset( $WishListMemberInstance ) ) {
			                $wlm_api_key = $WishListMemberInstance->GetOption( 'WLMAPIKey' );
		                }

		                if ($_GET['password_ac'] == 1) $password_ac = "%FIRSTNAME%%LASTNAME%";
		                if ($_GET['password_ac'] == 2) $password_ac = "%FIRSTNAME%%CONTACTID%";

		                echo '
                    	<ol>
                    		<li>Voeg in een automation een "Webhook" toe.</li>
                    		<li>Vul onderstaande url in:
                        <div class="bwa-code-example">
                    		<pre>' . get_site_url() . '/?wlmsimpleapi=' . $wlm_api_key . '&wlmsimpleapi_method=add_new_member&username=%EMAIL%&useremail=%EMAIL%&userpass=' . $password_ac . '&levelid=' . $_GET['level_ac'] . '&fname=%FIRSTNAME%&lname=%LASTNAME%</pre>
                    	</div></li>     
                    		<li>Stuur in een volgende stap een e-mail aan je gebruiker met de gebruikersnaam (%EMAIL%) en het wachtwoord ('.$wachtwoord_methode.')</li>               		
                         </ol>
                    	';
	                }
                    echo '
					<h2>Verwijderen van een level</h2>
	                <form action="'.basename($_SERVER['REQUEST_URI']).'" method="get">
	                	<input type="hidden" name="page" value="bwa_admin_page">
	                	<input type="hidden" name="tab" value="connect">
	                	<select id="level_ac" name="level_ac_remove">
                    		<option value="-1">Kies een level</option>';
                    	foreach (bwa_get_wishlist_levels() as $level) {
                    		$selected = ($_GET['level_ac_remove'] == $level['id']) ? " selected": "";

                    	    echo '<option value="'.$level['id'].'"'.$selected.'>'.$level['name']."</option>";
	                    }
                    echo '</select>
						  <input type="submit" value="Geef instructies" name="submit">
                    </form>';
					if ($_GET["level_ac_remove"] > 0) {
						global $WishListMemberInstance;
						if ( isset( $WishListMemberInstance ) ) {
							$wlm_api_key = $WishListMemberInstance->GetOption( 'WLMAPIKey' );
						}

						echo '
	                        <ol>
	                            <li>Voeg in een automation een "Webhook" toe.</li>
	                            <li>Vul onderstaande url in:
	                        <div class="bwa-code-example">
	                            <pre>' . get_site_url() . '/?wlmsimpleapi=' . $wlm_api_key . '&wlmsimpleapi_method=remove_member_from_level&useremail=%EMAIL%&levelid=' . $_GET['level_ac_remove'] . '</pre>
	                        </div></li>                   		
	                         </ol>
	                        ';
                    }
                    echo '
					<h2>Annuleren van een level (Cancel)</h2>
	                <form action="'.basename($_SERVER['REQUEST_URI']).'" method="get">
	                	<input type="hidden" name="page" value="bwa_admin_page">
	                	<input type="hidden" name="tab" value="connect">
	                	<select id="level_ac_cancel" name="level_ac_cancel">
                    		<option value="-1">Kies een level</option>';
                    	foreach (bwa_get_wishlist_levels() as $level) {
                    		$selected = ($_GET['level_ac_cancel'] == $level['id']) ? " selected": "";

                    	    echo '<option value="'.$level['id'].'"'.$selected.'>'.$level['name']."</option>";
	                    }
                    echo '</select>
						  <input type="submit" value="Geef instructies" name="submit">
                    </form>';
					if ($_GET["level_ac_cancel"] > 0) {
						global $WishListMemberInstance;
						if ( isset( $WishListMemberInstance ) ) {
							$wlm_api_key = $WishListMemberInstance->GetOption( 'WLMAPIKey' );
						}

						echo '
	                        <ol>
	                            <li>Voeg in een automation een "Webhook" toe.</li>
	                            <li>Vul onderstaande url in:
	                        <div class="bwa-code-example">
	                            <pre>' . get_site_url() . '/?wlmsimpleapi=' . $wlm_api_key . '&wlmsimpleapi_method=member_cancel_level&useremail=%EMAIL%&levelid=' . $_GET['level_ac_cancel'] . '</pre>
	                        </div></li>                   		
	                         </ol>
	                        ';
                    }
                    echo '
					<h2>Herstellen van een level (Uncancel)</h2>
	                <form action="'.basename($_SERVER['REQUEST_URI']).'" method="get">
	                	<input type="hidden" name="page" value="bwa_admin_page">
	                	<input type="hidden" name="tab" value="connect">
	                	<select id="level_ac_uncancel" name="level_ac_uncancel">
                    		<option value="-1">Kies een level</option>';
                    	foreach (bwa_get_wishlist_levels() as $level) {
                    		$selected = ($_GET['level_ac_uncancel'] == $level['id']) ? " selected": "";

                    	    echo '<option value="'.$level['id'].'"'.$selected.'>'.$level['name']."</option>";
	                    }
                    echo '</select>
						  <input type="submit" value="Geef instructies" name="submit">
                    </form>';
					if ($_GET["level_ac_uncancel"] > 0) {
						global $WishListMemberInstance;
						if ( isset( $WishListMemberInstance ) ) {
							$wlm_api_key = $WishListMemberInstance->GetOption( 'WLMAPIKey' );
						}

						echo '
	                        <ol>
	                            <li>Voeg in een automation een "Webhook" toe.</li>
	                            <li>Vul onderstaande url in:
	                        <div class="bwa-code-example">
	                            <pre>' . get_site_url() . '/?wlmsimpleapi=' . $wlm_api_key . '&wlmsimpleapi_method=member_uncancel_level&useremail=%EMAIL%&levelid=' . $_GET['level_ac_uncancel'] . '</pre>
	                        </div></li>                   		
	                         </ol>
	                        ';
					}                                        
                } else {
                    echo '
                    ' . $error_simple_wlm_api_active . $error_wlm_not_active . '
                    <h2>Better WishList API Log</h2>
                    <p>';
                    bwa_show_log();
                    echo "</p>";
                }

                echo '
                </div>
                <div id="sidebar" class="wrapper-cell">
                    <div class="sidebar_box info_box">
                        <h3>Plugin Info</h3>
                        <div class="inside">
                            <a href="http://bureauram.nl" target="_blank"><img src="'. plugins_url( 'img/ram_200x160.png', __FILE__ ) .'" width="200" /></a>
                            <ul>
                                <li>Naam: Better WishList API</li>
                                <li>Auteur: Rick Heijster @ Bureau RAM</li>
                                <li>Website: <a href="http://bureauram.nl" target="_blank">Bureau RAM</a></li>
                            </ul>
                            <p>Deze plugin wordt je gratis aangeboden door Bureau RAM.</p>
                            <p>Bureau RAM is specialist op het gebied van ledenwebsites met behulp van Wordpress en WishList Member</p>
							<p>De originele API (zonder aanpassingen en instellingenscherm) is afkomstig van Wishlist Member Products</p>
                        </div>
                    </div>
                </div>
            </div>
		</div>

	';

}


/* !9. SETTINGS */

// 9.1
// hint: registers all our plugin options
function bwa_register_options() {
	// plugin options
	register_setting('bwa_plugin_options', 'bwa_option_check_if_user_exists');
    register_setting('bwa_plugin_options', 'bwa_option_extend_if_user_exists');
	register_setting('bwa_plugin_options', 'bwa_option_send_confirmation_email');
	register_setting('bwa_plugin_options', 'bwa_option_destination_email');
	register_setting('bwa_plugin_options', 'bwa_option_update_user_data');
    register_setting('bwa_plugin_options', 'bwa_options_email_include_password');

}

/* !10. API */

function bwa_better_wlm_api () {
// This API is an improved version of the API written by ronaldo@wishlistproducts.com

	// Check if there's a simplewlmapi request
	if(isset($_REQUEST['wlmsimpleapi'])) {
        if ( function_exists('simple_wlm_api') ) {
            // We do not want to conflict with the original Simple WLM API. Disengage.
            $result['message'] = "Request received, but original plugin Simple WLM API is active. Better WishList API ignored request.";
            $result['status'] = "Error";
            bwa_log_event($_SERVER['QUERY_STRING'], $result);
            exit;
        }

        if ( !bwa_check_is_wlm_active() ) {
            // If WishList Member is not active, disengage.
            $result['message'] = "Request received, but WishList Member is not active. Better WishList API ignored request.";
            $result['status'] = "Error";
            bwa_log_event($_SERVER['QUERY_STRING'], $result);
            exit;
        }

		// Check if Wishlist Member is installed and activated using its global variable
		global $WishListMemberInstance;
		if(isset($WishListMemberInstance)) {

			header('Content-type: application/json');

			$api_key = $_REQUEST['wlmsimpleapi'];
			$wlm_api_key = $WishListMemberInstance->GetOption('WLMAPIKey');

			// Check if the passed api key matches wlm's api key
			if($api_key == $wlm_api_key) {
                $result = array();
                $bwa_options = bwa_get_current_options();

				$wlm_simple_api_method = $_REQUEST['wlmsimpleapi_method'];
				// Check if the method passed is valid
				if(in_array($wlm_simple_api_method, array('get_levels', 'get_members', 'get_level_members', 'remove_member_from_level', 'member_cancel_level', 'member_uncancel_level', 'add_new_member', 'add_existing_member'))) {

					$wlm_api_methods = new WLMAPIMethods();
					$wlm_api_methods->loadAPI();

					switch ($wlm_simple_api_method) {
						case 'get_levels':
							$data = $wlm_api_methods->get_levels();
							echo  json_encode( array( 'success' => 1, 'message' => $data ));
                            $result['message'] = "Request for levels received. List returned.";
                            $result['status'] = "Success";
							break;
						case 'get_members':
							$data = $wlm_api_methods->get_members();
							echo  json_encode( array( 'success' => 1, 'message' => $data ));
                            $result['message'] = "Request for members received. List returned.";
                            $result['status'] = "Success";
							break;
						case 'get_level_members':

							$level_id = $_REQUEST['levelid'];

							if(empty($level_id)) {
								echo  json_encode( array( 'success' => 0, 'message' => 'get_level_members method needs the level_id of the membership level' ));
                                $result['message'] = "Request for members of level received, but no level ID.";
                                $result['status'] = "Error";
							} else {
								$data = $wlm_api_methods->get_level_members($level_id);
								echo  json_encode( array( 'success' => 1, 'message' => $data ));
                                $result['message'] = "Request for members of level ".$level_id." received. List returned.";
                                $result['status'] = "Success";
							}
							break;
						case 'remove_member_from_level':

						    if (isset($_REQUEST['useremail'])) {
                                $user_email = $_REQUEST['useremail'];
                                $exists = email_exists($user_email);
                            } elseif (isset($_REQUEST['username'])) {
                                $user_email = $_REQUEST['username'];
                                $exists = email_exists($user_email);
                            } else {
                                $exists = false;
                                $user_email = "";
                            }

                            if ($exists) {
                                $level_id = $_REQUEST['levelid'];
                                $received_member_id = $exists;

                                if (empty($level_id) || empty($received_member_id)) {
                                    echo json_encode(array('success' => 0, 'message' => 'remove_existing_member method needs the level_id of the membership level and the members ID'));
                                    $result['message'] = "Request to remove member of level received, but no level ID or member ID received.";
                                    $result['status'] = "Error";
                                } else {
                                    $data = $wlm_api_methods->remove_member_from_level($level_id, $received_member_id);
                                    echo json_encode(array('success' => 1, 'message' => $data));
                                    $result['message'] = "Member " . $received_member_id . " removed from level " . $level_id;
                                    $result['status'] = "Success";
                                }
                            } elseif (!$exists && $user_email == "") {
                                echo  json_encode( array( 'success' => 0, 'message' => 'No value received for useremail of username' ));
                                $result['message'] = "Request to remove member from level received, but no value given for useremail or username.";
                                $result['status'] = "Error";
                            } else {
                                echo  json_encode( array( 'success' => 0, 'message' => 'member not found' ));
                                $result['message'] = "Request to member of level received, but member is not found.";
                                $result['status'] = "Error";
                            }

                            break;
                            case 'member_cancel_level':

                                if (isset($_REQUEST['useremail'])) {
                                    $user_email = $_REQUEST['useremail'];
                                    $exists = email_exists($user_email);
                                } elseif (isset($_REQUEST['username'])) {
                                    $user_email = $_REQUEST['username'];
                                    $exists = email_exists($user_email);
                                } else {
                                    $exists = false;
                                    $user_email = "";
                                }
    
                                if ($exists) {
                                    $level_id = $_REQUEST['levelid'];
                                    $received_member_id = $exists;
    
                                    if (empty($level_id) || empty($received_member_id)) {
                                        echo json_encode(array('success' => 0, 'message' => 'member_cancel_level method needs the level_id of the membership level and the email address of the member'));
                                        $result['message'] = "Request to cancel member of level received, but no level ID or member ID received.";
                                        $result['status'] = "Error";
                                    } else {
                                        $args_cancel = array("Cancelled" => true, "CancelDate" => time());
                                        $data = $wlm_api_methods->update_level_member_data($level_id, $received_member_id, $args_cancel);
                                        
                                        if ($data['success'] == 1) {
                                            $result_message = "Member " . $received_member_id . " cancelled from level " . $level_id;
                                            echo json_encode(array('success' => 1, 'message' => $result_message));
                                            $result['message'] = $result_message;
                                            $result['status'] = "Success";
                                        } else {
                                            $result_message = "Cancellation of Member " . $received_member_id . " from level " . $level_id . " FAILED";
                                            echo json_encode(array('success' => 1, 'message' => $result_message));
                                            $result['message'] = $result_message;
                                            $result['status'] = "Success";
                                        }                                        
                                    }
                                } elseif (!$exists && $user_email == "") {
                                    echo  json_encode( array( 'success' => 0, 'message' => 'No value received for useremail of username' ));
                                    $result['message'] = "Request to cancel member from level received, but no value given for useremail or username.";
                                    $result['status'] = "Error";
                                } else {
                                    echo  json_encode( array( 'success' => 0, 'message' => 'member not found' ));
                                    $result['message'] = "Request to cancel member from level received, but member is not found.";
                                    $result['status'] = "Error";
                                }
    
                                break;
                                case 'member_uncancel_level':

                                    if (isset($_REQUEST['useremail'])) {
                                        $user_email = $_REQUEST['useremail'];
                                        $exists = email_exists($user_email);
                                    } elseif (isset($_REQUEST['username'])) {
                                        $user_email = $_REQUEST['username'];
                                        $exists = email_exists($user_email);
                                    } else {
                                        $exists = false;
                                        $user_email = "";
                                    }
        
                                    if ($exists) {
                                        $level_id = $_REQUEST['levelid'];
                                        $received_member_id = $exists;
        
                                        if (empty($level_id) || empty($received_member_id)) {
                                            echo json_encode(array('success' => 0, 'message' => 'member_uncancel_level method needs the level_id of the membership level and the email address of the member'));
                                            $result['message'] = "Request to uncancel member of level received, but no level ID or member ID received.";
                                            $result['status'] = "Error";
                                        } else {
                                            $args_cancel = array("Cancelled" => false);
                                            $data = $wlm_api_methods->update_level_member_data($level_id, $received_member_id, $args_cancel);
                                            
                                            if ($data['success'] == 1) {
                                                $result_message = "Member " . $received_member_id . " uncancelled from level " . $level_id;
                                                echo json_encode(array('success' => 1, 'message' => $result_message));
                                                $result['message'] = $result_message;
                                                $result['status'] = "Success";
                                            } else {
                                                $result_message = "Uncancellation of Member " . $received_member_id . " from level " . $level_id . " FAILED";
                                                echo json_encode(array('success' => 1, 'message' => $result_message));
                                                $result['message'] = $result_message;
                                                $result['status'] = "Success";
                                            }                                        
                                        }
                                    } elseif (!$exists && $user_email == "") {
                                        echo  json_encode( array( 'success' => 0, 'message' => 'No value received for useremail of username' ));
                                        $result['message'] = "Request to uncancel member from level received, but no value given for useremail or username.";
                                        $result['status'] = "Error";
                                    } else {
                                        echo  json_encode( array( 'success' => 0, 'message' => 'member not found' ));
                                        $result['message'] = "Request to uncancel member from level received, but member is not found.";
                                        $result['status'] = "Error";
                                    }
        
                                    break;                                 
						case 'add_new_member':

							$user_login = $_REQUEST['username'];
							$user_email = $_REQUEST['useremail'];
							$user_pass = $_REQUEST['userpass'];
							$level_string = $_REQUEST['levelid'];
							$user_first_name = $_REQUEST['fname'];
							$user_last_name = $_REQUEST['lname'];

							if(empty($level_string) || empty($user_login) || empty($user_email) || empty($user_pass)) {
								echo  json_encode( array( 'success' => 0, 'message' => 'add_new_member  method needs the the following data: username, useremail, userpass, levelid'));
                                $result['message'] = "Request add member, but no level ID or user login or email or password received.";
                                $result['status'] = "Error";
							} else {
								$exists = email_exists($user_email);

								$array_levels = bwa_get_levels_array($level_string);
								$str_levels_id = bwa_get_levels_id_string($array_levels);
								$str_levels_name = bwa_get_levels_names_string($array_levels);

								if ( $exists && $bwa_options['bwa_option_check_if_user_exists'] ) {
                                    //User already exists. Add level to user
									$received_member_id = $exists;

									if(empty($level_string) || empty($received_member_id)) {
										echo  json_encode( array( 'success' => 0, 'message' => 'add_existing_member  method needs the level_id of the membership level and the members ID'));
									} else {

                                        if ($bwa_options['bwa_option_extend_if_user_exists']) {
                                            $args = array('Users' => $received_member_id, 'Timestamp' => time()); //Update registration date in case user already has this level
                                        } else {
                                            $args = array('Users' => $received_member_id);
                                        }
										

										foreach ($array_levels as $level) {
											$data = $wlm_api_methods->add_member_to_level($level, $args);
										}

                                        $result['message'] = "Request to add member ".$user_email.", but user already exists. Added user to level(s) ".$str_levels_name." (".$str_levels_id.") instead.";
                                        $result['status'] = "Success";

										$WishListMemberInstance->is_sequential( $received_member_id, true ); //Add option sequential

										if ($bwa_options['bwa_option_update_user_data']) {
											if (strlen($user_first_name) || strlen($user_last_name)) {
                                                $result_user_data = bwa_register_user_data($received_member_id, $user_first_name, $user_last_name);
                                                $result['message'] = $result['message']." ".$result_user_data;
                                            }
										}

                                        if ($bwa_options['bwa_option_send_confirmation_email']) {
                                            $action = "add_level";
                                            bwa_send_email_confirmation($action, $user_email, $user_pass, $str_levels_name);
                                        }

                                        if (bwa_check_is_rmt_active()) {
                                            if (get_option('rmt_option_create_login_link_existing_users') == 1 ) $result['message'] = $result['message']." ".rmt_process_existing_user_api($received_member_id, $user_pass);
                                        }

										echo  json_encode( array( 'success' => 1, 'message' => $data ));
									}
								} else {
                                    //User does not already exists. Add user
									$member_id = wp_create_user( $user_login, $user_pass, $user_email );

									$WishListMemberInstance->is_sequential( $member_id, true );

									$args = array('Users' => $member_id);

									foreach ($array_levels as $level) {
										$data = $wlm_api_methods->add_member_to_level($level, $args);
									}

                                    $result['message'] = "Request to add member ".$user_email.". Added user to level(s) ".$str_levels_name." (".$str_levels_id.").";
                                    $result['status'] = "Success";

                                    if ($bwa_options['bwa_option_update_user_data']) {
                                        if (strlen($user_first_name) || strlen($user_last_name)) {
                                            $result_user_data = bwa_register_user_data($member_id, $user_first_name, $user_last_name);
                                            $result['message'] = $result['message']." ".$result_user_data;
                                        }
                                    }

                                    if ($bwa_options['bwa_option_send_confirmation_email']) {
                                        $action = "new_user";
                                        bwa_send_email_confirmation($action, $user_email, $user_pass, $str_levels_name);
                                    }

                                    if (bwa_check_is_rmt_active()) {
                                        if (get_option('rmt_option_page_change_password') == 1 || get_option('rmt_option_create_login_link_new_users') == 1 ) {                                            
                                            $result['message'] = $result['message']." ".rmt_process_new_user_api($member_id, $user_pass);
                                        }
                                    }

									echo  json_encode( array( 'success' => 1, 'message' => $data ));
								}
							}
							break;
						case 'add_existing_member':

							$level_string = $_REQUEST['levelid'];
							$received_member_id = $_REQUEST['memberid'];
                            $user_first_name = $_REQUEST['fname'];
                            $user_last_name = $_REQUEST['lname'];
                            $user_pass = $_REQUEST['userpass'];

                            if ($bwa_options['bwa_option_extend_if_user_exists']) {
                                $args = array('Users' => $received_member_id, 'Timestamp' => time()); //Update registration date in case user already has this level
                            } else {
                                $args = array('Users' => $received_member_id);
                            }
                            
							if(empty($level_string) || empty($received_member_id)) {
								echo  json_encode( array( 'success' => 0, 'message' => 'add_existing_member  method needs the level_id of the membership level and the members ID'));
                                $result['message'] = "Request add member to level, but no level ID or member ID received.";
                                $result['status'] = "Error";
							} else {
								$array_levels = bwa_get_levels_array($level_string);
								$str_levels_id = bwa_get_levels_id_string($array_levels);
								$str_levels_name = bwa_get_levels_names_string($array_levels);

								foreach ($array_levels as $level) {
									$data = $wlm_api_methods->add_member_to_level($level, $args);
								}

                                $user_info = get_userdata($received_member_id);

                                $result['message'] = "Added user ".$user_info->user_email." to level(s) ".$str_levels_name." (".$str_levels_id.").";
                                $result['status'] = "Success";

                                if ($bwa_options['bwa_option_send_confirmation_email']) {
                                    $action = "add_level";
                                    bwa_send_email_confirmation($action, $user_info->user_email, "***", $str_levels_name);
                                }

                                if ($bwa_options['bwa_option_update_user_data']) {
                                    if (strlen($user_first_name) || strlen($user_last_name)) {
                                        $result_user_data = bwa_register_user_data($received_member_id, $user_first_name, $user_last_name);
                                        $result['message'] = $result['message']." ".$result_user_data;
                                    }
                                }

                                if (bwa_check_is_rmt_active()) {
                                    if (get_option('rmt_option_create_login_link_existing_users') == 1 ) $result['message'] = $result['message']." ".rmt_process_existing_user_api($received_member_id, $user_pass );
                                }

								echo  json_encode( array( 'success' => 1, 'message' => $data ));
							}
							break;
					}
				} else {
					echo  json_encode( array( 'success' => 0, 'message' => 'Wrong Method, supported methods are get_levels, get_members, add_new_member, add_existing_member' ));
                    $result['message'] = "Wrong Method, supported methods are get_levels, get_members, add_new_member, add_existing_member";
                    $result['status'] = "Error";
				}


			} else {
				echo  json_encode( array( 'success' => 0, 'message' => 'Wrong API Key' ));
                $result['message'] = "Wrong API Key";
                $result['status'] = "Error";
			}

            bwa_log_event(serialize($_REQUEST), $result);

			exit;
		} else {
            $result['message'] = "Request received, but WishListMemberInstance could not be set. Better WishList API ignored request.";
            $result['status'] = "Error";
            bwa_log_event(serialize($_REQUEST), $result);
        }
	}

}
