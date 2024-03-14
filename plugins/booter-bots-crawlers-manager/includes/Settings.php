<?php

namespace Upress\Booter;

class Settings {

	/** @var Settings */
	private static $instance;

	/** @var Settings */
	public static function initialize() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_menu', [ $this, 'register_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'plugin_action_links_' . plugin_basename( BOOTER_FILE ), [ $this, 'plugin_action_links' ] );
		add_action( 'admin_notices', [ $this, 'maybe_notice_404_plugins' ] );
		add_action( 'admin_notices', [ $this, 'maybe_notice_plugins_deactivated' ] );

		if ( is_admin() ) {
			add_action( 'admin_bar_menu', [ $this, 'admin_bar_menu' ], 9999 );
			add_action( 'pre_update_option_booter_settings', [ $this, 'clear_logs' ], 10, 2 );
			add_action( 'pre_update_option_booter_settings', [ $this, 'reschedule_404_logs' ], 10, 2 );
		}
	}

	public function admin_bar_menu( $wp_admin_bar ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings = get_option( 'booter_settings' );

		$svg = '<svg class="ab-icon" style="height:1em;width:auto;font-size:1em;top:5px;" viewBox="0 0 764.86 529.38" xml:space="preserve"><path fill="#fab31c" d="M703.02.28c-5.57 13.06-11.02 25.83-16.47 38.61-9.33 21.89-18.61 43.82-28.06 65.66-1.41 3.25-.86 3.85 2.57 3.84 33.19-.11 66.37-.07 99.56-.04 1.34 0 3.18-.69 3.94.49 1 1.55-.87 2.73-1.62 3.9-63.14 98.69-126.34 197.34-189.54 296-13.6 21.23-27.2 42.47-40.8 63.7-.74 1.15-1.53 2.26-2.93 3.11 2.73-9.58 5.47-19.15 8.2-28.73 26.59-93.27 53.16-186.55 79.84-279.79 1.03-3.61-.22-3.75-3.15-3.74-22.73.09-45.46.05-68.19.05-5.17 0-5.21-.01-3.95-4.76C556.08 106.79 569.77 55 583.41 3.19c.55-2.1 1.26-3.2 3.84-3.19 37.79.1 75.58.07 113.37.08.65 0 1.31.11 2.4.2z"/><path fill="#fff" d="M548.25 206.76c-19.8.23-39.6.02-59.39.18-3.5.03-3.75-.91-2.96-3.98 9.58-37.01 19.02-74.06 28.52-111.09 3.8-14.84 7.69-29.65 7.46-45.16-.32-22.1-10.83-36.12-32.01-42.31-10.63-3.11-21.56-4.31-32.6-4.31C320.35.06 183.44.07 46.52.07h-4.89c.97 1.76 1.59 2.96 2.28 4.13 20.73 35.23 41.44 70.47 62.25 105.65 1.33 2.25 1.54 4.23.87 6.64-1.93 6.96-3.74 13.96-5.56 20.96A2343324.9 2343324.9 0 0114.8 470.38C10 488.81 5.22 507.24.31 525.64c-.78 2.91-.24 4.09 2.88 3.64.82-.12 1.67-.02 2.51-.02h450.06c10.6 0 10.66.01 13.39-10.19 27.47-102.57 54.91-205.16 82.46-307.71.96-3.58.82-4.65-3.36-4.6zM375.13 332.84c-5.58 21.09-11.22 42.17-16.66 63.3-.61 2.38-1.45 3.07-3.85 3.07-57.17-.07-114.33-.07-171.5.01-3.08 0-3.27-.72-2.55-3.51 22.22-85.14 44.34-170.3 66.47-255.46.51-1.98.69-3.77 3.75-3.75 34.58.13 69.16.09 103.73.11.27 0 .55.09 1.24.22-6.17 22.86-12.31 45.37-18.28 67.93-.73 2.77-2.54 2.07-4.19 2.07-17.15.04-34.3.1-51.45-.05-2.77-.02-3.81.85-4.48 3.44-9.81 38.1-19.66 76.19-29.67 114.23-.9 3.41-.14 3.8 3.02 3.79 40.29-.1 80.59-.06 120.88-.06 4.77.01 4.77.01 3.54 4.66z"/></svg>';
		$on = sprintf( '<span style="color: #48BB78;">%s</span>', __( 'Enabled', 'booter' ) );
		$off = sprintf( '<span style="color: #F56565;">%s</span>', __( 'Disabled', 'booter' ) );

		$items = [
			'bad_robots' => [ __( 'Block Bad Robots', 'booter' ), Utilities::bool_value( $settings['block']['block_bad_robots'] ) ],
			'robots_txt' => [ __( 'robots.txt Management', 'booter' ), Utilities::bool_value( $settings['robots']['enabled'] ) ],
			'reject'     => [ __( 'Reject Links', 'booter' ), Utilities::bool_value( $settings['block']['enabled'] ) ],
			'rate_limit' => [ __( 'Rate Limiting', 'booter' ), Utilities::bool_value( $settings['rate_limit']['enabled'] ) ],
			'log_404'    => [ __( '404 Logging', 'booter' ), Utilities::bool_value( $settings['log_404']['enabled'] ) ],
		];

		$main_title_icons = [];
		foreach($items as $item) {
			$main_title_icons[] = '<span style="font-size: 2em; line-height: 1em; vertical-align: middle; color: ' . ( $item[1] ? '#48BB78' : '#F56565' ) . ';">&bull;</span>';
		}

		$wp_admin_bar->add_menu( [
			'id'        => 'booter',
			'title'     => $svg . __( 'Booter - Bots & Crawlers Manager', 'booter' ) . '&nbsp;&nbsp;' . implode( '&nbsp;', $main_title_icons ),
			'href'      => admin_url( 'options-general.php?page=booter' ),
		] );

		foreach ( $items as $slug => $data ) {
			$label = $data[0];
			$value = $data[1];

			$title = sprintf(
				'<span style="display: flex; justify-content: space-between; align-items: center; white-space: nowrap;">%s:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; %s</span>',
				$label,
				$value ? $on : $off
			);

			$wp_admin_bar->add_menu( [
				'id' => 'booter-' . $slug,
				'title' => $title,
				'href' => admin_url( 'options-general.php?page=booter' ),
				'parent' => 'booter'
			] );
		}
	}

	/**
	 * Queue up the options page scripts
	 */
	public function enqueue_scripts() {
		$screen = get_current_screen();

		$ver = defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : BOOTER_VERSION;
		$ajax_url = admin_url( 'admin-ajax.php' );

		wp_enqueue_script( 'booter-notices', BOOTER_URL . "/assets/dist/js/notice.js", [], $ver, true );
		wp_localize_script( 'booter-notices', 'wp_booter_notices', [
			'ajax_url' => $ajax_url,
			'ajax_nonce' => wp_create_nonce( 'booter-notices' ),
			'confirm_disable_plugins' => __( 'Are you sure you want to disable the plugins?', 'booter' ),
		]);

		if ( 'settings_page_booter' != $screen->id ) {
			return;
		}

		wp_enqueue_script( 'booter-options', BOOTER_URL . "/assets/dist/js/options.js", [], $ver, true );
		wp_enqueue_style( 'booter-options', BOOTER_URL . "/assets/dist/css/options.css", [], $ver );

		wp_localize_script( 'booter-options', 'wp_booter', [
			'ajax_url' => $ajax_url,
			'ajax_nonce' => wp_create_nonce( 'booter-options' ),
			'is_rtl' => is_rtl(),
			'site_url' => untrailingslashit( site_url() ),
			'trans' => [
				'toggle_panel' => __( 'Toggle Panel', 'booter' ),
				'confirm_title' => __( 'Are You Sure?', 'booter' ),
				'delete' => __( 'Delete', 'booter' ),
				'cancel' => __( 'Cancel', 'booter' ),
				'allow' => __( 'Allow', 'booter' ),
				'disallow' => __( 'Disallow', 'booter' ),
				'add' => __( 'Add', 'booter' ),
				'remove' => __( 'Remove', 'booter' ),
				'no' => __( 'No', 'booter' ),
				'yes' => __( 'Yes', 'booter' ),
				'disabled' => __( 'Disabled', 'booter' ),
				'enabled' => __( 'Enabled', 'booter' ),
				'manage' => __( 'Manage', 'booter' ),
				'save' => __( 'Save', 'booter' ),
				'add_string_to_block' => __( 'Add a string to block', 'booter' ),
				'add_allowed_url' => __( 'Add allowed URL', 'booter' ),
				'add_disallowed_url' => __( 'Add disallowed URL', 'booter' ),
				'all_crawlers' => __( 'All Crawlers', 'booter' ),
				'add_new_crawler' => __( 'Add New Crawler', 'booter' ),
				'user_agent' => __( 'User-Agent', 'booter' ),
				'crawl_rate' => __( 'Crawl Rate', 'booter' ),
				'not_defined' => __( 'Not Defined', 'booter' ),
				'n_seconds' => __( '%s Seconds', 'booter' ),
				'n_minutes' => __( '%s Minutes', 'booter' ),
				'between_scans' => __( 'Delay between scans', 'booter' ),
				'crawl_rate_description' => __( 'Use this rule to throttle a crawler if they are crawling your website too frequently. Some crawlers (eg. GoogleBot) will ignore this rule, and some others might interpret it a little differently.', 'booter' ),
				'no_crawl_rate_description' => __( 'No defined rate will allow the robots to crawl at their own discretion.', 'booter' ),
				'x_ignores_rule' => __( '%s ignores this rule', 'booter' ),
				'robots_include_block_strings' => __( 'Disallow Rejected Strings', 'booter' ),
				'robots_include_block_strings_description' => __( 'Include the rejected strings in the disallowed links list.', 'booter' ),
				'robots_include_block_strings_link' => __( 'Manage Rejected Strings', 'booter' ),
				'disallow_dashboard' => __( 'Disallow Dashboard', 'booter' ),
				'disallow_dashboard_description' => sprintf( __( 'Block all URLs starting with %s.', 'booter' ), sprintf( '<code>%s</code>', admin_url() ) ),
				'allow_ajax' => __( 'Allow AJAX', 'booter' ),
				'allow_ajax_description' => sprintf( __( 'Blocking the dashboard will block the %s file as well, if your theme uses AJAX to display content - AJAX has to be enabled for search engines to be able to see this content.', 'booter' ), sprintf( '<code>%s</code>', admin_url( 'admin-ajax.php' ) ) ),
				'new_crawler_useragent' => _x( 'New Crawler', 'tab label for new crawler when no user agent is defined', 'booter' ),
				'user_agent_star' => __( 'Use * as a user agent to affect all crawlers.', 'booter' ),
				'no_crawlers_defined' => __( 'You currently do not have any crawler settings defined.', 'booter' ),
				'user_agent_exists' => __( 'This user-agent already exists.', 'booter' ),
				'user_agent_invalid' => __( 'The user-agent can not contain spaces, tabs, and any of the following charactes: ()<>@,;:"/[]?={}', 'booter' ),
				'confirm_delete_crawler' => __( 'Are you sure you want to delete the crawler "%s"?', 'booter' ),
				'disallowed_links' => __( 'Disallowed Links', 'booter' ),
				'allowed_links' => __( 'Allowed Links', 'booter' ),
				'manage_blocked_bots' => __( 'Manage Blocked Bots', 'booter' ),
				'empty_robots_disallow' => __( 'No disallowed links means that all pages will be indexed.', 'booter' ),
				'update_bad_robots_list' => __( 'Update Bad Bots From Predefined List', 'booter' ),
				'bad_bots_description' => __( 'Any bots included in this list will be automatically blocked. You can update the list from our predefined list of known bad bots by clicking the above link (your current list will not be overwritten).', 'booter' ),
				'allow_disallow_wildcards' => __( 'Some crawlers support a limited form of "wildcards" for path values:', 'booter' ),
				'allow_disallow_wildcards_star' => __( '<code>*</code> designates 0 or more instances of any valid character.', 'booter' ),
				'allow_disallow_wildcards_dollar' => __( '<code>$</code> designates the end of the URL.', 'booter' ),
			]
		] );

	}

	public function plugin_action_links( $links ) {
		$links[] = sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'options-general.php?page=booter' ),
			__( 'Settings' )
		);
		return $links;
	}

	/**
	 * Register settings for the plugin
	 */
	public function register_settings() {
		register_setting( BOOTER_SETTINGS_KEY . '_group', BOOTER_SETTINGS_KEY );
	}

	/**
	 * Add the menu under the 'Settings' sidebar item
	 */
	public function register_menu() {
		add_options_page(
			__( 'Booter - Bots & Crawlers Manager', 'booter' ),
			__( 'Booter - Bots & Crawlers Manager', 'booter' ),
			'manage_options',
			'booter',
			[ $this, 'settings_page' ]
		);
	}

	/**
	 * Display the options page
	 */
	public function settings_page() {
		require_once BOOTER_DIR . '/views/options.php';
	}

	/**
	 * Generate the default settings for the plugin or merge new defaults with the current user settings
	 */
	public static function save_default_settings() {
		$settings = get_option( BOOTER_SETTINGS_KEY );

		if ( false === $settings ) {
			$settings = [];
		}

		$settings = array_merge( self::get_default_settings(), $settings );
		update_option( BOOTER_SETTINGS_KEY, $settings );
	}

	/**
	 * Show alert if any 404 redirect plugins active
	 */
	function maybe_notice_404_plugins() {
		if ( ! current_user_can( 'activate_plugins' ) || isset( $_COOKIE['booter_404_notice_dismissed'] ) ) {
			return;
		}

		$active = wp_get_active_and_valid_plugins();

		$active = array_intersect( $active, [
			WP_PLUGIN_DIR . '/all-404-redirect-to-homepage/all-404-redirect-to-homepage.php',
			WP_PLUGIN_DIR . '/redirect-404-error-page-to-homepage-or-custom-page/redirect-404-error-page-to-homepage-or-custom-page.php',
			WP_PLUGIN_DIR . '/404-solution/404-solution.php',
			WP_PLUGIN_DIR . '/404-to-301/404-to-301.php',
			WP_PLUGIN_DIR . '/wp-404-auto-redirect-to-similar-post/wp-404-auto-redirect-similar-post.php',
			WP_PLUGIN_DIR . '/redirect-404-error-page-to-homepage/redirect-404-error-page-to-homepage.php',
			WP_PLUGIN_DIR . '/redirect-404-to-parent/moove-redirect.php',
		] );

		if ( count( $active ) <= 0 ) {
			return;
		}

		$plugin_names = [];
		$plugin_slugs = [];
		foreach ( $active as $p ) {
			$plugin_slugs[] = str_replace( WP_PLUGIN_DIR . '/', '', $p );
			$plugin_names[] = get_plugin_data( $p )['Name'];
		}

		?>
		<div class="js-booter-404-notice notice notice-warning is-dismissible">
			<p>
				<?php
					printf(
						_n(
							'Booter - Bots & Crawlers Manager has found the following 404 redirect plugin active: %s',
							'Booter - Bots & Crawlers Manager has found the following 404 redirect plugins active: %s',
							count( $plugin_names ),
							'booter'
						),
						"<strong>" . implode( ', ', $plugin_names ) . "</strong>"
					);
				?>
			</p>
			<p>
				<?php esc_html_e( 'Such redirects prevents Booter from detecting 404 errors as well as being the wrong way to handle broken links.', 'booter' ); ?>
				<?php esc_html_e( 'A 404 error is the correct response to invalid URLs while a redirect (30X) tells search engines that the URLs exists (in another location).', 'booter' ); ?>
				<br>
				<?php esc_html_e( 'We therefore recommend that you disable these plugins.', 'booter' ); ?>
			</p>
			<p>
				<button type="button" class="button button-small js-booter-disable-plugins" data-slugs="<?php echo implode( ',', $plugin_slugs ); ?>">
					<?php echo esc_html( _n( 'Disable The Plugin', 'Disable The Plugins', count( $plugin_names ), 'booter' ) ); ?>
				</button>
			</p>
		</div>
		<?php
	}

	function maybe_notice_plugins_deactivated() {
		if ( ! isset( $_GET['booter-disabled-plugins'] ) ) {
		    return;
        }

		echo '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__( 'The plugins have been disabled.', 'booter' ) . '</strong></p></div>';
    }

	/**
     * Check which SEO plugin is active with enabled sitemap option and return the URL to that sitemap
	 * @return string
	 */
	public static function get_sitemap_url() {
	    include_once ABSPATH . 'wp-admin/includes/plugin.php';

	    // Yoast
        if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) && defined( 'WPSEO_VERSION' ) && class_exists( 'WPSEO_Sitemaps_Router' ) ) {
	        $yoast_seo_xml = get_option( 'wpseo_xml' );
	        if ( version_compare( WPSEO_VERSION, '7.0', '>=' ) ) {
		        $yoast_seo                         = get_option( 'wpseo' );
		        $yoast_seo_xml['enablexmlsitemap'] = isset( $yoast_seo['enable_xml_sitemap'] ) && $yoast_seo['enable_xml_sitemap'];
	        }

	        if ( $yoast_seo_xml['enablexmlsitemap'] ) {
		        return \WPSEO_Sitemaps_Router::get_base_url( 'sitemap_index.xml' );
            }
        }

        // All In One Seo
        if ( is_plugin_active( 'all-in-one-seo-pack/all_in_one_seo_pack.php' ) ) {
	        $all_in_one_seo_xml = get_option( 'aioseop_options' );
	        if ( isset( $all_in_one_seo_xml['modules']['aiosp_feature_manager_options']['aiosp_feature_manager_enable_sitemap'] ) && 'on' === $all_in_one_seo_xml['modules']['aiosp_feature_manager_options']['aiosp_feature_manager_enable_sitemap'] ) {
	            $prefix = ! empty( $all_in_one_seo_xml['modules']['aiosp_sitemap_options']['aiosp_sitemap_filename'] ) ? $all_in_one_seo_xml['modules']['aiosp_sitemap_options']['aiosp_sitemap_filename'] : 'sitemap';
		        return trailingslashit( home_url() ) . $prefix . '.xml';
	        }
        }

        // Jetpack
		if ( is_plugin_active( 'jetpack/jetpack.php' ) && class_exists( 'Jetpack' ) ) {
		    if ( \Jetpack::is_module_active( 'sitemaps' ) ) {
		        return function_exists( 'jetpack_sitemap_uri' ) ? jetpack_sitemap_uri() : trailingslashit( home_url() ) . 'sitemap.xml';
		    }
		}

		return '';
    }

    public static function get_default_settings() {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        $upress_enable                 = get_transient( 'upress_enable' );
        $woocommerce_available         = is_plugin_active( 'woocommerce/woocommerce.php' );
        $has_existing_non_booter_robots_file = RobotsWriter::robots_file_exists() && ! RobotsWriter::is_robots_generated_by_booter();
        $sitemap_url = str_replace( trailingslashit( home_url() ), '', self::get_sitemap_url() );

        return [
            'uninstall'  => 'no',
            'debug'  => 'no',
            'rate_limit' => [
                'enabled'           => $upress_enable ? 'yes' : 'no',
                'enabled_logged_in' => 'no',
                'requests_limit'    => '30',
                'block_for'         => '600',
                'exclude'           => [ 'uptimerobot' ],
            ],
            'block'      => [
                'enabled'             => 'yes',
                'block_useragents'    => 'bots',
                'enabled_woocommerce' => $woocommerce_available ? 'yes' : 'no',
                'strings'             => [
                    'public_html',
                    'index.php?',
                    '.win',
                    '.asia',
                    '.work',
                    '.xyz',
                    '.wine',
                    '.wtf',
                    '.xin',
                    '.world',
                    '.zone',
                    '.yoga',
                    '.wiki',
                    '.business',
                    '.us',
                    '.ltd',
                    '.photos',
                    '.cool',
                    '.game',
                    '.host',
                    '.fund',
                    '.pet',
                    '.help',
                    '.mba',
                    '.clothing',
                    '.business',
                    '.life',
                    'suspect',
                    '.pass',
                    '.edu',
                    '.ink',
                    '.diet',
                    '.fit',
                    'viagra',
                    'dating',
                    'sex',
                    'mailto',
                    'CHAR(',
                ],
                'http_response'       => '410',
                'block_bad_robots'    => 'yes',
                'badrobots'           => Utilities::get_bad_robots(),
                'regex_enabled'       => 'yes',
                'regex'               => [
                    '[\/+\-_.][0-9]{7,20}[\/+\-_.]',
                ],
                'block_empty_useragents' => 'yes',
            ],
            'log_404'    => [
                'enabled'      => 'yes',
                'send_report'  => 'no',
                'report_email' => '',
            ],
            'robots'     => [
                'enabled'         => $has_existing_non_booter_robots_file ? 'no' : 'yes',
                'block_all'       => 'no',
                'sitemap_enabled' => ! empty( $sitemap_url ) ? 'yes' : 'no',
                'sitemap_url'     => $sitemap_url,
                'manage_type'     => 'simple',
                'useragents'      => [
                    [
                        'useragent'             => '*',
                        'crawl_rate'            => '0',
                        'include_block_strings' => 'yes',
                        'restrict_wp_admin'     => 'yes',
                        'allow_wp_ajax'         => 'yes',
                        'disallow'              => [
                            '/*public_html/',
                            '/*index.php?',
                        ],
                        'allow'                 => [],
                    ]
                ],
            ]
        ];
    }

	/**
	 * Clear all the logs
	 * @param mixed $value New settings
	 * @param mixed $old_value Current Settings
	 *
	 * @return mixed
	 */
	public function clear_logs( $value, $old_value ) {
		global $wpdb;

		if ( isset( $_POST['clear_404_log'] ) ) {
			$dbname = $wpdb->prefix . BOOTER_404_DB_TABLE;
			$wpdb->query( "TRUNCATE TABLE {$dbname}" );

			return $old_value;
		}

		if ( isset( $_POST['clear_debug_log'] ) ) {
		    Logger::clear_log();

			return $old_value;
        }

		return $value;
	}

	/**
	 * Reschedule the 404 logs
	 * @param mixed $value New settings
	 * @param mixed $old_value Current Settings
	 *
	 * @return mixed
	 */
	public function reschedule_404_logs( $value, $old_value ) {
		if ( $old_value['log_404']['send_report'] != $value['log_404']['send_report'] ) {
		    $log = Log404::initialize();

		    $log->deactivation_hook();
		    $log->schedule_cronjobs( $value );
        }

		return $value;
	}
}
