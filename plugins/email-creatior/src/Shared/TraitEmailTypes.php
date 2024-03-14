<?php

namespace WilokeEmailCreator\Shared;

use Exception;
use WilokeEmailCreator\Illuminate\Message\MessageFactory;

trait TraitEmailTypes
{
	public function getEmailTypes(): array
	{
		$aDefaultType = [
			[
				"label" => esc_html__("Order on-hold", "emailcreator"),
				"value" => "customer_on_hold_order",
				"type"  => "order"
			],
			[
				"label" => esc_html__("Processing order", "emailcreator"),
				"value" => "customer_processing_order",
				"type"  => "order"
			],
			[
				"label" => esc_html__("Completed order", "emailcreator"),
				"value" => "customer_completed_order",
				"type"  => "order"
			],
			[
				"label" => esc_html__("Refunded order", "emailcreator"),
				"value" => "customer_refunded_order",
				"type"  => "order"
			],
			[
				"label" => esc_html__("Customer invoice / Order details", "emailcreator"),
				"value" => "customer_invoice",
				"type"  => "order"
			],
			[
				"label" => esc_html__("Customer note", "emailcreator"),
				"value" => "customer_note",
				"type"  => "order"
			],
			[
				"label" => esc_html__("New order", "emailcreator"),
				"value" => "new_order",
				"type"  => "order"
			],
			[
				"label" => esc_html__("Cancelled order", "emailcreator"),
				"value" => "cancelled_order",
				"type"  => "order"
			],
			[
				"label" => esc_html__("Failed order", "emailcreator"),
				"value" => "failed_order",
				"type"  => "order"
			],
			[
				"label" => esc_html__("Cart Abandonment", "emailcreator"),
				"value" => "cart_abandonment",
				"type"  => "cart_abandonment"
			],
			[
				"label" => esc_html__("New account", "emailcreator"),
				"value" => "customer_new_account",
				"type"  => "new_account"
			],
			[
				"label" => esc_html__("Reset password", "emailcreator"),
				"value" => "customer_reset_password",
				"type"  => "reset_password"
			]
		];
		return apply_filters(WILOKE_EMAIL_CREATOR_HOOK_PREFIX . 'src/Shared/TraitEmailTypes/addedEmailType',
			$aDefaultType);
	}
}
