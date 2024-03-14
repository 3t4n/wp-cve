<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

 if ( ! defined( 'ABSPATH' ) ) { exit; } require_once WP_FS__DIR_INCLUDES . '/class-fs-lock.php'; class FS_User_Lock { private $_lock; private static $_instance; static function instance() { if ( ! isset( self::$_instance ) ) { self::$_instance = new self(); } return self::$_instance; } private function __construct() { $current_user_id = Freemius::get_current_wp_user_id(); $this->_lock = new FS_Lock( "locked_{$current_user_id}" ); } function try_lock( $expiration = 0 ) { return $this->_lock->try_lock( $expiration ); } function lock( $expiration = 0 ) { $this->_lock->lock( $expiration ); } function unlock() { $this->_lock->unlock(); } }