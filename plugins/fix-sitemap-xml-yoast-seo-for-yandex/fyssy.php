<?php
/**
	Plugin Name: Fix sitemap.xml Yoast SEO for Yandex
	Plugin URI: http://wp-r.ru/
	Description: Исправляет ошибку image:image карты сайта sitemap.xml Yoast SEO в панели вебмастера Яндекс / Corrects the error image: image sitemap.xml Yoast SEO in the webmaster's Yandex panel
	Author: mojWP
	Version: 1.0
	Author URI: http://mojwp.ru/
	Text Domain: fyssy
	License: GPL
*/

add_filter( 'wpseo_xml_sitemap_img', '__return_false' );
?>