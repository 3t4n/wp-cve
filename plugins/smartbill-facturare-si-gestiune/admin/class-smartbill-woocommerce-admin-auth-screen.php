<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.smartbill.ro
 * @since      1.0.0
 * @package    smartbill-facturare-si-gestiune
 * @subpackage Smartbill_Woocommerce/admin
 */

/**
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @copyright  Intelligent IT SRL 2018
 * @author     Intelligent IT SRL <vreauapi@smartbill.ro>
 */
class Smartbill_Woocommerce_Admin_Auth_Screen {


	/**
	 *  Register auth fields
	 *
	 * @return void
	 */
	public function register_fields() {

		if ( ! empty( $_GET['page'] ) && in_array( $_GET['page'], array( 'smartbill-woocommerce', 'smartbill-woocommerce' ) ) ) {
			$settings['isTaxPayer'] = Smartbill_Woocommerce_Settings::is_vat_payable();
	
			if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
				$errors = get_settings_errors();
				$filtered_errors = array_filter( $errors, function( $value, $key ) {
					return isset( $value['type'] ) &&  'error' == $value['type'] ;
				}, ARRAY_FILTER_USE_BOTH );

				if (0 == count($filtered_errors)) {
            		wp_safe_redirect( admin_url( 'admin.php' ) . '?page=smartbill-woocommerce-settings' );
            		exit;
				}
			}
		}

