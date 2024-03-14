<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Customer;

/**
 * Customer data access unification.
 *
 * Customer is special case in system, because it's unification between WordPress user and
 * ShopMagic guest. It's not possible to create new customer in WordPress, but it's possible
 * to create new guest in ShopMagic.
 * Thus, construct of Customer is readonly.
 *
 * @todo Refine comment.
 */
interface Customer {
	public const USER_LANGUAGE_META = 'shopmagic_user_language';

	public function is_guest(): bool;

	public function get_id(): string;

	public function get_username(): string;

	public function get_first_name(): string;

	public function get_last_name(): string;

	public function get_full_name(): string;

	public function get_email(): string;

	public function get_phone(): string;

	public function get_language(): string;
}
