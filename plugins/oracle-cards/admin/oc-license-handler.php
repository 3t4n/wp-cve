<?php
define( 'EDD_ORACLE_CARDS_STORE_URL', 'https://emotionalonlinestorytelling.com/expansion/' );
define( 'EDD_ORACLE_CARDS_ITEM_ID', 1030 );
define( 'EDD_ORACLE_CARDS_ITEM_NAME', 'Oracle Cards' );
define( 'EDD_ORACLE_CARDS_PLUGIN_LICENSE_PAGE', 'oracle-cards-licenses' );

if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	require_once EOS_CARDS_DIR . '/admin/class-eos-oc-license-manager-client-pro.php';
}

/**
 * Initialize the updater. Hooked into `init` to work with the
 * wp_version_check cron job, which allows auto-updates.
 */
add_action( 'init',function() {
	// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
	$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
	if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
		return;
	}
	// retrieve our license key from the DB
	$license_key = trim( get_option( 'eos_oc_license_key' ) );
	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater(
		EDD_ORACLE_CARDS_STORE_URL,
		__FILE__,
		array(
			'version' => EOS_CARDS_PLUGIN_VERSION,  // current version number
			'license' => $license_key,             // license key (used get_option above to retrieve from DB)
			'item_id' => EDD_ORACLE_CARDS_ITEM_ID,       // ID of the product
			'author'  => 'Emotional Online Storytelling', // author of this plugin
			'beta'    => false,
		)
	);
} );

/**
 * Adds the plugin license page to the admin menu.
 *
 * @return void
 */
function eos_oc_license_menu() {
  add_submenu_page(
    'edit-tags.php?taxonomy=decks&post_type=card',
    esc_html__( 'License','oracle-cards' ),
    esc_html__( 'License','oracle-cards' ),
    'edit_posts',
    admin_url( '?page='.EDD_ORACLE_CARDS_PLUGIN_LICENSE_PAGE ),
    null,
    40
  );
  add_submenu_page(
    null,
    esc_html__( 'License','oracle-cards' ),
    esc_html__( 'License','oracle-cards' ),
    'edit_others_posts',
    EDD_ORACLE_CARDS_PLUGIN_LICENSE_PAGE,
    'eos_oc_license_page',
    60
  );
}
add_action( 'admin_menu','eos_oc_license_menu' );

function eos_oc_license_page() {
	add_settings_section(
		'eos_oc_license',
		sprintf( __( '%s License','oracle-cards' ),EDD_ORACLE_CARDS_ITEM_NAME ),
		'eos_oc_license_key_settings_section',
		EDD_ORACLE_CARDS_PLUGIN_LICENSE_PAGE
	);
	add_settings_field(
		'eos_oc_license_key',
		'<label for="eos_oc_license_key">' . __( 'License Key','oracle-cards' ) . '</label>',
		'eos_oc_license_key_settings_field',
		EDD_ORACLE_CARDS_PLUGIN_LICENSE_PAGE,
		'eos_oc_license',
	);
	?>
	<div class="wrap">
		<h2><?php esc_html_e( 'Plugin License Options' ); ?></h2>
		<form method="post" action="options.php">
			<?php
			do_settings_sections( EDD_ORACLE_CARDS_PLUGIN_LICENSE_PAGE );
			settings_fields( 'eos_oc_license' );
			submit_button();
			?>
		</form>
	<?php
}

// add_action( 'admin_init','eos_oc_license_page' );
/**
 * Adds content to the settings section.
 *
 * @return void
 */
function eos_oc_license_key_settings_section() {
	esc_html_e( 'Activate your license.','oracle-cards' );
}

/**
 * Outputs the license key settings field.
 *
 * @return void
 */
