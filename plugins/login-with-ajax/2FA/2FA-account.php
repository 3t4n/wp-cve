<?php
namespace Login_With_AJAX\TwoFA;

use Login_With_AJAX\TwoFA;
use LoginWithAjax;

class Account {
	
	public static function init(){
		//Menu/admin page
		add_action( 'show_user_profile', array( static::class, 'show_profile_fields' ), 1 );
		add_action( 'edit_user_profile', array( static::class, 'show_profile_fields' ), 1 );
		add_action( 'personal_options_update', array( static::class,'save_profile_fields') );
		add_action( 'edit_user_profile_update', array( static::class,'save_profile_fields') );
		if( is_admin() ) {
			global $pagenow;
			if( $pagenow === 'profile.php' || $pagenow === 'user-edit.php' ) {
				add_action( 'admin_enqueue_scripts', array( '\Login_With_AJAX\TwoFA', 'lwa_enqueue' ) );
			}
		}
		// add AJAX return for 2FA request
		add_action('wp_ajax_nopriv_lwa_2FA_setup', array( static::class, 'setup_ajax'));
		add_action('wp_ajax_lwa_2FA_setup', array( static::class, 'setup_ajax'));
		add_action('wp_ajax_nopriv_lwa_2FA_setup_verify', array( static::class, 'setup_verify_ajax'));
		add_action('wp_ajax_lwa_2FA_setup_verify', array( static::class, 'setup_verify_ajax'));
		add_action('lwa_ajax_2FA_setup_save', array( static::class, 'setup_save_ajax'));
		// add shortcode to show editor
		add_shortcode('lwa_2FA_editor', array( static::class, 'shortcode' ) );
		
		// BP integration
		add_action('bp_core_general_settings_before_submit', array( static::class, 'show_profile_fields_current_user' ), 11 );
		add_action('bp_core_general_settings_after_save', array( static::class, 'bp_save_profile_fields' ), 10, 2 );
		
		// WC integration
		add_action('woocommerce_edit_account_form', array( static::class, 'show_profile_fields_current_user' ), 11 );
		add_action('woocommerce_save_account_details', array( static::class, 'save_profile_fields' ), 10, 1 );
	}
	
	public static function shortcode() {
		ob_start();
		if ( is_user_logged_in() ) {
			$user = wp_get_current_user();
			?>
			<div class="lwa-wrapper lwa-bones">
				<div class="lwa pixelbones lwa-2FA" id="lwa-2FA" data-verify-url="<?php echo admin_url('admin-ajax.php'); ?>">
					<?php self::setup_form( $user ); ?>
				</div>
			</div>
			<?php
		} else {
			echo '<p>' . esc_html__('You must be logged in to view this content.', 'login-with-ajax-pro') . '</p>';
		}
		return ob_get_clean();
	}
	
	public static function show_profile_fields_title ( $user ) {
		ob_start();
		?>
		<h4><?php _e('Two Factor Authentication (2FA)','login-with-ajax'); ?></h4>
		<p><?php esc_html_e('2FA provides an extra layer of security in case your username and password is compromised, preventing any attacker from logging into your account without further authentication. We recommend enabling at least one 2FA method to secure your account.', 'login-with-ajax'); ?></p>
		<?php
		echo apply_filters('lwa_2FA_account_show_profile_fields_title', ob_get_clean(), $user);
	}
	
	/**
	 * Adds phone number to contact info of users, compatible with previous phone field method
	 * @param $array
	 * @return array
	 */
	public static function show_profile_fields( $user ){
		static::show_profile_fields_title( $user );
		?>
		<div class="lwa-wrapper lwa-bones">
			<div class="lwa pixelbones lwa-2FA" id="lwa-2FA" data-verify-url="<?php echo admin_url('admin-ajax.php'); ?>">
				<div class="lwa-2FA-setup">
				<?php
					static::setup_form_methods( $user );
				?>
				</div>
			</div>
		</div>
		<?php
	}
	
	public static function show_profile_fields_current_user() {
		static::show_profile_fields( wp_get_current_user() );
	}
	
	/**
	 * Enable or disable 2FA per profile
	 * @param $user_id
	 *
	 * @return false|void
	 */
	public static function save_profile_fields($user_id){
		if ( !current_user_can( 'edit_user', $user_id ) )
			return false;
		$user = get_user_by( 'id', $user_id );
		return static::save_settings( $user );
	}
	
	public static function bp_save_profile_fields( $user_id, $redirect_to ) {
		$user = get_user_by('ID', $user_id);
		$user_meta = LoginWithAjax::get_user_meta( $user_id, '2FA' );
		$result = static::save_profile_fields( $user_id );
		$user_meta_saved = LoginWithAjax::get_user_meta( $user_id, '2FA' );
		if ( $user_meta != $user_meta_saved ) {
			// changes made to 2FA settings, we circumvent only if no attempts made to change email or password, in which case we let BP decide changes notification
			$user_email     = sanitize_email( esc_html( trim( $_POST['email'] ) ) );
			$old_user_email = buddypress()->displayed_user->userdata->user_email;
			if ( empty( $_POST['pass1'] ) && empty( $_POST['pass2'] ) && $user_email === $old_user_email ) {
				// password and email unchanged, so we allow LWA to circumvent
				bp_core_add_message( __( 'Your settings have been saved.', 'buddypress' ), 'success' );
				bp_core_redirect( $redirect_to );
			}
		}
	}
	
