<?php
/*
Plugin Name: Mechanic No Right Click
Plugin URI:  https://www.adityasubawa.com/mechanic-no-right-click
Description: Mechanic - No Right Click previously BMT - No Right Click is a tiny plugin to not allowed right click, CTRL A, CTRL C, CTRL S, CTRL U, CTRL P and F12 function. Protected your contents by disabling mouse and keyboard commands.
Version: 1.1
Author: Aditya Subawa
Author URI: https://www.adityasubawa.com
License: GPLv2 or later
*/

/*
  Copyright 2016 Bali Mechanic Media & Aditya Subawa: http://www.adityasubawa.com

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */
 
/* load local language */
add_action('plugins_loaded', 'bmtnorightclick_load_textdomain');
function bmtnorightclick_load_textdomain() {
	load_plugin_textdomain( 'bmt-norightclick', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}

add_action('wp_head','bmt_norightclick');


function bmt_norightclick() {
   // first of all, i'm ready to disable right click function
	$bmt_output="<script language=JavaScript>
	var message='';
	function clickIE() {if (document.all) {(message);return false;}}
	function clickNS(e) {if 
	(document.layers||(document.getElementById&&!document.all)) {
	if (e.which==2||e.which==3) {(message);return false;}}}
	if (document.layers) 
	{document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS;}
	else{document.onmouseup=clickNS;document.oncontextmenu=clickIE;}
	document.oncontextmenu=new Function('return false')
	</script>";

	echo $bmt_output;
	// now disable F12 function
	$bmt_f12 = "<script language=JavaScript>
	document.onkeypress = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
           //alert('No F-12');
            return false;
        }
    }
    document.onmousedown = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            //alert('No F-keys');
            return false;
        }
    }
	document.onkeydown = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            //alert('No F-keys');
            return false;
        }
    }
	</script>";
	
	echo $bmt_f12;
	// hey, this is code for ctrl A, C, S, U going stuck.. lol 
	$bmt_ctrlc = "<script language='JavaScript'>
    var message='';
	function clickIE() {if (document.all) {(message);return false;}}
	function clickNS(e) {if 
	(document.layers||(document.getElementById&&!document.all)) {
	if (e.which==2||e.which==3) {(message);return false;}}}
	if (document.layers) 
	{document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS;}
	else{document.onmouseup=clickNS;document.oncontextmenu=clickIE;}
	document.oncontextmenu=new Function('return false');
	
    document.onkeypress = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
           //alert('No F-12');
            return false;
        }
    }
    document.onmousedown = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            //alert('No F-keys');
            return false;
        }
    }
	document.onkeydown = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            //alert('No F-keys');
            return false;
        }
    }

	function disableCtrlKeyCombination(e)
	{
	//list all CTRL + key combinations you want to disable
	var forbiddenKeys = new Array('a', 'n', 'c', 'x', 'v', 'j' , 'w','p','u');
	var key;
	var isCtrl;
	if(window.event)
	{
	key = window.event.keyCode;     //IE
	if(window.event.ctrlKey)
	isCtrl = true;
	else
	isCtrl = false;
	}
	else
	{
	key = e.which;     //firefox
	if(e.ctrlKey)
	isCtrl = true;
	else
	isCtrl = false;
	}
	//if ctrl is pressed check if other key is in forbidenKeys array
	if(isCtrl)
	{
	for(i=0; i<forbiddenKeys.length; i++)
	{
	//case-insensitive comparation
	if(forbiddenKeys[i].toLowerCase() == String.fromCharCode(key).toLowerCase())
	{

	return false;
	}
	}
	}
	return true;
	}
	</script>
	</head>
	<body onkeypress='return disableCtrlKeyCombination(event);' onkeydown='return disableCtrlKeyCombination(event);'>";
	
	echo $bmt_ctrlc;
}
