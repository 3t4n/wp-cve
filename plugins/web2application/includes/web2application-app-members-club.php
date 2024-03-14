<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// Init Options Global
global $w2a_options;
wp_enqueue_media();	


// SAVE SETTINGS
if (isset($_POST['updateSettings'])) {
	if(wp_verify_nonce($_REQUEST['w2a_app_members_update_settings'], 'w2a_app_members')){
		
		//sanitize input fields
		$postData = $_POST['data'];
		
		// send the data to save
		$url = 'http://www.web2application.com/w2a/api-process/save_premium_settings_from_plugin.php';
		$data = array('api_domain' => $_SERVER['SERVER_NAME'], 'api_key' => trim($w2a_options['w2a_api_key']), 'data' => $postData);
		$json = json_encode($data);
		
		// init header
		$headers = array("Content-type: application/json");

		// init curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		// check if response is not empty
        if ($response != ""){
            echo '<div id="web2app-error-mesage">';
            echo $response;
            echo '</div>';
        }

	} else {
		// display error
		echo '<div id="web2app-error-mesage">';
		echo _e('oops... some thing wrong. Please reload the page and try again', 'web2application');
		echo '</div>';
	}
}


// EDIT MEMBER
if (isset($_POST['editMember'])) {
	if(wp_verify_nonce($_REQUEST['w2a_edit_member'], 'w2a_edit_member')){

		//sanitize input fields
		$postData = $_POST['data']; //sanitize_text_field($_POST['data']);
		$ids = $_POST['ids'];

		// send the data to save
		$url = 'http://www.web2application.com/w2a/api-process/update_app_member_from_plugin.php';
		$data = array('api_domain' => $_SERVER['SERVER_NAME'], 'api_key' => trim($w2a_options['w2a_api_key']), 'id' => trim($_GET['member_id']), 'data' => $postData, 'ids' => $ids);
		$json = json_encode($data);

		// init header
		$headers = array("Content-type: application/json");

		// init curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		// check if response is not empty
        if ($response != ""){
            echo '<div id="web2app-error-mesage">';
            echo $response;
            echo '</div>';
        }
		
		header("Location: admin.php?page=web2application-app-members-club&tab=members-list");

	} else {
		// display error
		echo '<div id="web2app-error-mesage">';
		echo _e('oops... some thing wrong. Please reload the page and try again', 'w2a_domain');
		echo '</div>';
	}
}


// ADD GROUP
if (isset($_POST['addGroup'])) {
	if(wp_verify_nonce($_REQUEST['w2a_add_group'], 'w2a_add_group')){

		//sanitize input fields
		$postData = $_POST['data']; //sanitize_text_field($_POST['data']);

		// send the data to save
		$url = 'http://www.web2application.com/w2a/api-process/save_new_group_from_plugin.php';
		$data = array('api_domain' => $_SERVER['SERVER_NAME'], 'api_key' => trim($w2a_options['w2a_api_key']), 'data' => $postData);
		$json = json_encode($data);

		// init header
		$headers = array("Content-type: application/json");

		// init curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		// check if response is not empty
        if ($response != ""){
            echo '<div id="web2app-error-mesage">';
            echo $response;
            echo '</div>';
        }

	} else {
		// display error
		echo '<div id="web2app-error-mesage">';
		echo _e('oops... some thing wrong. Please reload the page and try again', 'w2a_domain');
		echo '</div>';
	}
}


// ADD AUTOMATION
if (isset($_POST['addAutomation'])) {
	if(wp_verify_nonce($_REQUEST['w2a_add_automation'], 'w2a_add_automation')){

		//sanitize input fields
		$postData = $_POST['data']; //sanitize_text_field($_POST['data']);

		// send the data to save
		$url = 'http://www.web2application.com/w2a/api-process/save_new_automation_from_plugin.php';
		$data = array('api_domain' => $_SERVER['SERVER_NAME'], 'api_key' => trim($w2a_options['w2a_api_key']), 'data' => $postData);
		$json = json_encode($data);

		// init header
		$headers = array("Content-type: application/json");

		// init curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		// check if response is not empty
        if ($response != ""){
            echo '<div id="web2app-error-mesage">';
            echo $response;
            echo '</div>';
        }
		
		header("Location: admin.php?page=web2application-app-members-club&tab=automations");

	} else {
		// display error
		echo '<div id="web2app-error-mesage">';
		echo _e('oops... some thing wrong. Please reload the page and try again', 'w2a_domain');
		echo '</div>';
	}
}


// SEND SPECIAL PUSH
if (isset($_POST['sendSpecialPush'])) {
    if(wp_verify_nonce($_REQUEST['w2a_send_special_push'], 'w2a_send_special_push')){
		// check
        if (isset($_POST['push_title']) && isset($_POST['push_message']) && $_POST['push_title'] != "" && $_POST['push_message'] != "") {
			
			//sanitize input fields
            $w2aPushTitle       = sanitize_text_field($_POST['push_title']);
            $w2aPushMessage     = sanitize_text_field($_POST['push_message']);
            $w2aPushImage       = sanitize_text_field($_POST['image']);
			$w2aRichPushImage   = sanitize_text_field($_POST['big_image']);
			$w2aPushLink        = sanitize_text_field($_POST['push_link']);
				
			$push_schedule = sanitize_text_field($_POST['push_schedule']);
            $w2aPushTime = ($push_schedule == "send_now") ? date('Y/m/d H:i:s') : sanitize_text_field($_POST['push_date']).' '.sanitize_text_field($_POST['push_time']).':00';
			
			$app_member_email = sanitize_text_field($_POST['app_member_email']);

            //send the data for pushing
            $url = 'http://www.web2application.com/w2a/api-process/send_special_push_from_plugin.php';
            $data = array('api_domain' => $_SERVER['SERVER_NAME'], 'api_key' => trim($w2a_options['w2a_api_key']),'push_title' => $w2aPushTitle, 'push_text' => $w2aPushMessage, 'push_image_url' => $w2aPushImage, 'rich_push_image_url' => $w2aRichPushImage, 'push_link' => $w2aPushLink, 'push_time' => $w2aPushTime, 'app_member_email' => $app_member_email);
			$json = json_encode($data);

            // init header
			$headers = array("Content-type: application/json");

			// init curl
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			
			$response = curl_exec($ch);
			curl_close($ch);
			
			// check if response is not empty
			/*if ($response != ""){
				echo '<div id="web2app-error-mesage">';
				echo $response;
				echo '</div>';
			}*/
			
			header("Location: admin.php?page=web2application-app-members-club&tab=members-list");
			
        } else {
            echo '<div id="web2app-error-mesage">';
            echo _e('Missing Title Or Body', 'web2application');
            echo '</div>';
        }
	// if nonces not ok
	} else {
		echo '<div id="web2app-error-mesage">';
			echo _e('oops... some thing wrong. Please reload the page and try again', 'web2application');
		echo '</div>';
	}		
}


// SEND PUSH TO GROUP
if (isset($_POST['sendGroupPush'])) {
    if(wp_verify_nonce($_REQUEST['w2a_send_group_push'], 'w2a_send_group_push')){
		// check
        if (isset($_POST['push_title']) && isset($_POST['push_message']) && $_POST['push_title'] != "" && $_POST['push_message'] != "") {
			
			//sanitize input fields
            $w2aPushTitle       = sanitize_text_field($_POST['push_title']);
            $w2aPushMessage     = sanitize_text_field($_POST['push_message']);
            $w2aPushImage       = sanitize_text_field($_POST['image']);
			$w2aRichPushImage   = sanitize_text_field($_POST['big_image']);
			$w2aPushLink        = sanitize_text_field($_POST['push_link']);
				
			$push_schedule = sanitize_text_field($_POST['push_schedule']);
            $w2aPushTime = ($push_schedule == "send_now") ? date('Y/m/d H:i:s') : sanitize_text_field($_POST['push_date']).' '.sanitize_text_field($_POST['push_time']).':00';
			
			$group_id = sanitize_text_field($_POST['group_id']);

            //send the data for pushing
            $url = 'http://www.web2application.com/w2a/api-process/send_group_push_from_plugin.php';
            $data = array('api_domain' => $_SERVER['SERVER_NAME'], 'api_key' => trim($w2a_options['w2a_api_key']),'push_title' => $w2aPushTitle, 'push_text' => $w2aPushMessage, 'push_image_url' => $w2aPushImage, 'rich_push_image_url' => $w2aRichPushImage, 'push_link' => $w2aPushLink, 'push_time' => $w2aPushTime, 'group_id' => $group_id);
			$json = json_encode($data);

            // init header
			$headers = array("Content-type: application/json");

			// init curl
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			
			$response = curl_exec($ch);
			curl_close($ch);
			
			// check if response is not empty
			/*if ($response != ""){
				echo '<div id="web2app-error-mesage">';
				echo $response;
				echo '</div>';
			}*/
			
			header("Location: admin.php?page=web2application-app-members-club&tab=groups");
			
        } else {
            echo '<div id="web2app-error-mesage">';
            echo _e('Missing Title Or Body', 'web2application');
            echo '</div>';
        }
	// if nonces not ok
	} else {
		echo '<div id="web2app-error-mesage">';
			echo _e('oops... some thing wrong. Please reload the page and try again', 'web2application');
		echo '</div>';
	}		
}


// ADD GROUP MEMBER 
if (isset($_POST['addGroupMember'])) {
	if(wp_verify_nonce($_REQUEST['w2a_add_group_member'], 'w2a_add_group_member')){

		//sanitize input fields
		$ids = $_POST['ids'];

		// send the data to save
		$url = 'http://www.web2application.com/w2a/api-process/save_new_group_member_from_plugin.php';
		$data = array('api_domain' => $_SERVER['SERVER_NAME'], 'api_key' => trim($w2a_options['w2a_api_key']), 'id' => trim($_GET['group_id']), 'ids' => $ids);
		$json = json_encode($data);

		// init header
		$headers = array("Content-type: application/json");

		// init curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		// check if response is not empty
        if ($response != ""){
            echo '<div id="web2app-error-mesage">';
            echo $response;
            echo '</div>';
        }
		
		header("Location: admin.php?page=web2application-app-members-club&tab=groups");

	} else {
		// display error
		echo '<div id="web2app-error-mesage">';
		echo _e('oops... some thing wrong. Please reload the page and try again', 'w2a_domain');
		echo '</div>';
	}
}


