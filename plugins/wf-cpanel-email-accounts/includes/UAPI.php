<?php
declare( strict_types=1 );
namespace WebFacing\cPanel;
use WebFacing\cPanel\Email\Main;
use WebFacing\cPanel\Email\Pro;
use function WebFacing\cPanel\Email\error_log;

/**
 * Exit if accessed directly
 */
\class_exists( 'WP' ) || exit;

abstract class UAPI {

	private   const  uapi = 'uapi';

	private   const  ulcb = '/usr/local/cpanel/bin/uapi';

	public    const  exec = 'shell_exec';

	public    static string $uapi = self::uapi;

	public    static int    $api_version = 0;

	public    static string $api_token = '';

	public    static string $cpanel_user;

	protected static int    $transient_time = 20 * \MINUTE_IN_SECONDS;

	protected static array  $transients = [];

	public    static string $response_message = self::exec;

	public    static int    $cache_hits = 0;

	public    static int    $cache_misses = 0;

	public    static function validate_shell( string $uapi = self::uapi ): bool {
		$command = self::$uapi . ' Email account_name account=' . Main::$cpanel_user . ' display=1';
		error_log( __FUNCTION__ . ' ' . self::exec . ' ' . $command );
		$result = @( self::exec )( $command );
		$res_ok = $result && \is_string( $result );

		if ( $res_ok && \str_contains( $result, 'apiversion: ' ) ) {
			self::$api_version = \intval( \explode( ' ', \explode( \PHP_EOL, $result )[1] )[1] ?? '' );
		}
		return $res_ok && \str_contains( $result ?? '', 'data: ' . Main::$cpanel_user );
	}

	public    static function set_shell(): bool {
		$ns = \defined( 'WF_CPANEL_SHELL_EXEC_DISABLE' ) ? \WF_CPANEL_SHELL_EXEC_DISABLE . '\\' : '';

		if ( \function_exists( $ns . self::exec ) && \is_callable( $ns . self::exec ) ) {
			self::$uapi = self::validate_shell() ? self::uapi : ( self::validate_shell( self::ulcb ) ? self::ulcb : '' );
			return ! empty( self::$uapi );
		} else {
			return false;
		}
	}

	public    static function get_defined_api_token(): string {
		return \defined( 'WF_CPANEL_API_TOKEN' ) ? \WF_CPANEL_API_TOKEN : '';
	}

	public    static function validate_token( string $raw_token = '' ): bool {
		$transient_name = Main::$pf . Main::$cpanel_user . ( Main::$remote_cpanel ? '@' . Main::$host_name : '' ) . '.' . __METHOD__;

		if ( \get_transient( $transient_name ) ) {
			return true;
		}

		if ( empty( $raw_token ) ) {
			$raw_token = self::get_defined_api_token();
		}

		if ( empty( $raw_token ) || ! \is_string( $raw_token ) ) {
			return false;
		}
		$args = [ 'headers' => [ 'Authorization' => 'cpanel ' . Main::$cpanel_user . ':' . $raw_token ] ];
		$url = 'https://' . Main::$host_name . ':2083/execute/Email/account_name?account=' . Main::$cpanel_user . '&display=1';
		error_log( $url );
		$response = \wp_remote_head( $url, $args  );
		self::$response_message = \wp_remote_retrieve_response_code( $response ) . ' ' . \wp_remote_retrieve_response_message( $response );
		$result = ! \is_wp_error( $response ) && \intval( ( \wp_remote_retrieve_response_code( $response ) ?: 0 ) / 100 ) === 2;

		if ( $result ) {
			\set_transient( $transient_name, true, 2 * \MINUTE_IN_SECONDS );
		} else {
			\delete_transient( $transient_name );
		}
		return $result;
	}

	protected static function call( string $module, string $function, array $params = [], string $output = 'json' ): ?string {

		if ( Main::$has_http && ! Main::$use_exec ) {
			$token = self::$api_token;
			$params = \array_map( 'rawurlencode', $params );
			$url  = \add_query_arg( $params, 'https://' . Main::$host_name . ':2083/execute/' . $module . '/' . $function );
			$url = \str_replace( '&_', '&', $url );
			error_log( $url );
			$args = [ 'headers' => [ 'Authorization' => 'cpanel ' . $token ] ];
			$response = \wp_remote_get( $url, $args );
			$body = \wp_remote_retrieve_body( $response );
			self::$response_message = \wp_remote_retrieve_response_code( $response ) . ' ' . \wp_remote_retrieve_response_message( $response );
			return $body;
		} elseif ( Main::$has_exec ) {
			$paramst = '';

			foreach( $params as $name => $value ) {
				$paramst .= ' ' . $name . '=' . ( \is_bool( $value ) || \is_integer( $value ) ? (int) $value : "'" . \rawurlencode( $value ?? '' ) . "'" );
			}
			self::$response_message = self::uapi . ' ok';
			error_log( self::exec . ' ' . self::$uapi . ' ' . $module . ' ' . $function . $paramst );
			return ( self::exec )( self::uapi . ' --output=' . $output . ' ' . $module . ' ' . $function . $paramst );
		} else {
			return '{}';
		}
	}

