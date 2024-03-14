<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// Init Options Global
global $w2a_options;

// create regular HTML Object
ob_start(); ?>

<!--<link href="//web2application.com/w2a/user/lib/fontawesome-picker/css/fontawesome-iconpicker.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>-->

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css" /> -->

<?php

if( isset( $_REQUEST['save_settings'] ) ) {
	
	if(wp_verify_nonce($_REQUEST['w2a_push_submit_post'], 'w2a_push')){
		
		$settings = $_REQUEST['w2a_settings'];
		
		$w2a_api_key = $settings['w2a_api_key'];
		$w2a_disable_web_push = 0;
		$w2a_disable_elementor = 0;
		$w2a_enable_notify_post = 0;
		$w2a_enable_notify_email = 0;
		$w2a_enable_search_widget = 0;
		$w2a_enable_search_widget_rtl = 0;
		
		if( isset( $settings['w2a_disable_web_push'] ) ) {
			$w2a_disable_web_push = 1;
		}
		if( isset( $settings['w2a_disable_elementor'] ) ) {
			$w2a_disable_elementor = 1;
		}
		if( isset( $settings['w2a_enable_notify_post'] ) ) {
			$w2a_enable_notify_post = 1;
		}
		if( isset( $settings['w2a_enable_notify_email'] ) ) {
			$w2a_enable_notify_email = 1;
		}
		if( isset( $settings['w2a_enable_search_widget'] ) ) {
			$w2a_enable_search_widget = 1;
		}
		if( isset( $settings['w2a_enable_search_widget_rtl'] ) ) {
			$w2a_enable_search_widget_rtl = 1;
		}
		
		$w2a_settings = array(
			'w2a_api_key' => $w2a_api_key,
			'w2a_disable_web_push' => $w2a_disable_web_push,
			'w2a_disable_elementor' => $w2a_disable_elementor,
			'w2a_enable_notify_post' => $w2a_enable_notify_post,
			'w2a_enable_notify_email' => $w2a_enable_notify_email,
			'w2a_enable_search_widget' => $w2a_enable_search_widget,
			'w2a_enable_search_widget_rtl' => $w2a_enable_search_widget_rtl,
		);
		
		if( !$w2a_options ) {
		    update_option('w2a_settings', $w2a_settings);
		}
		else {
		    
            $w2a_options = array_merge($w2a_options, $w2a_settings);
            update_option('w2a_settings', $w2a_options);
        }
	}
}

if( trim($w2a_options['w2a_api_key']) != '' ) {
	$url = 'https://www.web2application.com/w2a/api-process/get_app_data.php?api_domain='.$_SERVER['SERVER_NAME'].'&api_key='.trim($w2a_options['w2a_api_key']).'&version=new';
	$appData = http_get_content($url);
	
	$appDataRes = json_decode($appData);

	// check
	if ($appData != 'Wrong API. Please Check Your API Key' && $appDataRes != null) {
		// create file to save the $appId
		$path = W2A_APP_DATA_DIR."/web2appdata.json";

		// create a file
		$file = fopen($path, "w");
		fwrite($file, $appData);
		fclose($file);	
	}
}

$membershipArr = array('try' => 'trial membership', 'no' => 'free membership', '0' => 'Please set your API key', '1' => 'up to 2000 push recipients', '2' => 'up to 5000 push recipients', '3' => 'up to 10,000 push recipients + API', '4' => 'up to 20,000 push recipients + API', '5' => 'up to 50,00 push recipients + API', '6' => 'up to 100,000 push recipients + API');
$path = W2A_APP_DATA_DIR."/web2appdata.json";
$membership_data = file_get_contents($path);
$membership_id = 0;
$membership_data = json_decode($membership_data, true);
if( $membership_data != '' ) {
	$membership_id = $membership_data['app_paied'];
}

$w2a_options = get_option('w2a_settings');
?>

