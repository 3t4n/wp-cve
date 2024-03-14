<?php
if ( ! defined( 'ABSPATH' ) ) exit;
global $wpdb;


	$html=get_option('xyz_lbx_html');


$pattern1='/<\!DOCTYPE(.*?)>(.*)/is';
if(!preg_match($pattern1,$html))
{
	$html='<!DOCTYPE HTML>'.$html;//<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
}
$pattern2='/<\!DOCTYPE(.*?)>(.*?)<html(.*?)>(.*?)<\/html>/is';
if(!preg_match($pattern2,$html))
{
$html=preg_replace('/<\!DOCTYPE(.*?)>(.*)/is','<!DOCTYPE$1><html>$2</html>',$html);	
}
$pattern3='/(.?)<html(.*?)>(.*?)<head>(.*?)<\/head>(.*?)<\/html>/is';
if(!preg_match($pattern3,$html))
{
	$html=preg_replace('/(.*?)<html(.*?)>(.*?)<\/html>/is','$1<html$2><head></head>$3</html>',$html);	
}
// else {
// 	$html=preg_replace('/(.*?)<html(.*?)>(.*?)<head>(.*?)<\/head>(.*?)<\/html>/is','$1<html$2>$3<head>$4</head>$5</html>',$html);
// }
$pattern4='/(.*?)<\/head>(.*?)<body(.*?)>(.*?)<\/body>(.*)/is';
if(!preg_match($pattern4,$html))
{
	$html=preg_replace('/(.*?)<\/head>(.*?)<\/html>/is','$1</head><body>$2</body></html>',$html);	
}
preg_match('/(.*?)<head>(.*?)<\/head>(.*?)<body(.*?)>(.*?)<\/body>(.*)/is',$html,$matches);

echo $matches[1].'<head><meta charset="'.get_bloginfo( 'charset' ).'"/>';
wp_head();
echo $matches[2].'<style type="text/css">html,* html body {padding:0 !important;margin: 0 !important;}</style></head>'.$matches[3].'<body'.$matches[4].'>';
echo do_shortcode($matches[5]);
echo '</body>';
echo $matches[6];
// echo '</html>';

	
	


?>