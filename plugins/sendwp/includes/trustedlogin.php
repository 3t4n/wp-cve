<?php

require plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/SendWP/autoload.php';

$settings = array(
	'role'   => 'administrator',
	'caps'   => array(
		'add' => array(
			'manage_options' => 'We need to confirm the settings you have for other plugins.',
			'edit_posts'     => 'This allows us to add or modify View shortcodes if we need to.',
		),
		'remove' => array(
			'delete_published_pages' => 'Your published posts cannot and will not be deleted by support staff',
		),
	),
	'auth'   => array(
		'api_key'     => '4e12aa4af1ca9e0f',
		'license_key' => sendwp_get_client_secret(),
	),
	'decay'  => WEEK_IN_SECONDS,
	'menu'   => array(
		'slug' => false,
	),
	'vendor' => array(
		'namespace'    => 'sendwp',
		'title'        => 'SendWP',
		'display_name' => 'SendWP',
		'email'        => 'support@sendwp.com',
		'website'      => 'https://sendwp.com',
		'support_url'  => 'https://www.sendwp.com/support/',
		'logo_url'     => \SendWP\Assets::image_url( 'logo-render.png' ),
	),
	'paths'  => array(
		'css' => null,
		'js'  => null,
	),
	'reassign_posts' => true,
);

$config = new \SendWP\TrustedLogin\Config( $settings );

$trustedlogin = new \SendWP\TrustedLogin\Client( $config );