function eos_oc_license_key_settings_field() {
	$license = get_option( 'eos_oc_license_key' );
	$status  = get_option( 'eos_oc_license_status' );

	?>
	<p class="description"><?php esc_html_e( 'Enter your license key.' ); ?></p>
	<?php
	printf(
		'<input type="text" class="regular-text" id="eos_oc_license_key" name="eos_oc_license_key" value="%s" />',
		esc_attr( $license )
	);
	$button = array(
		'name'  => 'edd_license_deactivate',
		'label' => __( 'Deactivate License','oracle-cards' ),
	);
	if ( 'valid' !== $status ) {
		$button = array(
			'name'  => 'edd_license_activate',
			'label' => __( 'Activate License','oracle-cards' ),
		);
	}
	wp_nonce_field( 'eos_oc_nonce', 'eos_oc_nonce' );
	?>
	<input type="submit" class="button-secondary" name="<?php echo esc_attr( $button['name'] ); ?>" value="<?php echo esc_attr( $button['label'] ); ?>"/>
	<?php
}

/**
 * Registers the license key setting in the options table.
 *
 * @return void
 */
function eos_oc_register_option() {
	register_setting( 'eos_oc_license', 'eos_oc_license_key', 'eos_oc_sanitize_license' );
}
add_action( 'admin_init', 'eos_oc_register_option' );

/**
 * Sanitizes the license key.
 *
 * @param string  $new The license key.
 * @return string
 */
function eos_oc_sanitize_license( $new ) {
	$old = get_option( 'eos_oc_license_key' );
	if ( $old && $old !== $new ) {
		delete_option( 'eos_oc_license_status' ); // new license has been entered, so must reactivate
	}

	return sanitize_text_field( $new );
}

/**
 * Activates the license key.
 *
 * @return void
 */
function eos_oc_activate_license() {

	// listen for our activate button to be clicked
	if ( ! isset( $_POST['edd_license_activate'] ) ) {
		return;
	}

	// run a quick security check
	if ( ! check_admin_referer( 'eos_oc_nonce', 'eos_oc_nonce' ) ) {
		return; // get out if we didn't click the Activate button
	}

	// retrieve the license from the database
	$license = trim( get_option( 'eos_oc_license_key' ) );
	if ( ! $license ) {
		$license = ! empty( $_POST['eos_oc_license_key'] ) ? sanitize_text_field( $_POST['eos_oc_license_key'] ) : '';
	}
	if ( ! $license ) {
		return;
	}

	// data to send in our API request
	$api_params = array(
		'edd_action'  => 'activate_license',
		'license'     => $license,
		'item_id'     => EDD_ORACLE_CARDS_ITEM_ID,
		'item_name'   => rawurlencode( EDD_ORACLE_CARDS_ITEM_NAME ), // the name of our product in EDD
		'url'         => home_url(),
		'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
	);

	// Call the custom API.
	$response = wp_remote_post(
		EDD_ORACLE_CARDS_STORE_URL,
		array(
			'timeout'   => 15,
			'sslverify' => false,
			'body'      => $api_params,
		)
	);

		// make sure the response came back okay
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

		if ( is_wp_error( $response ) ) {
			$message = $response->get_error_message();
		} else {
			$message = __( 'An error occurred, please try again.','oracle-cards' );
		}
	} else {

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( false === $license_data->success ) {

			switch ( $license_data->error ) {

				case 'expired':
					$message = sprintf(
						/* translators: the license key expiration date */
						__( 'Your license key expired on %s.','oracle-cards' ),
						date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
					);
					break;

				case 'disabled':
				case 'revoked':
					$message = __( 'Your license key has been disabled.','oracle-cards' );
					break;

				case 'missing':
					$message = __( 'Invalid license.','oracle-cards' );
					break;

				case 'invalid':
				case 'site_inactive':
					$message = __( 'Your license is not active for this URL.','oracle-cards' );
					break;

				case 'item_name_mismatch':
					/* translators: the plugin name */
					$message = sprintf( __( 'This appears to be an invalid license key for %s.','oracle-cards' ), EDD_ORACLE_CARDS_ITEM_NAME );
					break;

				case 'no_activations_left':
					$message = __( 'Your license key has reached its activation limit.','oracle-cards' );
					break;

				default:
					$message = __( 'An error occurred, please try again.','oracle-cards' );
					break;
			}
		}
	}

		// Check if anything passed on a message constituting a failure
	if ( ! empty( $message ) ) {
		$redirect = add_query_arg(
			array(
				'page'          => EDD_ORACLE_CARDS_PLUGIN_LICENSE_PAGE,
				'sl_activation' => 'false',
				'message'       => rawurlencode( $message ),
			),
			admin_url( 'plugins.php' )
		);

		wp_safe_redirect( $redirect );
		exit();
	}

	// $license_data->license will be either "valid" or "invalid"
	if ( 'valid' === $license_data->license ) {
		update_option( 'eos_oc_license_key', $license );
	}
	update_option( 'eos_oc_license_status', $license_data->license );
	wp_safe_redirect( admin_url( 'plugins.php?page=' . EDD_ORACLE_CARDS_PLUGIN_LICENSE_PAGE ) );
	exit();
}
add_action( 'admin_init', 'eos_oc_activate_license' );