// DOWNLOAD CSV
if (isset($_POST['downloadCSV'])) {
	if(wp_verify_nonce($_REQUEST['w2a_download_csv'], 'w2a_download_csv')){
		
		// or where ever you want the file to go
		$path = wp_upload_dir();   
		// the file name you choose
		$outstream = fopen($path['path']."/members-list.csv", "w");  

		// the user information you want in the csv file
		$header = array("NAME", "PHONE", "EMAIL", "BIRTH DATE");  

		// creates the first line in the csv file
		fputcsv($outstream, $header);  
		
		// fields
		$fields = array("app_member_name", "app_member_phone", "app_member_email", "app_member_birthday");

		// initialize the array
		$values = array();   
		
		// iterate members
		foreach ($row->members as $member) {
			// values
			$values = array($member->app_member_name, $member->app_member_phone, $member->app_member_email, $member->app_member_birthday);
			
			// output the user info line to the csv file
			fputcsv($outstream, $values); 
		}

		fclose($outstream); 
		echo '<a href="'.$path['url'].'/members-list.csv">Download</a>';
	}
}




// default
$startDate = date('m/d/Y', strtotime('today - 30 days'));
$endDate = date('m/d/Y');

// check
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
	// get start and end date
	$startDate = date_format(date_create($_GET['start_date']), 'm/d/Y');
	$endDate = date_format(date_create($_GET['end_date']), 'm/d/Y');
}


// get appId to check api key validity
$url = 'https://www.web2application.com/w2a/api-process/get_app_id.php?api_domain='.$_SERVER['SERVER_NAME'].'&api_key='.trim($w2a_options['w2a_api_key'].'&version=new');
$appId = file_get_contents($url);

// check
if ($appId == "") {
	// init curl
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$appId = curl_exec($ch);
	curl_close($ch);
}

// check
$disabled = ($appId == 'Wrong API. Please Check Your API Key' || trim($w2a_options['w2a_api_key']) == "") ? true : false;

// check
if ($appId != 'Wrong API. Please Check Your API Key' && is_numeric($appId)) {
	// get app members club
	$url 		= 'https://www.web2application.com/w2a/api-process/get_app_members.php?api_domain='.$_SERVER['SERVER_NAME'].'&api_key='.trim($w2a_options['w2a_api_key'].'&start_date='.$startDate.'&end_date='.$endDate.'&version=new');
	$app 		= file_get_contents($url);
	//$row 		= json_decode($app);
	
	// check
	if ($app == "") {
		// init curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$app = curl_exec($ch);
		curl_close($ch);
	}
	
	// decode
	$row = json_decode($app); //echo $url;
}

$default_tab = 'dashboard';
$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<link href="//web2application.com/w2a/user/lib/colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet">
<script src="//web2application.com/w2a/user/lib/colorpicker/js/bootstrap-colorpicker.js"></script>

<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style type="text/css">
.symbol-group {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  margin-left: 10px;
}
.symbol-group .symbol {
  position: relative;
  z-index: 0;
  margin-left: -10px;
  border: 2px solid #ffffff;
  transition: all 0.3s ease;
}
.symbol-group .symbol:hover {
  transition: all 0.3s ease;
  z-index: 1;
}
.symbol-group.symbol-hover .symbol {
  cursor: pointer;
}

.flex-nowrap {
  flex-wrap: nowrap !important;
}
	
.symbol {
  display: inline-block;
  flex-shrink: 0;
  position: relative;
  border-radius: 0.475rem;
}
.symbol .symbol-label {
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 500;
  color: #3F4254;
  /*background-color: #F5F8FA;
  background-repeat: no-repeat;
  background-position: center center;
  background-size: cover;*/
  border-radius: 0.475rem;
}
.symbol .symbol-badge {
  position: absolute;
  border: 2px solid #ffffff;
  border-radius: 100%;
  top: 0;
  left: 50%;
  transform: translateX(-50%) translateY(-50%) !important;
}
.symbol.symbol-circle,
.symbol.symbol-circle > img,
.symbol.symbol-circle .symbol-label {
  border-radius: 50%;
}
.symbol .symbol-label {
  width: 50px;
  height: 50px;
}
	
.symbol.symbol-35px .symbol-label {
  width: 35px;
  height: 35px;
}
	
.button-primary {
  color: #000000;
  background-color: #009EF7;
  border-color: #009EF7;
  /*box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);*/
}
.button-primary:hover {
  color: #000000;
  background-color: #26adf8;
  border-color: #1aa8f8;
}
	
.button-success {
  color: #000000;
  background-color: #50CD89;
  border-color: #50CD89;
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
}
.button-success:hover {
  color: #000000;
  background-color: #6ad59b;
  border-color: #62d295;
}
	
.button-info {
  color: #ffffff;
  background-color: #7239EA;
  border-color: #7239EA;
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
}
.button-info:hover {
  color: #ffffff;
  background-color: #6130c7;
  border-color: #5b2ebb;
}
	
.button-warning {
  color: #000000;
  background-color: #FFC700;
  border-color: #FFC700;
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
}
.button-warning:hover {
  color: #000000;
  background-color: #ffcf26;
  border-color: #ffcd1a;
}
	
.button-danger {
  color: #000000;
  background-color: #F1416C;
  border-color: #F1416C;
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
}
.button-danger:hover {
  color: #000000;
  background-color: #f35e82;
  border-color: #f2547b;
}
	
.text-inverse-warning {
  color: #FFFFFF !important;
}
	
.fw-bold {
  font-weight: 500 !important;
}
	
.text-gray-400 {
  color: #B5B5C3 !important;
}
	
	
.form-control {
  width: 400px;
}
.col-md-3 {
  width: 200px;
}
</style>