	public    static function result( string $module, string $function, array $params = [], bool $return = false, bool $cache = true )/*: \stdClass | array*/ {

		if ( $return ) {
			$response_json = self::call( $module, $function, $params );
			$response = \json_decode( $response_json );
//			error_log( [ 'Response: ', $response ] );
		} else {
			$get_fresh = true;
			$param_hash = \serialize( $params );
			$transient_name = Main::$pf . Main::$cpanel_user . ( Main::$remote_cpanel ? '@' . Main::$host_name : '' ) . '_' . $module . '-' . $function . ( \count( $params ) ? '-' . ( Main::$is_debug ? $param_hash : \md5( $param_hash ) ) : '' );
			$transients[] = $transient_name;

			if ( $cache ) {
				$response = \json_decode( \get_transient ( $transient_name ) ?: '' );
//				$get_fresh = ! (
//					\is_int( $response ) ||
//					( \is_string( $response ) && \strlen( $response ) > 0   ) ||
//					( \is_object( $response ) && \property_exists( $response, 'data' ) && \is_array (  $response->data ) && \count( $response->data ) > 0 ) ||
//					( \is_object( $response ) && \property_exists( $response, 'result' ) && \is_object( $response->result ) && \property_exists( $response, 'data'  ) && \is_array( $response->data ) && \count( $response->data ) > 0 ) ||
//				false );
				$get_fresh = empty( $response );
			}

			if ( $get_fresh ) {
				$response_json = self::call( $module, $function, $params );
				\set_transient( $transient_name, $response_json, self::$transient_time );
				$response = \json_decode( $response_json ?? '{}' );

				if ( $cache ) {
					$transient_name = Main::$pf . 'cache_missses';
					self::$cache_misses = \intval( \get_transient( $transient_name ) );
					self::$cache_misses++;
					\set_transient( $transient_name, self::$cache_misses, self::$transient_time );
					$transients[] = $transient_name;
				}
			} else {
				$transient_name = Main::$pf . 'cache_hits';
				self::$response_message = 'cached';
				self::$cache_hits = \intval( \get_transient( $transient_name ) );
				self::$cache_hits++;
				\set_transient( $transient_name, self::$cache_hits, self::$transient_time );
				$transients[] = $transient_name;
			}
			$transient_name = Main::$pf . 'transients';
			\set_transient( $transient_name, \array_unique( \array_merge( (array) \get_transient( $transient_name ), $transients ) ), self::$transient_time );
		}

		if ( ! self::$api_version && \is_object( $response ) && \property_exists( $response, 'apiversion' ) ) {
			self::$api_version = \intval( $response->apiversion );
		}
		return $response && \property_exists( $response, 'result' ) ? ( $return ? $response->result ?? ( new \stdClass ) : $response->result->data ?? [] ) : ( $return ? $response ?? ( new \stdClass ) : $response->data ?? [] );
	}

	public    static function has_features( array $features = [ 'popaccts', 'forwarders' ] ): bool {
		$has = true;

		foreach ( $features as $feature ) {
			$has = $has && self::result( 'Features', 'has_feature', [ 'name' => $feature ] );
		}
		return $has;
	}

	public    static function feature_names(): array {
		$result = self::result( 'Features', 'get_feature_metadata' );
		return \wp_list_pluck( $result, 'name', 'id' );
	}

	public    static function set_locale( string $locale = '' ): bool {
		$set_locale = $locale ?: \explode( '_', \get_locale(), 2 )[0];
		$change = $set_locale !== self::result( 'Locale', 'get_attributes' )->locale;

		if ( $change ) {
			self::result( 'Locale', 'set_locale', [ 'locale' => $set_locale ] );
		}
		return $change;
	}

	public    static function server_info( string $name = '' )/*: array|string|int*/ {

		if ( empty( $name ) ) {
			return self::result( 'Variables', 'get_server_information' );
		} else {
			return self::result( 'Variables', 'get_server_information', [ 'name' => $name ] )->$name;
		}
	}

	public    static function session_info( string $name = '' )/*: array|string|int*/ {

		if ( empty( $name ) ) {
			return self::result( 'Variables', 'get_session_information' );
		} else {
			return self::result( 'Variables', 'get_session_information', [ 'name' => $name ] )->$name;
		}
	}
	public    static function quotas(): \stdClass {
		return self::result( 'Quota', 'get_local_quota_info' );
	}

	public    static function user( string $name = '' ): string {
		if ( empty( $name ) ) {
			return self::result( 'Variables', 'get_user_information' )->user;
		} else {
			return self::result( 'Variables', 'get_user_information', [ 'name' => $name ] )->$name;
		}
	}

	public    static function user_created(): ?int {
		return \intval( self::result( 'Variables', 'get_user_information' )->created ?? null );
	}

	public    static function user_updated(): ?int {
		return \intval( self::result( 'Variables', 'get_user_information' )->last_modified ?? null );
	}

