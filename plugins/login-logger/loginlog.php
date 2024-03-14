<?php
/*
Plugin Name: Login Logger
Plugin URI:
Description: Logs the most recent successful login for each user, as well as all unsuccessful logins
Version: 1.2.1
Author: Stephen Merriman
Author URI: http://www.cre8d-design.com

		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation; either version 2 of the License, or
		(at your option) any later version.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA	02111-1307	USA
*/
$loginlog_db_version = "1.2";

function loginlog_install()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "loginlog";
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
	if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {
		$sql = "CREATE TABLE " . $table_name . " (
			id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			username varchar(60) NOT NULL,
			time datetime NOT NULL,
			ip varchar(20) NOT NULL,
			success char(1) NOT NULL,
			active datetime NOT NULL
		)";
		dbDelta($sql);
		add_option("loginlog_db_version",$loginlog_db_version);
	}
}

function loginlog_loginfailed($user_login) {
	global $wpdb;
	$table_name = $wpdb->prefix . "loginlog";
	$insert = $wpdb->prepare("INSERT INTO ".$table_name." (username,time,active,ip,success) VALUES (%s,%s,%s,%s,'0')",$user_login,current_time('mysql'),current_time('mysql'),$_SERVER['REMOTE_ADDR']);
	$wpdb->query($insert);
}
function loginlog_loginsuccess($user_login) {
	global $wpdb;
	$table_name = $wpdb->prefix . "loginlog";
	$delete = $wpdb->prepare("DELETE FROM ".$table_name." WHERE username=%s AND success='1'",$user_login);
	$wpdb->query($delete);
	$insert = $wpdb->prepare("INSERT INTO ".$table_name." (username,time,active,ip,success) VALUES (%s,%s,%s,%s,'1')",$user_login,current_time('mysql'),current_time('mysql'),$_SERVER['REMOTE_ADDR']);
	$wpdb->query($insert);
}

function loginlog_users()
{
	global $wp_version;
	add_submenu_page('users.php','Login logs','Login logs',8,'login-logger/manage.php');
}
function loginlog_active() {
	global $user_login, $wpdb;
	$table_name = $wpdb->prefix."loginlog";
	$update = $wpdb->prepare("UPDATE ".$table_name." SET active = %s WHERE username=%s",current_time('mysql'),$user_login);
	$wpdb->query($update);
}

global $wp_version;

add_action('wp_login_failed','loginlog_loginfailed',10,1);
add_action('wp_login','loginlog_loginsuccess',10,1);

add_action('admin_menu','loginlog_users');
add_action('activate_login-logger/loginlog.php','loginlog_install');
add_action('init','loginlog_active');
?>