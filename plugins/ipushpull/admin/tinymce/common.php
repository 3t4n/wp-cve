<?php
$url = isset($_GET['url']) ? $_GET['url'] : '';
if ($url) {
    $scheme = parse_url($url, PHP_URL_SCHEME);
    $parse = parse_url($url);
    $parse['host'];
    $segs = explode('/', trim($parse['path'], '/'));
    $path = '';
    for ($i = count($segs) - 1; $i >= 0; $i--) {
        if ($segs[$i] != 'wp-content') {
            unset($segs[$i]);
            continue;
        }
        unset($segs[$i]);
        $path = $segs ? '/'.implode('/', $segs) : '';
        break;
    }
    $url = $scheme . '://' . $parse['host'] . $path ;
}