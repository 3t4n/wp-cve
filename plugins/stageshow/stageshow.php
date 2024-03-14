<?php
/* 
Plugin Name: StageShow
Plugin URI: http://wordpress.org/plugins/stageshow/
Version: 9.8.6
Author: Malcolm Shergold
Author URI: http://corondeck.co.uk
Description: A Wordpress Plugin to sell theatre tickets online
 
Copyright 2020 Malcolm Shergold

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

if (defined('IPN_CALLBACK'))
{
	if (IPN_CALLBACK != 'STAGESHOW_VARIANT')
		return;
}

define('STAGESHOW_CODE_PREFIX', 'stageshow');

include 'stageshow_defs.php';
include 'stageshow_main.php';

new StageShowPluginClass(__FILE__);
		
?>