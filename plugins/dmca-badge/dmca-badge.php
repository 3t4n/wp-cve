<?php
/*
Plugin Name: DMCA Website Protection Badge
Plugin URI : https://www.dmca.com/WordPress/default.aspx?r=wpd1
Description: Protect your content with a DMCA.com Website Protection Badge. Our badges deter content theft, provide tracking of unauthorized usage (with account), and make takedowns easier and more effective. Visit the plugin site to learn more about DMCA Website Protection Badges, or to register.
Version: 2.1.60
Author: DMCA.com
Text Domain: dmca-badge
Author URI: https://wordpress.org/plugins/dmca-badge/
Plugin URI: https://www.dmca.com/WordPress/default.aspx?r=wpd
License: GPLv2
*/
require( dirname( __FILE__ ) . '/libraries/imperative/imperative.php' );

require_library( 'restian', '0.4.1', __FILE__, 'libraries/restian/restian.php' );
require_library( 'sidecar', '0.5.1', __FILE__, 'libraries/sidecar/sidecar.php' );
require_library( 'dmca-api-client', '0.1.0', __FILE__, 'libraries/dmca-api-client/dmca-api-client.php' );

register_plugin_loader( __FILE__ );

define( 'DMCA_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
define( 'DMCA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );


add_filter( 'dmca_badge_html_raw', 'dmca_badge_override_html_raw', 10, 3 );
add_filter( 'dmca_filters_get_form_field_html', 'dmca_add_wrapper_in_field_html', 10, 2 );
add_action( 'dmca_badge_after_field', 'dmca_add_custom_badge_section', 10, 2 );
add_action( 'admin_footer', 'dmca_custom_scripts_addition' );
add_action( 'wp_ajax_dmca_sync_page', 'dmca_sync_page' );
add_action( 'wp_enqueue_scripts', 'dmca_enqueue_scripts' );
add_action( 'wp_footer', 'dmca_pass_token_to_widget' );


if ( ! function_exists( 'dmca_pass_token_to_widget' ) ) {
	/**
	 * Pass token to dmca widget
	 */
	function dmca_pass_token_to_widget() {
		$error_path = plugin_dir_url(__FILE__) ;
		try {  
			if ( is_user_logged_in() && current_user_can( 'manage_options' ) && ! empty( $token = dmca_get_login_token() ) ) {
				?>
				<script>
					document.cookie = "dmca_token" + "=" + "<?php echo esc_attr( $token ); ?>" + ";path=/";
				</script>
				<?php
			}
		}   
		//catch block  
		

		catch (Exception $e) 
		{  
			echo 'Exception Message: ' .$e->getMessage();  
			if ($e->getSeverity() === E_ERROR) {
				echo("E_ERROR triggered.\n");
			} else if ($e->getSeverity() === E_WARNING) {
				echo("E_WARNING triggered.\n");
			}
			echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
			echo 'ErrorException Message: ' .$er->getMessage();  
			echo "<br> $error_path";
		}  
		catch ( Throwable $th){
			echo 'ErrorException Message: ' .$th->getMessage();
			echo "<br> $error_path";
		}
			
	}
}


if ( ! function_exists( 'dmca_enqueue_scripts' ) ) {
	function dmca_enqueue_scripts() {
		$error_path = plugin_dir_url(__FILE__) ;
		try { 
			$badge_settings = dmca_get_option( 'dmca_badge_settings' );
			$settings       = isset( $badge_settings->values ) ? $badge_settings->values : array();
			
			if ( isset( $settings['badge']['dmca_widget_enable'] ) &&
				$settings['badge']['dmca_widget_enable'] &&
				isset( $settings['badge']['badge_selection'] ) &&
				$settings['badge']['badge_selection'] == 'widget' ) {
				$live_url = 'https://dmca-services.github.io/widget/widget.js';
				wp_enqueue_script( 'dmca-widget', $live_url );
			}
			else{
				//echo '<p>Please Contact your developer.</p>';
			}
		}
			catch (Exception $e) 
		{  
			echo 'Exception Message: ' .$e->getMessage();  
			if ($e->getSeverity() === E_ERROR) {
				echo("E_ERROR triggered.\n");
			} else if ($e->getSeverity() === E_WARNING) {
				echo("E_WARNING triggered.\n");
			}
			echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
			echo 'ErrorException Message: ' .$er->getMessage();  
			echo "<br> $error_path";
		}  
		catch ( Throwable $th){
			echo 'ErrorException Message: ' .$th->getMessage();
			echo "<br> $error_path";
		}
		//finally block  
		
	}
}


if ( ! function_exists( 'dmca_custom_scripts_addition' ) ) {
	function dmca_custom_scripts_addition() {
		
		$error_path = plugin_dir_url(__FILE__) ;
		try { 
			$screen = get_current_screen();

			if ( $screen->id != 'settings_page_dmca-badge-settings' ) {
				return;
			}

		}
		catch (Exception $e) 
		{  
			echo 'Exception Message: ' .$e->getMessage();  
			if ($e->getSeverity() === E_ERROR) {
				echo("E_ERROR triggered.\n");
			} else if ($e->getSeverity() === E_WARNING) {
				echo("E_WARNING triggered.\n");
			}
			echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
			echo 'ErrorException Message: ' .$er->getMessage();  
			echo "<br> $error_path";
		}  
		catch ( Throwable $th){
			echo 'ErrorException Message: ' .$th->getMessage();
			echo "<br> $error_path";
		}
		//finally block  
		
		?>

        <script>window.intercomSettings = {app_id: "ypgdx31r", messengerLocation: "wp-plugin"};</script>
        <script>(function () {
                var w = window;
                var ic = w.Intercom;
                if (typeof ic === "function") {
                    ic('reattach_activator');
                    ic('update', intercomSettings);
                } else {
                    var d = document;
                    var i = function () {
                        i.c(arguments)
                    };
                    i.q = [];
                    i.c = function (args) {
                        i.q.push(args)
                    };
                    w.Intercom = i;

                    function l() {
                        var s = d.createElement('script');
                        s.type = 'text/javascript';
                        s.async = true;
                        s.src = 'https://widget.intercom.io/widget/ypgdx31r';
                        var x = d.getElementsByTagName('script')[0];
                        x.parentNode.insertBefore(s, x);
                    }

                    if (w.attachEvent) {
                        w.attachEvent('onload', l);
                    } else {
                        w.addEventListener('load', l, false);
                    }
                }
            })()</script>

   
		<?php
	}
}


if ( ! function_exists( 'dmca_sync_page' ) ) {
	function dmca_sync_page() {
		$error_path = plugin_dir_url(__FILE__) ;
		try { 
		$page_id     = isset( $_POST['page_id'] ) ? sanitize_text_field( $_POST['page_id'] ) : '';
		$login_token = isset( $_POST['login_token'] ) ? wp_unslash( $_POST['login_token'] ) : '';

		if ( ! $page_id || empty( $page_id ) ) {
			wp_send_json_error();
		}

		wp_send_json_success( dmca_add_protected_item( $page_id, $login_token ) );
	}

	catch (Exception $e) 
			{  
				echo 'Exception Message: ' .$e->getMessage();  
				if ($e->getSeverity() === E_ERROR) {
					echo("E_ERROR triggered.\n");
				} else if ($e->getSeverity() === E_WARNING) {
					echo("E_WARNING triggered.\n");
				}
				echo "<br> $error_path";
			}  
			catch (ErrorException  $er)
			{  
				echo 'ErrorException Message: ' .$er->getMessage();  
				echo "<br> $error_path";
			}  
			catch ( Throwable $th){
				echo 'ErrorException Message: ' .$th->getMessage();
				echo "<br> $error_path";
			}
			//finally block  
	}
}


if ( ! function_exists( 'dmca_get_login_token' ) ) {
	/**
	 * Return login token for api
	 *
	 * @return mixed|void
	 */
	function dmca_get_login_token() {
		$error_path = plugin_dir_url(__FILE__) ;
		try { 
		$dmca_login_token = get_transient( 'dmca_login_token' );

		if ( empty( $dmca_login_token ) ) {
			$settings   = get_option( 'dmca_badge_settings' );
			$settings   = isset( $settings->values ) ? $settings->values : array();
			$email      = isset( $settings['authenticate']['email'] ) ? $settings['authenticate']['email'] : '';
			$password   = isset( $settings['authenticate']['password'] ) ? $settings['authenticate']['password'] : '';
			$base_url   = esc_url_raw( 'https://api.dmca.com', array( 'https' ) );
			$curl       = curl_init();
			$login_data = array( 'email' => $email, 'password' => $password );

			curl_setopt_array( $curl, array(
				CURLOPT_URL            => sprintf( '%s/login', $base_url ),
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => "",
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 30,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => "POST",
				CURLOPT_POSTFIELDS     => json_encode( $login_data ),
				CURLOPT_HTTPHEADER     => array(
					"Content-Type: application/json",
				),
			) );

			$response = curl_exec( $curl );
			$err      = curl_error( $curl );

			curl_close( $curl );

			$dmca_login_token = ! $err ? str_replace( '"', '', $response ) : '';
			set_transient( 'dmca_login_token', $dmca_login_token );
		}
		else{
			//echo '<p>Issue in token.</p>';
		}

		return apply_filters( 'dmca_login_token', $dmca_login_token );
	}

	catch (Exception $e) 
			{  
				echo 'Exception Message: ' .$e->getMessage();  
				if ($e->getSeverity() === E_ERROR) {
					echo("E_ERROR triggered.\n");
				} else if ($e->getSeverity() === E_WARNING) {
					echo("E_WARNING triggered.\n");
				}
				echo "<br> $error_path";
			}  
			catch (ErrorException  $er)
			{  
				echo 'ErrorException Message: ' .$er->getMessage();  
				echo "<br> $error_path";
			}  
			catch ( Throwable $th){
				echo 'ErrorException Message: ' .$th->getMessage();
				echo "<br> $error_path";
			}
			//finally block  
	}
}


if ( ! function_exists( 'dmca_add_protected_item' ) ) {
	/**
	 * Add protected Item
	 *
	 * @param bool $post_id
	 * @param string $token
	 * @param string $item_type
	 *
	 * @return bool|mixed|void
	 */
	function dmca_add_protected_item( $post_id = false, $token = '', $item_type = '' ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try { 
		if ( ! $post_id || empty( $post_id ) ) {
			return false;
		}

		$settings   = get_option( 'dmca_badge_settings' );
		$settings   = isset( $settings->values ) ? $settings->values : array();
		$account_id = isset( $settings['authenticate']['AccountID'] ) ? $settings['authenticate']['AccountID'] : '';
		$account_id = empty( $account_id ) ? get_user_meta( get_current_user_id(), 'dmca_account_id', true ) : $account_id;
		$token      = empty( $token ) ? dmca_get_login_token() : $token;
		$item_type  = empty( $item_type ) ? __( 'Web Page' ) : $item_type;
		$item_post  = get_post( $post_id );
		$item_data  = array(
			'badgeid'     => $account_id,
			'title'       => $item_post->post_title,
			'url'         => get_the_permalink( $item_post ),
			'description' => wp_trim_words( $item_post->post_content, 10 ),
			'status'      => $item_post->post_status,
			'source'      => site_url(),
			'type'        => $item_type,
		);
		$base_url   = esc_url_raw( 'https://api.dmca.com', array( 'https' ) );
		$curl       = curl_init();

		curl_setopt_array( $curl, array(
			CURLOPT_URL            => sprintf( '%s/addProtectedItem', $base_url ),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => 'POST',
			CURLOPT_POSTFIELDS     => json_encode( $item_data ),
			CURLOPT_HTTPHEADER     => array(
				"Content-Type: application/json",
				"Token: $token",
			),
		) );

		$response = curl_exec( $curl );
		$err      = curl_error( $curl );

		curl_close( $curl );

		if ( $err ) {
			return $err;
		}

		update_post_meta( $post_id, 'dmca_submission_status', 'sent' );

		error_log( $response );

		return apply_filters( 'dmca_add_protected_item', $response );
	}

	catch (Exception $e) 
			{  
				echo 'Exception Message: ' .$e->getMessage();  
				if ($e->getSeverity() === E_ERROR) {
					echo("E_ERROR triggered.\n");
				} else if ($e->getSeverity() === E_WARNING) {
					echo("E_WARNING triggered.\n");
				}
				echo "<br> $error_path";
			}  
			catch (ErrorException  $er)
			{  
				echo 'ErrorException Message: ' .$er->getMessage();  
				echo "<br> $error_path";
			}  
			catch ( Throwable $th){
				echo 'ErrorException Message: ' .$th->getMessage();
				echo "<br> $error_path";
			}
			//finally block  
	}
}


if ( ! function_exists( 'dmca_get_option' ) ) {
	/**
	 * Return option value
	 *
	 * @param string $option_key
	 * @param string $default_val
	 *
	 * @return mixed|string|void
	 */
	function dmca_get_option( $option_key = '', $default_val = '' ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try { 
		if ( empty( $option_key ) ) {
			return '';
		}

		$option_val = get_option( $option_key, $default_val );
		$option_val = empty( $option_val ) ? $default_val : $option_val;

//		echo '<pre>'; print_r( $option_key ); echo '</pre>';

		return apply_filters( 'dmca_filters_option_' . $option_key, $option_val );
	}

	catch (Exception $e) 
			{  
				echo 'Exception Message: ' .$e->getMessage();  
				if ($e->getSeverity() === E_ERROR) {
					echo("E_ERROR triggered.\n");
				} else if ($e->getSeverity() === E_WARNING) {
					echo("E_WARNING triggered.\n");
				}
				echo "<br> $error_path";
			}  
			catch (ErrorException  $er)
			{  
				echo 'ErrorException Message: ' .$er->getMessage();  
				echo "<br> $error_path";
			}  
			catch ( Throwable $th){
				echo 'ErrorException Message: ' .$th->getMessage();
				echo "<br> $error_path";
			}
			//finally block  
	}
}


if ( ! function_exists( 'dmca_get_meta' ) ) {
	/**
	 * Return Post Meta Value
	 *
	 * @param bool $meta_key
	 * @param bool $post_id
	 * @param string $default
	 *
	 * @return mixed|string|void
	 */
	function dmca_get_meta( $meta_key = false, $post_id = false, $default = '' ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try { 
		if ( ! $meta_key ) {
			return '';
		}

		$post_id    = ! $post_id ? get_the_ID() : $post_id;
		$meta_value = get_post_meta( $post_id, $meta_key, true );
		$meta_value = empty( $meta_value ) ? $default : $meta_value;

		return apply_filters( 'dmca_filters_get_meta', $meta_value, $meta_key, $post_id, $default );
	}

	catch (Exception $e) 
			{  
				echo 'Exception Message: ' .$e->getMessage();  
				if ($e->getSeverity() === E_ERROR) {
					echo("E_ERROR triggered.\n");
				} else if ($e->getSeverity() === E_WARNING) {
					echo("E_WARNING triggered.\n");
				}
				echo "<br> $error_path";
			}  
			catch (ErrorException  $er)
			{  
				echo 'ErrorException Message: ' .$er->getMessage();  
				echo "<br> $error_path";
			}  
			catch ( Throwable $th){
				echo 'ErrorException Message: ' .$th->getMessage();
				echo "<br> $error_path";
			}
			//finally block  
	}
}


if ( ! function_exists( 'get_dmca_submission_status' ) ) {
	/**
	 * Return html submission status
	 *
	 * @param bool $post_id
	 *
	 * @return mixed|void
	 */
	function get_dmca_submission_status( $post_id = false ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try { 
		$submission_status_r = get_dmca_submission_status_raw( $post_id );
		$submission_status_e = $submission_status_r === 'sent' ? esc_html( 'Sent' ) : esc_html( 'Not Sent' );

		return apply_filters( 'dmca_filters_get_dmca_submission_status', $submission_status_e, $post_id, $submission_status_r );
	}

	catch (Exception $e) 
			{  
				echo 'Exception Message: ' .$e->getMessage();  
				if ($e->getSeverity() === E_ERROR) {
					echo("E_ERROR triggered.\n");
				} else if ($e->getSeverity() === E_WARNING) {
					echo("E_WARNING triggered.\n");
				}
				echo "<br> $error_path";
			}  
			catch (ErrorException  $er)
			{  
				echo 'ErrorException Message: ' .$er->getMessage();  
				echo "<br> $error_path";
			}  
			catch ( Throwable $th){
				echo 'ErrorException Message: ' .$th->getMessage();
				echo "<br> $error_path";
			}
			//finally block  
	}
}


if ( ! function_exists( 'get_dmca_submission_status_raw' ) ) {
	/**
	 * Return html submission raw status
	 *
	 * @param bool $post_id
	 *
	 * @return mixed|void
	 */
	function get_dmca_submission_status_raw( $post_id = false ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try { 
		$post_id             = ! $post_id || empty( $post_id ) ? get_the_ID() : $post_id;
		$submission_status_r = dmca_get_meta( 'dmca_submission_status', $post_id, 'pending' );

		return apply_filters( 'dmca_filters_get_dmca_submission_status_raw', $submission_status_r, $post_id );
	}

	catch (Exception $e) 
			{  
				echo 'Exception Message: ' .$e->getMessage();  
				if ($e->getSeverity() === E_ERROR) {
					echo("E_ERROR triggered.\n");
				} else if ($e->getSeverity() === E_WARNING) {
					echo("E_WARNING triggered.\n");
				}
				echo "<br> $error_path";
			}  
			catch (ErrorException  $er)
			{  
				echo 'ErrorException Message: ' .$er->getMessage();  
				echo "<br> $error_path";
			}  
			catch ( Throwable $th){
				echo 'ErrorException Message: ' .$th->getMessage();
				echo "<br> $error_path";
			}
			//finally block  
	}
}


if ( ! function_exists( 'dmca_add_custom_badge_section' ) ) {
	/**
	 * @param $field_name
	 * @param $form
	 */
	function dmca_add_custom_badge_section( $field_name, $form ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try { 
		if ( $field_name == 'html' ) {
			include DMCA_PLUGIN_DIR . 'templates/badge-regular.php';
		}

		if ( $field_name == 'badge_custom_field' ) {
			include DMCA_PLUGIN_DIR . 'templates/badge-custom.php';
		}
	}

	catch (Exception $e) 
			{  
				echo 'Exception Message: ' .$e->getMessage();  
				if ($e->getSeverity() === E_ERROR) {
					echo("E_ERROR triggered.\n");
				} else if ($e->getSeverity() === E_WARNING) {
					echo("E_WARNING triggered.\n");
				}
				echo "<br> $error_path";
			}  
			catch (ErrorException  $er)
			{  
				echo 'ErrorException Message: ' .$er->getMessage();  
				echo "<br> $error_path";
			}  
			catch ( Throwable $th){
				echo 'ErrorException Message: ' .$th->getMessage();
				echo "<br> $error_path";
			}
			//finally block  
	}
}


if ( ! function_exists( 'dmca_add_wrapper_in_field_html' ) ) {
	function dmca_add_wrapper_in_field_html( $html, $field_name ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try { 
		if ( $field_name == 'html' ) {
			$html .= '</div>';
		}

		return $html;
	}

	catch (Exception $e) 
			{  
				echo 'Exception Message: ' .$e->getMessage();  
				if ($e->getSeverity() === E_ERROR) {
					echo("E_ERROR triggered.\n");
				} else if ($e->getSeverity() === E_WARNING) {
					echo("E_WARNING triggered.\n");
				}
				echo "<br> $error_path";
			}  
			catch (ErrorException  $er)
			{  
				echo 'ErrorException Message: ' .$er->getMessage();  
				echo "<br> $error_path";
			}  
			catch ( Throwable $th){
				echo 'ErrorException Message: ' .$th->getMessage();
				echo "<br> $error_path";
			}
			//finally block  
	}
}


if ( ! function_exists( 'dmca_badge_override_html_raw' ) ) {
	function dmca_badge_override_html_raw( $badge_html, $class, $settings ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try { 
		$badge_settings  = isset( $settings['badge'] ) ? $settings['badge'] : array();
		$badge_selection = isset( $badge_settings['badge_selection'] ) ? $badge_settings['badge_selection'] : 'regular';

		if ( $badge_selection === 'custom' && isset( $badge_settings['custom_badge'] ) ) {
			return $badge_settings['custom_badge'];
		}

		return $badge_html;
	}

	catch (Exception $e) 
			{  
				echo 'Exception Message: ' .$e->getMessage();  
				if ($e->getSeverity() === E_ERROR) {
					echo("E_ERROR triggered.\n");
				} else if ($e->getSeverity() === E_WARNING) {
					echo("E_WARNING triggered.\n");
				}
				echo "<br> $error_path";
			}  
			catch (ErrorException  $er)
			{  
				echo 'ErrorException Message: ' .$er->getMessage();  
				echo "<br> $error_path";
			}  
			catch ( Throwable $th){
				echo 'ErrorException Message: ' .$th->getMessage();
				echo "<br> $error_path";
			}
			//finally block  
	}
}


if ( ! function_exists( 'dmca_badge_get_account_id' ) ) {
	/**
	 * Return account ID
	 *
	 * @return mixed
	 */
	function dmca_badge_get_account_id() {
		$error_path = plugin_dir_url(__FILE__) ;
		try { 
		$settings   = dmca_get_option( 'dmca_badge_settings' );
		$settings   = isset( $settings->values ) ? $settings->values : array();
		$account_id = @$settings['authenticate']['AccountID'];
		$account_id = empty( $account_id ) ? get_user_meta( get_current_user_id(), 'dmca_account_id', true ) : $account_id;

		return $account_id;
	}

	catch (Exception $e) 
			{  
				echo 'Exception Message: ' .$e->getMessage();  
				if ($e->getSeverity() === E_ERROR) {
					echo("E_ERROR triggered.\n");
				} else if ($e->getSeverity() === E_WARNING) {
					echo("E_WARNING triggered.\n");
				}
				echo "<br> $error_path";
			}  
			catch (ErrorException  $er)
			{  
				echo 'ErrorException Message: ' .$er->getMessage();  
				echo "<br> $error_path";
			}  
			catch ( Throwable $th){
				echo 'ErrorException Message: ' .$th->getMessage();
				echo "<br> $error_path";
			}
			//finally block  
	}
}


if ( ! function_exists( 'dmca_badge_get_status_url' ) ) 
{
	/**
	 * Return badge status url
	 *
	 * @param array $append_args
	 *
	 * @return string
	 */
	function dmca_badge_get_status_url( $append_args = array() ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try { 
			$status_url = sprintf( '%s?ID=%s', esc_url( 'https://www.dmca.com/Protection/Status.aspx' ), dmca_badge_get_account_id() );

			if ( is_array( $append_args ) ) {
				foreach ( $append_args as $k => $v ) {
					$status_url .= sprintf( '&%s=%s', $k, $v );
				}
			}

			return $status_url;
		}

	catch (Exception $e) 
	{  
		echo 'Exception Message: ' .$e->getMessage();  
		if ($e->getSeverity() === E_ERROR) {
			echo("E_ERROR triggered.\n");
		} else if ($e->getSeverity() === E_WARNING) {
			echo("E_WARNING triggered.\n");
		}
		echo "<br> $error_path";
	}  
	catch (ErrorException  $er)
	{  
		echo 'ErrorException Message: ' .$er->getMessage();  
		echo "<br> $error_path";
	}  
	catch ( Throwable $th){
		echo 'ErrorException Message: ' .$th->getMessage();
		echo "<br> $error_path";
	}

	}
}