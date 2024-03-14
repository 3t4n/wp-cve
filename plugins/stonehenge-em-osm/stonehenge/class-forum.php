<?php
if( !defined('ABSPATH') ) exit;
if( !class_exists('Stonehenge_Creations_Forum') ) :
Class Stonehenge_Creations_Forum {

	#===============================================
	public function __construct() {
		add_action('stonehenge_menu', array($this, 'add_submenu_page'), 95);
	}

	#===============================================
	public function add_submenu_page() {
		add_submenu_page(
			'stonehenge-creations',
			'Free Support Forums',
			'Free Support',
			'manage_options',
			'stonehenge_forums',
			array($this, 'show_this_page')
		);
	}

	#===============================================
	public function show_this_page() {
		stonehenge()->load_admin_assets();
		echo '<div class="wrap">';
		echo 	'<h1>Stonehenge Creations – Free Plugins Support Forum</h1>';

		$access = get_site_option('stonehenge_forums');
		if( !isset($access) || empty($access) ) {
			$this->get_access();
		}
		else {
			$url = esc_url( add_query_arg( array( 'access' => strrev($access) ), 'https://support.stonehengecreations.nl/') );
			echo "<iframe src={$url} style='width:98%; max-width: 1000px; height: 700px; margin: 0 auto; display:block; border-bottom:2px solid MidnightBlue;'></iframe>";
		}
		echo '</div>';
	}

	#===============================================
	private function get_access() {
		if( isset($_POST['get_access_key']) ) {
			$this->create_access_key($_POST);
			echo '<meta http-equiv="refresh" content="0">';
		}

		$user 		= wp_get_current_user();
		$display 	= explode(' ', $user->display_name);

		if( !is_array($display) ) {
			$display 	= explode(' ', $user->nicename);
		}

		$first 		= !empty($user->user_firstname) ? $user->user_firstname : @$display[0];
		$last 		= !empty($user->user_lastname) ? $user->user_lastname : @$display[1];

		?>
		<div class="stonehenge_result" style="display:none;"></div>
		<br style="clear:both;">
		<form id="stonehenge_access_form" method="post" action="" data-parsley-validate="" novalidate="">
			<div class="stonehenge-metabox">
				<div class="stonehenge-metabox-header">
					<h3 class="handle">Get Free Access</h3>
				</div>

				<section class="stonehenge-section">
					<table class="stonehenge-table">
						<tr>
							<td colspan="2">
								<p>
									To access the Stonehenge Support Forums you need to register once for a free, life-time access key.<br>
								<strong>Registration is 100% optional,</strong> but required if you would like to get me to provide free support. There are no costs. No hidden fees.</p>
								<p>
									No data will be collected other than what you fill out below. Even your IP address will not be logged. Your data is completely save and will never be sold to or shared with any third-party. You will also not be added to a mailing list, etc. It is purely to log-in to the Stonehenge Creations Support Forums.</p>
								<p>
									After registration you will be automatically logged-in, so there is no hassle.</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo __wp('First Name'); ?></th>
							<td><input type="text" name="first_name" id="first_name" required="required" style="width: 22em;" value="<?php echo $first; ?>"></td>
						</tr>
						<tr>
							<th scope="row"><?php echo __wp('Last Name'); ?></th>
							<td><input type="text" name="last_name" id="last_name" required="required" style="width: 22em;" value="<?php echo $last; ?>"></td>
						</tr>
						<tr>
							<th scope="row"><?php echo __wp('Email'); ?></th>
							<td><input type="email" name="email" id="email" required="required" style="width: 22em;" value="<?php echo $user->user_email; ?>"></td>
						</tr>
						<tr>
							<th scope="row"></th>
							<td>
								<div class="listFieldError" style="display:none;"></div>
								<input type="submit" class="button-primary" name="get_access_key" value="Get Free Access" onclick="setTimeout(showErrors, 100)">
							</td>
						</tr>
					</table>
				</section>
			</div>
		</form>
		<?php
	}


	#===============================================
	private function create_access_key( $input ) {
		$first 	= stonehenge()->localize_name( sanitize_text_field( $input['first_name'] ) );
		$last 	= stonehenge()->localize_name( sanitize_text_field( $input['last_name'] ) );
		$email 	= strtolower( sanitize_email( $input['email'] ) );

		$api_params = array(
			'slm_action' 			=> 'slm_create_new',
			'secret_key' 			=> '5e28165c353286.65466388',
			'first_name' 			=> $first,
			'last_name' 			=> $last,
			'email' 				=> $email,
			'lic_status'			=> 'active',
			'max_allowed_domains' 	=> '10',
			'date_created' 			=> date('Y-m-d'),
			'date_expiry' 			=> date('Y-m-d', strtotime('+5 years')),
            'registered_domain' 	=> home_url(),
		);

		$response = wp_remote_get(add_query_arg($api_params, 'https://support.stonehengecreations.nl'), array('timeout' => 20, 'sslverify' => false));

		if( is_wp_error($response) ) {
			echo "Unexpected Error! The query returned with an error.";
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		if( $license_data ) {
			update_site_option('stonehenge_forums', $license_data->key, 'no');
		}
		return;
	}


} // End class.
endif;

