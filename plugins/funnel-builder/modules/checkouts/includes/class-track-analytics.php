<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
include __DIR__ . '/tracking/class-abstract-tracking.php';
include __DIR__ . '/tracking/class-facebook.php';
include __DIR__ . '/tracking/class-google-analytics.php';
include __DIR__ . '/tracking/class-google-ads.php';
include __DIR__ . '/tracking/class-pinterest.php';
include __DIR__ . '/tracking/class-tiktok.php';
include __DIR__ . '/tracking/class-snapchat.php';


WFACP_Analytics_Pixel::get_instance(); // Facebook Pixel
WFACP_Analytics_GA::get_instance(); //Google Analytics
WFACP_Analytics_GADS::get_instance(); // Google Ads
WFACP_Analytics_Pint::get_instance();// Pinterest
WFACP_Analytics_TikTok::get_instance();// Tiktok
WFACP_Analytics_Snapchat::get_instance();// SnapChat