<div class="wrap">

    <h2><?php _e('Web2Application Setting Page', 'web2application'); ?></h2>
	
	<?php /* ?>
    <form method="post" action="options.php">
	<?php */ ?>
    <form method="post">

        <?php settings_fields('w2a_settings_group'); ?>

        <h3><?php _e('Web2Application Setting', 'web2application'); ?></h3>
        <div class="my-section">
			<table class="form-table">
				<tbody>
					<p><?php _e('This plugin requires API Secret Key from the Web2application console.','web2application'); ?> <br />
						<?php _e('Please login to your app dashboard in our system, Go to Push Setting page from the side menu and copy this url : ','web2application'); ?><strong style="font-size:20px; color:green;"><?php echo $_SERVER['SERVER_NAME']; ?> </strong> <br />
						<?php _e('Please copy the URL AS IS , Do not add any chars','web2application'); ?> <br />
						<?php _e('To sign in to your account or signup to the system', 'web2application'); ?> <a href="http://web2application.com/w2a/user/login.php" target="_blank"><?php _e('Click Here ', 'web2application'); ?></a>
					</p>
					<tr>
						<th scope="row"><label for="w2a_api_key"><?php _e('Your web2application API key','web2application'); ?></label></th>
						<td><input name="w2a_settings[w2a_api_key]" type="text" id="w2a_api_key" value="<?php echo $w2a_options['w2a_api_key']; ?>" class="form-control col-md-4">
							<p class="description"><?php _e('Enter your Web2application API key - Please make sure there is no White spaces at the start or end of the key.', 'web2application'); ?><br><?php _e('If you dont have API key and you need help, please', 'web2application'); ?> <a href="http://web2application.com/create-api-key-wordpress-plugin/" target="_blank"><?php _e('Click Here ', 'web2application'); ?></a></p></td>
					</tr>
				</tbody>
			</table>
			<p>
				<strong><?php _e('Your Plan is:', 'web2application'); ?> </strong> <span style="font-size:18px; color:red;"><?php echo ucwords( $membershipArr[$membership_id] ); ?></span>
			</p>
		</div>
		
		<div class="my-section" style="margin-top:20px; width:48%; float:right;">
			<h3><?php _e('We are here to help :)', 'web2application'); ?></h3>
			<p><?php _e('If you have any problem operate this plugin please contact us and we will help.', 'web2application'); ?> <br>
			<?php _e('You can contact us via Messenger by', 'web2application') ?> <a href="https://m.me/web2application" target="_blank"><?php _e('Clicking Here', 'web2application'); ?></a>
			</p>
			<p><?php _e('Book Free Meeting with us', 'web2application'); ?> <br>
			<?php _e('You can also book a free meeting with us ', 'web2application'); ?> <a href="https://m.web2application.com/book-personal-meeting/" target="_blank"><?php _e('Click here to book a meeting' ,'web2application'); ?></a>
			</p>
			
			<p><?php _e('FREE COURSE', 'web2application'); ?> <br>
			<?php _e('You are welcome to take our free course that will guide you from A-Z', 'web2application'); ?> <a href="https://web2application.com/what-we-will-learn-in-this-course/" target="_blank"><?php _e('Enter Here' ,'web2application'); ?></a>
			</p>
		</div>
		
		<div class="my-section" style="margin-top:20px; width:48%; text-align: center; ">
					<iframe width="560" height="315" src="https://www.youtube.com/embed/EZRuRQ5kF84?si=kHymHltQ_MgoDbAf" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>

		</div>
		
		
		<div class="my-section" style="margin-top:20px;">
			<h3><?php _e('Web Push Setting', 'web2application'); ?></h3>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="w2a_disable_web_push"><?php _e('Disable Web Push','web2application'); ?></label></th>
						<td><?php if ( isset( $w2a_options['w2a_disable_web_push'] ) && $w2a_options['w2a_disable_web_push'] != "") { ?>
							<input type="checkbox" name="w2a_settings[w2a_disable_web_push]" id="w2a_disable_web_push" value="1" checked />
							<?php } else { ?>
							<input type="checkbox" name="w2a_settings[w2a_disable_web_push]" id="w2a_disable_web_push" value="1" />
							<?php } ?>
							<p class="description"><?php _e('This will turn off the web push usage.','web2application'); ?></p>
						</td>
					</tr>
				</tbody>
			</table><br><br>

			<h3><?php _e('Elementor Setting', 'web2application'); ?></h3>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="w2a_disable_elementor"><?php _e('Disable Elementor Feature','web2application'); ?></label></th>
						<td><?php if ( isset( $w2a_options['w2a_disable_elementor'] ) && $w2a_options['w2a_disable_elementor'] == "1") { ?>
							<input type="checkbox" name="w2a_settings[w2a_disable_elementor]" id="w2a_disable_elementor" value="1" checked />
							<?php } else { ?>
							<input type="checkbox" name="w2a_settings[w2a_disable_elementor]" id="w2a_disable_elementor" value="1" />
							<?php } ?>
							<p class="description"><?php _e('This will turn off the elementor features.','web2application'); ?></p>
						</td>
					</tr>
				</tbody>
			</table>

			<h3><?php _e('Notification Setting', 'web2application'); ?></h3>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="w2a_enable_notify_post"><?php _e('Notify when Post/Page/Product Added','web2application'); ?></label></th>
						<td><?php if ( isset( $w2a_options['w2a_enable_notify_post'] ) && $w2a_options['w2a_enable_notify_post'] == "1") { ?>
							<input type="checkbox" name="w2a_settings[w2a_enable_notify_post]" id="w2a_enable_notify_post" value="1" checked />
							<?php } else { ?>
							<input type="checkbox" name="w2a_settings[w2a_enable_notify_post]" id="w2a_enable_notify_post" value="1" />
							<?php } ?>
							<p class="description"><?php _e('This option gives you the ability to send push notifications to your users directly from you post/product/page edit screen.','web2application'); ?></p>
						</td>
					</tr>
				</tbody>
			</table>

			<?php
			$disabled = '';
			$checked = 'checked';
			$name = 'name="w2a_settings[w2a_enable_notify_email]"';
			if( $membership_id < 3 ) {
				$disabled = 'disabled';
				$name = '';
				$checked = '';
			}
			?>
			<h3><?php _e('Email Setting', 'web2application'); ?></h3>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="w2a_enable_notify_email"><?php _e('Send All Email as Push','web2application'); ?></label></th>
						<td><?php if ($w2a_options['w2a_enable_notify_email'] == "1") { ?>
							<input type="checkbox" <?=$name?> id="w2a_enable_notify_email" value="1" <?=$checked?> <?=$disabled?> />
							<?php } else { ?>
							<input type="checkbox" <?=$name?> id="w2a_enable_notify_email" value="1" <?=$disabled?> />
							<?php } ?>
							<p class="description"><?php _e('Send all emails as personal push notifications. This option is for users that set up the API in their app dashboard in web2application.com control panel', 'web2application'); ?></p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<div class="my-section" style="margin-top:20px;">
			<h3><?php _e('Search Widget Settings', 'web2application'); ?></h3>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="w2a_disable_web_push"><?php _e('Enable Search Widget','web2application'); ?></label></th>
						<td><?php if ( isset( $w2a_options['w2a_enable_search_widget'] ) && $w2a_options['w2a_enable_search_widget'] != "") { ?>
							<input type="checkbox" name="w2a_settings[w2a_enable_search_widget]" id="w2a_enable_search_widget" value="1" checked />
							<?php } else { ?>
							<input type="checkbox" name="w2a_settings[w2a_enable_search_widget]" id="w2a_enable_search_widget" value="1" />
							<?php } ?>
							<p class="description"><?php _e('This will turn on the search widget.','web2application'); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="w2a_disable_web_push"><?php _e('Enable Search Widget as RTL','web2application'); ?></label></th>
						<td><?php if ( isset( $w2a_options['w2a_enable_search_widget_rtl'] ) && $w2a_options['w2a_enable_search_widget_rtl'] != "") { ?>
							<input type="checkbox" name="w2a_settings[w2a_enable_search_widget_rtl]" id="w2a_enable_search_widget_rtl" value="1" checked />
							<?php } else { ?>
							<input type="checkbox" name="w2a_settings[w2a_enable_search_widget_rtl]" id="w2a_enable_search_widget_rtl" value="1" />
							<?php } ?>
							<p class="description"><?php _e('This will turn on the search widget as RTL.','web2application'); ?></p>
						</td>
					</tr>
				</tbody>
			</table><br><br>
		</div>
		
		<?php /* ?>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'web2application'); ?>"/></p>
		<?php */ ?>
		<?php wp_nonce_field('w2a_push', 'w2a_push_submit_post'); ?>
		<p class="submit"><input type="submit" name="save_settings" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'web2application'); ?>"/></p>

    </form>

</div>

<?php /* ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$(document).on('click', '#w2a_enable_notify_post', function(e) {
		if( $(this).prop('checked') ) {
			swal({
			  	title: "Are you sure?",
			  	text: "push delivery that pushes will not be send by mistake",
			  	icon: "warning",
			  	buttons: true,
			  	dangerMode: true,
			})
			.then((willDelete) => {
			  	if (willDelete) {
					swal("Click on Save changes to apply changes for push notification.");
			  	}
			  	else {
			  		$('#w2a_enable_notify_post').prop('checked', false);
			  	}
			});
		}
	})
});
</script>
<?php */ ?>


<?php

echo ob_get_clean();
