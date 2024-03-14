<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Guest;

use DateTimeInterface;
use WPDesk\ShopMagic\Components\Collections\ArrayCollection;
use WPDesk\ShopMagic\Components\Collections\Collection;
use WPDesk\ShopMagic\Customer\Customer;

final class Guest implements Customer {

	/** @var int|null */
	private $id;
	/** @var string */
	private $email;
	/** @var string */
	private $tracking_key;
	/** @var DateTimeInterface */
	private $created;
	/** @var DateTimeInterface */
	private $updated;
	/** @var Collection<string, GuestMeta> */
	private $meta;

	public function __construct() {
		$this->meta    = new ArrayCollection();
		$this->created = new \DateTimeImmutable();
		$this->updated = new \DateTimeImmutable();
	}

	public function get_email(): string {
		return $this->email ?? '';
	}

	public function set_email( string $email ): void {
		$this->email = $email;
	}

	public function get_tracking_key(): string {
		return $this->tracking_key;
	}

	public function set_tracking_key( string $tracking_key ): void {
		$this->tracking_key = $tracking_key;
	}

	public function get_created(): DateTimeInterface {
		return $this->created;
	}

	public function set_created( DateTimeInterface $created ): void {
		$this->created = $created;
	}

	public function get_updated(): DateTimeInterface {
		return $this->updated;
	}

	public function set_updated( DateTimeInterface $updated ): void {
		$this->updated = $updated;
	}

	/** @return Collection<string, GuestMeta> */
	public function get_meta(): Collection {
		return $this->meta;
	}

	/** @param Collection<string, GuestMeta> $meta */
	public function set_meta( Collection $meta ): void {
		$this->meta = $meta;
	}

	/**
	 * @param string|GuestMeta $meta
	 * @param string|null      $value
	 *
	 * @return void
	 */
	public function add_meta( $meta, string $value = null ): void {
		if ( is_string( $meta ) ) {
			if ( $this->meta->has( $meta ) ) {
				$meta = $this->meta->get( $meta );
			} else {
				$meta = new GuestMeta( $meta, $value );
			}
		}

		if ( $value !== null ) {
			$meta->set_meta_value( $value );
		}

		$this->meta->set( $meta->get_meta_key(), $meta );
	}

	public function get_meta_value( string $meta ): ?string {
		if ( $this->meta->has( $meta ) ) {
			return $this->meta->get( $meta )->get_meta_value();
		}

		$found_meta = $this->meta->find_first(
			static function ( $_, GuestMeta $m ) use ( $meta ) {
				return $m->get_meta_key() === $meta;
			} );

		if ( $found_meta ) {
			return $found_meta->get_meta_value();
		}

		return null;
	}

	public function get_id(): string {
		return 'g_' . $this->id;
	}

	/**
	 * Helper function as Guest needs special prefix during runtime to distinct it from
	 * registered users.
	 *
	 * @return int|null
	 */
	public function get_raw_id(): ?int {
		return $this->id;
	}

	public function set_id( ?int $id ): void {
		$this->id = $id;
	}

	/**
	 * @phpstan-assert-if-true !null $this->get_raw_id()
	 */
	public function exists(): bool {
		return isset( $this->id );
	}

	public function is_guest(): bool {
		return true;
	}

	public function get_username(): string {
		return $this->get_meta_value( 'username' ) ?? '';
	}

	public function get_first_name(): string {
		return $this->get_meta_value( 'first_name' ) ?? '';
	}

	public function get_last_name(): string {
		return $this->get_meta_value( 'last_name' ) ?? '';
	}

	public function get_full_name(): string {
		return $this->get_first_name() . ' ' . $this->get_last_name();
	}

	public function get_phone(): string {
		return $this->get_meta_value( 'billing_phone' ) ?? '';
	}

	public function get_language(): string {
		return $this->get_meta_value( 'shopmagic_user_language' ) ?? '';
	}
}
