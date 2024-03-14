<?php 
require_once 'includes/Services/Soundcloud.php';

function displaySCUSettings()
{
	//TODO - form validation for track info
	$errors = '';
	$sc_client_id = '';
	$sc_client_secret = '';
	$soundcloud_access_token = '';
	$sc_redirect_uri = site_url().'/wp-admin/admin.php?page=wpshq_scu_plugin_options';
	$sc_comments = '';
	$sc_visibility = '';
	$sc_genre = '';
	$sc_track_desc = '';
	$sc_track_title  = '';
	$attached_file = '';
	
	if (isset($_POST['soundcloud_clear'])){
			//add the data to the wp_options table
		$options = array(
			'sc_client_id' => '',
			'sc_client_secret' => '',
			'sc_client_access_token' => '',
			'sc_redirect_uri' => ''
		);
		update_option('soundcloud_settings', $options); //store the results in WP options table
		echo '<div id="message" class="updated fade">';
		echo '<p>Settings Cleared</p>';
		echo '</div>';
	} else if (isset($_POST['soundcloud_update'])){
		if ($_POST['sc-client-id'] != "") {
			$sc_client_id = filter_var($_POST['sc-client-id'], FILTER_SANITIZE_STRING);
			if ($_POST['sc-client-id'] == "") {
				$errors .= 'Please enter a valid SoundCloud ID.<br/><br/>';
			}
		} else {
			$errors .= 'Please enter your SoundCloud ID.<br/>';
		}
		 
		if ($_POST['sc-client-secret'] != "") {
			$sc_client_secret = filter_var($_POST['sc-client-secret'], FILTER_SANITIZE_STRING);
			if ($_POST['sc-client-secret'] == "") {
				$errors .= 'Please enter a valid SoundCloud secret.<br/>';
			}
		} else {
			$errors .= 'Please enter your SoundCloud secret string.<br/>';
		}
	
		if (!$errors)
		{
			//add the data to the wp_options table
			$options = array(
				'sc_client_id' => $sc_client_id,
				'sc_client_secret' => $sc_client_secret,
				'sc_client_access_token' => $soundcloud_access_token,
				'sc_redirect_uri' => $sc_redirect_uri
			);
			update_option('soundcloud_settings', $options); //store the results in WP options table
			echo '<div id="message" class="updated fade">';
			echo '<p>Settings Saved</p>';
			echo '</div>';
		}
		else
		{
			echo '<div class="error fade">' . $errors . '<br/></div>';
		}
		//now let's authenticate and grab the token
		//wp_scu_authenticate();
	}
	

	$sc_settings = get_option('soundcloud_settings');
	$sc_client_id = $sc_settings['sc_client_id'];
	$sc_client_secret = $sc_settings['sc_client_secret'];
	$soundcloud_access_token = $sc_settings['sc_client_access_token'];

//	if ($soundcloud_access_token == '') {
//		echo '<div class="error fade">The connection to the SoundCloud API needs to be authorized.
//		Please enter your settings and click the "Save" button to authorize the connection.<br/></div>';
//	}
?>

<div class="wrap">
<div id="poststuff"><div id="post-body">
<div class="postbox">
<h3><label for="title">Before Using This Plugin</label></h3>
<div class="inside">
<p class="postbox-container">To use the WP SoundCloud Ultimate plugin you will firstly need to create a SoundCloud app using your current SoundCloud account details and then paste some
of the details from your app in the configuration settings of this page.
<br /> 
<h2>Creating Your SoundCloud App</h2>
This literally takes a minute to do. To create a SoundCloud app go to <a href="http://soundcloud.com/you/apps" target="_blank">THIS PAGE</a> and fill in the details as follows:
<ol>
	<li type="disc">
		Enter the following string for the name of your app - <strong>soundcloud-ultimate-plugin</strong></li>
	<li type="disc">
		Then click the register button and enter the following details in the app form:
		<ol>
		<li type="disc"><strong>Website of your app</strong> - Enter your own website URL</li>
		<li type="disc"><strong>Redirect URI for Authentication</strong> - Copy and paste the following url - <span style="color: green; background-color:yellow;"><strong><?php echo site_url();?>/wp-admin/admin.php?page=wpshq_scu_plugin_options</strong></span></li>
		</ol>
	</li>
</ol>
<h2>Configuring the Plugin</h2>
After creating the app, copy the following details from your app and paste in the configuration settings on this page:
<ol>
	<li type="disc"><strong>Sound Cloud Client ID</strong> - Copy this value from your SoundCloud app and paste in the field below.</li>
	<li type="disc"><strong>Sound Cloud Client Secret</strong> - Copy this value from your SoundCloud app and paste in the field below.</li>
</ol>
After entering the configuration settings click the "Save Settings" button.
<h2>Connecting To SoundCloud</h2>
After saving your settings click the "<strong>Connect To Soundcloud</strong>" link in the "SoundCloud Connection Status" section below.
This will take you to the SoundCloud site and ask you to allow the "soundcloud-ultimate-plugin" to connect to your SoundCloud account.
Click the "Connect" button.
</p>
</div></div>
<div class="postbox">
<h3><label for="title">SoundCloud Connection Status</label></h3>
<div class="inside  scu_connect_status">
<?php
	if ($sc_client_id && $sc_client_secret && !$soundcloud_access_token){
		echo '<div class="scu_error_msg" style="color:red;"><strong>You are currently disconnected from SoundCloud.</strong></div>';
?>

<?php 
		wp_scu_authenticate();
?>
</p>
<?php
	} else if (!$sc_client_id && !$sc_client_secret) {
		echo '<div class="scu_error_msg" style="color:red;"><strong>You are currently disconnected from SoundCloud.</strong></div>';
	}else {
		echo '<div class="scu_success_msg" style="color:green;"><strong>You are currently connected to SoundCloud.</strong></div>';
	}
?>
</div></div>
<form action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="POST"	onsubmit="">
<input type="hidden" name="soundcloud_update" id="soundcloud_update" value="true" />
<div class="postbox">
<h3><label for="title">Enter Your SoundCloud Account Details</label></h3>
<div class="inside">
<table class="form-table">
	<tr valign="top">
		<th scope="row"><label for="SCClientID"> Enter Your SoundCloud Client ID:</label>
		</th>
		<td><input type="text" size="40" name="sc-client-id" value="<?php echo $sc_client_id; ?>" /></td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="SCClientID"> Enter Your SoundCloud Client Secret:</label>
		</th>
		<td><input type="text" size="40" name="sc-client-secret" value="<?php echo $sc_client_secret; ?>" /></td>
	</tr>
</table>
<input name="soundcloud_update" type="submit" value="Save Settings" class="button-primary" />
<input name="soundcloud_clear" type="submit" value="Clear Settings" class="button-primary" />
	</div></div>
<br />
	</form>
</div></div>
</div>
<?php
}

