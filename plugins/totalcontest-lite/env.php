<?php

// Uploads path
$upload = wp_upload_dir();

// TotalContest environment
return apply_filters(
	'totalcontest/filters/environment',
	array(
		'id'             => 'totalcontest',
		'name'           => 'TotalContest',
		'version'        => '2.7.5',
		'source'         => 'totalsuite.net',
		'versions'       => array(
			'wp'    => $GLOBALS['wp_version'],
			'php'   => PHP_VERSION,
			'mysql' => $GLOBALS['wpdb']->db_version(),
		),
		'textdomain'     => 'totalcontest',
		'domain'         => empty( $_SERVER['SERVER_NAME'] ) ? 'localhost' : $_SERVER['SERVER_NAME'],
		'root'           => TOTALCONTEST_ROOT,
		'path'           => wp_normalize_path( plugin_dir_path( TOTALCONTEST_ROOT ) ),
		'url'            => plugin_dir_url( TOTALCONTEST_ROOT ),
		'basename'       => plugin_basename( TOTALCONTEST_ROOT ),
		'rest-namespace' => 'totalcontest/v2',
		'namespace'      => 'TotalContest',
		'dirname'        => dirname( plugin_basename( TOTALCONTEST_ROOT ) ),
		'cache'          => array(
			'path' => WP_CONTENT_DIR . '/cache/totalcontest/',
			'url'  => content_url( '/cache/totalcontest/' ),
		),
		'exports'        => array(
			'path' => WP_CONTENT_DIR . '/exports/totalcontest/',
			'url'  => content_url( '/exports/totalcontest/' ),
		),
		'slug'           => 'totalcontest',
		'prefix'         => 'totalcontest_',
		'short-prefix'   => 'tc_',
		'options-key'    => 'totalcontest_options_repository',
		'tracking-key'   => 'totalcontest_tracking',
		'onboarding-key' => 'totalcontest_onboarding',
		'db'             => array(
			'version'    => '200',
			'option-key' => 'totalcontest_db_version',
			'tables'     => array(
				'log'   => function () {
					return $GLOBALS['wpdb']->prefix . 'totalcontest_log';
				},
				'votes' => function () {
					return $GLOBALS['wpdb']->prefix . 'totalcontest_votes';
				}
			),
			'prefix'     => function () {
				return (string) $GLOBALS['wpdb']->prefix;
			},
			'charset'    => (string) $GLOBALS['wpdb']->get_charset_collate(),
		),
		'api'            => array(
			'update'             => 'https://totalsuite.net/api/v2/products/totalcontest/update/',
			'store'              => 'https://totalsuite.net/api/v2/products/totalcontest/store/{{license}}/',
			'activation'         => 'https://totalsuite.net/api/v2/products/totalcontest/activate/',
			'check-access-token' => 'https://totalsuite.net/api/v2/users/check/',
			'blogFeed'           => 'https://totalsuite.net/wp-json/wp/v2/blog_article',
			'tracking'           => [
				'nps'         => 'https://collect.totalsuite.net/nps',
				'uninstall'   => 'https://collect.totalsuite.net/uninstall',
				'environment' => 'https://collect.totalsuite.net/env',
				'events'      => 'https://collect.totalsuite.net/event',
				'log'         => 'https://collect.totalsuite.net/log',
				'onboarding'  => 'https://collect.totalsuite.net/onboarding',
			]
		),
		'links'          => array(
			'activation'     => admin_url( 'edit.php?post_type=contest&page=dashboard&tab=dashboard>activation' ),
			'my-account'     => admin_url( 'edit.php?post_type=contest&page=dashboard&tab=dashboard>my-account' ),
			'signin-account' => 'https://totalsuite.net/ext/auth/signin',
			'changelog'      => 'https://totalsuite.net/product/totalcontest/changelog/#version-2.7.5',
			'website'        => 'https://totalsuite.net/product/totalcontest/',
			'support'        => 'https://totalsuite.net/support/',
			'customization'  => 'https://totalsuite.net/services/new/?department=25',
			'translate'      => 'https://totalsuite.net/translate/',
			'search'         => 'https://totalsuite.net/documentation/totalcontest/',
			'forums'         => 'https://totalsuite.net/forums/',
			'totalsuite'     => 'https://totalsuite.net/',
			'subscribe'      => 'https://subscribe.misqtech.com/totalsuite/',
			'twitter'        => 'https://twitter.com/totalsuite',
			'facebook'       => 'https://fb.me/totalsuite',
			'youtube'        => 'https://www.youtube.com/channel/UCp44ZQMpZhBB6chpKWoeEOw/',
			'upgrade-to-pro' => admin_url( 'edit.php?post_type=contest&page=upgrade-to-pro' ),
			'totalrating'    => 'https://totalsuite.net/products/totalrating/',
			'totalpoll'      => 'https://totalsuite.net/products/totalpoll/',
			'totalsurvey'    => 'https://totalsuite.net/products/totalsurvey/',
		),
		'requirements'   => array(
			'wp'    => '4.6',
			'php'   => '5.5',
			'mysql' => '5.5',
		),
		'recommended'    => array(
			'wp'    => '5.0',
			'php'   => '7.0',
			'mysql' => '8.0',
		),
		'autoload'       => array(
			'loader' => dirname( TOTALCONTEST_ROOT ) . '/vendor/autoload.php',
			'psr4'   => array(
				"TotalContest\\Modules\\Templates\\"  => array(
					trailingslashit( $upload['basedir'] . '/totalcontest/templates/' ),
					dirname( TOTALCONTEST_ROOT ) . '/modules/templates',
				),
				"TotalContest\\Modules\\Extensions\\" => array(
					trailingslashit( $upload['basedir'] . '/totalcontest/extensions/' ),
					dirname( TOTALCONTEST_ROOT ) . '/modules/extensions',
				),
			),
		),
	)
);
