<?php
/*
Plugin Name: Hacklog Remote Image Autosave
Version: 2.1.0
Plugin URI: http://ihacklog.com/?p=5087
Description: save remote images in the posts to local server and add it as an attachment to the post.
Author: 荒野无灯
Author URI: http://ihacklog.com
*/

/**
 * @package Hacklog Remote Image Autosave
 * @encoding UTF-8
 * @author 荒野无灯 <HuangYeWuDeng>
 * @link http://ihacklog.com
 * @copyright Copyright (C) 2012 荒野无灯
 * @license http://www.gnu.org/licenses/
 */

/*
 Copyright 2016  荒野无灯

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
defined('ABSPATH') || die('No direct access!');

if( is_admin() )
{	
define ( 'HACKLOG_RIA_LOADER', __FILE__ );
require plugin_dir_path ( __FILE__ ) . '/hacklog-remote-image-autosave.php';
//ok,let's go,have fun-_-
hacklog_remote_image_autosave::init();
}