	/**
	 * Saves settings from posted data in user profile page.
	 * @param $user
	 *
	 * @return boolean
	 */
	public static function save_settings( $user ) {
		if ( !empty($_REQUEST['lwa_2FA']) && is_array($_REQUEST['lwa_2FA']) && $user instanceof \WP_User ) {
			$user_meta = \LoginWithAjax::get_user_meta( $user->ID, '2FA' );
			if( !is_array($user_meta) ) $user_meta = array();
			foreach ( TwoFA::get_available_methods( $user ) as $method ) {
				if( empty($user_meta['methods'][$method::$method]) || !is_array($user_meta['methods'][$method::$method]) ){
					$user_meta['methods'][$method::$method] = array(
						'enabled' => false
					);
				}
				$user_meta['methods'][$method::$method]['enabled'] = !empty($_REQUEST['lwa_2FA'][$method::$method]);
				$user_meta['methods'][$method::$method] = $method::setup_save( $user_meta['methods'][$method::$method], $user );
			}
			\LoginWithAjax::update_user_meta( $user->ID, '2FA', $user_meta );
			return true;
		}
		return false;
	}
	
	/**
	 * AJAX request handler for when a user logged in and is requesting a form to verify 2FA or set up a new initial method.
	 * @return void
	 */
	public static function setup_ajax() {
		// get nonce and if verified then proceed
		$user = LoginWithAjax::check_user_and_nonce('2FA-setup-');
		if( !empty($user) ) {
			// check if user has methods available
			foreach( TwoFA::$methods as $method ) {
				if( $method::is_available() ) {
					static::setup_welcome( $user );
					exit();
				}
			}
			echo "No Methods Available";
		} else {
			echo 'Not Verified';
		}
		exit();
	}
	
	public static function setup_verify_ajax() {
		// get nonce and if verified then proceed
		if( !empty($_REQUEST['method']) ) {
			$method_type = sanitize_key($_REQUEST['method']);
			$user = LoginWithAjax::check_user_and_nonce( '2FA-setup-verify-' . $method_type .'-');
		}
		if( !empty($user) && !empty($method_type) ) {
			// check if user has methods available
			$method = TwoFA::$methods[ $method_type ];
			$response = $method::setup_verify( $user );
			if ( $response === true || !empty($response['result']) ) {
				if( !is_array($response) ) {
					// generate standard confirmation response based on text functions
					$response = array(
						'result' => true,
						'message' => $method::get_setup_status_ready_text($user),
						'reset' => $method::get_setup_status_reset_text(),
					);
					// if this is the only availble method to the user, then enable it anyway for them
					$methods = TwoFA::get_available_methods( $user );
					if ( count($methods) === 1 ) {
						$meta = LoginWithAjax::get_user_meta( $user->ID, '2FA' );
						if( isset($meta['methods'][$method_type]) && is_array($meta['methods'][$method_type]) ) {
							$meta['methods'][$method_type]['enabled'] = true;
						} else {
							$meta['methods'][$method_type] = array(
								'enabled' => true,
							);
						}
						LoginWithAjax::update_user_meta( $user->ID, '2FA', $meta );;
					}
				}
			} elseif( !is_array($response) && !isset($response['result']) ) {
				// generate standard error response based on text functions
				$response = array(
					'result' => false,
					'error' => $method::get_setup_status_error_text(),
				);
			}
		} elseif ( empty($_REQUEST['method']) ) {
			$response = array(
				'result' => false,
				'error' => 'No method supplied, please contact site admins for assistance.', // not translated as this is a bug and edge (if at all)
			);
		} else {
			$response = array(
				'result' => false,
				'error' => 'Invalid nonce, user or no method supplied.', // not translated as this is a bug and edge (if at all)
			);
		}
		echo json_encode($response);
		exit();
	}
	
	public static function setup_save_ajax( $response ) {
		// get nonce and if verified then proceed
		$user = LoginWithAjax::check_user_and_nonce('2FA-setup-save-');
		if( !empty($user) ) {
			// generate standard error response based on text functions
			include_once('2FA-account.php');
			$response = Account::save_settings($user);
			if ( $response ) {
				// saved, but check that at least one method was saved
				$methods = TwoFA::get_ready_methods($user);
				if( count($methods) > 0 ) {
					if ( is_user_logged_in() ) {
						// saved settings, confirm save
						$response = array(
							'result' => true,
							'message' => esc_html__('2FA Settings Saved.', 'login-with-ajax'), // not translated as this is a bug and edge (if at all)
						);
					} else {
						// verified, log user in and proceed
						remove_all_filters( 'lwa_authenticate' ); // prevent other authentication methods from blocking the 2FA success
						$response = LoginWithAjax::login(); // circumvent and get the regular login response as if a regular login was submitted
						$response['verified_TwoFA'] = true;
						$response['action'] = 'setup_2FA'; // change action so JS doesn't fire login trigger which may refresh page
					}
				} else {
					$response = array(
						'result' => false,
						'error' => esc_html__('Please enable at least one verification method.', 'login-with-ajax'), // not translated as this is a bug and edge (if at all)
					);
				}
			} else {
				$response = array(
					'result' => false,
					'error' => 'Something went wrong, please try again.', // not translated as this is a bug and edge (if at all)
				);
			}
			return $response;
		}
		return $response;
	}
	
	public static function setup_welcome ( $user ) {
		include( LoginWithAjax::locate_template( '2FA/setup.php' ) );
	}
	
	/**
	 * @param \WP_User $user
	 *
	 * @return void
	 */
	public static function setup_form( $user ) {
		include( LoginWithAjax::locate_template( '2FA/setup-form.php' ) );
	}
	
	/**
	 * @param \WP_User $user
	 *
	 * @return void
	 */
	public static function setup_form_methods( $user ) {
		$methods = TwoFA::get_available_methods( $user );
		?>
		<div class="lwa-2FA-methods">
			<?php foreach( $methods as $type => $method ) : ?>
				<?php $method::setup( $user, count($methods) === 1 ); ?>
			<?php endforeach; ?>
		</div>
		<?php
	}
}

Account::init();