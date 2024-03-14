<?php

namespace WilokeEmailCreator\Shared;

use WC_Order;
use WilokeEmailCreator\Illuminate\Prefix\AutoPrefix;
use WP_User;

class GetFieldPlaceholderSubjectEmail
{
	public static function getFieldPlaceholder($aArgs): array
	{
		$date_format = function_exists('wc_date_format') ? wc_date_format() : "";
		if (!empty($aArgs)) {
			if ($aArgs instanceof WP_User) {
				$aVariable = [
					'{user_login}'         => $aArgs->user_login,
					'{customer_username}'  => $aArgs->user_login,
					'{customer_name}'      => $aArgs->user_nicename,
					'{user_password}'      => $aArgs->user_pass,
					'{user_email}'         => $aArgs->user_email,
					'{set_password_url}'   => add_query_arg([
						'key'    => get_user_meta($aArgs->ID, AutoPrefix::namePrefix('reset_key'), true),
						'login'  => $aArgs->user_login,
						'action' => 'newaccount',
					], wc_get_page_permalink('myaccount') . '/lost-password/'),
					'{reset_password_url}' => add_query_arg([
						'key' => get_user_meta($aArgs->ID, AutoPrefix::namePrefix('reset_key'), true),
						'id'  => $aArgs->ID
					], wc_get_page_permalink('myaccount') . '/lost-password/'),
				];
			} else {
				if (is_array($aArgs)) {
					return $aArgs;
				}

				/**
				 * @var WC_Order $oWCOrder
				 */
				$oWCOrder = $aArgs;
				$aOrderData = $oWCOrder->get_data();

//				if (isset($aOrderData['id']) && !empty($aOrderData['id'])) {
//					$orderNumber = $aOrderData['id'];
//					if (!is_wp_error($oWCOrder)) {
//						$paymentMethod = $oWCOrder->get_payment_method();
//					} else {
//						$paymentMethod = $aOrderData['payment_method'];
//					}
//				} else {
//					$orderNumber = 0;
//					$paymentMethod = $aOrderData['payment_method'];
//				}

				$aVariable = [
					'{order_date}'               => $oWCOrder->get_date_created()->date_i18n(wc_date_format()),
					'{last_name}'                => $aOrderData['billing']['last_name'],
					'{billing_name}'             => $aOrderData['billing']['first_name'] . ' ' .
						$aOrderData['billing']['last_name'],
					'{billing_phone}'            => $oWCOrder->get_billing_phone(),
					'{billing_email}'            => $oWCOrder->get_billing_email(),
					'{customer_note}'            => $oWCOrder->get_customer_note(),
					'{coupon_expire_date}'       => date($date_format, current_time('U') + MONTH_IN_SECONDS),
					'{first_name}'               => $oWCOrder->get_billing_first_name(),
					'{order_discount}'           => $oWCOrder->get_discount_total(),
					'{order_number}'             => $oWCOrder->get_id(),
					'{order_received_url}'       => add_query_arg('key', $aOrderData['order_key'],
						wc_get_endpoint_url('order-received',
							$aOrderData['id'],
							wc_get_checkout_url())),
					'{order_shipping}'           => $oWCOrder->get_shipping_total(),
					'{order_subtotal}'           => wc_price((float)$aOrderData['total'] +
						(int)$aOrderData['discount_total']),
					'{order_total}'              => $oWCOrder->get_total(),
					'{order_tax}'                => $oWCOrder->get_total_tax(),
					'{payment_url}'              => add_query_arg(
						[
							'pay_for_order' => true,
							'key'           => $aOrderData['order_key']
						],
						wc_get_endpoint_url(
							'order-pay',
							$aOrderData['id'],
							wc_get_checkout_url()
						)
					),
					'{payment_method}'           => $oWCOrder->get_payment_method_title(),
					'{billing_postal_code}'      => $oWCOrder->get_billing_postcode(),
					'{billing_city}'             => $oWCOrder->get_billing_city(),
					'{billing_address1}'         => $oWCOrder->get_billing_address_1(),
					'{billing_address2}'         => $oWCOrder->get_billing_address_2(),
					'{shipping_city}'            => $oWCOrder->get_shipping_city(),
					'{shipping_address_map_url}' => $oWCOrder->get_shipping_address_map_url(),
					'{shipping_country}'         => $oWCOrder->get_shipping_country(),
					'{shipping_method}'          => $oWCOrder->get_shipping_method(),
					'{shipping_postal_code}'     => $oWCOrder->get_shipping_postcode(),
					'{shipping_total}'           => $oWCOrder->get_shipping_total(),
					'{shipping_tax}'             => $oWCOrder->get_shipping_tax(),
					'{shipping_to_display}'      => $oWCOrder->get_shipping_to_display(),
					'{shipping_address1}'        => $oWCOrder->get_billing_address_1(),
					'{shipping_company}'         => $oWCOrder->get_shipping_company(),
					'{shipping_address2}'        => $oWCOrder->get_billing_address_2(),
					'{shipping_phone}'           => $oWCOrder->get_shipping_phone(),
					'{shipping_name}'            => $oWCOrder->get_formatted_shipping_full_name(),
				];
			}

			return wp_parse_args($aVariable, [
				'{admin_email}'              => get_bloginfo('admin_email'),
				'{admin_phone}'              => get_user_meta(get_current_user_id(), 'user_phone', true),
				'{from_email}'               => sanitize_email(get_option('woocommerce_email_from_address')),
				'{checkout_url}'             => wc_get_checkout_url(),
				'{home_url}'                 => home_url(),
				'{myaccount_url}'            => wc_get_page_permalink('myaccount'),
				'{order_date}'               => date($date_format, current_time('U')),
				'{last_name}'                => esc_html__('Doe', "emailcreator"),
				'{customer_name}'            => esc_html__('John Doe', "emailcreator"),
				'{customer_phone_number}'    => '01027478292',
				'{customer_note}'            => esc_html__('Customer note', "emailcreator"),
				'{coupon_expire_date}'       => date($date_format, current_time('U') + MONTH_IN_SECONDS),
				'{first_name}'               => esc_html__('John', "emailcreator"),
				'{order_discount}'           => wc_price(5),
				'{order_fully_refund}'       => wc_price(0),
				'{order_note}'               => esc_html__('Order note', "emailcreator"),
				'{order_number}'             => 2158,
				'{order_partial_refund}'     => wc_price(0),
				'{order_received_url}'       => wc_get_endpoint_url('order-received', 2158, wc_get_checkout_url()),
				'{order_shipping}'           => wc_price(10),
				'{order_subtotal}'           => wc_price(50),
				'{order_total}'              => wc_price(55),
				'{order_tax}'                => wc_price(5),
				'{payment_method}'           => esc_html__('Paypal', "emailcreator"),
				'{payment_url}'              => wc_get_endpoint_url('order-pay', 2158, wc_get_checkout_url()) .
					'?pay_for_order=true&key=wc_order_6D6P8tQ0N',
				'{set_password_url}'         => wc_get_endpoint_url('lost-password',
					'?action=newaccount&key=N52psnY51Inm0yE3OdxL&login=johndoe', wc_get_page_permalink('myaccount')),
				'{reset_password_url}'       => wc_get_endpoint_url('lost-password', '?key=N52psnY51Inm0yE3OdxL',
					wc_get_page_permalink('myaccount')),
				'{site_title}'               => get_bloginfo('name'),
				'{shop_url}'                 => wc_get_endpoint_url('shop'),
				'{user_login}'               => esc_html__('johnDoe', "emailcreator"),
				'{user_password}'            => 'KG&Q#ToW&kLq0owvLWq4Ck',
				'{user_email}'               => 'johndoe@domain.com',
				'{current_year}'             => date('Y', current_time('U')),
				'{billing_phone}'            => '+1 909 980-1034',
				'{billing_city}'             => 'Hanoi',
				'{billing_postal_code}'      => '+123',
				'{billing_email}'            => 'billingemailsample@gmail.com',
				'{shipping_method}'          => esc_html__('Flat rate', "emailcreator"),
				'{billing_address1}'         => '195 Hancock Avenue,Center,Utah - UT - 27401',
				'{billing_address2}'         => '195 Hancock Avenue,Center,Utah - UT - 27401',
				'{shipping_address1}'        => '741 Somerset Road,Stanley,South Dakota - SD - 71349',
				'{shipping_address2}'        => '741 Somerset Road,Stanley,South Dakota - SD - 71349',
				'{shipping_city}'            => 'Hanoi',
				'{shipping_country}'         => "Vietnam",
				'{shipping_postal_code}'     => '123',
				'{shipping_to_display}'      => 'Shipping To Display',
				'{shipping_phone}'           => '(439)-181-8191',
				'{shipping_name}'            => 'Loni Alsayed',
				'{shipping_company}'         => 'EmailCreator',
				'{shipping_total}'           => wc_price("10"),
				'{shipping_tax}'             => wc_price("1"),
				'{shipping_address_map_url}' => "Address Map Here"
			]);
		}

		return [
			'order'   => [
				'admin_email'              => get_bloginfo('admin_email'),
				'admin_phone'              => get_user_meta(get_current_user_id(), 'user_phone', true) ?: '0123456789',
				'from_email'               => sanitize_email(get_option('woocommerce_email_from_address')),
				'checkout_url'             => function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : '',
				'home_url'                 => home_url(),
				'myaccount_url'            => function_exists('wc_get_page_permalink') ?
					wc_get_page_permalink('myaccount') : '',
				'order_date'               => date($date_format, current_time('U')),
				'last_name'                => esc_html__('Doe', "emailcreator"),
				'customer_name'            => esc_html__('John Doe', "emailcreator"),
				'customer_phone_number'    => '01027478292',
				'customer_note'            => esc_html__('Customer note', "emailcreator"),
				'coupon_expire_date'       => date($date_format, current_time('U') + MONTH_IN_SECONDS),
				'first_name'               => esc_html__('John', "emailcreator"),
				'order_discount'           => wc_price(5),
				'order_fully_refund'       => wc_price(0),
				'order_note'               => esc_html__('Order note', "emailcreator"),
				'order_number'             => 2158,
				'order_partial_refund'     => wc_price(0),
				'order_received_url'       => wc_get_endpoint_url('order-received', 2158, wc_get_checkout_url()),
				'order_shipping'           => wc_price(10),
				'order_subtotal'           => wc_price(50),
				'order_total'              => wc_price(55),
				'order_tax'                => wc_price(5),
				'payment_method'           => esc_html__('Paypal', "emailcreator"),
				'payment_url'              => wc_get_endpoint_url('order-pay', 2158, wc_get_checkout_url()) .
					'?pay_for_order=true&key=wc_order_6D6P8tQ0N',
				'site_title'               => get_bloginfo('name'),
				'shipping_method'          => esc_html__('Flat rate', "emailcreator"),
				'shop_url'                 => wc_get_endpoint_url('shop'),
				'current_year'             => date('Y', current_time('U')),
				'billing_postal_code'      => '+123',
				'billing_phone'            => '+1 909 980-1034',
				'billing_email'            => 'billingemailsample@gmail.com',
				'billing_city'             => 'Hanoi',
				'billing_name'             => esc_html__('John Doe', "emailcreator"),
				'billing_address1'         => '195 Hancock Avenue,Center,Utah - UT - 27401',
				'billing_address2'         => '195 Hancock Avenue,Center,Utah - UT - 27401',
				'shipping_address1'        => '741 Somerset Road,Stanley,South Dakota - SD - 71349',
				'shipping_address2'        => '741 Somerset Road,Stanley,South Dakota - SD - 71349',
				'shipping_phone'           => '(439)-181-8191',
				'shipping_city'            => 'Hanoi',
				'shipping_email'           => 'loni.alsayed@example.com',
				'shipping_name'            => 'Loni Alsayed',
				'shipping_company'         => 'Email Creator',
				'shipping_postal_code'     => "123",
				'shipping_to_display'      => "Shipping To Display",
				'shipping_tax'             => wc_price(1),
				'shipping_total'           => wc_price(10),
				'shipping_address_map_url' => "Address Map Here",
				'shipping_country'         => "Vietnam"
			],
			'account' => [
				'admin_email'        => get_bloginfo('admin_email'),
				'admin_phone'        => get_user_meta(get_current_user_id(), 'user_phone', true),
				'from_email'         => sanitize_email(get_option('woocommerce_email_from_address')),
				'checkout_url'       => wc_get_checkout_url(),
				'home_url'           => home_url(),
				'myaccount_url'      => wc_get_page_permalink('myaccount'),
				'set_password_url'   => wc_get_endpoint_url('lost-password',
					'?action=newaccount&key=N52psnY51Inm0yE3OdxL&login=johndoe', wc_get_page_permalink('myaccount')),
				'reset_password_url' => wc_get_endpoint_url('lost-password', '?key=N52psnY51Inm0yE3OdxL',
					wc_get_page_permalink('myaccount')),
				'site_title'         => get_bloginfo('name'),
				'shop_url'           => wc_get_endpoint_url('shop'),
				'user_login'         => esc_html__('johnDoe', "emailcreator"),
				'customer_username'  => esc_html__('johnDoe', "emailcreator"),
				'user_password'      => 'KG&Q#ToW&kLq0owvLWq4Ck',
				'user_email'         => 'johndoe@domain.com',
				'current_year'       => date('Y', current_time('U')),
			]
		];
	}

