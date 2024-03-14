<?php

/**
 * Description: Template file for admin render settings
 * 
 * @package Channelize Shopping
 * 
 * 
 */

defined('ABSPATH') || exit;

use Includes\Pages\CHLSAdmin;

require_once dirname(__FILE__, 2) . '/includes/chls_functions.php';
?>

<?php
$massage = '';
$type = '';
$options = get_option('channelize_live_shopping');
$public_key = isset($options['public_key']) ? $options['public_key'] : "";
$private_key = isset($options['private_key']) ? $options['private_key'] : "";
if (isset($_POST['submit'])) {

	$user_enter_private_key = sanitize_text_field($_POST['channelize_live_shopping_private_key']);
	$user_enter_public_key = sanitize_text_field($_POST['channelize_live_shopping_public_key']);
	$channelize_url = CHLSAdmin::$url;

	$data = array(
		"key" => base64_encode($user_enter_private_key),
	);
	$headers = array(
		"Content-Type" => "application/json"
	);
	try {
		$args = array(
			'body'        => json_encode($data),
			'timeout'     => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
			'cookies'     => array(),
		);
		$response = wp_remote_post($channelize_url, $args);
		$response_body     = wp_remote_retrieve_body($response);
		$response = json_decode($response_body);
		if (gettype($response) == 'string') {
			$response = json_decode($response, true);
		}
		$response = (array) $response;
		$public_key_response = isset($response['public_key']) ? $response['public_key'] : '';


		if (!($public_key_response == $user_enter_public_key)) {
			$massage = "The key is invalid. Please make sure you've entered the correct key.";
			$type = 'error';
		} else {
			$public_key = $user_enter_public_key;
			$private_key = $user_enter_private_key;

			$massage = "Settings saved";
			$type = 'success';
			$option = array();
			$option['private_key'] = $user_enter_private_key;
			$option['public_key'] = $user_enter_public_key;
			update_option('channelize_live_shopping', $option);

			chls_create_custom_page();
		}
	} catch (ErrorException $e) {

		print_r($e->getMessage());
	}
}


?>



<div>
	<h1>Live Shopping & Video Streams</h1>
	<div class="wrap" id="wpcf7-integration">

		<h1>Configuration</h1>

		<form method="post" action="#">
			<p>Channelize.io issues Public and Private Keys to app developers in order for them to identify their applications and access API. </p>
			<p>After signing up at <a href="<?php echo esc_url("https://channelize.io/channelize/lsc/account/login/") ?>" target='_blank'> Live Shopping & Video Streams Dashboard</a>, you can create multiple applications, each with its own Public key and Private key. Your Public and Private key can be found on the <strong>"Help & Support"</strong> page of your Dashboard.</p>

			<?php if ($massage) {
				echo '
			<div  class="notice notice-' . esc_html($type) . ' settings-error is-dismissible">
			<p><strong>' . esc_html($massage) . '</strong></p>
			</div>';
			}
			?>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row">Public Key</th>
						<td> <input required="" type="text" name="channelize_live_shopping_public_key" id="channelize_live_shoppping_public_key" value="<?php echo esc_html($public_key) ?>">
						</td>
					</tr>
					<tr>
						<th scope="row">Private Key</th>
						<td> <input required="" type="password" name="channelize_live_shopping_private_key" id="channelize_live_shopping_private_key" value="<?php echo esc_html($private_key) ?>">
							<br><br>
							<input type="checkbox" onclick="lsch_show_private_key()">Show Private Key
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
		</form>




	</div>


	<?php