	public    static function home_path(): string {
		return self::result( 'Variables', 'get_user_information' )->home;
	}

	public    static function two_factor(): bool {
		return \boolval( self::result( 'TwoFactorAuth', 'get_user_configuration' )->is_enabled );
	}

	public    static function main_domain(): string {
		return self::result( 'DomainInfo', 'list_domains' )->main_domain;
	}

	public    static function list_domains( string $domain_type = 'addon_domains' ): array {
		return self::result( 'DomainInfo', 'list_domains' )->$domain_type ?? [];
	}

	public    static function dead_domains(): ?array {
		return self::result( 'Variables', 'get_user_information', [ 'name' => 'dead_domains' ] )->dead_domains;
	}

	public    static function main_email_account(): \stdClass {
		$account = new \stdClass;
		$domain = self::main_domain();
		$account->domain = $domain;
		$account->email = self::result( 'Variables',  'get_user_information' )->user . '@' . $domain;
		$account->_diskused  = (int) self::result( 'Email', 'get_main_account_disk_usage_bytes' );
		$account->diskquota = '∞';
		return $account;
	}

	public    static function count_emails(): int {
		return \intval( self::result( 'Email', 'count_pops' ) );
	}

	public    static function email_accounts( bool $with_disk = false, string $domain = '' ): ?array {
		$params = [ 'infinitylang' => 1/*, 'maxaccounts' => 999*/ ];

		if ( $domain ) {
			$with_disk = true;
			$params['domain'] = $domain;
		}

		if ( $with_disk ) {
			return self::result( 'Email', 'list_pops_with_disk', $params );
		} else {
			return self::result( 'Email', 'list_pops', [ 'no_validate' => true, 'skip_main' => true ] );
		}
	}

	public    static function email_forwarders(): ?array {
		return self::result( 'Email', 'list_forwarders' );
	}

	public    static function webmail_apps( string $col = 'displayname' ): array {
		$result = (array) self::result( 'WebmailApps', 'list_webmail_apps' );
		return \array_column( $result, $col );
	}

	public    static function webmail_settings( string $email ): string {
		return self::result( 'Email', 'get_webmail_settings' )->domain;
	}

	public    static function main_disk_usage(): int {
		return (int) self::result( 'Email', 'get_main_account_disk_usage_bytes' );
	}

	public    static function email_disk_usage(): int {
		$used = self::main_disk_usage();

		foreach ( (array) self::result( 'Email', 'list_pops_with_disk' ) as $account ) {
			$used += (int) $account->_diskused;
		}
		return $used;
	}

	public    static function maximum_emails(): int {
		return (int) self::result( 'Variables', 'get_user_information' )->maximum_emails_per_hour;
	}

	public    static function queued_emails( string $email ): int {
		return (int) self::result( 'Email', 'get_held_message_count', [ 'email' => $email ] );
	}

	public    static function all_queued_emails(): int {
		$held = 0;

		foreach ( (array) self::result( 'Email', 'list_pops' ) as $account ) {
			$held += self::queued_emails( $account->email );
		}
		return $held;
	}

	public    static function add_forwarder( string $from, string $to, bool $batch = false ): \WP_Error {
		$domain = \explode( '@', $from )[1];
		$result = self::result( 'Email', 'add_forwarder', [
			'domain'   => $domain,
			'email'    => $from,
			'fwdopt'   => 'fwd',
			'fwdemail' => $to,
		], true );
		$errors = new \WP_Error;

		if ( ( $result->status ?? 0 ) === 0 && \is_array( $result->errors ) ) {

			foreach ( $result->errors as $code => $error ) {

				if ( ! empty( $error ) ) {
					$errors->add( $code, $error );
				}
			}
		} else {
			$count  = \count( \explode( ',', $to ) );
			$errors = Main::register_usage( $errors, __FUNCTION__, $count, $batch );
		}
		self::delete_transients();
		return $errors;
	}

	public    static function add_fail( string $email, string $message = '' ): \WP_Error {
		$errors = new \WP_Error;
		$domain = \explode( '@', $email, 2 )[1];
		$args   = [
			'domain'   => $domain,
			'email'    => $email,
			'fwdopt'   => 'fail',
		];

		if ( ! empty ( $message ) ) {
			$args['failmsgs'] = $message;
		}
		$result = self::result( 'Email', 'add_forwarder', $args , true );

		if ( ( $result->status ?? 0 ) === 0 && \is_array( $result->errors ) ) {

			foreach ( $result->errors as $code => $error ) {

				if ( ! empty( $error ) ) {
					$errors->add( $code, $error );
				}
			}
		}
		self::delete_transients();
		return $errors;
	}