	public static function getFieldPlaceholderCaseUserGuest($orderTotal, $name): array
	{
		return [
			'{admin_email}'              => get_bloginfo('admin_email'),
			'{admin_phone}'              => get_user_meta(get_current_user_id(), 'user_phone', true),
			'{from_email}'               => sanitize_email(get_option('woocommerce_email_from_address')),
			'{checkout_url}'             => function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : '',
			'{home_url}'                 => home_url(),
			'{myaccount_url}'            => function_exists('wc_get_page_permalink') ?
				wc_get_page_permalink('myaccount') : '',
			'{last_name}'                => esc_html__('Doe', 'emailcreator'),
			'{customer_name}'            => esc_html__('John Doe', "emailcreator"),
			'{customer_phone_number}'    => '01027478292',
			'{customer_note}'            => esc_html__('Customer note', "emailcreator"),
			'{first_name}'               => esc_html__('John', "emailcreator"),
			'{order_discount}'           => wc_price(5),
			'{order_fully_refund}'       => wc_price(0),
			'{order_note}'               => esc_html__('Order note', "emailcreator"),
			'{order_number}'             => 2158,
			'{order_partial_refund}'     => wc_price(0),
			'{order_received_url}'       => wc_get_endpoint_url('order-received', 2158, wc_get_checkout_url()),
			'{order_shipping}'           => wc_price(0),
			'{order_subtotal}'           => wc_price($orderTotal),
			'{order_total}'              => wc_price($orderTotal),
			'{order_tax}'                => wc_price(5),
			'{order_date}'               => date('M d, Y', time()),
			'{payment_method}'           => esc_html__('', "emailcreator"),
			'{payment_url}'              => wc_get_endpoint_url('order-pay', 2158, wc_get_checkout_url()) .
				'?pay_for_order=true&key=wc_order_6D6P8tQ0N',
			'{site_title}'               => get_bloginfo('name'),
			'{shipping_method}'          => esc_html__('Flat rate', "emailcreator"),
			'{shop_url}'                 => wc_get_endpoint_url('shop'),
			'{current_year}'             => date('Y', current_time('U')),
			'{billing_phone}'            => '+1 909 980-1034',
			'{billing_email}'            => 'billingemailsample@gmail.com',
			'{billing_postal_code}'      => '123 456',
			'{billing_city}'             => 'Hanoi',
			'{billing_address1}'         => '195 Hancock Avenue,Center,Utah - UT - 27401',
			'{billing_address2}'         => '195 Hancock Avenue,Center,Utah - UT - 27401',
			'{shipping_address1}'        => '741 Somerset Road,Stanley,South Dakota - SD - 71349',
			'{shipping_address2}'        => '741 Somerset Road,Stanley,South Dakota - SD - 71349',
			'{shipping_phone}'           => '(439)-181-8191',
			'{shipping_email}'           => 'loni.alsayed@example.com',
			'{shipping_name}'            => 'Loni Alsayed',
			'{shipping_city}'            => 'Loni Alsayed',
			'{shipping_company}'         => 'Email Creator',
			'{billing_name}'             => $name,
			'{shipping_postal_code}'     => "123",
			'{shipping_to_display}'      => "Shipping To Display",
			'{shipping_tax}'             => wc_price(1),
			'{shipping_total}'           => wc_price(10),
			'{shipping_country}'         => "Vietnam",
			'{shipping_address_map_url}' => "Address Map Here"
		];
	}
}
