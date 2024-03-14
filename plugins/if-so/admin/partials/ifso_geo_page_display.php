<?php
	if ( ! defined( 'ABSPATH' ) ) exit;

	require_once(IFSO_PLUGIN_BASE_DIR . 'services/geolocation-service/geolocation-service.class.php');

	use IfSo\Services\GeolocationService;

    global $wpdb;
	$license = get_option( 'edd_ifso_geo_license_key' );
	$status  = get_option( 'edd_ifso_geo_license_status' );
	$expires = get_option( 'edd_ifso_geo_license_expires' );
	// $item_name = get_option( 'edd_ifso_license_item_name' );

	function is_license_valid($status) {
		return ( $status !== false && $status == 'valid' );
	}

	function is_plusgeo_license_exist($geoData) {
		return ( isset($geoData['has_plusgeo_key']) && $geoData['has_plusgeo_key'] == true );
	}

	function is_pro_license_exist($geoData) {
		return ( isset($geoData['has_pro_key']) && $geoData['has_pro_key'] == true );
	}

	function get_subscription($geoData) {
		$subscription = '';

		if ( is_pro_license_exist($geoData) )
			$subscription = "Pro";
		else
			$subscription = "Free";

		if ( is_plusgeo_license_exist($geoData) )
			$subscription .= " +Geolocation";

		return $subscription;
	}

	function is_geo_data_valid($geoData) {
		return ( isset($geoData['success']) && $geoData['success'] == true );
	}

	function get_queries_left($geoData) {	//This actually shows the USED queries(not the ones left)
		if ( is_geo_data_valid($geoData) ) {
			return intval($geoData['realizations']);
		}
		return 0;
	}

	function get_monthly_queries($geoData) {
		if ( is_geo_data_valid($geoData) ) {
			return $geoData['bank'];
		}
		return 0;
	}

	function get_key($geoData, $key) {
		if ( isset( $geoData[$key] ) )
			return $geoData[$key];
		else
			return false;
	}

	function get_date_i18n($date,$shorten_month=false) {
        $month_format = $shorten_month ? 'M' : 'F';
		return date_i18n( "{$month_format} j, Y", strtotime( $date, current_time( 'timestamp' ) ) );
	}

	function get_pro_purchase_date($geoData) {
		return get_key($geoData, 'pro_purchase_date');
	}

	function get_pro_renewal_date($geoData) {
		return get_key($geoData, 'pro_renewal_date');
	}

	function get_plusgeo_purchase_date($geoData) {
		return get_key($geoData, 'plusgeo_purchase_date');
	}

	function get_plusgeo_renewal_date($geoData) {
		return get_key($geoData, 'plusgeo_renewal_date');
	}

	function get_pro_and_geo_realizations($geoData){
		$ret = ['pro' => 0, 'geo' => 0,];
		if(isset($geoData['geo_realizations']))
			$ret['geo'] = number_format(intval($geoData['geo_realizations']));
		if(isset($geoData['pro_realizations']))
			$ret['pro'] = number_format(intval($geoData['pro_realizations']));
		return $ret;
	}

    function print_extra_tabs_buttons($tabs){
        $ret = '';
        if(!empty($tabs)){
            foreach($tabs as $tab){
                if(!empty($tab['name'])){
                    $prettyname = !empty($tab['prettyname']) ? $tab['prettyname'] : $tab['name'];
                    $ret .= "<li class='ifso-tab' data-tab='ifso-geo-page-tab-{$tab['name']}'>{$prettyname}</li>";
                }
            }
        }
        return $ret;
    }

    function print_extra_tabs_contents($tabs){
        $ret = '';
        if(!empty($tabs)){
            foreach($tabs as $tab){
                if(!empty($tab['name']) && !empty($tab['content']))
                    $ret .=  "<div class='ifso-geo-page-tab-content ifso-geo-page-tab-{$tab['name']}'>{$tab['content']}</div>";
            }
        }
        return $ret;
    }

	$geoData = GeolocationService\GeolocationService::get_instance()->get_status($license);
	$geo_subscription = get_subscription($geoData, $license, $status);
	$geo_monthly_queries = number_format(get_monthly_queries($geoData));
	$geo_int_monthly_queries = get_monthly_queries($geoData);
	$geo_queries_left = number_format(get_queries_left($geoData));
	$geo_queries_left_send = get_queries_left($geoData);
	$geo_pro_purchase_date = get_pro_purchase_date($geoData);
	$separateRealizations = get_pro_and_geo_realizations($geoData);
	$pro_license_type = (isset($geoData['pro_license_type']) && !empty($geoData['pro_license_type'])) ? $geoData['pro_license_type'] : false;
	$geo_license_type = (isset($geoData['geo_license_type']) && !empty($geoData['geo_license_type'])) ? $geoData['geo_license_type'] : false;
	$pro_license_bank = (isset($geoData['product_bank']) && !empty($geoData['product_bank'])) ? number_format($geoData['product_bank']) : 0;
	$geo_license_bank = (isset($geoData['geo_bank']) && !empty($geoData['geo_bank'])) ? number_format($geoData['geo_bank']) : 0;
    $extra_tabs = apply_filters('ifso_geo_page_display_extra_tabs',[]);

	$sent_user_email = (isset($_POST["user-email-box"]) && !empty($_POST["user-email-box"]) &&  $_POST["user-email-box"] != get_option('admin_email')) ? sanitize_email($_POST["user-email-box"]) : get_option('admin_email');
	$set_alert_values = (isset($_POST["alert-checkbox-value"]) ? $_POST["alert-checkbox-value"]  : '') . " " . (isset($_POST["alert-checkbox-value-3"]) ? $_POST["alert-checkbox-value-3"] : '') . " " . (isset($_POST["alert-checkbox-value-2"]) ? $_POST["alert-checkbox-value-2"] : '') . " " . (isset($_POST["alert-checkbox-value-1"]) ? $_POST["alert-checkbox-value-1"] : '');

    function get_notification_data() {
        global $wpdb, $sent_user_email;
        $table = $wpdb->prefix . 'ifso_local_user';
        $user_notification_data = $wpdb->get_results( "SELECT * FROM {$table}"); //add another on reload
        if (count($user_notification_data) > 0) {
            $notifications_data_arr["user_email"] = (isset($user_notification_data[0]->user_email) && !empty($user_notification_data[0]->user_email)) ? $user_notification_data[0]->user_email : $sent_user_email;
            $notifications_data_arr["alert_values"] = isset($user_notification_data[0]->alert_values) ? $user_notification_data[0]->alert_values : '';
            $notifications_data_arr["record_id"] = isset($user_notification_data[0]->id) ? $user_notification_data[0]->id : 0;
            return $notifications_data_arr;
        }
    }

	$data = get_notification_data();
	$form_alert_values = explode(" ",$data["alert_values"]);

	$table = $wpdb->prefix . 'ifso_local_user';
    $daily_sessions_table_name = $wpdb->prefix . 'ifso_daily_sessions';

	if(isset($_POST['update_notifications'])) {
        $wpdb->query($wpdb->prepare("REPLACE INTO {$table} SET id = %d,user_email = %s,user_sessions = %d,user_bank = %d,alert_values = %s",
            [$data['record_id'],$sent_user_email,$geo_queries_left_send,$geo_int_monthly_queries,$set_alert_values]));
        $form_alert_values = explode(" ",get_notification_data()["alert_values"]);
    }

    $noLicenseMessageBox = '<div class="no_license_message">'. __("Enter a Geolocation License to gain extra sessions. ", 'if-so') . '<a href="https://www.if-so.com/plans/geolocation-plans/?ifso=geocredits" target="_blank">'. __("Click here to get a Geolocation license key", 'if-so') . '</a>.</div>';
