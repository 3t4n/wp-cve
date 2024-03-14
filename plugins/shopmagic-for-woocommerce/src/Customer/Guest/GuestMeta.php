<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Guest;

final class GuestMeta {

	/** @var int */
	public $guest_id;
	/** @var string */
	public $meta_key;
	/** @var string */
	public $meta_value;
	/** @var int|null */
	private $meta_id;

	public function __construct(
		string $meta_key = null,
		string $meta_value = null
	) {
		$this->meta_key = $meta_key;
		$this->meta_value = $meta_value;
	}

	/**
	 * This method allows us to easily use array_unique on collections of GuestMeta.
	 */
	public function __toString() {
		return $this->meta_key . ': ' . $this->meta_value;
	}

	public function get_meta_id(): ?int {
		return $this->meta_id;
	}

	public function set_meta_id( int $meta_id ): void {
		$this->meta_id = $meta_id;
	}

	public function get_guest_id(): int {
		return $this->guest_id;
	}

	public function set_guest_id( int $guest_id ): void {
		$this->guest_id = $guest_id;
	}

	public function get_meta_key(): string {
		return $this->meta_key;
	}

	public function set_meta_key( string $meta_key ): void {
		$this->meta_key = $meta_key;
	}

	public function get_meta_value(): string {
		return $this->meta_value;
	}

	public function set_meta_value( string $meta_value ): void {
		$this->meta_value = $meta_value;
	}

	public function get_id(): int {
		return $this->meta_id;
	}

	public function has_changed(): bool {
		return $this->meta_id === null || $this->meta_id === 0;
	}

	public function set_last_inserted_id( int $id ): void {
		$this->meta_id = $id;
	}

}