	public    static function add_blackhole( string $email ): \WP_Error {
		$errors = new \WP_Error;
		$domain = \explode( '@', $email )[1];
		$args   = [
			'domain'   => $domain,
			'email'    => $email,
			'fwdopt'   => 'blackhole',
		];
		$result = self::result( 'Email', 'add_forwarder', $args , true );

		if ( ( $result->status ?? 0 ) === 0 && \is_array( $result->errors ) ) {
			foreach ( $result->errors as $code => $error ) {
				if ( ! empty( $error ) ) {
					$errors->add( $code, $error );
				}
			}
		}
		self::delete_transients();
		return $errors;
	}

	public    static function add_account( string $email, string $password, bool $batch = false ): \WP_Error {
		$user   = \explode( '@', $email )[0];
		$domain = \explode( '@', $email )[1];
		$result = self::result( 'Email', 'add_pop', [
			'email'              => $user,
			'password'           => $password,
			'domain'             => $domain,
			'send_welcome_email' => \apply_filters( Main::pf . 'send_welcome_email', true, __METHOD__ ),
		], true );
		$errors = new \WP_Error;

		if ( ( $result->status ?? 0 ) === 0 && \is_array( $result->errors ) ) {

			foreach ( $result->errors as $code => $error ) {

				if ( ! empty( $error ) ) {
					$errors->add( $code, $error );
				}
			}
		} else {
			$count = 1;
			$errors = Main::register_usage( $errors, __FUNCTION__, $count, $batch );
		}
		self::delete_transients();
		return $errors;
	}

	public    static function add_responder( string $email, string $from, string $subject, string $body, int $start, int $stop, int $interval ): \WP_Error {
		$user   = \explode( '@', $email )[0];
		$domain = \explode( '@', $email )[1];
		$result = self::result( 'Email', 'add_auto_responder', [
			'body'     => $body,
			'domain'   => $domain,
			'email'    => $user,
			'from'     => $from,
			'interval' => $interval,
			'is_html'  => false,
			'start'    => $start,
			'stop'     => $stop,
			'subject'  => $subject,
		], true );
		$errors = new \WP_Error;

		if ( ( $result->status ?? 0 ) === 0 && \is_array( $result->errors ) ) {

			foreach ( $result->errors as $code => $error ) {

				if ( ! empty( $error ) ) {
					$errors->add( $code, $error );
				}
			}
		}
		return $errors;
	}

	public    static function delete_account( string $email ): \WP_Error {
		$domain = \explode( '@', $email )[1];
		$result = self::result( 'Email', 'delete_pop', [
			'email'    => $email,
			'domain'   => $domain,
		], true );
		$errors = new \WP_Error;

		if ( ( $result->status ?? 0 ) === 0 && \is_array( $result->errors ) ) {

			foreach ( $result->errors as $code => $error ) {

				if ( ! empty( $error ) ) {
					$errors->add( $code, $error );
				}
			}
		}
		self::delete_transients();
		return $errors;
	}

	public    static function delete_forward( string $email, string $dest_dirs ): \WP_Error {
		$errors = new \WP_Error;

		foreach ( \explode( ',', $dest_dirs ) as $dest_dir ) {
			$result = self::result( 'Email', 'delete_forwarder', [
				'address'   => $email,
				'forwarder' => $dest_dir,
			], true );

			if ( ( $result->status ?? 0 ) === 0 && \is_array( $result->errors ) ) {

				foreach ( $result->errors as $code => $error ) {

					if ( ! empty( $error ) ) {
						$errors->add( $code, $error );
					}
				}
			}
		}
		self::delete_transients();
		return $errors;
	}

	public    static function delete_responder( string $email ): \WP_Error {
		$errors = new \WP_Error;
		$result = self::result( 'Email', 'delete_auto_responder', [
			'email'   => $email,
		], true );

		if ( ( $result->status ?? 0 ) === 0 && \is_array( $result->errors ) ) {

			foreach ( $result->errors as $code => $error ) {

				if ( ! empty( $error ) ) {
					$errors->add( $code, $error );
				}
			}
		}
		return $errors;
	}

	public    static function send_settings( string $email, string $to ): \WP_Error {
		$errors = new \WP_Error;

		if ( Main::$is_proisp ) {
			$domain = \explode( '@', $email )[1];
			$res = \wp_mail(
				$to,
				'[' . $domain . '] Serverinformasjon for oppsett av e-post for ' . $email,
				'Se https://support.proisp.com/hc/nb/articles/10203805481617-Serverinformasjon-for-oppsett-av-e-post-',
			);

			if ( ! $res ) {
				$errors->add( 300, 'Mail could not be sent.' );
			}
		} else {
			$result = self::result( 'Email', 'dispatch_client_settings', [
				'account' => $email,
				'to'      => $to,
			], true );

			if ( ( $result->status ?? 0 ) === 0 && \is_array( $result->errors ) ) {

				foreach ( $result->errors as $code => $error ) {

					if ( ! empty( $error ) ) {
						$errors->add( $code, $error );
					}
				}
			}
		}
		return $errors;
	}

	public    static function create_main_webmail_session( ?string $remote_address = null ): ?\stdClass {
		$session = self::result( 'Session', 'create_webmail_session_for_self', [ 'remote_address' => $remote_address ?? $_SERVER['REMOTE_ADDR'] ] );
		$session->hostname = $session->hostname ?? Main::$host_name;
		return $session;
	}

