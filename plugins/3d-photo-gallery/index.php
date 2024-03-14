<?php
		/*
	Plugin Name: 3D Photo Gallery
	Description: 3D Photo Gallery is a fully responsive media 3D carousel wordpress plugin that allows you to display media content with an unique original layout from a 3D perspective.
	Plugin URI: http://webdevocean.com/3d-photo-gallery/
	Author: Labib Ahmed
	Author URI: http://webdevocean.com
	Version: 1.3
	License: GPL2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
	Text Domain: la-photogallery
	*/
	
	/*
	
	    Copyright (C) Year  Labib Ahmed  Email labib@najeebmediagroup.com
	
	    This program is free software; you can redistribute it and/or modify
	    it under the terms of the GNU General Public License, version 2, as
	    published by the Free Software Foundation.
	
	    This program is distributed in the hope that it will be useful,
	    but WITHOUT ANY WARRANTY; without even the implied warranty of
	    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	    GNU General Public License for more details.
	
	    You should have received a copy of the GNU General Public License
	    along with this program; if not, write to the Free Software
	    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/

	include_once ('plugin.class.php');
	if (class_exists('LA_Photo_Gallery')) {
		$object = new LA_Photo_Gallery;
	}
 ?>