<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( "Payamito_Connection" ) ) {
	class Payamito_Connection
	{

		static $instance;

		public $username;
		public $password;
		public $from;

		private function __construct()
		{
			$this->username = payamito_options( 'username' );
			$this->password = payamito_options( 'password' );
			$this->from     = payamito_options( 'SMS_line_number' );
		}

		public static function instance()
		{
			$class = static::class;

			if ( ! isset( self::$instance[ $class ] ) ) {
				self::$instance[ $class ] = new $class();
			}

			return self::$instance[ $class ];
		}

	}
}
