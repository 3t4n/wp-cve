<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<style type="text/css">
.form-control {
    width: 400px;
}
</style>

<?php
if ( ! defined( 'ABSPATH' ) ) exit;
	global $w2a_options;
	
	wp_enqueue_media();	
	
	
// send push to all
if (isset($_POST['send_push'])) {
	//if nonces ok	
    if(wp_verify_nonce($_REQUEST['w2a_push_submit_post'], 'w2a_push')){
    	//	check fields
        if (isset($_POST['Push_Title']) && isset($_POST['Push_Message']) && $_POST['Push_Title'] != "" && $_POST['Push_Message'] != "") {
			
			//sanitize input fields
            $w2aPushTitle       = sanitize_text_field($_POST['Push_Title']);
            $w2aPushMessage     = sanitize_text_field($_POST['Push_Message']);
            $w2aPushImage       = sanitize_text_field($_POST['image']);
			$w2aRichPushImage   = sanitize_text_field($_POST['big_image']);
			$w2aPushLink        = sanitize_text_field($_POST['Push_Link']);
				
			$push_schedule = sanitize_text_field($_POST['push_schedule']);
            $w2aPushTime = ($push_schedule == "send_now") ? date('Y/m/d H:i:s') : sanitize_text_field($_POST['push_date']).' '.sanitize_text_field($_POST['push_time']).':00';
			

            //send the data for pushing
            $url = 'http://www.web2application.com/w2a/api-process/send_push_from_plugin.php';
            $data = array('api_domain' => $_SERVER['SERVER_NAME'], 'api_key' => trim($w2a_options['w2a_api_key']),'push_title' => $w2aPushTitle, 'push_text' => $w2aPushMessage, 'push_image_url' => $w2aPushImage, 'rich_push_image_url' => $w2aRichPushImage, 'push_link' => $w2aPushLink, 'push_time' => $w2aPushTime);
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
} //end if send


// send pus to testers
if (isset($_POST['send_push_testers'])) {
	// if nonces okay
    if(wp_verify_nonce($_REQUEST['w2a_push_submit_post'], 'w2a_push')){
		//	check fields
        if (isset($_POST['Push_Title']) && isset($_POST['Push_Message']) && $_POST['Push_Title'] != "" && $_POST['Push_Message'] != "") {
			
			//sanitize input fields
            $w2aPushTitle       = sanitize_text_field($_POST['Push_Title']);
            $w2aPushMessage     = sanitize_text_field($_POST['Push_Message']);
            $w2aPushImage       = sanitize_text_field($_POST['image']);
			$w2aRichPushImage   = sanitize_text_field($_POST['big_image']);
			$w2aPushLink        = sanitize_text_field($_POST['Push_Link']);
				
			$push_schedule = sanitize_text_field($_POST['push_schedule']);
            $w2aPushTime = ($push_schedule == "send_now") ? date('Y/m/d H:i:s') : sanitize_text_field($_POST['push_date']).' '.sanitize_text_field($_POST['push_time']).':00';
			

            //send the data for pushing
            $url = 'http://www.web2application.com/w2a/api-process/send_push_from_plugin.php';
            $data = array('api_domain' => $_SERVER['SERVER_NAME'], 'api_key' => trim($w2a_options['w2a_api_key']),'push_title' => $w2aPushTitle, 'push_text' => $w2aPushMessage, 'push_image_url' => $w2aPushImage, 'rich_push_image_url' => $w2aRichPushImage, 'push_link' => $w2aPushLink, 'push_time' => $w2aPushTime);
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
			if ($response != "") { 

?>
				<script>
					// alert
					Swal.fire({
						title: 'Push Sent',
						text: "Your push has been sent to the app testers. Do you want to send it to all users now?",
						icon: 'success',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes, Send it now!'
					}).then((result) => {
						if (result.isConfirmed) {
							$('#send_push').trigger( "click" );
						}
					});
				</script>

<?php
			}
			
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
} //end if send
	
	
// woocomerce version check
function woocommerce_version_check( $version = '3.0.0' ) {
    if ( class_exists( 'WooCommerce' ) ) {
        global $woocommerce;
        if( version_compare( $woocommerce->version, $version, ">=" ) ) {
            return true;
        }
    }
    return false;
} // end

// get woocommerce version
function get_woo_version_number() {
    // If get_plugins() isn't available, require it
	if ( ! function_exists( 'get_plugins' ) )
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
        // Create the plugins folder and file variables
	$plugin_folder = get_plugins( '/' . 'woocommerce' );
	$plugin_file = 'woocommerce.php';
	
	// If the plugin version number is set, return it 
	if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
		return $plugin_folder[$plugin_file]['Version'];

	} else {
		return NULL;
	}
} // end


// get appId to check api key validity
$url = 'https://www.web2application.com/w2a/api-process/get_app_id.php?api_domain='.$_SERVER['SERVER_NAME'].'&api_key='.trim($w2a_options['w2a_api_key']).'&version=new';
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
	//$url 		= 'https://www.web2application.com/w2a/api-process/get_app_data.php?api_domain='.$_SERVER['SERVER_NAME'].'&api_key='.trim($w2a_options['w2a_api_key'].'&version=new');
	$url 		= 'https://www.web2application.com/w2a/api-process/get_app_members.php?api_domain='.$_SERVER['SERVER_NAME'].'&api_key='.trim($w2a_options['w2a_api_key'].'&version=new');
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

?>

<h2><?php _e('Send Push Notification Screen','web2application'); ?></h2>
<p class="description"><?php _e('From this Page you can send push notifications to all your users. If you need explantion about how to send the push notification, please ', 'web2application'); ?><a href="http://web2application.com/send-push-notifications-throw-wordpress-plugin/" target="_blank"><?php _e('Click Here ', 'web2application'); ?></a>
<div class="my-section">
<form method="post" id="send-form">
	
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label><?php _e('Push Title','web2application'); ?></label></th>
                <td><input name="Push_Title" type="text" id="Push_Title" value="<?php echo get_bloginfo( 'name' );?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> />
                <p class="description"><?php _e('Please Enter Your Push Title', 'web2application'); ?></p></td>
            </tr>
            <tr>
                <th scope="row"><label><?php _e('Push Message','web2application'); ?></label></th>
                <td><input name="Push_Message" type="text" id="Push_Message" value="<?php _e($_REQUEST['Push_Message']); ?>" class="form-control col-md-4" <?php if ($disabled) { echo "disabled"; } ?> />
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
                  <input id="image-url" type="text" name="image" value="<?php _e($_REQUEST['image']); ?>"/>
                  <input id="w2a-upload-button" type="button" class="button" value="Upload Or Select Image"  />
                  <p class="description"><?php _e('Please Select Image Or Paste Full Image Url. example : http://domain.com/image.jpg', 'web2application'); ?></p>
                </td>
            </tr>
			<tr>
                <th scope="row"><label><?php _e('Rich Push Image','web2application'); ?></label></th>
				<?php if($row->app_paied != 'no') { ?>
                <td>  
                  <input id="big-image-url" type="text" name="big_image" value="<?php _e($_REQUEST['big_image']); ?>"/>
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
								// check
                                /*if( get_woo_version_number() >= 3.0 ) {
                                    $products = wc_get_products();
                                } else {
                                    $products = get_products();
                                }*/
								
								/*$products = wc_get_products();
								foreach( $products as $product ){
                                    echo '<option value="' . get_site_url().'/?p='.$product->get_id() . '">' .  $product->get_name() . '</option>';
                                }
                                wp_reset_query();*/
																		
								$args = array('post_type' => 'product', 
											  'posts_per_page' => 12);
								$loop = new WP_Query( $args );
								if ( $loop->have_posts() ) {
									while ( $loop->have_posts() ) : $loop->the_post();
										//echo '<option value="' . get_site_url().'/?p='.$product->get_id() . '">' .  $product->get_name() . '</option>';
									//	echo '<option value="' . get_permalink() .'">' . get_the_title() . '</option>';
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
            <?php wp_nonce_field('w2a_push', 'w2a_push_submit_post'); ?>
		</tbody>
    </table> 
		
    <input type="submit" value="<?php _e('Send Push Notification', 'web2application'); ?>" id="send_push" name="send_push" class="button button-primary" <?php if ($disabled) { echo "disabled"; } ?> />
	
	<?php if (count($row->testers) >= 1) { ?>
	<input type="submit" value="<?php _e('Send Push Notification to Testers', 'web2application'); ?>" name="send_push_testers" class="button" <?php if ($disabled) { echo "disabled"; } ?> />
	<?php } ?>
</form> 
</div>    


<script>
jQuery(document).ready(function($){
	
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
	
	
	// UPLOADER //
	
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
	
});
</script>