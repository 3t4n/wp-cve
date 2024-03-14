<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// Init Options Global
global $w2a_options;


// UPDATE APP LINKS
if (isset($_POST['submit'])) {
	if(wp_verify_nonce($_REQUEST['w2a_update_app_links_submit_post'], 'w2a_update_app_links')){

		//sanitize input fields
		$postData = $_POST['data']; //sanitize_text_field($_POST['data']);

		// send the data to save
		$url = 'http://www.web2application.com/w2a/api-process/save_app_store_links_from_plugin.php';
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

// SAVE OFFER
if (isset($_POST['submit_offer'])) {
	if(wp_verify_nonce($_REQUEST['w2a_save_offer_submit_post'], 'w2a_save_offer')){

		//sanitize input fields
		$postData = $_POST['data']; //sanitize_text_field($_POST['data']);

		// send the data to save
		$url = 'http://www.web2application.com/w2a/api-process/save_offer_from_plugin.php';
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

// save cart reminder
if (isset($_POST['save_reminder'])) {
    if(wp_verify_nonce($_REQUEST['w2a_push_submit_reminder'], 'w2a_push')){
		
		$enable_reminder = 0;
		if( isset( $_REQUEST['enable_reminder'] ) ) {
			$enable_reminder = 1;
		}
		
		$reminder_days = $_REQUEST['reminder_days'];
		$reminder_push_link = $_REQUEST['reminder_push_link'];
		$reminder_push_title = $_REQUEST['reminder_push_title'];
		$reminder_body_push = stripslashes( $_REQUEST['reminder_body_push'] );
		$reminder_limit = $_REQUEST['reminder_limit'];
		$reminder_push_time = $_REQUEST['reminder_push_time'];
		
		$w2a_settings = array(
			'w2a_reminder_body_push' => $reminder_body_push,
			'w2a_reminder_push_link' => $reminder_push_link,
			'w2a_reminder_push_title' => $reminder_push_title,
			'w2a_reminder_days' => $reminder_days,
			'w2a_reminder_push_time' => $reminder_push_time,
			'w2a_reminder_limit' => $reminder_limit,
			'w2a_enable_reminder' => $enable_reminder,
		);
		
		if( !$w2a_options ) {
		    update_option('w2a_settings', $w2a_settings);
		}
		else {
		    
            $w2a_options = array_merge($w2a_options, $w2a_settings);
            update_option('w2a_settings', $w2a_options);
        }
		
		/* Set CRON */
		if( isset( $_REQUEST['enable_reminder'] ) && !empty( $reminder_push_time ) ) {
			if ( ! wp_next_scheduled( 'w2a_reminder_cron' ) ) {
				$cron_time = $reminder_push_time.':00';
				wp_schedule_event( strtotime($cron_time), 'daily', 'w2a_reminder_cron' );
			}
		}
		else {
			wp_clear_scheduled_hook('w2a_reminder_cron');
		}
		/* Set CRON */
	}
}

// save miss you reminder
if (isset($_POST['save_miss_you_reminder'])) {
    if(wp_verify_nonce($_REQUEST['w2a_push_submit_miss_you_reminder'], 'w2a_push')){
		
		$enable_miss_you_reminder = 0;
		if( isset( $_REQUEST['enable_miss_you_reminder'] ) ) {
			$enable_miss_you_reminder = 1;
		}
		
		$miss_you_reminder_days = $_REQUEST['miss_you_reminder_days'];
		$miss_you_reminder_push_link = $_REQUEST['miss_you_reminder_push_link'];
		$miss_you_reminder_push_title = $_REQUEST['miss_you_reminder_push_title'];
		$miss_you_reminder_body_push = stripslashes( $_REQUEST['miss_you_reminder_body_push'] );
		$miss_you_reminder_push_time = $_REQUEST['miss_you_reminder_push_time'];
		$miss_you_reminder_limit = $_REQUEST['miss_you_reminder_limit'];
		
		$w2a_settings = array(
			'w2a_miss_you_reminder_body_push' => $miss_you_reminder_body_push,
			'w2a_miss_you_reminder_push_link' => $miss_you_reminder_push_link,
			'w2a_miss_you_reminder_push_title' => $miss_you_reminder_push_title,
			'w2a_miss_you_reminder_days' => $miss_you_reminder_days,
			'w2a_miss_you_reminder_push_time' => $miss_you_reminder_push_time,
			'w2a_miss_you_reminder_limit' => $miss_you_reminder_limit,
			'w2a_enable_miss_you_reminder' => $enable_miss_you_reminder,
		);
		
		if( !$w2a_options ) {
		    update_option('w2a_settings', $w2a_settings);
		}
		else {
		    
            $w2a_options = array_merge($w2a_options, $w2a_settings);
            update_option('w2a_settings', $w2a_options);
        }
		
		/* Set CRON */
		if( isset( $_REQUEST['enable_miss_you_reminder'] ) && !empty( $miss_you_reminder_push_time ) ) {
			if ( ! wp_next_scheduled( 'w2a_miss_you_reminder_cron' ) ) {
				$cron_time = $miss_you_reminder_push_time.':00';
				wp_schedule_event( strtotime($cron_time), 'daily', 'w2a_miss_you_reminder_cron' );
			}
		}
		else {
			wp_clear_scheduled_hook('w2a_miss_you_reminder_cron');
		}
		/* Set CRON */
	}
}

// save app discount
if (isset($_POST['save_app_discount'])) {
    if(wp_verify_nonce($_REQUEST['w2a_push_submit_app_discount'], 'w2a_push')){
		
		$enable_app_discount = 0;
		if( isset( $_REQUEST['enable_app_discount'] ) ) {
			$enable_app_discount = 1;
		}
		
		$app_discount_perc = $_REQUEST['app_discount_perc'];
		
		$w2a_settings = array(
			'w2a_enable_app_discount' => $enable_app_discount,
			'w2a_app_discount_perc' => $app_discount_perc,
		);
		
		if( !$w2a_options ) {
		    update_option('w2a_settings', $w2a_settings);
		}
		else {
		    
            $w2a_options = array_merge($w2a_options, $w2a_settings);
            update_option('w2a_settings', $w2a_options);
        }
		
		$coupon_code = __('In-app Discount', 'web2application');
		$amount = $app_discount_perc;
		$discount_type = 'percent'; // Type: fixed_cart, percent, fixed_product, percent_product

		$coupon = array(
			'post_title' => $coupon_code,
			'post_content' => '',
			'post_status' => 'publish',
			'post_author' => 1,
			'post_type' => 'shop_coupon'
		);
		
		$check_post = get_page_by_title( $coupon_code, ARRAY_A, 'shop_coupon' );
		if( !empty($check_post) ) {
			$coupon['ID'] = $check_post['ID'];
			$new_coupon_id = wp_update_post( $coupon );
		}
		else {
			$new_coupon_id = wp_insert_post( $coupon );
		}
		
		// Add meta
		update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
		update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
		update_post_meta( $new_coupon_id, 'individual_use', 'no' );
		update_post_meta( $new_coupon_id, 'product_ids', '' );
		update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
		update_post_meta( $new_coupon_id, 'usage_limit', '' );
		update_post_meta( $new_coupon_id, 'expiry_date', '' );
		update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
		update_post_meta( $new_coupon_id, 'free_shipping', 'no' );

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
	// get app marketing tools
	$url 		= 'https://www.web2application.com/w2a/api-process/get_app_marketing_tools.php?api_domain='.$_SERVER['SERVER_NAME'].'&api_key='.trim($w2a_options['w2a_api_key'].'&version=new');
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
	$row = json_decode($app);
}

$default_tab = 'qr';
$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<style type="text/css">
.form-control {
    width: 400px;
}
</style>

<div class="wrap">
	<h2><?php _e('Marketing Tools - Bring More Users', 'web2application'); ?></h2>
	
	<nav class="nav-tab-wrapper">
      <a href="?page=web2application-marketing-tools&tab=qr" class="nav-tab <?php if($tab==='qr'):?>nav-tab-active<?php endif; ?>">Link QR</a>
      <a href="?page=web2application-marketing-tools&tab=offers" class="nav-tab <?php if($tab==='offers'):?>nav-tab-active<?php endif; ?>">Offers</a>
      <a href="?page=web2application-marketing-tools&tab=cart-reminder" class="nav-tab <?php if($tab==='cart-reminder'):?>nav-tab-active<?php endif; ?>">Cart Reminder</a>
      <a href="?page=web2application-marketing-tools&tab=miss-you-reminder" class="nav-tab <?php if($tab==='miss-you-reminder'):?>nav-tab-active<?php endif; ?>">Miss You Reminder</a>
      <a href="?page=web2application-marketing-tools&tab=app-discount" class="nav-tab <?php if($tab==='app-discount'):?>nav-tab-active<?php endif; ?>">In App Discount</a>
    </nav>
	
	<div class="tab-content">
		<?php
		switch($tab):
			case 'qr':
				?>
				<div class="my-section" style="margin-top:20px;">

					<h3><?php _e('App Download QR Code And Link', 'web2application'); ?></h3>
					<table class="form-table">
						<tbody>
							<tr>
								<td width="70%" valign="top">
									<p><?php _e('We made for you a special QR Code and link that you can send via whatsapp, sms, put in your site, banner links and more...', 'web2application') ?><br><?php _e('When a use enter the link or scan the code he will be redirect, according to his device to the right store for download', 'web2application'); ?></p>
									<?php if ($appId != 'Wrong API. Please Check Your API Key' && is_numeric($appId)) { ?>
									<p style="font-size:24px;"><strong>https://web2application.com/w2a/sl.php?an=<?php echo $row->app_id; ?></strong></p>
									<?php } else { ?>
									<p style="color: red;"><b><?php _e('Web2Application plugin not set. Please go to Web2application -> Setting and fix your API key', 'web2application') ?></b></p>
									<?php } ?>
								</td>
								<?php if ($appId != 'Wrong API. Please Check Your API Key' && is_numeric($appId)) { ?>
								<td><img src="http://web2application.com/w2a/qrcodes/temp/<?php echo $row->barcode_file_name; ?>" width="200" /></td>
								<?php } ?>
							</tr>
						</tbody>
					</table>

					<p><?php _e('Store links : (if your link needs update please copy the url from the store and update here)', 'web2application'); ?></p>

					<form method="post">
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><label><?php _e('Google Play Store URL','web2application'); ?></label></th>
									<td><input name="data[android_store_url]" type="text" value="<?php echo ($row->android_store_url); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Apple Apps Store URL','web2application'); ?></label></th>
									<td><input name="data[apple_store_url]" type="text" value="<?php echo ($row->apple_store_url); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
							</tbody>
						</table>
						<?php wp_nonce_field('w2a_update_app_links', 'w2a_update_app_links_submit_post'); ?>
						<p class="submit"><input type="submit" name="submit" class="button button-primary" value="<?php _e('Save Changes', 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> /></p>
					</form>
					
				</div>
				<?php
				break;
			case 'offers':
				?>
				<div class="my-section" style="margin-top:20px;">

					<h3><?php _e('Special Offers To App Users Only', 'web2application'); ?></h3>
					<p class="description"><?php _e('You can create special offers only to the app users and advert them on social networks, mailing list,sms and ...', 'web2application'); ?><br><?php _e('If somebody will get the link and click the system will check if your application are installed on the device and open the offer. If not, the user will be redirect to google paly or apple appstore to download your app!', 'web2application'); ?><br><?php _e('Its a great tool to make users download your app!', 'web2application'); ?><br><?php _e('The offer link will be available for 90 days.', 'web2application'); ?></p>

					<h4><?php _e('Add New Offer - Please fill the offer details:', 'web2application'); ?></h4>

					<form method="post">
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><label><?php _e('Offer Name','web2application'); ?></label></th>
									<td><input name="data[offer_name]" type="text" class="form-control col-md-4" required <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Offer Original URL link in Your Website','web2application'); ?></label></th>
									<td><input name="data[offer_original_url]" type="text" class="form-control col-md-4" required <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Message to users that dont have the app (Not all browsers ask it)','web2application'); ?></label></th>
									<td><input name="data[offer_dont_have_prompt]" type="text" class="form-control col-md-4" required value="Its seem that you dont have the app installed. do you want to download the app now?" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Desktop URL link in your website (To where users will redirect if they click the link from desktop computer)','web2application'); ?></label></th>
									<td><input name="data[offer_desktop_link]" type="text" class="form-control col-md-4" required <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<input name="data[android_store_url]" type="hidden" value="<?php echo ($row->android_store_url); ?>" />
								<input name="data[apple_store_url]" type="hidden" value="<?php echo ($row->apple_store_url); ?>" />
								<input name="data[android_pack_name]" type="hidden" value="<?php echo ($row->android_pack_name); ?>" />
							</tbody>
						</table>
						<?php wp_nonce_field('w2a_save_offer', 'w2a_save_offer_submit_post'); ?>
						<p class="submit"><input type="submit" name="submit_offer" class="button button-primary" value="<?php _e('Add Offer and Get Link', 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> /></p>
					</form>
					<br><br><hr><br>
				</div>
				<div class="my-section" style="margin-top:20px;">

					<h3><?php _e('Offer Links', 'w2a_domain'); ?></h3>
					<table class="form-table stripe" id="offer-table">
						<thead class="thead-dark">
							<tr>
								<th scope="col" width="150"><?php _e('Offer Name', 'web2application'); ?></th>
								<th scope="col" width="200"><?php _e('Original Link', 'web2application'); ?></th>
								<th scope="col" width="200"><?php _e('Promotion Smart Link To Advert' ,'web2application'); ?></th>
								<th scope="col" class="text-center" width="150"><?php _e('Action', 'web2application'); ?></th>
							</tr>
						</thead>
						<tbody>
						<?php
							// iterate
							foreach ($row->offers as $offer) {
								$currentLinkNumber = $offer->offer_auto_number;
						?>

							<tr>
							  <td><?php _e($offer->offer_name); ?></td>
							  <td><?php _e($offer->offer_original_link); ?></td>
							  <td><div id="specialOfferNo<?php echo $currentLinkNumber; ?>"><?php _e($offer->offer_advert_link); ?></div></td>
							  <td class="text-center">
								<a href="javascript:copyToClipboard('specialOfferNo<?php echo $currentLinkNumber; ?>')">Copy Link</a>
							  </td>
							</tr>

						<?php } ?>
						</tbody>
					</table>

				</div>
				<?php
				break;
			case 'cart-reminder':
				?>
				<div class="my-section1" style="margin-top:20px;">
					<p><?php _e('At this screen, you will be able to define a cart reminder to remind users that have added products to their shopping cart and didnt finish the purchase.'); ?></p>
					<p><?php _e('Please note: For the system to remember the user cart he must be logged in'); ?></p>
				</div>
				<div id="content">
					<form method="post" id="reminder_setting_form">
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><label for="enable_reminder"><?php _e('Enable Reminder','web2application'); ?></label></th>
									<td><?php if ( isset( $w2a_options['w2a_enable_reminder'] ) && $w2a_options['w2a_enable_reminder'] != "") { ?>
										<input type="checkbox" name="enable_reminder" id="enable_reminder" value="1" checked />
										<?php } else { ?>
										<input type="checkbox" name="enable_reminder" id="enable_reminder" value="1" />
										<?php } ?>
										<p class="description"><?php _e('This will turn on reminder.','web2application'); ?></p>
									</td>
								</tr>
								<tr>
									<th>
										<label for="reminder_days"><b><?php _e('Send miss you a reminder if the client didn\'t buy for:','web2application'); ?></b></label>
									</th>
									<td>							
										<input name="reminder_days" type="number" id="reminder_days" value="<?php echo ( isset( $w2a_options['w2a_reminder_days'] ) ) ? $w2a_options['w2a_reminder_days'] : ''; ?>" class="form-control col-md-2" style="width: 100px;" min="1" max="10"> <?php _e('Days','web2application'); ?>
									</td>
								</tr>
								<tr>
									<th>
										<label for="reminder_push_title"><b><?php _e('Push Title/Email Subject:','web2application'); ?></b></label>
									</th>
									<td>							
										<input name="reminder_push_title" type="text" id="reminder_push_title" value="<?php echo ( isset( $w2a_options['w2a_reminder_push_title'] ) ) ? $w2a_options['w2a_reminder_push_title'] : ''; ?>" class="form-control col-md-4">
									</td>
								</tr>
								<tr>
									<th>
										<label for="reminder_push_link"><b><?php _e('Push Link:','web2application'); ?></b></label>
									</th>
									<td>							
										<input name="reminder_push_link" type="text" id="reminder_push_link" value="<?php echo ( isset( $w2a_options['w2a_reminder_push_link'] ) ) ? $w2a_options['w2a_reminder_push_link'] : ''; ?>" class="form-control col-md-4">
									</td>
								</tr>
								<tr>
									<th>
										<label for="wcf_email_body"><b><?php _e('Email Body:','web2application'); ?></b></label>
									</th>
									<td>							
										<?php
										$body_push = '';
										if( isset( $w2a_options['w2a_reminder_body_push'] ) ) {
											$body_push = stripslashes($w2a_options['w2a_reminder_body_push']);
										}
										the_editor($body_push, 'reminder_body_push')
										?>
									</td>
								</tr>
								<tr>
									<th>
										<label for="reminder_limit"><b><?php _e('How many times it will send?','web2application'); ?></b></label>
									</th>
									<td>							
										<input name="reminder_limit" type="number" id="reminder_limit" value="<?php echo ( isset( $w2a_options['w2a_reminder_limit'] ) ) ? $w2a_options['w2a_reminder_limit'] : '1'; ?>" class="form-control col-md-2" style="width: 100px;" min="1" max="10">
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Push Schedule(Daily):','web2application'); ?></label></th>
									<td>
										<?php if($row->app_paied != 'no') { ?>
										<label for="reminder_push_time">
											<input name="reminder_push_time" type="text" id="reminder_push_time" value="<?php echo ( isset( $w2a_options['w2a_reminder_push_time'] ) ) ? $w2a_options['w2a_reminder_push_time'] : ''; ?>" class="form-control col-md-2" <?php if ($disabled) { echo "disabled"; } ?> style="width: 100px;" />
										</label>
										<?php } else { ?>
										<?php _e('Reminder Settings is available only to premium users', 'web2application'); ?>
										<?php } ?>
									</td>
								</tr>
							</tbody>
							<?php wp_nonce_field('w2a_push', 'w2a_push_submit_reminder'); ?>
						</table>
						<input type="submit" value="<?php _e('Save Settings', 'web2application'); ?>" name="save_reminder" class="button button-primary" <?php if ($disabled) { echo "disabled"; } ?> />
					</form>
				</div>
				<?php
				break;
			case 'miss-you-reminder':
				?>
				<div class="my-section1" style="margin-top:20px;">
					<p><?php _e('On this screen, you can set a reminder for customers who have not purchased from you for several days and try to bring them back.','web2application'); ?></p>
					<p><?php _e('It is recommended to give them a certain benefit and return them for another purchase in the store.'); ?></p>
				</div>
				<div id="content">
					<form method="post" id="miss_you_reminder_setting_form">
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><label for="enable_miss_you_reminder"><?php _e('Enable Miss You Reminder','web2application'); ?></label></th>
									<td><?php if ( isset( $w2a_options['w2a_enable_miss_you_reminder'] ) && $w2a_options['w2a_enable_miss_you_reminder'] != "") { ?>
										<input type="checkbox" name="enable_miss_you_reminder" id="enable_miss_you_reminder" value="1" checked />
										<?php } else { ?>
										<input type="checkbox" name="enable_miss_you_reminder" id="enable_miss_you_reminder" value="1" />
										<?php } ?>
										<p class="description"><?php _e('This will turn on reminder.','web2application'); ?></p>
									</td>
								</tr>
								<tr>
									<th>
										<label for="miss_you_reminder_days"><b><?php _e('Send miss you a reminder if the client didn\'t buy for:','web2application'); ?></b></label>
									</th>
									<td>							
										<input name="miss_you_reminder_days" type="number" id="miss_you_reminder_days" value="<?php echo ( isset( $w2a_options['w2a_miss_you_reminder_days'] ) ) ? $w2a_options['w2a_miss_you_reminder_days'] : ''; ?>" class="form-control col-md-2" style="width: 100px;" min="1" max="720"> <?php _e('Days','web2application'); ?>
									</td>
								</tr>
								<tr>
									<th>
										<label for="miss_you_reminder_push_title"><b><?php _e('Push Title/Email Subject:','web2application'); ?></b></label>
									</th>
									<td>							
										<input name="miss_you_reminder_push_title" type="text" id="miss_you_reminder_push_title" value="<?php echo ( isset( $w2a_options['w2a_miss_you_reminder_push_title'] ) ) ? $w2a_options['w2a_miss_you_reminder_push_title'] : ''; ?>" class="form-control col-md-4">
									</td>
								</tr>
								<tr>
									<th>
										<label for="miss_you_reminder_push_link"><b><?php _e('Push Link:','web2application'); ?></b></label>
									</th>
									<td>							
										<input name="miss_you_reminder_push_link" type="text" id="miss_you_reminder_push_link" value="<?php echo ( isset( $w2a_options['w2a_miss_you_reminder_push_link'] ) ) ? $w2a_options['w2a_miss_you_reminder_push_link'] : ''; ?>" class="form-control col-md-4">
									</td>
								</tr>
								<tr>
									<th>
										<label for="wcf_email_body"><b><?php _e('Email Body:','web2application'); ?></b></label>
									</th>
									<td>							
										<?php
										$miss_you_reminder_body_push = '';
										if( isset( $w2a_options['w2a_miss_you_reminder_body_push'] ) ) {
											$miss_you_reminder_body_push = stripslashes($w2a_options['w2a_miss_you_reminder_body_push']);
										}
										the_editor($miss_you_reminder_body_push, 'miss_you_reminder_body_push')
										?>
									</td>
								</tr>
								<tr>
									<th>
										<label for="miss_you_reminder_limit"><b><?php _e('How many times it will send?','web2application'); ?></b></label>
									</th>
									<td>							
										<input name="miss_you_reminder_limit" type="number" id="miss_you_reminder_limit" value="<?php echo ( isset( $w2a_options['w2a_miss_you_reminder_limit'] ) ) ? $w2a_options['w2a_miss_you_reminder_limit'] : '1'; ?>" class="form-control col-md-2" style="width: 100px;" min="1" max="10">
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Push Schedule(Daily):','web2application'); ?></label></th>
									<td>
										<?php if($row->app_paied != 'no') { ?>
										<label for="miss_you_reminder_push_time">
											<input name="miss_you_reminder_push_time" type="text" id="miss_you_reminder_push_time" value="<?php echo ( isset( $w2a_options['w2a_miss_you_reminder_push_time'] ) ) ? $w2a_options['w2a_miss_you_reminder_push_time'] : ''; ?>" class="form-control col-md-2" <?php if ($disabled) { echo "disabled"; } ?> style="width: 100px;" />
										</label>
										<?php } else { ?>
										<?php _e('Reminder Settings is available only to premium users', 'web2application'); ?>
										<?php } ?>
									</td>
								</tr>
							</tbody>
							<?php wp_nonce_field('w2a_push', 'w2a_push_submit_miss_you_reminder'); ?>
						</table>
						<input type="submit" value="<?php _e('Save Settings', 'web2application'); ?>" name="save_miss_you_reminder" class="button button-primary" <?php if ($disabled) { echo "disabled"; } ?> />
					</form>
				</div>
				<?php
				break;
			case 'app-discount':
				?>
				<!--<div class="my-section1" style="margin-top:20px;">
					<p><?php _e('In App Discount..','web2application'); ?></p>
				</div>-->
				<div class="my-section1" style="margin-top:20px;">
					<p><?php _e('A marketing tool that will make users download your android and iOS applications and provide a benefit to your members club.', 'web2application'); ?></p>
					<p><?php _e('You can set a special discount for customers who buy through the app and then they will receive a discount on the shopping cart as soon as they buy from the app', 'web2application'); ?></p>
				</div>
				<div id="content">
					<form method="post" id="app_discount_setting_form">
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><label for="enable_app_discount"><?php _e('Enable App Discount','web2application'); ?></label></th>
									<td><?php if ( isset( $w2a_options['w2a_enable_app_discount'] ) && $w2a_options['w2a_enable_app_discount'] != "") { ?>
										<input type="checkbox" name="enable_app_discount" id="enable_app_discount" value="1" checked />
										<?php } else { ?>
										<input type="checkbox" name="enable_app_discount" id="enable_app_discount" value="1" />
										<?php } ?>
										<p class="description"><?php _e('This will turn on App Discount.','web2application'); ?></p>
									</td>
								</tr>
								<tr>
									<th>
										<label for="app_discount_perc"><b><?php _e('Percentage Discount:','web2application'); ?></b></label>
									</th>
									<td>							
										<input name="app_discount_perc" type="number" id="app_discount_perc" value="<?php echo ( isset( $w2a_options['w2a_app_discount_perc'] ) ) ? $w2a_options['w2a_app_discount_perc'] : ''; ?>" class="form-control col-md-2" style="width: 100px;" step="1">
									</td>
								</tr>
							</tbody>
							<?php wp_nonce_field('w2a_push', 'w2a_push_submit_app_discount'); ?>
						</table>
						<input type="submit" value="<?php _e('Save Settings', 'web2application'); ?>" name="save_app_discount" class="button button-primary" <?php if ($disabled) { echo "disabled"; } ?> />
					</form>
				</div>
				<?php
				break;
		endswitch;
		?>
	</div>
	
</div>

<?php
function debug_to_console($data) {
	$output = $data;
	if (is_array($output))
		$output = implode(',', $output);

	echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
?>

<script type="text/javascript" charset="utf-8">
function copyToClipboard(containerid) {
    if (document.selection) {
        var range = document.body.createTextRange();
        range.moveToElementText(document.getElementById(containerid));
        range.select().createTextRange();
        document.execCommand("copy");

    } else if (window.getSelection) {
        window.getSelection().removeAllRanges();
        var range = document.createRange();
         range.selectNode(document.getElementById(containerid));
         window.getSelection().addRange(range);
         document.execCommand("Copy", false, null);
    }
}
</script>
<script>
$(document).ready(function() {
	// datatables
	$('#offer-table').DataTable();
	
	// time picker
	$('#miss_you_reminder_push_time, #reminder_push_time').timepicker({
    	timeFormat: 'HH:mm',
    	interval: 5,
    	startTime: '00:00',
    	dynamic: false,
    	dropdown: true,
    	scrollbar: true
	});
});
</script>