/**
 * Deactivates the license key.
 * This will decrease the site count.
 *
 * @return void
 */
function eos_oc_deactivate_license() {

	// listen for our activate button to be clicked
	if ( isset( $_POST['edd_license_deactivate'] ) ) {

		// run a quick security check
		if ( ! check_admin_referer( 'eos_oc_nonce', 'eos_oc_nonce' ) ) {
			return; // get out if we didn't click the Activate button
		}

		// retrieve the license from the database
		$license = trim( get_option( 'eos_oc_license_key' ) );

		// data to send in our API request
		$api_params = array(
			'edd_action'  => 'deactivate_license',
			'license'     => $license,
			'item_id'     => EDD_ORACLE_CARDS_ITEM_ID,
			'item_name'   => rawurlencode( EDD_ORACLE_CARDS_ITEM_NAME ), // the name of our product in EDD
			'url'         => home_url(),
			'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
		);

		// Call the custom API.
		$response = wp_remote_post(
			EDD_ORACLE_CARDS_STORE_URL,
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.','oracle-cards' );
			}

			$redirect = add_query_arg(
				array(
					'page'          => EDD_ORACLE_CARDS_PLUGIN_LICENSE_PAGE,
					'sl_activation' => 'false',
					'message'       => rawurlencode( $message ),
				),
				admin_url( 'plugins.php' )
			);

			wp_safe_redirect( $redirect );
			exit();
		}

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if ( 'deactivated' === $license_data->license ) {
			delete_option( 'eos_oc_license_status' );
		}

		wp_safe_redirect( admin_url( 'plugins.php?page=' . EDD_ORACLE_CARDS_PLUGIN_LICENSE_PAGE ) );
		exit();

	}
}
add_action( 'admin_init', 'eos_oc_deactivate_license' );

/**
 * Checks if a license key is still valid.
 * The updater does this for you, so this is only needed if you want
 * to do somemthing custom.
 *
 * @return void
 */
function eos_oc_check_license() {

	$license = trim( get_option( 'eos_oc_license_key' ) );

	$api_params = array(
		'edd_action'  => 'check_license',
		'license'     => $license,
		'item_id'     => EDD_ORACLE_CARDS_ITEM_ID,
		'item_name'   => rawurlencode( EDD_ORACLE_CARDS_ITEM_NAME ),
		'url'         => home_url(),
		'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
	);

	// Call the custom API.
	$response = wp_remote_post(
		EDD_ORACLE_CARDS_STORE_URL,
		array(
			'timeout'   => 15,
			'sslverify' => false,
			'body'      => $api_params,
		)
	);

	if ( is_wp_error( $response ) ) {
		return false;
	}

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	if ( 'valid' === $license_data->license ) {
		echo 'valid';
		exit;
		// this license is still valid
	} else {
		echo 'invalid';
		exit;
		// this license is no longer valid
	}
}

/**
 * This is a means of catching errors from the activation method above and displaying it to the customer
 */
function eos_oc_admin_notices() {
	if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {

		switch ( $_GET['sl_activation'] ) {

			case 'false':
				$message = urldecode( $_GET['message'] );
				?>
				<div class="error">
					<p><?php echo wp_kses_post( $message ); ?></p>
				</div>
				<?php
				break;

			case 'true':
			default:
				// Developers can put a custom success message here for when activation is successful if they way.
				break;

		}
	}
}
add_action( 'admin_notices', 'eos_oc_admin_notices' );
