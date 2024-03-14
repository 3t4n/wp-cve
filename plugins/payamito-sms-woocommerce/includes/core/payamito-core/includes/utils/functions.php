<?php

if (!function_exists('payamito_options')) {
	function payamito_options($option = '', $default = '')
	{
		$options = get_option('payamito');

		return (isset($options[$option])) ? $options[$option] : $default;
	}
}

if (!function_exists('payamito_send')) {
	function payamito_send($to, $text, $slug = "undefind")
	{
		$status = false;
		$send   = Payamito_Getway::instance()->send($to, $text);
		if ($send == 1) {
			$status = true;
		} else {
			$status = $send;
		}
		Payamito_DB::insert_sms($to, 2, $slug, $status, serialize($text));

		return $send;
	}
}

if (!function_exists('payamito_send_pattern')) {
	function payamito_send_pattern($to, $text, $bodyid, $slug)
	{
		$send = 11;
		if (!payamito_verify_moblie_number($to)) {
			$to = sprintf(__('Invalid phone number (%s)', 'payamito'), $to);
			Payamito_DB::insert_sms($to, 0, $slug, 11, maybe_serialize($text));
		} else {
			if (empty($text)) {
				$text = __('Empty message', 'payamito');
				Payamito_DB::insert_sms($to, 0, $slug, 11, $text);
			} else {
				$send   = Payamito_Getway::instance()->send_pattern($to, $text, $bodyid);
				if ($send > 10000) {
					$status = true;
				} else {
					$status = $send;
				}
				Payamito_DB::insert_sms($to, 1, $slug, $status, maybe_serialize($text));
			}
		}

		return $send;
	}
}
if (!function_exists('payamito_group_send')) {
	function payamito_group_send(array $to, string $text, $sendernumber = null, $slug = "undefined")
	{
		$status = false;
		$send = 11;
		foreach ($to as $phone_number) {
			if (!payamito_verify_moblie_number($phone_number)) {
				$phone_number = sprintf(__('Invalid phone number (%s)', 'payamito'), $phone_number);
				Payamito_DB::insert_sms($phone_number, 0, $slug, 11, maybe_serialize($text));
			} else {
				if (empty($text)) {
					$text = __('Empty message', 'payamito');
					Payamito_DB::insert_sms($phone_number, 0, $slug, 11, maybe_serialize($text));
				} else {
					$send = Payamito_Getway::instance()->send($phone_number, $text, $sendernumber);

					if ($send === 1) {
						$status = true;
					} else {
						$status = $send;
					}
					Payamito_DB::insert_sms($phone_number, 2, $slug, $status, maybe_serialize($text));
				}
			}
		}



		return $send;
	}
}
/**
 * Get crediet
 *
 * @return string
 * @since 1.1.3
 */
if (!function_exists('payamito_get_crediet')) {
	function payamito_get_crediet()
	{
		return Payamito_Getway::instance()->crediet();
	}
}

/**
 * Convert to english number
 *
 * @return int
 * @since 1.1.1
 */
if (!function_exists('payamito_to_english_number')) {
	function payamito_to_english_number($string)
	{
		$persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
		$english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

		return str_replace($persian, $english, $string);
	}
}

/**
 * Respanse gateway message
 *     * @param $response param
 */
if (!function_exists('payamito_code_to_message')) {
	function payamito_code_to_message($response)
	{
		if ($response === true) {
			return __('success', 'payamito-woocommerce');
		}
		$response = intval($response);

		$messages = [
			12     => "مدارک کاربر کامل نمی باشد",
			11     => ".ارسال نشده",
			10     => "کاربرمورد نظرفعال نمی باشد.",
			7      => "متن حاوی کلمه فیلتر شده می باشد، با واحد اداری تماس بگیرید",
			6      => "سامانه در حال بروزرسانی می باشد.",
			5      => "شماره فرستنده معتبرنمی باشد",
			4      => "محدودیت در حجم ارسال",
			3      => "حدودیت در ارسال روزانه",
			2      => ":اعتبار کافی نمی باشد",
			1      => "درخواست با موفقیت انجام شد",
			0      => "نام کاربری یا رمز عبور صحیح نمی باشد",
			-1    => "دسترسی برای استفاده از این وبسرویس غیرفعال است، با پشتیبانی تماس بگیرید.",
			-2    => "محدودیت تعداد شماره، محدودیت هر بار ارسال 1 شماره موبایل می باشد",
			-3    => "خط ارسالی در سیستم تعریف نشده است، با پشتیبانی سامانه تماس بگیرید.",
			-4    => "کد متن ارسالی صحیح نمی باشد و یا توسط مدیر سامانه تایید نشده است.",
			-5    => "متن ارسالی با توجه به متغیر های مشخص شده در متن پیشفرض همخوانی ندارد",
			-6    => "خطای داخلی رخ داده است با پشتیبانی تماس بگیرید",
			-7    => "خطایی در شماره فرستنده رخ داده است با پشتیبانی تماس بگیرید",
			-100  => 'حساب شما امکان ارسال بدون الگو  را ندارد',
			-1001 => 'ارتباط خود را به اینترنت چک کنید ',
		];

		foreach ($messages as $index => $m) {
			if ($index == $response) {
				return $m;
			}
		}

		return $response;
	}
}

