<?php
// License: GPLv2

// Set this to allow local caching of images.
$DO_CACHE = @file_exists(dirname(__FILE__) . "/enablecache.txt");

// Simply forward a youtube video.
isset($_REQUEST["v"]) or die("v");

$v = $_REQUEST["v"];
preg_match("/^[\w-]+$/", $v) or die("invalid: $v");

@header('Content-type: image/jpeg');

$url = "http://img.youtube.com/vi/$v/hqdefault.jpg";

$c = file_get_contents($url);
if ($DO_CACHE) {
	@file_put_contents(dirname(__FILE__) . "/$v.jpg", $c);
}
echo $c;
