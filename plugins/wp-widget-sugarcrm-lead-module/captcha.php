<?php
error_reporting(E_ALL);   
define('WP_USE_THEMES', false);
require_once("../../../wp-load.php");

header("Expires: Tue, 01 Jan 2013 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$fontPath = dirname(__FILE__) . '/fonts/times_new_yorker.ttf';
$imagePath = "image/45-degree-fabric.png";

$randomString = '';
for ($i = 0; $i < 5; $i++) {
    $randomString .= rand(0, 9);
}

set_transient('wp2sl_captcha', $randomString, 300);

$im = @imagecreatefrompng($imagePath);
$fontColor = imagecolorallocate($im, 0, 0, 0);
imagettftext($im, 30, 0, 10, 38, $fontColor, $fontPath, $randomString);

header('Content-type: image/png');
imagepng($im, null, 0);
imagedestroy($im);