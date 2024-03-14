<?php
/* if ( ! defined( 'ABSPATH' ) ) exit;
define('WP_DEBUG', false); */
// Init Options Global
global $w2a_options;

function w2a_enqueue_media_lib_uploader() {

    //Core media script
    wp_enqueue_media();

    // Your custom js file
    wp_register_script( 'media-lib-uploader-js', plugins_url( 'media-lib-uploader.js' , __FILE__ ), array('jquery') );
    wp_enqueue_script( 'media-lib-uploader-js' );
}
add_action('admin_enqueue_scripts', 'w2a_enqueue_media_lib_uploader');

// the media uploader will not open without it
wp_enqueue_media();	

// submit permission ux
if (isset($_POST['submit'])) {
	if(wp_verify_nonce($_REQUEST['w2a_web_push_submit_post'], 'w2a_web_push')){
		
		//sanitize input fields
		$postData = $_POST['data'];
		
		// send the data to save
		$url = 'http://www.web2application.com/w2a/api-process/save_web_push_settings_from_plugin.php';
		$data = array('api_domain' => $_SERVER['SERVER_NAME'], 'api_key' => trim($w2a_options['w2a_api_key']), 'data' => $postData);
		$json = json_encode($data);
		
		/*$options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data),
                    ),
                );
        $context  = stream_context_create($options);
        $html = file_get_contents($url, false, $context);*/

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

// submit firebase details
if (isset($_POST['submit2'])) {
	if(wp_verify_nonce($_REQUEST['w2a_firebase_submit_post'], 'w2a_firebase')){
		
		//sanitize input fields
		$postData = $_POST['data2'];
		
		// send the data to save
		$url = 'http://www.web2application.com/w2a/api-process/save_firebase_settings_from_plugin.php';
		$data = array('api_domain' => $_SERVER['SERVER_NAME'], 'api_key' => trim($w2a_options['w2a_api_key']), 'data' => $postData);
		$json = json_encode($data);
		
		/*$options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data),
                    ),
                );
        $context  = stream_context_create($options);
        $html = file_get_contents($url, false, $context);
		
		// check
        if ($html != ""){	
            echo '<div id="web2app-error-mesage">';
            echo $html;
            echo '</div>';
        }*/
		
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
		echo '<div id="web2app-error-mesage">';
			echo _e('oops... some thing wrong. Please reload the page and try again', 'web2application');
		echo '</div>';
	}
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
	// get app premium settings
	$url 		= 'https://www.web2application.com/w2a/api-process/get_web_push_settings.php?api_domain='.$_SERVER['SERVER_NAME'].'&api_key='.trim($w2a_options['w2a_api_key']);
	$settings 	= file_get_contents($url);
	//$row 		= json_decode($settings);
	//$push		= $row->push_settings;
	
	// check
	if ($settings == "") {
		// init curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$settings = curl_exec($ch);
		curl_close($ch);
	}
	
	// decode
	$row = json_decode($settings);
	$push = $row->push_settings;
}

?>

<link rel="stylesheet" type="text/css" href="https://web2application.com/w2a/webapps/1/web2app1.css">

<style type="text/css">
.my-section {
    background: #ffffff;
    padding: 10px;
}
.form-control {
    width: 400px;
}

	
<?php if (is_rtl()) { ?>
	.native-opt { position:fixed; top: 200px; right: 60%; z-index:9999999999999; width: 500px; }
<?php } else { ?>
	.native-opt { position:fixed; top: 200px; left: 55%; z-index:9999999999999; width: 500px; }
<?php } ?>
</style>

<div class="wrap">

    <h2><?php _e('Web Push Setting', 'web2application'); ?></h2>

	<div class="my-section" style="margin-bottom:20px;">
		<?php
		if ( defined( 'WP_ROCKET_VERSION' ) ) {
			echo "We are detecting that WP-ROCKET in enabled. IF you are using Minify JavaScript files, It can break google firebase JS file.<br>Please exloude the link : www.gstatic.com/firebasejs/7.11.0/firebase.js from the minifications inside wp-rocket settings";
		}
		?>
	</div>
	
	<div class="my-section">
		<h3><?php _e('Configure your alert prompt', 'web2application'); ?></h5>
		<table>
			<tr>
				<td valign="top">
					<form enctype="multipart/form-data" method="post">
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><label><?php _e('Permission UX Design','web2application'); ?></label></th>
									<td>
										<table>
											<tr>
												<td><label for="web_alert_type0"><input type="radio" id="web_alert_type0" name="data[web_alert_type]" value="Native Opt-in" <?php echo ($push->web_alert_type == 'Native Opt-in') ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> onClick="javascript:displaySample('native-opt');" /> <?php _e('Native Opt-in', 'web2application'); ?></label></td>
												<td><label for="web_alert_type1"><input type="radio" id="web_alert_type1" name="data[web_alert_type]" value="Custom Prompt" <?php echo ($push->web_alert_type == 'Custom Prompt') ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> onClick="javascript:displaySample('insider-opt-in-notification');" /> <?php _e('Custom Prompt', 'web2application'); ?></label></td>
												<td><label for="web_alert_type2"><input type="radio" id="web_alert_type2" name="data[web_alert_type]" value="Modal" <?php echo ($push->web_alert_type == 'Modal') ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> onClick="javascript:displaySample('opt-in-popup-container');" /> <?php _e('Modal', 'web2application'); ?></label></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Alert Title','web2application'); ?></label></th>
									<td><input name="data[web_alert_title]" id="web_alert_title" type="text" value="<?php echo ($push->web_alert_title); ?>" class="form-control" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Alert Description','web2application'); ?></label></th>
									<td><input name="data[web_alert_desc]" id="web_alert_dec" type="text" value="<?php echo ($push->web_alert_desc); ?>" class="form-control" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Alert Icon','web2application'); ?></label></th>
									<td><input id="image-url" type="text" name="data[web_alert_icon]" value="<?php echo ($push->web_alert_icon); ?>" class="form-control" <?php if ($disabled) { echo "disabled"; } ?> />
										<input id="w2a-upload-button" type="button" class="button" value="Upload Or Select Image"  />
										<p class="description"><?php _e('Please Select Image Or Paste Full Image Url. example : http://domain.com/image.jpg', 'web2application'); ?></p></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Close Button Title','web2application'); ?></label></th>
									<td><input name="data[web_alert_button]" id="web_alert_button" type="text" value="<?php echo ($push->web_alert_button); ?>" class="form-control" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
							</tbody>
						</table>
						<?php wp_nonce_field('w2a_web_push', 'w2a_web_push_submit_post'); ?>
						<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> /></p>
					</form>
				</td>
				<td width="30%">
					<img id="native-opt" class="native-opt" src="https://web2application.com/w2a/user/push_icons/img_native.png" style="margin-top: 20px; display: <?php echo ($push->web_alert_type == 'Native Opt-in') ? "" : "none"; ?>;" />
					<?php if (is_rtl()) { ?>
					<div id="insider-opt-in-notification" class="insider-opt-in-notification" style="top: 200px; right: 60%; display: <?php echo ($push->web_alert_type == 'Custom Prompt') ? "" : "none"; ?>;">
					<?php } else { ?>
					<div id="insider-opt-in-notification" class="insider-opt-in-notification" style="top: 200px; left: 70%; display: <?php echo ($push->web_alert_type == 'Custom Prompt') ? "" : "none"; ?>;">
					<?php } ?>
						<div class="insider-opt-in-notification-inner-container">
							<div class="insider-opt-in-notification-image-container">
								<img src="<?php echo $row->display_icon; ?>" class="insider-opt-in-notification-image">
							</div>
							<div class="insider-opt-in-notification-text-container">
								<div id="insider-opt-in-notification-title" class="insider-opt-in-notification-title"><?php echo $push->web_alert_title; ?></div>
								<div id="insider-opt-in-notification-description" class="insider-opt-in-notification-description"><?php echo stripslashes($push->web_alert_desc); ?></div>
							</div>
							<div style="clear: both;">
								<div class="insider-opt-in-notification-button-container">
									<button type="button" id="insider-opt-in-notification-button" class="insider-opt-in-notification-button insider-opt-in-disallow-button">
										<?php echo $push->web_alert_button; ?>
									</button>
								</div>
								<div style="clear: both;"></div>
							</div>
						</div>
					</div>
					<?php if (is_rtl()) { ?>
					<div id="opt-in-popup-container" class="opt-in-popup-container" style="top: 200px; right: 60%; display: <?php echo ($push->web_alert_type == 'Modal') ? "" : "none"; ?>;">
					<?php } else { ?>
					<div id="opt-in-popup-container" class="opt-in-popup-container" style="top: 200px; left: 70%; display: <?php echo ($push->web_alert_type == 'Modal') ? "" : "none"; ?>;">
					<?php } ?>
						<div class="row">
							<div class="opt-in-popup-image-container">
								<img src="<?php echo $row->display_icon; ?>" class="opt-in-popup-image" style="width: 150px; text-align: center;">
							</div>
						</div>
						<div class="row">
							<div id="opt-in-popup-title" class="opt-in-popup-title"><?php echo $push->web_alert_title; ?></div>
						</div>
						<div class="row">
							<div id="opt-in-popup-description" class="opt-in-popup-description"><?php echo stripslashes($push->web_alert_desc); ?></div>
						</div>
						<div class="row">
							<div class="opt-in-popup-button-container">
								<button type="button" id="opt-in-popup-button" class="opt-in-popup-button opt-in-popup-disallow-button">
									<?php echo $push->web_alert_button; ?>
								</button>
							</div>
						</div>
					</div>
				</td>
			</tr>
		</table>
	</div>
    <br><br>
	
	<div class="my-section">
	<!--
		<h3>Congratulations, now you can install and use Push messages on your website.</h3>
		<p class="description">PLEASE NOTE THAT IF YOU ALL READY HAVE ANDROID AND/OR IOS APP THAT WE CONFIGURE FOR YOU.<BR> CONTACT US AND WE WILL CONFIGURE IT FOR YOU IN ORDER TO PREVENT PUSH PROBLEMS</p>
		<p class="description">Step one: Please copy these lines of code to the title area of your site. <br>If necessary you should turn to your website builder</p>
		<div style="background-color: lightgrey;padding: 20px;">
			&lt;script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"&gt;&lt;/script&gt; <br>
			&lt;link rel="manifest" href="/manifest.json"&gt;	<br>
			&lt;script src="https://www.gstatic.com/firebasejs/5.10.0/firebase.js"&gt;&lt;/script&gt;<br>
			&lt;script type="text/javascript" src="https://web2application.com/w2a/webapps/<?php echo $appId; ?>/web2app1.js"&gt;&lt;/script&gt;
		</div>
		<p class="description">Please download the file ZIP from the link, Extract it and copy the 2 files to the main ROOT directory of your site</p>
		<a href="https://web2application.com/w2a/webapps/<?php echo $appId; ?>/web_push_files.zip" download="" >
			<button class="button button-primary">Click here to download the file</button>
		</a>
		<br><br>
	-->	
		<div style="background:green; padding: 20px; text-align:center; color:white; font-size:20px; width: 100%;">
			<?php _e('Your firebase setting all ready made and there is no need to set firebase again unless you want to change the details or account.', 'web2application'); ?>
			
		</div>
	</div>
    <br><br>
	
	<div class="my-section">
		<p class="description"><?php _e('Push notifications are a great way to make instant contact with your site visitors in common browsers, even if the site is not open in the browser (works great in Chrome and Firefox, Internet Explorer, safari and other browsers sometimes have problems depending on the version of the browser and its support)', 'web2application' ); ?></p>
		<p class="description"><strong><?php _e('It’s important to make sure that the site runs securely on the HTTPS protocol. If not the push messages will not work! (Google policy)', 'web2application'); ?></strong></p>
		<p class="description"><?php _e('Please follow the instruction on the right or browes to', 'web2application'); ?> <a href="https://web2application.com/how-to-add-push-notifications-to-a-web-site/" style="color:green;" target="_blank"><?php _e('this article', 'web2application'); ?></a></p>

		<form enctype="multipart/form-data" method="post">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label><?php _e('Your FireBase Init Code','web2application'); ?></label></th>
						<td><textarea name="data2[webpush_init_code]" rows="10" style="width:100%; padding:10px;" cols="30" placeholder="For security reasons we do not store this information. It can be obtained from your FireBase account or files" <?php if ($disabled) { echo "disabled"; } ?>></textarea></td>
					</tr>
					<tr>
						<th scope="row"><label><?php _e('Your FireBase Server Key','web2application'); ?></label></th>
						<td><input name="data2[webpush_server_key]" type="text" value="<?php echo ($push->fb_server_key); ?>" class="form-control" <?php if ($disabled) { echo "disabled"; } ?> style="width:100%;" /></td>
					</tr>
					<tr>
						<th scope="row"><label><?php _e('Your FireBase Certificate','web2application'); ?></label></th>
						<td><input name="data2[webpush_cert]" type="text" value="<?php echo ($push->webapp_key); ?>" class="form-control" <?php if ($disabled) { echo "disabled"; } ?> style="width:100%;" /></td>
					</tr>
				</tbody>
			</table>
			<?php wp_nonce_field('w2a_firebase', 'w2a_firebase_submit_post'); ?>
			<p class="submit"><input type="submit" name="submit2" id="submit2" class="button button-primary" value="<?php _e('Update', 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> /></p>
		</form>
	</div>
</div>


<script>
	jQuery(document).ready(function($){
	
        var w2aMediaUploader;

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
		
		
		// options
		// title
        $('#web_alert_title').on('input', function (e) {
			var val = $(this).val();
			document.getElementById('insider-opt-in-notification-title').value = val;
			document.getElementById('opt-in-popup-title').value = val;
        });

        // desc
        $('#web_alert_desc').on('input', function (e) {
			var val = $(this).val();
			document.getElementById('insider-opt-in-notification-description').value = val;
			document.getElementById('opt-in-popup-description').value = val;
        });

        // button
        $('#web_alert_button').on('input', function (e) {
			var val = $(this).val();
			document.getElementById('insider-opt-in-notification-button').value = val;
			document.getElementById('opt-in-popup-button').value = val;
        });
	
	});
	
	function displaySample(view) {
		// hide all by default
		document.getElementById('native-opt').style.display = "none";
		document.getElementById('insider-opt-in-notification').style.display = "none";
		document.getElementById('opt-in-popup-container').style.display = "none";

		// show selected view
		document.getElementById(view).style.display = "block";
	}
	
</script>

<?php ?>