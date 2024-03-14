<?php
/* 
Description: Code for TBD
 
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

include STAGESHOW_FILE_PATH.'stageshow_trolley.php';
include 'stageshowlib_jquery_trolley.php';

class StageShowJQueryTrolley extends StageShowLibJQueryTrolley
{
	function __construct($cartObjClassName)
	{
		parent::__construct($cartObjClassName);
	}	
}

new StageShowJQueryTrolley('StageShowCartPluginClass');
