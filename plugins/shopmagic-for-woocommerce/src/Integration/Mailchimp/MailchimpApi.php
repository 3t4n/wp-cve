<?php

namespace WPDesk\ShopMagic\Integration\Mailchimp;


use WPDesk\ShopMagic\Customer\UserAsCustomer;

/**
 * MailChimp Tools for ShopMagic
 *
 * @since   1.0.0
 */
interface MailchimpApi {

	public function add_member( MemberParamsBag $member_params ): bool;

	/**
	 * Extract the lists names and id to be used on options for the select element 'List name'
	 *
	 * @return string[]
	 */
	public function get_all_lists_options(): array;
}
