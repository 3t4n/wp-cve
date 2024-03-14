<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Customer;

class NullCustomer implements Customer {
	public function is_guest(): bool {
		return true;
	}

	public function get_id(): string {
		return '';
	}

	public function get_username(): string {
		return '';
	}

	public function get_first_name(): string {
		return '';
	}

	public function get_last_name(): string {
		return '';
	}

	public function get_full_name(): string {
		return '';
	}

	public function get_email(): string {
		return '';
	}

	public function get_phone(): string {
		return '';
	}

	public function get_language(): string {
		return '';
	}

	public function jsonSerialize(): array {
		return [];
	}
}
