<?php
namespace Login_With_AJAX\Transports\Traits;

use \Login_With_AJAX\Transports\Transport;

/**
 * Base class for sercices such as WhatsApp, Telegram, Twillio etc. which have shared traits such as receiving generic commands, handling webhooks etc.
 *
 * @property string $method; // Static Property
 *
 */
trait Webhooks {
	
	use Recipient;
	
	public static function init() {
		/** @noinspection PhpUnhandledExceptionInspection */
		static::init_webhooks();
	}
	
	/**
	 * @return void
	 * @throws \Exception
	 */
	public static function init_webhooks() {
		if( !is_subclass_of(static::class, '\Login_With_AJAX\Transports\Transport') ) {
			throw new \Exception('Class '. static::class .' must be subclass of \Login_With_AJAX\Transports\Transport to use \Login_With_AJAX\Transports\Traits\Webhooks');
		}
		if( static::get_webhook_url_endpoint() ) {
			add_action( 'rest_api_init', array( static::class, 'register_webhook' ) );
		}
	}
	
	/**
	 * If susccesful an array of message ids should be returned.
	 *
	 * @return array|\WP_Error
	 */
	public static function send( $recipient, $message, $data = array() ) {
		return new \WP_Error('Send method not implemented for '. static::class);
	}
	
	/**
	 * Allows custom webhook endpoints for classes using this trait, such as Twilio, which wants to use one endpoint for SMS and WhatsApp
	 *
	 * Method can be overriden to provide false value to deactivate webhooks, or a common webhook endpoint for services that allow multiple transports (e.g. Twilio SMS and WhatsApp)
	 *
	 * @return string
	 */
	public static function get_webhook_url_endpoint() {
		return static::$method;
	}
	
	/**
	 * Allows custom hook prefixes/names for classes using this trait, for example Twilio prefixes sms and whatsapp hooks with twilio_ to avoid clashes with other services such as direct WhatsApp integration.
	 * @return mixed
	 */
	public static function get_hook_method() {
		return static::$method;
	}
	
	public static function register_webhook() {
		// capture of payments API
		$route = array(
			'methods'  => 'GET,POST',
			'callback' => array( static::class, 'handle_webhook' ),
			'permission_callback' => '__return_true', // 5.5. compat
		);
		register_rest_route( 'login-with-ajax/v1/'. static::get_webhook_url_endpoint() , 'webhook', $route );
	}
	
	public static function get_webhook_url() {
		return get_rest_url( get_current_blog_id(), 'login-with-ajax/v1/'. static::get_webhook_url_endpoint() .'/webhook' );
	}
	
	public static function get_webhook_commands() {
		return array(
			'authorize' => array(
				'Authorize', esc_html__('Authorize', 'login-with-ajax-pro'),
				'Yes', esc_html__('Yes'),
			),
			'accept' => array(
				'Accept', esc_html__('Accept', 'login-with-ajax-pro'),
				'Yes', esc_html__('Yes'),
			),
			'decline' => array(
				'Decline', esc_html__('Decline', 'login-with-ajax-pro'),
				'Reject', esc_html__('Reject', 'login-with-ajax-pro'),
				'Cancel', esc_html__('Cancel'),
				'No', esc_html__('No'),
			),
			'verify' => array(
				'Verify', esc_html__('Verify', 'login-with-ajax-pro'),
				'Accept', esc_html__('Accept', 'login-with-ajax-pro')
			),
			'disconnect' => array(
				'Disconnect', esc_html__('Disconnect', 'login-with-ajax-pro'),
				'Decline', esc_html__('Decline', 'login-with-ajax-pro'),
			)
		);
	}
	
	/**
	 * Any transport will want to override this, since each payload is different for each service.
	 *
	 * @param array|object $payload Payload from webhook
	 * @param bool $text            Whether to accept text replies as a command as well as button payloads
	 *
	 * @return string
	 */
	public static function get_webhook_command( $payload, $text = false ) {
		return '';
	}
	
	/**
	 * Returns the recipient of the webhook, i.e. the phone number, username, etc. of the user on this site
	 * @param $payload
	 *
	 * @return mixed
	 */
	abstract public static function get_webhook_sender( $payload );
	
	public static function validate_webhook_signature( $header_signature, $payload ) {
		$credentials = static::get_credentials();
		$expected_signature = hash_hmac('sha256', $payload, $credentials['app_secret']);
		return hash_equals($header_signature, 'sha256='.$expected_signature);
	}
	
	/**
	 * Check if supplied command is an accepted webhook command string for the given command context.
	 *
	 * @param string $context   Type of command we are checking for, e.g. 'approve', 'decline', 'verify', 'disconnect'
	 * @param string $command   Command string to check against type of command
	 *
	 * @return bool
	 */
	public static function is_webhook_command ( $context, $command ) {
		if ( $context ) {
			// check if command is a straight key check (e.g. 'approve')
			if ( $command == $context ) {
				return true;
			}
			// get possible commands and see if context is in it
			$commands = self::get_webhook_commands();
			if ( !empty( $commands[ $context ] ) ) {
				return in_array( $command, $commands[ $context ] );
			}
		}
		return false;
	}
	
	public static function get_webhook_responses( $user = null ) {
		$responses_option = get_option('lwa_transport_responses', array());
		$defaults = static::get_default_templates( $user );
		if( !empty($responses_option[static::$method]) ) {
			$responses = array_merge( $responses_option[static::$method], $defaults );
		} else {
			$responses = $defaults;
		}
		return $responses;
	}
	
	public static function parse_message( $message, $user = null ) {
		if( $user === null ) {
			$user = wp_get_current_user();
		}
		// get a text message and replace placeholders in the format of %PLACEHOLDER{1}%, then return an associative array containing the placeholders replaced in a 'message' key and an array of the placeholders, ordered by the number in curly brackets within the placeholders, or order found in a 'placeholders' key
		$return = array(
			'message_original' => $message, // original message, e.g. 'Your code is %CODE%
			'message_templated' => $message, // templated message, e.g. 'Your code is {1}'
			'message' => $message, // message completely parsed and ready to send as-is, e.g. 'Your code is 123456'
			'placheolders' => array(), // array of placeholders, e.g. array( 1 => '123456' )
		);
		$message = preg_match_all('/%([A-Z0-9_]({([0-9]+)})?)%/', $message, $placeholders);
		foreach( $placeholders[1] as $k => $placeholder_full ) {
			// we know we have a placeholder, so we determine the type and order (if applicable) and then add it to placeholders
			$placeholder = $placeholders[2][$k];
			$placeholder_order = (int) $placeholders[3][$k];
			$pos = strpos($return['message_templated'], $placeholder_full);
			if ($pos !== false) {
				$return['message_templated'] = substr_replace($return['message_templated'], $placeholders[2][$k], $pos, strlen($placeholder_full));
			}
			$return['placheolders'][$placeholder_order] = $placeholder;
			// convert
			$return['message'] = str_replace( $placeholder_full, $placeholders[2][$k], $message );
			
		}
	}
	
	public static function parse_message_placeholder( $placeholder, $user = null ) {
		if( $user === null ) {
			$user = wp_get_current_user();
		}
		//WPML global redirect
		$lang = !empty($_REQUEST['lang']) ? sanitize_text_field($_REQUEST['lang']): get_locale();
		$redirect = str_replace('%USERNAME%', $user->user_login, $redirect);
		$redirect = str_replace('%USERNICENAME%', $user->user_nicename, $redirect);
		$redirect = str_replace("%LANG%", $lang.'/', $redirect);
	}
}