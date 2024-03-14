<?php
/**
 * Plugin Name: Magic Login Mail
 * Description: Enter your email address, and send you an email with a magic link to login without a password.
 * Version: 1.06
 * Author: Katsushi Kawamori
 * Author URI: https://riverforest-wp.info/
 * Text Domain: magic-login-mail
 *
 * @package Magic Login Mail
 */

/*
	Copyright (c) 2021- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if ( ! class_exists( 'MagicLoginMail' ) ) {
	require_once __DIR__ . '/lib/class-magicloginmail.php';
}
