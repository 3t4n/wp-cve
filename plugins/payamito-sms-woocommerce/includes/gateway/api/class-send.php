<?php

namespace Payamito\Woocommerce\Send;

use Payamito_Woocommerce;

/**
 *  Class Gateways Payamito
 *
 * @since   1.0.0
 */
defined( 'ABSPATH' ) || exit;

class Send
{

	protected static $instance = null;
	public           $success;

	public function __construct() {}

	/**
	 * Start the Class when called
	 *
	 * @since   1.0.0
	 */
	public static function get_instance()
	{
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public $OTP;

	public $phone_number;

	/**
	 * Respanse gateway message
	 *     * @param int $r param
	 */
	public function Message( $r )
	{
		if ( $r === true ) {
			return __( 'success', 'payamito-woocommerce' );
		}
		$r = intval( $r );

		$messages = [
			12  => "مدارک کاربر کامل نمی باشد",
			11  => ".ارسال نشده",
			10  => "کاربرمورد نظرفعال نمی باشد.",
			7   => "متن حاوی کلمه فیلتر شده می باشد، با واحد اداری تماس بگیرید",
			6   => "سامانه در حال بروزرسانی می باشد.",
			5   => "شماره فرستنده معتبرنمی باشد",
			4   => "محدودیت در حجم ارسال",
			3   => "حدودیت در ارسال روزانه",
			2   => ":اعتبار کافی نمی باشد",
			1   => "درخواست با موفقیت انجام شد",
			0   => "نام کاربری یا رمز عبور صحیح نمی باشد",
			- 1 => "دسترسی برای استفاده از این وبسرویس غیرفعال است، با پشتیبانی تماس بگیرید.",
			- 2 => "محدودیت تعداد شماره، محدودیت هر بار ارسال 1 شماره موبایل می باشد",
			- 3 => "خط ارسالی در سیستم تعریف نشده است، با پشتیبانی سامانه تماس بگیرید.",
			- 4 => "کد متن ارسالی صحیح نمی باشد و یا توسط مدیر سامانه تایید نشده است.",
			- 5 => "تن ارسالی با توجه به متغیر های مشخص شده در متن پیشفرض همخوانی ندارد",
			- 6 => "خطای داخلی رخ داده است با پشتیبانی تماس بگیرید",
			- 7 => "خطایی در شماره فرستنده رخ داده است با پشتیبانی تماس بگیرید",
			- 100,
			'حساب شما امکان ارسال بدون الگو  را ندارد',
		];

		foreach ( $messages as $index => $m ) {
			if ( $index == $r ) {
				return $m;
			}
		}

		return __( "Not Fount Message", "payamito-woocommerce" );
	}

	/**
	 * get gateway name
	 */
	public function getName()
	{
		return "Payamito";
	}

	/**
	 * Send sms
	 */
	public function Send_pattern( $toNum, $messageContent, $pattern_id )
	{
		$result = payamito_send_pattern( $toNum, $messageContent, $pattern_id, Payamito_Woocommerce::$slug );

		if ( $result > 2000 ) {
			$this->success = true;

			$result = true;
		} else {
			$result = json_decode( $result );
		}

		return [ "result" => $result, "message" => $this->Message( $result ) ];
	}

	public function Send( $toNum, $message )
	{
		$result = payamito_send( [ $toNum ], $message, payamito_wc()::$slug );

		$result = json_decode( $result );

		if ( $result == '1' ) {
			$result = true;
		}

		return [ "result" => $result, "message" => $this->Message( $result ) ];
	}
}