function wp_scu_authenticate(){
	//get soundcloud options
	$sc_options = get_option('soundcloud_settings');
	if ($sc_options) {
		$sc_id = $sc_options['sc_client_id'];
		$sc_secret = $sc_options['sc_client_secret']; 
		$sc_token = $sc_options['sc_client_access_token'];
		$sc_redirect_uri = $sc_options['sc_redirect_uri'];
	}
	$soundcloud = new Services_Soundcloud($sc_id, $sc_secret, $sc_redirect_uri);
	if (!$sc_token) {
		$params = array('scope' => 'non-expiring');
		$authorizeUrl = $soundcloud->getAuthorizeUrl($params);
		echo '<br /><a id="scu_connect_url" style="border-style:solid; padding:5px; border-color:orange;" href="'.$authorizeUrl.'">Click Here To Connect To SoundCloud</a>';
		try {
			//TODO: tighten up code - use isset to check if "code" param below exists
			$post_data = array();
			$curl_opts = array(CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false,);
			$accessToken = $soundcloud->accessToken($_GET['code'], $post_data, $curl_opts);
			echo scu_jquery_snippet();
		} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
		    //exit($e->getMessage());
		    echo '<div style="color:red;"><p><strong>SoundCloud Ultimate Error: Could not process the request - Error code ('.$e->getHttpCode().').</strong></p></div>';
			return;
		}
		//var_dump($accessToken);
		//store the token in tyhe options
		$sc_redirect_uri = site_url().'/wp-admin/admin.php?page=wpshq_scu_plugin_options';
		$param = array('sc_client_id' => $sc_id,
					'sc_client_secret' => $sc_secret,
					'sc_client_access_token' => $accessToken['access_token'],
					'sc_redirect_uri' => $sc_redirect_uri);
		update_option('soundcloud_settings', $param); //store the results in WP options table
		$soundcloud->setAccessToken($accessToken['access_token']);
	}else if ($sc_token) {
		$soundcloud->setAccessToken($sc_token);
	}	
}

function scu_jquery_snippet() {
	$code = '<script type="text/javascript">
				jQuery.noConflict();
				jQuery(document).ready(function($) {
					$(".scu_error_msg").remove();
					$("#scu_connect_url").remove();
					$("<div/>")
						.attr("class","scu_success_msg")
						.css("color","green")
						.css("font-weight", "bold")
						.val("You are currently connected to SoundCloud.")
						.text("You are currently connected to SoundCloud.")
						.appendTo($(".scu_connect_status"));					
						
				});
				</script>';
	return $code;
}
?>