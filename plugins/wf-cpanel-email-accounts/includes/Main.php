<?php
declare( strict_types=1 );
namespace WebFacing\cPanel\Email;
use WebFacing\cPanel\UAPI;

/**
 * Exit if accessed directly
 */
\class_exists( 'WP' ) || exit;

abstract class Main {

	public    const pf = 'wf_cpanel_email_';

	private   const proisp_packages = [
		'prostart'      => 'Pro Start',
		'promedium'     => 'Pro Medium',
		'propremium'    => 'Pro Premium',
		'enterprise10'  => 'Enterprise 10',
		'enterprise30'  => 'Enterprise 30',
		'enterprise60'  => 'Enterprise 60',
		'enterprise100' => 'Enterprise 100',
	];

	protected const pro_link = '<a href="https://webfacing.eu/">Pro edition</a>';

	protected const list = [ 'add_','batch','ac', 'exceeded', 'forward','is_', 1, 'license', 'count', 'er', 'soon_', 'usage', 3 ];

	public    static \stdClass  $plugin;

	public    static string     $pf;

	public    static bool       $is_debug = false;

	protected static ?string    $dev_email = null;

	protected static ?\WP_User  $dev_user = null;

	protected static bool       $is_dev = false;

	public    static bool       $is_pro = false;

	private   static bool       $did_init = false;

	protected static \stdClass  $page;

	protected static \WP_Screen $screen;

	protected static bool       $is_cpanel = false;

	public    static bool       $has_exec;

	public    static bool       $has_http;

	public    static bool       $use_exec = true;

	public    static bool       $is_subadmin = false;

	public    static string     $site_domain = '';

	public    static string     $main_domain = '';

	public    static int        $main_parts = 0;

	public    static bool       $domain_only = false;

	public    static bool       $remote_cpanel = false;

	public    static string     $host_name = 'localhost';

	public    static bool       $is_proisp = false;

	public    static float      $cpanel_version;

	public    static string     $cpanel_user;

	public    static string     $active_key = self::pf . 'token.active.';

	public    static ?string    $plan = null;

	public    static string     $package;

	public    static int        $bytes_used;

	public    static int        $byte_limit;

	public    static string     $class = self::class;

	public    static \stdClass  $usage;

	public    static string     $usage_key_name;

	public    static int        $usage_key_time = 1 * \WEEK_IN_SECONDS;

	public    static \stdClass  $error;

	protected static \stdClass  $license;

	public    static bool       $is_exceeded = false;

	public    static bool       $is_soon_exceeded = false;

	protected static string     $inv_text;

	protected static string     $exp_text;

	protected static function init(): void {

		if ( ! self::$did_init ) {

			self::$pf          = \trim( \str_replace( '_', '-', self::pf ) );

			self::$plugin      = (object) \get_plugin_data( PLUGIN_FILE );

			self::$is_debug    = \defined( 'WF_DEBUG' ) && \WF_DEBUG;

			self::$cpanel_user = \defined( 'WF_CPANEL_USER' ) ? \WF_CPANEL_USER : \explode( \DIRECTORY_SEPARATOR, \ABSPATH )[2];

			self::$site_domain = \wp_parse_url( \site_url(), \PHP_URL_HOST );

			self::$remote_cpanel = \defined( 'WF_CPANEL_HOST' ) && ! empty( \WF_CPANEL_HOST ) && ! empty( self::$cpanel_user );

			if ( self::$remote_cpanel ) {
				self::$host_name = \WF_CPANEL_HOST;
				self::$has_exec = false;
			} else {
				$saddr = $_SERVER['SERVER_ADDR'] ?? '';

				if ( empty ( $saddr ) ) {
					self::$host_name = self::$site_domain;
				} else {
					self::$host_name  = \gethostbyaddr( $saddr ) ?: '';
				}
				self::$has_exec = UAPI::set_shell();
			}

			self::$active_key  = self::pf . 'token.active.' . self::$cpanel_user . ( self::$remote_cpanel ? '@' . self::$host_name : '' );

			self::$has_http = self::set_token();

			self::$use_exec = ! self::$remote_cpanel && self::$has_exec && get_option( self::$active_key ) === UAPI::exec;

			self::$is_cpanel   = ( self::$has_http || self::$has_exec ) && UAPI::has_features();

			if ( self::$is_cpanel ) {
				UAPI::$cpanel_user = UAPI::user() ?? self::$cpanel_user;
				self::$active_key = self::pf . 'token.active.' . UAPI::$cpanel_user . ( self::$remote_cpanel ? '@' . self::$host_name : '' );
			}

			self::$usage_key_name = self::$pf . self::class . '-usage';

			self::$usage = (object) \get_transient( self::$usage_key_name );

			self::$did_init = true;
		}
	}

