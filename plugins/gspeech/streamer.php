<?php

define('ABSPATH',1);

// no direct access!
defined('ABSPATH') or die("No direct access");

$txt = $_GET['q'];
$txt = strip_tags($txt);
$txt = preg_replace('/<script\b[^>]*>(.*?)<\/script>/si', "", $txt);
$txt = preg_replace('/<style\b[^>]*>(.*?)<\/style>/si', "", $txt);
$txt = str_replace(array("\"","'"),"",$txt);
$txt = str_replace("&nbsp;","",$txt);
$txt = urlencode($txt);

$lang = (string)$_GET['l'];
$token = (string)$_GET['token'];
if($_GET['tr_tool'] == 'g') {
    $type = 'audio/mpeg';
    // $url = 'http://translate.google.com/translate_tts?ie=UTF-8&q=' . $txt . '&tl=' . $lang . '&client=t';
    $url = 'http://translate.google.com/translate_tts?ie=UTF-8&q='.$txt.'&tl=en&tk='.$token .'&client=t';
    $url = 'http://translate.google.com/translate_tts?ie=UTF-8&q='.$txt.'&tl='.$lang.'&total=1&idx=0&textlen=5&tk='.$token .'&client=tw-ob&prev=input&ttsspeed=1';

}


// $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
$agent = 'stagefright/1.2 (Linux;Android 5.0)';
$ch = curl_init ($url) ;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1) ;
curl_setopt($ch, CURLOPT_REFERER, 'http://translate.google.com/');
curl_setopt($ch, CURLOPT_USERAGENT, $agent);
$media = curl_exec($ch) ;
curl_close($ch) ;

$content_length = strlen($media);

header("Content-type: ".$type);
header("Content-length: ".$content_length);
echo $media;
?>