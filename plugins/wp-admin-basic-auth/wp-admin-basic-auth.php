<?php
/*
Plugin Name: WP Admin Basic Auth
Plugin URI: http://stocker.jp/diary/wp-admin-basic-auth/
Description: Enabling this plugin allows you to set up Basic authentication on your admin page using your WordPress user name and password. 
Author: Stocker_jp
Version: 1.0
Author URI: http://stocker.jp/

License:
 Released under the GPL license
  http://www.gnu.org/copyleft/gpl.html

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
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function admin_basic_auth(){
	// キャッシュを出力しない
	nocache_headers();
	// 既にログインしている場合はreturn
	if ( is_user_logged_in() ) {
		return;
	}

	// WordPress のユーザー認証で BASIC 認証ユーザー/パスワードをチェック
	$user = isset($_SERVER["PHP_AUTH_USER"]) ? $_SERVER["PHP_AUTH_USER"] : '';
	$pwd  = isset($_SERVER["PHP_AUTH_PW"])   ? $_SERVER["PHP_AUTH_PW"]   : '';
	if ( ! is_wp_error(wp_authenticate($user, $pwd)) ) {
		return;
	}

	// HTTPヘッダーで、BASIC認証を要求
	header('WWW-Authenticate: Basic realm="Please Enter Your Password"');
	header('HTTP/1.0 401 Unauthorized');
	// 認証がキャンセルされたら「Authorization Required」と表示して終了
	die('Authorization Required');
}

// WordPress管理画面へのログインページでHTMLが出力され始める前に admin_basic_auth() 関数を実行
add_action('login_init', 'admin_basic_auth');
