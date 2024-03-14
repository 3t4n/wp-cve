// JavaScript Document

//set no conflict value
var $j = jQuery.noConflict();

//after document ready
//$j(document).ready(function(){ code here });

//set up tabs
$j(function() {
	// setup ul in gp-tabbed-area to work as tabs for each div included in this div
	$j("#gp-tabbed-area").tabs();
	$j("#gp-tabbed-area").tabs({active: 0});
});