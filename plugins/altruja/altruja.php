<?php

/**
 * @package Altruja
 */
/*
Plugin Name: Altruja
Plugin URI: https://altruja.de/
Description: Einfach. Online. Spenden sammeln. Altruja is a fast, simple and trustworthy way to collect donations online.
Version: 1.0.7
Author: Altruja
Author URI: https://altruja.de
License: GPLv2 or later
Text Domain: altruja
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


//add_action( 'init', array( 'Altruja', 'main' ) );
require (dirname(__FILE__).'/main.php');
require (dirname(__FILE__).'/admin.php');

class Altruja {

  protected static $main, $admin;
  public static function main() {
    self::$main = new AltrujaMain();
  }

  public static function admin() {
    self::$admin = new AltrujaAdmin();
  }
}

Altruja::main();
Altruja::admin();