	public    static function create_webmail_session( string $email, ?string $remote_address = null ): ?\stdClass {
		$user    = \explode( '@', $email )[0];
		$domain  = \explode( '@', $email )[1] ?? Main::$main_domain;
		$session = (object) self::result( 'Session', 'create_webmail_session_for_mail_user', [ 'domain' => $domain, 'login' => $user, 'remote_address' => $remote_address ?? $_SERVER['REMOTE_ADDR'] ] );
		\do_action( 'qm/debug', 'For {email} got {session}', [ 'email' => $email, 'session' => $session ] );
		$session->hostname = $session->hostname ?? Main::$host_name;
		return $session;
	}

	public    static function set_password( string $email, string $password ): \WP_Error {
		$errors = new \WP_Error;

		if ( empty( $password ) ) {
			$errors->add( 1, 'Empty password - not set' );
		} else {
			$domain = \explode( '@', $email )[1];
			$result = self::result( 'Email', 'passwd_pop', [
				'email'    => $email,
				'password' => $password,
				'domain'   => $domain,
			], true );
		}

		if ( ( $result->status ?? 0 ) === 0 && \is_array( $result->errors ) ) {

			foreach ( $result->errors as $code => $error ) {

				if ( ! empty( $error ) ) {
					$errors->add( $code, $error );
				}
			}
		}
		return $errors;
	}

	public    static function set_quota( string $email, int $quota ): \WP_Error {
		$errors = new \WP_Error;
		$user   = \explode( '@', $email )[0];
		$domain = \explode( '@', $email )[1];
		$result = self::result( 'Email', 'edit_pop_quota', [
			'domain'   => $domain,
			'email'    => $user,
			'quota'    => $quota,
		], true );

		if ( ( $result->status ?? 0 ) === 0 && \is_array( $result->errors ) ) {

			foreach ( $result->errors as $code => $error ) {

				if ( ! empty( $error ) ) {
					$errors->add( $code, $error );
				}
			}
		}
		self::delete_transients();
		return $errors;
	}

	public    static function get_user_info(): \stdClass {
		return self::result( 'Variables', 'get_user_information' );
	}

	public    static function set_contact_info( string $primary, string $secondary, string $old_primary, string $old_secondary, string $password ): \WP_Error {
		$errors = new \WP_Error;

		if ( $primary === $old_primary && $secondary === $old_secondary ) {
			$errors->add( 0, _x( 'No changes made.', 'Notice info message' ) );
		} elseif ( ( empty( $primary ) && ! empty( $old_primary ) ) || ( empty( $secondary ) && ! empty( $old_secondary ) ) ) {
			$params = [
				 'old_address' => $old_primary,
				'_old_address' => $old_secondary,
					'password' => $password,
			];

			if ( empty( $params['_old_address'] ) ) {
				unset ( $params['_old_address'] );
			}
			$result = self::result( 'ContactInformation', 'unset_email_addresses', $params, true );

			if ( ( $result->status ?? 0 ) === 0 && \is_array( $result->errors ) ) {

				foreach ( $result->errors as $code => $error ) {

					if ( ! empty( $error ) ) {
						$errors->add( $code, 'ContactInformation::unset_email_addresses: ' . $error );
					}
				}
			} else {
				$old_primary   = '';
				$old_secondary = '';
			}
		}

		if ( empty( $primary ) && empty( $secondary ) ) {
			$errors->add( 1, _x( 'Contact info removed.', 'Notice success message' ) );
		} else {
			$params = [
					 'address' => $primary,
					'_address' => $secondary,
				 'old_address' => $old_primary,
				'_old_address' => $old_secondary,
					'password' => $password,
			];
			$params = \array_filter( $params, static fn( string $val ): bool => ! empty( $val ) );
			$result = self::result( 'ContactInformation', 'set_email_addresses', $params, true );

			if ( ( $result->status ?? 0 ) === 0 && \is_array( $result->errors ) ) {

				foreach ( $result->errors as $code => $error ) {

					if ( ! empty( $error ) ) {
						$errors->add( $code, 'ContactInformation::set_email_addresses: ' . $error );
					}
				}
			}
		}
		self::delete_transients();
		return $errors;
	}

	public    static function mail_domains(): array {
		return \array_filter( \wp_list_pluck( self::result( 'Email', 'list_mail_domains' ), 'domain' ), static function( string $element ): bool {
			return \count( \explode( '.', $element ) ) <= Main::$main_parts;
		} );
	}

	public    static function default_address( string $domain ): string {
		return self::result( 'Email', 'list_default_address', [ 'domain' => $domain ] )[0]->defaultaddress;
	}

