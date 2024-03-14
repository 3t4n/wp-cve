<?php

namespace WilokeEmailCreator\Email\Shared;

trait TraitHandleReplaceSubjectEmail
{
	public function getFieldPlaceholderSubjectEmail($aOrderItems = [], $aOrderData = []): array
	{
		$date_format = wc_date_format();
		$aVariable = [
			'{order_date}'            => date($date_format,),
			'{last_name}'             => esc_html__('Doe', 'emailcreator'),
			'{customer_name}'         => esc_html__('John Doe', 'emailcreator'),
			'{customer_phone_number}' => '01027478292',
			'{customer_note}'         => esc_html__('Customer note', 'emailcreator'),
			'{coupon_expire_date}'    => date($date_format, current_time('U') + MONTH_IN_SECONDS),
			'{first_name}'            => esc_html__('John', 'emailcreator'),
			'{order_discount}'        => wc_price(5),
			'{order_fully_refund}'    => wc_price(0),
			'{order_note}'            => esc_html__('Order note', 'emailcreator'),
			'{order_number}'          => 2158,
			'{order_partial_refund}'  => wc_price(0),
			'{order_received_url}'    => wc_get_endpoint_url('order-received', 2158, wc_get_checkout_url()),
			'{order_shipping}'        => wc_price(10),
			'{order_subtotal}'        => wc_price(50),
			'{order_total}'           => wc_price(55),
			'{order_tax}'             => wc_price(5),
			'{payment_method}'        => esc_html__('Paypal', "emailcreator"),
		];
		return wp_parse_args($aVariable, [
			'{admin_email}'           => get_bloginfo('admin_email'),
			'{admin_phone}'           => get_bloginfo('admin_email'),
			'{from_email}'            => sanitize_email(get_option('woocommerce_email_from_address')),
			'{checkout_url}'          => wc_get_checkout_url(),
			'{home_url}'              => home_url(),
			'{myaccount_url}'         => wc_get_page_permalink('myaccount'),
			'{order_date}'            => date($date_format, current_time('U')),
			'{last_name}'             => esc_html__('Doe', "emailcreator"),
			'{customer_name}'         => esc_html__('John Doe', "emailcreator"),
			'{customer_phone_number}' => '01027478292',
			'{customer_note}'         => esc_html__('Customer note', "emailcreator"),
			'{coupon_expire_date}'    => date($date_format, current_time('U') + MONTH_IN_SECONDS),
			'{first_name}'            => esc_html__('John', "emailcreator"),
			'{order_discount}'        => wc_price(5),
			'{order_fully_refund}'    => wc_price(0),
			'{order_note}'            => esc_html__('Order note', "emailcreator"),
			'{order_number}'          => 2158,
			'{order_partial_refund}'  => wc_price(0),
			'{order_received_url}'    => wc_get_endpoint_url('order-received', 2158, wc_get_checkout_url()),
			'{order_shipping}'        => wc_price(10),
			'{order_subtotal}'        => wc_price(50),
			'{order_total}'           => wc_price(55),
			'{order_tax}'             => wc_price(5),
			'{payment_method}'        => esc_html__('Paypal', "emailcreator"),
			'{payment_url}'           => wc_get_endpoint_url('order-pay', 2158, wc_get_checkout_url()) .
				'?pay_for_order=true&key=wc_order_6D6P8tQ0N',
			'{set_password_url}'      => wc_get_endpoint_url('lost-password',
				'?action=newaccount&key=N52psnY51Inm0yE3OdxL&login=johndoe', wc_get_page_permalink('myaccount')),
			'{reset_password_url}'    => wc_get_endpoint_url('lost-password', '?key=N52psnY51Inm0yE3OdxL',
				wc_get_page_permalink('myaccount')),
			'{site_title}'            => get_bloginfo('name'),
			'{shipping_method}'       => esc_html__('Flat rate', "emailcreator"),
			'{shop_url}'              => wc_get_endpoint_url('shop'),
			'{user_login}'            => esc_html__('johnDoe', "emailcreator"),
			'{user_password}'         => 'KG&Q#ToW&kLq0owvLWq4Ck',
			'{user_email}'            => 'johndoe@domain.com',
			'{current_year}'          => date('Y', current_time('U')),
		]);
	}

	public function handleReplaceSubjectEmail(string $subject, array $aRawVariable = []): string
	{
		$aVariable = $this->getFieldPlaceholderSubjectEmail($aRawVariable);
		$subject = str_replace(array_keys($aVariable), array_values($aVariable), $subject);
		return htmlspecialchars_decode($subject);
	}
}