		// for login.
		register_setting(
			'smartbill_plugin_options',
			'smartbill_plugin_options',
			array( 'sanitize_callback' => array( $this, 'smartbill_plugin_options_auth_validate' ) )
		);
		add_settings_section(
			'smartbill_plugin_login',
			'',
			array( 'Smartbill_Woocommerce_Admin_Auth_Screen', 'plugin_login_section_text' ),
			'smartbill_plugin'
		);
		add_settings_field(
			'smartbill_plugin_options_username',
			__( 'Email utilizator', 'smartbill-woocommerce' ),
			array( 'Smartbill_Woocommerce_Admin_Auth_Screen', 'settings_display_username' ),
			'smartbill_plugin',
			'smartbill_plugin_login'
		);
		add_settings_field(
			'smartbill_plugin_options_password',
			__( 'Token', 'smartbill-woocommerce' ),
			array( 'Smartbill_Woocommerce_Admin_Auth_Screen', 'settings_display_password' ),
			'smartbill_plugin',
			'smartbill_plugin_login'
		);
		add_settings_field(
			'smartbill_plugin_options_vat_code',
			__( 'Cod fiscal (CIF)', 'smartbill-woocommerce' ),
			array( 'Smartbill_Woocommerce_Admin_Auth_Screen', 'settings_display_vat_code' ),
			'smartbill_plugin',
			'smartbill_plugin_login'
		);
		add_settings_field(
			'smartbill_plugin_options_token',
			'',
			array( 'Smartbill_Woocommerce_Admin_Auth_Screen', 'settings_display_token' ),
			'smartbill_plugin',
			'smartbill_plugin_login'
		);
	}

	/**
	 * This function is used to show the header info and the login form
	 *
	 * @return void
	 */
	public function show_auth_window() {
		if ( ! check_smartbill_compatibility() ) {
			show_smartbill_version_err();
		}
		$this->show_header_info();
		$this->show_login_form();
	}

	/**
	 * This function shows  the header information about how to get a SmartBill Cloud account
	 *
	 * @return void
	 */
	public function show_header_info() {
		?>
			<div class="wrap">
				<h2><?php echo esc_attr__( 'SmartBill Facturare/Gestiune', 'smartbill-woocommerce' ); ?></h2>
			</div>
			<p>
				<?php echo esc_attr__( 'Nu ai cont?', 'smartbill-woocommerce' ); ?>
				<br />
				<?php echo esc_attr__( 'Incepe acum gratuit ', 'smartbill-woocommerce' ) . '<a href="https://cloud.smartbill.ro/inregistrare-cont/" target="_blank">' . esc_attr__( 'aici', 'smartbill-woocommerce' ) . '</a>'; ?>.
			</p>

		<?php
	}

	/**
	 * This function shows the SmartBill Cloud login form
	 *
	 * @return void
	 */
	public function show_login_form() {
		?>
		<div class="wrap">
			<h2>
				<?php echo esc_attr__( 'Autentificare SmartBill', 'smartbill-woocommerce' ); ?>
				<?php
				echo esc_attr__( ' v. ', 'smartbill-woocommerce' );
				echo esc_attr( SMARTBILL_PLUGIN_VERSION );
				?>
			</h2>
			<form action="<?php echo esc_url_raw( admin_url( 'options.php' ) ); ?>" method="post">
				<?php settings_fields( 'smartbill_plugin_options' ); ?>
				<?php do_settings_sections( 'smartbill_plugin' ); ?>
				<input name="Submit" type="submit" class="button button-primary" value="<?php echo esc_attr__( 'Autentificare', 'smartbill-woocommerce' ); ?>" />
			</form>
		</div>
		<?php
	}

	/**
	 * Display Logo
	 *
	 * @return void
	 */
	public static function plugin_login_section_text() {
		echo '
            <div>
                <img class="smartbill-login-size" src="' . esc_url_raw( plugin_dir_url( __FILE__ ) . '../assets/images/logo.png' ) . '"/>
            </div>
        ';
	}


	/**
	 * Display Username form input
	 *
	 * @return void
	 */
	public static function settings_display_username() {
		$options = get_option( 'smartbill_plugin_options' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['username'] ) ) {
			$username = trim( $options['username'] );
		} else {
			$username = '';
		}
		echo '<input id="smartbill-settings-username" class="smartbill-login-size" name="smartbill_plugin_options[username]" type="text" value="' . esc_attr( $username ) . '"/>';
	}


	/**
	 * Display password form input
	 *
	 * @return void
	 */
	public static function settings_display_password() {
		$options = get_option( 'smartbill_plugin_options' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['password'] ) ) {
			$password = trim( $options['password'] );
		} else {
			$password = '';
		}
		echo '
    <input id="smartbill_settings_password" class="smartbill-login-size" name="smartbill_plugin_options[password]" type="text" value="' . esc_attr( $password ) . '"/>';
		echo '<p class="description">' . esc_attr__( 'Poti obtine token-ul din contul', 'smartbill-woocommerce' ) . '  <a target="_blank" href="https://cloud.smartbill.ro/core/integrari/">' . esc_attr__( 'SmartBill > Contul Meu > Integrari', 'smartbill-woocommerce' ) . '</a>, ' . esc_attr__( 'zona API.', 'smartbill-woocommerce' ) . '</p>';
	}

	/**
	 * Display company VAT form input
	 *
	 * @return void
	 */
	public static function settings_display_vat_code() {
		$options = get_option( 'smartbill_plugin_options' );
		if ( ! empty( $options ) && is_array( $options ) && isset( $options['vat_code'] ) ) {
			$vat_code = trim( $options['vat_code'] );
		} else {
			$vat_code = '';
		}
		echo '<input id="smartbill_settings_vat_code" class="smartbill-login-size" name="smartbill_plugin_options[vat_code]" type="text" value="' . esc_attr( $vat_code ) . '"/>';
	}

	/**
	 * Display Token form input
	 *
	 * @return void
	 */
	public static function settings_display_token() {
		$options = get_option( 'smartbill_plugin_options' );
		if ( ! empty( $options ) && is_array( $options ) && ! empty( $options['token'] ) ) {
			$token = $options['token'];
		} else {
			$token = '';
		}

		settings_errors( 'smartbill_settings_vat_code' );
		if ( ! empty( $token ) && ! empty( $options['username'] ) && ! empty( $options['password'] ) && ! empty( $options['vat_code'] ) ) {
			echo '<div class="smartbill-succes-message"><strong>' . esc_attr__( 'Conectarea la SmartBill a fost facuta cu succes.', 'smartbill-woocommerce' ) . '</strong></div>';
		}

		echo '<input id="smartbill_settings_token" class="smartbill-login-size" name="smartbill_plugin_options[token]" type="hidden" value="' . esc_attr( $token ) . '"/>';
	}

	/**
	 * Function is used to validate connection to SmartBill Cloud by querying the VAT status.
	 * The previous API had a settings call for the company that retrieved specific info, but the new one doesn't
	 *
	 * @param Array $input form input values before validation.
	 *
	 * @throws \Exception Invalid auth.
	 * @throws Exception Missing VAT.
	 *
	 * @return boolean
	 */
	public static function validate_connection( $input = null ) {
		$options   = $input;
		$logged_in = false;

		try {
			if ( empty( $options['username'] ) || empty( $options['password'] ) ) {
				throw new \Exception( esc_attr__( 'Este necesar sa furnizati un utilizator si o parola valide.', 'smartbill-woocommerce' ) );
			}
			if ( empty( $options['vat_code'] ) ) {
				throw new Exception( esc_attr__( 'Va rugam sa completati toate datele din sectiunea de autentificare.', 'smartbill-woocommerce' ) );
			}
			$vat_code = $options['vat_code'];
			if ( isset( $options['vat_code'] ) ) {
				$vat_code = $input['vat_code'];
			}
			$client = new SmartBill_Cloud_REST_Client( $options['username'], $options['password'] );

			$series = $client->get_document_series( $vat_code );

			$final_inv_s = array();
			if ( isset( $series['list'] ) && is_array( $series['list'] ) ) {
				foreach ( $series['list'] as $ser ) {
					if ( 'f' == $ser['type'] ) {
						$final_inv_s[ $ser['name'] ] = $ser['name'];
					}
				}
				update_option( 'smartbill_invoice_series', array( $vat_code => $final_inv_s ) );
			} else {
				delete_option( 'smartbill_invoice_series', array( $vat_code => $final_inv_s ) );
			}

			$final_pro_s = array();
			if ( isset( $series['list'] ) && is_array( $series['list'] ) ) {
				foreach ( $series['list'] as $ser ) {
					if ( 'p' == $ser['type'] ) {
						$final_pro_s[ $ser['name'] ] = $ser['name'];
					}
				}
				update_option( 'smartbill_estimate_series', array( $vat_code => $final_pro_s ) );
			} else {
				delete_option( 'smartbill_estimate_series', array( $vat_code => $final_pro_s ) );
			}

			$stocks = $client->get_stock( $vat_code );
			if ( is_array( $stocks ) && isset( $stocks['warehouses'] ) ) {
				update_option( 'smartbill_stocks', $stocks['warehouses'] );
			} else {
				delete_option( 'smartbill_stocks' );
			}

			$mu = $client->get_measuring_units( $vat_code );
			if ( is_array( $mu ) && isset( $mu['mu'] ) ) {
				update_option( 'smartbill_s_um', $mu['mu'] );
			} else {
				delete_option( 'smartbill_s_um' );
			}

			$taxes = $client->get_taxes( $vat_code );
			if ( is_array( $taxes ) && isset( $taxes['taxes'] ) ) {
				update_option( 'smartbill_s_taxes', $taxes );
			} else {
				delete_option( 'smartbill_s_taxes' );
			}

			if ( is_array( $taxes ) && isset( $taxes['taxes'] ) ) {
				$logged_in = true;
				add_settings_error( 'smartbill_settings_vat_code', '', esc_attr__( 'Autentificare realizata cu succes!', 'smartbill-woocommerce' ) . '<br/> ' . esc_attr__( 'Acceseaza sectiunea', 'smartbill-woocommerce' ) . ' <a href="' . esc_url_raw( site_url() . '/wp-admin/admin.php?page=smartbill-woocommerce-settings' ) . '">' . esc_attr__( 'SmartBill > Setari', 'smartbill-woocommerce' ) . '</a> ' . esc_attr__( ' pentru configurarea modulului.', 'smartbill-woocommerce' ), 'updated' );
			} else {
				$logged_in = false;
				add_settings_error( 'smartbill_settings_vat_code', '', esc_attr__( 'Eroare la conectare la SmartBill Cloud pentru afisarea setarilor de TVA', 'smartbill-woocommerce' ), 'error' );
			}
		} catch ( \Exception $e ) {
			if ( $e->getMessage() == 'Firma este neplatitoare de tva.' ) {
				$logged_in = true;
				delete_option( 'smartbill_s_taxes' );
				add_settings_error( 'smartbill_settings_vat_code', '', esc_attr__( 'Autentificare realizata cu succes!', 'smartbill-woocommerce' ) . '<br/> ' . esc_attr__( 'Acceseaza sectiunea', 'smartbill-woocommerce' ) . ' <a href="' . esc_url_raw( site_url() . '/wp-admin/admin.php?page=smartbill-woocommerce-settings' ) . '">' . esc_attr__( 'SmartBill > Setari', 'smartbill-woocommerce' ) . '</a> ' . esc_attr__( ' pentru configurarea modulului.', 'smartbill-woocommerce' ), 'updated' );
			} else {
				$logged_in = false;
				add_settings_error( 'smartbill_settings_vat_code', '', $e->getMessage(), 'error' );
			}
		}
		return $logged_in;
	}

	/**
	 * Return auth state after validation.
	 *
	 * @param Array $input form input values before validation.
	 *
	 * @return Array $input
	 */
	public function smartbill_plugin_options_auth_validate( $input ) {
		$input['password'] = sanitize_text_field( $input['password'] );
		$input['username'] = sanitize_text_field( $input['username'] );
		$input['vat_code'] = sanitize_text_field( $input['vat_code'] );

		$input['password'] = trim( $input['password'] );
		$input['username'] = trim( $input['username'] );
		$input['vat_code'] = trim( $input['vat_code'] );

		$is_logged_in = self::validate_connection( $input );

		$input['token'] = $is_logged_in;

		return $input;
	}
}
//phpcs:ignore