<div class="wrap">
	<h2><?php _e('App Members Club', 'web2application'); ?></h2>
	
	<nav class="nav-tab-wrapper">
      <a href="?page=web2application-app-members-club&tab=dashboard" class="nav-tab <?php if($tab==='dashboard'):?>nav-tab-active<?php endif; ?>">Members Dashboard</a>
      <a href="?page=web2application-app-members-club&tab=members-list" class="nav-tab <?php if($tab==='members-list' || $tab==='edit-member'):?>nav-tab-active<?php endif; ?>">Members List</a>
      <a href="?page=web2application-app-members-club&tab=testers" class="nav-tab <?php if($tab==='testers'):?>nav-tab-active<?php endif; ?>">Testers</a>
      <a href="?page=web2application-app-members-club&tab=groups" class="nav-tab <?php if($tab==='groups' || $tab==='edit-group' || $tab==='add-group-member'):?>nav-tab-active<?php endif; ?>">Groups</a>
      <a href="?page=web2application-app-members-club&tab=automations" class="nav-tab <?php if($tab==='automations' || $tab==='add-automation'):?>nav-tab-active<?php endif; ?>">Automations</a>
      <a href="?page=web2application-app-members-club&tab=settings" class="nav-tab <?php if($tab==='settings'):?>nav-tab-active<?php endif; ?>">Settings</a>
    </nav>
	
	<div class="tab-content">
		<?php
		switch($tab):
			case 'dashboard':
				?>
				<div class="my-section" style="margin-top:20px;">
					<h3>
						<?php _e('Members Dashboard', 'web2application'); ?>
						<span style="float: right;">
							<input name="daterange" type="text" id="daterange" class="form-control col-md-3" value="<?php _e($startDate . " - " . $endDate); ?>" <?php if ($disabled) { echo "disabled"; } ?> />
						</span>
					</h3>
					<table cellspacing="50">
						<tbody>
							<tr>
								<td valign="top" style="border: 1px solid #f1f1f1; border-radius: 10px; padding: 10px;">
									<h3>Members by Sources</h3>
									<p><?php _e($row->dashboard->total_members); ?> <span style="font-size: 13px;">Total Members</span></p>
									<canvas id="kt_chart_1"></canvas>
								</td>
								<td valign="top" style="border: 1px solid #f1f1f1; border-radius: 10px; padding: 10px;">
									<h3>Members by Group</h3>
									<canvas id="kt_chart_2"></canvas>
								</td>
								<td valign="top" style="border: 1px solid #f1f1f1; border-radius: 10px; padding: 10px;">
									<h3>Members by Signup Date</h3>
									<p><?php _e($row->dashboard->num_of_members_for_today); ?> <span style="font-size: 13px;">Membership for Today</span></p>
									<canvas id="kt_chart_3"></canvas>
								</td>
							</tr>
							<tr>
								<td valign="top" style="border: 1px solid #f1f1f1; border-radius: 10px; padding: 10px;">
									<h3><span style="font-size: 30px;"><?php _e($row->dashboard->num_of_birthdays); ?></span> Number of Birthdays for Today</h3>
									<canvas id="kt_chart_4"></canvas>
								</td>
								<td valign="top" style="border: 1px solid #f1f1f1; border-radius: 10px; padding: 10px;">
									<h3><span style="font-size: 30px;"><?php _e(count($row->testers)); ?></span> Push Testers</h3>
									<!--begin::Users group-->
									<div class="symbol-group symbol-hover flex-nowrap" style="margin-bottom: 20px;">
									<?php 
										foreach ($row->testers as $tester) { 
											$arr = array("button-warning", "button-success", "button-danger", "button-primary", "button-info");
                                            shuffle($arr);
									?>
										<div class="symbol symbol-35px symbol-circle" title="<?php echo $tester->app_member_name; ?>">
                                        	<span class="symbol-label <?php echo $arr[0]; ?> text-inverse-warning fw-bold"><?php echo substr($tester->app_member_name, 0, 1); ?></span>
                                        </div>
                                    <?php } ?>
									<?php if (count($row->testers) > 10) { ?>
                                    	<a href="#" class="symbol symbol-35px symbol-circle">
                                         	<span class="symbol-label button-light text-gray-400 fs-8 fw-bold"><?php echo "+" . count($row->testers)-10; ?></span>
                                       	</a>
                                    <?php } ?>
                                    </div>
									<!--end::Users group-->
									<a href="admin.php?page=web2application-app-members-club&tab=testers" class="button button-primary">Manage Testers</a>
								</td>
								<td valign="top" style="border: 1px solid #f1f1f1; border-radius: 10px; padding: 10px;">
									<h3><span style="font-size: 30px;"><?php _e($row->dashboard->num_of_automations); ?></span> No. of Active Automations</h3>
									<canvas id="kt_chart_5"></canvas>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<?php
				break;
			case 'members-list':
				?>
				<div class="my-section" style="margin-top:20px;">
					<h3><?php _e('List of Members', 'web2application'); ?></h3>
					<table class="form-table stripe">
						<thead class="thead-dark">
							<tr>
								<th scope="col"><?php _e('Name', 'web2application'); ?></th>
								<th scope="col"><?php _e('Phone', 'web2application'); ?></th>
								<th scope="col"><?php _e('Email' ,'web2application'); ?></th>
								<th scope="col"><?php _e('Birthday' ,'web2application'); ?></th>
								<th scope="col" style="width: 100px;"><?php _e('Is Tester?' ,'web2application'); ?></th>
								<th scope="col" style="width: 100px;"><?php _e('Source' ,'web2application'); ?></th>
								<th scope="col"><?php _e('Groups' ,'web2application'); ?></th>
								<th scope="col" style="width: 100px;"><?php _e('Is Blocked?' ,'web2application'); ?></th>
								<th scope="col" style="width: 280px;"><?php _e('Action', 'web2application'); ?></th>
							</tr>
						</thead>
						<tbody>
						<?php
							// iterate
							foreach ($row->members as $member) {
								// id
                                $id = $member->id;
						?>

							<tr>
							  <td><?php _e($member->app_member_name); ?></td>
							  <td><?php _e($member->app_member_phone); ?></td>
							  <td><?php _e($member->app_member_email); ?></td>
							  <td><?php _e(date("d-m-Y ", strtotime($member->app_member_birthday))); ?></td>
							  <td><?php _e($member->is_tester == "1" ? "yes" : "no"); ?></td>
							  <td><?php _e($member->member_source); ?></td>
							  <td><?php _e($member->group); ?></td>
							  <td><?php _e($member->app_member_blocked); ?></td>
							  <td>
								  <a href="admin.php?page=web2application-app-members-club&tab=send-special-push&member_email=<?php echo $member->app_member_email; ?>">Send Push</a> | 
								  <?php if ($member->app_member_blocked == "no") { ?>
								  <a href="javascript:block_member('<?php echo $id; ?>');">Block</a> | 
                            	  <?php } else { ?>
								  <a href="javascript:unblock_member('<?php echo $id; ?>');">Unblock</a> | 
								  <?php } ?>
								  <a href="admin.php?page=web2application-app-members-club&tab=edit-member&member_id=<?php echo $id; ?>">Edit</a> | 
								  <a href="javascript:delete_member('<?php echo $id; ?>');">Delete</a>
							  </td>
							</tr>
							
						<?php } ?>
						</tbody>
					</table>
					<?php
						// or where ever you want the file to go
						$path = wp_upload_dir();   
						// the file name you choose
						$outstream = fopen($path['path']."/members-list.csv", "w");  

						// the user information you want in the csv file
						$header = array("NAME", "PHONE", "EMAIL", "BIRTH DATE");  

						// creates the first line in the csv file
						fputcsv($outstream, $header);  
					
						// init array
						$content = array();

						// iterate members
						foreach ($row->members as $member) {
							// values
							$values = array($member->app_member_name, $member->app_member_phone, $member->app_member_email, $member->app_member_birthday);

							// append
							array_push($content, $values);
						}
					
						// output the user info line to the csv file
						fputcsv($outstream, $content); 

						// close file
						fclose($outstream); 
					?>
					<p class="submit">
						<a href="<?php echo $path['url'].'/members-list.csv'; ?>"  class="button button-primary">Download CSV File</a>
					</p>
				</div>
				<?php
				break;
			case 'testers':
				?>
				<div class="my-section" style="margin-top:20px;">
					<h3><?php _e('List of Testers', 'web2application'); ?></h3>
					<table class="form-table stripe">
						<thead class="thead-dark">
							<tr>
								<th scope="col"><?php _e('Name', 'web2application'); ?></th>
								<th scope="col"><?php _e('Phone', 'web2application'); ?></th>
								<th scope="col"><?php _e('Email' ,'web2application'); ?></th>
								<th scope="col"><?php _e('Birthday' ,'web2application'); ?></th>
								<th scope="col" style="width: 100px;"><?php _e('Is Tester?' ,'web2application'); ?></th>
								<th scope="col" style="width: 100px;"><?php _e('Source' ,'web2application'); ?></th>
								<th scope="col"><?php _e('Groups' ,'web2application'); ?></th>
								<th scope="col" style="width: 100px;"><?php _e('Is Blocked?' ,'web2application'); ?></th>
								<th scope="col" style="width: 280px;"><?php _e('Action', 'web2application'); ?></th>
							</tr>
						</thead>
						<tbody>
						<?php
							// iterate
							foreach ($row->testers as $tester) {
								// id
                                $id = $tester->id;
						?>

							<tr>
							  <td><?php _e($tester->app_member_name); ?></td>
							  <td><?php _e($tester->app_member_phone); ?></td>
							  <td><?php _e($tester->app_member_email); ?></td>
							  <td><?php _e(date("d-m-Y ", strtotime($tester->app_member_birthday))); ?></td>
							  <td><?php _e($tester->is_tester == "1" ? "yes" : "no"); ?></td>
							  <td><?php _e($tester->member_source); ?></td>
							  <td><?php _e($tester->group); ?></td>
							  <td><?php _e($tester->app_member_blocked); ?></td>
							  <td>
								  <a href="admin.php?page=web2application-app-members-club&tab=send-special-push&member_email=<?php echo $member->app_member_email; ?>">Send Push</a> | 
								  <?php if ($member->app_member_blocked == "no") { ?>
								  <a href="javascript:block_member('<?php echo $id; ?>');">Block</a> | 
                            	  <?php } else { ?>
								  <a href="javascript:unblock_member('<?php echo $id; ?>');">Unblock</a> | 
								  <?php } ?>
								  <a href="admin.php?page=web2application-app-members-club&tab=edit-member&member_id=<?php echo $id; ?>">Edit</a> | 
								  <a href="javascript:delete_member('<?php echo $id; ?>');">Delete</a>
							  </td>
							</tr>

						<?php } ?>
						</tbody>
					</table>
				</div>
				<?php
				break;
			case 'groups':
				?>
				<form method="post">
					<div class="my-section" style="margin-top:20px;">
						<h3><?php _e('Create New Group', 'web2application'); ?></h3>
						<input name="data[app_id]" type="hidden" value="<?php echo $appId; ?>" />
						<table>
							<tbody>
								<tr>
									<th scope="row"><label><?php _e('Group Name','web2application'); ?></label></th>
									<td><input name="data[name]" type="text" class="form-control col-md-4" required <?php if ($disabled) { echo "disabled"; } ?> /></td>
									<?php wp_nonce_field('w2a_add_group', 'w2a_add_group'); ?>
									<td><input type="submit" name="addGroup" class="button button-primary" value="<?php _e('Add This Group', 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
		
				<div class="my-section" style="margin-top:20px;">
					<h3><?php _e('Groups/Categories', 'web2application'); ?></h3>
					<table class="form-table stripe">
						<thead class="thead-dark">
							<tr>
								<th scope="col"><?php _e('Name', 'web2application'); ?></th>
								<th scope="col"></th>
								<th scope="col"></th>
								<th scope="col"><?php _e('Action', 'web2application'); ?></th>
							</tr>
						</thead>
						<tbody>
						<?php
							// iterate
							foreach ($row->groups as $group) {
								// get id
								$id = $group->id;
								
								// format date created
								$date_created = date_create($group->date_created);
								$formatted_date = date_format($date_created,"d M Y");
								
								// get number of members
								$count = count($group->members);
								
								// get first 10 members
								$first10 = array_slice($group->members, 0, 10);
						?>

							<tr>
							  <td>
								  <span class="<?php echo "text_".$id; ?>"><b><?php _e($group->name); ?></b></span>
								  <input type="text" class="form-control <?php echo "form_".$id; ?>" id="<?php echo "group_name_".$id; ?>" value="<?php _e($group->name); ?>" style="display: none;" /><br>
								  <span><?php _e("created on " . $formatted_date); ?></span>
							  </td>
							  <td>
								  <b><?php _e($count); ?></b><br>
								  <span><?php _e("members count"); ?></span>
							  </td>
							  <td>
								  <div class="symbol-group symbol-hover flex-nowrap">
								  <?php 
									foreach ($first10 as $member) { 
										$arr = array("button-warning", "button-success", "button-danger", "button-primary", "button-info");
                                        shuffle($arr);
								  ?>
									  <div class="symbol symbol-35px symbol-circle" title="<?php echo $member->app_member_name; ?>">
										  <span class="symbol-label <?php echo $arr[0]; ?> text-inverse-warning fw-bold"><?php echo substr($member->app_member_name, 0, 1); ?></span>
                                 	  </div>
								  <?php } ?>
								  </div>
							  </td>
							  <td>
								  <div id="<?php echo "btn_group_1_".$id; ?>">
								  	<a href="admin.php?page=web2application-app-members-club&tab=add-group-member&group_id=<?php echo $id; ?>">Add/Remove Member</a> | 
									<?php if ($count >= 1) { ?>
								  	<a href="admin.php?page=web2application-app-members-club&tab=send-group-push&group_id=<?php echo $id; ?>">Send Push</a> | 
									<?php } else { ?>
									<a href="javascript:no_group_members();">Send Push</a> | 
									<?php } ?>
								  	<a href="javascript:edit_group('<?php echo $id; ?>');">Edit</a> | 
								  	<a href="javascript:delete_group('<?php echo $id; ?>');">Delete</a>
								  </div>
								  <div id="<?php echo "btn_group_2_".$id; ?>" style="display: none;">
								  	<a href="javascript:update_group('<?php echo $id; ?>');">Save</a> | 
								  	<a href="javascript:cancel_group_edit('<?php echo $id; ?>');">Cancel</a>
								  </div>
							  </td>
							</tr>

						<?php } ?>
						</tbody>
					</table>
				</div>
				<?php
				break;
			case 'automations':
				?>
				<div class="my-section" style="margin-top:20px;">
					<h3><?php _e('Automations', 'web2application'); ?></h3>
					<table class="form-table stripe" style="margin-top:20px;">
						<thead class="thead-dark">
							<tr>
								<th scope="col"><?php _e('No.', 'web2application'); ?></th>
								<th scope="col" style="width: 80%"><?php _e('Details', 'web2application'); ?></th>
								<th scope="col" style="width: 15%"><?php _e('Action', 'web2application'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
								// iterate
								foreach ($row->automations as $num => $automation) {
									// id
                                	$id = $automation->id;
									
									// format start date
									$start_date = date_create($automation->start_date);
                                    $formatted_start_date = date_format($start_date,"d M Y");
                                                        
                                    // format end date
                                    $end_date = date_create($automation->end_date);
                                    $formatted_end_date = date_format($end_date,"d M Y");
							?>
							<tr>
								<td style="vertical-align: top;"><?php _e($num+1); ?></td>
								<td>
									<table>
										<tr>
											<td><b><?php _e("Type"); ?></b></td>
											<td>
												<a href="#"><?php _e($automation->automation_type); ?></a>
												<?php if ($automation->automation_type == "Recurring push to group") { ?>
												<br><span><?php _e("from " . $formatted_start_date . " to " . $formatted_end_date . " at " . $automation->time_of_day); ?></span>
												<?php } ?>
											</td>
										</tr>
										<tr>
											<td><b><?php _e("Assigned to Group?"); ?></b></td>
											<td><?php _e($automation->name); ?></td>
										</tr>
										<tr>
											<td><b><?php _e("Send as Email?"); ?></b></td>
											<td><?php _e($automation->send_as_email == 1 ? "yes" : "no"); ?></td>
										</tr>
										<tr>
											<td><b><?php _e("Push Title"); ?></b></td>
											<td><?php _e($automation->push_title); ?></td>
										</tr>
										<tr>
											<td><b><?php _e("Push Message"); ?></b></td>
											<td><?php _e($automation->push_message); ?></td>
										</tr>
										<tr>
											<td><b><?php _e("Push Link"); ?></b></td>
											<td><?php _e($automation->push_link); ?></td>
										</tr>
										<?php if ($automation->send_as_email == 1) { ?>
										<tr>
											<td><b><?php _e("Email Subject"); ?></b></td>
											<td><?php _e($automation->email_subject); ?></td>
										</tr>
										<tr>
											<td><b><?php _e("Email Body"); ?></b></td>
											<td><?php _e(substr($automation->email_body, 0, 200)); ?></td>
										</tr>
										<?php } ?>
									</table>
								</td>
								<td style="vertical-align: top;">
									<?php if ($automation->is_enabled == "0") { ?>
                            		<a href="javascript:play_automation('<?php echo $id; ?>');">Play</a> | 
                            		<?php } else { ?>
									<a href="javascript:stop_automation('<?php echo $id; ?>');">Pause</a> | 
                            		<?php } ?>
									<a href="admin.php?page=web2application-app-members-club&tab=add-automation&id=<?php echo $id; ?>">Edit</a> | 
									<a href="javascript:delete_automation('<?php echo $id; ?>');">Delete</a>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<p class="submit">
						<a href="admin.php?page=web2application-app-members-club&tab=add-automation" class="button button-primary">Create New Automation</a>
					</p>
				</div>
				<?php
				break;
			case 'settings':
				?>
				<form method="post">
					<div class="my-section" style="margin-top:20px;">
						<h3><?php _e('Settings', 'web2application'); ?></h3>
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><label><?php _e('Enable Signup Form','web2application'); ?></label></th>
									<td>
										<table>
											<tr>
												<td><label for="w2a_signup_form1"><input type="radio" id="w2a_signup_form1" name="data[signup_form]" value="1" <?php echo ($row->settings->signup_form == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Yes', 'web2application'); ?></label></td>
												<td><label for="w2a_signup_form0"><input type="radio" id="w2a_signup_form0" name="data[signup_form]" value="0" <?php echo ($row->settings->signup_form == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('No', 'web2application'); ?></label></td>
											</tr>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="my-section" style="margin-top:20px;" id="signup-form-settings">
						<h3><?php _e('Signup Form Setting', 'web2application'); ?></h3>
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><label><?php _e('Form Label Title','web2application'); ?></label></th>
									<td><input name="data[form_label_title]" type="text" value="<?php echo ($row->settings->form_label_title); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Form Label Description','web2application'); ?></label></th>
									<td><input name="data[form_label_desc]" type="text" value="<?php echo ($row->settings->form_label_desc); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Form Label for Name Field','web2application'); ?></label></th>
									<td><input name="data[form_label_name]" type="text" value="<?php echo ($row->settings->form_label_name); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Form Label for Email Field','web2application'); ?></label></th>
									<td><input name="data[form_label_email]" type="text" value="<?php echo ($row->settings->form_label_email); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Form Label for Phone Field','web2application'); ?></label></th>
									<td>
										<table>
											<tr>
												<td><input name="data[form_label_phone]" type="text" value="<?php echo ($row->settings->form_label_phone); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
												<td width="50"></td>
												<td><label><?php _e('Active?','web2application'); ?></label></td>
												<td><label><input type="radio" name="data[form_label_phone_active]" value="1" <?php echo ($row->settings->form_label_phone_active == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Yes', 'web2application'); ?></label></td>
												<td><label><input type="radio" name="data[form_label_phone_active]" value="0" <?php echo ($row->settings->form_label_phone_active == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('No', 'web2application'); ?></label></td>
												<td width="50"></td>
												<td><label><?php _e('Required?','web2application'); ?></label></td>
												<td><label><input type="radio" name="data[form_label_phone_required]" value="1" <?php echo ($row->settings->form_label_phone_required == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Yes', 'web2application'); ?></label></td>
												<td><label><input type="radio" name="data[form_label_phone_required]" value="0" <?php echo ($row->settings->form_label_phone_required == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('No', 'web2application'); ?></label></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Form Label for Birth Date Field','web2application'); ?></label></th>
									<td>
										<table>
											<tr>
												<td><input name="data[form_label_birth]" type="text" value="<?php echo ($row->settings->form_label_birth); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
												<td width="50"></td>
												<td><label><?php _e('Active?','web2application'); ?></label></td>
												<td><label><input type="radio" name="data[form_label_birth_active]" value="1" <?php echo ($row->settings->form_label_birth_active == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Yes', 'web2application'); ?></label></td>
												<td><label><input type="radio" name="data[form_label_birth_active]" value="0" <?php echo ($row->settings->form_label_birth_active == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('No', 'web2application'); ?></label></td>
												<td width="50"></td>
												<td><label><?php _e('Required?','web2application'); ?></label></td>
												<td><label><input type="radio" name="data[form_label_birth_required]" value="1" <?php echo ($row->settings->form_label_birth_required == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Yes', 'web2application'); ?></label></td>
												<td><label><input type="radio" name="data[form_label_birth_required]" value="0" <?php echo ($row->settings->form_label_birth_required == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('No', 'web2application'); ?></label></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Form Label for Group/Category Field','web2application'); ?></label></th>
									<td>
										<table>
											<tr>
												<td><input name="data[form_label_group]" type="text" value="<?php echo ($row->settings->form_label_group); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
												<td width="50"></td>
												<td><label><?php _e('Active?','web2application'); ?></label></td>
												<td><label><input type="radio" name="data[form_label_group_active]" value="1" <?php echo ($row->settings->form_label_group_active == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Yes', 'web2application'); ?></label></td>
												<td><label><input type="radio" name="data[form_label_group_active]" value="0" <?php echo ($row->settings->form_label_group_active == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('No', 'web2application'); ?></label></td>
												<td width="50"></td>
												<td><label><?php _e('Required?','web2application'); ?></label></td>
												<td><label><input type="radio" name="data[form_label_group_required]" value="1" <?php echo ($row->settings->form_label_group_required == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Yes', 'web2application'); ?></label></td>
												<td><label><input type="radio" name="data[form_label_group_required]" value="0" <?php echo ($row->settings->form_label_group_required == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('No', 'web2application'); ?></label></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Submit Button Name','web2application'); ?></label></th>
									<td>
										<table>
											<tr>
												<td><input name="data[form_label_submit]" type="text" value="<?php echo ($row->settings->form_label_submit); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
												<td width="50"></td>
												<td><label><?php _e('Background Color','web2application'); ?></label></td>
												<td><input name="data[form_label_submit_bgcolor]" type="text" value="<?php echo ($row->settings->form_label_submit_bgcolor); ?>" class="form-control col-md-3" <?php if ($disabled) { echo "disabled"; } ?> /></td>
												<td width="50"></td>
												<td><label><?php _e('Text Color','web2application'); ?></label></td>
												<td><input name="data[form_label_submit_text_color]" type="text" value="<?php echo ($row->settings->form_label_submit_text_color); ?>" class="form-control col-md-3" <?php if ($disabled) { echo "disabled"; } ?> /></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Delete Profile Button Name','web2application'); ?></label></th>
									<td>
										<table>
											<tr>
												<td><input name="data[form_label_delete]" type="text" value="<?php echo ($row->settings->form_label_delete); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
												<td width="50"></td>
												<td><label><?php _e('Background Color','web2application'); ?></label></td>
												<td><input name="data[form_label_delete_bgcolor]" type="text" value="<?php echo ($row->settings->form_label_delete_bgcolor); ?>" class="form-control col-md-3" <?php if ($disabled) { echo "disabled"; } ?> /></td>
												<td width="50"></td>
												<td><label><?php _e('Text Color','web2application'); ?></label></td>
												<td><input name="data[form_label_delete_text_color]" type="text" value="<?php echo ($row->settings->form_label_delete_text_color); ?>" class="form-control col-md-3" <?php if ($disabled) { echo "disabled"; } ?> /></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Show Edit Member Button in?','web2application'); ?></label></th>
									<td>
										<table>
											<tr>
												<td><label for="w2a_show_edit_member_in1"><input type="radio" id="w2a_show_edit_member_in1" name="data[show_edit_member_in]" value="1" <?php echo ($row->settings->show_edit_member_in == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Yes', 'web2application'); ?></label></td>
												<td width="337"><label for="w2a_show_edit_member_in0"><input type="radio" id="w2a_show_edit_member_in0" name="data[show_edit_member_in]" value="0" <?php echo ($row->settings->show_edit_member_in == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('No', 'web2application'); ?></label></td>
												<td width="50"></td>
												<td><label><?php _e('Background Color','web2application'); ?></label></td>
												<td><input name="data[android_member_button_bgcolor]" type="text" value="<?php echo ($row->settings->android_member_button_bgcolor); ?>" class="form-control col-md-3" <?php if ($disabled) { echo "disabled"; } ?> /></td>
												<td width="50"></td>
												<td><label><?php _e('Text Color','web2application'); ?></label></td>
												<td><input name="data[android_member_button_text_color]" type="text" value="<?php echo ($row->settings->android_member_button_text_color); ?>" class="form-control col-md-3" <?php if ($disabled) { echo "disabled"; } ?> /></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Confirm Title to Delete Profile','web2application'); ?></label></th>
									<td><input name="data[form_label_confirm]" type="text" value="<?php echo ($row->settings->form_label_confirm); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Confirm Text Description','web2application'); ?></label></th>
									<td><input name="data[form_label_confirm_text]" type="text" value="<?php echo ($row->settings->form_label_confirm_text); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Cancel Button Name','web2application'); ?></label></th>
									<td><input name="data[form_label_confirm_cancel]" type="text" value="<?php echo ($row->settings->form_label_confirm_cancel); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Yes, Delete Button Name','web2application'); ?></label></th>
									<td><input name="data[form_label_confirm_yes]" type="text" value="<?php echo ($row->settings->form_label_confirm_yes); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Select Group Title','web2application'); ?></label></th>
									<td><input name="data[form_label_select_group_title]" type="text" value="<?php echo ($row->settings->form_label_select_group_title); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Done Button Name','web2application'); ?></label></th>
									<td><input name="data[form_label_done]" type="text" value="<?php echo ($row->settings->form_label_done); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Enable Form Webhook?','web2application'); ?></label></th>
									<td>
										<table>
											<tr>
												<td><label><input type="radio" id="w2a_form_webhook_status_1" name="data[form_webhook_status]" value="1" <?php echo ($row->settings->form_webhook_status == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Yes', 'web2application'); ?></label></td>
												<td><label><input type="radio" id="w2a_form_webhook_status_0" name="data[form_webhook_status]" value="0" <?php echo ($row->settings->form_webhook_status == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('No', 'web2application'); ?></label></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Webhook URL','web2application'); ?></label></th>
									<td><input name="data[form_webhook_url]" type="text" value="<?php echo ($row->settings->form_webhook_url); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
							</tbody>
						</table><br>
					</div>
					<div class="my-section" style="margin-top:20px;">
						<h3><?php _e('SMTP Settings', 'web2application'); ?></h3>
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><label><?php _e('SMTP Host','web2application'); ?></label></th>
									<td><input name="data[smtp_host]" type="text" value="<?php echo ($row->settings->smtp_host); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('SMTP Port','web2application'); ?></label></th>
									<td><input name="data[smtp_port]" type="text" value="<?php echo ($row->settings->smtp_port); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('SMTP Username','web2application'); ?></label></th>
									<td><input name="data[smtp_username]" type="text" value="<?php echo ($row->settings->smtp_username); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('SMTP Password','web2application'); ?></label></th>
									<td><input name="data[smtp_password]" type="text" value="<?php echo ($row->settings->smtp_password); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('SMTP Encryption','web2application'); ?></label></th>
									<td><select class="form-control col-md-4 select2" name="data[smtp_encryption]">
										  <option value="tls" <?php echo ($row->settings->smtp_encryption == "tls") ? "selected" : ""; ?>><?php _e('TLS', 'web2application'); ?></option>
										  <option value="ssl" <?php echo ($row->settings->smtp_encryption == "ssl") ? "selected" : ""; ?>><?php _e('SSL', 'web2application'); ?></option>
										  <option value="none" <?php echo ($row->settings->smtp_encryption == "none") ? "selected" : ""; ?>><?php _e('No Encryption', 'web2application'); ?></option>
										</select>
									</td>
								</tr>
							</tbody>
						</table>
						<?php wp_nonce_field('w2a_app_members', 'w2a_app_members_update_settings'); ?>
						<p class="submit">
							<input type="submit" name="updateSettings" class="button button-primary" value="<?php _e('Update Settings', 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> />
						</p>
					</div>
				</form>
				<?php
			break;
		case 'edit-member':
				// init
				$sMember = new stdClass();
				$myGroup = array();
				
				// iterate
				foreach ($row->members as $member) {
					// check
					if ($member->id == trim($_GET['member_id'])) {
						// get member from array
						$sMember = $member;
						
						// get groups
						$myGroup = explode(", ",$sMember->group);
					}
				}
				?>
				<form method="post">
					<div class="my-section" style="margin-top:20px;">
						<h3><?php _e('Edit Member\'s Detail', 'web2application'); ?></h3>
						<input name="data[app_id]" type="hidden" value="<?php echo $appId; ?>" />
						<input name="data[id]" type="hidden" value="<?php echo $sMember->id; ?>" />
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><label><?php _e('Member\'s Name','web2application'); ?></label></th>
									<td><input name="data[app_member_name]" type="text" class="form-control col-md-4" required value="<?php _e($sMember->app_member_name, 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Email','web2application'); ?></label></th>
									<td><input name="data[app_member_email]" type="text" class="form-control col-md-4" required readonly value="<?php _e($sMember->app_member_email, 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Phone','web2application'); ?></label></th>
									<td><input name="data[app_member_phone]" type="text" class="form-control col-md-4" value="<?php _e($sMember->app_member_phone, 'web2application'); ?>" required <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Date of Birth','web2application'); ?></label></th>
									<td><input name="data[app_member_birthday]" type="text" class="form-control col-md-4 datepicker" value="<?php _e($sMember->app_member_birthday, 'web2application'); ?>" required <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Is Tester?','web2application'); ?></label></th>
									<td><select class="form-control col-md-4 select2" name="data[is_tester]" id="select-type">
										  	<option value="1" <?php echo ($sMember->is_tester == 1) ? "selected" : ""; ?>><?php _e('yes', 'web2application'); ?></option>
										  	<option value="0" <?php echo ($sMember->is_tester == 0) ? "selected" : ""; ?>><?php _e('no', 'web2application'); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Source','web2application'); ?></label></th>
									<td><input name="data[member_source]" type="text" class="form-control col-md-4" value="<?php _e($sMember->member_source, 'web2application'); ?>" required <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Is Blocked?','web2application'); ?></label></th>
									<td><select class="form-control col-md-4 select2" name="data[app_member_blocked]" id="select-type">
										  	<option value="yes" <?php echo ($sMember->app_member_blocked == "yes") ? "selected" : ""; ?>><?php _e('yes', 'web2application'); ?></option>
										  	<option value="no" <?php echo ($sMember->app_member_blocked == "no") ? "selected" : ""; ?>><?php _e('no', 'web2application'); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row" valign="top"><label><?php _e('Select Category/Group','web2application'); ?></label></th>
									<td>
										<?php foreach ($row->groups as $group) { ?>
										<label><input type="checkbox" name="ids[]" value="<?php _e($group->id, 'web2application'); ?>" <?php echo (in_array($group->name, $myGroup)) ? "checked" : ""; ?> /><?php _e($group->name, 'web2application'); ?></label><br>
										<?php } ?>
									</td>
								</tr>
							</tbody>
						</table>
						<?php wp_nonce_field('w2a_edit_member', 'w2a_edit_member'); ?>
						<p class="submit">
							<input type="submit" name="editMember" class="button button-primary" value="<?php _e('Update', 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> /> &nbsp; 
							<a class="button" href="admin.php?page=web2application-app-members-club&tab=members-list">Cancel</a>
						</p>
					</div>
				</form>
				<?php
				break;
		case 'add-group-member':
				// init
				$sGroup = new stdClass();
				$myMembers = array();
				
				// iterate
				foreach ($row->groups as $group) {
					// check
					if ($group->id == trim($_GET['group_id'])) {
						// get group from array
						$sGroup = $group;
						
						// iterate group members
						foreach ($group->members as $groupMember) {
							// add member id
							array_push($myMembers, $groupMember->member_id);
						}
					}
				}
				?>
				<form method="post">
					<div class="my-section" style="margin-top:20px;">
						<h3><?php _e('Add/Remove Members to a Group', 'web2application'); ?></h3>
						<p><?php _e('Check members that you want to include in this group.','web2application'); ?></p>
						<input name="data[app_id]" type="hidden" value="<?php echo $appId; ?>" />
						<input name="data[id]" type="hidden" value="<?php echo $sGroup->id; ?>" />
						<table class="form-table">
							<tbody>
								<tr>
									<td>
										<?php foreach ($row->members as $member) { ?>
										<label><input type="checkbox" name="ids[]" value="<?php _e($member->id, 'web2application'); ?>" <?php echo (in_array($member->id, $myMembers)) ? "checked" : ""; ?> /><?php _e($member->app_member_name, 'web2application'); ?></label><br>
										<?php } ?>
									</td>
								</tr>
							</tbody>
						</table>
						<?php wp_nonce_field('w2a_add_group_member', 'w2a_add_group_member'); ?>
						<p class="submit">
							<input type="submit" name="addGroupMember" class="button button-primary" value="<?php _e('Update', 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> /> &nbsp; 
							<a class="button" href="admin.php?page=web2application-app-members-club&tab=groups">Cancel</a>
						</p>
					</div>
				</form>
				<?php
				break;
		case 'add-automation':
				// init
				$object = new stdClass();
				// get object from array
				// iterate
				foreach ($row->automations as $automation) {
					// check
					if ($automation->id == trim($_GET['id'])) {
						$object = $automation;
					}
				}
				?>
				<form method="post">
					<div class="my-section" style="margin-top:20px;">
						<h3><?php _e('Create New Automation', 'web2application'); ?></h3>
						<input name="data[app_id]" type="hidden" value="<?php echo $appId; ?>" />
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><label><?php _e('Please Select the Automation You Like to Add','web2application'); ?></label></th>
									<td><select class="form-control col-md-4 select2" name="data[automation_type]" id="select-type">
										  	<option value=""><?php _e('- select -', 'web2application'); ?></option>
										  	<option value="Birthday" <?php echo ($object->automation_type == "Birthday") ? "selected" : ""; ?>><?php _e('Birthday', 'web2application'); ?></option>
										  	<option value="Recurring push to group" <?php echo ($object->automation_type == "Recurring push to group") ? "selected" : ""; ?>><?php _e('Recurring push to group', 'web2application'); ?></option>
										  	<option value="Respond to member registration" <?php echo ($object->automation_type == "Respond to member registration") ? "selected" : ""; ?>><?php _e('Respond to member registration', 'web2application'); ?></option>
										</select>
									</td>
								</tr>
								<tr class="recurring-inputs" style="display: <?php echo ($object->automation_type == "Recurring push to group") ? "" : "none"; ?>;">
									<th scope="row"><label><?php _e('Please Select the Group','web2application'); ?></label></th>
									<td><select class="form-control col-md-4 select2" name="data[group_id]">
										  	<option value="0"><?php _e('- select -', 'web2application'); ?></option>
											<?php foreach ($row->groups as $group) { ?>
										  	<option value="<?php _e($group->id); ?>" <?php echo ($object->group_id == $group->id) ? "selected" : ""; ?>><?php _e($group->name, 'web2application'); ?></option>
											<?php } ?>
										</select>
									</td>
								</tr>
								<tr class="recurring-inputs" style="display: <?php echo ($object->automation_type == "Recurring push to group") ? "" : "none"; ?>;">
									<th scope="row"><label><?php _e('Recurring Start Date','web2application'); ?></label></th>
									<td><input name="data[start_date]" type="text" class="form-control col-md-4 datepicker" value="<?php _e($object->start_date, 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr class="recurring-inputs" style="display: <?php echo ($object->automation_type == "Recurring push to group") ? "" : "none"; ?>;">
									<th scope="row"><label><?php _e('Recurring End Date','web2application'); ?></label></th>
									<td><input name="data[end_date]" type="text" class="form-control col-md-4 datepicker" value="<?php _e($object->end_date, 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr class="recurring-inputs" style="display: <?php echo ($object->automation_type == "Recurring push to group") ? "" : "none"; ?>;">
									<th scope="row"><label><?php _e('Recurring Time of Day','web2application'); ?></label></th>
									<td><select class="form-control col-md-4 select2" name="data[time_of_day]">
										  <option value="00:00" <?php echo ($object->time_of_day == "00:00") ? "selected" : ""; ?>><?php _e('00:00', 'web2application'); ?></option>
										  <option value="01:00" <?php echo ($object->time_of_day == "01:00") ? "selected" : ""; ?>><?php _e('01:00', 'web2application'); ?></option>
										  <option value="02:00" <?php echo ($object->time_of_day == "02:00") ? "selected" : ""; ?>><?php _e('02:00', 'web2application'); ?></option>
										  <option value="03:00" <?php echo ($object->time_of_day == "03:00") ? "selected" : ""; ?>><?php _e('03:00', 'web2application'); ?></option>
										  <option value="04:00" <?php echo ($object->time_of_day == "04:00") ? "selected" : ""; ?>><?php _e('04:00', 'web2application'); ?></option>
										  <option value="05:00" <?php echo ($object->time_of_day == "05:00") ? "selected" : ""; ?>><?php _e('05:00', 'web2application'); ?></option>
										  <option value="06:00" <?php echo ($object->time_of_day == "06:00") ? "selected" : ""; ?>><?php _e('06:00', 'web2application'); ?></option>
										  <option value="07:00" <?php echo ($object->time_of_day == "07:00") ? "selected" : ""; ?>><?php _e('07:00', 'web2application'); ?></option>
										  <option value="08:00" <?php echo ($object->time_of_day == "08:00") ? "selected" : ""; ?>><?php _e('08:00', 'web2application'); ?></option>
										  <option value="09:00" <?php echo ($object->time_of_day == "09:00") ? "selected" : ""; ?>><?php _e('09:00', 'web2application'); ?></option>
										  <option value="10:00" <?php echo ($object->time_of_day == "10:00") ? "selected" : ""; ?>><?php _e('10:00', 'web2application'); ?></option>
										  <option value="11:00 <?php echo ($object->time_of_day == "11:00") ? "selected" : ""; ?>"><?php _e('11:00', 'web2application'); ?></option>
										  <option value="12:00 <?php echo ($object->time_of_day == "12:00") ? "selected" : ""; ?>"><?php _e('12:00', 'web2application'); ?></option>
										  <option value="13:00 <?php echo ($object->time_of_day == "13:00") ? "selected" : ""; ?>"><?php _e('13:00', 'web2application'); ?></option>
										  <option value="14:00 <?php echo ($object->time_of_day == "14:00") ? "selected" : ""; ?>"><?php _e('14:00', 'web2application'); ?></option>
										  <option value="15:00 <?php echo ($object->time_of_day == "15:00") ? "selected" : ""; ?>"><?php _e('15:00', 'web2application'); ?></option>
										  <option value="16:00 <?php echo ($object->time_of_day == "16:00") ? "selected" : ""; ?>"><?php _e('16:00', 'web2application'); ?></option>
										  <option value="17:00 <?php echo ($object->time_of_day == "17:00") ? "selected" : ""; ?>"><?php _e('17:00', 'web2application'); ?></option>
										  <option value="18:00 <?php echo ($object->time_of_day == "18:00") ? "selected" : ""; ?>"><?php _e('18:00', 'web2application'); ?></option>
										  <option value="19:00 <?php echo ($object->time_of_day == "19:00") ? "selected" : ""; ?>"><?php _e('19:00', 'web2application'); ?></option>
										  <option value="20:00 <?php echo ($object->time_of_day == "20:00") ? "selected" : ""; ?>"><?php _e('20:00', 'web2application'); ?></option>
										  <option value="21:00 <?php echo ($object->time_of_day == "21:00") ? "selected" : ""; ?>"><?php _e('21:00', 'web2application'); ?></option>
										  <option value="22:00 <?php echo ($object->time_of_day == "22:00") ? "selected" : ""; ?>"><?php _e('22:00', 'web2application'); ?></option>
										  <option value="23:00 <?php echo ($object->time_of_day == "23:00") ? "selected" : ""; ?>"><?php _e('23:00', 'web2application'); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Enable Automation?','web2application'); ?></label></th>
									<td>
										<table>
											<tr>
												<td><label for="w2a_is_enabled_1"><input type="radio" id="w2a_is_enabled_1" name="data[is_enabled]" value="1" <?php echo ($object->is_enabled == "1" || trim($_GET['id']) == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Yes', 'web2application'); ?></label></td>
												<td><label for="w2a_is_enabled_0"><input type="radio" id="w2a_is_enabled_0" name="data[is_enabled]" value="0" <?php echo ($object->is_enabled == "0") ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('No', 'web2application'); ?></label></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Send as Email too:','web2application'); ?></label></th>
									<td>
										<table>
											<tr>
												<td><label for="w2a_send_as_email_1"><input type="radio" id="w2a_send_as_email_1" name="data[send_as_email]" value="1" <?php echo ($object->send_as_email == "1") ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('Yes', 'web2application'); ?></label></td>
												<td><label for="w2a_send_as_email_0"><input type="radio" id="w2a_send_as_email_0" name="data[send_as_email]" value="0" <?php echo ($object->send_as_email == "0" || trim($_GET['id']) == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /><?php _e('No', 'web2application'); ?></label></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Push Title','web2application'); ?></label></th>
									<td><input name="data[push_title]" type="text" class="form-control col-md-4" value="<?php _e($object->push_title, 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Push Message','web2application'); ?></label></th>
									<td><textarea class="form-control col-md-4" name="data[push_message]" rows="4" <?php if ($disabled) { echo "disabled"; } ?>><?php _e($object->push_message, 'web2application'); ?></textarea></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Push Link','web2application'); ?></label></th>
									<td><select name="data[push_link]" value="<?php echo 'http://'.$_SERVER['SERVER_NAME']; ?>" class="form-control col-md-4 select2">
											<option value="<?php echo get_home_url(); ?>"><?php _e('Home Page', 'web2application'); ?></option>

											<optgroup label="<?php _e('Last Posts', 'web2application'); ?>">
											<?php
											$recent_posts = wp_get_recent_posts();

											foreach( $recent_posts as $recent ){
												echo '<option value="' . get_site_url().'/?p='.$recent["ID"] . '">' . $recent["post_title"] . '</option>';
											}
											wp_reset_query();
											?>

											<optgroup label="<?php _e('Last Pages', 'web2application'); ?>">
											<?php
											$pages = get_pages(); 
											foreach( $pages as $page ){
												echo '<option value="' . get_site_url().'/?p='.$page->ID . '">' . $page->post_title .  '</option>';
											}
											wp_reset_query();
											?>

											<?php if ( class_exists( 'WooCommerce' ) ) { ?>
												<optgroup label="<?php _e('Last products', 'web2application'); ?>">
											<?php
												$args = array('post_type' => 'product', 
															  'posts_per_page' => 12);
												$loop = new WP_Query( $args );
												if ( $loop->have_posts() ) {
													while ( $loop->have_posts() ) : $loop->the_post();
														echo '<option value="' . get_site_url().'/?p=' . wc_get_product()->get_id() . '">' .  get_the_title() . '</option>';
													endwhile;
												} else {
													echo __( 'No products found' );
												}
												wp_reset_postdata();
											?>
											<?php } ?>
										</select>

										<p class="description"><?php _e('The page or post that the push will lead to', 'web2application'); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Rich Push Image','web2application'); ?></label></th>
									<?php if($row->app_paied != 'no') { ?>
									<td>  
									  <input id="big-image-url" type="text" name="data[android_big_image]" class="form-control col-md-4" value="<?php _e($object->android_big_image, 'web2application'); ?>" />
									  <input id="w2a-upload-button" type="button" class="button" value="Upload Or Select Image"  />
									  <p class="description"><?php _e('Please Select Image Or Paste Full Image Url. example : http://domain.com/image.jpg', 'web2application'); ?></p>
									</td>
									<?php } else { ?>
									<td><?php _e('Big image is available only to premium users', 'web2application'); ?></td>
									<?php } ?>
								</tr>
								<tr class="email-section" style="display: <?php echo ($object->send_as_email == "1") ? "" : "none"; ?>;">
									<th scope="row"><label><?php _e('Email Subject','web2application'); ?></label></th>
									<td><input name="data[email_subject]" type="text" class="form-control col-md-4" value="<?php _e($object->email_subject, 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr class="email-section" style="display: <?php echo ($object->send_as_email == "1") ? "" : "none"; ?>;">
									<th scope="row"><label><?php _e('Email Body','web2application'); ?></label></th>
									<td><textarea class="form-control col-md-4" name="data[email_body]" rows="4" <?php if ($disabled) { echo "disabled"; } ?>><?php _e($object->email_body, 'web2application'); ?></textarea></td>
								</tr>
							</tbody>
						</table>
						<?php wp_nonce_field('w2a_add_automation', 'w2a_add_automation'); ?>
						<p class="submit">
							<input type="submit" name="addAutomation" class="button button-primary" value="<?php _e('Submit', 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> /> &nbsp; 
							<a class="button" href="admin.php?page=web2application-app-members-club&tab=automations">Cancel</a>
						</p>
					</div>
				</form>
				<?php
				break;
		case 'send-special-push':
				?>
				<form method="post">
					<div class="my-section" style="margin-top:20px;">
						<h3><?php _e('Send Special Push', 'web2application'); ?></h3>
						<input name="app_member_email" type="hidden" value="<?php echo trim($_GET['member_email']); ?>" />
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><label><?php _e('Push Title','web2application'); ?></label></th>
									<td><input name="push_title" type="text" value="<?php echo get_bloginfo( 'name' );?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> />
									<p class="description"><?php _e('Please Enter Your Push Title', 'web2application'); ?></p></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Push Message','web2application'); ?></label></th>
									<td><input name="push_message" type="text" value="" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> />
									<p class="description"><?php _e('Please Enter Your Message', 'web2application'); ?></p></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Push Schedule','web2application'); ?></label></th>
									<td>
										<table>
											<tr>
												<td><label for="send-now"><input type="radio" id="send-now" name="push_schedule" value="send_now" checked /><?php _e('Send Now', 'web2application'); ?></label></td>
												<?php if($row->app_paied != 'no') { ?>
												<td>
													<label for="schedule-push"><input type="radio" id="schedule-push" name="push_schedule" value="schedule_push" /><?php _e('Schedule this push', 'web2application'); ?></label>
													<input name="push_date" type="text" id="datepicker" value="" class="form-control col-md-2" <?php if ($disabled) { echo "disabled"; } ?> style="width: 100px; display: none;" />
													<input name="push_time" type="text" id="timepicker" value="" class="form-control col-md-2" <?php if ($disabled) { echo "disabled"; } ?> style="width: 100px; display: none;" />
												</td>
												<?php } else { ?>
												<td><?php _e('Schedule push notifications is available only to premium users', 'web2application'); ?></td>
												<?php } ?>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Push Image','web2application'); ?></label></th>
									<td>  
									  <input id="image-url" type="text" name="image" value=""/>
									  <input id="w2a-upload-button" type="button" class="button" value="Upload Or Select Image"  />
									  <p class="description"><?php _e('Please Select Image Or Paste Full Image Url. example : http://domain.com/image.jpg', 'web2application'); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Rich Push Image','web2application'); ?></label></th>
									<?php if($row->app_paied != 'no') { ?>
									<td>  
									  <input id="big-image-url" type="text" name="big_image" value=""/>
									  <input id="w2a-upload-button2" type="button" class="button" value="Upload Or Select Image"  />
									  <p class="description"><?php _e('Please Select Image Or Paste Full Image Url. example : http://domain.com/image.jpg', 'web2application'); ?></p>
									</td>
									<?php } else { ?>
									<td><?php _e('Big image is available only to premium users', 'web2application'); ?></td>
									<?php } ?>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Push Link','web2application'); ?></label></th>
									<td>
										<select name="Push_Link" type="text" id="Push_Link" value="<?php echo 'http://'.$_SERVER['SERVER_NAME']; ?>" class="form-control col-md-4">
											<option value="<?php echo get_home_url(); ?>"><?php _e('Home Page', 'web2application'); ?></option>
											<optgroup label="<?php _e('Last Posts', 'web2application'); ?>">
											<?php
												$recent_posts = wp_get_recent_posts();

												foreach( $recent_posts as $recent ){
													echo '<option value="' . get_site_url().'/?p='.$recent["ID"] . '">' . $recent["post_title"] . '</option>';
												}
												wp_reset_query();
											?>

											<optgroup label="<?php _e('Last Pages', 'web2application'); ?>">
											<?php
												$pages = get_pages(); 
												foreach( $pages as $page ){
													echo '<option value="' . get_site_url().'/?p='.$page->ID . '">' . $page->post_title .  '</option>';
												}
												wp_reset_query();
											?>

											<?php if ( class_exists( 'WooCommerce' ) ) { ?>
											<optgroup label="<?php _e('Last products', 'web2application'); ?>">
											<?php					
												$args = array('post_type' => 'product', 
															  'posts_per_page' => 12);
												$loop = new WP_Query( $args );
												if ( $loop->have_posts() ) {
													while ( $loop->have_posts() ) : $loop->the_post();
														echo '<option value="' . get_site_url().'/?p=' . wc_get_product()->get_id() . '">' .  get_the_title() . '</option>';
													endwhile;
												} else {
													echo __( 'No products found' );
												}
												wp_reset_postdata();
											?>
											<?php } ?>
										</select>

										<p class="description"><?php _e('The page or post that the push will lead to', 'web2application'); ?></p>
									</td>
								</tr>
							</tbody>
						</table> 
						<?php wp_nonce_field('w2a_send_special_push', 'w2a_send_special_push'); ?>
						<p class="submit">
							<input type="submit" name="sendSpecialPush" class="button button-primary" value="<?php _e('Send Push Notification', 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> /> &nbsp; 
							<a class="button" href="admin.php?page=web2application-app-members-club&tab=members-list">Cancel</a>
						</p>
					</div>
				</form>
				<?php
				break;
		case 'send-group-push':
				?>
				<form method="post">
					<div class="my-section" style="margin-top:20px;">
						<h3><?php _e('Send Push to Group', 'web2application'); ?></h3>
						<input name="group_id" type="hidden" value="<?php echo trim($_GET['group_id']); ?>" />
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><label><?php _e('Push Title','web2application'); ?></label></th>
									<td><input name="push_title" type="text" value="<?php echo get_bloginfo( 'name' );?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> />
									<p class="description"><?php _e('Please Enter Your Push Title', 'web2application'); ?></p></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Push Message','web2application'); ?></label></th>
									<td><input name="push_message" type="text" value="" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> />
									<p class="description"><?php _e('Please Enter Your Message', 'web2application'); ?></p></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Push Schedule','web2application'); ?></label></th>
									<td>
										<table>
											<tr>
												<td><label for="send-now"><input type="radio" id="send-now" name="push_schedule" value="send_now" checked /><?php _e('Send Now', 'web2application'); ?></label></td>
												<?php if($row->app_paied != 'no') { ?>
												<td>
													<label for="schedule-push"><input type="radio" id="schedule-push" name="push_schedule" value="schedule_push" /><?php _e('Schedule this push', 'web2application'); ?></label>
													<input name="push_date" type="text" id="datepicker" value="" class="form-control col-md-2" <?php if ($disabled) { echo "disabled"; } ?> style="width: 100px; display: none;" />
													<input name="push_time" type="text" id="timepicker" value="" class="form-control col-md-2" <?php if ($disabled) { echo "disabled"; } ?> style="width: 100px; display: none;" />
												</td>
												<?php } else { ?>
												<td><?php _e('Schedule push notifications is available only to premium users', 'web2application'); ?></td>
												<?php } ?>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Push Image','web2application'); ?></label></th>
									<td>  
									  <input id="image-url" type="text" name="image" value=""/>
									  <input id="w2a-upload-button" type="button" class="button" value="Upload Or Select Image"  />
									  <p class="description"><?php _e('Please Select Image Or Paste Full Image Url. example : http://domain.com/image.jpg', 'web2application'); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Rich Push Image','web2application'); ?></label></th>
									<?php if($row->app_paied != 'no') { ?>
									<td>  
									  <input id="big-image-url" type="text" name="big_image" value=""/>
									  <input id="w2a-upload-button2" type="button" class="button" value="Upload Or Select Image"  />
									  <p class="description"><?php _e('Please Select Image Or Paste Full Image Url. example : http://domain.com/image.jpg', 'web2application'); ?></p>
									</td>
									<?php } else { ?>
									<td><?php _e('Big image is available only to premium users', 'web2application'); ?></td>
									<?php } ?>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Push Link','web2application'); ?></label></th>
									<td>
										<select name="Push_Link" type="text" id="Push_Link" value="<?php echo 'http://'.$_SERVER['SERVER_NAME']; ?>" class="form-control col-md-4">
											<option value="<?php echo get_home_url(); ?>"><?php _e('Home Page', 'web2application'); ?></option>
											<optgroup label="<?php _e('Last Posts', 'web2application'); ?>">
											<?php
												$recent_posts = wp_get_recent_posts();

												foreach( $recent_posts as $recent ){
													echo '<option value="' . get_site_url().'/?p='.$recent["ID"] . '">' . $recent["post_title"] . '</option>';
												}
												wp_reset_query();
											?>

											<optgroup label="<?php _e('Last Pages', 'web2application'); ?>">
											<?php
												$pages = get_pages(); 
												foreach( $pages as $page ){
													echo '<option value="' . get_site_url().'/?p='.$page->ID . '">' . $page->post_title .  '</option>';
												}
												wp_reset_query();
											?>

											<?php if ( class_exists( 'WooCommerce' ) ) { ?>
											<optgroup label="<?php _e('Last products', 'web2application'); ?>">
											<?php					
												$args = array('post_type' => 'product', 
															  'posts_per_page' => 12);
												$loop = new WP_Query( $args );
												if ( $loop->have_posts() ) {
													while ( $loop->have_posts() ) : $loop->the_post();
														echo '<option value="' . get_site_url().'/?p=' . wc_get_product()->get_id() . '">' .  get_the_title() . '</option>';
													endwhile;
												} else {
													echo __( 'No products found' );
												}
												wp_reset_postdata();
											?>
											<?php } ?>
										</select>

										<p class="description"><?php _e('The page or post that the push will lead to', 'web2application'); ?></p>
									</td>
								</tr>
							</tbody>
						</table> 
						<?php wp_nonce_field('w2a_send_group_push', 'w2a_send_group_push'); ?>
						<p class="submit">
							<input type="submit" name="sendGroupPush" class="button button-primary" value="<?php _e('Send Push Notification', 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> /> &nbsp; 
							<a class="button" href="admin.php?page=web2application-app-members-club&tab=members-list">Cancel</a>
						</p>
					</div>
				</form>
				<?php
				break;
		endswitch;
		?>
	</div>
	
</div>

<script>
$(document).ready(function() {
	// run this on members list, testers, groups and automations only
	// check
	<?php if (in_array($tab, array('members-list', 'testers', 'groups', 'automations'))) { ?>
	// datatables
	$('.form-table').DataTable();
	<?php } ?>
	
	
	// run in edit members only
	<?php if ($tab == 'edit-member') { ?>
	// date picker
	$('.datepicker').datepicker({
		dateFormat: 'yy-mm-dd'
	});
	<?php } ?>
	
	
	// run in add automation only
	<?php if ($tab == 'add-automation') { ?>
	// btn-add-group
	$('#select-type').on('change', function() {
		// get selected
		var value = $(this).val(); 
		
		// check
		if (value == "Recurring push to group") {
			$("#select-group").show();
			$(".recurring-inputs").show();
		} else {
			$("#select-group").hide();
			$(".recurring-inputs").hide();
		}
	});

	// date picker
	$('.datepicker').datepicker({
		dateFormat: 'yy-mm-dd'
	});
	
	// send email
    $("#w2a_send_as_email_1").click(function () {
        $(".email-section").show();
    });
    
    // do not send email
    $("#w2a_send_as_email_0").click(function () {
        $(".email-section").hide();
    });
	
	// image picker
	var w2aMediaUploader;
	
	$('#w2a-upload-button').click(function() {
		// If the uploader object has already been created, reopen the dialog
		if (w2aMediaUploader) {
		  w2aMediaUploader.open();
		  return;
		}
		// Extend the wp.media object
		w2aMediaUploader = wp.media.frames.file_frame = wp.media({
		  title: 'Choose Image',
		  button: {
		  text: 'Choose Image'
		}, multiple: false });
	
		// When a file is selected, grab the URL and set it as the text field's value
		w2aMediaUploader.on('select', function() {
		  attachment = w2aMediaUploader.state().get('selection').first().toJSON();
		  $('#big-image-url').val(attachment.url);
		});
		// Open the uploader dialog
		w2aMediaUploader.open();
	});
	<?php } ?>
	
	
	// run in send push only
	<?php if ($tab == 'send-special-push' || $tab == 'send-group-push') { ?>
	$('#schedule-push').click(function () {
        $('#datepicker').show();
        $('#timepicker').show();
    });

    $('#send-now').click(function () {
        $('#datepicker').hide();
        $('#timepicker').hide();
    });
	
	// date picker
	$('#datepicker').datepicker({
	    dateFormat: 'yy-mm-dd'
	});
	
	// time picker
	$('#timepicker').timepicker({
    	timeFormat: 'HH:mm',
    	interval: 5,
    	startTime: '00:00',
    	dynamic: false,
    	dropdown: true,
    	scrollbar: true
	});
	
	var w2aMediaUploader;
	var w2aMediaUploader2;
	
	$('#w2a-upload-button').click(function(e) {
		e.preventDefault();
		// If the uploader object has already been created, reopen the dialog
		  if (w2aMediaUploader) {
		  w2aMediaUploader.open();
		  return;
		}
		// Extend the wp.media object
		w2aMediaUploader = wp.media.frames.file_frame = wp.media({
		  title: 'Choose Image',
		  button: {
		  text: 'Choose Image'
		}, multiple: false });
	
		// When a file is selected, grab the URL and set it as the text field's value
		w2aMediaUploader.on('select', function() {
		  attachment = w2aMediaUploader.state().get('selection').first().toJSON();
		  $('#image-url').val(attachment.url);
		});
		// Open the uploader dialog
		w2aMediaUploader.open();
	});
		
	$('#w2a-upload-button2').click(function(e) {
		e.preventDefault();
		// If the uploader object has already been created, reopen the dialog
		  if (w2aMediaUploader2) {
		  w2aMediaUploader2.open();
		  return;
		}
		// Extend the wp.media object
		w2aMediaUploader2 = wp.media.frames.file_frame = wp.media({
		  title: 'Choose Image',
		  button: {
		  text: 'Choose Image'
		}, multiple: false });
	
		// When a file is selected, grab the URL and set it as the text field's value
		w2aMediaUploader2.on('select', function() {
		  attachment = w2aMediaUploader2.state().get('selection').first().toJSON();
		  $('#big-image-url').val(attachment.url);
		});
		// Open the uploader dialog
		w2aMediaUploader2.open();
	  });
	<?php } ?>
	
	
	// run in dashboard only
	<?php if ($tab == 'dashboard') { ?>
		// date range picker
		$('#daterange').daterangepicker({
				opens: 'left'
			}, function(start, end, label) {
				console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
				// add to url location
				window.location.href = "admin.php?page=web2application-app-members-club&tab=dashboard&start_date=" + start.format('YYYY-MM-DD') + "&end_date=" + end.format('YYYY-MM-DD');
		  	});
	
	
		// START MEMBER BY SOURCE //
    
    const chart1 = document.getElementById('kt_chart_1');
    
    new Chart(chart1, {
        type: 'doughnut',
        data: {
          labels: ['Apps Form', 'API', 'Plugin'],
          datasets: [{
            label: 'No. of Members',
            data: [<?php echo $row->dashboard->member_count_from_apps; ?>, <?php echo $row->dashboard->member_count_from_api; ?>, <?php echo $row->dashboard->member_count_from_plugin; ?>],
            backgroundColor: [
              'rgb(255, 99, 132)',
              'rgb(54, 162, 235)',
              'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
          }]
        }
      });
    
    // END MEMBER BY SOURCE //
    
    
    // START MEMBERS BY GROUP //
    
    const chart2 = document.getElementById('kt_chart_2');
    
    new Chart(chart2, {
        type: 'bar',
        data: {
          labels: <?php echo json_encode(array_column($row->dashboard->groups, 'name')); ?>,
          datasets: [{
            label: 'No. of Members per Group',
            data: <?php echo json_encode(array_column($row->dashboard->groups, 'total_members')); ?>,
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    
    // END MEMBERS BY GROUP //
    
    
    // START MEMBERSHIP BY DATE //
    
    const chart3 = document.getElementById('kt_chart_3');
    
    new Chart(chart3, {
        type: 'line',
        data: {
          labels: <?php echo json_encode(array_column($row->dashboard->membership_by_date, 'date_registered')); ?>,
          datasets: [{
            label: 'No. of Registered Users',
            data: <?php echo json_encode(array_column($row->dashboard->membership_by_date, 'reg_count')); ?>,
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
          }]
        }
      });
    
    // END MEMBERSHIP BY DATE //
    
    
    // START NO. OF BIRTHDAYS //
    
    const chart4 = document.getElementById('kt_chart_4');
    
    new Chart(chart4, {
        type: 'pie',
        data: {
          labels: <?php echo json_encode(array_column($row->dashboard->bday_by_month, 'month')); ?>,
          datasets: [{
            label: 'No. of Birthdays',
            data: <?php echo json_encode(array_column($row->dashboard->bday_by_month, 'count')); ?>,
            hoverOffset: 4
          }]
        }
      });
    
    // END NO. OF BIRTHDAYS //
    
    
    // START AUTOMATION //
    
    const chart5 = document.getElementById('kt_chart_5');
    
    new Chart(chart5, {
        type: 'polarArea',
        data: {
          labels: <?php echo json_encode(array_column($row->dashboard->automation_by_date, 'date_created')); ?>,
          datasets: [{
            label: 'No. of Automations',
            data: <?php echo json_encode(array_column($row->dashboard->automation_by_date, 'count')); ?>,
            hoverOffset: 4
          }]
        }
      });
    
    // END AUTOMATION //
	<?php } ?>
});
	
	
// MEMBERS LIST

function block_member(id) {
    Swal.fire({
            title: 'Block Member',
            text: "Are you sure you want to block this app member?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, block it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "https://web2application.com/w2a/user/ajax-functions.php",
                    data: "task=block_app_member&id="+id,
                    async: false
                }).always(function(data) {
                    window.location.href = "admin.php?page=web2application-app-members-club&tab=members-list";
                });
            }
        });
}

function unblock_member(id) {
    Swal.fire({
            title: 'Unblock Member',
            text: "Are you sure you want to unblock this app member?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, unblock it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "https://web2application.com/w2a/user/ajax-functions.php",
                    data: "task=unblock_app_member&id="+id,
                    async: false
                }).always(function(data) {
                    window.location.href = "admin.php?page=web2application-app-members-club&tab=members-list";
                });
            }
        });
}

function delete_member(id) {
    Swal.fire({
            title: 'Are you sure you want to delete this?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "https://web2application.com/w2a/user/ajax-functions.php",
                    data: "task=delete_app_member&id="+id,
                    async: false
                }).always(function(data) {
                    let timerInterval
                    Swal.fire({
                        title: 'Deleted!',
                        html: 'Item is now deleted!',
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                            timerInterval = setInterval(() => {
                                const content = Swal.getHtmlContainer()
                                if (content) {
                                    const b = content.querySelector('b')
                                    if (b) {
                                        b.textContent = Swal.getTimerLeft()
                                    }
                                }
                            }, 100)
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            window.location.href = "admin.php?page=web2application-app-members-club&tab=members-list";
                        }
                    });
                });
            }
        });
}


// GROUP

function edit_group(id) {
	$('.text_'+id).hide();
    $('.form_'+id).show();
	
	$('#btn_group_1_'+id).hide();
	$('#btn_group_2_'+id).show();
}

function cancel_group_edit(id) {
	$('.text_'+id).show();
	$('.form_'+id).hide();
	
	$('#btn_group_1_'+id).show();
	$('#btn_group_2_'+id).hide();
}

function update_group(id) {
    // get values
    var name   	= $('#group_name_'+id).val();

    $.ajax({
        type: "POST",
        url: "https://web2application.com/w2a/user/ajax-functions.php",
        data: "task=edit_group&id="+id+"&name="+name,
        async: false
    }).always(function(data) {
        window.location.href = "admin.php?page=web2application-app-members-club&tab=groups";
    });
}

function delete_group(id) {
    Swal.fire({
            title: 'Are you sure you want to delete this group?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "https://web2application.com/w2a/user/ajax-functions.php",
                    data: "task=delete_group&id="+id,
                    async: false
                }).always(function(data) {
                    let timerInterval
                    Swal.fire({
                        title: 'Deleted!',
                        html: 'Item is now deleted!',
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                            timerInterval = setInterval(() => {
                                const content = Swal.getHtmlContainer()
                                if (content) {
                                    const b = content.querySelector('b')
                                    if (b) {
                                        b.textContent = Swal.getTimerLeft()
                                    }
                                }
                            }, 100)
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            window.location.href = "admin.php?page=web2application-app-members-club&tab=groups";
                        }
                    });
                });
            }
        });
}
	
function no_group_members() {
	Swal.fire({
            title: 'No Group Members!',
            text: "Cant send push to this group as it doesnt have any members...",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Okay'
        }).then((result) => {
            
        });
}


// AUTOMATION

function play_automation(id) {
    Swal.fire({
            title: 'Start Automation',
            text: "You are about to play/start an automation. Are you sure you are going to do this?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, start it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "https://web2application.com/w2a/user/ajax-functions.php",
                    data: "task=start_automation&id="+id,
                    async: false
                }).always(function(data) {
                    let timerInterval
                    Swal.fire({
                        title: 'Automation Started!',
                        html: 'Automation has been successfully started!',
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                            timerInterval = setInterval(() => {
                                const content = Swal.getHtmlContainer()
                                if (content) {
                                    const b = content.querySelector('b')
                                    if (b) {
                                        b.textContent = Swal.getTimerLeft()
                                    }
                                }
                            }, 100)
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            window.location.href = "admin.php?page=web2application-app-members-club&tab=automations";
                        }
                    });
                });
            }
        });
}

function stop_automation(id) {
    Swal.fire({
            title: 'Pause Automation',
            text: "You are about to pause an automation. Are you sure you are going to do this?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, pause it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "https://web2application.com/w2a/user/ajax-functions.php",
                    data: "task=pause_automation&id="+id,
                    async: false
                }).always(function(data) {
                    let timerInterval
                    Swal.fire({
                        title: 'Automation Paused!',
                        html: 'Automation has been successfully paused!',
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                            timerInterval = setInterval(() => {
                                const content = Swal.getHtmlContainer()
                                if (content) {
                                    const b = content.querySelector('b')
                                    if (b) {
                                        b.textContent = Swal.getTimerLeft()
                                    }
                                }
                            }, 100)
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            window.location.href = "admin.php?page=web2application-app-members-club&tab=automations";
                        }
                    });
                });
            }
        });
}

function delete_automation(id) {
    Swal.fire({
            title: 'Are you sure you want to delete this?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "https://web2application.com/w2a/user/ajax-functions.php",
                    data: "task=delete_automation&id="+id,
                    async: false
                }).always(function(data) {
                    let timerInterval
                    Swal.fire({
                        title: 'Deleted!',
                        html: 'Item is now deleted!',
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                            timerInterval = setInterval(() => {
                                const content = Swal.getHtmlContainer()
                                if (content) {
                                    const b = content.querySelector('b')
                                    if (b) {
                                        b.textContent = Swal.getTimerLeft()
                                    }
                                }
                            }, 100)
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            window.location.href = "admin.php?page=web2application-app-members-club&tab=automations";
                        }
                    });
                });
            }
        });
}
</script>