	public    static function load(): void {

		\define( __NAMESPACE__ . '\PLUGIN_BASENAME', \basename( PLUGIN_DIR ) . \DIRECTORY_SEPARATOR . \basename( PLUGIN_FILE ) );

		if ( \apply_filters( self::pf . 'frontend', false ) ) {
			self::init();
		}
		ShortCode::load();
	}

	public    static function admin(): void {

		self::$page        = new \stdClass;

		self::$dev_email = \defined( 'WF_DEV_EMAIL' ) &&
			\is_string( \WF_DEV_EMAIL ) &&
			\str_contains( \WF_DEV_EMAIL, '@' ) &&
			\strlen( \WF_DEV_EMAIL ) > 5 ?
			\sanitize_email( \WF_DEV_EMAIL ) :
			null
		;

		self::init();

		if ( self::$is_cpanel ) {

			self::$main_domain = UAPI::main_domain();

			self::$main_parts  = \count( \explode( '.', self::$main_domain ) );

			if ( ! \wp_doing_ajax() && ! \wp_doing_cron() ) {
				UAPI::set_locale();
				self::$plan = UAPI::user( 'plan' );
				$host_ids = \explode( '.', self::$host_name );
				$num_f    = \count( $host_ids );
				$host_id  = $num_f > 1 && \array_key_exists( $num_f - 1, $host_ids ) ? $host_ids[ $num_f - 2] . '.' . $host_ids[ $num_f - 1] : null;
				self::$is_proisp     = \in_array( $host_id, [ 'proisp.no', 'proisp.eu', 'proisp.com' ], true );
				self::$package     = self::$is_proisp ? ( self::proisp_packages[ self::$plan ] ?? '' ) : \ucfirst( self::$plan );
			}
		}

		\add_action( 'wp_loaded', static function(): void {
			self::create_license();
		}, 11 );

		\add_action( 'wp_loaded', static function(): void {
			self::set_exc();
			self::$inv_text = _x( 'Your %1$s license is invalid!', 'Notice Error Message' );
			self::$exp_text = _x( 'Your %1$s license will expire in %2$d days.', 'Notice Warning Message' );
		}, 91 );

		\add_action( 'init', static function(): void {

			/**
			 * Set this developer user
			 */
			$parts = \explode( '/', \rtrim( self::$plugin->AuthorURI, ' /' ) );
			$id = self::$dev_email ?? \end( $parts );
			self::$dev_user = \get_user_by( self::$dev_email ? 'email' : 'login', $id ) ?: null;
			self::$is_dev = self::$dev_user && self::$dev_user->ID === \get_current_user_id();

			self::$is_subadmin = \is_multisite() && ! \is_super_admin();

			$site_domain_only_defined =
				\defined( 'WF_CPANEL_EMAIL_SITE_DOMAIN_ONLY' ) || \defined( 'WF_CPANEL_EMAIL_DOMAIN_ONLY' )
			;
			$site_domain_only =
				( \defined( 'WF_CPANEL_EMAIL_SITE_DOMAIN_ONLY' ) && \WF_CPANEL_EMAIL_SITE_DOMAIN_ONLY ) ||
				( \defined( 'WF_CPANEL_EMAIL_DOMAIN_ONLY'      ) && \WF_CPANEL_EMAIL_DOMAIN_ONLY      )
			;
			self::$domain_only = $site_domain_only_defined ?
				$site_domain_only :
				self::$is_subadmin || $site_domain_only
			;

			self::$domain_only = \apply_filters( self::pf . 'site_domain_only', self::$domain_only );
		}, 1 );

		\add_action( 'current_screen', static function(): void {
			self::$screen = \get_current_screen();
		}, 0 );

		\add_action( 'admin_notices', static function(): void {
			$user_email  = \wp_get_current_user()->user_email;
			$user_domain = \explode( '@', $user_email )[1] ?? '@';
			$cap = \apply_filters( self::pf . 'capability', 'manage_options', self::$screen->id );

			if ( \current_user_can( $cap ) ) {
			} elseif ( \str_ends_with( self::$site_domain, $user_domain ) ) {
				$cap = 'cpanel';
			}

			if ( self::$is_cpanel ) {

				if ( \str_ends_with( self::$screen->id, self::$page->AccountsPage ) && ! isset( $_GET['r'] ) ) {

					if ( self::$is_pro ) {

						if ( self::$class::$is_valid ) {

							if ( self::$is_exceeded ) { ?>
								<div class="notice notice-warning">
									<p><?php \printf( _x( 'Your purchasd use of this plugin is exceeded. To add or create more emails, please purchase the %1$s again, with sufficient usage limits.', 'Notice Error Message' ), self::pro_link ); ?></p>
								</div>
<?php
							} elseif ( self::$is_soon_exceeded ) { ?>
								<div class="notice notice-info">
									<p><?php \printf( _x( 'Your purchasd use of this plugin will soon be exceeded. To add or create more emails, please purchase the %1$s again soon, with sufficient usage limits.', 'Notice Error Message' ), self::pro_link ); ?></p>
								</div>
<?php
							}
						}
					} else {

						if ( self::$is_exceeded ) { ?>
							<div class="notice notice-warning">
								<p><?php \printf( _x( 'The intended free use of this plugin is exceeded. To add or create more emails, please buy the %1$s. To remove this message stop using this %2$s plugin pages for %3$d days and the counters will reset.', 'Notice Error Message' ), self::pro_link, _x( 'cPanel® Email', 'Menu label' ), self::$usage_key_time / \DAY_IN_SECONDS ); ?></p>
							</div>
<?php
						}
					}
				}
			} elseif ( \current_user_can( $cap ) ) { ?>
				<div class="notice notice-error">
					<p>
<?php
				_ex( 'cPanel® Email features not available!', 'Notice Error Message' );

				if ( \current_user_can( 'deactivate_plugins' ) ) { ?>
					</p><p>
<?php
					if ( self::$has_http || self::$has_exec ) {
						\printf(
							/* translators: 1: This Plugin Name */
							_x( 'cPanel® not detected, or no email features available! You should just deactivate the "%1$s" plugin because it can\'t work on this host.', 'Notice Error Message' ),
							self::$plugin->Name
						);
					} else {
						\printf(
							/* translators: 1: shell_exec, 2: constant, 3: wp-config.php, 4: Documentation link */
							_x( 'cPanel® not yet detected, since the %1$s function is disabled and no valid API token configured. If <strong>on cPanel®</strong>, please log in on it, create a temporary token and add it in a constant %2$s in your %3$s file. See %4$s on how to create one. Later, tokens can be managed from your Admin area.', 'Notice Error Message' ),
							'<code>' . UAPI::exec . '</code>',
							'<code>WF_CPANEL_API_TOKEN</code>',
							'<code>wp-config.php</code>',
							'<a href="https://docs.cpanel.net/cpanel/security/manage-api-tokens-in-cpanel/" target="_blank" rel="external noreferrer noopener">Manage API Tokens in cPanel®</a>'
						);
					}
				} else {
					_ex( 'Please contact your administrator.', 'Notice Error Message' ); ?>
<?php
				} ?>
					</p>
				</div>
<?php
			}

			if ( self::$is_dev && self::$is_debug && self::$screen->id !== 'site-health' ) { ?>
				<div class="notice notice-info is-dismissible">
					<p>
						<?php echo self::$plugin->Name, ' ', self::$plugin->Version , ' ', self::$cpanel_user; ?><br/>
						<?='API: '.UAPI::$response_message?>, <?=UAPI::exec,' ',self::$has_exec?'':'not ','allowed'?><br/>
<?php
						$show = [];

						foreach ( [ 'forwarder', 'account' ] as $t ) {

							foreach ( [ false, true ] as $b ) {

								foreach ( [ false, true ] as $m ) {
									$show[ \substr( $t, 0, 1 ) . ( $b ? 'b' : '' ) . ( $m ? 'm' : '' ) ] = self::get_use( $t, $b, $m );
								}
							}
						}
						\print_r( $show ); ?>
					</p>
				</div>
<?php 				error_log( [ 'version' => self::$plugin->Version, 'dev_email' => self::$dev_user->user_email ] );
			} elseif ( self::$is_dev && ! self::$is_debug ) {
				\defined( 'QM_DISABLED' ) || \define( 'QM_DISABLED', true );
			}
		} );

		if ( self::$is_cpanel ) {

			self::$bytes_used = (int) UAPI::quotas()->bytes_used;

			self::$byte_limit = (int) UAPI::quotas()->byte_limit;

			AccountsPage::admin();
			NewEmail::admin();
			BoxesPage::admin();
			BackupsPage::admin();
			ContactsPage::admin();
			TokensPage::admin();
			SiteHealth::admin();

			\add_filter( 'dashboard_glance_items', static function( ?array $elements ): array {

				if ( \current_user_can( 'publish_pages' ) ) {
					$elements = $elements ?? [];
					$href = \add_query_arg( [ 'page' => self::$pf . 'accounts' ], \admin_url( 'admin.php' ) );
					// Protect against duplicate from my other plugin
					$dup = \preg_grep( '/ class="email-count"/', $elements );

					if ( \count( $dup ) ) {
						unset( $elements[ \array_keys( $dup)[0] ] );
					}

					$num_accounts = UAPI::count_emails();
					$elements[ self::$pf ] = '<a href="' . $href . '" class="cpanel-email-count">' . $num_accounts . ' ' . _nx( 'cPanel® email account used', 'cPanel® email accounts used', $num_accounts, 'Right Now' ) . '</a>';

					// Protect against duplicate from my other plugin
					$dup = \preg_grep( '/" class="disk-count /', $elements );

					if ( \count( $dup ) ) {
						unset( $elements[ \array_keys( $dup)[0] ] );
					}
					$elements[] = '<a href="' . $href . '" class="cpanel-disk-used" title="' . _x( 'cPanel® account storage space limit:', 'Right Now' ) . ' ' . \size_format( self::$byte_limit ) . '">' . \size_format( self::$bytes_used, 1 ) . ' ' . _x( 'cPanel® space used', 'Right Now' ) . '</a>';
				}
				return $elements;
			}, 112 );

			\add_action( 'rightnow_end', static function(): void {

				if ( \current_user_can( 'activate_plugins' ) && ! \class_exists( 'WebFacing\cPanel\Main' ) ) {
					echo \PHP_EOL, \wpautop( \sprintf(
						__( 'Hosted on a %1$s plan with %2$s storage space limit.' ),
						self::$package,
						\size_format( self::$byte_limit )
					) );
				}
			} );

			/*
			 * Custom Icon for Disk space in "At a Glance" widget
			 */
			\add_action( 'admin_head', static function(): void {

				if ( self::$screen->id === 'dashboard' ) { ?>
					<style>
						#dashboard_right_now li a.cpanel-email-count:before {
							content: '\f465';
							margin-left: -1px;
						}
						#dashboard_right_now li a.cpanel-disk-used:before {
							content: '\f17e';
							margin-left: -1px;
						}
					</style>
<?php
				}
			} );

