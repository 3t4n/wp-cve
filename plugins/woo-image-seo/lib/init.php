<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$dirname = dirname( __DIR__ );
$plugin_dir_url = plugin_dir_url( __DIR__ );

define(
	'WOO_IMAGE_SEO',
	[
		'option_name' => 'woo_image_seo',
		'root_dir' => $dirname . '/',
		'views_dir' => $dirname . '/views/',
		'root_url' => $plugin_dir_url,
		'assets_url' => $plugin_dir_url . 'assets/',
		'default_settings' => '{"alt":{"enable":1,"force":0,"count":0,"text":{"1":"[name]","2":"[none]","3":"[none]"},"custom":{"1":"","2":"","3":""}},"title":{"enable":1,"force":1,"count":0,"text":{"1":"[name]","2":"[none]","3":"[none]"},"custom":{"1":"","2":"","3":""}}}',
		'version' => '1.4.2',
		'site_locale' => get_locale(),
        'i18n' => [
            'bg_BG' => ['css', 'howdy.jpg'],
            'ru_RU' => ['css', 'howdy.jpg'],
        ],
	]
);

require_once WOO_IMAGE_SEO['root_dir'] . 'lib/functions/global.php';

if ( is_admin() ) {
    require_once WOO_IMAGE_SEO['root_dir'] . 'lib/functions/admin.php';
    require_once WOO_IMAGE_SEO['root_dir'] . 'lib/hooks/admin.php';
} else {
    require_once WOO_IMAGE_SEO['root_dir'] . 'lib/functions/tokens.php';
    require_once WOO_IMAGE_SEO['root_dir'] . 'lib/functions/public.php';
    require_once WOO_IMAGE_SEO['root_dir'] . 'lib/hooks/public.php';
}