/**
 * verify moblie number
 *Mobile number must start with 09
 *
 * @return mixed
 * @since 1.1.1
 */
if (!function_exists('payamito_verify_moblie_number')) {
	function payamito_verify_moblie_number($mobile)
	{
		return (bool) preg_match('/(98|0|98|0098)?([ ]|-|[()]){0,2}9[0-9]([ ]|-|[()]){0,2}(?:[0-9]([ ]|-|[()]){0,2}){8}/', payamito_to_english_number($mobile));
	}
}

/**
 * What type of request is this?
 *
 * @param string $type admin, ajax, cron or frontend.
 *
 * @return bool
 * @since 1.1.2
 */
if (!function_exists('payamito_is_request')) {
	function payamito_is_request($type)
	{
		switch ($type) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined('DOING_AJAX');
			case 'cron':
				return defined('DOING_CRON');
			case 'frontend':
				return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON');
		}
	}
}

/**
 * Jalali date converter
 *
 * @param string $type admin, ajax, cron or frontend.
 *
 * @return bool
 * @since 1.1.2
 */

if (!function_exists('payamito_jalali_converter')) {
	function payamito_jalali_converter($g_y, $g_m, $g_d, $mod = '')
	{
		$d_4   = $g_y % 4;
		$g_a   = [0, 0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
		$doy_g = $g_a[(int) $g_m] + $g_d;
		if ($d_4 == 0 and $g_m > 2) {
			$doy_g++;
		}
		$d_33 = (int) ((($g_y - 16) % 132) * .0305);
		$a    = ($d_33 == 3 or $d_33 < ($d_4 - 1) or $d_4 == 0) ? 286 : 287;
		$b    = (($d_33 == 1 or $d_33 == 2) and ($d_33 == $d_4 or $d_4 == 1)) ? 78 : (($d_33 == 3 and $d_4 == 0) ? 80 : 79);
		if ((int) (($g_y - 10) / 63) == 30) {
			$a--;
			$b++;
		}
		if ($doy_g > $b) {
			$jy    = $g_y - 621;
			$doy_j = $doy_g - $b;
		} else {
			$jy    = $g_y - 622;
			$doy_j = $doy_g + $a;
		}
		if ($doy_j < 187) {
			$jm = (int) (($doy_j - 1) / 31);
			$jd = $doy_j - (31 * $jm++);
		} else {
			$jm = (int) (($doy_j - 187) / 30);
			$jd = $doy_j - 186 - ($jm * 30);
			$jm += 7;
		}

		$jd = $jd > 9 ? $jd : '0' . $jd;
		$jm = $jm > 9 ? $jm : '0' . $jm;

		return ($mod == '') ? [$jy, $jm, $jd] : $jy . $mod . $jm . $mod . $jd;
	}
}

if (!function_exists('payamito_dynamic_text')) {
	function payamito_dynamic_text($id)
	{
		switch ($id) {
			case "pattern_active_title":
				return esc_html__('Send By Pattern', 'payamito');

			case "pattern_active_desc":
				return esc_html__('By activating this option, you will not need to provide a dedicated service line to send to the blacklist ', 'payamito');

			case "pattern_active_help":
				return esc_html__('To create the pattern, refer to the Paymito panel and the service web service module', 'payamito');

				//------------------

			case "pattern_ID_title":
				return esc_html__('Pattern ID', 'payamito');

			case "pattern_ID_desc":
				return esc_html__('Just enter the code received from the service web service section in the special tools menu in this section', 'payamito');

			case "pattern_ID_help":
				return esc_html__('In the SMS panel, a special tool and a submenu (service web service) are created', 'payamito');

				//------------------

			case "pattern_Variable_title":
				return esc_html__('Variable', 'payamito');

			case "pattern_Variable_desc":
				return esc_html__('You have to create the numbers related to the variable in the service web service section', 'payamito');

			case "pattern_Variable_help":
				return esc_html__('If there is a problem in this section, please contact Payamito Support', 'payamito');

				//------------------

			case "send_content_title":
				return esc_html__('SMS send manual', 'payamito');

			case "send_content_desc":
				return esc_html__('Use of this section is recommended when you have a dedicated service line', 'payamito');

			case "send_content_help":
				return esc_html__('The project of servicing dedicated lines to service lines takes at least 1 to 2 months', 'payamito');
		}

		return '';
	}
}
