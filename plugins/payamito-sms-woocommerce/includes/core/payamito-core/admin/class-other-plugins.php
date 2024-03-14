<?php
if ( ! class_exists( "Payamito_Other_Plugins" ) ) {
	class Payamito_Other_Plugins
	{
		private static $instance = null;

		private function __construct() {}

		public function get_plugins()
		{
			$plugins = [
				[
					'name'        => __( 'پیامک ووکامرس', 'payamito' ),
					'description' => 'ارسال پیامک در تمامی اکشن ها برای مدیر ، فروشنده ، کاربر و همچنین قابلیت فوق حرفه ای سبد خرید فراموش شده برای افزایش چشم گیر فروش سایت',
					'url'         => 'https://payamito.com/lab-plugins/',
					'image'       => PAYAMITO_URL . "/assets/images/puzzle.png",
				],
				[
					'name'        => __( 'پیامک گراویتی فرم', 'payamito' ),
					'description' => 'تمامی قسمت ها در گراویتی فرم از قابلیت پیامک برخوردار می شود.حتی امکان احراز شماره کاربر در فرم ها',
					'url'         => 'https://payamito.com/lab-plugins/',
					'image'       => PAYAMITO_URL . "/assets/images/puzzle.png",
				],

				[
					'name'        => __( 'پیامک افزونه تیکت', 'payamito' ),
					'description' => 'امکان ارسال پیامک برای تمامی حالات تیکت ها به پشتیبانان و کاربران و حتی مدیر سایت ، امکان احراز شماره کاربر در هنگام ارسال تیکت ',
					'url'         => 'https://payamito.com/lab-plugins/',
					'image'       => PAYAMITO_URL . "/assets/images/puzzle.png",
				],
				[
					'name'        => __( 'پیامک ایزی دیجیتال دانلود EDD', 'payamito' ),
					'description' => 'ارسال اتوماتیک پیامک برای وضعیت های سفارشات به مدیر سایت و کاربر ، امکان احراز شماره همراه کاربر در مرحله فرم صورتحساب',
					'url'         => 'https://payamito.com/lab-plugins/',
					'image'       => PAYAMITO_URL . "/assets/images/puzzle.png",
				],
				[
					'name'        => __( 'پیامک آلتیمیت ممبر', 'payamito' ),
					'description' => 'ثبت نام و ورود کاربر با امکان شماره موبایل ، ورود یکبار مصرف و ...',
					'url'         => 'https://payamito.com/lab-plugins/',
					'image'       => PAYAMITO_URL . "/assets/images/puzzle.png",
				],
				[
					'name'        => __( 'پیامک دکان', 'payamito' ),
					'description' => 'احراز هویت فروشندگان با امکان تایید شماره تلفن',
					'url'         => 'https://payamito.com/lab-plugins/',
					'image'       => PAYAMITO_URL . "/assets/images/puzzle.png",
				],
			];

			return $plugins;
		}

		public function get_arr( $array, $index, $default = null )
		{
			return $array[ $index ] ?? $default;
		}

		public static function getInstance()
		{
			if ( self::$instance == null ) {
				self::$instance = new Payamito_Other_Plugins();
			}

			return self::$instance;
		}
	}
}

if ( ! function_exists( 'payamito_other_plugins' ) ) {
	function payamito_other_plugins()
	{
		return Payamito_Other_Plugins::getInstance();
	}
}
payamito_other_plugins();