	public    static function set_default_email( string $domain, $dest ): \WP_Error {
		$errors = new \WP_Error;
		$result = self::result( 'Email', 'set_default_address', [
			'domain'    => $domain,
			'fwdopt'    => 'fwd',
			'fwdemail'  => $dest,
		], true );

		if ( ( $result->status ?? 0 ) === 0 && \is_array( $result->errors ) ) {

			foreach ( $result->errors as $code => $error ) {

				if ( ! empty( $error ) ) {
					$errors->add( 2, $error );
				}
			}
		}
		self::delete_transients();
		return $errors;
	}

	public    static function set_default_fail( string $domain, $message = '' ): \WP_Error {
		$errors = new \WP_Error;
		$args   = [
			'domain'   => $domain,
			'fwdopt'   => 'fail',
		];
		if ( ! empty ( $message ) ) {
			$args['failmsgs'] = $message;
		}
		$result = self::result( 'Email', 'set_default_address', $args, true );

		if ( ( $result->status ?? 0 ) === 0 && \is_array( $result->errors ) ) {

			foreach ( $result->errors as $code => $error ) {

				if ( ! empty( $error ) ) {
					$errors->add( $code, $error );
				}
			}
		}
		self::delete_transients();
		return $errors;
	}

	public    static function set_default_blackhole( string $domain ): \WP_Error {
		$errors = new \WP_Error;
		$result = self::result( 'Email', 'set_default_address', [
			'domain'    => $domain,
			'fwdopt'    => 'blackhole',
		], true );

		if ( ( $result->status ?? 0 ) === 0 && \is_array( $result->errors ) ) {

			foreach ( $result->errors as $code => $error ) {

				if ( ! empty( $error ) ) {
					$errors->add( $code, $error );
				}
			}
		}
		self::delete_transients();
		return $errors;
	}

	public    static function email_responders( string $domain = '' ) {

		if ( $domain ) {
			$params = [ 'domain' => $domain ];
		}
		return self::result( 'Email', 'list_auto_responders', $params );
	}

	public    static function email_responder( string $email ) {
		$params = ['email' => $email ];
		return self::result( 'Email', 'get_auto_responder', $params );
	}

	public    static function email_mailboxes( string $email = '' ): array {
		$email = empty( $email ) ? self::result( 'Variables',  'get_user_information' )->user : $email;
		$params = [ 'account' => $email ];
		return self::result( 'Mailboxes', 'get_mailbox_status_list', $params );
	}

	public    static function delete_messages( string $email, string $guid, string $query = 'savedbefore 52w' ) {
		$email = empty( $email ) ? self::result( 'Variables',  'get_user_information' )->user : $email;
		$params = [ 'account' => $email, 'mailbox_guid' => $guid, 'query' => $query ];
		$result = self::result( 'Mailboxes', 'expunge_messages_for_mailbox_guid', $params );
		self::delete_transients();
		return $result;
	}

	private   static function trace_destinations( array $dest_dirs, string $address = 'then', bool $last_only = false ): array {
		$key = \array_key_last( $dest_dirs );
		$address = $dest_dirs[ $key ]->address ?? $address;
//		$result = [ [ 'address' => Main::email_to_utf8( $address ), 'type' => $dest_dirs[ $key ]->type, 'message' => $dest_dirs[ $key ]->message ?? '', 'mx' => \explode( ' ', $dest_dirs[ $key ]->mx[0]->hostname ?? '' )[0], 'mailbox' => $dest_dirs[ $key ]->mailbox ?? false ] ];
		$result = $key ? [ [ 'address' => Main::email_to_utf8( $address ), 'type' => $dest_dirs[ $key ]->type, 'message' => $dest_dirs[ $key ]->message ?? '', 'mx' => [ (object) [ 'hostname' => $dest_dirs[ $key ]->mx[0]->hostname ?? null ] ], 'mailbox' => $dest_dirs[ $key ]->mailbox ?? false ] ] : [];

		if ( \is_object( $dest_dirs[ $key ] ?? null ) && \property_exists( $dest_dirs[ $key ], 'destinations' ) ) {

			if ( $last_only ) {
				$result = self::trace_destinations( $dest_dirs[ $key ]->destinations, $address, $last_only );
			} else {
				$result = \array_merge( $result, self::trace_destinations( $dest_dirs[ $key ]->destinations, $address ) );
			}
		}
		return $result;
	}

	public    static function auto_accept( string $domain ): bool {
		$result = self::result( 'Email', 'list_mxs', [ 'domain' => $domain ], true );

		if ( $result->status ) {
			return $result->data[0]->mxcheck === 'auto';
		} else {
			return false;
		}
	}

	public    static function set_always_accept( string $domain, bool $accept = true ): \WP_Error {
		$errors = new \WP_Error;
		$result = self::result( 'Email', 'set_always_accept', [ 'domain' => $domain, 'alwaysaccept' => $accept ? 'auto' : 'remote' ], true );

		if ( ( $result->status ?? 0 ) === 0 && \is_array( $result->errors ) ) {

			foreach ( $result->errors as $code => $error ) {

				if ( ! empty( $error ) ) {
					$errors->add( $code, $error );
				}
			}
		}
		return $errors;
	}

