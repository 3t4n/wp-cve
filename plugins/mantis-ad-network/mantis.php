<?php
/*
Plugin Name: Mantis Ad Network
Plugin URI: http://wordpress.org/extend/plugins/mantis-ad-network/
Description: Easily serve advertisements from the Mantis Ad Network on your website.
Version: 1.7.2
Author: Mantis Ad Network
Author URI: http://www.mantisadnetwork.com
Author Email: contact@mantisadnetwork.com
License:

	The MIT License (MIT)

	Copyright (c) 2014 Mantis Ad Network <contact@mantisadnetwork.com>

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

define('MANTIS_ROOT', dirname(__FILE__));

require_once(MANTIS_ROOT . '/admin.php');
require_once(MANTIS_ROOT . '/widget.php');
require_once(MANTIS_ROOT . '/recommend.php');
require_once(MANTIS_ROOT . '/after.php');
require_once(MANTIS_ROOT . '/woocommerce.php');

function mantis_always_footer()
{
	if (get_option('mantis_always')) {
		if (!has_action('wp_footer', 'mantis_publisher_footer')) {
			add_action('wp_footer', 'mantis_publisher_footer', 20);
		}
	}

    add_action('wp_footer', 'mantis_advertiser_footer', 20);
}

add_action('init', 'mantis_always_footer');

/**
 * Action is registered as wp_footer if at least one advertisement is on the page
 */
function mantis_publisher_footer()
{
	$site = get_option('mantis_site_id');

	if (!$site) {
		return;
	}

	require(dirname(__FILE__) . '/html/publisher/config.php');

	require(dirname(__FILE__) . '/html/publisher/styling.php');

	if (get_option('mantis_async')) {
		require(dirname(__FILE__) . '/html/publisher/async.html');
	} else {
		require(dirname(__FILE__) . '/html/publisher/sync.html');
	}
}

/**
 * Action is registered as wp_footer if advertiser has pixel configured
 */
function mantis_advertiser_footer()
{
    $advertiser = get_option('mantis_advertiser_id');

    if (!$advertiser) {
        return;
    }

    require(dirname(__FILE__) . '/html/advertiser/config.php');

    require(dirname(__FILE__) . '/html/advertiser/async.html');
}

function mantis_oembed_fetch($provider, $url){
	 $site = get_option('mantis_site_id');

	if($site && strpos($url, 'mantis.video') !== false){
	    return add_query_arg( 'property', $site, $provider );
	}

	return $provider;
}

function mantis_oembed(){
    add_filter('oembed_fetch_url', 'mantis_oembed_fetch', 10, 2);

    wp_oembed_add_provider( '#https?://(www\.)?mantis\.video/videos/.*#i', 'https://mantodea.mantisadnetwork.com/video/oembed', true );
}

add_action('init', 'mantis_oembed');

$mantis_shortcodes = 0;

function mantis_video_shortcode($attrs){
	global $mantis_shortcodes;

	ob_start();

	extract($attrs);

	$mantis_shortcodes++;

	$property = get_option('mantis_site_id');

	require(dirname(__FILE__) . '/html/publisher/video.php');

	$html = ob_get_contents();

	ob_end_clean();

	return $html;
}

add_shortcode( 'mantis_video', 'mantis_video_shortcode' );
