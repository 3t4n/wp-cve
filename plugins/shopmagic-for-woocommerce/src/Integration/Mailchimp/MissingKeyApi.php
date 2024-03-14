<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Integration\Mailchimp;

/**
 * Special case object served when MailChimp integration is missing a valid API key.
 */
class MissingKeyApi implements MailchimpApi {

	public function add_member( MemberParamsBag $member_params ): bool {
		return false;
	}

	public function get_all_lists_options(): array {
		return [
			esc_html__( 'Please make sure to provide Mailchimp API key!', 'shopmagic-for-woocommerce' ),
		];
	}
}