	//		Delete our expired transients
			\add_action( 'admin_footer', static function(): void {
				global $wpdb;

				if ( \idate( 's' ) === 0 ) {
					$pf        = self::$pf;
					$threshold = \time() - \MINUTE_IN_SECONDS;
					$sql       = "
						DELETE FROM `T1`, `T2`
						USING `{$wpdb->options}` AS `T1`
						JOIN `{$wpdb->options}` AS `T2` ON `T2`.`option_name` = REPLACE( `T1`.`option_name`, '_timeout', '' )
						WHERE ( `T1`.`option_name` LIKE '_transient\_timeout\_{$pf}%' OR `T1`.`option_name` LIKE '_site\_transient\_timeout\_{$pf}%' )
						AND `T1`.`option_value` < '{$threshold}'
					;";
					$rows = \ceil( $wpdb->query( $sql ) / 2 );
					\do_action( 'qm/alert', 'Deleted {rows} transients.', [ 'rows' => $rows ] );
				}
			} );
		}
	}

	public    static function set_token( string $raw_token = '' ): bool {

		if ( empty( $raw_token ) ) {
//			$active_key = self::pf . 'token.active.' . self::$cpanel_user;
			$active_key = self::$active_key;
			$token = get_option( $active_key );

			if ( $token ) {
				$aknown_key = self::pf . 'token.' . self::$cpanel_user . ( self::$remote_cpanel ? '@' . self::$host_name  : '' ) . '.' . $token;
				$raw_token  = get_option( $aknown_key );

				if ( $raw_token ) {
					$raw_token = \wp_doing_ajax() || UAPI::validate_token( $raw_token ) ? $raw_token : '';
				}

				if ( empty( $raw_token ) ) {

					if ( self::$has_exec ) {
						update_option( $active_key, UAPI::exec );
					} else  {
						delete_option( $active_key );
					}
				}
			}
		}

		if ( empty( $raw_token ) ) {
			$raw_token = UAPI::get_defined_api_token();
			$raw_token = \wp_doing_ajax() || UAPI::validate_token( $raw_token ) ? $raw_token : '';
		}

		if ( empty( $raw_token ) ) {
			return false;
		}
		UAPI::$api_token = self::$cpanel_user . ':' . $raw_token;
		return true;
	}

	public    static function register_usage( \WP_Error $errors, string $function, int $count, bool $batch ): \WP_Error {

		if ( self::$class::$usage ?? true ) {
			self::$class::$usage = new \stdClass;
		}

		if ( self::$is_exceeded && ( self::$error->$function->count['code'] ?? true ) ) {
			$errors->add( self::$error->$function->count['code'], self::$error->$function->count['description'] );
		}

		if ( self::$class::$usage->$function ?? true ) {
			self::$class::$usage->$function = new \stdClass;
			self::$class::$usage->$function->count = 0;
		}
		self::$class::$usage->$function->count += $count;

		if ( $batch ) {

			if ( self::$class::$usage->batch ?? true ) {
				self::$class::$usage->batch = new \stdClass;
			}

			if ( self::$class::$usage->batch->$function ?? true ) {
				self::$class::$usage->batch->$function = new \stdClass;
				self::$class::$usage->batch->$function->count = 0;
			}

			self::$class::$usage->batch->$function->count += $count;

			if ( self::$is_exceeded && ( self::$error->$function->count['code'] ?? false ) ) {
				$errors->add( self::$error->batch->$function->count['code'], self::$error->batch->$function->count['description'] );
			}
/*		} else {

			if ( self::$is_exceeded && ( self::$error->$function->count['code'] ?? false ) ) {
				$errors->add( self::$error->$function->count['code'], self::$error->$function->count['description'] );
			}

			if ( \is_null( self::$class::$usage->$function ) ) {
				self::$class::$usage->$function = new \stdClass;
				self::$class::$usage->$function->count = 0;
			}
			self::$class::$usage->$function->count += $count;
*/		}
		\set_transient( self::$usage_key_name, self::$class::$usage, self::$usage_key_time );

		if ( self::$is_pro ) {
			update_option( self::$usage_key_name, self::$class::$usage );
		}
		return $errors;
	}

	public    static function email_mx_self( string $domain = '' ): bool {
		$domain = $domain ?: self::$site_domain;
		$hostparts = \explode( '.', self::$host_name );
		$hostpartscount = \count( $hostparts );

		if ( $hostpartscount < 2 ) {
			error_log ( [ __METHOD__, 'hostparts',  $hostparts ] );
		}
		$hostends = ( $hostpartscount > 1 ? $hostparts[ $hostpartscount - 2] . '.' : '' ) . $hostparts[ $hostpartscount - 1];
		$hosts = [];
		$result = \getmxrr( $domain, $hosts );
		return $result && \array_key_exists( 0, $hosts ) && ( \str_ends_with( $hosts[0], $domain ) || \str_ends_with( $hosts[0], $hostends ) );
	}

	public    static function email_to_utf8( string $email ): string {

		if ( \is_email( $email ) && \function_exists( 'idn_to_utf8' ) ) {
			$parts = \explode( '@', $email, 2 );
			return $parts[0] . '@' . \idn_to_utf8( $parts[1] );
		} else {
			return $email;
		}
	}

	public    static function send_password( string $to_email, string $domain, string $password ): bool {
		return false;
	}

	public    static function create_license(): void {

		if ( ! self::$is_pro ) {
			list ( $a, $b, $c, $e, $f, $i, $k, $l, $n, $r, $s, $u, $x ) = self::list;

			self::$$l = (object) [
				$a . $f . $r     => (object) [ $n => -.05 ],
				$a . $c . $n     => (object) [ $n => -.20 ],
				$b => (object) [
					$a . $f . $r => (object) [ $n => -.25 ],
					$a . $c . $n => (object) [ $n => -.50 ],
				],
			];
		}
	}

	public    static function set_exc(): void {
		list ( $a, $b, $c, $e, $f, $i, $k, $l, $n, $r, $s, $u, $x ) = self::list;
		$uf  = self::get_use( $f . $r );
		$ua  = self::get_use( $c . $n );
		$ubf = self::get_use( $f . $r,  true );
		$uba = self::get_use( $c . $n,  true );
		$lf  = self::get_use( $f . $r, false, true );
		$la  = self::get_use( $c . $n, false, true );
		$lbf = self::get_use( $f . $r,  true, true );
		$lba = self::get_use( $c . $n,  true, true );

		if ( self::$is_pro ) {
			self::${ $i .  $e } = ( $ubf > $lbf      ) || ( $uba > $lba      );
			self::${ $i.$s.$e } = ( $lbf - $ubf < 30 ) || ( $lba - $uba < 20 );
		} else {
			self::${ $i .  $e } = ( $ubf > $lbf      ) || ( $uba > $lba      ) || ( $ua > $la      ) || ( $uf > $lf      );
			self::${ $i.$s.$e } = ( $lbf - $ubf < 30 ) || ( $lba - $uba < 20 ) || ( $lf - $uf < 20 ) || ( $la - $ua < 10 );
		}
		self::$error = (object) [
			'add_forwarder' => (object) [
				'count'     => [
					'code'        => 402,
					'description' => \sprintf(
						_x( 'Your %1$s usage limit is now exceeded. Used %2$d of %3$d forwarders.', 'Notice Error Message' ),
						self::$plugin->Name, $uf, $lf
					)
				],
			],
			'add_account'   => (object) [
				'count'     => [
					'code'        => 429,
					'description' => \sprintf(
						_x( 'Your %1$s usage limit is now exceeded. Used %2$d of %3$d accounts.', 'Notice Error Message' ),
						self::$plugin->Name, $ua, $la
					)
				],
			],
			'batch'         => (object) [
				'add_forwarder' => (object) [
					'count'     => [
						'code'        => 402,
						'description' => \sprintf(
							_x( 'Your %1$s usage limit is now exceeded. Used %2$d of %3$d forwarders in batch.', 'Notice Error Message' ),
							self::$plugin->Name, $ubf, $lbf
						)
					],
				],
				'add_account'   => (object) [
					'count'     => [
						'code'        => 429,
						'description' => \sprintf(
							_x( 'Your %1$s free usage limit is now exceeded. Used %2$d of %3$d accounts in batch.', 'Notice Error Message' ),
							self::$plugin->Name, $uba, $lba
						)
					],
				],
			],
		];
	}

	public    static function get_use( string $action, bool $batch = false, bool $max = false ): int {
		list ( $a, $b, $c, $e, $f, $i, $k, $l, $n, $r, $s, $u, $x ) = self::list;
		$t = \trim( $action );
		$t = \str_starts_with( $t, 'new-' ) ? \str_replace( 'new-', $a, $t ) : $t;
		$t = \str_contains ( $t,    '_' ) ? $t : $a . $t;
		$t = \str_ends_with( $t, $f ) ? \str_replace( $f, $f . $r, $t ) : $t;

		if ( \in_array( $t, [ $a . $f . $r, $a . $c . $n ], true ) ) {
			$u = $max ? self::$$l : self::$class::$$u;
			$c = ! self::$is_pro && $max;
			$x = $c ? 3 : 0;
			$v = $batch ? ( $u->$b->$t->$n ?? $x ) : ( $u->$t->$n ?? $x );
			$k = $c ? -$k : $v ** 2;
			return $v === 0 ? $v : \absint( $k / $v );
		} else {
			return 0;
		}
	}
}