?>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- TEMPORARY inner css! -->
<style>
    /* old table style - for the table that still remains in the geo tab - START */
    #customers {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        text-align: center;
        font-size: 14px;
    }
    #outer-div {
        width: 50%;
        /* box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); */
    }
    .outer-table {
        /*
        border: 1px solid #cccccc;
        border-bottom: 0;*/
    }
    #upper-table {
        width: 100%;
        text-align: center;
        background-color: #e5e5e5;
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        font-size: 14px;
        color: white;
    }
    .outer-table th{
        width: 50%;
        color: #333;
        padding: 10px 0;
    }
    .outer-table th:first-child{
        border-right: 1px dashed #ccc;
    }
    #customers {
      /* border-style: hidden; */
    }
    #customers td, #customers th {
        padding: 8px;
        width: 50%;
        border: 1px solid #ddd;
    }
    #customers p:nth-child(1){
        padding: 10px 0;
        color: #5787f9;
        font-size: 16px;
        font-weight:bold;
    }
    #customers p:nth-child(2){
        color: #a9a8a8;
        padding: 0 60px;
    }
    .inner-table {
        width:100%;
        /* overflow-y:scroll; */
    }
    #customers tr {
      background-color: white;
    }
    /* old table style - for the table that still remains in the geo tab - END */

    .percentage {
      font-weight:700;
    }

    /*new info tab styles -start*/
    .geo-dki-description {
        font-size: 16px;
        margin: 0 auto 15px;
    }

    .geo-dki-table,
    .geo-dki-table th,
    .geo-dki-table td {
        border: solid 1px black;
        border-collapse: collapse;
        padding: 14px 10px;
        font-size: 16px;
    }
    .geo-dki-table td:nth-child(2) {
        position: relative;
        padding-right: 30px;
    }

    .geo-dki-table-copy-button {
        position: absolute;
        top: 0;
        right: 0;
        font-size: 15px;
        padding: 2px 6px 6px;
        line-height: 1;
        color: black;
        background-color: #E0E0E0;
        margin: 0 0 0 5px;
        cursor: pointer;
    }
    .geo-dki-table-copy-button:hover {
        background-color: #D5D5D5;
    }
    /* click indicator start */
        .geo-dki-table-copy-button::before {
            content: "";
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: -13px;
            width: 0;
            height: 0;
            border-top: 4px solid transparent;
            border-bottom: 4px solid transparent;
            border-left: 8px solid #888888;
        }
        .geo-dki-table-copy-button::after {
            content: "Copied!";
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: calc(100% + 13px);
            background-color: #888888;
            color: white;
            font-size: 12px;
            padding: 2px 4px;
            border-radius: 3px;
            white-space: nowrap;
        }
        .geo-dki-table-copy-button::before,
        .geo-dki-table-copy-button::after {
            opacity: 0;
            visibility: hidden;
            transition: visibility 0.1s linear, opacity 0.1s linear;
        }
        .geo-dki-table-copy-button.active::before,
        .geo-dki-table-copy-button.active::after {
            visibility: visible;
            opacity: 1;
        }
    /* click indicator end */

    .geo-dki-table td a {
        text-decoration: none;
    }

    .geo-dki-table code {
        font-size: inherit;
    }

    .geo-dki-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
        color: white !important; /* to override wp on hover and active change */
        background-color: #4cb5d2;
        font-weight: 600;
        padding: 10px 22px 10px 19px;
        border-radius: 40px;
        border: none;
        outline: 0;
        margin: 40px auto 0;
    }
    .geo-dki-button:hover,
    .geo-dki-button:active {
        background-color: #48ACC7;
    }

    .geo-info {
        max-width: 974px;
    }

    .ifso-geo-page-tab-info .ifso-settings_paragraph{
        max-width: unset;
        font-size: 16px;
        margin-bottom: 2em;
    }

    .geo-info-download {
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 40px;
        border: groove 1px #697BF8;
        margin: 30px auto 20px;
    }
    .geo-info-download p {
        color: #697BF8;
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }
    .geo-info-download ul {
        color: #697BF8;
        list-style-type: none;
        margin: 6px auto 0;
    }
    .geo-info-download li {
        display: inline-block;
        font-size: 18px;
        line-height: 1.6;
        margin: 0 10px 0 0;
    }
    .geo-info-download li::before {
        content: '';
        display: inline-block;
        width: 11px;
        height: 11px;
        background-image: url("data:image/svg+xml, %3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512' fill='%23697BF8'%3E%3Cpath d='M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z'/%3E%3C/svg%3E");
        margin-right: 6px;
    }
    .geo-info-download a {
        flex-shrink: 0;
        color: white;
        background-color: #697BF8;
        padding: 6px 16px 8px 22px;
        font-size: 18px;
        text-decoration: none;
    }

    .geo-info-notification {
        margin: 10px auto 0;
    }
    .geo-info-notification svg {
        position: relative;
        top: 2px;
        display: inline-block;
        width: 9px;
        color: #319D56;
        fill: currentColor;
        padding: 3px;
        border: solid 1px currentColor;
        border-radius: 50%;
        margin-right: 1px;
    }
    .geo-info-notification p {
        display: inline;
        font-size: 16px;
        margin: 0 auto;
    }

    .geo-info-cards {
        display: flex;
        align-items: stretch;
        justify-content: baseline;
        flex-wrap: wrap;
        gap: 11px;
        margin: 30px 0 0;
    }
    .geo-info-cards .geo-info-card {
        box-sizing: border-box;
        width: 235px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: baseline;
        gap: 30px;
        text-align: center;
        background-color: white;
        padding: 14px 14px;
        border: solid 1px #d1d1d1;
    }
    .geo-info-cards .geo-info-card .geo-info-card-title{
        margin: 0 auto;
        font-size: 16px;
    }
    .geo-info-cards .geo-info-card .geo-info-card-content{
        margin: 0 auto;
        font-size: 22px;
        font-weight: 900;
    }
    .geo-info-cards .geo-info-card.your-subscription .geo-info-card-content {
        font-weight: initial;
        font-size: 13px;

        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0px;
    }
    .geo-info-cards .geo-info-card.your-subscription .geo-info-card-content .subsctiption-name{
        font-weight: 700;
    }
    .geo-info-cards .geo-info-card-content.error-label {
        color: red;
    }
    .geo-info-cards .geo-info-card-link.error-label {
        color: red;
        margin-top: -30px;
    }

    .geo-info-cards .geo-info-card-link{
        font-size:14px;
    }
    /*new info tab styles - end*/

    /* new info filter styles - start */
        .geo-info-filter {
            margin: 50px 0 0;
        }
        .geo-info-filter.disabled {
            display: none;
            visibility: none;
        }
        .geo-info-filter-container {
            position: relative;
            display: inline-block;
        }
        .geo-info-filter-container::before {
            content: "\f073";
            color: #555d66;
            font-family: FontAwesome;
            font-size: 16px;
            position: absolute;
            top: 5px;
            left: 9px;
        }
        .geo-info-filter-container .geo-info-filter-picker {
            display: inline-block;
            padding: 5px 0 5px 30px;
            float: none;
            line-height: 1;
            min-height: unset;
            width: 115px;
            margin: 0px;
        }
        .geo-info-filter .geo-info-filter-submit {
            margin-left: 5px;
        }
    /* new info filter styles - end */

    /* new info chart styles - start */
    .geo-info-chart-container {
        margin: 15px 0 0;
        position: relative;
        width: 100%;
        height: 250px;
    }
    .geo-info-chart-container.disabled {
        display: none;
        visibility: none;
    }
    /* new info chart styles - end */

    /* new info table styles - start */
    .geo-info-table-wrapper{
        margin: 50px 0 0;
    }
    .geo-info-table {
        width: 100%;
        text-align: left;
        font-size: 14px;
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-spacing: 1px;
        border-collapse: collapse;
    }
    .geo-info-table thead {
        background-color: white;
    }
    .geo-info-table tbody {
        background-color: white;
    }
    .geo-info-table tbody tr:nth-child(odd) {
        background-color: #F6F7F7;
    }
    .geo-info-table td, .geo-info-table th {
        padding: 10px 20px;
        width: 25%;
        border: 1px solid #ddd;
    }
    .geo-info-table th {
        padding: 20px;
        font-weight: normal;
    }
    .geo-info-table .empty-table-notifier p {
        margin: 5px auto 5px;
    }
    .geo-info-table td.empty-table-notifier{
        text-align:center;
    }
    /* new info table styles - end */

    /* new mobile media query - start */
    @media (max-width: 782px) {
        .geo-info-cards {
            justify-content: center;
        }
        .geo-info-cards .geo-info-card {
            width: 100%;
            gap: 14px;
        }
        .geo-info-cards .geo-info-card-link.error-label {
            margin-top: -10px;
        }
        .geo-info-download {
            text-align: center;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 25px;
        }
 
        .geo-info-filter {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .geo-info-chart-container {
            margin: 15px auto 0;
        }

        .geo-info-table {
            text-align: center;
            margin: 50px auto 0;
        }
    }
    /* new mobile media query - end */
</style>
</head>
<body>
    <div class="wrap">
        <h2> <?php _e('If-So Dynamic Content | Geolocation'); ?> </h2>
        <div class="ifso-geo-tabs-select-wrapper">
            <ul class="ifso-license-tabs-header">
                <li class="ifso-tab default-tab" data-tab="ifso-geo-page-tab-info">	 <?php _e('Info', 'if-so');?></li>
                <li class="ifso-tab" data-tab="ifso-geo-page-tab-notifications"><?php _e('Notifications', 'if-so'); ?></li>
                <li class="ifso-tab" data-tab="ifso-geo-page-tab-dki"><?php _e('DKI Shortcodes', 'if-so'); ?></li>
                <?php echo wp_kses_post(print_extra_tabs_buttons($extra_tabs)); ?>
            </ul>
        </div>
        <!-- new -->
        <div class="ifso-geo-tabs-contents-wrapper">
            <!-- dki tab contents: -->
            <script> 
                // script to copy the shortcode when clicking on the copy buttons
                document.addEventListener('DOMContentLoaded', () => {
                    let buttons = document.querySelectorAll('.geo-dki-table-copy-button')
                    buttons.forEach((button) => {
                        let code = button.parentElement.querySelector('code')
                        button.addEventListener('click', () => copyClickHandler(button, code))
                    })
                })
                function copyClickHandler(button, code) {
                    button.classList.add('active')
                    setTimeout(() => { button.classList.remove('active') }, 2000)
                    navigator.clipboard.writeText(code.innerHTML)
                }
            </script>
            <div class="ifso-geo-page-tab-content ifso-geo-page-tab-dki">
                <p class="geo-dki-description">
                    Save time with Dynamic Keyword Insertion (DKI) shortcodes! Automatically display personalized content instead of setting up multiple versions of dynamic content manually.
                    <a href="https://www.if-so.com/help/documentation/dynamic-keyword-insertion/?utm_source=Plugin&utm_medium=Help&utm_campaign=PluginsPage&utm_term=dki" target="_blank">
                        More available DKI shortcodes
                    </a>
                </p>

                <table class="geo-dki-table">
                    <thead>
                        <tr>
                            <th>Shortcode Name</th>
                            <th>The Shortcode</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Country Name</td>
                            <td>
                                <code>[ifsoDKI type='geo' show='country' fallback='' ajax='yes']</code><div class="geo-dki-table-copy-button">🗊</div></td>
                            <td>Display the user’s country name</td>
                        </tr>
                        <tr>
                            <td>User's Country Flag</td>
                            <td><code>[ifsoDKI type='geo' show='flag' width='50px' ajax='yes']</code><div class="geo-dki-table-copy-button">🗊</div></td>
                            <td>
                                Insert the user’s country flag
                                &#40;<a href="https://www.if-so.com/display-the-users-country-flag/?utm_source=Plugin&utm_medium=dki-table&utm_campaign=geolocation&utm_term=info-top&utm_content=a" target="_blank">learn more</a>&#41;
                            </td>
                        </tr>
                        <tr>
                            <td>City Name</td>
                            <td><code>[ifsoDKI type='geo' show='city' fallback='' ajax='yes']</code><div class="geo-dki-table-copy-button">🗊</div></td>
                            <td>Display the user’s city name</td>
                        </tr>
                        <tr>
                            <td>State Name</td>
                            <td><code>[ifsoDKI type='geo' show='state' fallback='' ajax='yes']</code><div class="geo-dki-table-copy-button">🗊</div></td>
                            <td>Display the user’s state name</td>
                        </tr>
                        <tr>
                            <td>Continent Name</td>
                            <td><code>[ifsoDKI type='geo' show='continent' fallback='' ajax='yes']</code><div class="geo-dki-table-copy-button">🗊</div></td>
                            <td>Display the user’s continent name</td>
                        </tr>
                        <tr>
                            <td>User's Time zone</td>
                            <td><code>[ifsoDKI type='geo' show='timezone' ajax='yes']</code><div class="geo-dki-table-copy-button">🗊</div></td>
                            <td>Display the user’s time zone</td>
                        </tr>
                        <tr>
                            <td>Event Auto-local Time Display</td>
                            <td><code>[ifsoDKI type='time' show='user-geo-timezone-sensitive' time='04/25/2022 08:00' format='n/j/o, G:i' ajax='yes']</code><div class="geo-dki-table-copy-button">🗊</div></td>
                            <td>
                                Show an event time calculated according to the user’s time zone
                                &#40;<a href="https://www.if-so.com/auto-local-time-display/?utm_source=Plugin&utm_medium=dki-table&utm_campaign=geolocation&utm_term=info-top&utm_content=a" target="_blank">learn more</a>&#41;
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- more DKI button - START -->
                    <a class="geo-dki-button" target="_blank" href="https://www.if-so.com/help/documentation/dynamic-keyword-insertion/?utm_source=Plugin&utm_medium=dki-table&utm_campaign=geolocation&utm_term=info-top&utm_content=a">
                        MORE DKI SHORTCODES
                        <svg class="geo-dki-button-arrow" width="15" height="14" viewBox="0 0 26 25">
                            <path fill="white" d="M13.758 25l-2.12-2.167 8.62-8.808H.003v-3.06h20.255l-8.62-8.808 2.12-2.164 12.238 12.503z"></path>
                        </svg> 
                    </a>
                <!-- more DKI button - END -->
            </div>
        </div>
        <!-- new -->

        <div class="ifso-geo-tabs-contents-wrapper">
            <!-- Notifications tab contents: -->
            <div class="ifso-geo-page-tab-content ifso-geo-page-tab-notifications">
                <div class="geolocation-info-wrapper">
                    <form method="post" action="" class="license-form">
                    <?php settings_fields('edd_ifso_license'); ?>
                    <table class="form-table license-tbl">
                        <tbody>
                            <tr valign="top">
                                <th class="licenseTable" scope="row"  valign="top">
                                    <?php _e('Send Email Alerts'); ?>
                                </th>
                                <td >
                                <input type="checkbox" class="box" name="alert-checkbox-value" id="dimming-checkbox" value="100"  <?php echo in_array('100', $form_alert_values) ? 'checked' : '';?>>
                                <span class="notification-line">  <i><?php _e('Check this box if you would like to be notified regarding your quota threshold', 'if-so'); ?></i> </span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th class="licenseTable" scope="row" valign="top">
                                    <?php _e('Quota Threshold', 'if-so'); ?>
                                </th>
                                <td>
                                <input type="checkbox" class="group1" id="1st-box" name="alert-checkbox-value-1" value="75" <?php echo in_array('75', $form_alert_values) ? 'checked' : '';?> <?php echo !in_array('100', $form_alert_values) ? 'disabled' : '';?> > <span class="notification-line">  <span class="percentage">75%</span> &nbsp 	   <i><?php _e('Receive an email alert when quota reaches 75%', 'if-so'); ?></i> </span><br><br>
                                <input type="checkbox" class="group1" id="2nd-box" name="alert-checkbox-value-2" value="95" <?php echo in_array('95', $form_alert_values) ? 'checked' : '';?> <?php echo !in_array('100', $form_alert_values) ? 'disabled' : '';?>>  <span class="notification-line">  <span class="percentage">95%</span>&nbsp&nbsp  <i><?php _e('Receive an email alert when quota reaches 90%', 'if-so'); ?></i> </span><br><br>
                                <input type="checkbox" class="group1" id="3rd-box" name="alert-checkbox-value-3" value="100" <?php echo in_array('100', $form_alert_values) ? 'checked' : '';?> <?php echo !in_array('100', $form_alert_values) ? 'disabled' : '';?>>  <span class="notification-line">  <span class="percentage">100%</span>&nbsp&nbsp  <i><?php _e('Receive an email alert when quota reaches 100%', 'if-so'); ?></i></span><br>
                                </td>
                            </tr>
                            <?php if(in_array('100', $form_alert_values) != 1 )
                                    echo "<script>$('.notification-line').css('color', '#DCDCDC	') </script>";
                                else
                                    echo "<script> $('.notification-line').css('color', 'black') </script>";
                            ?>

                            <tr valign="top">
                                <th class="licenseTable" scope="row" valign="top">
                                    <?php _e('Email'); ?>
                                </th>
                                <td>
                                <input id name="user-email-box" type="email" class="emailBox" value = "<?php echo get_notification_data()['user_email'];?>" >
                                </td>
                            </tr>

                            <tr valign="top">
                                <th></th>
                                <td style="padding-top:0;"><?php _e('Make sure emails don’t go to spam.','if-so') ?> <a id="ifso_send_test_email" href="#"><?php _e('Send a test email.','if-so') ?> </a></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <input type="submit" class="button-primary" name="update_notifications" value=<?php _e('Save', 'if-so')?>>
                    </form>
                </div>
            </div>
            <!--end of Notifications div  -->

            <!--Info Tab Contents--->
            <div class="ifso-geo-page-tab-content ifso-geo-page-tab-info">
                <!-- new geo info cards - START -->
                <div class="geo-info">
                    <?php _e('<p class="ifso-settings_paragraph">The geolocation service is limited to 250 monthly <span title="A session is defined as beginning when a visitor first visits a page with a geolocation trigger and ends when a visitor closes the browser, the user ip is changed, or after 25 minutes of inactivity." class="tm-tip ifso_tooltip line-tooltip">sessions</span>  with the free version and 1,000 monthly sessions for the duration of one year with the pro version.  <a class="buy-more-credits-link" href=" https://www.if-so.com/plans/geolocation-plans/?utm_source=Plugin&utm_medium=message&utm_campaign=geolocation&utm_term=info-top&utm_content=a" target="_blank">Click here</a> for additional options if your website handles a larger amount.</p>','if-so'); ?>
                    <div class="geo-info-notification">
                        <svg viewBox="0 0 512 512">
                            <path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998
                                26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203
                                36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"/>
                        </svg>
                        <p>
                            <b><?php _e('Not every page view counts as a session')?></b>
                            - <?php _e('If-So stores the users location during their visit so that a geolocation session is only counted once when users browse different pages on the same visit.'); ?>
                        </p>
                    </div>
                    <?php if(!defined('IFSO_GEOLOCATION_ON') || !IFSO_GEOLOCATION_ON){ ?>
                        <div class="geo-info-download">
                            <div>
                                <p><?php _e('Download our free geolocation extension for more options')?></p>
                                <ul>
                                    <li><?php _e('Browser-based Location (HTML5 Geolocation API)')?></li>
                                    <li><?php _e('Countries flag DKI shortcode')?></li>
                                    <li><?php _e('Log geolocation requests')?></li>
                                    <li><?php _e('Location override')?></li>
                                    <li><?php _e('Bots block mode')?></li>
                                </ul>
                            </div>
                            <a target="_blank" href="https://wordpress.org/plugins/if-so-geolocation/">
                                <?php _e('Free Download >')?>
                            </a>
                        </div>
                    <?php } ?>
                    <div class="geo-info-cards">
                        <div class="geo-info-card">
                            <p class="geo-info-card-title">
                                <?php _e('Monthly Sessions'); ?>
                            </p>

                            <?php if(!is_geo_data_valid($geoData)){ ?>
                                <p class="geo-info-card-content error-label">
                                    <?php echo number_format($geo_int_monthly_queries); ?>
                                </p>
                                <p class="geo-info-card-link error-label">
                                    Communication failure
                                    <br>
                                    <a class="geo-info-card-link error-label" href="https://www.if-so.com/help/communication-failure/?utm_source=Plugin&utm_medium=error&utm_campaign=geolocation&utm_term=comm_failure&utm_content=a" target="_blank">
                                        Click to solve
                                    </a>
                                </p>
                            <?php } else{ ?>
                                <p class="geo-info-card-content">
                                    <?php echo number_format($geo_int_monthly_queries); ?>
                                </p>
                                <a class="geo-info-card-link" target="_blank" href="https://www.if-so.com/plans/geolocation-plans/?utm_source=Plugin&utm_medium=message&utm_campaign=geolocation&utm_term=info-tab&utm_content=a">
                                    <?php _e('Upgrade'); ?>
                                </a>
                            <?php } ?>

                        </div>
                        <div class="geo-info-card">
                            <p class="geo-info-card-title">
                                <?php _e('Used Monthly Sessions'); ?>
                            </p>
                            <p class="geo-info-card-content">
                                <?php echo number_format($geo_queries_left_send); ?>
                            </p>
                            <a class="geo-info-card-link" href="https://www.if-so.com/faq-items/how-do-we-define-a-geolocation-session/?utm_source=Plugin&utm_medium=message&utm_campaign=geolocation&utm_term=info-tab&utm_content=session" target="_blank">
                                <?php _e('What is a session'); ?>
                            </a>
                        </div>
                        <div class="geo-info-card">
                            <p class="geo-info-card-title">
                                <?php _e('Remaining Monthly Sessions'); ?>
                            </p>
                            <p class="geo-info-card-content">
                                <?php echo number_format($geo_int_monthly_queries - $geo_queries_left_send); ?>
                            </p>
                        </div>
                        <?php if(is_geo_data_valid($geoData)): ?>
                        <div class="geo-info-card">
                            <p class="geo-info-card-title">
                                <?php _e('Next Renewal Date'); ?>
                            </p>
                            <p class="geo-info-card-content">
                                <?php
                                $renew_date = get_plusgeo_renewal_date($geoData) ? get_plusgeo_renewal_date($geoData) : get_pro_renewal_date($geoData);
                                echo get_date_i18n($renew_date,true);
                                ?>
                            </p>
                        </div>
                        <?php endif; ?>
                        <?php if(is_geo_data_valid($geoData) && ($pro_license_type!='free' || $geo_license_type)): ?>
                            <div class="geo-info-card your-subscription">
                                <p class="geo-info-card-title">
                                    <?php _e('Your Subscriptions'); ?>
                                </p>
                                <p class="geo-info-card-content">
                                    <?php echo (($pro_license_type && $pro_license_type!='free' || $pro_license_type=='free' && !$geo_license_type ) ?
                                            '<span class="geo-pro-subscription-span"><span class="subsctiption-name">' .  ucwords($pro_license_type) . '</span>' . " ({$separateRealizations['pro']}/{$pro_license_bank} sessions)</span>" : '') .
                                            (($geo_license_type) ? '<span class="geo-subscription-span"><span class="subsctiption-name">' . ucwords($geo_license_type) . '</span>'  . " ({$separateRealizations['geo']}/{$geo_license_bank} sessions)</span>" : '');?>
                                </p>
                                <a class="geo-info-card-link" target="_blank" href="https://www.if-so.com/plans/geolocation-plans/?utm_source=Plugin&utm_medium=message&utm_campaign=geolocation&utm_term=info-tab&utm_content=a">
                                    <?php _e('Upgrade','if-so'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>


                    <!-- new central call to DB - START -->
                    <?php
                        $results = $wpdb->get_results( "SELECT * FROM {$daily_sessions_table_name} ORDER BY id DESC");
                        $data_array = array();
                        $sessions_contain_badly_formatted_dates = false;
                        foreach ($results as $res) {
                            try{
                                $date = DateTime::createFromFormat(GeolocationService\GeolocationService::get_instance()->get_daily_sessions_table_date_format(),$res->sessions_date);
                                if ($date===false) throw new \Exception('Wrong date format in daily sessions table');
                                $sessions = intval($res->num_of_sessions);
                                $data = array(
                                    'date' => $date->format('Y-m-d'),
                                    'sessions' => $sessions
                                );
                                array_push($data_array, $data);
                            }
                            catch(\Exception $e){
                                $sessions_contain_badly_formatted_dates = true;
                                continue;
                            }
                        }
                        echo '<script>';
                        echo 'var rawSessionData = ' . json_encode($data_array) . ';';
                        echo '</script>';
                    ?>
                    <!-- new central call to DB - END -->

                    <!-- new date controls - START -->
                    <script src='<?php echo IFSO_PLUGIN_DIR_URL . 'admin/js/jquery.ifsodatetimepicker.full.min.js'; ?>' id='if-soDateTimePickerFullMinJs-js'></script>
                    <link rel='stylesheet' id='if-soDateTimePickerCss-css' href='<?php echo IFSO_PLUGIN_DIR_URL . 'admin/css/jquery.ifsodatetimepicker.css'; ?>' media='all' />
                    
                    <div class="geo-info-filter">  
                        <div class="geo-info-filter-container">
                            <input type="text" class="geo-info-filter-picker" id="start-date">
                        </div>
                        <div class="geo-info-filter-container">
                            <input type="text" class="geo-info-filter-picker" id="end-date">
                        </div>
                        <button class="geo-info-filter-submit button" onclick="dateActions.onFilterChange()">Filter</button>
                    </div>
                    <!-- new date controls - END -->

                    <!-- new chart - START -->
                    <div class="geo-info-chart-container">
                        <canvas class="geo-info-chart"></canvas>
                    </div>

                    <script 
                        src="https://cdn.jsdelivr.net/npm/chart.js" 
                        crossorigin="anonymous" 
                        referrerpolicy="no-referrer">
                    </script>

                    <script>
                        class DateActions {
                            constructor(rawData, tableBodySelector, chartContainerSelector, chartSelector, filterSelector, startDateSelector, endDateSelector) {
                                this.rawData = rawData
                                this.data = this.formatSessionData(rawSessionData)
                                this.filteredData = undefined

                                this.startDateSelector = startDateSelector
                                this.endDateSelector = endDateSelector
                             
                                document.addEventListener('DOMContentLoaded', () => { 
                                    this.tbody = document.querySelector(tableBodySelector)
                                    this.chartContainerElem = document.querySelector(chartContainerSelector)
                                    this.chartElem = this.chartContainerElem.querySelector(chartSelector)
                                    this.filterElem = document.querySelector(filterSelector)
                                    this.startDateElem = this.filterElem.querySelector(startDateSelector)
                                    this.endDateElem = this.filterElem.querySelector(endDateSelector)
                                    if(typeof(this.data)==='undefined' || this.data.length===0) {
                                        this.chartContainerElem.classList.toggle('nodisplay');
                                        this.filterElem.classList.toggle('nodisplay');
                                        return;
                                    }
                                    this.initFilters()
                                    this.initChart()
                                    this.fillChart()
                                    this.fillTable()
                                })
                            }

                            initFilters = function() {
                                let start = new Date()
                                start.setHours(0,0,0,0)
                                start.setMonth(start.getMonth() - 1)
                                let end = new Date()
                                end.setHours(0,0,0,0)
                                let minDate = new Date(this.data[0].date.valueOf())
                                minDate.setHours(0,0,0,0)
                                
                                let startStr = start.toISOString().substring(0, 10).split('-').join('/')
                                let endStr = end.toISOString().substring(0, 10).split('-').join('/')

                                let startDateSelector = this.startDateSelector
                                let endDateSelector = this.endDateSelector

                                jQuery(startDateSelector).ifsodatetimepicker({
                                    timepicker: false,
                                    format: 'm/d/Y',
                                    defaultDate: start,
                                    minDate: minDate,
                                    onShow: function(ct) {
                                        let endValue = jQuery(endDateSelector).val()
                                        this.setOptions({
                                            maxDate: endValue ? endValue : false
                                        })
                                    },
                                })
                                jQuery(endDateSelector).ifsodatetimepicker({
                                    timepicker: false,
                                    format: 'm/d/Y',
                                    defaultDate: end,
                                    maxDate:'0',
                                    onShow: function(ct) {
                                        let startValue = jQuery(startDateSelector).val()
                                        this.setOptions({
                                            // todo - cant set the minDate value because it disables all past value for some reason
                                            minDate: false // startValue ? startValue : false
                                        })
                                    },
                                })

                                this.startDateElem.value = this.formatForInput(start)
                                this.endDateElem.value = this.formatForInput(end)
                                this.filterDates(start, end)
                            }
                            formatForInput(date) {
                                let mm = (date.getMonth() + 1).toString().padStart('0', 2)
                                let dd = date.getDate().toString().padStart('0', 2)
                                let yyyy = date.getFullYear().toString()
                                return `${mm}/${dd}/${yyyy}`
                            }

                            initChart = function() {
                                let backgroundColorPlugin = {
                                    beforeDraw: function (chart, easing) {
                                        if (chart.config.options.chartArea && chart.config.options.chartArea.backgroundColor) {
                                            var ctx = chart.ctx
                                            var chartArea = chart.chartArea
                                            ctx.save()
                                            ctx.fillStyle = chart.config.options.chartArea.backgroundColor
                                            ctx.fillRect(chartArea.left, chartArea.top, chartArea.right - chartArea.left, chartArea.bottom - chartArea.top)
                                            ctx.restore()
                                        }
                                    }
                                }

                                this.chart = new Chart(this.chartElem, {
                                    type: 'line',
                                    data: {
                                        labels: undefined,
                                        datasets: [{
                                            label: 'sessions',
                                            data: undefined,
                                            borderWidth: 2,
                                            borderColor: '#EDC240',
                                            backgroundColor: 'white',
                                        }],
                                    },
                                    options: {
                                        scales: {
                                            y: { beginAtZero: true },
                                            x: {
                                                ticks: {
                                                    align: 'center',
                                                    autoSkip: true,
                                                    maxRotation: 0,
                                                    maxTicksLimit: 8
                                                },
                                            },

                                        },
                                        maintainAspectRatio: false,
                                        chartArea: { backgroundColor: '#F9F9F9' },
                                        plugins: {
                                            legend: { display: false },
                                            tooltip: { displayColors: false }
                                        }
                                    },
                                    plugins: [backgroundColorPlugin]
                                });
                            }

                            getChartLabels = function() {
                                return this.filteredData.map(val => val.date.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" }))
                            }
                            getChartData = function() {
                                return this.filteredData.map(val => val.sessions)
                            }

                            formatSessionData = function(data) {
                                let reversed = data.reverse()
                                let dateObjectified = reversed.map(item => { item.date = new Date(Date.parse(item.date)); return item;} )
                                let filled = this.addEmptyDates(dateObjectified)
                                return filled
                            }

                            addEmptyDates = function(data) {
                                if (data.length <= 1) return data
                                let start = data[0].date
                                let end = data[data.length - 1].date
                                for (let d = start; d <= end; d = new Date(d.getTime() + 86400000)) { // milliseconds in day
                                    let dateExists = data.find(item => item.date.getTime() === d.getTime())
                                    if (!dateExists) data.push({ date: d, sessions: 0 })
                                }
                                data.sort((a, b) => new Date(a.date) - new Date(b.date))  // Sort again by date
                                return data
                            }

                            filterDates = function(start, end) {
                                let startValid = start instanceof Date && !isNaN(start)
                                let endValid = end instanceof Date && !isNaN(end)

                                if ( !startValid || !endValid ) return
                                if ( start.getTime() >= end.getTime() ) return

                                let startDay = new Date(start.getTime())
                                let endDay = new Date(end.getTime())
                                startDay.setHours(0,0,0,0)
                                endDay.setHours(0,0,0,0)

                                this.filteredData = this.data.filter(val => {
                                    let day = new Date(val.date.getTime())
                                    day.setHours(0,0,0,0)
                                    return (day.getTime() >= startDay.getTime()) && 
                                           (day.getTime() <= endDay.getTime())
                                })
                            }

                            fillTable = function() {
                                this.tbody.innerHTML = ''
                                if (this.filteredData.length === 0) {
                                    let tr = document.createElement('tr')
                                    let notif = document.createElement('td')
                                    let par = document.createElement('p')
                                    notif.colSpan = 4
                                    notif.className = 'empty-table-notifier'
                                    par.innerHTML = "<?php _e('You haven`t used the geolocation service','if-so');?>"
                                    notif.appendChild(par)
                                    tr.appendChild(notif)
                                    this.tbody.appendChild(tr)
                                } else {
                                    this.filteredData.forEach(val => {
                                        let tr = document.createElement('tr')
                                        let dateTd = document.createElement('td')
                                        dateTd.innerHTML = val.date.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" })
                                        let sessionsTd = document.createElement('td')
                                        sessionsTd.innerHTML = val.sessions
                                        tr.appendChild(dateTd)
                                        tr.appendChild(sessionsTd)
                                        this.tbody.appendChild(tr)
                                    })
                                }
                            }

                            fillChart = function() {
                                if (this.filteredData.length === 0) { 
                                    this.chartContainerElem.classList.add('disabled')
                                    this.filterElem.classList.add('disabled')
                                } else {
                                    this.chart.data.labels = this.getChartLabels()
                                    this.chart.data.datasets[0].data = this.getChartData()
                                    this.chart.update()
                                }
                            }

                            onFilterChange = function() {
                                let start = new Date(this.startDateElem.value)
                                let end = new Date(this.endDateElem.value)
                                this.filterDates(start, end)
                                this.fillTable()
                                this.fillChart()
                            }
                        }

                        let dateActions = new DateActions(
                            window.rawSessionData,
                            '.geo-info-table tbody',
                            '.geo-info-chart-container',
                            '.geo-info-chart',
                            '.geo-info-filter',
                            '#start-date',
                            '#end-date',
                        )
                    </script>
                    <!-- new chart - END -->
                    
                    <div class="geo-info-table-wrapper">
                        <?php if($sessions_contain_badly_formatted_dates): ?>
                            <div class="red-noticebox">
                                One or more dates in your session log aren't being displayed due to incorrect formatting.
                            </div>
                        <?php endif; ?>
                        <!-- new table - START -->
                        <table class="geo-info-table">
                            <thead>
                            <tr>
                                <th><?php _e('Date','if-so');?></th>
                                <th><?php _e('Daily geolocation sessions','if-so');?></th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <!-- new table - END -->
                    </div>
                </div>
                <!-- new geo info cards - END -->

                <div>
                    <?php if(!is_geo_data_valid($geoData)){ ?>
                        <span class="error-label" style="color:red;padding-left:10px;">Communication failure! <a href="https://www.if-so.com/help/communication-failure/?utm_source=Plugin&utm_medium=error&utm_campaign=geolocation&utm_term=comm_failure&utm_content=a" target="_blank">Click to solve</a> </span>
                    <?php } ?>
                </div>

            </div>
            <!-- end Info Tab -->

            <!--start extra tabs-->
            <?php echo print_extra_tabs_contents($extra_tabs); ?>
            <!--end extra tabs-->

        </div> <!-- end of ifso-geo-tabs-contents-wrapper -->
    </div>
</body>
</html>