	public    static function subaccounts(): array {
		$text = _x( 'email',   'cPanel® Service' );
		$text = _x( 'ftp',     'cPanel® Service' );
		$text = _x( 'webdisk', 'cPanel® Service' );
		$accounts = [];
//		echo '<pre>'; var_dump( self::result( 'UserManager', 'list_users' ) );echo '</pre>';

		foreach ( (array) self::result( 'UserManager', 'list_users' ) as $account ) {

			if ( ! \str_starts_with( $account->username, self::$cpanel_user ) ) {
				$o_account = new \stdClass;
				$o_account->name = $account->username;

				foreach ( (array) $account->services as $sname => $service ) {

					if ( $service->enabled ?? false ) {
						$o_account->services[] = _x( $sname, 'cPanel® Service' );
					}
				}
				$accounts[ $account->real_name ?? $account->full_username ] = $o_account;
			}
		}
		return $accounts;
	}

	public    static function create_backup( ?string $email = null ): void {
		self::result( 'Backup', 'fullbackup_to_homedir', $email ? [ 'email' => $email ] : [] );
	}

	public    static function backups( $creds ): array {
		global $wp_filesystem;
//		return self::result( 'Backup', 'list_backups', [], true );
		\WP_Filesystem( $creds );
		$suffix    = '.tar.gz';
		$obscura   = \wp_generate_password( 8, false, false );
		$dest_dir  = \trailingslashit( $wp_filesystem->wp_content_dir() . 'cpanel' );
		$dir = '/';
		$args = [
			'dir'            => $dir,
			'dirs'           => $dir,
			'types'          => 'file',
			'include_mime'   => true,
			'mime_types'     => 'package/x-generic',
			'raw_mime_types' => 'application/x-gzip',
		];
		$files = \array_filter(
			self::result( 'Fileman', 'list_files', $args, false, false ),
			static fn( \stdClass $file ): bool =>
				$file->exists && $file->type === 'file' && \str_starts_with( $file->file, 'backup-' ) && \str_ends_with( $file->file, '_' . self::$cpanel_user . $suffix )
		);
		@$wp_filesystem->mkdir( $dest_dir, \FS_CHMOD_DIR );
		@$wp_filesystem->copy ( \trailingslashit( $wp_filesystem->wp_content_dir() ) . 'index.php', $dest_dir . 'index.php', false );
		self::result( 'DirectoryIndexes', 'set_indexing', [ 'dir' => $dest_dir ] );
		$found = false;

		foreach ( $files as $file ) {
			$file->mtime = $wp_filesystem->mtime( $file->fullpath );

			if ( Main::$is_debug ) {
				error_log( $file->fullpath . ' ' . \wp_date( 'Y-m-d H:i:s', $file->ctime ) . ' ' . ( \time() - $file->ctime ). ' ' . \wp_date( 'Y-m-d H:i:s', $file->mtime ) . ' ' . ( \time() - $file->mtime ) );
			}

			if ( \time() - $file->ctime > 4 && \time() - $file->mtime > 4 && \intval( $file->size ) > 16 * \MB_IN_BYTES && ! $wp_filesystem->exists( \str_replace( $suffix, '', $file->fullpath ) ) ) {
				$filname = \str_replace( $suffix, '-' . $obscura . $suffix, $file->file );
				$wp_filesystem->chmod( $file->fullpath, \FS_CHMOD_FILE );
				$wp_filesystem->move ( $file->fullpath, $dest_dir . $filname, true );
				$wp_filesystem->touch( $dest_dir . $filname, $file->ctime );
				$found = true;
			}
		}
		$temp_files = \array_map( static function( \stdClass $file ): \stdClass {
				$file->processing = true;
				return $file;
			},
			\array_filter(
				self::result( 'Fileman', 'list_files', $args, false, false ),
				static fn( \stdClass $file ): bool =>
					$file->exists && $file->type === 'file' && \str_starts_with( $file->file, 'backup-' ) && \str_ends_with( $file->file, '_' . self::$cpanel_user . $suffix )
			)
		);
		$args['dir' ] = $dest_dir;
		$args['dirs'] = $dest_dir;
		return \array_merge(
			$temp_files,
			\array_filter(
				self::result( 'Fileman', 'list_files', $args, false, ! $found ),
				static fn( \stdClass $file ): bool =>
					$file->exists && $file->type === 'file' && \str_starts_with( $file->file, 'backup-' ) && \str_contains( $file->file, '_' . self::$cpanel_user . '-' )
			)
		);
	}

	public    static function restored_backups(): array {
		global $wp_filesystem;
		$dest_dir = \trailingslashit( $wp_filesystem->wp_content_dir() ) . 'cpanel';
		$args['dir' ] = $dest_dir;
		$args['dirs'] = $dest_dir;
		$args['types'] ='dir';
		return \array_filter(
			self::result( 'Fileman', 'list_files', $args ),
			static fn( \stdClass $file ): bool =>
				$file->exists && $file->type === 'dir' && $file->size >= 4096 && \str_starts_with( $file->file, 'backup-' ) && \str_contains( $file->file, '_' . self::$cpanel_user  )
		);
	}


