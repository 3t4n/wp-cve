<?php
declare( strict_types=1 );
namespace WebFacing\cPanel\Email;
use WebFacing\cPanel\UAPI;

/**
 * Exit if accessed directly
 */
\class_exists( __NAMESPACE__ . '\Main' ) || exit;

abstract class SiteHealth extends Main {

	private const limits = [
		'good'        => 0.00,
		'recommended' => 0.90,
		'critical'    => 0.95,
	];

	private   static array   $limits = self::limits;

	public    static function admin(): void {

		self::$limits = \apply_filters( self::pf . 'disk_space_limits', self::limits ) ?? self::limits;

		\add_filter( 'site_status_tests', static function( array $tests ): array {

			$tests['direct']['email-routing'] = [
				'label' => _x( 'Email routing', 'Site Health Status Label' ),
				'test'  => [ __CLASS__, 'email_routing_test' ],
			];

			if ( ! self::$is_subadmin && ! self::$domain_only && ! \class_exists( 'WebFacing\cPanel\Main' )) {
				$tests['direct']['disk-space'] = [
					'label' => _x( 'Storage usage', 'Site Health Status Label' ),
					'test'  => [ __CLASS__, 'disk_space_test' ],
				];
			}
			$tests['direct']['notifications-domain'] = [
				'label' => _x( 'Account notifications domain', 'Site Health Status Label' ),
				'test'  => [ __CLASS__, 'cpanel_notfications_domain_test' ],
			];
			$tests['direct']['notifications-enabled'] = [
				'label' => _x( 'Account notifications', 'Site Health Status Label' ),
				'test'  => [ __CLASS__, 'cpanel_notifications_test' ],
			];
			$tests['direct']['two-factor-enabled'] = [
				'label' => _x( 'Two Factor login enabled', 'Site Health Status Label' ),
				'test'  => [ __CLASS__, 'cpanel_two_factor_test' ],
			];
			return $tests;
		} );

		\add_filter( 'debug_information', static function( array $debug_info ): array {

			$cpanel_path = UAPI::home_path();
			$emails_disk  = UAPI::email_disk_usage();

			if ( \is_multisite() ) {
				$debug_info['wp-paths-sizes'] = [ 'label' => \_x( 'Directories and Sizes', 'Site Health Info' ) ];
			}
			$debug_info['wp-paths-sizes']['fields']['cpanel-path'] = [
				'label'   =>_x( 'cPanel® path', 'Site Health Info' ),
				'value'   => $cpanel_path,
				'debug'   => $cpanel_path,
				'private' => false
			];

			$debug_info['wp-paths-sizes']['fields']['cpanel-plan'] = [
				'label'   =>_x( 'cPanel® plan', 'Site Health Info' ),
				'value'   => self::$package,
				'debug'   => self::$plan,
				'private' => false
			];

			$debug_info['wp-paths-sizes']['fields']['email_used'] = [
				'label'    => _x( 'Storage space used for emails', 'Site Health Info' ),
				'value'    => \size_format( $emails_disk, 1 ),
				'debug'    => \str_replace( ',', '.', \size_format( $emails_disk, 2 ) ),
				'private'  => false
			];

			$debug_info['wp-paths-sizes']['fields']['cpanel-disk-used'] = [
				'label'   =>_x( 'cPanel® account\'s total size', 'Site Health Info' ),
				'value'   => \size_format( self::$bytes_used, 0 ),
				'debug'   => self::$bytes_used,
				'private' => false
			];

			$debug_info['wp-paths-sizes']['fields']['cpanel-disk-max'] = [
				'label'   =>_x( 'cPanel® account\'s storage space limit', 'Site Health Info' ),
				'value'   => \size_format( self::$byte_limit ),
				'debug'   => self::$byte_limit,
				'private' => false
			];

			self::$cpanel_version = \floatval( UAPI::server_info( 'version' ) );

			$features = UAPI::feature_names();
			$feature_ids   = [];
			$feature_names = [];
			$x = _x( 'Email Accounts',                        'Site Health cPanel® Feature Name' );
			$x = _x( 'Default Address',                       'Site Health cPanel® Feature Name' );
			$x = _x( 'Email Disk Usage',                      'Site Health cPanel® Feature Name' );
			$x = _x( 'Email Filtering Manager',               'Site Health cPanel® Feature Name' );
			$x = _x( 'Webmail',                               'Site Health cPanel® Feature Name' );
			$x = _x( 'Email Deliverability (Authentication)', 'Site Health cPanel® Feature Name' );
			$x = _x( 'Email Trace',                           'Site Health cPanel® Feature Name' );
			$x = _x( 'Email Domain Forwarding',               'Site Health cPanel® Feature Name' );
			$x = _x( 'Contact Information',                   'Site Health cPanel® Feature Name' );
			$x = _x( 'Apache SpamAssassin™',                  'Site Health cPanel® Feature Name' );
			$x = _x( 'Apache SpamAssassin™ Spam Box',         'Site Health cPanel® Feature Name' );

			foreach ( [
				'popaccts',
				'defaultaddress',
				'emailarchive',
				'email_disk_usage',
				'emailauth',
				'emaildomainfwd',
				'emailtrace',
				'webmail',
				'traceaddy',
				'updatecontact',
				'blockers',
				'spamassassin',
				'spambox',
			] as $feature_id ) {

				if ( UAPI::has_features( [ $feature_id ] ) ) {
					$feature_ids[]   = $feature_id;
					$feature_names[] = _x( $features[ $feature_id ], 'Site Health cPanel® Feature Name' );
				}
			}
			$features = \array_combine( $feature_ids, $feature_names );

			if ( ! \str_contains( $debug_info['wp-constants']['label'], __( 'WebFacing™' ) ) ) {
				$debug_info['wp-constants']['label'] = \str_replace( ' ', ' &amp; ' . __( 'WebFacing™' ) . ' ', $debug_info['wp-constants']['label'] );
			}
			$debug_info['wp-constants']['description'] .= _x( ' Some info about how your cPanel® plugin accesses data.', 'Site Health Info' ) ;

			$debug_info['wp-constants']['fields']['WP_START_TIMESTAMP' ] = [
				'label'   => 'WP_START_TIMESTAMP',
				'value'   => \defined( 'WP_START_TIMESTAMP' ) ?
					( \is_float( \WP_START_TIMESTAMP ) ?
						\number_format_i18n( \WP_START_TIMESTAMP, 3 ) .
							\wp_date( ' \(Y-m-d\TH:i:s\) ', \current_time( 'timestamp', \WP_START_TIMESTAMP ) ) :
						\WP_START_TIMESTAMP . ' ▮'
					) :
					_x( 'Undefined', 'Site Health Info' ),
				'debug'   => \defined( 'WP_START_TIMESTAMP' ) ? \WP_START_TIMESTAMP : 'undefined',
				'private' => true,
			];

			$debug_info['wp-constants']['fields']['WP_HOME'] = [
				'label'   => 'WP_HOME',
				'value'   => \defined( 'WP_HOME' ) ?
					\WP_HOME :
					_x( 'Undefined', 'Site Health Info' ) . ' (' . \get_home_url() . ')',
				'debug'   => \defined( 'WP_HOME' ) ? \WP_HOME : 'undefined',
				'private' => false,
			];

			$debug_info['wp-constants']['fields']['WP_SITEURL'] = [
				'label'   => 'WP_SITEURL',
				'value'   => \defined( 'WP_SITEURL' ) ?
					\WP_SITEURL :
					_x( 'Undefined', 'Site Health Info' ) . ' (' . \get_site_url() . ')',
				'debug'   => \defined( 'WP_SITEURL' ) ? \WP_SITEURL : 'undefined',
				'private' => false,
			];

			$debug_info['wp-constants']['fields']['WP_PLUGIN_URL' ] = [
				'label'   => 'WP_PLUGIN_URL',
				'value'   => \defined( 'WP_PLUGIN_URL' ) ?
					\WP_PLUGIN_URL :
					_x( 'Undefined', 'Site Health Info' ) . ' ▮',
				'debug'   => \defined( 'WP_PLUGIN_URL' ) ? \WP_PLUGIN_URL : 'undefined',
			];

			$default = \is_ssl();
			$debug_info['wp-constants']['fields']['FORCE_SSL_ADMIN' ] = [
				'label'   => 'FORCE_SSL_ADMIN',
				'value'   => \defined( 'FORCE_SSL_ADMIN' ) ?
					( self::is_bool( \FORCE_SSL_ADMIN ) ?
						( \FORCE_SSL_ADMIN ?
							_x(  'Enabled', 'Site Health Info' ) :
							_x( 'Disabled', 'Site Health Info' )
						) :
						\FORCE_SSL_ADMIN . ( \FORCE_SSL_ADMIN === $default ?
							'' :
							' (' . _x( 'default:', 'Site Health Info' ) . ' ' . ( $default ?
								_x(  'Enabled', 'Site Health Info' ) :
								_x( 'Disabled', 'Site Health Info' ) . ') ▮'
							)
						)
					) :
					_x( 'Undefined', 'Site Health Info' ) . ' (' . _x( 'no', 'Site Health Info' ) . ')' . ( $default ? ' ▮' : '' ),
				'debug'   => \defined( 'FORCE_SSL_ADMIN' ) ? \FORCE_SSL_ADMIN : 'undefined',
				'private' => false,
			];

			$debug_info['wp-constants']['fields']['RELOCATE' ] = [
				'label'   => 'RELOCATE',
				'value'   => \defined( 'RELOCATE' ) ?
					( self::is_bool( \RELOCATE ) ?
						( \RELOCATE ?
							_x(  'Enabled', 'Site Health Info' ) . ' ▮':
							_x( 'Disabled', 'Site Health Info' )
						) :
						\RELOCATE . ' ▮'
					) :
					_x( 'Undefined', 'Site Health Info' ) . ' (' . _x( 'no', 'Site Health Info' ) . ')',
				'debug'   => \defined( 'RELOCATE' ) ? \RELOCATE : 'undefined',
				'private' => false,
			];

			$debug_info['wp-constants']['fields']['WP_DEVELOPMENT_MODE' ] = [
				'label'   => 'WP_DEVELOPMENT_MODE',
				'value'   => \defined( 'WP_DEVELOPMENT_MODE' ) ?
					( self::is_bool( \WP_DEVELOPMENT_MODE ) ?
						( \WP_DEVELOPMENT_MODE ?
							_x(  'Enabled', 'Site Health Info' ) . ' ▮':
							_x( 'Disabled', 'Site Health Info' )
						) :
						\WP_DEVELOPMENT_MODE . ' ▮'
					) :
					_x( 'Undefined', 'Site Health Info' ) . ' (' . _x( 'not in development', 'Site Health Info' ) . ')',
				'debug'   => \defined( 'WP_DEVELOPMENT_MODE' ) ? \WP_DEVELOPMENT_MODE : 'undefined',
				'private' => false,
			];

			if ( self::$has_http ) {
				$debug_info['wp-constants']['fields']['WP_HTTP_BLOCK_EXTERNAL'] = [
					'label'   => 'WP_HTTP_BLOCK_EXTERNAL',
					'value'   => \defined( 'WP_HTTP_BLOCK_EXTERNAL' ) ?
						( self::is_bool( \WP_HTTP_BLOCK_EXTERNAL ) ?
							( \WP_HTTP_BLOCK_EXTERNAL ?
								_x(  'Enabled', 'Site Health Info' ) . ' ▮' :
								_x( 'Disabled', 'Site Health Info' )
							) :
							\WP_HTTP_BLOCK_EXTERNAL . ( \WP_HTTP_BLOCK_EXTERNAL ? ' ▮' : '' )
						) :
						_x( 'Undefined', 'Site Health Info' ) . ' (' . _x( 'none blocked', 'Site Health Info' ) . ')',
					'debug'   => \defined( 'WP_HTTP_BLOCK_EXTERNAL' ) ? \WP_HTTP_BLOCK_EXTERNAL : 'undefined',
				];

				if ( \defined( 'WP_HTTP_BLOCK_EXTERNAL' ) && \WP_HTTP_BLOCK_EXTERNAL ) {

					$default = 'wordpress.org';
					$debug_info['wp-constants']['fields']['WP_ACCESSIBLE_HOSTS'] = [
						'label'   => 'WP_ACCESSIBLE_HOSTS',
						'value'   => \defined( 'WP_ACCESSIBLE_HOSTS' ) ?
							\WP_ACCESSIBLE_HOSTS . ( \str_contains( \strval( \WP_ACCESSIBLE_HOSTS ), $default ) ? '' : ' ▮' ) :
							_x( 'Undefined', 'Site Health Info' ) . ' (' . _x( 'all', 'Site Health Info' ) . ')',
						'debug'   => \defined( 'WP_ACCESSIBLE_HOSTS' ) ? \WP_ACCESSIBLE_HOSTS : 'undefined',
						'private' => false,
					];
				}
			}

			$debug_info['wp-constants']['fields']['DISALLOW_FILE_MODS' ] = [
				'label'   => 'DISALLOW_FILE_MODS',
				'value'   => \defined( 'DISALLOW_FILE_MODS' ) ?
					( self::is_bool( \DISALLOW_FILE_MODS ) ?
						( \DISALLOW_FILE_MODS ?
							_x(  'Enabled', 'Site Health Info' ) . ' ▮' :
							_x( 'Disabled', 'Site Health Info' )
						) :
						\DISALLOW_FILE_MODS . ( \DISALLOW_FILE_MODS ? ' ▮' : '' )
					) :
					_x( 'Undefined', 'Site Health Info' ) . ' (' . _x( 'not disallowed', 'Site Health Info' ) . ')',
				'debug'   => \defined( 'DISALLOW_FILE_MODS' ) ? \DISALLOW_FILE_MODS : 'undefined',
				'private' => false,
			];

			if ( ! ( \defined( 'DISALLOW_FILE_MODS' ) && \DISALLOW_FILE_MODS ) ) {

				$debug_info['wp-constants']['fields']['AUTOMATIC_UPDATER_DISABLED' ] = [
					'label'   => 'AUTOMATIC_UPDATER_DISABLED',
					'value'   => \defined( 'AUTOMATIC_UPDATER_DISABLED' ) ?
						( self::is_bool( \AUTOMATIC_UPDATER_DISABLED ) ?
							( \AUTOMATIC_UPDATER_DISABLED ?
								_x( 'Yes, disabled', 'Site Health Info' ) :
								_x(  'No, enabled',  'Site Health Info' )
							) :
							\AUTOMATIC_UPDATER_DISABLED . ' ▮'
						) :
						_x( 'Undefined', 'Site Health Info' ) . ' (' . _x( 'not disabled', 'Site Health Info' ) . ')',
					'debug'   => \defined( 'AUTOMATIC_UPDATER_DISABLED' ) ? \AUTOMATIC_UPDATER_DISABLED : 'undefined',
					'private' => false,
				];

				if ( ! ( \defined( 'AUTOMATIC_UPDATER_DISABLED' ) && \AUTOMATIC_UPDATER_DISABLED ) ) {

					$debug_info['wp-constants']['fields']['WP_AUTO_UPDATE_CORE' ] = [
						'label'   => 'WP_AUTO_UPDATE_CORE',
						'value'   => \defined( 'WP_AUTO_UPDATE_CORE' ) ?
							( self::is_bool( \WP_AUTO_UPDATE_CORE ) ?
								( \WP_AUTO_UPDATE_CORE ?
									_x(  'Enabled', 'Site Health Info' ) :
									_x( 'Disabled', 'Site Health Info' )
								) :
								\WP_AUTO_UPDATE_CORE . ' ▮'
							) :
							_x( 'Undefined', 'Site Health Info' ) . ' (minor)',
						'debug'   => \defined( 'WP_AUTO_UPDATE_CORE' ) ? \WP_AUTO_UPDATE_CORE : 'undefined',
						'private' => false,
					];
				}
			}

			$debug_info['wp-constants']['fields']['WP_DISABLE_FATAL_ERROR_HANDLER' ] = [
				'label'   => 'WP_DISABLE_FATAL_ERROR_HANDLER',
				'value'   => \defined( 'WP_DISABLE_FATAL_ERROR_HANDLER' ) ?
					( self::is_bool( \WP_DISABLE_FATAL_ERROR_HANDLER ) ?
						( \WP_DISABLE_FATAL_ERROR_HANDLER ?
							_x(    'Yes, disabled', 'Site Health Info' ) :
							_x( 'No, not disabled', 'Site Health Info' )
						) :
						\WP_DISABLE_FATAL_ERROR_HANDLER . ' ▮'
					) :
					_x( 'Undefined', 'Site Health Info' ) . ' (' . __( 'enabled' ) . ')',
				'debug'   => \defined( 'WP_DISABLE_FATAL_ERROR_HANDLER' ) ? \WP_DISABLE_FATAL_ERROR_HANDLER : 'undefined',
				'private' => false,
			];

			if ( \defined( 'WP_DISABLE_FATAL_ERROR_HANDLER' ) && \WP_DISABLE_FATAL_ERROR_HANDLER ) {

				$debug_info['wp-constants']['fields']['WP_SANDBOX_SCRAPING' ] = [
					'label'   => 'WP_SANDBOX_SCRAPING',
					'value'   => \defined( 'WP_SANDBOX_SCRAPING' ) ?
						( self::is_bool( \WP_SANDBOX_SCRAPING ) ?
							( \WP_SANDBOX_SCRAPING ?
								_x(  'Enabled', 'Site Health Info' ) :
								_x( 'Disabled', 'Site Health Info' )
							) :
							\WP_SANDBOX_SCRAPING . ' ▮'
						) :
						_x( 'Undefined', 'Site Health Info' ) . ' (' . __( 'not disabled' ) . ')',
					'debug'   => \defined( 'WP_SANDBOX_SCRAPING' ) ? \WP_SANDBOX_SCRAPING : 'undefined',
					'private' => false,
				];
			} else {

				$debug_info['wp-constants']['fields']['RECOVERY_MODE_EMAIL' ] = [
					'label'   => 'RECOVERY_MODE_EMAIL',
					'value'   => \defined( 'RECOVERY_MODE_EMAIL' ) ?
						\RECOVERY_MODE_EMAIL . ( \is_email( \RECOVERY_MODE_EMAIL ) ? '' : ' ▮' ) :
						_x( 'Undefined', 'Site Health Info' ) . ' (' . \get_option( 'admin_email' ) . ')',
					'debug'   => \defined( 'RECOVERY_MODE_EMAIL' ) ? \RECOVERY_MODE_EMAIL : 'undefined',
					'private' => false,
				];
			}

			$debug_info['wp-constants']['fields']['DISALLOW_FILE_MODS'] = [
				'label'   => 'DISALLOW_FILE_MODS',
				'value'   => \defined( 'DISALLOW_FILE_MODS' ) ?
					( self::is_bool( \DISALLOW_FILE_MODS ) ?
						( \DISALLOW_FILE_MODS ?
							_x( 'Disallowed', 'Site Health Info' ) . ' ▮' :
							_x(    'Allowed', 'Site Health Info' )
						) :
						\DISALLOW_FILE_MODS . ( \DISALLOW_FILE_MODS ? ' ▮' : '' )
					) :
					_x( 'Undefined', 'Site Health Info' ) . ' (' . _x( 'allowed', 'Site Health Info' ) . ')',
				'debug'   => \defined( 'DISALLOW_FILE_MODS' ) ? \DISALLOW_FILE_MODS : 'undefined',
				'private' => false,
			];

			$default = \trailingslashit( \get_option( 'siteurl' ) ) . 'wp-content';
			$debug_info['wp-constants']['fields']['WP_CONTENT_URL'] = [
				'label'   => 'WP_CONTENT_URL',
				'value'   => \defined( 'WP_CONTENT_URL' ) ?
					\WP_CONTENT_URL :
					_x( 'Undefined', 'Site Health Info' ) . ' (' . $default . ')',
				'debug'   => \defined( 'WP_CONTENT_URL' ) ? \WP_CONTENT_URL : 'undefined',
			];
			$debug_info['wp-constants']['fields']['WF_CPANEL_API_TOKEN'] = [
				'label'   => 'WF_CPANEL_API_TOKEN',
				'value'   => \defined( 'WF_CPANEL_API_TOKEN' ) ?
					( \WF_CPANEL_API_TOKEN ?
						_x(  'Enabled', 'Site Health Info' ) :
						_x( 'Disabled', 'Site Health Info' )
					) :
					_x( 'Undefined', 'Site Health Info' ),
				'debug'   => \defined( 'WF_CPANEL_API_TOKEN' ) ? \WF_CPANEL_API_TOKEN : 'undefined',
				'private' => true,
			];
			$debug_info['wp-constants']['fields']['WF_DEBUG'] = [
				'label'   => 'WF_DEBUG',
				'value'   => \defined( 'WF_DEBUG' ) ?
					( \WF_DEBUG ?
						_x(  'Enabled', 'Site Health Info' ) :
						_x( 'Disabled', 'Site Health Info' )
					) :
					_x( 'Undefined', 'Site Health Info' )  . ' (' . _x( 'not in debug mode', 'Site Health Info' ) . ')',
				'debug'   => \defined( 'WF_DEBUG' ) ? \WF_DEBUG : 'undefined',
				'private' => false,
			];

			if ( self::$is_debug || \defined( 'WF_CPANEL_HOST' ) ) {

				$debug_info['wp-constants']['fields']['WF_CPANEL_HOST'] = [
					'label'   => 'WF_CPANEL_HOST',
					'value'   => \defined( 'WF_CPANEL_HOST' ) ?
						( \is_string( \WF_CPANEL_HOST ) && ! \is_numeric( \WF_CPANEL_HOST ) ?
							\WF_CPANEL_HOST :
							_x(  'Invalid', 'Site Health Info' )
						) :
						_x( 'Undefined', 'Site Health Info' ) . ' (' . self::$host_name . ')',
					'debug'   => \defined( 'WF_CPANEL_HOST' ) ? \WF_CPANEL_HOST : 'undefined',
					'private' => false,
				];

				$debug_info['wp-constants']['fields']['WF_CPANEL_USER'] = [
					'label'   => 'WF_CPANEL_USER',
					'value'   => \defined( 'WF_CPANEL_USER' ) ?
						( \is_string( \WF_CPANEL_USER ) && ! \is_numeric( \WF_CPANEL_USER ) ?
							\WF_CPANEL_USER :
							_x(  'Invalid', 'Site Health Info' )
						) :
						_x( 'Undefined', 'Site Health Info' ) . ' (' . self::$cpanel_user . ')',
					'debug'   => \defined( 'WF_CPANEL_USER' ) ? \WF_CPANEL_USER : 'undefined',
					'private' => true,
				];
			}

			if ( self::$is_debug ) {

				$debug_info['wp-constants']['fields'][ __NAMESPACE__ . '\PLUGIN_BASENAME'] = [
					'label'   => 'PLUGIN_BASENAME',
					'value'   => \defined( __NAMESPACE__ . '\PLUGIN_BASENAME' ) ?
						\constant( __NAMESPACE__ . '\PLUGIN_BASENAME' ) :
						_x( 'Undefined', 'Site Health Info' ),
					'debug'   => \defined( __NAMESPACE__ . '\PLUGIN_BASENAME' ) ? \constant( __NAMESPACE__ . '\PLUGIN_BASENAME' ) : 'undefined',
					'private' => false,
				];

				$debug_info['wp-constants']['fields']['WF_CPANEL_SHELL_EXEC_DISABLE'] = [
					'label'   => 'WF_CPANEL_SHELL_EXEC_DISABLE',
					'value'   => \defined( 'WF_CPANEL_SHELL_EXEC_DISABLE' ) ?
						( \WF_CPANEL_SHELL_EXEC_DISABLE ?
							_x(  'Enabled', 'Site Health Info' ) :
							_x( 'Disabled', 'Site Health Info' )
						) :
						_x( 'Undefined', 'Site Health Info' )  . ' (' . _x( 'not disabled', 'Site Health Info' ) . ')',
					'debug'   => \defined( 'WF_CPANEL_SHELL_EXEC_DISABLE' ) ? \WF_CPANEL_SHELL_EXEC_DISABLE : 'undefined',
					'private' => false,
				];

				$debug_info['wp-constants']['fields']['WF_DEV_EMAIL'] = [
					'label'   => 'WF_DEV_EMAIL',
					'value'   => \defined( 'WF_DEV_EMAIL' ) ?
						\WF_DEV_EMAIL . ( \is_email( \WF_DEV_EMAIL ) ? '' : ' ▮' ) :
						_x( 'Undefined', 'Site Health Info' ),
					'debug'   => \defined( 'WF_DEV_EMAIL' ) ? \WF_DEV_EMAIL : 'undefined',
					'private' => true,
				];
			}

			$plugin_auto    =
				( ! \defined( 'DISALLOW_FILE_MODS' )         || ! \DISALLOW_FILE_MODS         ) &&
				( ! \defined( 'AUTOMATIC_UPDATER_DISABLED' ) || ! \AUTOMATIC_UPDATER_DISABLED ) &&
				\in_array( \constant( __NAMESPACE__ . '\PLUGIN_BASENAME' ), (array) get_option( 'auto_update_plugins', [] ), true )
			;
			$user_created   = UAPI::user_created();
			$user_updated   = UAPI::user_updated();
			$two_factor     = UAPI::two_factor();
			$max_emails     = UAPI::maximum_emails();
			$held_mails     = UAPI::all_queued_emails();
			$contacts       = UAPI::get_user_info();
			$show_main      = ! self::$domain_only || self::$site_domain === self::$main_domain;
			$subaccounts    = [];
			$addon_domains  = UAPI::list_domains( 'addodn_domains' );
			$parked_domains = UAPI::list_domains( 'parked_domains' );
			$dead_domains   = UAPI::dead_domains();

			foreach ( UAPI::subaccounts() as $name => $subaccount ) {
				$subaccounts[] = '●&nbsp;' . $name . ( \property_exists( $subaccount, 'services' ) ? '&nbsp;{' . \implode( ',', (array) $subaccount->services ) . '}&nbsp;' : '' );
			}
			$subaccounts  = \implode( ', ', $subaccounts );

			$debug_info[ self::$plugin->TextDomain ] = [
				'label'  => _x( 'Your cPanel® Account &mdash; Email Info', 'Site Health Info Label' ),
				'description' => \sprintf( _x( 'This is information about your cPanel® status and the %s plugin.', 'Site Health Info Description' ), '&laquo;'. self::$plugin->Name . '&raquo;' ),
				'fields' => [
					'plugin_version'      => [
						'label'    => _x( 'Plugin version', 'Site Health Info' ),
						'value'    => self::$plugin->Version,
						'debug'    => self::$plugin->Version,
						'private'  => false,
					],
					'plugin_auto_updated' => [
						'label'    => ' &mdash; ' . _x( 'Plugin Auto Updated', 'Site Health Info' ),
						'value'    => $plugin_auto ? __( 'Yes' ) : __( 'No' ),
						'debug'    => $plugin_auto,
						'private'  => ! self::$is_debug,
					],
					'plugin_usage'        => [
						'label'    => _x( 'Plugin usage', 'Site Health Info' ),
						'value'    => ( static function() {
							$vals  =  [];

							foreach( self::$class::$usage as $f => $c ) {

								if ( $f === 'batch'  ) {

									foreach ( $c as $bf => $bc ) {

										if ( isset( $bc->count ) ) {
											$n = self::$is_pro ? $bc->count ** 2 : 1;
											$d = self::$license->$f->$bf->count;
											$vals[] = \sprintf(
												/* translators: 1: Function, 2: Used, 3: Limit */
												_x( '%1$s: %2$d of %3$d', 'Site Health Info' ),
												$f . ' ' . $bf,
												$bc->count,
												$d ? \absint( $n / $d ) : 0,
											);
										}
									}
								} elseif ( isset( $c->count ) ) {
									$n = self::$is_pro ? $c->count ** 2 : 1;
									$d = self::$license->$f->count;
									$vals[] = \sprintf(
										/* translators: 1: Function, 2: Used, 3: Limit */
										_x( '%1$s: %2$d of %3$d', 'Site Health Info' ),
										$f,
										$c->count,
										$d ? \absint( $n / $d ) : 0,
									);
								}
							}
							return \implode( ', ', $vals );
						} )(),
						'debug'    => \print_r( self::$usage, true ),
					],
					'plugin_license'        => [
						'label'    => _x( 'Plugin license', 'Site Health Info' ),
						'value'    => ( static function() {
							$vals  =  [];

							foreach( self::$license as $f => $c ) {

								if ( $f === 'batch'  ) {

									foreach ( $c as $bf => $bc ) {

										if ( isset( $bc->count ) ) {
											$n = self::$is_pro ? ( self::$class::$usage->$f->$bf->count ?? 0 ) ** 2 : 1;
											$d = $bc->count;
											$l = $d ? \absint( $n / $d ) : 0;
											$vals[] = \sprintf(
												/* translators: 1: Function, 2: Limit, 3: Left */
												_x( '%1$s: max %2$d, left %3$d', 'Site Health Info' ),
												$f . ' ' . $bf,
												$l,
												$l - ( self::$class::$usage->$f->$bf->count ?? 0 ),
											);
										}
									}
								} elseif ( isset( $c->count ) ) {
									$n = self::$is_pro ? ( self::$class::$usage->$f->count ?? 0 ) ** 2 : 1;
									$d = $c->count;
									$l = $d ? \absint( $n / $d ) : 0;
									$vals[] = \sprintf(
										/* translators: 1: Function, 2: Limit, 3: Left */
										_x( '%1$s: max %2$d, left %3$d', 'Site Health Info' ),
										$f,
										$l,
										$l - ( self::$class::$usage->$f->count ?? 0 ),
									);
								}
							}
							return \implode( '; ', $vals );
						} )(),
						'debug'    => \print_r( self::$usage, true ),
					],
					'cpanel_version'      => [
						'label'    => _x( 'cPanel® version', 'Site Health Info' ),
						'value'    => \substr_replace( \number_format( 111.47 - ( 49. * \floatval( self::$cpanel_version ?: 8 ) / 115. ), 2, '.', '' ), ' (build ', -1, 0 ) . ')',
						'debug'    => self::$cpanel_version,
						'private'  => false,
					],
					'protocol'            => [
						'label'    => ' &mdash; ' . _x( 'API protocol', 'Site Health Info' ),
						'value'    => self::$has_http ? _x( 'HTTP REST', 'cPanel® access method' ) : _x( 'Command Line Shell Execution', 'cPanel® access method' ),
						'debug'    => self::$has_http ? 'HTTP' : 'SHELL',
						'private'  => false,
					],
					'api_version'          => [
						'label'    => ' &mdash; ' . _x( 'API version', 'Site Health Info' ),
						'value'    => UAPI::$api_version,
						'debug'    => UAPI::$api_version,
						'private'  => false,
					],
					'api_message'        => [
						'label'    => ' &mdash; ' . _x( 'Last HTTP response', 'Site Health Info' ),
						'value'    => UAPI::$response_message,
						'debug'    => \explode( ' ', UAPI::$response_message )[0],
						'private'  => false,
					],
					'cache'               =>      [
						'label'    => ' &mdash; ' . _x( 'Cache hits/misses', 'Site Health Info' ),
						'value'    => UAPI::$cache_hits . ' / ' .  UAPI::$cache_misses . ' (' . \number_format_i18n( 100. * UAPI::$cache_hits / ( UAPI::$cache_hits + UAPI::$cache_misses ), 0, '' ) . '%)',
						'debug'    => UAPI::$cache_hits .  '/'  .  UAPI::$cache_misses,
						'private'  => false,
					],
					'proisp'                => [
						'label' => _x( 'At PRO ISP?', 'Site Health Info' ),
						'value'    => ( self::$is_proisp ? __( 'Yes' ) : __( 'No' ) ) . ', ' . self::$package,
						'debug'    => self::$is_proisp,
						'private'  => ! self::$is_debug,
					],
					'features'            => [
						'label'    => _x( 'Features', 'Site Health Info' ),
						'value'    => \implode( ', ', $features ),
						'debug'    => \implode( ',', \array_keys( $features ) ),
						'private'  => false,
					],
					'cpanel_host'         => [
						'label' => _x( 'Host', 'Site Health Info' ),
						'value'    => self::$host_name,
						'debug'    => self::$host_name,
						'private'  => ! self::$is_debug,
					],
					'cpanel_user'         => [
						'label' => _x( 'User', 'Site Health Info' ),
						'value'    => self::$cpanel_user,
						'debug'    => self::$cpanel_user,
						'private'  => ! self::$is_debug,
					],
					'user_created'        => [
						'label' => \sprintf(
							_x( ' &mdash; User %1$s created',
								'Site Health Info, %1$s = user'
							),
							self::$cpanel_user
						),
						'value'    => $user_created ? \wp_date( \get_option( 'date_format' ), $user_created ) : '',
						'debug'    => $user_created ? \date( 'Y-m-d', $user_created ) : '',
						'private'  => ! self::$is_debug,
					],
					'user_updated'        => [
						'label' => \sprintf(
							_x( ' &mdash; User %1$s updated',
								'Site Health Info, %1$s = user'
							),
							self::$cpanel_user
						),
						'value'    => $user_updated ? \wp_date( \get_option( 'date_format' ), $user_updated ) : '',
						'debug'    => $user_updated ? \date( 'Y-m-d', $user_updated ) : '',
						'private'  => ! self::$is_debug,
					],
					'subaccounts'         => [
						'label' => _x( ' &mdash; Subaccounts {services}', 'Site Health Info' ) . ' (' . ( $subaccounts ? \count( \explode( ', ', $subaccounts ) ) : 0 ) . ')',
						'value'    => $subaccounts,
						'private'  => ! self::$is_debug,
					],
					'2fa_used'            => [
						'label'    => _x( ' &mdash; Two Factor Authentication?', 'Site Health Info' ),
						'value'    => $two_factor ? __( 'Yes' ) : __( 'No' ),
						'debug'    => $two_factor,
						'private'  => ! self::$is_debug,
					],
					'mail_server'         => [
						'label' => _x( 'Mail Server &amp; Storage Format', 'Site Health Info' ),
						'value'    => \ucfirst( UAPI::server_info( 'mail_server' ) ) . ' ' . UAPI::server_info( 'mailbox_storage_format' ),
						'private'  => false,
					],
					'webmail_apps'         => [
						'label' => _x( 'Webmail Applications', 'Site Health Info' ),
						'value'    => \implode( ',', UAPI::webmail_apps() ),
						'private'  => false,
					],
					'email_accounts_num'   => [
						'label'    => _x( 'Number of email accounts', 'Site Health Info' ),
						'value'    => \number_format_i18n( UAPI::count_emails(), 0, '' ),
						'debug'    => UAPI::count_emails(),
						'private'  => false,
					],
					'email_used'   => [
						'label'    => _x( 'Storage space used for emails', 'Site Health Info' ),
						'value'    => \size_format( $emails_disk, 1 ),
						'debug'    => \str_replace( ',', '.', \size_format( $emails_disk, 2 ) ),
						'private'  => false,
					],
					'maximum_emails'      => [
						'label'    => _x( 'Maximum sending emails per hour', 'Site Health Info' ),
						'value'    => \sprintf(
							/* translators: 1: formatted number */
							_x( '%1$s per hour', 'Site Health Info' ),
							\number_format_i18n( $max_emails ),
						),
						'debug'    => $max_emails . '/h',
						'private'  => false,
					],
					'queued_emails'       => [
						'label'    => _x( 'Current count of outgoing emails queue', 'Site Health Info' ),
						'value'    => \number_format_i18n( $held_mails ),
						'debug'    => $held_mails,
						'private'  => false,
					],
					'main_domain'         => [
						'label'    => _x( 'Main domain', 'Site Health Info' ),
						'value'    => \idn_to_utf8( self::$main_domain ),
						'debug'    => self::$main_domain,
						'private'  => false,
					],
					'addon_domains'       => [
						'label'    => _x( ' &mdash; Addon domains', 'Site Health Info' ) . ' (' . \count( $addon_domains ) . ')',
						'value'    => ( $addon_domains ? '●&nbsp;' : '' ) . \implode( ', ●&nbsp;', \array_map( 'idn_to_utf8', $addon_domains ) ),
						'debug'    => \count( $addon_domains ) ? \implode( ', ', $addon_domains ) : '(none)',
						'private'  => ! self::$is_debug,
					],

					'parked_domains'      => [
						'label'    => _x( ' &mdash; Parked domains', 'Site Health Info' ) . ' (' . \count( $parked_domains ) . ')',
						'value'    => ( $parked_domains ? '●&nbsp;' : '' ) . \implode( ', ●&nbsp;', \array_map( 'idn_to_utf8', $parked_domains ) ),
						'debug'    => \count( $parked_domains ) ? \implode( ', ', $parked_domains ) : '(none)',
						'private'  => ! self::$is_debug,
					],

					'dead_domains'        => [
						'label'    => _x( ' &mdash; Dead domains', 'Site Health Info' ) . ' (' . \count( $dead_domains ) . ')',
						'value'    => ( \count( $dead_domains ) ? '●&nbsp;' : '' ) . \implode( ', ●&nbsp;', \array_map( 'idn_to_utf8', $dead_domains ) ),
						'debug'    => \count( $dead_domains ) ? \implode( ', ', $dead_domains ) : '(none)',
						'private'  => ! self::$is_debug,
					],
					'contacts'            => [
						'label' => _x( 'Contact email addresses', 'Site Health Info' ),
						'value'    =>
							( $contacts->contact_email_2 ? '1.&nbsp;' : '' ) .
							$contacts->contact_email .
							( $contacts->contact_email_2 ? ', 2.&nbsp;' . $contacts->contact_email_2 : '' )
						,
						'debug'    => \print_r( [ 1 => ( $contacts->contact_email ?? null ), 2 => ( $contacts->contact_email_2 ?? null ) ], true ),
						'private'  => ! self::$is_debug,
					],
				]
			];

			if ( \is_multisite() ) {
//				unset ( $debug_info[ self::$plugin->TextDomain ]['fields']['plugin_auto_updated'] );
			}

			if ( self::$has_http  && ! self::$is_debug ) {
				unset ( $debug_info[ self::$plugin->TextDomain ]['fields']['apiversion'] );
			} else {
				unset ( $debug_info[ self::$plugin->TextDomain ]['fields']['http_message'] );
			}

			if ( ! \defined( 'WF_CPANEL_HOST' ) && ! \defined( 'WF_CPANEL_USER' ) && ! self::$is_debug ) {
				unset ( $debug_info[ self::$plugin->TextDomain ]['fields']['cpanel_host'] );
			}

			if ( $show_main ) {
				$info = UAPI::get_user_info();

				$notfications = [
					'notify_contact_address_change' =>
						' &mdash; ' . _x( 'Notify contact address change',
							'Site Health Info Notifcation' ),
					'notify_contact_address_change_notification_disabled' =>
						' &mdash; ' . _x( 'Notify contact address change disabled',
							'Site Health Info Notifcation' ),
					'notify_disk_limit' =>
						' &mdash; ' . _x( 'Notify storage space limit approach',
							'Site Health Info Notifcation' ),
					'notify_password_change' =>
						' &mdash; ' . _x( 'Notify password change',
							'Site Health Info Notifcation' ),
					'notify_password_change_notification_disabled' =>
						' &mdash; ' . _x( 'Notify password change notification disabled',
							'Site Health Info Notifcation' ),
//					'notify_email_quota_limit' =>
//						' &mdash; ' . _x( 'Notify email storage quota limits',
//							'Site Health Info Notifcation' ),
					'notify_twofactorauth_change' =>
						' &mdash; ' . _x( 'Notify two factor auth change',
							'Site Health Info Notifcation' ),
					'notify_twofactorauth_change_notification_disabled' =>
						' &mdash; ' . _x( 'Notify two factor auth change notification disabled',
							'Site Health Info Notifcation' ),
				];

				foreach( $notfications as $status => $text ) {
					$ok = \boolval( $info->$status ?? true );
					$debug_info[ self::$plugin->TextDomain ]['fields'][ \sanitize_key( $status ) ] = [
						'label'   => $text,
						'value'   => $ok ? __( 'Yes' ) : __( 'No' ),
						'debug'   => $ok,
						'private' => true,
					];
				}
			} else {
				unset (
					$debug_info[ self::$plugin->TextDomain ]['fields']['cpanel_user'],
					$debug_info[ self::$plugin->TextDomain ]['fields']['user_created'],
					$debug_info[ self::$plugin->TextDomain ]['fields']['user_updated'],
					$debug_info[ self::$plugin->TextDomain ]['fields']['email_accounts_num'],
					$debug_info[ self::$plugin->TextDomain ]['fields']['email_used'],
					$debug_info[ self::$plugin->TextDomain ]['fields']['main_domain'],
					$debug_info[ self::$plugin->TextDomain ]['fields']['contacts']
				);
			}

			foreach ( UAPI::mail_domains() as $domain ) {

				if ( ! self::$domain_only || $domain === self::$site_domain ) {
					$domain  = \idn_to_utf8( $domain );
					$mx_self = self::email_mx_self( $domain );
					$accept  = UAPI::auto_accept( $domain );

					$debug_info[ self::$plugin->TextDomain ]['fields'][ \sanitize_key( $domain  . '-mx_self' ) ] = [
						'label'   => __( 'Domain' ) . ' ' . \idn_to_utf8( $domain ) . ' ' . _x( 'can receive emails?', 'Site Health' ),
						'value'   => $mx_self ? __( 'Yes' ) : __( 'No' ),
						'debug'   => $mx_self,
						'private' => false,
					];

					$debug_info[ self::$plugin->TextDomain ]['fields'][ \sanitize_key( $domain . '-accepts' ) ] = [
						'label'   => ' &mdash; ' . __( 'auto detects local/remote delivery?' ),
						'value'   => $accept ? __( 'Yes' ) : __( 'No' ),
						'debug'   => $accept,
						'private' => false,
					];
				}
			}
			return $debug_info;
		} );

		\add_filter( 'site_health_navigation_tabs', static function( array $tabs ): array {
			$tabs['email-routing'] = esc_html_x( 'Email Routing', 'Site Health Tab' );
			return $tabs;
		} );

		\add_action( 'site_health_tab_content', static function( string $tab ): void {

			if ( $tab === 'email-routing' ) {

				if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['domain'] ) ) {
					$domain = \sanitize_text_field( $_POST['domain'] );
					$result = UAPI::set_always_accept( $domain, self::email_mx_self( $domain ) );

					if ( $result->has_errors() ) {
						\printf(
							/* translators: 1: error message */
							'<div class="notice notice-error"><p>%1$s</p></div>',
							\esc_html( $result->get_error_message() )
						);
					}
				}
				$email = $_GET['email'] ?? \get_bloginfo( 'admin_email' );
				$domain = \explode( '@', $email )[1];
				$local = \in_array( $domain, UAPI::mail_domains() );
				$auto  = $local ? UAPI::auto_accept( $domain ) : false;
				$main = UAPI::main_email_account()->email; ?>
				<div class="wrap" style="width: 66.67%; margin: 10px auto">
					<form action="" method="get">
						<fieldset>
							<input type="hidden" name="tab" value="<?=\sanitize_text_field($_GET['tab'])?>"/>
							<label for="email"><?php _ex(' Email Address', 'Form Field Label' ); ?>: </label>
							<input id="email" type="email" name="email" list="users" required="required" size="32" placeholder="<?=$email?>" autocomplete="off"/>
							<button type="submit"><?php _e( 'Check' ); ?></button>
						</fieldset>
						<datalist id="users">
							<option value="<?=$main?>"><?=self::email_to_utf8($main)?></option>
<?php
					foreach ( \get_users( [ 'fields' => 'user_email', 'role' => \get_user_count() > \KB_IN_BYTES ? 'administrator' : null, 'number' => \KB_IN_BYTES ] ) as $user_email ) { ?>
							<option value="<?=$user_email?>"><?=self::email_to_utf8($user_email)?></option>
<?php
					} ?>
						</datalist>
					</form>
<?php
					if ( $local ) { ?>
						<form action="" method="post">
							<input type="hidden" name="domain" value="<?=$domain?>"/>
							<p>
								<strong><?php _e( 'Auto detects local/remote delivery:' ); ?></strong> <em><?=$auto?__('Yes'):'<span style="color:orangered;">'.__('No').'</span>'?></em>
<?php
							if ( ! $auto ) { ?>
								&nbsp; <button type="submit"><?php \printf( _x( 'Fix this for %1$s', 'Button' ), '<code>' . \idn_to_utf8( $domain ) . '</code>' ) ; ?></button>
<?php
							} ?>
							</p>
						</form>
<?php
					} ?>
					<p><strong><?php _e( 'Delivery routing:' ); ?></strong> <em><?=UAPI::trace($email)?></em></p>
				</div>
<?php
			}
		} );
	}

	public    static function disk_space_test(): array {

		if ( self::$is_debug ) {
			$rand = \rand( 0, 2 );
			self::$bytes_used = $rand ? ( $rand < 2 ? \intval( self::limits['recommended'] * self::$byte_limit ) : \intval( self::limits['critical'] * self::$byte_limit ) ) : \intval( self::limits['good'] * self::$byte_limit );
		}
		$uploads_size = \get_dirsize( \wp_upload_dir()['basedir'], 30 );
		$emails_disk  = UAPI::email_disk_usage();

		$result = [
			'label'       => _x( 'Your server has enough storage space', 'Site Health Status Test Label' ),
			'status'      => 'good',
			'badge'       => [
				'label'   => _x( 'cPanel® Disk', 'Site Health Status Badge Label' ),
				'color'   => 'blue',
			],
			'description' => \wpautop( \sprintf(
				_x( 'In internet services providing (ISPs) or pure web hosting, storage space is the amount of space actually used or available on the server account for storing the content of your site. This content includes posts, pages, custom content, images, audios, videos, pdfs, other media files, logs, persistent object cache, page cache, your preferences, settings, configuration, and whatever else stored in files or databases. In case a full ISP, it is also used to store emails, including their content and attachments. The amount of used storage space tend to grow over time.', 'Site Health Info Test Description' ) .
				'</p><p>' .
				_x( 'The maximum amount depend on the subscribed package or plan typically from 1GB to over 100GB. When your available storage space is exhausted, your site may break or fail in strange, unpredictable ways. Deleting redundant temporary files, log files, and oher "garbage" may rectify it short term. Upgrading your plan/package/account is a more sustainable solution.', 'Site Health Info Test Description' ) .
				'</p><p>' .
				/* translators: ,  1: storage space used as formatted number, 2: storage space limit as formatted number,  3: storage space used as formatted number,  4: storage space used as formatted number */
				_x( 'Storage space used is %1$s out of %2$s available. Your uploaded media files use %3$s. Your emails use %4$s', 'Site Health Info Test Description' ),
				\size_format( self::$bytes_used, 1 ),
				\size_format( self::$byte_limit ),
				\size_format( $uploads_size, 1 ),
				\size_format( $emails_disk, 1 )
			) ),
			'actions'     => '<a href="https://' . self::$host_name . ':2083/">' . _x( 'Your cPanel® Server', 'Site Health Status Test Action' ) . '</a>',
			'test'        => 'disk-space',
		];
		$rate = self::$byte_limit ? self::$bytes_used / self::$byte_limit : 0.;
		if ( $rate >= self::$limits['recommended'] ) {
			$result['label'  ]      = _x( 'You are quite close to reaching the storage quota on your cPanel® server', 'Site Health Status Test Label' );
			$result['status' ]      = 'recommended';
			$result['badge'  ]['color'] = 'orange';
			$result['description'] .= \wpautop( _x( 'You are advised to inspect your cPanel® server or consult your host for further advice, or upgrade.', 'Site Health Status Test Description' ) );
		}

		if ( $rate >= self::$limits['critical'] ) {
			$result['label'  ]      = _x( 'You are very close to reaching the storage quota on your cPanel® server', 'Site Health Status Test Label' );
			$result['status' ]      = 'critical';
			$result['badge'  ]['color'] = 'red';
			$result['actions']     .= ' &nbsp; | &nbsp; <mark>' . _x( 'Immediate action is necessary to keep normal site behaviour, and to allow for new content.', 'Site Health Status Test Action' ) . '</mark>';
		}
		return $result;
	}

	public    static function email_routing_test(): array {

		$result = [
			'label'       => _x( 'Your incoming email delivery seems fine', 'Site Health Status Test Label' ),
			'status'      => 'good',
			'badge'       => [
				'label'   => _x( 'cPanel® Email', 'Site Health Status Test Badge Label' ),
				'color'   => 'blue',
			],
			'description' => \wpautop(
				_x( 'To receive emails to this host it\'s cruicial that the DNS MX records points to this server.', 'Site Health Status Test Description' )
			),
			'actions'     => '',
			'test'        => 'email-routing',
		];

		if ( ! self::email_mx_self( self::$main_domain ) ) {

			/* translators: 1: domain name */
			$result['label'  ]          = \sprintf( _x( 'Recipents will have issues receiving email from this server to addresses on \'%1$s\'.', 'Site Health Status Test Label' ),
				self::$main_domain
			);
			$result['status']          = 'critical';
			$result['badge' ]['color'] = 'purple';

			$result['description']     .= \wpautop( \PHP_EOL .
				_x( 'Your server uses <strong>local</strong> delivery for this domain, but the mail exchange server for your domain is a remote one. The emails will therefore stay on this server and never be delivered where it should.', 'Site Health Status Description' ) .
				/* translators: 1: domain name */
				\PHP_EOL . \sprintf( _x( 'You are advised to set the MX record for %1$s to point to this host or move the domain to this server.', 'Site Health Status Test Description' ),
				'<code>' . self::$main_domain . '</code>'
			) );

			$result['actions'] .= \sprintf( _x( 'To receive emails: Set the MX records to hostnames specified by your hosting provider.', 'Site Health Status Test Action' ), self::$main_domain );
		}
		return $result;
	}

	public    static function cpanel_notfications_domain_test(): array {
		$result = [
			'label'       => _x( 'Your cPanel® server is configured to send email notifications to a not self hosted domain.', 'Site Health Status Test Label' ),
			'status'      => 'good',
			'badge'       => [
				'label'   => _x( 'cPanel® Account', 'Site Health Status Test Badge Label' ),
				'color'   => 'blue',
			],
			'description' => \wpautop(
				_x( 'To receive notifications emails from your server it\'s cruicial that an email adress is set up and that important notifiactions are turned on.', 'Site Health Status Description' )
			),
			'actions'     => '',
			'test'        => 'notifications-domain',
		];

		$domains  = UAPI::mail_domains();
		$contact_info = UAPI::get_user_info();
		$primary   = $contact_info->contact_email ?? '';
		$secondary = $contact_info->contact_email_2 ?? '';
		$bad =\in_array( \explode( '@', $primary )[1], $domains ) && ( empty( $secondary ) || \in_array( \explode( '@', $secondary )[1], $domains ) );

		if ( $bad ) {
			$result['label'  ]          = _x( 'Your cPanel® server is not configured to send email notifications to a not self hosted domain.', 'Site Health Status Test Label' );
			$result['status']          = 'recommended';
			$result['badge' ]['color'] = 'purple';
			$result['description']     .= \wpautop( \PHP_EOL . \sprintf(
				/* translators: 1: admin url */
				_x( 'You should visit the &laquo;<a href="%1$s">' . _x( 'Contact Information', 'Site Health Status Test Description' ) . '</a> page and add an external email address.', 'Site Health Status Description' ),
				\add_query_arg( [ 'page' => self::$page->ContactsPage ], \admin_url( 'admin.php' ) )
			) );
		}
		return $result;
	}

	public    static function cpanel_notifications_test(): array {
		$result = [
			'label'       => _x( 'Your cPanel® server notifications configuration seems fine', 'Site Health Status Test Label' ),
			'status'      => 'good',
			'badge'       => [
				'label'   => _x( 'cPanel® Account', 'Site Health Status Test Badge Label' ),
				'color'   => 'blue',
			],
			'description' => \wpautop(
				_x( 'To receive notifications emails from your server it\'s cruicial that at least one email adress is set up and important notifiactions are turned on.', 'Site Health Status Test Description' )
			),
			'actions'     => '',
			'test'        => 'notifications-enabled',
		];

		$info = UAPI::get_user_info();
		$ok = true;

		foreach( [ 'notify_contact_address_change', 'notify_disk_limit', 'notify_password_change', 'notify_email_quota_limit', 'notify_twofactorauth_change' ] as $status ) {
			$ok = $ok && \boolval( $info->$status ?? true );	// notify_contact_address_change gone?
		}

		if ( ! $ok ) {

			$result['label'  ]          = _x( 'Your cPanel® server is not set up to send notifications for all important issues or events .', 'Site Health Status Label' );
			$result['status']           = 'recommended';
			$result['description']     .= \wpautop( \PHP_EOL .
				_x( 'Configure all notifications.', 'Site Health Status Test Description' ),
			);
			$result['actions'] .= \sprintf(
				/* translators: 1: <a href="link">, 2: </a> */
				_x( 'Log into your %1$scPanel and configure proper preferences &laquo;Contact Informaton&raquo; page%2$s', 'Site Health Status Test Action' ),
				'<a href="' . \add_query_arg( [ 'goto_app' => 'ContactInfo_Change'], 'https://' . self::$host_name . ':2083/' ) . '#contact_prefs_header' . '">',
				'</a>'
			);
		}
		return $result;
	}

	public    static function cpanel_two_factor_test(): array {
		$result = [
			'label'       => _x( 'Your cPanel® Two Factor authentication is enabled', 'Site Health Status Status Test Label' ),
			'status'      => 'good',
			'badge'       => [
				'label'   => _x( 'cPanel® Account', 'Site Health Status Test Badge Label' ),
				'color'   => 'blue',
			],
			'description' => \wpautop(
				_x( 'You have Two Factor authentification on cPanel® account login.', 'Site Health Status Test Description' )
			),
			'actions'     => '',
			'test'        => 'two-factor-enabled',
		];

		if ( ! UAPI::two_factor() ) {
			$result['label'  ] = _x( 'Your cPanel® account is not set up with two factor login authentification.', 'Site Health Status Test Label' );
			$result['status']  = 'recommended';
			$result['actions'] .= \sprintf(
				/* translators: 1: <a href="link">, 2: </a> */
				_x( 'Log into your %1$scPanel and configure Two Factor Authentication page%2$s under Security', 'Site Health Status Test Action' ),
				'<a href="' . \add_query_arg( [ 'goto_app' => '2fa'], 'https://' . self::$host_name . ':2083/' ) . '">',
				'</a>'
			);
		}
		return $result;
	}

	protected static function is_bool( $value ): bool {
		return
			\is_bool( $value ) ||
			( \is_int(    $value ) && \in_array( $value, \range( 0, 1 ),                  true ) ) ||
			( \is_string( $value ) && \in_array( $value, [ '', '0', '1', 'true', 'yes' ], true ) )
		;
	}
}