	public    static function restore_backup( string $file, string $dest_dir = '' ) /*\stdClass|\WP_Error*/ {
		global $wp_filesystem;

		if ( empty( $dest_dir ) ) {
			$dest_dir  = \trailingslashit( \trailingslashit( $wp_filesystem->wp_content_dir() ) . 'cpanel' );
		}

		$result = self::result( 'Backup', 'restore_files', [ 'backup' => $file, 'directory' => $dest_dir, 'verbose' => true ], true );

		if ( ( $result->status ?? 0 ) === 0 && \is_array( $result->errors ) ) {
			$errors = new \WP_Error;

			foreach ( $result->errors as $code => $error ) {

				if ( ! empty( $error ) ) {
					$errors->add( $code, $error );
				}
			}
			return $errors;
		} else {
			return $result;
		}
	}

	public    static function trace( string $email, bool $last_only = false ): string {
		$result = self::result( 'Email', 'trace_delivery', [ 'recipient' => $email ], true );

		if ( $result->status ) {
			$result = $result->data;
			return \implode( ' &rarr; ', \array_map( static function( array $dest_dir ) use( $last_only ): string {
				$types = [
					'bounce'          => \WebFacing\cPanel\Email\_x( 'bounce and return error message', 'Trace type' ),
					'command'         => \WebFacing\cPanel\Email\_x( 'run a program',                   'Trace type' ),
					'defer'           => \WebFacing\cPanel\Email\_x( 'be delayed',                      'Trace type' ),
					'discard'         => \WebFacing\cPanel\Email\_x( 'be discarded',                    'Trace type' ),
					'error'           => \WebFacing\cPanel\Email\_x( 'cause error',                     'Trace type' ),
					'local_delivery'  => \WebFacing\cPanel\Email\_x( 'be delivered locally',            'Trace type' ),
					'remote_delivery' => \WebFacing\cPanel\Email\_x( 'be delivered remotely',           'Trace type' ),
					'routed'          => \WebFacing\cPanel\Email\_x( 'be routed',                       'Trace type' ),
				];
				return ( $last_only ? '' : $dest_dir['address'] . ' ' ) . \WebFacing\cPanel\Email\_x( 'will', 'Trace' ) . ' ' . ( $types[ $dest_dir['type'] ] ?? $dest_dir['type'] ) . ( $dest_dir['message'] ? ' "' . $dest_dir['message'] . '"' : '' ) . ( $dest_dir['mailbox'] || $dest_dir['mx'][0]->hostname ? ' ' . \WebFacing\cPanel\Email\_x( 'to', 'Trace' ) . ' ' . ( $dest_dir['mailbox'] ?? '' ) . $dest_dir['mx'][0]->hostname : '' );
			}, $result ? self::trace_destinations( [ $result ], $result->address, $last_only ) : [] ) );
		} else {
			return $result->errors[0] ?? '';
		}
	}

	public   static function tokens(): array {
		return self::result( 'Tokens', 'list' );
	}

	public   static function token_rename( string $name, string $new_name ): \WP_Error {
		$errors = new \WP_Error;
		$result = self::result( 'Tokens', 'rename', [ 'name' => $name, 'new_name' => $new_name ], true );

		if ( $result->status === 0 && \is_array( $result->errors ) ) {

			foreach ( $result->errors as $code => $error ) {

				if ( ! empty( $error ) ) {
					$errors->add( $code, $error );
				}
			}
		}
		self::delete_transients();
		return $errors;
	}

	public   static function token_delete( string $name ): \WP_Error {
		$errors = new \WP_Error;
		$result = self::result( 'Tokens', 'revoke', [ 'name' => $name ], true );

		if ( $result->status === 0 && \is_array( $result->errors ) ) {

			foreach ( $result->errors as $code => $error ) {

				if ( ! empty( $error ) ) {
					$errors->add( $code, $error );
				}
			}
		}
		self::delete_transients();
		return $errors;
	}

	public   static function token_add( string $name, int $expires = 0 ): \WP_Error {
		$errors = new \WP_Error;
		$params = [ 'name' => $name ];

		if ( $expires ) {
			$params['expires_at'] = $expires;
		}
		$result = self::result( 'Tokens', 'create_full_access', $params, true );

		if ( $result->status === 0 && \is_array( $result->errors ) ) {

			foreach ( $result->errors as $code => $error ) {

				if ( ! empty( $error ) ) {
					$errors->add( $code, $error );
				}
			}
		} elseif ( \is_object( $result->data ) ) {
			$errors->add_data( $result->data->token );
		}
		self::delete_transients();
		return $errors;
	}

	protected static function delete_transients(): void {

		foreach ( (array) \get_transient( Main::$pf . 'transients' ) as $transient ) {
			\do_action( 'qm/debug', 'delete_' . $transient );
			\delete_transient( $transient );
		}
		\delete_transient( Main::$pf . 'transients' );
	}
}
